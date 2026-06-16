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
$page     = 15;
$nextPage = "c4_5_1.php"; 

if (isset($_GET['complete']) && $_GET['complete'] == '15') {
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



            <!-- ── Chapter nav ──────────────────────────────── -->
            <div class="chapter-nav">
                <a href="c4_4_2.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>4.4 Parsing Arithmetic Expressions (Part 3)</div>
                </a>
                <a href="?complete=15" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.5.1 Implementing Translation Grammars with Pushdown Translators
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