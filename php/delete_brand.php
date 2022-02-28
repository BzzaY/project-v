<?php

	session_start();

	include 'dbconnect.php';

	$brand_id = $_POST['brand_id'];

	$sql1 = "delete from brand where brand_id = '$brand_id'";

	if(mysqli_query($conn,$sql1))
	{
		$_SESSION['delete_brand']="Brand Successfully Deleted!";
	}
	else
	{
		$_SESSION['delete_brand']="Brand can't be Deleted!";
	}
	header('location: ../home.php');

?>