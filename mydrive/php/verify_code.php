<?php

require("database.php");

if($_SERVER['REQUEST_METHOD'] == "POST")
{
	$email = $_POST['email'];
	$ver_code = $_POST['ver_code'];

	$check = "SELECT * FROM users WHERE email = '$email' AND activation_code = '$ver_code'";
	$response = $db->query($check);
	if($response->num_rows != 0)
	{
		$update_status = "UPDATE users SET status = 'active' WHERE email = '$email'";
		if($db->query($update_status))
		{
			//create new table & folder for user data 
			$find_id = "SELECT id FROM users WHERE email = '$email'";
			$id_resp = $db->query($find_id);
			$id_array = $id_resp->fetch_assoc();
			$user_table_name = "user_".$id_array['id'];

			//create table program
			$create_user_table = "CREATE TABLE $user_table_name(
				id INT(11) NOT NULL AUTO_INCREMENT,
				file_name VARCHAR(100),
				file_size VARCHAR(100),
				star VARCHAR(100) DEFAULT 'no',
				date_time DATETIME DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY(id)
			)";
			if($db->query($create_user_table))
			{
				if(mkdir("../data/".$user_table_name))
				{
					echo "active";	
				}
				else
				{
					echo "folder not created !";
				}
			}
			
		}
		else{
			echo "status not updated";
		}
		
	}
	else
	{
		echo "wrong verification code";
	}
}
else
{
	echo "unauthorised request";
}


?>