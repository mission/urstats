<?php

	include("config.php");
	
	$result = mysql_query("SELECT `theme` FROM `urts_users` WHERE `username`='$uname'");
	
	if (mysql_num_rows($result) > 0) {
		$data = mysql_fetch_assoc($result);
	}
	else{}
	
	if($data['theme'] <> ''){
		$u_theme =($data['theme']);
	}
	else{	
		$u_theme = 'UrbanTerror';
	}
		
?>