<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
<?php 
define("INCLUDE_CHECK", true);


 echo "<title>{$page_title}</title>";
echo "
<link rel='stylesheet' type='text/css' href='{$u_theme_path}styles/style.css' media='screen' />";

	include("{$base}/includes/config.php");

	$result = mysql_query("SELECT `read` FROM `private_msg` WHERE `read`='0' AND `to`='$uname'");
	$msg_count = mysql_num_rows($result);
	if($msg_count > 0){
		if($msg_count == 1){
			$msg_abr = "message";
			}
		else{
			$msg_abr = "messages";
			}
		$new_msgs = "<a href='$linkrel/settings/'>You have $msg_count new $msg_abr</a>";
		}
echo"
</head>
<body>

<table width='100%' >
	<tr>
		<td width='5%'></td>
		<td>
			<table style='margin-left:auto;margin-right:auto' height='65' width='100%' class='container7'>
				<tr align='center'>
					<td width='33%'>$new_msgs</td>
					<td width='33%'>
						<button class='nav'  OnClick=parent.location='$linkrel'>Home</button>&nbsp;&nbsp;&nbsp;&nbsp;
						<button class='nav'  OnClick=parent.location='$linkrel/settings/'>Settings</button>&nbsp;&nbsp;&nbsp;&nbsp;
						<button class='nav' OnClick=parent.location='$linkrel/search/'>Search</button>&nbsp;&nbsp;&nbsp;&nbsp;
						<button class='nav'  OnClick=parent.location='$linkrel/admin/'>Admin</button>&nbsp;&nbsp;&nbsp;
						<button class='nav'  OnClick=parent.location='$linkrel/logout.php'>Logout</button>
					</td>
					<td width='33%' align='right'><font size='3'>You are logged in as </font><h3>{$uname}</h3></td>
				</tr>
			</table>
		</td>
		<td width='5%'></td>
	</tr>
</table>
<div align='center'><table><tr><td valign='top'>
<br /><br />";
?>