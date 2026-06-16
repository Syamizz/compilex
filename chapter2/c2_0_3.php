<?php
session_start();
include '../dbconn.php';
include '../save_progress.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$chapter = 2;
$page = 3;
$nextPage = "c2_1_0.php";

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
    <title>Chapter 2.0 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c2/c2_0.css">
</head>

<body>

    <?php include 'navbar_c2.php'; ?>

    <div class="note-container">

        <!-- ── Sidebar TOC ─────────────────────────────────── -->
        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#regex" class="toc-link">2.0.3 Regular Expressions</a></li>
                <li><a href="#sp-b" class="toc-link sub">↳ Sample Problem (b)</a></li>
                <li><a href="#sp-c" class="toc-link sub">↳ Sample Problem (c)</a></li>
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
                <div class="chapter-tag">📘 Chapter 2 · Section 2.0</div>
                <h1>Lexical Analysis — Formal Languages</h1>
                <div class="metadata">
                    <span>⏱ 20 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>🔤 Automata Theory</span>
                </div>
            </header>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 2.0.3 Regular Expressions                     -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="regex">
                <h2><span class="sec-icon">🔣</span> 2.0.3 Regular Expressions</h2>
                <p>
                    Another method for specifying languages is <strong>regular expressions</strong> — formulas
                    involving three operations on languages:
                </p>

                <div class="op-grid">
                    <div class="op-card">
                        <div class="op-name">Union</div>
                        <div class="op-sym">+</div>
                        <div class="op-desc">The set of all strings in <em>either</em> language. Since a language is a set, this is standard set union.</div>
                        <code class="op-ex">{abc,ab,ba} + {ba,bb}<br>= {abc,ab,ba,bb}<br><br>L + {} = L</code>
                    </div>
                    <div class="op-card">
                        <div class="op-name">Concatenation</div>
                        <div class="op-sym">·</div>
                        <div class="op-desc">Join each string in L₁ with each string in L₂. Note: L₁·L₂ ≠ L₂·L₁ in general.</div>
                        <code class="op-ex">{ab,a,c} · {b,ε}<br>= {abb,ab,ab,a,cb,c}<br>= {abb,ab,a,cb,c}<br><br>s · ε = s &nbsp;|&nbsp; L · φ = φ</code>
                    </div>
                    <div class="op-card">
                        <div class="op-name">Kleene Star</div>
                        <div class="op-sym">*</div>
                        <div class="op-desc">Zero or more concatenations of strings from L. L* = L⁰ ∪ L¹ ∪ L² ∪ … Always includes ε.</div>
                        <code class="op-ex">L⁰ = {ε}<br>L¹ = L<br>L² = L·L<br>L* = L⁰+L¹+L²+…<br><br>φ* = {ε}</code>
                    </div>
                </div>

                <h3>📐 Operator Precedence (highest → lowest)</h3>
                <div class="prec-row"><span class="prec-badge prec-1">1st (highest) — Kleene * </span><span style="font-size:13px; color:var(--muted);">Unary postfix — binds tightest</span></div>
                <div class="prec-row"><span class="prec-badge prec-2">2nd — Concatenation · </span><span style="font-size:13px; color:var(--muted);">L₁·L₂* means L₁·(L₂*)</span></div>
                <div class="prec-row"><span class="prec-badge prec-3">3rd (lowest) — Union + </span><span style="font-size:13px; color:var(--muted);">L₁ + L₂·L₃ means L₁ + (L₂·L₃)</span></div>

                <h3>📌 Key Examples</h3>

                <div class="regex-box">
                    <span class="rx-com">// (0+1)* — all strings of zeros and ones</span><br>
                    Let L = {0,1}<br>
                    L⁰ = {<span class="rx-sym">ε</span>}<br>
                    L¹ = {<span class="rx-sym">0</span>, <span class="rx-sym">1</span>}<br>
                    L² = {<span class="rx-sym">00</span>, <span class="rx-sym">01</span>, <span class="rx-sym">10</span>, <span class="rx-sym">11</span>}<br>
                    L* = {ε, 0, 1, 00, 01, 10, 11, 000, …} = <span class="rx-res">all binary strings</span>
                </div>

                <div class="regex-box">
                    <span class="rx-com">// 1·(0+1)*·0 = 1(0+1)*0</span><br>
                    = {<span class="rx-sym">10</span>, <span class="rx-sym">100</span>, <span class="rx-sym">110</span>, <span class="rx-sym">1000</span>, <span class="rx-sym">1010</span>, <span class="rx-sym">1100</span>, …}<br>
                    = <span class="rx-res">all strings beginning with 1 and ending with 0</span><br>
                    <span class="rx-com">// Same language as Figure 2.1!</span>
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Nested Kleene stars</strong> are independent. In <code>(0*1)*</code>, the outer <code>*</code>
                        and inner <code>*</code> each generate their own repetitions independently. For example,
                        <code>0001101</code> can be generated: outer * repeats 3 times — first inner generates
                        <code>000</code>, second inner generates ε (0 times), third inner generates <code>0</code>,
                        then each is followed by a <code>1</code>.
                    </div>
                </div>

                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>
                        <strong>Shorthand:</strong> If <code>x</code> is a character in the alphabet, then
                        <code>x</code> in a regular expression stands for the set <code>{"x"}</code> — the
                        one-string language containing just that character. So:
                        <br><code>0 + 1 = {0} + {1} = {0,1}</code>
                        <br><code>0 + ε = {0, ε}</code>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Sample Problem 2.0(b)                         -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="sp-b">
                <h2><span class="sec-icon">🧪</span> Sample Problem 2.0(b) — Listing Strings from Regular Expressions</h2>

                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 2.0 (b)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">For each regular expression, list six strings which are in its language:</p>

                        <div class="sp-sub">1. (a(b+c)*)*d</div>
                        <div class="regex-box" style="margin-bottom:16px;">
                            Six strings: &nbsp;
                            <span class="rx-sym">d</span> &nbsp;
                            <span class="rx-sym">ad</span> &nbsp;
                            <span class="rx-sym">abd</span> &nbsp;
                            <span class="rx-sym">acd</span> &nbsp;
                            <span class="rx-sym">aad</span> &nbsp;
                            <span class="rx-sym">abbcbd</span><br>
                            <span class="rx-com">// Outer * can fire 0 times (→ just d) or many times</span>
                        </div>

                        <div class="sp-sub">2. (a+b)*·(c+d)</div>
                        <div class="regex-box" style="margin-bottom:16px;">
                            Six strings: &nbsp;
                            <span class="rx-sym">c</span> &nbsp;
                            <span class="rx-sym">d</span> &nbsp;
                            <span class="rx-sym">ac</span> &nbsp;
                            <span class="rx-sym">abd</span> &nbsp;
                            <span class="rx-sym">babc</span> &nbsp;
                            <span class="rx-sym">bad</span><br>
                            <span class="rx-com">// (a+b)* generates any a/b string (incl. ε); must end in c or d</span>
                        </div>

                        <div class="sp-sub">3. (a*b*)*</div>
                        <div class="regex-box">
                            Six strings: &nbsp;
                            <span class="rx-sym">ε</span> &nbsp;
                            <span class="rx-sym">a</span> &nbsp;
                            <span class="rx-sym">b</span> &nbsp;
                            <span class="rx-sym">ab</span> &nbsp;
                            <span class="rx-sym">ba</span> &nbsp;
                            <span class="rx-sym">aa</span><br>
                            <span class="rx-com">// Note: (a*b*)* = (a+b)* — generates ALL strings of a's and b's</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Sample Problem 2.0(c)                         -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="sp-c">
                <h2><span class="sec-icon">🧪</span> Sample Problem 2.0(c) — Regular Expressions from FSMs</h2>

                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 2.0 (c) — Regular expressions for Sample Problem 2.0(a)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">Give a regular expression for each of the languages in Sample Problem 2.0(a):</p>

                        <div class="sp-sub">1. Odd number of zeros &nbsp;→</div>
                        <div class="regex-box" style="margin-bottom:16px;">
                            <span class="rx-op">1</span><span class="rx-star">*</span>
                            <span class="rx-sym">0</span>
                            <span class="rx-op">1</span><span class="rx-star">*</span>
                            (<span class="rx-sym">0</span>
                            <span class="rx-op">1</span><span class="rx-star">*</span>
                            <span class="rx-sym">0</span>
                            <span class="rx-op">1</span><span class="rx-star">*</span>)<span class="rx-star">*</span><br>
                            <span class="rx-com">// One zero, surrounded by any 1s, optionally followed by pairs of zeros with 1s in between</span>
                        </div>

                        <div class="sp-sub">2. Three consecutive ones &nbsp;→</div>
                        <div class="regex-box" style="margin-bottom:16px;">
                            (<span class="rx-sym">0</span>+<span class="rx-sym">1</span>)<span class="rx-star">*</span>
                            <span class="rx-sym">111</span>
                            (<span class="rx-sym">0</span>+<span class="rx-sym">1</span>)<span class="rx-star">*</span><br>
                            <span class="rx-com">// Any string before and after the "111" substring</span>
                        </div>

                        <div class="sp-sub">3. Exactly three zeros &nbsp;→</div>
                        <div class="regex-box" style="margin-bottom:16px;">
                            <span class="rx-op">1</span><span class="rx-star">*</span>
                            <span class="rx-sym">0</span>
                            <span class="rx-op">1</span><span class="rx-star">*</span>
                            <span class="rx-sym">0</span>
                            <span class="rx-op">1</span><span class="rx-star">*</span>
                            <span class="rx-sym">0</span>
                            <span class="rx-op">1</span><span class="rx-star">*</span><br>
                            <span class="rx-com">// Exactly three 0s, with any number of 1s between/around them</span>
                        </div>

                        <div class="sp-sub">4. Odd zeros AND even ones &nbsp;→</div>
                        <div class="regex-box">
                            (<span class="rx-sym">00</span>+<span class="rx-sym">11</span>)<span class="rx-star">*</span>
                            (<span class="rx-sym">01</span>+<span class="rx-sym">10</span>)
                            (<span class="rx-sym">1</span>(<span class="rx-sym">0</span>(<span class="rx-sym">11</span>)<span class="rx-star">*</span><span class="rx-sym">0</span>)<span class="rx-star">*</span><span class="rx-sym">1</span>+<span class="rx-sym">0</span>(<span class="rx-sym">1</span>(<span class="rx-sym">00</span>)<span class="rx-star">*</span><span class="rx-sym">1</span>)<span class="rx-star">*</span><span class="rx-sym">0</span>)<span class="rx-star">*</span>
                            <span class="rx-sym">1</span>(<span class="rx-sym">0</span>(<span class="rx-sym">11</span>)<span class="rx-star">*</span><span class="rx-sym">0</span>)<span class="rx-star">*</span> +
                            (<span class="rx-sym">00</span>+<span class="rx-sym">11</span>)<span class="rx-star">*</span><span class="rx-sym">0</span><br>
                            <span class="rx-com">// Complex — tracking both zero parity and one parity simultaneously</span>
                        </div>
                    </div>
                </div>
            </section>


            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="c2_0_2.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous Page</span>2.0.2 Finite State Machines</div>
                </a>
                <a href="?complete=3" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>2.1.0 Lexical Tokens</div>
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

        // ── FSM Simulator ────────────────────────────────────────
        const machines = {
            fig21: {
                label: 'Fig 2.1 — begins with 1, ends with 0',
                start: 'A',
                accept: ['C'],
                transitions: {
                    A: {
                        '0': 'A',
                        '1': 'B'
                    },
                    B: {
                        '0': 'C',
                        '1': 'B'
                    },
                    C: {
                        '0': 'C',
                        '1': 'B'
                    }
                }
            },
            fig22: {
                label: 'Fig 2.2 — even parity (even number of 1s)',
                start: 'A',
                accept: ['A'],
                transitions: {
                    A: {
                        '0': 'A',
                        '1': 'B'
                    },
                    B: {
                        '0': 'B',
                        '1': 'A'
                    }
                }
            },
            sp1: {
                label: 'SP(a).1 — odd number of zeros',
                start: 'A',
                accept: ['B'],
                transitions: {
                    A: {
                        '0': 'B',
                        '1': 'A'
                    },
                    B: {
                        '0': 'A',
                        '1': 'B'
                    }
                }
            },
            sp2: {
                label: 'SP(a).2 — three consecutive ones',
                start: 'A',
                accept: ['D'],
                transitions: {
                    A: {
                        '0': 'A',
                        '1': 'B'
                    },
                    B: {
                        '0': 'A',
                        '1': 'C'
                    },
                    C: {
                        '0': 'A',
                        '1': 'D'
                    },
                    D: {
                        '0': 'D',
                        '1': 'D'
                    }
                }
            }
        };

        let currentMachine = machines.fig21;
        let simState = null,
            simPos = 0,
            simStr = '';

        function changeMachine() {
            const sel = document.getElementById('machine-select').value;
            currentMachine = machines[sel];
            document.getElementById('sim-machine-label').textContent = currentMachine.label;
            resetSim();
        }

        function resetSim() {
            simState = null;
            simPos = 0;
            simStr = document.getElementById('sim-input').value.replace(/[^01]/g, '');
            document.getElementById('sim-input').value = simStr;
            renderTape();
            updateBubble('—', '#6B7280', '#F8F7FF', '#E5E7EB');
            document.getElementById('sim-step-label').textContent = '';
            const res = document.getElementById('sim-result');
            res.className = 'sim-result';
            res.textContent = '';
            document.getElementById('btn-step').disabled = simStr.length === 0;
            document.getElementById('btn-auto').disabled = simStr.length === 0;
        }

        function renderTape() {
            const tape = document.getElementById('sim-tape');
            tape.innerHTML = simStr.split('').map((c, i) => {
                let cls = 'sim-char';
                if (i < simPos) cls += ' read';
                if (i === simPos && simState !== null) cls += ' current';
                return `<div class="${cls}">${c}</div>`;
            }).join('') || '<span style="font-size:13px;color:var(--muted);">No input yet</span>';
        }

        function updateBubble(text, color, bg, border) {
            const b = document.getElementById('sim-state-bubble');
            b.textContent = text;
            b.style.color = color;
            b.style.background = bg;
            b.style.borderColor = border;
        }

        function stepSim() {
            if (simState === null) {
                simState = currentMachine.start;
                simPos = 0;
                renderTape();
                updateBubble(simState, '#6366F1', '#EEF2FF', '#6366F1');
                return;
            }
            if (simPos >= simStr.length) {
                finishSim();
                return;
            }
            const ch = simStr[simPos];
            const next = currentMachine.transitions[simState]?.[ch];
            if (!next) {
                updateBubble('ERR', '#EF4444', '#FFF1F2', '#EF4444');
                finishSim();
                return;
            }
            simState = next;
            simPos++;
            document.getElementById('sim-step-label').textContent = `Read "${ch}" → ${simState}`;
            renderTape();
            const accepted = currentMachine.accept.includes(simState);
            updateBubble(simState, accepted ? '#10B981' : '#6366F1', accepted ? '#F0FDF4' : '#EEF2FF', accepted ? '#10B981' : '#6366F1');
            if (simPos >= simStr.length) finishSim();
        }

        function finishSim() {
            const accepted = currentMachine.accept.includes(simState);
            const res = document.getElementById('sim-result');
            res.className = 'sim-result show ' + (accepted ? 'accepted' : 'rejected');
            res.innerHTML = accepted ?
                `✅ <strong>ACCEPTED</strong> — String "${simStr}" is in the language.` :
                `❌ <strong>REJECTED</strong> — String "${simStr}" is not in the language.`;
            document.getElementById('btn-step').disabled = true;
            document.getElementById('btn-auto').disabled = true;
        }

        function runSim() {
            simStr = document.getElementById('sim-input').value.replace(/[^01]/g, '');
            document.getElementById('sim-input').value = simStr;
            simState = currentMachine.start;
            simPos = 0;
            for (const ch of simStr) {
                const next = currentMachine.transitions[simState]?.[ch];
                if (!next) {
                    simState = '__err__';
                    break;
                }
                simState = next;
            }
            simPos = simStr.length;
            renderTape();
            finishSim();
            document.getElementById('sim-step-label').textContent = `Final state: ${simState}`;
        }

        function autoPlay() {
            simStr = document.getElementById('sim-input').value.replace(/[^01]/g, '');
            document.getElementById('sim-input').value = simStr;
            if (!simStr) return;
            simState = null;
            simPos = 0;
            const res = document.getElementById('sim-result');
            res.className = 'sim-result';
            res.textContent = '';
            renderTape();
            updateBubble('—', '#6B7280', '#F8F7FF', '#E5E7EB');
            document.getElementById('btn-step').disabled = true;
            document.getElementById('btn-auto').disabled = true;
            let i = -1;

            function next() {
                i++;
                if (i === 0) {
                    simState = currentMachine.start;
                    renderTape();
                    updateBubble(simState, '#6366F1', '#EEF2FF', '#6366F1');
                    setTimeout(next, 600);
                    return;
                }
                if (i > simStr.length) {
                    finishSim();
                    return;
                }
                stepSim();
                if (i <= simStr.length) setTimeout(next, 600);
            }
            next();
        }

        // initialise
        resetSim();
    </script>

</body>

</html>