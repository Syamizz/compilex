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
$page = 10;
$nextPage = "c3_2_1.php";

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
                <li><a href="#overview" class="toc-link">3.2 The Parsing Problem</a></li>
                <li><a href="#fig312" class="toc-link sub">↳ Figure 3.12 — English Sentence</a></li>
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
            <!-- 3.2 Overview                                   -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="overview">
                <h2><span class="sec-icon">📝</span> 3.2 The Parsing Problem</h2>
                <p>
                    You may recall from school the task of <strong>diagramming English sentences</strong> — grouping
                    words into phrases and assigning syntactic types such as noun phrase, predicate, and
                    prepositional phrase. This is exactly the same problem a compiler must solve during
                    <strong>syntax analysis</strong>.
                </p>
                <p>
                    The parsing problem is: given a grammar G and an input string of symbols, determine
                    (1) whether the string is in L(G), and (2) the <strong>structure</strong> of the string —
                    typically a derivation tree.
                </p>

                <div class="concept-grid">
                    <div class="concept-card">
                        <div class="cc-title" style="color:var(--purple);">What is parsing?</div>
                        <div class="cc-body">Given a grammar G and an input string, decide whether the string is in L(G) — and if so, produce a description of its structure (e.g. a derivation tree).</div>
                    </div>
                    <div class="concept-card">
                        <div class="cc-title" style="color:var(--green);">What is a parsing algorithm?</div>
                        <div class="cc-body">An algorithm that solves the parsing problem for a particular class of grammars. A good parsing algorithm is applicable to a large class of grammars and runs efficiently.</div>
                    </div>
                </div>
            </section>

            <!-- Figure 3.12 -->
            <section id="fig312">
                <h2><span class="sec-icon">🔤</span> Figure 3.12 — Diagrammed English Sentence</h2>
                <p>
                    The diagram below shows the syntactic structure of the sentence
                    <em>"The boy hugged the dog of a close neighbor"</em>.
                    Each word is first classified (Article, Noun, Verb, etc.), then words are grouped into
                    phrases (NounPhrase, PrepositionalPhrase), and finally the whole sentence structure
                    is identified. A compiler does the same thing for source code.
                </p>

                <div class="sentence-diagram">
                    <div class="sd-title">Figure 3.12 — Diagram of an English Sentence</div>
                    <svg width="100%" viewBox="0 0 760 300" style="display:block;min-width:500px;">
                        <defs>
                            <marker id="ta" markerWidth="6" markerHeight="6" refX="3" refY="3" orient="auto">
                                <circle cx="3" cy="3" r="2.5" fill="#6366F1" opacity=".35" />
                            </marker>
                        </defs>

                        <!-- Word boxes at bottom row -->
                        <!-- The -->
                        <rect x="4" y="240" width="50" height="28" rx="6" fill="#EFF6FF" stroke="#3B82F6" stroke-width="1.5" />
                        <text x="29" y="258" text-anchor="middle" fill="#1D4ED8" font-size="11" font-weight="700" font-family="JetBrains Mono">The</text>
                        <!-- boy -->
                        <rect x="60" y="240" width="44" height="28" rx="6" fill="#F0FDF4" stroke="#10B981" stroke-width="1.5" />
                        <text x="82" y="258" text-anchor="middle" fill="#065F46" font-size="11" font-weight="700" font-family="JetBrains Mono">boy</text>
                        <!-- hugged -->
                        <rect x="110" y="240" width="62" height="28" rx="6" fill="#FFF1F2" stroke="#EF4444" stroke-width="1.5" />
                        <text x="141" y="258" text-anchor="middle" fill="#9F1239" font-size="11" font-weight="700" font-family="JetBrains Mono">hugged</text>
                        <!-- the -->
                        <rect x="178" y="240" width="44" height="28" rx="6" fill="#EFF6FF" stroke="#3B82F6" stroke-width="1.5" />
                        <text x="200" y="258" text-anchor="middle" fill="#1D4ED8" font-size="11" font-weight="700" font-family="JetBrains Mono">the</text>
                        <!-- dog -->
                        <rect x="228" y="240" width="44" height="28" rx="6" fill="#F0FDF4" stroke="#10B981" stroke-width="1.5" />
                        <text x="250" y="258" text-anchor="middle" fill="#065F46" font-size="11" font-weight="700" font-family="JetBrains Mono">dog</text>
                        <!-- of -->
                        <rect x="278" y="240" width="36" height="28" rx="6" fill="var(--purple-s)" stroke="var(--purple)" stroke-width="1.5" />
                        <text x="296" y="258" text-anchor="middle" fill="var(--purple)" font-size="11" font-weight="700" font-family="JetBrains Mono">of</text>
                        <!-- a -->
                        <rect x="320" y="240" width="30" height="28" rx="6" fill="#EFF6FF" stroke="#3B82F6" stroke-width="1.5" />
                        <text x="335" y="258" text-anchor="middle" fill="#1D4ED8" font-size="11" font-weight="700" font-family="JetBrains Mono">a</text>
                        <!-- close -->
                        <rect x="356" y="240" width="50" height="28" rx="6" fill="#FEF3C7" stroke="#F59E0B" stroke-width="1.5" />
                        <text x="381" y="258" text-anchor="middle" fill="#92400E" font-size="11" font-weight="700" font-family="JetBrains Mono">close</text>
                        <!-- neighbor -->
                        <rect x="412" y="240" width="72" height="28" rx="6" fill="#F0FDF4" stroke="#10B981" stroke-width="1.5" />
                        <text x="448" y="258" text-anchor="middle" fill="#065F46" font-size="11" font-weight="700" font-family="JetBrains Mono">neighbor</text>

                        <!-- POS labels below word boxes -->
                        <text x="29" y="278" text-anchor="middle" fill="#3B82F6" font-size="9" font-family="Syne" font-weight="700">Article</text>
                        <text x="82" y="278" text-anchor="middle" fill="#10B981" font-size="9" font-family="Syne" font-weight="700">Noun</text>
                        <text x="141" y="278" text-anchor="middle" fill="#EF4444" font-size="9" font-family="Syne" font-weight="700">Verb</text>
                        <text x="200" y="278" text-anchor="middle" fill="#3B82F6" font-size="9" font-family="Syne" font-weight="700">Article</text>
                        <text x="250" y="278" text-anchor="middle" fill="#10B981" font-size="9" font-family="Syne" font-weight="700">Noun</text>
                        <text x="296" y="278" text-anchor="middle" fill="var(--purple)" font-size="9" font-family="Syne" font-weight="700">Prep</text>
                        <text x="335" y="278" text-anchor="middle" fill="#3B82F6" font-size="9" font-family="Syne" font-weight="700">Article</text>
                        <text x="381" y="278" text-anchor="middle" fill="#F59E0B" font-size="9" font-family="Syne" font-weight="700">Adj</text>
                        <text x="448" y="278" text-anchor="middle" fill="#10B981" font-size="9" font-family="Syne" font-weight="700">Noun</text>

                        <!-- Level 2: NounPhrases -->
                        <!-- NP1: The boy -->
                        <line x1="29" y1="240" x2="55" y2="210" stroke="#A855F7" stroke-width="1.4" opacity=".5" />
                        <line x1="82" y1="240" x2="55" y2="210" stroke="#A855F7" stroke-width="1.4" opacity=".5" />
                        <rect x="10" y="195" width="90" height="22" rx="6" fill="#F3E8FF" stroke="#A855F7" stroke-width="1.5" />
                        <text x="55" y="210" text-anchor="middle" fill="#6B21A8" font-size="10" font-weight="700" font-family="Syne">NounPhrase</text>

                        <!-- NP2: the dog -->
                        <line x1="200" y1="240" x2="224" y2="210" stroke="#A855F7" stroke-width="1.4" opacity=".5" />
                        <line x1="250" y1="240" x2="224" y2="210" stroke="#A855F7" stroke-width="1.4" opacity=".5" />
                        <rect x="180" y="195" width="88" height="22" rx="6" fill="#F3E8FF" stroke="#A855F7" stroke-width="1.5" />
                        <text x="224" y="210" text-anchor="middle" fill="#6B21A8" font-size="10" font-weight="700" font-family="Syne">NounPhrase</text>

                        <!-- NP3: a close neighbor -->
                        <line x1="335" y1="240" x2="415" y2="210" stroke="#A855F7" stroke-width="1.4" opacity=".5" />
                        <line x1="381" y1="240" x2="415" y2="210" stroke="#A855F7" stroke-width="1.4" opacity=".5" />
                        <line x1="448" y1="240" x2="415" y2="210" stroke="#A855F7" stroke-width="1.4" opacity=".5" />
                        <rect x="365" y="195" width="100" height="22" rx="6" fill="#F3E8FF" stroke="#A855F7" stroke-width="1.5" />
                        <text x="415" y="210" text-anchor="middle" fill="#6B21A8" font-size="10" font-weight="700" font-family="Syne">NounPhrase</text>

                        <!-- PrepPhrase: of + NP3 -->
                        <line x1="296" y1="240" x2="355" y2="170" stroke="#F97316" stroke-width="1.4" opacity=".5" />
                        <line x1="415" y1="195" x2="355" y2="170" stroke="#F97316" stroke-width="1.4" opacity=".5" />
                        <rect x="285" y="155" width="140" height="22" rx="6" fill="#FFF7ED" stroke="#F97316" stroke-width="1.5" />
                        <text x="355" y="170" text-anchor="middle" fill="#9A3412" font-size="10" font-weight="700" font-family="Syne">PrepositionalPhrase</text>

                        <!-- Level 3: Subject, DirectObject -->
                        <!-- Subject: NP1 -->
                        <line x1="55" y1="195" x2="55" y2="130" stroke="#6366F1" stroke-width="1.4" opacity=".5" />
                        <rect x="12" y="115" width="86" height="22" rx="6" fill="var(--purple-s)" stroke="var(--purple)" stroke-width="1.5" />
                        <text x="55" y="130" text-anchor="middle" fill="var(--purple)" font-size="10" font-weight="700" font-family="Syne">Subject</text>

                        <!-- DirectObject: NP2 + PrepPhrase -->
                        <line x1="224" y1="195" x2="280" y2="130" stroke="#6366F1" stroke-width="1.4" opacity=".5" />
                        <line x1="355" y1="155" x2="280" y2="130" stroke="#6366F1" stroke-width="1.4" opacity=".5" />
                        <rect x="210" y="115" width="140" height="22" rx="6" fill="var(--purple-s)" stroke="var(--purple)" stroke-width="1.5" />
                        <text x="280" y="130" text-anchor="middle" fill="var(--purple)" font-size="10" font-weight="700" font-family="Syne">DirectObject</text>

                        <!-- Level 4: Verb + DirectObject = Predicate -->
                        <line x1="141" y1="240" x2="200" y2="80" stroke="#6366F1" stroke-width="1.4" opacity=".4" />
                        <line x1="280" y1="115" x2="200" y2="80" stroke="#6366F1" stroke-width="1.4" opacity=".4" />
                        <rect x="138" y="65" width="124" height="22" rx="6" fill="#EFF6FF" stroke="#3B82F6" stroke-width="1.5" />
                        <text x="200" y="80" text-anchor="middle" fill="#1D4ED8" font-size="10" font-weight="700" font-family="Syne">Predicate</text>

                        <!-- Level 5: Subject + Predicate = Sentence -->
                        <line x1="55" y1="115" x2="170" y2="32" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="200" y1="65" x2="170" y2="32" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <rect x="110" y="16" width="120" height="24" rx="7" fill="rgba(99,102,241,.15)" stroke="var(--purple)" stroke-width="2" />
                        <text x="170" y="32" text-anchor="middle" fill="var(--purple)" font-size="11" font-weight="800" font-family="Syne">Sentence</text>
                    </svg>
                </div>

                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>
                        <strong>Analogy:</strong> Diagramming a sentence = syntax analysis. Words are like tokens;
                        phrase types (NounPhrase, PrepositionalPhrase) are like nonterminals; the parse tree
                        structure is the derivation tree. The compiler builds exactly this structure for source code.
                    </div>
                </div>
            </section>



      
            <div class="chapter-nav">
                <a href="c3_1_3.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>3.1 Ambiguities in Programming Languages</div>
                </a>
                <a href="?complete=10" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>Formal Definition</div>
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