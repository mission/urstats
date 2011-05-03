<?php
//Search
define('INCLUDE_CHECK',true);
$base = substr_replace(dirname(__FILE__),'',-7);
include("searchform.php");
include("searchresults.php");
include("$base/includes/defs.php");
$page_title ='Search';
include ($theme_path.'overallheader.php');
echo searchform();
echo '<br>';
echo searchresults();
include($theme_path.'overallfooter.php');
?>