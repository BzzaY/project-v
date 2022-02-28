<?php

	session_start();

	include 'dbconnect.php';

	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];

	$sql = "insert into user(username,email,password) values('$username','$email','$password')";

	$readsql = "select * from user";
	$result = mysqli_query($conn,$readsql);
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			if($row['email']==$email)
			{
				$_COOKIE['signupduplicate']="true1";
			}
			if($row['username']==$username)
			{
				$_COOKIE['signupduplicate']="true";
			}
		}
		if(isset($_COOKIE['signupduplicate']))
		{
			if($_COOKIE['signupduplicate']=="true")
			{
				$_SESSION['signup']="Username Already Exists!";
			}
			else if($_COOKIE['signupduplicate']=="true1")
			{
				$_SESSION['signup']="Email Already Exists!";
			}
			else
			{
				$_SESSION['signup']="User Account Created!";
				mysqli_query($conn,$sql);
			}
			$_COOKIE['signupduplicate']="false";
		}
		else
		{
			$_SESSION['signup']="User Account Created!";
			mysqli_query($conn,$sql);
		}
	}
	else
	{
		$_SESSION['signup']="User Account Created!";
		mysqli_query($conn,$sql);
	}

	$_SESSION['status']="signup";
	header('location: ../index.php');

?>