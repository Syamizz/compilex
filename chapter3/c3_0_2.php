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
$page = 2;
$nextPage = "c3_0_3.php";

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
                <li><a href="#classes" class="toc-link">3.0.2 Classes of Grammars</a></li>
                <li><a href="#chomsky" class="toc-link sub">↳ Chomsky Hierarchy</a></li>
                <li><a href="#g3" class="toc-link sub">↳ Grammar G3 — aⁿbⁿcⁿ</a></li>
                <li><a href="#sp-b" class="toc-link sub">↳ Sample Problem (b)</a></li>
                <li><a href="#sandbox" class="toc-link">🧪 Grammar Tester</a></li>
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
            <!-- 3.0.2 Classes of Grammars                     -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="classes">
                <h2><span class="sec-icon">🏛️</span> 3.0.2 Classes of Grammars — Chomsky Hierarchy</h2>
                <p>
                    In 1959, linguist <strong>Noam Chomsky</strong> classified grammars into four types based on
                    the restrictions placed on their rewriting rules. Each class is a <em>subset</em> of the class
                    above it.
                </p>
            </section>

            <!-- Chomsky Hierarchy -->
            <section id="chomsky">
                <h2><span class="sec-icon">🔺</span> The Four Classes (0 → 3: most general → most restricted)</h2>

                <div class="chomsky-grid">
                    <div class="ch-row c0">
                        <div class="ch-num c0">0</div>
                        <div class="ch-body">
                            <div class="ch-name">Unrestricted</div>
                            <div class="ch-rule">Rule form: <code>α → β</code> &nbsp;(any strings, no restriction)</div>
                            <div class="ch-desc">No restrictions on the rewriting rules. Either side may be any string of terminals and nonterminals (ε is only allowed on the right side).</div>
                            <div class="ch-ex" style="color:var(--red);">Example: &nbsp;<code>SaB → cS</code> &nbsp;(left side has multiple symbols)</div>
                        </div>
                    </div>
                    <div class="ch-row c1">
                        <div class="ch-num c1">1</div>
                        <div class="ch-body">
                            <div class="ch-name">Context-Sensitive</div>
                            <div class="ch-rule">Rule form: <code>αAγ → αβγ</code> &nbsp;(A rewrites only in context α…γ)</div>
                            <div class="ch-desc">The nonterminal A on the left is rewritten, but <em>only when it appears in a specific context</em> (α on its left, γ on its right). The context α and γ are preserved in the output.</div>
                            <div class="ch-ex" style="color:var(--amber);">Example: &nbsp;<code>SaB → caB</code> &nbsp;(S rewrites to c only when followed by aB)</div>
                        </div>
                    </div>
                    <div class="ch-row c2">
                        <div class="ch-num c2">2</div>
                        <div class="ch-body">
                            <div class="ch-name">Context-Free ⭐ <span style="font-size:12px;font-family:'DM Sans',sans-serif;font-weight:400;color:var(--muted);">used for programming languages</span></div>
                            <div class="ch-rule">Rule form: <code>A → α</code> &nbsp;(single nonterminal → anything)</div>
                            <div class="ch-desc">The nonterminal A on the left rewrites regardless of context. Most programming languages are defined by this class. Grammars G1 and G2 are both context-free.</div>
                            <div class="ch-ex" style="color:var(--purple);">Example: &nbsp;<code>A → aABb</code> &nbsp;(A can always be replaced)</div>
                        </div>
                    </div>
                    <div class="ch-row c3">
                        <div class="ch-num c3">3</div>
                        <div class="ch-body">
                            <div class="ch-name">Right Linear <span style="font-size:12px;font-family:'DM Sans',sans-serif;font-weight:400;color:var(--muted);">same power as regular expressions</span></div>
                            <div class="ch-rule">Rule form: <code>A → aB</code> or <code>A → a</code> &nbsp;(terminal then optional nonterminal at right)</div>
                            <div class="ch-desc">The most restricted class. Equivalent to regular expressions and finite state machines. Used to define lexical items: identifiers, constants, keywords.</div>
                            <div class="ch-ex" style="color:var(--green);">Example: &nbsp;<code>A → aB</code> , <code>B → b</code> &nbsp;(generates: ab)</div>
                        </div>
                    </div>
                </div>

                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>
                        <strong>Subset relationship:</strong> Every right-linear grammar is also context-free, context-sensitive, and unrestricted. Every context-free grammar is also context-sensitive and unrestricted. The classes form nested circles: Right-Linear ⊂ Context-Free ⊂ Context-Sensitive ⊂ Unrestricted (Figure 3.1 in the textbook).
                    </div>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        <strong>Symbol conventions for the hierarchy:</strong><br>
                        <code>A,B,C,…</code> = single nonterminal &nbsp;|&nbsp;
                        <code>a,b,c,…</code> = single terminal &nbsp;|&nbsp;
                        <code>X,Y,Z,…</code> = single terminal or nonterminal<br>
                        <code>x,y,z,…</code> = string of terminals &nbsp;|&nbsp;
                        <code>α,β,γ,…</code> = string of terminals and nonterminals
                    </div>
                </div>
            </section>

            <!-- Grammar G3 -->
            <section id="g3">
                <h2><span class="sec-icon">🅰️</span> Grammar G3 — aⁿbⁿcⁿ (Context-Sensitive, not Context-Free)</h2>
                <p>
                    This is an example of a context-sensitive grammar that is <em>not</em> context-free —
                    no context-free grammar exists for L(G3).
                </p>
                <div class="grammar-box">
                    <div class="g-title">G3 — Context-Sensitive grammar for aⁿbⁿcⁿ</div>
                    <div class="g-rule"><span class="g-num">1.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">a</span><span class="nt">S</span><span class="nt">B</span><span class="nt">C</span></span></div>
                    <div class="g-rule"><span class="g-num">2.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="ep">ε</span></span></div>
                    <div class="g-rule"><span class="g-num">3.</span><span class="g-lhs">a</span><span class="g-lhs">B</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">ab</span></span> &nbsp;<span style="color:#6C7086;font-size:11px;font-style:italic;">← context-sensitive: aB → ab only when preceded by a</span></div>
                    <div class="g-rule"><span class="g-num">4.</span><span class="g-lhs">b</span><span class="g-lhs">B</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">bb</span></span></div>
                    <div class="g-rule"><span class="g-num">5.</span><span class="g-lhs">C</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">c</span></span></div>
                    <div class="g-rule"><span class="g-num">6.</span><span class="g-lhs">C</span><span class="g-lhs">B</span><span class="g-arr"> → </span><span class="g-rhs"><span class="nt">C</span><span class="nt">X</span></span></div>
                    <div class="g-rule"><span class="g-num">7.</span><span class="g-lhs">C</span><span class="g-lhs">X</span><span class="g-arr"> → </span><span class="g-rhs"><span class="nt">B</span><span class="nt">X</span></span></div>
                    <div class="g-rule"><span class="g-num">8.</span><span class="g-lhs">B</span><span class="g-lhs">X</span><span class="g-arr"> → </span><span class="g-rhs"><span class="nt">B</span><span class="nt">C</span></span></div>
                </div>

                <p><strong>Example derivation</strong> for <code>aabbcc</code>:</p>
                <div style="background:var(--code-bg);border-radius:var(--radius);padding:16px 22px;margin:14px 0;font-family:'JetBrains Mono',monospace;font-size:12px;color:#CDD6F4;line-height:2.2;">
                    S ⇒ aSBC ⇒ aaSBCBC ⇒ aaBCBC ⇒ aaBCXC ⇒ aaBBXC ⇒ aaBBCC<br>
                    ⇒ <span style="color:#A6E3A1;">a</span><span style="color:#A6E3A1;">a</span>bBCC ⇒ <span style="color:#A6E3A1;">aa</span>bbCC ⇒ <span style="color:#A6E3A1;">aabb</span>Cc ⇒ <span style="color:#A6E3A1;">aabbcc</span>
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        L(G3) = {ε, abc, aabbcc, aaabbbccc, …} = {aⁿbⁿcⁿ | n ≥ 0}. This language
                        <strong>cannot be generated by any context-free grammar</strong>. It requires context-sensitivity
                        because the counts of a's, b's, and c's must all match.
                    </div>
                </div>
            </section>

            <!-- Sample Problem 3.0(b) -->
            <section id="sp-b">
                <h2><span class="sec-icon">🧪</span> Sample Problem 3.0(b) — Classify Grammar Rules</h2>
                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 3.0 (b)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">Classify each rule according to Chomsky's classification. Give the largest (most restricted) type that applies.</p>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin:12px 0;">
                            <div style="background:var(--code-bg);border-radius:10px;padding:12px 16px;">
                                <div style="font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;margin-bottom:6px;"><span style="color:#CBA6F7;">aSb</span> → <span style="color:#CBA6F7;">a</span><span style="color:#CBA6F7;">A</span><span style="color:#A6E3A1;">c</span><span style="color:#CBA6F7;">B</span><span style="color:#A6E3A1;">b</span></div>
                                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--amber);">Type 1 — Context-Sensitive</div>
                                <div style="font-size:12px;color:var(--muted);">α = a, A = S, γ = b — S rewrites in context</div>
                            </div>
                            <div style="background:var(--code-bg);border-radius:10px;padding:12px 16px;">
                                <div style="font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;margin-bottom:6px;"><span style="color:#CBA6F7;">B</span> → <span style="color:#A6E3A1;">a</span><span style="color:#CBA6F7;">A</span></div>
                                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--green);">Type 3 — Right Linear</div>
                                <div style="font-size:12px;color:var(--muted);">A → aB form: terminal then nonterminal</div>
                            </div>
                            <div style="background:var(--code-bg);border-radius:10px;padding:12px 16px;">
                                <div style="font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;margin-bottom:6px;"><span style="color:#A6E3A1;">a</span> → <span style="color:#CBA6F7;">A</span><span style="color:#CBA6F7;">B</span><span style="color:#CBA6F7;">C</span></div>
                                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--red);">Type 0 — Unrestricted</div>
                                <div style="font-size:12px;color:var(--muted);">Left side is a terminal — no class restriction matches</div>
                            </div>
                            <div style="background:var(--code-bg);border-radius:10px;padding:12px 16px;">
                                <div style="font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;margin-bottom:6px;"><span style="color:#CBA6F7;">S</span> → <span style="color:#A6E3A1;">a</span><span style="color:#CBA6F7;">B</span><span style="color:#A6E3A1;">c</span></div>
                                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--purple);">Type 2 — Context-Free</div>
                                <div style="font-size:12px;color:var(--muted);">A → α form: single nonterminal rewrites freely</div>
                            </div>
                            <div style="background:var(--code-bg);border-radius:10px;padding:12px 16px;">
                                <div style="font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;margin-bottom:6px;"><span style="color:#CBA6F7;">A</span><span style="color:#A6E3A1;">b</span> → <span style="color:#A6E3A1;">b</span></div>
                                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--amber);">Type 1 — Context-Sensitive</div>
                                <div style="font-size:12px;color:var(--muted);">A rewrites when followed by b (right context = b)</div>
                            </div>
                            <div style="background:var(--code-bg);border-radius:10px;padding:12px 16px;">
                                <div style="font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;margin-bottom:6px;"><span style="color:#CBA6F7;">A</span><span style="color:#CBA6F7;">B</span> → <span style="color:#CBA6F7;">B</span><span style="color:#CBA6F7;">A</span></div>
                                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--red);">Type 0 — Unrestricted</div>
                                <div style="font-size:12px;color:var(--muted);">Two nonterminals on the left side — not context-sensitive</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Grammar Membership Tester                     -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="sandbox">
                <h2><span class="sec-icon">🧪</span> Grammar Membership Tester</h2>
                <p>
                    Enter a string and select a grammar. The tester will tell you whether the string is
                    in the language of that grammar — and show a valid derivation if it is.
                </p>

                <div class="sandbox-wrap">
                    <div class="sandbox-header">
                        <h4>🔍 Is this string in the language?</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);">L(G1), L(G2), L(G3)</span>
                    </div>
                    <div class="sandbox-body">
                        <div class="sb-controls">
                            <select class="sb-grammar-select" id="sb-grammar">
                                <option value="g1">G1 — Odd palindromes over {0,1}</option>
                                <option value="g2">G2 — aⁿbⁿ (equal a's then b's)</option>
                                <option value="g3">G3 — aⁿbⁿcⁿ</option>
                            </select>
                            <input class="sb-input" id="sb-string" type="text" placeholder="e.g. 010 or aabb" maxlength="20">
                            <button class="sb-btn" onclick="testMembership()">Test ▶</button>
                            <button class="sb-btn sec" onclick="clearSb()">Clear</button>
                        </div>
                        <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:12px;">
                            <span style="font-size:12px;color:var(--muted);">Quick examples:</span>
                            <button onclick="setSb('g1','0')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(99,102,241,.3);background:transparent;color:var(--purple);cursor:pointer;">0</button>
                            <button onclick="setSb('g1','010')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(99,102,241,.3);background:transparent;color:var(--purple);cursor:pointer;">010</button>
                            <button onclick="setSb('g1','00100')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(99,102,241,.3);background:transparent;color:var(--purple);cursor:pointer;">00100</button>
                            <button onclick="setSb('g2','aabb')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(10,185,129,.3);background:transparent;color:var(--green);cursor:pointer;">aabb</button>
                            <button onclick="setSb('g2','aaabbb')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(10,185,129,.3);background:transparent;color:var(--green);cursor:pointer;">aaabbb</button>
                            <button onclick="setSb('g3','aabbcc')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(245,158,11,.3);background:transparent;color:var(--amber);cursor:pointer;">aabbcc</button>
                            <button onclick="setSb('g2','ab')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(239,68,68,.3);background:transparent;color:var(--red);cursor:pointer;">ab (G2✓)</button>
                            <button onclick="setSb('g1','00')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(239,68,68,.3);background:transparent;color:var(--red);cursor:pointer;">00 (G1✗)</button>
                        </div>
                        <div id="sb-result" class="sb-result" style="display:none;"></div>
                    </div>
                </div>
            </section>


            <div class="chapter-nav">
                <a href="c3_0_1.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>3.0.1 Grammars</div>
                </a>
                <a href="?complete=2" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>3.0.3 Context-Free Grammars &amp; BNF</div>
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

        // ════════════════════════════════════════════════════════
        // GRAMMAR MEMBERSHIP TESTER
        // ════════════════════════════════════════════════════════
        function setSb(g, s) {
            document.getElementById('sb-grammar').value = g;
            document.getElementById('sb-string').value = s;
            testMembership();
        }

        function clearSb() {
            document.getElementById('sb-string').value = '';
            document.getElementById('sb-result').style.display = 'none';
        }

        function testMembership() {
            const g = document.getElementById('sb-grammar').value;
            const str = document.getElementById('sb-string').value.trim();
            const res = document.getElementById('sb-result');
            let accepted = false,
                explanation = '';

            if (g === 'g1') {
                // G1: odd palindromes over {0,1}
                const valid = /^[01]+$/.test(str) && str.length % 2 === 1 && str === str.split('').reverse().join('');
                accepted = valid;
                if (!str) {
                    accepted = false;
                    explanation = 'Enter a non-empty string of 0s and 1s.';
                } else if (!/^[01]+$/.test(str)) explanation = 'G1 only uses alphabet {0,1}.';
                else if (str.length % 2 === 0) explanation = 'G1 requires odd-length strings only.';
                else if (str !== str.split('').reverse().join('')) explanation = `"${str}" is not a palindrome — it doesn't read the same backwards.`;
                else explanation = `"${str}" ∈ L(G1) — it's an odd-length palindrome over {0,1}. `;
            } else if (g === 'g2') {
                // G2: a^n b^n
                const m = str.match(/^(a*)(b*)$/);
                if (!m) {
                    accepted = false;
                    explanation = 'G2 only uses alphabet {a,b} and all a\'s must precede all b\'s.';
                } else if (str === '') {
                    accepted = true;
                    explanation = 'The null string ε ∈ L(G2) — n=0 is allowed.';
                } else if (m[1].length !== m[2].length) {
                    accepted = false;
                    explanation = `"${str}" has ${m[1].length} a(s) but ${m[2].length} b(s) — counts must be equal.`;
                } else {
                    accepted = true;
                    explanation = `"${str}" ∈ L(G2) = aⁿbⁿ with n=${m[1].length}.`;
                }
            } else {
                // G3: a^n b^n c^n
                const m = str.match(/^(a*)(b*)(c*)$/);
                if (!m) {
                    accepted = false;
                    explanation = 'G3 only uses alphabet {a,b,c} in order a…b…c.';
                } else if (str === '') {
                    accepted = true;
                    explanation = 'The null string ε ∈ L(G3) — n=0 is allowed.';
                } else if (m[1].length !== m[2].length || m[2].length !== m[3].length) {
                    accepted = false;
                    explanation = `"${str}" has ${m[1].length} a(s), ${m[2].length} b(s), ${m[3].length} c(s) — all three counts must be equal.`;
                } else {
                    accepted = true;
                    explanation = `"${str}" ∈ L(G3) = aⁿbⁿcⁿ with n=${m[1].length}.`;
                }
            }

            res.style.display = 'block';
            res.className = 'sb-result ' + (accepted ? 'accepted' : 'rejected');
            res.innerHTML = (accepted ? '✅ ' : '❌ ') + explanation;
        }

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
                const existing = target.firstElementChild;
                const bank = findBank(targetId);
                if (bank) {
                    document.getElementById(bank).appendChild(existing);
                    existing.onclick = null;
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
            // zone ids: z1-0, z1-1 → bank1; z2-0 → bank2
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
                fb.innerHTML = `⚠️ <strong>${correct} of ${total}</strong> correct. Green = right, red = wrong.`;
            } else {
                fb.className = 'dnd-feedback show bad';
                fb.innerHTML = `❌ None correct yet. Review the section and try again.`;
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