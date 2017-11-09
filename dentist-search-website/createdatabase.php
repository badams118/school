<!DOCTYPE html>
<html>
<head>
<title>Create Dentist Finder Database</title>
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

echo '<p>Successfully connected to database.</p>';

mysql_query('drop table service_providers');

mysql_query('
create table service_providers(
	service_provider_id int(15) not null auto_increment, 
	service_provider_name varchar(50),
	url varchar(240),
	creation_date timestamp default current_timestamp,
	created_by int(15),
	last_update_date timestamp,
	last_updated_by int(15),
	primary key(service_provider_id))
') or die(mysql_error());

echo "<p>SERVICE_PROVIDERS table creation successfull.</p>";

mysql_query('drop table users');

mysql_query('
create table users(
	user_id int(15) not null auto_increment, 
	service_provider_id int(15),
	user_name varchar(100) not null unique,
	first_name varchar(40),
	last_name varchar(50),
	email_address varchar(240),
	password varchar(100) not null,
	password_date date,
	last_logon_date date,
	creation_date timestamp default current_timestamp,
	created_by int(15),
	last_update_date timestamp,
	last_updated_by int(15),
	primary key(user_id))
') or die(mysql_error());

echo "<p>USERS table creation successfull.</p>";

mysql_query('drop table contacts');

mysql_query('
create table contacts(
	contact_id int(15) not null auto_increment, 
	service_provider_id int(15),
	title varchar(30),
	department varchar(30),
	address_id int(15),
	creation_date timestamp default current_timestamp,
	created_by int(15),
	last_update_date timestamp,
	last_updated_by int(15),
	primary key(contact_id))
') or die(mysql_error());

echo "<p>CONTACTS table creation successfull.</p>";

mysql_query('drop table addresses');

mysql_query('
create table addresses(
	address_id int(15) not null auto_increment, 
	service_provider_id int(15),
	contact_id int(15),
	address_line1 varchar(240),
	address_line2 varchar(240),
	city varchar(60),
	state varchar(2),
	postal_code varchar(10),
	creation_date timestamp default current_timestamp,
	created_by int(15),
	last_update_date timestamp,
	last_updated_by int(15),
	primary key(address_id))
') or die(mysql_error());

echo "<p>ADDRESSES table creation successfull.</p>";

mysql_query('drop table phones');

mysql_query('
create table phones(
	phone_id int(15) not null auto_increment, 
	service_provider_id int(15),
	contact_id int(15),
	address_id int(15),
	area_code varchar(3),
	phone_number varchar(7),
	extension varchar(20),
	phone_type varchar(30),
	creation_date timestamp default current_timestamp,
	created_by int(15),
	last_update_date timestamp,
	last_updated_by int(15),
	primary key(phone_id))
') or die(mysql_error());

echo "<p>PHONES table creation successfull.</p>";

mysql_query('drop table services');

mysql_query('
create table services(
	service_id int(15) not null auto_increment, 
	service_provider_id int(15),
	type varchar(150),
	description varchar(240),
	creation_date timestamp default current_timestamp,
	created_by int(15),
	last_update_date timestamp,
	last_updated_by int(15),
	primary key(service_id))
') or die(mysql_error());

echo "<p>SERVICES table creation successfull.</p>";

mysql_query('drop table reviews');

mysql_query('
create table reviews(
	review_id int(15) not null auto_increment, 
	user_id int(15),
	service_provider_id int(15),
	service_id int(15),
	comment varchar(4000),
	rating int(1),
	amount decimal(15, 2),
	creation_date timestamp default current_timestamp,
	created_by int(15),
	last_update_date timestamp,
	last_updated_by int(15),
	primary key(review_id))
') or die(mysql_error());

echo "<p>REVIEWS table creation successfull.</p>";

echo "<p>Database creation complete.</p>";

mysql_close($mysql_handle);

?>
</body>
</head>