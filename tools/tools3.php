<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CompileX – Chapter 4: Top Down Parsing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #F8F7FF;
            --purple: #6366F1;
            --purple-d: #4F46E5;
            --purple-s: #EEF2FF;
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
            font-family: 'Syne', sans-serif;
            background: var(--bg);
        }

        .tool-wrap {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin: 20px 0;
        }

        .tool-header {
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .tool-header h4 {
            font-weight: 700;
            font-size: 15px;
            color: white;
            margin: 0;
        }

        .tool-body {
            padding: 22px;
        }

        .tool-input {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            padding: 9px 13px;
            border: 1.5px solid rgba(99, 102, 241, .25);
            border-radius: 10px;
            outline: none;
            color: var(--text);
            background: var(--bg);
            width: 100%;
        }

        .tool-input:focus {
            border-color: var(--purple);
        }

        .tool-textarea {
            width: 100%;
            min-height: 80px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            padding: 12px 16px;
            border: 1.5px solid rgba(99, 102, 241, .25);
            border-radius: 10px;
            outline: none;
            resize: vertical;
            color: var(--text);
            background: var(--bg);
            line-height: 1.7;
        }

        .tool-textarea:focus {
            border-color: var(--purple);
        }

        .tool-btn {
            margin-top: 10px;
            padding: 9px 20px;
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

        .tool-btn:hover {
            background: var(--purple-d);
        }

        .tool-btn.sec {
            background: transparent;
            border: 1.5px solid rgba(99, 102, 241, .3);
            color: var(--purple);
        }

        .tool-btn.sec:hover {
            background: var(--purple-s);
        }

        .tool-log {
            margin-top: 12px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: #CDD6F4;
            background: var(--code-bg);
            padding: 10px 14px;
            border-radius: 8px;
            min-height: 28px;
            white-space: pre-wrap;
            line-height: 1.8;
        }

        .preset-btn {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 6px;
            border: 1px solid rgba(99, 102, 241, .3);
            background: transparent;
            color: var(--purple);
            cursor: pointer;
            font-family: 'Syne', sans-serif;
        }

        .preset-btn:hover {
            background: var(--purple-s);
        }

        .step-block {
            background: #F8F7FF;
            border: 1px solid rgba(99, 102, 241, .18);
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 10px;
        }

        .step-num {
            display: inline-block;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--purple);
            color: white;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            line-height: 26px;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .step-title {
            font-weight: 700;
            font-size: 13px;
            color: var(--text);
        }

        .step-result {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: #4F46E5;
            background: #EEF2FF;
            padding: 8px 12px;
            border-radius: 8px;
            margin-top: 8px;
            white-space: pre-wrap;
            line-height: 1.9;
        }

        .step-result.empty {
            color: #9CA3AF;
            font-style: italic;
        }

        .badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            border: 1.5px solid;
        }

        .badge-ok {
            background: #F0FDF4;
            border-color: #10B981;
            color: #065F46;
        }

        .badge-fail {
            background: #FFF1F2;
            border-color: #EF4444;
            color: #9F1239;
        }

        .atom-row {
            display: flex;
            gap: 6px;
            align-items: center;
            padding: 7px 12px;
            border-radius: 8px;
            background: #F8F7FF;
            border: 1px solid rgba(99, 102, 241, .15);
            margin: 4px 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
        }

        .atom-tag {
            padding: 2px 10px;
            border-radius: 5px;
            font-weight: 700;
            font-size: 11px;
            color: white;
        }

        .atom-ADD {
            background: #6366F1;
        }

        .atom-SUB {
            background: #8B5CF6;
        }

        .atom-MUL {
            background: #10B981;
        }

        .atom-DIV {
            background: #F59E0B;
        }

        .atom-MOV {
            background: #3B82F6;
        }

        .atom-TST {
            background: #EF4444;
        }

        .atom-JMP {
            background: #F97316;
        }

        .atom-LBL {
            background: #14B8A6;
            color: #fff;
        }

        .atom-NEG {
            background: #EC4899;
        }

        select {
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            padding: 8px 12px;
            border: 1.5px solid rgba(99, 102, 241, .25);
            border-radius: 8px;
            background: var(--bg);
            color: var(--text);
            outline: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: var(--code-bg);
            color: #A5B4FC;
            font-family: 'Syne', sans-serif;
            font-size: 11px;
            padding: 8px 12px;
            text-align: left;
        }

        td {
            padding: 7px 12px;
            font-size: 12px;
            font-family: 'JetBrains Mono', monospace;
            border-bottom: 1px solid #EEF2FF;
        }

        .tree-node {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 8px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            font-weight: 700;
            border: 2px solid;
        }

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .55);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-box {
            background: #F8F7FF;
            border-radius: 16px;
            width: 100%;
            max-width: 900px;
            max-height: 92vh;
            overflow-y: auto;
        }

        .modal-hdr {
            background: #18181B;
            padding: 16px 24px;
            border-radius: 16px 16px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-hdr span {
            font-weight: 700;
            font-size: 17px;
            color: #fff;
        }

        .modal-hdr p {
            font-size: 12px;
            color: #A1A1AA;
            margin: 4px 0 0;
        }

        .close-btn {
            background: transparent;
            border: 1px solid #3F3F46;
            color: #A1A1AA;
            border-radius: 8px;
            padding: 6px 14px;
            cursor: pointer;
            font-size: 13px;
            font-family: 'Syne', sans-serif;
        }

        .modal-body {
            padding: 28px;
        }

        .info-box {
            background: var(--code-bg);
            border-radius: 10px;
            padding: 16px 20px;
            margin-top: 10px;
        }

        .info-box .hdr {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 12px;
            color: #A5B4FC;
            margin-bottom: 10px;
            letter-spacing: .05em;
        }

        .info-box pre {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: #CDD6F4;
            line-height: 1.9;
            white-space: pre-wrap;
        }

        .card-btn {
            display: flex;
            align-items: center;
            gap: 16px;
            background: #F8F7FF;
            border: 1px solid rgba(99, 102, 241, .2);
            border-radius: 12px;
            padding: 16px 20px;
            cursor: pointer;
            text-align: left;
            width: 100%;
            transition: all .2s;
        }

        .card-btn:hover {
            border-color: #6366F1;
            background: #EEF2FF;
        }

        .card-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .deriv-step {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 14px;
            border-radius: 10px;
            background: var(--purple-s);
            border: 1px solid rgba(99, 102, 241, .2);
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: var(--text);
            margin: 4px 0;
        }

        .deriv-step .ds-num {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--purple);
            color: white;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        label.lbl {
            font-size: 12px;
            font-weight: 700;
            color: var(--muted);
            display: block;
            margin-bottom: 4px;
        }
    </style>
</head>

<body>

    <!-- ══ Chapter 4 Hub Card ══════════════════════════════════════════ -->
    <div style="max-width:700px;margin:40px auto;padding:0 20px;">
        <div style="background:#FFFFFF;border-radius:16px;box-shadow:0 4px 24px rgba(99,102,241,.10);border:1px solid rgba(99,102,241,.15);overflow:hidden;">

            <div style="background:#18181B;padding:20px 28px;">
                <h2 style="font-size:22px;font-weight:700;color:#FFFFFF;margin:0 0 6px;">CHAPTER 4</h2>
                <p style="font-size:12px;font-weight:600;letter-spacing:.1em;color:#A5B4FC;margin:0 0 6px;">TOP DOWN PARSING</p>
            </div>

            <div style="padding:24px 28px;display:flex;flex-direction:column;gap:14px;">

                <button class="card-btn" onclick="document.getElementById('m12step').style.display='flex'">
                    <div class="card-icon" style="background:#EEF2FF;">🧮</div>
                    <div style="flex:1;">
                        <div style="font-weight:700;font-size:15px;color:#1E1B4B;">12-Step Selection Set Calculator</div>
                        <div style="font-size:13px;color:#6B7280;margin-top:2px;">Walk through all 12 steps to find selection sets for any CF grammar — Section 4.3</div>
                    </div>
                    <div style="font-size:18px;color:#6366F1;">▶</div>
                </button>

                <button class="card-btn" onclick="document.getElementById('mLL1').style.display='flex'">
                    <div class="card-icon" style="background:#ECFDF5;">🌀</div>
                    <div style="flex:1;">
                        <div style="font-weight:700;font-size:15px;color:#1E1B4B;">LL(1) Arithmetic Expression Parser</div>
                        <div style="font-size:13px;color:#6B7280;margin-top:2px;">Parse var/const expressions with grammar G16, generate atoms — Section 4.4</div>
                    </div>
                    <div style="font-size:18px;color:#6366F1;">▶</div>
                </button>

                <button class="card-btn" onclick="document.getElementById('mDecaf').style.display='flex'">
                    <div class="card-icon" style="background:#FEF3C7;">🌿</div>
                    <div style="flex:1;">
                        <div style="font-weight:700;font-size:15px;color:#1E1B4B;">Decaf Expression Derivation Tree</div>
                        <div style="font-size:13px;color:#6B7280;margin-top:2px;">Visualise attributed derivation trees for Decaf expressions including assignment &amp; comparisons — Section 4.8</div>
                    </div>
                    <div style="font-size:18px;color:#6366F1;">▶</div>
                </button>

                <button class="card-btn" onclick="document.getElementById('mCtrl').style.display='flex'">
                    <div class="card-icon" style="background:#FFF1F2;">⚡</div>
                    <div style="flex:1;">
                        <div style="font-weight:700;font-size:15px;color:#1E1B4B;">Control Structure Atom Generator</div>
                        <div style="font-size:13px;color:#6B7280;margin-top:2px;">Generate LBL / JMP / TST atom sequences for while, for, if-else — Section 4.9</div>
                    </div>
                    <div style="font-size:18px;color:#6366F1;">▶</div>
                </button>

            </div>

            <div style="border-top:1px solid rgba(99,102,241,.1);padding:14px 28px;background:#F8F7FF;">
                <span style="font-size:12px;color:#9CA3AF;">🔗 All tools are based on examples from your compiler principles textbook — Chapter 4.</span>
            </div>
        </div>
    </div>


    <!-- ══════════════════════════════════════════════════════════════
  MODAL 1 — 12-Step Selection Set Calculator
══════════════════════════════════════════════════════════════ -->
    <div id="m12step" class="modal" onclick="if(event.target===this)this.style.display='none'">
        <div class="modal-box">
            <div class="modal-hdr">
                <div><span>🧮 12-Step Selection Set Calculator</span>
                    <p>Section 4.3 — LL(1) Grammar Analysis</p>
                </div>
                <button class="close-btn" onclick="document.getElementById('m12step').style.display='none'">✕ Close</button>
            </div>
            <div class="modal-body">
                <p style="font-size:14px;color:#6B7280;margin-bottom:20px;line-height:1.7;">
                    Enter grammar rules (one per line, use <code>→</code> or <code>-&gt;</code>). Use <code>ε</code> or empty RHS for epsilon rules. Nonterminals start with an uppercase letter. Click <strong>Run All 12 Steps</strong> to see each step's result.
                </p>

                <div class="tool-wrap">
                    <div class="tool-header" style="background:linear-gradient(135deg,#6366F1,#818CF8);">
                        <h4>🧮 Selection Set Engine</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);">Steps 1 – 12</span>
                    </div>
                    <div class="tool-body">
                        <div style="margin-bottom:12px;">
                            <span style="font-size:12px;color:var(--muted);margin-right:6px;">Presets:</span>
                            <div style="display:inline-flex;gap:6px;flex-wrap:wrap;">
                                <button class="preset-btn" onclick="ss_load('g15')">G15 (Textbook)</button>
                                <button class="preset-btn" onclick="ss_load('g16')">G16 Arithmetic</button>
                                <button class="preset-btn" onclick="ss_load('g14')">G14 Quasi-Simple</button>
                            </div>
                        </div>
                        <label class="lbl">Grammar Rules:</label>
                        <textarea class="tool-textarea" id="ss-rules" rows="6" spellcheck="false">S → ABc
A → bA
A → ε
B → c</textarea>
                        <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap;">
                            <button class="tool-btn" onclick="ss_run()">▶ Run All 12 Steps</button>
                            <button class="tool-btn sec" onclick="ss_clear()">↺ Clear</button>
                        </div>
                        <div id="ss-out" style="margin-top:16px;display:none;"></div>
                    </div>
                </div>

                <div class="info-box">
                    <div class="hdr">ALGORITHM OVERVIEW — 12 STEPS (Section 4.3)</div>
                    <pre>1. Nullable rules & nonterminals    7. "Is Direct End Of" (DEO)
2. "Begins Directly With" (BDW)     8. "Is End Of" (EO) = closure of DEO
3. "Begins With" (BW) = closure     9. "Is Followed By" (FB)
4. First(x) for each symbol        10. Extend FB with endmarker N
5. First of each rule's RHS        11. Follow sets for nullable NTs
6. "Followed Directly By" (FDB)    12. Selection Set for each rule</pre>
                </div>
            </div>
        </div>
    </div>


    <!-- ══════════════════════════════════════════════════════════════
  MODAL 2 — LL(1) Arithmetic Expression Parser (G16)
══════════════════════════════════════════════════════════════ -->
    <div id="mLL1" class="modal" onclick="if(event.target===this)this.style.display='none'">
        <div class="modal-box">
            <div class="modal-hdr">
                <div><span>🌀 LL(1) Arithmetic Expression Parser</span>
                    <p>Grammar G16 — Section 4.4</p>
                </div>
                <button class="close-btn" onclick="document.getElementById('mLL1').style.display='none'">✕ Close</button>
            </div>
            <div class="modal-body">
                <p style="font-size:14px;color:#6B7280;margin-bottom:20px;line-height:1.7;">
                    Enter an arithmetic expression using <code>var</code>, <code>const</code>, <code>+</code>, <code>-</code>, <code>*</code>, <code>/</code>, and parentheses. The tool parses using grammar G16, shows the derivation steps, and outputs atoms.
                </p>

                <div class="tool-wrap">
                    <div class="tool-header" style="background:linear-gradient(135deg,#059669,#34D399);">
                        <h4>🌀 G16 Parser + Atom Generator</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);">Left-recursive free grammar</span>
                    </div>
                    <div class="tool-body">
                        <div style="margin-bottom:10px;">
                            <span style="font-size:12px;color:var(--muted);margin-right:6px;">Presets:</span>
                            <div style="display:inline-flex;gap:6px;flex-wrap:wrap;">
                                <button class="preset-btn" onclick="ll1_load('var + var * var')">var + var * var</button>
                                <button class="preset-btn" onclick="ll1_load('(var + var) * const')">(var + var) * const</button>
                                <button class="preset-btn" onclick="ll1_load('var * var + var * const')">var * var + var * const</button>
                                <button class="preset-btn" onclick="ll1_load('var + var + var')">var + var + var</button>
                            </div>
                        </div>
                        <div style="display:flex;gap:10px;margin-top:8px;">
                            <input class="tool-input" id="ll1-input" value="var + var * var" style="flex:1;">
                            <button class="tool-btn" style="margin-top:0;" onclick="ll1_run()">▶ Parse</button>
                        </div>
                        <div id="ll1-out" style="display:none;margin-top:18px;">
                            <div style="font-weight:700;font-size:13px;color:var(--text);margin-bottom:10px;">Recursive Descent Trace:</div>
                            <div id="ll1-trace" style="font-family:'JetBrains Mono',monospace;font-size:12px;background:var(--code-bg);color:#CDD6F4;padding:14px;border-radius:10px;line-height:2;white-space:pre-wrap;max-height:220px;overflow-y:auto;"></div>
                            <div style="font-weight:700;font-size:13px;color:var(--text);margin:16px 0 8px;">Output Atoms:</div>
                            <div id="ll1-atoms"></div>
                            <div id="ll1-verdict" style="margin-top:10px;"></div>
                        </div>
                    </div>
                </div>

                <div class="info-box">
                    <div class="hdr">GRAMMAR G16 — LL(1) ARITHMETIC (Section 4.4)</div>
                    <pre>1. Expr   → Term Elist         Sel: {(, var, const}
2. Elist  → + Term Elist       Sel: {+}
3. Elist  → ε                  Sel: {), N}
4. Term   → Factor Tlist       Sel: {(, var, const}
5. Tlist  → * Factor Tlist     Sel: {*}
6. Tlist  → ε                  Sel: {+, ), N}
7. Factor → ( Expr )           Sel: {(}
8. Factor → var                Sel: {var}
9. Factor → const              Sel: {const}</pre>
                </div>
            </div>
        </div>
    </div>


    <!-- ══════════════════════════════════════════════════════════════
  MODAL 3 — Decaf Expression Derivation Tree (Section 4.8)
══════════════════════════════════════════════════════════════ -->
    <div id="mDecaf" class="modal" onclick="if(event.target===this)this.style.display='none'">
        <div class="modal-box">
            <div class="modal-hdr">
                <div><span>🌿 Decaf Expression Derivation Tree</span>
                    <p>Section 4.8 — Attributed Grammar</p>
                </div>
                <button class="close-btn" onclick="document.getElementById('mDecaf').style.display='none'">✕ Close</button>
            </div>
            <div class="modal-body">
                <p style="font-size:14px;color:#6B7280;margin-bottom:20px;line-height:1.7;">
                    Choose expression type and enter a Decaf-style expression. The tool shows the attributed derivation tree and the atoms produced. Use identifiers like <code>a</code>,<code>b</code>,<code>c</code>, numbers, operators <code>+ - * /</code>, assignment <code>=</code>, comparisons <code>== != &lt; &gt; &lt;= &gt;=</code>, and parentheses.
                </p>

                <div class="tool-wrap">
                    <div class="tool-header" style="background:linear-gradient(135deg,#F59E0B,#FBBF24);">
                        <h4>🌿 Decaf Attributed Tree Builder</h4>
                        <span style="font-size:12px;color:rgba(0,0,0,.5);">Section 4.8 Grammar</span>
                    </div>
                    <div class="tool-body">
                        <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;margin-bottom:12px;">
                            <div>
                                <label class="lbl">Starting symbol:</label>
                                <select id="decaf-start">
                                    <option value="expr">Expr (arithmetic / assignment)</option>
                                    <option value="boolexpr">BoolExpr (comparison)</option>
                                </select>
                            </div>
                        </div>
                        <div style="margin-bottom:10px;">
                            <span style="font-size:12px;color:var(--muted);margin-right:6px;">Presets:</span>
                            <div style="display:inline-flex;gap:6px;flex-wrap:wrap;">
                                <button class="preset-btn" onclick="decaf_load('expr','a + b')">a + b</button>
                                <button class="preset-btn" onclick="decaf_load('expr','a = b + c')">a = b + c</button>
                                <button class="preset-btn" onclick="decaf_load('boolexpr','a == b + c')">a == b+c</button>
                                <button class="preset-btn" onclick="decaf_load('boolexpr','a == (b = 3) + c')">a==(b=3)+c (Fig 4.8)</button>
                            </div>
                        </div>
                        <div style="display:flex;gap:10px;">
                            <input class="tool-input" id="decaf-input" value="a + b" style="flex:1;">
                            <button class="tool-btn" style="margin-top:0;" onclick="decaf_run()">🌿 Build Tree</button>
                        </div>
                        <div id="decaf-out" style="margin-top:18px;display:none;">
                            <div style="font-weight:700;font-size:13px;color:var(--text);margin-bottom:8px;">Attributed Tree (simplified):</div>
                            <div style="overflow-x:auto;background:white;border-radius:10px;border:1px solid rgba(99,102,241,.15);padding:16px;">
                                <svg id="decaf-svg" height="280" style="display:block;width:100%;"></svg>
                            </div>
                            <div style="font-weight:700;font-size:13px;color:var(--text);margin:14px 0 8px;">Atoms produced:</div>
                            <div id="decaf-atoms"></div>
                            <div id="decaf-log" class="tool-log" style="margin-top:10px;"></div>
                        </div>
                    </div>
                </div>

                <div class="info-box">
                    <div class="hdr">DECAF EXPRESSION GRAMMAR HIGHLIGHTS (Section 4.8)</div>
                    <pre>BoolExpr_L → Expr_p compare_c Expr_q  {TST}_p,q,,7-c,L
Expr_p     → AssignExpr_p  |  Rvalue_p
AssignExpr → ident_p = Expr_q  {MOV}_q,,p
Rvalue_p   → Term_q Elist_q,p
Elist      → + Term_r {ADD} Elist  |  - Term_r {SUB} Elist  |  ε
Term_p     → Factor_q Tlist_q,p
Tlist      → * Factor_r {MUL} Tlist  |  / Factor_r {DIV} Tlist  |  ε
Factor_p   → ( Expr_p )  |  ident_p  |  num_p  |  - Factor_q {NEG}</pre>
                </div>
            </div>
        </div>
    </div>


    <!-- ══════════════════════════════════════════════════════════════
  MODAL 4 — Control Structure Atom Generator (Section 4.9)
══════════════════════════════════════════════════════════════ -->
    <div id="mCtrl" class="modal" onclick="if(event.target===this)this.style.display='none'">
        <div class="modal-box">
            <div class="modal-hdr">
                <div><span>⚡ Control Structure Atom Generator</span>
                    <p>Section 4.9 — while / for / if-else</p>
                </div>
                <button class="close-btn" onclick="document.getElementById('mCtrl').style.display='none'">✕ Close</button>
            </div>
            <div class="modal-body">
                <p style="font-size:14px;color:#6B7280;margin-bottom:20px;line-height:1.7;">
                    Choose a control structure, fill in the fields, and see the exact atom stream (LBL, JMP, TST) that the Decaf compiler would produce — matching Figure 4.18.
                </p>

                <div class="tool-wrap">
                    <div class="tool-header" style="background:linear-gradient(135deg,#EF4444,#F87171);">
                        <h4>⚡ Atom Stream Generator</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.8);">Fig 4.18 — Section 4.9</span>
                    </div>
                    <div class="tool-body">
                        <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;margin-bottom:16px;">
                            <div>
                                <label class="lbl">Control structure:</label>
                                <select id="ctrl-type" onchange="ctrl_changed()">
                                    <option value="while">while (BoolExpr) Stmt</option>
                                    <option value="for">for (Expr; BoolExpr; Expr) Stmt</option>
                                    <option value="if">if (BoolExpr) Stmt</option>
                                    <option value="ifelse">if (BoolExpr) Stmt else Stmt</option>
                                </select>
                            </div>
                        </div>

                        <div id="ctrl-fields">
                            <!-- populated by ctrl_changed() -->
                        </div>

                        <div style="display:flex;gap:8px;margin-top:12px;">
                            <button class="tool-btn" onclick="ctrl_run()">⚡ Generate Atoms</button>
                            <button class="tool-btn sec" onclick="ctrl_example()">📋 Load Example</button>
                        </div>

                        <div id="ctrl-out" style="margin-top:18px;display:none;">
                            <div style="font-weight:700;font-size:13px;color:var(--text);margin-bottom:10px;">Atom stream (top = first emitted):</div>
                            <div id="ctrl-atoms"></div>
                            <div class="info-box" style="margin-top:12px;">
                                <div class="hdr">LEGEND</div>
                                <pre style="font-size:11px;">LBL  label         — marks a branch destination
JMP  label         — unconditional jump
TST  E1 E2 cmp lbl — branch to lbl if E1 cmp E2 is true (false ↓)
MOV  src dst       — assignment</pre>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="info-box" style="margin-top:8px;">
                    <div class="hdr">COMPARISON CODES (Section 4.8)</div>
                    <pre>== → 1   !=  → 6   |  complement = 7 - code
 &lt; → 2   &gt;=  → 5   |  TST branches when comparison is TRUE
 &gt; → 3   &lt;=  → 4   |  (so comparison is INVERTED from condition)</pre>
                </div>
            </div>
        </div>
    </div>


    <!-- ═══════════════════════════════════════════════════════════════
  JAVASCRIPT
═══════════════════════════════════════════════════════════════ -->
    <script>
        // ─────────────────────────────────────────────────────────────────
        // HELPERS
        // ─────────────────────────────────────────────────────────────────
        function escH(s) {
            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        // ─────────────────────────────────────────────────────────────────
        // TOOL 1 — 12-Step Selection Set Calculator
        // ─────────────────────────────────────────────────────────────────
        const SS_PRESETS = {
            g15: 'S → ABc\nA → bA\nA → ε\nB → c',
            g16: 'Expr → Term Elist\nElist → + Term Elist\nElist → ε\nTerm → Factor Tlist\nTlist → * Factor Tlist\nTlist → ε\nFactor → ( Expr )\nFactor → var\nFactor → const',
            g14: 'S → aAS\nS → b\nA → cAS\nA → ε'
        };

        function ss_load(k) {
            document.getElementById('ss-rules').value = SS_PRESETS[k];
            ss_clear();
        }

        function ss_clear() {
            document.getElementById('ss-out').style.display = 'none';
            document.getElementById('ss-out').innerHTML = '';
        }

        function ss_parseRules(text) {
            return text.trim().split('\n').map(l => {
                const sep = l.includes('→') ? '→' : '->';
                if (!l.includes(sep)) return null;
                const i = l.indexOf(sep);
                const lhs = l.slice(0, i).trim();
                const rhs = l.slice(i + sep.length).trim();
                return {
                    lhs,
                    rhs: (rhs === 'ε' || rhs === '') ? '' : rhs
                };
            }).filter(Boolean);
        }

        function ss_tokenize(s, NTs) {
            const sorted = [...NTs].sort((a, b) => b.length - a.length);
            const toks = [];
            let i = 0;
            while (i < s.length) {
                if (s[i] === ' ') {
                    i++;
                    continue;
                }
                let hit = false;
                for (const nt of sorted) {
                    if (s.startsWith(nt, i)) {
                        const after = i + nt.length < s.length ? s[i + nt.length] : '';
                        if (after === '' || after === ' ' || '+-*/()'.includes(after)) {
                            toks.push(nt);
                            i += nt.length;
                            hit = true;
                            break;
                        }
                    }
                }
                if (hit) continue;
                if ('+-*/()'.includes(s[i])) {
                    toks.push(s[i]);
                    i++;
                    continue;
                }
                let j = i;
                while (j < s.length && s[j] !== ' ' && !'+-*/()'.includes(s[j])) j++;
                if (j > i) {
                    toks.push(s.slice(i, j));
                    i = j;
                } else {
                    toks.push(s[i]);
                    i++;
                }
            }
            return toks;
        }

        function ss_run() {
            const rules = ss_parseRules(document.getElementById('ss-rules').value);
            if (!rules.length) {
                alert('Enter grammar rules first.');
                return;
            }

            const NTs = [...new Set(rules.map(r => r.lhs))];
            const Ts_raw = new Set();
            rules.forEach(r => {
                if (r.rhs === '') return;
                ss_tokenize(r.rhs, NTs).forEach(t => {
                    if (!NTs.includes(t)) Ts_raw.add(t);
                });
            });
            const Ts = [...Ts_raw];

            const isNT = x => NTs.includes(x);

            // Step 1: Nullable
            const nullableRules = new Set(),
                nullableNTs = new Set();
            let changed = true;
            while (changed) {
                changed = false;
                rules.forEach((r, i) => {
                    if (nullableRules.has(i)) return;
                    const toks = r.rhs === '' ? [] : ss_tokenize(r.rhs, NTs);
                    const canNull = toks.length === 0 || toks.every(t => nullableNTs.has(t));
                    if (canNull) {
                        nullableRules.add(i);
                        if (!nullableNTs.has(r.lhs)) {
                            nullableNTs.add(r.lhs);
                            changed = true;
                        }
                    }
                });
            }

            // Step 2: BDW
            const BDW = new Set();
            rules.forEach(r => {
                if (r.rhs === '') return;
                const toks = ss_tokenize(r.rhs, NTs);
                for (const t of toks) {
                    BDW.add(r.lhs + '→' + t);
                    if (!nullableNTs.has(t)) break;
                }
            });

            // Step 3: BW = closure of BDW
            const BW = new Set([...BDW]);
            // reflexive
            [...NTs, ...Ts].forEach(x => BW.add(x + '→' + x));
            // transitive
            let again = true;
            while (again) {
                again = false;
                const arr = [...BW];
                arr.forEach(p1 => {
                    const [a, b] = p1.split('→');
                    arr.forEach(p2 => {
                        const [c, d] = p2.split('→');
                        if (b === c && !BW.has(a + '→' + d)) {
                            BW.add(a + '→' + d);
                            again = true;
                        }
                    });
                });
            }

            // Step 4: First(x)
            const First = {};
            [...NTs, ...Ts].forEach(x => {
                First[x] = new Set();
            });
            BW.forEach(p => {
                const [a, b] = p.split('→');
                if (Ts.includes(b)) First[a].add(b);
            });
            Ts.forEach(t => First[t] = new Set([t]));

            // Step 5: First of RHS of each rule
            function firstOfSeq(toks) {
                const s = new Set();
                for (const t of toks) {
                    (First[t] || new Set()).forEach(x => s.add(x));
                    if (!nullableNTs.has(t)) break;
                }
                return s;
            }
            const RuleFirst = rules.map(r => r.rhs === '' ? new Set() : firstOfSeq(ss_tokenize(r.rhs, NTs)));

            // Step 6: FDB
            const FDB = new Set();
            rules.forEach(r => {
                if (r.rhs === '') return;
                const toks = ss_tokenize(r.rhs, NTs);
                for (let i = 0; i < toks.length; i++) {
                    if (!isNT(toks[i])) continue;
                    // check j=i+1..
                    let j = i + 1;
                    while (j < toks.length) {
                        FDB.add(toks[i] + '→' + toks[j]);
                        if (!nullableNTs.has(toks[j])) break;
                        j++;
                    }
                }
            });

            // Step 7: DEO
            const DEO = new Set();
            rules.forEach(r => {
                if (r.rhs === '') return;
                const toks = ss_tokenize(r.rhs, NTs);
                for (let i = toks.length - 1; i >= 0; i--) {
                    DEO.add(toks[i] + '→' + r.lhs);
                    if (!nullableNTs.has(toks[i])) break;
                }
            });

            // Step 8: EO = closure of DEO + reflexive nullable
            const EO = new Set([...DEO]);
            [...NTs, ...Ts].forEach(x => EO.add(x + '→' + x));
            nullableNTs.forEach(n => EO.add('N→' + n)); // not needed but keeps structure
            let ag2 = true;
            while (ag2) {
                ag2 = false;
                const arr = [...EO];
                arr.forEach(p1 => {
                    const [a, b] = p1.split('→');
                    arr.forEach(p2 => {
                        const [c, d] = p2.split('→');
                        if (b === c && !EO.has(a + '→' + d)) {
                            EO.add(a + '→' + d);
                            ag2 = true;
                        }
                    });
                });
            }

            // Step 9: FB
            const FB = new Set();
            const EOarr = [...EO],
                FDBa = [...FDB],
                BWa = [...BW];
            EOarr.forEach(p1 => {
                const [w, x] = p1.split('→');
                FDBa.forEach(p2 => {
                    const [x2, y] = p2.split('→');
                    if (x !== x2) return;
                    BWa.forEach(p3 => {
                        const [y2, z] = p3.split('→');
                        if (y !== y2) return;
                        FB.add(w + '→' + z);
                    });
                });
            });

            // Step 10: extend FB with N
            const startNT = rules[0].lhs;
            EOarr.forEach(p => {
                const [a, b] = p.split('→');
                if (b === startNT) FB.add(a + '→N');
            });

            // Step 11: Follow sets for nullable NTs
            const Fol = {};
            nullableNTs.forEach(n => {
                Fol[n] = new Set();
                FB.forEach(p => {
                    const [a, b] = p.split('→');
                    if (a === n && Ts.includes(b)) Fol[n].add(b);
                    if (a === n && b === 'N') Fol[n].add('N');
                });
            });

            // Step 12: Selection sets
            const Sel = rules.map((r, i) => {
                const first = new Set(RuleFirst[i]);
                if (nullableRules.has(i)) {
                    const fol = Fol[r.lhs] || new Set();
                    fol.forEach(x => first.add(x));
                }
                return first;
            });

            // Check LL(1)
            const byNT = {};
            rules.forEach((r, i) => {
                if (!byNT[r.lhs]) byNT[r.lhs] = [];
                byNT[r.lhs].push(i);
            });
            let isLL1 = true;
            Object.entries(byNT).forEach(([nt, idxs]) => {
                for (let a = 0; a < idxs.length; a++)
                    for (let b = a + 1; b < idxs.length; b++) {
                        const inter = [...Sel[idxs[a]]].filter(x => Sel[idxs[b]].has(x));
                        if (inter.length) isLL1 = false;
                    }
            });

            // Render
            const out = document.getElementById('ss-out');
            out.style.display = 'block';
            out.innerHTML = '';

            function mkStep(n, title, html) {
                const d = document.createElement('div');
                d.className = 'step-block';
                d.innerHTML = `<div style="display:flex;align-items:center;gap:0;margin-bottom:8px;">
      <span class="step-num">${n}</span>
      <span class="step-title">${title}</span>
    </div>${html}`;
                out.appendChild(d);
            }

            mkStep(1, 'Nullable Rules &amp; Nonterminals',
                `<div class="step-result">${nullableNTs.size?'Nullable NTs: '+[...nullableNTs].join(', '):'(none)'}\nNullable rules: ${nullableRules.size?[...nullableRules].map(i=>'Rule '+(i+1)+': '+rules[i].lhs+' → '+(rules[i].rhs||'ε')).join(', '):'(none)'}</div>`);

            mkStep(2, 'Begins Directly With (BDW)',
                `<div class="step-result">${[...BDW].filter(p=>NTs.includes(p.split('→')[0])).map(p=>p.replace('→',' BDW ')).join('\n')||'(none)'}</div>`);

            mkStep(3, 'Begins With (BW) = reflexive-transitive closure of BDW',
                `<div class="step-result">${[...BW].filter(p=>{const[a,b]=p.split('→');return NTs.includes(a)&&NTs.includes(b)&&a!==b;}).map(p=>p.replace('→',' BW ')).join('\n')||'(no extra transitive pairs)'}</div>`);

            mkStep(4, 'First Sets',
                `<div class="step-result">${NTs.map(n=>`First(${n}) = {${[...First[n]].join(', ')}}`).join('\n')}</div>`);

            mkStep(5, 'First of Each Rule RHS',
                `<div class="step-result">${rules.map((r,i)=>`Rule ${i+1}: First(${r.rhs||'ε'}) = {${[...RuleFirst[i]].join(', ')}}`).join('\n')}</div>`);

            if (nullableNTs.size === 0) {
                mkStep('6-11', '(Skipped — no nullable rules)', `<div class="step-result empty">Steps 6–11 are only needed when nullable nonterminals exist.</div>`);
            } else {
                mkStep(6, 'Followed Directly By (FDB)',
                    `<div class="step-result">${[...FDB].map(p=>p.replace('→',' FDB ')).join('\n')||'(none)'}</div>`);
                mkStep(7, 'Is Direct End Of (DEO)',
                    `<div class="step-result">${[...DEO].map(p=>p.replace('→',' DEO ')).join('\n')||'(none)'}</div>`);
                mkStep(8, 'Is End Of (EO) = closure of DEO',
                    `<div class="step-result">${[...EO].filter(p=>{const[a,b]=p.split('→');return a!==b&&!Ts.includes(a);}).map(p=>p.replace('→',' EO ')).join('\n')||'(none beyond reflexive)'}</div>`);
                mkStep(9, 'Is Followed By (FB)',
                    `<div class="step-result">${[...FB].filter(p=>!p.startsWith('N→')).map(p=>p.replace('→',' FB ')).join('\n')||'(none)'}</div>`);
                mkStep(10, 'Extend FB with Endmarker N',
                    `<div class="step-result">${[...FB].filter(p=>p.endsWith('→N')).map(p=>p.replace('→N',' FB N')).join('\n')||'(none)'}</div>`);
                mkStep(11, 'Follow Sets for Nullable NTs',
                    `<div class="step-result">${[...nullableNTs].map(n=>`Fol(${n}) = {${[...(Fol[n]||new Set())].join(', ')}}`).join('\n')||'(none)'}</div>`);
            }

            mkStep(12, 'Selection Sets',
                `<div class="step-result">${rules.map((r,i)=>`Sel(${i+1}): ${r.lhs} → ${r.rhs||'ε'}  →  {${[...Sel[i]].join(', ')}}`).join('\n')}</div>`);

            const ll1div = document.createElement('div');
            ll1div.style.cssText = 'margin-top:14px;padding:14px 18px;border-radius:10px;font-weight:700;font-size:14px;';
            if (isLL1) {
                ll1div.style.cssText += 'background:#F0FDF4;border:1.5px solid #10B981;color:#065F46;';
                ll1div.innerHTML = '✅ This grammar IS LL(1) — all rules defining the same nonterminal have disjoint selection sets.';
            } else {
                ll1div.style.cssText += 'background:#FFF1F2;border:1.5px solid #EF4444;color:#9F1239;';
                ll1div.innerHTML = '❌ This grammar is NOT LL(1) — some rules defining the same nonterminal have overlapping selection sets.';
            }
            out.appendChild(ll1div);
        }


        // ─────────────────────────────────────────────────────────────────
        // TOOL 2 — LL(1) Arithmetic Parser (G16) + Atom Generator
        // ─────────────────────────────────────────────────────────────────
        function ll1_load(s) {
            document.getElementById('ll1-input').value = s;
            ll1_run();
        }

        let ll1_atoms_out = [],
            ll1_trace_lines = [],
            ll1_temps = 0;

        function ll1_alloc() {
            ll1_temps++;
            return 'T' + ll1_temps;
        }

        function ll1_tokenize(s) {
            const toks = [];
            let i = 0;
            while (i < s.length) {
                if (/\s/.test(s[i])) {
                    i++;
                    continue;
                }
                if (s.startsWith('var', i)) {
                    toks.push('var');
                    i += 3;
                    continue;
                }
                if (s.startsWith('const', i)) {
                    toks.push('const');
                    i += 5;
                    continue;
                }
                toks.push(s[i]);
                i++;
            }
            return toks;
        }

        let ll1_toks = [],
            ll1_pos = 0;

        function ll1_peek() {
            return ll1_pos < ll1_toks.length ? ll1_toks[ll1_pos] : null;
        }

        function ll1_consume() {
            return ll1_toks[ll1_pos++];
        }

        function ll1_log(msg) {
            ll1_trace_lines.push(msg);
        }

        function ll1_Expr() { // returns location
            const p = ll1_peek();
            if (p === '(' || p === 'var' || p === 'const') {
                ll1_log('Expr → Term Elist');
                const q = ll1_Term();
                return ll1_Elist(q);
            } else throw new Error('Unexpected token in Expr: ' + p);
        }

        function ll1_Elist(p) {
            const peek = ll1_peek();
            if (peek === '+') {
                ll1_consume();
                ll1_log('Elist → + Term {ADD} Elist');
                const r = ll1_Term();
                const s = ll1_alloc();
                ll1_atoms_out.push({
                    op: 'ADD',
                    a: p,
                    b: r,
                    res: s
                });
                return ll1_Elist(s);
            } else if (peek === '-') {
                ll1_consume();
                ll1_log('Elist → - Term {SUB} Elist');
                const r = ll1_Term();
                const s = ll1_alloc();
                ll1_atoms_out.push({
                    op: 'SUB',
                    a: p,
                    b: r,
                    res: s
                });
                return ll1_Elist(s);
            } else {
                ll1_log('Elist → ε');
                return p;
            }
        }

        function ll1_Term() {
            const p = ll1_peek();
            if (p === '(' || p === 'var' || p === 'const') {
                ll1_log('Term → Factor Tlist');
                const q = ll1_Factor();
                return ll1_Tlist(q);
            } else throw new Error('Unexpected token in Term: ' + p);
        }

        function ll1_Tlist(p) {
            const peek = ll1_peek();
            if (peek === '*') {
                ll1_consume();
                ll1_log('Tlist → * Factor {MUL} Tlist');
                const r = ll1_Factor();
                const s = ll1_alloc();
                ll1_atoms_out.push({
                    op: 'MUL',
                    a: p,
                    b: r,
                    res: s
                });
                return ll1_Tlist(s);
            } else if (peek === '/') {
                ll1_consume();
                ll1_log('Tlist → / Factor {DIV} Tlist');
                const r = ll1_Factor();
                const s = ll1_alloc();
                ll1_atoms_out.push({
                    op: 'DIV',
                    a: p,
                    b: r,
                    res: s
                });
                return ll1_Tlist(s);
            } else {
                ll1_log('Tlist → ε');
                return p;
            }
        }

        function ll1_Factor() {
            const p = ll1_peek();
            if (p === '(') {
                ll1_consume();
                ll1_log('Factor → ( Expr )');
                const r = ll1_Expr();
                if (ll1_peek() === ')') ll1_consume();
                else throw new Error('Expected )');
                return r;
            } else if (p === 'var' || p === 'const') {
                ll1_consume();
                const loc = p + '_' + ll1_alloc();
                ll1_log('Factor → ' + p + ' [loc: ' + loc + ']');
                return loc;
            } else throw new Error('Unexpected token in Factor: ' + p);
        }

        function ll1_run() {
            ll1_atoms_out = [];
            ll1_trace_lines = [];
            ll1_temps = 0;
            const input = document.getElementById('ll1-input').value.trim();
            ll1_toks = ll1_tokenize(input);
            ll1_pos = 0;
            const out = document.getElementById('ll1-out');
            let result, err = null;
            try {
                result = ll1_Expr();
                if (ll1_peek() !== null) throw new Error('Leftover tokens: ' + ll1_peek());
            } catch (e) {
                err = e.message;
            }
            out.style.display = 'block';
            document.getElementById('ll1-trace').textContent = ll1_trace_lines.join('\n');
            const atomsDiv = document.getElementById('ll1-atoms');
            atomsDiv.innerHTML = '';
            ll1_atoms_out.forEach(a => {
                const d = document.createElement('div');
                d.className = 'atom-row';
                d.innerHTML = `<span class="atom-tag atom-${a.op}">${a.op}</span><span style="flex:1;">${escH(a.a)}</span><span>${escH(a.b)}</span><span>→</span><span style="color:var(--purple);font-weight:700;">${escH(a.res)}</span>`;
                atomsDiv.appendChild(d);
            });
            if (!ll1_atoms_out.length) atomsDiv.innerHTML = '<div style="font-size:12px;color:var(--muted);font-style:italic;">No atoms (single operand, no operations)</div>';
            const v = document.getElementById('ll1-verdict');
            if (err) {
                v.className = 'badge badge-fail';
                v.textContent = '❌ Parse error: ' + err;
            } else {
                v.className = 'badge badge-ok';
                v.textContent = '✅ Parsed — result in: ' + result;
            }
        }


        // ─────────────────────────────────────────────────────────────────
        // TOOL 3 — Decaf Attributed Derivation Tree
        // ─────────────────────────────────────────────────────────────────
        function decaf_load(start, expr) {
            document.getElementById('decaf-start').value = start;
            document.getElementById('decaf-input').value = expr;
            decaf_run();
        }

        let decaf_atoms = [],
            decaf_temps = 0,
            decaf_labels = 0;

        function dc_alloc() {
            decaf_temps++;
            return 'T' + decaf_temps;
        }

        function dc_newlabel() {
            decaf_labels++;
            return 'L' + decaf_labels;
        }

        function dc_tokenize(s) {
            const toks = [];
            let i = 0;
            while (i < s.length) {
                if (/\s/.test(s[i])) {
                    i++;
                    continue;
                }
                // two-char ops
                if (i + 1 < s.length) {
                    const two = s.slice(i, i + 2);
                    if (['==', '!=', '<=', '>='].includes(two)) {
                        toks.push(two);
                        i += 2;
                        continue;
                    }
                }
                toks.push(s[i]);
                i++;
            }
            return toks;
        }

        const CMP_CODE = {
            '==': 1,
            '<': 2,
            '>': 3,
            '<=': 4,
            '>=': 5,
            '!=': 6
        };

        let dc_toks = [],
            dc_pos = 0,
            dc_tree = null;

        function dc_peek() {
            return dc_pos < dc_toks.length ? dc_toks[dc_pos] : null;
        }

        function dc_consume() {
            return dc_toks[dc_pos++];
        }

        function dc_BoolExpr() {
            const node = {
                label: 'BoolExpr',
                children: []
            };
            const exprNode1 = dc_Expr();
            node.children.push(exprNode1);
            const cmpTok = dc_peek();
            const cmpCodes = new Set(['==', '!=', '<', '>', '<=', '>=']);
            if (!cmpCodes.has(cmpTok)) throw new Error('Expected comparison operator, got: ' + cmpTok);
            dc_consume();
            const cmpNode = {
                label: cmpTok,
                children: []
            };
            node.children.push(cmpNode);
            const exprNode2 = dc_Expr();
            node.children.push(exprNode2);
            const lbl = dc_newlabel();
            const code = CMP_CODE[cmpTok] || 1;
            decaf_atoms.push({
                op: 'TST',
                a: exprNode1._loc,
                b: exprNode2._loc,
                cmp: 7 - code,
                lbl
            });
            node._lbl = lbl;
            const tst = {
                label: '{TST}',
                note: exprNode1._loc + ',' + exprNode2._loc + ',,' + (7 - code) + ',' + lbl,
                children: []
            };
            node.children.push(tst);
            return node;
        }

        function dc_Expr() {
            const node = {
                label: 'Expr',
                children: []
            };
            const peek = dc_peek();
            // check if ident followed by =
            if (/^[a-zA-Z_][a-zA-Z0-9_]*$/.test(peek || '') && dc_toks[dc_pos + 1] === '=') {
                const assignNode = dc_AssignExpr();
                node.children.push(assignNode);
                node._loc = assignNode._loc;
            } else {
                const rv = dc_Rvalue();
                node.children.push(rv);
                node._loc = rv._loc;
            }
            return node;
        }

        function dc_AssignExpr() {
            const node = {
                label: 'AssignExpr',
                children: []
            };
            const id = dc_consume(); // ident
            const idNode = {
                label: id,
                children: [],
                _loc: id
            };
            node.children.push(idNode);
            if (dc_peek() !== '=') throw new Error('Expected = in assignment');
            dc_consume();
            const eq = {
                label: '=',
                children: []
            };
            node.children.push(eq);
            const exprNode = dc_Expr();
            node.children.push(exprNode);
            decaf_atoms.push({
                op: 'MOV',
                a: exprNode._loc,
                b: '',
                res: id
            });
            node.children.push({
                label: '{MOV}',
                note: exprNode._loc + ',,' + id,
                children: []
            });
            node._loc = id;
            return node;
        }

        function dc_Rvalue() {
            const node = {
                label: 'Rvalue',
                children: []
            };
            const termNode = dc_Term();
            node.children.push(termNode);
            const elistNode = dc_Elist(termNode._loc);
            node.children.push(elistNode);
            node._loc = elistNode._loc;
            return node;
        }

        function dc_Elist(p) {
            const node = {
                label: 'Elist',
                note: 'p=' + p,
                children: []
            };
            const peek = dc_peek();
            if (peek === '+') {
                dc_consume();
                const termNode = dc_Term();
                node.children.push(termNode);
                const s = dc_alloc();
                decaf_atoms.push({
                    op: 'ADD',
                    a: p,
                    b: termNode._loc,
                    res: s
                });
                node.children.push({
                    label: '{ADD}',
                    note: p + ',' + termNode._loc + ',' + s,
                    children: []
                });
                const rest = dc_Elist(s);
                node.children.push(rest);
                node._loc = rest._loc;
            } else if (peek === '-') {
                dc_consume();
                const termNode = dc_Term();
                node.children.push(termNode);
                const s = dc_alloc();
                decaf_atoms.push({
                    op: 'SUB',
                    a: p,
                    b: termNode._loc,
                    res: s
                });
                node.children.push({
                    label: '{SUB}',
                    note: p + ',' + termNode._loc + ',' + s,
                    children: []
                });
                const rest = dc_Elist(s);
                node.children.push(rest);
                node._loc = rest._loc;
            } else {
                node.children.push({
                    label: 'ε',
                    children: []
                });
                node._loc = p;
            }
            return node;
        }

        function dc_Term() {
            const node = {
                label: 'Term',
                children: []
            };
            const factNode = dc_Factor();
            node.children.push(factNode);
            const tlistNode = dc_Tlist(factNode._loc);
            node.children.push(tlistNode);
            node._loc = tlistNode._loc;
            return node;
        }

        function dc_Tlist(p) {
            const node = {
                label: 'Tlist',
                note: 'p=' + p,
                children: []
            };
            const peek = dc_peek();
            if (peek === '*') {
                dc_consume();
                const factNode = dc_Factor();
                node.children.push(factNode);
                const s = dc_alloc();
                decaf_atoms.push({
                    op: 'MUL',
                    a: p,
                    b: factNode._loc,
                    res: s
                });
                node.children.push({
                    label: '{MUL}',
                    note: p + ',' + factNode._loc + ',' + s,
                    children: []
                });
                const rest = dc_Tlist(s);
                node.children.push(rest);
                node._loc = rest._loc;
            } else if (peek === '/') {
                dc_consume();
                const factNode = dc_Factor();
                node.children.push(factNode);
                const s = dc_alloc();
                decaf_atoms.push({
                    op: 'DIV',
                    a: p,
                    b: factNode._loc,
                    res: s
                });
                node.children.push({
                    label: '{DIV}',
                    note: p + ',' + factNode._loc + ',' + s,
                    children: []
                });
                const rest = dc_Tlist(s);
                node.children.push(rest);
                node._loc = rest._loc;
            } else {
                node.children.push({
                    label: 'ε',
                    children: []
                });
                node._loc = p;
            }
            return node;
        }

        function dc_Factor() {
            const node = {
                label: 'Factor',
                children: []
            };
            const peek = dc_peek();
            if (peek === '(') {
                dc_consume();
                const exprNode = dc_Expr();
                node.children.push({
                    label: '(',
                    children: []
                }, exprNode, {
                    label: ')',
                    children: []
                });
                if (dc_peek() === ')') dc_consume();
                else throw new Error('Expected )');
                node._loc = exprNode._loc;
            } else if (peek === '-') {
                dc_consume();
                const factNode = dc_Factor();
                const s = dc_alloc();
                decaf_atoms.push({
                    op: 'NEG',
                    a: factNode._loc,
                    b: '',
                    res: s
                });
                node.children.push({
                    label: '-',
                    children: []
                }, factNode, {
                    label: '{NEG}',
                    note: factNode._loc + ',,' + s,
                    children: []
                });
                node._loc = s;
            } else if (/^[0-9]/.test(peek || '')) {
                dc_consume();
                node.children.push({
                    label: peek,
                    children: []
                });
                node._loc = peek;
            } else if (/^[a-zA-Z_]/.test(peek || '')) {
                dc_consume();
                node.children.push({
                    label: peek,
                    children: []
                });
                node._loc = peek;
            } else throw new Error('Unexpected token in Factor: ' + peek);
            return node;
        }

        function decaf_run() {
            decaf_atoms = [];
            decaf_temps = 0;
            decaf_labels = 0;
            const input = document.getElementById('decaf-input').value.trim();
            const startSym = document.getElementById('decaf-start').value;
            dc_toks = dc_tokenize(input);
            dc_pos = 0;
            let tree, err = null;
            try {
                tree = startSym === 'boolexpr' ? dc_BoolExpr() : dc_Expr();
                if (dc_peek() !== null) throw new Error('Leftover tokens: ' + dc_peek());
            } catch (e) {
                err = e.message;
            }

            const out = document.getElementById('decaf-out');
            out.style.display = 'block';

            const log = document.getElementById('decaf-log');
            if (err) {
                log.textContent = '❌ Parse error: ' + err;
                document.getElementById('decaf-atoms').innerHTML = '';
                document.getElementById('decaf-svg').innerHTML = '';
                return;
            }

            log.textContent = '✅ Parsed successfully. Atoms: ' + decaf_atoms.length;

            // Draw tree
            dc_drawTree(tree);

            // Atoms
            const ad = document.getElementById('decaf-atoms');
            ad.innerHTML = '';
            decaf_atoms.forEach(a => {
                const d = document.createElement('div');
                d.className = 'atom-row';
                if (a.op === 'TST') {
                    d.innerHTML = `<span class="atom-tag atom-TST">TST</span><span>${escH(a.a)}</span><span>${escH(a.b)}</span><span>cmp:${a.cmp}</span><span>→</span><span style="color:#EF4444;font-weight:700;">${escH(a.lbl)}</span>`;
                } else if (a.op === 'MOV') {
                    d.innerHTML = `<span class="atom-tag atom-MOV">MOV</span><span>${escH(a.a)}</span><span>→</span><span style="color:#3B82F6;font-weight:700;">${escH(a.res)}</span>`;
                } else {
                    d.innerHTML = `<span class="atom-tag atom-${a.op}">${a.op}</span><span>${escH(a.a)}</span><span>${escH(a.b)}</span><span>→</span><span style="color:var(--purple);font-weight:700;">${escH(a.res)}</span>`;
                }
                ad.appendChild(d);
            });
            if (!decaf_atoms.length) ad.innerHTML = '<div style="font-size:12px;color:var(--muted);font-style:italic;">No atoms for this expression.</div>';
        }

        function dc_drawTree(root) {
            const svg = document.getElementById('decaf-svg');
            svg.innerHTML = '';
            const W = Math.max(700, svg.parentElement.clientWidth - 32);
            const DEPTH = dc_treeDepth(root);
            const H = (DEPTH + 1) * 58 + 20;
            svg.setAttribute('width', W);
            svg.setAttribute('height', H);

            const leaves = [];

            function countL(n) {
                if (!n.children || n.children.length === 0) {
                    leaves.push(n);
                } else n.children.forEach(countL);
            }
            countL(root);
            const lw = W / (leaves.length + 1);
            let li = 0;

            function assignX(n, d) {
                n._d = d;
                if (!n.children || n.children.length === 0) {
                    li++;
                    n._x = li * lw;
                } else {
                    n.children.forEach(c => assignX(c, d + 1));
                    n._x = n.children.reduce((s, c) => s + c._x, 0) / n.children.length;
                }
                n._y = d * 58 + 30;
            }
            assignX(root, 0);

            const NT_CLR = {
                Expr: '#6366F1',
                AssignExpr: '#3B82F6',
                Rvalue: '#8B5CF6',
                BoolExpr: '#EF4444',
                Elist: '#10B981',
                Term: '#059669',
                Tlist: '#F59E0B',
                Factor: '#F97316'
            };

            function drawE(n) {
                (n.children || []).forEach(c => {
                    const ln = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                    ln.setAttribute('x1', n._x);
                    ln.setAttribute('y1', n._y);
                    ln.setAttribute('x2', c._x);
                    ln.setAttribute('y2', c._y);
                    ln.setAttribute('stroke', '#CBD5E1');
                    ln.setAttribute('stroke-width', '1.5');
                    svg.appendChild(ln);
                    drawE(c);
                });
            }

            function drawN(n) {
                const isLeaf = !n.children || n.children.length === 0;
                const isAction = n.label.startsWith('{');
                const clr = isAction ? '#EF4444' : isLeaf ? '#374151' : (NT_CLR[n.label] || '#6366F1');
                const r = isLeaf ? 13 : 18;
                if (isLeaf || isAction) {
                    const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                    rect.setAttribute('x', n._x - r);
                    rect.setAttribute('y', n._y - r);
                    rect.setAttribute('width', r * 2);
                    rect.setAttribute('height', r * 2);
                    rect.setAttribute('rx', '5');
                    rect.setAttribute('fill', isAction ? '#FFF1F2' : '#EEF2FF');
                    rect.setAttribute('stroke', clr);
                    rect.setAttribute('stroke-width', '1.5');
                    svg.appendChild(rect);
                } else {
                    const circ = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                    circ.setAttribute('cx', n._x);
                    circ.setAttribute('cy', n._y);
                    circ.setAttribute('r', r);
                    circ.setAttribute('fill', clr + '22');
                    circ.setAttribute('stroke', clr);
                    circ.setAttribute('stroke-width', '2');
                    svg.appendChild(circ);
                }
                const t = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                t.setAttribute('x', n._x);
                t.setAttribute('y', n._y + 4);
                t.setAttribute('text-anchor', 'middle');
                t.setAttribute('fill', clr);
                t.setAttribute('font-size', n.label.length > 6 ? '8' : '10');
                t.setAttribute('font-weight', '700');
                t.setAttribute('font-family', 'JetBrains Mono');
                t.textContent = n.label;
                svg.appendChild(t);
                (n.children || []).forEach(drawN);
            }
            drawE(root);
            drawN(root);
        }

        function dc_treeDepth(n) {
            return (!n.children || n.children.length === 0) ? 0 : 1 + Math.max(...n.children.map(dc_treeDepth));
        }


        // ─────────────────────────────────────────────────────────────────
        // TOOL 4 — Control Structure Atom Generator
        // ─────────────────────────────────────────────────────────────────
        function ctrl_changed() {
            const type = document.getElementById('ctrl-type').value;
            const f = document.getElementById('ctrl-fields');
            const cmpOpts = '<option>==</option><option>&lt;</option><option>&gt;</option><option>&lt;=</option><option>&gt;=</option><option>!=</option>';
            let html = '';
            if (type === 'while') {
                html = `<div style="display:flex;gap:10px;flex-wrap:wrap;">
      <div style="flex:1;min-width:120px;"><label class="lbl">BoolExpr: left</label><input class="tool-input" id="c-e1" value="x"></div>
      <div><label class="lbl">Comparison</label><select id="c-cmp">${cmpOpts}</select></div>
      <div style="flex:1;min-width:120px;"><label class="lbl">BoolExpr: right</label><input class="tool-input" id="c-e2" value="0"></div>
    </div>
    <div style="margin-top:10px;"><label class="lbl">Stmt (description)</label><input class="tool-input" id="c-stmt1" value="// loop body"></div>`;
            } else if (type === 'for') {
                html = `<div style="display:flex;gap:10px;flex-wrap:wrap;">
      <div style="flex:1;"><label class="lbl">Init Expr</label><input class="tool-input" id="c-init" value="i = 0"></div>
      <div style="flex:1;"><label class="lbl">Bool left</label><input class="tool-input" id="c-e1" value="i"></div>
      <div><label class="lbl">Cmp</label><select id="c-cmp">${cmpOpts}</select></div>
      <div style="flex:1;"><label class="lbl">Bool right</label><input class="tool-input" id="c-e2" value="10"></div>
      <div style="flex:1;"><label class="lbl">Update Expr</label><input class="tool-input" id="c-update" value="i = i + 1"></div>
    </div>
    <div style="margin-top:10px;"><label class="lbl">Stmt</label><input class="tool-input" id="c-stmt1" value="// loop body"></div>`;
            } else if (type === 'if') {
                html = `<div style="display:flex;gap:10px;flex-wrap:wrap;">
      <div style="flex:1;"><label class="lbl">Bool left</label><input class="tool-input" id="c-e1" value="a"></div>
      <div><label class="lbl">Cmp</label><select id="c-cmp">${cmpOpts}</select></div>
      <div style="flex:1;"><label class="lbl">Bool right</label><input class="tool-input" id="c-e2" value="b"></div>
    </div>
    <div style="margin-top:10px;"><label class="lbl">Then Stmt</label><input class="tool-input" id="c-stmt1" value="// then branch"></div>`;
            } else {
                html = `<div style="display:flex;gap:10px;flex-wrap:wrap;">
      <div style="flex:1;"><label class="lbl">Bool left</label><input class="tool-input" id="c-e1" value="a"></div>
      <div><label class="lbl">Cmp</label><select id="c-cmp">${cmpOpts}</select></div>
      <div style="flex:1;"><label class="lbl">Bool right</label><input class="tool-input" id="c-e2" value="b"></div>
    </div>
    <div style="margin-top:10px;"><label class="lbl">Then Stmt</label><input class="tool-input" id="c-stmt1" value="// then branch"></div>
    <div style="margin-top:8px;"><label class="lbl">Else Stmt</label><input class="tool-input" id="c-stmt2" value="// else branch"></div>`;
            }
            f.innerHTML = html;
            document.getElementById('ctrl-out').style.display = 'none';
        }

        function ctrl_example() {
            const type = document.getElementById('ctrl-type').value;
            ctrl_changed();
            setTimeout(() => {
                if (type === 'while') {
                    document.getElementById('c-e1').value = 'x';
                    document.getElementById('c-cmp').value = '>';
                    document.getElementById('c-e2').value = '0';
                    document.getElementById('c-stmt1').value = '// x = x - 1';
                } else if (type === 'for') {
                    document.getElementById('c-init').value = 'i = 1';
                    document.getElementById('c-e1').value = 'i';
                    document.getElementById('c-cmp').value = '<=';
                    document.getElementById('c-e2').value = '100';
                    document.getElementById('c-update').value = 'i = i + 1';
                    document.getElementById('c-stmt1').value = '// body';
                } else if (type === 'if') {
                    document.getElementById('c-e1').value = 'a';
                    document.getElementById('c-cmp').value = '==';
                    document.getElementById('c-e2').value = 'b';
                    document.getElementById('c-stmt1').value = '// do something';
                } else {
                    document.getElementById('c-e1').value = 'a';
                    document.getElementById('c-cmp').value = '==';
                    document.getElementById('c-e2').value = 'b';
                    document.getElementById('c-stmt1').value = '// then branch';
                    document.getElementById('c-stmt2').value = '// else branch';
                }
            }, 50);
        }

        let ctrl_lbl = 0;

        function ctrl_newlbl() {
            ctrl_lbl++;
            return 'L' + ctrl_lbl;
        }

        const CMP_NAMES = {
            '==': '==',
            '<': '<',
            '>': '>',
            '<=': '<=',
            '>=': '>=',
            '!=': '!='
        };

        function ctrl_complement(c) {
            const m = {
                '==': '!=',
                '<': '>=',
                '>': '<=',
                '<=': '>',
                '>=': '<',
                '!=': '=='
            };
            return m[c] || c;
        }

        function mkAtomEl(op, fields, note) {
            const d = document.createElement('div');
            d.className = 'atom-row';
            const cls = 'atom-' + op;
            d.innerHTML = `<span class="atom-tag ${cls}">${escH(op)}</span>${fields.map(f=>`<span>${escH(f)}</span>`).join('')}${note?`<span style="color:var(--muted);font-size:11px;margin-left:auto;">← ${escH(note)}</span>`:''}`;
            return d;
        }

        function mkComment(txt) {
            const d = document.createElement('div');
            d.style.cssText = 'padding:5px 12px;font-family:"JetBrains Mono",monospace;font-size:11px;color:#9CA3AF;font-style:italic;';
            d.textContent = txt;
            return d;
        }

        function mkDivider(txt) {
            const d = document.createElement('div');
            d.style.cssText = 'padding:4px 12px;font-family:"Syne",sans-serif;font-size:11px;font-weight:700;color:#6B7280;background:#F3F4F6;border-radius:6px;margin:4px 0;';
            d.textContent = txt;
            return d;
        }

        function ctrl_run() {
            ctrl_lbl = 0;
            const type = document.getElementById('ctrl-type').value;
            const e1 = (document.getElementById('c-e1') || {
                value: ''
            }).value || 'E1';
            const e2 = (document.getElementById('c-e2') || {
                value: ''
            }).value || 'E2';
            const rawCmp = (document.getElementById('c-cmp') || {
                value: '=='
            }).value;
            const cmp = rawCmp.replace('&lt;', '<').replace('&gt;', '>');
            const stmt1 = (document.getElementById('c-stmt1') || {
                value: 'Stmt'
            }).value || 'Stmt';
            const stmt2 = (document.getElementById('c-stmt2') || {
                value: 'Stmt'
            }).value || 'Stmt';
            const cmpCode = CMP_CODE[cmp] || 1;
            const invCode = 7 - cmpCode;

            const out = document.getElementById('ctrl-atoms');
            out.innerHTML = '';
            document.getElementById('ctrl-out').style.display = 'block';

            if (type === 'while') {
                const L1 = ctrl_newlbl(),
                    L2 = ctrl_newlbl();
                out.appendChild(mkComment('while (' + e1 + ' ' + cmp + ' ' + e2 + ') Stmt'));
                out.appendChild(mkAtomEl('LBL', [L1], 'mark top of loop'));
                out.appendChild(mkAtomEl('TST', [e1, e2, 'cmp:' + invCode, L2], 'branch to L2 if ' + e1 + ' ' + ctrl_complement(cmp) + ' ' + e2 + ' (condition false)'));
                out.appendChild(mkDivider('← atoms for: ' + stmt1));
                out.appendChild(mkAtomEl('JMP', [L1], 'go back to test'));
                out.appendChild(mkAtomEl('LBL', [L2], 'exit point'));

            } else if (type === 'for') {
                const init = (document.getElementById('c-init') || {
                    value: 'E1'
                }).value || 'E1';
                const update = (document.getElementById('c-update') || {
                    value: 'E3'
                }).value || 'E3';
                const L1 = ctrl_newlbl(),
                    L2 = ctrl_newlbl(),
                    L3 = ctrl_newlbl(),
                    L4 = ctrl_newlbl();
                out.appendChild(mkComment('for (' + init + '; ' + e1 + ' ' + cmp + ' ' + e2 + '; ' + update + ') Stmt'));
                out.appendChild(mkDivider('← atoms for init: ' + init));
                out.appendChild(mkAtomEl('LBL', [L1], 'top of boolean test'));
                out.appendChild(mkAtomEl('TST', [e1, e2, 'cmp:' + invCode, L2], 'branch to L2 if condition false'));
                out.appendChild(mkAtomEl('JMP', [L3], 'jump over update, into body'));
                out.appendChild(mkAtomEl('LBL', [L4], 'start of update expr'));
                out.appendChild(mkDivider('← atoms for update: ' + update));
                out.appendChild(mkAtomEl('JMP', [L1], 'jump to boolean test'));
                out.appendChild(mkAtomEl('LBL', [L3], 'start of body'));
                out.appendChild(mkDivider('← atoms for: ' + stmt1));
                out.appendChild(mkAtomEl('JMP', [L4], 'jump to update'));
                out.appendChild(mkAtomEl('LBL', [L2], 'exit point'));

            } else if (type === 'if') {
                const L1 = ctrl_newlbl(),
                    L2 = ctrl_newlbl();
                out.appendChild(mkComment('if (' + e1 + ' ' + cmp + ' ' + e2 + ') Stmt'));
                out.appendChild(mkAtomEl('TST', [e1, e2, 'cmp:' + invCode, L1], 'branch to L1 if condition false'));
                out.appendChild(mkDivider('← atoms for then: ' + stmt1));
                out.appendChild(mkAtomEl('LBL', [L1], 'exit point (skipped if true)'));

            } else {
                const L1 = ctrl_newlbl(),
                    L2 = ctrl_newlbl();
                out.appendChild(mkComment('if (' + e1 + ' ' + cmp + ' ' + e2 + ') Stmt else Stmt'));
                out.appendChild(mkAtomEl('TST', [e1, e2, 'cmp:' + invCode, L1], 'branch to L1 if condition false'));
                out.appendChild(mkDivider('← atoms for then: ' + stmt1));
                out.appendChild(mkAtomEl('JMP', [L2], 'jump over else branch'));
                out.appendChild(mkAtomEl('LBL', [L1], 'start of else'));
                out.appendChild(mkDivider('← atoms for else: ' + stmt2));
                out.appendChild(mkAtomEl('LBL', [L2], 'exit point'));
            }
        }

        // Init
        ctrl_changed();
    </script>
</body>

</html>