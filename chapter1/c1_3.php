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

  <style>
    :root {
      --bg: #F8F7FF;
      --purple: #6366F1;
      --purple-d: #4F46E5;
      --purple-s: #EEF2FF;
      --pink: #FFD6FF;
      --text: #1E1B4B;
      --muted: #6B7280;
      --card: #FFFFFF;
      --green: #10B981;
      --amber: #F59E0B;
      --red: #EF4444;
      --code-bg: #1A1830;
      --radius: 14px;
      --shadow: 0 4px 24px rgba(99,102,241,.09);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); font-size: 15px; line-height: 1.75; }

    /* ── Navbar ─────────────────────── */
    #navbar { background: var(--pink) !important; }
    .navbar-brand { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 21px; color: var(--text) !important; }
    #navbar .nav-link { font-weight: 500; color: var(--text) !important; border-radius: 8px; padding: 6px 13px !important; transition: background .2s; }
    #navbar .nav-link:hover { background: rgba(99,102,241,.1); }

    /* ── Layout ─────────────────────── */
    .note-container { display: grid; grid-template-columns: 260px 1fr; gap: 40px; max-width: 1140px; margin: 0 auto; padding: 36px 24px 80px; align-items: start; }

    /* ── TOC ────────────────────────── */
    .toc { position: sticky; top: 24px; background: var(--card); border: 1px solid rgba(99,102,241,.12); border-radius: var(--radius); padding: 22px 20px; box-shadow: var(--shadow); }
    .toc-title { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: var(--purple); margin-bottom: 14px; }
    .toc ul { list-style: none; padding: 0; }
    .toc ul li { margin-bottom: 4px; }
    .toc ul li a { display: block; padding: 7px 10px; border-radius: 8px; font-size: 13px; color: var(--muted); text-decoration: none; transition: background .18s, color .18s; line-height: 1.4; }
    .toc ul li a:hover, .toc ul li a.active { background: var(--purple-s); color: var(--purple); font-weight: 500; }
    .toc ul li a.sub { padding-left: 22px; font-size: 12px; }
    .toc-progress { margin-top: 18px; padding-top: 16px; border-top: 1px solid #EEF2FF; }
    .toc-progress-label { display: flex; justify-content: space-between; font-size: 12px; color: var(--muted); margin-bottom: 6px; }
    .toc-bar { height: 6px; background: #E8E8F0; border-radius: 20px; overflow: hidden; }
    .toc-bar-fill { height: 100%; background: var(--purple); border-radius: 20px; width: 0%; transition: width .6s ease; }

    /* ── Article ─────────────────────── */
    .content { min-width: 0; }
    .content-header { margin-bottom: 36px; }
    .chapter-tag { display: inline-flex; align-items: center; gap: 6px; background: var(--purple-s); color: var(--purple); font-family: 'Syne', sans-serif; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; padding: 4px 12px; border-radius: 20px; margin-bottom: 12px; }
    .content-header h1 { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 34px; letter-spacing: -1px; color: var(--text); line-height: 1.15; margin-bottom: 10px; }
    .metadata { display: flex; gap: 16px; font-size: 13px; color: var(--muted); flex-wrap: wrap; }
    .metadata span { display: flex; align-items: center; gap: 5px; }

    /* ── Sections ───────────────────── */
    section { margin-bottom: 52px; scroll-margin-top: 80px; }
    section h2 { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 22px; color: var(--text); margin-bottom: 14px; padding-bottom: 10px; border-bottom: 2px solid var(--purple-s); display: flex; align-items: center; gap: 10px; }
    section h2 .sec-icon { width: 32px; height: 32px; background: var(--purple-s); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
    section h3 { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 16px; color: var(--text); margin: 26px 0 10px; display: flex; align-items: center; gap: 8px; }
    section p { color: var(--muted); margin-bottom: 14px; }
    section p strong { color: var(--text); }
    section ul, section ol { color: var(--muted); margin: 0 0 14px 20px; }
    section li { margin-bottom: 5px; }
    section code { font-family: 'JetBrains Mono', monospace; font-size: 12.5px; background: var(--purple-s); color: var(--purple); padding: 2px 6px; border-radius: 5px; }

    /* ── Info boxes ─────────────────── */
    .note-box { display: flex; gap: 12px; padding: 16px 18px; border-radius: var(--radius); margin: 20px 0; font-size: 14px; line-height: 1.65; }
    .note-box .box-icon { font-size: 20px; flex-shrink: 0; margin-top: 1px; }
    .note-box.tip  { background: #EFF6FF; border-left: 4px solid #3B82F6; color: #1D4ED8; }
    .note-box.pro  { background: var(--purple-s); border-left: 4px solid var(--purple); color: #3730A3; }
    .note-box.warn { background: #FFFBEB; border-left: 4px solid var(--amber); color: #92400E; }
    .note-box.key  { background: #F0FDF4; border-left: 4px solid var(--green); color: #065F46; }

    /* ── Big-C notation cards ─────────── */
    .bigc-inline { display: inline-flex; flex-direction: column; align-items: center; background: var(--code-bg); border-radius: 10px; padding: 8px 16px; font-family: 'JetBrains Mono', monospace; vertical-align: middle; margin: 0 4px; }
    .bigc-inline .bc-super { font-size: 10px; color: #A5B4FC; }
    .bigc-inline .bc-main  { font-size: 18px; color: #CDD6F4; font-weight: 700; line-height: 1; }
    .bigc-inline .bc-sub   { font-size: 10px; color: #6C7086; }

    /* ── Computer diagram ────────────── */
    .computer-diagram { display: flex; align-items: center; justify-content: center; gap: 20px; margin: 28px 0; flex-wrap: wrap; }
    .comp-box { display: flex; flex-direction: column; align-items: center; }
    .comp-name-tag { background: var(--purple-s); border: 1.5px solid rgba(99,102,241,.3); border-radius: 6px 6px 0 0; padding: 5px 20px; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 12px; color: var(--purple); text-align: center; min-width: 140px; }
    .comp-ram { background: var(--card); border: 1.5px solid rgba(99,102,241,.3); border-radius: 0 0 8px 8px; padding: 14px 20px; font-family: 'JetBrains Mono', monospace; font-size: 13px; color: var(--text); text-align: center; min-width: 140px; box-shadow: var(--shadow); }
    .comp-ram small { display: block; font-size: 11px; color: var(--muted); margin-bottom: 4px; font-family: 'DM Sans', sans-serif; }
    .comp-arrow { display: flex; align-items: center; gap: 6px; color: var(--purple); font-size: 22px; font-weight: 700; }
    .comp-arrow span { font-size: 12px; color: var(--muted); font-family: 'JetBrains Mono', monospace; }
    .comp-io-label { font-size: 11px; color: var(--muted); margin-bottom: 4px; font-family: 'DM Sans', sans-serif; text-align: center; }

    /* ── Step diagram ────────────────── */
    .step-diagram { background: var(--card); border: 1px solid rgba(99,102,241,.15); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); margin: 28px 0; }
    .step-diagram-header { background: linear-gradient(135deg,#6366F1,#818CF8); padding: 12px 20px; display: flex; align-items: center; gap: 10px; }
    .step-diagram-header h4 { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 14px; color: white; margin: 0; }
    .step-diagram-body { padding: 24px; }
    .step-row { display: flex; align-items: center; gap: 16px; margin-bottom: 6px; }
    .step-badge { background: var(--purple); color: white; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 11px; padding: 3px 10px; border-radius: 12px; white-space: nowrap; flex-shrink: 0; }
    .step-arrow-down { display: flex; justify-content: flex-start; padding-left: 36px; margin-bottom: 6px; }
    .step-arrow-down svg { opacity: .5; }
    .step-desc { font-size: 13px; color: var(--muted); }

    /* ── Comparison table ─────────────── */
    .compare-table { width: 100%; border-collapse: collapse; margin: 16px 0; font-size: 14px; }
    .compare-table th { background: var(--purple-s); color: var(--purple); font-family: 'Syne', sans-serif; font-weight: 700; padding: 10px 14px; text-align: left; }
    .compare-table td { padding: 9px 14px; border-bottom: 1px solid #EEF2FF; color: var(--muted); vertical-align: top; }
    .compare-table tr:hover td { background: #FAFAFF; }
    .compare-table td:first-child { font-weight: 600; color: var(--text); }

    /* ── Sample problem ─────────────── */
    .sample-problem { background: var(--card); border: 1.5px solid rgba(99,102,241,.2); border-radius: var(--radius); margin: 28px 0; overflow: hidden; box-shadow: var(--shadow); }
    .sp-header { background: linear-gradient(135deg,#6366F1,#818CF8); padding: 12px 20px; display: flex; align-items: center; gap: 10px; }
    .sp-header h4 { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 14px; color: white; margin: 0; }
    .sp-body { padding: 20px; }
    .sp-question { color: var(--muted); font-size: 14px; margin-bottom: 16px; }
    .sp-solution-label { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--green); margin-bottom: 12px; }

    /* ── Intermediate form diagram ──── */
    .int-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin: 24px 0; }
    .int-form-card { background: var(--card); border: 1px solid rgba(99,102,241,.15); border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow); }
    .int-form-card h5 { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 14px; color: var(--text); margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
    .int-form-card .badge-bad  { background: #FFF1F2; color: #9F1239; font-size: 11px; padding: 2px 8px; border-radius: 8px; }
    .int-form-card .badge-good { background: #F0FDF4; color: #065F46; font-size: 11px; padding: 2px 8px; border-radius: 8px; }

    /* ── Formula box ─────────────────── */
    .formula-box { background: var(--code-bg); border-radius: var(--radius); padding: 20px 28px; margin: 20px 0; text-align: center; }
    .formula-box .formula { font-family: 'JetBrains Mono', monospace; font-size: 16px; color: #CDD6F4; }
    .formula-box .formula .highlight { color: #A5B4FC; font-weight: 700; }
    .formula-box .formula .good { color: #6EE7B7; font-weight: 700; }
    .formula-box small { display: block; color: rgba(205,214,244,.4); font-size: 12px; margin-top: 8px; }

    /* ── Interactive bootstrap demo ─── */
    .demo-wrap { background: var(--card); border: 1px solid rgba(99,102,241,.15); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); margin: 28px 0; }
    .demo-header { background: var(--purple-s); padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; }
    .demo-header h4 { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 15px; color: var(--purple); margin: 0; }
    .demo-body { padding: 24px; }
    .demo-controls { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
    .demo-btn { padding: 8px 18px; border-radius: 8px; border: 1.5px solid rgba(99,102,241,.3); background: transparent; color: var(--purple); font-family: 'Syne', sans-serif; font-weight: 600; font-size: 13px; cursor: pointer; transition: background .18s, color .18s; }
    .demo-btn:hover { background: var(--purple-s); }
    .demo-btn.active { background: var(--purple); color: white; border-color: var(--purple); }
    .demo-stage { display: none; }
    .demo-stage.show { display: block; }

    /* ── Quiz ───────────────────────── */
    .mini-quiz { background: var(--card); border: 1px solid rgba(99,102,241,.15); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); margin: 28px 0; }
    .quiz-header { background: linear-gradient(135deg,#6366F1,#818CF8); padding: 14px 20px; display: flex; align-items: center; gap: 10px; }
    .quiz-header h4 { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 15px; color: white; margin: 0; }
    .quiz-body { padding: 22px; }
    .quiz-q { font-weight: 600; color: var(--text); margin-bottom: 16px; font-size: 15px; }
    .quiz-options { display: flex; flex-direction: column; gap: 10px; }
    .quiz-opt { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border: 1.5px solid rgba(99,102,241,.15); border-radius: 10px; cursor: pointer; font-size: 14px; transition: border-color .18s, background .18s; color: var(--text); }
    .quiz-opt:hover:not(.answered) { border-color: var(--purple); background: var(--purple-s); }
    .quiz-opt.answered { cursor: not-allowed; }
    .quiz-opt.correct { border-color: var(--green); background: #F0FDF4; color: #065F46; }
    .quiz-opt.wrong   { border-color: var(--red); background: #FFF1F2; color: #9F1239; }
    .opt-circle { width: 28px; height: 28px; border-radius: 50%; background: var(--purple-s); color: var(--purple); font-family: 'Syne', sans-serif; font-weight: 700; font-size: 13px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .quiz-feedback { margin-top: 14px; padding: 10px 16px; border-radius: 10px; font-size: 14px; display: none; }
    .quiz-feedback.show { display: block; }
    .quiz-feedback.ok  { background: #F0FDF4; color: #065F46; }
    .quiz-feedback.bad { background: #FFF1F2; color: #9F1239; }

    /* ── Chapter nav ─────────────────── */
    .chapter-nav { display: flex; justify-content: space-between; gap: 16px; margin-top: 56px; padding-top: 28px; border-top: 1px solid #EEF2FF; }
    .nav-btn { display: flex; align-items: center; gap: 10px; padding: 14px 22px; border: 1.5px solid rgba(99,102,241,.25); border-radius: 12px; background: var(--card); text-decoration: none; color: var(--text); font-family: 'Syne', sans-serif; font-weight: 600; font-size: 14px; transition: border-color .2s, box-shadow .2s, transform .2s; box-shadow: var(--shadow); }
    .nav-btn:hover { border-color: var(--purple); box-shadow: 0 6px 24px rgba(99,102,241,.18); transform: translateY(-2px); color: var(--purple); }
    .nav-btn .btn-sub { font-family: 'DM Sans', sans-serif; font-weight: 400; font-size: 11px; color: var(--muted); display: block; }
    .nav-btn.next { margin-left: auto; }

    /* ── Animations ─────────────────── */
    @keyframes fadeUp { from { opacity:0; transform:translateY(18px); } to { opacity:1; transform:translateY(0); } }
    section { animation: fadeUp .5s ease forwards; opacity: 0; }
    section:nth-child(1) { animation-delay: .05s; }
    section:nth-child(2) { animation-delay: .10s; }
    section:nth-child(3) { animation-delay: .15s; }
    section:nth-child(4) { animation-delay: .20s; }
    section:nth-child(5) { animation-delay: .25s; }
    section:nth-child(6) { animation-delay: .30s; }
    section:nth-child(7) { animation-delay: .35s; }
    section:nth-child(8) { animation-delay: .40s; }

    @media (max-width:768px) {
      .note-container { grid-template-columns: 1fr; }
      .toc { position: static; }
      .int-form-grid { grid-template-columns: 1fr; }
      .computer-diagram { flex-direction: column; }
    }
  </style>
</head>

<body>

  <?php include '../dashboard.php'; ?>

  <div class="note-container">

    <!-- ── Sidebar TOC ─────────────────────────────────── -->
    <nav class="toc">
      <div class="toc-title">📖 Contents</div>
      <ul>
        <li><a href="#overview"     class="toc-link">1.3 Overview</a></li>
        <li><a href="#notation"     class="toc-link">1.3.0 Computer Notation</a></li>
        <li><a href="#bootstrap"    class="toc-link">1.3.1 Bootstrapping</a></li>
        <li><a href="#sp13"         class="toc-link sub">↳ Sample Problem 1.3</a></li>
        <li><a href="#cross"        class="toc-link">1.3.2 Cross Compiling</a></li>
        <li><a href="#intermediate" class="toc-link">1.3.3 Intermediate Form</a></li>
        <li><a href="#compilercomp" class="toc-link">1.3.4 Compiler-Compilers</a></li>
        <li><a href="#interactive"  class="toc-link">Interactive Explorer</a></li>
        <li><a href="#quiz"         class="toc-link">Check Your Knowledge</a></li>
      </ul>
      <div class="toc-progress">
        <div class="toc-progress-label"><span>Progress</span><span id="pct-label">0%</span></div>
        <div class="toc-bar"><div class="toc-bar-fill" id="toc-bar"></div></div>
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
          <thead><tr><th>Technique</th><th>Core Idea</th><th>When to Use</th></tr></thead>
          <tbody>
            <tr><td>Bootstrapping</td><td>Use a compiler as input to itself; build up from a small subset</td><td>Building the first compiler for a new language on an existing machine</td></tr>
            <tr><td>Cross Compiling</td><td>Compile for a new target machine using an existing compiler on another machine</td><td>Producing a compiler for a brand-new CPU without writing assembly</td></tr>
            <tr><td>Intermediate Form</td><td>Compile to a middle-layer language (e.g. atoms, bytecode); share front/back ends</td><td>Supporting many languages on many machines efficiently</td></tr>
            <tr><td>Compiler-Compilers</td><td>Auto-generate a compiler from a formal language specification</td><td>Rapid development using tools like SableCC, JavaCC, yacc</td></tr>
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

      <!-- ══════════════════════════════════════════════ -->
      <!-- 1.3.1 Bootstrapping                           -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="bootstrap">
        <h2><span class="sec-icon">🥾</span> 1.3.1 Bootstrapping</h2>
        <p>
          The term <strong>bootstrapping</strong> comes from "pull yourself up by your bootstraps" and generally
          involves using a program as input to itself. You may already know this from <em>bootstrap loaders</em>
          used to initialise a computer at power-on — hence the expression "to boot" a computer.
        </p>
        <p>
          In compiler terms, bootstrapping lets us build a full compiler for a language without writing the
          entire thing in machine language. The idea: write two smaller, simpler compilers instead.
        </p>

        <h3>📌 Example — Bootstrapping Java onto a Sun Computer (Figure 1.7)</h3>
        <p>
          Goal: produce a Java compiler for the Sun machine
          (<span style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--purple);">C<sup>Java→Sun</sup><sub>Sun</sub></span>)
          without writing the whole thing in assembly.
        </p>
        <p>
          The <strong>subset language</strong> "Sub" is simply Java without several superfluous features
          (enumerated types, unions, switch statements, etc.).
        </p>

        <div class="step-diagram">
          <div class="step-diagram-header">
            <span style="font-size:18px">🔧</span>
            <h4>Figure 1.7 — Two-Step Bootstrapping Process</h4>
          </div>
          <div class="step-diagram-body">

            <p style="font-size:13px; color:var(--muted); margin-bottom:20px;">
              We write <strong>two small compilers</strong> instead of one large one:
            </p>

            <!-- What we write -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:24px;">
              <div style="background:var(--purple-s); border-radius:var(--radius); padding:16px;">
                <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:12px; color:var(--purple); margin-bottom:8px;">WE WRITE (1) — in machine language</div>
                <div style="font-family:'JetBrains Mono',monospace; font-size:16px; color:var(--purple); margin-bottom:6px;">C<sup>Sub→Sun</sup><sub>Sun</sub></div>
                <div style="font-size:13px; color:var(--muted);">A compiler for the <em>subset</em> of Java, written in Sun machine language. Small and manageable to write by hand.</div>
              </div>
              <div style="background:var(--purple-s); border-radius:var(--radius); padding:16px;">
                <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:12px; color:var(--purple); margin-bottom:8px;">WE WRITE (2) — in the subset language</div>
                <div style="font-family:'JetBrains Mono',monospace; font-size:16px; color:var(--purple); margin-bottom:6px;">C<sup>Java→Sun</sup><sub>Sub</sub></div>
                <div style="font-size:13px; color:var(--muted);">A compiler for <em>full Java</em>, written in the Sub language. Much easier to write than assembly.</div>
              </div>
            </div>

            <!-- Step 1 -->
            <div class="step-row">
              <span class="step-badge">STEP 1</span>
              <span class="step-desc">Load the Sub compiler into the Sun. Feed the full-Java compiler (written in Sub) as input.</span>
            </div>
            <div class="computer-diagram" style="margin:16px 0;">
              <div>
                <div class="comp-io-label">Input</div>
                <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--purple); background:var(--purple-s); padding:8px 14px; border-radius:10px;">C<sup>Java→Sun</sup><sub>Sub</sub></div>
              </div>
              <div class="comp-arrow">→</div>
              <div class="comp-box">
                <div class="comp-name-tag">Sun</div>
                <div class="comp-ram"><small>RAM</small>C<sup>Sub→Sun</sup><sub>Sun</sub></div>
              </div>
              <div class="comp-arrow">→</div>
              <div>
                <div class="comp-io-label">Output ✅</div>
                <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--green); background:#F0FDF4; padding:8px 14px; border-radius:10px;">C<sup>Java→Sun</sup><sub>Sun</sub></div>
              </div>
            </div>
            <div class="note-box key" style="margin:0 0 16px;">
              <span class="box-icon">✅</span>
              <div>Output of Step 1 is exactly the compiler we wanted — a Java compiler that runs on the Sun and produces Sun machine code. <strong>Bootstrapping complete in one step here!</strong></div>
            </div>

            <div class="note-box tip">
              <span class="box-icon">💡</span>
              <div>
                <strong>In practice, bootstrapping is iterative.</strong> You start with a tiny subset (Sub₁),
                produce a slightly larger subset (Sub₂), feed that back in to produce Sub₃, and so on —
                expanding the language until you have a compiler for the complete language.
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Sample Problem 1.3 -->
      <section id="sp13">
        <h2><span class="sec-icon">🧪</span> Sample Problem 1.3</h2>

        <div class="sample-problem">
          <div class="sp-header"><span style="font-size:18px">📋</span><h4>Sample Problem 1.3</h4></div>
          <div class="sp-body">
            <p class="sp-question">Show the output of the following compilation using the Big-C notation:</p>

            <div class="computer-diagram" style="margin:20px 0;">
              <div>
                <div class="comp-io-label">Input</div>
                <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--purple); background:var(--purple-s); padding:8px 14px; border-radius:10px;">C<sup>Ada→PC</sup><sub>Java</sub></div>
              </div>
              <div class="comp-arrow">→</div>
              <div class="comp-box">
                <div class="comp-name-tag">Sun</div>
                <div class="comp-ram"><small>RAM</small>C<sup>Java→Sun</sup><sub>Sun</sub></div>
              </div>
              <div class="comp-arrow">→</div>
              <div>
                <div class="comp-io-label">Output = ?</div>
                <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--muted); background:#F8F7FF; border:1.5px dashed rgba(99,102,241,.3); padding:8px 14px; border-radius:10px;">C<sup>?→?</sup><sub>?</sub></div>
              </div>
            </div>

            <div class="sp-solution-label">✅ Solution</div>

            <div class="note-box pro" style="margin-bottom:16px;">
              <span class="box-icon">🧩</span>
              <div>
                <strong>Reasoning step-by-step:</strong>
                <ol style="margin: 8px 0 0 16px; color: #3730A3;">
                  <li>The compiler in RAM translates <strong>Java → Sun</strong>. So it processes input written in <strong>Java</strong> and produces output in <strong>Sun</strong> machine language.</li>
                  <li>The input is <code>C<sup>Ada→PC</sup><sub>Java</sub></code> — it <em>exists in Java</em>, so the executing compiler can process it. ✓</li>
                  <li>The <strong>superscript is preserved</strong> (the purpose doesn't change): Ada→PC stays Ada→PC.</li>
                  <li>The subscript of the output = the <em>object language</em> of the executing compiler = <strong>Sun</strong>.</li>
                </ol>
              </div>
            </div>

            <div class="computer-diagram" style="margin:20px 0;">
              <div>
                <div class="comp-io-label">Input</div>
                <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--purple); background:var(--purple-s); padding:8px 14px; border-radius:10px;">C<sup>Ada→PC</sup><sub>Java</sub></div>
              </div>
              <div class="comp-arrow">→</div>
              <div class="comp-box">
                <div class="comp-name-tag">Sun</div>
                <div class="comp-ram"><small>RAM</small>C<sup>Java→Sun</sup><sub>Sun</sub></div>
              </div>
              <div class="comp-arrow">→</div>
              <div>
                <div class="comp-io-label">Output ✅</div>
                <div style="font-family:'JetBrains Mono',monospace; font-size:14px; color:var(--green); background:#F0FDF4; padding:8px 14px; border-radius:10px; font-weight:700;">C<sup>Ada→PC</sup><sub>Sun</sub></div>
              </div>
            </div>
            <p style="font-size:13px; color:var(--muted);">
              The result is an Ada→PC compiler that now runs on a Sun machine. The same Ada→PC translation purpose is preserved; only the implementation language changed from Java to Sun machine code.
            </p>
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 1.3.2 Cross Compiling                         -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="cross">
        <h2><span class="sec-icon">🔀</span> 1.3.2 Cross Compiling</h2>
        <p>
          New computers are constantly being produced. Developers face the challenge of producing a new
          compiler for each existing programming language every time a new CPU is designed.
          <strong>Cross compiling</strong> solves this without writing anything in machine language.
        </p>

        <h3>📌 Example — Cross Compiling Java from a Sun to a Mac (Figure 1.8)</h3>
        <p>
          Suppose we already have a Java compiler for the Sun
          (<code>C<sup>Java→Sun</sup><sub>Sun</sub></code>).
          We design a new machine called a Mac and want to produce a Java compiler for it
          (<code>C<sup>Java→Mac</sup><sub>Mac</sub></code>).
          Instead of writing it in assembly, we <strong>write the compiler in Java</strong>
          (<code>C<sup>Java→Mac</sup><sub>Java</sub></code>) and cross-compile it in two steps.
        </p>

        <div class="step-diagram">
          <div class="step-diagram-header">
            <span style="font-size:18px">🔀</span>
            <h4>Figure 1.8 — Two-Step Cross Compilation</h4>
          </div>
          <div class="step-diagram-body">

            <!-- Step 1 -->
            <div class="step-row" style="margin-bottom:14px;">
              <span class="step-badge">STEP 1</span>
              <span class="step-desc">Use the Java compiler (written in Java) as input to the existing Sun Java compiler. Output: a Mac-targeting compiler that runs on the Sun.</span>
            </div>
            <div class="computer-diagram" style="margin:16px 0 24px;">
              <div>
                <div class="comp-io-label">Input</div>
                <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--purple); background:var(--purple-s); padding:8px 14px; border-radius:10px;">C<sup>Java→Mac</sup><sub>Java</sub></div>
              </div>
              <div class="comp-arrow">→</div>
              <div class="comp-box">
                <div class="comp-name-tag">Sun</div>
                <div class="comp-ram"><small>RAM</small>C<sup>Java→Sun</sup><sub>Sun</sub></div>
              </div>
              <div class="comp-arrow">→</div>
              <div>
                <div class="comp-io-label">Output</div>
                <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--amber); background:#FFFBEB; padding:8px 14px; border-radius:10px;">C<sup>Java→Mac</sup><sub>Sun</sub></div>
              </div>
            </div>

            <div style="border-left:3px solid var(--amber); padding-left:14px; margin-bottom:24px;">
              <p style="font-size:13px; color:var(--muted); margin:0;">
                We now have a compiler that translates Java to Mac machine code, but it <em>runs on the Sun</em> — not yet the Mac. We need one more step.
              </p>
            </div>

            <!-- Step 2 -->
            <div class="step-row" style="margin-bottom:14px;">
              <span class="step-badge">STEP 2</span>
              <span class="step-desc">Load the Step 1 output into the Sun. Feed the same Java compiler (written in Java) as input again. Output: a Mac compiler that runs natively on the Mac.</span>
            </div>
            <div class="computer-diagram" style="margin:16px 0;">
              <div>
                <div class="comp-io-label">Input</div>
                <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--purple); background:var(--purple-s); padding:8px 14px; border-radius:10px;">C<sup>Java→Mac</sup><sub>Java</sub></div>
              </div>
              <div class="comp-arrow">→</div>
              <div class="comp-box">
                <div class="comp-name-tag">Sun</div>
                <div class="comp-ram"><small>RAM</small>C<sup>Java→Mac</sup><sub>Sun</sub></div>
              </div>
              <div class="comp-arrow">→</div>
              <div>
                <div class="comp-io-label">Output ✅</div>
                <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:var(--green); background:#F0FDF4; padding:8px 14px; border-radius:10px; font-weight:700;">C<sup>Java→Mac</sup><sub>Mac</sub></div>
              </div>
            </div>

            <div class="note-box key" style="margin-top:16px;">
              <span class="box-icon">✅</span>
              <div>
                The final output is a Java compiler for the Mac, running natively on the Mac — exactly what we wanted. <strong>This entire process can be completed before a single Mac has been built</strong> — all we need is knowledge of the Mac's instruction set architecture.
              </div>
            </div>
          </div>
        </div>
      </section>

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
          <thead><tr><th>Intermediate Form</th><th>Used By</th><th>Notes</th></tr></thead>
          <tbody>
            <tr><td>Atom stream</td><td>Compiler internals (this textbook)</td><td>The sequence of atoms from the parser</td></tr>
            <tr><td>p-code</td><td>PDP-8, Apple II — UCSD Pascal</td><td>Early popular intermediate form from UC San Diego</td></tr>
            <tr><td>C language</td><td>Many languages today</td><td>Used as a portable high-level intermediate form</td></tr>
            <tr><td>Java Byte Code (JVM)</td><td>Java, Kotlin, Scala, Groovy</td><td>Extensively used on the internet; "write once, run anywhere"</td></tr>
          </tbody>
        </table>
      </section>

      <!-- ══════════════════════════════════════════════ -->
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
          <thead><tr><th>Tool</th><th>Environment</th><th>What it generates</th></tr></thead>
          <tbody>
            <tr><td><code>lex</code></td><td>Unix / C</td><td>Lexical analyser (scanner)</td></tr>
            <tr><td><code>yacc</code></td><td>Unix / C</td><td>Parser (syntax analyser) — "Yet Another Compiler Compiler"</td></tr>
            <tr><td><code>JavaCC</code></td><td>Java</td><td>Scanner + parser from a single grammar file</td></tr>
            <tr><td><code>SableCC</code></td><td>Java (public domain)</td><td>Full compiler framework — scanner, parser, AST, tree walker</td></tr>
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

      <!-- ══════════════════════════════════════════════ -->
      <!-- Quiz                                          -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="quiz">
        <h2><span class="sec-icon">🧩</span> Check Your Knowledge</h2>
        <p>Test what you've learned about section 1.3. Select the best answer for each question.</p>

        <!-- Q1 -->
        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span><h4>Question 1</h4></div>
          <div class="quiz-body">
            <p class="quiz-q">In the computer notation (Figure 1.6), what does the subscript of the executing compiler (the one loaded in RAM) represent?</p>
            <div class="quiz-options" id="q1-opts">
              <div class="quiz-opt" onclick="answer(this,'q1',false)"><div class="opt-circle">A</div> The source language of the program being compiled</div>
              <div class="quiz-opt" onclick="answer(this,'q1',true)"><div class="opt-circle">B</div> The machine language of the computer running it — the subscript must match the machine</div>
              <div class="quiz-opt" onclick="answer(this,'q1',false)"><div class="opt-circle">C</div> The target language the compiler will produce</div>
              <div class="quiz-opt" onclick="answer(this,'q1',false)"><div class="opt-circle">D</div> The intermediate form used internally by the compiler</div>
            </div>
            <div class="quiz-feedback" id="q1-fb"></div>
          </div>
        </div>

        <!-- Q2 -->
        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span><h4>Question 2</h4></div>
          <div class="quiz-body">
            <p class="quiz-q">When a compiler is used as input to another compiler, what happens to the superscript (e.g. Java→Sun) of the output?</p>
            <div class="quiz-options" id="q2-opts">
              <div class="quiz-opt" onclick="answer(this,'q2',false)"><div class="opt-circle">A</div> It changes to reflect the new language it was compiled into</div>
              <div class="quiz-opt" onclick="answer(this,'q2',true)"><div class="opt-circle">B</div> It stays the same — the purpose (translation performed) is preserved, only the subscript changes</div>
              <div class="quiz-opt" onclick="answer(this,'q2',false)"><div class="opt-circle">C</div> It becomes the name of the executing compiler</div>
              <div class="quiz-opt" onclick="answer(this,'q2',false)"><div class="opt-circle">D</div> It is always replaced with the intermediate language</div>
            </div>
            <div class="quiz-feedback" id="q2-fb"></div>
          </div>
        </div>

        <!-- Q3 -->
        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span><h4>Question 3</h4></div>
          <div class="quiz-body">
            <p class="quiz-q">What is the main advantage of bootstrapping a compiler rather than writing it entirely in machine language?</p>
            <div class="quiz-options" id="q3-opts">
              <div class="quiz-opt" onclick="answer(this,'q3',false)"><div class="opt-circle">A</div> The resulting compiler runs faster than one written in machine language</div>
              <div class="quiz-opt" onclick="answer(this,'q3',false)"><div class="opt-circle">B</div> It eliminates the need for a code generation phase</div>
              <div class="quiz-opt" onclick="answer(this,'q3',true)"><div class="opt-circle">C</div> You only need to write two smaller, simpler programs instead of one very complex one in assembly</div>
              <div class="quiz-opt" onclick="answer(this,'q3',false)"><div class="opt-circle">D</div> It produces a compiler that can run on any machine automatically</div>
            </div>
            <div class="quiz-feedback" id="q3-fb"></div>
          </div>
        </div>

        <!-- Q4 -->
        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span><h4>Question 4</h4></div>
          <div class="quiz-body">
            <p class="quiz-q">In cross compiling, when can the entire two-step process be completed?</p>
            <div class="quiz-options" id="q4-opts">
              <div class="quiz-opt" onclick="answer(this,'q4',false)"><div class="opt-circle">A</div> Only after at least one physical copy of the new machine has been built and tested</div>
              <div class="quiz-opt" onclick="answer(this,'q4',true)"><div class="opt-circle">B</div> Before a single copy of the new machine is built — you only need to know the machine's architecture</div>
              <div class="quiz-opt" onclick="answer(this,'q4',false)"><div class="opt-circle">C</div> After the new machine's operating system has been installed</div>
              <div class="quiz-opt" onclick="answer(this,'q4',false)"><div class="opt-circle">D</div> Only after writing a subset compiler in the new machine's assembly language</div>
            </div>
            <div class="quiz-feedback" id="q4-fb"></div>
          </div>
        </div>

        <!-- Q5 -->
        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span><h4>Question 5</h4></div>
          <div class="quiz-body">
            <p class="quiz-q">Given <strong>n = 5</strong> high-level languages and <strong>m = 4</strong> target machines, how many compilers are needed <em>without</em> intermediate form vs <em>with</em> intermediate form (assuming each front/back end is half a compiler)?</p>
            <div class="quiz-options" id="q5-opts">
              <div class="quiz-opt" onclick="answer(this,'q5',false)"><div class="opt-circle">A</div> 20 without vs 9 with</div>
              <div class="quiz-opt" onclick="answer(this,'q5',false)"><div class="opt-circle">B</div> 9 without vs 20 with</div>
              <div class="quiz-opt" onclick="answer(this,'q5',true)"><div class="opt-circle">C</div> 20 without vs 4.5 with — i.e. n×m = 20, (n+m)/2 = (5+4)/2 = 4.5</div>
              <div class="quiz-opt" onclick="answer(this,'q5',false)"><div class="opt-circle">D</div> 25 without vs 5 with</div>
            </div>
            <div class="quiz-feedback" id="q5-fb"></div>
          </div>
        </div>

      </section>

      <!-- Chapter nav -->
      <div class="chapter-nav">
        <a href="c1_2.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Previous section</span>1.2 Phases of a Compiler</div>
        </a>
        <a href="c1_4.php" class="nav-btn next">
          <div style="text-align:right"><span class="btn-sub">Next page</span>Chapter 1.4: Case Study Decaf</div>
          <span>→</span>
        </a>
      </div>

    </article>
  </div><!-- /.note-container -->

  <script>
    // ── TOC scroll progress ──────────────────────────────────
    const sections = document.querySelectorAll('section[id]');
    const tocLinks = document.querySelectorAll('.toc-link');
    const bar      = document.getElementById('toc-bar');
    const pctLabel = document.getElementById('pct-label');

    window.addEventListener('scroll', () => {
      const scrolled = window.scrollY;
      const total    = document.body.scrollHeight - window.innerHeight;
      const pct      = Math.round((scrolled / total) * 100);
      bar.style.width      = pct + '%';
      pctLabel.textContent = pct + '%';

      let current = '';
      sections.forEach(s => { if (scrolled >= s.offsetTop - 120) current = s.id; });
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
          if (o.getAttribute('onclick') && o.getAttribute('onclick').includes('true')) {
            o.classList.add('correct');
            o.querySelector('.opt-circle').textContent = '✓';
          }
        });
      }
      const fb = document.getElementById(`${qid}-fb`);
      fb.classList.add('show', correct ? 'ok' : 'bad');
      fb.innerHTML = correct
        ? '✅ <strong>Correct!</strong> Well done.'
        : '❌ <strong>Not quite.</strong> The correct answer is highlighted above.';
    }
  </script>

</body>
</html>