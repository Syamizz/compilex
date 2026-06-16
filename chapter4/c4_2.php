<?php
session_start();
include '../dbconn.php';
include '../save_progress.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$chapter = 4;
$page    = 3;
$nextPage = "c4_2_1.php"; // next sub-page (adjust as needed)

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
    <title>Chapter 4.2 - CompileX</title>

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
            line-height: 2;
            overflow-x: auto;
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
            color: #F9E2AF;
        }

        .mini-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
            margin: 18px 0;
        }

        .mini-card {
            background: var(--card, #fff);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 4px 18px rgba(99, 102, 241, .08);
        }

        .mini-card h4 {
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            color: var(--purple, #6366F1);
            font-weight: 700;
            margin: 0 0 10px;
        }

        .set-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 14px 0;
        }

        .set-chip {
            background: #EEF2FF;
            color: #4338CA;
            border: 1px solid rgba(99, 102, 241, .18);
            border-radius: 999px;
            padding: 6px 12px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            font-weight: 700;
        }

        .set-chip.follow {
            background: #F0FDF4;
            color: #065F46;
            border-color: rgba(16, 185, 129, .28);
        }

        .set-chip.epsilon {
            background: #FFFBEB;
            color: #92400E;
            border-color: rgba(245, 158, 11, .3);
        }

        .table-scroll {
            overflow-x: auto;
            margin: 16px 0;
        }

        .machine-table {
            width: 100%;
            border-collapse: collapse;
            margin: 18px 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12.5px;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 0 0 1px rgba(99, 102, 241, .15);
        }

        .machine-table th {
            background: #EEF2FF;
            color: #4338CA;
            padding: 10px 12px;
            text-align: center;
            font-weight: 700;
        }

        .machine-table td {
            padding: 10px 12px;
            text-align: center;
            border-top: 1px solid #EEF2FF;
            color: var(--text, #1E1B4B);
            white-space: nowrap;
        }

        .machine-table td.row-head {
            background: #F8FAFC;
            color: #4338CA;
            font-weight: 700;
        }

        .machine-table td.reject {
            color: #991B1B;
            background: #FEF2F2;
        }

        .machine-table td.accept {
            color: #065F46;
            background: #F0FDF4;
            font-weight: 700;
        }

        .machine-table td.action {
            color: #4338CA;
            background: #F8FAFF;
        }

        .machine-table td.eps-action {
            color: #92400E;
            background: #FFFBEB;
        }

        .tree-wrap {
            background: var(--card, #fff);
            border: 1px solid rgba(99, 102, 241, .15);
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

        .code-panel {
            background: #1A1830;
            color: #CDD6F4;
            border-radius: 12px;
            padding: 18px 20px;
            margin: 16px 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            line-height: 1.7;
            overflow-x: auto;
        }

        .code-panel .kw {
            color: #89B4FA;
        }

        .code-panel .cm {
            color: #7F849C;
        }

        .code-panel .fn {
            color: #CBA6F7;
        }

        .code-panel .ch {
            color: #A6E3A1;
        }

        .parse-steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
            margin: 18px 0;
        }

        .parse-step {
            background: #fff;
            border: 1px solid rgba(99, 102, 241, .16);
            border-radius: 12px;
            padding: 14px;
        }

        .parse-step .lookahead {
            font-family: 'JetBrains Mono', monospace;
            font-size: 18px;
            font-weight: 700;
            color: #4338CA;
            margin-bottom: 6px;
        }

        .parse-step .rule {
            font-size: 13px;
            color: var(--text, #1E1B4B);
            line-height: 1.5;
        }
    </style>
</head>

<body>

    <?php include 'navbar_c4.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">Contents</div>
            <ul>
                <li><a href="#intro" class="toc-link">4.2 Quasi-Simple Grammars</a></li>
                <li><a href="#follow" class="toc-link">Follow Sets</a></li>
                <li><a href="#selection" class="toc-link">Selection Sets</a></li>
                <li><a href="#pdm" class="toc-link">Pushdown Machines</a></li>
                <li><a href="#rdp" class="toc-link">Recursive Descent</a></li>
                <li><a href="#remark" class="toc-link">Final Remark</a></li>
                <li><a href="#sample" class="toc-link">Sample Problem 4.2</a></li>
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
                <div class="chapter-tag">Chapter 4.2</div>
                <h1>Quasi-Simple Grammars</h1>
                <div class="metadata">
                    <span>25 min read</span>
                    <span>Intermediate</span>
                    <span>Top Down Parsing</span>
                </div>
            </header>

            <section id="intro">
                <h2><span class="sec-icon">4.2</span> What is a Quasi-Simple Grammar?</h2>

                <p>
                    We can extend the class of grammars that can be parsed top down by permitting
                    <strong>epsilon rules</strong>. These grammars obey the same main restriction as
                    simple grammars, except that rules of the form <code>N &rarr; &epsilon;</code>
                    are also allowed.
                </p>

                <div class="note-box key">
                    <span class="box-icon">Key</span>
                    <div>
                        <strong>Definition:</strong> A <strong>quasi-simple grammar</strong> may contain
                        rules of the form <code>N &rarr; &epsilon;</code>, where <code>N</code> is a
                        nonterminal, as long as all rules defining the same nonterminal have
                        <strong>disjoint selection sets</strong>.
                    </div>
                </div>

                <h3>Grammar G14</h3>
                <p>The note uses the following grammar as the main example for this section:</p>

                <div class="grammar-box">
                    <span class="rule-num">G14:</span><br>
                    <span class="rule-num">1.</span> <span class="nt">S</span> <span class="arr">&rarr;</span> <span class="t">a</span><span class="nt">A</span><span class="nt">S</span><br>
                    <span class="rule-num">2.</span> <span class="nt">S</span> <span class="arr">&rarr;</span> <span class="t">b</span><br>
                    <span class="rule-num">3.</span> <span class="nt">A</span> <span class="arr">&rarr;</span> <span class="t">c</span><span class="nt">A</span><span class="nt">S</span><br>
                    <span class="rule-num">4.</span> <span class="nt">A</span> <span class="arr">&rarr;</span> <span class="eps">&epsilon;</span>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">Tip</span>
                    <div>
                        The new problem is deciding when an epsilon rule should be applied. For that,
                        we need <strong>follow sets</strong>.
                    </div>
                </div>
            </section>

            <section id="follow">
                <h2><span class="sec-icon">Fol</span> Follow Sets</h2>

                <p>
                    The <strong>follow set</strong> of a nonterminal <code>A</code>, written
                    <code>Fol(A)</code>, is the set of all terminals, or the endmarker <code>,</code>,
                    that can immediately follow an <code>A</code> in an intermediate form derived from
                    the starting nonterminal <code>S</code>.
                </p>

                <div class="mini-grid">
                    <div class="mini-card">
                        <h4>Follow Set of S</h4>
                        <p>
                            In G14, <code>S</code> can be followed by <code>a</code>, <code>b</code>,
                            or the endmarker <code>,</code>.
                        </p>
                        <div class="set-row">
                            <span class="set-chip follow">Fol(S) = { a, b, , }</span>
                        </div>
                    </div>

                    <div class="mini-card">
                        <h4>Follow Set of A</h4>
                        <p>
                            In G14, <code>A</code> can be followed by <code>a</code> or <code>b</code>.
                        </p>
                        <div class="set-row">
                            <span class="set-chip follow">Fol(A) = { a, b }</span>
                        </div>
                    </div>
                </div>

                <h3>Why <code>Fol(A) = { a, b }</code>?</h3>
                <p>
                    In derivations from <code>S</code>, an <code>A</code> can be followed by symbols
                    that begin an example of <code>S</code>. Since <code>S</code> can begin with
                    <code>a</code> or <code>b</code>, those terminals can immediately follow
                    <code>A</code>.
                </p>

                <div class="grammar-box">
                    <span class="nt">S</span> <span class="arr">&rArr;</span> <span class="t">a</span><span class="nt">A</span><span class="nt">S</span><br>
                    <span class="nt">S</span> <span class="arr">&rArr;</span> <span class="t">a</span><span class="nt">A</span><span class="t">a</span><span class="nt">A</span><span class="nt">S</span><br>
                    <span class="nt">S</span> <span class="arr">&rArr;</span> <span class="t">a</span><span class="nt">A</span><span class="t">b</span>
                </div>
            </section>

            <section id="selection">
                <h2><span class="sec-icon">Sel</span> Selection Sets in Quasi-Simple Grammars</h2>

                <p>
                    For non-epsilon rules, the selection set is still the first terminal on the
                    right-hand side. For an epsilon rule, the selection set is the follow set of the
                    nonterminal on the left side of the arrow.
                </p>

                <div class="note-box key">
                    <span class="box-icon">Rule</span>
                    <div>
                        If <code>A &rarr; &epsilon;</code>, then
                        <code>Sel(A &rarr; &epsilon;) = Fol(A)</code>.
                    </div>
                </div>

                <h3>Selection Sets for G14</h3>
                <div class="table-scroll">
                    <table class="machine-table">
                        <thead>
                            <tr>
                                <th>Rule</th>
                                <th>Production</th>
                                <th>Selection Set</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="row-head">1</td>
                                <td><code>S &rarr; aAS</code></td>
                                <td><span class="set-chip">{ a }</span></td>
                            </tr>
                            <tr>
                                <td class="row-head">2</td>
                                <td><code>S &rarr; b</code></td>
                                <td><span class="set-chip">{ b }</span></td>
                            </tr>
                            <tr>
                                <td class="row-head">3</td>
                                <td><code>A &rarr; cAS</code></td>
                                <td><span class="set-chip">{ c }</span></td>
                            </tr>
                            <tr>
                                <td class="row-head">4</td>
                                <td><code>A &rarr; &epsilon;</code></td>
                                <td><span class="set-chip epsilon">Fol(A) = { a, b }</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Parsing <code>acbb</code> with Selection Sets</h3>
                <p>
                    If the parser is trying to rewrite an <code>A</code>, it chooses rule 3 when the
                    current input symbol is <code>c</code>, but chooses rule 4 when the input symbol is
                    <code>a</code> or <code>b</code>.
                </p>

                <div class="parse-steps">
                    <div class="parse-step">
                        <div class="lookahead">a</div>
                        <div class="rule">For <code>S</code>, apply rule 1:<br><code>S &rarr; aAS</code></div>
                    </div>
                    <div class="parse-step">
                        <div class="lookahead">c</div>
                        <div class="rule">For <code>A</code>, apply rule 3:<br><code>A &rarr; cAS</code></div>
                    </div>
                    <div class="parse-step">
                        <div class="lookahead">b</div>
                        <div class="rule">For <code>A</code>, apply rule 4:<br><code>A &rarr; &epsilon;</code></div>
                    </div>
                    <div class="parse-step">
                        <div class="lookahead">b</div>
                        <div class="rule">For <code>S</code>, apply rule 2:<br><code>S &rarr; b</code></div>
                    </div>
                </div>
            </section>

            <section id="pdm">
                <h2><span class="sec-icon">PDM</span> Pushdown Machines for Quasi-Simple Grammars</h2>

                <p>
                    To build a pushdown machine for a quasi-simple grammar, we add one extra step to
                    the simple grammar construction: epsilon rules are applied by popping the
                    nonterminal from the stack while retaining the input pointer.
                </p>

                <div class="note-box key">
                    <span class="box-icon">4.5</span>
                    <div>
                        For each <code>&epsilon;</code> rule, fill the row of the nonterminal on the
                        left side with <code>Pop, Retain</code>, but only in the columns belonging to
                        the follow set of that nonterminal.
                    </div>
                </div>

                <h3>Pushdown Machine for G14</h3>
                <div class="table-scroll">
                    <table class="machine-table">
                        <thead>
                            <tr>
                                <th>Stack Top</th>
                                <th>a</th>
                                <th>b</th>
                                <th>c</th>
                                <th>,</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="row-head">S</td>
                                <td class="action">Rep(SAa), Retain</td>
                                <td class="action">Rep(b), Retain</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-head">A</td>
                                <td class="eps-action">Pop, Retain</td>
                                <td class="eps-action">Pop, Retain</td>
                                <td class="action">Rep(SAc), Retain</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-head">a</td>
                                <td class="action">Pop, Advance</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-head">b</td>
                                <td class="reject">Reject</td>
                                <td class="action">Pop, Advance</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-head">c</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="action">Pop, Advance</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-head">,</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="accept">Accept</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="note-box pro">
                    <span class="box-icon">Note</span>
                    <div>
                        The entries in columns <code>a</code> and <code>b</code> for row <code>A</code>
                        correspond to rule 4, <code>A &rarr; &epsilon;</code>, because
                        <code>Fol(A) = { a, b }</code>.
                    </div>
                </div>
            </section>

            <section id="rdp">
                <h2><span class="sec-icon">RDP</span> Recursive Descent for Quasi-Simple Grammars</h2>

                <p>
                    Recursive descent parsers for quasi-simple grammars are similar to those for
                    simple grammars. The only difference is that an epsilon rule is implemented by
                    checking the follow set and returning without reading input.
                </p>

                <h3>Recursive Descent Parser for G14</h3>
                <div class="code-panel">
                    <span class="kw">char</span> inp;<br><br>

                    <span class="kw">void</span> <span class="fn">parse</span>()<br>
                    {<br>
                    &nbsp;&nbsp;inp = getInp();<br>
                    &nbsp;&nbsp;<span class="fn">S</span>();<br>
                    &nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">','</span>) accept();<br>
                    &nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    }<br><br>

                    <span class="kw">void</span> <span class="fn">S</span>()<br>
                    {<br>
                    &nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">'a'</span>) <span class="cm">// apply rule 1</span><br>
                    &nbsp;&nbsp;{<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;inp = getInp();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">A</span>();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">S</span>();<br>
                    &nbsp;&nbsp;}<br>
                    &nbsp;&nbsp;<span class="kw">else if</span> (inp == <span class="ch">'b'</span>) inp = getInp(); <span class="cm">// apply rule 2</span><br>
                    &nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    }<br><br>

                    <span class="kw">void</span> <span class="fn">A</span>()<br>
                    {<br>
                    &nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">'c'</span>) <span class="cm">// apply rule 3</span><br>
                    &nbsp;&nbsp;{<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;inp = getInp();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">A</span>();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">S</span>();<br>
                    &nbsp;&nbsp;}<br>
                    &nbsp;&nbsp;<span class="kw">else if</span> (inp == <span class="ch">'a'</span> || inp == <span class="ch">'b'</span>) ; <span class="cm">// apply rule 4: A &rarr; epsilon</span><br>
                    &nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    }
                </div>
            </section>

            <section id="remark">
                <h2><span class="sec-icon">!</span> A Final Remark on Epsilon Rules</h2>

                <p>
                    It is not strictly necessary to compute the selection set for epsilon rules in
                    quasi-simple grammars. In a pushdown machine, the epsilon-rule row entries could
                    all be marked <code>Pop, Retain</code> instead of distinguishing between
                    <code>Reject</code> and <code>Pop, Retain</code>.
                </p>

                <div class="note-box warn">
                    <span class="box-icon">Careful</span>
                    <div>
                        The final acceptance or rejection result would still be correct, because a
                        wrong epsilon pop would cause a syntax error later. However, compilers usually
                        prefer detecting syntax errors as early as possible so the error message is
                        more meaningful.
                    </div>
                </div>
            </section>

            <section id="sample">
                <h2><span class="sec-icon">4.2</span> Sample Problem 4.2</h2>

                <p>
                    Find the selection sets for the following grammar. Is the grammar quasi-simple?
                    If so, show a pushdown machine and a recursive descent parser, showing methods
                    <code>S()</code> and <code>A()</code> only.
                </p>

                <div class="grammar-box">
                    <span class="rule-num">1.</span> <span class="nt">S</span> <span class="arr">&rarr;</span> <span class="t">b</span><span class="nt">A</span><span class="t">b</span><br>
                    <span class="rule-num">2.</span> <span class="nt">S</span> <span class="arr">&rarr;</span> <span class="t">a</span><br>
                    <span class="rule-num">3.</span> <span class="nt">A</span> <span class="arr">&rarr;</span> <span class="eps">&epsilon;</span><br>
                    <span class="rule-num">4.</span> <span class="nt">A</span> <span class="arr">&rarr;</span> <span class="t">a</span><span class="nt">S</span><span class="t">a</span>
                </div>

                <h3>Follow and Selection Sets</h3>
                <p>
                    To find the selection set for rule 3, find the follow set of <code>A</code>.
                    In the derivation <code>S &rArr; bAb</code>, the terminal <code>b</code> follows
                    <code>A</code>. No other terminal can follow <code>A</code>, so
                    <code>Fol(A) = { b }</code>.
                </p>

                <div class="set-row">
                    <span class="set-chip">Sel(1) = { b }</span>
                    <span class="set-chip">Sel(2) = { a }</span>
                    <span class="set-chip epsilon">Sel(3) = Fol(A) = { b }</span>
                    <span class="set-chip">Sel(4) = { a }</span>
                </div>

                <div class="note-box key">
                    <span class="box-icon">Answer</span>
                    <div>
                        The grammar is quasi-simple because the rules defining <code>S</code> have
                        disjoint selection sets, and the rules defining <code>A</code> also have
                        disjoint selection sets.
                    </div>
                </div>

                <h3>Pushdown Machine</h3>
                <div class="table-scroll">
                    <table class="machine-table">
                        <thead>
                            <tr>
                                <th>Stack Top</th>
                                <th>a</th>
                                <th>b</th>
                                <th>,</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="row-head">S</td>
                                <td class="action">Rep(a), Retain</td>
                                <td class="action">Rep(bAb), Retain</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-head">A</td>
                                <td class="action">Rep(aSa), Retain</td>
                                <td class="eps-action">Pop, Retain</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-head">a</td>
                                <td class="action">Pop, Advance</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-head">b</td>
                                <td class="reject">Reject</td>
                                <td class="action">Pop, Advance</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-head">,</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                                <td class="accept">Accept</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Recursive Descent Methods</h3>
                <div class="code-panel">
                    <span class="kw">void</span> <span class="fn">S</span>()<br>
                    {<br>
                    &nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">'b'</span>) <span class="cm">// apply rule 1</span><br>
                    &nbsp;&nbsp;{<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;inp = getInp();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">A</span>();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">'b'</span>) inp = getInp();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    &nbsp;&nbsp;}<br>
                    &nbsp;&nbsp;<span class="kw">else if</span> (inp == <span class="ch">'a'</span>) inp = getInp(); <span class="cm">// apply rule 2</span><br>
                    &nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    }<br><br>

                    <span class="kw">void</span> <span class="fn">A</span>()<br>
                    {<br>
                    &nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">'b'</span>) ; <span class="cm">// apply rule 3: A &rarr; epsilon</span><br>
                    &nbsp;&nbsp;<span class="kw">else if</span> (inp == <span class="ch">'a'</span>) <span class="cm">// apply rule 4</span><br>
                    &nbsp;&nbsp;{<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;inp = getInp();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">S</span>();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">'a'</span>) inp = getInp();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    &nbsp;&nbsp;}<br>
                    &nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    }
                </div>
            </section>

            <div class="chapter-nav">
                <a href="c4_1.php" class="nav-btn">
                    <span>&larr;</span>
                    <div><span class="btn-sub">Back to</span>4.1 Simple Grammars</div>
                </a>
                <a href="?complete=1" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.2.1 Practice
                    </div>
                    <span>&rarr;</span>
                </a>
            </div>

        </article>
    </div>

    <script>
        const sections = document.querySelectorAll('section[id]');
        const tocLinks = document.querySelectorAll('.toc-link');
        const bar = document.getElementById('toc-bar');
        const pctLabel = document.getElementById('pct-label');

        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            const total = document.body.scrollHeight - window.innerHeight;
            const pct = total > 0 ? Math.round((scrolled / total) * 100) : 0;
            bar.style.width = pct + '%';
            pctLabel.textContent = pct + '%';

            let current = '';
            sections.forEach(s => {
                if (scrolled >= s.offsetTop - 120) current = s.id;
            });
            tocLinks.forEach(l => {
                l.classList.toggle('active', l.getAttribute('href') === '#' + current);
            });
        });
    </script>

</body>

</html>