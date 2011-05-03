<?php 
define("INCLUDE_CHECK", true);
include ("includes/logincfg.php");
$c_rand = substr_replace(md5(dirname(__FILE__)),'',-20);
$uname = addslashes($_COOKIE["alh_{$c_rand}_u"]);
$result = mysql_query("SELECT `username` FROM `urts_users` WHERE `username`='$uname'");

if (mysql_num_rows($result) > 0) {
	$data     = mysql_fetch_assoc($result);
	$user     = stripslashes($data['username']);
	$password = $data['password'];
}

mysql_query("UPDATE `urts_users` SET `login`='0' WHERE `username`='{$uname}'")or  die("There was an error logging you out!");
 $past = time() - 3500; 
 
 //this makes the time in the past to destroy the cookie 
 setcookie("alh_{$c_rand}_u", $user, $past, "/"); 
 setcookie('alh_urts_p', $password, $past, "/"); 
  
include('signin.php');
die(); 
 ?> 