<?php
session_start();
include 'dbconn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];

    // Update the user details
    $sql = "UPDATE users SET fullname = ?, email = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $fullname, $email, $username);

    if ($stmt->execute()) {
        // Redirect back to profile with a success message
        header("Location: profile.php?update=success");
    } else {
        echo "Error updating profile: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>