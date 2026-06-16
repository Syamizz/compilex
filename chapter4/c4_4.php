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
$page     = 4;
$nextPage = "c4_5.php"; // adjust as needed

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
                <li><a href="#g16" class="toc-link">Grammar G16</a></li>
                <li><a href="#g16-algo" class="toc-link">G16 – 12-Step Algorithm</a></li>
                <li><a href="#g16-sel" class="toc-link">G16 Selection Sets</a></li>
                <li><a href="#sample" class="toc-link">Sample Problem 4.4</a></li>
                <li><a href="#pdm" class="toc-link">Pushdown Machine</a></li>
                <li><a href="#recdesc" class="toc-link">Recursive Descent Parser</a></li>
                <li><a href="#exercises" class="toc-link">Exercises 4.4</a></li>
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

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Sample Problem 4.4                    -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="sample">
                <h2><span class="sec-icon">🧪</span> Sample Problem 4.4</h2>

                <div class="note-box key">
                    <span class="box-icon">📖</span>
                    <div>
                        <strong>Problem:</strong> Show a pushdown machine and a recursive descent translator
                        for arithmetic expressions involving addition and multiplication using grammar G16.
                    </div>
                </div>

                <p>
                    <strong>Solution:</strong> To build the pushdown machine we make use of the selection sets shown above.
                    These tell us which columns of the machine are to be filled in for each row. For example, since
                    the selection set for rule 4 is <code>{(, var}</code>, we fill the cells in the row labeled
                    <code>Term</code> and columns labeled <code>(</code> and <code>var</code> with information from
                    rule 4: <code>Rep(Tlist Factor)</code>.
                </p>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Pushdown Machine                      -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="pdm">
                <h2><span class="sec-icon">🖥️</span> Pushdown Machine for G16</h2>

                <div class="pdm-wrap">
                    <table class="pdm-table">
                        <thead>
                            <tr>
                                <th>Stack Top</th>
                                <th>+</th>
                                <th>∗</th>
                                <th>(</th>
                                <th>)</th>
                                <th>var</th>
                                <th>N</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="row-hdr">Expr</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="action">Rep(Elist Term)<br>Retain</td>
                                <td class="reject">Reject</td>
                                <td class="action">Rep(Elist Term)<br>Retain</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-hdr">Elist</td>
                                <td class="action">Rep(Elist Term +)<br>Retain</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="action">Pop<br>Retain</td>
                                <td class="reject">Reject</td>
                                <td class="action">Pop<br>Retain</td>
                            </tr>
                            <tr>
                                <td class="row-hdr">Term</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="action">Rep(Tlist Factor)<br>Retain</td>
                                <td class="reject">Reject</td>
                                <td class="action">Rep(Tlist Factor)<br>Retain</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-hdr">Tlist</td>
                                <td class="action">Pop<br>Retain</td>
                                <td class="action">Rep(Tlist Factor ∗)<br>Retain</td>
                                <td class="reject">Reject</td>
                                <td class="action">Pop<br>Retain</td>
                                <td class="reject">Reject</td>
                                <td class="action">Pop<br>Retain</td>
                            </tr>
                            <tr>
                                <td class="row-hdr">Factor</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="action">Rep( ) Expr ( )<br>Retain</td>
                                <td class="reject">Reject</td>
                                <td class="action">Rep(var)<br>Retain</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-hdr">+</td>
                                <td class="action">Pop<br>Advance</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-hdr">∗</td>
                                <td class="reject">Reject</td>
                                <td class="action">Pop<br>Advance</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-hdr">(</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="action">Pop<br>Advance</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-hdr">)</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="action">Pop<br>Advance</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-hdr">var</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="action">Pop<br>Advance</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-hdr" style="color:#065F46;">, (Initial Stack)</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="accept">Accept</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        The initial stack contains <code>Expr , </code> (the start symbol and a bottom-of-stack
                        marker). The machine accepts when the input is exhausted (<code>N</code>) and the stack
                        contains only <code>,</code>.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Recursive Descent Parser              -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="recdesc">
                <h2><span class="sec-icon">💻</span> Recursive Descent Parser</h2>

                <p>
                    We make use of the selection sets again in the recursive descent parser. In each procedure,
                    the input symbols in the selection set tell us which rule of the grammar to apply.
                    Assume that a <code>var</code> is represented by the integer <code>256</code>.
                </p>

                <div class="code-block">
                    <span class="ty">int</span> inp; <span class="kw">const</span> <span class="ty">int</span> <span class="num">var</span> = <span class="num">256</span>;

                    <span class="ty">void</span> <span class="fn">Expr</span> ()
                    { <span class="kw">if</span> (inp==<span class="str">'('</span> || inp==var) <span class="cm">// apply rule 1</span>
                    { <span class="fn">Term</span> ();
                    <span class="fn">Elist</span> ();
                    } <span class="cm">// end rule 1</span>
                    <span class="kw">else</span> <span class="fn">reject</span>();
                    }
                </div>

                <div class="code-block">
                    <span class="ty">void</span> <span class="fn">Elist</span> ()
                    { <span class="kw">if</span> (inp==<span class="str">'+'</span>) <span class="cm">// apply rule 2</span>
                    { <span class="fn">getInp</span>();
                    <span class="fn">Term</span> ();
                    <span class="fn">Elist</span> ();
                    } <span class="cm">// end rule 2</span>
                    <span class="kw">else if</span> (inp==<span class="str">')'</span> || inp==<span class="str">'N'</span>) ; <span class="cm">// apply rule 3</span>
                    <span class="kw">else</span> <span class="fn">reject</span> ();
                    }
                </div>

                <div class="code-block">
                    <span class="ty">void</span> <span class="fn">Term</span> ()
                    { <span class="kw">if</span> (inp==<span class="str">'('</span> || inp==var) <span class="cm">// apply rule 4</span>
                    { <span class="fn">Factor</span> ();
                    <span class="fn">Tlist</span> ();
                    } <span class="cm">// end rule 4</span>
                    <span class="kw">else</span> <span class="fn">reject</span>();
                    }
                </div>

                <div class="code-block">
                    <span class="ty">void</span> <span class="fn">Tlist</span> ()
                    { <span class="kw">if</span> (inp==<span class="str">'*'</span>) <span class="cm">// apply rule 5</span>
                    { <span class="fn">getInp</span>();
                    <span class="fn">Factor</span> ();
                    <span class="fn">Tlist</span> ();
                    } <span class="cm">// end rule 5</span>
                    <span class="kw">else if</span> (inp==<span class="str">'+'</span> || inp==<span class="str">')'</span> || inp==<span class="str">'N'</span>)
                    ; <span class="cm">// apply rule 6</span>
                    <span class="kw">else</span> <span class="fn">reject</span>();
                    }
                </div>

                <div class="code-block">
                    <span class="ty">void</span> <span class="fn">Factor</span> ()
                    { <span class="kw">if</span> (inp==<span class="str">'('</span>) <span class="cm">// apply rule 7</span>
                    { <span class="fn">getInp</span>();
                    <span class="fn">Expr</span> ();
                    <span class="kw">if</span> (inp==<span class="str">')'</span>) <span class="fn">getInp</span>();
                    <span class="kw">else</span> <span class="fn">reject</span>();
                    } <span class="cm">// end rule 7</span>
                    <span class="kw">else if</span> (inp==var) <span class="fn">getInp</span>(); <span class="cm">// apply rule 8</span>
                    <span class="kw">else</span> <span class="fn">reject</span>();
                    }
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Null statements:</strong> There are two null statements (<code>;</code>) in this
                        parser — one in <code>Elist()</code> representing rule 3 (<code>Elist → ε</code>), and
                        one in <code>Tlist()</code> representing rule 6 (<code>Tlist → ε</code>).
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Exercises 4.4                         -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="exercises">
                <h2><span class="sec-icon">📝</span> Exercises 4.4</h2>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 1 — Derivation Trees</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">Show derivation trees for each of the following input strings, using grammar G16.</p>
                        <div class="grammar-box" style="line-height:2.1; font-size:13px;">
                            (a) <span class="t">var + var</span><br>
                            (b) <span class="t">var + var ∗ var</span><br>
                            (c) <span class="t">(var + var) ∗ var</span><br>
                            (d) <span class="t">((var))</span><br>
                            (e) <span class="t">var ∗ var ∗ var</span>
                        </div>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 2 — G5 vs G16</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            We have shown that grammar G16 for simple arithmetic expressions is LL(1), but grammar G5
                            is not LL(1). What are the advantages, if any, of grammar G5 over grammar G16?
                        </p>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 3 — Is G5 LL(n)?</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            Suppose we permitted our parser to "peek ahead" <code>n</code> characters in the input
                            stream to determine which rule to apply. Would we then be able to use grammar G5 to parse
                            simple arithmetic expressions top down? In other words, is grammar G5 LL(n)?
                        </p>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 4 — Null Statements</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            Find two null statements in the recursive descent parser of the sample problem in this
                            section. Which methods are they in and which grammar rules do they represent?
                        </p>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 5 — Recursive Descent for Stmt</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">Construct part of a recursive descent parser for the following portion of a programming language:</p>
                        <div class="grammar-box" style="line-height:2; font-size:13px;">
                            <span class="rule-num">1.</span> <span class="nt">Stmt</span> <span class="arr">→</span> <span class="kw" style="color:#FAB387">if</span> <span class="t">(</span><span class="nt">Expr</span><span class="t">)</span> <span class="nt">Stmt</span><br>
                            <span class="rule-num">2.</span> <span class="nt">Stmt</span> <span class="arr">→</span> <span class="kw" style="color:#FAB387">while</span> <span class="t">(</span><span class="nt">Expr</span><span class="t">)</span> <span class="nt">Stmt</span><br>
                            <span class="rule-num">3.</span> <span class="nt">Stmt</span> <span class="arr">→</span> <span class="t">{ </span><span class="nt">StmtList</span><span class="t"> }</span><br>
                            <span class="rule-num">4.</span> <span class="nt">Stmt</span> <span class="arr">→</span> <span class="nt">Expr</span> <span class="t">;</span>
                        </div>
                        <p style="font-size:13.5px; margin-top:10px;">
                            Write the procedure for the nonterminal <code>Stmt</code>. Assume the selection set
                            for rule 4 is <code>{(, identifier, number}</code>.
                        </p>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 6 — LL(1) Grammar for Regular Expressions</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            Show an LL(1) grammar for the language of regular expressions over the alphabet
                            <code>{0, 1}</code>, and show a recursive descent parser corresponding to the grammar.
                        </p>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 7 — Eliminate Left Recursion</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">Show how to eliminate the left recursion from each of the grammars shown below:</p>
                        <div class="grammar-box" style="line-height:2; font-size:13px;">
                            (a)<br>
                            <span class="rule-num">1.</span> <span class="nt">A</span> <span class="arr">→</span> <span class="nt">A</span> <span class="t">b c</span><br>
                            <span class="rule-num">2.</span> <span class="nt">A</span> <span class="arr">→</span> <span class="t">a b</span><br><br>
                            (b)<br>
                            <span class="rule-num">1.</span> <span class="nt">ParmList</span> <span class="arr">→</span> <span class="nt">ParmList</span> <span class="t">,</span> <span class="nt">Parm</span><br>
                            <span class="rule-num">2.</span> <span class="nt">ParmList</span> <span class="arr">→</span> <span class="nt">Parm</span>
                        </div>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 8 — LL(1) Grammar for a Parameter List</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            A parameter list is a list of 0 or more parameters separated by commas; a parameter
                            list neither begins nor ends with a comma. Show an LL(1) grammar for a parameter list.
                            Assume that <code>parameter</code> has already been defined.
                        </p>
                    </div>
                </div>
            </section>

            <!-- ── Chapter nav ──────────────────────────────── -->
            <div class="chapter-nav">
                <a href="c4_3.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous</span>4.3 LL(1) Parsers</div>
                </a>
                <a href="?complete=1" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.5 Next Topic
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