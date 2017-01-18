<?php
session_start();
?>
<!DOCTYPE html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Taiepa Social Network - Profile Settings</title>
<link rel="stylesheet" type="text/css" href="design.css">
</head>
<body>
<?php
require('mysql.php');

require('class.php');

$object = new classes;

$object->check_ban();

if(isset($_SESSION['username']))
{
	echo "<p><a href='index.php'>Back to Profile!</a></p>";
	echo "<form action='profile.php' method='post'>";
	echo "<p><input type='text' name='image' placeholder='url to image (ex: http://lol.com/lol.jpg)' class='input_text' autofocus></p>";
        echo "<p><input type='submit' value='Update' class='button' /></p>";
	echo "</form>";

	$image = htmlspecialchars(trim($_POST['image']));
	$safe_image = mysqli_escape_string($connect, $image);
	$session = htmlspecialchars($_SESSION['username']);
	$select = mysqli_query($connect, "SELECT * FROM `users` WHERE `username`='$session';");

	if($get_info = mysqli_fetch_assoc($select))
	{
		$username = $get_info['username'];
		$img = $get_info['image'];

		echo "<p>Username:[<b>$username</b>]</p>";
		echo "<p>Image:[<b>$img</b>]</p>";
	}

	if(($image) && (filter_var($image, FILTER_VALIDATE_URL)))
	{
		mysqli_query($connect, "UPDATE `users` SET `image`='$safe_image' WHERE `username`='$session';");

	        header("Location: index.php");
	}
}
else
{
	header('Location: index.php');
}
?>
</body>
</html>
