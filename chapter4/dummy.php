<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter 4.0 - CompileX</title>

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
            --blue: #2563EB;
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

        .note-container {
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 40px;
            max-width: 1140px;
            margin: 0 auto;
            padding: 36px 24px 80px;
            align-items: start;
        }

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
            margin: 0;
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

        .toc ul li a.sub {
            padding-left: 22px;
            font-size: 12px;
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
            letter-spacing: 0;
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

        section {
            margin-bottom: 52px;
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
            margin: 26px 0 10px;
        }

        section p {
            color: var(--muted);
            margin-bottom: 14px;
        }

        section p strong {
            color: var(--text);
        }

        section ul,
        section ol {
            color: var(--muted);
            margin: 0 0 14px 20px;
        }

        section li {
            margin-bottom: 5px;
        }

        section code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12.5px;
            background: var(--purple-s);
            color: var(--purple);
            padding: 2px 6px;
            border-radius: 5px;
        }

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

        .concept-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin: 20px 0;
        }

        .concept-card,
        .grammar-card,
        .sample-card {
            background: var(--card);
            border: 1.5px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            padding: 18px;
            box-shadow: var(--shadow);
        }

        .cc-title,
        .grammar-title,
        .sample-title {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 14px;
            margin-bottom: 6px;
            color: var(--text);
        }

        .cc-body,
        .sample-body {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.6;
        }

        .grammar-lines {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: #CDD6F4;
            background: var(--code-bg);
            border-radius: 10px;
            padding: 14px 16px;
            line-height: 1.8;
            margin-top: 12px;
            overflow-x: auto;
        }

        .parse-strip {
            display: flex;
            gap: 10px;
            align-items: stretch;
            overflow-x: auto;
            padding: 12px;
            background: var(--card);
            border: 1.5px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin: 20px 0;
        }

        .parse-step {
            min-width: 132px;
            border-radius: 10px;
            padding: 12px;
            background: var(--purple-s);
            border: 1px solid rgba(99, 102, 241, .18);
            position: relative;
        }

        .parse-step:not(:last-child)::after {
            content: ">";
            position: absolute;
            right: -9px;
            top: 38%;
            color: var(--purple);
            font-weight: 800;
        }

        .parse-step .rule {
            display: inline-flex;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 12px;
            background: var(--purple);
            color: white;
            margin-bottom: 8px;
        }

        .parse-step .sentential {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: var(--text);
            white-space: nowrap;
        }

        .algo-table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
            font-size: 14px;
        }

        .algo-table th {
            background: var(--code-bg);
            color: #A5B4FC;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            padding: 10px 14px;
            text-align: left;
            font-size: 12px;
        }

        .algo-table td {
            padding: 9px 14px;
            border-bottom: 1px solid #EEF2FF;
            color: var(--muted);
            vertical-align: top;
        }

        .algo-table td:first-child {
            font-weight: 600;
            color: var(--text);
        }

        .closure-lab {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin: 24px 0;
        }

        .lab-header {
            background: linear-gradient(135deg, #6366F1, #10B981);
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .lab-header h4 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: white;
            margin: 0;
        }

        .lab-score {
            color: white;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            background: rgba(255, 255, 255, .18);
            border-radius: 999px;
            padding: 5px 10px;
        }

        .lab-body {
            padding: 20px;
        }

        .lab-controls,
        .quiz-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 16px;
        }

        .lab-select {
            padding: 8px 12px;
            border: 1.5px solid rgba(99, 102, 241, .3);
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: var(--text);
            background: var(--bg);
            outline: none;
            cursor: pointer;
        }

        .lab-btn,
        .quiz-btn {
            padding: 8px 14px;
            border-radius: 8px;
            border: none;
            background: var(--purple);
            color: white;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: background .18s, transform .18s;
        }

        .lab-btn:hover,
        .quiz-btn:hover {
            background: var(--purple-d);
            transform: translateY(-1px);
        }

        .lab-btn.sec,
        .quiz-btn.sec {
            background: transparent;
            border: 1.5px solid rgba(99, 102, 241, .3);
            color: var(--purple);
        }

        .lab-btn.sec:hover,
        .quiz-btn.sec:hover {
            background: var(--purple-s);
        }

        .lab-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            align-items: start;
        }

        .relation-panel {
            border-radius: 12px;
            border: 1px solid rgba(99, 102, 241, .14);
            background: #FAFAFF;
            padding: 16px;
        }

        .panel-title {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 13px;
            color: var(--text);
            margin-bottom: 10px;
        }

        .pair-bank {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            min-height: 48px;
        }

        .pair-chip {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            border: 1.5px solid rgba(99, 102, 241, .22);
            color: var(--text);
            background: white;
            border-radius: 999px;
            padding: 6px 10px;
            cursor: pointer;
            transition: transform .18s, border .18s, background .18s;
        }

        .pair-chip:hover {
            transform: translateY(-1px);
            border-color: var(--purple);
        }

        .pair-chip.selected {
            background: var(--purple);
            color: white;
            border-color: var(--purple);
        }

        .pair-chip.correct {
            background: #DCFCE7;
            color: #065F46;
            border-color: var(--green);
        }

        .pair-chip.missed {
            background: #FEF3C7;
            color: #92400E;
            border-color: var(--amber);
        }

        .pair-chip.wrong {
            background: #FEE2E2;
            color: #991B1B;
            border-color: var(--red);
        }

        .matrix-wrap {
            overflow-x: auto;
        }

        .matrix {
            border-collapse: separate;
            border-spacing: 5px;
            margin-top: 4px;
        }

        .matrix th {
            font-family: 'Syne', sans-serif;
            color: var(--muted);
            font-size: 12px;
            text-align: center;
            min-width: 34px;
        }

        .matrix td {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            text-align: center;
            vertical-align: middle;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            background: white;
            border: 1px solid #E5E7EB;
            color: var(--muted);
        }

        .matrix td.base {
            background: var(--purple-s);
            color: var(--purple);
            border-color: rgba(99, 102, 241, .25);
            font-weight: 700;
        }

        .matrix td.added {
            background: #DCFCE7;
            color: #065F46;
            border-color: rgba(16, 185, 129, .35);
            font-weight: 700;
        }

        .graph-board {
            position: relative;
            min-height: 260px;
            background:
                linear-gradient(90deg, rgba(99, 102, 241, .06) 1px, transparent 1px),
                linear-gradient(rgba(99, 102, 241, .06) 1px, transparent 1px);
            background-size: 24px 24px;
            border-radius: 12px;
            overflow: hidden;
        }

        .graph-board svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .graph-node {
            position: absolute;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: white;
            border: 3px solid var(--purple);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            box-shadow: 0 6px 18px rgba(99, 102, 241, .14);
        }

        .edge-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            fill: var(--text);
        }

        .lab-feedback,
        .quiz-feedback {
            display: none;
            margin-top: 14px;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 13px;
            line-height: 1.55;
        }

        .lab-feedback.show,
        .quiz-feedback.show {
            display: block;
        }

        .lab-feedback.ok,
        .quiz-feedback.ok {
            background: #F0FDF4;
            color: #065F46;
            border: 1px solid rgba(16, 185, 129, .25);
        }

        .lab-feedback.bad,
        .quiz-feedback.bad {
            background: #FEF2F2;
            color: #991B1B;
            border: 1px solid rgba(239, 68, 68, .22);
        }

        .lab-feedback.partial {
            background: #FFFBEB;
            color: #92400E;
            border: 1px solid rgba(245, 158, 11, .25);
        }

        .mini-quiz {
            background: var(--card);
            border: 1.5px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin: 24px 0;
        }

        .quiz-header {
            padding: 14px 20px;
            background: var(--code-bg);
            color: white;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quiz-body {
            padding: 18px 20px 20px;
        }

        .quiz-opt {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 12px;
            border-radius: 10px;
            background: var(--bg);
            border: 1.5px solid transparent;
            margin-bottom: 8px;
            cursor: pointer;
            transition: border .18s, background .18s;
        }

        .quiz-opt:hover {
            border-color: rgba(99, 102, 241, .3);
            background: white;
        }

        .quiz-opt.answered {
            cursor: default;
        }

        .quiz-opt.correct {
            border-color: var(--green);
            background: #F0FDF4;
            color: #065F46;
        }

        .quiz-opt.wrong {
            border-color: var(--red);
            background: #FEF2F2;
            color: #991B1B;
        }

        .opt-circle {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid currentColor;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-size: 12px;
            flex-shrink: 0;
        }

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
            opacity: 0;
        }

        section:nth-child(1) {
            animation-delay: .05s;
        }

        section:nth-child(2) {
            animation-delay: .10s;
        }

        section:nth-child(3) {
            animation-delay: .15s;
        }

        section:nth-child(4) {
            animation-delay: .20s;
        }

        section:nth-child(5) {
            animation-delay: .25s;
        }

        section:nth-child(6) {
            animation-delay: .30s;
        }

        @media(max-width: 768px) {
            .note-container {
                grid-template-columns: 1fr;
            }

            .toc {
                position: static;
            }

            .concept-grid,
            .lab-grid {
                grid-template-columns: 1fr;
            }

            .content-header h1 {
                font-size: 28px;
            }

            .parse-step {
                min-width: 118px;
            }
        }
    </style>
</head>

<body>

    <?php include '../dashboard.php'; ?>

    <div class="note-container">
        <nav class="toc">
            <div class="toc-title">Contents</div>
            <ul>
                <li><a href="#overview" class="toc-link">4.0 Overview</a></li>
                <li><a href="#topdown" class="toc-link">Top Down Parsing</a></li>
                <li><a href="#relations" class="toc-link">Relations</a></li>
                <li><a href="#closure" class="toc-link">Reflexive Transitive Closure</a></li>
                <li><a href="#exercise" class="toc-link">Interactive Exercise</a></li>
                <li><a href="#quiz" class="toc-link">Check Your Knowledge</a></li>
            </ul>
            <div class="toc-progress">
                <div class="toc-progress-label">
                    <span>Progress</span>
                    <span id="toc-percent">0%</span>
                </div>
                <div class="toc-bar">
                    <div class="toc-bar-fill" id="toc-bar"></div>
                </div>
            </div>
        </nav>

        <article class="content">
            <header class="content-header">
                <div class="chapter-tag">Chapter 4 - Section 4.0</div>
                <h1>Relations and Closure for Top Down Parsing</h1>
                <div class="metadata">
                    <span>12 min read</span>
                    <span>Interactive closure lab</span>
                    <span>Based on Chapter 4 notes</span>
                </div>
            </header>

            <section id="overview">
                <h2><span class="sec-icon">4.0</span> Why Chapter 4 Starts Here</h2>
                <p>
                    Chapter 4 continues from the parsing problem: given a grammar and an input string,
                    determine whether the string belongs to the grammar language and, if it does, find
                    its structure. Parsing algorithms are usually classified as <strong>top down</strong>
                    or <strong>bottom up</strong>.
                </p>
                <p>
                    This chapter focuses on <strong>top down parsing</strong>. Before building those
                    algorithms, we need a small mathematics toolkit: sets, relations, and closures.
                    These ideas help automate parser construction from a grammar.
                </p>
                <div class="note-box key">
                    <div class="box-icon">Key</div>
                    <div>
                        A parser should not be hand-guessed every time. The goal is to use the grammar
                        to automatically decide what rule can be applied next.
                    </div>
                </div>
                <div class="concept-grid">
                    <div class="concept-card">
                        <div class="cc-title">Top down idea</div>
                        <div class="cc-body">Start from the grammar's start symbol and try to derive the input string.</div>
                    </div>
                    <div class="concept-card">
                        <div class="cc-title">Closure idea</div>
                        <div class="cc-body">Start with known ordered pairs, then add every pair that must also be true.</div>
                    </div>
                </div>
            </section>

            <section id="topdown">
                <h2><span class="sec-icon">TD</span> Top Down Parsing</h2>
                <p>
                    In a top down parsing algorithm, grammar rules are applied in a sequence that
                    follows a general top-to-bottom direction in the derivation tree. The parser begins
                    with the starting nonterminal and decides which grammar rule should be applied.
                </p>

                <div class="grammar-card">
                    <div class="grammar-title">Grammar G8 from the notes</div>
                    <div class="grammar-lines">
                        G8:<br>
                        1. S -> a S b<br>
                        2. S -> b A c<br>
                        3. A -> b S<br>
                        4. A -> a
                    </div>
                </div>

                <p>
                    For the input string <code>abbbaccb</code>, the top down derivation applies rules
                    in this order: <code>1, 2, 3, 2, 4</code>.
                </p>

                <div class="parse-strip" aria-label="Top down derivation sequence">
                    <div class="parse-step">
                        <div class="rule">1</div>
                        <div class="sentential">S</div>
                    </div>
                    <div class="parse-step">
                        <div class="rule">1</div>
                        <div class="sentential">aSb</div>
                    </div>
                    <div class="parse-step">
                        <div class="rule">2</div>
                        <div class="sentential">abAcb</div>
                    </div>
                    <div class="parse-step">
                        <div class="rule">3</div>
                        <div class="sentential">abbScb</div>
                    </div>
                    <div class="parse-step">
                        <div class="rule">2</div>
                        <div class="sentential">abbbAccb</div>
                    </div>
                    <div class="parse-step">
                        <div class="rule">4</div>
                        <div class="sentential">abbbaccb</div>
                    </div>
                </div>

                <div class="note-box tip">
                    <div class="box-icon">Tip</div>
                    <div>
                        The sequence of sentential forms is the parser's visible trail. If the trail can
                        reach the input string, the string can be derived from the grammar.
                    </div>
                </div>
            </section>

            <section id="relations">
                <h2><span class="sec-icon">R</span> Relations</h2>
                <p>
                    A <strong>relation</strong> is a set of ordered pairs. Each pair is written in
                    parentheses, such as <code>(a,b)</code>. Order matters: <code>(a,b)</code> and
                    <code>(b,a)</code> are not the same pair.
                </p>

                <div class="grammar-card">
                    <div class="grammar-title">Example relation R1</div>
                    <div class="grammar-lines">
                        R1 = {<br>
                        &nbsp;&nbsp;(a,b),<br>
                        &nbsp;&nbsp;(c,d),<br>
                        &nbsp;&nbsp;(b,a),<br>
                        &nbsp;&nbsp;(b,c),<br>
                        &nbsp;&nbsp;(c,c)<br>
                        }
                    </div>
                </div>

                <table class="algo-table">
                    <thead>
                        <tr>
                            <th>Idea</th>
                            <th>Meaning</th>
                            <th>Small example</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Ordered pair</td>
                            <td>A directed relationship from one item to another.</td>
                            <td><code>(a,b)</code> means a is related to b.</td>
                        </tr>
                        <tr>
                            <td>Relation</td>
                            <td>A set of ordered pairs.</td>
                            <td><code>{(a,b), (b,c)}</code></td>
                        </tr>
                        <tr>
                            <td>Named relation</td>
                            <td>Some relations are written using symbols.</td>
                            <td><code>4 &lt; 9</code>, <code>2 &lt; 3</code></td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section id="closure">
                <h2><span class="sec-icon">R*</span> Reflexive Transitive Closure</h2>
                <p>
                    If <code>R</code> is a relation, its <strong>reflexive transitive closure</strong>
                    is written as <code>R*</code>. It contains the original relation plus every pair
                    required by transitivity and reflexivity.
                </p>

                <div class="concept-grid">
                    <div class="concept-card">
                        <div class="cc-title">1. Keep original pairs</div>
                        <div class="cc-body">Every pair in <code>R</code> must also appear in <code>R*</code>.</div>
                    </div>
                    <div class="concept-card">
                        <div class="cc-title">2. Add transitive pairs</div>
                        <div class="cc-body">If <code>(a,b)</code> and <code>(b,c)</code> are in <code>R*</code>, add <code>(a,c)</code>.</div>
                    </div>
                    <div class="concept-card">
                        <div class="cc-title">3. Add reflexive pairs</div>
                        <div class="cc-body">For each symbol involved, add <code>(x,x)</code>.</div>
                    </div>
                    <div class="concept-card">
                        <div class="cc-title">4. Repeat until stable</div>
                        <div class="cc-body">New transitive pairs may create more pairs, so keep checking until no more are added.</div>
                    </div>
                </div>

                <div class="sample-card">
                    <div class="sample-title">Sample Problem 4.0 result for R1*</div>
                    <div class="sample-body">
                        From <code>R1 = {(a,b), (c,d), (b,a), (b,c), (c,c)}</code>, the closure includes
                        the original pairs, transitive pairs such as <code>(a,c)</code>,
                        <code>(b,d)</code>, and <code>(a,d)</code>, plus reflexive pairs such as
                        <code>(a,a)</code>, <code>(b,b)</code>, and <code>(d,d)</code>.
                    </div>
                </div>

                <div class="note-box warn">
                    <div class="box-icon">Watch</div>
                    <div>
                        Reflexive pairs do not create new transitive reachability. The notes compute
                        transitive additions first, then list reflexive additions last.
                    </div>
                </div>
            </section>

            <section id="exercise">
                <h2><span class="sec-icon">Lab</span> Interactive Exercise - Build R*</h2>
                <p>
                    Choose a relation, inspect its graph and matrix, then select every pair that belongs
                    in the reflexive transitive closure. Use "Reveal one hint" when you want a guided step.
                </p>

                <div class="closure-lab">
                    <div class="lab-header">
                        <h4>Closure Builder</h4>
                        <div class="lab-score" id="lab-score">0 selected</div>
                    </div>
                    <div class="lab-body">
                        <div class="lab-controls">
                            <select class="lab-select" id="relation-select" onchange="loadRelation()">
                                <option value="r1">R1 from Sample Problem 4.0</option>
                                <option value="short">Short chain</option>
                                <option value="cycle">Cycle relation</option>
                            </select>
                            <button class="lab-btn" type="button" onclick="checkClosure()">Check Answer</button>
                            <button class="lab-btn sec" type="button" onclick="revealHint()">Reveal one hint</button>
                            <button class="lab-btn sec" type="button" onclick="resetClosure()">Reset</button>
                        </div>

                        <div class="lab-grid">
                            <div class="relation-panel">
                                <div class="panel-title">Relation graph</div>
                                <div class="graph-board" id="graph-board">
                                    <svg id="graph-svg" viewBox="0 0 420 260" preserveAspectRatio="none"></svg>
                                </div>
                            </div>

                            <div class="relation-panel">
                                <div class="panel-title">Matrix view</div>
                                <div class="matrix-wrap" id="matrix-wrap"></div>
                            </div>
                        </div>

                        <div class="relation-panel" style="margin-top:18px;">
                            <div class="panel-title">Select all pairs in R*</div>
                            <div class="pair-bank" id="pair-bank"></div>
                        </div>

                        <div class="lab-feedback" id="lab-feedback"></div>
                    </div>
                </div>
            </section>

            <section id="quiz">
                <h2><span class="sec-icon">Quiz</span> Check Your Knowledge</h2>

                <div class="mini-quiz">
                    <div class="quiz-header">Question 1</div>
                    <div class="quiz-body">
                        <p>In top down parsing, where does the parser begin?</p>
                        <div id="q1-opts">
                            <div class="quiz-opt" onclick="answer(this, 'q1', false)"><span class="opt-circle">A</span>At the final token</div>
                            <div class="quiz-opt" onclick="answer(this, 'q1', true)"><span class="opt-circle">B</span>At the grammar start symbol</div>
                            <div class="quiz-opt" onclick="answer(this, 'q1', false)"><span class="opt-circle">C</span>At every terminal at once</div>
                        </div>
                        <div class="quiz-feedback" id="q1-fb"></div>
                    </div>
                </div>

                <div class="mini-quiz">
                    <div class="quiz-header">Question 2</div>
                    <div class="quiz-body">
                        <p>If <code>(a,b)</code> and <code>(b,c)</code> are in <code>R*</code>, what must be added?</p>
                        <div id="q2-opts">
                            <div class="quiz-opt" onclick="answer(this, 'q2', true)"><span class="opt-circle">A</span><code>(a,c)</code></div>
                            <div class="quiz-opt" onclick="answer(this, 'q2', false)"><span class="opt-circle">B</span><code>(c,a)</code></div>
                            <div class="quiz-opt" onclick="answer(this, 'q2', false)"><span class="opt-circle">C</span><code>(b,b)</code> only</div>
                        </div>
                        <div class="quiz-feedback" id="q2-fb"></div>
                    </div>
                </div>

                <div class="mini-quiz">
                    <div class="quiz-header">Question 3</div>
                    <div class="quiz-body">
                        <p>Why are reflexive pairs added to <code>R*</code>?</p>
                        <div id="q3-opts">
                            <div class="quiz-opt" onclick="answer(this, 'q3', false)"><span class="opt-circle">A</span>To reverse every relation</div>
                            <div class="quiz-opt" onclick="answer(this, 'q3', false)"><span class="opt-circle">B</span>To remove duplicate symbols</div>
                            <div class="quiz-opt" onclick="answer(this, 'q3', true)"><span class="opt-circle">C</span>To relate each involved symbol to itself</div>
                        </div>
                        <div class="quiz-feedback" id="q3-fb"></div>
                    </div>
                </div>
            </section>
        </article>
    </div>

    <script>
        const sections = document.querySelectorAll('section[id]');
        const tocLinks = document.querySelectorAll('.toc-link');
        const bar = document.getElementById('toc-bar');
        const percent = document.getElementById('toc-percent');

        function updateToc() {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const progress = docHeight > 0 ? Math.min(100, Math.round((scrollTop / docHeight) * 100)) : 0;
            bar.style.width = progress + '%';
            percent.textContent = progress + '%';

            let active = '';
            sections.forEach(section => {
                if (scrollTop >= section.offsetTop - 120) {
                    active = section.id;
                }
            });
            tocLinks.forEach(link => {
                link.classList.toggle('active', link.getAttribute('href') === '#' + active);
            });
        }

        window.addEventListener('scroll', updateToc);
        updateToc();

        const RELATIONS = {
            r1: {
                nodes: ['a', 'b', 'c', 'd'],
                pairs: [
                    ['a', 'b'],
                    ['c', 'd'],
                    ['b', 'a'],
                    ['b', 'c'],
                    ['c', 'c']
                ]
            },
            short: {
                nodes: ['x', 'y', 'z'],
                pairs: [
                    ['x', 'y'],
                    ['y', 'z']
                ]
            },
            cycle: {
                nodes: ['p', 'q', 'r'],
                pairs: [
                    ['p', 'q'],
                    ['q', 'r'],
                    ['r', 'p']
                ]
            }
        };

        let currentRelation = null;
        let selectedPairs = new Set();
        let hintIndex = 0;

        function pairKey(pair) {
            return pair[0] + ',' + pair[1];
        }

        function pairText(key) {
            const parts = key.split(',');
            return '(' + parts[0] + ',' + parts[1] + ')';
        }

        function computeClosure(relation) {
            const closure = new Set(relation.pairs.map(pairKey));

            let changed = true;
            while (changed) {
                changed = false;
                const pairs = Array.from(closure).map(key => key.split(','));
                pairs.forEach(first => {
                    pairs.forEach(second => {
                        if (first[1] === second[0]) {
                            const next = first[0] + ',' + second[1];
                            if (!closure.has(next)) {
                                closure.add(next);
                                changed = true;
                            }
                        }
                    });
                });
            }

            relation.nodes.forEach(node => closure.add(node + ',' + node));
            return closure;
        }

        function allPossiblePairs(nodes) {
            const pairs = [];
            nodes.forEach(a => nodes.forEach(b => pairs.push(a + ',' + b)));
            return pairs;
        }

        function loadRelation() {
            const key = document.getElementById('relation-select').value;
            currentRelation = RELATIONS[key];
            selectedPairs = new Set();
            hintIndex = 0;
            renderGraph();
            renderMatrix();
            renderPairBank();
            showFeedback('', '');
            updateScore();
        }

        function resetClosure() {
            selectedPairs = new Set();
            hintIndex = 0;
            renderPairBank();
            renderMatrix();
            showFeedback('', '');
            updateScore();
        }

        function renderPairBank() {
            const bank = document.getElementById('pair-bank');
            bank.innerHTML = '';
            allPossiblePairs(currentRelation.nodes).forEach(key => {
                const chip = document.createElement('button');
                chip.type = 'button';
                chip.className = 'pair-chip' + (selectedPairs.has(key) ? ' selected' : '');
                chip.textContent = pairText(key);
                chip.onclick = () => togglePair(key);
                bank.appendChild(chip);
            });
        }

        function togglePair(key) {
            if (selectedPairs.has(key)) {
                selectedPairs.delete(key);
            } else {
                selectedPairs.add(key);
            }
            renderPairBank();
            renderMatrix();
            updateScore();
        }

        function renderMatrix(resultSet = null) {
            const wrap = document.getElementById('matrix-wrap');
            const base = new Set(currentRelation.pairs.map(pairKey));
            const closure = resultSet || selectedPairs;
            let html = '<table class="matrix"><thead><tr><th></th>';
            currentRelation.nodes.forEach(node => html += '<th>' + node + '</th>');
            html += '</tr></thead><tbody>';
            currentRelation.nodes.forEach(row => {
                html += '<tr><th>' + row + '</th>';
                currentRelation.nodes.forEach(col => {
                    const key = row + ',' + col;
                    const cls = base.has(key) ? 'base' : closure.has(key) ? 'added' : '';
                    html += '<td class="' + cls + '">' + (base.has(key) || closure.has(key) ? '1' : '0') + '</td>';
                });
                html += '</tr>';
            });
            html += '</tbody></table>';
            wrap.innerHTML = html;
        }

        function renderGraph() {
            const board = document.getElementById('graph-board');
            const svg = document.getElementById('graph-svg');
            board.querySelectorAll('.graph-node').forEach(node => node.remove());
            svg.innerHTML = '<defs><marker id="arrow" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse"><path d="M 0 0 L 10 5 L 0 10 z" fill="#6366F1"></path></marker></defs>';

            const positions = layoutNodes(currentRelation.nodes);
            currentRelation.pairs.forEach(pair => {
                const from = positions[pair[0]];
                const to = positions[pair[1]];
                if (pair[0] === pair[1]) {
                    svg.innerHTML += '<path d="M ' + (from.x + 8) + ' ' + (from.y - 8) + ' C ' + (from.x + 38) + ' ' + (from.y - 48) + ', ' + (from.x + 72) + ' ' + (from.y - 10) + ', ' + (from.x + 26) + ' ' + (from.y + 8) + '" stroke="#10B981" stroke-width="3" fill="none" marker-end="url(#arrow)"></path>';
                } else {
                    svg.innerHTML += '<line x1="' + from.x + '" y1="' + from.y + '" x2="' + to.x + '" y2="' + to.y + '" stroke="#6366F1" stroke-width="3" marker-end="url(#arrow)"></line>';
                }
            });

            currentRelation.nodes.forEach(node => {
                const pos = positions[node];
                const el = document.createElement('div');
                el.className = 'graph-node';
                el.style.left = 'calc(' + (pos.x / 420 * 100) + '% - 21px)';
                el.style.top = 'calc(' + (pos.y / 260 * 100) + '% - 21px)';
                el.textContent = node;
                board.appendChild(el);
            });
        }

        function layoutNodes(nodes) {
            const centerX = 210;
            const centerY = 130;
            const radiusX = 145;
            const radiusY = 82;
            const positions = {};
            nodes.forEach((node, index) => {
                const angle = (-Math.PI / 2) + (index * 2 * Math.PI / nodes.length);
                positions[node] = {
                    x: Math.round(centerX + Math.cos(angle) * radiusX),
                    y: Math.round(centerY + Math.sin(angle) * radiusY)
                };
            });
            return positions;
        }

        function checkClosure() {
            const closure = computeClosure(currentRelation);
            const allKeys = allPossiblePairs(currentRelation.nodes);
            let correct = 0;
            let wrong = 0;
            let missed = 0;

            document.querySelectorAll('.pair-chip').forEach(chip => {
                const key = chip.textContent.slice(1, -1);
                chip.classList.remove('correct', 'wrong', 'missed', 'selected');
                if (selectedPairs.has(key) && closure.has(key)) {
                    chip.classList.add('correct');
                    correct++;
                } else if (selectedPairs.has(key) && !closure.has(key)) {
                    chip.classList.add('wrong');
                    wrong++;
                } else if (!selectedPairs.has(key) && closure.has(key)) {
                    chip.classList.add('missed');
                    missed++;
                }
            });

            renderMatrix(closure);
            if (wrong === 0 && missed === 0) {
                showFeedback('ok', 'Perfect. You selected every pair in R*.');
            } else if (correct > 0) {
                showFeedback('partial', correct + ' correct, ' + missed + ' missed, ' + wrong + ' extra. Green is correct, yellow is missed, red is extra.');
            } else {
                showFeedback('bad', 'Not yet. Start with the original pairs, add transitive reachability, then add each reflexive pair.');
            }

            document.getElementById('lab-score').textContent = correct + ' / ' + closure.size + ' correct';
        }

        function revealHint() {
            const closure = Array.from(computeClosure(currentRelation)).sort();
            const next = closure.find(key => !selectedPairs.has(key));
            if (!next) {
                showFeedback('ok', 'No more hints needed. Your selection already contains all closure pairs.');
                return;
            }
            selectedPairs.add(next);
            hintIndex++;
            renderPairBank();
            renderMatrix();
            updateScore();
            showFeedback('partial', 'Hint ' + hintIndex + ': add ' + pairText(next) + ' to the closure.');
        }

        function updateScore() {
            document.getElementById('lab-score').textContent = selectedPairs.size + ' selected';
        }

        function showFeedback(kind, message) {
            const fb = document.getElementById('lab-feedback');
            fb.className = 'lab-feedback';
            fb.textContent = '';
            if (!message) return;
            fb.classList.add('show', kind);
            fb.textContent = message;
        }

        const answered = {};

        function answer(el, qid, correct) {
            if (answered[qid]) return;
            answered[qid] = true;
            const opts = document.querySelectorAll('#' + qid + '-opts .quiz-opt');
            opts.forEach(opt => opt.classList.add('answered'));
            el.classList.add(correct ? 'correct' : 'wrong');
            el.querySelector('.opt-circle').textContent = correct ? 'OK' : 'X';

            if (!correct) {
                opts.forEach(opt => {
                    if (opt.getAttribute('onclick') && opt.getAttribute('onclick').includes('true')) {
                        opt.classList.add('correct');
                        opt.querySelector('.opt-circle').textContent = 'OK';
                    }
                });
            }

            const fb = document.getElementById(qid + '-fb');
            fb.className = 'quiz-feedback show ' + (correct ? 'ok' : 'bad');
            fb.textContent = correct ? 'Correct. Nice work.' : 'Not quite. The correct answer is highlighted.';
        }

        loadRelation();
    </script>
</body>

</html>