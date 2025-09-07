<?php
session_start();
$userEmail = $_SESSION['user'];

$id = $_POST['i'];
$folder = $_POST['fold'];
$file = $_POST['fil'];
require("database.php");

if(unlink("../data/".$folder."/".$file))
{
	if($db->query("DELETE FROM $folder WHERE id='$id'"))
	{
		$response = $db->query("SELECT sum(file_size) AS uds FROM $folder");
		$aa = $response->fetch_assoc();
		$newStorage = round($aa['uds'],2);

		if($db->query("UPDATE users SET used_storage = '$newStorage' WHERE email = '$userEmail'"))
		{
			//send in array formate
			echo json_encode(array("msg"=>"file deleted successfully","newFileSize"=>$newStorage));
		}
		else{
			echo json_encode(array("msg"=>"storage not updated"));
		}	
	}
	else{
		echo json_encode(array("msg"=>"failed"));
	}
}
else
{
	echo json_encode(array("msg"=>"file not deleted"));
}

?>