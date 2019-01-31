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

if (isset($_POST['newuser']) && isset($_POST['newuser2']))
{
	if ($_POST['newuser'] == $_POST['newuser2'])
	{
		$newuser = $_POST['newuser'];
		$query = "SELECT id FROM users WHERE username = ?";
		$stmt = $db->prepare( $query );
		$stmt->bindParam(1, $username);
		$stmt->execute();
		$num = $stmt->rowCount();
		if ($num > 0)
		{
		  $query = "UPDATE users set username = :user where username = :old";
		  $line = $db->prepare($query);
		  $line->bindParam(':user', $newuser);
		  $line->bindParam(':old', $username);
		  $line->execute();
		  $_SESSION['username'] = $username;
		}
	}
	else
	{
		echo "<script type='text/javascript'>alert('Passwords do not match');</script>";
		exit;
	}
}
else
{
}

?>