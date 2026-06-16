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
$page     = 8;
$nextPage = "c4_9.php"; // adjust as needed

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
    <title>Chapter 4.8 – CompileX</title>

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
        .grammar-box .attr { color: #F9E2AF; font-size: 11px; vertical-align: sub; }
        .grammar-box .comp { color: #89DCEB; font-size: 12px; margin-left: 18px; }
        .grammar-box .act  { color: #F38BA8; font-weight: 700; }

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

        /* ── Atom table ──────────────────────────────────────── */
        .atom-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            margin: 14px 0;
        }
        .atom-table th {
            background: var(--purple-s, #EEF2FF);
            color: var(--purple, #6366F1);
            padding: 9px 14px;
            text-align: left;
            font-weight: 700;
            font-size: 12px;
            border: 1px solid rgba(99,102,241,.15);
        }
        .atom-table td {
            padding: 8px 14px;
            border: 1px solid rgba(99,102,241,.1);
            color: var(--text, #1E1B4B);
            vertical-align: top;
            line-height: 1.55;
        }
        .atom-table td.atom-name {
            font-weight: 700;
            color: #B45309;
            background: #FFF7ED;
            white-space: nowrap;
        }
        .atom-table tr:nth-child(even) td { background: rgba(99,102,241,.025); }
        .atom-table tr:nth-child(even) td.atom-name { background: #FFF7ED; }

        /* ── Comparison code table ───────────────────────────── */
        .comp-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            margin: 14px 0;
        }
        .comp-table th {
            background: var(--purple-s, #EEF2FF);
            color: var(--purple, #6366F1);
            padding: 9px 14px;
            text-align: center;
            font-weight: 700;
            font-size: 12px;
            border: 1px solid rgba(99,102,241,.15);
        }
        .comp-table td {
            padding: 8px 14px;
            border: 1px solid rgba(99,102,241,.1);
            text-align: center;
            color: var(--text, #1E1B4B);
        }
        .comp-table td.op-sym  { font-weight: 700; color: #6366F1; }
        .comp-table td.comp-num { color: #B45309; font-weight: 700; }
        .comp-table td.complement { color: #065F46; font-weight: 700; }
        .comp-table tr:nth-child(even) td { background: rgba(99,102,241,.03); }

        /* ── Code sample boxes (if/while) ───────────────────── */
        .source-atom-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin: 16px 0;
        }
        @media (max-width: 680px) { .source-atom-grid { grid-template-columns: 1fr; } }

        .sa-box {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(99,102,241,.15);
        }
        .sa-header {
            background: var(--purple-s, #EEF2FF);
            padding: 8px 16px;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 13px;
            color: var(--purple, #6366F1);
        }
        .sa-body {
            background: #1A1830;
            padding: 16px 18px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: #CDD6F4;
            line-height: 2;
        }
        .sa-body .kw   { color: #CBA6F7; }
        .sa-body .atom { color: #F38BA8; font-weight: 700; }
        .sa-body .lbl  { color: #F9E2AF; font-weight: 700; }
        .sa-body .cm   { color: #6C7086; font-style: italic; }

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
            overflow-x: auto;
        }
        .tree-wrap figcaption {
            font-size: 12px;
            color: var(--muted, #6B7280);
            margin-top: 10px;
            text-align: center;
        }

        /* ── Inline code example ──────────────────────────────── */
        .code-block {
            background: #1A1830;
            border-radius: 12px;
            padding: 18px 22px;
            margin: 14px 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: #CDD6F4;
            line-height: 1.9;
            overflow-x: auto;
        }
        .code-block .kw  { color: #CBA6F7; }
        .code-block .cm  { color: #6C7086; font-style: italic; }
        .code-block .str { color: #A6E3A1; }
        .code-block .atom-ref { color: #F38BA8; font-weight: 700; }

        /* ── Selection set grid ───────────────────────────────── */
        .sel-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            margin: 14px 0;
        }
        .sel-card {
            background: var(--purple-s, #EEF2FF);
            border: 1.5px solid rgba(99,102,241,.2);
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
        .sel-card .sel-val { color: var(--text, #1E1B4B); font-size: 13px; }
        .sel-card.ok { border-color: rgba(16,185,129,.35); background: #F0FDF4; }
        .sel-card.ok .rule-id { color: #065F46; }

        /* ── Output sequence display ──────────────────────────── */
        .output-seq {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin: 14px 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
        }
        .output-seq .atom-line {
            background: #FFF7ED;
            border: 1.5px solid rgba(245,158,11,.25);
            border-radius: 8px;
            padding: 6px 14px;
            color: #92400E;
            font-weight: 600;
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
                <li><a href="#intro"        class="toc-link">4.8 Decaf Expressions</a></li>
                <li><a href="#new-atoms"    class="toc-link">New Atoms (Fig 4.17)</a></li>
                <li><a href="#bool-expr"    class="toc-link">Boolean Expressions</a></li>
                <li><a href="#comp-codes"   class="toc-link">Comparison Codes</a></li>
                <li><a href="#if-while"     class="toc-link">if / while Translation</a></li>
                <li><a href="#boolexpr-rule" class="toc-link">BoolExpr Grammar Rule</a></li>
                <li><a href="#assignment"   class="toc-link">Assignment</a></li>
                <li><a href="#full-grammar" class="toc-link">Full Decaf Grammar</a></li>
                <li><a href="#sample"       class="toc-link">Sample Problem 4.8</a></li>
                <li><a href="#exercises"    class="toc-link">Exercises 4.8</a></li>
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
                <h1>Decaf Expressions</h1>
                <div class="metadata">
                    <span>⏱ 30 min read</span>
                    <span>🎯 Advanced</span>
                    <span>⚙️ Translation Grammars</span>
                </div>
            </header>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION 4.8 – Introduction                      -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="intro">
                <h2><span class="sec-icon">🔍</span> Introduction</h2>

                <p>
                    Thus far we have seen how to translate simple infix expressions involving only addition
                    and multiplication into a stream of atoms. Obviously, we also need to include
                    <strong>subtraction</strong> and <strong>division</strong> operators in our language, but
                    this is straightforward, since subtraction has the same precedence as addition and division
                    has the same precedence as multiplication.
                </p>
                <p>
                    In this section, we extend the notion of expression to include <strong>comparisons</strong>
                    (boolean expressions) and <strong>assignments</strong>. Boolean expressions are expressions
                    such as <code>x &gt; y</code>, or <code>y2 == 33</code>.
                </p>

                <div class="note-box key">
                    <span class="box-icon">📖</span>
                    <div>
                        <strong>Java vs C/C++ comparisons:</strong> For those familiar with C and C++, the
                        comparison operators return ints (<code>0 = false</code>, <code>1 = true</code>), but
                        Java makes a distinction — the comparison operators return <strong>boolean</strong>
                        results (<code>true</code> or <code>false</code>). If you have ever spent hours debugging
                        a C/C++ program which contained <code>if (x=3)...</code> when you really intended
                        <code>if (x==3)...</code>, you understand the reason for this change. The Java compiler
                        will catch this error for you.
                    </div>
                </div>

                <p>
                    Assignment is also slightly different in Java. In C/C++, assignment is an operator which
                    produces a result in addition to the side effect of assigning a value to a variable. A
                    statement may be formed by following any expression with a semicolon. This is not the case
                    in Java — the expression statement must be an <strong>assignment</strong> or a
                    <strong>method call</strong>. Since there are no methods in Decaf, we are left with
                    an assignment.
                </p>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – New Atoms (Figure 4.17)               -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="new-atoms">
                <h2><span class="sec-icon">⚛️</span> New Atoms — Figure 4.17</h2>

                <p>
                    At this point we need to introduce three new atoms for <strong>transfer of control</strong>:
                    <strong>LBL</strong> (label), <strong>JMP</strong> (jump / unconditional branch), and
                    <strong>TST</strong> (test / conditional branch).
                </p>

                <table class="atom-table">
                    <thead>
                        <tr>
                            <th>Atom</th>
                            <th>Attributes</th>
                            <th>Purpose</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="atom-name">LBL</td>
                            <td>label name</td>
                            <td>Mark a spot to be used as a branch destination. A LBL atom is a placemarker in the atom stream — ultimately representing the run-time memory address of an instruction in the object program.</td>
                        </tr>
                        <tr>
                            <td class="atom-name">JMP</td>
                            <td>label name</td>
                            <td>Unconditional branch to the label specified. Always accompanied by a label name — the destination for the branch.</td>
                        </tr>
                        <tr>
                            <td class="atom-name">TST</td>
                            <td>Expr1, Expr2, comparison code, label name</td>
                            <td>Conditional branch. Compare Expr1 and Expr2 using the comparison code. Branch to the label if the result is <strong>true</strong>.</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box pro">
                    <span class="box-icon">📌</span>
                    <div>
                        <strong>TST atom in detail:</strong> <code>{TST}a,b,,c,x</code> will compare the
                        values stored at <code>a</code> and <code>b</code>, using the comparison whose code
                        is <code>c</code>, and branch to label <code>x</code> if the comparison is
                        <strong>true</strong>.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Boolean Expressions & if/while        -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="bool-expr">
                <h2><span class="sec-icon">🔀</span> Boolean Expressions</h2>

                <p>
                    Every time we need to use a boolean expression in a statement, we really want to put out a
                    <strong>TST atom that branches when the comparison is false</strong>. The following two
                    examples show an <code>if</code> statement and a <code>while</code> statement. The source
                    input is at the left, and the atoms to be put out are indented to the right.
                </p>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – if / while translation examples       -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="if-while">
                <h2><span class="sec-icon">↔️</span> if and while Translation</h2>

                <div class="source-atom-grid">
                    <div class="sa-box">
                        <div class="sa-header">if statement</div>
                        <div class="sa-body">
<span class="kw">if</span><br>
(<span class="str">x==3</span>)<br>
<span class="atom">[TST]</span> <span class="cm">// Branch to Label only if</span><br>
<span class="kw">stmt</span>  <span class="cm">// x==3 is false</span><br>
<span class="lbl">[Label]</span>
                        </div>
                    </div>
                    <div class="sa-box">
                        <div class="sa-header">while statement</div>
                        <div class="sa-body">
<span class="kw">while</span><br>
<span class="lbl">[Label1]</span><br>
(<span class="str">x&gt;2</span>)<br>
<span class="atom">[TST]</span> <span class="cm">// Branch to Label2 only if</span><br>
<span class="kw">stmt</span>  <span class="cm">// x&gt;2 is false</span><br>
<span class="atom">[JMP]</span> <span class="cm">// Unconditional branch to Label1</span><br>
<span class="lbl">[Label2]</span>
                        </div>
                    </div>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        The label atom itself (LBL) will be handled later, in <strong>Section 4.9</strong>.
                        To process a boolean expression, all we need to do here is put out a TST atom which
                        allocates a new label name and branches to that label when the comparison is
                        <strong>false</strong>.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Comparison Codes                      -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="comp-codes">
                <h2><span class="sec-icon">🔢</span> Comparison Codes</h2>

                <p>
                    Recall our six comparison codes. To get the <strong>logical complement</strong> of any
                    comparison, we simply subtract the code from <strong>7</strong>:
                </p>

                <table class="comp-table">
                    <thead>
                        <tr>
                            <th>Comparison</th>
                            <th>Code</th>
                            <th>Logical Complement</th>
                            <th>Code for Complement</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="op-sym">==</td>
                            <td class="comp-num">1</td>
                            <td class="op-sym">!=</td>
                            <td class="complement">6</td>
                        </tr>
                        <tr>
                            <td class="op-sym">&lt;</td>
                            <td class="comp-num">2</td>
                            <td class="op-sym">&gt;=</td>
                            <td class="complement">5</td>
                        </tr>
                        <tr>
                            <td class="op-sym">&gt;</td>
                            <td class="comp-num">3</td>
                            <td class="op-sym">&lt;=</td>
                            <td class="complement">4</td>
                        </tr>
                        <tr>
                            <td class="op-sym">&lt;=</td>
                            <td class="comp-num">4</td>
                            <td class="op-sym">&gt;</td>
                            <td class="complement">3</td>
                        </tr>
                        <tr>
                            <td class="op-sym">&gt;=</td>
                            <td class="comp-num">5</td>
                            <td class="op-sym">&lt;</td>
                            <td class="complement">2</td>
                        </tr>
                        <tr>
                            <td class="op-sym">!=</td>
                            <td class="comp-num">6</td>
                            <td class="op-sym">==</td>
                            <td class="complement">1</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        The formula <code>7 − c</code> gives the complement of comparison code <code>c</code>.
                        Since we want the TST atom to branch when the comparison is <strong>false</strong>, we
                        use <code>7 − c</code> as the code in the TST atom — effectively inverting the sense
                        of the comparison.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – BoolExpr Grammar Rule                 -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="boolexpr-rule">
                <h2><span class="sec-icon">📐</span> BoolExpr Grammar Rule</h2>

                <p>
                    The attributed grammar rule for a boolean expression is:
                </p>

                <div class="grammar-box">
                    <span class="nt">BoolExpr<sub class="attr">Lbl</sub></span>
                    <span class="arr">→</span>
                    <span class="nt">Expr<sub class="attr">p</sub></span>
                    <span class="t">compare<sub class="attr">c</sub></span>
                    <span class="nt">Expr<sub class="attr">q</sub></span>
                    <span class="act">{TST}<sub class="attr">p,q,,7-c,Lbl</sub></span>
                </div>

                <div class="note-box key">
                    <span class="box-icon">📖</span>
                    <div>
                        <strong><code>{TST}a,b,,c,x</code></strong> will compare the values stored at
                        <code>a</code> and <code>b</code>, using the comparison whose code is <code>c</code>,
                        and branch to label <code>x</code> if the comparison is <strong>true</strong>.
                        <br><br>
                        In the grammar rule above:
                        <ul style="margin:8px 0 0 16px; font-size:13.5px;">
                            <li>The attribute of <code>BoolExpr</code>, <code>Lbl</code>, is
                                <strong>synthesized</strong> and represents the target label for the TST atom.</li>
                            <li>The attribute of the token <code>compare</code>, <code>c</code>, is an integer
                                from 1–6 representing the comparison code.</li>
                            <li>The use of comparison code <code>7−c</code> <strong>inverts the sense</strong>
                                of the comparison as desired (branch when comparison is false).</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Assignment                            -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="assignment">
                <h2><span class="sec-icon">🟰</span> Assignment</h2>

                <p>
                    An assignment is an operator which returns a result that can be used as part of a larger
                    expression. For example:
                </p>

                <div class="code-block">
x = (y = 2) + (z = 3);  <span class="cm">// y is 2, z is 3, x is 5</span>
                </div>

                <p>
                    This means we will need to put out a <strong>MOV atom</strong> to implement the assignment,
                    in addition to giving it a synthesized attribute to be moved up the tree. The left operand
                    of the assignment must be an <strong>identifier</strong>, or what is often called an
                    <em>lvalue</em>.
                </p>

                <div class="note-box pro">
                    <span class="box-icon">📌</span>
                    <div>
                        Unlike the arithmetic operators, assignment <strong>associates to the right</strong>,
                        which permits multiple assignments such as:
                        <br>
                        <code style="font-family:'JetBrains Mono',monospace;">x = y = z = 0;  // x, y, and z are now all 0.</code>
                    </div>
                </div>

                <p>We could use a translation grammar such as the following:</p>

                <div class="grammar-box">
                    <span class="nt">Expr<sub class="attr">p</sub></span>
                    <span class="arr">→</span>
                    <span class="nt">AssignExpr<sub class="attr">p</sub></span><br>
                    <span class="nt">AssignExpr<sub class="attr">p</sub></span>
                    <span class="arr">→</span>
                    <span class="t">ident<sub class="attr">p</sub></span>
                    <span class="t">=</span>
                    <span class="nt">Expr<sub class="attr">q</sub></span>
                    <span class="act">{MOV}<sub class="attr">q,,p</sub></span>
                </div>

                <p>
                    The location of the result is the same as the location of the identifier receiving the
                    value. The attribute of <code>Expr</code> is synthesized. The output for an expression
                    such as <code>a = b + (c = 3)</code> will be:
                </p>

                <div class="output-seq">
                    <div class="atom-line">(MOV, 3, , c)</div>
                    <div class="atom-line">(ADD, b, c, T1)</div>
                    <div class="atom-line">(MOV, T1, , a)</div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Full Decaf Grammar                    -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="full-grammar">
                <h2><span class="sec-icon">📋</span> Full Attributed Translation Grammar for Decaf Expressions</h2>

                <p>
                    An attributed translation grammar for Decaf expressions involving addition, subtraction,
                    multiplication, division, comparisons, and assignment is shown below:
                </p>

                <div class="grammar-box">
                    <span class="rule-num">1.</span>
                    <span class="nt">BoolExpr<sub class="attr">L1</sub></span>
                    <span class="arr">→</span>
                    <span class="nt">Expr<sub class="attr">p</sub></span>
                    <span class="t">compare<sub class="attr">c</sub></span>
                    <span class="nt">Expr<sub class="attr">q</sub></span>
                    <span class="act">{TST}<sub class="attr">p,q,,7-c,L1</sub></span>
                    <span class="comp">L1 = newLabel()</span><br>

                    <span class="rule-num">2.</span>
                    <span class="nt">Expr<sub class="attr">p</sub></span>
                    <span class="arr">→</span>
                    <span class="nt">AssignExpr<sub class="attr">p</sub></span><br>

                    <span class="rule-num">3.</span>
                    <span class="nt">Expr<sub class="attr">p</sub></span>
                    <span class="arr">→</span>
                    <span class="nt">Rvalue<sub class="attr">p</sub></span><br>

                    <span class="rule-num">4.</span>
                    <span class="nt">AssignExpr<sub class="attr">p</sub></span>
                    <span class="arr">→</span>
                    <span class="t">ident<sub class="attr">p</sub></span>
                    <span class="t">=</span>
                    <span class="nt">Expr<sub class="attr">q</sub></span>
                    <span class="act">{MOV}<sub class="attr">q,,p</sub></span><br>

                    <span class="rule-num">5.</span>
                    <span class="nt">Rvalue<sub class="attr">p</sub></span>
                    <span class="arr">→</span>
                    <span class="nt">Term<sub class="attr">q</sub></span>
                    <span class="nt">Elist<sub class="attr">q,p</sub></span><br>

                    <span class="rule-num">6.</span>
                    <span class="nt">Elist<sub class="attr">p,q</sub></span>
                    <span class="arr">→</span>
                    <span class="t">+</span>
                    <span class="nt">Term<sub class="attr">r</sub></span>
                    <span class="act">{ADD}<sub class="attr">p,r,s</sub></span>
                    <span class="nt">Elist<sub class="attr">s,q</sub></span>
                    <span class="comp">s = alloc()</span><br>

                    <span class="rule-num">7.</span>
                    <span class="nt">Elist<sub class="attr">p,q</sub></span>
                    <span class="arr">→</span>
                    <span class="t">-</span>
                    <span class="nt">Term<sub class="attr">r</sub></span>
                    <span class="act">{SUB}<sub class="attr">p,r,s</sub></span>
                    <span class="nt">Elist<sub class="attr">s,q</sub></span>
                    <span class="comp">s = alloc()</span><br>

                    <span class="rule-num">8.</span>
                    <span class="nt">Elist<sub class="attr">p,q</sub></span>
                    <span class="arr">→</span>
                    <span class="eps">ε</span>
                    <span class="comp">q = p</span><br>

                    <span class="rule-num">9.</span>
                    <span class="nt">Term<sub class="attr">p</sub></span>
                    <span class="arr">→</span>
                    <span class="nt">Factor<sub class="attr">q</sub></span>
                    <span class="nt">Tlist<sub class="attr">q,p</sub></span><br>

                    <span class="rule-num">10.</span>
                    <span class="nt">Tlist<sub class="attr">p,q</sub></span>
                    <span class="arr">→</span>
                    <span class="t">*</span>
                    <span class="nt">Factor<sub class="attr">r</sub></span>
                    <span class="act">{MUL}<sub class="attr">p,r,s</sub></span>
                    <span class="nt">Tlist<sub class="attr">s,q</sub></span>
                    <span class="comp">s = alloc()</span><br>

                    <span class="rule-num">11.</span>
                    <span class="nt">Tlist<sub class="attr">p,q</sub></span>
                    <span class="arr">→</span>
                    <span class="t">/</span>
                    <span class="nt">Factor<sub class="attr">r</sub></span>
                    <span class="act">{DIV}<sub class="attr">p,r,s</sub></span>
                    <span class="nt">Tlist<sub class="attr">s,q</sub></span>
                    <span class="comp">s = alloc()</span><br>

                    <span class="rule-num">12.</span>
                    <span class="nt">Tlist<sub class="attr">p,q</sub></span>
                    <span class="arr">→</span>
                    <span class="eps">ε</span>
                    <span class="comp">q = p</span><br>

                    <span class="rule-num">13.</span>
                    <span class="nt">Factor<sub class="attr">p</sub></span>
                    <span class="arr">→</span>
                    <span class="t">(</span>
                    <span class="nt">Expr<sub class="attr">p</sub></span>
                    <span class="t">)</span><br>

                    <span class="rule-num">14.</span>
                    <span class="nt">Factor<sub class="attr">p</sub></span>
                    <span class="arr">→</span>
                    <span class="t">+</span>
                    <span class="nt">Factor<sub class="attr">p</sub></span><br>

                    <span class="rule-num">15.</span>
                    <span class="nt">Factor<sub class="attr">p</sub></span>
                    <span class="arr">→</span>
                    <span class="t">-</span>
                    <span class="nt">Factor<sub class="attr">q</sub></span>
                    <span class="act">{Neg}<sub class="attr">q,,p</sub></span>
                    <span class="comp">p = alloc()</span><br>

                    <span class="rule-num">16.</span>
                    <span class="nt">Factor<sub class="attr">p</sub></span>
                    <span class="arr">→</span>
                    <span class="t">num<sub class="attr">p</sub></span><br>

                    <span class="rule-num">17.</span>
                    <span class="nt">Factor<sub class="attr">p</sub></span>
                    <span class="arr">→</span>
                    <span class="t">ident<sub class="attr">p</sub></span>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Sample Problem 4.8                    -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="sample">
                <h2><span class="sec-icon">🧪</span> Sample Problem 4.8</h2>

                <div class="note-box key">
                    <span class="box-icon">📖</span>
                    <div>
                        <strong>Problem:</strong> Using the grammar for Decaf expressions given in this
                        section, show an attributed derivation tree for the boolean expression
                        <code>a == (b = 3) + c</code>.
                    </div>
                </div>

                <p>
                    <strong>Solution:</strong> We start with <code>BoolExpr</code> as the root. The input
                    <code>a == (b=3)+c</code> has a <code>==</code> comparison (code 1), so the TST atom will
                    use code <code>7−1 = 6</code> (which is <code>!=</code>, branching when equal is false).
                </p>

                <div class="tree-wrap">
                    <svg viewBox="0 0 780 440" width="740" xmlns="http://www.w3.org/2000/svg"
                         font-family="JetBrains Mono, monospace" font-size="11">

                        <!-- ── Level 0: BoolExpr root ── -->
                        <rect x="270" y="4" width="130" height="26" rx="8" fill="#6366F1"/>
                        <text x="335" y="21" text-anchor="middle" fill="white" font-weight="700" font-size="11">BoolExpr<tspan fill="#F9E2AF" font-size="9" dy="3">L1</tspan></text>

                        <!-- BoolExpr → Expr_a  compare_1  Expr_T1  {TST} -->
                        <line x1="335" y1="30" x2="110" y2="72" stroke="#6366F1" stroke-width="1.4"/>
                        <line x1="335" y1="30" x2="280" y2="72" stroke="#6366F1" stroke-width="1.4"/>
                        <line x1="335" y1="30" x2="430" y2="72" stroke="#6366F1" stroke-width="1.4"/>
                        <line x1="335" y1="30" x2="620" y2="72" stroke="#6366F1" stroke-width="1.4"/>

                        <!-- ── Level 1 ── -->
                        <!-- Expr_a -->
                        <circle cx="110" cy="82" r="18" fill="#6366F1"/>
                        <text x="110" y="79" text-anchor="middle" fill="white" font-weight="700" font-size="10">Expr</text>
                        <text x="110" y="90" text-anchor="middle" fill="#F9E2AF" font-size="9" font-weight="700">a</text>

                        <!-- compare_1 -->
                        <circle cx="280" cy="82" r="18" fill="#A6E3A1" opacity=".9"/>
                        <text x="280" y="79" text-anchor="middle" fill="#065F46" font-weight="700" font-size="10">comp</text>
                        <text x="280" y="90" text-anchor="middle" fill="#065F46" font-size="9" font-weight="700">1(==)</text>

                        <!-- Expr_T1 -->
                        <circle cx="430" cy="82" r="18" fill="#6366F1"/>
                        <text x="430" y="79" text-anchor="middle" fill="white" font-weight="700" font-size="10">Expr</text>
                        <text x="430" y="90" text-anchor="middle" fill="#F9E2AF" font-size="9" font-weight="700">T1</text>

                        <!-- {TST} action -->
                        <rect x="590" y="70" width="80" height="24" rx="6" fill="#FFF7ED" stroke="#F97316" stroke-width="1.2"/>
                        <text x="630" y="86" text-anchor="middle" fill="#B45309" font-weight="700" font-size="10">{TST}a,T1,,6,L1</text>

                        <!-- ── Expr_a → Rvalue_a ── -->
                        <line x1="110" y1="100" x2="110" y2="132" stroke="#6366F1" stroke-width="1.4"/>
                        <circle cx="110" cy="142" r="18" fill="#CBA6F7" opacity=".9"/>
                        <text x="110" y="139" text-anchor="middle" fill="#3730A3" font-weight="700" font-size="10">Rvalue</text>
                        <text x="110" y="150" text-anchor="middle" fill="#F9E2AF" font-size="9" font-weight="700">a</text>

                        <!-- Rvalue_a → Term_a Elist_a,a -->
                        <line x1="110" y1="160" x2="70"  y2="195" stroke="#6366F1" stroke-width="1.4"/>
                        <line x1="110" y1="160" x2="145" y2="195" stroke="#6366F1" stroke-width="1.4"/>

                        <circle cx="70"  cy="205" r="16" fill="#6366F1"/>
                        <text x="70"  y="202" text-anchor="middle" fill="white" font-weight="700" font-size="10">Term</text>
                        <text x="70"  y="213" text-anchor="middle" fill="#F9E2AF" font-size="9">a</text>

                        <circle cx="145" cy="205" r="16" fill="#CBA6F7" opacity=".85"/>
                        <text x="145" y="202" text-anchor="middle" fill="#3730A3" font-weight="700" font-size="10">Elist</text>
                        <text x="145" y="213" text-anchor="middle" fill="#F9E2AF" font-size="9">a,a</text>

                        <!-- Term_a → Factor_a Tlist_a,a -->
                        <line x1="70" y1="221" x2="45"  y2="255" stroke="#6366F1" stroke-width="1.4"/>
                        <line x1="70" y1="221" x2="90"  y2="255" stroke="#6366F1" stroke-width="1.4"/>

                        <circle cx="45"  cy="265" r="17" fill="#CBA6F7" opacity=".85"/>
                        <text x="45"  y="262" text-anchor="middle" fill="#3730A3" font-weight="700" font-size="10">Factor</text>
                        <text x="45"  y="273" text-anchor="middle" fill="#F9E2AF" font-size="9">a</text>

                        <circle cx="95" cy="265" r="17" fill="#CBA6F7" opacity=".85"/>
                        <text x="95"  y="262" text-anchor="middle" fill="#3730A3" font-weight="700" font-size="10">Tlist</text>
                        <text x="95"  y="273" text-anchor="middle" fill="#F9E2AF" font-size="9">a,a</text>

                        <!-- Factor_a → ident_a -->
                        <line x1="45" y1="282" x2="45" y2="314" stroke="#6366F1" stroke-width="1.4"/>
                        <circle cx="45" cy="324" r="14" fill="#A6E3A1" opacity=".9"/>
                        <text x="45" y="321" text-anchor="middle" fill="#065F46" font-weight="700" font-size="10">ident</text>
                        <text x="45" y="331" text-anchor="middle" fill="#065F46" font-size="9">a</text>

                        <!-- Tlist → ε -->
                        <line x1="95" y1="282" x2="95" y2="308" stroke="#6366F1" stroke-width="1.4"/>
                        <text x="95" y="318" text-anchor="middle" fill="#FAB387" font-weight="600" font-size="13">ε</text>

                        <!-- Elist_a,a → ε -->
                        <line x1="145" y1="221" x2="145" y2="248" stroke="#6366F1" stroke-width="1.4"/>
                        <text x="145" y="258" text-anchor="middle" fill="#FAB387" font-weight="600" font-size="13">ε</text>

                        <!-- ── Expr_T1 → Rvalue_T1 ── -->
                        <line x1="430" y1="100" x2="430" y2="132" stroke="#6366F1" stroke-width="1.4"/>
                        <circle cx="430" cy="142" r="18" fill="#CBA6F7" opacity=".9"/>
                        <text x="430" y="139" text-anchor="middle" fill="#3730A3" font-weight="700" font-size="10">Rvalue</text>
                        <text x="430" y="150" text-anchor="middle" fill="#F9E2AF" font-size="9" font-weight="700">T1</text>

                        <!-- Rvalue_T1 → Term_b Elist_b,T1 -->
                        <line x1="430" y1="160" x2="360" y2="195" stroke="#6366F1" stroke-width="1.4"/>
                        <line x1="430" y1="160" x2="510" y2="195" stroke="#6366F1" stroke-width="1.4"/>

                        <circle cx="360" cy="205" r="16" fill="#6366F1"/>
                        <text x="360" y="202" text-anchor="middle" fill="white" font-weight="700" font-size="10">Term</text>
                        <text x="360" y="213" text-anchor="middle" fill="#F9E2AF" font-size="9">b</text>

                        <circle cx="510" cy="205" r="16" fill="#CBA6F7" opacity=".85"/>
                        <text x="510" y="202" text-anchor="middle" fill="#3730A3" font-weight="700" font-size="10">Elist</text>
                        <text x="510" y="213" text-anchor="middle" fill="#F9E2AF" font-size="9">b,T1</text>

                        <!-- Term_b → Factor_b Tlist_b,b -->
                        <line x1="360" y1="221" x2="330" y2="255" stroke="#6366F1" stroke-width="1.4"/>
                        <line x1="360" y1="221" x2="385" y2="255" stroke="#6366F1" stroke-width="1.4"/>

                        <circle cx="330" cy="265" r="17" fill="#CBA6F7" opacity=".85"/>
                        <text x="330" y="262" text-anchor="middle" fill="#3730A3" font-weight="700" font-size="10">Factor</text>
                        <text x="330" y="273" text-anchor="middle" fill="#F9E2AF" font-size="9">b</text>

                        <circle cx="385" cy="265" r="17" fill="#CBA6F7" opacity=".85"/>
                        <text x="385" y="262" text-anchor="middle" fill="#3730A3" font-weight="700" font-size="10">Tlist</text>
                        <text x="385" y="273" text-anchor="middle" fill="#F9E2AF" font-size="9">b,b</text>

                        <!-- Factor_b → AssignExpr_b (rule 13 path: ( Expr_b )) -->
                        <line x1="330" y1="282" x2="310" y2="310" stroke="#6366F1" stroke-width="1.4"/>
                        <line x1="330" y1="282" x2="330" y2="310" stroke="#6366F1" stroke-width="1.4"/>
                        <line x1="330" y1="282" x2="355" y2="310" stroke="#6366F1" stroke-width="1.4"/>

                        <circle cx="310" cy="320" r="12" fill="#A6E3A1" opacity=".9"/>
                        <text x="310" y="325" text-anchor="middle" fill="#065F46" font-weight="700" font-size="11">(</text>

                        <circle cx="330" cy="320" r="16" fill="#6366F1"/>
                        <text x="330" y="317" text-anchor="middle" fill="white" font-weight="700" font-size="10">Expr</text>
                        <text x="330" y="328" text-anchor="middle" fill="#F9E2AF" font-size="9">b</text>

                        <circle cx="355" cy="320" r="12" fill="#A6E3A1" opacity=".9"/>
                        <text x="355" y="325" text-anchor="middle" fill="#065F46" font-weight="700" font-size="11">)</text>

                        <!-- Expr_b → AssignExpr_b → ident_b = Expr_3 {MOV} -->
                        <line x1="330" y1="336" x2="330" y2="360" stroke="#6366F1" stroke-width="1.4"/>
                        <circle cx="330" cy="370" r="18" fill="#CBA6F7" opacity=".85"/>
                        <text x="330" y="367" text-anchor="middle" fill="#3730A3" font-weight="700" font-size="10">AssignExpr</text>
                        <text x="330" y="378" text-anchor="middle" fill="#F9E2AF" font-size="9">b</text>

                        <!-- AssignExpr_b → ident_b = Expr_3 {MOV} -->
                        <line x1="330" y1="388" x2="285" y2="412" stroke="#6366F1" stroke-width="1.4"/>
                        <line x1="330" y1="388" x2="315" y2="412" stroke="#6366F1" stroke-width="1.4"/>
                        <line x1="330" y1="388" x2="345" y2="412" stroke="#6366F1" stroke-width="1.4"/>
                        <line x1="330" y1="388" x2="380" y2="412" stroke="#6366F1" stroke-width="1.4"/>

                        <circle cx="285" cy="422" r="14" fill="#A6E3A1" opacity=".9"/>
                        <text x="285" y="419" text-anchor="middle" fill="#065F46" font-weight="700" font-size="10">ident</text>
                        <text x="285" y="429" text-anchor="middle" fill="#065F46" font-size="9">b</text>

                        <circle cx="315" cy="422" r="12" fill="#A6E3A1" opacity=".9"/>
                        <text x="315" y="427" text-anchor="middle" fill="#065F46" font-weight="700" font-size="11">=</text>

                        <circle cx="345" cy="422" r="16" fill="#6366F1"/>
                        <text x="345" y="419" text-anchor="middle" fill="white" font-weight="700" font-size="10">Expr</text>
                        <text x="345" y="429" text-anchor="middle" fill="#F9E2AF" font-size="9">3</text>

                        <rect x="362" y="410" width="52" height="22" rx="5" fill="#FFF7ED" stroke="#F97316" stroke-width="1.2"/>
                        <text x="388" y="424" text-anchor="middle" fill="#B45309" font-weight="700" font-size="9">{MOV}3,,b</text>

                        <!-- Tlist_b,b → ε -->
                        <line x1="385" y1="282" x2="385" y2="308" stroke="#6366F1" stroke-width="1.4"/>
                        <text x="385" y="318" text-anchor="middle" fill="#FAB387" font-weight="600" font-size="13">ε</text>

                        <!-- Elist_b,T1 → + Term_c {ADD} Elist_T1,T1 -->
                        <line x1="510" y1="221" x2="460" y2="255" stroke="#6366F1" stroke-width="1.4"/>
                        <line x1="510" y1="221" x2="510" y2="255" stroke="#6366F1" stroke-width="1.4"/>
                        <line x1="510" y1="221" x2="560" y2="255" stroke="#6366F1" stroke-width="1.4"/>
                        <line x1="510" y1="221" x2="620" y2="255" stroke="#6366F1" stroke-width="1.4"/>

                        <circle cx="460" cy="265" r="12" fill="#A6E3A1" opacity=".9"/>
                        <text x="460" y="270" text-anchor="middle" fill="#065F46" font-weight="700" font-size="11">+</text>

                        <circle cx="510" cy="265" r="16" fill="#6366F1"/>
                        <text x="510" y="262" text-anchor="middle" fill="white" font-weight="700" font-size="10">Term</text>
                        <text x="510" y="273" text-anchor="middle" fill="#F9E2AF" font-size="9">c</text>

                        <rect x="536" y="253" width="56" height="22" rx="5" fill="#FFF7ED" stroke="#F97316" stroke-width="1.2"/>
                        <text x="564" y="267" text-anchor="middle" fill="#B45309" font-weight="700" font-size="9">{ADD}b,c,T1</text>

                        <circle cx="620" cy="265" r="16" fill="#CBA6F7" opacity=".85"/>
                        <text x="620" y="262" text-anchor="middle" fill="#3730A3" font-weight="700" font-size="10">Elist</text>
                        <text x="620" y="273" text-anchor="middle" fill="#F9E2AF" font-size="9">T1,T1</text>

                        <!-- Term_c → Factor_c Tlist_c,c -->
                        <line x1="510" y1="281" x2="490" y2="312" stroke="#6366F1" stroke-width="1.4"/>
                        <line x1="510" y1="281" x2="530" y2="312" stroke="#6366F1" stroke-width="1.4"/>

                        <circle cx="490" cy="322" r="17" fill="#CBA6F7" opacity=".85"/>
                        <text x="490" y="319" text-anchor="middle" fill="#3730A3" font-weight="700" font-size="10">Factor</text>
                        <text x="490" y="330" text-anchor="middle" fill="#F9E2AF" font-size="9">c</text>

                        <circle cx="535" cy="322" r="17" fill="#CBA6F7" opacity=".85"/>
                        <text x="535" y="319" text-anchor="middle" fill="#3730A3" font-weight="700" font-size="10">Tlist</text>
                        <text x="535" y="330" text-anchor="middle" fill="#F9E2AF" font-size="9">c,c</text>

                        <!-- Factor_c → ident_c -->
                        <line x1="490" y1="339" x2="490" y2="368" stroke="#6366F1" stroke-width="1.4"/>
                        <circle cx="490" cy="378" r="14" fill="#A6E3A1" opacity=".9"/>
                        <text x="490" y="375" text-anchor="middle" fill="#065F46" font-weight="700" font-size="10">ident</text>
                        <text x="490" y="385" text-anchor="middle" fill="#065F46" font-size="9">c</text>

                        <!-- Tlist_c,c → ε -->
                        <line x1="535" y1="339" x2="535" y2="364" stroke="#6366F1" stroke-width="1.4"/>
                        <text x="535" y="374" text-anchor="middle" fill="#FAB387" font-weight="600" font-size="13">ε</text>

                        <!-- Elist_T1,T1 → ε -->
                        <line x1="620" y1="281" x2="620" y2="306" stroke="#6366F1" stroke-width="1.4"/>
                        <text x="620" y="316" text-anchor="middle" fill="#FAB387" font-weight="600" font-size="13">ε</text>

                        <!-- Expr_3 annotation -->
                        <line x1="345" y1="438" x2="345" y2="430" stroke="#6366F1" stroke-width="1"/>
                        <text x="280" y="440" text-anchor="middle" fill="#6C7086" font-size="9" font-style="italic">Rvalue→Term3→Factor3→num3</text>
                    </svg>
                    <figcaption>Sample Problem 4.8 — Attributed derivation tree for <code>a == (b=3) + c</code></figcaption>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        The atoms produced for <code>a == (b=3) + c</code> are:
                    </div>
                </div>

                <div class="output-seq">
                    <div class="atom-line">(MOV, 3, , b)</div>
                    <div class="atom-line">(ADD, b, c, T1)</div>
                    <div class="atom-line">(TST, a, T1, , 6, L1)  // branch to L1 if a != T1 (i.e. a == (b=3)+c is false)</div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Exercises 4.8                         -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="exercises">
                <h2><span class="sec-icon">📝</span> Exercises 4.8</h2>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 1 — Attributed Derivation Trees</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            Show an attributed derivation tree using the grammar for Decaf expressions given
                            in this section for each of the following expressions or boolean expressions.
                            In part (a) start with <code>Expr</code>; in parts (b), (c), (d), (e) start
                            with <code>BoolExpr</code>.
                        </p>
                        <div class="grammar-box" style="line-height:2.2; font-size:13px;">
                            (a) <span class="t">a = b = c</span><br>
                            (b) <span class="t">a == b + c</span><br>
                            (c) <span class="t">(a=3) &lt;= (b=2)</span><br>
                            (d) <span class="t">a == - (c = 3)</span><br>
                            (e) <span class="t">a * (b=3) + c != 9</span>
                        </div>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 2 — Recursive Descent Parser</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            Show the recursive descent parser for the nonterminals <code>BoolExpr</code>,
                            <code>Rvalue</code>, and <code>Elist</code> given in the grammar for Decaf
                            expressions. The selection sets for the first eight grammar rules are given below
                            as a hint:
                        </p>
                        <div class="sel-grid">
                            <div class="sel-card ok">
                                <div class="rule-id">✓ Sel(1) — BoolExpr</div>
                                <div class="sel-val">{ ident, num, (, +, - }</div>
                            </div>
                            <div class="sel-card ok">
                                <div class="rule-id">✓ Sel(2) — Expr → AssignExpr</div>
                                <div class="sel-val">{ ident }</div>
                            </div>
                            <div class="sel-card ok">
                                <div class="rule-id">✓ Sel(3) — Expr → Rvalue</div>
                                <div class="sel-val">{ ident, num, (, +, - }</div>
                            </div>
                            <div class="sel-card ok">
                                <div class="rule-id">✓ Sel(4) — AssignExpr</div>
                                <div class="sel-val">{ ident }</div>
                            </div>
                            <div class="sel-card ok">
                                <div class="rule-id">✓ Sel(5) — Rvalue</div>
                                <div class="sel-val">{ ident, num, (, +, - }</div>
                            </div>
                            <div class="sel-card ok">
                                <div class="rule-id">✓ Sel(6) — Elist → +</div>
                                <div class="sel-val">{ + }</div>
                            </div>
                            <div class="sel-card ok">
                                <div class="rule-id">✓ Sel(7) — Elist → -</div>
                                <div class="sel-val">{ - }</div>
                            </div>
                            <div class="sel-card ok">
                                <div class="rule-id">✓ Sel(8) — Elist → ε</div>
                                <div class="sel-val">{ ), N }</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ── Chapter nav ──────────────────────────────── -->
            <div class="chapter-nav">
                <a href="c4_7.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous</span>4.7 Previous Topic</div>
                </a>
                <a href="?complete=1" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.9 Next Topic
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