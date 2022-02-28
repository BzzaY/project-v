<?php
	session_start();
	include 'php/dbconnect.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
	<div class="container">
		<div class="navigation">
			<ul>
				<li>
					<a>
						<span class="icon"><ion-icon name="desktop"></ion-icon></span>
						<span class="title">Inventory System</span>
					</a>
				</li>
				<li id="Dashboard">
					<a onclick="dashboard()">
						<span class="icon"><ion-icon name="home"></ion-icon></span>
						<span class="title">Dashboard</span>
					</a>
				</li>
				<li id="Inventory">
					<a onclick="inventory()">
						<span class="icon"><ion-icon name="appstore"></ion-icon></span>
						<span class="title">Inventory</span>
					</a>
				</li>
				<li id="Sales">
					<a onclick="sales()">
						<span class="icon"><ion-icon name="cloud-upload"></ion-icon></span>
						<span class="title">Sales</span>
					</a>
				</li>
				<li id="Purchases">
					<a onclick="purchases()">
						<span class="icon"><ion-icon name="cloud-download"></ion-icon></span>
						<span class="title">Purchases</span>
					</a>
				</li>
				<li id="Settings">
					<a onclick="settings()">
						<span class="icon"><ion-icon name="settings"></ion-icon></span>
						<span class="title">Settings</span>
					</a>
				</li>
				<li>
					<a onclick="logout()">
						<span class="icon"><ion-icon name="log-out"></ion-icon></span>
						<span class="title">Logout</span>
					</a>
				</li>
			</ul>
			<a style="position: absolute; bottom: 0px;width: 60px;height: 80px; text-align: center;">
				<span class="icon"><ion-icon style="width: 50px;height: 50px;" name="contrast"></ion-icon></span>
				<span class="title" style="justify-content: center;"><input id="theme" type="checkbox" class="theme_check_box" name="theme" onchange="theme()"></span>
			</a>
		</div>
		<div class="main">
			<div class="topbar">
				<div class="toggle">
					<ion-icon name="menu"></ion-icon>
				</div>
			</div>
			<div id="blur"></div>
			<div id="dashboard_content" class="content">

				<div class="content_box" style="padding: 0;margin: 0;display: flex;box-shadow: none;background: transparent;">
					<div class="content_box" style="display:block;margin: 10px;width: calc(20% - 60px);text-align: center;padding: 20px;">
						<h2 style="color: #fe0000;">Total Purchases</h2>
						<br>
						<h4>
<?php
	$sql = "select * from purchases";
	$result = mysqli_query($conn,$sql);
	$total_purchase = 0.00;
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			$purchase = 0.00;
			$purchase = $row['purchase_rate'] * $row['purchase_quantity'];
			$total_purchase = $total_purchase + $purchase;
		}
	}
	$total_purchase = $total_purchase + ($total_purchase * 0.13);
	echo $total_purchase;
?>
						</h4>
					</div>
					<div class="content_box" style="display:block;margin: 10px;width: calc(20% - 60px);text-align: center;padding: 20px;">
						<h2 style="color: #fe0000;">Total Sales</h2>
						<br>
						<h4>
<?php
	$sql = "select * from sales";
	$result = mysqli_query($conn,$sql);
	$total_purchase = 0.00;
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			$purchase = 0.00;
			$purchase = $row['sale_rate'] * $row['sale_quantity'];
			$total_purchase = $total_purchase + $purchase;
		}
	}
	$total_purchase = $total_purchase + ($total_purchase * 0.13);
	echo $total_purchase;
?>
						</h4>
					</div>
					<div class="content_box" style="display:block;margin: 10px;width: calc(20% - 60px);text-align: center;padding: 20px;">
						<h2 style="color: #fe0000;">Cost of Goods Sold</h2>
						<br>
						<h4>
<?php
	$sql = "select * from sales";
	$result = mysqli_query($conn,$sql);
	$total_cost = 0;
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			$sql1 = "select * from purchases";
			$result1 = mysqli_query($conn,$sql1);
			$purchase_rate = 0.00;
			$i = 0;
			if(mysqli_num_rows($result1)>0)
			{
				while($row1 = mysqli_fetch_array($result1))
				{
					if($row['product_id']==$row1['product_id'])
					{
						$purchase_rate = $purchase_rate + $row1['purchase_rate'];
						$i = $i + 1;
					}
				}
			}
			$purchase_rate = $purchase_rate / $i;
			$cost = $purchase_rate * $row['sale_quantity'];
			$total_cost = $total_cost + $cost;
		}
	}
	echo $total_cost;
?>
						</h4>
					</div>
					<div class="content_box" style="display:block;margin: 10px;width: calc(20% - 60px);text-align: center;padding: 20px;">
						<h2 style="color: #fe0000;">Gross Profit</h2>
						<br>
						<h4>
<?php
	echo $total_purchase - $total_cost;
?>
						</h4>
					</div>
					<div class="content_box" style="display:block;margin: 10px;width: calc(20% - 60px);text-align: center;padding: 20px;">
						<h2 style="color: #fe0000;">Net Profit</h2>
						<br>
						<h4>
<?php
	$sale_discount = 0.00;
	$sql = "select * from sales_history";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			$sale_discount = $sale_discount + $row['discount'];
		}
	}
	$purchase_discount = 0.00;
	$sql = "select * from purchases_history";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			$purchase_discount = $purchase_discount + $row['discount'];
		}
	}
	echo ($total_purchase - $total_cost) + $purchase_discount - $sale_discount;
?>
						</h4>
					</div>
				</div>

			</div>
			<div id="inventory_content" class="content" style="display:none;">

				<div class="content_box" style="display: flex;box-shadow: none;padding: 2px;">
					<div class="content_box" style="width:calc(100% - 20px);display: flex;margin: 10px;padding: 0;">
						<canvas id="myChart5" width="100" height="20"></canvas>
					</div>
				</div>
				<script>
					const data = {
						labels: [
<?php
	$sql = "select * from product";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			$purchase_rate = 0.00;
			$sql1 = "select * from purchases";
			$result1 = mysqli_query($conn,$sql1);
			$i = 0;
			if(mysqli_num_rows($result1)>0)
			{
				while($row1 = mysqli_fetch_array($result1))
				{
					if($row['product_id']==$row1['product_id'])
					{
						$purchase_rate = $purchase_rate + $row1['purchase_rate'];
						$i = $i + 1;
					}
				}
			}
			$purchase_rate = $purchase_rate / $i;
			$sale_rate = 0.00;
			$sql2 = "select * from sales";
			$result2 = mysqli_query($conn,$sql2);
			$i = 0;
			if(mysqli_num_rows($result2)>0)
			{
				while($row2 = mysqli_fetch_array($result2))
				{
					if($row['product_id']==$row2['product_id'])
					{
						$sale_rate = $sale_rate + $row2['sale_rate'];
						$i = $i + 1;
					}
				}
			}
			$sale_rate = $sale_rate/$i;
			if($sale_rate>0&&$purchase_rate>0)
			{
				echo "'";
				$sql3 = "select * from brand";
				$result3 = mysqli_query($conn,$sql3);
				if(mysqli_num_rows($result3)>0)
				{
					while($row3 = mysqli_fetch_array($result3))
					{
						if($row['brand_id']==$row3['brand_id'])
						{
							echo $row3['brand_name']."-";
						}
					}
				}
				$sql4 = "select * from category";
				$result4 = mysqli_query($conn,$sql4);
				if(mysqli_num_rows($result4)>0)
				{
					while($row4 = mysqli_fetch_array($result4))
					{
						if($row['category_id']==$row4['category_id'])
						{
							echo $row4['category_name']."-";
						}
					}
				}
				echo $row['product_name']."',";
			}
		}
	}
?>
						],
						datasets: [{
							type: 'line',
							label: 'Sales',
							data: [
<?php
	$sql = "select * from product";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			$purchase_rate = 0.00;
			$sql1 = "select * from purchases";
			$result1 = mysqli_query($conn,$sql1);
			$i = 0;
			if(mysqli_num_rows($result1)>0)
			{
				while($row1 = mysqli_fetch_array($result1))
				{
					if($row['product_id']==$row1['product_id'])
					{
						$purchase_rate = $purchase_rate + $row1['purchase_rate'];
						$i = $i + 1;
					}
				}
			}
			$purchase_rate = $purchase_rate/$i;
			$sale_rate = 0.00;
			$sql2 = "select * from sales";
			$result2 = mysqli_query($conn,$sql2);
			$i = 0;
			if(mysqli_num_rows($result2)>0)
			{
				while($row2 = mysqli_fetch_array($result2))
				{
					if($row['product_id']==$row2['product_id'])
					{
						$sale_rate = $sale_rate + $row2['sale_rate'];
						$i = $i + 1;
					}
				}
			}
			$sale_rate = $sale_rate/$i;
			if($sale_rate>0&&$purchase_rate>0)
			{
				echo $sale_rate.",";
			}
		}
	}
?>
							],
							fill: true,
							borderColor: '#0000ff'
						},{
							type: 'line',
							label: 'Purchases',
							data: [
<?php
	$sql = "select * from product";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			$purchase_rate = 0.00;
			$sql1 = "select * from purchases";
			$result1 = mysqli_query($conn,$sql1);
			$i = 0;
			if(mysqli_num_rows($result1)>0)
			{
				while($row1 = mysqli_fetch_array($result1))
				{
					if($row['product_id']==$row1['product_id'])
					{
						$purchase_rate = $purchase_rate + $row1['purchase_rate'];
						$i = $i + 1;
					}
				}
			}
			$purchase_rate = $purchase_rate/$i;
			$sale_rate = 0.00;
			$sql2 = "select * from sales";
			$result2 = mysqli_query($conn,$sql2);
			$i = 0;
			if(mysqli_num_rows($result2)>0)
			{
				while($row2 = mysqli_fetch_array($result2))
				{
					if($row['product_id']==$row2['product_id'])
					{
						$sale_rate = $sale_rate + $row2['sale_rate'];
						$i = $i + 1;
					}
				}
			}
			$sale_rate = $sale_rate/$i;
			if($sale_rate>0&&$purchase_rate>0)
			{
				echo $purchase_rate.",";
			}
		}
	}
?>
							],
							borderColor: '#ff0000'
						}]
					};
				const config = {
					type: 'scatter',
					data: data,
					options: {
						scales: {
							y: {
								beginAtZero: true
							}
						}
					}
				};
				const myChart5 = new Chart(
						document.getElementById('myChart5'),
						config
					);
				</script>

				<div id="add_product" class="content_box">
					<div class="top">
						<span class="title">Add Product</span>
					</div>
<?php
	if(isset($_SESSION['add_product']))
	{
		if($_SESSION['add_product']=="Product Successfully Added!")
		{
			echo "<div class='pwarning'>".$_SESSION['add_product']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['add_product']."</div>";
		}
	}
?>
					<form class="hover_content" method="post" action="php/add_product.php">
						<input type="text" class="input_field" name="product_name" placeholder="Product Name" required>
						<select class="input_field" name="brand_id" required>
							<option value="" selected disabled hidden>--select--</option>
<?php
	$readbrand1 = "select * from brand";
	$result_readbrand1 = mysqli_query($conn,$readbrand1);
	if(mysqli_num_rows($result_readbrand1)>0)
	{
		while($row_readbrand1 = mysqli_fetch_array($result_readbrand1))
		{
			if($row_readbrand1['brand_status']=="Available")
			{
?>
							<option value="<?php echo $row_readbrand1['brand_id']; ?>"><?php echo $row_readbrand1['brand_name']; ?></option>
<?php
			}
		}
	}
?>
						</select>
						<select class="input_field" name="category_id" required>
							<option value="" selected disabled hidden>--select--</option>
<?php
	$readcategory1 = "select * from category";
	$result_readcategory1 = mysqli_query($conn,$readcategory1);
	if(mysqli_num_rows($result_readcategory1)>0)
	{
		while($row_readcategory1 = mysqli_fetch_array($result_readcategory1))
		{
			if($row_readcategory1['category_status']=="Available")
			{
?>
							<option value="<?php echo $row_readcategory1['category_id']; ?>"><?php echo $row_readcategory1['category_name']; ?></option>
<?php
			}
		}
	}
?>
						</select><br><br><br>
						<button type="button" class="submit" onclick="close_hover('add_product')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
				</div>
				<div id="add_brand" class="content_box">
					<div class="top">
						<span class="title">Add Brand</span>
					</div>
<?php
	if(isset($_SESSION['add_brand']))
	{
		if($_SESSION['add_brand']=="Brand Successfully Added!")
		{
			echo "<div class='pwarning'>".$_SESSION['add_brand']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['add_brand']."</div>";
		}
	}
?>
					<form class="hover_content" method="post" action="php/add_brand.php">
						<input type="text" class="input_field" name="brand_name" placeholder="Brand Name" required>
						<select class="input_field" name="brand_status" required>
							<option value="" selected disabled hidden>--select--</option>
							<option value="Available">Available</option>
							<option value="Unavailable">Unavailable</option>
						</select><br><br><br>
						<button type="button" class="submit" onclick="close_hover('add_brand')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
				</div>
				<div id="add_category" class="content_box">
					<div class="top">
						<span class="title">Add Category</span>
					</div>
<?php
	if(isset($_SESSION['add_category']))
	{
		if($_SESSION['add_category']=="Category Successfully Added!")
		{
			echo "<div class='pwarning'>".$_SESSION['add_category']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['add_category']."</div>";
		}
	}
?>
					<form class="hover_content" method="post" action="php/add_category.php">
						<input type="text" class="input_field" name="category_name" placeholder="Category Name" required>
						<select class="input_field" name="category_status" required>
							<option value="" selected disabled hidden>--select--</option>
							<option value="Available">Available</option>
							<option value="Unavailable">Unavailable</option>
						</select><br><br><br>
						<button type="button" class="submit" onclick="close_hover('add_category')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
				</div>

				<div id="edit_product" class="content_box">
					<div class="top">
						<span class="title">Edit Product</span>
					</div>
<?php
	$readproduct11 = "select * from product";
	$result_readproduct11 = mysqli_query($conn,$readproduct11);
	if(mysqli_num_rows($result_readproduct11)>0)
	{
		while($row_readproduct11 = mysqli_fetch_array($result_readproduct11))
		{
			if($row_readproduct11['product_id']==$_COOKIE['id'])
			{
?>
					<form class="hover_content" method="post" action="php/edit_product.php">
						<input type="text" class="input_field" name="product_id" value="<?php if(isset($_COOKIE['id'])){echo $_COOKIE['id'];} ?>" readonly>
						<input type="text" class="input_field" name="product_name" value="<?php echo $row_readproduct11['product_name']; ?>" required>
						<select class="input_field" name="brand_id" required>
							<option value="" selected disabled hidden>--select--</option>
<?php
	$readbrand11 = "select * from brand";
	$result_readbrand11 = mysqli_query($conn,$readbrand11);
	if(mysqli_num_rows($result_readbrand11)>0)
	{
		while($row_readbrand11 = mysqli_fetch_array($result_readbrand11))
		{
			if($row_readbrand11['brand_status']=="Available")
			{
?>
							<option value="<?php echo $row_readbrand11['brand_id']; ?>" <?php if($row_readproduct11['brand_id']==$row_readbrand11['brand_id']){echo "selected";}?>><?php echo $row_readbrand11['brand_name']; ?></option>
<?php
			}
		}
	}
?>
						</select>
						<select class="input_field" name="category_id" required>
							<option value="" selected disabled hidden>--select--</option>
<?php
	$readcategory11 = "select * from category";
	$result_readcategory11 = mysqli_query($conn,$readcategory11);
	if(mysqli_num_rows($result_readcategory11)>0)
	{
		while($row_readcategory11 = mysqli_fetch_array($result_readcategory11))
		{
			if($row_readcategory11['category_status']=="Available")
			{
?>
							<option value="<?php echo $row_readcategory11['category_id']; ?>" <?php if($row_readproduct11['category_id']==$row_readcategory11['category_id']){echo "selected";}?>><?php echo $row_readcategory11['category_name']; ?></option>
<?php
			}
		}
	}
?>
						</select><br><br><br>
						<button type="button" class="submit" onclick="close_hover('edit_product')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
<?php
			}
		}
	}
?>
				</div>

				<div id="edit_brand" class="content_box">
					<div class="top">
						<span class="title">Edit Brand</span>
					</div>
<?php
	$readbrand12 = "select * from brand";
	$result_readbrand12 = mysqli_query($conn,$readbrand12);
	if(mysqli_num_rows($result_readbrand12)>0)
	{
		while($row_readbrand12 = mysqli_fetch_array($result_readbrand12))
		{
			if($row_readbrand12['brand_id']==$_COOKIE['id'])
			{
?>
					<form class="hover_content" method="post" action="php/edit_brand.php">
						<input type="text" class="input_field" name="brand_id" value="<?php if(isset($_COOKIE['id'])){echo $_COOKIE['id'];} ?>" readonly>
						<input type="text" class="input_field" name="brand_name" value="<?php echo $row_readbrand12['brand_name']; ?>" required>
						<select class="input_field" name="brand_status" required>
							<option <?php if($row_readbrand12['brand_status']=="Available"){echo "selected";} ?> value="Available">Available</option>
							<option <?php if($row_readbrand12['brand_status']=="Unavailable"){echo "selected";} ?> value="Unavailable">Unavailable</option>
						</select><br><br><br>
						<button type="button" class="submit" onclick="close_hover('edit_brand')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
<?php
			}
		}
	}
?>
				</div>

				<div id="edit_category" class="content_box">
					<div class="top">
						<span class="title">Edit Category</span>
					</div>
<?php
	$readcategory12 = "select * from category";
	$result_readcategory12 = mysqli_query($conn,$readcategory12);
	if(mysqli_num_rows($result_readcategory12)>0)
	{
		while($row_readcategory12 = mysqli_fetch_array($result_readcategory12))
		{
			if($row_readcategory12['category_id']==$_COOKIE['id'])
			{
?>
					<form class="hover_content" method="post" action="php/edit_category.php">
						<input type="text" class="input_field" name="category_id" value="<?php echo $_COOKIE['id']; ?>" readonly>
						<input type="text" class="input_field" name="category_name" value="<?php echo $row_readcategory12['category_name']; ?>" required>
						<select class="input_field" name="category_status" required>
							<option <?php if($row_readcategory12['category_status']=="Available"){echo "selected";} ?> value="Available">Available</option>
							<option <?php if($row_readcategory12['category_status']=="Unavailable"){echo "selected";} ?> value="Unavailable">Unavailable</option>
						</select><br><br><br>
						<button type="button" class="submit" onclick="close_hover('edit_category')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
<?php
			}
		}
	}
?>
				</div>

				<div id="delete_product" class="content_box">
					<div class="top">
						<span class="title">Delete Product</span>
					</div>
					<form class="hover_content" method="post" action="php/delete_product.php">
<?php
	$readproduct111 = "select * from product";
	$result_readproduct111 = mysqli_query($conn,$readproduct111);
	if(mysqli_num_rows($result_readproduct111)>0)
	{
		while($row_readproduct111 = mysqli_fetch_array($result_readproduct111))
		{
			if($row_readproduct111['product_id']==$_COOKIE['id'])
			{
?>
						<input type="text" class="input_field" name="product_id" value="<?php if(isset($_COOKIE['id'])){echo $_COOKIE['id'];} ?>" readonly>
						<input type="text" class="input_field" name="product_name" value="<?php echo $row_readproduct111['product_name']; ?>" readonly>
<?php
	$readbrand111 = "select * from brand";
	$result_readbrand111 = mysqli_query($conn,$readbrand111);
	if(mysqli_num_rows($result_readbrand111)>0)
	{
		while($row_readbrand111 = mysqli_fetch_array($result_readbrand111))
		{
			if($row_readproduct111['brand_id']==$row_readbrand111['brand_id'])
			{
?>
						<input type="text" class="input_field" name="brand_name" value="<?php echo $row_readbrand111['brand_name'];?>" readonly>
<?php
			}
		}
	}
	$readcategory111 = "select * from category";
	$result_readcategory111 = mysqli_query($conn,$readcategory111);
	if(mysqli_num_rows($result_readcategory111)>0)
	{
		while($row_readcategory111 = mysqli_fetch_array($result_readcategory111))
		{
			if($row_readproduct111['category_id']==$row_readcategory111['category_id'])
			{
?>
						<input type="text" class="input_field" name="category_name" value="<?php echo $row_readcategory111['category_name'];?>" readonly>
<?php
			}
		}
	}
?>
						</select><br><br><br>
<?php
			}
		}
	}
?>
						<button type="button" class="submit" onclick="close_hover('delete_product')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
				</div>

				<div id="delete_brand" class="content_box">
					<div class="top">
						<span class="title">Delete Brand</span>
					</div>
					<form class="hover_content" method="post" action="php/delete_brand.php">
<?php
	$readbrand123 = "select * from brand";
	$result_readbrand123 = mysqli_query($conn,$readbrand123);
	if(mysqli_num_rows($result_readbrand123)>0)
	{
		while($row_readbrand123 = mysqli_fetch_array($result_readbrand123))
		{
			if($row_readbrand123['brand_id']==$_COOKIE['id'])
			{
?>
						<input type="text" class="input_field" name="brand_id" value="<?php if(isset($_COOKIE['id'])){echo $_COOKIE['id'];} ?>" readonly>
						<input type="text" class="input_field" name="brand_name" value="<?php echo $row_readbrand123['brand_name']; ?>" readonly>
						<input type="text" class="input_field" name="brand_status" value="<?php echo $row_readbrand123['brand_status']; ?>" readonly>
						<br><br><br>
<?php
			}
		}
	}
?>
						<button type="button" class="submit" onclick="close_hover('delete_brand')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
				</div>

				<div id="delete_category" class="content_box">
					<div class="top">
						<span class="title">Delete Category</span>
					</div>
					<form class="hover_content" method="post" action="php/delete_category.php">
<?php
	$readcategory123 = "select * from category";
	$result_readcategory123 = mysqli_query($conn,$readcategory123);
	if(mysqli_num_rows($result_readcategory123)>0)
	{
		while($row_readcategory123 = mysqli_fetch_array($result_readcategory123))
		{
			if($row_readcategory123['category_id']==$_COOKIE['id'])
			{
?>
						<input type="text" class="input_field" name="category_id" value="<?php echo $_COOKIE['id']; ?>" readonly>
						<input type="text" class="input_field" name="category_name" value="<?php echo $row_readcategory123['category_name']; ?>" readonly>
						<input type="text" class="input_field" name="category_status" value="<?php echo $row_readcategory123['category_status']; ?>" readonly>
						<br><br><br>
<?php
			}
		}
	}
?>
						<button type="button" class="submit" onclick="close_hover('delete_category')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
				</div>

				<div id="product" class="content_box display">
					<div class="top">
						<span class="title">Product</span>
					</div>
					<div class="add_btn" onclick="open_hover('add_product')">Add Product</div>
<?php
	if(isset($_SESSION['edit_product']))
	{
		if($_SESSION['edit_product']=="Product Successfully Edited!")
		{
			echo "<div class='pwarning'>".$_SESSION['edit_product']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['edit_product']."</div>";
		}
	}
?>
<?php
	if(isset($_SESSION['delete_product']))
	{
		if($_SESSION['delete_product']=="Product Successfully Deleted!")
		{
			echo "<div class='pwarning'>".$_SESSION['delete_product']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['delete_product']."</div>";
		}
	}
?>
<?php
	$readproduct = "select * from product";
	$result_readproduct = mysqli_query($conn,$readproduct);
	if(mysqli_num_rows($result_readproduct)>0)
	{
?>
					<table>
						<thead>
							<tr>
								<td>Name</td>
								<td>Brand</td>
								<td>Category</td>
								<td>Status</td>
								<td>Quantity</td>
								<td></td>
							</tr>
						</thead>
						<tbody>
<?php
		while ($row_readproduct=mysqli_fetch_array($result_readproduct))
		{
?>
							<tr>
								<td><?php echo $row_readproduct['product_name']; ?></td>
								<td>
<?php
	$readbrand2 = "select * from brand";
	$result_readbrand2 = mysqli_query($conn,$readbrand2);
	if(mysqli_num_rows($result_readbrand2)>0)
	{
		while($row_readbrand2 = mysqli_fetch_array($result_readbrand2))
		{
			if($row_readproduct['brand_id']==$row_readbrand2['brand_id'])
			{
				echo $row_readbrand2['brand_name'];
			}
		}
	}
?>
								 </td>
								<td>
<?php
	$readcategory2 = "select * from category";
	$result_readcategory2 = mysqli_query($conn,$readcategory2);
	if(mysqli_num_rows($result_readcategory2)>0)
	{
		while($row_readcategory2 = mysqli_fetch_array($result_readcategory2))
		{
			if($row_readproduct['category_id']==$row_readcategory2['category_id'])
			{
				echo $row_readcategory2['category_name'];
			}
		}
	}
?>
								</td>
								<td><span class="status <?php if($row_readproduct['product_quantity']>0){echo "Available";}else{echo "Unavailable";} ?>"><?php if($row_readproduct['product_quantity']>0){echo "Available";}else{echo "Unavailable";} ?></span></td>
								<td><?php echo $row_readproduct['product_quantity']; ?></td>
								<td>
									<ion-icon name="funnel" onclick="dropdown('<?php echo $row_readproduct['product_id']; ?>')">
										
									</ion-icon>
									<div id="<?php echo $row_readproduct['product_id']; ?>" class="dropdown">
										<a onclick="edit_hover('inventory()','product','<?php echo $row_readproduct['product_id']; ?>')">Edit</a>
										<a onclick="delete_hover('inventory()','product','<?php echo $row_readproduct['product_id']; ?>')">Delete</a>
									</div>
								</td>
							</tr>
<?php
		}
?>
						</tbody>
					</table>
<?php
	}
?>
				</div>
				<div id="brand_category">
					<div id="brand" class="content_box display">
						<div class="top">
							<span class="title">Brand</span>
						</div>
						<div class="add_btn" onclick="open_hover('add_brand')">Add Brand</div>
<?php
	if(isset($_SESSION['edit_brand']))
	{
		if($_SESSION['edit_brand']=="Brand Successfully Edited!")
		{
			echo "<div class='pwarning'>".$_SESSION['edit_brand']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['edit_brand']."</div>";
		}
	}
?>
<?php
	if(isset($_SESSION['delete_brand']))
	{
		if($_SESSION['delete_brand']=="Brand Successfully Deleted!")
		{
			echo "<div class='pwarning'>".$_SESSION['delete_brand']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['delete_brand']."</div>";
		}
	}
?>
<?php
	$readbrand = "select * from brand";
	$result_readbrand = mysqli_query($conn,$readbrand);
	if(mysqli_num_rows($result_readbrand)>0)
	{
?>
						<table>
							<thead>
								<tr>
									<td>Brand</td>
									<td>Status</td>
									<td></td>
								</tr>
							</thead>
							<tbody>
<?php
		while ($row_readbrand=mysqli_fetch_array($result_readbrand))
		{
?>
								<tr>
									<td><?php echo $row_readbrand['brand_name']; ?></td>
									<td><span class="status <?php echo $row_readbrand['brand_status']; ?>"><?php echo $row_readbrand['brand_status']; ?></span></td>
									<td>
										<ion-icon name="funnel" onclick="dropdown('<?php echo $row_readbrand['brand_name'].$row_readbrand['brand_id']; ?>')">
											
										</ion-icon>
										<div id="<?php echo $row_readbrand['brand_name'].$row_readbrand['brand_id']; ?>" class="dropdown">
											<a onclick="edit_hover('inventory()','brand','<?php echo $row_readbrand['brand_id']; ?>')">Edit</a>
											<a onclick="delete_hover('inventory()','brand','<?php echo $row_readbrand['brand_id']; ?>')">Delete</a>
										</div>
									</td>
								</tr>
<?php
		}
?>
							</tbody>
						</table>
<?php
	}
?>
					</div>
					<div id="category" class="content_box display">
						<div class="top">
							<span class="title">Category</span>
						</div>
						<div class="add_btn" onclick="open_hover('add_category')">Add Category</div>
<?php
	if(isset($_SESSION['edit_category']))
	{
		if($_SESSION['edit_category']=="Category Successfully Edited!")
		{
			echo "<div class='pwarning'>".$_SESSION['edit_category']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['edit_category']."</div>";
		}
	}
?>
<?php
	if(isset($_SESSION['delete_category']))
	{
		if($_SESSION['delete_category']=="Category Successfully Deleted!")
		{
			echo "<div class='pwarning'>".$_SESSION['delete_category']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['delete_category']."</div>";
		}
	}
?>
<?php
	$readcategory = "select * from category";
	$result_readcategory = mysqli_query($conn,$readcategory);
	if(mysqli_num_rows($result_readcategory)>0)
	{
?>
						<table>
							<thead>
								<tr>
									<td>Category</td>
									<td>Status</td>
									<td></td>
								</tr>
							</thead>
							<tbody>
<?php
		while ($row_readcategory=mysqli_fetch_array($result_readcategory))
		{
?>
								<tr>
									<td><?php echo $row_readcategory['category_name']; ?></td>
									<td><span class="status <?php echo $row_readcategory['category_status']; ?>"><?php echo $row_readcategory['category_status']; ?></span></td>
									<td>
										<ion-icon name="funnel" onclick="dropdown('<?php echo $row_readcategory['category_name'].$row_readcategory['category_id']; ?>')">
											
										</ion-icon>
										<div id="<?php echo $row_readcategory['category_name'].$row_readcategory['category_id']; ?>" class="dropdown">
											<a onclick="edit_hover('inventory()','category','<?php echo $row_readcategory['category_id']; ?>')">Edit</a>
											<a onclick="delete_hover('inventory()','category','<?php echo $row_readcategory['category_id']; ?>')">Delete</a>
										</div>
									</td>
								</tr>
<?php
		}
?>
							</tbody>
						</table>
<?php
	}
?>
					</div>
				</div>
			</div>
			<div id="sales_content" class="content" style="display:none;">

				<div class="content_box" style="display: flex;box-shadow: none;padding: 2px;">
					<div class="content_box" style="width:calc(25% - 20px);display: flex;margin: 10px;padding: 0;">
						<canvas id="myChart" width="100"></canvas>
					</div>
					<div class="content_box" style="width:calc(75% - 20px);display: flex;margin: 10px;padding: 0;">
						<canvas id="myChart1" width="100" height="35"></canvas>
					</div>
				</div>
				<script>
				const ctx = document.getElementById('myChart').getContext('2d');
				const ctx1 = document.getElementById('myChart1').getContext('2d');
				const myChart = new Chart(ctx, {
				    type: 'doughnut',
				    data: {
				        labels: ['Credit', 'Cash Received', 'Bank Received'],
				        datasets: [{
				            label: 'Sales Report',
				            data: [
<?php
	$total_sale = 0.00;
	$total_cash_paid = 0.00;
	$total_bank_paid = 0.00;
	$total_due = 0.00;
	$sql = "select * from sales_history";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			$sale = 0.00;
			$cash_paid = 0.00;
			$bank_paid = 0.00;
			$sql1 = "select * from sales";
			$result1 = mysqli_query($conn,$sql1);
			if(mysqli_num_rows($result1)>0)
			{
				while($row1 = mysqli_fetch_array($result1))
				{
					if($row['bill_no']==$row1['bill_no'])
					{
						$sale = $sale + ($row1['sale_rate'] * $row1['sale_quantity']);
					}
				}
			}
			$sale = $sale + ($sale * 0.13);
			$sale = $sale - $row['discount'];
			$sql2 = "select * from sales_payment";
			$result2 = mysqli_query($conn,$sql2);
			if(mysqli_num_rows($result2)>0)
			{
				while($row2 = mysqli_fetch_array($result2))
				{
					if($row['bill_no']==$row2['bill_no'])
					{
						$cash_paid = $row2['cash'];
						$bank_paid = $row2['bank'];
					}
				}
			}
			$total_sale = $total_sale + $sale;
			$total_cash_paid = $total_cash_paid + $cash_paid;
			$total_bank_paid = $total_bank_paid + $bank_paid;
		}
	}
	$total_due = $total_sale - ($total_cash_paid + $total_bank_paid);
	echo $total_due.",".$total_cash_paid.",".$total_bank_paid;
?>
				            ],
				            backgroundColor: [
				                '#ff0000',
				                '#aa0000',
				                '#770000'
				            ],
				            borderColor: [
				                '#ffffff'
				            ],
				            borderWidth: 1
				        }]
				    }
				});
				const myChart1 = new Chart(ctx1, {
					type: 'line',
					data: {
						labels: [
<?php
	$sql = "select * from sales_history";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			echo "'Bill No.: ".$row['bill_no']."',";
		}
	}
?>
						],
						datasets: [{
							label: 'Sales Report',
							data: [
<?php
	$sql = "select * from sales_history limit 10";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			$sale = 0.00;
			$sql1 = "select * from sales";
			$result1 = mysqli_query($conn,$sql1);
			if(mysqli_num_rows($result1)>0)
			{
				while($row1 = mysqli_fetch_array($result1))
				{
					if($row['bill_no']==$row1['bill_no'])
					{
						$sale = $sale + ($row1['sale_rate'] * $row1['sale_quantity']);
					}
				}
			}
			$sale = $sale + ($sale * 0.13);
			$sale = $sale - $row['discount'];
			echo $sale.",";
		}
	}
?>
							],
							fill:true,
							borderColor: '#ff0000',
							tension: 0.1
						}]
					}
				});
				</script>

				<div id="add_vendor" class="content_box">
					<div class="top">
						<span class="title">New Vendor</span>
					</div>
<?php
	if(isset($_SESSION['add_vendor']))
	{
		if($_SESSION['add_vendor']=="Vendor Successfully Added!")
		{
			echo "<div class='pwarning'>".$_SESSION['add_vendor']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['add_vendor']."</div>";
		}
	}
?>
					<form class="hover_content" method="post" action="php/add_vendor.php">
						<input type="text" class="input_field" name="vendor_name" placeholder="Vendor Name" required>
						<input type="text" class="input_field" name="vendor_address" placeholder="Vendor Address" required>
						<input type="number" class="input_field" name="vendor_contact" placeholder="0000000000" required>
						<br><br><br>
						<button type="button" class="submit" onclick="close_hover('add_vendor')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
				</div>

				<div id="edit_vendor" class="content_box">
					<div class="top">
						<span class="title">Edit Vendor</span>
					</div>
<?php
	$readvendor12 = "select * from vendor";
	$result_readvendor12 = mysqli_query($conn,$readvendor12);
	if(mysqli_num_rows($result_readvendor12)>0)
	{
		while($row_readvendor12 = mysqli_fetch_array($result_readvendor12))
		{
			if($row_readvendor12['vendor_id']==$_COOKIE['id'])
			{
?>
					<form class="hover_content" method="post" action="php/edit_vendor.php">
						<input type="text" class="input_field" name="vendor_id" value="<?php if(isset($_COOKIE['id'])){echo $_COOKIE['id'];} ?>" readonly>
						<input type="text" class="input_field" name="vendor_name" value="<?php echo $row_readvendor12['vendor_name']; ?>" required>
						<input type="text" class="input_field" name="vendor_address" value="<?php echo $row_readvendor12['vendor_address']; ?>" required>
						<input type="number" class="input_field" name="vendor_contact" value="<?php echo $row_readvendor12['vendor_contact']; ?>" required><br><br><br>
						<button type="button" class="submit" onclick="close_hover('edit_vendor')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
<?php
			}
		}
	}
?>
				</div>

				<div id="view_vendor" class="content_box">
					<div class="top">
						<span class="title">View Vendor</span>
					</div>
					<form class="hover_content">
<?php
	$readvendor123 = "select * from vendor";
	$result_readvendor123 = mysqli_query($conn,$readvendor123);
	if(mysqli_num_rows($result_readvendor123)>0)
	{
		while($row_readvendor123 = mysqli_fetch_array($result_readvendor123))
		{
			if($row_readvendor123['vendor_id']==$_COOKIE['id'])
			{
?>
						<input type="text" class="input_field" name="vendor_id" value="<?php if(isset($_COOKIE['id'])){echo $_COOKIE['id'];} ?>" readonly>
						<input type="text" class="input_field" name="vendor_name" value="<?php echo $row_readvendor123['vendor_name']; ?>" readonly>
						<input type="text" class="input_field" name="vendor_address" value="<?php echo $row_readvendor123['vendor_address']; ?>" readonly>
						<input type="number" class="input_field" name="vendor_contact" value="<?php echo $row_readvendor123['vendor_contact']; ?>" readonly><br><br><br>
<?php
			}
		}
	}
?>
						<button type="button" class="submit" onclick="close_hover('view_vendor')">Close</button>
					</form>
				</div>

				<div id="delete_vendor" class="content_box">
					<div class="top">
						<span class="title">Delete Vendor</span>
					</div>
					<form class="hover_content" method="post" action="php/delete_vendor.php">
<?php
	$readvendor123 = "select * from vendor";
	$result_readvendor123 = mysqli_query($conn,$readvendor123);
	if(mysqli_num_rows($result_readvendor123)>0)
	{
		while($row_readvendor123 = mysqli_fetch_array($result_readvendor123))
		{
			if($row_readvendor123['vendor_id']==$_COOKIE['id'])
			{
?>
						<input type="text" class="input_field" name="vendor_id" value="<?php if(isset($_COOKIE['id'])){echo $_COOKIE['id'];} ?>" readonly>
						<input type="text" class="input_field" name="vendor_name" value="<?php echo $row_readvendor123['vendor_name']; ?>" readonly>
						<input type="text" class="input_field" name="vendor_address" value="<?php echo $row_readvendor123['vendor_address']; ?>" readonly>
						<input type="number" class="input_field" name="vendor_contact" value="<?php echo $row_readvendor123['vendor_contact']; ?>" readonly><br><br><br>
<?php
			}
		}
	}
?>
						<button type="button" class="submit" onclick="close_hover('delete_vendor')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
				</div>



				<div id="add_sale" class="content_box">
					<div class="top">
						<span class="title">New Sale</span>
					</div>
<?php
	if(isset($_SESSION['add_sale']))
	{
		if($_SESSION['add_sale']=="Sale Successfully Made!")
		{
			echo "<div class='pwarning'>".$_SESSION['add_sale']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['add_sale']."</div>";
		}
	}
?>
					<form class="hover_content" method="post" action="php/add_sale.php">
						<select style="width: 250px;text-align: center;" class="input_field" name="vendor_id" required>
							<option value="" selected disabled hidden>--select--</option>
<?php
	$readvendor1 = "select * from vendor";
	$result_readvendor1 = mysqli_query($conn,$readvendor1);
	if(mysqli_num_rows($result_readvendor1)>0)
	{
		while($row_readvendor1 = mysqli_fetch_array($result_readvendor1))
		{
?>
							<option value="<?php echo $row_readvendor1['vendor_id']; ?>"><?php echo $row_readvendor1['vendor_name']; ?></option>
<?php
		}
	}
?>
						</select>
						<TABLE id="dataTable1" class="dataTable" width="350px" border="0">  
					        <TH>
					        	<td>Product</td>
					        	<td>Rate</td>
					        	<td>Quantity</td>
					        	<td>Total</td>
					        	<td></td>
					        </TH>
					        <TR>  
					        	<TD>1</TD> 
				                <TD>
				        <select class="input_field" name="product_id1" required>
							<option value="" selected disabled hidden>--select--</option>
<?php
	$brand_name = "";
	$category_name = "";
	$readproduct1111 = "select * from product";
	$result_readproduct1111 = mysqli_query($conn,$readproduct1111);
	if(mysqli_num_rows($result_readproduct1111)>0)
	{
		while($row_readproduct1111 = mysqli_fetch_array($result_readproduct1111))
		{
			$readbrand1111 = "select * from brand";
			$result_readbrand1111 = mysqli_query($conn,$readbrand1111);
			if(mysqli_num_rows($result_readbrand1111)>0)
			{
				while($row_readbrand1111 = mysqli_fetch_array($result_readbrand1111))
				{
					if($row_readproduct1111['brand_id']==$row_readbrand1111['brand_id'])
					{
						$brand_name = $row_readbrand1111['brand_name'];
					}
				}
			}
			$readcategory1111 = "select * from category";
			$result_readcategory1111 = mysqli_query($conn,$readcategory1111);
			if(mysqli_num_rows($result_readcategory1111)>0)
			{
				while($row_readcategory1111 = mysqli_fetch_array($result_readcategory1111))
				{
					if($row_readproduct1111['category_id']==$row_readcategory1111['category_id'])
					{
						$category_name = $row_readcategory1111['category_name'];
					}
				}
			}
?>
							<option value="<?php echo $row_readproduct1111['product_id']; ?>"><?php echo $row_readproduct1111['product_name']."-".$brand_name."-".$category_name; ?></option>
<?php
		}
	}
?>
						</select> 
				                </TD>  
				                <TD><input class="input_field" type="number" name="sale_rate1" value="1.00" onchange="calculate_total1()" min="1" oninput="validity.valid||(value='');" required></TD>
				                <TD><input class="input_field" type="number" name="sale_quantity1" value="1" onchange="calculate_total1()" min="1" oninput="validity.valid||(value='');" required></TD>
				                <td><input class="input_field" type="number" name="total1" readonly></td>
				                <td></td>
							</TR>
				        </TABLE>
				        <input class="input_field" type="text" value="Sub Total" style="width: 250px;text-align: center;border: none;" readonly>
				        <input class="input_field" type="number" id="sub_total1" name="sub_total" value="0.00" style="width: 250px;text-align: center;" readonly>
				        <input class="input_field" type="text" value="Vat" style="width: 250px;text-align: center;border: none;" readonly>
				        <input class="input_field" type="number" id="vat1" name="vat" placeholder="13%" value="0.13" style="width: 250px;text-align: center;" readonly>
				        <input class="input_field" type="text" value="Discount" style="width: 250px;text-align: center;border: none;" readonly>
				        <input class="input_field" type="number" id="discount1" name="discount" value="0.00" min="0" oninput="validity.valid||(value='');" style="width: 250px;text-align: center;" onchange="calculate_total1()" required>
				        <input class="input_field" type="text" value="Net Total" style="width: 250px;text-align: center;border: none;" readonly>
				        <input class="input_field" type="number" id="net_total1" name="net_total" value="0.00" style="width: 250px;text-align: center;" readonly>

						<br><br><br>
						<button type="button" class="submit" onclick="addRow1('dataTable1')">Add Row</button>
						<button type="button" class="submit" onclick="close_hover('add_sale')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
				</div>

				<div id="view_saledetail" class="content_box">
					<div class="top">
						<span class="title">Bill Detail</span>
					</div>
					<form class="hover_content">
<?php
	$total = 0;
	$product_name = "";
	$readsales_history1 = "select * from sales_history";
	$result_readsales_history1 = mysqli_query($conn,$readsales_history1);
	if(mysqli_num_rows($result_readsales_history1)>0)
	{
		while($row_readsales_history1 = mysqli_fetch_array($result_readsales_history1))
		{
			if($row_readsales_history1['bill_no']==$_COOKIE['id'])
			{
?>
						<input type="text" value="Bill No.:<?php echo $row_readsales_history1['bill_no']; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>			
<?php
				$vendor_name = "";
				$readvendor1234 = "select * from vendor";
				$result_readvendor1234 = mysqli_query($conn,$readvendor1234);
				if(mysqli_num_rows($result_readvendor1234)>0)
				{
					while($row_readvendor1234=mysqli_fetch_array($result_readvendor1234))
					{
						if($row_readvendor1234['vendor_id']==$row_readsales_history1['vendor_id'])
						{
							$vendor_name = $row_readvendor1234['vendor_name'];
						}
					}
				}
?>
						<input type="text" value="<?php echo $vendor_name; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
					    <TABLE class="dataTable" width="350px" border="0">  
					        <TH>
					        	<td>Product</td>
					        	<td>Rate</td>
					        	<td>Quantity</td>
					        	<td>Total</td>
					        </TH>
<?php
				$readsales1 = "select * from sales";
				$result_readsales1 = mysqli_query($conn,$readsales1);
				if(mysqli_num_rows($result_readsales1)>0)
				{
					while($row_readsales1 = mysqli_fetch_array($result_readsales1))
					{
						if($row_readsales1['bill_no']==$row_readsales_history1['bill_no'])
						{
?>
<?php
							$readproduct111111 = "select * from product";
							$result_readproduct111111 = mysqli_query($conn,$readproduct111111);
							if(mysqli_num_rows($result_readproduct111111)>0)
							{
								while($row_readproduct111111=mysqli_fetch_array($result_readproduct111111))
								{
									if($row_readproduct111111['product_id']==$row_readsales1['product_id'])
									{
										$product_name = $row_readproduct111111['product_name'];
										$readbrand111111 = "select * from brand";
										$result_readbrand111111 = mysqli_query($conn,$readbrand111111);
										if(mysqli_num_rows($result_readbrand111111)>0)
										{
											while($row_readbrand111111=mysqli_fetch_array($result_readbrand111111))
											{
												if($row_readproduct111111['brand_id']==$row_readbrand111111['brand_id'])
												{
													$product_name = $product_name."-".$row_readbrand111111['brand_name'];
												}
											}
										}
										$readcategory111111 = "select * from category";
										$result_readcategory111111 = mysqli_query($conn,$readcategory111111);
										if(mysqli_num_rows($result_readcategory111111)>0)
										{
											while($row_readcategory111111=mysqli_fetch_array($result_readcategory111111))
											{
												if($row_readproduct111111['category_id']==$row_readcategory111111['category_id'])
												{
													$product_name = $product_name."-".$row_readcategory111111['category_name'];
												}
											}
										}
									}
								}
							}
?>
					        <TR>  
					        	<TD style="width: 10px;"></TD> 
				                <TD><input type="text" value="<?php echo $product_name; ?>" class="input_field" style="text-align: center;" readonly></TD>  
				                <TD><input type="text" value="<?php echo $row_readsales1['sale_rate']; ?>" class="input_field" style="text-align: center;" readonly></td>
				                <td><input type="text" value="<?php echo $row_readsales1['sale_quantity']; ?>" class="input_field" style="text-align: center;" readonly></td>
				                <td><input type="text" value="<?php echo $row_readsales1['sale_rate']*$row_readsales1['sale_quantity']; ?>" class="input_field" style="text-align: center;" readonly></td>
							</TR>
<?php
							$total = $total + ($row_readsales1['sale_rate']*$row_readsales1['sale_quantity']);
						}
					}
				}
?>
				        </TABLE>
				        <input type="text" value="Sub Total" class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
				        <input type="text" value="<?php echo $total; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
				        <input type="text" value="Vat Amount" class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
				        <input type="text" value="<?php echo $total*0.13;$total = $total + $total*0.13; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
				        <input type="text" value="Discount Amount" class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
				        <input type="text" value="<?php echo $row_readsales_history1['discount'];$total = $total - $row_readsales_history1['discount']; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
				        <input type="text" value="Net Total" class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
				        <input type="text" value="<?php echo $total; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
<?php
			}
		}
	}
?>
						<br><br><br>
						<button type="button" class="submit" onclick="close_hover('view_saledetail')">Close</button>
					</form>
				</div>

				<div id="payment_sale" class="content_box">
					<div class="top">
						<span class="title">Bill Receipt</span>
					</div>
					<form class="hover_content" method="post" action="php/sale_payment.php">
<?php
	$readsales_history2 = "select * from sales_history";
	$result_readsales_history2 = mysqli_query($conn,$readsales_history2);
	if(mysqli_num_rows($result_readsales_history2)>0)
	{
		while($row_readsales_history2 = mysqli_fetch_array($result_readsales_history2))
		{
			if($row_readsales_history2['bill_no']==$_COOKIE['id'])
			{
?>
						<input type="text" class="input_field" value="Bill No." style="width: 250px;text-align: center;border:none;" readonly>
						<input type="text" class="input_field" name="bill_no" style="width: 250px;text-align: center;" value="<?php echo $row_readsales_history2['bill_no']; ?>" readonly>
						<input type="text" class="input_field" value="Vendor" style="width: 250px;text-align: center;border:none;" readonly>
<?php
				$vendor_name = "";
				$readvendor12345 = "select * from vendor";
				$result_readvendor12345 = mysqli_query($conn,$readvendor12345);
				if(mysqli_num_rows($result_readvendor12345)>0)
				{
					while ($row_readvendor12345 = mysqli_fetch_array($result_readvendor12345))
					{
						if($row_readsales_history2['vendor_id']==$row_readvendor12345['vendor_id'])
						{
							$vendor_name = $row_readvendor12345['vendor_name'];
						}
					}
				}
				$total = 0.00;
				$readsales11 = "select * from sales";
				$result_readsales11 = mysqli_query($conn,$readsales11);
				if(mysqli_num_rows($result_readsales11)>0)
				{
					while ($row_readsales11 = mysqli_fetch_array($result_readsales11))
					{
						if($row_readsales_history2['bill_no']==$row_readsales11['bill_no'])
						{
							$total = $total + ($row_readsales11['sale_rate']*$row_readsales11['sale_quantity']);
						}
					}
				}
				$total = $total + ($total * 0.13);
				$total = $total - $row_readsales_history2['discount'];
?>
<?php
				$due = 0.00;
				$read_salespayment = "select * from sales_payment";
				$result_readsalespayment = mysqli_query($conn,$read_salespayment);
				if(mysqli_num_rows($result_readsalespayment)>0)
				{
					while($row_readsalespayment = mysqli_fetch_array($result_readsalespayment))
					{
						if($row_readsales_history2['bill_no']==$row_readsalespayment['bill_no'])
						{
							$due = $total - ($row_readsalespayment['cash']+$row_readsalespayment['bank']);
						}
					}
				}
?>
						<input type="text" class="input_field" value="<?php echo $vendor_name; ?>" style="width: 250px;text-align: center;" readonly>
						<input type="text" class="input_field" name="vendor_id" style="width: 250px;text-align: center;display: none;" value="<?php echo $row_readsales_history2['vendor_id']; ?>" readonly>
						<input type="text" class="input_field" name="total" style="width: 250px;text-align: center;display: none;" value="<?php echo $total; ?>" readonly>
						<input type="text" class="input_field" value="Due Amount" style="width: 250px;text-align: center;border:none;" readonly>
						<input type="text" class="input_field" name="due" style="width: 250px;text-align: center;" value="<?php echo $due; ?>" readonly>
						<input type="text" class="input_field" value="Payment" style="width: 250px;text-align: center;border:none;" readonly>
						<select class="input_field" name="payment" style="width:250px;text-align: center;" required>
							<option value="" selected disabled hidden>--select--</option>
							<option value="cash">Cash</option>
							<option value="bank">Bank</option>
						</select>
						<input type="text" class="input_field" value="Amount" style="width: 250px;text-align: center;border:none;" readonly>
						<input type="number" class="input_field" name="amount" style="width: 250px;text-align: center;" placeholder="0.00" min="0" oninput="validity.valid||(value='');" required>
						<br><br><br>
						<button type="button" class="submit" onclick="close_hover('payment_sale')">Close</button>
<?php
				if($due>0)
				{
?>
						<button type="submit" class="submit">Proceed</button>
<?php
				}
			}
		}
	}
?>
					</form>
				</div>

				<div id="delete_sale" class="content_box">
					<div class="top">
						<span class="title">Delete Bill</span>
					</div>
					<form class="hover_content" method="post" action="php/delete_sale.php">
<?php
	$total = 0;
	$product_name = "";
	$readsales_history1 = "select * from sales_history";
	$result_readsales_history1 = mysqli_query($conn,$readsales_history1);
	if(mysqli_num_rows($result_readsales_history1)>0)
	{
		while($row_readsales_history1 = mysqli_fetch_array($result_readsales_history1))
		{
			if($row_readsales_history1['bill_no']==$_COOKIE['id'])
			{
?>
						<input type="text" value="Bill No. : " class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
						<input type="text" name="bill_no" value="<?php echo $row_readsales_history1['bill_no']; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>			
<?php
				$vendor_name = "";
				$readvendor1234 = "select * from vendor";
				$result_readvendor1234 = mysqli_query($conn,$readvendor1234);
				if(mysqli_num_rows($result_readvendor1234)>0)
				{
					while($row_readvendor1234=mysqli_fetch_array($result_readvendor1234))
					{
						if($row_readvendor1234['vendor_id']==$row_readsales_history1['vendor_id'])
						{
							$vendor_name = $row_readvendor1234['vendor_name'];
						}
					}
				}
?>
						<input type="text" value="Company Name : " class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
						<input type="text" name="vendor_id" value="<?php echo $row_readsales_history1['vendor_id']; ?>" class="input_field" style="width: 250px;text-align: center;display: none;" readonly>
						<input type="text" value="<?php echo $vendor_name; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
					    <TABLE class="dataTable" width="350px" border="0">  
					        <TH>
					        	<td>Product</td>
					        	<td>Rate</td>
					        	<td>Quantity</td>
					        	<td>Total</td>
					        </TH>
<?php
				$readsales1 = "select * from sales";
				$result_readsales1 = mysqli_query($conn,$readsales1);
				if(mysqli_num_rows($result_readsales1)>0)
				{
					while($row_readsales1 = mysqli_fetch_array($result_readsales1))
					{
						if($row_readsales1['bill_no']==$row_readsales_history1['bill_no'])
						{
?>
<?php
							$readproduct11111 = "select * from product";
							$result_readproduct11111 = mysqli_query($conn,$readproduct11111);
							if(mysqli_num_rows($result_readproduct11111)>0)
							{
								while($row_readproduct11111=mysqli_fetch_array($result_readproduct11111))
								{
									if($row_readproduct11111['product_id']==$row_readsales1['product_id'])
									{
										$product_name = $row_readproduct11111['product_name'];
										$readbrand11111 = "select * from brand";
										$result_readbrand11111 = mysqli_query($conn,$readbrand11111);
										if(mysqli_num_rows($result_readbrand11111)>0)
										{
											while($row_readbrand11111=mysqli_fetch_array($result_readbrand11111))
											{
												if($row_readproduct11111['brand_id']==$row_readbrand11111['brand_id'])
												{
													$product_name = $product_name."-".$row_readbrand11111['brand_name'];
												}
											}
										}
										$readcategory11111 = "select * from category";
										$result_readcategory11111 = mysqli_query($conn,$readcategory11111);
										if(mysqli_num_rows($result_readcategory11111)>0)
										{
											while($row_readcategory11111=mysqli_fetch_array($result_readcategory11111))
											{
												if($row_readproduct11111['category_id']==$row_readcategory11111['category_id'])
												{
													$product_name = $product_name."-".$row_readcategory11111['category_name'];
												}
											}
										}
									}
								}
							}
?>
					        <TR>  
					        	<TD style="width: 10px;"></TD> 
				                <TD><input type="text" value="<?php echo $product_name; ?>" class="input_field" style="text-align: center;" readonly></TD>  
				                <TD><input type="text" value="<?php echo $row_readsales1['sale_rate']; ?>" class="input_field" style="text-align: center;" readonly></td>
				                <td><input type="text" value="<?php echo $row_readsales1['sale_quantity']; ?>" class="input_field" style="text-align: center;" readonly></td>
				                <td><input type="text" value="<?php echo $row_readsales1['sale_rate']*$row_readsales1['sale_quantity']; ?>" class="input_field" style="text-align: center;" readonly></td>
							</TR>
<?php
							$total = $total + ($row_readsales1['sale_rate']*$row_readsales1['sale_quantity']);
						}
					}
				}
?>
				        </TABLE>
				        <input type="text" value="Sub Total" class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
				        <input type="text" value="<?php echo $total; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
				        <input type="text" value="Vat Amount" class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
				        <input type="text" value="<?php echo $total*0.13;$total = $total + $total*0.13; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
				        <input type="text" value="Discount Amount" class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
				        <input type="text" value="<?php echo $row_readsales_history1['discount'];$total = $total - $row_readsales_history1['discount']; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
				        <input type="text" value="Net Total" class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
				        <input type="text" value="<?php echo $total; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
<?php
			}
		}
	}
?>
						<br><br><br>
						<button type="button" class="submit" onclick="close_hover('delete_sale')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
				</div>
				
				<div id="sales_history" class="content_box display">
					<div class="top">
						<span class="title">Sales History</span>
					</div>
					<div class="add_btn" onclick="open_hover('add_sale')">New Sale</div>
<?php
	if(isset($_SESSION['sales_payment']))
	{
		if($_SESSION['sales_payment']=="Payment Successfully Received!")
		{
			echo "<div class='pwarning'>".$_SESSION['sales_payment']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['sales_payment']."</div>";
		}
	}
?>
<?php
	if(isset($_SESSION['delete_sale']))
	{
		if($_SESSION['delete_sale']=="Sale Bill Successfully Deleted!")
		{
			echo "<div class='pwarning'>".$_SESSION['delete_sale']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['delete_sale']."</div>";
		}
	}
?>
<?php
	$readsales_history = "select * from sales_history";
	$result_readsales_history = mysqli_query($conn,$readsales_history);
	if(mysqli_num_rows($result_readsales_history)>0)
	{
?>
					<table>
						<thead>
							<tr>
								<td>Bill No.</td>
								<td>Vendor</td>
								<td>Total</td>
								<td>Due</td>
								<td>Status</td>
								<td></td>
							</tr>
						</thead>
						<tbody>
<?php
		while ($row_readsales_history=mysqli_fetch_array($result_readsales_history))
		{
?>
							<tr>
								<td><?php echo $row_readsales_history['bill_no']; ?></td>
								<td>
<?php
	$readvendor12345 = "select * from vendor";
	$result_readvendor12345 = mysqli_query($conn,$readvendor12345);
	if(mysqli_num_rows($result_readvendor12345)>0)
	{
		while($row_readvendor12345=mysqli_fetch_array($result_readvendor12345))
		{
			if($row_readsales_history['vendor_id']==$row_readvendor12345['vendor_id'])
			{
				echo $row_readvendor12345['vendor_name'];
			}
		}
	}
?>
								</td>
								<td>
<?php
	$readsales = "select * from sales";
	$result_readsales = mysqli_query($conn,$readsales);
	$total = 0;
	if(mysqli_num_rows($result_readsales)>0)
	{
		while($row_readsales=mysqli_fetch_array($result_readsales))
		{
			if($row_readsales_history['bill_no']==$row_readsales['bill_no'])
			{
				$total = $total + ($row_readsales['sale_rate'] * $row_readsales['sale_quantity']);
			}
		}
	}
	$total = $total + ($total * 0.13);
	$total = $total - $row_readsales_history['discount'];
	echo $total;
?>
								</td>
								<td>
<?php
	$payment = 0.00;
	$read_salespayment1 = "select * from sales_payment";
	$result_readsalespayment1 = mysqli_query($conn,$read_salespayment1);
	if(mysqli_num_rows($result_readsalespayment1)>0)
	{
		while($row_readsalespayment1 = mysqli_fetch_array($result_readsalespayment1))
		{
			if($row_readsales_history['bill_no']==$row_readsalespayment1['bill_no'])
			{
				$payment = $row_readsalespayment1['cash'] + $row_readsalespayment1['bank'];
			}
		}
	}
	$due = 0.00;
	$due = $total - $payment;
	if($due==0)
	{
		echo $due."</td><td><span class='status Available'>Received</span></td></script>";
	}
	else if($due<$total)
	{
		echo $due."</td><td><span class='status Partial'>Partial Received</span></td></script>";
	}
	else if($due==$total)
	{
		echo $due."</td><td><span class='status Unavailable'>Due</span></td></script>";
	}
?>
								<td>
									<ion-icon name="funnel" onclick="dropdown('<?php echo $row_readsales_history['date']; ?>')">
										
									</ion-icon>
									<div id="<?php echo $row_readsales_history['date']; ?>" class="dropdown">
										<a onclick="view_hover('sales()','saledetail','<?php echo $row_readsales_history['bill_no']; ?>')">View</a>
										<a onclick="payment_hover('sales()','sale','<?php echo $row_readsales_history['bill_no']; ?>')">Payment</a>
										<a onclick="edit_hover('sales()','sales_history','<?php echo $row_readsales_history['bill_no']; ?>')">Edit</a>
										<a onclick="delete_hover('sales()','sale','<?php echo $row_readsales_history['bill_no']; ?>')">Delete</a>
									</div>
								</td>
							</tr>
<?php
		}
?>
						</tbody>
					</table>
<?php
	}
?>  

				</div>

					<div id="vendor" class="content_box display">
						<div class="top">
							<span class="title">Vendor</span>
						</div>
						<div class="add_btn" onclick="open_hover('add_vendor')">New Vendor</div>
<?php
	if(isset($_SESSION['edit_vendor']))
	{
		if($_SESSION['edit_vendor']=="Vendor Successfully Edited!")
		{
			echo "<div class='pwarning'>".$_SESSION['edit_vendor']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['edit_vendor']."</div>";
		}
	}
?>
<?php
	if(isset($_SESSION['delete_vendor']))
	{
		if($_SESSION['delete_vendor']=="Vendor Successfully Deleted!")
		{
			echo "<div class='pwarning'>".$_SESSION['delete_vendor']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['delete_vendor']."</div>";
		}
	}
?>
<?php
	$readvendor = "select * from vendor";
	$result_readvendor = mysqli_query($conn,$readvendor);
	if(mysqli_num_rows($result_readvendor)>0)
	{
?>
						<table>
							<thead>
								<tr>
									<td>Vendor</td>
									<td>Balance</td>
									<td></td>
								</tr>
							</thead>
							<tbody>
<?php
		while ($row_readvendor=mysqli_fetch_array($result_readvendor))
		{
?>
								<tr>
									<td><?php echo $row_readvendor['vendor_name']; ?></td>
									<td>
<?php
	$due = 0.00;
	$readsales_history11 = "select * from sales_history";
	$result_readsales_history11 = mysqli_query($conn,$readsales_history11);
	if(mysqli_num_rows($result_readsales_history11)>0)
	{
		while($row_readsales_history11 = mysqli_fetch_array($result_readsales_history11))
		{
			if($row_readsales_history11['vendor_id']==$row_readvendor['vendor_id'])
			{
	$sale = 0.00;
	$received = 0.00;
	$readsales111 = "select * from sales";
	$result_readsales111 = mysqli_query($conn,$readsales111);
	if(mysqli_num_rows($result_readsales111)>0)
	{
		while($row_readsales111 = mysqli_fetch_array($result_readsales111))
		{
			if($row_readsales111['bill_no']==$row_readsales_history11['bill_no'])
			{
				$sale = $sale + ($row_readsales111['sale_rate'] * $row_readsales111['sale_quantity']);
			}
		}
	}
	$sale = $sale + ($sale * 0.13);
	$sale = $sale - $row_readsales_history11['discount'];
	$read_salespayment11 = "select * from sales_payment";
	$result_readsalespayment11 = mysqli_query($conn,$read_salespayment11);
	if(mysqli_num_rows($result_readsalespayment11)>0)
	{
		while($row_readsalespayment11 = mysqli_fetch_array($result_readsalespayment11))
		{
			if($row_readsalespayment11['bill_no']==$row_readsales_history11['bill_no'])
			{
				$received = $row_readsalespayment11['cash'] + $row_readsalespayment11['bank'];
			}
		}
	}
	$due = $due + ($sale-$received);
			}
		}
	}
	echo $due;
?>
									</td>
									<td>
										<ion-icon name="funnel" onclick="dropdown('<?php echo $row_readvendor['vendor_name'].$row_readvendor['vendor_name']; ?>')">
											
										</ion-icon>
										<div id="<?php echo $row_readvendor['vendor_name'].$row_readvendor['vendor_name']; ?>" class="dropdown">
											<a onclick="view_hover('sales()','vendor','<?php echo $row_readvendor['vendor_id']; ?>')">View</a>
											<a onclick="edit_hover('sales()','vendor','<?php echo $row_readvendor['vendor_id']; ?>')">Edit</a>
											<a onclick="delete_hover('sales()','vendor','<?php echo $row_readvendor['vendor_id']; ?>')">Delete</a>
										</div>
									</td>
								</tr>
<?php
		}
?>
							</tbody>
						</table>
<?php
	}
?>
					</div>
			</div>

			<div id="purchases_content" class="content" style="display:none;">

				<div class="content_box" style="display: flex;box-shadow: none;padding: 2px;">
					<div class="content_box" style="width:calc(25% - 20px);display: flex;margin: 10px;padding: 0;">
						<canvas id="myChart3" width="100"></canvas>
					</div>
					<div class="content_box" style="width:calc(75% - 20px);display: flex;margin: 10px;padding: 0;">
						<canvas id="myChart4" width="100" height="35"></canvas>
					</div>
				</div>
				<script>
				const ctx3 = document.getElementById('myChart3').getContext('2d');
				const ctx4 = document.getElementById('myChart4').getContext('2d');
				const myChart3 = new Chart(ctx3, {
				    type: 'doughnut',
				    data: {
				        labels: ['Credit', 'Cash Paid', 'Bank Paid'],
				        datasets: [{
				            label: 'Purchases Report',
				            data: [
<?php
	$total_sale = 0.00;
	$total_cash_paid = 0.00;
	$total_bank_paid = 0.00;
	$total_due = 0.00;
	$sql = "select * from purchases_history";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			$sale = 0.00;
			$cash_paid = 0.00;
			$bank_paid = 0.00;
			$sql1 = "select * from purchases";
			$result1 = mysqli_query($conn,$sql1);
			if(mysqli_num_rows($result1)>0)
			{
				while($row1 = mysqli_fetch_array($result1))
				{
					if($row['bill_no']==$row1['bill_no']&&$row['supplier_id']==$row1['supplier_id'])
					{
						$sale = $sale + ($row1['purchase_rate'] * $row1['purchase_quantity']);
					}
				}
			}
			$sale = $sale + ($sale * 0.13);
			$sale = $sale - $row['discount'];
			$sql2 = "select * from purchases_payment";
			$result2 = mysqli_query($conn,$sql2);
			if(mysqli_num_rows($result2)>0)
			{
				while($row2 = mysqli_fetch_array($result2))
				{
					if($row['bill_no']==$row2['bill_no']&&$row['supplier_id']==$row2['supplier_id'])
					{
						$cash_paid = $row2['cash'];
						$bank_paid = $row2['bank'];
					}
				}
			}
			$total_sale = $total_sale + $sale;
			$total_cash_paid = $total_cash_paid + $cash_paid;
			$total_bank_paid = $total_bank_paid + $bank_paid;
		}
	}
	$total_due = $total_sale - ($total_cash_paid + $total_bank_paid);
	echo $total_due.",".$total_cash_paid.",".$total_bank_paid;
?>
				            ],
				            backgroundColor: [
				                '#ff0000',
				                '#aa0000',
				                '#770000'
				            ],
				            borderColor: [
				                '#ffffff'
				            ],
				            borderWidth: 1
				        }]
				    }
				});
				const myChart4 = new Chart(ctx4, {
					type: 'line',
					data: {
						labels: [
<?php
	$sql = "select * from purchases_history";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			echo "'Bill No.: ".$row['bill_no']."',";
		}
	}
?>
						],
						datasets: [{
							label: 'Purchases Report',
							data: [
<?php
	$sql = "select * from purchases_history limit 10";
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)>0)
	{
		while($row = mysqli_fetch_array($result))
		{
			$sale = 0.00;
			$sql1 = "select * from purchases";
			$result1 = mysqli_query($conn,$sql1);
			if(mysqli_num_rows($result1)>0)
			{
				while($row1 = mysqli_fetch_array($result1))
				{
					if($row['bill_no']==$row1['bill_no']&&$row['supplier_id']==$row1['supplier_id'])
					{
						$sale = $sale + ($row1['purchase_rate'] * $row1['purchase_quantity']);
					}
				}
			}
			$sale = $sale + ($sale * 0.13);
			$sale = $sale - $row['discount'];
			echo $sale.",";
		}
	}
?>
							],
							fill:true,
							borderColor: '#ff0000',
							tension: 0.1
						}]
					}
				});
				</script>

				<div id="add_supplier" class="content_box">
					<div class="top">
						<span class="title">New Supplier</span>
					</div>
<?php
	if(isset($_SESSION['add_supplier']))
	{
		if($_SESSION['add_supplier']=="Supplier Successfully Added!")
		{
			echo "<div class='pwarning'>".$_SESSION['add_supplier']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['add_supplier']."</div>";
		}
	}
?>
					<form class="hover_content" method="post" action="php/add_supplier.php">
						<input type="text" class="input_field" name="supplier_name" placeholder="Supplier Name" required>
						<input type="text" class="input_field" name="supplier_address" placeholder="Supplier Address" required>
						<input type="number" class="input_field" name="supplier_contact" placeholder="0000000000" required>
						<br><br><br>
						<button type="button" class="submit" onclick="close_hover('add_supplier')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
				</div>

				<div id="edit_supplier" class="content_box">
					<div class="top">
						<span class="title">Edit Supplier</span>
					</div>
<?php
	$readsupplier12 = "select * from supplier";
	$result_readsupplier12 = mysqli_query($conn,$readsupplier12);
	if(mysqli_num_rows($result_readsupplier12)>0)
	{
		while($row_readsupplier12 = mysqli_fetch_array($result_readsupplier12))
		{
			if($row_readsupplier12['supplier_id']==$_COOKIE['id'])
			{
?>
					<form class="hover_content" method="post" action="php/edit_supplier.php">
						<input type="text" class="input_field" name="supplier_id" value="<?php if(isset($_COOKIE['id'])){echo $_COOKIE['id'];} ?>" readonly>
						<input type="text" class="input_field" name="supplier_name" value="<?php echo $row_readsupplier12['supplier_name']; ?>" required>
						<input type="text" class="input_field" name="supplier_address" value="<?php echo $row_readsupplier12['supplier_address']; ?>" required>
						<input type="number" class="input_field" name="supplier_contact" value="<?php echo $row_readsupplier12['supplier_contact']; ?>" required><br><br><br>
						<button type="button" class="submit" onclick="close_hover('edit_supplier')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
<?php
			}
		}
	}
?>
				</div>

				<div id="view_supplier" class="content_box">
					<div class="top">
						<span class="title">View Supplier</span>
					</div>
					<form class="hover_content">
<?php
	$readsupplier123 = "select * from supplier";
	$result_readsupplier123 = mysqli_query($conn,$readsupplier123);
	if(mysqli_num_rows($result_readsupplier123)>0)
	{
		while($row_readsupplier123 = mysqli_fetch_array($result_readsupplier123))
		{
			if($row_readsupplier123['supplier_id']==$_COOKIE['id'])
			{
?>
						<input type="text" class="input_field" name="supplier_id" value="<?php if(isset($_COOKIE['id'])){echo $_COOKIE['id'];} ?>" readonly>
						<input type="text" class="input_field" name="supplier_name" value="<?php echo $row_readsupplier123['supplier_name']; ?>" readonly>
						<input type="text" class="input_field" name="supplier_address" value="<?php echo $row_readsupplier123['supplier_address']; ?>" readonly>
						<input type="number" class="input_field" name="supplier_contact" value="<?php echo $row_readsupplier123['supplier_contact']; ?>" readonly><br><br><br>
<?php
			}
		}
	}
?>
						<button type="button" class="submit" onclick="close_hover('view_supplier')">Close</button>
					</form>
				</div>

				<div id="delete_supplier" class="content_box">
					<div class="top">
						<span class="title">Delete Supplier</span>
					</div>
					<form class="hover_content" method="post" action="php/delete_supplier.php">
<?php
	$readsupplier123 = "select * from supplier";
	$result_readsupplier123 = mysqli_query($conn,$readsupplier123);
	if(mysqli_num_rows($result_readsupplier123)>0)
	{
		while($row_readsupplier123 = mysqli_fetch_array($result_readsupplier123))
		{
			if($row_readsupplier123['supplier_id']==$_COOKIE['id'])
			{
?>
						<input type="text" class="input_field" name="supplier_id" value="<?php if(isset($_COOKIE['id'])){echo $_COOKIE['id'];} ?>" readonly>
						<input type="text" class="input_field" name="supplier_name" value="<?php echo $row_readsupplier123['supplier_name']; ?>" readonly>
						<input type="text" class="input_field" name="supplier_address" value="<?php echo $row_readsupplier123['supplier_address']; ?>" readonly>
						<input type="number" class="input_field" name="supplier_contact" value="<?php echo $row_readsupplier123['supplier_contact']; ?>" readonly><br><br><br>
<?php
			}
		}
	}
?>
						<button type="button" class="submit" onclick="close_hover('delete_supplier')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
				</div>

				<div id="add_purchase" class="content_box">
					<div class="top">
						<span class="title">New Purchase</span>
					</div>
<?php
	if(isset($_SESSION['add_purchase']))
	{
		if($_SESSION['add_purchase']=="Purchase Successfully Made!")
		{
			echo "<div class='pwarning'>".$_SESSION['add_purchase']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['add_purchase']."</div>";
		}
	}
?>
					<form class="hover_content" method="post" action="php/add_purchase.php">
						<input type="number" name="bill_no"class="input_field" placeholder="Bill No." style="width: 250px;text-align: center;" min="1" oninput="validity.valid||(value='');" required>
						<select style="width: 250px;text-align: center;" class="input_field" name="supplier_id" required>
							<option value="" selected disabled hidden>--select--</option>
<?php
	$readsupplier1 = "select * from supplier";
	$result_readsupplier1 = mysqli_query($conn,$readsupplier1);
	if(mysqli_num_rows($result_readsupplier1)>0)
	{
		while($row_readsupplier1 = mysqli_fetch_array($result_readsupplier1))
		{
?>
							<option value="<?php echo $row_readsupplier1['supplier_id']; ?>"><?php echo $row_readsupplier1['supplier_name']; ?></option>
<?php
		}
	}
?>
						</select> 
					    <TABLE id="dataTable" class="dataTable" width="350px" border="0">  
					        <TH>
					        	<td>Product</td>
					        	<td>Rate</td>
					        	<td>Quantity</td>
					        	<td>Total</td>
					        	<td></td>
					        </TH>
					        <TR>  
					        	<TD>1</TD> 
				                <TD>
				        <select class="input_field" name="product_id1" required>
							<option value="" selected disabled hidden>--select--</option>
<?php
	$brand_name = "";
	$category_name = "";
	$readproduct1111 = "select * from product";
	$result_readproduct1111 = mysqli_query($conn,$readproduct1111);
	if(mysqli_num_rows($result_readproduct1111)>0)
	{
		while($row_readproduct1111 = mysqli_fetch_array($result_readproduct1111))
		{
			$readbrand1111 = "select * from brand";
			$result_readbrand1111 = mysqli_query($conn,$readbrand1111);
			if(mysqli_num_rows($result_readbrand1111)>0)
			{
				while($row_readbrand1111 = mysqli_fetch_array($result_readbrand1111))
				{
					if($row_readproduct1111['brand_id']==$row_readbrand1111['brand_id'])
					{
						$brand_name = $row_readbrand1111['brand_name'];
					}
				}
			}
			$readcategory1111 = "select * from category";
			$result_readcategory1111 = mysqli_query($conn,$readcategory1111);
			if(mysqli_num_rows($result_readcategory1111)>0)
			{
				while($row_readcategory1111 = mysqli_fetch_array($result_readcategory1111))
				{
					if($row_readproduct1111['category_id']==$row_readcategory1111['category_id'])
					{
						$category_name = $row_readcategory1111['category_name'];
					}
				}
			}
?>
							<option value="<?php echo $row_readproduct1111['product_id']; ?>"><?php echo $row_readproduct1111['product_name']."-".$brand_name."-".$category_name; ?></option>
<?php
		}
	}
?>
						</select> 
				                </TD>  
				                <TD><input class="input_field" type="number" name="purchase_rate1" value="1.00" onchange="calculate_total()" min="1" oninput="validity.valid||(value='');" required></TD>
				                <TD><input class="input_field" type="number" name="purchase_quantity1" value="1" onchange="calculate_total()" min="1" oninput="validity.valid||(value='');" required></TD>
				                <td><input class="input_field" type="number" name="total1" readonly></td>
				                <td></td>
							</TR>
				        </TABLE>
				        <input class="input_field" type="text" value="Sub Total" style="width: 250px;text-align: center;border: none;" readonly>
				        <input class="input_field" type="number" id="sub_total" name="sub_total" value="0.00" style="width: 250px;text-align: center;" readonly>
				        <input class="input_field" type="text" value="Vat" style="width: 250px;text-align: center;border: none;" readonly>
				        <input class="input_field" type="number" id="vat" name="vat" placeholder="13%" value="0.13" style="width: 250px;text-align: center;" readonly>
				        <input class="input_field" type="text" value="Discount" style="width: 250px;text-align: center;border: none;" readonly>
				        <input class="input_field" type="number" id="discount" name="discount" value="0.00" min="0" oninput="validity.valid||(value='');" style="width: 250px;text-align: center;" onchange="calculate_total()" required>
				        <input class="input_field" type="text" value="Net Total" style="width: 250px;text-align: center;border: none;" readonly>
				        <input class="input_field" type="number" id="net_total" name="net_total" value="0.00" style="width: 250px;text-align: center;" readonly>
						<br><br><br>
						<button type="button" class="submit" onclick="addRow('dataTable')">Add Row</button>
						<button type="button" class="submit" onclick="close_hover('add_purchase')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
				</div>

				<div id="view_purchasedetail" class="content_box">
					<div class="top">
						<span class="title">Bill Detail</span>
					</div>
					<form class="hover_content">
<?php
	$total = 0;
	$product_name = "";
	$readpurchases_history1 = "select * from purchases_history";
	$result_readpurchases_history1 = mysqli_query($conn,$readpurchases_history1);
	if(mysqli_num_rows($result_readpurchases_history1)>0)
	{
		while($row_readpurchases_history1 = mysqli_fetch_array($result_readpurchases_history1))
		{
			if($row_readpurchases_history1['purchase_id']==$_COOKIE['id'])
			{
?>
						<input type="text" value="Bill No. : " class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
						<input type="text" value="Bill No.:<?php echo $row_readpurchases_history1['bill_no']; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>			
<?php
				$supplier_name = "";
				$readsupplier1234 = "select * from supplier";
				$result_readsupplier1234 = mysqli_query($conn,$readsupplier1234);
				if(mysqli_num_rows($result_readsupplier1234)>0)
				{
					while($row_readsupplier1234=mysqli_fetch_array($result_readsupplier1234))
					{
						if($row_readsupplier1234['supplier_id']==$row_readpurchases_history1['supplier_id'])
						{
							$supplier_name = $row_readsupplier1234['supplier_name'];
						}
					}
				}
?>
						<input type="text" value="Company Name : " class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
						<input type="text" value="<?php echo $supplier_name; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
					    <TABLE class="dataTable" width="350px" border="0">  
					        <TH>
					        	<td>Product</td>
					        	<td>Rate</td>
					        	<td>Quantity</td>
					        	<td>Total</td>
					        </TH>
<?php
				$readpurchases1 = "select * from purchases";
				$result_readpurchases1 = mysqli_query($conn,$readpurchases1);
				if(mysqli_num_rows($result_readpurchases1)>0)
				{
					while($row_readpurchases1 = mysqli_fetch_array($result_readpurchases1))
					{
						if($row_readpurchases1['bill_no']==$row_readpurchases_history1['bill_no']&&$row_readpurchases1['supplier_id']==$row_readpurchases_history1['supplier_id'])
						{
?>
<?php
							$readproduct11111 = "select * from product";
							$result_readproduct11111 = mysqli_query($conn,$readproduct11111);
							if(mysqli_num_rows($result_readproduct11111)>0)
							{
								while($row_readproduct11111=mysqli_fetch_array($result_readproduct11111))
								{
									if($row_readproduct11111['product_id']==$row_readpurchases1['product_id'])
									{
										$product_name = $row_readproduct11111['product_name'];
										$readbrand11111 = "select * from brand";
										$result_readbrand11111 = mysqli_query($conn,$readbrand11111);
										if(mysqli_num_rows($result_readbrand11111)>0)
										{
											while($row_readbrand11111=mysqli_fetch_array($result_readbrand11111))
											{
												if($row_readproduct11111['brand_id']==$row_readbrand11111['brand_id'])
												{
													$product_name = $product_name."-".$row_readbrand11111['brand_name'];
												}
											}
										}
										$readcategory11111 = "select * from category";
										$result_readcategory11111 = mysqli_query($conn,$readcategory11111);
										if(mysqli_num_rows($result_readcategory11111)>0)
										{
											while($row_readcategory11111=mysqli_fetch_array($result_readcategory11111))
											{
												if($row_readproduct11111['category_id']==$row_readcategory11111['category_id'])
												{
													$product_name = $product_name."-".$row_readcategory11111['category_name'];
												}
											}
										}
									}
								}
							}
?>
					        <TR>  
					        	<TD style="width: 10px;"></TD> 
				                <TD><input type="text" value="<?php echo $product_name; ?>" class="input_field" style="text-align: center;" readonly></TD>  
				                <TD><input type="text" value="<?php echo $row_readpurchases1['purchase_rate']; ?>" class="input_field" style="text-align: center;" readonly></td>
				                <td><input type="text" value="<?php echo $row_readpurchases1['purchase_quantity']; ?>" class="input_field" style="text-align: center;" readonly></td>
				                <td><input type="text" value="<?php echo $row_readpurchases1['purchase_rate']*$row_readpurchases1['purchase_quantity']; ?>" class="input_field" style="text-align: center;" readonly></td>
							</TR>
<?php
							$total = $total + ($row_readpurchases1['purchase_rate']*$row_readpurchases1['purchase_quantity']);
						}
					}
				}
?>
				        </TABLE>
				        <input type="text" value="Sub Total" class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
				        <input type="text" value="<?php echo $total; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
				        <input type="text" value="Vat Amount" class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
				        <input type="text" value="<?php echo $total*0.13;$total = $total + $total*0.13; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
				        <input type="text" value="Discount Amount" class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
				        <input type="text" value="<?php echo $row_readpurchases_history1['discount'];$total = $total - $row_readpurchases_history1['discount']; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
				        <input type="text" value="Net Total" class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
				        <input type="text" value="<?php echo $total; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
<?php
			}
		}
	}
?>
						<br><br><br>
						<button type="button" class="submit" onclick="close_hover('view_purchasedetail')">Close</button>
					</form>
				</div>

				<div id="payment_purchase" class="content_box">
					<div class="top">
						<span class="title">Bill Payment</span>
					</div>
					<form class="hover_content" method="post" action="php/purchase_payment.php">
<?php
	$readpurchases_history2 = "select * from purchases_history";
	$result_readpurchases_history2 = mysqli_query($conn,$readpurchases_history2);
	if(mysqli_num_rows($result_readpurchases_history2)>0)
	{
		while($row_readpurchases_history2 = mysqli_fetch_array($result_readpurchases_history2))
		{
			if($row_readpurchases_history2['purchase_id']==$_COOKIE['id'])
			{
?>
						<input type="text" class="input_field" value="Bill No." style="width: 250px;text-align: center;border:none;" readonly>
						<input type="text" class="input_field" name="bill_no" style="width: 250px;text-align: center;" value="<?php echo $row_readpurchases_history2['bill_no']; ?>" readonly>
						<input type="text" class="input_field" value="Supplier" style="width: 250px;text-align: center;border:none;" readonly>
<?php
				$supplier_name = "";
				$readsupplier12345 = "select * from supplier";
				$result_readsupplier12345 = mysqli_query($conn,$readsupplier12345);
				if(mysqli_num_rows($result_readsupplier12345)>0)
				{
					while ($row_readsupplier12345 = mysqli_fetch_array($result_readsupplier12345))
					{
						if($row_readpurchases_history2['supplier_id']==$row_readsupplier12345['supplier_id'])
						{
							$supplier_name = $row_readsupplier12345['supplier_name'];
						}
					}
				}
				$total = 0.00;
				$readpurchases11 = "select * from purchases";
				$result_readpurchases11 = mysqli_query($conn,$readpurchases11);
				if(mysqli_num_rows($result_readpurchases11)>0)
				{
					while ($row_readpurchases11 = mysqli_fetch_array($result_readpurchases11))
					{
						if($row_readpurchases_history2['supplier_id']==$row_readpurchases11['supplier_id']&&$row_readpurchases_history2['bill_no']==$row_readpurchases11['bill_no'])
						{
							$total = $total + ($row_readpurchases11['purchase_rate']*$row_readpurchases11['purchase_quantity']);
						}
					}
				}
				$total = $total + ($total * 0.13);
				$total = $total - $row_readpurchases_history2['discount'];
?>
<?php
				$due = 0.00;
				$read_purchasespayment = "select * from purchases_payment";
				$result_readpurchasespayment = mysqli_query($conn,$read_purchasespayment);
				if(mysqli_num_rows($result_readpurchasespayment)>0)
				{
					while($row_readpurchasespayment = mysqli_fetch_array($result_readpurchasespayment))
					{
						if($row_readpurchases_history2['bill_no']==$row_readpurchasespayment['bill_no']&&$row_readpurchases_history2['supplier_id']==$row_readpurchasespayment['supplier_id'])
						{
							$due = $total - ($row_readpurchasespayment['cash']+$row_readpurchasespayment['bank']);
						}
					}
				}
?>
						<input type="text" class="input_field" value="<?php echo $supplier_name; ?>" style="width: 250px;text-align: center;" readonly>
						<input type="text" class="input_field" name="supplier_id" style="width: 250px;text-align: center;display: none;" value="<?php echo $row_readpurchases_history2['supplier_id']; ?>" readonly>
						<input type="text" class="input_field" name="total" style="width: 250px;text-align: center;display: none;" value="<?php echo $total; ?>" readonly>
						<input type="text" class="input_field" value="Due Amount" style="width: 250px;text-align: center;border:none;" readonly>
						<input type="text" class="input_field" name="due" style="width: 250px;text-align: center;" value="<?php echo $due; ?>" readonly>
						<input type="text" class="input_field" value="Payment" style="width: 250px;text-align: center;border:none;" readonly>
						<select class="input_field" name="payment" style="width:250px;text-align: center;" required>
							<option value="" selected disabled hidden>--select--</option>
							<option value="cash">Cash</option>
							<option value="bank">Bank</option>
						</select>
						<input type="text" class="input_field" value="Amount" style="width: 250px;text-align: center;border:none;" readonly>
						<input type="number" class="input_field" name="amount" style="width: 250px;text-align: center;" placeholder="0.00" min="0" oninput="validity.valid||(value='');" required>
						<br><br><br>
						<button type="button" class="submit" onclick="close_hover('payment_purchase')">Close</button>
<?php
				if($due>0)
				{
?>
						<button type="submit" class="submit">Proceed</button>
<?php
				}
			}
		}
	}
?>
					</form>
				</div>

				<div id="delete_purchase" class="content_box">
					<div class="top">
						<span class="title">Delete Bill</span>
					</div>
					<form class="hover_content" method="post" action="php/delete_purchase.php">
<?php
	$total = 0;
	$product_name = "";
	$readpurchases_history1 = "select * from purchases_history";
	$result_readpurchases_history1 = mysqli_query($conn,$readpurchases_history1);
	if(mysqli_num_rows($result_readpurchases_history1)>0)
	{
		while($row_readpurchases_history1 = mysqli_fetch_array($result_readpurchases_history1))
		{
			if($row_readpurchases_history1['purchase_id']==$_COOKIE['id'])
			{
?>
						<input type="text" value="Bill No. : " class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
						<input type="text" name="bill_no" value="<?php echo $row_readpurchases_history1['bill_no']; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>			
<?php
				$supplier_name = "";
				$readsupplier1234 = "select * from supplier";
				$result_readsupplier1234 = mysqli_query($conn,$readsupplier1234);
				if(mysqli_num_rows($result_readsupplier1234)>0)
				{
					while($row_readsupplier1234=mysqli_fetch_array($result_readsupplier1234))
					{
						if($row_readsupplier1234['supplier_id']==$row_readpurchases_history1['supplier_id'])
						{
							$supplier_name = $row_readsupplier1234['supplier_name'];
						}
					}
				}
?>
						<input type="text" value="Company Name : " class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
						<input type="text" name="supplier_id" value="<?php echo $row_readpurchases_history1['supplier_id']; ?>" class="input_field" style="width: 250px;text-align: center;display: none;" readonly>
						<input type="text" value="<?php echo $supplier_name; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
					    <TABLE class="dataTable" width="350px" border="0">  
					        <TH>
					        	<td>Product</td>
					        	<td>Rate</td>
					        	<td>Quantity</td>
					        	<td>Total</td>
					        </TH>
<?php
				$readpurchases1 = "select * from purchases";
				$result_readpurchases1 = mysqli_query($conn,$readpurchases1);
				if(mysqli_num_rows($result_readpurchases1)>0)
				{
					while($row_readpurchases1 = mysqli_fetch_array($result_readpurchases1))
					{
						if($row_readpurchases1['bill_no']==$row_readpurchases_history1['bill_no']&&$row_readpurchases1['supplier_id']==$row_readpurchases_history1['supplier_id'])
						{
?>
<?php
							$readproduct11111 = "select * from product";
							$result_readproduct11111 = mysqli_query($conn,$readproduct11111);
							if(mysqli_num_rows($result_readproduct11111)>0)
							{
								while($row_readproduct11111=mysqli_fetch_array($result_readproduct11111))
								{
									if($row_readproduct11111['product_id']==$row_readpurchases1['product_id'])
									{
										$product_name = $row_readproduct11111['product_name'];
										$readbrand11111 = "select * from brand";
										$result_readbrand11111 = mysqli_query($conn,$readbrand11111);
										if(mysqli_num_rows($result_readbrand11111)>0)
										{
											while($row_readbrand11111=mysqli_fetch_array($result_readbrand11111))
											{
												if($row_readproduct11111['brand_id']==$row_readbrand11111['brand_id'])
												{
													$product_name = $product_name."-".$row_readbrand11111['brand_name'];
												}
											}
										}
										$readcategory11111 = "select * from category";
										$result_readcategory11111 = mysqli_query($conn,$readcategory11111);
										if(mysqli_num_rows($result_readcategory11111)>0)
										{
											while($row_readcategory11111=mysqli_fetch_array($result_readcategory11111))
											{
												if($row_readproduct11111['category_id']==$row_readcategory11111['category_id'])
												{
													$product_name = $product_name."-".$row_readcategory11111['category_name'];
												}
											}
										}
									}
								}
							}
?>
					        <TR>  
					        	<TD style="width: 10px;"></TD> 
				                <TD><input type="text" value="<?php echo $product_name; ?>" class="input_field" style="text-align: center;" readonly></TD>  
				                <TD><input type="text" value="<?php echo $row_readpurchases1['purchase_rate']; ?>" class="input_field" style="text-align: center;" readonly></td>
				                <td><input type="text" value="<?php echo $row_readpurchases1['purchase_quantity']; ?>" class="input_field" style="text-align: center;" readonly></td>
				                <td><input type="text" value="<?php echo $row_readpurchases1['purchase_rate']*$row_readpurchases1['purchase_quantity']; ?>" class="input_field" style="text-align: center;" readonly></td>
							</TR>
<?php
							$total = $total + ($row_readpurchases1['purchase_rate']*$row_readpurchases1['purchase_quantity']);
						}
					}
				}
?>
				        </TABLE>
				        <input type="text" value="Sub Total" class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
				        <input type="text" value="<?php echo $total; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
				        <input type="text" value="Vat Amount" class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
				        <input type="text" value="<?php echo $total*0.13;$total = $total + $total*0.13; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
				        <input type="text" value="Discount Amount" class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
				        <input type="text" value="<?php echo $row_readpurchases_history1['discount'];$total = $total - $row_readpurchases_history1['discount']; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
				        <input type="text" value="Net Total" class="input_field" style="width: 250px;text-align: center;border: none;" readonly>
				        <input type="text" value="<?php echo $total; ?>" class="input_field" style="width: 250px;text-align: center;" readonly>
<?php
			}
		}
	}
?>
						<br><br><br>
						<button type="button" class="submit" onclick="close_hover('delete_purchase')">Close</button>
						<button type="submit" class="submit">Proceed</button>
					</form>
				</div>
				
				<div id="purchases_history" class="content_box display">
					<div class="top">
						<span class="title">Purchases History</span>
					</div>
					<div class="add_btn" onclick="open_hover('add_purchase')">New Purchase</div>
<?php
	if(isset($_SESSION['purchases_payment']))
	{
		if($_SESSION['purchases_payment']=="Payment Successfully Made!")
		{
			echo "<div class='pwarning'>".$_SESSION['purchases_payment']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['purchases_payment']."</div>";
		}
	}
?>
<?php
	if(isset($_SESSION['delete_purchase']))
	{
		if($_SESSION['delete_purchase']=="Purchase Bill Successfully Deleted!")
		{
			echo "<div class='pwarning'>".$_SESSION['delete_purchase']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['delete_purchase']."</div>";
		}
	}
?>
<?php
	$readpurchases_history = "select * from purchases_history";
	$result_readpurchases_history = mysqli_query($conn,$readpurchases_history);
	if(mysqli_num_rows($result_readpurchases_history)>0)
	{
?>
					<table>
						<thead>
							<tr>
								<td>Bill No.</td>
								<td>Supplier</td>
								<td>Total</td>
								<td>Due</td>
								<td>Status</td>
								<td></td>
							</tr>
						</thead>
						<tbody>
<?php
		while ($row_readpurchases_history=mysqli_fetch_array($result_readpurchases_history))
		{
?>
							<tr>
								<td><?php echo $row_readpurchases_history['bill_no']; ?></td>
								<td>
<?php
	$readsupplier12345 = "select * from supplier";
	$result_readsupplier12345 = mysqli_query($conn,$readsupplier12345);
	if(mysqli_num_rows($result_readsupplier12345)>0)
	{
		while($row_readsupplier12345=mysqli_fetch_array($result_readsupplier12345))
		{
			if($row_readpurchases_history['supplier_id']==$row_readsupplier12345['supplier_id'])
			{
				echo $row_readsupplier12345['supplier_name'];
			}
		}
	}
?>
								</td>
								<td>
<?php
	$readpurchases = "select * from purchases";
	$result_readpurchases = mysqli_query($conn,$readpurchases);
	$total = 0;
	if(mysqli_num_rows($result_readpurchases)>0)
	{
		while($row_readpurchases=mysqli_fetch_array($result_readpurchases))
		{
			if($row_readpurchases_history['bill_no']==$row_readpurchases['bill_no']&&$row_readpurchases_history['supplier_id']==$row_readpurchases['supplier_id'])
			{
				$total = $total + ($row_readpurchases['purchase_rate'] * $row_readpurchases['purchase_quantity']);
			}
		}
	}
	$total = $total + ($total * 0.13);
	$total = $total - $row_readpurchases_history['discount'];
	echo $total;
?>
								</td>
								<td>
<?php
	$payment = 0.00;
	$read_purchasespayment1 = "select * from purchases_payment";
	$result_readpurchasespayment1 = mysqli_query($conn,$read_purchasespayment1);
	if(mysqli_num_rows($result_readpurchasespayment1)>0)
	{
		while($row_readpurchasespayment1 = mysqli_fetch_array($result_readpurchasespayment1))
		{
			if($row_readpurchases_history['bill_no']==$row_readpurchasespayment1['bill_no']&&$row_readpurchases_history['supplier_id']==$row_readpurchasespayment1['supplier_id'])
			{
				$payment = $row_readpurchasespayment1['cash'] + $row_readpurchasespayment1['bank'];
			}
		}
	}
	$due = 0.00;
	$due = $total - $payment;
	if($due==0)
	{
		echo $due."</td><td><span class='status Available'>Paid</span>";
	}
	else if($due<$total)
	{
		echo $due."</td><td><span class='status Partial'>Partial Payment</span>";
	}
	else if($due==$total)
	{
		echo $due."</td><td><span class='status Unavailable'>Due</span>";
	}
?>
								</td>
								<td>
									<ion-icon name="funnel" onclick="dropdown('<?php echo $row_readpurchases_history['date']; ?>')">
										
									</ion-icon>
									<div id="<?php echo $row_readpurchases_history['date']; ?>" class="dropdown">
										<a onclick="view_hover('purchases()','purchasedetail','<?php echo $row_readpurchases_history['purchase_id']; ?>')">View</a>
										<a onclick="payment_hover('purchases()','purchase','<?php echo $row_readpurchases_history['purchase_id']; ?>')">Payment</a>
										<a onclick="edit_hover('purchases()','purchases_history','<?php echo $row_readpurchases_history['purchase_id']; ?>')">Edit</a>
										<a onclick="delete_hover('purchases()','purchase','<?php echo $row_readpurchases_history['purchase_id']; ?>')">Delete</a>
									</div>
								</td>
							</tr>
<?php
		}
?>
						</tbody>
					</table>
<?php
	}
?>
				</div>

					<div id="supplier" class="content_box display">
						<div class="top">
							<span class="title">Supplier</span>
						</div>
						<div class="add_btn" onclick="open_hover('add_supplier')">New Supplier</div>
<?php
	if(isset($_SESSION['edit_supplier']))
	{
		if($_SESSION['edit_supplier']=="Supplier Successfully Edited!")
		{
			echo "<div class='pwarning'>".$_SESSION['edit_supplier']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['edit_supplier']."</div>";
		}
	}
?>
<?php
	if(isset($_SESSION['delete_supplier']))
	{
		if($_SESSION['delete_supplier']=="Supplier Successfully Deleted!")
		{
			echo "<div class='pwarning'>".$_SESSION['delete_supplier']."</div>";
		}
		else
		{
			echo "<div class='nwarning'>".$_SESSION['delete_supplier']."</div>";
		}
	}
?>
<?php
	$readsupplier = "select * from supplier";
	$result_readsupplier = mysqli_query($conn,$readsupplier);
	if(mysqli_num_rows($result_readsupplier)>0)
	{
?>
						<table>
							<thead>
								<tr>
									<td>Supplier</td>
									<td>Balance</td>
									<td></td>
								</tr>
							</thead>
							<tbody>
<?php
		while ($row_readsupplier=mysqli_fetch_array($result_readsupplier))
		{
?>
								<tr>
									<td><?php echo $row_readsupplier['supplier_name']; ?></td>
									<td>
<?php
	$due = 0.00;
	$readsales_history11 = "select * from purchases_history";
	$result_readsales_history11 = mysqli_query($conn,$readsales_history11);
	if(mysqli_num_rows($result_readsales_history11)>0)
	{
		while($row_readsales_history11 = mysqli_fetch_array($result_readsales_history11))
		{
			if($row_readsales_history11['supplier_id']==$row_readsupplier['supplier_id'])
			{
	$sale = 0.00;
	$received = 0.00;
	$readsales111 = "select * from purchases";
	$result_readsales111 = mysqli_query($conn,$readsales111);
	if(mysqli_num_rows($result_readsales111)>0)
	{
		while($row_readsales111 = mysqli_fetch_array($result_readsales111))
		{
			if($row_readsales111['bill_no']==$row_readsales_history11['bill_no']&&$row_readsales111['supplier_id']==$row_readsales_history11['supplier_id'])
			{
				$sale = $sale + ($row_readsales111['purchase_rate'] * $row_readsales111['purchase_quantity']);
			}
		}
	}
	$sale = $sale + ($sale * 0.13);
	$sale = $sale - $row_readsales_history11['discount'];
	$read_salespayment11 = "select * from purchases_payment";
	$result_readsalespayment11 = mysqli_query($conn,$read_salespayment11);
	if(mysqli_num_rows($result_readsalespayment11)>0)
	{
		while($row_readsalespayment11 = mysqli_fetch_array($result_readsalespayment11))
		{
			if($row_readsalespayment11['bill_no']==$row_readsales_history11['bill_no']&&$row_readsalespayment11['supplier_id']==$row_readsales_history11['supplier_id'])
			{
				$received = $row_readsalespayment11['cash'] + $row_readsalespayment11['bank'];
			}
		}
	}
	$due = $due + ($sale-$received);
			}
		}
	}
	echo $due;
?>
									</td>
									<td>
										<ion-icon name="funnel" onclick="dropdown('<?php echo $row_readsupplier['supplier_name'].$row_readsupplier['supplier_id']; ?>')">
											
										</ion-icon>
										<div id="<?php echo $row_readsupplier['supplier_name'].$row_readsupplier['supplier_id']; ?>" class="dropdown">
											<a onclick="view_hover('purchases()','supplier','<?php echo $row_readsupplier['supplier_id']; ?>')">View</a>
											<a onclick="edit_hover('purchases()','supplier','<?php echo $row_readsupplier['supplier_id']; ?>')">Edit</a>
											<a onclick="delete_hover('purchases()','supplier','<?php echo $row_readsupplier['supplier_id']; ?>')">Delete</a>
										</div>
									</td>
								</tr>
<?php
		}
?>
							</tbody>
						</table>
<?php
	}
?>
					</div>

			</div>
			<div id="settings_content" class="content" style="display:none;">
				
			</div>
		</div>
	</div>
	<script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
	<script>
		let toggle = document.querySelector('.toggle');
		let navigation = document.querySelector('.navigation');
		let main = document.querySelector('.main');
		toggle.onclick = function(){
			navigation.classList.toggle('active');
			main.classList.toggle('active');
		}
		let list = document.querySelectorAll('.navigation li');
		function activeLink(){
			list.forEach((item) =>
			item.classList.remove('hovered'));
			this.classList.add('hovered');
		}
		list.forEach((item) =>
		item.addEventListener('click',activeLink));
		document.documentElement.style.setProperty('--text-color', 'white');
		function theme()
		{
			if(document.getElementById("theme").checked == true)
			{
				document.documentElement.style.setProperty('--background-color', 'black');
				document.documentElement.style.setProperty('--content-color', 'white');
				let date = new Date(Date.now() + 86400e3);
				date = date.toUTCString();
				document.cookie = "theme=dark; expires=" + date;
			}
			else
			{
				document.documentElement.style.setProperty('--background-color', 'white');
				document.documentElement.style.setProperty('--content-color', 'black');
				let date = new Date(Date.now() + 86400e3);
				date = date.toUTCString();
				document.cookie = "theme=light; expires=" + date;
			}
		}
		function getCookie(cookieName) {
		    var name = cookieName + "=";
		    var ca = document.cookie.split(';');
		    for (var i = 0; i < ca.length; i++) {
		        var c = ca[i].trim();
		        if ((c.indexOf(name)) == 0) {
		            return c.substr(name.length);
		        }

		    }
		    return null;
		}
		function themeload()
		{
			var theme = getCookie("theme");
			if(theme != null)
			{
				if(theme == "dark")
				{
					document.getElementById("theme").checked = true;
				}
				else
				{
					document.getElementById("theme").checked = false;
				}
			}
		}
		themeload();
		theme();
		dashboard();
		function allgone()
		{
			document.getElementById('Dashboard').classList.remove('hovered');
			document.getElementById('Inventory').classList.remove('hovered');
			document.getElementById('Sales').classList.remove('hovered');
			document.getElementById('Purchases').classList.remove('hovered');
			document.getElementById('Settings').classList.remove('hovered');
			document.getElementById("dashboard_content").style.display = "none";
			document.getElementById("inventory_content").style.display = "none";
			document.getElementById("sales_content").style.display = "none";
			document.getElementById("purchases_content").style.display = "none";
			document.getElementById("settings_content").style.display = "none";
		}
		function dashboard()
		{
			allgone();
			document.getElementById("dashboard_content").style.display = "block";
			document.getElementById('Dashboard').classList.add('hovered');
		}
		function inventory()
		{
			allgone();
			document.getElementById("inventory_content").style.display = "block";
			document.getElementById('Inventory').classList.add('hovered');
		}
		function sales()
		{
			allgone();
			document.getElementById("sales_content").style.display = "block";
			document.getElementById('Sales').classList.add('hovered');
		}
		function purchases()
		{
			allgone();
			document.getElementById("purchases_content").style.display = "block";
			document.getElementById('Purchases').classList.add('hovered');
		}
		function settings()
		{
			allgone();
			document.getElementById("settings_content").style.display = "block";
			document.getElementById('Settings').classList.add('hovered');
		}
		function logout()
		{
			let date = new Date(Date.now() + 86400e3);
			date = date.toUTCString();
			document.cookie = "loginstatus=logout; expires=" + date;
			window.location.href = 'index.php';
		}
		function open_hover(hover)
		{
			document.getElementById('blur').style.display = "block";
			document.getElementById(hover).style.display = "block";
		}
		function close_hover(hover)
		{
			document.getElementById(hover).style.display = "none";
			document.getElementById('blur').style.display = "none";
			clear_cookies();
		}
		function dropdown(id)
		{
			if(document.getElementById(id).style.display == "block")
			{
				document.getElementById(id).style.display = "none";
			}
			else
			{
				document.getElementById(id).style.display = "block";
			}
		}
		function edit_hover(menu,field,id)
		{
			let date = new Date(Date.now() + 86400e3);
			date = date.toUTCString();
			document.cookie = "menu=" + menu + "; expires=" + date; 
			document.cookie = "field=" + field + "; expires=" + date;
			document.cookie = "id=" + id + "; expires=" + date;
			location.reload(true);
		}
		function delete_hover(menu,sector,id)
		{
			let date = new Date(Date.now() + 86400e3);
			date = date.toUTCString();
			document.cookie = "menu=" + menu + "; expires=" + date; 
			document.cookie = "sector=" + sector + "; expires=" + date;
			document.cookie = "id=" + id + "; expires=" + date;
			location.reload(true);
		}
		function view_hover(menu,vector,id)
		{
			let date = new Date(Date.now() + 86400e3);
			date = date.toUTCString();
			document.cookie = "menu=" + menu + "; expires=" + date; 
			document.cookie = "vector=" + vector + "; expires=" + date;
			document.cookie = "id=" + id + "; expires=" + date;
			location.reload(true);
		}
		function payment_hover(menu,pector,id)
		{
			let date = new Date(Date.now() + 86400e3);
			date = date.toUTCString();
			document.cookie = "menu=" + menu + "; expires=" + date; 
			document.cookie = "pector=" + pector + "; expires=" + date;
			document.cookie = "id=" + id + "; expires=" + date;
			location.reload(true);
		}
		function clear_cookies()
		{
			let date = new Date(Date.now() + 86400e3);
			date = date.toUTCString();
			document.cookie = "menu=dashboard(); expires=" + date; 
			document.cookie = "sector=; expires=" + date;
			document.cookie = "field=; expires=" + date;
			document.cookie = "vector=; expires=" + date;
			document.cookie = "pector=; expires=" + date;
		}
	</script>
</body>
</html>
<?php
	if(isset($_COOKIE['field']))
	{
		if($_COOKIE['field']!="")
		{
			echo "<script>document.getElementById('edit_".$_COOKIE['field']."').style.display = 'block';document.getElementById('blur').style.display = 'block';clear_cookies();</script>";
		}
	}
	if(isset($_COOKIE['sector']))
	{
		if($_COOKIE['sector']!="")
		{
			echo "<script>document.getElementById('delete_".$_COOKIE['sector']."').style.display = 'block';document.getElementById('blur').style.display = 'block';clear_cookies();</script>";
		}
	}
	if(isset($_COOKIE['vector']))
	{
		if($_COOKIE['vector']!="")
		{
			echo "<script>document.getElementById('view_".$_COOKIE['vector']."').style.display = 'block';document.getElementById('blur').style.display = 'block';clear_cookies();</script>";
		}
	}
	if(isset($_COOKIE['pector']))
	{
		if($_COOKIE['pector']!="")
		{
			echo "<script>document.getElementById('payment_".$_COOKIE['pector']."').style.display = 'block';document.getElementById('blur').style.display = 'block';clear_cookies();</script>";
		}
	}
	if(isset($_COOKIE['menu']))
	{
		echo "<script>clear_cookies();".$_COOKIE['menu'].";</script>";
	}
	if(isset($_SESSION['add_brand'])||isset($_SESSION['add_category'])||isset($_SESSION['add_product'])||isset($_SESSION['edit_brand'])||isset($_SESSION['edit_category'])||isset($_SESSION['edit_product'])||isset($_SESSION['delete_brand'])||isset($_SESSION['delete_category'])||isset($_SESSION['delete_product']))
	{
		if(isset($_SESSION['add_product']))
		{
			echo "<script>open_hover('add_product');</script>";
		}
		if(isset($_SESSION['add_brand']))
		{
			echo "<script>open_hover('add_brand');</script>";
		}
		if(isset($_SESSION['add_category']))
		{
			echo "<script>open_hover('add_category');</script>";
		}
		echo "<script>clear_cookies();inventory();</script>";
	}
	if(isset($_SESSION['add_sale'])||isset($_SESSION['add_vendor'])||isset($_SESSION['edit_sale'])||isset($_SESSION['edit_vendor'])||isset($_SESSION['delete_sale'])||isset($_SESSION['delete_vendor'])||isset($_SESSION['sales_payment']))
	{
		if(isset($_SESSION['add_sale']))
		{
			echo "<script>open_hover('add_sale');</script>";
		}
		if(isset($_SESSION['add_vendor']))
		{
			echo "<script>open_hover('add_vendor');</script>";
		}
		echo "<script>clear_cookies();sales();</script>";
	}
	if(isset($_SESSION['add_purchase'])||isset($_SESSION['add_supplier'])||isset($_SESSION['delete_purchase'])||isset($_SESSION['delete_supplier'])||isset($_SESSION['edit_purchase'])||isset($_SESSION['edit_supplier'])||isset($_SESSION['purchases_payment']))
	{
		if(isset($_SESSION['add_purchase']))
		{
			echo "<script>open_hover('add_purchase');</script>";
		}
		if(isset($_SESSION['add_supplier']))
		{
			echo "<script>open_hover('add_supplier');</script>";
		}
		echo "<script>clear_cookies();purchases();</script>";
	}
	session_destroy();
?>



		
<SCRIPT language="javascript">
	let date = new Date(Date.now() + 86400e3);
	date = date.toUTCString();
	document.cookie = "rowCount1=1; expires=" + date;
	document.cookie = "rowCount=1; expires=" + date;
	calculate_total();
	calculate_total1();
    function addRow(tableID) {  
        var table = document.getElementById(tableID);  
        var rowCount = table.rows.length; 
        let date = new Date(Date.now() + 86400e3);
		date = date.toUTCString();
		document.cookie = "rowCount=" + rowCount + "; expires=" + date;
        var row = table.insertRow(rowCount);
        var cell0 = row.insertCell(0);  
        var element0 = document.createElement("span");
        element0.innerHTML = rowCount;
        var btnName = "button" + (rowCount);  
        element0.name = btnName;
        cell0.appendChild(element0);
        
        var cell1 = row.insertCell(1);
        cell1.innerHTML = "<select onchange='calculate_total()' class='input_field' name='product_id"+rowCount+"' required><option value='' selected disabled hidden>--select--</option><?php
	$brand_name = "";
	$category_name = "";
	$readproduct1111 = "select * from product";
	$result_readproduct1111 = mysqli_query($conn,$readproduct1111);
	if(mysqli_num_rows($result_readproduct1111)>0)
	{
		while($row_readproduct1111 = mysqli_fetch_array($result_readproduct1111))
		{
			$readbrand1111 = "select * from brand";
			$result_readbrand1111 = mysqli_query($conn,$readbrand1111);
			if(mysqli_num_rows($result_readbrand1111)>0)
			{
				while($row_readbrand1111 = mysqli_fetch_array($result_readbrand1111))
				{
					if($row_readproduct1111['brand_id']==$row_readbrand1111['brand_id'])
					{
						$brand_name = $row_readbrand1111['brand_name'];
					}
				}
			}
			$readcategory1111 = "select * from category";
			$result_readcategory1111 = mysqli_query($conn,$readcategory1111);
			if(mysqli_num_rows($result_readcategory1111)>0)
			{
				while($row_readcategory1111 = mysqli_fetch_array($result_readcategory1111))
				{
					if($row_readproduct1111['category_id']==$row_readcategory1111['category_id'])
					{
						$category_name = $row_readcategory1111['category_name'];
					}
				}
			}
?>
							<option value='<?php echo $row_readproduct1111["product_id"]; ?>'><?php echo $row_readproduct1111['product_name']."-".$brand_name."-".$category_name; ?></option><?php
		}
	}
?>
						</select> ";

        var cell2 = row.insertCell(2);
        cell2.innerHTML = "<input class='input_field' type='number' name='purchase_rate"+rowCount+"' onchange='calculate_total()' min='1.00' oninput='validity.valid||(value='');' value='1.00' required>";

        var cell3 = row.insertCell(3);
        cell3.innerHTML = "<input class='input_field' type='number' name='purchase_quantity"+rowCount+"' onchange='calculate_total()' min='1' oninput='validity.valid||(value='');' value='1' required>";

        var cell4 = row.insertCell(4);
        cell4.innerHTML = "<input class='input_field' type='number' name='total"+rowCount+"' placeholder='0.00' readonly>";

        var cell5 = row.insertCell(5);  
        var element5 = document.createElement("input");
        element5.class = "input_field"  
        element5.type = "button"; 
        element5.setAttribute('value', 'Delete'); 
        element5.onclick = function () { removeRow(btnName); }  
        cell5.appendChild(element5);  
    }
    function addRow1(tableID) {  
        var table = document.getElementById(tableID);  
        var rowCount = table.rows.length; 
        let date = new Date(Date.now() + 86400e3);
		date = date.toUTCString();
		document.cookie = "rowCount1=" + rowCount + "; expires=" + date;
        var row = table.insertRow(rowCount);
        var cell0 = row.insertCell(0);  
        var element0 = document.createElement("span");
        element0.innerHTML = rowCount;
        var btnName = "button" + (rowCount);  
        element0.name = btnName;
        cell0.appendChild(element0);
        
        var cell1 = row.insertCell(1);
        cell1.innerHTML = "<select onchange='calculate_total1()' class='input_field' name='product_id"+rowCount+"' required><option value='' selected disabled hidden>--select--</option><?php
	$brand_name = "";
	$category_name = "";
	$readproduct11111 = "select * from product";
	$result_readproduct11111 = mysqli_query($conn,$readproduct11111);
	if(mysqli_num_rows($result_readproduct11111)>0)
	{
		while($row_readproduct11111 = mysqli_fetch_array($result_readproduct11111))
		{
			$readbrand11111 = "select * from brand";
			$result_readbrand11111 = mysqli_query($conn,$readbrand11111);
			if(mysqli_num_rows($result_readbrand11111)>0)
			{
				while($row_readbrand11111 = mysqli_fetch_array($result_readbrand11111))
				{
					if($row_readproduct11111['brand_id']==$row_readbrand11111['brand_id'])
					{
						$brand_name = $row_readbrand11111['brand_name'];
					}
				}
			}
			$readcategory11111 = "select * from category";
			$result_readcategory11111 = mysqli_query($conn,$readcategory11111);
			if(mysqli_num_rows($result_readcategory11111)>0)
			{
				while($row_readcategory11111 = mysqli_fetch_array($result_readcategory11111))
				{
					if($row_readproduct11111['category_id']==$row_readcategory11111['category_id'])
					{
						$category_name = $row_readcategory11111['category_name'];
					}
				}
			}
?>
							<option value='<?php echo $row_readproduct11111["product_id"]; ?>'><?php echo $row_readproduct11111['product_name']."-".$brand_name."-".$category_name; ?></option><?php
		}
	}
?>
						</select> ";

        var cell2 = row.insertCell(2);
        cell2.innerHTML = "<input class='input_field' type='number' name='sale_rate"+rowCount+"' onchange='calculate_total1()' min='1.00' oninput='validity.valid||(value='');' value='1.00' required>";

        var cell3 = row.insertCell(3);
        cell3.innerHTML = "<input class='input_field' type='number' name='sale_quantity"+rowCount+"' onchange='calculate_total1()' min='1' oninput='validity.valid||(value='');' value='1' required>";

        var cell4 = row.insertCell(4);
        cell4.innerHTML = "<input class='input_field' type='number' name='total"+rowCount+"' placeholder='0.00' readonly>";

        var cell5 = row.insertCell(5);  
        var element5 = document.createElement("input");
        element5.class = "input_field"  
        element5.type = "button"; 
        element5.setAttribute('value', 'Delete'); 
        element5.onclick = function () { removeRow1(btnName); }  
        cell5.appendChild(element5);  
    }  
    function removeRow(btnName) {  
        try {  
            var table = document.getElementById('dataTable');  
            var rowCount = table.rows.length;  
            for (var i = 0; i < rowCount; i++) {  
                var row = table.rows[i];  
                var rowObj = row.cells[0].childNodes[0];  
                if (rowObj.name == btnName) {  
                    table.deleteRow(i);  
                    rowCount--;  
                }  
            }
            let date = new Date(Date.now() + 86400e3);
			date = date.toUTCString();
			document.cookie = "rowCount=" + rowCount + "; expires=" + date;
        }  
        catch (e) {  
            alert(e);  
        }  
    }
    function removeRow1(btnName) {  
        try {  
            var table = document.getElementById('dataTable1');  
            var rowCount = table.rows.length;  
            for (var i = 0; i < rowCount; i++) {  
                var row = table.rows[i];  
                var rowObj = row.cells[0].childNodes[0];  
                if (rowObj.name == btnName) {  
                    table.deleteRow(i);  
                    rowCount--;  
                }  
            }
            let date = new Date(Date.now() + 86400e3);
			date = date.toUTCString();
			document.cookie = "rowCount1=" + rowCount + "; expires=" + date;
        }  
        catch (e) {  
            alert(e);  
        }  
    }
    function calculate_total()
    {
    	var table = document.getElementById('dataTable');  
        var rowCount = table.rows.length;
        let sub_total = 0;
        for (var i = 1; i < rowCount; i++) {
        	var row = table.rows[i];
        	var rowrate = row.cells[2].childNodes[0];
        	var rowquantity = row.cells[3].childNodes[0];
        	var rowtotal = row.cells[4].childNodes[0];
        	rowtotal.value = rowrate.value * rowquantity.value;
        	sub_total = parseInt(sub_total) + parseInt(rowtotal.value);
        }
        var rowsub_total = document.getElementById('sub_total');
        rowsub_total.value = parseInt(sub_total);
        var rowvat = document.getElementById('vat');
        rowvat.value = parseInt(sub_total) * 0.13;
        sub_total = parseInt(sub_total) + parseInt(rowvat.value);
        var rowdiscount = document.getElementById('discount');
        sub_total = parseInt(sub_total) - rowdiscount.value;
        var rownet_total = document.getElementById('net_total');
        rownet_total.value = parseInt(sub_total);
    }
    function calculate_total1()
    {
    	var table = document.getElementById('dataTable1');  
        var rowCount = table.rows.length;
        let sub_total = 0;
        for (var i = 1; i < rowCount; i++) {
        	var row = table.rows[i];
        	var rowrate = row.cells[2].childNodes[0];
        	var rowquantity = row.cells[3].childNodes[0];
        	var rowtotal = row.cells[4].childNodes[0];
        	rowtotal.value = rowrate.value * rowquantity.value;
        	sub_total = parseInt(sub_total) + parseInt(rowtotal.value);
        }
        var rowsub_total = document.getElementById('sub_total1');
        rowsub_total.value = parseInt(sub_total);
        var rowvat = document.getElementById('vat1');
        rowvat.value = parseInt(sub_total) * 0.13;
        sub_total = parseInt(sub_total) + parseInt(rowvat.value);
        var rowdiscount = document.getElementById('discount1');
        sub_total = parseInt(sub_total) - rowdiscount.value;
        var rownet_total = document.getElementById('net_total1');
        rownet_total.value = parseInt(sub_total);
    }
</SCRIPT>