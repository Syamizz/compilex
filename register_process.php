<?php
include "dbconn.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register");
    exit();
}

$username = trim($_POST['username'] ?? '');
$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirmPassword = trim($_POST['confirm_password'] ?? '');

function goBackWithError($message) {
    header("Location: register?error=" . urlencode($message));
    exit();
}

if ($username == '' || $fullname == '' || $email == '' || $password == '' || $confirmPassword == '') {
    goBackWithError("All fields are required!");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    goBackWithError("Invalid email address!");
}

if (strlen($password) < 6) {
    goBackWithError("Password must be at least 6 characters!");
}

if ($password !== $confirmPassword) {
    goBackWithError("Password does not match!");
}

$checkSql = "SELECT user_id FROM users WHERE username = ? OR email = ?";
$stmt = $conn->prepare($checkSql);
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt->close();
    $conn->close();
    goBackWithError("Username or Email already exists!");
}

$stmt->close();

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, fullname, password, email) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $username, $fullname, $hashedPassword, $email);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();

    header("Location: index");
    exit();
}

$stmt->close();
$conn->close();

goBackWithError("Registration failed. Please try again.");
?>