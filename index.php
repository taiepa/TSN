<?php
session_start();
?>
<!DOCTYPE html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Taiepa Social Network</title>
<link rel="stylesheet" type="text/css" href="design.css">
</head>
<body>
<?php
require('class.php');

$object = new classes;

$object->check_ban();

$get = htmlspecialchars(trim($_GET['user']));

if(!isset($_SESSION['username']))
{
	echo "<h1>Taiepa Social Network</h1>";
	echo "<div class='login'>";
	echo "<p><b><u>Login</u></b></p>";
	echo "<form action='index.php' method='post'>";
	echo "<p>";
	echo "<p><input type='text' name='username_login' placeholder='username' class='input_text' autofocus></p>";
	echo "<p><input type='password' name='password_login' placeholder='password' class='input_text' required></p>";
	echo "<p><input type='submit' value='Login' class='button' /></p>";
	echo "</p>";
	echo "</form>";
	$object->login();
	echo "</div>";

	echo "<div class='register'>";
        echo "<p><b><u>Register</u></b></p>";
        echo "<form action='index.php' method='post'>";
        echo "<li><input type='text' name='username_register' placeholder='Choose your username' maxlength='32' class='input_text' autofocus></li>";
        echo "<li><input type='password' name='password_register' placeholder='Choose your password' maxlength='64' class='input_text' required></li>";
        echo "<li><input type='password' name='repassword_register' placeholder='Retype your password' maxlength='64' class='input_text' required></li>";
        echo "<p><input type='submit' value='Register' class='button' /></p>";
        echo "</form>";
        $object->register();
	echo "</div>";
}
if(isset($_SESSION['username']))
{
	$object->meny();

	echo "<div class='top'>";

	echo "<div class='search'>";
        echo "<form action='search.php' method='get'>";
	echo "<input type='text' name='search' placeholder='Search..' maxlength='32' />";
        echo "<input type='submit' value='Search' />";
	echo "</form>";
	echo "</div>";

        echo "<div class='likes'>";
        echo "<form action='index.php?user=$get' method='post'>";
        echo "<input type='submit' value='Like' name='like' class='button' />";
        echo "</form>";
        $object->write_likes();
        echo "</div>";

        echo "<div class='add_friend'>";
        echo "<form action='index.php?user=$get' method='post'>";
        echo "<input type='submit' value='Add friend' name='add_friend' class='button' />";
        echo "</form>";
        $object->add_friend();
        echo "</div>";

	echo "</div>";

	echo "<div class='last'>";
	echo "<p><b><u>Last Activity</u></b></p>";
        $object->last();
	echo "</div>";

	echo "<div class='write'>";
        echo "<form action='index.php?user=$get' method='post'>";
	echo "<p><textarea name='text' maxlength='1024' placeholder='Write a message..'></textarea></p>";
        echo "<p><input type='submit' value='Share' class='button' /></p>";
	echo "</form>";
	$object->write_text();
	echo "</div>";

	echo "<div class='header'>$get's Taiepa!</div>";

	echo "<div class='wall'>";
	$object->read_text();
	echo "</div>";

	echo "<div class='read_likes'>";
        $object->read_likes();
	echo "</div>";

	echo "<div class='read_message'>";
	echo "<p><b><u>$get's inbox</u></b></p>";
	$object->read_message();
	echo "</div>";

        $object->image();
}

?>
</body>
</html>
