<?php
$host = 'localhost';
$username = 'root';
$password = ''; // Change if necessary
$database = 'crud_app';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>