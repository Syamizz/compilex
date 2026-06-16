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
$nextPage = "c4_1_1.php"; // next sub-page (adjust as needed)

if (isset($_GET['complete']) && $_GET['complete'] == '4') {
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
                <li><a href="#intro" class="toc-link">4.1 Simple Grammars</a></li>
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



            <div class="chapter-nav">
                <a href="c4_0_2.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>4.0.3 Visualing Relations </div>
                </a>
                <a href="?complete=4" class="nav-btn next">
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