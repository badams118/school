<?php
session_start();

ini_set('display_errors', 'On');

$dbhost = 'dbms.oregonstate.edu';
$dbname = 'my-dbms';
$dbuser = 'user';
$dbpass = 'password';
	
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
?>