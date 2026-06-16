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
$page    = 5;
$nextPage = "c4_1_2.php"; // next sub-page (adjust as needed)

if (isset($_GET['complete']) && $_GET['complete'] == '5') {
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
                <li><a href="#selection" class="toc-link">Selection Sets</a></li>
                <li><a href="#pdm" class="toc-link">Pushdown Machine</a></li>
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



            <div class="chapter-nav">
                <a href="c4_1_0.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>4.1 Simple Grammars </div>
                </a>
                <a href="?complete=5" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        Continue 4.1 Simple Grammars
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