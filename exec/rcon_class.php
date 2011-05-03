<?php
class rcon_cmd {

        // Some vars needed for rcon info
        private $password;

        // Some vars to store what server we will connect to
        private $address;
        private $port;
        private $socket_connection;

        // Misc. other vars
        private $last_socket_err_num;
        private $last_socket_err_str;

        // Constructor: takes the IP address (or hostname), port, and rcon password of
        // the server and opens a connection.
        public function __construct($serv_address, $serv_port, $serv_password, $timeout=30) {
                $this->address = $serv_address;
                $this->port = intval($serv_port);
                $this->password = $serv_password; //CHANGE

                $this->last_socket_err_num = -1;
                $this->last_socket_err_str = "";

                // Open up the connection wih the given address and port
                $this->socket_connection = fsockopen("udp://" . $this->address, $this->port, $this->last_socket_err_num, $this->last_socket_err_str, $timeout);
                if (!$this->socket_connection) {
                        die("Could not connect with given ip:port\n<br>errno: $this->last_socket_err_num\n<br>errstr: $this->last_socket_err_str");
                }

        }

        // Precondition: A socket has been opened without error by the constructor
        // Postcondidtion: The given command will be sent and a response will be
        //      recoverable from the function get_response()
        public function send_command($cmd) {
				$rcon = $this->password;
				$cmd = stripslashes($cmd);
                fwrite($this->socket_connection, str_repeat(chr(255), 4) . "rcon $rcon $cmd" . "\n");
        }
		public function send_regcommand($cmd) {
                fwrite($this->socket_connection, str_repeat(chr(255), 4) . "$cmd" . "\n");
        }
		public function send_kick($cmd) {
				fwrite($this->socket_connection, str_repeat(chr(255), 4) . "rcon " . $this->password . "  kick " . $cmd . "\n");
		}

        // Get the server's response to our previous query.
        // Precondition: A command should have already been sent with send_command($cmd).
        // Postcondidtion: The server's response string will be returned.
        public function get_response() {
                stream_set_timeout($this->socket_connection, 0, 500000);
                $buffer = "";
                while ($buff = fread($this->socket_connection, 9999)) {
                        list($header, $contents) = explode("\n", $buff, 2); // Trim off the header of each packet we receive.
                        $buffer .= $contents;
                }
                return $buffer;
        }

        public function close() {
                fclose($this->socket_connection);
        }

        public function get_players() { // Take the info from a "/rcon status" command and parse it for an array of player names.
		$this->send_command("status");
		$status = $this->get_response();
		//echo $status;
		if (!$status || trim($status) == "Bad rconpassword.") {
			return false;
		}

		$playerlines = explode("\n", $status); // Break the status into indvidual lines
		$players = array();
		for($i = 3; $i < count($playerlines); $i++) { // Create a new array with player's status in an array
			$line = trim(preg_replace('/\s\s+/', ' ', $playerlines[$i]));
			$player_status = explode(" ", $line); // Split the player status into an array "num score ping name lastmsg address qport rate"
			$status_size = count($player_status);
	
			if ($status_size < 8) { // Skip this line if it doesnt have enough fields.
				continue;
			}

			// It is possible for names to have spaces. There are ordinarily 9 pieces of info in the array, more mean there are spaces
			$num_name_chunks = $status_size - 8;
			$name = $player_status[3];
			for($j = 0; $j < $num_name_chunks; $j++) { // Concatenate all of the name chunks that exist in a name with spaces
				$name .= " " . $player_status[4 + $j];
			}

			$name = substr($name, 0, strlen($name) - 2); // Remove the "^7" that rcon puts at the end of the name
			$stripped_name = $this->strip_colors($name); // Rename colors

			if ($name == "") { // Make sure the name is a real person
				$name = "UnnamedPlayer";
			}

			$player['num'] = ($player_status[0]);
			$player['score'] = ($player_status[1]);
			$player['ping'] = ($player_status[2]);

			$player['name'] = $name;
			$player['stripped_name'] = $stripped_name;

			$player['lastmsg'] = ($player_status[4+$num_name_chunks]);
			$player['address'] = $player_status[5+$num_name_chunks];
			$player['qport'] = ($player_status[6+$num_name_chunks]);
			$player['rate'] = ($player_status[7+$num_name_chunks]);

			$players[] = $player;
		}
		return $players;
	}
	public function strip_quotes($str1) {
		return preg_replace("/\"/","", $str1);
	}
	public function strip_space($str3) {
		return preg_replace("/\ /", "", $str3);
	}
	
	public function strip_colon($str2) {
		return preg_replace("/\:/"," ", $str2);
	}
	// Remove ^# colors
	public function strip_colors($str) {
		return preg_replace("/\^./","", $str);
		
	}
	public function getsconfig($cmd) {
		$this->send_command("$cmd");
		$cfg = $this->get_response();
		if (!cfg) {
			return false;
		}
		$cfg1 = $this->strip_colors($cfg);
		$cfg1 = preg_replace("/\"/", "++", $cfg1);
		$cfg1 = $this->strip_space($cfg1);
		$cfg1 = explode("++", $duinfo);
		$cfg3 = $cfg1[2];
		return $cfg3;
		}
	
	public function dump_guid($clientid) {
		$this->send_command("dumpuser ". $clientid);
		$duinfo = $this->get_response();
		if (!duinfo) {
			return false;
		}
		$duinfo = $this->strip_colors($duinfo);
		$duinfo1 = explode("cl_guid", $duinfo);
		$duinfoguid = $duinfo1[1];
		$duinfoguid2 = explode("\n", $duinfoguid);
		$duinfoguid3 = $duinfoguid2[0];
		$duinfoguid = $this->strip_space($duinfoguid3);
		
		return $duinfoguid;
		
		}

		
		
	public function get_map() {
		$this->send_command("status");
		$mapinfo = $this->get_response();
		if (!mapinfo) {
			return false;
		}
		$mapinfo = substr($mapinfo, 5);
		$map_array = explode(' ',$mapinfo);
		$mapinfo1 = $map_array[0];
		$mapinfo2 = trim(substr_replace($mapinfo1, ' ', -4));
		return $mapinfo2;
		
		}

		

    public function get_all_info($clientid) {
        $this->send_command("dumpuser ". $clientid);
		sleep(1);
        $allinfo = $this->get_response();
        if (!allinfo) {
            return false;
        }
  //Colors
        $color1 = explode("cg_rgb", $allinfo);
        $color2 = $color1[1];
        $color3 = explode("\n", $color2);
        $color4 = $color3[0];
        $pinfo['colors'] = $color4;
 //Gear
        $gear1 = explode("gear", $allinfo);
        $gear2 = $gear1[1];
        $gear3 = explode("\n", $gear2);
        $gear4 = $gear3[0];
        $gear5 = $this->strip_space($gear4);
        $pinfo['gears'] = $gear5;
  //Red player
        $racered1 = explode("racered", $allinfo);
        $racered2 = $racered1[1];
        $racered3 = explode("\n", $racered2);
        $racered4 = $racered3[0];
        $pinfo['reds'] = trim($racered4);
 //Blue player
        $raceblue1 = explode("raceblue", $allinfo);
        $raceblue2 = $raceblue1[1];
        $raceblue3 = explode("\n", $raceblue2);
        $raceblue4 = $raceblue3[0];
        $pinfo['blues'] = trim($raceblue4);
  //Guid
        $duinfo1 = explode("cl_guid", $allinfo);
        $duinfoguid1 = $duinfo1[1];
        $duinfoguid2 = explode("\n", $duinfoguid1);
        $duinfoguid3 = $duinfoguid2[0];
        $duinfoguid4 = $this->strip_space($duinfoguid3);
        $pinfo['guids'] = $duinfoguid4;
        
        $pinfos[] = $pinfo;

        
        return $pinfos;
        
        }

		
    public function dump_user($clientid) {
		$this->send_command("dumpuser " . $clientid);
		$userinfo = $this->get_response();
		//echo $userinfo;
		if (!$userinfo) {
			return false;
		}

		$cvarlines = explode("\n", $userinfo);
		$cvars = array();
		for($i = 2; $i < count($cvarlines); $i++) {
			$line = trim(preg_replace('/\s\s+/', ' ', $cvarlines[$i]));
			$cvarlinetokens = explode(" ", $line, 2);
			if (count($cvarlinetokens) != 2) {
				continue;
			}
			$cvars[$cvarlinetokens[0]] = $cvarlinetokens[1];

		}
		return $cvars;
	}
//Get rid
	public function byebye($name,$slotnum,$ip,$reply){
	$base = substr_replace(dirname(__FILE__),'',-5);
	include("$base/includes/logincfg.php"); //Database file	
	
		//Slap 'em!
			$cmd = "slap $slotnum";
			$this->send_command($cmd);
			sleep(1);
		//Yell at 'em!
			$cmd = "bigtext $reply";
			$this->send_command($cmd);
			sleep(1);
		//Kick 'em while they're down!
			$cmd = "kick $slotnum";
			$this->send_command($cmd);
			sleep(1);
		//Don't let the door hit ya where the good lord split ya!
			$cmd = "addip $ip";
			$this->send_command($cmd);
			sleep(1);
		//Add them as banned to the player database
			mysql_query("UPDATE `players` SET `banned`='Yes' WHERE `name`='$name' AND `ip`='$ip' ");
		//Let them know you mean business!
			$cmd = "say \"$name was Banned by URsTats!\"";
			$this->send_command($cmd);
			
			return $cheat_response_b;
	}

//Check for cheaters, then mess them up!
	public function check_cheat($guid,$qport,$slotnum,$name,$ip){
	$base = substr_replace(dirname(__FILE__),'',-5);
	include("$base/includes/logincfg.php"); //Database file		
	
	$cr1 = "\"Cheaters are not allowed here!\"";
	$cr2 = "\"Names like that aren't welcomed here!\"";
	$cr3 = "\"Take your shenanigans elsewhere $name\"";
	
		$cheat = mysql_query("SELECT * FROM `ban_me`"); //Check Database against current player info		
		 //Make sure we don't pass empty information and get an error
			$cheat_rows = mysql_num_rows($cheat); //Count amount of results returned
		while($data = mysql_fetch_assoc($cheat)){
			$d_guid = $data['guid'];
			$d_name = stripslashes($data['name']);
			$d_ip   = $data['ip'];
			$d_qport= $data['qport'];
			$d_id   = $data['id'];
			
			if($d_guid !== ""){
				if(strstr($guid, $d_guid)){
					$reply = $cr1; 
					$this->byebye($name,$slotnum,$ip,$reply,$id);
					}
				}
			elseif($d_name !== ""){			
				if(strstr(strtolower($name), strtolower($d_name))){
					$reply = $cr2;
					$this->byebye($name,$slotnum,$ip,$reply,$id);
					}
				}
			elseif($d_ip !== ""){				
				if(strstr($ip, $d_ip)){
					$reply = $cr3;				
					$this->byebye($name,$slotnum,$ip,$reply,$id);
					}
				}
			elseif($d_qport !== ""){
				if(strstr($qport, $d_qport)){
					$reply = $cr1;
					$this->byebye($name,$slotnum,$ip,$reply,$id);
					}
				else{
					$cheat_response = "-";
					}
				}					

			}

		return $cheat_response;
	

}
}	
?>