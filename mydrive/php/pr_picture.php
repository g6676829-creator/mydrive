<?php
session_start();
$userEmail = $_SESSION['user'];
require("database.php");

$resp = $db->query("SELECT * FROM users WHERE email = '$userEmail'");
$userData = $resp->fetch_assoc();
$userIdFolder = "user_".$userData['id'];

$image = $_POST['image'];
echo $image;

?>