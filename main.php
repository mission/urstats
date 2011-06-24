<?php

function view($id){

	include_once('exec/rcon_class.php');
	include ('includes/config.php');
	include ('includes/defs.php');

	$dt = time();
	$dtc = time("M d y");

	$sql=("SELECT * FROM `servers` WHERE `id`='{$id}'");
	$result = mysql_query($sql);
	
	//Fetch Game Server Info
	while ($data = mysql_fetch_assoc($result)) {
		$svname  = stripslashes($data['name']);
		$svname  = stripslashes($svname);
		$svip    = $data['ip'];
		$svport  = $data['port'];
		$svrcon  = stripslashes($data['rconpass']);
		$r       = new rcon_cmd("$svip", "$svport", "$svrcon");
	}

	
	//Send command from emulator
	if(isset($_POST['send'])){
		$command   = $_POST['cmd'];
		$username  = $uname;
		$ip        = $_SERVER['REMOTE_ADDR'];
		$cmd_db    = addslashes($command);
		$timestamp = time();
		$bad_cmd   = "Server variables are not allowed to be changed!";
		
		//Add an entry to log
		mysql_query("INSERT INTO `admin_log`(`admin`,`ip`,`command`,`timestamp`) VALUES('$username','$ip','$cmd_db','$timestamp')");
	
	
		//Verify server vars are not changed
		$cmd_vartest = explode(" ", $command);
		$vartest     = $cmd_vartest[0];
		$pid         = $cmd_vartest[1];
		$say         = $cmd_vartest[2];
		
		/*Set some constants:
		Actions*/
		$slap        = "slap $pid\n wait 20\n";
		$kick        = "kick $pid";
		
		//File to write to
		$slap_script_file = "slap_script.cfg";
		$slap_script 	  = ("{$script_path}{$slap_script_file}");
		$fp 			  = fopen($slap_script , "w");
	
		//These commands are NOT allowed
		$not_allowed = array("set", "seta", "rcon", "rconpassword","password","sv_","cl_");
	
		//Check if they are using illegal commands
		if(!issuperadmin($uname)){
			foreach($not_allowed as $na){
				if($na == $vartest){
					$get_response = $bad_cmd;
					break;
					}
			}
		}
		
		if($get_response !== $bad_cmd){
		
			//Check for other commands
			switch ($vartest){
		
				case "bigslap":		

					//If the admin did not offer a phrase after the command
					if($say == ""){
						$bigtext = "tell $pid \"You just got nailed to the wall by $username!\"";
					}
					else{
						$bt_var = str_replace("bigslap ".$pid, "\"", $command);
						$bt_var = $bt_var."\"";
						$bigtext = "tell $pid $bt_var";
					}
				
					//Write to the file
					fputs($fp , "{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$bigtext}");
					fclose($fp);
				
					//Send the command to the server
					$cmd = "exec $slap_script_file";
					$r->send_command($cmd);
				
					//Send response back to emulator upon success
					$get_response ="You nailed him to the WALL!";
					break;
			
				case "pwnkick":
				
					//If the admin did not offer a phrase after the command
					if($say == ""){
						$bigtext = "tell $pid \"Don't be an asshat!!\" \n wait 1000\n";
					}
					else{
						$bt_var  = str_replace("pwnkick ".$pid, "\"", $command);
						$bt_var  = $bt_var."\" \n wait 1000\n";
						$bigtext = "bigtext $bt_var";
					}
			
					//Write to the file				
					fputs($fp , "{$bigtext}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$slap}{$kick}");
					fclose($fp);
				
					//Send the command to the server
					$cmd = "exec $slap_script_file";
					$r->send_command($cmd);
				
					//Send response back to emulator upon success
					$get_response ="Player $pid was pwnt!!";
					break;
			
				default:
					//If custom commands are not used. Send command straight to server
					$cmd = $command;
					$r->send_command($cmd);
				
					//Remove ^7 and get response
					$get_response = str_replace("^7", "", $r->get_response());
				
				
			}
		}
	
	}

//Console Emulator Page
echo "
<table style='width:100%'>
    <tr>
        <td>    
          <table style='width: 100%;'>
			<form action='?mode=view&type=server&id=$id' method='post'>
              <tr>
				<td class='container3' style='text-align:center'>Urban Terror Console Emulator - $svname
				<br />
				</td>
			  </tr>
			  <tr>
                    <td width='800px'>
                        <p>
                            <textarea style='width: 100%;height:300px;min-height:300px;max-height:300px;background: black;color: lime;-moz-border-radius:12px;-khtml-border-radius: 12px;-webkit-border-radius: 12px;	border-radius:12px;' name='S1' rows='2' cols='2'>$get_response</textarea></p>
                        <p>
                            &nbsp;</p>
                        <p>
                            <input style='background: black;color: white;width: 100%;-moz-border-radius:12px;-khtml-border-radius: 12px;-webkit-border-radius: 12px;	border-radius:12px;' name='cmd' type='text' />
                            <input class='nav' name='send' type='submit' value='Send' /></p>
                    </form></td>
                 </tr>
             </table>
			 <br />
			 <table class='container3' width='100%'>
				<tr>
					<th colspan='10'>Online players</th>
				</tr>
				<tr>
					<td class='container3'>Number</td>
					<td class='container3'>Score</td>
					<td class='container3'>Ping</td>
					<td class='container3'>Name</td>
					<td class='container3'>IP</td>
					<td class='container3'>Qport</td>
					<td class='container3'>Options</td>
				</tr>					
					";
			/*Let's set time back a little (5 Minutes = 300 seconds) 
			to catch online players within last 5 minutes */	
			$time_now = time();
			$s_time = $time_now - 300;
			
			$ssv_name = addslashes($svname);
			
			$players = mysql_query("SELECT * FROM `players` WHERE `last` 
						> $s_time AND `laston`='$ssv_name' ORDER BY `slot`")
						or die(mysql_error());
			$p_count = mysql_num_rows($players);
			
			//If there are players online, display them
			if($p_count > 0){
				while($player = mysql_fetch_assoc($players)){
					$p_num   = $player['slot'];
					$p_score = $player['score'];
					$p_ping  = $player['ping'];
					$p_name  = $player['name'];
					$p_ip    = $player['ip'];
					$p_qport = $player['qport'];
				
					echo"
				<tr>
					<td> $p_num </td>
					<td> $p_score </td>
					<td> $p_ping </td>
					<td> $p_name </td>
					<td> $p_ip </td>
					<td> $p_qport </td>
					<td></td>
				</tr>";
				}
}				
			else{
				echo "<td colspan='6'>No players online</td>";
				}
				echo"
			</table>
         </td>
     </tr>
</table>
";
		//Close UDP port
		$r->close();
}



//View Cheaters page
function vcheaters(){
	include('includes/config.php');
	include ("includes/defs.php");
	
	
	$h_res = mysql_query("SELECT * FROM `players` WHERE `banned`='Yes'");
	$h_rows = mysql_num_rows($h_res);
	echo"
<table class='container3'>
	<tr>
		<th colspan='10'>Cheaters found by URsTats - $h_rows</th>
	</tr>
	<tr>
		<td>Player</td><td>&nbsp;</td><td>IP</td><td>&nbsp;</td><td>Last Server</td><td>&nbsp;</td><td>Last Seen</td><td>&nbsp;</td><td>GUID</td>
	</tr>";

	if($h_rows > 0){
		while($h_data = mysql_fetch_assoc($h_res)){
			$h_name   = stripslashes($h_data['name']);
			$h_ip     = stripslashes($h_data['ip']);
			$h_laston = stripslashes($h_data['laston']);
			$h_last   = $h_data['last'];			
			$h_last   = date("m-d-y @ H:i", $h_last);
			$h_guid   = stripslashes($h_data['guid']);
			echo "
	<tr>
		<td>$h_name</td><td>&nbsp;</td><td>$h_ip</td><td>&nbsp;</td><td>$h_laston</td><td>&nbsp;</td><td>$h_last</td><td>&nbsp;</td><td>$h_guid</td>
	</tr>";
		}
	}
	
	else{
		echo"
	<tr>
		<td>No Cheaters Found!</td>
	</tr>";
	}
	echo"
</table>";
	
}




//View individual server statistics
function viewserver(){

	include ("includes/config.php");
	include ("includes/defs.php");	
	
	$id      = $_GET['id'];
	
	$result = mysql_query("SELECT * FROM `servers` WHERE `id`='$id'") or die("You have selected an invalid server ID");
	$data = mysql_fetch_assoc($result);
	$name = $data['name'];
	
	//Let's see if we need more than 1 page of results
	$results = $_GET['results'];
	
	if(isset($results)){
		$page = $results;
	}
	else{
		$page = 0;
	}	
	$pageup   = $page + 20;
	$pagedown = $page - 20;

	if (!$stype){
		$stype = "name";
	}
	
	/*If we are viewing results other than the first page,
	show the Previous Page link*/
	if($results > 0){
		$down = "<a href='$linkrel/?mode=view&type=indserver&id={$id}&results=$pagedown'>Previous Page</a>&nbsp;&bull;&nbsp;";
	}
	else{
		$down = "";
	}
	
	//Get statistics for top of View Server page
	$sql = mysql_query("SELECT * FROM `players` WHERE `laston`='$name'");
	$row_count = mysql_num_rows($sql);	
	
	if(!$row_count){
		$s_results = "";
	}
	else{
		$r_sub = $row_count - ($row_count - $results);
		if($row_count - $results < 20){
			$d_sub = $r_sub + $row_count - $results;
		}
		else{
			$d_sub = $results + 20;
		}
			$s_results = "Results: $r_sub to $d_sub of $row_count";
		
	}
	
	/*If we have more results than 20
	show the Next Page link*/
	if($results + 20 < $row_count){
		$up ="<a href='$linkrel/?mode=view&type=indserver&id={$id}&results=$pageup'>Next Page</a>";
	}
	else{
		$up = "";
	}
	
	


	echo "

	<table style='margin-left:auto;margin-right:auto'>
		<tr>
			<td valign='top'>
                <form action='' method='post'><table width='100%' class='container3'>
                    <tr>
                         <td colspan='7' width='100%'></td>
                    </tr>
                    <tr>
                         <th colspan='7' width='100%'>Showing $s_results from server $name</th>
                    </tr>
					<tr>
                         <td><b>Name</b></td>
						 <td><b>IP</b></td>
						 <td><b>GUID</b></td>
						 <td><b>First Seen</b></td>
						 <td><b>Last Seen</b></td>
						 <td><b>Last Server</b></td>
                    </tr>";
			$result = mysql_query("SELECT * FROM `players` WHERE `laston`='$name' ORDER BY `LAST` DESC LIMIT {$page},20");
				while($data = mysql_fetch_assoc($result)){
					$id     = $data['id'];
					$name   = htmlentities(stripslashes($data['name']));
					$ip     = stripslashes($data['ip']);
					$guid   = stripslashes($data['guid']);
					$last   = $data['last'];
					$added  = $data['added'];
					$laston = stripslashes($data['laston']);		
					$last 	= date("m-d-y @ H:i", $last);
					$added  = date("m-d-y @ H:i", $added);
					
					echo"		
                    <tr>
                         <td style='color:red'>$name</td>
						 <td>$ip</td>
						 <td style='color:pink'>$guid</td>
						 <td style='color:green'><font size='1'>$added</font></td>
						 <td style='color:lime'><font size='1'>$last</font></td>
						 <td style='color:yellow'>$laston</td>
                    </tr>";
				}
				
				echo"
				</table>
			</td>
		</tr>
	</table>	
	<center><p>{$down}{$up}</p></center>
	<br />
	<br />
	<br />
";	
}




function main(){

	include ("includes/config.php");
	include ("includes/defs.php");
		
	//All users
	$result = mysql_query("SELECT * FROM `players`");
	$t_plays = mysql_num_rows($result);
	
	//Banned Users
	$h_res = mysql_query("SELECT * FROM `players` WHERE `banned`='Yes'");
	$h_rows = mysql_num_rows($h_res);
	
	//If there are any banned users, scroll marquee
if($h_rows > 0){
	echo "<center><font face='terminal' color='red' size='4'><marquee class='container3' width='800px' behavior='scroll' direction='left' scrollamount='4'>WARNING! URsTats has found $h_rows cheaters! Names:";
	while($h_data = mysql_fetch_assoc($h_res)){	
			$h_id     = $h_data['id'];
			$h_name   = stripslashes($h_data['name']);
			$h_ip     = stripslashes($h_data['ip']);
			$h_laston = stripslashes($h_data['laston']);
			$h_last   = $h_data['last'];			
			$h_last   = date("m-d-y @ H:i", $h_last);
			$h_guid   = stripslashes($h_data['guid']);
			echo "-| $h_name |";
			mysql_query("UPDATE `players` SET `banned`='Yes' WHERE `id`='$h_id'");
			}
	echo "</marquee></font></center><span style='text-align:right'><form method='post' action='$linkrel/?mode=view&type=cheaters'><input type='submit' class='nav' name='cheaters' value='View All' /></form></span>";
}


	//Main page layout
	echo "


	<table style='margin-left:auto;margin-right:auto'>
		<tr>
			<td valign='top'>
                <form action='' method='post'><table width='100%' class='container3'>
                    <tr>
                         <td colspan='7' width='100%'></td>
                    </tr>
                    <tr>
                         <th colspan='7' width='100%'>Last 20 Players - Total Players: $t_plays </th>
                    </tr>
					<tr>
                         <td><b>Name</b></td>
						 <td><b>IP</b></td>
						 <td><b>GUID</b></td>
						 <td><b>First Seen</b></td>
						 <td><b>Last Seen</b></td>
						 <td><b>Last Server</b></td>
                    </tr>";
			//Fetch last 20 players
			$result = mysql_query("SELECT * FROM `players` ORDER BY `LAST` DESC LIMIT 20");
				while($data = mysql_fetch_assoc($result)){
					$id     = $data['id'];
					$name   = htmlentities(stripslashes($data['name']));
					$ip     = stripslashes($data['ip']);
					$guid   = stripslashes($data['guid']);
					$last   = $data['last'];
					$added  = $data['added'];
					$laston = stripslashes($data['laston']);		
					$last 	= date("m-d-y @ H:i", $last);
					$added  = date("m-d-y @ H:i", $added);
					
					echo"		
                    <tr>
                         <td style='color:red'>$name</td>
						 <td>$ip</td>
						 <td style='color:pink'>$guid</td>
						 <td style='color:green'><font size='1'>$added</font></td>
						 <td style='color:lime'><font size='1'>$last</font></td>
						 <td style='color:yellow'>$laston</td>
                    </tr>";
				}
			echo"
				</table>
			</td>
		</tr>
	</table>	
	<table width='100%' class='container3'>
		<tr>
			<th colspan='2' style='margin-left:auto;margin-right:auto;'>Console Administration</td>
		</tr>
		<tr>
			<td colspan='2'></td>
		</tr>";
			//Show all game servers
			$result = mysql_query("SELECT * FROM `servers`");
				while($data = mysql_fetch_assoc($result)){
					$id   = $data['id'];
					$name = $data['name'];
					
					echo"
		<tr>
			<td><a href='$linkrel/?mode=view&type=server&id={$id}'>$name</a></td><td style='text-align:right'><a href='$linkrel/?mode=view&type=indserver&id={$id}'>View this server</a></td>
		</tr>";
				}
				echo"
	</table>	
	<br />
	<br />
	<br />
";	
}
	

?>