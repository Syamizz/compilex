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
$page = 8;
$nextPage = "c2_2_1.php";

if (isset($_GET['complete']) && $_GET['complete'] == '8') {
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
        <li><a href="#overview" class="toc-link">2.2 Overview</a></li>
        <li><a href="#array" class="toc-link">2.2.0 Array Implementation</a></li>
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
      <!-- 2.2 Overview                                   -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="overview">
        <h2><span class="sec-icon">⚙️</span> 2.2 Overview</h2>
        <p>
          Finite state machines can be used to <strong>simplify lexical analysis</strong>. This section
          covers:
        </p>
        <ol>
          <li>How an FSM is implemented as a <strong>2D array</strong> in code</li>
          <li>FSM examples for real lexical analysis problems: <strong>identifiers</strong>, <strong>numeric constants</strong>, and <strong>keywords</strong></li>
          <li>How to add <strong>actions</strong> (function calls) to FSM transitions to build symbol tables, convert numeric constants, and emit tokens</li>
        </ol>
        <div class="note-box pro">
          <span class="box-icon">🧩</span>
          <div>
            <strong>Key idea:</strong> A finite state machine is not just a language recogniser — it can <em>do work</em> as it transitions. By attaching a function to each arc, every character read can trigger processing (e.g. accumulate a number, store an identifier, emit a token).
          </div>
        </div>
      </section>

      <!-- ══════════════════════════════════════════════ -->
      <!-- 2.2.0 Array Implementation                    -->
      <!-- ══════════════════════════════════════════════ -->
      <section id="array">
        <h2><span class="sec-icon">🗃️</span> 2.2.0 Array Implementation of an FSM</h2>
        <p>
          An FSM can be implemented very simply as a <strong>2D integer array</strong>: one row per state,
          one column per possible input symbol. Each cell holds the <em>next state</em> for that
          (current state, input symbol) pair — identical in structure to the table form of an FSM.
        </p>
        <p>
          Input characters may need to be <strong>coded as integers</strong> to serve as array indices,
          depending on the implementation language.
        </p>

        <div class="code-wrap">
          <div class="code-header">
            <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
            <span class="code-lang">Java — FSM array simulation</span>
            <button class="copy-btn" onclick="copyBlock('fsm-array-code',this)">Copy</button>
          </div>
          <pre><code id="fsm-array-code"><span class="typ">boolean</span>[] accept = <span class="kw">new</span> <span class="typ">boolean</span>[STATES];
<span class="typ">int</span>[][]   fsm    = <span class="kw">new</span> <span class="typ">int</span>[STATES][INPUTS]; <span class="cm">// state table</span>
<span class="cm">// initialize table here ...</span>

<span class="typ">int</span> inp   = <span class="num">0</span>; <span class="cm">// input symbol (0 .. INPUTS)</span>
<span class="typ">int</span> state = <span class="num">0</span>; <span class="cm">// starting state</span>

<span class="kw">try</span> {
    inp = System.in.read() - <span class="str">'0'</span>; <span class="cm">// read char, convert to int</span>
    <span class="kw">while</span> (inp >= <span class="num">0</span> &amp;&amp; inp &lt; INPUTS) {
        state = fsm[state][inp];           <span class="cm">// next state</span>
        inp   = System.in.read() - <span class="str">'0'</span>; <span class="cm">// next input</span>
    }
} <span class="kw">catch</span> (IOException ioe) {
    System.out.println(<span class="str">"IO error "</span> + ioe);
}

<span class="kw">if</span> (accept[state])
    System.out.println(<span class="str">"Accepted"</span>);
<span class="kw">else</span>
    System.out.println(<span class="str">"Rejected"</span>);</code></pre>
        </div>

        <div class="note-box tip">
          <span class="box-icon">💡</span>
          <div>
            <strong>How it works:</strong> The loop reads one input symbol at a time and looks up
            <code>fsm[state][inp]</code> to get the next state. When input ends, check
            <code>accept[state]</code> — if <code>true</code>, the string is accepted; otherwise rejected.
            No backtracking — each symbol is read exactly once.
          </div>
        </div>
      </section>



   


      <div class="chapter-nav">
        <a href="c2_1_3.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Previous page</span>2.1.3 Numeric Constants</div>
        </a>
        <a href="?complete=8" class="nav-btn next">
          <div style="text-align:right"><span class="btn-sub">Next page</span>2.2.1 FSM Examples for Lexical Analysis</div>
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
        /* ready */ }
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