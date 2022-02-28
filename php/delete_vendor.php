<?php

	session_start();

	include 'dbconnect.php';

	$vendor_id = $_POST['vendor_id'];

	$sql1 = "delete from vendor where vendor_id = '$vendor_id'";

	if(mysqli_query($conn,$sql1))
	{
		$_SESSION['delete_vendor']="Vendor Successfully Deleted!";
	}
	else
	{
		$_SESSION['delete_vendor']="Vendor can't be Deleted!";
	}
	header('location: ../home.php');

?>