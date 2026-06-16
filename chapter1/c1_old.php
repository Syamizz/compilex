<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chapter 1 – CompileX</title>

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
      --shadow: 0 4px 24px rgba(99, 102, 241, .09);
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--bg);
      color: var(--text);
      font-size: 15px;
      line-height: 1.75;
    }

    /* ── Navbar ─────────────────────── */
    #navbar {
      background: var(--pink) !important;
    }

    .navbar-brand {
      font-family: 'Syne', sans-serif;
      font-weight: 800;
      font-size: 21px;
      color: var(--text) !important;
    }

    #navbar .nav-link {
      font-weight: 500;
      color: var(--text) !important;
      border-radius: 8px;
      padding: 6px 13px !important;
      transition: background .2s;
    }

    #navbar .nav-link:hover {
      background: rgba(99, 102, 241, .1);
    }

    /* ── Layout ─────────────────────── */
    .note-container {
      display: grid;
      grid-template-columns: 260px 1fr;
      gap: 40px;
      max-width: 1140px;
      margin: 0 auto;
      padding: 36px 24px 80px;
      align-items: start;
    }

    /* ── TOC Sidebar ─────────────────── */
    .toc {
      position: sticky;
      top: 24px;
      background: var(--card);
      border: 1px solid rgba(99, 102, 241, .12);
      border-radius: var(--radius);
      padding: 22px 20px;
      box-shadow: var(--shadow);
    }

    .toc-title {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--purple);
      margin-bottom: 14px;
    }

    .toc ul {
      list-style: none;
      padding: 0;
    }

    .toc ul li {
      margin-bottom: 4px;
    }

    .toc ul li a {
      display: block;
      padding: 7px 10px;
      border-radius: 8px;
      font-size: 13px;
      color: var(--muted);
      text-decoration: none;
      transition: background .18s, color .18s;
      line-height: 1.4;
    }

    .toc ul li a:hover,
    .toc ul li a.active {
      background: var(--purple-s);
      color: var(--purple);
      font-weight: 500;
    }

    .toc-progress {
      margin-top: 18px;
      padding-top: 16px;
      border-top: 1px solid #EEF2FF;
    }

    .toc-progress-label {
      display: flex;
      justify-content: space-between;
      font-size: 12px;
      color: var(--muted);
      margin-bottom: 6px;
    }

    .toc-bar {
      height: 6px;
      background: #E8E8F0;
      border-radius: 20px;
      overflow: hidden;
    }

    .toc-bar-fill {
      height: 100%;
      background: var(--purple);
      border-radius: 20px;
      width: 0%;
      transition: width .6s ease;
    }

    /* ── Article ─────────────────────── */
    .content {
      min-width: 0;
    }

    .content-header {
      margin-bottom: 36px;
    }

    .chapter-tag {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--purple-s);
      color: var(--purple);
      font-family: 'Syne', sans-serif;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1px;
      text-transform: uppercase;
      padding: 4px 12px;
      border-radius: 20px;
      margin-bottom: 12px;
    }

    .content-header h1 {
      font-family: 'Syne', sans-serif;
      font-weight: 800;
      font-size: 34px;
      letter-spacing: -1px;
      color: var(--text);
      line-height: 1.15;
      margin-bottom: 10px;
    }

    .metadata {
      display: flex;
      gap: 16px;
      font-size: 13px;
      color: var(--muted);
      flex-wrap: wrap;
    }

    .metadata span {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    /* ── Sections ───────────────────── */
    section {
      margin-bottom: 48px;
      scroll-margin-top: 80px;
    }

    section h2 {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 22px;
      color: var(--text);
      margin-bottom: 14px;
      padding-bottom: 10px;
      border-bottom: 2px solid var(--purple-s);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    section h2 .sec-icon {
      width: 32px;
      height: 32px;
      background: var(--purple-s);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      flex-shrink: 0;
    }

    section h3 {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 16px;
      color: var(--text);
      margin: 24px 0 10px;
    }

    section p {
      color: var(--muted);
      margin-bottom: 14px;
    }

    /* ── Info boxes ─────────────────── */
    .note-box {
      display: flex;
      gap: 12px;
      padding: 16px 18px;
      border-radius: var(--radius);
      margin: 20px 0;
      font-size: 14px;
      line-height: 1.65;
    }

    .note-box .box-icon {
      font-size: 20px;
      flex-shrink: 0;
      margin-top: 1px;
    }

    .note-box.tip {
      background: #EFF6FF;
      border-left: 4px solid #3B82F6;
      color: #1D4ED8;
    }

    .note-box.pro {
      background: var(--purple-s);
      border-left: 4px solid var(--purple);
      color: #3730A3;
    }

    .note-box.warn {
      background: #FFFBEB;
      border-left: 4px solid var(--amber);
      color: #92400E;
    }

    .note-box.key {
      background: #F0FDF4;
      border-left: 4px solid var(--green);
      color: #065F46;
    }

    .note-box.red {
      background: #FFF1F2;
      border-left: 4px solid var(--red);
      color: #9F1239;
    }

    /* ── Code block ─────────────────── */
    .code-wrap {
      background: var(--code-bg);
      border-radius: var(--radius);
      margin: 20px 0;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, .25);
    }

    .code-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 16px;
      background: rgba(255, 255, 255, .05);
      border-bottom: 1px solid rgba(255, 255, 255, .07);
    }

    .code-lang {
      font-family: 'JetBrains Mono', monospace;
      font-size: 11px;
      color: rgba(255, 255, 255, .5);
      letter-spacing: .5px;
      text-transform: uppercase;
    }

    .code-dots {
      display: flex;
      gap: 6px;
    }

    .code-dots span {
      width: 10px;
      height: 10px;
      border-radius: 50%;
    }

    .dot-r {
      background: #FF5F57;
    }

    .dot-y {
      background: #FEBC2E;
    }

    .dot-g {
      background: #28C840;
    }

    .copy-btn {
      background: rgba(255, 255, 255, .08);
      border: none;
      color: rgba(255, 255, 255, .6);
      font-size: 11px;
      padding: 4px 10px;
      border-radius: 6px;
      cursor: pointer;
      font-family: 'DM Sans', sans-serif;
      transition: background .2s;
    }

    .copy-btn:hover {
      background: rgba(255, 255, 255, .15);
      color: white;
    }

    pre {
      background: transparent;
      margin: 0;
      padding: 20px;
      overflow-x: auto;
    }

    pre code {
      font-family: 'JetBrains Mono', monospace;
      font-size: 13px;
      line-height: 1.8;
      color: #CDD6F4;
    }

    .kw {
      color: #CBA6F7;
    }

    .cm {
      color: #6C7086;
      font-style: italic;
    }

    .str {
      color: #A6E3A1;
    }

    .num {
      color: #FAB387;
    }

    .fn {
      color: #89B4FA;
    }

    .reg {
      color: #F38BA8;
    }

    .op {
      color: #94E2D5;
    }

    /* ── Comparison table ───────────── */
    .compare-table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      font-size: 14px;
    }

    .compare-table th {
      background: var(--purple-s);
      color: var(--purple);
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      padding: 10px 14px;
      text-align: left;
    }

    .compare-table td {
      padding: 9px 14px;
      border-bottom: 1px solid #EEF2FF;
      color: var(--muted);
      vertical-align: top;
    }

    .compare-table tr:hover td {
      background: #FAFAFF;
    }

    .compare-table td:first-child {
      font-weight: 600;
      color: var(--text);
      white-space: nowrap;
    }

    /* ── Error table ────────────────── */
    .error-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin: 20px 0;
    }

    .error-col {
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow);
    }

    .error-col-header {
      padding: 10px 16px;
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 13px;
    }

    .error-col-header.compile {
      background: #FFF1F2;
      color: #9F1239;
    }

    .error-col-header.runtime {
      background: #FFFBEB;
      color: #92400E;
    }

    .error-col pre {
      background: var(--code-bg);
      margin: 0;
      padding: 16px;
    }

    .error-col pre code {
      font-size: 12px;
    }

    /* ── Big-C notation ─────────────── */
    .bigc-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
      margin: 20px 0;
    }

    .bigc-card {
      background: var(--card);
      border: 1px solid rgba(99, 102, 241, .15);
      border-radius: var(--radius);
      padding: 18px;
      box-shadow: var(--shadow);
      text-align: center;
    }

    .bigc-label {
      font-size: 12px;
      color: var(--muted);
      margin-bottom: 10px;
    }

    .bigc-notation {
      font-family: 'JetBrains Mono', monospace;
      font-size: 18px;
      color: var(--purple);
      font-weight: 700;
      margin: 8px 0;
    }

    .bigc-sub {
      font-size: 11px;
      color: var(--muted);
      font-style: italic;
    }

    /* ── Sample Problem box ─────────── */
    .sample-problem {
      background: var(--card);
      border: 1.5px solid rgba(99, 102, 241, .2);
      border-radius: var(--radius);
      margin: 24px 0;
      overflow: hidden;
      box-shadow: var(--shadow);
    }

    .sp-header {
      background: linear-gradient(135deg, #6366F1, #818CF8);
      padding: 12px 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .sp-header h4 {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 14px;
      color: white;
      margin: 0;
    }

    .sp-body {
      padding: 20px;
    }

    .sp-question {
      color: var(--muted);
      font-size: 14px;
      margin-bottom: 14px;
    }

    .sp-solution-label {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--green);
      margin-bottom: 8px;
    }

    /* ── Stepper ────────────────────── */
    .stepper-wrap {
      background: var(--card);
      border: 1px solid rgba(99, 102, 241, .15);
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      margin: 24px 0;
    }

    .stepper-header {
      background: var(--purple-s);
      padding: 14px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .stepper-header h4 {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 15px;
      color: var(--purple);
      margin: 0;
    }

    .step-counter {
      font-size: 12px;
      color: var(--purple);
      font-weight: 600;
    }

    .stepper-body {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0;
    }

    .stepper-code {
      background: var(--code-bg);
      padding: 20px;
      overflow-x: auto;
    }

    .stepper-code pre {
      padding: 0;
    }

    .stepper-code .line {
      display: block;
      padding: 3px 8px;
      border-radius: 4px;
      font-family: 'JetBrains Mono', monospace;
      font-size: 12.5px;
      color: rgba(205, 214, 244, .55);
      line-height: 1.7;
      transition: background .25s, color .25s;
    }

    .stepper-code .line.active {
      background: rgba(99, 102, 241, .25);
      color: #CDD6F4;
    }

    .stepper-explain {
      padding: 20px 22px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .explain-box {
      flex: 1;
    }

    .explain-step-label {
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--purple);
      margin-bottom: 8px;
    }

    .explain-title {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 16px;
      color: var(--text);
      margin-bottom: 8px;
    }

    .explain-desc {
      font-size: 13.5px;
      color: var(--muted);
      line-height: 1.65;
    }

    .reg-table {
      margin-top: 14px;
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
      font-family: 'JetBrains Mono', monospace;
    }

    .reg-table th {
      background: var(--purple-s);
      color: var(--purple);
      padding: 5px 10px;
      text-align: left;
      font-size: 11px;
      font-weight: 700;
    }

    .reg-table td {
      padding: 5px 10px;
      border-bottom: 1px solid #F1F0FB;
      color: var(--text);
    }

    .reg-table tr.changed td {
      background: #FFFBEB;
      color: #92400E;
    }

    .stepper-nav {
      display: flex;
      gap: 10px;
      margin-top: 16px;
    }

    .step-btn {
      flex: 1;
      padding: 9px;
      border: 1.5px solid rgba(99, 102, 241, .3);
      border-radius: 8px;
      background: transparent;
      color: var(--purple);
      font-family: 'Syne', sans-serif;
      font-weight: 600;
      font-size: 13px;
      cursor: pointer;
      transition: background .18s, color .18s;
    }

    .step-btn:hover {
      background: var(--purple-s);
    }

    .step-btn:disabled {
      opacity: .35;
      cursor: not-allowed;
    }

    .step-btn.primary {
      background: var(--purple);
      color: white;
      border-color: var(--purple);
    }

    .step-btn.primary:hover {
      background: var(--purple-d);
    }

    /* ── Quiz ───────────────────────── */
    .mini-quiz {
      background: var(--card);
      border: 1px solid rgba(99, 102, 241, .15);
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      margin: 28px 0;
    }

    .quiz-header {
      background: linear-gradient(135deg, #6366F1, #818CF8);
      padding: 14px 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .quiz-header h4 {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 15px;
      color: white;
      margin: 0;
    }

    .quiz-body {
      padding: 22px;
    }

    .quiz-q {
      font-weight: 600;
      color: var(--text);
      margin-bottom: 16px;
      font-size: 15px;
    }

    .quiz-options {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .quiz-opt {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      border: 1.5px solid rgba(99, 102, 241, .15);
      border-radius: 10px;
      cursor: pointer;
      font-size: 14px;
      transition: border-color .18s, background .18s;
      color: var(--text);
    }

    .quiz-opt:hover:not(.answered) {
      border-color: var(--purple);
      background: var(--purple-s);
    }

    .quiz-opt.answered {
      cursor: not-allowed;
    }

    .quiz-opt.correct {
      border-color: var(--green);
      background: #F0FDF4;
      color: #065F46;
    }

    .quiz-opt.wrong {
      border-color: var(--red);
      background: #FFF1F2;
      color: #9F1239;
    }

    .opt-circle {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: var(--purple-s);
      color: var(--purple);
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 13px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .quiz-feedback {
      margin-top: 14px;
      padding: 10px 16px;
      border-radius: 10px;
      font-size: 14px;
      display: none;
    }

    .quiz-feedback.show {
      display: block;
    }

    .quiz-feedback.ok {
      background: #F0FDF4;
      color: #065F46;
    }

    .quiz-feedback.bad {
      background: #FFF1F2;
      color: #9F1239;
    }

    /* ── Chapter nav ────────────────── */
    .chapter-nav {
      display: flex;
      justify-content: space-between;
      gap: 16px;
      margin-top: 56px;
      padding-top: 28px;
      border-top: 1px solid #EEF2FF;
    }

    .nav-btn {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 14px 22px;
      border: 1.5px solid rgba(99, 102, 241, .25);
      border-radius: 12px;
      background: var(--card);
      text-decoration: none;
      color: var(--text);
      font-family: 'Syne', sans-serif;
      font-weight: 600;
      font-size: 14px;
      transition: border-color .2s, box-shadow .2s, transform .2s;
      box-shadow: var(--shadow);
    }

    .nav-btn:hover {
      border-color: var(--purple);
      box-shadow: 0 6px 24px rgba(99, 102, 241, .18);
      transform: translateY(-2px);
      color: var(--purple);
    }

    .nav-btn .btn-sub {
      font-family: 'DM Sans', sans-serif;
      font-weight: 400;
      font-size: 11px;
      color: var(--muted);
      display: block;
    }

    .nav-btn.next {
      margin-left: auto;
    }

    /* ── Animations ─────────────────── */
    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(18px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    section {
      animation: fadeUp .5s ease forwards;
    }

    section:nth-child(1) {
      animation-delay: .05s;
    }

    section:nth-child(2) {
      animation-delay: .12s;
    }

    section:nth-child(3) {
      animation-delay: .19s;
    }

    section:nth-child(4) {
      animation-delay: .26s;
    }

    section:nth-child(5) {
      animation-delay: .33s;
    }

    section:nth-child(6) {
      animation-delay: .40s;
    }

    @media (max-width: 768px) {
      .note-container {
        grid-template-columns: 1fr;
      }

      .toc {
        position: static;
      }

      .stepper-body {
        grid-template-columns: 1fr;
      }

      .error-grid {
        grid-template-columns: 1fr;
      }

      .bigc-grid {
        grid-template-columns: 1fr;
      }
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
        <li><a href="#definition" class="toc-link">1.1 What is a Compiler?</a></li>
        <li><a href="#source-object" class="toc-link">1.1.1 Source & Object Programs</a></li>
        <li><a href="#vs-interpreter" class="toc-link">1.1.2 Compiler vs Interpreter</a></li>
        <li><a href="#errors" class="toc-link">1.1.3 Compile-time vs Run-time Errors</a></li>
        <li><a href="#sample-a" class="toc-link">1.1.4 Sample Problem (a) — while loop</a></li>
        <li><a href="#sample-b" class="toc-link">1.1.5 Sample Problem (b) — for loop</a></li>
        <li><a href="#bigc" class="toc-link">1.1.6 Big-C Notation</a></li>
        <li><a href="#sample-c" class="toc-link">1.1.7 Sample Problem (c) — Big-C</a></li>
        <li><a href="#walkthrough" class="toc-link">1.1.8 Step-by-Step Walkthrough</a></li>
      </ul>
      <div class="toc-progress">
        <div class="toc-progress-label">
          <span>Progress</span>
          <span id="pct-label">0%</span>
        </div>
        <div class="toc-bar">
          <div class="toc-bar-fill" id="toc-bar"></div>
        </div>
      </div>
    </nav>

    <!-- ── Main content ───────────────────────────────── -->
    <article class="content">

      <header class="content-header">
        <div class="chapter-tag">📘 Chapter 1</div>
        <h1>Introduction to Compilers</h1>
        <div class="metadata">
          <span>⏱ 15 min read</span>
          <span>🎯 Beginner</span>
          <span>⚙️ Compiler Theory</span>
        </div>
      </header>

      <!-- ─────────────────────────────────────────────── -->
      <!-- 1.1 What is a Compiler?                        -->
      <!-- ─────────────────────────────────────────────── -->
      <section id="definition">
        <h2><span class="sec-icon">🔍</span> What is a Compiler?</h2>
        <p>
          Recall from your study of assembly language that the CPU can only execute very simple, primitive instructions —
          adding two numbers in memory, moving data between registers, or branching to another address. There is certainly
          <em>no single instruction</em> capable of evaluating a complex expression such as
          <code>((x−x₀)² + (x−x₁)²)^½</code>, and there is no way to execute a multi-branch statement in one step.
        </p>
        <p>
          These capabilities are implemented by a software translator known as a <strong>compiler</strong>. The function
          of the compiler is to accept high-level statements and translate them into sequences of machine language
          operations which, when loaded into memory and executed, carry out the intended computation.
        </p>

        <div class="note-box warn">
          <span class="box-icon">⚠️</span>
          <div>
            <strong>Important:</strong> When processing a statement such as <code>x = x * 9;</code>, the compiler does
            <em>not</em> perform the multiplication. It generates, as output, a sequence of instructions
            <em>including</em> a "multiply" instruction. The computation happens only at run time.
          </div>
        </div>

        <p>
          Languages that permit complex operations are called <strong>high-level languages</strong>, or
          <strong>programming languages</strong>.
        </p>

        <div class="note-box tip">
          <span class="box-icon">💡</span>
          <div>
            <strong>User Interface Perspective:</strong> A programming language is itself a sophisticated
            <em>user interface</em> — a mechanism through which a programmer communicates with the computer.
            Even if you never design a compiler, understanding how compilers work makes you a significantly
            better programmer.
          </div>
        </div>
      </section>

      <!-- ─────────────────────────────────────────────── -->
      <!-- 1.1.1 Source & Object Programs                 -->
      <!-- ─────────────────────────────────────────────── -->
      <section id="source-object">
        <h2><span class="sec-icon">📄</span> Source &amp; Object Programs</h2>
        <p>
          A compiler accepts as input a program written in a particular high-level language and produces as output an
          equivalent program in machine language for a specific <strong>target machine</strong>. Two programs are
          considered <em>equivalent</em> if they always produce the same output when given the same input.
        </p>

        <table class="compare-table">
          <thead>
            <tr>
              <th>Term</th>
              <th>Definition</th>
              <th>Example</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Source Program</td>
              <td>The input to the compiler — written in the source language</td>
              <td>Java, C++, Ada</td>
            </tr>
            <tr>
              <td>Object Program</td>
              <td>The output of the compiler — written in the object language</td>
              <td>x86 machine code, MIPS assembly</td>
            </tr>
            <tr>
              <td>Target Machine</td>
              <td>The specific machine for which object code is generated</td>
              <td>Apple Macintosh, SUN, PC</td>
            </tr>
          </tbody>
        </table>

        <p>Some concrete examples of compilers:</p>
        <ul style="margin-left:20px; color:var(--muted); margin-bottom:14px;">
          <li>A Java compiler for the Apple Macintosh</li>
          <li>A COBOL compiler for the SUN</li>
          <li>A C++ compiler for the Apple Macintosh</li>
        </ul>

        <h3>Example: Translating <code>A = B + C * D;</code></h3>
        <p>
          The compiler must be smart enough to know that multiplication happens before addition, even though the
          addition appears first when scanning left to right. It also checks for proper <strong>syntax</strong>
          and issues helpful error messages when errors are found.
        </p>

        <div class="code-wrap">
          <div class="code-header">
            <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
            <span class="code-lang">Assembly — Compiled Output for A = B + C * D</span>
            <button class="copy-btn" onclick="copyBlock('abcd-block', this)">Copy</button>
          </div>
          <pre><code id="abcd-block"><span class="op">LOD</span> <span class="reg">R1</span>,<span class="num">C</span>       <span class="cm">// Load the value of C into reg 1</span>
<span class="op">MUL</span> <span class="reg">R1</span>,<span class="num">D</span>       <span class="cm">// Multiply the value of D by reg 1</span>
<span class="op">STO</span> <span class="reg">R1</span>,TEMP1   <span class="cm">// Store the result in TEMP1</span>
<span class="op">LOD</span> <span class="reg">R1</span>,<span class="num">B</span>       <span class="cm">// Load the value of B into reg 1</span>
<span class="op">ADD</span> <span class="reg">R1</span>,TEMP1   <span class="cm">// Add value of TEMP1 to register 1</span>
<span class="op">STO</span> <span class="reg">R1</span>,TEMP2   <span class="cm">// Store the result in TEMP2</span>
<span class="op">MOV</span> <span class="num">A</span>,TEMP2    <span class="cm">// Move TEMP2 to A, the final result</span></code></pre>
        </div>

        <div class="note-box pro">
          <span class="box-icon">🧩</span>
          <div>
            <strong>Semantic Equivalence over Optimality:</strong> In designing a compiler, the primary concern is that
            the object program be <em>semantically equivalent</em> to the source program (produces the same output for
            any given input). Object program efficiency is important, but not as important as correct code generation.
            Many compilers will not generate perfectly optimal code.
          </div>
        </div>

        <h3>Advantages of High-Level Languages</h3>
        <table class="compare-table">
          <thead>
            <tr>
              <th>Advantage</th>
              <th>Explanation</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Readability &amp; maintainability</td>
              <td>Machine/assembly language is difficult to work with and maintain</td>
            </tr>
            <tr>
              <td>Portability</td>
              <td>A high-level program can run on any machine that has a compiler for that language</td>
            </tr>
            <tr>
              <td>No retraining needed</td>
              <td>Programmers don't need to relearn every time a new CPU is introduced</td>
            </tr>
            <tr>
              <td>Abstraction</td>
              <td>High-level languages support data abstraction (data structures) and program abstraction (procedures/functions)</td>
            </tr>
          </tbody>
        </table>

        <h3>Disadvantages of High-Level Languages</h3>
        <table class="compare-table">
          <thead>
            <tr>
              <th>Disadvantage</th>
              <th>Explanation</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Limited control</td>
              <td>Programmer doesn't have complete control of machine resources (registers, interrupts, I/O buffers)</td>
            </tr>
            <tr>
              <td>Possible inefficiency</td>
              <td>The compiler may generate inefficient machine language programs</td>
            </tr>
            <tr>
              <td>Extra software needed</td>
              <td>The compiler itself must be available on the target platform</td>
            </tr>
          </tbody>
        </table>
        <p>As compiler development and hardware have improved, these disadvantages have become less problematic. Consequently, most programming today is done with high-level languages.</p>
      </section>

      <!-- ─────────────────────────────────────────────── -->
      <!-- 1.1.2 Compiler vs Interpreter                  -->
      <!-- ─────────────────────────────────────────────── -->
      <section id="vs-interpreter">
        <h2><span class="sec-icon">⚖️</span> Compiler vs Interpreter</h2>
        <p>
          An <strong>interpreter</strong> serves a purpose very similar to a compiler. The input is also a program
          written in a high-level language — but rather than generating a machine language program, the interpreter
          <em>directly carries out</em> the computations specified in the source program.
        </p>

        <div class="note-box key">
          <span class="box-icon">🔑</span>
          <div>
            <strong>Core distinction:</strong> The <em>output of a compiler</em> is a program (object code).
            The <em>output of an interpreter</em> is the source program's actual output (the computed result).
          </div>
        </div>

        <table class="compare-table">
          <thead>
            <tr>
              <th>Aspect</th>
              <th>Compiler</th>
              <th>Interpreter</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Output</td>
              <td>Object program (machine code)</td>
              <td>Program's computed output</td>
            </tr>
            <tr>
              <td>Execution</td>
              <td>Translates entire program first, then runs</td>
              <td>Reads and executes one line at a time</td>
            </tr>
            <tr>
              <td>Speed at run time</td>
              <td>Faster (already translated)</td>
              <td>Slower (translates on the fly)</td>
            </tr>
            <tr>
              <td>Error reporting</td>
              <td>Compile-time syntax errors reported before run</td>
              <td>Errors found only when that line is reached</td>
            </tr>
            <tr>
              <td>Example</td>
              <td>C, C++, Java (native)</td>
              <td>Python (CPython), early BASIC</td>
            </tr>
          </tbody>
        </table>

        <p>
          Consider the input <code>a = 3; b = 4; println(a*b);</code> — a compiler generates assembly instructions
          for it, while an interpreter evaluates it and directly outputs <strong>12</strong>.
        </p>

        <div class="note-box tip">
          <span class="box-icon">💡</span>
          <div>
            <strong>Common confusion:</strong> Many commercial compilers come packaged with a built-in
            edit–compile–run front end, so students don't notice the compilation step. As larger programs are
            divided into modules, each compiled separately to an <em>object module</em> and then <em>linked</em>,
            the distinction between compile time and run time becomes clearer.
          </div>
        </div>

        <p>
          It is important to remember that a compiler is itself a program, written in some language. When describing
          compilation we are always dealing with <strong>three languages</strong>:
        </p>
        <ol style="margin-left:20px; color:var(--muted); margin-bottom:14px;">
          <li>The <strong>source language</strong> — the input to the compiler</li>
          <li>The <strong>object language</strong> — the output of the compiler</li>
          <li>The <strong>implementation language</strong> — the language in which the compiler itself is written</li>
        </ol>
        <p>Note that the object language need not be machine code — it could itself be a high-level language.</p>
      </section>

      <!-- ─────────────────────────────────────────────── -->
      <!-- 1.1.3 Compile-time vs Run-time Errors          -->
      <!-- ─────────────────────────────────────────────── -->
      <section id="errors">
        <h2><span class="sec-icon">🐛</span> Compile-time vs Run-time Errors</h2>
        <p>
          <strong>Compile-time</strong> is when the source program is being compiled.
          <strong>Run time</strong> is when the resulting object program is loaded and executed.
          The type of error determines when it is detected.
        </p>

        <div class="error-grid">
          <div class="error-col">
            <div class="error-col-header compile">🔴 Compile-Time Errors (Syntax Errors)</div>
            <div class="code-wrap" style="margin:0; border-radius:0;">
              <pre><code><span class="cm">// Missing closing parenthesis</span>
a = ((b+c)*d;

<span class="cm">// Missing parentheses in if</span>
if x&lt;b fn1();
  else fn2();</code></pre>
            </div>
          </div>
          <div class="error-col">
            <div class="error-col-header runtime">🟡 Run-Time Errors (Logic Errors)</div>
            <div class="code-wrap" style="margin:0; border-radius:0;">
              <pre><code><span class="cm">// Division by zero</span>
x = a - a;
y = 100 / x;

<span class="cm">// Invalid array subscript</span>
Integer n[] = new Integer[7];
n[8] = 16;</code></pre>
            </div>
          </div>
        </div>

        <div class="note-box warn">
          <span class="box-icon">⚠️</span>
          <div>
            Syntax errors are reported by the compiler at <em>compile time</em>. Other kinds of errors —
            like division by zero or out-of-bounds array access — are not generally detected by the compiler
            and are called <em>run-time errors</em>. They only manifest when the program executes.
          </div>
        </div>
      </section>

      <!-- ─────────────────────────────────────────────── -->
      <!-- 1.1.4 Sample Problem (a) — while loop          -->
      <!-- ─────────────────────────────────────────────── -->
      <section id="sample-a">
        <h2><span class="sec-icon">🧪</span> Sample Problem 1.1(a) — Compiling a While Loop</h2>

        <div class="sample-problem">
          <div class="sp-header">
            <span style="font-size:18px">📋</span>
            <h4>Sample Problem 1.1 (a)</h4>
          </div>
          <div class="sp-body">
            <p class="sp-question">
              Show the output of a Java native code compiler, in any typical assembly language, for the following Java input:
            </p>
            <div class="code-wrap" style="margin-bottom:16px;">
              <div class="code-header">
                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                <span class="code-lang">Java — Source</span>
              </div>
              <pre><code><span class="kw">while</span> (x &lt; a + b) x = <span class="num">2</span> * x;</code></pre>
            </div>
            <div class="sp-solution-label">✅ Solution — Compiler Output (Assembly)</div>
            <div class="code-wrap">
              <div class="code-header">
                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                <span class="code-lang">Assembly — Object Code</span>
                <button class="copy-btn" onclick="copyBlock('sp-a-block', this)">Copy</button>
              </div>
              <pre><code id="sp-a-block">L1: <span class="op">LOD</span> <span class="reg">R1</span>,A          <span class="cm">// Load A into reg. 1</span>
    <span class="op">ADD</span> <span class="reg">R1</span>,B          <span class="cm">// Add B to reg. 1</span>
    <span class="op">STO</span> <span class="reg">R1</span>,Temp1      <span class="cm">// Temp1 = A + B</span>
    <span class="op">CMP</span> X,Temp1       <span class="cm">// Test for while condition</span>
    <span class="op">BL</span>  L2            <span class="cm">// Continue with loop if X &lt; Temp1</span>
    <span class="op">B</span>   L3            <span class="cm">// Terminate loop</span>
L2: <span class="op">LOD</span> <span class="reg">R1</span>,=<span class="str">'2'</span>
    <span class="op">MUL</span> <span class="reg">R1</span>,X
    <span class="op">STO</span> <span class="reg">R1</span>,X          <span class="cm">// X = 2 * X</span>
    <span class="op">B</span>   L1            <span class="cm">// Repeat loop</span>
L3:</code></pre>
            </div>
            <div class="note-box pro" style="margin-top:16px;">
              <span class="box-icon">🧩</span>
              <div>
                <strong>Why two branches?</strong> Note the somewhat convoluted logic after the <code>CMP</code>
                instruction — it branches to L2 if <code>X &lt; Temp1</code>, then unconditionally branches to L3.
                Some compilers could optimise this by branching directly to L3 when <code>X ≥ Temp1</code>,
                eliminating an instruction. But many compilers generate code this way, prioritising correctness
                over optimal instruction count.
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ─────────────────────────────────────────────── -->
      <!-- 1.1.5 Sample Problem (b) — for loop            -->
      <!-- ─────────────────────────────────────────────── -->
      <section id="sample-b">
        <h2><span class="sec-icon">🔁</span> Sample Problem 1.1(b) — Compiler vs Interpreter Output</h2>

        <div class="sample-problem">
          <div class="sp-header">
            <span style="font-size:18px">📋</span>
            <h4>Sample Problem 1.1 (b)</h4>
          </div>
          <div class="sp-body">
            <p class="sp-question">
              Show the compiler output and the interpreter output for the following Java source code:
            </p>
            <div class="code-wrap" style="margin-bottom:16px;">
              <div class="code-header">
                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                <span class="code-lang">Java — Source</span>
              </div>
              <pre><code><span class="kw">for</span> (i=<span class="num">1</span>; i&lt;=<span class="num">4</span>; i++) System.out.println(i*<span class="num">3</span>);</code></pre>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
              <div>
                <div class="sp-solution-label">🖥️ Compiler Output (Assembly)</div>
                <div class="code-wrap">
                  <div class="code-header">
                    <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                    <span class="code-lang">Assembly</span>
                  </div>
                  <pre><code>    <span class="op">LOD</span> <span class="reg">R1</span>,=<span class="str">'4'</span>
    <span class="op">STO</span> <span class="reg">R1</span>,Temp1
    <span class="op">MOV</span> i,=<span class="str">'1'</span>
L1: <span class="op">CMP</span> i,Temp1
    <span class="op">BH</span>  L2          <span class="cm">// Branch if i &gt; Temp1</span>
    <span class="op">LOD</span> <span class="reg">R1</span>,i
    <span class="op">MUL</span> <span class="reg">R1</span>,=<span class="str">'3'</span>
    <span class="op">STO</span> <span class="reg">R1</span>,Temp2
    <span class="op">PUSH</span> Temp2
    <span class="op">CALL</span> Println
    <span class="op">ADD</span> i,=<span class="str">'1'</span>     <span class="cm">// Add 1 to i</span>
    <span class="op">B</span>   L1
L2:</code></pre>
                </div>
              </div>
              <div>
                <div class="sp-solution-label">💬 Interpreter Output (Result)</div>
                <div class="code-wrap">
                  <div class="code-header">
                    <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                    <span class="code-lang">Printed Output</span>
                  </div>
                  <pre><code><span class="num">3</span>
<span class="num">6</span>
<span class="num">9</span>
<span class="num">12</span></code></pre>
                </div>
                <div class="note-box key" style="margin-top:12px;">
                  <span class="box-icon">💡</span>
                  <div>The interpreter directly computed and printed the values <strong>3, 6, 9, 12</strong> — it
                    did not generate any machine code at all.</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ─────────────────────────────────────────────── -->
      <!-- 1.1.6 Big-C Notation                           -->
      <!-- ─────────────────────────────────────────────── -->
      <section id="bigc">
        <h2><span class="sec-icon">🔠</span> Big-C Notation for Compilers</h2>
        <p>
          A concise notation for describing compilers was introduced by Aho (1986). In these diagrams the large
          <strong>C</strong> stands for <em>Compiler</em> (not the C programming language). The components are:
        </p>

        <table class="compare-table">
          <thead>
            <tr>
              <th>Component</th>
              <th>Meaning</th>
              <th>Example</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Superscript (top arrow)</td>
              <td>The intended <em>translation</em> — source → target language</td>
              <td>Java → Mac machine language</td>
            </tr>
            <tr>
              <td>Subscript (bottom)</td>
              <td>The language the compiler <em>exists in</em> (runs on)</td>
              <td>Written in Sun machine language</td>
            </tr>
          </tbody>
        </table>

        <p>In this notation, the name of a machine represents its machine language — i.e. <em>Sun</em> means Sun machine language, <em>PC</em> means Intel Pentium machine language.</p>

        <div class="bigc-grid">
          <div class="bigc-card">
            <div class="bigc-label">Figure 1.2 (a)</div>
            <div class="bigc-notation">C<sup>Java→Mac</sup><sub>Mac</sub></div>
            <div class="bigc-sub">A Java compiler that runs on the Mac and produces Mac machine code</div>
          </div>
          <div class="bigc-card">
            <div class="bigc-label">Figure 1.2 (b)</div>
            <div class="bigc-notation">C<sup>Java→Mac</sup><sub>Sun</sub></div>
            <div class="bigc-sub">A Java compiler that generates Mac code but runs on a Sun computer</div>
          </div>
          <div class="bigc-card">
            <div class="bigc-label">Figure 1.2 (c)</div>
            <div class="bigc-notation">C<sup>PC→Java</sup><sub>Ada</sub></div>
            <div class="bigc-sub">Translates PC machine language to Java; written in Ada — won't run on any machine directly</div>
          </div>
        </div>

        <div class="note-box tip">
          <span class="box-icon">💡</span>
          <div>
            The language the compiler is written in does <em>not</em> need to match the object language. A compiler
            that produces Mac machine code could run on a Sun. And the object language doesn't need to be machine code —
            it could be a high-level language like Java.
          </div>
        </div>
      </section>

      <!-- ─────────────────────────────────────────────── -->
      <!-- 1.1.7 Sample Problem (c) — Big-C               -->
      <!-- ─────────────────────────────────────────────── -->
      <section id="sample-c">
        <h2><span class="sec-icon">✏️</span> Sample Problem 1.1(c) — Big-C Notation Practice</h2>

        <div class="sample-problem">
          <div class="sp-header">
            <span style="font-size:18px">📋</span>
            <h4>Sample Problem 1.1 (c)</h4>
          </div>
          <div class="sp-body">
            <p class="sp-question">Using the Big-C notation, show each of the following compilers:</p>
            <ol style="color:var(--muted); margin-left:20px; margin-bottom:16px;">
              <li>(a) An Ada compiler which runs on the PC and compiles to PC machine language.</li>
              <li>(b) An Ada compiler which compiles to PC machine language, but which is written in Ada.</li>
              <li>(c) An Ada compiler which compiles to PC machine language, but runs on a Sun.</li>
            </ol>

            <div class="sp-solution-label">✅ Solution</div>
            <div class="bigc-grid">
              <div class="bigc-card">
                <div class="bigc-label">(a) Ada → PC, runs on PC</div>
                <div class="bigc-notation">C<sup>Ada→PC</sup><sub>PC</sub></div>
                <div class="bigc-sub">Compiles Ada to PC machine language; the compiler itself runs on a PC</div>
              </div>
              <div class="bigc-card">
                <div class="bigc-label">(b) Ada → PC, written in Ada</div>
                <div class="bigc-notation">C<sup>Ada→PC</sup><sub>Ada</sub></div>
                <div class="bigc-sub">Compiles Ada to PC machine language; written in Ada — won't run directly on any machine</div>
              </div>
              <div class="bigc-card">
                <div class="bigc-label">(c) Ada → PC, runs on Sun</div>
                <div class="bigc-notation">C<sup>Ada→PC</sup><sub>Sun</sub></div>
                <div class="bigc-sub">Compiles Ada to PC machine language; the compiler runs on a Sun machine</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ─────────────────────────────────────────────── -->
      <!-- 1.1.8 Step-by-Step Walkthrough                 -->
      <!-- ─────────────────────────────────────────────── -->
      <section id="walkthrough">
        <h2><span class="sec-icon">▶️</span> Step-by-Step Walkthrough</h2>
        <p>Step through each assembly instruction for <code>A = B + C * D</code> and watch what happens to the registers and memory in real time.</p>

        <div class="stepper-wrap">
          <div class="stepper-header">
            <h4>🖥️ Executing: A = B + C * D</h4>
            <span class="step-counter" id="step-counter">Step 1 of 7</span>
          </div>
          <div class="stepper-body">
            <div class="stepper-code">
              <pre><code id="step-code"></code></pre>
            </div>
            <div class="stepper-explain">
              <div class="explain-box">
                <div class="explain-step-label" id="step-label">STEP 1</div>
                <div class="explain-title" id="step-title">Loading C into R1</div>
                <div class="explain-desc" id="step-desc"></div>
                <table class="reg-table">
                  <thead>
                    <tr>
                      <th>Register / Var</th>
                      <th>Value</th>
                    </tr>
                  </thead>
                  <tbody id="reg-tbody"></tbody>
                </table>
              </div>
              <div class="stepper-nav">
                <button class="step-btn" id="btn-prev" onclick="stepMove(-1)" disabled>← Prev</button>
                <button class="step-btn primary" id="btn-next" onclick="stepMove(1)">Next →</button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ─────────────────────────────────────────────── -->


      <!-- Chapter nav -->
      <div class="chapter-nav">
        <a href="index.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Back to</span>Home</div>
        </a>
        <a href="c1_2.php" class="nav-btn next">
          <div style="text-align:right"><span class="btn-sub">Next page</span>1.2 The Phases of a Compiler</div>
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

    // ── Copy code blocks ─────────────────────────────────────
    function copyBlock(id, btn) {
      const text = document.getElementById(id).innerText;
      navigator.clipboard.writeText(text).then(() => {
        btn.textContent = 'Copied!';
        setTimeout(() => btn.textContent = 'Copy', 1800);
      });
    }

    // ── Step-by-step walkthrough (A = B + C * D) ─────────────
    const steps = [{
        active: 0,
        label: 'STEP 1 — LOD R1, C',
        title: 'Load C into Register R1',
        desc: 'The LOD (Load) instruction reads the value at memory address C and copies it into CPU register R1. Memory is unchanged.',
        regs: [{
          r: 'R1',
          v: 'C',
          changed: true
        }, {
          r: 'TEMP1',
          v: '—'
        }, {
          r: 'TEMP2',
          v: '—'
        }, {
          r: 'A',
          v: '—'
        }]
      },
      {
        active: 1,
        label: 'STEP 2 — MUL R1, D',
        title: 'Multiply R1 by D',
        desc: 'MUL multiplies the value in R1 (holds C) by the value at memory address D. The result C×D is written back into R1.',
        regs: [{
          r: 'R1',
          v: 'C × D',
          changed: true
        }, {
          r: 'TEMP1',
          v: '—'
        }, {
          r: 'TEMP2',
          v: '—'
        }, {
          r: 'A',
          v: '—'
        }]
      },
      {
        active: 2,
        label: 'STEP 3 — STO R1, TEMP1',
        title: 'Spill R1 to TEMP1',
        desc: 'STO (Store) copies the value from R1 into memory location TEMP1. This frees R1 so it can be reused for the next sub-expression.',
        regs: [{
          r: 'R1',
          v: 'C × D'
        }, {
          r: 'TEMP1',
          v: 'C × D',
          changed: true
        }, {
          r: 'TEMP2',
          v: '—'
        }, {
          r: 'A',
          v: '—'
        }]
      },
      {
        active: 3,
        label: 'STEP 4 — LOD R1, B',
        title: 'Load B into Register R1',
        desc: 'R1 is reloaded with the value from memory address B. The previous C×D value was safely stored in TEMP1.',
        regs: [{
          r: 'R1',
          v: 'B',
          changed: true
        }, {
          r: 'TEMP1',
          v: 'C × D'
        }, {
          r: 'TEMP2',
          v: '—'
        }, {
          r: 'A',
          v: '—'
        }]
      },
      {
        active: 4,
        label: 'STEP 5 — ADD R1, TEMP1',
        title: 'Add TEMP1 to R1',
        desc: 'ADD reads TEMP1 (which holds C×D) and adds it to R1 (which holds B). The result B + (C×D) is stored back in R1.',
        regs: [{
          r: 'R1',
          v: 'B + (C×D)',
          changed: true
        }, {
          r: 'TEMP1',
          v: 'C × D'
        }, {
          r: 'TEMP2',
          v: '—'
        }, {
          r: 'A',
          v: '—'
        }]
      },
      {
        active: 5,
        label: 'STEP 6 — STO R1, TEMP2',
        title: 'Store result in TEMP2',
        desc: 'The combined result B+(C×D) is stored in TEMP2. This is the final computed value, ready to be assigned to variable A.',
        regs: [{
          r: 'R1',
          v: 'B + (C×D)'
        }, {
          r: 'TEMP1',
          v: 'C × D'
        }, {
          r: 'TEMP2',
          v: 'B + (C×D)',
          changed: true
        }, {
          r: 'A',
          v: '—'
        }]
      },
      {
        active: 6,
        label: 'STEP 7 — MOV A, TEMP2',
        title: 'Assign final result to A',
        desc: 'MOV copies TEMP2 into the memory address of variable A. The expression A = B + C*D is now fully evaluated and stored. ✅',
        regs: [{
          r: 'R1',
          v: 'B + (C×D)'
        }, {
          r: 'TEMP1',
          v: 'C × D'
        }, {
          r: 'TEMP2',
          v: 'B + (C×D)'
        }, {
          r: 'A',
          v: 'B + (C×D)',
          changed: true
        }]
      }
    ];

    const asmLines = [
      'LOD R1,C       // Load the value of C into reg 1',
      'MUL R1,D       // Multiply the value of D by reg 1',
      'STO R1,TEMP1   // Store the result in TEMP1',
      'LOD R1,B       // Load the value of B into reg 1',
      'ADD R1,TEMP1   // Add value of Temp1 to register 1',
      'STO R1,TEMP2   // Store the result in TEMP2',
      'MOV A,TEMP2    // Move TEMP2 to A, the final result',
    ];

    let currentStep = 0;

    function renderStep() {
      const s = steps[currentStep];
      document.getElementById('step-code').innerHTML = asmLines.map((line, i) =>
        `<span class="line${i === s.active ? ' active' : ''}">${line}</span>`
      ).join('\n');
      document.getElementById('step-label').textContent = s.label;
      document.getElementById('step-title').textContent = s.title;
      document.getElementById('step-desc').textContent = s.desc;
      document.getElementById('reg-tbody').innerHTML = s.regs.map(r =>
        `<tr class="${r.changed ? 'changed' : ''}"><td>${r.r}</td><td>${r.v}</td></tr>`
      ).join('');
      document.getElementById('step-counter').textContent = `Step ${currentStep + 1} of ${steps.length}`;
      document.getElementById('btn-prev').disabled = currentStep === 0;
      const nextBtn = document.getElementById('btn-next');
      nextBtn.disabled = currentStep === steps.length - 1;
      nextBtn.textContent = currentStep === steps.length - 1 ? '✅ Done' : 'Next →';
    }

    function stepMove(dir) {
      currentStep = Math.max(0, Math.min(steps.length - 1, currentStep + dir));
      renderStep();
    }

    renderStep();
  </script>

</body>

</html>