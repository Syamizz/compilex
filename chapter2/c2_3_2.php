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
$page = 13;
$nextPage = "c2_3_3.php";

if (isset($_GET['complete']) && $_GET['complete'] == '13') {
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
    <title>Chapter 2.3 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c2/c2_3.css">
</head>

<body>

    <?php include 'navbar_c2.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#bst" class="toc-link">2.3.2 Binary Search Tree</a></li>
                <li><a href="#bst-vis" class="toc-link sub">↳ Interactive BST Builder</a></li>
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
                <div class="chapter-tag">📘 Chapter 2 · Section 2.3</div>
                <h1>Lexical Tables</h1>
                <div class="metadata">
                    <span>⏱ 16 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>🗃️ Data Structures</span>
                </div>
            </header>







            <!-- ══════════════════════════════════════════════ -->
            <!-- 2.3.2 Binary Search Tree                      -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="bst">
                <h2><span class="sec-icon">🌳</span> 2.3.2 Binary Search Tree</h2>
                <p>
                    The table is organised as a <strong>binary search tree (BST)</strong>: for every node, all
                    words in its <em>left subtree</em> alphabetically precede it, and all words in its
                    <em>right subtree</em> follow it.
                </p>

                <h3>Insertion Algorithm</h3>
                <ol>
                    <li>The first word encountered becomes the <strong>root</strong></li>
                    <li>For each subsequent word <em>w</em>, start at the root and compare:
                        <ul>
                            <li>If <em>w</em> &lt; current node → go <strong>left</strong></li>
                            <li>If <em>w</em> &gt; current node → go <strong>right</strong></li>
                            <li>If <em>w</em> = current node → <strong>already in table</strong>, stop</li>
                        </ul>
                    </li>
                    <li>When a null child is reached, <strong>insert w there</strong></li>
                </ol>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Balance depends on insertion order.</strong> The same set of words can produce very
                        different trees depending on the order they are inserted. Inserting in alphabetical order
                        produces a degenerate tree (effectively a linked list), degrading performance to O(n²).
                    </div>
                </div>

                <h3>Figure 2.8 — Two BSTs for the Same Words</h3>
                <p>
                    Words: <code>frog, tree, hill, bird, bat, cat</code> inserted in that order (a) produce a
                    <strong>balanced</strong> tree. The same words in alphabetical order (b) produce a
                    <strong>degenerate</strong> (linked-list) tree.
                </p>

                <!-- Static Figure 2.8 SVG -->
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin:20px 0;">
                    <!-- (a) Balanced -->
                    <div style="background:var(--card);border:1px solid rgba(99,102,241,.15);border-radius:var(--radius);padding:16px;box-shadow:var(--shadow);">
                        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--purple);margin-bottom:10px;text-align:center;">(a) frog, tree, hill, bird, bat, cat — BALANCED</div>
                        <svg width="100%" viewBox="0 0 260 200" style="display:block;">
                            <defs>
                                <marker id="ba" markerWidth="6" markerHeight="6" refX="3" refY="3" orient="auto">
                                    <circle cx="3" cy="3" r="2.5" fill="#6366F1" opacity=".5" />
                                </marker>
                            </defs>
                            <!-- edges -->
                            <line x1="130" y1="36" x2="70" y2="84" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="36" x2="195" y2="84" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="70" y1="100" x2="35" y2="148" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="70" y1="100" x2="108" y2="148" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="195" y1="100" x2="158" y2="148" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <!-- nodes -->
                            <circle cx="130" cy="24" r="20" fill="rgba(99,102,241,.12)" stroke="#6366F1" stroke-width="1.8" />
                            <text x="130" y="29" text-anchor="middle" fill="#818CF8" font-size="11" font-weight="700" font-family="JetBrains Mono">frog</text>
                            <circle cx="70" cy="88" r="20" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.5" />
                            <text x="70" y="93" text-anchor="middle" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">bird</text>
                            <circle cx="195" cy="88" r="20" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.5" />
                            <text x="195" y="93" text-anchor="middle" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">tree</text>
                            <circle cx="35" cy="156" r="18" fill="rgba(16,185,129,.08)" stroke="#10B981" stroke-width="1.5" />
                            <text x="35" y="161" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">bat</text>
                            <circle cx="108" cy="156" r="18" fill="rgba(16,185,129,.08)" stroke="#10B981" stroke-width="1.5" />
                            <text x="108" y="161" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">cat</text>
                            <circle cx="158" cy="156" r="18" fill="rgba(16,185,129,.08)" stroke="#10B981" stroke-width="1.5" />
                            <text x="158" y="161" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">hill</text>
                            <text x="130" y="196" text-anchor="middle" fill="#10B981" font-size="10" font-family="DM Sans">✓ Balanced — O(n log n)</text>
                        </svg>
                    </div>
                    <!-- (b) Degenerate -->
                    <div style="background:var(--card);border:1px solid rgba(239,68,68,.2);border-radius:var(--radius);padding:16px;box-shadow:var(--shadow);">
                        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--red);margin-bottom:10px;text-align:center;">(b) bat, bird, cat, frog, hill, tree — DEGENERATE</div>
                        <svg width="100%" viewBox="0 0 260 210" style="display:block;">
                            <line x1="50" y1="36" x2="90" y2="60" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="110" y1="74" x2="140" y2="98" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="160" y1="112" x2="190" y2="136" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="210" y1="150" x2="230" y2="174" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <circle cx="50" cy="24" r="18" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.5" />
                            <text x="50" y="29" text-anchor="middle" fill="#F87171" font-size="11" font-family="JetBrains Mono">bat</text>
                            <circle cx="110" cy="62" r="18" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.2" />
                            <text x="110" y="67" text-anchor="middle" fill="#F87171" font-size="11" font-family="JetBrains Mono">bird</text>
                            <circle cx="160" cy="100" r="18" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.2" />
                            <text x="160" y="105" text-anchor="middle" fill="#F87171" font-size="11" font-family="JetBrains Mono">cat</text>
                            <circle cx="205" cy="138" r="18" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.2" />
                            <text x="205" y="143" text-anchor="middle" fill="#F87171" font-size="11" font-family="JetBrains Mono">frog</text>
                            <circle cx="235" cy="174" r="16" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.2" />
                            <text x="235" y="179" text-anchor="middle" fill="#F87171" font-size="10" font-family="JetBrains Mono">hill</text>
                            <text x="130" y="202" text-anchor="middle" fill="#EF4444" font-size="10" font-family="DM Sans">✗ Degenerate — O(n²) worst case</text>
                        </svg>
                    </div>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        <strong>Search complexity:</strong> In a <em>balanced</em> BST of n nodes, finding any word
                        requires at most <strong>log₂(n) + 1</strong> comparisons. For 2047 = 2¹¹−1 balanced nodes,
                        that is at most 12 comparisons. In the worst case (alphabetical insertion), it takes up to n comparisons.
                    </div>
                </div>
            </section>

            <!-- Interactive BST -->
            <section id="bst-vis">
                <h2><span class="sec-icon">▶️</span> Interactive BST Builder</h2>
                <p>Type a word and click <strong>Insert</strong> to add it to the BST. Watch how the tree grows
                    and note how insertion order affects its shape. Use the preset examples from the textbook.</p>

                <div class="vis-wrap">
                    <div class="vis-header">
                        <h4>🌳 Binary Search Tree Visualiser</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);" id="bst-stats">0 nodes</span>
                    </div>
                    <div class="vis-body">
                        <div class="vis-controls">
                            <input class="vis-input" id="bst-input" type="text" placeholder="Enter word…" maxlength="12" onkeydown="if(event.key==='Enter')bstInsert()">
                            <button class="vis-btn" onclick="bstInsert()">Insert</button>
                            <button class="vis-btn sec" onclick="bstReset()">Clear</button>
                        </div>
                        <div class="preset-btns" style="margin-bottom:14px;">
                            <span style="font-size:12px;color:var(--muted);margin-right:4px;">Presets:</span>
                            <button class="preset-btn" onclick="bstPreset(['frog','tree','hill','bird','bat','cat'])">Fig 2.8(a) — balanced</button>
                            <button class="preset-btn" onclick="bstPreset(['bat','bird','cat','frog','hill','tree'])">Fig 2.8(b) — degenerate</button>
                            <button class="preset-btn" onclick="bstPreset(['sum','x3','count','x210','x','x33'])">Ex 2.3.1(c)</button>
                        </div>
                        <div class="vis-canvas">
                            <svg id="bst-svg" class="vis-svg" width="700" height="240"></svg>
                        </div>
                        <div class="vis-log" id="bst-log">Insert a word to start building the tree.</div>
                    </div>
                </div>
            </section>





            <div class="chapter-nav">
                <a href="c2_3_1.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>2.3.1 Sequential Search</div>
                </a>
                <a href="?complete=13" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>2.3.3 Hash Tables</div>
                    <span>→</span>
                </a>
            </div>

        </article>
    </div>

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

        // ════════════════════════════════════════════════════════
        // BST Visualiser
        // ════════════════════════════════════════════════════════
        let bstRoot = null;
        let bstCount = 0;

        class BSTNode {
            constructor(val) {
                this.val = val;
                this.left = null;
                this.right = null;
            }
        }

        function bstInsert() {
            const w = document.getElementById('bst-input').value.trim().toLowerCase();
            if (!w) return;
            document.getElementById('bst-input').value = '';
            const log = document.getElementById('bst-log');
            const result = bstInsertNode(w);
            log.textContent = result.msg;
            bstCount = bstCountNodes(bstRoot);
            document.getElementById('bst-stats').textContent = bstCount + ' node' + (bstCount === 1 ? '' : 's');
            bstDraw();
        }

        function bstInsertNode(w) {
            if (!bstRoot) {
                bstRoot = new BSTNode(w);
                return {
                    msg: `"${w}" inserted as root.`
                };
            }
            let cur = bstRoot,
                path = [];
            while (cur) {
                path.push(cur.val);
                if (w < cur.val) {
                    if (!cur.left) {
                        cur.left = new BSTNode(w);
                        return {
                            msg: `"${w}" < "${cur.val}" → inserted as left child. Path: ${path.join(' → ')}`
                        };
                    }
                    cur = cur.left;
                } else if (w > cur.val) {
                    if (!cur.right) {
                        cur.right = new BSTNode(w);
                        return {
                            msg: `"${w}" > "${cur.val}" → inserted as right child. Path: ${path.join(' → ')}`
                        };
                    }
                    cur = cur.right;
                } else return {
                    msg: `"${w}" already in tree (found at: ${path.join(' → ')})`
                };
            }
        }

        function bstCountNodes(n) {
            return n ? 1 + bstCountNodes(n.left) + bstCountNodes(n.right) : 0;
        }

        function bstReset() {
            bstRoot = null;
            bstCount = 0;
            document.getElementById('bst-stats').textContent = '0 nodes';
            document.getElementById('bst-log').textContent = 'Insert a word to start building the tree.';
            bstDraw();
        }

        function bstPreset(words) {
            bstReset();
            words.forEach(w => {
                bstInsertNode(w);
            });
            bstCount = bstCountNodes(bstRoot);
            document.getElementById('bst-stats').textContent = bstCount + ' nodes';
            document.getElementById('bst-log').textContent = 'Preset loaded: ' + words.join(', ');
            bstDraw();
        }

        function bstDraw() {
            const svg = document.getElementById('bst-svg');
            svg.innerHTML = '';
            if (!bstRoot) return;
            const W = 700,
                H = 240;
            svg.setAttribute('width', W);
            svg.setAttribute('height', H);
            const depth = bstDepth(bstRoot);
            const levelH = Math.min(H / (depth + 1), 60);

            function pos(node, level, lo, hi) {
                if (!node) return;
                const x = (lo + hi) / 2,
                    y = level * levelH + 30;
                node._x = x;
                node._y = y;
                pos(node.left, level + 1, lo, (lo + hi) / 2);
                pos(node.right, level + 1, (lo + hi) / 2, hi);
            }
            pos(bstRoot, 0, 0, W);

            function drawEdges(node) {
                if (!node) return;
                if (node.left) {
                    drawLine(svg, node._x, node._y, node.left._x, node.left._y);
                    drawEdges(node.left);
                }
                if (node.right) {
                    drawLine(svg, node._x, node._y, node.right._x, node.right._y);
                    drawEdges(node.right);
                }
            }

            function drawNodes(node) {
                if (!node) return;
                const isLeaf = !node.left && !node.right;
                const r = 20;
                const c = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                c.setAttribute('cx', node._x);
                c.setAttribute('cy', node._y);
                c.setAttribute('r', r);
                c.setAttribute('fill', isLeaf ? 'rgba(16,185,129,.1)' : 'rgba(99,102,241,.1)');
                c.setAttribute('stroke', isLeaf ? '#10B981' : '#6366F1');
                c.setAttribute('stroke-width', node === bstRoot ? '2.5' : '1.8');
                svg.appendChild(c);
                const t = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                t.setAttribute('x', node._x);
                t.setAttribute('y', node._y + 4);
                t.setAttribute('text-anchor', 'middle');
                t.setAttribute('fill', isLeaf ? '#10B981' : '#818CF8');
                t.setAttribute('font-size', node.val.length > 5 ? '9' : '11');
                t.setAttribute('font-weight', '700');
                t.setAttribute('font-family', 'JetBrains Mono');
                t.textContent = node.val;
                svg.appendChild(t);
                drawNodes(node.left);
                drawNodes(node.right);
            }
            drawEdges(bstRoot);
            drawNodes(bstRoot);
        }

        function drawLine(svg, x1, y1, x2, y2) {
            const l = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            l.setAttribute('x1', x1);
            l.setAttribute('y1', y1);
            l.setAttribute('x2', x2);
            l.setAttribute('y2', y2);
            l.setAttribute('stroke', '#6366F1');
            l.setAttribute('stroke-width', '1.5');
            l.setAttribute('opacity', '.4');
            svg.appendChild(l);
        }

        function bstDepth(n) {
            return n ? 1 + Math.max(bstDepth(n.left), bstDepth(n.right)) : 0;
        }
    </script>

</body>

</html>