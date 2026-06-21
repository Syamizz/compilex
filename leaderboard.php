<?php
session_start();
include 'dbconn.php';

$leaderboards = [];

$stmt = $conn->prepare("
    SELECT
        l.board_chap,
        u.username,
        MAX(l.scorepoint) AS scorepoint
    FROM leaderboard l
    INNER JOIN users u
        ON l.user_id = u.user_id
    GROUP BY l.board_chap, l.user_id, u.username
    ORDER BY l.board_chap ASC, scorepoint DESC
");

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $chapter = (int)$row['board_chap'];

    $leaderboards[$chapter][] = [
        'username' => $row['username'],
        'scorepoint' => (int)$row['scorepoint'],
    ];
}

$stmt->close();

function rankLabel(int $rank): string
{
    if ($rank === 1) return '🥇 1';
    if ($rank === 2) return '🥈 2';
    if ($rank === 3) return '🥉 3';

    return (string)$rank;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CompileX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="css/body.css">
    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+NZ+Basic:wght@100..400&display=swap" rel="stylesheet">

    <style>
        /* === LEADERBOARD REDESIGN === */
        @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap');

        body {
            font-family: 'Syne', sans-serif;
            background: #F5F4F2;
        }

        .section {
            max-width: 1050px;
            margin: auto;
            padding: 50px 20px;
        }

        .section h2 {
            text-align: left;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #534AB7;
            margin-bottom: 12px;
            font-family: 'JetBrains Mono', monospace;
        }

        /* Card */
        .card.leaderboard-card {
            padding: 0;
            border: 0.5px solid #D3D1C7;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: none;
            background: #fff;
        }

        /* Table */
        .leaderboard-table {
            width: 100%;
            border-collapse: collapse;
        }

        .leaderboard-table thead tr {
            background: #F1EFE8;
            border-bottom: 0.5px solid #D3D1C7;
        }

        .leaderboard-table th {
            background: transparent;
            color: #888780;
            padding: 10px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-family: 'JetBrains Mono', monospace;
        }

        .leaderboard-table td {
            padding: 11px 16px;
            text-align: left;
            border-bottom: 0.5px solid #F1EFE8;
            font-size: 14px;
            color: #2C2C2A;
        }

        .leaderboard-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Top rows subtle highlight */
        .leaderboard-table tbody tr:nth-child(1) {
            background: linear-gradient(90deg, rgba(255, 215, 0, 0.06) 0%, transparent 70%);
        }

        .leaderboard-table tbody tr:nth-child(2) {
            background: linear-gradient(90deg, rgba(180, 180, 180, 0.06) 0%, transparent 70%);
        }

        .leaderboard-table tbody tr:nth-child(3) {
            background: linear-gradient(90deg, rgba(205, 127, 50, 0.06) 0%, transparent 70%);
        }

        .leaderboard-table tbody tr:hover {
            background: #F5F4F2;
        }

        /* Rank */
        .rank-1 {
            color: #c9920b;
            font-size: 18px;
        }

        .rank-2 {
            color: #888780;
            font-size: 18px;
        }

        .rank-3 {
            color: #a0541e;
            font-size: 18px;
        }

        /* Score column */
        .leaderboard-table td:last-child {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
            color: #534AB7;
        }

        /* Score bar (add this inside <td> for score) */
        .score-bar-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .score-bar-bg {
            flex: 1;
            height: 4px;
            background: #E5E7EB;
            border-radius: 2px;
            overflow: hidden;
            max-width: 80px;
        }

        .score-bar {
            height: 100%;
            border-radius: 2px;
            background: #6366F1;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav id="navbar" class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a id="title" class="navbar-brand" href="#">CompileX</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="home.php">Home</a>
                    </li>
                   
                    <li class="nav-item">
                        <a class="nav-link" href="quiz.php">Quiz</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="leaderboard.php">Leaderboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="profile.php">Profile</a>
                    </li>
                </ul>
            </div>
            <div class="collapse navbar-collapse">
                <div class="ms-auto">
                    <a href="logout.php" class="button-28">LOGOUT</a>
                </div>
            </div>
        </div>
    </nav>



   <?php for ($section = 1; $section <= 2; $section++): ?>
    <div class="section">
        <div class="row">
            <?php
            $startChapter = ($section - 1) * 2 + 1;
            $endChapter = $startChapter + 1;
            ?>

            <?php for ($chapter = $startChapter; $chapter <= $endChapter; $chapter++): ?>
                <div class="col-md-6">
                    <h2>Quiz Chapter <?= $chapter ?></h2>

                    <div class="card leaderboard-card">
                        <table class="leaderboard-table">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Username</th>
                                    <th>Score Points</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (!empty($leaderboards[$chapter])): ?>
                                    <?php foreach ($leaderboards[$chapter] as $index => $entry): ?>
                                        <?php
                                        $rank = $index + 1;
                                        $rankClass = $rank <= 3 ? 'rank-' . $rank : '';
                                        ?>
                                        <tr>
                                            <td class="<?= $rankClass ?>">
                                                <?= htmlspecialchars(rankLabel($rank)) ?>
                                            </td>
                                            <td><?= htmlspecialchars($entry['username']) ?></td>
                                            <td><?= htmlspecialchars((string)$entry['scorepoint']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3">No scores yet</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
<?php endfor; ?>

    <footer>
        © <?php echo date("Y"); ?> ezComp • Compiler Learning Platform
    </footer>

</body>

</html>