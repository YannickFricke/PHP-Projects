<?php
session_start();
include 'db.php';
mysql_select_db($chars);
$name = $_POST['name'];
$sql = "SELECT * FROM `characters` where `account` = '". $_SESSION['id']. "' AND `name` = '" .$name."';";
$query = mysql_query($sql);
$data = mysql_fetch_array($query);
$race = $data[race];
include 'cords.php';
mysql_query($sql);
if (mysql_affected_rows($connect)== 1)
{
	echo "Charakter wurde in die Hauptstadt der jeweiligen Rasse teleportiert!";
}
else 
{
	echo "Fehler bei der Verarbeitung!";
}
?>