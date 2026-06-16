<?php
require 'vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setClientId("240796627985-takdbulg4b2meq4b0a24k8oakramv3ss.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost/compilex/google-callback.php");

if (isset($_GET['code'])) {

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    $google_service = new Google_Service_Oauth2($client);
    $data = $google_service->userinfo->get();

    $_SESSION['user_email'] = $data->email;
    $_SESSION['user_name'] = $data->name;
    $_SESSION['user_image'] = $data->picture;

    header("Location: home.php");
    exit();
}
?>