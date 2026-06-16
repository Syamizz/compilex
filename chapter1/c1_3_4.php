<?php
session_start();
include '../dbconn.php';
include '../save_progress.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$chapter = 1;
$page = 16;
$nextPage = "c1_4_0.php";

if (isset($_GET['complete']) && $_GET['complete'] == $page) {
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
  <title>Chapter 1.3 – CompileX</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">


  <link rel="stylesheet" href="../css/c1_3.css">
</head>

<body>

  <?php include 'navbar_c1.php'; ?>

  <div class="note-container">

    <!-- ── Sidebar TOC ─────────────────────────────────── -->
    <nav class="toc">
      <div class="toc-title">📖 Contents</div>
      <ul>
        <li><a href="#compilercomp" class="toc-link">1.3.4 Compiler-Compilers</a></li>
        <li><a href="#interactive" class="toc-link">Interactive Explorer</a></li>
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
        <div class="chapter-tag">📘 Chapter 1 · Section 1.3</div>
        <h1>Implementation Techniques</h1>
        <div class="metadata">
          <span>⏱ 16 min read</span>
          <span>🎯 Intermediate</span>
          <span>⚙️ Compiler Theory</span>
        </div>
      </header>




      - ══════════════════════════════════════════════ -->
      <!-- 1.3.4 Compiler-Compilers                      -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="compilercomp">
        <h2><span class="sec-icon">🤖</span> 1.3.4 Compiler-Compilers</h2>
        <p>
          Much of compiler design is now so well-understood that the process can be <strong>automated</strong>.
          A <strong>compiler-compiler</strong> (also called a <em>compiler generator</em>) takes formal
          specifications of:
        </p>
        <ul>
          <li>The <strong>source language</strong> (described by a grammar)</li>
          <li>The <strong>target machine</strong> (described by a machine specification)</li>
        </ul>
        <p>…and <strong>automatically generates a compiler</strong> from those specifications.</p>

        <table class="compare-table">
          <thead>
            <tr>
              <th>Tool</th>
              <th>Environment</th>
              <th>What it generates</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><code>lex</code></td>
              <td>Unix / C</td>
              <td>Lexical analyser (scanner)</td>
            </tr>
            <tr>
              <td><code>yacc</code></td>
              <td>Unix / C</td>
              <td>Parser (syntax analyser) — "Yet Another Compiler Compiler"</td>
            </tr>
            <tr>
              <td><code>JavaCC</code></td>
              <td>Java</td>
              <td>Scanner + parser from a single grammar file</td>
            </tr>
            <tr>
              <td><code>SableCC</code></td>
              <td>Java (public domain)</td>
              <td>Full compiler framework — scanner, parser, AST, tree walker</td>
            </tr>
          </tbody>
        </table>

        <div class="note-box tip">
          <span class="box-icon">💡</span>
          <div>
            <strong>SableCC</strong> is introduced in Chapters 2 and 5 of the textbook. It generates the lexical
            analysis and syntax analysis phases automatically from a grammar specification — you only write
            the semantic actions and code generation by hand.
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Interactive Explorer                          -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="interactive">
        <h2><span class="sec-icon">▶️</span> Interactive Technique Explorer</h2>
        <p>Select a technique below to review the key steps and Big-C diagrams side by side.</p>

        <div class="demo-wrap">
          <div class="demo-header">
            <h4>🖥️ Implementation Technique Review</h4>
          </div>
          <div class="demo-body">
            <div class="demo-controls">
              <button class="demo-btn active" onclick="showTech(this,'t1')">🥾 Bootstrapping</button>
              <button class="demo-btn" onclick="showTech(this,'t2')">🔀 Cross Compiling</button>
              <button class="demo-btn" onclick="showTech(this,'t3')">🔗 Intermediate Form</button>
              <button class="demo-btn" onclick="showTech(this,'t4')">🤖 Compiler-Compilers</button>
            </div>

            <!-- Bootstrapping -->
            <div class="demo-stage show" id="t1">
              <h3 style="margin-top:0;">🥾 Bootstrapping</h3>
              <p><strong>Goal:</strong> Build a compiler for a new language without writing everything in machine code.</p>
              <p><strong>Strategy:</strong> Write two simpler compilers — one for a subset of the language (in assembly), one for the full language (in the subset). Feed the second through the first.</p>
              <div style="background:var(--code-bg); border-radius:var(--radius); padding:20px; font-family:'JetBrains Mono',monospace; font-size:13px; color:#CDD6F4; line-height:2.2;">
                <span style="color:#6C7086;">// You write:</span><br>
                C<sup style="color:#A5B4FC;">Sub→Sun</sup><sub style="color:#6C7086;">Sun</sub> &nbsp; <span style="color:#6C7086;">// small compiler, in assembly — manageable</span><br>
                C<sup style="color:#A5B4FC;">Java→Sun</sup><sub style="color:#6C7086;">Sub</sub> &nbsp; <span style="color:#6C7086;">// full-language compiler, in Sub — easy</span><br>
                <br>
                <span style="color:#6C7086;">// You run (feed #2 through #1):</span><br>
                C<sup style="color:#A5B4FC;">Java→Sun</sup><sub style="color:#6C7086;">Sub</sub> &nbsp;→ <span style="color:#6C7086;">[Sun running</span> C<sup style="color:#A5B4FC;">Sub→Sun</sup><sub style="color:#6C7086;">Sun</sub><span style="color:#6C7086;">]</span> → C<sup style="color:#6EE7B7;">Java→Sun</sup><sub style="color:#6EE7B7;">Sun</sub> <span style="color:#6C7086;">// ← desired result!</span>
              </div>
            </div>

            <!-- Cross Compiling -->
            <div class="demo-stage" id="t2">
              <h3 style="margin-top:0;">🔀 Cross Compiling</h3>
              <p><strong>Goal:</strong> Produce a compiler for a brand-new machine (Mac) using an existing machine (Sun).</p>
              <p><strong>Strategy:</strong> Write the target compiler in a high-level language (Java). Cross-compile it twice on the existing machine.</p>
              <div style="background:var(--code-bg); border-radius:var(--radius); padding:20px; font-family:'JetBrains Mono',monospace; font-size:13px; color:#CDD6F4; line-height:2.2;">
                <span style="color:#6C7086;">// You already have:</span><br>
                C<sup style="color:#A5B4FC;">Java→Sun</sup><sub style="color:#6C7086;">Sun</sub><br>
                <br>
                <span style="color:#6C7086;">// You write (in Java):</span><br>
                C<sup style="color:#A5B4FC;">Java→Mac</sup><sub style="color:#6C7086;">Java</sub><br>
                <br>
                <span style="color:#6C7086;">// Step 1 — feed through Sun compiler:</span><br>
                → C<sup style="color:#FAB387;">Java→Mac</sup><sub style="color:#FAB387;">Sun</sub> &nbsp;<span style="color:#6C7086;">// runs on Sun, targets Mac</span><br>
                <br>
                <span style="color:#6C7086;">// Step 2 — feed again through Step 1 output:</span><br>
                → C<sup style="color:#6EE7B7;">Java→Mac</sup><sub style="color:#6EE7B7;">Mac</sub> &nbsp;<span style="color:#6C7086;">// ← runs natively on Mac!</span>
              </div>
            </div>

            <!-- Intermediate Form -->
            <div class="demo-stage" id="t3">
              <h3 style="margin-top:0;">🔗 Intermediate Form</h3>
              <p><strong>Goal:</strong> Support n languages on m machines with far fewer than n×m compilers.</p>
              <p><strong>Strategy:</strong> Each language gets one <em>front end</em> (→ Int). Each machine gets one <em>back end</em> (Int →). Total: n + m translators instead of n × m.</p>
              <div style="background:var(--code-bg); border-radius:var(--radius); padding:20px; font-family:'JetBrains Mono',monospace; font-size:13px; color:#CDD6F4; line-height:2;">
                <span style="color:#6C7086;">// Front ends (one per language):</span><br>
                C<sup style="color:#A5B4FC;">Java→Int</sup> &nbsp; C<sup style="color:#A5B4FC;">C++→Int</sup> &nbsp; C<sup style="color:#A5B4FC;">Ada→Int</sup><br>
                <br>
                <span style="color:#6C7086;">// Back ends (one per machine):</span><br>
                C<sup style="color:#6EE7B7;">Int→PC</sup> &nbsp; C<sup style="color:#6EE7B7;">Int→Mac</sup><br>
                <br>
                <span style="color:#6C7086;">// Total: 3 + 2 = 5 translators (vs 3×2 = 6 compilers)</span>
              </div>
            </div>

            <!-- Compiler-Compilers -->
            <div class="demo-stage" id="t4">
              <h3 style="margin-top:0;">🤖 Compiler-Compilers</h3>
              <p><strong>Goal:</strong> Automate compiler construction from formal specifications.</p>
              <p><strong>How it works:</strong> You provide a grammar for the source language and a machine description. The compiler-compiler generates the scanner and parser automatically.</p>
              <div style="background:var(--code-bg); border-radius:var(--radius); padding:20px; font-family:'JetBrains Mono',monospace; font-size:13px; color:#CDD6F4; line-height:2;">
                <span style="color:#6C7086;">// Input to a compiler-compiler:</span><br>
                grammar.scc &nbsp;&nbsp;<span style="color:#6C7086;">// SableCC grammar file</span><br>
                <br>
                <span style="color:#6C7086;">// Auto-generated output:</span><br>
                <span style="color:#A5B4FC;">Lexer.java</span> &nbsp;&nbsp;&nbsp;<span style="color:#6C7086;">// scanner (from token definitions)</span><br>
                <span style="color:#A5B4FC;">Parser.java</span> &nbsp;&nbsp;<span style="color:#6C7086;">// parser (from grammar rules)</span><br>
                <span style="color:#A5B4FC;">AST.java</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#6C7086;">// abstract syntax tree classes</span><br>
                <br>
                <span style="color:#6C7086;">// You still write manually:</span><br>
                <span style="color:#FAB387;">CodeGen.java</span> &nbsp;<span style="color:#6C7086;">// code generation phase</span>
              </div>
            </div>
          </div>
        </div>
      </section>


      <!-- Chapter nav -->
      <div class="chapter-nav">
        <a href="c1_3_2.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Previous page</span>1.3.2 Cross Compiling</div>
        </a>
        <a href="?complete=16" class="nav-btn next">
          <div style="text-align:right"><span class="btn-sub">Next page</span>1.3.4 Compiler-Compilers</div>
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

    // ── Interactive technique explorer ───────────────────────
    function showTech(btn, id) {
      document.querySelectorAll('.demo-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.demo-stage').forEach(s => s.classList.remove('show'));
      btn.classList.add('active');
      document.getElementById(id).classList.add('show');
    }
  </script>

</body>

</html>