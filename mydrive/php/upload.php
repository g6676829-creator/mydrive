
<?php
session_start();
$userEmail = $_SESSION['user'];
require("database.php");

$file = $_FILES['data'];

$fileName = strtolower($file['name']);
$location = $file['tmp_name'];
$fileSize = round($file['size'] / 1024 / 1024, 2);

$userDetailQuery = "SELECT * FROM users WHERE email = '$userEmail'";
$resp = $db->query($userDetailQuery);
$userData = $resp->fetch_assoc();
$userIdFolder = "user_".$userData['id'];

$totalSpace = $userData['storage'];
$usedSpace = $userData['used_storage'];
$freeSpace = $totalSpace-$usedSpace;

if($fileSize < $freeSpace)
{
	if(file_exists("../data/".$userIdFolder."/".$fileName))
	{
		echo json_encode(array("msg"=>"file allready exist"));
	}
	else
	{
		//becouse user's folder name = user's table name in database
		if(move_uploaded_file($location, "../data/".$userIdFolder."/".$fileName))
		{
			$store = "INSERT INTO $userIdFolder(file_name, file_size) VALUES('$fileName', '$fileSize')";
			if($db->query($store))
			{
				$fsSql = "SELECT sum(file_size) AS uds FROM $userIdFolder";
				$response = $db->query($fsSql);
				$aa = $response->fetch_assoc();
				$totalUsedStorage = round($aa['uds'],2);

				$updateSql = "UPDATE users SET used_storage = '$totalUsedStorage' WHERE email = '$userEmail'";
				if($db->query($updateSql))
				{

					//send data in array formate
					echo json_encode(array("msg"=>"file uploaded successfully","usedFileSize"=>$totalUsedStorage));

				}
				else
				{
					echo json_encode(array("msg"=>"used storage not updated"));
				}
			}
			else
			{
				echo json_encode(array("msg"=>"file details not updated in database"));
			}
		}
		else
		{
			echo json_encode(array("msg"=>"upload failed"));
		}
	}
}
else
{
	echo json_encode(array("msg"=>"file size too large please purchase more storage"));
}

?>
