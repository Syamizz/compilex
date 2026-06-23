<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CompileX - Learn Compiler Principles</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

  
    <link rel="stylesheet" href="body.css">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">


    <!-- font -->

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

        S body {
            font-family: 'Syne', sans-serif;
            background: var(--color-background-tertiary);
            margin: 0;
        }


        .login-btn {
            background: #A5B4FC;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
        }

        .login-btn:hover {
            background: #818CF8;
        }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 80px 20px;
        }

        .hero h1 {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .hero p {
            max-width: 700px;
            margin: auto;
            font-size: 18px;
            color: #6B7280;
        }

        /* Section */
        .section {
            max-width: 1000px;
            margin: auto;
            padding: 60px 20px;
        }

        .section h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1F2937;
        }

        .topics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            background: #FFFFFF;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .card h3 {
            margin-top: 0;
            color: #6366F1;
        }

        /* CTA */
        .cta {
            text-align: center;
            padding: 60px 20px;
        }

        .cta button {
            background: #6366F1;
            color: white;
            border: none;
            padding: 14px 28px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        .cta button:hover {
            background: #4F46E5;
        }

        footer {
            text-align: center;
            padding: 20px;
            background: #FFFFFF;
            margin-top: 40px;
            font-size: 14px;
            color: #6B7280;
        }

        



        


        .fade {
            animation: fadeEffect 1s;
        }

        @keyframes fadeEffect {
            from {
                opacity: .4
            }

            to {
                opacity: 1
            }
        }

        .progress-card {
            padding: 25px;
        }

        .chapter-title {
            font-size: 18px;
            margin-bottom: 15px;
            color: #6366F1;
        }

        .big-progress {
            height: 28px;
            /* makes the bar larger */
            font-size: 14px;
            font-weight: bold;
        }



        /* Make the card slightly lift or change color when hovered */
        .progress-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }

        .progress-card:hover {
            transform: translateY(-3px);
            /* Subtle lift */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            /* Soft shadow */
            border-color: #6366f1;
            /* Highlight the border */
        }


        /* CSS */
        .button-4 {
            appearance: none;
            background-color: #FAFBFC;
            border: 1px solid rgba(27, 31, 35, 0.15);
            border-radius: 6px;
            box-shadow: rgba(27, 31, 35, 0.04) 0 1px 0, rgba(255, 255, 255, 0.25) 0 1px 0 inset;
            box-sizing: border-box;
            color: #24292E;
            cursor: pointer;
            display: inline-block;
            font-family: -apple-system, system-ui, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji";
            font-size: 14px;
            font-weight: 500;
            line-height: 20px;
            list-style: none;
            padding: 6px 16px;
            position: relative;
            transition: background-color 0.2s cubic-bezier(0.3, 0, 0.5, 1);
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            vertical-align: middle;
            white-space: nowrap;
            word-wrap: break-word;
        }

        .button-4:hover {
            background-color: #F3F4F6;
            text-decoration: none;
            transition-duration: 0.1s;
        }

        .button-4:disabled {
            background-color: #FAFBFC;
            border-color: rgba(27, 31, 35, 0.15);
            color: #959DA5;
            cursor: default;
        }

        .button-4:active {
            background-color: #EDEFF2;
            box-shadow: rgba(225, 228, 232, 0.2) 0 1px 0 inset;
            transition: none 0s;
        }

        .button-4:focus {
            outline: 1px transparent;
        }

        .button-4:before {
            display: none;
        }

        .button-4:-webkit-details-marker {
            display: none;
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


        /* Interactive BST visualiser */
        .vis-wrap {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin: 24px 0;
        }

        .vis-header {
            background: linear-gradient(135deg, #6366F1, #818CF8);
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .vis-header h4 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: white;
            margin: 0;
        }

        .vis-body {
            padding: 20px;
        }

        .vis-controls {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 16px;
            align-items: center;
        }

        .vis-input {
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

        .vis-input:focus {
            border-color: var(--purple);
        }

        .vis-btn {
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

        .vis-btn:hover {
            background: var(--purple-d);
        }

        .vis-btn.sec {
            background: transparent;
            border: 1.5px solid rgba(99, 102, 241, .3);
            color: var(--purple);
        }

        .vis-btn.sec:hover {
            background: var(--purple-s);
        }

        .vis-canvas {
            width: 100%;
            min-height: 220px;
            position: relative;
            overflow-x: auto;
        }

        .vis-svg {
            display: block;
        }

        .vis-log {
            margin-top: 12px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--muted);
            background: var(--code-bg);
            padding: 10px 14px;
            border-radius: 8px;
            min-height: 28px;
        }

        .preset-btns {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .preset-btn {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 6px;
            border: 1px solid rgba(99, 102, 241, .3);
            background: transparent;
            color: var(--purple);
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
        }

        .preset-btn:hover {
            background: var(--purple-s);
        }

        /* Interactive BST visualiser */

        /* Hash table visualiser */
        .hash-wrap {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin: 24px 0;
        }

        .hash-header {
            background: linear-gradient(135deg, #10B981, #34D399);
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .hash-header h4 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: white;
            margin: 0;
        }

        .hash-body {
            padding: 20px;
        }

        .hash-controls {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 16px;
            align-items: center;
        }

        .hash-input {
            font-family: 'JetBrains Mono', monospace;
            font-size: 14px;
            padding: 8px 14px;
            border: 1.5px solid rgba(16, 185, 129, .3);
            border-radius: 8px;
            outline: none;
            width: 180px;
            color: var(--text);
            background: var(--bg);
        }

        .hash-input:focus {
            border-color: var(--green);
        }

        .hash-btn {
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

        .hash-btn:hover {
            background: #059669;
        }

        .hash-btn.sec {
            background: transparent;
            border: 1.5px solid rgba(16, 185, 129, .3);
            color: var(--green);
        }

        .hash-btn.sec:hover {
            background: #F0FDF4;
        }

        .hash-table-vis {
            display: flex;
            gap: 4px;
            flex-direction: column;
        }

        .hash-slot {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .hash-idx {
            width: 32px;
            height: 32px;
            background: var(--code-bg);
            color: #A5B4FC;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            flex-shrink: 0;
        }

        .hash-chain {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
        }

        .hash-node {
            background: var(--purple-s);
            border: 1.5px solid rgba(99, 102, 241, .3);
            color: var(--purple);
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all .3s;
        }

        .hash-node.new {
            background: #FEF3C7;
            border-color: var(--amber);
            color: #92400E;
            animation: pop .35s ease;
        }

        .hash-null {
            color: rgba(99, 102, 241, .3);
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
        }

        .hash-arrow {
            color: rgba(99, 102, 241, .4);
            font-size: 14px;
        }

        .hash-formula {
            background: var(--code-bg);
            border-radius: 8px;
            padding: 10px 16px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: #CDD6F4;
            margin-top: 12px;
        }

        .hash-formula .hf-hi {
            color: #FAB387;
        }

        .hash-log {
            margin-top: 12px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--muted);
            background: var(--code-bg);
            padding: 10px 14px;
            border-radius: 8px;
            min-height: 28px;
        }

        /* Hash table visualiser */



        /* Interactive scanner */
        .scanner-wrap {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin: 24px 0;
        }

        .scanner-header {
            background: linear-gradient(135deg, #6366F1, #818CF8);
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .scanner-header h4 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: white;
            margin: 0;
        }

        .scanner-body {
            padding: 20px;
        }

        .scanner-textarea {
            width: 100%;
            min-height: 90px;
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

        .scanner-textarea:focus {
            border-color: var(--purple);
        }

        .scanner-btn {
            margin-top: 10px;
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

        .scanner-btn:hover {
            background: var(--purple-d);
        }

        .scanner-out {
            margin-top: 16px;
        }

        .scanner-stream {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 10px;
        }

        .sc-chip {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            padding: 5px 12px;
            border-radius: 10px;
            border: 1.5px solid;
            font-family: 'JetBrains Mono', monospace;
            cursor: default;
            transition: transform .15s;
        }

        .sc-chip:hover {
            transform: translateY(-2px);
        }

        .sc-chip .sc-type {
            font-size: 9px;
            opacity: .7;
            margin-bottom: 1px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .sc-chip .sc-val {
            font-size: 12px;
            font-weight: 700;
        }

        .sc-kw {
            background: #FEF3C7;
            border-color: #F59E0B;
            color: #92400E;
        }

        .sc-id {
            background: #EFF6FF;
            border-color: #3B82F6;
            color: #1D4ED8;
        }

        .sc-num {
            background: #F0FDF4;
            border-color: #10B981;
            color: #065F46;
        }

        .sc-op {
            background: #FFF1F2;
            border-color: #EF4444;
            color: #9F1239;
        }

        .sc-sp {
            background: #F1F5F9;
            border-color: #CBD5E1;
            color: #475569;
        }

        .sc-cm {
            background: #F8FAFC;
            border-color: #94A3B8;
            color: #64748B;
            font-style: italic;
        }

        .sc-misc {
            background: #FFF7ED;
            border-color: #FB923C;
            color: #9A3412;
        }

        .sc-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
            font-size: 13px;
        }

        .sc-table th {
            background: var(--code-bg);
            color: #A5B4FC;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            padding: 8px 12px;
            text-align: left;
            font-size: 11px;
        }

        .sc-table td {
            padding: 7px 12px;
            border-bottom: 1px solid #EEF2FF;
        }

        .sc-table td:first-child {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
        }

        .preset-btns {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .preset-btn {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 6px;
            border: 1px solid rgba(99, 102, 241, .3);
            background: transparent;
            color: var(--purple);
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
        }

        .preset-btn:hover {
            background: var(--purple-s);
        }

        /* Interactive scanner */
    </style>

</head>

<body>

    



    <!-- ══════════════════════════════════════════════ -->
    <!-- Chapter 2 Tools Card                           -->
    <!-- ══════════════════════════════════════════════ -->
    <div style="max-width: 700px; margin: 40px auto; padding: 0 20px;">

        <!-- Card -->
        <div style="background: #FFFFFF; border-radius: 16px; box-shadow: 0 4px 24px rgba(99,102,241,.10); border: 1px solid rgba(99,102,241,.15); overflow: hidden;">

            <!-- Card header -->
            <div style="background: #18181B; padding: 20px 28px;">
                <h2 style="font-size: 22px; font-weight: 700; color: #FFFFFF; margin: 0 0 6px;">CHAPTER 2</h2>
                <p style="font-size: 12px; font-weight: 600; letter-spacing: .1em; color: #A5B4FC; margin: 0 0 6px;">SYMBOL TABLES &amp; LEXICAL ANALYSIS</p>
                
            </div>

            <!-- Tool buttons -->
            <div style="padding: 24px 28px; display: flex; flex-direction: column; gap: 14px;">

                <!-- Java Tokeniser -->
                <button onclick="document.getElementById('tokModal').style.display='flex'"
                    style="display: flex; align-items: center; gap: 16px; background: #F8F7FF; border: 1px solid rgba(99,102,241,.2); border-radius: 12px; padding: 16px 20px; cursor: pointer; text-align: left; width: 100%; transition: all .2s;"
                    onmouseover="this.style.borderColor='#6366F1'; this.style.background='#EEF2FF';"
                    onmouseout="this.style.borderColor='rgba(99,102,241,.2)'; this.style.background='#F8F7FF';">
                    <div style="width: 44px; height: 44px; border-radius: 10px; background: #EEF2FF; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">🔤</div>
                    <div style="flex: 1;">
                        <div style="font-family: 'Syne', sans-serif; font-weight: 700; font-size: 15px; color: #1E1B4B;">Java Tokeniser</div>
                        <div style="font-size: 13px; color: #6B7280; margin-top: 2px;">Classify tokens using the 9-class scheme from Section 2.1</div>
                    </div>
                    <div style="font-size: 18px; color: #6366F1; flex-shrink: 0;">▶</div>
                </button>

                <!-- BST Builder -->
                <button onclick="document.getElementById('bstModal').style.display='flex'"
                    style="display: flex; align-items: center; gap: 16px; background: #F8F7FF; border: 1px solid rgba(99,102,241,.2); border-radius: 12px; padding: 16px 20px; cursor: pointer; text-align: left; width: 100%; transition: all .2s;"
                    onmouseover="this.style.borderColor='#6366F1'; this.style.background='#EEF2FF';"
                    onmouseout="this.style.borderColor='rgba(99,102,241,.2)'; this.style.background='#F8F7FF';">
                    <div style="width: 44px; height: 44px; border-radius: 10px; background: #ECFDF5; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">🌳</div>
                    <div style="flex: 1;">
                        <div style="font-family: 'Syne', sans-serif; font-weight: 700; font-size: 15px; color: #1E1B4B;">Binary Search Tree Builder</div>
                        <div style="font-size: 13px; color: #6B7280; margin-top: 2px;">Visualise BST insertion order — balanced vs degenerate (Fig 2.8)</div>
                    </div>
                    <div style="font-size: 18px; color: #6366F1; flex-shrink: 0;">▶</div>
                </button>

                <!-- Hash Table -->
                <button onclick="document.getElementById('hashModal').style.display='flex'"
                    style="display: flex; align-items: center; gap: 16px; background: #F8F7FF; border: 1px solid rgba(99,102,241,.2); border-radius: 12px; padding: 16px 20px; cursor: pointer; text-align: left; width: 100%; transition: all .2s;"
                    onmouseover="this.style.borderColor='#6366F1'; this.style.background='#EEF2FF';"
                    onmouseout="this.style.borderColor='rgba(99,102,241,.2)'; this.style.background='#F8F7FF';">
                    <div style="width: 44px; height: 44px; border-radius: 10px; background: #F0FDF4; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">🔑</div>
                    <div style="flex: 1;">
                        <div style="font-family: 'Syne', sans-serif; font-weight: 700; font-size: 15px; color: #1E1B4B;">Hash Table Visualiser</div>
                        <div style="font-size: 13px; color: #6B7280; margin-top: 2px;">See <code style="background:#F0FDF4; color:#065F46; padding:1px 6px; border-radius:4px; font-size:12px;">(len + ascii(first)) % HASHMAX</code> live — Fig 2.9</div>
                    </div>
                    <div style="font-size: 18px; color: #6366F1; flex-shrink: 0;">▶</div>
                </button>

                <!-- Decaf Scanner -->
                <button onclick="document.getElementById('decafModal').style.display='flex'"
                    style="display: flex; align-items: center; gap: 16px; background: #F8F7FF; border: 1px solid rgba(99,102,241,.2); border-radius: 12px; padding: 16px 20px; cursor: pointer; text-align: left; width: 100%; transition: all .2s;"
                    onmouseover="this.style.borderColor='#6366F1'; this.style.background='#EEF2FF';"
                    onmouseout="this.style.borderColor='rgba(99,102,241,.2)'; this.style.background='#F8F7FF';">
                    <div style="width: 44px; height: 44px; border-radius: 10px; background: #FEF3C7; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">☕</div>
                    <div style="flex: 1;">
                        <div style="font-family: 'Syne', sans-serif; font-weight: 700; font-size: 15px; color: #1E1B4B;">Decaf Scanner</div>
                        <div style="font-size: 13px; color: #6B7280; margin-top: 2px;">Scan Decaf source using <code style="background:#FEF3C7; color:#92400E; padding:1px 6px; border-radius:4px; font-size:12px;">decaf.grammar</code> from Section 2.5</div>
                    </div>
                    <div style="font-size: 18px; color: #6366F1; flex-shrink: 0;">▶</div>
                </button>

            </div>

            <!-- Card footer -->
            <div style="border-top: 1px solid rgba(99,102,241,.1); padding: 14px 28px; background: #F8F7FF; display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 12px; color: #9CA3AF;">🔗 All tools are based on examples from your compiler principles textbook.</span>
            </div>

        </div>
    </div>

    <!-- ══════════════════════════════════════════════ -->
    <!-- Tokeniser Modal                                -->
    <!-- ══════════════════════════════════════════════ -->
    <div id="tokModal" onclick="if(event.target===this)this.style.display='none'"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:9999; align-items:center; justify-content:center; padding:20px;">
        <div style="background:#F8F7FF; border-radius:16px; width:100%; max-width:820px; max-height:90vh; overflow-y:auto; position:relative;">

            <!-- Modal header -->
            <div style="background:#18181B; padding:16px 24px; border-radius:16px 16px 0 0; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <span style="font-family:'Syne',sans-serif; font-weight:700; font-size:17px; color:#fff;">🔤 Interactive Java Tokeniser</span>
                    <p style="font-size:12px; color:#A1A1AA; margin:4px 0 0;">Compiler Principles — Lexical Analysis Tool</p>
                </div>
                <button onclick="document.getElementById('tokModal').style.display='none'"
                    style="background:transparent; border:1px solid #3F3F46; color:#A1A1AA; border-radius:8px; padding:6px 14px; cursor:pointer; font-size:13px; font-family:'Syne',sans-serif;">
                    ✕ Close
                </button>
            </div>

            <!-- Modal body — original tokeniser content unchanged -->
            <div style="padding:28px;">
                <p style="font-size:14px; color:#6B7280; margin-bottom:20px; line-height:1.7;">
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
            </div>
        </div>
    </div>


    <br><br>



    <!-- ══════════════════════════════════════════════ -->
    <!-- BST Modal                                      -->
    <!-- ══════════════════════════════════════════════ -->
    <div id="bstModal" onclick="if(event.target===this)this.style.display='none'"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:9999; align-items:center; justify-content:center; padding:20px;">
        <div style="background:#F8F7FF; border-radius:16px; width:100%; max-width:820px; max-height:90vh; overflow-y:auto; position:relative;">

            <!-- Modal header -->
            <div style="background:#18181B; padding:16px 24px; border-radius:16px 16px 0 0; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <span style="font-family:'Syne',sans-serif; font-weight:700; font-size:17px; color:#fff;">🌳 Interactive BST Builder</span>
                    <p style="font-size:12px; color:#A1A1AA; margin:4px 0 0;">Compiler Principles — Symbol Table Visualiser</p>
                </div>
                <button onclick="document.getElementById('bstModal').style.display='none'"
                    style="background:transparent; border:1px solid #3F3F46; color:#A1A1AA; border-radius:8px; padding:6px 14px; cursor:pointer; font-size:13px; font-family:'Syne',sans-serif;">
                    ✕ Close
                </button>
            </div>

            <!-- Modal body — original BST content unchanged -->
            <div style="padding:28px;">
                <p style="font-size:14px; color:#6B7280; margin-bottom:20px; line-height:1.7;">
                    Type a word and click <strong>Insert</strong> to add it to the BST. Watch how the tree grows
                    and note how insertion order affects its shape. Use the preset examples from the textbook.
                </p>

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
            </div>
        </div>
    </div>





    <!-- ══════════════════════════════════════════════ -->
    <!-- Hash Table Modal                               -->
    <!-- ══════════════════════════════════════════════ -->
    <div id="hashModal" onclick="if(event.target===this)this.style.display='none'"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:9999; align-items:center; justify-content:center; padding:20px;">
        <div style="background:#F8F7FF; border-radius:16px; width:100%; max-width:820px; max-height:90vh; overflow-y:auto; position:relative;">

            <!-- Modal header -->
            <div style="background:#18181B; padding:16px 24px; border-radius:16px 16px 0 0; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <span style="font-family:'Syne',sans-serif; font-weight:700; font-size:17px; color:#fff;">🔑 Interactive Hash Table Visualiser</span>
                    <p style="font-size:12px; color:#A1A1AA; margin:4px 0 0;">Compiler Principles — Symbol Table Hashing</p>
                </div>
                <button onclick="document.getElementById('hashModal').style.display='none'"
                    style="background:transparent; border:1px solid #3F3F46; color:#A1A1AA; border-radius:8px; padding:6px 14px; cursor:pointer; font-size:13px; font-family:'Syne',sans-serif;">
                    ✕ Close
                </button>
            </div>

            <!-- Modal body — original hash table content unchanged -->
            <div style="padding:28px;">
                <p style="font-size:14px; color:#6B7280; margin-bottom:20px; line-height:1.7;">
                    Type a word and click <strong>Insert</strong> to add it. The hash function
                    <code>(len + ascii(first)) % HASHMAX</code> is applied live, and the word is placed in
                    the correct bucket. Use the preset to reproduce Figure 2.9 from the textbook.
                </p>

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
            </div>
        </div>
    </div>

    <!-- Decaf Scanner Modal                            -->
    <!-- ══════════════════════════════════════════════ -->
    <div id="decafModal" onclick="if(event.target===this)this.style.display='none'"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:9999; align-items:center; justify-content:center; padding:20px;">
        <div style="background:#F8F7FF; border-radius:16px; width:100%; max-width:820px; max-height:90vh; overflow-y:auto; position:relative;">

            <!-- Modal header -->
            <div style="background:#18181B; padding:16px 24px; border-radius:16px 16px 0 0; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <span style="font-family:'Syne',sans-serif; font-weight:700; font-size:17px; color:#fff;">☕ Interactive Decaf Scanner</span>
                    <p style="font-size:12px; color:#A1A1AA; margin:4px 0 0;">Compiler Principles — Decaf Lexical Analysis</p>
                </div>
                <button onclick="document.getElementById('decafModal').style.display='none'"
                    style="background:transparent; border:1px solid #3F3F46; color:#A1A1AA; border-radius:8px; padding:6px 14px; cursor:pointer; font-size:13px; font-family:'Syne',sans-serif;">
                    ✕ Close
                </button>
            </div>

            <!-- Modal body — original Decaf scanner content unchanged -->
            <div style="padding:28px;">
                <p style="font-size:14px; color:#6B7280; margin-bottom:20px; line-height:1.7;">
                    Type (or paste) a Decaf source snippet. The scanner will classify every token according to
                    the <code>decaf.grammar</code> token definitions from Section 2.5. Comments and spaces are
                    marked but shown dimmed (they would be <em>ignored</em> by the parser).
                </p>

                <div class="scanner-wrap">
                    <div class="scanner-header">
                        <h4>☕ Decaf Lexical Scanner</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);">Based on decaf.grammar</span>
                    </div>
                    <div class="scanner-body">
                        <textarea class="scanner-textarea" id="scan-input" spellcheck="false">float cos, x;
        x = 3.14159;
        while (x > 0.0001) {
        x = x * 2.0E-1;
        } // end while</textarea>
                        <button class="scanner-btn" onclick="runDecafScanner()">🔍 Scan</button>
                        <div class="preset-btns">
                            <button class="preset-btn" onclick="setScanPreset(0)">Cosine snippet</button>
                            <button class="preset-btn" onclick="setScanPreset(1)">Comments test</button>
                            <button class="preset-btn" onclick="setScanPreset(2)">Keywords + ops</button>
                            <button class="preset-btn" onclick="setScanPreset(3)">Numbers test</button>
                        </div>

                        <div class="scanner-out" id="scan-out" style="display:none;">
                            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--text);margin-bottom:8px;">Token stream (comments &amp; spaces dimmed):</div>
                            <div class="scanner-stream" id="scan-chips"></div>
                            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--text);margin:16px 0 8px;">Token table:</div>
                            <table class="sc-table" id="scan-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Token class</th>
                                        <th>Lexeme</th>
                                        <th>Passed to parser?</th>
                                    </tr>
                                </thead>
                                <tbody id="scan-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <script>
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
    </script>

    <script>
        // ════════════════════════════════════════════════════════
        // BST Visualiser
        // ════════════════════════════════════════════════════════
        let bstRoot = null;
        let bstCount = 0;

        class BSTNode {
            constructor(val) {
                this.val = val;
                this.left = null;
                this.right = null;
            }
        }

        function bstInsert() {
            const w = document.getElementById('bst-input').value.trim().toLowerCase();
            if (!w) return;
            document.getElementById('bst-input').value = '';
            const log = document.getElementById('bst-log');
            const result = bstInsertNode(w);
            log.textContent = result.msg;
            bstCount = bstCountNodes(bstRoot);
            document.getElementById('bst-stats').textContent = bstCount + ' node' + (bstCount === 1 ? '' : 's');
            bstDraw();
        }

        function bstInsertNode(w) {
            if (!bstRoot) {
                bstRoot = new BSTNode(w);
                return {
                    msg: `"${w}" inserted as root.`
                };
            }
            let cur = bstRoot,
                path = [];
            while (cur) {
                path.push(cur.val);
                if (w < cur.val) {
                    if (!cur.left) {
                        cur.left = new BSTNode(w);
                        return {
                            msg: `"${w}" < "${cur.val}" → inserted as left child. Path: ${path.join(' → ')}`
                        };
                    }
                    cur = cur.left;
                } else if (w > cur.val) {
                    if (!cur.right) {
                        cur.right = new BSTNode(w);
                        return {
                            msg: `"${w}" > "${cur.val}" → inserted as right child. Path: ${path.join(' → ')}`
                        };
                    }
                    cur = cur.right;
                } else return {
                    msg: `"${w}" already in tree (found at: ${path.join(' → ')})`
                };
            }
        }

        function bstCountNodes(n) {
            return n ? 1 + bstCountNodes(n.left) + bstCountNodes(n.right) : 0;
        }

        function bstReset() {
            bstRoot = null;
            bstCount = 0;
            document.getElementById('bst-stats').textContent = '0 nodes';
            document.getElementById('bst-log').textContent = 'Insert a word to start building the tree.';
            bstDraw();
        }

        function bstPreset(words) {
            bstReset();
            words.forEach(w => {
                bstInsertNode(w);
            });
            bstCount = bstCountNodes(bstRoot);
            document.getElementById('bst-stats').textContent = bstCount + ' nodes';
            document.getElementById('bst-log').textContent = 'Preset loaded: ' + words.join(', ');
            bstDraw();
        }

        function bstDraw() {
            const svg = document.getElementById('bst-svg');
            svg.innerHTML = '';
            if (!bstRoot) return;
            const W = 700,
                H = 240;
            svg.setAttribute('width', W);
            svg.setAttribute('height', H);
            const depth = bstDepth(bstRoot);
            const levelH = Math.min(H / (depth + 1), 60);

            function pos(node, level, lo, hi) {
                if (!node) return;
                const x = (lo + hi) / 2,
                    y = level * levelH + 30;
                node._x = x;
                node._y = y;
                pos(node.left, level + 1, lo, (lo + hi) / 2);
                pos(node.right, level + 1, (lo + hi) / 2, hi);
            }
            pos(bstRoot, 0, 0, W);

            function drawEdges(node) {
                if (!node) return;
                if (node.left) {
                    drawLine(svg, node._x, node._y, node.left._x, node.left._y);
                    drawEdges(node.left);
                }
                if (node.right) {
                    drawLine(svg, node._x, node._y, node.right._x, node.right._y);
                    drawEdges(node.right);
                }
            }

            function drawNodes(node) {
                if (!node) return;
                const isLeaf = !node.left && !node.right;
                const r = 20;
                const c = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                c.setAttribute('cx', node._x);
                c.setAttribute('cy', node._y);
                c.setAttribute('r', r);
                c.setAttribute('fill', isLeaf ? 'rgba(16,185,129,.1)' : 'rgba(99,102,241,.1)');
                c.setAttribute('stroke', isLeaf ? '#10B981' : '#6366F1');
                c.setAttribute('stroke-width', node === bstRoot ? '2.5' : '1.8');
                svg.appendChild(c);
                const t = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                t.setAttribute('x', node._x);
                t.setAttribute('y', node._y + 4);
                t.setAttribute('text-anchor', 'middle');
                t.setAttribute('fill', isLeaf ? '#10B981' : '#818CF8');
                t.setAttribute('font-size', node.val.length > 5 ? '9' : '11');
                t.setAttribute('font-weight', '700');
                t.setAttribute('font-family', 'JetBrains Mono');
                t.textContent = node.val;
                svg.appendChild(t);
                drawNodes(node.left);
                drawNodes(node.right);
            }
            drawEdges(bstRoot);
            drawNodes(bstRoot);
        }

        function drawLine(svg, x1, y1, x2, y2) {
            const l = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            l.setAttribute('x1', x1);
            l.setAttribute('y1', y1);
            l.setAttribute('x2', x2);
            l.setAttribute('y2', y2);
            l.setAttribute('stroke', '#6366F1');
            l.setAttribute('stroke-width', '1.5');
            l.setAttribute('opacity', '.4');
            svg.appendChild(l);
        }

        function bstDepth(n) {
            return n ? 1 + Math.max(bstDepth(n.left), bstDepth(n.right)) : 0;
        }
    </script>

    <script>
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
            hashData = Array.from({
                length: hashMax
            }, () => []);
            hashWordCount = 0;
            renderHashTable();
        }

        function hashResize() {
            const v = parseInt(document.getElementById('hash-max-input').value);
            if (v >= 2 && v <= 20) {
                hashMax = v;
                hashInit();
            }
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
                if (!hashData[idx].includes(w)) {
                    hashData[idx].push(w);
                    hashWordCount++;
                }
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
                            arr.className = 'hash-arrow';
                            arr.textContent = '→';
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
    </script>

    <script>
        // ════════════════════════════════════════════════════════
        // Decaf Scanner
        // ════════════════════════════════════════════════════════
        const DECAF_KEYWORDS = new Set([
            'class', 'public', 'static', 'void', 'main', 'String', 'int', 'float',
            'for', 'while', 'if', 'else'
        ]);
        const COMPARE_OPS = new Set(['==', '<', '>', '<=', '>=', '!=']);
        const SINGLE_OPS = {
            '+': 'plus',
            '-': 'minus',
            '*': 'mult',
            '/': 'div',
            '=': 'assign',
            '(': 'l_par',
            ')': 'r_par',
            '{': 'l_brace',
            '}': 'r_brace',
            '[': 'l_bracket',
            ']': 'r_bracket',
            ',': 'comma',
            ';': 'semi'
        };

        const presets = [
            `float cos, x;\nx = 3.14159;\nwhile (x > 0.0001) {\n  x = x * 2.0E-1;\n} // end while`,
            `/* multi-line\n   comment */\nx = 1; // single line comment\ny = 2;`,
            `class Foo {\n  public static void main(String[] args) {\n    if (a != b) { x = 0; }\n  }\n}`,
            `int n = 42;\nfloat f = 3.14;\nfloat g = .5;\nfloat h = 1.5e10;\nfloat j = 2.0E-3;`
        ];

        function setScanPreset(i) {
            document.getElementById('scan-input').value = presets[i];
            runDecafScanner();
        }

        function decafLex(src) {
            const tokens = [];
            let i = 0;
            while (i < src.length) {
                // newline / whitespace
                if (/[\s]/.test(src[i])) {
                    i++;
                    continue;
                }

                // line comment
                if (src[i] === '/' && src[i + 1] === '/') {
                    let s = i;
                    while (i < src.length && src[i] !== '\n') i++;
                    if (i < src.length) i++; // consume newline
                    tokens.push({
                        cls: 'comment1',
                        val: src.slice(s, i).trim(),
                        ignored: true
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
                        cls: 'comment2',
                        val: src.slice(s, i),
                        ignored: true
                    });
                    continue;
                }
                // string constant (not in Decaf grammar but for safety)
                if (src[i] === '"') {
                    let s = i;
                    i++;
                    while (i < src.length && src[i] !== '"') i++;
                    i++;
                    tokens.push({
                        cls: 'misc',
                        val: src.slice(s, i),
                        ignored: false
                    });
                    continue;
                }
                // number — must start with digit OR dot followed by digit
                if (/[0-9]/.test(src[i]) || (src[i] === '.' && /[0-9]/.test(src[i + 1] || ''))) {
                    let s = i;
                    if (/[0-9]/.test(src[i])) {
                        while (i < src.length && /[0-9]/.test(src[i])) i++;
                        if (i < src.length && src[i] === '.') {
                            i++;
                            while (i < src.length && /[0-9]/.test(src[i])) i++;
                        }
                    } else {
                        i++;
                        while (i < src.length && /[0-9]/.test(src[i])) i++;
                    }
                    if (i < src.length && /[eE]/.test(src[i])) {
                        i++;
                        if (/[+\-]/.test(src[i] || '')) i++;
                        while (i < src.length && /[0-9]/.test(src[i])) i++;
                    }
                    tokens.push({
                        cls: 'number',
                        val: src.slice(s, i),
                        ignored: false
                    });
                    continue;
                }
                // identifier or keyword
                if (/[a-zA-Z_]/.test(src[i])) {
                    let s = i;
                    while (i < src.length && /[a-zA-Z0-9_]/.test(src[i])) i++;
                    const w = src.slice(s, i);
                    const cls = DECAF_KEYWORDS.has(w) ? 'keyword' : 'identifier';
                    tokens.push({
                        cls,
                        val: w,
                        ignored: false
                    });
                    continue;
                }
                // two-char operators
                const two = src.slice(i, i + 2);
                if (COMPARE_OPS.has(two)) {
                    tokens.push({
                        cls: 'compare',
                        val: two,
                        ignored: false
                    });
                    i += 2;
                    continue;
                }
                // single-char
                if (src[i] === '=') {
                    tokens.push({
                        cls: 'assign',
                        val: '=',
                        ignored: false
                    });
                    i++;
                    continue;
                }
                if (SINGLE_OPS[src[i]]) {
                    tokens.push({
                        cls: SINGLE_OPS[src[i]],
                        val: src[i],
                        ignored: false
                    });
                    i++;
                    continue;
                }
                if (COMPARE_OPS.has(src[i])) {
                    tokens.push({
                        cls: 'compare',
                        val: src[i],
                        ignored: false
                    });
                    i++;
                    continue;
                }
                // misc
                tokens.push({
                    cls: 'misc',
                    val: src[i],
                    ignored: false
                });
                i++;
            }
            return tokens;
        }

        const classStyle = {
            keyword: 'sc-kw',
            identifier: 'sc-id',
            number: 'sc-num',
            compare: 'sc-op',
            assign: 'sc-op',
            plus: 'sc-op',
            minus: 'sc-op',
            mult: 'sc-op',
            div: 'sc-op',
            l_par: 'sc-op',
            r_par: 'sc-op',
            l_brace: 'sc-op',
            r_brace: 'sc-op',
            l_bracket: 'sc-op',
            r_bracket: 'sc-op',
            comma: 'sc-op',
            semi: 'sc-op',
            comment1: 'sc-cm',
            comment2: 'sc-cm',
            space: 'sc-sp',
            misc: 'sc-misc'
        };

        function runDecafScanner() {
            const src = document.getElementById('scan-input').value;
            const tokens = decafLex(src);
            const chips = document.getElementById('scan-chips');
            const tbody = document.getElementById('scan-tbody');
            chips.innerHTML = '';
            tbody.innerHTML = '';

            let n = 0;
            tokens.forEach(t => {
                const style = classStyle[t.cls] || 'sc-misc';
                // chip
                const div = document.createElement('div');
                div.className = 'sc-chip ' + style + (t.ignored ? ' sc-cm' : '');
                div.style.opacity = t.ignored ? '.45' : '1';
                div.innerHTML = `<span class="sc-type">${t.cls}</span><span class="sc-val">${escHtml(t.val.length > 14 ? t.val.slice(0,12)+'…' : t.val)}</span>`;
                chips.appendChild(div);

                // table row
                if (!t.ignored) n++;
                const tr = document.createElement('tr');
                tr.style.opacity = t.ignored ? '.45' : '1';
                tr.innerHTML = `
          <td>${t.ignored ? '—' : n}</td>
          <td><span class="sc-chip ${style}" style="padding:3px 10px;font-size:11px;display:inline-flex;gap:4px;"><span class="sc-val">${t.cls}</span></span></td>
          <td style="font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--text);">${escHtml(t.val)}</td>
          <td style="font-size:12px;">${t.ignored ? '<span style="color:var(--muted);">❌ Ignored</span>' : '<span style="color:var(--green);">✅ Yes</span>'}</td>
        `;
                tbody.appendChild(tr);
            });
            document.getElementById('scan-out').style.display = 'block';
        }

        function escHtml(s) {
            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        // run on load
        //runDecafScanner();
    </script>
</body>

</html>