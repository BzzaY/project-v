<?php

	session_start();

	include 'dbconnect.php';

	$supplier_name = $_POST['supplier_name'];
	$supplier_address = $_POST['supplier_address'];
	$supplier_contact = $_POST['supplier_contact'];

	$sql1 = "insert into supplier(supplier_name,supplier_address,supplier_contact) values('$supplier_name','$supplier_address','$supplier_contact')";

		
	if($supplier_contact>999999999&&$supplier_contact<10000000000)
	{
		if(mysqli_query($conn,$sql1))
		{
			$_SESSION['add_supplier'] = "Supplier Successfully Added!";
		}
		else
		{
			$_SESSION['add_supplier'] = "Supplier can't be Added!";
		}
	}
	else
	{
		$_SESSION['add_supplier']="Invalid Contact No.";
	}
	
	header('location: ../home.php');

?>