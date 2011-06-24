<?php
/* Not yet finished 
This will be a Server CFG file generator*/

define("INCLUDE_CHECK", true);
include("../includes/config.php");
include("../includes/defs.php");
$page_title = "URsTats - Admin Control Panel";
function checkNum($num){
  return ($num%2) ? TRUE : FALSE;
}
include ($theme_path.'overallheader.php');

if(isset($_POST['submit'])){
echo"
<table width='100%'>
	<tr>
		<td><textarea style='width:100%;height:600px;min-height:600px;max-height:600px;background: black;color: lime' name='S1' rows='10' cols='10'>
//Example config. Note that everything behind // is ignored by the game
//Try to keep cvar-values as short as possible. Otherwise you might get \"info string length exceeded\" errors on your server

//*** Administrator Info, shows in some gamebrowsers ***
sets \" Admin\" \"$admin\" //Uses a space in front so it shows up at the top of the properties list
sets \" Email\" \"$email\"

//*** Server Name and Daily Message ***
set sv_hostname \"$server\" //Your servername here
set g_motd \"$motd\" //Your message of the day here, it is displayed while connecting
set sv_joinmessage \"$joinmsg\" //Your joinmessage here, it is displayed when the game is joined

//*** General Game Settings ***
set sv_maxclients \"$max_client\" //max clientslots available on the server, using more than 16 is not advised. It can cause lag and most maps are not built for it. Going over 24 can cause nasty bugs.
set g_maxGameClients \"$max_ns_client\" //max clients that can actually join the game. Other clients are forced to spectate. 0=all
set sv_privateClients \"$private_amnt\" //Amount of private slots. This amount of slots will be reserved for players who enter the right privatepassword
set g_gametype \"$gametype\" //0=FreeForAll, 3=TeamDeathMatch, 4=Team Survivor, 5=Follow the Leader, 6=Capture and Hold, 7=Capture The Flag, 8=Bombmode
sets sv_dlURL \"$dl_url\" //Sets the address for auto-downloading. Auto-download only works on ioUrbanTerror-clients, not quake3-clients. The client will try to download <sv_dlURL>/q3ut4/mapname.pk3. So if your server is running ut4_coolmap and sv_dlURL is set to 'yoursite.com/maps', make sure the maps is hosted at http://www.yoursite.com/maps/q3ut4/ut4_coolmap.pk3. Leaving this set 'urbanterror.net' will make it use a map mirror with the most common maps on it. If you got your own hosting, please us that though, to save bandwith.

//*** Passwords ***
set rconpassword \"$rcon_pass\" //Password to control the server remotely using rcon.
set sv_privatePassword \"$private_pass\" //password for private slots
set g_password \"$server_pass\" //password for the server. Nothing = public

//*** Limits/times ***
set timelimit \"$timelimit\" //time in minutes before map is over, 0=never
set fraglimit \"$fraglimit\" //amount of points to be scored before map is over, 0=never
set capturelimit \"$cap_limit\" //amount of flagcaps before map is over, 0=never
set g_warmup \"$warmpup_time\" //time in seconds before game starts when changed to a new map. Gives slower computers time to load before game starts

//*** Respawning *** (FFA, TDM, CAH, CTF)
set g_respawnDelay \"$respawn_delay\" //seconds before respawn, ignored when g_waverespawns is 1
set g_forcerespawn \"$force_respawn\" //seconds before respawn is forced, even when plater did not press fire
set g_waverespawns \"$wave_respawn\" //use waverespawns, meaning everybody in a team respawns at the same time
set g_bluewave \"$blue_wave\" //seconds between blue waverespawns, ignored when g_waverespawns is 0
set g_redwave \"$red_wave\" //seconds between red waverespawns, ignored when g_waverespawns is 0
set g_respawnProtection \"$spawn_god\" //amount of seconds a spawning players is protected from damage

//*** Rules ***
set g_deadchat \"$dead_chat\" //Determines if alive players can see dead players message. 0=living players can not see dead players chat 1=living players see only team-messages from dead teammembers 2=living players also see normal chats from dead players
set g_antiwarp \"$anti_warp\" //enable or disable antiwarp. This option smooths the movement of warping players (warping is caused by a crappy connection, for instance when torrenting during playing). The warping player will experience stutters when this is enabled
set g_antiwarptol \"$anti_warp_tol\" //tolerance of the antiwarp. Higher = more tolerant. 50=default
set g_gear \"$gear\" //bitmask that decides which votes are allowed and which not. Check http://www.urbanterror.net/gear_calc.html to find the correct number
set g_allowvote \"$vote\" //bitmask that decides which votes are allowed and which not. Check http://www.urbanterror.net/allowvote_calc.html to find the correct number
set g_failedvotetime \"$fail_vote\" //time in seconds before someone can call another vote after another has failed
set g_followstrict \"$follow_team\" //1=no haunting of enemies when dead
set sv_floodprotect \"$flood_protect\" //1=stops clients from spamming many chatlines

//*** Matchmode ***
set g_matchmode \"$match_mode\" //matchmode is for matchplay. Features timeouts and ready-commands
set g_timeouts \"$num_timeouts\" //ammount of timeouts that a team can do per map
set g_timeoutlength \"$timeout_length\" //length of the timeout
set g_pauselength \"$pause_length\" //length of a pause. This can only be done by rcon. 0=indefinatly

//*** Team Game Settings ***
set g_friendlyFire \"$ff\" //0=no friendlyfire 1=friendlyfire on, kick after too many TK's 2=friendlyfire on, no kicks
set g_maxteamkills \"$tk\" //amount of TK's before you get kicked when friendlyfire is 1
set g_teamkillsforgettime \"$tk_time\" //amount of seconds before TK's are forgotten
set g_teamautojoin \"$autojoin\" //force players to autojoin on connect, instead of letting them spec untill they join themselves
set g_teamForceBalance \"$force_team_balance\" //if on, you can't join a team when it has more players then the other
set g_maintainTeam \"$keep_teams\" //when switching maps, players will stay in their team
set g_teamnamered \"$red_name\" //name for the red team, nothing = Red Dragons
set g_teamnameblue \"$blue_name\" //name for the red team, nothing = SWAT
set g_swaproles \"$swap_roles\" //When map is over, play it again with the teams swapped (recommended for bombmode). After that, change map. 0=change map immediatly when map is over, no swapping of teams

//*** Team Survivor/Bombmode/Follow the Leader Specific Settings ***
set g_maxrounds \"$max_rounds\" //number of rounds before map is over, 0=never
set g_RoundTime \"$round_time\" //maximum minutes a round can take
set g_survivorrule \"$survivor_rule\" //0=teams don't get a point when time is up before everyone is dead. 1=team with most players left gets point
set g_suddendeath \"$sudden_death\" //when map is over and both teams have same amount of points, add another round
set g_bombdefusetime \"$bomb_defuse_time\" //seconds it takes to defuse bomb
set g_bombexplodetime \"$bomb_explode_time\" //seconds before bomb goes off after planting

//*** Capture the flag Specific Settings ***
set g_flagreturntime \"$flag_return_time\" //if a flag is dropped, return it after this amount of seconds
set g_hotpotato \"$hot_potato\" //when both flags are taken, they will explode after this amount of minutes

//*** Advanced settings *** Dont change, unless you know what you are doing
set sv_strictauth \"0\" //1=check for valid cdkey, this means ioUrbanTerror players will not be able to join
set sv_pure \"$cheats\" //dont let players load modified pk3-files
set sv_maxRate \"$max_rate\" //maximum traffic per second the server will send per client. 25000 or 0 = max
set sv_timeout \"$ci_timeout\" //time in seconds before player with a interupted connection will be kicked
set g_inactivity \"$drop_inactive\" //time in seconds before a non-moving player will be kicked

//*** Master servers *** servers the server will report to if 'dedicated' is set to 2. When set to 1, it doesn't report.
set sv_master1 \"\" //This one will be set automatically by the game-engine, so just leave it blank
set sv_master2 \"master.urbanterror.info\"
set sv_master3 \"master2.urbanterror.info\"
set sv_master4 \"master.quake3arena.com\"
set sv_master5 \"\"

//*** Other Settings ***
set g_armbands \"$arm_bands\" //determines the behaviour of the armbandcolor (also shows on playerlist and minimap). 0=player's choice, set with cg_rgb 1=Based on teamcolor (red or blue) 2=assigned by server (random)
set sv_maxping \"$max_ping\" //max ping a client may have when connecting to the server
set sv_minping \"$min_ping\" //min ping a client may have when connecting to the server
set g_allowchat \"$chat_type\" //0= no chatting at all 1=teamchats only 2=all chats
set g_log \"$log\" //name of the logfile. Empty (\"\") means no log. Log will be in the q3ut4 folder in windows. Linux uses ~/.q3a/q3ut4
set g_logsync \"$log_sync\" //enables/disables direct writing to the log file instead of buffered
set g_loghits \"$log_hits\" //log every single hit. Creates very big logs
set g_logroll \"$log_roll\" //create new log every now and then, instead of always using the same one
set logfile \"$log_file\" //additional logging in seperate qconsole.log file. 1=buffered, 2=synced
set g_cahtime \"$cah_time\" //Interval in seconds of awarding points for flags in Capture and Hold gamemode

//*** Map Rotation ***
set g_mapcycle \"$map_cycle\" //name of mapcycle-file, located in q3ut4 directory
map $start_map //what map to start with


//*** Anti Cheat ***
//pb_sv_enable //to enable PB, remove the // at the beginning of this line (only works when using Quake 3 Arena, not ioUrbanTerror)
set sv_battleye \"0\" //Keep this disabled, BattlEye is dead</textarea>
</td>
</tr>
</table>
";
}
else{
$email_site = str_replace('http://www.', '',$site_name);
echo"
<table width='100%' class='container3'>
	<tr>
		<th colspan='10' style='text-align:center'>New Server Config File</th>
	</tr>
	<tr>
		<td colspan='10'><br /></td>
	</tr>
	<tr>
		<td>Server name: </td>
		<td><input type='text' name='server' value='URT 4.1 Server' /></td>
		<td>Admin Name: </td>
		<td><input type='text' name='admin' value='Admin' /></td>
		<td>Admin Email </td>
		<td><input type='text' name='email' value='$uname@$email_site' /></td>
	</tr>
	<tr>
		<td>RCON Password: </td>
		<td><input type='text' name='rconpass' value='13375@uCE' /></td>
		<td>Private Password: </td>
		<td><input type='text' name='privatepass' value='pr1v@Te5L0T' /></td>
		<td>Server Password: </td>
		<td><input type='text' name='serverpass' value='53rv3rP@55' /></td>		
	</tr>
	<tr>
		<td colspan='10'><br /></td>
	</tr>
	<tr>
		<td>Message of The Day: </td>
		<td colspan='5'><input type='text' name='motd' value='Welcome to our server!' size='100' /></td>
	</tr>
	<tr>
		<td>Join Message: </td>
		<td colspan='5'><input type='text' name='joinmsg' value='Visit us at $site_name' size='100' /></td>
	</tr>
	<tr>
		<td colspan='10'><br /></td>
	</tr>
	<tr>
		<td>Max Players: </td>
		<td><select name='maxclients'>";
		$i = 1;
		do{ echo"
			<option>$i</option>";
			++$i;
			}
		while($i < 33);
		echo"
			</select>
		</td>
		<td>Max players in game: </td>
		<td><select name='maxnsclients'>";
		$i = 1;
		do{ echo"
			<option>$i</option>";
			++$i;
			}
		while($i < 32);
		echo"
			</select>
		</td>
		<td colspan='2'>Others are forced to spectate</td>
	</tr>
	<tr>
		<td>Amount Private Slots: </td>
		<td><select name='privateamnt'>";
		$i = 1;
		do{ echo"
			<option>$i</option>";
			++$i;
			}
		while($i < 6);
		echo"
			</select>
		</td>
		<td>Map URL: </td>
		<td colspan='3'><input type='text' name='dlurl' value='$site_name/q3ut4' size='50' /></td>
	</tr>
	<tr>
		<td>Time limit (minutes): </td>
		<td><input type='text' name='timelimit' value='30' size='10' /></td>
		<td>Frag limit: </td>
		<td><input type='text' name='fraglimit' value='0' size='10' /></td>
		<td>Capture limit: </td>
		<td><input type='text' name='fraglimit' value='0' size='10' /></td>
	</tr>
	<tr>
		<td>Warmup time (seconds): </td>
		<td><input type='text' name='warmup' value='0' size='10' /></td>
	</tr>
	<tr>
		<td><br /></td>
	</tr>
	<tr>
		<td>Respawn Delay (seconds): </td>
		<td><input type='text' name='respawndelay' value='200' size='10' /></td>
	</tr>
		
		
</table>";
}

include($theme_path.'overallfooter.php');

?>