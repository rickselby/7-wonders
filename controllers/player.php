<?php

class Player extends Controller {

	/**
	 * Save details for a new player
	 */
	public function add_post()
	{
		$players = new Players();
		$players->add($_POST['FirstName'], $_POST['LastName']);
		$this->_setMessage('Player Added Successfully');
		$this->_redirect('player/manage/');
	}

	/**
	 * Show player management page
	 */
	public function manage()
	{
		$players = new Players();
		$this->_template->set('players', $players->getAll());
		$games = new Games();
		$this->_template->set('games', $games->areGames());
		$this->_template->render();
	}

	/**
	 * Get a list of players in JSON format
	 * @param string $sort [OPTIONAL] 'first' or 'last', default 'last'
	 */
	public function get($sort = 'last')
	{
		$list = Standings::current($sort);
		$this->_template->json($list);
	}

	/**
	 * Save updates to player information
	 */
	public function update_post()
	{
		$players = new Players();
		$playerList = $players->getAll();
		$games = new Games();
		$areGames = $games->areGames();
		foreach($_POST['FirstName'] AS $id => $fName)
		{
			$players->setDetails($id,
					$_POST['FirstName'][$id],
					$_POST['LastName'][$id],
					// Don't allow changes to Paid/Arrived after games started
					$areGames
						? $playerList[$id]['Paid']
						: isset($_POST['Paid'][$id]),
					$areGames
						? $playerList[$id]['Arrived']
						: isset($_POST['Arrived'][$id])
					);
		}
		$this->_setMessage('Players Updated Successfully');
		$this->_redirect('player/manage/');
	}

	/**
	 * TESTING FUNCTION
	 * Add some random players to the database
	 */
	public function fill_post()
	{
		$players = new Players();
		for($i = 1; $i <= $_POST['amount']; $i++)
		{
			$p = RandomNames::getName();
			$players->add($p['FirstName'], $p['LastName']);
		}
		foreach($players->getAll() AS $id => $p)
		{
			$players->setDetails($id,
					$p['FirstName'],
					$p['LastName'],
					true, true);
		}
		$this->_setMessage('Added lots of random names');
		$this->_redirect('player/manage/');
	}

}
