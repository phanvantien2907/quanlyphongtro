<?php
$host = "localhost";
$database = "gtpt";
$username = "root";
$password = "";

// create connecton to database
$conn = new mysqli($host, $username, $password, $database);

// check connection
if($conn->connect_error) {
    die("Kết nối thất bại tới database" . $conn->connect_error);
}

?>