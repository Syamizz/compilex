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
$page = 3;
$nextPage = "c1_1_3.php";

if (isset($_GET['complete']) && $_GET['complete'] == '3') {
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
                <li><a href="#vs-interpreter" class="toc-link">1.1.2 Compiler vs Interpreter</a></li>
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
            <!-- 1.1.2 Compiler vs Interpreter                  -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="vs-interpreter">
                <h2><span class="sec-icon">⚖️</span> Compiler vs Interpreter</h2>
                <p>
                    An <strong>interpreter</strong> serves a purpose very similar to a compiler. The input is also a program
                    written in a high-level language — but rather than generating a machine language program, the interpreter
                    <em>directly carries out</em> the computations specified in the source program.
                </p>

                <div class="note-box key">
                    <span class="box-icon">🔑</span>
                    <div>
                        <strong>Core distinction:</strong> The <em>output of a compiler</em> is a program (object code).
                        The <em>output of an interpreter</em> is the source program's actual output (the computed result).
                    </div>
                </div>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Aspect</th>
                            <th>Compiler</th>
                            <th>Interpreter</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Output</td>
                            <td>Object program (machine code)</td>
                            <td>Program's computed output</td>
                        </tr>
                        <tr>
                            <td>Execution</td>
                            <td>Translates entire program first, then runs</td>
                            <td>Reads and executes one line at a time</td>
                        </tr>
                        <tr>
                            <td>Speed at run time</td>
                            <td>Faster (already translated)</td>
                            <td>Slower (translates on the fly)</td>
                        </tr>
                        <tr>
                            <td>Error reporting</td>
                            <td>Compile-time syntax errors reported before run</td>
                            <td>Errors found only when that line is reached</td>
                        </tr>
                        <tr>
                            <td>Example</td>
                            <td>C, C++, Java (native)</td>
                            <td>Python (CPython), early BASIC</td>
                        </tr>
                    </tbody>
                </table>

                <p>
                    Consider the input <code>a = 3; b = 4; println(a*b);</code> — a compiler generates assembly instructions
                    for it, while an interpreter evaluates it and directly outputs <strong>12</strong>.
                </p>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        <strong>Common confusion:</strong> Many commercial compilers come packaged with a built-in
                        edit–compile–run front end, so students don't notice the compilation step. As larger programs are
                        divided into modules, each compiled separately to an <em>object module</em> and then <em>linked</em>,
                        the distinction between compile time and run time becomes clearer.
                    </div>
                </div>

                <p>
                    It is important to remember that a compiler is itself a program, written in some language. When describing
                    compilation we are always dealing with <strong>three languages</strong>:
                </p>
                <ol style="margin-left:20px; color:var(--muted); margin-bottom:14px;">
                    <li>The <strong>source language</strong> — the input to the compiler</li>
                    <li>The <strong>object language</strong> — the output of the compiler</li>
                    <li>The <strong>implementation language</strong> — the language in which the compiler itself is written</li>
                </ol>
                <p>Note that the object language need not be machine code — it could itself be a high-level language.</p>
            </section>





            <!-- ─────────────────────────────────────────────── -->


            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="c1_1_1.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous Page</span>1.1.1 Source & Object Programs</div>
                </a>
                <a href="?complete=3" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>1.1.3 Compile-time vs Run-time Errors</div>
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