<?php

class Games extends Model {

	// 'Cache' some queries and results to try to improve speed
	private $queries = [];
	private $results = [];

	public function __construct()
	{
		$config = Config::instance();
		parent::connectDB($config->sqlite_file);
	}

	/**
	 * Set which player will use the given wonder in a round
	 * @param integer $round
	 * @param integer $wonder WonderID
	 * @param integer $player PlayerID
	 */
	public function setWonder($round, $wonder, $player)
	{
		// Set up the query on the first run
		if (!isset($this->queries['setWonder']))
		{
			$this->queries['setWonder'] = $this->db->prepare(
				'INSERT INTO Games (RoundNum, WonderID, PlayerID)
					VALUES(?, ?, ?)');
		}

		$this->queries['setWonder']->execute($round, $wonder, $player);
	}

	/**
	 * Set a players' table for a round
	 * @param integer $round
	 * @param integer $player PlayerID
	 * @param integer $table
	 */
	public function setTable($round, $player, $table)
	{
		// Set up the query on the first run
		if (!isset($this->queries['setTable']))
		{
			$this->queries['setTable'] = $this->db->prepare(
				'UPDATE Games SET TableNum = ?
					WHERE RoundNum = ? AND PlayerID = ?');
		}

		$this->queries['setTable']->execute($table, $round, $player);
	}


	/**
	 * Get the current round
	 * @return integer
	 */
	public function getRound()
	{
		if (!isset($this->results['getRound']))
		{
			$q = $this->db->query(
					'SELECT MAX(RoundNum) FROM Games WHERE TableNum IS NOT NULL'
					);
			$this->results['getRound'] = $q->fetchColumn();
		}
		return $this->results['getRound'];
	}

	/**
	 * Get the total number of rounds to be played
	 * @return integer
	 */
	public function getMaxRounds()
	{
		if (!isset($this->results['maxRounds']))
		{
			$q = $this->db->query('SELECT MAX(RoundNum) FROM Games');
			$this->results['maxRounds'] = $q->fetchColumn();
		}
		return $this->results['maxRounds'];
	}

	/**
	 * Get the next round
	 * @return integer
	 */
	public function getNextRound()
	{
		return $this->getRound() + 1;
	}

	/**
	 * Get the round number of the last round with results
	 * @return integer
	 */
	public function getLastCompleteRound()
	{
		// As we can't start a round until the results are in, we only need
		// to check this round and last round
		return
			($this->isRoundComplete()
				?	$this->getRound()
				:	$this->getRound() - 1);
	}

	/**
	 * Get the last round with complete results
	 * @return integer
	 */
	public function getLastCompleteResults()
	{
		// Only calculate this once
		if (!isset($this->results['getLastCompleteRound']))
		{
			// See where there are incomplete games
			$q = $this->db->query(
					'SELECT RoundNum
					 FROM Games
					 WHERE Complete IS NULL AND TableNum IS NOT NULL
					 GROUP BY RoundNum
					 ORDER BY RoundNum ASC
					 LIMIT 1');
			$r = $q->fetchAll();
			if (count($r) == 0)
			{
				// No rounds with incomplete results, so use the current round
				$this->results['getLastCompleteRound'] = $this->getRound();
			} else {
				// We got a round with incomplete results; so use the previous
				// round
				$this->results['getLastCompleteRound'] = $r[0]['RoundNum'] - 1;
			}
		}

		return $this->results['getLastCompleteRound'];
	}

	/**
	 * Check if the current round is complete
	 * @return boolean
	 */
	public function isRoundComplete()
	{
		return (count($this->getWithoutResults()) == 0);
	}

	/**
	 * Get a list of games without ranks entered
	 * @return array
	 */
	public function getWithoutResults()
	{
		if (!isset($this->results['getWithoutResults']))
		{
			$q = $this->db->query(
					'SELECT RoundNum, TableNum
					FROM Games
					WHERE Rank IS NULL AND TableNum IS NOT NULL
					GROUP BY RoundNum, TableNum'
					);
			$this->results['getWithoutResults'] = $q->fetchAll();
		}
		return $this->results['getWithoutResults'];
	}

	/**
	 * Get a list of results for a given round/table
	 * @param integer $round
	 * @param integer $table
	 * @return array
	 */
	public function getResults($round, $table)
	{
		if (!isset($this->queries['getResults']))
		{
			$this->queries['getResults'] = $this->db->prepare(
				'SELECT
					SeatNum,
					PlayerID,
					FirstName || " " || LastName AS FullName,
					Games.WonderID,
					WonderName,
					Rank,
					TempTotalPts,
					MilitaryPts,
					MoneyPts,
					WonderPts,
					CivilPts,
					SciencePts,
					CommercePts,
					GuildsPts
				FROM
					Games
				INNER JOIN Players USING (PlayerID)
				LEFT JOIN Wonders USING (WonderID)
				WHERE
						RoundNum = ?
					AND TableNum = ?
				ORDER BY SeatNum ASC');
		}
		$this->queries['getResults']->execute($round, $table);
		return $this->queries['getResults']->fetchAll();
	}

	/**
	 * Get results for a full round
	 * @param integer $round
	 * @return array
	 */
	public function getRoundResults($round)
	{
		if (!isset($this->queries['getRoundResults']))
		{
			$this->queries['getRoundResults'] = $this->db->prepare(
				'SELECT
					PlayerID,
					WonderID,
					Rank,
					MilitaryPts,
					MoneyPts,
					WonderPts,
					CivilPts,
					SciencePts,
					CommercePts,
					GuildsPts,
					TotalPts
				FROM
					GamesFull
				WHERE
					RoundNum = ?');
		}
		$this->queries['getRoundResults']->execute($round);
		return $this->queries['getRoundResults']->fetchAll();
	}

	/**
	 * Set a players' rank for a game
	 * @param integer $round
	 * @param integer $player
	 * @param integer $rank
	 */
	public function setRank($round, $player, $rank, $tmpTotalPts)
	{
		if (!isset($this->queries['setRank']))
		{
			$this->queries['setRank'] = $this->db->prepare(
				'UPDATE Games SET
					Rank = ?,
					TempTotalPts = ?
				WHERE
						RoundNum = ?
					AND PlayerID = ?');
		}
		$this->queries['setRank']->execute($rank, $tmpTotalPts, $round, $player);
	}

	/**
	 * Get a list of games that are not marked as complete
	 * @return array
	 */
	public function getIncomplete()
	{
		$q = $this->db->query(
				'SELECT RoundNum, TableNum
				FROM Games
				WHERE Complete IS NULL
					AND Rank IS NOT NULL
				GROUP BY RoundNum, TableNum');
		return $q->fetchAll();
	}

	/**
	 * Set full results for a player in a round
	 * @param integer $round
	 * @param integer $player
	 * @param integer $wonderID WonderID
	 * @param integer $military
	 * @param integer $money
	 * @param integer $wonderPts
	 * @param integer $civil
	 * @param integer $science
	 * @param integer $commerce
	 * @param integer $guild
	 */
	public function setPoints($round, $player, $military, $money,
			$wonderPts,	$civil, $science, $commerce, $guild)
	{
		if (!isset($this->queries['setPoints']))
		{
			$this->queries['setPoints'] = $this->db->prepare(
				'UPDATE Games SET
					MilitaryPts = ?,
					MoneyPts = ?,
					WonderPts = ?,
					CivilPts = ?,
					SciencePts = ?,
					CommercePts = ?,
					GuildsPts = ?
				WHERE
						RoundNum = ?
					AND	PlayerID = ?');
		}
		$this->queries['setPoints']->execute(
				$military, $money, $wonderPts, $civil, $science, $commerce,
				$guild, $round, $player
				);
	}

	/**
	 * Mark a games' results as complete
	 * @param integer $round
	 * @param integer $table
	 */
	public function setComplete($round, $table)
	{
		$q = $this->db->prepare(
				'UPDATE Games
					SET	Complete = 1
					WHERE	RoundNum = ?
						AND	TableNum = ?');
		$q->execute($round, $table);
	}

	/**
	 * Get seating details for a round
	 * @param integer $round
	 * @return array
	 */
	public function getSeating($round)
	{
		if (!isset($this->queries['getSeating']))
		{
			$this->queries['getSeating'] = $this->db->prepare(
				'SELECT
					TableNum,
					SeatNum,
					WonderName,
					WonderSide,
					PlayerID
				FROM
					GamesFull
				WHERE
					RoundNum = ?
				ORDER BY
					TableNum ASC,
					SeatNum ASC'
				);
		}
		$this->queries['getSeating']->execute($round);
		return $this->queries['getSeating']->fetchAll();
	}

	/**
	 * Check if there are games in the system
	 * @return boolean
	 */
	public function areGames()
	{
		$q = $this->db->query('SELECT * FROM Games');
		return (count($q->fetchAll()) != 0);
	}

	/**
	 * Get the maximum rank possible
	 * (Only works once the first round is set up)
	 * @return integer
	 */
	public function maxRank()
	{
		$q = $this->db->query('SELECT MAX(Rank) FROM Games WHERE TableNum IS NOT NULL');
		return $q->fetchColumn();
	}

	public function clearRound($round)
	{
		$q = $this->db->prepare('DELETE FROM Games WHERE RoundNum = ?');
		$q->execute($round);
	}

}