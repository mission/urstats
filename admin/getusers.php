<?php

/*++++++++++++++++++++++|Function SAVEUS|+++++++++++++++++++++++++*/
/*Updates the edited user info*/
	function saveus($id, $username, $password, $email,$type) {

		include "../includes/defs.php";
	
		/*
		If the password is default "password", do not
		change the password.
		*/
		if($password == "password"){
			$c_pass   = "";
		}
		else{
			$password = sha1(md5($password));
			$c_pass   = ",`password`='{$password}'";
		}		
			
			/*
			Set the user type and user name. Then update user.
			*/
			$type     = strtolower($type);
			$username = addslashes($username);
	
		mysql_query("UPDATE `urts_users` SET `username`='{$username}'{$c_pass},`email`='{$email}',`type`='{$type}' WHERE `ID`='{$id}'") OR DIE("An error occured updating the user information. Error code: A3");
		
		
		echo "
<table align='center' class='container3'>
	<tr align='center'>
		<td align='center'>Changes to user <h3>{$username}</h3> saved successfully!</td>
	</tr>
	<tr>
		<td align='center'><button class='nav'  OnClick=\"location.href='{$linkrel}/admin/'\">Ok</button></td>
	</tr>
</table>";
}
/*----------------------|Function SAVEUS|-------------------------*/



/*+++++++++++++++++++++|Function DELETEUS|+++++++++++++++++++++++++*/
/*Delete's the selected user*/
function deleteus($id) {
	
	include "../includes/defs.php";
	
	$delname = mysql_query("SELECT `username` FROM `urts_users` WHERE `id`='{$id}'");
	$data    = mysql_fetch_assoc($delname);
	$nme     = $data['username'];
	
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
/*---------------------|Function DELETEUS|-------------------------*/



/*+++++++++++++++++++++++|Function EDITUS|+++++++++++++++++++++++++*/
/*Grabs selected user info and outputs to a submittable form*/
function editus($id) {
	
	include("../includes/defs.php");
	
	$result = mysql_query("SELECT * FROM `urts_users` WHERE `ID`='{$id}' limit 1;");
	
	/*
	Fetch select users' info from the database
	*/
	if (mysql_num_rows($result) > 0) {
		$data     = mysql_fetch_assoc($result);
		$username = stripslashes($data['username']);
		$email    = stripslashes($data['email']);
		$password = sha1(md5($data['pasword']));	
		$type     = $data['type'];
		
		echo "
		<form action='$linkrel/admin/?mode=save&type=user&id=$id' method='post'>
		<table class='container4'>
			<tr>
				<th colspan='3'>Edit User &bull; $username &bull; $type</th>
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
	
	//If the user does not exist.. Give an error
	else{
		die("An error was encountered while updating the user. Error code: A4");
	}
}
/*-----------------------|Function EDITUS|-------------------------*/



/*+++++++++++++++++++++++|Function LOGOFF|+++++++++++++++++++++++++*/
/*Force logged in user to logoff*/
function logoff($id){

	mysql_query("UPDATE `urts_users` SET `login`='0' where `ID`='$id'") or  die("Error logging user out!".mysql_error()."");
	
	echo "<center>User logged out successfully!</center>";
	
}
/*-----------------------|Function LOGOFF|-------------------------*/



/*++++++++++++++++++++++|Function GETUSERS|++++++++++++++++++++++++*/
/*Main function. This is the first output seen in the admin section*/
function getusers(){
	
	include ("../includes/config.php");
	include ("../includes/defs.php");
	
	
	/*
	If there are more than 20 results, we need to offer links to
	navigate through the results.
	*/
	if (!isset($_GET['page']) or !is_numeric($_GET['page'])) {  
		$page = 0;
	} 
	else {
		$page = (int)$_GET['page'];
	}
	
			
	/*
	Don't go back a page if there's nowhere to go
	*/
	if($page==0){
		$bbtn = "disabled='disabled'";}
	else{
		$bbtn = '';
	}
	
	
	/*
	Get the list of all admins from the database. If no results
	are returned, then there is a database connection issue. Though
	at first glance one would argue that it just means there are no
	users in the database. Though that is true, there will always be
	at least one user from the installation.
	*/
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
					<td><button class='nav' {$bbtn} Onclick='location.href=\"{$linkrel}/admin/?page=".($page-20)."\"'><--<br></td>
					<td colspan='5'>&nbsp;&nbsp;</td>
					<td style='text-align:right'><button class='nav' Onclick='location.href=\"{$linkrel}/admin/?page=".($page+20)."\"'>--></td>
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
	
	/*
	Here is where we actually print the users to the main admin
	page.
	*/
	while ($data = mysql_fetch_assoc($result)){
		$id        = $data['id'];
		$username  = stripslashes($data['username']);
		$password  = $data['password'];
		$logged_in = $data['login'];
		$email     = stripslashes($data['email']);
		$type      = $data['type'];
		
		
		/*
		Change the online status image according to whether
		they have logged in or out.
		*/
		if($logged_in == "1"){
			$online = "logged_on";
			}
		else{
			$online = "logged_off";
			}
		
		
	
		echo "
				<tr>
					<td><font size='3'>{$username}</font></td>
					<td>&nbsp;&nbsp;</td>
					<td><font size='1'>$email</font></td>
					<td>&nbsp;&nbsp;</td>
					<td>$type</td>
					<td>&nbsp;&nbsp;</td>
					<td>";
					
					if(issuperadmin($uname)){
						echo "
<form action='' method='post'>
<a href='$linkrel/admin/?mode=edit&type=user&id={$id}'>
<img class='nav' src='{$u_theme_path}styles/images/b_edit.png' width='16' height='16' /></a>

&nbsp;&nbsp;&nbsp;&nbsp;

<a href='$linkrel/admin/?mode=delete&type=user&id=$id' onClick=\"if (! confirm('Are you sure you want to delete user: {$username}?')) return false;\">
<img class='nav' src='{$u_theme_path}styles/images/b_del.png' width='16' height='16' /></a>

&nbsp;&nbsp;&nbsp;&nbsp;

<a href='$linkrel/admin/?mode=logoff&type=user&id=$id'>
<img class='nav' src='{$u_theme_path}styles/images/$online.png' width='16' height='16' />

<input type='hidden' name='entID' value='{$id}'>
<input type='hidden' name='actione' value='editus'>
<input type='hidden' name='actiond' value='deleteus'>

</form>";
					}
			
	echo"					
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
				</tr>";
		if(issuperadmin($uname)){
			echo"
				<tr>
					<td>Add new user:</td>
					<td><button class='nav'  OnClick=parent.location='$linkrel/admin/adduser/'>Go</button></td>
				</tr>
				<tr>
					<td>Manage Servers</td>
					<td><button class='nav'  OnClick=parent.location='$linkrel/admin/servers/'>Go</button></td>
				</tr>
				<tr>
					<td>Admin Log</td>
					<td><button class='nav'  OnClick=parent.location='$linkrel/admin/log/'>Go</button></td>
				</tr>
				<tr>
					<td>Send Email</td>
					<td><button class='nav'  OnClick=parent.location='$linkrel/admin/mail/'>Go</button></td>
				</tr>";
		}
			echo"
				<tr>			
					<td>Manage Auto-Bans</td>
					<td><button class='nav'  OnClick=parent.location='$linkrel/admin/ban/'>Go</button></td>
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
/*----------------------|Function GETUSERS|------------------------*/

?>