<?php
if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');//<-- DO NOT CHANGE


/* Database config */
$db_host		= 'localhost';
$db_user		= '';
$db_pass		= '';
$db_database	= '';
/*end config*/


$link = mysql_connect($db_host,$db_user,$db_pass) or die('Unable to connect to database.');
mysql_select_db($db_database,$link);
mysql_query('SET names UTF8');

//Cookie Expiration
$cookie_expire = 3600;

?>