<?php

	session_start();

	include 'dbconnect.php';

	$vendor_name = $_POST['vendor_name'];
	$vendor_address = $_POST['vendor_address'];
	$vendor_contact = $_POST['vendor_contact'];

	$sql1 = "insert into vendor(vendor_name,vendor_address,vendor_contact) values('$vendor_name','$vendor_address','$vendor_contact')";

		
	if($vendor_contact>999999999&&$vendor_contact<10000000000)
	{
		if(mysqli_query($conn,$sql1))
		{
			$_SESSION['add_vendor'] = "Vendor Successfully Added!";
		}
		else
		{
			$_SESSION['add_vendor'] = "Vendor can't be Added!";
		}
	}
	else
	{
		$_SESSION['add_vendor']="Invalid Contact No.";
	}

	header('location: ../home.php');

?>