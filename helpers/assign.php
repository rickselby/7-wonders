<?php

class Assign {

	static $lists;

	public static function Wonders($tables, $seats)
	{
		// rounds == seats

		// Get a list of all orders of wonders starting with '1'
		self::$lists = [];
		self::makeArray($seats);

		// Randomise the lists
		shuffle(self::$lists);

		// Get the size of the list
		$listSize = count(self::$lists);

		// If we can't create enough lists for the table, stop now
		if ($listSize < $tables)
			return false;

		// Save the original list
		$firstList = self::$lists;

		// Get rid of any that have the same numbers in a row
		for ($k1 = 0; $k1 <= $listSize; $k1++)
		{
			if (!isset(self::$lists[$k1]))
				continue;

			for($k2 = 0; $k2 <= $listSize; $k2++)
			{
				if ($k1 == $k2)
					continue;

				if (!isset(self::$lists[$k2]))
					continue;

				for($i = 0; $i < count(self::$lists[$k1]) - 1; $i++)
				{
					if ((self::$lists[$k1][$i] == self::$lists[$k2][$i])
							&& (self::$lists[$k1][$i+1] == self::$lists[$k2][$i+1]))
					{
						unset(self::$lists[$k2]);
						continue 2;
					}
				}
			}
		}

		if ($tables == count(self::$lists))
		{
			shuffle(self::$lists);
			return self::$lists;
		} elseif ($tables < count(self::$lists)) {
			shuffle(self::$lists);
			return array_slice(self::$lists, 0, $tables);
		} else {
			// not enough in list
			$output = [];
			// get all the good ones
			foreach(self::$lists AS $k => $l)
			{
				$output[] = $l;
				unset($firstList[$k]);
			}
			echo count($output).' '.$tables;
			// randomly pick more as we need them
			for($i = count($output); $i < $tables; $i++)
			{
				$k = array_rand($firstList);
				$output[] = $firstList[$k];
				unset($firstList[$k]);
			}
			return $output;
		}

	}

	public static function makeArray($num, $arr = [])
	{
		if (count($arr) == $num)
		{
			self::$lists[] = $arr;
		}

		if ($arr === [])
		{
			self::makeArray($num, [0]);
		} else {
			for ($i = 1; $i < $num; $i++)
			{
				if (!in_array($i, $arr))
				{
					$a1 = $arr;
					array_push($a1, $i);
					self::makeArray($num, $a1);
				}
			}
		}
	}


}