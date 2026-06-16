<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chapter 2.3 – CompileX</title>

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

    /* TOC */
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

    /* Compare table */
    .compare-table { width: 100%; border-collapse: collapse; margin: 16px 0; font-size: 14px; }
    .compare-table th { background: var(--purple-s); color: var(--purple); font-family: 'Syne', sans-serif; font-weight: 700; padding: 10px 14px; text-align: left; }
    .compare-table td { padding: 9px 14px; border-bottom: 1px solid #EEF2FF; color: var(--muted); vertical-align: top; }
    .compare-table tr:hover td { background: #FAFAFF; }
    .compare-table td:first-child { font-weight: 600; color: var(--text); }

    /* Method summary cards */
    .method-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; margin: 20px 0; }
    .method-card { background: var(--card); border: 1.5px solid rgba(99,102,241,.15); border-radius: var(--radius); padding: 18px; box-shadow: var(--shadow); }
    .method-card .mc-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 14px; color: var(--text); margin-bottom: 6px; }
    .method-card .mc-complexity { font-family: 'JetBrains Mono', monospace; font-size: 13px; font-weight: 700; padding: 3px 10px; border-radius: 8px; display: inline-block; margin-bottom: 10px; }
    .method-card .mc-desc { font-size: 13px; color: var(--muted); line-height: 1.55; }
    .mc-bad  .mc-complexity { background: #FFF1F2; color: #9F1239; }
    .mc-mid  .mc-complexity { background: #FFFBEB; color: #92400E; }
    .mc-good .mc-complexity { background: #F0FDF4; color: #065F46; }

    /* Interactive BST visualiser */
    .vis-wrap { background: var(--card); border: 1px solid rgba(99,102,241,.15); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); margin: 24px 0; }
    .vis-header { background: linear-gradient(135deg,#6366F1,#818CF8); padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; }
    .vis-header h4 { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 15px; color: white; margin: 0; }
    .vis-body { padding: 20px; }
    .vis-controls { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 16px; align-items: center; }
    .vis-input { font-family: 'JetBrains Mono', monospace; font-size: 14px; padding: 8px 14px; border: 1.5px solid rgba(99,102,241,.3); border-radius: 8px; outline: none; width: 180px; color: var(--text); background: var(--bg); }
    .vis-input:focus { border-color: var(--purple); }
    .vis-btn { padding: 8px 16px; border-radius: 8px; border: none; background: var(--purple); color: white; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 13px; cursor: pointer; transition: background .18s; }
    .vis-btn:hover { background: var(--purple-d); }
    .vis-btn.sec { background: transparent; border: 1.5px solid rgba(99,102,241,.3); color: var(--purple); }
    .vis-btn.sec:hover { background: var(--purple-s); }
    .vis-canvas { width: 100%; min-height: 220px; position: relative; overflow-x: auto; }
    .vis-svg { display: block; }
    .vis-log { margin-top: 12px; font-family: 'JetBrains Mono', monospace; font-size: 12px; color: var(--muted); background: var(--code-bg); padding: 10px 14px; border-radius: 8px; min-height: 28px; }
    .preset-btns { display: flex; gap: 6px; flex-wrap: wrap; }
    .preset-btn { font-size: 11px; padding: 4px 10px; border-radius: 6px; border: 1px solid rgba(99,102,241,.3); background: transparent; color: var(--purple); cursor: pointer; font-family: 'DM Sans', sans-serif; }
    .preset-btn:hover { background: var(--purple-s); }

    /* Hash table visualiser */
    .hash-wrap { background: var(--card); border: 1px solid rgba(99,102,241,.15); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); margin: 24px 0; }
    .hash-header { background: linear-gradient(135deg,#10B981,#34D399); padding: 14px 20px; display: flex; justify-content: space-between; align-items: center; }
    .hash-header h4 { font-family: 'Syne', sans-serif; font-weight: 700; font-size: 15px; color: white; margin: 0; }
    .hash-body { padding: 20px; }
    .hash-controls { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 16px; align-items: center; }
    .hash-input { font-family: 'JetBrains Mono', monospace; font-size: 14px; padding: 8px 14px; border: 1.5px solid rgba(16,185,129,.3); border-radius: 8px; outline: none; width: 180px; color: var(--text); background: var(--bg); }
    .hash-input:focus { border-color: var(--green); }
    .hash-btn { padding: 8px 16px; border-radius: 8px; border: none; background: var(--green); color: white; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 13px; cursor: pointer; transition: background .18s; }
    .hash-btn:hover { background: #059669; }
    .hash-btn.sec { background: transparent; border: 1.5px solid rgba(16,185,129,.3); color: var(--green); }
    .hash-btn.sec:hover { background: #F0FDF4; }
    .hash-table-vis { display: flex; gap: 4px; flex-direction: column; }
    .hash-slot { display: flex; align-items: center; gap: 10px; }
    .hash-idx { width: 32px; height: 32px; background: var(--code-bg); color: #A5B4FC; font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; border-radius: 6px; flex-shrink: 0; }
    .hash-chain { display: flex; align-items: center; gap: 4px; flex-wrap: wrap; }
    .hash-node { background: var(--purple-s); border: 1.5px solid rgba(99,102,241,.3); color: var(--purple); font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 8px; display: flex; align-items: center; gap: 6px; transition: all .3s; }
    .hash-node.new { background: #FEF3C7; border-color: var(--amber); color: #92400E; animation: pop .35s ease; }
    .hash-null { color: rgba(99,102,241,.3); font-family: 'JetBrains Mono', monospace; font-size: 12px; }
    .hash-arrow { color: rgba(99,102,241,.4); font-size: 14px; }
    .hash-formula { background: var(--code-bg); border-radius: 8px; padding: 10px 16px; font-family: 'JetBrains Mono', monospace; font-size: 13px; color: #CDD6F4; margin-top: 12px; }
    .hash-formula .hf-hi { color: #FAB387; }
    .hash-log { margin-top: 12px; font-family: 'JetBrains Mono', monospace; font-size: 12px; color: var(--muted); background: var(--code-bg); padding: 10px 14px; border-radius: 8px; min-height: 28px; }
    @keyframes pop { 0%{transform:scale(.7);opacity:.4;} 60%{transform:scale(1.1);} 100%{transform:scale(1);opacity:1;} }

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
    .quiz-opt.wrong   { border-color: var(--red); background: #FFF1F2; color: #9F1239; }
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
    section:nth-child(7){animation-delay:.35s;}

    @media(max-width:768px){
      .note-container{grid-template-columns:1fr;}.toc{position:static;}
      .method-grid{grid-template-columns:1fr;}
    }
  </style>
</head>

<body>

  <?php include '../dashboard.php'; ?>

  <div class="note-container">

    <nav class="toc">
      <div class="toc-title">📖 Contents</div>
      <ul>
        <li><a href="#overview"  class="toc-link">2.3 Overview</a></li>
        <li><a href="#summary"   class="toc-link">Method Comparison</a></li>
        <li><a href="#seq"       class="toc-link">2.3.1 Sequential Search</a></li>
        <li><a href="#bst"       class="toc-link">2.3.2 Binary Search Tree</a></li>
        <li><a href="#bst-vis"   class="toc-link sub">↳ Interactive BST Builder</a></li>
        <li><a href="#hash"      class="toc-link">2.3.3 Hash Table</a></li>
        <li><a href="#hash-vis"  class="toc-link sub">↳ Interactive Hash Table</a></li>
        <li><a href="#quiz"      class="toc-link">Check Your Knowledge</a></li>
      </ul>
      <div class="toc-progress">
        <div class="toc-progress-label"><span>Progress</span><span id="pct-label">0%</span></div>
        <div class="toc-bar"><div class="toc-bar-fill" id="toc-bar"></div></div>
      </div>
    </nav>

    <article class="content">

      <header class="content-header">
        <div class="chapter-tag">📘 Chapter 2 · Section 2.3</div>
        <h1>Lexical Tables</h1>
        <div class="metadata">
          <span>⏱ 16 min read</span>
          <span>🎯 Intermediate</span>
          <span>🗃️ Data Structures</span>
        </div>
      </header>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 2.3 Overview                                   -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="overview">
        <h2><span class="sec-icon">🗃️</span> 2.3 Overview</h2>
        <p>
          One of the most important functions of lexical analysis is the <strong>creation of tables</strong>
          used later in the compiler. Such tables include:
        </p>
        <ul>
          <li>A <strong>symbol table</strong> for identifiers</li>
          <li>A <strong>table of numeric constants</strong></li>
          <li>A <strong>table of string constants</strong></li>
          <li><strong>Statement labels</strong> and <strong>line numbers</strong> (for languages like BASIC)</li>
        </ul>
        <p>
          The three implementation techniques discussed below can apply to any of these tables.
          The key requirement: each word should appear in the table <strong>exactly once</strong>,
          regardless of how many times it appears in the source program.
        </p>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Method Comparison Summary                     -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="summary">
        <h2><span class="sec-icon">⚖️</span> Method Comparison</h2>

        <div class="method-grid">
          <div class="method-card mc-bad">
            <div class="mc-title">Sequential Search</div>
            <div class="mc-complexity">O(n²)</div>
            <div class="mc-desc">Array or linked list. Scan from start each time. Simple to implement, but slow for large tables. Generally not used for symbol tables.</div>
          </div>
          <div class="method-card mc-mid">
            <div class="mc-title">Binary Search Tree</div>
            <div class="mc-complexity">O(n log n) best · O(n²) worst</div>
            <div class="mc-desc">Fast when balanced; degrades to linear if words are inserted in alphabetical order. Structure depends on insertion order.</div>
          </div>
          <div class="method-card mc-good">
            <div class="mc-title">Hash Table</div>
            <div class="mc-complexity">O(n) average</div>
            <div class="mc-desc">Array of linked lists. A hash function maps each word to a bucket. Structure does not depend on insertion order. Best choice for large symbol tables.</div>
          </div>
        </div>

        <table class="compare-table">
          <thead><tr><th>Technique</th><th>Structure</th><th>Build time</th><th>Typical use</th></tr></thead>
          <tbody>
            <tr><td>Sequential Search</td><td>Array or linked list</td><td>O(n²)</td><td>Statement labels, small constant tables</td></tr>
            <tr><td>Binary Search Tree</td><td>Binary tree (BST)</td><td>O(n log n) balanced / O(n²) degenerate</td><td>Symbol tables (when order matters)</td></tr>
            <tr><td>Hash Table</td><td>Array of linked lists</td><td>O(n) average</td><td>Symbol tables, constant tables, line numbers</td></tr>
          </tbody>
        </table>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 2.3.1 Sequential Search                       -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="seq">
        <h2><span class="sec-icon">📋</span> 2.3.1 Sequential Search</h2>
        <p>
          The table is organised as an <strong>array</strong> or <strong>linked list</strong>.
          Each time a new word is encountered, the list is scanned from the beginning. If the word
          is not already in the list, it is <strong>added at the end</strong>.
        </p>
        <p>
          To insert the <em>k</em>-th word, the scanner must compare it against all <em>k−1</em>
          previous entries. Summing over all n words: 0 + 1 + 2 + … + (n−1) = n(n−1)/2 comparisons,
          giving <strong>O(n²)</strong> total build time.
        </p>

        <div class="note-box warn">
          <span class="box-icon">⚠️</span>
          <div>
            <strong>When to use sequential search:</strong> It is easy to implement but not efficient as the
            number of words grows. It is generally <em>not</em> used for symbol tables or line number tables.
            It <em>is</em> acceptable for small tables such as statement labels or constants.
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 2.3.2 Binary Search Tree                      -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="bst">
        <h2><span class="sec-icon">🌳</span> 2.3.2 Binary Search Tree</h2>
        <p>
          The table is organised as a <strong>binary search tree (BST)</strong>: for every node, all
          words in its <em>left subtree</em> alphabetically precede it, and all words in its
          <em>right subtree</em> follow it.
        </p>

        <h3>Insertion Algorithm</h3>
        <ol>
          <li>The first word encountered becomes the <strong>root</strong></li>
          <li>For each subsequent word <em>w</em>, start at the root and compare:
            <ul>
              <li>If <em>w</em> &lt; current node → go <strong>left</strong></li>
              <li>If <em>w</em> &gt; current node → go <strong>right</strong></li>
              <li>If <em>w</em> = current node → <strong>already in table</strong>, stop</li>
            </ul>
          </li>
          <li>When a null child is reached, <strong>insert w there</strong></li>
        </ol>

        <div class="note-box warn">
          <span class="box-icon">⚠️</span>
          <div>
            <strong>Balance depends on insertion order.</strong> The same set of words can produce very
            different trees depending on the order they are inserted. Inserting in alphabetical order
            produces a degenerate tree (effectively a linked list), degrading performance to O(n²).
          </div>
        </div>

        <h3>Figure 2.8 — Two BSTs for the Same Words</h3>
        <p>
          Words: <code>frog, tree, hill, bird, bat, cat</code> inserted in that order (a) produce a
          <strong>balanced</strong> tree. The same words in alphabetical order (b) produce a
          <strong>degenerate</strong> (linked-list) tree.
        </p>

        <!-- Static Figure 2.8 SVG -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin:20px 0;">
          <!-- (a) Balanced -->
          <div style="background:var(--card);border:1px solid rgba(99,102,241,.15);border-radius:var(--radius);padding:16px;box-shadow:var(--shadow);">
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--purple);margin-bottom:10px;text-align:center;">(a) frog, tree, hill, bird, bat, cat — BALANCED</div>
            <svg width="100%" viewBox="0 0 260 200" style="display:block;">
              <defs><marker id="ba" markerWidth="6" markerHeight="6" refX="3" refY="3" orient="auto"><circle cx="3" cy="3" r="2.5" fill="#6366F1" opacity=".5"/></marker></defs>
              <!-- edges -->
              <line x1="130" y1="36" x2="70"  y2="84"  stroke="#6366F1" stroke-width="1.5" opacity=".4"/>
              <line x1="130" y1="36" x2="195" y2="84"  stroke="#6366F1" stroke-width="1.5" opacity=".4"/>
              <line x1="70"  y1="100" x2="35" y2="148" stroke="#6366F1" stroke-width="1.5" opacity=".4"/>
              <line x1="70"  y1="100" x2="108" y2="148" stroke="#6366F1" stroke-width="1.5" opacity=".4"/>
              <line x1="195" y1="100" x2="158" y2="148" stroke="#6366F1" stroke-width="1.5" opacity=".4"/>
              <!-- nodes -->
              <circle cx="130" cy="24"  r="20" fill="rgba(99,102,241,.12)" stroke="#6366F1" stroke-width="1.8"/>
              <text x="130" y="29"  text-anchor="middle" fill="#818CF8" font-size="11" font-weight="700" font-family="JetBrains Mono">frog</text>
              <circle cx="70"  cy="88"  r="20" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.5"/>
              <text x="70"  y="93"  text-anchor="middle" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">bird</text>
              <circle cx="195" cy="88"  r="20" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.5"/>
              <text x="195" y="93"  text-anchor="middle" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">tree</text>
              <circle cx="35"  cy="156" r="18" fill="rgba(16,185,129,.08)" stroke="#10B981" stroke-width="1.5"/>
              <text x="35"  y="161" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">bat</text>
              <circle cx="108" cy="156" r="18" fill="rgba(16,185,129,.08)" stroke="#10B981" stroke-width="1.5"/>
              <text x="108" y="161" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">cat</text>
              <circle cx="158" cy="156" r="18" fill="rgba(16,185,129,.08)" stroke="#10B981" stroke-width="1.5"/>
              <text x="158" y="161" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">hill</text>
              <text x="130" y="196" text-anchor="middle" fill="#10B981" font-size="10" font-family="DM Sans">✓ Balanced — O(n log n)</text>
            </svg>
          </div>
          <!-- (b) Degenerate -->
          <div style="background:var(--card);border:1px solid rgba(239,68,68,.2);border-radius:var(--radius);padding:16px;box-shadow:var(--shadow);">
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--red);margin-bottom:10px;text-align:center;">(b) bat, bird, cat, frog, hill, tree — DEGENERATE</div>
            <svg width="100%" viewBox="0 0 260 210" style="display:block;">
              <line x1="50"  y1="36"  x2="90"  y2="60"  stroke="#EF4444" stroke-width="1.5" opacity=".4"/>
              <line x1="110" y1="74"  x2="140" y2="98"  stroke="#EF4444" stroke-width="1.5" opacity=".4"/>
              <line x1="160" y1="112" x2="190" y2="136" stroke="#EF4444" stroke-width="1.5" opacity=".4"/>
              <line x1="210" y1="150" x2="230" y2="174" stroke="#EF4444" stroke-width="1.5" opacity=".4"/>
              <circle cx="50"  cy="24"  r="18" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.5"/>
              <text x="50"  y="29"  text-anchor="middle" fill="#F87171" font-size="11" font-family="JetBrains Mono">bat</text>
              <circle cx="110" cy="62"  r="18" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.2"/>
              <text x="110" y="67"  text-anchor="middle" fill="#F87171" font-size="11" font-family="JetBrains Mono">bird</text>
              <circle cx="160" cy="100" r="18" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.2"/>
              <text x="160" y="105" text-anchor="middle" fill="#F87171" font-size="11" font-family="JetBrains Mono">cat</text>
              <circle cx="205" cy="138" r="18" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.2"/>
              <text x="205" y="143" text-anchor="middle" fill="#F87171" font-size="11" font-family="JetBrains Mono">frog</text>
              <circle cx="235" cy="174" r="16" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.2"/>
              <text x="235" y="179" text-anchor="middle" fill="#F87171" font-size="10" font-family="JetBrains Mono">hill</text>
              <text x="130" y="202" text-anchor="middle" fill="#EF4444" font-size="10" font-family="DM Sans">✗ Degenerate — O(n²) worst case</text>
            </svg>
          </div>
        </div>

        <div class="note-box tip">
          <span class="box-icon">💡</span>
          <div>
            <strong>Search complexity:</strong> In a <em>balanced</em> BST of n nodes, finding any word
            requires at most <strong>log₂(n) + 1</strong> comparisons. For 2047 = 2¹¹−1 balanced nodes,
            that is at most 12 comparisons. In the worst case (alphabetical insertion), it takes up to n comparisons.
          </div>
        </div>
      </section>

      <!-- Interactive BST -->
      <section id="bst-vis">
        <h2><span class="sec-icon">▶️</span> Interactive BST Builder</h2>
        <p>Type a word and click <strong>Insert</strong> to add it to the BST. Watch how the tree grows
        and note how insertion order affects its shape. Use the preset examples from the textbook.</p>

        <div class="vis-wrap">
          <div class="vis-header">
            <h4>🌳 Binary Search Tree Visualiser</h4>
            <span style="font-size:12px;color:rgba(255,255,255,.7);" id="bst-stats">0 nodes</span>
          </div>
          <div class="vis-body">
            <div class="vis-controls">
              <input class="vis-input" id="bst-input" type="text" placeholder="Enter word…" maxlength="12" onkeydown="if(event.key==='Enter')bstInsert()">
              <button class="vis-btn" onclick="bstInsert()">Insert</button>
              <button class="vis-btn sec" onclick="bstReset()">Clear</button>
            </div>
            <div class="preset-btns" style="margin-bottom:14px;">
              <span style="font-size:12px;color:var(--muted);margin-right:4px;">Presets:</span>
              <button class="preset-btn" onclick="bstPreset(['frog','tree','hill','bird','bat','cat'])">Fig 2.8(a) — balanced</button>
              <button class="preset-btn" onclick="bstPreset(['bat','bird','cat','frog','hill','tree'])">Fig 2.8(b) — degenerate</button>
              <button class="preset-btn" onclick="bstPreset(['sum','x3','count','x210','x','x33'])">Ex 2.3.1(c)</button>
            </div>
            <div class="vis-canvas">
              <svg id="bst-svg" class="vis-svg" width="700" height="240"></svg>
            </div>
            <div class="vis-log" id="bst-log">Insert a word to start building the tree.</div>
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 2.3.3 Hash Table                              -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="hash">
        <h2><span class="sec-icon">🔑</span> 2.3.3 Hash Table</h2>
        <p>
          A hash table can implement a symbol table, constant table, line number table, etc.
          The method used here is an <strong>array of linked lists</strong>:
        </p>
        <ol>
          <li>Start with an array of <code>HASHMAX</code> null pointers — each will become the head of a linked list</li>
          <li>To store a word, apply the <strong>hash function</strong> to get an array index</li>
          <li>Search that list sequentially — if the word is already there, stop; otherwise <strong>append it</strong></li>
        </ol>

        <h3>The Hash Function</h3>
        <p>
          A good hash function uses some arithmetic combination of the word's characters, divided by the
          table size, taking the <strong>remainder</strong>. The textbook's example:
        </p>
        <div style="background:var(--code-bg);border-radius:var(--radius);padding:16px 22px;margin:16px 0;font-family:'JetBrains Mono',monospace;font-size:14px;color:#CDD6F4;">
          hash(word) = (length(word) + ascii(word[0])) % HASHMAX
        </div>

        <table class="compare-table">
          <thead><tr><th>Word</th><th>len</th><th>ascii(first)</th><th>len + ascii</th><th>% 6</th><th>Bucket</th></tr></thead>
          <tbody>
            <tr><td><code>frog</code></td><td>4</td><td>102 ('f')</td><td>106</td><td>106 % 6</td><td><strong>4</strong></td></tr>
            <tr><td><code>tree</code></td><td>4</td><td>116 ('t')</td><td>120</td><td>120 % 6</td><td><strong>0</strong></td></tr>
            <tr><td><code>hill</code></td><td>4</td><td>104 ('h')</td><td>108</td><td>108 % 6</td><td><strong>0</strong></td></tr>
            <tr><td><code>bird</code></td><td>4</td><td>98 ('b')</td><td>102</td><td>102 % 6</td><td><strong>0</strong></td></tr>
            <tr><td><code>bat</code></td><td>3</td><td>98 ('b')</td><td>101</td><td>101 % 6</td><td><strong>5</strong></td></tr>
            <tr><td><code>cat</code></td><td>3</td><td>99 ('c')</td><td>102</td><td>102 % 6</td><td><strong>0</strong></td></tr>
          </tbody>
        </table>

        <div class="note-box pro">
          <span class="box-icon">🧩</span>
          <div>
            <strong>Structure is insertion-order independent.</strong> Unlike a BST, the hash table's
            bucket structure does not change based on the order words were inserted — only the sequence
            within a particular chain can vary. This is a major advantage over BSTs.
          </div>
        </div>

        <div class="note-box tip">
          <span class="box-icon">💡</span>
          <div>
            <strong>Choosing a good hash function</strong> is critical. A poor function may put many
            words in the same bucket (many collisions), degrading to sequential search within that bucket.
            The goal is to distribute words as <em>evenly as possible</em> across all buckets.
          </div>
        </div>
      </section>

      <!-- Interactive Hash Table -->
      <section id="hash-vis">
        <h2><span class="sec-icon">▶️</span> Interactive Hash Table Visualiser</h2>
        <p>Type a word and click <strong>Insert</strong> to add it. The hash function
        <code>(len + ascii(first)) % HASHMAX</code> is applied live, and the word is placed in
        the correct bucket. Use the preset to reproduce Figure 2.9 from the textbook.</p>

        <div class="hash-wrap">
          <div class="hash-header">
            <h4>🔑 Hash Table Visualiser</h4>
            <span style="font-size:12px;color:rgba(255,255,255,.7);" id="hash-stats">HASHMAX = 6 | 0 words</span>
          </div>
          <div class="hash-body">
            <div class="hash-controls">
              <input class="hash-input" id="hash-word-input" type="text" placeholder="Enter word…" maxlength="14" onkeydown="if(event.key==='Enter')hashInsert()">
              <input class="hash-input" id="hash-max-input" type="number" value="6" min="2" max="20" style="width:80px;" onchange="hashResize()">
              <label style="font-size:13px;color:var(--muted);">HASHMAX</label>
              <button class="hash-btn" onclick="hashInsert()">Insert</button>
              <button class="hash-btn sec" onclick="hashReset()">Clear</button>
            </div>
            <div class="preset-btns" style="margin-bottom:14px;">
              <span style="font-size:12px;color:var(--muted);margin-right:4px;">Presets:</span>
              <button class="preset-btn" onclick="hashPreset(['frog','tree','hill','bird','bat','cat'],6)">Fig 2.9 (HASHMAX=6)</button>
              <button class="preset-btn" onclick="hashPreset(['bog','cab','bc','cb','h33','h22','cater'],6)">Ex 2.3.5</button>
            </div>

            <div class="hash-table-vis" id="hash-table-vis"></div>

            <div class="hash-formula" id="hash-formula-display">
              hash(<span style="color:#A6E3A1;">word</span>) = (len + ascii(first)) % HASHMAX
            </div>
            <div class="hash-log" id="hash-log">Insert a word to see the hash function in action.</div>
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Quiz                                          -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="quiz">
        <h2><span class="sec-icon">🧩</span> Check Your Knowledge</h2>
        <p>Test what you've learned about Section 2.3. Select the best answer for each question.</p>

        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span><h4>Question 1</h4></div>
          <div class="quiz-body">
            <p class="quiz-q">What is the time complexity to build a symbol table of n words using <em>sequential search</em>?</p>
            <div class="quiz-options" id="q1-opts">
              <div class="quiz-opt" onclick="answer(this,'q1',false)"><div class="opt-circle">A</div> O(n)</div>
              <div class="quiz-opt" onclick="answer(this,'q1',false)"><div class="opt-circle">B</div> O(n log n)</div>
              <div class="quiz-opt" onclick="answer(this,'q1',true)"><div class="opt-circle">C</div> O(n²) — each new word must be compared against all previous entries</div>
              <div class="quiz-opt" onclick="answer(this,'q1',false)"><div class="opt-circle">D</div> O(log n)</div>
            </div>
            <div class="quiz-feedback" id="q1-fb"></div>
          </div>
        </div>

        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span><h4>Question 2</h4></div>
          <div class="quiz-body">
            <p class="quiz-q">A BST of 2047 perfectly balanced nodes needs at most how many comparisons to find a word?</p>
            <div class="quiz-options" id="q2-opts">
              <div class="quiz-opt" onclick="answer(this,'q2',false)"><div class="opt-circle">A</div> 2047 comparisons</div>
              <div class="quiz-opt" onclick="answer(this,'q2',false)"><div class="opt-circle">B</div> 1024 comparisons</div>
              <div class="quiz-opt" onclick="answer(this,'q2',true)"><div class="opt-circle">C</div> 12 comparisons — log₂(2047) + 1 ≈ 11 + 1</div>
              <div class="quiz-opt" onclick="answer(this,'q2',false)"><div class="opt-circle">D</div> 22 comparisons</div>
            </div>
            <div class="quiz-feedback" id="q2-fb"></div>
          </div>
        </div>

        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span><h4>Question 3</h4></div>
          <div class="quiz-body">
            <p class="quiz-q">Using the textbook's hash function <code>(len + ascii(first)) % 6</code>, which bucket does <code>bat</code> go into?</p>
            <div class="quiz-options" id="q3-opts">
              <div class="quiz-opt" onclick="answer(this,'q3',false)"><div class="opt-circle">A</div> Bucket 0 — (3 + 98) % 6 = 101 % 6 = 0</div>
              <div class="quiz-opt" onclick="answer(this,'q3',false)"><div class="opt-circle">B</div> Bucket 4 — (3 + 98) % 6 = 101 % 6 = 4</div>
              <div class="quiz-opt" onclick="answer(this,'q3',true)"><div class="opt-circle">C</div> Bucket 5 — (3 + 98) % 6 = 101 % 6 = 5</div>
              <div class="quiz-opt" onclick="answer(this,'q3',false)"><div class="opt-circle">D</div> Bucket 2 — (3 + 98) % 6 = 101 % 6 = 2</div>
            </div>
            <div class="quiz-feedback" id="q3-fb"></div>
          </div>
        </div>

        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span><h4>Question 4</h4></div>
          <div class="quiz-body">
            <p class="quiz-q">What is the key advantage of using a <em>hash table implemented as an array of linked lists</em> (as described in the textbook) compared to a BST?</p>
            <div class="quiz-options" id="q4-opts">
              <div class="quiz-opt" onclick="answer(this,'q4',false)"><div class="opt-circle">A</div> It always uses less memory than a BST</div>
              <div class="quiz-opt" onclick="answer(this,'q4',true)"><div class="opt-circle">B</div> The structure does not depend on the insertion order of words — it is consistent regardless of the sequence encountered</div>
              <div class="quiz-opt" onclick="answer(this,'q4',false)"><div class="opt-circle">C</div> It allows words to be stored in alphabetical order automatically</div>
              <div class="quiz-opt" onclick="answer(this,'q4',false)"><div class="opt-circle">D</div> It is easier to implement than sequential search</div>
            </div>
            <div class="quiz-feedback" id="q4-fb"></div>
          </div>
        </div>

        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span><h4>Question 5</h4></div>
          <div class="quiz-body">
            <p class="quiz-q">If words are inserted into a BST in <em>alphabetical order</em>, what shape does the tree take and what is the worst-case build time?</p>
            <div class="quiz-options" id="q5-opts">
              <div class="quiz-opt" onclick="answer(this,'q5',false)"><div class="opt-circle">A</div> A perfectly balanced tree with O(n log n) build time</div>
              <div class="quiz-opt" onclick="answer(this,'q5',true)"><div class="opt-circle">B</div> A degenerate tree resembling a linked list, with O(n²) build time</div>
              <div class="quiz-opt" onclick="answer(this,'q5',false)"><div class="opt-circle">C</div> A complete binary tree with O(n) build time</div>
              <div class="quiz-opt" onclick="answer(this,'q5',false)"><div class="opt-circle">D</div> A hash table with O(1) average lookup time</div>
            </div>
            <div class="quiz-feedback" id="q5-fb"></div>
          </div>
        </div>

      </section>

      <div class="chapter-nav">
        <a href="ch2_s2.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Previous section</span>2.2 Implementation with FSMs</div>
        </a>
        <a href="ch2_s4.php" class="nav-btn next">
          <div style="text-align:right"><span class="btn-sub">Next section</span>2.4 Lex &amp; JavaCC</div>
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

    // ════════════════════════════════════════════════════════
    // BST Visualiser
    // ════════════════════════════════════════════════════════
    let bstRoot = null;
    let bstCount = 0;

    class BSTNode {
      constructor(val) { this.val = val; this.left = null; this.right = null; }
    }

    function bstInsert() {
      const w = document.getElementById('bst-input').value.trim().toLowerCase();
      if (!w) return;
      document.getElementById('bst-input').value = '';
      const log = document.getElementById('bst-log');
      const result = bstInsertNode(w);
      log.textContent = result.msg;
      bstCount = bstCountNodes(bstRoot);
      document.getElementById('bst-stats').textContent = bstCount + ' node' + (bstCount===1?'':'s');
      bstDraw();
    }

    function bstInsertNode(w) {
      if (!bstRoot) { bstRoot = new BSTNode(w); return {msg:`"${w}" inserted as root.`}; }
      let cur = bstRoot, path = [];
      while (cur) {
        path.push(cur.val);
        if (w < cur.val) { if (!cur.left) { cur.left = new BSTNode(w); return {msg:`"${w}" < "${cur.val}" → inserted as left child. Path: ${path.join(' → ')}`}; } cur = cur.left; }
        else if (w > cur.val) { if (!cur.right) { cur.right = new BSTNode(w); return {msg:`"${w}" > "${cur.val}" → inserted as right child. Path: ${path.join(' → ')}`}; } cur = cur.right; }
        else return {msg:`"${w}" already in tree (found at: ${path.join(' → ')})`};
      }
    }

    function bstCountNodes(n) { return n ? 1 + bstCountNodes(n.left) + bstCountNodes(n.right) : 0; }

    function bstReset() {
      bstRoot = null; bstCount = 0;
      document.getElementById('bst-stats').textContent = '0 nodes';
      document.getElementById('bst-log').textContent = 'Insert a word to start building the tree.';
      bstDraw();
    }

    function bstPreset(words) {
      bstReset();
      words.forEach(w => { bstInsertNode(w); });
      bstCount = bstCountNodes(bstRoot);
      document.getElementById('bst-stats').textContent = bstCount + ' nodes';
      document.getElementById('bst-log').textContent = 'Preset loaded: ' + words.join(', ');
      bstDraw();
    }

    function bstDraw() {
      const svg = document.getElementById('bst-svg');
      svg.innerHTML = '';
      if (!bstRoot) return;
      const W = 700, H = 240;
      svg.setAttribute('width', W);
      svg.setAttribute('height', H);
      const depth = bstDepth(bstRoot);
      const levelH = Math.min(H / (depth + 1), 60);

      function pos(node, level, lo, hi) {
        if (!node) return;
        const x = (lo + hi) / 2, y = level * levelH + 30;
        node._x = x; node._y = y;
        pos(node.left,  level+1, lo, (lo+hi)/2);
        pos(node.right, level+1, (lo+hi)/2, hi);
      }
      pos(bstRoot, 0, 0, W);

      function drawEdges(node) {
        if (!node) return;
        if (node.left)  { drawLine(svg, node._x, node._y, node.left._x,  node.left._y); drawEdges(node.left); }
        if (node.right) { drawLine(svg, node._x, node._y, node.right._x, node.right._y); drawEdges(node.right); }
      }
      function drawNodes(node) {
        if (!node) return;
        const isLeaf = !node.left && !node.right;
        const r = 20;
        const c = document.createElementNS('http://www.w3.org/2000/svg','circle');
        c.setAttribute('cx', node._x); c.setAttribute('cy', node._y); c.setAttribute('r', r);
        c.setAttribute('fill', isLeaf ? 'rgba(16,185,129,.1)' : 'rgba(99,102,241,.1)');
        c.setAttribute('stroke', isLeaf ? '#10B981' : '#6366F1');
        c.setAttribute('stroke-width', node === bstRoot ? '2.5' : '1.8');
        svg.appendChild(c);
        const t = document.createElementNS('http://www.w3.org/2000/svg','text');
        t.setAttribute('x', node._x); t.setAttribute('y', node._y + 4);
        t.setAttribute('text-anchor','middle');
        t.setAttribute('fill', isLeaf ? '#10B981' : '#818CF8');
        t.setAttribute('font-size', node.val.length > 5 ? '9' : '11');
        t.setAttribute('font-weight','700');
        t.setAttribute('font-family','JetBrains Mono');
        t.textContent = node.val;
        svg.appendChild(t);
        drawNodes(node.left); drawNodes(node.right);
      }
      drawEdges(bstRoot);
      drawNodes(bstRoot);
    }

    function drawLine(svg, x1, y1, x2, y2) {
      const l = document.createElementNS('http://www.w3.org/2000/svg','line');
      l.setAttribute('x1',x1); l.setAttribute('y1',y1);
      l.setAttribute('x2',x2); l.setAttribute('y2',y2);
      l.setAttribute('stroke','#6366F1'); l.setAttribute('stroke-width','1.5'); l.setAttribute('opacity','.4');
      svg.appendChild(l);
    }

    function bstDepth(n) { return n ? 1 + Math.max(bstDepth(n.left), bstDepth(n.right)) : 0; }

    // ════════════════════════════════════════════════════════
    // Hash Table Visualiser
    // ════════════════════════════════════════════════════════
    let hashMax = 6;
    let hashData = []; // array of arrays
    let hashWordCount = 0;

    function hashFunc(word) {
      if (!word) return 0;
      return (word.length + word.charCodeAt(0)) % hashMax;
    }

    function hashInit() {
      hashData = Array.from({length: hashMax}, () => []);
      hashWordCount = 0;
      renderHashTable();
    }

    function hashResize() {
      const v = parseInt(document.getElementById('hash-max-input').value);
      if (v >= 2 && v <= 20) { hashMax = v; hashInit(); }
    }

    function hashReset() {
      hashInit();
      document.getElementById('hash-log').textContent = 'Insert a word to see the hash function in action.';
      document.getElementById('hash-stats').textContent = `HASHMAX = ${hashMax} | 0 words`;
    }

    function hashInsert() {
      const w = document.getElementById('hash-word-input').value.trim().toLowerCase();
      if (!w) return;
      document.getElementById('hash-word-input').value = '';
      const idx = hashFunc(w);
      const bucket = hashData[idx];
      const log = document.getElementById('hash-log');
      const asc = w.charCodeAt(0);
      const formula = `hash("${w}") = (${w.length} + ${asc}) % ${hashMax} = ${w.length + asc} % ${hashMax} = ${idx}`;
      if (bucket.includes(w)) {
        log.textContent = `"${w}" already in bucket ${idx}. ${formula}`;
        return;
      }
      bucket.push(w);
      hashWordCount++;
      document.getElementById('hash-stats').textContent = `HASHMAX = ${hashMax} | ${hashWordCount} words`;
      log.textContent = `${formula}  →  Added to bucket ${idx}`;
      document.getElementById('hash-formula-display').innerHTML =
        `hash(<span style="color:#A6E3A1;">"${w}"</span>) = (<span style="color:#FAB387;">${w.length}</span> + <span style="color:#FAB387;">${asc}</span>) % ${hashMax} = <span style="color:#6EE7B7;font-weight:700;">${idx}</span>`;
      renderHashTable(idx, w);
    }

    function hashPreset(words, max) {
      hashMax = max;
      document.getElementById('hash-max-input').value = max;
      hashInit();
      words.forEach(w => {
        const idx = hashFunc(w);
        if (!hashData[idx].includes(w)) { hashData[idx].push(w); hashWordCount++; }
      });
      document.getElementById('hash-stats').textContent = `HASHMAX = ${hashMax} | ${hashWordCount} words`;
      document.getElementById('hash-log').textContent = 'Preset loaded: ' + words.join(', ');
      renderHashTable();
    }

    function renderHashTable(newIdx = -1, newWord = '') {
      const vis = document.getElementById('hash-table-vis');
      vis.innerHTML = '';
      for (let i = 0; i < hashMax; i++) {
        const slot = document.createElement('div');
        slot.className = 'hash-slot';
        const idx = document.createElement('div');
        idx.className = 'hash-idx';
        idx.textContent = i;
        slot.appendChild(idx);
        const chain = document.createElement('div');
        chain.className = 'hash-chain';
        if (hashData[i].length === 0) {
          const nil = document.createElement('span');
          nil.className = 'hash-null';
          nil.textContent = 'null';
          chain.appendChild(nil);
        } else {
          hashData[i].forEach((w, j) => {
            if (j > 0) {
              const arr = document.createElement('span');
              arr.className = 'hash-arrow'; arr.textContent = '→';
              chain.appendChild(arr);
            }
            const node = document.createElement('div');
            node.className = 'hash-node' + (i === newIdx && w === newWord ? ' new' : '');
            node.textContent = w;
            chain.appendChild(node);
          });
        }
        slot.appendChild(chain);
        vis.appendChild(slot);
      }
    }

    // init
    hashInit();

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