<?php

class Game extends Controller {

	/**
	 * Show page to capture / show ranks from a game
	 * @param integer $round
	 * @param integer $table
	 */
	public function results($round, $table)
	{
		$this->getResults($round, $table);
		$this->_template->render();
	}

	/**
	 * Show page to capture full results from a game
	 * @param integer $round
	 * @param integer $table
	 */
	public function complete($round, $table)
	{
		$this->getResults($round, $table);
		$wonders = new Wonders();
		$this->_template->set('wonders', $wonders->getAll());
		$this->_template->render();
	}

	/**
	 * Get resultse for a game, and set template up
	 * @param integer $round
	 * @param integer $table
	 */
	private function getResults($round, $table)
	{
		$games = new Games();
		$this->_template->set('results', $games->getResults($round, $table));
		$this->_template->set('round', $round);
		$this->_template->set('table', $table);
	}

	/**
	 * Save posted ranks for a game
	 */
	public function results_post()
	{
		$games = new Games();

		// Check if the posted data is good
		$good = true;
		// Check there are no blank ranks
		foreach($_POST['rank'] AS $r)
		{
			if (trim($r) == '')
			{
				$this->_setMessage('Must set ranks for all players');
				$good = false;
				break;
			}
		}

		// Check the total points have been entered
		foreach($_POST['TempTotalPts'] AS $p)
		{
			if (trim($p) == '')
			{
				$this->_setMessage('Must enter game points for all players');
				$good = false;
				break;
			}
		}

		if ($good)
		{
			// Save each rank for each player
			foreach($_POST['rank'] AS $player => $rank)
			{
				$games->setRank($_POST['round'], $player, $rank, $_POST['TempTotalPts'][$player]);
			}
			$this->_setMessage('Results added successfully');
			$this->_redirect('');
		} else {
			// Redirect back to results screen
			$this->_redirect('game/results/'.$_POST['round'].'/'.$_POST['table'].'/');
		}
	}

	/**
	 * Save complete results
	 * Always save, only mark complete if valid
	 */
	public function complete_post()
	{
		$games = new Games();
		$config = Config::instance();

		// Check if the posted data is good
		$good = true;
		// Check no values are empty
		foreach(array_column($config->categoriesSubmit, 0) AS $f)
		{
			foreach($_POST[$f] AS $r)
			{
				if ($r == '')
				{
					$this->_setMessage('Must set values for all points');
					$good = false;
					break 2;
				}
			}
		}

		// Save each set of results
		foreach($_POST['MilitaryPts'] AS $player => $militaryPts)
		{
			$games->setPoints(
					$_POST['round'],
					$player,
					$_POST['MilitaryPts'][$player],
					$_POST['MoneyPts'][$player],
					$_POST['WonderPts'][$player],
					$_POST['CivilPts'][$player],
					$_POST['SciencePts'][$player],
					$_POST['CommercePts'][$player],
					$_POST['GuildsPts'][$player]
					);
		}

		// If the results are good, and we want to mark this as complete,
		// then do so
		if ($good && isset($_POST['Complete']))
		{
			$games->setComplete($_POST['round'], $_POST['table']);
			$this->_setMessage('Results added successfully');
			$this->_redirect('');
		}

		// Redirect back to the page if not marking as complete
		$this->_setMessage('Results saved');
		$this->_redirect('game/complete/'.$_POST['round'].'/'.$_POST['table'].'/');
	}

	/**
	 * Show the seating chart for a round
	 * @param integer $round
	 */
	public function seating($round)
	{
		$games = new Games();
		$players = new Players();

		$playerList = $players->getPresent();

		$tables = array();
		foreach($games->getSeating($round) AS $s)
		{
			$tables[$s['TableNum']][$s['SeatNum']] = array_merge($s, $playerList[$s['PlayerID']]);
		}

		$this->_template->set('round', $round);
		$this->_template->set('tables', $tables);

		$wonders = new Wonders();
		$this->_template->set('wonders', $wonders->getUsing());

		$this->_template->render();
	}

}
