<?php 


 // Connects to your Database 
 define("INCLUDE_CHECK", true);

include "includes/logincfg.php";
$c_rand = substr_replace(md5(dirname(__FILE__)),'',-20);
if(isset($_COOKIE["alh_{$c_rand}_u"])){
	$username = addslashes($_COOKIE["alh_{$c_rand}_u"]); 
 	$pass = $_COOKIE['alh_urts_p'];
 	$check = mysql_query("SELECT * FROM `urts_users` WHERE `username`='$username'")or die(mysql_error());
 	
	while($info = mysql_fetch_array($check)){
		if ($pass !== $info['password']){ 
 		 	die("An error has occurred! Please clear your browser cookies to continue!");
		}
 	}
 }
else{
	$pli = "<center><p>Please log in.</p></center>";
}

//If not all fields completed
if(isset($_POST['submitl'])) { 
 	if(!$_POST['usernamel']){
		echo "
			<br><center><font color='white'><p>Please enter a username</p></font></center>";
		echo show_login();
		die();
	}	
	if(!$_POST['passl']){
		echo show_login();
		echo "<br><center><font color='white'><p>You can't login without a password.</p></font></center>";
		die();
 	}


	$check = mysql_query("SELECT * FROM `urts_users` WHERE `username` = '".$_POST['usernamel']."'")or die(mysql_error());
	$check2 = mysql_num_rows($check);
	if ($check2 == 0){
		echo show_login();
		echo"<br><center><font color='white'><p>User does not exist!</p></font></center>";
	}

	while($info = mysql_fetch_array($check)){
		$_POST['passl']      = addslashes($_POST['passl']);
		$info['password']    = addslashes($info['password']);
		$_POST['passl']      = md5($_POST['passl']);
		if ($_POST['passl'] != $info['password']){
			echo show_login();
			echo "<br><font color='white'>Incorrect password. Please try again..</font>";
		}
		else 
		{ 
		$lnum = "1";
		mysql_query("UPDATE `urts_users` SET `login`='{$lnum}' WHERE `username`='".$_POST['usernamel']."'")or  die("Error saving changes!".mysql_error()."");
		$_POST['usernamel'] = stripslashes($_POST['usernamel']); 
		$hour = time() + $cookie_expire;  
		setcookie("alh_{$c_rand}_u", $_POST['usernamel'], $hour, "/"); 
		setcookie("alh_urts_p", $_POST['passl'], $hour, "/");	 
		$page_title = "Login";

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

		die();

		} 
	} 
 } 
 else{ 
 echo show_login();
}
function show_login(){ 
 echo "
<!--[if !IE 6]><!--><link rel='stylesheet' type='text/css' href='theme/clear_black/styles/style.css' /><!--<![endif]-->
<head>
<title>Login</title>
</head>
<body>";
echo"
 <div align='center'><form action='' method='post'>
	<table>
		<tr>
			<td valign='top'>
				<div align='center'><font color='white'>$pli</font>
					<table class='container4'> 
						<tr>
							<td colspan='2'><h1>Login</h1></td>
						</tr> 
						<tr>
							<td>Username:</td>
							<td><input type='text' name='usernamel' maxlength='40'></td>
						</tr> 
						<tr>
							<td>Password:</td>
							<td><input type='password' name='passl' maxlength='50'></td>
						</tr> 
						<tr>
							<td colspan='2' align='right'><input type='submit' name='submitl' value='Login'></td>
						</tr> 
				</div>
			</td>
		</tr>
	</table></form>
</div>";
}
?>