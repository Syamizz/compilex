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
$page     = 20;
$nextPage = "c4_7_1.php";

if (isset($_GET['complete']) && $_GET['complete'] == '20') {
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
                <li><a href="#intro"      class="toc-link">4.7 Translation Grammar</a></li>
                <li><a href="#atoms"      class="toc-link">Atoms</a></li>
                <li><a href="#g21"        class="toc-link">Grammar G21</a></li>
                <li><a href="#sample"     class="toc-link">Sample Problem 4.7</a></li>
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

            <section id="intro">
                <h2><span class="sec-icon">4.7</span> Translation Grammar for Expressions</h2>

                <p>
                    In this section, we make use of the material presented thus far on top down parsing to
                    implement a translator for <strong>infix expressions</strong> involving addition and
                    multiplication.
                </p>

                <p>
                    The output of the translator will be a stream of <strong>atoms</strong>, which could be
                    easily translated to the appropriate instructions on a typical target machine.
                </p>

                <div class="note-box key">
                    <span class="box-icon">Key</span>
                    <div>
                        The translator must find temporary storage locations, or use a stack, to store
                        intermediate results at run time. The final result of the expression is indicated by
                        the final temporary location produced by the translation.
                    </div>
                </div>
            </section>

            <section id="atoms">
                <h2><span class="sec-icon">A</span> Atoms</h2>

                <p>Each atom consists of four parts:</p>

                <table class="atom-table">
                    <thead>
                        <tr>
                            <th>Part</th>
                            <th>Meaning</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Operation</td>
                            <td><code>ADD</code> or <code>MULT</code></td>
                        </tr>
                        <tr>
                            <td>Left operand</td>
                            <td>The first run-time operand location</td>
                        </tr>
                        <tr>
                            <td>Right operand</td>
                            <td>The second run-time operand location</td>
                        </tr>
                        <tr>
                            <td>Result</td>
                            <td>The location where the result is stored</td>
                        </tr>
                    </tbody>
                </table>

                <p>
                    For example, if the input were <code>A + B * C + D</code>, the output could be:
                </p>

                <div class="code-block"><span class="out">MULT</span>    B       C       Temp1
<span class="out">ADD</span>     A       Temp1   Temp2
<span class="out">ADD</span>     Temp2   D       Temp3</div>

                <div class="note-box tip">
                    <span class="box-icon">Note</span>
                    <div>
                        This output indicates that the final result of the expression is in
                        <code>Temp3</code>. Later, Section 4.8 adds two more fields to the atoms.
                    </div>
                </div>
            </section>

            <section id="g21">
                <h2><span class="sec-icon">G21</span> Attributed Translation Grammar</h2>

                <p>
                    In grammar G21, all nonterminal attributes are synthesized except the first attribute
                    on <code>Elist</code> and <code>Tlist</code>, which are inherited.
                </p>

                <div class="grammar-box">
                    <span class="rule-num">G21:</span><br>

                    <span class="rule-num">1.</span>
                    <span class="nt">Expr<sub class="attr">p</sub></span>
                    <span class="arr">&rarr;</span>
                    <span class="nt">Term<sub class="attr">q</sub></span>
                    <span class="nt">Elist<sub class="attr">q,p</sub></span><br>

                    <span class="rule-num">2.</span>
                    <span class="nt">Elist<sub class="attr">p,q</sub></span>
                    <span class="arr">&rarr;</span>
                    <span class="t">+</span>
                    <span class="nt">Term<sub class="attr">r</sub></span>
                    <span class="act">{ADD}<sub class="attr">p,r,s</sub></span>
                    <span class="nt">Elist<sub class="attr">s,q</sub></span>
                    <span class="comp">s = Alloc()</span><br>

                    <span class="rule-num">3.</span>
                    <span class="nt">Elist<sub class="attr">p,q</sub></span>
                    <span class="arr">&rarr;</span>
                    <span class="eps">&epsilon;</span>
                    <span class="comp">q = p</span><br>

                    <span class="rule-num">4.</span>
                    <span class="nt">Term<sub class="attr">p</sub></span>
                    <span class="arr">&rarr;</span>
                    <span class="nt">Factor<sub class="attr">q</sub></span>
                    <span class="nt">Tlist<sub class="attr">q,p</sub></span><br>

                    <span class="rule-num">5.</span>
                    <span class="nt">Tlist<sub class="attr">p,q</sub></span>
                    <span class="arr">&rarr;</span>
                    <span class="t">*</span>
                    <span class="nt">Factor<sub class="attr">r</sub></span>
                    <span class="act">{MULT}<sub class="attr">p,r,s</sub></span>
                    <span class="nt">Tlist<sub class="attr">s,q</sub></span>
                    <span class="comp">s = Alloc()</span><br>

                    <span class="rule-num">6.</span>
                    <span class="nt">Tlist<sub class="attr">p,q</sub></span>
                    <span class="arr">&rarr;</span>
                    <span class="eps">&epsilon;</span>
                    <span class="comp">q = p</span><br>

                    <span class="rule-num">7.</span>
                    <span class="nt">Factor<sub class="attr">p</sub></span>
                    <span class="arr">&rarr;</span>
                    <span class="t">(</span>
                    <span class="nt">Expr<sub class="attr">p</sub></span>
                    <span class="t">)</span><br>

                    <span class="rule-num">8.</span>
                    <span class="nt">Factor<sub class="attr">p</sub></span>
                    <span class="arr">&rarr;</span>
                    <span class="t">ident<sub class="attr">p</sub></span>
                </div>

                <div class="note-box pro">
                    <span class="box-icon">Rule</span>
                    <div>
                        The intent of the action symbol <code>{ADD}<sub>p,r,s</sub></code> is to put out an
                        <code>ADD</code> atom with operands <code>p</code> and <code>r</code>, and result
                        <code>s</code>.
                    </div>
                </div>

                <p>
                    In many rules, several symbols share an attribute. This means that the attribute is to
                    have the same value on those symbols. For example, in rule 1, the attribute of
                    <code>Term</code> is supposed to have the same value as the first attribute of
                    <code>Elist</code>, so those attributes are given the same name.
                </p>

                <p>
                    This also could have been done in rules 3 and 6, but the grammar keeps them separate to
                    clarify the recursive descent parser. For this reason, only four attribute computation
                    rules are needed, two of which involve a call to <code>Alloc()</code>.
                </p>

                <div class="note-box key">
                    <span class="box-icon">Alloc</span>
                    <div>
                        <code>Alloc()</code> allocates space for a temporary result and returns a pointer to it.
                        In these examples, the temporary results are named <code>Temp1</code>,
                        <code>Temp2</code>, <code>Temp3</code>, and so on. The attribute of an
                        <code>ident</code> token is the value part of that token, indicating the run-time
                        location for the variable.
                    </div>
                </div>
            </section>

            <section id="sample">
                <h2><span class="sec-icon">SP</span> Sample Problem 4.7</h2>

                <div class="note-box key">
                    <span class="box-icon">Problem</span>
                    <div>
                        Show an attributed derivation tree for the expression <code>a+b</code>, using grammar G21.
                    </div>
                </div>

                <p><strong>Solution:</strong></p>

                <p>
                    The subscripts in this tree all represent pointers. The subscript <code>a</code> represents
                    a pointer to the entry for variable <code>a</code> in the symbol table, and <code>T1</code>
                    represents a pointer to the temporary location or stack position for <code>T1</code>.
                </p>

                <div class="tree-wrap">
<pre>                         Expr T1
                    /              \
              Term a                Elist a,T1
             /      \              /     |        |        \
      Factor a    Tlist a,a       +   Term b   {ADD}a,b,T1 Elist T1,T1
          |           |                /   \                    |
       ident a     epsilon       Factor b  Tlist b,b         epsilon
                                      |        |
                                   ident b  epsilon</pre>
                </div>
            </section>

   
 

            <div class="chapter-nav">
                <a href="c4_6.php" class="nav-btn">
                    <span>&larr;</span>
                    <div><span class="btn-sub">Previous</span>4.6 Attributed Grammars</div>
                </a>
                <a href="?complete=20" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.7.1 Translation Grammar for Expressions
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
