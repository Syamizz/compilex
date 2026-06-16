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
$page     = 5;
$nextPage = "c4_6.php"; // adjust as needed

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
    <title>Chapter 4.5 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c1_1.css">

    <style>
        /* ── Grammar / code box ─────────────────────────────── */
        .grammar-box {
            background: var(--code-bg, #1A1830);
            border-radius: 12px;
            padding: 20px 24px;
            margin: 16px 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13.5px;
            color: #CDD6F4;
            line-height: 2;
        }

        .grammar-box .rule-num {
            color: rgba(205, 214, 244, .45);
            user-select: none;
            margin-right: 10px;
        }

        .grammar-box .nt {
            color: #CBA6F7;
        }

        .grammar-box .t {
            color: #A6E3A1;
        }

        .grammar-box .arr {
            color: #94E2D5;
        }

        .grammar-box .eps {
            color: #FAB387;
        }

        .grammar-box .act {
            color: #F38BA8;
            font-weight: 700;
        }

        /* action symbols {…} */

        /* ── Steps / algo blocks ────────────────────────────── */
        .algo-block {
            background: var(--card, #fff);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: 14px;
            overflow: hidden;
            margin: 18px 0;
            box-shadow: 0 4px 18px rgba(99, 102, 241, .07);
        }

        .algo-block-header {
            background: var(--purple-s, #EEF2FF);
            padding: 12px 20px;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 14px;
            color: var(--purple, #6366F1);
            border-bottom: 1px solid rgba(99, 102, 241, .12);
        }

        .algo-block-body {
            padding: 18px 22px;
        }

        /* ── Code block (recursive descent) ─────────────────── */
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

        .code-block .kw {
            color: #CBA6F7;
        }

        .code-block .ty {
            color: #89DCEB;
        }

        .code-block .fn {
            color: #FAB387;
        }

        .code-block .cm {
            color: #6C7086;
            font-style: italic;
        }

        .code-block .str {
            color: #A6E3A1;
        }

        .code-block .num {
            color: #F38BA8;
        }

        .code-block .out {
            color: #F38BA8;
            font-weight: 600;
        }

        /* output / print calls */

        /* ── Pushdown machine table ──────────────────────────── */
        .pdm-wrap {
            overflow-x: auto;
            margin: 18px 0;
            border-radius: 12px;
            border: 1px solid rgba(99, 102, 241, .15);
            box-shadow: 0 4px 18px rgba(99, 102, 241, .07);
        }

        .pdm-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11.5px;
            min-width: 760px;
        }

        .pdm-table th {
            background: var(--purple-s, #EEF2FF);
            color: var(--purple, #6366F1);
            padding: 9px 10px;
            text-align: center;
            font-weight: 700;
            font-size: 11.5px;
            border: 1px solid rgba(99, 102, 241, .15);
        }

        .pdm-table td {
            padding: 6px 10px;
            border: 1px solid rgba(99, 102, 241, .1);
            text-align: center;
            color: var(--text, #1E1B4B);
            font-size: 11px;
            vertical-align: middle;
            line-height: 1.5;
        }

        .pdm-table td.reject {
            color: #DC2626;
            font-weight: 600;
        }

        .pdm-table td.action {
            color: #6366F1;
            font-weight: 600;
            font-size: 10.5px;
        }

        .pdm-table td.accept {
            background: #F0FDF4;
            color: #065F46;
            font-weight: 700;
        }

        .pdm-table td.out-act {
            color: #B45309;
            font-weight: 600;
            font-size: 10.5px;
            background: #FFFBEB;
        }

        .pdm-table tr:nth-child(even) td {
            background: rgba(99, 102, 241, .025);
        }

        .pdm-table td.row-hdr {
            background: var(--purple-s, #EEF2FF);
            color: var(--purple, #6366F1);
            font-weight: 700;
            text-align: left;
            padding-left: 12px;
            white-space: nowrap;
        }

        .pdm-table td.row-hdr.act-row {
            background: #FFF7ED;
            color: #B45309;
        }

        /* ── Tree SVG wrapper ─────────────────────────────────── */
        .tree-wrap {
            background: var(--card, #fff);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: 14px;
            padding: 20px;
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .tree-wrap figcaption {
            font-size: 12px;
            color: var(--muted, #6B7280);
            margin-top: 10px;
            text-align: center;
        }

        /* ── Derived string display ───────────────────────────── */
        .derived-string {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px;
            margin: 14px 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
        }

        .ds-token {
            padding: 5px 10px;
            border-radius: 8px;
            font-weight: 600;
        }

        .ds-token.input {
            background: #EEF2FF;
            color: #4338CA;
        }

        .ds-token.action {
            background: #FFF7ED;
            color: #B45309;
            border: 1.5px solid rgba(180, 83, 9, .25);
        }

        /* ── Selection set cards ─────────────────────────────── */
        .sel-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            margin: 14px 0;
        }

        .sel-card {
            background: var(--purple-s, #EEF2FF);
            border: 1.5px solid rgba(99, 102, 241, .2);
            border-radius: 10px;
            padding: 10px 14px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
        }

        .sel-card .rule-id {
            font-weight: 700;
            color: var(--purple, #6366F1);
            margin-bottom: 4px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .8px;
        }

        .sel-card .sel-val {
            color: var(--text, #1E1B4B);
            font-size: 13.5px;
        }

        .sel-card.ok {
            border-color: rgba(16, 185, 129, .35);
            background: #F0FDF4;
        }

        .sel-card.ok .rule-id {
            color: #065F46;
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
                <li><a href="#intro" class="toc-link">4.5 Introduction</a></li>
                <li><a href="#g17" class="toc-link">Grammar G17</a></li>
                <li><a href="#deriv-tree" class="toc-link">Derivation Tree (Fig 4.13)</a></li>
                <li><a href="#pushdown" class="toc-link">4.5.1 Pushdown Translators</a></li>
                <li><a href="#pdm-table" class="toc-link">Fig 4.14 – PDM Table</a></li>
                <li><a href="#recdesc" class="toc-link">4.5.2 Recursive Descent</a></li>
                <li><a href="#g18" class="toc-link">Grammar G18 Example</a></li>
                <li><a href="#g17-recdesc" class="toc-link">G17 Recursive Descent</a></li>
                <li><a href="#sample" class="toc-link">Sample Problem 4.5</a></li>
                <li><a href="#exercises" class="toc-link">Exercises 4.5</a></li>
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
                <h1>Syntax-Directed Translation</h1>
                <div class="metadata">
                    <span>⏱ 25 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>⚙️ Translation Grammars</span>
                </div>
            </header>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION 4.5 – Introduction                      -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="intro">
                <h2><span class="sec-icon">🔍</span> Introduction</h2>

                <p>
                    Thus far we have explored top down parsing in a way that has been exclusively concerned with
                    <strong>syntax</strong>. The programs we have developed can check only for syntax errors; they
                    cannot produce output, and they do not deal at all with <strong>semantics</strong> — the intent
                    or meaning of the source program.
                </p>
                <p>
                    For this purpose, we now introduce <strong>action symbols</strong> which give us the capability
                    of producing output and/or calling other methods while parsing an input string. A grammar
                    containing action symbols is called a <strong>translation grammar</strong>. Action symbols are
                    designated by enclosing them in curly braces <code>{}</code>. The meaning of the action symbol,
                    by default, is to produce output — the action symbol itself.
                </p>

                <div class="note-box key">
                    <span class="box-icon">📖</span>
                    <div>
                        <strong>Definition — Translation Grammar:</strong> A grammar that contains
                        <strong>action symbols</strong> enclosed in curly braces <code>{ }</code>.
                        To find the selection sets in a translation grammar, simply
                        <strong>remove all the action symbols</strong> — this results in the
                        <strong>underlying grammar</strong>. Note that a rule of the form
                        <code>A → {action}</code> is an <em>epsilon rule</em> in the underlying grammar.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Grammar G17                           -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="g17">
                <h2><span class="sec-icon">📐</span> Grammar G17 — Infix to Postfix</h2>

                <p>
                    An example of a translation grammar to translate infix expressions involving addition and
                    multiplication to <strong>postfix form</strong> is shown below.
                </p>

                <div class="grammar-box">
                    <span class="rule-num">G17:</span><br>
                    <span class="rule-num">1.</span> <span class="nt">Expr</span> <span class="arr">→</span> <span class="nt">Term</span> <span class="nt">Elist</span><br>
                    <span class="rule-num">2.</span> <span class="nt">Elist</span> <span class="arr">→</span> <span class="t">+</span> <span class="nt">Term</span> <span class="act">{+}</span> <span class="nt">Elist</span><br>
                    <span class="rule-num">3.</span> <span class="nt">Elist</span> <span class="arr">→</span> <span class="eps">ε</span><br>
                    <span class="rule-num">4.</span> <span class="nt">Term</span> <span class="arr">→</span> <span class="nt">Factor</span> <span class="nt">Tlist</span><br>
                    <span class="rule-num">5.</span> <span class="nt">Tlist</span> <span class="arr">→</span> <span class="t">∗</span> <span class="nt">Factor</span> <span class="act">{∗}</span> <span class="nt">Tlist</span><br>
                    <span class="rule-num">6.</span> <span class="nt">Tlist</span> <span class="arr">→</span> <span class="eps">ε</span><br>
                    <span class="rule-num">7.</span> <span class="nt">Factor</span> <span class="arr">→</span> <span class="t">(</span> <span class="nt">Expr</span> <span class="t">)</span><br>
                    <span class="rule-num">8.</span> <span class="nt">Factor</span> <span class="arr">→</span> <span class="t">var</span> <span class="act">{var}</span>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        The <strong>underlying grammar</strong> of G17 is grammar <strong>G16</strong> from
                        Section 4.4 — simply remove all action symbols <span style="color:#B45309">{+}</span>,
                        <span style="color:#B45309">{∗}</span>, <span style="color:#B45309">{var}</span>
                        and the grammar is identical to G16.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Derivation Tree Fig 4.13              -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="deriv-tree">
                <h2><span class="sec-icon">🌳</span> Derivation Tree for <code>var + var ∗ var</code> (Figure 4.13)</h2>

                <p>
                    A derivation tree for the expression <code>var + var ∗ var</code> is shown below using
                    grammar G17. Note that the action symbols appear as leaves in the tree alongside the terminals.
                </p>

                <div class="tree-wrap">
                    <svg viewBox="0 0 620 390" width="580" xmlns="http://www.w3.org/2000/svg"
                        font-family="JetBrains Mono, monospace" font-size="12">

                        <!-- ── edges ── -->
                        <!-- Expr → Term Elist -->
                        <line x1="310" y1="28" x2="170" y2="75" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="310" y1="28" x2="430" y2="75" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Term → Factor Tlist -->
                        <line x1="170" y1="90" x2="100" y2="138" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="170" y1="90" x2="210" y2="138" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Factor → var {var} -->
                        <line x1="100" y1="153" x2="75" y2="200" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="100" y1="153" x2="120" y2="200" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Tlist → ε -->
                        <line x1="210" y1="153" x2="210" y2="200" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Elist → + Term {+} Elist -->
                        <line x1="430" y1="90" x2="320" y2="138" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="430" y1="90" x2="420" y2="138" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="430" y1="90" x2="510" y2="138" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="430" y1="90" x2="570" y2="138" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Term → Factor Tlist (right branch) -->
                        <line x1="420" y1="153" x2="380" y2="200" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="420" y1="153" x2="460" y2="200" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Factor → var {var} (right) -->
                        <line x1="380" y1="215" x2="355" y2="262" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="380" y1="215" x2="400" y2="262" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Tlist → * Factor {*} Tlist -->
                        <line x1="460" y1="215" x2="430" y2="262" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="460" y1="215" x2="465" y2="262" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="460" y1="215" x2="510" y2="262" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="460" y1="215" x2="555" y2="262" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Factor deep → var {var} -->
                        <line x1="465" y1="278" x2="448" y2="322" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="465" y1="278" x2="480" y2="322" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Tlist deep → ε -->
                        <line x1="555" y1="278" x2="555" y2="322" stroke="#6366F1" stroke-width="1.5" />

                        <!-- Elist right → ε -->
                        <line x1="570" y1="153" x2="570" y2="200" stroke="#6366F1" stroke-width="1.5" />

                        <!-- ── nodes ── -->
                        <circle cx="310" cy="20" r="16" fill="#6366F1" />
                        <text x="310" y="25" text-anchor="middle" fill="white" font-weight="700">Expr</text>

                        <circle cx="170" cy="82" r="16" fill="#6366F1" />
                        <text x="170" y="87" text-anchor="middle" fill="white" font-weight="700">Term</text>

                        <circle cx="430" cy="82" r="16" fill="#CBA6F7" opacity=".9" />
                        <text x="430" y="87" text-anchor="middle" fill="#3730A3" font-weight="700">Elist</text>

                        <!-- Factor left -->
                        <circle cx="100" cy="145" r="17" fill="#CBA6F7" opacity=".85" />
                        <text x="100" y="150" text-anchor="middle" fill="#3730A3" font-weight="700">Factor</text>

                        <!-- Tlist left -->
                        <circle cx="210" cy="145" r="16" fill="#CBA6F7" opacity=".85" />
                        <text x="210" y="150" text-anchor="middle" fill="#3730A3" font-weight="700">Tlist</text>

                        <!-- var left -->
                        <circle cx="75" cy="212" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="75" y="217" text-anchor="middle" fill="#065F46" font-weight="600">var</text>

                        <!-- {var} left -->
                        <rect x="104" y="198" width="34" height="20" rx="5" fill="#FFF7ED" stroke="#F97316" stroke-width="1.2" />
                        <text x="121" y="212" text-anchor="middle" fill="#B45309" font-weight="700" font-size="10">{var}</text>

                        <!-- ε Tlist left -->
                        <text x="210" y="215" text-anchor="middle" fill="#FAB387" font-weight="600" font-size="14">ε</text>

                        <!-- + -->
                        <circle cx="320" cy="145" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="320" y="150" text-anchor="middle" fill="#065F46" font-weight="600">+</text>

                        <!-- Term right -->
                        <circle cx="420" cy="145" r="16" fill="#6366F1" />
                        <text x="420" y="150" text-anchor="middle" fill="white" font-weight="700">Term</text>

                        <!-- {+} -->
                        <rect x="493" y="131" width="34" height="20" rx="5" fill="#FFF7ED" stroke="#F97316" stroke-width="1.2" />
                        <text x="510" y="145" text-anchor="middle" fill="#B45309" font-weight="700" font-size="10">{+}</text>

                        <!-- Elist right end -->
                        <circle cx="570" cy="145" r="16" fill="#CBA6F7" opacity=".85" />
                        <text x="570" y="150" text-anchor="middle" fill="#3730A3" font-weight="700">Elist</text>

                        <!-- ε Elist right -->
                        <text x="570" y="215" text-anchor="middle" fill="#FAB387" font-weight="600" font-size="14">ε</text>

                        <!-- Factor right -->
                        <circle cx="380" cy="207" r="17" fill="#CBA6F7" opacity=".85" />
                        <text x="380" y="212" text-anchor="middle" fill="#3730A3" font-weight="700">Factor</text>

                        <!-- Tlist right -->
                        <circle cx="460" cy="207" r="16" fill="#CBA6F7" opacity=".85" />
                        <text x="460" y="212" text-anchor="middle" fill="#3730A3" font-weight="700">Tlist</text>

                        <!-- var right -->
                        <circle cx="355" cy="274" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="355" y="279" text-anchor="middle" fill="#065F46" font-weight="600">var</text>

                        <!-- {var} right -->
                        <rect x="384" y="252" width="34" height="20" rx="5" fill="#FFF7ED" stroke="#F97316" stroke-width="1.2" />
                        <text x="401" y="266" text-anchor="middle" fill="#B45309" font-weight="700" font-size="10">{var}</text>

                        <!-- * -->
                        <circle cx="430" cy="274" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="430" y="279" text-anchor="middle" fill="#065F46" font-weight="600">∗</text>

                        <!-- Factor deep -->
                        <circle cx="465" cy="270" r="17" fill="#CBA6F7" opacity=".85" />
                        <text x="465" y="275" text-anchor="middle" fill="#3730A3" font-weight="700">Factor</text>

                        <!-- {*} -->
                        <rect x="538" y="252" width="34" height="20" rx="5" fill="#FFF7ED" stroke="#F97316" stroke-width="1.2" />
                        <text x="555" y="266" text-anchor="middle" fill="#B45309" font-weight="700" font-size="10">{∗}</text>

                        <!-- Tlist deep -->
                        <circle cx="555" cy="270" r="16" fill="#CBA6F7" opacity=".85" />
                        <text x="555" y="275" text-anchor="middle" fill="#3730A3" font-weight="700">Tlist</text>

                        <!-- var deep -->
                        <circle cx="448" cy="334" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="448" y="339" text-anchor="middle" fill="#065F46" font-weight="600">var</text>

                        <!-- {var} deep -->
                        <rect x="464" y="310" width="34" height="20" rx="5" fill="#FFF7ED" stroke="#F97316" stroke-width="1.2" />
                        <text x="481" y="324" text-anchor="middle" fill="#B45309" font-weight="700" font-size="10">{var}</text>

                        <!-- ε Tlist deep -->
                        <text x="555" y="338" text-anchor="middle" fill="#FAB387" font-weight="600" font-size="14">ε</text>
                    </svg>
                    <figcaption>Figure 4.13 — Derivation tree for <code>var + var ∗ var</code> using grammar G17</figcaption>
                </div>

                <p>Listing the <strong>leaves</strong> of the derivation tree, the derived string is:</p>

                <div class="derived-string">
                    <span class="ds-token input">var</span>
                    <span class="ds-token action">{var}</span>
                    <span class="ds-token input">+</span>
                    <span class="ds-token input">var</span>
                    <span class="ds-token action">{var}</span>
                    <span class="ds-token input">∗</span>
                    <span class="ds-token input">var</span>
                    <span class="ds-token action">{var}</span>
                    <span class="ds-token action">{∗}</span>
                    <span class="ds-token action">{+}</span>
                </div>

                <p>
                    Input symbols and action symbols are interspersed. Separating out the
                    <strong>action symbols</strong> gives the output defined by the translation grammar:
                </p>

                <div class="derived-string">
                    <span class="ds-token action">{var}</span>
                    <span class="ds-token action">{var}</span>
                    <span class="ds-token action">{var}</span>
                    <span class="ds-token action">{∗}</span>
                    <span class="ds-token action">{+}</span>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        This output — <code>var var var ∗ +</code> — is the <strong>postfix</strong> (reverse Polish
                        notation) form of <code>var + var ∗ var</code>. Multiplication binds tighter, so
                        <code>{∗}</code> appears before <code>{+}</code> in the output.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION 4.5.1 – Pushdown Translators            -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="pushdown">
                <h2><span class="sec-icon">🖥️</span> 4.5.1 Implementing Translation Grammars with Pushdown Translators</h2>

                <p>
                    To implement a translation grammar with a pushdown machine, action symbols should be treated as
                    <strong>stack symbols</strong> and are pushed onto the stack in exactly the same way as terminals
                    and nonterminals occurring on the right side of a grammar rule.
                </p>

                <div class="note-box key">
                    <span class="box-icon">📖</span>
                    <div>
                        In addition, each action symbol <code>{A}</code> representing output should
                        <strong>label a row</strong> of the pushdown machine table. Every column of that row should
                        contain the entry:
                        <br><br>
                        <code style="font-family:'JetBrains Mono',monospace; color:#B45309; font-weight:700;">
                            Out(A), Pop, Retain
                        </code>
                        <br><br>
                        This means: output the symbol <code>A</code>, pop the stack, and retain the current input
                        symbol (do not advance).
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – PDM Table Figure 4.14                 -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="pdm-table">
                <h2><span class="sec-icon">📊</span> Figure 4.14 — Extended Pushdown Infix to Postfix Translator (G17)</h2>

                <p>
                    A pushdown machine to parse and translate infix expressions into postfix, according to
                    translation grammar G17, is shown below. All empty cells are assumed to be <strong>Reject</strong>.
                </p>

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
                                <td class="action">Rep(Elist {+} Term +)<br>Retain</td>
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
                                <td class="action">Rep(Tlist {∗} Factor ∗)<br>Retain</td>
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
                                <td class="action">Rep({var} var)<br>Retain</td>
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
                            <!-- Action symbol rows -->
                            <tr>
                                <td class="row-hdr act-row">{var}</td>
                                <td class="out-act">Out(var)<br>Pop, Retain</td>
                                <td class="out-act">Out(var)<br>Pop, Retain</td>
                                <td class="out-act">Out(var)<br>Pop, Retain</td>
                                <td class="out-act">Out(var)<br>Pop, Retain</td>
                                <td class="out-act">Out(var)<br>Pop, Retain</td>
                                <td class="out-act">Out(var)<br>Pop, Retain</td>
                            </tr>
                            <tr>
                                <td class="row-hdr act-row">{+}</td>
                                <td class="out-act">Out(+)<br>Pop, Retain</td>
                                <td class="out-act">Out(+)<br>Pop, Retain</td>
                                <td class="out-act">Out(+)<br>Pop, Retain</td>
                                <td class="out-act">Out(+)<br>Pop, Retain</td>
                                <td class="out-act">Out(+)<br>Pop, Retain</td>
                                <td class="out-act">Out(+)<br>Pop, Retain</td>
                            </tr>
                            <tr>
                                <td class="row-hdr act-row">{∗}</td>
                                <td class="out-act">Out(∗)<br>Pop, Retain</td>
                                <td class="out-act">Out(∗)<br>Pop, Retain</td>
                                <td class="out-act">Out(∗)<br>Pop, Retain</td>
                                <td class="out-act">Out(∗)<br>Pop, Retain</td>
                                <td class="out-act">Out(∗)<br>Pop, Retain</td>
                                <td class="out-act">Out(∗)<br>Pop, Retain</td>
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

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        Action symbol rows (<span style="color:#B45309">{var}</span>,
                        <span style="color:#B45309">{+}</span>, <span style="color:#B45309">{∗}</span>)
                        output their symbol and <strong>pop/retain</strong> in <em>every</em> column —
                        because action symbols are output regardless of what the current input symbol is.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION 4.5.2 – Recursive Descent               -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="recdesc">
                <h2><span class="sec-icon">💻</span> 4.5.2 Implementing Translation Grammars with Recursive Descent</h2>

                <p>
                    To implement a translation grammar with a recursive descent translator, action symbols should be
                    <strong>handled as they are encountered</strong> in the right side of a grammar rule. Normally
                    this means simply writing out the action symbol, but it could mean calling a method, depending
                    on the purpose of the action symbol.
                </p>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Important:</strong> It is important always to <strong>check for the input symbols
                            in the selection set before processing action symbols</strong>. Consider grammar G18 below
                        — the method <code>S</code> should only print the action symbol <code>print</code> if the
                        input is an <code>a</code>.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Grammar G18 Example                   -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="g18">
                <h2><span class="sec-icon">📐</span> Grammar G18 — A Cautionary Example</h2>

                <div class="grammar-box">
                    <span class="rule-num">G18:</span><br>
                    <span class="rule-num">1.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="act">{print}</span> <span class="t">a</span> <span class="nt">S</span><br>
                    <span class="rule-num">2.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="t">b</span> <span class="nt">B</span><br>
                    <span class="rule-num">3.</span> <span class="nt">B</span> <span class="arr">→</span> <span class="act">{print}</span>
                </div>

                <div class="note-box pro">
                    <span class="box-icon">📌</span>
                    <div>
                        Rule 3 (<code>B → {print}</code>) is really an <strong>epsilon rule</strong> in the
                        underlying grammar, since there are no terminals or nonterminals — only the action symbol.
                        Using the selection set algorithm, we find:
                    </div>
                </div>

                <div class="sel-grid" style="max-width:400px;">
                    <div class="sel-card ok">
                        <div class="rule-id">✓ Rule 1 — S → {print} a S</div>
                        <div class="sel-val">Sel(1) = { a }</div>
                    </div>
                    <div class="sel-card ok">
                        <div class="rule-id">✓ Rule 2 — S → b B</div>
                        <div class="sel-val">Sel(2) = { b }</div>
                    </div>
                    <div class="sel-card ok">
                        <div class="rule-id">✓ Rule 3 — B → {print}</div>
                        <div class="sel-val">Sel(3) = { N }</div>
                    </div>
                </div>

                <p>The recursive descent translator for grammar G18 is shown below:</p>

                <div class="code-block">
                    <span class="ty">void</span> <span class="fn">S</span> ()
                    { <span class="kw">if</span> (inp==<span class="str">'a'</span>)
                    { <span class="fn">getInp</span>(); <span class="cm">// apply rule 1</span>
                    System.out.<span class="out">println</span> (<span class="str">"print"</span>);
                    <span class="fn">S</span>();
                    } <span class="cm">// end rule 1</span>
                    <span class="kw">else if</span> (inp==<span class="str">'b'</span>)
                    { <span class="fn">getInp</span>(); <span class="cm">// apply rule 2</span>
                    <span class="fn">B</span>();
                    } <span class="cm">// end rule 2</span>
                    <span class="kw">else</span> <span class="fn">Reject</span> ();
                    }

                    <span class="ty">void</span> <span class="fn">B</span> ()
                    { <span class="kw">if</span> (inp==<span class="str">'N'</span>) System.out.<span class="out">println</span> (<span class="str">"print"</span>);
                    <span class="cm">// apply rule 3</span>
                    <span class="kw">else</span> <span class="fn">Reject</span> ();
                    }
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – G17 Recursive Descent Translator      -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="g17-recdesc">
                <h2><span class="sec-icon">💻</span> G17 — Recursive Descent Infix to Postfix Translator</h2>

                <p>
                    With these concepts in mind, we can write a recursive descent translator to translate infix
                    expressions to postfix according to grammar G17. Assume that a <code>var</code> token is
                    represented by the integer <code>256</code>.
                </p>

                <div class="code-block">
                    <span class="kw">final</span> <span class="ty">int</span> <span class="num">var</span> = <span class="num">256</span>; <span class="cm">// var token</span>
                    <span class="ty">char</span> inp;
                </div>

                <div class="code-block">
                    <span class="ty">void</span> <span class="fn">Expr</span> ()
                    { <span class="kw">if</span> (inp==<span class="str">'('</span> || inp==var)
                    { <span class="fn">Term</span> (); <span class="cm">// apply rule 1</span>
                    <span class="fn">Elist</span> ();
                    } <span class="cm">// end rule 1</span>
                    <span class="kw">else</span> <span class="fn">Reject</span> ();
                    }
                </div>

                <div class="code-block">
                    <span class="ty">void</span> <span class="fn">Elist</span> ()
                    { <span class="kw">if</span> (inp==<span class="str">'+'</span>)
                    { <span class="fn">getInp</span>(); <span class="cm">// apply rule 2</span>
                    <span class="fn">Term</span> ();
                    System.out.<span class="out">println</span> (<span class="str">'+'</span>); <span class="cm">// action {+}</span>
                    <span class="fn">Elist</span> ();
                    } <span class="cm">// end rule 2</span>
                    <span class="kw">else if</span> (inp==<span class="str">'N'</span> || inp==<span class="str">')'</span>) ; <span class="cm">// apply rule 3</span>
                    <span class="kw">else</span> <span class="fn">Reject</span> ();
                    }
                </div>

                <div class="code-block">
                    <span class="ty">void</span> <span class="fn">Term</span> ()
                    { <span class="kw">if</span> (inp==<span class="str">'('</span> || inp==var)
                    { <span class="fn">Factor</span> (); <span class="cm">// apply rule 4</span>
                    <span class="fn">Tlist</span> ();
                    } <span class="cm">// end rule 4</span>
                    <span class="kw">else</span> <span class="fn">Reject</span> ();
                    }
                </div>

                <div class="code-block">
                    <span class="ty">void</span> <span class="fn">Tlist</span> ()
                    { <span class="kw">if</span> (inp==<span class="str">'∗'</span>)
                    { <span class="fn">getInp</span>(); <span class="cm">// apply rule 5</span>
                    <span class="fn">Factor</span> ();
                    System.out.<span class="out">println</span> (<span class="str">'∗'</span>); <span class="cm">// action {∗}</span>
                    <span class="fn">Tlist</span> ();
                    } <span class="cm">// end rule 5</span>
                    <span class="kw">else if</span> (inp==<span class="str">'+'</span> || inp==<span class="str">')'</span> || inp==<span class="str">'N'</span>) ;
                    <span class="cm">// apply rule 6</span>
                    <span class="kw">else</span> <span class="fn">Reject</span> ();
                    }
                </div>

                <div class="code-block">
                    <span class="ty">void</span> <span class="fn">Factor</span> ()
                    { <span class="kw">if</span> (inp==<span class="str">'('</span>)
                    { <span class="fn">getInp</span>(); <span class="cm">// apply rule 7</span>
                    <span class="fn">Expr</span> ();
                    <span class="kw">if</span> (inp==<span class="str">')'</span>) <span class="fn">getInp</span>();
                    <span class="kw">else</span> <span class="fn">Reject</span> ();
                    } <span class="cm">// end rule 7</span>
                    <span class="kw">else if</span> (inp==var)
                    { <span class="fn">getInp</span>(); <span class="cm">// apply rule 8</span>
                    System.out.<span class="out">println</span> (<span class="str">"var"</span>); <span class="cm">// action {var}</span>
                    } <span class="cm">// end rule 8</span>
                    <span class="kw">else</span> <span class="fn">Reject</span> ();
                    }
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        Notice how action symbols translate directly into <code>System.out.println()</code>
                        calls placed <em>exactly where</em> the action symbol appears in the grammar rule's
                        right-hand side. For rule 2, the <code>{+}</code> appears <em>after</em>
                        <code>Term</code>, so the print call comes after <code>Term()</code> is called.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Sample Problem 4.5                    -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="sample">
                <h2><span class="sec-icon">🧪</span> Sample Problem 4.5</h2>

                <div class="note-box key">
                    <span class="box-icon">📖</span>
                    <div>
                        <strong>Problem:</strong> Show an extended pushdown translator for the translation
                        grammar G18.
                    </div>
                </div>

                <p><strong>Solution:</strong></p>

                <div class="pdm-wrap">
                    <table class="pdm-table">
                        <thead>
                            <tr>
                                <th>Stack Top</th>
                                <th>a</th>
                                <th>b</th>
                                <th>N</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="row-hdr">S</td>
                                <td class="action">Rep(S a {print})<br>Retain</td>
                                <td class="action">Rep(B b)<br>Retain</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-hdr">B</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="action">Rep({print})<br>Retain</td>
                            </tr>
                            <tr>
                                <td class="row-hdr">a</td>
                                <td class="action">Pop<br>Advance</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-hdr">b</td>
                                <td class="reject">Reject</td>
                                <td class="action">Pop<br>Advance</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-hdr act-row">{print}</td>
                                <td class="out-act">Out({print})<br>Pop, Retain</td>
                                <td class="out-act">Out({print})<br>Pop, Retain</td>
                                <td class="out-act">Out({print})<br>Pop, Retain</td>
                            </tr>
                            <tr>
                                <td class="row-hdr" style="color:#065F46;">, (Initial Stack)</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="accept">Accept</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Exercises 4.5                         -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="exercises">
                <h2><span class="sec-icon">📝</span> Exercises 4.5</h2>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 1 — Derivation Tree & Output String</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            Consider the following translation grammar with starting nonterminal <code>S</code>,
                            in which action symbols are put out:
                        </p>
                        <div class="grammar-box" style="line-height:2;">
                            <span class="rule-num">1.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="nt">A</span> <span class="t">b</span> <span class="nt">B</span><br>
                            <span class="rule-num">2.</span> <span class="nt">A</span> <span class="arr">→</span> <span class="act">{w}</span> <span class="t">a c</span><br>
                            <span class="rule-num">3.</span> <span class="nt">A</span> <span class="arr">→</span> <span class="t">b</span> <span class="nt">A</span> <span class="act">{x}</span><br>
                            <span class="rule-num">4.</span> <span class="nt">B</span> <span class="arr">→</span> <span class="act">{y}</span>
                        </div>
                        <p style="font-size:13.5px; margin-top:10px;">
                            Show a derivation tree and the output string for the input <code>bacb</code>.
                        </p>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 2 — Extended Pushdown Translator</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            Show an extended pushdown translator for the translation grammar of Problem 1.
                        </p>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 3 — Recursive Descent Translator</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            Show a recursive descent translator for the translation grammar of Problem 1.
                        </p>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 4 — Java Statements for Translation Rules</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            Write the Java statement which would appear in a recursive descent parser for each
                            of the following translation grammar rules:
                        </p>
                        <div class="grammar-box" style="line-height:2.2; font-size:13px;">
                            (a) <span class="nt">A</span> <span class="arr">→</span> <span class="act">{w}</span> <span class="t">a</span> <span class="act">{x}</span> <span class="nt">B C</span><br>
                            (b) <span class="nt">A</span> <span class="arr">→</span> <span class="t">a</span> <span class="act">{w}</span> <span class="act">{x}</span> <span class="nt">B C</span><br>
                            (c) <span class="nt">A</span> <span class="arr">→</span> <span class="t">a</span> <span class="act">{w}</span> <span class="nt">B</span> <span class="act">{x}</span> <span class="nt">C</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ── Chapter nav ──────────────────────────────── -->
            <div class="chapter-nav">
                <a href="c4_4.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous</span>4.4 Parsing Arithmetic Expressions</div>
                </a>
                <a href="?complete=1" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.6 Next Topic
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
    </script>

</body>

</html>