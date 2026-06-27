<?php
session_start();
include '../dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

/*
Quiz IDs:

Chapter 1: Q001 Easy, Q002 Normal, Q003 Hard
Chapter 2: Q004 Easy, Q005 Normal, Q006 Hard
Chapter 3: Q007 Easy, Q008 Normal, Q009 Hard
Chapter 4: Q010 Easy, Q011 Normal, Q012 Hard
*/

$quizIds = [
    1 => [
        'easy'   => 'Q001',
        'normal' => 'Q002',
        'hard'   => 'Q003',
    ],
    2 => [
        'easy'   => 'Q004',
        'normal' => 'Q005',
        'hard'   => 'Q006',
    ],
    3 => [
        'easy'   => 'Q007',
        'normal' => 'Q008',
        'hard'   => 'Q009',
    ],
    4 => [
        'easy'   => 'Q010',
        'normal' => 'Q011',
        'hard'   => 'Q012',
    ],
];

/* Find quizzes already completed by this user */
$completedQuizzes = [];

$stmt = $conn->prepare("
    SELECT DISTINCT quiz_id
    FROM leaderboard
    WHERE user_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $completedQuizzes[$row['quiz_id']] = true;
}

$stmt->close();

function quizCompleted(
    string $quizId,
    array $completedQuizzes
): bool {
    return isset($completedQuizzes[$quizId]);
}

function difficultyUnlocked(
    int $chapter,
    string $difficulty,
    array $quizIds,
    array $completedQuizzes
): bool {
    /* Every Easy quiz is always unlocked */
    if ($difficulty === 'easy') {
        return true;
    }

    /* Normal unlocks after completing Easy */
    if ($difficulty === 'normal') {
        return quizCompleted(
            $quizIds[$chapter]['easy'],
            $completedQuizzes
        );
    }

    /* Hard unlocks after completing Normal */
    if ($difficulty === 'hard') {
        return quizCompleted(
            $quizIds[$chapter]['normal'],
            $completedQuizzes
        );
    }

    return false;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz - CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="../css/body.css">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">


    <style>
        :root {
            --bg: #F8F7FF;
            --purple: #6366F1;
            --purple-d: #4F46E5;
            --purple-s: #EEF2FF;
            --pink: #FFD6FF;
            --text: #1E1B4B;
            --muted: #6B7280;
            --card: #FFFFFF;
            --locked-bg: #F3F4F6;
            --locked-txt: #9CA3AF;
            --easy: #10B981;
            --normal: #F59E0B;
            --hard: #EF4444;
            --radius: 18px;
            --shadow: 0 4px 28px rgba(99, 102, 241, .09);
            --shadow-h: 0 14px 40px rgba(99, 102, 241, .2);
        }

        body {
            font-family: 'Syne', sans-serif;
            background: var(--color-background-tertiary);
            margin: 0;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }



        .page-header {
            max-width: 1100px;
            margin: 0 auto;
            padding: 52px 24px 8px;
        }

        .page-header h1 {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 36px;
            letter-spacing: -1px;
            color: var(--text);
            line-height: 1.1;
        }

        .page-header p {
            color: var(--muted);
            font-size: 15px;
            margin-top: 8px;
        }

        .page-header h1 span {
            color: var(--purple);
        }

        .quiz-grid {
            max-width: 1100px;
            margin: 0 auto;
            padding: 36px 24px 80px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
        }

        .chapter-card {
            background: var(--card);
            border-radius: var(--radius);
            border: 1px solid rgba(99, 102, 241, .12);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: transform .28s ease, box-shadow .28s ease;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .chapter-card:not(.locked):hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-h);
        }

        .chapter-card.locked {
            background: var(--locked-bg);
            border-color: #E5E7EB;
            box-shadow: none;
            cursor: not-allowed;
        }

        .card-bar {
            height: 5px;
            width: 100%;
        }

        .bar-1 {
            background: linear-gradient(90deg, #6366F1, #818CF8);
        }

        .bar-2 {
            background: linear-gradient(90deg, #06B6D4, #22D3EE);
        }

        .bar-3 {
            background: linear-gradient(90deg, #F59E0B, #FBBF24);
        }

        .bar-4 {
            background: linear-gradient(90deg, #EF4444, #F87171);
        }

        .locked .card-bar {
            background: #D1D5DB;
        }

        .card-body-inner {
            padding: 24px 22px 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .chapter-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'Syne', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--purple);
            background: var(--purple-s);
            padding: 4px 10px;
            border-radius: 20px;
            margin-bottom: 14px;
            width: fit-content;
        }

        .locked .chapter-badge {
            color: var(--locked-txt);
            background: #E5E7EB;
        }

        .card-title-text {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 18px;
            color: var(--text);
            margin-bottom: 6px;
        }

        .locked .card-title-text {
            color: var(--locked-txt);
        }

        .card-desc {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.6;
            margin-bottom: 20px;
            flex: 1;
        }

        .locked .card-desc {
            color: #D1D5DB;
        }

        .diff-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .diff-btn {
            flex: 1;
            min-width: 64px;
            padding: 9px 6px;
            border: none;
            border-radius: 10px;
            font-size: 12px;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: transform .18s, box-shadow .18s, filter .18s;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            letter-spacing: .3px;
        }

        .diff-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, .15);
        }

        .diff-btn:active {
            transform: scale(.97);
        }

        .btn-easy {
            background: #D1FAE5;
            color: #065F46;
        }

        .btn-normal {
            background: #FEF3C7;
            color: #92400E;
        }

        .btn-hard {
            background: #FEE2E2;
            color: #991B1B;
        }

        .btn-easy:hover {
            background: #A7F3D0;
        }

        .btn-normal:hover {
            background: #FDE68A;
        }

        .btn-hard:hover {
            background: #FECACA;
        }

        .locked .diff-btn {
            background: #E5E7EB !important;
            color: #9CA3AF !important;
            cursor: not-allowed;
            pointer-events: none;
        }

        .lock-banner {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #F3F4F6;
            border-top: 1px solid #E5E7EB;
            padding: 10px 22px;
            font-size: 12px;
            color: var(--locked-txt);
            font-weight: 500;
        }

        .lock-icon {
            font-size: 15px;
        }

        .unlock-note {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #F0FDF4;
            border-top: 1px solid #BBF7D0;
            padding: 10px 22px;
            font-size: 12px;
            color: #065F46;
            font-weight: 500;
        }

        @keyframes riseUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chapter-card {
            opacity: 0;
            animation: riseUp .55s ease forwards;
        }

        .chapter-card:nth-child(1) {
            animation-delay: .05s;
        }

        .chapter-card:nth-child(2) {
            animation-delay: .15s;
        }

        .chapter-card:nth-child(3) {
            animation-delay: .25s;
        }

        .chapter-card:nth-child(4) {
            animation-delay: .35s;
        }

        .tooltip-wrap {
            position: relative;
            display: block;
        }

        .tooltip-wrap .tip {
            display: none;
            position: absolute;
            bottom: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%);
            background: #1E1B4B;
            color: white;
            font-size: 11px;
            padding: 6px 10px;
            border-radius: 6px;
            white-space: nowrap;
            z-index: 20;
        }

        .tooltip-wrap:hover .tip {
            display: block;
        }

        .mini-bar-wrap {
            margin-bottom: 16px;
        }

        .mini-bar-label {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: var(--muted);
            margin-bottom: 5px;
        }

        .locked .mini-bar-label {
            color: #D1D5DB;
        }

        .mini-bar {
            height: 6px;
            background: #E8E8F0;
            border-radius: 20px;
            overflow: hidden;
        }

        .mini-bar-fill {
            height: 100%;
            border-radius: 20px;
            transition: width 1.2s cubic-bezier(.4, 0, .2, 1);
        }

        .locked .mini-bar {
            background: #E5E7EB;
        }

        .locked .mini-bar-fill {
            background: #D1D5DB !important;
        }

        .diff-btn.locked-difficulty {
            background: #E5E7EB !important;
            color: #9CA3AF !important;
            cursor: not-allowed;
            pointer-events: none;
            box-shadow: none;
        }

        .diff-btn.completed-difficulty {
            box-shadow: inset 0 0 0 2px #22C55E;
        }

        .difficulty-note {
            display: flex;
            align-items: center;
            gap: 7px;
            min-height: 42px;
            padding: 10px 22px;
            border-top: 1px solid #E5E7EB;
            background: #F8FAFC;
            color: #64748B;
            font-size: 12px;
            font-weight: 600;
        }

        .difficulty-note.all-completed {
            border-color: #BBF7D0;
            background: #F0FDF4;
            color: #047857;
        }
    </style>
</head>

<body>

    <?php include 'dashboard.php'; ?>

    <div class="page-header">
        <h1>Choose Your <span>Quiz</span></h1>
        <p>Complete Easy to unlock Normal, then complete Normal to unlock Hard.</p>
    </div>

    <?php
    $chapters = [
        1 => [
            'title' => 'Introduction to Compilers',
            'desc'  => 'Tokenisation, regular expressions, and the role of a scanner in compilation.',
            'bar'   => 'bar-1',
        ],
        2 => [
            'title' => 'Syntax Analysis',
            'desc'  => 'Context-free grammars, parse trees, and top-down vs bottom-up parsing.',
            'bar'   => 'bar-2',
        ],
        3 => [
            'title' => 'Semantic Analysis',
            'desc'  => 'Type checking, symbol tables, and enforcing language semantics.',
            'bar'   => 'bar-3',
        ],
        4 => [
            'title' => 'Top Down Parsing',
            'desc'  => 'Intermediate code, optimisation strategies, and target machine code.',
            'bar'   => 'bar-4',
        ],
    ];

    $difficulties = [
        'easy'   => ['label' => 'Easy',   'file' => 'easy.php'],
        'normal' => ['label' => 'Normal', 'file' => 'norm.php'],
        'hard'   => ['label' => 'Hard',   'file' => 'hard.php'],
    ];

    $bar_colors = [
        1 => '#6366F1',
        2 => '#06B6D4',
        3 => '#F59E0B',
        4 => '#EF4444',
    ];
    ?>

    <div class="quiz-grid">
    <?php foreach ($chapters as $ch => $info): ?>
        <?php
        $easyCompleted = quizCompleted(
            $quizIds[$ch]['easy'],
            $completedQuizzes
        );

        $normalCompleted = quizCompleted(
            $quizIds[$ch]['normal'],
            $completedQuizzes
        );

        $hardCompleted = quizCompleted(
            $quizIds[$ch]['hard'],
            $completedQuizzes
        );

        if ($hardCompleted) {
            $quizProgress = 100;
        } elseif ($normalCompleted) {
            $quizProgress = 67;
        } elseif ($easyCompleted) {
            $quizProgress = 33;
        } else {
            $quizProgress = 0;
        }
        ?>

        <div class="chapter-card">
            <div class="card-bar <?= htmlspecialchars($info['bar']) ?>"></div>

            <div class="card-body-inner">
                <span class="chapter-badge">
                    Chapter <?= $ch ?>
                </span>

                <div class="card-title-text">
                    <?= htmlspecialchars($info['title']) ?>
                </div>

                <div class="card-desc">
                    <?= htmlspecialchars($info['desc']) ?>
                </div>

                <div class="mini-bar-wrap">
                    <div class="mini-bar-label">
                        <span>Quiz progress</span>
                        <span><?= $quizProgress ?>%</span>
                    </div>

                    <div class="mini-bar">
                        <div
                            class="mini-bar-fill"
                            style="
                                width: <?= $quizProgress ?>%;
                                background: <?= $bar_colors[$ch] ?>;
                            ">
                        </div>
                    </div>
                </div>

                <div class="diff-group">
                    <?php foreach ($difficulties as $level => $difficulty): ?>
                        <?php
                        $unlocked = difficultyUnlocked(
                            $ch,
                            $level,
                            $quizIds,
                            $completedQuizzes
                        );

                        $completed = quizCompleted(
                            $quizIds[$ch][$level],
                            $completedQuizzes
                        );

                        $href = "ch{$ch}/{$difficulty['file']}";

                        $buttonClass =
                            'diff-btn btn-' . $level;

                        if (!$unlocked) {
                            $buttonClass .= ' locked-difficulty';
                        }

                        if ($completed) {
                            $buttonClass .= ' completed-difficulty';
                        }

                        if ($level === 'normal') {
                            $lockedMessage =
                                'Complete Easy first';
                        } elseif ($level === 'hard') {
                            $lockedMessage =
                                'Complete Normal first';
                        } else {
                            $lockedMessage = '';
                        }
                        ?>

                        <div class="tooltip-wrap">
                            <?php if (!$unlocked): ?>
                                <span class="tip">
                                    <?= htmlspecialchars($lockedMessage) ?>
                                </span>
                            <?php endif; ?>

                            <a
                                href="<?= $unlocked
                                    ? htmlspecialchars($href)
                                    : '#' ?>"
                                class="<?= htmlspecialchars($buttonClass) ?>"
                                <?= !$unlocked
                                    ? 'tabindex="-1" aria-disabled="true"'
                                    : '' ?>>

                                <?= $completed ? '✓ ' : '' ?>
                                <?= htmlspecialchars($difficulty['label']) ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if ($hardCompleted): ?>
                <div class="difficulty-note all-completed">
                    ✓ All difficulties completed
                </div>
            <?php elseif ($normalCompleted): ?>
                <div class="difficulty-note">
                    Hard quiz unlocked
                </div>
            <?php elseif ($easyCompleted): ?>
                <div class="difficulty-note">
                    Normal quiz unlocked
                </div>
            <?php else: ?>
                <div class="difficulty-note">
                    Start with the Easy quiz
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

</body>

</html>