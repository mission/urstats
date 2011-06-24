<?php

	/*Import critical files and define page title 
	==================================================*/
	
	define('INCLUDE_CHECK',true);
	$base = substr_replace(dirname(__FILE__),'',-6);

	include ("$base/includes/defs.php");
	include ("$base/includes/config.php");
	include ("$base/includes/check_access.php");
	
	$page_title ='Control Panel'; 

	include($theme_path.'overallheader.php');
	include_once("getusers.php");
	
	/*=================================================*/
	
	

	/*Check URsTats Version 
	==================================================*/
	
				$version = "4.4";
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
				
	/*=================================================*/

	
	
	/*Determine Mode and set page body accordingly 
	==================================================*/
	
	$mode     = $_GET['mode'];
	
	if($mode){
		$id       = $_GET['id'];
		$type     = $_POST['type'];
		$password = $_POST['editPassword'];
		$username = $_POST['editUsername'];
		$email    = $_POST['editEmail'];
		
		switch ($mode){
			case "edit":
				echo editus($id);
				break;
			case "delete":
				echo deleteus($id);
				break;
			case "logoff":
				echo logoff($id);
				break;
			case "save":
				echo saveus($id, $username, $password, $email,$type);
				break;
			default:
				echo "<center>You have accessed an invalid resource!</center>";
				break;
		}
	}
	/*=================================================*/



	/*Render the remaining page
	==================================================*/	
	
	echo getusers();

	include($theme_path.'overallfooter.php');
	
	/*=================================================*/
	
?>
