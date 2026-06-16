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
$page = 14;
$nextPage = "c1_3_3.php";

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
    <title>Chapter 1.3 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c1_3.css">
</head>

<body>

    <?php include 'navbar_c1.php'; ?>

    <div class="note-container">

        <!-- ── Sidebar TOC ─────────────────────────────────── -->
        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#cross" class="toc-link">1.3.2 Cross Compiling</a></li>
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
                <div class="chapter-tag">📘 Chapter 1 · Section 1.3</div>
                <h1>Implementation Techniques</h1>
                <div class="metadata">
                    <span>⏱ 16 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>⚙️ Compiler Theory</span>
                </div>
            </header>




            <!-- ══════════════════════════════════════════════ -->
            <!-- 1.3.2 Cross Compiling                         -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="cross">
                <h2><span class="sec-icon">🔀</span> 1.3.2 Cross Compiling</h2>
                <p>
                    New computers are constantly being produced. Developers face the challenge of producing a new
                    compiler for each existing programming language every time a new CPU is designed.
                    <strong>Cross compiling</strong> solves this without writing anything in machine language.
                </p>

                <h3>📌 Example — Cross Compiling Java from a Sun to a Mac (Figure 1.8)</h3>
                <p>
                    Suppose we already have a Java compiler for the Sun
                    (<code>C<sup>Java→Sun</sup><sub>Sun</sub></code>).
                    We design a new machine called a Mac and want to produce a Java compiler for it
                    (<code>C<sup>Java→Mac</sup><sub>Mac</sub></code>).
                    Instead of writing it in assembly, we <strong>write the compiler in Java</strong>
                    (<code>C<sup>Java→Mac</sup><sub>Java</sub></code>) and cross-compile it in two steps.
                </p>

                <div class="step-diagram">
                    <div class="step-diagram-header">
                        <span style="font-size:18px">🔀</span>
                        <h4>Figure 1.8 — Two-Step Cross Compilation</h4>
                    </div>
                    <div class="step-diagram-body">

                        <!-- Step 1 -->
                        <div class="step-row" style="margin-bottom:14px;">
                            <span class="step-badge">STEP 1</span>
                            <span class="step-desc">Use the Java compiler (written in Java) as input to the existing Sun Java compiler. Output: a Mac-targeting compiler that runs on the Sun.</span>
                        </div>
                        <div class="computer-diagram" style="margin:16px 0 24px;">
                            <div>
                                <div class="comp-io-label">Input</div>
                                <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--purple); background:var(--purple-s); padding:8px 14px; border-radius:10px;">C<sup>Java→Mac</sup><sub>Java</sub></div>
                            </div>
                            <div class="comp-arrow">→</div>
                            <div class="comp-box">
                                <div class="comp-name-tag">Sun</div>
                                <div class="comp-ram"><small>RAM</small>C<sup>Java→Sun</sup><sub>Sun</sub></div>
                            </div>
                            <div class="comp-arrow">→</div>
                            <div>
                                <div class="comp-io-label">Output</div>
                                <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--amber); background:#FFFBEB; padding:8px 14px; border-radius:10px;">C<sup>Java→Mac</sup><sub>Sun</sub></div>
                            </div>
                        </div>

                        <div style="border-left:3px solid var(--amber); padding-left:14px; margin-bottom:24px;">
                            <p style="font-size:13px; color:var(--muted); margin:0;">
                                We now have a compiler that translates Java to Mac machine code, but it <em>runs on the Sun</em> — not yet the Mac. We need one more step.
                            </p>
                        </div>

                        <!-- Step 2 -->
                        <div class="step-row" style="margin-bottom:14px;">
                            <span class="step-badge">STEP 2</span>
                            <span class="step-desc">Load the Step 1 output into the Sun. Feed the same Java compiler (written in Java) as input again. Output: a Mac compiler that runs natively on the Mac.</span>
                        </div>
                        <div class="computer-diagram" style="margin:16px 0;">
                            <div>
                                <div class="comp-io-label">Input</div>
                                <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--purple); background:var(--purple-s); padding:8px 14px; border-radius:10px;">C<sup>Java→Mac</sup><sub>Java</sub></div>
                            </div>
                            <div class="comp-arrow">→</div>
                            <div class="comp-box">
                                <div class="comp-name-tag">Sun</div>
                                <div class="comp-ram"><small>RAM</small>C<sup>Java→Mac</sup><sub>Sun</sub></div>
                            </div>
                            <div class="comp-arrow">→</div>
                            <div>
                                <div class="comp-io-label">Output ✅</div>
                                <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--green); background:#F0FDF4; padding:8px 14px; border-radius:10px; font-weight:700;">C<sup>Java→Mac</sup><sub>Mac</sub></div>
                            </div>
                        </div>

                        <div class="note-box key" style="margin-top:16px;">
                            <span class="box-icon">✅</span>
                            <div>
                                The final output is a Java compiler for the Mac, running natively on the Mac — exactly what we wanted. <strong>This entire process can be completed before a single Mac has been built</strong> — all we need is knowledge of the Mac's instruction set architecture.
                            </div>
                        </div>
                    </div>
                </div>
            </section>





            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="c1_3_1.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>1.3.1 Bootstrapping</div>
                </a>
                <a href="?complete=14" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>1.3.3 Intermediate Form</div>
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

        // ── Interactive technique explorer ───────────────────────
        function showTech(btn, id) {
            document.querySelectorAll('.demo-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.demo-stage').forEach(s => s.classList.remove('show'));
            btn.classList.add('active');
            document.getElementById(id).classList.add('show');
        }
    </script>

</body>

</html>