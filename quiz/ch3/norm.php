<?php
session_start();
include '../../dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$questions = [
    // ── 3.0.1 Grammars ────────────────────────────────────────────
    [
        'q'      => 'Which of the following best describes a grammar in formal language theory?',
        'choices' => [
            'A' => 'A set of strings from a given alphabet',
            'B' => 'A list of rules used to produce all strings of a language without generating strings outside it',
            'C' => 'A finite state machine that accepts or rejects input strings',
            'D' => 'A table that maps input symbols to output tokens',
        ],
        'answer'  => 'B',
        'explain' => 'A grammar is a list of rules which can be used to produce or generate all the strings of a language, and which does not generate any strings which are not in the language.',
        'color'   => 'indigo',
        'image'   => null,
    ],
    [
        'q'      => 'Using Grammar G1 below, which language does it specify?\n\n  1. S → 0S0\n  2. S → 1S1\n  3. S → 0\n  4. S → 1',
        'choices' => [
            'A' => 'All binary strings of even length',
            'B' => 'All binary strings with more 0s than 1s',
            'C' => 'Palindromes of odd length over {0, 1}',
            'D' => 'All binary strings beginning and ending with the same symbol',
        ],
        'answer'  => 'C',
        'explain' => 'Grammar G1 generates strings like 0, 1, 000, 010, 101, 111 — all palindromes of odd length over {0,1}. Each rule wraps the current string with a matching symbol on both sides, ensuring it reads the same forwards and backwards.',
        'color'   => 'teal',
        'image'   => null,
    ],
    [
        'q'      => 'What is a derivation in the context of a grammar?',
        'choices' => [
            'A' => 'A tree structure showing the hierarchy of grammar rules',
            'B' => 'A sequence of rewriting rules applied to the starting nonterminal, ending with a string of terminals',
            'C' => 'A set of all possible strings that a grammar can produce',
            'D' => 'A function that maps nonterminals to their token classes',
        ],
        'answer'  => 'B',
        'explain' => 'A derivation is a sequence of rewriting rules, applied to the starting nonterminal, ending with a string of terminals. It demonstrates that a particular string is a member of the language.',
        'color'   => 'purple',
        'image'   => null,
    ],

    // ── 3.0.2 Classes of Grammars ──────────────────────────────────
    [
        'q'      => 'According to Chomsky\'s classification, which grammar type allows rules of the form A → α, where A is a single nonterminal and α is any string of terminals and nonterminals?',
        'choices' => [
            'A' => 'Unrestricted',
            'B' => 'Context-Sensitive',
            'C' => 'Context-Free',
            'D' => 'Right Linear',
        ],
        'answer'  => 'C',
        'explain' => 'A context-free grammar has rules of the form A → α, where A is a single nonterminal and α is any string of terminals and nonterminals. Most programming languages are defined using this type.',
        'color'   => 'rose',
        'image'   => null,
    ],
    [
        'q'      => 'Which of the following grammar rules is classified as Right Linear (Type 3)?',
        'choices' => [
            'A' => 'aSb → aAcBb',
            'B' => 'AB → BA',
            'C' => 'S → aBc',
            'D' => 'B → aA',
        ],
        'answer'  => 'D',
        'explain' => 'A right linear grammar rule has the form A → aB or A → a, where A and B are nonterminals and a is a terminal. "B → aA" matches the form A → aB exactly — it is Type 3, Right Linear.',
        'color'   => 'amber',
        'image'   => null,
    ],
    [
        'q'      => 'The language L(G3) = {aⁿbⁿcⁿ | n ≥ 0} from the notes belongs to which grammar class?',
        'choices' => [
            'A' => 'Right Linear only',
            'B' => 'Context-Free but not Right Linear',
            'C' => 'Context-Sensitive but not Context-Free',
            'D' => 'Unrestricted but not Context-Sensitive',
        ],
        'answer'  => 'C',
        'explain' => 'The language {aⁿbⁿcⁿ} requires a context-sensitive grammar (Grammar G3 in the notes). There is no context-free grammar that can generate this language, making it context-sensitive but not context-free.',
        'color'   => 'green',
        'image'   => null,
    ],

    // ── 3.0.3 Context-Free Grammars ────────────────────────────────
    [
        'q'      => 'A context-free grammar is said to be ambiguous when:',
        'choices' => [
            'A' => 'It generates strings that are not in the intended language',
            'B' => 'There is more than one derivation tree for a particular string',
            'C' => 'It has more than one nonterminal symbol',
            'D' => 'It uses both left-most and right-most derivations for the same rule',
        ],
        'answer'  => 'B',
        'explain' => 'A context-free grammar is ambiguous if there is more than one derivation TREE for a particular string. Multiple derivations that yield the same tree are not considered ambiguous.',
        'color'   => 'cyan',
        'image'   => null,
    ],
    [
        'q'      => 'In BNF notation, which of the following is the correct BNF representation of the grammar rule: S → a S b | ε ?',
        'choices' => [
            'A' => 'S ::= a S b + ε',
            'B' => '<S> ::= a <S> b | ε',
            'C' => '[S] := a [S] b | ε',
            'D' => 'S = "a" S "b" | null',
        ],
        'answer'  => 'B',
        'explain' => 'In Backus-Naur Form (BNF), nonterminals are enclosed in angle brackets <>, and the arrow → is replaced by ::=. The vertical bar | allows multiple alternatives on one line.',
        'color'   => 'indigo',
        'image'   => null,
    ],

    // ── 3.0.4 Pushdown Machines ─────────────────────────────────────
    [
        'q'      => 'Which of the following correctly describes a pushdown machine\'s stack operation Rep(X, Y, Z)?',
        'choices' => [
            'A' => 'Pushes X, Y, Z onto the stack without removing anything',
            'B' => 'Pops the top symbol and replaces it by pushing Z first, then Y, then X (so X ends on top)',
            'C' => 'Pops the top symbol and pushes X, Y, Z in order left to right (so Z ends on top)',
            'D' => 'Replaces the entire stack contents with X, Y, and Z',
        ],
        'answer'  => 'C',
        'explain' => 'The Replace function Rep(X,Y,Z) pops the top stack symbol and pushes the symbols in the order listed LEFT to RIGHT. So X is pushed first, then Y, then Z — meaning Z ends up on top of the stack.',
        'color'   => 'teal',
        'image'   => null,
    ],
    [
        'q'      => 'Look at the pushdown machine diagram for accepting the language {aⁿbⁿ | n ≥ 0}. What does the machine do while in state S1 reading the input "a"?',
        'choices' => [
            'A' => 'Pops an X from the stack and advances',
            'B' => 'Pushes an X onto the stack and advances',
            'C' => 'Transitions to S2 and rejects the input',
            'D' => 'Outputs the symbol "a" and stays in S1',
        ],
        'answer'  => 'B',
        'explain' => 'In state S1, the machine pushes an X onto the stack for each "a" it reads and advances the input pointer. This counts the a\'s. In state S2 it pops an X for each "b", matching the counts.',
        'color'   => 'purple',
        'image'   => 'pdm_anbn',
    ],
    [
        'q'      => 'Which key difference distinguishes a pushdown machine from a finite state machine?',
        'choices' => [
            'A' => 'A pushdown machine can have more than one accepting state',
            'B' => 'A pushdown machine has an infinite stack, enabling it to accept context-free languages',
            'C' => 'A pushdown machine can backtrack over input symbols already read',
            'D' => 'A pushdown machine uses regular expressions for state transitions',
        ],
        'answer'  => 'B',
        'explain' => 'The key difference is the infinite stack. Without it, a pushdown machine is just a finite state machine. The stack allows it to accept context-free languages, which FSMs cannot handle (e.g., balanced parentheses).',
        'color'   => 'rose',
        'image'   => null,
    ],

    // ── 3.0.5 Correspondence ────────────────────────────────────────
    [
        'q'      => 'The language of palindromes over {0,1} WITHOUT a centermarker cannot be accepted by a deterministic pushdown machine. Why?',
        'choices' => [
            'A' => 'The alphabet {0,1} is too small for a deterministic machine to handle',
            'B' => 'The machine would need to count an infinite number of symbols',
            'C' => 'Without a centermarker the machine never knows when to switch from pushing to popping',
            'D' => 'Palindromes are a right-linear language and require only an FSM',
        ],
        'answer'  => 'C',
        'explain' => 'Without a centermarker, the machine never knows for sure when it is processing the mirror image of the initial portion of the string — it cannot determine when to stop pushing and start popping. A nondeterministic machine would be needed.',
        'color'   => 'amber',
        'image'   => null,
    ],

    // ── 3.1 Ambiguities ─────────────────────────────────────────────
    [
        'q'      => 'Grammar G4 for arithmetic expressions is ambiguous. Grammar G5 (shown below) resolves this by:\n\n  1. Expr → Expr + Term\n  2. Expr → Term\n  3. Term → Term ∗ Factor\n  4. Term → Factor\n  5. Factor → ( Expr ) | var | const',
        'choices' => [
            'A' => 'Removing multiplication from the grammar entirely',
            'B' => 'Introducing the nonterminals Term and Factor to enforce operator precedence',
            'C' => 'Using BNF notation instead of standard grammar rules',
            'D' => 'Adding extra parentheses around every expression',
        ],
        'answer'  => 'B',
        'explain' => 'Grammar G5 resolves the ambiguity of G4 by introducing Term and Factor as intermediate nonterminals. This enforces that multiplication (*) binds more tightly than addition (+), reflecting standard operator precedence.',
        'color'   => 'green',
        'image'   => null,
    ],
    [
        'q'      => 'Consider the "dangling else" ambiguity in grammar G6. How does Grammar G7 resolve it?',
        'choices' => [
            'A' => 'By removing the else clause from the grammar entirely',
            'B' => 'By requiring all if statements to have an else clause',
            'C' => 'By introducing Matched and Unmatched nonterminals so that each else is paired with the closest unmatched if',
            'D' => 'By reordering the grammar rules so that if appears before else',
        ],
        'answer'  => 'C',
        'explain' => 'Grammar G7 differentiates between Matched (if-else pairs) and Unmatched (if without else) statements using two nonterminals. This ensures each else is associated with the closest previous unmatched if, resolving the ambiguity.',
        'color'   => 'cyan',
        'image'   => null,
    ],
    [
        'q'      => 'In a derivation tree for var + var ∗ var using Grammar G5, which operation appears higher (closer to the root) in the tree, and what does this indicate?',
        'choices' => [
            'A' => 'Multiplication (*) is higher, indicating it has lower precedence',
            'B' => 'Addition (+) is higher, indicating it has lower precedence and is evaluated last',
            'C' => 'Both operators are at the same level, indicating equal precedence',
            'D' => 'Addition (+) is higher, indicating it has higher precedence and is evaluated first',
        ],
        'answer'  => 'B',
        'explain' => 'In Grammar G5\'s derivation tree for var + var * var, the addition (+) node appears higher (closer to the root). Operators closer to the root are evaluated LAST — meaning addition has lower precedence and multiplication is computed first.',
        'color'   => 'indigo',
        'image'   => 'g5_tree',
    ],
];

$total     = count($questions);
$submitted = false;
$score     = 0;
$results   = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = true;

    foreach ($questions as $i => $q) {
        $userAns  = $_POST['q' . $i] ?? null;
        $answered = $userAns !== null;
        $correct  = $answered && $userAns === $q['answer'];

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

    $board_id = 'BD' . $next_number;
    $board_chap = 3;
    $quiz_type = "Normal";

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

function getImage(string $key): string
{
    $imgs = [

        'pdm_anbn' => '<svg viewBox="0 0 520 200" xmlns="http://www.w3.org/2000/svg" style="max-width:520px;width:100%;display:block;margin:12px 0;">
  <defs>
    <marker id="arr" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
      <path d="M0,0 L0,6 L8,3 z" fill="#374151"/>
    </marker>
  </defs>
  <!-- Arrow into S1 -->
  <line x1="20" y1="100" x2="60" y2="100" stroke="#6366F1" stroke-width="2" marker-end="url(#arr)"/>
  <!-- S1 -->
  <circle cx="90" cy="100" r="28" fill="#EEF2FF" stroke="#6366F1" stroke-width="2"/>
  <text x="90" y="96" text-anchor="middle" font-size="13" font-weight="700" fill="#4338CA">S1</text>
  <text x="90" y="112" text-anchor="middle" font-size="9" fill="#4338CA">push a</text>
  <!-- S1 self-loop: push(X) / a -->
  <path d="M76,73 Q90,38 104,73" fill="none" stroke="#374151" stroke-width="1.5" marker-end="url(#arr)"/>
  <text x="90" y="30" text-anchor="middle" font-size="11" fill="#6366F1" font-weight="600">a / push(X)</text>
  <!-- S1 → S2 on b -->
  <line x1="118" y1="100" x2="202" y2="100" stroke="#374151" stroke-width="1.5" marker-end="url(#arr)"/>
  <text x="160" y="88" text-anchor="middle" font-size="11" fill="#374151">b / pop</text>
  <!-- S2 -->
  <circle cx="230" cy="100" r="28" fill="#F0FDFA" stroke="#14B8A6" stroke-width="2"/>
  <text x="230" y="96" text-anchor="middle" font-size="13" font-weight="700" fill="#0F766E">S2</text>
  <text x="230" y="112" text-anchor="middle" font-size="9" fill="#0F766E">pop b</text>
  <!-- S2 self-loop: pop / b -->
  <path d="M216,73 Q230,38 244,73" fill="none" stroke="#374151" stroke-width="1.5" marker-end="url(#arr)"/>
  <text x="230" y="30" text-anchor="middle" font-size="11" fill="#6366F1" font-weight="600">b / pop</text>
  <!-- S2 → Accept on N (stack = comma) -->
  <line x1="258" y1="100" x2="330" y2="100" stroke="#374151" stroke-width="1.5" marker-end="url(#arr)"/>
  <text x="294" y="88" text-anchor="middle" font-size="11" fill="#374151">N / stack=,</text>
  <!-- Accept -->
  <rect x="335" y="78" width="80" height="44" rx="10" fill="#D1FAE5" stroke="#22C55E" stroke-width="2"/>
  <text x="375" y="97" text-anchor="middle" font-size="13" font-weight="700" fill="#065F46">ACCEPT</text>
  <text x="375" y="113" text-anchor="middle" font-size="9" fill="#065F46">n ≥ 0</text>
  <!-- S1 → Accept directly on N (ε case) -->
  <path d="M90,128 Q90,168 335,168 Q360,168 360,122" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-dasharray="5,3" marker-end="url(#arr)"/>
  <text x="220" y="182" text-anchor="middle" font-size="10" fill="#9CA3AF">N, stack=,  (ε case: n=0)</text>
  <!-- Stack diagram -->
  <rect x="430" y="60" width="70" height="110" rx="6" fill="#F8F7FF" stroke="#C7D2FE" stroke-width="1.5"/>
  <text x="465" y="80" text-anchor="middle" font-size="10" font-weight="700" fill="#6366F1">STACK</text>
  <text x="465" y="100" text-anchor="middle" font-size="11" fill="#374151">X</text>
  <text x="465" y="118" text-anchor="middle" font-size="11" fill="#374151">X</text>
  <text x="465" y="136" text-anchor="middle" font-size="11" fill="#374151">X</text>
  <text x="465" y="154" text-anchor="middle" font-size="11" fill="#4338CA">╌ (bottom)</text>
  <text x="465" y="175" text-anchor="middle" font-size="9" fill="#9CA3AF">after reading "aaa"</text>
</svg>',

        'g5_tree' => '<svg viewBox="0 0 440 240" xmlns="http://www.w3.org/2000/svg" style="max-width:440px;width:100%;display:block;margin:12px 0;">
  <!-- Title -->
  <text x="220" y="18" text-anchor="middle" font-size="11" font-weight="700" fill="#6B7280">Derivation Tree: var + var * var  (Grammar G5)</text>
  <!-- Root: Expr -->
  <rect x="170" y="26" width="60" height="26" rx="8" fill="#EEF2FF" stroke="#6366F1" stroke-width="1.5"/>
  <text x="200" y="44" text-anchor="middle" font-size="13" font-weight="700" fill="#4338CA">Expr</text>
  <!-- Expr → Expr + Term -->
  <line x1="180" y1="52" x2="100" y2="82" stroke="#6366F1" stroke-width="1.5"/>
  <line x1="200" y1="52" x2="200" y2="82" stroke="#374151" stroke-width="1.5"/>
  <line x1="220" y1="52" x2="310" y2="82" stroke="#6366F1" stroke-width="1.5"/>
  <!-- Left child: Expr -->
  <rect x="66" y="82" width="60" height="26" rx="8" fill="#EEF2FF" stroke="#6366F1" stroke-width="1.5"/>
  <text x="96" y="100" text-anchor="middle" font-size="13" font-weight="700" fill="#4338CA">Expr</text>
  <!-- Middle: + -->
  <rect x="176" y="82" width="28" height="26" rx="6" fill="#FEF3C7" stroke="#F59E0B" stroke-width="1.5"/>
  <text x="190" y="100" text-anchor="middle" font-size="14" font-weight="700" fill="#92400E">+</text>
  <!-- Right child: Term -->
  <rect x="276" y="82" width="60" height="26" rx="8" fill="#F0FDF4" stroke="#22C55E" stroke-width="1.5"/>
  <text x="306" y="100" text-anchor="middle" font-size="13" font-weight="700" fill="#15803D">Term</text>
  <!-- Expr → Term → Factor → var (left leaf) -->
  <line x1="96" y1="108" x2="96" y2="136" stroke="#374151" stroke-width="1.5"/>
  <rect x="66" y="136" width="60" height="26" rx="8" fill="#F0FDF4" stroke="#22C55E" stroke-width="1.5"/>
  <text x="96" y="154" text-anchor="middle" font-size="13" font-weight="700" fill="#15803D">Term</text>
  <line x1="96" y1="162" x2="96" y2="190" stroke="#374151" stroke-width="1.5"/>
  <rect x="66" y="190" width="60" height="26" rx="8" fill="#FAF5FF" stroke="#A855F7" stroke-width="1.5"/>
  <text x="96" y="208" text-anchor="middle" font-size="13" font-weight="700" fill="#7E22CE">Factor</text>
  <line x1="96" y1="216" x2="96" y2="228" stroke="#374151" stroke-width="1"/>
  <text x="96" y="238" text-anchor="middle" font-size="12" font-weight="600" fill="#374151">var</text>
  <!-- Term → Term * Factor (right subtree) -->
  <line x1="296" y1="108" x2="256" y2="136" stroke="#374151" stroke-width="1.5"/>
  <line x1="306" y1="108" x2="306" y2="136" stroke="#374151" stroke-width="1.5"/>
  <line x1="316" y1="108" x2="356" y2="136" stroke="#374151" stroke-width="1.5"/>
  <!-- Term left child: Factor -->
  <rect x="222" y="136" width="60" height="26" rx="8" fill="#FAF5FF" stroke="#A855F7" stroke-width="1.5"/>
  <text x="252" y="154" text-anchor="middle" font-size="13" font-weight="700" fill="#7E22CE">Factor</text>
  <!-- * operator -->
  <rect x="286" y="136" width="28" height="26" rx="6" fill="#FFF1F2" stroke="#F43F5E" stroke-width="1.5"/>
  <text x="300" y="154" text-anchor="middle" font-size="14" font-weight="700" fill="#BE123C">*</text>
  <!-- Term right child: Factor -->
  <rect x="326" y="136" width="60" height="26" rx="8" fill="#FAF5FF" stroke="#A855F7" stroke-width="1.5"/>
  <text x="356" y="154" text-anchor="middle" font-size="13" font-weight="700" fill="#7E22CE">Factor</text>
  <!-- Factor → var (middle) -->
  <line x1="252" y1="162" x2="252" y2="228" stroke="#374151" stroke-width="1"/>
  <text x="252" y="238" text-anchor="middle" font-size="12" font-weight="600" fill="#374151">var</text>
  <!-- Factor → var (right) -->
  <line x1="356" y1="162" x2="356" y2="228" stroke="#374151" stroke-width="1"/>
  <text x="356" y="238" text-anchor="middle" font-size="12" font-weight="600" fill="#374151">var</text>
  <!-- annotation -->
  <text x="10" y="238" font-size="9" fill="#9CA3AF">+ is near root → evaluated LAST (lower precedence)</text>
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
    <title>Chapter 3 – Normal Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../css/body.css">
    <style>
        body {
            background: #F4F3FF;
            font-family: 'Segoe UI', sans-serif;
        }

        .page-header {
            max-width: 1100px;
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

        .page-header p {
            color: #6B7280;
            font-size: 14px;
            margin-top: 6px;
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
            padding: 14px 12px 16px;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 6px;
            align-items: start;
            margin-top: 28px;
            margin-right: 20px;
            width: 230px;
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

        /* Nav answered count */
        .nav-count {
            grid-column: 1 / -1;
            text-align: center;
            font-size: 11px;
            color: #9CA3AF;
            margin-top: 2px;
        }

        /* Nav submit button */
        .nav-submit-wrap {
            grid-column: 1 / -1;
            margin-top: 10px;
        }

        .nav-submit-btn {
            width: 100%;
            padding: 9px 0;
            background: linear-gradient(135deg, #6366F1, #8B5CF6);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: .3px;
            box-shadow: 0 3px 10px rgba(99, 102, 241, .3);
            transition: transform .15s, box-shadow .15s;
        }

        .nav-submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(99, 102, 241, .4);
        }

        /* ── Quiz wrap ── */
        .quiz-wrap {
            flex: 1;
            min-width: 0;
            margin: 28px 0 80px;
            padding: 0;
        }

        /* ── Q card ── */
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

        .q-num {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: 10px;
            background: #EEF2FF;
            color: #4338CA;
        }

        .q-text {
            font-size: 15px;
            font-weight: 600;
            color: #1E1B4B;
            line-height: 1.6;
            margin-bottom: 12px;
            white-space: pre-line;
        }

        /* Image box */
        .q-image {
            background: #F8F7FF;
            border: 1px solid #E0E7FF;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 16px;
            overflow-x: auto;
        }

        .q-image-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            color: #9CA3AF;
            margin-bottom: 4px;
        }

        /* Choices */
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

        .choice-btn:hover:not([disabled]) {
            border-color: #6366F1;
            background: #EEF2FF;
            color: #4338CA;
        }

        .choice-btn.selected {
            border-color: #6366F1;
            background: #EEF2FF;
            color: #4338CA;
            font-weight: 600;
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
            background: #6366F1;
            color: #fff;
        }

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

        /* Result overrides */
        .q-card.result-correct {
            border-left-color: #22C55E !important;
        }

        .q-card.result-wrong {
            border-left-color: #EF4444 !important;
        }

        .q-card.result-skipped {
            border-left-color: #9CA3AF !important;
        }

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
            font-size: 44px;
            font-weight: 800;
            color: #6366F1;
            line-height: 1;
        }

        .score-lbl {
            font-size: 11px;
            color: #9CA3AF;
            margin-top: 4px;
            font-weight: 600;
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
            font-size: 15px;
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

        /* ── Main submit ── */
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
    </style>
</head>

<body>

    <nav id="navbar" class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a id="title" class="navbar-brand" href="#">CompileX</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                    <li class="nav-item"><a class="nav-link" href="quiz.php">Quiz</a></li>
                    <li class="nav-item"><a class="nav-link" href="leaderboard.php">Leaderboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
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
        <h1>Chapter 3 — <span class="accent">Multiple Choice</span> Quiz</h1>
        <p>15 questions covering Sections 3.0 (Grammars, Languages &amp; Pushdown Machines) and 3.1 (Ambiguities in Programming Languages). Some questions include diagrams.</p>
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
                    else                    $nc .= ' nav-wrong';
                }
            ?>
                <a href="#q-<?= $i ?>" class="<?= $nc ?>" id="nav-btn-<?= $i ?>"><?= $i + 1 ?></a>
            <?php endfor; ?>

            <div class="nav-count" id="nav-count">0 / <?= $total ?></div>

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
                    <div class="score-bar">
                        <div class="score-bar-fill" style="width:<?= $pct ?>%"></div>
                    </div>
                    <div class="score-msg">
                        <?php
                        if ($pct === 100)    echo '🏆 Perfect! You have mastered Chapter 3!';
                        elseif ($pct >= 80)  echo '🎉 Excellent! Great grasp of grammars and pushdown machines.';
                        elseif ($pct >= 60)  echo '👍 Good effort — review the diagrams and explanations below.';
                        elseif ($pct >= 40)  echo '📖 Keep going! Re-read sections 3.0 and 3.1.';
                        else                 echo '💪 Don\'t give up — study the grammar classes and try again!';
                        ?>
                    </div>
                    <a class="btn-retry" href="<?= $_SERVER['PHP_SELF'] ?>">↺ Retry Quiz</a>
                </div>
            <?php endif; ?>

            <form method="POST" id="quizForm">
                <?php foreach ($questions as $i => $q):
                    $r = $results[$i] ?? null;
                    $extra = '';
                    if ($submitted && $r) {
                        if (!$r['answered'])    $extra = ' result-skipped';
                        elseif ($r['correct'])  $extra = ' result-correct';
                        else                    $extra = ' result-wrong';
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
                                $bc  = 'choice-btn';
                                if ($submitted && $r) {
                                    $bc .= ' disabled';
                                    if ($letter === $q['answer'])                          $bc .= ' correct-ans';
                                    elseif ($r['answered'] && $letter === $r['userAns'])   $bc .= ' wrong-ans';
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
            const qi = btn.dataset.qi;
            const val = btn.dataset.val;

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

        // Active scroll highlight
        const cards = document.querySelectorAll('.q-card');
        const navBtns = document.querySelectorAll('.q-nav-btn');

        cards.forEach(card => {
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
            }).observe(card);
        });

        // Restore selections after form resubmit / back navigation
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

        if (answered.size)
            document.getElementById('nav-count').textContent = answered.size + ' / ' + total;
    </script>

</body>

</html>