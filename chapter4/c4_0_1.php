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
$page    = 2;
$nextPage = "c4_0_2.php"; // next sub-page (adjust as needed)

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
                <li><a href="#relations" class="toc-link">4.0.1 Relations</a></li>
                <li><a href="#closure" class="toc-link">4.0.2 Reflexive Transitive Closure</a></li>
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
            <!-- SECTION 4.0.1 – Relations                          -->
            <!-- ─────────────────────────────────────────────────── -->
            <section id="relations">
                <h2><span class="sec-icon">🔗</span> Relations</h2>

                <p>
                    Whether working with top down or bottom up parsing algorithms, we will always be looking
                    for ways to <strong>automate</strong> the process of producing a parser from the grammar.
                    This requires mathematics involving <em>sets</em> and <em>relations</em>.
                </p>

                <div class="note-box key">
                    <span class="box-icon">📖</span>
                    <div>
                        <strong>Definition — Relation:</strong> A <em>relation</em> is a
                        <strong>set of ordered pairs</strong>. Each pair is written in parentheses,
                        separated by a comma: <code>(a, b)</code>.
                        Note that <code>(a, b)</code> and <code>(b, a)</code> are <em>not</em> the same pair.
                    </div>
                </div>

                <p>Example relation <strong>R1</strong>:</p>

                <div class="grammar-box" style="line-height:2.2">
                    <span style="color:#94E2D5">R1 =</span> { <span style="color:#CBA6F7">(a,b)</span>,
                    <span style="color:#CBA6F7">(c,d)</span>,
                    <span style="color:#CBA6F7">(b,a)</span>,
                    <span style="color:#CBA6F7">(b,c)</span>,
                    <span style="color:#CBA6F7">(c,c)</span> }
                </div>

                <p>
                    Sometimes the name of the relation is used directly to list elements.
                    For example, the familiar <em>less-than</em> relation for integers:
                </p>

                <div class="grammar-box" style="line-height:2.2">
                    <span style="color:#FAB387">4 &lt; 9</span><br>
                    <span style="color:#FAB387">5 &lt; 22</span><br>
                    <span style="color:#FAB387">2 &lt; 3</span><br>
                    <span style="color:#FAB387">-3 &lt; 0</span>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────────── -->
            <!-- SECTION 4.0.2 – Reflexive Transitive Closure       -->
            <!-- ─────────────────────────────────────────────────── -->
            <section id="closure">
                <h2><span class="sec-icon">♾️</span> Reflexive Transitive Closure</h2>

                <div class="note-box key">
                    <span class="box-icon">📖</span>
                    <div>
                        <strong>Definition — R*:</strong>
                        If <code>R</code> is a relation, then the <strong>reflexive transitive closure</strong>
                        of <code>R</code> is designated <code>R*</code>. It is a relation built from
                        the same elements of <code>R</code> with three properties:
                    </div>
                </div>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Property</th>
                            <th>Rule</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><strong>Original</strong></td>
                            <td>All pairs of <code>R</code> are also in <code>R*</code>.</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><strong>Transitive</strong></td>
                            <td>If <code>(a,b)</code> and <code>(b,c)</code> are in <code>R*</code>,
                                then <code>(a,c)</code> is in <code>R*</code>.</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td><strong>Reflexive</strong></td>
                            <td>If <code>a</code> appears in any pair of <code>R</code>,
                                then <code>(a,a)</code> is in <code>R*</code>.</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Watch out:</strong> In rule 2 (transitivity) we search the pairs of
                        <code>R*</code>, <em>not</em> just the original <code>R</code>. This means
                        that newly added transitive pairs must <em>themselves</em> be checked — the
                        process continues until no new pairs can be added.
                    </div>
                </div>
            </section>

       


            <!-- ── Chapter nav ───────────────────────────────────── -->
            <div class="chapter-nav">
                <a href="c4_0_0.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Back to</span>Top Down Parsing</div>
                </a>
                <a href="?complete=2" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.0.3 Closure Visualised
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

        // ── Mini quiz ─────────────────────────────────────────────────
        function checkQuiz(el, correct) {
            if (document.querySelector('.quiz-opt.answered')) return;
            document.querySelectorAll('.quiz-opt').forEach(o => o.classList.add('answered'));
            el.classList.add(correct ? 'correct' : 'wrong');
            const fb = document.getElementById('qfb');
            fb.className = 'quiz-feedback show ' + (correct ? 'ok' : 'bad');
            fb.textContent = correct ?
                '✅ Correct! (x,y) and (y,z) chain together to give the transitive pair (x,z).' :
                '❌ Not quite. (x,a) and (x,x) or (y,y) come from reflexivity, not transitivity. Transitivity chains two overlapping pairs.';
        }
    </script>

</body>

</html>