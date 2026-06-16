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

    #navbar { background: var(--pink) !important; }
    .navbar-brand { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 21px; color: var(--text) !important; }
    #navbar .nav-link { font-weight: 500; color: var(--text) !important; border-radius: 8px; padding: 6px 13px !important; transition: background .2s; }
    #navbar .nav-link:hover { background: rgba(99,102,241,.1); }

    .note-container { display: grid; grid-template-columns: 260px 1fr; gap: 40px; max-width: 1140px; margin: 0 auto; padding: 36px 24px 80px; align-items: start; }

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

    .content { min-width: 0; }
    .content-header { margin-bottom: 36px; }
    .chapter-tag { display: inline-flex; align-items: center; gap: 6px; background: var(--purple-s); color: var(--purple); font-family: 'Syne', sans-serif; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; padding: 4px 12px; border-radius: 20px; margin-bottom: 12px; }
    .content-header h1 { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 34px; letter-spacing: -1px; color: var(--text); line-height: 1.15; margin-bottom: 10px; }
    .metadata { display: flex; gap: 16px; font-size: 13px; color: var(--muted); flex-wrap: wrap; }
    .metadata span { display: flex; align-items: center; gap: 5px; }

    section { margin-bottom: 52px; scroll-margin-top: 80px; }
    section h2 { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 22px; color: var(--text); margin-bottom: 14px; padding-bottom: 10px; border-bottom: 2px solid var(--purple-s); display: flex; align-items: center; gap: 10px; }
    section h2 .sec-icon { width: 32px; height: 32px; background: var(--purple-s); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
    section h3 { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 16px; color: var(--text); margin: 26px 0 10px; }
    section p { color: var(--muted); margin-bottom: 14px; }
    section p strong { color: var(--text); }
    section ul, section ol { color: var(--muted); margin: 0 0 14px 20px; }
    section li { margin-bottom: 5px; }
    section code { font-family: 'JetBrains Mono', monospace; font-size: 12.5px; background: var(--purple-s); color: var(--purple); padding: 2px 6px; border-radius: 5px; }

    .note-box { display: flex; gap: 12px; padding: 16px 18px; border-radius: var(--radius); margin: 20px 0; font-size: 14px; line-height: 1.65; }
    .note-box .box-icon { font-size: 20px; flex-shrink: 0; margin-top: 1px; }
    .note-box.tip  { background: #EFF6FF; border-left: 4px solid #3B82F6; color: #1D4ED8; }
    .note-box.pro  { background: var(--purple-s); border-left: 4px solid var(--purple); color: #3730A3; }
    .note-box.warn { background: #FFFBEB; border-left: 4px solid var(--amber); color: #92400E; }
    .note-box.key  { background: #F0FDF4; border-left: 4px solid var(--green); color: #065F46; }

    /* Code block */
    .code-wrap { background: var(--code-bg); border-radius: var(--radius); margin: 20px 0; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.25); }
    .code-header { display: flex; justify-content: space-between; align-items: center; padding: 10px 16px; background: rgba(255,255,255,.05); border-bottom: 1px solid rgba(255,255,255,.07); }
    .code-lang { font-family: 'JetBrains Mono', monospace; font-size: 11px; color: rgba(255,255,255,.5); letter-spacing: .5px; text-transform: uppercase; }
    .code-dots { display: flex; gap: 6px; }
    .code-dots span { width:10px;height:10px;border-radius:50%; }
    .dot-r{background:#FF5F57;}.dot-y{background:#FEBC2E;}.dot-g{background:#28C840;}
    .copy-btn { background: rgba(255,255,255,.08); border: none; color: rgba(255,255,255,.6); font-size: 11px; padding: 4px 10px; border-radius: 6px; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background .2s; }
    .copy-btn:hover { background: rgba(255,255,255,.15); color: white; }
    pre { background: transparent; margin: 0; padding: 20px; overflow-x: auto; }
    pre code { font-family: 'JetBrains Mono', monospace; font-size: 13px; line-height: 1.85; color: #CDD6F4; background: transparent; padding: 0; border-radius: 0; }
    .kw{color:#CBA6F7;}.cm{color:#6C7086;font-style:italic;}.str{color:#A6E3A1;}
    .num{color:#FAB387;}.fn{color:#89B4FA;}.typ{color:#94E2D5;}.sym{color:#F38BA8;}

    /* Compare table */
    .compare-table { width: 100%; border-collapse: collapse; margin: 16px 0; font-size: 14px; }
    .compare-table th { background: var(--purple-s); color: var(--purple); font-family: 'Syne', sans-serif; font-weight: 700; padding: 10px 14px; text-align: left; }
    .compare-table td { padding: 9px 14px; border-bottom: 1px solid #EEF2FF; color: var(--muted); vertical-align: top; }
    .compare-table tr:hover td { background: #FAFAFF; }
    .compare-table td:first-child { font-weight: 600; color: var(--text); }

    /* Tool comparison cards */
    .tool-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin: 20px 0; }
    .tool-card { background: var(--card); border: 1.5px solid rgba(99,102,241,.15); border-radius: var(--radius); padding: 16px 18px; box-shadow: var(--shadow); }
    .tool-card .tc-name { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 14px; color: var(--purple); margin-bottom: 3px; }
    .tool-card .tc-sub  { font-size: 12px; color: var(--muted); margin-bottom: 8px; }
    .tool-card .tc-desc { font-size: 13px; color: var(--muted); line-height: 1.55; }

    /* Grammar section cards */
    .grammar-sections { display: flex; flex-direction: column; gap: 8px; margin: 20px 0; }
    .gs-row { display: flex; align-items: stretch; gap: 12px; }
    .gs-num { width: 36px; height: 36px; border-radius: 10px; font-family: 'Syne', sans-serif; font-weight: 800; font-size: 15px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; align-self: flex-start; margin-top: 2px; }
    .gs-body { flex: 1; }
    .gs-name { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 14px; color: var(--text); margin-bottom: 2px; }
    .gs-desc { font-size: 13px; color: var(--muted); }
    .gs-active .gs-num { background: var(--purple-s); color: var(--purple); }
    .gs-dim .gs-num    { background: #F1F5F9; color: #94A3B8; }

    /* Operator reference table */
    .op-ref { display: grid; grid-template-columns: auto 1fr auto; gap: 0; border-radius: var(--radius); overflow: hidden; border: 1px solid rgba(99,102,241,.15); margin: 20px 0; }
    .op-ref-row { display: contents; }
    .op-ref-row:hover > * { background: #FAFAFF; }
    .op-ref .or-op   { background: var(--code-bg); color: #A5B4FC; font-family: 'JetBrains Mono', monospace; font-size: 13px; font-weight: 700; padding: 9px 16px; border-bottom: 1px solid rgba(255,255,255,.04); }
    .op-ref .or-desc { background: var(--card); color: var(--muted); font-size: 13px; padding: 9px 14px; border-bottom: 1px solid #EEF2FF; }
    .op-ref .or-ex   { background: var(--purple-s); color: var(--purple); font-family: 'JetBrains Mono', monospace; font-size: 12px; padding: 9px 14px; border-bottom: 1px solid #EEF2FF; white-space: nowrap; }
    .op-ref-header > * { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 12px; background: var(--purple-s); color: var(--purple); padding: 8px 14px; }

    /* Sample problem */
    .sample-problem { background: var(--card); border: 1.5px solid rgba(99,102,241,.2); border-radius: var(--radius); margin: 28px 0; overflow: hidden; box-shadow: var(--shadow); }
    .sp-header { background: linear-gradient(135deg,#6366F1,#818CF8); padding: 12px 20px; display: flex; align-items: center; gap: 10px; }
    .sp-header h4 { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 14px; color: white; margin: 0; }
    .sp-body { padding: 20px; }
    .sp-question { color: var(--muted); font-size: 14px; margin-bottom: 16px; }
    .sp-solution-label { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--green); margin-bottom: 12px; }

    /* Token stream output */
    .tok-stream { display: flex; flex-wrap: wrap; gap: 8px; margin: 12px 0; }
    .tok-chip { display: inline-flex; flex-direction: column; align-items: center; padding: 6px 14px; border-radius: 10px; border: 1.5px solid; font-family: 'JetBrains Mono', monospace; }
    .tok-chip .tc-type { font-size: 10px; opacity: .7; margin-bottom: 2px; }
    .tok-chip .tc-val  { font-size: 13px; font-weight: 700; }
    .tc-num  { background: #F0FDF4; border-color: #10B981; color: #065F46; }
    .tc-id   { background: #EFF6FF; border-color: #3B82F6; color: #1D4ED8; }
    .tc-sp   { background: #F1F5F9; border-color: #CBD5E1; color: #475569; font-style: italic; }
    .tc-kw   { background: #FEF3C7; border-color: #F59E0B; color: #92400E; }
    .tc-op   { background: #FFF1F2; border-color: #EF4444; color: #9F1239; }
    .tc-cur  { background: #F3E8FF; border-color: #A855F7; color: #6B21A8; }
    .tc-unk  { background: #F8FAFC; border-color: #94A3B8; color: #475569; }

    /* ── DRAG-AND-DROP QUIZ ─────────────────────────────── */
    .dnd-quiz { background: var(--card); border: 1.5px solid rgba(99,102,241,.15); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); margin: 28px 0; }
    .dnd-header { padding: 14px 20px; display: flex; align-items: center; gap: 10px; }
    .dnd-q-num  { width: 32px; height: 32px; border-radius: 50%; font-family: 'Syne', sans-serif; font-weight: 800; font-size: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .dnd-q-text { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 14px; }
    .dnd-body  { padding: 18px 20px 20px; }
    .dnd-prompt { font-size: 14px; color: var(--muted); margin-bottom: 16px; }
    .dnd-prompt code { font-family: 'JetBrains Mono', monospace; font-size: 12.5px; background: var(--purple-s); color: var(--purple); padding: 2px 6px; border-radius: 5px; }

    /* Bank (source chips) */
    .dnd-bank { display: flex; flex-wrap: wrap; gap: 8px; min-height: 50px; padding: 12px; border: 2px dashed rgba(99,102,241,.2); border-radius: 10px; background: var(--bg); margin-bottom: 18px; transition: background .2s; }
    .dnd-bank.drag-over { background: var(--purple-s); border-color: var(--purple); }

    /* Drag chip */
    .dnd-chip { padding: 8px 16px; border-radius: 10px; font-family: 'JetBrains Mono', monospace; font-size: 13px; font-weight: 700; cursor: grab; user-select: none; transition: transform .15s, box-shadow .15s, opacity .15s; border: 2px solid; }
    .dnd-chip:active { cursor: grabbing; transform: scale(1.06); box-shadow: 0 8px 24px rgba(0,0,0,.18); }
    .dnd-chip.dragging { opacity: .45; }
    .chip-a { background: #FEF3C7; border-color: #F59E0B; color: #92400E; }
    .chip-b { background: #EFF6FF; border-color: #3B82F6; color: #1D4ED8; }
    .chip-c { background: #F0FDF4; border-color: #10B981; color: #065F46; }
    .chip-d { background: #F3E8FF; border-color: #A855F7; color: #6B21A8; }
    .chip-e { background: #FFF1F2; border-color: #EF4444; color: #9F1239; }
    .chip-f { background: #FFFBEB; border-color: #F59E0B; color: #78350F; }

    /* Drop zones row */
    .dnd-zones { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 14px; }
    .dnd-zone-wrap { flex: 1; min-width: 160px; }
    .dnd-zone-label { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: .8px; margin-bottom: 6px; }
    .dnd-zone { min-height: 56px; border: 2px dashed rgba(99,102,241,.25); border-radius: 10px; padding: 8px; display: flex; align-items: center; justify-content: center; transition: background .2s, border-color .2s; background: var(--bg); }
    .dnd-zone.drag-over { background: var(--purple-s); border-color: var(--purple); }
    .dnd-zone.correct-zone { border-color: var(--green); background: #F0FDF4; }
    .dnd-zone.wrong-zone   { border-color: var(--red);   background: #FFF1F2; }
    .dnd-zone .dnd-chip    { cursor: pointer; }
    .dnd-zone .dnd-chip:hover { opacity: .8; }

    .dnd-check { padding: 8px 20px; border-radius: 8px; border: none; background: var(--purple); color: white; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 13px; cursor: pointer; transition: background .18s; }
    .dnd-check:hover { background: var(--purple-d); }
    .dnd-reset { padding: 8px 16px; border-radius: 8px; border: 1.5px solid rgba(99,102,241,.3); background: transparent; color: var(--purple); font-family: 'Syne', sans-serif; font-weight: 700; font-size: 13px; cursor: pointer; margin-left: 8px; transition: background .18s; }
    .dnd-reset:hover { background: var(--purple-s); }
    .dnd-feedback { margin-top: 12px; padding: 10px 16px; border-radius: 10px; font-size: 14px; display: none; }
    .dnd-feedback.show { display: block; }
    .dnd-feedback.ok  { background: #F0FDF4; color: #065F46; }
    .dnd-feedback.bad { background: #FFF1F2; color: #9F1239; }
    .dnd-feedback.partial { background: #FFFBEB; color: #92400E; }

    /* Quiz */
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
    .quiz-opt.wrong   { border-color: var(--red);   background: #FFF1F2; color: #9F1239; }
    .opt-circle { width: 28px; height: 28px; border-radius: 50%; background: var(--purple-s); color: var(--purple); font-family: 'Syne', sans-serif; font-weight: 700; font-size: 13px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .quiz-feedback { margin-top: 14px; padding: 10px 16px; border-radius: 10px; font-size: 14px; display: none; }
    .quiz-feedback.show { display: block; }
    .quiz-feedback.ok  { background: #F0FDF4; color: #065F46; }
    .quiz-feedback.bad { background: #FFF1F2; color: #9F1239; }

    .chapter-nav { display: flex; justify-content: space-between; gap: 16px; margin-top: 56px; padding-top: 28px; border-top: 1px solid #EEF2FF; }
    .nav-btn { display: flex; align-items: center; gap: 10px; padding: 14px 22px; border: 1.5px solid rgba(99,102,241,.25); border-radius: 12px; background: var(--card); text-decoration: none; color: var(--text); font-family: 'Syne', sans-serif; font-weight: 600; font-size: 14px; transition: border-color .2s, box-shadow .2s, transform .2s; box-shadow: var(--shadow); }
    .nav-btn:hover { border-color: var(--purple); box-shadow: 0 6px 24px rgba(99,102,241,.18); transform: translateY(-2px); color: var(--purple); }
    .nav-btn .btn-sub { font-family: 'DM Sans', sans-serif; font-weight: 400; font-size: 11px; color: var(--muted); display: block; }
    .nav-btn.next { margin-left: auto; }

    @keyframes fadeUp { from{opacity:0;transform:translateY(18px);}to{opacity:1;transform:translateY(0);} }
    section { animation: fadeUp .5s ease forwards; opacity: 0; }
    section:nth-child(1){animation-delay:.05s;}section:nth-child(2){animation-delay:.10s;}
    section:nth-child(3){animation-delay:.15s;}section:nth-child(4){animation-delay:.20s;}
    section:nth-child(5){animation-delay:.25s;}section:nth-child(6){animation-delay:.30s;}
    section:nth-child(7){animation-delay:.35s;}section:nth-child(8){animation-delay:.40s;}
    section:nth-child(9){animation-delay:.45s;}section:nth-child(10){animation-delay:.50s;}

    @media(max-width:768px){
      .note-container{grid-template-columns:1fr;}.toc{position:static;}
      .tool-grid{grid-template-columns:1fr;}
      .dnd-zones{flex-direction:column;}
      .op-ref{grid-template-columns:auto 1fr;}
      .op-ref .or-ex{display:none;}
    }
  </style>
</head>

<body>

  <?php include '../dashboard.php'; ?>

  <div class="note-container">

    <nav class="toc">
      <div class="toc-title">📖 Contents</div>
      <ul>
        <li><a href="#overview"   class="toc-link">2.4 Overview</a></li>
        <li><a href="#tools"      class="toc-link">Tools: lex, yacc, SableCC</a></li>
        <li><a href="#sablecc-adv"class="toc-link">Why SableCC?</a></li>
        <li><a href="#input-file" class="toc-link">2.4.1 Grammar File Structure</a></li>
        <li><a href="#tokens"     class="toc-link sub">↳ 2.4.1.1 Token Declarations</a></li>
        <li><a href="#helpers"    class="toc-link sub">↳ 2.4.1.2 Helper Declarations</a></li>
        <li><a href="#states"     class="toc-link sub">↳ 2.4.1.3 States &amp; Context</a></li>
        <li><a href="#sp-a"       class="toc-link sub">↳ Sample Problem (a)</a></li>
        <li><a href="#sp-b"       class="toc-link sub">↳ Sample Problem (b)</a></li>
        <li><a href="#example"    class="toc-link">2.4.1.4 Full Example</a></li>
        <li><a href="#running"    class="toc-link">2.4.2 Running SableCC</a></li>
        <li><a href="#dragdrop"   class="toc-link">🎯 Drag &amp; Drop Quiz</a></li>
        <li><a href="#quiz"       class="toc-link">Check Your Knowledge</a></li>
      </ul>
      <div class="toc-progress">
        <div class="toc-progress-label"><span>Progress</span><span id="pct-label">0%</span></div>
        <div class="toc-bar"><div class="toc-bar-fill" id="toc-bar"></div></div>
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
      <!-- 2.4 Overview                                   -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="overview">
        <h2><span class="sec-icon">🛠️</span> 2.4 Overview</h2>
        <p>
          Rather than writing a lexical scanner by hand, we can use <strong>compiler-generating tools</strong>
          that automatically produce a scanner from a set of formal specifications. These tools originated
          in the Unix programming environment.
        </p>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Tools                                          -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="tools">
        <h2><span class="sec-icon">⚙️</span> Tools — lex, yacc, and Java Alternatives</h2>

        <div class="tool-grid">
          <div class="tool-card">
            <div class="tc-name">lex</div>
            <div class="tc-sub">Unix · C language</div>
            <div class="tc-desc">Generates a lexical analyser (scanner) from a set of regular-expression-based specifications. Designed for use with C.</div>
          </div>
          <div class="tool-card">
            <div class="tc-name">yacc</div>
            <div class="tc-sub">Yet Another Compiler-Compiler · Unix · C</div>
            <div class="tc-desc">Generates a parser (syntax analyser) from a grammar specification. Designed for use with C, typically paired with lex.</div>
          </div>
          <div class="tool-card">
            <div class="tc-name">JavaCC</div>
            <div class="tc-sub">Sun Microsystems · Java</div>
            <div class="tc-desc">Probably the most widely used Java replacement for lex+yacc. Combines scanner and parser generation. Limited to LL(1) grammars.</div>
          </div>
          <div class="tool-card">
            <div class="tc-name">SableCC ⭐</div>
            <div class="tc-sub">McGill University · Java (free)</div>
            <div class="tc-desc">Object-oriented, generates modular code, produces syntax trees, supports a wider class of grammars than JavaCC. Used throughout this course.</div>
          </div>
        </div>

        <p>Other Java tools include <strong>JLex</strong> (lexical analysis), <strong>CUP</strong> (Constructor of Useful Parsers), and <strong>ANTLR</strong> (Another Tool for Language Recognition).</p>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Why SableCC                                   -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="sablecc-adv">
        <h2><span class="sec-icon">⭐</span> Why SableCC over JavaCC?</h2>
        <ul>
          <li>Designed to <strong>make good use of Java</strong> — object-oriented, uses class inheritance extensively</li>
          <li>Compilation errors are <strong>easier to fix</strong></li>
          <li>Generates <strong>modular software</strong> — each class in a separate file</li>
          <li>Generates <strong>syntax trees</strong>, from which atoms or code can be generated directly</li>
          <li>Accommodates a <strong>wider class of languages</strong> — JavaCC only supports LL(1) grammars</li>
        </ul>
        <div class="note-box pro">
          <span class="box-icon">🧩</span>
          <div>
            Unlike lex/yacc which can be used separately, <strong>SableCC combines</strong> lexical and syntax
            analysis into a single program. Since syntax analysis is covered later, a SableCC template is
            provided so students can run SableCC for lexical analysis alone.
          </div>
        </div>
      </section>

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
          <div class="gs-row gs-active"><div class="gs-num">1</div><div class="gs-body"><div class="gs-name">Package declaration</div><div class="gs-desc">Declares the Java package name for all generated classes.</div></div></div>
          <div class="gs-row gs-active"><div class="gs-num">2</div><div class="gs-body"><div class="gs-name">Helpers</div><div class="gs-desc">Reusable macros (semantic substitutions) used within Token definitions.</div></div></div>
          <div class="gs-row gs-active"><div class="gs-num">3</div><div class="gs-body"><div class="gs-name">States</div><div class="gs-desc">Named scanner states for handling context-sensitive tokens (e.g. comments, strings).</div></div></div>
          <div class="gs-row gs-active"><div class="gs-num">4</div><div class="gs-body"><div class="gs-name">Tokens</div><div class="gs-desc">The token definitions using regular expressions. Core of the grammar file.</div></div></div>
          <div class="gs-row gs-dim"><div class="gs-num" style="background:#F1F5F9;color:#94A3B8;">5</div><div class="gs-body"><div class="gs-name" style="color:var(--muted);">Ignored tokens</div><div class="gs-desc">Tokens to discard (e.g. whitespace). Covered later.</div></div></div>
          <div class="gs-row gs-dim"><div class="gs-num" style="background:#F1F5F9;color:#94A3B8;">6</div><div class="gs-body"><div class="gs-name" style="color:var(--muted);">Productions</div><div class="gs-desc">Grammar rules for syntax analysis. Covered in Chapter 3.</div></div></div>
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
          <div class="op-ref-row op-ref-header"><div>Form</div><div>Description</div><div>Example</div></div>
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
          <div class="op-ref-row op-ref-header"><div>Operator</div><div>Meaning</div><div>Example</div></div>
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
          <thead><tr><th>Syntax</th><th>Meaning</th></tr></thead>
          <tbody>
            <tr><td><code>{statename} token = def;</code></td><td>Apply this definition only when in <code>statename</code>; remain in <code>statename</code></td></tr>
            <tr><td><code>{statename->newstate} token = def;</code></td><td>Apply when in <code>statename</code>; transition to <code>newstate</code></td></tr>
            <tr><td><code>{s1->s2, s3->s4, s5} token = def;</code></td><td>Multiple state associations in one definition</td></tr>
            <tr><td><code>token = def;</code></td><td>No state specified — applies in <strong>any</strong> state</td></tr>
          </tbody>
        </table>

        <h3>Right Context with <code>/</code></h3>
        <p>
          To recognise a token <em>only when followed by a certain pattern</em>, use the forward slash:
        </p>
        <div class="code-wrap">
          <div class="code-header"><div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">Right context example</span></div>
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
          <div class="code-header"><div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">SableCC — BOL/inline state example</span></div>
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
          <div class="sp-header"><span style="font-size:18px">📋</span><h4>Sample Problem 2.4 (a)</h4></div>
          <div class="sp-body">
            <p class="sp-question">Show the sequence of tokens recognised by the definitions of <code>number</code>, <code>identifier</code>, and <code>space</code> for the following input:</p>
            <div class="code-wrap" style="margin-bottom:16px;">
              <div class="code-header"><div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">Input</span></div>
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
          <div class="sp-header"><span style="font-size:18px">📋</span><h4>Sample Problem 2.4 (b)</h4></div>
          <div class="sp-body">
            <p class="sp-question">Show the token and state definitions needed to process a text file containing numbers, currency values (beginning with <code>$</code>, e.g. <code>$3045</code>), and spaces. Distinguish <strong>money</strong> from ordinary <strong>number</strong>. Assume whole numbers only.</p>
            <div class="sp-solution-label">✅ Solution</div>
            <div class="code-wrap">
              <div class="code-header"><div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">SableCC grammar — currency vs number</span><button class="copy-btn" onclick="copyBlock('sp-b-code',this)">Copy</button></div>
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
          <div class="code-header"><div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">lexing.grammar — complete example</span><button class="copy-btn" onclick="copyBlock('full-grammar',this)">Copy</button></div>
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

      <!-- ══════════════════════════════════════════════ -->
      <!-- 2.4.2 Running SableCC                         -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="running">
        <h2><span class="sec-icon">▶️</span> 2.4.2 Running SableCC — Three Steps</h2>

        <div style="display:flex;flex-direction:column;gap:12px;margin:20px 0;">
          <div style="background:var(--card);border:1.5px solid rgba(99,102,241,.15);border-radius:var(--radius);padding:16px 20px;box-shadow:var(--shadow);">
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--purple);margin-bottom:8px;">STEP 1 — Generate Java class definitions</div>
            <div class="code-wrap" style="margin:0;"><pre style="padding:12px 16px;"><code>sablecc lexing.grammar</code></pre></div>
            <div style="font-size:13px;color:var(--muted);margin-top:8px;">Produces a subdirectory (e.g. <code>lexing/</code>) containing all generated Java source files.</div>
          </div>
          <div style="background:var(--card);border:1.5px solid rgba(99,102,241,.15);border-radius:var(--radius);padding:16px 20px;box-shadow:var(--shadow);">
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--purple);margin-bottom:8px;">STEP 2 — Compile the generated Java classes</div>
            <div class="code-wrap" style="margin:0;"><pre style="padding:12px 16px;"><code>javac lexing/*.java</code></pre></div>
            <div style="font-size:13px;color:var(--muted);margin-top:8px;">Copy your <code>Lexing.java</code> driver into the <code>lexing/</code> subdirectory first, then compile from the parent directory.</div>
          </div>
          <div style="background:var(--card);border:1.5px solid rgba(16,185,129,.15);border-radius:var(--radius);padding:16px 20px;box-shadow:var(--shadow);">
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--green);margin-bottom:8px;">STEP 3 — Execute the scanner</div>
            <div class="code-wrap" style="margin:0;"><pre style="padding:12px 16px;"><code>java lexing.Lexing</code></pre></div>
            <div style="font-size:13px;color:var(--muted);margin-top:8px;">Reads from standard input (keyboard). Use <code>Ctrl+D</code> (Unix) or <code>Ctrl+Z</code> (Windows) to signal end-of-file.</div>
          </div>
        </div>

        <h3>Sample Session Output</h3>
        <div class="code-wrap">
          <div class="code-header"><div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">Terminal — java lexing.Lexing</span></div>
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
          <div class="code-header"><div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div><span class="code-lang">Java — Lexing.java driver (key part)</span><button class="copy-btn" onclick="copyBlock('lexing-java',this)">Copy</button></div>
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

      <!-- ══════════════════════════════════════════════ -->
      <!-- DRAG AND DROP QUIZ                            -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="dragdrop">
        <h2><span class="sec-icon">🎯</span> Drag &amp; Drop — Match the Concepts</h2>
        <p>Drag each coloured chip from the bank into the correct matching drop zone. Each question has exactly one correct answer per zone.</p>

        <!-- DnD Q1 -->
        <div class="dnd-quiz" id="dnd1">
          <div class="dnd-header" style="background:linear-gradient(135deg,#6366F1,#818CF8);">
            <div class="dnd-q-num" style="background:rgba(255,255,255,.2);color:white;">1</div>
            <div class="dnd-q-text" style="color:white;">Match each SableCC operator to what it means</div>
          </div>
          <div class="dnd-body">
            <p class="dnd-prompt">Drag the operators into the correct description boxes.</p>
            <div class="dnd-bank" id="bank1" ondrop="dropChip(event,'bank1')" ondragover="allowDrop(event)">
              <div class="dnd-chip chip-a" draggable="true" ondragstart="dragStart(event)" id="chip-p-plus">p+</div>
              <div class="dnd-chip chip-b" draggable="true" ondragstart="dragStart(event)" id="chip-p-star">p*</div>
              <div class="dnd-chip chip-c" draggable="true" ondragstart="dragStart(event)" id="chip-p-q">p?</div>
            </div>
            <div class="dnd-zones">
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#10B981;">Zero or one repetition</div>
                <div class="dnd-zone" id="zone1-a" ondrop="dropChip(event,'zone1-a')" ondragover="allowDrop(event)" data-answer="chip-p-q"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#3B82F6;">Zero or more repetitions</div>
                <div class="dnd-zone" id="zone1-b" ondrop="dropChip(event,'zone1-b')" ondragover="allowDrop(event)" data-answer="chip-p-star"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#F59E0B;">One or more repetitions</div>
                <div class="dnd-zone" id="zone1-c" ondrop="dropChip(event,'zone1-c')" ondragover="allowDrop(event)" data-answer="chip-p-plus"></div>
              </div>
            </div>
            <button class="dnd-check" onclick="checkDnD('dnd1',['zone1-a','zone1-b','zone1-c'])">✓ Check</button>
            <button class="dnd-reset" onclick="resetDnD('dnd1','bank1',['zone1-a','zone1-b','zone1-c'])">↺ Reset</button>
            <div class="dnd-feedback" id="fb-dnd1"></div>
          </div>
        </div>

        <!-- DnD Q2 -->
        <div class="dnd-quiz" id="dnd2">
          <div class="dnd-header" style="background:linear-gradient(135deg,#10B981,#34D399);">
            <div class="dnd-q-num" style="background:rgba(255,255,255,.2);color:white;">2</div>
            <div class="dnd-q-text" style="color:white;">Match each SableCC section to its role in the grammar file</div>
          </div>
          <div class="dnd-body">
            <p class="dnd-prompt">Drag the section names into the correct description boxes.</p>
            <div class="dnd-bank" id="bank2" ondrop="dropChip(event,'bank2')" ondragover="allowDrop(event)">
              <div class="dnd-chip chip-a" draggable="true" ondragstart="dragStart(event)" id="chip-helpers">Helpers</div>
              <div class="dnd-chip chip-b" draggable="true" ondragstart="dragStart(event)" id="chip-states">States</div>
              <div class="dnd-chip chip-c" draggable="true" ondragstart="dragStart(event)" id="chip-tokens">Tokens</div>
            </div>
            <div class="dnd-zones">
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#6366F1;">Named scanner modes for context-sensitive scanning</div>
                <div class="dnd-zone" id="zone2-a" ondrop="dropChip(event,'zone2-a')" ondragover="allowDrop(event)" data-answer="chip-states"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#F59E0B;">Reusable sub-definitions used inside token declarations</div>
                <div class="dnd-zone" id="zone2-b" ondrop="dropChip(event,'zone2-b')" ondragover="allowDrop(event)" data-answer="chip-helpers"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#10B981;">The words to be recognised, defined with regex</div>
                <div class="dnd-zone" id="zone2-c" ondrop="dropChip(event,'zone2-c')" ondragover="allowDrop(event)" data-answer="chip-tokens"></div>
              </div>
            </div>
            <button class="dnd-check" onclick="checkDnD('dnd2',['zone2-a','zone2-b','zone2-c'])">✓ Check</button>
            <button class="dnd-reset" onclick="resetDnD('dnd2','bank2',['zone2-a','zone2-b','zone2-c'])">↺ Reset</button>
            <div class="dnd-feedback" id="fb-dnd2"></div>
          </div>
        </div>

        <!-- DnD Q3 -->
        <div class="dnd-quiz" id="dnd3">
          <div class="dnd-header" style="background:linear-gradient(135deg,#F59E0B,#FBBF24);">
            <div class="dnd-q-num" style="background:rgba(255,255,255,.2);color:white;">3</div>
            <div class="dnd-q-text" style="color:white;">Match each token from <code style="background:rgba(255,255,255,.2);padding:1px 6px;border-radius:4px;">sum = sum + salary ;</code> to its class</div>
          </div>
          <div class="dnd-body">
            <p class="dnd-prompt">Using the <code>lexing.grammar</code> definitions, drag each lexeme to its correct token class. Note: <code>=</code> and <code>;</code> are <strong>not</strong> defined — they are Unknown.</p>
            <div class="dnd-bank" id="bank3" ondrop="dropChip(event,'bank3')" ondragover="allowDrop(event)">
              <div class="dnd-chip chip-a" draggable="true" ondragstart="dragStart(event)" id="chip-ident2">Identifier</div>
              <div class="dnd-chip chip-b" draggable="true" ondragstart="dragStart(event)" id="chip-arith2">Arith Op</div>
              <div class="dnd-chip chip-c" draggable="true" ondragstart="dragStart(event)" id="chip-unk2">Unknown</div>
            </div>
            <div class="dnd-zones">
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#3B82F6;"><code>sum</code> → ?</div>
                <div class="dnd-zone" id="zone3-a" ondrop="dropChip(event,'zone3-a')" ondragover="allowDrop(event)" data-answer="chip-ident2"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#9F1239;"><code>+</code> → ?</div>
                <div class="dnd-zone" id="zone3-b" ondrop="dropChip(event,'zone3-b')" ondragover="allowDrop(event)" data-answer="chip-arith2"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#475569;"><code>=</code> → ?</div>
                <div class="dnd-zone" id="zone3-c" ondrop="dropChip(event,'zone3-c')" ondragover="allowDrop(event)" data-answer="chip-unk2"></div>
              </div>
            </div>
            <button class="dnd-check" onclick="checkDnD('dnd3',['zone3-a','zone3-b','zone3-c'])">✓ Check</button>
            <button class="dnd-reset" onclick="resetDnD('dnd3','bank3',['zone3-a','zone3-b','zone3-c'])">↺ Reset</button>
            <div class="dnd-feedback" id="fb-dnd3"></div>
          </div>
        </div>

      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Multiple Choice Quiz                          -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="quiz">
        <h2><span class="sec-icon">🧩</span> Check Your Knowledge</h2>
        <p>Test what you've learned about Section 2.4.</p>

        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span><h4>Question 1</h4></div>
          <div class="quiz-body">
            <p class="quiz-q">If <code>identifier</code> is listed <em>before</em> <code>keyword</code> in the Tokens section, what token does the input <code>while</code> produce?</p>
            <div class="quiz-options" id="q1-opts">
              <div class="quiz-opt" onclick="answer(this,'q1',true)"><div class="opt-circle">A</div> An identifier — it is listed first and both match the same length, so the first definition wins</div>
              <div class="quiz-opt" onclick="answer(this,'q1',false)"><div class="opt-circle">B</div> A keyword — SableCC always prefers keywords over identifiers</div>
              <div class="quiz-opt" onclick="answer(this,'q1',false)"><div class="opt-circle">C</div> An error — the input matches two definitions simultaneously</div>
              <div class="quiz-opt" onclick="answer(this,'q1',false)"><div class="opt-circle">D</div> A longer match would be selected, so it would be split into tokens</div>
            </div>
            <div class="quiz-feedback" id="q1-fb"></div>
          </div>
        </div>

        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span><h4>Question 2</h4></div>
          <div class="quiz-body">
            <p class="quiz-q">What is the key difference between how Helpers work in SableCC vs macros in <code>lex</code>?</p>
            <div class="quiz-options" id="q2-opts">
              <div class="quiz-opt" onclick="answer(this,'q2',false)"><div class="opt-circle">A</div> SableCC helpers can only define single characters, whereas lex macros can define full expressions</div>
              <div class="quiz-opt" onclick="answer(this,'q2',false)"><div class="opt-circle">B</div> There is no difference — helpers and lex macros behave identically</div>
              <div class="quiz-opt" onclick="answer(this,'q2',true)"><div class="opt-circle">C</div> lex uses textual substitution (which can change meaning); SableCC uses semantic substitution (which preserves the intended meaning)</div>
              <div class="quiz-opt" onclick="answer(this,'q2',false)"><div class="opt-circle">D</div> SableCC helpers are only usable in the States section, not the Tokens section</div>
            </div>
            <div class="quiz-feedback" id="q2-fb"></div>
          </div>
        </div>

        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span><h4>Question 3</h4></div>
          <div class="quiz-body">
            <p class="quiz-q">What does the SableCC transition <code>{def -> currency} dollar = '$';</code> mean?</p>
            <div class="quiz-options" id="q3-opts">
              <div class="quiz-opt" onclick="answer(this,'q3',false)"><div class="opt-circle">A</div> Apply this rule in any state and switch to both <code>def</code> and <code>currency</code></div>
              <div class="quiz-opt" onclick="answer(this,'q3',true)"><div class="opt-circle">B</div> When in state <code>def</code> and a <code>$</code> is read, produce a <code>dollar</code> token and transition to the <code>currency</code> state</div>
              <div class="quiz-opt" onclick="answer(this,'q3',false)"><div class="opt-circle">C</div> Switch from the <code>currency</code> state back to <code>def</code> when <code>$</code> is read</div>
              <div class="quiz-opt" onclick="answer(this,'q3',false)"><div class="opt-circle">D</div> Define a currency token that accepts dollar signs only in the <code>currency</code> state</div>
            </div>
            <div class="quiz-feedback" id="q3-fb"></div>
          </div>
        </div>

        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span><h4>Question 4</h4></div>
          <div class="quiz-body">
            <p class="quiz-q">In the <code>lexing.grammar</code> example, what token does the input <code>whilex</code> produce when <code>keyword</code> is listed <em>before</em> <code>identifier</code>?</p>
            <div class="quiz-options" id="q4-opts">
              <div class="quiz-opt" onclick="answer(this,'q4',false)"><div class="opt-circle">A</div> A keyword — <code>while</code> is listed first</div>
              <div class="quiz-opt" onclick="answer(this,'q4',true)"><div class="opt-circle">B</div> An identifier — <code>whilex</code> (6 chars) is a longer match than <code>while</code> (5 chars), so the longer match wins</div>
              <div class="quiz-opt" onclick="answer(this,'q4',false)"><div class="opt-circle">C</div> Two tokens: <code>while</code> (keyword) + <code>x</code> (identifier)</div>
              <div class="quiz-opt" onclick="answer(this,'q4',false)"><div class="opt-circle">D</div> An unknown token — no definition covers a 6-character string</div>
            </div>
            <div class="quiz-feedback" id="q4-fb"></div>
          </div>
        </div>

        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span><h4>Question 5</h4></div>
          <div class="quiz-body">
            <p class="quiz-q">What is the correct three-step order to build and run a SableCC scanner?</p>
            <div class="quiz-options" id="q5-opts">
              <div class="quiz-opt" onclick="answer(this,'q5',false)"><div class="opt-circle">A</div> javac → sablecc → java</div>
              <div class="quiz-opt" onclick="answer(this,'q5',true)"><div class="opt-circle">B</div> sablecc (generate classes) → javac (compile) → java (execute)</div>
              <div class="quiz-opt" onclick="answer(this,'q5',false)"><div class="opt-circle">C</div> java → sablecc → javac</div>
              <div class="quiz-opt" onclick="answer(this,'q5',false)"><div class="opt-circle">D</div> sablecc → java (no compilation needed — SableCC outputs bytecode directly)</div>
            </div>
            <div class="quiz-feedback" id="q5-fb"></div>
          </div>
        </div>

      </section>

      <div class="chapter-nav">
        <a href="c2_3.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Previous section</span>2.3 Lexical Tables</div>
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
      tocLinks.forEach(l => { l.classList.toggle('active', l.getAttribute('href') === '#' + current); });
    });

    function copyBlock(id, btn) {
      navigator.clipboard.writeText(document.getElementById(id).innerText).then(() => {
        btn.textContent = 'Copied!';
        setTimeout(() => btn.textContent = 'Copy', 1800);
      });
    }

    // ════════════════════════════════════════════════════════
    // DRAG AND DROP ENGINE
    // ════════════════════════════════════════════════════════
    let draggingId = null;
    let draggingFrom = null;

    function dragStart(e) {
      draggingId   = e.target.id;
      draggingFrom = e.target.parentElement.id;
      e.target.classList.add('dragging');
      e.dataTransfer.effectAllowed = 'move';
      e.dataTransfer.setData('text/plain', e.target.id);
    }

    function allowDrop(e) {
      e.preventDefault();
      e.currentTarget.classList.add('drag-over');
    }

    document.addEventListener('dragleave', (e) => {
      if (e.target.classList) {
        e.target.classList.remove('drag-over');
      }
    });

    document.addEventListener('dragend', () => {
      document.querySelectorAll('.dnd-chip').forEach(c => c.classList.remove('dragging'));
      document.querySelectorAll('.dnd-zone,.dnd-bank').forEach(z => z.classList.remove('drag-over'));
    });

    function dropChip(e, targetId) {
      e.preventDefault();
      e.currentTarget.classList.remove('drag-over');
      const chipId = e.dataTransfer.getData('text/plain');
      const chip   = document.getElementById(chipId);
      const target = document.getElementById(targetId);
      if (!chip || !target) return;

      // If target is a zone and already has a chip, send existing chip back to its bank
      const targetIsZone = target.classList.contains('dnd-zone');
      if (targetIsZone && target.children.length > 0) {
        const existing = target.firstElementChild;
        const bank = findBank(targetId);
        if (bank) document.getElementById(bank).appendChild(existing);
      }

      // If target is a zone and chip is coming from another zone, clear source zone
      if (targetIsZone && draggingFrom !== target.id && !draggingFrom.startsWith('bank')) {
        // source is a zone — already removed by drag
      }

      target.appendChild(chip);
      chip.classList.remove('dragging');

      // If going to zone, make chip clickable to return to bank
      if (targetIsZone) {
        chip.onclick = () => {
          const bank = findBank(targetId);
          if (bank) { document.getElementById(bank).appendChild(chip); chip.onclick = null; }
        };
      } else {
        chip.onclick = null;
      }
    }

    function findBank(zoneId) {
      // zone id format: zone{N}-{x} → bank is bank{N}
      const m = zoneId.match(/zone(\d+)/);
      return m ? 'bank' + m[1] : null;
    }

    function checkDnD(quizId, zoneIds) {
      let correct = 0;
      const total = zoneIds.length;
      zoneIds.forEach(zid => {
        const zone = document.getElementById(zid);
        const expected = zone.dataset.answer;
        const chip = zone.firstElementChild;
        zone.classList.remove('correct-zone','wrong-zone');
        if (chip && chip.id === expected) { zone.classList.add('correct-zone'); correct++; }
        else { zone.classList.add('wrong-zone'); }
      });
      const fb = document.getElementById('fb-' + quizId);
      fb.classList.remove('show','ok','bad','partial');
      if (correct === total) {
        fb.className = 'dnd-feedback show ok';
        fb.innerHTML = '✅ <strong>Perfect!</strong> All ' + total + ' matched correctly.';
      } else if (correct > 0) {
        fb.className = 'dnd-feedback show partial';
        fb.innerHTML = '⚠️ <strong>' + correct + ' of ' + total + '</strong> correct. Green = right, red = wrong. Try again!';
      } else {
        fb.className = 'dnd-feedback show bad';
        fb.innerHTML = '❌ None correct yet. Review the section and try again.';
      }
    }

    function resetDnD(quizId, bankId, zoneIds) {
      const bank = document.getElementById(bankId);
      zoneIds.forEach(zid => {
        const zone = document.getElementById(zid);
        zone.classList.remove('correct-zone','wrong-zone');
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
      fb.innerHTML = correct
        ? '✅ <strong>Correct!</strong> Well done.'
        : '❌ <strong>Not quite.</strong> The correct answer is highlighted above.';
    }
  </script>

</body>
</html>