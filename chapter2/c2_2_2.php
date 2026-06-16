<?php
session_start();
include '../dbconn.php';
include '../save_progress.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

$chapter = 2;
$page = 10;
$nextPage = "c2_3_0.php";

if (isset($_GET['complete']) && $_GET['complete'] == '10') {
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
  <title>Chapter 2.2 – CompileX</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../css/c2/c2_2.css">
</head>

<body>

  <?php include 'navbar_c2.php'; ?>

  <div class="note-container">

    <nav class="toc">
      <div class="toc-title">📖 Contents</div>
      <ul>
        <li><a href="#actions" class="toc-link">2.2.2 Actions for FSMs</a></li>
        <li><a href="#fig27" class="toc-link sub">↳ Fig 2.7 Parity Bit Generator</a></li>
        <li><a href="#sp22" class="toc-link">Sample Problem 2.2</a></li>
        <li><a href="#sp22-trace" class="toc-link sub">↳ Trace: 46.73e-21</a></li>
        <li><a href="#sim" class="toc-link">Interactive Simulator</a></li>
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

      <div class="chapter-nav">
        <a href="c2_2_1.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Previous page</span>2.2.1 Finite State Machines Examples</div>
        </a>
        <a href="?complete=10" class="nav-btn next">
          <div style="text-align:right"><span class="btn-sub">Next page</span>2.3.0 Lexical Tables</div>
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
        /* ready */
      }
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
  </script>

</body>

</html>