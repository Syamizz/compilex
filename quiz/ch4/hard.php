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
        'q' => 'In a top-down parsing algorithm, where does construction of the derivation tree begin?',
        'choices' => [
            'A' => 'At the leaves',
            'B' => 'At the starting nonterminal',
            'C' => 'At the final input symbol',
            'D' => 'At the code generator',
        ],
        'answer' => 'B',
        'explain' => 'Chapter 4 states that a top-down algorithm begins with the starting nonterminal and applies rules in a downward direction through the derivation tree.',
    ],
    [
        'type' => 'mcq',
        'q' => 'Which condition must every rule of a simple grammar satisfy?',
        'choices' => [
            'A' => 'Every rule must begin with a nonterminal',
            'B' => 'Every rule must contain an epsilon symbol',
            'C' => 'Every rule must have the form A -> a alpha',
            'D' => 'Every rule must contain exactly one symbol',
        ],
        'answer' => 'C',
        'explain' => 'A simple grammar requires each rule to have the form A -> a alpha, where A is a nonterminal, a is a terminal, and alpha is a string of symbols.',
    ],
    [
        'type' => 'mcq',
        'q' => 'Why must rules defining the same nonterminal have disjoint selection sets?',
        'choices' => [
            'A' => 'So the parser can uniquely decide which rule to apply',
            'B' => 'So every rule can contain epsilon',
            'C' => 'So the parser can process several inputs simultaneously',
            'D' => 'So all nonterminals become terminals',
        ],
        'answer' => 'A',
        'explain' => 'Disjoint selection sets allow the current input symbol to uniquely determine which grammar rule should be applied.',
    ],
    [
        'type' => 'mcq',
        'q' => 'When applying a grammar rule with a pushdown parser, why are the right-side symbols placed onto the stack in reverse order?',
        'choices' => [
            'A' => 'The last grammar symbol must be processed first',
            'B' => 'The first grammar symbol must end up on top of the stack',
            'C' => 'The stack can contain only terminals',
            'D' => 'The input must be read from right to left',
        ],
        'answer' => 'B',
        'explain' => 'Because a stack is last-in first-out, reversing the right side places its first symbol on top so it is processed first.',
    ],
    [
        'type' => 'mcq',
        'q' => 'For an epsilon rule N -> epsilon in a quasi-simple grammar, what is its selection set?',
        'choices' => [
            'A' => 'First(N)',
            'B' => 'Every terminal in the grammar',
            'C' => 'The follow set of N',
            'D' => 'Only the endmarker',
        ],
        'answer' => 'C',
        'explain' => 'Chapter 4 defines the selection set of an epsilon rule as the follow set of the nonterminal on its left side.',
    ],
    [
        'type' => 'mcq',
        'q' => 'What does LL(1) mean?',
        'choices' => [
            'A' => 'The parser scans left to right, produces a left-most derivation, and uses one lookahead symbol',
            'B' => 'The parser scans right to left and uses one stack symbol',
            'C' => 'The parser produces a right-most derivation using one rule',
            'D' => 'The grammar has one terminal and one nonterminal',
        ],
        'answer' => 'A',
        'explain' => 'The two L characters refer to scanning left to right and finding a left-most derivation. The 1 means at most one lookahead symbol.',
    ],
    [
        'type' => 'mcq',
        'q' => 'Why is the rule Expr -> Expr + Term unsuitable for recursive-descent parsing?',
        'choices' => [
            'A' => 'It contains an addition operator',
            'B' => 'It causes immediate left recursion and infinite recursive calls',
            'C' => 'It has too many terminal symbols',
            'D' => 'It cannot generate arithmetic expressions',
        ],
        'answer' => 'B',
        'explain' => 'A recursive-descent method for Expr would call itself before consuming input, causing infinite recursion.',
    ],
    [
        'type' => 'mcq',
        'q' => 'How are action symbols represented in a translation grammar?',
        'choices' => [
            'A' => 'Inside square brackets',
            'B' => 'Inside parentheses',
            'C' => 'Inside curly braces',
            'D' => 'As lowercase nonterminals',
        ],
        'answer' => 'C',
        'explain' => 'Chapter 4 encloses action symbols in curly braces, such as {ADD}, {MULT}, or {var}.',
    ],
    [
        'type' => 'mcq',
        'q' => 'Which statement correctly distinguishes synthesized and inherited attributes?',
        'choices' => [
            'A' => 'Synthesized attributes come from lower nodes; inherited attributes come from higher nodes',
            'B' => 'Both types must come from terminal symbols',
            'C' => 'Synthesized attributes come only from the scanner',
            'D' => 'Inherited attributes always contain labels',
        ],
        'answer' => 'A',
        'explain' => 'Synthesized values move upward from lower nodes, while inherited values are passed from higher nodes or across the tree.',
    ],
    [
        'type' => 'mcq',
        'q' => 'Why does the Decaf boolean-expression translation use comparison code 7 - c in its TST atom?',
        'choices' => [
            'A' => 'To allocate seven temporary variables',
            'B' => 'To convert the comparison into an assignment',
            'C' => 'To use the logical complement and branch when the condition is false',
            'D' => 'To make every comparison true',
        ],
        'answer' => 'C',
        'explain' => 'The TST used by control structures must branch when the source condition is false. Subtracting the comparison code from 7 produces its logical complement.',
    ],

    // 5 DRAG AND DROP QUESTIONS
    [
        'type' => 'drag',
        'q' => 'Drag each relation term to its correct definition.',
        'items' => [
            'item1' => 'Relation',
            'item2' => 'Transitive',
            'item3' => 'Reflexive',
            'item4' => 'R*',
        ],
        'targets' => [
            'pairs' => 'A set of ordered pairs',
            'chain' => 'If (a,b) and (b,c) occur, then (a,c) occurs',
            'self' => 'Contains a pair such as (a,a)',
            'closure' => 'The reflexive transitive closure of R',
        ],
        'answer' => [
            'item1' => 'pairs',
            'item2' => 'chain',
            'item3' => 'self',
            'item4' => 'closure',
        ],
        'explain' => 'Section 4.0 defines relations and the reflexive transitive closure used later when calculating selection sets.',
    ],
    [
        'type' => 'drag',
        'q' => 'Drag each simple-grammar pushdown action to the correct situation.',
        'items' => [
            'item1' => 'REP',
            'item2' => 'Pop and advance',
            'item3' => 'Accept',
            'item4' => 'Reject',
        ],
        'targets' => [
            'grammar_rule' => 'A grammar rule is selected for a nonterminal',
            'terminal_match' => 'The stack terminal matches the input terminal',
            'finished' => 'The bottom marker and endmarker are reached together',
            'invalid' => 'No valid table action exists',
        ],
        'answer' => [
            'item1' => 'grammar_rule',
            'item2' => 'terminal_match',
            'item3' => 'finished',
            'item4' => 'invalid',
        ],
        'explain' => 'The one-state pushdown construction uses REP for grammar rules, pop and advance for matching terminals, and accepts only when input and stack finish together.',
    ],
    [
        'type' => 'drag',
        'q' => 'Drag each selection-set concept to its correct meaning.',
        'items' => [
            'item1' => 'Nullable nonterminal',
            'item2' => 'First(A)',
            'item3' => 'Fol(A)',
            'item4' => 'Selection set',
        ],
        'targets' => [
            'epsilon' => 'A nonterminal from which epsilon can be derived',
            'begin' => 'Terminals that can begin a string derived from A',
            'after' => 'Terminals or endmarker that can immediately follow A',
            'choose' => 'Input symbols that direct the parser to use a rule',
        ],
        'answer' => [
            'item1' => 'epsilon',
            'item2' => 'begin',
            'item3' => 'after',
            'item4' => 'choose',
        ],
        'explain' => 'These concepts are used by the Chapter 4 algorithm for finding the selection set of each grammar rule.',
    ],
    [
        'type' => 'drag',
        'q' => 'Drag each expression-grammar nonterminal to its role in grammar G16.',
        'items' => [
            'item1' => 'Expr',
            'item2' => 'Elist',
            'item3' => 'Term',
            'item4' => 'Tlist',
        ],
        'targets' => [
            'expression' => 'Begins with a Term followed by an addition list',
            'addition_tail' => 'Represents repeated + Term portions',
            'term' => 'Begins with a Factor followed by a multiplication list',
            'multiplication_tail' => 'Represents repeated * Factor portions',
        ],
        'answer' => [
            'item1' => 'expression',
            'item2' => 'addition_tail',
            'item3' => 'term',
            'item4' => 'multiplication_tail',
        ],
        'explain' => 'Grammar G16 removes left recursion by introducing Elist and Tlist while preserving normal operator precedence.',
    ],
    [
        'type' => 'drag',
        'q' => 'Drag each control-flow atom to its correct purpose.',
        'items' => [
            'item1' => 'LBL',
            'item2' => 'JMP',
            'item3' => 'TST',
            'item4' => 'MOV',
        ],
        'targets' => [
            'mark' => 'Marks a branch destination',
            'unconditional' => 'Branches unconditionally to a label',
            'conditional' => 'Compares expressions and branches when a condition is true',
            'assignment' => 'Assigns a source value to a target',
        ],
        'answer' => [
            'item1' => 'mark',
            'item2' => 'unconditional',
            'item3' => 'conditional',
            'item4' => 'assignment',
        ],
        'explain' => 'Sections 4.8 and 4.9 introduce LBL, JMP, TST, and MOV atoms for Decaf expressions and control structures.',
    ],

    // 5 MIX AND MATCH QUESTIONS
    [
        'type' => 'match',
        'q' => 'Match each grammar class with its Chapter 4 property.',
        'left' => [
            'a' => 'Simple grammar',
            'b' => 'Quasi-simple grammar',
            'c' => 'LL(1) grammar',
            'd' => 'Translation grammar',
        ],
        'right' => [
            'r1' => 'Rules have form A -> a alpha and alternatives begin differently',
            'r2' => 'A simple-style grammar that may also contain epsilon rules',
            'r3' => 'Alternatives have disjoint selection sets with one-symbol lookahead',
            'r4' => 'Contains action symbols that can produce output',
        ],
        'answer' => [
            'a' => 'r1',
            'b' => 'r2',
            'c' => 'r3',
            'd' => 'r4',
        ],
        'explain' => 'Chapter 4 gradually extends simple grammars to quasi-simple, LL(1), and translation grammars.',
    ],
    [
        'type' => 'match',
        'q' => 'Match each recursive-descent operation with its behavior.',
        'left' => [
            'a' => 'Nonterminal on the right side',
            'b' => 'Terminal on the right side',
            'c' => 'Epsilon rule',
            'd' => 'Action symbol',
        ],
        'right' => [
            'r1' => 'Call the corresponding method',
            'r2' => 'Confirm it matches and read the next token',
            'r3' => 'Return without consuming input',
            'r4' => 'Produce output or call another method',
        ],
        'answer' => [
            'a' => 'r1',
            'b' => 'r2',
            'c' => 'r3',
            'd' => 'r4',
        ],
        'explain' => 'These are the basic actions used when implementing ordinary and translation grammars with recursive descent.',
    ],
    [
        'type' => 'match',
        'q' => 'Match each grammar with its purpose or property.',
        'left' => [
            'a' => 'G5',
            'b' => 'G16',
            'c' => 'G17',
            'd' => 'G21',
        ],
        'right' => [
            'r1' => 'Left-recursive arithmetic-expression grammar',
            'r2' => 'LL(1) arithmetic grammar after eliminating left recursion',
            'r3' => 'Translation grammar that converts infix expressions to postfix',
            'r4' => 'Attributed translation grammar that produces expression atoms',
        ],
        'answer' => [
            'a' => 'r1',
            'b' => 'r2',
            'c' => 'r3',
            'd' => 'r4',
        ],
        'explain' => 'The chapter transforms the expression grammar from G5 into G16, then extends it into translation grammar G17 and attributed translation grammar G21.',
    ],
    [
        'type' => 'match',
        'q' => 'Match each comparison operator with its atom comparison code.',
        'left' => [
            'a' => '==',
            'b' => '<',
            'c' => '<=',
            'd' => '!=',
        ],
        'right' => [
            'r1' => 'Code 1',
            'r2' => 'Code 2',
            'r3' => 'Code 4',
            'r4' => 'Code 6',
        ],
        'answer' => [
            'a' => 'r1',
            'b' => 'r2',
            'c' => 'r3',
            'd' => 'r4',
        ],
        'explain' => 'Chapter 4 assigns codes 1 through 6 to ==, <, >, <=, >=, and != respectively.',
    ],
    [
        'type' => 'match',
        'q' => 'Match each attributed-grammar concept with its description.',
        'left' => [
            'a' => 'Synthesized attribute',
            'b' => 'Inherited attribute',
            'c' => 'Alloc()',
            'd' => 'Atom()',
        ],
        'right' => [
            'r1' => 'Returns information from lower nodes to a calling method',
            'r2' => 'Passes information from higher nodes or earlier symbols',
            'r3' => 'Provides storage for a temporary expression result',
            'r4' => 'Outputs an atom record',
        ],
        'answer' => [
            'a' => 'r1',
            'b' => 'r2',
            'c' => 'r3',
            'd' => 'r4',
        ],
        'explain' => 'Attributed recursive-descent translators use parameters for attributes, Alloc() for temporary storage, and Atom() to emit output.',
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
    $quiz_id = 'Q012'; // Chapter 4 Hard

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
    <title>Chapter 4 Hard Quiz</title>
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

            <h1>Chapter 4 Hard Quiz</h1>

            <p>
                20 mixed questions based only on Chapter 4:
                top-down parsing, relations and closure, simple and
                quasi-simple grammars, LL(1), selection sets,
                recursive descent, translation grammars, attributed
                grammars, Decaf expressions, and control structures.
            </p>
        </div>

        <?php if (!$submitted): ?>
            <div class="timer" id="timerDisplay">35:00</div>
        <?php endif; ?>

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