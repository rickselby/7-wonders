<?php

/**
 * Having considered the best ways of sharing a datbase connection between
 * models, I failed to come up with a better way than the singleton pattern.
 * Direct Injection is good, but the database object would have to be injected
 * in the controller, and so visible in the controller. I like not having to
 * inject stuff all over the place, and let it all be sorted out behind the
 * scenes (well, in the model, but still, that's where it's important).
 *
 * I've actually gone with a mutiton setup, in case any projects require two
 * different database connections.
 */

class db {

	private static $instance = array();

	/**
	 * Get a database connection
	 *
	 * @param string $file		Path to SQLite file
	 *
	 * @return PDO
	 */
    public static function getInstance($file)
    {
    	if (empty(self::$instance[$file]))
		{
			try {
				self::$instance[$file]
						= new PDO('sqlite:'.$file);

				// Fixes bug with PDO::query() not working on certain SELECT
				// statements
				self::$instance[$file]->setAttribute(
						PDO::ATTR_EMULATE_PREPARES, true);
				self::$instance[$file]->setAttribute(
						PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				// Use the extended PDO statement class for statements
				self::$instance[$file]->setAttribute(
						PDO::ATTR_STATEMENT_CLASS,
						array('Extend_PDOStatement', array()));
				self::$instance[$file]->setAttribute(
						PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

				self::createTables($file);
			} catch (Exception $e) {

				self::serverError($e);
				die();
			}
     	}
      	return self::$instance[$file];
   	}

	/**
	 * Disable __construct
	 */
	private function __construct() { }

	/**
	 * Error to show if MySQL is down / unreachable
	 */
	static private function serverError($e)
	{
		echo '<h1>SQLite Error</h1>';
		echo '<p>There was an error connecting to the SQLite file.';
		echo '<p>'.$e->getMessage().'</p>';
	}

	/**
	 * Create tables listed in the config file
	 * @param string $file
	 */
	static private function createTables($file)
	{
		$config = Config::instance();
		foreach($config->sqlite_table AS $t)
		{
			self::$instance[$file]->exec($t);
		}
	}
}

/**
 * Extend PDOStatement to add new functionality
 */
class Extend_PDOStatement extends PDOStatement
{
	/**
	 * Not sure why this is required, but it is. Meh.
	 */
	private function __construct()	{}

	/**
	 * Override execute - allow input parameters to come as parameters, not in
	 * an array
	 */
	public function execute($bound_input_params = null)
	{
		if (func_num_args() == 0
				|| (func_num_args() == 1
					&& is_array($bound_input_params)))
		{
			parent::execute($bound_input_params);
		} else {
			// multiple parameters passed
			// pass them as an array
			parent::execute(func_get_args());
		}
	}

	/**
     * Return an array with the keys as the first field from the SQL query
     *
     * ie: 'SELECT id, name, dob FROM table'
     * becomes
     * array(id => array(name, dob), id => array(name, dob)...);
     *
     * @return array
	 */
	public function fetchKeyedArray()
	{
		$arr = $this->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);
		$arr = array_map('reset', $arr);
		$keys = array_keys($arr);
		// if there's only one item in the lower arrays, we might as well
		// remove the array element
		if (count($keys) && count($arr[$keys[0]]) == 1)
		{
			$arr = array_map('reset', $arr);
		}

		return $arr;
	}
}
