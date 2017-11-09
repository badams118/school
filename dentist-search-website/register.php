<!DOCTYPE html>
<html>
<head>
	<title>Registration</title> 
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <style>
		body {
			padding-top: 60px;
		}
    </style>
	<script src="js/jquery.min.js"></script> 
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.validate.min.js"></script>
	<script>$(document).ready(function() {$("form").validate();});</script>
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
			</ul>
		  </div>
		</div>
	  </div>
	</div>
	
	<div class="container">
		<table border='0'  align='center'><tr><td>

			<h4>Please register below:</h4>
			
			<form method="post" action="register.php" enctype="multipart/form-data"> 
				<table>
					<tr><td>User Name:</td><td><input type="text" name="username" class="required" maxlength="100"></td></tr>
					<tr><td>Password:</td><td><input type="password" name="password" class="required" maxlength="100"></td></tr>
					<tr><td></td><td><input type="submit" value="OK"></td></tr>
				</table>
			</form>
<?php
include "config.php";
	
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$username = strtolower($_POST["username"]);
	$password = base64_encode(hash('sha256', $_POST["password"]."6dvh10n2"));

	if ($mysqli->query("INSERT INTO users(user_name, password) values ("
						."'".$mysqli->real_escape_string($username)."'"
						.",'".$mysqli->real_escape_string($password)."')")) 
	{
		echo "<p>Thank you for registering, ".$_POST["username"].".</p>
			<p>To log in, please click <a href='login.php'>here</a>.</p>";
	} else {
		echo "<p>A user with that user name already exists.  Please choose another.</p>";
	}
}

$mysqli->close();
?>

		</td></tr></table>
	</div>
</body>
</html>