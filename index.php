<?php
/**
 * z1Core PHP Framework index file
 *
 * Developer: Denis Kuschenko
 * Site: z1web.ru
 * Mail: ziffyweb@gmail.com
 *
 * 2016(c)
 */

require_once ('app/config.php'); // Include configuration file
require_once ('core/functions.php'); //Include functions
require_once ('core/z1core.php'); // Include framework library file

$z1 = new z1Core(); // Create spec of class z1Core Class
$z1->run($config); // Main func. which start process of running web site