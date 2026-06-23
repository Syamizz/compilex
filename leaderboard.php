<?php
session_start();
include 'dbconn.php';

$leaderboards = [];

/*
 * Get each user's total best score for every chapter.
 * The inner query prevents duplicate leaderboard records
 * for the same user and quiz from being counted twice.
 */
$stmt = $conn->prepare("
    SELECT
        q.quiz_chap,
        u.user_id,
        u.username,
        SUM(lb.best_score) AS total_score
    FROM (
        SELECT
            user_id,
            quiz_id,
            MAX(best_score) AS best_score
        FROM leaderboard
        GROUP BY user_id, quiz_id
    ) AS lb
    INNER JOIN users AS u
        ON u.user_id = lb.user_id
    INNER JOIN quiz AS q
        ON q.quiz_id = lb.quiz_id
    GROUP BY
        q.quiz_chap,
        u.user_id,
        u.username
    ORDER BY
        q.quiz_chap ASC,
        total_score DESC,
        u.username ASC
");

if (!$stmt) {
    die('Unable to prepare leaderboard: ' . $conn->error);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $chapter = (int)$row['quiz_chap'];

    $leaderboards[$chapter][] = [
        'username' => $row['username'],
        'score'    => (int)$row['total_score'],
    ];
}

$stmt->close();

/* This function was missing and caused your fatal error. */
function rankLabel(int $rank): string
{
    if ($rank === 1) {
        return '🥇 1';
    }

    if ($rank === 2) {
        return '🥈 2';
    }

    if ($rank === 3) {
        return '🥉 3';
    }

    return (string)$rank;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>CompileX Leaderboard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
    </script>

    <link rel="stylesheet" href="css/body.css">

    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap"
    >

    <style>
        body {
            margin: 0;
            padding-top: 70px;
            font-family: 'Syne', sans-serif;
            background: #F5F4F2;
        }

        .leaderboard-container {
            max-width: 1100px;
            margin: auto;
            padding: 40px 20px 80px;
        }

        .chapter-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 32px;
            margin-bottom: 38px;
        }

        .chapter-heading {
            margin-bottom: 12px;
            color: #534AB7;
            font-family: 'JetBrains Mono', monospace;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .leaderboard-card {
            overflow: hidden;
            padding: 0;
            background: #FFFFFF;
            border: 1px solid #D3D1C7;
            border-radius: 14px;
        }

        .leaderboard-table {
            width: 100%;
            border-collapse: collapse;
        }

        .leaderboard-table thead tr {
            background: #F1EFE8;
            border-bottom: 1px solid #D3D1C7;
        }

        .leaderboard-table th {
            padding: 12px 16px;
            color: #888780;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-align: left;
            text-transform: uppercase;
        }

        .leaderboard-table td {
            padding: 13px 16px;
            color: #2C2C2A;
            font-size: 14px;
            text-align: left;
            border-bottom: 1px solid #F1EFE8;
        }

        .leaderboard-table tbody tr:last-child td {
            border-bottom: none;
        }

        .leaderboard-table tbody tr:hover {
            background: #F8F7F4;
        }

        .leaderboard-table tbody tr:nth-child(1) {
            background: linear-gradient(
                90deg,
                rgba(255, 215, 0, 0.09),
                transparent 70%
            );
        }

        .leaderboard-table tbody tr:nth-child(2) {
            background: linear-gradient(
                90deg,
                rgba(180, 180, 180, 0.09),
                transparent 70%
            );
        }

        .leaderboard-table tbody tr:nth-child(3) {
            background: linear-gradient(
                90deg,
                rgba(205, 127, 50, 0.09),
                transparent 70%
            );
        }

        .rank-column {
            width: 90px;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
        }

        .rank-1 {
            color: #C9920B !important;
        }

        .rank-2 {
            color: #777777 !important;
        }

        .rank-3 {
            color: #A0541E !important;
        }

        .score-column {
            width: 130px;
            color: #534AB7 !important;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
        }

        .empty-value {
            color: #AAA7A0;
        }

        footer {
            padding: 24px;
            color: #777;
            font-size: 13px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .chapter-grid {
                grid-template-columns: 1fr;
            }

            .leaderboard-container {
                padding: 30px 14px 60px;
            }

            .leaderboard-table th,
            .leaderboard-table td {
                padding: 11px 12px;
            }
        }
    </style>
</head>

<body>

    <nav
        id="navbar"
        class="navbar navbar-expand-lg fixed-top"
    >
        <div class="container-fluid">
            <a
                id="title"
                class="navbar-brand"
                href="home.php"
            >
                CompileX
            </a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div
                class="collapse navbar-collapse"
                id="navbarNav"
            >
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="quiz.php">
                            Quiz
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="tools/">
                            Tools
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="nav-link active"
                            href="leaderboard.php"
                        >
                            Leaderboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">
                            Profile
                        </a>
                    </li>
                </ul>
            </div>

            <div class="collapse navbar-collapse">
                <div class="ms-auto">
                    <a href="logout.php" class="button-28">
                        LOGOUT
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="leaderboard-container">

        <?php for ($section = 1; $section <= 2; $section++): ?>
            <?php
            $startChapter = (($section - 1) * 2) + 1;
            $endChapter = $startChapter + 1;
            ?>

            <div class="chapter-grid">
                <?php for (
                    $chapter = $startChapter;
                    $chapter <= $endChapter;
                    $chapter++
                ): ?>
                    <section>
                        <h2 class="chapter-heading">
                            Quiz Chapter <?= $chapter ?>
                        </h2>

                        <div class="leaderboard-card">
                            <table class="leaderboard-table">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Username</th>
                                        <th>Score Points</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php for (
                                        $rank = 1;
                                        $rank <= 5;
                                        $rank++
                                    ): ?>
                                        <?php
                                        $entry =
                                            $leaderboards[$chapter][$rank - 1]
                                            ?? null;

                                        $rankClass =
                                            $rank <= 3
                                                ? 'rank-' . $rank
                                                : '';
                                        ?>

                                        <tr>
                                            <td
                                                class="rank-column <?= $rankClass ?>"
                                            >
                                                <?= htmlspecialchars(
                                                    rankLabel($rank)
                                                ) ?>
                                            </td>

                                            <td>
                                                <?php if ($entry): ?>
                                                    <?= htmlspecialchars(
                                                        $entry['username']
                                                    ) ?>
                                                <?php else: ?>
                                                    <span class="empty-value">
                                                        —
                                                    </span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="score-column">
                                                <?php if ($entry): ?>
                                                    <?= htmlspecialchars(
                                                        (string)$entry['score']
                                                    ) ?>
                                                <?php else: ?>
                                                    <span class="empty-value">
                                                        —
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                <?php endfor; ?>
            </div>
        <?php endfor; ?>

    </main>

    <footer>
        © <?= date('Y') ?> CompileX • Compiler Learning Platform
    </footer>

</body>
</html>