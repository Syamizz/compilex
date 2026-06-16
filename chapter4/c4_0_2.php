<?php
session_start();
include '../dbconn.php';
include '../save_progress.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$chapter = 4;
$page    = 3;
$nextPage = "c4_1_0.php";

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
    <title>Chapter 4 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Re-use the existing chapter CSS (same stylesheet as chapter 1) -->
    <link rel="stylesheet" href="../css/c1_1.css">

    <link rel="stylesheet" href="../css/c4/c4_0.css">
</head>

<body>

    <?php include 'navbar_c4.php'; ?>

    <div class="note-container">

        <!-- ── Sidebar TOC ─────────────────────────────────────── -->
        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#closure-vis" class="toc-link">4.0.3 Visualing Relations</a></li>
                <li><a href="#sample" class="toc-link">Sample Problem 4.0</a></li>
            </ul>
            <div class="toc-progress">
                <div class="toc-progress-label">
                    <span>Progress</span>
                    <span id="pct-label">0%</span>
                </div>
                <div class="toc-bar">
                    <div class="toc-bar-fill" id="toc-bar"></div>
                </div>
            </div>
        </nav>

        <!-- ── Main content ──────────────────────────────────── -->
        <article class="content">

            <header class="content-header">
                <div class="chapter-tag">📘 Chapter 4</div>
                <h1>Top Down Parsing</h1>
                <div class="metadata">
                    <span>⏱ 20 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>⚙️ Parsing Theory</span>
                </div>
            </header>



            <!-- ─────────────────────────────────────────────────── -->
            <!-- SECTION 4.0.3 – Closure Visualised (Figure 4.3)    -->
            <!-- ─────────────────────────────────────────────────── -->
            <section id="closure-vis">
                <h2><span class="sec-icon">🗺️</span> Visualising Relations</h2>

                <p>
                    A relation can be drawn as a directed graph, where each element is a node
                    and each pair <code>(x, y)</code> is an arrow from <code>x</code> to <code>y</code>.
                </p>

                <!-- SVG Figure 4.3 -->
                <div class="tree-wrap">
                    <svg viewBox="0 0 380 200" width="360" xmlns="http://www.w3.org/2000/svg"
                        font-family="JetBrains Mono, monospace" font-size="13">

                        <!-- ── Transitive chain a→b→c ── -->
                        <!-- arrows -->
                        <!-- a→b -->
                        <line x1="76" y1="70" x2="150" y2="70" stroke="#6366F1" stroke-width="1.8" marker-end="url(#arr)" />
                        <!-- b→c -->
                        <line x1="186" y1="70" x2="254" y2="70" stroke="#6366F1" stroke-width="1.8" marker-end="url(#arr)" />
                        <!-- transitive a→c (curved) -->
                        <path d="M 76 60 Q 168 20 254 60" fill="none" stroke="#F59E0B" stroke-width="1.6"
                            stroke-dasharray="5,3" marker-end="url(#arrAmber)" />

                        <!-- nodes -->
                        <circle cx="62" cy="70" r="18" fill="#EEF2FF" stroke="#6366F1" stroke-width="1.5" />
                        <text x="62" y="75" text-anchor="middle" fill="#6366F1" font-weight="700">a</text>

                        <circle cx="168" cy="70" r="18" fill="#EEF2FF" stroke="#6366F1" stroke-width="1.5" />
                        <text x="168" y="75" text-anchor="middle" fill="#6366F1" font-weight="700">b</text>

                        <circle cx="268" cy="70" r="18" fill="#EEF2FF" stroke="#6366F1" stroke-width="1.5" />
                        <text x="268" y="75" text-anchor="middle" fill="#6366F1" font-weight="700">c</text>

                        <!-- label -->
                        <text x="310" y="74" fill="#374151" font-size="12">Transitive</text>
                        <text x="132" y="18" fill="#F59E0B" font-size="11">(a,c) added</text>

                        <!-- ── Reflexive loop on a ── -->
                        <path d="M 62 148 C 28 128 28 178 62 158" fill="none" stroke="#10B981" stroke-width="1.8"
                            marker-end="url(#arrGreen)" />
                        <circle cx="62" cy="153" r="18" fill="#F0FDF4" stroke="#10B981" stroke-width="1.5" />
                        <text x="62" y="158" text-anchor="middle" fill="#065F46" font-weight="700">a</text>
                        <text x="110" y="157" fill="#374151" font-size="12">Reflexive</text>
                        <text x="110" y="172" fill="#10B981" font-size="11">(a,a) added</text>

                        <!-- arrow markers -->
                        <defs>
                            <marker id="arr" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
                                <path d="M0,0 L0,6 L8,3 z" fill="#6366F1" />
                            </marker>
                            <marker id="arrAmber" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
                                <path d="M0,0 L0,6 L8,3 z" fill="#F59E0B" />
                            </marker>
                            <marker id="arrGreen" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
                                <path d="M0,0 L0,6 L8,3 z" fill="#10B981" />
                            </marker>
                        </defs>
                    </svg>
                    <figcaption>Figure 4.3 — Reflexive and transitive elements added to form R*</figcaption>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        <strong>Reading the diagram:</strong> Solid purple arrows are the original relation.
                        The <span style="color:#F59E0B">amber dashed arrow</span> is a
                        <em>transitive</em> addition, and the
                        <span style="color:#10B981">green self-loop</span> is a
                        <em>reflexive</em> addition.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────────── -->
            <!-- SECTION – Sample Problem 4.0 (Stepper)             -->
            <!-- ─────────────────────────────────────────────────── -->
            <section id="sample">
                <h2><span class="sec-icon">🧪</span> Sample Problem 4.0</h2>

                <p>Show <code>R1*</code> — the reflexive transitive closure of <code>R1</code>.</p>

                <!-- Closure stepper -->
                <div class="closure-stepper">
                    <div class="cs-header">
                        <h4 id="cs-title">Building R1* step-by-step</h4>
                        <span class="step-counter" id="cs-counter">Step 1 of 3</span>
                    </div>
                    <div class="cs-body">
                        <div class="cs-step-label" id="cs-label">STEP 1 — Start with R1 (Original pairs)</div>
                        <div class="cs-step-title" id="cs-step-title">Copy all pairs from R1 into R1*</div>
                        <div class="cs-step-desc" id="cs-desc">
                            By property 1, every pair already in R1 is automatically in R1*.
                            These are the 5 original pairs we begin with.
                        </div>
                        <div class="legend-row">
                            <span class="legend-pill original">■ Original (from R1)</span>
                            <span class="legend-pill transitive">■ Transitive</span>
                            <span class="legend-pill reflexive">■ Reflexive</span>
                        </div>
                        <div class="cs-pairs-wrap" id="cs-pairs"></div>
                    </div>
                    <div class="cs-nav">
                        <button class="step-btn" id="cs-prev" onclick="closureMove(-1)" disabled>← Back</button>
                        <button class="step-btn primary" id="cs-next" onclick="closureMove(1)">Next →</button>
                    </div>
                </div>

                <!-- Full answer table -->
                <h3>Complete R1* Table</h3>
                <p>All pairs in the closure, grouped by how they were added:</p>

                <table class="rel-table">
                    <thead>
                        <tr>
                            <th>Pair</th>
                            <th>Source</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>(a,b)</code></td>
                            <td>R1</td>
                            <td class="reason">Original pair</td>
                        </tr>
                        <tr>
                            <td><code>(c,d)</code></td>
                            <td>R1</td>
                            <td class="reason">Original pair</td>
                        </tr>
                        <tr>
                            <td><code>(b,a)</code></td>
                            <td>R1</td>
                            <td class="reason">Original pair</td>
                        </tr>
                        <tr>
                            <td><code>(b,c)</code></td>
                            <td>R1</td>
                            <td class="reason">Original pair</td>
                        </tr>
                        <tr>
                            <td><code>(c,c)</code></td>
                            <td>R1</td>
                            <td class="reason">Original pair</td>
                        </tr>
                        <tr class="transitive">
                            <td><code>(a,c)</code></td>
                            <td>Transitive</td>
                            <td class="reason">(a,b) + (b,c) → (a,c)</td>
                        </tr>
                        <tr class="transitive">
                            <td><code>(b,d)</code></td>
                            <td>Transitive</td>
                            <td class="reason">(b,c) + (c,d) → (b,d)</td>
                        </tr>
                        <tr class="transitive">
                            <td><code>(a,d)</code></td>
                            <td>Transitive</td>
                            <td class="reason">(a,c) + (c,d) → (a,d)</td>
                        </tr>
                        <tr class="reflexive">
                            <td><code>(a,a)</code></td>
                            <td>Reflexive</td>
                            <td class="reason">a appears in a pair</td>
                        </tr>
                        <tr class="reflexive">
                            <td><code>(b,b)</code></td>
                            <td>Reflexive</td>
                            <td class="reason">b appears in a pair</td>
                        </tr>
                        <tr class="reflexive">
                            <td><code>(d,d)</code></td>
                            <td>Reflexive</td>
                            <td class="reason">d appears in a pair</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box key">
                    <span class="box-icon">✅</span>
                    <div>
                        <strong>Why not (c,a) or (d,a)?</strong> Relations are <em>directed</em>
                        — we can only chain pairs where the <em>second</em> element of one matches the
                        <em>first</em> element of the next. <code>(c,d)</code> exists but there is no
                        pair starting with <code>d</code> (other than the reflexive <code>(d,d)</code>),
                        so the chain stops at <code>d</code>.
                    </div>
                </div>
            </section>



            <!-- ── Chapter nav ───────────────────────────────────── -->
            <div class="chapter-nav">
                <a href="c4_0_1.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>4.0.1 Relations & 4.0.2 Reflexive Transitive Closure</div>
                </a>
                <a href="?complete=3" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.1 Simple Grammars
                    </div>
                    <span>→</span>
                </a>
            </div>

        </article>
    </div><!-- /.note-container -->

    <!-- ── Scripts ─────────────────────────────────────────────── -->
    <script>
        // ── TOC scroll progress ──────────────────────────────────────
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

        // ── Closure stepper data ─────────────────────────────────────
        const closureSteps = [{
                label: 'STEP 1 — Original pairs from R1',
                title: 'Copy all pairs from R1 into R1*',
                desc: 'By property 1, every pair already in R1 is automatically in R1*. These are our 5 starting pairs.',
                pairs: [{
                        text: '(a,b)',
                        cls: 'from-r'
                    },
                    {
                        text: '(c,d)',
                        cls: 'from-r'
                    },
                    {
                        text: '(b,a)',
                        cls: 'from-r'
                    },
                    {
                        text: '(b,c)',
                        cls: 'from-r'
                    },
                    {
                        text: '(c,c)',
                        cls: 'from-r'
                    },
                ]
            },
            {
                label: 'STEP 2 — Apply Transitivity',
                title: 'Add transitive pairs to R1*',
                desc: 'Check every pair combination in R1*. If (x,y) and (y,z) both exist, add (x,z). Repeat until no new pairs appear.',
                pairs: [{
                        text: '(a,b)',
                        cls: 'from-r'
                    },
                    {
                        text: '(c,d)',
                        cls: 'from-r'
                    },
                    {
                        text: '(b,a)',
                        cls: 'from-r'
                    },
                    {
                        text: '(b,c)',
                        cls: 'from-r'
                    },
                    {
                        text: '(c,c)',
                        cls: 'from-r'
                    },
                    {
                        text: '(a,c)',
                        cls: 'transitive',
                        note: '← (a,b)+(b,c)'
                    },
                    {
                        text: '(b,d)',
                        cls: 'transitive',
                        note: '← (b,c)+(c,d)'
                    },
                    {
                        text: '(a,d)',
                        cls: 'transitive',
                        note: '← (a,c)+(c,d)'
                    },
                ]
            },
            {
                label: 'STEP 3 — Apply Reflexivity',
                title: 'Add reflexive pairs (a,a) for every element a',
                desc: 'Every symbol that appears in any pair gets a self-loop (a,a) in R*. The symbols present are: a, b, c, d.',
                pairs: [{
                        text: '(a,b)',
                        cls: 'from-r'
                    },
                    {
                        text: '(c,d)',
                        cls: 'from-r'
                    },
                    {
                        text: '(b,a)',
                        cls: 'from-r'
                    },
                    {
                        text: '(b,c)',
                        cls: 'from-r'
                    },
                    {
                        text: '(c,c)',
                        cls: 'from-r'
                    },
                    {
                        text: '(a,c)',
                        cls: 'transitive'
                    },
                    {
                        text: '(b,d)',
                        cls: 'transitive'
                    },
                    {
                        text: '(a,d)',
                        cls: 'transitive'
                    },
                    {
                        text: '(a,a)',
                        cls: 'reflexive',
                        note: '← reflexive'
                    },
                    {
                        text: '(b,b)',
                        cls: 'reflexive',
                        note: '← reflexive'
                    },
                    {
                        text: '(d,d)',
                        cls: 'reflexive',
                        note: '← reflexive'
                    },
                ]
            }
        ];

        let csStep = 0;

        function renderClosure() {
            const s = closureSteps[csStep];
            document.getElementById('cs-label').textContent = s.label;
            document.getElementById('cs-step-title').textContent = s.title;
            document.getElementById('cs-desc').textContent = s.desc;
            document.getElementById('cs-counter').textContent =
                `Step ${csStep + 1} of ${closureSteps.length}`;

            const wrap = document.getElementById('cs-pairs');
            wrap.innerHTML = '<div class="pair-grid">' +
                s.pairs.map(p =>
                    `<div class="pair-chip ${p.cls}" title="${p.note || ''}">${p.text}</div>`
                ).join('') +
                '</div>';

            document.getElementById('cs-prev').disabled = csStep === 0;
            const nxt = document.getElementById('cs-next');
            nxt.disabled = csStep === closureSteps.length - 1;
            nxt.textContent = csStep === closureSteps.length - 1 ? 'Done ✓' : 'Next →';
            nxt.className = 'step-btn' + (csStep === closureSteps.length - 1 ? '' : ' primary');
        }

        function closureMove(dir) {
            csStep = Math.max(0, Math.min(closureSteps.length - 1, csStep + dir));
            renderClosure();
        }

        renderClosure();

        
    </script>

</body>

</html>