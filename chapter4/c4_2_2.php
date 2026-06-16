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
$page    = 9;
$nextPage = "c4_3_0.php"; // next sub-page (adjust as needed)

if (isset($_GET['complete']) && $_GET['complete'] == '9') {
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
    <link rel="stylesheet" href="../css/c4/c4_2.css">

</head>

<body>

    <?php include 'navbar_c4.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">Contents</div>
            <ul>
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
                    <div><span class="btn-sub">Back to</span>4.2 Quasi-Simple Grammars (Part 2)</div>
                </a>
                <a href="?complete=9" class="nav-btn next">
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