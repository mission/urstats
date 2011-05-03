<?php
//DO NOT MODIFY THIS FILE!

if(isset($_POST['submit'])){
	$file1 = substr_replace(dirname(__FILE__), '', -8)."/includes/config.php";
	$fp    = fopen($file1 , "w");
	
	$host  = $_POST['host'];
	$user  = $_POST['user'];
	$pass  = $_POST['pass'];
	$dbase = $_POST['dbase'];
	$site  = $_POST['site'];
	$script= $_POST['script'];

//config.php	
$contents = "<?php

if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly'); //<-- DO NOT CHANGE

//Only edit Database config!
/* Database config */
\$db_host		= '$host';
\$db_user		= '$user';
\$db_pass		= '$pass';
\$db_database	= '$dbase';
/*end config*/




//DO NOT CHANGE ANYTHING BELOW
\$link = mysql_connect(\$db_host,\$db_user,\$db_pass) or die('Unable to connect to database.');
mysql_select_db(\$db_database,\$link);
mysql_query('SET names UTF8');

//Universal Variables
\$dir = substr_replace(dirname(__FILE__),'',-9);
\$c_rand = substr_replace(md5(\$dir),'',-20); //Hash string to generate unique session cookie per install
\$uname = addslashes(\$_COOKIE[\"alh_{\$c_rand}_u\"]);
\$pword = \$_COOKIE['alh_urts_p'];

//Check if logged in
include_once(\"defs.php\");
	\$result = mysql_query(\"SELECT * FROM `urts_users` WHERE `username`='\$uname'\") or die (mysql_error());
if (!\$result){
	header(\"location: {\$linkrel}/signin.php\");
	die();
	}
if(!\$_COOKIE[\"alh_{\$c_rand}_u\"]){
	header(\"location: {\$linkrel}/signin.php\");
	die();
	}
	
while (\$data = mysql_fetch_assoc(\$result)){
	\$lid = \$data['login'];
		if (\$lid == '0') {
			header(\"location: {\$linkrel}/signin.php\");
			die();
		}
		if(\$data['password'] != \$pword){
			header(\"location: {\$linkrel}/signin.php\");
			die();
		}
		elseif(\$data['username'] != \$uname){
			header(\"location: {\$linkrel}/signin.php\");
			die();
		}
		
}



?>";
	fputs($fp , $contents);
	fclose($fp);
	
//logincfg.php
	$file2 = substr_replace(dirname(__FILE__), '', -8)."/includes/logincfg.php";
	$fp = fopen($file2 , "w");
$contents2 ="<?php
if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');//<-- DO NOT CHANGE


/* Database config */
\$db_host		= '$host';
\$db_user		= '$user';
\$db_pass		= '$pass';
\$db_database	= '$dbase';
/*end config*/


\$link = mysql_connect(\$db_host,\$db_user,\$db_pass) or die('Unable to connect to database.');
mysql_select_db(\$db_database,\$link);
mysql_query('SET names UTF8');

//Cookie Expiration
\$cookie_expire = 3600;

?>";
	fputs($fp , $contents2);
	fclose($fp);
	
//defs.php
	$file3 = substr_replace(dirname(__FILE__), '', -8)."/includes/defs.php";
	$fp = fopen($file3 , "w");
	$in_path = substr_replace($_SERVER['PHP_SELF'],'', -26);
	$script = $_POST['script'];
	
$contents3 ="<?php
// Definitions File
\$html_root = \$_SERVER['DOCUMENT_ROOT']; //Do not change <--

//Changeable defs - ONLY EDIT THESE -
\$site_name    = \"$site\"; //Your full site address WITHOUT the trailing /. Example: http://www.alphahusky.info
\$install_path = \"$in_path\"; // !IMPORTANT! Read Below -->
/*--------------------------
\$install_path --> Where this install is in relation to the root directory. 
Example: If it's at, http://www.yoursite.com/server2/, then
\$install_path = \"/server2\";
Do NOT put in the full web address as it will NOT work.
Leave empty if it in your root directory. 
If it were just at http://www.yoursite.com, then
\$install_path = \"\";
---------------------------*/

\$script_path  = \"$script\"; 
/*--------------------------
\$script_path --> *Optional*. This is to use extra CVARS I made. 
Example: \$script_path = \"/home/urt/urbanterror/q3ut4\";
The extra CVARS may ONLY be used IF the web server is on the
same box as your game server.
---------------------------*/









//Change if instructed to do so
\$include_path = \$base.\"/\";  //By default, URsTats should be installed to the root directory of your server.


//Other defs - DO NOT MODIFY
\$linkrel = \"\$site_name\".\$install_path.\"/urstats\";
\$here = \$_SERVER['PHP_SELF'];
\$prntdate = date('l F j\, Y');
\$err12 = \"ERROR #: 12 - There was an error with authentication. Please contact the system administrator.\";
\$adfuncl = (\$include_path.'admin/func/');
include('get_theme.php');
\$theme = \$u_theme;
\$theme_path = (\$include_path.'theme/'.\$theme.'/');
\$u_theme_path = (\$linkrel.'/theme/'.\$theme.'/');
\$copyright = \"URsTats designed by: Alphahusky (aka Puss-N-Boots). <a href='http://www.alphahusky.info'>http://www.alphahusky.info</a>\";
?>";

	fputs($fp , $contents3);
	fclose($fp);

	echo installdb();
	die();
}

echo" 
<div align='center'>
<table>
	<tr>
		<th><h1>URsTats Setup</h1></th>
	</tr>
</table>
<br />
<form action='' method='post'>
<table>
	<tr>
		<td colspan='2'><h2>Website information</h2></td>
	</tr>
	<tr>
		<td>Web Address (No trialing slashes)</td><td><input type='text' name='site' value='' /></td>
	</tr>
	<tr>
		<td colspan='3'><h2>Database Information</h2></td>
	</tr>
	<tr>
		<td>Host(Typically localhost)</td><td><input type='text' name='host' value='localhost' /></td>
	</tr>
	<tr>
		<td>Username</td><td><input type='text' name='user' value='' /></td>
	</tr>
	<tr>
		<td>Password</td><td><input type='text' name='pass' value='' /></td>
	</tr>
	<tr>
		<td>Database</td><td><input type='text' name='dbase' value='' /></td>
	</tr>
	<tr>
		<td colspan='2'><h2>Game info (OPTIONAL)</h2></td>
	</tr>
	<tr>
		<td>Server path to q3ut4 folder</td><td><input type='text' name='script' value='/home/urt/urbanterror/' /></td>
	</tr>
	<tr>
		<td colspan='2'><input type='submit' name='submit' value='Submit' /></td>
	</tr>
</table></form>
<br />
<p><h2>Important Notes:</h2></p>
<p>&bull; Web address MUST be in this format: http://www.yoursite.com. If you are using on local machine you may want to use http://127.0.0.1 .</p>
<p>This must ONLY be the web address. Even if you plan on using multiple instances if this software on one box http://www.yoursite.com/ is incorrect</p>
<p> due to the trailing slash</p>
<p>&bull; Host - Host is generally localhost if it is on your server. If it is not, you may need to ask your Server host to provide that information to you</p>
<p>&bull; Username and Password to your database</p>
<p>&bull; Database - Name of the database you set up for URsTats</p>
<p>&bull; Server Path to q3ut4 - This is the server path to your q3ut4 directory if you have it installed on the same machine as your web server.</p>
<p>This option is only for the use of the 2 additional CVARS I added to the Console Emulator. See the INSTRUCTIONS.txt for further information.</p>
<p><h2>It is highly recommended to create a separate database for URsTats to avoid conflicts with any other software</h2></p>
</div>";

function installdb(){
define("INCLUDE_CHECK", true);
include('../includes/logincfg.php');

$er = "<p>There was an error creating table ";
$sc = " table was created successfully!</p>";

mysql_query("SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";") or die("There was an error setting SQL_MODE: Line 4. Please check that your config.php file is set correctly.");

echo"<p>Mode set correctly!</p>";

//admin_log
mysql_query("
CREATE TABLE IF NOT EXISTS `admin_log` (
  `id` int(11) NOT NULL auto_increment,
  `admin` varchar(60) NOT NULL,
  `ip` varchar(60) NOT NULL,
  `command` varchar(1000) NOT NULL,
  `timestamp` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;") or die($er."admin_log.");

echo "<p>admin_log".$sc;

//ban_me
mysql_query("
CREATE TABLE IF NOT EXISTS `ban_me` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `guid` varchar(50) NOT NULL,
  `qport` varchar(50) NOT NULL,
  `desc` varchar(20000) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;") or die($er."ban_me.");

echo "<p>ban_me".$sc;

mysql_query("
INSERT INTO `ban_me` (`id`, `name`, `ip`, `guid`, `qport`, `desc`) VALUES
(1, '', '', 'Jewgasm', '', 'Cheat Program - iTerror'),
(2, '', '', 'ktkwftmkemfew', '', 'Cheat Program - Unknown'),
(3, '', '', '', '1337', 'Cheat Program - Unknown');")or die("There was an error updating table ban_me.");

echo "<p>ban_me was updated successfully!</p>";

//players
mysql_query("
CREATE TABLE IF NOT EXISTS `players` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `guid` varchar(100) NOT NULL,
  `last` int(100) NOT NULL,
  `added` int(100) NOT NULL,
  `laston` varchar(1000) NOT NULL,
  `score` int(11) NOT NULL default '0',
  `qport` varchar(200) NOT NULL,
  `banned` varchar(3) NOT NULL default 'No',
  `slot` int(20) NOT NULL,
  `ping` int(3) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;") or die($er."players");

echo "<p>players".$sc;

//private_msg
mysql_query("
CREATE TABLE IF NOT EXISTS `private_msg` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(1000) NOT NULL,
  `body` varchar(10000) NOT NULL,
  `to` varchar(2000) NOT NULL,
  `from` varchar(2000) NOT NULL,
  `read` int(1) NOT NULL default '0',
  `timestamp` varchar(20) NOT NULL,
  `in_del` int(1) NOT NULL default '0',
  `out_del` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;")or die($er."private_msg");

echo "<p>private_msg".$sc;

//servers
mysql_query("
CREATE TABLE IF NOT EXISTS `servers` (
  `id` int(10) NOT NULL auto_increment,
  `order` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `port` varchar(100) NOT NULL,
  `version` varchar(100) NOT NULL,
  `rconpass` varchar(100) NOT NULL,
  `Status` varchar(100) NOT NULL default 'Online',
  `current_map` varchar(60) NOT NULL,
  `next_map` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;")or die($er."servers");

echo "<p>servers".$sc;

//urts_users
mysql_query("
CREATE TABLE IF NOT EXISTS `urts_users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `theme` varchar(200) NOT NULL,
  `login` varchar(11) NOT NULL,
  `type` varchar(20) NOT NULL default 'admin',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;")or die($er."urts_users");

echo "<p>urts_users".$sc;

echo "<p>All tables inserted successfully!</p>";
echo "<p><a href='install2.php'>Next</a></p>";
}

?>