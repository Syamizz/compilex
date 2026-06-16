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
$page = 12;
$nextPage = "c1_3_1.php";

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
        <li><a href="#overview" class="toc-link">1.3 Overview</a></li>
        <li><a href="#notation" class="toc-link">1.3.0 Computer Notation</a></li>
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

      <!-- ══════════════════════════════════════════════ -->
      <!-- 1.3 Overview                                   -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="overview">
        <h2><span class="sec-icon">🗺️</span> Overview</h2>
        <p>
          A compiler is not a trivial program. A new compiler, with all optimisations, could take
          <strong>over a person-year</strong> to implement. For this reason we are always looking for
          techniques and shortcuts that speed up the development process.
        </p>
        <p>This includes:</p>
        <ul>
          <li>Reusing compilers or <strong>portions of compilers</strong> that have been developed previously</li>
          <li>Using special <strong>compiler-generating tools</strong> such as <code>lex</code> and <code>yacc</code>
            (Unix environment), or newer tools like <code>JavaCC</code> or <code>SableCC</code></li>
        </ul>

        <table class="compare-table">
          <thead>
            <tr>
              <th>Technique</th>
              <th>Core Idea</th>
              <th>When to Use</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Bootstrapping</td>
              <td>Use a compiler as input to itself; build up from a small subset</td>
              <td>Building the first compiler for a new language on an existing machine</td>
            </tr>
            <tr>
              <td>Cross Compiling</td>
              <td>Compile for a new target machine using an existing compiler on another machine</td>
              <td>Producing a compiler for a brand-new CPU without writing assembly</td>
            </tr>
            <tr>
              <td>Intermediate Form</td>
              <td>Compile to a middle-layer language (e.g. atoms, bytecode); share front/back ends</td>
              <td>Supporting many languages on many machines efficiently</td>
            </tr>
            <tr>
              <td>Compiler-Compilers</td>
              <td>Auto-generate a compiler from a formal language specification</td>
              <td>Rapid development using tools like SableCC, JavaCC, yacc</td>
            </tr>
          </tbody>
        </table>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 1.3.0 Computer Notation                        -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="notation">
        <h2><span class="sec-icon">🖥️</span> Computer &amp; Compiler Notation (Figures 1.5 &amp; 1.6)</h2>
        <p>
          To describe implementation techniques graphically, the textbook uses a specific notation.
          A <strong>computer</strong> is drawn as a rectangle, with the machine name in a smaller rectangle on top.
          The program loaded in its RAM is shown inside. Input arrives from the left; output leaves to the right.
        </p>

        <!-- Figure 1.5 -->
        <h3>📐 Figure 1.5 — A Program Running on a Computer</h3>
        <div class="computer-diagram">
          <div>
            <div class="comp-io-label">Input</div>
            <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--purple); background:var(--purple-s); padding:8px 16px; border-radius:10px;">C<sup>X→Y</sup><sub>S</sub></div>
          </div>
          <div class="comp-arrow">→</div>
          <div class="comp-box">
            <div class="comp-name-tag">Machine Name (M)</div>
            <div class="comp-ram">
              <small>Program loaded in RAM</small>
              C<sup>S→O</sup><sub>M</sub>
            </div>
          </div>
          <div class="comp-arrow">→</div>
          <div>
            <div class="comp-io-label">Output</div>
            <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--green); background:#F0FDF4; padding:8px 16px; border-radius:10px;">C<sup>X→Y</sup><sub>O</sub></div>
          </div>
        </div>

        <div class="note-box pro">
          <span class="box-icon">🧩</span>
          <div>
            <strong>Key rules for Figure 1.6:</strong>
            <ul style="margin: 8px 0 0 16px;">
              <li>The superscript on the <strong>output</strong> is always the same as on the <strong>input</strong> (X→Y), because a compiler does not change the <em>purpose</em> of the source program — it only changes the language it exists in.</li>
              <li>The subscript of the executing compiler (in RAM) <strong>must be M</strong> — the machine language of the computer running it.</li>
              <li>The subscript of the <strong>input</strong> must match the <em>source language</em> of the executing compiler (S).</li>
              <li>The subscript of the <strong>output</strong> must match the <em>object language</em> of the executing compiler (O).</li>
            </ul>
          </div>
        </div>

        <div class="note-box warn">
          <span class="box-icon">⚠️</span>
          <div>
            <strong>Critical constraint:</strong> A computer can only run programs written in <em>its own machine language</em>.
            The compiler loaded into RAM <strong>must have the machine's language as its subscript</strong> — otherwise the computer cannot execute it.
          </div>
        </div>
      </section>





      <!-- Chapter nav -->
      <div class="chapter-nav">
        <a href="../home.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Previous page</span>Home</div>
        </a>
        <a href="?complete=12" class="nav-btn next">
          <div style="text-align:right"><span class="btn-sub">Next page</span>1.3.1 Bootstrapping</div>
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
  </script>

</body>

</html>