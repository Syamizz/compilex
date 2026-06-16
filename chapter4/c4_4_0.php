<?php
session_start();
include '../dbconn.php';
include '../save_progress.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$chapter  = 4;
$page     = 12;
$nextPage = "c4_4_1.php";

if (isset($_GET['complete']) && $_GET['complete'] == '12') {
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
    <title>Chapter 4.4 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c1_1.css">
    <link rel="stylesheet" href="../css/c4/c4_4.css">

</head>

<body>

    <?php include 'navbar_c4.php'; ?>

    <div class="note-container">

        <!-- ── Sidebar TOC ─────────────────────────────────────── -->
        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#intro" class="toc-link">4.4 Intro & G5</a></li>
                <li><a href="#g5-sel" class="toc-link">G5 Selection Sets</a></li>
                <li><a href="#left-rec" class="toc-link">Left Recursion</a></li>
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
                <h1>Parsing Arithmetic Expressions Top Down</h1>
                <div class="metadata">
                    <span>⏱ 30 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>⚙️ LL(1) Parsing</span>
                </div>
            </header>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION 4.4 – Intro & G5                        -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="intro">
                <h2><span class="sec-icon">🔍</span> Introduction</h2>

                <p>
                    Now that we understand how to determine whether a grammar can be parsed top down, and how to
                    construct a top down parser, we can begin to address the problem of building top down parsers
                    for actual programming languages. One of the most heavily studied aspects of parsing programming
                    languages deals with <strong>arithmetic expressions</strong>.
                </p>
                <p>
                    Recall grammar <strong>G5</strong> for arithmetic expressions involving only addition and
                    multiplication, from Section 3.1. We wish to determine whether this grammar is <strong>LL(1)</strong>.
                </p>

                <h3>Grammar G5</h3>
                <div class="grammar-box">
                    <span class="rule-num">G5:</span><br>
                    <span class="rule-num">1.</span> <span class="nt">Expr</span> <span class="arr">→</span> <span class="nt">Expr</span> <span class="t">+</span> <span class="nt">Term</span><br>
                    <span class="rule-num">2.</span> <span class="nt">Expr</span> <span class="arr">→</span> <span class="nt">Term</span><br>
                    <span class="rule-num">3.</span> <span class="nt">Term</span> <span class="arr">→</span> <span class="nt">Term</span> <span class="t">∗</span> <span class="nt">Factor</span><br>
                    <span class="rule-num">4.</span> <span class="nt">Term</span> <span class="arr">→</span> <span class="nt">Factor</span><br>
                    <span class="rule-num">5.</span> <span class="nt">Factor</span> <span class="arr">→</span> <span class="t">(</span> <span class="nt">Expr</span> <span class="t">)</span><br>
                    <span class="rule-num">6.</span> <span class="nt">Factor</span> <span class="arr">→</span> <span class="t">var</span>
                </div>

                <p>
                    In order to determine whether this grammar is LL(1), we must first find the
                    <strong>selection set</strong> for each rule using the twelve-step algorithm from Section 4.3.
                </p>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- G5 Selection Sets (Steps 1–5, 12)              -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="g5-sel">
                <h2><span class="sec-icon">📋</span> G5 – Selection Sets (Steps 1, 2, 3, 4, 5, 12)</h2>

                <div class="algo-block">
                    <div class="algo-block-header">Step 1 — Nullable Rules & Nonterminals</div>
                    <div class="algo-block-body">
                        <div class="note-box tip"><span class="box-icon">✅</span>
                            <div>Nullable rules: <strong>none</strong><br>Nullable nonterminals: <strong>none</strong></div>
                        </div>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Step 2 — BDW Relation (Begins Directly With)</div>
                    <div class="algo-block-body">
                        <div class="grammar-box" style="line-height:1.9">
                            <span class="nt">Expr</span> <span class="arr">BDW</span> <span class="nt">Expr</span><br>
                            <span class="nt">Expr</span> <span class="arr">BDW</span> <span class="nt">Term</span><br>
                            <span class="nt">Term</span> <span class="arr">BDW</span> <span class="nt">Term</span><br>
                            <span class="nt">Term</span> <span class="arr">BDW</span> <span class="nt">Factor</span><br>
                            <span class="nt">Factor</span> <span class="arr">BDW</span> <span class="t">(</span><br>
                            <span class="nt">Factor</span> <span class="arr">BDW</span> <span class="t">var</span>
                        </div>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Step 3 — BW Relation (Reflexive Transitive Closure of BDW)</div>
                    <div class="algo-block-body">
                        <div class="grammar-box" style="line-height:1.9">
                            <span class="nt">Expr</span> <span class="arr">BW</span> <span class="nt">Expr</span><br>
                            <span class="nt">Expr</span> <span class="arr">BW</span> <span class="nt">Term</span><br>
                            <span class="nt">Term</span> <span class="arr">BW</span> <span class="nt">Term</span><br>
                            <span class="nt">Term</span> <span class="arr">BW</span> <span class="nt">Factor</span><br>
                            <span class="nt">Factor</span> <span class="arr">BW</span> <span class="t">(</span><br>
                            <span class="nt">Factor</span> <span class="arr">BW</span> <span class="t">var</span><br>
                            <span style="color:#94E2D5">Factor</span> <span class="arr">BW</span> <span class="nt">Factor</span><br>
                            <span class="t">(</span> <span class="arr">BW</span> <span class="t">(</span><br>
                            <span class="t">var</span> <span class="arr">BW</span> <span class="t">var</span><br>
                            <span class="nt">Expr</span> <span class="arr">BW</span> <span class="nt">Factor</span><br>
                            <span class="nt">Expr</span> <span class="arr">BW</span> <span class="t">(</span><br>
                            <span class="nt">Expr</span> <span class="arr">BW</span> <span class="t">var</span><br>
                            <span class="nt">Term</span> <span class="arr">BW</span> <span class="t">(</span><br>
                            <span class="nt">Term</span> <span class="arr">BW</span> <span class="t">var</span><br>
                            <span class="t">∗</span> <span class="arr">BW</span> <span class="t">∗</span><br>
                            <span class="t">+</span> <span class="arr">BW</span> <span class="t">+</span><br>
                            <span class="t">)</span> <span class="arr">BW</span> <span class="t">)</span>
                        </div>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Step 4 — First Sets</div>
                    <div class="algo-block-body">
                        <div class="grammar-box" style="line-height:1.9">
                            First(<span class="nt">Expr</span>) = { <span class="t">(</span>, <span class="t">var</span> }<br>
                            First(<span class="nt">Term</span>) = { <span class="t">(</span>, <span class="t">var</span> }<br>
                            First(<span class="nt">Factor</span>) = { <span class="t">(</span>, <span class="t">var</span> }
                        </div>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Step 5 — First of Each Rule's RHS</div>
                    <div class="algo-block-body">
                        <div class="grammar-box" style="line-height:1.9">
                            (1) First(<span class="nt">Expr</span> <span class="t">+</span> <span class="nt">Term</span>) = { <span class="t">(</span>, <span class="t">var</span> }<br>
                            (2) First(<span class="nt">Term</span>) = { <span class="t">(</span>, <span class="t">var</span> }<br>
                            (3) First(<span class="nt">Term</span> <span class="t">∗</span> <span class="nt">Factor</span>) = { <span class="t">(</span>, <span class="t">var</span> }<br>
                            (4) First(<span class="nt">Factor</span>) = { <span class="t">(</span>, <span class="t">var</span> }<br>
                            (5) First(<span class="t">( </span><span class="nt">Expr</span><span class="t"> )</span>) = { <span class="t">(</span> }<br>
                            (6) First(<span class="t">var</span>) = { <span class="t">var</span> }
                        </div>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Step 12 — Selection Sets for G5</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px; color:var(--muted);">Since there are no nullable rules, selection sets come directly from Step 5.</p>
                        <div class="sel-grid">
                            <div class="sel-card conflict">
                                <div class="rule-id">⚠ Rule 1 — Expr → Expr + Term</div>
                                <div class="sel-val">Sel(1) = { (, var }</div>
                            </div>
                            <div class="sel-card conflict">
                                <div class="rule-id">⚠ Rule 2 — Expr → Term</div>
                                <div class="sel-val">Sel(2) = { (, var }</div>
                            </div>
                            <div class="sel-card conflict">
                                <div class="rule-id">⚠ Rule 3 — Term → Term ∗ Factor</div>
                                <div class="sel-val">Sel(3) = { (, var }</div>
                            </div>
                            <div class="sel-card conflict">
                                <div class="rule-id">⚠ Rule 4 — Term → Factor</div>
                                <div class="sel-val">Sel(4) = { (, var }</div>
                            </div>
                            <div class="sel-card ok">
                                <div class="rule-id">✓ Rule 5 — Factor → ( Expr )</div>
                                <div class="sel-val">Sel(5) = { ( }</div>
                            </div>
                            <div class="sel-card ok">
                                <div class="rule-id">✓ Rule 6 — Factor → var</div>
                                <div class="sel-val">Sel(6) = { var }</div>
                            </div>
                        </div>

                        <div class="note-box warn" style="margin-top:16px;">
                            <span class="box-icon">⚠️</span>
                            <div>
                                <strong>G5 is NOT LL(1).</strong> Rules 1 and 2 both define <code>Expr</code> and their selection sets
                                intersect ({(, var}). The same conflict occurs between rules 3 and 4 for <code>Term</code>.
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Left Recursion                        -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="left-rec">
                <h2><span class="sec-icon">🔄</span> Left Recursion</h2>

                <p>
                    The fact that grammar G5 is not suitable for top down parsing can be determined much more
                    easily by <strong>inspection</strong>. Rules 1 and 3 both have a property known as
                    <strong>left recursion</strong>:
                </p>

                <div class="grammar-box">
                    <span class="rule-num">1.</span> <span class="nt">Expr</span> <span class="arr">→</span> <span class="nt">Expr</span> <span class="t">+</span> <span class="nt">Term</span><br>
                    <span class="rule-num">3.</span> <span class="nt">Term</span> <span class="arr">→</span> <span class="nt">Term</span> <span class="t">∗</span> <span class="nt">Factor</span>
                </div>

                <p>They are in the general form:</p>
                <div class="grammar-box">
                    <span class="nt">A</span> <span class="arr">→</span> <span class="nt">A</span><span style="color:#FAB387">α</span>
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Any rule of the form A → Aα cannot be parsed top down.</strong>
                        In a recursive descent parser, the method for nonterminal A would immediately call itself,
                        producing <em>infinite recursion</em> with no escape hatch. Any grammar with left recursion
                        cannot be LL(1).
                    </div>
                </div>

                <h3>Eliminating Left Recursion</h3>
                <p>
                    Left recursion can be eliminated by rewriting the grammar with an equivalent grammar that
                    does not have left recursion. In general, if the offending rules are in the form:
                </p>
                <div class="grammar-box">
                    <span class="nt">A</span> <span class="arr">→</span> <span class="nt">A</span><span style="color:#FAB387">α</span><br>
                    <span class="nt">A</span> <span class="arr">→</span> <span style="color:#FAB387">β</span>
                </div>
                <p>
                    where <code>β</code> is a string of terminals and nonterminals that does <em>not</em> begin with
                    <code>A</code>, we can eliminate the left recursion by introducing a new nonterminal
                    <code>R</code> and rewriting as:
                </p>
                <div class="grammar-box">
                    <span class="nt">A</span> <span class="arr">→</span> <span style="color:#FAB387">β</span> <span class="nt">R</span><br>
                    <span class="nt">R</span> <span class="arr">→</span> <span style="color:#FAB387">α</span> <span class="nt">R</span><br>
                    <span class="nt">R</span> <span class="arr">→</span> <span class="eps">ε</span>
                </div>

                <div class="note-box pro">
                    <span class="box-icon">📌</span>
                    <div>
                        A more detailed and complete explanation of left recursion elimination can be found in
                        <strong>Parsons [1992]</strong>.
                    </div>
                </div>
            </section>







            <!-- ── Chapter nav ──────────────────────────────── -->
            <div class="chapter-nav">
                <a href="c4_3_1.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>4.3 LL(1) Grammars (Part 2)</div>
                </a>
                <a href="?complete=12" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.4 Parsing Arithmetic Expressions Top Down (Part 2)
                    </div>
                    <span>→</span>
                </a>
            </div>

        </article>
    </div><!-- /.note-container -->

    <!-- ── Scripts ──────────────────────────────────────────── -->
    <script>
        // ── TOC scroll progress ──────────────────────────────
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
            tocLinks.forEach(l => l.classList.toggle('active', l.getAttribute('href') === '#' + current));
        });


        let curStep = 0;

        function renderStep() {
            const s = steps[curStep];
            document.getElementById('st-label').textContent = s.label;
            document.getElementById('st-sublabel').textContent = s.sub;
            document.getElementById('st-title').textContent = s.title;
            document.getElementById('st-desc').textContent = s.desc;
            document.getElementById('st-content').innerHTML = s.html;
            document.getElementById('st-counter').textContent = `Step ${curStep + 1} of ${steps.length}`;
            document.getElementById('st-prev').disabled = curStep === 0;
            const nxt = document.getElementById('st-next');
            nxt.disabled = curStep === steps.length - 1;
            nxt.textContent = curStep === steps.length - 1 ? 'Done ✓' : 'Next →';
            nxt.className = 'step-btn' + (curStep === steps.length - 1 ? '' : ' primary');
        }

        function stepMove(dir) {
            curStep = Math.max(0, Math.min(steps.length - 1, curStep + dir));
            renderStep();
        }

        renderStep();
    </script>

</body>

</html>