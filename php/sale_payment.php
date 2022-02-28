<?php

	session_start();

	include 'dbconnect.php';

	$bill_no = $_POST['bill_no'];
	$due = $_POST['due'];
	$payment = $_POST['payment'];
	$amount = $_POST['amount'];
	$total = $_POST['total'];

	if($due>=$amount)
	{
		if($payment=="cash")
		{
			$sql = "update sales_payment set cash = cash + '$amount' where bill_no = '$bill_no'";
			if(mysqli_query($conn,$sql))
			{
				$_SESSION['sales_payment']="Payment Successfully Received!";
			}
			else
			{
				$_SESSION['sales_payment']="Payment can't be Made!";
			}
		}
		else
		{
			$sql = "update sales_payment set bank = bank + '$amount' where bill_no = '$bill_no'";
			if(mysqli_query($conn,$sql))
			{
				$_SESSION['sales_payment']="Payment Successfully Received!";
			}
			else
			{
				$_SESSION['sales_payment']="Payment can't be Made!";
			}
		}	
	}
	else
	{
		$_SESSION['sales_payment']="Bill Amount Invalid!";
	}
	header('location: ../home.php');

?>