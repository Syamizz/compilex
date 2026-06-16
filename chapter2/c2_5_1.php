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
$page = 19;
$nextPage = "c2_5_2.php";

if (isset($_GET['complete']) && $_GET['complete'] == '19') {
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
    <title>Chapter 2.5 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c2/c2_5.css">
</head>

<body>

    <?php include 'navbar_c2.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#helpers" class="toc-link">2.5.1 Helpers Section</a></li>
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
                <div class="chapter-tag">📘 Chapter 2 · Section 2.5</div>
                <h1>Case Study: Lexical Analysis for Decaf</h1>
                <div class="metadata">
                    <span>⏱ 16 min read</span>
                    <span>☕ Decaf</span>
                    <span>🛠️ SableCC</span>
                </div>
            </header>



            <!-- ══════════════════════════════════════════════ -->
            <!-- 2.5.1 Helpers Section                         -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="helpers">
                <h2><span class="sec-icon">🔧</span> 2.5.1 The Helpers Section</h2>
                <p>
                    The Helpers section defines reusable sub-definitions (macros) used within the Tokens section.
                    There is <strong>no States section</strong> in <code>decaf.grammar</code> — comments are
                    handled without states.
                </p>

                <table class="helper-table">
                    <thead>
                        <tr>
                            <th>Helper</th>
                            <th>Definition</th>
                            <th>Description</th>
                            <th>Example</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>letter</td>
                            <td>['a'..'z'] | ['A'..'Z']</td>
                            <td>Any single letter, upper or lower case</td>
                            <td>w</td>
                        </tr>
                        <tr>
                            <td>digit</td>
                            <td>['0'..'9']</td>
                            <td>Any single numeric digit</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>digits</td>
                            <td>digit+</td>
                            <td>A string of one or more digits</td>
                            <td>2040099</td>
                        </tr>
                        <tr>
                            <td>exp</td>
                            <td>['e'+'E'] ['+'+ '-']? digits</td>
                            <td>Exponent part of a numeric constant (e or E, optional sign, digits)</td>
                            <td>E-34</td>
                        </tr>
                        <tr>
                            <td>newline</td>
                            <td>[10 + 13]</td>
                            <td>End-of-line character (LF or CR for cross-platform compatibility)</td>
                            <td>'\n'</td>
                        </tr>
                        <tr>
                            <td>non_star</td>
                            <td>[[0..0xffff] - '*']</td>
                            <td>Any Unicode character that is <em>not</em> an asterisk — used for multi-line comments</td>
                            <td>/</td>
                        </tr>
                        <tr>
                            <td>non_slash</td>
                            <td>[[0..0xffff] - '/']</td>
                            <td>Any Unicode character that is <em>not</em> a forward slash — used for multi-line comments</td>
                            <td>*</td>
                        </tr>
                        <tr>
                            <td>non_star_slash</td>
                            <td>[[0..0xffff] - ['*'+'/']]</td>
                            <td>Any Unicode character except asterisk or slash — used in the middle body of a multi-line comment</td>
                            <td>$</td>
                        </tr>
                    </tbody>
                </table>

                <div class="code-wrap">
                    <div class="code-header">
                        <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">decaf.grammar — Helpers section</span><button class="copy-btn" onclick="copyBlock('helpers-code',this)">Copy</button>
                    </div>
                    <pre><code id="helpers-code"><span class="kw">Helpers</span>                                       <span class="cm">// Examples</span>
  letter        = [<span class="str">'a'</span>..<span class="str">'z'</span>] | [<span class="str">'A'</span>..<span class="str">'Z'</span>] ;     <span class="cm">// w</span>
  digit         = [<span class="str">'0'</span>..<span class="str">'9'</span>] ;                 <span class="cm">// 3</span>
  digits        = digit+ ;                       <span class="cm">// 2040099</span>
  exp           = [<span class="str">'e'</span> + <span class="str">'E'</span>] [<span class="str">'+'</span> + <span class="str">'-'</span>]? digits ; <span class="cm">// E-34</span>
  newline       = [<span class="num">10</span> + <span class="num">13</span>] ;                  <span class="cm">// '\n'</span>
  non_star      = [[<span class="num">0</span>..<span class="num">0xffff</span>] - <span class="str">'*'</span>] ;        <span class="cm">// /</span>
  non_slash     = [[<span class="num">0</span>..<span class="num">0xffff</span>] - <span class="str">'/'</span>] ;        <span class="cm">// *</span>
  non_star_slash = [[<span class="num">0</span>..<span class="num">0xffff</span>] - [<span class="str">'*'</span> + <span class="str">'/'</span>]] ; <span class="cm">// $</span></code></pre>
                </div>
            </section>











            <div class="chapter-nav">
                <a href="c2_5_0.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>2.5.0 Case Study: Lexical Analysis for Decaf</div>
                </a>
                <a href="?complete=19" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>2.5.2 Tokens Section</div>
                    <span>→</span>
                </a>
            </div>

        </article>
    </div>

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

        function copyBlock(id, btn) {
            navigator.clipboard.writeText(document.getElementById(id).innerText).then(() => {
                btn.textContent = 'Copied!';
                setTimeout(() => btn.textContent = 'Copy', 1800);
            });
        }

        // ════════════════════════════════════════════════════════
        // Decaf Scanner
        // ════════════════════════════════════════════════════════
        const DECAF_KEYWORDS = new Set([
            'class', 'public', 'static', 'void', 'main', 'String', 'int', 'float',
            'for', 'while', 'if', 'else'
        ]);
        const COMPARE_OPS = new Set(['==', '<', '>', '<=', '>=', '!=']);
        const SINGLE_OPS = {
            '+': 'plus',
            '-': 'minus',
            '*': 'mult',
            '/': 'div',
            '=': 'assign',
            '(': 'l_par',
            ')': 'r_par',
            '{': 'l_brace',
            '}': 'r_brace',
            '[': 'l_bracket',
            ']': 'r_bracket',
            ',': 'comma',
            ';': 'semi'
        };

        const presets = [
            `float cos, x;\nx = 3.14159;\nwhile (x > 0.0001) {\n  x = x * 2.0E-1;\n} // end while`,
            `/* multi-line\n   comment */\nx = 1; // single line comment\ny = 2;`,
            `class Foo {\n  public static void main(String[] args) {\n    if (a != b) { x = 0; }\n  }\n}`,
            `int n = 42;\nfloat f = 3.14;\nfloat g = .5;\nfloat h = 1.5e10;\nfloat j = 2.0E-3;`
        ];

        function setScanPreset(i) {
            document.getElementById('scan-input').value = presets[i];
            runDecafScanner();
        }

        function decafLex(src) {
            const tokens = [];
            let i = 0;
            while (i < src.length) {
                // newline / whitespace
                if (/[\s]/.test(src[i])) {
                    i++;
                    continue;
                }

                // line comment
                if (src[i] === '/' && src[i + 1] === '/') {
                    let s = i;
                    while (i < src.length && src[i] !== '\n') i++;
                    if (i < src.length) i++; // consume newline
                    tokens.push({
                        cls: 'comment1',
                        val: src.slice(s, i).trim(),
                        ignored: true
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
                        cls: 'comment2',
                        val: src.slice(s, i),
                        ignored: true
                    });
                    continue;
                }
                // string constant (not in Decaf grammar but for safety)
                if (src[i] === '"') {
                    let s = i;
                    i++;
                    while (i < src.length && src[i] !== '"') i++;
                    i++;
                    tokens.push({
                        cls: 'misc',
                        val: src.slice(s, i),
                        ignored: false
                    });
                    continue;
                }
                // number — must start with digit OR dot followed by digit
                if (/[0-9]/.test(src[i]) || (src[i] === '.' && /[0-9]/.test(src[i + 1] || ''))) {
                    let s = i;
                    if (/[0-9]/.test(src[i])) {
                        while (i < src.length && /[0-9]/.test(src[i])) i++;
                        if (i < src.length && src[i] === '.') {
                            i++;
                            while (i < src.length && /[0-9]/.test(src[i])) i++;
                        }
                    } else {
                        i++;
                        while (i < src.length && /[0-9]/.test(src[i])) i++;
                    }
                    if (i < src.length && /[eE]/.test(src[i])) {
                        i++;
                        if (/[+\-]/.test(src[i] || '')) i++;
                        while (i < src.length && /[0-9]/.test(src[i])) i++;
                    }
                    tokens.push({
                        cls: 'number',
                        val: src.slice(s, i),
                        ignored: false
                    });
                    continue;
                }
                // identifier or keyword
                if (/[a-zA-Z_]/.test(src[i])) {
                    let s = i;
                    while (i < src.length && /[a-zA-Z0-9_]/.test(src[i])) i++;
                    const w = src.slice(s, i);
                    const cls = DECAF_KEYWORDS.has(w) ? 'keyword' : 'identifier';
                    tokens.push({
                        cls,
                        val: w,
                        ignored: false
                    });
                    continue;
                }
                // two-char operators
                const two = src.slice(i, i + 2);
                if (COMPARE_OPS.has(two)) {
                    tokens.push({
                        cls: 'compare',
                        val: two,
                        ignored: false
                    });
                    i += 2;
                    continue;
                }
                // single-char
                if (src[i] === '=') {
                    tokens.push({
                        cls: 'assign',
                        val: '=',
                        ignored: false
                    });
                    i++;
                    continue;
                }
                if (SINGLE_OPS[src[i]]) {
                    tokens.push({
                        cls: SINGLE_OPS[src[i]],
                        val: src[i],
                        ignored: false
                    });
                    i++;
                    continue;
                }
                if (COMPARE_OPS.has(src[i])) {
                    tokens.push({
                        cls: 'compare',
                        val: src[i],
                        ignored: false
                    });
                    i++;
                    continue;
                }
                // misc
                tokens.push({
                    cls: 'misc',
                    val: src[i],
                    ignored: false
                });
                i++;
            }
            return tokens;
        }

        const classStyle = {
            keyword: 'sc-kw',
            identifier: 'sc-id',
            number: 'sc-num',
            compare: 'sc-op',
            assign: 'sc-op',
            plus: 'sc-op',
            minus: 'sc-op',
            mult: 'sc-op',
            div: 'sc-op',
            l_par: 'sc-op',
            r_par: 'sc-op',
            l_brace: 'sc-op',
            r_brace: 'sc-op',
            l_bracket: 'sc-op',
            r_bracket: 'sc-op',
            comma: 'sc-op',
            semi: 'sc-op',
            comment1: 'sc-cm',
            comment2: 'sc-cm',
            space: 'sc-sp',
            misc: 'sc-misc'
        };

        function runDecafScanner() {
            const src = document.getElementById('scan-input').value;
            const tokens = decafLex(src);
            const chips = document.getElementById('scan-chips');
            const tbody = document.getElementById('scan-tbody');
            chips.innerHTML = '';
            tbody.innerHTML = '';

            let n = 0;
            tokens.forEach(t => {
                const style = classStyle[t.cls] || 'sc-misc';
                // chip
                const div = document.createElement('div');
                div.className = 'sc-chip ' + style + (t.ignored ? ' sc-cm' : '');
                div.style.opacity = t.ignored ? '.45' : '1';
                div.innerHTML = `<span class="sc-type">${t.cls}</span><span class="sc-val">${escHtml(t.val.length > 14 ? t.val.slice(0,12)+'…' : t.val)}</span>`;
                chips.appendChild(div);

                // table row
                if (!t.ignored) n++;
                const tr = document.createElement('tr');
                tr.style.opacity = t.ignored ? '.45' : '1';
                tr.innerHTML = `
          <td>${t.ignored ? '—' : n}</td>
          <td><span class="sc-chip ${style}" style="padding:3px 10px;font-size:11px;display:inline-flex;gap:4px;"><span class="sc-val">${t.cls}</span></span></td>
          <td style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--text);">${escHtml(t.val)}</td>
          <td style="font-size:12px;">${t.ignored ? '<span style="color:var(--muted);">❌ Ignored</span>' : '<span style="color:var(--green);">✅ Yes</span>'}</td>
        `;
                tbody.appendChild(tr);
            });
            document.getElementById('scan-out').style.display = 'block';
        }

        function escHtml(s) {
            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        // run on load
        runDecafScanner();
    </script>

</body>

</html>