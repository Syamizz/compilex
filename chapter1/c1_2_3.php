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
$page = 9;
$nextPage = "c1_2_4.php";

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
    <title>Chapter 1.2 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c1_2.css">
    
</head>

<body>

    <?php include 'navbar_c1.php'; ?>

    <div class="note-container">

        <!-- ── Sidebar TOC ─────────────────────────────────── -->
        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#global-opt" class="toc-link">1.2.3 Global Optimisation</a></li>
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

        <!-- ── Main content ───────────────────────────────── -->
        <article class="content">

            <header class="content-header">
                <div class="chapter-tag">📘 Chapter 1 · Section 1.2</div>
                <h1>The Phases of a Compiler</h1>
                <div class="metadata">
                    <span>⏱ 18 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>⚙️ Compiler Theory</span>
                </div>
            </header>



            <!-- ══════════════════════════════════════════════ -->
            <!-- 1.2.3 Global Optimisation                     -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="global-opt">
                <h2><span class="sec-icon">⚡</span> 1.2.3 Global Optimisation</h2>
                <p>
                    The global optimisation phase is <strong>optional</strong>. Its purpose is to make the object program
                    more efficient in space and/or time. It examines the <strong>sequence of atoms</strong> from the parser
                    to find redundant, unreachable, or inefficient code. Because it runs before the code generator, it is
                    also called <strong>machine-independent optimisation</strong>.
                </p>

                <h3>📌 Example 1 — Dead Code Elimination</h3>
                <p>Unreachable statements after an unconditional <code>goto</code> can never execute and should be removed:</p>
                <div class="code-wrap">
                    <div class="code-header">
                        <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                        <span class="code-lang">Before Optimisation</span>
                    </div>
                    <pre><code>  stmt1
  go to label1
  stmt2          <span class="cm">// ← unreachable — can never execute</span>
  stmt3          <span class="cm">// ← unreachable — can never execute</span>
label2: stmt4</code></pre>
                </div>
                <p><code>stmt2</code> and <code>stmt3</code> can never be executed. They are <strong>unreachable</strong> and can be eliminated from the object program.</p>

                <h3>📌 Example 2 — Loop Invariant Code Motion</h3>
                <p>
                    If a computation inside a loop does not depend on the loop variable, it is a
                    <strong>loop invariant</strong> and can be moved outside the loop:
                </p>

                <div class="opt-grid">
                    <div class="opt-before">
                        <div class="opt-label">Before — 100,000 sqrt calls</div>
                        <div class="code-wrap" style="margin:0;">
                            <pre><code><span class="kw">for</span> (i=<span class="num">1</span>; i&lt;=<span class="num">100000</span>; i++) {
  x = Math.sqrt(y);  <span class="cm" style="background:rgba(239,68,68,.15); display:block; margin:0 -20px; padding:0 20px;">// called 100,000×!</span>
  System.out.println(x+i);
}</code></pre>
                        </div>
                    </div>
                    <div class="opt-arrow">→</div>
                    <div class="opt-after">
                        <div class="opt-label">After — 1 sqrt call</div>
                        <div class="code-wrap" style="margin:0;">
                            <pre><code><span class="cm" style="background:rgba(16,185,129,.08); display:block; margin:0 -20px; padding:0 20px;">x = Math.sqrt(y); // moved out!</span>
<span class="kw">for</span> (i=<span class="num">1</span>; i&lt;=<span class="num">100000</span>; i++) {
  System.out.println(x+i);
}</code></pre>
                        </div>
                    </div>
                </div>
                <p>This eliminates <strong>99,999 unnecessary calls</strong> to the sqrt method at run time.</p>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Debugging caveat:</strong> Global optimisation can seriously impact run-time debugging. If
                        <code>y</code> was negative causing a run-time error in <code>sqrt</code>, the user would not know
                        the actual source location because the compiler moved the statement without informing the programmer.
                        Most compilers provide a switch to <strong>turn optimisation on/off</strong> — off for debugging, on
                        for production builds.
                    </div>
                </div>
            </section>






            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="c1_2_2.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous section</span>1.2.2 Syntax Analysis Phase</div>
                </a>
                <a href="?complete=9" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>Chapter 1.2.4: Code Generation</div>
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

        // ── Copy code ────────────────────────────────────────────
        function copyBlock(id, btn) {
            const text = document.getElementById(id).innerText;
            navigator.clipboard.writeText(text).then(() => {
                btn.textContent = 'Copied!';
                setTimeout(() => btn.textContent = 'Copy', 1800);
            });
        }
    </script>

</body>

</html>