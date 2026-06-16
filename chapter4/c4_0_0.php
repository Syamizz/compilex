<?php
session_start();
include '../dbconn.php';
include '../save_progress.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$chapter = 4;
$page    = 1;
$nextPage = "c4_0_1.php"; // next sub-page (adjust as needed)

if (isset($_GET['complete']) && $_GET['complete'] == '1') {
    saveProgress($conn, $user_id, $chapter, $page);
    header("Location: " . $nextPage);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter 4 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="../css/c1_1.css">
    <link rel="stylesheet" href="../css/c4/c4_0.css">
</head>

<body>

    <?php include 'navbar_c4.php'; ?>

    <div class="note-container">

        <!-- ── Sidebar TOC ─────────────────────────────────────── -->
        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#intro" class="toc-link">4.0 Top Down Parsing</a></li>
           </ul>
            <div class="toc-progress">
                <div class="toc-progress-label">
                    <span>Progress</span>
                    <span id="pct-label">0%</span>
                </div>
                <div class="toc-bar">
                    <div class="toc-bar-fill" id="toc-bar"></div>
                </div>
            </div>
        </nav>

        <!-- ── Main content ──────────────────────────────────── -->
        <article class="content">

            <header class="content-header">
                <div class="chapter-tag">📘 Chapter 4</div>
                <h1>Top Down Parsing</h1>
                <div class="metadata">
                    <span>⏱ 20 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>⚙️ Parsing Theory</span>
                </div>
            </header>

            <!-- ─────────────────────────────────────────────────── -->
            <!-- SECTION 4.0 – Introduction                         -->
            <!-- ─────────────────────────────────────────────────── -->
            <section id="intro">
                <h2><span class="sec-icon">🔍</span> What is Top Down Parsing?</h2>

                <p>
                    The <strong>parsing problem</strong> was defined in Section 3.2 as follows: given a grammar and an
                    input string, determine whether the string is in the language of the grammar, and — if so —
                    determine its structure.
                </p>
                <p>
                    Parsing algorithms are usually classified as either <strong>top down</strong> or
                    <strong>bottom up</strong>, which refers to the sequence in which a derivation tree is
                    built or traversed. In this chapter we consider only <em>top down</em> algorithms.
                </p>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        <strong>Key idea:</strong> In a top down algorithm the parser begins at the
                        <em>root</em> of the derivation tree (the start symbol) and works downwards, choosing
                        grammar rules that match the input one symbol at a time.
                    </div>
                </div>

                <h3>Grammar G8 — Running Example</h3>
                <p>Throughout this chapter we use the following grammar:</p>

                <div class="grammar-box">
                    <span class="rule-num">G8:</span><br>
                    <span class="rule-num">1.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="t">a</span> <span class="nt">S</span> <span class="t">b</span><br>
                    <span class="rule-num">2.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="t">b</span> <span class="nt">A</span> <span class="t">c</span><br>
                    <span class="rule-num">3.</span> <span class="nt">A</span> <span class="arr">→</span> <span class="t">b</span> <span class="nt">S</span><br>
                    <span class="rule-num">4.</span> <span class="nt">A</span> <span class="arr">→</span> <span class="t">a</span>
                </div>

                <div class="note-box pro">
                    <span class="box-icon">📌</span>
                    <div>
                        <strong>Convention:</strong> uppercase letters (<code>S</code>, <code>A</code>)
                        are <em>nonterminals</em>; lowercase letters (<code>a</code>, <code>b</code>,
                        <code>c</code>) are <em>terminals</em>. The arrow <code>→</code> means
                        "can be replaced by".
                    </div>
                </div>

                <h3>Derivation Tree for <code>abbbaccb</code></h3>
                <p>
                    Figure 4.1 shows the derivation tree produced when G8 is used to derive
                    <code>abbbaccb</code>. A top down algorithm builds this tree from the root
                    <code>S</code> downwards.
                </p>

                <!-- SVG derivation tree (Figure 4.1) -->
                <div class="tree-wrap">
                    <svg viewBox="0 0 420 280" width="380" xmlns="http://www.w3.org/2000/svg"
                        font-family="JetBrains Mono, monospace" font-size="14">

                        <!-- edges -->
                        <!-- S → a S b (level 1) -->
                        <line x1="210" y1="30" x2="100" y2="80" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="210" y1="30" x2="210" y2="80" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="210" y1="30" x2="320" y2="80" stroke="#6366F1" stroke-width="1.5" />

                        <!-- S → b A c (level 2) -->
                        <line x1="210" y1="95" x2="140" y2="145" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="210" y1="95" x2="210" y2="145" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="210" y1="95" x2="280" y2="145" stroke="#6366F1" stroke-width="1.5" />

                        <!-- A → b S (level 3) -->
                        <line x1="210" y1="160" x2="170" y2="210" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="210" y1="160" x2="250" y2="210" stroke="#6366F1" stroke-width="1.5" />

                        <!-- S → b A c (level 4) -->
                        <line x1="250" y1="225" x2="200" y2="255" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="250" y1="225" x2="250" y2="255" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="250" y1="225" x2="300" y2="255" stroke="#6366F1" stroke-width="1.5" />

                        <!-- nodes -->
                        <!-- L0: S -->
                        <circle cx="210" cy="22" r="14" fill="#6366F1" />
                        <text x="210" y="27" text-anchor="middle" fill="white" font-weight="700">S</text>

                        <!-- L1: a  S  b -->
                        <circle cx="100" cy="88" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="100" y="93" text-anchor="middle" fill="#065F46" font-weight="600">a</text>

                        <circle cx="210" cy="88" r="14" fill="#6366F1" />
                        <text x="210" y="93" text-anchor="middle" fill="white" font-weight="700">S</text>

                        <circle cx="320" cy="88" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="320" y="93" text-anchor="middle" fill="#065F46" font-weight="600">b</text>

                        <!-- L2: b  A  c -->
                        <circle cx="140" cy="153" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="140" y="158" text-anchor="middle" fill="#065F46" font-weight="600">b</text>

                        <circle cx="210" cy="153" r="14" fill="#CBA6F7" opacity=".9" />
                        <text x="210" y="158" text-anchor="middle" fill="#3730A3" font-weight="700">A</text>

                        <circle cx="280" cy="153" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="280" y="158" text-anchor="middle" fill="#065F46" font-weight="600">c</text>

                        <!-- L3: b  S -->
                        <circle cx="170" cy="218" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="170" y="223" text-anchor="middle" fill="#065F46" font-weight="600">b</text>

                        <circle cx="250" cy="218" r="14" fill="#6366F1" />
                        <text x="250" y="223" text-anchor="middle" fill="white" font-weight="700">S</text>

                        <!-- L4: b  A  c -->
                        <circle cx="200" cy="263" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="200" y="268" text-anchor="middle" fill="#065F46" font-weight="600">b</text>

                        <circle cx="250" cy="263" r="14" fill="#CBA6F7" opacity=".9" />
                        <text x="250" y="268" text-anchor="middle" fill="#3730A3" font-weight="700">A</text>

                        <circle cx="300" cy="263" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="300" y="268" text-anchor="middle" fill="#065F46" font-weight="600">c</text>
                    </svg>
                    <figcaption>Figure 4.1 — Derivation tree for <code>abbbaccb</code> using grammar G8</figcaption>
                </div>

                <h3>Sequence of Events (Figure 4.2)</h3>
                <p>
                    The derivation below shows exactly which rule is applied at each expansion step.
                    Underlined symbols have already been matched by the parser.
                </p>

                <div class="deriv-seq">
                    <span class="ds-step"><span class="nt" style="color:#6366F1">S</span></span>
                    <span class="ds-arrow">⇒</span>
                    <span class="ds-rule">1</span>
                    <span class="ds-step"><span class="underline">a</span>Sb</span>
                    <span class="ds-arrow">⇒</span>
                    <span class="ds-rule">2</span>
                    <span class="ds-step"><span class="underline">ab</span>Acb</span>
                    <span class="ds-arrow">⇒</span>
                    <span class="ds-rule">3</span>
                    <span class="ds-step"><span class="underline">abb</span>Scb</span>
                    <span class="ds-arrow">⇒</span>
                    <span class="ds-rule">2</span>
                    <span class="ds-step"><span class="underline">abbb</span>Accb</span>
                    <span class="ds-arrow">⇒</span>
                    <span class="ds-rule">4</span>
                    <span class="ds-step"><span class="underline">abbbaccb</span> ✓</span>
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Important:</strong> At each step, the parser examines the
                        <em>next unread input symbol</em> and compares it with the first symbol
                        on the right-hand side of available rules. G8 is simple enough that one
                        symbol of look-ahead is always sufficient to decide which rule to apply.
                    </div>
                </div>
            </section>

         
           
            <!-- ── Chapter nav ───────────────────────────────────── -->
            <div class="chapter-nav">
                <a href="../home.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Back to</span>Home</div>
                </a>
                <a href="?complete=1" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.0.1 Relations & 4.0.2 Reflexive Transitive Closure
                    </div>
                    <span>→</span>
                </a>
            </div>

        </article>
    </div><!-- /.note-container -->

    <!-- ── Scripts ─────────────────────────────────────────────── -->
    <script>
        // ── TOC scroll progress ──────────────────────────────────────
        const sections = document.querySelectorAll('section[id]');
        const tocLinks = document.querySelectorAll('.toc-link');
        const bar = document.getElementById('toc-bar');
        const pctLabel = document.getElementById('pct-label');

        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            const total = document.body.scrollHeight - window.innerHeight;
            const pct = Math.round((scrolled / total) * 100);
            bar.style.width = pct + '%';
            pctLabel.textContent = pct + '%';

            let current = '';
            sections.forEach(s => {
                if (scrolled >= s.offsetTop - 120) current = s.id;
            });
            tocLinks.forEach(l => {
                l.classList.toggle('active', l.getAttribute('href') === '#' + current);
            });
        });

        // ── Closure stepper data ─────────────────────────────────────
        const closureSteps = [{
                label: 'STEP 1 — Original pairs from R1',
                title: 'Copy all pairs from R1 into R1*',
                desc: 'By property 1, every pair already in R1 is automatically in R1*. These are our 5 starting pairs.',
                pairs: [{
                        text: '(a,b)',
                        cls: 'from-r'
                    },
                    {
                        text: '(c,d)',
                        cls: 'from-r'
                    },
                    {
                        text: '(b,a)',
                        cls: 'from-r'
                    },
                    {
                        text: '(b,c)',
                        cls: 'from-r'
                    },
                    {
                        text: '(c,c)',
                        cls: 'from-r'
                    },
                ]
            },
            {
                label: 'STEP 2 — Apply Transitivity',
                title: 'Add transitive pairs to R1*',
                desc: 'Check every pair combination in R1*. If (x,y) and (y,z) both exist, add (x,z). Repeat until no new pairs appear.',
                pairs: [{
                        text: '(a,b)',
                        cls: 'from-r'
                    },
                    {
                        text: '(c,d)',
                        cls: 'from-r'
                    },
                    {
                        text: '(b,a)',
                        cls: 'from-r'
                    },
                    {
                        text: '(b,c)',
                        cls: 'from-r'
                    },
                    {
                        text: '(c,c)',
                        cls: 'from-r'
                    },
                    {
                        text: '(a,c)',
                        cls: 'transitive',
                        note: '← (a,b)+(b,c)'
                    },
                    {
                        text: '(b,d)',
                        cls: 'transitive',
                        note: '← (b,c)+(c,d)'
                    },
                    {
                        text: '(a,d)',
                        cls: 'transitive',
                        note: '← (a,c)+(c,d)'
                    },
                ]
            },
            {
                label: 'STEP 3 — Apply Reflexivity',
                title: 'Add reflexive pairs (a,a) for every element a',
                desc: 'Every symbol that appears in any pair gets a self-loop (a,a) in R*. The symbols present are: a, b, c, d.',
                pairs: [{
                        text: '(a,b)',
                        cls: 'from-r'
                    },
                    {
                        text: '(c,d)',
                        cls: 'from-r'
                    },
                    {
                        text: '(b,a)',
                        cls: 'from-r'
                    },
                    {
                        text: '(b,c)',
                        cls: 'from-r'
                    },
                    {
                        text: '(c,c)',
                        cls: 'from-r'
                    },
                    {
                        text: '(a,c)',
                        cls: 'transitive'
                    },
                    {
                        text: '(b,d)',
                        cls: 'transitive'
                    },
                    {
                        text: '(a,d)',
                        cls: 'transitive'
                    },
                    {
                        text: '(a,a)',
                        cls: 'reflexive',
                        note: '← reflexive'
                    },
                    {
                        text: '(b,b)',
                        cls: 'reflexive',
                        note: '← reflexive'
                    },
                    {
                        text: '(d,d)',
                        cls: 'reflexive',
                        note: '← reflexive'
                    },
                ]
            }
        ];

        let csStep = 0;

        function renderClosure() {
            const s = closureSteps[csStep];
            document.getElementById('cs-label').textContent = s.label;
            document.getElementById('cs-step-title').textContent = s.title;
            document.getElementById('cs-desc').textContent = s.desc;
            document.getElementById('cs-counter').textContent =
                `Step ${csStep + 1} of ${closureSteps.length}`;

            const wrap = document.getElementById('cs-pairs');
            wrap.innerHTML = '<div class="pair-grid">' +
                s.pairs.map(p =>
                    `<div class="pair-chip ${p.cls}" title="${p.note || ''}">${p.text}</div>`
                ).join('') +
                '</div>';

            document.getElementById('cs-prev').disabled = csStep === 0;
            const nxt = document.getElementById('cs-next');
            nxt.disabled = csStep === closureSteps.length - 1;
            nxt.textContent = csStep === closureSteps.length - 1 ? 'Done ✓' : 'Next →';
            nxt.className = 'step-btn' + (csStep === closureSteps.length - 1 ? '' : ' primary');
        }

        function closureMove(dir) {
            csStep = Math.max(0, Math.min(closureSteps.length - 1, csStep + dir));
            renderClosure();
        }

        renderClosure();

        // ── Mini quiz ─────────────────────────────────────────────────
        function checkQuiz(el, correct) {
            if (document.querySelector('.quiz-opt.answered')) return;
            document.querySelectorAll('.quiz-opt').forEach(o => o.classList.add('answered'));
            el.classList.add(correct ? 'correct' : 'wrong');
            const fb = document.getElementById('qfb');
            fb.className = 'quiz-feedback show ' + (correct ? 'ok' : 'bad');
            fb.textContent = correct ?
                '✅ Correct! (x,y) and (y,z) chain together to give the transitive pair (x,z).' :
                '❌ Not quite. (x,a) and (x,x) or (y,y) come from reflexivity, not transitivity. Transitivity chains two overlapping pairs.';
        }
    </script>

</body>

</html>