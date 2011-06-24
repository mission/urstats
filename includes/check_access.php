<?php

function issuperadmin($uname){

	//Let's see if they are Super Admin
	include ('includes/config.php');
	include ('includes/defs.php');
	$result = mysql_query("SELECT * FROM `urts_users` WHERE `username` = '$uname' LIMIT 1") or die("Dbase error");
	$data   = mysql_fetch_assoc($result);
	$type   = $data['type'];
	
	if($type == "super"){
		return true;
	}
	else{
		return false;
	}
}				

?>