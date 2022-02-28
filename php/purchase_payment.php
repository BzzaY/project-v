<?php

	session_start();

	include 'dbconnect.php';

	$bill_no = $_POST['bill_no'];
	$supplier_id = $_POST['supplier_id'];
	$due = $_POST['due'];
	$payment = $_POST['payment'];
	$amount = $_POST['amount'];
	$total = $_POST['total'];

	if($due>=$amount)
	{
		if($payment=="cash")
		{
			$sql = "update purchases_payment set cash = cash + '$amount' where bill_no = '$bill_no' && supplier_id = '$supplier_id'";
			if(mysqli_query($conn,$sql))
			{
				$_SESSION['purchases_payment']="Payment Successfully Made!";
			}
			else
			{
				$_SESSION['purchases_payment']="Payment can't be Made!";
			}
		}
		else
		{
			$sql = "update purchases_payment set bank = bank + '$amount' where bill_no = '$bill_no' && supplier_id = '$supplier_id'";
			if(mysqli_query($conn,$sql))
			{
				$_SESSION['purchases_payment']="Payment Successfully Made!";
			}
			else
			{
				$_SESSION['purchases_payment']="Payment can't be Made!";
			}
		}	
	}
	else
	{
		$_SESSION['purchases_payment']="Bill Amount Invalid!";
	}
	header('location: ../home.php');

?>