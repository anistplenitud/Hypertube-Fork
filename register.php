<?php
require_once("setup.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dbname = "hypertube";
$db->query("USE ".$dbname);
if ($_POST['email'] && $_POST['username'] && $_POST['password'] && $_POST['password2'])
{
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	if ($_POST['password'] != $_POST['password2'])
	{
		header("Location: index.php");
		echo "<script type='text/javascript'>alert('Password does not match');</script>";
		exit;
	}
	$user = $_POST['username'];
	$email = trim($_POST['email']);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		echo "<script type='text/javascript'>alert('Please use a valid email addresss');</script>";
		exit ;
	}
	$query = $db->prepare("SELECT * FROM users WHERE email = :name");
	$query->bindParam(':name', $email);
	$query->execute();
	if ($query->rowcount() > 0)
	{
		echo "<script type='text/javascript'>alert('Email already has an account');</script>";
		exit;
	}
	$query = $db->prepare("SELECT * FROM users WHERE username = :name");
	$query->bindParam(':name', $user);
	$query->execute();
	if ($query->rowcount() > 0)
	{
		echo "<script type='text/javascript'>alert('Username is already taken');</script>";
		exit;
	}
	$password = $_POST['password'];
	if (strlen($password) < 8)
	{
		echo "<script type='text/javascript'>alert('Password must be at least 8 characters long');</script>";
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
	$verificationCode = md5(uniqid("something", true));
	$verificationLink = "http://localhost:8080/hypertube/index.php?code=" . $verificationCode;
	$htmlStr = "";
	$htmlStr .= "Hi " . $email . ",<br /><br />";
	$htmlStr .= "Please click the button below to verify your account and have access to the Hypertube website.<br /><br /><br />";
	$htmlStr .= "<a href='{$verificationLink}' target='_blank' style='padding:1em; font-weight:bold; background-color:blue; color:#fff;'>VERIFY EMAIL</a><br /><br /><br />";
	$htmlStr .= "Kind regards,<br />";
	$htmlStr .= "<a href='http://localhost:8080/hypertube/' target='_blank'>Hypertube</a><br />";
	$name = "Hypertube";
	$email_sender = "no-reply@hypertube.com";
	$subject = "Verification Link | Hypertube | Registration";
	$recipient_email = $email;
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: {$name} <{$email_sender}> \n";
	$body = $htmlStr;
	if (mail($recipient_email, $subject, $body, $headers) )
		echo "<div id='successMessage'>A verification email was sent to <b>" . $email . "</b>, please open your email inbox and click the given link so you can login.</div>";
	$table = "users";
	$picture = "images/default.png";
	$sql = "INSERT INTO users (name, surname, email, username, password, token, picture) VALUES (:first_name, :last_name, :email, :username, :passwd, :token, :picture)";
	$coolpwd = hash('whirlpool', $password);
	$noti = "off";
	$stmt= $db->prepare($sql);
	$stmt->bindParam(':first_name', $first_name);
	$stmt->bindParam('last_name', $last_name);
	$stmt->bindParam(':email', $email);
	$stmt->bindParam(':username', $user);
	$stmt->bindParam(':passwd', $coolpwd);
	$stmt->bindParam(':token', $verificationCode);
	$stmt->bindParam(':picture', $picture);
	$stmt->execute();
	header('Location: index.php?email=yes');
}
?>