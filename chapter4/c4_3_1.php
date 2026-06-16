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
$page    = 11;
$nextPage = "c4_4_0.php"; // next sub-page (adjust as needed)

if (isset($_GET['complete']) && $_GET['complete'] == '11') {
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
    <link rel="stylesheet" href="../css/c4/c4_3.css">

</head>

<body>

    <?php include 'navbar_c4.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">Contents</div>
            <ul>
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
                <a href="c4_3_0.php" class="nav-btn">
                    <span>&larr;</span>
                    <div><span class="btn-sub">Back to</span>4.3 LL(1) Grammars (Part 1)</div>
                </a>
                <a href="?complete=11" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.4 Parsing Arithmetic Expressions Top Down
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