<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chapter 3.0 – CompileX</title>

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

    /* Grammar display */
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
      line-height: 2;
      display: flex;
      gap: 12px;
      align-items: baseline;
    }

    .grammar-box .g-num {
      color: rgba(205, 214, 244, .35);
      font-size: 11px;
      min-width: 20px;
    }

    .grammar-box .g-lhs {
      color: #CBA6F7;
      font-weight: 700;
    }

    .grammar-box .g-arr {
      color: #6C7086;
    }

    .grammar-box .g-rhs {
      color: #CDD6F4;
    }

    .grammar-box .g-rhs .nt {
      color: #CBA6F7;
    }

    .grammar-box .g-rhs .t {
      color: #A6E3A1;
    }

    .grammar-box .g-rhs .ep {
      color: #F38BA8;
      font-style: italic;
    }

    /* Derivation stepper */
    .deriv-wrap {
      background: var(--card);
      border: 1px solid rgba(99, 102, 241, .15);
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      margin: 24px 0;
    }

    .deriv-header {
      background: linear-gradient(135deg, #6366F1, #818CF8);
      padding: 14px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .deriv-header h4 {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 15px;
      color: white;
      margin: 0;
    }

    .deriv-body {
      padding: 20px;
    }

    .deriv-select-row {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-bottom: 16px;
    }

    .deriv-sel {
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      padding: 8px 12px;
      border: 1.5px solid rgba(99, 102, 241, .3);
      border-radius: 8px;
      outline: none;
      color: var(--text);
      background: var(--bg);
      cursor: pointer;
    }

    .deriv-sel:focus {
      border-color: var(--purple);
    }

    .deriv-btn {
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

    .deriv-btn:hover {
      background: var(--purple-d);
    }

    .deriv-btn.sec {
      background: transparent;
      border: 1.5px solid rgba(99, 102, 241, .3);
      color: var(--purple);
    }

    .deriv-btn.sec:hover {
      background: var(--purple-s);
    }

    .deriv-steps {
      display: flex;
      flex-direction: column;
      gap: 6px;
      margin-top: 12px;
    }

    .deriv-step {
      display: flex;
      align-items: baseline;
      gap: 12px;
      padding: 8px 14px;
      border-radius: 10px;
      font-family: 'JetBrains Mono', monospace;
      font-size: 13px;
      opacity: 0;
      transform: translateY(10px);
      transition: opacity .35s ease, transform .35s ease;
    }

    .deriv-step.visible {
      opacity: 1;
      transform: translateY(0);
    }

    .deriv-step.start {
      background: rgba(99, 102, 241, .08);
    }

    .deriv-step.middle {
      background: rgba(245, 158, 11, .06);
    }

    .deriv-step.final {
      background: rgba(16, 185, 129, .1);
    }

    .ds-label {
      font-size: 10px;
      color: rgba(99, 102, 241, .6);
      min-width: 28px;
      font-family: 'DM Sans', sans-serif;
      font-weight: 700;
      text-transform: uppercase;
    }

    .ds-str .nt {
      color: var(--purple);
      font-weight: 700;
    }

    .ds-str .t {
      color: var(--green);
    }

    .ds-str .ep {
      color: var(--amber);
      font-style: italic;
    }

    .ds-rule {
      font-size: 11px;
      color: var(--muted);
      margin-left: 8px;
      font-family: 'DM Sans', sans-serif;
    }

    .deriv-result {
      margin-top: 12px;
      padding: 10px 16px;
      border-radius: 10px;
      font-size: 14px;
      font-family: 'JetBrains Mono', monospace;
    }

    .deriv-result.show {
      display: block;
    }

    /* Chomsky hierarchy */
    .chomsky-grid {
      display: flex;
      flex-direction: column;
      gap: 0;
      margin: 24px 0;
    }

    .ch-row {
      display: flex;
      align-items: stretch;
      gap: 0;
    }

    .ch-num {
      width: 48px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Syne', sans-serif;
      font-weight: 800;
      font-size: 22px;
      flex-shrink: 0;
      border-radius: 10px 0 0 10px;
    }

    .ch-body {
      flex: 1;
      padding: 14px 18px;
      border-radius: 0 10px 10px 0;
      margin: 4px 0;
    }

    .ch-name {
      font-family: 'Syne', sans-serif;
      font-weight: 800;
      font-size: 14px;
      margin-bottom: 4px;
    }

    .ch-rule {
      font-family: 'JetBrains Mono', monospace;
      font-size: 12.5px;
      margin-bottom: 6px;
    }

    .ch-desc {
      font-size: 13px;
      color: var(--muted);
    }

    .ch-ex {
      font-family: 'JetBrains Mono', monospace;
      font-size: 12px;
      margin-top: 4px;
    }

    .c0 {
      background: rgba(239, 68, 68, .08);
    }

    .c0 .ch-num {
      background: #FFF1F2;
      color: var(--red);
    }

    .c1 {
      background: rgba(245, 158, 11, .08);
    }

    .c1 .ch-num {
      background: #FFFBEB;
      color: var(--amber);
    }

    .c2 {
      background: rgba(99, 102, 241, .08);
    }

    .c2 .ch-num {
      background: var(--purple-s);
      color: var(--purple);
    }

    .c3 {
      background: rgba(16, 185, 129, .08);
    }

    .c3 .ch-num {
      background: #F0FDF4;
      color: var(--green);
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

    .chip-0 {
      background: #FFF1F2;
      border-color: #EF4444;
      color: #9F1239;
    }

    .chip-1 {
      background: #FFFBEB;
      border-color: #F59E0B;
      color: #92400E;
    }

    .chip-2 {
      background: var(--purple-s);
      border-color: var(--purple);
      color: var(--purple-d);
    }

    .chip-3 {
      background: #F0FDF4;
      border-color: #10B981;
      color: #065F46;
    }

    .chip-q {
      background: #F3E8FF;
      border-color: #A855F7;
      color: #6B21A8;
    }

    .dnd-zones {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      margin-bottom: 14px;
    }

    .dnd-zone-wrap {
      flex: 1;
      min-width: 140px;
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

    /* Grammar sandbox */
    .sandbox-wrap {
      background: var(--card);
      border: 1px solid rgba(99, 102, 241, .15);
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      margin: 24px 0;
    }

    .sandbox-header {
      background: linear-gradient(135deg, #10B981, #34D399);
      padding: 14px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .sandbox-header h4 {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 15px;
      color: white;
      margin: 0;
    }

    .sandbox-body {
      padding: 20px;
    }

    .sb-controls {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-bottom: 14px;
      align-items: center;
    }

    .sb-input {
      font-family: 'JetBrains Mono', monospace;
      font-size: 14px;
      padding: 8px 14px;
      border: 1.5px solid rgba(16, 185, 129, .3);
      border-radius: 8px;
      outline: none;
      flex: 1;
      min-width: 160px;
      color: var(--text);
      background: var(--bg);
    }

    .sb-input:focus {
      border-color: var(--green);
    }

    .sb-btn {
      padding: 8px 18px;
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

    .sb-btn:hover {
      background: #059669;
    }

    .sb-btn.sec {
      background: transparent;
      border: 1.5px solid rgba(16, 185, 129, .3);
      color: var(--green);
    }

    .sb-btn.sec:hover {
      background: #F0FDF4;
    }

    .sb-grammar-select {
      padding: 8px 12px;
      border-radius: 8px;
      border: 1.5px solid rgba(16, 185, 129, .3);
      font-family: 'DM Sans', sans-serif;
      font-size: 13px;
      color: var(--text);
      background: var(--bg);
      outline: none;
      cursor: pointer;
    }

    .sb-result {
      margin-top: 14px;
      padding: 14px 18px;
      border-radius: 10px;
      font-size: 14px;
    }

    .sb-result.accepted {
      background: #F0FDF4;
      color: #065F46;
      border: 1.5px solid var(--green);
    }

    .sb-result.rejected {
      background: #FFF1F2;
      color: #9F1239;
      border: 1.5px solid var(--red);
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

      .ch-row {
        flex-direction: column;
      }

      .ch-num {
        width: 100%;
        border-radius: 10px 10px 0 0;
        padding: 8px;
        text-align: center;
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
        <li><a href="#ch3-intro" class="toc-link">Chapter 3 Intro</a></li>
        <li><a href="#overview" class="toc-link">3.0 Overview</a></li>
        <li><a href="#grammars" class="toc-link">3.0.1 Grammars</a></li>
        <li><a href="#g1" class="toc-link sub">↳ Grammar G1 — Palindromes</a></li>
        <li><a href="#g2" class="toc-link sub">↳ Grammar G2 — aⁿbⁿ</a></li>
        <li><a href="#sp-a" class="toc-link sub">↳ Sample Problem (a)</a></li>
        <li><a href="#deriv-sim" class="toc-link sub">↳ Derivation Stepper</a></li>
        <li><a href="#classes" class="toc-link">3.0.2 Classes of Grammars</a></li>
        <li><a href="#chomsky" class="toc-link sub">↳ Chomsky Hierarchy</a></li>
        <li><a href="#g3" class="toc-link sub">↳ Grammar G3 — aⁿbⁿcⁿ</a></li>
        <li><a href="#sp-b" class="toc-link sub">↳ Sample Problem (b)</a></li>
        <li><a href="#sandbox" class="toc-link">🧪 Grammar Tester</a></li>
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
        <div class="chapter-tag">📘 Chapter 3 · Section 3.0</div>
        <h1>Grammars, Languages &amp; Grammar Classes</h1>
        <div class="metadata">
          <span>⏱ 20 min read</span>
          <span>🎯 Intermediate</span>
          <span>📐 Formal Languages</span>
        </div>
      </header>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Chapter 3 Intro                               -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="ch3-intro">
        <h2><span class="sec-icon">📖</span> Chapter 3 — Syntax Analysis</h2>
        <p>
          The <strong>second phase</strong> of a compiler is called <strong>syntax analysis</strong>.
          Its input is the stream of tokens produced by lexical analysis. These tokens are checked
          for <strong>proper syntax</strong> — ensuring statements and expressions are correctly formed.
        </p>
        <p>Examples of syntax errors in Java:</p>
        <div style="background:var(--code-bg);border-radius:var(--radius);padding:16px 22px;margin:14px 0;font-family:'JetBrains Mono',monospace;font-size:13px;line-height:2;color:#CDD6F4;">
          <span style="color:#F38BA8;">x = (2+3) * 9);</span> &nbsp;<span style="color:#6C7086;font-style:italic;">// mismatched parentheses</span><br>
          <span style="color:#F38BA8;">if x>y x = 2;</span> &nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#6C7086;font-style:italic;">// missing parentheses</span><br>
          <span style="color:#F38BA8;">while (x==3) do f1();</span> <span style="color:#6C7086;font-style:italic;">// invalid keyword 'do'</span>
        </div>
        <p>
          When the compiler encounters a syntax error, it should produce an <strong>informative message</strong>
          and, ideally, <strong>continue scanning</strong> for additional errors. It is not expected to guess
          the programmer's intent.
        </p>
        <p>
          The output of syntax analysis (when no errors exist) is a <strong>stream of atoms</strong> or
          <strong>syntax trees</strong>. Using the parser to produce output as well as check syntax is called
          <strong>syntax-directed translation</strong>.
        </p>
        <div class="note-box pro">
          <span class="box-icon">🧩</span>
          <div>
            Just as we used <em>regular expressions</em> and <em>finite state machines</em> for lexical
            analysis, we use <em>formal grammars</em> for syntax analysis — but the theory is significantly
            more powerful. Most early compiler research focused specifically on this phase.
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 3.0 Overview                                   -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="overview">
        <h2><span class="sec-icon">🗺️</span> 3.0 Overview</h2>
        <p>
          Before studying syntax analysis we need three foundational concepts from formal language theory,
          each of which builds on the previous:
        </p>
        <ol>
          <li><strong>Grammars (3.0.1)</strong> — A new way to specify languages using rewriting rules</li>
          <li><strong>Classes of Grammars (3.0.2)</strong> — Chomsky's hierarchy: unrestricted, context-sensitive, context-free, right-linear</li>
          <li><strong>Context-Free Grammars (3.0.3)</strong> — The class used for programming languages, and BNF notation</li>
        </ol>
        <div class="note-box tip">
          <span class="box-icon">💡</span>
          <div>
            We have already seen two ways to specify a language: <em>regular expressions</em> and
            <em>finite state machines</em>. Grammars are a <strong>third, more powerful way</strong> —
            capable of describing languages that neither of the previous methods can handle.
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 3.0.1 Grammars                                -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="grammars">
        <h2><span class="sec-icon">📐</span> 3.0.1 Grammars</h2>
        <p>
          A <strong>grammar</strong> is a list of rules that can generate all strings of a language
          and nothing else. Formally, a grammar consists of four components:
        </p>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin:18px 0;">
          <div style="background:var(--card);border:1.5px solid rgba(99,102,241,.15);border-radius:var(--radius);padding:16px;box-shadow:var(--shadow);">
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--purple);margin-bottom:6px;">1. Terminal symbols</div>
            <div style="font-size:13px;color:var(--muted);">The input alphabet — the actual characters that appear in strings of the language. Written in <span style="color:var(--green);font-family:'JetBrains Mono',monospace;">lowercase</span>.</div>
          </div>
          <div style="background:var(--card);border:1.5px solid rgba(99,102,241,.15);border-radius:var(--radius);padding:16px;box-shadow:var(--shadow);">
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--purple);margin-bottom:6px;">2. Nonterminal symbols</div>
            <div style="font-size:13px;color:var(--muted);">Intermediate symbols used in rules but never appearing in final strings. Written in <span style="color:#CBA6F7;font-family:'JetBrains Mono',monospace;">UPPERCASE</span>. One is the starting nonterminal.</div>
          </div>
          <div style="background:var(--card);border:1.5px solid rgba(99,102,241,.15);border-radius:var(--radius);padding:16px;box-shadow:var(--shadow);">
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--purple);margin-bottom:6px;">3. Productions (rewriting rules)</div>
            <div style="font-size:13px;color:var(--muted);">Rules of the form <code>α → β</code>, where α and β are strings of terminals/nonterminals and α is not null. Applied repeatedly to generate strings.</div>
          </div>
          <div style="background:var(--card);border:1.5px solid rgba(99,102,241,.15);border-radius:var(--radius);padding:16px;box-shadow:var(--shadow);">
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--purple);margin-bottom:6px;">4. Starting nonterminal</div>
            <div style="font-size:13px;color:var(--muted);">Always <code>S</code> unless otherwise specified. Every derivation begins here. If a sentential form contains only terminals, it is in the language.</div>
          </div>
        </div>

        <div class="note-box warn">
          <span class="box-icon">⚠️</span>
          <div>
            <strong>Convention used throughout Chapter 3:</strong>
            Lowercase letters (<code>a,b,c,…</code>) = terminal symbols.
            Uppercase letters (<code>A,B,C,…</code>) = single nonterminals.
            <code>α,β,γ</code> = strings of terminals and/or nonterminals.
            <code>x,y,z</code> = strings of terminals only.
            <code>S</code> is always the starting nonterminal.
          </div>
        </div>

        <p>
          A <strong>derivation</strong> is a sequence of rule applications, starting from S, ending in a
          string of terminals. It proves that a particular string is in the language:
        </p>
        <div style="background:var(--code-bg);border-radius:var(--radius);padding:14px 22px;margin:14px 0;font-family:'JetBrains Mono',monospace;font-size:14px;color:#CDD6F4;text-align:center;">
          S ⇒ α ⇒ β ⇒ γ ⇒ … ⇒ x
        </div>
      </section>

      <!-- Grammar G1 -->
      <section id="g1">
        <h2><span class="sec-icon">🔢</span> Grammar G1 — Palindromes over {0, 1}</h2>

        <div class="grammar-box">
          <div class="g-title">G1 — Terminal symbols: {0, 1} · Starting nonterminal: S</div>
          <div class="g-rule"><span class="g-num">1.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">0</span><span class="nt">S</span><span class="t">0</span></span></div>
          <div class="g-rule"><span class="g-num">2.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">1</span><span class="nt">S</span><span class="t">1</span></span></div>
          <div class="g-rule"><span class="g-num">3.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">0</span></span></div>
          <div class="g-rule"><span class="g-num">4.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">1</span></span></div>
        </div>

        <p><strong>Example derivation</strong> showing that <code>0010100</code> ∈ L(G1):</p>
        <div style="background:var(--code-bg);border-radius:var(--radius);padding:16px 22px;margin:14px 0;font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;line-height:2.2;">
          <span style="color:#CBA6F7;">S</span> ⇒ <span style="color:#A6E3A1;">0</span><span style="color:#CBA6F7;">S</span><span style="color:#A6E3A1;">0</span> ⇒ <span style="color:#A6E3A1;">0</span><span style="color:#A6E3A1;">0</span><span style="color:#CBA6F7;">S</span><span style="color:#A6E3A1;">00</span> ⇒ <span style="color:#A6E3A1;">00</span><span style="color:#A6E3A1;">1</span><span style="color:#CBA6F7;">S</span><span style="color:#A6E3A1;">1</span><span style="color:#A6E3A1;">00</span> ⇒ <span style="color:#A6E3A1;">0010100</span>
          <div style="font-size:11px;color:#6C7086;margin-top:4px;">rules applied: 1 → 1 → 2 → 4</div>
        </div>

        <div class="note-box key">
          <span class="box-icon">✅</span>
          <div>
            L(G1) = {0, 1, 000, 010, 101, 111, 00000, …} — all <strong>odd-length palindromes</strong>
            over {0,1}. A palindrome reads the same forwards and backwards.
          </div>
        </div>
      </section>

      <!-- Grammar G2 -->
      <section id="g2">
        <h2><span class="sec-icon">🅰️</span> Grammar G2 — aⁿbⁿ (equal a's then b's)</h2>

        <div class="grammar-box">
          <div class="g-title">G2 — Terminal symbols: {a, b} · Starting nonterminal: S</div>
          <div class="g-rule"><span class="g-num">1.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="nt">A</span><span class="nt">S</span><span class="nt">B</span></span></div>
          <div class="g-rule"><span class="g-num">2.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="ep">ε</span></span></div>
          <div class="g-rule"><span class="g-num">3.</span><span class="g-lhs">A</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">a</span></span></div>
          <div class="g-rule"><span class="g-num">4.</span><span class="g-lhs">B</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">b</span></span></div>
        </div>

        <p><strong>Example derivation</strong> showing that <code>aabb</code> ∈ L(G2):</p>
        <div style="background:var(--code-bg);border-radius:var(--radius);padding:16px 22px;margin:14px 0;font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;line-height:2.2;">
          <span style="color:#CBA6F7;">S</span> ⇒ <span style="color:#CBA6F7;">ASB</span> ⇒ <span style="color:#CBA6F7;">A</span><span style="color:#CBA6F7;">ASB</span><span style="color:#CBA6F7;">B</span> ⇒ <span style="color:#CBA6F7;">A</span><span style="color:#CBA6F7;">A</span><span style="color:#F38BA8;">ε</span><span style="color:#CBA6F7;">B</span><span style="color:#CBA6F7;">B</span> ⇒ <span style="color:#CBA6F7;">A</span><span style="color:#CBA6F7;">A</span><span style="color:#CBA6F7;">B</span><span style="color:#CBA6F7;">B</span> ⇒ <span style="color:#CBA6F7;">A</span><span style="color:#CBA6F7;">A</span><span style="color:#CBA6F7;">B</span><span style="color:#A6E3A1;">b</span> ⇒ <span style="color:#CBA6F7;">A</span><span style="color:#CBA6F7;">A</span><span style="color:#A6E3A1;">b</span><span style="color:#A6E3A1;">b</span> ⇒ <span style="color:#CBA6F7;">A</span><span style="color:#A6E3A1;">a</span><span style="color:#A6E3A1;">bb</span> ⇒ <span style="color:#A6E3A1;">aabb</span>
        </div>

        <div class="note-box key">
          <span class="box-icon">✅</span>
          <div>
            L(G2) = {ε, ab, aabb, aaabbb, …} = {aⁿbⁿ | n ≥ 0} — all strings of zero or more a's
            followed by <em>exactly the same number</em> of b's.
            <br><strong>Note:</strong> Multiple different derivations can produce the same string — order
            of rule application may vary.
          </div>
        </div>
      </section>

      <!-- Sample Problem 3.0(a) -->
      <section id="sp-a">
        <h2><span class="sec-icon">🧪</span> Sample Problem 3.0(a)</h2>
        <div class="sample-problem">
          <div class="sp-header"><span style="font-size:18px">📋</span>
            <h4>Sample Problem 3.0 (a)</h4>
          </div>
          <div class="sp-body">
            <p class="sp-question">Show three different derivations using the grammar below (grammar rules 1–4):</p>
            <div class="grammar-box" style="margin-bottom:16px;">
              <div class="g-rule"><span class="g-num">1.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">a</span><span class="nt">S</span><span class="nt">A</span></span></div>
              <div class="g-rule"><span class="g-num">2.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="nt">B</span><span class="nt">A</span></span></div>
              <div class="g-rule"><span class="g-num">3.</span><span class="g-lhs">A</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">a</span><span class="t">b</span></span></div>
              <div class="g-rule"><span class="g-num">4.</span><span class="g-lhs">B</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">b</span><span class="nt">A</span></span></div>
            </div>

            <div class="sp-label">✅ Derivation 1</div>
            <div style="background:var(--code-bg);border-radius:10px;padding:12px 18px;font-family:'JetBrains Mono',monospace;font-size:12.5px;color:#CDD6F4;line-height:2;margin-bottom:10px;">
              S ⇒ <span style="color:#CBA6F7;">a</span>SA ⇒ a<span style="color:#CBA6F7;">BA</span>A ⇒ a<span style="color:#CBA6F7;">B</span><span style="color:#A6E3A1;">ab</span>A ⇒ a<span style="color:#CBA6F7;">B</span>ab<span style="color:#A6E3A1;">ab</span> ⇒ a<span style="color:#A6E3A1;">b</span><span style="color:#CBA6F7;">A</span>abab ⇒ ab<span style="color:#A6E3A1;">ab</span>abab = <span style="color:#6EE7B7; font-weight:700;">abababab</span>
            </div>

            <div class="sp-label">✅ Derivation 2</div>
            <div style="background:var(--code-bg);border-radius:10px;padding:12px 18px;font-family:'JetBrains Mono',monospace;font-size:12.5px;color:#CDD6F4;line-height:2;margin-bottom:10px;">
              S ⇒ aSA ⇒ aS<span style="color:#A6E3A1;">ab</span> ⇒ a<span style="color:#CBA6F7;">BA</span>ab ⇒ a<span style="color:#A6E3A1;">b</span><span style="color:#CBA6F7;">A</span>Aab ⇒ ab<span style="color:#A6E3A1;">ab</span>Aab ⇒ abab<span style="color:#A6E3A1;">ab</span>ab = <span style="color:#6EE7B7; font-weight:700;">abababab</span>
            </div>

            <div class="sp-label">✅ Derivation 3</div>
            <div style="background:var(--code-bg);border-radius:10px;padding:12px 18px;font-family:'JetBrains Mono',monospace;font-size:12.5px;color:#CDD6F4;line-height:2;">
              S ⇒ <span style="color:#CBA6F7;">BA</span> ⇒ <span style="color:#A6E3A1;">b</span><span style="color:#CBA6F7;">A</span>A ⇒ b<span style="color:#A6E3A1;">ab</span>A ⇒ bab<span style="color:#A6E3A1;">ab</span> = <span style="color:#6EE7B7; font-weight:700;">babab</span>
            </div>

            <div class="note-box tip" style="margin-top:14px;">
              <span class="box-icon">💡</span>
              <div>Multiple different derivations can produce the <strong>same string</strong> (abababab appears in both Derivation 1 and 2). Two grammars are <strong>equivalent</strong> if they produce exactly the same language L(G).</div>
            </div>
          </div>
        </div>
      </section>

      <!-- Derivation Stepper -->
      <section id="deriv-sim">
        <h2><span class="sec-icon">▶️</span> Interactive Derivation Stepper</h2>
        <p>Choose a grammar and a preset derivation. Click <strong>Step</strong> to apply rules one by one and watch the sentential form evolve.</p>

        <div class="deriv-wrap">
          <div class="deriv-header">
            <h4>📐 Derivation Stepper</h4>
            <span id="deriv-progress" style="font-size:12px;color:rgba(255,255,255,.7);">Step 0</span>
          </div>
          <div class="deriv-body">
            <div class="deriv-select-row">
              <select class="deriv-sel" id="deriv-grammar-sel" onchange="loadDerivation()">
                <option value="g1a">G1 — derive 0010100</option>
                <option value="g1b">G1 — derive 10101</option>
                <option value="g2a">G2 — derive aabb</option>
                <option value="g2b">G2 — derive aaabbb</option>
              </select>
              <button class="deriv-btn" onclick="derivStep()">Step →</button>
              <button class="deriv-btn sec" onclick="derivAll()">Run All ⚡</button>
              <button class="deriv-btn sec" onclick="loadDerivation()">↺ Reset</button>
            </div>

            <div class="deriv-steps" id="deriv-steps"></div>
            <div id="deriv-result" class="deriv-result" style="display:none;"></div>
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 3.0.2 Classes of Grammars                     -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="classes">
        <h2><span class="sec-icon">🏛️</span> 3.0.2 Classes of Grammars — Chomsky Hierarchy</h2>
        <p>
          In 1959, linguist <strong>Noam Chomsky</strong> classified grammars into four types based on
          the restrictions placed on their rewriting rules. Each class is a <em>subset</em> of the class
          above it.
        </p>
      </section>

      <!-- Chomsky Hierarchy -->
      <section id="chomsky">
        <h2><span class="sec-icon">🔺</span> The Four Classes (0 → 3: most general → most restricted)</h2>

        <div class="chomsky-grid">
          <div class="ch-row c0">
            <div class="ch-num c0">0</div>
            <div class="ch-body">
              <div class="ch-name">Unrestricted</div>
              <div class="ch-rule">Rule form: <code>α → β</code> &nbsp;(any strings, no restriction)</div>
              <div class="ch-desc">No restrictions on the rewriting rules. Either side may be any string of terminals and nonterminals (ε is only allowed on the right side).</div>
              <div class="ch-ex" style="color:var(--red);">Example: &nbsp;<code>SaB → cS</code> &nbsp;(left side has multiple symbols)</div>
            </div>
          </div>
          <div class="ch-row c1">
            <div class="ch-num c1">1</div>
            <div class="ch-body">
              <div class="ch-name">Context-Sensitive</div>
              <div class="ch-rule">Rule form: <code>αAγ → αβγ</code> &nbsp;(A rewrites only in context α…γ)</div>
              <div class="ch-desc">The nonterminal A on the left is rewritten, but <em>only when it appears in a specific context</em> (α on its left, γ on its right). The context α and γ are preserved in the output.</div>
              <div class="ch-ex" style="color:var(--amber);">Example: &nbsp;<code>SaB → caB</code> &nbsp;(S rewrites to c only when followed by aB)</div>
            </div>
          </div>
          <div class="ch-row c2">
            <div class="ch-num c2">2</div>
            <div class="ch-body">
              <div class="ch-name">Context-Free ⭐ <span style="font-size:12px;font-family:'DM Sans',sans-serif;font-weight:400;color:var(--muted);">used for programming languages</span></div>
              <div class="ch-rule">Rule form: <code>A → α</code> &nbsp;(single nonterminal → anything)</div>
              <div class="ch-desc">The nonterminal A on the left rewrites regardless of context. Most programming languages are defined by this class. Grammars G1 and G2 are both context-free.</div>
              <div class="ch-ex" style="color:var(--purple);">Example: &nbsp;<code>A → aABb</code> &nbsp;(A can always be replaced)</div>
            </div>
          </div>
          <div class="ch-row c3">
            <div class="ch-num c3">3</div>
            <div class="ch-body">
              <div class="ch-name">Right Linear <span style="font-size:12px;font-family:'DM Sans',sans-serif;font-weight:400;color:var(--muted);">same power as regular expressions</span></div>
              <div class="ch-rule">Rule form: <code>A → aB</code> or <code>A → a</code> &nbsp;(terminal then optional nonterminal at right)</div>
              <div class="ch-desc">The most restricted class. Equivalent to regular expressions and finite state machines. Used to define lexical items: identifiers, constants, keywords.</div>
              <div class="ch-ex" style="color:var(--green);">Example: &nbsp;<code>A → aB</code> , <code>B → b</code> &nbsp;(generates: ab)</div>
            </div>
          </div>
        </div>

        <div class="note-box pro">
          <span class="box-icon">🧩</span>
          <div>
            <strong>Subset relationship:</strong> Every right-linear grammar is also context-free, context-sensitive, and unrestricted. Every context-free grammar is also context-sensitive and unrestricted. The classes form nested circles: Right-Linear ⊂ Context-Free ⊂ Context-Sensitive ⊂ Unrestricted (Figure 3.1 in the textbook).
          </div>
        </div>

        <div class="note-box tip">
          <span class="box-icon">💡</span>
          <div>
            <strong>Symbol conventions for the hierarchy:</strong><br>
            <code>A,B,C,…</code> = single nonterminal &nbsp;|&nbsp;
            <code>a,b,c,…</code> = single terminal &nbsp;|&nbsp;
            <code>X,Y,Z,…</code> = single terminal or nonterminal<br>
            <code>x,y,z,…</code> = string of terminals &nbsp;|&nbsp;
            <code>α,β,γ,…</code> = string of terminals and nonterminals
          </div>
        </div>
      </section>

      <!-- Grammar G3 -->
      <section id="g3">
        <h2><span class="sec-icon">🅰️</span> Grammar G3 — aⁿbⁿcⁿ (Context-Sensitive, not Context-Free)</h2>
        <p>
          This is an example of a context-sensitive grammar that is <em>not</em> context-free —
          no context-free grammar exists for L(G3).
        </p>
        <div class="grammar-box">
          <div class="g-title">G3 — Context-Sensitive grammar for aⁿbⁿcⁿ</div>
          <div class="g-rule"><span class="g-num">1.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">a</span><span class="nt">S</span><span class="nt">B</span><span class="nt">C</span></span></div>
          <div class="g-rule"><span class="g-num">2.</span><span class="g-lhs">S</span><span class="g-arr"> → </span><span class="g-rhs"><span class="ep">ε</span></span></div>
          <div class="g-rule"><span class="g-num">3.</span><span class="g-lhs">a</span><span class="g-lhs">B</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">ab</span></span> &nbsp;<span style="color:#6C7086;font-size:11px;font-style:italic;">← context-sensitive: aB → ab only when preceded by a</span></div>
          <div class="g-rule"><span class="g-num">4.</span><span class="g-lhs">b</span><span class="g-lhs">B</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">bb</span></span></div>
          <div class="g-rule"><span class="g-num">5.</span><span class="g-lhs">C</span><span class="g-arr"> → </span><span class="g-rhs"><span class="t">c</span></span></div>
          <div class="g-rule"><span class="g-num">6.</span><span class="g-lhs">C</span><span class="g-lhs">B</span><span class="g-arr"> → </span><span class="g-rhs"><span class="nt">C</span><span class="nt">X</span></span></div>
          <div class="g-rule"><span class="g-num">7.</span><span class="g-lhs">C</span><span class="g-lhs">X</span><span class="g-arr"> → </span><span class="g-rhs"><span class="nt">B</span><span class="nt">X</span></span></div>
          <div class="g-rule"><span class="g-num">8.</span><span class="g-lhs">B</span><span class="g-lhs">X</span><span class="g-arr"> → </span><span class="g-rhs"><span class="nt">B</span><span class="nt">C</span></span></div>
        </div>

        <p><strong>Example derivation</strong> for <code>aabbcc</code>:</p>
        <div style="background:var(--code-bg);border-radius:var(--radius);padding:16px 22px;margin:14px 0;font-family:'JetBrains Mono',monospace;font-size:12px;color:#CDD6F4;line-height:2.2;">
          S ⇒ aSBC ⇒ aaSBCBC ⇒ aaBCBC ⇒ aaBCXC ⇒ aaBBXC ⇒ aaBBCC<br>
          ⇒ <span style="color:#A6E3A1;">a</span><span style="color:#A6E3A1;">a</span>bBCC ⇒ <span style="color:#A6E3A1;">aa</span>bbCC ⇒ <span style="color:#A6E3A1;">aabb</span>Cc ⇒ <span style="color:#A6E3A1;">aabbcc</span>
        </div>

        <div class="note-box warn">
          <span class="box-icon">⚠️</span>
          <div>
            L(G3) = {ε, abc, aabbcc, aaabbbccc, …} = {aⁿbⁿcⁿ | n ≥ 0}. This language
            <strong>cannot be generated by any context-free grammar</strong>. It requires context-sensitivity
            because the counts of a's, b's, and c's must all match.
          </div>
        </div>
      </section>

      <!-- Sample Problem 3.0(b) -->
      <section id="sp-b">
        <h2><span class="sec-icon">🧪</span> Sample Problem 3.0(b) — Classify Grammar Rules</h2>
        <div class="sample-problem">
          <div class="sp-header"><span style="font-size:18px">📋</span>
            <h4>Sample Problem 3.0 (b)</h4>
          </div>
          <div class="sp-body">
            <p class="sp-question">Classify each rule according to Chomsky's classification. Give the largest (most restricted) type that applies.</p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin:12px 0;">
              <div style="background:var(--code-bg);border-radius:10px;padding:12px 16px;">
                <div style="font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;margin-bottom:6px;"><span style="color:#CBA6F7;">aSb</span> → <span style="color:#CBA6F7;">a</span><span style="color:#CBA6F7;">A</span><span style="color:#A6E3A1;">c</span><span style="color:#CBA6F7;">B</span><span style="color:#A6E3A1;">b</span></div>
                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--amber);">Type 1 — Context-Sensitive</div>
                <div style="font-size:12px;color:var(--muted);">α = a, A = S, γ = b — S rewrites in context</div>
              </div>
              <div style="background:var(--code-bg);border-radius:10px;padding:12px 16px;">
                <div style="font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;margin-bottom:6px;"><span style="color:#CBA6F7;">B</span> → <span style="color:#A6E3A1;">a</span><span style="color:#CBA6F7;">A</span></div>
                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--green);">Type 3 — Right Linear</div>
                <div style="font-size:12px;color:var(--muted);">A → aB form: terminal then nonterminal</div>
              </div>
              <div style="background:var(--code-bg);border-radius:10px;padding:12px 16px;">
                <div style="font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;margin-bottom:6px;"><span style="color:#A6E3A1;">a</span> → <span style="color:#CBA6F7;">A</span><span style="color:#CBA6F7;">B</span><span style="color:#CBA6F7;">C</span></div>
                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--red);">Type 0 — Unrestricted</div>
                <div style="font-size:12px;color:var(--muted);">Left side is a terminal — no class restriction matches</div>
              </div>
              <div style="background:var(--code-bg);border-radius:10px;padding:12px 16px;">
                <div style="font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;margin-bottom:6px;"><span style="color:#CBA6F7;">S</span> → <span style="color:#A6E3A1;">a</span><span style="color:#CBA6F7;">B</span><span style="color:#A6E3A1;">c</span></div>
                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--purple);">Type 2 — Context-Free</div>
                <div style="font-size:12px;color:var(--muted);">A → α form: single nonterminal rewrites freely</div>
              </div>
              <div style="background:var(--code-bg);border-radius:10px;padding:12px 16px;">
                <div style="font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;margin-bottom:6px;"><span style="color:#CBA6F7;">A</span><span style="color:#A6E3A1;">b</span> → <span style="color:#A6E3A1;">b</span></div>
                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--amber);">Type 1 — Context-Sensitive</div>
                <div style="font-size:12px;color:var(--muted);">A rewrites when followed by b (right context = b)</div>
              </div>
              <div style="background:var(--code-bg);border-radius:10px;padding:12px 16px;">
                <div style="font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;margin-bottom:6px;"><span style="color:#CBA6F7;">A</span><span style="color:#CBA6F7;">B</span> → <span style="color:#CBA6F7;">B</span><span style="color:#CBA6F7;">A</span></div>
                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:var(--red);">Type 0 — Unrestricted</div>
                <div style="font-size:12px;color:var(--muted);">Two nonterminals on the left side — not context-sensitive</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Grammar Membership Tester                     -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="sandbox">
        <h2><span class="sec-icon">🧪</span> Grammar Membership Tester</h2>
        <p>
          Enter a string and select a grammar. The tester will tell you whether the string is
          in the language of that grammar — and show a valid derivation if it is.
        </p>

        <div class="sandbox-wrap">
          <div class="sandbox-header">
            <h4>🔍 Is this string in the language?</h4>
            <span style="font-size:12px;color:rgba(255,255,255,.7);">L(G1), L(G2), L(G3)</span>
          </div>
          <div class="sandbox-body">
            <div class="sb-controls">
              <select class="sb-grammar-select" id="sb-grammar">
                <option value="g1">G1 — Odd palindromes over {0,1}</option>
                <option value="g2">G2 — aⁿbⁿ (equal a's then b's)</option>
                <option value="g3">G3 — aⁿbⁿcⁿ</option>
              </select>
              <input class="sb-input" id="sb-string" type="text" placeholder="e.g. 010 or aabb" maxlength="20">
              <button class="sb-btn" onclick="testMembership()">Test ▶</button>
              <button class="sb-btn sec" onclick="clearSb()">Clear</button>
            </div>
            <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:12px;">
              <span style="font-size:12px;color:var(--muted);">Quick examples:</span>
              <button onclick="setSb('g1','0')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(99,102,241,.3);background:transparent;color:var(--purple);cursor:pointer;">0</button>
              <button onclick="setSb('g1','010')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(99,102,241,.3);background:transparent;color:var(--purple);cursor:pointer;">010</button>
              <button onclick="setSb('g1','00100')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(99,102,241,.3);background:transparent;color:var(--purple);cursor:pointer;">00100</button>
              <button onclick="setSb('g2','aabb')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(10,185,129,.3);background:transparent;color:var(--green);cursor:pointer;">aabb</button>
              <button onclick="setSb('g2','aaabbb')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(10,185,129,.3);background:transparent;color:var(--green);cursor:pointer;">aaabbb</button>
              <button onclick="setSb('g3','aabbcc')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(245,158,11,.3);background:transparent;color:var(--amber);cursor:pointer;">aabbcc</button>
              <button onclick="setSb('g2','ab')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(239,68,68,.3);background:transparent;color:var(--red);cursor:pointer;">ab (G2✓)</button>
              <button onclick="setSb('g1','00')" style="font-size:11px;padding:3px 9px;border-radius:5px;border:1px solid rgba(239,68,68,.3);background:transparent;color:var(--red);cursor:pointer;">00 (G1✗)</button>
            </div>
            <div id="sb-result" class="sb-result" style="display:none;"></div>
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- DRAG AND DROP — 5 answers                     -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="dragdrop">
        <h2><span class="sec-icon">🎯</span> Drag &amp; Drop — Chomsky Classification</h2>
        <p>Drag each grammar rule into the correct Chomsky class zone. Each question has <strong>5 chips</strong> and 5 zones.</p>

        <!-- DnD Q1 -->
        <div class="dnd-quiz" id="dnd1">
          <div class="dnd-header" style="background:linear-gradient(135deg,#6366F1,#818CF8);">
            <div class="dnd-q-num" style="background:rgba(255,255,255,.2);color:white;">1</div>
            <div class="dnd-q-text" style="color:white;">Match each grammar rule to its Chomsky class (most restricted type that applies)</div>
          </div>
          <div class="dnd-body">
            <p class="dnd-prompt">Drag the rules into the correct classification zones. Use the most restricted class.</p>
            <div class="dnd-bank" id="bank1" ondrop="dropChip(event,'bank1')" ondragover="allowDrop(event)">
              <div class="dnd-chip chip-0" draggable="true" ondragstart="dragStart(event)" id="c1-r1">aB → ba</div>
              <div class="dnd-chip chip-1" draggable="true" ondragstart="dragStart(event)" id="c1-r2">aSb → acb</div>
              <div class="dnd-chip chip-2" draggable="true" ondragstart="dragStart(event)" id="c1-r3">S → aBc</div>
              <div class="dnd-chip chip-3" draggable="true" ondragstart="dragStart(event)" id="c1-r4">A → aB</div>
              <div class="dnd-chip chip-q" draggable="true" ondragstart="dragStart(event)" id="c1-r5">AB → BA</div>
            </div>
            <div class="dnd-zones">
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:var(--red);">Type 0 — Unrestricted</div>
                <div class="dnd-zone" id="z1-0" ondrop="dropChip(event,'z1-0')" ondragover="allowDrop(event)" data-answer="c1-r5"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:var(--amber);">Type 1 — Context-Sensitive</div>
                <div class="dnd-zone" id="z1-1" ondrop="dropChip(event,'z1-1')" ondragover="allowDrop(event)" data-answer="c1-r2"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:var(--purple);">Type 2 — Context-Free</div>
                <div class="dnd-zone" id="z1-2" ondrop="dropChip(event,'z1-2')" ondragover="allowDrop(event)" data-answer="c1-r3"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:var(--green);">Type 3 — Right Linear</div>
                <div class="dnd-zone" id="z1-3" ondrop="dropChip(event,'z1-3')" ondragover="allowDrop(event)" data-answer="c1-r4"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:var(--red);">Type 0 — Also Unrestricted</div>
                <div class="dnd-zone" id="z1-4" ondrop="dropChip(event,'z1-4')" ondragover="allowDrop(event)" data-answer="c1-r1"></div>
              </div>
            </div>
            <button class="dnd-check" onclick="checkDnD('dnd1',['z1-0','z1-1','z1-2','z1-3','z1-4'])">✓ Check</button>
            <button class="dnd-reset-btn" onclick="resetDnD('dnd1','bank1',['z1-0','z1-1','z1-2','z1-3','z1-4'])">↺ Reset</button>
            <div class="dnd-feedback" id="fb-dnd1"></div>
          </div>
        </div>

        <!-- DnD Q2 -->
        <div class="dnd-quiz" id="dnd2">
          <div class="dnd-header" style="background:linear-gradient(135deg,#10B981,#34D399);">
            <div class="dnd-q-num" style="background:rgba(255,255,255,.2);color:white;">2</div>
            <div class="dnd-q-text" style="color:white;">Match each string to the grammar it belongs to (G1, G2, or G3)</div>
          </div>
          <div class="dnd-body">
            <p class="dnd-prompt">G1 = palindromes over {0,1} · G2 = aⁿbⁿ · G3 = aⁿbⁿcⁿ</p>
            <div class="dnd-bank" id="bank2" ondrop="dropChip(event,'bank2')" ondragover="allowDrop(event)">
              <div class="dnd-chip chip-0" draggable="true" ondragstart="dragStart(event)" id="c2-s1">aabbcc</div>
              <div class="dnd-chip chip-1" draggable="true" ondragstart="dragStart(event)" id="c2-s2">1</div>
              <div class="dnd-chip chip-2" draggable="true" ondragstart="dragStart(event)" id="c2-s3">aaabbb</div>
              <div class="dnd-chip chip-3" draggable="true" ondragstart="dragStart(event)" id="c2-s4">ε (null)</div>
              <div class="dnd-chip chip-q" draggable="true" ondragstart="dragStart(event)" id="c2-s5">10101</div>
            </div>
            <div class="dnd-zones">
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:var(--red);">G3 — aⁿbⁿcⁿ</div>
                <div class="dnd-zone" id="z2-0" ondrop="dropChip(event,'z2-0')" ondragover="allowDrop(event)" data-answer="c2-s1"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:var(--amber);">G1 — single char palindrome</div>
                <div class="dnd-zone" id="z2-1" ondrop="dropChip(event,'z2-1')" ondragover="allowDrop(event)" data-answer="c2-s2"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:var(--purple);">G2 — aⁿbⁿ (n=3)</div>
                <div class="dnd-zone" id="z2-2" ondrop="dropChip(event,'z2-2')" ondragover="allowDrop(event)" data-answer="c2-s3"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:var(--green);">G2 — aⁿbⁿ (n=0)</div>
                <div class="dnd-zone" id="z2-3" ondrop="dropChip(event,'z2-3')" ondragover="allowDrop(event)" data-answer="c2-s4"></div>
              </div>
              <div class="dnd-zone-wrap">
                <div class="dnd-zone-label" style="color:#6B21A8;">G1 — odd palindrome</div>
                <div class="dnd-zone" id="z2-4" ondrop="dropChip(event,'z2-4')" ondragover="allowDrop(event)" data-answer="c2-s5"></div>
              </div>
            </div>
            <button class="dnd-check" onclick="checkDnD('dnd2',['z2-0','z2-1','z2-2','z2-3','z2-4'])">✓ Check</button>
            <button class="dnd-reset-btn" onclick="resetDnD('dnd2','bank2',['z2-0','z2-1','z2-2','z2-3','z2-4'])">↺ Reset</button>
            <div class="dnd-feedback" id="fb-dnd2"></div>
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Quiz                                          -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="quiz">
        <h2><span class="sec-icon">🧩</span> Check Your Knowledge</h2>
        <p>Test what you've learned about Sections 3.0–3.0.2.</p>

        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span>
            <h4>Question 1</h4>
          </div>
          <div class="quiz-body">
            <p class="quiz-q">What does a <em>derivation</em> prove about a string?</p>
            <div class="quiz-options" id="q1-opts">
              <div class="quiz-opt" onclick="answer(this,'q1',false)">
                <div class="opt-circle">A</div> That the string is the only string in the language
              </div>
              <div class="quiz-opt" onclick="answer(this,'q1',true)">
                <div class="opt-circle">B</div> That the particular string is a member of the language — it demonstrates this by showing a sequence of rule applications from the starting nonterminal to that string
              </div>
              <div class="quiz-opt" onclick="answer(this,'q1',false)">
                <div class="opt-circle">C</div> That the grammar has no more than one way to produce the string
              </div>
              <div class="quiz-opt" onclick="answer(this,'q1',false)">
                <div class="opt-circle">D</div> That the string is the shortest string in the language
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
            <p class="quiz-q">Which Chomsky class is used for most programming languages?</p>
            <div class="quiz-options" id="q2-opts">
              <div class="quiz-opt" onclick="answer(this,'q2',false)">
                <div class="opt-circle">A</div> Type 0 — Unrestricted
              </div>
              <div class="quiz-opt" onclick="answer(this,'q2',false)">
                <div class="opt-circle">B</div> Type 1 — Context-Sensitive
              </div>
              <div class="quiz-opt" onclick="answer(this,'q2',true)">
                <div class="opt-circle">C</div> Type 2 — Context-Free, because it is powerful enough for programming language constructs while still being practical to parse
              </div>
              <div class="quiz-opt" onclick="answer(this,'q2',false)">
                <div class="opt-circle">D</div> Type 3 — Right Linear, because it is the simplest and most efficient
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
            <p class="quiz-q">The rule <code>aSb → acb</code> belongs to which Chomsky class (most restricted)?</p>
            <div class="quiz-options" id="q3-opts">
              <div class="quiz-opt" onclick="answer(this,'q3',false)">
                <div class="opt-circle">A</div> Type 0 — Unrestricted
              </div>
              <div class="quiz-opt" onclick="answer(this,'q3',true)">
                <div class="opt-circle">B</div> Type 1 — Context-Sensitive (α = a, A = S, γ = b — S is rewritten in the context of a on its left and b on its right)
              </div>
              <div class="quiz-opt" onclick="answer(this,'q3',false)">
                <div class="opt-circle">C</div> Type 2 — Context-Free
              </div>
              <div class="quiz-opt" onclick="answer(this,'q3',false)">
                <div class="opt-circle">D</div> Type 3 — Right Linear
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
            <p class="quiz-q">L(G2) = {aⁿbⁿ | n ≥ 0}. Which of the following strings is NOT in L(G2)?</p>
            <div class="quiz-options" id="q4-opts">
              <div class="quiz-opt" onclick="answer(this,'q4',false)">
                <div class="opt-circle">A</div> ε (the null string)
              </div>
              <div class="quiz-opt" onclick="answer(this,'q4',false)">
                <div class="opt-circle">B</div> ab
              </div>
              <div class="quiz-opt" onclick="answer(this,'q4',false)">
                <div class="opt-circle">C</div> aaabbb
              </div>
              <div class="quiz-opt" onclick="answer(this,'q4',true)">
                <div class="opt-circle">D</div> aabb<strong>a</strong> — it has three a's but only two b's (n must be the same on both sides)
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
            <p class="quiz-q">What is the subset relationship between Right Linear (type 3) and Context-Free (type 2) grammars?</p>
            <div class="quiz-options" id="q5-opts">
              <div class="quiz-opt" onclick="answer(this,'q5',false)">
                <div class="opt-circle">A</div> Context-free is a subset of right linear — every context-free grammar can be rewritten as right linear
              </div>
              <div class="quiz-opt" onclick="answer(this,'q5',true)">
                <div class="opt-circle">B</div> Right linear is a subset of context-free — every right-linear grammar is also context-free, context-sensitive, and unrestricted
              </div>
              <div class="quiz-opt" onclick="answer(this,'q5',false)">
                <div class="opt-circle">C</div> They are disjoint — a grammar cannot be both right linear and context-free
              </div>
              <div class="quiz-opt" onclick="answer(this,'q5',false)">
                <div class="opt-circle">D</div> They are equivalent — every context-free grammar is also right linear
              </div>
            </div>
            <div class="quiz-feedback" id="q5-fb"></div>
          </div>
        </div>

      </section>

      <div class="chapter-nav">
        <a href="c2_5.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Previous section</span>2.5 Lexical Analysis for Decaf</div>
        </a>
        <a href="c3_0.php" class="nav-btn next">
          <div style="text-align:right"><span class="btn-sub">Next section</span>3.0.3 Context-Free Grammars &amp; BNF</div>
          <span>→</span>
        </a>
      </div>

    </article>
  </div>

  <script>
    // ── TOC ──────────────────────────────────────────────────
    const sections = document.querySelectorAll('section[id]');
    const tocLinks = document.querySelectorAll('.toc-link');
    const bar = document.getElementById('toc-bar');
    const pctLabel = document.getElementById('pct-label');
    window.addEventListener('scroll', () => {
      const scrolled = window.scrollY;
      const total = document.body.scrollHeight - window.innerHeight;
      bar.style.width = Math.round(scrolled / total * 100) + '%';
      pctLabel.textContent = Math.round(scrolled / total * 100) + '%';
      let cur = '';
      sections.forEach(s => {
        if (scrolled >= s.offsetTop - 120) cur = s.id;
      });
      tocLinks.forEach(l => l.classList.toggle('active', l.getAttribute('href') === '#' + cur));
    });

    // ════════════════════════════════════════════════════════
    // DERIVATION STEPPER
    // ════════════════════════════════════════════════════════
    const DERIVATIONS = {
      g1a: {
        title: 'G1 — Deriving 0010100',
        steps: [{
            form: 'S',
            rule: '(start)',
            type: 'start'
          },
          {
            form: '0S0',
            rule: 'Rule 1: S→0S0',
            type: 'middle'
          },
          {
            form: '00S00',
            rule: 'Rule 1: S→0S0',
            type: 'middle'
          },
          {
            form: '001S100',
            rule: 'Rule 2: S→1S1',
            type: 'middle'
          },
          {
            form: '0010100',
            rule: 'Rule 3: S→0',
            type: 'final'
          },
        ]
      },
      g1b: {
        title: 'G1 — Deriving 10101',
        steps: [{
            form: 'S',
            rule: '(start)',
            type: 'start'
          },
          {
            form: '1S1',
            rule: 'Rule 2: S→1S1',
            type: 'middle'
          },
          {
            form: '10S01',
            rule: 'Rule 1: S→0S0',
            type: 'middle'
          },
          {
            form: '10101',
            rule: 'Rule 4: S→1',
            type: 'final'
          },
        ]
      },
      g2a: {
        title: 'G2 — Deriving aabb',
        steps: [{
            form: 'S',
            rule: '(start)',
            type: 'start'
          },
          {
            form: 'ASB',
            rule: 'Rule 1: S→ASB',
            type: 'middle'
          },
          {
            form: 'AASBB',
            rule: 'Rule 1: S→ASB',
            type: 'middle'
          },
          {
            form: 'AA\u03b5BB',
            rule: 'Rule 2: S→ε',
            type: 'middle'
          },
          {
            form: 'AABB',
            rule: '(ε removed)',
            type: 'middle'
          },
          {
            form: 'AABb',
            rule: 'Rule 4: B→b',
            type: 'middle'
          },
          {
            form: 'AAbb',
            rule: 'Rule 4: B→b',
            type: 'middle'
          },
          {
            form: 'Aabb',
            rule: 'Rule 3: A→a',
            type: 'middle'
          },
          {
            form: 'aabb',
            rule: 'Rule 3: A→a',
            type: 'final'
          },
        ]
      },
      g2b: {
        title: 'G2 — Deriving aaabbb',
        steps: [{
            form: 'S',
            rule: '(start)',
            type: 'start'
          },
          {
            form: 'ASB',
            rule: 'Rule 1: S→ASB',
            type: 'middle'
          },
          {
            form: 'AASBB',
            rule: 'Rule 1: S→ASB',
            type: 'middle'
          },
          {
            form: 'AAASBB B',
            rule: 'Rule 1: S→ASB',
            type: 'middle'
          },
          {
            form: 'AAAε BBB',
            rule: 'Rule 2: S→ε',
            type: 'middle'
          },
          {
            form: 'AAABBB',
            rule: '(ε removed)',
            type: 'middle'
          },
          {
            form: 'AAABBb',
            rule: 'Rule 4: B→b',
            type: 'middle'
          },
          {
            form: 'AAABbb',
            rule: 'Rule 4: B→b',
            type: 'middle'
          },
          {
            form: 'AAAbb b',
            rule: 'Rule 4: B→b',
            type: 'middle'
          },
          {
            form: 'AAAbbb',
            rule: '—',
            type: 'middle'
          },
          {
            form: 'AAabbb',
            rule: 'Rule 3: A→a',
            type: 'middle'
          },
          {
            form: 'Aaabbb',
            rule: 'Rule 3: A→a',
            type: 'middle'
          },
          {
            form: 'aaabbb',
            rule: 'Rule 3: A→a',
            type: 'final'
          },
        ]
      }
    };

    let derivCurrent = null;
    let derivIdx = 0;

    function loadDerivation() {
      const key = document.getElementById('deriv-grammar-sel').value;
      derivCurrent = DERIVATIONS[key];
      derivIdx = 0;
      const container = document.getElementById('deriv-steps');
      container.innerHTML = '';
      document.getElementById('deriv-result').style.display = 'none';
      document.getElementById('deriv-progress').textContent = 'Step 0 of ' + (derivCurrent.steps.length - 1);
      // show first step immediately
      appendDerivStep(0);
    }

    function appendDerivStep(i) {
      const s = derivCurrent.steps[i];
      const el = document.createElement('div');
      el.className = 'deriv-step ' + s.type;
      el.innerHTML = `<span class="ds-label">${i===0?'START':i===derivCurrent.steps.length-1?'FINAL':'Step '+i}</span>
        <span class="ds-str">${formatForm(s.form)}</span>
        <span class="ds-rule">${s.rule}</span>`;
      document.getElementById('deriv-steps').appendChild(el);
      requestAnimationFrame(() => el.classList.add('visible'));
    }

    function formatForm(f) {
      return f.split('').map(c => {
        if (c === c.toUpperCase() && /[A-Z]/.test(c)) return `<span class="nt">${c}</span>`;
        if (/[a-z0-9]/.test(c)) return `<span class="t">${c}</span>`;
        if (c === 'ε' || c === '\u03b5') return `<span class="ep">ε</span>`;
        return c;
      }).join('');
    }

    function derivStep() {
      if (!derivCurrent) {
        loadDerivation();
        return;
      }
      derivIdx++;
      if (derivIdx >= derivCurrent.steps.length) {
        showDerivResult();
        return;
      }
      appendDerivStep(derivIdx);
      document.getElementById('deriv-progress').textContent = `Step ${derivIdx} of ${derivCurrent.steps.length-1}`;
      if (derivIdx === derivCurrent.steps.length - 1) showDerivResult();
    }

    function derivAll() {
      if (!derivCurrent) loadDerivation();
      while (derivIdx < derivCurrent.steps.length - 1) {
        derivIdx++;
        appendDerivStep(derivIdx);
      }
      document.getElementById('deriv-progress').textContent = `Step ${derivIdx} of ${derivCurrent.steps.length-1}`;
      showDerivResult();
    }

    function showDerivResult() {
      const final = derivCurrent.steps[derivCurrent.steps.length - 1].form;
      const res = document.getElementById('deriv-result');
      res.style.display = 'block';
      res.style.background = '#F0FDF4';
      res.style.color = '#065F46';
      res.style.border = '1.5px solid #10B981';
      res.innerHTML = `✅ <strong style="font-family:'JetBrains Mono',monospace">"${final}"</strong> has been derived — it is a member of the language!`;
    }

    // init
    loadDerivation();

    // ════════════════════════════════════════════════════════
    // GRAMMAR MEMBERSHIP TESTER
    // ════════════════════════════════════════════════════════
    function setSb(g, s) {
      document.getElementById('sb-grammar').value = g;
      document.getElementById('sb-string').value = s;
      testMembership();
    }

    function clearSb() {
      document.getElementById('sb-string').value = '';
      document.getElementById('sb-result').style.display = 'none';
    }

    function testMembership() {
      const g = document.getElementById('sb-grammar').value;
      const str = document.getElementById('sb-string').value.trim();
      const res = document.getElementById('sb-result');
      let accepted = false,
        explanation = '';

      if (g === 'g1') {
        // G1: odd palindromes over {0,1}
        const valid = /^[01]+$/.test(str) && str.length % 2 === 1 && str === str.split('').reverse().join('');
        accepted = valid;
        if (!str) {
          accepted = false;
          explanation = 'Enter a non-empty string of 0s and 1s.';
        } else if (!/^[01]+$/.test(str)) explanation = 'G1 only uses alphabet {0,1}.';
        else if (str.length % 2 === 0) explanation = 'G1 requires odd-length strings only.';
        else if (str !== str.split('').reverse().join('')) explanation = `"${str}" is not a palindrome — it doesn't read the same backwards.`;
        else explanation = `"${str}" ∈ L(G1) — it's an odd-length palindrome over {0,1}. `;
      } else if (g === 'g2') {
        // G2: a^n b^n
        const m = str.match(/^(a*)(b*)$/);
        if (!m) {
          accepted = false;
          explanation = 'G2 only uses alphabet {a,b} and all a\'s must precede all b\'s.';
        } else if (str === '') {
          accepted = true;
          explanation = 'The null string ε ∈ L(G2) — n=0 is allowed.';
        } else if (m[1].length !== m[2].length) {
          accepted = false;
          explanation = `"${str}" has ${m[1].length} a(s) but ${m[2].length} b(s) — counts must be equal.`;
        } else {
          accepted = true;
          explanation = `"${str}" ∈ L(G2) = aⁿbⁿ with n=${m[1].length}.`;
        }
      } else {
        // G3: a^n b^n c^n
        const m = str.match(/^(a*)(b*)(c*)$/);
        if (!m) {
          accepted = false;
          explanation = 'G3 only uses alphabet {a,b,c} in order a…b…c.';
        } else if (str === '') {
          accepted = true;
          explanation = 'The null string ε ∈ L(G3) — n=0 is allowed.';
        } else if (m[1].length !== m[2].length || m[2].length !== m[3].length) {
          accepted = false;
          explanation = `"${str}" has ${m[1].length} a(s), ${m[2].length} b(s), ${m[3].length} c(s) — all three counts must be equal.`;
        } else {
          accepted = true;
          explanation = `"${str}" ∈ L(G3) = aⁿbⁿcⁿ with n=${m[1].length}.`;
        }
      }

      res.style.display = 'block';
      res.className = 'sb-result ' + (accepted ? 'accepted' : 'rejected');
      res.innerHTML = (accepted ? '✅ ' : '❌ ') + explanation;
    }

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
        const existing = target.firstElementChild;
        const bank = findBank(targetId);
        if (bank) {
          document.getElementById(bank).appendChild(existing);
          existing.onclick = null;
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
      // zone ids: z1-0, z1-1 → bank1; z2-0 → bank2
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
        fb.innerHTML = `⚠️ <strong>${correct} of ${total}</strong> correct. Green = right, red = wrong.`;
      } else {
        fb.className = 'dnd-feedback show bad';
        fb.innerHTML = `❌ None correct yet. Review the section and try again.`;
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

    // ── Quiz ─────────────────────────────────────────────────
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