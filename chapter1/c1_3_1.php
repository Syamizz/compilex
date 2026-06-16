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
$page = 13;
$nextPage = "c1_3_2.php";

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
                <li><a href="#bootstrap" class="toc-link">1.3.1 Bootstrapping</a></li>
                <li><a href="#sp13" class="toc-link sub">↳ Sample Problem 1.3</a></li>
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
            <!-- 1.3.1 Bootstrapping                           -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="bootstrap">
                <h2><span class="sec-icon">🥾</span> 1.3.1 Bootstrapping</h2>
                <p>
                    The term <strong>bootstrapping</strong> comes from "pull yourself up by your bootstraps" and generally
                    involves using a program as input to itself. You may already know this from <em>bootstrap loaders</em>
                    used to initialise a computer at power-on — hence the expression "to boot" a computer.
                </p>
                <p>
                    In compiler terms, bootstrapping lets us build a full compiler for a language without writing the
                    entire thing in machine language. The idea: write two smaller, simpler compilers instead.
                </p>

                <h3>📌 Example — Bootstrapping Java onto a Sun Computer (Figure 1.7)</h3>
                <p>
                    Goal: produce a Java compiler for the Sun machine
                    (<span style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--purple);">C<sup>Java→Sun</sup><sub>Sun</sub></span>)
                    without writing the whole thing in assembly.
                </p>
                <p>
                    The <strong>subset language</strong> "Sub" is simply Java without several superfluous features
                    (enumerated types, unions, switch statements, etc.).
                </p>

                <div class="step-diagram">
                    <div class="step-diagram-header">
                        <span style="font-size:18px">🔧</span>
                        <h4>Figure 1.7 — Two-Step Bootstrapping Process</h4>
                    </div>
                    <div class="step-diagram-body">

                        <p style="font-size:13px; color:var(--muted); margin-bottom:20px;">
                            We write <strong>two small compilers</strong> instead of one large one:
                        </p>

                        <!-- What we write -->
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:24px;">
                            <div style="background:var(--purple-s); border-radius:var(--radius); padding:16px;">
                                <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:12px; color:var(--purple); margin-bottom:8px;">WE WRITE (1) — in machine language</div>
                                <div style="font-family:'JetBrains Mono',monospace; font-size:16px; color:var(--purple); margin-bottom:6px;">C<sup>Sub→Sun</sup><sub>Sun</sub></div>
                                <div style="font-size:13px; color:var(--muted);">A compiler for the <em>subset</em> of Java, written in Sun machine language. Small and manageable to write by hand.</div>
                            </div>
                            <div style="background:var(--purple-s); border-radius:var(--radius); padding:16px;">
                                <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:12px; color:var(--purple); margin-bottom:8px;">WE WRITE (2) — in the subset language</div>
                                <div style="font-family:'JetBrains Mono',monospace; font-size:16px; color:var(--purple); margin-bottom:6px;">C<sup>Java→Sun</sup><sub>Sub</sub></div>
                                <div style="font-size:13px; color:var(--muted);">A compiler for <em>full Java</em>, written in the Sub language. Much easier to write than assembly.</div>
                            </div>
                        </div>

                        <!-- Step 1 -->
                        <div class="step-row">
                            <span class="step-badge">STEP 1</span>
                            <span class="step-desc">Load the Sub compiler into the Sun. Feed the full-Java compiler (written in Sub) as input.</span>
                        </div>
                        <div class="computer-diagram" style="margin:16px 0;">
                            <div>
                                <div class="comp-io-label">Input</div>
                                <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--purple); background:var(--purple-s); padding:8px 14px; border-radius:10px;">C<sup>Java→Sun</sup><sub>Sub</sub></div>
                            </div>
                            <div class="comp-arrow">→</div>
                            <div class="comp-box">
                                <div class="comp-name-tag">Sun</div>
                                <div class="comp-ram"><small>RAM</small>C<sup>Sub→Sun</sup><sub>Sun</sub></div>
                            </div>
                            <div class="comp-arrow">→</div>
                            <div>
                                <div class="comp-io-label">Output ✅</div>
                                <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--green); background:#F0FDF4; padding:8px 14px; border-radius:10px;">C<sup>Java→Sun</sup><sub>Sun</sub></div>
                            </div>
                        </div>
                        <div class="note-box key" style="margin:0 0 16px;">
                            <span class="box-icon">✅</span>
                            <div>Output of Step 1 is exactly the compiler we wanted — a Java compiler that runs on the Sun and produces Sun machine code. <strong>Bootstrapping complete in one step here!</strong></div>
                        </div>

                        <div class="note-box tip">
                            <span class="box-icon">💡</span>
                            <div>
                                <strong>In practice, bootstrapping is iterative.</strong> You start with a tiny subset (Sub₁),
                                produce a slightly larger subset (Sub₂), feed that back in to produce Sub₃, and so on —
                                expanding the language until you have a compiler for the complete language.
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sample Problem 1.3 -->
            <section id="sp13">
                <h2><span class="sec-icon">🧪</span> Sample Problem 1.3</h2>

                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 1.3</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">Show the output of the following compilation using the Big-C notation:</p>

                        <div class="computer-diagram" style="margin:20px 0;">
                            <div>
                                <div class="comp-io-label">Input</div>
                                <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--purple); background:var(--purple-s); padding:8px 14px; border-radius:10px;">C<sup>Ada→PC</sup><sub>Java</sub></div>
                            </div>
                            <div class="comp-arrow">→</div>
                            <div class="comp-box">
                                <div class="comp-name-tag">Sun</div>
                                <div class="comp-ram"><small>RAM</small>C<sup>Java→Sun</sup><sub>Sun</sub></div>
                            </div>
                            <div class="comp-arrow">→</div>
                            <div>
                                <div class="comp-io-label">Output = ?</div>
                                <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--muted); background:#F8F7FF; border:1.5px dashed rgba(99,102,241,.3); padding:8px 14px; border-radius:10px;">C<sup>?→?</sup><sub>?</sub></div>
                            </div>
                        </div>

                        <div class="sp-solution-label">✅ Solution</div>

                        <div class="note-box pro" style="margin-bottom:16px;">
                            <span class="box-icon">🧩</span>
                            <div>
                                <strong>Reasoning step-by-step:</strong>
                                <ol style="margin: 8px 0 0 16px; color: #3730A3;">
                                    <li>The compiler in RAM translates <strong>Java → Sun</strong>. So it processes input written in <strong>Java</strong> and produces output in <strong>Sun</strong> machine language.</li>
                                    <li>The input is <code>C<sup>Ada→PC</sup><sub>Java</sub></code> — it <em>exists in Java</em>, so the executing compiler can process it. ✓</li>
                                    <li>The <strong>superscript is preserved</strong> (the purpose doesn't change): Ada→PC stays Ada→PC.</li>
                                    <li>The subscript of the output = the <em>object language</em> of the executing compiler = <strong>Sun</strong>.</li>
                                </ol>
                            </div>
                        </div>

                        <div class="computer-diagram" style="margin:20px 0;">
                            <div>
                                <div class="comp-io-label">Input</div>
                                <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--purple); background:var(--purple-s); padding:8px 14px; border-radius:10px;">C<sup>Ada→PC</sup><sub>Java</sub></div>
                            </div>
                            <div class="comp-arrow">→</div>
                            <div class="comp-box">
                                <div class="comp-name-tag">Sun</div>
                                <div class="comp-ram"><small>RAM</small>C<sup>Java→Sun</sup><sub>Sun</sub></div>
                            </div>
                            <div class="comp-arrow">→</div>
                            <div>
                                <div class="comp-io-label">Output ✅</div>
                                <div style="font-family:'JetBrains Mono',monospace; font-size:14px; color:var(--green); background:#F0FDF4; padding:8px 14px; border-radius:10px; font-weight:700;">C<sup>Ada→PC</sup><sub>Sun</sub></div>
                            </div>
                        </div>
                        <p style="font-size:13px; color:var(--muted);">
                            The result is an Ada→PC compiler that now runs on a Sun machine. The same Ada→PC translation purpose is preserved; only the implementation language changed from Java to Sun machine code.
                        </p>
                    </div>
                </div>
            </section>







            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="c1_3_0.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>1.3 Implementation Techniques</div>
                </a>
                <a href="?complete=13" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>1.3.2 Cross Compiling</div>
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