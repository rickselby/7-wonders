<?php

/**
 * It's a singleton. Shoot me.
 */

class Config {

	private static $instance;
	protected $vars = array();

	/**
	 * Get an instance of the config class
	 * 
	 * @return Config
	 */
	public static function instance()
	{
		if (!isset(self::$instance))
		{
			self::$instance = new Config;
		}
		return self::$instance;
	}

	public function __set($name, $value)
	{
		$this->vars[$name] = $value;
	}

	public function __get($name)
	{
		return $this->vars[$name];
	}

	public function __isset($name)
	{
		return isset($this->vars[$name]);
	}

	public function __unset($name)
	{
		unset($this->vars[$name]);
	}

	/**
	 * Stop the regular initialisation of the singleton
	 */
	private function __construct()
	{

	}

	public function __clone()
	{
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

	public function __wakeup()
	{
		trigger_error('Unserializing is not allowed.', E_USER_ERROR);
	}

}
