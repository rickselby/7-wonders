<?php

class Players extends Model {

	public function __construct()
	{
		$config = Config::instance();
		parent::connectDB($config->sqlite_file);
	}

	/**
	 * Get details of all the players
	 * @return array
	 */
	public function getAll()
	{
		$q = $this->db->query(
				'SELECT	PlayerID, PlayerID, FirstName, LastName, Paid, Arrived,
						FirstName || " " || LastName AS FullName
				 FROM	Players'
				);
		return $q->fetchKeyedArray();
	}

	/**
	 * Get details of all players that have arrived
	 * @return array
	 */
	public function getPresent()
	{
		$q = $this->db->query(
				'SELECT	PlayerID, PlayerID, FirstName, LastName, Paid, Arrived,
						FirstName || " " || LastName AS FullName
				 FROM	Players
				 WHERE	Arrived = 1'
				);
		return $q->fetchKeyedArray();

	}

	/**
	 * Add a new player
	 * @param string $first
	 * @param string $last
	 */
	public function add($first, $last)
	{
		$q = $this->db->prepare(
				'INSERT INTO Players (FirstName, LastName)
					VALUES (?, ?)'
				);
		$q->execute($first, $last);
	}

	/**
	 * Update details for a player
	 * @param integer $id PlayerID
	 * @param string $first
	 * @param string $last
	 * @param boolean $paid
	 * @param boolean $arrived
	 */
	public function setDetails($id, $first, $last, $paid, $arrived)
	{
		$q = $this->db->prepare(
				'UPDATE Players SET
					FirstName = ?,
					LastName = ?,
					Paid = ?,
					Arrived = ?
					WHERE PlayerID = ?'
				);
		$q->execute(
				$first,
				$last,
				($paid ? 1 : 0),
				($arrived ? 1 : 0),
				$id
				);
	}

}
