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
$page = 1;
$nextPage = "c2_0_2.php";

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
        <li><a href="#intro" class="toc-link">Chapter 2 Intro</a></li>
        <li><a href="#lang-elem" class="toc-link">2.0.1 Language Elements</a></li>
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


 
   
      
 
      <!-- Chapter nav -->
      <div class="chapter-nav">
        <a href="../home.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Back to</span>Home</div>
        </a>
        <a href="?complete=1" class="nav-btn next">
          <div style="text-align:right"><span class="btn-sub">Next page</span>2.0.2 Finite State Machines</div>
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