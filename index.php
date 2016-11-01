<?php
/**
 * z1WEB PHP core index file
 *
 * Developer: Denis Kuschenko
 * Site: z1web.ru
 * Mail: ziffyweb@gmail.com
 *
 * 2016(c)
 */

require_once ('config.php'); // Include configuration file
require_once ('lib/functions.php'); //Include functions
require_once ('lib/z1core.php'); // Include framework library file
$loader = require __DIR__ . '/vendor/autoload.php';


$z1 = new z1Core(); // Create spec of class z1Core Class
$z1->run($config); // Main func. which start process of running web site