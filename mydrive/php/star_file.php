<?php
require("database.php");

$id = $_POST['id'];
$status = $_POST['status'];
$tableName = $_POST['table'];

$update = $db->query("UPDATE $tableName SET star = '$status' WHERE id = '$id'");

if($update){
	echo "success";
}
else{
	echo "failed ! unable to update";
}
?>