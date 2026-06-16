<?php
session_start();
include 'dbconn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_pic'])) {
    $username = $_SESSION['username'];
    $file = $_FILES['profile_pic'];

    // 1. File Details
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileError = $file['error'];
    
    // 2. Extract Extension and create a unique name
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = array('jpg', 'jpeg', 'png');

    if (in_array($fileExt, $allowed)) {
        if ($fileError === 0) {
            // Create a unique name to prevent overwriting (e.g., user1_164938.jpg)
            $newFileName = $username . "_" . time() . "." . $fileExt;
            $fileDestination = 'images/' . $newFileName;

            // 3. Move file to folder
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                
                // 4. Update Database
                $sql = "UPDATE users SET profile_image = ? WHERE username = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $newFileName, $username);
                
                if ($stmt->execute()) {
                    header("Location: profile.php?upload=success");
                } else {
                    echo "Database update failed.";
                }
            } else {
                echo "Failed to move uploaded file.";
            }
        } else {
            echo "There was an error uploading your file.";
        }
    } else {
        echo "Invalid file type. Only JPG, JPEG, and PNG allowed.";
    }
}
?>