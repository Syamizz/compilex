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
$page     = 13;
$nextPage = "c4_4_2.php"; 

if (isset($_GET['complete']) && $_GET['complete'] == '13') {
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
                <li><a href="#g16" class="toc-link">Grammar G16</a></li>
                <li><a href="#g16-algo" class="toc-link">G16 – 12-Step Algorithm</a></li>
                <li><a href="#g16-sel" class="toc-link">G16 Selection Sets</a></li>
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
            <!-- SECTION – Grammar G16                           -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="g16">
                <h2><span class="sec-icon">📐</span> Grammar G16 — Left Recursion Eliminated</h2>

                <p>
                    This methodology is used to rewrite the grammar for simple arithmetic expressions. The new
                    nonterminals introduced are <strong>Elist</strong> and <strong>Tlist</strong>. The equivalent
                    grammar G16 is shown below.
                </p>

                <div class="grammar-box">
                    <span class="rule-num">G16:</span><br>
                    <span class="rule-num">1.</span> <span class="nt">Expr</span> <span class="arr">→</span> <span class="nt">Term</span> <span class="nt">Elist</span><br>
                    <span class="rule-num">2.</span> <span class="nt">Elist</span> <span class="arr">→</span> <span class="t">+</span> <span class="nt">Term</span> <span class="nt">Elist</span><br>
                    <span class="rule-num">3.</span> <span class="nt">Elist</span> <span class="arr">→</span> <span class="eps">ε</span><br>
                    <span class="rule-num">4.</span> <span class="nt">Term</span> <span class="arr">→</span> <span class="nt">Factor</span> <span class="nt">Tlist</span><br>
                    <span class="rule-num">5.</span> <span class="nt">Tlist</span> <span class="arr">→</span> <span class="t">∗</span> <span class="nt">Factor</span> <span class="nt">Tlist</span><br>
                    <span class="rule-num">6.</span> <span class="nt">Tlist</span> <span class="arr">→</span> <span class="eps">ε</span><br>
                    <span class="rule-num">7.</span> <span class="nt">Factor</span><span class="arr">→</span> <span class="t">(</span> <span class="nt">Expr</span> <span class="t">)</span><br>
                    <span class="rule-num">8.</span> <span class="nt">Factor</span><span class="arr">→</span> <span class="t">var</span>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        In grammar G16, an <code>Expr</code> is still the sum of one or more <code>Terms</code>
                        and a <code>Term</code> is still the product of one or more <code>Factors</code>, but the
                        left recursion has been eliminated. This grammar also defines the <strong>precedence of
                            operators</strong> as desired. The student should construct several derivation trees to
                        be convinced it is not ambiguous.
                    </div>
                </div>

                <h3>Derivation Tree for <code>var + var * var</code> (Figure 4.12)</h3>

                <div class="tree-wrap">
                    <svg viewBox="0 0 560 360" width="520" xmlns="http://www.w3.org/2000/svg"
                        font-family="JetBrains Mono, monospace" font-size="13">

                        <!-- ── edges ── -->
                        <!-- Expr → Term Elist -->
                        <line x1="280" y1="28" x2="160" y2="75" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="280" y1="28" x2="380" y2="75" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Term → Factor Tlist -->
                        <line x1="160" y1="90" x2="100" y2="138" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="160" y1="90" x2="200" y2="138" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Factor → var -->
                        <line x1="100" y1="153" x2="100" y2="198" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Tlist → ε -->
                        <line x1="200" y1="153" x2="200" y2="198" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Elist → + Term Elist -->
                        <line x1="380" y1="90" x2="300" y2="138" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="380" y1="90" x2="400" y2="138" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="380" y1="90" x2="480" y2="138" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Term → Factor Tlist (right branch) -->
                        <line x1="400" y1="153" x2="360" y2="200" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="400" y1="153" x2="440" y2="200" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Factor → var (right) -->
                        <line x1="360" y1="215" x2="360" y2="260" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Tlist → * Factor Tlist -->
                        <line x1="440" y1="215" x2="400" y2="260" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="440" y1="215" x2="450" y2="260" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="440" y1="215" x2="500" y2="260" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Factor → var (deep) -->
                        <line x1="450" y1="275" x2="450" y2="318" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Tlist → ε (deep) -->
                        <line x1="500" y1="275" x2="500" y2="318" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Elist → ε (right) -->
                        <line x1="480" y1="153" x2="480" y2="198" stroke="#6366F1" stroke-width="1.5" />

                        <!-- ── nodes ── -->
                        <!-- Expr -->
                        <circle cx="280" cy="20" r="15" fill="#6366F1" />
                        <text x="280" y="25" text-anchor="middle" fill="white" font-weight="700">Expr</text>

                        <!-- Term (left) -->
                        <circle cx="160" cy="82" r="15" fill="#6366F1" />
                        <text x="160" y="87" text-anchor="middle" fill="white" font-weight="700">Term</text>

                        <!-- Elist -->
                        <circle cx="380" cy="82" r="15" fill="#CBA6F7" opacity=".9" />
                        <text x="380" y="87" text-anchor="middle" fill="#3730A3" font-weight="700">Elist</text>

                        <!-- Factor (left) -->
                        <circle cx="100" cy="145" r="16" fill="#CBA6F7" opacity=".85" />
                        <text x="100" y="150" text-anchor="middle" fill="#3730A3" font-weight="700">Factor</text>

                        <!-- Tlist (left) -->
                        <circle cx="200" cy="145" r="15" fill="#CBA6F7" opacity=".85" />
                        <text x="200" y="150" text-anchor="middle" fill="#3730A3" font-weight="700">Tlist</text>

                        <!-- var (left) -->
                        <circle cx="100" cy="210" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="100" y="215" text-anchor="middle" fill="#065F46" font-weight="600">var</text>

                        <!-- ε (Tlist left) -->
                        <text x="200" y="215" text-anchor="middle" fill="#FAB387" font-weight="600">ε</text>

                        <!-- + -->
                        <circle cx="300" cy="145" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="300" y="150" text-anchor="middle" fill="#065F46" font-weight="600">+</text>

                        <!-- Term (right) -->
                        <circle cx="400" cy="145" r="15" fill="#6366F1" />
                        <text x="400" y="150" text-anchor="middle" fill="white" font-weight="700">Term</text>

                        <!-- Elist (right-end) -->
                        <circle cx="480" cy="145" r="15" fill="#CBA6F7" opacity=".85" />
                        <text x="480" y="150" text-anchor="middle" fill="#3730A3" font-weight="700">Elist</text>

                        <!-- ε (Elist right) -->
                        <text x="480" y="212" text-anchor="middle" fill="#FAB387" font-weight="600">ε</text>

                        <!-- Factor (right) -->
                        <circle cx="360" cy="207" r="16" fill="#CBA6F7" opacity=".85" />
                        <text x="360" y="212" text-anchor="middle" fill="#3730A3" font-weight="700">Factor</text>

                        <!-- Tlist (right) -->
                        <circle cx="440" cy="207" r="15" fill="#CBA6F7" opacity=".85" />
                        <text x="440" y="212" text-anchor="middle" fill="#3730A3" font-weight="700">Tlist</text>

                        <!-- var (right) -->
                        <circle cx="360" cy="270" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="360" y="275" text-anchor="middle" fill="#065F46" font-weight="600">var</text>

                        <!-- * -->
                        <circle cx="400" cy="272" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="400" y="277" text-anchor="middle" fill="#065F46" font-weight="600">∗</text>

                        <!-- Factor (deep) -->
                        <circle cx="450" cy="268" r="16" fill="#CBA6F7" opacity=".85" />
                        <text x="450" y="273" text-anchor="middle" fill="#3730A3" font-weight="700">Factor</text>

                        <!-- Tlist (deep) -->
                        <circle cx="500" cy="268" r="15" fill="#CBA6F7" opacity=".85" />
                        <text x="500" y="273" text-anchor="middle" fill="#3730A3" font-weight="700">Tlist</text>

                        <!-- var (deep) -->
                        <circle cx="450" cy="328" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="450" y="333" text-anchor="middle" fill="#065F46" font-weight="600">var</text>

                        <!-- ε (Tlist deep) -->
                        <text x="500" y="333" text-anchor="middle" fill="#FAB387" font-weight="600">ε</text>
                    </svg>
                    <figcaption>Figure 4.12 — Derivation tree for <code>var + var * var</code> using grammar G16</figcaption>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – G16 12-Step Algorithm                 -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="g16-algo">
                <h2><span class="sec-icon">⚙️</span> G16 — 12-Step Algorithm</h2>
                <p>We now determine whether grammar G16 is LL(1) using the full algorithm to find selection sets.</p>

                <!-- Interactive stepper -->
                <div class="stepper">
                    <div class="stepper-header">
                        <h4 id="st-label">STEP 1</h4>
                        <span id="st-counter" style="font-size:12px;color:var(--purple);">Step 1 of 9</span>
                    </div>
                    <div class="stepper-body">
                        <div class="step-label" id="st-sublabel"></div>
                        <div class="step-title" id="st-title"></div>
                        <div class="step-desc" id="st-desc"></div>
                        <div id="st-content"></div>
                    </div>
                    <div class="stepper-nav">
                        <button class="step-btn" id="st-prev" onclick="stepMove(-1)">← Prev</button>
                        <button class="step-btn primary" id="st-next" onclick="stepMove(1)">Next →</button>
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – G16 Selection Sets Result             -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="g16-sel">
                <h2><span class="sec-icon">✅</span> G16 — Selection Sets Result</h2>

                <div class="sel-grid">
                    <div class="sel-card ok">
                        <div class="rule-id">✓ Rule 1 — Expr → Term Elist</div>
                        <div class="sel-val">Sel(1) = { (, var }</div>
                    </div>
                    <div class="sel-card ok">
                        <div class="rule-id">✓ Rule 2 — Elist → + Term Elist</div>
                        <div class="sel-val">Sel(2) = { + }</div>
                    </div>
                    <div class="sel-card ok">
                        <div class="rule-id">✓ Rule 3 — Elist → ε</div>
                        <div class="sel-val">Sel(3) = { ), N }</div>
                    </div>
                    <div class="sel-card ok">
                        <div class="rule-id">✓ Rule 4 — Term → Factor Tlist</div>
                        <div class="sel-val">Sel(4) = { (, var }</div>
                    </div>
                    <div class="sel-card ok">
                        <div class="rule-id">✓ Rule 5 — Tlist → ∗ Factor Tlist</div>
                        <div class="sel-val">Sel(5) = { ∗ }</div>
                    </div>
                    <div class="sel-card ok">
                        <div class="rule-id">✓ Rule 6 — Tlist → ε</div>
                        <div class="sel-val">Sel(6) = { +, ), N }</div>
                    </div>
                    <div class="sel-card ok">
                        <div class="rule-id">✓ Rule 7 — Factor → ( Expr )</div>
                        <div class="sel-val">Sel(7) = { ( }</div>
                    </div>
                    <div class="sel-card ok">
                        <div class="rule-id">✓ Rule 8 — Factor → var</div>
                        <div class="sel-val">Sel(8) = { var }</div>
                    </div>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">✅</span>
                    <div>
                        <strong>Grammar G16 is LL(1).</strong> All rules defining the same nonterminal
                        (rules 2 & 3; rules 5 & 6; rules 7 & 8) have <em>disjoint</em> selection sets.
                    </div>
                </div>

                <div class="note-box pro">
                    <span class="box-icon">📌</span>
                    <div>
                        <strong>Note on Step 9:</strong> Several more entries could have been listed in the
                        FB relation (e.g. <code>var FB +</code>, <code>Tlist FB Elist</code>). These are not
                        necessary because in Step 11 we only need pairs from Step 9 where the <em>left</em>
                        member is a nullable nonterminal <em>and</em> the <em>right</em> member is a terminal.
                    </div>
                </div>
            </section>





            <!-- ── Chapter nav ──────────────────────────────── -->
            <div class="chapter-nav">
                <a href="c4_4_0.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous</span>4.4 Parsing Arithmetic Expressions Top Down (Part 1)</div>
                </a>
                <a href="?complete=13" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.4 Parsing Arithmetic Expressions Top Down (Part 3)
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

        // ── 12-step stepper data ─────────────────────────────
        const steps = [{
                label: 'STEP 1',
                sub: 'Nullable Rules & Nonterminals',
                title: 'Find all nullable rules and nonterminals',
                desc: 'A rule is nullable if it can derive the empty string ε. A nonterminal is nullable if any of its rules are nullable.',
                html: `<div class="note-box tip"><span class="box-icon">✅</span><div>
                    <strong>Nullable rules:</strong> 3, 6 (Elist → ε and Tlist → ε)<br>
                    <strong>Nullable nonterminals:</strong> Elist, Tlist
                </div></div>`
            },
            {
                label: 'STEP 2',
                sub: 'BDW Relation (Begins Directly With)',
                title: 'Build the BDW relation',
                desc: 'A BDW B if there is a rule where A\'s right-hand side begins with B.',
                html: `<div class="grammar-box" style="line-height:1.9">
                    <span class="nt">Expr</span>   <span class="arr">BDW</span> <span class="nt">Term</span><br>
                    <span class="nt">Elist</span>  <span class="arr">BDW</span> <span class="t">+</span><br>
                    <span class="nt">Term</span>   <span class="arr">BDW</span> <span class="nt">Factor</span><br>
                    <span class="nt">Tlist</span>  <span class="arr">BDW</span> <span class="t">∗</span><br>
                    <span class="nt">Factor</span> <span class="arr">BDW</span> <span class="t">(</span><br>
                    <span class="nt">Factor</span> <span class="arr">BDW</span> <span class="t">var</span>
                </div>`
            },
            {
                label: 'STEP 3',
                sub: 'BW Relation (Reflexive Transitive Closure of BDW)',
                title: 'Compute BW = BDW*',
                desc: 'Apply reflexive and transitive closure to the BDW relation. For nullable nonterminals, "skip over" them when computing BDW.',
                html: `<div class="grammar-box" style="line-height:1.8; font-size:12.5px;">
                    <span class="nt">Expr</span>  <span class="arr">BW</span> <span class="nt">Term</span> &nbsp;
                    <span class="nt">Elist</span> <span class="arr">BW</span> <span class="t">+</span> &nbsp;
                    <span class="nt">Term</span>  <span class="arr">BW</span> <span class="nt">Factor</span><br>
                    <span class="nt">Tlist</span> <span class="arr">BW</span> <span class="t">∗</span> &nbsp;
                    <span class="nt">Factor</span><span class="arr">BW</span> <span class="t">(</span> &nbsp;
                    <span class="nt">Factor</span><span class="arr">BW</span> <span class="t">var</span><br>
                    <span class="nt">Expr</span>  <span class="arr">BW</span> <span class="nt">Factor</span> &nbsp; <span style="color:#94E2D5">(transitive)</span><br>
                    <span class="nt">Term</span>  <span class="arr">BW</span> <span class="t">(</span> &nbsp;
                    <span class="nt">Term</span>  <span class="arr">BW</span> <span class="t">var</span> &nbsp;
                    <span class="nt">Expr</span>  <span class="arr">BW</span> <span class="t">(</span> &nbsp;
                    <span class="nt">Expr</span>  <span class="arr">BW</span> <span class="t">var</span><br>
                    <span class="nt">Expr</span>  <span class="arr">BW</span> <span class="nt">Expr</span> &nbsp;
                    <span class="nt">Term</span>  <span class="arr">BW</span> <span class="nt">Term</span> &nbsp;
                    <span class="nt">Factor</span><span class="arr">BW</span> <span class="nt">Factor</span><br>
                    <span class="nt">Elist</span> <span class="arr">BW</span> <span class="nt">Elist</span> &nbsp;
                    <span class="nt">Tlist</span> <span class="arr">BW</span> <span class="nt">Tlist</span> &nbsp; <span style="color:#94E2D5">(reflexive)</span><br>
                    <span class="t">+</span> <span class="arr">BW</span> <span class="t">+</span> &nbsp;
                    <span class="t">∗</span> <span class="arr">BW</span> <span class="t">∗</span> &nbsp;
                    <span class="t">(</span> <span class="arr">BW</span> <span class="t">(</span> &nbsp;
                    <span class="t">var</span><span class="arr">BW</span> <span class="t">var</span> &nbsp;
                    <span class="t">)</span> <span class="arr">BW</span> <span class="t">)</span>
                </div>`
            },
            {
                label: 'STEP 4',
                sub: 'First Sets',
                title: 'Compute First sets for all nonterminals',
                desc: 'First(A) = set of terminals t such that A BW t.',
                html: `<div class="grammar-box" style="line-height:2">
                    First(<span class="nt">Expr</span>)  = { <span class="t">(</span>, <span class="t">var</span> }<br>
                    First(<span class="nt">Elist</span>) = { <span class="t">+</span> }<br>
                    First(<span class="nt">Term</span>)  = { <span class="t">(</span>, <span class="t">var</span> }<br>
                    First(<span class="nt">Tlist</span>) = { <span class="t">∗</span> }<br>
                    First(<span class="nt">Factor</span>)= { <span class="t">(</span>, <span class="t">var</span> }
                </div>`
            },
            {
                label: 'STEP 5',
                sub: 'First of Each Rule\'s RHS',
                title: 'Compute First(RHS) for each grammar rule',
                desc: 'These become selection sets for rules with no nullable left-hand sides.',
                html: `<div class="grammar-box" style="line-height:2">
                    (1) First(<span class="nt">Term Elist</span>)      = { <span class="t">(</span>, <span class="t">var</span> }<br>
                    (2) First(<span class="t">+</span> <span class="nt">Term Elist</span>)  = { <span class="t">+</span> }<br>
                    (3) First(<span class="eps">ε</span>)              = { }<br>
                    (4) First(<span class="nt">Factor Tlist</span>)    = { <span class="t">(</span>, <span class="t">var</span> }<br>
                    (5) First(<span class="t">∗</span> <span class="nt">Factor Tlist</span>) = { <span class="t">∗</span> }<br>
                    (6) First(<span class="eps">ε</span>)              = { }<br>
                    (7) First(<span class="t">(</span> <span class="nt">Expr</span> <span class="t">)</span>)  = { <span class="t">(</span> }<br>
                    (8) First(<span class="t">var</span>)           = { <span class="t">var</span> }
                </div>`
            },
            {
                label: 'STEPS 6–8',
                sub: 'FDB / DEO / EO Relations',
                title: 'Build Follow-related relations',
                desc: 'FDB: "Followed Directly By". DEO: direct end-of. EO: reflexive transitive closure of DEO. These are used to find what can follow nullable nonterminals.',
                html: `<div class="grammar-box" style="line-height:1.8; font-size:12px;">
                    <strong style="color:#94E2D5">Step 6 — FDB (Followed Directly By):</strong><br>
                    <span class="nt">Term</span>  <span class="arr">FDB</span> <span class="nt">Elist</span> &nbsp;
                    <span class="nt">Factor</span> <span class="arr">FDB</span> <span class="nt">Tlist</span> &nbsp;
                    <span class="nt">Expr</span>  <span class="arr">FDB</span> <span class="t">)</span><br><br>
                    <strong style="color:#94E2D5">Step 7 — DEO (Directly End Of):</strong><br>
                    <span class="nt">Elist</span> <span class="arr">DEO</span> <span class="nt">Expr</span> &nbsp;
                    <span class="nt">Term</span>  <span class="arr">DEO</span> <span class="nt">Expr</span> &nbsp;
                    <span class="nt">Elist</span> <span class="arr">DEO</span> <span class="nt">Elist</span> &nbsp;
                    <span class="nt">Term</span>  <span class="arr">DEO</span> <span class="nt">Elist</span><br>
                    <span class="nt">Tlist</span> <span class="arr">DEO</span> <span class="nt">Term</span> &nbsp;
                    <span class="nt">Factor</span><span class="arr">DEO</span> <span class="nt">Term</span> &nbsp;
                    <span class="nt">Tlist</span> <span class="arr">DEO</span> <span class="nt">Tlist</span> &nbsp;
                    <span class="nt">Factor</span><span class="arr">DEO</span> <span class="nt">Tlist</span><br>
                    <span class="t">)</span>     <span class="arr">DEO</span> <span class="nt">Factor</span> &nbsp;
                    <span class="t">var</span>   <span class="arr">DEO</span> <span class="nt">Factor</span><br><br>
                    <strong style="color:#94E2D5">Step 8 — EO (Reflexive Transitive Closure of DEO) — selected entries:</strong><br>
                    <span class="nt">Tlist</span> <span class="arr">EO</span> <span class="nt">Expr</span> &nbsp;
                    <span class="nt">Tlist</span> <span class="arr">EO</span> <span class="nt">Elist</span> &nbsp;
                    <span class="nt">Factor</span><span class="arr">EO</span> <span class="nt">Expr</span> &nbsp;
                    <span class="nt">Factor</span><span class="arr">EO</span> <span class="nt">Elist</span><br>
                    <span class="t">)</span>     <span class="arr">EO</span> <span class="nt">Term</span> &nbsp;
                    <span class="t">)</span>     <span class="arr">EO</span> <span class="nt">Tlist</span> &nbsp;
                    <span class="t">)</span>     <span class="arr">EO</span> <span class="nt">Expr</span> &nbsp;
                    <span class="t">)</span>     <span class="arr">EO</span> <span class="nt">Elist</span> <span style="color:#94E2D5">(transitive)</span><br>
                    <span class="t">var</span>   <span class="arr">EO</span> <span class="nt">Term</span> &nbsp;
                    <span class="t">var</span>   <span class="arr">EO</span> <span class="nt">Tlist</span> &nbsp;
                    <span class="t">var</span>   <span class="arr">EO</span> <span class="nt">Expr</span> &nbsp;
                    <span class="t">var</span>   <span class="arr">EO</span> <span class="nt">Elist</span>
                </div>`
            },
            {
                label: 'STEP 9',
                sub: 'FB Relation (Followed By)',
                title: 'Build FB = EO ∘ FDB ∘ BW (followed by a terminal)',
                desc: 'Used to find what terminals can follow nullable nonterminals. Only pairs with a nullable nonterminal on the left and a terminal on the right are needed.',
                html: `<div class="grammar-box" style="line-height:1.8; font-size:12px;">
                    <span class="nt">Tlist</span>  <span class="arr">EO</span> <span class="nt">Term</span>  <span class="arr">FDB</span> <span class="nt">Elist</span> <span class="arr">BW</span> <span class="t">+</span> &nbsp;&rarr;&nbsp; <span class="nt">Tlist</span> <span class="arr">FB</span> <span class="t">+</span><br>
                    <span class="nt">Elist</span>  <span class="arr">BW</span> <span class="t">+</span><br>
                    <span class="nt">Factor</span> <span class="arr">EO</span> <span class="nt">???</span> <span class="arr">BW</span> <span class="t">+</span> &nbsp;&rarr;&nbsp; <span class="nt">Factor</span> <span class="arr">FB</span> <span class="t">+</span><br>
                    <span class="t">)</span>     <span class="arr">EO</span> <span class="nt">Factor</span> <span class="arr">FDB</span> <span class="nt">Tlist</span> <span class="arr">BW</span> <span class="t">∗</span> &nbsp;&rarr;&nbsp; <span class="t">)</span> <span class="arr">EO</span> <span class="arr">BW</span> <span class="t">∗</span><br>
                    <span class="nt">Elist</span>  <span class="arr">EO</span> <span class="nt">Expr</span>  <span class="arr">FDB</span> <span class="t">)</span> <span class="arr">BW</span> <span class="t">)</span> &nbsp;&rarr;&nbsp; <span class="nt">Elist</span> <span class="arr">FB</span> <span class="t">)</span><br>
                    <span class="nt">Tlist</span>  <span class="arr">EO</span> <span class="nt">Expr</span> &nbsp;&rarr;&nbsp; <span class="nt">Tlist</span> <span class="arr">FB</span> <span class="t">)</span>
                </div>`
            },
            {
                label: 'STEPS 10–11',
                sub: 'FB Terminal + Follow Sets',
                title: 'Find which terminals follow the end-of-input marker, then compute Follow sets',
                desc: 'Step 10 identifies N (end-of-input) as following the start symbol. Step 11 computes Fol(X) for each nullable nonterminal X.',
                html: `<div class="grammar-box" style="line-height:2">
                    <strong style="color:#94E2D5">Step 10:</strong><br>
                    <span class="nt">Elist</span> <span class="arr">FB</span> N &nbsp;
                    <span class="nt">Term</span>  <span class="arr">FB</span> N &nbsp;
                    <span class="nt">Expr</span>  <span class="arr">FB</span> N &nbsp;
                    <span class="nt">Tlist</span> <span class="arr">FB</span> N &nbsp;
                    <span class="nt">Factor</span><span class="arr">FB</span> N<br><br>
                    <strong style="color:#94E2D5">Step 11 — Follow Sets:</strong><br>
                    Fol(<span class="nt">Elist</span>) = { <span class="t">)</span>, N }<br>
                    Fol(<span class="nt">Tlist</span>) = { <span class="t">+</span>, <span class="t">)</span>, N }
                </div>`
            },
            {
                label: 'STEP 12',
                sub: 'Final Selection Sets',
                title: 'Compute Selection Sets for all rules',
                desc: 'For non-nullable rules: Sel = First(RHS). For nullable rules: Sel = Fol(LHS). G16 is LL(1) if selection sets for rules sharing a nonterminal are disjoint.',
                html: `<div class="sel-grid">
                    <div class="sel-card ok"><div class="rule-id">✓ Rule 1</div><div class="sel-val">Sel(1) = { (, var }</div></div>
                    <div class="sel-card ok"><div class="rule-id">✓ Rule 2</div><div class="sel-val">Sel(2) = { + }</div></div>
                    <div class="sel-card ok"><div class="rule-id">✓ Rule 3</div><div class="sel-val">Sel(3) = { ), N }</div></div>
                    <div class="sel-card ok"><div class="rule-id">✓ Rule 4</div><div class="sel-val">Sel(4) = { (, var }</div></div>
                    <div class="sel-card ok"><div class="rule-id">✓ Rule 5</div><div class="sel-val">Sel(5) = { ∗ }</div></div>
                    <div class="sel-card ok"><div class="rule-id">✓ Rule 6</div><div class="sel-val">Sel(6) = { +, ), N }</div></div>
                    <div class="sel-card ok"><div class="rule-id">✓ Rule 7</div><div class="sel-val">Sel(7) = { ( }</div></div>
                    <div class="sel-card ok"><div class="rule-id">✓ Rule 8</div><div class="sel-val">Sel(8) = { var }</div></div>
                </div>
                <div class="note-box tip"><span class="box-icon">✅</span><div>
                    <strong>G16 is LL(1)!</strong> Rules 2 & 3 (Elist), rules 5 & 6 (Tlist), and rules 7 & 8 (Factor) all have disjoint selection sets.
                </div></div>`
            }
        ];

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