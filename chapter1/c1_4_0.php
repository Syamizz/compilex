<?php
session_start();
include '../dbconn.php';
include '../save_progress.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$chapter = 1;
$page = 17;
$nextPage = "c1_4_1.php";

if (isset($_GET['complete']) && $_GET['complete'] == $page) {
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
    <title>Chapter 1.4 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c1_4.css">
</head>

<body>

    <?php include 'navbar_c1.php'; ?>

    <div class="note-container">

        <!-- ── Sidebar TOC ─────────────────────────────────── -->
        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#overview" class="toc-link">1.4 What is Decaf?</a></li>
            </ul>
            <div class="toc-progress">
                <div class="toc-progress-label"><span>Progress</span><span id="pct-label">0%</span></div>
                <div class="toc-bar">
                    <div class="toc-bar-fill" id="toc-bar"></div>
                </div>
            </div>
        </nav>

        <!-- ── Main content ───────────────────────────────── -->
        <article class="content">

            <header class="content-header">
                <div class="chapter-tag">📘 Chapter 1 · Section 1.4</div>
                <h1>Case Study: Decaf</h1>
                <div class="metadata">
                    <span>⏱ 12 min read</span>
                    <span>🎯 Beginner</span>
                    <span>☕ Decaf Language</span>
                </div>
            </header>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 1.4 Overview                                   -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="overview">
                <h2><span class="sec-icon">☕</span> What is Decaf?</h2>
                <p>
                    As we study each phase of compilation throughout this textbook, we need a real language to
                    apply the concepts to. For this purpose the textbook defines a language called <strong>Decaf</strong>
                    — a relatively simple <strong>subset of Java</strong>. The implementation of the Decaf compiler
                    will serve as an extended case study, with the last section of each chapter showing how the
                    concepts apply to an actual compiler.
                </p>
                <p>
                    Decaf is a <strong>"bare bones" version of Java</strong>. It strips away complex features while
                    retaining enough to write genuinely useful programs — making it an ideal vehicle for learning
                    compiler design without being overwhelmed by language complexity.
                </p>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        <strong>Why "Decaf"?</strong> Just like decaffeinated coffee is Java without the caffeine,
                        Decaf is Java without the complex features — same structure, simplified content.
                        The lexical specifications (free-format, whitespace as delimiters, numeric constants,
                        comments) are the same as standard C.
                    </div>
                </div>
            </section>










            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="c1_3_5.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>1.3 Implementation Techniques</div>
                </a>
                <a href="?complete=17" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next chapter</span>1.4.1 What is Decaf?</div>
                    <span>→</span>
                </a>
            </div>

        </article>
    </div><!-- /.note-container -->

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




        document.querySelectorAll('.line').forEach(line => {
            line.addEventListener('click', () => {
                document.querySelectorAll('.line').forEach(l => l.classList.remove('active'));
                line.classList.add('active');
                const idx = parseInt(line.dataset.idx);
                const a = annotations[idx];
                document.getElementById('ann-label').textContent = a.label;
                document.getElementById('ann-title').textContent = a.title;
                document.getElementById('ann-desc').textContent = a.desc;
                const hintEl = document.getElementById('ann-hint');
                if (a.hint) {
                    hintEl.textContent = a.hint;
                    hintEl.style.display = 'block';
                } else {
                    hintEl.style.display = 'none';
                }
            });
        });
    </script>

</body>

</html>