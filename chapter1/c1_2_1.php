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
$page = 7;
$nextPage = "c1_2_2.php";

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
                <li><a href="#lexical" class="toc-link">1.2.1 Lexical Analysis</a></li>
                <li><a href="#sp-a" class="toc-link sub">↳ Sample Problem (a)</a></li>
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
            <!-- 1.2.1 Lexical Analysis                        -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="lexical">
                <h2><span class="sec-icon">🔤</span> 1.2.1 Lexical Analysis (Scanner)</h2>
                <p>
                    The first phase is called <strong>lexical analysis</strong> (or the <em>lexical scanner</em>).
                    Its job is to isolate the "words" in the input string. A word — also called a
                    <strong>lexeme</strong>, <strong>lexical item</strong>, or <strong>lexical token</strong> — is a string
                    of input characters taken as a single unit and passed to the next phase.
                </p>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Token Class</th>
                            <th>Description</th>
                            <th>Examples</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Keywords</td>
                            <td>Reserved words of the language</td>
                            <td><code>while</code>, <code>void</code>, <code>if</code>, <code>for</code></td>
                        </tr>
                        <tr>
                            <td>Identifiers</td>
                            <td>Names declared by the programmer</td>
                            <td><code>sum</code>, <code>unit</code>, <code>myVar</code></td>
                        </tr>
                        <tr>
                            <td>Operators</td>
                            <td>Arithmetic, relational, and logical operators</td>
                            <td><code>+</code>, <code>-</code>, <code>*</code>, <code>/</code>, <code>==</code></td>
                        </tr>
                        <tr>
                            <td>Numeric Constants</td>
                            <td>Integer and floating-point literals</td>
                            <td><code>124</code>, <code>12.35</code>, <code>0.09E-23</code></td>
                        </tr>
                        <tr>
                            <td>Character Constants</td>
                            <td>Single characters or strings in quotes</td>
                            <td><code>'a'</code>, <code>"hello"</code></td>
                        </tr>
                        <tr>
                            <td>Special Characters</td>
                            <td>Delimiters used by the grammar</td>
                            <td><code>.</code>, <code>(</code>, <code>)</code>, <code>,</code>, <code>;</code>, <code>:</code></td>
                        </tr>
                        <tr>
                            <td>Comments</td>
                            <td>Identified by scanner but <em>discarded</em> — not passed on</td>
                            <td><code>/* ... */</code>, <code>// ...</code></td>
                        </tr>
                    </tbody>
                </table>

                <p>
                    The output of the lexical phase is a <strong>stream of tokens</strong>. This phase also builds the
                    <strong>symbol table</strong> — a data structure that stores all identifiers used in the source program,
                    along with their attributes (type, scope, memory location, etc.).
                </p>

                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>
                        <strong>Symbol Table placement:</strong> In block-structured languages, it may be preferable to
                        construct the symbol table during the <em>syntax analysis phase</em> because program blocks
                        (and identifier scopes) may be nested.
                    </div>
                </div>
            </section>

            <!-- Sample Problem 1.2(a) -->
            <section id="sp-a">
                <h2><span class="sec-icon">🧪</span> Sample Problem 1.2(a) — Lexical Tokens</h2>

                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 1.2 (a)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">Show the token classes, or "words", put out by the lexical analysis phase for this Java source input:</p>
                        <div class="code-wrap" style="margin-bottom:16px;">
                            <div class="code-header">
                                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                                <span class="code-lang">Java — Source Input</span>
                            </div>
                            <pre><code>sum = sum + unit * <span class="cm">/* accumulate sum */</span> 1.2e-12 ;</code></pre>
                        </div>

                        <div class="sp-solution-label">✅ Solution — Token Stream</div>
                        <p style="font-size:13px; color:var(--muted); margin-bottom:12px;">
                            Note: the comment <code>/* accumulate sum */</code> is identified by the scanner but is <em>discarded</em> and does not appear in the output.
                        </p>
                        <div class="token-stream">
                            <span class="token ident">identifier<br><strong>sum</strong></span>
                            <span class="token assign">assignment<br><strong>=</strong></span>
                            <span class="token ident">identifier<br><strong>sum</strong></span>
                            <span class="token op">operator<br><strong>+</strong></span>
                            <span class="token ident">identifier<br><strong>unit</strong></span>
                            <span class="token op">operator<br><strong>*</strong></span>
                            <span class="token comment">comment<br><strong>discarded</strong></span>
                            <span class="token num">numeric constant<br><strong>1.2e-12</strong></span>
                            <span class="token special">special char<br><strong>;</strong></span>
                        </div>
                    </div>
                </div>
            </section>




            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="c1.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous section</span>1.1 What is a Compiler?</div>
                </a>
                <a href="?complete=7" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>Chapter 1.2.2: Syntax Analysis Phase</div>
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