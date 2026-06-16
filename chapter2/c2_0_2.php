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
$page = 2;
$nextPage = "c2_0_3.php";

if (isset($_GET['complete']) && $_GET['complete'] == '2') {
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
  <title>Chapter 2.0 – CompileX</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

 <link rel="stylesheet" href="../css/c2/c2_0.css">
</head>

<body>

  <?php include 'navbar_c2.php'; ?>

  <div class="note-container">

    <!-- ── Sidebar TOC ─────────────────────────────────── -->
    <nav class="toc">
      <div class="toc-title">📖 Contents</div>
      <ul>
        <li><a href="#fsm" class="toc-link">2.0.2 Finite State Machines</a></li>
        <li><a href="#sp-a" class="toc-link sub">↳ Sample Problem (a)</a></li>
        <li><a href="#fsm-sim" class="toc-link sub">↳ FSM Simulator</a></li>
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
        <div class="chapter-tag">📘 Chapter 2 · Section 2.0</div>
        <h1>Lexical Analysis — Formal Languages</h1>
        <div class="metadata">
          <span>⏱ 20 min read</span>
          <span>🎯 Intermediate</span>
          <span>🔤 Automata Theory</span>
        </div>
      </header>

     
 

      <!-- ══════════════════════════════════════════════ -->
      <!-- 2.0.2 Finite State Machines                   -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="fsm">
        <h2><span class="sec-icon">⚙️</span> 2.0.2 Finite State Machines</h2>
        <p>
          For infinite or very large languages, describing the exact set of strings in English lacks
          precision. A <strong>finite state machine (FSM)</strong> — also called a <em>finite automaton</em> —
          gives a mathematically precise specification of a language.
        </p>

        <p>A finite state machine consists of:</p>
        <ol>
          <li>A <strong>finite set of states</strong>, one designated as the <em>starting state</em> and zero or more as <em>accepting states</em></li>
          <li>A <strong>state transition function</strong>: given a current state and an input symbol, it returns the next state</li>
        </ol>

        <div class="note-box pro">
          <span class="box-icon">🧩</span>
          <div>
            <strong>How it works:</strong> The machine starts in the starting state. It reads each input symbol one at a time, transitioning to new states. After the entire string is consumed, if the machine is in an <em>accepting state</em>, the string is <strong>accepted</strong>. Otherwise it is <strong>rejected</strong>. The set of all accepted strings forms a language.
          </div>
        </div>

        <h3>📐 Notation</h3>
        <table class="compare-table">
          <thead>
            <tr>
              <th>Symbol</th>
              <th>Meaning</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Circle</td>
              <td>A state</td>
            </tr>
            <tr>
              <td>Double circle</td>
              <td>An accepting state</td>
            </tr>
            <tr>
              <td>Arrow with no source</td>
              <td>Starting state</td>
            </tr>
            <tr>
              <td>Labelled arc between states</td>
              <td>Transition: if in this state and read this symbol, go to that state</td>
            </tr>
          </tbody>
        </table>

        <!-- Figure 2.1 -->
        <div class="fsm-wrap">
          <div class="fsm-header">
            <h4>Figure 2.1 — Strings beginning with 1 and ending with 0</h4>
            <span style="font-size:12px; color:var(--purple);">Alphabet: {0, 1}</span>
          </div>
          <div class="fsm-body">
            <div class="fsm-svg-panel">
              <svg width="300" height="180" viewBox="0 0 300 180">
                <defs>
                  <marker id="arr2" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
                    <path d="M0,0 L0,6 L8,3 z" fill="#6366F1" opacity=".7" />
                  </marker>
                </defs>
                <!-- start arrow -->
                <line x1="10" y1="90" x2="42" y2="90" stroke="#6366F1" stroke-width="1.5" opacity=".6" marker-end="url(#arr2)" />
                <!-- A→B on 1 -->
                <line x1="68" y1="90" x2="128" y2="90" stroke="#6366F1" stroke-width="1.5" opacity=".6" marker-end="url(#arr2)" />
                <text x="98" y="83" text-anchor="middle" fill="#A5B4FC" font-size="12" font-family="JetBrains Mono">1</text>
                <!-- A→A on 0 (self-loop) -->
                <path d="M 55 72 Q 55 48 55 55" stroke="#6366F1" stroke-width="1.5" fill="none" opacity=".5" marker-end="url(#arr2)" />
                <text x="33" y="52" fill="#A5B4FC" font-size="12" font-family="JetBrains Mono">0</text>
                <!-- B→B on 1 -->
                <path d="M 150 72 Q 150 48 150 55" stroke="#6366F1" stroke-width="1.5" fill="none" opacity=".5" marker-end="url(#arr2)" />
                <text x="160" y="50" fill="#A5B4FC" font-size="12" font-family="JetBrains Mono">1</text>
                <!-- B→C on 0 -->
                <line x1="170" y1="90" x2="228" y2="90" stroke="#6366F1" stroke-width="1.5" opacity=".6" marker-end="url(#arr2)" />
                <text x="199" y="83" text-anchor="middle" fill="#A5B4FC" font-size="12" font-family="JetBrains Mono">0</text>
                <!-- C→B on 1 -->
                <path d="M 240 75 Q 200 45 160 75" stroke="#6366F1" stroke-width="1.5" fill="none" opacity=".5" marker-end="url(#arr2)" />
                <text x="200" y="52" fill="#A5B4FC" font-size="12" font-family="JetBrains Mono">1</text>
                <!-- C→C on 0 -->
                <path d="M 255 72 Q 280 48 255 60" stroke="#6366F1" stroke-width="1.5" fill="none" opacity=".5" marker-end="url(#arr2)" />
                <text x="282" y="56" fill="#A5B4FC" font-size="12" font-family="JetBrains Mono">0</text>
                <!-- D self on 0,1 -->
                <line x1="55" y1="108" x2="55" y2="145" stroke="#6366F1" stroke-width="1.5" opacity=".6" marker-end="url(#arr2)" />
                <!-- wait - D state is not in fig 2.1, skip -->

                <!-- States -->
                <!-- A (start) -->
                <circle cx="55" cy="90" r="22" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.8" />
                <text x="55" y="95" text-anchor="middle" fill="#A5B4FC" font-size="13" font-weight="700" font-family="Syne">A</text>
                <!-- B -->
                <circle cx="150" cy="90" r="22" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.8" />
                <text x="150" y="95" text-anchor="middle" fill="#A5B4FC" font-size="13" font-weight="700" font-family="Syne">B</text>
                <!-- C (accepting) -->
                <circle cx="245" cy="90" r="22" fill="rgba(16,185,129,.08)" stroke="#10B981" stroke-width="1.8" />
                <circle cx="245" cy="90" r="17" fill="none" stroke="#10B981" stroke-width="1.2" />
                <text x="245" y="95" text-anchor="middle" fill="#10B981" font-size="13" font-weight="700" font-family="Syne">C</text>

                <!-- labels -->
                <text x="55" y="125" text-anchor="middle" fill="#6C7086" font-size="10" font-family="DM Sans">start</text>
                <text x="245" y="125" text-anchor="middle" fill="#10B981" font-size="10" font-family="DM Sans">accept</text>
              </svg>
            </div>
            <div class="fsm-table-panel">
              <p style="font-size:13px; color:var(--muted); margin-bottom:12px;"><strong>Table form</strong> — each cell shows next state:</p>
              <table class="fsm-table">
                <thead>
                  <tr>
                    <th>State</th>
                    <th>0</th>
                    <th>1</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="state-name starting">→ A</td>
                    <td>A</td>
                    <td>B</td>
                  </tr>
                  <tr>
                    <td class="state-name">B</td>
                    <td>C</td>
                    <td>B</td>
                  </tr>
                  <tr>
                    <td class="state-name accepting">* C</td>
                    <td>C</td>
                    <td>B</td>
                  </tr>
                </tbody>
              </table>
              <p style="font-size:12px; color:var(--muted); margin-top:10px;">→ = starting state &nbsp;|&nbsp; * = accepting state</p>
            </div>
          </div>
          <div class="fsm-caption">
            <strong>Accepts:</strong> Any string of 0s and 1s that <em>begins with 1</em> and <em>ends with 0</em>. Examples: 10, 100, 110, 1010. The machine is <strong>deterministic</strong> — exactly one transition per state per symbol.
          </div>
        </div>

        <!-- Figure 2.2 Even Parity -->
        <div class="fsm-wrap">
          <div class="fsm-header">
            <h4>Figure 2.2 — Even Parity Checker (even number of 1s)</h4>
            <span style="font-size:12px; color:var(--purple);">Alphabet: {0, 1}</span>
          </div>
          <div class="fsm-body">
            <div class="fsm-svg-panel">
              <svg width="220" height="160" viewBox="0 0 220 160">
                <defs>
                  <marker id="arr3" markerWidth="8" markerHeight="8" refX="6" refY="3" orient="auto">
                    <path d="M0,0 L0,6 L8,3 z" fill="#6366F1" opacity=".7" />
                  </marker>
                </defs>
                <!-- start arrow -->
                <line x1="8" y1="80" x2="42" y2="80" stroke="#6366F1" stroke-width="1.5" opacity=".6" marker-end="url(#arr3)" />
                <!-- A self-loop on 0 -->
                <path d="M55 58 Q55 34 55 44" stroke="#6366F1" stroke-width="1.5" fill="none" opacity=".5" marker-end="url(#arr3)" />
                <text x="33" y="38" fill="#A5B4FC" font-size="12" font-family="JetBrains Mono">0</text>
                <!-- A→B on 1 -->
                <path d="M75 80 Q110 55 140 80" stroke="#6366F1" stroke-width="1.5" fill="none" opacity=".6" marker-end="url(#arr3)" />
                <text x="110" y="62" text-anchor="middle" fill="#A5B4FC" font-size="12" font-family="JetBrains Mono">1</text>
                <!-- B→A on 1 -->
                <path d="M140 96 Q110 120 75 96" stroke="#6366F1" stroke-width="1.5" fill="none" opacity=".6" marker-end="url(#arr3)" />
                <text x="110" y="120" text-anchor="middle" fill="#A5B4FC" font-size="12" font-family="JetBrains Mono">1</text>
                <!-- B self-loop on 0 -->
                <path d="M162 58 Q162 34 162 44" stroke="#6366F1" stroke-width="1.5" fill="none" opacity=".5" marker-end="url(#arr3)" />
                <text x="174" y="38" fill="#A5B4FC" font-size="12" font-family="JetBrains Mono">0</text>
                <!-- States -->
                <!-- A accepting -->
                <circle cx="55" cy="80" r="22" fill="rgba(16,185,129,.08)" stroke="#10B981" stroke-width="1.8" />
                <circle cx="55" cy="80" r="17" fill="none" stroke="#10B981" stroke-width="1.2" />
                <text x="55" y="85" text-anchor="middle" fill="#10B981" font-size="13" font-weight="700" font-family="Syne">A</text>
                <!-- B -->
                <circle cx="162" cy="80" r="22" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.8" />
                <text x="162" y="85" text-anchor="middle" fill="#A5B4FC" font-size="13" font-weight="700" font-family="Syne">B</text>
                <text x="55" y="115" text-anchor="middle" fill="#10B981" font-size="10" font-family="DM Sans">start+accept</text>
                <text x="162" y="115" text-anchor="middle" fill="#6C7086" font-size="10" font-family="DM Sans">odd 1s</text>
              </svg>
            </div>
            <div class="fsm-table-panel">
              <p style="font-size:13px; color:var(--muted); margin-bottom:12px;"><strong>Table form:</strong></p>
              <table class="fsm-table">
                <thead>
                  <tr>
                    <th>State</th>
                    <th>0</th>
                    <th>1</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="state-name accepting">→* A</td>
                    <td>A</td>
                    <td>B</td>
                  </tr>
                  <tr>
                    <td class="state-name">B</td>
                    <td>B</td>
                    <td>A</td>
                  </tr>
                </tbody>
              </table>
              <div class="note-box key" style="margin-top:14px; font-size:13px;">
                <span class="box-icon">✅</span>
                <div>State A = even number of 1s seen so far (start = 0 ones = even ✓). State B = odd. A 0 never changes parity; a 1 always flips it.</div>
              </div>
            </div>
          </div>
          <div class="fsm-caption">
            <strong>Accepts:</strong> All strings of 0s and 1s containing an <em>even</em> number of 1s (including ε, which has zero 1s). This is called a <strong>parity checker</strong>.
          </div>
        </div>

        <div class="note-box pro">
          <span class="box-icon">🧩</span>
          <div>
            <strong>Deterministic FSM:</strong> For each state, there is <em>exactly one arc</em> leaving that state labelled by each possible input symbol. No ambiguity — every input leads to exactly one next state. We work only with <strong>deterministic finite state machines (DFSMs)</strong>.
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Sample Problem 2.0(a) — FSM design            -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="sp-a">
        <h2><span class="sec-icon">🧪</span> Sample Problem 2.0(a) — Designing FSMs</h2>

        <div class="sample-problem">
          <div class="sp-header"><span style="font-size:18px">📋</span>
            <h4>Sample Problem 2.0 (a) — Input alphabet: {0, 1}</h4>
          </div>
          <div class="sp-body">
            <p class="sp-question">Show a finite state machine in either state graph or table form for each of the following languages:</p>

            <!-- Part 1 -->
            <div class="sp-sub">1. Strings containing an odd number of zeros</div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px;">
              <div>
                <table class="fsm-table" style="width:100%;">
                  <thead>
                    <tr>
                      <th>State</th>
                      <th>0</th>
                      <th>1</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="state-name starting">→ A</td>
                      <td>B</td>
                      <td>A</td>
                    </tr>
                    <tr>
                      <td class="state-name accepting">* B</td>
                      <td>A</td>
                      <td>B</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="note-box key" style="margin:0;">
                <span class="box-icon">💡</span>
                <div><strong>Idea:</strong> A = even zeros so far, B = odd zeros. A 0 flips parity; a 1 keeps it. Accept state B = odd zeros seen.</div>
              </div>
            </div>

            <!-- Part 2 -->
            <div class="sp-sub">2. Strings containing three consecutive ones</div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px;">
              <div>
                <table class="fsm-table" style="width:100%;">
                  <thead>
                    <tr>
                      <th>State</th>
                      <th>0</th>
                      <th>1</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="state-name starting">→ A</td>
                      <td>A</td>
                      <td>B</td>
                    </tr>
                    <tr>
                      <td class="state-name">B</td>
                      <td>A</td>
                      <td>C</td>
                    </tr>
                    <tr>
                      <td class="state-name">C</td>
                      <td>A</td>
                      <td>D</td>
                    </tr>
                    <tr>
                      <td class="state-name accepting">* D</td>
                      <td>D</td>
                      <td>D</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="note-box key" style="margin:0;">
                <span class="box-icon">💡</span>
                <div><strong>Idea:</strong> Track consecutive 1s seen: A=0, B=1, C=2, D=3+. Once in D (three consecutive 1s found), stay there regardless of further input.</div>
              </div>
            </div>

            <!-- Part 3 -->
            <div class="sp-sub">3. Strings containing exactly three zeros</div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px;">
              <div>
                <table class="fsm-table" style="width:100%;">
                  <thead>
                    <tr>
                      <th>State</th>
                      <th>0</th>
                      <th>1</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="state-name starting">→ A</td>
                      <td>B</td>
                      <td>A</td>
                    </tr>
                    <tr>
                      <td class="state-name">B</td>
                      <td>C</td>
                      <td>B</td>
                    </tr>
                    <tr>
                      <td class="state-name">C</td>
                      <td>D</td>
                      <td>C</td>
                    </tr>
                    <tr>
                      <td class="state-name accepting">* D</td>
                      <td>E</td>
                      <td>D</td>
                    </tr>
                    <tr>
                      <td class="state-name">E</td>
                      <td>E</td>
                      <td>E</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="note-box key" style="margin:0;">
                <span class="box-icon">💡</span>
                <div><strong>Idea:</strong> Count zeros: A=0, B=1, C=2, D=3 (accept). If a 4th zero arrives → E (dead/trap state, never accepted again). 1s don't change zero count.</div>
              </div>
            </div>

            <!-- Part 4 -->
            <div class="sp-sub">4. Strings with odd zeros AND even ones</div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
              <div>
                <table class="fsm-table" style="width:100%;">
                  <thead>
                    <tr>
                      <th>State</th>
                      <th>0</th>
                      <th>1</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="state-name starting">→ A</td>
                      <td>B</td>
                      <td>C</td>
                    </tr>
                    <tr>
                      <td class="state-name accepting">* B</td>
                      <td>A</td>
                      <td>D</td>
                    </tr>
                    <tr>
                      <td class="state-name">C</td>
                      <td>D</td>
                      <td>A</td>
                    </tr>
                    <tr>
                      <td class="state-name">D</td>
                      <td>C</td>
                      <td>B</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="note-box key" style="margin:0;">
                <span class="box-icon">💡</span>
                <div><strong>Idea:</strong> Track (zero-parity, one-parity) pairs: A=(even,even), B=(odd,even)✓, C=(even,odd), D=(odd,odd). Accept only B = odd zeros + even ones.</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Interactive FSM Simulator                     -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="fsm-sim">
        <h2><span class="sec-icon">▶️</span> Interactive FSM Simulator</h2>
        <p>
          Choose a machine and type any string of 0s and 1s. The simulator will step through
          each character and show you whether the string is accepted or rejected.
        </p>

        <div class="sim-wrap">
          <div class="sim-header">
            <h4>🤖 FSM Simulator</h4>
            <span id="sim-machine-label" style="font-size:12px; color:rgba(255,255,255,.7);">Fig 2.1 — starts with 1, ends with 0</span>
          </div>
          <div class="sim-body">
            <div class="sim-controls">
              <select id="machine-select" style="padding:8px 12px; border-radius:8px; border:1.5px solid rgba(99,102,241,.3); font-family:'DM Sans',sans-serif; font-size:13px; color:var(--text); background:var(--bg); outline:none; cursor:pointer;" onchange="changeMachine()">
                <option value="fig21">Fig 2.1 — begins with 1, ends with 0</option>
                <option value="fig22">Fig 2.2 — even parity (even number of 1s)</option>
                <option value="sp1">SP(a).1 — odd number of zeros</option>
                <option value="sp2">SP(a).2 — three consecutive ones</option>
              </select>
              <input class="sim-input" id="sim-input" type="text" placeholder="Type 0s and 1s…" maxlength="20" oninput="resetSim()">
              <button class="sim-btn" onclick="runSim()">▶ Run</button>
              <button class="sim-btn reset" onclick="resetSim()">↺ Reset</button>
            </div>

            <div class="sim-state-row">
              <span style="font-size:13px; color:var(--muted);">Current state:</span>
              <span class="sim-state-bubble" id="sim-state-bubble" style="border-color:var(--purple); color:var(--purple); background:var(--purple-s);">—</span>
              <span style="font-size:13px; color:var(--muted);" id="sim-step-label"></span>
            </div>

            <div class="sim-tape" id="sim-tape"></div>

            <div id="sim-result" class="sim-result"></div>

            <div style="margin-top:12px; display:flex; gap:8px; flex-wrap:wrap;">
              <button class="sim-btn reset" onclick="stepSim()" id="btn-step" disabled style="flex:none;">Step →</button>
              <button class="sim-btn reset" onclick="autoPlay()" id="btn-auto" disabled style="flex:none;">⚡ Auto-play</button>
            </div>
          </div>
        </div>
      </section>

   

 
 
 
      <!-- Chapter nav -->
      <div class="chapter-nav">
        <a href="c2_0_1.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Previous Page</span>2.0.1 Language Elements</div>
        </a>
        <a href="?complete=2" class="nav-btn next">
          <div style="text-align:right"><span class="btn-sub">Next page</span>2.0.3 Regular Expressions</div>
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

    // ── FSM Simulator ────────────────────────────────────────
    const machines = {
      fig21: {
        label: 'Fig 2.1 — begins with 1, ends with 0',
        start: 'A',
        accept: ['C'],
        transitions: {
          A: {
            '0': 'A',
            '1': 'B'
          },
          B: {
            '0': 'C',
            '1': 'B'
          },
          C: {
            '0': 'C',
            '1': 'B'
          }
        }
      },
      fig22: {
        label: 'Fig 2.2 — even parity (even number of 1s)',
        start: 'A',
        accept: ['A'],
        transitions: {
          A: {
            '0': 'A',
            '1': 'B'
          },
          B: {
            '0': 'B',
            '1': 'A'
          }
        }
      },
      sp1: {
        label: 'SP(a).1 — odd number of zeros',
        start: 'A',
        accept: ['B'],
        transitions: {
          A: {
            '0': 'B',
            '1': 'A'
          },
          B: {
            '0': 'A',
            '1': 'B'
          }
        }
      },
      sp2: {
        label: 'SP(a).2 — three consecutive ones',
        start: 'A',
        accept: ['D'],
        transitions: {
          A: {
            '0': 'A',
            '1': 'B'
          },
          B: {
            '0': 'A',
            '1': 'C'
          },
          C: {
            '0': 'A',
            '1': 'D'
          },
          D: {
            '0': 'D',
            '1': 'D'
          }
        }
      }
    };

    let currentMachine = machines.fig21;
    let simState = null,
      simPos = 0,
      simStr = '';

    function changeMachine() {
      const sel = document.getElementById('machine-select').value;
      currentMachine = machines[sel];
      document.getElementById('sim-machine-label').textContent = currentMachine.label;
      resetSim();
    }

    function resetSim() {
      simState = null;
      simPos = 0;
      simStr = document.getElementById('sim-input').value.replace(/[^01]/g, '');
      document.getElementById('sim-input').value = simStr;
      renderTape();
      updateBubble('—', '#6B7280', '#F8F7FF', '#E5E7EB');
      document.getElementById('sim-step-label').textContent = '';
      const res = document.getElementById('sim-result');
      res.className = 'sim-result';
      res.textContent = '';
      document.getElementById('btn-step').disabled = simStr.length === 0;
      document.getElementById('btn-auto').disabled = simStr.length === 0;
    }

    function renderTape() {
      const tape = document.getElementById('sim-tape');
      tape.innerHTML = simStr.split('').map((c, i) => {
        let cls = 'sim-char';
        if (i < simPos) cls += ' read';
        if (i === simPos && simState !== null) cls += ' current';
        return `<div class="${cls}">${c}</div>`;
      }).join('') || '<span style="font-size:13px;color:var(--muted);">No input yet</span>';
    }

    function updateBubble(text, color, bg, border) {
      const b = document.getElementById('sim-state-bubble');
      b.textContent = text;
      b.style.color = color;
      b.style.background = bg;
      b.style.borderColor = border;
    }

    function stepSim() {
      if (simState === null) {
        simState = currentMachine.start;
        simPos = 0;
        renderTape();
        updateBubble(simState, '#6366F1', '#EEF2FF', '#6366F1');
        return;
      }
      if (simPos >= simStr.length) {
        finishSim();
        return;
      }
      const ch = simStr[simPos];
      const next = currentMachine.transitions[simState]?.[ch];
      if (!next) {
        updateBubble('ERR', '#EF4444', '#FFF1F2', '#EF4444');
        finishSim();
        return;
      }
      simState = next;
      simPos++;
      document.getElementById('sim-step-label').textContent = `Read "${ch}" → ${simState}`;
      renderTape();
      const accepted = currentMachine.accept.includes(simState);
      updateBubble(simState, accepted ? '#10B981' : '#6366F1', accepted ? '#F0FDF4' : '#EEF2FF', accepted ? '#10B981' : '#6366F1');
      if (simPos >= simStr.length) finishSim();
    }

    function finishSim() {
      const accepted = currentMachine.accept.includes(simState);
      const res = document.getElementById('sim-result');
      res.className = 'sim-result show ' + (accepted ? 'accepted' : 'rejected');
      res.innerHTML = accepted ?
        `✅ <strong>ACCEPTED</strong> — String "${simStr}" is in the language.` :
        `❌ <strong>REJECTED</strong> — String "${simStr}" is not in the language.`;
      document.getElementById('btn-step').disabled = true;
      document.getElementById('btn-auto').disabled = true;
    }

    function runSim() {
      simStr = document.getElementById('sim-input').value.replace(/[^01]/g, '');
      document.getElementById('sim-input').value = simStr;
      simState = currentMachine.start;
      simPos = 0;
      for (const ch of simStr) {
        const next = currentMachine.transitions[simState]?.[ch];
        if (!next) {
          simState = '__err__';
          break;
        }
        simState = next;
      }
      simPos = simStr.length;
      renderTape();
      finishSim();
      document.getElementById('sim-step-label').textContent = `Final state: ${simState}`;
    }

    function autoPlay() {
      simStr = document.getElementById('sim-input').value.replace(/[^01]/g, '');
      document.getElementById('sim-input').value = simStr;
      if (!simStr) return;
      simState = null;
      simPos = 0;
      const res = document.getElementById('sim-result');
      res.className = 'sim-result';
      res.textContent = '';
      renderTape();
      updateBubble('—', '#6B7280', '#F8F7FF', '#E5E7EB');
      document.getElementById('btn-step').disabled = true;
      document.getElementById('btn-auto').disabled = true;
      let i = -1;

      function next() {
        i++;
        if (i === 0) {
          simState = currentMachine.start;
          renderTape();
          updateBubble(simState, '#6366F1', '#EEF2FF', '#6366F1');
          setTimeout(next, 600);
          return;
        }
        if (i > simStr.length) {
          finishSim();
          return;
        }
        stepSim();
        if (i <= simStr.length) setTimeout(next, 600);
      }
      next();
    }

    // initialise
    resetSim();

  
  </script>

</body>

</html>