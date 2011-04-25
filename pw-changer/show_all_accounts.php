<?php
include 'db.php';
$sql = "SELECT * FROM account ORDER BY `id`;";
$query = mysql_query($sql);
echo "<center><table border=\"1\"><tr>";
echo "<td>ID</td>";
echo "<td>Accountname</td></tr>";

while ($row = mysql_fetch_object($query))
{
	echo "<tr>";
	echo "<td>";
	echo "$row->id";
	echo "</td>";
	echo "<td>";
	echo "$row->username";
}
	echo "</tr>";
	echo "</table>";
?>

<html>
<head>
<title>
Alle User anzeigen
</title>
</head>
<body>
<center>
<a href="javascript:history.back()">Zur&uuml;ck</a>
</center>
</body>
</html>