<?php

	/*Import critical files and define page title 
	==================================================*/
	
	define('INCLUDE_CHECK',true);
	$base = substr_replace(dirname(__FILE__),'',-9);

	include("$base/includes/config.php");
	include('../../includes/defs.php');
	
	$page_title = "Manage Auto-Ban Rules";
	
	include('../../includes/check_access.php');
	include($theme_path.'overallheader.php');
	
	/*================================================*/
	
	
	

	/*What do we want to do with the rules?
	View? Add? Delete? Edit?
	==================================================*/
	
	
	/*++++++++++++++++++|SAVE RULE|+++++++++++++++++++*/

	if(isset($_POST['save'])){
		
		$type      = strtolower($_POST['bantype']);
		$value     = addslashes($_POST['value']);
		$desc      = addslashes($_POST['desc']);
		$id        = $_POST['id'];
		$save_type = $_POST['savet'];
		
		
		switch($save_type){
		/*+++++++++++++++++++|NEW RULE|+++++++++++++++++++*/
		
			case "new":
			
				mysql_query("INSERT INTO `ban_me` (`$type`,`desc`) VALUES('$value','$desc')") or die("There was an error adding the new rule!");
			
				echo"<div align='center'>New Rule Added Successfully!</div>";
				
				break;
		/*+++++++++++++++++|UPDATE RULE|++++++++++++++++++*/
		
			case "update":
				
				/*This should never happen.. but just in case*/
				if(!$id){
					die("I need to know which server to update!");
					}
				
				mysql_query("UPDATE `ban_me` SET `$type`='$value',`desc`='$desc' WHERE `id`='$id' ") or die("There was an error updating the ban rule!");

				echo"<div align='center'>Rule Modified Successfully!</div>";
				
				break;
				
		/*+++++++++++++++++|NO FUNCTION|++++++++++++++++++*/
			
			default:
				
				echo"<div align='center'>What would you like to do with this rule?</div>";				
		
		}
	}
	
	
	
	/*++++++++++++++++++|DELETE RULE|+++++++++++++++++++*/	
	
	if(isset($_GET['id'])){
		
		$id = $_GET['id'];
			
			if($id){
		
				mysql_query("DELETE FROM `ban_me` WHERE `id`='$id'") or die("You cannot delete what does not exist!");
		
				echo"<div align='center'>Rule Deleted Successfully!</div>";
			}
			
			else{
			
				echo"<div align='center'>I can't delete that if you don't tell me which one!</div>";
			}
	}
	
	


	/*Primary body page
	==================================================*/	

	echo"
<br />
<table width='100%'>
	<tr>
		<th colspan='10'>Auto-Ban Manager</th>
	</tr>
</table>
<br />
<form action='{$here}/?mode=save&type=ban' method='post'>
<table width='100%' class='container3'>
	<tr>
		<th colspan='3'>New Auto-Ban Rule <input type='hidden' name='savet' value='new' /></th>
	</tr>
	<tr>
		<td>Enter Value: </td>
		<td><input type='text' name='value' value='' /></td>
		<td><select name='bantype'>
			<option name='guid'>GUID</option>
			<option name='ip'>IP</option>
			<option name='qport'>Qport</option>
			<option name='name'>Name</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Description:</td><td colspan='2'><input type='text' name='desc' value='' /></td>
	</tr>
	<tr>
		<td colspan='3' style='text-align:right'><input class='nav' type='submit' name='save' value='Add' /></td>
	</tr>
</table></form>
<br />
<table width='100%' class='container3'>
	<tr>
		<th colspan='7'>Current Ban Rules</th>
	</tr>
	<tr>
		<td class='container5'>Type</td><td></td><td class='container5'>Value</td><td></td><td class='container5'>Description</td><td></td><td class='container5'>Options</td>
	</tr>";
	

	/*Get all BAN RULES from database. Let's make
	sure there are rules to start*/	
	
	$result = mysql_query("SELECT * FROM `ban_me`");
	if(!$result){
		echo"
		</table></form>";
	}
	
	
	else{
		while($data = mysql_fetch_assoc($result)){
			$name = htmlentities(stripslashes($data['name']));
			$guid = $data['guid'];
			$ip   = $data['ip'];
			$qport= $data['qport'];
			$desc = stripslashes($data['desc']);
			$id = $data['id'];
			
			
			/* Since there is only one entry marked in the dbase
			for ban types, we need to discover which one it is 
			for each rule */
			
			if($name !== ""){
				$type = "Name";
				$value = $name;
				}
			elseif($guid !== ""){
				$type = "GUID";
				$value = $guid;
				}
			elseif($ip !== ""){
				$type = "IP";
				$value = $ip;
				}
			elseif($qport !== ""){
				$type = "Qport";
				$value = $qport;
				}
				
				
			/* If, for some funky reason, there is no
				type entry.. let's reflect that*/
				
			if(!$type){
				$type = "No Entry!";
				$value = "No Entry!";
				}
			
	echo"
	<tr>
		<td>$type</td><td></td><td>$value</td><td></td><td>$desc</td><td></td><td><a href='$linkrel/admin/ban/?id=$id' onClick=\"if (! confirm('Are you sure you want to delete Rule: {$type}-{$value}?')) return false;\"><img class='nav' src='{$u_theme_path}styles/images/b_del.png' width='16' height='16' /></a></td>
	</tr>";
		}
		
		
		echo"
</table>";
	}
	
	/*================================================*/
	
	

	/*Send the footer
	==================================================*/
	
	include($theme_path.'overallfooter.php');
	
	/*================================================*/
	
?>