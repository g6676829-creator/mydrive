<?php
session_start();
require("database.php");

if($_SERVER['REQUEST_METHOD'] == "POST")
{
	$email = $_POST['email'];
	$password = md5($_POST['pass']);

	$check_email = "SELECT email FROM users WHERE email = '$email'";
	$e_resp = $db->query($check_email); 
	if($e_resp->num_rows != 0)
	{
		$psw_check = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
		$psw_resp = $db->query($psw_check);
		if($psw_resp->num_rows != 0)
		{
			//eps stand for email , password & status 
			$eps_check = "SELECT * FROM users WHERE email = '$email' AND password = '$password' AND status = 'active'";
			$s_resp = $db->query($eps_check);
			if($s_resp->num_rows != 0)
			{
				echo "active";
				$_SESSION['user'] = $email;
			}
			else
			{
				echo "pending";
			}

		}
		else
		{
			echo "wrong password";
		}
	}
	else
	{
		echo "user not found";
	}
}
else
{
	echo "unauthorised request";
}

?>