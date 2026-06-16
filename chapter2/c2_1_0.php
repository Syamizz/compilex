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
$page = 4;
$nextPage = "c2_1_1.php";

if (isset($_GET['complete']) && $_GET['complete'] == '4') {
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
                <li><a href="#overview" class="toc-link">2.1 Overview</a></li>
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
            <!-- 2.1 Overview                                   -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="overview">
                <h2><span class="sec-icon">🔤</span> 2.1 Lexical Tokens — Overview</h2>
                <p>
                    The first phase of a compiler is called <strong>lexical analysis</strong>. Because it scans the
                    input string <strong>without backtracking</strong> — reading each symbol exactly once and
                    processing it correctly — it is often called a <strong>lexical scanner</strong>.
                </p>
                <p>
                    Its job is to isolate the <strong>"words"</strong> in the input string. A word — also called a
                    <strong>lexeme</strong>, <strong>lexical item</strong>, or <strong>lexical token</strong> — is a
                    string of input characters taken as a single unit and passed on to the next phase of compilation.
                </p>

                <div class="note-box key">
                    <span class="box-icon">✅</span>
                    <div>
                        <strong>Output of this phase:</strong> A <em>stream of tokens</em>, one per word encountered.
                        Each token has two parts:
                        <ol style="margin: 8px 0 0 16px;">
                            <li><strong>Class</strong> — what <em>kind</em> of word it is (keyword, identifier, operator, …)</li>
                            <li><strong>Value</strong> — which specific <em>member</em> of that class (a pointer, a code, etc.)</li>
                        </ol>
                    </div>
                </div>
            </section>

          
 





            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="c2_0_3.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>2.0.3 Regular Expressions</div>
                </a>
                <a href="?complete=4" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>2.1.1 The Nine Token Classes</div>
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