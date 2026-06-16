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
$page = 12;
$nextPage = "../home.php";

if (isset($_GET['complete']) && $_GET['complete'] == '12') {
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
               <li><a href="#sentence-diag" class="toc-link">🔤 Sentence Diagrammer</a></li>
                <li><a href="#parse-race" class="toc-link">⚡ Parse Direction Visualiser</a></li>
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
            <!-- Interactive 1: Sentence Diagrammer            -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="sentence-diag">
                <h2><span class="sec-icon">🔤</span> Interactive — Sentence Diagrammer</h2>
                <p>
                    Select a sentence. The diagrammer will identify each word's part of speech, group them into
                    phrases, and build the complete sentence structure — just like the compiler's syntax analysis
                    phase builds a parse tree from tokens.
                </p>

                <div class="diagram-wrap">
                    <div class="diagram-header">
                        <h4>📖 Sentence Structure Analyser</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);">Part-of-speech → phrases → structure</span>
                    </div>
                    <div class="diagram-body">
                        <div class="diagram-sel-row">
                            <select class="diagram-sel" id="sent-sel" onchange="diagramSentence()">
                                <option value="0">The boy hugged the dog of a close neighbor (Fig 3.12)</option>
                                <option value="1">A compiler reads the input program</option>
                                <option value="2">The lexer scans a large source file</option>
                                <option value="3">Each token carries a class and a value</option>
                            </select>
                            <button class="diagram-btn" onclick="diagramSentence()">Analyse →</button>
                        </div>
                        <div class="diagram-out" id="diagram-out" style="display:none;">
                            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px;">Word classification:</div>
                            <div class="diag-word-row" id="diag-words"></div>
                            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin:16px 0 8px;">Phrase structure:</div>
                            <div class="diag-phrase-row" id="diag-phrases"></div>
                            <div class="note-box pro" style="margin-top:14px;">
                                <span class="box-icon">🧩</span>
                                <div id="diag-analogy">This parallels compiler syntax analysis: words = tokens, phrase types = nonterminals, tree = derivation tree.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Interactive 2: Parse Direction Visualiser     -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="parse-race">
                <h2><span class="sec-icon">⚡</span> Parse Direction Visualiser — Top-Down vs Bottom-Up</h2>
                <p>
                    Select an expression and watch both parsing strategies build the derivation tree for
                    <code>var + var * var</code> using Grammar G5. Top-down starts from the start symbol;
                    bottom-up starts from the tokens.
                </p>

                <div class="race-wrap">
                    <div class="race-header">
                        <h4>⚡ Top-Down vs Bottom-Up — Side by Side</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);">Grammar G5</span>
                    </div>
                    <div class="race-body">
                        <div class="race-controls">
                            <select class="race-sel" id="race-sel">
                                <option value="vpv">var + var * var</option>
                                <option value="vv">var * var</option>
                                <option value="pvpv">(var + var) * var</option>
                            </select>
                            <button class="race-btn" onclick="raceStep()" id="race-step-btn">Step →</button>
                            <button class="race-btn sec" onclick="raceRunAll()">Run All ⚡</button>
                            <button class="race-btn sec" onclick="raceReset()">↺ Reset</button>
                        </div>
                        <div class="race-expr" id="race-expr">var + var * var</div>
                        <div class="race-grid">
                            <div class="race-col top-down">
                                <div class="race-col-title" style="color:var(--purple);">⬇️ Top-Down (Predict / Expand)</div>
                                <div id="td-steps"></div>
                            </div>
                            <div class="race-col bottom-up">
                                <div class="race-col-title" style="color:var(--green);">⬆️ Bottom-Up (Shift / Reduce)</div>
                                <div id="bu-steps"></div>
                            </div>
                        </div>
                        <div id="race-result" style="display:none;margin-top:12px;padding:10px 16px;border-radius:10px;background:#F0FDF4;color:#065F46;font-size:14px;font-weight:600;"></div>
                    </div>
                </div>
            </section>

      
            <div class="chapter-nav">
                <a href="c3_2_1.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>3.2 The Parsing Problem</div>
                </a>
                <a href="?complete=12" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Complete</span>Homepage</div>
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