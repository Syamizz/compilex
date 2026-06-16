<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter 3.1 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c3/c3_1.css">
</head>

<body>

    <?php include '../dashboard.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#overview" class="toc-link">3.1 Overview</a></li>
                <li><a href="#g5" class="toc-link">G5 — Unambiguous Expressions</a></li>
                <li><a href="#fig39" class="toc-link sub">↳ Figure 3.9 — Parse Tree</a></li>
                <li><a href="#left-assoc" class="toc-link sub">↳ Left Associativity</a></li>
                <li><a href="#g6" class="toc-link">G6 — Dangling Else (Ambiguous)</a></li>
                <li><a href="#fig310" class="toc-link sub">↳ Two Trees for Dangling Else</a></li>
                <li><a href="#g7" class="toc-link">G7 — Unambiguous If-Else</a></li>
                <li><a href="#fig311" class="toc-link sub">↳ Figure 3.11 Parse Tree</a></li>
                <li><a href="#tree-builder" class="toc-link">🌳 Parse Tree Builder</a></li>
                <li><a href="#amb-checker" class="toc-link">⚠️ Ambiguity Checker</a></li>
                <li><a href="#sort-rules" class="toc-link">🔀 Rule Ordering Exercise</a></li>
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
                <div class="chapter-tag">📘 Chapter 3 · Section 3.1</div>
                <h1>Ambiguities in Programming Languages</h1>
                <div class="metadata">
                    <span>⏱ 18 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>📐 Grammar Design</span>
                </div>
            </header>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 3.1 Overview                                   -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="overview">
                <h2><span class="sec-icon">⚠️</span> 3.1 Why Ambiguity Matters</h2>
                <p>
                    Ambiguities in grammars for programming languages <strong>must be avoided</strong>. Recall:
                    a grammar is ambiguous if any string has more than one derivation tree. Since a derivation
                    tree reflects the <em>structure and meaning</em> of a program, different trees imply different
                    interpretations — which leads to inconsistent compiler behaviour.
                </p>
                <p>
                    There are two main strategies to resolve ambiguity:
                </p>
                <ol>
                    <li><strong>Rewrite the grammar</strong> to be equivalent but unambiguous (G4→G5, G6→G7)</li>
                    <li><strong>Specify a rule</strong> outside the grammar that disambiguates (e.g. "else always matches closest unmatched if")</li>
                </ol>
                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        Grammar G4 (from Section 3.0.3) was ambiguous for arithmetic expressions because it had no
                        built-in precedence or associativity. The fix (G5) introduces a hierarchy of nonterminals —
                        <code>Expr → Term → Factor</code> — that encodes precedence and left-associativity directly
                        into the grammar structure.
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- G5 — Unambiguous Arithmetic                   -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="g5">
                <h2><span class="sec-icon">✅</span> Grammar G5 — Unambiguous Arithmetic Expressions</h2>
                <p>
                    Grammar G5 is an <strong>unambiguous</strong> version of G4. It introduces a three-level
                    hierarchy that naturally encodes operator precedence and associativity:
                </p>

                <div class="grammar-box">
                    <div class="g-title">G5 — Unambiguous arithmetic expressions</div>
                    <div class="g-rule"><span class="g-num">1.</span><span class="g-nt">Expr</span><span style="color:#6C7086;"> → </span><span class="g-nt">Expr</span><span class="g-t"> + </span><span class="g-nt">Term</span><span style="color:#6C7086;font-size:11px;font-style:italic;margin-left:12px;">// left-associative +</span></div>
                    <div class="g-rule"><span class="g-num">2.</span><span class="g-nt">Expr</span><span style="color:#6C7086;"> → </span><span class="g-nt">Term</span></div>
                    <div class="g-rule"><span class="g-num">3.</span><span class="g-nt">Term</span><span style="color:#6C7086;"> → </span><span class="g-nt">Term</span><span class="g-t"> * </span><span class="g-nt">Factor</span><span style="color:#6C7086;font-size:11px;font-style:italic;margin-left:12px;">// left-associative *</span></div>
                    <div class="g-rule"><span class="g-num">4.</span><span class="g-nt">Term</span><span style="color:#6C7086;"> → </span><span class="g-nt">Factor</span></div>
                    <div class="g-rule"><span class="g-num">5.</span><span class="g-nt">Factor</span><span style="color:#6C7086;"> → </span><span class="g-t">( </span><span class="g-nt">Expr</span><span class="g-t"> )</span></div>
                    <div class="g-rule"><span class="g-num">6.</span><span class="g-nt">Factor</span><span style="color:#6C7086;"> → </span><span class="g-t">var</span></div>
                    <div class="g-rule"><span class="g-num">7.</span><span class="g-nt">Factor</span><span style="color:#6C7086;"> → </span><span class="g-t">const</span></div>
                </div>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Nonterminal</th>
                            <th>Role</th>
                            <th>Precedence level</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>Expr</code></td>
                            <td>Addition/subtraction level</td>
                            <td>Lowest (evaluated last)</td>
                        </tr>
                        <tr>
                            <td><code>Term</code></td>
                            <td>Multiplication/division level</td>
                            <td>Middle</td>
                        </tr>
                        <tr>
                            <td><code>Factor</code></td>
                            <td>Atomic values: vars, consts, parenthesised expressions</td>
                            <td>Highest (evaluated first)</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box key">
                    <span class="box-icon">✅</span>
                    <div>
                        In G5, <strong>every subtree in the derivation tree corresponds to a subexpression</strong>,
                        according to the usual precedence rules. There is only <em>one</em> derivation tree for any
                        given expression — proving G5 is unambiguous.
                    </div>
                </div>
            </section>

            <!-- Figure 3.9 -->
            <section id="fig39">
                <h2><span class="sec-icon">🌳</span> Figure 3.9 — Parse Tree for <code>var + var * var</code> (G5)</h2>
                <p>
                    This derivation tree shows that <code>*</code> binds tighter than <code>+</code> — the
                    multiplication subtree appears deeper in the tree. There is only one possible tree for
                    this expression in G5, proving it is unambiguous.
                </p>

                <div class="tree-panel">
                    <div class="tree-panel-title">Figure 3.9 — var + var * var parsed by G5</div>
                    <svg width="100%" viewBox="0 0 420 240" style="display:block;max-width:520px;margin:0 auto;">
                        <defs>
                            <marker id="ta" markerWidth="6" markerHeight="6" refX="3" refY="3" orient="auto">
                                <circle cx="3" cy="3" r="2.5" fill="#6366F1" opacity=".4" />
                            </marker>
                        </defs>
                        <!-- Edges: Expr → Expr + Term -->
                        <line x1="210" y1="28" x2="90" y2="68" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="210" y1="28" x2="200" y2="68" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="210" y1="28" x2="330" y2="68" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <!-- Expr (left) → Term → Factor → var -->
                        <line x1="90" y1="84" x2="90" y2="120" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="90" y1="136" x2="90" y2="172" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <!-- Term (right) → Term * Factor -->
                        <line x1="330" y1="84" x2="270" y2="120" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="330" y1="84" x2="330" y2="120" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="330" y1="84" x2="390" y2="120" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <!-- Term inner → Factor → var -->
                        <line x1="270" y1="136" x2="270" y2="172" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="270" y1="188" x2="270" y2="220" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <!-- Factor (right) → var -->
                        <line x1="390" y1="136" x2="390" y2="172" stroke="#6366F1" stroke-width="1.5" opacity=".4" />

                        <!-- Nodes -->
                        <circle cx="210" cy="20" r="16" fill="rgba(99,102,241,.15)" stroke="#6366F1" stroke-width="1.8" />
                        <text x="210" y="25" text-anchor="middle" fill="#818CF8" font-size="11" font-weight="700" font-family="Syne">Expr</text>
                        <circle cx="90" cy="76" r="16" fill="rgba(99,102,241,.10)" stroke="#6366F1" stroke-width="1.5" />
                        <text x="90" y="81" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">Expr</text>
                        <circle cx="200" cy="76" r="12" fill="rgba(245,158,11,.12)" stroke="#F59E0B" stroke-width="1.4" />
                        <text x="200" y="81" text-anchor="middle" fill="#F59E0B" font-size="13" font-weight="700" font-family="JetBrains Mono">+</text>
                        <circle cx="330" cy="76" r="16" fill="rgba(99,102,241,.12)" stroke="#6366F1" stroke-width="1.6" />
                        <text x="330" y="81" text-anchor="middle" fill="#818CF8" font-size="11" font-weight="700" font-family="Syne">Term</text>
                        <circle cx="90" cy="128" r="14" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.4" />
                        <text x="90" y="133" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">Term</text>
                        <circle cx="90" cy="178" r="14" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.4" />
                        <text x="90" y="183" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">Factor</text>
                        <circle cx="90" cy="220" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                        <text x="90" y="225" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">var</text>
                        <circle cx="270" cy="128" r="14" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.4" />
                        <text x="270" y="133" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">Term</text>
                        <circle cx="330" cy="128" r="12" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.4" />
                        <text x="330" y="133" text-anchor="middle" fill="#F87171" font-size="13" font-weight="700" font-family="JetBrains Mono">*</text>
                        <circle cx="390" cy="128" r="14" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.4" />
                        <text x="390" y="133" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">Factor</text>
                        <circle cx="270" cy="178" r="14" fill="rgba(99,102,241,.08)" stroke="#6366F1" stroke-width="1.4" />
                        <text x="270" y="183" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">Factor</text>
                        <circle cx="270" cy="220" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                        <text x="270" y="225" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">var</text>
                        <circle cx="390" cy="178" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                        <text x="390" y="183" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">var</text>

                        <text x="210" y="20" text-anchor="middle" dx="0" dy="0" fill="none" />
                        <text x="70" y="240" fill="#10B981" font-size="9" font-family="DM Sans">Term deeper → * has higher precedence ✓</text>
                    </svg>
                </div>
            </section>

            <!-- Left associativity -->
            <section id="left-assoc">
                <h2><span class="sec-icon">⬅️</span> Left Associativity in G5</h2>
                <p>
                    Grammar G5 also enforces <strong>left associativity</strong> for both <code>+</code> and
                    <code>*</code>. Consider <code>var + var + var</code>: in G5, Rule 1 is
                    <code>Expr → Expr + Term</code>, which always places the left operand as an
                    <code>Expr</code> subtree. This forces the tree to be left-leaning — meaning
                    <code>a + b + c</code> is parsed as <code>(a + b) + c</code>, not <code>a + (b + c)</code>.
                </p>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin:20px 0;">
                    <div class="tree-panel">
                        <div class="tree-panel-title">Left-associative: (var + var) + var ✅</div>
                        <svg width="100%" viewBox="0 0 200 160" style="display:block;">
                            <line x1="100" y1="22" x2="55" y2="58" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="100" y1="22" x2="100" y2="58" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="100" y1="22" x2="155" y2="58" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="55" y1="74" x2="25" y2="110" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="55" y1="74" x2="55" y2="110" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="55" y1="74" x2="85" y2="110" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <circle cx="100" cy="16" r="14" fill="rgba(99,102,241,.15)" stroke="#6366F1" stroke-width="1.8" />
                            <text x="100" y="21" text-anchor="middle" fill="#818CF8" font-size="10" font-weight="700" font-family="Syne">Expr</text>
                            <circle cx="55" cy="66" r="13" fill="rgba(99,102,241,.10)" stroke="#6366F1" stroke-width="1.5" />
                            <text x="55" y="71" text-anchor="middle" fill="#A5B4FC" font-size="10" font-weight="700" font-family="Syne">Expr</text>
                            <circle cx="100" cy="66" r="11" fill="rgba(245,158,11,.12)" stroke="#F59E0B" stroke-width="1.3" />
                            <text x="100" y="71" text-anchor="middle" fill="#F59E0B" font-size="12" font-weight="700" font-family="JetBrains Mono">+</text>
                            <circle cx="155" cy="66" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="155" y="71" text-anchor="middle" fill="#10B981" font-size="10" font-family="JetBrains Mono">var</text>
                            <circle cx="25" cy="118" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="25" y="123" text-anchor="middle" fill="#10B981" font-size="10" font-family="JetBrains Mono">var</text>
                            <circle cx="55" cy="118" r="11" fill="rgba(245,158,11,.12)" stroke="#F59E0B" stroke-width="1.3" />
                            <text x="55" y="123" text-anchor="middle" fill="#F59E0B" font-size="12" font-weight="700" font-family="JetBrains Mono">+</text>
                            <circle cx="85" cy="118" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="85" y="123" text-anchor="middle" fill="#10B981" font-size="10" font-family="JetBrains Mono">var</text>
                            <text x="100" y="148" text-anchor="middle" fill="#10B981" font-size="9" font-family="DM Sans">(a+b)+c — left subtree is deeper ✓</text>
                        </svg>
                    </div>
                    <div class="tree-panel">
                        <div class="tree-panel-title">Right-associative: var + (var + var) ✗ — not in G5</div>
                        <svg width="100%" viewBox="0 0 200 160" style="display:block;">
                            <line x1="100" y1="22" x2="45" y2="58" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="100" y1="22" x2="100" y2="58" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="100" y1="22" x2="155" y2="58" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="155" y1="74" x2="125" y2="110" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="155" y1="74" x2="155" y2="110" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="155" y1="74" x2="185" y2="110" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <circle cx="100" cy="16" r="14" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.8" />
                            <text x="100" y="21" text-anchor="middle" fill="#F87171" font-size="10" font-weight="700" font-family="Syne">Expr</text>
                            <circle cx="45" cy="66" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="45" y="71" text-anchor="middle" fill="#10B981" font-size="10" font-family="JetBrains Mono">var</text>
                            <circle cx="100" cy="66" r="11" fill="rgba(245,158,11,.12)" stroke="#F59E0B" stroke-width="1.3" />
                            <text x="100" y="71" text-anchor="middle" fill="#F59E0B" font-size="12" font-weight="700" font-family="JetBrains Mono">+</text>
                            <circle cx="155" cy="66" r="13" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.5" />
                            <text x="155" y="71" text-anchor="middle" fill="#F87171" font-size="10" font-weight="700" font-family="Syne">Expr</text>
                            <circle cx="125" cy="118" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="125" y="123" text-anchor="middle" fill="#10B981" font-size="10" font-family="JetBrains Mono">var</text>
                            <circle cx="155" cy="118" r="11" fill="rgba(245,158,11,.12)" stroke="#F59E0B" stroke-width="1.3" />
                            <text x="155" y="123" text-anchor="middle" fill="#F59E0B" font-size="12" font-weight="700" font-family="JetBrains Mono">+</text>
                            <circle cx="185" cy="118" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="185" y="123" text-anchor="middle" fill="#10B981" font-size="10" font-family="JetBrains Mono">var</text>
                            <text x="100" y="148" text-anchor="middle" fill="#EF4444" font-size="9" font-family="DM Sans">a+(b+c) — right subtree deeper ✗ not from G5</text>
                        </svg>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- G6 — Dangling Else                            -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="g6">
                <h2><span class="sec-icon">⚠️</span> Grammar G6 — The Dangling Else Problem</h2>
                <p>
                    Another classic example of ambiguity in programming languages is the
                    <strong>dangling else</strong> problem. Grammar G6 defines conditional statements:
                </p>

                <div class="grammar-box">
                    <div class="g-title">G6 — Ambiguous if-else (part of a larger grammar)</div>
                    <div class="g-rule"><span class="g-num">1.</span><span class="g-nt">Stmt</span><span style="color:#6C7086;"> → </span><span class="g-nt">IfStmt</span></div>
                    <div class="g-rule"><span class="g-num">2.</span><span class="g-nt">IfStmt</span><span style="color:#6C7086;"> → </span><span class="g-t">if ( </span><span class="g-nt">Expr</span><span class="g-t"> ) </span><span class="g-nt">Stmt</span></div>
                    <div class="g-rule"><span class="g-num">3.</span><span class="g-nt">IfStmt</span><span style="color:#6C7086;"> → </span><span class="g-t">if ( </span><span class="g-nt">Expr</span><span class="g-t"> ) </span><span class="g-nt">Stmt</span><span class="g-t"> else </span><span class="g-nt">Stmt</span></div>
                </div>

                <p>
                    The string <code>if (Expr) if (Expr) Stmt else Stmt</code> has
                    <strong>two different derivation trees</strong> (Figure 3.10), corresponding to two different
                    interpretations of which <code>if</code> the <code>else</code> belongs to.
                </p>
            </section>

            <!-- Figure 3.10 -->
            <section id="fig310">
                <h2><span class="sec-icon">🔀</span> Figure 3.10 — Two Parse Trees for the Dangling Else</h2>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin:20px 0;">
                    <div class="tree-panel">
                        <div class="tree-panel-title">Tree 1 — else matches OUTER if ✗ (wrong)</div>
                        <svg width="100%" viewBox="0 0 260 230" style="display:block;">
                            <line x1="130" y1="22" x2="130" y2="56" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="72" x2="40" y2="108" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="72" x2="130" y2="108" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="72" x2="220" y2="108" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="124" x2="130" y2="158" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="174" x2="90" y2="210" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="174" x2="170" y2="210" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <circle cx="130" cy="16" r="14" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.8" />
                            <text x="130" y="21" text-anchor="middle" fill="#F87171" font-size="10" font-weight="700" font-family="Syne">Stmt</text>
                            <circle cx="130" cy="64" r="14" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.6" />
                            <text x="130" y="69" text-anchor="middle" fill="#F87171" font-size="10" font-weight="700" font-family="Syne">IfStmt</text>
                            <circle cx="40" cy="116" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="40" y="121" text-anchor="middle" fill="#10B981" font-size="9" font-family="JetBrains Mono">if(E)</text>
                            <circle cx="130" cy="116" r="12" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.4" />
                            <text x="130" y="121" text-anchor="middle" fill="#F87171" font-size="9" font-weight="700" font-family="Syne">Stmt</text>
                            <circle cx="220" cy="116" r="12" fill="rgba(245,158,11,.1)" stroke="#F59E0B" stroke-width="1.3" />
                            <text x="220" y="121" text-anchor="middle" fill="#F59E0B" font-size="9" font-family="JetBrains Mono">Stmt</text>
                            <circle cx="130" cy="166" r="13" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.4" />
                            <text x="130" y="171" text-anchor="middle" fill="#F87171" font-size="9" font-weight="700" font-family="Syne">IfStmt</text>
                            <circle cx="90" cy="218" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="90" y="223" text-anchor="middle" fill="#10B981" font-size="9" font-family="JetBrains Mono">if(E)</text>
                            <circle cx="170" cy="218" r="12" fill="rgba(245,158,11,.1)" stroke="#F59E0B" stroke-width="1.3" />
                            <text x="170" y="223" text-anchor="middle" fill="#F59E0B" font-size="9" font-family="JetBrains Mono">Stmt</text>
                            <text x="130" y="232" text-anchor="middle" fill="#EF4444" font-size="9" font-family="DM Sans" dy="10">else → outer if ✗</text>
                        </svg>
                    </div>
                    <div class="tree-panel">
                        <div class="tree-panel-title">Tree 2 — else matches INNER if ✅ (correct)</div>
                        <svg width="100%" viewBox="0 0 260 230" style="display:block;">
                            <line x1="130" y1="22" x2="130" y2="56" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="72" x2="130" y2="108" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="124" x2="50" y2="160" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="124" x2="130" y2="160" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="124" x2="210" y2="160" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="176" x2="90" y2="210" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="176" x2="130" y2="210" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="130" y1="176" x2="175" y2="210" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <circle cx="130" cy="16" r="14" fill="rgba(99,102,241,.15)" stroke="#6366F1" stroke-width="1.8" />
                            <text x="130" y="21" text-anchor="middle" fill="#818CF8" font-size="10" font-weight="700" font-family="Syne">Stmt</text>
                            <circle cx="130" cy="64" r="14" fill="rgba(99,102,241,.12)" stroke="#6366F1" stroke-width="1.6" />
                            <text x="130" y="69" text-anchor="middle" fill="#818CF8" font-size="10" font-weight="700" font-family="Syne">IfStmt</text>
                            <circle cx="130" cy="116" r="14" fill="rgba(99,102,241,.10)" stroke="#6366F1" stroke-width="1.5" />
                            <text x="130" y="121" text-anchor="middle" fill="#A5B4FC" font-size="10" font-weight="700" font-family="Syne">IfStmt</text>
                            <circle cx="50" cy="168" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="50" y="173" text-anchor="middle" fill="#10B981" font-size="9" font-family="JetBrains Mono">if(E)</text>
                            <circle cx="130" cy="168" r="14" fill="rgba(99,102,241,.10)" stroke="#6366F1" stroke-width="1.5" />
                            <text x="130" y="173" text-anchor="middle" fill="#A5B4FC" font-size="9" font-weight="700" font-family="Syne">Stmt</text>
                            <circle cx="210" cy="168" r="12" fill="rgba(245,158,11,.1)" stroke="#F59E0B" stroke-width="1.3" />
                            <text x="210" y="173" text-anchor="middle" fill="#F59E0B" font-size="9" font-family="JetBrains Mono">Stmt</text>
                            <circle cx="90" cy="218" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.3" />
                            <text x="90" y="223" text-anchor="middle" fill="#10B981" font-size="9" font-family="JetBrains Mono">if(E)</text>
                            <circle cx="130" cy="218" r="12" fill="rgba(245,158,11,.1)" stroke="#F59E0B" stroke-width="1.3" />
                            <text x="130" y="223" text-anchor="middle" fill="#F59E0B" font-size="9" font-family="JetBrains Mono">Stmt</text>
                            <circle cx="175" cy="218" r="12" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.3" />
                            <text x="175" y="223" text-anchor="middle" fill="#F87171" font-size="9" font-family="JetBrains Mono">else</text>
                            <text x="130" y="232" text-anchor="middle" fill="#10B981" font-size="9" font-family="DM Sans" dy="10">else → inner if ✓ (closest unmatched)</text>
                        </svg>
                    </div>
                </div>

                <div class="note-box warn">
                    <span class="box-icon">⚠️</span>
                    <div>
                        This ambiguity is normally resolved by the rule: <strong>"an else always associates with the
                            closest previous unmatched if"</strong>. Tree 2 is the correct interpretation in Java, C, and
                        most languages. Grammar G7 encodes this rule directly into the grammar structure.
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- G7 — Unambiguous If-Else                      -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="g7">
                <h2><span class="sec-icon">✅</span> Grammar G7 — Unambiguous If-Else</h2>
                <p>
                    G7 rewrites G6 to be unambiguous by differentiating between two kinds of if statements —
                    those with a matching else (<strong>Matched</strong>) and those without (<strong>Unmatched</strong>).
                </p>

                <div class="grammar-box">
                    <div class="g-title">G7 — Unambiguous if-else grammar</div>
                    <div class="g-rule"><span class="g-num">1.</span><span class="g-nt">Stmt</span><span style="color:#6C7086;"> → </span><span class="g-nt">IfStmt</span></div>
                    <div class="g-rule"><span class="g-num">2.</span><span class="g-nt">IfStmt</span><span style="color:#6C7086;"> → </span><span class="g-nt">Matched</span></div>
                    <div class="g-rule"><span class="g-num">3.</span><span class="g-nt">IfStmt</span><span style="color:#6C7086;"> → </span><span class="g-nt">Unmatched</span></div>
                    <div class="g-rule"><span class="g-num">4.</span><span class="g-nt">Matched</span><span style="color:#6C7086;"> → </span><span class="g-t">if ( </span><span class="g-nt">Expr</span><span class="g-t"> ) </span><span class="g-nt">Matched</span><span class="g-t"> else </span><span class="g-nt">Matched</span></div>
                    <div class="g-rule"><span class="g-num">5.</span><span class="g-nt">Matched</span><span style="color:#6C7086;"> → </span><span class="g-nt">OtherStmt</span></div>
                    <div class="g-rule"><span class="g-num">6.</span><span class="g-nt">Unmatched</span><span style="color:#6C7086;"> → </span><span class="g-t">if ( </span><span class="g-nt">Expr</span><span class="g-t"> ) </span><span class="g-nt">Stmt</span></div>
                    <div class="g-rule"><span class="g-num">7.</span><span class="g-nt">Unmatched</span><span style="color:#6C7086;"> → </span><span class="g-t">if ( </span><span class="g-nt">Expr</span><span class="g-t"> ) </span><span class="g-nt">Matched</span><span class="g-t"> else </span><span class="g-nt">Unmatched</span></div>
                </div>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Nonterminal</th>
                            <th>Meaning</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>Matched</code></td>
                            <td>An if-statement that has a matching else (or a non-if statement)</td>
                        </tr>
                        <tr>
                            <td><code>Unmatched</code></td>
                            <td>An if-statement without a matching else — the "dangling" case</td>
                        </tr>
                        <tr>
                            <td><code>OtherStmt</code></td>
                            <td>Any non-if statement: while, for, expression, etc.</td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box key">
                    <span class="box-icon">✅</span>
                    <div>
                        Rule 4 requires that both the "then" branch and the "else" branch of a matched if are themselves
                        <code>Matched</code>. This prevents an else-less if from appearing in the "then" branch
                        (which would create a dangling else), forcing the else to always match the closest if.
                    </div>
                </div>
            </section>

            <!-- Figure 3.11 -->
            <section id="fig311">
                <h2><span class="sec-icon">🌳</span> Figure 3.11 — Parse Tree Using G7</h2>
                <p>
                    Using Grammar G7, the string
                    <code>if (Expr) if (Expr) OtherStmt else OtherStmt</code>
                    has only <strong>one</strong> derivation tree, shown below. The <code>else</code>
                    matches the inner <code>if</code>, as enforced by the grammar.
                </p>

                <div class="tree-panel">
                    <div class="tree-panel-title">Figure 3.11 — Unique parse tree using G7 (else matches inner if)</div>
                    <svg width="100%" viewBox="0 0 480 250" style="display:block;max-width:560px;margin:0 auto;">
                        <line x1="240" y1="26" x2="240" y2="60" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="240" y1="76" x2="240" y2="110" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="240" y1="126" x2="240" y2="160" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="240" y1="176" x2="80" y2="212" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="240" y1="176" x2="240" y2="212" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="240" y1="176" x2="400" y2="212" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="240" y1="228" x2="180" y2="248" stroke="#6366F1" stroke-width="1.2" opacity=".3" />
                        <line x1="240" y1="228" x2="300" y2="248" stroke="#6366F1" stroke-width="1.2" opacity=".3" />
                        <!-- nodes -->
                        <circle cx="240" cy="18" r="14" fill="rgba(99,102,241,.15)" stroke="#6366F1" stroke-width="1.8" />
                        <text x="240" y="23" text-anchor="middle" fill="#818CF8" font-size="11" font-weight="700" font-family="Syne">Stmt</text>
                        <circle cx="240" cy="68" r="14" fill="rgba(99,102,241,.12)" stroke="#6366F1" stroke-width="1.6" />
                        <text x="240" y="73" text-anchor="middle" fill="#818CF8" font-size="11" font-weight="700" font-family="Syne">IfStmt</text>
                        <circle cx="240" cy="118" r="16" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.6" />
                        <text x="240" y="123" text-anchor="middle" fill="#F87171" font-size="11" font-weight="700" font-family="Syne">Unmatched</text>
                        <circle cx="240" cy="168" r="14" fill="rgba(99,102,241,.10)" stroke="#6366F1" stroke-width="1.5" />
                        <text x="240" y="173" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">IfStmt</text>
                        <circle cx="80" cy="220" r="14" fill="rgba(16,185,129,.12)" stroke="#10B981" stroke-width="1.6" />
                        <text x="80" y="225" text-anchor="middle" fill="#10B981" font-size="10" font-weight="700" font-family="Syne">Matched</text>
                        <circle cx="240" cy="220" r="12" fill="rgba(245,158,11,.1)" stroke="#F59E0B" stroke-width="1.3" />
                        <text x="240" y="225" text-anchor="middle" fill="#F59E0B" font-size="9" font-family="JetBrains Mono">if(E)</text>
                        <circle cx="400" cy="220" r="14" fill="rgba(16,185,129,.12)" stroke="#10B981" stroke-width="1.6" />
                        <text x="400" y="225" text-anchor="middle" fill="#10B981" font-size="10" font-weight="700" font-family="Syne">Matched</text>
                        <!-- bottom labels -->
                        <text x="80" y="243" text-anchor="middle" fill="#10B981" font-size="9" font-family="JetBrains Mono">if(E) Matched else Matched</text>
                        <text x="80" y="255" text-anchor="middle" fill="#6C7086" font-size="9" font-family="DM Sans">(Rule 4)</text>
                        <text x="400" y="243" text-anchor="middle" fill="#10B981" font-size="9" font-family="JetBrains Mono">OtherStmt</text>
                        <text x="400" y="255" text-anchor="middle" fill="#6C7086" font-size="9" font-family="DM Sans">(Rule 5)</text>
                        <text x="120" y="270" text-anchor="middle" fill="#10B981" font-size="10" font-family="DM Sans">else → inner if ✓ (only one derivation possible in G7)</text>
                    </svg>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Interactive: Parse Tree Builder               -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="tree-builder">
                <h2><span class="sec-icon">🌳</span> Parse Tree Builder — G5 Expressions</h2>
                <p>
                    Enter an arithmetic expression using <code>var</code>, <code>const</code>, <code>+</code>,
                    <code>*</code>, and parentheses. The builder will show the step-by-step parse using G5
                    and display the derivation.
                </p>

                <div class="tree-builder">
                    <div class="tb-header">
                        <h4>🌲 G5 Parse Tree Builder</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);">Uses Grammar G5</span>
                    </div>
                    <div class="tb-body">
                        <div class="tb-controls">
                            <select class="tb-sel" id="tb-expr-sel" onchange="buildTree()">
                                <option value="v+v*v">var + var * var</option>
                                <option value="v*v">var * var</option>
                                <option value="v*v+v">var * var + var</option>
                                <option value="(v+v)*v">(var + var) * var</option>
                                <option value="v*v*v">var * var * var</option>
                                <option value="v">var</option>
                            </select>
                            <button class="tb-btn sec" onclick="buildTree()">↺ Refresh</button>
                        </div>
                        <div id="tb-output" style="background:var(--code-bg);border-radius:var(--radius);padding:16px;font-family:'JetBrains Mono',monospace;font-size:12.5px;color:#CDD6F4;line-height:2;"></div>
                        <div id="tb-tree-svg" style="margin-top:16px;overflow-x:auto;"></div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Ambiguity Checker                             -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="amb-checker">
                <h2><span class="sec-icon">⚠️</span> Ambiguity Checker — G6 vs G7</h2>
                <p>
                    Test whether an <code>if</code>/<code>else</code> structure is ambiguous under G6.
                    Select the nesting pattern and see how many derivation trees it produces.
                </p>

                <div class="amb-wrap">
                    <div class="amb-header">
                        <h4>🔍 If-Else Ambiguity Tester</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);">G6 vs G7</span>
                    </div>
                    <div class="amb-body">
                        <select class="amb-input" id="amb-sel" style="width:auto;font-size:13px;padding:8px 12px;cursor:pointer;">
                            <option value="if_s">if (E) Stmt</option>
                            <option value="if_else">if (E) Stmt else Stmt</option>
                            <option value="if_if_s">if (E) if (E) Stmt</option>
                            <option value="if_if_s_else_s">if (E) if (E) Stmt else Stmt</option>
                            <option value="if_s_else_if_s">if (E) Stmt else if (E) Stmt</option>
                        </select>
                        <button class="amb-btn" style="margin-top:0;margin-left:8px;" onclick="checkAmbiguity()">Check Ambiguity</button>
                        <div id="amb-result" class="amb-result" style="display:none;"></div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Rule Ordering Exercise                        -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="sort-rules">
                <h2><span class="sec-icon">🔀</span> Rule Ordering Exercise — Build G5</h2>
                <p>
                    The rules of Grammar G5 are shown in a scrambled order. <strong>Drag to reorder them</strong>
                    so that the grammar correctly encodes precedence: Expr (lowest) → Term → Factor (highest).
                    Check your answer when done.
                </p>

                <div class="sort-wrap">
                    <div class="sort-header">
                        <h4>🔀 Reorder G5 Rules</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);">Drag to reorder · then check</span>
                    </div>
                    <div class="sort-body">
                        <div id="sort-list">
                            <div class="sort-item" draggable="true" data-rule="Factor → var" data-correct="6" ondragstart="sortDragStart(event)" ondragover="sortDragOver(event)" ondragleave="sortDragLeave(event)" ondrop="sortDrop(event)">
                                <span class="si-num">?</span><span class="si-rule"><span style="color:#CBA6F7;">Factor</span> <span style="color:#6C7086;">→</span> <span style="color:#A6E3A1;">var</span></span><span class="si-drag">⠿</span>
                            </div>
                            <div class="sort-item" draggable="true" data-rule="Expr → Expr + Term" data-correct="1" ondragstart="sortDragStart(event)" ondragover="sortDragOver(event)" ondragleave="sortDragLeave(event)" ondrop="sortDrop(event)">
                                <span class="si-num">?</span><span class="si-rule"><span style="color:#CBA6F7;">Expr</span> <span style="color:#6C7086;">→</span> <span style="color:#CBA6F7;">Expr</span> <span style="color:#A6E3A1;">+</span> <span style="color:#CBA6F7;">Term</span></span><span class="si-drag">⠿</span>
                            </div>
                            <div class="sort-item" draggable="true" data-rule="Term → Factor" data-correct="4" ondragstart="sortDragStart(event)" ondragover="sortDragOver(event)" ondragleave="sortDragLeave(event)" ondrop="sortDrop(event)">
                                <span class="si-num">?</span><span class="si-rule"><span style="color:#CBA6F7;">Term</span> <span style="color:#6C7086;">→</span> <span style="color:#CBA6F7;">Factor</span></span><span class="si-drag">⠿</span>
                            </div>
                            <div class="sort-item" draggable="true" data-rule="Factor → ( Expr )" data-correct="5" ondragstart="sortDragStart(event)" ondragover="sortDragOver(event)" ondragleave="sortDragLeave(event)" ondrop="sortDrop(event)">
                                <span class="si-num">?</span><span class="si-rule"><span style="color:#CBA6F7;">Factor</span> <span style="color:#6C7086;">→</span> <span style="color:#A6E3A1;">( </span><span style="color:#CBA6F7;">Expr</span><span style="color:#A6E3A1;"> )</span></span><span class="si-drag">⠿</span>
                            </div>
                            <div class="sort-item" draggable="true" data-rule="Term → Term * Factor" data-correct="3" ondragstart="sortDragStart(event)" ondragover="sortDragOver(event)" ondragleave="sortDragLeave(event)" ondrop="sortDrop(event)">
                                <span class="si-num">?</span><span class="si-rule"><span style="color:#CBA6F7;">Term</span> <span style="color:#6C7086;">→</span> <span style="color:#CBA6F7;">Term</span> <span style="color:#A6E3A1;">*</span> <span style="color:#CBA6F7;">Factor</span></span><span class="si-drag">⠿</span>
                            </div>
                            <div class="sort-item" draggable="true" data-rule="Factor → const" data-correct="7" ondragstart="sortDragStart(event)" ondragover="sortDragOver(event)" ondragleave="sortDragLeave(event)" ondrop="sortDrop(event)">
                                <span class="si-num">?</span><span class="si-rule"><span style="color:#CBA6F7;">Factor</span> <span style="color:#6C7086;">→</span> <span style="color:#A6E3A1;">const</span></span><span class="si-drag">⠿</span>
                            </div>
                            <div class="sort-item" draggable="true" data-rule="Expr → Term" data-correct="2" ondragstart="sortDragStart(event)" ondragover="sortDragOver(event)" ondragleave="sortDragLeave(event)" ondrop="sortDrop(event)">
                                <span class="si-num">?</span><span class="si-rule"><span style="color:#CBA6F7;">Expr</span> <span style="color:#6C7086;">→</span> <span style="color:#CBA6F7;">Term</span></span><span class="si-drag">⠿</span>
                            </div>
                        </div>
                        <button class="sort-check-btn" onclick="checkSort()">✓ Check Order</button>
                        <button class="sort-reset-btn" onclick="resetSort()">↺ Shuffle Again</button>
                        <div class="sort-result" id="sort-result"></div>
                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- DRAG AND DROP — 5 answers                     -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="dragdrop">
                <h2><span class="sec-icon">🎯</span> Drag &amp; Drop Quiz — Ambiguity Concepts</h2>

                <!-- DnD Q1 -->
                <div class="dnd-quiz" id="dnd1">
                    <div class="dnd-header" style="background:linear-gradient(135deg,#6366F1,#818CF8);">
                        <div class="dnd-q-num" style="background:rgba(255,255,255,.2);color:white;">1</div>
                        <div class="dnd-q-text" style="color:white;">Match each grammar or rule to its correct description</div>
                    </div>
                    <div class="dnd-body">
                        <p class="dnd-prompt">Drag the grammar names to the correct description zones.</p>
                        <div class="dnd-bank" id="bank1" ondrop="dropChip(event,'bank1')" ondragover="allowDrop(event)">
                            <div class="dnd-chip chip-a" draggable="true" ondragstart="dragStart(event)" id="c1-g4">G4</div>
                            <div class="dnd-chip chip-b" draggable="true" ondragstart="dragStart(event)" id="c1-g5">G5</div>
                            <div class="dnd-chip chip-c" draggable="true" ondragstart="dragStart(event)" id="c1-g6">G6</div>
                            <div class="dnd-chip chip-d" draggable="true" ondragstart="dragStart(event)" id="c1-g7">G7</div>
                            <div class="dnd-chip chip-e" draggable="true" ondragstart="dragStart(event)" id="c1-matched">Matched</div>
                        </div>
                        <div class="dnd-zones">
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#F59E0B;">Ambiguous arithmetic expressions</div>
                                <div class="dnd-zone" id="z1a" ondrop="dropChip(event,'z1a')" ondragover="allowDrop(event)" data-answer="c1-g4"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#3B82F6;">Unambiguous arithmetic (Expr→Term→Factor)</div>
                                <div class="dnd-zone" id="z1b" ondrop="dropChip(event,'z1b')" ondragover="allowDrop(event)" data-answer="c1-g5"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#9F1239;">Ambiguous dangling else</div>
                                <div class="dnd-zone" id="z1c" ondrop="dropChip(event,'z1c')" ondragover="allowDrop(event)" data-answer="c1-g6"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#10B981;">Unambiguous if-else (Matched/Unmatched)</div>
                                <div class="dnd-zone" id="z1d" ondrop="dropChip(event,'z1d')" ondragover="allowDrop(event)" data-answer="c1-g7"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#6366F1;">If-stmt with a corresponding else</div>
                                <div class="dnd-zone" id="z1e" ondrop="dropChip(event,'z1e')" ondragover="allowDrop(event)" data-answer="c1-matched"></div>
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
                        <div class="dnd-q-text" style="color:white;">In G5, which nonterminal handles each expression type?</div>
                    </div>
                    <div class="dnd-body">
                        <p class="dnd-prompt">Match each expression or operator to the nonterminal in G5 that handles it.</p>
                        <div class="dnd-bank" id="bank2" ondrop="dropChip(event,'bank2')" ondragover="allowDrop(event)">
                            <div class="dnd-chip chip-a" draggable="true" ondragstart="dragStart(event)" id="c2-expr">Expr</div>
                            <div class="dnd-chip chip-b" draggable="true" ondragstart="dragStart(event)" id="c2-term">Term</div>
                            <div class="dnd-chip chip-c" draggable="true" ondragstart="dragStart(event)" id="c2-factor">Factor</div>
                            <div class="dnd-chip chip-d" draggable="true" ondragstart="dragStart(event)" id="c2-expr2">Expr (again)</div>
                            <div class="dnd-chip chip-e" draggable="true" ondragstart="dragStart(event)" id="c2-factor2">Factor (again)</div>
                        </div>
                        <div class="dnd-zones">
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#F59E0B;">Addition operator <code>+</code></div>
                                <div class="dnd-zone" id="z2a" ondrop="dropChip(event,'z2a')" ondragover="allowDrop(event)" data-answer="c2-expr"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#3B82F6;">Multiplication operator <code>*</code></div>
                                <div class="dnd-zone" id="z2b" ondrop="dropChip(event,'z2b')" ondragover="allowDrop(event)" data-answer="c2-term"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#9F1239;">The atom <code>var</code></div>
                                <div class="dnd-zone" id="z2c" ondrop="dropChip(event,'z2c')" ondragover="allowDrop(event)" data-answer="c2-factor"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#10B981;">Grouping <code>(Expr)</code></div>
                                <div class="dnd-zone" id="z2d" ondrop="dropChip(event,'z2d')" ondragover="allowDrop(event)" data-answer="c2-factor2"></div>
                            </div>
                            <div class="dnd-zone-wrap">
                                <div class="dnd-zone-label" style="color:#6366F1;">Whole expression <code>var+var*var</code></div>
                                <div class="dnd-zone" id="z2e" ondrop="dropChip(event,'z2e')" ondragover="allowDrop(event)" data-answer="c2-expr2"></div>
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
                        <p class="quiz-q">In Grammar G5, the rule <code>Expr → Expr + Term</code> (not <code>Expr → Term + Expr</code>) enforces which property?</p>
                        <div class="quiz-options" id="q1-opts">
                            <div class="quiz-opt" onclick="answer(this,'q1',false)">
                                <div class="opt-circle">A</div> Right associativity — a+b+c is parsed as a+(b+c)
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q1',true)">
                                <div class="opt-circle">B</div> Left associativity — a+b+c is parsed as (a+b)+c, because Expr on the left keeps expanding leftward
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q1',false)">
                                <div class="opt-circle">C</div> Higher precedence for addition over multiplication
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q1',false)">
                                <div class="opt-circle">D</div> Unambiguity — it simply reduces the number of rules
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
                        <p class="quiz-q">Why does Grammar G5 use three levels — <code>Expr</code>, <code>Term</code>, <code>Factor</code>?</p>
                        <div class="quiz-options" id="q2-opts">
                            <div class="quiz-opt" onclick="answer(this,'q2',false)">
                                <div class="opt-circle">A</div> To allow more complex expressions including arrays and function calls
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q2',true)">
                                <div class="opt-circle">B</div> To encode operator precedence — multiplication (*) binds tighter than addition (+) because Term appears at a deeper level than Expr
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q2',false)">
                                <div class="opt-circle">C</div> To reduce the number of grammar rules
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q2',false)">
                                <div class="opt-circle">D</div> To make the grammar context-sensitive instead of context-free
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
                        <p class="quiz-q">The dangling else problem says <code>else</code> always associates with the closest previous unmatched <code>if</code>. In G7, which rule enforces this?</p>
                        <div class="quiz-options" id="q3-opts">
                            <div class="quiz-opt" onclick="answer(this,'q3',false)">
                                <div class="opt-circle">A</div> Rule 6: <code>Unmatched → if (Expr) Stmt</code>
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q3',true)">
                                <div class="opt-circle">B</div> Rule 4: <code>Matched → if (Expr) Matched else Matched</code> — it requires both branches of a matched if to themselves be Matched, preventing an else-less if from appearing in the then-branch
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q3',false)">
                                <div class="opt-circle">C</div> Rule 7: <code>Unmatched → if (Expr) Matched else Unmatched</code>
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q3',false)">
                                <div class="opt-circle">D</div> Rule 2: <code>IfStmt → Matched</code>
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
                        <p class="quiz-q">Grammar G6 produces two derivation trees for <code>if (E) if (E) Stmt else Stmt</code>. Which tree represents the interpretation used in Java?</p>
                        <div class="quiz-options" id="q4-opts">
                            <div class="quiz-opt" onclick="answer(this,'q4',false)">
                                <div class="opt-circle">A</div> Tree 1 — else matches the outer if
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q4',true)">
                                <div class="opt-circle">B</div> Tree 2 — else matches the inner if (closest unmatched if), which is the standard convention
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q4',false)">
                                <div class="opt-circle">C</div> Either tree — Java leaves this ambiguous
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q4',false)">
                                <div class="opt-circle">D</div> Neither — Java requires braces to disambiguate
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
                        <p class="quiz-q">Grammar G4 is <em>equivalent</em> to Grammar G5 (L(G4) = L(G5)). What does this mean?</p>
                        <div class="quiz-options" id="q5-opts">
                            <div class="quiz-opt" onclick="answer(this,'q5',false)">
                                <div class="opt-circle">A</div> They have exactly the same rules — just written in a different order
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q5',false)">
                                <div class="opt-circle">B</div> G4 can generate more strings than G5
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q5',true)">
                                <div class="opt-circle">C</div> They generate exactly the same set of strings — the language is the same, but G5's derivation trees uniquely reflect the intended structure whereas G4's do not
                            </div>
                            <div class="quiz-opt" onclick="answer(this,'q5',false)">
                                <div class="opt-circle">D</div> G5 generates a strict subset of the strings G4 generates
                            </div>
                        </div>
                        <div class="quiz-feedback" id="q5-fb"></div>
                    </div>
                </div>

            </section>

            <div class="chapter-nav">
                <a href="c3_0.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous section</span>3.0.3 – 3.0.5 CFGs &amp; Pushdown Machines</div>
                </a>
                <a href="c3_2.php" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next section</span>3.2 The Parsing Problem</div>
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
        // PARSE TREE BUILDER (G5)
        // ════════════════════════════════════════════════════════
        function buildTree() {
            const sel = document.getElementById('tb-expr-sel').value;
            const exprMap = {
                'v+v*v': {
                    steps: ['Expr', 'Expr + Term', 'Term + Term', 'Factor + Term', 'var + Term', 'var + Term * Factor', 'var + Factor * Factor', 'var + var * Factor', 'var + var * var'],
                    result: 'var + var * var',
                    desc: '* deeper → higher precedence ✓'
                },
                'v*v': {
                    steps: ['Expr', 'Term', 'Term * Factor', 'Factor * Factor', 'var * Factor', 'var * var'],
                    result: 'var * var',
                    desc: 'simple multiplication'
                },
                'v*v+v': {
                    steps: ['Expr', 'Expr + Term', 'Term + Term', 'Term * Factor + Term', 'Factor * Factor + Term', 'var * Factor + Term', 'var * var + Term', 'var * var + Factor', 'var * var + var'],
                    result: 'var * var + var',
                    desc: '* higher precedence ✓ left assoc ✓'
                },
                '(v+v)*v': {
                    steps: ['Expr', 'Term', 'Term * Factor', 'Factor * Factor', '( Expr ) * Factor', '( Expr + Term ) * Factor', '( Term + Term ) * Factor', '( Factor + Factor ) * Factor', '( var + var ) * Factor', '( var + var ) * var'],
                    result: '(var + var) * var',
                    desc: 'parentheses override precedence ✓'
                },
                'v*v*v': {
                    steps: ['Expr', 'Term', 'Term * Factor', 'Term * Factor * Factor', 'Factor * Factor * Factor', 'var * Factor * Factor', 'var * var * Factor', 'var * var * var'],
                    result: 'var * var * var',
                    desc: 'left-associative: (var*var)*var ✓'
                },
                'v': {
                    steps: ['Expr', 'Term', 'Factor', 'var'],
                    result: 'var',
                    desc: 'atomic expression'
                }
            };
            const e = exprMap[sel];
            const out = document.getElementById('tb-output');
            out.innerHTML = e.steps.map((step, i) => {
                const prefix = i === 0 ? '<span style="color:#6C7086;">Deriving: </span>' : '<span style="color:#6C7086;">⇒ </span>';
                const coloured = step
                    .replace(/\b(Expr|Term|Factor)\b/g, '<span style="color:#CBA6F7;font-weight:700;">$1</span>')
                    .replace(/\b(var|const)\b/g, '<span style="color:#A6E3A1;">$1</span>')
                    .replace(/([+*()])/g, '<span style="color:#F38BA8;">$1</span>');
                return prefix + coloured;
            }).join('<br>') + `<br><span style="color:#6EE7B7;font-weight:700;">Result: ${e.result}</span> <span style="color:#6C7086;font-style:italic;">(${e.desc})</span>`;

            // Simple SVG tree
            const svg = document.getElementById('tb-tree-svg');
            svg.innerHTML = `<div style="background:var(--purple-s);border-radius:10px;padding:12px 16px;font-size:13px;color:var(--purple);font-family:'DM Sans',sans-serif;">
        <strong>✅ Parse successful</strong> — "${e.result}" is in L(G5). 
        Derivation has ${e.steps.length} steps. ${e.desc}
      </div>`;
        }

        buildTree();

        // ════════════════════════════════════════════════════════
        // AMBIGUITY CHECKER
        // ════════════════════════════════════════════════════════
        const AMB_DATA = {
            if_s: {
                trees: 1,
                g6: 'Unambiguous — only 1 tree',
                g7: '1 tree (Unmatched rule 6)'
            },
            if_else: {
                trees: 1,
                g6: 'Unambiguous — only 1 tree',
                g7: '1 tree (Matched rule 4, OtherStmt)'
            },
            if_if_s: {
                trees: 1,
                g6: 'Unambiguous — only 1 tree (no else to assign)',
                g7: '1 tree'
            },
            if_if_s_else_s: {
                trees: 2,
                g6: 'AMBIGUOUS — 2 trees! else can match inner or outer if',
                g7: '1 tree (G7 forces else → inner if via Matched rule)'
            },
            if_s_else_if_s: {
                trees: 1,
                g6: 'Unambiguous — else clearly belongs to first if',
                g7: '1 tree'
            }
        };

        function checkAmbiguity() {
            const sel = document.getElementById('amb-sel').value;
            const d = AMB_DATA[sel];
            const res = document.getElementById('amb-result');
            res.style.display = 'block';
            if (d.trees > 1) {
                res.className = 'amb-result ambig';
                res.innerHTML = `⚠️ <strong>AMBIGUOUS under G6!</strong> ${d.g6}<br><br>✅ <strong>G7 resolves this:</strong> ${d.g7}`;
            } else {
                res.className = 'amb-result ok';
                res.innerHTML = `✅ <strong>Unambiguous under G6</strong> — ${d.g6}<br>G7 also gives: ${d.g7}`;
            }
        }

        // ════════════════════════════════════════════════════════
        // SORTABLE RULE ORDERING
        // ════════════════════════════════════════════════════════
        let sortDragging = null;

        function sortDragStart(e) {
            sortDragging = e.currentTarget;
            e.currentTarget.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
        }

        function sortDragOver(e) {
            e.preventDefault();
            if (e.currentTarget !== sortDragging) {
                e.currentTarget.classList.add('over');
            }
        }

        function sortDragLeave(e) {
            e.currentTarget.classList.remove('over');
        }

        function sortDrop(e) {
            e.preventDefault();
            const target = e.currentTarget;
            target.classList.remove('over');
            if (target === sortDragging) return;
            const list = document.getElementById('sort-list');
            const items = [...list.children];
            const fromIdx = items.indexOf(sortDragging);
            const toIdx = items.indexOf(target);
            if (fromIdx < toIdx) list.insertBefore(sortDragging, target.nextSibling);
            else list.insertBefore(sortDragging, target);
            sortDragging.classList.remove('dragging');
            sortDragging = null;
            updateSortNums();
        }
        document.addEventListener('dragend', () => {
            if (sortDragging) {
                sortDragging.classList.remove('dragging');
                sortDragging = null;
            }
        });

        function updateSortNums() {
            [...document.getElementById('sort-list').children].forEach((item, i) => {
                item.querySelector('.si-num').textContent = i + 1;
            });
        }

        function checkSort() {
            const items = [...document.getElementById('sort-list').children];
            const correctOrder = ['1', '2', '3', '4', '5', '6', '7'];
            let correct = 0;
            items.forEach((item, i) => {
                if (item.dataset.correct === correctOrder[i]) correct++;
            });
            const res = document.getElementById('sort-result');
            res.style.display = 'block';
            if (correct === 7) {
                res.className = 'sort-result show ok';
                res.innerHTML = '✅ <strong>Perfect!</strong> G5 rules in the correct order: Expr (addition) → Term (multiplication) → Factor (atomic). This hierarchy encodes precedence!';
            } else {
                res.className = 'sort-result show bad';
                res.innerHTML = `❌ <strong>${correct}/7 correct.</strong> Hint: Expr rules first (rules 1–2), then Term rules (3–4), then Factor rules (5–7). The order matters because Term nests inside Expr gives * higher precedence than +.`;
            }
        }

        function resetSort() {
            const list = document.getElementById('sort-list');
            const items = [...list.children];
            for (let i = items.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                list.insertBefore(items[j], items[i]);
                [items[i], items[j]] = [items[j], items[i]];
            }
            updateSortNums();
            const res = document.getElementById('sort-result');
            res.style.display = 'none';
            res.innerHTML = '';
        }

        updateSortNums();

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