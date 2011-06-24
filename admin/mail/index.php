<?php

	/*Import critical files and define page title 
	==================================================*/

	define('INCLUDE_CHECK',true);
	$base = substr_replace(dirname(__FILE__),'',-10);
				
	include("$base/includes/config.php");
	include('../../includes/defs.php');

	$page_title = "Mass e-mailer";

	include('../../includes/check_access.php');
	include($theme_path.'overallheader.php');
		
	/*=================================================*/

	


	/*Are they super admin?
	==================================================*/
	
	if(!issuperadmin($uname)){
	
		echo "<div align='center'>You are not allowed to view this page!</div>";
		
		include($theme_path.'overallfooter.php');
		
		die();
		}
	
	/*================================================*/



	
				
	/*Clean up site name (Remove http://www)
	===================================================*/
	$repvars = array(
		1 => "http://",
		2 => "www.",
	);
	
	$site_clean = str_replace($repvars," ",$site_name);
	
	//Just in case they are using a non-standard port
	$site_clean = explode(":", $site_clean);
	
	$site_clean = trim($site_clean[0]);
	/*=================================================*/
	
	


	/*Get current logged in users' email address
	===================================================*/
	$result = mysql_query("SELECT `email` FROM 
			`urts_users` WHERE `username`='$uname'");
			
	$data = mysql_fetch_assoc($result);
	$email = $data['email'];
	/*=================================================*/

	
	
	
	/*Define the email signature
	===================================================*/
	$signature =
	"\r\n\r\n\r\nThis email was sent by URsTats".
					"software on the $site_name server.\r\nIf you feel you've".
					"recieved this message in error, you may contact the admin ".
					"at $site_name.";
	/*=================================================*/
	
	
	
					

	/*Send the message
	===================================================*/
	if(isset($_POST['submit'])){
	
		$subject = $_POST['subject'].$signature;
		$body    = $_POST['body'];
		$headers = "From: URsTats<$email>\r\n"."X-mailer: URsTats_mailer\r\n";
	
		//Send the email to every admin
		$userquery = mysql_query("SELECT `email` FROM `urts_users`");
		
		while($useremail = mysql_fetch_assoc($userquery)){
			
			$to = $useremail['email'];
			mail($to, $subject, $body, $headers) 
			or die ("There was an error sending your email!");

		}
		
		echo "Mail sent successfully!";

	}
	/*=================================================*/


echo"
<div align='center'>
<table>
	<tr>
		<th>URsTats - Mass Emailer</th>
	</tr>
</table>
<form action='' method='post'>
<table class='container5'>
	<tr>		
		<td width='800px'>Subject: <input type='text' name='subject' value='' size='100' /></td>
	</tr>
	<tr>
		<td><textarea style='width: 100%;height:300px;min-height:300px;max-height:300px;background: black;color: lime;-moz-border-radius:12px;-khtml-border-radius: 12px;-webkit-border-radius: 12px;	border-radius:12px;' name='body' rows='2' cols='2'></textarea></p></td>
	</tr>
	<tr>
		<td><input type='submit' name='submit' value='Submit' /></td>
	</tr>
</table>
</form>";

	

include($theme_path.'overallfooter.php');
?>