<?php
include "config.php";

$service_provider_id 	= $_GET["id"];
$service_provider_name	= null;
$address_line1 			= null;
$city 					= null;
$state 					= null;
$postal_code 			= null;
$area_code 				= null;
$phone_number 			= null;

$stmt = $mysqli->prepare("
	SELECT	sp.service_provider_name, 
			a.address_line1, 
			a.city, 
			a.state, 
			a.postal_code, 
			CONCAT('(', p.area_code, ')') area_code, 
			CONCAT(SUBSTR(p.phone_number, 1, 3), '-', SUBSTR(p.phone_number, 4, 4)) phone_number
	FROM 	service_providers sp 
	LEFT OUTER JOIN addresses a 
		ON sp.service_provider_id = a.service_provider_id
	LEFT OUTER JOIN phones p 
		ON sp.service_provider_id = p.service_provider_id
	LEFT OUTER JOIN reviews r
		ON sp.service_provider_id = r.service_provider_id
	WHERE sp.service_provider_id = ? ");
	
$stmt->bind_param('i', $service_provider_id);

$stmt->execute();

$stmt->bind_result(
	$service_provider_name,
	$address_line1,
	$city,
	$state,
	$postal_code,
	$area_code,
	$phone_number);
	
$stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Dentist Details</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <style>
		body {
			padding-top: 60px;
		}
    </style>
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBNS3QCbBFXDT4bSvoJ2g1aJDnQ_jA1yoI&sensor=false"></script>
	<script>
		var geocoder;
		var map;

		function initialize(address)
		{
			geocoder = new google.maps.Geocoder();

			var mapProp = {
				zoom:11,
				mapTypeId:google.maps.MapTypeId.ROADMAP
			};

			map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

			geocoder.geocode( { 'address': address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					map.setCenter(results[0].geometry.location);
					var marker = new google.maps.Marker({
						map: map,
						position: results[0].geometry.location
					});
				} else {
					alert("Geocode was not successful for the following reason: " + status);
				}
			});;
		}
	</script>
	
<?php
echo "<script>window.onload = function() { initialize('".
	htmlspecialchars($address_line1).", ".
	htmlspecialchars($city).", ".
	htmlspecialchars($state)." ".
	htmlspecialchars($postal_code).", USA".
	"'); }</script>";
?>

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
			<li><a href='login.php'>Log in</a></li>
		</ul>";
} else {
	echo "
			<li><a href='logout.php'>Log out</a></li>
		</ul>";
}
?>

		  </div>
		</div>
	  </div>
	</div>
	
	<div class="container">

<?php

echo "<table border='0'  align='center'><tr><td>";

if (isset($service_provider_name)){
	echo "<table border='1' cellpadding='4'>";
	echo "<th>Name</th><th>Address</th><th>Phone</th>";
	echo "<tr>";
		echo "<td>".htmlspecialchars($service_provider_name)."</td>";
		echo "<td>".htmlspecialchars($address_line1)."<br>".
			htmlspecialchars($city).", ".
			htmlspecialchars($state)." ".
			htmlspecialchars($postal_code)."</td>";
		echo "<td>".htmlspecialchars($area_code)." ".
			htmlspecialchars($phone_number)."</td>";
	echo "</tr>";
	echo "</table>";
?>

<p><div id="googleMap" style="width:500px;height:380px;"></div></p>
<h4>Reviews:</h4>

<?php
} else {
	echo "Invalid parameter. Please return to the <a href='search.php'>search</a> page.";
}

$stmt->close();

$username	= null;
$comment	= null;
$rating		= null;
$amount		= null;
$date 		= null;

$stmt = $mysqli->prepare("
	SELECT	u.user_name,
			r.comment,
			r.rating,
			CONCAT('$', FORMAT(r.amount, 2)) amount,
			DATE_FORMAT(r.creation_date, '%c-%e-%Y') creation_date
	FROM 	users u, reviews r
	WHERE 	u.user_id = r.user_id
	AND		r.service_provider_id = ? 
	ORDER BY r.creation_date DESC ");
	
$stmt->bind_param('i', $service_provider_id);
	
$stmt->execute();

$stmt->bind_result($username, $comment, $rating, $amount, $date);
	
if (isset($service_provider_name))
{
	if ($stmt->fetch())
	{
		echo "<table border='1' cellpadding='4'>";
		echo "<th>User Name</th><th>Date</th><th>Rating</th><th>Amount</th><th>Comment</th>";
		
		do
		{
			echo "<tr>";
				echo "<td valign='top'>".htmlspecialchars($username)."</td>";
				echo "<td valign='top'>".htmlspecialchars($date)."</td>";
				echo "<td valign='top'>".htmlspecialchars($rating)."</td>";
				echo "<td valign='top'>".htmlspecialchars($amount)."</td>";
				echo "<td style='max-width:400px;word-wrap:break-word;'>".htmlspecialchars($comment)."</td>";
			echo "</tr>";
		} while ($stmt->fetch());
		
		echo "</table>";
	}
	
	// Don't allow reviews unless the user is logged in.
	if (isset($_SESSION['username']))
	{
		echo "<p>Add your review <a href='review.php?id=".$service_provider_id."'>here</a>.</p>";
	} else {
		echo "<p>Please <a href='login.php'>log in</a> to post a review.</p>";
	}
}

echo "</td></tr></table>";

$stmt->close();
$mysqli->close();
?>

</div>

</body>
</html>