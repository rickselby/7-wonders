<?php

/**
 * HTML helpers
 */
class HTML {

	/**
	 * Create a list of options for a SELECT from an array
	 * If each item is an array, set the value/label keys within the array
	 *
	 * @param mixed[] $list List of options
	 * @param string $value [optional] Key for value
	 * @param string $label [optional] Key for label
	 * @param mixed $selected [optional] Value currently selected
	 *
	 * @return string HTML output
	 */
	public static function optionList($list, $value = NULL, $label = NULL, $selected = NULL)
	{
		$options = '';
		foreach($list AS $row)
		{
			$options .= '<option value="'.($value ? $row[$value] : $row).'"';
			if (is_array($selected)
					? in_array($value ? $row[$value] : $row, $selected)
					: ($selected !== NULL ? ($value ? $row[$value] : $row) == $selected : false))
			{
				$options .= ' selected="selected"';
			}
			$options .= '>'.($label ? $row[$label] : $row).'</option>';
		}
		return $options."\n";
	}

	/**
	 * Create a list of options for a SELECT from an array, using the key as the value
	 *
	 * @param mixed[] $list List of (value => label) pairs
	 * @param mixed $selected [optional] Value currently selected
	 *
	 * @return string HTML output
	 */
	public static function keyedOptionList($list, $selected = NULL)
	{
		$options = '';
		foreach($list AS $value => $label)
		{
			$options .= '<option value="'.$value.'"';
			if (is_array($selected)
					? in_array($value, $selected)
					: ($selected !== NULL ? $value == $selected : false))
			{
				$options .= ' selected="selected"';
			}
			$options .= '>'.$label.'</option>';
		}
		return $options."\n";
	}


	/**
	 * Create a list of options for a SELECT from an array of usernames
	 *
	 * @param string[] $list List of usernames
	 * @param string $selected [optional] Current username selected
	 *
	 * @return string HTML output
	 */
	public static function userOptionList($list, $selected = NULL)
	{
		// Make sure the selected user is in the list...
		if ($selected != NULL && !in_array($selected, $list))
		{
			$list[] = $selected;
		}

		$list = User::sortBySurname($list);

		$options = '';
		foreach($list AS $row)
		{
			$options .= '<option value="'.$row.'"';
			if ($row == $selected)
			{
				$options .= ' selected="selected"';
			}
			$options .= '>'.User::realnameOrUsername($row).'</option>';
		}
		return $options."\n";
	}

	/**
	 * Basic markup of text - new lines to <br /> and bulleted lists
	 *
	 * @param string $text Text to change
	 * @param array $allowedHTML [optional] Array of HTML tags allowed
	 *
	 * @return string
	 */
	public static function listMarkUp($text, $allowedHTML = '')
	{
		// Add a line break at the end if there's not already one
		if (substr($text, -1) != "\n")
		{
			$text .= "\n";
		}

		if (is_array($allowedHTML))
		{
			$allowedHTML = implode('', $allowedHTML);
		}

		// Remove HTML
		$text = strip_tags($text, $allowedHTML);
		// Sanitise line breaks
		$text = str_replace(array("\n", "\r\n", "\r"), "\n", $text);

		// Add a line break at the start if there's a bullet as the first item
		if (substr($text, 0, 1) == '*')
		{
			$text = "\n".$text;
		}

		// Capture the bulleted lists and replace them with HTML
		$text = preg_replace_callback('/(?:\n\*.*)+\n/',
				function ($match)
				{
					// Yes this could all be one line but it's readable this way
					$str = str_replace(
										array("\n", '* '),
										array('','*'),
										$match[0]
										);
					$str = substr($str, 1);
					$str = '<ul><li>'
							.str_replace('*','</li><li>', $str)
							.'</li></ul>';
					return $str;
				}, $text);

		// Convert line breaks to <br /> except around lists
		$text = preg_replace('/(?<!<\/ul>)\n(?!<ul>)/', "\n<br />", $text);

		return $text;
	}

	/**
	 * Take a block of text and convert URLs to links
	 * (Andrew's original function)
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	static public function linkify($text)
	{
	   if (preg_match("_(http(s?)://|www\.)_",$text))
	   {
		   return preg_replace("_(^|\s)(?:(http(s?)://)|(www\.))([\S]+)(\s|$)_","$1<a href=\"http$3://$4$5\">$2$4$5</a>$6",$text);
	   } else {
		   return($text);
	   }
	}


}
