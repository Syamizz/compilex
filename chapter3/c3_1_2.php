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
$page = 8;
$nextPage = "c3_1_3.php";

if (isset($_GET['complete']) && $_GET['complete'] == '8') {
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
            <!-- G6 — Dangling Else                            -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="g6">
                <h2><span class="sec-icon">⚠️</span> Grammar G6 — The Dangling Else Problem</h2>
                <p>
                    Another classic example of ambiguity in programming languages is the
                    <strong>dangling else</strong> problem. Grammar G6 defines conditional statements:
                </p>

                <div class="grammar-box">
                    <div class="g-title">G6 — Ambiguous if-else (part of a larger grammar)</div>
                    <div class="g-rule"><span class="g-num">1.</span><span class="g-nt">Stmt</span><span style="color:#6C7086;"> → </span><span class="g-nt">IfStmt</span></div>
                    <div class="g-rule"><span class="g-num">2.</span><span class="g-nt">IfStmt</span><span style="color:#6C7086;"> → </span><span class="g-t">if ( </span><span class="g-nt">Expr</span><span class="g-t"> ) </span><span class="g-nt">Stmt</span></div>
                    <div class="g-rule"><span class="g-num">3.</span><span class="g-nt">IfStmt</span><span style="color:#6C7086;"> → </span><span class="g-t">if ( </span><span class="g-nt">Expr</span><span class="g-t"> ) </span><span class="g-nt">Stmt</span><span class="g-t"> else </span><span class="g-nt">Stmt</span></div>
                </div>

                <p>
                    The string <code>if (Expr) if (Expr) Stmt else Stmt</code> has
                    <strong>two different derivation trees</strong> (Figure 3.10), corresponding to two different
                    interpretations of which <code>if</code> the <code>else</code> belongs to.
                </p>
            </section>

            <!-- Figure 3.10 -->
            <section id="fig310">
                <h2><span class="sec-icon">🔀</span> Figure 3.10 — Two Parse Trees for the Dangling Else</h2>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin:20px 0;">
                    <div class="tree-panel">
                        <div class="tree-panel-title">Tree 1 — else matches OUTER if ✗ (wrong)</div>
                        <svg width="100%" viewBox="0 0 260 230" style="display:block;">
                            <line x1="130" y1="22" x2="130" y2="56" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="72" x2="40" y2="108" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="72" x2="130" y2="108" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="72" x2="220" y2="108" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="124" x2="130" y2="158" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="174" x2="90" y2="210" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="174" x2="170" y2="210" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <circle cx="130" cy="16" r="14" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.8" />
                            <text x="130" y="21" text-anchor="middle" fill="#F87171" font-size="10" font-weight="700" font-family="Syne">Stmt</text>
                            <circle cx="130" cy="64" r="14" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.6" />
                            <text x="130" y="69" text-anchor="middle" fill="#F87171" font-size="10" font-weight="700" font-family="Syne">IfStmt</text>
                            <circle cx="40" cy="116" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="40" y="121" text-anchor="middle" fill="#10B981" font-size="9" font-family="JetBrains Mono">if(E)</text>
                            <circle cx="130" cy="116" r="12" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.4" />
                            <text x="130" y="121" text-anchor="middle" fill="#F87171" font-size="9" font-weight="700" font-family="Syne">Stmt</text>
                            <circle cx="220" cy="116" r="12" fill="rgba(245,158,11,.1)" stroke="#F59E0B" stroke-width="1.3" />
                            <text x="220" y="121" text-anchor="middle" fill="#F59E0B" font-size="9" font-family="JetBrains Mono">Stmt</text>
                            <circle cx="130" cy="166" r="13" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.4" />
                            <text x="130" y="171" text-anchor="middle" fill="#F87171" font-size="9" font-weight="700" font-family="Syne">IfStmt</text>
                            <circle cx="90" cy="218" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="90" y="223" text-anchor="middle" fill="#10B981" font-size="9" font-family="JetBrains Mono">if(E)</text>
                            <circle cx="170" cy="218" r="12" fill="rgba(245,158,11,.1)" stroke="#F59E0B" stroke-width="1.3" />
                            <text x="170" y="223" text-anchor="middle" fill="#F59E0B" font-size="9" font-family="JetBrains Mono">Stmt</text>
                            <text x="130" y="232" text-anchor="middle" fill="#EF4444" font-size="9" font-family="DM Sans" dy="10">else → outer if ✗</text>
                        </svg>
                    </div>
                    <div class="tree-panel">
                        <div class="tree-panel-title">Tree 2 — else matches INNER if ✅ (correct)</div>
                        <svg width="100%" viewBox="0 0 260 230" style="display:block;">
                            <line x1="130" y1="22" x2="130" y2="56" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="72" x2="130" y2="108" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="124" x2="50" y2="160" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="124" x2="130" y2="160" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="124" x2="210" y2="160" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="176" x2="90" y2="210" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="176" x2="130" y2="210" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="176" x2="175" y2="210" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <circle cx="130" cy="16" r="14" fill="rgba(99,102,241,.15)" stroke="#6366F1" stroke-width="1.8" />
                            <text x="130" y="21" text-anchor="middle" fill="#818CF8" font-size="10" font-weight="700" font-family="Syne">Stmt</text>
                            <circle cx="130" cy="64" r="14" fill="rgba(99,102,241,.12)" stroke="#6366F1" stroke-width="1.6" />
                            <text x="130" y="69" text-anchor="middle" fill="#818CF8" font-size="10" font-weight="700" font-family="Syne">IfStmt</text>
                            <circle cx="130" cy="116" r="14" fill="rgba(99,102,241,.10)" stroke="#6366F1" stroke-width="1.5" />
                            <text x="130" y="121" text-anchor="middle" fill="#A5B4FC" font-size="10" font-weight="700" font-family="Syne">IfStmt</text>
                            <circle cx="50" cy="168" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="50" y="173" text-anchor="middle" fill="#10B981" font-size="9" font-family="JetBrains Mono">if(E)</text>
                            <circle cx="130" cy="168" r="14" fill="rgba(99,102,241,.10)" stroke="#6366F1" stroke-width="1.5" />
                            <text x="130" y="173" text-anchor="middle" fill="#A5B4FC" font-size="9" font-weight="700" font-family="Syne">Stmt</text>
                            <circle cx="210" cy="168" r="12" fill="rgba(245,158,11,.1)" stroke="#F59E0B" stroke-width="1.3" />
                            <text x="210" y="173" text-anchor="middle" fill="#F59E0B" font-size="9" font-family="JetBrains Mono">Stmt</text>
                            <circle cx="90" cy="218" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="90" y="223" text-anchor="middle" fill="#10B981" font-size="9" font-family="JetBrains Mono">if(E)</text>
                            <circle cx="130" cy="218" r="12" fill="rgba(245,158,11,.1)" stroke="#F59E0B" stroke-width="1.3" />
                            <text x="130" y="223" text-anchor="middle" fill="#F59E0B" font-size="9" font-family="JetBrains Mono">Stmt</text>
                            <circle cx="175" cy="218" r="12" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.3" />
                            <text x="175" y="223" text-anchor="middle" fill="#F87171" font-size="9" font-family="JetBrains Mono">else</text>
                            <text x="130" y="232" text-anchor="middle" fill="#10B981" font-size="9" font-family="DM Sans" dy="10">else → inner if ✓ (closest unmatched)</text>
                        </svg>
                    </div>
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        This ambiguity is normally resolved by the rule: <strong>"an else always associates with the
                            closest previous unmatched if"</strong>. Tree 2 is the correct interpretation in Java, C, and
                        most languages. Grammar G7 encodes this rule directly into the grammar structure.
                    </div>
                </div>
            </section>



    


            <div class="chapter-nav">
                <a href="c3_1_1.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>Grammar G6 — The Dangling Else Problem</div>
                </a>
                <a href="?complete=8" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>Grammar G7 — Unambiguous If-Else</div>
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