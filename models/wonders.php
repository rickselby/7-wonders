<?php

class Wonders extends Model {

	// List of wonders
	var $wonders = ["Alexandria",
					"Babylon",
					"Ephesus",
					"Giza",
					"Halicarnassus",
					"Olympia",
					"Rhodes"];

	/**
	 * Construct the class
	 */
	public function __construct()
	{
		$config = Config::instance();
		parent::connectDB($config->sqlite_file);

		// Add wonders to the database if required
		$q = $this->db->query('SELECT * FROM Wonders');
		if (count($q->fetchAll()) == 0)
		{
			$iQ = $this->db->prepare('INSERT INTO Wonders (WonderName) VALUES (?)');

			foreach($this->wonders AS $w)
			{
				$iQ->execute($w);
			}
		}
	}

	/**
	 * Get all the wonders
	 * @return array
	 */
	public function getAll()
	{
		$q = $this->db->query(
				'SELECT WonderID, WonderName
				 FROM Wonders
				 ORDER BY WonderName ASC'
				);

		return $q->fetchKeyedArray();
	}

	/**
	 * Get the wonders being used
	 * @return array
	 */
	public function getUsing()
	{
		$q = $this->db->query(
				'SELECT WonderID, WonderName
				FROM Wonders
				WHERE WonderUse = 1
				ORDER BY WonderName ASC'
				);

		return $q->fetchKeyedArray();
	}

	/**
	 * Set which wonders are being used in this tournament
	 * Assign each wonder a random seat, and randomly choose A or B side
	 * @param array $wonders Array of WonderIDs
	 */
	public function setUsing($wonders)
	{
		$this->db->query('UPDATE Wonders SET WonderUse = 0');

		$seats = range(1, count($wonders));

		$q = $this->db->prepare(
				'UPDATE	Wonders
				SET		WonderUse = 1,
						WonderSide = ?,
						SeatNum = ?
				WHERE	WonderID = ?');
		foreach($wonders AS $id)
		{
			$side = mt_rand(1,2) == 1 ? 'A' : 'B';

			$seatID = array_rand($seats);
			$q->execute($side, $seats[$seatID], $id);
			unset($seats[$seatID]);
		}
	}

	public function areSet()
	{
		$q = $this->db->query('SELECT * FROM Wonders WHERE WonderUse != 0');
		$wonders = $q->fetchAll();
		return count($wonders) != 0;
	}

}
