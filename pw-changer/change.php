<?php
include 'db.php';
$id = $_POST["id"];
$apw = strToUpper($_POST["apw"]);
$npw = strToUpper($_POST["npw"]);
$npw2 = strToUpper($_POST["npw2"]);
if ($npw != $npw2)
{
	echo "Neue Passwörter müssen übereinstimmen!";
	exit();
}
if ($npw == $apw)
{
	echo "Man kann nicht das gleiche Passwort nehmen!";
	exit;
}
$sql = "SELECT * FROM `account` where `id` = '" .$id. "';";
mysql_query($sql);
$query = mysql_query($sql);
$data = mysql_fetch_array($query);
$name = $data["username"];
$aapw = $data["sha_pass_hash"];
$vergleich = "$name:$apw";

if (sha1($name. ":" .$apw) === $aapw)
{
	$sql = "UPDATE `account` SET `sha_pass_hash`='" .sha1($name. ":" .$npw). "' WHERE (`id`='" .$id. "');";
	mysql_query($sql);
	echo "Erfolgreich ge&auml;ndert!";
}
else {
	echo "Passwort falsch!";
}
?>