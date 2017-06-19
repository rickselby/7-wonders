<?php

class Sorting {

	/**
	 * Function for returning sorting by name (either first, last or last,first)
	 * @param array $a First item to sort
	 * @param array $b Second item to sort
	 * @param string $order [OPTIONAL] 'first' or 'last', defaults to 'last'
	 * @return integer
	 */
	static public function byName($a, $b, $order = 'last')
	{
		if ($order == 'rand')
		{
			return (rand(1,10) > 5 ? 1 : -1);
		}

		// Set up the order the keys will be checked
		if ($order == 'first')
		{
			$keys = ['FirstName', 'LastName'];
		} else {
			$keys = ['LastName', 'FirstName'];
		}

		return ($a[$keys[0]] == $b[$keys[0]])
					? (
						($a[$keys[1]] == $b[$keys[1]])
							? 0 // Both names are identical
							: (($a[$keys[1]] > $b[$keys[1]]) ? 1 : -1)
					 )
					: (($a[$keys[0]] > $b[$keys[0]]) ? 1 : -1);
	}
}
