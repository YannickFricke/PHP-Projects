<?php
$host = "localhost:3316";
//User verändern falls dieser nicht "root" ist
$user = "root";
//Passwort verändern falls dieses nicht "trinity" ist
$pw = "trinity";
//Hier die Datenbank angeben wo alle Charaktere drin sind
$chars = "characters";
//Hier die Datenbank angeben wo sich die User authentifizieren (im Normalfall "auth" oder "realmd"
$accs = "realmd";
//Einfach so lassen xD
$connect = mysql_connect($host, $user, $pw);
?>