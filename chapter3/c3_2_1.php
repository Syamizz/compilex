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
$page = 11;
$nextPage = "c3_2_2.php";

if (isset($_GET['complete']) && $_GET['complete'] == '11') {
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
    <title>Chapter 3.2 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c3/c3_2.css">
</head>

<body>

    <?php include 'navbar_c3.php'; ?>
    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#parsing-def" class="toc-link">Formal Definition</a></li>
                <li><a href="#algotypes" class="toc-link">Bottom-Up vs Top-Down</a></li>
                <li><a href="#completesearch" class="toc-link">Complete Search — Why Rejected</a></li>
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
                <div class="chapter-tag">📘 Chapter 3 · Section 3.2</div>
                <h1>The Parsing Problem</h1>
                <div class="metadata">
                    <span>⏱ 14 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>📐 Syntax Analysis</span>
                </div>
            </header>

     



            <!-- ══════════════════════════════════════════════ -->
            <!-- Formal definition                             -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="parsing-def">
                <h2><span class="sec-icon">📐</span> The Parsing Problem — Formal Definition</h2>
                <p>
                    The <strong>parsing problem</strong> is formally stated as:
                </p>
                <div style="background:var(--code-bg);border-radius:var(--radius);padding:20px 24px;margin:16px 0;font-size:14px;color:#CDD6F4;line-height:2;">
                    <span style="color:#CBA6F7;font-weight:700;">Given:</span> a grammar G and a string of input symbols<br>
                    <span style="color:#A6E3A1;font-weight:700;">Decide:</span> whether the string is in L(G)<br>
                    <span style="color:#89B4FA;font-weight:700;">Also:</span> determine the <em>structure</em> of the string — a derivation tree<br>
                    <span style="color:#FAB387;font-weight:700;">Output:</span> "YES" (+ derivation tree) or "NO" (+ error message)
                </div>

                <div class="concept-grid">
                    <div class="concept-card">
                        <div class="cc-title" style="color:var(--green);">✅ Accepted string</div>
                        <div class="cc-body">The string is in L(G). The parser produces a derivation tree that describes the string's complete structure. This tree drives the next phases of the compiler.</div>
                    </div>
                    <div class="concept-card">
                        <div class="cc-title" style="color:var(--red);">❌ Rejected string</div>
                        <div class="cc-body">The string is NOT in L(G). The compiler outputs an informative syntax error message. A good compiler continues scanning to find additional errors.</div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Bottom-Up vs Top-Down                         -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="algotypes">
                <h2><span class="sec-icon">🔄</span> Bottom-Up vs Top-Down Parsing</h2>
                <p>
                    For <strong>context-free grammars</strong>, there are two kinds of parsing algorithms.
                    These terms refer to the sequence in which the derivation tree is built.
                </p>

                <div class="parse-dir-wrap">
                    <div class="parse-dir-card" style="border:2px solid var(--purple);">
                        <div class="pdc-title" style="color:var(--purple);">⬇️ Top-Down Parsing</div>
                        <p style="font-size:13px;color:var(--muted);margin-bottom:12px;">
                            Builds the derivation tree from the <strong>root (start symbol) down to the leaves</strong>.
                            Starts with the start nonterminal and tries to derive the input string using rewriting rules.
                        </p>
                        <div class="pdc-steps">
                            <div class="pdc-step" style="background:rgba(99,102,241,.1);">Start: <span style="color:#CBA6F7;">Expr</span></div>
                            <div class="pdc-step" style="background:rgba(99,102,241,.08);"><span class="pdc-arrow">↓</span> Apply rule: <span style="color:#CBA6F7;">Expr → Expr + Term</span></div>
                            <div class="pdc-step" style="background:rgba(99,102,241,.08);"><span class="pdc-arrow">↓</span> Apply rule: <span style="color:#CBA6F7;">Expr → Term</span></div>
                            <div class="pdc-step" style="background:rgba(99,102,241,.06);"><span class="pdc-arrow">↓</span> … expand until leaves match input</div>
                            <div class="pdc-step" style="background:rgba(16,185,129,.1);"><span style="color:var(--green);">✓</span> Leaves = <span style="color:#A6E3A1;">var + var * var</span></div>
                        </div>
                        <div class="note-box key" style="margin-top:12px;font-size:13px;">
                            <span class="box-icon">💡</span>
                            <div>Prediction-based. Works best when the grammar is in a restricted form (LL grammars). Easier to implement by hand.</div>
                        </div>
                    </div>

                    <div class="parse-dir-card" style="border:2px solid var(--green);">
                        <div class="pdc-title" style="color:var(--green);">⬆️ Bottom-Up Parsing</div>
                        <p style="font-size:13px;color:var(--muted);margin-bottom:12px;">
                            Builds the derivation tree from the <strong>leaves (input tokens) up to the root</strong>.
                            Reads input tokens and reduces them to nonterminals, until the start symbol is reached.
                        </p>
                        <div class="pdc-steps">
                            <div class="pdc-step" style="background:rgba(16,185,129,.08);">Start: <span style="color:#A6E3A1;">var + var * var</span></div>
                            <div class="pdc-step" style="background:rgba(16,185,129,.07);"><span class="pdc-arrow">↑</span> Reduce: <span style="color:#A6E3A1;">var</span> → <span style="color:#CBA6F7;">Factor</span></div>
                            <div class="pdc-step" style="background:rgba(16,185,129,.07);"><span class="pdc-arrow">↑</span> Reduce: <span style="color:#CBA6F7;">Factor</span> → <span style="color:#CBA6F7;">Term</span></div>
                            <div class="pdc-step" style="background:rgba(16,185,129,.06);"><span class="pdc-arrow">↑</span> … reduce until start symbol</div>
                            <div class="pdc-step" style="background:rgba(16,185,129,.1);"><span style="color:var(--green);">✓</span> Reach: <span style="color:#CBA6F7;">Expr</span></div>
                        </div>
                        <div class="note-box key" style="margin-top:12px;font-size:13px;">
                            <span class="box-icon">💡</span>
                            <div>Reduction-based. Handles a wider class of grammars (LR grammars). More powerful but typically harder to implement by hand; usually generated by tools.</div>
                        </div>
                    </div>
                </div>

                <table class="algo-table">
                    <thead>
                        <tr>
                            <th>Property</th>
                            <th>Top-Down</th>
                            <th>Bottom-Up</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tree construction direction</td>
                            <td>Root → leaves</td>
                            <td>Leaves → root</td>
                        </tr>
                        <tr>
                            <td>Starting point</td>
                            <td>Start symbol (nonterminal)</td>
                            <td>Input tokens (terminals)</td>
                        </tr>
                        <tr>
                            <td>Operation</td>
                            <td>Prediction / expansion</td>
                            <td>Shift / reduce</td>
                        </tr>
                        <tr>
                            <td>Grammar class</td>
                            <td>LL(k) grammars</td>
                            <td>LR(k) grammars (larger class)</td>
                        </tr>
                        <tr>
                            <td>Implementation</td>
                            <td>Easier by hand</td>
                            <td>Usually tool-generated (yacc, SableCC)</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Complete Search                               -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="completesearch">
                <h2><span class="sec-icon">🐢</span> Complete Search — Why It Is Rejected</h2>
                <p>
                    There exist parsing algorithms that can be applied to <em>any</em> context-free grammar,
                    using a <strong>complete search strategy</strong> — trying all possible parse sequences until
                    one succeeds or all possibilities are exhausted.
                </p>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Why complete search is unacceptable:</strong> These algorithms are generally too slow.
                        They cannot run in <strong>polynomial time</strong> — their time complexity grows too fast
                        for practical use in a compiler that must handle real programs. For this reason, practical
                        compilers use restricted grammar classes (LL or LR) that admit efficient parsing algorithms
                        running in <strong>linear time O(n)</strong> in the length of the input.
                    </div>
                </div>

                <div class="concept-grid">
                    <div class="concept-card">
                        <div class="cc-title" style="color:var(--red);">❌ Complete search</div>
                        <div class="cc-body">Works for any CFG. Tries all possible derivations systematically. Exponential/polynomial time — not practical for real compilers.</div>
                    </div>
                    <div class="concept-card">
                        <div class="cc-title" style="color:var(--green);">✅ Efficient parsers</div>
                        <div class="cc-body">Work for restricted grammar classes (LL, LR). Run in <strong>O(n) linear time</strong>. Require the grammar to satisfy certain structural properties. Used in all practical compilers.</div>
                    </div>
                </div>
            </section>



            <div class="chapter-nav">
                <a href="c3_2_0.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>3.2 The Parsing Problem</div>
                </a>
                <a href="?complete=11" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>Continue 3.2 The Parsing Problem</div>
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
        // SENTENCE DIAGRAMMER
        // ════════════════════════════════════════════════════════
        const SENTENCES = [{
                words: [{
                        w: 'The',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'boy',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    },
                    {
                        w: 'hugged',
                        t: 'Verb',
                        cls: 't-verb',
                        lt: 'l-verb'
                    },
                    {
                        w: 'the',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'dog',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    },
                    {
                        w: 'of',
                        t: 'Prep',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        w: 'a',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'close',
                        t: 'Adj',
                        cls: 't-adj',
                        lt: 'l-adj'
                    },
                    {
                        w: 'neighbor',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    }
                ],
                phrases: [{
                        label: 'NounPhrase',
                        words: 'The boy',
                        cls: 't-np',
                        lt: 'l-np'
                    },
                    {
                        label: 'NounPhrase',
                        words: 'the dog',
                        cls: 't-np',
                        lt: 'l-np'
                    },
                    {
                        label: 'NounPhrase',
                        words: 'a close neighbor',
                        cls: 't-np',
                        lt: 'l-np'
                    },
                    {
                        label: 'PrepPhrase',
                        words: 'of a close neighbor',
                        cls: 't-pp',
                        lt: 'l-pp'
                    },
                    {
                        label: 'Subject',
                        words: 'The boy',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        label: 'DirectObject',
                        words: 'the dog of a close neighbor',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        label: 'Predicate',
                        words: 'hugged the dog of a close neighbor',
                        cls: 't-verb',
                        lt: 'l-verb'
                    },
                    {
                        label: 'Sentence',
                        words: 'The boy hugged the dog of a close neighbor',
                        cls: 't-article',
                        lt: 'l-article'
                    }
                ],
                analogy: 'In Fig 3.12: Articles/Nouns/Verb = tokens; NounPhrase/PrepPhrase = nonterminals; the full diagram = derivation tree.'
            },
            {
                words: [{
                        w: 'A',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'compiler',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    },
                    {
                        w: 'reads',
                        t: 'Verb',
                        cls: 't-verb',
                        lt: 'l-verb'
                    },
                    {
                        w: 'the',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'input',
                        t: 'Adj',
                        cls: 't-adj',
                        lt: 'l-adj'
                    },
                    {
                        w: 'program',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    }
                ],
                phrases: [{
                        label: 'NounPhrase',
                        words: 'A compiler',
                        cls: 't-np',
                        lt: 'l-np'
                    },
                    {
                        label: 'NounPhrase',
                        words: 'the input program',
                        cls: 't-np',
                        lt: 'l-np'
                    },
                    {
                        label: 'Subject',
                        words: 'A compiler',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        label: 'DirectObject',
                        words: 'the input program',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        label: 'Predicate',
                        words: 'reads the input program',
                        cls: 't-verb',
                        lt: 'l-verb'
                    },
                    {
                        label: 'Sentence',
                        words: 'A compiler reads the input program',
                        cls: 't-article',
                        lt: 'l-article'
                    }
                ],
                analogy: '"A compiler" and "the input program" are NounPhrases (nonterminals). Verb + DirectObject = Predicate. The full tree = derivation tree for this "source string".'
            },
            {
                words: [{
                        w: 'The',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'lexer',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    },
                    {
                        w: 'scans',
                        t: 'Verb',
                        cls: 't-verb',
                        lt: 'l-verb'
                    },
                    {
                        w: 'a',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'large',
                        t: 'Adj',
                        cls: 't-adj',
                        lt: 'l-adj'
                    },
                    {
                        w: 'source',
                        t: 'Adj',
                        cls: 't-adj',
                        lt: 'l-adj'
                    },
                    {
                        w: 'file',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    }
                ],
                phrases: [{
                        label: 'NounPhrase',
                        words: 'The lexer',
                        cls: 't-np',
                        lt: 'l-np'
                    },
                    {
                        label: 'NounPhrase',
                        words: 'a large source file',
                        cls: 't-np',
                        lt: 'l-np'
                    },
                    {
                        label: 'Subject',
                        words: 'The lexer',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        label: 'DirectObject',
                        words: 'a large source file',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        label: 'Predicate',
                        words: 'scans a large source file',
                        cls: 't-verb',
                        lt: 'l-verb'
                    },
                    {
                        label: 'Sentence',
                        words: 'The lexer scans a large source file',
                        cls: 't-article',
                        lt: 'l-article'
                    }
                ],
                analogy: 'Adjectives modify Nouns (like operators qualifying operands). The phrase structure parallels how operators and operands are grouped in a parse tree.'
            },
            {
                words: [{
                        w: 'Each',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'token',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    },
                    {
                        w: 'carries',
                        t: 'Verb',
                        cls: 't-verb',
                        lt: 'l-verb'
                    },
                    {
                        w: 'a',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'class',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    },
                    {
                        w: 'and',
                        t: 'Prep',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        w: 'a',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'value',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    }
                ],
                phrases: [{
                        label: 'NounPhrase',
                        words: 'Each token',
                        cls: 't-np',
                        lt: 'l-np'
                    },
                    {
                        label: 'NounPhrase',
                        words: 'a class and a value',
                        cls: 't-np',
                        lt: 'l-np'
                    },
                    {
                        label: 'Subject',
                        words: 'Each token',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        label: 'DirectObject',
                        words: 'a class and a value',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        label: 'Predicate',
                        words: 'carries a class and a value',
                        cls: 't-verb',
                        lt: 'l-verb'
                    },
                    {
                        label: 'Sentence',
                        words: 'Each token carries a class and a value',
                        cls: 't-article',
                        lt: 'l-article'
                    }
                ],
                analogy: 'This sentence describes the token structure from Ch2! Notice how the diagram builds hierarchically — just like a compiler\'s derivation tree.'
            }
        ];

        function diagramSentence() {
            const idx = parseInt(document.getElementById('sent-sel').value);
            const data = SENTENCES[idx];
            const wordsDiv = document.getElementById('diag-words');
            const phrasesDiv = document.getElementById('diag-phrases');
            wordsDiv.innerHTML = '';
            phrasesDiv.innerHTML = '';
            data.words.forEach(w => {
                const el = document.createElement('div');
                el.className = 'diag-word';
                el.innerHTML = `<span class="dw-word ${w.cls}">${w.w}</span><span class="dw-type ${w.lt}">${w.t}</span>`;
                wordsDiv.appendChild(el);
            });
            data.phrases.forEach(p => {
                const el = document.createElement('div');
                el.className = `diag-phrase`;
                el.style.borderColor = getComputedStyle(document.documentElement).getPropertyValue('--purple');
                el.style.borderColor = p.cls.includes('verb') ? '#EF4444' : p.cls.includes('np') ? '#A855F7' : p.cls.includes('pp') ? '#F97316' : '#6366F1';
                el.style.background = p.cls.includes('verb') ? '#FFF1F2' : p.cls.includes('np') ? '#F3E8FF' : p.cls.includes('pp') ? '#FFF7ED' : '#EEF2FF';
                el.innerHTML = `<span class="dp-label" style="color:${p.cls.includes('verb')?'#EF4444':p.cls.includes('np')?'#A855F7':p.cls.includes('pp')?'#F97316':'var(--purple)'};">${p.label}</span><span style="font-size:13px;color:var(--text);">${p.words}</span>`;
                phrasesDiv.appendChild(el);
            });
            document.getElementById('diag-analogy').textContent = data.analogy;
            document.getElementById('diagram-out').style.display = 'block';
        }

        diagramSentence();

        // ════════════════════════════════════════════════════════
        // PARSE DIRECTION RACE
        // ════════════════════════════════════════════════════════
        const RACE_DATA = {
            vpv: {
                expr: 'var + var * var',
                td: ['Start: Expr', '→ Expr + Term (Rule 1)', '→ Term + Term', '→ Factor + Term', '→ var + Term (match var)', '→ var + Term * Factor (Rule 3)', '→ var + Factor * Factor', '→ var + var * Factor (match var)', '→ var + var * var (match var)', '✓ Derivation tree complete'],
                bu: ['Input: var + var * var', 'shift var → Factor', 'Factor → Term (Reduce Rule 4)', 'shift + ', 'shift var → Factor', 'Factor → Term (Reduce Rule 4)', 'shift * ', 'shift var → Factor', 'Term * Factor → Term (Reduce Rule 3)', 'Expr + Term → Expr (Reduce Rule 1)', '✓ Derivation tree complete']
            },
            vv: {
                expr: 'var * var',
                td: ['Start: Expr', '→ Term (Rule 2)', '→ Term * Factor (Rule 3)', '→ Factor * Factor', '→ var * Factor (match var)', '→ var * var (match var)', '✓ Derivation tree complete'],
                bu: ['Input: var * var', 'shift var → Factor', 'Factor → Term (Reduce Rule 4)', 'shift *', 'shift var → Factor', 'Term * Factor → Term (Reduce Rule 3)', 'Term → Expr (Reduce Rule 2)', '✓ Derivation tree complete']
            },
            pvpv: {
                expr: '(var + var) * var',
                td: ['Start: Expr', '→ Term (Rule 2)', '→ Term * Factor (Rule 3)', '→ Factor * Factor', '→ (Expr) * Factor (Rule 5)', '→ (Expr + Term) * Factor', '→ (Term + Term) * Factor', '→ (Factor + Factor) * Factor', '→ (var + var) * Factor', '→ (var + var) * var', '✓ Derivation tree complete'],
                bu: ['Input: (var + var) * var', 'shift (', 'shift var → Factor → Term', 'shift +', 'shift var → Factor → Term', 'Expr + Term → Expr', '(Expr) → Factor (Reduce Rule 5)', 'shift *', 'shift var → Factor', 'Term * Factor → Term', 'Term → Expr (Reduce Rule 2)', '✓ Derivation tree complete']
            }
        };

        let racePos = 0,
            raceCurrent = null;

        function raceReset() {
            const key = document.getElementById('race-sel').value;
            raceCurrent = RACE_DATA[key];
            racePos = 0;
            document.getElementById('race-expr').textContent = raceCurrent.expr;
            document.getElementById('td-steps').innerHTML = '';
            document.getElementById('bu-steps').innerHTML = '';
            document.getElementById('race-result').style.display = 'none';
            document.getElementById('race-step-btn').disabled = false;
        }

        function raceStep() {
            if (!raceCurrent) {
                raceReset();
            }
            const maxSteps = Math.max(raceCurrent.td.length, raceCurrent.bu.length);
            if (racePos >= maxSteps) {
                showRaceResult();
                return;
            }
            if (racePos < raceCurrent.td.length) {
                const el = document.createElement('div');
                el.className = 'race-step td';
                el.textContent = raceCurrent.td[racePos];
                document.getElementById('td-steps').appendChild(el);
                requestAnimationFrame(() => el.classList.add('show'));
            }
            if (racePos < raceCurrent.bu.length) {
                const el = document.createElement('div');
                el.className = 'race-step bu';
                el.textContent = raceCurrent.bu[racePos];
                document.getElementById('bu-steps').appendChild(el);
                requestAnimationFrame(() => el.classList.add('show'));
            }
            racePos++;
            if (racePos >= maxSteps) showRaceResult();
        }

        function raceRunAll() {
            if (!raceCurrent) raceReset();
            const maxSteps = Math.max(raceCurrent.td.length, raceCurrent.bu.length);
            while (racePos < maxSteps) raceStep();
        }

        function showRaceResult() {
            document.getElementById('race-step-btn').disabled = true;
            const res = document.getElementById('race-result');
            res.style.display = 'block';
            res.innerHTML = `✅ Both parsing directions accept <strong style="font-family:'JetBrains Mono',monospace;">${raceCurrent.expr}</strong> and produce the same derivation tree — just built in opposite directions.`;
        }

        raceReset();
    </script>

</body>

</html>