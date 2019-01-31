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


  if (isset($_POST['email1']) && isset($_POST['email2']))
  {
	$email = trim($_POST['email1']);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		echo "<script type='text/javascript'>alert('Please use a valid email addresss');</script>";
		exit ;
	}
	  $oldemail = $_POST['oldemail'];
	$query = $db->prepare("SELECT * FROM users WHERE email = :name");
	$query->bindParam(':name', $email);
	$query->execute();
	if ($query->rowcount() > 0)
	{
		echo "<script type='text/javascript'>alert('Email already has an account');</script>";
		exit;
	  }
	  ###### updates ##########################
	  $query = "UPDATE users set verified = :zero where email = :old";
	  $zero = 0;
	  $line = $db->prepare($query);
	  $line->bindParam(':zero', $zero);
	  $line->bindParam(':old', $oldemail);
	  $line->execute();
	  $verificationCode = md5(uniqid("something", true));
	$verificationLink = "http://localhost:8080/matcha/login.php?code=" . $verificationCode;
	$htmlStr = "";
	$htmlStr .= "Hi " . $email . ",<br /><br />";
	$htmlStr .= "Please click the button below to verify your subscription and have access to the Matcha website.<br /><br /><br />";
	$htmlStr .= "<a href='{$verificationLink}' target='_blank' style='padding:1em; font-weight:bold; background-color:blue; color:#fff;'>VERIFY EMAIL</a><br /><br /><br />";
	$htmlStr .= "Kind regards,<br />";
	$htmlStr .= "<a href='http://localhost:8080/matcha/' target='_blank'>Matcha</a><br />";
	$name = "Matcha";
	$email_sender = "no-reply@matcha.com";
	$subject = "Verification Link | Matcha | Registration";
	$recipient_email = $email;
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: {$name} <{$email_sender}> \n";
	$body = $htmlStr;
	if (mail($recipient_email, $subject, $body, $headers) )
		  echo "<script type='text/javascript'>alert('An email has been sent to you, click on it to verify your account and log in');</script>";
	$table = "users";
	$sql = "UPDATE users set email = :newemail, token = :token where email = :old";
	  $stmt= $db->prepare($sql);
	$stmt->bindParam(':newemail', $email);
	  $stmt->bindParam(':token', $verificationCode);
	  $stmt->bindParam(':old', $oldemail);
	$stmt->execute();
	  ####################end here ############################
	  if ($line->execute())
	   {
		   header("Location: logout.php");
	   }
	  else
	  {
		  echo "<script type='text/javascript'>alert('Email already has an account');</script>";
		  exit;
	  }
  }
?>