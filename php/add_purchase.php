<?php

	session_start();

	include 'dbconnect.php';

	$bill_no = $_POST['bill_no'];
	$supplier_id = $_POST['supplier_id'];
	$discount = $_POST['discount'];

	$sql = "insert into purchases_history(bill_no,supplier_id,discount,biller) values('$bill_no','$supplier_id','$discount','".$_COOKIE['biller']."')";

	if(mysqli_query($conn,$sql))
	{
		$sql3 = "insert into purchases_payment(bill_no,supplier_id) values('$bill_no','$supplier_id')";
		mysqli_query($conn,$sql3);
		for($i = 1;$i<=$_COOKIE['rowCount'];$i++)
		{
			$product_id = $_POST['product_id'.$i];
			$purchase_rate = $_POST['purchase_rate'.$i];
			$purchase_quantity = $_POST['purchase_quantity'.$i];
			$sql1 = "insert into purchases(bill_no,supplier_id,product_id,purchase_rate,purchase_quantity) values('$bill_no','$supplier_id','$product_id','$purchase_rate','$purchase_quantity')";
			if(mysqli_query($conn,$sql1))
			{
				$_COOKIE['add_purchase'] = "true";
				$sql2 = "update product set product_quantity= product_quantity+'$purchase_quantity' where product_id = '$product_id'";
				mysqli_query($conn,$sql2);
			}
			else
			{
				$_COOKIE['add_purchase'] = "false";
			}
		}
		if(isset($_COOKIE['add_purchase']))
		{
			if($_COOKIE['add_purchase']=="true")
			{
				$_SESSION['add_purchase'] = "Purchase Successfully Made!";
			}
			else
			{
				$_SESSION['add_purchase'] = "Purchase can't be Made!";
				header('location: ../home.php');
			}
		}
		else
		{
			$_SESSION['add_purchase'] = "Purchase can't be Made!";
			header('location: ../home.php');
		}
		
	}
	else
	{
		$_SESSION['add_purchase'] = "Purchase can't be Made!";
		header('location: ../home.php');
	}

	

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Bill</title>
        <link rel="stylesheet" type="text/css" href="../css/main.css">
        <style>
        	#ticket{
        		margin: 40px 100px;
        	}
			td,th,tr,table {
			    border-top: 1px solid black;
			    border-collapse: collapse;
			}
			table{
				width: 100%;
			}
			td.description,th.description {
			    width: 40%;
			}
			td.other,th.other{
			    width: 20%;
			}
			.centered {
			    text-align: center;
			    align-content: center;
			}
			.ticket {
			    width: 100%;
			    align-content: center;
			}
			#back{
				width: 200px;
			}
			@media print {
			    #back,
			    #back * {
			        display: none !important;
			    }
			}
        </style>
    </head>
    <body>
    	<div id="ticket" class="content_box">
        <div class="ticket">
        	<h2 class="centered">Purchase Invoice</h2>
            <p class="centered">
                <br>
<?php
	$sql11="select * from supplier";
	$result11=mysqli_query($conn,$sql11);
	if(mysqli_num_rows($result11)>0)
	{
		while ($row11 = mysqli_fetch_array($result11)) {
			if($row11['supplier_id']==$supplier_id)
			{
				echo "Bill No. : ".$bill_no."<br>".$row11['supplier_name']."<br>".$row11['supplier_address']."<br>".$row11['supplier_contact'];
			}
		}
	}
?>
            </p>
            <table class="centered">
                <thead>
                    <tr>
                        <th class="description">Product</th>
                        <th class="other">Rate</th>
                        <th class="other">Quantity</th>
                        <th class="other">Total</th>
                    </tr>
                </thead>
                <tbody>
                	<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
<?php
	$total = 0;
	for($i = 1;$i<=$_COOKIE['rowCount'];$i++)
	{
		$product_id = $_POST['product_id'.$i];
		$purchase_rate = $_POST['purchase_rate'.$i];
		$purchase_quantity = $_POST['purchase_quantity'.$i];
		$total = $total + ($purchase_rate*$purchase_quantity);
		$sql12="select * from product";
		$result12=mysqli_query($conn,$sql12);
		$product_name = "";
		if(mysqli_num_rows($result12)>0)
		{
			while ($row12 = mysqli_fetch_array($result12)) {
				if($row12['product_id']==$product_id)
				{
					$product_name = $row12['product_name'];
				}
			}
		}
?>
                    <tr>
                        <td class="description"><?php echo $product_name; ?></td>
                        <td class="quantity"><?php echo $purchase_rate; ?></td>
                        <td class="quantity"><?php echo $purchase_quantity; ?></td>
                        <td class="quantity"><?php echo $purchase_rate*$purchase_quantity; ?></td>
                    </tr>
<?php
	}
?>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="3">Sub Total</td>
						<td><?php echo $total; ?></td>
					</tr>
					<tr>
						<td colspan="3">Vat Amount</td>
						<td><?php echo $total*0.13; $total = $total + ($total*0.13); ?></td>
					</tr>
					<tr>
						<td colspan="3">Discount</td>
						<td><?php echo $discount; $total = $total - $discount; ?></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="3">Net Total</td>
						<td><?php echo $total; ?></td>
					</tr>
                </tbody>
            </table>
            <br><br><br><br><br><br>
            <p class="centered">
            	Bill by --<?php echo $_COOKIE['biller']; ?>--
            </p>
        </div>
    	</div>
        <button id="back" class="submit_btn" onclick="home()">Back</button>
        <script>
        	window.print();
    		function home()
    		{
    			window.location = "../home.php";
    		}
        </script>
    </body>
</html>