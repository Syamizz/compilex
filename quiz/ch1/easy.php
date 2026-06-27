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
    $timeRemaining = intval($_POST['time_remaining'] ?? 0);

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

        /* Chapter 2 Normal-style page layout */
        .page-header,
        .page-layout {
            max-width: 1100px;
        }

        .page-header {
            padding: 44px 24px 0;
        }

        .page-layout {
            display: flex;
            align-items: flex-start;
            margin: 0 auto;
            padding: 0 24px;
        }

        .quiz-wrap {
            flex: 1;
            min-width: 0;
            max-width: none;
            margin: 28px 0 80px;
            padding: 0;
        }

        /* Left question navigator */
        .q-nav {
            position: sticky;
            top: 80px;
            flex-shrink: 0;
            box-sizing: border-box;
            width: 230px;
            margin-top: 28px;
            margin-right: 20px;
            padding: 14px 12px 16px;
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 6px;
            align-items: start;
            background: #fff;
            border: 1px solid #E0E7FF;
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(99, 102, 241, .09);
        }

        .timer-wrap {
            grid-column: 1 / -1;
            width: 100%;
            margin-bottom: 14px;
            text-align: center;
        }

        .timer-box {
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 10px 12px;
            background: #fff;
            border: 1.5px solid #E0E7FF;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(99, 102, 241, .10);
            color: var(--indigo);
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 2px;
            transition: all .3s;
        }

        .timer-box.warning {
            border-color: #F59E0B;
            color: #92400E;
            background: #FFFBEB;
        }

        .timer-box.danger {
            border-color: var(--red);
            color: var(--red-t);
            background: #FFF5F5;
            animation: timerPulse .6s infinite alternate;
        }

        @keyframes timerPulse {
            from { box-shadow: 0 4px 18px rgba(239, 68, 68, .15); }
            to { box-shadow: 0 4px 28px rgba(239, 68, 68, .45); }
        }

        .q-nav-label {
            grid-column: 1 / -1;
            margin-bottom: 4px;
            color: #9CA3AF;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
            text-align: center;
            text-transform: uppercase;
        }

        .q-nav-btn {
            justify-self: center;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border: 1.5px solid #E5E7EB;
            border-radius: 6px;
            background: #F9FAFB;
            color: #6B7280;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all .18s;
        }

        .q-nav-btn:hover {
            border-color: var(--indigo);
            background: var(--indigo-s);
            color: var(--indigo-d);
            transform: scale(1.08);
        }

        .q-nav-btn.nav-active {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .3);
        }

        .q-nav-btn.nav-correct {
            border-color: var(--green);
            background: var(--green);
            color: #fff;
        }

        .q-nav-btn.nav-wrong {
            border-color: var(--red);
            background: var(--red);
            color: #fff;
        }

        .q-nav-btn.nav-skipped {
            border-color: #9CA3AF;
            background: #9CA3AF;
            color: #fff;
        }

        .nav-count,
        .nav-submit-wrap {
            grid-column: 1 / -1;
            min-width: 0;
        }

        .nav-count {
            margin-top: 2px;
            color: #9CA3AF;
            font-size: 11px;
            text-align: center;
        }

        .nav-submit-wrap {
            margin-top: 10px;
        }

        .nav-submit-btn {
            width: 100%;
            padding: 9px 0;
            border: 0;
            border-radius: 8px;
            background: linear-gradient(135deg, #6366F1, #8B5CF6);
            box-shadow: 0 3px 10px rgba(99, 102, 241, .3);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        /* Immediate answer response */
        .q-card.answer-locked .tf-group label {
            pointer-events: none;
        }

        .tf-group label.answer-muted {
            opacity: .5;
        }

        .tf-group label.correct-choice {
            opacity: 1;
        }

        .tf-group label.correct-choice .tf-btn {
            border-color: var(--green);
            background: var(--green-s);
            color: var(--green-t);
        }

        .tf-group label.wrong-choice .tf-btn {
            border-color: #60A5FA !important;
            background: #DBEAFE !important;
            color: #1D4ED8 !important;
            box-shadow: 0 0 0 2px rgba(96, 165, 250, .18);
        }

        .answer-feedback {
            display: none;
            width: fit-content;
            margin-bottom: 10px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .answer-feedback.show {
            display: block;
        }

        .explain-box {
            display: none;
        }

        .explain-box.show {
            display: block;
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 28px 16px 0;
            }

            .page-header h1 {
                font-size: 23px;
            }

            .page-layout {
                display: block;
                padding: 0 14px;
            }

            .q-nav {
                position: sticky;
                top: 0;
                z-index: 100;
                width: 100%;
                margin: 18px 0;
            }

            .q-nav-btn {
                width: 100%;
                max-width: 42px;
                margin: auto;
            }

            .quiz-wrap {
                width: 100%;
                margin-top: 18px;
            }

            .q-card {
                padding: 20px 16px;
            }

            .tf-group {
                flex-direction: column;
            }

            .tf-btn {
                padding: 12px;
            }

            .btn-submit {
                width: 100%;
                padding: 14px 20px;
            }
        }

        @media (max-width: 420px) {
            .q-card {
                padding: 17px 12px;
            }

            .q-text {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <audio id="correctSound" src="../../sound/correct answer.mp3" preload="auto"></audio>
    <audio id="wrongSound" src="../../sound/wrong answer.mp3" preload="auto"></audio>
    <audio id="submitSound" src="../../sound/alert2.mp3" preload="auto"></audio>

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

    <div class="page-layout">

        <nav class="q-nav" id="qNav">
            <?php if (!$submitted): ?>
                <div class="timer-wrap">
                    <div class="timer-box" id="timerBox">
                        <span aria-hidden="true">⏱</span>
                        <span id="timerDisplay">10:00</span>
                    </div>
                </div>
            <?php endif; ?>

            <div class="q-nav-label">Question</div>

            <?php for ($i = 0; $i < $total; $i++):
                $navClass = 'q-nav-btn';

                if ($submitted && isset($results[$i])) {
                    if (!$results[$i]['answered']) {
                        $navClass .= ' nav-skipped';
                    } elseif ($results[$i]['correct']) {
                        $navClass .= ' nav-correct';
                    } else {
                        $navClass .= ' nav-wrong';
                    }
                }
            ?>
                <a href="#q-<?= $i ?>"
                    class="<?= $navClass ?>"
                    id="nav-btn-<?= $i ?>">
                    <?= $i + 1 ?>
                </a>
            <?php endfor; ?>

            <div class="nav-count" id="nav-count">
                <?= $submitted
                    ? count(array_filter($results, fn($r) => $r['answered']))
                    : 0 ?>
                / <?= $total ?>
            </div>

            <?php if (!$submitted): ?>
                <div class="nav-submit-wrap">
                    <button type="submit"
                        form="quizForm"
                        class="nav-submit-btn">
                        Submit →
                    </button>
                </div>
            <?php endif; ?>
        </nav>

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
        <form method="POST"
            id="quizForm"
            class="<?= $submitted ? 'submitted' : '' ?>">

            <input type="hidden"
                name="time_remaining"
                id="timeRemainingInput"
                value="0">

            <?php foreach ($questions as $i => $q):
                $r   = $results[$i] ?? null;
                $cls = '';
                if ($submitted && $r) {
                    if (!$r['answered'])   $cls = 'skipped';
                    elseif ($r['correct']) $cls = 'correct';
                    else                   $cls = 'wrong';
                }
            ?>
                <div id="q-<?= $i ?>"
                    class="q-card <?= $cls ?>"
                    data-question="<?= $i ?>"
                    data-answer="<?= $q['answer'] ? 'true' : 'false' ?>">

                    <?php if ($submitted && $r): ?>
                        <?php if (!$r['answered']): ?>
                            <div class="answer-feedback show badge-skipped">
                                ⚪ Skipped — Correct answer:
                                <?= $q['answer'] ? 'True' : 'False' ?>
                            </div>
                        <?php elseif ($r['correct']): ?>
                            <div class="answer-feedback show badge-correct">
                                ✔ Correct
                            </div>
                        <?php else: ?>
                            <div class="answer-feedback show badge-wrong">
                                ✘ Incorrect — Correct answer:
                                <?= $q['answer'] ? 'True' : 'False' ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="answer-feedback"
                            id="feedback-<?= $i ?>"></div>
                    <?php endif; ?>

                    <div class="q-num">
                        Question <?= $i + 1 ?> of <?= $total ?>
                    </div>
                    <div class="q-text"><?= htmlspecialchars($q['q']) ?></div>

                    <div class="tf-group">
                        <label data-value="true"
                            class="<?=
                                $submitted && $q['answer'] === true
                                    ? 'correct-choice'
                                    : (
                                        $submitted && $r && $r['answered'] &&
                                        $r['userAns'] === true
                                            ? 'wrong-choice'
                                            : ($submitted ? 'answer-muted' : '')
                                    )
                            ?>">
                            <input type="radio" name="q<?= $i ?>" value="true"
                                <?= ($submitted && $r && $r['answered'] && $r['userAns'] === true) ? 'checked' : '' ?>>
                            <div class="tf-btn true-btn">
                                <span>✓</span> True
                            </div>
                        </label>
                        <label data-value="false"
                            class="<?=
                                $submitted && $q['answer'] === false
                                    ? 'correct-choice'
                                    : (
                                        $submitted && $r && $r['answered'] &&
                                        $r['userAns'] === false
                                            ? 'wrong-choice'
                                            : ($submitted ? 'answer-muted' : '')
                                    )
                            ?>">
                            <input type="radio" name="q<?= $i ?>" value="false"
                                <?= ($submitted && $r && $r['answered'] && $r['userAns'] === false) ? 'checked' : '' ?>>
                            <div class="tf-btn false-btn">
                                <span>✗</span> False
                            </div>
                        </label>
                    </div>

                    <div class="explain-box <?= $submitted ? 'show' : '' ?>"
                        id="explanation-<?= $i ?>">
                        💡 <?= htmlspecialchars($q['explain']) ?>
                    </div>

                </div>
            <?php endforeach; ?>

            <?php if (!$submitted): ?>
                <div class="submit-wrap">
                    <button type="submit" class="btn-submit">Submit Answers →</button>
                </div>
            <?php endif; ?>
        </form>

        </div>
    </div>

    <script>
        const total = <?= $total ?>;
        const quizSubmitted = <?= $submitted ? 'true' : 'false' ?>;
        const answered = new Set();

        function playSound(soundId) {
            const sound = document.getElementById(soundId);
            if (!sound) return;

            sound.currentTime = 0;
            sound.play().catch(function() {
                // The browser may block audio before user interaction.
            });
        }

        function answerQuestion(input) {
            if (quizSubmitted) return;

            const card = input.closest(".q-card");
            if (!card || card.dataset.answered === "true") return;

            const questionIndex = card.dataset.question;
            const selectedAnswer = input.value;
            const correctAnswer = card.dataset.answer;
            const labels = card.querySelectorAll(".tf-group label");
            const correctLabel = card.querySelector(
                `.tf-group label[data-value="${correctAnswer}"]`
            );
            const selectedLabel = input.closest("label");

            input.checked = true;
            card.dataset.answered = "true";
            card.classList.add("answer-locked");
            answered.add(questionIndex);

            labels.forEach(function(label) {
                if (label !== correctLabel && label !== selectedLabel) {
                    label.classList.add("answer-muted");
                }
            });

            if (correctLabel) {
                correctLabel.classList.add("correct-choice");
            }

            const feedback = document.getElementById(
                "feedback-" + questionIndex
            );
            const explanation = document.getElementById(
                "explanation-" + questionIndex
            );
            const navButton = document.getElementById(
                "nav-btn-" + questionIndex
            );

            if (selectedAnswer === correctAnswer) {
                playSound("correctSound");
                card.classList.add("correct");

                if (feedback) {
                    feedback.textContent = "✔ Correct";
                    feedback.className =
                        "answer-feedback show badge-correct";
                }

                if (navButton) {
                    navButton.classList.add("nav-correct");
                }
            } else {
                playSound("wrongSound");
                card.classList.add("wrong");

                if (selectedLabel) {
                    selectedLabel.classList.add("wrong-choice");
                }

                if (feedback) {
                    const answerText =
                        correctAnswer === "true" ? "True" : "False";
                    feedback.textContent =
                        "✘ Incorrect — Correct answer: " + answerText;
                    feedback.className =
                        "answer-feedback show badge-wrong";
                }

                if (navButton) {
                    navButton.classList.add("nav-wrong");
                }
            }

            if (explanation) {
                explanation.classList.add("show");
            }

            const count = document.getElementById("nav-count");
            if (count) {
                count.textContent = answered.size + " / " + total;
            }
        }

        document.querySelectorAll(
            '.q-card input[type="radio"]'
        ).forEach(function(input) {
            input.addEventListener("change", function() {
                answerQuestion(input);
            });
        });

        const cards = document.querySelectorAll(".q-card");
        const navButtons = document.querySelectorAll(".q-nav-btn");

        cards.forEach(function(card) {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (!entry.isIntersecting) return;

                    navButtons.forEach(function(button) {
                        button.classList.remove("nav-active");
                    });

                    const activeButton = document.getElementById(
                        "nav-btn-" + entry.target.dataset.question
                    );

                    if (activeButton) {
                        activeButton.classList.add("nav-active");
                    }
                });
            }, {
                threshold: .35
            });

            observer.observe(card);
        });

        const DURATION = 10 * 60;
        const timerBox = document.getElementById("timerBox");
        const timerDisplay = document.getElementById("timerDisplay");
        const timeInput = document.getElementById("timeRemainingInput");
        const quizForm = document.getElementById("quizForm");

        if (!quizSubmitted && timerBox && timerDisplay && quizForm) {
            let remaining = DURATION;
            let isSubmitting = false;

            function formatTime(seconds) {
                const minutes = String(
                    Math.floor(seconds / 60)
                ).padStart(2, "0");
                const secondsLeft = String(seconds % 60).padStart(2, "0");
                return minutes + ":" + secondsLeft;
            }

            function tick() {
                timerDisplay.textContent = formatTime(remaining);

                if (remaining <= 120 && remaining > 30) {
                    timerBox.classList.add("warning");
                    timerBox.classList.remove("danger");
                } else if (remaining <= 30) {
                    timerBox.classList.remove("warning");
                    timerBox.classList.add("danger");
                }

                if (remaining <= 0) {
                    clearInterval(timerInterval);
                    if (timeInput) timeInput.value = 0;
                    playSound("submitSound");

                    setTimeout(function() {
                        quizForm.submit();
                    }, 500);
                    return;
                }

                remaining--;
            }

            tick();
            const timerInterval = setInterval(tick, 1000);

            quizForm.addEventListener("submit", function(event) {
                if (isSubmitting) return;

                event.preventDefault();
                isSubmitting = true;
                clearInterval(timerInterval);

                if (timeInput) {
                    timeInput.value = remaining;
                }

                playSound("submitSound");

                setTimeout(function() {
                    quizForm.submit();
                }, 500);
            });
        }
    </script>

</body>

</html>
