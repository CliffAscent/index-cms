<?php

/**
* Do not allow direct access.
*/
if (!defined('BASEPATH') || !BASEPATH) {
	die('Direct access not allowed.');
}


/**
* An application child class.
*/
class App extends IndexCMS {

	/**
	* Home page override.
	*
	* @return void
	*/
	protected function home() {
		$data['text'] = 'This is an override home() method from app.php.';

		$this->display('home', $data, false, $data['text']);
	}


	/**
	* 404 page override.
	*
	* @return void
	*/
	protected function notFound($requested = false, $reason = false) {
		$data['error'] = 'This is an override notFound() method from app.php.';

		$data['requested'] = $requested;
		$data['reason'] = $reason;

		$this->display('404', $data, false, $data['error']);
	}


	/**
	* Test page.
	*
	* @return void
	*/
	protected function test() {
		$data['test'] = 'This is a test method in app.php.';

		$this->display('test', $data, false, $data['test']);
	}


	/**
	* Launch the app.
	*
	* @return self
	*/
	function __construct() {
		// Block routes here to prevent direct file access.
		// Entire directories can be blocked by adding a / to the end.
		// $this->blockedMethods[] = 'private';

		parent::__construct();
	}
}


/**
* Initialize the IndexCMS/App object.
*/
$IndexCMS = new App();

?>