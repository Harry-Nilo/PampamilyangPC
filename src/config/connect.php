<?php
$host = "localhost";
$user = "root";
$password = "admin"; //TODO: Change password before deployment (the one you set for your MySQL server)
$db = "pampamilyang_pc";

$connect = mysqli_connect($host, $user, $password, $db);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
