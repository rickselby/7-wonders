<?php

class Template {

	/** @var array **/
	protected $variables = array();
	/** @var string **/
	protected $_controller;
	/** @var string **/
	protected $_view;
	/** @var array **/
	protected $csConfig;

	// List of items in $csConfig (for header-cms.php) that should be arrays
	private static $csConfigArrays = array('JavascriptIncludes','StyleIncludes',
											'BreadCrumbContent','URLRewrite');

	static $subdir = 'views';

	/**
	 * Construct the class; set the controller name
	 *
	 * @param string $controller Controller name
	 */
	public function __construct($controller)
	{
		$this->_controller = strtolower($controller);
		$this->setCSConfig('StyleIncludes', '/webmaster/framework/css/cs.css');
		// This works with *most* recent things, and keeps the menus going
		$this->setCSConfig('jQuery', '1.8.0');
	}

	/**
	 * Set the view file to use (strips _post off the end of a file name
	 * automatically)
	 *
	 * @param string $view View file name
	 */
	public function setView($view)
	{
		$this->_view = strtolower(preg_replace('/_post$/','',$view));
	}

	/**
	 * Set a variable to use in a template
	 *
	 * @param string $name Variable name
	 * @param mixed $value Value
	 */
	public function set($name, $value)
	{
		$this->variables[$name] = $value;
	}

	/**
	 * Display the template
	 */
    public function render()
	{
		// Pull the variables array out into variables(!)
		extract($this->variables);

		// Get the view folder directory (& set the variable for use within
		// templates)
		$template_dir = PROJECT_ROOT . self::$subdir . DIRECTORY_SEPARATOR
				. $this->_controller . DIRECTORY_SEPARATOR;

		// Get the message to show, then clear the message
		if (isset($_SESSION['Message']) && is_array($_SESSION['Message']))
		{
			if (count($_SESSION['Message']) == 1)
			{
				$message = '<p class="message">'.array_pop($_SESSION['Message']).'</p>';
			} elseif (count($_SESSION['Message']) > 1) {
				$message = '<ul class="message"><li>'.implode('</li><li>',$_SESSION['Message']).'</li></ul>';
			}
			unset($_SESSION['Message']);
		}

		$config = Config::instance();

		// Load the template file
		if (is_file($template_dir.$this->_view.'.php'))
		{
			include ($template_dir.$this->_view.'.php');
		} else {
			$msg = 'Framework: Could not find template file '.$template_dir.$this->_view.'.php';
			do404($msg);
		}
    }

	/**
	 * Parse a view and return the generated HTML
	 *
	 * @param string $view Path to the view
	 *
	 * @return string
	 */
	public function getHTML($view)
	{
		extract($this->variables);

		// Get the view folder directory (& set the variable for use within
		// templates)
		$template_dir = PROJECT_ROOT . self::$subdir . DIRECTORY_SEPARATOR
				. $this->_controller . DIRECTORY_SEPARATOR;

		ob_start();
		// Load the template file
		if (is_file($template_dir.$view.'.php'))
		{
			include ($template_dir.$view.'.php');
		} else {
			$msg = 'Framework: Could not find template file '.$template_dir.$view.'.php';
			do404($msg);
		}

		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	/**
	 * Output json encoded data for an ajax response
	 *
	 * @param mixed[] $array Array for conversion
	 */
	public function json($array = array())
	{
		echo json_encode($array);
	}

	/**
	 * Set up a $csConfig setting for the CMS header
	 *
	 * @param string $var csConfig variable name
	 * @param mixed $val csConfig value
	 */
	public function setCSConfig($var, $val)
	{
		if (in_array($var, self::$csConfigArrays))
		{
			$this->csConfig[$var][] = $val;
		} else {
			$this->csConfig[$var] = $val;
		}
	}

}
