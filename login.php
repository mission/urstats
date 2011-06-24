<?php 

	/*Import login database config file
	==================================================*/
	
	define("INCLUDE_CHECK", true);
	include "includes/logincfg.php";
	
	/*=================================================*/	
	

	
	
	/*Set unique cookie ID to stop the "cookie
	monsters" :D
	==================================================*/	
	
	$c_rand = substr_replace(md5(dirname(__FILE__)),'',-20);
	
	/*=================================================*/



	
	/* Check to see if they should be logged in
	==================================================*/
	
	if(isset($_COOKIE["alh_{$c_rand}_u"])){
	
		$username = addslashes($_COOKIE["alh_{$c_rand}_u"]);
		
		$pass     = $_COOKIE['alh_urts_p'];
		
		$check    = mysql_query("SELECT * FROM `urts_users` WHERE `username`='$username'")or die("There was an error reading the database!");
 	
		$info     = mysql_fetch_array($check);
		
		
		/*This should only occur when the users info is
		change WHILE they are still logged in. This can
		also occur if someone is trying to play dirty.
		Say, the cookie monsters for instance :D*/
		
			if ($pass !== $info['password']){ 
			
				die("An error has occurred! Please clear your browser cookies to continue!");
			}
		
	}
	
	/*=================================================*/	

	
	

	/* Make sure they filled in both Username & Password
	and then verify all info is correct
	==================================================*/
	
	if($_POST['submit']) {
	
		if(!$_POST['username']){
		
			echo "
			<br><center><font color='white'><p>Please enter a username.</p></font></center>";
			
			echo show_login();
			
		}	
	
	
		if(!$_POST['pass']){
	
			echo show_login();
		
			echo "<br><center><font color='white'><p>You can't log in without a password!</p></font></center>";
		
		}
	/*=================================================*/

	
	
	
	/* Now let's make sure they are REAL!
	==================================================*/
	
	$check  = mysql_query("SELECT * FROM `urts_users` WHERE `username` = '".$_POST['username']."'")or die(mysql_error());
	$rows   = mysql_num_rows($check);
	
	if ($rows == 0){
	
		echo show_login();
		
		echo"<br><center><font color='white'><p>User does not exist!</p></font></center>";
	}
	
	/*=================================================*/	
	

	
	
	/* Does the password match the records?
	==================================================*/
		$info               = mysql_fetch_array($check);
		$password           = addslashes(sha1(md5($_POST['pass'])));
		$password_check     = addslashes($info['password']);
		$username           = addslashes($_POST['username']);
		
		/* If passwords do not match */
		if ($password != $password_check){
		
			echo show_login();
			
			echo "<br><font color='white'>Incorrect password. Please try again..</font>";
			
		}
		
		
		/*If the passwords do match.. log them in!*/
		
		else{ 
		
			mysql_query("UPDATE `urts_users` SET `login`='1' WHERE `username`='$username'")or  die("Error saving changes!");
		
			$username = stripslashes($_POST['username']); 
			
			/*$cookie_expire is defined in the includes/logincfg.php*/
			
			$hour     = time() + $cookie_expire; 
			
			setcookie("alh_{$c_rand}_u", $username, $hour, "/"); 
			setcookie("alh_urts_p", $password, $hour, "/");	 

			echo "
<br />
<br />
<body style='background: black;color:white'>
<table class='container5' style='text-align:center' width='100%'>
	<tr>
		<td>You have been logged in succesfully!</td>
	</tr>
	<tr align='center'>
		<td align='center'><button class='nav' name='oks' OnClick=parent.location='index.php'>OK</button></td>
	</tr>
</table>
</body>";



		} 
	}
	
	/*================================================*/


	
	
	/* If they did not submit the form, show it to them
	==================================================*/	
 
 else{ 
 
	echo show_login();
}

	/*================================================*/
	
	
	
	

/*++++++++++++++++++++++|Function show_login|+++++++++++++++++++++++++*/
/*Main login page*/

function show_login(){ 

	echo "
<!--[if !IE 6]><!--><link rel='stylesheet' type='text/css' href='theme/clear_black/styles/style.css' /><!--<![endif]-->
<head>
<title>Login</title>
</head>
<body>
 <div align='center'><form action='' method='post'>
	<table>
		<tr>
			<td valign='top'>
				<div align='center'></br></br></br></br></br>
					<table class='container4'> 
						<tr>
							<td colspan='2'><h1>Login</h1></td>
						</tr> 
						<tr>
							<td>Username:</td>
							<td><input type='text' name='username' maxlength='40'></td>
						</tr> 
						<tr>
							<td>Password:</td>
							<td><input type='password' name='pass' maxlength='50'></td>
						</tr> 
						<tr>
							<td colspan='2' align='right'><input type='submit' name='submit' value='Login'></td>
						</tr> 
				</div>
			</td>
		</tr>
	</table></form>
</div>";

}

/*----------------------|Function show_login|-------------------------*/


?>