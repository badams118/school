<!DOCTYPE html>
<html>
<head>
	<title>Search Results</title>
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <style>
		body {
			padding-top: 60px;
		}
    </style>
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
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
include "config.php";

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
$service_provider_id	= null;
$service_provider_name	= null;
$address_line1 			= null;
$city 					= null;
$state 					= null;
$postal_code 			= null;
$area_code 				= null;
$phone_number 			= null;
$amount 				= null;
$rating 				= null;
	
$bp_spname 		= "%";
$bp_city 		= "%";
$bp_postalcode 	= "%";
$bp_areacode 	= "%";
	
if ($_SERVER['REQUEST_METHOD'] == "GET") {
	if (strlen($_GET["spname"]) > 0) {
		$bp_spname = "%".strtoupper($_GET["spname"])."%";
	}
	if (strlen($_GET["city"]) > 0) {
		$bp_city = strtoupper($_GET["city"]);
	}
	if (strlen($_GET["zip"]) > 0) {
		$bp_postalcode = $_GET["zip"];
	}
	if (strlen($_GET["areacode"]) > 0) {
		$bp_areacode = $_GET["areacode"];
	}
} 

$stmt = $mysqli->prepare("
	SELECT	sp.service_provider_id,
			sp.service_provider_name, 
			a.address_line1, 
			a.city, 
			a.state, 
			a.postal_code, 
			CONCAT('(', p.area_code, ')') area_code, 
			CONCAT(SUBSTR(p.phone_number, 1, 3), '-', SUBSTR(p.phone_number, 4, 4)) phone_number,
			CONCAT('$', FORMAT(AVG(r.amount), 2)) amount,
			FORMAT(AVG(r.rating), 1) rating
	FROM 	service_providers sp 
	LEFT OUTER JOIN addresses a 
		ON sp.service_provider_id = a.service_provider_id
	LEFT OUTER JOIN phones p 
		ON sp.service_provider_id = p.service_provider_id
	LEFT OUTER JOIN reviews r
		ON sp.service_provider_id = r.service_provider_id 
	WHERE	UPPER(sp.service_provider_name) LIKE ? 
	AND 	UPPER(a.city) 					LIKE ? 
	AND 	a.postal_code 					LIKE ? 
	AND		p.area_code 					LIKE ?
	GROUP BY	sp.service_provider_id,
				sp.service_provider_name, 
				a.address_line1,
				a.city, 
				a.state, 
				a.postal_code, 
				p.area_code, 
				p.phone_number
	ORDER BY	a.state, a.city, a.postal_code, sp.service_provider_name
	LIMIT	25");
	
$stmt->bind_param('ssss', 
	$bp_spname, 
	$bp_city, 
	$bp_postalcode, 
	$bp_areacode);

$stmt->execute();

$stmt->bind_result(
	$service_provider_id,
	$service_provider_name,
	$address_line1,
	$city,
	$state,
	$postal_code,
	$area_code,
	$phone_number,
	$amount,
	$rating);

echo "<table border='0'  align='center'><tr><td>";

echo "<table border='1' cellpadding='4'>";
echo "<th>Name</th><th>Address</th><th>Phone</th><th>Average<br>Rating</th><th>Average<br>Cost</th>";
while ($stmt->fetch())
{
	echo "<tr>";
		echo "<td><a href='details.php?id=".htmlspecialchars($service_provider_id)."'>".
			htmlspecialchars($service_provider_name)."</a></td>";
		echo "<td>".htmlspecialchars($address_line1)."<br>".
			htmlspecialchars($city).", ".
			htmlspecialchars($state)." ".
			htmlspecialchars($postal_code)."</td>";
		echo "<td>".htmlspecialchars($area_code)." ".
			htmlspecialchars($phone_number)."</td>";
		echo "<td align='right'>".htmlspecialchars($rating)."</td>";
		echo "<td align='right'>".htmlspecialchars($amount)."</td>";
	echo "</tr>";
}
echo "</table>";

echo "</td></tr></table>";

$stmt->close();
$mysqli->close();
?>

	</div>
</body>
</html>