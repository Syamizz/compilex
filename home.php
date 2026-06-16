<?php
session_start();
include 'dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];

$userProgress = [];


$stmt = $conn->prepare("
    SELECT
        p.prog_id,
        p.chapter,
        p.pages AS total_pages,
        COALESCE(up.current_page, 0) AS current_page,
        COALESCE(up.percentage, 0) AS percentage,
        COALESCE(up.status, 'Not Started') AS status,
        up.updated_at
    FROM progress p
    LEFT JOIN users_progress up
        ON p.prog_id = up.prog_id
        AND up.user_id = ?
    ORDER BY p.chapter ASC
");

$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $userProgress[(int)$row['chapter']] = [
        'prog_id'      => (int)$row['prog_id'],
        'total_pages'  => (int)$row['total_pages'],
        'current_page' => (int)$row['current_page'],
        'percentage'   => (float)$row['percentage'],
        'status'       => $row['status'],
        'updated_at'   => $row['updated_at'],
    ];
}

$stmt->close();


$chapters = [
    [
        'id'       => 'ch1',
        'chapter'  => 1,
        'title'    => 'Chapter 1 — Introduction to Compilers',
        'href'     => 'chapter1/c1.php',
        'color'    => '#10B981',
        'colorBg'  => '#D1FAE5',
        'colorTxt' => '#065F46',
        'barClass' => 'green',
        'topics'   => [
            ['label' => '1.1.0 What is a Compiler?', 'href' => 'chapter1/c1_1_0.php'],
            ['label' => '1.1.1 Source & Object Programs', 'href' => 'chapter1/c1_1_1.php'],
            ['label' => '1.1.2 Compiler vs Interpreter', 'href' => 'chapter1/c1_1_2.php'],
            ['label' => '1.1.3 Compile-time vs Run-time Errors', 'href' => 'chapter1/c1_1_3.php'],
            ['label' => '1.1.4 Big-C Notation', 'href' => 'chapter1/c1_1_4.php'],
            ['label' => '1.2.0 The Phases of a Compiler', 'href' => 'chapter1/c1_2_0.php'],
            ['label' => '1.2.1 Lexical Analysis (Scanner)', 'href' => 'chapter1/c1_2_1.php'],
            ['label' => '1.2.2 Syntax Analysis Phase', 'href' => 'chapter1/c1_2_2.php'],
            ['label' => '1.2.3 Global Optimization', 'href' => 'chapter1/c1_2_3.php'],
            ['label' => '1.2.4 Code Generation', 'href' => 'chapter1/c1_2_4.php'],
            ['label' => '1.2.5 Local Optimization', 'href' => 'chapter1/c1_2_5.php'],
            ['label' => '1.3 Implementation Techniques', 'href' => 'chapter1/c1_3_0.php'],
            ['label' => '1.3.1 Bootstrapping', 'href' => 'chapter1/c1_3_1.php'],
            ['label' => '1.3.2 Cross Compiling', 'href' => 'chapter1/c1_3_2.php'],
            ['label' => '1.3.3 Compiling to Intermediate Form', 'href' => 'chapter1/c1_3_3.php'],
            ['label' => '1.3.4 Compiler-Compilers', 'href' => 'chapter1/c1_3_4.php'],
            ['label' => '1.4 Case Study: Decaf', 'href' => 'chapter1/c1_4_0.php'],
            ['label' => '1.4.1 Decaf Features vs Java', 'href' => 'chapter1/c1_4_1.php'],
            ['label' => '1.4.2 The Cosine Program', 'href' => 'chapter1/c1_4_2.php'],
            ['label' => '1.4.3 The Mini Target Machine', 'href' => 'chapter1/c1_4_3.php'],
            ['label' => '1.4.4 Valid Decaf Program Segments', 'href' => 'chapter1/c1_4_4.php'],
        ],
    ],
    [
        'id'       => 'ch2',
        'chapter'  => 2,
        'title'    => 'Chapter 2 — Lexical Analysis',
        'href'     => 'chapter2/c2.php',
        'color'    => '#06B6D4',
        'colorBg'  => '#CFFAFE',
        'colorTxt' => '#164E63',
        'barClass' => 'blue',
        'topics'   => [
            ['label' => '2.0.1 Language Elements', 'href' => 'chapter2/c2_0_1.php'],
            ['label' => '2.0.2 Finite State Machines', 'href' => 'chapter2/c2_0_2.php'],
            ['label' => '2.0.3 Regular Expressions', 'href' => 'chapter2/c2_0_3.php'],
            ['label' => '2.1.1 The Nine Token Classes', 'href' => 'chapter2/c2_1_1.php'],
            ['label' => '2.1.2 The Token Stream', 'href' => 'chapter2/c2_1_2.php'],
            ['label' => '2.1.3 Numeric Constants — Conversion', 'href' => 'chapter2/c2_1_3.php'],
            ['label' => '2.2.0 Implementation with Finite State Machines', 'href' => 'chapter2/c2_2_0.php'],
            ['label' => '2.2.1 FSM Examples for Lexical Analysis', 'href' => 'chapter2/c2_2_1.php'],
            ['label' => '2.2.2 Actions for Finite State Machines', 'href' => 'chapter2/c2_2_2.php'],
            ['label' => '2.3 Bottom-Up Parsing', 'href' => 'chapter2/c2_3_0.php'],
            ['label' => '2.3.1 Sequential Search', 'href' => 'chapter2/c2_3_1.php'],
            ['label' => '2.3.2 Binary Search Tree', 'href' => 'chapter2/c2_3_2.php'],
            ['label' => '2.3.3 Hash Table', 'href' => 'chapter2/c2_3_3.php'],
            ['label' => '2.4 Lexical Analysis with SableCC', 'href' => 'chapter2/c2_4_0.php'],
            ['label' => '2.4.1 SableCC Grammar File Structure', 'href' => 'chapter2/c2_4_1.php'],
            ['label' => '2.4.2 Running SableCC', 'href' => 'chapter2/c2_4_2.php'],
            ['label' => '2.5 Case Study: Lexical Analysis for Decaf', 'href' => 'chapter2/c2_5_0.php'],
            ['label' => '2.5.1 The Helpers Section', 'href' => 'chapter2/c2_5_1.php'],
            ['label' => '2.5.2 The Tokens Section', 'href' => 'chapter2/c2_5_2.php'],
        ],
    ],
    [
        'id'       => 'ch3',
        'chapter'  => 3,
        'title'    => 'Chapter 3 — Syntax Analysis',
        'href'     => 'chapter3/c3.php',
        'color'    => '#F59E0B',
        'colorBg'  => '#FEF3C7',
        'colorTxt' => '#92400E',
        'barClass' => 'yellow',
        'topics'   => [
            ['label' => '3.0.1 Grammars', 'href' => 'chapter3/c3_0_1.php'],
            ['label' => '3.0.2 Classes of Grammars', 'href' => 'chapter3/c3_0_2.php'],
            ['label' => '3.0.3 Context-Free Grammars', 'href' => 'chapter3/c3_0_3.php'],
            ['label' => '3.0.4 Pushdown Machines', 'href' => 'chapter3/c3_0_4.php'],
            ['label' => '3.0.5 Machines & Languages', 'href' => 'chapter3/c3_0_5.php'],
            ['label' => '3.1 Ambiguities in Programming Language', 'href' => 'chapter3/c3_1_0.php'],
            ['label' => '3.1 G5 - Unambiguous Expressions', 'href' => 'chapter3/c3_1_1.php'],
            ['label' => '3.1 G6 - Dangling Else (Ambiguous)', 'href' => 'chapter3/c3_1_2.php'],
            ['label' => '3.1 G7 - Unambiguous If-Else', 'href' => 'chapter3/c3_1_3.php'],
            ['label' => '3.2 The Parsing Problem - Part 1', 'href' => 'chapter3/c3_2_0.php'],
            ['label' => '3.2 The Parsing Problem - Part 2', 'href' => 'chapter3/c3_2_1.php'],
            ['label' => '3.2 The Parsing Problem - Part 3', 'href' => 'chapter3/c3_2_2.php'],

        ],
    ],
    [
        'id'       => 'ch4',
        'chapter'  => 4,
        'title'    => 'Chapter 4 — Top Down Parsing',
        'href'     => 'chapter4/c4_0.php',
        'color'    => '#EF4444',
        'colorBg'  => '#FEE2E2',
        'colorTxt' => '#991B1B',
        'barClass' => 'red',
        'topics'   => [
            ['label' => '4.0 What is Top Down Parsing?', 'href' => 'chapter4/c4_0_0.php'],
            ['label' => '4.0.1 Relations & 4.0.2 Reflexive Transitive Closure', 'href' => 'chapter4/c4_0_1.php'],
            ['label' => '4.0.1 Relations & 4.0.3 Visualing Relations', 'href' => 'chapter4/c4_0_2.php'],
            ['label' => '4.1 Simple Grammars (Part 1)', 'href' => 'chapter4/c4_1_0.php'],
            ['label' => '4.1 Simple Grammars (Part 2)', 'href' => 'chapter4/c4_1_1.php'],
            ['label' => '4.1 Simple Grammars (Part 3)', 'href' => 'chapter4/c4_1_2.php'],
            ['label' => '4.2 Quasi-Simple Grammars (Part 1)', 'href' => 'chapter4/c4_2_0.php'],
            ['label' => '4.2 Quasi-Simple Grammars (Part 2)', 'href' => 'chapter4/c4_2_1.php'],
            ['label' => '4.2 Quasi-Simple Grammars (Part 3)', 'href' => 'chapter4/c4_2_2.php'],
            ['label' => '4.3 LL(1) Grammars (Part 1)', 'href' => 'chapter4/c4_3_0.php'],
            ['label' => '4.3 LL(1) Grammars (Part 2)', 'href' => 'chapter4/c4_3_1.php'],
            ['label' => '4.4 Parsing Arithmetic Expressions Top Down (Part 1)', 'href' => 'chapter4/c4_4_0.php'],
            ['label' => '4.4 Parsing Arithmetic Expressions Top Down (Part 2)', 'href' => 'chapter4/c4_4_1.php'],
            ['label' => '4.4 Parsing Arithmetic Expressions Top Down (Part 3)', 'href' => 'chapter4/c4_4_2.php'],
            ['label' => '4.5 Syntax-Directed Translation', 'href' => 'chapter4/c4_5_0.php'],
            ['label' => '4.5.1 Implementing Translation Grammars with Pushdown Translators', 'href' => 'chapter4/c4_5_1.php'],
            ['label' => '4.5.2 Implementing Translation Grammars with Recursive Descent', 'href' => 'chapter4/c4_5_2.php'],
            ['label' => '4.6 Attributed Grammars', 'href' => 'chapter4/c4_6_0.php'],
            ['label' => '4.6.1 Implementing Attributed Grammars with Recursive Descent ', 'href' => 'chapter4/c4_6_1.php'],
            ['label' => '4.7 An Attributed Translation Grammar for Expressions', 'href' => 'chapter4/c4_7_0.php'],
            ['label' => '4.7.1 Translating Expressions with Recursive Descent', 'href' => 'chapter4/c4_7_1.php'],
        ],
    ],
];

foreach ($chapters as &$ch) {
    $totalTopics = count($ch['topics']);
    $chapterNo = $ch['chapter'];

    $dbProgress = $userProgress[$chapterNo] ?? null;

    if ($dbProgress) {
        $totalPages = max(1, (int)$dbProgress['total_pages']);
        $currentPage = max(0, (int)$dbProgress['current_page']);

        $completed = max(0, min($currentPage, $totalTopics));

        $pct = (float)$dbProgress['percentage'];

        if ($pct <= 0 && $currentPage > 0) {
            $pct = ($currentPage / $totalPages) * 100;
        }

        $pct = max(0, min($pct, 100));
        $status = $dbProgress['status'];
    } else {
        $completed = 0;
        $pct = 0;
        $status = 'Not Started';
    }

    $isCompleted = strtolower($status) === 'completed' || $pct >= 100;

    $ch['pct'] = round($pct);
    $ch['status'] = $isCompleted ? 'Completed' : $status;
    $ch['isCompleted'] = $isCompleted;
    $ch['locked'] = false;

    $idx = $completed >= $totalTopics ? $totalTopics - 1 : $completed;
    $idx = max(0, $idx);

    $ch['currentTopic'] = $idx;
    $ch['currentLabel'] = $ch['topics'][$idx]['label'];
    $ch['currentHref']  = $ch['topics'][$idx]['href'];

    $nextIdx = $idx + 1;
    $ch['nextLabel'] = $nextIdx < $totalTopics ? $ch['topics'][$nextIdx]['label'] : null;
    $ch['nextHref']  = $nextIdx < $totalTopics ? $ch['topics'][$nextIdx]['href'] : null;

    $ch['doneTopics'] = array_slice($ch['topics'], 0, $completed);
}

unset($ch);

$leaderboard = [];

$stmt = $conn->prepare("
    SELECT
        u.user_id,
        u.username,
        SUM(best_scores.scorepoint) AS total_score
    FROM users u
    INNER JOIN (
        SELECT
            user_id,
            board_chap,
            LOWER(quiz_type) AS quiz_type,
            MAX(scorepoint) AS scorepoint
        FROM leaderboard
        GROUP BY user_id, board_chap, LOWER(quiz_type)
    ) best_scores
        ON best_scores.user_id = u.user_id
    GROUP BY u.user_id, u.username
    ORDER BY total_score DESC
    LIMIT 5
");

$stmt->execute();
$result = $stmt->get_result();

$rank = 1;

while ($row = $result->fetch_assoc()) {
    $leaderboard[] = [
        'rank'  => $rank,
        'name'  => $row['username'],
        'score' => (int)$row['total_score'],
    ];

    $rank++;
}

$stmt->close();


$quizLevels = [
    'easy' => ['label' => 'Easy', 'class' => 'easy'],
    'normal' => ['label' => 'Normal', 'class' => 'normal'],
    'hard' => ['label' => 'Hard', 'class' => 'hard'],
];

$quizProgress = [];
$totalQuizCount = 0;
$answeredQuizCount = 0;

for ($chapter = 1; $chapter <= 4; $chapter++) {
    foreach ($quizLevels as $level => $info) {
        $quizProgress[$chapter][$level] = [
            'answered' => false,
            'score' => null,
        ];
        $totalQuizCount++;
    }
}

$stmt = $conn->prepare("
    SELECT
        board_chap,
        LOWER(quiz_type) AS quiz_type,
        MAX(scorepoint) AS scorepoint
    FROM leaderboard
    WHERE user_id = ?
    GROUP BY board_chap, LOWER(quiz_type)
");

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $chapter = (int)$row['board_chap'];
    $level = strtolower($row['quiz_type']);

    if (isset($quizProgress[$chapter][$level])) {
        $quizProgress[$chapter][$level]['answered'] = true;
        $quizProgress[$chapter][$level]['score'] = (int)$row['scorepoint'];
        $answeredQuizCount++;
    }
}

$stmt->close();

$notAnsweredQuizCount = $totalQuizCount - $answeredQuizCount;

$hasAnySavedProgress = false;
$allChapterProgressZero = true;

foreach ($userProgress as $progress) {
    $percentage = (float)($progress['percentage'] ?? 0);
    $currentPage = (int)($progress['current_page'] ?? 0);
    $status = strtolower($progress['status'] ?? 'not started');

    if (
        !empty($progress['updated_at']) ||
        $currentPage > 0 ||
        $percentage > 0 ||
        $status !== 'not started'
    ) {
        $hasAnySavedProgress = true;
    }

    if ($currentPage > 0 || $percentage > 0) {
        $allChapterProgressZero = false;
    }
}

$showTutorial = !$hasAnySavedProgress || $allChapterProgressZero;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ezComp – Learn Compiler Principles</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/body.css">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --indigo: #6366F1;
            --indigo-dark: #4F46E5;
            --indigo-light: #EEF2FF;
        }

        body {
            font-family: 'Syne', sans-serif;
            background: var(--color-background-tertiary);
            margin: 0;
        }

        .page-wrap {
            max-width: 1500px;
            margin: auto;
            padding: 52px 32px 80px;
        }

        .section-heading {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--color-text-secondary);
            margin-bottom: 18px;
        }

        .progress-card {
            background: var(--color-background-primary);
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: 16px;
            margin-bottom: 14px;
            overflow: hidden;
            transition: box-shadow 0.22s ease, border-color 0.22s ease, transform 0.22s ease;
            position: relative;
        }

        .progress-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(99, 102, 241, 0.10);
            border-color: var(--indigo);
        }

        .progress-card.locked {
            opacity: 0.65;
        }

        .progress-card.locked:hover {
            transform: none;
            box-shadow: none;
            border-color: var(--color-border-tertiary);
        }

        .card-accent {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            border-radius: 16px 0 0 16px;
            transition: width 0.22s ease;
        }

        .progress-card:hover .card-accent {
            width: 6px;
        }

        .card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 20px 0 24px;
            gap: 12px;
        }

        .chapter-link {
            font-size: 14px;
            font-weight: 600;
            color: var(--color-text-primary);
            text-decoration: none;
            flex: 1;
            min-width: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chapter-link:hover {
            color: var(--indigo);
        }

        .locked-link {
            color: var(--color-text-secondary);
            cursor: not-allowed;
        }

        .pct-badge {
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            flex-shrink: 0;
        }

        .bar-track {
            margin: 10px 24px 0;
            height: 5px;
            background: var(--color-background-secondary);
            border-radius: 10px;
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            border-radius: 10px;
            transition: width 1s cubic-bezier(.4, 0, .2, 1);
        }

        .card-current {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 12px 24px 0;
            padding: 9px 12px;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.18s;
        }

        .card-current:hover {
            background: var(--color-background-secondary);
        }

        .locked-current {
            cursor: not-allowed;
            background: var(--color-background-secondary);
        }

        .locked-current:hover {
            background: var(--color-background-secondary);
        }

        .current-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
            animation: blink 1.6s ease-in-out infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .35;
            }
        }

        .current-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            flex-shrink: 0;
        }

        .current-topic-name {
            font-size: 13px;
            font-weight: 500;
            color: var(--color-text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .continue-arrow {
            margin-left: auto;
            font-size: 13px;
            flex-shrink: 0;
            opacity: 0;
            transform: translateX(-4px);
            transition: opacity .18s, transform .18s;
        }

        .card-current:hover .continue-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        .card-expand-toggle {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 8px 24px 0;
            padding: 5px 0;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 12px;
            color: var(--color-text-secondary);
            font-family: inherit;
            transition: color .18s;
        }

        .card-expand-toggle:hover {
            color: var(--indigo);
        }

        .expand-chevron {
            display: inline-block;
            transition: transform .25s ease;
            font-style: normal;
            font-size: 10px;
            line-height: 1;
        }

        .card-expand-toggle.open .expand-chevron {
            transform: rotate(180deg);
        }

        .completed-list {
            display: none;
            margin: 4px 24px 0;
            border-radius: 10px;
            overflow: hidden;
            border: 0.5px solid var(--color-border-tertiary);
        }

        .completed-list.open {
            display: block;
        }

        .done-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            font-size: 13px;
            color: var(--color-text-secondary);
            text-decoration: none;
            border-bottom: 0.5px solid var(--color-border-tertiary);
            transition: background .15s, color .15s;
        }

        .done-item:last-child {
            border-bottom: none;
        }

        .done-item:hover {
            background: var(--color-background-secondary);
            color: var(--color-text-primary);
        }

        .done-check {
            font-size: 13px;
            flex-shrink: 0;
        }

        .card-next {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 8px 24px 0;
            padding: 7px 12px;
            border-radius: 10px;
            border: 0.5px dashed var(--color-border-secondary);
            text-decoration: none;
            transition: border-color .18s, background .18s;
        }

        .card-next:hover {
            border-color: var(--indigo);
            background: var(--indigo-light);
        }

        .next-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--color-text-secondary);
            flex-shrink: 0;
        }

        .next-name {
            font-size: 12px;
            color: var(--color-text-secondary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-next:hover .next-name {
            color: var(--indigo);
        }

        .card-bottom-pad {
            height: 16px;
        }

        .leaderboard-card {
            background: var(--color-background-primary);
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: 16px;
            overflow: hidden;
        }

        .lb-table {
            width: 100%;
            border-collapse: collapse;
        }

        .lb-table thead tr {
            background: var(--indigo);
        }

        .lb-table th {
            color: #fff;
            padding: 13px 16px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .4px;
            text-align: center;
        }

        .lb-table td {
            padding: 12px 16px;
            text-align: center;
            font-size: 13px;
            border-bottom: 0.5px solid var(--color-border-tertiary);
            color: var(--color-text-primary);
            transition: background .15s;
        }

        .lb-table tr:last-child td {
            border-bottom: none;
        }

        .lb-table tbody tr:hover td {
            background: var(--indigo-light);
        }

        .rank-medal {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            font-size: 12px;
            font-weight: 600;
        }

        .rank-1 .rank-medal {
            background: #FEF9C3;
            color: #713F12;
            border: 1px solid #FCD34D;
        }

        .rank-2 .rank-medal {
            background: #F1F5F9;
            color: #334155;
            border: 1px solid #CBD5E1;
        }

        .rank-3 .rank-medal {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #D97706;
        }

        .rank-n .rank-medal {
            background: var(--color-background-secondary);
            color: var(--color-text-secondary);
            border: 0.5px solid var(--color-border-secondary);
        }

        .score-pill {
            display: inline-block;
            background: var(--indigo-light);
            color: var(--indigo-dark);
            font-weight: 600;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        .bar-fill.green {
            background: linear-gradient(90deg, #10B981, #34D399);
        }

        .bar-fill.blue {
            background: linear-gradient(90deg, #06B6D4, #22D3EE);
        }

        .bar-fill.yellow {
            background: linear-gradient(90deg, #F59E0B, #FCD34D);
        }

        .bar-fill.red {
            background: linear-gradient(90deg, #EF4444, #FCA5A5);
        }

        footer {
            text-align: center;
            padding: 28px;
            color: var(--color-text-secondary);
            font-size: 12px;
            border-top: 0.5px solid var(--color-border-tertiary);
            margin-top: 10px;
        }

        @media (max-width: 700px) {
            .main-grid {
                grid-template-columns: 1fr !important;
            }
        }

        /* Badges style */
        .badges-card {
            background: white;
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: 16px;
            overflow: hidden;
        }

        .badges-card-header {
            background: linear-gradient(135deg, #e6e9f5 0%, #cfd9f2 50%, #b8c6ec 100%);
            padding: 14px 20px;
            border-bottom: 0.5px solid rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .badges-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            padding: 18px;
            background: linear-gradient(135deg, #e6e9f5 0%, #cfd9f2 50%, #b8c6ec 100%);
        }

        @media (max-width: 500px) {
            .badges-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .badge-icon-img,
            .badge-icon-img-wrap {
                width: 80px;
                height: 80px;
            }
        }

        .badge-slot {

            padding: 18px 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            position: relative;
            transition: background 0.18s;
        }

        .badge-icon-img-wrap {
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;
            background: rgba(255, 255, 255, 0.55);
            box-shadow:
                0 10px 22px rgba(0, 0, 0, 0.18),
                inset 0 2px 4px rgba(255, 255, 255, 0.7);
            transform: translateY(-4px);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .badge-icon-img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            filter: drop-shadow(0 8px 8px rgba(0, 0, 0, 0.22));
        }

        .badge-slot:hover .badge-icon-img-wrap {
            transform: translateY(-8px) scale(1.06);
            box-shadow:
                0 16px 30px rgba(0, 0, 0, 0.24),
                inset 0 2px 5px rgba(255, 255, 255, 0.85);
        }


        .badge-earned-tick {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #10B981;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge-name {
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            color: var(--color-text-primary);
            line-height: 1.3;
        }

        .badge-desc {
            font-size: 10px;
            text-align: center;
            color: var(--color-text-secondary);
            line-height: 1.3;
        }

        /* Badges locked */
        .badge-locked {
            opacity: 0.65;
        }

        .locked-slot {
            background: rgba(255, 255, 255, 0.35);
            border: 2px dashed rgba(80, 80, 80, 0.25);
            box-shadow:
                inset 0 2px 8px rgba(0, 0, 0, 0.08),
                0 6px 14px rgba(0, 0, 0, 0.10);
        }

        .locked-question {
            font-size: 48px;
            font-weight: 800;
            color: rgba(80, 80, 80, 0.35);
        }

        .badge-locked .badge-name {
            color: rgba(60, 60, 60, 0.55);
        }

        .badge-locked .badge-desc {
            color: rgba(60, 60, 60, 0.45);
        }

        .completed-current {
            background: linear-gradient(135deg, #ECFDF5, #D1FAE5);
            border: 0.5px solid #A7F3D0;
        }

        .completed-current:hover {
            background: linear-gradient(135deg, #D1FAE5, #A7F3D0);
        }

        .completed-label {
            color: #047857;
        }

        .completed-topic-name {
            color: #065F46;
            font-weight: 700;
        }

        .completed-check {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #10B981;
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }

        .review-arrow {
            margin-left: auto;
            font-size: 12px;
            color: #047857;
            opacity: 0;
            transform: translateX(-4px);
            transition: opacity .18s, transform .18s;
        }

        .completed-current:hover .review-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        /* quiz progress */
        .quiz-progress-card {
            background: var(--color-background-primary);
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 28px;
        }

        .quiz-progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 18px;
            background: var(--indigo-light);
            border-bottom: 0.5px solid var(--color-border-tertiary);
        }

        .quiz-progress-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--color-text-primary);
        }

        .quiz-progress-count {
            font-size: 12px;
            font-weight: 700;
            color: var(--indigo-dark);
        }

        .quiz-summary-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            padding: 14px 18px 4px;
        }

        .quiz-summary-box {
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 12px;
            font-weight: 700;
        }

        .quiz-summary-box.done {
            background: #D1FAE5;
            color: #065F46;
        }

        .quiz-summary-box.todo {
            background: #FEE2E2;
            color: #991B1B;
        }

        .quiz-chapter-list {
            padding: 12px 18px 18px;
            display: grid;
            gap: 12px;
        }

        .quiz-chapter-block {
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: 12px;
            padding: 12px;
        }

        .quiz-chapter-title {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--color-text-primary);
        }

        .quiz-level-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .quiz-level-pill {
            border-radius: 10px;
            padding: 9px 8px;
            text-align: center;
            font-size: 11px;
            font-weight: 700;
            border: 0.5px solid transparent;
        }

        .quiz-level-pill.answered {
            background: #ECFDF5;
            color: #047857;
            border-color: #A7F3D0;
        }

        .quiz-level-pill.unanswered {
            background: #F9FAFB;
            color: #9CA3AF;
            border-color: #E5E7EB;
        }

        .quiz-score {
            display: block;
            margin-top: 3px;
            font-size: 10px;
            font-weight: 600;
        }

        /* css for up next*/
        .up-next-list {
            cursor: default;
        }

        .up-next-list:hover {
            border-color: var(--color-border-secondary);
            background: transparent;
        }

        .tutorial-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            z-index: 9990;
            display: none;
        }

        .tutorial-backdrop.active {
            display: block;
        }

        .tutorial-highlight {
            position: relative;
            z-index: 9992;
            box-shadow: 0 0 0 4px #ffffff, 0 0 0 9px #6366F1, 0 18px 45px rgba(0, 0, 0, 0.25);
            border-radius: 14px;
        }

        .tutorial-box {
            position: fixed;
            z-index: 9993;
            width: min(420px, calc(100vw - 32px));
            background: #ffffff;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.28);
            display: none;
        }

        .tutorial-box.active {
            display: block;
        }

        .tutorial-step-count {
            font-size: 12px;
            font-weight: 700;
            color: #6366F1;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 8px;
        }

        .tutorial-title {
            font-size: 20px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 8px;
        }

        .tutorial-text {
            font-size: 14px;
            line-height: 1.6;
            color: #374151;
            margin-bottom: 18px;
        }

        .tutorial-actions {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .tutorial-btn {
            border: none;
            border-radius: 999px;
            padding: 9px 18px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .tutorial-btn.secondary {
            background: #F3F4F6;
            color: #374151;
        }

        .tutorial-btn.primary {
            background: #6366F1;
            color: white;
        }
    </style>
</head>

<body>

    <div id="tutorial-navbar">
        <?php include 'dashboard.php'; ?>
    </div>

    <!-- check badges for users -->
    <?php include 'check_badges/badge_1.php'; ?>
    <?php include 'check_badges/badge_2.php'; ?>
    <?php include 'check_badges/badge_3.php'; ?>
    <?php include 'check_badges/badge_6.php'; ?>

    <div class="page-wrap">
        <h1>Welcome back, <?php echo $username; ?></h1>
        <br><br>
        <div class="main-grid" style="display:grid; grid-template-columns:1.25fr 0.9fr 1.35fr; gap:32px; align-items:start;">

            <div id="tutorial-learning-progress">
                <div class="section-heading">Your learning progress</div>

                <?php foreach ($chapters as $ch): ?>
                    <div class="progress-card <?= $ch['locked'] ? 'locked' : '' ?>" id="card-<?= $ch['id'] ?>">

                        <div class="card-accent" style="background:<?= $ch['locked'] ? '#9CA3AF' : $ch['color'] ?>;"></div>

                        <div class="card-top">
                            <?php if ($ch['locked']): ?>
                                <span class="chapter-link locked-link"><?= htmlspecialchars($ch['title']) ?> 🔒</span>
                            <?php else: ?>
                                <a class="chapter-link" href="<?= $ch['href'] ?>"><?= htmlspecialchars($ch['title']) ?></a>
                            <?php endif; ?>

                            <span class="pct-badge" style="background:<?= $ch['locked'] ? '#F3F4F6' : $ch['colorBg'] ?>; color:<?= $ch['locked'] ? '#6B7280' : $ch['colorTxt'] ?>;">
                                <?= $ch['pct'] ?>%
                            </span>
                        </div>

                        <div class="bar-track">
                            <div class="bar-fill <?= $ch['locked'] ? '' : $ch['barClass'] ?>"
                                style="width:<?= $ch['pct'] ?>%; background:<?= $ch['locked'] ? '#9CA3AF' : '' ?>;"
                                data-width="<?= $ch['pct'] ?>"></div>
                        </div>

                        <?php if (!$ch['locked'] && !empty($ch['doneTopics'])): ?>
                            <button
                                class="card-expand-toggle"
                                onclick="toggleDone('done-<?= $ch['id'] ?>', this)"
                                aria-expanded="false">
                                <i class="expand-chevron">▼</i>
                                <?= count($ch['doneTopics']) ?> topic<?= count($ch['doneTopics']) > 1 ? 's' : '' ?> completed
                            </button>

                            <div class="completed-list" id="done-<?= $ch['id'] ?>">
                                <?php foreach ($ch['doneTopics'] as $done): ?>
                                    <a class="done-item" href="<?= $done['href'] ?>">
                                        <span class="done-check" style="color:<?= $ch['color'] ?>;">✓</span>
                                        <span><?= htmlspecialchars($done['label']) ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($ch['locked']): ?>
                            <div class="card-current locked-current">
                                <span class="current-dot" style="background:#9CA3AF;"></span>
                                <span class="current-label" style="color:#6B7280;">Locked</span>
                                <span class="current-topic-name">Complete previous chapter first</span>
                            </div>
                        <?php elseif ($ch['isCompleted']): ?>
                            <a class="card-current completed-current" href="<?= $ch['href'] ?>">
                                <span class="completed-check">✓</span>
                                <span class="current-label completed-label">Completed</span>
                                <span class="current-topic-name completed-topic-name">Chapter finished</span>
                                <span class="review-arrow">Review chapter →</span>
                            </a>
                        <?php else: ?>
                            <a class="card-current" href="<?= $ch['currentHref'] ?>">
                                <span class="current-dot" style="background:<?= $ch['color'] ?>;"></span>
                                <span class="current-label" style="color:<?= $ch['color'] ?>;">Now</span>
                                <span class="current-topic-name"><?= htmlspecialchars($ch['currentLabel']) ?></span>
                                <span class="continue-arrow" style="color:<?= $ch['color'] ?>;">Continue →</span>
                            </a>
                        <?php endif; ?>

                        <?php if (!$ch['locked'] && $ch['nextLabel']): ?>
                            <div class="card-next up-next-list">
                                <span class="next-label">Up next</span>
                                <span class="next-name"><?= htmlspecialchars($ch['nextLabel']) ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="card-bottom-pad"></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div>
                <div class="section-heading">Your quiz progress</div>

                <div class="quiz-progress-card" id="tutorial-quiz-progress">
                    <div class="quiz-progress-header">
                        <span class="quiz-progress-title">Quiz completion</span>
                        <span class="quiz-progress-count">
                            <?= $answeredQuizCount ?> / <?= $totalQuizCount ?> done
                        </span>
                    </div>

                    <div class="quiz-summary-row">
                        <div class="quiz-summary-box done">
                            Answered: <?= $answeredQuizCount ?>
                        </div>
                        <div class="quiz-summary-box todo">
                            Not answered: <?= $notAnsweredQuizCount ?>
                        </div>
                    </div>

                    <div class="quiz-chapter-list">
                        <?php for ($chapter = 1; $chapter <= 4; $chapter++): ?>
                            <div class="quiz-chapter-block">
                                <div class="quiz-chapter-title">Chapter <?= $chapter ?></div>

                                <div class="quiz-level-grid">
                                    <?php foreach ($quizLevels as $level => $info):
                                        $quiz = $quizProgress[$chapter][$level];
                                    ?>
                                        <div class="quiz-level-pill <?= $quiz['answered'] ? 'answered' : 'unanswered' ?>">
                                            <?= $quiz['answered'] ? '✓' : '○' ?>
                                            <?= htmlspecialchars($info['label']) ?>

                                            <span class="quiz-score">
                                                <?= $quiz['answered']
                                                    ? 'Score: ' . htmlspecialchars((string)$quiz['score'])
                                                    : 'Not answered'
                                                ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <div>
                <div class="section-heading">Top overall leaderboard</div>

                <div class="leaderboard-card" id="tutorial-leaderboard">
                    <table class="lb-table">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Username</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($leaderboard as $entry):
                                if ($entry['rank'] <= 3) {
                                    $rankClass = 'rank-' . $entry['rank'];
                                } else {
                                    $rankClass = 'rank-n';
                                }
                            ?>
                                <tr class="<?= $rankClass ?>">
                                    <td><span class="rank-medal"><?= $entry['rank'] ?></span></td>
                                    <td style="font-weight:500;"><?= htmlspecialchars($entry['name']) ?></td>
                                    <td><span class="score-pill"><?= $entry['score'] ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php
                $userBadges = [];

                $stmt = $conn->prepare("
    SELECT badges_id
    FROM users_badges
    WHERE user_id = ?
");
                $stmt->bind_param("i", $userId);
                $stmt->execute();

                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $userBadges[] = $row['badges_id'];
                }

                $stmt->close();


                $badges = [];

                $badgeStmt = $conn->prepare("
    SELECT badges_id, badges_name, description, icon
    FROM badges
    ORDER BY badges_id ASC
");
                $badgeStmt->execute();

                $badgeResult = $badgeStmt->get_result();

                while ($row = $badgeResult->fetch_assoc()) {
                    $isEarned = in_array($row['badges_id'], $userBadges);

                    $badges[] = [
                        'id' => $row['badges_id'],
                        'name' => $row['badges_name'],
                        'desc' => $row['description'],
                        'icon' => $row['icon'],
                        'earned' => $isEarned
                    ];
                }

                $badgeStmt->close();

                $earnedCount = count($userBadges);
                ?>


                <div class="section-heading" style="margin-top: 28px;">Badges earned</div>

                <div class="badges-card" id="tutorial-badges">
                    <div class="badges-card-header">
                        <span class="badges-card-title">Your badges</span>
                        <span class="badges-count-pill">
                            <?= $earnedCount ?> / <?= count($badges) ?> earned
                        </span>
                    </div>

                    <div class="badges-grid">
                        <?php foreach ($badges as $badge): ?>
                            <div class="badge-slot <?= $badge['earned'] ? 'badge-earned' : 'badge-locked' ?>">

                                <?php if ($badge['earned']): ?>


                                    <div class="badge-icon-img-wrap">
                                        <img
                                            src="badges/<?= htmlspecialchars($badge['icon']) ?>"
                                            alt="<?= htmlspecialchars($badge['name']) ?>"
                                            class="badge-icon-img">
                                    </div>

                                    <span class="badge-name">
                                        <?= htmlspecialchars($badge['name']) ?>
                                    </span>

                                    <span class="badge-desc">
                                        <?= htmlspecialchars($badge['desc']) ?>
                                    </span>

                                <?php else: ?>

                                    <div class="badge-icon-img-wrap locked-slot">
                                        <span class="locked-question">?</span>
                                    </div>

                                    <span class="badge-name">
                                        Locked
                                    </span>

                                    <span class="badge-desc">
                                        <?= htmlspecialchars($badge['desc']) ?>
                                    </span>

                                <?php endif; ?>

                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>






            </div> <!-- end right column -->

        </div>
    </div>

    <footer>
        © <?php echo date("Y"); ?> ezComp • Compiler Learning Platform
    </footer>

    <script>
        function toggleDone(listId, btn) {
            const list = document.getElementById(listId);
            const open = list.classList.toggle('open');
            btn.classList.toggle('open', open);
            btn.setAttribute('aria-expanded', open);
        }

        window.addEventListener('load', () => {
            document.querySelectorAll('.bar-fill').forEach(bar => {
                const target = bar.dataset.width + '%';
                bar.style.width = '0%';

                requestAnimationFrame(() => {
                    setTimeout(() => {
                        bar.style.width = target;
                    }, 80);
                });
            });
        });
    </script>

    <?php if ($showTutorial): ?>
        <div class="tutorial-backdrop" id="tutorialBackdrop"></div>

        <div class="tutorial-box" id="tutorialBox">
            <div class="tutorial-step-count" id="tutorialStepCount"></div>
            <div class="tutorial-title" id="tutorialTitle"></div>
            <div class="tutorial-text" id="tutorialText"></div>

            <div class="tutorial-actions">
                <button class="tutorial-btn secondary" type="button" id="tutorialSkipBtn">
                    Skip
                </button>

                <button class="tutorial-btn primary" type="button" id="tutorialNextBtn">
                    Next
                </button>
            </div>
        </div>

        <script>
            const tutorialSteps = [{
                    selector: '#tutorial-learning-progress',
                    title: 'Learning progress',
                    text: 'Welcome new user! This section shows your chapter progress. Each bar tells you how much of a chapter you have completed. You can click a chapter or the current topic to continue learning from where you stopped.'
                },
                {
                    selector: '#tutorial-quiz-progress',
                    title: 'Quiz progress',
                    text: 'This area tracks your quiz completion for every chapter. Each chapter has Easy, Normal, and Hard quizzes. Completed quizzes will show your score, while unfinished quizzes stay marked as not answered.'
                },
                {
                    selector: '#tutorial-leaderboard',
                    title: 'Leaderboard',
                    text: 'The leaderboard shows the top learners based on quiz scores. Keep answering quizzes and improving your scores to climb the ranking.'
                },
                {
                    selector: '#tutorial-badges',
                    title: 'Badges',
                    text: 'Badges are rewards for your achievements. Some badges unlock when you complete quiz levels, finish chapters, or reach important learning milestones.'
                },
                {
                    selector: '#tutorial-navbar',
                    title: 'Navigation bar',
                    text: 'Use the navigation bar to move between Home, Quiz, Leaderboard, and Profile. It helps you quickly access the main parts of CompileX.'
                }
            ];

            let tutorialIndex = 0;

            const backdrop = document.getElementById('tutorialBackdrop');
            const box = document.getElementById('tutorialBox');
            const stepCount = document.getElementById('tutorialStepCount');
            const title = document.getElementById('tutorialTitle');
            const text = document.getElementById('tutorialText');
            const nextBtn = document.getElementById('tutorialNextBtn');
            const skipBtn = document.getElementById('tutorialSkipBtn');

            function clearTutorialHighlight() {
                document.querySelectorAll('.tutorial-highlight').forEach(element => {
                    element.classList.remove('tutorial-highlight');
                });
            }

            function placeTutorialBox(target) {
                const rect = target.getBoundingClientRect();
                const boxWidth = box.offsetWidth;
                const boxHeight = box.offsetHeight;
                const gap = 18;

                let top = rect.top + window.scrollY;
                let left = rect.right + gap + window.scrollX;

                if (left + boxWidth > window.innerWidth - 16) {
                    left = rect.left - boxWidth - gap + window.scrollX;
                }

                if (left < 16) {
                    left = 16;
                    top = rect.bottom + gap + window.scrollY;
                }

                if (top + boxHeight > window.scrollY + window.innerHeight - 16) {
                    top = window.scrollY + window.innerHeight - boxHeight - 16;
                }

                if (top < window.scrollY + 16) {
                    top = window.scrollY + 16;
                }

                box.style.top = top + 'px';
                box.style.left = left + 'px';
            }

            function showTutorialStep() {
                clearTutorialHighlight();

                const step = tutorialSteps[tutorialIndex];
                const target = document.querySelector(step.selector);

                if (!target) {
                    tutorialIndex++;
                    if (tutorialIndex >= tutorialSteps.length) {
                        closeTutorial();
                    } else {
                        showTutorialStep();
                    }
                    return;
                }

                backdrop.classList.add('active');
                box.classList.add('active');

                target.classList.add('tutorial-highlight');
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                stepCount.textContent = `Step ${tutorialIndex + 1} of ${tutorialSteps.length}`;
                title.textContent = step.title;
                text.textContent = step.text;
                nextBtn.textContent = tutorialIndex === tutorialSteps.length - 1 ? 'Finish' : 'Next';

                setTimeout(() => {
                    placeTutorialBox(target);
                }, 350);
            }

            function closeTutorial() {
                clearTutorialHighlight();
                backdrop.classList.remove('active');
                box.classList.remove('active');
            }

            nextBtn.addEventListener('click', () => {
                tutorialIndex++;

                if (tutorialIndex >= tutorialSteps.length) {
                    closeTutorial();
                } else {
                    showTutorialStep();
                }
            });

            skipBtn.addEventListener('click', closeTutorial);

            window.addEventListener('resize', () => {
                const step = tutorialSteps[tutorialIndex];
                const target = document.querySelector(step.selector);

                if (target && box.classList.contains('active')) {
                    placeTutorialBox(target);
                }
            });

            window.addEventListener('load', showTutorialStep);
        </script>
    <?php endif; ?>
</body>

</html>