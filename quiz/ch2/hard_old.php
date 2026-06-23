<?php
session_start();
include '../../dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$questions = [
    // ── 2.3 Lexical Tables ────────────────────────────────────────
    [
        'q'      => 'When building a symbol table using a binary search tree, what is the WORST-CASE time complexity for inserting n identifiers?',
        'choices' => [
            'A' => 'O(n log₂n) — the tree is always balanced after each insert',
            'B' => 'O(n²) — occurs when identifiers are inserted in alphabetical order',
            'C' => 'O(n) — each insert only touches the rightmost path',
            'D' => 'O(log₂n) — binary search guarantees this regardless of order',
        ],
        'answer'  => 'B',
        'explain' => 'When identifiers are entered in sorted (alphabetical) order, the BST degenerates into a linked list — every new word goes to the rightmost leaf. Searching then costs O(n) per insert, giving O(n²) total. O(n log₂n) is only the best case for a balanced tree.',
        'color'   => 'rose',
        'image'   => null,
    ],
    [
        'q'      => 'Using the hash function hash(w) = (length(w) + ascii(first_char(w))) % 6, compute hash("cat") and hash("hill"). Which bucket do they share?',
        'choices' => [
            'A' => 'Both hash to bucket 0',
            'B' => 'cat → bucket 0, hill → bucket 4 — they do not collide',
            'C' => 'cat → bucket 5, hill → bucket 0',
            'D' => 'Both hash to bucket 5',
        ],
        'answer'  => 'A',
        'explain' => 'cat: length=3, ascii(\'c\')=99, (3+99)%6 = 102%6 = 0. hill: length=4, ascii(\'h\')=104, (4+104)%6 = 108%6 = 0. Both hash to bucket 0, so they collide in the same linked list.',
        'color'   => 'indigo',
        'image'   => null,
    ],
    [
        'q'      => 'A hash table is implemented as an array of linked lists (chaining). Compared to an array-of-words implementation, what is the PRIMARY advantage of chaining?',
        'choices' => [
            'A' => 'Chaining uses less memory in all cases since pointers are smaller than strings',
            'B' => 'Chaining never requires rehashing — the table size can stay fixed regardless of how many words are inserted',
            'C' => 'Chaining is faster to search because binary search can be applied to each list',
            'D' => 'Chaining sorts words automatically when they are inserted into the list',
        ],
        'answer'  => 'B',
        'explain' => 'With an array-of-words hash table, the table can fill up, forcing a costly rehash. An array of linked lists never overflows — extra words simply extend the relevant list. The textbook identifies this as the main advantage of chaining, at the cost of extra pointer storage.',
        'color'   => 'teal',
        'image'   => null,
    ],
    [
        'q'      => 'After inserting the words frog, tree, hill, bird, bat, cat into a BST (in that order), how many string comparisons are needed to insert a NEW identifier "apple"?',
        'choices' => [
            'A' => '1 comparison — apple goes directly to the first open spot',
            'B' => '2 comparisons — only the root and one child are checked',
            'C' => '3 comparisons — frog, bird, bat are traversed',
            'D' => '4 comparisons — frog, bird, bat, and then apple is inserted',
        ],
        'answer'  => 'C',
        'explain' => 'Root is frog; apple < frog → go left to bird; apple < bird → go left to bat; apple < bat → bat has no left child → insert here. That is 3 comparisons (frog, bird, bat).',
        'color'   => 'purple',
        'image'   => null,
    ],

    // ── 2.4 SableCC ──────────────────────────────────────────────
    [
        'q'      => 'In a SableCC Tokens section, if two token definitions match the SAME length input, which token is chosen?',
        'choices' => [
            'A' => 'The one whose definition contains fewer operators',
            'B' => 'The one listed LAST in the Tokens section',
            'C' => 'The one listed FIRST in the Tokens section',
            'D' => 'SableCC throws a compile-time ambiguity error',
        ],
        'answer'  => 'C',
        'explain' => 'SableCC uses two rules: (1) the longer match wins; (2) when lengths are equal, the definition listed FIRST wins. This is why keywords must be listed before the general identifier rule — \'while\' matches both keyword and identifier at 5 chars, and the first-listed rule wins.',
        'color'   => 'amber',
        'image'   => null,
    ],
    [
        'q'      => 'Consider the SableCC Helpers section. Why are SableCC helpers SAFER than lex macros when used inside token definitions?',
        'choices' => [
            'A' => 'SableCC helpers are textual substitutions, just like lex macros, so there is no difference in safety',
            'B' => 'SableCC helpers perform semantic substitution — the helper\'s meaning is preserved, avoiding operator-precedence bugs caused by textual expansion',
            'C' => 'SableCC helpers are compiled to bytecode before substitution, preventing runtime errors',
            'D' => 'SableCC helpers can only be used once, ensuring no accidental reuse',
        ],
        'answer'  => 'B',
        'explain' => 'In lex, macros are textual substitutions. Substituting sign = \'+\' | \'-\' into number = sign? digit+ yields \'+\' | \'-\'? digit+, which is parsed as \'+\' | (\'-\'? digit+) — wrong. SableCC helpers substitute semantically, preserving the intended grouping and operator precedence.',
        'color'   => 'green',
        'image'   => null,
    ],
    [
        'q'      => 'Examine the SableCC token definition below. What language does it recognize?\n\ncomment2 = \'/*\' non_star* \'*\' (non_star_slash non_star* \'*\'+)* \'/\' ;',
        'choices' => [
            'A' => 'Only single-line comments that begin with /* and end at the next newline',
            'B' => 'Multi-line C-style comments that may contain * but not the exact sequence */',
            'C' => 'Any string beginning with /* and ending with */ including nested comments',
            'D' => 'Multi-line comments beginning with // and ending with */',
        ],
        'answer'  => 'B',
        'explain' => 'This regex accepts /* then any non-star chars, then one or more stars, then zero or more repetitions of (a non-star-slash char, then non-stars, then stars+), then /. The result is exactly C-style /* … */ comments where */ ends the comment, but sequences like * alone inside are permitted. Nested comments are NOT handled.',
        'color'   => 'cyan',
        'image'   => null,
    ],
    [
        'q'      => 'In SableCC, what is the purpose of the States section, and what is the FIRST state listed treated as?',
        'choices' => [
            'A' => 'States define accepted token classes; the first state is the final/accepting state',
            'B' => 'States name scanner modes for left-context sensitivity; the first state listed is the start state',
            'C' => 'States define error recovery procedures; the first state is the error state',
            'D' => 'States list all token types; the first state is the default token class',
        ],
        'answer'  => 'B',
        'explain' => 'SableCC States represent different scanner modes (e.g., inside a string, at beginning of line, in currency mode). The scanner begins in the FIRST listed state. Token definitions can be restricted to specific states and can trigger state transitions using the -> operator.',
        'color'   => 'rose',
        'image'   => null,
    ],
    [
        'q'      => 'In the SableCC currency scanner example, the tokens are defined as:\n\n{def->currency} dollar = \'$\';\n{currency->def} money = num;\n{def} number = num;\n\nWhat token sequence is produced for the input: $9 42?',
        'choices' => [
            'A' => 'money(9), number(42) — the dollar sign is silently discarded',
            'B' => 'dollar($), money(9), number(42)',
            'C' => 'dollar($), number(9), number(42)',
            'D' => 'money($9), number(42)',
        ],
        'answer'  => 'B',
        'explain' => 'Scanner starts in def. Seeing $ → emits token dollar and transitions to currency. In currency state, 9 matches money (not number) → emits money(9) and transitions back to def. 42 is now in def state → emits number(42). Sequence: dollar, money, number.',
        'color'   => 'indigo',
        'image'   => null,
    ],
    [
        'q'      => 'What does the SableCC right-context operator / do in a token definition like:\n\ncurrency = number / space* \'CR\' ;',
        'choices' => [
            'A' => 'It includes the space* CR as part of the currency token',
            'B' => 'It divides the number value by CR before outputting the token',
            'C' => 'It matches number only if followed by space* CR, but the matched token does NOT include the right context',
            'D' => 'It requires the input to begin with space* CR before the number',
        ],
        'answer'  => 'C',
        'explain' => 'The forward slash / introduces a right context. SableCC matches the token (number) only when the specified right context (space* CR) follows it, but the right context itself is NOT consumed as part of the token. This is a lookahead mechanism.',
        'color'   => 'teal',
        'image'   => null,
    ],
    [
        'q'      => 'Given the following SableCC Helpers and Tokens, what token class is produced for input "a4x"?\n\nHelpers: char = [\'a\'..\'z\'] [\'0\'..\'9\']? ;\nTokens: token1 = char char ; token2 = char \'x\' ; token3 = char+ ;',
        'choices' => [
            'A' => 'token2 — "a4x" is matched as char followed by \'x\'',
            'B' => 'token1 — "a4" and "x" are matched as two consecutive char units',
            'C' => 'token3 — "a4x" is matched as one or more chars (longest match)',
            'D' => 'token1 wins because it is listed first and ties with token3 at 3 characters',
        ],
        'answer'  => 'B',
        'explain' => 'char matches one letter optionally followed by a digit: "a4" is one char, "x" is one char. token1 = char char matches "a4x" as two consecutive chars (3 characters). token3 = char+ could match "a4" (2 chars) but not all 3 without treating "x" separately. token1 matches the full 3-char sequence, so token1 wins.',
        'color'   => 'purple',
        'image'   => null,
    ],
    [
        'q'      => 'What is the TWO-STEP process to generate and run a SableCC scanner from a grammar file named "myLang.grammar"?',
        'choices' => [
            'A' => 'Step 1: javac myLang.grammar  →  Step 2: java myLang.Lexer',
            'B' => 'Step 1: sablecc myLang.grammar  →  Step 2: javac myLang/*.java, then java myLang.ClassName',
            'C' => 'Step 1: lex myLang.grammar  →  Step 2: gcc lex.yy.c -o scanner',
            'D' => 'Step 1: antlr myLang.grammar  →  Step 2: javac myLang/*.java',
        ],
        'answer'  => 'B',
        'explain' => 'Step 1: Run "sablecc myLang.grammar" to generate Java class files into a sub-directory named myLang. Step 2: Copy your main class (e.g. MyLang.java) into the sub-directory, compile with "javac myLang/*.java", then run with "java myLang.MyLang".',
        'color'   => 'amber',
        'image'   => null,
    ],

    // ── 2.5 Decaf Case Study ──────────────────────────────────────
    [
        'q'      => 'In the Decaf SableCC grammar, WHY is the keyword "class" defined as "clas" (with one s)?',
        'choices' => [
            'A' => 'Decaf only supports a subset of class features, so the shorter name reflects its limitations',
            'B' => 'SableCC does not permit "class" as a token name — it is a reserved name in SableCC itself',
            'C' => '"class" conflicts with the Java class keyword used in the generated lexer code',
            'D' => 'The original Decaf specification misspelled the keyword intentionally',
        ],
        'answer'  => 'B',
        'explain' => 'The textbook explicitly states: "The keyword class is an exception; for some reason SableCC will not permit the use of class as a name, so it is shortened to clas." SableCC reserves or conflicts with "class" internally, so the grammar author must use an alternative name.',
        'color'   => 'green',
        'image'   => null,
    ],
    [
        'q'      => 'In the Decaf token definition:\n\nnumber = (digits \'.\' ? digits? | \'.\' digits) exp? ;\n\nWhich of the following strings would be accepted as a valid number token?',
        'choices' => [
            'A' => '".25" — a decimal number starting with a dot',
            'B' => '"e10" — an exponent without a mantissa',
            'C' => '"1." — a trailing dot after an integer with no fraction',
            'D' => 'Both A and C are valid',
        ],
        'answer'  => 'D',
        'explain' => 'The pattern (digits \'.\' ? digits? | \'.\' digits) allows: (1) digits followed by optional dot and optional digits — so "1." is valid (digits=1, dot present, digits?=nothing); (2) a dot followed by digits — so ".25" is valid. Exercise 3 in §2.5 asks students to EXCLUDE these forms, confirming they are accepted by the default definition.',
        'color'   => 'cyan',
        'image'   => null,
    ],
    [
        'q'      => 'The Decaf compiler is described as a TWO-PASS compiler. What are the outputs of the FIRST pass (implemented with SableCC)?',
        'choices' => [
            'A' => 'Machine code for the mini simulated machine',
            'B' => 'A file of atoms and a file of numeric constants, used as input for the code generator',
            'C' => 'An optimised intermediate representation and a symbol table in XML format',
            'D' => 'A parse tree and a type-checking report',
        ],
        'answer'  => 'B',
        'explain' => 'The textbook states: "The result [of the first pass] is a file of atoms, and a file of numeric constants. These two files form the input for the code generator, which produces machine code for a simulated machine, called mini."',
        'color'   => 'rose',
        'image'   => null,
    ],
    [
        'q'      => 'To make the Decaf keyword "while" case-insensitive (so WHILE, While, wHiLe all match), which SableCC token definition approach would work correctly?',
        'choices' => [
            'A' => 'while = \'while\' | \'WHILE\' ;  — only upper and lower are checked',
            'B' => 'while = [\'w\'+\'W\'][\'h\'+\'H\'][\'i\'+\'I\'][\'l\'+\'L\'][\'e\'+\'E\'] ;  — each character allows upper or lower case',
            'C' => 'while = ignore_case(\'while\') ;  — SableCC provides an ignore_case() function',
            'D' => 'while = [\'a\'..\'z\']+ ;  — the general identifier rule already covers all cases',
        ],
        'answer'  => 'B',
        'explain' => 'The textbook explicitly shows this technique: "while = [\'w\'+\'W\'][\'h\'+\'H\'][\'i\'+\'I\'][\'l\'+\'L\'][\'e\'+\'E\'];" — each position in the keyword uses a union of its upper and lower-case character. SableCC has no built-in ignore_case function.',
        'color'   => 'indigo',
        'image'   => null,
    ],
    [
        'q'      => 'In the Decaf Helpers section, what is the purpose of the non_star_slash helper, and in which token definition is it used?',
        'choices' => [
            'A' => 'It matches any character that is not a forward slash; used in comment1 to stop at end of line',
            'B' => 'It matches any unicode character that is neither * nor /; used inside comment2 to handle the interior of multi-line comments',
            'C' => 'It matches any character that is not a digit or letter; used inside the identifier token',
            'D' => 'It matches arithmetic operators; used in the expression parser',
        ],
        'answer'  => 'B',
        'explain' => 'non_star_slash = [[0..0xffff] - [\'*\' + \'/\']]; — it matches any unicode character except asterisk or slash. It appears in comment2 (multi-line comments) in the part (non_star_slash non_star* \'*\'+)*, which handles interior sequences that contain neither * followed by / together.',
        'color'   => 'teal',
        'image'   => null,
    ],
    [
        'q'      => 'Instead of defining a separate token for each Decaf keyword (clas, public, static, …), a student proposes a SINGLE "keyword" token. What change is ALSO required to make this work correctly with identifiers?',
        'choices' => [
            'A' => 'The keyword token must be listed AFTER the identifier token so identifiers take priority',
            'B' => 'The keyword token must be listed BEFORE the identifier token, and the parser must distinguish keywords from identifiers by checking a lookup table',
            'C' => 'No change is needed — SableCC automatically separates keywords from identifiers',
            'D' => 'The identifier token must be removed entirely since keywords now cover all letter sequences',
        ],
        'answer'  => 'B',
        'explain' => 'If a single keyword token is used, it must be listed before identifier so the longest-match / first-listed tie-breaking rules correctly identify reserved words. The parser then uses a lookup table or grammar rule to distinguish keyword sub-types. Exercise 4 of §2.5 asks exactly this change.',
        'color'   => 'purple',
        'image'   => null,
    ],
    [
        'q'      => 'Which of the following is listed as an IGNORED TOKEN in the Decaf SableCC grammar, meaning the parser never sees it?',
        'choices' => [
            'A' => 'identifier and number — they are processed during semantic analysis',
            'B' => 'space, comment1, and comment2 — whitespace and comments are filtered before parsing',
            'C' => 'semi and comma — punctuation is handled entirely by the lexer',
            'D' => 'misc — unrecognised characters are silently discarded',
        ],
        'answer'  => 'B',
        'explain' => 'The textbook states: "Comments are listed with white space as Ignored Tokens, i.e. the parser never even sees these tokens." The Decaf grammar lists space, comment1, and comment2 as ignored tokens so the syntax analyser only receives meaningful language tokens.',
        'color'   => 'amber',
        'image'   => null,
    ],
    [
        'q'      => 'The Decaf token definition includes:\n\nmisc = [0..0xffff] ;\n\nWhat is the purpose of this catch-all token, and what typically happens when the parser receives it?',
        'choices' => [
            'A' => 'It silently discards any unrecognised character so scanning continues cleanly',
            'B' => 'It converts unrecognised characters to their unicode code point for later processing',
            'C' => 'It matches any single character not covered by earlier tokens, and the parser will most likely report a syntax error upon receiving it',
            'D' => 'It triggers a state transition to an error recovery state in SableCC',
        ],
        'answer'  => 'C',
        'explain' => 'The textbook says: "Any character which has not been matched as part of the above tokens is called a misc token, and will most likely cause the parser to report a syntax error." misc ensures the scanner never crashes on unknown input — instead it passes the responsibility for error reporting to the parser.',
        'color'   => 'green',
        'image'   => null,
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
        $userAns  = $_POST['q' . $i] ?? null;
        $answered = $userAns !== null;
        $correct  = $answered && $userAns === $q['answer'];
        if ($correct) $score++;
        $results[] = ['answered' => $answered, 'userAns' => $userAns, 'correct' => $correct];
    }

    $basePoints  = $score * 150; // Hard = 150 pts per correct
    $bonusPoints = 0;
    $bonusReason = '';

    if ($score === $total && $timeRemaining > 5 * 60) {
        $bonusPoints = 300;
        $bonusReason = '⚡ Perfect score with over 5 minutes to spare!';
    }

    $totalPoints = $basePoints + $bonusPoints;

    $result = $conn->query("
        SELECT board_id FROM leaderboard
        ORDER BY CAST(SUBSTRING(board_id, 3) AS UNSIGNED) DESC LIMIT 1
    ");

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_number = (int) substr($row['board_id'], 2);
        $next_number = $last_number + 1;
    } else {
        $next_number = 1;
    }

    $board_id  = 'BD' . $next_number;
    $board_chap = 2;
    $quiz_type  = 'Hard';
    $scorepoint = $totalPoints;

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
    <title>Chapter 2 – Hard Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../css/body.css">
    <link rel="stylesheet" href="norm.css">
    <style>
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

        <?php endforeach; ?><?php for ($i = 1; $i <= 20; $i++) echo ".q-card:nth-child($i){animation-delay:" . ($i * .04) . "s;}"; ?>@keyframes rise {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Hard difficulty badge */
        .difficulty-chip {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        /* Monospace code blocks in questions */
        .q-text code,
        .q-text pre {
            font-family: 'Courier New', monospace;
            background: rgba(0, 0, 0, .05);
            border-radius: 4px;
            padding: 2px 5px;
            font-size: 13px;
        }

        .q-text pre {
            padding: 10px 14px;
            margin: 8px 0 4px;
            white-space: pre-wrap;
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

    <!-- ── Page header ── -->
    <div class="page-header">
        <span class="difficulty-chip">🔥 Hard</span>
        <h1>Chapter 2 — <span class="accent">Lexical Analysis</span></h1>
        <p>20 advanced questions covering Lexical Tables, SableCC, and the Decaf Case Study · <?= $total ?> Questions</p>
    </div>

    <!-- ── Timer ── -->
    <?php if (!$submitted): ?>
        <div class="timer-wrap">
            <div class="timer-box" id="timerBox">
                <span class="timer-icon">⏱</span>
                <span id="timerDisplay">30:00</span>
            </div>
        </div>
    <?php endif; ?>

    <div class="page-layout">

        <!-- ── Left nav ── -->
        <nav class="q-nav" aria-label="Question navigator">
            <div class="q-nav-label">Questions</div>
            <?php foreach ($questions as $i => $q):
                $nb = 'q-nav-btn';
                if ($submitted && isset($results[$i])) {
                    $r = $results[$i];
                    if (!$r['answered'])  $nb .= ' nav-skipped';
                    elseif ($r['correct']) $nb .= ' nav-correct';
                    else                   $nb .= ' nav-wrong';
                }
            ?>
                <a href="#q-<?= $i ?>" class="<?= $nb ?>" id="nav-btn-<?= $i ?>"><?= $i + 1 ?></a>
            <?php endforeach; ?>

            <?php if (!$submitted): ?>
                <div class="nav-submit-wrap">
                    <button class="nav-submit-btn" onclick="document.getElementById('quizForm').requestSubmit()">Submit →</button>
                </div>
            <?php endif; ?>
            <div class="nav-count" id="nav-count">0 / <?= $total ?></div>
        </nav>

        <!-- ── Quiz wrap ── -->
        <div class="quiz-wrap">

            <?php if ($submitted): ?>
                <!-- Score card -->
                <div class="score-card">
                    <div class="score-row">
                        <div class="score-stat">
                            <div class="score-big"><?= $pct ?>%</div>
                            <div class="score-lbl">Score</div>
                        </div>
                        <div class="score-stat">
                            <div class="score-big stat-correct"><?= $score ?></div>
                            <div class="score-lbl">Correct</div>
                        </div>
                        <div class="score-stat">
                            <div class="score-big stat-wrong"><?= $total - $score ?></div>
                            <div class="score-lbl">Wrong / Skipped</div>
                        </div>
                    </div>
                    <div class="score-bar">
                        <div class="score-bar-fill" style="width:<?= $pct ?>%"></div>
                    </div>
                    <div class="score-msg">
                        <?php
                        if ($pct === 100)       echo '🏆 Flawless! You mastered Chapter 2 at Hard level!';
                        elseif ($pct >= 80)     echo '🔥 Outstanding performance on a tough quiz!';
                        elseif ($pct >= 60)     echo '💪 Solid effort — review the explanations to close the gaps.';
                        else                    echo '📖 Keep studying — the explanations below will help!';
                        ?>
                    </div>
                    <a class="btn-retry" href="">🔁 Retry</a>
                </div>
            <?php endif; ?>

            <form method="POST" id="quizForm">
                <input type="hidden" name="time_remaining" id="timeRemainingInput" value="0">

                <?php foreach ($questions as $i => $q):
                    $r = $submitted && isset($results[$i]) ? $results[$i] : null;
                    $cardCls = 'q-card color-' . $q['color'];
                    if ($submitted && $r) {
                        if (!$r['answered'])   $cardCls .= ' result-skipped';
                        elseif ($r['correct']) $cardCls .= ' result-correct';
                        else                   $cardCls .= ' result-wrong';
                    }
                ?>
                    <div class="<?= $cardCls ?>" id="q-<?= $i ?>">

                        <?php if ($submitted && $r): ?>
                            <?php if (!$r['answered']): ?>
                                <span class="result-badge badge-skipped">⚪ Skipped</span>
                            <?php elseif ($r['correct']): ?>
                                <span class="result-badge badge-correct">✔ Correct</span>
                            <?php else: ?>
                                <span class="result-badge badge-wrong">✘ Wrong — Answer: <?= $q['answer'] ?></span>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="q-num">Question <?= $i + 1 ?> of <?= $total ?></div>
                        <div class="q-text"><?= nl2br(htmlspecialchars($q['q'])) ?></div>

                        <div class="choices">
                            <?php foreach ($q['choices'] as $letter => $text):
                                $bc = 'choice-btn';
                                if ($submitted && $r) {
                                    $bc .= ' disabled';
                                    if ($letter === $q['answer'])                        $bc .= ' correct-ans';
                                    elseif ($r['answered'] && $letter === $r['userAns']) $bc .= ' wrong-ans';
                                }
                                $chk = ($submitted && $r && $r['answered'] && $r['userAns'] === $letter);
                            ?>
                                <button type="button" class="<?= $bc ?>" data-qi="<?= $i ?>" data-val="<?= $letter ?>"
                                    onclick="selectChoice(this)" <?= $submitted ? 'disabled' : '' ?>>
                                    <span class="letter"><?= $letter ?></span>
                                    <span><?= htmlspecialchars($text) ?></span>
                                    <input type="radio" name="q<?= $i ?>" value="<?= $letter ?>" style="display:none" <?= $chk ? 'checked' : '' ?>>
                                </button>
                            <?php endforeach; ?>
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

        </div><!-- quiz-wrap -->
    </div><!-- page-layout -->

    <script>
        const total = <?= $total ?>;
        let answered = new Set();

        function selectChoice(btn) {
            const qi = btn.dataset.qi,
                val = btn.dataset.val;
            document.querySelectorAll(`.choice-btn[data-qi="${qi}"]`).forEach(b => {
                b.classList.remove('selected');
                b.querySelector('input[type=radio]').checked = false;
            });
            btn.classList.add('selected');
            btn.querySelector('input[type=radio]').checked = true;
            answered.add(qi);
            const nb = document.getElementById('nav-btn-' + qi);
            if (nb && !nb.classList.contains('nav-correct') && !nb.classList.contains('nav-wrong'))
                nb.classList.add('nav-answered');
            document.getElementById('nav-count').textContent = answered.size + ' / ' + total;
        }

        // Active nav highlight on scroll
        const cards = document.querySelectorAll('.q-card');
        const navBtns = document.querySelectorAll('.q-nav-btn');
        cards.forEach(c =>
            new IntersectionObserver(entries => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        const idx = e.target.id.split('-')[1];
                        navBtns.forEach(b => b.classList.remove('nav-active'));
                        const ab = document.getElementById('nav-btn-' + idx);
                        if (ab) ab.classList.add('nav-active');
                    }
                });
            }, {
                threshold: 0.35
            }).observe(c)
        );

        // Restore on back nav
        document.querySelectorAll('input[type=radio]:checked').forEach(inp => {
            const btn = inp.closest('.choice-btn');
            if (btn) {
                btn.classList.add('selected');
                const qi = btn.dataset.qi;
                answered.add(qi);
                const nb = document.getElementById('nav-btn-' + qi);
                if (nb) nb.classList.add('nav-answered');
            }
        });
        if (answered.size) document.getElementById('nav-count').textContent = answered.size + ' / ' + total;

        // ── Timer (30 min for hard) ──
        const DURATION = 30 * 60;
        const timerBox = document.getElementById('timerBox');
        const timerDisp = document.getElementById('timerDisplay');
        const timeInput = document.getElementById('timeRemainingInput');

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
                    if (timeInput) timeInput.value = 0;
                    document.getElementById('quizForm').submit();
                    return;
                }
                remaining--;
            }

            tick();
            const timerInterval = setInterval(tick, 1000);

            document.getElementById('quizForm').addEventListener('submit', function() {
                if (timeInput) timeInput.value = remaining;
            });
        }
    </script>

</body>

</html>