<?php
session_start();

include 'dbconn.php';

$username = $_POST['username'];
$password = $_POST['password'];
$_SESSION['username']=$username;

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {

        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['user_id'];

        header("Location: /compilex/index?login=success");
        exit();

    } else {
        header("Location: /compilex/index?error=1");
        exit();
    }

} else {
    header("Location: /compilex/index?error=1");
    exit();
}

$stmt->close();
$conn->close();
?>