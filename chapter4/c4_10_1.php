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
$page     = 27;
$nextPage = "../home.php"; // adjust as needed

if (isset($_GET['complete']) && $_GET['complete'] == '27') {
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
                  <li><a href="#iterative"    class="toc-link">Iterative vs Recursive Lists</a></li>
                <li><a href="#factor-code"  class="toc-link">Factor() Parser Code</a></li>
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
            <!-- SECTION – Iterative vs Recursive Lists          -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="iterative">
                <h2><span class="sec-icon">🔁</span> Iterative vs Recursive List Parsing</h2>

                <p>
                    The recursive descent parser is taken directly from Figure 4.19. The only departure is in
                    the description of iterative structures such as <code>IdentList</code> and
                    <code>ArgList</code>. Context-free grammars are useful in describing recursive constructs,
                    but are <strong>not very useful</strong> in describing these iterative constructs.
                </p>
                <p>
                    For example, the definition of <code>IdentList</code> in the grammar is:
                </p>

                <div class="compare-strip">
                    <div class="cs-box">
                        <div class="cs-head">📐 Grammar (recursive) definition</div>
                        <div class="cs-body">
                            <span class="nt">IdentList</span> <span class="arr">→</span> <span style="color:#A6E3A1">identifier</span><br>
                            <span style="color:#94E2D5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|</span> <span style="color:#A6E3A1">identifier</span> <span style="color:#A6E3A1">,</span> <span class="nt">IdentList</span>
                        </div>
                    </div>
                    <div class="cs-box">
                        <div class="cs-head">💻 Parser implementation (iterative)</div>
                        <div class="cs-body">
<span style="color:#CBA6F7">if</span> (token.get_class() != IDENTIFIER) error();<br>
<span style="color:#CBA6F7">while</span> (token.get_class() == IDENTIFIER)<br>
{   token.getToken();<br>
&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#CBA6F7">if</span> (token.get_class() == <span style="color:#A6E3A1">','</span>)<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;token.getToken();<br>
}
                        </div>
                    </div>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        While the recursive grammar definition of <code>IdentList</code> is perfectly valid,
                        it leads to a <strong>less efficient parser</strong> (for compilers that don't
                        translate tail recursion into loops). Using a <strong>loop</strong> to scan for a
                        list of identifiers separated by commas is more practical. This methodology is also
                        used for the methods <code>ArgList()</code> and <code>StmtList()</code>.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Factor() Parser Code                  -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="factor-code">
                <h2><span class="sec-icon">💻</span> Recursive Descent: <code>Factor()</code></h2>

                <p>
                    Note that the fact that we have assigned the <strong>same attribute</strong> to certain
                    symbols in the grammar saves some effort in the parser. For example, the definition of
                    <code>Factor</code> uses a subscript of <code>p</code> on the <code>Factor</code> as
                    well as on the <code>Expr</code>, <code>identifier</code>, and <code>number</code> on
                    the right side of the arrow. This simply means that <strong>the value of the Factor is
                    the same as the item on the right side</strong>, and the parser is (ignoring unary
                    operations):
                </p>

                <div class="code-block">
<span class="ty">void</span> <span class="fn">Factor</span> (<span class="cls">MutableInt</span> p)
{   <span class="kw">if</span> (token.<span class="fn">get_class</span>() == <span class="str">'('</span>)
    {   token.<span class="fn">getToken</span>();
        <span class="fn">Expr</span> (p);
        <span class="kw">if</span> (token.<span class="fn">get_class</span>() == <span class="str">')'</span>) token.<span class="fn">getToken</span>();
        <span class="kw">else</span> <span class="fn">error</span>();
    }
    <span class="kw">else if</span> (inp == <span class="tok">IDENTIFIER</span>)
    {   <span class="cm">// store the value part of the identifier</span>
        p.<span class="fn">set</span> (token.<span class="fn">get_value</span>());
        token.<span class="fn">getToken</span>();
    }
    <span class="kw">else if</span> (inp == <span class="tok">NUMBER</span>)
    {   p.<span class="fn">set</span> (token.<span class="fn">get_value</span>());
        token.<span class="fn">getToken</span>();
    }
    <span class="kw">else</span>  <span class="cm">// check for unary operators +, - ...</span>
}
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        When the same attribute subscript appears on both sides of a grammar rule, no extra
                        computation is needed — the parser simply passes the value through. This is why
                        <code>Factor<sub>p</sub> → num<sub>p</sub></code> translates to just
                        <code>p.set(token.get_value())</code> with no arithmetic.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Exercises 4.10                        -->
            <!-- ─────────────────────────────────────────────── -->
   

            <!-- ── Chapter nav ──────────────────────────────── -->
            <div class="chapter-nav">
                <a href="c4_10_0.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous</span>4.10 Case Study: A Top Down Parser for Decaf (Part 1) </div>
                </a>
                <a href="?complete=27" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Done</span>
                        Homepage
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