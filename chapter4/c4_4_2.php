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
$page     = 14;
$nextPage = "c4_5_0.php";

if (isset($_GET['complete']) && $_GET['complete'] == '14') {
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
                <li><a href="#sample" class="toc-link">Sample Problem 4.4</a></li>
                <li><a href="#pdm" class="toc-link">Pushdown Machine</a></li>
                <li><a href="#recdesc" class="toc-link">Recursive Descent Parser</a></li>
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


            <!-- ── Chapter nav ──────────────────────────────── -->
            <div class="chapter-nav">
                <a href="c4_4_1.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous</span>4.4 Parsing Arithmetic Expressions Top Down (Part 2)</div>
                </a>
                <a href="?complete=14" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.5 Syntax-Directed Translation
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