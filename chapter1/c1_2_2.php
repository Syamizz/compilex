<?php
session_start();
include '../dbconn.php';
include '../save_progress.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$chapter = 1;
$page = 8;
$nextPage = "c1_2_3.php";

if (isset($_GET['complete']) && $_GET['complete'] == $page) {
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
    <title>Chapter 1.2 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c1_2.css">

    
</head>

<body>

    <?php include 'navbar_c1.php'; ?>

    <div class="note-container">

        <!-- ── Sidebar TOC ─────────────────────────────────── -->
        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#syntax" class="toc-link">1.2.2 Syntax Analysis</a></li>
                <li><a href="#sp-b" class="toc-link sub">↳ Sample Problem (b)</a></li>
                <li><a href="#sp-c" class="toc-link sub">↳ Sample Problem (c)</a></li>
                <li><a href="#sp-d" class="toc-link sub">↳ Sample Problem (d)</a></li>
                <li><a href="#semantic" class="toc-link">Semantic Analysis</a></li>
             </ul>
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

        <!-- ── Main content ───────────────────────────────── -->
        <article class="content">

            <header class="content-header">
                <div class="chapter-tag">📘 Chapter 1 · Section 1.2</div>
                <h1>The Phases of a Compiler</h1>
                <div class="metadata">
                    <span>⏱ 18 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>⚙️ Compiler Theory</span>
                </div>
            </header>

            

            <!-- ══════════════════════════════════════════════ -->
            <!-- 1.2.2 Syntax Analysis                         -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="syntax">
                <h2><span class="sec-icon">🌳</span> 1.2.2 Syntax Analysis (Parser)</h2>
                <p>
                    The syntax analysis phase — called the <strong>parser</strong> — checks for proper syntax,
                    issues appropriate error messages, and determines the underlying structure of the source program.
                    The output can be:
                </p>
                <ul>
                    <li>A <strong>stream of atoms</strong> (atomic operations), or</li>
                    <li>A <strong>syntax tree</strong> (a tree showing the structure of the statement)</li>
                </ul>

                <h3>🔵 Atoms</h3>
                <p>
                    An <strong>atom</strong> is an atomic operation — one that is generally available with one (or just a few)
                    machine language instructions on most target machines. Atoms have the form:
                </p>
                <div class="atom-wrap">
                    <div style="font-family:'JetBrains Mono',monospace; font-size:13px; color:#CDD6F4; margin-bottom:14px;">
                        <span style="color:#A5B4FC;">(operation</span>, operand1, operand2, <span style="color:#6EE7B7;">result</span>)
                    </div>
                    <div style="font-size:13px; color:rgba(205,214,244,.6); margin-bottom:14px;">Common operations and their atoms:</div>
                    <div class="atom-list">
                        <div class="atom-row">
                            <span class="atom-paren">(</span>
                            <span class="atom-op">ADD</span>
                            <span class="atom-operand">A</span><span class="atom-operand">B</span>
                            <span class="atom-result">C</span>
                            <span class="atom-paren">)</span>
                            <span style="color:rgba(205,214,244,.4); font-size:12px; margin-left:14px;">→ C = A + B</span>
                        </div>
                        <div class="atom-row">
                            <span class="atom-paren">(</span>
                            <span class="atom-op">MULT</span>
                            <span class="atom-operand">C</span><span class="atom-operand">D</span>
                            <span class="atom-result">TEMP1</span>
                            <span class="atom-paren">)</span>
                            <span style="color:rgba(205,214,244,.4); font-size:12px; margin-left:14px;">→ TEMP1 = C * D</span>
                        </div>
                        <div class="atom-row">
                            <span class="atom-paren">(</span>
                            <span class="atom-op">MOVE</span>
                            <span class="atom-operand">TEMP2</span>
                            <span class="atom-result">A</span>
                            <span class="atom-paren">)</span>
                            <span style="color:rgba(205,214,244,.4); font-size:12px; margin-left:14px;">→ A = TEMP2</span>
                        </div>
                        <div class="atom-row">
                            <span class="atom-paren">(</span>
                            <span class="atom-op">LBL</span>
                            <span class="atom-operand">L1</span>
                            <span class="atom-paren">)</span>
                            <span style="color:rgba(205,214,244,.4); font-size:12px; margin-left:14px;">→ label marker</span>
                        </div>
                        <div class="atom-row">
                            <span class="atom-paren">(</span>
                            <span class="atom-op">JMP</span>
                            <span class="atom-operand">L1</span>
                            <span class="atom-paren">)</span>
                            <span style="color:rgba(205,214,244,.4); font-size:12px; margin-left:14px;">→ unconditional branch to L1</span>
                        </div>
                        <div class="atom-row">
                            <span class="atom-paren">(</span>
                            <span class="atom-op">TEST</span>
                            <span class="atom-operand">A</span><span class="atom-operand">&lt;=</span><span class="atom-operand">B</span>
                            <span class="atom-result">L2</span>
                            <span class="atom-paren">)</span>
                            <span style="color:rgba(205,214,244,.4); font-size:12px; margin-left:14px;">→ branch to L2 if A &lt;= B</span>
                        </div>
                    </div>
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        The compiler must put out the <strong>MULT atom before the ADD atom</strong> for <code>A = B + C * D</code>,
                        despite the fact that addition is encountered first when scanning left to right. Operator precedence
                        must be respected.
                    </div>
                </div>

                <h3>🌳 Syntax Trees</h3>
                <p>
                    Some parsers output <strong>syntax trees</strong> as an intermediate data structure. In a syntax tree:
                </p>
                <ul>
                    <li>Each <strong>interior node</strong> represents an operation or control structure</li>
                    <li>Each <strong>leaf node</strong> represents an operand</li>
                </ul>
                <p>
                    Code generation from a syntax tree uses a <strong>postfix traversal</strong> — for each node N,
                    visit all subtrees of N first, then visit N last, generating the instruction(s) for N at that point.
                </p>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Control Structure</th>
                            <th>Tree Shape</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>if (Expr) Stmt1 else Stmt2</code></td>
                            <td>3 children: condition, true-branch, else-branch</td>
                        </tr>
                        <tr>
                            <td><code>while (Cond) Stmt</code></td>
                            <td>2 children: loop condition, loop body</td>
                        </tr>
                        <tr>
                            <td>Compound statement <code>{ ... }</code></td>
                            <td>Unlimited children (one per statement) OR binary tree using <code>;</code> as concatenation operator</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <!-- Sample Problem 1.2(b) -->
            <section id="sp-b">
                <h2><span class="sec-icon">🧪</span> Sample Problem 1.2(b) — Atoms for <code>A = B + C * D</code></h2>

                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 1.2 (b)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">Show atoms corresponding to the following Java statement:</p>
                        <div class="code-wrap" style="margin-bottom:16px;">
                            <div class="code-header">
                                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                                <span class="code-lang">Java — Source</span>
                            </div>
                            <pre><code>A = B + C * D ;</code></pre>
                        </div>

                        <div class="sp-solution-label">✅ Solution — Atom Stream</div>
                        <div class="atom-wrap">
                            <div class="atom-list">
                                <div class="atom-row">
                                    <span class="atom-paren">(</span>
                                    <span class="atom-op">MULT</span>
                                    <span class="atom-operand">C</span><span class="atom-operand">D</span>
                                    <span class="atom-result">TEMP1</span>
                                    <span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← multiplication first (precedence)</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span>
                                    <span class="atom-op">ADD</span>
                                    <span class="atom-operand">B</span><span class="atom-operand">TEMP1</span>
                                    <span class="atom-result">TEMP2</span>
                                    <span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← then add B</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span>
                                    <span class="atom-op">MOVE</span>
                                    <span class="atom-operand">TEMP2</span>
                                    <span class="atom-result">A</span>
                                    <span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← assign result to A</span>
                                </div>
                            </div>
                        </div>

                        <p style="font-size:13px; color:var(--muted); margin-top:14px; margin-bottom:4px;"><strong>Corresponding syntax tree for <code>A = B + C * D</code>:</strong></p>
                        <div class="tree-wrap">
                            <svg width="320" height="210" viewBox="0 0 320 210">
                                <defs>
                                    <marker id="arr" markerWidth="6" markerHeight="6" refX="3" refY="3" orient="auto">
                                        <path d="M0,0 L0,6 L6,3 z" fill="#6366F1" opacity=".5" />
                                    </marker>
                                </defs>
                                <!-- edges -->
                                <line x1="160" y1="34" x2="80" y2="74" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="160" y1="34" x2="240" y2="74" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="240" y1="94" x2="180" y2="134" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="240" y1="94" x2="300" y2="134" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="180" y1="154" x2="130" y2="188" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="180" y1="154" x2="230" y2="188" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <!-- nodes -->
                                <circle cx="160" cy="24" r="20" fill="#6366F1" opacity=".15" stroke="#6366F1" stroke-width="1.5" />
                                <text x="160" y="29" text-anchor="middle" fill="#818CF8" font-size="14" font-weight="700">=</text>

                                <circle cx="80" cy="84" r="20" fill="#1A1830" stroke="#6366F1" stroke-width="1.5" opacity=".7" />
                                <text x="80" y="89" text-anchor="middle" fill="#CDD6F4" font-size="12">A</text>

                                <circle cx="240" cy="84" r="20" fill="#6366F1" opacity=".15" stroke="#6366F1" stroke-width="1.5" />
                                <text x="240" y="89" text-anchor="middle" fill="#818CF8" font-size="14" font-weight="700">+</text>

                                <circle cx="180" cy="144" r="20" fill="#1A1830" stroke="#6366F1" stroke-width="1.5" opacity=".7" />
                                <text x="180" y="149" text-anchor="middle" fill="#CDD6F4" font-size="12">B</text>

                                <circle cx="300" cy="144" r="20" fill="#6366F1" opacity=".15" stroke="#6366F1" stroke-width="1.5" />
                                <text x="300" y="149" text-anchor="middle" fill="#818CF8" font-size="14" font-weight="700">*</text>

                                <circle cx="130" cy="196" r="16" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="130" y="201" text-anchor="middle" fill="#CDD6F4" font-size="12">C</text>

                                <circle cx="230" cy="196" r="16" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="230" y="201" text-anchor="middle" fill="#CDD6F4" font-size="12">D</text>

                                <!-- postfix order labels -->
                                <text x="60" y="118" fill="#F9E2AF" font-size="10" opacity=".7">① leaf A</text>
                                <text x="158" y="170" fill="#F9E2AF" font-size="10" opacity=".7">② leaf B</text>
                                <text x="108" y="216" fill="#F9E2AF" font-size="9" opacity=".7">③ C</text>
                                <text x="208" y="216" fill="#F9E2AF" font-size="9" opacity=".7">④ D</text>
                                <text x="270" y="178" fill="#A5B4FC" font-size="10" opacity=".8">⑤ MULT</text>
                                <text x="194" y="62" fill="#A5B4FC" font-size="10" opacity=".8">⑥ ADD</text>
                                <text x="128" y="16" fill="#6EE7B7" font-size="10" opacity=".8">⑦ MOVE</text>
                            </svg>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sample Problem 1.2(c) -->
            <section id="sp-c">
                <h2><span class="sec-icon">🧪</span> Sample Problem 1.2(c) — Atoms for a <code>while</code> loop</h2>

                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 1.2 (c)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">Show atoms corresponding to the Java statement:</p>
                        <div class="code-wrap" style="margin-bottom:16px;">
                            <div class="code-header">
                                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                                <span class="code-lang">Java — Source</span>
                            </div>
                            <pre><code><span class="kw">while</span> (A &lt;= B) A = A + <span class="num">1</span>;</code></pre>
                        </div>

                        <div class="sp-solution-label">✅ Solution — Atom Stream</div>
                        <div class="atom-wrap">
                            <div class="atom-list">
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op" style="background:rgba(249,226,175,.15);color:#F9E2AF;">LBL</span><span class="atom-operand">L1</span><span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← loop top label</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op">TEST</span><span class="atom-operand">A</span><span class="atom-operand">&lt;=</span><span class="atom-operand">B</span><span class="atom-result">L2</span><span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← if A&lt;=B, branch to L2 (loop body)</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op" style="background:rgba(239,68,68,.12);color:#F87171;">JMP</span><span class="atom-operand">L3</span><span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← condition false → exit loop</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op" style="background:rgba(249,226,175,.15);color:#F9E2AF;">LBL</span><span class="atom-operand">L2</span><span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← loop body starts here</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op">ADD</span><span class="atom-operand">A</span><span class="atom-operand">1</span><span class="atom-result">A</span><span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← A = A + 1</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op" style="background:rgba(239,68,68,.12);color:#F87171;">JMP</span><span class="atom-operand">L1</span><span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← go back to loop top</span>
                                </div>
                                <div class="atom-row">
                                    <span class="atom-paren">(</span><span class="atom-op" style="background:rgba(249,226,175,.15);color:#F9E2AF;">LBL</span><span class="atom-operand">L3</span><span class="atom-paren">)</span>
                                    <span style="color:rgba(205,214,244,.35); font-size:11px; margin-left:14px;">← exit label</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sample Problem 1.2(d) -->
            <section id="sp-d">
                <h2><span class="sec-icon">🧪</span> Sample Problem 1.2(d) — Syntax Tree for <code>if-else</code></h2>

                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 1.2 (d)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">
                            Show a syntax tree for the Java statement below. Assume an if-statement has three subtrees:
                            one for the condition, one for the consequent, and one for the else statement.
                        </p>
                        <div class="code-wrap" style="margin-bottom:16px;">
                            <div class="code-header">
                                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                                <span class="code-lang">Java — Source</span>
                            </div>
                            <pre><code><span class="kw">if</span> (A + <span class="num">3</span> &lt; <span class="num">400</span>) A = <span class="num">0</span>; <span class="kw">else</span> B = A * A;</code></pre>
                        </div>

                        <div class="sp-solution-label">✅ Solution — Syntax Tree</div>
                        <div class="tree-wrap">
                            <svg width="520" height="240" viewBox="0 0 520 240">
                                <!-- edges: if node to children -->
                                <line x1="260" y1="34" x2="120" y2="80" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="260" y1="34" x2="260" y2="80" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="260" y1="34" x2="400" y2="80" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <!-- condition: < node -->
                                <line x1="120" y1="100" x2="70" y2="140" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="120" y1="100" x2="170" y2="140" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <!-- + node -->
                                <line x1="70" y1="160" x2="40" y2="200" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="70" y1="160" x2="100" y2="200" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <!-- else: * node -->
                                <line x1="400" y1="100" x2="440" y2="140" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="440" y1="160" x2="410" y2="200" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="440" y1="160" x2="470" y2="200" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <!-- = consequent: left -->
                                <line x1="260" y1="100" x2="220" y2="140" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <line x1="260" y1="100" x2="260" y2="140" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                <!-- else = left -->
                                <line x1="400" y1="100" x2="370" y2="140" stroke="#6366F1" stroke-width="1.5" opacity=".4" />

                                <!-- if root -->
                                <rect x="230" y="10" width="60" height="28" rx="8" fill="#6366F1" opacity=".2" stroke="#6366F1" stroke-width="1.5" />
                                <text x="260" y="29" text-anchor="middle" fill="#A5B4FC" font-size="13" font-weight="700">if</text>

                                <!-- condition subtree: < -->
                                <circle cx="120" cy="90" r="16" fill="#6366F1" opacity=".15" stroke="#6366F1" stroke-width="1.5" />
                                <text x="120" y="95" text-anchor="middle" fill="#818CF8" font-size="13" font-weight="700">&lt;</text>
                                <!-- + -->
                                <circle cx="70" cy="150" r="16" fill="#6366F1" opacity=".15" stroke="#6366F1" stroke-width="1.5" />
                                <text x="70" y="155" text-anchor="middle" fill="#818CF8" font-size="13" font-weight="700">+</text>
                                <circle cx="40" cy="206" r="14" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="40" y="211" text-anchor="middle" fill="#CDD6F4" font-size="11">A</text>
                                <circle cx="100" cy="206" r="14" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="100" y="211" text-anchor="middle" fill="#FAB387" font-size="11">3</text>
                                <!-- 400 -->
                                <circle cx="170" cy="148" r="18" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="170" y="153" text-anchor="middle" fill="#FAB387" font-size="10">400</text>

                                <!-- consequent: = node -->
                                <circle cx="260" cy="90" r="16" fill="#6366F1" opacity=".15" stroke="#6366F1" stroke-width="1.5" />
                                <text x="260" y="95" text-anchor="middle" fill="#818CF8" font-size="13" font-weight="700">=</text>
                                <circle cx="220" cy="148" r="14" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="220" y="153" text-anchor="middle" fill="#CDD6F4" font-size="11">A</text>
                                <circle cx="260" cy="148" r="14" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="260" y="153" text-anchor="middle" fill="#FAB387" font-size="11">0</text>

                                <!-- else: = node -->
                                <circle cx="370" cy="148" r="14" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="370" y="153" text-anchor="middle" fill="#CDD6F4" font-size="11">B</text>
                                <circle cx="400" cy="90" r="16" fill="#6366F1" opacity=".15" stroke="#6366F1" stroke-width="1.5" />
                                <text x="400" y="95" text-anchor="middle" fill="#818CF8" font-size="13" font-weight="700">=</text>
                                <!-- * -->
                                <circle cx="440" cy="150" r="16" fill="#6366F1" opacity=".15" stroke="#6366F1" stroke-width="1.5" />
                                <text x="440" y="155" text-anchor="middle" fill="#818CF8" font-size="13" font-weight="700">*</text>
                                <circle cx="410" cy="206" r="14" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="410" y="211" text-anchor="middle" fill="#CDD6F4" font-size="11">A</text>
                                <circle cx="470" cy="206" r="14" fill="#1A1830" stroke="#6366F1" stroke-width="1" opacity=".7" />
                                <text x="470" y="211" text-anchor="middle" fill="#CDD6F4" font-size="11">A</text>

                                <!-- labels -->
                                <text x="70" y="230" text-anchor="middle" fill="#F9E2AF" font-size="9" opacity=".7">condition</text>
                                <text x="245" y="230" text-anchor="middle" fill="#F9E2AF" font-size="9" opacity=".7">consequent</text>
                                <text x="430" y="230" text-anchor="middle" fill="#F9E2AF" font-size="9" opacity=".7">else</text>
                            </svg>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Semantic Analysis                             -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="semantic">
                <h2><span class="sec-icon">🧠</span> Semantic Analysis</h2>
                <p>
                    Many compilers include a <strong>semantic analysis</strong> phase. In this phase:
                </p>
                <ul>
                    <li>Data types are <strong>checked</strong> for compatibility</li>
                    <li><strong>Type conversions</strong> are performed when necessary (e.g. int → float)</li>
                    <li>Some semantic errors can be detected: division by zero, use of a null pointer</li>
                </ul>
                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>
                        Semantic analysis catches errors that are syntactically valid but logically incorrect.
                        For example, <code>int x = "hello";</code> is syntactically structured correctly but
                        is a <em>type error</em> caught by semantic analysis.
                    </div>
                </div>
            </section>

  

    

            <!-- Chapter nav -->
            <div class="chapter-nav">
                <a href="c1_2_1.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous section</span>1.2.1 Lexical Analysis</div>
                </a>
                <a href="?complete=8" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>Chapter 1.2.3: Global Optimisation</div>
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

        // ── Copy code ────────────────────────────────────────────
        function copyBlock(id, btn) {
            const text = document.getElementById(id).innerText;
            navigator.clipboard.writeText(text).then(() => {
                btn.textContent = 'Copied!';
                setTimeout(() => btn.textContent = 'Copy', 1800);
            });
        }

       
    </script>

</body>

</html>