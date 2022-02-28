<?php

	session_start();

	include 'dbconnect.php';

	$supplier_id = $_POST['supplier_id'];
	$supplier_name = $_POST['supplier_name'];
	$supplier_address = $_POST['supplier_address'];
	$supplier_contact = $_POST['supplier_contact'];

	$sql1 = "update supplier set supplier_name='$supplier_name',supplier_address='$supplier_address',supplier_contact='$supplier_contact' where supplier_id='$supplier_id'";
	
	if($supplier_contact>999999999&&$supplier_contact<10000000000)
	{
		if(mysqli_query($conn,$sql1))
		{
			$_SESSION['edit_supplier']="Supplier Successfully Edited!";
		}
		else
		{
			$_SESSION['edit_supplier']="Supplier can't be Edited!";
		}
	}
	else
	{
		$_SESSION['edit_supplier']="Invalid Contact No.";
	}
	header('location: ../home.php');

?>