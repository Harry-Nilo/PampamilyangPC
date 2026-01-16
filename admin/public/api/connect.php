<?php
$host = "localhost";
$user = "root";
$password = "admin";
$db = "pampamilyang_pc";

$connect = mysqli_connect($host, $user, $password, $db);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
