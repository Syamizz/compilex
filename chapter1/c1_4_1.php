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
$page = 18;
$nextPage = "c1_4_2.php";

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
    <title>Chapter 1.4 – CompileX</title>

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

        .str {
            color: #A6E3A1;
        }

        .fn {
            color: #89B4FA;
        }

        .typ {
            color: #94E2D5;
        }

        .var {
            color: #CDD6F4;
        }

        .ann {
            color: #F38BA8;
        }

        /* ── Feature comparison grid ────── */
        .feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 24px 0;
        }

        .feature-card {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
        }

        .feature-card h5 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .feature-card h5.has {
            color: var(--green);
        }

        .feature-card h5.lacks {
            color: var(--red);
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .feature-item .fi-icon {
            flex-shrink: 0;
            margin-top: 2px;
            font-size: 14px;
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

        .tag-yes {
            background: #F0FDF4;
            color: #065F46;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 8px;
            font-weight: 600;
        }

        .tag-no {
            background: #FFF1F2;
            color: #9F1239;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 8px;
            font-weight: 600;
        }

        /* ── Series math box ────────────── */
        .math-box {
            background: var(--code-bg);
            border-radius: var(--radius);
            padding: 20px 28px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .2);
        }

        .math-box .formula {
            font-family: 'JetBrains Mono', monospace;
            font-size: 15px;
            color: #CDD6F4;
            line-height: 2;
        }

        .math-box .formula .kw2 {
            color: #CBA6F7;
        }

        .math-box .formula .pos {
            color: #A6E3A1;
        }

        .math-box .formula .neg {
            color: #F38BA8;
        }

        .math-box small {
            display: block;
            color: rgba(205, 214, 244, .4);
            font-size: 12px;
            margin-top: 8px;
        }

        /* ── Annotated code stepper ─────── */
        .annotated-wrap {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin: 24px 0;
        }

        .annotated-header {
            background: var(--purple-s);
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .annotated-header h4 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: var(--purple);
            margin: 0;
        }

        .annotated-body {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .ann-code {
            background: var(--code-bg);
            padding: 20px;
            overflow-x: auto;
        }

        .ann-code pre {
            padding: 0;
        }

        .ann-code .line {
            display: block;
            padding: 3px 8px;
            border-radius: 4px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12.5px;
            color: rgba(205, 214, 244, .5);
            line-height: 1.75;
            transition: background .25s, color .25s;
            cursor: pointer;
        }

        .ann-code .line:hover {
            background: rgba(99, 102, 241, .1);
            color: rgba(205, 214, 244, .85);
        }

        .ann-code .line.active {
            background: rgba(99, 102, 241, .25);
            color: #CDD6F4;
        }

        .ann-panel {
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .ann-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--purple);
            margin-bottom: 8px;
        }

        .ann-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 16px;
            color: var(--text);
            margin-bottom: 8px;
        }

        .ann-desc {
            font-size: 13.5px;
            color: var(--muted);
            line-height: 1.65;
        }

        .ann-hint {
            margin-top: 12px;
            padding: 10px 14px;
            background: var(--purple-s);
            border-radius: 10px;
            font-size: 13px;
            color: var(--purple);
        }

        /* ── Validity exercise ─────────── */
        .validity-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin: 20px 0;
        }

        .validity-card {
            background: var(--card);
            border: 1.5px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .validity-label {
            padding: 8px 14px;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .validity-card .code-wrap {
            margin: 0;
            border-radius: 0;
            box-shadow: none;
        }

        .validity-card pre {
            padding: 14px 18px;
        }

        .validity-card pre code {
            font-size: 12px;
        }

        .verdict {
            font-size: 11px;
            padding: 3px 9px;
            border-radius: 8px;
            font-weight: 700;
        }

        .verdict.valid {
            background: #F0FDF4;
            color: #065F46;
        }

        .verdict.invalid {
            background: #FFF1F2;
            color: #9F1239;
        }

        .validity-explain {
            padding: 10px 14px;
            font-size: 12.5px;
            color: var(--muted);
            border-top: 1px solid #EEF2FF;
        }

        /* ── Mini machine card ─────────── */
        .mini-card {
            background: var(--card);
            border: 1.5px solid rgba(99, 102, 241, .2);
            border-radius: var(--radius);
            padding: 22px;
            box-shadow: var(--shadow);
            margin: 24px 0;
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .mini-icon {
            width: 56px;
            height: 56px;
            background: var(--purple-s);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            flex-shrink: 0;
        }

        .mini-body h4 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 16px;
            color: var(--text);
            margin-bottom: 6px;
        }

        .mini-body p {
            font-size: 13.5px;
            color: var(--muted);
            margin: 0;
            line-height: 1.65;
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

        @media (max-width:768px) {
            .note-container {
                grid-template-columns: 1fr;
            }

            .toc {
                position: static;
            }

            .feature-grid,
            .validity-grid,
            .annotated-body {
                grid-template-columns: 1fr;
            }

            .mini-card {
                flex-direction: column;
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
                <li><a href="#features" class="toc-link">1.4.1 Features vs Java</a></li>
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
                <div class="chapter-tag">📘 Chapter 1 · Section 1.4</div>
                <h1>Case Study: Decaf</h1>
                <div class="metadata">
                    <span>⏱ 12 min read</span>
                    <span>🎯 Beginner</span>
                    <span>☕ Decaf Language</span>
                </div>
            </header>



            <!-- ══════════════════════════════════════════════ -->
            <!-- 1.4.1 Features vs Java                         -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="features">
                <h2><span class="sec-icon">⚖️</span> 1.4.1 Decaf Features vs Java</h2>
                <p>
                    Decaf retains just enough of Java to write real programs, while removing everything that
                    would make compiler design unnecessarily complicated.
                </p>

                <div class="feature-grid">
                    <div class="feature-card">
                        <h5 class="has">✅ What Decaf HAS</h5>
                        <div class="feature-item"><span class="fi-icon">🔢</span><span>Data types: <code>int</code> and <code>float</code> only</span></div>
                        <div class="feature-item"><span class="fi-icon">🔁</span><span><code>while</code> loop control structure</span></div>
                        <div class="feature-item"><span class="fi-icon">🔁</span><span><code>for</code> loop control structure</span></div>
                        <div class="feature-item"><span class="fi-icon">🔀</span><span><code>if</code> conditional control structure</span></div>
                        <div class="feature-item"><span class="fi-icon">🧮</span><span>Arithmetic expressions (<code>+</code>, <code>-</code>, <code>*</code>, <code>/</code>)</span></div>
                        <div class="feature-item"><span class="fi-icon">📋</span><span>Variable assignment statements</span></div>
                        <div class="feature-item"><span class="fi-icon">📐</span><span>Free-format source (whitespace as delimiters)</span></div>
                        <div class="feature-item"><span class="fi-icon">💬</span><span>Comments (same as standard C)</span></div>
                    </div>
                    <div class="feature-card">
                        <h5 class="lacks">❌ What Decaf LACKS (vs Java)</h5>
                        <div class="feature-item"><span class="fi-icon">🚫</span><span>Arrays — no <code>int[]</code> or <code>float[]</code></span></div>
                        <div class="feature-item"><span class="fi-icon">🚫</span><span>Classes (other than the required wrapper)</span></div>
                        <div class="feature-item"><span class="fi-icon">🚫</span><span>Enumerated types</span></div>
                        <div class="feature-item"><span class="fi-icon">🚫</span><span>Methods and subprograms</span></div>
                        <div class="feature-item"><span class="fi-icon">🚫</span><span><code>switch</code> statements</span></div>
                        <div class="feature-item"><span class="fi-icon">🚫</span><span><code>do-while</code> loops</span></div>
                        <div class="feature-item"><span class="fi-icon">🚫</span><span>Unions and complex type structures</span></div>
                        <div class="feature-item"><span class="fi-icon">🚫</span><span>String type and string operations</span></div>
                    </div>
                </div>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Feature</th>
                            <th>Java</th>
                            <th>Decaf</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Data types</td>
                            <td>int, float, double, String, boolean, char, …</td>
                            <td><span class="tag-yes">int</span> &nbsp;<span class="tag-yes">float</span> only</td>
                        </tr>
                        <tr>
                            <td>Arrays</td>
                            <td>✅ Supported</td>
                            <td><span class="tag-no">Not supported</span></td>
                        </tr>
                        <tr>
                            <td>Classes</td>
                            <td>✅ Full OOP</td>
                            <td><span class="tag-no">Only required main wrapper</span></td>
                        </tr>
                        <tr>
                            <td>Methods</td>
                            <td>✅ Supported</td>
                            <td><span class="tag-no">Not supported</span></td>
                        </tr>
                        <tr>
                            <td>while loop</td>
                            <td>✅ Supported</td>
                            <td><span class="tag-yes">Supported</span></td>
                        </tr>
                        <tr>
                            <td>for loop</td>
                            <td>✅ Supported</td>
                            <td><span class="tag-yes">Supported</span></td>
                        </tr>
                        <tr>
                            <td>if / else</td>
                            <td>✅ Supported</td>
                            <td><span class="tag-yes">Supported</span></td>
                        </tr>
                        <tr>
                            <td>switch</td>
                            <td>✅ Supported</td>
                            <td><span class="tag-no">Not supported</span></td>
                        </tr>
                        <tr>
                            <td>Comments</td>
                            <td>// and /* */</td>
                            <td><span class="tag-yes">Same as standard C</span></td>
                        </tr>
                    </tbody>
                </table>
            </section>

    


 






            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="c1_3_5.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>1.3 Implementation Techniques</div>
                </a>
                <a href="?complete=18" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next chapter</span>1.4.2 The Cosine Program</div>
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

        // ── Annotated code stepper ───────────────────────────────
        const annotations = [{
                label: 'LINE 1 — Class Declaration',
                title: 'class Cosine { }',
                desc: 'Decaf requires the program to be wrapped in a class — the only class allowed. The class name is Cosine. This is mandatory boilerplate inherited from Java\'s structure.',
                hint: null
            },
            {
                label: 'LINE 2 — Main Method',
                title: 'public static void main(String[] args)',
                desc: 'The standard Java entry point. Decaf programs must have this exact main method signature. Note: String[] args is accepted here even though Decaf does not otherwise support arrays or strings — it is part of the required wrapper.',
                hint: '⚠️ Decaf does not support methods generally — only this one main entry point is required.'
            },
            {
                label: 'LINE 3 — Variable Declarations',
                title: 'float cos, x, n, term, eps, alt;',
                desc: 'All six variables are declared as float. Decaf only supports int and float — no double, no String, no arrays. All variables used in the program must be declared before use.',
                hint: '💡 Decaf only has int and float as data types.'
            },
            {
                label: 'LINE 4 — Initialise x',
                title: 'x = 3.14159;',
                desc: 'Sets x to approximately π (pi) in radians. This is the angle whose cosine we want to compute. cos(π) ≈ −1.',
                hint: '🔢 x = π radians → cos(x) should converge to −1'
            },
            {
                label: 'LINE 5 — Initialise eps',
                title: 'eps = 0.0001;',
                desc: 'eps (epsilon) is the tolerance — the loop continues until the current term drops below this value. A smaller eps gives greater precision but requires more iterations.',
                hint: '🎯 Smaller eps = more accurate result, more iterations.'
            },
            {
                label: 'LINE 6 — Initialise n',
                title: 'n = 1;',
                desc: 'n is the denominator counter. The cosine series denominators are 1!, 2!, 3!, 4!, … but computed incrementally: the first divisor pair is (1)(2), then (3)(4), etc. n starts at 1.',
                hint: '📐 n advances by 2 each iteration: 1, 3, 5, 7, …'
            },
            {
                label: 'LINE 7 — Initialise cos',
                title: 'cos = 1;',
                desc: 'cos is the running sum accumulating the cosine approximation. It starts at 1 because the first term of the cosine series is 1 (the x⁰ term).',
                hint: '∑ cos starts at 1 — the first term of the alternating series.'
            },
            {
                label: 'LINE 8 — Initialise term',
                title: 'term = 1;',
                desc: 'term holds the absolute value of the most recently computed term. It starts at 1 (the first term). The loop condition checks term > eps to decide whether to continue.',
                hint: '🔁 term shrinks each iteration — when it\'s tiny enough, we stop.'
            },
            {
                label: 'LINE 9 — Initialise alt',
                title: 'alt = -1;',
                desc: 'alt is the alternating sign factor: −1, +1, −1, +1, … The first series term after 1 is subtracted (−x²/2), so alt starts at −1. It flips sign each iteration via alt = −alt.',
                hint: '± Alternates: −1 → +1 → −1 → +1 …'
            },
            {
                label: 'LINE 10 — While Loop Condition',
                title: 'while (term > eps)',
                desc: 'The loop continues as long as the current term is greater than the tolerance eps. When a term drops to 0.0001 or below, the remaining terms are negligible and we stop — the approximation is accurate enough.',
                hint: '🛑 Loop exits when term ≤ 0.0001'
            },
            {
                label: 'LINE 11 — Compute Next Term',
                title: 'term = term * x * x / n / (n+1);',
                desc: 'Computes the next term by multiplying the previous term by x² then dividing by n and (n+1). Due to left-to-right associativity of * and /, this evaluates as ((((term×x)×x)÷n)÷(n+1)) — both n and (n+1) are in the denominator.',
                hint: '⚠️ Left-associative: both n and (n+1) divide the result.'
            },
            {
                label: 'LINE 12 — Add Term to Sum',
                title: 'cos = cos + alt * term;',
                desc: 'Adds the signed current term to the cosine accumulator. alt is −1 or +1, so this alternately subtracts and adds: cos = cos − term, cos = cos + term, etc. This implements the alternating series.',
                hint: '± alt * term produces the correct sign for each series term.'
            },
            {
                label: 'LINE 13 — Flip Sign',
                title: 'alt = -alt;',
                desc: 'Flips the sign of alt: −1 becomes +1, and +1 becomes −1. This ensures the next term is added with the opposite sign, implementing the alternating nature of the cosine series.',
                hint: '🔄 Each iteration: −1→+1 or +1→−1'
            },
            {
                label: 'LINE 14 — Advance n',
                title: 'n = n + 2;',
                desc: 'Advances n by 2: 1, 3, 5, 7, … The next iteration will use n and (n+1) as divisors — i.e. (3)(4), then (5)(6), then (7)(8) — corresponding to the growing factorials in the cosine series denominators.',
                hint: '📈 n: 1 → 3 → 5 → 7 → … denominators grow each step.'
            },
            {
                label: 'LINE 15 — Closing Braces',
                title: '} } }',
                desc: 'Closes the while loop body, the main method body, and the class body respectively. Decaf follows Java\'s brace conventions — every opened brace must be closed.',
                hint: null
            }
        ];

        document.querySelectorAll('.line').forEach(line => {
            line.addEventListener('click', () => {
                document.querySelectorAll('.line').forEach(l => l.classList.remove('active'));
                line.classList.add('active');
                const idx = parseInt(line.dataset.idx);
                const a = annotations[idx];
                document.getElementById('ann-label').textContent = a.label;
                document.getElementById('ann-title').textContent = a.title;
                document.getElementById('ann-desc').textContent = a.desc;
                const hintEl = document.getElementById('ann-hint');
                if (a.hint) {
                    hintEl.textContent = a.hint;
                    hintEl.style.display = 'block';
                } else {
                    hintEl.style.display = 'none';
                }
            });
        });
    </script>

</body>

</html>