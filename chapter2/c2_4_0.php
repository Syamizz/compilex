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
$page = 15;
$nextPage = "c2_4_1.php";

if (isset($_GET['complete']) && $_GET['complete'] == '15') {
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
    <title>Chapter 2.4 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c2/c2_4.css">
</head>

<body>

    <?php include 'navbar_c2.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#overview" class="toc-link">2.4 Overview</a></li>
                <li><a href="#tools" class="toc-link">Tools: lex, yacc, SableCC</a></li>
                <li><a href="#sablecc-adv" class="toc-link">Why SableCC?</a></li>
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
                <div class="chapter-tag">📘 Chapter 2 · Section 2.4</div>
                <h1>Lexical Analysis with SableCC</h1>
                <div class="metadata">
                    <span>⏱ 20 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>🛠️ Compiler Tools</span>
                </div>
            </header>

            <!-- ══════════════════════════════════════════════ -->
            <!-- 2.4 Overview                                   -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="overview">
                <h2><span class="sec-icon">🛠️</span> 2.4 Overview</h2>
                <p>
                    Rather than writing a lexical scanner by hand, we can use <strong>compiler-generating tools</strong>
                    that automatically produce a scanner from a set of formal specifications. These tools originated
                    in the Unix programming environment.
                </p>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Tools                                          -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="tools">
                <h2><span class="sec-icon">⚙️</span> Tools — lex, yacc, and Java Alternatives</h2>

                <div class="tool-grid">
                    <div class="tool-card">
                        <div class="tc-name">lex</div>
                        <div class="tc-sub">Unix · C language</div>
                        <div class="tc-desc">Generates a lexical analyser (scanner) from a set of regular-expression-based specifications. Designed for use with C.</div>
                    </div>
                    <div class="tool-card">
                        <div class="tc-name">yacc</div>
                        <div class="tc-sub">Yet Another Compiler-Compiler · Unix · C</div>
                        <div class="tc-desc">Generates a parser (syntax analyser) from a grammar specification. Designed for use with C, typically paired with lex.</div>
                    </div>
                    <div class="tool-card">
                        <div class="tc-name">JavaCC</div>
                        <div class="tc-sub">Sun Microsystems · Java</div>
                        <div class="tc-desc">Probably the most widely used Java replacement for lex+yacc. Combines scanner and parser generation. Limited to LL(1) grammars.</div>
                    </div>
                    <div class="tool-card">
                        <div class="tc-name">SableCC ⭐</div>
                        <div class="tc-sub">McGill University · Java (free)</div>
                        <div class="tc-desc">Object-oriented, generates modular code, produces syntax trees, supports a wider class of grammars than JavaCC. Used throughout this course.</div>
                    </div>
                </div>

                <p>Other Java tools include <strong>JLex</strong> (lexical analysis), <strong>CUP</strong> (Constructor of Useful Parsers), and <strong>ANTLR</strong> (Another Tool for Language Recognition).</p>
            </section>

            <!-- ══════════════════════════════════════════════ -->
            <!-- Why SableCC                                   -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="sablecc-adv">
                <h2><span class="sec-icon">⭐</span> Why SableCC over JavaCC?</h2>
                <ul>
                    <li>Designed to <strong>make good use of Java</strong> — object-oriented, uses class inheritance extensively</li>
                    <li>Compilation errors are <strong>easier to fix</strong></li>
                    <li>Generates <strong>modular software</strong> — each class in a separate file</li>
                    <li>Generates <strong>syntax trees</strong>, from which atoms or code can be generated directly</li>
                    <li>Accommodates a <strong>wider class of languages</strong> — JavaCC only supports LL(1) grammars</li>
                </ul>
                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>
                        Unlike lex/yacc which can be used separately, <strong>SableCC combines</strong> lexical and syntax
                        analysis into a single program. Since syntax analysis is covered later, a SableCC template is
                        provided so students can run SableCC for lexical analysis alone.
                    </div>
                </div>
            </section>


            <div class="chapter-nav">
                <a href="c2_3_3.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>2.3.3 Hash Tables</div>
                </a>
                <a href="?complete=15" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>2.4.1 Grammar File Structure</div>
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

        // ════════════════════════════════════════════════════════
        // DRAG AND DROP ENGINE
        // ════════════════════════════════════════════════════════
        let draggingId = null;
        let draggingFrom = null;

        function dragStart(e) {
            draggingId = e.target.id;
            draggingFrom = e.target.parentElement.id;
            e.target.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', e.target.id);
        }

        function allowDrop(e) {
            e.preventDefault();
            e.currentTarget.classList.add('drag-over');
        }

        document.addEventListener('dragleave', (e) => {
            if (e.target.classList) {
                e.target.classList.remove('drag-over');
            }
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

            // If target is a zone and already has a chip, send existing chip back to its bank
            const targetIsZone = target.classList.contains('dnd-zone');
            if (targetIsZone && target.children.length > 0) {
                const existing = target.firstElementChild;
                const bank = findBank(targetId);
                if (bank) document.getElementById(bank).appendChild(existing);
            }

            // If target is a zone and chip is coming from another zone, clear source zone
            if (targetIsZone && draggingFrom !== target.id && !draggingFrom.startsWith('bank')) {
                // source is a zone — already removed by drag
            }

            target.appendChild(chip);
            chip.classList.remove('dragging');

            // If going to zone, make chip clickable to return to bank
            if (targetIsZone) {
                chip.onclick = () => {
                    const bank = findBank(targetId);
                    if (bank) {
                        document.getElementById(bank).appendChild(chip);
                        chip.onclick = null;
                    }
                };
            } else {
                chip.onclick = null;
            }
        }

        function findBank(zoneId) {
            // zone id format: zone{N}-{x} → bank is bank{N}
            const m = zoneId.match(/zone(\d+)/);
            return m ? 'bank' + m[1] : null;
        }

        function checkDnD(quizId, zoneIds) {
            let correct = 0;
            const total = zoneIds.length;
            zoneIds.forEach(zid => {
                const zone = document.getElementById(zid);
                const expected = zone.dataset.answer;
                const chip = zone.firstElementChild;
                zone.classList.remove('correct-zone', 'wrong-zone');
                if (chip && chip.id === expected) {
                    zone.classList.add('correct-zone');
                    correct++;
                } else {
                    zone.classList.add('wrong-zone');
                }
            });
            const fb = document.getElementById('fb-' + quizId);
            fb.classList.remove('show', 'ok', 'bad', 'partial');
            if (correct === total) {
                fb.className = 'dnd-feedback show ok';
                fb.innerHTML = '✅ <strong>Perfect!</strong> All ' + total + ' matched correctly.';
            } else if (correct > 0) {
                fb.className = 'dnd-feedback show partial';
                fb.innerHTML = '⚠️ <strong>' + correct + ' of ' + total + '</strong> correct. Green = right, red = wrong. Try again!';
            } else {
                fb.className = 'dnd-feedback show bad';
                fb.innerHTML = '❌ None correct yet. Review the section and try again.';
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
                    if (o.getAttribute('onclick')?.includes('true')) {
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