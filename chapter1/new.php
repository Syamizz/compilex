<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text-to-Speech – CompileX</title>

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
            --blue: #3B82F6;
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

        /* Navbar */
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

        /* Layout */
        .note-container {
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 40px;
            max-width: 1140px;
            margin: 0 auto;
            padding: 36px 24px 80px;
            align-items: start;
        }

        /* TOC */
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

        /* TTS Status pill in sidebar */
        .tts-status-pill {
            margin-top: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 20px;
            background: var(--purple-s);
            font-size: 12px;
            font-weight: 600;
            color: var(--purple);
            cursor: pointer;
            border: none;
            width: 100%;
            transition: background .2s;
        }

        .tts-status-pill:hover {
            background: #DDD6FE;
        }

        .tts-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--green);
            flex-shrink: 0;
        }

        .tts-dot.off {
            background: var(--muted);
        }

        /* Article */
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

        /* Sections */
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

        /* Note boxes */
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

        /* Code blocks */
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

        .prop {
            color: #94E2D5;
        }

        .op {
            color: #F38BA8;
        }

        /* Compare table */
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
        }

        /* ── TTS LIVE DEMO CARD ── */
        .demo-card {
            background: var(--card);
            border: 1.5px solid rgba(99, 102, 241, .2);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin: 24px 0;
        }

        .demo-card-header {
            background: linear-gradient(135deg, #6366F1, #818CF8);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .demo-card-header h4 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: white;
            margin: 0;
        }

        .demo-card-body {
            padding: 20px;
        }

        /* TTS playground */
        .tts-playground {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .tts-textarea {
            width: 100%;
            min-height: 90px;
            border: 1.5px solid rgba(99, 102, 241, .2);
            border-radius: 10px;
            padding: 12px 14px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--text);
            background: var(--bg);
            resize: vertical;
            transition: border-color .2s;
        }

        .tts-textarea:focus {
            outline: none;
            border-color: var(--purple);
        }

        .tts-controls {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .tts-control-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .tts-control-group label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text);
            display: flex;
            justify-content: space-between;
        }

        .tts-control-group label span {
            font-weight: 400;
            color: var(--purple);
        }

        input[type=range] {
            width: 100%;
            accent-color: var(--purple);
        }

        .voice-select {
            width: 100%;
            padding: 8px 12px;
            border: 1.5px solid rgba(99, 102, 241, .2);
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: var(--text);
            background: var(--bg);
            cursor: pointer;
        }

        .voice-select:focus {
            outline: none;
            border-color: var(--purple);
        }

        .tts-btn-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .tts-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            border: none;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s;
        }

        .tts-btn:hover {
            transform: translateY(-1px);
        }

        .tts-btn.speak {
            background: var(--purple);
            color: white;
        }

        .tts-btn.speak:hover {
            background: var(--purple-d);
            box-shadow: 0 4px 16px rgba(99, 102, 241, .35);
        }

        .tts-btn.pause {
            background: var(--amber);
            color: #1E1B4B;
        }

        .tts-btn.stop {
            background: #F1F0FB;
            color: var(--purple);
            border: 1.5px solid rgba(99, 102, 241, .2);
        }

        .tts-status-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 10px;
            background: var(--purple-s);
            font-size: 13px;
            color: var(--purple);
            font-weight: 500;
        }

        .pulse {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--green);
            animation: pulse 1s infinite;
            flex-shrink: 0;
        }

        .pulse.idle {
            background: var(--muted);
            animation: none;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .5;
                transform: scale(1.3);
            }
        }

        /* Stepper */
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
            color: rgba(205, 214, 244, .45);
            line-height: 1.9;
            transition: background .25s, color .25s;
            white-space: pre;
        }

        .stepper-code .line.active {
            background: rgba(99, 102, 241, .28);
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

        /* speak-along highlight */
        .speak-highlight {
            background: rgba(99, 102, 241, .15);
            border-radius: 4px;
            transition: background .3s;
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

        /* Sound toggle in stepper */
        .sound-toggle {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 20px;
            border: 1.5px solid rgba(99, 102, 241, .25);
            background: var(--card);
            color: var(--muted);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s, color .2s;
        }

        .sound-toggle.on {
            background: var(--purple-s);
            color: var(--purple);
            border-color: var(--purple);
        }

        /* Property cards */
        .prop-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin: 20px 0;
        }

        .prop-card {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            padding: 18px;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .prop-name {
            font-family: 'JetBrains Mono', monospace;
            font-size: 14px;
            color: var(--purple);
            font-weight: 700;
            margin-bottom: 6px;
        }

        .prop-range {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .prop-default {
            display: inline-block;
            background: var(--purple-s);
            color: var(--purple);
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 11px;
            font-weight: 700;
        }

        /* Browser support grid */
        .browser-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin: 20px 0;
        }

        .browser-card {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .12);
            border-radius: var(--radius);
            padding: 14px;
            text-align: center;
            box-shadow: var(--shadow);
        }

        .browser-icon {
            font-size: 26px;
            margin-bottom: 6px;
        }

        .browser-name {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 13px;
            color: var(--text);
            margin-bottom: 4px;
        }

        .browser-support {
            font-size: 11px;
            font-weight: 700;
            border-radius: 20px;
            padding: 2px 10px;
            display: inline-block;
        }

        .support-full {
            background: #D1FAE5;
            color: #065F46;
        }

        .support-partial {
            background: #FFFBEB;
            color: #92400E;
        }

        /* Quiz */
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

        /* Chapter nav */
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

        /* Animations */
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

        section:nth-child(7) {
            animation-delay: .47s;
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

            .prop-grid {
                grid-template-columns: 1fr;
            }

            .browser-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .tts-controls {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <?php include '../dashboard.php'; ?>

    <div class="note-container">

        <!-- Sidebar TOC -->
        <nav class="toc">
            <div class="toc-title">🔊 Contents</div>
            <ul>
                <li><a href="#what-is-tts" class="toc-link">1. What is TTS?</a></li>
                <li><a href="#how-it-works" class="toc-link">2. How the API Works</a></li>
                <li><a href="#basic-usage" class="toc-link">3. Basic Usage</a></li>
                <li><a href="#properties" class="toc-link">4. Rate, Pitch &amp; Volume</a></li>
                <li><a href="#voices" class="toc-link">5. Choosing a Voice</a></li>
                <li><a href="#events" class="toc-link">6. Events &amp; Callbacks</a></li>
                <li><a href="#playground" class="toc-link">7. Live Playground</a></li>
                <li><a href="#stepper" class="toc-link">8. Step-by-Step Walkthrough</a></li>
                <li><a href="#browser-support" class="toc-link">9. Browser Support</a></li>
                <li><a href="#quiz" class="toc-link">10. Quiz Yourself</a></li>
            </ul>

            <!-- Global TTS toggle -->
            <button class="tts-status-pill" id="global-tts-toggle" onclick="toggleGlobalTTS()">
                <span class="tts-dot" id="global-dot"></span>
                <span id="global-tts-label">Auto-read: ON</span>
            </button>

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

        <!-- Main Content -->
        <article class="content">

            <header class="content-header">
                <div class="chapter-tag">🔊 Web APIs</div>
                <h1>Text-to-Speech with the Web Speech API</h1>
                <div class="metadata">
                    <span>⏱ 20 min read</span>
                    <span>🎯 Beginner–Intermediate</span>
                    <span>🌐 Browser API</span>
                </div>
            </header>

            <!-- ── 1. What is TTS? ── -->
            <section id="what-is-tts">
                <h2><span class="sec-icon">🔊</span> What is Text-to-Speech?</h2>
                <p>
                    <strong>Text-to-Speech (TTS)</strong> converts written text into spoken audio. Modern browsers expose this
                    capability natively through the <strong>Web Speech API</strong> — specifically the
                    <code>SpeechSynthesis</code> interface — meaning you can add voice to any webpage
                    with <em>zero external libraries</em> and zero server calls.
                </p>
                <p>
                    The browser hands your text to the operating system's built-in speech engine, which then generates audio
                    through the user's speakers or headphones.
                </p>
                <div class="note-box key">
                    <span class="box-icon">🔑</span>
                    <div>
                        <strong>Key insight:</strong> TTS uses the device's own voice engine. This means voices differ across
                        operating systems — a Mac will sound different from Windows or Android for the same code.
                    </div>
                </div>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Concept</th>
                            <th>What it is</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>SpeechSynthesis</td>
                            <td>The controller — start, pause, stop, and list voices</td>
                        </tr>
                        <tr>
                            <td>SpeechSynthesisUtterance</td>
                            <td>One "chunk" of speech — the text + settings for that chunk</td>
                        </tr>
                        <tr>
                            <td>SpeechSynthesisVoice</td>
                            <td>A specific voice (language, gender, engine) available on the device</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <!-- ── 2. How it works ── -->
            <section id="how-it-works">
                <h2><span class="sec-icon">⚙️</span> How the API Works</h2>
                <p>
                    There are only two objects you need to understand. Think of <code>SpeechSynthesis</code> as the
                    <em>speaker system</em> and <code>SpeechSynthesisUtterance</code> as the <em>script</em> you hand to it.
                </p>

                <div class="code-wrap">
                    <div class="code-header">
                        <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                        <span class="code-lang">Conceptual Overview</span>
                    </div>
                    <pre><code><span class="cm">// The global controller — always available in browsers</span>
<span class="kw">const</span> synth = <span class="prop">window</span>.<span class="fn">speechSynthesis</span>;

<span class="cm">// One utterance = one piece of text + its settings</span>
<span class="kw">const</span> utterance = <span class="kw">new</span> <span class="fn">SpeechSynthesisUtterance</span>(<span class="str">"Hello world"</span>);

<span class="cm">// Hand the script to the speaker</span>
synth.<span class="fn">speak</span>(utterance);</code></pre>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        <strong>Queue behaviour:</strong> Multiple <code>speak()</code> calls queue up — the browser speaks
                        them one after another. Call <code>synth.cancel()</code> first if you want to interrupt and start fresh.
                    </div>
                </div>
            </section>

            <!-- ── 3. Basic Usage ── -->
            <section id="basic-usage">
                <h2><span class="sec-icon">▶️</span> Basic Usage</h2>
                <p>Here is the minimal working example — three lines of JavaScript to speak any text:</p>

                <div class="code-wrap">
                    <div class="code-header">
                        <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                        <span class="code-lang">JavaScript — Minimal TTS</span>
                        <button class="copy-btn" onclick="copyBlock('basic-block', this)">Copy</button>
                    </div>
                    <pre><code id="basic-block"><span class="kw">function</span> <span class="fn">speak</span>(text) {
  <span class="kw">const</span> utterance = <span class="kw">new</span> <span class="fn">SpeechSynthesisUtterance</span>(text);
  <span class="prop">window</span>.<span class="fn">speechSynthesis</span>.<span class="fn">speak</span>(utterance);
}

<span class="cm">// Call it anywhere</span>
<span class="fn">speak</span>(<span class="str">"Welcome to CompileX!"</span>);</code></pre>
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>User gesture required:</strong> Most browsers block TTS that fires automatically on page load
                        without a user interaction (click, tap, keypress). Always trigger <code>speak()</code> from an event
                        handler to avoid silent failures.
                    </div>
                </div>

                <h3>Stopping and Pausing</h3>
                <div class="code-wrap">
                    <div class="code-header">
                        <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                        <span class="code-lang">JavaScript — Controls</span>
                        <button class="copy-btn" onclick="copyBlock('controls-block', this)">Copy</button>
                    </div>
                    <pre><code id="controls-block"><span class="kw">const</span> synth = <span class="prop">window</span>.<span class="fn">speechSynthesis</span>;

synth.<span class="fn">pause</span>();    <span class="cm">// Pause mid-speech</span>
synth.<span class="fn">resume</span>();   <span class="cm">// Resume from where it paused</span>
synth.<span class="fn">cancel</span>();   <span class="cm">// Stop immediately, clear the queue</span>

<span class="cm">// Check state</span>
synth.<span class="prop">speaking</span>;   <span class="cm">// true if currently speaking</span>
synth.<span class="prop">paused</span>;     <span class="cm">// true if paused</span>
synth.<span class="prop">pending</span>;    <span class="cm">// true if queued utterances are waiting</span></code></pre>
                </div>
            </section>

            <!-- ── 4. Properties ── -->
            <section id="properties">
                <h2><span class="sec-icon">🎚️</span> Rate, Pitch &amp; Volume</h2>
                <p>
                    Each <code>SpeechSynthesisUtterance</code> has three numeric properties you can tune before calling
                    <code>speak()</code>. They do not affect other utterances in the queue.
                </p>

                <div class="prop-grid">
                    <div class="prop-card">
                        <div class="prop-name">.rate</div>
                        <div class="prop-range">Range: 0.1 → 10</div>
                        <div class="prop-default">default: 1</div>
                        <p style="font-size:12px;color:var(--muted);margin-top:10px;">Speaking speed. 0.5 = half speed, 2 = double speed.</p>
                    </div>
                    <div class="prop-card">
                        <div class="prop-name">.pitch</div>
                        <div class="prop-range">Range: 0 → 2</div>
                        <div class="prop-default">default: 1</div>
                        <p style="font-size:12px;color:var(--muted);margin-top:10px;">Voice pitch. 0 = very low, 2 = very high.</p>
                    </div>
                    <div class="prop-card">
                        <div class="prop-name">.volume</div>
                        <div class="prop-range">Range: 0 → 1</div>
                        <div class="prop-default">default: 1</div>
                        <p style="font-size:12px;color:var(--muted);margin-top:10px;">Loudness. 0 = silent, 1 = full volume.</p>
                    </div>
                </div>

                <div class="code-wrap">
                    <div class="code-header">
                        <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                        <span class="code-lang">JavaScript — Setting Properties</span>
                        <button class="copy-btn" onclick="copyBlock('props-block', this)">Copy</button>
                    </div>
                    <pre><code id="props-block"><span class="kw">const</span> utterance = <span class="kw">new</span> <span class="fn">SpeechSynthesisUtterance</span>(<span class="str">"Adjusting speed and pitch"</span>);

utterance.<span class="prop">rate</span>   = <span class="num">0.9</span>;   <span class="cm">// Slightly slower than normal</span>
utterance.<span class="prop">pitch</span>  = <span class="num">1.1</span>;   <span class="cm">// Slightly higher pitch</span>
utterance.<span class="prop">volume</span> = <span class="num">1.0</span>;   <span class="cm">// Full volume</span>
utterance.<span class="prop">lang</span>   = <span class="str">"en-US"</span>; <span class="cm">// Language / accent hint</span>

<span class="prop">window</span>.<span class="fn">speechSynthesis</span>.<span class="fn">speak</span>(utterance);</code></pre>
                </div>
            </section>

            <!-- ── 5. Voices ── -->
            <section id="voices">
                <h2><span class="sec-icon">🎤</span> Choosing a Voice</h2>
                <p>
                    <code>speechSynthesis.getVoices()</code> returns an array of available
                    <code>SpeechSynthesisVoice</code> objects. The list loads asynchronously — you must wait for the
                    <code>voiceschanged</code> event before using it.
                </p>

                <div class="code-wrap">
                    <div class="code-header">
                        <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                        <span class="code-lang">JavaScript — Listing &amp; Picking a Voice</span>
                        <button class="copy-btn" onclick="copyBlock('voices-block', this)">Copy</button>
                    </div>
                    <pre><code id="voices-block"><span class="kw">let</span> voices = [];

<span class="cm">// Voices load async — listen for the ready event</span>
<span class="prop">window</span>.<span class="fn">speechSynthesis</span>.<span class="prop">onvoiceschanged</span> = () => {
  voices = <span class="prop">window</span>.<span class="fn">speechSynthesis</span>.<span class="fn">getVoices</span>();
  <span class="fn">console</span>.<span class="fn">log</span>(voices); <span class="cm">// Inspect available voices</span>
};

<span class="cm">// Assign a specific voice to an utterance</span>
<span class="kw">function</span> <span class="fn">speakWithVoice</span>(text, voiceName) {
  <span class="kw">const</span> utterance = <span class="kw">new</span> <span class="fn">SpeechSynthesisUtterance</span>(text);
  <span class="kw">const</span> voice = voices.<span class="fn">find</span>(v => v.<span class="prop">name</span> === voiceName);
  <span class="kw">if</span> (voice) utterance.<span class="prop">voice</span> = voice;
  <span class="prop">window</span>.<span class="fn">speechSynthesis</span>.<span class="fn">speak</span>(utterance);
}</code></pre>
                </div>

                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>
                        Each <code>SpeechSynthesisVoice</code> has three useful properties:
                        <code>voice.name</code> (e.g. "Samantha"),
                        <code>voice.lang</code> (e.g. "en-US"), and
                        <code>voice.localService</code> (true if the voice works offline).
                    </div>
                </div>
            </section>

            <!-- ── 6. Events ── -->
            <section id="events">
                <h2><span class="sec-icon">📡</span> Events &amp; Callbacks</h2>
                <p>
                    Utterances fire events at key moments. You can hook into these to update UI, highlight text,
                    or chain actions together.
                </p>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>When it fires</th>
                            <th>Common use</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>onstart</code></td>
                            <td>Speech begins</td>
                            <td>Show a "speaking" indicator</td>
                        </tr>
                        <tr>
                            <td><code>onend</code></td>
                            <td>Speech finishes</td>
                            <td>Hide the indicator, move to next step</td>
                        </tr>
                        <tr>
                            <td><code>onpause</code></td>
                            <td>Speech is paused</td>
                            <td>Update pause/resume button state</td>
                        </tr>
                        <tr>
                            <td><code>onresume</code></td>
                            <td>Speech resumes</td>
                            <td>Update pause/resume button state</td>
                        </tr>
                        <tr>
                            <td><code>onerror</code></td>
                            <td>Something went wrong</td>
                            <td>Show fallback message</td>
                        </tr>
                        <tr>
                            <td><code>onboundary</code></td>
                            <td>Word or sentence boundary reached</td>
                            <td>Highlight the current word in text</td>
                        </tr>
                    </tbody>
                </table>

                <div class="code-wrap">
                    <div class="code-header">
                        <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                        <span class="code-lang">JavaScript — Using Events</span>
                        <button class="copy-btn" onclick="copyBlock('events-block', this)">Copy</button>
                    </div>
                    <pre><code id="events-block"><span class="kw">const</span> utterance = <span class="kw">new</span> <span class="fn">SpeechSynthesisUtterance</span>(<span class="str">"Listen to me carefully."</span>);

utterance.<span class="prop">onstart</span> = () => {
  <span class="fn">console</span>.<span class="fn">log</span>(<span class="str">"Started speaking"</span>);
  statusEl.<span class="prop">textContent</span> = <span class="str">"🔊 Speaking..."</span>;
};

utterance.<span class="prop">onend</span> = () => {
  <span class="fn">console</span>.<span class="fn">log</span>(<span class="str">"Finished speaking"</span>);
  statusEl.<span class="prop">textContent</span> = <span class="str">"✅ Done"</span>;
};

utterance.<span class="prop">onerror</span> = (e) => {
  <span class="fn">console</span>.<span class="fn">error</span>(<span class="str">"TTS error:"</span>, e.<span class="prop">error</span>);
};

<span class="prop">window</span>.<span class="fn">speechSynthesis</span>.<span class="fn">speak</span>(utterance);</code></pre>
                </div>
            </section>

            <!-- ── 7. Live Playground ── -->
            <section id="playground">
                <h2><span class="sec-icon">🎮</span> Live Playground</h2>
                <p>
                    Try everything you've just learned. Type any text, adjust the sliders, pick a voice,
                    and hit <strong>Speak</strong>.
                </p>

                <div class="demo-card">
                    <div class="demo-card-header">
                        <span style="font-size:20px;">🔊</span>
                        <h4>TTS Playground — try it live</h4>
                    </div>
                    <div class="demo-card-body">
                        <div class="tts-playground">

                            <textarea class="tts-textarea" id="tts-input" placeholder="Type anything here and click Speak...">Welcome to CompileX! This is the Web Speech API in action — running entirely in your browser with no server needed.</textarea>

                            <div class="tts-controls">
                                <div class="tts-control-group">
                                    <label>Rate (speed) <span id="rate-val">1.0</span></label>
                                    <input type="range" min="0.1" max="2" step="0.1" value="1" id="rate-slider" oninput="document.getElementById('rate-val').textContent=parseFloat(this.value).toFixed(1)">
                                </div>
                                <div class="tts-control-group">
                                    <label>Pitch <span id="pitch-val">1.0</span></label>
                                    <input type="range" min="0" max="2" step="0.1" value="1" id="pitch-slider" oninput="document.getElementById('pitch-val').textContent=parseFloat(this.value).toFixed(1)">
                                </div>
                                <div class="tts-control-group">
                                    <label>Volume <span id="vol-val">1.0</span></label>
                                    <input type="range" min="0" max="1" step="0.05" value="1" id="vol-slider" oninput="document.getElementById('vol-val').textContent=parseFloat(this.value).toFixed(2)">
                                </div>
                                <div class="tts-control-group">
                                    <label>Voice</label>
                                    <select class="voice-select" id="voice-select"></select>
                                </div>
                            </div>

                            <div class="tts-btn-row">
                                <button class="tts-btn speak" onclick="playgroundSpeak()">▶ Speak</button>
                                <button class="tts-btn pause" onclick="playgroundPause()" id="pg-pause-btn">⏸ Pause</button>
                                <button class="tts-btn stop" onclick="playgroundStop()">⏹ Stop</button>
                            </div>

                            <div class="tts-status-bar" id="pg-status">
                                <span class="pulse idle" id="pg-pulse"></span>
                                <span id="pg-status-text">Ready — click Speak to begin</span>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

            <!-- ── 8. Stepper ── -->
            <section id="stepper">
                <h2><span class="sec-icon">🧩</span> Step-by-Step Walkthrough</h2>
                <p>
                    Step through each part of a complete, production-ready TTS function. Sound reads each
                    step aloud automatically — toggle it with the button in the header.
                </p>

                <div class="stepper-wrap">
                    <div class="stepper-header">
                        <h4>🔊 Building a complete speak() function</h4>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <button class="sound-toggle on" id="sound-toggle-btn" onclick="toggleStepperSound()">🔊 Sound On</button>
                            <span class="step-counter" id="step-counter">Step 1 of 8</span>
                        </div>
                    </div>
                    <div class="stepper-body">
                        <div class="stepper-code">
                            <pre><code id="step-code"></code></pre>
                        </div>
                        <div class="stepper-explain">
                            <div class="explain-box">
                                <div class="explain-step-label" id="step-label">STEP 1</div>
                                <div class="explain-title" id="step-title"></div>
                                <div class="explain-desc" id="step-desc"></div>
                            </div>
                            <div class="stepper-nav">
                                <button class="step-btn" id="btn-prev" onclick="stepMove(-1)" disabled>← Prev</button>
                                <button class="step-btn primary" id="btn-next" onclick="stepMove(1)">Next →</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ── 9. Browser Support ── -->
            <section id="browser-support">
                <h2><span class="sec-icon">🌐</span> Browser Support</h2>
                <p>The Web Speech API (TTS side) has broad support across modern browsers:</p>

                <div class="browser-grid">
                    <div class="browser-card">
                        <div class="browser-icon">🟡</div>
                        <div class="browser-name">Chrome</div>
                        <span class="browser-support support-full">Full support</span>
                        <p style="font-size:11px;color:var(--muted);margin-top:8px;">Best voice selection; uses Google TTS engine on Android</p>
                    </div>
                    <div class="browser-card">
                        <div class="browser-icon">🔵</div>
                        <div class="browser-name">Edge</div>
                        <span class="browser-support support-full">Full support</span>
                        <p style="font-size:11px;color:var(--muted);margin-top:8px;">Chromium-based; includes high-quality Neural voices</p>
                    </div>
                    <div class="browser-card">
                        <div class="browser-icon">🧭</div>
                        <div class="browser-name">Safari</div>
                        <span class="browser-support support-full">Full support</span>
                        <p style="font-size:11px;color:var(--muted);margin-top:8px;">Uses macOS/iOS native voices; very natural on Apple devices</p>
                    </div>
                    <div class="browser-card">
                        <div class="browser-icon">🦊</div>
                        <div class="browser-name">Firefox</div>
                        <span class="browser-support support-partial">Partial</span>
                        <p style="font-size:11px;color:var(--muted);margin-top:8px;"><code>onboundary</code> event not supported; basics work fine</p>
                    </div>
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        Always check with <code>if ('speechSynthesis' in window)</code> before calling any TTS method,
                        so your page degrades gracefully in unsupported environments.
                    </div>
                </div>

                <div class="code-wrap">
                    <div class="code-header">
                        <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                        <span class="code-lang">JavaScript — Feature Detection</span>
                        <button class="copy-btn" onclick="copyBlock('detect-block', this)">Copy</button>
                    </div>
                    <pre><code id="detect-block"><span class="kw">if</span> (<span class="str">'speechSynthesis'</span> <span class="kw">in</span> <span class="prop">window</span>) {
  <span class="cm">// Safe to use TTS</span>
  <span class="fn">speak</span>(<span class="str">"TTS is supported!"</span>);
} <span class="kw">else</span> {
  <span class="cm">// Fallback — display text visually instead</span>
  <span class="fn">console</span>.<span class="fn">warn</span>(<span class="str">"Speech synthesis not supported"</span>);
}</code></pre>
                </div>
            </section>

            <!-- ── 10. Quiz ── -->
            <section id="quiz">
                <h2><span class="sec-icon">🧠</span> Quiz Yourself</h2>

                <!-- Q1 -->
                <div class="mini-quiz">
                    <div class="quiz-header"><span style="font-size:18px;">❓</span>
                        <h4>Question 1</h4>
                    </div>
                    <div class="quiz-body">
                        <div class="quiz-q">What does calling <code>window.speechSynthesis.cancel()</code> do?</div>
                        <div class="quiz-options" id="q1">
                            <div class="quiz-opt" onclick="answerQ('q1',this,false)">
                                <div class="opt-circle">A</div>Pauses speech and keeps the queue
                            </div>
                            <div class="quiz-opt" onclick="answerQ('q1',this,true)">
                                <div class="opt-circle">B</div>Stops speech immediately and clears the entire queue
                            </div>
                            <div class="quiz-opt" onclick="answerQ('q1',this,false)">
                                <div class="opt-circle">C</div>Mutes the audio without stopping
                            </div>
                            <div class="quiz-opt" onclick="answerQ('q1',this,false)">
                                <div class="opt-circle">D</div>Removes all installed voices
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
                        <div class="quiz-q">Which property controls how fast the text is spoken?</div>
                        <div class="quiz-options" id="q2">
                            <div class="quiz-opt" onclick="answerQ('q2',this,false)">
                                <div class="opt-circle">A</div><code>utterance.pitch</code>
                            </div>
                            <div class="quiz-opt" onclick="answerQ('q2',this,false)">
                                <div class="opt-circle">B</div><code>utterance.volume</code>
                            </div>
                            <div class="quiz-opt" onclick="answerQ('q2',this,true)">
                                <div class="opt-circle">C</div><code>utterance.rate</code>
                            </div>
                            <div class="quiz-opt" onclick="answerQ('q2',this,false)">
                                <div class="opt-circle">D</div><code>utterance.speed</code>
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
                        <div class="quiz-q">Why must you listen to <code>voiceschanged</code> before calling <code>getVoices()</code>?</div>
                        <div class="quiz-options" id="q3">
                            <div class="quiz-opt" onclick="answerQ('q3',this,false)">
                                <div class="opt-circle">A</div>To avoid security errors in Chrome
                            </div>
                            <div class="quiz-opt" onclick="answerQ('q3',this,true)">
                                <div class="opt-circle">B</div>Voices load asynchronously — the list is empty until the event fires
                            </div>
                            <div class="quiz-opt" onclick="answerQ('q3',this,false)">
                                <div class="opt-circle">C</div>The browser won't speak until a voice is confirmed
                            </div>
                            <div class="quiz-opt" onclick="answerQ('q3',this,false)">
                                <div class="opt-circle">D</div>It's only needed on Firefox
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
                        <div class="quiz-q">Which utterance event fires when speech finishes completely?</div>
                        <div class="quiz-options" id="q4">
                            <div class="quiz-opt" onclick="answerQ('q4',this,false)">
                                <div class="opt-circle">A</div><code>onboundary</code>
                            </div>
                            <div class="quiz-opt" onclick="answerQ('q4',this,false)">
                                <div class="opt-circle">B</div><code>onpause</code>
                            </div>
                            <div class="quiz-opt" onclick="answerQ('q4',this,false)">
                                <div class="opt-circle">C</div><code>onstart</code>
                            </div>
                            <div class="quiz-opt" onclick="answerQ('q4',this,true)">
                                <div class="opt-circle">D</div><code>onend</code>
                            </div>
                        </div>
                        <div class="quiz-feedback" id="q4-fb"></div>
                    </div>
                </div>

            </section>

            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="c1_1.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous</span>1.1 Introduction to Compilers</div>
                </a>
                <a href="c1_2.php" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next</span>1.2 The Phases of a Compiler</div>
                    <span>→</span>
                </a>
            </div>

        </article>
    </div>

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
            tocLinks.forEach(l => l.classList.toggle('active', l.getAttribute('href') === '#' + current));
        });

        // ── Copy code blocks ─────────────────────────────────────
        function copyBlock(id, btn) {
            navigator.clipboard.writeText(document.getElementById(id).innerText).then(() => {
                btn.textContent = 'Copied!';
                setTimeout(() => btn.textContent = 'Copy', 1800);
            });
        }

        // ── Global TTS toggle ────────────────────────────────────
        let globalTTS = true;

        function toggleGlobalTTS() {
            globalTTS = !globalTTS;
            const dot = document.getElementById('global-dot');
            const label = document.getElementById('global-tts-label');
            dot.className = 'tts-dot' + (globalTTS ? '' : ' off');
            label.textContent = 'Auto-read: ' + (globalTTS ? 'ON' : 'OFF');
            if (!globalTTS) window.speechSynthesis.cancel();
        }

        // ── Core speak helper ────────────────────────────────────
        function speak(text, opts = {}) {
            if (!('speechSynthesis' in window)) return;
            window.speechSynthesis.cancel();
            const u = new SpeechSynthesisUtterance(text);
            u.rate = opts.rate ?? 0.95;
            u.pitch = opts.pitch ?? 1.0;
            u.volume = opts.volume ?? 1.0;
            if (opts.voice) u.voice = opts.voice;
            if (opts.onstart) u.onstart = opts.onstart;
            if (opts.onend) u.onend = opts.onend;
            window.speechSynthesis.speak(u);
        }

        // ── Playground ───────────────────────────────────────────
        let pgVoices = [];
        let pgPaused = false;

        window.speechSynthesis.onvoiceschanged = () => {
            pgVoices = window.speechSynthesis.getVoices();
            const sel = document.getElementById('voice-select');
            sel.innerHTML = pgVoices.map((v, i) =>
                `<option value="${i}">${v.name} (${v.lang})</option>`
            ).join('');
        };

        function pgSetStatus(text, active) {
            document.getElementById('pg-status-text').textContent = text;
            const pulse = document.getElementById('pg-pulse');
            pulse.className = 'pulse' + (active ? '' : ' idle');
        }

        function playgroundSpeak() {
            const text = document.getElementById('tts-input').value.trim();
            if (!text) return;
            const rate = parseFloat(document.getElementById('rate-slider').value);
            const pitch = parseFloat(document.getElementById('pitch-slider').value);
            const volume = parseFloat(document.getElementById('vol-slider').value);
            const sel = document.getElementById('voice-select');
            const voice = pgVoices[sel.value] || null;
            pgPaused = false;
            document.getElementById('pg-pause-btn').textContent = '⏸ Pause';
            speak(text, {
                rate,
                pitch,
                volume,
                voice,
                onstart: () => pgSetStatus('🔊 Speaking...', true),
                onend: () => pgSetStatus('✅ Done', false)
            });
            pgSetStatus('🔊 Speaking...', true);
        }

        function playgroundPause() {
            const synth = window.speechSynthesis;
            const btn = document.getElementById('pg-pause-btn');
            if (pgPaused) {
                synth.resume();
                pgPaused = false;
                btn.textContent = '⏸ Pause';
                pgSetStatus('🔊 Speaking...', true);
            } else {
                synth.pause();
                pgPaused = true;
                btn.textContent = '▶ Resume';
                pgSetStatus('⏸ Paused', false);
            }
        }

        function playgroundStop() {
            window.speechSynthesis.cancel();
            pgPaused = false;
            document.getElementById('pg-pause-btn').textContent = '⏸ Pause';
            pgSetStatus('⏹ Stopped', false);
        }

        // ── Stepper data ─────────────────────────────────────────
        const stepLines = [
            `function speak(text, opts = {}) {`,
            `  if (!('speechSynthesis' in window)) return;`,
            `  window.speechSynthesis.cancel();`,
            `  const u = new SpeechSynthesisUtterance(text);`,
            `  u.rate   = opts.rate   ?? 0.95;`,
            `  u.pitch  = opts.pitch  ?? 1.0;`,
            `  u.volume = opts.volume ?? 1.0;`,
            `  if (opts.voice) u.voice = opts.voice;`,
            `  if (opts.onend) u.onend = opts.onend;`,
            `  window.speechSynthesis.speak(u);`,
            `}`,
        ];

        const stepData = [{
                active: 0,
                label: 'STEP 1',
                title: 'Declare the function',
                desc: 'We define a reusable speak() function that accepts the text to say and an optional opts object for rate, pitch, volume, voice, and callbacks.'
            },
            {
                active: 1,
                label: 'STEP 2',
                title: 'Feature detection',
                desc: "Check whether the browser supports the Web Speech API before doing anything. If not, we return early rather than crash. This is called 'graceful degradation'."
            },
            {
                active: 2,
                label: 'STEP 3',
                title: 'Cancel any current speech',
                desc: 'Cancel stops whatever is currently playing and empties the queue. This prevents words from piling up when the user clicks Speak multiple times quickly.'
            },
            {
                active: 3,
                label: 'STEP 4',
                title: 'Create an Utterance',
                desc: 'SpeechSynthesisUtterance is the object that wraps the text and all its settings. Think of it as one "script" handed to the speech engine.'
            },
            {
                active: 4,
                label: 'STEP 5',
                title: 'Set the rate',
                desc: 'utterance.rate controls speaking speed. We use the nullish coalescing operator (??) so that if opts.rate is not provided, we default to 0.95 — slightly slower than normal for clarity.'
            },
            {
                active: 5,
                label: 'STEP 6',
                title: 'Set the pitch',
                desc: 'utterance.pitch adjusts how high or low the voice sounds. 1.0 is the natural default. Values above 1 sound higher, below 1 sound deeper.'
            },
            {
                active: 6,
                label: 'STEP 7',
                title: 'Set the volume',
                desc: 'utterance.volume ranges 0 (silent) to 1 (full). We default to 1.0 — full volume — unless the caller overrides it.'
            },
            {
                active: 7,
                label: 'STEP 8',
                title: 'Assign a voice (optional)',
                desc: 'If a specific SpeechSynthesisVoice object was passed in opts.voice, we assign it. Otherwise the browser picks the default voice for the language.'
            },
            {
                active: 8,
                label: 'STEP 9',
                title: 'Wire up the onend callback',
                desc: 'If the caller provided an onend function, we attach it. This fires when speech finishes, letting us chain actions — like advancing to the next step automatically.'
            },
            {
                active: 9,
                label: 'STEP 10',
                title: 'Speak!',
                desc: 'Finally, we pass the configured utterance to the speech synthesiser. The browser queues it and begins speaking. All the setup above happens in microseconds.'
            },
        ];

        let currentStep = 0;
        let stepperSound = true;

        function toggleStepperSound() {
            stepperSound = !stepperSound;
            const btn = document.getElementById('sound-toggle-btn');
            btn.textContent = stepperSound ? '🔊 Sound On' : '🔇 Sound Off';
            btn.className = 'sound-toggle' + (stepperSound ? ' on' : '');
            if (!stepperSound) window.speechSynthesis.cancel();
        }

        function renderStep() {
            const s = stepData[currentStep];
            document.getElementById('step-code').innerHTML = stepLines.map((line, i) =>
                `<span class="line${i === s.active ? ' active' : ''}">${line.replace(/</g,'&lt;').replace(/>/g,'&gt;')}</span>`
            ).join('\n');
            document.getElementById('step-label').textContent = s.label;
            document.getElementById('step-title').textContent = s.title;
            document.getElementById('step-desc').textContent = s.desc;
            document.getElementById('step-counter').textContent = `Step ${currentStep + 1} of ${stepData.length}`;
            document.getElementById('btn-prev').disabled = currentStep === 0;
            const nextBtn = document.getElementById('btn-next');
            nextBtn.disabled = currentStep === stepData.length - 1;
            nextBtn.textContent = currentStep === stepData.length - 1 ? '✅ Done' : 'Next →';
            if (stepperSound && globalTTS) speak(s.title + '. ' + s.desc);
        }

        function stepMove(dir) {
            currentStep = Math.max(0, Math.min(stepData.length - 1, currentStep + dir));
            renderStep();
        }

        renderStep();

        // ── Quiz ─────────────────────────────────────────────────
        const feedbacks = {
            q1: {
                ok: 'Correct! cancel() stops speech immediately and clears the queue — unlike pause(), which keeps the queue intact.',
                bad: 'Not quite. cancel() does not pause — it stops everything and empties the queue.'
            },
            q2: {
                ok: 'Correct! utterance.rate controls speed. 0.5 = half speed, 2 = double speed.',
                bad: 'Not quite. The rate property controls speed. pitch controls tone, volume controls loudness, and .speed does not exist.'
            },
            q3: {
                ok: 'Correct! getVoices() returns an empty array before the browser finishes loading them. voiceschanged fires when the list is ready.',
                bad: 'Not quite. The reason is async loading — the voice list is empty until voiceschanged fires.'
            },
            q4: {
                ok: 'Correct! onend fires when the utterance finishes speaking completely.',
                bad: 'Not quite. onend is the event for completion. onboundary fires at word/sentence boundaries, onpause when paused, onstart when speaking begins.'
            },
        };

        function answerQ(qId, el, correct) {
            const opts = document.getElementById(qId).querySelectorAll('.quiz-opt');
            opts.forEach(o => o.classList.add('answered'));
            el.classList.add(correct ? 'correct' : 'wrong');
            const fb = document.getElementById(qId + '-fb');
            fb.textContent = correct ? '✅ ' + feedbacks[qId].ok : '❌ ' + feedbacks[qId].bad;
            fb.className = 'quiz-feedback show ' + (correct ? 'ok' : 'bad');
            if (globalTTS) speak(correct ? 'Correct! ' + feedbacks[qId].ok : 'Not quite. ' + feedbacks[qId].bad);
        }
    </script>

</body>

</html>