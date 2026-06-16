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
$page    = 6;
$nextPage = "c4_2_0.php"; // next sub-page (adjust as needed)

if (isset($_GET['complete']) && $_GET['complete'] == '6') {
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

    <link rel="stylesheet" href="../css/c4/c4_1.css">
</head>

<body>

    <?php include 'navbar_c4.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
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
                <a href="c4_1_1.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>4.1 Simple Grammars </div>
                </a>
                <a href="?complete=6" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.2 Quasi-Simple Grammars
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