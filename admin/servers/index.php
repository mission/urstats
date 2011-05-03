<?php
define('INCLUDE_CHECK',true);
$base = substr_replace(dirname(__FILE__),'',-13);
include("$base/includes/config.php");
include('../../includes/defs.php');
$page_title = "Manage Servers";
include($adfuncl.'check_access.php');
include($theme_path.'overallheader.php');

$nss = "No Server Selected!";
$nr  = "This type is not recognized!";

if(isset($_GET['mode'])){
	$mode = $_GET['mode'];
	$id   = $_GET['id'];
	$type = $_GET['type'];
	
	//Edit
	if($mode == "edit"){
		if($type == "server"){
			if(isset($id)){
				echo"
				<form action='{$here}/?mode=save&type=server&id=$id' method='post'>
				<table width='100%'>
					<tr>
						<th colspan='10'>Edit Server</th>
					</tr>
					<tr>
						<td class='container5'>Name </td><td></td><td class='container5'>IP </td><td></td><td class='container5'>PORT</td><td></td><td class='container5'>RCON</td><td></td><td class='container5'>Status</td>
					</tr>
					";
				$result = mysql_query("SELECT * FROM `servers` WHERE `id`='$id'");
				while($data = mysql_fetch_assoc($result)){
					$s_name     = stripslashes($data['name']);
					$s_ip       = $data['ip'];
					$s_rcon     = stripslashes($data['rconpass']);
					$s_status   = stripslashes($data['Status']);
					$s_id       = $data['id'];
					$s_port     = $data['port'];
					echo"
					<tr>
						<td><input type='text' name='sname' value='$s_name' /></td><td></td><td><input type='text' name='sip' value='$s_ip' /></td><td></td><td><input type='text' name='sport' value='$s_port' /><td></td><td><input type='text' name='srcon' value='$s_rcon' /></td><td></td><td><input type='text' name='sstatus' value='$s_status' /></td>
					</tr>";
					}
				echo"
					<tr>
						<td colspan='10' style='text-align:right'><input class='nav' type='submit' name='saves' value='Save' /></td>
					</tr>
				</table></form>";
			}
			else{
				die($nss);
			}
		}
		else{
			die($nr);
		}
	}
	elseif($mode == "new"){
		if($type == "server"){
				$s_name   = addslashes($_POST['sname']);
				$s_ip     = addslashes($_POST['sip']);
				$s_port   = $_POST['sport'];
				$s_rcon   = $_POST['srcon'];
				$s_status = "Online";
				
				mysql_query("INSERT INTO `servers` (`name`,`ip`,`port`,`rconpass`,`status`) VALUES('$s_name','$s_ip','$s_port','$s_rcon','$s_status')") or die(mysql_error());
				echo"
				<div align='center'>New server added successfully!</div>";
		}
		else{
			die($nr);
		}				
	}
	elseif($mode == "save"){
		if($type == "server"){
			if(isset($id)){
				$s_name   = addslashes($_POST['sname']);
				$s_ip     = $_POST['sip'];
				$s_port   = $_POST['sport'];
				$s_rcon   = addslashes($_POST['srcon']);
				$s_status = addslashes($_POST['sstatus']);		
		
				mysql_query("UPDATE `servers` SET `name`='$s_name',`ip`='$s_ip',`port`='$s_port',`rconpass`='$s_rcon',`Status`='$s_status' WHERE `id`='$id' ") or die(mysql_error());
				echo"
				<div align='center'>Server updated successfully!</div>";
			}
			else{
				die($nss);
			}
		}
		else{
			die($nr);
		}		
	}
	elseif($mode == "delete"){
		if($type == "server"){
			if(isset($id)){
				mysql_query("DELETE FROM `servers` WHERE `id`='$id'");
				echo"
				<div align='center'>Server deleted successfully!</div>";
			}
			else{
				die($nss);
			}
		}
		else{
			die($nr);
		}		
	}			
}
				
		
		
		
echo"
<br />
<table width='100%'>
	<tr>
		<th style='margin-left:auto;margin-right:auto;text-align:center' colspan='10'>Manage Servers</th>
	</tr>
</table>
<br />
<table class='container3'>
	<tr>
		<td valign='top'>
			<form action='{$here}/?mode=new&type=server' method='post'>
			<table class='container3'>
				<tr>
					<th colspan='2'>Add Server</th>
				</tr>
				<tr>
					<td>Name: </td>
					<td><input type='text' name='sname' value='' /></td>
				</tr>
				<tr>
					<td>IP: </td>
					<td><input type='text' name='sip' value='' /></td>
				</tr>
				<tr>
					<td>Port: </td>
					<td><input type='text' name='sport' value='' /></td>
				</td>
				<tr>
					<td>RCON: </td>
					<td><input type='text' name='srcon' value='' /></td>
				</tr>
				<tr>
					<td colspan='2' style='text-align:center'><input class='nav' type='submit' name='news' value='Save' />
				</tr>
			</table></form>
		</td>
		<td>
			<table>
				<tr>
					<th colspan='11'>Modify Existing Servers</th>
				</tr>
				<tr>
					<td class='container5'>Name </td><td></td><td class='container5'>IP </td><td></td><td class='container5'>Port </td><td></td><td class='container5'>RCON</td><td></td><td class='container5'>Status</td><td></td><td class='container5'>Options</td>
				</tr>
				";
			$result = mysql_query("SELECT * FROM `servers`");
			while($data = mysql_fetch_assoc($result)){
				$s_name     = stripslashes($data['name']);
				$s_ip       = $data['ip'];
				$s_rcon     = stripslashes($data['rconpass']);
				$s_status   = stripslashes($data['Status']);
				$s_id       = $data['id'];
				$s_port     = $data['port'];
				echo"
				<tr>
					<td>$s_name</td><td></td><td>$s_ip</td><td></td><td>$s_port</td><td></td><td>$s_rcon</td><td></td><td>$s_status</td><td></td><td><a href='$linkrel/admin/servers/?mode=delete&type=server&id=$s_id' onClick=\"if (! confirm('Are you sure you want to delete Server: {$s_name}?')) return false;\"><img class='nav' src='{$u_theme_path}styles/images/b_del.png' width='16' height='16' /></a>&nbsp;&nbsp;<a href='$linkrel/admin/servers/?mode=edit&type=server&id=$s_id'><img class='nav' src='{$u_theme_path}styles/images/b_edit.png' width='16' height='16' /></a></td>
				</tr>";
				}
			echo"
			</table>
		</td>
	</tr>
</table>";

include($theme_path.'overallfooter.php');
?>