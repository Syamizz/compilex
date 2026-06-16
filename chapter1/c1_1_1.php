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
$page = 2;
$nextPage = "c1_1_2.php";

if (isset($_GET['complete']) && $_GET['complete'] == '2') {
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
    <title>Chapter 1 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c1_1.css">

    <style>
        /* CSS */
        .button-28 {
            appearance: none;
            background-color: transparent;
            border: 2px solid #1A1A1A;
            border-radius: 15px;
            box-sizing: border-box;
            color: #3B3B3B;
            cursor: pointer;
            display: inline-block;
            font-size: 10px;
            font-weight: 600;
            line-height: normal;
            margin: 0;
            min-height: 30px;
            min-width: 0;
            outline: none;
            padding: 12px 20px;
            text-align: center;
            text-decoration: none;
            transition: all 300ms cubic-bezier(.23, 1, 0.32, 1);
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            width: auto;
            will-change: transform;
        }

        .button-28:disabled {
            pointer-events: none;
        }

        .button-28:hover {
            color: #fff;
            background-color: #1A1A1A;
            box-shadow: rgba(0, 0, 0, 0.25) 0 8px 15px;
            transform: translateY(-2px);
        }

        .button-28:active {
            box-shadow: none;
            transform: translateY(0);
        }
    </style>
</head>

<body>

    <?php include 'navbar_c1.php'; ?>

    <div class="note-container">

        <!-- ── Sidebar TOC ─────────────────────────────────── -->
        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#source-object" class="toc-link">1.1.1 Source & Object Programs</a></li>
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
                <div class="chapter-tag">📘 Chapter 1</div>
                <h1>Introduction to Compilers</h1>
                <div class="metadata">
                    <span>⏱ 15 min read</span>
                    <span>🎯 Beginner</span>
                    <span>⚙️ Compiler Theory</span>
                </div>
            </header>


            <!-- ─────────────────────────────────────────────── -->
            <!-- 1.1.1 Source & Object Programs                 -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="source-object">
                <h2><span class="sec-icon">📄</span> Source &amp; Object Programs</h2>
                <p>
                    A compiler accepts as input a program written in a particular high-level language and produces as output an
                    equivalent program in machine language for a specific <strong>target machine</strong>. Two programs are
                    considered <em>equivalent</em> if they always produce the same output when given the same input.
                </p>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Term</th>
                            <th>Definition</th>
                            <th>Example</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Source Program</td>
                            <td>The input to the compiler — written in the source language</td>
                            <td>Java, C++, Ada</td>
                        </tr>
                        <tr>
                            <td>Object Program</td>
                            <td>The output of the compiler — written in the object language</td>
                            <td>x86 machine code, MIPS assembly</td>
                        </tr>
                        <tr>
                            <td>Target Machine</td>
                            <td>The specific machine for which object code is generated</td>
                            <td>Apple Macintosh, SUN, PC</td>
                        </tr>
                    </tbody>
                </table>

                <p>Some concrete examples of compilers:</p>
                <ul style="margin-left:20px; color:var(--muted); margin-bottom:14px;">
                    <li>A Java compiler for the Apple Macintosh</li>
                    <li>A COBOL compiler for the SUN</li>
                    <li>A C++ compiler for the Apple Macintosh</li>
                </ul>

                <h3>Example: Translating <code>A = B + C * D;</code></h3>
                <p>
                    The compiler must be smart enough to know that multiplication happens before addition, even though the
                    addition appears first when scanning left to right. It also checks for proper <strong>syntax</strong>
                    and issues helpful error messages when errors are found.
                </p>

                <div class="code-wrap">
                    <div class="code-header">
                        <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                        <span class="code-lang">Assembly — Compiled Output for A = B + C * D</span>
                        <button class="copy-btn" onclick="copyBlock('abcd-block', this)">Copy</button>
                    </div>
                    <pre><code id="abcd-block"><span class="op">LOD</span> <span class="reg">R1</span>,<span class="num">C</span>       <span class="cm">// Load the value of C into reg 1</span>
<span class="op">MUL</span> <span class="reg">R1</span>,<span class="num">D</span>       <span class="cm">// Multiply the value of D by reg 1</span>
<span class="op">STO</span> <span class="reg">R1</span>,TEMP1   <span class="cm">// Store the result in TEMP1</span>
<span class="op">LOD</span> <span class="reg">R1</span>,<span class="num">B</span>       <span class="cm">// Load the value of B into reg 1</span>
<span class="op">ADD</span> <span class="reg">R1</span>,TEMP1   <span class="cm">// Add value of TEMP1 to register 1</span>
<span class="op">STO</span> <span class="reg">R1</span>,TEMP2   <span class="cm">// Store the result in TEMP2</span>
<span class="op">MOV</span> <span class="num">A</span>,TEMP2    <span class="cm">// Move TEMP2 to A, the final result</span></code></pre>
                </div>

                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>
                        <strong>Semantic Equivalence over Optimality:</strong> In designing a compiler, the primary concern is that
                        the object program be <em>semantically equivalent</em> to the source program (produces the same output for
                        any given input). Object program efficiency is important, but not as important as correct code generation.
                        Many compilers will not generate perfectly optimal code.
                    </div>
                </div>

                <h3>Advantages of High-Level Languages</h3>
                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Advantage</th>
                            <th>Explanation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Readability &amp; maintainability</td>
                            <td>Machine/assembly language is difficult to work with and maintain</td>
                        </tr>
                        <tr>
                            <td>Portability</td>
                            <td>A high-level program can run on any machine that has a compiler for that language</td>
                        </tr>
                        <tr>
                            <td>No retraining needed</td>
                            <td>Programmers don't need to relearn every time a new CPU is introduced</td>
                        </tr>
                        <tr>
                            <td>Abstraction</td>
                            <td>High-level languages support data abstraction (data structures) and program abstraction (procedures/functions)</td>
                        </tr>
                    </tbody>
                </table>

                <h3>Disadvantages of High-Level Languages</h3>
                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Disadvantage</th>
                            <th>Explanation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Limited control</td>
                            <td>Programmer doesn't have complete control of machine resources (registers, interrupts, I/O buffers)</td>
                        </tr>
                        <tr>
                            <td>Possible inefficiency</td>
                            <td>The compiler may generate inefficient machine language programs</td>
                        </tr>
                        <tr>
                            <td>Extra software needed</td>
                            <td>The compiler itself must be available on the target platform</td>
                        </tr>
                    </tbody>
                </table>
                <p>As compiler development and hardware have improved, these disadvantages have become less problematic. Consequently, most programming today is done with high-level languages.</p>
            </section>







     
     
            <!-- ─────────────────────────────────────────────── -->


            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="c1_1_0.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous Page</span>1.1.0 What is a Compiler?</div>
                </a>
                <a href="?complete=2" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>1.1.2 Compiler vs Interpreter</div>
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

        // ── Copy code blocks ─────────────────────────────────────
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