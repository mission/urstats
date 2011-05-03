<?php
//Workorder
define("INCLUDE_CHECK", true);
$base = dirname(__FILE__);
include("{$base}/includes/config.php");
include("{$base}/main.php");
include("{$base}/includes/defs.php");
$page_title = "URsTats - Admin Control Panel";
function checkNum($num){
  return ($num%2) ? TRUE : FALSE;
}
include ($theme_path.'overallheader.php');


//Determine mode
if(isset($_GET['mode'])){
	$type = $_GET['type'];
	$mode = $_GET['mode'];
	$id   = $_GET['id'];

	if($mode == "view"){
		if(isset($type)){
			If($type == "server"){			
				echo view($id);
			}
			elseif($type == "indserver"){
				echo viewserver();
				}
			elseif($type == "cheaters"){
				echo vcheaters();
				}
			else{
				echo main();
			}
		}
		else{
			echo main();
		}
	}
	elseif($mode == ""){
		echo main();
	}
}

//No action
else{
	echo main();
}

include($theme_path.'overallfooter.php');

?>