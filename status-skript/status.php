<?
/*
 * Copyright by FrickX @ http://frickx.jshost.net/forum/
 * Copyright by FrickX @ http://easy-emu.de/
 * Checkout http://frickx.jshost.net/forum/ for new versions and PHP-Scripts! ;)

 */

echo "<html><head><title>FrickX's Status Skript</title></head></html>";
echo "<table border=0>";
/*
 * Information for LoginServer
 */

$host = "127.0.0.1";
$port = "3724";

$f = @pfsockopen($host, $port, $errno, $errstr, 1);
if ($f <= 0 )
{
	echo "<tr><td>LoginServer</td><td><html><img src=\"off.png\"</img></html></td></tr>";
}
else
{
	echo"<tr><td>LoginServer</td><td><html><img src=\"on.png\"</img></html></td></tr>";
}

/*
 * Information for Realm 1
 * Just copy it for other Realms! ;) and change the port + realmname! ;)
 */

$host = "127.0.0.1";
$port = "8085";
$name = "MyRealm";
$f = @pfsockopen($host, $port, $errno, $errstr, 1);
if ($f <= 0 )
{
	echo "<tr><td>" .$name."</td><td><html><img src=\"off.png\"</img></html></td></tr>";
}
else
{
	echo "<tr><td>" .$name."</td><td><html><img src=\"on.png\"</img></html></td></tr><br />";
}

/* 
 * Information for uptime
 * Change the settings for your Server!
 */

$db_host = "127.0.0.1:3316";
$db_user = "root";
$db_pass = "trinity";

mysql_connect($db_host, $db_user, $db_pass) or die ("Can't connect with $host");
mysql_selectdb ("realmd");

$sql = mysql_query("SELECT * FROM `uptime` ORDER BY `starttime` DESC LIMIT 0, 1"); 
$row = mysql_fetch_array($sql);    

$day = floor($row['uptime'] / 86400);
$hour = floor($row['uptime'] / 3600) - $day * 24;
$min = floor($row['uptime'] / 60 / 60);
$uptime_string = $hour.' Stunde(n)';

echo "<tr><td>Server Uptime</td><td>$uptime_string</td></tr>";

/*
 * Get all online GMs
 */

$con = mysql_connect("127.0.0.1:3316", "root", "trinity") or die ("Die Angaben zu Verbindung stimmen nicht!");
$db = mysql_select_db("realmd") or die ("Konnte Datenbank nicht auswählen!");

$sql_gm = "SELECT * FROM `account`, `account_access` WHERE account_access.gmlevel >='1' AND account.online ='1' AND (account.id = account_access.id)";
$result_gm = mysql_query($sql_gm);
$row_gm = mysql_num_rows($result_gm);

echo "<tr><td>GM's online</td><td>" .$row_gm. "</td></tr>";

/*
 * Get all online Alliance-Players
 */

mysql_select_db("characters");
$sql = "SELECT SUM(`online`) FROM `characters` WHERE `race` IN(1,3,4,7,11)";
$query = mysql_query($sql) or die(mysql_error());
$data = mysql_result($query,0,0);
if ($data = 0)
{
	$data = "0";
}
echo "<tr><td>Allianzer Online</td><td> $data</td></tr>";

/*
 * Get all online Horde-Players
 */
$sql = "SELECT SUM(`online`) FROM `characters` WHERE `race` IN(2,5,6,8,10)";
$query = mysql_query($sql) or die(mysql_error());
$data = mysql_result($query,0,0);
if ($data = 0)
{
	$data = "0";
}
echo "<tr><td>Hordler Online</td><td> $data</td></tr>";
echo "</table>";
?>