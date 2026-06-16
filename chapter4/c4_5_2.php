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
$page     = 17;
$nextPage = "c4_6_0.php"; // adjust as needed

if (isset($_GET['complete']) && $_GET['complete'] == '17') {
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
                <li><a href="#recdesc" class="toc-link">4.5.2 Recursive Descent</a></li>
                <li><a href="#g18" class="toc-link">Grammar G18 Example</a></li>
                <li><a href="#g17-recdesc" class="toc-link">G17 Recursive Descent</a></li>
                <li><a href="#sample" class="toc-link">Sample Problem 4.5</a></li>
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

          

            <!-- ── Chapter nav ──────────────────────────────── -->
            <div class="chapter-nav">
                <a href="c4_5_1.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous</span>4.5.1 Implementing Translation Grammars with Pushdown Translators</div>
                </a>
                <a href="?complete=17" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.6 Attributed Grammars
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