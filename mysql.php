<?php
/*
	Taiepa Official Database Handler: https://txin.pro/
*/

$connect = new mysqli("txin.pro", "user", "password", "taiepaDB");

mysqli_set_charset($connect,"utf8");

if(mysqli_connect_errno())
{
	die("<pre>MYSQL_ERROR DETECTED, CONTACT ADMINISTRATOR!</pre>");
}

?>
