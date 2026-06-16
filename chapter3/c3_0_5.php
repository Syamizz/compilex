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
$page = 5;
$nextPage = "c3_1_0.php";

if (isset($_GET['complete']) && $_GET['complete'] == '5') {
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
                <li><a href="#correspondence" class="toc-link">3.0.5 Machines &amp; Languages</a></li>
                <li><a href="#sp-e" class="toc-link sub">↳ Sample Problem (e)</a></li>
                <li><a href="#pdm-sim" class="toc-link">Interactive PDM Simulator</a></li>
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
            <!-- 3.0.5 Correspondence                          -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="correspondence">
                <h2><span class="sec-icon">🔗</span> 3.0.5 Correspondence Between Machines &amp; Languages</h2>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Machine</th>
                            <th>Equivalent grammar class</th>
                            <th>Also equivalent to</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Finite State Machine</td>
                            <td>Right Linear (Type 3)</td>
                            <td>Regular expressions</td>
                        </tr>
                        <tr>
                            <td>Deterministic Pushdown Machine</td>
                            <td>Deterministic context-free languages (subset of Type 2)</td>
                            <td>—</td>
                        </tr>
                        <tr>
                            <td>Nondeterministic Pushdown Machine</td>
                            <td>All context-free languages (Type 2)</td>
                            <td>—</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Important limitation:</strong> There are context-free languages that <em>cannot</em> be
                        accepted by a <em>deterministic</em> pushdown machine. The language of palindromes
                        <strong>without a centermarker</strong> (P) is one example — the machine can never know when to
                        switch from pushing to popping. With a centermarker <strong>c</strong> (language Pc), a
                        deterministic PDM is sufficient. <strong>Nondeterministic</strong> pushdown machines can accept
                        all context-free languages, but are beyond the scope of this course.
                    </div>
                </div>
            </section>

            <!-- Sample Problem 3.0(e) -->
            <section id="sp-e">
                <h2><span class="sec-icon">🧪</span> Sample Problem 3.0(e) — Right Linear Grammars</h2>
                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 3.0 (e) — Right linear grammars for Sample Problem 2.0(a) languages</h4>
                    </div>
                    <div class="sp-body">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                            <div>
                                <div class="sp-label">(1) Strings with odd number of 0s</div>
                                <div class="grammar-box" style="margin:0;">
                                    <div class="g-title">Right Linear Grammar</div>
                                    <div class="g-rule"><span class="g-num">1.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">0</span></div>
                                    <div class="g-rule"><span class="g-num">2.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">1</span><span class="g-nt">S</span></div>
                                    <div class="g-rule"><span class="g-num">3.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">0</span><span class="g-nt">A</span></div>
                                    <div class="g-rule"><span class="g-num">4.</span><span class="g-nt">A</span><span style="color:#6C7086;"> → </span><span class="g-t">1</span></div>
                                    <div class="g-rule"><span class="g-num">5.</span><span class="g-nt">A</span><span style="color:#6C7086;"> → </span><span class="g-t">1</span><span class="g-nt">A</span></div>
                                    <div class="g-rule"><span class="g-num">6.</span><span class="g-nt">A</span><span style="color:#6C7086;"> → </span><span class="g-t">0</span><span class="g-nt">S</span></div>
                                </div>
                            </div>
                            <div>
                                <div class="sp-label">(2) Strings with three consecutive 1s</div>
                                <div class="grammar-box" style="margin:0;">
                                    <div class="g-title">Right Linear Grammar</div>
                                    <div class="g-rule"><span class="g-num">1.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">1</span><span class="g-nt">S</span></div>
                                    <div class="g-rule"><span class="g-num">2.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">0</span><span class="g-nt">S</span></div>
                                    <div class="g-rule"><span class="g-num">3.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">1</span><span class="g-nt">A</span></div>
                                    <div class="g-rule"><span class="g-num">4.</span><span class="g-nt">A</span><span style="color:#6C7086;"> → </span><span class="g-t">1</span><span class="g-nt">B</span></div>
                                    <div class="g-rule"><span class="g-num">5.</span><span class="g-nt">B</span><span style="color:#6C7086;"> → </span><span class="g-t">1</span><span class="g-nt">C</span></div>
                                    <div class="g-rule"><span class="g-num">6.</span><span class="g-nt">B</span><span style="color:#6C7086;"> → </span><span class="g-t">1</span></div>
                                    <div class="g-rule"><span class="g-num">7–10.</span><span class="g-nt">C</span><span style="color:#6C7086;"> → </span><span style="color:#CDD6F4;">any input</span><span class="g-nt">C</span> / accept</div>
                                </div>
                            </div>
                        </div>
                        <div class="note-box tip" style="margin-top:14px;">
                            <span class="box-icon">💡</span>
                            <div>Right linear grammars have each rule of the form <code>A → aB</code> or <code>A → a</code>. They generate exactly the same languages as <em>finite state machines</em> and <em>regular expressions</em>. These grammars are used for lexical token definitions.</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Interactive PDM Simulator                     -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="pdm-sim">
                <h2><span class="sec-icon">▶️</span> Interactive PDM Simulator</h2>
                <p>Select a machine and enter an input string. Step through or run the PDM and watch the stack change in real time.</p>

                <div class="pdm-sim">
                    <div class="pdm-sim-header">
                        <h4>🗂️ Pushdown Machine Simulator</h4>
                        <span id="pdm-status-label" style="font-size:12px;color:rgba(255,255,255,.7);">Ready</span>
                    </div>
                    <div class="pdm-sim-body">
                        <div class="pdm-ctrl">
                            <select class="pdm-sel" id="pdm-machine-sel" onchange="pdmReset()">
                                <option value="anbn">Fig 3.4 — aⁿbⁿ</option>
                                <option value="paren">Fig 3.6 — Balanced parentheses</option>
                            </select>
                            <input class="pdm-inp" id="pdm-input" type="text" placeholder="e.g. aabb" maxlength="16" oninput="pdmReset()">
                            <button class="pdm-btn" onclick="pdmRun()">▶ Run</button>
                            <button class="pdm-btn sec" onclick="pdmStep()" id="pdm-step-btn">Step →</button>
                            <button class="pdm-btn sec" onclick="pdmReset()">↺ Reset</button>
                        </div>
                        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;">
                            <span style="font-size:12px;color:var(--muted);">Examples:</span>
                            <button onclick="pdmEx('anbn','aabb')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(99,102,241,.3);background:transparent;color:var(--purple);cursor:pointer;">aabb ✓</button>
                            <button onclick="pdmEx('anbn','aaabbb')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(99,102,241,.3);background:transparent;color:var(--purple);cursor:pointer;">aaabbb ✓</button>
                            <button onclick="pdmEx('anbn','aab')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(239,68,68,.3);background:transparent;color:var(--red);cursor:pointer;">aab ✗</button>
                            <button onclick="pdmEx('paren','(())')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(10,185,129,.3);background:transparent;color:var(--green);cursor:pointer;">(()) ✓</button>
                            <button onclick="pdmEx('paren','(()')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(239,68,68,.3);background:transparent;color:var(--red);cursor:pointer;">(() ✗</button>
                        </div>

                        <div class="pdm-tape" id="pdm-tape"></div>

                        <div style="display:flex;gap:16px;flex-wrap:wrap;margin-top:10px;">
                            <div>
                                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--purple);margin-bottom:6px;">Stack (top at top):</div>
                                <div class="pdm-stack-vis" id="pdm-stack" style="min-height:60px;"></div>
                            </div>
                            <div style="flex:1;">
                                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--purple);margin-bottom:6px;">State: <span id="pdm-cur-state" style="color:var(--amber);">—</span></div>
                                <div class="pdm-log" id="pdm-log">Select a machine and enter input to begin.</div>
                            </div>
                        </div>
                        <div class="pdm-status" id="pdm-result"></div>
                    </div>
                </div>
            </section>




            <div class="chapter-nav">
                <a href="c3_0_4.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>3.0.4 Pushdown Machines</div>
                </a>
                <a href="?complete=5" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>3.1 Ambiguities in Programming Languages</div>
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