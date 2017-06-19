<?php

class Model {

	/** @var PDO Database connection, if connected **/
	protected $db;

	/**
	 * Connect to a database
	 *
	 * @param string $host		MySQL server
	 * @param string $database	Database
	 * @param string $username	Username
	 * @param string $password	Password
	 */
	protected function connectDB($file)
	{
		$this->db = db::getInstance($file);
	}

}