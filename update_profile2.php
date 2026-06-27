<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $selectedImage = $_POST['profile_image'] ?? '';

    $allowedImages = [
        'image_1.png',
        'image_2.png',
        'image_3.png',
        'image_4.png',
        'image_5.png',
        'image_6.png'
    ];

    if (in_array($selectedImage, $allowedImages, true)) {
        $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE user_id = ?");
        $stmt->bind_param("si", $selectedImage, $userId);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: profile.php");
exit();
?>