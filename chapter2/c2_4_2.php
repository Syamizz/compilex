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
$page = 17;
$nextPage = "c2_5_0.php";

if (isset($_GET['complete']) && $_GET['complete'] == '17') {
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
        <li><a href="#running" class="toc-link">2.4.2 Running SableCC</a></li>
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
      <!-- 2.4.2 Running SableCC                         -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="running">
        <h2><span class="sec-icon">▶️</span> 2.4.2 Running SableCC — Three Steps</h2>

        <div style="display:flex;flex-direction:column;gap:12px;margin:20px 0;">
          <div style="background:var(--card);border:1.5px solid rgba(99,102,241,.15);border-radius:var(--radius);padding:16px 20px;box-shadow:var(--shadow);">
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--purple);margin-bottom:8px;">STEP 1 — Generate Java class definitions</div>
            <div class="code-wrap" style="margin:0;">
              <pre style="padding:12px 16px;"><code>sablecc lexing.grammar</code></pre>
            </div>
            <div style="font-size:13px;color:var(--muted);margin-top:8px;">Produces a subdirectory (e.g. <code>lexing/</code>) containing all generated Java source files.</div>
          </div>
          <div style="background:var(--card);border:1.5px solid rgba(99,102,241,.15);border-radius:var(--radius);padding:16px 20px;box-shadow:var(--shadow);">
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--purple);margin-bottom:8px;">STEP 2 — Compile the generated Java classes</div>
            <div class="code-wrap" style="margin:0;">
              <pre style="padding:12px 16px;"><code>javac lexing/*.java</code></pre>
            </div>
            <div style="font-size:13px;color:var(--muted);margin-top:8px;">Copy your <code>Lexing.java</code> driver into the <code>lexing/</code> subdirectory first, then compile from the parent directory.</div>
          </div>
          <div style="background:var(--card);border:1.5px solid rgba(16,185,129,.15);border-radius:var(--radius);padding:16px 20px;box-shadow:var(--shadow);">
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--green);margin-bottom:8px;">STEP 3 — Execute the scanner</div>
            <div class="code-wrap" style="margin:0;">
              <pre style="padding:12px 16px;"><code>java lexing.Lexing</code></pre>
            </div>
            <div style="font-size:13px;color:var(--muted);margin-top:8px;">Reads from standard input (keyboard). Use <code>Ctrl+D</code> (Unix) or <code>Ctrl+Z</code> (Windows) to signal end-of-file.</div>
          </div>
        </div>

        <h3>Sample Session Output</h3>
        <div class="code-wrap">
          <div class="code-header">
            <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">Terminal — java lexing.Lexing</span>
          </div>
          <pre><code><span class="cm">Input:</span>   sum = sum + salary ;

<span class="cm">Output:</span>
Identifier: sum
Unknown    =
Identifier: sum
Arith Op:  +
Identifier: salary
Unknown    ;</code></pre>
        </div>

        <div class="note-box tip">
          <span class="box-icon">💡</span>
          <div>
            <strong>Token class naming:</strong> Each token name in the grammar is prefixed with
            <code>T</code> in the generated Java code — so <code>number</code> becomes
            <code>TNumber</code>, <code>ident</code> becomes <code>TIdent</code>, etc. A special
            token <code>EOF</code> signals the end of the input file.
          </div>
        </div>

        <div class="code-wrap">
          <div class="code-header">
            <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">Java — Lexing.java driver (key part)</span><button class="copy-btn" onclick="copyBlock('lexing-java',this)">Copy</button>
          </div>
          <pre><code id="lexing-java"><span class="kw">while</span> (!(token <span class="kw">instanceof</span> <span class="typ">EOF</span>)) {
  token = lexer.next();
  <span class="kw">if</span>      (token <span class="kw">instanceof</span> <span class="typ">TNumber</span>)  System.out.print(<span class="str">"Number:      "</span>);
  <span class="kw">else if</span> (token <span class="kw">instanceof</span> <span class="typ">TIdent</span>)   System.out.print(<span class="str">"Identifier:  "</span>);
  <span class="kw">else if</span> (token <span class="kw">instanceof</span> <span class="typ">TArithOp</span>) System.out.print(<span class="str">"Arith Op:    "</span>);
  <span class="kw">else if</span> (token <span class="kw">instanceof</span> <span class="typ">TRelOp</span>)   System.out.print(<span class="str">"Relational:  "</span>);
  <span class="kw">else if</span> (token <span class="kw">instanceof</span> <span class="typ">TParen</span>)   System.out.print(<span class="str">"Parentheses: "</span>);
  <span class="kw">else if</span> (token <span class="kw">instanceof</span> <span class="typ">TBlank</span>)   ;  <span class="cm">// Ignore white space</span>
  <span class="kw">else if</span> (token <span class="kw">instanceof</span> <span class="typ">TUnknown</span>) System.out.print(<span class="str">"Unknown:     "</span>);
  <span class="kw">if</span> (!(token <span class="kw">instanceof</span> <span class="typ">TBlank</span>))
    System.out.println(token); <span class="cm">// print token text</span>
}</code></pre>
        </div>
      </section>




      <div class="chapter-nav">
        <a href="c2_4_1.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Previous page</span>2.4.1 Grammar File Structure</div>
        </a>
        <a href="?complete=17" class="nav-btn next">
          <div style="text-align:right"><span class="btn-sub">Next page</span>2.5 Case Study: Lexical Analysis for Decaf</div>
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