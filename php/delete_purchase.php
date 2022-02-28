<?php

	session_start();

	include 'dbconnect.php';

	$bill_no = $_POST['bill_no'];
	$supplier_id = $_POST['supplier_id'];

	$sql1 = "delete from purchases_history where bill_no = '$bill_no' && supplier_id = '$supplier_id'";
	$sql2 = "delete from purchases where bill_no = '$bill_no' && supplier_id = '$supplier_id'";
	$sql3 = "delete from purchases_payment where bill_no = '$bill_no' && supplier_id = '$supplier_id'";

	$sql4 = "select * from purchases";
	$result = mysqli_query($conn,$sql4);
	if(mysqli_num_rows($result)>0)
	{
		while ($row = mysqli_fetch_array($result))
		{
			if($row['bill_no']==$bill_no&&$row['supplier_id']==$supplier_id)
			{
				$sql = "update product set product_quantity = product_quantity - '".$row['purchase_quantity']."' where product_id = '".$row['product_id']."'";
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
			$_SESSION['delete_purchase']="Purchase Bill Successfully Deleted!";
		}
		else
		{
			$_SESSION['delete_purchase']="Purchase Bill can't be Deleted!";
		}
	}
	else
	{
		$_SESSION['delete_purchase']="Purchase Bill can't be Deleted!";
	}
	header('location: ../home.php');

?>