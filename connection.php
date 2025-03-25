<?php
$host = "localhost";
$database = "casestudy";
$username = "root";
$password = "";

// create connecton to database
$conn = new mysqli($host, $username, $password, $database);

// check connection
if($conn->connect_error) {
    die("Kết nối thất bại tới database" . $conn->connect_error);
}
else {
    echo "Kết nối thành công tới database";
}

?>