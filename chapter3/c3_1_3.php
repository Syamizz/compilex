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
$page = 9;
$nextPage = "c3_2_0.php";

if (isset($_GET['complete']) && $_GET['complete'] == '9') {
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
                <li><a href="#g6" class="toc-link">G6 — Dangling Else (Ambiguous)</a></li>
                <li><a href="#fig310" class="toc-link sub">↳ Two Trees for Dangling Else</a></li>
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
            <!-- G7 — Unambiguous If-Else                      -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="g7">
                <h2><span class="sec-icon">✅</span> Grammar G7 — Unambiguous If-Else</h2>
                <p>
                    G7 rewrites G6 to be unambiguous by differentiating between two kinds of if statements —
                    those with a matching else (<strong>Matched</strong>) and those without (<strong>Unmatched</strong>).
                </p>

                <div class="grammar-box">
                    <div class="g-title">G7 — Unambiguous if-else grammar</div>
                    <div class="g-rule"><span class="g-num">1.</span><span class="g-nt">Stmt</span><span style="color:#6C7086;"> → </span><span class="g-nt">IfStmt</span></div>
                    <div class="g-rule"><span class="g-num">2.</span><span class="g-nt">IfStmt</span><span style="color:#6C7086;"> → </span><span class="g-nt">Matched</span></div>
                    <div class="g-rule"><span class="g-num">3.</span><span class="g-nt">IfStmt</span><span style="color:#6C7086;"> → </span><span class="g-nt">Unmatched</span></div>
                    <div class="g-rule"><span class="g-num">4.</span><span class="g-nt">Matched</span><span style="color:#6C7086;"> → </span><span class="g-t">if ( </span><span class="g-nt">Expr</span><span class="g-t"> ) </span><span class="g-nt">Matched</span><span class="g-t"> else </span><span class="g-nt">Matched</span></div>
                    <div class="g-rule"><span class="g-num">5.</span><span class="g-nt">Matched</span><span style="color:#6C7086;"> → </span><span class="g-nt">OtherStmt</span></div>
                    <div class="g-rule"><span class="g-num">6.</span><span class="g-nt">Unmatched</span><span style="color:#6C7086;"> → </span><span class="g-t">if ( </span><span class="g-nt">Expr</span><span class="g-t"> ) </span><span class="g-nt">Stmt</span></div>
                    <div class="g-rule"><span class="g-num">7.</span><span class="g-nt">Unmatched</span><span style="color:#6C7086;"> → </span><span class="g-t">if ( </span><span class="g-nt">Expr</span><span class="g-t"> ) </span><span class="g-nt">Matched</span><span class="g-t"> else </span><span class="g-nt">Unmatched</span></div>
                </div>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Nonterminal</th>
                            <th>Meaning</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>Matched</code></td>
                            <td>An if-statement that has a matching else (or a non-if statement)</td>
                        </tr>
                        <tr>
                            <td><code>Unmatched</code></td>
                            <td>An if-statement without a matching else — the "dangling" case</td>
                        </tr>
                        <tr>
                            <td><code>OtherStmt</code></td>
                            <td>Any non-if statement: while, for, expression, etc.</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box key">
                    <span class="box-icon">✅</span>
                    <div>
                        Rule 4 requires that both the "then" branch and the "else" branch of a matched if are themselves
                        <code>Matched</code>. This prevents an else-less if from appearing in the "then" branch
                        (which would create a dangling else), forcing the else to always match the closest if.
                    </div>
                </div>
            </section>

            <!-- Figure 3.11 -->
            <section id="fig311">
                <h2><span class="sec-icon">🌳</span> Figure 3.11 — Parse Tree Using G7</h2>
                <p>
                    Using Grammar G7, the string
                    <code>if (Expr) if (Expr) OtherStmt else OtherStmt</code>
                    has only <strong>one</strong> derivation tree, shown below. The <code>else</code>
                    matches the inner <code>if</code>, as enforced by the grammar.
                </p>

                <div class="tree-panel">
                    <div class="tree-panel-title">Figure 3.11 — Unique parse tree using G7 (else matches inner if)</div>
                    <svg width="100%" viewBox="0 0 480 250" style="display:block;max-width:560px;margin:0 auto;">
                        <line x1="240" y1="26" x2="240" y2="60" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="240" y1="76" x2="240" y2="110" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="240" y1="126" x2="240" y2="160" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="240" y1="176" x2="80" y2="212" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="240" y1="176" x2="240" y2="212" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="240" y1="176" x2="400" y2="212" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="240" y1="228" x2="180" y2="248" stroke="#6366F1" stroke-width="1.2" opacity=".3" />
                        <line x1="240" y1="228" x2="300" y2="248" stroke="#6366F1" stroke-width="1.2" opacity=".3" />
                        <!-- nodes -->
                        <circle cx="240" cy="18" r="14" fill="rgba(99,102,241,.15)" stroke="#6366F1" stroke-width="1.8" />
                        <text x="240" y="23" text-anchor="middle" fill="#818CF8" font-size="11" font-weight="700" font-family="Syne">Stmt</text>
                        <circle cx="240" cy="68" r="14" fill="rgba(99,102,241,.12)" stroke="#6366F1" stroke-width="1.6" />
                        <text x="240" y="73" text-anchor="middle" fill="#818CF8" font-size="11" font-weight="700" font-family="Syne">IfStmt</text>
                        <circle cx="240" cy="118" r="16" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.6" />
                        <text x="240" y="123" text-anchor="middle" fill="#F87171" font-size="11" font-weight="700" font-family="Syne">Unmatched</text>
                        <circle cx="240" cy="168" r="14" fill="rgba(99,102,241,.10)" stroke="#6366F1" stroke-width="1.5" />
                        <text x="240" y="173" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">IfStmt</text>
                        <circle cx="80" cy="220" r="14" fill="rgba(16,185,129,.12)" stroke="#10B981" stroke-width="1.6" />
                        <text x="80" y="225" text-anchor="middle" fill="#10B981" font-size="10" font-weight="700" font-family="Syne">Matched</text>
                        <circle cx="240" cy="220" r="12" fill="rgba(245,158,11,.1)" stroke="#F59E0B" stroke-width="1.3" />
                        <text x="240" y="225" text-anchor="middle" fill="#F59E0B" font-size="9" font-family="JetBrains Mono">if(E)</text>
                        <circle cx="400" cy="220" r="14" fill="rgba(16,185,129,.12)" stroke="#10B981" stroke-width="1.6" />
                        <text x="400" y="225" text-anchor="middle" fill="#10B981" font-size="10" font-weight="700" font-family="Syne">Matched</text>
                        <!-- bottom labels -->
                        <text x="80" y="243" text-anchor="middle" fill="#10B981" font-size="9" font-family="JetBrains Mono">if(E) Matched else Matched</text>
                        <text x="80" y="255" text-anchor="middle" fill="#6C7086" font-size="9" font-family="DM Sans">(Rule 4)</text>
                        <text x="400" y="243" text-anchor="middle" fill="#10B981" font-size="9" font-family="JetBrains Mono">OtherStmt</text>
                        <text x="400" y="255" text-anchor="middle" fill="#6C7086" font-size="9" font-family="DM Sans">(Rule 5)</text>
                        <text x="120" y="270" text-anchor="middle" fill="#10B981" font-size="10" font-family="DM Sans">else → inner if ✓ (only one derivation possible in G7)</text>
                    </svg>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Interactive: Parse Tree Builder               -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="tree-builder">
                <h2><span class="sec-icon">🌳</span> Parse Tree Builder — G5 Expressions</h2>
                <p>
                    Enter an arithmetic expression using <code>var</code>, <code>const</code>, <code>+</code>,
                    <code>*</code>, and parentheses. The builder will show the step-by-step parse using G5
                    and display the derivation.
                </p>

                <div class="tree-builder">
                    <div class="tb-header">
                        <h4>🌲 G5 Parse Tree Builder</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);">Uses Grammar G5</span>
                    </div>
                    <div class="tb-body">
                        <div class="tb-controls">
                            <select class="tb-sel" id="tb-expr-sel" onchange="buildTree()">
                                <option value="v+v*v">var + var * var</option>
                                <option value="v*v">var * var</option>
                                <option value="v*v+v">var * var + var</option>
                                <option value="(v+v)*v">(var + var) * var</option>
                                <option value="v*v*v">var * var * var</option>
                                <option value="v">var</option>
                            </select>
                            <button class="tb-btn sec" onclick="buildTree()">↺ Refresh</button>
                        </div>
                        <div id="tb-output" style="background:var(--code-bg);border-radius:var(--radius);padding:16px;font-family:'JetBrains Mono',monospace;font-size:12.5px;color:#CDD6F4;line-height:2;"></div>
                        <div id="tb-tree-svg" style="margin-top:16px;overflow-x:auto;"></div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Ambiguity Checker                             -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="amb-checker">
                <h2><span class="sec-icon">⚠️</span> Ambiguity Checker — G6 vs G7</h2>
                <p>
                    Test whether an <code>if</code>/<code>else</code> structure is ambiguous under G6.
                    Select the nesting pattern and see how many derivation trees it produces.
                </p>

                <div class="amb-wrap">
                    <div class="amb-header">
                        <h4>🔍 If-Else Ambiguity Tester</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);">G6 vs G7</span>
                    </div>
                    <div class="amb-body">
                        <select class="amb-input" id="amb-sel" style="width:auto;font-size:13px;padding:8px 12px;cursor:pointer;">
                            <option value="if_s">if (E) Stmt</option>
                            <option value="if_else">if (E) Stmt else Stmt</option>
                            <option value="if_if_s">if (E) if (E) Stmt</option>
                            <option value="if_if_s_else_s">if (E) if (E) Stmt else Stmt</option>
                            <option value="if_s_else_if_s">if (E) Stmt else if (E) Stmt</option>
                        </select>
                        <button class="amb-btn" style="margin-top:0;margin-left:8px;" onclick="checkAmbiguity()">Check Ambiguity</button>
                        <div id="amb-result" class="amb-result" style="display:none;"></div>
                    </div>
                </div>
            </section>






            <div class="chapter-nav">
                <a href="c3_1_2.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>Grammar G6 — The Dangling Else Problem</div>
                </a>
                <a href="?complete=9" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>3.2 - The Parsing Problem</div>
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