<?php

function saveus($id, $username, $password, $email,$type) {
	include "../includes/defs.php";
	include "../includes/config.php";
	if($password == "password"){
		$c_pass   = "";
		}
	else{
		$password = md5($password);
		$c_pass   = ",`password`='{$password}'";
		}		
		$type     = strtolower($type);
		$username = addslashes($username);
		$p2       = $_POST['p2'];

	
	mysql_connect($db_host,$db_user,$db_pass) or die(mysql_error());
	mysql_select_db($db_database) or die(mysql_error());
	mysql_query("UPDATE `urts_users` SET `username`='{$username}'$c_pass,`email`='{$email}',`type`='$type' WHERE `ID`='{$id}'") or  die("An error occured updating the user information. Error code: A3");
	echo "
<table align='center' class='container3'>
	<tr align='center'>
		<td align='center'>Changes to user <h3>{$username}</h3> saved successfully!</td>
	</tr>
	<tr>
		<td align='center'><button class='nav'  OnClick=\"location.href='$linkrel/admin/'\">Ok</button></td>
	</tr>
</table>";
}



function deleteus($id) {
	include "../includes/config.php";
	include "../includes/defs.php";
	mysql_connect($db_host,$db_user,$db_pass) or die(mysql_error());
	mysql_select_db($db_database) or die(mysql_error());
	$delname = mysql_query("SELECT `username` FROM `urts_users` WHERE `id`='{$id}'");
	$data = mysql_fetch_assoc($delname);
	$nme = $data['username'];
	mysql_query("DELETE FROM `urts_users` WHERE `id`='{$id}'");
	echo "
<form name='blank' action='$linkrel/admin/' method='post'>
<table class='container3'>
	<tr>
		<td>User {$nme} deleted successfully!</td>
	</tr>
	<tr align='center'>
		<td><input type='submit' class='nav' name='blank' value='OK'></td>
	</tr>
</table></form>";
}


function editus($id) {
	
include("../includes/config.php");
include("../includes/defs.php");
	
	$result = mysql_query("SELECT * FROM `urts_users` WHERE `ID`='{$id}' limit 1;");
	if (mysql_num_rows($result) > 0) {
		$data=mysql_fetch_assoc($result);
		//Clean up dbase text
		$username = stripslashes($data['username']);
		$email    = stripslashes($data['email']);
		$password = md5($data['pasword']);		
		echo "
		<form action='$linkrel/admin/?mode=save&type=user&id=$id' method='post'>
		<table class='container4'>
			<tr>
				<th colspan='3'>Edit User &bull; $username &bull; {$data['type']}</th>
			</tr>
			<tr>
				<td>Username:</td>
				<td>&nbsp;&nbsp;</td>
				<td><input type='text' name='editUsername' value='$username'></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td>&nbsp;&nbsp;</td>
				<td><input type='password' name='editPassword' value='password'></td>
			</tr>	
			<tr>
				<td>Email:</td>
				<td>&nbsp;&nbsp;</td>
				<td><input type='text' name='editEmail' value='$email'></td>
			</tr>	
			<tr>
				<td>User Type:</td>
				<td>&nbsp;&nbsp;</td>
				<td><select name='type'>
					<option name='admin'>Admin</option>
					<option name='super'>Super</option></select></td>
			</tr>			
			<tr>
				<td colspan='2' align='center'><input class='nav' type='submit' name='save' value='Save' /></td>
				<td align='left'><input type='hidden' name='actions' value='save'></td>
			</tr>
		</table></form>";
	} 
	else{
		die("An error was encountered while updating the user. Error code: A4");
	}
}

function logoff($id){
	$zero = "0";
	mysql_query("UPDATE `urts_users` SET `login`='{$zero}' where `ID`='$id'") or  die("Error logging user out!".mysql_error()."");
	echo "<center>User logged out successfully!</center>";
	echo getusers();
}

function getusers(){

//Sort pages
	if (!isset($_GET['page']) or !is_numeric($_GET['page'])) {  
		$page = 0;
	} 
	else {
		$page = (int)$_GET['page'];
	}
	
include ("../includes/config.php");
include ("../includes/defs.php");
	
			
//Don't go back a page if there's nowhere to go
if($page==0){
		$bbtn = "disabled='disabled'";}
	else{
		$bbtn = '';
	}
	

	$result = mysql_query("SELECT * FROM `urts_users` LIMIT $page, 20");
if (!$result){
	die("Unable to connect to database. Error code: A5");
	}
	
echo "
<table>
	<tr>
		<td valign='top'>
			<table width='100%' align='center' class='container5'>
				<tr>
					<th colspan='10'><center><h1>User list</h1></center></th>
				</tr>
				<tr>
					<td><button class='nav' {$bbtn} Onclick='location.href=\"$linkrel/admin/?page=".($page-20)."\"'><--<br></td>
					<td colspan='5'>&nbsp;&nbsp;</td>
					<td style='text-align:right'><button class='nav' Onclick='location.href=\"$linkrel/admin/?page=".($page+20)."\"'>--></td>
				</tr>
				<tr>
					<td><font size='3'><b>Username</b></font></td>
					<td>&nbsp;&nbsp;</td>
					<td style='text-align:center'><font size='3'><b>Email</b></font></td>
					<td>&nbsp;&nbsp;</td>
					<td style='text-align:center'><font size='3'><b>Type</b></font></td>
					<td>&nbsp;&nbsp;</td>
					<td style='text-align:center'><font size='3'><b>Options</b></font></td>
				</tr>"; 
		
while ($data=mysql_fetch_assoc($result)){
		$id        = $data['id'];
		$username  = stripslashes($data['username']);
		$password  = $data['password'];
		$logged_in = $data['login'];
		$email     = stripslashes($data['email']);
		$type      = $data['type'];
		
		if($logged_in == "1"){
			$online = "logged_on";
			}
		else{
			$online = "logged_off";
			}

if ($username == $uname){
	$delbtn = "";			
	}
elseif ($id == "1"){
	$delbtn = "";
	}
else{
	$delbtn = "<a href='$linkrel/admin/?mode=edit&type=user&id={$id}'><img class='nav' src='{$u_theme_path}styles/images/b_edit.png' width='16' height='16' /></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='$linkrel/admin/?mode=delete&type=user&id=$id' onClick=\"if (! confirm('Are you sure you want to delete user: {$username}?')) return false;\"><img class='nav' src='{$u_theme_path}styles/images/b_del.png' width='16' height='16' />&nbsp;&nbsp;&nbsp;&nbsp;<a href='$linkrel/admin/?mode=logoff&type=user&id=$id'><img class='nav' src='{$u_theme_path}styles/images/$online.png' width='16' height='16' />";
	}
	
echo "
				<tr>
					<td><font size='3'>{$username}</font></td>
					<td>&nbsp;&nbsp;</td>
					<td><font size='1'>$email</font></td>
					<td>&nbsp;&nbsp;</td>
					<td>$type</td>
					<td>&nbsp;&nbsp;</td>
					<td><form action='' method='post'>{$delbtn}<input type='hidden' name='entID' value='{$id}'><input type='hidden' name='actione' value='editus'><input type='hidden' name='actiond' value='deleteus'></form>
					</td>
				</tr>";
}
		
echo "
			</table>
		</td>
		<td></td>
		<td valign='top'>
			<table class='container5'>
				<tr>
					<th colspan='2'><h1>Options</h1></th>
				</tr>
				<tr>
					<td>Add new user:</td>
					<td><button class='nav'  OnClick=parent.location='$linkrel/admin/adduser/'>Go</button></td>
				</tr>
				<tr>
					<td>Manage Servers</td>
					<td><button class='nav'  OnClick=parent.location='$linkrel/admin/servers/'>Go</button></td>
				</tr>
				<tr>
					<td>Manage Auto-Bans</td>
					<td><button class='nav'  OnClick=parent.location='$linkrel/admin/ban/'>Go</button></td>
				</tr>
				<tr>
					<td>Admin Log</td>
					<td><button class='nav'  OnClick=parent.location='$linkrel/admin/log/'>Go</button></td>
				</tr>
			</table>
			<table>
				<tr>
					<td></td>
				</tr>
			</table>
		</td>
	</tr>
</table>";		
		
}
?>