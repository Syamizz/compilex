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
$page = 15;
$nextPage = "c1_3_4.php";

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
        <li><a href="#intermediate" class="toc-link">1.3.3 Intermediate Form</a></li>
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
      <!-- 1.3.3 Intermediate Form                        -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="intermediate">
        <h2><span class="sec-icon">🔗</span> 1.3.3 Compiling to Intermediate Form</h2>
        <p>
          It is possible to compile to an <strong>intermediate form</strong> — a language somewhere between a
          high-level source language and machine code. The stream of atoms from the parser is one example;
          Java Byte Code is another popular one.
        </p>

        <h3>💡 The Problem it Solves</h3>
        <p>
          Without intermediate form, supporting <strong>n languages on m machines</strong> requires
          <strong>n × m compilers</strong>. Each language needs a separate compiler for every target machine.
        </p>
        <p>
          With intermediate form, a compiler is split into two halves:
        </p>
        <ul>
          <li>A <strong>front end</strong>: translates one source language into the intermediate form</li>
          <li>A <strong>back end</strong>: translates the intermediate form into one target machine's code</li>
        </ul>

        <div class="int-form-grid">
          <div class="int-form-card">
            <h5>😓 Without Intermediate Form <span class="badge-bad">n × m compilers</span></h5>
            <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:6px; text-align:center; font-size:12px; font-family:'JetBrains Mono',monospace;">
              <div style="background:var(--purple-s); padding:6px; border-radius:6px; color:var(--purple);">Java→PC</div>
              <div style="background:var(--purple-s); padding:6px; border-radius:6px; color:var(--purple);">C++→PC</div>
              <div style="background:var(--purple-s); padding:6px; border-radius:6px; color:var(--purple);">Ada→PC</div>
              <div style="background:var(--purple-s); padding:6px; border-radius:6px; color:var(--purple);">Java→Mac</div>
              <div style="background:var(--purple-s); padding:6px; border-radius:6px; color:var(--purple);">C++→Mac</div>
              <div style="background:var(--purple-s); padding:6px; border-radius:6px; color:var(--purple);">Ada→Mac</div>
            </div>
            <p style="font-size:13px; color:var(--muted); margin-top:12px; margin-bottom:0;">3 languages × 2 machines = <strong>6 compilers</strong> total.</p>
          </div>
          <div class="int-form-card">
            <h5>😊 With Intermediate Form <span class="badge-good">(n + m) / 2 compilers</span></h5>
            <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:6px; text-align:center; font-size:12px; font-family:'JetBrains Mono',monospace; margin-bottom:8px;">
              <div style="background:#F0FDF4; padding:6px; border-radius:6px; color:#065F46;">Java→Int</div>
              <div style="background:#F0FDF4; padding:6px; border-radius:6px; color:#065F46;">C++→Int</div>
              <div style="background:#F0FDF4; padding:6px; border-radius:6px; color:#065F46;">Ada→Int</div>
            </div>
            <div style="text-align:center; font-size:11px; color:var(--muted); margin-bottom:8px;">3 front ends ↑ + 2 back ends ↓</div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:6px; text-align:center; font-size:12px; font-family:'JetBrains Mono',monospace;">
              <div style="background:#F0FDF4; padding:6px; border-radius:6px; color:#065F46;">Int→PC</div>
              <div style="background:#F0FDF4; padding:6px; border-radius:6px; color:#065F46;">Int→Mac</div>
            </div>
            <p style="font-size:13px; color:var(--muted); margin-top:12px; margin-bottom:0;">3 + 2 = <strong>5 translators</strong> (vs 6). Savings grow rapidly with n and m.</p>
          </div>
        </div>

        <div class="formula-box">
          <div class="formula">
            Without intermediate form: &nbsp;<span class="highlight">n × m</span> compilers
          </div>
          <div class="formula" style="margin-top:10px;">
            With intermediate form: &nbsp;<span class="good">(n + m) / 2</span> compilers
            <span style="color:rgba(205,214,244,.4); font-size:13px;"> (assuming each front/back end = ½ a compiler)</span>
          </div>
          <small>For 10 languages and 10 machines: 100 compilers vs just 10.</small>
        </div>

        <h3>📚 Real-World Examples</h3>
        <table class="compare-table">
          <thead>
            <tr>
              <th>Intermediate Form</th>
              <th>Used By</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Atom stream</td>
              <td>Compiler internals (this textbook)</td>
              <td>The sequence of atoms from the parser</td>
            </tr>
            <tr>
              <td>p-code</td>
              <td>PDP-8, Apple II — UCSD Pascal</td>
              <td>Early popular intermediate form from UC San Diego</td>
            </tr>
            <tr>
              <td>C language</td>
              <td>Many languages today</td>
              <td>Used as a portable high-level intermediate form</td>
            </tr>
            <tr>
              <td>Java Byte Code (JVM)</td>
              <td>Java, Kotlin, Scala, Groovy</td>
              <td>Extensively used on the internet; "write once, run anywhere"</td>
            </tr>
          </tbody>
        </table>
      </section>




      <!-- Chapter nav -->
      <div class="chapter-nav">
        <a href="c1_3_2.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Previous page</span>1.3.2 Cross Compiling</div>
        </a>
        <a href="?complete=15" class="nav-btn next">
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