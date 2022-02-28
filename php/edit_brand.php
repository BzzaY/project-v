<?php

	session_start();

	include 'dbconnect.php';

	$brand_id = $_POST['brand_id'];
	$brand_name = $_POST['brand_name'];
	$brand_status = $_POST['brand_status'];

	$sql1 = "update brand set brand_name='$brand_name',brand_status='$brand_status' where brand_id='$brand_id'";

	if(mysqli_query($conn,$sql1))
	{
		$_SESSION['edit_brand']="Brand Successfully Edited!";
	}
	else
	{
		$_SESSION['edit_brand']="Brand can't be Edited!";
	}
	header('location: ../home.php');

?>