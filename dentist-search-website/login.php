<?php 
include "config.php";
			
if ($_SERVER['REQUEST_METHOD'] == "POST")
{	
	$_SESSION['login_failed'] = False;

	$userid		= null;
	$username 	= $_REQUEST['username'];
	$password 	= base64_encode(hash('sha256',$_REQUEST["password"]."xxxxxxxx"));
	
	//check the login
	$stmt = $mysqli->prepare("
		SELECT	user_id 
		FROM 	users 
		WHERE 	user_name	= '".$username."' 
		AND 	password	= '".$password."'" );
		
    $stmt->execute();

	$stmt->bind_result($userid);

    $stmt->store_result();
		
	if ($stmt->fetch())
	{ 
		//if login passes, then go to search page
		$_SESSION['userid'] 		= $userid;
		$_SESSION['username'] 		= $username;
		$stmt->close();
		header('Location: search.php');
	} 
	
	//reload login page with login failure message
	$_SESSION['login_failed'] = True;
	$stmt->close();
	header('Location: login.php');
		
} else {
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <style>
		body {
			padding-top: 60px;
		}
    </style>
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.validate.min.js"></script>
	<script> $(document).ready(function() {$("form").validate();}); </script>
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
	  <div class="navbar-inner">
		<div class="container">
		  <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <div class="nav-collapse collapse">		  
			<ul class='nav pull-right'>
				<li class='active'><a href='search.php'>New Search</a></li>	
				
<?php
if (!isset($_SESSION['username']))
{
	echo "
			<li><a href='register.php'>Register</a></li>
		</ul>";
}
?>

		  </div>
		</div>
	  </div>
	</div>
	
	<div class="container">
		<table border='0'  align='center'><tr><td>
			<h4>Please log in:</h4>
			<form method="post" action="login.php"> 
				<table>
					<tr><td>Username:</td><td><input type="text" name="username" class="required" maxlength="100"></td></tr>
					<tr><td>Password:</td><td><input type="password" name="password" class="required" maxlength="100"></td></tr>
					<tr><td></td><td><input type="submit" value="OK"></td></tr>
				</table>
			</form>
<?php
	if (isset($_SESSION['login_failed'])) {
		if ($_SESSION['login_failed']) {
			echo "<p>Login failed. Please re-enter your username and password, or <a href='register.php'>register</a>.</p>";
		}
	}
	
	$_SESSION['login_failed'] = False;
}

$mysqli->close();
?>

		</td></tr></table>
	</div>
</body>
</html>