<?php
session_start();
include '../dbconn.php';
include '../save_progress.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$chapter = 3;
$page = 4;
$nextPage = "c3_0_5.php";

if (isset($_GET['complete']) && $_GET['complete'] == '4') {
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
    <title>Chapter 3.0.3–3.0.5 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../css/c3/c3_0_0.css">
</head>

<body>

    <?php include 'navbar_c3.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                 <li><a href="#pdm" class="toc-link">3.0.4 Pushdown Machines</a></li>
                <li><a href="#pdm-g2" class="toc-link sub">↳ PDM for aⁿbⁿ (Fig 3.4)</a></li>
                <li><a href="#pdm-paren" class="toc-link sub">↳ PDM for Parentheses (Fig 3.6)</a></li>
                <li><a href="#replace" class="toc-link sub">↳ Replace Operation</a></li>
                <li><a href="#postfix" class="toc-link sub">↳ Infix → Postfix (Fig 3.8)</a></li>
                <li><a href="#sp-d" class="toc-link sub">↳ Sample Problem (d)</a></li>
            </ul>
            <div class="toc-progress">
                <div class="toc-progress-label"><span>Progress</span><span id="pct-label">0%</span></div>
                <div class="toc-bar">
                    <div class="toc-bar-fill" id="toc-bar"></div>
                </div>
            </div>
        </nav>

        <article class="content">

            <header class="content-header">
                <div class="chapter-tag">📘 Chapter 3 · Sections 3.0.3 – 3.0.5</div>
                <h1>Context-Free Grammars &amp; Pushdown Machines</h1>
                <div class="metadata">
                    <span>⏱ 22 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>📐 Formal Languages</span>
                </div>
            </header>

      
       
           
       

        

    

            <!-- ══════════════════════════════════════════════ -->
            <!-- 3.0.4 Pushdown Machines                       -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="pdm">
                <h2><span class="sec-icon">🗂️</span> 3.0.4 Pushdown Machines</h2>
                <p>
                    A <strong>pushdown machine (PDM)</strong> is used for syntax analysis, just as a finite state
                    machine is used for lexical analysis. It is like an FSM but adds an <strong>infinite stack</strong>.
                </p>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Component</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>States</td>
                            <td>Finite set; one is the starting state</td>
                        </tr>
                        <tr>
                            <td>Input alphabet</td>
                            <td>The input symbols; includes <code>N</code> as end-of-input marker</td>
                        </tr>
                        <tr>
                            <td>Stack</td>
                            <td>Infinite LIFO stack; initialised with <code>&#44;</code> (bottom-of-stack marker). Stack symbols may overlap with input symbols.</td>
                        </tr>
                        <tr>
                            <td>Transition function</td>
                            <td>Arguments: (current state, current input, top of stack) → new state + stack operation + input pointer move</td>
                        </tr>
                        <tr>
                            <td>Input pointer</td>
                            <td>Each transition may <strong>advance</strong> to next input or <strong>retain</strong> (not advance)</td>
                        </tr>
                        <tr>
                            <td>Stack operations</td>
                            <td><strong>push(X)</strong> or <strong>pop</strong> on each transition</td>
                        </tr>
                        <tr>
                            <td>Termination</td>
                            <td>Machine halts by taking an <strong>Accept</strong> or <strong>Reject</strong> exit — unlike FSM which halts when all input is consumed</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>
                        Without the infinite stack, a pushdown machine is simply a <strong>finite state machine</strong>.
                        The stack is what gives it additional power to recognise context-free languages.
                    </div>
                </div>
            </section>

            <!-- PDM for anbn -->
            <section id="pdm-g2">
                <h2><span class="sec-icon">📊</span> Figure 3.4 — PDM for aⁿbⁿ (Language of G2)</h2>
                <p>
                    This pushdown machine accepts exactly the language <code>aⁿbⁿ</code> (n ≥ 0) — the same language
                    as Grammar G2. In state S1 it pushes X's as each <code>a</code> is read; in state S2 it pops
                    an X for each <code>b</code> read.
                </p>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin:16px 0;">
                    <div>
                        <p style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--purple);margin-bottom:8px;">State S1 (pushing X's)</p>
                        <table class="pdm-table">
                            <thead>
                                <tr>
                                    <th>Stack</th>
                                    <th>a</th>
                                    <th>b</th>
                                    <th>N</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="stack-sym">X</td>
                                    <td class="action">Push(X)<br>Advance<br>→S1</td>
                                    <td class="action">Pop<br>Advance<br>→S2</td>
                                    <td class="reject">Reject</td>
                                </tr>
                                <tr>
                                    <td class="stack-sym">,</td>
                                    <td class="action">Push(X)<br>Advance<br>→S1</td>
                                    <td class="reject">Reject</td>
                                    <td class="accept">Accept</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <p style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--purple);margin-bottom:8px;">State S2 (popping X's)</p>
                        <table class="pdm-table">
                            <thead>
                                <tr>
                                    <th>Stack</th>
                                    <th>a</th>
                                    <th>b</th>
                                    <th>N</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="stack-sym">X</td>
                                    <td class="reject">Reject</td>
                                    <td class="action">Pop<br>Advance<br>→S2</td>
                                    <td class="reject">Reject</td>
                                </tr>
                                <tr>
                                    <td class="stack-sym">,</td>
                                    <td class="reject">Reject</td>
                                    <td class="reject">Reject</td>
                                    <td class="accept">Accept</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <h3>Figure 3.5 — Stack trace for input <code>aabb</code></h3>
                <div class="stack-trace">
                    <div class="st-step">
                        <div class="st-stack">
                            <div class="st-sym sym-bot">,</div>
                        </div>
                        <div class="st-input">start</div>
                        <div class="st-state">S1</div>
                    </div>
                    <div style="color:var(--purple);font-size:18px;align-self:flex-end;padding-bottom:28px;">→</div>
                    <div class="st-step">
                        <div class="st-stack">
                            <div class="st-sym sym-x">X</div>
                            <div class="st-sym sym-bot">,</div>
                        </div>
                        <div class="st-input">read a</div>
                        <div class="st-state">S1</div>
                    </div>
                    <div style="color:var(--purple);font-size:18px;align-self:flex-end;padding-bottom:28px;">→</div>
                    <div class="st-step">
                        <div class="st-stack">
                            <div class="st-sym sym-x">X</div>
                            <div class="st-sym sym-x">X</div>
                            <div class="st-sym sym-bot">,</div>
                        </div>
                        <div class="st-input">read a</div>
                        <div class="st-state">S1</div>
                    </div>
                    <div style="color:var(--purple);font-size:18px;align-self:flex-end;padding-bottom:28px;">→</div>
                    <div class="st-step">
                        <div class="st-stack">
                            <div class="st-sym sym-x">X</div>
                            <div class="st-sym sym-bot">,</div>
                        </div>
                        <div class="st-input">read b</div>
                        <div class="st-state">S2</div>
                    </div>
                    <div style="color:var(--purple);font-size:18px;align-self:flex-end;padding-bottom:28px;">→</div>
                    <div class="st-step">
                        <div class="st-stack">
                            <div class="st-sym sym-bot">,</div>
                        </div>
                        <div class="st-input">read b</div>
                        <div class="st-state">S2</div>
                    </div>
                    <div style="color:var(--purple);font-size:18px;align-self:flex-end;padding-bottom:28px;">→</div>
                    <div class="st-step">
                        <div class="st-stack">
                            <div class="st-sym sym-bot">,</div>
                        </div>
                        <div class="st-input">read N</div>
                        <div class="st-state accept" style="color:var(--green);">ACCEPT</div>
                    </div>
                </div>
            </section>

            <!-- PDM parentheses -->
            <section id="pdm-paren">
                <h2><span class="sec-icon">( )</span> Figure 3.6 — PDM for Balanced Parentheses</h2>
                <p>
                    This machine accepts any string of correctly balanced parentheses. It cannot be accepted
                    by a finite state machine because there could be an <em>unlimited number of nested</em>
                    left parentheses before the first right parenthesis — the stack handles this naturally.
                </p>

                <table class="pdm-table" style="max-width:400px;">
                    <thead>
                        <tr>
                            <th>Stack</th>
                            <th>( (left)</th>
                            <th>) (right)</th>
                            <th>N</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="stack-sym">X</td>
                            <td class="action">Push(X)<br>Advance<br>→S1</td>
                            <td class="action">Pop<br>Advance<br>→S1</td>
                            <td class="reject">Reject</td>
                        </tr>
                        <tr>
                            <td class="stack-sym">,</td>
                            <td class="action">Push(X)<br>Advance<br>→S1</td>
                            <td class="reject">Reject</td>
                            <td class="accept">Accept</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        This language is <em>similar</em> to L(G2): each left parenthesis pushes X; each right parenthesis pops X. Acceptance when both input and stack (above <code>,</code>) are empty. Compare with Grammar G2 which uses a's and b's instead of ( and ).
                    </div>
                </div>
            </section>

            <!-- Replace Operation -->
            <section id="replace">
                <h2><span class="sec-icon">🔄</span> The Replace Operation — Rep(X, Y, Z, …)</h2>
                <p>
                    To simplify pushdown machine descriptions, an <strong>extended pushdown machine</strong> adds
                    the <strong>Replace</strong> operation: <code>Rep(X, Y, Z, …)</code>. This pops the top stack
                    symbol and then pushes the given symbols in order (left to right).
                </p>
                <p>
                    For example, <code>Rep(Term, +, Expr)</code> pops the current top and pushes:
                    first <code>Term</code>, then <code>+</code>, then <code>Expr</code>
                    (so Expr is on top after the operation).
                </p>
                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>
                        The Replace operation adds <em>no extra power</em> — every Replace can be expressed as a pop
                        followed by one or more pushes. It is included only for <strong>convenience</strong> to simplify
                        problem solutions.
                    </div>
                </div>
            </section>

            <!-- Infix to Postfix -->
            <section id="postfix">
                <h2><span class="sec-icon">🔢</span> Figure 3.8 — Extended Pushdown Translator: Infix → Postfix</h2>
                <p>
                    A <strong>pushdown translator</strong> is a pushdown machine with an <em>output function</em>
                    on transitions. Figure 3.8 shows an extended pushdown translator that converts simple
                    infix expressions to postfix. In postfix, both operands precede the operator.
                </p>

                <div class="postfix-wrap">
                    <div class="postfix-header">
                        <h4>Infix → Postfix Conversion Examples</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);">a = any variable or constant</span>
                    </div>
                    <div class="postfix-body">
                        <div class="postfix-examples">
                            <div class="postfix-ex"><span class="infix">2 + 3</span><span class="arr">→</span><span class="postfix">2 3 +</span></div>
                            <div class="postfix-ex"><span class="infix">2 + 3 * 5</span><span class="arr">→</span><span class="postfix">2 3 5 * +</span></div>
                            <div class="postfix-ex"><span class="infix">2 * 3 + 5</span><span class="arr">→</span><span class="postfix">2 3 * 5 +</span></div>
                            <div class="postfix-ex"><span class="infix">(2 + 3) * 5</span><span class="arr">→</span><span class="postfix">2 3 + 5 *</span></div>
                        </div>
                        <div class="note-box tip" style="margin-top:0;">
                            <span class="box-icon">💡</span>
                            <div>
                                Parentheses are <strong>never used in postfix</strong> — operator precedence is encoded in
                                the order of operands. The stack symbol <code>E</code> represents an expression on the stack;
                                <code>L</code> represents a left parenthesis; <code>Ep</code> = expression above a plus;
                                <code>Lp</code> = left paren above a plus.
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sample Problem 3.0(d) -->
            <section id="sp-d">
                <h2><span class="sec-icon">🧪</span> Sample Problem 3.0(d) — PDM Trace for a+(a*a)</h2>
                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 3.0 (d) — Figure 3.8 trace for input a+(a*a)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">Show the sequence of stacks and states which the pushdown machine of Figure 3.8 would go through for input <code>a+(a*a)</code>.</p>
                        <div class="sp-label">✅ Solution — Output: aa a*+</div>
                        <div style="overflow-x:auto;">
                            <table class="pdm-table" style="min-width:600px;">
                                <thead>
                                    <tr>
                                        <th>Stack top→bot</th>
                                        <th>Input</th>
                                        <th>State</th>
                                        <th>Action/Output</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>,</td>
                                        <td>a</td>
                                        <td>S1</td>
                                        <td>out(a), push(E)</td>
                                    </tr>
                                    <tr>
                                        <td>E ,</td>
                                        <td>+</td>
                                        <td>S1</td>
                                        <td>push(+) → Lp on E? push(Ep)</td>
                                    </tr>
                                    <tr>
                                        <td>Ep ,</td>
                                        <td>(</td>
                                        <td>S1</td>
                                        <td>push(Lp)</td>
                                    </tr>
                                    <tr>
                                        <td>Lp Ep ,</td>
                                        <td>a</td>
                                        <td>S1</td>
                                        <td>out(a), push(E)</td>
                                    </tr>
                                    <tr>
                                        <td>E Lp Ep ,</td>
                                        <td>*</td>
                                        <td>S1</td>
                                        <td>out(a*) — multiply is above; push(Ls)</td>
                                    </tr>
                                    <tr>
                                        <td>Ls Ep ,</td>
                                        <td>a</td>
                                        <td>S1</td>
                                        <td>out(a), push(E)</td>
                                    </tr>
                                    <tr>
                                        <td>E Ls Ep ,</td>
                                        <td>)</td>
                                        <td>S1</td>
                                        <td>→S2 (handle closing paren)</td>
                                    </tr>
                                    <tr>
                                        <td>Ep ,</td>
                                        <td>—</td>
                                        <td>S3→S1</td>
                                        <td>out(+), Rep(E)</td>
                                    </tr>
                                    <tr>
                                        <td>E ,</td>
                                        <td>N</td>
                                        <td>S1</td>
                                        <td>Accept</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="note-box key" style="margin-top:14px;">
                            <span class="box-icon">✅</span>
                            <div>Output produced: <strong style="font-family:'JetBrains Mono',monospace;">a a a* +</strong> — which is the correct postfix for a+(a*a). Multiplication binds tighter so its operands appear together.</div>
                        </div>
                    </div>
                </div>
            </section>


      


            <div class="chapter-nav">
                <a href="c3_0_3.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>3.0.3 Context-Free Grammars</div>
                </a>
                <a href="?complete=4" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>3.0.5 Correspondence Between Machines & Languages</div>
                    <span>→</span>
                </a>
            </div>

        </article>
    </div>

    <script>
        // TOC scroll
        const sections = document.querySelectorAll('section[id]');
        const tocLinks = document.querySelectorAll('.toc-link');
        const bar = document.getElementById('toc-bar');
        const pctLabel = document.getElementById('pct-label');
        window.addEventListener('scroll', () => {
            const s = window.scrollY,
                t = document.body.scrollHeight - window.innerHeight;
            bar.style.width = Math.round(s / t * 100) + '%';
            pctLabel.textContent = Math.round(s / t * 100) + '%';
            let cur = '';
            sections.forEach(sec => {
                if (s >= sec.offsetTop - 120) cur = sec.id;
            });
            tocLinks.forEach(l => l.classList.toggle('active', l.getAttribute('href') === '#' + cur));
        });

        // ════════════════════════════════════════════════════════
        // PDM SIMULATOR
        // ════════════════════════════════════════════════════════
        // Machines: fig34 = anbn, fig36 = balanced parens
        const MACHINES = {
            anbn: {
                // State S1: push X on a; switch to S2 on b; accept on N with just ,
                // State S2: pop on b; accept on N with just ,
                simulate: function(str) {
                    const input = str.split('').concat(['N']);
                    let stack = [','];
                    let state = 'S1';
                    let i = 0;
                    const trace = [{
                        stack: [...stack],
                        state,
                        input_sym: '(start)'
                    }];
                    let result = null;
                    while (i < input.length && result === null) {
                        const sym = input[i];
                        const top = stack[stack.length - 1];
                        if (state === 'S1') {
                            if (sym === 'a') {
                                stack.push('X');
                                i++;
                            } else if (sym === 'b') {
                                if (top === 'X') {
                                    stack.pop();
                                    i++;
                                    state = 'S2';
                                } else {
                                    result = 'reject';
                                    break;
                                }
                            } else if (sym === 'N') {
                                result = (top === ',') ? 'accept' : 'reject';
                                break;
                            } else {
                                result = 'reject';
                                break;
                            }
                        } else {
                            if (sym === 'b') {
                                if (top === 'X') {
                                    stack.pop();
                                    i++;
                                } else {
                                    result = 'reject';
                                    break;
                                }
                            } else if (sym === 'N') {
                                result = (top === ',') ? 'accept' : 'reject';
                                break;
                            } else {
                                result = 'reject';
                                break;
                            }
                        }
                        trace.push({
                            stack: [...stack],
                            state,
                            input_sym: sym
                        });
                    }
                    if (!result) result = 'reject';
                    return {
                        trace,
                        result
                    };
                }
            },
            paren: {
                simulate: function(str) {
                    const input = str.split('').concat(['N']);
                    let stack = [','];
                    let state = 'S1';
                    let i = 0;
                    const trace = [{
                        stack: [...stack],
                        state,
                        input_sym: '(start)'
                    }];
                    let result = null;
                    while (i < input.length && result === null) {
                        const sym = input[i];
                        const top = stack[stack.length - 1];
                        if (sym === '(') {
                            stack.push('X');
                            i++;
                        } else if (sym === ')') {
                            if (top === 'X') {
                                stack.pop();
                                i++;
                            } else {
                                result = 'reject';
                                break;
                            }
                        } else if (sym === 'N') {
                            result = (top === ',') ? 'accept' : 'reject';
                            break;
                        } else {
                            result = 'reject';
                            break;
                        }
                        trace.push({
                            stack: [...stack],
                            state,
                            input_sym: sym
                        });
                    }
                    if (!result) result = 'reject';
                    return {
                        trace,
                        result
                    };
                }
            }
        };

        let pdmTrace = null,
            pdmPos = 0,
            pdmStr = '';

        function pdmEx(machine, s) {
            document.getElementById('pdm-machine-sel').value = machine;
            document.getElementById('pdm-input').value = s;
            pdmReset();
        }

        function pdmReset() {
            pdmStr = document.getElementById('pdm-input').value.trim();
            pdmPos = 0;
            pdmTrace = null;
            renderPdmTape([]);
            renderPdmStack([',']);
            document.getElementById('pdm-cur-state').textContent = '—';
            document.getElementById('pdm-log').textContent = 'Press Step or Run to simulate.';
            document.getElementById('pdm-step-btn').disabled = false;
            const res = document.getElementById('pdm-result');
            res.className = 'pdm-status';
            res.textContent = '';
            document.getElementById('pdm-status-label').textContent = 'Ready';
        }

        function pdmEnsureTrace() {
            if (!pdmTrace) {
                const machine = document.getElementById('pdm-machine-sel').value;
                pdmStr = document.getElementById('pdm-input').value.trim();
                const m = machine === 'anbn' ? MACHINES.anbn : MACHINES.paren;
                pdmTrace = m.simulate(pdmStr);
                pdmPos = 0;
            }
        }

        function pdmStep() {
            pdmEnsureTrace();
            if (pdmPos >= pdmTrace.trace.length) {
                finishPdm();
                return;
            }
            const step = pdmTrace.trace[pdmPos];
            renderPdmTape(pdmStr.split(''), pdmPos - 1);
            renderPdmStack(step.stack);
            document.getElementById('pdm-cur-state').textContent = step.state;
            document.getElementById('pdm-log').textContent = `Step ${pdmPos}: read "${step.input_sym}" · stack top=${step.stack[step.stack.length-1]}`;
            pdmPos++;
            document.getElementById('pdm-status-label').textContent = `Step ${pdmPos}/${pdmTrace.trace.length}`;
            if (pdmPos >= pdmTrace.trace.length) finishPdm();
        }

        function pdmRun() {
            pdmEnsureTrace();
            pdmPos = pdmTrace.trace.length;
            renderPdmTape(pdmStr.split(''), pdmStr.length);
            const last = pdmTrace.trace[pdmTrace.trace.length - 1];
            renderPdmStack(last.stack);
            document.getElementById('pdm-cur-state').textContent = last.state;
            finishPdm();
        }

        function finishPdm() {
            document.getElementById('pdm-step-btn').disabled = true;
            const accepted = pdmTrace.result === 'accept';
            const res = document.getElementById('pdm-result');
            res.className = 'pdm-status show ' + (accepted ? 'accepted' : 'rejected');
            res.innerHTML = accepted ?
                `✅ <strong>ACCEPTED</strong> — "${pdmStr}" is in the language.` :
                `❌ <strong>REJECTED</strong> — "${pdmStr}" is not in the language.`;
            document.getElementById('pdm-status-label').textContent = accepted ? 'ACCEPTED ✅' : 'REJECTED ❌';
        }

        function renderPdmTape(chars, readUpTo) {
            const tape = document.getElementById('pdm-tape');
            tape.innerHTML = chars.map((c, i) => {
                let cls = 'pdm-char';
                if (i < readUpTo) cls += ' read';
                else if (i === readUpTo) cls += ' current';
                return `<div class="${cls}">${c}</div>`;
            }).join('') || '<span style="font-size:13px;color:var(--muted);">No input</span>';
        }

        function renderPdmStack(stack) {
            const vis = document.getElementById('pdm-stack');
            vis.innerHTML = '';
            [...stack].reverse().forEach((s, i) => {
                const el = document.createElement('div');
                el.className = 'pdm-stack-item';
                if (s === 'X') {
                    el.style.background = '#EEF2FF';
                    el.style.color = '#6366F1';
                } else if (s === ',') {
                    el.style.background = '#F1F5F9';
                    el.style.color = '#475569';
                } else {
                    el.style.background = '#FEF3C7';
                    el.style.color = '#92400E';
                }
                el.textContent = s;
                vis.appendChild(el);
            });
        }

        // init
        pdmReset();

        // ════════════════════════════════════════════════════════
        // DRAG AND DROP
        // ════════════════════════════════════════════════════════
        let draggingId = null;

        function dragStart(e) {
            draggingId = e.target.id;
            e.target.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', e.target.id);
        }

        function allowDrop(e) {
            e.preventDefault();
            e.currentTarget.classList.add('drag-over');
        }
        document.addEventListener('dragleave', e => {
            if (e.target.classList) e.target.classList.remove('drag-over');
        });
        document.addEventListener('dragend', () => {
            document.querySelectorAll('.dnd-chip').forEach(c => c.classList.remove('dragging'));
            document.querySelectorAll('.dnd-zone,.dnd-bank').forEach(z => z.classList.remove('drag-over'));
        });

        function dropChip(e, targetId) {
            e.preventDefault();
            e.currentTarget.classList.remove('drag-over');
            const chipId = e.dataTransfer.getData('text/plain');
            const chip = document.getElementById(chipId);
            const target = document.getElementById(targetId);
            if (!chip || !target) return;
            const isZone = target.classList.contains('dnd-zone');
            if (isZone && target.children.length > 0) {
                const ex = target.firstElementChild;
                const b = findBank(targetId);
                if (b) {
                    document.getElementById(b).appendChild(ex);
                    ex.onclick = null;
                }
            }
            target.appendChild(chip);
            chip.classList.remove('dragging');
            if (isZone) {
                chip.onclick = () => {
                    const b = findBank(targetId);
                    if (b) {
                        document.getElementById(b).appendChild(chip);
                        chip.onclick = null;
                    }
                };
            } else chip.onclick = null;
        }

        function findBank(zoneId) {
            const m = zoneId.match(/^z(\d)/);
            return m ? 'bank' + m[1] : null;
        }

        function checkDnD(quizId, zoneIds) {
            let correct = 0;
            const total = zoneIds.length;
            zoneIds.forEach(zid => {
                const zone = document.getElementById(zid);
                const chip = zone.firstElementChild;
                zone.classList.remove('correct-zone', 'wrong-zone');
                if (chip && chip.id === zone.dataset.answer) {
                    zone.classList.add('correct-zone');
                    correct++;
                } else zone.classList.add('wrong-zone');
            });
            const fb = document.getElementById('fb-' + quizId);
            fb.classList.remove('show', 'ok', 'bad', 'partial');
            if (correct === total) {
                fb.className = 'dnd-feedback show ok';
                fb.innerHTML = `✅ <strong>Perfect!</strong> All ${total} matched correctly.`;
            } else if (correct > 0) {
                fb.className = 'dnd-feedback show partial';
                fb.innerHTML = `⚠️ <strong>${correct} of ${total}</strong> correct.`;
            } else {
                fb.className = 'dnd-feedback show bad';
                fb.innerHTML = `❌ None correct yet. Review and try again.`;
            }
        }

        function resetDnD(quizId, bankId, zoneIds) {
            const bank = document.getElementById(bankId);
            zoneIds.forEach(zid => {
                const zone = document.getElementById(zid);
                zone.classList.remove('correct-zone', 'wrong-zone');
                while (zone.firstElementChild) {
                    const c = zone.firstElementChild;
                    c.onclick = null;
                    bank.appendChild(c);
                }
            });
            const fb = document.getElementById('fb-' + quizId);
            fb.className = 'dnd-feedback';
            fb.innerHTML = '';
        }


    </script>

</body>

</html>