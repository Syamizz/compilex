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
$page     = 26;
$nextPage = "c4_10_1.php"; // adjust as needed

if (isset($_GET['complete']) && $_GET['complete'] == '26') {
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
    <title>Chapter 4.10 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c1_1.css">

    <style>
        /* ── Grammar / code boxes ───────────────────────────── */
        .grammar-box {
            background: var(--code-bg, #1A1830);
            border-radius: 12px;
            padding: 20px 24px;
            margin: 16px 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: #CDD6F4;
            line-height: 2.1;
        }
        .grammar-box .rule-num  { color: rgba(205,214,244,.4); user-select:none; margin-right:8px; }
        .grammar-box .nt        { color: #CBA6F7; }
        .grammar-box .t         { color: #A6E3A1; }
        .grammar-box .arr       { color: #94E2D5; }
        .grammar-box .eps       { color: #FAB387; }
        .grammar-box .attr      { color: #F9E2AF; font-size: 10px; vertical-align: sub; }
        .grammar-box .comp-rule { color: #89DCEB; font-size: 11.5px; margin-left: 16px; }
        .grammar-box .act       { color: #F38BA8; font-weight: 700; }
        .grammar-box .kw        { color: #CBA6F7; font-weight: 700; }
        .grammar-box .sep       { color: rgba(205,214,244,.25); margin: 0 6px; }
        .grammar-box .or        { color: #94E2D5; margin-right: 6px; }
        .grammar-box .sec-title {
            color: #89DCEB;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            margin: 14px 0 4px;
            border-top: 1px solid rgba(99,102,241,.18);
            padding-top: 10px;
        }

        /* ── Algo blocks ─────────────────────────────────────── */
        .algo-block {
            background: var(--card, #fff);
            border: 1px solid rgba(99,102,241,.15);
            border-radius: 14px;
            overflow: hidden;
            margin: 18px 0;
            box-shadow: 0 4px 18px rgba(99,102,241,.07);
        }
        .algo-block-header {
            background: var(--purple-s, #EEF2FF);
            padding: 12px 20px;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 14px;
            color: var(--purple, #6366F1);
            border-bottom: 1px solid rgba(99,102,241,.12);
        }
        .algo-block-body { padding: 18px 22px; }

        /* ── Code block (Java recursive descent) ────────────── */
        .code-block {
            background: #1A1830;
            border-radius: 12px;
            padding: 20px 24px;
            margin: 14px 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: #CDD6F4;
            line-height: 1.9;
            overflow-x: auto;
        }
        .code-block .kw   { color: #CBA6F7; }
        .code-block .ty   { color: #89DCEB; }
        .code-block .fn   { color: #FAB387; }
        .code-block .cm   { color: #6C7086; font-style: italic; }
        .code-block .str  { color: #A6E3A1; }
        .code-block .num  { color: #F38BA8; }
        .code-block .cls  { color: #94E2D5; }
        .code-block .out  { color: #F38BA8; font-weight: 600; }
        .code-block .tok  { color: #F9E2AF; }

        /* ── Method-call purpose badges ─────────────────────── */
        .method-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin: 14px 0;
        }
        @media (max-width: 680px) { .method-grid { grid-template-columns: 1fr; } }
        .method-card {
            border-radius: 10px;
            padding: 14px 16px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
        }
        .method-card.alloc {
            background: #EEF2FF;
            border: 1.5px solid rgba(99,102,241,.25);
        }
        .method-card.alloc .mc-name { color: #4338CA; font-weight: 700; font-size: 14px; margin-bottom: 6px; }
        .method-card.newlab {
            background: #F0FDF4;
            border: 1.5px solid rgba(16,185,129,.3);
        }
        .method-card.newlab .mc-name { color: #065F46; font-weight: 700; font-size: 14px; margin-bottom: 6px; }

        /* ── Iterative vs recursive note strip ──────────────── */
        .compare-strip {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin: 16px 0;
        }
        @media (max-width: 680px) { .compare-strip { grid-template-columns: 1fr; } }
        .cs-box {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(99,102,241,.15);
        }
        .cs-head {
            background: var(--purple-s, #EEF2FF);
            padding: 8px 14px;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 12px;
            color: var(--purple, #6366F1);
        }
        .cs-body {
            background: #1A1830;
            padding: 14px 16px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12.5px;
            color: #CDD6F4;
            line-height: 1.9;
        }
        .cs-body .kw  { color: #CBA6F7; }
        .cs-body .nt  { color: #CBA6F7; }
        .cs-body .arr { color: #94E2D5; }
        .cs-body .or  { color: #94E2D5; }

        /* ── Fig 4.19 two-column grammar layout ─────────────── */
        .fig-grammar-wrap {
            background: var(--code-bg, #1A1830);
            border-radius: 14px;
            padding: 22px 26px;
            margin: 16px 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12.5px;
            color: #CDD6F4;
            line-height: 2;
            box-shadow: 0 4px 24px rgba(0,0,0,.18);
            overflow-x: auto;
        }
        .fig-grammar-wrap .fg-title {
            color: #89DCEB;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid rgba(99,102,241,.18);
            padding-bottom: 6px;
            margin-bottom: 8px;
        }
        .fig-grammar-wrap .fg-rule {
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 0 10px;
            align-items: baseline;
            border-bottom: 1px solid rgba(255,255,255,.04);
            padding: 2px 0;
        }
        .fig-grammar-wrap .fg-lhs { color: #CBA6F7; white-space: nowrap; }
        .fig-grammar-wrap .fg-rhs { color: #CDD6F4; }
        .fig-grammar-wrap .fg-rhs .kw   { color: #CBA6F7; font-weight: 700; }
        .fig-grammar-wrap .fg-rhs .t    { color: #A6E3A1; }
        .fig-grammar-wrap .fg-rhs .act  { color: #F38BA8; font-weight: 700; }
        .fig-grammar-wrap .fg-rhs .comp { color: #89DCEB; font-size: 11px; }
        .fig-grammar-wrap .fg-rhs .attr { color: #F9E2AF; font-size: 10px; vertical-align: sub; }
        .fig-grammar-wrap .fg-rhs .or   { color: #94E2D5; margin: 0 4px; }
        .fig-grammar-wrap .fg-rhs .eps  { color: #FAB387; }
        .fig-grammar-wrap .fg-or {
            grid-column: 1 / -1;
            color: #94E2D5;
            padding-left: 8px;
            font-size: 12px;
        }
        .fig-grammar-wrap .fg-comp-line {
            grid-column: 2;
            color: #89DCEB;
            font-size: 11px;
            padding-left: 12px;
            line-height: 1.6;
        }
    </style>
</head>

<body>

    <?php include 'navbar_c4.php'; ?>

    <div class="note-container">

        <!-- ── Sidebar TOC ─────────────────────────────────────── -->
        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#intro"        class="toc-link">4.10 Overview</a></li>
                <li><a href="#ll-grammar"   class="toc-link">LL Grammar for Decaf</a></li>
                <li><a href="#helper-methods" class="toc-link">alloc() & newlab()</a></li>
                <li><a href="#fig419"       class="toc-link">Figure 4.19 — Full Grammar</a></li>
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
                <h1>Case Study: A Top Down Parser for Decaf</h1>
                <div class="metadata">
                    <span>⏱ 30 min read</span>
                    <span>🎯 Advanced</span>
                    <span>⚙️ Recursive Descent</span>
                </div>
            </header>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION 4.10 – Overview                         -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="intro">
                <h2><span class="sec-icon">🔍</span> Overview</h2>

                <p>
                    In this section we show how the concepts of this chapter can be applied to a compiler for
                    a subset of Java — the <strong>Decaf language</strong>. We will develop a top down parser
                    for Decaf and implement it using the technique known as <strong>recursive descent</strong>.
                    
                </p>

                <div class="note-box pro">
                    <span class="box-icon">📌</span>
                    <div>
                        Note that in the software package there are actually <strong>two parsers</strong>:
                        one is <em>top down</em> (for this chapter) and the other is <em>bottom up</em>
                        (for Chapter 5). The bottom up parser is the one designed to work with the other
                        phases to form a complete compiler. The top down parser is included only so that we
                        can have a case study for this chapter. The SableCC grammar file,
                        <code>decaf.grammar</code>, is used solely for lexical analysis at this point.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – LL Grammar for Decaf                  -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="ll-grammar">
                <h2><span class="sec-icon">📐</span> LL Grammar for Decaf</h2>

                <p>
                    In order to write the recursive descent parser, we will need to work with a grammar that
                    is <strong>LL</strong>. This grammar is relatively easy to write and is shown in Figure
                    4.19. The most difficult part is the part which defines <strong>arithmetic
                    expressions</strong> — it is taken directly from Section 4.8.
                </p>

                <p>
                    The rest of the grammar is relatively easy because Java, like C and C++, was
                    <strong>designed to be amenable to top-down parsing</strong> (in the tradition of Pascal);
                    the developer of C++ recommends recursive descent parsers (see Stroustrup 1994). To see
                    this, note that most of the constructs begin with <strong>keywords</strong>. For example,
                    when expecting a statement, the parser need examine only <strong>one token</strong> to
                    decide which kind of statement it is. The possibilities are simply:
                    <code>for</code>, <code>if</code>, <code>while</code>, <code>{</code>, or
                    <code>identifier</code>.
                </p>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        In other words, the part of the Decaf grammar defining statements is
                        <strong>simple</strong> (in the technical sense). Note that the C language permits
                        a statement to be formed by appending a semicolon to any expression; Java, however,
                        requires a simple statement to be either an <strong>assignment</strong> or a
                        <strong>method call</strong>.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – alloc() and newlab()                  -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="helper-methods">
                <h2><span class="sec-icon">🔧</span> Helper Methods: <code>alloc()</code> and <code>newlab()</code></h2>

                <p>
                    In Figure 4.19 there are two method calls used throughout the grammar:
                </p>

                <div class="method-grid">
                    <div class="method-card alloc">
                        <div class="mc-name">alloc()</div>
                        <div>Finds space for a <strong>temporary result</strong> of an expression. Returns a location (e.g. <code>T1</code>, <code>T2</code>, …) for storing an intermediate computed value.</div>
                    </div>
                    <div class="method-card newlab">
                        <div class="mc-name">newlab()</div>
                        <div>Generates a new <strong>label number</strong>, designated <code>L1</code>, <code>L2</code>, <code>L3</code>, … Used wherever a branch target needs a unique name.</div>
                    </div>
                </div>

                <div class="note-box pro">
                    <span class="box-icon">📌</span>
                    <div>
                        The control structures in Figure 4.19 are taken directly from Figure 4.18 and
                        described in Section 4.9. The convoluted logic flow in the <code>for</code> statement
                        results from the fact that the <strong>third expression needs to be evaluated after
                        the loop body is executed</strong>, and before testing for the next iteration.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Figure 4.19 Full Grammar              -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="fig419">
                <h2><span class="sec-icon">📋</span> Figure 4.19 — Attributed Translation Grammar for Decaf</h2>

                <p>
                    The complete attributed translation grammar for Decaf is shown below (continued across two
                    parts as in the textbook).
                </p>

                <!-- ── Part 1 of Figure 4.19 ── -->
                <div class="fig-grammar-wrap">
                    <div class="fg-title">Figure 4.19 — Part 1: Program Structure, Declarations, Statements</div>

                    <!-- Program -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">Program</span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="kw">class</span>
                            <span class="t">identifier</span>
                            <span class="t">{</span>
                            <span class="kw">public static void</span><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="kw">main</span>
                            <span class="t">(String [ ] identifier)</span><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="nt">CompoundStmt</span>
                            <span class="t">}</span>
                        </span>
                    </div>

                    <!-- Declaration -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">Declaration</span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="nt">Type</span>
                            <span class="nt">IdentList</span>
                            <span class="t">;</span>
                        </span>
                    </div>

                    <!-- Type -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">Type</span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="kw">int</span>
                            <span class="or">|</span>
                            <span class="kw">float</span>
                        </span>
                    </div>

                    <!-- IdentList -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">IdentList</span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="t">identifier</span>
                            <span class="t">,</span>
                            <span class="nt">IdentList</span>
                            <span class="or">|</span>
                            <span class="t">identifier</span>
                        </span>
                    </div>

                    <!-- Stmt -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">Stmt</span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="nt">AssignStmt</span>
                            <span class="or">|</span>
                            <span class="nt">ForStmt</span>
                            <span class="or">|</span>
                            <span class="nt">WhileStmt</span>
                            <span class="or">|</span>
                            <span class="nt">IfStmt</span>
                            <span class="or">|</span>
                            <span class="nt">CompoundStmt</span>
                            <span class="or">|</span>
                            <span class="nt">Declaration</span>
                            <span class="or">|</span>
                            <span class="t">;</span>
                        </span>
                    </div>

                    <!-- AssignStmt -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">AssignStmt</span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="nt">AssignExpr<sub class="attr">p</sub></span>
                            <span class="t">;</span>
                        </span>
                    </div>

                    <!-- ForStmt -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">ForStmt</span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="kw">for</span>
                            <span class="t">(</span>
                            <span class="nt">OptAssignExpr<sub class="attr">r</sub></span>
                            <span class="t">;</span>
                            <span class="act">{LBL}<sub class="attr">Lbl1</sub></span><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="nt">OptBoolExpr<sub class="attr">Lbl4</sub></span>
                            <span class="t">;</span>
                            <span class="act">{JMP}<sub class="attr">Lbl2</sub></span>
                            <span class="act">{LBL}<sub class="attr">Lbl3</sub></span><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="nt">OptAssignExpr<sub class="attr">r</sub></span>
                            <span class="t">)</span>
                            <span class="act">{JMP}<sub class="attr">Lbl1</sub></span>
                            <span class="act">{LBL}<sub class="attr">Lbl2</sub></span>
                            <span class="nt">Stmt</span><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="act">{JMP}<sub class="attr">Lbl3</sub></span>
                            <span class="act">{LBL}<sub class="attr">Lbl4</sub></span><br>
                            <span class="comp">Lbl1 = newlab()&nbsp;&nbsp;Lbl2 = newlab()&nbsp;&nbsp;Lbl3 = newlab()</span>
                        </span>
                    </div>

                    <!-- WhileStmt -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">WhileStmt</span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="kw">while</span>
                            <span class="act">{LBL}<sub class="attr">Lbl1</sub></span>
                            <span class="t">(</span>
                            <span class="nt">BoolExpr<sub class="attr">Lbl2</sub></span>
                            <span class="t">)</span><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="nt">Stmt</span>
                            <span class="act">{JMP}<sub class="attr">Lbl1</sub></span>
                            <span class="act">{LBL}<sub class="attr">Lbl2</sub></span><br>
                            <span class="comp">Lbl1 = newlab()</span>
                        </span>
                    </div>

                    <!-- IfStmt -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">IfStmt</span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="kw">if</span>
                            <span class="t">(</span>
                            <span class="nt">BoolExpr<sub class="attr">Lbl1</sub></span>
                            <span class="t">)</span>
                            <span class="nt">Stmt</span>
                            <span class="act">{JMP}<sub class="attr">Lbl2</sub></span><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="act">{LBL}<sub class="attr">Lbl1</sub></span>
                            <span class="nt">ElsePart</span>
                            <span class="act">{LBL}<sub class="attr">Lbl2</sub></span><br>
                            <span class="comp">Lbl2 = newlab()</span>
                        </span>
                    </div>

                    <!-- ElsePart -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">ElsePart</span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="kw">else</span>
                            <span class="nt">Stmt</span>
                            <span class="or">|</span>
                            <span class="eps">ε</span>
                        </span>
                    </div>

                    <!-- CompoundStmt -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">CompoundStmt</span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="t">{</span>
                            <span class="nt">StmtList</span>
                            <span class="t">}</span>
                        </span>
                    </div>

                    <!-- StmtList -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">StmtList</span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="nt">StmtList</span>
                            <span class="nt">Stmt</span>
                            <span class="or">|</span>
                            <span class="eps">ε</span>
                        </span>
                    </div>

                    <!-- OptAssignExpr -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">OptAssignExpr</span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="nt">AssignExpr<sub class="attr">p</sub></span>
                            <span class="or">|</span>
                            <span class="eps">ε</span>
                        </span>
                    </div>

                    <!-- OptBoolExpr -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">OptBoolExpr<sub class="attr">Lbl1</sub></span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="nt">BoolExpr<sub class="attr">Lbl1</sub></span>
                            <span class="or">|</span>
                            <span class="eps">ε</span>
                        </span>
                    </div>

                    <!-- BoolExpr -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">BoolExpr<sub class="attr">Lbl1</sub></span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="nt">Expr<sub class="attr">p</sub></span>
                            <span class="t">compare<sub class="attr">c</sub></span>
                            <span class="nt">Expr<sub class="attr">q</sub></span>
                            <span class="act">{TST}<sub class="attr">p,q,,7-c,Lbl1</sub></span><br>
                            <span class="comp">Lbl1 = newlab()</span>
                        </span>
                    </div>

                    <!-- Expr -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">Expr<sub class="attr">p</sub></span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="nt">AssignExpr<sub class="attr">p</sub></span>
                            <span class="or">|</span>
                            <span class="nt">Rvalue<sub class="attr">q</sub></span>
                            <span class="nt">Elist<sub class="attr">q,p</sub></span>
                        </span>
                    </div>

                    <!-- AssignExpr -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">AssignExpr<sub class="attr">p</sub></span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="t">identifier<sub class="attr">p</sub></span>
                            <span class="t">=</span>
                            <span class="nt">Expr<sub class="attr">q</sub></span>
                            <span class="act">{MOV}<sub class="attr">q,,p</sub></span>
                        </span>
                    </div>

                    <!-- Rvalue -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">Rvalue<sub class="attr">p</sub></span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="nt">Term<sub class="attr">q</sub></span>
                            <span class="nt">Elist<sub class="attr">p,q</sub></span>
                        </span>
                    </div>
                </div>

                <!-- ── Part 2 of Figure 4.19 ── -->
                <div class="fig-grammar-wrap" style="margin-top:4px;">
                    <div class="fg-title">Figure 4.19 — Part 2: Expressions (continued)</div>

                    <!-- Elist -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">Elist<sub class="attr">p,q</sub></span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="t">+</span>
                            <span class="nt">Term<sub class="attr">r</sub></span>
                            <span class="act">{ADD}<sub class="attr">p,r,s</sub></span>
                            <span class="nt">Elist<sub class="attr">s,q</sub></span>
                            <span class="comp">s = alloc()</span>
                        </span>
                    </div>
                    <div class="fg-rule">
                        <span class="fg-lhs"></span>
                        <span class="fg-rhs">
                            <span class="or">|</span>
                            <span class="t">-</span>
                            <span class="nt">Term<sub class="attr">r</sub></span>
                            <span class="act">{SUB}<sub class="attr">p,r,s</sub></span>
                            <span class="nt">Elist<sub class="attr">s,q</sub></span>
                            <span class="comp">s = alloc()</span>
                        </span>
                    </div>
                    <div class="fg-rule">
                        <span class="fg-lhs"></span>
                        <span class="fg-rhs">
                            <span class="or">|</span>
                            <span class="eps">ε</span>
                            <span class="comp">q = p</span>
                        </span>
                    </div>

                    <!-- Term -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">Term<sub class="attr">p</sub></span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="nt">Factor<sub class="attr">q</sub></span>
                            <span class="nt">Tlist<sub class="attr">q,p</sub></span>
                        </span>
                    </div>

                    <!-- Tlist -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">Tlist<sub class="attr">p,q</sub></span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="t">*</span>
                            <span class="nt">Factor<sub class="attr">r</sub></span>
                            <span class="act">{MUL}<sub class="attr">p,r,s</sub></span>
                            <span class="nt">Tlist<sub class="attr">s,q</sub></span>
                            <span class="comp">s = alloc()</span>
                        </span>
                    </div>
                    <div class="fg-rule">
                        <span class="fg-lhs"></span>
                        <span class="fg-rhs">
                            <span class="or">|</span>
                            <span class="t">/</span>
                            <span class="nt">Factor<sub class="attr">r</sub></span>
                            <span class="act">{DIV}<sub class="attr">p,r,s</sub></span>
                            <span class="nt">Tlist<sub class="attr">s,q</sub></span>
                            <span class="comp">s = alloc()</span>
                        </span>
                    </div>
                    <div class="fg-rule">
                        <span class="fg-lhs"></span>
                        <span class="fg-rhs">
                            <span class="or">|</span>
                            <span class="eps">ε</span>
                            <span class="comp">q = p</span>
                        </span>
                    </div>

                    <!-- Factor -->
                    <div class="fg-rule">
                        <span class="fg-lhs"><span class="nt">Factor<sub class="attr">p</sub></span></span>
                        <span class="fg-rhs">
                            <span class="arr">→</span>
                            <span class="t">(</span>
                            <span class="nt">Expr<sub class="attr">p</sub></span>
                            <span class="t">)</span>
                        </span>
                    </div>
                    <div class="fg-rule">
                        <span class="fg-lhs"></span>
                        <span class="fg-rhs">
                            <span class="or">|</span>
                            <span class="t">+</span>
                            <span class="nt">Factor<sub class="attr">p</sub></span>
                        </span>
                    </div>
                    <div class="fg-rule">
                        <span class="fg-lhs"></span>
                        <span class="fg-rhs">
                            <span class="or">|</span>
                            <span class="t">-</span>
                            <span class="nt">Factor<sub class="attr">q</sub></span>
                            <span class="act">{Neg}<sub class="attr">q,,p</sub></span>
                            <span class="comp">p = alloc()</span>
                        </span>
                    </div>
                    <div class="fg-rule">
                        <span class="fg-lhs"></span>
                        <span class="fg-rhs">
                            <span class="or">|</span>
                            <span class="t">num<sub class="attr">p</sub></span>
                        </span>
                    </div>
                    <div class="fg-rule">
                        <span class="fg-lhs"></span>
                        <span class="fg-rhs">
                            <span class="or">|</span>
                            <span class="t">identifier<sub class="attr">p</sub></span>
                        </span>
                    </div>
                </div>
            </section>

      
  

          

            <!-- ── Chapter nav ──────────────────────────────── -->
            <div class="chapter-nav">
                <a href="c4_9_1.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous</span>4.9 Translating Control Structures (Part 2)</div>
                </a>
                <a href="?complete=26" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.10 Case Study: A Top Down Parser for Decaf (Part 2)
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
        const bar      = document.getElementById('toc-bar');
        const pctLabel = document.getElementById('pct-label');

        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            const total    = document.body.scrollHeight - window.innerHeight;
            const pct      = Math.round((scrolled / total) * 100);
            bar.style.width      = pct + '%';
            pctLabel.textContent = pct + '%';
            let current = '';
            sections.forEach(s => { if (scrolled >= s.offsetTop - 120) current = s.id; });
            tocLinks.forEach(l => l.classList.toggle('active', l.getAttribute('href') === '#' + current));
        });
    </script>

</body>
</html>