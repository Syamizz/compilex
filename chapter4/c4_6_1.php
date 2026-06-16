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
$page     = 19;
$nextPage = "c4_7_0.php"; // adjust as needed

if (isset($_GET['complete']) && $_GET['complete'] == '19') {
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
                  <li><a href="#recdesc"     class="toc-link">4.6.1 Implementing Attributed Grammars with Recursive Descent</a></li>
                <li><a href="#mutableint"  class="toc-link">MutableInt Class</a></li>
                <li><a href="#caution"     class="toc-link">Contradictions & Caution</a></li>
                <li><a href="#sample"      class="toc-link">Sample Problem 4.6</a></li>
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

    
            <!-- ── Chapter nav ──────────────────────────────── -->
            <div class="chapter-nav">
                <a href="c4_6_0.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous</span>4.6 Attributed Grammars</div>
                </a>
                <a href="?complete=19" class="nav-btn next">
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