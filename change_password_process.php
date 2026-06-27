<?php
session_start();

function returnWithError(string $message): void
{
    header('Location: change_password.php?error=' . urlencode($message));
    exit;
}

if (!isset($_SESSION['user_id'], $_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if (
    $_SERVER['REQUEST_METHOD'] !== 'POST'
    || !isset($_POST['new_password'], $_POST['confirm_password'])
) {
    returnWithError('Please submit the change password form.');
}

$newPassword = $_POST['new_password'];
$confirmPassword = $_POST['confirm_password'];
$userId = (int) $_SESSION['user_id'];

if ($newPassword === '' || $confirmPassword === '') {
    returnWithError('Both password fields are required.');
}

if (strlen($newPassword) < 8) {
    returnWithError('Password must be at least 8 characters.');
}

if ($newPassword !== $confirmPassword) {
    returnWithError('Passwords do not match.');
}

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'compilex';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    returnWithError('Database connection failed.');
}

$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
$stmt = $conn->prepare(
    'UPDATE users SET password = ? WHERE user_id = ?'
);

if ($stmt === false) {
    $conn->close();
    returnWithError('Could not prepare the password update.');
}

$stmt->bind_param('si', $hashedPassword, $userId);

if (!$stmt->execute()) {
    $stmt->close();
    $conn->close();
    returnWithError('Password update failed.');
}

if ($stmt->affected_rows < 1) {
    $stmt->close();
    $conn->close();
    returnWithError('Password update failed: user was not found.');
}

$stmt->close();
$conn->close();

header('Location: profile.php');
exit;
?>
