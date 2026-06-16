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
$page = 5;
$nextPage = "c2_1_2.php";

if (isset($_GET['complete']) && $_GET['complete'] == '5') {
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
    <title>Chapter 2.1 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c2/c2_1.css">
</head>

<body>

    <?php include 'navbar_c2.php'; ?>

    <div class="note-container">

        <!-- ── Sidebar TOC ─────────────────────────────────── -->
        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#tok-classes" class="toc-link">2.1.1 Token Classes (1–9)</a></li>
                <li><a href="#word-bound" class="toc-link">Word Boundaries</a></li>
            </ul>
            <div class="toc-progress">
                <div class="toc-progress-label"><span>Progress</span><span id="pct-label">0%</span></div>
                <div class="toc-bar">
                    <div class="toc-bar-fill" id="toc-bar"></div>
                </div>
            </div>
        </nav>

        <!-- ── Main content ───────────────────────────────── -->
        <article class="content">

            <header class="content-header">
                <div class="chapter-tag">📘 Chapter 2 · Section 2.1</div>
                <h1>Lexical Tokens</h1>
                <div class="metadata">
                    <span>⏱ 14 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>🔤 Lexical Analysis</span>
                </div>
            </header>

      

            <!-- ══════════════════════════════════════════════ -->
            <!-- 2.1.1 Token Classes                           -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="tok-classes">
                <h2><span class="sec-icon">🏷️</span> 2.1.1 The Nine Token Classes</h2>
                <p>
                    There are nine categories of input that the lexical scanner must handle.
                    Classes 1–6 produce tokens passed to the next phase. Classes 7–9 are
                    identified by the scanner but <em>discarded</em> — they do not appear in the token stream.
                </p>

                <div class="token-classes-grid">

                    <div class="tc-card cls-1">
                        <div class="tc-num">1</div>
                        <div class="tc-body">
                            <div class="tc-name">Keywords</div>
                            <div class="tc-desc">Reserved words with predefined meaning to the compiler. In Java and C, all keywords are reserved — programmers cannot use them as identifiers. (PL/1 is unusual in having no reserved words.)</div>
                            <span class="tc-ex">while &nbsp; if &nbsp; else &nbsp; for &nbsp; int &nbsp; float</span>
                        </div>
                    </div>

                    <div class="tc-card cls-2">
                        <div class="tc-num">2</div>
                        <div class="tc-body">
                            <div class="tc-name">Identifiers</div>
                            <div class="tc-desc">Names constructed by the programmer to attach a label to a variable, class, constant, or function. Stored in the symbol table; the token value is a pointer to the symbol table entry.</div>
                            <span class="tc-ex">x33 &nbsp; total &nbsp; calc &nbsp; myVar</span>
                        </div>
                    </div>

                    <div class="tc-card cls-3">
                        <div class="tc-num">3</div>
                        <div class="tc-body">
                            <div class="tc-name">Operators</div>
                            <div class="tc-desc">Symbols used for arithmetic, relational, or logical operations. Operators may consist of <em>more than one character</em> — e.g. <code>!=</code>, <code>&lt;=</code>, <code>==</code>.</div>
                            <span class="tc-ex">+ &nbsp; - &nbsp; = &nbsp; != &nbsp; &lt;= &nbsp; &&</span>
                        </div>
                    </div>

                    <div class="tc-card cls-4">
                        <div class="tc-num">4</div>
                        <div class="tc-body">
                            <div class="tc-name">Numeric Constants</div>
                            <div class="tc-desc">Integer and floating-point literals. Must be <em>converted from string form to numeric format</em> so arithmetic can be performed. May be stored in a constant table; the token value is a pointer to that entry.</div>
                            <span class="tc-ex">124 &nbsp; 12.35 &nbsp; 0.09E-23 &nbsp; 2.5e+33</span>
                        </div>
                    </div>

                    <div class="tc-card cls-5">
                        <div class="tc-num">5</div>
                        <div class="tc-body">
                            <div class="tc-name">Character Constants</div>
                            <div class="tc-desc">Single characters or strings of characters enclosed in quotes. The scanner identifies their boundaries using the quote delimiters.</div>
                            <span class="tc-ex">'a' &nbsp; "hello" &nbsp; "world\n"</span>
                        </div>
                    </div>

                    <div class="tc-card cls-6">
                        <div class="tc-num">6</div>
                        <div class="tc-body">
                            <div class="tc-name">Special Characters</div>
                            <div class="tc-desc">Characters used as delimiters by the grammar. These are generally single-character words. Some may have no value part — e.g. a left parenthesis is its own token class.</div>
                            <span class="tc-ex">. &nbsp; ( &nbsp; ) &nbsp; { &nbsp; } &nbsp; ; &nbsp; ,</span>
                        </div>
                    </div>

                    <div class="tc-card cls-x">
                        <div class="tc-num" style="background:#FAFAFA; color:#94A3B8; border:1px solid #E2E8F0;">7</div>
                        <div class="tc-body">
                            <div class="tc-name">Comments <span style="font-size:11px; color:var(--muted);">(discarded)</span></div>
                            <div class="tc-desc">Must be <em>detected and identified</em> by the scanner so they are correctly skipped, but are <strong>not passed on</strong> as tokens to the next phase.</div>
                            <span class="tc-ex" style="background:#F1F5F9; color:#94A3B8;">/* ... */ &nbsp; // comment</span>
                        </div>
                    </div>

                    <div class="tc-card cls-x">
                        <div class="tc-num" style="background:#FAFAFA; color:#94A3B8; border:1px solid #E2E8F0;">8</div>
                        <div class="tc-body">
                            <div class="tc-name">White Space <span style="font-size:11px; color:var(--muted);">(discarded)</span></div>
                            <div class="tc-desc">Spaces and tabs serve only as <em>delimiters</em> between tokens in most languages. They are identified but <strong>not put out</strong> as tokens.</div>
                            <span class="tc-ex" style="background:#F1F5F9; color:#94A3B8;">space &nbsp; tab</span>
                        </div>
                    </div>

                    <div class="tc-card cls-x" style="grid-column: 1 / -1;">
                        <div class="tc-num" style="background:#FAFAFA; color:#94A3B8; border:1px solid #E2E8F0;">9</div>
                        <div class="tc-body">
                            <div class="tc-name">Newline <span style="font-size:11px; color:var(--muted);">(depends on language)</span></div>
                            <div class="tc-desc">In <em>free-format</em> languages (like Java), newline characters are ignored just like white space. In languages where layout matters (like Python or FORTRAN), a newline token <em>is</em> produced. Java is free-format.</div>
                            <span class="tc-ex" style="background:#F1F5F9; color:#94A3B8;">\n &nbsp; (ignored in Java)</span>
                        </div>
                    </div>

                </div>

                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>
                        <strong>Keywords vs Identifiers:</strong> Keywords have a <em>predefined meaning</em> to the compiler.
                        <strong>Reserved words</strong> are keywords that are <em>unavailable</em> to the programmer as identifiers.
                        In Java and C, every keyword is reserved. PL/1 is a notable exception — it has no reserved words at all.
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 2.1.2 Word Boundaries                         -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="word-bound">
                <h2><span class="sec-icon">🔲</span> Word Boundaries — Annotated Example</h2>
                <p>
                    The following Java source line illustrates exactly where the scanner finds word boundaries
                    and which class each token belongs to. Hover over any token to see its class.
                </p>

                <div class="src-line-wrap">
                    <div style="font-size:11px; color:rgba(205,214,244,.35); margin-bottom:10px; font-family:'JetBrains Mono',monospace; letter-spacing:.5px; text-transform:uppercase;">Source input</div>
                    <div class="src-line">
                        <div class="src-tok"><span class="word w1">while</span><span class="cls-tag">1</span></div>
                        <div class="src-tok"><span class="word w6">(</span><span class="cls-tag">6</span></div>
                        <div class="src-tok"><span class="word w2">x33</span><span class="cls-tag">2</span></div>
                        <div class="src-tok"><span class="word w3">&lt;=</span><span class="cls-tag">3</span></div>
                        <div class="src-tok"><span class="word w4">2.5e+33</span><span class="cls-tag">4</span></div>
                        <div class="src-tok"><span class="word w3">-</span><span class="cls-tag">3</span></div>
                        <div class="src-tok"><span class="word w2">total</span><span class="cls-tag">2</span></div>
                        <div class="src-tok"><span class="word w6">)</span><span class="cls-tag">6</span></div>
                        <div class="src-tok"><span class="word w2">calc</span><span class="cls-tag">2</span></div>
                        <div class="src-tok"><span class="word w6">(</span><span class="cls-tag">6</span></div>
                        <div class="src-tok"><span class="word w2">x33</span><span class="cls-tag">2</span></div>
                        <div class="src-tok"><span class="word w6">)</span><span class="cls-tag">6</span></div>
                        <div class="src-tok"><span class="word w6">;</span><span class="cls-tag">6</span></div>
                        <div class="src-tok"><span class="word wx">//!</span><span class="cls-tag" style="color:rgba(148,163,184,.4);">ignored</span></div>
                    </div>
                    <div style="display:flex; flex-wrap:wrap; gap:10px; margin-top:4px;">
                        <span style="font-size:11px; color:rgba(252,211,77,.8); font-family:'JetBrains Mono',monospace;">■ 1 keyword</span>
                        <span style="font-size:11px; color:rgba(147,197,253,.8); font-family:'JetBrains Mono',monospace;">■ 2 identifier</span>
                        <span style="font-size:11px; color:rgba(252,165,165,.8); font-family:'JetBrains Mono',monospace;">■ 3 operator</span>
                        <span style="font-size:11px; color:rgba(110,231,183,.8); font-family:'JetBrains Mono',monospace;">■ 4 numeric const</span>
                        <span style="font-size:11px; color:rgba(165,180,252,.8); font-family:'JetBrains Mono',monospace;">■ 6 special char</span>
                        <span style="font-size:11px; color:rgba(148,163,184,.5); font-family:'JetBrains Mono',monospace;">■ ignored</span>
                    </div>
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Lexical analysis does NOT check syntax.</strong> The scanner processes tokens one by one
                        without understanding their grammatical arrangement. An input like
                        <code>} while if ( {</code> would produce <em>five valid tokens</em> — syntax errors are only
                        caught in the next phase (syntax analysis / parser).
                    </div>
                </div>
            </section>

        

         
        


            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="c2_1_0.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>2.0 Lexical Tokens</div>
                </a>
                <a href="?complete=5" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next section</span>2.1.2 Token Stream Output</div>
                    <span>→</span>
                </a>
            </div>

        </article>
    </div><!-- /.note-container -->

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

        // ── Interactive tokeniser ────────────────────────────────
        const KEYWORDS = new Set(['while', 'if', 'else', 'for', 'int', 'float', 'return', 'class',
            'public', 'static', 'void', 'new', 'true', 'false', 'null', 'this', 'do', 'break',
            'continue', 'import', 'package', 'try', 'catch', 'finally', 'throw', 'throws',
            'String', 'boolean', 'double', 'long', 'char', 'byte', 'short', 'extends', 'implements',
            'interface', 'abstract', 'final', 'super'
        ]);
        const OPERATORS = new Set(['+', '-', '*', '/', '=', '<', '>', '!', '&', '|', '^', '~', '%',
            '<=', '>=', '==', '!=', '&&', '||', '++', '--', '+=', '-=', '*=', '/=', '%=', '->'
        ]);
        const SPECIALS = new Set(['.', '(', ')', '[', ']', '{', '}', ';', ',', ':', '?', '@']);

        const examples = [
            'while ( x33 <= 2.5e+33 - total ) calc ( x33 ) ; //!',
            'for (i=1; i<=fin+3.5e6; i=i*3)\n  ac = ac + /* incr */ 1;',
            'if (sum != 133) /* sum=133 */ x = 0;'
        ];

        function setExample(i) {
            document.getElementById('tok-input').value = examples[i];
            tokenise();
        }

        const classInfo = {
            1: {
                name: 'Keyword',
                chipClass: 't1',
                colour: '#92400E'
            },
            2: {
                name: 'Identifier',
                chipClass: 't2',
                colour: '#1D4ED8'
            },
            3: {
                name: 'Operator',
                chipClass: 't3',
                colour: '#9F1239'
            },
            4: {
                name: 'Numeric const',
                chipClass: 't4',
                colour: '#065F46'
            },
            5: {
                name: 'Char constant',
                chipClass: 't5',
                colour: '#6B21A8'
            },
            6: {
                name: 'Special char',
                chipClass: 't6',
                colour: '#4338CA'
            },
            7: {
                name: 'Comment',
                chipClass: 'tx',
                colour: '#64748B'
            },
            8: {
                name: 'White space',
                chipClass: 'tx',
                colour: '#94A3B8'
            },
        };

        function tokenise() {
            const src = document.getElementById('tok-input').value;
            const tokens = lex(src);
            const chips = document.getElementById('tok-chips');
            const tbody = document.getElementById('tok-tbody');
            chips.innerHTML = '';
            tbody.innerHTML = '';

            let n = 0;
            tokens.forEach(t => {
                const info = classInfo[t.cls];
                // chip
                const chip = document.createElement('div');
                chip.className = 'tok-chip ' + info.chipClass;
                chip.innerHTML = `<span class="tc-v">${escHtml(t.val)}</span><span class="tc-c">cls ${t.cls}</span>`;
                chip.title = `Class ${t.cls}: ${info.name}`;
                chips.appendChild(chip);

                // table row
                if (t.cls <= 6) n++;
                const tr = document.createElement('tr');
                tr.style.opacity = t.cls > 6 ? '.45' : '1';
                const noteMap = {
                    1: 'Predefined meaning',
                    2: '→ symbol table ptr',
                    3: 'Operator code',
                    4: '→ constant table ptr',
                    5: 'String/char literal',
                    6: 'Delimiter code',
                    7: 'Discarded — no token output',
                    8: 'Discarded — no token output'
                };
                tr.innerHTML = `
          <td style="color:var(--muted); font-size:12px; font-family:'JetBrains Mono',monospace;">${t.cls <= 6 ? n : '—'}</td>
          <td><span class="badge-cls tok-chip ${info.chipClass}" style="padding:2px 8px; font-size:11px;">${t.cls}</span></td>
          <td style="color:var(--text); font-size:13px;">${info.name}</td>
          <td style="font-family:'JetBrains Mono',monospace; font-size:12px; color:var(--purple);">${escHtml(t.val)}</td>
          <td style="font-size:12px; color:var(--muted); font-style:italic;">${noteMap[t.cls] || ''}</td>
        `;
                tbody.appendChild(tr);
            });

            document.getElementById('tok-output').style.display = 'block';
        }

        function escHtml(s) {
            return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        function lex(src) {
            const tokens = [];
            let i = 0;
            while (i < src.length) {
                // whitespace
                if (/[ \t\r\n]/.test(src[i])) {
                    i++;
                    continue;
                }
                // line comment
                if (src[i] === '/' && src[i + 1] === '/') {
                    let s = i;
                    while (i < src.length && src[i] !== '\n') i++;
                    tokens.push({
                        cls: 7,
                        val: src.slice(s, i)
                    });
                    continue;
                }
                // block comment
                if (src[i] === '/' && src[i + 1] === '*') {
                    let s = i;
                    i += 2;
                    while (i < src.length && !(src[i - 1] === '*' && src[i] === '/')) i++;
                    i++;
                    tokens.push({
                        cls: 7,
                        val: src.slice(s, i)
                    });
                    continue;
                }
                // string literal
                if (src[i] === '"') {
                    let s = i;
                    i++;
                    while (i < src.length && src[i] !== '"') {
                        if (src[i] === '\\') i++;
                        i++;
                    }
                    i++;
                    tokens.push({
                        cls: 5,
                        val: src.slice(s, i)
                    });
                    continue;
                }
                // char literal
                if (src[i] === "'") {
                    let s = i;
                    i++;
                    while (i < src.length && src[i] !== "'") {
                        if (src[i] === '\\') i++;
                        i++;
                    }
                    i++;
                    tokens.push({
                        cls: 5,
                        val: src.slice(s, i)
                    });
                    continue;
                }
                // numeric constant
                if (/[0-9]/.test(src[i]) || (src[i] === '.' && /[0-9]/.test(src[i + 1] || ''))) {
                    let s = i;
                    while (i < src.length && /[0-9]/.test(src[i])) i++;
                    if (i < src.length && src[i] === '.') {
                        i++;
                        while (i < src.length && /[0-9]/.test(src[i])) i++;
                    }
                    if (i < src.length && /[eE]/.test(src[i])) {
                        i++;
                        if (/[+\-]/.test(src[i] || '')) i++;
                        while (i < src.length && /[0-9]/.test(src[i])) i++;
                    }
                    tokens.push({
                        cls: 4,
                        val: src.slice(s, i)
                    });
                    continue;
                }
                // identifier or keyword
                if (/[a-zA-Z_$]/.test(src[i])) {
                    let s = i;
                    while (i < src.length && /[a-zA-Z0-9_$]/.test(src[i])) i++;
                    const word = src.slice(s, i);
                    tokens.push({
                        cls: KEYWORDS.has(word) ? 1 : 2,
                        val: word
                    });
                    continue;
                }
                // two-char operators
                const two = src.slice(i, i + 2);
                if (OPERATORS.has(two)) {
                    tokens.push({
                        cls: 3,
                        val: two
                    });
                    i += 2;
                    continue;
                }
                // single-char operator
                if (OPERATORS.has(src[i])) {
                    tokens.push({
                        cls: 3,
                        val: src[i]
                    });
                    i++;
                    continue;
                }
                // special
                if (SPECIALS.has(src[i])) {
                    tokens.push({
                        cls: 6,
                        val: src[i]
                    });
                    i++;
                    continue;
                }
                // unknown — skip
                i++;
            }
            return tokens;
        }

        // run on load
        tokenise();

       
    </script>

</body>

</html>