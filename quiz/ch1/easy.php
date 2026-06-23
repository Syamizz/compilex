<?php
session_start();
include '../../dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$questions = [
    [
        'q'      => 'A compiler translates a high-level source program into an equivalent machine language (object) program.',
        'answer' => true,
        'explain' => 'A compiler accepts a program written in a high-level language and produces an equivalent program in machine language for a target machine.',
    ],
    [
        'q'      => 'When a compiler processes the statement x = x * 9, it performs the actual multiplication at compile time.',
        'answer' => false,
        'explain' => 'The compiler does NOT perform the multiplication — it generates a sequence of instructions, including a "multiply" instruction, to be executed later at run time.',
    ],
    [
        'q'      => 'The output of an interpreter is a machine language program, just like a compiler.',
        'answer' => false,
        'explain' => 'The output of a compiler is a program, whereas the output of an interpreter is the source program\'s output (the computed result).',
    ],
    [
        'q'      => 'Lexical analysis is the first phase of a compiler, responsible for isolating "words" (tokens) from the input string.',
        'answer' => true,
        'explain' => 'Lexical analysis (the scanner) is the first phase and attempts to isolate the "words" — also called lexemes or tokens — from the input source string.',
    ],
    [
        'q'      => 'Comments in source code are included in the token output produced by the lexical analysis phase.',
        'answer' => false,
        'explain' => 'Comments must be identified by the scanner but are NOT included in the output — they are ignored by subsequent phases.',
    ],
    [
        'q'      => 'The syntax analysis phase (parser) checks for proper syntax and determines the underlying structure of the source program.',
        'answer' => true,
        'explain' => 'The parser checks for proper syntax, issues error messages, and determines the underlying structure, outputting atoms or syntax trees.',
    ],
    [
        'q'      => 'In a syntax tree, each leaf node represents an operation or control structure.',
        'answer' => false,
        'explain' => 'In a syntax tree, each INTERIOR node represents an operation or control structure, while each LEAF node represents an operand.',
    ],
    [
        'q'      => 'Global optimization is a required phase in every compiler.',
        'answer' => false,
        'explain' => 'Global optimization is optional. Its purpose is to make the object program more efficient, and it may be omitted — atoms can be passed directly from the syntax phase to the code generator.',
    ],
    [
        'q'      => 'A single-pass compiler works by having the syntax analysis phase call lexical analysis as a subroutine each time it needs a token.',
        'answer' => true,
        'explain' => 'In a single-pass compiler, the parser calls the lexical analyser as a subroutine whenever it needs a token, and calls the code generator when an atom is ready — all in one pass.',
    ],
    [
        'q'      => 'Local optimization is performed before code generation, making it machine-independent.',
        'answer' => false,
        'explain' => 'LOCAL optimization is performed AFTER code generation (it examines instruction sequences output by the code generator) and is machine-DEPENDENT. It is GLOBAL optimization that is machine-independent.',
    ],
];

$total = count($questions);
$submitted = false;
$correct_answers = 0;
$scorepoint = 0;
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = true;

    foreach ($questions as $i => $q) {
        $key = 'q' . $i;
        $raw = $_POST[$key] ?? null;
        $answered = $raw !== null;
        $userAns = $answered ? ($raw === 'true') : null;
        $correct = $answered && ($userAns === $q['answer']);

        if ($correct) {
            $correct_answers++;
        }

        $results[] = [
            'answered' => $answered,
            'userAns'  => $userAns,
            'correct'  => $correct,
        ];
    }


    $scorepoint = $correct_answers * 100;

    // Chapter 1 Easy quiz is stored in the quiz table as Q001.
    $quiz_id = 'Q001';

    // Generate the next attempt ID in the format QA001, QA002, QA003, ...
    $result = $conn->query("
        SELECT qa_id
        FROM quiz_attempt
        WHERE qa_id REGEXP '^QA[0-9]+$'
        ORDER BY CAST(SUBSTRING(qa_id, 3) AS UNSIGNED) DESC
        LIMIT 1
");

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_number = (int) substr($row['qa_id'], 2);
        $next_number = $last_number + 1;
    } else {
        $next_number = 1;
    }

    $qa_id = 'QA' . str_pad($next_number, 3, '0', STR_PAD_LEFT);

    $stmt = $conn->prepare("
        INSERT INTO quiz_attempt (qa_id, user_id, quiz_id, scorepoint, answered_at)
        VALUES (?, ?, ?, ?, NOW())
");

    if (!$stmt) {
        die('Unable to prepare quiz attempt: ' . $conn->error);
    }

    $stmt->bind_param("sisi", $qa_id, $user_id, $quiz_id, $scorepoint);

    if (!$stmt->execute()) {
        die('Unable to save quiz attempt: ' . $stmt->error);
    }

    $stmt->close();
}

$score = $correct_answers;
$pct = $submitted ? round($score / $total * 100) : 0;

// Check whether this user already has a leaderboard record for Q001.
$leaderboardStmt = $conn->prepare("
    SELECT board_id, best_score
    FROM leaderboard
    WHERE user_id = ?
      AND quiz_id = 'Q001'
    LIMIT 1
");

$leaderboardStmt->bind_param("i", $user_id);
$leaderboardStmt->execute();
$leaderboardResult = $leaderboardStmt->get_result();

if ($leaderboardResult->num_rows > 0) {
    $leaderboardRow = $leaderboardResult->fetch_assoc();
    $currentBestScore = (int)$leaderboardRow['best_score'];

    // Update only when the new score is higher.
    if ($scorepoint > $currentBestScore) {
        $updateStmt = $conn->prepare("
            UPDATE leaderboard
            SET best_score = ?, updated_at = NOW()
            WHERE board_id = ?
        ");

        $updateStmt->bind_param(
            "is",
            $scorepoint,
            $leaderboardRow['board_id']
        );

        $updateStmt->execute();
        $updateStmt->close();
    }
} else {
    // Generate the next board ID: BD1, BD2, BD3, ...
    $boardResult = $conn->query("
        SELECT board_id
        FROM leaderboard
        WHERE board_id REGEXP '^BD[0-9]+$'
        ORDER BY CAST(SUBSTRING(board_id, 3) AS UNSIGNED) DESC
        LIMIT 1
    ");

    if ($boardResult && $boardResult->num_rows > 0) {
        $boardRow = $boardResult->fetch_assoc();
        $nextBoardNumber =
            (int)substr($boardRow['board_id'], 2) + 1;
    } else {
        $nextBoardNumber = 1;
    }

    $board_id = 'BD' . $nextBoardNumber;

    $insertStmt = $conn->prepare("
    INSERT INTO leaderboard
        (board_id, user_id, quiz_id, best_score, updated_at)
    VALUES (?, ?, 'Q001', ?, NOW())
");

$insertStmt->bind_param(
    "sii",
    $board_id,
    $user_id,
    $scorepoint
);

$insertStmt->execute();
$insertStmt->close();
}

$leaderboardStmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter 1 – Easy Quiz</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../css/body.css">

    <style>
        :root {
            --indigo: #6366F1;
            --indigo-d: #4F46E5;
            --indigo-s: #EEF2FF;
            --green: #10B981;
            --green-s: #D1FAE5;
            --green-t: #065F46;
            --red: #EF4444;
            --red-s: #FEE2E2;
            --red-t: #991B1B;
            --radius: 14px;
            --text: #1E1B4B;
            --muted: #6B7280;
        }

        body {
            background: #F8F7FF;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ── Page header ── */
        .page-header {
            max-width: 780px;
            margin: 0 auto;
            padding: 48px 24px 0;
        }

        .page-header h1 {
            font-size: 30px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .page-header h1 span {
            color: var(--indigo);
        }

        .page-header p {
            color: var(--muted);
            font-size: 14px;
            margin: 0;
        }

        /* ── Quiz form ── */
        .quiz-wrap {
            max-width: 780px;
            margin: 32px auto 80px;
            padding: 0 24px;
        }

        /* ── Question card ── */
        .q-card {
            background: #fff;
            border: 1px solid rgba(99, 102, 241, .13);
            border-radius: var(--radius);
            padding: 24px 26px;
            margin-bottom: 18px;
            box-shadow: 0 2px 12px rgba(99, 102, 241, .06);
            transition: box-shadow .2s;
        }

        .q-card:hover {
            box-shadow: 0 6px 24px rgba(99, 102, 241, .12);
        }

        /* Number badge */
        .q-num {
            display: inline-block;
            background: var(--indigo-s);
            color: var(--indigo);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .q-text {
            font-size: 15px;
            font-weight: 600;
            color: var(--text);
            line-height: 1.55;
            margin-bottom: 16px;
        }

        /* True / False toggle buttons */
        .tf-group {
            display: flex;
            gap: 10px;
        }

        .tf-group label {
            flex: 1;
            cursor: pointer;
        }

        .tf-group input[type="radio"] {
            display: none;
        }

        .tf-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 10px 0;
            border-radius: 10px;
            border: 1.5px solid #E5E7EB;
            font-size: 14px;
            font-weight: 600;
            color: var(--muted);
            background: #F9FAFB;
            transition: all .18s;
            user-select: none;
        }

        .tf-group input:checked+.tf-btn.true-btn {
            background: var(--green-s);
            border-color: var(--green);
            color: var(--green-t);
        }

        .tf-group input:checked+.tf-btn.false-btn {
            background: var(--red-s);
            border-color: var(--red);
            color: var(--red-t);
        }

        .tf-btn:hover {
            border-color: #C7C7D9;
            background: #F3F4F6;
        }

        /* Result states (after submit) */
        .q-card.correct {
            border-color: var(--green);
            background: #F0FDF4;
        }

        .q-card.wrong {
            border-color: var(--red);
            background: #FFF5F5;
        }

        .q-card.skipped {
            border-color: #E5E7EB;
            background: #F9FAFB;
        }

        /* Result badge */
        .result-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: 8px;
        }

        .badge-correct {
            background: var(--green-s);
            color: var(--green-t);
        }

        .badge-wrong {
            background: var(--red-s);
            color: var(--red-t);
        }

        .badge-skipped {
            background: #F3F4F6;
            color: #6B7280;
        }

        /* Explanation box */
        .explain-box {
            margin-top: 12px;
            padding: 12px 14px;
            background: rgba(99, 102, 241, .06);
            border-left: 3px solid var(--indigo);
            border-radius: 0 8px 8px 0;
            font-size: 13px;
            color: #374151;
            line-height: 1.6;
        }

        /* Locked buttons after submit */
        .submitted .tf-group label {
            pointer-events: none;
        }

        /* ── Submit button ── */
        .submit-wrap {
            text-align: center;
            margin-top: 10px;
        }

        .btn-submit {
            background: var(--indigo);
            color: #fff;
            border: none;
            padding: 14px 48px;
            font-size: 16px;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            transition: background .2s, transform .15s;
            letter-spacing: .3px;
        }

        .btn-submit:hover {
            background: var(--indigo-d);
            transform: translateY(-2px);
        }

        /* ── Score card ── */
        .score-card {
            background: #fff;
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            padding: 30px;
            text-align: center;
            margin-bottom: 28px;
            box-shadow: 0 4px 20px rgba(99, 102, 241, .10);
        }

        .score-big {
            font-size: 52px;
            font-weight: 800;
            color: var(--indigo);
            line-height: 1;
        }

        .score-sub {
            color: var(--muted);
            font-size: 14px;
            margin-top: 4px;
        }

        .score-bar-wrap {
            margin: 18px 0 6px;
        }

        .score-bar {
            height: 10px;
            background: #E8E8F0;
            border-radius: 20px;
            overflow: hidden;
        }

        .score-bar-fill {
            height: 100%;
            border-radius: 20px;
            background: linear-gradient(90deg, var(--indigo), #818CF8);
            transition: width 1s ease;
        }

        .score-msg {
            font-size: 15px;
            font-weight: 600;
            color: var(--text);
            margin-top: 10px;
        }

        .btn-retry {
            display: inline-block;
            margin-top: 14px;
            padding: 10px 30px;
            background: var(--indigo-s);
            color: var(--indigo-d);
            font-weight: 700;
            font-size: 14px;
            border-radius: 10px;
            text-decoration: none;
            border: 1.5px solid rgba(99, 102, 241, .25);
            transition: background .18s;
        }

        .btn-retry:hover {
            background: #dde0ff;
            color: var(--indigo-d);
        }

        /* Staggered card animation */
        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .q-card {
            opacity: 0;
            animation: rise .45s ease forwards;
        }

        <?php for ($i = 1; $i <= 10; $i++) echo ".q-card:nth-child($i){animation-delay:" . ($i * .05) . "s;}"; ?>
    </style>
</head>

<body>

    <nav id="navbar" class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a id="title" class="navbar-brand" href="#">CompileX</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="../../home.php">Home</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="../../quiz/">Quiz</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../tools/">Tools</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../leaderboard.php">Leaderboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="../../profile.php">Profile</a>
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

    <div class="page-header">
        <h1>Chapter 1 &mdash; <span>True / False</span> Quiz</h1>
        <p>10 questions based on Chapter 1.1 (What is a Compiler?) and 1.2 (Phases of a Compiler). Select True or False for each question, then submit.</p>
    </div>

    <div class="quiz-wrap">

        <?php if ($submitted): ?>
            <!-- ── Score summary ── -->
            <div class="score-card">
                <div class="score-big"><?= $score ?>/<?= $total ?></div>
                <div class="score-sub">Score: <?= $pct ?>%</div>
                <div class="score-bar-wrap">
                    <div class="score-bar">
                        <div class="score-bar-fill" style="width:<?= $pct ?>%"></div>
                    </div>
                </div>
                <div class="score-msg">
                    <?php
                    if ($pct === 100)      echo '🏆 Perfect score! Outstanding work.';
                    elseif ($pct >= 80)    echo '🎉 Great job! You have a solid grasp of Chapter 1.';
                    elseif ($pct >= 60)    echo '👍 Good effort — review the explanations below to improve.';
                    else                   echo '📖 Keep studying! Read Chapter 1 again and try once more.';
                    ?>
                </div>
                <a class="btn-retry" href="<?= $_SERVER['PHP_SELF'] ?>">↺ Try Again</a>
            </div>
        <?php endif; ?>

        <!-- ── Questions ── -->
        <form method="POST" class="<?= $submitted ? 'submitted' : '' ?>">
            <?php foreach ($questions as $i => $q):
                $r   = $results[$i] ?? null;
                $cls = '';
                if ($submitted && $r) {
                    if (!$r['answered'])   $cls = 'skipped';
                    elseif ($r['correct']) $cls = 'correct';
                    else                   $cls = 'wrong';
                }
            ?>
                <div class="q-card <?= $cls ?>">

                    <?php if ($submitted && $r): ?>
                        <?php if (!$r['answered']): ?>
                            <span class="result-badge badge-skipped">⚪ Skipped</span>
                        <?php elseif ($r['correct']): ?>
                            <span class="result-badge badge-correct">✔ Correct</span>
                        <?php else: ?>
                            <span class="result-badge badge-wrong">✘ Incorrect — Answer: <?= $q['answer'] ? 'True' : 'False' ?></span>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="q-num">Question <?= $i + 1 ?></div>
                    <div class="q-text"><?= htmlspecialchars($q['q']) ?></div>

                    <div class="tf-group">
                        <label>
                            <input type="radio" name="q<?= $i ?>" value="true"
                                <?= ($submitted && $r && $r['answered'] && $r['userAns'] === true) ? 'checked' : '' ?>>
                            <div class="tf-btn true-btn">
                                <span>✓</span> True
                            </div>
                        </label>
                        <label>
                            <input type="radio" name="q<?= $i ?>" value="false"
                                <?= ($submitted && $r && $r['answered'] && $r['userAns'] === false) ? 'checked' : '' ?>>
                            <div class="tf-btn false-btn">
                                <span>✗</span> False
                            </div>
                        </label>
                    </div>

                    <?php if ($submitted): ?>
                        <div class="explain-box">💡 <?= htmlspecialchars($q['explain']) ?></div>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>

            <?php if (!$submitted): ?>
                <div class="submit-wrap">
                    <button type="submit" class="btn-submit">Submit Answers →</button>
                </div>
            <?php endif; ?>
        </form>

    </div>

</body>

</html>
