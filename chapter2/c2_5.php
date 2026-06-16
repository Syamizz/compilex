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

  <?php include '../dashboard.php'; ?>

  <div class="note-container">

    <nav class="toc">
      <div class="toc-title">📖 Contents</div>
      <ul>
        <li><a href="#overview" class="toc-link">2.5 Overview</a></li>
        <li><a href="#helpers" class="toc-link">2.5.1 Helpers Section</a></li>
        <li><a href="#tokens" class="toc-link">2.5.2 Tokens Section</a></li>
        <li><a href="#comments" class="toc-link sub">↳ Comments</a></li>
        <li><a href="#keywords" class="toc-link sub">↳ Keywords</a></li>
        <li><a href="#operators" class="toc-link sub">↳ Operators &amp; Symbols</a></li>
        <li><a href="#id-num" class="toc-link sub">↳ Identifier &amp; Number</a></li>
        <li><a href="#full-grammar" class="toc-link">Full decaf.grammar</a></li>
        <li><a href="#scanner" class="toc-link">Interactive Scanner</a></li>
        <li><a href="#dragdrop" class="toc-link">🎯 Drag &amp; Drop Quiz</a></li>
        <li><a href="#quiz" class="toc-link">Check Your Knowledge</a></li>
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
      <!-- 2.5 Overview                                   -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="overview">
        <h2><span class="sec-icon">☕</span> 2.5 Overview</h2>
        <p>
          This section presents the <strong>lexical analysis phase</strong> for the Decaf language —
          the first phase of our complete case-study compiler. The lexical analysis is implemented in
          the <strong>Helpers</strong> and <strong>Tokens</strong> sections of the SableCC source file
          <code>decaf.grammar</code> (shown in full in Appendix B.2).
        </p>
        <p>
          The Decaf compiler is a <strong>two-pass compiler</strong>:
        </p>
        <ol>
          <li><strong>Pass 1 (SableCC)</strong> — Lexical + syntax analysis → produces a file of atoms and a file of numeric constants</li>
          <li><strong>Pass 2 (Code generator)</strong> — Translates atoms + constants into machine code for the <em>Mini</em> simulated machine</li>
        </ol>
        <div class="note-box pro">
          <span class="box-icon">🧩</span>
          <div>
            Comments and whitespace are listed in the <strong>Ignored Tokens</strong> section of the grammar — the parser never sees these tokens. A <code>Hashtable</code> class is used to implement both the symbol table and the numeric constant table (discussed further in Chapter 5).
          </div>
        </div>
      </section>

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

      <!-- ══════════════════════════════════════════════ -->
      <!-- DRAG AND DROP QUIZ                            -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="dragdrop">
        <h2><span class="sec-icon">🎯</span> Drag &amp; Drop — Decaf Grammar Concepts</h2>
        <p>Drag each chip into the correct drop zone. Each question has three chips and three zones.</p>

        <!-- DnD Q1 — Helper purpose -->
        <div class="dnd-quiz" id="dnd1">
          <div class="dnd-header" style="background:linear-gradient(135deg,#6366F1,#818CF8);">
            <div class="dnd-q-num" style="background:rgba(255,255,255,.2);color:white;">1</div>
            <div class="dnd-q-text" style="color:white;">Match each Decaf Helper to its purpose</div>
          </div>
          <div class="dnd-body">
            <p class="dnd-prompt">Drag the helper names into the correct description boxes.</p>
            <div class="dnd-bank" id="bank1" ondrop="dropChip(event,'bank1')" ondragover="allowDrop(event)">
              <div class="dnd-chip chip-a" draggable="true" ondragstart="dragStart(event)" id="chip-exp">exp</div>
              <div class="dnd-chip chip-b" draggable="true" ondragstart="dragStart(event)" id="chip-nonstar">non_star</div>
              <div class="dnd-chip chip-c" draggable="true" ondragstart="dragStart(event)" id="chip-digits">digits</div>
            </div>
            <div class="dnd-zones">
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#10B981;">One or more numeric digits</div>
                <div class="dnd-zone" id="z1a" ondrop="dropChip(event,'z1a')" ondragover="allowDrop(event)" data-answer="chip-digits"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#6366F1;">Exponent part like E-34 or e+2</div>
                <div class="dnd-zone" id="z1b" ondrop="dropChip(event,'z1b')" ondragover="allowDrop(event)" data-answer="chip-exp"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#F59E0B;">Any Unicode char except asterisk</div>
                <div class="dnd-zone" id="z1c" ondrop="dropChip(event,'z1c')" ondragover="allowDrop(event)" data-answer="chip-nonstar"></div>
              </div>
            </div>
            <button class="dnd-check" onclick="checkDnD('dnd1',['z1a','z1b','z1c'])">✓ Check</button>
            <button class="dnd-reset-btn" onclick="resetDnD('dnd1','bank1',['z1a','z1b','z1c'])">↺ Reset</button>
            <div class="dnd-feedback" id="fb-dnd1"></div>
          </div>
        </div>

        <!-- DnD Q2 — Token category -->
        <div class="dnd-quiz" id="dnd2">
          <div class="dnd-header" style="background:linear-gradient(135deg,#10B981,#34D399);">
            <div class="dnd-q-num" style="background:rgba(255,255,255,.2);color:white;">2</div>
            <div class="dnd-q-text" style="color:white;">Match each Decaf lexeme to its token class</div>
          </div>
          <div class="dnd-body">
            <p class="dnd-prompt">What token class does each of these produce in <code>decaf.grammar</code>?</p>
            <div class="dnd-bank" id="bank2" ondrop="dropChip(event,'bank2')" ondragover="allowDrop(event)">
              <div class="dnd-chip chip-a" draggable="true" ondragstart="dragStart(event)" id="chip-kw2">keyword (clas)</div>
              <div class="dnd-chip chip-b" draggable="true" ondragstart="dragStart(event)" id="chip-num2">number</div>
              <div class="dnd-chip chip-c" draggable="true" ondragstart="dragStart(event)" id="chip-cmp2">compare</div>
            </div>
            <div class="dnd-zones">
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#9F1239;"><code>&lt;=</code></div>
                <div class="dnd-zone" id="z2a" ondrop="dropChip(event,'z2a')" ondragover="allowDrop(event)" data-answer="chip-cmp2"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#92400E;"><code>class</code></div>
                <div class="dnd-zone" id="z2b" ondrop="dropChip(event,'z2b')" ondragover="allowDrop(event)" data-answer="chip-kw2"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#065F46;"><code>3.14e-2</code></div>
                <div class="dnd-zone" id="z2c" ondrop="dropChip(event,'z2c')" ondragover="allowDrop(event)" data-answer="chip-num2"></div>
              </div>
            </div>
            <button class="dnd-check" onclick="checkDnD('dnd2',['z2a','z2b','z2c'])">✓ Check</button>
            <button class="dnd-reset-btn" onclick="resetDnD('dnd2','bank2',['z2a','z2b','z2c'])">↺ Reset</button>
            <div class="dnd-feedback" id="fb-dnd2"></div>
          </div>
        </div>

        <!-- DnD Q3 — Ignored or not -->
        <div class="dnd-quiz" id="dnd3">
          <div class="dnd-header" style="background:linear-gradient(135deg,#F59E0B,#FBBF24);">
            <div class="dnd-q-num" style="background:rgba(255,255,255,.2);color:white;">3</div>
            <div class="dnd-q-text" style="color:white;">Sort these Decaf tokens by whether the parser sees them</div>
          </div>
          <div class="dnd-body">
            <p class="dnd-prompt">Drag each token to the correct zone — does the parser receive it, or is it discarded?</p>
            <div class="dnd-bank" id="bank3" ondrop="dropChip(event,'bank3')" ondragover="allowDrop(event)">
              <div class="dnd-chip chip-a" draggable="true" ondragstart="dragStart(event)" id="chip-cm3">comment2</div>
              <div class="dnd-chip chip-b" draggable="true" ondragstart="dragStart(event)" id="chip-id3">identifier</div>
              <div class="dnd-chip chip-c" draggable="true" ondragstart="dragStart(event)" id="chip-sp3">space</div>
            </div>
            <div class="dnd-zones">
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#6366F1;">✅ Parser RECEIVES this token</div>
                <div class="dnd-zone" id="z3a" ondrop="dropChip(event,'z3a')" ondragover="allowDrop(event)" data-answer="chip-id3"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#64748B;">❌ Discarded — Ignored Token (comment)</div>
                <div class="dnd-zone" id="z3b" ondrop="dropChip(event,'z3b')" ondragover="allowDrop(event)" data-answer="chip-cm3"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#94A3B8;">❌ Discarded — Ignored Token (whitespace)</div>
                <div class="dnd-zone" id="z3c" ondrop="dropChip(event,'z3c')" ondragover="allowDrop(event)" data-answer="chip-sp3"></div>
              </div>
            </div>
            <button class="dnd-check" onclick="checkDnD('dnd3',['z3a','z3b','z3c'])">✓ Check</button>
            <button class="dnd-reset-btn" onclick="resetDnD('dnd3','bank3',['z3a','z3b','z3c'])">↺ Reset</button>
            <div class="dnd-feedback" id="fb-dnd3"></div>
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Multiple Choice Quiz                          -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="quiz">
        <h2><span class="sec-icon">🧩</span> Check Your Knowledge</h2>
        <p>Test what you've learned about Section 2.5.</p>

        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span>
            <h4>Question 1</h4>
          </div>
          <div class="quiz-body">
            <p class="quiz-q">Why is the Decaf keyword <code>class</code> named <code>clas</code> (without the second 's') in the grammar file?</p>
            <div class="quiz-options" id="q1-opts">
              <div class="quiz-opt" onclick="answer(this,'q1',false)">
                <div class="opt-circle">A</div> To save memory by using a shorter name
              </div>
              <div class="quiz-opt" onclick="answer(this,'q1',true)">
                <div class="opt-circle">B</div> Because SableCC will not permit <code>class</code> as a token name — it conflicts with Java's reserved word <code>class</code>
              </div>
              <div class="quiz-opt" onclick="answer(this,'q1',false)">
                <div class="opt-circle">C</div> Because Decaf's <code>class</code> keyword has different semantics from Java's
              </div>
              <div class="quiz-opt" onclick="answer(this,'q1',false)">
                <div class="opt-circle">D</div> It is an error in the textbook — <code>class</code> can be used
              </div>
            </div>
            <div class="quiz-feedback" id="q1-fb"></div>
          </div>
        </div>

        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span>
            <h4>Question 2</h4>
          </div>
          <div class="quiz-body">
            <p class="quiz-q">The Decaf helper <code>non_star_slash</code> matches any character except <code>*</code> and <code>/</code>. Where is it used, and why is it needed?</p>
            <div class="quiz-options" id="q2-opts">
              <div class="quiz-opt" onclick="answer(this,'q2',false)">
                <div class="opt-circle">A</div> In the <code>div</code> token to avoid matching multi-character sequences
              </div>
              <div class="quiz-opt" onclick="answer(this,'q2',true)">
                <div class="opt-circle">B</div> Inside the <code>comment2</code> token definition — to correctly handle the body of <code>/* ... */</code> comments without accidentally matching <code>*/</code> too early
              </div>
              <div class="quiz-opt" onclick="answer(this,'q2',false)">
                <div class="opt-circle">C</div> In the <code>identifier</code> token to exclude operators from identifier names
              </div>
              <div class="quiz-opt" onclick="answer(this,'q2',false)">
                <div class="opt-circle">D</div> In the <code>number</code> token to prevent division operators inside numeric constants
              </div>
            </div>
            <div class="quiz-feedback" id="q2-fb"></div>
          </div>
        </div>

        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span>
            <h4>Question 3</h4>
          </div>
          <div class="quiz-body">
            <p class="quiz-q">Which token does the input <code>while123</code> produce in Decaf, and why?</p>
            <div class="quiz-options" id="q3-opts">
              <div class="quiz-opt" onclick="answer(this,'q3',false)">
                <div class="opt-circle">A</div> The keyword <code>while</code> followed by the number <code>123</code> — the scanner splits at the boundary
              </div>
              <div class="quiz-opt" onclick="answer(this,'q3',true)">
                <div class="opt-circle">B</div> An <code>identifier</code> — <code>while123</code> (8 chars) is a longer match than the keyword <code>while</code> (5 chars), so the longest-match rule gives identifier
              </div>
              <div class="quiz-opt" onclick="answer(this,'q3',false)">
                <div class="opt-circle">C</div> A <code>misc</code> token — it is not a valid keyword or identifier
              </div>
              <div class="quiz-opt" onclick="answer(this,'q3',false)">
                <div class="opt-circle">D</div> A <code>while</code> keyword — keywords always take priority regardless of length
              </div>
            </div>
            <div class="quiz-feedback" id="q3-fb"></div>
          </div>
        </div>

        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span>
            <h4>Question 4</h4>
          </div>
          <div class="quiz-body">
            <p class="quiz-q">The Decaf <code>number</code> token is defined as <code>(digits '.'? digits? | '.' digits) exp?</code>. Which of these inputs does it accept?</p>
            <div class="quiz-options" id="q4-opts">
              <div class="quiz-opt" onclick="answer(this,'q4',false)">
                <div class="opt-circle">A</div> Only integers like <code>42</code>
              </div>
              <div class="quiz-opt" onclick="answer(this,'q4',false)">
                <div class="opt-circle">B</div> Only decimal numbers like <code>3.14</code>
              </div>
              <div class="quiz-opt" onclick="answer(this,'q4',true)">
                <div class="opt-circle">C</div> Integers, decimals, numbers starting with a dot (<code>.5</code>), and scientific notation (<code>1.5e10</code>)
              </div>
              <div class="quiz-opt" onclick="answer(this,'q4',false)">
                <div class="opt-circle">D</div> Only numbers with both a decimal point and an exponent
              </div>
            </div>
            <div class="quiz-feedback" id="q4-fb"></div>
          </div>
        </div>

        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span>
            <h4>Question 5</h4>
          </div>
          <div class="quiz-body">
            <p class="quiz-q">What is the purpose of the <code>misc</code> token in Decaf?</p>
            <div class="quiz-options" id="q5-opts">
              <div class="quiz-opt" onclick="answer(this,'q5',false)">
                <div class="opt-circle">A</div> It matches all miscellaneous keywords not listed in the keyword section
              </div>
              <div class="quiz-opt" onclick="answer(this,'q5',false)">
                <div class="opt-circle">B</div> It is ignored like whitespace and comments
              </div>
              <div class="quiz-opt" onclick="answer(this,'q5',true)">
                <div class="opt-circle">C</div> It is a catch-all that matches any single character not matched by any other token — it will most likely cause the parser to report a syntax error
              </div>
              <div class="quiz-opt" onclick="answer(this,'q5',false)">
                <div class="opt-circle">D</div> It matches multi-character operators that are not listed individually
              </div>
            </div>
            <div class="quiz-feedback" id="q5-fb"></div>
          </div>
        </div>

      </section>

      <div class="chapter-nav">
        <a href="ch2_s4.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Previous section</span>2.4 Lexical Analysis with SableCC</div>
        </a>
        <a href="ch3_s0.php" class="nav-btn next">
          <div style="text-align:right"><span class="btn-sub">Next chapter</span>Chapter 3: Syntax Analysis</div>
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

    // ════════════════════════════════════════════════════════
    // DRAG AND DROP ENGINE
    // ════════════════════════════════════════════════════════
    let draggingId = null;

    function dragStart(e) {
      draggingId = e.target.id;
      e.target.classList.add('dragging');
      e.dataTransfer.effectAllowed = 'move';
      e.dataTransfer.setData('text/plain', e.target.id);
    }

    function allowDrop(e) {
      e.preventDefault();
      e.currentTarget.classList.add('drag-over');
    }
    document.addEventListener('dragleave', e => {
      if (e.target.classList) e.target.classList.remove('drag-over');
    });
    document.addEventListener('dragend', () => {
      document.querySelectorAll('.dnd-chip').forEach(c => c.classList.remove('dragging'));
      document.querySelectorAll('.dnd-zone,.dnd-bank').forEach(z => z.classList.remove('drag-over'));
    });

    function dropChip(e, targetId) {
      e.preventDefault();
      e.currentTarget.classList.remove('drag-over');
      const chipId = e.dataTransfer.getData('text/plain');
      const chip = document.getElementById(chipId);
      const target = document.getElementById(targetId);
      if (!chip || !target) return;
      const isZone = target.classList.contains('dnd-zone');
      if (isZone && target.children.length > 0) {
        const existing = target.firstElementChild;
        const bank = findBank(targetId);
        if (bank) {
          document.getElementById(bank).appendChild(existing);
          existing.onclick = null;
        }
      }
      target.appendChild(chip);
      chip.classList.remove('dragging');
      if (isZone) {
        chip.onclick = () => {
          const bank = findBank(targetId);
          if (bank) {
            document.getElementById(bank).appendChild(chip);
            chip.onclick = null;
          }
        };
      } else {
        chip.onclick = null;
      }
    }

    function findBank(zoneId) {
      const m = zoneId.match(/z(\d)/);
      return m ? 'bank' + m[1] : null;
    }

    function checkDnD(quizId, zoneIds) {
      let correct = 0;
      const total = zoneIds.length;
      zoneIds.forEach(zid => {
        const zone = document.getElementById(zid);
        const expected = zone.dataset.answer;
        const chip = zone.firstElementChild;
        zone.classList.remove('correct-zone', 'wrong-zone');
        if (chip && chip.id === expected) {
          zone.classList.add('correct-zone');
          correct++;
        } else {
          zone.classList.add('wrong-zone');
        }
      });
      const fb = document.getElementById('fb-' + quizId);
      fb.classList.remove('show', 'ok', 'bad', 'partial');
      if (correct === total) {
        fb.className = 'dnd-feedback show ok';
        fb.innerHTML = '✅ <strong>Perfect!</strong> All ' + total + ' matched correctly.';
      } else if (correct > 0) {
        fb.className = 'dnd-feedback show partial';
        fb.innerHTML = '⚠️ <strong>' + correct + ' of ' + total + '</strong> correct. Green = right, red = wrong.';
      } else {
        fb.className = 'dnd-feedback show bad';
        fb.innerHTML = '❌ None correct yet. Review the section and try again.';
      }
    }

    function resetDnD(quizId, bankId, zoneIds) {
      const bank = document.getElementById(bankId);
      zoneIds.forEach(zid => {
        const zone = document.getElementById(zid);
        zone.classList.remove('correct-zone', 'wrong-zone');
        while (zone.firstElementChild) {
          const c = zone.firstElementChild;
          c.onclick = null;
          bank.appendChild(c);
        }
      });
      const fb = document.getElementById('fb-' + quizId);
      fb.className = 'dnd-feedback';
      fb.innerHTML = '';
    }

    // ── Quiz ─────────────────────────────────────────────────
    const answered = {};

    function answer(el, qid, correct) {
      if (answered[qid]) return;
      answered[qid] = true;
      const opts = document.querySelectorAll(`#${qid}-opts .quiz-opt`);
      opts.forEach(o => o.classList.add('answered'));
      el.classList.add(correct ? 'correct' : 'wrong');
      el.querySelector('.opt-circle').textContent = correct ? '✓' : '✗';
      if (!correct) {
        opts.forEach(o => {
          if (o.getAttribute('onclick')?.includes('true')) {
            o.classList.add('correct');
            o.querySelector('.opt-circle').textContent = '✓';
          }
        });
      }
      const fb = document.getElementById(`${qid}-fb`);
      fb.classList.add('show', correct ? 'ok' : 'bad');
      fb.innerHTML = correct ? '✅ <strong>Correct!</strong> Well done.' : '❌ <strong>Not quite.</strong> The correct answer is highlighted above.';
    }
  </script>

</body>

</html>