<?php
define('INCLUDE_CHECK',true);
$base = substr_replace(dirname(__FILE__),'',-9);
include("$base/includes/config.php");
include('../../includes/defs.php');
$page_title = "Admin Log";
include($adfuncl.'check_access.php');
include($theme_path.'overallheader.php');

echo "
<table class='container3' width='100%'>
	<tr>
		<th colspan='10'>Admin Log</th>
	</tr>
	<tr>
		<td class='container5'>Command</td><td></td><td class='container5'>Username</td><td></td><td class='container5'>IP</td><td></td><td class='container5'>Time</td>
	</tr>";

$result = mysql_query("SELECT * FROM `admin_log` ORDER BY `timestamp` DESC");
while($data = mysql_fetch_assoc($result)){
	$command   = stripslashes($data['command']);
	$username  = stripslashes($data['admin']);
	$ip        = $data['ip'];
	$timestamp = date('M d y',$data['timestamp']);
	
	echo"
	<tr>
		<td>$command</td><td></td><td>$username</td><td></td><td>$ip</td><td></td><td>$timestamp</td>
	</tr>";
	}
echo"
</table>";



?>