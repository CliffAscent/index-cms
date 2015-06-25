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
class Plugin extends IndexCMS {

	/**
	* Home page override.
	*
	* @return void
	*/
	protected function home() {
		$data['text'] = 'This is an override home() method from plugin.php.';

		$this->display('home', $data, false, $data['text']);
	}


	/**
	* 404 page override.
	*
	* @return void
	*/
	protected function notFound($requested = false, $reason = false) {
		$data['error'] = 'This is an override notFound() method from plugin.php.';

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
		$data['test'] = 'This is a test method in plugin.php.';

		$this->display('test', $data, false, $data['test']);
	}


	/**
	* Launch the plugin.
	*
	* @return self
	*/
	function __construct() {
		parent::__construct();
	}
}


/**
* Initialize the IndexCMS/Plugin object.
*/
$IndexCMS = new Plugin();

?>