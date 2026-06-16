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
$page = 5;
$nextPage = "c1_2_0.php";

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
                <li><a href="#bigc" class="toc-link">1.1.4 Big-C Notation</a></li>
                <li><a href="#sample-c" class="toc-link">↳ Sample Problem (c) — Big-C</a></li>
                <li><a href="#walkthrough" class="toc-link">↳ Step-by-Step Walkthrough</a></li>
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
            <!-- 1.1.4 Big-C Notation                           -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="bigc">
                <h2><span class="sec-icon">🔠</span> Big-C Notation for Compilers</h2>
                <p>
                    A concise notation for describing compilers was introduced by Aho (1986). In these diagrams the large
                    <strong>C</strong> stands for <em>Compiler</em> (not the C programming language). The components are:
                </p>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Component</th>
                            <th>Meaning</th>
                            <th>Example</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Superscript (top arrow)</td>
                            <td>The intended <em>translation</em> — source → target language</td>
                            <td>Java → Mac machine language</td>
                        </tr>
                        <tr>
                            <td>Subscript (bottom)</td>
                            <td>The language the compiler <em>exists in</em> (runs on)</td>
                            <td>Written in Sun machine language</td>
                        </tr>
                    </tbody>
                </table>

                <p>In this notation, the name of a machine represents its machine language — i.e. <em>Sun</em> means Sun machine language, <em>PC</em> means Intel Pentium machine language.</p>

                <div class="bigc-grid">
                    <div class="bigc-card">
                        <div class="bigc-label">Figure 1.2 (a)</div>
                        <div class="bigc-notation">C<sup>Java→Mac</sup><sub>Mac</sub></div>
                        <div class="bigc-sub">A Java compiler that runs on the Mac and produces Mac machine code</div>
                    </div>
                    <div class="bigc-card">
                        <div class="bigc-label">Figure 1.2 (b)</div>
                        <div class="bigc-notation">C<sup>Java→Mac</sup><sub>Sun</sub></div>
                        <div class="bigc-sub">A Java compiler that generates Mac code but runs on a Sun computer</div>
                    </div>
                    <div class="bigc-card">
                        <div class="bigc-label">Figure 1.2 (c)</div>
                        <div class="bigc-notation">C<sup>PC→Java</sup><sub>Ada</sub></div>
                        <div class="bigc-sub">Translates PC machine language to Java; written in Ada — won't run on any machine directly</div>
                    </div>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        The language the compiler is written in does <em>not</em> need to match the object language. A compiler
                        that produces Mac machine code could run on a Sun. And the object language doesn't need to be machine code —
                        it could be a high-level language like Java.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- Sample Problem (c) — Big-C               -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="sample-c">
                <h2><span class="sec-icon">✏️</span> Sample Problem 1.1(c) — Big-C Notation Practice</h2>

                <div class="sample-problem">
                    <div class="sp-header">
                        <span style="font-size:18px">📋</span>
                        <h4>Sample Problem 1.1 (c)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">Using the Big-C notation, show each of the following compilers:</p>
                        <ol style="color:var(--muted); margin-left:20px; margin-bottom:16px;">
                            <li>(a) An Ada compiler which runs on the PC and compiles to PC machine language.</li>
                            <li>(b) An Ada compiler which compiles to PC machine language, but which is written in Ada.</li>
                            <li>(c) An Ada compiler which compiles to PC machine language, but runs on a Sun.</li>
                        </ol>

                        <div class="sp-solution-label">✅ Solution</div>
                        <div class="bigc-grid">
                            <div class="bigc-card">
                                <div class="bigc-label">(a) Ada → PC, runs on PC</div>
                                <div class="bigc-notation">C<sup>Ada→PC</sup><sub>PC</sub></div>
                                <div class="bigc-sub">Compiles Ada to PC machine language; the compiler itself runs on a PC</div>
                            </div>
                            <div class="bigc-card">
                                <div class="bigc-label">(b) Ada → PC, written in Ada</div>
                                <div class="bigc-notation">C<sup>Ada→PC</sup><sub>Ada</sub></div>
                                <div class="bigc-sub">Compiles Ada to PC machine language; written in Ada — won't run directly on any machine</div>
                            </div>
                            <div class="bigc-card">
                                <div class="bigc-label">(c) Ada → PC, runs on Sun</div>
                                <div class="bigc-notation">C<sup>Ada→PC</sup><sub>Sun</sub></div>
                                <div class="bigc-sub">Compiles Ada to PC machine language; the compiler runs on a Sun machine</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- 1.1.8 Step-by-Step Walkthrough                 -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="walkthrough">
                <h2 class="sr-only">Step-by-step compiler walkthrough examples with interactive stepper</h2>

                <div style="padding: 1rem 0;">
                    <p class="section-label">Choose an example to step through:</p>
                    <div class="example-picker">
                        <button class="pick-btn active" onclick="switchExample(0, this)">A = B + C * D</button>
                        <button class="pick-btn" onclick="switchExample(1, this)">while (x &lt; a+b)</button>
                        <button class="pick-btn" onclick="switchExample(2, this)">for (i=1; i&lt;=4; i++)</button>
                        <button class="pick-btn" onclick="switchExample(3, this)">if (a &gt; b)</button>
                    </div>

                    <div class="stepper-wrap" id="stepper">
                        <div class="stepper-header">
                            <span class="stepper-title" id="stepper-title">Executing: A = B + C * D</span>
                            <span class="step-counter" id="step-counter">Step 1 of 7</span>
                        </div>
                        <div class="stepper-body">
                            <div class="stepper-code">
                                <code id="step-code"></code>
                            </div>
                            <div class="stepper-explain">
                                <div class="step-label" id="step-label">STEP 1</div>
                                <div class="step-title" id="step-title"></div>
                                <div class="step-desc" id="step-desc"></div>
                                <table class="reg-table">
                                    <thead>
                                        <tr>
                                            <th>Register / Var</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody id="reg-tbody"></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="stepper-nav">
                            <button class="step-btn" id="btn-prev" onclick="stepMove(-1)" disabled>← Prev</button>
                            <button class="step-btn" id="btn-reset" onclick="resetStep()">Reset</button>
                            <button class="step-btn primary" id="btn-next" onclick="stepMove(1)">Next →</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->


            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="index.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Back to</span>Home</div>
                </a>
                <a href="?complete=5" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>1.2 The Phases of a Compiler</div>
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

        const examples = [{
                title: "Executing: A = B + C * D",
                source: "A = B + C * D;",
                lines: [
                    "LOD R1,C       // Load C into register R1",
                    "MUL R1,D       // R1 = C × D",
                    "STO R1,TEMP1   // TEMP1 = C × D",
                    "LOD R1,B       // Load B into R1",
                    "ADD R1,TEMP1   // R1 = B + (C×D)",
                    "STO R1,TEMP2   // TEMP2 = B + (C×D)",
                    "MOV A,TEMP2    // A = final result ✓",
                ],
                steps: [{
                        label: "STEP 1 — LOD R1,C",
                        title: "Load C into R1",
                        desc: "The LOD instruction reads the value stored at memory address C and places it into CPU register R1. No computation yet — just a data move.",
                        regs: [{
                            r: "R1",
                            v: "C",
                            c: true
                        }, {
                            r: "TEMP1",
                            v: "—"
                        }, {
                            r: "TEMP2",
                            v: "—"
                        }, {
                            r: "A",
                            v: "—"
                        }]
                    },
                    {
                        label: "STEP 2 — MUL R1,D",
                        title: "Multiply R1 by D",
                        desc: "MUL takes the value in R1 (which holds C) and multiplies it by the value at memory address D. The result C×D overwrites R1.",
                        regs: [{
                            r: "R1",
                            v: "C × D",
                            c: true
                        }, {
                            r: "TEMP1",
                            v: "—"
                        }, {
                            r: "TEMP2",
                            v: "—"
                        }, {
                            r: "A",
                            v: "—"
                        }]
                    },
                    {
                        label: "STEP 3 — STO R1,TEMP1",
                        title: "Spill result to TEMP1",
                        desc: "STO copies R1 into memory address TEMP1. This frees R1 so it can be reused. The intermediate result C×D is now safe in memory.",
                        regs: [{
                            r: "R1",
                            v: "C × D"
                        }, {
                            r: "TEMP1",
                            v: "C × D",
                            c: true
                        }, {
                            r: "TEMP2",
                            v: "—"
                        }, {
                            r: "A",
                            v: "—"
                        }]
                    },
                    {
                        label: "STEP 4 — LOD R1,B",
                        title: "Load B into R1",
                        desc: "R1 is now reloaded with B from memory. The previous value C×D was safely stored in TEMP1 so nothing is lost.",
                        regs: [{
                            r: "R1",
                            v: "B",
                            c: true
                        }, {
                            r: "TEMP1",
                            v: "C × D"
                        }, {
                            r: "TEMP2",
                            v: "—"
                        }, {
                            r: "A",
                            v: "—"
                        }]
                    },
                    {
                        label: "STEP 5 — ADD R1,TEMP1",
                        title: "Add TEMP1 to R1",
                        desc: "ADD fetches TEMP1 (C×D) and adds it to R1 (B). The sum B+(C×D) is stored back into R1. Operator precedence is handled correctly.",
                        regs: [{
                            r: "R1",
                            v: "B + (C×D)",
                            c: true
                        }, {
                            r: "TEMP1",
                            v: "C × D"
                        }, {
                            r: "TEMP2",
                            v: "—"
                        }, {
                            r: "A",
                            v: "—"
                        }]
                    },
                    {
                        label: "STEP 6 — STO R1,TEMP2",
                        title: "Store result in TEMP2",
                        desc: "The full result B+(C×D) is stored in TEMP2, ready to be assigned to variable A.",
                        regs: [{
                            r: "R1",
                            v: "B + (C×D)"
                        }, {
                            r: "TEMP1",
                            v: "C × D"
                        }, {
                            r: "TEMP2",
                            v: "B + (C×D)",
                            c: true
                        }, {
                            r: "A",
                            v: "—"
                        }]
                    },
                    {
                        label: "STEP 7 — MOV A,TEMP2",
                        title: "Assign final result to A",
                        desc: "MOV copies TEMP2 into variable A's memory address. The expression A = B + C*D is now fully evaluated and stored.",
                        regs: [{
                            r: "R1",
                            v: "B + (C×D)"
                        }, {
                            r: "TEMP1",
                            v: "C × D"
                        }, {
                            r: "TEMP2",
                            v: "B + (C×D)"
                        }, {
                            r: "A",
                            v: "B + (C×D)",
                            c: true
                        }]
                    },
                ]
            },
            {
                title: "Compiling: while (x < a+b) x = 2*x",
                source: "while (x < a + b) x = 2 * x;",
                lines: [
                    "L1: LOD R1,A        // Load A",
                    "    ADD R1,B        // R1 = A + B",
                    "    STO R1,TEMP1   // TEMP1 = A+B",
                    "    CMP X,TEMP1    // Compare X vs TEMP1",
                    "    BL  L2         // Branch to L2 if X < TEMP1",
                    "    B   L3         // Else jump out of loop",
                    "L2: LOD R1,='2'    // Load literal 2",
                    "    MUL R1,X       // R1 = 2 * X",
                    "    STO R1,X       // X = 2 * X",
                    "    B   L1         // Jump back to loop start",
                    "L3:                // Loop exit point",
                ],
                steps: [{
                        label: "STEP 1 — LOD R1,A",
                        title: "Load A (start of loop body)",
                        desc: "Each iteration begins at label L1. We first compute the loop condition a+b, so A is loaded into R1.",
                        regs: [{
                            r: "R1",
                            v: "A",
                            c: true
                        }, {
                            r: "TEMP1",
                            v: "—"
                        }, {
                            r: "X",
                            v: "X (unchanged)"
                        }]
                    },
                    {
                        label: "STEP 2 — ADD R1,B",
                        title: "Compute A + B",
                        desc: "B is added to R1. Now R1 holds the value a+b, which is the right-hand side of the while condition.",
                        regs: [{
                            r: "R1",
                            v: "A + B",
                            c: true
                        }, {
                            r: "TEMP1",
                            v: "—"
                        }, {
                            r: "X",
                            v: "X (unchanged)"
                        }]
                    },
                    {
                        label: "STEP 3 — STO R1,TEMP1",
                        title: "Save A+B to TEMP1",
                        desc: "The computed value a+b is stored into TEMP1 so it can be used in the comparison without recalculating.",
                        regs: [{
                            r: "R1",
                            v: "A + B"
                        }, {
                            r: "TEMP1",
                            v: "A + B",
                            c: true
                        }, {
                            r: "X",
                            v: "X (unchanged)"
                        }]
                    },
                    {
                        label: "STEP 4 — CMP X,TEMP1",
                        title: "Test the while condition",
                        desc: "CMP compares X against TEMP1 (a+b). It sets CPU condition flags but does NOT branch yet — the next instruction decides what to do with the result.",
                        regs: [{
                            r: "R1",
                            v: "A + B"
                        }, {
                            r: "TEMP1",
                            v: "A + B"
                        }, {
                            r: "X",
                            v: "X (compared)"
                        }]
                    },
                    {
                        label: "STEP 5 — BL L2",
                        title: "Branch if X < TEMP1 (continue loop)",
                        desc: "BL (Branch if Less) jumps to L2 if the condition x < a+b is true, meaning the loop should continue executing.",
                        regs: [{
                            r: "flags",
                            v: "x < a+b ?",
                            c: true
                        }, {
                            r: "TEMP1",
                            v: "A + B"
                        }, {
                            r: "X",
                            v: "X"
                        }]
                    },
                    {
                        label: "STEP 6 — B L3",
                        title: "Unconditional jump to exit",
                        desc: "If BL did NOT branch (x >= a+b), we fall through to this unconditional branch to L3, which exits the loop.",
                        regs: [{
                            r: "flags",
                            v: "x >= a+b"
                        }, {
                            r: "TEMP1",
                            v: "A + B"
                        }, {
                            r: "X",
                            v: "X"
                        }]
                    },
                    {
                        label: "STEP 7 — LOD R1,'2'",
                        title: "Load literal 2 (inside loop)",
                        desc: "We are now inside the loop (at L2). Load the literal constant 2 into R1 to prepare the multiplication.",
                        regs: [{
                            r: "R1",
                            v: "2",
                            c: true
                        }, {
                            r: "TEMP1",
                            v: "A + B"
                        }, {
                            r: "X",
                            v: "X"
                        }]
                    },
                    {
                        label: "STEP 8 — MUL R1,X",
                        title: "Multiply 2 × X",
                        desc: "MUL multiplies R1 (holds 2) by the current value of X. The result 2×X is stored back in R1.",
                        regs: [{
                            r: "R1",
                            v: "2 × X",
                            c: true
                        }, {
                            r: "TEMP1",
                            v: "A + B"
                        }, {
                            r: "X",
                            v: "X (old)"
                        }]
                    },
                    {
                        label: "STEP 9 — STO R1,X",
                        title: "Update X = 2 * X",
                        desc: "STO stores R1 back into X's memory location. X has now been updated to its new value for this iteration.",
                        regs: [{
                            r: "R1",
                            v: "2 × X"
                        }, {
                            r: "TEMP1",
                            v: "A + B"
                        }, {
                            r: "X",
                            v: "2 × X",
                            c: true
                        }]
                    },
                    {
                        label: "STEP 10 — B L1",
                        title: "Jump back to loop start",
                        desc: "Unconditional branch back to L1. The condition is re-evaluated. If x < a+b still holds, the loop runs again.",
                        regs: [{
                            r: "R1",
                            v: "2 × X"
                        }, {
                            r: "TEMP1",
                            v: "A + B"
                        }, {
                            r: "X",
                            v: "2 × X"
                        }]
                    },
                    {
                        label: "STEP 11 — L3 (exit)",
                        title: "Loop exit point",
                        desc: "Label L3 marks where control jumps when the while condition becomes false (x >= a+b). Execution continues from here.",
                        regs: [{
                            r: "R1",
                            v: "(last 2×X)"
                        }, {
                            r: "TEMP1",
                            v: "A + B"
                        }, {
                            r: "X",
                            v: "final X"
                        }]
                    },
                ]
            },
            {
                title: "Compiling: for (i=1; i<=4; i++) println(i*3)",
                source: "for (i=1; i<=4; i++) System.out.println(i*3);",
                lines: [
                    "    LOD R1,='4'     // Load limit 4",
                    "    STO R1,TEMP1   // TEMP1 = 4",
                    "    MOV i,='1'     // i = 1 (init)",
                    "L1: CMP i,TEMP1    // Compare i vs 4",
                    "    BH  L2         // Exit if i > 4",
                    "    LOD R1,i       // Load i",
                    "    MUL R1,='3'    // R1 = i * 3",
                    "    STO R1,TEMP2   // TEMP2 = i*3",
                    "    PUSH TEMP2     // Push arg",
                    "    CALL Println   // Print it",
                    "    ADD i,='1'     // i++",
                    "    B   L1         // Loop back",
                    "L2:                // Loop done",
                ],
                steps: [{
                        label: "STEP 1 — LOD R1,'4'",
                        title: "Load the loop limit",
                        desc: "We load the constant 4 (the upper bound of the for loop) into R1 so it can be stored for repeated comparisons.",
                        regs: [{
                            r: "R1",
                            v: "4",
                            c: true
                        }, {
                            r: "TEMP1",
                            v: "—"
                        }, {
                            r: "i",
                            v: "—"
                        }]
                    },
                    {
                        label: "STEP 2 — STO R1,TEMP1",
                        title: "Save limit to TEMP1",
                        desc: "Store the limit value (4) into TEMP1 in memory. This avoids reloading the constant every iteration.",
                        regs: [{
                            r: "R1",
                            v: "4"
                        }, {
                            r: "TEMP1",
                            v: "4",
                            c: true
                        }, {
                            r: "i",
                            v: "—"
                        }]
                    },
                    {
                        label: "STEP 3 — MOV i,'1'",
                        title: "Initialize i = 1",
                        desc: "MOV places the constant 1 directly into variable i. This is the initialization part of the for statement.",
                        regs: [{
                            r: "R1",
                            v: "4"
                        }, {
                            r: "TEMP1",
                            v: "4"
                        }, {
                            r: "i",
                            v: "1",
                            c: true
                        }]
                    },
                    {
                        label: "STEP 4 — CMP i,TEMP1",
                        title: "Check loop condition i <= 4",
                        desc: "Each iteration starts here (L1). CMP compares i against TEMP1 (the limit 4). Condition flags are set for the next branch.",
                        regs: [{
                            r: "R1",
                            v: "4"
                        }, {
                            r: "TEMP1",
                            v: "4"
                        }, {
                            r: "i",
                            v: "current i"
                        }]
                    },
                    {
                        label: "STEP 5 — BH L2",
                        title: "Exit if i > 4",
                        desc: "BH (Branch if Higher) jumps to L2 to exit the loop when i exceeds 4. If i <= 4, we fall through and continue.",
                        regs: [{
                            r: "flags",
                            v: "i > 4 ?",
                            c: true
                        }, {
                            r: "TEMP1",
                            v: "4"
                        }, {
                            r: "i",
                            v: "current i"
                        }]
                    },
                    {
                        label: "STEP 6 — LOD R1,i",
                        title: "Load current i into R1",
                        desc: "Load the current value of loop variable i into R1 to compute the body expression i*3.",
                        regs: [{
                            r: "R1",
                            v: "i",
                            c: true
                        }, {
                            r: "TEMP1",
                            v: "4"
                        }, {
                            r: "i",
                            v: "current i"
                        }]
                    },
                    {
                        label: "STEP 7 — MUL R1,'3'",
                        title: "Compute i * 3",
                        desc: "Multiply R1 (holds i) by the literal 3. The product i×3 is stored back in R1.",
                        regs: [{
                            r: "R1",
                            v: "i × 3",
                            c: true
                        }, {
                            r: "TEMP1",
                            v: "4"
                        }, {
                            r: "i",
                            v: "current i"
                        }]
                    },
                    {
                        label: "STEP 8 — STO R1,TEMP2",
                        title: "Save result to TEMP2",
                        desc: "The computed value i×3 is written to TEMP2 to prepare it as an argument for the println call.",
                        regs: [{
                            r: "R1",
                            v: "i × 3"
                        }, {
                            r: "TEMP2",
                            v: "i × 3",
                            c: true
                        }, {
                            r: "i",
                            v: "current i"
                        }]
                    },
                    {
                        label: "STEP 9 — PUSH TEMP2",
                        title: "Push argument onto stack",
                        desc: "PUSH places TEMP2 onto the call stack. The Println subroutine will read its argument from here.",
                        regs: [{
                            r: "stack",
                            v: "i × 3",
                            c: true
                        }, {
                            r: "TEMP2",
                            v: "i × 3"
                        }, {
                            r: "i",
                            v: "current i"
                        }]
                    },
                    {
                        label: "STEP 10 — CALL Println",
                        title: "Call Println — output the value",
                        desc: "CALL transfers control to the Println subroutine, which prints the value (3, 6, 9, or 12) and returns.",
                        regs: [{
                            r: "output",
                            v: "prints i × 3",
                            c: true
                        }, {
                            r: "TEMP2",
                            v: "i × 3"
                        }, {
                            r: "i",
                            v: "current i"
                        }]
                    },
                    {
                        label: "STEP 11 — ADD i,'1'",
                        title: "Increment i (i++)",
                        desc: "ADD adds 1 to i in memory. This is the increment part of the for loop: i++.",
                        regs: [{
                            r: "R1",
                            v: "i × 3"
                        }, {
                            r: "TEMP2",
                            v: "i × 3"
                        }, {
                            r: "i",
                            v: "i + 1",
                            c: true
                        }]
                    },
                    {
                        label: "STEP 12 — B L1",
                        title: "Jump back to condition check",
                        desc: "Unconditional branch back to L1. The condition i<=4 is tested again. The loop runs while i is 1, 2, 3, 4.",
                        regs: [{
                            r: "R1",
                            v: "i × 3"
                        }, {
                            r: "TEMP2",
                            v: "i × 3"
                        }, {
                            r: "i",
                            v: "i + 1"
                        }]
                    },
                    {
                        label: "STEP 13 — L2 (done)",
                        title: "Loop finished, execution resumes",
                        desc: "When i becomes 5, BH at step 5 jumps here. The for loop is complete. Output was: 3, 6, 9, 12.",
                        regs: [{
                            r: "output",
                            v: "3, 6, 9, 12"
                        }, {
                            r: "TEMP1",
                            v: "4"
                        }, {
                            r: "i",
                            v: "5"
                        }]
                    },
                ]
            },
            {
                title: "Compiling: if (a > b) x = a; else x = b;",
                source: "if (a > b) x = a; else x = b;",
                lines: [
                    "    CMP a,b         // Compare a vs b",
                    "    BH  L1         // Branch if a > b",
                    "    MOV x,b        // else: x = b",
                    "    B   L2         // Skip 'then' block",
                    "L1: MOV x,a        // then: x = a",
                    "L2:                // Merge point",
                ],
                steps: [{
                        label: "STEP 1 — CMP a,b",
                        title: "Compare a and b",
                        desc: "CMP subtracts b from a and sets CPU condition flags based on the result. It does NOT store a value — it only sets flags for the branch instructions that follow.",
                        regs: [{
                            r: "a",
                            v: "a"
                        }, {
                            r: "b",
                            v: "b"
                        }, {
                            r: "flags",
                            v: "a vs b",
                            c: true
                        }, {
                            r: "x",
                            v: "—"
                        }]
                    },
                    {
                        label: "STEP 2 — BH L1",
                        title: "Branch if a > b (then branch)",
                        desc: "BH (Branch if Higher) checks the flags. If a > b, it jumps to label L1 where the 'then' block lives: x = a.",
                        regs: [{
                            r: "flags",
                            v: "a > b ?",
                            c: true
                        }, {
                            r: "b",
                            v: "b"
                        }, {
                            r: "a",
                            v: "a"
                        }, {
                            r: "x",
                            v: "—"
                        }]
                    },
                    {
                        label: "STEP 3 — MOV x,b",
                        title: "Else branch: assign x = b",
                        desc: "If BH did NOT branch (meaning a <= b), we arrive here — the else branch. MOV copies b directly into x.",
                        regs: [{
                            r: "flags",
                            v: "a <= b"
                        }, {
                            r: "b",
                            v: "b"
                        }, {
                            r: "a",
                            v: "a"
                        }, {
                            r: "x",
                            v: "b",
                            c: true
                        }]
                    },
                    {
                        label: "STEP 4 — B L2",
                        title: "Skip over the then-block",
                        desc: "After the else branch runs, an unconditional B jumps to L2, bypassing the 'then' block (x = a) which doesn't apply here.",
                        regs: [{
                            r: "flags",
                            v: "a <= b"
                        }, {
                            r: "b",
                            v: "b"
                        }, {
                            r: "a",
                            v: "a"
                        }, {
                            r: "x",
                            v: "b"
                        }]
                    },
                    {
                        label: "STEP 5 — L1: MOV x,a",
                        title: "Then branch: assign x = a",
                        desc: "Label L1 is where BH jumped when a > b. MOV copies a into x. This is the 'then' branch of the if statement.",
                        regs: [{
                            r: "flags",
                            v: "a > b"
                        }, {
                            r: "b",
                            v: "b"
                        }, {
                            r: "a",
                            v: "a"
                        }, {
                            r: "x",
                            v: "a",
                            c: true
                        }]
                    },
                    {
                        label: "STEP 6 — L2 (merge)",
                        title: "Both branches meet here",
                        desc: "Label L2 is the merge point. Whether we took the 'then' branch or the 'else' branch, execution continues from here. x now holds max(a, b).",
                        regs: [{
                            r: "flags",
                            v: "—"
                        }, {
                            r: "b",
                            v: "b"
                        }, {
                            r: "a",
                            v: "a"
                        }, {
                            r: "x",
                            v: "max(a,b)"
                        }]
                    },
                ]
            }
        ];

        let currentExample = 0;
        let currentStep = 0;

        function render() {
            const ex = examples[currentExample];
            const s = ex.steps[currentStep];

            document.getElementById("stepper-title").textContent = ex.title;
            document.getElementById("step-counter").textContent = `Step ${currentStep + 1} of ${ex.steps.length}`;

            document.getElementById("step-code").innerHTML = ex.lines.map((line, i) =>
                `<span class="code-line${i === currentStep ? " active" : ""}">${line}</span>`
            ).join("\n");

            document.getElementById("step-label").textContent = s.label;
            document.getElementById("step-title").textContent = s.title;
            document.getElementById("step-desc").textContent = s.desc;

            document.getElementById("reg-tbody").innerHTML = s.regs.map(r =>
                `<tr class="${r.c ? "changed" : ""}"><td>${r.r}</td><td>${r.v}</td></tr>`
            ).join("");

            document.getElementById("btn-prev").disabled = currentStep === 0;
            const nextBtn = document.getElementById("btn-next");
            nextBtn.disabled = currentStep === ex.steps.length - 1;
            nextBtn.textContent = currentStep === ex.steps.length - 1 ? "Done ✓" : "Next →";
            nextBtn.className = "step-btn" + (currentStep === ex.steps.length - 1 ? "" : " primary");
        }

        function stepMove(dir) {
            const ex = examples[currentExample];
            currentStep = Math.max(0, Math.min(ex.steps.length - 1, currentStep + dir));
            render();
        }

        function resetStep() {
            currentStep = 0;
            render();
        }

        function switchExample(idx, btn) {
            document.querySelectorAll(".pick-btn").forEach(b => b.classList.remove("active"));
            btn.classList.add("active");
            currentExample = idx;
            currentStep = 0;
            render();
        }

        render();
    </script>

</body>

</html>