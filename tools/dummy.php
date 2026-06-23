<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CompileX - Learn Compiler Principles</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="body.css">
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
            margin: 0;
        }

        /* ── Navbar ── */
        #navbar {
            background-color: #18181B;
        }

        #navbar a,
        #navbar .nav-link {
            color: #FFFFFF;
        }

        #navbar a:hover,
        #navbar .nav-link:hover {
            color: #A1A1AA;
        }

        #navbar a.active,
        #navbar .nav-link.active {
            color: #FFFFFF;
            border-bottom: 4px solid #6366F1;
        }

        .button-28 {
            appearance: none;
            background-color: transparent;
            border: 2px solid #f7f3f3;
            border-radius: 15px;
            color: #504141;
            cursor: pointer;
            display: inline-block;
            font-size: 10px;
            font-weight: 600;
            min-height: 30px;
            outline: none;
            padding: 12px 20px;
            text-align: center;
            text-decoration: none;
            transition: all 300ms cubic-bezier(.23, 1, 0.32, 1);
            width: auto;
        }

        .button-28:hover {
            color: #fff;
            background-color: #1A1A1A;
            box-shadow: rgba(0, 0, 0, 0.25) 0 8px 15px;
            transform: translateY(-2px);
        }

        footer {
            text-align: center;
            padding: 20px;
            background: #FFFFFF;
            margin-top: 40px;
            font-size: 14px;
            color: #6B7280;
        }

        /* ── Preset buttons ── */
        .preset-btns {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
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

        /* ── Shared tool wrap ── */
        .tool-wrap {
            background: var(--card);
            border: 1px solid rgba(99, 102, 241, .15);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            margin: 24px 0;
        }

        .tool-header {
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .tool-header h4 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: white;
            margin: 0;
        }

        .tool-body {
            padding: 24px;
        }

        .tool-input {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            padding: 10px 14px;
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
            margin-top: 12px;
            padding: 9px 22px;
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

        /* ── Chomsky badge colours ── */
        .badge-type0 {
            background: #FFF1F2;
            border: 1.5px solid #EF4444;
            color: #9F1239;
        }

        .badge-type1 {
            background: #FEF3C7;
            border: 1.5px solid #F59E0B;
            color: #92400E;
        }

        .badge-type2 {
            background: #EFF6FF;
            border: 1.5px solid #3B82F6;
            color: #1D4ED8;
        }

        .badge-type3 {
            background: #F0FDF4;
            border: 1.5px solid #10B981;
            color: #065F46;
        }

        .ch-badge {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 20px;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 13px;
            border: 1.5px solid;
            margin-top: 10px;
        }

        /* ── Derivation step chips ── */
        .deriv-stream {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 12px;
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

        .deriv-step.ds-final {
            background: #F0FDF4;
            border-color: #10B981;
        }

        .deriv-step.ds-applied {
            background: #FEF3C7;
            border-color: #F59E0B;
        }

        .deriv-rule-tag {
            margin-left: auto;
            font-size: 10px;
            opacity: .6;
            white-space: nowrap;
        }

        /* ── Parse tree SVG ── */
        #ptree-svg {
            display: block;
            width: 100%;
        }
    </style>
</head>

<body>

  
    

    <!-- ══ Chapter 3 Tools Card ════════════════════════════════════ -->
    <div style="max-width:700px; margin:40px auto; padding:0 20px;">
        <div style="background:#FFFFFF; border-radius:16px; box-shadow:0 4px 24px rgba(99,102,241,.10); border:1px solid rgba(99,102,241,.15); overflow:hidden;">

            <!-- Card header -->
            <div style="background:#18181B; padding:20px 28px;">
                <p style="font-size:12px; font-weight:600; letter-spacing:.1em; color:#A5B4FC; margin:0 0 6px;">CHAPTER 3 — SYNTAX ANALYSIS</p>
                <h2 style="font-size:22px; font-weight:700; color:#FFFFFF; margin:0 0 6px;">CHAPTER 3 — SYNTAX ANALYSIS</h2>
                
            </div>

            <!-- Tool buttons -->
            <div style="padding:24px 28px; display:flex; flex-direction:column; gap:14px;">

                <!-- Grammar Derivation Simulator -->
                <button onclick="document.getElementById('derivModal').style.display='flex'"
                    style="display:flex;align-items:center;gap:16px;background:#F8F7FF;border:1px solid rgba(99,102,241,.2);border-radius:12px;padding:16px 20px;cursor:pointer;text-align:left;width:100%;transition:all .2s;"
                    onmouseover="this.style.borderColor='#6366F1';this.style.background='#EEF2FF';"
                    onmouseout="this.style.borderColor='rgba(99,102,241,.2)';this.style.background='#F8F7FF';">
                    <div style="width:44px;height:44px;border-radius:10px;background:#EEF2FF;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">📜</div>
                    <div style="flex:1;">
                        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:#1E1B4B;">Grammar Derivation Simulator</div>
                        <div style="font-size:13px;color:#6B7280;margin-top:2px;">Step through derivations for G1, G2 and custom grammars — Section 3.0.1</div>
                    </div>
                    <div style="font-size:18px;color:#6366F1;flex-shrink:0;">▶</div>
                </button>

                <!-- Chomsky Hierarchy Classifier -->
                <button onclick="document.getElementById('chomskyModal').style.display='flex'"
                    style="display:flex;align-items:center;gap:16px;background:#F8F7FF;border:1px solid rgba(99,102,241,.2);border-radius:12px;padding:16px 20px;cursor:pointer;text-align:left;width:100%;transition:all .2s;"
                    onmouseover="this.style.borderColor='#6366F1';this.style.background='#EEF2FF';"
                    onmouseout="this.style.borderColor='rgba(99,102,241,.2)';this.style.background='#F8F7FF';">
                    <div style="width:44px;height:44px;border-radius:10px;background:#ECFDF5;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">🏷️</div>
                    <div style="flex:1;">
                        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:#1E1B4B;">Chomsky Hierarchy Classifier</div>
                        <div style="font-size:13px;color:#6B7280;margin-top:2px;">Classify any grammar rule into Type 0 / 1 / 2 / 3 — Section 3.0.2</div>
                    </div>
                    <div style="font-size:18px;color:#6366F1;flex-shrink:0;">▶</div>
                </button>

                <!-- Parse Tree Builder -->
                <button onclick="document.getElementById('ptreeModal').style.display='flex'"
                    style="display:flex;align-items:center;gap:16px;background:#F8F7FF;border:1px solid rgba(99,102,241,.2);border-radius:12px;padding:16px 20px;cursor:pointer;text-align:left;width:100%;transition:all .2s;"
                    onmouseover="this.style.borderColor='#6366F1';this.style.background='#EEF2FF';"
                    onmouseout="this.style.borderColor='rgba(99,102,241,.2)';this.style.background='#F8F7FF';">
                    <div style="width:44px;height:44px;border-radius:10px;background:#F0FDF4;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">🌿</div>
                    <div style="flex:1;">
                        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:#1E1B4B;">Parse Tree Builder</div>
                        <div style="font-size:13px;color:#6B7280;margin-top:2px;">Visualise derivation trees for arithmetic expressions using grammar G5 — Section 3.1</div>
                    </div>
                    <div style="font-size:18px;color:#6366F1;flex-shrink:0;">▶</div>
                </button>

                <!-- Pushdown Machine Simulator -->
                <button onclick="document.getElementById('pdmModal').style.display='flex'"
                    style="display:flex;align-items:center;gap:16px;background:#F8F7FF;border:1px solid rgba(99,102,241,.2);border-radius:12px;padding:16px 20px;cursor:pointer;text-align:left;width:100%;transition:all .2s;"
                    onmouseover="this.style.borderColor='#6366F1';this.style.background='#EEF2FF';"
                    onmouseout="this.style.borderColor='rgba(99,102,241,.2)';this.style.background='#F8F7FF';">
                    <div style="width:44px;height:44px;border-radius:10px;background:#FEF3C7;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">⚙️</div>
                    <div style="flex:1;">
                        <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:#1E1B4B;">Pushdown Machine Simulator</div>
                        <div style="font-size:13px;color:#6B7280;margin-top:2px;">Trace stack states for <code style="background:#FEF3C7;color:#92400E;padding:1px 6px;border-radius:4px;font-size:12px;">aⁿbⁿ</code> and balanced-parentheses machines — Fig 3.4 &amp; 3.6</div>
                    </div>
                    <div style="font-size:18px;color:#6366F1;flex-shrink:0;">▶</div>
                </button>

            </div>

            <!-- Card footer -->
            <div style="border-top:1px solid rgba(99,102,241,.1);padding:14px 28px;background:#F8F7FF;display:flex;align-items:center;gap:8px;">
                <span style="font-size:12px;color:#9CA3AF;">🔗 All tools are based on examples from your compiler principles textbook.</span>
            </div>
        </div>
    </div>


    <!-- ══════════════════════════════════════════════════════════════
     MODAL 1 — Grammar Derivation Simulator
     ══════════════════════════════════════════════════════════════ -->
    <div id="derivModal" onclick="if(event.target===this)this.style.display='none'"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:9999;align-items:center;justify-content:center;padding:20px;">
        <div style="background:#F8F7FF;border-radius:16px;width:100%;max-width:840px;max-height:90vh;overflow-y:auto;position:relative;">

            <div style="background:#18181B;padding:16px 24px;border-radius:16px 16px 0 0;display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:17px;color:#fff;">📜 Grammar Derivation Simulator</span>
                    <p style="font-size:12px;color:#A1A1AA;margin:4px 0 0;">Compiler Principles — Section 3.0.1</p>
                </div>
                <button onclick="document.getElementById('derivModal').style.display='none'"
                    style="background:transparent;border:1px solid #3F3F46;color:#A1A1AA;border-radius:8px;padding:6px 14px;cursor:pointer;font-size:13px;font-family:'Syne',sans-serif;">✕ Close</button>
            </div>

            <div style="padding:28px;">
                <p style="font-size:14px;color:#6B7280;margin-bottom:20px;line-height:1.7;">
                    Enter grammar rules (one per line) using the form <code>LHS → RHS</code>. Use <code>ε</code> or leave the RHS blank for epsilon.
                    Then enter your starting sentential form and click <strong>Step</strong> to apply one rule at a time, or <strong>Auto-Derive</strong> to find a full derivation automatically.
                </p>

                <div class="tool-wrap">
                    <div class="tool-header" style="background:linear-gradient(135deg,#6366F1,#818CF8);">
                        <h4>📜 Derivation Engine</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);">Left-most substitution</span>
                    </div>
                    <div class="tool-body">

                        <!-- Presets -->
                        <div style="margin-bottom:12px;">
                            <span style="font-size:12px;color:var(--muted);margin-right:6px;">Presets:</span>
                            <div class="preset-btns" style="display:inline-flex;gap:6px;flex-wrap:wrap;">
                                <button class="preset-btn" onclick="derivLoadPreset('g1')">G1 — Palindromes</button>
                                <button class="preset-btn" onclick="derivLoadPreset('g2')">G2 — aⁿbⁿ</button>
                                <button class="preset-btn" onclick="derivLoadPreset('g5')">G5 — Arithmetic</button>
                            </div>
                        </div>

                        <label style="font-size:12px;font-weight:700;color:var(--muted);margin-bottom:4px;display:block;">Grammar Rules (LHS → RHS, one per line):</label>
                        <textarea class="tool-textarea" id="deriv-rules" rows="5" spellcheck="false">S → 0S0
S → 1S1
S → 0
S → 1</textarea>

                        <div style="display:flex;gap:10px;margin-top:12px;flex-wrap:wrap;align-items:flex-end;">
                            <div style="flex:1;min-width:160px;">
                                <label style="font-size:12px;font-weight:700;color:var(--muted);margin-bottom:4px;display:block;">Starting form:</label>
                                <input class="tool-input" id="deriv-start" value="S" placeholder="e.g. S">
                            </div>
                            <div style="flex:1;min-width:160px;">
                                <label style="font-size:12px;font-weight:700;color:var(--muted);margin-bottom:4px;display:block;">Target string (for Auto-Derive):</label>
                                <input class="tool-input" id="deriv-target" value="0010100" placeholder="e.g. 0010100">
                            </div>
                        </div>

                        <div style="display:flex;gap:8px;margin-top:12px;flex-wrap:wrap;">
                            <button class="tool-btn" onclick="derivStep()">⏭ Step (left-most)</button>
                            <button class="tool-btn" onclick="derivAuto()">⚡ Auto-Derive</button>
                            <button class="tool-btn sec" onclick="derivReset()">↺ Reset</button>
                        </div>

                        <!-- Rule selector -->
                        <div id="deriv-rule-picker" style="display:none;margin-top:14px;">
                            <div style="font-size:12px;font-weight:700;color:var(--muted);margin-bottom:6px;">Choose which rule to apply to <strong id="deriv-target-nt" style="color:var(--purple);"></strong>:</div>
                            <div id="deriv-rule-btns" style="display:flex;gap:6px;flex-wrap:wrap;"></div>
                        </div>

                        <div id="deriv-stream" class="deriv-stream"></div>
                        <div id="deriv-log" class="tool-log" style="margin-top:12px;">Enter rules, then click Step or Auto-Derive.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ══════════════════════════════════════════════════════════════
     MODAL 2 — Chomsky Hierarchy Classifier
     ══════════════════════════════════════════════════════════════ -->
    <div id="chomskyModal" onclick="if(event.target===this)this.style.display='none'"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:9999;align-items:center;justify-content:center;padding:20px;">
        <div style="background:#F8F7FF;border-radius:16px;width:100%;max-width:840px;max-height:90vh;overflow-y:auto;position:relative;">

            <div style="background:#18181B;padding:16px 24px;border-radius:16px 16px 0 0;display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:17px;color:#fff;">🏷️ Chomsky Hierarchy Classifier</span>
                    <p style="font-size:12px;color:#A1A1AA;margin:4px 0 0;">Compiler Principles — Section 3.0.2</p>
                </div>
                <button onclick="document.getElementById('chomskyModal').style.display='none'"
                    style="background:transparent;border:1px solid #3F3F46;color:#A1A1AA;border-radius:8px;padding:6px 14px;cursor:pointer;font-size:13px;font-family:'Syne',sans-serif;">✕ Close</button>
            </div>

            <div style="padding:28px;">
                <p style="font-size:14px;color:#6B7280;margin-bottom:20px;line-height:1.7;">
                    Enter one or more grammar rules (one per line). Use capital letters for nonterminals, lowercase for terminals.
                    Use <code>ε</code> for epsilon. The classifier will apply Chomsky's hierarchy to each rule and explain why.
                </p>

                <div class="tool-wrap">
                    <div class="tool-header" style="background:linear-gradient(135deg,#10B981,#34D399);">
                        <h4>🏷️ Grammar Rule Classifier</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);">Type 0 → 1 → 2 → 3</span>
                    </div>
                    <div class="tool-body">

                        <div style="margin-bottom:12px;">
                            <span style="font-size:12px;color:var(--muted);margin-right:6px;">Examples:</span>
                            <div class="preset-btns" style="display:inline-flex;gap:6px;flex-wrap:wrap;">
                                <button class="preset-btn" onclick="chomskyLoadEx('ex1')">Textbook Ex. 1</button>
                                <button class="preset-btn" onclick="chomskyLoadEx('ex2')">Textbook Ex. 2</button>
                                <button class="preset-btn" onclick="chomskyLoadEx('ex3')">Mixed types</button>
                            </div>
                        </div>

                        <label style="font-size:12px;font-weight:700;color:var(--muted);margin-bottom:4px;display:block;">Grammar Rules (LHS → RHS, one per line):</label>
                        <textarea class="tool-textarea" id="chomsky-input" rows="5" spellcheck="false">A → aB
S → aBc
aSb → aAcBb
AB → BA</textarea>

                        <button class="tool-btn" style="margin-top:10px;" onclick="classifyChomsky()">🔍 Classify</button>

                        <div id="chomsky-output" style="display:none;margin-top:20px;">
                            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--text);margin-bottom:12px;">Classification results:</div>
                            <div id="chomsky-results"></div>

                            <!-- Hierarchy diagram -->
                            <div style="margin-top:24px;background:var(--code-bg);border-radius:12px;padding:20px;">
                                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:#A5B4FC;margin-bottom:14px;letter-spacing:.05em;">CHOMSKY HIERARCHY (outer = less restricted)</div>
                                <div style="position:relative;display:flex;flex-direction:column;gap:6px;">
                                    <div id="ch-ring0" style="border-radius:10px;padding:10px 16px;border:2px solid #EF4444;background:rgba(239,68,68,.08);">
                                        <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:#EF4444;">Type 0 — Unrestricted</span>
                                        <span style="font-size:11px;color:#94A3B8;margin-left:8px;">No restrictions on LHS or RHS</span>
                                    </div>
                                    <div id="ch-ring1" style="border-radius:10px;padding:10px 16px;border:2px solid #F59E0B;background:rgba(245,158,11,.08);margin-left:20px;">
                                        <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:#F59E0B;">Type 1 — Context-Sensitive</span>
                                        <span style="font-size:11px;color:#94A3B8;margin-left:8px;">αAγ → αβγ (A is single NT)</span>
                                    </div>
                                    <div id="ch-ring2" style="border-radius:10px;padding:10px 16px;border:2px solid #3B82F6;background:rgba(59,130,246,.08);margin-left:40px;">
                                        <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:#3B82F6;">Type 2 — Context-Free</span>
                                        <span style="font-size:11px;color:#94A3B8;margin-left:8px;">A → α (single NT on left)</span>
                                    </div>
                                    <div id="ch-ring3" style="border-radius:10px;padding:10px 16px;border:2px solid #10B981;background:rgba(16,185,129,.08);margin-left:60px;">
                                        <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:#10B981;">Type 3 — Right Linear</span>
                                        <span style="font-size:11px;color:#94A3B8;margin-left:8px;">A → aB or A → a</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ══════════════════════════════════════════════════════════════
     MODAL 3 — Parse Tree Builder (Grammar G5)
     ══════════════════════════════════════════════════════════════ -->
    <div id="ptreeModal" onclick="if(event.target===this)this.style.display='none'"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:9999;align-items:center;justify-content:center;padding:20px;">
        <div style="background:#F8F7FF;border-radius:16px;width:100%;max-width:900px;max-height:90vh;overflow-y:auto;position:relative;">

            <div style="background:#18181B;padding:16px 24px;border-radius:16px 16px 0 0;display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:17px;color:#fff;">🌿 Parse Tree Builder</span>
                    <p style="font-size:12px;color:#A1A1AA;margin:4px 0 0;">Compiler Principles — Grammar G5, Section 3.1</p>
                </div>
                <button onclick="document.getElementById('ptreeModal').style.display='none'"
                    style="background:transparent;border:1px solid #3F3F46;color:#A1A1AA;border-radius:8px;padding:6px 14px;cursor:pointer;font-size:13px;font-family:'Syne',sans-serif;">✕ Close</button>
            </div>

            <div style="padding:28px;">
                <p style="font-size:14px;color:#6B7280;margin-bottom:20px;line-height:1.7;">
                    Enter an arithmetic expression using <code>var</code>, <code>const</code>, <code>+</code>, <code>*</code>, <code>-</code>, <code>/</code>, and parentheses.
                    Grammar G5 enforces correct precedence (× before +) and left-associativity. The tree shows why the expression is unambiguous.
                </p>

                <div class="tool-wrap">
                    <div class="tool-header" style="background:linear-gradient(135deg,#059669,#10B981);">
                        <h4>🌿 G5 Parse Tree Visualiser</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);">Unambiguous arithmetic grammar</span>
                    </div>
                    <div class="tool-body">

                        <div style="margin-bottom:10px;">
                            <span style="font-size:12px;color:var(--muted);margin-right:6px;">Presets:</span>
                            <div class="preset-btns" style="display:inline-flex;gap:6px;flex-wrap:wrap;">
                                <button class="preset-btn" onclick="ptreeLoad('var + var * var')">var + var * var (Fig 3.9)</button>
                                <button class="preset-btn" onclick="ptreeLoad('(var + var) * const')">( var + var ) * const</button>
                                <button class="preset-btn" onclick="ptreeLoad('var + var + var')">var + var + var (left-assoc)</button>
                                <button class="preset-btn" onclick="ptreeLoad('var * var + const')">var * var + const</button>
                            </div>
                        </div>

                        <div style="display:flex;gap:10px;margin-top:8px;">
                            <input class="tool-input" id="ptree-input" value="var + var * var" placeholder="e.g. var + var * const" style="flex:1;">
                            <button class="tool-btn" style="margin-top:0;" onclick="buildPTree()">🌿 Build Tree</button>
                        </div>

                        <div id="ptree-out" style="margin-top:18px;display:none;">
                            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--text);margin-bottom:8px;">Derivation tree (G5):</div>
                            <div style="overflow-x:auto;background:white;border-radius:10px;border:1px solid rgba(99,102,241,.15);padding:16px;">
                                <svg id="ptree-svg" height="320"></svg>
                            </div>
                            <div id="ptree-log" class="tool-log" style="margin-top:10px;"></div>
                        </div>
                    </div>
                </div>

                <!-- G5 rules reference -->
                <div style="background:var(--code-bg);border-radius:10px;padding:16px 20px;margin-top:8px;">
                    <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:#A5B4FC;margin-bottom:10px;letter-spacing:.05em;">GRAMMAR G5 — UNAMBIGUOUS ARITHMETIC (Section 3.1)</div>
                    <div style="font-family:'JetBrains Mono',monospace;font-size:12px;color:#CDD6F4;line-height:2;">
                        1. Expr → Expr + Term<br>
                        2. Expr → Expr - Term<br>
                        3. Expr → Term<br>
                        4. Term → Term * Factor<br>
                        5. Term → Term / Factor<br>
                        6. Term → Factor<br>
                        7. Factor → ( Expr )<br>
                        8. Factor → var<br>
                        9. Factor → const
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ══════════════════════════════════════════════════════════════
     MODAL 4 — Pushdown Machine Simulator
     ══════════════════════════════════════════════════════════════ -->
    <div id="pdmModal" onclick="if(event.target===this)this.style.display='none'"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:9999;align-items:center;justify-content:center;padding:20px;">
        <div style="background:#F8F7FF;border-radius:16px;width:100%;max-width:860px;max-height:90vh;overflow-y:auto;position:relative;">

            <div style="background:#18181B;padding:16px 24px;border-radius:16px 16px 0 0;display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <span style="font-family:'Syne',sans-serif;font-weight:700;font-size:17px;color:#fff;">⚙️ Pushdown Machine Simulator</span>
                    <p style="font-size:12px;color:#A1A1AA;margin:4px 0 0;">Compiler Principles — Sections 3.0.4, Fig 3.4 &amp; 3.6</p>
                </div>
                <button onclick="document.getElementById('pdmModal').style.display='none'"
                    style="background:transparent;border:1px solid #3F3F46;color:#A1A1AA;border-radius:8px;padding:6px 14px;cursor:pointer;font-size:13px;font-family:'Syne',sans-serif;">✕ Close</button>
            </div>

            <div style="padding:28px;">
                <p style="font-size:14px;color:#6B7280;margin-bottom:20px;line-height:1.7;">
                    Choose a machine and enter an input string. The simulator traces every stack configuration and state transition, showing exactly how the pushdown machine accepts or rejects the input.
                </p>

                <div class="tool-wrap">
                    <div class="tool-header" style="background:linear-gradient(135deg,#F59E0B,#FBBF24);">
                        <h4>⚙️ Pushdown Machine Trace</h4>
                        <span style="font-size:12px;color:rgba(0,0,0,.5);">Step-by-step stack simulation</span>
                    </div>
                    <div class="tool-body">

                        <!-- Machine selector -->
                        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px;align-items:center;">
                            <div>
                                <label style="font-size:12px;font-weight:700;color:var(--muted);display:block;margin-bottom:4px;">Machine:</label>
                                <select id="pdm-machine" onchange="pdmMachineChanged()"
                                    style="font-family:'Syne',sans-serif;font-size:13px;padding:8px 12px;border:1.5px solid rgba(99,102,241,.25);border-radius:8px;background:var(--bg);color:var(--text);outline:none;">
                                    <option value="anbn">Fig 3.4 — aⁿbⁿ (accepts aⁿbⁿ, n≥0)</option>
                                    <option value="parens">Fig 3.6 — Balanced Parentheses</option>
                                </select>
                            </div>
                            <div style="flex:1;min-width:160px;">
                                <label style="font-size:12px;font-weight:700;color:var(--muted);display:block;margin-bottom:4px;">Input string:</label>
                                <input class="tool-input" id="pdm-input" value="aabb" placeholder="e.g. aabb" style="width:100%;">
                            </div>
                        </div>

                        <div style="margin-bottom:14px;">
                            <span style="font-size:12px;color:var(--muted);margin-right:6px;">Examples:</span>
                            <div class="preset-btns" style="display:inline-flex;gap:6px;flex-wrap:wrap;" id="pdm-presets">
                                <button class="preset-btn" onclick="pdmLoadPreset('aabb')">aabb ✓</button>
                                <button class="preset-btn" onclick="pdmLoadPreset('aaabbb')">aaabbb ✓</button>
                                <button class="preset-btn" onclick="pdmLoadPreset('')">ε (empty) ✓</button>
                                <button class="preset-btn" onclick="pdmLoadPreset('aab')">aab ✗</button>
                                <button class="preset-btn" onclick="pdmLoadPreset('abb')">abb ✗</button>
                            </div>
                        </div>

                        <div style="display:flex;gap:8px;">
                            <button class="tool-btn" onclick="pdmRun()">▶ Run Simulation</button>
                            <button class="tool-btn sec" onclick="pdmStep()">⏭ Step</button>
                            <button class="tool-btn sec" onclick="pdmReset()">↺ Reset</button>
                        </div>

                        <!-- Stack trace display -->
                        <div id="pdm-trace-wrap" style="margin-top:18px;display:none;">
                            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:13px;color:var(--text);margin-bottom:10px;">Execution trace:</div>
                            <div style="overflow-x:auto;">
                                <table style="width:100%;border-collapse:collapse;font-size:12px;" id="pdm-trace-table">
                                    <thead>
                                        <tr style="background:var(--code-bg);">
                                            <th style="color:#A5B4FC;font-family:'Syne',sans-serif;padding:8px 12px;text-align:left;font-size:11px;">Step</th>
                                            <th style="color:#A5B4FC;font-family:'Syne',sans-serif;padding:8px 12px;text-align:left;font-size:11px;">State</th>
                                            <th style="color:#A5B4FC;font-family:'Syne',sans-serif;padding:8px 12px;text-align:left;font-size:11px;">Input read</th>
                                            <th style="color:#A5B4FC;font-family:'Syne',sans-serif;padding:8px 12px;text-align:left;font-size:11px;">Stack (top→bottom)</th>
                                            <th style="color:#A5B4FC;font-family:'Syne',sans-serif;padding:8px 12px;text-align:left;font-size:11px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pdm-trace-body"></tbody>
                                </table>
                            </div>
                            <div id="pdm-verdict" style="margin-top:12px;padding:12px 18px;border-radius:10px;font-family:'Syne',sans-serif;font-weight:700;font-size:14px;display:none;"></div>
                        </div>

                        <div id="pdm-log" class="tool-log" style="margin-top:12px;">Choose a machine and enter an input string, then click Run.</div>
                    </div>
                </div>

                <!-- Machine description box -->
                <div id="pdm-desc" style="background:var(--code-bg);border-radius:10px;padding:16px 20px;margin-top:8px;">
                    <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:#A5B4FC;margin-bottom:8px;letter-spacing:.05em;">MACHINE DESCRIPTION — Fig 3.4 (Section 3.0.4)</div>
                    <div style="font-family:'JetBrains Mono',monospace;font-size:12px;color:#CDD6F4;line-height:1.9;">
                        States: S1 (start), S2<br>
                        Stack symbols: X, , (bottom marker)<br>
                        S1: on 'a' → push(X), advance | on 'N' (end) → if stack=, Accept<br>
                        S1: on 'b' → switch to S2, pop, advance<br>
                        S2: on 'b' → pop, advance | on 'N' → if stack=, Accept<br>
                        Language: { aⁿbⁿ | n ≥ 0 }
                    </div>
                </div>
            </div>
        </div>
    </div>


    


    <!-- ══════════════════════════════════════════════════════════════
     JAVASCRIPT
     ══════════════════════════════════════════════════════════════ -->
    <script>
        // ════════════════════════════════════════════════════════════════
        // TOOL 1 — Grammar Derivation Simulator
        // ════════════════════════════════════════════════════════════════

        const DERIV_PRESETS = {
            g1: {
                rules: 'S → 0S0\nS → 1S1\nS → 0\nS → 1',
                start: 'S',
                target: '0010100'
            },
            g2: {
                rules: 'S → ASB\nS → ε\nA → a\nB → b',
                start: 'S',
                target: 'aabb'
            },
            g5: {
                rules: 'Expr → Expr + Term\nExpr → Term\nTerm → Term * Factor\nTerm → Factor\nFactor → ( Expr )\nFactor → var\nFactor → const',
                start: 'Expr',
                target: 'var + var * var'
            }
        };

        let derivHistory = []; // array of sentential forms
        let derivStepIdx = 0;

        function derivLoadPreset(key) {
            const p = DERIV_PRESETS[key];
            document.getElementById('deriv-rules').value = p.rules;
            document.getElementById('deriv-start').value = p.start;
            document.getElementById('deriv-target').value = p.target;
            derivReset();
        }

        function parseRules() {
            const lines = document.getElementById('deriv-rules').value.trim().split('\n');
            const rules = [];
            for (const line of lines) {
                const sep = line.includes('→') ? '→' : (line.includes('->') ? '->' : null);
                if (!sep) continue;
                const [lhs, rhs] = line.split(sep).map(s => s.trim());
                rules.push({
                    lhs,
                    rhs: rhs === 'ε' || rhs === '' ? '' : rhs
                });
            }
            return rules;
        }

        function isNonterminal(tok) {
            // Capital letter word or multi-char starting with capital
            return /^[A-Z]/.test(tok);
        }

        function tokeniseSentential(form) {
            // Tokenise into known grammar symbols (multi-char like "Expr", "Term", etc.)
            // Strategy: greedy longest match against known nonterminals, else char-by-char
            const rules = parseRules();
            const nts = [...new Set(rules.map(r => r.lhs))];
            // Sort by length descending for greedy match
            nts.sort((a, b) => b.length - a.length);

            const tokens = [];
            let i = 0;
            while (i < form.length) {
                if (form[i] === ' ') {
                    i++;
                    continue;
                }
                let matched = false;
                for (const nt of nts) {
                    if (form.startsWith(nt, i)) {
                        tokens.push(nt);
                        i += nt.length;
                        matched = true;
                        break;
                    }
                }
                if (!matched) {
                    tokens.push(form[i]);
                    i++;
                }
            }
            return tokens;
        }

        function findLeftmostNT(form) {
            const rules = parseRules();
            const nts = new Set(rules.map(r => r.lhs));
            const tokens = tokeniseSentential(form);
            for (const tok of tokens) {
                if (nts.has(tok)) return tok;
            }
            return null;
        }

        function applyRule(form, rule) {
            const rules = parseRules();
            const nts = [...new Set(rules.map(r => r.lhs))].sort((a, b) => b.length - a.length);
            // Replace first occurrence of lhs in form
            const lhs = rule.lhs;
            // Find position considering multi-char tokens
            let i = 0;
            while (i < form.length) {
                if (form[i] === ' ') {
                    i++;
                    continue;
                }
                if (form.startsWith(lhs, i)) {
                    const before = form.slice(0, i).trimEnd();
                    const after = form.slice(i + lhs.length).trimStart();
                    const mid = rule.rhs;
                    return [before, mid, after].filter(s => s !== '').join(' ');
                }
                // skip token
                let skipped = false;
                for (const nt of nts) {
                    if (form.startsWith(nt, i) && nt !== lhs) {
                        i += nt.length;
                        skipped = true;
                        break;
                    }
                }
                if (!skipped) i++;
            }
            return form;
        }

        function derivReset() {
            const start = document.getElementById('deriv-start').value.trim();
            derivHistory = [start];
            derivStepIdx = 0;
            document.getElementById('deriv-stream').innerHTML = '';
            document.getElementById('deriv-rule-picker').style.display = 'none';
            document.getElementById('deriv-log').textContent = 'Ready. Click Step to apply a rule.';
            renderDerivHistory();
        }

        function renderDerivHistory() {
            const stream = document.getElementById('deriv-stream');
            stream.innerHTML = '';
            derivHistory.forEach((form, idx) => {
                const div = document.createElement('div');
                const isLast = idx === derivHistory.length - 1;
                const isFinal = isLast && !findLeftmostNT(form);
                div.className = 'deriv-step' + (isFinal ? ' ds-final' : (isLast ? ' ds-applied' : ''));
                div.innerHTML = `<div class="ds-num">${idx}</div>
            <span style="flex:1;">${escH(form || 'ε')}</span>
            ${idx === 0 ? '<span class="deriv-rule-tag">start</span>' : ''}`;
                stream.appendChild(div);
            });
            stream.scrollTop = stream.scrollHeight;
        }

        function derivStep() {
            const rules = parseRules();
            if (rules.length === 0) {
                document.getElementById('deriv-log').textContent = 'Please enter grammar rules first.';
                return;
            }

            const current = derivHistory[derivHistory.length - 1];
            const nt = findLeftmostNT(current);

            if (!nt) {
                document.getElementById('deriv-log').textContent = `✅ "${current || 'ε'}" is a terminal string — derivation complete!`;
                document.getElementById('deriv-rule-picker').style.display = 'none';
                return;
            }

            const applicable = rules.filter(r => r.lhs === nt);
            if (applicable.length === 0) {
                document.getElementById('deriv-log').textContent = `❌ No rule found for nonterminal "${nt}".`;
                return;
            }

            if (applicable.length === 1) {
                const newForm = applyRule(current, applicable[0]);
                derivHistory.push(newForm);
                renderDerivHistory();
                document.getElementById('deriv-rule-picker').style.display = 'none';
                document.getElementById('deriv-log').textContent = `Applied rule: ${applicable[0].lhs} → ${applicable[0].rhs || 'ε'}`;
            } else {
                // Show rule picker
                document.getElementById('deriv-target-nt').textContent = nt;
                const btns = document.getElementById('deriv-rule-btns');
                btns.innerHTML = '';
                applicable.forEach(rule => {
                    const btn = document.createElement('button');
                    btn.className = 'preset-btn';
                    btn.textContent = `${rule.lhs} → ${rule.rhs || 'ε'}`;
                    btn.onclick = () => {
                        const newForm = applyRule(current, rule);
                        derivHistory.push(newForm);
                        renderDerivHistory();
                        document.getElementById('deriv-rule-picker').style.display = 'none';
                        document.getElementById('deriv-log').textContent = `Applied rule: ${rule.lhs} → ${rule.rhs || 'ε'}`;
                    };
                    btns.appendChild(btn);
                });
                document.getElementById('deriv-rule-picker').style.display = 'block';
                document.getElementById('deriv-log').textContent = `Multiple rules for "${nt}" — choose one above.`;
            }
        }

        function derivAuto() {
            const rules = parseRules();
            const target = document.getElementById('deriv-target').value.trim();
            const start = document.getElementById('deriv-start').value.trim();
            if (!rules.length) {
                document.getElementById('deriv-log').textContent = 'Enter rules first.';
                return;
            }

            // BFS with depth limit
            const MAX_DEPTH = 20;
            const queue = [{
                form: start,
                history: [start]
            }];
            const visited = new Set([start]);

            while (queue.length > 0) {
                const {
                    form,
                    history
                } = queue.shift();
                if (history.length > MAX_DEPTH + 1) continue;

                const nt = findLeftmostNT(form);
                if (!nt) {
                    if (form === target || (form === '' && target === 'ε')) {
                        derivHistory = history;
                        renderDerivHistory();
                        document.getElementById('deriv-rule-picker').style.display = 'none';
                        document.getElementById('deriv-log').textContent = `✅ Derivation found in ${history.length - 1} step(s)!`;
                        return;
                    }
                    continue;
                }

                const applicable = rules.filter(r => r.lhs === nt);
                for (const rule of applicable) {
                    const newForm = applyRule(form, rule);
                    if (!visited.has(newForm)) {
                        visited.add(newForm);
                        queue.push({
                            form: newForm,
                            history: [...history, newForm]
                        });
                    }
                }
            }

            document.getElementById('deriv-log').textContent = `❌ Could not derive "${target}" within ${MAX_DEPTH} steps. Try a shorter target.`;
        }


        // ════════════════════════════════════════════════════════════════
        // TOOL 2 — Chomsky Hierarchy Classifier
        // ════════════════════════════════════════════════════════════════

        const CHOMSKY_EXAMPLES = {
            ex1: 'aSb → aAcBb\nB → aA\na → ABC\nS → aBc',
            ex2: 'Ab → b\nAB → BA\nB → aA\nS → aBc',
            ex3: 'A → aB\nA → a\nS → aAb\nSaB → caB\nAB → BA'
        };

        function chomskyLoadEx(key) {
            document.getElementById('chomsky-input').value = CHOMSKY_EXAMPLES[key];
            classifyChomsky();
        }

        function classifyRule(lhs, rhs) {
            const eps = rhs === '' || rhs === 'ε';

            // Helpers
            const isNT = s => /^[A-Z]/.test(s);
            const isTerm = s => /^[a-z0-9]$/.test(s);
            const lhsTokens = tokeniseString(lhs);
            const rhsTokens = eps ? [] : tokeniseString(rhs);

            // Type 3: A → aB or A → a (single NT on left, right side is terminal optionally followed by single NT)
            if (lhsTokens.length === 1 && isNT(lhsTokens[0])) {
                if (eps) return {
                    type: 2,
                    name: 'Context-Free',
                    reason: 'Single NT on left; ε on right'
                };
                if (rhsTokens.length === 1 && isTerm(rhsTokens[0])) return {
                    type: 3,
                    name: 'Right Linear',
                    reason: 'A → a'
                };
                if (rhsTokens.length === 2 && isTerm(rhsTokens[0]) && isNT(rhsTokens[1])) return {
                    type: 3,
                    name: 'Right Linear',
                    reason: 'A → aB'
                };
                // Any single NT on left → context-free
                return {
                    type: 2,
                    name: 'Context-Free',
                    reason: 'Single NT on left side'
                };
            }

            // Type 1: αAγ → αβγ where β is non-empty (LHS length ≤ RHS length, single NT being rewritten in context)
            if (lhsTokens.length > 1) {
                const ntCount = lhsTokens.filter(isNT).length;
                const lhsLen = lhsTokens.length;
                const rhsLen = rhsTokens.length;
                if (ntCount === 1 && rhsLen >= lhsLen) return {
                    type: 1,
                    name: 'Context-Sensitive',
                    reason: 'αAγ → αβγ (single NT rewritten in context)'
                };
                if (ntCount === 1 && rhsLen < lhsLen) return {
                    type: 0,
                    name: 'Unrestricted',
                    reason: 'αAγ → αβγ but |RHS| < |LHS| (shrinking rule not allowed in CS)'
                };
                // multiple NTs on left
                return {
                    type: 0,
                    name: 'Unrestricted',
                    reason: 'Multiple symbols including NTs on left — unrestricted'
                };
            }

            return {
                type: 0,
                name: 'Unrestricted',
                reason: 'Does not fit any restricted class'
            };
        }

        function tokeniseString(s) {
            // Split into multi-char NT tokens (capital-started words) or single chars
            const tokens = [];
            let i = 0;
            while (i < s.length) {
                if (s[i] === ' ') {
                    i++;
                    continue;
                }
                if (/[A-Z]/.test(s[i])) {
                    let j = i + 1;
                    while (j < s.length && /[a-zA-Z0-9]/.test(s[j]) && /[A-Z]/.test(s[i])) {
                        // keep reading if still looks like same word
                        if (/[a-z0-9]/.test(s[j])) j++;
                        else break;
                    }
                    tokens.push(s.slice(i, j));
                    i = j;
                } else {
                    tokens.push(s[i]);
                    i++;
                }
            }
            return tokens;
        }

        const TYPE_COLOURS = ['badge-type0', 'badge-type1', 'badge-type2', 'badge-type3'];
        const TYPE_LABELS = ['Type 0 — Unrestricted', 'Type 1 — Context-Sensitive', 'Type 2 — Context-Free', 'Type 3 — Right Linear'];

        function classifyChomsky() {
            const lines = document.getElementById('chomsky-input').value.trim().split('\n').filter(l => l.trim());
            const results = document.getElementById('chomsky-results');
            results.innerHTML = '';

            let overallType = 3;

            lines.forEach(line => {
                const sep = line.includes('→') ? '→' : '->';
                if (!line.includes(sep)) return;
                const [lhs, rhs] = line.split(sep).map(s => s.trim());
                const res = classifyRule(lhs, rhs || '');
                overallType = Math.min(overallType, res.type);

                const div = document.createElement('div');
                div.style.cssText = 'display:flex;align-items:center;gap:12px;padding:10px 14px;background:white;border-radius:10px;margin-bottom:8px;border:1px solid rgba(99,102,241,.1);';
                div.innerHTML = `
            <span style="font-family:'JetBrains Mono',monospace;font-size:13px;color:var(--text);flex:1;">${escH(lhs)} → ${escH(rhs || 'ε')}</span>
            <span class="ch-badge ${TYPE_COLOURS[res.type]}" style="white-space:nowrap;">${TYPE_LABELS[res.type]}</span>
            <span style="font-size:11px;color:var(--muted);font-style:italic;max-width:200px;text-align:right;">${res.reason}</span>
        `;
                results.appendChild(div);
            });

            if (lines.length > 1) {
                const summary = document.createElement('div');
                summary.style.cssText = 'margin-top:10px;padding:12px 16px;background:var(--code-bg);border-radius:10px;font-family:\'Syne\',sans-serif;font-weight:700;font-size:13px;color:white;';
                summary.innerHTML = `Overall grammar classification: <span class="ch-badge ${TYPE_COLOURS[overallType]}" style="margin-left:8px;">${TYPE_LABELS[overallType]}</span>
            <div style="font-size:11px;font-weight:400;color:#94A3B8;margin-top:6px;">A grammar's class is determined by its most permissive (least restricted) rule.</div>`;
                results.appendChild(summary);
            }

            document.getElementById('chomsky-output').style.display = 'block';
        }


        // ════════════════════════════════════════════════════════════════
        // TOOL 3 — Parse Tree Builder (Grammar G5)
        // ════════════════════════════════════════════════════════════════

        function ptreeLoad(expr) {
            document.getElementById('ptree-input').value = expr;
            buildPTree();
        }

        // ── Recursive-descent parser for G5 ──
        // Expr → Expr + Term | Expr - Term | Term
        // Term → Term * Factor | Term / Factor | Factor
        // Factor → ( Expr ) | var | const

        function ptreeTokenise(s) {
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

        let ptTokens = [],
            ptPos = 0;

        function ptPeek() {
            return ptPos < ptTokens.length ? ptTokens[ptPos] : null;
        }

        function ptConsume() {
            return ptTokens[ptPos++];
        }

        function parseExpr() {
            let node = {
                label: 'Expr',
                children: []
            };
            let term = parseTerm();
            // Check for left-recursive: Expr → Expr + Term | Expr - Term
            while (ptPeek() === '+' || ptPeek() === '-') {
                const op = ptConsume();
                const right = parseTerm();
                const inner = {
                    label: 'Expr',
                    children: [node, {
                        label: op,
                        children: []
                    }, right]
                };
                node = inner;
            }
            if (node.children.length === 0) {
                node.children.push(term);
            }
            return node;
        }

        function parseTerm() {
            let node = {
                label: 'Term',
                children: []
            };
            let factor = parseFactor();
            while (ptPeek() === '*' || ptPeek() === '/') {
                const op = ptConsume();
                const right = parseFactor();
                const inner = {
                    label: 'Term',
                    children: [node, {
                        label: op,
                        children: []
                    }, right]
                };
                node = inner;
            }
            if (node.children.length === 0) {
                node.children.push(factor);
            }
            return node;
        }

        function parseFactor() {
            const node = {
                label: 'Factor',
                children: []
            };
            const tok = ptPeek();
            if (tok === '(') {
                ptConsume();
                const inner = parseExpr();
                const rp = ptConsume(); // ')'
                node.children.push({
                    label: '(',
                    children: []
                }, inner, {
                    label: ')',
                    children: []
                });
            } else if (tok === 'var' || tok === 'const') {
                ptConsume();
                node.children.push({
                    label: tok,
                    children: []
                });
            } else {
                node.children.push({
                    label: tok || '?',
                    children: []
                });
                ptConsume();
            }
            return node;
        }

        function buildPTree() {
            const input = document.getElementById('ptree-input').value.trim();
            ptTokens = ptreeTokenise(input);
            ptPos = 0;
            let tree;
            try {
                tree = parseExpr();
                if (ptPos < ptTokens.length) throw new Error('Unexpected token: ' + ptTokens[ptPos]);
            } catch (e) {
                document.getElementById('ptree-log').textContent = '❌ Parse error: ' + e.message;
                document.getElementById('ptree-out').style.display = 'block';
                document.getElementById('ptree-svg').innerHTML = '';
                return;
            }

            document.getElementById('ptree-out').style.display = 'block';
            document.getElementById('ptree-log').textContent = `✅ Parsed successfully — ${countNodes(tree)} nodes, depth ${treeDepth(tree)}.`;
            drawPTree(tree);
        }

        function countNodes(n) {
            return 1 + (n.children || []).reduce((s, c) => s + countNodes(c), 0);
        }

        function treeDepth(n) {
            return n.children.length === 0 ? 0 : 1 + Math.max(...n.children.map(treeDepth));
        }

        function drawPTree(root) {
            const svg = document.getElementById('ptree-svg');
            svg.innerHTML = '';
            const W = Math.max(700, svg.parentElement.clientWidth - 32);
            const depth = treeDepth(root);
            const H = (depth + 1) * 56 + 20;
            svg.setAttribute('width', W);
            svg.setAttribute('height', H);

            // Assign x positions via in-order traversal
            let leafIdx = 0;
            const leaves = [];

            function countLeaves(n) {
                if (n.children.length === 0) {
                    leaves.push(n);
                    return;
                }
                n.children.forEach(countLeaves);
            }
            countLeaves(root);
            const leafW = W / (leaves.length + 1);

            function assignX(n, depth) {
                n._depth = depth;
                if (n.children.length === 0) {
                    leafIdx++;
                    n._x = leafIdx * leafW;
                } else {
                    n.children.forEach(c => assignX(c, depth + 1));
                    n._x = n.children.reduce((s, c) => s + c._x, 0) / n.children.length;
                }
                n._y = depth * 56 + 30;
            }
            assignX(root, 0);

            const isTerminal = n => n.children.length === 0;
            const NT_COLOURS = {
                'Expr': '#6366F1',
                'Term': '#10B981',
                'Factor': '#F59E0B'
            };

            function drawEdges(n) {
                (n.children || []).forEach(c => {
                    const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                    line.setAttribute('x1', n._x);
                    line.setAttribute('y1', n._y);
                    line.setAttribute('x2', c._x);
                    line.setAttribute('y2', c._y);
                    line.setAttribute('stroke', '#CBD5E1');
                    line.setAttribute('stroke-width', '1.5');
                    svg.appendChild(line);
                    drawEdges(c);
                });
            }

            function drawNodes(n) {
                const isT = isTerminal(n);
                const colour = isT ? '#1E1B4B' : (NT_COLOURS[n.label] || '#6366F1');
                const r = isT ? 14 : 20;
                if (isT) {
                    const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                    rect.setAttribute('x', n._x - r);
                    rect.setAttribute('y', n._y - r);
                    rect.setAttribute('width', r * 2);
                    rect.setAttribute('height', r * 2);
                    rect.setAttribute('rx', '6');
                    rect.setAttribute('fill', '#EEF2FF');
                    rect.setAttribute('stroke', '#6366F1');
                    rect.setAttribute('stroke-width', '1.5');
                    svg.appendChild(rect);
                } else {
                    const circ = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                    circ.setAttribute('cx', n._x);
                    circ.setAttribute('cy', n._y);
                    circ.setAttribute('r', r);
                    circ.setAttribute('fill', colour + '22');
                    circ.setAttribute('stroke', colour);
                    circ.setAttribute('stroke-width', '2');
                    svg.appendChild(circ);
                }
                const t = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                t.setAttribute('x', n._x);
                t.setAttribute('y', n._y + 4);
                t.setAttribute('text-anchor', 'middle');
                t.setAttribute('fill', colour);
                t.setAttribute('font-size', n.label.length > 4 ? '9' : '11');
                t.setAttribute('font-weight', '700');
                t.setAttribute('font-family', 'JetBrains Mono');
                t.textContent = n.label;
                svg.appendChild(t);
                (n.children || []).forEach(drawNodes);
            }

            drawEdges(root);
            drawNodes(root);
        }


        // ════════════════════════════════════════════════════════════════
        // TOOL 4 — Pushdown Machine Simulator
        // ════════════════════════════════════════════════════════════════

        let pdmSteps = [],
            pdmStepCursor = 0;

        function pdmMachineChanged() {
            const m = document.getElementById('pdm-machine').value;
            const presets = document.getElementById('pdm-presets');
            const desc = document.getElementById('pdm-desc');

            if (m === 'anbn') {
                presets.innerHTML = `
            <button class="preset-btn" onclick="pdmLoadPreset('aabb')">aabb ✓</button>
            <button class="preset-btn" onclick="pdmLoadPreset('aaabbb')">aaabbb ✓</button>
            <button class="preset-btn" onclick="pdmLoadPreset('')">ε (empty) ✓</button>
            <button class="preset-btn" onclick="pdmLoadPreset('aab')">aab ✗</button>
            <button class="preset-btn" onclick="pdmLoadPreset('abb')">abb ✗</button>`;
                document.getElementById('pdm-input').value = 'aabb';
                desc.innerHTML = `<div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:#A5B4FC;margin-bottom:8px;letter-spacing:.05em;">MACHINE — Fig 3.4 (Section 3.0.4)</div>
            <div style="font-family:'JetBrains Mono',monospace;font-size:12px;color:#CDD6F4;line-height:1.9;">
            States: S1 (start), S2 | Stack bottom: ,<br>
            S1 + 'a': push(X), advance<br>
            S1 + 'b': switch to S2, pop, advance<br>
            S1 + end: if stack=, → Accept else Reject<br>
            S2 + 'b': pop, advance<br>
            S2 + end: if stack=, → Accept else Reject<br>
            Language: { aⁿbⁿ | n ≥ 0 }</div>`;
            } else {
                presets.innerHTML = `
            <button class="preset-btn" onclick="pdmLoadPreset('(())')">( ( ) ) ✓</button>
            <button class="preset-btn" onclick="pdmLoadPreset('((()))')">( ( ( ) ) ) ✓</button>
            <button class="preset-btn" onclick="pdmLoadPreset('')">ε ✓</button>
            <button class="preset-btn" onclick="pdmLoadPreset('(()')">( ( ) ✗</button>
            <button class="preset-btn" onclick="pdmLoadPreset('())')">( ) ) ✗</button>`;
                document.getElementById('pdm-input').value = '(())';
                desc.innerHTML = `<div style="font-family:'Syne',sans-serif;font-weight:700;font-size:12px;color:#A5B4FC;margin-bottom:8px;letter-spacing:.05em;">MACHINE — Fig 3.6 (Section 3.0.4)</div>
            <div style="font-family:'JetBrains Mono',monospace;font-size:12px;color:#CDD6F4;line-height:1.9;">
            States: S1 (start) | Stack symbols: X, ,<br>
            '(' → push(X), advance<br>
            ')' + X on top → pop, advance<br>
            ')' + , on top → Reject (too many closing)<br>
            end + , on top → Accept<br>
            end + X on top → Reject (unclosed parens)<br>
            Language: balanced parentheses</div>`;
            }
            pdmReset();
        }

        function pdmLoadPreset(s) {
            document.getElementById('pdm-input').value = s;
            pdmReset();
        }

        function simulateAnBn(input) {
            const steps = [];
            let stack = [','];
            let state = 'S1';
            let i = 0;

            const addStep = (inputSym, action) => {
                steps.push({
                    state,
                    inputSym: inputSym === '' ? 'ε' : inputSym,
                    stack: [...stack],
                    action
                });
            };

            while (true) {
                const sym = i < input.length ? input[i] : 'N';

                if (state === 'S1') {
                    if (sym === 'a') {
                        addStep('a', 'push(X), advance');
                        stack = ['X', ...stack];
                        i++;
                    } else if (sym === 'b') {
                        if (stack[0] !== 'X') {
                            addStep('b', 'Reject (no X to pop)');
                            steps.push({
                                state,
                                inputSym: '-',
                                stack: [...stack],
                                action: '❌ REJECT'
                            });
                            break;
                        }
                        addStep('b', 'pop(X), advance → S2');
                        stack = stack.slice(1);
                        i++;
                        state = 'S2';
                    } else if (sym === 'N') {
                        if (stack[0] === ',') {
                            addStep('N', 'stack=, → Accept');
                            steps.push({
                                state,
                                inputSym: '-',
                                stack: [...stack],
                                action: '✅ ACCEPT'
                            });
                            break;
                        } else {
                            addStep('N', 'Reject (X still on stack)');
                            steps.push({
                                state,
                                inputSym: '-',
                                stack: [...stack],
                                action: '❌ REJECT'
                            });
                            break;
                        }
                    } else {
                        addStep(sym, 'Reject (unexpected symbol)');
                        steps.push({
                            state,
                            inputSym: '-',
                            stack: [...stack],
                            action: '❌ REJECT'
                        });
                        break;
                    }
                } else { // S2
                    if (sym === 'b') {
                        if (stack[0] !== 'X') {
                            addStep('b', 'Reject (no X to pop)');
                            steps.push({
                                state,
                                inputSym: '-',
                                stack: [...stack],
                                action: '❌ REJECT'
                            });
                            break;
                        }
                        addStep('b', 'pop(X), advance');
                        stack = stack.slice(1);
                        i++;
                    } else if (sym === 'N') {
                        if (stack[0] === ',') {
                            addStep('N', 'stack=, → Accept');
                            steps.push({
                                state,
                                inputSym: '-',
                                stack: [...stack],
                                action: '✅ ACCEPT'
                            });
                            break;
                        } else {
                            addStep('N', 'Reject (X still on stack)');
                            steps.push({
                                state,
                                inputSym: '-',
                                stack: [...stack],
                                action: '❌ REJECT'
                            });
                            break;
                        }
                    } else {
                        addStep(sym, 'Reject (unexpected in S2)');
                        steps.push({
                            state,
                            inputSym: '-',
                            stack: [...stack],
                            action: '❌ REJECT'
                        });
                        break;
                    }
                }
                if (steps.length > 200) {
                    steps.push({
                        state,
                        inputSym: '-',
                        stack: [...stack],
                        action: 'HALT (too many steps)'
                    });
                    break;
                }
            }
            return steps;
        }

        function simulateParens(input) {
            const steps = [];
            let stack = [','];
            let state = 'S1';
            let i = 0;

            const addStep = (inputSym, action) => {
                steps.push({
                    state,
                    inputSym: inputSym === '' ? 'ε' : inputSym,
                    stack: [...stack],
                    action
                });
            };

            while (true) {
                const sym = i < input.length ? input[i] : 'N';

                if (sym === '(') {
                    addStep('(', 'push(X), advance');
                    stack = ['X', ...stack];
                    i++;
                } else if (sym === ')') {
                    if (stack[0] === 'X') {
                        addStep(')', 'pop(X), advance');
                        stack = stack.slice(1);
                        i++;
                    } else {
                        addStep(')', 'Reject (stack empty)');
                        steps.push({
                            state,
                            inputSym: '-',
                            stack: [...stack],
                            action: '❌ REJECT'
                        });
                        break;
                    }
                } else if (sym === 'N') {
                    if (stack[0] === ',') {
                        addStep('N', 'stack=, → Accept');
                        steps.push({
                            state,
                            inputSym: '-',
                            stack: [...stack],
                            action: '✅ ACCEPT'
                        });
                        break;
                    } else {
                        addStep('N', 'Reject (unclosed parens)');
                        steps.push({
                            state,
                            inputSym: '-',
                            stack: [...stack],
                            action: '❌ REJECT'
                        });
                        break;
                    }
                } else {
                    addStep(sym, 'Reject (unexpected)');
                    steps.push({
                        state,
                        inputSym: '-',
                        stack: [...stack],
                        action: '❌ REJECT'
                    });
                    break;
                }

                if (steps.length > 200) {
                    steps.push({
                        state,
                        inputSym: '-',
                        stack: [...stack],
                        action: 'HALT'
                    });
                    break;
                }
            }
            return steps;
        }

        function pdmReset() {
            pdmSteps = [];
            pdmStepCursor = 0;
            document.getElementById('pdm-trace-body').innerHTML = '';
            document.getElementById('pdm-trace-wrap').style.display = 'none';
            document.getElementById('pdm-verdict').style.display = 'none';
            document.getElementById('pdm-log').textContent = 'Ready. Click Run or Step.';
        }

        function pdmRun() {
            const machine = document.getElementById('pdm-machine').value;
            const input = document.getElementById('pdm-input').value;
            pdmSteps = machine === 'anbn' ? simulateAnBn(input) : simulateParens(input);
            pdmStepCursor = pdmSteps.length;
            renderPdmTrace(0, pdmSteps.length);
        }

        function pdmStep() {
            if (pdmSteps.length === 0) {
                // Generate steps first
                const machine = document.getElementById('pdm-machine').value;
                const input = document.getElementById('pdm-input').value;
                pdmSteps = machine === 'anbn' ? simulateAnBn(input) : simulateParens(input);
                pdmStepCursor = 0;
                document.getElementById('pdm-trace-body').innerHTML = '';
                document.getElementById('pdm-trace-wrap').style.display = 'block';
                document.getElementById('pdm-verdict').style.display = 'none';
            }
            if (pdmStepCursor < pdmSteps.length) {
                renderPdmTrace(pdmStepCursor, pdmStepCursor + 1);
                pdmStepCursor++;
            }
        }

        function renderPdmTrace(from, to) {
            const tbody = document.getElementById('pdm-trace-body');
            document.getElementById('pdm-trace-wrap').style.display = 'block';

            for (let idx = from; idx < to; idx++) {
                const s = pdmSteps[idx];
                const isVerdict = s.action.includes('ACCEPT') || s.action.includes('REJECT') || s.action.includes('HALT');
                const tr = document.createElement('tr');
                tr.style.cssText = `border-bottom:1px solid #EEF2FF;${isVerdict ? 'font-weight:700;' : ''}`;
                const actionColour = s.action.includes('ACCEPT') ? '#10B981' : s.action.includes('REJECT') ? '#EF4444' : 'var(--text)';
                tr.innerHTML = `
            <td style="padding:7px 12px;font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--muted);">${idx + 1}</td>
            <td style="padding:7px 12px;font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--purple);">${escH(s.state || '')}</td>
            <td style="padding:7px 12px;font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--text);">${escH(s.inputSym)}</td>
            <td style="padding:7px 12px;font-family:'JetBrains Mono',monospace;font-size:12px;color:#6366F1;">${s.stack.join(' ')}</td>
            <td style="padding:7px 12px;font-size:12px;color:${actionColour};">${escH(s.action)}</td>
        `;
                tbody.appendChild(tr);
            }

            // Show verdict
            const last = pdmSteps[pdmSteps.length - 1];
            if (to >= pdmSteps.length && last) {
                const v = document.getElementById('pdm-verdict');
                const accepted = last.action.includes('ACCEPT');
                v.style.cssText = `margin-top:12px;padding:12px 18px;border-radius:10px;font-family:'Syne',sans-serif;font-weight:700;font-size:14px;display:block;background:${accepted ? '#F0FDF4' : '#FFF1F2'};border:1.5px solid ${accepted ? '#10B981' : '#EF4444'};color:${accepted ? '#065F46' : '#9F1239'};`;
                const input = document.getElementById('pdm-input').value || 'ε';
                v.textContent = accepted ? `✅ "${input}" is ACCEPTED` : `❌ "${input}" is REJECTED`;
                document.getElementById('pdm-log').textContent = `Simulation complete — ${pdmSteps.length} steps.`;
            }
        }


        // ── Shared helpers ──────────────────────────────────────────────
        function escH(s) {
            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        // Init
        derivReset();
    </script>
</body>

</html>