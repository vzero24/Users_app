<?php
$servername = "db";             // The hostname should be 'db'
$username = "root";             // The username is 'root'
$password = "root_password";    // Replace with the actual root password from your docker-compose.yml
$dbname = "users_app";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}
