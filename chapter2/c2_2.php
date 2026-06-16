<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chapter 2.2 – CompileX</title>

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

    /* Info boxes */
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

    /* Code block */
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
      line-height: 1.85;
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

    .fn {
      color: #89B4FA;
    }

    .typ {
      color: #94E2D5;
    }

    .str {
      color: #A6E3A1;
    }

    /* FSM diagram panel */
    .fsm-wrap {
      background: var(--card);
      border: 1px solid rgba(99, 102, 241, .15);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      margin: 20px 0;
    }

    .fsm-header {
      background: var(--purple-s);
      padding: 12px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .fsm-header h4 {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 14px;
      color: var(--purple);
      margin: 0;
    }

    .fsm-body {
      display: grid;
      grid-template-columns: 1fr 1fr;
    }

    .fsm-svg-panel {
      padding: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-right: 1px solid #EEF2FF;
    }

    .fsm-explain-panel {
      padding: 20px;
    }

    .fsm-explain-panel h5 {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 13px;
      color: var(--text);
      margin-bottom: 10px;
    }

    .fsm-caption {
      padding: 12px 20px;
      font-size: 13px;
      color: var(--muted);
      background: #FAFAFA;
      border-top: 1px solid #EEF2FF;
    }

    /* Action function cards */
    .action-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
      margin: 20px 0;
    }

    .action-card {
      background: var(--code-bg);
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, .2);
    }

    .action-card-header {
      padding: 8px 16px;
      display: flex;
      align-items: center;
      gap: 8px;
      background: rgba(99, 102, 241, .2);
    }

    .action-card-header .fn-name {
      font-family: 'JetBrains Mono', monospace;
      font-size: 13px;
      color: #A5B4FC;
      font-weight: 700;
    }

    .action-card-header .fn-role {
      font-size: 11px;
      color: rgba(205, 214, 244, .5);
    }

    .action-card pre {
      padding: 12px 16px;
    }

    .action-card pre code {
      font-size: 12px;
      line-height: 1.7;
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

    .sp-solution-label {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--green);
      margin-bottom: 12px;
    }

    /* Variable trace table */
    .trace-table {
      width: 100%;
      border-collapse: collapse;
      font-family: 'JetBrains Mono', monospace;
      font-size: 13px;
      margin: 16px 0;
    }

    .trace-table th {
      background: var(--code-bg);
      color: #A5B4FC;
      padding: 8px 14px;
      text-align: center;
      font-size: 12px;
    }

    .trace-table td {
      padding: 8px 14px;
      border-bottom: 1px solid #EEF2FF;
      text-align: center;
      color: var(--muted);
    }

    .trace-table td.changed {
      color: var(--amber);
      font-weight: 700;
      background: rgba(245, 158, 11, .06);
    }

    .trace-table td.input-col {
      color: #6EE7B7;
      font-weight: 700;
    }

    .trace-table td.fn-col {
      color: #A5B4FC;
      font-style: italic;
    }

    /* Interactive numeric FSM simulator */
    .sim-wrap {
      background: var(--card);
      border: 1px solid rgba(99, 102, 241, .15);
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      margin: 24px 0;
    }

    .sim-header {
      background: linear-gradient(135deg, #6366F1, #818CF8);
      padding: 14px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .sim-header h4 {
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 15px;
      color: white;
      margin: 0;
    }

    .sim-body {
      padding: 24px;
    }

    .sim-input-row {
      display: flex;
      gap: 10px;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 16px;
    }

    .sim-input {
      font-family: 'JetBrains Mono', monospace;
      font-size: 14px;
      padding: 8px 14px;
      border: 1.5px solid rgba(99, 102, 241, .3);
      border-radius: 8px;
      outline: none;
      flex: 1;
      min-width: 160px;
      color: var(--text);
      background: var(--bg);
    }

    .sim-input:focus {
      border-color: var(--purple);
    }

    .sim-btn {
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

    .sim-btn:hover {
      background: var(--purple-d);
    }

    .sim-btn.reset {
      background: transparent;
      border: 1.5px solid rgba(99, 102, 241, .3);
      color: var(--purple);
    }

    .sim-btn.reset:hover {
      background: var(--purple-s);
    }

    .sim-tape {
      display: flex;
      gap: 5px;
      flex-wrap: wrap;
      margin: 12px 0;
    }

    .sim-char {
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

    .sim-char.read {
      border-color: var(--purple);
      color: var(--purple);
      background: var(--purple-s);
    }

    .sim-char.current {
      border-color: var(--amber);
      color: #92400E;
      background: #FEF3C7;
      transform: scale(1.1);
    }

    .sim-state-row {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 8px 0;
      font-size: 14px;
    }

    .sim-state-bubble {
      padding: 5px 16px;
      border-radius: 20px;
      font-family: 'Syne', sans-serif;
      font-weight: 700;
      font-size: 14px;
      border: 2px solid;
      transition: all .3s;
    }

    .sim-vars {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 8px;
      margin: 12px 0;
    }

    .var-box {
      background: var(--code-bg);
      border-radius: 8px;
      padding: 8px 10px;
      text-align: center;
    }

    .var-box .vb-name {
      font-size: 10px;
      color: rgba(205, 214, 244, .45);
      font-family: 'JetBrains Mono', monospace;
      margin-bottom: 2px;
    }

    .var-box .vb-val {
      font-family: 'JetBrains Mono', monospace;
      font-size: 15px;
      font-weight: 700;
      color: #CDD6F4;
      transition: color .3s;
    }

    .var-box.changed .vb-val {
      color: #FAB387;
    }

    .sim-action-label {
      font-size: 12px;
      color: var(--amber);
      font-family: 'JetBrains Mono', monospace;
      margin-top: 4px;
      min-height: 18px;
    }

    .sim-result {
      margin-top: 14px;
      padding: 12px 18px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 600;
      display: none;
    }

    .sim-result.show {
      display: block;
    }

    .sim-result.accepted {
      background: #F0FDF4;
      color: #065F46;
      border: 1.5px solid var(--green);
    }

    .sim-result.rejected {
      background: #FFF1F2;
      color: #9F1239;
      border: 1.5px solid var(--red);
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

    @media(max-width:768px) {
      .note-container {
        grid-template-columns: 1fr;
      }

      .toc {
        position: static;
      }

      .fsm-body,
      .action-grid {
        grid-template-columns: 1fr;
      }

      .sim-vars {
        grid-template-columns: repeat(3, 1fr);
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
        <li><a href="#overview" class="toc-link">2.2 Overview</a></li>
        <li><a href="#array" class="toc-link">2.2.0 Array Implementation</a></li>
        <li><a href="#fig24" class="toc-link">2.2.1 FSM Examples</a></li>
        <li><a href="#fig24" class="toc-link sub">↳ Fig 2.4 Identifiers</a></li>
        <li><a href="#fig25" class="toc-link sub">↳ Fig 2.5 Numeric Constants</a></li>
        <li><a href="#fig26" class="toc-link sub">↳ Fig 2.6 Keyword Recogniser</a></li>
        <li><a href="#actions" class="toc-link">2.2.2 Actions for FSMs</a></li>
        <li><a href="#fig27" class="toc-link sub">↳ Fig 2.7 Parity Bit Generator</a></li>
        <li><a href="#sp22" class="toc-link">Sample Problem 2.2</a></li>
        <li><a href="#sp22-trace" class="toc-link sub">↳ Trace: 46.73e-21</a></li>
        <li><a href="#sim" class="toc-link">Interactive Simulator</a></li>
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
        <div class="chapter-tag">📘 Chapter 2 · Section 2.2</div>
        <h1>Implementation with Finite State Machines</h1>
        <div class="metadata">
          <span>⏱ 18 min read</span>
          <span>🎯 Intermediate</span>
          <span>⚙️ FSM + Actions</span>
        </div>
      </header>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 2.2 Overview                                   -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="overview">
        <h2><span class="sec-icon">⚙️</span> 2.2 Overview</h2>
        <p>
          Finite state machines can be used to <strong>simplify lexical analysis</strong>. This section
          covers:
        </p>
        <ol>
          <li>How an FSM is implemented as a <strong>2D array</strong> in code</li>
          <li>FSM examples for real lexical analysis problems: <strong>identifiers</strong>, <strong>numeric constants</strong>, and <strong>keywords</strong></li>
          <li>How to add <strong>actions</strong> (function calls) to FSM transitions to build symbol tables, convert numeric constants, and emit tokens</li>
        </ol>
        <div class="note-box pro">
          <span class="box-icon">🧩</span>
          <div>
            <strong>Key idea:</strong> A finite state machine is not just a language recogniser — it can <em>do work</em> as it transitions. By attaching a function to each arc, every character read can trigger processing (e.g. accumulate a number, store an identifier, emit a token).
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 2.2.0 Array Implementation                    -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="array">
        <h2><span class="sec-icon">🗃️</span> 2.2.0 Array Implementation of an FSM</h2>
        <p>
          An FSM can be implemented very simply as a <strong>2D integer array</strong>: one row per state,
          one column per possible input symbol. Each cell holds the <em>next state</em> for that
          (current state, input symbol) pair — identical in structure to the table form of an FSM.
        </p>
        <p>
          Input characters may need to be <strong>coded as integers</strong> to serve as array indices,
          depending on the implementation language.
        </p>

        <div class="code-wrap">
          <div class="code-header">
            <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
            <span class="code-lang">Java — FSM array simulation</span>
            <button class="copy-btn" onclick="copyBlock('fsm-array-code',this)">Copy</button>
          </div>
          <pre><code id="fsm-array-code"><span class="typ">boolean</span>[] accept = <span class="kw">new</span> <span class="typ">boolean</span>[STATES];
<span class="typ">int</span>[][]   fsm    = <span class="kw">new</span> <span class="typ">int</span>[STATES][INPUTS]; <span class="cm">// state table</span>
<span class="cm">// initialize table here ...</span>

<span class="typ">int</span> inp   = <span class="num">0</span>; <span class="cm">// input symbol (0 .. INPUTS)</span>
<span class="typ">int</span> state = <span class="num">0</span>; <span class="cm">// starting state</span>

<span class="kw">try</span> {
    inp = System.in.read() - <span class="str">'0'</span>; <span class="cm">// read char, convert to int</span>
    <span class="kw">while</span> (inp >= <span class="num">0</span> &amp;&amp; inp &lt; INPUTS) {
        state = fsm[state][inp];           <span class="cm">// next state</span>
        inp   = System.in.read() - <span class="str">'0'</span>; <span class="cm">// next input</span>
    }
} <span class="kw">catch</span> (IOException ioe) {
    System.out.println(<span class="str">"IO error "</span> + ioe);
}

<span class="kw">if</span> (accept[state])
    System.out.println(<span class="str">"Accepted"</span>);
<span class="kw">else</span>
    System.out.println(<span class="str">"Rejected"</span>);</code></pre>
        </div>

        <div class="note-box tip">
          <span class="box-icon">💡</span>
          <div>
            <strong>How it works:</strong> The loop reads one input symbol at a time and looks up
            <code>fsm[state][inp]</code> to get the next state. When input ends, check
            <code>accept[state]</code> — if <code>true</code>, the string is accepted; otherwise rejected.
            No backtracking — each symbol is read exactly once.
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 2.2.1 FSM Examples                            -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="fig24">
        <h2><span class="sec-icon">🔤</span> 2.2.1 FSM Examples for Lexical Analysis</h2>

        <!-- Figure 2.4 — Identifiers -->
        <h3>Figure 2.4 — Accepting Identifiers (letter followed by letters/digits)</h3>
        <p>
          An identifier begins with a <strong>letter</strong> (<code>L</code> = a–z or A–Z) and is
          followed by any number of <strong>letters or digits</strong> (<code>D</code> = 0–9).
          All unspecified transitions go to the <strong>dead state</strong> (rejection).
        </p>
        <div class="note-box warn">
          <span class="box-icon">⚠️</span>
          <div>
            A preprocessor is needed to <strong>convert input characters to class tokens</strong>
            (L or D) before feeding them to this FSM. The FSM works on symbol classes, not raw ASCII values.
          </div>
        </div>

        <div class="fsm-wrap">
          <div class="fsm-header">
            <h4>Figure 2.4 — Identifier FSM</h4>
            <span style="font-size:12px;color:var(--purple);">Alphabet: {L = any letter, D = any digit}</span>
          </div>
          <div class="fsm-body">
            <div class="fsm-svg-panel">
              <svg width="280" height="140" viewBox="0 0 280 140">
                <defs>
                  <marker id="a4" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
                    <path d="M0,0 L0,6 L8,3 z" fill="#6366F1" opacity=".7" />
                  </marker>
                </defs>
                <!-- start arrow -->
                <line x1="8" y1="70" x2="40" y2="70" stroke="#6366F1" stroke-width="1.5" opacity=".6" marker-end="url(#a4)" />
                <!-- S→A on L -->
                <line x1="68" y1="70" x2="128" y2="70" stroke="#6366F1" stroke-width="1.5" opacity=".6" marker-end="url(#a4)" />
                <text x="98" y="63" text-anchor="middle" fill="#A5B4FC" font-size="12" font-family="JetBrains Mono">L</text>
                <!-- A self-loop L,D -->
                <path d="M152 50 Q152 26 152 36" stroke="#6366F1" stroke-width="1.5" fill="none" opacity=".6" marker-end="url(#a4)" />
                <text x="164" y="26" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">L,D</text>
                <!-- S→dead on D (shown as label) -->
                <line x1="55" y1="88" x2="55" y2="120" stroke="#EF4444" stroke-width="1.2" opacity=".5" marker-end="url(#a4)" />
                <text x="30" y="112" fill="#F87171" font-size="10" font-family="DM Sans">D→dead</text>

                <!-- States -->
                <circle cx="55" cy="70" r="22" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.8" />
                <text x="55" y="75" text-anchor="middle" fill="#A5B4FC" font-size="13" font-weight="700" font-family="Syne">S</text>
                <!-- A accepting -->
                <circle cx="152" cy="70" r="22" fill="rgba(16,185,129,.08)" stroke="#10B981" stroke-width="1.8" />
                <circle cx="152" cy="70" r="17" fill="none" stroke="#10B981" stroke-width="1.2" />
                <text x="152" y="75" text-anchor="middle" fill="#10B981" font-size="13" font-weight="700" font-family="Syne">A</text>
                <!-- dead -->
                <rect x="218" y="56" width="46" height="28" rx="6" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.2" opacity=".6" />
                <text x="241" y="75" text-anchor="middle" fill="#F87171" font-size="11" font-family="Syne" font-weight="700">dead</text>
                <line x1="174" y1="70" x2="218" y2="70" stroke="#EF4444" stroke-width="1.2" opacity=".5" marker-end="url(#a4)" />
                <text x="196" y="63" text-anchor="middle" fill="#F87171" font-size="10" font-family="JetBrains Mono">other</text>

                <text x="55" y="108" text-anchor="middle" fill="#6C7086" font-size="10" font-family="DM Sans">start</text>
                <text x="152" y="108" text-anchor="middle" fill="#10B981" font-size="10" font-family="DM Sans">accept</text>
              </svg>
            </div>
            <div class="fsm-explain-panel">
              <h5>States & Transitions</h5>
              <table style="width:100%;border-collapse:collapse;font-family:'JetBrains Mono',monospace;font-size:13px;">
                <thead>
                  <tr style="background:var(--purple-s);">
                    <th style="padding:7px 12px;color:var(--purple);font-size:11px;">State</th>
                    <th style="padding:7px 12px;color:var(--purple);font-size:11px;">L</th>
                    <th style="padding:7px 12px;color:var(--purple);font-size:11px;">D</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td style="padding:7px 12px;font-weight:700;color:var(--purple);">→ S</td>
                    <td style="padding:7px 12px;color:var(--muted);">A</td>
                    <td style="padding:7px 12px;color:#F87171;">dead</td>
                  </tr>
                  <tr>
                    <td style="padding:7px 12px;font-weight:700;color:#10B981;">* A</td>
                    <td style="padding:7px 12px;color:var(--muted);">A</td>
                    <td style="padding:7px 12px;color:var(--muted);">A</td>
                  </tr>
                </tbody>
              </table>
              <div class="note-box key" style="margin-top:12px;font-size:13px;">
                <span class="box-icon">✅</span>
                <div>Accepts: <code>x</code>, <code>x33</code>, <code>total</code>, <code>myVar</code><br>Rejects: <code>3abc</code> (starts with digit)</div>
              </div>
            </div>
          </div>
          <div class="fsm-caption">A string of one or more characters where the <em>first</em> is a letter and the <em>rest</em> are letters or digits. All unspecified transitions go to the dead state (rejection).</div>
        </div>

        <!-- Figure 2.5 — Numeric Constants -->
        <h3 id="fig25">Figure 2.5 — Accepting Numeric Constants</h3>
        <p>
          This FSM accepts integers and floating-point numbers, including scientific notation like
          <code>2.5e+33</code>. Constants must <strong>begin with a digit</strong> — values like
          <code>.099</code> are not accepted by this machine (some languages allow this; Java does,
          Pascal does not). All unspecified transitions go to the dead state.
        </p>

        <div class="fsm-wrap">
          <div class="fsm-header">
            <h4>Figure 2.5 — Numeric Constant FSM</h4>
            <span style="font-size:12px;color:var(--purple);">D=digit · E=e or E · .+- as shown</span>
          </div>
          <div class="fsm-body">
            <div class="fsm-svg-panel">
              <svg width="320" height="200" viewBox="0 0 320 200">
                <defs>
                  <marker id="a5" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
                    <path d="M0,0 L0,6 L8,3 z" fill="#6366F1" opacity=".7" />
                  </marker>
                </defs>
                <!-- start arrow -->
                <line x1="4" y1="60" x2="32" y2="60" stroke="#6366F1" stroke-width="1.5" opacity=".6" marker-end="url(#a5)" />
                <!-- q0→q1 on D -->
                <line x1="58" y1="60" x2="98" y2="60" stroke="#6366F1" stroke-width="1.5" opacity=".6" marker-end="url(#a5)" />
                <text x="78" y="53" text-anchor="middle" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">D</text>
                <!-- q1 self D -->
                <path d="M118 42 Q118 20 118 30" stroke="#6366F1" stroke-width="1.5" fill="none" opacity=".6" marker-end="url(#a5)" />
                <text x="130" y="20" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">D</text>
                <!-- q1→q2 on . -->
                <line x1="138" y1="60" x2="178" y2="60" stroke="#6366F1" stroke-width="1.5" opacity=".6" marker-end="url(#a5)" />
                <text x="158" y="53" text-anchor="middle" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">.</text>
                <!-- q2→q3 on D -->
                <line x1="198" y1="60" x2="238" y2="60" stroke="#6366F1" stroke-width="1.5" opacity=".6" marker-end="url(#a5)" />
                <text x="218" y="53" text-anchor="middle" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">D</text>
                <!-- q3 self D -->
                <path d="M258 42 Q258 20 258 30" stroke="#6366F1" stroke-width="1.5" fill="none" opacity=".6" marker-end="url(#a5)" />
                <text x="270" y="20" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">D</text>
                <!-- q1→q4 on E  (down) -->
                <line x1="118" y1="78" x2="118" y2="118" stroke="#6366F1" stroke-width="1.5" opacity=".6" marker-end="url(#a5)" />
                <text x="126" y="104" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">E</text>
                <!-- q3→q4 on E -->
                <line x1="258" y1="78" x2="178" y2="128" stroke="#6366F1" stroke-width="1.5" opacity=".6" marker-end="url(#a5)" />
                <text x="228" y="110" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">E</text>
                <!-- q4→q5 on +- -->
                <line x1="158" y1="138" x2="118" y2="138" stroke="#6366F1" stroke-width="1.5" opacity=".6" marker-end="url(#a5)" />
                <text x="138" y="152" text-anchor="middle" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">+,-</text>
                <!-- q4→q6 on D (skip sign) -->
                <line x1="158" y1="148" x2="78" y2="168" stroke="#6366F1" stroke-width="1.5" opacity=".5" marker-end="url(#a5)" />
                <text x="105" y="165" fill="#A5B4FC" font-size="10" font-family="JetBrains Mono">D</text>
                <!-- q5→q6 on D -->
                <line x1="98" y1="138" x2="58" y2="168" stroke="#6366F1" stroke-width="1.5" opacity=".6" marker-end="url(#a5)" />
                <text x="64" y="158" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">D</text>
                <!-- q6 self D -->
                <path d="M42 155 Q20 140 32 152" stroke="#6366F1" stroke-width="1.5" fill="none" opacity=".6" marker-end="url(#a5)" />
                <text x="6" y="144" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">D</text>

                <!-- States -->
                <circle cx="45" cy="60" r="18" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.8" />
                <text x="45" y="65" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">q0</text>
                <!-- q1 accepting -->
                <circle cx="118" cy="60" r="18" fill="rgba(16,185,129,.08)" stroke="#10B981" stroke-width="1.8" />
                <circle cx="118" cy="60" r="13" fill="none" stroke="#10B981" stroke-width="1.1" />
                <text x="118" y="65" text-anchor="middle" fill="#10B981" font-size="11" font-weight="700" font-family="Syne">q1</text>
                <circle cx="188" cy="60" r="18" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.8" />
                <text x="188" y="65" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">q2</text>
                <!-- q3 accepting -->
                <circle cx="258" cy="60" r="18" fill="rgba(16,185,129,.08)" stroke="#10B981" stroke-width="1.8" />
                <circle cx="258" cy="60" r="13" fill="none" stroke="#10B981" stroke-width="1.1" />
                <text x="258" y="65" text-anchor="middle" fill="#10B981" font-size="11" font-weight="700" font-family="Syne">q3</text>
                <circle cx="168" cy="128" r="18" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.8" />
                <text x="168" y="133" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">q4</text>
                <circle cx="105" cy="138" r="18" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.8" />
                <text x="105" y="143" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">q5</text>
                <!-- q6 accepting -->
                <circle cx="40" cy="174" r="18" fill="rgba(16,185,129,.08)" stroke="#10B981" stroke-width="1.8" />
                <circle cx="40" cy="174" r="13" fill="none" stroke="#10B981" stroke-width="1.1" />
                <text x="40" y="179" text-anchor="middle" fill="#10B981" font-size="11" font-weight="700" font-family="Syne">q6</text>
                <text x="45" y="46" text-anchor="middle" fill="#6C7086" font-size="9" font-family="DM Sans">start</text>
              </svg>
            </div>
            <div class="fsm-explain-panel">
              <h5>Accepting States</h5>
              <table style="width:100%;border-collapse:collapse;font-size:12px;font-family:'JetBrains Mono',monospace;">
                <thead>
                  <tr style="background:var(--purple-s);">
                    <th style="padding:6px 10px;color:var(--purple);">State</th>
                    <th style="padding:6px 10px;color:var(--purple);">Accepts</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td style="padding:6px 10px;font-weight:700;color:#10B981;">* q1</td>
                    <td style="padding:6px 10px;color:var(--muted);">integer: <code>124</code>, <code>0</code></td>
                  </tr>
                  <tr>
                    <td style="padding:6px 10px;font-weight:700;color:#10B981;">* q3</td>
                    <td style="padding:6px 10px;color:var(--muted);">float with decimal: <code>12.35</code></td>
                  </tr>
                  <tr>
                    <td style="padding:6px 10px;font-weight:700;color:#10B981;">* q6</td>
                    <td style="padding:6px 10px;color:var(--muted);">scientific: <code>1e5</code>, <code>2.5e+33</code></td>
                  </tr>
                </tbody>
              </table>
              <div class="note-box warn" style="margin-top:12px;font-size:13px;">
                <span class="box-icon">⚠️</span>
                <div>Constants must begin with a digit. <code>.099</code> is <strong>not accepted</strong> — would need extra states starting from a decimal point.</div>
              </div>
            </div>
          </div>
          <div class="fsm-caption">Accepts integers (q1), decimals (q3), and scientific notation (q6). All unspecified transitions lead to the dead/rejection state.</div>
        </div>

        <!-- Figure 2.6 — Keyword Recogniser -->
        <h3 id="fig26">Figure 2.6 — Keyword Recogniser</h3>
        <p>
          This FSM recognises the keywords <code>if</code>, <code>int</code>, <code>import</code>,
          <code>for</code>, <code>float</code>. It is <strong>not completely specified</strong> — in a
          real compiler it must also handle identifiers that are prefixes of keywords (like <code>i</code>,
          <code>fo</code>) or identifiers that contain keywords as prefixes (like <code>fork</code>).
        </p>
        <div class="fsm-wrap">
          <div class="fsm-header">
            <h4>Figure 2.6 — Keyword Recogniser for: if, int, import, for, float</h4>
          </div>
          <div class="fsm-body">
            <div class="fsm-svg-panel" style="padding:16px;">
              <svg width="300" height="220" viewBox="0 0 300 220">
                <defs>
                  <marker id="a6" markerWidth="7" markerHeight="7" refX="5" refY="3" orient="auto">
                    <path d="M0,0 L0,6 L7,3 z" fill="#6366F1" opacity=".65" />
                  </marker>
                </defs>
                <!-- start -->
                <line x1="4" y1="32" x2="26" y2="32" stroke="#6366F1" stroke-width="1.3" opacity=".6" marker-end="url(#a6)" />
                <!-- root state -->
                <circle cx="42" cy="32" r="16" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.6" />
                <text x="42" y="37" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">·</text>

                <!-- branch i -->
                <line x1="58" y1="24" x2="92" y2="14" stroke="#6366F1" stroke-width="1.3" opacity=".6" marker-end="url(#a6)" />
                <text x="72" y="12" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">i</text>
                <circle cx="106" cy="12" r="14" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.4" />
                <text x="106" y="17" text-anchor="middle" fill="#A5B4FC" font-size="10" font-family="Syne">i</text>

                <!-- i→f (if) -->
                <line x1="120" y1="12" x2="155" y2="12" stroke="#6366F1" stroke-width="1.3" opacity=".6" marker-end="url(#a6)" />
                <text x="137" y="8" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">f</text>
                <circle cx="168" cy="12" r="14" fill="rgba(16,185,129,.08)" stroke="#10B981" stroke-width="1.4" />
                <circle cx="168" cy="12" r="10" fill="none" stroke="#10B981" stroke-width="1" />
                <text x="168" y="17" text-anchor="middle" fill="#10B981" font-size="10" font-weight="700" font-family="Syne">if</text>

                <!-- i→n (int) -->
                <line x1="115" y1="20" x2="155" y2="44" stroke="#6366F1" stroke-width="1.3" opacity=".6" marker-end="url(#a6)" />
                <text x="130" y="38" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">n</text>
                <circle cx="168" cy="50" r="14" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.4" />
                <text x="168" y="55" text-anchor="middle" fill="#A5B4FC" font-size="10" font-family="Syne">in</text>

                <!-- in→t (int) -->
                <line x1="182" y1="50" x2="216" y2="50" stroke="#6366F1" stroke-width="1.3" opacity=".6" marker-end="url(#a6)" />
                <text x="199" y="44" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">t</text>
                <circle cx="230" cy="50" r="14" fill="rgba(16,185,129,.08)" stroke="#10B981" stroke-width="1.4" />
                <circle cx="230" cy="50" r="10" fill="none" stroke="#10B981" stroke-width="1" />
                <text x="230" y="55" text-anchor="middle" fill="#10B981" font-size="9" font-weight="700" font-family="Syne">int</text>

                <!-- i→m (import) -->
                <line x1="115" y1="24" x2="155" y2="80" stroke="#6366F1" stroke-width="1.3" opacity=".6" marker-end="url(#a6)" />
                <text x="122" y="66" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">m</text>
                <circle cx="168" cy="86" r="14" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.4" />
                <text x="168" y="91" text-anchor="middle" fill="#A5B4FC" font-size="9" font-family="Syne">im</text>
                <!-- im→p,o,r,t skipped for brevity → accepting -->
                <line x1="182" y1="86" x2="242" y2="86" stroke="#6366F1" stroke-width="1.3" opacity=".4" stroke-dasharray="3,2" marker-end="url(#a6)" />
                <text x="210" y="80" fill="#6C7086" font-size="9" font-family="JetBrains Mono">port</text>
                <circle cx="256" cy="86" r="14" fill="rgba(16,185,129,.08)" stroke="#10B981" stroke-width="1.4" />
                <circle cx="256" cy="86" r="10" fill="none" stroke="#10B981" stroke-width="1" />
                <text x="256" y="91" text-anchor="middle" fill="#10B981" font-size="8" font-weight="700" font-family="Syne">imp</text>

                <!-- branch f (for, float) -->
                <line x1="55" y1="38" x2="90" y2="120" stroke="#6366F1" stroke-width="1.3" opacity=".6" marker-end="url(#a6)" />
                <text x="62" y="100" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">f</text>
                <circle cx="104" cy="132" r="14" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.4" />
                <text x="104" y="137" text-anchor="middle" fill="#A5B4FC" font-size="10" font-family="Syne">f</text>

                <!-- f→o (for) -->
                <line x1="118" y1="132" x2="152" y2="132" stroke="#6366F1" stroke-width="1.3" opacity=".6" marker-end="url(#a6)" />
                <text x="135" y="126" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">o</text>
                <circle cx="166" cy="132" r="14" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.4" />
                <text x="166" y="137" text-anchor="middle" fill="#A5B4FC" font-size="10" font-family="Syne">fo</text>

                <!-- fo→r (for) -->
                <line x1="180" y1="132" x2="214" y2="132" stroke="#6366F1" stroke-width="1.3" opacity=".6" marker-end="url(#a6)" />
                <text x="197" y="126" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">r</text>
                <circle cx="228" cy="132" r="14" fill="rgba(16,185,129,.08)" stroke="#10B981" stroke-width="1.4" />
                <circle cx="228" cy="132" r="10" fill="none" stroke="#10B981" stroke-width="1" />
                <text x="228" y="137" text-anchor="middle" fill="#10B981" font-size="9" font-weight="700" font-family="Syne">for</text>

                <!-- f→l (float) -->
                <line x1="110" y1="144" x2="152" y2="170" stroke="#6366F1" stroke-width="1.3" opacity=".6" marker-end="url(#a6)" />
                <text x="118" y="168" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">l</text>
                <circle cx="166" cy="178" r="14" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.4" />
                <text x="166" y="183" text-anchor="middle" fill="#A5B4FC" font-size="9" font-family="Syne">fl</text>
                <!-- fl→oat → accepting -->
                <line x1="180" y1="178" x2="234" y2="178" stroke="#6366F1" stroke-width="1.3" opacity=".4" stroke-dasharray="3,2" marker-end="url(#a6)" />
                <text x="207" y="172" fill="#6C7086" font-size="9" font-family="JetBrains Mono">oat</text>
                <circle cx="248" cy="178" r="14" fill="rgba(16,185,129,.08)" stroke="#10B981" stroke-width="1.4" />
                <circle cx="248" cy="178" r="10" fill="none" stroke="#10B981" stroke-width="1" />
                <text x="248" y="183" text-anchor="middle" fill="#10B981" font-size="8" font-weight="700" font-family="Syne">flt</text>
              </svg>
            </div>
            <div class="fsm-explain-panel">
              <h5>Recognised keywords</h5>
              <div style="display:flex;flex-direction:column;gap:6px;">
                <div style="display:flex;align-items:center;gap:8px;font-family:'JetBrains Mono',monospace;font-size:13px;"><span style="background:#F0FDF4;color:#065F46;padding:3px 10px;border-radius:6px;font-weight:700;">if</span><span style="color:var(--muted);font-size:12px;">→ 2 chars</span></div>
                <div style="display:flex;align-items:center;gap:8px;font-family:'JetBrains Mono',monospace;font-size:13px;"><span style="background:#F0FDF4;color:#065F46;padding:3px 10px;border-radius:6px;font-weight:700;">int</span><span style="color:var(--muted);font-size:12px;">→ 3 chars</span></div>
                <div style="display:flex;align-items:center;gap:8px;font-family:'JetBrains Mono',monospace;font-size:13px;"><span style="background:#F0FDF4;color:#065F46;padding:3px 10px;border-radius:6px;font-weight:700;">import</span><span style="color:var(--muted);font-size:12px;">→ 6 chars</span></div>
                <div style="display:flex;align-items:center;gap:8px;font-family:'JetBrains Mono',monospace;font-size:13px;"><span style="background:#F0FDF4;color:#065F46;padding:3px 10px;border-radius:6px;font-weight:700;">for</span><span style="color:var(--muted);font-size:12px;">→ 3 chars</span></div>
                <div style="display:flex;align-items:center;gap:8px;font-family:'JetBrains Mono',monospace;font-size:13px;"><span style="background:#F0FDF4;color:#065F46;padding:3px 10px;border-radius:6px;font-weight:700;">float</span><span style="color:var(--muted);font-size:12px;">→ 5 chars</span></div>
              </div>
              <div class="note-box warn" style="margin-top:12px;font-size:13px;">
                <span class="box-icon">⚠️</span>
                <div>Not completely specified. Must also handle <code>i</code>, <code>fo</code> (keyword prefixes) and <code>fork</code> (identifier with keyword prefix) — handled by adding actions.</div>
              </div>
            </div>
          </div>
          <div class="fsm-caption">Each keyword gets a unique accepting state. Dashed arcs represent multiple sequential character transitions condensed for clarity.</div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 2.2.2 Actions for FSMs                        -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="actions">
        <h2><span class="sec-icon">⚡</span> 2.2.2 Actions for Finite State Machines</h2>
        <p>
          Recognising words is not enough — lexical analysis also needs to <strong>build the symbol table</strong>,
          <strong>convert numeric constants</strong>, and <strong>emit tokens</strong>. We associate an
          <strong>action</strong> (a function call) with each state transition.
        </p>
        <p>
          This is implemented with a <strong>second 2D array of the same dimensions</strong> as the state
          transition array — an array of function references to invoke as each transition occurs. A
          transition labelled <code>i / P()</code> means: if the input is <code>i</code>, call
          <code>P()</code> before moving to the next state.
        </p>

        <!-- Figure 2.7 — Parity Bit Generator -->
        <h3 id="fig27">Figure 2.7 — Parity Bit Generator (FSM with action)</h3>
        <p>
          This machine generates a parity bit so that the input string + parity bit always has an
          <strong>even number of ones</strong>. The variable <code>parity</code> is initialised to 0
          and is complemented by the action function <code>P()</code> each time a <code>1</code> is read.
        </p>
        <div class="fsm-wrap">
          <div class="fsm-header">
            <h4>Figure 2.7 — Parity Bit Generator with Action</h4>
          </div>
          <div class="fsm-body">
            <div class="fsm-svg-panel">
              <svg width="220" height="140" viewBox="0 0 220 140">
                <defs>
                  <marker id="a7" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
                    <path d="M0,0 L0,6 L8,3 z" fill="#6366F1" opacity=".7" />
                  </marker>
                </defs>
                <line x1="6" y1="70" x2="36" y2="70" stroke="#6366F1" stroke-width="1.5" opacity=".6" marker-end="url(#a7)" />
                <path d="M55 50 Q55 26 55 36" stroke="#6366F1" stroke-width="1.5" fill="none" opacity=".5" marker-end="url(#a7)" />
                <text x="34" y="30" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">0</text>
                <path d="M75 70 Q110 45 140 70" stroke="#6366F1" stroke-width="1.5" fill="none" opacity=".6" marker-end="url(#a7)" />
                <text x="108" y="52" text-anchor="middle" fill="#FAB387" font-size="11" font-family="JetBrains Mono">1/P()</text>
                <path d="M140 84 Q110 110 75 84" stroke="#6366F1" stroke-width="1.5" fill="none" opacity=".6" marker-end="url(#a7)" />
                <text x="108" y="110" text-anchor="middle" fill="#FAB387" font-size="11" font-family="JetBrains Mono">1/P()</text>
                <path d="M162 50 Q162 26 162 36" stroke="#6366F1" stroke-width="1.5" fill="none" opacity=".5" marker-end="url(#a7)" />
                <text x="173" y="30" fill="#A5B4FC" font-size="11" font-family="JetBrains Mono">0</text>
                <!-- States -->
                <circle cx="55" cy="70" r="22" fill="rgba(16,185,129,.08)" stroke="#10B981" stroke-width="1.8" />
                <circle cx="55" cy="70" r="17" fill="none" stroke="#10B981" stroke-width="1.2" />
                <text x="55" y="75" text-anchor="middle" fill="#10B981" font-size="12" font-weight="700" font-family="Syne">A</text>
                <circle cx="162" cy="70" r="22" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.8" />
                <text x="162" y="75" text-anchor="middle" fill="#A5B4FC" font-size="12" font-weight="700" font-family="Syne">B</text>
                <text x="55" y="106" text-anchor="middle" fill="#10B981" font-size="10" font-family="DM Sans">even 1s</text>
                <text x="162" y="106" text-anchor="middle" fill="#6C7086" font-size="10" font-family="DM Sans">odd 1s</text>
              </svg>
            </div>
            <div class="fsm-explain-panel">
              <h5>Action function P()</h5>
              <div class="code-wrap" style="margin:0;">
                <pre><code><span class="kw">void</span> <span class="fn">P</span>() {
  <span class="kw">if</span> (parity == <span class="num">0</span>)
    parity = <span class="num">1</span>;
  <span class="kw">else</span>
    parity = <span class="num">0</span>;
}</code></pre>
              </div>
              <div class="note-box key" style="margin-top:12px;font-size:13px;">
                <span class="box-icon">💡</span>
                <div><code>P()</code> is only called on a <code>1</code> input — it flips the parity bit. A <code>0</code> input causes no action and no parity change.</div>
              </div>
            </div>
          </div>
          <div class="fsm-caption">State A = even number of 1s seen (start + accept). State B = odd number of 1s. The action P() fires on every 1 transition, flipping the running parity.</div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Sample Problem 2.2                            -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="sp22">
        <h2><span class="sec-icon">🧪</span> Sample Problem 2.2 — Numeric String to Floating Point</h2>

        <div class="sample-problem">
          <div class="sp-header"><span style="font-size:18px">📋</span>
            <h4>Sample Problem 2.2</h4>
          </div>
          <div class="sp-body">
            <p class="sp-question">Design a finite state machine, with actions, to read numeric strings and convert them to an appropriate internal numeric format (floating point).</p>

            <div class="sp-solution-label">✅ Solution — Global Variables &amp; Action Functions</div>
            <p style="font-size:13px;color:var(--muted);margin-bottom:14px;">
              Four global integer variables track the conversion state. Six action functions
              <code>P1</code>–<code>P6</code> are called on specific transitions:
            </p>

            <div class="action-grid">
              <div class="action-card">
                <div class="action-card-header"><span class="fn-name">P1()</span><span class="fn-role">First digit — initialise all variables</span></div>
                <pre><code><span class="typ">int</span> Places, N, D, Exp, Sign;

<span class="kw">void</span> <span class="fn">P1</span>() {
  Places = <span class="num">0</span>;  <span class="cm">// decimal places so far</span>
  N      = D;  <span class="cm">// D = current digit value</span>
  Exp    = <span class="num">0</span>;  <span class="cm">// default exponent = 0</span>
  Sign   = <span class="num">+1</span>; <span class="cm">// default sign = positive</span>
}</code></pre>
              </div>
              <div class="action-card">
                <div class="action-card-header"><span class="fn-name">P2()</span><span class="fn-role">Digit before decimal point</span></div>
                <pre><code><span class="kw">void</span> <span class="fn">P2</span>() {
  N = N * <span class="num">10</span> + D;
  <span class="cm">// accumulate integer part</span>
}</code></pre>
              </div>
              <div class="action-card">
                <div class="action-card-header"><span class="fn-name">P3()</span><span class="fn-role">Digit after decimal point</span></div>
                <pre><code><span class="kw">void</span> <span class="fn">P3</span>() {
  N      = N * <span class="num">10</span> + D;
  Places = Places + <span class="num">1</span>;
  <span class="cm">// count decimal places</span>
}</code></pre>
              </div>
              <div class="action-card">
                <div class="action-card-header"><span class="fn-name">P4()</span><span class="fn-role">Sign of exponent (+ or -)</span></div>
                <pre><code><span class="kw">void</span> <span class="fn">P4</span>() {
  <span class="kw">if</span> (input == <span class="str">'-'</span>)
    Sign = <span class="num">-1</span>;
  <span class="cm">// else Sign stays +1</span>
}</code></pre>
              </div>
              <div class="action-card">
                <div class="action-card-header"><span class="fn-name">P5()</span><span class="fn-role">First digit of exponent</span></div>
                <pre><code><span class="kw">void</span> <span class="fn">P5</span>() {
  Exp = D;
  <span class="cm">// first exponent digit</span>
}</code></pre>
              </div>
              <div class="action-card">
                <div class="action-card-header"><span class="fn-name">P6()</span><span class="fn-role">Further digits of exponent</span></div>
                <pre><code><span class="kw">void</span> <span class="fn">P6</span>() {
  Exp = Exp * <span class="num">10</span> + D;
  <span class="cm">// accumulate exponent</span>
}</code></pre>
              </div>
            </div>

            <div class="note-box key" style="margin-top:16px;">
              <span class="box-icon">✅</span>
              <div>
                <strong>Final result formula:</strong><br>
                <code style="font-size:14px;">Result = N × 10 ^ (Sign × Exp − Places)</code><br><br>
                where <code>Math.pow(x, y) = xʸ</code>. This correctly reconstructs any integer, decimal, or scientific notation constant.
              </div>
            </div>

            <div class="sp-solution-label" style="margin-top:20px;">FSM State Diagram (transitions with actions)</div>
            <table style="width:100%;border-collapse:collapse;font-family:'JetBrains Mono',monospace;font-size:13px;margin-top:10px;">
              <thead>
                <tr style="background:var(--code-bg);">
                  <th style="padding:8px 12px;color:#A5B4FC;font-size:11px;text-align:left;">From</th>
                  <th style="padding:8px 12px;color:#A5B4FC;font-size:11px;">Input</th>
                  <th style="padding:8px 12px;color:#A5B4FC;font-size:11px;">Action</th>
                  <th style="padding:8px 12px;color:#A5B4FC;font-size:11px;text-align:left;">To</th>
                  <th style="padding:8px 12px;color:#A5B4FC;font-size:11px;text-align:left;">Meaning</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="padding:7px 12px;color:var(--purple);">start</td>
                  <td style="padding:7px 12px;text-align:center;">D</td>
                  <td style="padding:7px 12px;text-align:center;color:#FAB387;">P1()</td>
                  <td style="padding:7px 12px;">q1*</td>
                  <td style="padding:7px 12px;color:var(--muted);font-family:'DM Sans',sans-serif;font-size:12px;">first digit, init vars</td>
                </tr>
                <tr>
                  <td style="padding:7px 12px;color:var(--purple);">q1</td>
                  <td style="padding:7px 12px;text-align:center;">D</td>
                  <td style="padding:7px 12px;text-align:center;color:#FAB387;">P2()</td>
                  <td style="padding:7px 12px;">q1*</td>
                  <td style="padding:7px 12px;color:var(--muted);font-family:'DM Sans',sans-serif;font-size:12px;">more integer digits</td>
                </tr>
                <tr>
                  <td style="padding:7px 12px;color:var(--purple);">q1</td>
                  <td style="padding:7px 12px;text-align:center;">.</td>
                  <td style="padding:7px 12px;text-align:center;color:var(--muted);">—</td>
                  <td style="padding:7px 12px;">q2</td>
                  <td style="padding:7px 12px;color:var(--muted);font-family:'DM Sans',sans-serif;font-size:12px;">decimal point seen</td>
                </tr>
                <tr>
                  <td style="padding:7px 12px;color:var(--purple);">q2</td>
                  <td style="padding:7px 12px;text-align:center;">D</td>
                  <td style="padding:7px 12px;text-align:center;color:#FAB387;">P3()</td>
                  <td style="padding:7px 12px;">q3*</td>
                  <td style="padding:7px 12px;color:var(--muted);font-family:'DM Sans',sans-serif;font-size:12px;">fractional digits, count Places</td>
                </tr>
                <tr>
                  <td style="padding:7px 12px;color:var(--purple);">q3</td>
                  <td style="padding:7px 12px;text-align:center;">D</td>
                  <td style="padding:7px 12px;text-align:center;color:#FAB387;">P3()</td>
                  <td style="padding:7px 12px;">q3*</td>
                  <td style="padding:7px 12px;color:var(--muted);font-family:'DM Sans',sans-serif;font-size:12px;">more fractional digits</td>
                </tr>
                <tr>
                  <td style="padding:7px 12px;color:var(--purple);">q1 or q3</td>
                  <td style="padding:7px 12px;text-align:center;">E</td>
                  <td style="padding:7px 12px;text-align:center;color:var(--muted);">—</td>
                  <td style="padding:7px 12px;">q4</td>
                  <td style="padding:7px 12px;color:var(--muted);font-family:'DM Sans',sans-serif;font-size:12px;">start of exponent</td>
                </tr>
                <tr>
                  <td style="padding:7px 12px;color:var(--purple);">q4</td>
                  <td style="padding:7px 12px;text-align:center;">+,-</td>
                  <td style="padding:7px 12px;text-align:center;color:#FAB387;">P4()</td>
                  <td style="padding:7px 12px;">q5</td>
                  <td style="padding:7px 12px;color:var(--muted);font-family:'DM Sans',sans-serif;font-size:12px;">set Sign if -</td>
                </tr>
                <tr>
                  <td style="padding:7px 12px;color:var(--purple);">q4 or q5</td>
                  <td style="padding:7px 12px;text-align:center;">D</td>
                  <td style="padding:7px 12px;text-align:center;color:#FAB387;">P5()</td>
                  <td style="padding:7px 12px;">q6*</td>
                  <td style="padding:7px 12px;color:var(--muted);font-family:'DM Sans',sans-serif;font-size:12px;">first exponent digit</td>
                </tr>
                <tr>
                  <td style="padding:7px 12px;color:var(--purple);">q6</td>
                  <td style="padding:7px 12px;text-align:center;">D</td>
                  <td style="padding:7px 12px;text-align:center;color:#FAB387;">P6()</td>
                  <td style="padding:7px 12px;">q6*</td>
                  <td style="padding:7px 12px;color:var(--muted);font-family:'DM Sans',sans-serif;font-size:12px;">further exponent digits</td>
                </tr>
              </tbody>
            </table>
            <p style="font-size:12px;color:var(--muted);margin-top:6px;">* = accepting state. All unspecified transitions → dead state.</p>
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Sample Problem 2.2 — Trace                   -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="sp22-trace">
        <h2><span class="sec-icon">📊</span> Trace — Input: <code>46.73e-21</code></h2>
        <p>
          From Exercise 2.2 question 6: trace the values of <strong>N</strong>, <strong>Places</strong>,
          <strong>Sign</strong>, <strong>Exp</strong>, and <strong>D</strong> as the string
          <code>46.73e-21</code> is read character by character.
        </p>

        <table class="trace-table">
          <thead>
            <tr>
              <th>Step</th>
              <th>Char</th>
              <th>State</th>
              <th>Action</th>
              <th>D</th>
              <th>N</th>
              <th>Places</th>
              <th>Sign</th>
              <th>Exp</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>0</td>
              <td class="input-col">—</td>
              <td>start</td>
              <td class="fn-col">—</td>
              <td>—</td>
              <td>—</td>
              <td>—</td>
              <td>—</td>
              <td>—</td>
            </tr>
            <tr>
              <td>1</td>
              <td class="input-col">4</td>
              <td>q1</td>
              <td class="fn-col">P1()</td>
              <td class="changed">4</td>
              <td class="changed">4</td>
              <td class="changed">0</td>
              <td class="changed">+1</td>
              <td class="changed">0</td>
            </tr>
            <tr>
              <td>2</td>
              <td class="input-col">6</td>
              <td>q1</td>
              <td class="fn-col">P2()</td>
              <td class="changed">6</td>
              <td class="changed">46</td>
              <td>0</td>
              <td>+1</td>
              <td>0</td>
            </tr>
            <tr>
              <td>3</td>
              <td class="input-col">.</td>
              <td>q2</td>
              <td class="fn-col">—</td>
              <td>6</td>
              <td>46</td>
              <td>0</td>
              <td>+1</td>
              <td>0</td>
            </tr>
            <tr>
              <td>4</td>
              <td class="input-col">7</td>
              <td>q3</td>
              <td class="fn-col">P3()</td>
              <td class="changed">7</td>
              <td class="changed">467</td>
              <td class="changed">1</td>
              <td>+1</td>
              <td>0</td>
            </tr>
            <tr>
              <td>5</td>
              <td class="input-col">3</td>
              <td>q3</td>
              <td class="fn-col">P3()</td>
              <td class="changed">3</td>
              <td class="changed">4673</td>
              <td class="changed">2</td>
              <td>+1</td>
              <td>0</td>
            </tr>
            <tr>
              <td>6</td>
              <td class="input-col">e</td>
              <td>q4</td>
              <td class="fn-col">—</td>
              <td>3</td>
              <td>4673</td>
              <td>2</td>
              <td>+1</td>
              <td>0</td>
            </tr>
            <tr>
              <td>7</td>
              <td class="input-col">-</td>
              <td>q5</td>
              <td class="fn-col">P4()</td>
              <td>3</td>
              <td>4673</td>
              <td>2</td>
              <td class="changed">-1</td>
              <td>0</td>
            </tr>
            <tr>
              <td>8</td>
              <td class="input-col">2</td>
              <td>q6</td>
              <td class="fn-col">P5()</td>
              <td class="changed">2</td>
              <td>4673</td>
              <td>2</td>
              <td>-1</td>
              <td class="changed">2</td>
            </tr>
            <tr>
              <td>9</td>
              <td class="input-col">1</td>
              <td>q6</td>
              <td class="fn-col">P6()</td>
              <td class="changed">1</td>
              <td>4673</td>
              <td>2</td>
              <td>-1</td>
              <td class="changed">21</td>
            </tr>
          </tbody>
        </table>

        <div class="note-box key">
          <span class="box-icon">✅</span>
          <div>
            <strong>Final computation:</strong><br>
            <code>Result = N × 10^(Sign × Exp − Places)</code><br>
            <code>= 4673 × 10^(−1 × 21 − 2)</code><br>
            <code>= 4673 × 10^(−23)</code><br>
            <code>= 4.673 × 10^(−20)</code> &nbsp;≈ <strong>4.673e-20</strong>
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Interactive Simulator                         -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="sim">
        <h2><span class="sec-icon">▶️</span> Interactive Numeric FSM Simulator</h2>
        <p>
          Type a numeric constant and watch the FSM from Sample Problem 2.2 process it step by
          step — tracking states, action calls, and variable values live.
        </p>

        <div class="sim-wrap">
          <div class="sim-header">
            <h4>🔢 Numeric FSM + Actions Simulator</h4>
            <span style="font-size:12px;color:rgba(255,255,255,.7);">Based on Sample Problem 2.2</span>
          </div>
          <div class="sim-body">
            <div class="sim-input-row">
              <input class="sim-input" id="num-input" type="text" placeholder="e.g. 46.73e-21" value="46.73e-21" maxlength="20">
              <button class="sim-btn" onclick="runNumSim()">▶ Run</button>
              <button class="sim-btn reset" onclick="stepNumSim()" id="btn-step">Step →</button>
              <button class="sim-btn reset" onclick="resetNumSim()">↺ Reset</button>
            </div>

            <div class="sim-tape" id="num-tape"></div>

            <div class="sim-state-row">
              <span style="font-size:13px;color:var(--muted);">State:</span>
              <span class="sim-state-bubble" id="num-state" style="border-color:var(--purple);color:var(--purple);background:var(--purple-s);">start</span>
              <span class="sim-action-label" id="num-action"></span>
            </div>

            <div class="sim-vars">
              <div class="var-box" id="vb-n">
                <div class="vb-name">N</div>
                <div class="vb-val" id="vv-n">—</div>
              </div>
              <div class="var-box" id="vb-d">
                <div class="vb-name">D</div>
                <div class="vb-val" id="vv-d">—</div>
              </div>
              <div class="var-box" id="vb-p">
                <div class="vb-name">Places</div>
                <div class="vb-val" id="vv-p">—</div>
              </div>
              <div class="var-box" id="vb-s">
                <div class="vb-name">Sign</div>
                <div class="vb-val" id="vv-s">—</div>
              </div>
              <div class="var-box" id="vb-e">
                <div class="vb-name">Exp</div>
                <div class="vb-val" id="vv-e">—</div>
              </div>
            </div>

            <div class="sim-result" id="num-result"></div>
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Quiz                                          -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="quiz">
        <h2><span class="sec-icon">🧩</span> Check Your Knowledge</h2>
        <p>Test what you've learned about Section 2.2. Select the best answer for each question.</p>

        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span>
            <h4>Question 1</h4>
          </div>
          <div class="quiz-body">
            <p class="quiz-q">How is a finite state machine implemented as a data structure in code?</p>
            <div class="quiz-options" id="q1-opts">
              <div class="quiz-opt" onclick="answer(this,'q1',false)">
                <div class="opt-circle">A</div> As a linked list of state objects, each with a list of transitions
              </div>
              <div class="quiz-opt" onclick="answer(this,'q1',true)">
                <div class="opt-circle">B</div> As a 2D integer array with one row per state and one column per input symbol — each cell holds the next state
              </div>
              <div class="quiz-opt" onclick="answer(this,'q1',false)">
                <div class="opt-circle">C</div> As a stack where each push represents entering a new state
              </div>
              <div class="quiz-opt" onclick="answer(this,'q1',false)">
                <div class="opt-circle">D</div> As a recursive function that calls itself for each input symbol
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
            <p class="quiz-q">In Figure 2.4, why would <code>3abc</code> be rejected by the identifier FSM?</p>
            <div class="quiz-options" id="q2-opts">
              <div class="quiz-opt" onclick="answer(this,'q2',false)">
                <div class="opt-circle">A</div> Because it contains both letters and digits
              </div>
              <div class="quiz-opt" onclick="answer(this,'q2',true)">
                <div class="opt-circle">B</div> Because the first character is a digit (D), which from the start state transitions to the dead state — identifiers must begin with a letter
              </div>
              <div class="quiz-opt" onclick="answer(this,'q2',false)">
                <div class="opt-circle">C</div> Because <code>abc</code> is a keyword
              </div>
              <div class="quiz-opt" onclick="answer(this,'q2',false)">
                <div class="opt-circle">D</div> Because the string is too short
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
            <p class="quiz-q">In Sample Problem 2.2, what does the action function <code>P3()</code> do?</p>
            <div class="quiz-options" id="q3-opts">
              <div class="quiz-opt" onclick="answer(this,'q3',false)">
                <div class="opt-circle">A</div> Sets the sign of the exponent to negative
              </div>
              <div class="quiz-opt" onclick="answer(this,'q3',false)">
                <div class="opt-circle">B</div> Initialises N, Places, Exp, and Sign for the first digit
              </div>
              <div class="quiz-opt" onclick="answer(this,'q3',true)">
                <div class="opt-circle">C</div> Accumulates a digit into N after the decimal point and increments Places to count decimal places
              </div>
              <div class="quiz-opt" onclick="answer(this,'q3',false)">
                <div class="opt-circle">D</div> Reads the first digit of the exponent
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
            <p class="quiz-q">After processing <code>46.73e-21</code> through the numeric FSM, what are the final values of N, Places, Sign, and Exp?</p>
            <div class="quiz-options" id="q4-opts">
              <div class="quiz-opt" onclick="answer(this,'q4',false)">
                <div class="opt-circle">A</div> N=4673, Places=2, Sign=+1, Exp=21
              </div>
              <div class="quiz-opt" onclick="answer(this,'q4',true)">
                <div class="opt-circle">B</div> N=4673, Places=2, Sign=−1, Exp=21
              </div>
              <div class="quiz-opt" onclick="answer(this,'q4',false)">
                <div class="opt-circle">C</div> N=46, Places=73, Sign=−1, Exp=21
              </div>
              <div class="quiz-opt" onclick="answer(this,'q4',false)">
                <div class="opt-circle">D</div> N=4673, Places=4, Sign=−1, Exp=21
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
            <p class="quiz-q">How are actions (function calls) added to an FSM implementation?</p>
            <div class="quiz-options" id="q5-opts">
              <div class="quiz-opt" onclick="answer(this,'q5',false)">
                <div class="opt-circle">A</div> By modifying the state transition array so states can call themselves recursively
              </div>
              <div class="quiz-opt" onclick="answer(this,'q5',true)">
                <div class="opt-circle">B</div> By adding a second 2D array of the same dimensions containing function references — the corresponding function is called on each transition
              </div>
              <div class="quiz-opt" onclick="answer(this,'q5',false)">
                <div class="opt-circle">C</div> By embedding if-else chains inside each state's handler function
              </div>
              <div class="quiz-opt" onclick="answer(this,'q5',false)">
                <div class="opt-circle">D</div> By creating a separate FSM for each action that must be performed
              </div>
            </div>
            <div class="quiz-feedback" id="q5-fb"></div>
          </div>
        </div>

      </section>

      <div class="chapter-nav">
        <a href="ch2_s1.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Previous section</span>2.1 Lexical Tokens</div>
        </a>
        <a href="ch2_s3.php" class="nav-btn next">
          <div style="text-align:right"><span class="btn-sub">Next section</span>2.3 Lex &amp; Scanner Tools</div>
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
      tocLinks.forEach(l => {
        l.classList.toggle('active', l.getAttribute('href') === '#' + current);
      });
    });

    function copyBlock(id, btn) {
      navigator.clipboard.writeText(document.getElementById(id).innerText).then(() => {
        btn.textContent = 'Copied!';
        setTimeout(() => btn.textContent = 'Copy', 1800);
      });
    }

    // ── Numeric FSM Simulator ────────────────────────────────
    // States: 'start','q1','q2','q3','q4','q5','q6','dead'
    // Variables: N, D, Places, Sign, Exp
    let numVars = {
      N: null,
      D: null,
      Places: null,
      Sign: null,
      Exp: null
    };
    let numState = 'start';
    let numPos = 0;
    let numStr = '';
    let numDone = false;

    function isDigit(c) {
      return c >= '0' && c <= '9';
    }

    function isE(c) {
      return c === 'e' || c === 'E';
    }

    function charType(c) {
      if (isDigit(c)) return 'D';
      if (c === '.') return '.';
      if (isE(c)) return 'E';
      if (c === '+' || c === '-') return 'S';
      return 'other';
    }

    function numTransition(state, ct) {
      if (state === 'start' && ct === 'D') return {
        next: 'q1',
        action: 'P1'
      };
      if (state === 'q1' && ct === 'D') return {
        next: 'q1',
        action: 'P2'
      };
      if (state === 'q1' && ct === '.') return {
        next: 'q2',
        action: null
      };
      if (state === 'q1' && ct === 'E') return {
        next: 'q4',
        action: null
      };
      if (state === 'q2' && ct === 'D') return {
        next: 'q3',
        action: 'P3'
      };
      if (state === 'q3' && ct === 'D') return {
        next: 'q3',
        action: 'P3'
      };
      if (state === 'q3' && ct === 'E') return {
        next: 'q4',
        action: null
      };
      if (state === 'q4' && ct === 'S') return {
        next: 'q5',
        action: 'P4'
      };
      if (state === 'q4' && ct === 'D') return {
        next: 'q6',
        action: 'P5'
      };
      if (state === 'q5' && ct === 'D') return {
        next: 'q6',
        action: 'P5'
      };
      if (state === 'q6' && ct === 'D') return {
        next: 'q6',
        action: 'P6'
      };
      return {
        next: 'dead',
        action: null
      };
    }

    function doAction(name, ch) {
      const d = isDigit(ch) ? parseInt(ch) : null;
      if (d !== null) numVars.D = d;
      if (name === 'P1') {
        numVars.Places = 0;
        numVars.N = d;
        numVars.Exp = 0;
        numVars.Sign = 1;
      } else if (name === 'P2') {
        numVars.N = numVars.N * 10 + d;
      } else if (name === 'P3') {
        numVars.N = numVars.N * 10 + d;
        numVars.Places++;
      } else if (name === 'P4') {
        if (ch === '-') numVars.Sign = -1;
      } else if (name === 'P5') {
        numVars.Exp = d;
      } else if (name === 'P6') {
        numVars.Exp = numVars.Exp * 10 + d;
      }
    }

    const accepting = ['q1', 'q3', 'q6'];

    function resetNumSim() {
      numStr = document.getElementById('num-input').value.trim();
      numState = 'start';
      numPos = 0;
      numDone = false;
      numVars = {
        N: null,
        D: null,
        Places: null,
        Sign: null,
        Exp: null
      };
      renderNumTape();
      setStateBubble('start', false);
      document.getElementById('num-action').textContent = '';
      updateVarBoxes({});
      const res = document.getElementById('num-result');
      res.className = 'sim-result';
      res.textContent = '';
      document.getElementById('btn-step').disabled = false;
    }

    function renderNumTape() {
      const tape = document.getElementById('num-tape');
      tape.innerHTML = numStr.split('').map((c, i) => {
        let cls = 'sim-char';
        if (i < numPos) cls += ' read';
        if (i === numPos && numState !== 'start' && !numDone) cls += ' current';
        return `<div class="${cls}">${c}</div>`;
      }).join('');
    }

    function setStateBubble(state, accept) {
      const b = document.getElementById('num-state');
      b.textContent = state;
      if (state === 'dead') {
        b.style.color = '#EF4444';
        b.style.background = '#FFF1F2';
        b.style.borderColor = '#EF4444';
      } else if (accept) {
        b.style.color = '#10B981';
        b.style.background = '#F0FDF4';
        b.style.borderColor = '#10B981';
      } else {
        b.style.color = 'var(--purple)';
        b.style.background = 'var(--purple-s)';
        b.style.borderColor = 'var(--purple)';
      }
    }

    function updateVarBoxes(changed) {
      const map = {
        N: 'vb-n',
        D: 'vb-d',
        Places: 'vb-p',
        Sign: 'vb-s',
        Exp: 'vb-e'
      };
      const vmap = {
        N: 'vv-n',
        D: 'vv-d',
        Places: 'vv-p',
        Sign: 'vv-s',
        Exp: 'vv-e'
      };
      for (const k of ['N', 'D', 'Places', 'Sign', 'Exp']) {
        const el = document.getElementById(map[k]);
        const vv = document.getElementById(vmap[k]);
        el.classList.toggle('changed', !!changed[k]);
        const v = numVars[k];
        vv.textContent = v === null ? '—' : (k === 'Sign' ? (v === 1 ? '+1' : '-1') : v);
      }
    }

    function stepNumSim() {
      if (numDone) return;
      if (numState === 'start' && numPos === 0) {
        /* ready */ }
      if (numPos >= numStr.length) {
        finishNumSim();
        return;
      }
      const ch = numStr[numPos];
      const ct = charType(ch);
      const res = numTransition(numState, ct);
      const prevVars = {
        ...numVars
      };
      if (res.action) doAction(res.action, ch);
      numState = res.next;
      numPos++;
      renderNumTape();
      const acc = accepting.includes(numState);
      setStateBubble(numState, acc);
      document.getElementById('num-action').textContent = res.action ? `→ ${res.action}()` : '';
      const changed = {};
      for (const k of ['N', 'D', 'Places', 'Sign', 'Exp']) {
        if (numVars[k] !== prevVars[k]) changed[k] = true;
      }
      updateVarBoxes(changed);
      if (numState === 'dead' || numPos >= numStr.length) finishNumSim();
    }

    function finishNumSim() {
      numDone = true;
      document.getElementById('btn-step').disabled = true;
      const accepted = accepting.includes(numState);
      const res = document.getElementById('num-result');
      if (accepted) {
        const v = numVars;
        const result = v.N * Math.pow(10, v.Sign * v.Exp - v.Places);
        res.className = 'sim-result show accepted';
        res.innerHTML = `✅ <strong>ACCEPTED</strong> — Result = ${v.N} × 10^(${v.Sign > 0 ? '+':'-'}${v.Exp} − ${v.Places}) = <strong>${result.toExponential(4)}</strong>`;
      } else {
        res.className = 'sim-result show rejected';
        res.innerHTML = `❌ <strong>REJECTED</strong> — Input "${numStr}" is not a valid numeric constant (ended in dead state).`;
      }
    }

    function runNumSim() {
      resetNumSim();
      while (!numDone && numPos <= numStr.length) stepNumSim();
    }

    // initialise
    resetNumSim();

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
          if (o.getAttribute('onclick')?.includes('true')) {
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