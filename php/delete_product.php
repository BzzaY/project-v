<?php

	session_start();

	include 'dbconnect.php';

	$product_id = $_POST['product_id'];

	$sql1 = "delete from product where product_id = '$product_id'";

	if(mysqli_query($conn,$sql1))
	{
		$_SESSION['delete_product']="Product Successfully Deleted!";
	}
	else
	{
		$_SESSION['delete_product']="Product can't be Deleted!";
	}
	header('location: ../home.php');

?>