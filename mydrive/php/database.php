<?php

$db = new mysqli("localhost","root","","mydrive");

if($db->connect_error)
{
	die("connection not establish");
}

?>