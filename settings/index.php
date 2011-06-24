<?php  
define("INCLUDE_CHECK", true);
$base = substr_replace(dirname(__FILE__),'',-8);

include ("{$base}/includes/config.php");
include_once("{$base}/includes/defs.php");
$page_title='Settings';
include($theme_path.'overallheader.php');

//Set "Universals"
$err_msg1 = "You have accessed an invalid resource!";

//Send the message (Store to dbase)
if(isset($_POST['sendmsg'])){
	$to        = addslashes($_POST['to']);
	$from      = addslashes($uname);
	$subject   = addslashes($_POST['subject']);
	$body      = addslashes($_POST['body']);
	$timestamp = time();
	mysql_query("INSERT INTO `private_msg` (`to`,`from`,`subject`,`body`,`timestamp`) VALUES('$to','$from','$subject','$body','$timestamp')")or die(mysql_error("There was an error sending the Private Message"));
	
	echo"
<table>
	<tr>
		<td>Message sent to $to!</td>
	</tr>
</table>";
}

//Message actions
//New Message	
if(isset($_GET['msg'])){
	if($_GET['msg'] == "new"){
	echo"
	<table width='100%'>
		<tr>
			<td valign='top' colspan='2'>
			<div align='center'><form action='$linkrel/settings/?action=save' method='post'>
			<table class='container5' width='100%' style='text-align:center'>
				<tr>
					<th width='100%' colspan='2'>New Message</th>
				</tr>
				<tr>
					<td>To: </td><td><select name='to' value=''>";
					$result = mysql_query("SELECT `username` FROM `urts_users`");
					while($data = mysql_fetch_assoc($result)){
						$m_user = stripslashes($data['username']);
						echo"
									<option>$m_user</option>";
								}
						echo"
									</select>
					</td>
				</tr>
				<tr>
					<td>Subject</td><td><input type='text' name='subject' value='' /></td>
				</tr>
				<tr>
					<td colspan='2'></td>
				</tr>
				<tr>
					<td colspan='2'><textarea style='width: 100%;height:300px;min-height:300px;max-height:300px' name='body' rows='2' cols='2'></textarea></td>
				</tr>
				<tr>
					<td></td>
					<td style='text-align:right'><input type='submit' name='sendmsg' value='Send' /></td>
				</tr>
			</table></form>
			</div>
			</td>
		</tr>
	</table>";
	
}
//View Message
elseif($_GET['msg'] == "view"){
	if(!isset($_GET['mid'])){
		die("Error: ".$err_msg1);
		}
	$mid    = $_GET['mid'];
	$result = mysql_query("SELECT * FROM `private_msg` WHERE `id`='$mid'");
	while($data =mysql_fetch_assoc($result)){
		$to        = stripslashes($data['to']);
		$from      = stripslashes($data['from']);
		$subject   = stripslashes($data['subject']);
		$body      = stripslashes($data['body']);
		$timestamp = $data['timestamp'];
		$t1 = $data['timestamp'];
		$timestamp = date("M d y", $timestamp);
		
		if(isset($_GET['box'])){
			$box = $_GET['box'];
			if($box == "inbox"){
				if($to !== $uname){
					//Kill injection attacks or invalid links
					die("Hey! Mind your business!");
				}
			}
			elseif($box =='sent'){
				if($from !== $uname){
					die("Hey! Mind your business!");
				}
			}
			else{				
				die("Error: ".$err_msg1);
				}
		}
		else{
		die("Error: ".$err_msg1);
		}
		if($to == $uname){
			mysql_query("UPDATE `private_msg` SET `read`='1' WHERE `id`='$mid'") or die("There was an error marking your message as read");
			}
		echo"
	<table width='100%'>
		<tr>
			<td valign='top' colspan='2'>
			<table class='container5' width='100%' style='text-align:center'>
				<tr>
					<th width='100%' colspan='2'>$subject - $timestamp</th>
				</tr>
				<tr>
					<td style='text-align:left'>To: </td><td style='text-align:left'>$to</td>
				</tr>
				<tr>
					<td style='text-align:left'>From:</td><td style='text-align:left'>$from</td>
				</tr>
				<tr>
					<td style='text-align:left'>Subject:</td><td style='text-align:left'>$subject</td>
				</tr>
				<tr>
					<td colspan='2'><textarea style='width: 100%;height:300px;min-height:300px;max-height:300px' name='body' rows='2' cols='2'>$body</textarea></td>
				</tr>
			</table></form>
			</td>
		</tr>
	</table>";

}
}
}

//Delete messages
if(isset($_GET['mode'])){
	if($_GET['mode'] == "delete"){
		$mid = $_GET['mid'];
		if(isset($_GET['box'])){
			//Delete Inbox
			if($_GET['box'] == "inbox"){
				mysql_query("UPDATE `private_msg` SET `out_del`='1' WHERE `id`='$mid' AND `to`='$uname'") or die(mysqsl_error());
			}
			//Delete Outbox
			elseif($_GET['box'] == "sent"){
				mysql_query("UPDATE `private_msg` SET `in_del`='1' WHERE `id`='$mid' AND `from`='$uname'") or die(mysqsl_error());
			}
			else{
				//Kill injection attacks or invalid links
				die("Error: ".$err_msg1);
			}
		}
		else{
			die("Error: ".$err_msg1);
			}
				
		echo"
<div align='center'>Message deleted successfully!</div>";
}
}
		
		
 //Update theme
 if (isset($_POST['submit'])) { 
	mysql_query("UPDATE `urts_users` SET theme='".$_POST[themeb]."' where username='".$uname."'") or die(mysqsl_error());
	

//Separate what user sees and safe name of themes
	if($_POST['themeb'] == 'alphatech'){
		$them =('Alphatech');
		}
		elseif($_POST['themeb'] == 'pinkurple'){
		$them =('Pinkurple');
		}
		elseif($_POST['themeb'] == 'matrix'){
		$them =('Matrix');
		}
		elseif($_POST['themeb'] == 'smiley'){
		$them =('Smiley');
		}
		elseif($_POST['themeb'] == 'deepblue'){
		$them =('DeepBlue');
		}
		elseif($_POST['themeb'] == 'borderz'){
		$them =('Borderz');
		}
		elseif($_POST['themeb'] == 'CoDFaction'){
		$them =('CoDFaction');
		}
		elseif($_POST['themeb'] == 'clear_black'){
		$them =('Clear Black');
		}
		elseif($_POST['themeb'] == 'UrbanTerror'){
		$them =('UrbanTerror');
		}
		
echo "<table>
		<tr>
			<td>
				<table class='container4'>
					<tr>
						<td><h1><center>New Theme!</center></h1></td>
					</tr>
					<tr>
						<td>Your theme has been changed succesfully to &nbsp;<b>{$them}</b>&nbsp;!<br><br></td>
					</tr>
					<tr>
						<td><div align='center'><button class='nav'  onClick=\"parent.location='$linkrel/settings/'\">Back</button></div></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>";   

}
else{
echo "
	<table width='100%'>
	<tr>
		<td colspan='2'>
			<table width='100%' class='container5'>
				<tr>
					<th colspan='2'>Private Messaging</th>
				</tr>
				<tr>
					<td><a href='$linkrel/settings/?msg=new'>Compose Message</a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign='top'>
		<form action='' method ='post'>
			<table class='container5' width='100%' style='text-align:center'>
				<tr>
					<th colspan='10'>Inbox</th>
				</tr>
				<tr>
					<td class='container3'>From</td><td></td><td class='container3'>Subject</td><td></td><td class='container3'>Sent</td><td></td><td class='container3'>Options</td>
				</tr>";

				$result= mysql_query("SELECT * FROM `private_msg` WHERE `to`='$uname' AND `out_del`='0'");
				while($data=mysql_fetch_assoc($result)){
					$from      = stripslashes($data['from']);
					$subject   = stripslashes($data['subject']);
					$subject   = substr_replace($subject,'...', 20);
					$timestamp = $data['timestamp'];
					$timestamp = date("M d y", $timestamp);
					$mid       = $data['id'];
					if($timestamp == ""){
						$timestamp = date('M d y');
						}
					$read      = $data['read'];
					
					if($read == 0){
						$b  = "<b><a href='$linkrel/settings/?msg=view&mid=$mid&box=inbox'>";
						$bc = "</a></b>";
					}
					else{
						$b  = "<a href='$linkrel/settings/?msg=view&mid=$mid&box=inbox'>";
						$bc = "</a>";
					}
					echo"
				<tr>
					<td>{$b}$from{$b2}</td><td></td><td>{$b}$subject{$b2}</td><td></td><td>{$b}$timestamp{$b2}</td><td></td><td><a href='$linkrel/settings/?mode=delete&mid=$mid&box=inbox' onClick=\"if (! confirm('Are you sure you want to delete the message: {$subject}?')) return false;\"><img class='nav' src='{$u_theme_path}styles/images/b_del.png' width='16' height='16' /></td>
				</tr>";
				}
				
echo "
			</table>
		</td>
		<td valign='top'>
			<table class='container5' width='100%' style='text-align:center'>
				<tr>
					<th colspan='10'>Outbox</th>
				</tr>
				<tr>
					<td class='container3'>To</td><td></td><td class='container3'>Subject</td><td></td><td class='container3'>Sent</td><td></td><td class='container3'>Options</td>
				</tr>";

				$result= mysql_query("SELECT * FROM `private_msg` WHERE `from`='$uname' AND `in_del`='0'");
				while($data=mysql_fetch_assoc($result)){
					$to        = stripslashes($data['to']);
					$subject   = stripslashes($data['subject']);
					$subject   = substr_replace($subject,'...', 20);
					$timestamp = $data['timestamp'];
					$timestamp = date("M d y", $timestamp);
					$mid       = $data['id'];
					if($timestamp == ""){
						$timestamp = date('M d y');
						}
					$read      = $data['read'];
					
					if($read == 0){
						$b  = "<b><a href='$linkrel/settings/?msg=view&mid=$mid&box=sent'>";
						$bc = "</a></b>";
					}
					else{
						$b  = "<a href='$linkrel/settings/?msg=view&mid=$mid&box=sent'>";
						$bc = "</a>";
					}
					echo"
				<tr>
					<td>{$b}$to{$b2}</td><td></td><td>{$b}$subject{$b2}</td><td></td><td>{$b}$timestamp{$b2}</td><td></td><td><a href='$linkrel/settings/?mode=delete&mid=$mid&box=sent' onClick=\"if (! confirm('Are you sure you want to delete the message: {$subject}?')) return false;\"><img class='nav' src='{$u_theme_path}styles/images/b_del.png' width='16' height='16' /></td>
				</tr>";
				}
				
echo "
			</table>
			<br />
			<br />
		</td>
	</tr>
		<tr>
			<td valign='top' colspan='2'>
			<div align='center'><form action='$linkrel/settings/' method='post'>
			<table class='container5' width='100%' style='text-align:center'>
				<tr>
					<th width='100%'>Theme:</th>
				</tr>
				<tr>
					<td>
				<tr>
					<td valign='top'>
					<br> 
						<select name='themeb'>
							<option value='alphatech'>Alphatech</option>
							<option value='borderz'>Borderz</option>
							<option value='clear_black'>Clear Black</option>
							<option value='CoDFaction'>CoDFaction</option>
							<option value='deepblue'>DeepBlue</option>
							<option value='matrix'>Matrix</option>
							<option value='pinkurple'>Pinkurple</option>
							<option value='professional'>professional</option>
							<option value='smiley'>Smiley</option>
							<option value='UrbanTerror'>Urban Terror</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan=2><input type='submit' class='nav' name='submit' value='Change'></td>
				</tr>
			</table></form>
			</div>
		</td>
	</tr>
</table>
<table height='20px'>
	<tr>
		<td></td>
	</tr>
</table>";

include_once($theme_path.'overallfooter.php');
}
?> 