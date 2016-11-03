<?php

class indexModule extends Module {
	/**
	 * indexModule constructor.
	 * @param $page z1Core
	 */
	function __construct($page) {
		$page->setTitle('Z1WEB CORE');
		$page->setTemplate('mainTemplate');
		$page->setView('indexView');
		$page->setData(['version' => 'alpha']);
	}
}
