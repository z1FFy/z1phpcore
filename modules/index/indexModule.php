<?php

class indexModule extends Module {
	/**
	 * indexModule constructor.
	 * @param $page z1Core
	 */
	function __construct($page) {
		$page->setTemplate('main');
		$page->setTitle('Z1WEB CORE');
		$page->setView('index');
		$page->setData(['version' => 'alpha']);
	}
}
