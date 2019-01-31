<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once("setup.php");

$db->query("USE ".$dbname);
$db->exec("USE hypertube");
$query = $db->prepare("SELECT * FROM users WHERE id = :id");
$query->bindParam(":id", $_SESSION['id']);
$query->execute();
$data = $query->fetch(PDO::FETCH_ASSOC);
$username = $data['username'];
$oauth = $data['oauth'];
$pp = $data['picture'];

if (isset($_FILES["file"]["name"]))
{
	$allowTypes = array('jpg','png','jpeg');
	$fileName = basename($_FILES["file"]["name"]);
	$targetDir = "images/";
	$targetFilePath = $targetDir . $fileName;
	$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
	
	if (in_array($fileType, $allowTypes))
	{
		move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath);
		$query = "UPDATE users set picture = :photo where id = :id";
		$line = $db->prepare($query);
		$line->bindParam(':photo', $targetFilePath);
		$line->bindParam(':id', $_SESSION['id']);
		$line->execute();
	}
}

?>