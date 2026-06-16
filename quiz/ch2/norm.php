<?php
session_start();
include '../../dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$questions = [
    // ── 2.0 Formal Languages ──────────────────────────────────────
    [
        'q'      => 'Which of the following correctly defines a formal language?',
        'choices' => [
            'A' => 'Any language spoken naturally by humans, like English or Italian',
            'B' => 'A set of strings from a given alphabet that can be specified precisely',
            'C' => 'A programming language that runs on a computer without compilation',
            'D' => 'A list of grammar rules written in English prose',
        ],
        'answer'  => 'B',
        'explain' => 'A formal language is a set of strings from a given alphabet. It can be specified precisely and is amenable for use with computers, unlike natural languages.',
        'color'   => 'indigo',
        'image'   => null,
    ],
    [
        'q'      => 'What is the difference between the empty set {} and the null string ε?',
        'choices' => [
            'A' => 'They are identical — both represent "nothing"',
            'B' => 'The empty set has one element (ε); the null string has zero characters',
            'C' => 'The empty set contains no strings at all; the null string ε is a string of zero characters',
            'D' => 'The null string ε contains spaces; the empty set contains only newlines',
        ],
        'answer'  => 'C',
        'explain' => 'The empty set {} contains NO strings. The null string ε is a valid string — it just has zero characters. {ε} is a language containing exactly one string (the null string), which is NOT the same as {}.',
        'color'   => 'teal',
        'image'   => null,
    ],
    [
        'q'      => 'Consider the finite state machine diagram below. What language does it accept?',
        'choices' => [
            'A' => 'Any string of 0s and 1s that begins with 0 and ends with 1',
            'B' => 'Any string of 0s and 1s that begins with 1 and ends with 0',
            'C' => 'Any string of 0s and 1s that contains an even number of 1s',
            'D' => 'Any string of 0s and 1s that contains at least one 0',
        ],
        'answer'  => 'B',
        'explain' => 'The FSM (Figure 2.1 from notes) has starting state A and only accepting state C. State B is reached by reading a 1, and state C (accepting) is reached from B by reading a 0. Only strings beginning with 1 and ending with 0 end in state C.',
        'color'   => 'purple',
        'image'   => 'fsm_fig21',
    ],
    [
        'q'      => 'In a deterministic finite state machine (FSM), which statement is TRUE?',
        'choices' => [
            'A' => 'Multiple arcs can leave a state with the same input symbol',
            'B' => 'The starting state cannot also be an accepting state',
            'C' => 'There is exactly one arc leaving each state for each possible input symbol',
            'D' => 'The machine can backtrack to a previous state if the input is wrong',
        ],
        'answer'  => 'C',
        'explain' => 'A deterministic FSM has exactly one arc leaving each state labeled by each possible input symbol — no ambiguity, no backtracking. This ensures there is always exactly one entry in every cell of the transition table.',
        'color'   => 'rose',
        'image'   => null,
    ],
    [
        'q'      => 'Which of the following correctly evaluates the regular expression concatenation: {ab, a} · {b, ε} ?',
        'choices' => [
            'A' => '{ab, a, b, ε}',
            'B' => '{abb, ab, a, b}',
            'C' => '{ab, a, abb, b}',
            'D' => '{abb, ab, ab, a, b, ε}',
        ],
        'answer'  => 'C',
        'explain' => 'Concatenate each string in the first set with each in the second: ab·b=abb, ab·ε=ab, a·b=b, a·ε=a. Result = {abb, ab, b, a}. Note ab appears once only (sets have no duplicates). Options C lists this correctly.',
        'color'   => 'amber',
        'image'   => null,
    ],
    [
        'q'      => 'In regular expressions, what is the correct precedence order (highest to lowest)?',
        'choices' => [
            'A' => 'Union (+) → Concatenation → Kleene *',
            'B' => 'Concatenation → Kleene * → Union (+)',
            'C' => 'Kleene * → Concatenation → Union (+)',
            'D' => 'Kleene * → Union (+) → Concatenation',
        ],
        'answer'  => 'C',
        'explain' => 'Kleene * has highest precedence, then concatenation, then union (+) has lowest. So L1 + L2·L3 = L1 + (L2·L3), and L1·L2* = L1·(L2*).',
        'color'   => 'green',
        'image'   => null,
    ],
    [
        'q'      => 'Which strings are in the language of the regular expression 1·(0+1)*·0?',
        'choices' => [
            'A' => 'All strings of 0s and 1s (including the null string)',
            'B' => 'All strings beginning with 1 and ending with 0',
            'C' => 'All strings with exactly one 1 followed by one 0',
            'D' => 'All strings beginning with 0 and ending with 1',
        ],
        'answer'  => 'B',
        'explain' => 'The expression 1·(0+1)*·0 generates strings that start with 1, followed by zero or more 0s or 1s in any combination, and end with 0. Examples: 10, 100, 110, 1010.',
        'color'   => 'cyan',
        'image'   => null,
    ],

    // ── 2.1 Lexical Tokens ────────────────────────────────────────
    [
        'q'      => 'Look at the Java input below. How many tokens does the lexical phase output?
        
        while ( x33 <= 2.5e+33 - total ) calc ( x33 ) ; //!',
        'choices' => [
            'A' => '15 tokens including the comment',
            'B' => '14 tokens — the comment is kept',
            'C' => '13 tokens — the comment and semicolon are discarded',
            'D' => '13 tokens — only the comment is discarded',
        ],
        'answer'  => 'D',
        'explain' => 'The 13 tokens are: while, (, x33, <=, 2.5e+33, -, total, ), calc, (, x33, ), ; — the comment //! is detected but NOT output. Semicolons are special-character tokens and ARE included.',
        'color'   => 'indigo',
        'image'   => null,
    ],
    [
        'q'      => 'Which of the following statements about the symbol table is CORRECT?',
        'choices' => [
            'A' => 'The symbol table stores every occurrence of an identifier, including duplicates',
            'B' => 'The symbol table stores each identifier once regardless of how many times it appears in the source',
            'C' => 'The symbol table only stores numeric constants, not identifiers',
            'D' => 'The symbol table is built during the code generation phase only',
        ],
        'answer'  => 'B',
        'explain' => 'The symbol table stores each identifier ONCE, regardless of how many times it occurs. It also stores information about the identifier such as its kind and associated run-time details. It is commonly organized as a binary search tree or hash table.',
        'color'   => 'teal',
        'image'   => null,
    ],
    [
        'q'      => 'The lexical analysis phase receives the input: } while if ( {  — what does it do?',
        'choices' => [
            'A' => 'It immediately throws a syntax error and stops',
            'B' => 'It outputs 5 tokens corresponding to the 5 words, without checking syntax',
            'C' => 'It skips unrecognised tokens and outputs only "while" and "if"',
            'D' => 'It outputs 3 tokens and discards the brackets as whitespace',
        ],
        'answer'  => 'B',
        'explain' => 'The lexical analyser does NOT check for proper syntax. It outputs 5 tokens for the 5 words (}, while, if, (, {). Syntax errors are detected in the syntax analysis (parser) phase.',
        'color'   => 'purple',
        'image'   => null,
    ],

    // ── 2.2 FSMs for Lexical Analysis ─────────────────────────────
    [
        'q'      => 'Look at the FSM below for recognising identifiers. What rule does it enforce?',
        'choices' => [
            'A' => 'Identifiers must begin with a digit and may be followed by letters or digits',
            'B' => 'Identifiers must begin with a letter and may be followed by any number of letters or digits',
            'C' => 'Identifiers can start with any character and may only contain letters',
            'D' => 'Identifiers must be exactly one letter long',
        ],
        'answer'  => 'B',
        'explain' => 'The FSM in Figure 2.4 accepts identifiers that begin with a letter (L), then are followed by zero or more letters (L) or digits (D). This matches standard identifier rules in languages like Java and C.',
        'color'   => 'rose',
        'image'   => 'fsm_identifier',
    ],
    [
        'q'      => 'In the FSM implementation with a 2D array, what do the ROWS and COLUMNS of the array represent?',
        'choices' => [
            'A' => 'Rows = input symbols; Columns = states',
            'B' => 'Rows = states; Columns = input symbols',
            'C' => 'Rows = tokens; Columns = lexemes',
            'D' => 'Rows = actions; Columns = transitions',
        ],
        'answer'  => 'B',
        'explain' => 'The FSM is implemented as a 2D array fsm[STATES][INPUTS] where rows represent states and columns represent input symbols. Each cell contains the next state for that state/input combination.',
        'color'   => 'amber',
        'image'   => null,
    ],
    [
        'q'      => 'What is the purpose of ACTIONS associated with FSM state transitions in lexical analysis?',
        'choices' => [
            'A' => 'To reject invalid tokens immediately without reading further input',
            'B' => 'To allow the FSM to handle more than 2 input symbols at a time',
            'C' => 'To build symbol tables, convert numeric constants, and put out tokens as transitions occur',
            'D' => 'To allow the FSM to backtrack to a previous state when needed',
        ],
        'answer'  => 'C',
        'explain' => 'Actions are functions associated with state transitions. They enable the FSM to do more than just accept/reject — they can build a symbol table, convert numeric constants to internal format, and output tokens.',
        'color'   => 'green',
        'image'   => null,
    ],
    [
        'q'      => 'In Sample Problem 2.2, the result of reading a numeric string like 46.73e-21 is computed as:',
        'choices' => [
            'A' => 'Result = N + Places × Sign × Exp',
            'B' => 'Result = N × 10^(Sign×Exp − Places)',
            'C' => 'Result = N × 10^(Places − Sign×Exp)',
            'D' => 'Result = N / (10^Exp × Places)',
        ],
        'answer'  => 'B',
        'explain' => 'The formula from the notes is: Result = N × Math.pow(10, Sign×Exp − Places), where N is the integer part of the mantissa, Places counts decimal digits, Sign is the exponent sign, and Exp is the exponent value.',
        'color'   => 'cyan',
        'image'   => null,
    ],
    [
        'q'      => 'The parity bit generator FSM (Figure 2.7) complements a variable called "parity" every time it reads a 1. What is the parity value after reading the string 1 0 1 1?',
        'choices' => [
            'A' => 'parity = 0 (even number of 1s read)',
            'B' => 'parity = 1 (odd number of 1s read)',
            'C' => 'parity = 3 (count of 1s read)',
            'D' => 'parity = 0 (0s are also counted)',
        ],
        'answer'  => 'B',
        'explain' => 'Parity starts at 0 and is complemented on each 1. Reading 1→parity=1, 0→no change, 1→parity=0, 1→parity=1. Three 1s were read (odd), so parity ends as 1. This indicates an odd number of 1s, so a parity bit of 1 would be added to make the total even.',
        'color'   => 'indigo',
        'image'   => 'fsm_parity',
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

    $basePoints  = $score * 100;
    $bonusPoints = 0;
    $bonusReason = '';

    if ($score === $total && $timeRemaining > 5 * 60) {
        $bonusPoints = 200;
        $bonusReason = '⚡ Perfect score with over 5 minutes to spare!';
    }

    $totalPoints = $basePoints + $bonusPoints;

    // Generate board ID like BD1, BD2, BD3
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

    $board_id = 'BD' . $next_number;

    $board_chap = 2;
    $quiz_type = "Normal";
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

// Inline SVG images for questions
function getImage(string $key): string
{
    $imgs = [

        'fsm_fig21' => '<svg viewBox="0 0 460 160" xmlns="http://www.w3.org/2000/svg" style="max-width:460px;width:100%;display:block;margin:12px 0;">
  <!-- Arrow into A -->
  <line x1="30" y1="80" x2="68" y2="80" stroke="#6366F1" stroke-width="2" marker-end="url(#arr)"/>
  <!-- State A -->
  <circle cx="90" cy="80" r="22" fill="#EEF2FF" stroke="#6366F1" stroke-width="2"/>
  <text x="90" y="85" text-anchor="middle" font-size="14" font-weight="700" fill="#4338CA">A</text>
  <!-- A→B on 1 -->
  <line x1="112" y1="80" x2="188" y2="80" stroke="#374151" stroke-width="1.5" marker-end="url(#arr)"/>
  <text x="150" y="70" text-anchor="middle" font-size="12" fill="#374151">1</text>
  <!-- State B -->
  <circle cx="210" cy="80" r="22" fill="#F0FDF4" stroke="#14B8A6" stroke-width="2"/>
  <text x="210" y="85" text-anchor="middle" font-size="14" font-weight="700" fill="#0F766E">B</text>
  <!-- B→C on 0 -->
  <line x1="232" y1="80" x2="308" y2="80" stroke="#374151" stroke-width="1.5" marker-end="url(#arr)"/>
  <text x="270" y="70" text-anchor="middle" font-size="12" fill="#374151">0</text>
  <!-- State C (accepting = double circle) -->
  <circle cx="330" cy="80" r="22" fill="#FFF1F2" stroke="#F43F5E" stroke-width="2"/>
  <circle cx="330" cy="80" r="17" fill="none" stroke="#F43F5E" stroke-width="1.5"/>
  <text x="330" y="85" text-anchor="middle" font-size="14" font-weight="700" fill="#BE123C">C</text>
  <!-- B self-loop on 1 -->
  <path d="M200,58 Q210,30 220,58" fill="none" stroke="#374151" stroke-width="1.5" marker-end="url(#arr)"/>
  <text x="210" y="27" text-anchor="middle" font-size="12" fill="#374151">1</text>
  <!-- C→B on 1 -->
  <path d="M330,58 Q270,20 210,58" fill="none" stroke="#374151" stroke-width="1.5" marker-end="url(#arr)"/>
  <text x="270" y="22" text-anchor="middle" font-size="12" fill="#374151">1</text>
  <!-- C self-loop on 0: arc above -->
  <path d="M318,60 Q330,30 342,60" fill="none" stroke="#374151" stroke-width="1.5" marker-end="url(#arr)"/>
  <text x="350" y="38" text-anchor="middle" font-size="12" fill="#374151">0</text>
  <!-- A self-loop on 0 -->
  <path d="M78,60 Q90,28 102,60" fill="none" stroke="#374151" stroke-width="1.5" marker-end="url(#arr)"/>
  <text x="90" y="27" text-anchor="middle" font-size="12" fill="#374151">0</text>
  <!-- State D dead -->
  <circle cx="210" cy="140" r="18" fill="#F3F4F6" stroke="#9CA3AF" stroke-width="1.5" stroke-dasharray="4,3"/>
  <text x="210" y="145" text-anchor="middle" font-size="13" fill="#9CA3AF">D</text>
  <text x="210" y="162" text-anchor="middle" font-size="10" fill="#9CA3AF">dead</text>
  <defs>
    <marker id="arr" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
      <path d="M0,0 L0,6 L8,3 z" fill="#374151"/>
    </marker>
  </defs>
  <!-- legend -->
  <text x="10" y="150" font-size="11" fill="#6B7280">Start: A   Accept: C (double circle)</text>
</svg>',

        'fsm_identifier' => '<svg viewBox="0 0 380 130" xmlns="http://www.w3.org/2000/svg" style="max-width:380px;width:100%;display:block;margin:12px 0;">
  <defs>
    <marker id="arr2" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
      <path d="M0,0 L0,6 L8,3 z" fill="#374151"/>
    </marker>
  </defs>
  <!-- Arrow into S0 -->
  <line x1="18" y1="65" x2="55" y2="65" stroke="#6366F1" stroke-width="2" marker-end="url(#arr2)"/>
  <!-- S0 -->
  <circle cx="78" cy="65" r="22" fill="#EEF2FF" stroke="#6366F1" stroke-width="2"/>
  <text x="78" y="70" text-anchor="middle" font-size="13" font-weight="700" fill="#4338CA">S0</text>
  <!-- S0→S1 on L -->
  <line x1="100" y1="65" x2="178" y2="65" stroke="#374151" stroke-width="1.5" marker-end="url(#arr2)"/>
  <text x="140" y="55" text-anchor="middle" font-size="12" fill="#374151">L</text>
  <!-- S1 accepting -->
  <circle cx="200" cy="65" r="22" fill="#F0FDF4" stroke="#22C55E" stroke-width="2"/>
  <circle cx="200" cy="65" r="17" fill="none" stroke="#22C55E" stroke-width="1.5"/>
  <text x="200" y="70" text-anchor="middle" font-size="13" font-weight="700" fill="#15803D">S1</text>
  <!-- S1 self-loop L,D -->
  <path d="M188,43 Q200,14 212,43" fill="none" stroke="#374151" stroke-width="1.5" marker-end="url(#arr2)"/>
  <text x="200" y="12" text-anchor="middle" font-size="11" fill="#374151">L, D</text>
  <!-- S0 dead on D -->
  <circle cx="78" cy="118" r="16" fill="#F3F4F6" stroke="#9CA3AF" stroke-width="1.5" stroke-dasharray="4,3"/>
  <text x="78" y="123" text-anchor="middle" font-size="11" fill="#9CA3AF">dead</text>
  <line x1="78" y1="87" x2="78" y2="100" stroke="#9CA3AF" stroke-width="1.5" marker-end="url(#arr2)" stroke-dasharray="4,3"/>
  <text x="100" y="97" font-size="11" fill="#9CA3AF">D → dead</text>
  <!-- legend -->
  <text x="240" y="55" font-size="11" fill="#6B7280">L = any letter</text>
  <text x="240" y="72" font-size="11" fill="#6B7280">D = any digit</text>
  <text x="240" y="89" font-size="11" fill="#6B7280">Start: S0</text>
  <text x="240" y="106" font-size="11" fill="#6B7280">Accept: S1</text>
</svg>',

        'fsm_parity' => '<svg viewBox="0 0 340 140" xmlns="http://www.w3.org/2000/svg" style="max-width:340px;width:100%;display:block;margin:12px 0;">
  <defs>
    <marker id="arr3" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
      <path d="M0,0 L0,6 L8,3 z" fill="#374151"/>
    </marker>
  </defs>
  <!-- arrow into S0 -->
  <line x1="18" y1="70" x2="55" y2="70" stroke="#6366F1" stroke-width="2" marker-end="url(#arr3)"/>
  <!-- S0 accepting (even parity = 0 ones) -->
  <circle cx="80" cy="70" r="24" fill="#EEF2FF" stroke="#6366F1" stroke-width="2"/>
  <circle cx="80" cy="70" r="19" fill="none" stroke="#6366F1" stroke-width="1.5"/>
  <text x="80" y="65" text-anchor="middle" font-size="12" font-weight="700" fill="#4338CA">S0</text>
  <text x="80" y="79" text-anchor="middle" font-size="9" fill="#4338CA">parity=0</text>
  <!-- S0→S1 on 1/P() -->
  <path d="M104,70 L196,70" stroke="#374151" stroke-width="1.5" marker-end="url(#arr3)"/>
  <text x="150" y="60" text-anchor="middle" font-size="11" fill="#6366F1" font-weight="600">1 / P()</text>
  <!-- S1 (odd parity) -->
  <circle cx="220" cy="70" r="24" fill="#FFF1F2" stroke="#F43F5E" stroke-width="2"/>
  <text x="220" y="65" text-anchor="middle" font-size="12" font-weight="700" fill="#BE123C">S1</text>
  <text x="220" y="79" text-anchor="middle" font-size="9" fill="#BE123C">parity=1</text>
  <!-- S1→S0 on 1/P() -->
  <path d="M196,82 Q150,110 104,82" fill="none" stroke="#374151" stroke-width="1.5" marker-end="url(#arr3)"/>
  <text x="150" y="112" text-anchor="middle" font-size="11" fill="#6366F1" font-weight="600">1 / P()</text>
  <!-- S0 self on 0 -->
  <path d="M68,47 Q80,18 92,47" fill="none" stroke="#374151" stroke-width="1.5" marker-end="url(#arr3)"/>
  <text x="80" y="15" text-anchor="middle" font-size="11" fill="#374151">0</text>
  <!-- S1 self on 0 -->
  <path d="M208,47 Q220,18 232,47" fill="none" stroke="#374151" stroke-width="1.5" marker-end="url(#arr3)"/>
  <text x="220" y="15" text-anchor="middle" font-size="11" fill="#374151">0</text>
  <!-- legend -->
  <text x="10" y="132" font-size="10" fill="#6B7280">P() complements parity variable (0↔1) on each 1 read</text>
</svg>',

    ];
    return $imgs[$key] ?? '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter 2 – Normal Quiz</title>
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

    <div class="page-header">
        <div class="difficulty-chip">⚡ Medium Level</div>
        <h1>Chapter 2 — <span class="accent">Multiple Choice</span> Quiz</h1>
        <p>15 questions covering Sections 2.0 (Formal Languages &amp; FSMs), 2.1 (Lexical Tokens), and 2.2 (FSM Implementation). Some questions include diagrams.</p>
    </div>

    <div class="page-layout">

        <!-- ── Left Nav ── -->
        <nav class="q-nav" id="qNav">
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
            <div class="nav-count" id="nav-count">0 / <?= $total ?></div>

            <!-- submit inside nav -->
            <?php if (!$submitted): ?>
                <div class="nav-submit-wrap">
                    <button type="submit" form="quizForm" class="nav-submit-btn">Submit →</button>
                </div>
            <?php endif; ?>
        </nav>

        <!-- ── Questions ── -->
        <div class="quiz-wrap">
            <?php if (!$submitted): ?>
                <div class="timer-wrap">
                    <div class="timer-box" id="timerBox">
                        <span class="timer-icon">⏱</span>
                        <span id="timerDisplay">25:00</span>
                    </div>
                </div>
            <?php endif; ?>

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
                    <div style="margin:14px 0 10px;padding:16px;background:#EEF2FF;border-radius:12px;border:1.5px solid rgba(99,102,241,.2);">
                        <div style="font-size:13px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:.8px;margin-bottom:6px;">Points Earned</div>
                        <div style="display:flex;justify-content:center;align-items:baseline;gap:8px;flex-wrap:wrap;">
                            <span style="font-size:36px;font-weight:800;color:#6366F1;"><?= $totalPoints ?></span>
                            <span style="font-size:14px;color:#6B7280;">pts</span>
                            <?php if ($bonusPoints > 0): ?>
                                <span style="font-size:13px;background:#D1FAE5;color:#065F46;font-weight:700;padding:3px 10px;border-radius:20px;border:1px solid #6EE7B7;">
                                    +<?= $bonusPoints ?> bonus
                                </span>
                            <?php endif; ?>
                        </div>
                        <div style="display:flex;justify-content:center;gap:20px;margin-top:10px;font-size:13px;color:#6B7280;">
                            <span>Base: <strong style="color:#374151;"><?= $basePoints ?> pts</strong></span>
                            <?php if ($bonusPoints > 0): ?>
                                <span>Bonus: <strong style="color:#059669;">+<?= $bonusPoints ?> pts</strong></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($bonusReason): ?>
                            <div style="margin-top:10px;font-size:13px;font-weight:600;color:#4338CA;"><?= $bonusReason ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="score-bar">
                        <div class="score-bar-fill" style="width:<?= $pct ?>%"></div>
                    </div>
                    <div class="score-msg">
                        <?php
                        if ($pct === 100 && $bonusPoints > 0) echo '🏆 Perfect score with bonus! Incredible!';
                        elseif ($pct === 100)  echo '🏆 Perfect! Lexical analysis master!';
                        elseif ($pct >= 80)    echo '🎉 Excellent! Great grasp of Chapter 2.';
                        elseif ($pct >= 60)    echo '👍 Good effort — review the FSM diagrams again.';
                        elseif ($pct >= 40)    echo '📖 Keep going! Re-read sections 2.0–2.2.';
                        else                   echo '💪 Don\'t give up — study the diagrams and try again!';
                        ?>
                    </div>
                    <a class="btn-retry" href="<?= $_SERVER['PHP_SELF'] ?>">↺ Retry Quiz</a>
                </div>
            <?php endif; ?>

            <form method="POST" id="quizForm">
                <input type="hidden" name="time_remaining" id="timeRemainingInput" value="0">
                <?php foreach ($questions as $i => $q):
                    $r = $results[$i] ?? null;
                    $extra = '';
                    if ($submitted && $r) {
                        if (!$r['answered'])    $extra = ' result-skipped';
                        elseif ($r['correct'])  $extra = ' result-correct';
                        else                   $extra = ' result-wrong';
                    }
                ?>
                    <div id="q-<?= $i ?>" class="q-card color-<?= $q['color'] ?><?= $extra ?>">

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
                        <div class="q-text"><?= htmlspecialchars($q['q']) ?></div>

                        <?php if (!empty($q['image'])): ?>
                            <div class="q-image">
                                <div class="q-image-label">📊 Diagram</div>
                                <?= getImage($q['image']) ?>
                            </div>
                        <?php endif; ?>

                        <div class="choices">
                            <?php foreach ($q['choices'] as $letter => $text):
                                $bc = 'choice-btn';
                                if ($submitted && $r) {
                                    $bc .= ' disabled';
                                    if ($letter === $q['answer'])                         $bc .= ' correct-ans';
                                    elseif ($r['answered'] && $letter === $r['userAns'])    $bc .= ' wrong-ans';
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

        // Active highlight on scroll
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

        // ── Timer ──────────────────────────────────────────────
        const DURATION = 25 * 60;
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

            // Capture remaining time on manual submit
            document.getElementById('quizForm').addEventListener('submit', function() {
                if (timeInput) timeInput.value = remaining;
            });
        }
    </script>

</body>

</html>