<?php

	session_start();

	include 'dbconnect.php';

	$category_id = $_POST['category_id'];

	$sql1 = "delete from category where category_id = '$category_id'";

	if(mysqli_query($conn,$sql1))
	{
		$_SESSION['delete_category']="Category Successfully Deleted!";
	}
	else
	{
		$_SESSION['delete_category']="Category can't be Deleted!";
	}
	header('location: ../home.php');

?>