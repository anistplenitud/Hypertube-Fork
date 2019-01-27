<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "config.php";
require_once "setup.php";

$accesstoken = $helper->getAccessToken();

if (!$accesstoken)
{
    header ('Location : login.php');
    exit();
}
$oauth2client = $fb->getOAuth2Client();
if (!$accesstoken->isLongLived())
    $accesstoken = $oauth2client->getLongLivedAccessToken($accesstoken);
$response = $fb->get("/me?fields=id,first_name, last_name, gender, email, picture.type(large)", $accesstoken);
$userinfo = $response->getGraphNode()->asArray();
echo "<pre>";
var_dump($userinfo);
$fb_id = $userinfo["id"];
$first_name = $userinfo["first_name"];
$last_name = $userinfo["last_name"];
$picture = $userinfo["picture"]['url'];
$email = "default";

$_SESSION["first_name"] = $first_name;
$_SESSION["last_name"] = $last_name;
$_SESSION["picture"] = $picture;
$_SESSION["email"] = $email;

$password = "default";
$verificationCode = "default";

$query = $db->prepare("SELECT id FROM users WHERE fb_id = :fb");
$query->bindParam(':fb', $fb_id);
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
    $sql = "INSERT INTO users (name, surname, email, username, password, token, fb_id, picture) VALUES (:first_name, :last_name, :email, :username, :passwd, :token, :fb_id, :picture)";
    $coolpwd = hash('whirlpool', $password);
    $stmt= $db->prepare($sql);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam('last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':username', $first_name);
    $stmt->bindParam(':passwd', $coolpwd);
    $stmt->bindParam(':token', $verificationCode);
    $stmt->bindParam(':fb_id', $fb_id);
    $stmt->bindParam(':picture', $picture);
    $stmt->execute();
    $query = $db->prepare("SELECT id FROM users WHERE fb_id = :fb");
    $query->bindParam(':fb', $fb_id);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $user_id = $row['id'];
    $_SESSION["logged_in"] = true;
    $_SESSION['id'] = $user_id;
    header ('Location: home.php');
}
?>