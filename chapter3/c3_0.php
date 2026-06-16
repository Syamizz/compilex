<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter 3.0.3–3.0.5 – CompileX</title>

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

        /* Grammar box */
        .grammar-box {
            background: var(--code-bg);
            border-radius: var(--radius);
            padding: 20px 24px;
            margin: 18px 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .2);
        }

        .grammar-box .g-title {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 14px;
            color: #A5B4FC;
            margin-bottom: 12px;
        }

        .grammar-box .g-rule {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            line-height: 2.1;
            display: flex;
            gap: 12px;
            align-items: baseline;
        }

        .grammar-box .g-num {
            color: rgba(205, 214, 244, .3);
            font-size: 11px;
            min-width: 20px;
        }

        .g-nt {
            color: #CBA6F7;
            font-weight: 700;
        }

        .g-t {
            color: #A6E3A1;
        }

        .g-ep {
            color: #F38BA8;
            font-style: italic;
        }

        /* Derivation tree SVG panels */
        .tree-panel {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            padding: 16px;
            box-shadow: var(--shadow);
            overflow-x: auto;
        }

        .tree-panel-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 13px;
            color: var(--purple);
            margin-bottom: 10px;
        }

        /* Pushdown machine */
        .pdm-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            margin: 16px 0;
        }

        .pdm-table th {
            background: var(--code-bg);
            color: #A5B4FC;
            padding: 8px 10px;
            text-align: center;
            font-size: 11px;
            font-weight: 700;
        }

        .pdm-table td {
            padding: 7px 10px;
            border: 1px solid #EEF2FF;
            text-align: center;
            color: var(--muted);
            background: var(--card);
        }

        .pdm-table td.stack-sym {
            font-weight: 700;
            color: var(--purple);
            background: var(--purple-s);
        }

        .pdm-table td.accept {
            color: var(--green);
            font-weight: 700;
        }

        .pdm-table td.reject {
            color: var(--red);
            opacity: .6;
        }

        .pdm-table td.action {
            color: var(--text);
            font-size: 11px;
            line-height: 1.5;
        }

        /* Stack trace */
        .stack-trace {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: flex-end;
            margin: 16px 0;
        }

        .st-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .st-stack {
            display: flex;
            flex-direction: column-reverse;
            gap: 2px;
            min-height: 60px;
            align-items: center;
        }

        .st-sym {
            width: 36px;
            height: 24px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            font-weight: 700;
        }

        .st-input {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--amber);
        }

        .st-state {
            font-size: 10px;
            color: var(--purple);
            font-family: 'Syne', sans-serif;
            font-weight: 700;
        }

        .sym-x {
            background: var(--purple-s);
            color: var(--purple);
        }

        .sym-bot {
            background: #F1F5F9;
            color: #475569;
        }

        .sym-special {
            background: #FEF3C7;
            color: #92400E;
        }

        /* Interactive PDM simulator */
        .pdm-sim {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin: 24px 0;
        }

        .pdm-sim-header {
            background: linear-gradient(135deg, #6366F1, #818CF8);
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pdm-sim-header h4 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: white;
            margin: 0;
        }

        .pdm-sim-body {
            padding: 20px;
        }

        .pdm-ctrl {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 14px;
            align-items: center;
        }

        .pdm-sel {
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

        .pdm-inp {
            font-family: 'JetBrains Mono', monospace;
            font-size: 14px;
            padding: 8px 14px;
            border: 1.5px solid rgba(99, 102, 241, .3);
            border-radius: 8px;
            outline: none;
            width: 180px;
            color: var(--text);
            background: var(--bg);
        }

        .pdm-inp:focus {
            border-color: var(--purple);
        }

        .pdm-btn {
            padding: 8px 18px;
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

        .pdm-btn:hover {
            background: var(--purple-d);
        }

        .pdm-btn.sec {
            background: transparent;
            border: 1.5px solid rgba(99, 102, 241, .3);
            color: var(--purple);
        }

        .pdm-btn.sec:hover {
            background: var(--purple-s);
        }

        .pdm-tape {
            display: flex;
            gap: 5px;
            margin: 12px 0;
            flex-wrap: wrap;
        }

        .pdm-char {
            width: 38px;
            height: 38px;
            border: 2px solid rgba(99, 102, 241, .2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'JetBrains Mono', monospace;
            font-size: 15px;
            font-weight: 700;
            color: var(--muted);
            background: var(--card);
            transition: all .25s;
        }

        .pdm-char.read {
            border-color: var(--purple);
            color: var(--purple);
            background: var(--purple-s);
        }

        .pdm-char.current {
            border-color: var(--amber);
            color: #92400E;
            background: #FEF3C7;
            transform: scale(1.1);
        }

        .pdm-stack-vis {
            display: flex;
            flex-direction: column-reverse;
            gap: 3px;
            min-height: 40px;
            margin: 8px 0;
        }

        .pdm-stack-item {
            padding: 5px 14px;
            border-radius: 6px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            font-weight: 700;
            text-align: center;
            width: fit-content;
        }

        .pdm-status {
            margin-top: 12px;
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            display: none;
        }

        .pdm-status.show {
            display: block;
        }

        .pdm-status.accepted {
            background: #F0FDF4;
            color: #065F46;
            border: 1.5px solid var(--green);
        }

        .pdm-status.rejected {
            background: #FFF1F2;
            color: #9F1239;
            border: 1.5px solid var(--red);
        }

        .pdm-log {
            margin-top: 10px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--muted);
            background: var(--code-bg);
            padding: 10px 14px;
            border-radius: 8px;
            min-height: 28px;
        }

        /* Infix→Postfix demo */
        .postfix-wrap {
            background: var(--card);
            border: 1px solid rgba(16, 185, 129, .2);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin: 24px 0;
        }

        .postfix-header {
            background: linear-gradient(135deg, #10B981, #34D399);
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .postfix-header h4 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: white;
            margin: 0;
        }

        .postfix-body {
            padding: 20px;
        }

        .postfix-examples {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 14px 0;
        }

        .postfix-ex {
            background: var(--code-bg);
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
        }

        .postfix-ex .infix {
            color: #CDD6F4;
        }

        .postfix-ex .arr {
            color: #6C7086;
            margin: 0 8px;
        }

        .postfix-ex .postfix {
            color: #A6E3A1;
            font-weight: 700;
        }

        /* Compare table */
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

        /* Sample problem */
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

        .sp-label {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--green);
            margin: 12px 0 8px;
        }

        /* DnD */
        .dnd-quiz {
            background: var(--card);
            border: 1.5px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin: 28px 0;
        }

        .dnd-header {
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dnd-q-num {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .dnd-q-text {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 14px;
        }

        .dnd-body {
            padding: 18px 20px 20px;
        }

        .dnd-prompt {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 14px;
        }

        .dnd-prompt code {
            font-family: 'JetBrains Mono', monospace;
            background: var(--purple-s);
            color: var(--purple);
            padding: 2px 6px;
            border-radius: 5px;
            font-size: 12.5px;
        }

        .dnd-bank {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            min-height: 54px;
            padding: 12px;
            border: 2px dashed rgba(99, 102, 241, .2);
            border-radius: 10px;
            background: var(--bg);
            margin-bottom: 16px;
            transition: background .2s;
        }

        .dnd-bank.drag-over {
            background: var(--purple-s);
            border-color: var(--purple);
        }

        .dnd-chip {
            padding: 7px 13px;
            border-radius: 10px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12.5px;
            font-weight: 700;
            cursor: grab;
            user-select: none;
            transition: transform .15s, box-shadow .15s, opacity .15s;
            border: 2px solid;
        }

        .dnd-chip:active {
            cursor: grabbing;
            transform: scale(1.06);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .18);
        }

        .dnd-chip.dragging {
            opacity: .4;
        }

        .chip-a {
            background: #FEF3C7;
            border-color: #F59E0B;
            color: #92400E;
        }

        .chip-b {
            background: #EFF6FF;
            border-color: #3B82F6;
            color: #1D4ED8;
        }

        .chip-c {
            background: var(--purple-s);
            border-color: var(--purple);
            color: var(--purple-d);
        }

        .chip-d {
            background: #F0FDF4;
            border-color: #10B981;
            color: #065F46;
        }

        .chip-e {
            background: #FFF1F2;
            border-color: #EF4444;
            color: #9F1239;
        }

        .dnd-zones {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .dnd-zone-wrap {
            flex: 1;
            min-width: 130px;
        }

        .dnd-zone-label {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .7px;
            margin-bottom: 6px;
        }

        .dnd-zone {
            min-height: 52px;
            border: 2px dashed rgba(99, 102, 241, .22);
            border-radius: 10px;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .2s, border-color .2s;
            background: var(--bg);
        }

        .dnd-zone.drag-over {
            background: var(--purple-s);
            border-color: var(--purple);
        }

        .dnd-zone.correct-zone {
            border-color: var(--green);
            background: #F0FDF4;
        }

        .dnd-zone.wrong-zone {
            border-color: var(--red);
            background: #FFF1F2;
        }

        .dnd-zone .dnd-chip {
            cursor: pointer;
        }

        .dnd-zone .dnd-chip:hover {
            opacity: .8;
        }

        .dnd-check {
            padding: 8px 20px;
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

        .dnd-check:hover {
            background: var(--purple-d);
        }

        .dnd-reset-btn {
            padding: 8px 16px;
            border-radius: 8px;
            border: 1.5px solid rgba(99, 102, 241, .3);
            background: transparent;
            color: var(--purple);
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            margin-left: 8px;
        }

        .dnd-reset-btn:hover {
            background: var(--purple-s);
        }

        .dnd-feedback {
            margin-top: 12px;
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 14px;
            display: none;
        }

        .dnd-feedback.show {
            display: block;
        }

        .dnd-feedback.ok {
            background: #F0FDF4;
            color: #065F46;
        }

        .dnd-feedback.bad {
            background: #FFF1F2;
            color: #9F1239;
        }

        .dnd-feedback.partial {
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

        section:nth-child(10) {
            animation-delay: .50s;
        }

        section:nth-child(11) {
            animation-delay: .55s;
        }

        @media(max-width:768px) {
            .note-container {
                grid-template-columns: 1fr;
            }

            .toc {
                position: static;
            }

            .dnd-zones {
                flex-direction: column;
            }

            .postfix-examples {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <?php include '../dashboard.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#cfg" class="toc-link">3.0.3 Context-Free Grammars</a></li>
                <li><a href="#bnf" class="toc-link sub">↳ BNF Notation</a></li>
                <li><a href="#deriv-trees" class="toc-link sub">↳ Derivation Trees</a></li>
                <li><a href="#ambiguous" class="toc-link sub">↳ Ambiguous Grammars</a></li>
                <li><a href="#leftmost" class="toc-link sub">↳ Left-Most Derivation</a></li>
                <li><a href="#sp-c" class="toc-link sub">↳ Sample Problem (c)</a></li>
                <li><a href="#pdm" class="toc-link">3.0.4 Pushdown Machines</a></li>
                <li><a href="#pdm-g2" class="toc-link sub">↳ PDM for aⁿbⁿ (Fig 3.4)</a></li>
                <li><a href="#pdm-paren" class="toc-link sub">↳ PDM for Parentheses (Fig 3.6)</a></li>
                <li><a href="#replace" class="toc-link sub">↳ Replace Operation</a></li>
                <li><a href="#postfix" class="toc-link sub">↳ Infix → Postfix (Fig 3.8)</a></li>
                <li><a href="#sp-d" class="toc-link sub">↳ Sample Problem (d)</a></li>
                <li><a href="#correspondence" class="toc-link">3.0.5 Machines &amp; Languages</a></li>
                <li><a href="#sp-e" class="toc-link sub">↳ Sample Problem (e)</a></li>
                <li><a href="#pdm-sim" class="toc-link">Interactive PDM Simulator</a></li>
                <li><a href="#dragdrop" class="toc-link">🎯 Drag &amp; Drop Quiz</a></li>
                <li><a href="#quiz" class="toc-link">Check Your Knowledge</a></li>
            </ul>
            <div class="toc-progress">
                <div class="toc-progress-label"><span>Progress</span><span id="pct-label">0%</span></div>
                <div class="toc-bar">
                    <div class="toc-bar-fill" id="toc-bar"></div>
                </div>
            </div>
        </nav>

        <article class="content">

            <header class="content-header">
                <div class="chapter-tag">📘 Chapter 3 · Sections 3.0.3 – 3.0.5</div>
                <h1>Context-Free Grammars &amp; Pushdown Machines</h1>
                <div class="metadata">
                    <span>⏱ 22 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>📐 Formal Languages</span>
                </div>
            </header>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 3.0.3 Context-Free Grammars                   -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="cfg">
                <h2><span class="sec-icon">📐</span> 3.0.3 Context-Free Grammars</h2>
                <p>
                    Most programming languages are defined by <strong>context-free grammars (CFGs)</strong>.
                    Although there are aspects of programming languages that cannot be fully specified with a CFG
                    (e.g. declaring a variable before using it), using more complex grammars would only serve to
                    <em>confuse rather than clarify</em>, and context-sensitive grammars cannot be used
                    practically to construct a compiler.
                </p>
                <p>
                    Recall: a context-free grammar rule has the form <code>A → α</code> — a single nonterminal
                    on the left rewrites to any string of terminals and nonterminals on the right, regardless of context.
                </p>
            </section>

            <!-- BNF -->
            <section id="bnf">
                <h2><span class="sec-icon">🔣</span> BNF Notation</h2>
                <p>
                    Context-free grammars are commonly written in <strong>Backus-Naur Form (BNF)</strong>,
                    in which nonterminals are enclosed in angle brackets <code>&lt;&gt;</code>
                    and the arrow <code>→</code> is replaced by <code>::=</code>. Multiple alternatives
                    for the same nonterminal can be given on one line using the <strong>vertical bar</strong> <code>|</code>.
                </p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin:16px 0;">
                    <div>
                        <p style="font-size:13px;color:var(--muted);margin-bottom:8px;"><strong>BNF form:</strong></p>
                        <div class="grammar-box" style="margin:0;">
                            <div style="font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;line-height:2;">
                                &lt;S&gt; ::= <span style="color:#A6E3A1;">a</span> &lt;S&gt; <span style="color:#A6E3A1;">b</span> | <span style="color:#F38BA8;">ε</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p style="font-size:13px;color:var(--muted);margin-bottom:8px;"><strong>Equivalent grammar rules:</strong></p>
                        <div class="grammar-box" style="margin:0;">
                            <div class="g-rule"><span class="g-num">1.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">a</span> <span class="g-nt">S</span> <span class="g-t">b</span></div>
                            <div class="g-rule"><span class="g-num">2.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-ep">ε</span></div>
                        </div>
                    </div>
                </div>
                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>BNF and context-free grammars are <strong>equivalent forms</strong>. The textbook uses regular grammar notation (<code>→</code>) rather than BNF (<code>::=</code>) purely for appearance. Both describe exactly the same languages.</div>
                </div>
            </section>

            <!-- Derivation Trees -->
            <section id="deriv-trees">
                <h2><span class="sec-icon">🌳</span> Derivation Trees</h2>
                <p>
                    A <strong>derivation tree</strong> (also called a <em>parse tree</em>) visually represents a derivation:
                </p>
                <ul>
                    <li>Each <strong>interior node</strong> corresponds to a nonterminal in the sentential form</li>
                    <li>Each <strong>leaf node</strong> corresponds to a terminal symbol in the derived string</li>
                </ul>
                <p>
                    The derivation tree does more than show that a string is in the language — it shows the
                    <strong>structure of the string</strong>, which may affect its meaning (semantics).
                    Below is the derivation tree for <code>aaabbb</code> using Grammar G2:
                </p>

                <div class="tree-panel" style="margin:20px 0;">
                    <div class="tree-panel-title">Figure 3.2 — Derivation tree for aaabbb using G2</div>
                    <svg width="100%" viewBox="0 0 360 200" style="display:block;max-width:360px;margin:0 auto;">
                        <defs>
                            <marker id="ta" markerWidth="6" markerHeight="6" refX="3" refY="3" orient="auto">
                                <circle cx="3" cy="3" r="2.5" fill="#6366F1" opacity=".4" />
                            </marker>
                        </defs>
                        <!-- edges -->
                        <line x1="180" y1="26" x2="100" y2="60" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="180" y1="26" x2="180" y2="60" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="180" y1="26" x2="260" y2="60" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="100" y1="76" x2="60" y2="110" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="180" y1="76" x2="120" y2="110" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="180" y1="76" x2="180" y2="110" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="180" y1="76" x2="240" y2="110" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="260" y1="76" x2="300" y2="110" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="120" y1="126" x2="90" y2="160" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="180" y1="126" x2="150" y2="160" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="180" y1="126" x2="180" y2="160" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="180" y1="126" x2="210" y2="160" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="240" y1="126" x2="270" y2="160" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <!-- S root -->
                        <circle cx="180" cy="18" r="16" fill="rgba(99,102,241,.15)" stroke="#6366F1" stroke-width="1.8" />
                        <text x="180" y="23" text-anchor="middle" fill="#818CF8" font-size="13" font-weight="700" font-family="Syne">S</text>
                        <!-- Level 1: A S B -->
                        <circle cx="100" cy="68" r="14" fill="rgba(99,102,241,.1)" stroke="#6366F1" stroke-width="1.5" />
                        <text x="100" y="73" text-anchor="middle" fill="#A5B4FC" font-size="12" font-weight="700" font-family="Syne">A</text>
                        <circle cx="180" cy="68" r="14" fill="rgba(99,102,241,.15)" stroke="#6366F1" stroke-width="1.8" />
                        <text x="180" y="73" text-anchor="middle" fill="#818CF8" font-size="13" font-weight="700" font-family="Syne">S</text>
                        <circle cx="260" cy="68" r="14" fill="rgba(99,102,241,.1)" stroke="#6366F1" stroke-width="1.5" />
                        <text x="260" y="73" text-anchor="middle" fill="#A5B4FC" font-size="12" font-weight="700" font-family="Syne">B</text>
                        <!-- A→a -->
                        <circle cx="60" cy="118" r="13" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                        <text x="60" y="123" text-anchor="middle" fill="#10B981" font-size="12" font-weight="700" font-family="JetBrains Mono">a</text>
                        <!-- S → A S B (inner) -->
                        <circle cx="120" cy="118" r="13" fill="rgba(99,102,241,.1)" stroke="#6366F1" stroke-width="1.4" />
                        <text x="120" y="123" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">A</text>
                        <circle cx="180" cy="118" r="13" fill="rgba(99,102,241,.12)" stroke="#6366F1" stroke-width="1.6" />
                        <text x="180" y="123" text-anchor="middle" fill="#818CF8" font-size="12" font-weight="700" font-family="Syne">S</text>
                        <circle cx="240" cy="118" r="13" fill="rgba(99,102,241,.1)" stroke="#6366F1" stroke-width="1.4" />
                        <text x="240" y="123" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">B</text>
                        <!-- B→b -->
                        <circle cx="300" cy="118" r="13" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                        <text x="300" y="123" text-anchor="middle" fill="#10B981" font-size="12" font-weight="700" font-family="JetBrains Mono">b</text>
                        <!-- inner A→a, S→ε, B→b -->
                        <circle cx="90" cy="168" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.2" />
                        <text x="90" y="173" text-anchor="middle" fill="#10B981" font-size="12" font-weight="700" font-family="JetBrains Mono">a</text>
                        <circle cx="150" cy="168" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.2" />
                        <text x="150" y="173" text-anchor="middle" fill="#10B981" font-size="12" font-weight="700" font-family="JetBrains Mono">a</text>
                        <circle cx="180" cy="168" r="12" fill="rgba(245,158,11,.1)" stroke="#F59E0B" stroke-width="1.2" />
                        <text x="180" y="173" text-anchor="middle" fill="#F59E0B" font-size="12" font-style="italic" font-family="JetBrains Mono">ε</text>
                        <circle cx="210" cy="168" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.2" />
                        <text x="210" y="173" text-anchor="middle" fill="#10B981" font-size="12" font-weight="700" font-family="JetBrains Mono">b</text>
                        <circle cx="270" cy="168" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.2" />
                        <text x="270" y="173" text-anchor="middle" fill="#10B981" font-size="12" font-weight="700" font-family="JetBrains Mono">b</text>
                        <!-- leaf labels -->
                        <text x="180" y="195" text-anchor="middle" fill="#6C7086" font-size="10" font-family="DM Sans">leaves: a a ε b b → aaabbb (ε ignored)</text>
                    </svg>
                </div>
            </section>

            <!-- Ambiguous Grammars -->
            <section id="ambiguous">
                <h2><span class="sec-icon">⚠️</span> Ambiguous Grammars — Grammar G4</h2>
                <p>
                    A context-free grammar is <strong>ambiguous</strong> if there is <em>more than one derivation tree</em>
                    for some string. This is like an ambiguous phrase in natural language — multiple interpretations.
                </p>

                <div class="grammar-box">
                    <div class="g-title">G4 — Simple arithmetic expressions (ambiguous)</div>
                    <div class="g-rule"><span class="g-num">1.</span><span class="g-nt">Expr</span><span style="color:#6C7086;"> → </span><span class="g-nt">Expr</span><span style="color:#F38BA8;"> + </span><span class="g-nt">Expr</span></div>
                    <div class="g-rule"><span class="g-num">2.</span><span class="g-nt">Expr</span><span style="color:#6C7086;"> → </span><span class="g-nt">Expr</span><span style="color:#F38BA8;"> * </span><span class="g-nt">Expr</span></div>
                    <div class="g-rule"><span class="g-num">3.</span><span class="g-nt">Expr</span><span style="color:#6C7086;"> → </span><span class="g-t">(</span><span class="g-nt">Expr</span><span class="g-t">)</span></div>
                    <div class="g-rule"><span class="g-num">4.</span><span class="g-nt">Expr</span><span style="color:#6C7086;"> → </span><span class="g-t">var</span></div>
                    <div class="g-rule"><span class="g-num">5.</span><span class="g-nt">Expr</span><span style="color:#6C7086;"> → </span><span class="g-t">const</span></div>
                </div>

                <p>
                    For the string <code>var + var * var</code>, G4 produces <strong>two different derivation trees</strong>
                    (Figure 3.3). The <em>second</em> tree is the correct interpretation because it shows multiplication
                    taking precedence over addition — as defined in most programming languages. A non-ambiguous grammar
                    for expressions will be given in Section 3.1.
                </p>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin:20px 0;">
                    <div class="tree-panel">
                        <div class="tree-panel-title">Tree 1 — var + (var * var) ✅ correct</div>
                        <svg width="100%" viewBox="0 0 240 180" style="display:block;">
                            <line x1="120" y1="24" x2="60" y2="64" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="120" y1="24" x2="120" y2="64" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="120" y1="24" x2="190" y2="64" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="190" y1="80" x2="155" y2="120" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="190" y1="80" x2="190" y2="120" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="190" y1="80" x2="225" y2="120" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <circle cx="120" cy="16" r="14" fill="rgba(99,102,241,.15)" stroke="#6366F1" stroke-width="1.8" />
                            <text x="120" y="21" text-anchor="middle" fill="#818CF8" font-size="11" font-weight="700" font-family="Syne">Expr</text>
                            <circle cx="60" cy="72" r="13" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                            <text x="60" y="77" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">var</text>
                            <circle cx="120" cy="72" r="13" fill="rgba(245,158,11,.1)" stroke="#F59E0B" stroke-width="1.4" />
                            <text x="120" y="77" text-anchor="middle" fill="#F59E0B" font-size="13" font-weight="700" font-family="JetBrains Mono">+</text>
                            <circle cx="190" cy="72" r="14" fill="rgba(99,102,241,.12)" stroke="#6366F1" stroke-width="1.6" />
                            <text x="190" y="77" text-anchor="middle" fill="#818CF8" font-size="11" font-weight="700" font-family="Syne">Expr</text>
                            <circle cx="155" cy="128" r="13" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                            <text x="155" y="133" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">var</text>
                            <circle cx="190" cy="128" r="13" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.4" />
                            <text x="190" y="133" text-anchor="middle" fill="#F87171" font-size="13" font-weight="700" font-family="JetBrains Mono">*</text>
                            <circle cx="225" cy="128" r="13" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                            <text x="225" y="133" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">var</text>
                            <text x="120" y="165" text-anchor="middle" fill="#10B981" font-size="10" font-family="DM Sans">* has higher precedence ✓</text>
                        </svg>
                    </div>
                    <div class="tree-panel">
                        <div class="tree-panel-title">Tree 2 — (var + var) * var ❌ wrong precedence</div>
                        <svg width="100%" viewBox="0 0 240 180" style="display:block;">
                            <line x1="120" y1="24" x2="50" y2="64" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="120" y1="24" x2="120" y2="64" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="120" y1="24" x2="190" y2="64" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="50" y1="80" x2="20" y2="120" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="50" y1="80" x2="50" y2="120" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="50" y1="80" x2="85" y2="120" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <circle cx="120" cy="16" r="14" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.8" />
                            <text x="120" y="21" text-anchor="middle" fill="#F87171" font-size="11" font-weight="700" font-family="Syne">Expr</text>
                            <circle cx="50" cy="72" r="14" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.6" />
                            <text x="50" y="77" text-anchor="middle" fill="#F87171" font-size="11" font-weight="700" font-family="Syne">Expr</text>
                            <circle cx="120" cy="72" r="13" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.4" />
                            <text x="120" y="77" text-anchor="middle" fill="#F87171" font-size="13" font-weight="700" font-family="JetBrains Mono">*</text>
                            <circle cx="190" cy="72" r="13" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                            <text x="190" y="77" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">var</text>
                            <circle cx="20" cy="128" r="13" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                            <text x="20" y="133" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">var</text>
                            <circle cx="50" cy="128" r="13" fill="rgba(245,158,11,.1)" stroke="#F59E0B" stroke-width="1.4" />
                            <text x="50" y="133" text-anchor="middle" fill="#F59E0B" font-size="13" font-weight="700" font-family="JetBrains Mono">+</text>
                            <circle cx="85" cy="128" r="13" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                            <text x="85" y="133" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">var</text>
                            <text x="120" y="165" text-anchor="middle" fill="#EF4444" font-size="10" font-family="DM Sans">+ has same precedence as * ✗</text>
                        </svg>
                    </div>
                </div>
            </section>

            <!-- Left-Most Derivation -->
            <section id="leftmost">
                <h2><span class="sec-icon">⬅️</span> Left-Most &amp; Right-Most Derivations</h2>
                <p>
                    A <strong>left-most derivation</strong> is one in which the left-most nonterminal is always
                    the one to which a rule is applied. Example for G2 (left-most):
                </p>
                <div style="background:var(--code-bg);border-radius:var(--radius);padding:16px 22px;margin:14px 0;font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;line-height:2.2;">
                    <span style="color:#CBA6F7;">S</span> ⇒ <span style="color:#CBA6F7;">A</span>SB ⇒ <span style="color:#A6E3A1;">a</span>SB ⇒ a<span style="color:#CBA6F7;">A</span>SBB ⇒ a<span style="color:#A6E3A1;">a</span>SBB ⇒ aa<span style="color:#CBA6F7;">S</span>BB ⇒ aaBB ⇒ aa<span style="color:#CBA6F7;">B</span>B ⇒ aa<span style="color:#A6E3A1;">b</span>B ⇒ aabb
                    <div style="font-size:11px;color:#6C7086;margin-top:4px;">← always expanding the leftmost nonterminal</div>
                </div>

                <div class="note-box key">
                    <span class="box-icon">✅</span>
                    <div>
                        Left-most and right-most derivations are <strong>normal forms</strong> for derivations.
                        If two different derivations produce the same left-most derivation, they correspond to the
                        <em>same derivation tree</em>. There is a <strong>one-to-one correspondence</strong> between
                        derivation trees and left-most (or right-most) derivations.
                    </div>
                </div>
            </section>

            <!-- Sample Problem 3.0(c) -->
            <section id="sp-c">
                <h2><span class="sec-icon">🧪</span> Sample Problem 3.0(c) — Ambiguity Test</h2>
                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 3.0 (c)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">Determine whether the following grammar is ambiguous. If so, show two different derivation trees for the same string, and the left-most derivation for each.</p>
                        <div class="grammar-box" style="margin-bottom:16px;">
                            <div class="g-rule"><span class="g-num">1.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">a</span><span class="g-nt">S</span><span class="g-t">b</span><span class="g-nt">S</span></div>
                            <div class="g-rule"><span class="g-num">2.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">a</span><span class="g-nt">S</span></div>
                            <div class="g-rule"><span class="g-num">3.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">c</span></div>
                        </div>

                        <div class="sp-label">✅ Result — Grammar IS Ambiguous (two trees for aacbc)</div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:10px;">
                            <div class="tree-panel">
                                <div class="tree-panel-title">Tree 1</div>
                                <svg width="100%" viewBox="0 0 180 170" style="display:block;">
                                    <line x1="90" y1="24" x2="40" y2="64" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                    <line x1="90" y1="24" x2="90" y2="64" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                    <line x1="90" y1="24" x2="140" y2="64" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                    <line x1="90" y1="80" x2="70" y2="120" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                    <line x1="90" y1="80" x2="110" y2="120" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                    <circle cx="90" cy="16" r="14" fill="rgba(99,102,241,.15)" stroke="#6366F1" stroke-width="1.8" />
                                    <text x="90" y="21" text-anchor="middle" fill="#818CF8" font-size="12" font-weight="700" font-family="Syne">S</text>
                                    <circle cx="40" cy="72" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                                    <text x="40" y="77" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">a</text>
                                    <circle cx="90" cy="72" r="12" fill="rgba(99,102,241,.12)" stroke="#6366F1" stroke-width="1.6" />
                                    <text x="90" y="77" text-anchor="middle" fill="#818CF8" font-size="12" font-weight="700" font-family="Syne">S</text>
                                    <circle cx="140" cy="72" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                                    <text x="140" y="77" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">c</text>
                                    <circle cx="70" cy="128" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.2" />
                                    <text x="70" y="133" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">a</text>
                                    <circle cx="110" cy="128" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.2" />
                                    <text x="110" y="133" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">c</text>
                                    <text x="90" y="158" text-anchor="middle" fill="#6C7086" font-size="9" font-family="DM Sans">S → aSbS, inner S → aS → ac</text>
                                </svg>
                            </div>
                            <div class="tree-panel">
                                <div class="tree-panel-title">Tree 2</div>
                                <svg width="100%" viewBox="0 0 180 170" style="display:block;">
                                    <line x1="90" y1="24" x2="60" y2="64" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                                    <line x1="90" y1="24" x2="130" y2="64" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                                    <line x1="60" y1="80" x2="30" y2="120" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                                    <line x1="60" y1="80" x2="65" y2="120" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                                    <line x1="60" y1="80" x2="100" y2="120" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                                    <circle cx="90" cy="16" r="14" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.8" />
                                    <text x="90" y="21" text-anchor="middle" fill="#F87171" font-size="12" font-weight="700" font-family="Syne">S</text>
                                    <circle cx="60" cy="72" r="12" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.6" />
                                    <text x="60" y="77" text-anchor="middle" fill="#F87171" font-size="12" font-weight="700" font-family="Syne">S</text>
                                    <circle cx="130" cy="72" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                                    <text x="130" y="77" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">c</text>
                                    <circle cx="30" cy="128" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.2" />
                                    <text x="30" y="133" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">a</text>
                                    <circle cx="65" cy="128" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.2" />
                                    <text x="65" y="133" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">a</text>
                                    <circle cx="100" cy="128" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.2" />
                                    <text x="100" y="133" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">c</text>
                                    <text x="90" y="158" text-anchor="middle" fill="#6C7086" font-size="9" font-family="DM Sans">S → aS, inner S → aSbS</text>
                                </svg>
                            </div>
                        </div>
                        <div style="margin-top:14px;">
                            <div class="sp-label">Left-most derivation for Tree 1:</div>
                            <div style="background:var(--code-bg);border-radius:8px;padding:10px 16px;font-family:'JetBrains Mono',monospace;font-size:12px;color:#CDD6F4;line-height:2;">
                                S ⇒ aSbS ⇒ aaSbS ⇒ aacbS ⇒ aacbc
                            </div>
                            <div class="sp-label">Left-most derivation for Tree 2:</div>
                            <div style="background:var(--code-bg);border-radius:8px;padding:10px 16px;font-family:'JetBrains Mono',monospace;font-size:12px;color:#CDD6F4;line-height:2;">
                                S ⇒ aS ⇒ aaSbS ⇒ aacbS ⇒ aacbc
                            </div>
                        </div>
                        <div class="note-box key" style="margin-top:12px;">
                            <span class="box-icon">✅</span>
                            <div>Both derivations produce <strong>aacbc</strong> via different trees → grammar is <strong>ambiguous</strong>.</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 3.0.4 Pushdown Machines                       -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="pdm">
                <h2><span class="sec-icon">🗂️</span> 3.0.4 Pushdown Machines</h2>
                <p>
                    A <strong>pushdown machine (PDM)</strong> is used for syntax analysis, just as a finite state
                    machine is used for lexical analysis. It is like an FSM but adds an <strong>infinite stack</strong>.
                </p>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Component</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>States</td>
                            <td>Finite set; one is the starting state</td>
                        </tr>
                        <tr>
                            <td>Input alphabet</td>
                            <td>The input symbols; includes <code>N</code> as end-of-input marker</td>
                        </tr>
                        <tr>
                            <td>Stack</td>
                            <td>Infinite LIFO stack; initialised with <code>&#44;</code> (bottom-of-stack marker). Stack symbols may overlap with input symbols.</td>
                        </tr>
                        <tr>
                            <td>Transition function</td>
                            <td>Arguments: (current state, current input, top of stack) → new state + stack operation + input pointer move</td>
                        </tr>
                        <tr>
                            <td>Input pointer</td>
                            <td>Each transition may <strong>advance</strong> to next input or <strong>retain</strong> (not advance)</td>
                        </tr>
                        <tr>
                            <td>Stack operations</td>
                            <td><strong>push(X)</strong> or <strong>pop</strong> on each transition</td>
                        </tr>
                        <tr>
                            <td>Termination</td>
                            <td>Machine halts by taking an <strong>Accept</strong> or <strong>Reject</strong> exit — unlike FSM which halts when all input is consumed</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>
                        Without the infinite stack, a pushdown machine is simply a <strong>finite state machine</strong>.
                        The stack is what gives it additional power to recognise context-free languages.
                    </div>
                </div>
            </section>

            <!-- PDM for anbn -->
            <section id="pdm-g2">
                <h2><span class="sec-icon">📊</span> Figure 3.4 — PDM for aⁿbⁿ (Language of G2)</h2>
                <p>
                    This pushdown machine accepts exactly the language <code>aⁿbⁿ</code> (n ≥ 0) — the same language
                    as Grammar G2. In state S1 it pushes X's as each <code>a</code> is read; in state S2 it pops
                    an X for each <code>b</code> read.
                </p>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin:16px 0;">
                    <div>
                        <p style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--purple);margin-bottom:8px;">State S1 (pushing X's)</p>
                        <table class="pdm-table">
                            <thead>
                                <tr>
                                    <th>Stack</th>
                                    <th>a</th>
                                    <th>b</th>
                                    <th>N</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="stack-sym">X</td>
                                    <td class="action">Push(X)<br>Advance<br>→S1</td>
                                    <td class="action">Pop<br>Advance<br>→S2</td>
                                    <td class="reject">Reject</td>
                                </tr>
                                <tr>
                                    <td class="stack-sym">,</td>
                                    <td class="action">Push(X)<br>Advance<br>→S1</td>
                                    <td class="reject">Reject</td>
                                    <td class="accept">Accept</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <p style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--purple);margin-bottom:8px;">State S2 (popping X's)</p>
                        <table class="pdm-table">
                            <thead>
                                <tr>
                                    <th>Stack</th>
                                    <th>a</th>
                                    <th>b</th>
                                    <th>N</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="stack-sym">X</td>
                                    <td class="reject">Reject</td>
                                    <td class="action">Pop<br>Advance<br>→S2</td>
                                    <td class="reject">Reject</td>
                                </tr>
                                <tr>
                                    <td class="stack-sym">,</td>
                                    <td class="reject">Reject</td>
                                    <td class="reject">Reject</td>
                                    <td class="accept">Accept</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <h3>Figure 3.5 — Stack trace for input <code>aabb</code></h3>
                <div class="stack-trace">
                    <div class="st-step">
                        <div class="st-stack">
                            <div class="st-sym sym-bot">,</div>
                        </div>
                        <div class="st-input">start</div>
                        <div class="st-state">S1</div>
                    </div>
                    <div style="color:var(--purple);font-size:18px;align-self:flex-end;padding-bottom:28px;">→</div>
                    <div class="st-step">
                        <div class="st-stack">
                            <div class="st-sym sym-x">X</div>
                            <div class="st-sym sym-bot">,</div>
                        </div>
                        <div class="st-input">read a</div>
                        <div class="st-state">S1</div>
                    </div>
                    <div style="color:var(--purple);font-size:18px;align-self:flex-end;padding-bottom:28px;">→</div>
                    <div class="st-step">
                        <div class="st-stack">
                            <div class="st-sym sym-x">X</div>
                            <div class="st-sym sym-x">X</div>
                            <div class="st-sym sym-bot">,</div>
                        </div>
                        <div class="st-input">read a</div>
                        <div class="st-state">S1</div>
                    </div>
                    <div style="color:var(--purple);font-size:18px;align-self:flex-end;padding-bottom:28px;">→</div>
                    <div class="st-step">
                        <div class="st-stack">
                            <div class="st-sym sym-x">X</div>
                            <div class="st-sym sym-bot">,</div>
                        </div>
                        <div class="st-input">read b</div>
                        <div class="st-state">S2</div>
                    </div>
                    <div style="color:var(--purple);font-size:18px;align-self:flex-end;padding-bottom:28px;">→</div>
                    <div class="st-step">
                        <div class="st-stack">
                            <div class="st-sym sym-bot">,</div>
                        </div>
                        <div class="st-input">read b</div>
                        <div class="st-state">S2</div>
                    </div>
                    <div style="color:var(--purple);font-size:18px;align-self:flex-end;padding-bottom:28px;">→</div>
                    <div class="st-step">
                        <div class="st-stack">
                            <div class="st-sym sym-bot">,</div>
                        </div>
                        <div class="st-input">read N</div>
                        <div class="st-state accept" style="color:var(--green);">ACCEPT</div>
                    </div>
                </div>
            </section>

            <!-- PDM parentheses -->
            <section id="pdm-paren">
                <h2><span class="sec-icon">( )</span> Figure 3.6 — PDM for Balanced Parentheses</h2>
                <p>
                    This machine accepts any string of correctly balanced parentheses. It cannot be accepted
                    by a finite state machine because there could be an <em>unlimited number of nested</em>
                    left parentheses before the first right parenthesis — the stack handles this naturally.
                </p>

                <table class="pdm-table" style="max-width:400px;">
                    <thead>
                        <tr>
                            <th>Stack</th>
                            <th>( (left)</th>
                            <th>) (right)</th>
                            <th>N</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="stack-sym">X</td>
                            <td class="action">Push(X)<br>Advance<br>→S1</td>
                            <td class="action">Pop<br>Advance<br>→S1</td>
                            <td class="reject">Reject</td>
                        </tr>
                        <tr>
                            <td class="stack-sym">,</td>
                            <td class="action">Push(X)<br>Advance<br>→S1</td>
                            <td class="reject">Reject</td>
                            <td class="accept">Accept</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        This language is <em>similar</em> to L(G2): each left parenthesis pushes X; each right parenthesis pops X. Acceptance when both input and stack (above <code>,</code>) are empty. Compare with Grammar G2 which uses a's and b's instead of ( and ).
                    </div>
                </div>
            </section>

            <!-- Replace Operation -->
            <section id="replace">
                <h2><span class="sec-icon">🔄</span> The Replace Operation — Rep(X, Y, Z, …)</h2>
                <p>
                    To simplify pushdown machine descriptions, an <strong>extended pushdown machine</strong> adds
                    the <strong>Replace</strong> operation: <code>Rep(X, Y, Z, …)</code>. This pops the top stack
                    symbol and then pushes the given symbols in order (left to right).
                </p>
                <p>
                    For example, <code>Rep(Term, +, Expr)</code> pops the current top and pushes:
                    first <code>Term</code>, then <code>+</code>, then <code>Expr</code>
                    (so Expr is on top after the operation).
                </p>
                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>
                        The Replace operation adds <em>no extra power</em> — every Replace can be expressed as a pop
                        followed by one or more pushes. It is included only for <strong>convenience</strong> to simplify
                        problem solutions.
                    </div>
                </div>
            </section>

            <!-- Infix to Postfix -->
            <section id="postfix">
                <h2><span class="sec-icon">🔢</span> Figure 3.8 — Extended Pushdown Translator: Infix → Postfix</h2>
                <p>
                    A <strong>pushdown translator</strong> is a pushdown machine with an <em>output function</em>
                    on transitions. Figure 3.8 shows an extended pushdown translator that converts simple
                    infix expressions to postfix. In postfix, both operands precede the operator.
                </p>

                <div class="postfix-wrap">
                    <div class="postfix-header">
                        <h4>Infix → Postfix Conversion Examples</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);">a = any variable or constant</span>
                    </div>
                    <div class="postfix-body">
                        <div class="postfix-examples">
                            <div class="postfix-ex"><span class="infix">2 + 3</span><span class="arr">→</span><span class="postfix">2 3 +</span></div>
                            <div class="postfix-ex"><span class="infix">2 + 3 * 5</span><span class="arr">→</span><span class="postfix">2 3 5 * +</span></div>
                            <div class="postfix-ex"><span class="infix">2 * 3 + 5</span><span class="arr">→</span><span class="postfix">2 3 * 5 +</span></div>
                            <div class="postfix-ex"><span class="infix">(2 + 3) * 5</span><span class="arr">→</span><span class="postfix">2 3 + 5 *</span></div>
                        </div>
                        <div class="note-box tip" style="margin-top:0;">
                            <span class="box-icon">💡</span>
                            <div>
                                Parentheses are <strong>never used in postfix</strong> — operator precedence is encoded in
                                the order of operands. The stack symbol <code>E</code> represents an expression on the stack;
                                <code>L</code> represents a left parenthesis; <code>Ep</code> = expression above a plus;
                                <code>Lp</code> = left paren above a plus.
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sample Problem 3.0(d) -->
            <section id="sp-d">
                <h2><span class="sec-icon">🧪</span> Sample Problem 3.0(d) — PDM Trace for a+(a*a)</h2>
                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 3.0 (d) — Figure 3.8 trace for input a+(a*a)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">Show the sequence of stacks and states which the pushdown machine of Figure 3.8 would go through for input <code>a+(a*a)</code>.</p>
                        <div class="sp-label">✅ Solution — Output: aa a*+</div>
                        <div style="overflow-x:auto;">
                            <table class="pdm-table" style="min-width:600px;">
                                <thead>
                                    <tr>
                                        <th>Stack top→bot</th>
                                        <th>Input</th>
                                        <th>State</th>
                                        <th>Action/Output</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>,</td>
                                        <td>a</td>
                                        <td>S1</td>
                                        <td>out(a), push(E)</td>
                                    </tr>
                                    <tr>
                                        <td>E ,</td>
                                        <td>+</td>
                                        <td>S1</td>
                                        <td>push(+) → Lp on E? push(Ep)</td>
                                    </tr>
                                    <tr>
                                        <td>Ep ,</td>
                                        <td>(</td>
                                        <td>S1</td>
                                        <td>push(Lp)</td>
                                    </tr>
                                    <tr>
                                        <td>Lp Ep ,</td>
                                        <td>a</td>
                                        <td>S1</td>
                                        <td>out(a), push(E)</td>
                                    </tr>
                                    <tr>
                                        <td>E Lp Ep ,</td>
                                        <td>*</td>
                                        <td>S1</td>
                                        <td>out(a*) — multiply is above; push(Ls)</td>
                                    </tr>
                                    <tr>
                                        <td>Ls Ep ,</td>
                                        <td>a</td>
                                        <td>S1</td>
                                        <td>out(a), push(E)</td>
                                    </tr>
                                    <tr>
                                        <td>E Ls Ep ,</td>
                                        <td>)</td>
                                        <td>S1</td>
                                        <td>→S2 (handle closing paren)</td>
                                    </tr>
                                    <tr>
                                        <td>Ep ,</td>
                                        <td>—</td>
                                        <td>S3→S1</td>
                                        <td>out(+), Rep(E)</td>
                                    </tr>
                                    <tr>
                                        <td>E ,</td>
                                        <td>N</td>
                                        <td>S1</td>
                                        <td>Accept</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="note-box key" style="margin-top:14px;">
                            <span class="box-icon">✅</span>
                            <div>Output produced: <strong style="font-family:'JetBrains Mono',monospace;">a a a* +</strong> — which is the correct postfix for a+(a*a). Multiplication binds tighter so its operands appear together.</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 3.0.5 Correspondence                          -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="correspondence">
                <h2><span class="sec-icon">🔗</span> 3.0.5 Correspondence Between Machines &amp; Languages</h2>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Machine</th>
                            <th>Equivalent grammar class</th>
                            <th>Also equivalent to</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Finite State Machine</td>
                            <td>Right Linear (Type 3)</td>
                            <td>Regular expressions</td>
                        </tr>
                        <tr>
                            <td>Deterministic Pushdown Machine</td>
                            <td>Deterministic context-free languages (subset of Type 2)</td>
                            <td>—</td>
                        </tr>
                        <tr>
                            <td>Nondeterministic Pushdown Machine</td>
                            <td>All context-free languages (Type 2)</td>
                            <td>—</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Important limitation:</strong> There are context-free languages that <em>cannot</em> be
                        accepted by a <em>deterministic</em> pushdown machine. The language of palindromes
                        <strong>without a centermarker</strong> (P) is one example — the machine can never know when to
                        switch from pushing to popping. With a centermarker <strong>c</strong> (language Pc), a
                        deterministic PDM is sufficient. <strong>Nondeterministic</strong> pushdown machines can accept
                        all context-free languages, but are beyond the scope of this course.
                    </div>
                </div>
            </section>

            <!-- Sample Problem 3.0(e) -->
            <section id="sp-e">
                <h2><span class="sec-icon">🧪</span> Sample Problem 3.0(e) — Right Linear Grammars</h2>
                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 3.0 (e) — Right linear grammars for Sample Problem 2.0(a) languages</h4>
                    </div>
                    <div class="sp-body">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                            <div>
                                <div class="sp-label">(1) Strings with odd number of 0s</div>
                                <div class="grammar-box" style="margin:0;">
                                    <div class="g-title">Right Linear Grammar</div>
                                    <div class="g-rule"><span class="g-num">1.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">0</span></div>
                                    <div class="g-rule"><span class="g-num">2.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">1</span><span class="g-nt">S</span></div>
                                    <div class="g-rule"><span class="g-num">3.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">0</span><span class="g-nt">A</span></div>
                                    <div class="g-rule"><span class="g-num">4.</span><span class="g-nt">A</span><span style="color:#6C7086;"> → </span><span class="g-t">1</span></div>
                                    <div class="g-rule"><span class="g-num">5.</span><span class="g-nt">A</span><span style="color:#6C7086;"> → </span><span class="g-t">1</span><span class="g-nt">A</span></div>
                                    <div class="g-rule"><span class="g-num">6.</span><span class="g-nt">A</span><span style="color:#6C7086;"> → </span><span class="g-t">0</span><span class="g-nt">S</span></div>
                                </div>
                            </div>
                            <div>
                                <div class="sp-label">(2) Strings with three consecutive 1s</div>
                                <div class="grammar-box" style="margin:0;">
                                    <div class="g-title">Right Linear Grammar</div>
                                    <div class="g-rule"><span class="g-num">1.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">1</span><span class="g-nt">S</span></div>
                                    <div class="g-rule"><span class="g-num">2.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">0</span><span class="g-nt">S</span></div>
                                    <div class="g-rule"><span class="g-num">3.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">1</span><span class="g-nt">A</span></div>
                                    <div class="g-rule"><span class="g-num">4.</span><span class="g-nt">A</span><span style="color:#6C7086;"> → </span><span class="g-t">1</span><span class="g-nt">B</span></div>
                                    <div class="g-rule"><span class="g-num">5.</span><span class="g-nt">B</span><span style="color:#6C7086;"> → </span><span class="g-t">1</span><span class="g-nt">C</span></div>
                                    <div class="g-rule"><span class="g-num">6.</span><span class="g-nt">B</span><span style="color:#6C7086;"> → </span><span class="g-t">1</span></div>
                                    <div class="g-rule"><span class="g-num">7–10.</span><span class="g-nt">C</span><span style="color:#6C7086;"> → </span><span style="color:#CDD6F4;">any input</span><span class="g-nt">C</span> / accept</div>
                                </div>
                            </div>
                        </div>
                        <div class="note-box tip" style="margin-top:14px;">
                            <span class="box-icon">💡</span>
                            <div>Right linear grammars have each rule of the form <code>A → aB</code> or <code>A → a</code>. They generate exactly the same languages as <em>finite state machines</em> and <em>regular expressions</em>. These grammars are used for lexical token definitions.</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Interactive PDM Simulator                     -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="pdm-sim">
                <h2><span class="sec-icon">▶️</span> Interactive PDM Simulator</h2>
                <p>Select a machine and enter an input string. Step through or run the PDM and watch the stack change in real time.</p>

                <div class="pdm-sim">
                    <div class="pdm-sim-header">
                        <h4>🗂️ Pushdown Machine Simulator</h4>
                        <span id="pdm-status-label" style="font-size:12px;color:rgba(255,255,255,.7);">Ready</span>
                    </div>
                    <div class="pdm-sim-body">
                        <div class="pdm-ctrl">
                            <select class="pdm-sel" id="pdm-machine-sel" onchange="pdmReset()">
                                <option value="anbn">Fig 3.4 — aⁿbⁿ</option>
                                <option value="paren">Fig 3.6 — Balanced parentheses</option>
                            </select>
                            <input class="pdm-inp" id="pdm-input" type="text" placeholder="e.g. aabb" maxlength="16" oninput="pdmReset()">
                            <button class="pdm-btn" onclick="pdmRun()">▶ Run</button>
                            <button class="pdm-btn sec" onclick="pdmStep()" id="pdm-step-btn">Step →</button>
                            <button class="pdm-btn sec" onclick="pdmReset()">↺ Reset</button>
                        </div>
                        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;">
                            <span style="font-size:12px;color:var(--muted);">Examples:</span>
                            <button onclick="pdmEx('anbn','aabb')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(99,102,241,.3);background:transparent;color:var(--purple);cursor:pointer;">aabb ✓</button>
                            <button onclick="pdmEx('anbn','aaabbb')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(99,102,241,.3);background:transparent;color:var(--purple);cursor:pointer;">aaabbb ✓</button>
                            <button onclick="pdmEx('anbn','aab')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(239,68,68,.3);background:transparent;color:var(--red);cursor:pointer;">aab ✗</button>
                            <button onclick="pdmEx('paren','(())')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(10,185,129,.3);background:transparent;color:var(--green);cursor:pointer;">(()) ✓</button>
                            <button onclick="pdmEx('paren','(()')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(239,68,68,.3);background:transparent;color:var(--red);cursor:pointer;">(() ✗</button>
                        </div>

                        <div class="pdm-tape" id="pdm-tape"></div>

                        <div style="display:flex;gap:16px;flex-wrap:wrap;margin-top:10px;">
                            <div>
                                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--purple);margin-bottom:6px;">Stack (top at top):</div>
                                <div class="pdm-stack-vis" id="pdm-stack" style="min-height:60px;"></div>
                            </div>
                            <div style="flex:1;">
                                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--purple);margin-bottom:6px;">State: <span id="pdm-cur-state" style="color:var(--amber);">—</span></div>
                                <div class="pdm-log" id="pdm-log">Select a machine and enter input to begin.</div>
                            </div>
                        </div>
                        <div class="pdm-status" id="pdm-result"></div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- DRAG AND DROP — 5 answers                     -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="dragdrop">
                <h2><span class="sec-icon">🎯</span> Drag &amp; Drop Quiz</h2>
                <p>Each question has <strong>5 chips</strong> and 5 zones. Drag each chip to its correct zone.</p>

                <!-- DnD Q1 — Match concept to definition -->
                <div class="dnd-quiz" id="dnd1">
                    <div class="dnd-header" style="background:linear-gradient(135deg,#6366F1,#818CF8);">
                        <div class="dnd-q-num" style="background:rgba(255,255,255,.2);color:white;">1</div>
                        <div class="dnd-q-text" style="color:white;">Match each concept to its correct definition</div>
                    </div>
                    <div class="dnd-body">
                        <p class="dnd-prompt">Drag the concept names into the matching description boxes.</p>
                        <div class="dnd-bank" id="bank1" ondrop="dropChip(event,'bank1')" ondragover="allowDrop(event)">
                            <div class="dnd-chip chip-a" draggable="true" ondragstart="dragStart(event)" id="c1-ambig">Ambiguous grammar</div>
                            <div class="dnd-chip chip-b" draggable="true" ondragstart="dragStart(event)" id="c1-lmd">Left-most derivation</div>
                            <div class="dnd-chip chip-c" draggable="true" ondragstart="dragStart(event)" id="c1-pdm">Pushdown machine</div>
                            <div class="dnd-chip chip-d" draggable="true" ondragstart="dragStart(event)" id="c1-rep">Rep(X,Y,Z)</div>
                            <div class="dnd-chip chip-e" draggable="true" ondragstart="dragStart(event)" id="c1-dtree">Derivation tree</div>
                        </div>
                        <div class="dnd-zones">
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#F59E0B;">Always expands the leftmost nonterminal</div>
                                <div class="dnd-zone" id="z1a" ondrop="dropChip(event,'z1a')" ondragover="allowDrop(event)" data-answer="c1-lmd"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#6366F1;">Interior nodes = nonterminals, leaves = terminals</div>
                                <div class="dnd-zone" id="z1b" ondrop="dropChip(event,'z1b')" ondragover="allowDrop(event)" data-answer="c1-dtree"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#10B981;">FSM + infinite stack for syntax analysis</div>
                                <div class="dnd-zone" id="z1c" ondrop="dropChip(event,'z1c')" ondragover="allowDrop(event)" data-answer="c1-pdm"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#EF4444;">More than one derivation tree for same string</div>
                                <div class="dnd-zone" id="z1d" ondrop="dropChip(event,'z1d')" ondragover="allowDrop(event)" data-answer="c1-ambig"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#A855F7;">Pop top + push multiple symbols</div>
                                <div class="dnd-zone" id="z1e" ondrop="dropChip(event,'z1e')" ondragover="allowDrop(event)" data-answer="c1-rep"></div>
                            </div>
                        </div>
                        <button class="dnd-check" onclick="checkDnD('dnd1',['z1a','z1b','z1c','z1d','z1e'])">✓ Check</button>
                        <button class="dnd-reset-btn" onclick="resetDnD('dnd1','bank1',['z1a','z1b','z1c','z1d','z1e'])">↺ Reset</button>
                        <div class="dnd-feedback" id="fb-dnd1"></div>
                    </div>
                </div>

                <!-- DnD Q2 — Match machine to language -->
                <div class="dnd-quiz" id="dnd2">
                    <div class="dnd-header" style="background:linear-gradient(135deg,#10B981,#34D399);">
                        <div class="dnd-q-num" style="background:rgba(255,255,255,.2);color:white;">2</div>
                        <div class="dnd-q-text" style="color:white;">Match each input string to whether the Fig 3.4 PDM (aⁿbⁿ) accepts or rejects it</div>
                    </div>
                    <div class="dnd-body">
                        <p class="dnd-prompt">Figure 3.4 PDM accepts exactly aⁿbⁿ (n ≥ 0).</p>
                        <div class="dnd-bank" id="bank2" ondrop="dropChip(event,'bank2')" ondragover="allowDrop(event)">
                            <div class="dnd-chip chip-a" draggable="true" ondragstart="dragStart(event)" id="c2-aabb">aabb</div>
                            <div class="dnd-chip chip-b" draggable="true" ondragstart="dragStart(event)" id="c2-ep">ε (empty)</div>
                            <div class="dnd-chip chip-c" draggable="true" ondragstart="dragStart(event)" id="c2-ab">ab</div>
                            <div class="dnd-chip chip-d" draggable="true" ondragstart="dragStart(event)" id="c2-aab">aab</div>
                            <div class="dnd-chip chip-e" draggable="true" ondragstart="dragStart(event)" id="c2-ba">ba</div>
                        </div>
                        <div class="dnd-zones">
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#10B981;">✅ ACCEPT — aabb (n=2)</div>
                                <div class="dnd-zone" id="z2a" ondrop="dropChip(event,'z2a')" ondragover="allowDrop(event)" data-answer="c2-aabb"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#10B981;">✅ ACCEPT — ε (n=0)</div>
                                <div class="dnd-zone" id="z2b" ondrop="dropChip(event,'z2b')" ondragover="allowDrop(event)" data-answer="c2-ep"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#10B981;">✅ ACCEPT — ab (n=1)</div>
                                <div class="dnd-zone" id="z2c" ondrop="dropChip(event,'z2c')" ondragover="allowDrop(event)" data-answer="c2-ab"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#EF4444;">❌ REJECT — unequal count</div>
                                <div class="dnd-zone" id="z2d" ondrop="dropChip(event,'z2d')" ondragover="allowDrop(event)" data-answer="c2-aab"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#EF4444;">❌ REJECT — b before a</div>
                                <div class="dnd-zone" id="z2e" ondrop="dropChip(event,'z2e')" ondragover="allowDrop(event)" data-answer="c2-ba"></div>
                            </div>
                        </div>
                        <button class="dnd-check" onclick="checkDnD('dnd2',['z2a','z2b','z2c','z2d','z2e'])">✓ Check</button>
                        <button class="dnd-reset-btn" onclick="resetDnD('dnd2','bank2',['z2a','z2b','z2c','z2d','z2e'])">↺ Reset</button>
                        <div class="dnd-feedback" id="fb-dnd2"></div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Quiz                                          -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="quiz">
                <h2><span class="sec-icon">🧩</span> Check Your Knowledge</h2>

                <div class="mini-quiz">
                    <div class="quiz-header"><span style="font-size:18px;">❓</span>
                        <h4>Question 1</h4>
                    </div>
                    <div class="quiz-body">
                        <p class="quiz-q">Grammar G4 is ambiguous for <code>var + var * var</code>. Which derivation tree gives the <em>correct</em> interpretation for a programming language?</p>
                        <div class="quiz-options" id="q1-opts">
                            <div class="quiz-opt" onclick="answer(this,'q1',false)">
                                <div class="opt-circle">A</div> The tree where addition is the root — (var+var)*var
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q1',true)">
                                <div class="opt-circle">B</div> The tree where addition is the root's child of multiplication — var+(var*var) — because multiplication has higher precedence
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q1',false)">
                                <div class="opt-circle">C</div> Either tree is equally correct
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q1',false)">
                                <div class="opt-circle">D</div> The grammar must be fixed before either tree can be used
                            </div>
                        </div>
                        <div class="quiz-feedback" id="q1-fb"></div>
                    </div>
                </div>

                <div class="mini-quiz">
                    <div class="quiz-header"><span style="font-size:18px;">❓</span>
                        <h4>Question 2</h4>
                    </div>
                    <div class="quiz-body">
                        <p class="quiz-q">What is the key structural difference between a finite state machine and a pushdown machine?</p>
                        <div class="quiz-options" id="q2-opts">
                            <div class="quiz-opt" onclick="answer(this,'q2',false)">
                                <div class="opt-circle">A</div> Pushdown machines have more states
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q2',true)">
                                <div class="opt-circle">B</div> Pushdown machines have an infinite stack — without the stack, a pushdown machine reduces to a finite state machine
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q2',false)">
                                <div class="opt-circle">C</div> Pushdown machines can read the input more than once
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q2',false)">
                                <div class="opt-circle">D</div> Pushdown machines can only process strings of parentheses
                            </div>
                        </div>
                        <div class="quiz-feedback" id="q2-fb"></div>
                    </div>
                </div>

                <div class="mini-quiz">
                    <div class="quiz-header"><span style="font-size:18px;">❓</span>
                        <h4>Question 3</h4>
                    </div>
                    <div class="quiz-body">
                        <p class="quiz-q">The language of palindromes without a centermarker (P) cannot be accepted by a <em>deterministic</em> PDM. Why?</p>
                        <div class="quiz-options" id="q3-opts">
                            <div class="quiz-opt" onclick="answer(this,'q3',false)">
                                <div class="opt-circle">A</div> Because palindromes are a right-linear language and PDMs are too powerful for them
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q3',true)">
                                <div class="opt-circle">B</div> Without a centermarker, the machine never knows when to switch from pushing to popping — it can't tell when it's reading the mirror image of the initial portion
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q3',false)">
                                <div class="opt-circle">C</div> Palindromes are context-sensitive, which is beyond any pushdown machine
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q3',false)">
                                <div class="opt-circle">D</div> The PDM runs out of stack space for long palindromes
                            </div>
                        </div>
                        <div class="quiz-feedback" id="q3-fb"></div>
                    </div>
                </div>

                <div class="mini-quiz">
                    <div class="quiz-header"><span style="font-size:18px;">❓</span>
                        <h4>Question 4</h4>
                    </div>
                    <div class="quiz-body">
                        <p class="quiz-q">What does <code>Rep(Term, +, Expr)</code> do to the stack?</p>
                        <div class="quiz-options" id="q4-opts">
                            <div class="quiz-opt" onclick="answer(this,'q4',false)">
                                <div class="opt-circle">A</div> Pushes Term, +, and Expr onto the stack without removing anything
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q4',true)">
                                <div class="opt-circle">B</div> Pops the top stack symbol, then pushes Term, then +, then Expr — so Expr ends up on top
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q4',false)">
                                <div class="opt-circle">C</div> Replaces the entire stack contents with Term, +, Expr
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q4',false)">
                                <div class="opt-circle">D</div> Pops three symbols and pushes Term, +, Expr
                            </div>
                        </div>
                        <div class="quiz-feedback" id="q4-fb"></div>
                    </div>
                </div>

                <div class="mini-quiz">
                    <div class="quiz-header"><span style="font-size:18px;">❓</span>
                        <h4>Question 5</h4>
                    </div>
                    <div class="quiz-body">
                        <p class="quiz-q">What is the infix-to-postfix conversion of <code>2 * 3 + 5</code>?</p>
                        <div class="quiz-options" id="q5-opts">
                            <div class="quiz-opt" onclick="answer(this,'q5',false)">
                                <div class="opt-circle">A</div> 2 3 + 5 *
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q5',false)">
                                <div class="opt-circle">B</div> 2 * 3 5 +
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q5',true)">
                                <div class="opt-circle">C</div> 2 3 * 5 + — multiplication is evaluated first so 2 and 3 come together with *, then 5 and + follow
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q5',false)">
                                <div class="opt-circle">D</div> 5 3 2 * +
                            </div>
                        </div>
                        <div class="quiz-feedback" id="q5-fb"></div>
                    </div>
                </div>

            </section>

            <div class="chapter-nav">
                <a href="c3.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous section</span>3.0 – 3.0.2 Grammars &amp; Classes</div>
                </a>
                <a href="c3_1.php" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next section</span>3.1 Ambiguities in Programming Languages</div>
                    <span>→</span>
                </a>
            </div>

        </article>
    </div>

    <script>
        // TOC scroll
        const sections = document.querySelectorAll('section[id]');
        const tocLinks = document.querySelectorAll('.toc-link');
        const bar = document.getElementById('toc-bar');
        const pctLabel = document.getElementById('pct-label');
        window.addEventListener('scroll', () => {
            const s = window.scrollY,
                t = document.body.scrollHeight - window.innerHeight;
            bar.style.width = Math.round(s / t * 100) + '%';
            pctLabel.textContent = Math.round(s / t * 100) + '%';
            let cur = '';
            sections.forEach(sec => {
                if (s >= sec.offsetTop - 120) cur = sec.id;
            });
            tocLinks.forEach(l => l.classList.toggle('active', l.getAttribute('href') === '#' + cur));
        });

        // ════════════════════════════════════════════════════════
        // PDM SIMULATOR
        // ════════════════════════════════════════════════════════
        // Machines: fig34 = anbn, fig36 = balanced parens
        const MACHINES = {
            anbn: {
                // State S1: push X on a; switch to S2 on b; accept on N with just ,
                // State S2: pop on b; accept on N with just ,
                simulate: function(str) {
                    const input = str.split('').concat(['N']);
                    let stack = [','];
                    let state = 'S1';
                    let i = 0;
                    const trace = [{
                        stack: [...stack],
                        state,
                        input_sym: '(start)'
                    }];
                    let result = null;
                    while (i < input.length && result === null) {
                        const sym = input[i];
                        const top = stack[stack.length - 1];
                        if (state === 'S1') {
                            if (sym === 'a') {
                                stack.push('X');
                                i++;
                            } else if (sym === 'b') {
                                if (top === 'X') {
                                    stack.pop();
                                    i++;
                                    state = 'S2';
                                } else {
                                    result = 'reject';
                                    break;
                                }
                            } else if (sym === 'N') {
                                result = (top === ',') ? 'accept' : 'reject';
                                break;
                            } else {
                                result = 'reject';
                                break;
                            }
                        } else {
                            if (sym === 'b') {
                                if (top === 'X') {
                                    stack.pop();
                                    i++;
                                } else {
                                    result = 'reject';
                                    break;
                                }
                            } else if (sym === 'N') {
                                result = (top === ',') ? 'accept' : 'reject';
                                break;
                            } else {
                                result = 'reject';
                                break;
                            }
                        }
                        trace.push({
                            stack: [...stack],
                            state,
                            input_sym: sym
                        });
                    }
                    if (!result) result = 'reject';
                    return {
                        trace,
                        result
                    };
                }
            },
            paren: {
                simulate: function(str) {
                    const input = str.split('').concat(['N']);
                    let stack = [','];
                    let state = 'S1';
                    let i = 0;
                    const trace = [{
                        stack: [...stack],
                        state,
                        input_sym: '(start)'
                    }];
                    let result = null;
                    while (i < input.length && result === null) {
                        const sym = input[i];
                        const top = stack[stack.length - 1];
                        if (sym === '(') {
                            stack.push('X');
                            i++;
                        } else if (sym === ')') {
                            if (top === 'X') {
                                stack.pop();
                                i++;
                            } else {
                                result = 'reject';
                                break;
                            }
                        } else if (sym === 'N') {
                            result = (top === ',') ? 'accept' : 'reject';
                            break;
                        } else {
                            result = 'reject';
                            break;
                        }
                        trace.push({
                            stack: [...stack],
                            state,
                            input_sym: sym
                        });
                    }
                    if (!result) result = 'reject';
                    return {
                        trace,
                        result
                    };
                }
            }
        };

        let pdmTrace = null,
            pdmPos = 0,
            pdmStr = '';

        function pdmEx(machine, s) {
            document.getElementById('pdm-machine-sel').value = machine;
            document.getElementById('pdm-input').value = s;
            pdmReset();
        }

        function pdmReset() {
            pdmStr = document.getElementById('pdm-input').value.trim();
            pdmPos = 0;
            pdmTrace = null;
            renderPdmTape([]);
            renderPdmStack([',']);
            document.getElementById('pdm-cur-state').textContent = '—';
            document.getElementById('pdm-log').textContent = 'Press Step or Run to simulate.';
            document.getElementById('pdm-step-btn').disabled = false;
            const res = document.getElementById('pdm-result');
            res.className = 'pdm-status';
            res.textContent = '';
            document.getElementById('pdm-status-label').textContent = 'Ready';
        }

        function pdmEnsureTrace() {
            if (!pdmTrace) {
                const machine = document.getElementById('pdm-machine-sel').value;
                pdmStr = document.getElementById('pdm-input').value.trim();
                const m = machine === 'anbn' ? MACHINES.anbn : MACHINES.paren;
                pdmTrace = m.simulate(pdmStr);
                pdmPos = 0;
            }
        }

        function pdmStep() {
            pdmEnsureTrace();
            if (pdmPos >= pdmTrace.trace.length) {
                finishPdm();
                return;
            }
            const step = pdmTrace.trace[pdmPos];
            renderPdmTape(pdmStr.split(''), pdmPos - 1);
            renderPdmStack(step.stack);
            document.getElementById('pdm-cur-state').textContent = step.state;
            document.getElementById('pdm-log').textContent = `Step ${pdmPos}: read "${step.input_sym}" · stack top=${step.stack[step.stack.length-1]}`;
            pdmPos++;
            document.getElementById('pdm-status-label').textContent = `Step ${pdmPos}/${pdmTrace.trace.length}`;
            if (pdmPos >= pdmTrace.trace.length) finishPdm();
        }

        function pdmRun() {
            pdmEnsureTrace();
            pdmPos = pdmTrace.trace.length;
            renderPdmTape(pdmStr.split(''), pdmStr.length);
            const last = pdmTrace.trace[pdmTrace.trace.length - 1];
            renderPdmStack(last.stack);
            document.getElementById('pdm-cur-state').textContent = last.state;
            finishPdm();
        }

        function finishPdm() {
            document.getElementById('pdm-step-btn').disabled = true;
            const accepted = pdmTrace.result === 'accept';
            const res = document.getElementById('pdm-result');
            res.className = 'pdm-status show ' + (accepted ? 'accepted' : 'rejected');
            res.innerHTML = accepted ?
                `✅ <strong>ACCEPTED</strong> — "${pdmStr}" is in the language.` :
                `❌ <strong>REJECTED</strong> — "${pdmStr}" is not in the language.`;
            document.getElementById('pdm-status-label').textContent = accepted ? 'ACCEPTED ✅' : 'REJECTED ❌';
        }

        function renderPdmTape(chars, readUpTo) {
            const tape = document.getElementById('pdm-tape');
            tape.innerHTML = chars.map((c, i) => {
                let cls = 'pdm-char';
                if (i < readUpTo) cls += ' read';
                else if (i === readUpTo) cls += ' current';
                return `<div class="${cls}">${c}</div>`;
            }).join('') || '<span style="font-size:13px;color:var(--muted);">No input</span>';
        }

        function renderPdmStack(stack) {
            const vis = document.getElementById('pdm-stack');
            vis.innerHTML = '';
            [...stack].reverse().forEach((s, i) => {
                const el = document.createElement('div');
                el.className = 'pdm-stack-item';
                if (s === 'X') {
                    el.style.background = '#EEF2FF';
                    el.style.color = '#6366F1';
                } else if (s === ',') {
                    el.style.background = '#F1F5F9';
                    el.style.color = '#475569';
                } else {
                    el.style.background = '#FEF3C7';
                    el.style.color = '#92400E';
                }
                el.textContent = s;
                vis.appendChild(el);
            });
        }

        // init
        pdmReset();

        // ════════════════════════════════════════════════════════
        // DRAG AND DROP
        // ════════════════════════════════════════════════════════
        let draggingId = null;

        function dragStart(e) {
            draggingId = e.target.id;
            e.target.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', e.target.id);
        }

        function allowDrop(e) {
            e.preventDefault();
            e.currentTarget.classList.add('drag-over');
        }
        document.addEventListener('dragleave', e => {
            if (e.target.classList) e.target.classList.remove('drag-over');
        });
        document.addEventListener('dragend', () => {
            document.querySelectorAll('.dnd-chip').forEach(c => c.classList.remove('dragging'));
            document.querySelectorAll('.dnd-zone,.dnd-bank').forEach(z => z.classList.remove('drag-over'));
        });

        function dropChip(e, targetId) {
            e.preventDefault();
            e.currentTarget.classList.remove('drag-over');
            const chipId = e.dataTransfer.getData('text/plain');
            const chip = document.getElementById(chipId);
            const target = document.getElementById(targetId);
            if (!chip || !target) return;
            const isZone = target.classList.contains('dnd-zone');
            if (isZone && target.children.length > 0) {
                const ex = target.firstElementChild;
                const b = findBank(targetId);
                if (b) {
                    document.getElementById(b).appendChild(ex);
                    ex.onclick = null;
                }
            }
            target.appendChild(chip);
            chip.classList.remove('dragging');
            if (isZone) {
                chip.onclick = () => {
                    const b = findBank(targetId);
                    if (b) {
                        document.getElementById(b).appendChild(chip);
                        chip.onclick = null;
                    }
                };
            } else chip.onclick = null;
        }

        function findBank(zoneId) {
            const m = zoneId.match(/^z(\d)/);
            return m ? 'bank' + m[1] : null;
        }

        function checkDnD(quizId, zoneIds) {
            let correct = 0;
            const total = zoneIds.length;
            zoneIds.forEach(zid => {
                const zone = document.getElementById(zid);
                const chip = zone.firstElementChild;
                zone.classList.remove('correct-zone', 'wrong-zone');
                if (chip && chip.id === zone.dataset.answer) {
                    zone.classList.add('correct-zone');
                    correct++;
                } else zone.classList.add('wrong-zone');
            });
            const fb = document.getElementById('fb-' + quizId);
            fb.classList.remove('show', 'ok', 'bad', 'partial');
            if (correct === total) {
                fb.className = 'dnd-feedback show ok';
                fb.innerHTML = `✅ <strong>Perfect!</strong> All ${total} matched correctly.`;
            } else if (correct > 0) {
                fb.className = 'dnd-feedback show partial';
                fb.innerHTML = `⚠️ <strong>${correct} of ${total}</strong> correct.`;
            } else {
                fb.className = 'dnd-feedback show bad';
                fb.innerHTML = `❌ None correct yet. Review and try again.`;
            }
        }

        function resetDnD(quizId, bankId, zoneIds) {
            const bank = document.getElementById(bankId);
            zoneIds.forEach(zid => {
                const zone = document.getElementById(zid);
                zone.classList.remove('correct-zone', 'wrong-zone');
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

        // Quiz
        const answered = {};

        function answer(el, qid, correct) {
            if (answered[qid]) return;
            answered[qid] = true;
            const opts = document.querySelectorAll(`#${qid}-opts .quiz-opt`);
            opts.forEach(o => o.classList.add('answered'));
            el.classList.add(correct ? 'correct' : 'wrong');
            el.querySelector('.opt-circle').textContent = correct ? '✓' : '✗';
            if (!correct) opts.forEach(o => {
                if (o.getAttribute('onclick')?.includes('true')) {
                    o.classList.add('correct');
                    o.querySelector('.opt-circle').textContent = '✓';
                }
            });
            const fb = document.getElementById(`${qid}-fb`);
            fb.classList.add('show', correct ? 'ok' : 'bad');
            fb.innerHTML = correct ? '✅ <strong>Correct!</strong> Well done.' : '❌ <strong>Not quite.</strong> The correct answer is highlighted above.';
        }
    </script>

</body>

</html>