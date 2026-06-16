<?php
require 'vendor/autoload.php';

$client = new Google_Client();
$client->setClientId("240796627985-takdbulg4b2meq4b0a24k8oakramv3ss.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost/compilex/google-callback.php");

$client->addScope("email");
$client->addScope("profile");

header("Location: " . $client->createAuthUrl());
exit();
?>