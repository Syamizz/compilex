<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter 3.2 – CompileX</title>

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

        /* English sentence diagram */
        .sentence-diagram {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
            margin: 20px 0;
            overflow-x: auto;
        }

        .sd-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 13px;
            color: var(--purple);
            margin-bottom: 14px;
        }

        /* Key concept cards */
        .concept-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin: 20px 0;
        }

        .concept-card {
            background: var(--card);
            border: 1.5px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            padding: 18px;
            box-shadow: var(--shadow);
        }

        .concept-card .cc-title {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .concept-card .cc-body {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.6;
        }

        /* Parsing algorithm comparison */
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

        .algo-table tr:hover td {
            background: #FAFAFF;
        }

        .algo-table td:first-child {
            font-weight: 600;
            color: var(--text);
        }

        /* Bottom-up / Top-down visual */
        .parse-dir-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin: 20px 0;
        }

        .parse-dir-card {
            background: var(--card);
            border: 1.5px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            padding: 16px;
            box-shadow: var(--shadow);
        }

        .pdc-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .pdc-steps {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .pdc-step {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pdc-arrow {
            color: var(--purple);
            font-size: 14px;
        }

        /* Interactive: Sentence Diagrammer */
        .diagram-wrap {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin: 24px 0;
        }

        .diagram-header {
            background: linear-gradient(135deg, #6366F1, #818CF8);
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .diagram-header h4 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: white;
            margin: 0;
        }

        .diagram-body {
            padding: 20px;
        }

        .diagram-sel-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 14px;
            align-items: center;
        }

        .diagram-sel {
            padding: 8px 12px;
            border: 1.5px solid rgba(99, 102, 241, .3);
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: var(--text);
            background: var(--bg);
            outline: none;
            cursor: pointer;
            flex: 1;
        }

        .diagram-btn {
            padding: 8px 16px;
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

        .diagram-btn:hover {
            background: var(--purple-d);
        }

        .diagram-out {
            margin-top: 16px;
        }

        .diag-word-row {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .diag-word {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .diag-word .dw-word {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 8px;
            border: 2px solid;
            cursor: default;
        }

        .diag-word .dw-type {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .t-article {
            background: #EFF6FF;
            border-color: #3B82F6;
            color: #1D4ED8;
        }

        .l-article {
            color: #3B82F6;
        }

        .t-noun {
            background: #F0FDF4;
            border-color: #10B981;
            color: #065F46;
        }

        .l-noun {
            color: #10B981;
        }

        .t-verb {
            background: #FFF1F2;
            border-color: #EF4444;
            color: #9F1239;
        }

        .l-verb {
            color: #EF4444;
        }

        .t-adj {
            background: #FEF3C7;
            border-color: #F59E0B;
            color: #92400E;
        }

        .l-adj {
            color: #F59E0B;
        }

        .t-prep {
            background: var(--purple-s);
            border-color: var(--purple);
            color: var(--purple);
        }

        .l-prep {
            color: var(--purple);
        }

        .t-np {
            background: #F3E8FF;
            border-color: #A855F7;
            color: #6B21A8;
        }

        .l-np {
            color: #A855F7;
        }

        .t-pp {
            background: #FFF7ED;
            border-color: #F97316;
            color: #9A3412;
        }

        .l-pp {
            color: #F97316;
        }

        .diag-phrase-row {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 10px;
        }

        .diag-phrase {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 13px;
            border: 1.5px solid;
        }

        .diag-phrase .dp-label {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 11px;
            min-width: 100px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        /* Parse direction race */
        .race-wrap {
            background: var(--card);
            border: 1px solid rgba(16, 185, 129, .2);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin: 24px 0;
        }

        .race-header {
            background: linear-gradient(135deg, #10B981, #34D399);
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .race-header h4 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: white;
            margin: 0;
        }

        .race-body {
            padding: 20px;
        }

        .race-expr {
            font-family: 'JetBrains Mono', monospace;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
            padding: 12px;
            background: var(--code-bg);
            border-radius: 10px;
            color: #CDD6F4;
            margin-bottom: 16px;
            letter-spacing: 2px;
        }

        .race-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .race-col {
            border-radius: 10px;
            padding: 14px;
            border: 2px solid;
        }

        .race-col.top-down {
            border-color: var(--purple);
            background: var(--purple-s);
        }

        .race-col.bottom-up {
            border-color: var(--green);
            background: #F0FDF4;
        }

        .race-col-title {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .race-step {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11.5px;
            padding: 5px 8px;
            border-radius: 6px;
            margin-bottom: 4px;
            opacity: 0;
            transform: translateY(6px);
            transition: opacity .3s ease, transform .3s ease;
        }

        .race-step.show {
            opacity: 1;
            transform: translateY(0);
        }

        .race-step.td {
            background: rgba(99, 102, 241, .12);
            color: var(--purple);
        }

        .race-step.bu {
            background: rgba(16, 185, 129, .1);
            color: #065F46;
        }

        .race-controls {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 14px;
            align-items: center;
        }

        .race-sel {
            padding: 8px 12px;
            border: 1.5px solid rgba(16, 185, 129, .3);
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: var(--text);
            background: var(--bg);
            outline: none;
            cursor: pointer;
        }

        .race-btn {
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            background: var(--green);
            color: white;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: background .18s;
        }

        .race-btn:hover {
            background: #059669;
        }

        .race-btn.sec {
            background: transparent;
            border: 1.5px solid rgba(16, 185, 129, .3);
            color: var(--green);
        }

        .race-btn.sec:hover {
            background: #F0FDF4;
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
            padding: 7px 14px;
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

            .parse-dir-wrap,
            .race-grid,
            .concept-grid {
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
                <li><a href="#overview" class="toc-link">3.2 The Parsing Problem</a></li>
                <li><a href="#fig312" class="toc-link sub">↳ Figure 3.12 — English Sentence</a></li>
                <li><a href="#parsing-def" class="toc-link">Formal Definition</a></li>
                <li><a href="#algotypes" class="toc-link">Bottom-Up vs Top-Down</a></li>
                <li><a href="#completesearch" class="toc-link">Complete Search — Why Rejected</a></li>
                <li><a href="#sentence-diag" class="toc-link">🔤 Sentence Diagrammer</a></li>
                <li><a href="#parse-race" class="toc-link">⚡ Parse Direction Visualiser</a></li>
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
                <div class="chapter-tag">📘 Chapter 3 · Section 3.2</div>
                <h1>The Parsing Problem</h1>
                <div class="metadata">
                    <span>⏱ 14 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>📐 Syntax Analysis</span>
                </div>
            </header>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 3.2 Overview                                   -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="overview">
                <h2><span class="sec-icon">📝</span> 3.2 The Parsing Problem</h2>
                <p>
                    You may recall from school the task of <strong>diagramming English sentences</strong> — grouping
                    words into phrases and assigning syntactic types such as noun phrase, predicate, and
                    prepositional phrase. This is exactly the same problem a compiler must solve during
                    <strong>syntax analysis</strong>.
                </p>
                <p>
                    The parsing problem is: given a grammar G and an input string of symbols, determine
                    (1) whether the string is in L(G), and (2) the <strong>structure</strong> of the string —
                    typically a derivation tree.
                </p>

                <div class="concept-grid">
                    <div class="concept-card">
                        <div class="cc-title" style="color:var(--purple);">What is parsing?</div>
                        <div class="cc-body">Given a grammar G and an input string, decide whether the string is in L(G) — and if so, produce a description of its structure (e.g. a derivation tree).</div>
                    </div>
                    <div class="concept-card">
                        <div class="cc-title" style="color:var(--green);">What is a parsing algorithm?</div>
                        <div class="cc-body">An algorithm that solves the parsing problem for a particular class of grammars. A good parsing algorithm is applicable to a large class of grammars and runs efficiently.</div>
                    </div>
                </div>
            </section>

            <!-- Figure 3.12 -->
            <section id="fig312">
                <h2><span class="sec-icon">🔤</span> Figure 3.12 — Diagrammed English Sentence</h2>
                <p>
                    The diagram below shows the syntactic structure of the sentence
                    <em>"The boy hugged the dog of a close neighbor"</em>.
                    Each word is first classified (Article, Noun, Verb, etc.), then words are grouped into
                    phrases (NounPhrase, PrepositionalPhrase), and finally the whole sentence structure
                    is identified. A compiler does the same thing for source code.
                </p>

                <div class="sentence-diagram">
                    <div class="sd-title">Figure 3.12 — Diagram of an English Sentence</div>
                    <svg width="100%" viewBox="0 0 760 300" style="display:block;min-width:500px;">
                        <defs>
                            <marker id="ta" markerWidth="6" markerHeight="6" refX="3" refY="3" orient="auto">
                                <circle cx="3" cy="3" r="2.5" fill="#6366F1" opacity=".35" />
                            </marker>
                        </defs>

                        <!-- Word boxes at bottom row -->
                        <!-- The -->
                        <rect x="4" y="240" width="50" height="28" rx="6" fill="#EFF6FF" stroke="#3B82F6" stroke-width="1.5" />
                        <text x="29" y="258" text-anchor="middle" fill="#1D4ED8" font-size="11" font-weight="700" font-family="JetBrains Mono">The</text>
                        <!-- boy -->
                        <rect x="60" y="240" width="44" height="28" rx="6" fill="#F0FDF4" stroke="#10B981" stroke-width="1.5" />
                        <text x="82" y="258" text-anchor="middle" fill="#065F46" font-size="11" font-weight="700" font-family="JetBrains Mono">boy</text>
                        <!-- hugged -->
                        <rect x="110" y="240" width="62" height="28" rx="6" fill="#FFF1F2" stroke="#EF4444" stroke-width="1.5" />
                        <text x="141" y="258" text-anchor="middle" fill="#9F1239" font-size="11" font-weight="700" font-family="JetBrains Mono">hugged</text>
                        <!-- the -->
                        <rect x="178" y="240" width="44" height="28" rx="6" fill="#EFF6FF" stroke="#3B82F6" stroke-width="1.5" />
                        <text x="200" y="258" text-anchor="middle" fill="#1D4ED8" font-size="11" font-weight="700" font-family="JetBrains Mono">the</text>
                        <!-- dog -->
                        <rect x="228" y="240" width="44" height="28" rx="6" fill="#F0FDF4" stroke="#10B981" stroke-width="1.5" />
                        <text x="250" y="258" text-anchor="middle" fill="#065F46" font-size="11" font-weight="700" font-family="JetBrains Mono">dog</text>
                        <!-- of -->
                        <rect x="278" y="240" width="36" height="28" rx="6" fill="var(--purple-s)" stroke="var(--purple)" stroke-width="1.5" />
                        <text x="296" y="258" text-anchor="middle" fill="var(--purple)" font-size="11" font-weight="700" font-family="JetBrains Mono">of</text>
                        <!-- a -->
                        <rect x="320" y="240" width="30" height="28" rx="6" fill="#EFF6FF" stroke="#3B82F6" stroke-width="1.5" />
                        <text x="335" y="258" text-anchor="middle" fill="#1D4ED8" font-size="11" font-weight="700" font-family="JetBrains Mono">a</text>
                        <!-- close -->
                        <rect x="356" y="240" width="50" height="28" rx="6" fill="#FEF3C7" stroke="#F59E0B" stroke-width="1.5" />
                        <text x="381" y="258" text-anchor="middle" fill="#92400E" font-size="11" font-weight="700" font-family="JetBrains Mono">close</text>
                        <!-- neighbor -->
                        <rect x="412" y="240" width="72" height="28" rx="6" fill="#F0FDF4" stroke="#10B981" stroke-width="1.5" />
                        <text x="448" y="258" text-anchor="middle" fill="#065F46" font-size="11" font-weight="700" font-family="JetBrains Mono">neighbor</text>

                        <!-- POS labels below word boxes -->
                        <text x="29" y="278" text-anchor="middle" fill="#3B82F6" font-size="9" font-family="Syne" font-weight="700">Article</text>
                        <text x="82" y="278" text-anchor="middle" fill="#10B981" font-size="9" font-family="Syne" font-weight="700">Noun</text>
                        <text x="141" y="278" text-anchor="middle" fill="#EF4444" font-size="9" font-family="Syne" font-weight="700">Verb</text>
                        <text x="200" y="278" text-anchor="middle" fill="#3B82F6" font-size="9" font-family="Syne" font-weight="700">Article</text>
                        <text x="250" y="278" text-anchor="middle" fill="#10B981" font-size="9" font-family="Syne" font-weight="700">Noun</text>
                        <text x="296" y="278" text-anchor="middle" fill="var(--purple)" font-size="9" font-family="Syne" font-weight="700">Prep</text>
                        <text x="335" y="278" text-anchor="middle" fill="#3B82F6" font-size="9" font-family="Syne" font-weight="700">Article</text>
                        <text x="381" y="278" text-anchor="middle" fill="#F59E0B" font-size="9" font-family="Syne" font-weight="700">Adj</text>
                        <text x="448" y="278" text-anchor="middle" fill="#10B981" font-size="9" font-family="Syne" font-weight="700">Noun</text>

                        <!-- Level 2: NounPhrases -->
                        <!-- NP1: The boy -->
                        <line x1="29" y1="240" x2="55" y2="210" stroke="#A855F7" stroke-width="1.4" opacity=".5" />
                        <line x1="82" y1="240" x2="55" y2="210" stroke="#A855F7" stroke-width="1.4" opacity=".5" />
                        <rect x="10" y="195" width="90" height="22" rx="6" fill="#F3E8FF" stroke="#A855F7" stroke-width="1.5" />
                        <text x="55" y="210" text-anchor="middle" fill="#6B21A8" font-size="10" font-weight="700" font-family="Syne">NounPhrase</text>

                        <!-- NP2: the dog -->
                        <line x1="200" y1="240" x2="224" y2="210" stroke="#A855F7" stroke-width="1.4" opacity=".5" />
                        <line x1="250" y1="240" x2="224" y2="210" stroke="#A855F7" stroke-width="1.4" opacity=".5" />
                        <rect x="180" y="195" width="88" height="22" rx="6" fill="#F3E8FF" stroke="#A855F7" stroke-width="1.5" />
                        <text x="224" y="210" text-anchor="middle" fill="#6B21A8" font-size="10" font-weight="700" font-family="Syne">NounPhrase</text>

                        <!-- NP3: a close neighbor -->
                        <line x1="335" y1="240" x2="415" y2="210" stroke="#A855F7" stroke-width="1.4" opacity=".5" />
                        <line x1="381" y1="240" x2="415" y2="210" stroke="#A855F7" stroke-width="1.4" opacity=".5" />
                        <line x1="448" y1="240" x2="415" y2="210" stroke="#A855F7" stroke-width="1.4" opacity=".5" />
                        <rect x="365" y="195" width="100" height="22" rx="6" fill="#F3E8FF" stroke="#A855F7" stroke-width="1.5" />
                        <text x="415" y="210" text-anchor="middle" fill="#6B21A8" font-size="10" font-weight="700" font-family="Syne">NounPhrase</text>

                        <!-- PrepPhrase: of + NP3 -->
                        <line x1="296" y1="240" x2="355" y2="170" stroke="#F97316" stroke-width="1.4" opacity=".5" />
                        <line x1="415" y1="195" x2="355" y2="170" stroke="#F97316" stroke-width="1.4" opacity=".5" />
                        <rect x="285" y="155" width="140" height="22" rx="6" fill="#FFF7ED" stroke="#F97316" stroke-width="1.5" />
                        <text x="355" y="170" text-anchor="middle" fill="#9A3412" font-size="10" font-weight="700" font-family="Syne">PrepositionalPhrase</text>

                        <!-- Level 3: Subject, DirectObject -->
                        <!-- Subject: NP1 -->
                        <line x1="55" y1="195" x2="55" y2="130" stroke="#6366F1" stroke-width="1.4" opacity=".5" />
                        <rect x="12" y="115" width="86" height="22" rx="6" fill="var(--purple-s)" stroke="var(--purple)" stroke-width="1.5" />
                        <text x="55" y="130" text-anchor="middle" fill="var(--purple)" font-size="10" font-weight="700" font-family="Syne">Subject</text>

                        <!-- DirectObject: NP2 + PrepPhrase -->
                        <line x1="224" y1="195" x2="280" y2="130" stroke="#6366F1" stroke-width="1.4" opacity=".5" />
                        <line x1="355" y1="155" x2="280" y2="130" stroke="#6366F1" stroke-width="1.4" opacity=".5" />
                        <rect x="210" y="115" width="140" height="22" rx="6" fill="var(--purple-s)" stroke="var(--purple)" stroke-width="1.5" />
                        <text x="280" y="130" text-anchor="middle" fill="var(--purple)" font-size="10" font-weight="700" font-family="Syne">DirectObject</text>

                        <!-- Level 4: Verb + DirectObject = Predicate -->
                        <line x1="141" y1="240" x2="200" y2="80" stroke="#6366F1" stroke-width="1.4" opacity=".4" />
                        <line x1="280" y1="115" x2="200" y2="80" stroke="#6366F1" stroke-width="1.4" opacity=".4" />
                        <rect x="138" y="65" width="124" height="22" rx="6" fill="#EFF6FF" stroke="#3B82F6" stroke-width="1.5" />
                        <text x="200" y="80" text-anchor="middle" fill="#1D4ED8" font-size="10" font-weight="700" font-family="Syne">Predicate</text>

                        <!-- Level 5: Subject + Predicate = Sentence -->
                        <line x1="55" y1="115" x2="170" y2="32" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="200" y1="65" x2="170" y2="32" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <rect x="110" y="16" width="120" height="24" rx="7" fill="rgba(99,102,241,.15)" stroke="var(--purple)" stroke-width="2" />
                        <text x="170" y="32" text-anchor="middle" fill="var(--purple)" font-size="11" font-weight="800" font-family="Syne">Sentence</text>
                    </svg>
                </div>

                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>
                        <strong>Analogy:</strong> Diagramming a sentence = syntax analysis. Words are like tokens;
                        phrase types (NounPhrase, PrepositionalPhrase) are like nonterminals; the parse tree
                        structure is the derivation tree. The compiler builds exactly this structure for source code.
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Formal definition                             -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="parsing-def">
                <h2><span class="sec-icon">📐</span> The Parsing Problem — Formal Definition</h2>
                <p>
                    The <strong>parsing problem</strong> is formally stated as:
                </p>
                <div style="background:var(--code-bg);border-radius:var(--radius);padding:20px 24px;margin:16px 0;font-size:14px;color:#CDD6F4;line-height:2;">
                    <span style="color:#CBA6F7;font-weight:700;">Given:</span> a grammar G and a string of input symbols<br>
                    <span style="color:#A6E3A1;font-weight:700;">Decide:</span> whether the string is in L(G)<br>
                    <span style="color:#89B4FA;font-weight:700;">Also:</span> determine the <em>structure</em> of the string — a derivation tree<br>
                    <span style="color:#FAB387;font-weight:700;">Output:</span> "YES" (+ derivation tree) or "NO" (+ error message)
                </div>

                <div class="concept-grid">
                    <div class="concept-card">
                        <div class="cc-title" style="color:var(--green);">✅ Accepted string</div>
                        <div class="cc-body">The string is in L(G). The parser produces a derivation tree that describes the string's complete structure. This tree drives the next phases of the compiler.</div>
                    </div>
                    <div class="concept-card">
                        <div class="cc-title" style="color:var(--red);">❌ Rejected string</div>
                        <div class="cc-body">The string is NOT in L(G). The compiler outputs an informative syntax error message. A good compiler continues scanning to find additional errors.</div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Bottom-Up vs Top-Down                         -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="algotypes">
                <h2><span class="sec-icon">🔄</span> Bottom-Up vs Top-Down Parsing</h2>
                <p>
                    For <strong>context-free grammars</strong>, there are two kinds of parsing algorithms.
                    These terms refer to the sequence in which the derivation tree is built.
                </p>

                <div class="parse-dir-wrap">
                    <div class="parse-dir-card" style="border:2px solid var(--purple);">
                        <div class="pdc-title" style="color:var(--purple);">⬇️ Top-Down Parsing</div>
                        <p style="font-size:13px;color:var(--muted);margin-bottom:12px;">
                            Builds the derivation tree from the <strong>root (start symbol) down to the leaves</strong>.
                            Starts with the start nonterminal and tries to derive the input string using rewriting rules.
                        </p>
                        <div class="pdc-steps">
                            <div class="pdc-step" style="background:rgba(99,102,241,.1);">Start: <span style="color:#CBA6F7;">Expr</span></div>
                            <div class="pdc-step" style="background:rgba(99,102,241,.08);"><span class="pdc-arrow">↓</span> Apply rule: <span style="color:#CBA6F7;">Expr → Expr + Term</span></div>
                            <div class="pdc-step" style="background:rgba(99,102,241,.08);"><span class="pdc-arrow">↓</span> Apply rule: <span style="color:#CBA6F7;">Expr → Term</span></div>
                            <div class="pdc-step" style="background:rgba(99,102,241,.06);"><span class="pdc-arrow">↓</span> … expand until leaves match input</div>
                            <div class="pdc-step" style="background:rgba(16,185,129,.1);"><span style="color:var(--green);">✓</span> Leaves = <span style="color:#A6E3A1;">var + var * var</span></div>
                        </div>
                        <div class="note-box key" style="margin-top:12px;font-size:13px;">
                            <span class="box-icon">💡</span>
                            <div>Prediction-based. Works best when the grammar is in a restricted form (LL grammars). Easier to implement by hand.</div>
                        </div>
                    </div>

                    <div class="parse-dir-card" style="border:2px solid var(--green);">
                        <div class="pdc-title" style="color:var(--green);">⬆️ Bottom-Up Parsing</div>
                        <p style="font-size:13px;color:var(--muted);margin-bottom:12px;">
                            Builds the derivation tree from the <strong>leaves (input tokens) up to the root</strong>.
                            Reads input tokens and reduces them to nonterminals, until the start symbol is reached.
                        </p>
                        <div class="pdc-steps">
                            <div class="pdc-step" style="background:rgba(16,185,129,.08);">Start: <span style="color:#A6E3A1;">var + var * var</span></div>
                            <div class="pdc-step" style="background:rgba(16,185,129,.07);"><span class="pdc-arrow">↑</span> Reduce: <span style="color:#A6E3A1;">var</span> → <span style="color:#CBA6F7;">Factor</span></div>
                            <div class="pdc-step" style="background:rgba(16,185,129,.07);"><span class="pdc-arrow">↑</span> Reduce: <span style="color:#CBA6F7;">Factor</span> → <span style="color:#CBA6F7;">Term</span></div>
                            <div class="pdc-step" style="background:rgba(16,185,129,.06);"><span class="pdc-arrow">↑</span> … reduce until start symbol</div>
                            <div class="pdc-step" style="background:rgba(16,185,129,.1);"><span style="color:var(--green);">✓</span> Reach: <span style="color:#CBA6F7;">Expr</span></div>
                        </div>
                        <div class="note-box key" style="margin-top:12px;font-size:13px;">
                            <span class="box-icon">💡</span>
                            <div>Reduction-based. Handles a wider class of grammars (LR grammars). More powerful but typically harder to implement by hand; usually generated by tools.</div>
                        </div>
                    </div>
                </div>

                <table class="algo-table">
                    <thead>
                        <tr>
                            <th>Property</th>
                            <th>Top-Down</th>
                            <th>Bottom-Up</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tree construction direction</td>
                            <td>Root → leaves</td>
                            <td>Leaves → root</td>
                        </tr>
                        <tr>
                            <td>Starting point</td>
                            <td>Start symbol (nonterminal)</td>
                            <td>Input tokens (terminals)</td>
                        </tr>
                        <tr>
                            <td>Operation</td>
                            <td>Prediction / expansion</td>
                            <td>Shift / reduce</td>
                        </tr>
                        <tr>
                            <td>Grammar class</td>
                            <td>LL(k) grammars</td>
                            <td>LR(k) grammars (larger class)</td>
                        </tr>
                        <tr>
                            <td>Implementation</td>
                            <td>Easier by hand</td>
                            <td>Usually tool-generated (yacc, SableCC)</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Complete Search                               -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="completesearch">
                <h2><span class="sec-icon">🐢</span> Complete Search — Why It Is Rejected</h2>
                <p>
                    There exist parsing algorithms that can be applied to <em>any</em> context-free grammar,
                    using a <strong>complete search strategy</strong> — trying all possible parse sequences until
                    one succeeds or all possibilities are exhausted.
                </p>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        <strong>Why complete search is unacceptable:</strong> These algorithms are generally too slow.
                        They cannot run in <strong>polynomial time</strong> — their time complexity grows too fast
                        for practical use in a compiler that must handle real programs. For this reason, practical
                        compilers use restricted grammar classes (LL or LR) that admit efficient parsing algorithms
                        running in <strong>linear time O(n)</strong> in the length of the input.
                    </div>
                </div>

                <div class="concept-grid">
                    <div class="concept-card">
                        <div class="cc-title" style="color:var(--red);">❌ Complete search</div>
                        <div class="cc-body">Works for any CFG. Tries all possible derivations systematically. Exponential/polynomial time — not practical for real compilers.</div>
                    </div>
                    <div class="concept-card">
                        <div class="cc-title" style="color:var(--green);">✅ Efficient parsers</div>
                        <div class="cc-body">Work for restricted grammar classes (LL, LR). Run in <strong>O(n) linear time</strong>. Require the grammar to satisfy certain structural properties. Used in all practical compilers.</div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Interactive 1: Sentence Diagrammer            -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="sentence-diag">
                <h2><span class="sec-icon">🔤</span> Interactive — Sentence Diagrammer</h2>
                <p>
                    Select a sentence. The diagrammer will identify each word's part of speech, group them into
                    phrases, and build the complete sentence structure — just like the compiler's syntax analysis
                    phase builds a parse tree from tokens.
                </p>

                <div class="diagram-wrap">
                    <div class="diagram-header">
                        <h4>📖 Sentence Structure Analyser</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);">Part-of-speech → phrases → structure</span>
                    </div>
                    <div class="diagram-body">
                        <div class="diagram-sel-row">
                            <select class="diagram-sel" id="sent-sel" onchange="diagramSentence()">
                                <option value="0">The boy hugged the dog of a close neighbor (Fig 3.12)</option>
                                <option value="1">A compiler reads the input program</option>
                                <option value="2">The lexer scans a large source file</option>
                                <option value="3">Each token carries a class and a value</option>
                            </select>
                            <button class="diagram-btn" onclick="diagramSentence()">Analyse →</button>
                        </div>
                        <div class="diagram-out" id="diagram-out" style="display:none;">
                            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px;">Word classification:</div>
                            <div class="diag-word-row" id="diag-words"></div>
                            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px;margin:16px 0 8px;">Phrase structure:</div>
                            <div class="diag-phrase-row" id="diag-phrases"></div>
                            <div class="note-box pro" style="margin-top:14px;">
                                <span class="box-icon">🧩</span>
                                <div id="diag-analogy">This parallels compiler syntax analysis: words = tokens, phrase types = nonterminals, tree = derivation tree.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Interactive 2: Parse Direction Visualiser     -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="parse-race">
                <h2><span class="sec-icon">⚡</span> Parse Direction Visualiser — Top-Down vs Bottom-Up</h2>
                <p>
                    Select an expression and watch both parsing strategies build the derivation tree for
                    <code>var + var * var</code> using Grammar G5. Top-down starts from the start symbol;
                    bottom-up starts from the tokens.
                </p>

                <div class="race-wrap">
                    <div class="race-header">
                        <h4>⚡ Top-Down vs Bottom-Up — Side by Side</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);">Grammar G5</span>
                    </div>
                    <div class="race-body">
                        <div class="race-controls">
                            <select class="race-sel" id="race-sel">
                                <option value="vpv">var + var * var</option>
                                <option value="vv">var * var</option>
                                <option value="pvpv">(var + var) * var</option>
                            </select>
                            <button class="race-btn" onclick="raceStep()" id="race-step-btn">Step →</button>
                            <button class="race-btn sec" onclick="raceRunAll()">Run All ⚡</button>
                            <button class="race-btn sec" onclick="raceReset()">↺ Reset</button>
                        </div>
                        <div class="race-expr" id="race-expr">var + var * var</div>
                        <div class="race-grid">
                            <div class="race-col top-down">
                                <div class="race-col-title" style="color:var(--purple);">⬇️ Top-Down (Predict / Expand)</div>
                                <div id="td-steps"></div>
                            </div>
                            <div class="race-col bottom-up">
                                <div class="race-col-title" style="color:var(--green);">⬆️ Bottom-Up (Shift / Reduce)</div>
                                <div id="bu-steps"></div>
                            </div>
                        </div>
                        <div id="race-result" style="display:none;margin-top:12px;padding:10px 16px;border-radius:10px;background:#F0FDF4;color:#065F46;font-size:14px;font-weight:600;"></div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- DRAG AND DROP — 5 chips                       -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="dragdrop">
                <h2><span class="sec-icon">🎯</span> Drag &amp; Drop Quiz</h2>
                <p>Each question has <strong>5 chips</strong> and 5 zones. Drag each chip to the correct zone.</p>

                <!-- DnD Q1 -->
                <div class="dnd-quiz" id="dnd1">
                    <div class="dnd-header" style="background:linear-gradient(135deg,#6366F1,#818CF8);">
                        <div class="dnd-q-num" style="background:rgba(255,255,255,.2);color:white;">1</div>
                        <div class="dnd-q-text" style="color:white;">Match each concept to its correct description</div>
                    </div>
                    <div class="dnd-body">
                        <p class="dnd-prompt">Drag each term to its correct definition box.</p>
                        <div class="dnd-bank" id="bank1" ondrop="dropChip(event,'bank1')" ondragover="allowDrop(event)">
                            <div class="dnd-chip chip-a" draggable="true" ondragstart="dragStart(event)" id="c1-parsing">Parsing problem</div>
                            <div class="dnd-chip chip-b" draggable="true" ondragstart="dragStart(event)" id="c1-topdown">Top-down parsing</div>
                            <div class="dnd-chip chip-c" draggable="true" ondragstart="dragStart(event)" id="c1-bottomup">Bottom-up parsing</div>
                            <div class="dnd-chip chip-d" draggable="true" ondragstart="dragStart(event)" id="c1-complete">Complete search</div>
                            <div class="dnd-chip chip-e" draggable="true" ondragstart="dragStart(event)" id="c1-algo">Parsing algorithm</div>
                        </div>
                        <div class="dnd-zones">
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#F59E0B;">Is string in L(G)? Also find its structure</div>
                                <div class="dnd-zone" id="z1a" ondrop="dropChip(event,'z1a')" ondragover="allowDrop(event)" data-answer="c1-parsing"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#3B82F6;">Builds tree from root down to leaves</div>
                                <div class="dnd-zone" id="z1b" ondrop="dropChip(event,'z1b')" ondragover="allowDrop(event)" data-answer="c1-topdown"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#9F1239;">Builds tree from leaves up to root</div>
                                <div class="dnd-zone" id="z1c" ondrop="dropChip(event,'z1c')" ondragover="allowDrop(event)" data-answer="c1-bottomup"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#EF4444;">Tries all possibilities — too slow</div>
                                <div class="dnd-zone" id="z1d" ondrop="dropChip(event,'z1d')" ondragover="allowDrop(event)" data-answer="c1-complete"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#10B981;">Solves parsing for a particular grammar class</div>
                                <div class="dnd-zone" id="z1e" ondrop="dropChip(event,'z1e')" ondragover="allowDrop(event)" data-answer="c1-algo"></div>
                            </div>
                        </div>
                        <button class="dnd-check" onclick="checkDnD('dnd1',['z1a','z1b','z1c','z1d','z1e'])">✓ Check</button>
                        <button class="dnd-reset-btn" onclick="resetDnD('dnd1','bank1',['z1a','z1b','z1c','z1d','z1e'])">↺ Reset</button>
                        <div class="dnd-feedback" id="fb-dnd1"></div>
                    </div>
                </div>

                <!-- DnD Q2 -->
                <div class="dnd-quiz" id="dnd2">
                    <div class="dnd-header" style="background:linear-gradient(135deg,#10B981,#34D399);">
                        <div class="dnd-q-num" style="background:rgba(255,255,255,.2);color:white;">2</div>
                        <div class="dnd-q-text" style="color:white;">Match each sentence component to its compiler equivalent</div>
                    </div>
                    <div class="dnd-body">
                        <p class="dnd-prompt">The English sentence diagram (Fig 3.12) maps directly to compiler concepts. Match them.</p>
                        <div class="dnd-bank" id="bank2" ondrop="dropChip(event,'bank2')" ondragover="allowDrop(event)">
                            <div class="dnd-chip chip-a" draggable="true" ondragstart="dragStart(event)" id="c2-words">Words</div>
                            <div class="dnd-chip chip-b" draggable="true" ondragstart="dragStart(event)" id="c2-phrtype">Phrase types (NP, PP…)</div>
                            <div class="dnd-chip chip-c" draggable="true" ondragstart="dragStart(event)" id="c2-diagram">Sentence diagram</div>
                            <div class="dnd-chip chip-d" draggable="true" ondragstart="dragStart(event)" id="c2-grammar">English grammar rules</div>
                            <div class="dnd-chip chip-e" draggable="true" ondragstart="dragStart(event)" id="c2-sent">Sentence</div>
                        </div>
                        <div class="dnd-zones">
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#F59E0B;">Tokens (lexical items)</div>
                                <div class="dnd-zone" id="z2a" ondrop="dropChip(event,'z2a')" ondragover="allowDrop(event)" data-answer="c2-words"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#3B82F6;">Nonterminals (grammar symbols)</div>
                                <div class="dnd-zone" id="z2b" ondrop="dropChip(event,'z2b')" ondragover="allowDrop(event)" data-answer="c2-phrtype"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#9F1239;">Derivation tree (parse tree)</div>
                                <div class="dnd-zone" id="z2c" ondrop="dropChip(event,'z2c')" ondragover="allowDrop(event)" data-answer="c2-diagram"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#10B981;">Context-free grammar (CFG)</div>
                                <div class="dnd-zone" id="z2d" ondrop="dropChip(event,'z2d')" ondragover="allowDrop(event)" data-answer="c2-grammar"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#6366F1;">Program / source string</div>
                                <div class="dnd-zone" id="z2e" ondrop="dropChip(event,'z2e')" ondragover="allowDrop(event)" data-answer="c2-sent"></div>
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
                        <p class="quiz-q">What are the two outputs a parser must provide for an accepted input string?</p>
                        <div class="quiz-options" id="q1-opts">
                            <div class="quiz-opt" onclick="answer(this,'q1',false)">
                                <div class="opt-circle">A</div> "YES" and the list of grammar rules used
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q1',true)">
                                <div class="opt-circle">B</div> "YES" and some description of the string's structure — such as a derivation tree
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q1',false)">
                                <div class="opt-circle">C</div> The token stream and an error count
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q1',false)">
                                <div class="opt-circle">D</div> The syntax tree and the compiled machine code
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
                        <p class="quiz-q">In the analogy between English sentence diagramming and compiler syntax analysis, what corresponds to a <em>word</em> in the source program?</p>
                        <div class="quiz-options" id="q2-opts">
                            <div class="quiz-opt" onclick="answer(this,'q2',true)">
                                <div class="opt-circle">A</div> A token produced by the lexical analysis phase
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q2',false)">
                                <div class="opt-circle">B</div> A grammar rule (production)
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q2',false)">
                                <div class="opt-circle">C</div> A nonterminal symbol in the grammar
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q2',false)">
                                <div class="opt-circle">D</div> A machine instruction in the output
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
                        <p class="quiz-q">Which statement best describes the difference between top-down and bottom-up parsing?</p>
                        <div class="quiz-options" id="q3-opts">
                            <div class="quiz-opt" onclick="answer(this,'q3',false)">
                                <div class="opt-circle">A</div> Top-down parsing is always faster than bottom-up parsing
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q3',false)">
                                <div class="opt-circle">B</div> Bottom-up parsing is only applicable to right-linear grammars
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q3',true)">
                                <div class="opt-circle">C</div> Top-down builds the tree from the start symbol downward (prediction/expansion); bottom-up builds from input tokens upward (shift/reduce). Bottom-up handles a wider class of grammars.
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q3',false)">
                                <div class="opt-circle">D</div> They are equivalent — both produce identical parse trees via the same steps
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
                        <p class="quiz-q">Why are complete-search parsing algorithms considered unacceptable for use in real compilers?</p>
                        <div class="quiz-options" id="q4-opts">
                            <div class="quiz-opt" onclick="answer(this,'q4',false)">
                                <div class="opt-circle">A</div> They only work for right-linear grammars
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q4',false)">
                                <div class="opt-circle">B</div> They cannot produce derivation trees
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q4',true)">
                                <div class="opt-circle">C</div> They are too slow — they cannot run in polynomial time, making them impractical for real programs
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q4',false)">
                                <div class="opt-circle">D</div> They require the grammar to be unambiguous
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
                        <p class="quiz-q">A parsing algorithm should be applicable to a <em>large class of grammars</em>. Why is this important?</p>
                        <div class="quiz-options" id="q5-opts">
                            <div class="quiz-opt" onclick="answer(this,'q5',false)">
                                <div class="opt-circle">A</div> So that the parser can handle natural language as well as programming languages
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q5',false)">
                                <div class="opt-circle">B</div> To reduce the number of tokens that the lexical analyser must produce
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q5',true)">
                                <div class="opt-circle">C</div> So that the grammar writer has flexibility in expressing the programming language and so it accommodates the kinds of rewriting rules normally found in programming language grammars
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q5',false)">
                                <div class="opt-circle">D</div> To allow the same parser to be used for both lexical and syntax analysis
                            </div>
                        </div>
                        <div class="quiz-feedback" id="q5-fb"></div>
                    </div>
                </div>

            </section>

            <div class="chapter-nav">
                <a href="c3_1.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous section</span>3.1 Ambiguities in Programming Languages</div>
                </a>
                <a href="../home.php" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Complete</span>Homepage</div>
                    <span>→</span>
                </a>
            </div>

        </article>
    </div>

    <script>
        // TOC
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
        // SENTENCE DIAGRAMMER
        // ════════════════════════════════════════════════════════
        const SENTENCES = [{
                words: [{
                        w: 'The',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'boy',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    },
                    {
                        w: 'hugged',
                        t: 'Verb',
                        cls: 't-verb',
                        lt: 'l-verb'
                    },
                    {
                        w: 'the',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'dog',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    },
                    {
                        w: 'of',
                        t: 'Prep',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        w: 'a',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'close',
                        t: 'Adj',
                        cls: 't-adj',
                        lt: 'l-adj'
                    },
                    {
                        w: 'neighbor',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    }
                ],
                phrases: [{
                        label: 'NounPhrase',
                        words: 'The boy',
                        cls: 't-np',
                        lt: 'l-np'
                    },
                    {
                        label: 'NounPhrase',
                        words: 'the dog',
                        cls: 't-np',
                        lt: 'l-np'
                    },
                    {
                        label: 'NounPhrase',
                        words: 'a close neighbor',
                        cls: 't-np',
                        lt: 'l-np'
                    },
                    {
                        label: 'PrepPhrase',
                        words: 'of a close neighbor',
                        cls: 't-pp',
                        lt: 'l-pp'
                    },
                    {
                        label: 'Subject',
                        words: 'The boy',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        label: 'DirectObject',
                        words: 'the dog of a close neighbor',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        label: 'Predicate',
                        words: 'hugged the dog of a close neighbor',
                        cls: 't-verb',
                        lt: 'l-verb'
                    },
                    {
                        label: 'Sentence',
                        words: 'The boy hugged the dog of a close neighbor',
                        cls: 't-article',
                        lt: 'l-article'
                    }
                ],
                analogy: 'In Fig 3.12: Articles/Nouns/Verb = tokens; NounPhrase/PrepPhrase = nonterminals; the full diagram = derivation tree.'
            },
            {
                words: [{
                        w: 'A',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'compiler',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    },
                    {
                        w: 'reads',
                        t: 'Verb',
                        cls: 't-verb',
                        lt: 'l-verb'
                    },
                    {
                        w: 'the',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'input',
                        t: 'Adj',
                        cls: 't-adj',
                        lt: 'l-adj'
                    },
                    {
                        w: 'program',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    }
                ],
                phrases: [{
                        label: 'NounPhrase',
                        words: 'A compiler',
                        cls: 't-np',
                        lt: 'l-np'
                    },
                    {
                        label: 'NounPhrase',
                        words: 'the input program',
                        cls: 't-np',
                        lt: 'l-np'
                    },
                    {
                        label: 'Subject',
                        words: 'A compiler',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        label: 'DirectObject',
                        words: 'the input program',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        label: 'Predicate',
                        words: 'reads the input program',
                        cls: 't-verb',
                        lt: 'l-verb'
                    },
                    {
                        label: 'Sentence',
                        words: 'A compiler reads the input program',
                        cls: 't-article',
                        lt: 'l-article'
                    }
                ],
                analogy: '"A compiler" and "the input program" are NounPhrases (nonterminals). Verb + DirectObject = Predicate. The full tree = derivation tree for this "source string".'
            },
            {
                words: [{
                        w: 'The',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'lexer',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    },
                    {
                        w: 'scans',
                        t: 'Verb',
                        cls: 't-verb',
                        lt: 'l-verb'
                    },
                    {
                        w: 'a',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'large',
                        t: 'Adj',
                        cls: 't-adj',
                        lt: 'l-adj'
                    },
                    {
                        w: 'source',
                        t: 'Adj',
                        cls: 't-adj',
                        lt: 'l-adj'
                    },
                    {
                        w: 'file',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    }
                ],
                phrases: [{
                        label: 'NounPhrase',
                        words: 'The lexer',
                        cls: 't-np',
                        lt: 'l-np'
                    },
                    {
                        label: 'NounPhrase',
                        words: 'a large source file',
                        cls: 't-np',
                        lt: 'l-np'
                    },
                    {
                        label: 'Subject',
                        words: 'The lexer',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        label: 'DirectObject',
                        words: 'a large source file',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        label: 'Predicate',
                        words: 'scans a large source file',
                        cls: 't-verb',
                        lt: 'l-verb'
                    },
                    {
                        label: 'Sentence',
                        words: 'The lexer scans a large source file',
                        cls: 't-article',
                        lt: 'l-article'
                    }
                ],
                analogy: 'Adjectives modify Nouns (like operators qualifying operands). The phrase structure parallels how operators and operands are grouped in a parse tree.'
            },
            {
                words: [{
                        w: 'Each',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'token',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    },
                    {
                        w: 'carries',
                        t: 'Verb',
                        cls: 't-verb',
                        lt: 'l-verb'
                    },
                    {
                        w: 'a',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'class',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    },
                    {
                        w: 'and',
                        t: 'Prep',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        w: 'a',
                        t: 'Article',
                        cls: 't-article',
                        lt: 'l-article'
                    },
                    {
                        w: 'value',
                        t: 'Noun',
                        cls: 't-noun',
                        lt: 'l-noun'
                    }
                ],
                phrases: [{
                        label: 'NounPhrase',
                        words: 'Each token',
                        cls: 't-np',
                        lt: 'l-np'
                    },
                    {
                        label: 'NounPhrase',
                        words: 'a class and a value',
                        cls: 't-np',
                        lt: 'l-np'
                    },
                    {
                        label: 'Subject',
                        words: 'Each token',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        label: 'DirectObject',
                        words: 'a class and a value',
                        cls: 't-prep',
                        lt: 'l-prep'
                    },
                    {
                        label: 'Predicate',
                        words: 'carries a class and a value',
                        cls: 't-verb',
                        lt: 'l-verb'
                    },
                    {
                        label: 'Sentence',
                        words: 'Each token carries a class and a value',
                        cls: 't-article',
                        lt: 'l-article'
                    }
                ],
                analogy: 'This sentence describes the token structure from Ch2! Notice how the diagram builds hierarchically — just like a compiler\'s derivation tree.'
            }
        ];

        function diagramSentence() {
            const idx = parseInt(document.getElementById('sent-sel').value);
            const data = SENTENCES[idx];
            const wordsDiv = document.getElementById('diag-words');
            const phrasesDiv = document.getElementById('diag-phrases');
            wordsDiv.innerHTML = '';
            phrasesDiv.innerHTML = '';
            data.words.forEach(w => {
                const el = document.createElement('div');
                el.className = 'diag-word';
                el.innerHTML = `<span class="dw-word ${w.cls}">${w.w}</span><span class="dw-type ${w.lt}">${w.t}</span>`;
                wordsDiv.appendChild(el);
            });
            data.phrases.forEach(p => {
                const el = document.createElement('div');
                el.className = `diag-phrase`;
                el.style.borderColor = getComputedStyle(document.documentElement).getPropertyValue('--purple');
                el.style.borderColor = p.cls.includes('verb') ? '#EF4444' : p.cls.includes('np') ? '#A855F7' : p.cls.includes('pp') ? '#F97316' : '#6366F1';
                el.style.background = p.cls.includes('verb') ? '#FFF1F2' : p.cls.includes('np') ? '#F3E8FF' : p.cls.includes('pp') ? '#FFF7ED' : '#EEF2FF';
                el.innerHTML = `<span class="dp-label" style="color:${p.cls.includes('verb')?'#EF4444':p.cls.includes('np')?'#A855F7':p.cls.includes('pp')?'#F97316':'var(--purple)'};">${p.label}</span><span style="font-size:13px;color:var(--text);">${p.words}</span>`;
                phrasesDiv.appendChild(el);
            });
            document.getElementById('diag-analogy').textContent = data.analogy;
            document.getElementById('diagram-out').style.display = 'block';
        }

        diagramSentence();

        // ════════════════════════════════════════════════════════
        // PARSE DIRECTION RACE
        // ════════════════════════════════════════════════════════
        const RACE_DATA = {
            vpv: {
                expr: 'var + var * var',
                td: ['Start: Expr', '→ Expr + Term (Rule 1)', '→ Term + Term', '→ Factor + Term', '→ var + Term (match var)', '→ var + Term * Factor (Rule 3)', '→ var + Factor * Factor', '→ var + var * Factor (match var)', '→ var + var * var (match var)', '✓ Derivation tree complete'],
                bu: ['Input: var + var * var', 'shift var → Factor', 'Factor → Term (Reduce Rule 4)', 'shift + ', 'shift var → Factor', 'Factor → Term (Reduce Rule 4)', 'shift * ', 'shift var → Factor', 'Term * Factor → Term (Reduce Rule 3)', 'Expr + Term → Expr (Reduce Rule 1)', '✓ Derivation tree complete']
            },
            vv: {
                expr: 'var * var',
                td: ['Start: Expr', '→ Term (Rule 2)', '→ Term * Factor (Rule 3)', '→ Factor * Factor', '→ var * Factor (match var)', '→ var * var (match var)', '✓ Derivation tree complete'],
                bu: ['Input: var * var', 'shift var → Factor', 'Factor → Term (Reduce Rule 4)', 'shift *', 'shift var → Factor', 'Term * Factor → Term (Reduce Rule 3)', 'Term → Expr (Reduce Rule 2)', '✓ Derivation tree complete']
            },
            pvpv: {
                expr: '(var + var) * var',
                td: ['Start: Expr', '→ Term (Rule 2)', '→ Term * Factor (Rule 3)', '→ Factor * Factor', '→ (Expr) * Factor (Rule 5)', '→ (Expr + Term) * Factor', '→ (Term + Term) * Factor', '→ (Factor + Factor) * Factor', '→ (var + var) * Factor', '→ (var + var) * var', '✓ Derivation tree complete'],
                bu: ['Input: (var + var) * var', 'shift (', 'shift var → Factor → Term', 'shift +', 'shift var → Factor → Term', 'Expr + Term → Expr', '(Expr) → Factor (Reduce Rule 5)', 'shift *', 'shift var → Factor', 'Term * Factor → Term', 'Term → Expr (Reduce Rule 2)', '✓ Derivation tree complete']
            }
        };

        let racePos = 0,
            raceCurrent = null;

        function raceReset() {
            const key = document.getElementById('race-sel').value;
            raceCurrent = RACE_DATA[key];
            racePos = 0;
            document.getElementById('race-expr').textContent = raceCurrent.expr;
            document.getElementById('td-steps').innerHTML = '';
            document.getElementById('bu-steps').innerHTML = '';
            document.getElementById('race-result').style.display = 'none';
            document.getElementById('race-step-btn').disabled = false;
        }

        function raceStep() {
            if (!raceCurrent) {
                raceReset();
            }
            const maxSteps = Math.max(raceCurrent.td.length, raceCurrent.bu.length);
            if (racePos >= maxSteps) {
                showRaceResult();
                return;
            }
            if (racePos < raceCurrent.td.length) {
                const el = document.createElement('div');
                el.className = 'race-step td';
                el.textContent = raceCurrent.td[racePos];
                document.getElementById('td-steps').appendChild(el);
                requestAnimationFrame(() => el.classList.add('show'));
            }
            if (racePos < raceCurrent.bu.length) {
                const el = document.createElement('div');
                el.className = 'race-step bu';
                el.textContent = raceCurrent.bu[racePos];
                document.getElementById('bu-steps').appendChild(el);
                requestAnimationFrame(() => el.classList.add('show'));
            }
            racePos++;
            if (racePos >= maxSteps) showRaceResult();
        }

        function raceRunAll() {
            if (!raceCurrent) raceReset();
            const maxSteps = Math.max(raceCurrent.td.length, raceCurrent.bu.length);
            while (racePos < maxSteps) raceStep();
        }

        function showRaceResult() {
            document.getElementById('race-step-btn').disabled = true;
            const res = document.getElementById('race-result');
            res.style.display = 'block';
            res.innerHTML = `✅ Both parsing directions accept <strong style="font-family:'JetBrains Mono',monospace;">${raceCurrent.expr}</strong> and produce the same derivation tree — just built in opposite directions.`;
        }

        raceReset();

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