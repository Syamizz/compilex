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
$page     = 21;
$nextPage = "c4_8_0.php";

if (isset($_GET['complete']) && $_GET['complete'] == '21') {
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
    <title>Chapter 4.7 - CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c1_1.css">

    <style>
        .grammar-box {
            background: var(--code-bg, #1A1830);
            border-radius: 12px;
            padding: 20px 24px;
            margin: 16px 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13.5px;
            color: #CDD6F4;
            line-height: 2.1;
            overflow-x: auto;
        }
        .grammar-box .rule-num { color: rgba(205,214,244,.45); user-select:none; margin-right:10px; }
        .grammar-box .nt   { color: #CBA6F7; }
        .grammar-box .t    { color: #A6E3A1; }
        .grammar-box .arr  { color: #94E2D5; }
        .grammar-box .eps  { color: #FAB387; }
        .grammar-box .attr { color: #F9E2AF; font-size: 11px; vertical-align: sub; }
        .grammar-box .comp { color: #89DCEB; font-size: 12px; margin-left: 18px; }
        .grammar-box .act  { color: #F38BA8; font-weight: 700; }

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
        .code-block .cls  { color: #94E2D5; }
        .code-block .out  { color: #F38BA8; font-weight: 600; }

        .atom-table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
            font-size: 13.5px;
            overflow: hidden;
            border-radius: 12px;
            border: 1px solid rgba(99,102,241,.14);
        }
        .atom-table th {
            background: #EEF2FF;
            color: #4338CA;
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            padding: 12px 14px;
            border-bottom: 1px solid rgba(99,102,241,.14);
        }
        .atom-table td {
            padding: 11px 14px;
            border-bottom: 1px solid rgba(99,102,241,.1);
            font-family: 'JetBrains Mono', monospace;
        }
        .atom-table tr:last-child td { border-bottom: 0; }

        .tree-wrap {
            background: var(--card, #fff);
            border: 1px solid rgba(99,102,241,.15);
            border-radius: 14px;
            padding: 20px;
            margin: 20px 0;
            overflow-x: auto;
        }
        .tree-wrap pre {
            margin: 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            line-height: 1.55;
            color: var(--text, #111827);
        }
    </style>
</head>

<body>

    <?php include 'navbar_c4.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">Contents</div>
            <ul>
                <li><a href="#recdesc"    class="toc-link">4.7.1 Recursive Descent</a></li>
                <li><a href="#code"       class="toc-link">Translator Code</a></li>
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

        <article class="content">

            <header class="content-header">
                <div class="chapter-tag">Chapter 4</div>
                <h1>An Attributed Translation Grammar for Expressions</h1>
                <div class="metadata">
                    <span>30 min read</span>
                    <span>Advanced</span>
                    <span>Attributed Translation Grammar</span>
                </div>
            </header>

 

            <section id="recdesc">
                <h2><span class="sec-icon">4.7.1</span> Translating Expressions with Recursive Descent</h2>

                <p>
                    In the recursive descent translator, synthesized attributes of nonterminals are implemented
                    as references, and inherited attributes are implemented as primitives.
                </p>

                <div class="note-box tip">
                    <span class="box-icon">Methods</span>
                    <div>
                        The <code>alloc()</code> and <code>atom()</code> methods are not shown in the note.
                        <code>alloc()</code> simply allocates space for a temporary result, and
                        <code>atom()</code> simply puts out an atom, which could be a record consisting of
                        the four fields described in Section 4.7.
                    </div>
                </div>

                <p>
                    The underlying grammar of G21 is the grammar for expressions, G16, given in Section 4.4.
                    The selection sets for this grammar are shown in that section. As in Sample Problem 4.6,
                    the <code>Token</code> class is assumed to have methods <code>get_class()</code> and
                    <code>getToken()</code>. The wrapper class <code>MutableInt</code> is used for synthesized
                    attributes.
                </p>
            </section>

            <section id="code">
                <h2><span class="sec-icon">Code</span> Recursive Descent Translator</h2>

                <div class="code-block"><span class="kw">class</span> <span class="cls">Expressions</span>
{
    <span class="cls">Token</span> token;
    <span class="kw">static</span> <span class="ty">int</span> next = <span class="num">0</span>;  <span class="cm">// for allocation of temporary storage</span>

    <span class="kw">public static</span> <span class="ty">void</span> <span class="fn">main</span> (<span class="cls">String</span>[] args)
    {
        <span class="cls">Expressions</span> e = <span class="kw">new</span> <span class="cls">Expressions</span>();
        e.<span class="fn">eval</span>();
    }

    <span class="ty">void</span> <span class="fn">eval</span> ()
    {   <span class="cls">MutableInt</span> p = <span class="kw">new</span> <span class="cls">MutableInt</span>(<span class="num">0</span>);
        token = <span class="kw">new</span> <span class="cls">Token</span>();
        token.<span class="fn">getToken</span>();
        <span class="fn">Expr</span>(p);                         <span class="cm">// look for an expression</span>
        <span class="kw">if</span> (token.<span class="fn">get_class</span>() != <span class="cls">Token</span>.End) <span class="fn">reject</span>();
        <span class="kw">else</span> <span class="fn">accept</span>();
    }

    <span class="ty">void</span> <span class="fn">Expr</span> (<span class="cls">MutableInt</span> p)
    {   <span class="cls">MutableInt</span> q = <span class="kw">new</span> <span class="cls">MutableInt</span>(<span class="num">0</span>);
        <span class="kw">if</span> (token.<span class="fn">get_class</span>() == <span class="cls">Token</span>.Lpar ||
            token.<span class="fn">get_class</span>() == <span class="cls">Token</span>.Ident ||
            token.<span class="fn">get_class</span>() == <span class="cls">Token</span>.Num)
        {   <span class="fn">Term</span>(q);                         <span class="cm">// apply rule 1</span>
            <span class="fn">Elist</span>(q.<span class="fn">get</span>(), p);
        }                                      <span class="cm">// end rule 1</span>
        <span class="kw">else</span> <span class="fn">reject</span>();
    }

    <span class="ty">void</span> <span class="fn">Elist</span> (<span class="ty">int</span> p, <span class="cls">MutableInt</span> q)
    {   <span class="ty">int</span> s;
        <span class="cls">MutableInt</span> r = <span class="kw">new</span> <span class="cls">MutableInt</span>();
        <span class="kw">if</span> (token.<span class="fn">get_class</span>() == <span class="cls">Token</span>.Plus)
        {   token.<span class="fn">getToken</span>();                <span class="cm">// apply rule 2</span>
            <span class="fn">Term</span>(r);
            s = <span class="fn">alloc</span>();
            <span class="fn">atom</span>(<span class="str">"ADD"</span>, p, r, s);          <span class="cm">// put out atom</span>
            <span class="fn">Elist</span>(s, q);
        }                                      <span class="cm">// end rule 2</span>
        <span class="kw">else if</span> (token.<span class="fn">get_class</span>() == <span class="cls">Token</span>.End ||
                 token.<span class="fn">get_class</span>() == <span class="cls">Token</span>.Rpar)
            q.<span class="fn">set</span>(p);                         <span class="cm">// rule 3</span>
        <span class="kw">else</span> <span class="fn">reject</span>();
    }

    <span class="ty">void</span> <span class="fn">Term</span> (<span class="cls">MutableInt</span> p)
    {   <span class="cls">MutableInt</span> q = <span class="kw">new</span> <span class="cls">MutableInt</span>();
        <span class="kw">if</span> (token.<span class="fn">get_class</span>() == <span class="cls">Token</span>.Lpar ||
            token.<span class="fn">get_class</span>() == <span class="cls">Token</span>.Ident ||
            token.<span class="fn">get_class</span>() == <span class="cls">Token</span>.Num)
        {   <span class="fn">Factor</span>(q);                       <span class="cm">// apply rule 4</span>
            <span class="fn">Tlist</span>(q.<span class="fn">get</span>(), p);
        }                                      <span class="cm">// end rule 4</span>
        <span class="kw">else</span> <span class="fn">reject</span>();
    }

    <span class="ty">void</span> <span class="fn">Tlist</span> (<span class="ty">int</span> p, <span class="cls">MutableInt</span> q)
    {   <span class="ty">int</span> inp, s;
        <span class="cls">MutableInt</span> r = <span class="kw">new</span> <span class="cls">MutableInt</span>();
        <span class="kw">if</span> (token.<span class="fn">get_class</span>() == <span class="cls">Token</span>.Mult)
        {   token.<span class="fn">getToken</span>();                <span class="cm">// apply rule 5</span>
            inp = token.<span class="fn">get_class</span>();
            <span class="fn">Factor</span>(r);
            s = <span class="fn">alloc</span>();
            <span class="fn">atom</span>(<span class="str">"MULT"</span>, p, r, s);
            <span class="fn">Tlist</span>(s, q);
        }                                      <span class="cm">// end rule 5</span>
        <span class="kw">else if</span> (token.<span class="fn">get_class</span>() == <span class="cls">Token</span>.Plus ||
                 token.<span class="fn">get_class</span>() == <span class="cls">Token</span>.Rpar ||
                 token.<span class="fn">get_class</span>() == <span class="cls">Token</span>.End)
            q.<span class="fn">set</span>(p);                         <span class="cm">// rule 6</span>
        <span class="kw">else</span> <span class="fn">reject</span>();
    }

    <span class="ty">void</span> <span class="fn">Factor</span> (<span class="cls">MutableInt</span> p)
    {   <span class="kw">if</span> (token.<span class="fn">get_class</span>() == <span class="cls">Token</span>.Lpar)
        {   token.<span class="fn">getToken</span>();                <span class="cm">// apply rule 7</span>
            <span class="fn">Expr</span>(p);
            <span class="kw">if</span> (token.<span class="fn">get_class</span>() == <span class="cls">Token</span>.Rpar)
                token.<span class="fn">getToken</span>();
            <span class="kw">else</span> <span class="fn">reject</span>();
        }                                      <span class="cm">// end rule 7</span>
        <span class="kw">else if</span> (token.<span class="fn">get_class</span>() == <span class="cls">Token</span>.Ident ||
                 token.<span class="fn">get_class</span>() == <span class="cls">Token</span>.Num)
        {   p.<span class="fn">set</span>(<span class="fn">alloc</span>());                 <span class="cm">// apply rule 8</span>
            token.<span class="fn">getToken</span>();
        }                                      <span class="cm">// end rule 8</span>
        <span class="kw">else</span> <span class="fn">reject</span>();
    }
}</div>
            </section>

       
            <div class="chapter-nav">
                <a href="c4_6.php" class="nav-btn">
                    <span>&larr;</span>
                    <div><span class="btn-sub">Previous</span>4.6 Attributed Grammars</div>
                </a>
                <a href="?complete=21" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.8 Decaf Expressions
                    </div>
                    <span>&rarr;</span>
                </a>
            </div>

        </article>
    </div>

    <script>
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
