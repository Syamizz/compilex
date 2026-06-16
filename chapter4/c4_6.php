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
$page     = 6;
$nextPage = "c4_7.php"; // adjust as needed

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
    <title>Chapter 4.6 – CompileX</title>

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
            line-height: 2.1;
        }
        .grammar-box .rule-num { color: rgba(205,214,244,.45); user-select:none; margin-right:10px; }
        .grammar-box .nt   { color: #CBA6F7; }
        .grammar-box .t    { color: #A6E3A1; }
        .grammar-box .arr  { color: #94E2D5; }
        .grammar-box .eps  { color: #FAB387; }
        .grammar-box .attr { color: #F9E2AF; font-size: 11px; vertical-align: sub; } /* subscript attributes */
        .grammar-box .comp { color: #89DCEB; font-size: 12px; margin-left: 18px; }   /* attribute computation */
        .grammar-box .act  { color: #F38BA8; font-weight: 700; }

        /* ── Steps / algo blocks ────────────────────────────── */
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

        /* ── Code block ──────────────────────────────────────── */
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
            white-space: pre;
            tab-size: 4;
        }
        .code-block .kw   { color: #CBA6F7; }
        .code-block .ty   { color: #89DCEB; }
        .code-block .fn   { color: #FAB387; }
        .code-block .cm   { color: #6C7086; font-style: italic; }
        .code-block .str  { color: #A6E3A1; }
        .code-block .num  { color: #F38BA8; }
        .code-block .cls  { color: #94E2D5; }  /* class name */
        .code-block .out  { color: #F38BA8; font-weight: 600; }

        /* ── Attribute type badges ───────────────────────────── */
        .badge-synth {
            display: inline-block;
            background: #EEF2FF; color: #4338CA;
            font-size: 11px; font-weight: 700;
            padding: 2px 9px; border-radius: 20px;
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: .4px;
        }
        .badge-inh {
            display: inline-block;
            background: #FFF7ED; color: #B45309;
            font-size: 11px; font-weight: 700;
            padding: 2px 9px; border-radius: 20px;
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: .4px;
        }

        /* ── Tree SVG wrapper ─────────────────────────────────── */
        .tree-wrap {
            background: var(--card, #fff);
            border: 1px solid rgba(99,102,241,.15);
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

        /* ── Two-column compare ───────────────────────────────── */
        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin: 18px 0;
        }
        @media (max-width: 700px) { .two-col { grid-template-columns: 1fr; } }

        .attr-card {
            border-radius: 12px;
            padding: 16px 18px;
            font-size: 13.5px;
        }
        .attr-card.synth {
            background: #EEF2FF;
            border: 1.5px solid rgba(99,102,241,.25);
        }
        .attr-card.synth h4 { color: #4338CA; font-family:'Syne',sans-serif; font-size:14px; font-weight:700; margin-bottom:8px; }
        .attr-card.inh {
            background: #FFF7ED;
            border: 1.5px solid rgba(245,158,11,.3);
        }
        .attr-card.inh h4 { color: #B45309; font-family:'Syne',sans-serif; font-size:14px; font-weight:700; margin-bottom:8px; }
    </style>
</head>

<body>

    <?php include 'navbar_c4.php'; ?>

    <div class="note-container">

        <!-- ── Sidebar TOC ─────────────────────────────────────── -->
        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#intro"       class="toc-link">4.6 Attributed Grammars</a></li>
                <li><a href="#g19"         class="toc-link">Grammar G19 — Synthesized</a></li>
                <li><a href="#fig415"      class="toc-link">Figure 4.15 — Prefix Tree</a></li>
                <li><a href="#g20"         class="toc-link">Grammar G20 — Inherited</a></li>
                <li><a href="#fig416"      class="toc-link">Figure 4.16 — Declaration Tree</a></li>
                <li><a href="#recdesc"     class="toc-link">4.6.1 Recursive Descent</a></li>
                <li><a href="#mutableint"  class="toc-link">MutableInt Class</a></li>
                <li><a href="#caution"     class="toc-link">Contradictions & Caution</a></li>
                <li><a href="#sample"      class="toc-link">Sample Problem 4.6</a></li>
                <li><a href="#exercises"   class="toc-link">Exercises 4.6</a></li>
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
                <h1>Attributed Grammars</h1>
                <div class="metadata">
                    <span>⏱ 30 min read</span>
                    <span>🎯 Advanced</span>
                    <span>⚙️ Attributed Grammars</span>
                </div>
            </header>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION 4.6 – Introduction                      -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="intro">
                <h2><span class="sec-icon">🔍</span> Introduction</h2>

                <p>
                    It will soon become clear that many programming language constructs cannot be adequately
                    described with a context-free grammar. For example, many languages stipulate that a loop
                    control variable must not be altered within the scope of the loop — this is not possible to
                    describe in a practical way with a context-free grammar. Also, it will be necessary to
                    propagate information, such as a location for the temporary result of a subexpression, from
                    one grammar rule to another.
                </p>
                <p>
                    Therefore, we extend grammars further by introducing <strong>attributed grammars</strong>, in
                    which each of the terminals and nonterminals may have <strong>zero or more attributes</strong>,
                    normally designated by <strong>subscripts</strong>, associated with it.
                </p>

                <div class="note-box key">
                    <span class="box-icon">📖</span>
                    <div>
                        <strong>Examples of attributes:</strong><br>
                        • An attribute on an <code>Expr</code> nonterminal could be a pointer to the stack
                        location containing the result value of an evaluated expression.<br>
                        • The attribute of an input symbol (a lexical token) could be the <strong>value part</strong>
                        of the token.
                    </div>
                </div>

                <p>
                    In an attributed grammar there may be <strong>zero or more attribute computation rules</strong>
                    associated with each grammar rule. These show how the attributes are assigned values as the
                    corresponding grammar rule is applied during the parse.
                </p>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        <strong>How to build attributed derivation trees:</strong> First eliminate all attributes
                        from the grammar and build a plain derivation tree. Then enter attribute values in the tree
                        according to the attribute computation rules. Because some attributes take values from
                        <em>higher</em> nodes and some from <em>lower</em> nodes, the process of filling in
                        attribute values is not straightforward.
                    </div>
                </div>

                <div class="two-col">
                    <div class="attr-card synth">
                        <h4>🔼 Synthesized Attributes</h4>
                        <span class="badge-synth">bottom → up</span>
                        <p style="margin-top:10px; font-size:13px; color:var(--text);">
                            Take their values from attributes of <strong>lower nodes</strong> in the
                            derivation tree. Information flows <em>upward</em> — child nodes compute values
                            that are returned to parent nodes.
                        </p>
                    </div>
                    <div class="attr-card inh">
                        <h4>🔽 Inherited Attributes</h4>
                        <span class="badge-inh">top → down</span>
                        <p style="margin-top:10px; font-size:13px; color:var(--text);">
                            Take their values from attributes of <strong>higher nodes</strong> in the
                            derivation tree. Information flows <em>downward</em> — parent nodes pass values
                            down to child nodes.
                        </p>
                    </div>
                </div>

                <div class="note-box pro">
                    <span class="box-icon">📌</span>
                    <div>
                        <strong>Consistency rule:</strong> The number and kind of attributes of a symbol must
                        be consistent throughout the entire grammar. For example, if the symbol
                        <code>A<sub>i,s</sub></code> has two attributes — the first inherited and the second
                        synthesized — then this must be true <em>everywhere</em> the symbol <code>A</code>
                        appears in the grammar.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Grammar G19 (Synthesized)            -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="g19">
                <h2><span class="sec-icon">📐</span> Grammar G19 — Prefix Expression Evaluator</h2>

                <p>
                    Grammar G19 is an attributed grammar for <strong>prefix expressions</strong> involving
                    addition and multiplication. The attributes (shown as subscripts) are intended to
                    <strong>evaluate the arithmetic expression</strong>. The attribute on the terminal
                    <code>const</code> is the value of the constant as supplied by the lexical phase.
                </p>

                <div class="note-box pro">
                    <span class="box-icon">📌</span>
                    <div>
                        This kind of expression evaluation is typical of what is done in an
                        <strong>interpreter</strong>, but not in a compiler.
                    </div>
                </div>

                <div class="grammar-box">
                    <span class="rule-num">G19:</span><br>
                    <span class="rule-num">1.</span>
                    <span class="nt">Expr<sub class="attr">p</sub></span>
                    <span class="arr">→</span>
                    <span class="t">+</span>
                    <span class="nt">Expr<sub class="attr">q</sub></span>
                    <span class="nt">Expr<sub class="attr">r</sub></span>
                    <span class="comp">p = q + r</span><br>

                    <span class="rule-num">2.</span>
                    <span class="nt">Expr<sub class="attr">p</sub></span>
                    <span class="arr">→</span>
                    <span class="t">∗</span>
                    <span class="nt">Expr<sub class="attr">q</sub></span>
                    <span class="nt">Expr<sub class="attr">r</sub></span>
                    <span class="comp">p = q ∗ r</span><br>

                    <span class="rule-num">3.</span>
                    <span class="nt">Expr<sub class="attr">p</sub></span>
                    <span class="arr">→</span>
                    <span class="t">const<sub class="attr">q</sub></span>
                    <span class="comp">p = q</span>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        All attribute values in G19 are taken from <em>lower nodes</em> in the tree —
                        these are <span class="badge-synth">synthesized attributes</span>. The value of
                        each subexpression is computed from its children and passed upward.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Figure 4.15                           -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="fig415">
                <h2><span class="sec-icon">🌳</span> Figure 4.15 — Attributed Derivation Tree for <code>+ ∗ 3 4 + 5 6</code></h2>

                <p>
                    The attributed derivation tree for the prefix input <code>+ ∗ 3 4 + 5 6</code> is shown
                    below. The attribute on each node is the value of that subexpression.
                </p>

                <div class="tree-wrap">
                    <svg viewBox="0 0 500 310" width="460" xmlns="http://www.w3.org/2000/svg"
                         font-family="JetBrains Mono, monospace" font-size="12">

                        <!-- edges level 0→1 -->
                        <line x1="250" y1="30" x2="160" y2="80" stroke="#6366F1" stroke-width="1.5"/>
                        <line x1="250" y1="30" x2="320" y2="80" stroke="#6366F1" stroke-width="1.5"/>
                        <line x1="250" y1="30" x2="390" y2="80" stroke="#6366F1" stroke-width="1.5"/>

                        <!-- edges level 1 left → 2 -->
                        <line x1="320" y1="95" x2="250" y2="145" stroke="#6366F1" stroke-width="1.5"/>
                        <line x1="320" y1="95" x2="290" y2="145" stroke="#6366F1" stroke-width="1.5"/>
                        <line x1="320" y1="95" x2="350" y2="145" stroke="#6366F1" stroke-width="1.5"/>

                        <!-- edges level 1 right → 2 -->
                        <line x1="390" y1="95" x2="390" y2="145" stroke="#6366F1" stroke-width="1.5"/>
                        <line x1="390" y1="95" x2="430" y2="145" stroke="#6366F1" stroke-width="1.5"/>
                        <line x1="390" y1="95" x2="460" y2="145" stroke="#6366F1" stroke-width="1.5"/>

                        <!-- edges level 2 → 3 (leaves) -->
                        <line x1="290" y1="160" x2="290" y2="208" stroke="#6366F1" stroke-width="1.5"/>
                        <line x1="350" y1="160" x2="350" y2="208" stroke="#6366F1" stroke-width="1.5"/>
                        <line x1="430" y1="160" x2="430" y2="208" stroke="#6366F1" stroke-width="1.5"/>
                        <line x1="460" y1="160" x2="460" y2="208" stroke="#6366F1" stroke-width="1.5"/>

                        <!-- Expr23 root -->
                        <circle cx="250" cy="22" r="18" fill="#6366F1"/>
                        <text x="250" y="19" text-anchor="middle" fill="white" font-weight="700" font-size="11">Expr</text>
                        <text x="250" y="31" text-anchor="middle" fill="#F9E2AF" font-size="10" font-weight="700">23</text>

                        <!-- + operator -->
                        <circle cx="160" cy="88" r="14" fill="#A6E3A1" opacity=".9"/>
                        <text x="160" y="93" text-anchor="middle" fill="#065F46" font-weight="700">+</text>

                        <!-- Expr12 -->
                        <circle cx="320" cy="88" r="18" fill="#6366F1"/>
                        <text x="320" y="85" text-anchor="middle" fill="white" font-weight="700" font-size="11">Expr</text>
                        <text x="320" y="97" text-anchor="middle" fill="#F9E2AF" font-size="10" font-weight="700">12</text>

                        <!-- Expr11 -->
                        <circle cx="390" cy="88" r="18" fill="#6366F1"/>
                        <text x="390" y="85" text-anchor="middle" fill="white" font-weight="700" font-size="11">Expr</text>
                        <text x="390" y="97" text-anchor="middle" fill="#F9E2AF" font-size="10" font-weight="700">11</text>

                        <!-- * operator -->
                        <circle cx="250" cy="153" r="14" fill="#A6E3A1" opacity=".9"/>
                        <text x="250" y="158" text-anchor="middle" fill="#065F46" font-weight="700">∗</text>

                        <!-- Expr3 -->
                        <circle cx="290" cy="153" r="17" fill="#6366F1"/>
                        <text x="290" y="150" text-anchor="middle" fill="white" font-weight="700" font-size="11">Expr</text>
                        <text x="290" y="162" text-anchor="middle" fill="#F9E2AF" font-size="10" font-weight="700">3</text>

                        <!-- Expr4 -->
                        <circle cx="350" cy="153" r="17" fill="#6366F1"/>
                        <text x="350" y="150" text-anchor="middle" fill="white" font-weight="700" font-size="11">Expr</text>
                        <text x="350" y="162" text-anchor="middle" fill="#F9E2AF" font-size="10" font-weight="700">4</text>

                        <!-- + right operator -->
                        <circle cx="390" cy="153" r="14" fill="#A6E3A1" opacity=".9"/>
                        <text x="390" y="158" text-anchor="middle" fill="#065F46" font-weight="700">+</text>

                        <!-- Expr5 -->
                        <circle cx="430" cy="153" r="17" fill="#6366F1"/>
                        <text x="430" y="150" text-anchor="middle" fill="white" font-weight="700" font-size="11">Expr</text>
                        <text x="430" y="162" text-anchor="middle" fill="#F9E2AF" font-size="10" font-weight="700">5</text>

                        <!-- Expr6 -->
                        <circle cx="460" cy="153" r="17" fill="#6366F1"/>
                        <text x="460" y="150" text-anchor="middle" fill="white" font-weight="700" font-size="11">Expr</text>
                        <text x="460" y="162" text-anchor="middle" fill="#F9E2AF" font-size="10" font-weight="700">6</text>

                        <!-- const leaves -->
                        <circle cx="290" cy="220" r="17" fill="#CBA6F7" opacity=".85"/>
                        <text x="290" y="217" text-anchor="middle" fill="#3730A3" font-size="10" font-weight="700">const</text>
                        <text x="290" y="229" text-anchor="middle" fill="#F9E2AF" font-size="10" font-weight="700">3</text>

                        <circle cx="350" cy="220" r="17" fill="#CBA6F7" opacity=".85"/>
                        <text x="350" y="217" text-anchor="middle" fill="#3730A3" font-size="10" font-weight="700">const</text>
                        <text x="350" y="229" text-anchor="middle" fill="#F9E2AF" font-size="10" font-weight="700">4</text>

                        <circle cx="430" cy="220" r="17" fill="#CBA6F7" opacity=".85"/>
                        <text x="430" y="217" text-anchor="middle" fill="#3730A3" font-size="10" font-weight="700">const</text>
                        <text x="430" y="229" text-anchor="middle" fill="#F9E2AF" font-size="10" font-weight="700">5</text>

                        <circle cx="460" cy="220" r="17" fill="#CBA6F7" opacity=".85"/>
                        <text x="460" y="217" text-anchor="middle" fill="#3730A3" font-size="10" font-weight="700">const</text>
                        <text x="460" y="229" text-anchor="middle" fill="#F9E2AF" font-size="10" font-weight="700">6</text>

                        <!-- annotation arrows showing computation -->
                        <text x="20" y="100" fill="#6C7086" font-size="10" font-style="italic">∗3×4=12</text>
                        <text x="20" y="115" fill="#6C7086" font-size="10" font-style="italic">+5+6=11</text>
                        <text x="20" y="130" fill="#6C7086" font-size="10" font-style="italic">+12+11=23</text>
                    </svg>
                    <figcaption>Figure 4.15 — Attributed derivation tree for the prefix expression <code>+ ∗ 3 4 + 5 6</code></figcaption>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        The attribute value at each node is the computed result of that subexpression:
                        <code>∗ 3 4 = 12</code>, <code>+ 5 6 = 11</code>, <code>+ 12 11 = 23</code>.
                        All values flow <em>upward</em> — these are <span class="badge-synth">synthesized attributes</span>.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Grammar G20 (Inherited)              -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="g20">
                <h2><span class="sec-icon">📐</span> Grammar G20 — Type Declarations</h2>

                <p>
                    A second example involving attribute values taken from <em>higher</em> nodes in the tree
                    uses <span class="badge-inh">inherited attributes</span>. Grammar G20 is a grammar for
                    declarations of a numeric data type. In G20, <code>type</code> is a terminal (token)
                    whose value part may be a type such as <code>int</code>, <code>float</code>, etc.
                </p>
                <p>
                    This grammar is used to specify type declarations such as <code>Integer x, y, z</code>.
                    We wish to store the type of each identifier with its symbol table entry. To do this,
                    the <strong>type must be passed down</strong> the derivation tree to each of the variables
                    being declared.
                </p>

                <div class="grammar-box">
                    <span class="rule-num">G20:</span><br>
                    <span class="rule-num">1.</span>
                    <span class="nt">Dcl</span>
                    <span class="arr">→</span>
                    <span class="t">type<sub class="attr">t</sub></span>
                    <span class="nt">Varlist<sub class="attr">v</sub></span>
                    <span class="comp">v = t</span><br>

                    <span class="rule-num">2.</span>
                    <span class="nt">Varlist<sub class="attr">v</sub></span>
                    <span class="arr">→</span>
                    <span class="nt">Varlist<sub class="attr">w</sub></span>
                    <span class="t">,</span>
                    <span class="t">ident<sub class="attr">x</sub></span>
                    <span class="comp">w = v</span><br>

                    <span class="rule-num">3.</span>
                    <span class="nt">Varlist<sub class="attr">v</sub></span>
                    <span class="arr">→</span>
                    <span class="t">ident<sub class="attr">x</sub></span>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        The type attribute <code>v</code> is passed <strong>down</strong> the tree from
                        <code>Dcl</code> through each <code>Varlist</code> node to reach each identifier.
                        This is a classic use of <span class="badge-inh">inherited attributes</span>.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Figure 4.16                           -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="fig416">
                <h2><span class="sec-icon">🌳</span> Figure 4.16 — Attributed Derivation Tree for <code>int a, b, c</code></h2>

                <p>
                    The attributed derivation tree for the declaration <code>int a, b, c</code> using grammar
                    G20 is shown below. Note that the attribute values move either <strong>horizontally</strong>
                    on one level (rule 1) or <strong>down</strong> to a lower level (rules 2 and 3).
                </p>

                <div class="tree-wrap">
                    <svg viewBox="0 0 500 310" width="460" xmlns="http://www.w3.org/2000/svg"
                         font-family="JetBrains Mono, monospace" font-size="12">

                        <!-- edges -->
                        <!-- Dcl → type_int  Varlist_int -->
                        <line x1="250" y1="30" x2="130" y2="80" stroke="#B45309" stroke-width="1.5" stroke-dasharray="4,2"/>
                        <line x1="250" y1="30" x2="350" y2="80" stroke="#B45309" stroke-width="1.5" stroke-dasharray="4,2"/>

                        <!-- Varlist_int (L1) → Varlist_int , ident_c -->
                        <line x1="350" y1="95" x2="250" y2="145" stroke="#B45309" stroke-width="1.5" stroke-dasharray="4,2"/>
                        <line x1="350" y1="95" x2="370" y2="145" stroke="#6366F1" stroke-width="1.5"/>
                        <line x1="350" y1="95" x2="430" y2="145" stroke="#6366F1" stroke-width="1.5"/>

                        <!-- Varlist_int (L2) → Varlist_int , ident_b -->
                        <line x1="250" y1="160" x2="160" y2="210" stroke="#B45309" stroke-width="1.5" stroke-dasharray="4,2"/>
                        <line x1="250" y1="160" x2="250" y2="210" stroke="#6366F1" stroke-width="1.5"/>
                        <line x1="250" y1="160" x2="310" y2="210" stroke="#6366F1" stroke-width="1.5"/>

                        <!-- Varlist_int (L3) → ident_a -->
                        <line x1="160" y1="225" x2="160" y2="272" stroke="#6366F1" stroke-width="1.5"/>

                        <!-- Dcl node -->
                        <rect x="215" y="12" width="72" height="26" rx="8" fill="#6366F1"/>
                        <text x="251" y="29" text-anchor="middle" fill="white" font-weight="700" font-size="12">Dcl</text>

                        <!-- type_int -->
                        <rect x="88" y="72" width="84" height="26" rx="8" fill="#CBA6F7" opacity=".9"/>
                        <text x="130" y="82" text-anchor="middle" fill="#3730A3" font-weight="700" font-size="11">Type</text>
                        <text x="130" y="93" text-anchor="middle" fill="#B45309" font-weight="700" font-size="10">integer</text>

                        <!-- Varlist_int L1 -->
                        <rect x="298" y="72" width="104" height="26" rx="8" fill="#FAB387" opacity=".9"/>
                        <text x="350" y="82" text-anchor="middle" fill="#7C2D12" font-weight="700" font-size="11">Varlist</text>
                        <text x="350" y="93" text-anchor="middle" fill="#B45309" font-weight="700" font-size="10">integer</text>

                        <!-- Varlist_int L2 -->
                        <rect x="198" y="143" width="104" height="26" rx="8" fill="#FAB387" opacity=".9"/>
                        <text x="250" y="153" text-anchor="middle" fill="#7C2D12" font-weight="700" font-size="11">Varlist</text>
                        <text x="250" y="164" text-anchor="middle" fill="#B45309" font-weight="700" font-size="10">integer</text>

                        <!-- , c -->
                        <circle cx="370" cy="153" r="12" fill="#A6E3A1" opacity=".9"/>
                        <text x="370" y="158" text-anchor="middle" fill="#065F46" font-weight="700">,</text>

                        <circle cx="430" cy="153" r="17" fill="#CBA6F7" opacity=".85"/>
                        <text x="430" y="150" text-anchor="middle" fill="#3730A3" font-size="10" font-weight="700">ident</text>
                        <text x="430" y="162" text-anchor="middle" fill="#F9E2AF" font-size="10" font-weight="700">c</text>

                        <!-- Varlist_int L3 -->
                        <rect x="108" y="212" width="104" height="26" rx="8" fill="#FAB387" opacity=".9"/>
                        <text x="160" y="222" text-anchor="middle" fill="#7C2D12" font-weight="700" font-size="11">Varlist</text>
                        <text x="160" y="233" text-anchor="middle" fill="#B45309" font-weight="700" font-size="10">integer</text>

                        <!-- , b -->
                        <circle cx="250" cy="220" r="12" fill="#A6E3A1" opacity=".9"/>
                        <text x="250" y="225" text-anchor="middle" fill="#065F46" font-weight="700">,</text>

                        <circle cx="310" cy="220" r="17" fill="#CBA6F7" opacity=".85"/>
                        <text x="310" y="217" text-anchor="middle" fill="#3730A3" font-size="10" font-weight="700">ident</text>
                        <text x="310" y="229" text-anchor="middle" fill="#F9E2AF" font-size="10" font-weight="700">b</text>

                        <!-- ident_a -->
                        <circle cx="160" cy="283" r="17" fill="#CBA6F7" opacity=".85"/>
                        <text x="160" y="280" text-anchor="middle" fill="#3730A3" font-size="10" font-weight="700">ident</text>
                        <text x="160" y="292" text-anchor="middle" fill="#F9E2AF" font-size="10" font-weight="700">a</text>

                        <!-- legend arrow: dashed = inherited flow -->
                        <line x1="20" y1="265" x2="50" y2="265" stroke="#B45309" stroke-width="1.5" stroke-dasharray="4,2"/>
                        <text x="55" y="269" fill="#B45309" font-size="10" font-style="italic">= inherited attribute flow</text>
                    </svg>
                    <figcaption>Figure 4.16 — Attributed derivation tree for <code>int a, b, c</code> using grammar G20</figcaption>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        The type <code>integer</code> originates at the <code>Type</code> leaf and is passed
                        <em>horizontally</em> to <code>Varlist</code> by rule 1 (<code>v = t</code>), then passed
                        <em>downward</em> through each <code>Varlist</code> node by rule 2 (<code>w = v</code>)
                        until it reaches every identifier — <code>a</code>, <code>b</code>, and <code>c</code>.
                        These are <span class="badge-inh">inherited attributes</span>.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION 4.6.1 – Recursive Descent               -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="recdesc">
                <h2><span class="sec-icon">💻</span> 4.6.1 Implementing Attributed Grammars with Recursive Descent</h2>

                <p>
                    To implement an attributed grammar with recursive descent, the attributes will be implemented
                    as <strong>parameters or instance variables</strong> in the methods defining nonterminals.
                    For example, if <code>S<sub>a,b</sub></code> is a nonterminal with two attributes, then the
                    method <code>S</code> will have two parameters, <code>a</code> and <code>b</code>.
                </p>

                <div class="two-col">
                    <div class="attr-card synth">
                        <h4>🔼 Synthesized Attributes → Return values</h4>
                        <p style="font-size:13px; color:var(--text);">
                            Used to return information to the <em>calling</em> method. Must be implemented
                            with <strong>objects (reference types)</strong> so the called method can modify
                            the value seen by the caller.
                        </p>
                    </div>
                    <div class="attr-card inh">
                        <h4>🔽 Inherited Attributes → Pass values in</h4>
                        <p style="font-size:13px; color:var(--text);">
                            Pass information <em>to</em> the called method. May be passed <strong>by value
                            or using primitive types</strong>, since the callee only reads them.
                        </p>
                    </div>
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        Synthesized attributes are implemented with objects. We might be tempted to use the Java
                        wrapper class <code>Integer</code> — but <code>Integer</code> is <strong>not mutable</strong>
                        (Integer objects cannot be changed). Therefore, we build our own wrapper class called
                        <strong>MutableInt</strong>.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – MutableInt Class                      -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="mutableint">
                <h2><span class="sec-icon">🔧</span> The MutableInt Class</h2>

                <p>
                    The <code>MutableInt</code> class is a wrapper class for <code>int</code> values whose
                    contents can be changed — needed to implement synthesized attributes with recursive descent.
                    Action symbol attributes will be implemented as <strong>instance variables</strong>.
                </p>

                <div class="code-block">
<span class="cm">// Wrapper class for ints which lets you change the value.</span>
<span class="cm">// This class is needed to implement attributed grammars</span>
<span class="cm">// with recursive descent</span>
<span class="kw">class</span> <span class="cls">MutableInt</span> <span class="kw">extends</span> <span class="cls">Object</span>
{   <span class="ty">int</span> value;              <span class="cm">// store a single int</span>

    <span class="cls">MutableInt</span> (<span class="ty">int</span> i)     <span class="cm">// Initializing constructor</span>
    {   value = i; }

    <span class="cls">MutableInt</span> ()          <span class="cm">// Default constructor</span>
    {   value = <span class="num">0</span>;           <span class="cm">// default value is 0</span>
    }

    <span class="ty">int</span> <span class="fn">get</span>()              <span class="cm">// Accessor</span>
    {   <span class="kw">return</span> value; }

    <span class="ty">void</span> <span class="fn">set</span> (<span class="ty">int</span> i)       <span class="cm">// Mutator</span>
    {   value = i; }

    <span class="kw">public</span> <span class="cls">String</span> <span class="fn">toString</span>()  <span class="cm">// For printing</span>
    {   <span class="kw">return</span> (<span class="kw">new</span> <span class="cls">Integer</span> (value)).<span class="fn">toString</span>(); }
}
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Contradictions & Caution              -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="caution">
                <h2><span class="sec-icon">⚠️</span> Attribute Computation Order & Contradictions</h2>

                <p>
                    Care must be taken that the attribute computation rules are included at the appropriate places,
                    and that they do not constitute a <strong>contradiction</strong>. Consider this example:
                </p>

                <div class="grammar-box">
                    <span class="nt">S<sub class="attr">p</sub></span>
                    <span class="arr">→</span>
                    <span class="t">a</span>
                    <span class="nt">A<sub class="attr">r</sub></span>
                    <span class="nt">B<sub class="attr">s</sub></span>
                    <span class="comp">p = r + s</span>
                </div>

                <p>The recursive descent method for <code>S</code> would be:</p>

                <div class="code-block">
<span class="ty">void</span> <span class="fn">S</span> (<span class="cls">MutableInt</span> p)
{   <span class="kw">if</span> (token.<span class="fn">get_class</span>()==<span class="str">'a'</span>)
    {   token.<span class="fn">getToken</span>();
        <span class="fn">A</span>(r);
        <span class="fn">B</span>(s);
        <span class="cm">// this must come after calls to A(r), B(s)</span>
        p.<span class="fn">set</span>(r.<span class="fn">get</span>() + s.<span class="fn">get</span>());
    }
}
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        In this example, methods <code>S</code>, <code>A</code>, and <code>B</code> all return
                        values via their parameters (synthesized attributes implemented as references), and there
                        is <strong>no contradiction</strong> — the computation rule <code>p = r + s</code> is
                        placed <em>after</em> both <code>A(r)</code> and <code>B(s)</code> are called.
                    </div>
                </div>

                <p>
                    However, assuming that the attribute of <code>B</code> is <em>synthesized</em> and the
                    attribute of <code>A</code> is <em>inherited</em>, the following rule <strong>cannot be
                    implemented</strong>:
                </p>

                <div class="grammar-box">
                    <span class="nt">S</span>
                    <span class="arr">→</span>
                    <span class="t">a</span>
                    <span class="nt">A<sub class="attr">p</sub></span>
                    <span class="nt">B<sub class="attr">q</sub></span>
                    <span class="comp">p = q</span>
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        In the method <code>S</code>, <code>q</code> will not have a value until method
                        <code>B</code> has been called and terminated. Therefore, it will not be possible to
                        assign a value to <code>p</code> <em>before</em> calling method <code>A</code>.
                        This assumes, as always, that input is read <strong>from left to right</strong>.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Sample Problem 4.6                    -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="sample">
                <h2><span class="sec-icon">🧪</span> Sample Problem 4.6</h2>

                <div class="note-box key">
                    <span class="box-icon">📖</span>
                    <div>
                        <strong>Problem:</strong> Show a recursive descent parser for the attributed grammar G19.
                        Assume that the <code>Token</code> class has inspector methods <code>get_class()</code>
                        and <code>get_val()</code>, which return the class and value parts of a lexical token,
                        respectively. The method <code>getToken()</code> reads in a new token.
                    </div>
                </div>

                <p><strong>Solution:</strong></p>

                <div class="code-block">
<span class="kw">class</span> <span class="cls">RecDescent</span>
{
    <span class="kw">final</span> <span class="ty">int</span> Num = <span class="num">0</span>;   <span class="cm">// token classes</span>
    <span class="kw">final</span> <span class="ty">int</span> Op  = <span class="num">1</span>;
    <span class="kw">final</span> <span class="ty">int</span> End = <span class="num">2</span>;
    <span class="cls">Token</span> token;

    <span class="ty">void</span> <span class="fn">Eval</span> ()
    {   <span class="cls">MutableInt</span> p = <span class="kw">new</span> <span class="cls">MutableInt</span>(<span class="num">0</span>);
        token = <span class="kw">new</span> <span class="cls">Token</span>();
        token.<span class="fn">getToken</span>();   <span class="cm">// Read a token from stdin</span>
        <span class="fn">Expr</span> (p);
        <span class="cm">// show final result</span>
        <span class="kw">if</span> (token.<span class="fn">get_class</span>()==End) System.out.<span class="out">println</span> (p);
        <span class="kw">else</span> <span class="fn">reject</span>();
    }

    <span class="ty">void</span> <span class="fn">Expr</span> (<span class="cls">MutableInt</span> p)
    {   <span class="cls">MutableInt</span> q = <span class="kw">new</span> <span class="cls">MutableInt</span>(<span class="num">0</span>),
                   r = <span class="kw">new</span> <span class="cls">MutableInt</span>(<span class="num">0</span>);  <span class="cm">// Attributes q, r</span>

        <span class="kw">if</span> (token.<span class="fn">get_class</span>()==Op)        <span class="cm">// Operator?</span>
            <span class="kw">if</span> (token.<span class="fn">get_value</span>()== (<span class="ty">int</span>)<span class="str">'+'</span>)  <span class="cm">// apply rule 1</span>
            {   token.<span class="fn">getToken</span>();            <span class="cm">// read next token</span>
                <span class="fn">Expr</span>(q);
                <span class="fn">Expr</span>(r);
                p.<span class="fn">set</span> (q.<span class="fn">get</span>() + r.<span class="fn">get</span>());
            }  <span class="cm">// end rule 1</span>
            <span class="kw">else</span>                              <span class="cm">// should be *, apply rule 2</span>
            {   token.<span class="fn">getToken</span>();            <span class="cm">// read next token</span>
                <span class="fn">Expr</span>(q);
                <span class="fn">Expr</span>(r);
                p.<span class="fn">set</span> (q.<span class="fn">get</span>() * r.<span class="fn">get</span>());
            }  <span class="cm">// end rule 2</span>
        <span class="kw">else if</span> (token.<span class="fn">get_class</span>()==Num)   <span class="cm">// Is it a number?</span>
        {   p.<span class="fn">set</span> (token.<span class="fn">get_value</span>());     <span class="cm">// apply rule 3</span>
            token.<span class="fn">getToken</span>();                <span class="cm">// read next token</span>
        }  <span class="cm">// end rule 3</span>
        <span class="kw">else</span> <span class="fn">reject</span>();
    }
}
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        Notice that <code>MutableInt</code> parameters are used for <code>p</code>,
                        <code>q</code>, and <code>r</code> — all synthesized attributes. The computation
                        rules <code>p = q + r</code> and <code>p = q ∗ r</code> are placed <em>after</em>
                        the recursive <code>Expr()</code> calls, ensuring that <code>q</code> and
                        <code>r</code> already have their values before <code>p</code> is set.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Exercises 4.6                         -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="exercises">
                <h2><span class="sec-icon">📝</span> Exercises 4.6</h2>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 1 — Attributed Derivation Tree & Output</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            Consider the following attributed translation grammar with starting nonterminal
                            <code>S</code>, in which action symbols are output:
                        </p>
                        <div class="grammar-box" style="line-height:2.2;">
                            <span class="rule-num">1.</span>
                            <span class="nt">S<sub class="attr">p</sub></span>
                            <span class="arr">→</span>
                            <span class="nt">A<sub class="attr">q</sub></span>
                            <span class="t">b</span>
                            <span class="nt">A<sub class="attr">t</sub></span>
                            <span class="comp">p = r + t</span><br>

                            <span class="rule-num">2.</span>
                            <span class="nt">A<sub class="attr">p</sub></span>
                            <span class="arr">→</span>
                            <span class="t">a<sub class="attr">p</sub></span>
                            <span class="act">{w}<sub class="attr">p</sub></span>
                            <span class="t">c</span><br>

                            <span class="rule-num">3.</span>
                            <span class="nt">A<sub class="attr">p</sub></span>
                            <span class="arr">→</span>
                            <span class="t">b<sub class="attr">q</sub></span>
                            <span class="nt">A<sub class="attr">r</sub></span>
                            <span class="act">{x}<sub class="attr">p</sub></span>
                            <span class="comp">p = q + r</span>
                        </div>
                        <p style="font-size:13.5px; margin-top:10px;">
                            Show an attributed derivation tree for the input string
                            <code>a<sub>1</sub>cb<sub>2</sub>b<sub>3</sub>a<sub>4</sub>c</code>, and show the
                            output symbols with attributes corresponding to this input.
                        </p>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 2 — Recursive Descent Translator</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            Show a recursive descent translator for the grammar of Problem 1. Assume that all
                            attributes are integers and that, as in Sample Problem 4.6, the <code>Token</code>
                            class has methods <code>get_class()</code> and <code>get_value()</code> which return
                            the class and value parts of a lexical token, and the <code>Token</code> class has a
                            <code>getToken()</code> method which reads a token from the standard input file.
                        </p>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 3 — Attributed Derivation Trees</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            Show an attributed derivation tree for each input string using the following attributed
                            grammar:
                        </p>
                        <div class="grammar-box" style="line-height:2.4;">
                            <span class="rule-num">1.</span>
                            <span class="nt">S<sub class="attr">p</sub></span>
                            <span class="arr">→</span>
                            <span class="nt">A<sub class="attr">q,r</sub></span>
                            <span class="nt">B<sub class="attr">t</sub></span>
                            <span class="comp">p = q ∗ t</span><br>
                            <span style="margin-left:110px;" class="comp">r = q + t</span><br>

                            <span class="rule-num">2.</span>
                            <span class="nt">A<sub class="attr">p,q</sub></span>
                            <span class="arr">→</span>
                            <span class="t">b<sub class="attr">r</sub></span>
                            <span class="nt">A<sub class="attr">t,u</sub></span>
                            <span class="t">c</span>
                            <span class="comp">u = r</span><br>
                            <span style="margin-left:110px;" class="comp">p = r + t + u</span><br>

                            <span class="rule-num">3.</span>
                            <span class="nt">A<sub class="attr">p,q</sub></span>
                            <span class="arr">→</span>
                            <span class="eps">ε</span>
                            <span class="comp">p = 0</span><br>

                            <span class="rule-num">4.</span>
                            <span class="nt">B<sub class="attr">p</sub></span>
                            <span class="arr">→</span>
                            <span class="t">a<sub class="attr">p</sub></span>
                        </div>
                        <p style="font-size:13.5px; margin-top:12px;">
                            Show attributed derivation trees for:
                        </p>
                        <div class="grammar-box" style="line-height:2; font-size:13px;">
                            (a) <span class="t">a<sub class="attr">2</sub></span><br>
                            (b) <span class="t">b<sub class="attr">1</sub>ca<sub class="attr">3</sub></span><br>
                            (c) <span class="t">b<sub class="attr">2</sub>b<sub class="attr">3</sub>cca<sub class="attr">4</sub></span>
                        </div>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 4 — Feasibility of Recursive Descent</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            Is it possible to write a recursive descent parser for the attributed translation
                            grammar of Problem 3?
                        </p>
                    </div>
                </div>
            </section>

            <!-- ── Chapter nav ──────────────────────────────── -->
            <div class="chapter-nav">
                <a href="c4_5.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous</span>4.5 Syntax-Directed Translation</div>
                </a>
                <a href="?complete=1" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.7 Next Topic
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