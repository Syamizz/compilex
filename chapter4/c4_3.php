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
$page    = 4;
$nextPage = "c4_3_1.php"; // next sub-page (adjust as needed)

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
    <title>Chapter 4.3 - CompileX</title>

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

        .mini-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
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

        .algo-list {
            counter-reset: algo;
            display: grid;
            gap: 10px;
            margin: 18px 0;
        }

        .algo-step {
            display: grid;
            grid-template-columns: 42px 1fr;
            gap: 12px;
            align-items: start;
            background: #fff;
            border: 1px solid rgba(99, 102, 241, .14);
            border-radius: 12px;
            padding: 12px 14px;
        }

        .algo-step::before {
            counter-increment: algo;
            content: counter(algo);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #EEF2FF;
            color: #4338CA;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
        }

        .algo-step strong {
            color: var(--text, #1E1B4B);
        }

        .algo-step p {
            margin: 4px 0 0;
            color: var(--muted, #6B7280);
            font-size: 13.5px;
            line-height: 1.55;
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

        .stack-strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(92px, 1fr));
            gap: 10px;
            margin: 18px 0;
        }

        .stack-card {
            background: #fff;
            border: 1px solid rgba(99, 102, 241, .18);
            border-radius: 10px;
            padding: 10px;
            text-align: center;
        }

        .stack-card .input {
            color: var(--muted, #6B7280);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 6px;
        }

        .stack-card .stack {
            font-family: 'JetBrains Mono', monospace;
            color: #4338CA;
            font-size: 13px;
            font-weight: 700;
        }
    </style>
</head>

<body>

    <?php include 'navbar_c4.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">Contents</div>
            <ul>
                <li><a href="#intro" class="toc-link">4.3 LL(1) Grammars</a></li>
                <li><a href="#g15" class="toc-link">Grammar G15</a></li>
                <li><a href="#algorithm" class="toc-link">Selection Set Algorithm</a></li>
                <li><a href="#g15-result" class="toc-link">G15 Results</a></li>
                <li><a href="#machine" class="toc-link">Pushdown Machine</a></li>
                <li><a href="#rdp" class="toc-link">Recursive Descent</a></li>
                <li><a href="#sample" class="toc-link">Sample Problem 4.3</a></li>
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
                <div class="chapter-tag">Chapter 4.3</div>
                <h1>LL(1) Grammars</h1>
                <div class="metadata">
                    <span>30 min read</span>
                    <span>Advanced</span>
                    <span>Top Down Parsing</span>
                </div>
            </header>

            <section id="intro">
                <h2><span class="sec-icon">LL</span> What is an LL(1) Grammar?</h2>

                <p>
                    We now generalize the class of grammars that can be parsed top down by allowing
                    rules of the form <code>A &rarr; &alpha;</code>, where <code>&alpha;</code> is any
                    string of terminals and nonterminals. The grammar must still satisfy one important
                    condition: any two rules defining the same nonterminal must have
                    <strong>disjoint selection sets</strong>.
                </p>

                <div class="note-box key">
                    <span class="box-icon">Key</span>
                    <div>
                        A grammar is <strong>LL(1)</strong> if the parser can scan the input from
                        left to right, construct a leftmost derivation, and decide which rule to apply
                        using no more than <strong>one lookahead input symbol</strong>.
                    </div>
                </div>

                <p>
                    Once selection sets have been found, an LL(1) grammar can be parsed using the same
                    style of one-state pushdown machine or recursive descent parser introduced in the
                    previous sections.
                </p>
            </section>

            <section id="g15">
                <h2><span class="sec-icon">G15</span> Running Example</h2>

                <p>
                    The note uses grammar G15 to demonstrate the complete algorithm for finding
                    selection sets of an arbitrary context-free grammar.
                </p>

                <div class="grammar-box">
                    <span class="rule-num">G15:</span><br>
                    <span class="rule-num">1.</span> <span class="nt">S</span> <span class="arr">&rarr;</span> <span class="nt">A</span><span class="nt">B</span><span class="t">c</span><br>
                    <span class="rule-num">2.</span> <span class="nt">A</span> <span class="arr">&rarr;</span> <span class="t">b</span><span class="nt">A</span><br>
                    <span class="rule-num">3.</span> <span class="nt">A</span> <span class="arr">&rarr;</span> <span class="eps">&epsilon;</span><br>
                    <span class="rule-num">4.</span> <span class="nt">B</span> <span class="arr">&rarr;</span> <span class="t">c</span>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">Idea</span>
                    <div>
                        We expect terminal <code>b</code> to be in the selection set for rule 1 because
                        <code>S &rArr; ABc &rArr; bABc</code>. The algorithm below formalizes this idea.
                    </div>
                </div>
            </section>

            <section id="algorithm">
                <h2><span class="sec-icon">12</span> Algorithm to Find Selection Sets</h2>

                <p>
                    The algorithm has twelve steps. The phrase "any string" includes the null string
                    unless otherwise stated.
                </p>

                <div class="algo-list">
                    <div class="algo-step">
                        <div>
                            <strong>Find nullable rules and nullable nonterminals.</strong>
                            <p>A rule is nullable if <code>&epsilon;</code> can be derived from its right side.</p>
                        </div>
                    </div>
                    <div class="algo-step">
                        <div>
                            <strong>Find Begins Directly With (BDW).</strong>
                            <p><code>A BDW X</code> if a rule for <code>A</code> can begin directly with symbol <code>X</code>.</p>
                        </div>
                    </div>
                    <div class="algo-step">
                        <div>
                            <strong>Find Begins With (BW).</strong>
                            <p><code>BW</code> is the reflexive transitive closure of <code>BDW</code>, plus <code>a BW a</code> for every terminal.</p>
                        </div>
                    </div>
                    <div class="algo-step">
                        <div>
                            <strong>Find <code>First(x)</code> for every symbol.</strong>
                            <p><code>First(A)</code> is the set of terminals that can begin a form derived from <code>A</code>.</p>
                        </div>
                    </div>
                    <div class="algo-step">
                        <div>
                            <strong>Find <code>First</code> for the right side of each rule.</strong>
                            <p>Union first sets from left to right, stopping when a non-nullable symbol is reached.</p>
                        </div>
                    </div>
                    <div class="algo-step">
                        <div>
                            <strong>Find Is Followed Directly By (FDB).</strong>
                            <p><code>B FDB X</code> when <code>B</code> can be directly followed by <code>X</code> in a rule.</p>
                        </div>
                    </div>
                    <div class="algo-step">
                        <div>
                            <strong>Find Is Direct End Of (DEO).</strong>
                            <p><code>X DEO A</code> when symbol <code>X</code> can appear at the direct end of a rule defining <code>A</code>.</p>
                        </div>
                    </div>
                    <div class="algo-step">
                        <div>
                            <strong>Find Is End Of (EO).</strong>
                            <p><code>EO</code> is the reflexive transitive closure of <code>DEO</code>, plus reflexive pairs for nullable nonterminals.</p>
                        </div>
                    </div>
                    <div class="algo-step">
                        <div>
                            <strong>Find Is Followed By (FB).</strong>
                            <p>If <code>W EO X</code>, <code>X FDB Y</code>, and <code>Y BW Z</code>, then <code>W FB Z</code>.</p>
                        </div>
                    </div>
                    <div class="algo-step">
                        <div>
                            <strong>Extend <code>FB</code> to include the endmarker.</strong>
                            <p>If <code>A EO S</code>, where <code>S</code> is the start symbol, add <code>A FB ,</code>.</p>
                        </div>
                    </div>
                    <div class="algo-step">
                        <div>
                            <strong>Find <code>Fol(A)</code> for nullable nonterminals.</strong>
                            <p><code>Fol(A) = { t | A FB t }</code>, where <code>t</code> is a terminal or endmarker.</p>
                        </div>
                    </div>
                    <div class="algo-step">
                        <div>
                            <strong>Find <code>Sel(n)</code> for every rule.</strong>
                            <p>For a non-nullable rule use <code>First(&alpha;)</code>. For a nullable rule use <code>First(&alpha;) &cup; Fol(A)</code>.</p>
                        </div>
                    </div>
                </div>

                <div class="note-box pro">
                    <span class="box-icon">Note</span>
                    <div>
                        If the grammar contains no nullable rules, the selection sets can be obtained
                        after step 5, so steps 6 through 11 may be skipped.
                    </div>
                </div>
            </section>

            <section id="g15-result">
                <h2><span class="sec-icon">G15</span> Applying the Algorithm to G15</h2>

                <div class="mini-grid">
                    <div class="mini-card">
                        <h4>Step 1</h4>
                        <p>Nullable rule: rule 3. Nullable nonterminal: <code>A</code>.</p>
                        <div class="set-row">
                            <span class="set-chip epsilon">A &rarr; &epsilon;</span>
                        </div>
                    </div>

                    <div class="mini-card">
                        <h4>Steps 4 and 5</h4>
                        <p>First sets and right-side first sets:</p>
                        <div class="set-row">
                            <span class="set-chip">First(S) = { b, c }</span>
                            <span class="set-chip">First(A) = { b }</span>
                            <span class="set-chip">First(B) = { c }</span>
                            <span class="set-chip">First(ABc) = { b, c }</span>
                        </div>
                    </div>

                    <div class="mini-card">
                        <h4>Step 11</h4>
                        <p>Only nullable nonterminals need follow sets for selection sets.</p>
                        <div class="set-row">
                            <span class="set-chip follow">Fol(A) = { c }</span>
                        </div>
                    </div>
                </div>

                <h3>Important Relations for G15</h3>
                <div class="table-scroll">
                    <table class="machine-table">
                        <thead>
                            <tr>
                                <th>Relation</th>
                                <th>Pairs from the note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="row-head">BDW</td>
                                <td><code>S BDW A</code>, <code>S BDW B</code>, <code>A BDW b</code>, <code>B BDW c</code></td>
                            </tr>
                            <tr>
                                <td class="row-head">BW</td>
                                <td><code>S BW A</code>, <code>S BW B</code>, <code>A BW b</code>, <code>B BW c</code>, <code>S BW b</code>, <code>S BW c</code>, reflexive terminal/nonterminal pairs</td>
                            </tr>
                            <tr>
                                <td class="row-head">FDB</td>
                                <td><code>A FDB B</code>, <code>B FDB c</code></td>
                            </tr>
                            <tr>
                                <td class="row-head">DEO</td>
                                <td><code>c DEO S</code>, <code>A DEO A</code>, <code>b DEO A</code>, <code>c DEO B</code></td>
                            </tr>
                            <tr>
                                <td class="row-head">FB</td>
                                <td><code>A FB B</code>, <code>A FB c</code>, <code>b FB B</code>, <code>b FB c</code>, <code>B FB c</code>, <code>c FB c</code>, <code>S FB ,</code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Selection Sets for G15</h3>
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
                                <td><code>S &rarr; ABc</code></td>
                                <td><span class="set-chip">{ b, c }</span></td>
                            </tr>
                            <tr>
                                <td class="row-head">2</td>
                                <td><code>A &rarr; bA</code></td>
                                <td><span class="set-chip">{ b }</span></td>
                            </tr>
                            <tr>
                                <td class="row-head">3</td>
                                <td><code>A &rarr; &epsilon;</code></td>
                                <td><span class="set-chip epsilon">Fol(A) = { c }</span></td>
                            </tr>
                            <tr>
                                <td class="row-head">4</td>
                                <td><code>B &rarr; c</code></td>
                                <td><span class="set-chip">{ c }</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="note-box key">
                    <span class="box-icon">Answer</span>
                    <div>
                        G15 is <strong>LL(1)</strong> because the rules defining <code>A</code>
                        have disjoint selection sets: rule 2 has <code>{ b }</code> and rule 3 has
                        <code>{ c }</code>.
                    </div>
                </div>
            </section>

            <section id="machine">
                <h2><span class="sec-icon">PDM</span> Pushdown Machines for LL(1) Grammars</h2>

                <p>
                    Once the selection sets are known, the pushdown machine is constructed exactly as
                    for quasi-simple grammars. For a rule <code>A &rarr; &alpha;</code>, fill the row
                    for <code>A</code> and the columns in that rule's selection set with
                    <code>Rep(&alpha;<sup>r</sup>), Retain</code>. For epsilon rules, use
                    <code>Pop, Retain</code>.
                </p>

                <h3>Pushdown Machine for G15</h3>
                <div class="table-scroll">
                    <table class="machine-table">
                        <thead>
                            <tr>
                                <th>Stack Top</th>
                                <th>b</th>
                                <th>c</th>
                                <th>,</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="row-head">S</td>
                                <td class="action">Rep(cBA), Retain</td>
                                <td class="action">Rep(cBA), Retain</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-head">A</td>
                                <td class="action">Rep(Ab), Retain</td>
                                <td class="eps-action">Pop, Retain</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-head">B</td>
                                <td class="reject">Reject</td>
                                <td class="action">Rep(c), Retain</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-head">b</td>
                                <td class="action">Pop, Advance</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-head">c</td>
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
            </section>

            <section id="rdp">
                <h2><span class="sec-icon">RDP</span> Recursive Descent for LL(1) Grammars</h2>

                <p>
                    Recursive descent construction is also the same as before: when implementing each
                    rule, check whether the current input symbol is in the selection set for that rule.
                </p>

                <h3>Recursive Descent Parser for G15</h3>
                <div class="code-panel">
                    <span class="kw">void</span> <span class="fn">parse</span>()<br>
                    {<br>
                    &nbsp;&nbsp;inp = getInp();<br>
                    &nbsp;&nbsp;<span class="fn">S</span>();<br>
                    &nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">','</span>) accept();<br>
                    &nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    }<br><br>

                    <span class="kw">void</span> <span class="fn">S</span>()<br>
                    {<br>
                    &nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">'b'</span> || inp == <span class="ch">'c'</span>) <span class="cm">// apply rule 1</span><br>
                    &nbsp;&nbsp;{<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">A</span>();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">B</span>();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">'c'</span>) inp = getInp();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    &nbsp;&nbsp;}<br>
                    &nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    }<br><br>

                    <span class="kw">void</span> <span class="fn">A</span>()<br>
                    {<br>
                    &nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">'b'</span>) <span class="cm">// apply rule 2</span><br>
                    &nbsp;&nbsp;{<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;inp = getInp();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">A</span>();<br>
                    &nbsp;&nbsp;}<br>
                    &nbsp;&nbsp;<span class="kw">else if</span> (inp == <span class="ch">'c'</span>) ; <span class="cm">// apply rule 3: A &rarr; epsilon</span><br>
                    &nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    }<br><br>

                    <span class="kw">void</span> <span class="fn">B</span>()<br>
                    {<br>
                    &nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">'c'</span>) inp = getInp(); <span class="cm">// apply rule 4</span><br>
                    &nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    }
                </div>

                <div class="note-box warn">
                    <span class="box-icon">Note</span>
                    <div>
                        When processing rule 1, do not read an input symbol before calling
                        <code>A()</code>. Input is read only when a terminal in the grammar rule is
                        actually encountered.
                    </div>
                </div>
            </section>

            <section id="sample">
                <h2><span class="sec-icon">4.3</span> Sample Problem 4.3</h2>

                <p>
                    Show the sequence of stacks that occurs when the pushdown machine of Figure 4.11
                    parses the string <code>bcc</code>.
                </p>

                <div class="stack-strip">
                    <div class="stack-card">
                        <div class="input">start</div>
                        <div class="stack">S,</div>
                    </div>
                    <div class="stack-card">
                        <div class="input">rule 1</div>
                        <div class="stack">ABc,</div>
                    </div>
                    <div class="stack-card">
                        <div class="input">rule 2</div>
                        <div class="stack">bABc,</div>
                    </div>
                    <div class="stack-card">
                        <div class="input">match b</div>
                        <div class="stack">ABc,</div>
                    </div>
                    <div class="stack-card">
                        <div class="input">rule 3</div>
                        <div class="stack">Bc,</div>
                    </div>
                    <div class="stack-card">
                        <div class="input">rule 4</div>
                        <div class="stack">cc,</div>
                    </div>
                    <div class="stack-card">
                        <div class="input">match c</div>
                        <div class="stack">c,</div>
                    </div>
                    <div class="stack-card">
                        <div class="input">match c</div>
                        <div class="stack">,</div>
                    </div>
                    <div class="stack-card">
                        <div class="input">endmarker</div>
                        <div class="stack">Accept</div>
                    </div>
                </div>

                <div class="note-box key">
                    <span class="box-icon">Trace</span>
                    <div>
                        The stack sequence is:
                        <code>S, &rArr; ABc, &rArr; bABc, &rArr; ABc, &rArr; Bc, &rArr; cc, &rArr; c, &rArr; , &rArr; Accept</code>.
                    </div>
                </div>
            </section>

            <div class="chapter-nav">
                <a href="c4_2.php" class="nav-btn">
                    <span>&larr;</span>
                    <div><span class="btn-sub">Back to</span>4.2 Quasi-Simple Grammars</div>
                </a>
                <a href="?complete=1" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.3.1 Practice
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