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
        'q'      => 'The lexical scanner reads the input string without backtracking — it processes each symbol exactly once.',
        'answer' => true,
        'explain' => 'The lexical scanner scans the input without backtracking, reading each symbol once and processing it correctly, which is why it is called a scanner.',
        'color'  => 'indigo',
    ],
    [
        'q'      => 'In Java, keywords are reserved words, meaning programmers can use them freely as identifiers.',
        'answer' => false,
        'explain' => 'Reserved words are keywords that are NOT available to the programmer for use as identifiers. In Java (and C), all keywords are reserved.',
        'color'  => 'teal',
    ],
    [
        'q'      => 'White space (spaces and tabs) and comments are both output as tokens by the lexical analyser.',
        'answer' => false,
        'explain' => 'White space is generally ignored (not output as tokens) and comments are detected but also not put out as tokens to the next phase.',
        'color'  => 'purple',
    ],
    [
        'q'      => 'Each token output by the lexical phase consists of two parts: a class (kind of token) and a value (which member of the class).',
        'answer' => true,
        'explain' => 'Each token has (1) a class indicating which kind of token it is, and (2) a value indicating which specific member of that class it represents.',
        'color'  => 'rose',
    ],
    [
        'q'      => 'A formal language is defined as a set of strings from a given alphabet.',
        'answer' => true,
        'explain' => 'A formal language is precisely a set of strings from a given alphabet — this is the fundamental definition used in compiler theory.',
        'color'  => 'amber',
    ],
    [
        'q'      => 'The empty set {} and the null string ε are the same thing.',
        'answer' => false,
        'explain' => 'The empty set {} contains no strings at all, while the null string ε is a string of zero characters. {ε} is a language containing one string (the null string), which is different from {} (the empty language).',
        'color'  => 'green',
    ],
    [
        'q'      => 'A finite state machine accepts an input string if and only if the machine is in an accepting state after reading the entire string.',
        'answer' => true,
        'explain' => 'When the entire input string has been read, if the machine is in an accepting state the string is accepted; otherwise it is rejected.',
        'color'  => 'cyan',
    ],
    [
        'q'      => 'In a deterministic finite state machine, there can be more than one arc leaving a state labeled by the same input symbol.',
        'answer' => false,
        'explain' => 'A deterministic finite state machine has exactly one arc leaving each state for each possible input symbol — no ambiguity is allowed.',
        'color'  => 'indigo',
    ],
    [
        'q'      => 'In regular expressions, Kleene * (closure) takes precedence over concatenation, and concatenation takes precedence over union (+).',
        'answer' => true,
        'explain' => 'The precedence order is: Kleene * (highest) → concatenation → union + (lowest). Parentheses can override this order.',
        'color'  => 'teal',
    ],
    [
        'q'      => 'The symbol table built during lexical analysis stores each identifier every time it appears in the source program.',
        'answer' => false,
        'explain' => 'The symbol table stores each identifier ONCE, regardless of how many times it occurs in the source program. It also stores information about the identifier such as its kind and associated run-time information.',
        'color'  => 'purple',
    ],
];

$total     = count($questions);
$submitted = false;
$score     = 0;
$results   = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted     = true;
    $timeRemaining = intval($_POST['time_remaining'] ?? 0);

    foreach ($questions as $i => $q) {
        $key      = 'q' . $i;
        $raw      = $_POST[$key] ?? null;
        $answered = $raw !== null;
        $userAns  = $answered ? ($raw === 'true') : null;
        $correct  = $answered && ($userAns === $q['answer']);
        if ($correct) $score++;
        $results[] = ['answered' => $answered, 'userAns' => $userAns, 'correct' => $correct];
    }

    // Base points: 100 per correct answer
    $basePoints  = $score * 100;
    $bonusPoints = 0;
    $bonusReason = '';

    // Bonus only if perfect score
    if ($score === $total) {
        if ($timeRemaining > 7 * 60) {          // more than 7 min left
            $bonusPoints = 300;
            $bonusReason = '🚀 Finished with over 7 minutes to spare!';
        } elseif ($timeRemaining > 5 * 60) {    // more than 5 min left
            $bonusPoints = 200;
            $bonusReason = '⚡ Finished with over 5 minutes to spare!';
        }
    }

    $totalPoints = $basePoints + $bonusPoints;
    $pct         = round($score / $total * 100);

    $scorepoint = $totalPoints;
$quiz_id = 'Q004'; // Chapter 2 Easy

/*
 * Save the new quiz attempt
 */
$attemptResult = $conn->query("
    SELECT qa_id
    FROM quiz_attempt
    WHERE qa_id REGEXP '^QA[0-9]+$'
    ORDER BY CAST(SUBSTRING(qa_id, 3) AS UNSIGNED) DESC
    LIMIT 1
");

if ($attemptResult && $attemptResult->num_rows > 0) {
    $attemptRow = $attemptResult->fetch_assoc();
    $nextAttemptNumber =
        (int)substr($attemptRow['qa_id'], 2) + 1;
} else {
    $nextAttemptNumber = 1;
}

$qa_id = 'QA' . str_pad(
    $nextAttemptNumber,
    3,
    '0',
    STR_PAD_LEFT
);

$attemptStmt = $conn->prepare("
    INSERT INTO quiz_attempt
        (qa_id, user_id, quiz_id, scorepoint, answered_at)
    VALUES (?, ?, 'Q004', ?, NOW())
");

if (!$attemptStmt) {
    die('Unable to prepare quiz attempt: ' . $conn->error);
}

$attemptStmt->bind_param(
    "sii",
    $qa_id,
    $user_id,
    $scorepoint
);

if (!$attemptStmt->execute()) {
    die('Unable to save quiz attempt: ' . $attemptStmt->error);
}

$attemptStmt->close();


/*
 * Check whether the user already has a Q004 leaderboard row
 */
$leaderboardStmt = $conn->prepare("
    SELECT board_id, best_score
    FROM leaderboard
    WHERE user_id = ?
      AND quiz_id = 'Q004'
    LIMIT 1
");

if (!$leaderboardStmt) {
    die('Unable to prepare leaderboard: ' . $conn->error);
}

$leaderboardStmt->bind_param("i", $user_id);
$leaderboardStmt->execute();

$leaderboardResult = $leaderboardStmt->get_result();

if ($leaderboardResult->num_rows > 0) {
    /*
     * Update only when the new score is higher
     */
    $leaderboardRow = $leaderboardResult->fetch_assoc();
    $currentBestScore = (int)$leaderboardRow['best_score'];

    if ($scorepoint > $currentBestScore) {
        $updateStmt = $conn->prepare("
            UPDATE leaderboard
            SET best_score = ?,
                updated_at = NOW()
            WHERE board_id = ?
        ");

        if (!$updateStmt) {
            die(
                'Unable to prepare leaderboard update: '
                . $conn->error
            );
        }

        $board_id = $leaderboardRow['board_id'];

        $updateStmt->bind_param(
            "is",
            $scorepoint,
            $board_id
        );

        if (!$updateStmt->execute()) {
            die(
                'Unable to update leaderboard: '
                . $updateStmt->error
            );
        }

        $updateStmt->close();
    }
} else {
    /*
     * Create a new leaderboard row
     */
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
            (
                board_id,
                user_id,
                quiz_id,
                best_score,
                updated_at
            )
        VALUES (?, ?, 'Q004', ?, NOW())
    ");

    if (!$insertStmt) {
        die(
            'Unable to prepare leaderboard insert: '
            . $conn->error
        );
    }

    $insertStmt->bind_param(
        "sii",
        $board_id,
        $user_id,
        $scorepoint
    );

    if (!$insertStmt->execute()) {
        die(
            'Unable to save leaderboard: '
            . $insertStmt->error
        );
    }

    $insertStmt->close();
}

$leaderboardStmt->close();
}

$pct = $submitted ? round($score / $total * 100) : 0;

$palette = [
    'indigo' => ['border' => '#6366F1', 'light' => '#EEF2FF', 'text' => '#4338CA'],
    'teal'   => ['border' => '#14B8A6', 'light' => '#F0FDFA', 'text' => '#0F766E'],
    'purple' => ['border' => '#A855F7', 'light' => '#FAF5FF', 'text' => '#7E22CE'],
    'rose'   => ['border' => '#F43F5E', 'light' => '#FFF1F2', 'text' => '#BE123C'],
    'amber'  => ['border' => '#F59E0B', 'light' => '#FFFBEB', 'text' => '#92400E'],
    'green'  => ['border' => '#22C55E', 'light' => '#F0FDF4', 'text' => '#15803D'],
    'cyan'   => ['border' => '#06B6D4', 'light' => '#ECFEFF', 'text' => '#155E75'],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter 2 – True / False Quiz</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../css/body.css">

    <link rel="stylesheet" href="easy.css">

    <style>
        /* ── Left nav ── */
        .q-nav {
            position: sticky;
            top: 80px;
            flex-shrink: 0;
            background: #fff;
            border-radius: 16px;
            border: 1px solid #E0E7FF;
            box-shadow: 0 4px 18px rgba(99, 102, 241, .09);
            padding: 14px 12px;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 6px;
            align-items: start;
            margin-top: 28px;
            margin-right: 20px;
            width: 210px;
        }

        .q-nav-label {
            grid-column: 1 / -1;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #9CA3AF;
            margin-bottom: 4px;
            text-align: center;
        }

        .q-nav-btn {
            width: 34px;
            height: 34px;
            border-radius: 6px;
            border: 1.5px solid #E5E7EB;
            background: #F9FAFB;
            font-size: 12px;
            font-weight: 700;
            color: #6B7280;
            cursor: pointer;
            transition: all .18s;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .q-nav-btn:hover {
            background: #EEF2FF;
            border-color: #6366F1;
            color: #4338CA;
            transform: scale(1.08);
        }

        .q-nav-btn.nav-answered {
            background: #6366F1;
            border-color: #6366F1;
            color: #fff;
        }

        .q-nav-btn.nav-active {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .3);
        }

        .q-nav-btn.nav-correct {
            background: #22C55E;
            border-color: #22C55E;
            color: #fff;
        }

        .q-nav-btn.nav-wrong {
            background: #EF4444;
            border-color: #EF4444;
            color: #fff;
        }

        .q-nav-btn.nav-skipped {
            background: #9CA3AF;
            border-color: #9CA3AF;
            color: #fff;
        }

        /* ── Quiz wrap ── */
        .quiz-wrap {
            flex: 1;
            min-width: 0;
            margin: 28px 0 80px;
            padding: 0;
        }

        /* ── Question card ── */
        .q-card {
            background: #fff;
            border: 1px solid rgba(99, 102, 241, .13);
            border-left: 5px solid #E5E7EB;
            border-radius: var(--radius);
            padding: 24px 26px;
            margin-bottom: 18px;
            box-shadow: 0 2px 12px rgba(99, 102, 241, .06);
            transition: box-shadow .2s;
            opacity: 0;
            animation: rise .45s ease forwards;
        }

        .q-card:hover {
            box-shadow: 0 6px 24px rgba(99, 102, 241, .12);
        }

        <?php foreach ($palette as $name => $c): ?>.q-card.color-<?= $name ?> {
            border-left-color: <?= $c['border'] ?>;
            background: <?= $c['light'] ?>;
        }

        .q-card.color-<?= $name ?>.q-num {
            background: <?= $c['light'] ?>;
            color: <?= $c['text'] ?>;
            border: 1px solid <?= $c['border'] ?>22;
        }

        <?php endforeach; ?><?php for ($i = 1; $i <= 10; $i++) echo ".q-card:nth-child($i){animation-delay:" . ($i * .05) . "s;}"; ?>@keyframes rise {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            line-height: 1.6;
            margin-bottom: 16px;
        }

        /* Match the completed Chapter 1 Easy interaction and layout. */
        .q-nav {
            box-sizing: border-box;
            width: 230px;
            padding-bottom: 16px;
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }

        .q-nav > .timer-wrap {
            position: static;
            grid-column: 1 / -1;
            width: 100%;
            margin: 0 0 14px;
        }

        .q-nav > .timer-wrap .timer-box {
            box-sizing: border-box;
            display: flex;
            width: 100%;
            justify-content: center;
            padding: 10px 12px;
        }

        .q-nav-btn {
            justify-self: center;
        }

        .q-nav-label,
        .nav-count,
        .nav-submit-wrap {
            min-width: 0;
        }

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
            border-color: var(--green) !important;
            background: var(--green-s) !important;
            color: var(--green-t) !important;
        }

        /* The user's wrong selection is blue; the correct answer stays green. */
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
                grid-template-columns: repeat(5, minmax(0, 1fr));
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

            .score-row {
                flex-wrap: wrap;
                gap: 24px;
            }

            .score-card {
                padding: 24px 16px;
            }

            .score-big {
                font-size: 34px;
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
        <div class="difficulty-chip">🌱 Easy Level</div>
        <h1>Chapter 2 &mdash; <span>True / False</span> Quiz</h1>
        <p>10 questions based on Section 2.0 (Formal Languages &amp; Finite State Machines) and 2.1 (Lexical Tokens). Select True or False for each question, then submit.</p>
    </div>

    <div class="page-layout">

        <!-- ── Left Nav ── -->
        <nav class="q-nav" id="qNav">
            <?php if (!$submitted): ?>
                <div class="timer-wrap">
                    <div class="timer-box" id="timerBox">
                        <span class="timer-icon" aria-hidden="true">⏱</span>
                        <span id="timerDisplay">10:00</span>
                    </div>
                </div>
            <?php endif; ?>

            <div class="q-nav-label">Question</div>
            <?php for ($i = 0; $i < $total; $i++):
                $nc = 'q-nav-btn';
                if ($submitted && isset($results[$i])) {
                    $r = $results[$i];
                    if (!$r['answered'])    $nc .= ' nav-skipped';
                    elseif ($r['correct'])  $nc .= ' nav-correct';
                    else                   $nc .= ' nav-wrong';
                }
            ?>
                <a href="#q-<?= $i ?>" class="<?= $nc ?>" id="nav-btn-<?= $i ?>"><?= $i + 1 ?></a>
            <?php endfor; ?>

            <!-- answered counter -->
            <div class="nav-count" id="nav-count">
                <?= $submitted
                    ? count(array_filter($results, fn($r) => $r['answered']))
                    : 0 ?>
                / <?= $total ?>
            </div>

            <!-- submit inside nav -->
            <?php if (!$submitted): ?>
                <div class="nav-submit-wrap">
                    <button type="submit" form="quizForm" class="nav-submit-btn">Submit →</button>
                </div>
            <?php endif; ?>
        </nav>


        <!-- ── Questions ── -->
        <div class="quiz-wrap">
            <?php if ($submitted):
                $wrong   = array_reduce(array_keys($results), fn($c, $i) => $c + (!$results[$i]['correct'] && $results[$i]['answered'] ? 1 : 0), 0);
                $skipped = array_reduce($results, fn($c, $r) => $c + (!$r['answered'] ? 1 : 0), 0);
            ?>
                <div class="score-card">
                    <div class="score-row">
                        <div class="score-stat">
                            <div class="score-big"><?= $score ?>/<?= $total ?></div>
                            <div class="score-lbl">Score</div>
                        </div>
                        <div class="score-stat">
                            <div class="score-big stat-correct"><?= $score ?></div>
                            <div class="score-lbl">Correct</div>
                        </div>
                        <div class="score-stat">
                            <div class="score-big stat-wrong"><?= $wrong + $skipped ?></div>
                            <div class="score-lbl">Missed</div>
                        </div>
                        <div class="score-stat">
                            <div class="score-big" style="color:#F59E0B;"><?= $pct ?>%</div>
                            <div class="score-lbl">Percentage</div>
                        </div>
                    </div>

                    <!-- Points display -->
                    <div style="margin: 14px 0 10px; padding: 16px; background: #EEF2FF; border-radius: 12px; border: 1.5px solid rgba(99,102,241,.2);">
                        <div style="font-size:13px; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:.8px; margin-bottom:6px;">Points Earned</div>
                        <div style="display:flex; justify-content:center; align-items:baseline; gap:8px; flex-wrap:wrap;">
                            <span style="font-size:36px; font-weight:800; color:#6366F1;"><?= $totalPoints ?></span>
                            <span style="font-size:14px; color:#6B7280;">pts</span>
                            <?php if ($bonusPoints > 0): ?>
                                <span style="font-size:13px; background:#D1FAE5; color:#065F46; font-weight:700; padding:3px 10px; border-radius:20px; border:1px solid #6EE7B7;">
                                    +<?= $bonusPoints ?> bonus
                                </span>
                            <?php endif; ?>
                        </div>
                        <div style="display:flex; justify-content:center; gap:20px; margin-top:10px; font-size:13px; color:#6B7280;">
                            <span>Base: <strong style="color:#374151;"><?= $basePoints ?> pts</strong></span>
                            <?php if ($bonusPoints > 0): ?>
                                <span>Bonus: <strong style="color:#059669;">+<?= $bonusPoints ?> pts</strong></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($bonusReason): ?>
                            <div style="margin-top:10px; font-size:13px; font-weight:600; color:#4338CA;"><?= $bonusReason ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="score-bar">
                        <div class="score-bar-fill" style="width:<?= $pct ?>%"></div>
                    </div>
                    <div class="score-msg">
                        <?php
                        if ($pct === 100 && $bonusPoints > 0) echo '🏆 Perfect score with bonus! Incredible!';
                        elseif ($pct == 100)                  echo '🏆 Perfect score! You nailed Chapter 2!';
                        elseif ($pct >= 80 && $pct < 100)    echo '🎉 Great job! Strong understanding of lexical analysis.';
                        elseif ($pct >= 60 && $pct < 80)    echo '👍 Good effort — review the explanations below.';
                        elseif ($pct >= 0 && $pct < 60)     echo '📖 Keep studying! Re-read Chapter 2.0 and 2.1 and try again.';
                        ?>
                    </div>
                    <a class="btn-retry" href="<?= $_SERVER['PHP_SELF'] ?>">↺ Try Again</a>
                </div>
            <?php endif; ?>

            <form method="POST" id="quizForm" class="<?= $submitted ? 'submitted' : '' ?>">

                <!-- hidden field to capture time remaining on submit -->
                <input type="hidden" name="time_remaining" id="timeRemainingInput" value="0">
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
                        class="q-card color-<?= $q['color'] ?> <?= $cls ?>"
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

                        <div class="q-num">Question <?= $i + 1 ?> of <?= $total ?></div>
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

        </div><!-- end quiz-wrap -->
    </div><!-- end page-layout -->

    <script type="text/plain" id="legacyQuizScript">
        const navBtns = document.querySelectorAll('.q-nav-btn');
        const cards = document.querySelectorAll('.q-card');
        const countEl = document.getElementById('nav-count');
        const total = <?= $total ?>;

        const answered = new Set();

        function updateCount() {
            if (countEl) countEl.textContent = answered.size + ' / ' + total;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const idx = entry.target.id.split('-')[1];
                    navBtns.forEach(b => b.classList.remove('nav-active'));
                    const active = document.getElementById('nav-btn-' + idx);
                    if (active) active.classList.add('nav-active');
                }
            });
        }, {
            threshold: 0.4
        });

        cards.forEach(c => observer.observe(c));

        document.querySelectorAll('input[type=radio]').forEach(radio => {
            radio.addEventListener('change', () => {
                const name = radio.getAttribute('name');
                const idx = name.replace('q', '');
                const btn = document.getElementById('nav-btn-' + idx);

                if (btn) {
                    if (!btn.classList.contains('nav-correct') &&
                        !btn.classList.contains('nav-wrong') &&
                        !btn.classList.contains('nav-skipped')) {
                        btn.classList.add('nav-answered');
                    }
                }

                answered.add(idx);
                updateCount();
            });
        });

        document.querySelectorAll('input[type=radio]:checked').forEach(radio => {
            answered.add(radio.getAttribute('name').replace('q', ''));
        });
        updateCount();

        // ── Timer ──────────────────────────────────────────────
        const DURATION = 10 * 60;
        const timerBox = document.getElementById('timerBox');
        const timerDisp = document.getElementById('timerDisplay');
        const timeInput = document.getElementById('timeRemainingInput');

        function submitWithTime(remaining) {
            if (timeInput) timeInput.value = remaining;
            document.getElementById('quizForm').submit();
        }

        if (timerBox && timerDisp) {
            let remaining = DURATION;

            function formatTime(s) {
                const m = String(Math.floor(s / 60)).padStart(2, '0');
                const sec = String(s % 60).padStart(2, '0');
                return m + ':' + sec;
            }

            function tick() {
                timerDisp.textContent = formatTime(remaining);

                if (remaining <= 300 && remaining > 60) {
                    timerBox.classList.add('warning');
                    timerBox.classList.remove('danger');
                }
                if (remaining <= 60) {
                    timerBox.classList.remove('warning');
                    timerBox.classList.add('danger');
                }

                if (remaining <= 0) {
                    clearInterval(timerInterval);
                    timerDisp.textContent = '00:00';
                    submitWithTime(0);
                    return;
                }

                remaining--;
            }

            tick();
            const timerInterval = setInterval(tick, 1000);

            // Intercept manual submit to capture remaining time
            document.getElementById('quizForm').addEventListener('submit', function() {
                if (timeInput) timeInput.value = remaining;
            });
        }
    </script>

    <script>
        const totalQuestions = <?= $total ?>;
        const quizSubmitted = <?= $submitted ? 'true' : 'false' ?>;
        const answeredQuestions = new Set();

        function playQuizSound(soundId) {
            const sound = document.getElementById(soundId);
            if (!sound) return;
            sound.currentTime = 0;
            sound.play().catch(function() {});
        }

        function answerQuestion(input) {
            if (quizSubmitted) return;

            const card = input.closest(".q-card");
            if (!card || card.dataset.answered === "true") return;

            const index = card.dataset.question;
            const selected = input.value;
            const correct = card.dataset.answer;
            const selectedLabel = input.closest("label");
            const correctLabel = card.querySelector(
                `.tf-group label[data-value="${correct}"]`
            );

            input.checked = true;
            card.dataset.answered = "true";
            card.classList.add("answer-locked");
            answeredQuestions.add(index);

            card.querySelectorAll(".tf-group label").forEach(function(label) {
                if (label !== selectedLabel && label !== correctLabel) {
                    label.classList.add("answer-muted");
                }
            });

            if (correctLabel) correctLabel.classList.add("correct-choice");

            const feedback = document.getElementById("feedback-" + index);
            const explanation = document.getElementById("explanation-" + index);
            const navButton = document.getElementById("nav-btn-" + index);

            if (selected === correct) {
                playQuizSound("correctSound");
                card.classList.add("correct");

                if (feedback) {
                    feedback.textContent = "✔ Correct";
                    feedback.className = "answer-feedback show badge-correct";
                }

                if (navButton) navButton.classList.add("nav-correct");
            } else {
                playQuizSound("wrongSound");
                card.classList.add("wrong");

                if (selectedLabel) selectedLabel.classList.add("wrong-choice");

                if (feedback) {
                    feedback.textContent =
                        "✘ Incorrect — Correct answer: " +
                        (correct === "true" ? "True" : "False");
                    feedback.className = "answer-feedback show badge-wrong";
                }

                if (navButton) navButton.classList.add("nav-wrong");
            }

            if (explanation) explanation.classList.add("show");

            const count = document.getElementById("nav-count");
            if (count) {
                count.textContent =
                    answeredQuestions.size + " / " + totalQuestions;
            }
        }

        document.querySelectorAll('.q-card input[type="radio"]').forEach(
            function(input) {
                input.addEventListener("change", function() {
                    answerQuestion(input);
                });
            }
        );

        const questionNavButtons = document.querySelectorAll(".q-nav-btn");

        document.querySelectorAll(".q-card").forEach(function(card) {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (!entry.isIntersecting) return;

                    questionNavButtons.forEach(function(button) {
                        button.classList.remove("nav-active");
                    });

                    const active = document.getElementById(
                        "nav-btn-" + entry.target.dataset.question
                    );
                    if (active) active.classList.add("nav-active");
                });
            }, { threshold: .35 });

            observer.observe(card);
        });

        const EASY_DURATION = 10 * 60;
        const easyTimerBox = document.getElementById("timerBox");
        const easyTimerDisplay = document.getElementById("timerDisplay");
        const easyTimeInput = document.getElementById("timeRemainingInput");
        const easyQuizForm = document.getElementById("quizForm");

        if (
            !quizSubmitted &&
            easyTimerBox &&
            easyTimerDisplay &&
            easyQuizForm
        ) {
            let remaining = EASY_DURATION;
            let isSubmitting = false;

            function formatEasyTime(seconds) {
                return String(Math.floor(seconds / 60)).padStart(2, "0") +
                    ":" + String(seconds % 60).padStart(2, "0");
            }

            function easyTick() {
                easyTimerDisplay.textContent = formatEasyTime(remaining);

                if (remaining <= 120 && remaining > 30) {
                    easyTimerBox.classList.add("warning");
                    easyTimerBox.classList.remove("danger");
                } else if (remaining <= 30) {
                    easyTimerBox.classList.remove("warning");
                    easyTimerBox.classList.add("danger");
                }

                if (remaining <= 0) {
                    clearInterval(easyTimerInterval);
                    if (easyTimeInput) easyTimeInput.value = 0;
                    playQuizSound("submitSound");
                    setTimeout(function() {
                        easyQuizForm.submit();
                    }, 500);
                    return;
                }

                remaining--;
            }

            easyTick();
            const easyTimerInterval = setInterval(easyTick, 1000);

            easyQuizForm.addEventListener("submit", function(event) {
                if (isSubmitting) return;

                event.preventDefault();
                isSubmitting = true;
                clearInterval(easyTimerInterval);
                if (easyTimeInput) easyTimeInput.value = remaining;
                playQuizSound("submitSound");

                setTimeout(function() {
                    easyQuizForm.submit();
                }, 500);
            });
        }
    </script>

</body>

</html>
