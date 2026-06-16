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
$page = 16;
$nextPage = "c2_4_2.php";

if (isset($_GET['complete']) && $_GET['complete'] == '16') {
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
  <title>Chapter 2.4 – CompileX</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../css/c2/c2_4.css">
</head>

<body>

  <?php include 'navbar_c2.php'; ?>

  <div class="note-container">

    <nav class="toc">
      <div class="toc-title">📖 Contents</div>
      <ul>
        <li><a href="#input-file" class="toc-link">2.4.1 Grammar File Structure</a></li>
        <li><a href="#tokens" class="toc-link sub">↳ 2.4.1.1 Token Declarations</a></li>
        <li><a href="#helpers" class="toc-link sub">↳ 2.4.1.2 Helper Declarations</a></li>
        <li><a href="#states" class="toc-link sub">↳ 2.4.1.3 States &amp; Context</a></li>
        <li><a href="#sp-a" class="toc-link sub">↳ Sample Problem (a)</a></li>
        <li><a href="#sp-b" class="toc-link sub">↳ Sample Problem (b)</a></li>
        <li><a href="#example" class="toc-link">2.4.1.4 Full Example</a></li>
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
        <div class="chapter-tag">📘 Chapter 2 · Section 2.4</div>
        <h1>Lexical Analysis with SableCC</h1>
        <div class="metadata">
          <span>⏱ 20 min read</span>
          <span>🎯 Intermediate</span>
          <span>🛠️ Compiler Tools</span>
        </div>
      </header>






      <!-- ══════════════════════════════════════════════ -->
      <!-- 2.4.1 Grammar File Structure                  -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="input-file">
        <h2><span class="sec-icon">📄</span> 2.4.1 SableCC Grammar File Structure</h2>
        <p>
          The input to SableCC is a text file with the suffix <code>.grammar</code>. It has
          <strong>six sections</strong>. For lexical analysis (this chapter) we use only the first four.
          All names (Helpers, States, Tokens) must use <strong>lowercase letters and underscores only</strong>.
          Single-line (<code>//</code>) and multi-line (<code>/* … */</code>) comments are allowed anywhere.
        </p>

        <div class="grammar-sections">
          <div class="gs-row gs-active">
            <div class="gs-num">1</div>
            <div class="gs-body">
              <div class="gs-name">Package declaration</div>
              <div class="gs-desc">Declares the Java package name for all generated classes.</div>
            </div>
          </div>
          <div class="gs-row gs-active">
            <div class="gs-num">2</div>
            <div class="gs-body">
              <div class="gs-name">Helpers</div>
              <div class="gs-desc">Reusable macros (semantic substitutions) used within Token definitions.</div>
            </div>
          </div>
          <div class="gs-row gs-active">
            <div class="gs-num">3</div>
            <div class="gs-body">
              <div class="gs-name">States</div>
              <div class="gs-desc">Named scanner states for handling context-sensitive tokens (e.g. comments, strings).</div>
            </div>
          </div>
          <div class="gs-row gs-active">
            <div class="gs-num">4</div>
            <div class="gs-body">
              <div class="gs-name">Tokens</div>
              <div class="gs-desc">The token definitions using regular expressions. Core of the grammar file.</div>
            </div>
          </div>
          <div class="gs-row gs-dim">
            <div class="gs-num" style="background:#F1F5F9;color:#94A3B8;">5</div>
            <div class="gs-body">
              <div class="gs-name" style="color:var(--muted);">Ignored tokens</div>
              <div class="gs-desc">Tokens to discard (e.g. whitespace). Covered later.</div>
            </div>
          </div>
          <div class="gs-row gs-dim">
            <div class="gs-num" style="background:#F1F5F9;color:#94A3B8;">6</div>
            <div class="gs-body">
              <div class="gs-name" style="color:var(--muted);">Productions</div>
              <div class="gs-desc">Grammar rules for syntax analysis. Covered in Chapter 3.</div>
            </div>
          </div>
        </div>

        <div class="code-wrap">
          <div class="code-header">
            <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
            <span class="code-lang">language.grammar — skeleton</span>
          </div>
          <pre><code><span class="kw">Package</span> package-name ;

<span class="kw">Helpers</span>
  <span class="cm">[ Helper declarations go here ]</span>

<span class="kw">States</span>
  <span class="cm">[ State declarations go here ]</span>

<span class="kw">Tokens</span>
  <span class="cm">[ Token declarations go here ]</span></code></pre>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 2.4.1.1 Token Declarations                    -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="tokens">
        <h2><span class="sec-icon">🏷️</span> 2.4.1.1 Token Declarations</h2>
        <p>
          Every lexical token must be declared with a name and defined using a <strong>token definition</strong>.
          The format is:
        </p>
        <div style="background:var(--code-bg);border-radius:var(--radius);padding:14px 22px;margin:14px 0;font-family:'JetBrains Mono',monospace;font-size:14px;color:#CDD6F4;">
          <span style="color:#A5B4FC;">token-name</span> = <span style="color:#A6E3A1;">token-definition</span> ;
        </div>

        <h3>Character and Set Notation</h3>
        <div class="op-ref">
          <div class="op-ref-row op-ref-header">
            <div>Form</div>
            <div>Description</div>
            <div>Example</div>
          </div>
          <div class="op-ref-row">
            <div class="op-ref or-op">'w'</div>
            <div class="op-ref or-desc">A single character in single quotes</div>
            <div class="op-ref or-ex">'(' &nbsp; '9' &nbsp; '$'</div>
          </div>
          <div class="op-ref-row">
            <div class="op-ref or-op">13</div>
            <div class="op-ref or-desc">Decimal or hex ASCII/Unicode code for a character</div>
            <div class="op-ref or-ex">13 = '\n' newline</div>
          </div>
          <div class="op-ref-row">
            <div class="op-ref or-op">['a'..'z']</div>
            <div class="op-ref or-desc">Range of characters — all chars from first to last</div>
            <div class="op-ref or-ex">['0'..'9'] all digits</div>
          </div>
          <div class="op-ref-row">
            <div class="op-ref or-op">[s1 + s2]</div>
            <div class="op-ref or-desc">Union of two sets — all chars in either set</div>
            <div class="op-ref or-ex">[['a'..'z']+['A'..'Z']]</div>
          </div>
          <div class="op-ref-row">
            <div class="op-ref or-op">[s1 - s2]</div>
            <div class="op-ref or-desc">Difference — chars in s1 that are NOT in s2</div>
            <div class="op-ref or-ex">[[0..127] - ['\t'+'\n']]</div>
          </div>
          <div class="op-ref-row">
            <div class="op-ref or-op">'while'</div>
            <div class="op-ref or-desc">A string of characters in single quotes</div>
            <div class="op-ref or-ex">'while' 'for'</div>
          </div>
        </div>

        <h3>Regular Expression Operators in Token Definitions</h3>
        <div class="op-ref">
          <div class="op-ref-row op-ref-header">
            <div>Operator</div>
            <div>Meaning</div>
            <div>Example</div>
          </div>
          <div class="op-ref-row">
            <div class="op-ref or-op">(p)</div>
            <div class="op-ref or-desc">Parentheses for grouping / controlling precedence</div>
            <div class="op-ref or-ex">(a|b)*</div>
          </div>
          <div class="op-ref-row">
            <div class="op-ref or-op">pq</div>
            <div class="op-ref or-desc">Concatenation of two definitions</div>
            <div class="op-ref or-ex">letter digit</div>
          </div>
          <div class="op-ref-row">
            <div class="op-ref or-op">p | q</div>
            <div class="op-ref or-desc">Union (alternation) of two definitions</div>
            <div class="op-ref or-ex">'a' | 'b'</div>
          </div>
          <div class="op-ref-row">
            <div class="op-ref or-op">p*</div>
            <div class="op-ref or-desc">Kleene star — 0 or more repetitions of p</div>
            <div class="op-ref or-ex">digit*</div>
          </div>
          <div class="op-ref-row">
            <div class="op-ref or-op">p+</div>
            <div class="op-ref or-desc">One or more repetitions of p</div>
            <div class="op-ref or-ex">digit+</div>
          </div>
          <div class="op-ref-row">
            <div class="op-ref or-op">p?</div>
            <div class="op-ref or-desc">Optional p — 0 or 1 occurrences</div>
            <div class="op-ref or-ex">sign?</div>
          </div>
        </div>

        <div class="note-box warn">
          <span class="box-icon">⚠️</span>
          <div>
            <strong>Two uses of '+' !</strong> If <code>s1</code> and <code>s2</code> are <em>sets</em>,
            <code>[s1 + s2]</code> is their <em>union</em>. If <code>p</code> is a
            <em>regular expression</em>, <code>p+</code> means <em>one or more</em>. To combine regular
            expressions, use <code>|</code> (not <code>+</code>).
          </div>
        </div>

        <h3>⚠️ Token Matching Priority</h3>
        <p>When two definitions match the same input, SableCC uses these rules:</p>
        <ol>
          <li><strong>Longer match wins</strong> — the definition matching more characters is selected</li>
          <li><strong>First listed wins</strong> — if two definitions match the same length, the one listed earlier in the file is chosen</li>
        </ol>

        <div class="note-box warn">
          <span class="box-icon">⚠️</span>
          <div>
            <strong>Common mistake — keywords before identifiers:</strong> If <code>identifier</code> is
            listed before <code>keyword</code>, then <code>while</code> matches <code>identifier</code>
            first (same length, listed first). Fix: list <code>keyword</code> <em>before</em>
            <code>identifier</code>. Then <code>while</code> = keyword (tie → first wins);
            <code>whilex</code> = identifier (6 chars &gt; 5 chars → longer wins).
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 2.4.1.2 Helper Declarations                   -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="helpers">
        <h2><span class="sec-icon">🔧</span> 2.4.1.2 Helper Declarations</h2>
        <p>
          Helpers are <strong>named sub-definitions</strong> that can be reused within Token definitions.
          They act like macros — any helper defined in the <code>Helpers</code> section can be referenced
          by name inside the <code>Tokens</code> section.
        </p>

        <div class="code-wrap">
          <div class="code-header">
            <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
            <span class="code-lang">SableCC — Helpers example</span>
            <button class="copy-btn" onclick="copyBlock('helpers-code',this)">Copy</button>
          </div>
          <pre><code id="helpers-code"><span class="kw">Helpers</span>
  digit   = [<span class="str">'0'</span>..<span class="str">'9'</span>] ;
  letter  = [[<span class="str">'a'</span>..<span class="str">'z'</span>] + [<span class="str">'A'</span>..<span class="str">'Z'</span>]] ;
  sign    = <span class="str">'+'</span> | <span class="str">'-'</span> ;
  newline = <span class="num">10</span> | <span class="num">13</span> ;     <span class="cm">// ascii codes for LF and CR</span>
  tab     = <span class="num">9</span> ;           <span class="cm">// ascii code for tab</span>

<span class="kw">Tokens</span>
  number     = sign? digit+ ;
  <span class="cm">// optional sign, 1 or more digits</span>

  identifier = letter (letter | digit | <span class="str">'_'</span>)* ;
  <span class="cm">// letter followed by 0 or more letters, digits, or _</span>

  space      = <span class="str">' '</span> | newline | tab ;</code></pre>
        </div>

        <div class="note-box warn">
          <span class="box-icon">⚠️</span>
          <div>
            <strong>SableCC Helpers ≠ lex macros.</strong> In <code>lex</code>, macros are
            <em>textual</em> substitutions — substituting <code>sign</code> into <code>number</code>
            gives <code>number = '+' | '-'? digit+</code>, which is parsed as
            <em>"either a plus, or an optional minus followed by digits"</em> — not what the user
            intended. <strong>SableCC uses semantic substitution</strong>, which correctly treats
            <code>sign?</code> as "optional (+ or -)". This is a common stumbling block with lex.
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 2.4.1.3 States, Left Context, Right Context   -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="states">
        <h2><span class="sec-icon">🔀</span> 2.4.1.3 State Declarations, Left &amp; Right Context</h2>
        <p>
          Sometimes the scanner needs different behaviour depending on <em>where</em> it is in the input —
          for example, ignoring tokens inside a comment. <strong>States</strong> allow the scanner to
          switch modes.
        </p>

        <h3>Declaring States</h3>
        <div style="background:var(--code-bg);border-radius:var(--radius);padding:14px 22px;margin:14px 0;font-family:'JetBrains Mono',monospace;font-size:14px;color:#CDD6F4;">
          <span class="kw" style="color:#CBA6F7;">States</span> &nbsp; statename1, statename2, statename3 ;
          <div style="font-size:12px;color:#6C7086;margin-top:6px;">// The first state listed is the start state</div>
        </div>

        <h3>State-Associated Token Definitions</h3>
        <table class="compare-table">
          <thead>
            <tr>
              <th>Syntax</th>
              <th>Meaning</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><code>{statename} token = def;</code></td>
              <td>Apply this definition only when in <code>statename</code>; remain in <code>statename</code></td>
            </tr>
            <tr>
              <td><code>{statename->newstate} token = def;</code></td>
              <td>Apply when in <code>statename</code>; transition to <code>newstate</code></td>
            </tr>
            <tr>
              <td><code>{s1->s2, s3->s4, s5} token = def;</code></td>
              <td>Multiple state associations in one definition</td>
            </tr>
            <tr>
              <td><code>token = def;</code></td>
              <td>No state specified — applies in <strong>any</strong> state</td>
            </tr>
          </tbody>
        </table>

        <h3>Right Context with <code>/</code></h3>
        <p>
          To recognise a token <em>only when followed by a certain pattern</em>, use the forward slash:
        </p>
        <div class="code-wrap">
          <div class="code-header">
            <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">Right context example</span>
          </div>
          <pre><code>currency = number / space* <span class="str">'DB'</span> | number / space* <span class="str">'CR'</span> ;
<span class="cm">// The token matched is just "number" — the right context</span>
<span class="cm">// " DB" or " CR" is NOT included in the token value</span></code></pre>
        </div>
        <p>
          In the text <code>Your bill is 14.50 CR</code>, SableCC matches <code>14.50</code> as a
          currency token (the <code> CR</code> is the right context and is excluded). The <code>12</code>
          in <code>12 days</code> would not match because the right context is absent.
        </p>

        <h3>Example — Beginning-of-Line State</h3>
        <div class="code-wrap">
          <div class="code-header">
            <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">SableCC — BOL/inline state example</span>
          </div>
          <pre><code><span class="kw">States</span>
  bol, inline ; <span class="cm">// bol is the start state</span>

<span class="kw">Tokens</span>
  {bol->inline, inline} char = [[<span class="num">0</span>..<span class="num">0xfff</span>] - [<span class="num">10</span> + <span class="num">13</span>]] ;
  <span class="cm">// Non-newline char: apply in either state, transition to inline</span>

  {bol, inline->bol} eol = <span class="num">10</span> | <span class="num">13</span> | <span class="num">10</span> <span class="num">13</span> ;
  <span class="cm">// Newline char: apply in either state, transition to bol</span></code></pre>
        </div>
      </section>

      <!-- Sample Problem 2.4(a) -->
      <section id="sp-a">
        <h2><span class="sec-icon">🧪</span> Sample Problem 2.4(a)</h2>
        <div class="sample-problem">
          <div class="sp-header"><span style="font-size:18px">📋</span>
            <h4>Sample Problem 2.4 (a)</h4>
          </div>
          <div class="sp-body">
            <p class="sp-question">Show the sequence of tokens recognised by the definitions of <code>number</code>, <code>identifier</code>, and <code>space</code> for the following input:</p>
            <div class="code-wrap" style="margin-bottom:16px;">
              <div class="code-header">
                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">Input</span>
              </div>
              <pre><code>334 abc abc334</code></pre>
            </div>
            <div class="sp-solution-label">✅ Solution</div>
            <div class="tok-stream">
              <div class="tok-chip tc-num"><span class="tc-type">number</span><span class="tc-val">334</span></div>
              <div class="tok-chip tc-sp"><span class="tc-type">space</span><span class="tc-val">⎵</span></div>
              <div class="tok-chip tc-id"><span class="tc-type">identifier</span><span class="tc-val">abc</span></div>
              <div class="tok-chip tc-sp"><span class="tc-type">space</span><span class="tc-val">⎵</span></div>
              <div class="tok-chip tc-id"><span class="tc-type">identifier</span><span class="tc-val">abc334</span></div>
            </div>
            <div class="note-box tip" style="margin-top:12px;">
              <span class="box-icon">💡</span>
              <div><code>abc334</code> matches <code>identifier</code> because it starts with a letter.
                It does <em>not</em> split into <code>abc</code>+<code>334</code> — the scanner reads as
                many characters as possible (longest match).</div>
            </div>
          </div>
        </div>
      </section>

      <!-- Sample Problem 2.4(b) -->
      <section id="sp-b">
        <h2><span class="sec-icon">🧪</span> Sample Problem 2.4(b)</h2>
        <div class="sample-problem">
          <div class="sp-header"><span style="font-size:18px">📋</span>
            <h4>Sample Problem 2.4 (b)</h4>
          </div>
          <div class="sp-body">
            <p class="sp-question">Show the token and state definitions needed to process a text file containing numbers, currency values (beginning with <code>$</code>, e.g. <code>$3045</code>), and spaces. Distinguish <strong>money</strong> from ordinary <strong>number</strong>. Assume whole numbers only.</p>
            <div class="sp-solution-label">✅ Solution</div>
            <div class="code-wrap">
              <div class="code-header">
                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">SableCC grammar — currency vs number</span><button class="copy-btn" onclick="copyBlock('sp-b-code',this)">Copy</button>
              </div>
              <pre><code id="sp-b-code"><span class="kw">Helpers</span>
  num = [<span class="str">'0'</span>..<span class="str">'9'</span>]+ ;  <span class="cm">// 1 or more digits</span>

<span class="kw">States</span>
  def, currency ;  <span class="cm">// def is the start state</span>

<span class="kw">Tokens</span>
  space                  = (<span class="str">' '</span> | <span class="num">10</span> | <span class="num">13</span> | <span class="str">'\t'</span>) ;

  {def -> currency} dollar = <span class="str">'$'</span> ;
  <span class="cm">// When in 'def', seeing '$' → switch to 'currency' state</span>

  {currency -> def}  money  = num ;
  <span class="cm">// When in 'currency', digits form a money token → back to 'def'</span>

  {def}              number = num ;
  <span class="cm">// When in 'def', digits are an ordinary number</span></code></pre>
            </div>
            <div class="note-box key" style="margin-top:14px;">
              <span class="box-icon">✅</span>
              <div>
                For input <code>$3045 99 $7</code>: the scanner starts in <code>def</code>.
                <code>$</code> → <code>dollar</code> token, transition to <code>currency</code>.
                <code>3045</code> → <code>money</code> token, transition back to <code>def</code>.
                <code>99</code> → <code>number</code> token (in <code>def</code>).
                <code>$7</code> → <code>dollar</code> then <code>money</code> again.
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 2.4.1.4 Full Example                          -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="example">
        <h2><span class="sec-icon">📋</span> 2.4.1.4 Complete Example — lexing.grammar</h2>
        <p>
          This complete grammar file produces a scanner that recognises numbers, identifiers,
          arithmetic operators, relational operators, and parentheses. It is placed in a file named
          <code>lexing.grammar</code>.
        </p>

        <div class="code-wrap">
          <div class="code-header">
            <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">lexing.grammar — complete example</span><button class="copy-btn" onclick="copyBlock('full-grammar',this)">Copy</button>
          </div>
          <pre><code id="full-grammar"><span class="kw">Package</span> lexing ;  <span class="cm">// Java package for generated scanner</span>

<span class="kw">Helpers</span>
  num    = [<span class="str">'0'</span>..<span class="str">'9'</span>]+ ;
  <span class="cm">// 1 or more decimal digits</span>

  letter = [<span class="str">'a'</span>..<span class="str">'z'</span>] | [<span class="str">'A'</span>..<span class="str">'Z'</span>] ;
  <span class="cm">// A single upper or lowercase character</span>

<span class="kw">Tokens</span>
  number   = num ;
  <span class="cm">// A number token is a whole number</span>

  ident    = letter (letter | num)* ;
  <span class="cm">// Letter followed by 0 or more letters/numbers</span>

  arith_op = [[<span class="str">'+'</span> + <span class="str">'-'</span>] + [<span class="str">'*'</span> + <span class="str">'/'</span>]] ;
  <span class="cm">// Arithmetic operators: + - * /</span>

  rel_op   = [<span class="str">'<'</span> + <span class="str">'>'</span>] | <span class="str">'=='</span> | <span class="str">'<='</span> | <span class="str">'>='</span> | <span class="str">'!='</span> ;
  <span class="cm">// Relational operators</span>

  paren    = [<span class="str">'('</span> + <span class="str">')'</span>] ;
  <span class="cm">// Parentheses</span>

  blank    = (<span class="str">' '</span> | <span class="str">'\t'</span> | <span class="num">10</span> | <span class="str">'\n'</span>)+ ;
  <span class="cm">// White space (ignored by the Lexing.java driver)</span>

  unknown  = [<span class="num">0</span>..<span class="num">0xffff</span>] ;
  <span class="cm">// Any single character not matched above</span></code></pre>
        </div>
      </section>





      <div class="chapter-nav">
        <a href="c2_4_0.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Previous page</span>2.4 Lexical Analysis with SableCC</div>
        </a>
        <a href="?complete=16" class="nav-btn next">
          <div style="text-align:right"><span class="btn-sub">Next page</span>2.4.2 Running SableCC</div>
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
  </script>

</body>

</html>