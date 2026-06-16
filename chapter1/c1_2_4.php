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
$page = 10;
$nextPage = "c1_2_5.php";

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
                <li><a href="#codegen" class="toc-link">1.2.4 Code Generation</a></li>
                <li><a href="#sp-e" class="toc-link sub">↳ Sample Problem (e)</a></li>
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
            <!-- 1.2.4 Code Generation                         -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="codegen">
                <h2><span class="sec-icon">💾</span> 1.2.4 Code Generation</h2>
                <p>
                    In the code generation phase, atoms or syntax trees are translated into <strong>machine language
                        (binary) instructions</strong>, or into assembly language (which the assembler then converts to
                    binary). Symbolic addresses (statement labels) are translated to relocatable memory addresses.
                </p>
                <p>
                    Most Java compilers produce an intermediate form known as <strong>Byte Code</strong>, which is
                    interpreted by the Java runtime. In this course we assume the compiler produces
                    <strong>native code</strong> for a particular machine.
                </p>

                <h3>Register Allocation</h3>
                <p>
                    For target machines with several CPU registers, the code generator is responsible for
                    <strong>register allocation</strong> — tracking which registers are in use and which are available as
                    code is generated. For example, an ADD atom translates to three machine instructions:
                </p>

                <div class="two-col">
                    <div>
                        <div class="atom-wrap">
                            <div style="font-size:12px; color:rgba(205,214,244,.5); margin-bottom:10px;">ATOM INPUT</div>
                            <div class="atom-list">
                                <div class="atom-row">
                                    <span class="atom-paren">(</span>
                                    <span class="atom-op">ADD</span>
                                    <span class="atom-operand">A</span><span class="atom-operand">B</span>
                                    <span class="atom-result">Temp</span>
                                    <span class="atom-paren">)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="code-wrap" style="margin:0;">
                            <div class="code-header">
                                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                                <span class="code-lang">Assembly Output</span>
                            </div>
                            <pre><code><span class="op">LOD</span> <span class="reg">R1</span>,A     <span class="cm">// Load A into reg. 1</span>
<span class="op">ADD</span> <span class="reg">R1</span>,B     <span class="cm">// Add B to reg. 1</span>
<span class="op">STO</span> <span class="reg">R1</span>,Temp  <span class="cm">// Store reg. 1 in Temp</span></code></pre>
                        </div>
                    </div>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        The object language does not have to be machine code — it can be <strong>another high-level language</strong>.
                        This improves portability of the language being implemented.
                    </div>
                </div>
            </section>

            <!-- Sample Problem 1.2(e) -->
            <section id="sp-e">
                <h2><span class="sec-icon">🧪</span> Sample Problem 1.2(e) — Atoms to Assembly</h2>

                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 1.2 (e)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">Show assembly language instructions corresponding to the following atom string:</p>

                        <div class="atom-wrap" style="margin-bottom:16px;">
                            <div class="atom-list">
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op">ADD</span><span class="atom-operand">A</span><span class="atom-operand">B</span><span class="atom-result">Temp1</span><span class="atom-paren">)</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op">TEST</span><span class="atom-operand">A</span><span class="atom-operand">==</span><span class="atom-operand">B</span><span class="atom-result">L1</span><span class="atom-paren">)</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op">MOVE</span><span class="atom-operand">Temp1</span><span class="atom-result">A</span><span class="atom-paren">)</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op" style="background:rgba(249,226,175,.15);color:#F9E2AF;">LBL</span><span class="atom-operand">L1</span><span class="atom-paren">)</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op">MOVE</span><span class="atom-operand">Temp1</span><span class="atom-result">B</span><span class="atom-paren">)</span>
                                </div>
                            </div>
                        </div>

                        <div class="sp-solution-label">✅ Solution — Assembly Output</div>
                        <div class="code-wrap">
                            <div class="code-header">
                                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                                <span class="code-lang">Assembly</span>
                                <button class="copy-btn" onclick="copyBlock('sp-e-block',this)">Copy</button>
                            </div>
                            <pre><code id="sp-e-block"><span class="op">LOD</span> <span class="reg">R1</span>,A
<span class="op">ADD</span> <span class="reg">R1</span>,B
<span class="op">STO</span> <span class="reg">R1</span>,Temp1     <span class="cm">// ADD, A, B, Temp1</span>
<span class="op">CMP</span> A,B
<span class="op">BE</span>  L1             <span class="cm">// TEST, A, ==, B, L1</span>
<span class="op">MOV</span> A,Temp1        <span class="cm">// MOVE, Temp1, A  (dest=first operand)</span>
<span class="lbl">L1:</span> <span class="op">MOV</span> B,Temp1   <span class="cm">// MOVE, Temp1, B</span></code></pre>
                        </div>
                        <div class="note-box warn" style="margin-top:14px;">
                            <span class="box-icon">⚠️</span>
                            <div>
                                <strong>Operand order note:</strong> For the MOV instruction, the destination is the
                                <em>first</em> operand and the source is the <em>second</em> — the reverse of the operand
                                positions in the MOVE atom.
                            </div>
                        </div>
                    </div>
                </div>
            </section>





            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="c1_2_3.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>1.2.3  Global Optimization</div>
                </a>
                <a href="?complete=10" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>1.2.5  Local Optimization</div>
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