<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "dbblog"; 

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8");

?>