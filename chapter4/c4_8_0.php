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
$page     = 22;
$nextPage = "c4_8_1.php"; // adjust as needed

if (isset($_GET['complete']) && $_GET['complete'] == '22') {
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
                <li><a href="#if-while"     class="toc-link">if / while Translation</a></li>
                <li><a href="#comp-codes"   class="toc-link">Comparison Codes</a></li>
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
       

  
            <!-- ── Chapter nav ──────────────────────────────── -->
            <div class="chapter-nav">
                <a href="c4_7_1.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous</span>4.7.1 Translating Expressions with Recursive Descent</div>
                </a>
                <a href="?complete=22" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.8 Decaf Expressions (Part 2)
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