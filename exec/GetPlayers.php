<?php
define("INCLUDE_CHECK", true);
include_once('rcon_class.php');
$base = substr_replace(dirname(__FILE__),'',-5);
include("$base/includes/logincfg.php");

echo"
<table width='100%' style='background-color:black;color:white'>
	<tr>
		<td style='color:yellow'>Name</td><td></td><td style='color:yellow'>IP</td><td></td><td style='color:yellow'>GUID</td><td></td><td style='color:yellow'>New/Updated</td><td></td>
	</tr>";

mysql_connect("$db_host", "$db_user", "$db_pass") or die(mysql_error());
mysql_select_db("$db_database") or die(mysql_error());
$result = mysql_query("SELECT * FROM `servers` where Status='Online'");
while ($data=mysql_fetch_assoc($result)) {
	$svname  = $data['name'];
	$svname  = stripslashes($svname);
	$svip    = $data['ip'];
	$svport  = $data['port'];
	$svrcon  = stripslashes($data['rconpass']);
	$r       = new rcon_cmd("$svip", "$svport", "$svrcon");
	$players = $r->get_players();
	$date    = time();	
	
	if ($players ==""){
		echo "
	<tr>
		<td colspan='8'>Player info not available</td>
	</tr>";
	} 
	else{
		foreach ($players as $player){
			$pid     = "NULL";
			$slotnum = $player['num'];
			$score	 = $player['score'];
			$ping    = $player['ping'];
			$name 	 = addslashes($player['stripped_name']);
			$guid 	 = $r->dump_guid("$slotnum");
			$qport   = $player['qport'];
			sleep(1);

	  
			if ($name ==""){
				echo "
	<tr>
		<td colspan='8'>No Name is available, continuing without database entry </td>
	</tr>";
			} 
			elseif ($guid ==""){
				echo "
	<tr>
		<td colspan='8'>Bot</td>
	</tr>";
			} 
			else{

				list($ip) = explode(":", $player['address'], 2);
				
				$runq  = mysql_query("SELECT * FROM `players` WHERE `name` LIKE CONVERT(_utf8 \"$name\" USING latin1) COLLATE latin1_swedish_ci AND `ip` LIKE CONVERT(_utf8 \"$ip\" USING latin1) COLLATE latin1_swedish_ci AND `guid`='$guid'");
				$count = mysql_num_rows($runq);
				$data  = mysql_fetch_assoc($runq);
				$plid  = $data['id'];
				$answer  = $r->check_cheat($guid,$qport,$slotnum,$name,$ip);
				if($count == '1') {
					//Update entry				
					mysql_query("UPDATE `players` SET `last`='$date',`laston`='$svname',`qport`='$qport',`slot`='$slotnum',`ping`='$ping',`score`='$score' WHERE `id`='$plid' LIMIT 1") or die(mysql_error());
					echo "
	<tr>
		<td>$name</td><td></td><td>$ip</td><td></td><td>$guid</td><td></td><td>Updated</td><td>$answer</td>
	</tr>";
				
				} 
				else {
					//New Entry
					mysql_query("INSERT INTO `players` (`name`,`ip`,`guid`,`last`,`added`,`laston`,`qport`,`slot`,`ping`,`score`) VALUES ('$name','$ip','$guid','$date','$date','$svname','$qport','$slotnum','$ping','$score')") or die(mysql_error());
	echo"
	<tr>
		<td>$name</td><td></td><td>$ip</td><td></td><td>$guid</td><td></td><td>New</td><td>$answer</td>
	</tr>";
				}
			}
		}
	}
			
}
echo"
</table>";

?>