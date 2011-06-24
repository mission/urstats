<?php
//DO NOT MODIFY THIS FILE!
define("INCLUDE_CHECK", true);
include('../includes/logincfg.php');
echo"<div align='center'>";
function nextp($username){
	echo"
<table>
	<tr>
		<th>Final Steps</th>
	</tr>
	<tr>
		<td>
			<p><h2>Thank you for using my program $username!</h2></p>
			<p>Since you have made it this far, there is only 1 thing left to do!</p>
			<p>You now need to set a scheduled task to run this software every X</p>
			<p>amount of minutes. This task needs to be run uninterupted in order</p>
			<p>to run the software 24/7.</p>
			<p>There are a couple ways to do this:</p>
			<p></p>
			<p><h1>Linux: Use Cron jobs</h1></p>
			<p>Depending on your distro, you may need elevated permissions such as</p>
			<p>su or sudo to proceed.</p>
			<p>&bull; From Terminal: <i>nano cron /etc/crontab</i></p>
			<p>&bull; On the last line insert the following, replacing the \"5\"</p>
			<p>with the interval (in minutes) you would like the script to run. 2 - 5 minutes is recommended.</p>
			<p>*/5 * * * * root /usr/bin/wget -q -O /dev/null http://www.yoursitename.com/urstats/exec/GetPlayers.php</p>
			<p>&bull; Now save (CTRL+X then type the letter \"Y\")</p>
			<p>&bull; Restart crontab by <i>service crond restart</i></P>
			<p>That's it! Now login to URsTats and ADD a SERVER by going to http://www.yourserveraddress.com/urstats/</p>
			<p>IMPORTANT! See Final Note at bottom!</p>
			<p></P>
			<p><h1>Windows</h1></p>
			<p>I made a program that is similar to crontab for Linux, that is for Windows.</p>
			<p>You can download that <a href='http://www.alphahusky.info/forums/viewtopic.php?f=3&t=26'>HERE</a>. There are noth 32-bit and 64-bit versions available.</p>
			<p>If you do not have the latest .NET framework, you can download it from <a href='http://www.microsoft.com/downloads/en/details.aspx?FamilyID=9cfb2d51-5ff4-4491-b0e5-b386f32c0992&displaylang=en'>HERE</a></p>
			<p>.NET framework is required to run ALPHA-Cron.</p>
			<p>Once you get it, place a shortcut to your desktop to get to it quickly.</p>
			<p>&bull; Run the program</p>
			<p>&bull; Enter the web address to the script: http://www.yoursitename.com/urstats/exec/GetPlayers.php</p>
			<p>&bull; Adjust the interval (in minutes) to how often URsTats will run.</p>
			<p>&bull; Click SAVE.</p>
			<p>&bull; You can expand the built in browser by clicking on the double arrows(>>), to view the results of the script execution.</p>
			<p>Note: The script will not do anything until you ADD SERVERS from http://www.yoursite.com/urstats/</p>
			<p>When you minimize ALPHA-Cron, it will go right to the system tray. You can view it by Right-Click->Show</p>
		</td>
	</tr>
	<tr>
		<td><h1>Final Note</h1></td>
	</tr>
	<tr>
		<td>The steps below are <u>CRUCIAL</u> to security of your install!</td>
	</tr>
	<tr>
		<td><h2>DELETE THE INSTALL FOLDER SO THAT NOBODY CAN USE THEM AGAIN!</h2></td>
	</tr>
	<tr>
		<td><h2>Change the permissions of the /includes and all of it's files back to 0644 (rw-r--r--)</h2></td>
	</tr>
</table>";
}



if(isset($_POST['submit'])){
	$pass     = $_POST['password'];
	$pass2 	  = $_POST['password2'];
	$username = $_POST['username'];
	$email    = $_POST['email'];
	if(!$pass){
		echo "<div align='center'>Please enter a Password!</div>";
		}
	elseif(!$username){
		echo "<div align='center'>Please enter a Username!</div>";
		}
	elseif(!$pass2){
		echo "<div align='center'>Please re-enter your Password!</div>";
		}	
	elseif(!$email){
		echo "<div align='center'>Please enter an Email Address!</div>";
		}
	elseif($pass !== $pass2){
		echo "<div align='center'>Your passwords do not match!</div>";
		}
	else{
		$password = sha1(md5($pass));
		$username = addslashes($username);
		$email    = addslashes($email);
		$type     = "super";
		mysql_query("INSERT INTO `urts_users` (`username`, `password`,`email`,`type`) VALUES ('$username', '$password', '$email','$type')") or die ("There was a problem registering the new user. Please contact your system administrator!");

	echo nextp($username);
	die();
	}
	
}

echo"
<form action='' method='post'>
<table>
	<tr>
		<th>URsTats Setup</th>
	</tr>
	<tr>
		<td>Please enter information for the Primary Super Admin</td>
	</tr>
	<tr>
		<td>Username: <input type='text' name='username' value='' /></td>
	</tr>
	<tr>
		<td>Password: <input type='password' name='password' value='' /></td>
	</tr>
	<tr>
		<td>Re-Type: <input type='password' name='password2' value='' /></td>
	</tr>
	<tr>
		<td>Email: <input type='text' name='email' value='' /></td>
	</tr>
	<tr>
		<td><input type='submit' name='submit' value='Submit' /></td>
	</tr>
</table></form>";
echo"</div>";
?>