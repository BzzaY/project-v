<?php

	session_start();

	include 'dbconnect.php';

	$category_name = $_POST['category_name'];
	$category_status = $_POST['category_status'];

	$sql1 = "insert into category(category_name,category_status) values('$category_name','$category_status')";

	$readsql = "select * from category";
	$result = mysqli_query($conn,$readsql);
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			if($row['category_name']==$category_name)
			{
				$_COOKIE['category_match']="true";
			}
		}
		if(isset($_COOKIE['category_match']))
		{
			if($_COOKIE['category_match']=="true")
			{
				$_SESSION['add_category'] = "Category Already Exists!";
			}
			else
			{
				mysqli_query($conn,$sql1);
				$_SESSION['add_category'] = "Category Successfully Added!";
			}
			$_COOKIE['category_match']="false";
		}
		else
		{
			mysqli_query($conn,$sql1);
			$_SESSION['add_category'] = "Category Successfully Added!";
		}
	}
	else
	{
		mysqli_query($conn,$sql1);
		$_SESSION['add_category'] = "Category Successfully Added!";
	}
	header('location: ../home.php');

?>