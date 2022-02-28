<?php

	session_start();

	include 'dbconnect.php';

	$product_name = $_POST['product_name'];
	$brand_id = $_POST['brand_id'];
	$category_id = $_POST['category_id'];
	$product_quantity = 0;

	$sql1 = "insert into product(product_name,brand_id,category_id,product_quantity) values('$product_name','$brand_id','$category_id',$product_quantity)";

	$readsql = "select * from product";
	$result = mysqli_query($conn,$readsql);
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			if($row['product_name']==$product_name&&$row['brand_id']==$brand_id&&$row['category_id']==$category_id)
			{
				$_COOKIE['product_match']="true";
			}
		}
		if(isset($_COOKIE['product_match']))
		{
			if($_COOKIE['product_match']=="true")
			{
				$_SESSION['add_product'] = "Product Already Exists!";
			}
			else
			{
				mysqli_query($conn,$sql1);
				$_SESSION['add_product'] = "Product Successfully Added!";
			}
			$_COOKIE['brand_match']="false";
		}
		else
		{
			mysqli_query($conn,$sql1);
			$_SESSION['add_product'] = "Product Successfully Added!";
		}
	}
	else
	{
		mysqli_query($conn,$sql1);
		$_SESSION['add_product'] = "Product Successfully Added!";
	}
	header('location: ../home.php');

?>