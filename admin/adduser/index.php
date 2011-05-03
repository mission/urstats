<?php 
define("INCLUDE_CHECK", true);
$base = substr_replace(dirname(__FILE__),'',-13);
include ("$base/includes/config.php");
include ("../../includes/defs.php");
$page_title = 'Add User';
include($adfuncl.'check_access.php');
include($theme_path.'/overallheader.php');

 
 if (isset($_POST['submit'])){ 
 	if (!$_POST['username'] | !$_POST['pass'] | !$_POST['pass2']){
		die('You did not complete all of the required fields');
	}
		$username = addslashes($_POST['username']);
 	
		$check = mysql_query("SELECT `username` FROM `urts_users` WHERE `username`='$username'") or die(mysql_error());
		$check2 = mysql_num_rows($check);
			if ($check2 != 0) {
				die('Sorry, the username '.$username.' is already in use.');
 			}
			if ($_POST['pass'] != $_POST['pass2']) {
				die('Your passwords did not match. ');
			}
				$password = $_POST['pass'];
				$password = md5($password);
				$username = addslashes($username);
				$email    = addslashes($_POST['email']);
				$a_status = strtolower($_POST['astatus']);
			
		mysql_query("INSERT INTO `urts_users` (`username`, `password`,`email`,`type`) VALUES ('$username', '$password', '$email','$a_status')") or die ("There was a problem registering the new user. Please contact your system administrator!");
		
		echo"
			<div align=\"center\">
				<table>
					<tr>
						<td valign=\"top\">
							<div align=\"center\">
							<br><br>
							<table class='container4'>
								<tr>
									<td><h1><center>Registered</center></h1></td>
								</tr>
								<tr>
									<td>The user &nbsp;<b>$username</b>,&nbsp; has been added successfully!<br><br></td>
								</tr>
								<tr>
									<td><div align='center'><button class='nav' OnClick=parent.location='$linkrel/admin/adduser/'>Back</button></div></td>
								</tr>
							</table>
							</div>
						</td>
					</tr>
				</table>
			</div>
			<table height='20px'>
				<tr>
					<td></td>
				</tr>
			</table>";
			
   
}
else{

echo "
   <div align='center'>
	<table>
		<tr>
			<td valign='top'>
			<div align='center'><br><br>
				<form action='$linkrel/admin/adduser/' method='post'>
				<table class='container4'>
					<tr>
						<td>Username:</td>
						<td><input type='text' name='username' maxlength='60'></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type='password' name='pass' maxlength='10'></td>
					</tr>
					<tr>
						<td>Confirm Password:</td>
						<td><input type='password' name='pass2' maxlength='10'></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><input type='text' name='email' maxlength='30'></td>
					</tr>
					<tr>
						<td>User Type:</td>
						<td><select name='astatus'>
							<option name='admin'>Admin</option>
							<option name='super'>Super</option></select></td>
					</tr>
					<tr>
						<td colspan=2><input type='submit' name='submit' class='nav' value='Register'></td>
					</tr> 
				</table></form>
			</div>
			</td>
		</tr>
	</table>
   </div>
<table height='20px'>
	<tr>
		<td></td>
	</tr>
</table>";
} 

include($theme_path.'overallfooter.php');
?> 