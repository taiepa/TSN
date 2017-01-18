<?php
session_start();
?>

<!DOCTYPE html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Taiepa Social Network - Search</title>
<link rel="stylesheet" type="text/css" href="design.css">
</head>
<body>
<?php
if(isset($_SESSION['username']))
{
	require('class.php');

	$object = new classes;

	$object->check_ban();

	$object->search();
}
else
{
	header('Location: index.php');
}
?>
</body>
</html>
