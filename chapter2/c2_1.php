<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter 2.1 – CompileX</title>

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

        /* ── TOC ─────────────────────────── */
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

        /* ── Token class cards ─────────── */
        .token-classes-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin: 20px 0;
        }

        .tc-card {
            background: var(--card);
            border: 1.5px solid rgba(99, 102, 241, .12);
            border-radius: var(--radius);
            padding: 16px 18px;
            box-shadow: var(--shadow);
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }

        .tc-num {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .tc-body .tc-name {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 14px;
            color: var(--text);
            margin-bottom: 3px;
        }

        .tc-body .tc-desc {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.5;
        }

        .tc-body .tc-ex {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            margin-top: 6px;
            padding: 4px 8px;
            border-radius: 6px;
            display: inline-block;
        }

        /* per-class colours */
        .cls-1 .tc-num {
            background: #FEF3C7;
            color: #92400E;
        }

        .cls-1 .tc-ex {
            background: #FEF3C7;
            color: #92400E;
        }

        .cls-2 .tc-num {
            background: #EFF6FF;
            color: #1D4ED8;
        }

        .cls-2 .tc-ex {
            background: #EFF6FF;
            color: #1D4ED8;
        }

        .cls-3 .tc-num {
            background: #FFF1F2;
            color: #9F1239;
        }

        .cls-3 .tc-ex {
            background: #FFF1F2;
            color: #9F1239;
        }

        .cls-4 .tc-num {
            background: #F0FDF4;
            color: #065F46;
        }

        .cls-4 .tc-ex {
            background: #F0FDF4;
            color: #065F46;
        }

        .cls-5 .tc-num {
            background: #F3E8FF;
            color: #6B21A8;
        }

        .cls-5 .tc-ex {
            background: #F3E8FF;
            color: #6B21A8;
        }

        .cls-6 .tc-num {
            background: var(--purple-s);
            color: var(--purple);
        }

        .cls-6 .tc-ex {
            background: var(--purple-s);
            color: var(--purple);
        }

        .cls-x .tc-num {
            background: #F1F5F9;
            color: #475569;
        }

        .cls-x .tc-ex {
            background: #F1F5F9;
            color: #475569;
        }

        /* ── Annotated source line ─────── */
        .src-line-wrap {
            background: var(--code-bg);
            border-radius: var(--radius);
            padding: 24px 20px 16px;
            margin: 20px 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .25);
            overflow-x: auto;
        }

        .src-line {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            align-items: flex-end;
            margin-bottom: 12px;
        }

        .src-tok {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
        }

        .src-tok .word {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            padding: 4px 8px;
            border-radius: 6px;
            white-space: nowrap;
            font-weight: 600;
            cursor: default;
            transition: opacity .15s;
        }

        .src-tok .cls-tag {
            font-size: 10px;
            font-weight: 700;
            color: rgba(205, 214, 244, .4);
            font-family: 'JetBrains Mono', monospace;
        }

        .src-tok:hover .word {
            opacity: .75;
        }

        /* token colours matching classes */
        .w1 {
            background: rgba(245, 158, 11, .2);
            color: #FCD34D;
        }

        .w2 {
            background: rgba(59, 130, 246, .2);
            color: #93C5FD;
        }

        .w3 {
            background: rgba(239, 68, 68, .2);
            color: #FCA5A5;
        }

        .w4 {
            background: rgba(16, 185, 129, .2);
            color: #6EE7B7;
        }

        .w5 {
            background: rgba(167, 139, 250, .2);
            color: #C4B5FD;
        }

        .w6 {
            background: rgba(99, 102, 241, .2);
            color: #A5B4FC;
        }

        .wx {
            background: rgba(255, 255, 255, .06);
            color: rgba(205, 214, 244, .35);
            font-style: italic;
        }

        /* ── Token stream table ────────── */
        .token-stream-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
        }

        .token-stream-table th {
            background: var(--purple-s);
            color: var(--purple);
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            padding: 10px 14px;
            text-align: left;
        }

        .token-stream-table td {
            padding: 9px 14px;
            border-bottom: 1px solid #EEF2FF;
            color: var(--muted);
            vertical-align: middle;
        }

        .token-stream-table tr:hover td {
            background: #FAFAFF;
        }

        .token-stream-table .cls-cell {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 13px;
            text-align: center;
            width: 60px;
        }

        .token-stream-table .val-cell {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--text);
        }

        .token-stream-table .note-cell {
            font-size: 12px;
            color: var(--muted);
            font-style: italic;
        }

        .badge-cls {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
        }

        /* ── Symbol table diagram ─────── */
        .symtab-wrap {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin: 20px 0;
        }

        .symtab-header {
            background: var(--purple-s);
            padding: 12px 20px;
        }

        .symtab-header h4 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 14px;
            color: var(--purple);
            margin: 0;
        }

        .symtab-body {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .symtab-col {
            padding: 18px 20px;
        }

        .symtab-col:first-child {
            border-right: 1px solid #EEF2FF;
        }

        .symtab-col h5 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 13px;
            color: var(--text);
            margin-bottom: 10px;
        }

        .symtab-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 7px 10px;
            border: 1px solid rgba(99, 102, 241, .1);
            border-radius: 8px;
            margin-bottom: 6px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
        }

        .symtab-row .sr-name {
            color: var(--purple);
            font-weight: 700;
            min-width: 60px;
        }

        .symtab-row .sr-info {
            color: var(--muted);
            font-size: 11px;
            font-family: 'DM Sans', sans-serif;
        }

        /* ── Interactive tokeniser ─────── */
        .tok-wrap {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin: 24px 0;
        }

        .tok-header {
            background: linear-gradient(135deg, #6366F1, #818CF8);
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .tok-header h4 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: white;
            margin: 0;
        }

        .tok-body {
            padding: 24px;
        }

        .tok-textarea {
            width: 100%;
            min-height: 80px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            padding: 12px 16px;
            border: 1.5px solid rgba(99, 102, 241, .25);
            border-radius: 10px;
            outline: none;
            resize: vertical;
            color: var(--text);
            background: var(--bg);
            line-height: 1.7;
        }

        .tok-textarea:focus {
            border-color: var(--purple);
        }

        .tok-btn {
            margin-top: 12px;
            padding: 9px 22px;
            border-radius: 8px;
            border: none;
            background: var(--purple);
            color: white;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: background .18s;
        }

        .tok-btn:hover {
            background: var(--purple-d);
        }

        .tok-output {
            margin-top: 18px;
        }

        .tok-stream {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 10px;
        }

        .tok-chip {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            padding: 6px 12px;
            border-radius: 10px;
            border: 1.5px solid;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            cursor: default;
            transition: transform .15s;
        }

        .tok-chip:hover {
            transform: translateY(-2px);
        }

        .tok-chip .tc-v {
            font-weight: 700;
            font-size: 13px;
        }

        .tok-chip .tc-c {
            font-size: 10px;
            opacity: .7;
            margin-top: 2px;
        }

        .tok-chip.t1 {
            background: #FEF3C7;
            border-color: #F59E0B;
            color: #92400E;
        }

        .tok-chip.t2 {
            background: #EFF6FF;
            border-color: #3B82F6;
            color: #1D4ED8;
        }

        .tok-chip.t3 {
            background: #FFF1F2;
            border-color: #EF4444;
            color: #9F1239;
        }

        .tok-chip.t4 {
            background: #F0FDF4;
            border-color: #10B981;
            color: #065F46;
        }

        .tok-chip.t5 {
            background: #F3E8FF;
            border-color: #A855F7;
            color: #6B21A8;
        }

        .tok-chip.t6 {
            background: var(--purple-s);
            border-color: var(--purple);
            color: var(--purple);
        }

        .tok-chip.tx {
            background: #F1F5F9;
            border-color: #CBD5E1;
            color: #475569;
            font-style: italic;
        }

        .tok-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
            font-size: 13px;
        }

        .tok-table th {
            background: var(--code-bg);
            color: #A5B4FC;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            padding: 8px 12px;
            text-align: left;
            font-size: 12px;
        }

        .tok-table td {
            padding: 7px 12px;
            border-bottom: 1px solid #EEF2FF;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
        }

        .tok-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 14px;
        }

        .tok-leg-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            color: var(--muted);
        }

        .tok-leg-dot {
            width: 10px;
            height: 10px;
            border-radius: 3px;
        }

        /* ── Compare table ─────────────── */
        .compare-table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
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

        /* ── Chapter nav ─────────────────── */
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

        section:nth-child(7) {
            animation-delay: .35s;
        }

        section:nth-child(8) {
            animation-delay: .40s;
        }

        @media (max-width:768px) {
            .note-container {
                grid-template-columns: 1fr;
            }

            .toc {
                position: static;
            }

            .token-classes-grid {
                grid-template-columns: 1fr;
            }

            .symtab-body {
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
                <li><a href="#overview" class="toc-link">2.1 Overview</a></li>
                <li><a href="#tok-classes" class="toc-link">2.1.1 Token Classes (1–9)</a></li>
                <li><a href="#word-bound" class="toc-link">2.1.2 Word Boundaries</a></li>
                <li><a href="#tok-stream" class="toc-link">2.1.3 Token Stream Output</a></li>
                <li><a href="#symtab" class="toc-link">2.1.4 Symbol Table</a></li>
                <li><a href="#numconst" class="toc-link">2.1.5 Numeric Constants</a></li>
                <li><a href="#variations" class="toc-link">2.1.6 Design Variations</a></li>
                <li><a href="#interactive" class="toc-link">Interactive Tokeniser</a></li>
                <li><a href="#quiz" class="toc-link">Check Your Knowledge</a></li>
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
                <div class="chapter-tag">📘 Chapter 2 · Section 2.1</div>
                <h1>Lexical Tokens</h1>
                <div class="metadata">
                    <span>⏱ 14 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>🔤 Lexical Analysis</span>
                </div>
            </header>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 2.1 Overview                                   -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="overview">
                <h2><span class="sec-icon">🔤</span> 2.1 Lexical Tokens — Overview</h2>
                <p>
                    The first phase of a compiler is called <strong>lexical analysis</strong>. Because it scans the
                    input string <strong>without backtracking</strong> — reading each symbol exactly once and
                    processing it correctly — it is often called a <strong>lexical scanner</strong>.
                </p>
                <p>
                    Its job is to isolate the <strong>"words"</strong> in the input string. A word — also called a
                    <strong>lexeme</strong>, <strong>lexical item</strong>, or <strong>lexical token</strong> — is a
                    string of input characters taken as a single unit and passed on to the next phase of compilation.
                </p>

                <div class="note-box key">
                    <span class="box-icon">✅</span>
                    <div>
                        <strong>Output of this phase:</strong> A <em>stream of tokens</em>, one per word encountered.
                        Each token has two parts:
                        <ol style="margin: 8px 0 0 16px;">
                            <li><strong>Class</strong> — what <em>kind</em> of word it is (keyword, identifier, operator, …)</li>
                            <li><strong>Value</strong> — which specific <em>member</em> of that class (a pointer, a code, etc.)</li>
                        </ol>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 2.1.1 Token Classes                           -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="tok-classes">
                <h2><span class="sec-icon">🏷️</span> 2.1.1 The Nine Token Classes</h2>
                <p>
                    There are nine categories of input that the lexical scanner must handle.
                    Classes 1–6 produce tokens passed to the next phase. Classes 7–9 are
                    identified by the scanner but <em>discarded</em> — they do not appear in the token stream.
                </p>

                <div class="token-classes-grid">

                    <div class="tc-card cls-1">
                        <div class="tc-num">1</div>
                        <div class="tc-body">
                            <div class="tc-name">Keywords</div>
                            <div class="tc-desc">Reserved words with predefined meaning to the compiler. In Java and C, all keywords are reserved — programmers cannot use them as identifiers. (PL/1 is unusual in having no reserved words.)</div>
                            <span class="tc-ex">while &nbsp; if &nbsp; else &nbsp; for &nbsp; int &nbsp; float</span>
                        </div>
                    </div>

                    <div class="tc-card cls-2">
                        <div class="tc-num">2</div>
                        <div class="tc-body">
                            <div class="tc-name">Identifiers</div>
                            <div class="tc-desc">Names constructed by the programmer to attach a label to a variable, class, constant, or function. Stored in the symbol table; the token value is a pointer to the symbol table entry.</div>
                            <span class="tc-ex">x33 &nbsp; total &nbsp; calc &nbsp; myVar</span>
                        </div>
                    </div>

                    <div class="tc-card cls-3">
                        <div class="tc-num">3</div>
                        <div class="tc-body">
                            <div class="tc-name">Operators</div>
                            <div class="tc-desc">Symbols used for arithmetic, relational, or logical operations. Operators may consist of <em>more than one character</em> — e.g. <code>!=</code>, <code>&lt;=</code>, <code>==</code>.</div>
                            <span class="tc-ex">+ &nbsp; - &nbsp; = &nbsp; != &nbsp; &lt;= &nbsp; &&</span>
                        </div>
                    </div>

                    <div class="tc-card cls-4">
                        <div class="tc-num">4</div>
                        <div class="tc-body">
                            <div class="tc-name">Numeric Constants</div>
                            <div class="tc-desc">Integer and floating-point literals. Must be <em>converted from string form to numeric format</em> so arithmetic can be performed. May be stored in a constant table; the token value is a pointer to that entry.</div>
                            <span class="tc-ex">124 &nbsp; 12.35 &nbsp; 0.09E-23 &nbsp; 2.5e+33</span>
                        </div>
                    </div>

                    <div class="tc-card cls-5">
                        <div class="tc-num">5</div>
                        <div class="tc-body">
                            <div class="tc-name">Character Constants</div>
                            <div class="tc-desc">Single characters or strings of characters enclosed in quotes. The scanner identifies their boundaries using the quote delimiters.</div>
                            <span class="tc-ex">'a' &nbsp; "hello" &nbsp; "world\n"</span>
                        </div>
                    </div>

                    <div class="tc-card cls-6">
                        <div class="tc-num">6</div>
                        <div class="tc-body">
                            <div class="tc-name">Special Characters</div>
                            <div class="tc-desc">Characters used as delimiters by the grammar. These are generally single-character words. Some may have no value part — e.g. a left parenthesis is its own token class.</div>
                            <span class="tc-ex">. &nbsp; ( &nbsp; ) &nbsp; { &nbsp; } &nbsp; ; &nbsp; ,</span>
                        </div>
                    </div>

                    <div class="tc-card cls-x">
                        <div class="tc-num" style="background:#FAFAFA; color:#94A3B8; border:1px solid #E2E8F0;">7</div>
                        <div class="tc-body">
                            <div class="tc-name">Comments <span style="font-size:11px; color:var(--muted);">(discarded)</span></div>
                            <div class="tc-desc">Must be <em>detected and identified</em> by the scanner so they are correctly skipped, but are <strong>not passed on</strong> as tokens to the next phase.</div>
                            <span class="tc-ex" style="background:#F1F5F9; color:#94A3B8;">/* ... */ &nbsp; // comment</span>
                        </div>
                    </div>

                    <div class="tc-card cls-x">
                        <div class="tc-num" style="background:#FAFAFA; color:#94A3B8; border:1px solid #E2E8F0;">8</div>
                        <div class="tc-body">
                            <div class="tc-name">White Space <span style="font-size:11px; color:var(--muted);">(discarded)</span></div>
                            <div class="tc-desc">Spaces and tabs serve only as <em>delimiters</em> between tokens in most languages. They are identified but <strong>not put out</strong> as tokens.</div>
                            <span class="tc-ex" style="background:#F1F5F9; color:#94A3B8;">space &nbsp; tab</span>
                        </div>
                    </div>

                    <div class="tc-card cls-x" style="grid-column: 1 / -1;">
                        <div class="tc-num" style="background:#FAFAFA; color:#94A3B8; border:1px solid #E2E8F0;">9</div>
                        <div class="tc-body">
                            <div class="tc-name">Newline <span style="font-size:11px; color:var(--muted);">(depends on language)</span></div>
                            <div class="tc-desc">In <em>free-format</em> languages (like Java), newline characters are ignored just like white space. In languages where layout matters (like Python or FORTRAN), a newline token <em>is</em> produced. Java is free-format.</div>
                            <span class="tc-ex" style="background:#F1F5F9; color:#94A3B8;">\n &nbsp; (ignored in Java)</span>
                        </div>
                    </div>

                </div>

                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>
                        <strong>Keywords vs Identifiers:</strong> Keywords have a <em>predefined meaning</em> to the compiler.
                        <strong>Reserved words</strong> are keywords that are <em>unavailable</em> to the programmer as identifiers.
                        In Java and C, every keyword is reserved. PL/1 is a notable exception — it has no reserved words at all.
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 2.1.2 Word Boundaries                         -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="word-bound">
                <h2><span class="sec-icon">🔲</span> 2.1.2 Word Boundaries — Annotated Example</h2>
                <p>
                    The following Java source line illustrates exactly where the scanner finds word boundaries
                    and which class each token belongs to. Hover over any token to see its class.
                </p>

                <div class="src-line-wrap">
                    <div style="font-size:11px; color:rgba(205,214,244,.35); margin-bottom:10px; font-family:'JetBrains Mono',monospace; letter-spacing:.5px; text-transform:uppercase;">Source input</div>
                    <div class="src-line">
                        <div class="src-tok"><span class="word w1">while</span><span class="cls-tag">1</span></div>
                        <div class="src-tok"><span class="word w6">(</span><span class="cls-tag">6</span></div>
                        <div class="src-tok"><span class="word w2">x33</span><span class="cls-tag">2</span></div>
                        <div class="src-tok"><span class="word w3">&lt;=</span><span class="cls-tag">3</span></div>
                        <div class="src-tok"><span class="word w4">2.5e+33</span><span class="cls-tag">4</span></div>
                        <div class="src-tok"><span class="word w3">-</span><span class="cls-tag">3</span></div>
                        <div class="src-tok"><span class="word w2">total</span><span class="cls-tag">2</span></div>
                        <div class="src-tok"><span class="word w6">)</span><span class="cls-tag">6</span></div>
                        <div class="src-tok"><span class="word w2">calc</span><span class="cls-tag">2</span></div>
                        <div class="src-tok"><span class="word w6">(</span><span class="cls-tag">6</span></div>
                        <div class="src-tok"><span class="word w2">x33</span><span class="cls-tag">2</span></div>
                        <div class="src-tok"><span class="word w6">)</span><span class="cls-tag">6</span></div>
                        <div class="src-tok"><span class="word w6">;</span><span class="cls-tag">6</span></div>
                        <div class="src-tok"><span class="word wx">//!</span><span class="cls-tag" style="color:rgba(148,163,184,.4);">ignored</span></div>
                    </div>
                    <div style="display:flex; flex-wrap:wrap; gap:10px; margin-top:4px;">
                        <span style="font-size:11px; color:rgba(252,211,77,.8); font-family:'JetBrains Mono',monospace;">■ 1 keyword</span>
                        <span style="font-size:11px; color:rgba(147,197,253,.8); font-family:'JetBrains Mono',monospace;">■ 2 identifier</span>
                        <span style="font-size:11px; color:rgba(252,165,165,.8); font-family:'JetBrains Mono',monospace;">■ 3 operator</span>
                        <span style="font-size:11px; color:rgba(110,231,183,.8); font-family:'JetBrains Mono',monospace;">■ 4 numeric const</span>
                        <span style="font-size:11px; color:rgba(165,180,252,.8); font-family:'JetBrains Mono',monospace;">■ 6 special char</span>
                        <span style="font-size:11px; color:rgba(148,163,184,.5); font-family:'JetBrains Mono',monospace;">■ ignored</span>
                    </div>
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Lexical analysis does NOT check syntax.</strong> The scanner processes tokens one by one
                        without understanding their grammatical arrangement. An input like
                        <code>} while if ( {</code> would produce <em>five valid tokens</em> — syntax errors are only
                        caught in the next phase (syntax analysis / parser).
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 2.1.3 Token Stream Output                     -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="tok-stream">
                <h2><span class="sec-icon">📤</span> 2.1.3 The Token Stream</h2>
                <p>
                    For the example source line <code>while ( x33 &lt;= 2.5e+33 - total ) calc ( x33 ) ; //!</code>,
                    the scanner produces the following stream of tokens. Notice the comment <code>//!</code>
                    is silently discarded and produces no token.
                </p>

                <table class="token-stream-table">
                    <thead>
                        <tr>
                            <th style="width:80px;">Class</th>
                            <th>Value</th>
                            <th>Explanation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="cls-cell"><span class="badge-cls" style="background:#FEF3C7;color:#92400E;">1</span></td>
                            <td class="val-cell">[code for <strong>while</strong>]</td>
                            <td class="note-cell">Keyword — predefined token code, no symbol table lookup needed</td>
                        </tr>
                        <tr>
                            <td class="cls-cell"><span class="badge-cls" style="background:var(--purple-s);color:var(--purple);">6</span></td>
                            <td class="val-cell">[code for <strong>(</strong>]</td>
                            <td class="note-cell">Special character — some implementations need no value at all</td>
                        </tr>
                        <tr>
                            <td class="cls-cell"><span class="badge-cls" style="background:#EFF6FF;color:#1D4ED8;">2</span></td>
                            <td class="val-cell">[ptr → symbol table: <strong>x33</strong>]</td>
                            <td class="note-cell">Identifier — value is a pointer to its symbol table entry</td>
                        </tr>
                        <tr>
                            <td class="cls-cell"><span class="badge-cls" style="background:#FFF1F2;color:#9F1239;">3</span></td>
                            <td class="val-cell">[code for <strong>&lt;=</strong>]</td>
                            <td class="note-cell">Operator — multi-character operator recognised as a single token</td>
                        </tr>
                        <tr>
                            <td class="cls-cell"><span class="badge-cls" style="background:#F0FDF4;color:#065F46;">4</span></td>
                            <td class="val-cell">[ptr → constant table: <strong>2.5e+33</strong>]</td>
                            <td class="note-cell">Numeric constant — stored in constant table; value is a pointer</td>
                        </tr>
                        <tr>
                            <td class="cls-cell"><span class="badge-cls" style="background:#FFF1F2;color:#9F1239;">3</span></td>
                            <td class="val-cell">[code for <strong>-</strong>]</td>
                            <td class="note-cell">Operator</td>
                        </tr>
                        <tr>
                            <td class="cls-cell"><span class="badge-cls" style="background:#EFF6FF;color:#1D4ED8;">2</span></td>
                            <td class="val-cell">[ptr → symbol table: <strong>total</strong>]</td>
                            <td class="note-cell">Identifier</td>
                        </tr>
                        <tr>
                            <td class="cls-cell"><span class="badge-cls" style="background:var(--purple-s);color:var(--purple);">6</span></td>
                            <td class="val-cell">[code for <strong>)</strong>]</td>
                            <td class="note-cell">Special character</td>
                        </tr>
                        <tr>
                            <td class="cls-cell"><span class="badge-cls" style="background:#EFF6FF;color:#1D4ED8;">2</span></td>
                            <td class="val-cell">[ptr → symbol table: <strong>calc</strong>]</td>
                            <td class="note-cell">Identifier — a different symbol table entry from x33</td>
                        </tr>
                        <tr>
                            <td class="cls-cell"><span class="badge-cls" style="background:var(--purple-s);color:var(--purple);">6</span></td>
                            <td class="val-cell">[code for <strong>(</strong>]</td>
                            <td class="note-cell">Special character</td>
                        </tr>
                        <tr>
                            <td class="cls-cell"><span class="badge-cls" style="background:#EFF6FF;color:#1D4ED8;">2</span></td>
                            <td class="val-cell">[ptr → symbol table: <strong>x33</strong>]</td>
                            <td class="note-cell">Same identifier x33 — same pointer as the first x33 token</td>
                        </tr>
                        <tr>
                            <td class="cls-cell"><span class="badge-cls" style="background:var(--purple-s);color:var(--purple);">6</span></td>
                            <td class="val-cell">[code for <strong>)</strong>]</td>
                            <td class="note-cell">Special character</td>
                        </tr>
                        <tr>
                            <td class="cls-cell"><span class="badge-cls" style="background:var(--purple-s);color:var(--purple);">6</span></td>
                            <td class="val-cell">[code for <strong>;</strong>]</td>
                            <td class="note-cell">Special character</td>
                        </tr>
                        <tr style="background:#F8FAFC; opacity:.6;">
                            <td class="cls-cell" style="color:#94A3B8; font-style:italic; font-size:11px;">—</td>
                            <td class="val-cell" style="color:#94A3B8; font-style:italic;">//!</td>
                            <td class="note-cell" style="color:#94A3B8;">Comment — detected and discarded. Produces NO token.</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        <strong>No value needed for some tokens:</strong> A left parenthesis is inherently its own token class — there's only one kind of <code>(</code>, so no value is needed. Contrast this with identifiers, where the value (symbol table pointer) tells you <em>which</em> identifier it is.
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 2.1.4 Symbol Table                            -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="symtab">
                <h2><span class="sec-icon">📋</span> 2.1.4 The Symbol Table</h2>
                <p>
                    During lexical analysis, a <strong>symbol table</strong> is constructed as identifiers are encountered.
                    It stores each identifier <em>exactly once</em>, regardless of how many times it appears in the source.
                    It also records attributes about each identifier.
                </p>

                <div class="symtab-wrap">
                    <div class="symtab-header">
                        <h4>Symbol Table — from the example source line</h4>
                    </div>
                    <div class="symtab-body">
                        <div class="symtab-col">
                            <h5>📌 Entries stored</h5>
                            <div class="symtab-row">
                                <span class="sr-name">x33</span>
                                <span class="sr-info">type: float · declared in: main · 2 occurrences</span>
                            </div>
                            <div class="symtab-row">
                                <span class="sr-name">total</span>
                                <span class="sr-info">type: unknown yet · 1 occurrence</span>
                            </div>
                            <div class="symtab-row">
                                <span class="sr-name">calc</span>
                                <span class="sr-info">type: function · 1 occurrence</span>
                            </div>
                            <div style="margin-top:10px; font-size:12px; color:var(--muted);">
                                x33 appears twice in source but is stored only once. Both token values point to the same entry.
                            </div>
                        </div>
                        <div class="symtab-col">
                            <h5>⚙️ Implementation notes</h5>
                            <div class="note-box pro" style="margin:0; font-size:13px;">
                                <span class="box-icon" style="font-size:16px;">🧩</span>
                                <div>
                                    <strong>Data structure:</strong> Often a <em>binary search tree</em> or <em>hash table</em> for efficient searching.
                                    <br><br>
                                    <strong>Block-structured languages</strong> (Java, C, Algol): the same identifier can have different declarations in different blocks. Two strategies: (1) separate symbol table per block, or (2) block scopes encoded in a single table. This is typically handled during <em>syntax analysis</em> — the scanner just stores the identifier text and returns a pointer.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 2.1.5 Numeric Constants                       -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="numconst">
                <h2><span class="sec-icon">🔢</span> 2.1.5 Numeric Constants — Conversion</h2>
                <p>
                    Numeric constants must be <strong>converted from string form to a numeric format</strong> before
                    arithmetic can be performed. The compiler initially sees all input as characters — the string
                    <code>"3.4e+6"</code> is a sequence of six characters, not a floating-point number.
                </p>
                <p>
                    Converting this to an actual floating-point or integer binary value is non-trivial. Most compiler
                    writers use <strong>library routines</strong> to handle this conversion. The converted value is
                    stored in a <strong>constant table</strong>, and the token value is a pointer to that entry.
                </p>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Input string</th>
                            <th>Interpretation</th>
                            <th>Stored as</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>124</code></td>
                            <td>Integer literal</td>
                            <td>32-bit int binary: 0x0000007C</td>
                        </tr>
                        <tr>
                            <td><code>12.35</code></td>
                            <td>Floating-point literal</td>
                            <td>IEEE 754 float</td>
                        </tr>
                        <tr>
                            <td><code>0.09E-23</code></td>
                            <td>Scientific notation float</td>
                            <td>IEEE 754 double</td>
                        </tr>
                        <tr>
                            <td><code>2.5e+33</code></td>
                            <td>Scientific notation float</td>
                            <td>IEEE 754 double</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Case sensitivity:</strong> If the source language is <em>not</em> case sensitive, the scanner must normalise all alphabetic characters to upper or lower case (often via a preprocessor) before tokenisation. <strong>Java is case sensitive</strong> — <code>While</code> and <code>while</code> are different tokens.
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 2.1.6 Design Variations                       -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="variations">
                <h2><span class="sec-icon">⚙️</span> 2.1.6 Design Variations</h2>
                <p>
                    The basic scheme of one token per word has variations that can improve efficiency
                    or simplify later phases:
                </p>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Variation</th>
                            <th>Description</th>
                            <th>Example</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Combined assignment token</td>
                            <td>When an identifier is immediately followed by <code>=</code>, emit a single <em>assignment token</em> whose value is the symbol table pointer for the identifier. Reduces two tokens to one.</td>
                            <td><code>x =</code> → one token (class: assign, value: ptr to x)</td>
                        </tr>
                        <tr>
                            <td>Keyword as its own class</td>
                            <td>Each keyword gets a unique token class (rather than all keywords sharing class 1). Increases the number of classes significantly but simplifies the syntax analysis phase.</td>
                            <td><code>while</code> → class WHILE; <code>if</code> → class IF</td>
                        </tr>
                        <tr>
                            <td>No value for unique tokens</td>
                            <td>Single-character specials and keywords have only one possible value — so no value part is needed, saving space.</td>
                            <td><code>(</code> → just class 6-LPAREN, no value</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        <strong>Scanner stores into string space:</strong> In block-structured languages, the scanner can simply store each identifier text into a <em>string space array</em> and return a pointer to its first character. The symbol table itself can be built during the parse phase when block boundaries are known.
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Interactive Tokeniser                         -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="interactive">
                <h2><span class="sec-icon">▶️</span> Interactive Java Tokeniser</h2>
                <p>
                    Type (or paste) a Java source snippet below and click <strong>Tokenise</strong>.
                    The scanner will identify each word boundary and classify every token according
                    to the 9-class scheme from Section 2.1.
                </p>

                <div class="tok-wrap">
                    <div class="tok-header">
                        <h4>🔤 Lexical Scanner Simulator</h4>
                        <span style="font-size:12px; color:rgba(255,255,255,.7);">Classes 7–9 are identified but discarded</span>
                    </div>
                    <div class="tok-body">
                        <textarea class="tok-textarea" id="tok-input" spellcheck="false" placeholder="e.g.  while ( x33 <= 2.5e+33 - total ) calc ( x33 ) ; //!">while ( x33 <= 2.5e+33 - total ) calc ( x33 ) ; //!</textarea>
                        <button class="tok-btn" onclick="tokenise()">🔍 Tokenise</button>
                        <div style="margin-top:8px; display:flex; gap:8px; flex-wrap:wrap;">
                            <button onclick="setExample(0)" style="font-size:12px; padding:4px 12px; border-radius:6px; border:1px solid rgba(99,102,241,.3); background:transparent; color:var(--purple); cursor:pointer; font-family:'DM Sans',sans-serif;">Example 1</button>
                            <button onclick="setExample(1)" style="font-size:12px; padding:4px 12px; border-radius:6px; border:1px solid rgba(99,102,241,.3); background:transparent; color:var(--purple); cursor:pointer; font-family:'DM Sans',sans-serif;">Example 2</button>
                            <button onclick="setExample(2)" style="font-size:12px; padding:4px 12px; border-radius:6px; border:1px solid rgba(99,102,241,.3); background:transparent; color:var(--purple); cursor:pointer; font-family:'DM Sans',sans-serif;">Example 3</button>
                        </div>

                        <div class="tok-output" id="tok-output" style="display:none;">
                            <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:13px; color:var(--text); margin-bottom:10px;">Token stream:</div>
                            <div class="tok-stream" id="tok-chips"></div>

                            <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:13px; color:var(--text); margin:18px 0 8px;">Token table:</div>
                            <table class="tok-table" id="tok-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Class</th>
                                        <th>Class Name</th>
                                        <th>Value / Lexeme</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody id="tok-tbody"></tbody>
                            </table>

                            <div class="tok-legend">
                                <div class="tok-leg-item">
                                    <div class="tok-leg-dot" style="background:#F59E0B;"></div> 1 Keyword
                                </div>
                                <div class="tok-leg-item">
                                    <div class="tok-leg-dot" style="background:#3B82F6;"></div> 2 Identifier
                                </div>
                                <div class="tok-leg-item">
                                    <div class="tok-leg-dot" style="background:#EF4444;"></div> 3 Operator
                                </div>
                                <div class="tok-leg-item">
                                    <div class="tok-leg-dot" style="background:#10B981;"></div> 4 Numeric const
                                </div>
                                <div class="tok-leg-item">
                                    <div class="tok-leg-dot" style="background:#A855F7;"></div> 5 Char constant
                                </div>
                                <div class="tok-leg-item">
                                    <div class="tok-leg-dot" style="background:#6366F1;"></div> 6 Special char
                                </div>
                                <div class="tok-leg-item">
                                    <div class="tok-leg-dot" style="background:#94A3B8;"></div> ignored
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="c2_0_3.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>2.0 Formal Languages</div>
                </a>
                <a href="ch2_s2.php" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next section</span>2.2 Implementing a Scanner</div>
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

        // ── Interactive tokeniser ────────────────────────────────
        const KEYWORDS = new Set(['while', 'if', 'else', 'for', 'int', 'float', 'return', 'class',
            'public', 'static', 'void', 'new', 'true', 'false', 'null', 'this', 'do', 'break',
            'continue', 'import', 'package', 'try', 'catch', 'finally', 'throw', 'throws',
            'String', 'boolean', 'double', 'long', 'char', 'byte', 'short', 'extends', 'implements',
            'interface', 'abstract', 'final', 'super'
        ]);
        const OPERATORS = new Set(['+', '-', '*', '/', '=', '<', '>', '!', '&', '|', '^', '~', '%',
            '<=', '>=', '==', '!=', '&&', '||', '++', '--', '+=', '-=', '*=', '/=', '%=', '->'
        ]);
        const SPECIALS = new Set(['.', '(', ')', '[', ']', '{', '}', ';', ',', ':', '?', '@']);

        const examples = [
            'while ( x33 <= 2.5e+33 - total ) calc ( x33 ) ; //!',
            'for (i=1; i<=fin+3.5e6; i=i*3)\n  ac = ac + /* incr */ 1;',
            'if (sum != 133) /* sum=133 */ x = 0;'
        ];

        function setExample(i) {
            document.getElementById('tok-input').value = examples[i];
            tokenise();
        }

        const classInfo = {
            1: {
                name: 'Keyword',
                chipClass: 't1',
                colour: '#92400E'
            },
            2: {
                name: 'Identifier',
                chipClass: 't2',
                colour: '#1D4ED8'
            },
            3: {
                name: 'Operator',
                chipClass: 't3',
                colour: '#9F1239'
            },
            4: {
                name: 'Numeric const',
                chipClass: 't4',
                colour: '#065F46'
            },
            5: {
                name: 'Char constant',
                chipClass: 't5',
                colour: '#6B21A8'
            },
            6: {
                name: 'Special char',
                chipClass: 't6',
                colour: '#4338CA'
            },
            7: {
                name: 'Comment',
                chipClass: 'tx',
                colour: '#64748B'
            },
            8: {
                name: 'White space',
                chipClass: 'tx',
                colour: '#94A3B8'
            },
        };

        function tokenise() {
            const src = document.getElementById('tok-input').value;
            const tokens = lex(src);
            const chips = document.getElementById('tok-chips');
            const tbody = document.getElementById('tok-tbody');
            chips.innerHTML = '';
            tbody.innerHTML = '';

            let n = 0;
            tokens.forEach(t => {
                const info = classInfo[t.cls];
                // chip
                const chip = document.createElement('div');
                chip.className = 'tok-chip ' + info.chipClass;
                chip.innerHTML = `<span class="tc-v">${escHtml(t.val)}</span><span class="tc-c">cls ${t.cls}</span>`;
                chip.title = `Class ${t.cls}: ${info.name}`;
                chips.appendChild(chip);

                // table row
                if (t.cls <= 6) n++;
                const tr = document.createElement('tr');
                tr.style.opacity = t.cls > 6 ? '.45' : '1';
                const noteMap = {
                    1: 'Predefined meaning',
                    2: '→ symbol table ptr',
                    3: 'Operator code',
                    4: '→ constant table ptr',
                    5: 'String/char literal',
                    6: 'Delimiter code',
                    7: 'Discarded — no token output',
                    8: 'Discarded — no token output'
                };
                tr.innerHTML = `
          <td style="color:var(--muted); font-size:12px; font-family:'JetBrains Mono',monospace;">${t.cls <= 6 ? n : '—'}</td>
          <td><span class="badge-cls tok-chip ${info.chipClass}" style="padding:2px 8px; font-size:11px;">${t.cls}</span></td>
          <td style="color:var(--text); font-size:13px;">${info.name}</td>
          <td style="font-family:'JetBrains Mono',monospace; font-size:12px; color:var(--purple);">${escHtml(t.val)}</td>
          <td style="font-size:12px; color:var(--muted); font-style:italic;">${noteMap[t.cls] || ''}</td>
        `;
                tbody.appendChild(tr);
            });

            document.getElementById('tok-output').style.display = 'block';
        }

        function escHtml(s) {
            return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        function lex(src) {
            const tokens = [];
            let i = 0;
            while (i < src.length) {
                // whitespace
                if (/[ \t\r\n]/.test(src[i])) {
                    i++;
                    continue;
                }
                // line comment
                if (src[i] === '/' && src[i + 1] === '/') {
                    let s = i;
                    while (i < src.length && src[i] !== '\n') i++;
                    tokens.push({
                        cls: 7,
                        val: src.slice(s, i)
                    });
                    continue;
                }
                // block comment
                if (src[i] === '/' && src[i + 1] === '*') {
                    let s = i;
                    i += 2;
                    while (i < src.length && !(src[i - 1] === '*' && src[i] === '/')) i++;
                    i++;
                    tokens.push({
                        cls: 7,
                        val: src.slice(s, i)
                    });
                    continue;
                }
                // string literal
                if (src[i] === '"') {
                    let s = i;
                    i++;
                    while (i < src.length && src[i] !== '"') {
                        if (src[i] === '\\') i++;
                        i++;
                    }
                    i++;
                    tokens.push({
                        cls: 5,
                        val: src.slice(s, i)
                    });
                    continue;
                }
                // char literal
                if (src[i] === "'") {
                    let s = i;
                    i++;
                    while (i < src.length && src[i] !== "'") {
                        if (src[i] === '\\') i++;
                        i++;
                    }
                    i++;
                    tokens.push({
                        cls: 5,
                        val: src.slice(s, i)
                    });
                    continue;
                }
                // numeric constant
                if (/[0-9]/.test(src[i]) || (src[i] === '.' && /[0-9]/.test(src[i + 1] || ''))) {
                    let s = i;
                    while (i < src.length && /[0-9]/.test(src[i])) i++;
                    if (i < src.length && src[i] === '.') {
                        i++;
                        while (i < src.length && /[0-9]/.test(src[i])) i++;
                    }
                    if (i < src.length && /[eE]/.test(src[i])) {
                        i++;
                        if (/[+\-]/.test(src[i] || '')) i++;
                        while (i < src.length && /[0-9]/.test(src[i])) i++;
                    }
                    tokens.push({
                        cls: 4,
                        val: src.slice(s, i)
                    });
                    continue;
                }
                // identifier or keyword
                if (/[a-zA-Z_$]/.test(src[i])) {
                    let s = i;
                    while (i < src.length && /[a-zA-Z0-9_$]/.test(src[i])) i++;
                    const word = src.slice(s, i);
                    tokens.push({
                        cls: KEYWORDS.has(word) ? 1 : 2,
                        val: word
                    });
                    continue;
                }
                // two-char operators
                const two = src.slice(i, i + 2);
                if (OPERATORS.has(two)) {
                    tokens.push({
                        cls: 3,
                        val: two
                    });
                    i += 2;
                    continue;
                }
                // single-char operator
                if (OPERATORS.has(src[i])) {
                    tokens.push({
                        cls: 3,
                        val: src[i]
                    });
                    i++;
                    continue;
                }
                // special
                if (SPECIALS.has(src[i])) {
                    tokens.push({
                        cls: 6,
                        val: src[i]
                    });
                    i++;
                    continue;
                }
                // unknown — skip
                i++;
            }
            return tokens;
        }

        // run on load
        tokenise();

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
            fb.innerHTML = correct ?
                '✅ <strong>Correct!</strong> Well done.' :
                '❌ <strong>Not quite.</strong> The correct answer is highlighted above.';
        }
    </script>

</body>

</html>