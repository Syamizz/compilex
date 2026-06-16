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
$page = 7;
$nextPage = "c2_2_0.php";

if (isset($_GET['complete']) && $_GET['complete'] == '7') {
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
                <li><a href="#numconst" class="toc-link">2.1.3 Numeric Constants</a></li>
                <li><a href="#variations" class="toc-link">Design Variations</a></li>
                <li><a href="#interactive" class="toc-link">Interactive Tokeniser</a></li>
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
            <!-- 2.1.3 Numeric Constants                       -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="numconst">
                <h2><span class="sec-icon">🔢</span> 2.1.3 Numeric Constants — Conversion</h2>
                <p>
                    Numeric constants must be <strong>converted from string form to a numeric format</strong> before
                    arithmetic can be performed. The compiler initially sees all input as characters — the string
                    <code>"3.4e+6"</code> is a sequence of six characters, not a floating-point number.
                </p>
                <p>
                    Converting this to an actual floating-point or integer binary value is non-trivial. Most compiler
                    writers use <strong>library routines</strong> to handle this conversion. The converted value is
                    stored in a <strong>constant table</strong>, and the token value is a pointer to that entry.
                </p>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Input string</th>
                            <th>Interpretation</th>
                            <th>Stored as</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>124</code></td>
                            <td>Integer literal</td>
                            <td>32-bit int binary: 0x0000007C</td>
                        </tr>
                        <tr>
                            <td><code>12.35</code></td>
                            <td>Floating-point literal</td>
                            <td>IEEE 754 float</td>
                        </tr>
                        <tr>
                            <td><code>0.09E-23</code></td>
                            <td>Scientific notation float</td>
                            <td>IEEE 754 double</td>
                        </tr>
                        <tr>
                            <td><code>2.5e+33</code></td>
                            <td>Scientific notation float</td>
                            <td>IEEE 754 double</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Case sensitivity:</strong> If the source language is <em>not</em> case sensitive, the scanner must normalise all alphabetic characters to upper or lower case (often via a preprocessor) before tokenisation. <strong>Java is case sensitive</strong> — <code>While</code> and <code>while</code> are different tokens.
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 2.1.6 Design Variations                       -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="variations">
                <h2><span class="sec-icon">⚙️</span>Design Variations</h2>
                <p>
                    The basic scheme of one token per word has variations that can improve efficiency
                    or simplify later phases:
                </p>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Variation</th>
                            <th>Description</th>
                            <th>Example</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Combined assignment token</td>
                            <td>When an identifier is immediately followed by <code>=</code>, emit a single <em>assignment token</em> whose value is the symbol table pointer for the identifier. Reduces two tokens to one.</td>
                            <td><code>x =</code> → one token (class: assign, value: ptr to x)</td>
                        </tr>
                        <tr>
                            <td>Keyword as its own class</td>
                            <td>Each keyword gets a unique token class (rather than all keywords sharing class 1). Increases the number of classes significantly but simplifies the syntax analysis phase.</td>
                            <td><code>while</code> → class WHILE; <code>if</code> → class IF</td>
                        </tr>
                        <tr>
                            <td>No value for unique tokens</td>
                            <td>Single-character specials and keywords have only one possible value — so no value part is needed, saving space.</td>
                            <td><code>(</code> → just class 6-LPAREN, no value</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        <strong>Scanner stores into string space:</strong> In block-structured languages, the scanner can simply store each identifier text into a <em>string space array</em> and return a pointer to its first character. The symbol table itself can be built during the parse phase when block boundaries are known.
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Interactive Tokeniser                         -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="interactive">
                <h2><span class="sec-icon">▶️</span> Interactive Java Tokeniser</h2>
                <p>
                    Type (or paste) a Java source snippet below and click <strong>Tokenise</strong>.
                    The scanner will identify each word boundary and classify every token according
                    to the 9-class scheme from Section 2.1.
                </p>

                <div class="tok-wrap">
                    <div class="tok-header">
                        <h4>🔤 Lexical Scanner Simulator</h4>
                        <span style="font-size:12px; color:rgba(255,255,255,.7);">Classes 7–9 are identified but discarded</span>
                    </div>
                    <div class="tok-body">
                        <textarea class="tok-textarea" id="tok-input" spellcheck="false" placeholder="e.g.  while ( x33 <= 2.5e+33 - total ) calc ( x33 ) ; //!">while ( x33 <= 2.5e+33 - total ) calc ( x33 ) ; //!</textarea>
                        <button class="tok-btn" onclick="tokenise()">🔍 Tokenise</button>
                        <div style="margin-top:8px; display:flex; gap:8px; flex-wrap:wrap;">
                            <button onclick="setExample(0)" style="font-size:12px; padding:4px 12px; border-radius:6px; border:1px solid rgba(99,102,241,.3); background:transparent; color:var(--purple); cursor:pointer; font-family:'DM Sans',sans-serif;">Example 1</button>
                            <button onclick="setExample(1)" style="font-size:12px; padding:4px 12px; border-radius:6px; border:1px solid rgba(99,102,241,.3); background:transparent; color:var(--purple); cursor:pointer; font-family:'DM Sans',sans-serif;">Example 2</button>
                            <button onclick="setExample(2)" style="font-size:12px; padding:4px 12px; border-radius:6px; border:1px solid rgba(99,102,241,.3); background:transparent; color:var(--purple); cursor:pointer; font-family:'DM Sans',sans-serif;">Example 3</button>
                        </div>

                        <div class="tok-output" id="tok-output" style="display:none;">
                            <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:13px; color:var(--text); margin-bottom:10px;">Token stream:</div>
                            <div class="tok-stream" id="tok-chips"></div>

                            <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:13px; color:var(--text); margin:18px 0 8px;">Token table:</div>
                            <table class="tok-table" id="tok-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Class</th>
                                        <th>Class Name</th>
                                        <th>Value / Lexeme</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody id="tok-tbody"></tbody>
                            </table>

                            <div class="tok-legend">
                                <div class="tok-leg-item">
                                    <div class="tok-leg-dot" style="background:#F59E0B;"></div> 1 Keyword
                                </div>
                                <div class="tok-leg-item">
                                    <div class="tok-leg-dot" style="background:#3B82F6;"></div> 2 Identifier
                                </div>
                                <div class="tok-leg-item">
                                    <div class="tok-leg-dot" style="background:#EF4444;"></div> 3 Operator
                                </div>
                                <div class="tok-leg-item">
                                    <div class="tok-leg-dot" style="background:#10B981;"></div> 4 Numeric const
                                </div>
                                <div class="tok-leg-item">
                                    <div class="tok-leg-dot" style="background:#A855F7;"></div> 5 Char constant
                                </div>
                                <div class="tok-leg-item">
                                    <div class="tok-leg-dot" style="background:#6366F1;"></div> 6 Special char
                                </div>
                                <div class="tok-leg-item">
                                    <div class="tok-leg-dot" style="background:#94A3B8;"></div> ignored
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="c2_1_2.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>2.1.2 Token Stream Output</div>
                </a>
                <a href="?complete=7" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>2.2 Implementing a Scanner</div>
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