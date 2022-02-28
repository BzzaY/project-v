<?php

	session_start();

	include 'dbconnect.php';

	$brand_name = $_POST['brand_name'];
	$brand_status = $_POST['brand_status'];

	$sql1 = "insert into brand(brand_name,brand_status) values('$brand_name','$brand_status')";

	$readsql = "select * from brand";
	$result = mysqli_query($conn,$readsql);
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			if($row['brand_name']==$brand_name)
			{
				$_COOKIE['brand_match']="true";
			}
		}
		if(isset($_COOKIE['brand_match']))
		{
			if($_COOKIE['brand_match']=="true")
			{
				$_SESSION['add_brand'] = "Brand Already Exists!";
			}
			else
			{
				mysqli_query($conn,$sql1);
				$_SESSION['add_brand'] = "Brand Successfully Added!";
			}
			$_COOKIE['brand_match']="false";
		}
		else
		{
			mysqli_query($conn,$sql1);
			$_SESSION['add_brand'] = "Brand Successfully Added!";
		}
	}
	else
	{
		mysqli_query($conn,$sql1);
		$_SESSION['add_brand'] = "Brand Successfully Added!";
	}
	header('location: ../home.php');

?>