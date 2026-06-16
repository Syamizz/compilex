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
$page = 11;
$nextPage = "c2_3_1.php";

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
                <li><a href="#overview" class="toc-link">2.3 Overview</a></li>
                <li><a href="#summary" class="toc-link">Method Comparison</a></li>
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
            <!-- 2.3 Overview                                   -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="overview">
                <h2><span class="sec-icon">🗃️</span> 2.3 Overview</h2>
                <p>
                    One of the most important functions of lexical analysis is the <strong>creation of tables</strong>
                    used later in the compiler. Such tables include:
                </p>
                <ul>
                    <li>A <strong>symbol table</strong> for identifiers</li>
                    <li>A <strong>table of numeric constants</strong></li>
                    <li>A <strong>table of string constants</strong></li>
                    <li><strong>Statement labels</strong> and <strong>line numbers</strong> (for languages like BASIC)</li>
                </ul>
                <p>
                    The three implementation techniques discussed below can apply to any of these tables.
                    The key requirement: each word should appear in the table <strong>exactly once</strong>,
                    regardless of how many times it appears in the source program.
                </p>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Method Comparison Summary                     -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="summary">
                <h2><span class="sec-icon">⚖️</span> Method Comparison</h2>

                <div class="method-grid">
                    <div class="method-card mc-bad">
                        <div class="mc-title">Sequential Search</div>
                        <div class="mc-complexity">O(n²)</div>
                        <div class="mc-desc">Array or linked list. Scan from start each time. Simple to implement, but slow for large tables. Generally not used for symbol tables.</div>
                    </div>
                    <div class="method-card mc-mid">
                        <div class="mc-title">Binary Search Tree</div>
                        <div class="mc-complexity">O(n log n) best · O(n²) worst</div>
                        <div class="mc-desc">Fast when balanced; degrades to linear if words are inserted in alphabetical order. Structure depends on insertion order.</div>
                    </div>
                    <div class="method-card mc-good">
                        <div class="mc-title">Hash Table</div>
                        <div class="mc-complexity">O(n) average</div>
                        <div class="mc-desc">Array of linked lists. A hash function maps each word to a bucket. Structure does not depend on insertion order. Best choice for large symbol tables.</div>
                    </div>
                </div>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Technique</th>
                            <th>Structure</th>
                            <th>Build time</th>
                            <th>Typical use</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Sequential Search</td>
                            <td>Array or linked list</td>
                            <td>O(n²)</td>
                            <td>Statement labels, small constant tables</td>
                        </tr>
                        <tr>
                            <td>Binary Search Tree</td>
                            <td>Binary tree (BST)</td>
                            <td>O(n log n) balanced / O(n²) degenerate</td>
                            <td>Symbol tables (when order matters)</td>
                        </tr>
                        <tr>
                            <td>Hash Table</td>
                            <td>Array of linked lists</td>
                            <td>O(n) average</td>
                            <td>Symbol tables, constant tables, line numbers</td>
                        </tr>
                    </tbody>
                </table>
            </section>







            <div class="chapter-nav">
                <a href="c2_2_2.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>2.2.2 Actions for Finite State Machines</div>
                </a>
                <a href="?complete=11" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>2.3.1 Sequential Search </div>
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