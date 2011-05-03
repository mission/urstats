<?php
//Admin
define('INCLUDE_CHECK',true);
$base = substr_replace(dirname(__FILE__),'',-6);
include ("$base/includes/defs.php");
include ("$base/includes/config.php");
$page_title ='Control Panel'; 
include($adfuncl.'check_access.php');
include($theme_path.'overallheader.php');

$sparm = $_REQUEST['sparm'];
include_once("getusers.php");

//Check version
$version = "4.3.2";
$download_url = "http://www.alphahusky.info/downloads/urstats/";
$version_url = "http://www.alphahusky.info/downloads/urstats/version.php";
$furl = fopen($version_url, 'r');
if(!$furl){
}
else{
$version_check = fread($furl,20);
if($version_check !== $version){
	echo"
<div align='center'>Your version of URsTats is out of date! Please <a href='$download_url'>upgrade</a>.</div>";
}
}
//End check version

$id       = $_GET['id'];
$mode     = $_GET['mode'];
$type     = $_POST['type'];
$password = $_POST['editPassword'];
$username = $_POST['editUsername'];
$email    = $_POST['editEmail'];

if(isset($mode)){
		if($mode == "edit"){
			echo editus($id);
			die();
		}
		elseif($mode == "delete"){
			echo deleteus($id);
			die();
		}
		elseif($mode == "logoff"){
			echo logoff($id);
			die();
		}
		elseif($mode == "save"){
			echo saveus($id, $username, $password, $email,$type);
			die();
		}
}

echo getusers();

include($theme_path.'overallfooter.php');
?>
