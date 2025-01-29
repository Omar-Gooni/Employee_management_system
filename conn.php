<?php
$host = "localhost:3307";
$user = "root";
$pass = "";
$dbname = "employe_management_system_db";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    echo "not connected";
}
?>