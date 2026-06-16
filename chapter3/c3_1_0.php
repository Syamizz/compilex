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
$page = 6;
$nextPage = "c3_1_1.php";

if (isset($_GET['complete']) && $_GET['complete'] == '6') {
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
                <li><a href="#overview" class="toc-link">3.1 Overview</a></li>
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
            <!-- 3.1 Overview                                   -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="overview">
                <h2><span class="sec-icon">⚠️</span> 3.1 Why Ambiguity Matters</h2>
                <p>
                    Ambiguities in grammars for programming languages <strong>must be avoided</strong>. Recall:
                    a grammar is ambiguous if any string has more than one derivation tree. Since a derivation
                    tree reflects the <em>structure and meaning</em> of a program, different trees imply different
                    interpretations — which leads to inconsistent compiler behaviour.
                </p>
                <p>
                    There are two main strategies to resolve ambiguity:
                </p>
                <ol>
                    <li><strong>Rewrite the grammar</strong> to be equivalent but unambiguous (G4→G5, G6→G7)</li>
                    <li><strong>Specify a rule</strong> outside the grammar that disambiguates (e.g. "else always matches closest unmatched if")</li>
                </ol>
                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        Grammar G4 (from Section 3.0.3) was ambiguous for arithmetic expressions because it had no
                        built-in precedence or associativity. The fix (G5) introduces a hierarchy of nonterminals —
                        <code>Expr → Term → Factor</code> — that encodes precedence and left-associativity directly
                        into the grammar structure.
                    </div>
                </div>
            </section>

   


            <div class="chapter-nav">
                <a href="c3_0_5.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>3.0.5 Correspondence Between Machines & Languages</div>
                </a>
                <a href="?complete=6" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>Grammar G5 — Unambiguous Arithmetic Expressions</div>
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

        // ════════════════════════════════════════════════════════
        // SORTABLE RULE ORDERING
        // ════════════════════════════════════════════════════════
        let sortDragging = null;

        function sortDragStart(e) {
            sortDragging = e.currentTarget;
            e.currentTarget.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
        }

        function sortDragOver(e) {
            e.preventDefault();
            if (e.currentTarget !== sortDragging) {
                e.currentTarget.classList.add('over');
            }
        }

        function sortDragLeave(e) {
            e.currentTarget.classList.remove('over');
        }

        function sortDrop(e) {
            e.preventDefault();
            const target = e.currentTarget;
            target.classList.remove('over');
            if (target === sortDragging) return;
            const list = document.getElementById('sort-list');
            const items = [...list.children];
            const fromIdx = items.indexOf(sortDragging);
            const toIdx = items.indexOf(target);
            if (fromIdx < toIdx) list.insertBefore(sortDragging, target.nextSibling);
            else list.insertBefore(sortDragging, target);
            sortDragging.classList.remove('dragging');
            sortDragging = null;
            updateSortNums();
        }
        document.addEventListener('dragend', () => {
            if (sortDragging) {
                sortDragging.classList.remove('dragging');
                sortDragging = null;
            }
        });

        function updateSortNums() {
            [...document.getElementById('sort-list').children].forEach((item, i) => {
                item.querySelector('.si-num').textContent = i + 1;
            });
        }

        function checkSort() {
            const items = [...document.getElementById('sort-list').children];
            const correctOrder = ['1', '2', '3', '4', '5', '6', '7'];
            let correct = 0;
            items.forEach((item, i) => {
                if (item.dataset.correct === correctOrder[i]) correct++;
            });
            const res = document.getElementById('sort-result');
            res.style.display = 'block';
            if (correct === 7) {
                res.className = 'sort-result show ok';
                res.innerHTML = '✅ <strong>Perfect!</strong> G5 rules in the correct order: Expr (addition) → Term (multiplication) → Factor (atomic). This hierarchy encodes precedence!';
            } else {
                res.className = 'sort-result show bad';
                res.innerHTML = `❌ <strong>${correct}/7 correct.</strong> Hint: Expr rules first (rules 1–2), then Term rules (3–4), then Factor rules (5–7). The order matters because Term nests inside Expr gives * higher precedence than +.`;
            }
        }

        function resetSort() {
            const list = document.getElementById('sort-list');
            const items = [...list.children];
            for (let i = items.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                list.insertBefore(items[j], items[i]);
                [items[i], items[j]] = [items[j], items[i]];
            }
            updateSortNums();
            const res = document.getElementById('sort-result');
            res.style.display = 'none';
            res.innerHTML = '';
        }

        updateSortNums();


    </script>

</body>

</html>