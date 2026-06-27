<?php
session_start();
include '../../dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

$questions = [
    // 10 MULTIPLE CHOICE QUESTIONS
    [
        'type' => 'mcq',
        'q' => 'In Chapter 2, lexical analysis identifies words in the source program and passes them to later compiler phases. What does each token consist of?',
        'choices' => [
            'A' => 'Only the original source character',
            'B' => 'A class and a value',
            'C' => 'A parse tree and a grammar rule',
            'D' => 'A machine instruction and address',
        ],
        'answer' => 'B',
        'explain' => 'The note states that words are passed as tokens, with each token consisting of a class and value.',
    ],
    [
        'type' => 'mcq',
        'q' => 'Which statement correctly describes comments during lexical analysis?',
        'choices' => [
            'A' => 'Comments must be detected but are not put out as tokens',
            'B' => 'Comments are always passed to syntax analysis as keyword tokens',
            'C' => 'Comments are stored in the numeric constant table',
            'D' => 'Comments are treated as identifiers',
        ],
        'answer' => 'A',
        'explain' => 'Chapter 2 says comments must be detected in lexical analysis, but they are not put out as tokens.',
    ],
    [
        'type' => 'mcq',
        'q' => 'A deterministic finite state machine must have which property?',
        'choices' => [
            'A' => 'At least two starting states',
            'B' => 'Exactly one arc leaving each state for each possible input symbol',
            'C' => 'No accepting states',
            'D' => 'Only one state in total',
        ],
        'answer' => 'B',
        'explain' => 'The note defines deterministic machines as completely specified with no contradictions: exactly one arc for each state and input symbol.',
    ],
    [
        'type' => 'mcq',
        'q' => 'In regular expressions from Chapter 2, what is the correct precedence from highest to lowest?',
        'choices' => [
            'A' => 'Union, concatenation, Kleene star',
            'B' => 'Concatenation, union, Kleene star',
            'C' => 'Kleene star, concatenation, union',
            'D' => 'Kleene star, union, concatenation',
        ],
        'answer' => 'C',
        'explain' => 'The note says Kleene star takes precedence over concatenation, and concatenation takes precedence over union.',
    ],
    [
        'type' => 'mcq',
        'q' => 'What language is described by the regular expression 1(0+1)*0?',
        'choices' => [
            'A' => 'All strings of zeros and ones',
            'B' => 'All strings beginning with 1 and ending with 0',
            'C' => 'All strings with an even number of ones',
            'D' => 'Only the string 10',
        ],
        'answer' => 'B',
        'explain' => 'Chapter 2 gives this expression as the set of all strings of zeros and ones which begin with 1 and end with 0.',
    ],
    [
        'type' => 'mcq',
        'q' => 'For the Java input while ( x33 <= 2.5e+33 - total ) calc ( x33 ) ; //! which item is not put out as a token?',
        'choices' => [
            'A' => 'while',
            'B' => '<=',
            'C' => ';',
            'D' => '//!',
        ],
        'answer' => 'D',
        'explain' => 'The note shows that the comment is not put out. The semicolon is still a special-character token.',
    ],
    [
        'type' => 'mcq',
        'q' => 'What does the lexical analysis phase do with the syntactically incorrect input } while if ( { ?',
        'choices' => [
            'A' => 'It checks syntax and rejects the whole input',
            'B' => 'It outputs five tokens corresponding to the five words',
            'C' => 'It outputs only while and if',
            'D' => 'It converts the symbols into numeric constants',
        ],
        'answer' => 'B',
        'explain' => 'The note says lexical analysis does not check proper syntax; that error is left for syntax analysis.',
    ],
    [
        'type' => 'mcq',
        'q' => 'In SableCC, which source-file section defines macros such as digit, letter, and newline?',
        'choices' => [
            'A' => 'Helpers',
            'B' => 'States',
            'C' => 'Tokens',
            'D' => 'Ignored Tokens',
        ],
        'answer' => 'A',
        'explain' => 'Chapter 2 describes Helpers as macros used by the Tokens section.',
    ],
    [
        'type' => 'mcq',
        'q' => 'In the Decaf case study, why is the keyword class named clas in the SableCC Tokens section?',
        'choices' => [
            'A' => 'Because Decaf does not support classes',
            'B' => 'Because SableCC will not permit class as a token name',
            'C' => 'Because class is treated as a comment',
            'D' => 'Because class is stored only in the symbol table',
        ],
        'answer' => 'B',
        'explain' => 'The note says class is an exception; for some reason SableCC will not permit class as a name, so it is shortened to clas.',
    ],
    [
        'type' => 'mcq',
        'q' => 'In the Decaf Tokens section, what does misc = [0..0xffff] represent?',
        'choices' => [
            'A' => 'Only valid identifiers',
            'B' => 'Only white space',
            'C' => 'Any character not matched as part of the previous tokens',
            'D' => 'Only arithmetic operators',
        ],
        'answer' => 'C',
        'explain' => 'The note says any character not matched as part of the above tokens is called a misc token and will likely cause a syntax error.',
    ],

    // 5 DRAG AND DROP QUESTIONS
    [
        'type' => 'drag',
        'q' => 'Drag each lexical item to the correct token class from Chapter 2.',
        'items' => [
            'item1' => 'while',
            'item2' => 'x33',
            'item3' => '<=',
            'item4' => '2.5e+33',
        ],
        'targets' => [
            'keyword' => 'Keyword',
            'identifier' => 'Identifier',
            'operator' => 'Operator',
            'numeric' => 'Numeric constant',
        ],
        'answer' => [
            'item1' => 'keyword',
            'item2' => 'identifier',
            'item3' => 'operator',
            'item4' => 'numeric',
        ],
        'explain' => 'Chapter 2 lists keywords, identifiers, operators, and numeric constants as lexical token classes.',
    ],
    [
        'type' => 'drag',
        'q' => 'Drag each regular-expression operation to its meaning.',
        'items' => [
            'item1' => 'Union',
            'item2' => 'Concatenation',
            'item3' => 'Kleene star',
            'item4' => 'Parentheses',
        ],
        'targets' => [
            'combine' => 'Set union of languages',
            'join' => 'Juxtaposition of strings',
            'repeat' => 'Zero or more repetitions',
            'group' => 'Controls precedence',
        ],
        'answer' => [
            'item1' => 'combine',
            'item2' => 'join',
            'item3' => 'repeat',
            'item4' => 'group',
        ],
        'explain' => 'The note defines union, concatenation, and Kleene star as the three regular-expression operations; parentheses specify precedence.',
    ],
    [
        'type' => 'drag',
        'q' => 'Drag each SableCC section or feature to its role.',
        'items' => [
            'item1' => 'Helpers',
            'item2' => 'States',
            'item3' => 'Tokens',
            'item4' => 'Ignored Tokens',
        ],
        'targets' => [
            'macros' => 'Defines useful macros',
            'modes' => 'Defines scanner modes',
            'patterns' => 'Defines named token patterns',
            'skip' => 'Tokens the parser never sees',
        ],
        'answer' => [
            'item1' => 'macros',
            'item2' => 'modes',
            'item3' => 'patterns',
            'item4' => 'skip',
        ],
        'explain' => 'Chapter 2 says SableCC lexical analysis uses Helpers, States, and Tokens; Decaf comments and white space are ignored tokens.',
    ],
    [
        'type' => 'drag',
        'q' => 'Drag each Decaf helper to the correct meaning.',
        'items' => [
            'item1' => 'letter',
            'item2' => 'digit',
            'item3' => 'digits',
            'item4' => 'exp',
        ],
        'targets' => [
            'single_letter' => 'One upper or lower case letter',
            'single_digit' => 'One numeric digit',
            'many_digits' => 'One or more digits',
            'exponent' => 'Exponent part of a numeric constant',
        ],
        'answer' => [
            'item1' => 'single_letter',
            'item2' => 'single_digit',
            'item3' => 'many_digits',
            'item4' => 'exponent',
        ],
        'explain' => 'These helper definitions are listed in the Decaf case study Helpers section.',
    ],
    [
        'type' => 'drag',
        'q' => 'Drag each SableCC command or Java command to the correct step.',
        'items' => [
            'item1' => 'sablecc lexing.grammar',
            'item2' => 'javac lexing/*.java',
            'item3' => 'java lexing.Lexing',
            'item4' => 'ctrl-z on Windows/DOS',
        ],
        'targets' => [
            'generate' => 'Generate Java class definitions',
            'compile' => 'Compile generated Java files',
            'run' => 'Execute the scanner',
            'eof' => 'Terminate standard input',
        ],
        'answer' => [
            'item1' => 'generate',
            'item2' => 'compile',
            'item3' => 'run',
            'item4' => 'eof',
        ],
        'explain' => 'Chapter 2 explains the two-step SableCC process, then running the scanner and ending input with the EOF character.',
    ],

    // 5 MIX AND MATCH QUESTIONS
    [
        'type' => 'match',
        'q' => 'Match each token class with the description from Chapter 2.',
        'left' => [
            'a' => 'Keyword',
            'b' => 'Identifier',
            'c' => 'Special character',
            'd' => 'White space',
        ],
        'right' => [
            'r1' => 'A word with predefined meaning to the compiler',
            'r2' => 'A programmer-created name for a construct',
            'r3' => 'Delimiter such as parentheses, braces, comma, or semicolon',
            'r4' => 'Spaces and tabs generally ignored except as delimiters',
        ],
        'answer' => [
            'a' => 'r1',
            'b' => 'r2',
            'c' => 'r3',
            'd' => 'r4',
        ],
        'explain' => 'These are the token categories and definitions listed in Section 2.1.',
    ],
    [
        'type' => 'match',
        'q' => 'Match each finite state machine term with its Chapter 2 meaning.',
        'left' => [
            'a' => 'Starting state',
            'b' => 'Accepting state',
            'c' => 'Transition function',
            'd' => 'Rejected string',
        ],
        'right' => [
            'r1' => 'State where the machine initially begins',
            'r2' => 'State that accepts the input if reached after all input is read',
            'r3' => 'Function of current state and input symbol returning a state',
            'r4' => 'Input that leaves the machine in a non-accepting state',
        ],
        'answer' => [
            'a' => 'r1',
            'b' => 'r2',
            'c' => 'r3',
            'd' => 'r4',
        ],
        'explain' => 'Section 2.0.2 defines these FSM components and how acceptance/rejection works.',
    ],
    [
        'type' => 'match',
        'q' => 'Match each Decaf token definition with the kind of token it recognizes.',
        'left' => [
            'a' => "compare = '==' | '<' | '>' | '<=' | '>=' | '!='",
            'b' => "identifier = letter (letter | digit | '_')*",
            'c' => "number = (digits '.'? digits? | '.'digits) exp?",
            'd' => "space = ' ' | 9 | newline",
        ],
        'right' => [
            'r1' => 'Relational operators',
            'r2' => 'Identifier beginning with a letter',
            'r3' => 'Numeric constant with optional decimal point and exponent',
            'r4' => 'White space including tab and newline',
        ],
        'answer' => [
            'a' => 'r1',
            'b' => 'r2',
            'c' => 'r3',
            'd' => 'r4',
        ],
        'explain' => 'These definitions are taken from the Decaf Tokens section in Chapter 2.',
    ],
    [
        'type' => 'match',
        'q' => 'Match each SableCC state notation with its meaning.',
        'left' => [
            'a' => '{statename} token = def;',
            'b' => '{statename->newstate} token = def;',
            'c' => 'token = def;',
            'd' => 'States def, currency;',
        ],
        'right' => [
            'r1' => 'Apply only in that state and remain in that state',
            'r2' => 'Apply in one state and change to another state',
            'r3' => 'Apply regardless of current scanner state',
            'r4' => 'Declare scanner states, with the first as start state',
        ],
        'answer' => [
            'a' => 'r1',
            'b' => 'r2',
            'c' => 'r3',
            'd' => 'r4',
        ],
        'explain' => 'Section 2.4 explains scanner states and the transition operator ->.',
    ],
    [
        'type' => 'match',
        'q' => 'Match each Chapter 2 concept with the table or output it relates to.',
        'left' => [
            'a' => 'Symbol table',
            'b' => 'Numeric constants table',
            'c' => 'Stream of tokens',
            'd' => 'File of atoms',
        ],
        'right' => [
            'r1' => 'Stores each identifier once and related information',
            'r2' => 'Stores converted numeric constants',
            'r3' => 'Output of lexical analysis, one token for each word',
            'r4' => 'One output of the Decaf syntax and lexical phases',
        ],
        'answer' => [
            'a' => 'r1',
            'b' => 'r2',
            'c' => 'r3',
            'd' => 'r4',
        ],
        'explain' => 'Chapter 2 discusses symbol/numeric tables, token streams, and Decaf producing a file of atoms and numeric constants.',
    ],
];

$total = count($questions);
$submitted = false;
$score = 0;
$results = [];

function isQuestionCorrect(array $q, array $post): bool
{
    if ($q['type'] === 'mcq') {
        return isset($post['answer']) && $post['answer'] === $q['answer'];
    }

    foreach ($q['answer'] as $key => $value) {
        if (!isset($post[$key]) || $post[$key] !== $value) {
            return false;
        }
    }

    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = true;
    $timeRemaining = intval($_POST['time_remaining'] ?? 0);

    foreach ($questions as $i => $q) {
        $postAnswer = $_POST['q' . $i] ?? [];
        if ($q['type'] === 'mcq') {
            $postAnswer = ['answer' => $_POST['q' . $i] ?? null];
        }

        $answered = !empty(array_filter($postAnswer, fn($v) => $v !== null && $v !== ''));
        $correct = $answered && isQuestionCorrect($q, $postAnswer);

        if ($correct) {
            $score++;
        }

        $results[$i] = [
            'answered' => $answered,
            'correct' => $correct,
            'userAns' => $postAnswer,
        ];
    }

    $basePoints = $score * 100;
    $bonusPoints = 0;
    $bonusReason = '';

    if ($score === $total && $timeRemaining > 5 * 60) {
        $bonusPoints = 200;
        $bonusReason = 'Perfect score with over 5 minutes to spare!';
    }

    $totalPoints = $basePoints + $bonusPoints;
    $scorepoint = $totalPoints;
    $quiz_id = 'Q006'; // Chapter 2 Hard

    $attemptResult = $conn->query("
        SELECT qa_id
        FROM quiz_attempt
        WHERE qa_id REGEXP '^QA[0-9]+$'
        ORDER BY CAST(SUBSTRING(qa_id, 3) AS UNSIGNED) DESC
        LIMIT 1
    ");

    if ($attemptResult && $attemptResult->num_rows > 0) {
        $attemptRow = $attemptResult->fetch_assoc();
        $nextAttemptNumber = (int)substr($attemptRow['qa_id'], 2) + 1;
    } else {
        $nextAttemptNumber = 1;
    }

    $qa_id = 'QA' . str_pad($nextAttemptNumber, 3, '0', STR_PAD_LEFT);

    $attemptStmt = $conn->prepare("
        INSERT INTO quiz_attempt
            (qa_id, user_id, quiz_id, scorepoint, answered_at)
        VALUES (?, ?, ?, ?, NOW())
    ");

    if (!$attemptStmt) {
        die('Unable to prepare quiz attempt: ' . $conn->error);
    }

    $attemptStmt->bind_param("sisi", $qa_id, $user_id, $quiz_id, $scorepoint);

    if (!$attemptStmt->execute()) {
        die('Unable to save quiz attempt: ' . $attemptStmt->error);
    }

    $attemptStmt->close();

    $leaderboardStmt = $conn->prepare("
        SELECT board_id, best_score
        FROM leaderboard
        WHERE user_id = ?
          AND quiz_id = ?
        LIMIT 1
    ");

    if (!$leaderboardStmt) {
        die('Unable to prepare leaderboard: ' . $conn->error);
    }

    $leaderboardStmt->bind_param("is", $user_id, $quiz_id);
    $leaderboardStmt->execute();

    $leaderboardResult = $leaderboardStmt->get_result();

    if ($leaderboardResult->num_rows > 0) {
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
                die('Unable to prepare leaderboard update: ' . $conn->error);
            }

            $board_id = $leaderboardRow['board_id'];
            $updateStmt->bind_param("is", $scorepoint, $board_id);

            if (!$updateStmt->execute()) {
                die('Unable to update leaderboard: ' . $updateStmt->error);
            }

            $updateStmt->close();
        }
    } else {
        $boardResult = $conn->query("
            SELECT board_id
            FROM leaderboard
            WHERE board_id REGEXP '^BD[0-9]+$'
            ORDER BY CAST(SUBSTRING(board_id, 3) AS UNSIGNED) DESC
            LIMIT 1
        ");

        if ($boardResult && $boardResult->num_rows > 0) {
            $boardRow = $boardResult->fetch_assoc();
            $nextBoardNumber = (int)substr($boardRow['board_id'], 2) + 1;
        } else {
            $nextBoardNumber = 1;
        }

        $board_id = 'BD' . $nextBoardNumber;

        $insertStmt = $conn->prepare("
            INSERT INTO leaderboard
                (board_id, user_id, quiz_id, best_score, updated_at)
            VALUES (?, ?, ?, ?, NOW())
        ");

        if (!$insertStmt) {
            die('Unable to prepare leaderboard insert: ' . $conn->error);
        }

        $insertStmt->bind_param("sisi", $board_id, $user_id, $quiz_id, $scorepoint);

        if (!$insertStmt->execute()) {
            die('Unable to save leaderboard: ' . $insertStmt->error);
        }

        $insertStmt->close();
    }

    $leaderboardStmt->close();
}

$pct = $submitted ? round($score / $total * 100) : 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Chapter 2 Hard Quiz</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>


    <style>
        body {
            background: #F4F3FF;
            font-family: 'Segoe UI', sans-serif;
            color: #111827;
        }

        /* Header */
        .page-header,
        .header {
            max-width: 1100px;
            margin: 0 auto;
            padding: 44px 24px 0;
            text-align: left;
        }

        .page-header h1,
        .header h1 {
            font-size: 32px;
            font-weight: 900;
            color: #1E1B4B;
            letter-spacing: -.5px;
        }

        .page-header h1 .accent,
        .header h1 .accent {
            color: #DC2626;
        }

        .page-header p,
        .header p {
            color: #6B7280;
            font-size: 14px;
            margin-top: 6px;
            max-width: 780px;
            line-height: 1.65;
        }

        .difficulty-chip,
        .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #FEE2E2, #FED7AA);
            color: #991B1B;
            font-size: 12px;
            font-weight: 800;
            padding: 5px 14px;
            border-radius: 20px;
            border: 1px solid #FB7185;
            margin-bottom: 8px;
            letter-spacing: .7px;
            text-transform: uppercase;
            box-shadow: 0 6px 18px rgba(239, 68, 68, .18);
        }

        /* Layout */
        .page-layout,
        .page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px 70px;
        }

        .page-layout {
            display: flex;
            align-items: flex-start;
        }

        .quiz-wrap {
            flex: 1;
            min-width: 0;
            margin: 28px 0 80px;
        }

        /* Left question nav */
        .q-nav {
            position: sticky;
            top: 80px;
            flex-shrink: 0;
            background: rgba(255, 255, 255, .92);
            backdrop-filter: blur(12px);
            border-radius: 18px;
            border: 1px solid #FBCFE8;
            box-shadow: 0 12px 28px rgba(190, 24, 93, .12);
            padding: 14px 12px 16px;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 7px;
            margin-top: 28px;
            margin-right: 20px;
            width: 235px;
        }

        .q-nav-label {
            grid-column: 1 / -1;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #9CA3AF;
            margin-bottom: 4px;
            text-align: center;
        }

        .q-nav-btn {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            border: 1.5px solid #E5E7EB;
            background: #FFFFFF;
            font-size: 12px;
            font-weight: 800;
            color: #6B7280;
            cursor: pointer;
            transition: all .18s;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .q-nav-btn:hover {
            background: #FFF1F2;
            border-color: #F43F5E;
            color: #BE123C;
            transform: translateY(-2px) scale(1.06);
        }

        .q-nav-btn.nav-answered,
        .q-nav-btn.nav-active {
            background: linear-gradient(135deg, #F43F5E, #8B5CF6);
            border-color: #F43F5E;
            color: #fff;
            box-shadow: 0 0 0 3px rgba(244, 63, 94, .2);
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

        .nav-count {
            grid-column: 1/-1;
            text-align: center;
            font-size: 11px;
            color: #9CA3AF;
            margin-top: 2px;
        }

        .nav-submit-wrap {
            grid-column: 1 / -1;
            margin-top: 10px;
        }

        .nav-submit-btn,
        .btn-submit {
            background: linear-gradient(135deg, #EF4444, #F97316, #8B5CF6);
            color: #fff;
            border: none;
            font-weight: 800;
            cursor: pointer;
            letter-spacing: .3px;
            box-shadow: 0 8px 20px rgba(239, 68, 68, .28);
            transition: transform .15s, box-shadow .15s;
        }

        .nav-submit-btn {
            width: 100%;
            padding: 9px 0;
            border-radius: 10px;
            font-size: 12px;
        }

        .btn-submit {
            padding: 15px 52px;
            font-size: 16px;
            border-radius: 14px;
        }

        .nav-submit-btn:hover,
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(239, 68, 68, .36);
        }

        /* Timer */
        .timer-wrap {
            position: sticky;
            top: 16px;
            z-index: 99;
            text-align: center;
            margin-bottom: 18px;
        }

        .timer-box,
        .timer {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #FFFFFF;
            border: 1.5px solid #FBCFE8;
            border-radius: 14px;
            padding: 10px 24px;
            box-shadow: 0 8px 24px rgba(244, 63, 94, .14);
            font-size: 22px;
            font-weight: 900;
            color: #E11D48;
            letter-spacing: 2px;
        }

        .timer-box.warning {
            border-color: #F59E0B;
            color: #92400E;
            background: #FFFBEB;
        }

        .timer-box.danger {
            border-color: #EF4444;
            color: #991B1B;
            background: #FFF1F2;
            animation: pulse .6s infinite alternate;
        }

        /* Question cards */
        .q-card {
            background: rgba(255, 255, 255, .95);
            border-radius: 18px;
            border: 1px solid #F3E8FF;
            border-left: 6px solid #F43F5E;
            box-shadow: 0 10px 28px rgba(15, 23, 42, .08);
            padding: 26px 28px;
            margin-bottom: 20px;
            animation: rise .45s ease forwards;
            transition: transform .18s, box-shadow .18s, border-color .18s;
        }

        .q-card:nth-of-type(4n + 1) {
            border-left-color: #F43F5E;
        }

        .q-card:nth-of-type(4n + 2) {
            border-left-color: #8B5CF6;
        }

        .q-card:nth-of-type(4n + 3) {
            border-left-color: #06B6D4;
        }

        .q-card:nth-of-type(4n + 4) {
            border-left-color: #F59E0B;
        }

        .q-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 38px rgba(15, 23, 42, .12);
        }

        .q-num,
        .q-top {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #BE123C;
            background: #FFF1F2;
            padding: 5px 12px;
            border-radius: 20px;
            margin-bottom: 12px;
        }

        .q-text {
            font-size: 15px;
            font-weight: 700;
            color: #1E1B4B;
            line-height: 1.65;
            margin-bottom: 14px;
            white-space: pre-line;
        }

        /* MCQ Choices */
        .choices {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .choice-btn,
        .choice {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 13px 15px;
            border: 1.5px solid #E5E7EB;
            border-radius: 12px;
            background: #fff;
            cursor: pointer;
            font-size: 14px;
            color: #374151;
            text-align: left;
            transition: all .18s;
            width: 100%;
        }

        .choice-btn:hover,
        .choice:hover {
            background: #FFF7ED;
            border-color: #FB923C;
            transform: translateX(3px);
        }

        .choice-btn .letter {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 27px;
            height: 27px;
            border-radius: 50%;
            background: #F3F4F6;
            font-size: 12px;
            font-weight: 800;
            color: #6B7280;
            flex-shrink: 0;
        }

        .choice-btn input[type="radio"] {
            display: none;
        }

        /* Drag and drop */
        .drag-wrap {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 18px;
        }

        .drag-bank,
        .drop-grid {
            min-height: 130px;
            padding: 15px;
            border: 1.5px dashed #C4B5FD;
            border-radius: 14px;
            background: linear-gradient(135deg, #FAF5FF, #EEF2FF);
        }

        .drop-grid {
            display: grid;
            gap: 10px;
        }

        .drag-item {
            display: inline-flex;
            align-items: center;
            margin: 6px;
            padding: 9px 13px;
            border: 0;
            border-radius: 999px;
            background: linear-gradient(135deg, #6366F1, #EC4899);
            color: #fff;
            font-size: 13px;
            font-weight: 800;
            cursor: grab;
            box-shadow: 0 6px 14px rgba(99, 102, 241, .22);
        }

        .drag-item.selected-drag {
            outline: 3px solid rgba(244, 63, 94, .28);
            transform: scale(1.05);
        }

        .drop-zone {
            min-height: 48px;
            padding: 10px;
            border: 1.5px solid #DDD6FE;
            border-radius: 12px;
            background: #fff;
            transition: border-color .18s, background .18s;
        }

        .drop-zone:hover {
            border-color: #8B5CF6;
            background: #F5F3FF;
        }

        .drop-label {
            display: block;
            margin-bottom: 6px;
            color: #4C1D95;
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        /* Mix and match */
        .match-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            align-items: center;
            margin-bottom: 12px;
            padding: 12px;
            border-radius: 14px;
            background: linear-gradient(135deg, #F8FAFC, #ECFEFF);
            border: 1px solid #CFFAFE;
        }

        .match-row>div:first-child {
            font-weight: 700;
            color: #164E63;
        }

        select {
            width: 100%;
            padding: 11px 12px;
            border: 1.5px solid #A5F3FC;
            border-radius: 12px;
            background: #fff;
            color: #155E75;
            font-weight: 600;
            outline: none;
        }

        select:focus {
            border-color: #06B6D4;
            box-shadow: 0 0 0 3px rgba(6, 182, 212, .16);
        }

        /* Results */
        .choice-btn.correct-ans,
        .choice-btn.correct-ans.answer-muted {
            opacity: 1;
            border-color: #22C55E !important;
            background: #F0FDF4 !important;
            color: #15803D !important;
            font-weight: 700;
        }

        .choice-btn.correct-ans .letter {
            background: #22C55E;
            color: #FFFFFF;
        }

        .choice-btn.wrong-ans {
            opacity: 1;
            border-color: #EF4444 !important;
            background: #FFF1F2 !important;
            color: #991B1B !important;
            font-weight: 700;
        }

        .choice-btn.wrong-ans .letter {
            background: #EF4444;
            color: #FFFFFF;
        }

        .choice-btn.answer-locked {
            cursor: default;
            pointer-events: none;
        }

        .choice-btn.answer-muted {
            opacity: 0.55;
        }

        .q-card.result-correct {
            border-left-color: #22C55E !important;
        }

        .q-card.result-wrong {
            border-left-color: #EF4444 !important;
        }

        .q-card.result-skipped {
            border-left-color: #9CA3AF !important;
        }

        .answer-feedback,
        .feedback {
            width: fit-content;
            margin-bottom: 10px;
            padding: 6px 13px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 800;
        }

        .answer-feedback {
            display: none;
        }

        .answer-feedback.show {
            display: block;
        }

        .badge-correct,
        .correct,
        .answer-feedback.badge-correct {
            color: #065F46;
            background: #D1FAE5;
        }

        .badge-wrong,
        .wrong,
        .answer-feedback.badge-wrong {
            color: #991B1B;
            background: #FEE2E2;
        }

        .badge-skipped,
        .answer-feedback.badge-skipped {
            color: #4B5563;
            background: #F3F4F6;
        }

        .explain-box,
        .explain {
            margin-top: 14px;
            padding: 13px 15px;
            background: linear-gradient(135deg, rgba(99, 102, 241, .08), rgba(236, 72, 153, .08));
            border-left: 4px solid #8B5CF6;
            border-radius: 0 10px 10px 0;
            font-size: 13px;
            color: #374151;
            line-height: 1.65;
        }

        .explain-box {
            display: none;
        }

        .explain-box.show {
            display: block;
        }

        /* Score card */
        .score-card {
            background: rgba(255, 255, 255, .96);
            border-radius: 20px;
            padding: 32px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 14px 36px rgba(99, 102, 241, .16);
            border: 1px solid #FBCFE8;
        }

        .score-row {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-bottom: 20px;
        }

        .score-big {
            font-size: 44px;
            font-weight: 900;
            color: #DC2626;
            line-height: 1;
        }

        .score-lbl {
            font-size: 11px;
            color: #9CA3AF;
            margin-top: 4px;
            font-weight: 700;
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
            background: #FFE4E6;
            border-radius: 20px;
            overflow: hidden;
            margin: 0 0 10px;
        }

        .score-bar-fill {
            height: 100%;
            border-radius: 20px;
            background: linear-gradient(90deg, #EF4444, #F97316, #8B5CF6);
            transition: width 1.1s ease;
        }

        .score-msg {
            font-size: 15px;
            font-weight: 800;
            color: #1E1B4B;
        }

        .btn-retry {
            display: inline-block;
            margin-top: 16px;
            padding: 11px 32px;
            background: #FFF1F2;
            color: #BE123C;
            font-weight: 800;
            font-size: 14px;
            border-radius: 12px;
            text-decoration: none;
            border: 1.5px solid #FDA4AF;
            transition: background .18s, transform .18s;
        }

        .btn-retry:hover {
            background: #FFE4E6;
            color: #9F1239;
            transform: translateY(-1px);
        }

        .submit-wrap {
            text-align: center;
            margin-top: 16px;
        }

        /* Animations */
        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            from {
                box-shadow: 0 4px 18px rgba(239, 68, 68, .15);
            }

            to {
                box-shadow: 0 4px 28px rgba(239, 68, 68, .45);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {

            .page-header,
            .header {
                padding: 28px 16px 0;
            }

            .page-header h1,
            .header h1 {
                font-size: 24px;
            }

            .page-layout,
            .page {
                display: block;
                padding: 0 14px 50px;
            }

            .q-nav {
                position: sticky;
                top: 0;
                z-index: 100;
                width: 100%;
                margin: 18px 0;
                grid-template-columns: repeat(5, 1fr);
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

            .choice-btn,
            .choice {
                padding: 11px;
                font-size: 13px;
            }

            .drag-wrap,
            .match-row {
                grid-template-columns: 1fr;
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

        #navbar {
            background-color: #18181B;
            width: 100%;
            z-index: 1050;
        }

        /* Navbar text — white on dark background */
        #navbar a,
        #navbar .nav-link {
            color: #FFFFFF;
        }

        /* Hover state — slightly muted so there's a visual reaction */
        #navbar a:hover,
        #navbar .nav-link:hover {
            color: #A1A1AA;
        }

        /* Active/current page — white + indigo underline */
        #navbar a.active,
        #navbar .nav-link.active {
            color: #FFFFFF;
            border-bottom: 4px solid #6366F1;
        }

        .button-28 {
            appearance: none;
            background-color: transparent;
            border: 2px solid #f7f3f3;
            border-radius: 15px;
            box-sizing: border-box;
            color: #504141;
            cursor: pointer;
            display: inline-block;
            font-size: 10px;
            font-weight: 600;
            line-height: normal;
            margin: 0;
            min-height: 30px;
            min-width: 0;
            outline: none;
            padding: 12px 20px;
            text-align: center;
            text-decoration: none;
            transition: all 300ms cubic-bezier(.23, 1, 0.32, 1);
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            width: auto;
            will-change: transform;
        }

        .button-28:disabled {
            pointer-events: none;
        }

        .button-28:hover {
            color: #fff;
            background-color: #1A1A1A;
            box-shadow: rgba(0, 0, 0, 0.25) 0 8px 15px;
            transform: translateY(-2px);
        }

        .button-28:active {
            box-shadow: none;
            transform: translateY(0);
        }

        /* left nav bar */
        .page-layout {
            display: flex;
            align-items: flex-start;
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
            gap: 20px;
        }

        .quiz-wrap {
            flex: 1;
            min-width: 0;
        }

        .q-nav {
            position: sticky;
            top: 80px;
            flex-shrink: 0;
            width: 230px;
            margin-top: 28px;
            padding: 14px 12px 16px;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 7px;
            background: rgba(255, 255, 255, .95);
            border: 1px solid #FBCFE8;
            border-radius: 18px;
            box-shadow: 0 12px 28px rgba(190, 24, 93, .12);
        }

        .q-nav-label {
            grid-column: 1 / -1;
            text-align: center;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #9CA3AF;
            margin-bottom: 4px;
        }

        .q-nav-btn {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            border: 1.5px solid #E5E7EB;
            background: #FFFFFF;
            font-size: 12px;
            font-weight: 800;
            color: #6B7280;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all .18s;
        }

        .q-nav-btn:hover {
            background: #FFF1F2;
            border-color: #F43F5E;
            color: #BE123C;
            transform: translateY(-2px) scale(1.06);
        }

        .q-nav-btn.nav-active,
        .q-nav-btn.nav-answered {
            background: linear-gradient(135deg, #F43F5E, #8B5CF6);
            border-color: #F43F5E;
            color: #fff;
            box-shadow: 0 0 0 3px rgba(244, 63, 94, .2);
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

        .nav-count {
            grid-column: 1 / -1;
            text-align: center;
            font-size: 11px;
            font-weight: 700;
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
            background: linear-gradient(135deg, #EF4444, #F97316, #8B5CF6);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(239, 68, 68, .28);
        }

        .nav-submit-btn:hover {
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
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
                grid-template-columns: repeat(5, 1fr);
            }

            .q-nav-btn {
                width: 100%;
                max-width: 42px;
                margin: auto;
            }
        }

        .q-nav {
            box-sizing: border-box;
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }

      .q-nav>.timer {
            box-sizing: border-box;
            grid-column: 1 / -1;
            width: 100%;
            justify-content: center;
            justify-self: stretch;
            padding: 10px 12px;
            margin-bottom: 0;
        }

        .q-nav-btn {
            justify-self: center;
        }

        .q-nav-label,
        .nav-count,
        .nav-submit-wrap {
            min-width: 0;
        }

        .nav-submit-btn {
            box-sizing: border-box;
            display: block;
            max-width: 100%;
        }

        @media (max-width: 768px) {
            .q-nav {
                grid-template-columns: repeat(5, minmax(0, 1fr));
            }
        }



        /* Live answer results */
        .q-card.result-correct {
            border-left: 5px solid #22C55E !important;
            background: #FBF7FF;
        }

        .q-card.result-wrong {
            border-left: 5px solid #FF4D5E !important;
            background: #F2FFFC;
        }

        .q-card.result-correct:hover,
        .q-card.result-wrong:hover {
            transform: none;
        }

        /* Correct or wrong badge */
        .live-feedback {
            display: none;
            width: fit-content;
            margin: 0 0 14px;
            padding: 7px 13px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            line-height: 1.3;
        }

        .live-feedback.show {
            display: block;
        }

        .live-feedback.show.correct {
            color: #047857;
            background: #C9F7DF;
        }

        .live-feedback.show.wrong {
            color: #B91C1C;
            background: #FEE2E2;
        }

        /* MCQ answer choices */
        .choice.answer-option {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 48px;
            padding: 10px 13px;
            border: 1px solid #E5E7EB;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.78);
            color: #374151;
            cursor: pointer;
            transition:
                border-color 0.18s,
                background 0.18s,
                color 0.18s,
                transform 0.18s;
        }

        .choice.answer-option:hover {
            border-color: #8B5CF6;
            background: #FAF5FF;
            transform: translateX(3px);
        }

        .choice.answer-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .choice-letter {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 26px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #F1F5F9;
            color: #94A3B8;
            font-size: 12px;
            font-weight: 800;
        }

        .choice-text {
            flex: 1;
            min-width: 0;
            line-height: 1.45;
        }

        /* Correct answer */
        .choice.answer-option.correct-live {
            opacity: 1 !important;
            border-color: #22C55E !important;
            background: #EFFDF4 !important;
            color: #15803D !important;
            font-weight: 700;
        }

        .choice.answer-option.correct-live .choice-letter {
            color: #FFFFFF;
            background: #22C55E;
        }

        /* Selected wrong answer */
        .choice.answer-option.wrong-live {
            opacity: 1 !important;
            border-color: #FF4D5E !important;
            background: #FFF1F2 !important;
            color: #B91C1C !important;
            font-weight: 700;
        }

        .choice.answer-option.wrong-live .choice-letter {
            color: #FFFFFF;
            background: #FF4D5E;
        }

        /* Unselected answers after checking */
        .choice.answer-option.muted {
            opacity: 0.52;
        }

        .choice.answer-option.locked {
            pointer-events: none;
            cursor: default;
        }

        /* Explanation shown after answering */
        .live-explain {
            display: none;
            align-items: flex-start;
            gap: 8px;
            margin-top: 13px;
            padding: 12px 14px;
            border-left: 2px solid #6366F1;
            border-radius: 0 8px 8px 0;
            background: #EEF2FF;
            color: #374151;
            font-size: 13px;
            line-height: 1.55;
        }

        .live-explain.show {
            display: flex;
        }

        .explain-icon {
            flex-shrink: 0;
        }

        /* Drag-and-drop results */
        .drop-zone.correct-live {
            border-color: #22C55E !important;
            background: #EFFDF4 !important;
        }

        .drop-zone.wrong-live {
            border-color: #FF4D5E !important;
            background: #FFF1F2 !important;
        }

        /* Match question results */
        .match-row.correct-live {
            border-color: #22C55E !important;
            background: #EFFDF4 !important;
        }

        .match-row.wrong-live {
            border-color: #FF4D5E !important;
            background: #FFF1F2 !important;
        }

        /* Responsive answer layouts */
        @media (max-width: 768px) {
            .drag-wrap {
                grid-template-columns: 1fr;
            }

            .drop-grid {
                grid-template-columns: 1fr;
            }

            .match-row {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .drag-item {
                max-width: 100%;
                white-space: normal;
                text-align: left;
            }
        }

        @media (max-width: 600px) {
            .choice.answer-option {
                align-items: flex-start;
                padding: 11px;
            }

            .choice-letter {
                flex-basis: 24px;
                width: 24px;
                height: 24px;
            }

            .live-feedback {
                max-width: 100%;
                white-space: normal;
            }

            .live-explain {
                padding: 11px 12px;
            }
        }

        .control-locked {
            pointer-events: none;
            cursor: default;
        }
    </style>
</head>

<body>
    <audio id="correctSound"
        src="../../sound/correct answer.mp3"
        preload="auto"></audio>

    <audio id="wrongSound"
        src="../../sound/wrong answer.mp3"
        preload="auto"></audio>

    <audio id="submitSound"
        src="../../sound/alert2.mp3"
        preload="auto"></audio>

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

    <br><br><br>
    <div class="page">
        <div class="header">
            <div class="chip">Hard Level</div>
            <h1>Chapter 2 Hard Quiz</h1>
            <p>20 mixed questions based only on Chapter 2: Lexical Analysis, formal languages, FSMs, regular expressions, SableCC, and Decaf lexical rules.</p>
        </div>



        <?php if ($submitted): ?>
            <?php
            $basePoints = $score * 100;
            $bonusPoints = $bonusPoints ?? 0;
            $totalPoints = $totalPoints ?? $basePoints;
            ?>
            <div class="score-card">
                <h2><?= $score ?> / <?= $total ?></h2>
                <p>Percentage: <strong><?= $pct ?>%</strong></p>
                <p>Points earned: <strong><?= $totalPoints ?></strong></p>
                <?php if (!empty($bonusReason)): ?>
                    <p><?= htmlspecialchars($bonusReason) ?></p>
                <?php endif; ?>
                <a class="btn-retry" href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">Retry Quiz</a>
            </div>
        <?php endif; ?>

        <div class="page-layout">

            <!-- Left Question Nav -->
            <nav class="q-nav" id="qNav">
                <?php if (!$submitted): ?>
                    <div class="timer" id="timerDisplay">35:00</div>
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
                    <a href="#q-<?= $i ?>" class="<?= $navClass ?>" id="nav-btn-<?= $i ?>">
                        <?= $i + 1 ?>
                    </a>
                <?php endfor; ?>

                <div class="nav-count" id="nav-count">
                    <?= $submitted ? $score . ' / ' . $total : '0 / ' . $total ?>
                </div>

                <?php if (!$submitted): ?>
                    <div class="nav-submit-wrap">
                        <button type="submit" form="quizForm" class="nav-submit-btn">
                            Submit →
                        </button>
                    </div>
                <?php endif; ?>
            </nav>

            <div class="quiz-wrap">

                <form method="POST" id="quizForm">
                    <input type="hidden" name="time_remaining" id="timeRemainingInput" value="0">

                    <?php foreach ($questions as $i => $q): ?>
                        <?php $r = $results[$i] ?? null; ?>

                        <div
                            id="q-<?= $i ?>"
                            class="q-card"
                            data-question="<?= $i ?>"
                            data-type="<?= htmlspecialchars($q['type']) ?>"
                            data-answer="<?= htmlspecialchars(
                                                $q['type'] === 'mcq'
                                                    ? $q['answer']
                                                    : json_encode($q['answer'])
                                            ) ?>">

                            <?php if (!$submitted): ?>
                                <div
                                    class="live-feedback"
                                    id="live-feedback-<?= $i ?>">
                                </div>
                            <?php endif; ?>

                            <div class="q-top">
                                <span>Question <?= $i + 1 ?> of <?= $total ?></span>
                                <span><?= strtoupper(str_replace('_', ' ', $q['type'])) ?></span>
                            </div>

                            <div class="q-text">
                                <?= htmlspecialchars($q['q']) ?>
                            </div>

                            <?php if ($q['type'] === 'mcq'): ?>
                                <div class="choices">
                                    <?php foreach ($q['choices'] as $letter => $choice): ?>
                                        <label
                                            class="choice answer-option"
                                            data-qi="<?= $i ?>"
                                            data-val="<?= htmlspecialchars($letter) ?>">

                                            <input
                                                type="radio"
                                                name="q<?= $i ?>"
                                                value="<?= htmlspecialchars($letter) ?>"
                                                <?= $submitted ? 'disabled' : '' ?>
                                                <?= isset($r['userAns']['answer']) &&
                                                    $r['userAns']['answer'] === $letter
                                                    ? 'checked'
                                                    : '' ?>>

                                            <span class="choice-letter">
                                                <?= htmlspecialchars($letter) ?>
                                            </span>

                                            <span class="choice-text">
                                                <?= htmlspecialchars($choice) ?>
                                            </span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($q['type'] === 'drag'): ?>
                                <div class="drag-wrap">
                                    <div class="drag-bank" id="bank-<?= $i ?>">
                                        <?php foreach ($q['items'] as $itemId => $itemText): ?>
                                            <?php $selectedTarget = $r['userAns'][$itemId] ?? ''; ?>
                                            <button
                                                type="button"
                                                class="drag-item"
                                                draggable="<?= $submitted ? 'false' : 'true' ?>"
                                                data-qi="<?= $i ?>"
                                                data-item="<?= htmlspecialchars($itemId) ?>"
                                                ondragstart="dragStart(event)">
                                                <?= htmlspecialchars($itemText) ?>
                                            </button>

                                            <input
                                                type="hidden"
                                                name="q<?= $i ?>[<?= htmlspecialchars($itemId) ?>]"
                                                id="input-<?= $i ?>-<?= htmlspecialchars($itemId) ?>"
                                                value="<?= htmlspecialchars($selectedTarget) ?>">
                                        <?php endforeach; ?>
                                    </div>

                                    <div class="drop-grid">
                                        <?php foreach ($q['targets'] as $targetId => $targetText): ?>
                                            <div
                                                class="drop-zone"
                                                data-qi="<?= $i ?>"
                                                data-target="<?= htmlspecialchars($targetId) ?>"
                                                ondragover="allowDrop(event)"
                                                ondrop="dropItem(event)">
                                                <span class="drop-label"><?= htmlspecialchars($targetText) ?></span>

                                                <?php if ($submitted): ?>
                                                    <?php foreach ($q['items'] as $itemId => $itemText): ?>
                                                        <?php if (($r['userAns'][$itemId] ?? '') === $targetId): ?>
                                                            <span class="drag-item"><?= htmlspecialchars($itemText) ?></span>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($q['type'] === 'match'): ?>
                                <?php foreach ($q['left'] as $leftId => $leftText): ?>
                                    <div class="match-row">
                                        <div><?= htmlspecialchars($leftText) ?></div>
                                        <select name="q<?= $i ?>[<?= htmlspecialchars($leftId) ?>]" <?= $submitted ? 'disabled' : '' ?>>
                                            <option value="">Choose match</option>
                                            <?php foreach ($q['right'] as $rightId => $rightText): ?>
                                                <option
                                                    value="<?= htmlspecialchars($rightId) ?>"
                                                    <?= isset($r['userAns'][$leftId]) && $r['userAns'][$leftId] === $rightId ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($rightText) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <?php if ($submitted && $r): ?>
                                <?php if ($r['correct']): ?>
                                    <div class="feedback correct">Correct</div>
                                <?php else: ?>
                                    <div class="feedback wrong">Wrong or incomplete</div>
                                <?php endif; ?>

                                <div class="explain">
                                    <?= htmlspecialchars($q['explain']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <?php if (!$submitted): ?>
                        <div class="submit-wrap">
                            <button class="btn-submit" type="submit">Submit Answers</button>
                        </div>
                    <?php endif; ?>
                    <?php if (!$submitted): ?>
                        <div
                            class="explain live-explain"
                            id="live-explain-<?= $i ?>">
                            <span class="explain-icon">💡</span>
                            <span><?= htmlspecialchars($q['explain']) ?></span>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <script>
                const submitted = <?= $submitted ? 'true' : 'false' ?>;
                const totalQuestions = <?= $total ?>;
                const answeredQuestions = new Set();
                let selectedDragItem = null;

                function playSound(soundId) {
                    const sound = document.getElementById(soundId);
                    if (!sound) return;

                    sound.currentTime = 0;
                    sound.play().catch(function() {});
                }

                function markQuestionAnswered(questionIndex, isCorrect = null) {
                    if (submitted) return;

                    answeredQuestions.add(String(questionIndex));

                    const navButton = document.getElementById("nav-btn-" + questionIndex);
                    if (navButton) {
                        navButton.classList.add("nav-answered");

                        if (isCorrect === true) {
                            navButton.classList.remove("nav-wrong");
                            navButton.classList.add("nav-correct");
                        }

                        if (isCorrect === false) {
                            navButton.classList.remove("nav-correct");
                            navButton.classList.add("nav-wrong");
                        }
                    }

                    const navCount = document.getElementById("nav-count");
                    if (navCount) {
                        navCount.textContent = answeredQuestions.size + " / " + totalQuestions;
                    }
                }

                function showLiveFeedback(questionIndex, isCorrect) {
                    const card = document.getElementById("q-" + questionIndex);
                    const feedback = document.getElementById(
                        "live-feedback-" + questionIndex
                    );
                    const explain = document.getElementById(
                        "live-explain-" + questionIndex
                    );

                    if (!card) return;

                    card.classList.remove("result-correct", "result-wrong");
                    card.classList.add(
                        isCorrect ? "result-correct" : "result-wrong"
                    );

                    if (feedback) {
                        if (isCorrect) {
                            feedback.textContent = "✓ Correct";
                        } else if (card.dataset.type === "mcq") {
                            feedback.textContent =
                                "✕ Wrong — Correct answer: " +
                                card.dataset.answer;
                        } else {
                            feedback.textContent =
                                "✕ Wrong — Correct answers are highlighted";
                        }

                        feedback.className =
                            "live-feedback show " +
                            (isCorrect ? "correct" : "wrong");
                    }

                    if (explain) {
                        explain.classList.add("show");
                    }

                    playSound(
                        isCorrect ? "correctSound" : "wrongSound"
                    );

                    markQuestionAnswered(questionIndex, isCorrect);
                }

                function getAnswerMap(card) {
                    try {
                        return JSON.parse(card.dataset.answer);
                    } catch (error) {
                        return {};
                    }
                }

                function lockQuestion(card) {
                    card.dataset.answered = "true";

                    // Keep form fields enabled so their values are included in POST.
                    card.querySelectorAll("select").forEach(function(select) {
                        select.classList.add("control-locked");
                        select.setAttribute("aria-disabled", "true");
                        select.tabIndex = -1;
                    });

                    card.querySelectorAll(".answer-option").forEach(function(option) {
                        option.classList.add("locked");
                    });

                    // Drag buttons can be disabled because answers use hidden inputs.
                    card.querySelectorAll(".drag-item").forEach(function(item) {
                        item.setAttribute("draggable", "false");

                        if (item.tagName === "BUTTON") {
                            item.disabled = true;
                        }
                    });
                }

                function checkMcq(label) {
                    if (submitted) return;

                    const questionIndex = label.dataset.qi;
                    const card = document.getElementById("q-" + questionIndex);

                    if (!card || card.dataset.answered === "true") return;

                    const selectedAnswer = label.dataset.val;
                    const correctAnswer = card.dataset.answer;
                    const isCorrect = selectedAnswer === correctAnswer;

                    const radio = label.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.checked = true;
                    }

                    card.querySelectorAll(".answer-option").forEach(function(option) {
                        option.classList.add("locked");

                        if (option.dataset.val === correctAnswer) {
                            option.classList.add("correct-live");
                        } else if (option === label && !isCorrect) {
                            option.classList.add("wrong-live");
                        } else {
                            option.classList.add("muted");
                        }
                    });

                    lockQuestion(card);
                    showLiveFeedback(questionIndex, isCorrect);
                }

                document.querySelectorAll(".answer-option").forEach(function(label) {
                    label.addEventListener("click", function() {
                        checkMcq(label);
                    });
                });

                function allowDrop(event) {
                    if (submitted) return;
                    event.preventDefault();
                }

                function dragStart(event) {
                    if (submitted) return;

                    event.dataTransfer.setData("item", event.target.dataset.item);
                    event.dataTransfer.setData("text", event.target.textContent.trim());
                    event.dataTransfer.setData("qi", event.target.dataset.qi);
                }

                function putDragItem(questionIndex, itemId, itemText, target, zone) {
                    const input = document.getElementById("input-" + questionIndex + "-" + itemId);
                    if (!input) return;

                    input.value = target;
                    input.dispatchEvent(new Event("change"));

                    zone.querySelectorAll(".drag-item").forEach(function(existing) {
                        existing.remove();
                    });

                    const pill = document.createElement("span");
                    pill.className = "drag-item";
                    pill.textContent = itemText;
                    zone.appendChild(pill);

                    checkDragQuestion(questionIndex);
                }

                function dropItem(event) {
                    if (submitted) return;

                    event.preventDefault();

                    const zone = event.currentTarget;
                    const questionIndex = zone.dataset.qi;
                    const target = zone.dataset.target;
                    const itemId = event.dataTransfer.getData("item");
                    const itemText = event.dataTransfer.getData("text");

                    putDragItem(questionIndex, itemId, itemText, target, zone);
                }

                document.querySelectorAll(".drag-item").forEach(function(item) {
                    item.addEventListener("click", function() {
                        if (submitted) return;

                        selectedDragItem = {
                            qi: item.dataset.qi,
                            item: item.dataset.item,
                            text: item.textContent.trim()
                        };

                        document.querySelectorAll(".drag-item").forEach(function(button) {
                            button.classList.remove("selected-drag");
                        });

                        item.classList.add("selected-drag");
                    });
                });

                document.querySelectorAll(".drop-zone").forEach(function(zone) {
                    zone.addEventListener("click", function() {
                        if (submitted || !selectedDragItem) return;
                        if (selectedDragItem.qi !== zone.dataset.qi) return;

                        putDragItem(
                            selectedDragItem.qi,
                            selectedDragItem.item,
                            selectedDragItem.text,
                            zone.dataset.target,
                            zone
                        );

                        selectedDragItem = null;
                    });
                });

                function checkDragQuestion(questionIndex) {
                    const card = document.getElementById("q-" + questionIndex);
                    if (!card || card.dataset.answered === "true") return;

                    const answerMap = getAnswerMap(card);
                    const inputs = card.querySelectorAll('input[type="hidden"][name^="q"]');

                    let allFilled = true;
                    let isCorrect = true;

                    inputs.forEach(function(input) {
                        const itemId = input.id.replace("input-" + questionIndex + "-", "");

                        if (!input.value) {
                            allFilled = false;
                        }

                        if (answerMap[itemId] !== input.value) {
                            isCorrect = false;
                        }
                    });

                    if (!allFilled) {
                        markQuestionAnswered(questionIndex);
                        return;
                    }

                    card.querySelectorAll(".drop-zone").forEach(function(zone) {
                        zone.classList.remove("correct-live", "wrong-live");
                    });

                    inputs.forEach(function(input) {
                        const itemId = input.id.replace("input-" + questionIndex + "-", "");
                        const zone = card.querySelector('.drop-zone[data-target="' + input.value + '"]');

                        if (zone) {
                            zone.classList.add(answerMap[itemId] === input.value ? "correct-live" : "wrong-live");
                        }
                    });

                    lockQuestion(card);
                    showLiveFeedback(questionIndex, isCorrect);
                }

                document.querySelectorAll("select").forEach(function(select) {
                    select.addEventListener("change", function() {
                        if (submitted) return;

                        const match = select.name.match(/^q(\d+)/);
                        if (!match) return;

                        checkMatchQuestion(match[1]);
                    });
                });

                function checkMatchQuestion(questionIndex) {
                    const card = document.getElementById("q-" + questionIndex);
                    if (!card || card.dataset.answered === "true") return;

                    const answerMap = getAnswerMap(card);
                    const selects = card.querySelectorAll("select");

                    let allFilled = true;
                    let isCorrect = true;

                    selects.forEach(function(select) {
                        const leftMatch = select.name.match(/\[([^\]]+)\]/);
                        const leftId = leftMatch ? leftMatch[1] : "";

                        if (!select.value) {
                            allFilled = false;
                        }

                        if (answerMap[leftId] !== select.value) {
                            isCorrect = false;
                        }
                    });

                    markQuestionAnswered(questionIndex);

                    if (!allFilled) return;

                    selects.forEach(function(select) {
                        const row = select.closest(".match-row");
                        const leftMatch = select.name.match(/\[([^\]]+)\]/);
                        const leftId = leftMatch ? leftMatch[1] : "";

                        if (row) {
                            row.classList.add(answerMap[leftId] === select.value ? "correct-live" : "wrong-live");
                        }
                    });

                    lockQuestion(card);
                    showLiveFeedback(questionIndex, isCorrect);
                }

                const cards = document.querySelectorAll(".q-card");
                const navButtons = document.querySelectorAll(".q-nav-btn");

                cards.forEach(function(card) {
                    const observer = new IntersectionObserver(function(entries) {
                        entries.forEach(function(entry) {
                            if (!entry.isIntersecting) return;

                            const index = entry.target.dataset.question;

                            navButtons.forEach(function(button) {
                                button.classList.remove("nav-active");
                            });

                            const activeButton = document.getElementById("nav-btn-" + index);
                            if (activeButton) {
                                activeButton.classList.add("nav-active");
                            }
                        });
                    }, {
                        threshold: 0.35
                    });

                    observer.observe(card);
                });

                const duration = 35 * 60;
                const timerDisplay = document.getElementById("timerDisplay");
                const timeInput = document.getElementById("timeRemainingInput");
                const quizForm = document.getElementById("quizForm");

                if (!submitted && timerDisplay && quizForm) {
                    let remaining = duration;

                    function formatTime(seconds) {
                        const minutes = String(Math.floor(seconds / 60)).padStart(2, "0");
                        const secs = String(seconds % 60).padStart(2, "0");
                        return minutes + ":" + secs;
                    }

                    const timer = setInterval(function() {
                        timerDisplay.textContent = formatTime(remaining);

                        if (remaining <= 0) {
                            clearInterval(timer);

                            if (timeInput) {
                                timeInput.value = 0;
                            }

                            prepareFormForSubmit();
                            quizForm.submit();
                            return;
                        }

                        remaining--;
                    }, 1000);

                    let isSubmitting = false;

                    quizForm.addEventListener("submit", function(event) {
                        if (isSubmitting) return;

                        event.preventDefault();
                        isSubmitting = true;

                        clearInterval(timer);

                        if (timeInput) {
                            timeInput.value = remaining;
                        }

                        playSound("submitSound");

                        setTimeout(function() {
                            prepareFormForSubmit();
                            quizForm.submit();
                        }, 500);
                    });
                }

                function prepareFormForSubmit() {
                    if (!quizForm) return;

                    // Safety: disabled named fields are not submitted by browsers.
                    quizForm.querySelectorAll("[name]:disabled").forEach(function(control) {
                        control.disabled = false;
                    });
                }
            </script>


</body>

</html>