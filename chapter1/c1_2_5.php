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
$page = 11;
$nextPage = "c1_3_0.php";

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
               <li><a href="#local-opt" class="toc-link">1.2.5 Local Optimisation</a></li>
                <li><a href="#pipeline" class="toc-link">Full Pipeline Diagram</a></li>
                <li><a href="#passes" class="toc-link">Single vs Multiple Pass</a></li>
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
            <!-- 1.2.5 Local Optimisation                      -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="local-opt">
                <h2><span class="sec-icon">🔧</span> 1.2.5 Local Optimisation</h2>
                <p>
                    The local optimisation phase is also <strong>optional</strong>. It examines sequences of instructions
                    from the code generator to find unnecessary or redundant instructions. Because it depends on the
                    specific instructions of the target machine, it is called <strong>machine-dependent optimisation</strong>.
                </p>
                <p>
                    Consider compiling <code>A + B + C</code>. The code generator naively outputs 6 instructions, but
                    2 of them are redundant:
                </p>

                <div class="opt-grid">
                    <div class="opt-before">
                        <div class="opt-label">Before — Code Generator Output (6 instructions)</div>
                        <div class="code-wrap" style="margin:0;">
                            <pre><code><span class="op">LOD</span> <span class="reg">R1</span>,A
<span class="op">ADD</span> <span class="reg">R1</span>,B
<span class="op">STO</span> <span class="reg">R1</span>,TEMP1  <span class="cm">// ← store result *</span>
<span class="op">LOD</span> <span class="reg">R1</span>,TEMP1 <span class="cm">// ← reload same thing *</span>
<span class="op">ADD</span> <span class="reg">R1</span>,C
<span class="op">STO</span> <span class="reg">R1</span>,TEMP2</code></pre>
                        </div>
                    </div>
                    <div class="opt-arrow">→</div>
                    <div class="opt-after">
                        <div class="opt-label">After — Local Optimisation (4 instructions)</div>
                        <div class="code-wrap" style="margin:0;">
                            <pre><code><span class="op">LOD</span> <span class="reg">R1</span>,A
<span class="op">ADD</span> <span class="reg">R1</span>,B
<span class="op">ADD</span> <span class="reg">R1</span>,C     <span class="cm">// R1 still holds A+B!</span>
<span class="op">STO</span> <span class="reg">R1</span>,TEMP</code></pre>
                        </div>
                    </div>
                </div>

                <p>
                    The instructions marked with <code>*</code> (STO to TEMP1 then immediately LOD from TEMP1) cancel each
                    other out — R1 already holds the correct value. Eliminating them makes the object program both
                    <strong>smaller</strong> and <strong>faster</strong>.
                </p>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Full Pipeline Diagram                         -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="pipeline">
                <h2><span class="sec-icon">🔄</span> Full Compiler Pipeline (Figure 1.4)</h2>
                <p>
                    The diagram below shows all phases and what each one outputs. Note that the optional optimisation
                    phases can be omitted — atoms can be passed directly from Syntax Analysis to Code Generation, and
                    instructions can be passed directly to the output file.
                </p>

                <div class="pipeline">
                    <div class="pipe-stage">
                        <div class="pipe-box">
                            <div class="pipe-name">📝 Source Program <span class="pipe-badge">Input</span></div>
                            <div class="pipe-desc">A string of characters (source code)</div>
                        </div>
                    </div>
                    <div class="pipe-arrow">
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-output-label">characters</div>
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-arrow-head"></div>
                    </div>

                    <div class="pipe-stage">
                        <div class="pipe-box">
                            <div class="pipe-name">🔤 Lexical Analysis <span class="pipe-badge">Phase 1</span></div>
                            <div class="pipe-desc">Identifies words/tokens; builds symbol table</div>
                            <div class="pipe-io">Input: characters → Output: token stream</div>
                        </div>
                    </div>
                    <div class="pipe-arrow">
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-output-label">Tokens</div>
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-arrow-head"></div>
                    </div>

                    <div class="pipe-stage">
                        <div class="pipe-box">
                            <div class="pipe-name">🌳 Syntax Analysis <span class="pipe-badge">Phase 2</span></div>
                            <div class="pipe-desc">Checks grammar; produces atoms or syntax trees; semantic type-checking</div>
                            <div class="pipe-io">Input: tokens → Output: atom stream</div>
                        </div>
                    </div>
                    <div class="pipe-arrow">
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-output-label">Atoms</div>
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-arrow-head"></div>
                    </div>

                    <div class="pipe-stage pipe-optional">
                        <div class="pipe-box">
                            <div class="pipe-name">⚡ Global Optimisation <span class="pipe-badge">Optional</span></div>
                            <div class="pipe-desc">Dead code elimination, loop invariant code motion — machine-independent</div>
                            <div class="pipe-io">Input: atoms → Output: optimised atoms</div>
                        </div>
                    </div>
                    <div class="pipe-arrow">
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-output-label">Atoms (optimised)</div>
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-arrow-head"></div>
                    </div>

                    <div class="pipe-stage">
                        <div class="pipe-box">
                            <div class="pipe-name">💾 Code Generation <span class="pipe-badge">Phase 3</span></div>
                            <div class="pipe-desc">Translates atoms/trees into machine/assembly instructions; register allocation</div>
                            <div class="pipe-io">Input: atoms → Output: instruction sequence</div>
                        </div>
                    </div>
                    <div class="pipe-arrow">
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-output-label">Instructions</div>
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-arrow-head"></div>
                    </div>

                    <div class="pipe-stage pipe-optional">
                        <div class="pipe-box">
                            <div class="pipe-name">🔧 Local Optimisation <span class="pipe-badge">Optional</span></div>
                            <div class="pipe-desc">Eliminates redundant load/store pairs; machine-dependent</div>
                            <div class="pipe-io">Input: instructions → Output: optimised instructions</div>
                        </div>
                    </div>
                    <div class="pipe-arrow">
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-output-label">Instructions (optimised)</div>
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-arrow-head"></div>
                    </div>

                    <div class="pipe-stage">
                        <div class="pipe-box" style="border-color:var(--green); background:#F0FDF4;">
                            <div class="pipe-name" style="color:#065F46;">✅ Object Program <span class="pipe-badge" style="background:#D1FAE5;color:#065F46;">Output</span></div>
                            <div class="pipe-desc" style="color:#047857;">Machine language binary / assembly ready for linking</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Single vs Multiple Pass                       -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="passes">
                <h2><span class="sec-icon">🔁</span> Single-Pass vs Multiple-Pass Compilers</h2>
                <p>There are two main ways to manage the flow of control between phases:</p>

                <div class="pass-grid">
                    <div class="pass-card">
                        <h5>📁 Multiple-Pass Compiler</h5>
                        <p>
                            Each phase runs from start to finish separately, writing its output to a <strong>disk file</strong>.
                            For example: lexical analysis scans the <em>entire</em> source program and writes a token file.
                            Then syntax analysis reads the <em>entire</em> token file and writes an atom file. The process
                            repeats for each phase. The input is scanned <em>multiple times</em>.
                        </p>
                    </div>
                    <div class="pass-card">
                        <h5>⚡ Single-Pass Compiler</h5>
                        <p>
                            Syntax analysis starts first. Each time it needs a token, it calls lexical analysis as a
                            <strong>subroutine</strong> — which reads just enough characters to produce one token and returns it.
                            Whenever the parser has enough to produce an atom, it calls the code generator as a subroutine.
                            The entire source is scanned <em>only once</em>.
                        </p>
                    </div>
                </div>

                <div class="note-box key">
                    <span class="box-icon">💡</span>
                    <div>
                        <strong>Single-pass compilers</strong> are faster and use less disk/memory, but may have limitations
                        (e.g. forward references to identifiers not yet declared). <strong>Multiple-pass compilers</strong>
                        are more flexible and can support complex language features at the cost of speed.
                    </div>
                </div>
            </section>



            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="c1_2_4.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>1.2.4  Code Generation</div>
                </a>
                <a href="?complete=11" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>Chapter 1.3: Implementation Technique</div>
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