<?php

/**
 * Turn on full error reporting in my testing directory
 */
error_reporting(E_ALL);
define('WEBMASTER_TESTING', true);

// Set locale stuff correctly
// DO NOT COMMIT?
setlocale(LC_ALL, 'en_GB.UTF8', 'eng');
date_default_timezone_set('Europe/London');

// The root of the project; where the MVC files are
define('PROJECT_ROOT', dirname($_SERVER['SCRIPT_FILENAME']).DIRECTORY_SEPARATOR);

// The root of the project on the web server
define('PROJECT_WEB', dirname($_SERVER['SCRIPT_NAME']).(dirname($_SERVER['SCRIPT_NAME']) == DIRECTORY_SEPARATOR ? '' : DIRECTORY_SEPARATOR));

/**
 * We're gonna go start a session here. It may be useful somewhere...
 */
session_name('LOCAL'.implode('', array_map('ucwords', explode(DIRECTORY_SEPARATOR, PROJECT_WEB))));
if (defined('SESSION_ROOT'))
{
	session_set_cookie_params(0, SESSION_ROOT);
} else {
	session_set_cookie_params(0, PROJECT_WEB);
}
session_start();

// The root of the shared classes directory
define('FRAMEWORK_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);

// The root of the shared classes directory
// This is currently the parent of the framework subdirectory
define('LIBRARY_ROOT', FRAMEWORK_ROOT.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR);

// Function to autoload classes
function autoload($className)
{
	if (file_exists(FRAMEWORK_ROOT . 'class.'.strtolower($className).'.php'))
	{
		// Class is a shared class
		require_once(FRAMEWORK_ROOT . 'class.'.strtolower($className).'.php');
	} elseif (file_exists(LIBRARY_ROOT . 'class.'.strtolower($className).'.php')) {
		// Class is a shared class
		require_once(LIBRARY_ROOT . 'class.'.strtolower($className).'.php');
	} elseif (file_exists(PROJECT_ROOT . 'controllers'.DIRECTORY_SEPARATOR.strtolower($className).'.php')) {
		// Class is a controller
		require_once(PROJECT_ROOT . 'controllers'.DIRECTORY_SEPARATOR.strtolower($className).'.php');
	} elseif (file_exists(PROJECT_ROOT . 'models'.DIRECTORY_SEPARATOR.strtolower($className).'.php')) {
		// Class is a model
		require_once(PROJECT_ROOT . 'models'.DIRECTORY_SEPARATOR.strtolower($className).'.php');
	} elseif (file_exists(PROJECT_ROOT . 'helpers'.DIRECTORY_SEPARATOR.strtolower($className).'.php')) {
		// Class is a helper
		require_once(PROJECT_ROOT . 'helpers'.DIRECTORY_SEPARATOR.strtolower($className).'.php');
	} else {
		throw new Exception('Cannot find class "'.$className.'"');
	}
}
spl_autoload_register('autoload');

// Strip slashes from input data
if (get_magic_quotes_gpc())
{
	$_GET		= General::array_map_recursive('stripslashes', $_GET);
	$_POST		= General::array_map_recursive('stripslashes', $_POST);
	$_REQUEST	= General::array_map_recursive('stripslashes', $_REQUEST);
	$_COOKIE	= General::array_map_recursive('stripslashes', $_COOKIE);
}

/**
 * Get the basic configuration
 */
require_once(PROJECT_ROOT.'config.php');

/**
 * The main calling function
 * Don't try to break the URL down if it's a cron job...
 */
if (!defined('CRON_JOB'))
{
	if (isset($_GET['url']))
	{
		$url = rtrim($_GET['url'], '/');
	} else {
		$url = '';
	}

	if (!isset($GLOBALS['routes']))
	{
		$GLOBALS['routes'] = array();
	}

	/**
	 * Reroute the url (if required)
	 */
	foreach($GLOBALS['routes'] AS $m => $r)
	{
		$url = preg_replace($m, $r, $url);
	}

	$url = rtrim($url, '/');

	/**
	 * Break the url into pieces
	 */
	$urlArray = explode("/",$url);

	$controller = $action = NULL;
	$queryString = array();
	// Get the parts of the path requested
	if (count($urlArray) > 0)
	{
		$controller = ucwords($urlArray[0]);
		if (count($urlArray) > 1)
		{
			$action = str_replace('-', '_', $urlArray[1]);
			if (count($urlArray) > 2)
			{
				$queryString = array_slice($urlArray, 2);
			}
		}
	}

	// use the default controller if none specified
	if ($controller == NULL)
	{
		$controller = DEFAULT_CONTROLLER;
	}

	// Use the default action if none specified
	if ($action == NULL)
	{
		$action = '_default';
	} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// If it's a post action, modify the action to call
		$action .= '_post';
	}

	/**
	 * Try to initialise the controller
	 */
	try {
		$template = new Template($controller);
		$template->setView($action);
		$dispatch = new $controller($template);
	} catch (Exception $e) {
		$msg = 'Framework: Could not load controller "'.$controller.'"; url was "'.$url.'"; error was "'.$e->getMessage().'"';
		do404($msg);
	}

	/**
	 * Try to call the action
	 */
	if ((int)method_exists($dispatch, $action))
	{
		call_user_func_array(array($dispatch,$action),$queryString);
	} else {
		$msg = 'Framework: Method "'.$action.'" is not callable in controller "'.$controller.'" (url was "'.$url.'")';
		do404($msg);
	}
}

/**
 * Show the standard CS 404 error page
 */
function do404($msg)
{
	error_log($msg);
	header("Status: 404 Not Found");

	if (defined('WEBMASTER_TESTING'))
	{
		echo '<p class="message" style="background-color: red;">'.$msg.'</p>';
	}

	echo '<h1>404 Error - Page Not Found</h1>';

	exit();
}
