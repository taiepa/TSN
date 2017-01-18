<?php
//database setup
//epicnet - users:id,username,password,date,ip,ban,admin,image,likes
//epicnet - post:id,username,date,text,owner
//epicnet - likes:id,followed,by,date
//epicnet - message:id,to,from,date
//epicnet - friends:id,username,friend,date
class classes
{
	//Menu
	public function meny()
	{
		echo "<lu class='meny'>";
		echo "<li><a href='index.php'><b>Profile</b></a></li>";
                echo "<li><a href='message.php'>Private Message</a></li>";
                echo "<li><a href='profile.php'>Profile Settings</a></li>";
		echo "<li><a href='logout.php'>Logout!</a></li>";
		echo "</lu>";
	}
	//Login
	public function login($username,$password)
	{
		require('mysql.php');

		$username = htmlspecialchars(trim($_POST['username_login']));
		$password = htmlspecialchars(trim($_POST['password_login']));

		if(($username) && ($password))
		{
			$safe = mysqli_escape_string($connect, $username);

			$string = sha1($password);
			$sha1 = sha1($string);
			$md5 = md5($sha1);

			$select = mysqli_query($connect, "SELECT * FROM `users` WHERE `username`='$safe' AND `password`='$md5';");

			if(mysqli_num_rows($select) == 1)
			{
				$_SESSION['username'] = $username;
				header("Location: index.php");
			}
		}
	}
	//Register
        public function register($username,$password)
        {
                require('mysql.php');

                $username = htmlspecialchars(trim($_POST['username_register']));
                $password = htmlspecialchars(trim($_POST['password_register']));
		$repassword = htmlspecialchars(trim($_POST['repassword_register']));

                if(($username) && ($username != $password) && ($password == $repassword) && ($password) && (ctype_alpha($username)))
                {
                        $safe = mysqli_escape_string($connect, $username);

                        $string = sha1($password);
                        $sha1 = sha1($string);
                        $md5 = md5($sha1);

			$date = date('l dS F Y H:i:s');

                        $ip = $_SERVER['REMOTE_ADDR'];

                        $select_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `username`='$safe';");
                        $select_ip = mysqli_query($connect, "SELECT * FROM `users` WHERE `ip`='$ip';");

                        if(mysqli_num_rows($select_user) == 1)
                        {
				echo "<p>Username already exist, please choose another!</p>";
                        }
                        elseif(mysqli_num_rows($select_ip) == 1)
                        {
                                echo "<p>You already have an account, try login!</p>";
                        }
			elseif(mysqli_num_rows($select_user) == 0)
			{
				mysqli_query($connect,"INSERT INTO `post` (`username`,`date`,`text`,`owner`,`static`) VALUES ('".$safe."','".$date."','Welcome to Taiepa Social Network!','".$safe."','1');");
				mysqli_query($connect,"INSERT INTO `users` (`username`,`password`,`date`,`admin`,`ip`,`ban`,`image`,`likes`) VALUES ('".$safe."','".$md5."','".$date."','0','".$ip."','0','img/image-not-found.jpg','0');");

                                echo "<p>Welcome! You are now able to login with: $username. Have fun!</p>";
			}
                }
        }
	//Write
        public function write_text()
        {
                require('mysql.php');

		$session = htmlspecialchars($_SESSION['username']);
		$text = htmlspecialchars(trim($_POST['text']));
                $get = htmlspecialchars($_GET['user']);
                $safe_text = mysqli_escape_string($connect, $text);
                $safe_get = mysqli_escape_string($connect, $get);

                $date = date('l dS F Y H:i:s');

		if($text)
		{
                        mysqli_query($connect,"INSERT INTO `post` (`username`,`date`,`text`,`owner`,`static`) VALUES ('".$session."','".$date."','".$safe_text."','".$safe_get."','0');");

			header("Location: index.php?user=$get");
		}
        }
	//Read
        public function read_text()
        {
                require('mysql.php');

		$session = htmlspecialchars($_SESSION['username']);
		$get = htmlspecialchars($_GET['user']);
                $safe = mysqli_escape_string($connect, $get);

		$select = mysqli_query($connect, "SELECT * FROM `post` WHERE `owner`='$safe' ORDER BY `id` DESC LIMIT 25;");

		$rows = mysqli_num_rows($select);

		if($rows == 0)
		{
			header("Location: index.php?user=$session");
		}
		elseif($rows != 0)
		{
			while($test = mysqli_fetch_assoc($select))
			{
				$username = $test['username'];
				$date = $test['date'];
				$text = nl2br($test['text']);
				$owner = $test['owner'];
				$id = $test['id'];

				echo "<p>Posted by: <a href='index.php?user=$username'><b>$username</a></b></p>";
				echo "<p>$text</p>";
				echo "<p>[<a href='delete.php?delete=$id'><b>x</b></a>][#$id][$date]</p><hr />";
			}
		}
        }
	//Image
	public function image()
	{
		require('mysql.php');

		$get = htmlspecialchars(trim($_GET['user']));

		$safe = mysqli_escape_string($connect, $get);

		$select = mysqli_query($connect, "SELECT * FROM `users` WHERE `username`='$safe';");

		if($get_image = mysqli_fetch_assoc($select))
		{
			$image = $get_image['image'];

			echo "<p><img src='$image' class='image' /></p>";
		}
	}
	//Search
	public function search()
	{
		require('mysql.php');

		$get = htmlspecialchars(trim($_GET['search']));

                $safe = mysqli_escape_string($connect, $get);

		$select = mysqli_query($connect, "SELECT * FROM `users` WHERE `username` LIKE '%$safe%';");

		$rows = mysqli_num_rows($select);

		if(($rows > 0) && ($get))
		{
			echo "<h3>Found $rows user.</h3>";

			while($get_info = mysqli_fetch_assoc($select))
			{
				$username = $get_info['username'];
				echo "<p><a href='index.php?user=$username' />$username</a></p>";
			}
		}
		else
		{
			header("Location: index.php");
		}
	}
	//Last Active
	public function last()
	{
		require('mysql.php');

		$select = mysqli_query($connect, "SELECT * FROM `post` ORDER BY `id` DESC LIMIT 5;");

		while($get_info = mysqli_fetch_assoc($select))
		{
			$username = $get_info['username'];
			$date = $get_info['date'];
			$owner = $get_info['owner'];

			echo "<p><a href='index.php?user=$owner' />$date<br />$username</a></p>";
		}
	}
	//Read Likes
	public function read_likes()
	{
		require('mysql.php');

		$get = htmlspecialchars(trim($_GET['user']));

                $safe = mysqli_escape_string($connect, $get);

		$select = mysqli_query($connect, "SELECT * FROM `users` WHERE `username`='$safe';");

		$rows = mysqli_num_rows($select);

		if($rows == 1)
		{
			if($get_info = mysqli_fetch_assoc($select))
			{
				$likes = $get_info['likes'];

				echo "Likes: <b>$likes</b>";
			}
		}
	}
        //Write Likes
        public function write_likes()
        {
                require('mysql.php');

                $like = htmlspecialchars($_POST['like']);

                $get = htmlspecialchars(trim($_GET['user']));

                $safe = mysqli_escape_string($connect, $get);

		$session = htmlspecialchars($_SESSION['username']);

                $select = mysqli_query($connect, "SELECT * FROM `users` WHERE `username`='$safe';");

		$select_likes = mysqli_query($connect, "SELECT * FROM `likes` WHERE `followed`='$safe' AND `by`='$session';");

                $rows = mysqli_num_rows($select);

                if($rows == 1)
                {
                        if($like)
                        {
                                if($get_info = mysqli_fetch_assoc($select))
                                {
		                        $date = date('Y-m-d H:i:s');

                                        $likes = $get_info['likes'];

                                        $add_like = $likes + 1;

					if((mysqli_num_rows($select_likes) == 0) && ($get != $session))
					{
						mysqli_query($connect,	"INSERT INTO `likes` (`followed`,`by`,`date`) VALUES ('".$safe."','".$session."','".$date."');");
						mysqli_query($connect, "UPDATE `users` SET `likes`='$add_like' WHERE `username`='$safe';");
						header('Location: index.php');
					}
                                }
                        }
                }
        }
	//Message
	public function message()
	{
		require('mysql.php');

		$session = htmlspecialchars($_SESSION['username']);

                $username = htmlspecialchars(trim($_POST['user']));

		$message = htmlspecialchars(trim($_POST['message']));

		$safe = mysqli_escape_string($connect, $message);

		$safe_username = mysqli_escape_string($connect, $username);

		$date = date('Y-m-d H:i:s');

		$select = mysqli_query($connect, "SELECT * FROM `users` WHERE `username`='$safe_username';");

		$rows = mysqli_num_rows($select);

		if(($message) && ($rows == 1))
		{
			mysqli_query($connect,  "INSERT INTO `message` (`to`,`from`,`message`,`date`) VALUES ('".$safe_username."','".$session."','".$safe."','".$date."');");

			header('Location: index.php');
		}
	}
	//Read Message
	public function read_message()
	{
		require('mysql.php');

		$session = htmlspecialchars($_SESSION['username']);

		$select = mysqli_query($connect, "SELECT * FROM `message` WHERE `to`='$session' ORDER BY `id` DESC LIMIT 10;");

		$rows = mysqli_num_rows($select);

		if($rows == 0)
		{
			echo "<p>Inbox is empty.</p>";
		}
		else
		{
			while($get_info = mysqli_fetch_assoc($select))
			{
				$message = $get_info['message'];

				$from = $get_info['from'];

				$date = $get_info['date'];

				echo "<p><b><a href='message.php'>$from</a></b>:$message</p><hr />";
			}
		}
	}
        //Add friend
        public function add_friend()
        {
		require('mysql.php');

		$session = htmlspecialchars($_SESSION['username']);

                $get = htmlspecialchars(trim($_GET['user']));

		$button = htmlspecialchars(trim($_POST['add_friend']));

                $safe = mysqli_escape_string($connect, $get);

		$select = mysqli_query($connect, "SELECT * FROM `friends` WHERE `username`='$session' AND `friend`='$safe';");

		$rows = mysqli_num_rows($select);

                $date = date('Y-m-d H:i:s');

		if($button)
		{
			if(($rows == 0) && ($get != $session))
			{
                	        mysqli_query($connect,  "INSERT INTO `friends` (`username`,`friend`,`date`) VALUES ('".$session."','".$safe."','".$date."');");
				header('Location: message.php');
			}
		}
	}
	//Ban
	public function check_ban()
	{
		require('mysql.php');

		$session = htmlspecialchars($_SESSION['username']);

		$select = mysqli_query($connect, "SELECT * FROM `users` WHERE `username`='$session' AND `ban`='1';");

		$rows = mysqli_num_rows($select);

		if($rows == 1)
		{
			die("<h1>You are banned!</h1>");
		}
	}
}

?>
