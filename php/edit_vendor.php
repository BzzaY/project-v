<?php

	session_start();

	include 'dbconnect.php';

	$vendor_id = $_POST['vendor_id'];
	$vendor_name = $_POST['vendor_name'];
	$vendor_address = $_POST['vendor_address'];
	$vendor_contact = $_POST['vendor_contact'];

	$sql1 = "update vendor set vendor_name='$vendor_name',vendor_address='$vendor_address',vendor_contact='$vendor_contact' where vendor_id='$vendor_id'";

	if($vendor_contact>999999999&&$vendor_contact<10000000000)
	{
		if(mysqli_query($conn,$sql1))
		{
			$_SESSION['edit_vendor']="Vendor Successfully Edited!";
		}
		else
		{
			$_SESSION['edit_vendor']="Vendor can't be Edited!";
		}
	}
	else
	{
		$_SESSION['edit_vendor']="Invalid Contact No.";
	}
	header('location: ../home.php');

?>