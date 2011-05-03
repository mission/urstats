<?php
// Definitions File
$html_root = $_SERVER['DOCUMENT_ROOT']; //Do not change <--

//Changeable defs - ONLY EDIT THESE -
$site_name    = ""; //Your full site address WITHOUT the trailing /. Example: http://www.alphahusky.info
$install_path = "/test/testing"; // !IMPORTANT! Read Below -->
/*--------------------------
$install_path --> Where this install is in relation to the root directory. 
Example: If it's at, http://www.yoursite.com/server2/, then
$install_path = "/server2";
Do NOT put in the full web address as it will NOT work.
Leave empty if it in your root directory. 
If it were just at http://www.yoursite.com, then
$install_path = "";
---------------------------*/

$script_path  = "/home/urt/urbanterror/"; 
/*--------------------------
$script_path --> *Optional*. This is to use extra CVARS I made. 
Example: $script_path = "/home/urt/urbanterror/q3ut4";
The extra CVARS may ONLY be used IF the web server is on the
same box as your game server.
---------------------------*/









//Change if instructed to do so
$include_path = $base."/";  //By default, URsTats should be installed to the root directory of your server.


//Other defs - DO NOT MODIFY
$linkrel = "$site_name".$install_path."/urstats";
$here = $_SERVER['PHP_SELF'];
$prntdate = date('l F j\, Y');
$err12 = "ERROR #: 12 - There was an error with authentication. Please contact the system administrator.";
$adfuncl = ($include_path.'admin/func/');
include('get_theme.php');
$theme = $u_theme;
$theme_path = ($include_path.'theme/'.$theme.'/');
$u_theme_path = ($linkrel.'/theme/'.$theme.'/');
$copyright = "URsTats designed by: Alphahusky (aka Puss-N-Boots). <a href='http://www.alphahusky.info'>http://www.alphahusky.info</a>";
?>