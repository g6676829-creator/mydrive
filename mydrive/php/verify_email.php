<?php
require("database.php");

if($_SERVER['REQUEST_METHOD'] == "POST")
{
	$email = $_POST['email'];

	$check = "SELECT email FROM users WHERE email = '$email'";
	$response = $db->query($check);
	if($response->num_rows != 0)
	{
		echo "userexist";
	}
	else
	{
		echo "notfound";
	}
}
else
{
	echo "unauthorised request";
}
?>