<?php
session_start();
include '../dbconn.php';
include '../save_progress.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$chapter  = 4;
$page     = 24;
$nextPage = "c4_10.php"; // adjust as needed

if (isset($_GET['complete']) && $_GET['complete'] == '1') {
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
    <title>Chapter 4.9 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c1_1.css">

    <style>
        /* ── Atom / code boxes ──────────────────────────────── */
        .grammar-box {
            background: var(--code-bg, #1A1830);
            border-radius: 12px;
            padding: 20px 24px;
            margin: 16px 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13.5px;
            color: #CDD6F4;
            line-height: 2.1;
        }

        .grammar-box .rule-num {
            color: rgba(205, 214, 244, .45);
            user-select: none;
            margin-right: 10px;
        }

        .grammar-box .nt {
            color: #CBA6F7;
        }

        .grammar-box .t {
            color: #A6E3A1;
        }

        .grammar-box .arr {
            color: #94E2D5;
        }

        .grammar-box .eps {
            color: #FAB387;
        }

        .grammar-box .attr {
            color: #F9E2AF;
            font-size: 11px;
            vertical-align: sub;
        }

        .grammar-box .comp {
            color: #89DCEB;
            font-size: 12px;
            margin-left: 18px;
        }

        .grammar-box .act {
            color: #F38BA8;
            font-weight: 700;
        }

        .grammar-box .kw {
            color: #CBA6F7;
        }

        /* ── Algo blocks ─────────────────────────────────────── */
        .algo-block {
            background: var(--card, #fff);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: 14px;
            overflow: hidden;
            margin: 18px 0;
            box-shadow: 0 4px 18px rgba(99, 102, 241, .07);
        }

        .algo-block-header {
            background: var(--purple-s, #EEF2FF);
            padding: 12px 20px;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 14px;
            color: var(--purple, #6366F1);
            border-bottom: 1px solid rgba(99, 102, 241, .12);
        }

        .algo-block-body {
            padding: 18px 22px;
        }

        /* ── Atom reference table ───────────────────────────── */
        .atom-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            margin: 14px 0;
        }

        .atom-table th {
            background: var(--purple-s, #EEF2FF);
            color: var(--purple, #6366F1);
            padding: 9px 14px;
            text-align: left;
            font-weight: 700;
            font-size: 12px;
            border: 1px solid rgba(99, 102, 241, .15);
        }

        .atom-table td {
            padding: 8px 14px;
            border: 1px solid rgba(99, 102, 241, .1);
            vertical-align: middle;
            line-height: 1.55;
        }

        .atom-table td.atom-name {
            font-weight: 700;
            color: #B45309;
            background: #FFF7ED;
            white-space: nowrap;
        }

        .atom-table td.atom-sig {
            color: #6366F1;
            font-weight: 600;
        }

        .atom-table tr:nth-child(even) td {
            background: rgba(99, 102, 241, .025);
        }

        .atom-table tr:nth-child(even) td.atom-name {
            background: #FFF7ED;
        }

        /* ── Comparison code table ───────────────────────────── */
        .comp-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13.5px;
            margin: 14px 0;
            max-width: 380px;
        }

        .comp-table th {
            background: var(--purple-s, #EEF2FF);
            color: var(--purple, #6366F1);
            padding: 8px 18px;
            text-align: center;
            font-weight: 700;
            font-size: 12px;
            border: 1px solid rgba(99, 102, 241, .15);
        }

        .comp-table td {
            padding: 7px 18px;
            border: 1px solid rgba(99, 102, 241, .1);
            text-align: center;
            color: var(--text, #1E1B4B);
        }

        .comp-table td.op-sym {
            font-weight: 700;
            color: #6366F1;
            font-size: 15px;
        }

        .comp-table td.op-code {
            font-weight: 700;
            color: #B45309;
        }

        .comp-table tr:nth-child(even) td {
            background: rgba(99, 102, 241, .03);
        }

        /* ── Control-structure flow diagrams (SVG wrappers) ─── */
        .flow-wrap {
            background: var(--card, #fff);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: 14px;
            padding: 20px 16px;
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-x: auto;
        }

        .flow-wrap figcaption {
            font-size: 12px;
            color: var(--muted, #6B7280);
            margin-top: 10px;
            text-align: center;
        }

        /* ── Two-column layout for while/for side-by-side ───── */
        .two-flow {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin: 18px 0;
        }

        @media (max-width: 700px) {
            .two-flow {
                grid-template-columns: 1fr;
            }
        }

        /* ── Output atom sequence ─────────────────────────────── */
        .output-seq {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin: 14px 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
        }

        .output-seq .atom-line {
            background: #FFF7ED;
            border: 1.5px solid rgba(245, 158, 11, .25);
            border-radius: 8px;
            padding: 6px 14px;
            color: #92400E;
            font-weight: 600;
        }

        .output-seq .atom-line.lbl {
            background: #F0FDF4;
            border-color: rgba(16, 185, 129, .3);
            color: #065F46;
        }

        .output-seq .atom-line.stmt {
            background: #EEF2FF;
            border-color: rgba(99, 102, 241, .25);
            color: #4338CA;
            font-style: italic;
        }

        .output-seq .atom-line.cm {
            background: transparent;
            border-color: transparent;
            color: var(--muted, #6B7280);
            font-style: italic;
            font-size: 12px;
            padding: 2px 14px;
        }

        /* ── Equivalence box ──────────────────────────────────── */
        .equiv-grid {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 14px;
            align-items: start;
            margin: 16px 0;
        }

        @media (max-width: 680px) {
            .equiv-grid {
                grid-template-columns: 1fr;
            }

            .equiv-arrow {
                display: none;
            }
        }

        .equiv-arrow {
            font-size: 22px;
            color: var(--purple, #6366F1);
            display: flex;
            align-items: center;
            padding-top: 14px;
        }

        /* ── Statement grammar list ───────────────────────────── */
        .stmt-list {
            counter-reset: stmt;
            list-style: none;
            padding: 0;
            margin: 12px 0;
        }

        .stmt-list li {
            counter-increment: stmt;
            display: flex;
            gap: 12px;
            align-items: baseline;
            padding: 7px 0;
            border-bottom: 1px solid rgba(99, 102, 241, .08);
            font-size: 13.5px;
        }

        .stmt-list li::before {
            content: counter(stmt) ".";
            min-width: 22px;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            color: var(--purple, #6366F1);
        }

        .stmt-list li code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: #B45309;
            background: #FFF7ED;
            padding: 1px 6px;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <?php include 'navbar_c4.php'; ?>

    <div class="note-container">

        <!-- ── Sidebar TOC ─────────────────────────────────────── -->
        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#intro" class="toc-link">4.9 Control Structures</a></li>
                <li><a href="#atoms" class="toc-link">Atoms for Control</a></li>
                <li><a href="#comp-codes" class="toc-link">Comparison Codes</a></li>
                <li><a href="#fig418a" class="toc-link">Fig 4.18(a) while & for</a></li>
                <li><a href="#fig418b" class="toc-link">Fig 4.18(b) if & BoolExpr</a></li>
                <li><a href="#stmt-defs" class="toc-link">Statement Definitions</a></li>
                <li><a href="#for-equiv" class="toc-link">for ↔ while Equivalence</a></li>
                <li><a href="#else-dangling" class="toc-link">Dangling else</a></li>
                <li><a href="#sample" class="toc-link">Sample Problem 4.9</a></li>
                <li><a href="#exercises" class="toc-link">Exercises 4.9</a></li>
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

        <!-- ── Main content ──────────────────────────────────── -->
        <article class="content">

            <header class="content-header">
                <div class="chapter-tag">📘 Chapter 4</div>
                <h1>Translating Control Structures</h1>
                <div class="metadata">
                    <span>⏱ 25 min read</span>
                    <span>🎯 Advanced</span>
                    <span>⚙️ Code Generation</span>
                </div>
            </header>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION 4.9 – Introduction                      -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="intro">
                <h2><span class="sec-icon">🔍</span> Introduction</h2>

                <p>
                    In order to translate control structures, such as <code>for</code>, <code>while</code>, and
                    <code>if</code> statements, we must first consider the set of <strong>primitive control
                        operations</strong> available on the target machine. These are typically simple operations
                    such as Unconditional Jump (Goto), Compare, and Conditional Jump.
                </p>
                <p>
                    In order to implement these Jump operations, we need to establish a jump, or
                    <strong>destination address</strong>. During the parse or syntax analysis phase there are,
                    as yet, no machine addresses attached to the output. In addition, we must handle
                    <strong>forward jumps</strong> when we don't yet know the destination of the jump.
                </p>

                <div class="note-box key">
                    <span class="box-icon">📖</span>
                    <div>
                        To solve the forward-jump problem we introduce a special atom called a
                        <strong>Label</strong>, which is used to mark the destination of a jump. During code
                        generation, a machine address is associated with each Label atom. At this point, we
                        need to add <strong>two additional fields</strong> to our atoms — one for comparison
                        codes (1–6) and one for jump destinations.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Atoms for Control                     -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="atoms">
                <h2><span class="sec-icon">⚛️</span> Atoms for Control Structures</h2>

                <p>We will use the following atoms to implement control structures:</p>

                <table class="atom-table">
                    <thead>
                        <tr>
                            <th>Atom</th>
                            <th>E1</th>
                            <th>E2</th>
                            <th>–</th>
                            <th>Cmp</th>
                            <th>Lbl</th>
                            <th>Purpose</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="atom-name">JMP</td>
                            <td>–</td>
                            <td>–</td>
                            <td>–</td>
                            <td>–</td>
                            <td class="atom-sig">Lbl</td>
                            <td>Unconditional jump to the specified label.</td>
                        </tr>
                        <tr>
                            <td class="atom-name">TST</td>
                            <td class="atom-sig">E1</td>
                            <td class="atom-sig">E2</td>
                            <td>–</td>
                            <td class="atom-sig">Cmp</td>
                            <td class="atom-sig">Lbl</td>
                            <td>Conditional branch if comparison is true. Compare values of E1 and E2 using comparison operator Cmp, then branch to Lbl if true.</td>
                        </tr>
                        <tr>
                            <td class="atom-name">LBL</td>
                            <td>–</td>
                            <td>–</td>
                            <td>–</td>
                            <td>–</td>
                            <td class="atom-sig">Lbl</td>
                            <td>Label used as a branch destination. A tag/marker so JMP and TST atoms can refer to a destination without knowing target machine addresses.</td>
                        </tr>
                        <tr>
                            <td class="atom-name">MOV</td>
                            <td class="atom-sig">Src</td>
                            <td>–</td>
                            <td class="atom-sig">Trg</td>
                            <td>–</td>
                            <td>–</td>
                            <td>Move (assignment). Assigns source operand to target operand: <code>Trg = Src</code>.</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        <strong>Example:</strong> <code>TST, A, C, , 4, L3</code> means
                        jump to label <code>L3</code> if <code>A &lt;= C</code>
                        (comparison code 4 = <code>&lt;=</code>).
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Comparison Codes                      -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="comp-codes">
                <h2><span class="sec-icon">🔢</span> Comparison Codes</h2>

                <p>The six comparison operators and their codes are:</p>

                <table class="comp-table">
                    <thead>
                        <tr>
                            <th>Operator</th>
                            <th>Code</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="op-sym">==</td>
                            <td class="op-code">1</td>
                        </tr>
                        <tr>
                            <td class="op-sym">&lt;</td>
                            <td class="op-code">2</td>
                        </tr>
                        <tr>
                            <td class="op-sym">&gt;</td>
                            <td class="op-code">3</td>
                        </tr>
                        <tr>
                            <td class="op-sym">&lt;=</td>
                            <td class="op-code">4</td>
                        </tr>
                        <tr>
                            <td class="op-sym">&gt;=</td>
                            <td class="op-code">5</td>
                        </tr>
                        <tr>
                            <td class="op-sym">!=</td>
                            <td class="op-code">6</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Statement Definitions                  -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="stmt-defs">
                <h2><span class="sec-icon">📋</span> Statement Definitions</h2>

                <p>
                    The control structures in Figures 4.18(a) and 4.18(b) correspond to the following statement
                    definitions (which are similar to the C++ control structures):
                </p>

                <ul class="stmt-list">
                    <li><code>Stmt → while ( BoolExpr ) Stmt</code></li>
                    <li><code>Stmt → for ( Expr ; BoolExpr ; Expr ) Stmt</code></li>
                    <li><code>Stmt → if ( BoolExpr ) Stmt ElsePart</code></li>
                    <li><code>ElsePart → else Stmt</code></li>
                    <li><code>ElsePart → ε</code></li>
                </ul>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Figure 4.18(a) while and for          -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="fig418a">
                <h2><span class="sec-icon">🔄</span> Figure 4.18(a) — Control Structures for <code>while</code> and <code>for</code> Statements</h2>

                <p>
                    The diagrams below show the <strong>sequence in which atoms are put out</strong> (top to
                    bottom) for the <code>while</code> and <code>for</code> control structures. Input tokens
                    are shown alongside atoms so you understand when each atom is emitted. The arrows indicate
                    <strong>run-time flow of control</strong> — not the order in which the compiler outputs atoms.
                </p>

                <div class="note-box pro">
                    <span class="box-icon">📌</span>
                    <div>
                        <strong>Visual conventions (Figure 4.18):</strong> ADD and JMP atoms are enclosed in
                        <strong>rectangles</strong>; LBL atoms are enclosed in <strong>ovals</strong>;
                        TST atoms are enclosed in <strong>diamond-shaped parallelograms</strong>.
                    </div>
                </div>

                <div class="two-flow">
                    <!-- while -->
                    <div class="flow-wrap">
                        <svg viewBox="0 0 220 360" width="210" xmlns="http://www.w3.org/2000/svg"
                            font-family="JetBrains Mono, monospace" font-size="12">

                            <!-- ── while keyword ── -->
                            <text x="110" y="22" text-anchor="middle" fill="#CBA6F7" font-weight="700" font-size="13">while</text>

                            <!-- LBL Lbl1 (oval) -->
                            <ellipse cx="110" cy="52" rx="52" ry="17" fill="#F0FDF4" stroke="#10B981" stroke-width="1.8" />
                            <text x="110" y="57" text-anchor="middle" fill="#065F46" font-weight="700" font-size="12">LBL Lbl1</text>

                            <!-- ( BoolExpr_Lbl2 ) -->
                            <text x="110" y="92" text-anchor="middle" fill="#6C7086" font-size="11">( BoolExpr</text>
                            <text x="162" y="92" text-anchor="start" fill="#F9E2AF" font-size="10">Lbl2</text>
                            <text x="110" y="106" text-anchor="middle" fill="#6C7086" font-size="11">)</text>

                            <!-- TST diamond -->
                            <polygon points="110,122 160,145 110,168 60,145" fill="#FFF7ED" stroke="#F97316" stroke-width="1.8" />
                            <text x="110" y="141" text-anchor="middle" fill="#B45309" font-weight="700" font-size="10">TST</text>
                            <text x="110" y="154" text-anchor="middle" fill="#B45309" font-size="9">False→Lbl2</text>

                            <!-- Stmt -->
                            <text x="110" y="198" text-anchor="middle" fill="#6C7086" font-size="11">Stmt</text>

                            <!-- JMP Lbl1 (rect) -->
                            <rect x="60" y="210" width="100" height="26" rx="6" fill="#EEF2FF" stroke="#6366F1" stroke-width="1.8" />
                            <text x="110" y="227" text-anchor="middle" fill="#4338CA" font-weight="700" font-size="12">JMP Lbl1</text>

                            <!-- LBL Lbl2 (oval) -->
                            <ellipse cx="110" cy="266" rx="52" ry="17" fill="#F0FDF4" stroke="#10B981" stroke-width="1.8" />
                            <text x="110" y="271" text-anchor="middle" fill="#065F46" font-weight="700" font-size="12">LBL Lbl2</text>

                            <!-- flow arrows -->
                            <line x1="110" y1="69" x2="110" y2="80" stroke="#6366F1" stroke-width="1.2" marker-end="url(#arr)" />
                            <line x1="110" y1="109" x2="110" y2="120" stroke="#6366F1" stroke-width="1.2" marker-end="url(#arr)" />
                            <line x1="110" y1="168" x2="110" y2="190" stroke="#6366F1" stroke-width="1.2" marker-end="url(#arr)" />
                            <line x1="110" y1="206" x2="110" y2="210" stroke="#6366F1" stroke-width="1.2" marker-end="url(#arr)" />
                            <line x1="110" y1="236" x2="110" y2="249" stroke="#6366F1" stroke-width="1.2" marker-end="url(#arr)" />

                            <!-- back-loop arrow (JMP → LBL1) -->
                            <path d="M 60 223 Q 20 223 20 52 Q 20 35 58 35" fill="none" stroke="#6366F1" stroke-width="1.2" stroke-dasharray="4,2" marker-end="url(#arr)" />

                            <!-- False branch arrow (TST → LBL2) -->
                            <path d="M 160 145 Q 195 145 195 266 Q 195 283 162 283" fill="none" stroke="#F97316" stroke-width="1.2" stroke-dasharray="4,2" marker-end="url(#arr-o)" />
                            <text x="197" y="210" fill="#F97316" font-size="9" font-style="italic">False</text>

                            <defs>
                                <marker id="arr" markerWidth="6" markerHeight="6" refX="3" refY="3" orient="auto">
                                    <path d="M0,0 L6,3 L0,6 Z" fill="#6366F1" />
                                </marker>
                                <marker id="arr-o" markerWidth="6" markerHeight="6" refX="3" refY="3" orient="auto">
                                    <path d="M0,0 L6,3 L0,6 Z" fill="#F97316" />
                                </marker>
                            </defs>
                        </svg>
                        <figcaption><strong>while</strong> statement</figcaption>
                    </div>

                    <!-- for -->
                    <div class="flow-wrap">
                        <svg viewBox="0 0 220 480" width="210" xmlns="http://www.w3.org/2000/svg"
                            font-family="JetBrains Mono, monospace" font-size="12">

                            <!-- for keyword -->
                            <text x="110" y="18" text-anchor="middle" fill="#CBA6F7" font-weight="700" font-size="13">for</text>

                            <!-- ( Expr ; -->
                            <text x="110" y="40" text-anchor="middle" fill="#6C7086" font-size="11">( Expr ;</text>

                            <!-- LBL Lbl1 -->
                            <ellipse cx="110" cy="66" rx="52" ry="17" fill="#F0FDF4" stroke="#10B981" stroke-width="1.8" />
                            <text x="110" y="71" text-anchor="middle" fill="#065F46" font-weight="700" font-size="12">LBL Lbl1</text>

                            <!-- LBL Lbl4 -->
                            <ellipse cx="110" cy="104" rx="52" ry="17" fill="#F0FDF4" stroke="#10B981" stroke-width="1.8" />
                            <text x="110" y="109" text-anchor="middle" fill="#065F46" font-weight="700" font-size="12">LBL Lbl4</text>

                            <!-- LBL Lbl3 -->
                            <ellipse cx="110" cy="142" rx="52" ry="17" fill="#F0FDF4" stroke="#10B981" stroke-width="1.8" />
                            <text x="110" y="147" text-anchor="middle" fill="#065F46" font-weight="700" font-size="12">LBL Lbl3</text>

                            <!-- BoolExpr_Lbl2 ; -->
                            <text x="110" y="178" text-anchor="middle" fill="#6C7086" font-size="11">BoolExpr</text>
                            <text x="162" y="178" text-anchor="start" fill="#F9E2AF" font-size="10">Lbl2</text>
                            <text x="110" y="191" text-anchor="middle" fill="#6C7086" font-size="11">;</text>

                            <!-- TST diamond -->
                            <polygon points="110,200 160,222 110,244 60,222" fill="#FFF7ED" stroke="#F97316" stroke-width="1.8" />
                            <text x="110" y="218" text-anchor="middle" fill="#B45309" font-weight="700" font-size="10">TST</text>
                            <text x="110" y="230" text-anchor="middle" fill="#B45309" font-size="9">False→Lbl2</text>

                            <!-- JMP Lbl3 -->
                            <rect x="60" y="254" width="100" height="26" rx="6" fill="#EEF2FF" stroke="#6366F1" stroke-width="1.8" />
                            <text x="110" y="271" text-anchor="middle" fill="#4338CA" font-weight="700" font-size="12">JMP Lbl3</text>

                            <!-- Expr_q ) -->
                            <text x="110" y="302" text-anchor="middle" fill="#6C7086" font-size="11">Expr</text>
                            <text x="130" y="302" text-anchor="start" fill="#F9E2AF" font-size="10">q</text>
                            <text x="110" y="316" text-anchor="middle" fill="#6C7086" font-size="11">)</text>

                            <!-- JMP Lbl1 -->
                            <rect x="60" y="324" width="100" height="26" rx="6" fill="#EEF2FF" stroke="#6366F1" stroke-width="1.8" />
                            <text x="110" y="341" text-anchor="middle" fill="#4338CA" font-weight="700" font-size="12">JMP Lbl1</text>

                            <!-- Stmt -->
                            <text x="110" y="372" text-anchor="middle" fill="#6C7086" font-size="11">Stmt</text>

                            <!-- JMP Lbl4 -->
                            <rect x="60" y="382" width="100" height="26" rx="6" fill="#EEF2FF" stroke="#6366F1" stroke-width="1.8" />
                            <text x="110" y="399" text-anchor="middle" fill="#4338CA" font-weight="700" font-size="12">JMP Lbl4</text>

                            <!-- LBL Lbl2 -->
                            <ellipse cx="110" cy="436" rx="52" ry="17" fill="#F0FDF4" stroke="#10B981" stroke-width="1.8" />
                            <text x="110" y="441" text-anchor="middle" fill="#065F46" font-weight="700" font-size="12">LBL Lbl2</text>

                            <!-- connector lines (simplified) -->
                            <line x1="110" y1="83" x2="110" y2="87" stroke="#6366F1" stroke-width="1.2" />
                            <line x1="110" y1="121" x2="110" y2="125" stroke="#6366F1" stroke-width="1.2" />
                            <line x1="110" y1="159" x2="110" y2="168" stroke="#6366F1" stroke-width="1.2" />
                            <line x1="110" y1="194" x2="110" y2="198" stroke="#6366F1" stroke-width="1.2" />
                            <line x1="110" y1="244" x2="110" y2="254" stroke="#6366F1" stroke-width="1.2" />
                            <line x1="110" y1="280" x2="110" y2="295" stroke="#6366F1" stroke-width="1.2" />
                            <line x1="110" y1="318" x2="110" y2="324" stroke="#6366F1" stroke-width="1.2" />
                            <line x1="110" y1="350" x2="110" y2="365" stroke="#6366F1" stroke-width="1.2" />
                            <line x1="110" y1="368" x2="110" y2="382" stroke="#6366F1" stroke-width="1.2" />
                            <line x1="110" y1="408" x2="110" y2="419" stroke="#6366F1" stroke-width="1.2" />

                            <!-- JMP Lbl3 → LBL Lbl3 back arc -->
                            <path d="M 160 267 Q 200 267 200 142 Q 200 125 162 125" fill="none" stroke="#6366F1" stroke-width="1.2" stroke-dasharray="4,2" />

                            <!-- JMP Lbl4 → LBL Lbl4 back arc -->
                            <path d="M 60 395 Q 25 395 25 104 Q 25 87 58 87" fill="none" stroke="#6366F1" stroke-width="1.2" stroke-dasharray="4,2" />

                            <!-- JMP Lbl1 → LBL Lbl1 back arc -->
                            <path d="M 60 337 Q 8 337 8 66 Q 8 49 58 49" fill="none" stroke="#6366F1" stroke-width="1.2" stroke-dasharray="4,2" />

                            <!-- False branch →LBL Lbl2 -->
                            <path d="M 160 222 Q 215 222 215 436 Q 215 453 162 453" fill="none" stroke="#F97316" stroke-width="1.2" stroke-dasharray="4,2" />
                            <text x="216" y="340" fill="#F97316" font-size="9" font-style="italic">False</text>

                            <defs>
                                <marker id="arr2" markerWidth="6" markerHeight="6" refX="3" refY="3" orient="auto">
                                    <path d="M0,0 L6,3 L0,6 Z" fill="#6366F1" />
                                </marker>
                            </defs>
                        </svg>
                        <figcaption><strong>for</strong> statement</figcaption>
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Figure 4.18(b) if & BoolExpr         -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="fig418b">
                <h2><span class="sec-icon">↔️</span> Figure 4.18(b) — Control Structures for <code>if</code> Statements and Boolean Expressions</h2>

                <div class="two-flow">
                    <!-- if stmt -->
                    <div class="flow-wrap">
                        <svg viewBox="0 0 220 380" width="210" xmlns="http://www.w3.org/2000/svg"
                            font-family="JetBrains Mono, monospace" font-size="12">

                            <text x="110" y="18" text-anchor="middle" fill="#CBA6F7" font-weight="700" font-size="13">if</text>

                            <!-- ( BoolExpr_Lbl1 ) -->
                            <text x="110" y="40" text-anchor="middle" fill="#6C7086" font-size="11">( BoolExpr</text>
                            <text x="163" y="40" text-anchor="start" fill="#F9E2AF" font-size="10">Lbl1</text>
                            <text x="110" y="54" text-anchor="middle" fill="#6C7086" font-size="11">)</text>

                            <!-- TST diamond -->
                            <polygon points="110,65 160,90 110,115 60,90" fill="#FFF7ED" stroke="#F97316" stroke-width="1.8" />
                            <text x="110" y="86" text-anchor="middle" fill="#B45309" font-weight="700" font-size="10">TST</text>
                            <text x="110" y="98" text-anchor="middle" fill="#B45309" font-size="9">False→Lbl1</text>

                            <!-- Stmt -->
                            <text x="110" y="140" text-anchor="middle" fill="#6C7086" font-size="11">Stmt</text>

                            <!-- JMP Lbl2 -->
                            <rect x="60" y="150" width="100" height="26" rx="6" fill="#EEF2FF" stroke="#6366F1" stroke-width="1.8" />
                            <text x="110" y="167" text-anchor="middle" fill="#4338CA" font-weight="700" font-size="12">JMP Lbl2</text>

                            <!-- LBL Lbl1 -->
                            <ellipse cx="110" cy="210" rx="52" ry="17" fill="#F0FDF4" stroke="#10B981" stroke-width="1.8" />
                            <text x="110" y="215" text-anchor="middle" fill="#065F46" font-weight="700" font-size="12">LBL Lbl1</text>

                            <!-- ElsePart (may be omitted) -->
                            <text x="110" y="248" text-anchor="middle" fill="#6C7086" font-size="11">Else</text>
                            <text x="110" y="262" text-anchor="middle" fill="#6C7086" font-size="11">Stmt</text>

                            <!-- LBL Lbl2 -->
                            <ellipse cx="110" cy="298" rx="52" ry="17" fill="#F0FDF4" stroke="#10B981" stroke-width="1.8" />
                            <text x="110" y="303" text-anchor="middle" fill="#065F46" font-weight="700" font-size="12">LBL Lbl2</text>

                            <!-- ElsePart label -->
                            <text x="110" y="330" text-anchor="middle" fill="#6C7086" font-size="10" font-style="italic">ElsePart</text>
                            <text x="110" y="342" text-anchor="middle" fill="#6C7086" font-size="10" font-style="italic">(may be omitted)</text>

                            <!-- connector lines -->
                            <line x1="110" y1="57" x2="110" y2="63" stroke="#6366F1" stroke-width="1.2" />
                            <line x1="110" y1="115" x2="110" y2="132" stroke="#6366F1" stroke-width="1.2" />
                            <line x1="110" y1="145" x2="110" y2="150" stroke="#6366F1" stroke-width="1.2" />
                            <line x1="110" y1="176" x2="110" y2="193" stroke="#6366F1" stroke-width="1.2" />
                            <line x1="110" y1="227" x2="110" y2="240" stroke="#6366F1" stroke-width="1.2" />
                            <line x1="110" y1="265" x2="110" y2="281" stroke="#6366F1" stroke-width="1.2" />

                            <!-- False branch TST → LBL Lbl1 -->
                            <path d="M 160 90 Q 198 90 198 210 Q 198 227 162 227" fill="none" stroke="#F97316" stroke-width="1.2" stroke-dasharray="4,2" />
                            <text x="199" y="155" fill="#F97316" font-size="9" font-style="italic">False</text>

                            <!-- JMP Lbl2 → LBL Lbl2 -->
                            <path d="M 60 163 Q 15 163 15 298 Q 15 315 58 315" fill="none" stroke="#6366F1" stroke-width="1.2" stroke-dasharray="4,2" />
                        </svg>
                        <figcaption><strong>if</strong> statement (with optional else)</figcaption>
                    </div>

                    <!-- BoolExpr -->
                    <div class="flow-wrap">
                        <svg viewBox="0 0 220 200" width="210" xmlns="http://www.w3.org/2000/svg"
                            font-family="JetBrains Mono, monospace" font-size="12">

                            <text x="110" y="20" text-anchor="middle" fill="#CBA6F7" font-weight="700" font-size="13">boolean expr</text>
                            <text x="138" y="20" text-anchor="start" fill="#F9E2AF" font-size="10">lbl</text>

                            <!-- Expr_p -->
                            <text x="80" y="52" text-anchor="middle" fill="#6C7086" font-size="11">Expr</text>
                            <text x="100" y="52" text-anchor="start" fill="#F9E2AF" font-size="10">p</text>

                            <!-- compare_c -->
                            <text x="110" y="80" text-anchor="middle" fill="#A6E3A1" font-size="11">compare</text>
                            <text x="155" y="80" text-anchor="start" fill="#F9E2AF" font-size="10">c</text>

                            <!-- Expr_q -->
                            <text x="80" y="108" text-anchor="middle" fill="#6C7086" font-size="11">Expr</text>
                            <text x="100" y="108" text-anchor="start" fill="#F9E2AF" font-size="10">q</text>

                            <!-- TST diamond -->
                            <polygon points="110,120 170,148 110,176 50,148" fill="#FFF7ED" stroke="#F97316" stroke-width="1.8" />
                            <text x="110" y="143" text-anchor="middle" fill="#B45309" font-weight="700" font-size="10">TST</text>
                            <text x="110" y="156" text-anchor="middle" fill="#B45309" font-size="9">p,q,,7-c,Lbl</text>

                            <!-- lines -->
                            <line x1="110" y1="23" x2="110" y2="44" stroke="#6366F1" stroke-width="1" />
                            <line x1="110" y1="57" x2="110" y2="72" stroke="#6366F1" stroke-width="1" />
                            <line x1="110" y1="84" x2="110" y2="113" stroke="#6366F1" stroke-width="1" />
                            <line x1="110" y1="113" x2="110" y2="118" stroke="#6366F1" stroke-width="1" />
                        </svg>
                        <figcaption><strong>boolean expression</strong> always outputs a TST atom branching when comparison is <strong>false</strong></figcaption>
                    </div>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        For <code>if</code> statements, we must <strong>jump around the statement which is not
                            to be executed</strong>. This provides for a relatively simple translation. The boolean
                        expression attribute is the target label for the TST atom's branch — the label where
                        control goes when the comparison is <strong>false</strong>.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – for ↔ while Equivalence              -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="for-equiv">
                <h2><span class="sec-icon">🔁</span> <code>for</code> ↔ <code>while</code> Equivalence</h2>

                <p>
                    The <code>for</code> statement requires some additional explanation. The following
                    <code>for</code> statement and <code>while</code> statement are <strong>equivalent</strong>:
                </p>

                <div class="equiv-grid">
                    <div class="grammar-box" style="font-size:13px; line-height:2;">
                        <span class="kw">for</span> (E1 ; boolean ; E2)<br>
                        &nbsp;&nbsp;Stmt
                    </div>
                    <div class="equiv-arrow">≡</div>
                    <div class="grammar-box" style="font-size:13px; line-height:2;">
                        E1 ;<br>
                        <span class="kw">while</span> (boolean)<br>
                        &nbsp;&nbsp;{<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;Stmt<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;E2 ;<br>
                        &nbsp;&nbsp;}
                    </div>
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        This means that after the atoms for <code>Stmt</code> are put out, we must put out a
                        <strong>jump to the atoms corresponding to expression E2</strong>. In addition, there
                        must be a jump <strong>after the atoms of expression E2</strong> back to the beginning
                        of the loop (the boolean condition). The LL(2) grammar for Decaf shown in the next
                        section makes direct use of Figure 4.18 for the control structures.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Dangling else                         -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="else-dangling">
                <h2><span class="sec-icon">⚠️</span> The Dangling <code>else</code> Problem</h2>

                <p>
                    Unfortunately, the grammar shown above is <strong>not LL(1)</strong>. This can be seen by
                    finding the follow set of <code>ElsePart</code>, and noting that it contains the keyword
                    <code>else</code>. Consequently, rules 4 and 5 do not have disjoint selection sets.
                </p>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        However, it is still possible to write a <strong>recursive descent parser</strong>.
                        This is due to the fact that all <code>else</code>s are matched with the
                        <em>closest preceding unmatched if</em>. When our parser for the nonterminal
                        <code>ElsePart</code> encounters an <code>else</code>, it is <em>never wrong</em> to
                        apply rule 4, because the closest preceding unmatched <code>if</code> must be the one
                        on top of the recursive call stack.
                    </div>
                </div>

                <div class="note-box pro">
                    <span class="box-icon">📌</span>
                    <div>
                        Aho [1986] claims that there is no LL(1) grammar for this language. This is apparently
                        one of those rare instances where <strong>theory fails, but our practical knowledge of
                            how things work comes to the rescue</strong>.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Sample Problem 4.9                    -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="sample">
                <h2><span class="sec-icon">🧪</span> Sample Problem 4.9</h2>

                <div class="note-box key">
                    <span class="box-icon">📖</span>
                    <div>
                        <strong>Problem:</strong> Show the atom string which would be put out that corresponds
                        to the following Java statement:
                        <br><br>
                        <code style="font-family:'JetBrains Mono',monospace; font-size:14px;">while (x &gt; 0) Stmt</code>
                    </div>
                </div>

                <p><strong>Solution:</strong></p>

                <div class="output-seq">
                    <div class="atom-line lbl">(LBL, L1)</div>
                    <div class="atom-line">(TST, x, 0, , 4, L2)
                        <span style="color:#6C7086; font-size:11px; margin-left:10px; font-style:italic;">
                            // Branch to L2 if x &lt;= 0
                        </span>
                    </div>
                    <div class="atom-line stmt">Atoms for Stmt</div>
                    <div class="atom-line">(JMP, L1)</div>
                    <div class="atom-line lbl">(LBL, L2)</div>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        The condition <code>x &gt; 0</code> has comparison code <strong>3</strong>. Its
                        complement is <code>7 − 3 = 4</code>, which is <code>&lt;=</code>. So
                        <code>TST, x, 0, , 4, L2</code> branches to L2 when <code>x &lt;= 0</code>
                        (i.e., when <code>x &gt; 0</code> is <em>false</em>), skipping the loop body.
                    </div>
                </div>
            </section>

            <!-- ─────────────────────────────────────────────── -->
            <!-- SECTION – Exercises 4.9                         -->
            <!-- ─────────────────────────────────────────────── -->
            <section id="exercises">
                <h2><span class="sec-icon">📝</span> Exercises 4.9</h2>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 1 — Atom Sequences</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            Show the sequence of atoms which would be put out according to Figure 4.18 for each
                            of the following input strings:
                        </p>
                        <div class="grammar-box" style="line-height:2.3; font-size:13px;">
                            (a) <span class="kw">if</span> (a==b) <span class="kw">while</span> (x&lt;y) Stmt<br>
                            (b) <span class="kw">for</span> (i = 1; i&lt;=100; i = i+1)<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="kw">for</span> (j = 1; j&lt;=i; j = j+1) Stmt<br>
                            (c) <span class="kw">if</span> (a==b) <span class="kw">for</span> (i=1; i&lt;=20; i=i+1) Stmt1<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="kw">else</span> <span class="kw">while</span> (i&gt;0) Stmt2<br>
                            (d) <span class="kw">if</span> (a==b) <span class="kw">if</span> (b&gt;0) Stmt1 <span class="kw">else</span> <span class="kw">while</span> (i&gt;0) Stmt2
                        </div>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 2 — Attributed Translation Grammar Rules</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            Show an attributed translation grammar rule for each of the control structures given
                            in Figure 4.18. Assume <code>if</code> statements always have an <code>else</code>
                            part, and that there is a method <code>newlab</code> which allocates a new statement
                            label.
                        </p>
                        <div class="grammar-box" style="line-height:2.2; font-size:13px;">
                            <span class="rule-num">1.</span>
                            <span class="nt">WhileStmt</span>
                            <span class="arr">→</span>
                            <span class="kw">while</span>
                            <span class="t">(</span>
                            <span class="nt">BoolExpr</span>
                            <span class="t">)</span>
                            <span class="nt">Stmt</span><br>

                            <span class="rule-num">2.</span>
                            <span class="nt">ForStmt</span>
                            <span class="arr">→</span>
                            <span class="kw">for</span>
                            <span class="t">(</span>
                            <span class="nt">AssignExpr</span>
                            <span class="t">;</span>
                            <span class="nt">BoolExpr</span>
                            <span class="t">;</span><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="nt">AssignExpr</span>
                            <span class="t">)</span>
                            <span class="nt">Stmt</span><br>

                            <span class="rule-num">3.</span>
                            <span class="nt">IfStmt</span>
                            <span class="arr">→</span>
                            <span class="kw">if</span>
                            <span class="t">(</span>
                            <span class="nt">BoolExpr</span>
                            <span class="t">)</span>
                            <span class="nt">Stmt</span>
                            <span class="kw">else</span>
                            <span class="nt">Stmt</span>
                        </div>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 3 — Recursive Descent Translator</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            Show a recursive descent translator for your solutions to Problem 2. Show methods
                            for <code>WhileStmt</code>, <code>ForStmt</code>, and <code>IfStmt</code>.
                        </p>
                    </div>
                </div>

                <div class="algo-block">
                    <div class="algo-block-header">Exercise 4 — Loop Control Variable</div>
                    <div class="algo-block-body">
                        <p style="font-size:13.5px;">
                            Does your Java compiler permit a loop control variable to be altered inside the
                            loop, as in the following example?
                        </p>
                        <div class="grammar-box" style="font-size:13px; line-height:2;">
                            <span class="kw">for</span> (<span class="kw">int</span> i=<span style="color:#F38BA8">0</span>; i&lt;<span style="color:#F38BA8">100</span>; i = i+<span style="color:#F38BA8">1</span>)<br>
                            { System.out.println (i);<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;i = <span style="color:#F38BA8">100</span>;<br>
                            }
                        </div>
                    </div>
                </div>
            </section>

            <!-- ── Chapter nav ──────────────────────────────── -->
            <div class="chapter-nav">
                <a href="c4_8.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous</span>4.8 Decaf Expressions</div>
                </a>
                <a href="?complete=1" class="nav-btn next">
                    <div style="text-align:right">
                        <span class="btn-sub">Next page</span>
                        4.10 Next Topic
                    </div>
                    <span>→</span>
                </a>
            </div>

        </article>
    </div><!-- /.note-container -->

    <!-- ── Scripts ──────────────────────────────────────────── -->
    <script>
        // ── TOC scroll progress ──────────────────────────────
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
            tocLinks.forEach(l => l.classList.toggle('active', l.getAttribute('href') === '#' + current));
        });
    </script>

</body>

</html>