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

if (isset($_POST['passwd']) && isset($_POST['passwd2']))
{
  if ($_POST['passwd'] && $_POST['passwd2'])
  {
	 if ($_POST['passwd'] != $_POST['passwd2'])
	 {
	  echo "<script type='text/javascript'>alert('Password does not match');</script>";
	  exit;
	  }
	$password = $_POST['oldpasswd'];
	$newpwd = $_POST['passwd'];
	if (strlen($password) < 8)
	{
	  echo "<script type='text/javascript'>alert('Password must be at least characters long');</script>";
	  exit;
	}
	if (!preg_match("#[0-9]+#", $password))
	{
	  echo "<script type='text/javascript'>alert('Password must include at least one number');</script>";
	  exit;
	}
	if (!preg_match("#[a-zA-Z]+#", $password))
	{
		  echo "<script type='text/javascript'>alert('Password must include at least one letter');</script>";
	  exit;
	}
	$query = "SELECT id FROM users WHERE password = ?";
	$pwd = hash('whirlpool', $password);
	$stmt = $db->prepare( $query );
	$stmt->bindParam(1, $pwd);
	$stmt->execute();
	$num = $stmt->rowCount();
	if ($num > 0)
	{
	  $newpwd = hash('whirlpool', $newpwd);
	  $query = "UPDATE users set password = :pwd where password = :old";
	  $line = $db->prepare($query);
	  $line->bindParam(':pwd', $newpwd);
	  $line->bindParam(':old', $pwd);
	  if ($line->execute())
		  echo "Password successfully changed.";
	}
	else
	  echo "You entered a wrong old password";
  }
}
else
{}

?>