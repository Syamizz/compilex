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
$page    = 1;
$nextPage = "c4_1_1.php"; // next sub-page (adjust as needed)

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
    <title>Chapter 4 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Re-use the existing chapter CSS (same stylesheet as chapter 1) -->
    <link rel="stylesheet" href="../css/c1_1.css">

    <style>
        /* ── Relation / pair display ─────────────────────────── */
        .pair-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 8px;
            margin: 16px 0;
        }

        .pair-chip {
            background: var(--purple-s, #EEF2FF);
            color: var(--purple, #6366F1);
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 8px;
            text-align: center;
            border: 1.5px solid transparent;
            transition: border-color .2s, background .2s;
        }

        .pair-chip.from-r {
            background: #EEF2FF;
            border-color: rgba(99, 102, 241, .25);
        }

        .pair-chip.transitive {
            background: #FFFBEB;
            color: #92400E;
            border-color: rgba(245, 158, 11, .35);
        }

        .pair-chip.reflexive {
            background: #F0FDF4;
            color: #065F46;
            border-color: rgba(16, 185, 129, .35);
        }

        /* ── Closure stepper ─────────────────────────────────── */
        .closure-stepper {
            background: var(--card, #fff);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(99, 102, 241, .09);
            margin: 24px 0;
        }

        .cs-header {
            background: var(--purple-s, #EEF2FF);
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cs-header h4 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: var(--purple, #6366F1);
            margin: 0;
        }

        .cs-body {
            padding: 22px;
        }

        .cs-step-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--purple, #6366F1);
            margin-bottom: 6px;
        }

        .cs-step-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 16px;
            color: var(--text, #1E1B4B);
            margin-bottom: 8px;
        }

        .cs-step-desc {
            font-size: 13.5px;
            color: var(--muted, #6B7280);
            line-height: 1.65;
            margin-bottom: 14px;
        }

        .cs-pairs-wrap {
            margin-top: 10px;
        }

        .cs-nav {
            display: flex;
            gap: 10px;
            padding: 14px 22px;
            background: var(--purple-s, #EEF2FF);
            border-top: 1px solid rgba(99, 102, 241, .12);
        }

        /* ── Grammar rule display ────────────────────────────── */
        .grammar-box {
            background: var(--code-bg, #1A1830);
            border-radius: 12px;
            padding: 20px 24px;
            margin: 16px 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13.5px;
            color: #CDD6F4;
            line-height: 2;
        }

        .grammar-box .rule-num {
            color: rgba(205, 214, 244, .45);
            user-select: none;
            margin-right: 10px;
        }

        .grammar-box .nt {
            color: #CBA6F7;
        }

        /* nonterminals – purple */
        .grammar-box .t {
            color: #A6E3A1;
        }

        /* terminals – green */
        .grammar-box .arr {
            color: #94E2D5;
        }

        /* arrow – teal */

        /* ── Derivation sequence ─────────────────────────────── */
        .deriv-seq {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px;
            margin: 16px 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
        }

        .deriv-seq .ds-step {
            background: var(--purple-s, #EEF2FF);
            color: var(--purple, #6366F1);
            padding: 5px 10px;
            border-radius: 8px;
        }

        .deriv-seq .ds-step .underline {
            text-decoration: underline;
            text-decoration-color: #6366F1;
        }

        .deriv-seq .ds-arrow {
            color: var(--muted, #6B7280);
            font-size: 16px;
            padding: 0 2px;
        }

        .deriv-seq .ds-rule {
            font-size: 10px;
            background: rgba(99, 102, 241, .12);
            color: var(--purple, #6366F1);
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        /* ── Tree SVG wrapper ────────────────────────────────── */
        .tree-wrap {
            background: var(--card, #fff);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: 14px;
            padding: 20px;
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .tree-wrap figcaption {
            font-size: 12px;
            color: var(--muted, #6B7280);
            margin-top: 10px;
            text-align: center;
        }

        /* ── Relation table ──────────────────────────────────── */
        .rel-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            margin: 16px 0;
        }

        .rel-table th {
            background: var(--purple-s, #EEF2FF);
            color: var(--purple, #6366F1);
            padding: 8px 14px;
            text-align: left;
            font-weight: 700;
            font-size: 12px;
        }

        .rel-table td {
            padding: 7px 14px;
            border-bottom: 1px solid #EEF2FF;
            color: var(--text, #1E1B4B);
        }

        .rel-table td.reason {
            color: var(--muted, #6B7280);
            font-style: italic;
            font-size: 12px;
        }

        .rel-table tr.transitive td {
            background: #FFFBEB;
        }

        .rel-table tr.reflexive td {
            background: #F0FDF4;
        }

        /* legend pills */
        .legend-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin: 12px 0 20px;
        }

        .legend-pill {
            font-size: 12px;
            padding: 4px 12px;
            border-radius: 20px;
            font-family: 'JetBrains Mono', monospace;
        }

        .legend-pill.original {
            background: #EEF2FF;
            color: #4338CA;
        }

        .legend-pill.transitive {
            background: #FFFBEB;
            color: #92400E;
        }

        .legend-pill.reflexive {
            background: #F0FDF4;
            color: #065F46;
        }
    </style>
</head>

<body>

    <?php include 'navbar_c4.php'; ?>

    <div class="note-container">

        <!-- ── Sidebar TOC ─────────────────────────────────────── -->
        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#intro" class="toc-link">4.0 Top Down Parsing</a></li>
                <li><a href="#relations" class="toc-link">4.0.1 Relations</a></li>
                <li><a href="#closure" class="toc-link">4.0.2 Reflexive Transitive Closure</a></li>
                <li><a href="#closure-vis" class="toc-link">4.0.3 Closure Visualised</a></li>
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
            <!-- SECTION 4.0 – Introduction                         -->
            <!-- ─────────────────────────────────────────────────── -->
            <section id="intro">
                <h2><span class="sec-icon">🔍</span> What is Top Down Parsing?</h2>

                <p>
                    The <strong>parsing problem</strong> was defined in Section 3.2 as follows: given a grammar and an
                    input string, determine whether the string is in the language of the grammar, and — if so —
                    determine its structure.
                </p>
                <p>
                    Parsing algorithms are usually classified as either <strong>top down</strong> or
                    <strong>bottom up</strong>, which refers to the sequence in which a derivation tree is
                    built or traversed. In this chapter we consider only <em>top down</em> algorithms.
                </p>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        <strong>Key idea:</strong> In a top down algorithm the parser begins at the
                        <em>root</em> of the derivation tree (the start symbol) and works downwards, choosing
                        grammar rules that match the input one symbol at a time.
                    </div>
                </div>

                <h3>Grammar G8 — Running Example</h3>
                <p>Throughout this chapter we use the following grammar:</p>

                <div class="grammar-box">
                    <span class="rule-num">G8:</span><br>
                    <span class="rule-num">1.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="t">a</span> <span class="nt">S</span> <span class="t">b</span><br>
                    <span class="rule-num">2.</span> <span class="nt">S</span> <span class="arr">→</span> <span class="t">b</span> <span class="nt">A</span> <span class="t">c</span><br>
                    <span class="rule-num">3.</span> <span class="nt">A</span> <span class="arr">→</span> <span class="t">b</span> <span class="nt">S</span><br>
                    <span class="rule-num">4.</span> <span class="nt">A</span> <span class="arr">→</span> <span class="t">a</span>
                </div>

                <div class="note-box pro">
                    <span class="box-icon">📌</span>
                    <div>
                        <strong>Convention:</strong> uppercase letters (<code>S</code>, <code>A</code>)
                        are <em>nonterminals</em>; lowercase letters (<code>a</code>, <code>b</code>,
                        <code>c</code>) are <em>terminals</em>. The arrow <code>→</code> means
                        "can be replaced by".
                    </div>
                </div>

                <h3>Derivation Tree for <code>abbbaccb</code></h3>
                <p>
                    Figure 4.1 shows the derivation tree produced when G8 is used to derive
                    <code>abbbaccb</code>. A top down algorithm builds this tree from the root
                    <code>S</code> downwards.
                </p>

                <!-- SVG derivation tree (Figure 4.1) -->
                <div class="tree-wrap">
                    <svg viewBox="0 0 420 280" width="380" xmlns="http://www.w3.org/2000/svg"
                        font-family="JetBrains Mono, monospace" font-size="14">

                        <!-- edges -->
                        <!-- S → a S b (level 1) -->
                        <line x1="210" y1="30" x2="100" y2="80" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="210" y1="30" x2="210" y2="80" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="210" y1="30" x2="320" y2="80" stroke="#6366F1" stroke-width="1.5" />

                        <!-- S → b A c (level 2) -->
                        <line x1="210" y1="95" x2="140" y2="145" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="210" y1="95" x2="210" y2="145" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="210" y1="95" x2="280" y2="145" stroke="#6366F1" stroke-width="1.5" />

                        <!-- A → b S (level 3) -->
                        <line x1="210" y1="160" x2="170" y2="210" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="210" y1="160" x2="250" y2="210" stroke="#6366F1" stroke-width="1.5" />

                        <!-- S → b A c (level 4) -->
                        <line x1="250" y1="225" x2="200" y2="255" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="250" y1="225" x2="250" y2="255" stroke="#6366F1" stroke-width="1.5" />
                        <line x1="250" y1="225" x2="300" y2="255" stroke="#6366F1" stroke-width="1.5" />

                        <!-- nodes -->
                        <!-- L0: S -->
                        <circle cx="210" cy="22" r="14" fill="#6366F1" />
                        <text x="210" y="27" text-anchor="middle" fill="white" font-weight="700">S</text>

                        <!-- L1: a  S  b -->
                        <circle cx="100" cy="88" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="100" y="93" text-anchor="middle" fill="#065F46" font-weight="600">a</text>

                        <circle cx="210" cy="88" r="14" fill="#6366F1" />
                        <text x="210" y="93" text-anchor="middle" fill="white" font-weight="700">S</text>

                        <circle cx="320" cy="88" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="320" y="93" text-anchor="middle" fill="#065F46" font-weight="600">b</text>

                        <!-- L2: b  A  c -->
                        <circle cx="140" cy="153" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="140" y="158" text-anchor="middle" fill="#065F46" font-weight="600">b</text>

                        <circle cx="210" cy="153" r="14" fill="#CBA6F7" opacity=".9" />
                        <text x="210" y="158" text-anchor="middle" fill="#3730A3" font-weight="700">A</text>

                        <circle cx="280" cy="153" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="280" y="158" text-anchor="middle" fill="#065F46" font-weight="600">c</text>

                        <!-- L3: b  S -->
                        <circle cx="170" cy="218" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="170" y="223" text-anchor="middle" fill="#065F46" font-weight="600">b</text>

                        <circle cx="250" cy="218" r="14" fill="#6366F1" />
                        <text x="250" y="223" text-anchor="middle" fill="white" font-weight="700">S</text>

                        <!-- L4: b  A  c -->
                        <circle cx="200" cy="263" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="200" y="268" text-anchor="middle" fill="#065F46" font-weight="600">b</text>

                        <circle cx="250" cy="263" r="14" fill="#CBA6F7" opacity=".9" />
                        <text x="250" y="268" text-anchor="middle" fill="#3730A3" font-weight="700">A</text>

                        <circle cx="300" cy="263" r="14" fill="#A6E3A1" opacity=".9" />
                        <text x="300" y="268" text-anchor="middle" fill="#065F46" font-weight="600">c</text>
                    </svg>
                    <figcaption>Figure 4.1 — Derivation tree for <code>abbbaccb</code> using grammar G8</figcaption>
                </div>

                <h3>Sequence of Events (Figure 4.2)</h3>
                <p>
                    The derivation below shows exactly which rule is applied at each expansion step.
                    Underlined symbols have already been matched by the parser.
                </p>

                <div class="deriv-seq">
                    <span class="ds-step"><span class="nt" style="color:#6366F1">S</span></span>
                    <span class="ds-arrow">⇒</span>
                    <span class="ds-rule">1</span>
                    <span class="ds-step"><span class="underline">a</span>Sb</span>
                    <span class="ds-arrow">⇒</span>
                    <span class="ds-rule">2</span>
                    <span class="ds-step"><span class="underline">ab</span>Acb</span>
                    <span class="ds-arrow">⇒</span>
                    <span class="ds-rule">3</span>
                    <span class="ds-step"><span class="underline">abb</span>Scb</span>
                    <span class="ds-arrow">⇒</span>
                    <span class="ds-rule">2</span>
                    <span class="ds-step"><span class="underline">abbb</span>Accb</span>
                    <span class="ds-arrow">⇒</span>
                    <span class="ds-rule">4</span>
                    <span class="ds-step"><span class="underline">abbbaccb</span> ✓</span>
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Important:</strong> At each step, the parser examines the
                        <em>next unread input symbol</em> and compares it with the first symbol
                        on the right-hand side of available rules. G8 is simple enough that one
                        symbol of look-ahead is always sufficient to decide which rule to apply.
                    </div>
                </div>
            </section>

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
                <a href="../home.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Back to</span>Home</div>
                </a>
                <a href="?complete=1" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.1 EZ Grammars
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