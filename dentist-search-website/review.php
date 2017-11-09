<?php
include "config.php";
	
if (!isset($_SESSION['username']))
{
	header('Location: login.php');
}

if ($_SERVER['REQUEST_METHOD'] == "POST" 		&& 
		array_key_exists("rating", $_REQUEST)	&& 
		array_key_exists("price", $_REQUEST) 	&& 
		array_key_exists("comment", $_REQUEST)) 
{
	if ($mysqli->query("INSERT INTO reviews(user_id, service_provider_id, comment, rating, amount) values ("
						."'".$mysqli->real_escape_string($_SESSION['userid'])."'"
						.",'".$mysqli->real_escape_string($_REQUEST['id'])."'"
						.",'".$mysqli->real_escape_string($_REQUEST['comment'])."'"
						.",'".$mysqli->real_escape_string($_REQUEST['rating'])."'"
						.",'".$mysqli->real_escape_string($_REQUEST['price'])."')")) 
						
	header('Location: details.php?id='.$_REQUEST['id']);
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Add Your Review</title>
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
				<li><a href='logout.php'>Log out</a></li>
			</ul>
		  </div>
		</div>
	  </div>
	</div>
	
<div class="container">

<?php	
$service_provider_id	= $_GET["id"];
$service_provider_name 	= null;

$stmt = $mysqli->prepare("
	SELECT	service_provider_name
	FROM 	service_providers  
	WHERE 	service_provider_id = ? ");
	
$stmt->bind_param('i', $service_provider_id);

$stmt->execute();

$stmt->bind_result($service_provider_name);

$stmt->fetch();

if (strlen($service_provider_name) > 0) {
	echo "<h4>Please provide your review for: ".$service_provider_name."</h4>";
?>

<form method="post" action="review.php" enctype="multipart/form-data" id="reviewform">
	<table>
		<tr><td>Rating:</td><td>
			1 <input type="radio" name="rating" value="1">
			2 <input type="radio" name="rating" value="2">
			3 <input type="radio" name="rating" value="3">
			4 <input type="radio" name="rating" value="4">
			5 <input type="radio" name="rating" value="5" checked></td></tr>
		<tr><td>Price:</td><td><input name="price" type="number" class="required number" maxlength="18" style="text-align:right"></td></tr>
		<tr><td><input type="hidden" name="id" value="<?php echo $service_provider_id; ?>" /></td><td>
			<textarea rows="5" cols="50"  maxlength="300" name="comment" form="reviewform">Enter comments here...</textarea>
		</td></tr>
		<tr><td></td><td><input type="submit" value="OK"></td></tr>
	</table>
</form>

<?php 
} else {
	echo "Invalid parameter. Please use the <a href='search.php'>search</a> page.";
}
?>

</div>
</body>
</html>