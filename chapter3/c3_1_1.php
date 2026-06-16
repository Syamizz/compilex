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
$page = 7;
$nextPage = "c3_1_2.php";

if (isset($_GET['complete']) && $_GET['complete'] == '7') {
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
    <title>Chapter 3.1 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c3/c3_1.css">
</head>

<body>

    <?php include 'navbar_c3.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#g5" class="toc-link">G5 — Unambiguous Expressions</a></li>
                <li><a href="#fig39" class="toc-link sub">↳ Figure 3.9 — Parse Tree</a></li>
                <li><a href="#left-assoc" class="toc-link sub">↳ Left Associativity</a></li>
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
                <div class="chapter-tag">📘 Chapter 3 · Section 3.1</div>
                <h1>Ambiguities in Programming Languages</h1>
                <div class="metadata">
                    <span>⏱ 18 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>📐 Grammar Design</span>
                </div>
            </header>


            <!-- ══════════════════════════════════════════════ -->
            <!-- G5 — Unambiguous Arithmetic                   -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="g5">
                <h2><span class="sec-icon">✅</span> Grammar G5 — Unambiguous Arithmetic Expressions</h2>
                <p>
                    Grammar G5 is an <strong>unambiguous</strong> version of G4. It introduces a three-level
                    hierarchy that naturally encodes operator precedence and associativity:
                </p>

                <div class="grammar-box">
                    <div class="g-title">G5 — Unambiguous arithmetic expressions</div>
                    <div class="g-rule"><span class="g-num">1.</span><span class="g-nt">Expr</span><span style="color:#6C7086;"> → </span><span class="g-nt">Expr</span><span class="g-t"> + </span><span class="g-nt">Term</span><span style="color:#6C7086;font-size:11px;font-style:italic;margin-left:12px;">// left-associative +</span></div>
                    <div class="g-rule"><span class="g-num">2.</span><span class="g-nt">Expr</span><span style="color:#6C7086;"> → </span><span class="g-nt">Term</span></div>
                    <div class="g-rule"><span class="g-num">3.</span><span class="g-nt">Term</span><span style="color:#6C7086;"> → </span><span class="g-nt">Term</span><span class="g-t"> * </span><span class="g-nt">Factor</span><span style="color:#6C7086;font-size:11px;font-style:italic;margin-left:12px;">// left-associative *</span></div>
                    <div class="g-rule"><span class="g-num">4.</span><span class="g-nt">Term</span><span style="color:#6C7086;"> → </span><span class="g-nt">Factor</span></div>
                    <div class="g-rule"><span class="g-num">5.</span><span class="g-nt">Factor</span><span style="color:#6C7086;"> → </span><span class="g-t">( </span><span class="g-nt">Expr</span><span class="g-t"> )</span></div>
                    <div class="g-rule"><span class="g-num">6.</span><span class="g-nt">Factor</span><span style="color:#6C7086;"> → </span><span class="g-t">var</span></div>
                    <div class="g-rule"><span class="g-num">7.</span><span class="g-nt">Factor</span><span style="color:#6C7086;"> → </span><span class="g-t">const</span></div>
                </div>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Nonterminal</th>
                            <th>Role</th>
                            <th>Precedence level</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>Expr</code></td>
                            <td>Addition/subtraction level</td>
                            <td>Lowest (evaluated last)</td>
                        </tr>
                        <tr>
                            <td><code>Term</code></td>
                            <td>Multiplication/division level</td>
                            <td>Middle</td>
                        </tr>
                        <tr>
                            <td><code>Factor</code></td>
                            <td>Atomic values: vars, consts, parenthesised expressions</td>
                            <td>Highest (evaluated first)</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box key">
                    <span class="box-icon">✅</span>
                    <div>
                        In G5, <strong>every subtree in the derivation tree corresponds to a subexpression</strong>,
                        according to the usual precedence rules. There is only <em>one</em> derivation tree for any
                        given expression — proving G5 is unambiguous.
                    </div>
                </div>
            </section>

            <!-- Figure 3.9 -->
            <section id="fig39">
                <h2><span class="sec-icon">🌳</span> Figure 3.9 — Parse Tree for <code>var + var * var</code> (G5)</h2>
                <p>
                    This derivation tree shows that <code>*</code> binds tighter than <code>+</code> — the
                    multiplication subtree appears deeper in the tree. There is only one possible tree for
                    this expression in G5, proving it is unambiguous.
                </p>

                <div class="tree-panel">
                    <div class="tree-panel-title">Figure 3.9 — var + var * var parsed by G5</div>
                    <svg width="100%" viewBox="0 0 420 240" style="display:block;max-width:520px;margin:0 auto;">
                        <defs>
                            <marker id="ta" markerWidth="6" markerHeight="6" refX="3" refY="3" orient="auto">
                                <circle cx="3" cy="3" r="2.5" fill="#6366F1" opacity=".4" />
                            </marker>
                        </defs>
                        <!-- Edges: Expr → Expr + Term -->
                        <line x1="210" y1="28" x2="90" y2="68" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="210" y1="28" x2="200" y2="68" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="210" y1="28" x2="330" y2="68" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <!-- Expr (left) → Term → Factor → var -->
                        <line x1="90" y1="84" x2="90" y2="120" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="90" y1="136" x2="90" y2="172" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <!-- Term (right) → Term * Factor -->
                        <line x1="330" y1="84" x2="270" y2="120" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="330" y1="84" x2="330" y2="120" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="330" y1="84" x2="390" y2="120" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <!-- Term inner → Factor → var -->
                        <line x1="270" y1="136" x2="270" y2="172" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="270" y1="188" x2="270" y2="220" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <!-- Factor (right) → var -->
                        <line x1="390" y1="136" x2="390" y2="172" stroke="#6366F1" stroke-width="1.5" opacity=".4" />

                        <!-- Nodes -->
                        <circle cx="210" cy="20" r="16" fill="rgba(99,102,241,.15)" stroke="#6366F1" stroke-width="1.8" />
                        <text x="210" y="25" text-anchor="middle" fill="#818CF8" font-size="11" font-weight="700" font-family="Syne">Expr</text>
                        <circle cx="90" cy="76" r="16" fill="rgba(99,102,241,.10)" stroke="#6366F1" stroke-width="1.5" />
                        <text x="90" y="81" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">Expr</text>
                        <circle cx="200" cy="76" r="12" fill="rgba(245,158,11,.12)" stroke="#F59E0B" stroke-width="1.4" />
                        <text x="200" y="81" text-anchor="middle" fill="#F59E0B" font-size="13" font-weight="700" font-family="JetBrains Mono">+</text>
                        <circle cx="330" cy="76" r="16" fill="rgba(99,102,241,.12)" stroke="#6366F1" stroke-width="1.6" />
                        <text x="330" y="81" text-anchor="middle" fill="#818CF8" font-size="11" font-weight="700" font-family="Syne">Term</text>
                        <circle cx="90" cy="128" r="14" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.4" />
                        <text x="90" y="133" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">Term</text>
                        <circle cx="90" cy="178" r="14" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.4" />
                        <text x="90" y="183" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">Factor</text>
                        <circle cx="90" cy="220" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                        <text x="90" y="225" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">var</text>
                        <circle cx="270" cy="128" r="14" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.4" />
                        <text x="270" y="133" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">Term</text>
                        <circle cx="330" cy="128" r="12" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.4" />
                        <text x="330" y="133" text-anchor="middle" fill="#F87171" font-size="13" font-weight="700" font-family="JetBrains Mono">*</text>
                        <circle cx="390" cy="128" r="14" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.4" />
                        <text x="390" y="133" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">Factor</text>
                        <circle cx="270" cy="178" r="14" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.4" />
                        <text x="270" y="183" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">Factor</text>
                        <circle cx="270" cy="220" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                        <text x="270" y="225" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">var</text>
                        <circle cx="390" cy="178" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                        <text x="390" y="183" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">var</text>

                        <text x="210" y="20" text-anchor="middle" dx="0" dy="0" fill="none" />
                        <text x="70" y="240" fill="#10B981" font-size="9" font-family="DM Sans">Term deeper → * has higher precedence ✓</text>
                    </svg>
                </div>
            </section>

            <!-- Left associativity -->
            <section id="left-assoc">
                <h2><span class="sec-icon">⬅️</span> Left Associativity in G5</h2>
                <p>
                    Grammar G5 also enforces <strong>left associativity</strong> for both <code>+</code> and
                    <code>*</code>. Consider <code>var + var + var</code>: in G5, Rule 1 is
                    <code>Expr → Expr + Term</code>, which always places the left operand as an
                    <code>Expr</code> subtree. This forces the tree to be left-leaning — meaning
                    <code>a + b + c</code> is parsed as <code>(a + b) + c</code>, not <code>a + (b + c)</code>.
                </p>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin:20px 0;">
                    <div class="tree-panel">
                        <div class="tree-panel-title">Left-associative: (var + var) + var ✅</div>
                        <svg width="100%" viewBox="0 0 200 160" style="display:block;">
                            <line x1="100" y1="22" x2="55" y2="58" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="100" y1="22" x2="100" y2="58" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="100" y1="22" x2="155" y2="58" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="55" y1="74" x2="25" y2="110" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="55" y1="74" x2="55" y2="110" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="55" y1="74" x2="85" y2="110" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <circle cx="100" cy="16" r="14" fill="rgba(99,102,241,.15)" stroke="#6366F1" stroke-width="1.8" />
                            <text x="100" y="21" text-anchor="middle" fill="#818CF8" font-size="10" font-weight="700" font-family="Syne">Expr</text>
                            <circle cx="55" cy="66" r="13" fill="rgba(99,102,241,.10)" stroke="#6366F1" stroke-width="1.5" />
                            <text x="55" y="71" text-anchor="middle" fill="#A5B4FC" font-size="10" font-weight="700" font-family="Syne">Expr</text>
                            <circle cx="100" cy="66" r="11" fill="rgba(245,158,11,.12)" stroke="#F59E0B" stroke-width="1.3" />
                            <text x="100" y="71" text-anchor="middle" fill="#F59E0B" font-size="12" font-weight="700" font-family="JetBrains Mono">+</text>
                            <circle cx="155" cy="66" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="155" y="71" text-anchor="middle" fill="#10B981" font-size="10" font-family="JetBrains Mono">var</text>
                            <circle cx="25" cy="118" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="25" y="123" text-anchor="middle" fill="#10B981" font-size="10" font-family="JetBrains Mono">var</text>
                            <circle cx="55" cy="118" r="11" fill="rgba(245,158,11,.12)" stroke="#F59E0B" stroke-width="1.3" />
                            <text x="55" y="123" text-anchor="middle" fill="#F59E0B" font-size="12" font-weight="700" font-family="JetBrains Mono">+</text>
                            <circle cx="85" cy="118" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="85" y="123" text-anchor="middle" fill="#10B981" font-size="10" font-family="JetBrains Mono">var</text>
                            <text x="100" y="148" text-anchor="middle" fill="#10B981" font-size="9" font-family="DM Sans">(a+b)+c — left subtree is deeper ✓</text>
                        </svg>
                    </div>
                    <div class="tree-panel">
                        <div class="tree-panel-title">Right-associative: var + (var + var) ✗ — not in G5</div>
                        <svg width="100%" viewBox="0 0 200 160" style="display:block;">
                            <line x1="100" y1="22" x2="45" y2="58" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="100" y1="22" x2="100" y2="58" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="100" y1="22" x2="155" y2="58" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="155" y1="74" x2="125" y2="110" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="155" y1="74" x2="155" y2="110" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="155" y1="74" x2="185" y2="110" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <circle cx="100" cy="16" r="14" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.8" />
                            <text x="100" y="21" text-anchor="middle" fill="#F87171" font-size="10" font-weight="700" font-family="Syne">Expr</text>
                            <circle cx="45" cy="66" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="45" y="71" text-anchor="middle" fill="#10B981" font-size="10" font-family="JetBrains Mono">var</text>
                            <circle cx="100" cy="66" r="11" fill="rgba(245,158,11,.12)" stroke="#F59E0B" stroke-width="1.3" />
                            <text x="100" y="71" text-anchor="middle" fill="#F59E0B" font-size="12" font-weight="700" font-family="JetBrains Mono">+</text>
                            <circle cx="155" cy="66" r="13" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.5" />
                            <text x="155" y="71" text-anchor="middle" fill="#F87171" font-size="10" font-weight="700" font-family="Syne">Expr</text>
                            <circle cx="125" cy="118" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="125" y="123" text-anchor="middle" fill="#10B981" font-size="10" font-family="JetBrains Mono">var</text>
                            <circle cx="155" cy="118" r="11" fill="rgba(245,158,11,.12)" stroke="#F59E0B" stroke-width="1.3" />
                            <text x="155" y="123" text-anchor="middle" fill="#F59E0B" font-size="12" font-weight="700" font-family="JetBrains Mono">+</text>
                            <circle cx="185" cy="118" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="185" y="123" text-anchor="middle" fill="#10B981" font-size="10" font-family="JetBrains Mono">var</text>
                            <text x="100" y="148" text-anchor="middle" fill="#EF4444" font-size="9" font-family="DM Sans">a+(b+c) — right subtree deeper ✗ not from G5</text>
                        </svg>
                    </div>
                </div>
            </section>

        





    


            <div class="chapter-nav">
                <a href="c3_1_0.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>3.1 Ambiguities in Programming Languages</div>
                </a>
                <a href="?complete=7" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>Grammar G6 — The Dangling Else Problem</div>
                    <span>→</span>
                </a>
            </div>

        </article>
    </div>

    <script>
        // TOC
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
        // PARSE TREE BUILDER (G5)
        // ════════════════════════════════════════════════════════
        function buildTree() {
            const sel = document.getElementById('tb-expr-sel').value;
            const exprMap = {
                'v+v*v': {
                    steps: ['Expr', 'Expr + Term', 'Term + Term', 'Factor + Term', 'var + Term', 'var + Term * Factor', 'var + Factor * Factor', 'var + var * Factor', 'var + var * var'],
                    result: 'var + var * var',
                    desc: '* deeper → higher precedence ✓'
                },
                'v*v': {
                    steps: ['Expr', 'Term', 'Term * Factor', 'Factor * Factor', 'var * Factor', 'var * var'],
                    result: 'var * var',
                    desc: 'simple multiplication'
                },
                'v*v+v': {
                    steps: ['Expr', 'Expr + Term', 'Term + Term', 'Term * Factor + Term', 'Factor * Factor + Term', 'var * Factor + Term', 'var * var + Term', 'var * var + Factor', 'var * var + var'],
                    result: 'var * var + var',
                    desc: '* higher precedence ✓ left assoc ✓'
                },
                '(v+v)*v': {
                    steps: ['Expr', 'Term', 'Term * Factor', 'Factor * Factor', '( Expr ) * Factor', '( Expr + Term ) * Factor', '( Term + Term ) * Factor', '( Factor + Factor ) * Factor', '( var + var ) * Factor', '( var + var ) * var'],
                    result: '(var + var) * var',
                    desc: 'parentheses override precedence ✓'
                },
                'v*v*v': {
                    steps: ['Expr', 'Term', 'Term * Factor', 'Term * Factor * Factor', 'Factor * Factor * Factor', 'var * Factor * Factor', 'var * var * Factor', 'var * var * var'],
                    result: 'var * var * var',
                    desc: 'left-associative: (var*var)*var ✓'
                },
                'v': {
                    steps: ['Expr', 'Term', 'Factor', 'var'],
                    result: 'var',
                    desc: 'atomic expression'
                }
            };
            const e = exprMap[sel];
            const out = document.getElementById('tb-output');
            out.innerHTML = e.steps.map((step, i) => {
                const prefix = i === 0 ? '<span style="color:#6C7086;">Deriving: </span>' : '<span style="color:#6C7086;">⇒ </span>';
                const coloured = step
                    .replace(/\b(Expr|Term|Factor)\b/g, '<span style="color:#CBA6F7;font-weight:700;">$1</span>')
                    .replace(/\b(var|const)\b/g, '<span style="color:#A6E3A1;">$1</span>')
                    .replace(/([+*()])/g, '<span style="color:#F38BA8;">$1</span>');
                return prefix + coloured;
            }).join('<br>') + `<br><span style="color:#6EE7B7;font-weight:700;">Result: ${e.result}</span> <span style="color:#6C7086;font-style:italic;">(${e.desc})</span>`;

            // Simple SVG tree
            const svg = document.getElementById('tb-tree-svg');
            svg.innerHTML = `<div style="background:var(--purple-s);border-radius:10px;padding:12px 16px;font-size:13px;color:var(--purple);font-family:'DM Sans',sans-serif;">
        <strong>✅ Parse successful</strong> — "${e.result}" is in L(G5). 
        Derivation has ${e.steps.length} steps. ${e.desc}
      </div>`;
        }

        buildTree();

        // ════════════════════════════════════════════════════════
        // AMBIGUITY CHECKER
        // ════════════════════════════════════════════════════════
        const AMB_DATA = {
            if_s: {
                trees: 1,
                g6: 'Unambiguous — only 1 tree',
                g7: '1 tree (Unmatched rule 6)'
            },
            if_else: {
                trees: 1,
                g6: 'Unambiguous — only 1 tree',
                g7: '1 tree (Matched rule 4, OtherStmt)'
            },
            if_if_s: {
                trees: 1,
                g6: 'Unambiguous — only 1 tree (no else to assign)',
                g7: '1 tree'
            },
            if_if_s_else_s: {
                trees: 2,
                g6: 'AMBIGUOUS — 2 trees! else can match inner or outer if',
                g7: '1 tree (G7 forces else → inner if via Matched rule)'
            },
            if_s_else_if_s: {
                trees: 1,
                g6: 'Unambiguous — else clearly belongs to first if',
                g7: '1 tree'
            }
        };

        function checkAmbiguity() {
            const sel = document.getElementById('amb-sel').value;
            const d = AMB_DATA[sel];
            const res = document.getElementById('amb-result');
            res.style.display = 'block';
            if (d.trees > 1) {
                res.className = 'amb-result ambig';
                res.innerHTML = `⚠️ <strong>AMBIGUOUS under G6!</strong> ${d.g6}<br><br>✅ <strong>G7 resolves this:</strong> ${d.g7}`;
            } else {
                res.className = 'amb-result ok';
                res.innerHTML = `✅ <strong>Unambiguous under G6</strong> — ${d.g6}<br>G7 also gives: ${d.g7}`;
            }
        }

   
      

    </script>

</body>

</html>