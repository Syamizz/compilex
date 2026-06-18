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
$page     = 25;
$nextPage = "c4_10_0.php"; // adjust as needed

if (isset($_GET['complete']) && $_GET['complete'] == '25') {
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
    <title>Chapter 4.9 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c1_1.css">

    <style>
        /* ── Atom / code boxes ──────────────────────────────── */
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

        .grammar-box .attr {
            color: #F9E2AF;
            font-size: 11px;
            vertical-align: sub;
        }

        .grammar-box .comp {
            color: #89DCEB;
            font-size: 12px;
            margin-left: 18px;
        }

        .grammar-box .act {
            color: #F38BA8;
            font-weight: 700;
        }

        .grammar-box .kw {
            color: #CBA6F7;
        }

        /* ── Algo blocks ─────────────────────────────────────── */
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

        /* ── Atom reference table ───────────────────────────── */
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
            border: 1px solid rgba(99, 102, 241, .15);
        }

        .atom-table td {
            padding: 8px 14px;
            border: 1px solid rgba(99, 102, 241, .1);
            vertical-align: middle;
            line-height: 1.55;
        }

        .atom-table td.atom-name {
            font-weight: 700;
            color: #B45309;
            background: #FFF7ED;
            white-space: nowrap;
        }

        .atom-table td.atom-sig {
            color: #6366F1;
            font-weight: 600;
        }

        .atom-table tr:nth-child(even) td {
            background: rgba(99, 102, 241, .025);
        }

        .atom-table tr:nth-child(even) td.atom-name {
            background: #FFF7ED;
        }

        /* ── Comparison code table ───────────────────────────── */
        .comp-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13.5px;
            margin: 14px 0;
            max-width: 380px;
        }

        .comp-table th {
            background: var(--purple-s, #EEF2FF);
            color: var(--purple, #6366F1);
            padding: 8px 18px;
            text-align: center;
            font-weight: 700;
            font-size: 12px;
            border: 1px solid rgba(99, 102, 241, .15);
        }

        .comp-table td {
            padding: 7px 18px;
            border: 1px solid rgba(99, 102, 241, .1);
            text-align: center;
            color: var(--text, #1E1B4B);
        }

        .comp-table td.op-sym {
            font-weight: 700;
            color: #6366F1;
            font-size: 15px;
        }

        .comp-table td.op-code {
            font-weight: 700;
            color: #B45309;
        }

        .comp-table tr:nth-child(even) td {
            background: rgba(99, 102, 241, .03);
        }

        /* ── Control-structure flow diagrams (SVG wrappers) ─── */
        .flow-wrap {
            background: var(--card, #fff);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: 14px;
            padding: 20px 16px;
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-x: auto;
        }

        .flow-wrap figcaption {
            font-size: 12px;
            color: var(--muted, #6B7280);
            margin-top: 10px;
            text-align: center;
        }

        /* ── Two-column layout for while/for side-by-side ───── */
        .two-flow {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin: 18px 0;
        }

        @media (max-width: 700px) {
            .two-flow {
                grid-template-columns: 1fr;
            }
        }

        /* ── Output atom sequence ─────────────────────────────── */
        .output-seq {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin: 14px 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
        }

        .output-seq .atom-line {
            background: #FFF7ED;
            border: 1.5px solid rgba(245, 158, 11, .25);
            border-radius: 8px;
            padding: 6px 14px;
            color: #92400E;
            font-weight: 600;
        }

        .output-seq .atom-line.lbl {
            background: #F0FDF4;
            border-color: rgba(16, 185, 129, .3);
            color: #065F46;
        }

        .output-seq .atom-line.stmt {
            background: #EEF2FF;
            border-color: rgba(99, 102, 241, .25);
            color: #4338CA;
            font-style: italic;
        }

        .output-seq .atom-line.cm {
            background: transparent;
            border-color: transparent;
            color: var(--muted, #6B7280);
            font-style: italic;
            font-size: 12px;
            padding: 2px 14px;
        }

        /* ── Equivalence box ──────────────────────────────────── */
        .equiv-grid {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 14px;
            align-items: start;
            margin: 16px 0;
        }

        @media (max-width: 680px) {
            .equiv-grid {
                grid-template-columns: 1fr;
            }

            .equiv-arrow {
                display: none;
            }
        }

        .equiv-arrow {
            font-size: 22px;
            color: var(--purple, #6366F1);
            display: flex;
            align-items: center;
            padding-top: 14px;
        }

        /* ── Statement grammar list ───────────────────────────── */
        .stmt-list {
            counter-reset: stmt;
            list-style: none;
            padding: 0;
            margin: 12px 0;
        }

        .stmt-list li {
            counter-increment: stmt;
            display: flex;
            gap: 12px;
            align-items: baseline;
            padding: 7px 0;
            border-bottom: 1px solid rgba(99, 102, 241, .08);
            font-size: 13.5px;
        }

        .stmt-list li::before {
            content: counter(stmt) ".";
            min-width: 22px;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            color: var(--purple, #6366F1);
        }

        .stmt-list li code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: #B45309;
            background: #FFF7ED;
            padding: 1px 6px;
            border-radius: 4px;
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
               <li><a href="#stmt-defs" class="toc-link">Statement Definitions</a></li>
                <li><a href="#for-equiv" class="toc-link">for ↔ while Equivalence</a></li>
                <li><a href="#else-dangling" class="toc-link">Dangling else</a></li>
                <li><a href="#sample" class="toc-link">Sample Problem 4.9</a></li>
                <li><a href="#exercises" class="toc-link">Exercises 4.9</a></li>
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
                <h1>Translating Control Structures</h1>
                <div class="metadata">
                    <span>⏱ 25 min read</span>
                    <span>🎯 Advanced</span>
                    <span>⚙️ Code Generation</span>
                </div>
            </header>

        

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Statement Definitions                  -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="stmt-defs">
                <h2><span class="sec-icon">📋</span> Statement Definitions</h2>

                <p>
                    The control structures in Figures 4.18(a) and 4.18(b) correspond to the following statement
                    definitions (which are similar to the C++ control structures):
                </p>

                <ul class="stmt-list">
                    <li><code>Stmt → while ( BoolExpr ) Stmt</code></li>
                    <li><code>Stmt → for ( Expr ; BoolExpr ; Expr ) Stmt</code></li>
                    <li><code>Stmt → if ( BoolExpr ) Stmt ElsePart</code></li>
                    <li><code>ElsePart → else Stmt</code></li>
                    <li><code>ElsePart → ε</code></li>
                </ul>
            </section>

          

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – for ↔ while Equivalence              -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="for-equiv">
                <h2><span class="sec-icon">🔁</span> <code>for</code> ↔ <code>while</code> Equivalence</h2>

                <p>
                    The <code>for</code> statement requires some additional explanation. The following
                    <code>for</code> statement and <code>while</code> statement are <strong>equivalent</strong>:
                </p>

                <div class="equiv-grid">
                    <div class="grammar-box" style="font-size:13px; line-height:2;">
                        <span class="kw">for</span> (E1 ; boolean ; E2)<br>
                        &nbsp;&nbsp;Stmt
                    </div>
                    <div class="equiv-arrow">≡</div>
                    <div class="grammar-box" style="font-size:13px; line-height:2;">
                        E1 ;<br>
                        <span class="kw">while</span> (boolean)<br>
                        &nbsp;&nbsp;{<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;Stmt<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;E2 ;<br>
                        &nbsp;&nbsp;}
                    </div>
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        This means that after the atoms for <code>Stmt</code> are put out, we must put out a
                        <strong>jump to the atoms corresponding to expression E2</strong>. In addition, there
                        must be a jump <strong>after the atoms of expression E2</strong> back to the beginning
                        of the loop (the boolean condition). The LL(2) grammar for Decaf shown in the next
                        section makes direct use of Figure 4.18 for the control structures.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Dangling else                         -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="else-dangling">
                <h2><span class="sec-icon">⚠️</span> The Dangling <code>else</code> Problem</h2>

                <p>
                    Unfortunately, the grammar shown above is <strong>not LL(1)</strong>. This can be seen by
                    finding the follow set of <code>ElsePart</code>, and noting that it contains the keyword
                    <code>else</code>. Consequently, rules 4 and 5 do not have disjoint selection sets.
                </p>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        However, it is still possible to write a <strong>recursive descent parser</strong>.
                        This is due to the fact that all <code>else</code>s are matched with the
                        <em>closest preceding unmatched if</em>. When our parser for the nonterminal
                        <code>ElsePart</code> encounters an <code>else</code>, it is <em>never wrong</em> to
                        apply rule 4, because the closest preceding unmatched <code>if</code> must be the one
                        on top of the recursive call stack.
                    </div>
                </div>

                <div class="note-box pro">
                    <span class="box-icon">📌</span>
                    <div>
                        Aho [1986] claims that there is no LL(1) grammar for this language. This is apparently
                        one of those rare instances where <strong>theory fails, but our practical knowledge of
                            how things work comes to the rescue</strong>.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Sample Problem 4.9                    -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="sample">
                <h2><span class="sec-icon">🧪</span> Sample Problem 4.9</h2>

                <div class="note-box key">
                    <span class="box-icon">📖</span>
                    <div>
                        <strong>Problem:</strong> Show the atom string which would be put out that corresponds
                        to the following Java statement:
                        <br><br>
                        <code style="font-family:'JetBrains Mono',monospace; font-size:14px;">while (x &gt; 0) Stmt</code>
                    </div>
                </div>

                <p><strong>Solution:</strong></p>

                <div class="output-seq">
                    <div class="atom-line lbl">(LBL, L1)</div>
                    <div class="atom-line">(TST, x, 0, , 4, L2)
                        <span style="color:#6C7086; font-size:11px; margin-left:10px; font-style:italic;">
                            // Branch to L2 if x &lt;= 0
                        </span>
                    </div>
                    <div class="atom-line stmt">Atoms for Stmt</div>
                    <div class="atom-line">(JMP, L1)</div>
                    <div class="atom-line lbl">(LBL, L2)</div>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        The condition <code>x &gt; 0</code> has comparison code <strong>3</strong>. Its
                        complement is <code>7 − 3 = 4</code>, which is <code>&lt;=</code>. So
                        <code>TST, x, 0, , 4, L2</code> branches to L2 when <code>x &lt;= 0</code>
                        (i.e., when <code>x &gt; 0</code> is <em>false</em>), skipping the loop body.
                    </div>
                </div>
            </section>

        

            <!-- ── Chapter nav ──────────────────────────────── -->
            <div class="chapter-nav">
                <a href="c4_9_0.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>4.9 Translating Control Structures (Part 1)</div>
                </a>
                <a href="?complete=25" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.10 Case Study: A Top Down Parser for Decaf
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