<?php
	include_once($include_path.'includes/config.php');
	Include_once($include_path.'includes/defs.php');
	$result = mysql_query("SELECT `username` FROM `urts_users` WHERE `username`='$uname' AND `type`='super'") or die("Unable to verify user! Error code: A1");
	if (mysql_num_rows($result) > 0) {
		$data = mysql_fetch_assoc($result);
		}
		else{ 
			die("You are not allowed to view this!");
		}				

?>