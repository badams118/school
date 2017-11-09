<!DOCTYPE html>
<html>
<head>
<title>Populate Sample Data</title>
<body>
<?php
ini_set('display_errors', 'On');

$dbhost = 'dbms.oregonstate.edu';
$dbname = 'my-dbms';
$dbuser = 'user';
$dbpass = 'password';

$mysql_handle = mysql_connect($dbhost, $dbuser, $dbpass)
or die("Error connecting to database server");

mysql_select_db($dbname, $mysql_handle)
or die("Error selecting database: $dbname");

echo "<p>Successfully connected to database.</p>";

mysql_query("truncate table service_providers");
mysql_query("truncate table addresses");
mysql_query("truncate table phones");
mysql_query("truncate table reviews");
mysql_query("truncate table users");

mysql_query("insert into service_providers (service_provider_id, service_provider_name) values (1, 'Downtown Dental')");
mysql_query("insert into service_providers (service_provider_id, service_provider_name) values (2, 'Michigan Avenue Dental Associates')");
mysql_query("insert into service_providers (service_provider_id, service_provider_name) values (3, 'Printers Row Dentistry')");
mysql_query("insert into service_providers (service_provider_id, service_provider_name) values (4, 'University Associates Dentistry')");
mysql_query("insert into service_providers (service_provider_id, service_provider_name) values (5, 'Chicago Cosmetic Dental')");

mysql_query("insert into service_providers (service_provider_id, service_provider_name) values (6, 'Calm Dental')");
mysql_query("insert into service_providers (service_provider_id, service_provider_name) values (7, 'Trident Cosmetic & Family Dentistry')");
mysql_query("insert into service_providers (service_provider_id, service_provider_name) values (8, 'Esthetic Dentistry Dental Group')");
mysql_query("insert into service_providers (service_provider_id, service_provider_name) values (9, 'Wilshire Dental Care')");
mysql_query("insert into service_providers (service_provider_id, service_provider_name) values (10, 'American Dental Association')");

mysql_query("insert into service_providers (service_provider_id, service_provider_name) values (11, 'Dentistry Of Miami')");
mysql_query("insert into service_providers (service_provider_id, service_provider_name) values (12, 'Coral Gables Dentistry')");
mysql_query("insert into service_providers (service_provider_id, service_provider_name) values (13, 'Skyline Dental Care')");
mysql_query("insert into service_providers (service_provider_id, service_provider_name) values (14, 'Great Expressions Dental Centers')");
mysql_query("insert into service_providers (service_provider_id, service_provider_name) values (15, 'Relax and Smile Dental Care')");

$rs = mysql_query("select * from service_providers");
$nrows = mysql_numrows($rs);

mysql_query("insert into addresses (service_provider_id, address_line1, city, state, postal_code) 
	values (1, '525 S Olive St.', 'Chicago', 'IL', '60602')");	
mysql_query("insert into addresses (service_provider_id, address_line1, city, state, postal_code) 
	values (2, '122 S Michigan Ave.', 'Chicago', 'IL', '60602')");	
mysql_query("insert into addresses (service_provider_id, address_line1, city, state, postal_code) 
	values (3, '721 S Dearborn St.', 'Chicago', 'IL', '60605')");
mysql_query("insert into addresses (service_provider_id, address_line1, city, state, postal_code) 
	values (4, '222 North Lasalle St.', 'Chicago', 'IL', '60601')");
mysql_query("insert into addresses (service_provider_id, address_line1, city, state, postal_code) 
	values (5, '222 North Lasalle St.', 'Chicago', 'IL', '60601')");
	
mysql_query("insert into addresses (service_provider_id, address_line1, city, state, postal_code) 
	values (6, '525 S Olive St.', 'Los Angeles', 'CA', '90013')");	
mysql_query("insert into addresses (service_provider_id, address_line1, city, state, postal_code) 
	values (7, '11377 W Olympic Blvd.', 'Los Angeles', 'CA', '90064')");	
mysql_query("insert into addresses (service_provider_id, address_line1, city, state, postal_code) 
	values (8, '1080 Wilshire Blvd.', 'Los Angeles', 'CA', '90017')");
mysql_query("insert into addresses (service_provider_id, address_line1, city, state, postal_code) 
	values (9, '6200 Wilshire Blvd.', 'Los Angeles', 'CA', '90048')");
mysql_query("insert into addresses (service_provider_id, address_line1, city, state, postal_code) 
	values (10, '3660 Wilshire Blvd.', 'Los Angeles', 'CA', '90010')");

mysql_query("insert into addresses (service_provider_id, address_line1, city, state, postal_code) 
	values (11, '7800 SW 87th Ave.', 'Miami', 'FL', '33173')");	
mysql_query("insert into addresses (service_provider_id, address_line1, city, state, postal_code) 
	values (12, '220 Miracle Mile', 'Miami', 'FL', '33134')");	
mysql_query("insert into addresses (service_provider_id, address_line1, city, state, postal_code) 
	values (13, '25 SE 2nd Ave.', 'Miami', 'FL', '33131')");
mysql_query("insert into addresses (service_provider_id, address_line1, city, state, postal_code) 
	values (14, '1261 SW 8th St.', 'Miami', 'FL', '33135')");
mysql_query("insert into addresses (service_provider_id, address_line1, city, state, postal_code) 
	values (15, '138 NE 2nd Ave.', 'Miami', 'FL', '33132')");
	
mysql_query("insert into phones (service_provider_id, area_code, phone_number) 
	values (1, '312', '7828862')");
mysql_query("insert into phones (service_provider_id, area_code, phone_number) 
	values (2, '312', '9229595')");
mysql_query("insert into phones (service_provider_id, area_code, phone_number) 
	values (3, '312', '4350411')");
mysql_query("insert into phones (service_provider_id, area_code, phone_number) 
	values (4, '312', '7045511')");
mysql_query("insert into phones (service_provider_id, area_code, phone_number) 
	values (5, '312', '2652757')");

mysql_query("insert into phones (service_provider_id, area_code, phone_number) 
	values (6, '213', '4162420')");
mysql_query("insert into phones (service_provider_id, area_code, phone_number) 
	values (7, '213', '4441445')");	
mysql_query("insert into phones (service_provider_id, area_code, phone_number) 
	values (8, '213', '5534535')");
mysql_query("insert into phones (service_provider_id, area_code, phone_number) 
	values (9, '213', '9386137')");
mysql_query("insert into phones (service_provider_id, area_code, phone_number) 
	values (10, '213', '3807669')");

mysql_query("insert into phones (service_provider_id, area_code, phone_number) 
	values (11, '305', '5982622')");
mysql_query("insert into phones (service_provider_id, area_code, phone_number) 
	values (12, '305', '5671992')");	
mysql_query("insert into phones (service_provider_id, area_code, phone_number) 
	values (13, '305', '3731361')");
mysql_query("insert into phones (service_provider_id, area_code, phone_number) 
	values (14, '305', '8582545')");
mysql_query("insert into phones (service_provider_id, area_code, phone_number) 
	values (15, '305', '4329770')");
		
echo "<p>Data load complete.</p>";

mysql_close($mysql_handle);
?>
</body>
</head>