<?php

	session_start();

	include 'dbconnect.php';

	$product_id = $_POST['product_id'];
	$product_name = $_POST['product_name'];
	$brand_id = $_POST['brand_id'];
	$category_id = $_POST['category_id'];

	$sql1 = "update product set product_name='$product_name',brand_id='$brand_id',category_id='$category_id' where product_id='$product_id'";

	if(mysqli_query($conn,$sql1))
	{
		$_SESSION['edit_product']="Product Successfully Edited!";
	}
	else
	{
		$_SESSION['edit_product']="Product can't be Edited!";
	}
	header('location: ../home.php');

?>