<?php

if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly'); //<-- DO NOT CHANGE

//Only edit Database config!
/* Database config */
$db_host		= 'localhost';
$db_user		= '';
$db_pass		= '';
$db_database	= '';
/*end config*/




//DO NOT CHANGE ANYTHING BELOW
$link = mysql_connect($db_host,$db_user,$db_pass) or die('Unable to connect to database.');
mysql_select_db($db_database,$link);
mysql_query('SET names UTF8');

//Universal Variables
$dir = substr_replace(dirname(__FILE__),'',-9);
$c_rand = substr_replace(md5($dir),'',-20); //Hash string to generate unique session cookie per install
$uname = addslashes($_COOKIE["alh_{$c_rand}_u"]);
$pword = $_COOKIE['alh_urts_p'];

//Check if logged in
include_once("defs.php");
	$result = mysql_query("SELECT * FROM `urts_users` WHERE `username`='$uname'") or die (mysql_error());
if (!$result){
	header("location: {$linkrel}/signin.php");
	die();
	}
if(!$_COOKIE["alh_{$c_rand}_u"]){
	header("location: {$linkrel}/signin.php");
	die();
	}
	
while ($data = mysql_fetch_assoc($result)){
	$lid = $data['login'];
		if ($lid == '0') {
			header("location: {$linkrel}/signin.php");
			die();
		}
		if($data['password'] != $pword){
			header("location: {$linkrel}/signin.php");
			die();
		}
		elseif($data['username'] != $uname){
			header("location: {$linkrel}/signin.php");
			die();
		}
		
}



?>