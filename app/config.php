<?php
/**
 * z1Core PHP Framework
 *
 * Configuration file
 *
 * Developer: Denis Kuschenko
 * Site: z1web.ru
 * Mail: ziffyweb@gmail.com
 *
 * 2016(c)
 */

error_reporting(E_ALL); // Showing errors ( on the dev. server )
ini_set('display_errors', 0); // If not setting on the php.ini

CONST DEV_SERVER = TRUE; // This is development or production ?

CONST
DB_HOST = 'localhost',  // Database hostname (localhost?)
DB_USER = 'root',  // Database user (root?)
DB_PASS = '',  // Database password (empty?)
DB_DATABASE = ''; // Database name

$config =
[
	'GENERAL' =>
	[
		'title' => '',
		'module-actionSeparator' => '-' // Separator between module and action names in url. e.g. - "/" or "-" or "_"
	],
	'DEV' =>
	[
		'protocol' => 'http',  // Protocol (http / https)
		'domain' => 'localhost/z1core/', // Domain (example.com / localhost)
		'template' => 'main',  // Default template name (main)
		'indexModule' => 'index', // Module which load on index page (index)
	],
	'PRODUCTION' =>
	[
		'protocol' => 'http',
		'domain' => '',
		'template' => 'main',
		'indexModule' => 'index',
	]
];