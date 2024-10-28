<?php
$host = 'localhost';
$username = 'root'; // your db username
$password = ''; // your db password
$database = 'auth_db';

$con = mysqli_connect($host, $username, $password, $database);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>