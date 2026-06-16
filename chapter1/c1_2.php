<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter 1.2 – CompileX</title>

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
            display: flex;
            align-items: center;
            gap: 8px;
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
            margin-bottom: 4px;
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
            background: transparent;
            padding: 0;
            border-radius: 0;
        }

        .kw {
            color: #CBA6F7;
        }

        .cm {
            color: #6C7086;
            font-style: italic;
        }

        .num {
            color: #FAB387;
        }

        .op {
            color: #94E2D5;
        }

        .reg {
            color: #F38BA8;
        }

        .str {
            color: #A6E3A1;
        }

        .lbl {
            color: #F9E2AF;
        }

        /* ── Token list ─────────────────── */
        .token-stream {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 16px 0;
        }

        .token {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            font-weight: 500;
            border: 1px solid;
        }

        .token.keyword {
            background: #F3E8FF;
            color: #7C3AED;
            border-color: #C4B5FD;
        }

        .token.ident {
            background: #EFF6FF;
            color: #2563EB;
            border-color: #93C5FD;
        }

        .token.op {
            background: #FFF7ED;
            color: #C2410C;
            border-color: #FDBA74;
        }

        .token.num {
            background: #F0FDF4;
            color: #15803D;
            border-color: #86EFAC;
        }

        .token.assign {
            background: #FFF1F2;
            color: #BE123C;
            border-color: #FDA4AF;
        }

        .token.special {
            background: #F8FAFC;
            color: #475569;
            border-color: #CBD5E1;
        }

        .token.comment {
            background: #F1F5F9;
            color: #94A3B8;
            border-color: #CBD5E1;
            font-style: italic;
        }

        /* ── Atom table ─────────────────── */
        .atom-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin: 16px 0;
        }

        .atom-row {
            display: flex;
            align-items: center;
            gap: 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
        }

        .atom-paren {
            color: rgba(205, 214, 244, .4);
            font-size: 15px;
            padding: 0 2px;
        }

        .atom-op {
            background: rgba(99, 102, 241, .2);
            color: #A5B4FC;
            padding: 4px 10px;
            border-radius: 6px 0 0 6px;
            font-weight: 600;
            min-width: 68px;
            text-align: center;
        }

        .atom-operand {
            background: rgba(255, 255, 255, .04);
            color: #CDD6F4;
            padding: 4px 10px;
            border-left: 1px solid rgba(255, 255, 255, .06);
        }

        .atom-result {
            background: rgba(16, 185, 129, .12);
            color: #6EE7B7;
            padding: 4px 10px;
            border-left: 1px solid rgba(255, 255, 255, .06);
            border-radius: 0 6px 6px 0;
            font-weight: 600;
        }

        .atom-wrap {
            background: var(--code-bg);
            border-radius: var(--radius);
            padding: 16px 20px;
            margin: 16px 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .2);
        }

        /* ── Phases pipeline ────────────── */
        .pipeline {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0;
            margin: 28px 0;
        }

        .pipe-stage {
            width: 100%;
            max-width: 520px;
        }

        .pipe-box {
            background: var(--card);
            border: 1.5px solid rgba(99, 102, 241, .2);
            border-radius: var(--radius);
            padding: 14px 20px;
            box-shadow: var(--shadow);
            transition: border-color .2s, transform .2s;
            cursor: default;
        }

        .pipe-box:hover {
            border-color: var(--purple);
            transform: translateX(4px);
        }

        .pipe-name {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 14px;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 3px;
        }

        .pipe-badge {
            font-size: 10px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 10px;
            background: var(--purple-s);
            color: var(--purple);
        }

        .pipe-desc {
            font-size: 13px;
            color: var(--muted);
        }

        .pipe-io {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11.5px;
            color: var(--purple);
            margin-top: 6px;
        }

        .pipe-arrow {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 4px 0;
        }

        .pipe-arrow-line {
            width: 2px;
            height: 18px;
            background: linear-gradient(to bottom, var(--purple), rgba(99, 102, 241, .3));
        }

        .pipe-arrow-head {
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 8px solid rgba(99, 102, 241, .5);
        }

        .pipe-output-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            color: var(--muted);
            background: var(--purple-s);
            padding: 2px 8px;
            border-radius: 6px;
            margin: 2px 0;
        }

        .pipe-optional {
            opacity: .65;
        }

        .pipe-optional .pipe-badge {
            background: #FEF9C3;
            color: #854D0E;
        }

        /* ── Syntax tree ────────────────── */
        .tree-wrap {
            display: flex;
            justify-content: center;
            margin: 24px 0;
            overflow-x: auto;
        }

        .tree-wrap svg text {
            font-family: 'JetBrains Mono', monospace;
        }

        /* ── Two-col layout ─────────────── */
        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin: 16px 0;
        }

        /* ── Compare table ───────────────── */
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

        /* ── Sample problem ─────────────── */
        .sample-problem {
            background: var(--card);
            border: 1.5px solid rgba(99, 102, 241, .2);
            border-radius: var(--radius);
            margin: 28px 0;
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
            margin-bottom: 16px;
        }

        .sp-solution-label {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--green);
            margin-bottom: 10px;
        }

        /* ── Optimisation before/after ──── */
        .opt-grid {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 12px;
            align-items: center;
            margin: 16px 0;
        }

        .opt-arrow {
            font-size: 24px;
            color: var(--green);
            text-align: center;
            font-weight: 700;
        }

        .opt-label {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }

        .opt-before .opt-label {
            color: var(--red);
        }

        .opt-after .opt-label {
            color: var(--green);
        }

        .highlight-remove {
            background: rgba(239, 68, 68, .15);
            border-left: 3px solid var(--red);
        }

        .highlight-keep {
            background: rgba(16, 185, 129, .1);
        }

        /* ── Pass diagram ────────────────── */
        .pass-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin: 20px 0;
        }

        .pass-card {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            padding: 18px;
            box-shadow: var(--shadow);
        }

        .pass-card h5 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 14px;
            color: var(--text);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pass-card p {
            font-size: 13px;
            color: var(--muted);
            margin: 0;
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

        section:nth-child(9) {
            animation-delay: .45s;
        }

        @media (max-width: 768px) {
            .note-container {
                grid-template-columns: 1fr;
            }

            .toc {
                position: static;
            }

            .two-col,
            .pass-grid,
            .opt-grid {
                grid-template-columns: 1fr;
            }

            .opt-arrow {
                transform: rotate(90deg);
            }
        }
    </style>
</head>

<body>

    <?php include 'navbar_c1.php'; ?>

    <div class="note-container">

        <!-- ── Sidebar TOC ─────────────────────────────────── -->
        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#overview" class="toc-link">1.2 Overview</a></li>
                <li><a href="#lexical" class="toc-link">1.2.1 Lexical Analysis</a></li>
                <li><a href="#sp-a" class="toc-link sub">↳ Sample Problem (a)</a></li>
                <li><a href="#syntax" class="toc-link">1.2.2 Syntax Analysis</a></li>
                <li><a href="#sp-b" class="toc-link sub">↳ Sample Problem (b)</a></li>
                <li><a href="#sp-c" class="toc-link sub">↳ Sample Problem (c)</a></li>
                <li><a href="#sp-d" class="toc-link sub">↳ Sample Problem (d)</a></li>
                <li><a href="#semantic" class="toc-link">Semantic Analysis</a></li>
                <li><a href="#global-opt" class="toc-link">1.2.3 Global Optimisation</a></li>
                <li><a href="#codegen" class="toc-link">1.2.4 Code Generation</a></li>
                <li><a href="#sp-e" class="toc-link sub">↳ Sample Problem (e)</a></li>
                <li><a href="#local-opt" class="toc-link">1.2.5 Local Optimisation</a></li>
                <li><a href="#pipeline" class="toc-link">Full Pipeline Diagram</a></li>
                <li><a href="#passes" class="toc-link">Single vs Multiple Pass</a></li>
                <li><a href="#quiz" class="toc-link">Check Your Knowledge</a></li>
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
                <div class="chapter-tag">📘 Chapter 1 · Section 1.2</div>
                <h1>The Phases of a Compiler</h1>
                <div class="metadata">
                    <span>⏱ 18 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>⚙️ Compiler Theory</span>
                </div>
            </header>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 1.2 Overview                                   -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="overview">
                <h2><span class="sec-icon">🗺️</span> Overview — Why Phases?</h2>
                <p>
                    The input to a compiler is simply a <strong>string of characters</strong>. Nothing is automatically
                    "understood" — the computer must be explicitly programmed to determine that
                    <code>sum = sum + 1;</code> is an assignment statement, not just random text.
                </p>
                <p>
                    To simplify design and construction, the compiler is broken into <strong>phases</strong>.
                    At minimum, a compiler has three phases:
                </p>
                <ol>
                    <li><strong>Lexical Analysis</strong> — break the character stream into words (tokens)</li>
                    <li><strong>Syntax Analysis</strong> — check grammar and build structure (atoms / parse tree)</li>
                    <li><strong>Code Generation</strong> — translate structure into target machine instructions</li>
                </ol>
                <p>Optionally, <strong>Global Optimisation</strong> (before code generation) and <strong>Local Optimisation</strong> (after) can be added to produce more efficient object programs.</p>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        <strong>Key insight:</strong> Each phase transforms the source into a progressively lower-level
                        representation — characters → tokens → atoms/tree → machine instructions. Each phase only needs to
                        understand the output of the previous phase, not the whole program at once.
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 1.2.1 Lexical Analysis                        -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="lexical">
                <h2><span class="sec-icon">🔤</span> 1.2.1 Lexical Analysis (Scanner)</h2>
                <p>
                    The first phase is called <strong>lexical analysis</strong> (or the <em>lexical scanner</em>).
                    Its job is to isolate the "words" in the input string. A word — also called a
                    <strong>lexeme</strong>, <strong>lexical item</strong>, or <strong>lexical token</strong> — is a string
                    of input characters taken as a single unit and passed to the next phase.
                </p>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Token Class</th>
                            <th>Description</th>
                            <th>Examples</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Keywords</td>
                            <td>Reserved words of the language</td>
                            <td><code>while</code>, <code>void</code>, <code>if</code>, <code>for</code></td>
                        </tr>
                        <tr>
                            <td>Identifiers</td>
                            <td>Names declared by the programmer</td>
                            <td><code>sum</code>, <code>unit</code>, <code>myVar</code></td>
                        </tr>
                        <tr>
                            <td>Operators</td>
                            <td>Arithmetic, relational, and logical operators</td>
                            <td><code>+</code>, <code>-</code>, <code>*</code>, <code>/</code>, <code>==</code></td>
                        </tr>
                        <tr>
                            <td>Numeric Constants</td>
                            <td>Integer and floating-point literals</td>
                            <td><code>124</code>, <code>12.35</code>, <code>0.09E-23</code></td>
                        </tr>
                        <tr>
                            <td>Character Constants</td>
                            <td>Single characters or strings in quotes</td>
                            <td><code>'a'</code>, <code>"hello"</code></td>
                        </tr>
                        <tr>
                            <td>Special Characters</td>
                            <td>Delimiters used by the grammar</td>
                            <td><code>.</code>, <code>(</code>, <code>)</code>, <code>,</code>, <code>;</code>, <code>:</code></td>
                        </tr>
                        <tr>
                            <td>Comments</td>
                            <td>Identified by scanner but <em>discarded</em> — not passed on</td>
                            <td><code>/* ... */</code>, <code>// ...</code></td>
                        </tr>
                    </tbody>
                </table>

                <p>
                    The output of the lexical phase is a <strong>stream of tokens</strong>. This phase also builds the
                    <strong>symbol table</strong> — a data structure that stores all identifiers used in the source program,
                    along with their attributes (type, scope, memory location, etc.).
                </p>

                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>
                        <strong>Symbol Table placement:</strong> In block-structured languages, it may be preferable to
                        construct the symbol table during the <em>syntax analysis phase</em> because program blocks
                        (and identifier scopes) may be nested.
                    </div>
                </div>
            </section>

            <!-- Sample Problem 1.2(a) -->
            <section id="sp-a">
                <h2><span class="sec-icon">🧪</span> Sample Problem 1.2(a) — Lexical Tokens</h2>

                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 1.2 (a)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">Show the token classes, or "words", put out by the lexical analysis phase for this Java source input:</p>
                        <div class="code-wrap" style="margin-bottom:16px;">
                            <div class="code-header">
                                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                                <span class="code-lang">Java — Source Input</span>
                            </div>
                            <pre><code>sum = sum + unit * <span class="cm">/* accumulate sum */</span> 1.2e-12 ;</code></pre>
                        </div>

                        <div class="sp-solution-label">✅ Solution — Token Stream</div>
                        <p style="font-size:13px; color:var(--muted); margin-bottom:12px;">
                            Note: the comment <code>/* accumulate sum */</code> is identified by the scanner but is <em>discarded</em> and does not appear in the output.
                        </p>
                        <div class="token-stream">
                            <span class="token ident">identifier<br><strong>sum</strong></span>
                            <span class="token assign">assignment<br><strong>=</strong></span>
                            <span class="token ident">identifier<br><strong>sum</strong></span>
                            <span class="token op">operator<br><strong>+</strong></span>
                            <span class="token ident">identifier<br><strong>unit</strong></span>
                            <span class="token op">operator<br><strong>*</strong></span>
                            <span class="token comment">comment<br><strong>discarded</strong></span>
                            <span class="token num">numeric constant<br><strong>1.2e-12</strong></span>
                            <span class="token special">special char<br><strong>;</strong></span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 1.2.2 Syntax Analysis                         -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="syntax">
                <h2><span class="sec-icon">🌳</span> 1.2.2 Syntax Analysis (Parser)</h2>
                <p>
                    The syntax analysis phase — called the <strong>parser</strong> — checks for proper syntax,
                    issues appropriate error messages, and determines the underlying structure of the source program.
                    The output can be:
                </p>
                <ul>
                    <li>A <strong>stream of atoms</strong> (atomic operations), or</li>
                    <li>A <strong>syntax tree</strong> (a tree showing the structure of the statement)</li>
                </ul>

                <h3>🔵 Atoms</h3>
                <p>
                    An <strong>atom</strong> is an atomic operation — one that is generally available with one (or just a few)
                    machine language instructions on most target machines. Atoms have the form:
                </p>
                <div class="atom-wrap">
                    <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:#CDD6F4; margin-bottom:14px;">
                        <span style="color:#A5B4FC;">(operation</span>, operand1, operand2, <span style="color:#6EE7B7;">result</span>)
                    </div>
                    <div style="font-size:13px; color:rgba(205,214,244,.6); margin-bottom:14px;">Common operations and their atoms:</div>
                    <div class="atom-list">
                        <div class="atom-row">
                            <span class="atom-paren">(</span>
                            <span class="atom-op">ADD</span>
                            <span class="atom-operand">A</span><span class="atom-operand">B</span>
                            <span class="atom-result">C</span>
                            <span class="atom-paren">)</span>
                            <span style="color:rgba(205,214,244,.4); font-size:12px; margin-left:14px;">→ C = A + B</span>
                        </div>
                        <div class="atom-row">
                            <span class="atom-paren">(</span>
                            <span class="atom-op">MULT</span>
                            <span class="atom-operand">C</span><span class="atom-operand">D</span>
                            <span class="atom-result">TEMP1</span>
                            <span class="atom-paren">)</span>
                            <span style="color:rgba(205,214,244,.4); font-size:12px; margin-left:14px;">→ TEMP1 = C * D</span>
                        </div>
                        <div class="atom-row">
                            <span class="atom-paren">(</span>
                            <span class="atom-op">MOVE</span>
                            <span class="atom-operand">TEMP2</span>
                            <span class="atom-result">A</span>
                            <span class="atom-paren">)</span>
                            <span style="color:rgba(205,214,244,.4); font-size:12px; margin-left:14px;">→ A = TEMP2</span>
                        </div>
                        <div class="atom-row">
                            <span class="atom-paren">(</span>
                            <span class="atom-op">LBL</span>
                            <span class="atom-operand">L1</span>
                            <span class="atom-paren">)</span>
                            <span style="color:rgba(205,214,244,.4); font-size:12px; margin-left:14px;">→ label marker</span>
                        </div>
                        <div class="atom-row">
                            <span class="atom-paren">(</span>
                            <span class="atom-op">JMP</span>
                            <span class="atom-operand">L1</span>
                            <span class="atom-paren">)</span>
                            <span style="color:rgba(205,214,244,.4); font-size:12px; margin-left:14px;">→ unconditional branch to L1</span>
                        </div>
                        <div class="atom-row">
                            <span class="atom-paren">(</span>
                            <span class="atom-op">TEST</span>
                            <span class="atom-operand">A</span><span class="atom-operand">&lt;=</span><span class="atom-operand">B</span>
                            <span class="atom-result">L2</span>
                            <span class="atom-paren">)</span>
                            <span style="color:rgba(205,214,244,.4); font-size:12px; margin-left:14px;">→ branch to L2 if A &lt;= B</span>
                        </div>
                    </div>
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        The compiler must put out the <strong>MULT atom before the ADD atom</strong> for <code>A = B + C * D</code>,
                        despite the fact that addition is encountered first when scanning left to right. Operator precedence
                        must be respected.
                    </div>
                </div>

                <h3>🌳 Syntax Trees</h3>
                <p>
                    Some parsers output <strong>syntax trees</strong> as an intermediate data structure. In a syntax tree:
                </p>
                <ul>
                    <li>Each <strong>interior node</strong> represents an operation or control structure</li>
                    <li>Each <strong>leaf node</strong> represents an operand</li>
                </ul>
                <p>
                    Code generation from a syntax tree uses a <strong>postfix traversal</strong> — for each node N,
                    visit all subtrees of N first, then visit N last, generating the instruction(s) for N at that point.
                </p>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Control Structure</th>
                            <th>Tree Shape</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>if (Expr) Stmt1 else Stmt2</code></td>
                            <td>3 children: condition, true-branch, else-branch</td>
                        </tr>
                        <tr>
                            <td><code>while (Cond) Stmt</code></td>
                            <td>2 children: loop condition, loop body</td>
                        </tr>
                        <tr>
                            <td>Compound statement <code>{ ... }</code></td>
                            <td>Unlimited children (one per statement) OR binary tree using <code>;</code> as concatenation operator</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <!-- Sample Problem 1.2(b) -->
            <section id="sp-b">
                <h2><span class="sec-icon">🧪</span> Sample Problem 1.2(b) — Atoms for <code>A = B + C * D</code></h2>

                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 1.2 (b)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">Show atoms corresponding to the following Java statement:</p>
                        <div class="code-wrap" style="margin-bottom:16px;">
                            <div class="code-header">
                                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                                <span class="code-lang">Java — Source</span>
                            </div>
                            <pre><code>A = B + C * D ;</code></pre>
                        </div>

                        <div class="sp-solution-label">✅ Solution — Atom Stream</div>
                        <div class="atom-wrap">
                            <div class="atom-list">
                                <div class="atom-row">
                                    <span class="atom-paren">(</span>
                                    <span class="atom-op">MULT</span>
                                    <span class="atom-operand">C</span><span class="atom-operand">D</span>
                                    <span class="atom-result">TEMP1</span>
                                    <span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← multiplication first (precedence)</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span>
                                    <span class="atom-op">ADD</span>
                                    <span class="atom-operand">B</span><span class="atom-operand">TEMP1</span>
                                    <span class="atom-result">TEMP2</span>
                                    <span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← then add B</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span>
                                    <span class="atom-op">MOVE</span>
                                    <span class="atom-operand">TEMP2</span>
                                    <span class="atom-result">A</span>
                                    <span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← assign result to A</span>
                                </div>
                            </div>
                        </div>

                        <p style="font-size:13px; color:var(--muted); margin-top:14px; margin-bottom:4px;"><strong>Corresponding syntax tree for <code>A = B + C * D</code>:</strong></p>
                        <div class="tree-wrap">
                            <svg width="320" height="210" viewBox="0 0 320 210">
                                <defs>
                                    <marker id="arr" markerWidth="6" markerHeight="6" refX="3" refY="3" orient="auto">
                                        <path d="M0,0 L0,6 L6,3 z" fill="#6366F1" opacity=".5" />
                                    </marker>
                                </defs>
                                <!-- edges -->
                                <line x1="160" y1="34" x2="80" y2="74" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="160" y1="34" x2="240" y2="74" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="240" y1="94" x2="180" y2="134" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="240" y1="94" x2="300" y2="134" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="180" y1="154" x2="130" y2="188" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="180" y1="154" x2="230" y2="188" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <!-- nodes -->
                                <circle cx="160" cy="24" r="20" fill="#6366F1" opacity=".15" stroke="#6366F1" stroke-width="1.5" />
                                <text x="160" y="29" text-anchor="middle" fill="#818CF8" font-size="14" font-weight="700">=</text>

                                <circle cx="80" cy="84" r="20" fill="#1A1830" stroke="#6366F1" stroke-width="1.5" opacity=".7" />
                                <text x="80" y="89" text-anchor="middle" fill="#CDD6F4" font-size="12">A</text>

                                <circle cx="240" cy="84" r="20" fill="#6366F1" opacity=".15" stroke="#6366F1" stroke-width="1.5" />
                                <text x="240" y="89" text-anchor="middle" fill="#818CF8" font-size="14" font-weight="700">+</text>

                                <circle cx="180" cy="144" r="20" fill="#1A1830" stroke="#6366F1" stroke-width="1.5" opacity=".7" />
                                <text x="180" y="149" text-anchor="middle" fill="#CDD6F4" font-size="12">B</text>

                                <circle cx="300" cy="144" r="20" fill="#6366F1" opacity=".15" stroke="#6366F1" stroke-width="1.5" />
                                <text x="300" y="149" text-anchor="middle" fill="#818CF8" font-size="14" font-weight="700">*</text>

                                <circle cx="130" cy="196" r="16" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="130" y="201" text-anchor="middle" fill="#CDD6F4" font-size="12">C</text>

                                <circle cx="230" cy="196" r="16" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="230" y="201" text-anchor="middle" fill="#CDD6F4" font-size="12">D</text>

                                <!-- postfix order labels -->
                                <text x="60" y="118" fill="#F9E2AF" font-size="10" opacity=".7">① leaf A</text>
                                <text x="158" y="170" fill="#F9E2AF" font-size="10" opacity=".7">② leaf B</text>
                                <text x="108" y="216" fill="#F9E2AF" font-size="9" opacity=".7">③ C</text>
                                <text x="208" y="216" fill="#F9E2AF" font-size="9" opacity=".7">④ D</text>
                                <text x="270" y="178" fill="#A5B4FC" font-size="10" opacity=".8">⑤ MULT</text>
                                <text x="194" y="62" fill="#A5B4FC" font-size="10" opacity=".8">⑥ ADD</text>
                                <text x="128" y="16" fill="#6EE7B7" font-size="10" opacity=".8">⑦ MOVE</text>
                            </svg>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sample Problem 1.2(c) -->
            <section id="sp-c">
                <h2><span class="sec-icon">🧪</span> Sample Problem 1.2(c) — Atoms for a <code>while</code> loop</h2>

                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 1.2 (c)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">Show atoms corresponding to the Java statement:</p>
                        <div class="code-wrap" style="margin-bottom:16px;">
                            <div class="code-header">
                                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                                <span class="code-lang">Java — Source</span>
                            </div>
                            <pre><code><span class="kw">while</span> (A &lt;= B) A = A + <span class="num">1</span>;</code></pre>
                        </div>

                        <div class="sp-solution-label">✅ Solution — Atom Stream</div>
                        <div class="atom-wrap">
                            <div class="atom-list">
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op" style="background:rgba(249,226,175,.15);color:#F9E2AF;">LBL</span><span class="atom-operand">L1</span><span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← loop top label</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op">TEST</span><span class="atom-operand">A</span><span class="atom-operand">&lt;=</span><span class="atom-operand">B</span><span class="atom-result">L2</span><span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← if A&lt;=B, branch to L2 (loop body)</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op" style="background:rgba(239,68,68,.12);color:#F87171;">JMP</span><span class="atom-operand">L3</span><span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← condition false → exit loop</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op" style="background:rgba(249,226,175,.15);color:#F9E2AF;">LBL</span><span class="atom-operand">L2</span><span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← loop body starts here</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op">ADD</span><span class="atom-operand">A</span><span class="atom-operand">1</span><span class="atom-result">A</span><span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← A = A + 1</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op" style="background:rgba(239,68,68,.12);color:#F87171;">JMP</span><span class="atom-operand">L1</span><span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← go back to loop top</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op" style="background:rgba(249,226,175,.15);color:#F9E2AF;">LBL</span><span class="atom-operand">L3</span><span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← exit label</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sample Problem 1.2(d) -->
            <section id="sp-d">
                <h2><span class="sec-icon">🧪</span> Sample Problem 1.2(d) — Syntax Tree for <code>if-else</code></h2>

                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 1.2 (d)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">
                            Show a syntax tree for the Java statement below. Assume an if-statement has three subtrees:
                            one for the condition, one for the consequent, and one for the else statement.
                        </p>
                        <div class="code-wrap" style="margin-bottom:16px;">
                            <div class="code-header">
                                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                                <span class="code-lang">Java — Source</span>
                            </div>
                            <pre><code><span class="kw">if</span> (A + <span class="num">3</span> &lt; <span class="num">400</span>) A = <span class="num">0</span>; <span class="kw">else</span> B = A * A;</code></pre>
                        </div>

                        <div class="sp-solution-label">✅ Solution — Syntax Tree</div>
                        <div class="tree-wrap">
                            <svg width="520" height="240" viewBox="0 0 520 240">
                                <!-- edges: if node to children -->
                                <line x1="260" y1="34" x2="120" y2="80" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="260" y1="34" x2="260" y2="80" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="260" y1="34" x2="400" y2="80" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <!-- condition: < node -->
                                <line x1="120" y1="100" x2="70" y2="140" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="120" y1="100" x2="170" y2="140" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <!-- + node -->
                                <line x1="70" y1="160" x2="40" y2="200" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="70" y1="160" x2="100" y2="200" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <!-- else: * node -->
                                <line x1="400" y1="100" x2="440" y2="140" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="440" y1="160" x2="410" y2="200" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="440" y1="160" x2="470" y2="200" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <!-- = consequent: left -->
                                <line x1="260" y1="100" x2="220" y2="140" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="260" y1="100" x2="260" y2="140" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <!-- else = left -->
                                <line x1="400" y1="100" x2="370" y2="140" stroke="#6366F1" stroke-width="1.5" opacity=".4" />

                                <!-- if root -->
                                <rect x="230" y="10" width="60" height="28" rx="8" fill="#6366F1" opacity=".2" stroke="#6366F1" stroke-width="1.5" />
                                <text x="260" y="29" text-anchor="middle" fill="#A5B4FC" font-size="13" font-weight="700">if</text>

                                <!-- condition subtree: < -->
                                <circle cx="120" cy="90" r="16" fill="#6366F1" opacity=".15" stroke="#6366F1" stroke-width="1.5" />
                                <text x="120" y="95" text-anchor="middle" fill="#818CF8" font-size="13" font-weight="700">&lt;</text>
                                <!-- + -->
                                <circle cx="70" cy="150" r="16" fill="#6366F1" opacity=".15" stroke="#6366F1" stroke-width="1.5" />
                                <text x="70" y="155" text-anchor="middle" fill="#818CF8" font-size="13" font-weight="700">+</text>
                                <circle cx="40" cy="206" r="14" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="40" y="211" text-anchor="middle" fill="#CDD6F4" font-size="11">A</text>
                                <circle cx="100" cy="206" r="14" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="100" y="211" text-anchor="middle" fill="#FAB387" font-size="11">3</text>
                                <!-- 400 -->
                                <circle cx="170" cy="148" r="18" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="170" y="153" text-anchor="middle" fill="#FAB387" font-size="10">400</text>

                                <!-- consequent: = node -->
                                <circle cx="260" cy="90" r="16" fill="#6366F1" opacity=".15" stroke="#6366F1" stroke-width="1.5" />
                                <text x="260" y="95" text-anchor="middle" fill="#818CF8" font-size="13" font-weight="700">=</text>
                                <circle cx="220" cy="148" r="14" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="220" y="153" text-anchor="middle" fill="#CDD6F4" font-size="11">A</text>
                                <circle cx="260" cy="148" r="14" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="260" y="153" text-anchor="middle" fill="#FAB387" font-size="11">0</text>

                                <!-- else: = node -->
                                <circle cx="370" cy="148" r="14" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="370" y="153" text-anchor="middle" fill="#CDD6F4" font-size="11">B</text>
                                <circle cx="400" cy="90" r="16" fill="#6366F1" opacity=".15" stroke="#6366F1" stroke-width="1.5" />
                                <text x="400" y="95" text-anchor="middle" fill="#818CF8" font-size="13" font-weight="700">=</text>
                                <!-- * -->
                                <circle cx="440" cy="150" r="16" fill="#6366F1" opacity=".15" stroke="#6366F1" stroke-width="1.5" />
                                <text x="440" y="155" text-anchor="middle" fill="#818CF8" font-size="13" font-weight="700">*</text>
                                <circle cx="410" cy="206" r="14" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="410" y="211" text-anchor="middle" fill="#CDD6F4" font-size="11">A</text>
                                <circle cx="470" cy="206" r="14" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="470" y="211" text-anchor="middle" fill="#CDD6F4" font-size="11">A</text>

                                <!-- labels -->
                                <text x="70" y="230" text-anchor="middle" fill="#F9E2AF" font-size="9" opacity=".7">condition</text>
                                <text x="245" y="230" text-anchor="middle" fill="#F9E2AF" font-size="9" opacity=".7">consequent</text>
                                <text x="430" y="230" text-anchor="middle" fill="#F9E2AF" font-size="9" opacity=".7">else</text>
                            </svg>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Semantic Analysis                             -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="semantic">
                <h2><span class="sec-icon">🧠</span> Semantic Analysis</h2>
                <p>
                    Many compilers include a <strong>semantic analysis</strong> phase. In this phase:
                </p>
                <ul>
                    <li>Data types are <strong>checked</strong> for compatibility</li>
                    <li><strong>Type conversions</strong> are performed when necessary (e.g. int → float)</li>
                    <li>Some semantic errors can be detected: division by zero, use of a null pointer</li>
                </ul>
                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>
                        Semantic analysis catches errors that are syntactically valid but logically incorrect.
                        For example, <code>int x = "hello";</code> is syntactically structured correctly but
                        is a <em>type error</em> caught by semantic analysis.
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 1.2.3 Global Optimisation                     -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="global-opt">
                <h2><span class="sec-icon">⚡</span> 1.2.3 Global Optimisation</h2>
                <p>
                    The global optimisation phase is <strong>optional</strong>. Its purpose is to make the object program
                    more efficient in space and/or time. It examines the <strong>sequence of atoms</strong> from the parser
                    to find redundant, unreachable, or inefficient code. Because it runs before the code generator, it is
                    also called <strong>machine-independent optimisation</strong>.
                </p>

                <h3>📌 Example 1 — Dead Code Elimination</h3>
                <p>Unreachable statements after an unconditional <code>goto</code> can never execute and should be removed:</p>
                <div class="code-wrap">
                    <div class="code-header">
                        <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                        <span class="code-lang">Before Optimisation</span>
                    </div>
                    <pre><code>  stmt1
  go to label1
  stmt2          <span class="cm">// ← unreachable — can never execute</span>
  stmt3          <span class="cm">// ← unreachable — can never execute</span>
label2: stmt4</code></pre>
                </div>
                <p><code>stmt2</code> and <code>stmt3</code> can never be executed. They are <strong>unreachable</strong> and can be eliminated from the object program.</p>

                <h3>📌 Example 2 — Loop Invariant Code Motion</h3>
                <p>
                    If a computation inside a loop does not depend on the loop variable, it is a
                    <strong>loop invariant</strong> and can be moved outside the loop:
                </p>

                <div class="opt-grid">
                    <div class="opt-before">
                        <div class="opt-label">Before — 100,000 sqrt calls</div>
                        <div class="code-wrap" style="margin:0;">
                            <pre><code><span class="kw">for</span> (i=<span class="num">1</span>; i&lt;=<span class="num">100000</span>; i++) {
  x = Math.sqrt(y);  <span class="cm" style="background:rgba(239,68,68,.15); display:block; margin:0 -20px; padding:0 20px;">// called 100,000×!</span>
  System.out.println(x+i);
}</code></pre>
                        </div>
                    </div>
                    <div class="opt-arrow">→</div>
                    <div class="opt-after">
                        <div class="opt-label">After — 1 sqrt call</div>
                        <div class="code-wrap" style="margin:0;">
                            <pre><code><span class="cm" style="background:rgba(16,185,129,.08); display:block; margin:0 -20px; padding:0 20px;">x = Math.sqrt(y); // moved out!</span>
<span class="kw">for</span> (i=<span class="num">1</span>; i&lt;=<span class="num">100000</span>; i++) {
  System.out.println(x+i);
}</code></pre>
                        </div>
                    </div>
                </div>
                <p>This eliminates <strong>99,999 unnecessary calls</strong> to the sqrt method at run time.</p>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Debugging caveat:</strong> Global optimisation can seriously impact run-time debugging. If
                        <code>y</code> was negative causing a run-time error in <code>sqrt</code>, the user would not know
                        the actual source location because the compiler moved the statement without informing the programmer.
                        Most compilers provide a switch to <strong>turn optimisation on/off</strong> — off for debugging, on
                        for production builds.
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 1.2.4 Code Generation                         -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="codegen">
                <h2><span class="sec-icon">💾</span> 1.2.4 Code Generation</h2>
                <p>
                    In the code generation phase, atoms or syntax trees are translated into <strong>machine language
                        (binary) instructions</strong>, or into assembly language (which the assembler then converts to
                    binary). Symbolic addresses (statement labels) are translated to relocatable memory addresses.
                </p>
                <p>
                    Most Java compilers produce an intermediate form known as <strong>Byte Code</strong>, which is
                    interpreted by the Java runtime. In this course we assume the compiler produces
                    <strong>native code</strong> for a particular machine.
                </p>

                <h3>Register Allocation</h3>
                <p>
                    For target machines with several CPU registers, the code generator is responsible for
                    <strong>register allocation</strong> — tracking which registers are in use and which are available as
                    code is generated. For example, an ADD atom translates to three machine instructions:
                </p>

                <div class="two-col">
                    <div>
                        <div class="atom-wrap">
                            <div style="font-size:12px; color:rgba(205,214,244,.5); margin-bottom:10px;">ATOM INPUT</div>
                            <div class="atom-list">
                                <div class="atom-row">
                                    <span class="atom-paren">(</span>
                                    <span class="atom-op">ADD</span>
                                    <span class="atom-operand">A</span><span class="atom-operand">B</span>
                                    <span class="atom-result">Temp</span>
                                    <span class="atom-paren">)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="code-wrap" style="margin:0;">
                            <div class="code-header">
                                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                                <span class="code-lang">Assembly Output</span>
                            </div>
                            <pre><code><span class="op">LOD</span> <span class="reg">R1</span>,A     <span class="cm">// Load A into reg. 1</span>
<span class="op">ADD</span> <span class="reg">R1</span>,B     <span class="cm">// Add B to reg. 1</span>
<span class="op">STO</span> <span class="reg">R1</span>,Temp  <span class="cm">// Store reg. 1 in Temp</span></code></pre>
                        </div>
                    </div>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        The object language does not have to be machine code — it can be <strong>another high-level language</strong>.
                        This improves portability of the language being implemented.
                    </div>
                </div>
            </section>

            <!-- Sample Problem 1.2(e) -->
            <section id="sp-e">
                <h2><span class="sec-icon">🧪</span> Sample Problem 1.2(e) — Atoms to Assembly</h2>

                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 1.2 (e)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">Show assembly language instructions corresponding to the following atom string:</p>

                        <div class="atom-wrap" style="margin-bottom:16px;">
                            <div class="atom-list">
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op">ADD</span><span class="atom-operand">A</span><span class="atom-operand">B</span><span class="atom-result">Temp1</span><span class="atom-paren">)</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op">TEST</span><span class="atom-operand">A</span><span class="atom-operand">==</span><span class="atom-operand">B</span><span class="atom-result">L1</span><span class="atom-paren">)</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op">MOVE</span><span class="atom-operand">Temp1</span><span class="atom-result">A</span><span class="atom-paren">)</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op" style="background:rgba(249,226,175,.15);color:#F9E2AF;">LBL</span><span class="atom-operand">L1</span><span class="atom-paren">)</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op">MOVE</span><span class="atom-operand">Temp1</span><span class="atom-result">B</span><span class="atom-paren">)</span>
                                </div>
                            </div>
                        </div>

                        <div class="sp-solution-label">✅ Solution — Assembly Output</div>
                        <div class="code-wrap">
                            <div class="code-header">
                                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                                <span class="code-lang">Assembly</span>
                                <button class="copy-btn" onclick="copyBlock('sp-e-block',this)">Copy</button>
                            </div>
                            <pre><code id="sp-e-block"><span class="op">LOD</span> <span class="reg">R1</span>,A
<span class="op">ADD</span> <span class="reg">R1</span>,B
<span class="op">STO</span> <span class="reg">R1</span>,Temp1     <span class="cm">// ADD, A, B, Temp1</span>
<span class="op">CMP</span> A,B
<span class="op">BE</span>  L1             <span class="cm">// TEST, A, ==, B, L1</span>
<span class="op">MOV</span> A,Temp1        <span class="cm">// MOVE, Temp1, A  (dest=first operand)</span>
<span class="lbl">L1:</span> <span class="op">MOV</span> B,Temp1   <span class="cm">// MOVE, Temp1, B</span></code></pre>
                        </div>
                        <div class="note-box warn" style="margin-top:14px;">
                            <span class="box-icon">⚠️</span>
                            <div>
                                <strong>Operand order note:</strong> For the MOV instruction, the destination is the
                                <em>first</em> operand and the source is the <em>second</em> — the reverse of the operand
                                positions in the MOVE atom.
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 1.2.5 Local Optimisation                      -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="local-opt">
                <h2><span class="sec-icon">🔧</span> 1.2.5 Local Optimisation</h2>
                <p>
                    The local optimisation phase is also <strong>optional</strong>. It examines sequences of instructions
                    from the code generator to find unnecessary or redundant instructions. Because it depends on the
                    specific instructions of the target machine, it is called <strong>machine-dependent optimisation</strong>.
                </p>
                <p>
                    Consider compiling <code>A + B + C</code>. The code generator naively outputs 6 instructions, but
                    2 of them are redundant:
                </p>

                <div class="opt-grid">
                    <div class="opt-before">
                        <div class="opt-label">Before — Code Generator Output (6 instructions)</div>
                        <div class="code-wrap" style="margin:0;">
                            <pre><code><span class="op">LOD</span> <span class="reg">R1</span>,A
<span class="op">ADD</span> <span class="reg">R1</span>,B
<span class="op">STO</span> <span class="reg">R1</span>,TEMP1  <span class="cm">// ← store result *</span>
<span class="op">LOD</span> <span class="reg">R1</span>,TEMP1 <span class="cm">// ← reload same thing *</span>
<span class="op">ADD</span> <span class="reg">R1</span>,C
<span class="op">STO</span> <span class="reg">R1</span>,TEMP2</code></pre>
                        </div>
                    </div>
                    <div class="opt-arrow">→</div>
                    <div class="opt-after">
                        <div class="opt-label">After — Local Optimisation (4 instructions)</div>
                        <div class="code-wrap" style="margin:0;">
                            <pre><code><span class="op">LOD</span> <span class="reg">R1</span>,A
<span class="op">ADD</span> <span class="reg">R1</span>,B
<span class="op">ADD</span> <span class="reg">R1</span>,C     <span class="cm">// R1 still holds A+B!</span>
<span class="op">STO</span> <span class="reg">R1</span>,TEMP</code></pre>
                        </div>
                    </div>
                </div>

                <p>
                    The instructions marked with <code>*</code> (STO to TEMP1 then immediately LOD from TEMP1) cancel each
                    other out — R1 already holds the correct value. Eliminating them makes the object program both
                    <strong>smaller</strong> and <strong>faster</strong>.
                </p>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Full Pipeline Diagram                         -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="pipeline">
                <h2><span class="sec-icon">🔄</span> Full Compiler Pipeline (Figure 1.4)</h2>
                <p>
                    The diagram below shows all phases and what each one outputs. Note that the optional optimisation
                    phases can be omitted — atoms can be passed directly from Syntax Analysis to Code Generation, and
                    instructions can be passed directly to the output file.
                </p>

                <div class="pipeline">
                    <div class="pipe-stage">
                        <div class="pipe-box">
                            <div class="pipe-name">📝 Source Program <span class="pipe-badge">Input</span></div>
                            <div class="pipe-desc">A string of characters (source code)</div>
                        </div>
                    </div>
                    <div class="pipe-arrow">
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-output-label">characters</div>
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-arrow-head"></div>
                    </div>

                    <div class="pipe-stage">
                        <div class="pipe-box">
                            <div class="pipe-name">🔤 Lexical Analysis <span class="pipe-badge">Phase 1</span></div>
                            <div class="pipe-desc">Identifies words/tokens; builds symbol table</div>
                            <div class="pipe-io">Input: characters → Output: token stream</div>
                        </div>
                    </div>
                    <div class="pipe-arrow">
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-output-label">Tokens</div>
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-arrow-head"></div>
                    </div>

                    <div class="pipe-stage">
                        <div class="pipe-box">
                            <div class="pipe-name">🌳 Syntax Analysis <span class="pipe-badge">Phase 2</span></div>
                            <div class="pipe-desc">Checks grammar; produces atoms or syntax trees; semantic type-checking</div>
                            <div class="pipe-io">Input: tokens → Output: atom stream</div>
                        </div>
                    </div>
                    <div class="pipe-arrow">
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-output-label">Atoms</div>
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-arrow-head"></div>
                    </div>

                    <div class="pipe-stage pipe-optional">
                        <div class="pipe-box">
                            <div class="pipe-name">⚡ Global Optimisation <span class="pipe-badge">Optional</span></div>
                            <div class="pipe-desc">Dead code elimination, loop invariant code motion — machine-independent</div>
                            <div class="pipe-io">Input: atoms → Output: optimised atoms</div>
                        </div>
                    </div>
                    <div class="pipe-arrow">
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-output-label">Atoms (optimised)</div>
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-arrow-head"></div>
                    </div>

                    <div class="pipe-stage">
                        <div class="pipe-box">
                            <div class="pipe-name">💾 Code Generation <span class="pipe-badge">Phase 3</span></div>
                            <div class="pipe-desc">Translates atoms/trees into machine/assembly instructions; register allocation</div>
                            <div class="pipe-io">Input: atoms → Output: instruction sequence</div>
                        </div>
                    </div>
                    <div class="pipe-arrow">
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-output-label">Instructions</div>
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-arrow-head"></div>
                    </div>

                    <div class="pipe-stage pipe-optional">
                        <div class="pipe-box">
                            <div class="pipe-name">🔧 Local Optimisation <span class="pipe-badge">Optional</span></div>
                            <div class="pipe-desc">Eliminates redundant load/store pairs; machine-dependent</div>
                            <div class="pipe-io">Input: instructions → Output: optimised instructions</div>
                        </div>
                    </div>
                    <div class="pipe-arrow">
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-output-label">Instructions (optimised)</div>
                        <div class="pipe-arrow-line"></div>
                        <div class="pipe-arrow-head"></div>
                    </div>

                    <div class="pipe-stage">
                        <div class="pipe-box" style="border-color:var(--green); background:#F0FDF4;">
                            <div class="pipe-name" style="color:#065F46;">✅ Object Program <span class="pipe-badge" style="background:#D1FAE5;color:#065F46;">Output</span></div>
                            <div class="pipe-desc" style="color:#047857;">Machine language binary / assembly ready for linking</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Single vs Multiple Pass                       -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="passes">
                <h2><span class="sec-icon">🔁</span> Single-Pass vs Multiple-Pass Compilers</h2>
                <p>There are two main ways to manage the flow of control between phases:</p>

                <div class="pass-grid">
                    <div class="pass-card">
                        <h5>📁 Multiple-Pass Compiler</h5>
                        <p>
                            Each phase runs from start to finish separately, writing its output to a <strong>disk file</strong>.
                            For example: lexical analysis scans the <em>entire</em> source program and writes a token file.
                            Then syntax analysis reads the <em>entire</em> token file and writes an atom file. The process
                            repeats for each phase. The input is scanned <em>multiple times</em>.
                        </p>
                    </div>
                    <div class="pass-card">
                        <h5>⚡ Single-Pass Compiler</h5>
                        <p>
                            Syntax analysis starts first. Each time it needs a token, it calls lexical analysis as a
                            <strong>subroutine</strong> — which reads just enough characters to produce one token and returns it.
                            Whenever the parser has enough to produce an atom, it calls the code generator as a subroutine.
                            The entire source is scanned <em>only once</em>.
                        </p>
                    </div>
                </div>

                <div class="note-box key">
                    <span class="box-icon">💡</span>
                    <div>
                        <strong>Single-pass compilers</strong> are faster and use less disk/memory, but may have limitations
                        (e.g. forward references to identifiers not yet declared). <strong>Multiple-pass compilers</strong>
                        are more flexible and can support complex language features at the cost of speed.
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Quiz                                          -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="quiz">
                <h2><span class="sec-icon">🧩</span> Check Your Knowledge</h2>
                <p>Test what you've learned about section 1.2. Select the best answer for each question.</p>

                <!-- Q1 -->
                <div class="mini-quiz">
                    <div class="quiz-header"><span style="font-size:18px;">❓</span>
                        <h4>Question 1</h4>
                    </div>
                    <div class="quiz-body">
                        <p class="quiz-q">What does the lexical analysis phase output?</p>
                        <div class="quiz-options" id="q1-opts">
                            <div class="quiz-opt" onclick="answer(this,'q1',false)">
                                <div class="opt-circle">A</div> A syntax tree representing the structure of the program
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q1',true)">
                                <div class="opt-circle">B</div> A stream of tokens (lexemes) and a symbol table
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q1',false)">
                                <div class="opt-circle">C</div> Machine language instructions for the target machine
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q1',false)">
                                <div class="opt-circle">D</div> A stream of atoms representing atomic operations
                            </div>
                        </div>
                        <div class="quiz-feedback" id="q1-fb"></div>
                    </div>
                </div>

                <!-- Q2 -->
                <div class="mini-quiz">
                    <div class="quiz-header"><span style="font-size:18px;">❓</span>
                        <h4>Question 2</h4>
                    </div>
                    <div class="quiz-body">
                        <p class="quiz-q">For the statement <code>A = B + C * D</code>, what is the <em>first</em> atom the parser must output?</p>
                        <div class="quiz-options" id="q2-opts">
                            <div class="quiz-opt" onclick="answer(this,'q2',false)">
                                <div class="opt-circle">A</div> (ADD, B, C, TEMP1)
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q2',false)">
                                <div class="opt-circle">B</div> (MOVE, TEMP2, A)
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q2',true)">
                                <div class="opt-circle">C</div> (MULT, C, D, TEMP1)
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q2',false)">
                                <div class="opt-circle">D</div> (ADD, B, D, TEMP1)
                            </div>
                        </div>
                        <div class="quiz-feedback" id="q2-fb"></div>
                    </div>
                </div>

                <!-- Q3 -->
                <div class="mini-quiz">
                    <div class="quiz-header"><span style="font-size:18px;">❓</span>
                        <h4>Question 3</h4>
                    </div>
                    <div class="quiz-body">
                        <p class="quiz-q">What is the difference between global optimisation and local optimisation?</p>
                        <div class="quiz-options" id="q3-opts">
                            <div class="quiz-opt" onclick="answer(this,'q3',false)">
                                <div class="opt-circle">A</div> Global optimisation happens after code generation; local optimisation happens before
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q3',true)">
                                <div class="opt-circle">B</div> Global optimisation works on atoms (machine-independent) before code generation; local optimisation works on instructions (machine-dependent) after code generation
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q3',false)">
                                <div class="opt-circle">C</div> They are the same thing — just different names used in different textbooks
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q3',false)">
                                <div class="opt-circle">D</div> Local optimisation only applies to local variables; global optimisation applies to global variables
                            </div>
                        </div>
                        <div class="quiz-feedback" id="q3-fb"></div>
                    </div>
                </div>

                <!-- Q4 -->
                <div class="mini-quiz">
                    <div class="quiz-header"><span style="font-size:18px;">❓</span>
                        <h4>Question 4</h4>
                    </div>
                    <div class="quiz-body">
                        <p class="quiz-q">In a postfix traversal of a syntax tree for code generation, when is the instruction for a node generated?</p>
                        <div class="quiz-options" id="q4-opts">
                            <div class="quiz-opt" onclick="answer(this,'q4',false)">
                                <div class="opt-circle">A</div> Before visiting any of its subtrees (prefix order)
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q4',false)">
                                <div class="opt-circle">B</div> Between visiting its left and right subtrees (infix order)
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q4',true)">
                                <div class="opt-circle">C</div> After visiting all subtrees of the node (postfix order)
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q4',false)">
                                <div class="opt-circle">D</div> In the same left-to-right order the source code was written
                            </div>
                        </div>
                        <div class="quiz-feedback" id="q4-fb"></div>
                    </div>
                </div>

                <!-- Q5 -->
                <div class="mini-quiz">
                    <div class="quiz-header"><span style="font-size:18px;">❓</span>
                        <h4>Question 5</h4>
                    </div>
                    <div class="quiz-body">
                        <p class="quiz-q">In a single-pass compiler, how does the syntax analysis phase get tokens?</p>
                        <div class="quiz-options" id="q5-opts">
                            <div class="quiz-opt" onclick="answer(this,'q5',false)">
                                <div class="opt-circle">A</div> It reads an entire token file written by the lexical analysis phase
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q5',true)">
                                <div class="opt-circle">B</div> It calls the lexical analysis phase as a subroutine each time it needs one token
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q5',false)">
                                <div class="opt-circle">C</div> It tokenises the source itself without a separate lexical phase
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q5',false)">
                                <div class="opt-circle">D</div> All tokens are preloaded into memory before syntax analysis begins
                            </div>
                        </div>
                        <div class="quiz-feedback" id="q5-fb"></div>
                    </div>
                </div>

            </section>

            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="c1.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous section</span>1.1 What is a Compiler?</div>
                </a>
                <a href="c1_3.php" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>Chapter 1.3: Implementation Technique</div>
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

        // ── Copy code ────────────────────────────────────────────
        function copyBlock(id, btn) {
            const text = document.getElementById(id).innerText;
            navigator.clipboard.writeText(text).then(() => {
                btn.textContent = 'Copied!';
                setTimeout(() => btn.textContent = 'Copy', 1800);
            });
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
            fb.innerHTML = correct ?
                '✅ <strong>Correct!</strong> Well done.' :
                '❌ <strong>Not quite.</strong> The correct answer is highlighted above.';
        }
    </script>

</body>

</html>