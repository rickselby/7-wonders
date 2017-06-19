<?php

class Tournament extends Controller {

	public function __construct($template)
	{
		parent::__construct($template);
		$this->_setDefault('index');
	}

	/**
	 * Show the main index page
	 */
	public function index()
	{
		$games = new Games();
		$wonders = new Wonders();
		$players = new Players();

		$this->_template->set('gamesResults', $games->getWithoutResults());
		$this->_template->set('gamesComplete', $games->getIncomplete());
		$this->_template->set('nextRound', $games->getNextRound());
		$this->_template->set('maxRounds', $games->getMaxRounds());
		$this->_template->set('wondersSet', $wonders->areSet());
		$this->_template->set('playerCount', count($players->getPresent()));

		$config = Config::instance();
		$this->_template->set('categories', $config->categories);

		$this->_template->render();
	}

	/**
	 * Show the "set up next round" page
	 */
	public function round()
	{
		$games = new Games();
		// Can only show page if previous round is complete
		if ($games->isRoundComplete())
		{
			$this->_template->set('nextRound', $games->getNextRound());
			$players = new Players();
			$this->_template->set('playerCount', count($players->getPresent()));
			$wonders = new Wonders();
			$this->_template->set('wonders', $wonders->getUsing());
		} else {
			$this->_template->set('incomplete', true);
		}
		$this->_template->render();
	}

	/**
	 * Save details of the new round
	 */
	public function round_post()
	{
		$games = new Games();

		// Get the next round number
		$round = $games->getNextRound();

		// Get the list of players for each wonder
		$wonderList = array();
		foreach($games->getRoundResults($round) AS $p)
		{
			$wonderList[$p['WonderID']][] = $p['PlayerID'];
		}

		// Current standings
		$standings = Standings::current('rand');

		foreach($wonderList AS $playerList)
		{
			$table = 1;
			foreach($standings AS $s)
			{
				if (in_array($s['PlayerID'], $playerList))
				{
					$games->setTable($round, $s['PlayerID'], $table++);
				}
			}
		}

		$this->_setMessage('Round Created Successfully');
		$this->_redirect('');
	}

	/**
	 * Show the current standings
	 * @param string $sort [OPTIONAL] 'first' or 'last', default 'last'
	 */
	public function standings($sort = 'last')
	{
		$games = new Games();
		$this->_template->set('rounds', $games->getLastCompleteRound());
		$this->_template->set('standings', Standings::current($sort));
		$this->_template->set('maxRounds', $games->getMaxRounds());
		$this->_template->render();
	}

	/**
	 * Show the standings for a scoring category
	 * @param string $category
	 */
	public function scoring($category)
	{
		$config = Config::instance();
		// Check the requested category exists
		if (!in_array($category, array_column($config->categories, 0)))
		{
			$this->_setMessage('Invalid Category Requested');
			$this->_redirect('');
		}

		$games = new Games();
		$this->_template->set('rounds', $games->getLastCompleteResults());
		$this->_template->set('standings', Standings::scoring($category));

		$k = array_search($category, array_column($config->categories, 0));
		$this->_template->set('category', $config->categories[$k][1]);

		$this->_template->render();
	}

	/**
	 * Show the standings for the wonders
	 */
	public function wonders_standings()
	{
		$games = new Games();
		$this->_template->set('rounds', $games->getLastCompleteResults());
		$this->_template->set('standings', Standings::wonders());
		$this->_template->set('maxRank', $games->maxRank());

		$this->_template->render();
	}


	public function wonders()
	{
		$wonders = new Wonders();
		$this->_template->set('wonders', $wonders->getAll());

		$this->_template->render();
	}

	public function wonders_post()
	{
		$wonders = new Wonders();
		$players = new Players();
		$games = new Games();

		$wonders->setUsing($_POST['wonders']);

		// Now we assign them wonders to people...

		$wonderList = $_POST['wonders'];
		shuffle($wonderList);

		$playerList = $players->getPresent();

		$wonders = count($wonderList);
		$players = count($playerList);
		$tables = $players / $wonders;

		if ($tables !== intval($tables))
		{
			$this->_setMessage('Could not assign wonders - not an even number');
			$this->_template->render();
		}

		$assignments = Assign::Wonders($tables, $wonders);

		foreach($assignments AS $a)
		{
			for ($i = 0; $i < count($a); $i++)
			{
				// Get a random player
				$playerKey = array_rand($playerList);
				$playerID = $playerList[$playerKey]['PlayerID'];

				// For each wonder in this assignment list, assign to a round
				for ($j = 0; $j < count($a); $j++)
				{
					// Offset the wonders on each round for each player
					// using this assignment list
					$k = $a[(($i + $j)%count($a))];

					$games->setWonder($j+1, $wonderList[$k], $playerID);
				}

				unset($playerList[$playerKey]);
			}
		}
		$this->_setMessage('Wonders Set Successfully');
		$this->_redirect('');
	}

}