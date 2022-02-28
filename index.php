<?php
	session_start();
	if(isset($_COOKIE['loginstatus']))
	{
		if($_COOKIE['loginstatus']=="login")
		{
			header('location: home.php');
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
	<script>
		function login()
		{
			document.getElementById("login").style.left = "50px";
			document.getElementById("signup").style.left = "450px";
			document.getElementById("btn").style.left = "0px";
			document.getElementById("btn").style.background = "#fe0000";
			document.documentElement.style.setProperty('--border-color', '#fe0000');
		}
		function signup()
		{
			document.getElementById("login").style.left = "-400px";
			document.getElementById("signup").style.left = "50px";
			document.getElementById("btn").style.left = "110px";
			document.getElementById("btn").style.background = "#00bb00";
			document.documentElement.style.setProperty('--border-color', '#00bb00');
		}
	</script>
	<div class="login_container">
		<div class="login_form">
			<div class="login_button">
				<div id="btn"></div>
				<button type="button" class="toggle_btn" onclick="login()">Login</button>
				<button type="button" class="toggle_btn" onclick="signup()">Signup</button>
			</div>
<?php
	if(isset($_SESSION['login']))
	{
		if($_SESSION['login']=="Username or Password Invalid!")
		{
			echo "<div id='nwarning' class='nwarning'>".$_SESSION['login']."</div>"."<script>setTimeout(function(){document.getElementById('nwarning').style.display = 'none';}, 2000);</script>";
		}
		else
		{
			echo "<div id='pwarning' class='pwarning'>".$_SESSION['login']."</div>";
		}
	}
?>
<?php
	if(isset($_SESSION['signup']))
	{
		if($_SESSION['signup']=="User Account Created!")
		{
			echo "<div id='pwarning' class='pwarning'>".$_SESSION['signup']."</div>";
		}
		else
		{
			echo "<div id='nwarning' class='nwarning'>".$_SESSION['signup']."</div>"."<script>setTimeout(function(){document.getElementById('nwarning').style.display = 'none';}, 2000);</script>";
		}
	}
?>
			<form id="login" class="login_input" method="post" action="php/login.php">
				<input type="text" class="input_box" name="username" placeholder="Username" value='<?php if(isset($_COOKIE["username"])){echo $_COOKIE["username"];} ?>' required>
				<input type="password" class="input_box" name="password" placeholder="Password" value='<?php if(isset($_COOKIE["password"])){echo $_COOKIE["password"];} ?>' required>
				<input type="checkbox" class="check_box" name="checkbox"><span>Remember me</span>
				<button type="submit" class="submit_btn">Login</button>
			</form>
			<form id="signup" class="login_input" method="post" action="php/signup.php">
				<input type="text" class="input_box" name="username" placeholder="Username" required>
				<input type="email" class="input_box" name="email" placeholder="Email" required>
				<input type="password" class="input_box" name="password" placeholder="Password" required>
				<input type="checkbox" class="check_box" name="checkbox" required><span>I agree Terms & Conditions</span>
				<button type="submit" class="submit_btn">Signup</button>
			</form>
			<div class="theme">
				<input id="theme" type="checkbox" class="theme_check_box" name="theme" onchange="theme()"><span>Dark Theme</span>
			</div>
		</div>
	</div>
	<script>
		function theme()
		{
			if(document.getElementById("theme").checked == true)
			{
				document.documentElement.style.setProperty('--background-color', 'black');
				document.documentElement.style.setProperty('--text-color', 'white');
				let date = new Date(Date.now() + 86400e3);
				date = date.toUTCString();
				document.cookie = "theme=dark; expires=" + date;
			}
			else
			{
				document.documentElement.style.setProperty('--background-color', 'white');
				document.documentElement.style.setProperty('--text-color', 'black');
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
	</script>
</body>
</html>
<?php
	if(isset($_SESSION['status']))
	{
		if($_SESSION['status']=="signup")
		{
			echo "<script>signup();</script>";
		}
		if($_SESSION['status']=="login")
		{
			echo "<script>login();</script>";
		}
	}
	if($_SESSION['signup']=="User Account Created!")
	{
		echo "<script>setTimeout(function(){document.getElementById('pwarning').innerText = 'Loging In...';}, 2000);setTimeout(function(){document.getElementById('pwarning').style.display = 'none';}, 4000);</script>";
		setcookie("loginstatus", "login", time() + (86400 * 30));
		header("refresh:4; url=home.php");
	}
	if($_SESSION['login']=="Loging In...")
	{
		echo "<script>setTimeout(function(){document.getElementById('pwarning').style.display = 'none';}, 2000);</script>";
		setcookie("loginstatus", "login", time() + (86400 * 30));
		header("refresh:2; url=home.php");
	}
	session_destroy();
?>
