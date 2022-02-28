<?php

	session_start();

	include 'dbconnect.php';

	$bill_no = $_POST['bill_no'];

	$sql1 = "delete from sales_history where bill_no = '$bill_no'";
	$sql2 = "delete from sales where bill_no = '$bill_no'";
	$sql3 = "delete from sales_payment where bill_no = '$bill_no'";

	$sql4 = "select * from sales";
	$result = mysqli_query($conn,$sql4);
	if(mysqli_num_rows($result)>0)
	{
		while ($row = mysqli_fetch_array($result))
		{
			if($row['bill_no']==$bill_no)
			{
				$sql = "update product set product_quantity = product_quantity + '".$row['sale_quantity']."' where product_id = '".$row['product_id']."'";
				if(mysqli_query($conn,$sql))
				{

				}
				else
				{
					$_COOKIE['error']="true";
				}
			}
		}
	}
	if(!isset($_COOKIE['error']))
	{
		if(mysqli_query($conn,$sql1)&&mysqli_query($conn,$sql2)&&mysqli_query($conn,$sql3))
		{
			$_SESSION['delete_sale']="Sale Bill Successfully Deleted!";
		}
		else
		{
			$_SESSION['delete_sale']="Sale Bill can't be Deleted!";
		}
	}
	else
	{
		$_SESSION['delete_sale']="Sale Bill can't be Deleted!";
	}
	header('location: ../home.php');

?>