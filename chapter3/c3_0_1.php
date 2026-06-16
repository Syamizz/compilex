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
$page = 1;
$nextPage = "c3_0_2.php";

if (isset($_GET['complete']) && $_GET['complete'] == '1') {
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
    <title>Chapter 3.0 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c3/c3_0.css">
</head>

<body>

    <?php include 'navbar_c3.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#grammars" class="toc-link">3.0.1 Grammars</a></li>
                <li><a href="#g1" class="toc-link sub">↳ Grammar G1 — Palindromes</a></li>
                <li><a href="#g2" class="toc-link sub">↳ Grammar G2 — aⁿbⁿ</a></li>
                <li><a href="#sp-a" class="toc-link sub">↳ Sample Problem (a)</a></li>
                <li><a href="#deriv-sim" class="toc-link sub">↳ Derivation Stepper</a></li>
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
                <div class="chapter-tag">📘 Chapter 3 · Section 3.0</div>
                <h1>Grammars, Languages &amp; Grammar Classes</h1>
                <div class="metadata">
                    <span>⏱ 20 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>📐 Formal Languages</span>
                </div>
            </header>



            <!-- ══════════════════════════════════════════════ -->
            <!-- 3.0.1 Grammars                                -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="grammars">
                <h2><span class="sec-icon">📐</span> 3.0.1 Grammars</h2>
                <p>
                    A <strong>grammar</strong> is a list of rules that can generate all strings of a language
                    and nothing else. Formally, a grammar consists of four components:
                </p>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin:18px 0;">
                    <div style="background:var(--card);border:1.5px solid rgba(99,102,241,.15);border-radius:var(--radius);padding:16px;box-shadow:var(--shadow);">
                        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--purple);margin-bottom:6px;">1. Terminal symbols</div>
                        <div style="font-size:13px;color:var(--muted);">The input alphabet — the actual characters that appear in strings of the language. Written in <span style="color:var(--green);font-family:'JetBrains Mono',monospace;">lowercase</span>.</div>
                    </div>
                    <div style="background:var(--card);border:1.5px solid rgba(99,102,241,.15);border-radius:var(--radius);padding:16px;box-shadow:var(--shadow);">
                        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--purple);margin-bottom:6px;">2. Nonterminal symbols</div>
                        <div style="font-size:13px;color:var(--muted);">Intermediate symbols used in rules but never appearing in final strings. Written in <span style="color:#CBA6F7;font-family:'JetBrains Mono',monospace;">UPPERCASE</span>. One is the starting nonterminal.</div>
                    </div>
                    <div style="background:var(--card);border:1.5px solid rgba(99,102,241,.15);border-radius:var(--radius);padding:16px;box-shadow:var(--shadow);">
                        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--purple);margin-bottom:6px;">3. Productions (rewriting rules)</div>
                        <div style="font-size:13px;color:var(--muted);">Rules of the form <code>α → β</code>, where α and β are strings of terminals/nonterminals and α is not null. Applied repeatedly to generate strings.</div>
                    </div>
                    <div style="background:var(--card);border:1.5px solid rgba(99,102,241,.15);border-radius:var(--radius);padding:16px;box-shadow:var(--shadow);">
                        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--purple);margin-bottom:6px;">4. Starting nonterminal</div>
                        <div style="font-size:13px;color:var(--muted);">Always <code>S</code> unless otherwise specified. Every derivation begins here. If a sentential form contains only terminals, it is in the language.</div>
                    </div>
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Convention used throughout Chapter 3:</strong>
                        Lowercase letters (<code>a,b,c,…</code>) = terminal symbols.
                        Uppercase letters (<code>A,B,C,…</code>) = single nonterminals.
                        <code>α,β,γ</code> = strings of terminals and/or nonterminals.
                        <code>x,y,z</code> = strings of terminals only.
                        <code>S</code> is always the starting nonterminal.
                    </div>
                </div>

                <p>
                    A <strong>derivation</strong> is a sequence of rule applications, starting from S, ending in a
                    string of terminals. It proves that a particular string is in the language:
                </p>
                <div style="background:var(--code-bg);border-radius:var(--radius);padding:14px 22px;margin:14px 0;font-family:'JetBrains Mono',monospace;font-size:14px;color:#CDD6F4;text-align:center;">
                    S ⇒ α ⇒ β ⇒ γ ⇒ … ⇒ x
                </div>
            </section>

            <!-- Grammar G1 -->
            <section id="g1">
                <h2><span class="sec-icon">🔢</span> Grammar G1 — Palindromes over {0, 1}</h2>

                <div class="grammar-box">
                    <div class="g-title">G1 — Terminal symbols: {0, 1} · Starting nonterminal: S</div>
                    <div class="g-rule"><span class="g-num">1.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">0</span><span class="nt">S</span><span class="t">0</span></span></div>
                    <div class="g-rule"><span class="g-num">2.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">1</span><span class="nt">S</span><span class="t">1</span></span></div>
                    <div class="g-rule"><span class="g-num">3.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">0</span></span></div>
                    <div class="g-rule"><span class="g-num">4.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">1</span></span></div>
                </div>

                <p><strong>Example derivation</strong> showing that <code>0010100</code> ∈ L(G1):</p>
                <div style="background:var(--code-bg);border-radius:var(--radius);padding:16px 22px;margin:14px 0;font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;line-height:2.2;">
                    <span style="color:#CBA6F7;">S</span> ⇒ <span style="color:#A6E3A1;">0</span><span style="color:#CBA6F7;">S</span><span style="color:#A6E3A1;">0</span> ⇒ <span style="color:#A6E3A1;">0</span><span style="color:#A6E3A1;">0</span><span style="color:#CBA6F7;">S</span><span style="color:#A6E3A1;">00</span> ⇒ <span style="color:#A6E3A1;">00</span><span style="color:#A6E3A1;">1</span><span style="color:#CBA6F7;">S</span><span style="color:#A6E3A1;">1</span><span style="color:#A6E3A1;">00</span> ⇒ <span style="color:#A6E3A1;">0010100</span>
                    <div style="font-size:11px;color:#6C7086;margin-top:4px;">rules applied: 1 → 1 → 2 → 4</div>
                </div>

                <div class="note-box key">
                    <span class="box-icon">✅</span>
                    <div>
                        L(G1) = {0, 1, 000, 010, 101, 111, 00000, …} — all <strong>odd-length palindromes</strong>
                        over {0,1}. A palindrome reads the same forwards and backwards.
                    </div>
                </div>
            </section>

            <!-- Grammar G2 -->
            <section id="g2">
                <h2><span class="sec-icon">🅰️</span> Grammar G2 — aⁿbⁿ (equal a's then b's)</h2>

                <div class="grammar-box">
                    <div class="g-title">G2 — Terminal symbols: {a, b} · Starting nonterminal: S</div>
                    <div class="g-rule"><span class="g-num">1.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="nt">A</span><span class="nt">S</span><span class="nt">B</span></span></div>
                    <div class="g-rule"><span class="g-num">2.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="ep">ε</span></span></div>
                    <div class="g-rule"><span class="g-num">3.</span><span class="g-lhs">A</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">a</span></span></div>
                    <div class="g-rule"><span class="g-num">4.</span><span class="g-lhs">B</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">b</span></span></div>
                </div>

                <p><strong>Example derivation</strong> showing that <code>aabb</code> ∈ L(G2):</p>
                <div style="background:var(--code-bg);border-radius:var(--radius);padding:16px 22px;margin:14px 0;font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;line-height:2.2;">
                    <span style="color:#CBA6F7;">S</span> ⇒ <span style="color:#CBA6F7;">ASB</span> ⇒ <span style="color:#CBA6F7;">A</span><span style="color:#CBA6F7;">ASB</span><span style="color:#CBA6F7;">B</span> ⇒ <span style="color:#CBA6F7;">A</span><span style="color:#CBA6F7;">A</span><span style="color:#F38BA8;">ε</span><span style="color:#CBA6F7;">B</span><span style="color:#CBA6F7;">B</span> ⇒ <span style="color:#CBA6F7;">A</span><span style="color:#CBA6F7;">A</span><span style="color:#CBA6F7;">B</span><span style="color:#CBA6F7;">B</span> ⇒ <span style="color:#CBA6F7;">A</span><span style="color:#CBA6F7;">A</span><span style="color:#CBA6F7;">B</span><span style="color:#A6E3A1;">b</span> ⇒ <span style="color:#CBA6F7;">A</span><span style="color:#CBA6F7;">A</span><span style="color:#A6E3A1;">b</span><span style="color:#A6E3A1;">b</span> ⇒ <span style="color:#CBA6F7;">A</span><span style="color:#A6E3A1;">a</span><span style="color:#A6E3A1;">bb</span> ⇒ <span style="color:#A6E3A1;">aabb</span>
                </div>

                <div class="note-box key">
                    <span class="box-icon">✅</span>
                    <div>
                        L(G2) = {ε, ab, aabb, aaabbb, …} = {aⁿbⁿ | n ≥ 0} — all strings of zero or more a's
                        followed by <em>exactly the same number</em> of b's.
                        <br><strong>Note:</strong> Multiple different derivations can produce the same string — order
                        of rule application may vary.
                    </div>
                </div>
            </section>

            <!-- Sample Problem 3.0(a) -->
            <section id="sp-a">
                <h2><span class="sec-icon">🧪</span> Sample Problem 3.0(a)</h2>
                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 3.0 (a)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">Show three different derivations using the grammar below (grammar rules 1–4):</p>
                        <div class="grammar-box" style="margin-bottom:16px;">
                            <div class="g-rule"><span class="g-num">1.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">a</span><span class="nt">S</span><span class="nt">A</span></span></div>
                            <div class="g-rule"><span class="g-num">2.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="nt">B</span><span class="nt">A</span></span></div>
                            <div class="g-rule"><span class="g-num">3.</span><span class="g-lhs">A</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">a</span><span class="t">b</span></span></div>
                            <div class="g-rule"><span class="g-num">4.</span><span class="g-lhs">B</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">b</span><span class="nt">A</span></span></div>
                        </div>

                        <div class="sp-label">✅ Derivation 1</div>
                        <div style="background:var(--code-bg);border-radius:10px;padding:12px 18px;font-family:'JetBrains Mono',monospace;font-size:12.5px;color:#CDD6F4;line-height:2;margin-bottom:10px;">
                            S ⇒ <span style="color:#CBA6F7;">a</span>SA ⇒ a<span style="color:#CBA6F7;">BA</span>A ⇒ a<span style="color:#CBA6F7;">B</span><span style="color:#A6E3A1;">ab</span>A ⇒ a<span style="color:#CBA6F7;">B</span>ab<span style="color:#A6E3A1;">ab</span> ⇒ a<span style="color:#A6E3A1;">b</span><span style="color:#CBA6F7;">A</span>abab ⇒ ab<span style="color:#A6E3A1;">ab</span>abab = <span style="color:#6EE7B7; font-weight:700;">abababab</span>
                        </div>

                        <div class="sp-label">✅ Derivation 2</div>
                        <div style="background:var(--code-bg);border-radius:10px;padding:12px 18px;font-family:'JetBrains Mono',monospace;font-size:12.5px;color:#CDD6F4;line-height:2;margin-bottom:10px;">
                            S ⇒ aSA ⇒ aS<span style="color:#A6E3A1;">ab</span> ⇒ a<span style="color:#CBA6F7;">BA</span>ab ⇒ a<span style="color:#A6E3A1;">b</span><span style="color:#CBA6F7;">A</span>Aab ⇒ ab<span style="color:#A6E3A1;">ab</span>Aab ⇒ abab<span style="color:#A6E3A1;">ab</span>ab = <span style="color:#6EE7B7; font-weight:700;">abababab</span>
                        </div>

                        <div class="sp-label">✅ Derivation 3</div>
                        <div style="background:var(--code-bg);border-radius:10px;padding:12px 18px;font-family:'JetBrains Mono',monospace;font-size:12.5px;color:#CDD6F4;line-height:2;">
                            S ⇒ <span style="color:#CBA6F7;">BA</span> ⇒ <span style="color:#A6E3A1;">b</span><span style="color:#CBA6F7;">A</span>A ⇒ b<span style="color:#A6E3A1;">ab</span>A ⇒ bab<span style="color:#A6E3A1;">ab</span> = <span style="color:#6EE7B7; font-weight:700;">babab</span>
                        </div>

                        <div class="note-box tip" style="margin-top:14px;">
                            <span class="box-icon">💡</span>
                            <div>Multiple different derivations can produce the <strong>same string</strong> (abababab appears in both Derivation 1 and 2). Two grammars are <strong>equivalent</strong> if they produce exactly the same language L(G).</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Derivation Stepper -->
            <section id="deriv-sim">
                <h2><span class="sec-icon">▶️</span> Interactive Derivation Stepper</h2>
                <p>Choose a grammar and a preset derivation. Click <strong>Step</strong> to apply rules one by one and watch the sentential form evolve.</p>

                <div class="deriv-wrap">
                    <div class="deriv-header">
                        <h4>📐 Derivation Stepper</h4>
                        <span id="deriv-progress" style="font-size:12px;color:rgba(255,255,255,.7);">Step 0</span>
                    </div>
                    <div class="deriv-body">
                        <div class="deriv-select-row">
                            <select class="deriv-sel" id="deriv-grammar-sel" onchange="loadDerivation()">
                                <option value="g1a">G1 — derive 0010100</option>
                                <option value="g1b">G1 — derive 10101</option>
                                <option value="g2a">G2 — derive aabb</option>
                                <option value="g2b">G2 — derive aaabbb</option>
                            </select>
                            <button class="deriv-btn" onclick="derivStep()">Step →</button>
                            <button class="deriv-btn sec" onclick="derivAll()">Run All ⚡</button>
                            <button class="deriv-btn sec" onclick="loadDerivation()">↺ Reset</button>
                        </div>

                        <div class="deriv-steps" id="deriv-steps"></div>
                        <div id="deriv-result" class="deriv-result" style="display:none;"></div>
                    </div>
                </div>
            </section>

 


            <div class="chapter-nav">
                <a href="../home.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Back to</span>Home</div>
                </a>
                <a href="?complete=1" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>3.0.2 Classes of Grammars</div>
                    <span>→</span>
                </a>
            </div>

        </article>
    </div>

    <script>
        // ── TOC ──────────────────────────────────────────────────
        const sections = document.querySelectorAll('section[id]');
        const tocLinks = document.querySelectorAll('.toc-link');
        const bar = document.getElementById('toc-bar');
        const pctLabel = document.getElementById('pct-label');
        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            const total = document.body.scrollHeight - window.innerHeight;
            bar.style.width = Math.round(scrolled / total * 100) + '%';
            pctLabel.textContent = Math.round(scrolled / total * 100) + '%';
            let cur = '';
            sections.forEach(s => {
                if (scrolled >= s.offsetTop - 120) cur = s.id;
            });
            tocLinks.forEach(l => l.classList.toggle('active', l.getAttribute('href') === '#' + cur));
        });

        // ════════════════════════════════════════════════════════
        // DERIVATION STEPPER
        // ════════════════════════════════════════════════════════
        const DERIVATIONS = {
            g1a: {
                title: 'G1 — Deriving 0010100',
                steps: [{
                        form: 'S',
                        rule: '(start)',
                        type: 'start'
                    },
                    {
                        form: '0S0',
                        rule: 'Rule 1: S→0S0',
                        type: 'middle'
                    },
                    {
                        form: '00S00',
                        rule: 'Rule 1: S→0S0',
                        type: 'middle'
                    },
                    {
                        form: '001S100',
                        rule: 'Rule 2: S→1S1',
                        type: 'middle'
                    },
                    {
                        form: '0010100',
                        rule: 'Rule 3: S→0',
                        type: 'final'
                    },
                ]
            },
            g1b: {
                title: 'G1 — Deriving 10101',
                steps: [{
                        form: 'S',
                        rule: '(start)',
                        type: 'start'
                    },
                    {
                        form: '1S1',
                        rule: 'Rule 2: S→1S1',
                        type: 'middle'
                    },
                    {
                        form: '10S01',
                        rule: 'Rule 1: S→0S0',
                        type: 'middle'
                    },
                    {
                        form: '10101',
                        rule: 'Rule 4: S→1',
                        type: 'final'
                    },
                ]
            },
            g2a: {
                title: 'G2 — Deriving aabb',
                steps: [{
                        form: 'S',
                        rule: '(start)',
                        type: 'start'
                    },
                    {
                        form: 'ASB',
                        rule: 'Rule 1: S→ASB',
                        type: 'middle'
                    },
                    {
                        form: 'AASBB',
                        rule: 'Rule 1: S→ASB',
                        type: 'middle'
                    },
                    {
                        form: 'AA\u03b5BB',
                        rule: 'Rule 2: S→ε',
                        type: 'middle'
                    },
                    {
                        form: 'AABB',
                        rule: '(ε removed)',
                        type: 'middle'
                    },
                    {
                        form: 'AABb',
                        rule: 'Rule 4: B→b',
                        type: 'middle'
                    },
                    {
                        form: 'AAbb',
                        rule: 'Rule 4: B→b',
                        type: 'middle'
                    },
                    {
                        form: 'Aabb',
                        rule: 'Rule 3: A→a',
                        type: 'middle'
                    },
                    {
                        form: 'aabb',
                        rule: 'Rule 3: A→a',
                        type: 'final'
                    },
                ]
            },
            g2b: {
                title: 'G2 — Deriving aaabbb',
                steps: [{
                        form: 'S',
                        rule: '(start)',
                        type: 'start'
                    },
                    {
                        form: 'ASB',
                        rule: 'Rule 1: S→ASB',
                        type: 'middle'
                    },
                    {
                        form: 'AASBB',
                        rule: 'Rule 1: S→ASB',
                        type: 'middle'
                    },
                    {
                        form: 'AAASBB B',
                        rule: 'Rule 1: S→ASB',
                        type: 'middle'
                    },
                    {
                        form: 'AAAε BBB',
                        rule: 'Rule 2: S→ε',
                        type: 'middle'
                    },
                    {
                        form: 'AAABBB',
                        rule: '(ε removed)',
                        type: 'middle'
                    },
                    {
                        form: 'AAABBb',
                        rule: 'Rule 4: B→b',
                        type: 'middle'
                    },
                    {
                        form: 'AAABbb',
                        rule: 'Rule 4: B→b',
                        type: 'middle'
                    },
                    {
                        form: 'AAAbb b',
                        rule: 'Rule 4: B→b',
                        type: 'middle'
                    },
                    {
                        form: 'AAAbbb',
                        rule: '—',
                        type: 'middle'
                    },
                    {
                        form: 'AAabbb',
                        rule: 'Rule 3: A→a',
                        type: 'middle'
                    },
                    {
                        form: 'Aaabbb',
                        rule: 'Rule 3: A→a',
                        type: 'middle'
                    },
                    {
                        form: 'aaabbb',
                        rule: 'Rule 3: A→a',
                        type: 'final'
                    },
                ]
            }
        };

        let derivCurrent = null;
        let derivIdx = 0;

        function loadDerivation() {
            const key = document.getElementById('deriv-grammar-sel').value;
            derivCurrent = DERIVATIONS[key];
            derivIdx = 0;
            const container = document.getElementById('deriv-steps');
            container.innerHTML = '';
            document.getElementById('deriv-result').style.display = 'none';
            document.getElementById('deriv-progress').textContent = 'Step 0 of ' + (derivCurrent.steps.length - 1);
            // show first step immediately
            appendDerivStep(0);
        }

        function appendDerivStep(i) {
            const s = derivCurrent.steps[i];
            const el = document.createElement('div');
            el.className = 'deriv-step ' + s.type;
            el.innerHTML = `<span class="ds-label">${i===0?'START':i===derivCurrent.steps.length-1?'FINAL':'Step '+i}</span>
        <span class="ds-str">${formatForm(s.form)}</span>
        <span class="ds-rule">${s.rule}</span>`;
            document.getElementById('deriv-steps').appendChild(el);
            requestAnimationFrame(() => el.classList.add('visible'));
        }

        function formatForm(f) {
            return f.split('').map(c => {
                if (c === c.toUpperCase() && /[A-Z]/.test(c)) return `<span class="nt">${c}</span>`;
                if (/[a-z0-9]/.test(c)) return `<span class="t">${c}</span>`;
                if (c === 'ε' || c === '\u03b5') return `<span class="ep">ε</span>`;
                return c;
            }).join('');
        }

        function derivStep() {
            if (!derivCurrent) {
                loadDerivation();
                return;
            }
            derivIdx++;
            if (derivIdx >= derivCurrent.steps.length) {
                showDerivResult();
                return;
            }
            appendDerivStep(derivIdx);
            document.getElementById('deriv-progress').textContent = `Step ${derivIdx} of ${derivCurrent.steps.length-1}`;
            if (derivIdx === derivCurrent.steps.length - 1) showDerivResult();
        }

        function derivAll() {
            if (!derivCurrent) loadDerivation();
            while (derivIdx < derivCurrent.steps.length - 1) {
                derivIdx++;
                appendDerivStep(derivIdx);
            }
            document.getElementById('deriv-progress').textContent = `Step ${derivIdx} of ${derivCurrent.steps.length-1}`;
            showDerivResult();
        }

        function showDerivResult() {
            const final = derivCurrent.steps[derivCurrent.steps.length - 1].form;
            const res = document.getElementById('deriv-result');
            res.style.display = 'block';
            res.style.background = '#F0FDF4';
            res.style.color = '#065F46';
            res.style.border = '1.5px solid #10B981';
            res.innerHTML = `✅ <strong style="font-family:'JetBrains Mono',monospace">"${final}"</strong> has been derived — it is a member of the language!`;
        }

        // init
        loadDerivation();

        
    </script>

</body>

</html>