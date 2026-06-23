<?php
/*
    Badge B001:
    User must complete every Easy quiz:
    Q001, Q004, Q007 and Q010.
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

$userId = (int)$_SESSION['user_id'];
$badgeId = 'B001';
$badgeAwarded = false;
$earnedBadge = null;

/* Check whether the user already owns B001 */
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

/*
 * Check Easy quizzes completed for Chapters 1-4.
 *
 * leaderboard contains quiz_id.
 * quiz contains quiz_chap and quiz_type.
 */
$stmt = $conn->prepare("
    SELECT COUNT(DISTINCT q.quiz_chap) AS easy_done
    FROM leaderboard AS l
    INNER JOIN quiz AS q
        ON q.quiz_id = l.quiz_id
    WHERE l.user_id = ?
      AND LOWER(q.quiz_type) = 'easy'
      AND q.quiz_chap IN (1, 2, 3, 4)
");

$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$easyDone = (int)($row['easy_done'] ?? 0);

$stmt->close();

if ($easyDone < 4) {
    return;
}

/* Generate the next ID: UB001, UB002, UB003... */
$result = $conn->query("
    SELECT ub_id
    FROM users_badges
    WHERE ub_id REGEXP '^UB[0-9]+$'
    ORDER BY CAST(SUBSTRING(ub_id, 3) AS UNSIGNED) DESC
    LIMIT 1
");

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nextNumber = (int)substr($row['ub_id'], 2) + 1;
} else {
    $nextNumber = 1;
}

$ubId = 'UB' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

/* Award badge B001 */
$stmt = $conn->prepare("
    INSERT INTO users_badges
        (ub_id, user_id, badges_id, date_earned)
    VALUES (?, ?, ?, NOW())
");

$stmt->bind_param("sis", $ubId, $userId, $badgeId);

$badgeAwarded = $stmt->execute();
$stmt->close();

if ($badgeAwarded) {
    $stmt = $conn->prepare("
        SELECT badges_name, description, icon
        FROM badges
        WHERE badges_id = ?
        LIMIT 1
    ");

    $stmt->bind_param("s", $badgeId);
    $stmt->execute();

    $result = $stmt->get_result();
    $earnedBadge = $result->fetch_assoc();

    $stmt->close();
}
?>

<?php if ($badgeAwarded && $earnedBadge): ?>
    <div class="badge-popup-backdrop" id="badgePopup">
        <div class="badge-popup" role="dialog" aria-modal="true" aria-labelledby="badgePopupHeading">
            <h2 id="badgePopupHeading">Congrats you earned a new badges</h2>

            <img
                src="badges/<?= htmlspecialchars($earnedBadge['icon']) ?>"
                alt="<?= htmlspecialchars($earnedBadge['badges_name']) ?>">

            <p class="badge-popup-title">
                <?= htmlspecialchars($earnedBadge['badges_name']) ?>
            </p>

            <p class="badge-popup-desc">
                <?= htmlspecialchars($earnedBadge['description']) ?>
            </p>

            <button type="button" onclick="closeBadgePopup()">OK</button>
        </div>
    </div>

    <style>
        .badge-popup-backdrop {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(15, 23, 42, 0.55);
            z-index: 9999;
            padding: 20px;
        }

        .badge-popup {
            width: min(360px, 100%);
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.25);
            padding: 28px 24px 24px;
            text-align: center;
            animation: badgePop 0.22s ease-out;
        }

        .badge-popup img {
            width: 96px;
            height: 96px;
            object-fit: contain;
            margin-bottom: 16px;
        }

        .badge-popup h2 {
            margin: 0 0 8px;
            font-size: 22px;
            color: #111827;
        }

        .badge-popup-title {
            margin: 0 0 8px;
            font-size: 16px;
            font-weight: 700;
            color: #4f46e5;
        }

        .badge-popup-desc {
            margin: 0 0 22px;
            font-size: 14px;
            line-height: 1.5;
            color: #6b7280;
        }

        .badge-popup button {
            border: 0;
            border-radius: 999px;
            background: #4f46e5;
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            padding: 10px 28px;
            cursor: pointer;
        }

        .badge-popup button:hover {
            background: #4338ca;
        }

        @keyframes badgePop {
            from {
                opacity: 0;
                transform: translateY(10px) scale(0.96);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>

    <script>
        function closeBadgePopup() {
            const popup = document.getElementById('badgePopup');

            if (popup) {
                popup.remove();
            }
        }
    </script>
<?php endif; ?>