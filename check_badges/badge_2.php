<?php
/*
    Badge B001 condition:
    User must answer all Easy quizzes from Chapter 1 to Chapter 4.

    Required tables:
    leaderboard:
        board_chap
        quiz_type
        user_id

    users_badges:
        ub_id
        user_id
        badges_id
        date_earned
*/

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
$badgeId = 'B002';

/* Check if user already has B001 */
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

/* Check if user answered Easy quiz for Chapter 1, 2, 3, and 4 */
$stmt = $conn->prepare("
    SELECT COUNT(DISTINCT board_chap) AS normal_done
    FROM leaderboard
    WHERE user_id = ?
    AND LOWER(quiz_type) = 'normal'
    AND board_chap IN (1, 2, 3, 4)
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$normalDone = (int)$row['normal_done'];
$stmt->close();

if ($normalDone < 4) {
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