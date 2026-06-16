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
$page = 20;
$nextPage = "../home.php";

if (isset($_GET['complete']) && $_GET['complete'] == '20') {
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
        <li><a href="#tokens" class="toc-link">2.5.2 Tokens Section</a></li>
        <li><a href="#comments" class="toc-link sub">↳ Comments</a></li>
        <li><a href="#keywords" class="toc-link sub">↳ Keywords</a></li>
        <li><a href="#operators" class="toc-link sub">↳ Operators &amp; Symbols</a></li>
        <li><a href="#id-num" class="toc-link sub">↳ Identifier &amp; Number</a></li>
        <li><a href="#full-grammar" class="toc-link">Full decaf.grammar</a></li>
        <li><a href="#scanner" class="toc-link">Interactive Scanner</a></li>
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
      <!-- 2.5.2 Tokens Section                          -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="tokens">
        <h2><span class="sec-icon">🏷️</span> 2.5.2 The Tokens Section</h2>
        <p>
          The Tokens section defines every token used in Decaf. Here is an overview of all token
          categories, organised by type:
        </p>

        <div class="tok-category">
          <div class="tok-cat-row cat-comment">
            <span class="tok-cat-label">Comments<br><small style="font-weight:400;">(ignored)</small></span>
            <div class="tok-cat-chips">
              <span class="tok-small">comment1</span>
              <span class="tok-small">comment2</span>
            </div>
          </div>
          <div class="tok-cat-row cat-space">
            <span class="tok-cat-label">Whitespace<br><small style="font-weight:400;">(ignored)</small></span>
            <div class="tok-cat-chips">
              <span class="tok-small">space</span>
            </div>
          </div>
          <div class="tok-cat-row cat-keyword">
            <span class="tok-cat-label">Keywords</span>
            <div class="tok-cat-chips">
              <span class="tok-small">clas</span><span class="tok-small">public</span><span class="tok-small">static</span>
              <span class="tok-small">void</span><span class="tok-small">main</span><span class="tok-small">string</span>
              <span class="tok-small">int</span><span class="tok-small">float</span><span class="tok-small">for</span>
              <span class="tok-small">while</span><span class="tok-small">if</span><span class="tok-small">else</span>
            </div>
          </div>
          <div class="tok-cat-row cat-operator">
            <span class="tok-cat-label">Operators &amp; Symbols</span>
            <div class="tok-cat-chips">
              <span class="tok-small">assign (=)</span><span class="tok-small">compare</span>
              <span class="tok-small">plus</span><span class="tok-small">minus</span><span class="tok-small">mult</span><span class="tok-small">div</span>
              <span class="tok-small">l_par</span><span class="tok-small">r_par</span><span class="tok-small">l_brace</span><span class="tok-small">r_brace</span>
              <span class="tok-small">l_bracket</span><span class="tok-small">r_bracket</span><span class="tok-small">comma</span><span class="tok-small">semi</span>
            </div>
          </div>
          <div class="tok-cat-row cat-ident">
            <span class="tok-cat-label">Identifier</span>
            <div class="tok-cat-chips"><span class="tok-small">identifier</span></div>
          </div>
          <div class="tok-cat-row cat-number">
            <span class="tok-cat-label">Number</span>
            <div class="tok-cat-chips"><span class="tok-small">number</span></div>
          </div>
          <div class="tok-cat-row cat-misc">
            <span class="tok-cat-label">Fallback</span>
            <div class="tok-cat-chips"><span class="tok-small">misc</span></div>
          </div>
        </div>
      </section>

      <!-- Comments -->
      <section id="comments">
        <h2><span class="sec-icon">💬</span> Comments — comment1 &amp; comment2</h2>
        <p>
          Decaf (like Java) has two kinds of comments. Both are listed as <strong>Ignored Tokens</strong>
          — the parser never sees them.
        </p>
        <table style="width:100%;border-collapse:collapse;font-size:14px;margin:16px 0;">
          <thead>
            <tr style="background:var(--purple-s);">
              <th style="padding:10px 14px;color:var(--purple);font-family:'Syne',sans-serif;font-weight:700;">Type</th>
              <th style="padding:10px 14px;color:var(--purple);font-family:'Syne',sans-serif;font-weight:700;">Syntax</th>
              <th style="padding:10px 14px;color:var(--purple);font-family:'Syne',sans-serif;font-weight:700;">Example</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style="padding:9px 14px;font-weight:600;color:var(--text);">comment1 (single-line)</td>
              <td style="padding:9px 14px;color:var(--muted);">Begins with <code>//</code>, terminates at newline</td>
              <td style="padding:9px 14px;font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--muted);">// this is a comment</td>
            </tr>
            <tr>
              <td style="padding:9px 14px;font-weight:600;color:var(--text);">comment2 (multi-line)</td>
              <td style="padding:9px 14px;color:var(--muted);">Begins with <code>/*</code>, ends with <code>*/</code></td>
              <td style="padding:9px 14px;font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--muted);">/* multi line */</td>
            </tr>
          </tbody>
        </table>

        <div class="code-wrap">
          <div class="code-header">
            <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">Comment token definitions</span>
          </div>
          <pre><code>comment1 = <span class="str">'//'</span> [[<span class="num">0</span>..<span class="num">0xffff</span>] - newline]* newline ;
<span class="cm">// Any characters (except newline) after '//', then a newline</span>

comment2 = <span class="str">'/*'</span> non_star*
           (<span class="sym">non_star_slash</span> non_star* <span class="str">'*'</span>+)* <span class="str">'/'</span> ;
<span class="cm">// Designed using an FSM model (see Exercise 2.2.4)</span>
<span class="cm">// Handles all edge cases: /**/, /***/, /* a * b */</span></code></pre>
        </div>

        <div class="note-box warn">
          <span class="box-icon">⚠️</span>
          <div>
            <strong>comment2 is carefully designed.</strong> The pattern uses <code>non_star</code>,
            <code>non_star_slash</code>, and sequences of <code>*+</code> to correctly handle all
            edge cases without accidentally ending the comment early or running past the true end.
            This definition was derived using a finite state machine model as a guide.
          </div>
        </div>
      </section>

      <!-- Keywords -->
      <section id="keywords">
        <h2><span class="sec-icon">🔑</span> Keywords &amp; the <code>clas</code> Special Case</h2>
        <p>
          Each Decaf keyword is defined as itself — a string literal in single quotes.
          Because keywords are listed <em>before</em> <code>identifier</code> in the Tokens section,
          they take priority when the same string is matched.
        </p>

        <div class="note-box warn">
          <span class="box-icon">⚠️</span>
          <div>
            <strong>The <code>clas</code> exception:</strong> SableCC does not permit the word
            <code>class</code> as a token name (it conflicts with Java's reserved word <code>class</code>).
            So the Decaf keyword <code>class</code> is named <code>clas</code> (shortened by one letter)
            in the grammar file.
          </div>
        </div>

        <div class="note-box tip">
          <span class="box-icon">💡</span>
          <div>
            <strong>Case-insensitive languages:</strong> For a language like BASIC or Pascal, each keyword
            would need to accept both cases. For example, <code>while</code> would be defined as:
            <br><code>while = ['w'+'W']['h'+'H']['i'+'I']['l'+'L']['e'+'E'];</code><br>
            Alternatively, a preprocessor converts all letters to lowercase before scanning.
            <strong>Java (and Decaf) is case-sensitive</strong>, so simple string literals suffice.
          </div>
        </div>
      </section>

      <!-- Operators -->
      <section id="operators">
        <h2><span class="sec-icon">⚙️</span> Operators, Symbols &amp; compare</h2>
        <p>
          All arithmetic operators, delimiters, and brackets are given individual names in Decaf.
          The <strong>compare</strong> token covers all six relational operators.
        </p>

        <div class="code-wrap">
          <div class="code-header">
            <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">Operators and special symbols</span>
          </div>
          <pre><code>assign    = <span class="str">'='</span> ;
compare   = <span class="str">'=='</span> | <span class="str">'<'</span> | <span class="str">'>'</span> | <span class="str">'<='</span> | <span class="str">'>='</span> | <span class="str">'!='</span> ;
plus      = <span class="str">'+'</span> ;
minus     = <span class="str">'-'</span> ;
mult      = <span class="str">'*'</span> ;
div       = <span class="str">'/'</span> ;
l_par     = <span class="str">'('</span> ;   r_par     = <span class="str">')'</span> ;
l_brace   = <span class="str">'{'</span> ;   r_brace   = <span class="str">'}'</span> ;
l_bracket = <span class="str">'['</span> ;   r_bracket = <span class="str">']'</span> ;
comma     = <span class="str">','</span> ;
semi      = <span class="str">';'</span> ;</code></pre>
        </div>

        <div class="note-box pro">
          <span class="box-icon">🧩</span>
          <div>
            Each operator and delimiter is given its own token name. This is <em>tedious</em> but unavoidable
            with SableCC — every token the parser will use must be individually declared.
            The <code>assign</code> token (<code>=</code>) is separate from <code>compare</code> (<code>==</code>):
            <code>==</code> has two characters and is always a longer match.
          </div>
        </div>
      </section>

      <!-- Identifier and Number -->
      <section id="id-num">
        <h2><span class="sec-icon">🔤</span> Identifier, Number &amp; misc</h2>

        <div class="code-wrap">
          <div class="code-header">
            <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">identifier, number, misc</span>
          </div>
          <pre><code>identifier = letter (letter | digit | <span class="str">'_'</span>)* ;
<span class="cm">// A letter followed by 0 or more letters, digits, or underscores</span>
<span class="cm">// Must come AFTER keywords — longer match / first-listed rules apply</span>

number = (digits <span class="str">'.'</span>? digits? | <span class="str">'.'</span> digits) exp? ;
<span class="cm">// Accepts:  42    3.14    3.    .5    1.5e10    2.0E-3</span>
<span class="cm">// Uses the Helper exp for the optional exponent part</span>

misc = [<span class="num">0</span>..<span class="num">0xffff</span>] ;
<span class="cm">// Any single Unicode character not matched above</span>
<span class="cm">// Will most likely cause the parser to report a syntax error</span></code></pre>
        </div>

        <div class="note-box warn">
          <span class="box-icon">⚠️</span>
          <div>
            <strong>Number allows leading decimal point:</strong> The definition
            <code>digits '.'? digits? | '.' digits</code> accepts both <code>42</code> and
            <code>.5</code>. To <em>exclude</em> constants that don't begin with a digit (like
            <code>.25</code>), Exercise 2.5.3 asks you to revise this definition to use only
            <code>digits '.'? digits?</code>.
          </div>
        </div>
      </section>

      <!-- Full Grammar -->
      <section id="full-grammar">
        <h2><span class="sec-icon">📋</span> The Full decaf.grammar Tokens Section</h2>

        <div class="code-wrap">
          <div class="code-header">
            <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">decaf.grammar — complete Tokens section</span><button class="copy-btn" onclick="copyBlock('full-tokens',this)">Copy</button>
          </div>
          <pre><code id="full-tokens"><span class="kw">Tokens</span>
  <span class="cm">// ── Comments (listed in Ignored Tokens) ──────────────</span>
  comment1   = <span class="str">'//'</span> [[<span class="num">0</span>..<span class="num">0xffff</span>] - newline]* newline ;
  comment2   = <span class="str">'/*'</span> non_star*
               (non_star_slash non_star* <span class="str">'*'</span>+)* <span class="str">'/'</span> ;

  <span class="cm">// ── Whitespace (listed in Ignored Tokens) ────────────</span>
  space      = <span class="str">' '</span> | <span class="num">9</span> | newline ;   <span class="cm">// 9 = tab</span>

  <span class="cm">// ── Keywords (reserved) ──────────────────────────────</span>
  clas       = <span class="str">'class'</span> ;   <span class="cm">// 'class' is reserved in Java/SableCC</span>
  public     = <span class="str">'public'</span> ;
  static     = <span class="str">'static'</span> ;
  void       = <span class="str">'void'</span> ;
  main       = <span class="str">'main'</span> ;
  string     = <span class="str">'String'</span> ;
  int        = <span class="str">'int'</span> ;
  float      = <span class="str">'float'</span> ;
  for        = <span class="str">'for'</span> ;
  while      = <span class="str">'while'</span> ;
  if         = <span class="str">'if'</span> ;
  else       = <span class="str">'else'</span> ;

  <span class="cm">// ── Operators ─────────────────────────────────────────</span>
  assign     = <span class="str">'='</span> ;
  compare    = <span class="str">'=='</span> | <span class="str">'<'</span> | <span class="str">'>'</span> | <span class="str">'<='</span> | <span class="str">'>='</span> | <span class="str">'!='</span> ;
  plus       = <span class="str">'+'</span> ;
  minus      = <span class="str">'-'</span> ;
  mult       = <span class="str">'*'</span> ;
  div        = <span class="str">'/'</span> ;

  <span class="cm">// ── Delimiters ────────────────────────────────────────</span>
  l_par      = <span class="str">'('</span> ;   r_par      = <span class="str">')'</span> ;
  l_brace    = <span class="str">'{'</span> ;   r_brace    = <span class="str">'}'</span> ;
  l_bracket  = <span class="str">'['</span> ;   r_bracket  = <span class="str">']'</span> ;
  comma      = <span class="str">','</span> ;
  semi       = <span class="str">';'</span> ;

  <span class="cm">// ── Identifier and Number ─────────────────────────────</span>
  identifier = letter (letter | digit | <span class="str">'_'</span>)* ;
  number     = (digits <span class="str">'.'</span>? digits? | <span class="str">'.'</span> digits) exp? ;
  misc       = [<span class="num">0</span>..<span class="num">0xffff</span>] ;</code></pre>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Interactive Decaf Scanner                     -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="scanner">
        <h2><span class="sec-icon">▶️</span> Interactive Decaf Scanner</h2>
        <p>
          Type (or paste) a Decaf source snippet. The scanner will classify every token according to
          the <code>decaf.grammar</code> token definitions from Section 2.5. Comments and spaces are
          marked but shown dimmed (they would be <em>ignored</em> by the parser).
        </p>

        <div class="scanner-wrap">
          <div class="scanner-header">
            <h4>☕ Decaf Lexical Scanner</h4>
            <span style="font-size:12px;color:rgba(255,255,255,.7);">Based on decaf.grammar</span>
          </div>
          <div class="scanner-body">
            <textarea class="scanner-textarea" id="scan-input" spellcheck="false">float cos, x;
x = 3.14159;
while (x > 0.0001) {
  x = x * 2.0E-1;
} // end while</textarea>
            <button class="scanner-btn" onclick="runDecafScanner()">🔍 Scan</button>
            <div class="preset-btns">
              <button class="preset-btn" onclick="setScanPreset(0)">Cosine snippet</button>
              <button class="preset-btn" onclick="setScanPreset(1)">Comments test</button>
              <button class="preset-btn" onclick="setScanPreset(2)">Keywords + ops</button>
              <button class="preset-btn" onclick="setScanPreset(3)">Numbers test</button>
            </div>

            <div class="scanner-out" id="scan-out" style="display:none;">
              <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--text);margin-bottom:8px;">Token stream (comments &amp; spaces dimmed):</div>
              <div class="scanner-stream" id="scan-chips"></div>
              <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--text);margin:16px 0 8px;">Token table:</div>
              <table class="sc-table" id="scan-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Token class</th>
                    <th>Lexeme</th>
                    <th>Passed to parser?</th>
                  </tr>
                </thead>
                <tbody id="scan-tbody"></tbody>
              </table>
            </div>
          </div>
        </div>
      </section>



      <div class="chapter-nav">
        <a href="c2_5_1.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Previous page</span>2.5.1 Helpers Section</div>
        </a>
        <a href="?complete=20" class="nav-btn next">
          <div style="text-align:right"><span class="btn-sub">Done</span>Homepage</div>
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