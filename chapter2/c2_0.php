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

  <?php include '../dashboard.php'; ?>

  <div class="note-container">

    <!-- ── Sidebar TOC ─────────────────────────────────── -->
    <nav class="toc">
      <div class="toc-title">📖 Contents</div>
      <ul>
        <li><a href="#intro" class="toc-link">Chapter 2 Intro</a></li>
        <li><a href="#lang-elem" class="toc-link">2.0.1 Language Elements</a></li>
        <li><a href="#fsm" class="toc-link">2.0.2 Finite State Machines</a></li>
        <li><a href="#sp-a" class="toc-link sub">↳ Sample Problem (a)</a></li>
        <li><a href="#fsm-sim" class="toc-link sub">↳ FSM Simulator</a></li>
        <li><a href="#regex" class="toc-link">2.0.3 Regular Expressions</a></li>
        <li><a href="#sp-b" class="toc-link sub">↳ Sample Problem (b)</a></li>
        <li><a href="#sp-c" class="toc-link sub">↳ Sample Problem (c)</a></li>
        <li><a href="#quiz" class="toc-link">Check Your Knowledge</a></li>
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
      <!-- Chapter 2 Intro                               -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="intro">
        <h2><span class="sec-icon">🔤</span> Chapter 2 — Lexical Analysis</h2>
        <p>
          Lexical analysis is the <strong>identification of words</strong> in the source program.
          These words — called <strong>tokens</strong> — are passed to subsequent phases of the compiler,
          each consisting of a <em>class</em> (what kind of word it is) and a <em>value</em> (the actual text).
        </p>
        <p>
          The lexical analysis phase can also begin building tables used later in compilation — the
          <strong>symbol table</strong> (identifiers) and a <strong>table of numeric constants</strong>
          are two examples constructed in this phase.
        </p>

        <div class="note-box tip">
          <span class="box-icon">💡</span>
          <div>
            <strong>Skip note:</strong> Before diving into lexical analysis implementation, we must cover
            the underlying theory — <strong>formal languages</strong> and <strong>automata theory</strong>.
            Students already familiar with regular expressions and finite automata can skip Section 2.0
            and jump directly to Section 2.1.
          </div>
        </div>

        <table class="compare-table">
          <thead>
            <tr>
              <th>Term</th>
              <th>Meaning</th>
              <th>Example</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Formal language</td>
              <td>A language specified precisely, amenable for use with computers</td>
              <td>Java syntax, Decaf syntax</td>
            </tr>
            <tr>
              <td>Natural language</td>
              <td>A language normally spoken by people</td>
              <td>English, Italian, Swahili</td>
            </tr>
            <tr>
              <td>Token class</td>
              <td>The type of a lexical item</td>
              <td>keyword, identifier, operator</td>
            </tr>
            <tr>
              <td>Token value</td>
              <td>The actual text of the lexical item</td>
              <td><code>while</code>, <code>sum</code>, <code>+</code></td>
            </tr>
          </tbody>
        </table>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 2.0.1 Language Elements                        -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="lang-elem">
        <h2><span class="sec-icon">📚</span> 2.0.1 Language Elements</h2>
        <p>
          Before defining a language we need three fundamental building blocks from discrete mathematics.
          Understanding the <strong>difference between a set and a string</strong> — and between the
          <strong>empty set and the null string</strong> — is critical.
        </p>

        <div class="def-grid">
          <div class="def-card">
            <div class="def-term">Set</div>
            <div class="def-sym">{ … }</div>
            <div class="def-desc">A collection of <em>unique</em> objects. Elements may be listed in any order; duplicates don't count twice. May be infinite. The empty set contains no elements.</div>
            <code class="def-ex">
              {boy, girl, animal}<br>
              = {girl, boy, animal, girl}<br>
              empty set: {} or φ
            </code>
          </div>
          <div class="def-card">
            <div class="def-term">String</div>
            <div class="def-sym">ε = null string</div>
            <div class="def-desc">A <em>list</em> of characters from a given alphabet. Elements need <em>not</em> be unique; <em>order matters</em>. "abc" ≠ "cba". The null string ε is a valid string of zero characters.</div>
            <code class="def-ex">
              "abc" ≠ "cba"<br>
              "abb" ≠ "ab"<br>
              null string: ε
            </code>
          </div>
          <div class="def-card">
            <div class="def-term">Language</div>
            <div class="def-sym">L = set of strings</div>
            <div class="def-desc">A (formal) language is a <em>set of strings</em> from a given alphabet. It can be finite or infinite. Each string in the language is a member of that language.</div>
            <code class="def-ex">
              {0, 10, 1011}<br>
              {ε, 0, 00, 000, …}<br>
              Java programs
            </code>
          </div>
        </div>

        <div class="note-box warn">
          <span class="box-icon">⚠️</span>
          <div>
            <strong>Critical distinction:</strong>
            <ul style="margin:8px 0 0 16px;">
              <li><strong>Empty set</strong> <code>{ }</code> or <code>φ</code> — a language containing <em>no strings</em></li>
              <li><strong>Null string</strong> <code>ε</code> — a string of <em>zero characters</em>; the set <code>{ε}</code> contains exactly one element (the null string) and is <em>not empty</em></li>
            </ul>
          </div>
        </div>

        <h3>📌 Examples of Languages from {0, 1}</h3>
        <table class="compare-table">
          <thead>
            <tr>
              <th>Language</th>
              <th>Finite?</th>
              <th>Contains ε?</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><code>{0, 10, 1011}</code></td>
              <td>✅ Finite</td>
              <td>No</td>
              <td>Just those 3 strings</td>
            </tr>
            <tr>
              <td><code>{}</code> (φ)</td>
              <td>✅ Finite</td>
              <td>No</td>
              <td>The empty language — no strings at all</td>
            </tr>
            <tr>
              <td><code>{ε, 0, 00, 000, …}</code></td>
              <td>❌ Infinite</td>
              <td>Yes</td>
              <td>All strings of zero or more zeros</td>
            </tr>
            <tr>
              <td>Even number of 1s</td>
              <td>❌ Infinite</td>
              <td>Yes (ε has 0 ones)</td>
              <td>All 0/1 strings with an even count of 1s</td>
            </tr>
          </tbody>
        </table>
      </section>

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

      <!-- ══════════════════════════════════════════════ -->
      <!-- 2.0.3 Regular Expressions                     -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="regex">
        <h2><span class="sec-icon">🔣</span> 2.0.3 Regular Expressions</h2>
        <p>
          Another method for specifying languages is <strong>regular expressions</strong> — formulas
          involving three operations on languages:
        </p>

        <div class="op-grid">
          <div class="op-card">
            <div class="op-name">Union</div>
            <div class="op-sym">+</div>
            <div class="op-desc">The set of all strings in <em>either</em> language. Since a language is a set, this is standard set union.</div>
            <code class="op-ex">{abc,ab,ba} + {ba,bb}<br>= {abc,ab,ba,bb}<br><br>L + {} = L</code>
          </div>
          <div class="op-card">
            <div class="op-name">Concatenation</div>
            <div class="op-sym">·</div>
            <div class="op-desc">Join each string in L₁ with each string in L₂. Note: L₁·L₂ ≠ L₂·L₁ in general.</div>
            <code class="op-ex">{ab,a,c} · {b,ε}<br>= {abb,ab,ab,a,cb,c}<br>= {abb,ab,a,cb,c}<br><br>s · ε = s &nbsp;|&nbsp; L · φ = φ</code>
          </div>
          <div class="op-card">
            <div class="op-name">Kleene Star</div>
            <div class="op-sym">*</div>
            <div class="op-desc">Zero or more concatenations of strings from L. L* = L⁰ ∪ L¹ ∪ L² ∪ … Always includes ε.</div>
            <code class="op-ex">L⁰ = {ε}<br>L¹ = L<br>L² = L·L<br>L* = L⁰+L¹+L²+…<br><br>φ* = {ε}</code>
          </div>
        </div>

        <h3>📐 Operator Precedence (highest → lowest)</h3>
        <div class="prec-row"><span class="prec-badge prec-1">1st (highest) — Kleene * </span><span style="font-size:13px; color:var(--muted);">Unary postfix — binds tightest</span></div>
        <div class="prec-row"><span class="prec-badge prec-2">2nd — Concatenation · </span><span style="font-size:13px; color:var(--muted);">L₁·L₂* means L₁·(L₂*)</span></div>
        <div class="prec-row"><span class="prec-badge prec-3">3rd (lowest) — Union + </span><span style="font-size:13px; color:var(--muted);">L₁ + L₂·L₃ means L₁ + (L₂·L₃)</span></div>

        <h3>📌 Key Examples</h3>

        <div class="regex-box">
          <span class="rx-com">// (0+1)* — all strings of zeros and ones</span><br>
          Let L = {0,1}<br>
          L⁰ = {<span class="rx-sym">ε</span>}<br>
          L¹ = {<span class="rx-sym">0</span>, <span class="rx-sym">1</span>}<br>
          L² = {<span class="rx-sym">00</span>, <span class="rx-sym">01</span>, <span class="rx-sym">10</span>, <span class="rx-sym">11</span>}<br>
          L* = {ε, 0, 1, 00, 01, 10, 11, 000, …} = <span class="rx-res">all binary strings</span>
        </div>

        <div class="regex-box">
          <span class="rx-com">// 1·(0+1)*·0 = 1(0+1)*0</span><br>
          = {<span class="rx-sym">10</span>, <span class="rx-sym">100</span>, <span class="rx-sym">110</span>, <span class="rx-sym">1000</span>, <span class="rx-sym">1010</span>, <span class="rx-sym">1100</span>, …}<br>
          = <span class="rx-res">all strings beginning with 1 and ending with 0</span><br>
          <span class="rx-com">// Same language as Figure 2.1!</span>
        </div>

        <div class="note-box warn">
          <span class="box-icon">⚠️</span>
          <div>
            <strong>Nested Kleene stars</strong> are independent. In <code>(0*1)*</code>, the outer <code>*</code>
            and inner <code>*</code> each generate their own repetitions independently. For example,
            <code>0001101</code> can be generated: outer * repeats 3 times — first inner generates
            <code>000</code>, second inner generates ε (0 times), third inner generates <code>0</code>,
            then each is followed by a <code>1</code>.
          </div>
        </div>

        <div class="note-box pro">
          <span class="box-icon">🧩</span>
          <div>
            <strong>Shorthand:</strong> If <code>x</code> is a character in the alphabet, then
            <code>x</code> in a regular expression stands for the set <code>{"x"}</code> — the
            one-string language containing just that character. So:
            <br><code>0 + 1 = {0} + {1} = {0,1}</code>
            <br><code>0 + ε = {0, ε}</code>
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Sample Problem 2.0(b)                         -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="sp-b">
        <h2><span class="sec-icon">🧪</span> Sample Problem 2.0(b) — Listing Strings from Regular Expressions</h2>

        <div class="sample-problem">
          <div class="sp-header"><span style="font-size:18px">📋</span>
            <h4>Sample Problem 2.0 (b)</h4>
          </div>
          <div class="sp-body">
            <p class="sp-question">For each regular expression, list six strings which are in its language:</p>

            <div class="sp-sub">1. (a(b+c)*)*d</div>
            <div class="regex-box" style="margin-bottom:16px;">
              Six strings: &nbsp;
              <span class="rx-sym">d</span> &nbsp;
              <span class="rx-sym">ad</span> &nbsp;
              <span class="rx-sym">abd</span> &nbsp;
              <span class="rx-sym">acd</span> &nbsp;
              <span class="rx-sym">aad</span> &nbsp;
              <span class="rx-sym">abbcbd</span><br>
              <span class="rx-com">// Outer * can fire 0 times (→ just d) or many times</span>
            </div>

            <div class="sp-sub">2. (a+b)*·(c+d)</div>
            <div class="regex-box" style="margin-bottom:16px;">
              Six strings: &nbsp;
              <span class="rx-sym">c</span> &nbsp;
              <span class="rx-sym">d</span> &nbsp;
              <span class="rx-sym">ac</span> &nbsp;
              <span class="rx-sym">abd</span> &nbsp;
              <span class="rx-sym">babc</span> &nbsp;
              <span class="rx-sym">bad</span><br>
              <span class="rx-com">// (a+b)* generates any a/b string (incl. ε); must end in c or d</span>
            </div>

            <div class="sp-sub">3. (a*b*)*</div>
            <div class="regex-box">
              Six strings: &nbsp;
              <span class="rx-sym">ε</span> &nbsp;
              <span class="rx-sym">a</span> &nbsp;
              <span class="rx-sym">b</span> &nbsp;
              <span class="rx-sym">ab</span> &nbsp;
              <span class="rx-sym">ba</span> &nbsp;
              <span class="rx-sym">aa</span><br>
              <span class="rx-com">// Note: (a*b*)* = (a+b)* — generates ALL strings of a's and b's</span>
            </div>
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Sample Problem 2.0(c)                         -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="sp-c">
        <h2><span class="sec-icon">🧪</span> Sample Problem 2.0(c) — Regular Expressions from FSMs</h2>

        <div class="sample-problem">
          <div class="sp-header"><span style="font-size:18px">📋</span>
            <h4>Sample Problem 2.0 (c) — Regular expressions for Sample Problem 2.0(a)</h4>
          </div>
          <div class="sp-body">
            <p class="sp-question">Give a regular expression for each of the languages in Sample Problem 2.0(a):</p>

            <div class="sp-sub">1. Odd number of zeros &nbsp;→</div>
            <div class="regex-box" style="margin-bottom:16px;">
              <span class="rx-op">1</span><span class="rx-star">*</span>
              <span class="rx-sym">0</span>
              <span class="rx-op">1</span><span class="rx-star">*</span>
              (<span class="rx-sym">0</span>
              <span class="rx-op">1</span><span class="rx-star">*</span>
              <span class="rx-sym">0</span>
              <span class="rx-op">1</span><span class="rx-star">*</span>)<span class="rx-star">*</span><br>
              <span class="rx-com">// One zero, surrounded by any 1s, optionally followed by pairs of zeros with 1s in between</span>
            </div>

            <div class="sp-sub">2. Three consecutive ones &nbsp;→</div>
            <div class="regex-box" style="margin-bottom:16px;">
              (<span class="rx-sym">0</span>+<span class="rx-sym">1</span>)<span class="rx-star">*</span>
              <span class="rx-sym">111</span>
              (<span class="rx-sym">0</span>+<span class="rx-sym">1</span>)<span class="rx-star">*</span><br>
              <span class="rx-com">// Any string before and after the "111" substring</span>
            </div>

            <div class="sp-sub">3. Exactly three zeros &nbsp;→</div>
            <div class="regex-box" style="margin-bottom:16px;">
              <span class="rx-op">1</span><span class="rx-star">*</span>
              <span class="rx-sym">0</span>
              <span class="rx-op">1</span><span class="rx-star">*</span>
              <span class="rx-sym">0</span>
              <span class="rx-op">1</span><span class="rx-star">*</span>
              <span class="rx-sym">0</span>
              <span class="rx-op">1</span><span class="rx-star">*</span><br>
              <span class="rx-com">// Exactly three 0s, with any number of 1s between/around them</span>
            </div>

            <div class="sp-sub">4. Odd zeros AND even ones &nbsp;→</div>
            <div class="regex-box">
              (<span class="rx-sym">00</span>+<span class="rx-sym">11</span>)<span class="rx-star">*</span>
              (<span class="rx-sym">01</span>+<span class="rx-sym">10</span>)
              (<span class="rx-sym">1</span>(<span class="rx-sym">0</span>(<span class="rx-sym">11</span>)<span class="rx-star">*</span><span class="rx-sym">0</span>)<span class="rx-star">*</span><span class="rx-sym">1</span>+<span class="rx-sym">0</span>(<span class="rx-sym">1</span>(<span class="rx-sym">00</span>)<span class="rx-star">*</span><span class="rx-sym">1</span>)<span class="rx-star">*</span><span class="rx-sym">0</span>)<span class="rx-star">*</span>
              <span class="rx-sym">1</span>(<span class="rx-sym">0</span>(<span class="rx-sym">11</span>)<span class="rx-star">*</span><span class="rx-sym">0</span>)<span class="rx-star">*</span> +
              (<span class="rx-sym">00</span>+<span class="rx-sym">11</span>)<span class="rx-star">*</span><span class="rx-sym">0</span><br>
              <span class="rx-com">// Complex — tracking both zero parity and one parity simultaneously</span>
            </div>
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- Quiz                                          -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="quiz">
        <h2><span class="sec-icon">🧩</span> Check Your Knowledge</h2>
        <p>Test what you've learned about Section 2.0. Select the best answer for each question.</p>

        <!-- Q1 -->
        <div class="mini-quiz">
          <div class="quiz-header"><span style="font-size:18px;">❓</span>
            <h4>Question 1</h4>
          </div>
          <div class="quiz-body">
            <p class="quiz-q">What is the key difference between the <em>empty set</em> <code>φ</code> and the <em>null string</em> <code>ε</code>?</p>
            <div class="quiz-options" id="q1-opts">
              <div class="quiz-opt" onclick="answer(this,'q1',false)">
                <div class="opt-circle">A</div> They are the same thing — both represent "nothing"
              </div>
              <div class="quiz-opt" onclick="answer(this,'q1',true)">
                <div class="opt-circle">B</div> φ is a language with no strings in it; ε is a string of zero characters — the set {ε} contains one element
              </div>
              <div class="quiz-opt" onclick="answer(this,'q1',false)">
                <div class="opt-circle">C</div> ε is a language; φ is a string
              </div>
              <div class="quiz-opt" onclick="answer(this,'q1',false)">
                <div class="opt-circle">D</div> φ always contains ε as a member
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
            <p class="quiz-q">A finite state machine accepts a string when…</p>
            <div class="quiz-options" id="q2-opts">
              <div class="quiz-opt" onclick="answer(this,'q2',false)">
                <div class="opt-circle">A</div> It reads at least one character from the input
              </div>
              <div class="quiz-opt" onclick="answer(this,'q2',true)">
                <div class="opt-circle">B</div> After consuming the entire input string, the machine is in an accepting state
              </div>
              <div class="quiz-opt" onclick="answer(this,'q2',false)">
                <div class="opt-circle">C</div> It passes through an accepting state at any point during processing
              </div>
              <div class="quiz-opt" onclick="answer(this,'q2',false)">
                <div class="opt-circle">D</div> The input string matches any single transition label in the machine
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
            <p class="quiz-q">In regular expression precedence, which operation binds most tightly (evaluated first)?</p>
            <div class="quiz-options" id="q3-opts">
              <div class="quiz-opt" onclick="answer(this,'q3',false)">
                <div class="opt-circle">A</div> Union (+)
              </div>
              <div class="quiz-opt" onclick="answer(this,'q3',false)">
                <div class="opt-circle">B</div> Concatenation (·)
              </div>
              <div class="quiz-opt" onclick="answer(this,'q3',true)">
                <div class="opt-circle">C</div> Kleene star (*)
              </div>
              <div class="quiz-opt" onclick="answer(this,'q3',false)">
                <div class="opt-circle">D</div> All three have equal precedence
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
            <p class="quiz-q">What language does the regular expression <code>(0+1)*</code> describe?</p>
            <div class="quiz-options" id="q4-opts">
              <div class="quiz-opt" onclick="answer(this,'q4',false)">
                <div class="opt-circle">A</div> All strings of 0s and 1s that alternate between 0 and 1
              </div>
              <div class="quiz-opt" onclick="answer(this,'q4',false)">
                <div class="opt-circle">B</div> Only strings with an equal number of 0s and 1s
              </div>
              <div class="quiz-opt" onclick="answer(this,'q4',true)">
                <div class="opt-circle">C</div> All possible strings of 0s and 1s, including ε
              </div>
              <div class="quiz-opt" onclick="answer(this,'q4',false)">
                <div class="opt-circle">D</div> Only the strings "0" and "1"
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
            <p class="quiz-q">The Even Parity Checker FSM (Figure 2.2) has its starting state also as an accepting state. Why?</p>
            <div class="quiz-options" id="q5-opts">
              <div class="quiz-opt" onclick="answer(this,'q5',false)">
                <div class="opt-circle">A</div> Because every FSM must have its start state be an accepting state
              </div>
              <div class="quiz-opt" onclick="answer(this,'q5',true)">
                <div class="opt-circle">B</div> Because the null string ε has zero 1s, which is an even number — so ε must be accepted
              </div>
              <div class="quiz-opt" onclick="answer(this,'q5',false)">
                <div class="opt-circle">C</div> To ensure the machine can always accept at least one string
              </div>
              <div class="quiz-opt" onclick="answer(this,'q5',false)">
                <div class="opt-circle">D</div> Because the machine only has two states, so one must accept all inputs
              </div>
            </div>
            <div class="quiz-feedback" id="q5-fb"></div>
          </div>
        </div>

      </section>

      <!-- Chapter nav -->
      <div class="chapter-nav">
        <a href="../chapter1/c1_4.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Previous section</span>1.4 Case Study: Decaf</div>
        </a>
        <a href="ch2_s1.php" class="nav-btn next">
          <div style="text-align:right"><span class="btn-sub">Next section</span>2.1 Lexical Analysis</div>
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