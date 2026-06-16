<?php
session_start();
include '../dbconn.php';
include '../save_progress.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$chapter = 3;
$page = 3;
$nextPage = "c3_0_4.php";

if (isset($_GET['complete']) && $_GET['complete'] == '3') {
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
    <title>Chapter 3.0.3–3.0.5 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c3/c3_0_0.css">
</head>

<body>

    <?php include 'navbar_c3.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#cfg" class="toc-link">3.0.3 Context-Free Grammars</a></li>
                <li><a href="#bnf" class="toc-link sub">↳ BNF Notation</a></li>
                <li><a href="#deriv-trees" class="toc-link sub">↳ Derivation Trees</a></li>
                <li><a href="#ambiguous" class="toc-link sub">↳ Ambiguous Grammars</a></li>
                <li><a href="#leftmost" class="toc-link sub">↳ Left-Most Derivation</a></li>
                <li><a href="#sp-c" class="toc-link sub">↳ Sample Problem (c)</a></li>
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
                <div class="chapter-tag">📘 Chapter 3 · Sections 3.0.3 – 3.0.5</div>
                <h1>Context-Free Grammars &amp; Pushdown Machines</h1>
                <div class="metadata">
                    <span>⏱ 22 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>📐 Formal Languages</span>
                </div>
            </header>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 3.0.3 Context-Free Grammars                   -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="cfg">
                <h2><span class="sec-icon">📐</span> 3.0.3 Context-Free Grammars</h2>
                <p>
                    Most programming languages are defined by <strong>context-free grammars (CFGs)</strong>.
                    Although there are aspects of programming languages that cannot be fully specified with a CFG
                    (e.g. declaring a variable before using it), using more complex grammars would only serve to
                    <em>confuse rather than clarify</em>, and context-sensitive grammars cannot be used
                    practically to construct a compiler.
                </p>
                <p>
                    Recall: a context-free grammar rule has the form <code>A → α</code> — a single nonterminal
                    on the left rewrites to any string of terminals and nonterminals on the right, regardless of context.
                </p>
            </section>

            <!-- BNF -->
            <section id="bnf">
                <h2><span class="sec-icon">🔣</span> BNF Notation</h2>
                <p>
                    Context-free grammars are commonly written in <strong>Backus-Naur Form (BNF)</strong>,
                    in which nonterminals are enclosed in angle brackets <code>&lt;&gt;</code>
                    and the arrow <code>→</code> is replaced by <code>::=</code>. Multiple alternatives
                    for the same nonterminal can be given on one line using the <strong>vertical bar</strong> <code>|</code>.
                </p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin:16px 0;">
                    <div>
                        <p style="font-size:13px;color:var(--muted);margin-bottom:8px;"><strong>BNF form:</strong></p>
                        <div class="grammar-box" style="margin:0;">
                            <div style="font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;line-height:2;">
                                &lt;S&gt; ::= <span style="color:#A6E3A1;">a</span> &lt;S&gt; <span style="color:#A6E3A1;">b</span> | <span style="color:#F38BA8;">ε</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p style="font-size:13px;color:var(--muted);margin-bottom:8px;"><strong>Equivalent grammar rules:</strong></p>
                        <div class="grammar-box" style="margin:0;">
                            <div class="g-rule"><span class="g-num">1.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">a</span> <span class="g-nt">S</span> <span class="g-t">b</span></div>
                            <div class="g-rule"><span class="g-num">2.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-ep">ε</span></div>
                        </div>
                    </div>
                </div>
                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>BNF and context-free grammars are <strong>equivalent forms</strong>. The textbook uses regular grammar notation (<code>→</code>) rather than BNF (<code>::=</code>) purely for appearance. Both describe exactly the same languages.</div>
                </div>
            </section>

            <!-- Derivation Trees -->
            <section id="deriv-trees">
                <h2><span class="sec-icon">🌳</span> Derivation Trees</h2>
                <p>
                    A <strong>derivation tree</strong> (also called a <em>parse tree</em>) visually represents a derivation:
                </p>
                <ul>
                    <li>Each <strong>interior node</strong> corresponds to a nonterminal in the sentential form</li>
                    <li>Each <strong>leaf node</strong> corresponds to a terminal symbol in the derived string</li>
                </ul>
                <p>
                    The derivation tree does more than show that a string is in the language — it shows the
                    <strong>structure of the string</strong>, which may affect its meaning (semantics).
                    Below is the derivation tree for <code>aaabbb</code> using Grammar G2:
                </p>

                <div class="tree-panel" style="margin:20px 0;">
                    <div class="tree-panel-title">Figure 3.2 — Derivation tree for aaabbb using G2</div>
                    <svg width="100%" viewBox="0 0 360 200" style="display:block;max-width:360px;margin:0 auto;">
                        <defs>
                            <marker id="ta" markerWidth="6" markerHeight="6" refX="3" refY="3" orient="auto">
                                <circle cx="3" cy="3" r="2.5" fill="#6366F1" opacity=".4" />
                            </marker>
                        </defs>
                        <!-- edges -->
                        <line x1="180" y1="26" x2="100" y2="60" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="180" y1="26" x2="180" y2="60" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="180" y1="26" x2="260" y2="60" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="100" y1="76" x2="60" y2="110" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="180" y1="76" x2="120" y2="110" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="180" y1="76" x2="180" y2="110" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="180" y1="76" x2="240" y2="110" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="260" y1="76" x2="300" y2="110" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="120" y1="126" x2="90" y2="160" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="180" y1="126" x2="150" y2="160" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="180" y1="126" x2="180" y2="160" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="180" y1="126" x2="210" y2="160" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <line x1="240" y1="126" x2="270" y2="160" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                        <!-- S root -->
                        <circle cx="180" cy="18" r="16" fill="rgba(99,102,241,.15)" stroke="#6366F1" stroke-width="1.8" />
                        <text x="180" y="23" text-anchor="middle" fill="#818CF8" font-size="13" font-weight="700" font-family="Syne">S</text>
                        <!-- Level 1: A S B -->
                        <circle cx="100" cy="68" r="14" fill="rgba(99,102,241,.1)" stroke="#6366F1" stroke-width="1.5" />
                        <text x="100" y="73" text-anchor="middle" fill="#A5B4FC" font-size="12" font-weight="700" font-family="Syne">A</text>
                        <circle cx="180" cy="68" r="14" fill="rgba(99,102,241,.15)" stroke="#6366F1" stroke-width="1.8" />
                        <text x="180" y="73" text-anchor="middle" fill="#818CF8" font-size="13" font-weight="700" font-family="Syne">S</text>
                        <circle cx="260" cy="68" r="14" fill="rgba(99,102,241,.1)" stroke="#6366F1" stroke-width="1.5" />
                        <text x="260" y="73" text-anchor="middle" fill="#A5B4FC" font-size="12" font-weight="700" font-family="Syne">B</text>
                        <!-- A→a -->
                        <circle cx="60" cy="118" r="13" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                        <text x="60" y="123" text-anchor="middle" fill="#10B981" font-size="12" font-weight="700" font-family="JetBrains Mono">a</text>
                        <!-- S → A S B (inner) -->
                        <circle cx="120" cy="118" r="13" fill="rgba(99,102,241,.1)" stroke="#6366F1" stroke-width="1.4" />
                        <text x="120" y="123" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">A</text>
                        <circle cx="180" cy="118" r="13" fill="rgba(99,102,241,.12)" stroke="#6366F1" stroke-width="1.6" />
                        <text x="180" y="123" text-anchor="middle" fill="#818CF8" font-size="12" font-weight="700" font-family="Syne">S</text>
                        <circle cx="240" cy="118" r="13" fill="rgba(99,102,241,.1)" stroke="#6366F1" stroke-width="1.4" />
                        <text x="240" y="123" text-anchor="middle" fill="#A5B4FC" font-size="11" font-weight="700" font-family="Syne">B</text>
                        <!-- B→b -->
                        <circle cx="300" cy="118" r="13" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                        <text x="300" y="123" text-anchor="middle" fill="#10B981" font-size="12" font-weight="700" font-family="JetBrains Mono">b</text>
                        <!-- inner A→a, S→ε, B→b -->
                        <circle cx="90" cy="168" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.2" />
                        <text x="90" y="173" text-anchor="middle" fill="#10B981" font-size="12" font-weight="700" font-family="JetBrains Mono">a</text>
                        <circle cx="150" cy="168" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.2" />
                        <text x="150" y="173" text-anchor="middle" fill="#10B981" font-size="12" font-weight="700" font-family="JetBrains Mono">a</text>
                        <circle cx="180" cy="168" r="12" fill="rgba(245,158,11,.1)" stroke="#F59E0B" stroke-width="1.2" />
                        <text x="180" y="173" text-anchor="middle" fill="#F59E0B" font-size="12" font-style="italic" font-family="JetBrains Mono">ε</text>
                        <circle cx="210" cy="168" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.2" />
                        <text x="210" y="173" text-anchor="middle" fill="#10B981" font-size="12" font-weight="700" font-family="JetBrains Mono">b</text>
                        <circle cx="270" cy="168" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.2" />
                        <text x="270" y="173" text-anchor="middle" fill="#10B981" font-size="12" font-weight="700" font-family="JetBrains Mono">b</text>
                        <!-- leaf labels -->
                        <text x="180" y="195" text-anchor="middle" fill="#6C7086" font-size="10" font-family="DM Sans">leaves: a a ε b b → aaabbb (ε ignored)</text>
                    </svg>
                </div>
            </section>

            <!-- Ambiguous Grammars -->
            <section id="ambiguous">
                <h2><span class="sec-icon">⚠️</span> Ambiguous Grammars — Grammar G4</h2>
                <p>
                    A context-free grammar is <strong>ambiguous</strong> if there is <em>more than one derivation tree</em>
                    for some string. This is like an ambiguous phrase in natural language — multiple interpretations.
                </p>

                <div class="grammar-box">
                    <div class="g-title">G4 — Simple arithmetic expressions (ambiguous)</div>
                    <div class="g-rule"><span class="g-num">1.</span><span class="g-nt">Expr</span><span style="color:#6C7086;"> → </span><span class="g-nt">Expr</span><span style="color:#F38BA8;"> + </span><span class="g-nt">Expr</span></div>
                    <div class="g-rule"><span class="g-num">2.</span><span class="g-nt">Expr</span><span style="color:#6C7086;"> → </span><span class="g-nt">Expr</span><span style="color:#F38BA8;"> * </span><span class="g-nt">Expr</span></div>
                    <div class="g-rule"><span class="g-num">3.</span><span class="g-nt">Expr</span><span style="color:#6C7086;"> → </span><span class="g-t">(</span><span class="g-nt">Expr</span><span class="g-t">)</span></div>
                    <div class="g-rule"><span class="g-num">4.</span><span class="g-nt">Expr</span><span style="color:#6C7086;"> → </span><span class="g-t">var</span></div>
                    <div class="g-rule"><span class="g-num">5.</span><span class="g-nt">Expr</span><span style="color:#6C7086;"> → </span><span class="g-t">const</span></div>
                </div>

                <p>
                    For the string <code>var + var * var</code>, G4 produces <strong>two different derivation trees</strong>
                    (Figure 3.3). The <em>second</em> tree is the correct interpretation because it shows multiplication
                    taking precedence over addition — as defined in most programming languages. A non-ambiguous grammar
                    for expressions will be given in Section 3.1.
                </p>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin:20px 0;">
                    <div class="tree-panel">
                        <div class="tree-panel-title">Tree 1 — var + (var * var) ✅ correct</div>
                        <svg width="100%" viewBox="0 0 240 180" style="display:block;">
                            <line x1="120" y1="24" x2="60" y2="64" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="120" y1="24" x2="120" y2="64" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="120" y1="24" x2="190" y2="64" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="190" y1="80" x2="155" y2="120" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="190" y1="80" x2="190" y2="120" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <line x1="190" y1="80" x2="225" y2="120" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                            <circle cx="120" cy="16" r="14" fill="rgba(99,102,241,.15)" stroke="#6366F1" stroke-width="1.8" />
                            <text x="120" y="21" text-anchor="middle" fill="#818CF8" font-size="11" font-weight="700" font-family="Syne">Expr</text>
                            <circle cx="60" cy="72" r="13" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                            <text x="60" y="77" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">var</text>
                            <circle cx="120" cy="72" r="13" fill="rgba(245,158,11,.1)" stroke="#F59E0B" stroke-width="1.4" />
                            <text x="120" y="77" text-anchor="middle" fill="#F59E0B" font-size="13" font-weight="700" font-family="JetBrains Mono">+</text>
                            <circle cx="190" cy="72" r="14" fill="rgba(99,102,241,.12)" stroke="#6366F1" stroke-width="1.6" />
                            <text x="190" y="77" text-anchor="middle" fill="#818CF8" font-size="11" font-weight="700" font-family="Syne">Expr</text>
                            <circle cx="155" cy="128" r="13" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                            <text x="155" y="133" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">var</text>
                            <circle cx="190" cy="128" r="13" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.4" />
                            <text x="190" y="133" text-anchor="middle" fill="#F87171" font-size="13" font-weight="700" font-family="JetBrains Mono">*</text>
                            <circle cx="225" cy="128" r="13" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                            <text x="225" y="133" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">var</text>
                            <text x="120" y="165" text-anchor="middle" fill="#10B981" font-size="10" font-family="DM Sans">* has higher precedence ✓</text>
                        </svg>
                    </div>
                    <div class="tree-panel">
                        <div class="tree-panel-title">Tree 2 — (var + var) * var ❌ wrong precedence</div>
                        <svg width="100%" viewBox="0 0 240 180" style="display:block;">
                            <line x1="120" y1="24" x2="50" y2="64" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="120" y1="24" x2="120" y2="64" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="120" y1="24" x2="190" y2="64" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="50" y1="80" x2="20" y2="120" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="50" y1="80" x2="50" y2="120" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <line x1="50" y1="80" x2="85" y2="120" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                            <circle cx="120" cy="16" r="14" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.8" />
                            <text x="120" y="21" text-anchor="middle" fill="#F87171" font-size="11" font-weight="700" font-family="Syne">Expr</text>
                            <circle cx="50" cy="72" r="14" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.6" />
                            <text x="50" y="77" text-anchor="middle" fill="#F87171" font-size="11" font-weight="700" font-family="Syne">Expr</text>
                            <circle cx="120" cy="72" r="13" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.4" />
                            <text x="120" y="77" text-anchor="middle" fill="#F87171" font-size="13" font-weight="700" font-family="JetBrains Mono">*</text>
                            <circle cx="190" cy="72" r="13" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                            <text x="190" y="77" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">var</text>
                            <circle cx="20" cy="128" r="13" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                            <text x="20" y="133" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">var</text>
                            <circle cx="50" cy="128" r="13" fill="rgba(245,158,11,.1)" stroke="#F59E0B" stroke-width="1.4" />
                            <text x="50" y="133" text-anchor="middle" fill="#F59E0B" font-size="13" font-weight="700" font-family="JetBrains Mono">+</text>
                            <circle cx="85" cy="128" r="13" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                            <text x="85" y="133" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">var</text>
                            <text x="120" y="165" text-anchor="middle" fill="#EF4444" font-size="10" font-family="DM Sans">+ has same precedence as * ✗</text>
                        </svg>
                    </div>
                </div>
            </section>

            <!-- Left-Most Derivation -->
            <section id="leftmost">
                <h2><span class="sec-icon">⬅️</span> Left-Most &amp; Right-Most Derivations</h2>
                <p>
                    A <strong>left-most derivation</strong> is one in which the left-most nonterminal is always
                    the one to which a rule is applied. Example for G2 (left-most):
                </p>
                <div style="background:var(--code-bg);border-radius:var(--radius);padding:16px 22px;margin:14px 0;font-family:'JetBrains Mono',monospace;font-size:13px;color:#CDD6F4;line-height:2.2;">
                    <span style="color:#CBA6F7;">S</span> ⇒ <span style="color:#CBA6F7;">A</span>SB ⇒ <span style="color:#A6E3A1;">a</span>SB ⇒ a<span style="color:#CBA6F7;">A</span>SBB ⇒ a<span style="color:#A6E3A1;">a</span>SBB ⇒ aa<span style="color:#CBA6F7;">S</span>BB ⇒ aaBB ⇒ aa<span style="color:#CBA6F7;">B</span>B ⇒ aa<span style="color:#A6E3A1;">b</span>B ⇒ aabb
                    <div style="font-size:11px;color:#6C7086;margin-top:4px;">← always expanding the leftmost nonterminal</div>
                </div>

                <div class="note-box key">
                    <span class="box-icon">✅</span>
                    <div>
                        Left-most and right-most derivations are <strong>normal forms</strong> for derivations.
                        If two different derivations produce the same left-most derivation, they correspond to the
                        <em>same derivation tree</em>. There is a <strong>one-to-one correspondence</strong> between
                        derivation trees and left-most (or right-most) derivations.
                    </div>
                </div>
            </section>

            <!-- Sample Problem 3.0(c) -->
            <section id="sp-c">
                <h2><span class="sec-icon">🧪</span> Sample Problem 3.0(c) — Ambiguity Test</h2>
                <div class="sample-problem">
                    <div class="sp-header"><span style="font-size:18px">📋</span>
                        <h4>Sample Problem 3.0 (c)</h4>
                    </div>
                    <div class="sp-body">
                        <p class="sp-question">Determine whether the following grammar is ambiguous. If so, show two different derivation trees for the same string, and the left-most derivation for each.</p>
                        <div class="grammar-box" style="margin-bottom:16px;">
                            <div class="g-rule"><span class="g-num">1.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">a</span><span class="g-nt">S</span><span class="g-t">b</span><span class="g-nt">S</span></div>
                            <div class="g-rule"><span class="g-num">2.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">a</span><span class="g-nt">S</span></div>
                            <div class="g-rule"><span class="g-num">3.</span><span class="g-nt">S</span><span style="color:#6C7086;"> → </span><span class="g-t">c</span></div>
                        </div>

                        <div class="sp-label">✅ Result — Grammar IS Ambiguous (two trees for aacbc)</div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:10px;">
                            <div class="tree-panel">
                                <div class="tree-panel-title">Tree 1</div>
                                <svg width="100%" viewBox="0 0 180 170" style="display:block;">
                                    <line x1="90" y1="24" x2="40" y2="64" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                    <line x1="90" y1="24" x2="90" y2="64" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                    <line x1="90" y1="24" x2="140" y2="64" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                    <line x1="90" y1="80" x2="70" y2="120" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                    <line x1="90" y1="80" x2="110" y2="120" stroke="#6366F1" stroke-width="1.5" opacity=".4" />
                                    <circle cx="90" cy="16" r="14" fill="rgba(99,102,241,.15)" stroke="#6366F1" stroke-width="1.8" />
                                    <text x="90" y="21" text-anchor="middle" fill="#818CF8" font-size="12" font-weight="700" font-family="Syne">S</text>
                                    <circle cx="40" cy="72" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                                    <text x="40" y="77" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">a</text>
                                    <circle cx="90" cy="72" r="12" fill="rgba(99,102,241,.12)" stroke="#6366F1" stroke-width="1.6" />
                                    <text x="90" y="77" text-anchor="middle" fill="#818CF8" font-size="12" font-weight="700" font-family="Syne">S</text>
                                    <circle cx="140" cy="72" r="12" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                                    <text x="140" y="77" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">c</text>
                                    <circle cx="70" cy="128" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.2" />
                                    <text x="70" y="133" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">a</text>
                                    <circle cx="110" cy="128" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.2" />
                                    <text x="110" y="133" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">c</text>
                                    <text x="90" y="158" text-anchor="middle" fill="#6C7086" font-size="9" font-family="DM Sans">S → aSbS, inner S → aS → ac</text>
                                </svg>
                            </div>
                            <div class="tree-panel">
                                <div class="tree-panel-title">Tree 2</div>
                                <svg width="100%" viewBox="0 0 180 170" style="display:block;">
                                    <line x1="90" y1="24" x2="60" y2="64" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                                    <line x1="90" y1="24" x2="130" y2="64" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                                    <line x1="60" y1="80" x2="30" y2="120" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                                    <line x1="60" y1="80" x2="65" y2="120" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                                    <line x1="60" y1="80" x2="100" y2="120" stroke="#EF4444" stroke-width="1.5" opacity=".4" />
                                    <circle cx="90" cy="16" r="14" fill="rgba(239,68,68,.1)" stroke="#EF4444" stroke-width="1.8" />
                                    <text x="90" y="21" text-anchor="middle" fill="#F87171" font-size="12" font-weight="700" font-family="Syne">S</text>
                                    <circle cx="60" cy="72" r="12" fill="rgba(239,68,68,.08)" stroke="#EF4444" stroke-width="1.6" />
                                    <text x="60" y="77" text-anchor="middle" fill="#F87171" font-size="12" font-weight="700" font-family="Syne">S</text>
                                    <circle cx="130" cy="72" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.4" />
                                    <text x="130" y="77" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">c</text>
                                    <circle cx="30" cy="128" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.2" />
                                    <text x="30" y="133" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">a</text>
                                    <circle cx="65" cy="128" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.2" />
                                    <text x="65" y="133" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">a</text>
                                    <circle cx="100" cy="128" r="11" fill="rgba(16,185,129,.1)" stroke="#10B981" stroke-width="1.2" />
                                    <text x="100" y="133" text-anchor="middle" fill="#10B981" font-size="11" font-family="JetBrains Mono">c</text>
                                    <text x="90" y="158" text-anchor="middle" fill="#6C7086" font-size="9" font-family="DM Sans">S → aS, inner S → aSbS</text>
                                </svg>
                            </div>
                        </div>
                        <div style="margin-top:14px;">
                            <div class="sp-label">Left-most derivation for Tree 1:</div>
                            <div style="background:var(--code-bg);border-radius:8px;padding:10px 16px;font-family:'JetBrains Mono',monospace;font-size:12px;color:#CDD6F4;line-height:2;">
                                S ⇒ aSbS ⇒ aaSbS ⇒ aacbS ⇒ aacbc
                            </div>
                            <div class="sp-label">Left-most derivation for Tree 2:</div>
                            <div style="background:var(--code-bg);border-radius:8px;padding:10px 16px;font-family:'JetBrains Mono',monospace;font-size:12px;color:#CDD6F4;line-height:2;">
                                S ⇒ aS ⇒ aaSbS ⇒ aacbS ⇒ aacbc
                            </div>
                        </div>
                        <div class="note-box key" style="margin-top:12px;">
                            <span class="box-icon">✅</span>
                            <div>Both derivations produce <strong>aacbc</strong> via different trees → grammar is <strong>ambiguous</strong>.</div>
                        </div>
                    </div>
                </div>
            </section>













            <div class="chapter-nav">
                <a href="c3_0_2.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>3.0.2 Classes of Grammars</div>
                </a>
                <a href="?complete=3" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>3.0.4 Pushdown Machines</div>
                    <span>→</span>
                </a>
            </div>

        </article>
    </div>

    <script>
        // TOC scroll
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
        // PDM SIMULATOR
        // ════════════════════════════════════════════════════════
        // Machines: fig34 = anbn, fig36 = balanced parens
        const MACHINES = {
            anbn: {
                // State S1: push X on a; switch to S2 on b; accept on N with just ,
                // State S2: pop on b; accept on N with just ,
                simulate: function(str) {
                    const input = str.split('').concat(['N']);
                    let stack = [','];
                    let state = 'S1';
                    let i = 0;
                    const trace = [{
                        stack: [...stack],
                        state,
                        input_sym: '(start)'
                    }];
                    let result = null;
                    while (i < input.length && result === null) {
                        const sym = input[i];
                        const top = stack[stack.length - 1];
                        if (state === 'S1') {
                            if (sym === 'a') {
                                stack.push('X');
                                i++;
                            } else if (sym === 'b') {
                                if (top === 'X') {
                                    stack.pop();
                                    i++;
                                    state = 'S2';
                                } else {
                                    result = 'reject';
                                    break;
                                }
                            } else if (sym === 'N') {
                                result = (top === ',') ? 'accept' : 'reject';
                                break;
                            } else {
                                result = 'reject';
                                break;
                            }
                        } else {
                            if (sym === 'b') {
                                if (top === 'X') {
                                    stack.pop();
                                    i++;
                                } else {
                                    result = 'reject';
                                    break;
                                }
                            } else if (sym === 'N') {
                                result = (top === ',') ? 'accept' : 'reject';
                                break;
                            } else {
                                result = 'reject';
                                break;
                            }
                        }
                        trace.push({
                            stack: [...stack],
                            state,
                            input_sym: sym
                        });
                    }
                    if (!result) result = 'reject';
                    return {
                        trace,
                        result
                    };
                }
            },
            paren: {
                simulate: function(str) {
                    const input = str.split('').concat(['N']);
                    let stack = [','];
                    let state = 'S1';
                    let i = 0;
                    const trace = [{
                        stack: [...stack],
                        state,
                        input_sym: '(start)'
                    }];
                    let result = null;
                    while (i < input.length && result === null) {
                        const sym = input[i];
                        const top = stack[stack.length - 1];
                        if (sym === '(') {
                            stack.push('X');
                            i++;
                        } else if (sym === ')') {
                            if (top === 'X') {
                                stack.pop();
                                i++;
                            } else {
                                result = 'reject';
                                break;
                            }
                        } else if (sym === 'N') {
                            result = (top === ',') ? 'accept' : 'reject';
                            break;
                        } else {
                            result = 'reject';
                            break;
                        }
                        trace.push({
                            stack: [...stack],
                            state,
                            input_sym: sym
                        });
                    }
                    if (!result) result = 'reject';
                    return {
                        trace,
                        result
                    };
                }
            }
        };

        let pdmTrace = null,
            pdmPos = 0,
            pdmStr = '';

        function pdmEx(machine, s) {
            document.getElementById('pdm-machine-sel').value = machine;
            document.getElementById('pdm-input').value = s;
            pdmReset();
        }

        function pdmReset() {
            pdmStr = document.getElementById('pdm-input').value.trim();
            pdmPos = 0;
            pdmTrace = null;
            renderPdmTape([]);
            renderPdmStack([',']);
            document.getElementById('pdm-cur-state').textContent = '—';
            document.getElementById('pdm-log').textContent = 'Press Step or Run to simulate.';
            document.getElementById('pdm-step-btn').disabled = false;
            const res = document.getElementById('pdm-result');
            res.className = 'pdm-status';
            res.textContent = '';
            document.getElementById('pdm-status-label').textContent = 'Ready';
        }

        function pdmEnsureTrace() {
            if (!pdmTrace) {
                const machine = document.getElementById('pdm-machine-sel').value;
                pdmStr = document.getElementById('pdm-input').value.trim();
                const m = machine === 'anbn' ? MACHINES.anbn : MACHINES.paren;
                pdmTrace = m.simulate(pdmStr);
                pdmPos = 0;
            }
        }

        function pdmStep() {
            pdmEnsureTrace();
            if (pdmPos >= pdmTrace.trace.length) {
                finishPdm();
                return;
            }
            const step = pdmTrace.trace[pdmPos];
            renderPdmTape(pdmStr.split(''), pdmPos - 1);
            renderPdmStack(step.stack);
            document.getElementById('pdm-cur-state').textContent = step.state;
            document.getElementById('pdm-log').textContent = `Step ${pdmPos}: read "${step.input_sym}" · stack top=${step.stack[step.stack.length-1]}`;
            pdmPos++;
            document.getElementById('pdm-status-label').textContent = `Step ${pdmPos}/${pdmTrace.trace.length}`;
            if (pdmPos >= pdmTrace.trace.length) finishPdm();
        }

        function pdmRun() {
            pdmEnsureTrace();
            pdmPos = pdmTrace.trace.length;
            renderPdmTape(pdmStr.split(''), pdmStr.length);
            const last = pdmTrace.trace[pdmTrace.trace.length - 1];
            renderPdmStack(last.stack);
            document.getElementById('pdm-cur-state').textContent = last.state;
            finishPdm();
        }

        function finishPdm() {
            document.getElementById('pdm-step-btn').disabled = true;
            const accepted = pdmTrace.result === 'accept';
            const res = document.getElementById('pdm-result');
            res.className = 'pdm-status show ' + (accepted ? 'accepted' : 'rejected');
            res.innerHTML = accepted ?
                `✅ <strong>ACCEPTED</strong> — "${pdmStr}" is in the language.` :
                `❌ <strong>REJECTED</strong> — "${pdmStr}" is not in the language.`;
            document.getElementById('pdm-status-label').textContent = accepted ? 'ACCEPTED ✅' : 'REJECTED ❌';
        }

        function renderPdmTape(chars, readUpTo) {
            const tape = document.getElementById('pdm-tape');
            tape.innerHTML = chars.map((c, i) => {
                let cls = 'pdm-char';
                if (i < readUpTo) cls += ' read';
                else if (i === readUpTo) cls += ' current';
                return `<div class="${cls}">${c}</div>`;
            }).join('') || '<span style="font-size:13px;color:var(--muted);">No input</span>';
        }

        function renderPdmStack(stack) {
            const vis = document.getElementById('pdm-stack');
            vis.innerHTML = '';
            [...stack].reverse().forEach((s, i) => {
                const el = document.createElement('div');
                el.className = 'pdm-stack-item';
                if (s === 'X') {
                    el.style.background = '#EEF2FF';
                    el.style.color = '#6366F1';
                } else if (s === ',') {
                    el.style.background = '#F1F5F9';
                    el.style.color = '#475569';
                } else {
                    el.style.background = '#FEF3C7';
                    el.style.color = '#92400E';
                }
                el.textContent = s;
                vis.appendChild(el);
            });
        }

        // init
        pdmReset();

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
    </script>

</body>

</html>