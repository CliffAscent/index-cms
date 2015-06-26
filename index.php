<?php

/**
* Signal that the request came through index.php
* 	and store the path to the application root.
*/
define('BASEPATH', realpath(NULL));


/**
* The parent application class.
*/
class IndexCMS {
	protected $hasHtml = false;
	protected $hasMethod = false;
	protected $hasOption = false;
	protected $hasPhp = false;
	protected $blockedMethods = array('__construct', '_app', 'app', 'debug', 'display', 'displayOptions', 'footer', 'header', 'index', 'redirect', 'route');
	protected $method = false;
	protected $page = '';
	protected $post = false;
	protected $uri = false;


	/**
	* Debug data and optionally terminate the application.
	*
	* @param mixed $data The data you wish to be var_dump().
	* @param bool $die Should the method die() at completion.
	*
	* @return void
	*/
	public function debug($data = false, $die = false) {
		var_dump($data);

		if ($die) {
			die();
		}
	}


	/**
	* Expose the data, display the page, and optionally terminate the application.
	*
	* @param mixed $page The page string or false.
	* @param array $data The data that should be exposed to the template.
	* @param bool $callMethod Should a matched method be called.
	* @param mixed $directOutput A string to echo or false.
	* @param bool $die Should the method die() at completion.
	*
	* @return void
	*/
	protected function display($page = false, $data = false, $callMethod = true, $directOutput = false, $die = true) {

		// Check for a back-up page.
		if (empty($page)) {
			$page = $this->page;
		}

		// Direct output or 404 if a page is not defined.
		if (empty($page)) {
			if ($directOutput) {
				echo $directOutput;
				
				if ($die) {
					die();
				}
			}
			else {
				$this->notFound();
			}
		}

		// Get the proper method name.
		if (!empty($page)) {
			if (strpos($page, '/')) {
				$this->method = str_replace('/', '_', $page);
			} else {
				$this->method = $page;
			}
		}

		// Expose the data to the template.
		if ($data) {
			foreach ($data as $key => $val) {
				${$key} = $val;
			}
		}

		$this->displayOptions($page);

		// Load the proper display option using the following priority:
		// custom method > php file > html file > 404 method
		if ($this->hasMethod && $callMethod) {
			$this->{$this->method}();
		}
		elseif ($this->hasPhp) {
			include $page . '.php';
		}
		elseif ($this->hasHtml) {
			if (file_exists($page . '.html')) {
				echo file_get_contents($page . '.html');
			}
			elseif (file_exists($page . '.htm')) {
				echo file_get_contents($page . '.htm');
			}
			elseif (file_exists($page . '.tpl')) {
				echo file_get_contents($page . '.tpl');
			}
		}
		else {
			if ($directOutput) {
				echo $directOutput;
			}
			else {
				$this->notFound($page);
			}
		}

		if ($die) {
			die();
		}
	}


	/**
	* See what display options exist.
	*
	* @param mixed $page The page string or false.
	*
	* @return bool Does the page have a display option.
	*/
	protected function displayOptions($page = false) {
		// Reset the options
		$this->hasOption = false;
		$this->hasMethod = false;
		$this->hasPhp = false;
		$this->hasHtml = false;

		if (empty($page)) {
			return false;
		}

		if ($this->method && method_exists($this, $this->method)) {
			$this->hasOption = true;
			$this->hasMethod = true;
		}
		if (file_exists($page . '.php')) {
			$this->hasOption = true;
			$this->hasPhp = true;
		}
		if (file_exists($page . '.html')) {
			$this->hasOption = true;
			$this->hasHtml = true;
		}
		elseif (file_exists($page . '.htm')) {
			$this->hasOption = true;
			$this->hasHtml = true;
		}
		elseif (file_exists($page . '.tpl')) {
			$this->hasOption = true;
			$this->hasHtml = true;
		}

		return $this->hasOption;
	}


	/**
	* Home page.
	*
	* @return void
	*/
	protected function home() {
		$data['text'] = 'Index CMS is designed for rapid deployment of marked-up content or to serve as a basic website.';

		$this->display('home', $data, false, $data['text']);
	}


	/**
	* 404 page.
	*
	* @return void
	*/
	protected function notFound($requested = false, $reason = false) {
		if ($requested) {
			$data['error'] = 'The requested page "' . $requested . '" cannot be found.';
		}
		else {
			$data['error'] = 'The requested page cannot be found.';
		}

		if ($reason) {
			$data['error'] .= ' Reason: "' . $reason . '"';
		}

		$data['requested'] = $requested;
		$data['reason'] = $reason;

		$this->display('404', $data, false, $data['error']);
	}


	/**
	* Redirect to the provided URI.
	*
	* @param mixed $uri The URI to redirect to or false.
	* @param mixed $protocol The protocol to use or false.
	*
	* @return void
	*/
	public function redirect($uri = false, $protocol = false) {
		if (empty($uri)) {
			$uri = $this->uri;
		}

		// Get the proper protocol.
		if (empty($protocol)) {
			$protocol = (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://';
		}

		header('Location: ' . $protocol . $_SERVER['SERVER_NAME'] . $uri);
	}


	/**
	* Route the request to the proper method.
	*
	* @return void
	*/
	protected function route() {
		if (!empty($_POST)) {
			$this->post = $_POST;
		}

		// Show the home page if nothing was requested.
		if (empty($this->page) && empty($this->post)) {
			$this->home();
		}

		// Show the 404 page for blocked requests.
		if (in_array($this->page, $this->blockedMethods)) {
			$this->notFound($this->page, 'reserved URI requested');
		}

		// Route POST requests.
		if (!empty($this->post)) {
			if (empty($this->page)) {
				$this->page = 'home';
			}

			// Get the proper method name.
			if (strpos($this->page, '/')) {
				$this->method = str_replace('/', '_', $this->page);
			} else {
				$this->method = $this->page;
			}

			// Display the proper method or a JSON encoded error message.
			if ($this->method && method_exists($this, $this->method)) {
				$this->{$this->method}();
			}
			else {
				echo json_encode(array('status' => 'error', 'message' => 'Method ' . (string) $this->method . '() does not exist.'));
			}

			die();
		}

		// Call the proper method.
		if ($this->page == '') {
			$this->home();
		}
		elseif ($this->page == 'home') {
			$this->redirect(str_replace('home', '', $this->uri));
		}
		elseif ($this->page == '404' || $this->page == 'notFound') {
			$this->notFound($this->page);
		}
		else {
			$this->display($this->page);
		}
	}


	/**
	* Launch the application.
	*
	* @return self
	*/
	function __construct() {
		$this->page = rtrim((empty($_GET['page'])) ? '' : $_GET['page'], '/');
		$this->uri = rtrim((empty($_SERVER['REQUEST_URI'])) ? false : str_replace('/?', '?', $_SERVER['REQUEST_URI']), '/');
		
		self::route();
	}
}


/**
* Load app.php or initialize the IndexCMS object.
*/
if (file_exists('app.php')) {
	include 'app.php';
}
else {
	$IndexCMS = new IndexCMS();
}

?>