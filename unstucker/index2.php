<?php
include 'db.php';
session_start();
if (!isset($_SESSION['user']))
{
	echo "Sie m&uuml;ssen eingeloggt sein!";
}
mysql_select_db($chars);

$sql = "SELECT * FROM `characters` WHERE `account` = '". $_SESSION['id']. "';";
$query = mysql_query($sql);
echo "<center>";
echo "Ihre Charaktere:";
echo "<table border=1>";
echo "<tr><td>Name</td><td>Rasse</td></tr>";
while ($row = mysql_fetch_object($query))
{
	include 'races.php';
	echo "<tr>";
	echo "<td>";
	echo "$row->name";
	echo "</td>";
	echo "<td>";
	echo $race;
	echo "</td>";
	echo "</tr>";
//	echo "$row->Vorname";
//	echo "$row->Nachname";
}
echo "</table>";
echo "</center>";
?>
<html>
<head>
<title>
Charakter ausw&auml;hlen
</title>
</head>
<body>
<form action="unstuck.php" method="post">
<p>Name des Charackters:<br><input name="name" type="text" size="30" maxlength="30"></p><br><input type="submit" value="Unstuck!">
</form>
</body>
</html>