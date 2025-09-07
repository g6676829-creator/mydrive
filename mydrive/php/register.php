<?php
require("database.php");

//check request method
if($_SERVER['REQUEST_METHOD'] == "POST")
{
	//create verification code pattern
	$pattern = "1234567890";

	$len = strlen($pattern)-1;
	$v_code = [];
	for ($i=0; $i < 6; $i++) { 
		$indexing = rand(0,$len);
		$v_code[] = $pattern[$indexing];
	}

	$ver_code = implode($v_code);

	$name = $_POST['name'];
	$email = $_POST['email'];
	$password = md5($_POST['password']);

	$check = "SELECT email FROM users WHERE email = '$email'";

	$response = $db->query($check);
	if($response->num_rows != 0)
	{
		echo "userexist";
	}
	else
	{
		//$sendto = "ramanujnishad565@gmail.com";
		$subject = "Verify account";
		$msg = "Thanks for choosing us your verification code is = ".$ver_code;
		$sendfrom = "From: g6676829@gmail.com";

		if(mail($email, $subject, $msg, $sendfrom))
		{
			$store = "INSERT INTO users(name,email,password,activation_code) VALUES('$name','$email','$password','$ver_code')";
			if($db->query($store))
			{
				echo "success";
			}
			else
			{
				echo "failed";
			}
		}
		else
		{
			echo "Try again with another email id";
		}
	}
}
else
{echo "unauthorised request";}



?>