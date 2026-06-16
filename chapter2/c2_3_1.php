<?php
session_start();
include '../dbconn.php';
include '../save_progress.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$chapter = 2;
$page = 12;
$nextPage = "c2_3_2.php";

if (isset($_GET['complete']) && $_GET['complete'] == '12') {
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
    <title>Chapter 2.3 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c2/c2_3.css">
</head>

<body>

    <?php include 'navbar_c2.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#seq" class="toc-link">2.3.1 Sequential Search</a></li>
            </ul>
            <div class="toc-progress">
                <div class="toc-progress-label"><span>Progress</span><span id="pct-label">0%</span></div>
                <div class="toc-bar">
                    <div class="toc-bar-fill" id="toc-bar"></div>
                </div>
            </div>
        </nav>

        <article class="content">

            <header class="content-header">
                <div class="chapter-tag">📘 Chapter 2 · Section 2.3</div>
                <h1>Lexical Tables</h1>
                <div class="metadata">
                    <span>⏱ 16 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>🗃️ Data Structures</span>
                </div>
            </header>





            <!-- ══════════════════════════════════════════════ -->
            <!-- 2.3.1 Sequential Search                       -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="seq">
                <h2><span class="sec-icon">📋</span> 2.3.1 Sequential Search</h2>
                <p>
                    The table is organised as an <strong>array</strong> or <strong>linked list</strong>.
                    Each time a new word is encountered, the list is scanned from the beginning. If the word
                    is not already in the list, it is <strong>added at the end</strong>.
                </p>
                <p>
                    To insert the <em>k</em>-th word, the scanner must compare it against all <em>k−1</em>
                    previous entries. Summing over all n words: 0 + 1 + 2 + … + (n−1) = n(n−1)/2 comparisons,
                    giving <strong>O(n²)</strong> total build time.
                </p>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>When to use sequential search:</strong> It is easy to implement but not efficient as the
                        number of words grows. It is generally <em>not</em> used for symbol tables or line number tables.
                        It <em>is</em> acceptable for small tables such as statement labels or constants.
                    </div>
                </div>
            </section>








            <div class="chapter-nav">
                <a href="c2_3_0.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>2.3 Lexical Tables</div>
                </a>
                <a href="?complete=12" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>2.3.2 Binary Search Tree</div>
                    <span>→</span>
                </a>
            </div>

        </article>
    </div>

    <script>
        // ── TOC scroll progress ──────────────────────────────────
        const sections = document.querySelectorAll('section[id]');
        const tocLinks = document.querySelectorAll('.toc-link');
        const bar = document.getElementById('toc-bar');
        const pctLabel = document.getElementById('pct-label');
        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            const total = document.body.scrollHeight - window.innerHeight;
            const pct = Math.round((scrolled / total) * 100);
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