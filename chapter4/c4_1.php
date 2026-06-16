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
$page    = 2;
$nextPage = "c4_1_1.php"; // next sub-page (adjust as needed)

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
    <title>Chapter 4.1 - CompileX</title>

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

        .grammar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
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

        .sel-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            min-height: 30px;
            padding: 4px 10px;
            border-radius: 999px;
            background: #EEF2FF;
            color: #4338CA;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            font-weight: 700;
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

        .table-scroll {
            overflow-x: auto;
            margin: 16px 0;
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

        .stack-strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(86px, 1fr));
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
    </style>
</head>

<body>

    <?php include 'navbar_c4.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#intro" class="toc-link">4.1 Simple Grammars</a></li>
                <li><a href="#selection" class="toc-link">Selection Sets</a></li>
                <li><a href="#pdm" class="toc-link">Pushdown Machine</a></li>
                <li><a href="#construction" class="toc-link">Machine Construction</a></li>
                <li><a href="#rdp" class="toc-link">Recursive Descent</a></li>
                <li><a href="#sample" class="toc-link">Sample Problem 4.1</a></li>
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
                <div class="chapter-tag">📘 Chapter 4.1</div>
                <h1>Simple Grammars</h1>
                <div class="metadata">
                    <span>⏱ 25 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>⚙️ Top Down Parsing</span>
                </div>
            </header>

            <section id="intro">
                <h2><span class="sec-icon">🧩</span> What is a Simple Grammar?</h2>

                <p>
                    To construct top down parsers mechanically, we first restrict the form of
                    context-free grammar rules. These restricted grammars are not powerful enough
                    for real compiler design, but they are a clear first step toward understanding
                    top down parsing.
                </p>

                <div class="note-box key">
                    <span class="box-icon">📖</span>
                    <div>
                        <strong>Definition:</strong> A grammar is a <strong>simple grammar</strong>
                        if every rule has the form <code>A → aα</code>, where <code>A</code> is a
                        nonterminal, <code>a</code> is a terminal, and <code>α</code> is any string
                        of terminals and nonterminals. Also, every pair of rules defining the same
                        nonterminal must begin with <strong>different terminals</strong>.
                    </div>
                </div>

                <div class="grammar-grid">
                    <div class="mini-card">
                        <h4>G9 — Simple</h4>
                        <div class="grammar-box">
                            <span class="rule-num">1.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="t">a</span><span class="nt">S</span><span class="t">b</span><br>
                            <span class="rule-num">2.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="t">b</span>
                        </div>
                        <p>Both rules begin with terminals, and the two rules for <code>S</code> begin with different terminals.</p>
                    </div>

                    <div class="mini-card">
                        <h4>G10 — Not Simple</h4>
                        <div class="grammar-box">
                            <span class="rule-num">1.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="t">a</span><span class="nt">S</span><span class="t">b</span><br>
                            <span class="rule-num">2.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="t">ε</span>
                        </div>
                        <p>This grammar is not simple because it contains an epsilon rule.</p>
                    </div>

                    <div class="mini-card">
                        <h4>G11 — Not Simple</h4>
                        <div class="grammar-box">
                            <span class="rule-num">1.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="t">a</span><span class="nt">S</span><span class="t">b</span><span class="nt">S</span><br>
                            <span class="rule-num">2.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="t">a</span>
                        </div>
                        <p>This grammar is not simple because both rules defining <code>S</code> begin with <code>a</code>.</p>
                    </div>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        A language that can be specified by a simple grammar is called a
                        <strong>simple language</strong>.
                    </div>
                </div>
            </section>

            <section id="selection">
                <h2><span class="sec-icon">🎯</span> Selection Sets</h2>

                <p>
                    A top down parser must decide which grammar rule to apply as the input string is
                    being parsed. The set of input symbols that imply the application of a grammar
                    rule is called the <strong>selection set</strong> of that rule.
                </p>

                <div class="note-box key">
                    <span class="box-icon">📌</span>
                    <div>
                        For simple grammars, the selection set of each rule contains exactly one
                        terminal symbol: the terminal that begins the right-hand side of the rule.
                    </div>
                </div>

                <h3>Selection Sets for G9</h3>
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
                                <td><code>S → aSb</code></td>
                                <td><span class="sel-badge">{ a }</span></td>
                            </tr>
                            <tr>
                                <td class="row-head">2</td>
                                <td><code>S → b</code></td>
                                <td><span class="sel-badge">{ b }</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Grammar G12</h3>
                <p>
                    In the grammar below, each rule defining <code>S</code> begins with a different
                    terminal. Therefore, the next input symbol can guide the parser.
                </p>

                <div class="grammar-box">
                    <span class="rule-num">G12:</span><br>
                    <span class="rule-num">1.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="t">a</span> <span class="t">b</span> <span class="nt">S</span> <span class="t">d</span><br>
                    <span class="rule-num">2.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="t">b</span> <span class="t">a</span> <span class="nt">S</span> <span class="t">d</span><br>
                    <span class="rule-num">3.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="t">d</span>
                </div>

                <div class="tree-wrap">
                    <svg viewBox="0 0 520 250" width="480" xmlns="http://www.w3.org/2000/svg" font-family="JetBrains Mono, monospace" font-size="13">
                        <defs>
                            <marker id="arr" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
                                <path d="M0,0 L0,6 L8,3 z" fill="#6366F1" />
                            </marker>
                        </defs>
                        <text x="20" y="36" fill="#374151">Input: abbaddd</text>

                        <text x="60" y="82" fill="#4338CA" font-weight="700">a</text>
                        <line x1="76" y1="78" x2="136" y2="78" stroke="#6366F1" marker-end="url(#arr)" />
                        <text x="150" y="82" fill="#1E1B4B">apply rule 1: S → abSd</text>

                        <text x="60" y="132" fill="#4338CA" font-weight="700">b</text>
                        <line x1="76" y1="128" x2="136" y2="128" stroke="#6366F1" marker-end="url(#arr)" />
                        <text x="150" y="132" fill="#1E1B4B">apply rule 2: S → baSd</text>

                        <text x="60" y="182" fill="#4338CA" font-weight="700">d</text>
                        <line x1="76" y1="178" x2="136" y2="178" stroke="#6366F1" marker-end="url(#arr)" />
                        <text x="150" y="182" fill="#1E1B4B">apply rule 3: S → d</text>

                        <text x="60" y="226" fill="#065F46" font-weight="700">Derivation:</text>
                        <text x="150" y="226" fill="#065F46">S ⇒ abSd ⇒ abbaSdd ⇒ abbaddd</text>
                    </svg>
                    <figcaption>Figure 4.4 idea — the underscored/current input symbol determines which rule is applied.</figcaption>
                </div>
            </section>

            <section id="pdm">
                <h2><span class="sec-icon">⚙️</span> Parsing Simple Languages with Pushdown Machines</h2>

                <p>
                    For any simple grammar, we can construct a one-state extended pushdown machine
                    that parses the language of the grammar. The machine begins with a stack
                    containing the bottom marker <code>,</code> and the starting nonterminal.
                </p>

                <h3>Grammar G13</h3>
                <div class="grammar-box">
                    <span class="rule-num">G13:</span><br>
                    <span class="rule-num">1.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="t">a</span><span class="nt">S</span><span class="nt">B</span><br>
                    <span class="rule-num">2.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="t">b</span><br>
                    <span class="rule-num">3.</span> <span class="nt">B</span> <span class="arr">→</span> <span class="t">a</span><br>
                    <span class="rule-num">4.</span> <span class="nt">B</span> <span class="arr">→</span> <span class="t">b</span><span class="nt">B</span><span class="t">a</span>
                </div>

                <p>
                    If the top stack symbol is a nonterminal, the current input symbol chooses the
                    grammar rule by using the selection set. If the top stack symbol is a terminal,
                    it must match the current input symbol; if it matches, the machine pops the stack
                    and advances the input pointer.
                </p>

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
                                <td class="action">Rep(BSa), Retain</td>
                                <td class="action">Rep(b), Retain</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-head">B</td>
                                <td class="action">Rep(a), Retain</td>
                                <td class="action">Rep(aBb), Retain</td>
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

                <div class="note-box pro">
                    <span class="box-icon">📌</span>
                    <div>
                        <strong>Why reverse the rule?</strong> For <code>S → aSB</code>, the machine
                        uses <code>Rep(BSa)</code> because stack replacement pushes the right-hand
                        side in reverse order. This leaves <code>a</code> on top to be matched first.
                    </div>
                </div>
            </section>

            <section id="construction">
                <h2><span class="sec-icon">🛠️</span> Mechanical Construction</h2>

                <p>
                    The note gives a direct method for constructing the extended pushdown machine
                    from any simple grammar.
                </p>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Construction Step</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Build a table with each column labeled by a terminal symbol and the endmarker <code>,</code>. Label each row by a nonterminal, terminal, and the bottom marker <code>,</code>.</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>For each grammar rule <code>A → aα</code>, fill row <code>A</code> and column <code>a</code> with <code>Rep(α<sup>r</sup>a), Retain</code>, where <code>α<sup>r</sup></code> is <code>α</code> reversed.</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>For each terminal <code>a</code>, fill row <code>a</code> and column <code>a</code> with <code>Pop, Advance</code>.</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Fill row <code>,</code> and column <code>,</code> with <code>Accept</code>.</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Fill all other cells with <code>Reject</code>.</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Initialize the stack with <code>,</code> and the starting nonterminal.</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section id="rdp">
                <h2><span class="sec-icon">🔁</span> Recursive Descent Parsers</h2>

                <p>
                    A second way to implement a parser for simple grammars is called
                    <strong>recursive descent</strong>. A method is written for each nonterminal.
                    Each method assumes that the first input symbol of the example it is looking for
                    has already been read.
                </p>

                <h3>Recursive Descent Parser for G13</h3>
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
                    &nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">'a'</span>) <span class="cm">// apply rule 1: S → aSB</span><br>
                    &nbsp;&nbsp;{<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;inp = getInp();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">S</span>();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">B</span>();<br>
                    &nbsp;&nbsp;}<br>
                    &nbsp;&nbsp;<span class="kw">else if</span> (inp == <span class="ch">'b'</span>) inp = getInp(); <span class="cm">// apply rule 2: S → b</span><br>
                    &nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    }<br><br>

                    <span class="kw">void</span> <span class="fn">B</span>()<br>
                    {<br>
                    &nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">'a'</span>) inp = getInp(); <span class="cm">// apply rule 3: B → a</span><br>
                    &nbsp;&nbsp;<span class="kw">else if</span> (inp == <span class="ch">'b'</span>) <span class="cm">// apply rule 4: B → bBa</span><br>
                    &nbsp;&nbsp;{<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;inp = getInp();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">B</span>();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">'a'</span>) inp = getInp();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    &nbsp;&nbsp;}<br>
                    &nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    }
                </div>
            </section>

            <section id="sample">
                <h2><span class="sec-icon">🧪</span> Sample Problem 4.1</h2>

                <p>
                    Show a one-state pushdown machine and a recursive descent parser, showing methods
                    <code>S()</code> and <code>A()</code> only, for the following grammar.
                </p>

                <div class="grammar-box">
                    <span class="rule-num">1.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="t">0</span> <span class="nt">S</span> <span class="t">1</span> <span class="nt">A</span><br>
                    <span class="rule-num">2.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="t">1</span> <span class="t">0</span> <span class="nt">A</span><br>
                    <span class="rule-num">3.</span> <span class="nt">A</span> <span class="arr">→</span> <span class="t">0</span> <span class="nt">S</span> <span class="t">0</span><br>
                    <span class="rule-num">4.</span> <span class="nt">A</span> <span class="arr">→</span> <span class="t">1</span>
                </div>

                <div class="note-box key">
                    <span class="box-icon">✅</span>
                    <div>
                        This grammar is simple because rules 1 and 2, which define <code>S</code>,
                        begin with different terminals, and rules 3 and 4, which define <code>A</code>,
                        also begin with different terminals.
                    </div>
                </div>

                <h3>One-State Pushdown Machine</h3>
                <div class="table-scroll">
                    <table class="machine-table">
                        <thead>
                            <tr>
                                <th>Stack Top</th>
                                <th>0</th>
                                <th>1</th>
                                <th>,</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="row-head">S</td>
                                <td class="action">Rep(A1S0), Retain</td>
                                <td class="action">Rep(A01), Retain</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-head">A</td>
                                <td class="action">Rep(0S0), Retain</td>
                                <td class="action">Rep(1), Retain</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-head">0</td>
                                <td class="action">Pop, Advance</td>
                                <td class="reject">Reject</td>
                                <td class="reject">Reject</td>
                            </tr>
                            <tr>
                                <td class="row-head">1</td>
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
                    &nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">'0'</span>) <span class="cm">// apply rule 1</span><br>
                    &nbsp;&nbsp;{<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;inp = getInp();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">S</span>();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">'1'</span>) inp = getInp();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">A</span>();<br>
                    &nbsp;&nbsp;}<br>
                    &nbsp;&nbsp;<span class="kw">else if</span> (inp == <span class="ch">'1'</span>) <span class="cm">// apply rule 2</span><br>
                    &nbsp;&nbsp;{<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;inp = getInp();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">'0'</span>) inp = getInp();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">A</span>();<br>
                    &nbsp;&nbsp;}<br>
                    &nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    }<br><br>

                    <span class="kw">void</span> <span class="fn">A</span>()<br>
                    {<br>
                    &nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">'0'</span>) <span class="cm">// apply rule 3</span><br>
                    &nbsp;&nbsp;{<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;inp = getInp();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="fn">S</span>();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="kw">if</span> (inp == <span class="ch">'0'</span>) inp = getInp();<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    &nbsp;&nbsp;}<br>
                    &nbsp;&nbsp;<span class="kw">else if</span> (inp == <span class="ch">'1'</span>) inp = getInp(); <span class="cm">// apply rule 4</span><br>
                    &nbsp;&nbsp;<span class="kw">else</span> reject();<br>
                    }
                </div>
            </section>

            <div class="chapter-nav">
                <a href="c4_0.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Back to</span>4.0 Top Down Parsing</div>
                </a>
                <a href="?complete=1" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.1.1 Practice
                    </div>
                    <span>→</span>
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