<?php

function searchresults()
{
$base = substr_replace(dirname(__FILE__),'',-7);
include ("$base/includes/config.php");
include("$base/includes/config.php");
include("$base/includes/defs.php");

$results = $_GET['results'];

if(isset($_POST['sparm'])){
	$results = 0;
	$stype = $_POST['stype'];
	$sparm = $_POST['sparm'];
	}
else{
	$stype = $_REQUEST['stype'];
	$sparm = $_REQUEST['sparm'];
	}


if(isset($results)){
	$page = $results;
}
else{
	$page = 0;
	}
	
$pageup = $page + 20;
$pagedown = $page - 20;

if ($stype == ""){
	$stype = "name";
}
if($results > 0){
	$down = "<a href='$linkrel/search/?stype=$stype&sparm=$sparm&results=$pagedown'>Previous Page</a>&nbsp;&bull;&nbsp;";
	}
	else{
	$down = "";
	}
	
	$sql2 = mysql_query("SELECT * FROM `players` WHERE `$stype` LIKE '%$sparm%'");
	$row_count = mysql_num_rows($sql2);	
if(!$row_count){
	$s_results = "";
	}
	else{
		$r_sub = $row_count - ($row_count - $results);
		if($row_count - $results < 20){
			$d_sub = $r_sub + $row_count - $results;
			}
			else{
			$d_sub = $results + 20;
			}
			$s_results = "Results: $r_sub to $d_sub of $row_count";
		
	}
	
$sql = "SELECT * FROM `players` WHERE `$stype` LIKE '%$sparm%' ORDER BY `last` DESC LIMIT {$page},20";
mysql_connect("$db_host", "$db_user", "$db_pass") or die(mysql_error());
mysql_select_db("$db_database") or die(mysql_error());
$result = mysql_query($sql) or die(mysql_error());
$numres = mysql_num_rows($result);

if($results + 20 < $row_count){
	$up ="<a href='$linkrel/search/?stype=$stype&sparm=$sparm&results=$pageup'>Next Page</a>";
	}
	else{
	$up = "";
	}
if ($numres > 0) {
	$fields_num = mysql_num_fields($result);
	echo "
<table class='container5' style='margin-left:auto;margin-right:auto'>
	<tr><th width='400px'>Search Results:</th>
	</tr>
</table>
<center>$s_results</center>
<table class='container4' style='margin-left:auto;margin-right:auto' width='100%'>
	<tr>
		<td>
			<table width='100%'>
				<tr>
					<td><b><font color='white'>Name</font></b></td>
					<td>&nbsp;&nbsp;</td>
					<td><b><font color='white'>IP</font></b></td>
					<td>&nbsp;&nbsp;</td>
					<td><b><font color='white'>GUID</font></b></td>
					<td>&nbsp;&nbsp;</td>
					<td><b><font color='white'>First Seen</font></b></td>
					<td>&nbsp;&nbsp;</td>
					<td><b><font color='white'>Last Seen</font></b></td>
					<td>&nbsp;&nbsp;</td>
					<td><b><font color='white'>Server</font></b></td>
				</tr>";
				while ($data=mysql_fetch_assoc($result)){
					$id = $data['id'];
					$ip = $data['ip'];
					$name   = htmlentities(stripslashes($data['name']));
					$guid   = stripslashes($data['guid']);
					$server = stripslashes($data['laston']);
					$last   = stripslashes($data['last']);
					$added  = stripslashes($data['added']);
					$last 	= date("m-d-y @ H:i", $last);
					$added = date("m-d-y @ H:i", $added);
					
					echo "
				<tr>
					<td><font size='1'>{$name}</font></td>
					<td>&nbsp;&nbsp;</td>
					<td><font size='1'>{$ip}</font></td>
					<td>&nbsp;&nbsp;</td>
					<td><font size='1'>{$guid}</font></td>
					<td>&nbsp;&nbsp;</td>
					<td><font size='1'>{$added}</font></td>
					<td>&nbsp;&nbsp;</td>
					<td><font size='1'>{$last}</font></td>
					<td>&nbsp;&nbsp;</td>
					<td><font size='1'>{$server}</font></td>
					<td>&nbsp;&nbsp;</td>
					";
	
				}
}
				echo "
			</table>
		</td>
	</tr>
</table>
<br />

<center>{$down}{$up}</center>
";

}
?>