<?php

	session_start();

	include 'dbconnect.php';

	$username = $_POST['username'];
	$password = $_POST['password'];


	$readsql = "select * from user";
	$result = mysqli_query($conn,$readsql);
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			if($row['username']==$username && $row['password']==$password)
			{
				$_COOKIE['loginmatch']="true";
			}
		}
		if(isset($_COOKIE['loginmatch']))
		{
			if($_COOKIE['loginmatch']=="true")
			{
				$_SESSION['login']="Loging In...";
				setcookie("biller", $username, time() + (86400 * 30), "/");
				if(isset($_POST['checkbox']))
				{
					setcookie("username", $username, time() + (86400 * 30), "/");
					setcookie("password", $password, time() + (86400 * 30), "/");
				}
			}
			else
			{
				$_SESSION['login']="Username or Password Invalid!";
			}
			$_COOKIE['loginmatch']="false";
		}
		else
		{
			$_SESSION['login']="Username or Password Invalid!";
		}
	}
	else
	{
		$_SESSION['login']="Username or Password Invalid!";
	}

	$_SESSION['status']="login";
	header('location: ../index.php');

?>