<?php
session_start();
?>
<!DOCTYPE html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Taiepa Social Network - Private Message</title>
<link rel="stylesheet" type="text/css" href="design.css">
</head>
<body>
<?php
if(isset($_SESSION['username']))
{
	require('class.php');
	require('mysql.php');

	$object = new classes;

	$session = htmlspecialchars($_SESSION['username']);

	$select = mysqli_query($connect, "SELECT * FROM `friends` WHERE `username`='$session';");

	$rows = mysqli_num_rows($select);

	echo "<div class='message'>";
	echo "<p><a href='index.php'>Back to Profile!</a></p>";
        echo "<form action='message.php' method='post'>";
	echo "<select name='user' class='select' autofocus>";
	if($rows > 0)
	{
		while($get_info = mysqli_fetch_assoc($select))
		{
			$friend = $get_info['friend'];

			echo "<option value='$friend'>$friend</option>";
		}
	}
	else
	{
		echo "<option value=''>No friends!</option>";
	}
	echo "</select>";
        echo "<p><textarea name='message' maxlength='1024' placeholder='Message'></textarea></p>";
        echo "<p><input type='submit' value='Send' class='button' /></p>";
        echo "</form>";
        $object->message();
	echo "</div>";
}
?>
</body>
</html>
