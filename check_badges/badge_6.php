<?php

if (!isset($conn)) {
    include 'dbconn.php';
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    return;
}

$userId = $_SESSION['user_id'];
$badgeId = 'B006';

/* Check if user already has B006 */
$stmt = $conn->prepare("
    SELECT ub_id
    FROM users_badges
    WHERE user_id = ?
    AND badges_id = ?
    LIMIT 1
");
$stmt->bind_param("is", $userId, $badgeId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt->close();
    return;
}

$stmt->close();

/* Check if user completed Chapter 1, 2, 3, and 4 */
$stmt = $conn->prepare("
    SELECT COUNT(DISTINCT prog_id) AS chapters_done
    FROM users_progress
    WHERE user_id = ?
    AND status = 'Completed'
    AND percentage = 100
    AND prog_id IN (101, 102, 103, 104)
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$chaptersDone = (int)$row['chapters_done'];
$stmt->close();

if ($chaptersDone < 4) {
    return;
}

/* Generate next ub_id like UB001, UB002, UB003 */
$result = $conn->query("
    SELECT ub_id
    FROM users_badges
    ORDER BY CAST(SUBSTRING(ub_id, 3) AS UNSIGNED) DESC
    LIMIT 1
");

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lastNumber = (int) substr($row['ub_id'], 2);
    $nextNumber = $lastNumber + 1;
} else {
    $nextNumber = 1;
}

$ubId = 'UB' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
$dateEarned = date('Y-m-d');

/* Insert new badge */
$stmt = $conn->prepare("
    INSERT INTO users_badges (ub_id, user_id, badges_id, date_earned)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("siss", $ubId, $userId, $badgeId, $dateEarned);
$stmt->execute();
$stmt->close();

?>