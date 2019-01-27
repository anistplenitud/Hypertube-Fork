<?php

require_once "Facebook/autoload.php";
require_once "GoogleAPI/vendor/autoload.php";
session_start();

$fb = new \Facebook\Facebook([

    'app_id' => '742179166163818',
    'app_secret' => '96d3e5357aedaeb473ce6278aac3ab90',
    'default_graph_version' => 'v3.2'
]);

$helper = $fb->getRedirectLoginHelper();

$client = new Google_Client();
$client->setClientId("32577828744-vhgomgap61350g8m8m7g3fitsd7qbjig.apps.googleusercontent.com");
$client->setClientSecret("d_lXbGc2QrK_0Ue7VGrnZYUm");
$client->setApplicationName("Hypertube");
$client->setRedirectUri("http://localhost:8080/Hypertube/google-callback.php");
$client->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");
?>