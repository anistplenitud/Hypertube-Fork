<?php

require_once "config.php";
require_once "setup.php";
require_once("setup.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (isset($_GET['code']))
{
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['token'] = $token;
}
else
    header ('Location: login.php');

$oauth = new Google_Service_Oauth2($client);
$userinfo = $oauth->userinfo_v2_me->get();

echo "<pre>";
var_dump($userinfo);
$first_name = $userinfo["givenName"];
$last_name = $userinfo["familyName"];
$picture = $userinfo["picture"];
$email = $userinfo["email"];
$verificationCode = "default";
$password = "default";

$query = $db->prepare("SELECT id FROM users WHERE email = :email");
$query->bindParam(':email', $email);
$query->execute();
$num = $query->rowCount();
if ($num > 0)
{
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $user_id = $row['id'];
    $_SESSION["logged_in"] = true;
    $_SESSION['id'] = $user_id;
    header ('Location: home.php');
}
else
{
    $sql = "INSERT INTO users (name, surname, email, username, password, token, picture, oauth) VALUES (:first_name, :last_name, :email, :username, :passwd, :token, :picture, :oauth)";
    $coolpwd = hash('whirlpool', $password);
    $code = rand(100000, 199999);
    $username = "user" . $code;
    $oauth = 1;
    $stmt= $db->prepare($sql);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam('last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':passwd', $coolpwd);
    $stmt->bindParam(':token', $verificationCode);
    $stmt->bindParam(':picture', $picture);
    $stmt->bindParam(':oauth', $oauth);
    $stmt->execute();
    $query = $db->prepare("SELECT id FROM users WHERE email = :email");
    $query->bindParam(':email', $email);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $user_id = $row['id'];
    $_SESSION["logged_in"] = true;
    $_SESSION['id'] = $user_id;
    header ('Location: home.php');
}
?>