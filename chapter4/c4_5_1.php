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
$page     = 16;
$nextPage = "c4_5_2.php"; // adjust as needed

if (isset($_GET['complete']) && $_GET['complete'] == '16') {
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
              <li><a href="#pushdown" class="toc-link">4.5.1 Pushdown Translators</a></li>
                <li><a href="#pdm-table" class="toc-link">Fig 4.14 – PDM Table</a></li>
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


            <!-- ── Chapter nav ──────────────────────────────── -->
            <div class="chapter-nav">
                <a href="c4_5_0.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous</span>4.5 Syntax-Directed Translation</div>
                </a>
                <a href="?complete=16" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.5.2 Implementing Translation Grammars with Recursive Descent
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