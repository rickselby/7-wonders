<?php

class General {

	/**
	 * Map a function recursively through an array
	 *
	 * @param callback $callback Function to map
	 * @param mixed[] $array Array to map function to
	 *
	 * @return mixed[]
	 */
	static public function array_map_recursive($callback, $array)
	{
		$b = array();
		foreach ($array as $key => $value)
		{
			$b[$key] = is_array($value)
						? self::array_map_recursive($callback, $value)
						: call_user_func($callback, $value);
		}
		return $b;
	}

	/**
	 * Show the nice name for an error
	 *
	 * @param integer $type Error Code
	 *
	 * @return string
	 */
	static public function FriendlyErrorType($type)
	{
		switch($type)
		{
			case E_ERROR:
				return 'E_ERROR';
			case E_WARNING:
				return 'E_WARNING';
			case E_PARSE:
				return 'E_PARSE';
			case E_NOTICE:
				return 'E_NOTICE';
			case E_CORE_ERROR:
				return 'E_CORE_ERROR';
			case E_CORE_WARNING:
				return 'E_CORE_WARNING';
			case E_CORE_ERROR:
				return 'E_COMPILE_ERROR';
			case E_CORE_WARNING:
				return 'E_COMPILE_WARNING';
			case E_USER_ERROR:
				return 'E_USER_ERROR';
			case E_USER_WARNING:
				return 'E_USER_WARNING';
			case E_USER_NOTICE:
				return 'E_USER_NOTICE';
			case E_STRICT:
				return 'E_STRICT';
			case E_RECOVERABLE_ERROR:
				return 'E_RECOVERABLE_ERROR';
			case E_DEPRECATED:
				return 'E_DEPRECATED';
			case E_USER_DEPRECATED:
				return 'E_USER_DEPRECATED';
		}
		return '';
	}

	/**
	 * Change a variable to NULL if it is an empty string
	 *
	 * @param string $var Variable
	 *
	 * @return string|null
	 */
	static public function emptyIsNull($var)
	{
		return $var === '' ? NULL : $var;
	}

	/**
	 * Get the maximum upload size for the server in a readable format
	 *
	 * @return string
	 */
	static public function getMaxUploadSize()
	{
		$displayMaxSize = ini_get('post_max_size');

		switch ( substr($displayMaxSize,-1) )
		{
		  case 'G':
			$displayMaxSize = $displayMaxSize * 1024;
		  case 'M':
			$displayMaxSize = $displayMaxSize * 1024;
		  case 'K':
			$displayMaxSize = $displayMaxSize * 1024;
		}

		return self::formatBytes($displayMaxSize);
	}

	/**
	 * Format a value of bytes in a readable format
	 *
	 * @param int $bytes Bytes
	 * @param int $precision [optional] Precision to round to [defaults to 2]
	 *
	 * @return string
	 */
	static public function formatBytes($bytes, $precision = 2)
	{
		$units = array('B', 'KiB', 'MiB', 'GiB', 'TiB');

		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);

		$bytes /= (1 << (10 * $pow));

		return round($bytes, $precision) . $units[$pow];
	}

	/**
	 * Add 'th','nd','st' to a number as required
	 *
	 * @param int $num Number
	 *
	 * @return string
	 */
	static public function ordinalSuffix($num)
	{
		$units = $num % 10 ;
		$tens = $num % 100 ; // needed for 11th 12th 13th special cases

		if (!is_numeric($num))
		{
			return false;
		}

		switch ($units)
		{
			case 1 :
				if ($tens == 11)
				{
					return $num.'th';
				} else {
					return $num.'st';
				}
			case 2 :
				if ($tens == 12)
				{
					return $num.'th';
				} else {
					return $num.'nd';
				}
			case 3 :
				if ($tens == 13)
				{
					return $num.'th';
				} else {
					return $num.'rd';
				}
			default:
				return $num.'th';
		}
	}

	/**
	 * Format a framework path to point directly to the index.php
	 * @param string $path
	 * @return string
	 */
	static public function frameworkLink($path)
	{
		return 'index.php?url='.$path;
	}
}
