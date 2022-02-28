<?php

	session_start();

	include 'dbconnect.php';

	$category_id = $_POST['category_id'];
	$category_name = $_POST['category_name'];
	$category_status = $_POST['category_status'];

	$sql1 = "update category set category_name='$category_name',category_status='$category_status' where category_id='$category_id'";

	if(mysqli_query($conn,$sql1))
	{
		$_SESSION['edit_category']="Category Successfully Edited!";
	}
	else
	{
		$_SESSION['edit_category']="Category can't be Edited!";
	}
	header('location: ../home.php');

?>