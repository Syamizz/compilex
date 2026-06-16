<?php
session_start();
include '../../dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$questions = [
    [
        'q'      => 'In a top down parsing algorithm, grammar rules are applied in a sequence that corresponds to a top down direction in the derivation tree.',
        'answer' => true,
        'explain' => 'A top down parsing algorithm applies grammar rules in a sequence which corresponds to a general top down direction in the derivation tree, beginning with the starting nonterminal and working downward.',
        'color'  => 'indigo',
    ],
    [
        'q'      => 'The reflexive transitive closure R* requires that if (a,b) is in R, then (b,a) must also be in R*.',
        'answer' => false,
        'explain' => 'The reflexive transitive closure R* requires two properties: transitivity (if (a,b) and (b,c) are in R*, then (a,c) is in R*) and reflexivity (if a appears in any pair of R, then (a,a) is in R*). It does NOT require symmetry — (a,b) in R does not imply (b,a) in R*.',
        'color'  => 'teal',
    ],
    [
        'q'      => 'A grammar is considered simple if every rule begins with a terminal symbol, and no two rules defining the same nonterminal begin with the same terminal.',
        'answer' => true,
        'explain' => 'A simple grammar requires every rule to be of the form A → aα (starting with a terminal), and every pair of rules defining the same nonterminal must begin with different terminals on the right side of the arrow.',
        'color'  => 'purple',
    ],
    [
        'q'      => 'A quasi-simple grammar is identical to a simple grammar and does not allow epsilon (ε) rules.',
        'answer' => false,
        'explain' => 'A quasi-simple grammar extends simple grammars by also permitting rules of the form N → ε (epsilon rules), as long as all rules defining the same nonterminal still have disjoint selection sets.',
        'color'  => 'rose',
    ],
    [
        'q'      => 'For a simple grammar, the selection set of each rule always contains exactly one terminal symbol — the one beginning the right-hand side of the rule.',
        'answer' => true,
        'explain' => 'In simple grammars, the selection set of each rule always contains exactly one terminal symbol — the terminal which begins the right-hand side of the rule. This is why simple grammars are easy to parse top down.',
        'color'  => 'amber',
    ],
    [
        'q'      => 'The follow set of a nonterminal A, Fol(A), is the set of all terminals that can begin a string derivable from A.',
        'answer' => false,
        'explain' => 'The follow set of a nonterminal A (Fol(A)) is the set of all terminals (or endmarker) which can immediately FOLLOW an A in an intermediate form derived from the starting nonterminal. The set of terminals that can BEGIN a string derivable from A is called First(A).',
        'color'  => 'green',
    ],
    [
        'q'      => 'Any grammar containing left recursion (a rule of the form A → Aα) cannot be LL(1).',
        'answer' => true,
        'explain' => 'A grammar with left recursion cannot be LL(1) because in a recursive descent parser, the method for nonterminal A would immediately call itself before reading any input, causing infinite recursion with no escape hatch.',
        'color'  => 'cyan',
    ],
    [
        'q'      => 'Grammar G5 for arithmetic expressions (with rules like Expr → Expr + Term) is LL(1) and can be parsed top down without modification.',
        'answer' => false,
        'explain' => 'Grammar G5 is NOT LL(1) because it contains left recursion (Expr → Expr + Term and Term → Term * Factor). Rules 1 and 2 define the same nonterminal Expr and have intersecting selection sets {(,var}. The left recursion must be eliminated first, producing an equivalent grammar like G16.',
        'color'  => 'indigo',
    ],
    [
        'q'      => 'In the algorithm for finding selection sets, if a grammar contains no nullable rules, steps 6 through 11 can be skipped and selection sets obtained directly from step 5.',
        'answer' => true,
        'explain' => 'If the grammar contains no nullable rules, the selection set for each rule equals First(right side of rule), which is computed in step 5. Steps 6–11 are only needed to compute follow sets for nullable nonterminals, which are then used in step 12 for nullable rules.',
        'color'  => 'teal',
    ],
    [
        'q'      => 'In a recursive descent parser for a quasi-simple grammar, an epsilon rule is implemented by reading the next input symbol and returning.',
        'answer' => false,
        'explain' => 'In a recursive descent parser, an epsilon rule is implemented using a null statement — the method simply returns to the calling method WITHOUT reading any input characters. This correctly represents the derivation of the null string ε.',
        'color'  => 'purple',
    ],
];

$total     = count($questions);
$submitted = false;
$score     = 0;
$results   = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = true;

    foreach ($questions as $i => $q) {
        $key      = 'q' . $i;
        $raw      = $_POST[$key] ?? null;
        $answered = $raw !== null;
        $userAns  = $answered ? ($raw === 'true') : null;
        $correct  = $answered && ($userAns === $q['answer']);

        if ($correct) {
            $score++;
        }

        $results[] = [
            'answered' => $answered,
            'userAns'  => $userAns,
            'correct'  => $correct
        ];
    }

    $scorepoint = $score * 100;

    $result = $conn->query("
        SELECT board_id
        FROM leaderboard
        ORDER BY CAST(SUBSTRING(board_id, 3) AS UNSIGNED) DESC
        LIMIT 1
    ");

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_number = (int) substr($row['board_id'], 2);
        $next_number = $last_number + 1;
    } else {
        $next_number = 1;
    }

    $board_id   = 'BD' . $next_number;
    $board_chap = 4;
    $quiz_type  = "Easy";

    $stmt = $conn->prepare("
        INSERT INTO leaderboard (board_id, board_chap, quiz_type, scorepoint, user_id)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("sisii", $board_id, $board_chap, $quiz_type, $scorepoint, $user_id);
    $stmt->execute();
    $stmt->close();
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
    <title>Chapter 4 – True / False Quiz</title>

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
            max-width: 1100px;
            margin: 0 auto;
            padding: 44px 24px 0;
        }

        .page-header h1 {
            font-size: 28px;
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

        .difficulty-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #D1FAE5;
            color: #065F46;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            border: 1px solid #6EE7B7;
            margin-bottom: 8px;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        /* ── Layout ── */
        .page-layout {
            display: flex;
            align-items: flex-start;
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
            gap: 0;
        }

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

        /* True / False buttons */
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
            padding: 11px 0;
            border-radius: 10px;
            border: 1.5px solid #E5E7EB;
            font-size: 14px;
            font-weight: 600;
            color: var(--muted);
            background: #fff;
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
            border-color: #A5B4FC;
            background: #F3F4F6;
        }

        /* Result card states */
        .q-card.correct {
            border-left-color: var(--green) !important;
            background: #F0FDF4 !important;
            border-color: #BBF7D0;
        }

        .q-card.wrong {
            border-left-color: var(--red) !important;
            background: #FFF5F5 !important;
            border-color: #FECACA;
        }

        .q-card.skipped {
            border-left-color: #9CA3AF !important;
            background: #F9FAFB !important;
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

        /* Explanation */
        .explain-box {
            margin-top: 12px;
            padding: 12px 14px;
            background: rgba(99, 102, 241, .06);
            border-left: 3px solid var(--indigo);
            border-radius: 0 8px 8px 0;
            font-size: 13px;
            color: #374151;
            line-height: 1.65;
        }

        /* Locked after submit */
        .submitted .tf-group label {
            pointer-events: none;
        }

        /* ── Score card ── */
        .score-card {
            background: #fff;
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            padding: 30px;
            text-align: center;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(99, 102, 241, .10);
        }

        .score-row {
            display: flex;
            justify-content: center;
            gap: 36px;
            margin-bottom: 18px;
        }

        .score-stat {
            text-align: center;
        }

        .score-big {
            font-size: 44px;
            font-weight: 800;
            color: var(--indigo);
            line-height: 1;
        }

        .score-lbl {
            font-size: 11px;
            color: #9CA3AF;
            margin-top: 3px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .stat-correct {
            color: var(--green);
        }

        .stat-wrong {
            color: var(--red);
        }

        .score-bar {
            height: 10px;
            background: #E8E8F0;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 10px;
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

        /* ── Submit ── */
        .submit-wrap {
            text-align: center;
            margin-top: 10px;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--indigo), #8B5CF6);
            color: #fff;
            border: none;
            padding: 14px 48px;
            font-size: 16px;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            letter-spacing: .3px;
            box-shadow: 0 4px 16px rgba(99, 102, 241, .3);
            transition: transform .15s, box-shadow .15s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(99, 102, 241, .4);
        }
    </style>
</head>

<body>

    <nav id="navbar" class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a id="title" class="navbar-brand" href="#">CompileX</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Chapter
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Chap 1</a></li>
                            <li><a class="dropdown-item" href="#">Chap 2</a></li>
                            <li><a class="dropdown-item" href="#">Chap 3</a></li>
                            <li><a class="dropdown-item" href="#">Chap 4</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="quiz.php">Quiz</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="leaderboard.php">Leaderboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
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
        <h1>Chapter 4 &mdash; <span>True / False</span> Quiz</h1>
        <p>10 questions based on Section 4.0 (Relations &amp; Closure), 4.1 (Simple Grammars), 4.2 (Quasi-Simple Grammars), 4.3 (LL(1) Grammars), and 4.4 (Parsing Arithmetic Expressions). Select True or False for each question, then submit.</p>
    </div>

    <div class="page-layout">

        <!-- ── Left nav ── -->
        <nav class="q-nav" id="qNav">
            <div class="q-nav-label">Question</div>
            <?php for ($i = 0; $i < $total; $i++):
                $navClass = 'q-nav-btn';
                if ($submitted && isset($results[$i])) {
                    $r = $results[$i];
                    if (!$r['answered'])   $navClass .= ' nav-skipped';
                    elseif ($r['correct']) $navClass .= ' nav-correct';
                    else                   $navClass .= ' nav-wrong';
                }
            ?>
                <a href="#q-<?= $i ?>" class="<?= $navClass ?>" id="nav-btn-<?= $i ?>"><?= $i + 1 ?></a>
            <?php endfor; ?>
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
                    <div class="score-bar">
                        <div class="score-bar-fill" style="width:<?= $pct ?>%"></div>
                    </div>
                    <div class="score-msg">
                        <?php
                        if ($pct === 100)    echo '🏆 Perfect score! You have mastered Chapter 4!';
                        elseif ($pct >= 80)  echo '🎉 Great job! Strong grasp of top down parsing concepts.';
                        elseif ($pct >= 60)  echo '👍 Good effort — review the explanations below.';
                        else                 echo '📖 Keep studying! Re-read Sections 4.0 through 4.4 and try again.';
                        ?>
                    </div>
                    <a class="btn-retry" href="<?= $_SERVER['PHP_SELF'] ?>">↺ Try Again</a>
                </div>
            <?php endif; ?>

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
                    <div id="q-<?= $i ?>" class="q-card color-<?= $q['color'] ?> <?= $cls ?>">

                        <?php if ($submitted && $r): ?>
                            <?php if (!$r['answered']): ?>
                                <span class="result-badge badge-skipped">⚪ Skipped</span>
                            <?php elseif ($r['correct']): ?>
                                <span class="result-badge badge-correct">✔ Correct</span>
                            <?php else: ?>
                                <span class="result-badge badge-wrong">✘ Incorrect — Answer: <?= $q['answer'] ? 'True' : 'False' ?></span>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="q-num">Question <?= $i + 1 ?> of <?= $total ?></div>
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

        </div><!-- end quiz-wrap -->
    </div><!-- end page-layout -->

    <script>
        const navBtns = document.querySelectorAll('.q-nav-btn');
        const cards = document.querySelectorAll('.q-card');

        // Highlight nav btn on scroll
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

        // Mark nav as answered when a radio is clicked
        document.querySelectorAll('input[type=radio]').forEach(radio => {
            radio.addEventListener('change', () => {
                const name = radio.getAttribute('name'); // "qN"
                const idx = name.replace('q', '');
                const btn = document.getElementById('nav-btn-' + idx);
                if (btn && !btn.classList.contains('nav-correct') && !btn.classList.contains('nav-wrong')) {
                    btn.classList.add('nav-answered');
                }
            });
        });
    </script>

</body>

</html>