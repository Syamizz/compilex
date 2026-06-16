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
$page = 9;
$nextPage = "c2_2_2.php";

if (isset($_GET['complete']) && $_GET['complete'] == '9') {
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
                <li><a href="#fig24" class="toc-link">2.2.1 FSM Examples</a></li>
                <li><a href="#fig24" class="toc-link sub">↳ Fig 2.4 Identifiers</a></li>
                <li><a href="#fig25" class="toc-link sub">↳ Fig 2.5 Numeric Constants</a></li>
                <li><a href="#fig26" class="toc-link sub">↳ Fig 2.6 Keyword Recogniser</a></li>
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







            <div class="chapter-nav">
                <a href="c2_2_0.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>2.2.0 Array Implementation</div>
                </a>
                <a href="?complete=9" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>2.2.2 Actions for Finite State Machines</div>
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