<?php

class Standings {

	/**
	 * Get tournament standings
	 * @param string $sort [OPTIONAL] 'first' or 'last', default 'last'
	 * @return array Ordered array of people & details
	 */
	public static function current($sort = 'last')
	{
		$games = new Games();
		$players = new Players();
		$config = Config::instance();

		// Get a list of players and set up default values
		$results = array();
		foreach($players->getPresent() AS $p)
		{
			$results[$p['PlayerID']] =
						array_merge($p, [
				'TotalPoints' => 0,
				'Points' => [],
				'Ranks' => [1 => 0,0,0,0,0,0,0],
				'Results' => [],
				'RankStr' => '',
				'GamePts' => 0,
			]);
		}

		// Step through each complete round
		for($i = 1; $i <= $games->getLastCompleteRound(); $i++)
		{
			// Get the results for the round
			foreach($games->getRoundResults($i) AS $r)
			{
				// Sum points
				$results[$r['PlayerID']]['TotalPoints'] += $config->points[$r['Rank']];
				// Set points for this round
				$results[$r['PlayerID']]['Points'][$i] = $config->points[$r['Rank']];
				// Sum ranks
				$results[$r['PlayerID']]['Ranks'][$r['Rank']]++;
				// Set rank for this round
				$results[$r['PlayerID']]['Results'][$i] = $r['Rank'];
				// Total Points
				$results[$r['PlayerID']]['GamePts'] += $r['TotalPts'];

				// Regenerate the rank string (for sorting)
				$results[$r['PlayerID']]['RankStr'] = self::makeRankStr(
						$results[$r['PlayerID']]['TotalPoints'],
						$results[$r['PlayerID']]['Ranks'],
						$results[$r['PlayerID']]['GamePts']);
			}
		}

		// Sort by RankStr
		usort($results, function(&$a, &$b) use ($sort) {
			if ($a['RankStr'] != $b['RankStr'])
			{
				return $a['RankStr'] < $b['RankStr'] ? 1 : -1;
			} else {
				return Sorting::byName($a, $b, $sort);
			}
		});

		return $results;
	}

	/**
	 * Make a RankStr for tournament standings
	 * @param integer $totalPoints
	 * @param array $ranks
	 * @return string
	 */
	private static function makeRankStr($totalPoints, $ranks, $gamePoints)
	{
		// Most significant value goes first (bigger is better, so subtract from
		// the largest number that'll fit), then the next significant, etc.
		return sprintf('%03d%02d%02d%02d%02d%02d%02d%02d%03d',
				999-$totalPoints,	$ranks[1], $ranks[2], $ranks[3], $ranks[4],
				 $ranks[5], $ranks[6], $ranks[7], $gamePoints);
	}

	/**
	 * Get standings for a scoring category
	 * @param string $category
	 * @return array|false Ordered array of people & details, or false if error
	 */
	public static function scoring($category)
	{
		$games = new Games();
		$players = new Players();
		$config = Config::instance();

		// Check it's a valid category
		if (!in_array($category, array_column($config->categories, 0)))
		{
			return false;
		}

		// Get a list of players and set up default values
		$results = array();
		foreach($players->getPresent() AS $p)
		{
			$results[$p['PlayerID']] =
						array_merge($p, [
				'TotalPoints' => 0,
				'Points' => [],
				'RankStr' => '',
			]);
		}

		// Step through each complete round
		for($i = 1; $i <= $games->getLastCompleteResults(); $i++)
		{
			// Get the results for this round
			foreach($games->getRoundResults($i) AS $r)
			{
				// Sum the total points for this category
				$results[$r['PlayerID']]['TotalPoints'] += $r[$category];
				// Set the points for this category in this round
				$results[$r['PlayerID']]['Points'][$i] = (int) $r[$category];

				// Regenerate the RankStr
				$results[$r['PlayerID']]['RankStr'] =
						// Most significant: total points
						// Next: highest score
						sprintf('%04d%04d',
								$results[$r['PlayerID']]['TotalPoints'],
								max($results[$r['PlayerID']]['Points']));
			}
		}

		// Sort by RankStr
		usort($results, function(&$a, &$b) {
			if ($a['RankStr'] != $b['RankStr'])
			{
                            if ($a['TotalPoints'] != $b['TotalPoints'])
                            {
                                return $a['TotalPoints'] < $b['TotalPoints'] ? 1 : -1;
                            } else {
                                return max($a['Points']) < max($b['Points']) ? 1 : -1;
                            }
#				return $a['RankStr'] < $b['RankStr'] ? 1 : -1;
			} else {
				return Sorting::byName($a, $b);
			}
		});

		return $results;
	}

	/**
	 * Get standings for the wonders
	 * @return array Ordered array of wonders & details
	 */
	public static function wonders()
	{
		$games = new Games();
		$wonders = new Wonders();

		// Get a list of wonders and set up default values
		$results = array();
		foreach($wonders->getAll() AS $k => $w)
		{
			$results[$k] = [
				'WonderName' => $w,
				'TotalPoints' => 0,
				'Games' => 0,
				'AveragePoints' => 0,
				'Ranks' => [1 => 0,0,0,0,0,0,0],
				'RankStr' => ''
			];
		}

		// Step through each completed round
		for($i = 1; $i <= $games->getLastCompleteResults(); $i++)
		{
			// Get the results for each round
			foreach($games->getRoundResults($i) AS $r)
			{
				// Sum the total points
				$results[$r['WonderID']]['TotalPoints'] += $r['TotalPts'];
				// Sum the ranks
				$results[$r['WonderID']]['Ranks'][$r['Rank']]++;
				// Increment the games played
				$results[$r['WonderID']]['Games']++;

				// Recalculate average points
				$results[$r['WonderID']]['AveragePoints'] =
						$results[$r['WonderID']]['TotalPoints']
						/ $results[$r['WonderID']]['Games'];

				// Regenerate RankStr
				$results[$r['WonderID']]['RankStr'] =
						self::makeWonderRankStr(
								$results[$r['WonderID']]['AveragePoints'],
								$results[$r['WonderID']]['Ranks'],
								true
								);
			}
		}

		// Sort by Average Points, RankStr
		usort($results, function(&$a, &$b) {
			// Although average points are in the RankStr, this does it to a higher
			// number of decimal points
			if ($a['AveragePoints'] != $b['AveragePoints'])
			{
				return $a['AveragePoints'] < $b['AveragePoints'] ? 1 : -1;
			} elseif ($a['RankStr'] != $b['RankStr']) {
				return $a['RankStr'] < $b['RankStr'] ? 1 : -1;
			} else {
				return 0;
			}
		});

		return $results;
	}

	/**
	 * Make a RankStr for tournament standings
	 * @param float $avPts
	 * @param array $ranks
	 * @return string
	 */
	private static function makeWonderRankStr($avPts, $ranks)
	{
		// Most significant value goes first, then the next significant, etc.
		return sprintf('%07.3f%03d%03d%03d%03d%03d%03d%03d',
				$avPts,	$ranks[1], $ranks[2], $ranks[3], $ranks[4],
				 $ranks[5], $ranks[6], $ranks[7]);
	}

}
