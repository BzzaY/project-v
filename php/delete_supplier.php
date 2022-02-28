<?php

	session_start();

	include 'dbconnect.php';

	$supplier_id = $_POST['supplier_id'];

	$sql1 = "delete from supplier where supplier_id = '$supplier_id'";

	if(mysqli_query($conn,$sql1))
	{
		$_SESSION['delete_supplier']="Supplier Successfully Deleted!";
	}
	else
	{
		$_SESSION['delete_supplier']="Supplier can't be Deleted!";
	}
	header('location: ../home.php');

?>