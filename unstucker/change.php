<?php
include 'db.php';
session_start();
$uname = strToUpper($_POST['acc']);

$pwd = strToUpper($_POST['pwd']);

$str = $uname. ":" .$pwd ;

$pwd1 = sha1($str);

mysql_select_db($accs);

$sql = "SELECT * FROM `account` WHERE `username` = '" .$uname. "' AND `sha_pass_hash` = '" .$pwd1. "';";

$query = mysql_query($sql);
if (mysql_num_rows($query) == 0)
{
echo "Entweder ist der Name oder das Passwort falsch";
}

else 
{
	$data = mysql_fetch_array($query);
	echo "Erfolgreich eingeloggt!";
	$_SESSION['id'] = $data[id];
	$_SESSION['user'] = $uname;
	$_SESSION['password'] = $pwd1;
	echo "<html><meta http-equiv=\"refresh\" content=\"5; URL=index2.php\"></html>";
}

?>