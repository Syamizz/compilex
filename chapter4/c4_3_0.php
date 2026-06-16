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
$page    = 10;
$nextPage = "c4_3_1.php";

if (isset($_GET['complete']) && $_GET['complete'] == '10') {
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
    <title>Chapter 4.3 - CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c1_1.css">
    <link rel="stylesheet" href="../css/c4/c4_3.css">

    <style>
        /* ── Interactive algorithm steps ── */
        .algo-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 1.25rem;
        }

        .algo-step {
            border: 1px solid #e2e2f0;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
            transition: box-shadow 0.15s;

        }

        .algo-step:hover {
            box-shadow: 0 2px 8px rgba(80, 70, 200, 0.07);
        }

        .algo-step-header {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px 16px;
            cursor: pointer;
            user-select: none;
            background: transparent;
            border: none;
            width: 100%;
            text-align: left;
        }

        .algo-step-header:hover {
            background: #f7f7fd;
        }

        .step-num {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            background: #e6f7ef;
            color: #1a7a4a;
            margin-top: 1px;
            transition: background 0.2s, color 0.2s;
        }

        .algo-step.open .step-num {
            background: #7c6fe0;
            color: #fff;
        }

        .step-title-block {
            flex: 1;
        }

        .step-title {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a2e;
            margin: 0 0 3px;
        }

        .step-desc {
            font-size: 13px;
            color: #666;
            margin: 0;
            
            line-height: 1.5;
        }

        .step-chevron {
            color: #aaa;
            font-size: 18px;
            margin-top: 4px;
            flex-shrink: 0;
            transition: transform 0.2s;
            line-height: 1;
        }

        .algo-step.open .step-chevron {
            transform: rotate(180deg);
        }

        /* ── Example panel ── */
        .example-panel {
            display: none;
            border-top: 1px solid #e8e8f5;
            padding: 14px 16px 16px 16px;
            background: #f9f9fd;
            width: 670px;

        }

        .algo-step.open .example-panel {
            display: block;
        }

        .ex-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #999;
            margin: 0 0 8px;
            width: 200px;
            box-sizing: border-box;
            text-align: center;
            
        }

        .ex-code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12.5px;
            line-height: 1.9;
            background: #fff;
            border: 1px solid #e2e2f0;
            border-radius: 8px;
            padding: 10px 14px;
            width: 300px;
            margin-bottom: 10px;
            color: #222;
            white-space: pre-line;
            word-break: normal;
        }

        .ex-code .nt {
            color: #5a4fcc;
            font-weight: 500;
        }

        .ex-code .t {
            color: #1a7a4a;
        }

        .ex-code .arr {
            color: #999;
        }

        .ex-code strong {
            color: #1a1a2e;
        }

        .ex-result {
            font-size: 13px;
            color: #333;
            line-height: 1.7;
            width: 300px;
        }

        .ex-result code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            background: #fff;
            padding: 1px 5px;
            border-radius: 4px;
            border: 1px solid #ddd;
            color: #333;
        }

        .ex-row {
            display: block;
            margin-bottom: 4px;
            line-height: 1.8;
            width: 500px;
        }

        .chip {
            display: inline-block;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11.5px;
            padding: 2px 8px;
            border-radius: 4px;
            margin: 2px 2px;
            background: #eae8fc;
            color: #4a3fbf;
            font-weight: 500;
            white-space: nowrap;
        }

        .chip.eps {
            background: #fef3e2;
            color: #a05c00;
        }

        .chip.fol {
            background: #e6f7ef;
            color: #1a7a4a;
        }

        .ex-tip {
            font-size: 12px;
            color: #777;
            margin-top: 10px;
            padding: 6px 10px;
            border-left: 3px solid #7c6fe0;
            background: #f3f1fd;
            border-radius: 0 6px 6px 0;
            display: block;
            line-height: 1.6;
        }

        .ex-tip em {
            color: #5a4fcc;
            font-style: normal;
            font-weight: 500;
        }
    </style>
</head>

<body>

    <?php include 'navbar_c4.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">Contents</div>
            <ul>
                <li><a href="#intro" class="toc-link">4.3 LL(1) Grammars</a></li>
                <li><a href="#g15" class="toc-link">Grammar G15</a></li>
                <li><a href="#algorithm" class="toc-link">Selection Set Algorithm</a></li>
                <li><a href="#g15-result" class="toc-link">G15 Results</a></li>
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

        <article class="content">

            <header class="content-header">
                <div class="chapter-tag">Chapter 4.3</div>
                <h1>LL(1) Grammars</h1>
                <div class="metadata">
                    <span>30 min read</span>
                    <span>Advanced</span>
                    <span>Top Down Parsing</span>
                </div>
            </header>

            <section id="intro">
                <h2><span class="sec-icon">LL</span> What is an LL(1) Grammar?</h2>

                <p>
                    We now generalize the class of grammars that can be parsed top down by allowing
                    rules of the form <code>A &rarr; &alpha;</code>, where <code>&alpha;</code> is any
                    string of terminals and nonterminals. The grammar must still satisfy one important
                    condition: any two rules defining the same nonterminal must have
                    <strong>disjoint selection sets</strong>.
                </p>

                <div class="note-box key">
                    <span class="box-icon">Key</span>
                    <div>
                        A grammar is <strong>LL(1)</strong> if the parser can scan the input from
                        left to right, construct a leftmost derivation, and decide which rule to apply
                        using no more than <strong>one lookahead input symbol</strong>.
                    </div>
                </div>

                <p>
                    Once selection sets have been found, an LL(1) grammar can be parsed using the same
                    style of one-state pushdown machine or recursive descent parser introduced in the
                    previous sections.
                </p>
            </section>

            <section id="g15">
                <h2><span class="sec-icon">G15</span> Running Example</h2>

                <p>
                    The note uses grammar G15 to demonstrate the complete algorithm for finding
                    selection sets of an arbitrary context-free grammar.
                </p>

                <div class="grammar-box">
                    <span class="rule-num">G15:</span><br>
                    <span class="rule-num">1.</span> <span class="nt">S</span> <span class="arr">&rarr;</span> <span class="nt">A</span><span class="nt">B</span><span class="t">c</span><br>
                    <span class="rule-num">2.</span> <span class="nt">A</span> <span class="arr">&rarr;</span> <span class="t">b</span><span class="nt">A</span><br>
                    <span class="rule-num">3.</span> <span class="nt">A</span> <span class="arr">&rarr;</span> <span class="eps">&epsilon;</span><br>
                    <span class="rule-num">4.</span> <span class="nt">B</span> <span class="arr">&rarr;</span> <span class="t">c</span>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">Idea</span>
                    <div>
                        We expect terminal <code>b</code> to be in the selection set for rule 1 because
                        <code>S &rArr; ABc &rArr; bABc</code>. The algorithm below formalizes this idea.
                    </div>
                </div>
            </section>

            <section id="algorithm">
                <h2><span class="sec-icon">12</span> Algorithm to Find Selection Sets</h2>

                <p>
                    The algorithm has twelve steps. The phrase "any string" includes the null string
                    unless otherwise stated. <strong>Click any step</strong> to see it applied to G15.
                </p>

                <div class="algo-list" id="algo-list"></div>

                <div class="note-box pro">
                    <span class="box-icon">Note</span>
                    <div>
                        If the grammar contains no nullable rules, the selection sets can be obtained
                        after step 5, so steps 6 through 11 may be skipped.
                    </div>
                </div>
            </section>

            <section id="g15-result">
                <h2><span class="sec-icon">G15</span> Applying the Algorithm to G15</h2>

                <div class="mini-grid">
                    <div class="mini-card">
                        <h4>Step 1</h4>
                        <p>Nullable rule: rule 3. Nullable nonterminal: <code>A</code>.</p>
                        <div class="set-row">
                            <span class="set-chip epsilon">A &rarr; &epsilon;</span>
                        </div>
                    </div>

                    <div class="mini-card">
                        <h4>Steps 4 and 5</h4>
                        <p>First sets and right-side first sets:</p>
                        <div class="set-row">
                            <span class="set-chip">First(S) = { b, c }</span>
                            <span class="set-chip">First(A) = { b }</span>
                            <span class="set-chip">First(B) = { c }</span>
                            <span class="set-chip">First(ABc) = { b, c }</span>
                        </div>
                    </div>

                    <div class="mini-card">
                        <h4>Step 11</h4>
                        <p>Only nullable nonterminals need follow sets for selection sets.</p>
                        <div class="set-row">
                            <span class="set-chip follow">Fol(A) = { c }</span>
                        </div>
                    </div>
                </div>

                <h3>Important Relations for G15</h3>
                <div class="table-scroll">
                    <table class="machine-table">
                        <thead>
                            <tr>
                                <th>Relation</th>
                                <th>Pairs from the note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="row-head">BDW</td>
                                <td><code>S BDW A</code>, <code>S BDW B</code>, <code>A BDW b</code>, <code>B BDW c</code></td>
                            </tr>
                            <tr>
                                <td class="row-head">BW</td>
                                <td><code>S BW A</code>, <code>S BW B</code>, <code>A BW b</code>, <code>B BW c</code>, <code>S BW b</code>, <code>S BW c</code>, reflexive terminal/nonterminal pairs</td>
                            </tr>
                            <tr>
                                <td class="row-head">FDB</td>
                                <td><code>A FDB B</code>, <code>B FDB c</code></td>
                            </tr>
                            <tr>
                                <td class="row-head">DEO</td>
                                <td><code>c DEO S</code>, <code>A DEO A</code>, <code>b DEO A</code>, <code>c DEO B</code></td>
                            </tr>
                            <tr>
                                <td class="row-head">FB</td>
                                <td><code>A FB B</code>, <code>A FB c</code>, <code>b FB B</code>, <code>b FB c</code>, <code>B FB c</code>, <code>c FB c</code>, <code>S FB ,</code></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Selection Sets for G15</h3>
                <div class="table-scroll">
                    <table class="machine-table">
                        <thead>
                            <tr>
                                <th>Rule</th>
                                <th>Production</th>
                                <th>Selection Set</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="row-head">1</td>
                                <td><code>S &rarr; ABc</code></td>
                                <td><span class="set-chip">{ b, c }</span></td>
                            </tr>
                            <tr>
                                <td class="row-head">2</td>
                                <td><code>A &rarr; bA</code></td>
                                <td><span class="set-chip">{ b }</span></td>
                            </tr>
                            <tr>
                                <td class="row-head">3</td>
                                <td><code>A &rarr; &epsilon;</code></td>
                                <td><span class="set-chip epsilon">Fol(A) = { c }</span></td>
                            </tr>
                            <tr>
                                <td class="row-head">4</td>
                                <td><code>B &rarr; c</code></td>
                                <td><span class="set-chip">{ c }</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="note-box key">
                    <span class="box-icon">Answer</span>
                    <div>
                        G15 is <strong>LL(1)</strong> because the rules defining <code>A</code>
                        have disjoint selection sets: rule 2 has <code>{ b }</code> and rule 3 has
                        <code>{ c }</code>.
                    </div>
                </div>
            </section>



            <div class="chapter-nav">
                <a href="c4_2_2.php" class="nav-btn">
                    <span>&larr;</span>
                    <div><span class="btn-sub">Back to</span>4.2 Quasi-Simple Grammars (Part 3)</div>
                </a>
                <a href="?complete=10" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.3 LL(1) Grammars (Part 2)
                    </div>
                    <span>&rarr;</span>
                </a>
            </div>

        </article>
    </div>

    <script>
        /* ── TOC scroll progress ── */
        const sections = document.querySelectorAll('section[id]');
        const tocLinks = document.querySelectorAll('.toc-link');
        const bar = document.getElementById('toc-bar');
        const pctLabel = document.getElementById('pct-label');

        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;
            const total = document.body.scrollHeight - window.innerHeight;
            const pct = total > 0 ? Math.round((scrolled / total) * 100) : 0;
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

        /* ── Interactive 12-step algorithm ── */
        const algoSteps = [{
                title: "Find nullable rules and nullable nonterminals",
                desc: "A rule is nullable if &epsilon; can be derived from its right side.",
                example: `
                    <div class="ex-label">G15 example</div>
                    <div class="ex-code"><span class="nt">A</span> <span class="arr">&rarr;</span> <span class="t">&epsilon;</span> &nbsp;&larr; nullable rule (rule 3)</div>
                    <div class="ex-result">
                        <div class="ex-row">Nullable rule: <span class="chip eps">rule 3</span></div>
                        <div class="ex-row">Nullable nonterminal: <span class="chip eps">A</span></div>
                        <div class="ex-tip">Only rules that directly produce &epsilon;, or whose right-side symbols are <em>all nullable nonterminals</em>, qualify as nullable.</div>
                    </div>`
            },
            {
                title: "Find Begins Directly With (BDW)",
                desc: "A BDW X if a rule for A can begin directly with symbol X.",
                example: `
                    <div class="ex-label">G15 example</div>
                    <div class="ex-code">Rule 1: <span class="nt">S</span> <span class="arr">&rarr;</span> <span class="nt">A</span> <span class="nt">B</span> <span class="t">c</span> &nbsp;(A is nullable, so B can also be first)</div>
                    <div class="ex-result">
                        <div class="ex-row"><span class="chip">S BDW A</span> — A is the first symbol in rule 1</div>
                        <div class="ex-row"><span class="chip">S BDW B</span> — A is nullable, so B can be first</div>
                        <div class="ex-row"><span class="chip">A BDW b</span> — rule 2: A &rarr; bA</div>
                        <div class="ex-row"><span class="chip">B BDW c</span> — rule 4: B &rarr; c</div>
                    </div>`
            },
            {
                title: "Find Begins With (BW)",
                desc: "BW is the reflexive transitive closure of BDW, plus a BW a for every terminal.",
                example: `
                    <div class="ex-label">G15 example</div>
                    <div class="ex-result">
                        <div class="ex-row">From BDW (direct): <span class="chip">S BW A</span> <span class="chip">S BW B</span> <span class="chip">A BW b</span> <span class="chip">B BW c</span></div>
                        <div class="ex-row">Transitive: <span class="chip">S BW b</span> (S&rarr;A, A&rarr;b) &ensp; <span class="chip">S BW c</span> (S&rarr;B, B&rarr;c)</div>
                        <div class="ex-row">Reflexive: <span class="chip">S BW S</span> <span class="chip">A BW A</span> <span class="chip">B BW B</span> <span class="chip">b BW b</span> <span class="chip">c BW c</span></div>
                        <div class="ex-tip">Every symbol "begins with" itself by reflexivity. Terminals that don't appear in BDW still need their own BW pairs for step 9.</div>
                    </div>`
            },
            {
                title: "Find First(x) for every symbol",
                desc: "First(A) is the set of terminals that can begin a sentential form derived from A.",
                example: `
                    <div class="ex-label">G15 example</div>
                    <div class="ex-result">
                        <div class="ex-row"><span class="chip">First(S) = {b, c}</span></div>
                        <div class="ex-row"><span class="chip">First(A) = {b}</span></div>
                        <div class="ex-row"><span class="chip">First(B) = {c}</span></div>
                        <div class="ex-row"><span class="chip">First(b) = {b}</span> &ensp; <span class="chip">First(c) = {c}</span></div>
                        <div class="ex-tip">First(A) = all terminals t such that <code>A BW t</code>. For terminals, First(t) = {t}.</div>
                    </div>`
            },
            {
                title: "Find First for the right side of each rule",
                desc: "Union First sets from left to right, stopping when a non-nullable symbol is reached.",
                example: `
                    <div class="ex-label">G15 example</div>
                    <div class="ex-result">
                        <div class="ex-row">Rule 1 <code>ABc</code>: First(A) &cup; First(B) = <span class="chip">{b, c}</span> &nbsp;<em>(A nullable, include First(B))</em></div>
                        <div class="ex-row">Rule 2 <code>bA</code>: <span class="chip">{b}</span> &nbsp;<em>(b not nullable — stop)</em></div>
                        <div class="ex-row">Rule 3 <code>&epsilon;</code>: <span class="chip eps">{}</span> &nbsp;<em>(empty set)</em></div>
                        <div class="ex-row">Rule 4 <code>c</code>: <span class="chip">{c}</span></div>
                        <div class="ex-tip">If the grammar has <em>no</em> nullable rules, skip steps 6–11 and go straight to step 12.</div>
                    </div>`
            },
            {
                title: "Find Is Followed Directly By (FDB)",
                desc: "B FDB X when B can be directly followed by X in a rule.",
                example: `
                    <div class="ex-label">G15 example</div>
                    <div class="ex-code">Rule 1: <span class="nt">S</span> <span class="arr">&rarr;</span> <span class="nt">A</span> <span class="nt">B</span> <span class="t">c</span></div>
                    <div class="ex-result">
                        <div class="ex-row"><span class="chip">A FDB B</span> — A is immediately followed by B in rule 1</div>
                        <div class="ex-row"><span class="chip">B FDB c</span> — B is immediately followed by c in rule 1</div>
                        <div class="ex-tip">If A were nullable here, we would also derive <code>A FDB c</code> by skipping over A.</div>
                    </div>`
            },
            {
                title: "Find Is Direct End Of (DEO)",
                desc: "X DEO A when symbol X can appear at the direct end of a rule defining A.",
                example: `
                    <div class="ex-label">G15 example</div>
                    <div class="ex-result">
                        <div class="ex-row"><span class="chip">c DEO S</span> — rule 1: S&rarr;ABc, c is last</div>
                        <div class="ex-row"><span class="chip">A DEO A</span> — rule 2: A&rarr;bA, A is last</div>
                        <div class="ex-row"><span class="chip">b DEO A</span> — rule 2: A&rarr;bA, A is nullable so b can be last</div>
                        <div class="ex-row"><span class="chip">c DEO B</span> — rule 4: B&rarr;c</div>
                        <div class="ex-tip">Because A is nullable in A&rarr;bA, the b preceding it can also be treated as the end of that rule.</div>
                    </div>`
            },
            {
                title: "Find Is End Of (EO)",
                desc: "EO is the reflexive transitive closure of DEO, plus N EO N for each nullable nonterminal.",
                example: `
                    <div class="ex-label">G15 example</div>
                    <div class="ex-result">
                        <div class="ex-row">From DEO: <span class="chip">c EO S</span> <span class="chip">A EO A</span> <span class="chip">b EO A</span> <span class="chip">c EO B</span></div>
                        <div class="ex-row">Reflexive: <span class="chip">c EO c</span> <span class="chip">b EO b</span> <span class="chip">S EO S</span> <span class="chip">B EO B</span></div>
                        <div class="ex-row">Nullable pair: <span class="chip eps">A EO A</span> (already included above)</div>
                        <div class="ex-tip">No new transitive pairs arise in G15 at this step.</div>
                    </div>`
            },
            {
                title: "Find Is Followed By (FB)",
                desc: "If W EO X, X FDB Y, and Y BW Z, then W FB Z.",
                example: `
                    <div class="ex-label">G15 example — combining EO + FDB + BW</div>
                    <div class="ex-code"><span class="nt">A</span> EO <span class="nt">A</span> + <span class="nt">A</span> FDB <span class="nt">B</span> + <span class="nt">B</span> BW <span class="nt">B</span> &rarr; <strong>A FB B</strong>
<span class="nt">A</span> EO <span class="nt">A</span> + <span class="nt">A</span> FDB <span class="nt">B</span> + <span class="nt">B</span> BW <span class="t">c</span> &rarr; <strong>A FB c</strong>
<span class="t">b</span> EO <span class="nt">A</span> + <span class="nt">A</span> FDB <span class="nt">B</span> + <span class="nt">B</span> BW <span class="nt">B</span> &rarr; <strong>b FB B</strong>
<span class="t">b</span> EO <span class="nt">A</span> + <span class="nt">A</span> FDB <span class="nt">B</span> + <span class="nt">B</span> BW <span class="t">c</span> &rarr; <strong>b FB c</strong>
<span class="nt">B</span> EO <span class="nt">B</span> + <span class="nt">B</span> FDB <span class="t">c</span> + <span class="t">c</span> BW <span class="t">c</span> &rarr; <strong>B FB c</strong>
<span class="t">c</span> EO <span class="nt">B</span> + <span class="nt">B</span> FDB <span class="t">c</span> + <span class="t">c</span> BW <span class="t">c</span> &rarr; <strong>c FB c</strong></div>`
            },
            {
                title: "Extend FB to include the endmarker",
                desc: "If A EO S (start symbol), add A FB &#x22A3;.",
                example: `
                    <div class="ex-label">G15 example</div>
                    <div class="ex-result">
                        <div class="ex-row"><code>S EO S</code> — S is the start symbol (reflexive pair from step 8)</div>
                        <div class="ex-row">&rarr; add <span class="chip">S FB &#x22A3;</span></div>
                        <div class="ex-tip">After this step, the FB relation for G15 has 7 pairs total.</div>
                    </div>`
            },
            {
                title: "Find Fol(A) for nullable nonterminals",
                desc: "Fol(A) = { t | A FB t }, where t is a terminal or endmarker.",
                example: `
                    <div class="ex-label">G15 example</div>
                    <div class="ex-result">
                        <div class="ex-row">Only <span class="chip eps">A</span> is a nullable nonterminal.</div>
                        <div class="ex-row">From FB: <code>A FB c</code> &rarr; <span class="chip fol">Fol(A) = {c}</span></div>
                        <div class="ex-tip">Follow sets are only needed for <em>nullable</em> nonterminals when computing selection sets.</div>
                    </div>`
            },
            {
                title: "Find Sel(n) for every rule",
                desc: "Non-nullable rule: Sel = First(&alpha;). Nullable rule: Sel = First(&alpha;) &cup; Fol(A).",
                example: `
                    <div class="ex-label">G15 example — final selection sets</div>
                    <div class="ex-result">
                        <div class="ex-row">Rule 1 <code>S&rarr;ABc</code>: <span class="chip">{b, c}</span> &nbsp;<em>First(ABc), not nullable</em></div>
                        <div class="ex-row">Rule 2 <code>A&rarr;bA</code>: <span class="chip">{b}</span> &nbsp;<em>First(bA), not nullable</em></div>
                        <div class="ex-row">Rule 3 <code>A&rarr;&epsilon;</code>: <span class="chip fol">{c}</span> &nbsp;<em>First(&epsilon;) &cup; Fol(A) = {} &cup; {c}</em></div>
                        <div class="ex-row">Rule 4 <code>B&rarr;c</code>: <span class="chip">{c}</span> &nbsp;<em>First(c), not nullable</em></div>
                        <div class="ex-tip">G15 is <strong>LL(1)</strong> — rules 2 and 3 both define A but have disjoint sets {b} and {c}, so the parser always knows which rule to apply.</div>
                    </div>`
            }
        ];

        const list = document.getElementById('algo-list');

        algoSteps.forEach(function(s, i) {
            const div = document.createElement('div');
            div.className = 'algo-step';

            const btn = document.createElement('button');
            btn.className = 'algo-step-header';
            btn.setAttribute('aria-expanded', 'false');
            btn.innerHTML =
                '<div class="step-num">' + (i + 1) + '</div>' +
                '<div class="step-title-block">' +
                '<p class="step-title">' + s.title + '</p>' +
                '<p class="step-desc">' + s.desc + '</p>' +
                '</div>' +
                '<span class="step-chevron" aria-hidden="true">&#8964;</span>';

            const panel = document.createElement('div');
            panel.className = 'example-panel';
            panel.setAttribute('role', 'region');
            panel.setAttribute('aria-label', 'G15 example for step ' + (i + 1));
            panel.innerHTML = s.example;

            btn.addEventListener('click', function() {
                const isOpen = div.classList.toggle('open');
                btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            div.appendChild(btn);
            div.appendChild(panel);
            list.appendChild(div);
        });
    </script>

</body>

</html>