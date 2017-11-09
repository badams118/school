<!DOCTYPE html>
<html>
<head>
	<title>Dentist Search</title>
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
	  
<?php
session_start();

if (!isset($_SESSION['username']))
{
	echo "
		<ul class='nav pull-right'>
			<li class='active'><a href='register.php'>Register</a></li>
			<li><a href='login.php'>Log in</a></li>
		</ul>";
} else {
	echo "
		<ul class='nav pull-right'>
			<li><a href='logout.php'>Log out</a></li>
		</ul>";
}
?>

	  </div>
	</div>
  </div>
</div>

<div class="container">

	<table border='0'  align='center'><tr><td>
		<h1 align='center'>Welcome to the Dentist Search Website</h1>
		<h4>Please enter one or more criteria to see dentists, and routine cleaning prices in your area.</h4>
		
		<form method="get" action="results.php" enctype="multipart/form-data"> 
			<table>
				<tr><td>Dentist Name:</td><td><input type="text" name="spname" maxlength="50"></td></tr>
				<tr><td>City:</td><td><input type="text" name="city" maxlength="60"></td></tr>
				<tr><td>Postal Code:</td><td><input type="text" name="zip" maxlength="5"></td></tr>
				<tr><td>Area Code:</td><td><input type="text" name="areacode" maxlength="3"></td></tr>
				<tr><td></td><td><input type="submit" value="OK"></td></tr>
			</table>
		</form>
	</td></tr></table>
</div>
</body>
</html>