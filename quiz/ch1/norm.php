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
        'q' => 'What is the primary function of a compiler?',
        'choices' => [
            'A' => 'To execute the source program directly and display output',
            'B' => 'To translate a high-level source program into an equivalent machine language object program',
            'C' => 'To check only the syntax of a program and report errors',
            'D' => 'To interpret each line of code one at a time at run time',
        ],
        'answer' => 'B',
        'explain' => 'A compiler accepts a program written in a high-level language and produces as output an equivalent program in machine language for a particular target machine.',
        'color'   => 'indigo',
    ],
    [
        'q' => 'When a compiler processes the statement x = x * 9, which of the following is TRUE?',
        'choices' => [
            'A' => 'The compiler performs the multiplication and stores the result',
            'B' => 'The compiler ignores the statement as it is trivial',
            'C' => 'The compiler generates a sequence of instructions including a multiply instruction — it does NOT perform the multiplication',
            'D' => 'The compiler converts 9 to binary and stores it in memory',
        ],
        'answer' => 'C',
        'explain' => 'The compiler does NOT perform the multiplication. It generates, as output, a sequence of instructions including a "multiply" instruction to be executed later at run time.',
        'color'   => 'teal',
    ],
    [
        'q' => 'What is the key difference between a compiler and an interpreter?',
        'choices' => [
            'A' => 'A compiler works only with Java; an interpreter works with all languages',
            'B' => 'The output of a compiler is a machine language program; the output of an interpreter is the source program\'s computed result',
            'C' => 'An interpreter is faster than a compiler at generating code',
            'D' => 'A compiler runs one line at a time; an interpreter translates the whole program first',
        ],
        'answer' => 'B',
        'explain' => 'The output of a compiler is a program (in machine language), whereas the output of an interpreter is the source program\'s output — the actual computed result.',
        'color'   => 'purple',
    ],
    [
        'q' => 'Which of the following is a compile-time error (detected by the compiler)?',
        'choices' => [
            'A' => 'Division by zero at run time',
            'B' => 'An array subscript out of bounds',
            'C' => 'A missing closing parenthesis: a = ((b+c)*d;',
            'D' => 'Dereferencing a null pointer',
        ],
        'answer' => 'C',
        'explain' => 'Syntax errors like a missing parenthesis are detected by the compiler at compile time. Division by zero, invalid subscripts, and null pointer errors are run-time errors.',
        'color'   => 'rose',
    ],
    [
        'q' => 'What is the FIRST phase of a compiler?',
        'choices' => [
            'A' => 'Syntax Analysis',
            'B' => 'Code Generation',
            'C' => 'Global Optimization',
            'D' => 'Lexical Analysis',
        ],
        'answer' => 'D',
        'explain' => 'Lexical analysis (the scanner) is the first phase of a compiler. It isolates "words" (tokens/lexemes) from the input string and passes them to the next phase.',
        'color'   => 'amber',
    ],
    [
        'q' => 'Given the Java input: sum = sum + unit * /* accumulate sum */ 1.2e-12 ;   — how many tokens does the lexical phase output?',
        'choices' => [
            'A' => '8 tokens including the comment',
            'B' => '7 tokens (comment is discarded)',
            'C' => '5 tokens',
            'D' => '9 tokens',
        ],
        'answer' => 'B',
        'explain' => 'The 7 tokens are: identifier(sum), assignment(=), identifier(sum), operator(+), identifier(unit), operator(*), numeric constant(1.2e-12). The comment /* accumulate sum */ is identified but discarded.',
        'color'   => 'green',
    ],
    [
        'q' => 'Which of the following token types is identified by the scanner but NOT included in its output?',
        'choices' => [
            'A' => 'Identifiers',
            'B' => 'Numeric constants',
            'C' => 'Comments',
            'D' => 'Keywords',
        ],
        'answer' => 'C',
        'explain' => 'Comments must be identified by the scanner (lexical analyser) but are NOT included in the token output — they are ignored by all subsequent phases.',
        'color'   => 'cyan',
    ],
    [
        'q' => 'What does the symbol table built during lexical analysis store?',
        'choices' => [
            'A' => 'All machine language instructions generated so far',
            'B' => 'All atoms produced by the parser',
            'C' => 'All identifiers used in the source program, along with their relevant information and attributes',
            'D' => 'All syntax errors found during compilation',
        ],
        'answer' => 'C',
        'explain' => 'The symbol table stores all identifiers used in the source program, including relevant information and attributes of those identifiers.',
        'color'   => 'indigo',
    ],
    [
        'q' => 'For the Java statement A = B + C * D, what is the CORRECT sequence of atoms output by the parser?',
        'choices' => [
            'A' => '(ADD,B,C,T1), (MULT,T1,D,T2), (MOVE,T2,A)',
            'B' => '(MULT,C,D,T1), (ADD,B,T1,T2), (MOVE,T2,A)',
            'C' => '(MOVE,A,B), (ADD,B,C,T1), (MULT,T1,D,A)',
            'D' => '(ADD,B,C,T1), (MOVE,T1,A), (MULT,C,D,A)',
        ],
        'answer' => 'B',
        'explain' => 'Multiplication has higher precedence, so the MULT atom comes first: (MULT,C,D,T1), then (ADD,B,T1,T2), then (MOVE,T2,A). The compiler must respect operator precedence.',
        'color'   => 'purple',
    ],
    [
        'q' => 'In a syntax tree, what do INTERIOR nodes and LEAF nodes represent respectively?',
        'choices' => [
            'A' => 'Interior = operands; Leaf = operations',
            'B' => 'Interior = operations or control structures; Leaf = operands',
            'C' => 'Interior = tokens; Leaf = atoms',
            'D' => 'Interior = machine instructions; Leaf = registers',
        ],
        'answer' => 'B',
        'explain' => 'In syntax trees, each interior node represents an operation or control structure, and each leaf node represents an operand.',
        'color'   => 'teal',
    ],
    [
        'q' => 'Global optimization is described as "machine-independent" because:',
        'choices' => [
            'A' => 'It runs on any operating system without modification',
            'B' => 'It is invoked BEFORE the code generator, examining atoms rather than machine instructions',
            'C' => 'It does not affect the source program in any way',
            'D' => 'It can only be used with interpreted languages',
        ],
        'answer' => 'B',
        'explain' => 'Global optimization is called machine-independent because it operates on atoms (before code generation), not on machine-specific instructions. Local optimization, performed after code generation, is machine-dependent.',
        'color'   => 'rose',
    ],
    [
        'q' => 'Which of the following is an example of a loop invariant that global optimization would move OUT of a loop?',
        'choices' => [
            'A' => 'A variable that changes on every loop iteration',
            'B' => 'A computation that depends on the loop counter i',
            'C' => 'x = Math.sqrt(y) inside a loop where y never changes',
            'D' => 'An array access a[i] that uses the loop index',
        ],
        'answer' => 'C',
        'explain' => 'Since y does not change inside the loop, x = Math.sqrt(y) is a loop invariant. Global optimization moves it outside the loop, eliminating 99,999 unnecessary calls.',
        'color'   => 'amber',
    ],
    [
        'q' => 'In a single-pass compiler, how does control flow between the phases?',
        'choices' => [
            'A' => 'Each phase writes its entire output to a disk file before the next phase starts',
            'B' => 'Lexical analysis runs completely first, then syntax analysis, then code generation',
            'C' => 'The syntax analyser calls lexical analysis as a subroutine for each token, and calls the code generator as a subroutine for each atom',
            'D' => 'All three phases run in parallel on separate processor cores',
        ],
        'answer' => 'C',
        'explain' => 'In a single-pass compiler, the parser (syntax analysis) calls the lexical analyser as a subroutine each time it needs a token, and calls the code generator whenever it has produced an atom.',
        'color'   => 'green',
    ],
    [
        'q' => 'What is bootstrapping in the context of compiler implementation?',
        'choices' => [
            'A' => 'Downloading a compiler from the internet to a new machine',
            'B' => 'Using a small compiler written for a language subset as input to itself (or a related compiler) to produce a full-language compiler',
            'C' => 'Writing a compiler entirely in machine language from scratch',
            'D' => 'Converting assembly language directly to machine code',
        ],
        'answer' => 'B',
        'explain' => 'Bootstrapping involves writing a compiler for a language subset in machine language, then writing a full-language compiler in that subset language and using the first compiler to translate it — "pulling yourself up by your bootstraps".',
        'color'   => 'cyan',
    ],
    [
        'q' => 'What is the main advantage of compiling to an intermediate form (such as atoms or byte code)?',
        'choices' => [
            'A' => 'The object program runs faster than native machine code',
            'B' => 'You need only one front-end per high-level language and one back-end per target machine, reducing the total number of compilers needed',
            'C' => 'It eliminates the need for a lexical analysis phase',
            'D' => 'It allows the compiler to skip syntax checking entirely',
        ],
        'answer' => 'B',
        'explain' => 'With intermediate form, for n languages and m machines you need n front-ends + m back-ends instead of n×m full compilers. This dramatically reduces development effort.',
        'color'   => 'indigo',
    ],
];

$total     = count($questions);
$submitted = false;
$score     = 0;
$results   = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = true;
    $timeRemaining = intval($_POST['time_remaining'] ?? 0);

    foreach ($questions as $i => $q) {
        $key      = 'q' . $i;
        $userAns  = $_POST[$key] ?? null;
        $answered = $userAns !== null;
        $correct  = $answered && $userAns === $q['answer'];
        if ($correct) $score++;
        $results[] = ['answered' => $answered, 'userAns' => $userAns, 'correct' => $correct];
    }

    $scorepoint = $score * 100;

    $quiz_id = 'Q002';

/* Save every attempt in quiz_attempt */
$result = $conn->query("
    SELECT qa_id
    FROM quiz_attempt
    WHERE qa_id REGEXP '^QA[0-9]+$'
    ORDER BY CAST(SUBSTRING(qa_id, 3) AS UNSIGNED) DESC
    LIMIT 1
");

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $next_number = (int)substr($row['qa_id'], 2) + 1;
} else {
    $next_number = 1;
}

$qa_id = 'QA' . str_pad($next_number, 3, '0', STR_PAD_LEFT);

$attemptStmt = $conn->prepare("
    INSERT INTO quiz_attempt
        (qa_id, user_id, quiz_id, scorepoint, answered_at)
    VALUES (?, ?, 'Q002', ?, NOW())
");

$attemptStmt->bind_param(
    "sii",
    $qa_id,
    $user_id,
    $scorepoint
);

$attemptStmt->execute();
$attemptStmt->close();


/* Check the user's Chapter 1 Normal leaderboard record */
$leaderboardStmt = $conn->prepare("
    SELECT board_id, best_score
    FROM leaderboard
    WHERE user_id = ?
      AND quiz_id = 'Q002'
    LIMIT 1
");

$leaderboardStmt->bind_param("i", $user_id);
$leaderboardStmt->execute();
$leaderboardResult = $leaderboardStmt->get_result();

if ($leaderboardResult->num_rows > 0) {
    $leaderboardRow = $leaderboardResult->fetch_assoc();
    $currentBestScore = (int)$leaderboardRow['best_score'];

    // Only replace the best score when the new score is higher.
    if ($scorepoint > $currentBestScore) {
        $updateStmt = $conn->prepare("
            UPDATE leaderboard
            SET best_score = ?,
                updated_at = NOW()
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
    /* Create a leaderboard record when none exists */
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
        VALUES (?, ?, 'Q002', ?, NOW())
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
}

$pct = $submitted ? round($score / $total * 100) : 0;

// color palette map
$palette = [
    'indigo' => ['bg' => '#EEF2FF', 'border' => '#6366F1', 'text' => '#4338CA', 'light' => '#C7D2FE'],
    'teal'   => ['bg' => '#F0FDFA', 'border' => '#14B8A6', 'text' => '#0F766E', 'light' => '#99F6E4'],
    'purple' => ['bg' => '#FAF5FF', 'border' => '#A855F7', 'text' => '#7E22CE', 'light' => '#E9D5FF'],
    'rose'   => ['bg' => '#FFF1F2', 'border' => '#F43F5E', 'text' => '#BE123C', 'light' => '#FECDD3'],
    'amber'  => ['bg' => '#FFFBEB', 'border' => '#F59E0B', 'text' => '#92400E', 'light' => '#FDE68A'],
    'green'  => ['bg' => '#F0FDF4', 'border' => '#22C55E', 'text' => '#15803D', 'light' => '#BBF7D0'],
    'cyan'   => ['bg' => '#ECFEFF', 'border' => '#06B6D4', 'text' => '#155E75', 'light' => '#A5F3FC'],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter 1 – Medium Quiz</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../css/body.css">

    <style>
        body {
            background: #F4F3FF;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ── Header ── */
        .page-header {
            max-width: 1160px;
            margin: 0 auto;
            padding: 44px 24px 0;
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 800;
            color: #1E1B4B;
            letter-spacing: -.5px;
        }

        .page-header h1 .accent {
            color: #6366F1;
        }

        .difficulty-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #FEF3C7;
            color: #92400E;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            border: 1px solid #FCD34D;
            margin-bottom: 8px;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .page-header p {
            color: #6B7280;
            font-size: 14px;
            margin-top: 6px;
        }

        /* ── Progress tracker ── */
        .tracker {
            max-width: 1160px;
            margin: 18px auto 0;
            padding: 0 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tracker-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #E5E7EB;
            transition: background .3s;
            cursor: default;
        }

        .tracker-dot.answered {
            background: #6366F1;
        }

        .tracker-label {
            font-size: 12px;
            color: #9CA3AF;
            margin-left: 4px;
        }

        /* ── Question card ── */
        .q-card {
            background: #fff;
            border-radius: 16px;
            border-left: 5px solid #E5E7EB;
            box-shadow: 0 2px 14px rgba(0, 0, 0, .06);
            padding: 26px 28px;
            margin-bottom: 20px;
            opacity: 0;
            animation: rise .45s ease forwards;
            transition: box-shadow .2s;
        }

        .q-card:hover {
            box-shadow: 0 8px 28px rgba(0, 0, 0, .10);
        }

        <?php foreach ($palette as $name => $c): ?>.q-card.color-<?= $name ?> {
            border-left-color: <?= $c['border'] ?>;
            background: <?= $c['bg'] ?>;
        }

        .q-card.color-<?= $name ?>.q-num {
            background: <?= $c['light'] ?>;
            color: <?= $c['text'] ?>;
        }

        .q-card.color-<?= $name ?>.choice-btn:hover:not(.disabled) {
            border-color: <?= $c['border'] ?>;
            background: <?= $c['light'] ?>;
            color: <?= $c['text'] ?>;
        }

        .q-card.color-<?= $name ?>.choice-btn.selected {
            border-color: <?= $c['border'] ?>;
            background: <?= $c['light'] ?>;
            color: <?= $c['text'] ?>;
            font-weight: 600;
        }

        <?php endforeach; ?><?php for ($i = 1; $i <= 15; $i++) echo ".q-card:nth-child($i){animation-delay:" . ($i * .04) . "s;}"; ?>@keyframes rise {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Q number badge ── */
        .q-num {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: 10px;
        }

        /* ── Question text ── */
        .q-text {
            font-size: 15px;
            font-weight: 600;
            color: #1E1B4B;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        /* ── Choice buttons ── */
        .choices {
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .choice-btn {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 15px;
            border: 1.5px solid #E5E7EB;
            border-radius: 10px;
            background: #fff;
            cursor: pointer;
            font-size: 14px;
            color: #374151;
            text-align: left;
            transition: all .18s;
            width: 100%;
        }

        .choice-btn .letter {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #F3F4F6;
            font-size: 12px;
            font-weight: 700;
            color: #6B7280;
            flex-shrink: 0;
            transition: all .18s;
        }

        .choice-btn.selected .letter {
            background: inherit;
            filter: brightness(.85);
        }

        /* Result states */
        .choice-btn.correct-ans {
            border-color: #22C55E !important;
            background: #F0FDF4 !important;
            color: #15803D !important;
            font-weight: 600;
        }

        .choice-btn.correct-ans .letter {
            background: #22C55E;
            color: #fff;
        }

        .choice-btn.wrong-ans {
            border-color: #EF4444 !important;
            background: #FFF5F5 !important;
            color: #991B1B !important;
        }

        .choice-btn.wrong-ans .letter {
            background: #EF4444;
            color: #fff;
        }

        .choice-btn.disabled {
            cursor: default;
            pointer-events: none;
            opacity: .65;
        }

        /* Card result states */
        .q-card.result-correct {
            border-left-color: #22C55E !important;
        }

        .q-card.result-wrong {
            border-left-color: #EF4444 !important;
        }

        .q-card.result-skipped {
            border-left-color: #9CA3AF !important;
        }

        /* Result badge */
        .result-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: 8px;
        }

        .badge-correct {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge-wrong {
            background: #FEE2E2;
            color: #991B1B;
        }

        .badge-skipped {
            background: #F3F4F6;
            color: #6B7280;
        }

        /* Explanation */
        .explain-box {
            margin-top: 14px;
            padding: 12px 14px;
            background: rgba(99, 102, 241, .07);
            border-left: 3px solid #6366F1;
            border-radius: 0 8px 8px 0;
            font-size: 13px;
            color: #374151;
            line-height: 1.65;
        }

        /* ── Score card ── */
        .score-card {
            background: #fff;
            border-radius: 18px;
            padding: 32px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 4px 24px rgba(99, 102, 241, .12);
            border: 1px solid #E0E7FF;
        }

        .score-row {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-bottom: 20px;
        }

        .score-stat {
            text-align: center;
        }

        .score-big {
            font-size: 46px;
            font-weight: 800;
            color: #6366F1;
            line-height: 1;
        }

        .score-lbl {
            font-size: 12px;
            color: #9CA3AF;
            margin-top: 4px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .stat-correct {
            color: #22C55E;
        }

        .stat-wrong {
            color: #EF4444;
        }

        .score-bar {
            height: 12px;
            background: #E8E8F0;
            border-radius: 20px;
            overflow: hidden;
            margin: 0 0 10px;
        }

        .score-bar-fill {
            height: 100%;
            border-radius: 20px;
            background: linear-gradient(90deg, #6366F1, #818CF8);
            transition: width 1.1s ease;
        }

        .score-msg {
            font-size: 16px;
            font-weight: 700;
            color: #1E1B4B;
        }

        .btn-retry {
            display: inline-block;
            margin-top: 16px;
            padding: 11px 32px;
            background: #EEF2FF;
            color: #4338CA;
            font-weight: 700;
            font-size: 14px;
            border-radius: 10px;
            text-decoration: none;
            border: 1.5px solid #C7D2FE;
            transition: background .18s;
        }

        .btn-retry:hover {
            background: #c7d2fe;
            color: #3730a3;
        }

        /* ── Layout with sidebar ── */
        .page-layout {
            display: flex;
            align-items: flex-start;
            gap: 0;
            max-width: 1160px;
            margin: 0 auto;
            padding: 0 16px;
        }

        /* ── Left nav sidebar ── */
        .q-nav {
            position: sticky;
            top: 80px;
            width: 310px;
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
            margin-left: 0px;
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
            width: 38px;
            height: 38px;
            border-radius: 10px;
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
            flex-shrink: 0;
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

        .q-nav-btn.nav-answered:hover {
            background: #4F46E5;
            border-color: #4F46E5;
            color: #fff;
        }

        .q-nav-btn.nav-active {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .3);
        }

        /* result colors on nav after submit */
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

        .q-nav-btn.nav-correct:hover {
            background: #16A34A;
            border-color: #16A34A;
        }

        .q-nav-btn.nav-wrong:hover {
            background: #DC2626;
            border-color: #DC2626;
        }

        /* ── Quiz wrap (right column) ── */
        .quiz-wrap {
            flex: 1;
            min-width: 0;
            margin: 28px 0 80px;
            padding: 0;
        }

        /* ── Submit ── */
        .submit-wrap {
            text-align: center;
            margin-top: 10px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #6366F1, #8B5CF6);
            color: #fff;
            border: none;
            padding: 15px 52px;
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

        /* Chapter 2 Normal-style layout and interaction overrides */
        .tracker {
            display: none;
        }

        .page-header,
        .page-layout {
            max-width: 1100px;
        }

        .page-layout {
            padding: 0 24px;
        }

        .q-nav {
            box-sizing: border-box;
            width: 230px;
            padding: 14px 12px 16px;
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }

        .q-nav > .timer-wrap {
            position: static;
            grid-column: 1 / -1;
            width: 100%;
            margin: 0 0 14px;
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
            color: #6366F1;
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
            border-color: #EF4444;
            color: #991B1B;
            background: #FFF5F5;
            animation: timerPulse .6s infinite alternate;
        }

        @keyframes timerPulse {
            from { box-shadow: 0 4px 18px rgba(239, 68, 68, .15); }
            to { box-shadow: 0 4px 28px rgba(239, 68, 68, .45); }
        }

        .q-nav-btn {
            width: 34px;
            height: 34px;
            border-radius: 6px;
            justify-self: center;
        }

        .q-nav-label,
        .nav-count,
        .nav-submit-wrap {
            min-width: 0;
        }

        .nav-count {
            grid-column: 1 / -1;
            text-align: center;
            font-size: 11px;
            color: #9CA3AF;
            margin-top: 2px;
        }

        .nav-submit-wrap {
            grid-column: 1 / -1;
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

        .choice-btn input[type="radio"] {
            display: none !important;
        }

        .choice-btn.answer-locked {
            cursor: default;
            pointer-events: none;
        }

        .choice-btn.answer-muted {
            opacity: .55;
        }

        .choice-btn.correct-ans,
        .choice-btn.correct-ans.answer-muted {
            opacity: 1;
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

            .choice-btn {
                padding: 11px;
                font-size: 13px;
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

            .choice-btn {
                gap: 8px;
            }

            .choice-btn .letter {
                min-width: 24px;
                height: 24px;
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
        <div class="difficulty-chip">⚡ Medium Level</div>
        <h1>Chapter 1 — <span class="accent">Multiple Choice</span> Quiz</h1>
        <p>15 questions covering Sections 1.1, 1.2, and 1.3. Choose the best answer for each question.</p>
    </div>

    <!-- Dot tracker -->
    <div class="tracker" id="tracker">
        <?php for ($i = 0; $i < $total; $i++): ?>
            <div class="tracker-dot" id="dot-<?= $i ?>" title="Q<?= $i + 1 ?>"></div>
        <?php endfor; ?>
        <span class="tracker-label" id="tracker-label">0 / <?= $total ?> answered</span>
    </div>

    <div class="page-layout">

        <!-- ── Left question navigator ── -->
        <nav class="q-nav" id="qNav">
            <?php if (!$submitted): ?>
                <div class="timer-wrap">
                    <div class="timer-box" id="timerBox">
                        <span aria-hidden="true">⏱</span>
                        <span id="timerDisplay">25:00</span>
                    </div>
                </div>
            <?php endif; ?>

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

            <div class="nav-count" id="nav-count">0 / <?= $total ?></div>

            <?php if (!$submitted): ?>
                <div class="nav-submit-wrap">
                    <button type="submit" form="quizForm" class="nav-submit-btn">
                        Submit →
                    </button>
                </div>
            <?php endif; ?>
        </nav>

        <div class="quiz-wrap">

            <?php if ($submitted):
                $wrong   = $score < $total ? array_reduce(array_keys($results), fn($c, $i) => $c + (!$results[$i]['correct'] && $results[$i]['answered'] ? 1 : 0), 0) : 0;
                $skipped = array_reduce($results, fn($c, $r) => $c + (!$r['answered'] ? 1 : 0), 0);
            ?>
                <div class="score-card">
                    <div class="score-row">
                        <div class="score-stat">
                            <div class="score-big"><?= $score ?>/<?= $total ?></div>
                            <div class="score-lbl">Total Score</div>
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
                        if ($pct === 100)    echo '🏆 Perfect! You mastered Chapter 1!';
                        elseif ($pct >= 80)  echo '🎉 Excellent work! Strong understanding of compiler concepts.';
                        elseif ($pct >= 60)  echo '👍 Good effort — check the explanations below to fill the gaps.';
                        elseif ($pct >= 40)  echo '📖 Keep going! Review Chapter 1 and try again.';
                        else                 echo '💪 Don\'t give up — re-read the notes and try once more!';
                        ?>
                    </div>
                    <a class="btn-retry" href="<?= $_SERVER['PHP_SELF'] ?>">↺ Retry Quiz</a>
                </div>
            <?php endif; ?>

            <form method="POST" id="quizForm">
                <input type="hidden"
                    name="time_remaining"
                    id="timeRemainingInput"
                    value="0">

                <?php foreach ($questions as $i => $q):
                    $pal = $palette[$q['color']];
                    $r   = $results[$i] ?? null;
                    $cardExtra = '';
                    if ($submitted && $r) {
                        if (!$r['answered'])   $cardExtra = ' result-skipped';
                        elseif ($r['correct']) $cardExtra = ' result-correct';
                        else                   $cardExtra = ' result-wrong';
                    }
                ?>
                    <div id="q-<?= $i ?>"
                        class="q-card color-<?= $q['color'] ?><?= $cardExtra ?>"
                        data-question="<?= $i ?>"
                        data-answer="<?= htmlspecialchars($q['answer']) ?>">

                        <?php if ($submitted && $r): ?>
                            <?php if (!$r['answered']): ?>
                                <div class="answer-feedback show badge-skipped">
                                    ⚪ Skipped — Correct answer: <?= $q['answer'] ?>
                                </div>
                            <?php elseif ($r['correct']): ?>
                                <div class="answer-feedback show badge-correct">✔ Correct</div>
                            <?php else: ?>
                                <div class="answer-feedback show badge-wrong">
                                    ✘ Wrong — Correct answer: <?= $q['answer'] ?>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="answer-feedback" id="feedback-<?= $i ?>"></div>
                        <?php endif; ?>

                        <div class="q-num">Question <?= $i + 1 ?> of <?= $total ?></div>
                        <div class="q-text"><?= htmlspecialchars($q['q']) ?></div>

                        <div class="choices">
                            <?php foreach ($q['choices'] as $letter => $text):
                                $btnClass = 'choice-btn';
                                if ($submitted && $r) {
                                    $btnClass .= ' answer-locked';
                                    if ($letter === $q['answer']) {
                                        $btnClass .= ' correct-ans';
                                    } elseif ($r['answered'] && $letter === $r['userAns']) {
                                        $btnClass .= ' wrong-ans';
                                    } else {
                                        $btnClass .= ' answer-muted';
                                    }
                                }
                                $checked = ($submitted && $r && $r['answered'] && $r['userAns'] === $letter);
                            ?>
                                <button type="button"
                                    class="<?= $btnClass ?><?= (!$submitted && '') ?>"
                                    data-qi="<?= $i ?>"
                                    data-val="<?= $letter ?>"
                                    onclick="selectChoice(this)"
                                    <?= ($submitted) ? 'disabled' : '' ?>>
                                    <span class="letter"><?= $letter ?></span>
                                    <span class="choice-text"><?= htmlspecialchars($text) ?></span>
                                    <input type="radio" name="q<?= $i ?>" value="<?= $letter ?>"
                                        <?= $checked ? 'checked' : '' ?>>
                                </button>
                            <?php endforeach; ?>
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

    <script>
        const total = <?= $total ?>;
        const quizSubmitted = <?= $submitted ? 'true' : 'false' ?>;
        const answered = new Set();

        function playSound(soundId) {
            const sound = document.getElementById(soundId);
            if (!sound) return;

            sound.currentTime = 0;
            sound.play().catch(function() {
                // Audio may be blocked until the first user interaction.
            });
        }

        function selectChoice(button) {
            if (quizSubmitted) return;

            const questionIndex = button.dataset.qi;
            const selectedAnswer = button.dataset.val;
            const card = document.getElementById("q-" + questionIndex);

            if (!card || card.dataset.answered === "true") return;

            const correctAnswer = card.dataset.answer;
            const buttons = card.querySelectorAll(".choice-btn");
            const correctButton = card.querySelector(
                `.choice-btn[data-val="${correctAnswer}"]`
            );
            const radio = button.querySelector('input[type="radio"]');

            if (radio) radio.checked = true;

            card.dataset.answered = "true";
            answered.add(questionIndex);

            buttons.forEach(function(choice) {
                choice.classList.add("answer-locked");

                if (choice !== button && choice.dataset.val !== correctAnswer) {
                    choice.classList.add("answer-muted");
                }
            });

            if (correctButton) {
                correctButton.classList.add("correct-ans");
            }

            const feedback = document.getElementById(
                "feedback-" + questionIndex
            );
            const explanation = document.getElementById(
                "explanation-" + questionIndex
            );
            const navigationButton = document.getElementById(
                "nav-btn-" + questionIndex
            );

            if (selectedAnswer === correctAnswer) {
                playSound("correctSound");
                card.classList.add("result-correct");

                if (feedback) {
                    feedback.textContent = "✔ Correct";
                    feedback.className =
                        "answer-feedback show badge-correct";
                }

                if (navigationButton) {
                    navigationButton.classList.add("nav-correct");
                }
            } else {
                playSound("wrongSound");
                card.classList.add("result-wrong");
                button.classList.add("wrong-ans");

                if (feedback) {
                    feedback.textContent =
                        "✘ Wrong — Correct answer: " + correctAnswer;
                    feedback.className =
                        "answer-feedback show badge-wrong";
                }

                if (navigationButton) {
                    navigationButton.classList.add("nav-wrong");
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

        // Highlight active nav button on scroll
        const cards = document.querySelectorAll('.q-card');
        const navBtns = document.querySelectorAll('.q-nav-btn');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const idx = entry.target.dataset.question;
                    navBtns.forEach(b => b.classList.remove('nav-active'));
                    const activeBtn = document.getElementById('nav-btn-' + idx);
                    if (activeBtn) activeBtn.classList.add('nav-active');
                }
            });
        }, {
            threshold: 0.35
        });
        cards.forEach(c => observer.observe(c));

        const DURATION = 25 * 60;
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

                if (remaining <= 300 && remaining > 60) {
                    timerBox.classList.add("warning");
                    timerBox.classList.remove("danger");
                } else if (remaining <= 60) {
                    timerBox.classList.remove("warning");
                    timerBox.classList.add("danger");
                }

                if (remaining <= 0) {
                    clearInterval(timerInterval);
                    if (timeInput) timeInput.value = 0;
                    sessionStorage.setItem("chapter1ScrollTop", "1");
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

                if (timeInput) timeInput.value = remaining;

                sessionStorage.setItem("chapter1ScrollTop", "1");
                playSound("submitSound");

                setTimeout(function() {
                    quizForm.submit();
                }, 500);
            });
        }

        if (
            quizSubmitted ||
            sessionStorage.getItem("chapter1ScrollTop") === "1"
        ) {
            sessionStorage.removeItem("chapter1ScrollTop");

            if ("scrollRestoration" in history) {
                history.scrollRestoration = "manual";
            }

            window.addEventListener("load", function() {
                window.scrollTo({
                    top: 0,
                    left: 0,
                    behavior: "auto"
                });
            });
        }
    </script>

</body>

</html>
