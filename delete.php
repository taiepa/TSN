<?php
session_start();
require('mysql.php');

$session = htmlspecialchars($_SESSION['username']);
$get = htmlspecialchars(trim($_GET['delete']));

$select = mysqli_query($connect, "SELECT * FROM `post` WHERE `username`='$session' AND `id`='$get';");
$select_admin = mysqli_query($connect, "SELECT * FROM `users` WHERE `username`='$session' AND `admin`='1';");

$row_admin = mysqli_num_rows($select_admin);
$row = mysqli_num_rows($select);

if($row == "1")
{
	mysqli_query($connect, "DELETE FROM `post` WHERE `id`='$get' AND `static`='0';");

	header("Location: index.php");
}
elseif($row_admin == "1")
{
        mysqli_query($connect, "DELETE FROM `post` WHERE `id`='$get' AND `static`='0';");

        header("Location: index.php");
}
else
{
	echo "Permission denied #$get<br /><a href='index.php'>Go back</a>";
}

?>
