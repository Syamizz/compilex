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
$page    = 7;
$nextPage = "c4_2_1.php"; // next sub-page (adjust as needed)

if (isset($_GET['complete']) && $_GET['complete'] == '7') {
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
                <li><a href="#intro" class="toc-link">4.2 Quasi-Simple Grammars</a></li>
                <li><a href="#follow" class="toc-link">Follow Sets</a></li>
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



            <div class="chapter-nav">
                <a href="c4_1_2.php" class="nav-btn">
                    <span>&larr;</span>
                    <div><span class="btn-sub">Back to</span>4.1 Simple Grammars</div>
                </a>
                <a href="?complete=7" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.2 Quasi-Simple Grammars (Part 2)
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