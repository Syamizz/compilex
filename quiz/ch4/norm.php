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
        'q' => 'In top down parsing, how is the derivation tree generally built?',
        'choices' => [
            'A' => 'From the leaves upward to the root',
            'B' => 'From the starting nonterminal downward toward the terminals',
            'C' => 'By reading the whole input first and then choosing grammar rules',
            'D' => 'By ignoring grammar rules and matching only tokens',
        ],
        'answer' => 'B',
        'explain' => 'A top down parser starts with the starting nonterminal and applies grammar rules in a way that constructs the derivation tree from the root downward.',
        'color' => 'indigo',
        'image' => null,
    ],
    [
        'q' => 'What is the main job of a parser?',
        'choices' => [
            'A' => 'Convert source code directly into machine code',
            'B' => 'Determine whether an input string is in the grammar language and find its structure',
            'C' => 'Store every identifier into the symbol table',
            'D' => 'Remove all comments and whitespace from the source program',
        ],
        'answer' => 'B',
        'explain' => 'The parsing problem is to determine whether a string belongs to the language of a grammar and, if it does, determine the structure of the string.',
        'color' => 'teal',
        'image' => null,
    ],
    [
        'q' => 'A relation is best described as:',
        'choices' => [
            'A' => 'A set of ordered pairs',
            'B' => 'A list of grammar terminals only',
            'C' => 'A rule that must always contain epsilon',
            'D' => 'A stack operation in a pushdown machine',
        ],
        'answer' => 'A',
        'explain' => 'A relation is a set of ordered pairs. For example, (a,b) and (b,a) are different ordered pairs.',
        'color' => 'purple',
        'image' => null,
    ],
    [
        'q' => 'What does the reflexive transitive closure R* of a relation R include?',
        'choices' => [
            'A' => 'Only the original pairs in R',
            'B' => 'Only pairs where both elements are the same',
            'C' => 'The original pairs, transitive pairs, and reflexive pairs',
            'D' => 'Only pairs that contain terminal symbols',
        ],
        'answer' => 'C',
        'explain' => 'R* contains all pairs in R, adds transitive pairs such as (a,c) when (a,b) and (b,c) exist, and adds reflexive pairs such as (a,a).',
        'color' => 'rose',
        'image' => null,
    ],
    [
        'q' => 'Which condition must be true for a grammar to be a simple grammar?',
        'choices' => [
            'A' => 'Every rule must begin with a nonterminal',
            'B' => 'Every rule must be of the form A -> a alpha, and rules for the same nonterminal must begin with different terminals',
            'C' => 'Every grammar rule must contain an epsilon production',
            'D' => 'Every rule must have exactly one symbol on the right side',
        ],
        'answer' => 'B',
        'explain' => 'A simple grammar has rules of the form A -> a alpha, where A is a nonterminal, a is a terminal, and rules defining the same nonterminal begin with different terminals.',
        'color' => 'amber',
        'image' => null,
    ],
    [
        'q' => 'What is the selection set of a grammar rule used for in top down parsing?',
        'choices' => [
            'A' => 'To decide which grammar rule should be applied based on the current input symbol',
            'B' => 'To choose the next temporary variable name',
            'C' => 'To decide which user earns a badge',
            'D' => 'To store the final machine code instruction',
        ],
        'answer' => 'A',
        'explain' => 'The selection set contains the input symbols that indicate a particular grammar rule should be used.',
        'color' => 'green',
        'image' => null,
    ],
    [
        'q' => 'For top down parsing to choose rules correctly, rules defining the same nonterminal should have:',
        'choices' => [
            'A' => 'Identical selection sets',
            'B' => 'Empty selection sets',
            'C' => 'Disjoint selection sets',
            'D' => 'Selection sets containing only endmarkers',
        ],
        'answer' => 'C',
        'explain' => 'Selection sets for rules defining the same nonterminal must be disjoint so the parser can decide exactly which rule to apply.',
        'color' => 'cyan',
        'image' => null,
    ],
    [
        'q' => 'In the pushdown machine construction for a simple grammar, when the top stack symbol is a terminal and it matches the current input symbol, what should the machine do?',
        'choices' => [
            'A' => 'Replace the terminal with the starting nonterminal',
            'B' => 'Pop the stack and advance the input pointer',
            'C' => 'Retain the input pointer and push epsilon',
            'D' => 'Immediately reject the string',
        ],
        'answer' => 'B',
        'explain' => 'If the top stack symbol is a terminal and it matches the current input symbol, the machine pops the stack and advances the input pointer.',
        'color' => 'indigo',
        'image' => null,
    ],
    [
        'q' => 'When applying a grammar rule in the pushdown machine, why is the right side of the rule pushed in reverse order?',
        'choices' => [
            'A' => 'So the leftmost symbol of the rule will be processed first',
            'B' => 'So the parser reads the input from right to left',
            'C' => 'So terminals are ignored before nonterminals',
            'D' => 'So the stack can become empty immediately',
        ],
        'answer' => 'A',
        'explain' => 'Because a stack is last-in-first-out, pushing the rule body in reverse order makes the leftmost symbol appear on top and get processed first.',
        'color' => 'teal',
        'image' => null,
    ],
    [
        'q' => 'In recursive descent parsing, what is usually written for each nonterminal in the grammar?',
        'choices' => [
            'A' => 'A database table',
            'B' => 'A separate method or function',
            'C' => 'A CSS class',
            'D' => 'A lexical token value only',
        ],
        'answer' => 'B',
        'explain' => 'Recursive descent parsers commonly use one method for each nonterminal. The method recognizes strings that can be derived from that nonterminal.',
        'color' => 'purple',
        'image' => null,
    ],
    [
        'q' => 'A quasi-simple grammar extends a simple grammar by allowing:',
        'choices' => [
            'A' => 'Only terminal symbols',
            'B' => 'Rules with no nonterminals at all',
            'C' => 'Epsilon rules, while still requiring disjoint selection sets',
            'D' => 'Rules that begin with the same terminal for the same nonterminal',
        ],
        'answer' => 'C',
        'explain' => 'A quasi-simple grammar obeys the simple grammar restrictions but also permits epsilon rules, as long as rules for the same nonterminal still have disjoint selection sets.',
        'color' => 'rose',
        'image' => null,
    ],
    [
        'q' => 'For an epsilon rule N -> epsilon in a quasi-simple grammar, what is its selection set?',
        'choices' => [
            'A' => 'The follow set of N',
            'B' => 'The first terminal of the rule',
            'C' => 'Only the starting nonterminal',
            'D' => 'The set of all grammar rules',
        ],
        'answer' => 'A',
        'explain' => 'The selection set of an epsilon rule is the follow set of the nonterminal on the left side.',
        'color' => 'amber',
        'image' => null,
    ],
    [
        'q' => 'In a recursive descent parser, how is an epsilon rule usually applied?',
        'choices' => [
            'A' => 'By reading two input symbols',
            'B' => 'By returning without reading any input',
            'C' => 'By deleting the whole stack',
            'D' => 'By always rejecting the current input',
        ],
        'answer' => 'B',
        'explain' => 'An epsilon rule derives the empty string, so the parser applies it by returning to the caller without consuming an input symbol.',
        'color' => 'green',
        'image' => null,
    ],
    [
        'q' => 'In syntax-directed translation, what is an action symbol used for?',
        'choices' => [
            'A' => 'To force every input string to be accepted',
            'B' => 'To perform output or semantic actions during parsing',
            'C' => 'To replace all terminals with epsilon',
            'D' => 'To prevent recursive descent from using methods',
        ],
        'answer' => 'B',
        'explain' => 'Syntax-directed translation inserts action symbols into grammar rules so actions, such as outputting atoms or syntax-tree nodes, can be performed during parsing.',
        'color' => 'cyan',
        'image' => null,
    ],
    [
        'q' => 'In attributed grammars, what is the purpose of attributes?',
        'choices' => [
            'A' => 'To attach extra information to grammar symbols, such as values, types, or temporary locations',
            'B' => 'To remove all semantic meaning from a parse tree',
            'C' => 'To make every grammar right-linear',
            'D' => 'To replace the lexical analyzer',
        ],
        'answer' => 'A',
        'explain' => 'Attributes carry information on terminals and nonterminals. They can represent values, data types, temporary result locations, labels, and other semantic information.',
        'color' => 'indigo',
        'image' => null,
    ],
];

$total = count($questions);
$submitted = false;
$score = 0;
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = true;
    $timeRemaining = intval($_POST['time_remaining'] ?? 0);

    foreach ($questions as $i => $q) {
        $userAns = $_POST['q' . $i] ?? null;
        $answered = $userAns !== null;
        $correct = $answered && $userAns === $q['answer'];

        if ($correct) {
            $score++;
        }

        $results[] = [
            'answered' => $answered,
            'userAns' => $userAns,
            'correct' => $correct
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
    $board_chap = 4;
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
    'teal' => ['bg' => '#F0FDFA', 'border' => '#14B8A6', 'text' => '#0F766E', 'light' => '#99F6E4'],
    'purple' => ['bg' => '#FAF5FF', 'border' => '#A855F7', 'text' => '#7E22CE', 'light' => '#E9D5FF'],
    'rose' => ['bg' => '#FFF1F2', 'border' => '#F43F5E', 'text' => '#BE123C', 'light' => '#FECDD3'],
    'amber' => ['bg' => '#FFFBEB', 'border' => '#F59E0B', 'text' => '#92400E', 'light' => '#FDE68A'],
    'green' => ['bg' => '#F0FDF4', 'border' => '#22C55E', 'text' => '#15803D', 'light' => '#BBF7D0'],
    'cyan' => ['bg' => '#ECFEFF', 'border' => '#06B6D4', 'text' => '#155E75', 'light' => '#A5F3FC'],
];

function getImage(string $key): string
{
    return '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter 4 - Normal Quiz</title>

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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="home.php">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Chapter</a>
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
        <div class="difficulty-chip">Medium Level</div>
        <h1>Chapter 4 - <span class="accent">Multiple Choice</span> Quiz</h1>
        <p>15 questions covering top down parsing, simple grammars, quasi-simple grammars, recursive descent, syntax-directed translation, and attributed grammars.</p>
    </div>

    <div class="page-layout">
        <nav class="q-nav" id="qNav">
            <div class="q-nav-label">Question</div>

            <?php for ($i = 0; $i < $total; $i++):
                $nc = 'q-nav-btn';
                if ($submitted && isset($results[$i])) {
                    $r = $results[$i];
                    if (!$r['answered']) $nc .= ' nav-skipped';
                    elseif ($r['correct']) $nc .= ' nav-correct';
                    else $nc .= ' nav-wrong';
                }
            ?>
                <a href="#q-<?= $i ?>" class="<?= $nc ?>" id="nav-btn-<?= $i ?>"><?= $i + 1 ?></a>
            <?php endfor; ?>

            <div class="nav-count" id="nav-count">0 / <?= $total ?></div>

            <?php if (!$submitted): ?>
                <div class="nav-submit-wrap">
                    <button type="submit" form="quizForm" class="nav-submit-btn">Submit</button>
                </div>
            <?php endif; ?>
        </nav>

        <div class="quiz-wrap">
            <?php if (!$submitted): ?>
                <div class="timer-wrap">
                    <div class="timer-box" id="timerBox">
                        <span class="timer-icon">Timer</span>
                        <span id="timerDisplay">25:00</span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($submitted):
                $wrong = array_reduce(array_keys($results), fn($c, $i) => $c + (!$results[$i]['correct'] && $results[$i]['answered'] ? 1 : 0), 0);
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
                            <div style="margin-top:10px;font-size:13px;font-weight:600;color:#4338CA;"><?= htmlspecialchars($bonusReason) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="score-bar">
                        <div class="score-bar-fill" style="width:<?= $pct ?>%"></div>
                    </div>

                    <div class="score-msg">
                        <?php
                        if ($pct === 100 && $bonusPoints > 0) echo 'Perfect score with bonus! Excellent work.';
                        elseif ($pct === 100) echo 'Perfect! You mastered Chapter 4.';
                        elseif ($pct >= 80) echo 'Excellent! Strong understanding of top down parsing.';
                        elseif ($pct >= 60) echo 'Good effort. Review the explanations below.';
                        elseif ($pct >= 40) echo 'Keep going. Re-read Chapter 4 and try again.';
                        else echo 'Do not give up. Focus on simple grammars, follow sets, and recursive descent.';
                        ?>
                    </div>

                    <a class="btn-retry" href="<?= $_SERVER['PHP_SELF'] ?>">Retry Quiz</a>
                </div>
            <?php endif; ?>

            <form method="POST" id="quizForm">
                <input type="hidden" name="time_remaining" id="timeRemainingInput" value="0">

                <?php foreach ($questions as $i => $q):
                    $r = $results[$i] ?? null;
                    $extra = '';
                    if ($submitted && $r) {
                        if (!$r['answered']) $extra = ' result-skipped';
                        elseif ($r['correct']) $extra = ' result-correct';
                        else $extra = ' result-wrong';
                    }
                ?>
                    <div id="q-<?= $i ?>" class="q-card color-<?= $q['color'] ?><?= $extra ?>">
                        <?php if ($submitted && $r): ?>
                            <?php if (!$r['answered']): ?>
                                <span class="result-badge badge-skipped">Skipped</span>
                            <?php elseif ($r['correct']): ?>
                                <span class="result-badge badge-correct">Correct</span>
                            <?php else: ?>
                                <span class="result-badge badge-wrong">Wrong - Answer: <?= $q['answer'] ?></span>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="q-num">Question <?= $i + 1 ?> of <?= $total ?></div>
                        <div class="q-text"><?= htmlspecialchars($q['q']) ?></div>

                        <?php if (!empty($q['image'])): ?>
                            <div class="q-image">
                                <div class="q-image-label">Diagram</div>
                                <?= getImage($q['image']) ?>
                            </div>
                        <?php endif; ?>

                        <div class="choices">
                            <?php foreach ($q['choices'] as $letter => $text):
                                $bc = 'choice-btn';
                                if ($submitted && $r) {
                                    $bc .= ' disabled';
                                    if ($letter === $q['answer']) $bc .= ' correct-ans';
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
                            <div class="explain-box"><?= htmlspecialchars($q['explain']) ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <?php if (!$submitted): ?>
                    <div class="submit-wrap">
                        <button type="submit" class="btn-submit">Submit Answers</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <script>
        const total = <?= $total ?>;
        let answered = new Set();

        function selectChoice(btn) {
            const qi = btn.dataset.qi;

            document.querySelectorAll(`.choice-btn[data-qi="${qi}"]`).forEach(b => {
                b.classList.remove('selected');
                b.querySelector('input[type=radio]').checked = false;
            });

            btn.classList.add('selected');
            btn.querySelector('input[type=radio]').checked = true;
            answered.add(qi);

            const nb = document.getElementById('nav-btn-' + qi);
            if (nb && !nb.classList.contains('nav-correct') && !nb.classList.contains('nav-wrong')) {
                nb.classList.add('nav-answered');
            }

            document.getElementById('nav-count').textContent = answered.size + ' / ' + total;
        }

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

        if (answered.size) {
            document.getElementById('nav-count').textContent = answered.size + ' / ' + total;
        }

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

            document.getElementById('quizForm').addEventListener('submit', function() {
                if (timeInput) timeInput.value = remaining;
            });
        }
    </script>

</body>

</html>