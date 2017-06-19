<?php

class Controller {

	/** @var Template **/
	protected $_template;
	/** @var string **/
	protected $_default;

	/**
	 * Construct the class. Set up the template.
	 */
	public function __construct($template)
	{
		$this->_template = $template;
	}

	/**
	 * Set the default function to call for this controller
	 *
	 * @param string $default Default function to call if none specified
	 */
	protected function _setDefault($default)
	{
		$this->_default = $default;
	}

	/**
	 * Call the default function (if it has been set)
	 */
	public function _default()
	{
		if (isset($this->_default))
		{
			$this->_template->setView($this->_default);
			// Pass the default controller action to the requested action
			if (count($_POST))
			{
				call_user_func_array(array($this, $this->_default.'_post'),
									 func_get_args());
			} else {
				call_user_func_array(array($this, $this->_default),
									 func_get_args());
			}
		} else {
			do404('Framework: No default function set for controller "'.get_class($this).'"');
		}
	}

	/**
	 * Redirect to a given path
	 *
	 * @param string $path Project path to redirect to
	 */
	public function _redirect($path)
	{
		if (headers_sent())
		{
			echo '<p>Trying to redirect to ',
					'<a href="http://',$_SERVER['SERVER_NAME'],PROJECT_WEB,$path,'">',PROJECT_WEB,$path,'</a>',
					' but can\'t.</p>';
		} else {
			header('Location: '.PROJECT_WEB.General::frameworkLink($path));
		}
		exit();
	}

	/**
	 * Set a message to show on the next rendered page
	 *
	 * @param string $msg Message to show on next rendered page
	 */
	public function _setMessage($msg)
	{
		if (!defined('CRON_JOB'))
		{
			$_SESSION['Message'][] = $msg;
		}
	}

}
