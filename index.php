<?php

	/*Import critical files and define page title 
	==================================================*/
	define("INCLUDE_CHECK", true);
	$base = dirname(__FILE__);
	
	include("{$base}/includes/config.php");
	include("{$base}/main.php");
	include("{$base}/includes/defs.php");
	include("{$base}/includes/check_access.php");

	$page_title = "URsTats - Admin Control Panel";

	include ($theme_path.'overallheader.php');
	/*================================================*/


	
	
	/*Determine Mode and set page body accordingly 
	==================================================*/	
	if(isset($_GET['mode'])){
		$type = $_GET['type'];
		$mode = $_GET['mode'];
		$id   = $_GET['id'];

		switch ($mode){
			case "view":
				if(isset($type)){
					switch ($type){
						case "server":			
							echo view($id);
							break;
					
						case "indserver":
							echo viewserver();
							break;
					
						case "cheaters":
							echo vcheaters();
							break;
					
						default:
							echo main();
					}
				}
				else{
					echo main();
				}
				break;
			
			default:
				echo main();
				break;
		}
	}
	else{
		echo main();
	}
	/*================================================*/

	
	

	/*Page footer
	==================================================*/
	include($theme_path.'overallfooter.php');
	/*================================================*/	

?>