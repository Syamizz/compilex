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
$page    = 8;
$nextPage = "c4_2_2.php"; // next sub-page (adjust as needed)

if (isset($_GET['complete']) && $_GET['complete'] == '8') {
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
                <li><a href="#selection" class="toc-link">Selection Sets</a></li>
                <li><a href="#pdm" class="toc-link">Pushdown Machines</a></li>
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


            <div class="chapter-nav">
                <a href="c4_2_0.php" class="nav-btn">
                    <span>&larr;</span>
                    <div><span class="btn-sub">Back to</span>4.2 Quasi-Simple Grammars (Part 1)</div>
                </a>
                <a href="?complete=8" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.2 Quasi-Simple Grammars (Part 3)
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