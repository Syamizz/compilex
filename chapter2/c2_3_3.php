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
$page = 14;
$nextPage = "c2_4_0.php";

if (isset($_GET['complete']) && $_GET['complete'] == '14') {
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
    <title>Chapter 2.3 – CompileX</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/c2/c2_3.css">
</head>

<body>

    <?php include 'navbar_c2.php'; ?>

    <div class="note-container">

        <nav class="toc">
            <div class="toc-title">📖 Contents</div>
            <ul>
                <li><a href="#hash" class="toc-link">2.3.3 Hash Table</a></li>
                <li><a href="#hash-vis" class="toc-link sub">↳ Interactive Hash Table</a></li>
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
                <div class="chapter-tag">📘 Chapter 2 · Section 2.3</div>
                <h1>Lexical Tables</h1>
                <div class="metadata">
                    <span>⏱ 16 min read</span>
                    <span>🎯 Intermediate</span>
                    <span>🗃️ Data Structures</span>
                </div>
            </header>



            <!-- ══════════════════════════════════════════════ -->
            <!-- 2.3.3 Hash Table                              -->
            <!-- ══════════════════════════════════════════════ -->
            <section id="hash">
                <h2><span class="sec-icon">🔑</span> 2.3.3 Hash Table</h2>
                <p>
                    A hash table can implement a symbol table, constant table, line number table, etc.
                    The method used here is an <strong>array of linked lists</strong>:
                </p>
                <ol>
                    <li>Start with an array of <code>HASHMAX</code> null pointers — each will become the head of a linked list</li>
                    <li>To store a word, apply the <strong>hash function</strong> to get an array index</li>
                    <li>Search that list sequentially — if the word is already there, stop; otherwise <strong>append it</strong></li>
                </ol>

                <h3>The Hash Function</h3>
                <p>
                    A good hash function uses some arithmetic combination of the word's characters, divided by the
                    table size, taking the <strong>remainder</strong>. The textbook's example:
                </p>
                <div style="background:var(--code-bg);border-radius:var(--radius);padding:16px 22px;margin:16px 0;font-family:'JetBrains Mono',monospace;font-size:14px;color:#CDD6F4;">
                    hash(word) = (length(word) + ascii(word[0])) % HASHMAX
                </div>

                <table class="compare-table">
                    <thead>
                        <tr>
                            <th>Word</th>
                            <th>len</th>
                            <th>ascii(first)</th>
                            <th>len + ascii</th>
                            <th>% 6</th>
                            <th>Bucket</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>frog</code></td>
                            <td>4</td>
                            <td>102 ('f')</td>
                            <td>106</td>
                            <td>106 % 6</td>
                            <td><strong>4</strong></td>
                        </tr>
                        <tr>
                            <td><code>tree</code></td>
                            <td>4</td>
                            <td>116 ('t')</td>
                            <td>120</td>
                            <td>120 % 6</td>
                            <td><strong>0</strong></td>
                        </tr>
                        <tr>
                            <td><code>hill</code></td>
                            <td>4</td>
                            <td>104 ('h')</td>
                            <td>108</td>
                            <td>108 % 6</td>
                            <td><strong>0</strong></td>
                        </tr>
                        <tr>
                            <td><code>bird</code></td>
                            <td>4</td>
                            <td>98 ('b')</td>
                            <td>102</td>
                            <td>102 % 6</td>
                            <td><strong>0</strong></td>
                        </tr>
                        <tr>
                            <td><code>bat</code></td>
                            <td>3</td>
                            <td>98 ('b')</td>
                            <td>101</td>
                            <td>101 % 6</td>
                            <td><strong>5</strong></td>
                        </tr>
                        <tr>
                            <td><code>cat</code></td>
                            <td>3</td>
                            <td>99 ('c')</td>
                            <td>102</td>
                            <td>102 % 6</td>
                            <td><strong>0</strong></td>
                        </tr>
                    </tbody>
                </table>

                <div class="note-box pro">
                    <span class="box-icon">🧩</span>
                    <div>
                        <strong>Structure is insertion-order independent.</strong> Unlike a BST, the hash table's
                        bucket structure does not change based on the order words were inserted — only the sequence
                        within a particular chain can vary. This is a major advantage over BSTs.
                    </div>
                </div>

                <div class="note-box tip">
                    <span class="box-icon">💡</span>
                    <div>
                        <strong>Choosing a good hash function</strong> is critical. A poor function may put many
                        words in the same bucket (many collisions), degrading to sequential search within that bucket.
                        The goal is to distribute words as <em>evenly as possible</em> across all buckets.
                    </div>
                </div>
            </section>

            <!-- Interactive Hash Table -->
            <section id="hash-vis">
                <h2><span class="sec-icon">▶️</span> Interactive Hash Table Visualiser</h2>
                <p>Type a word and click <strong>Insert</strong> to add it. The hash function
                    <code>(len + ascii(first)) % HASHMAX</code> is applied live, and the word is placed in
                    the correct bucket. Use the preset to reproduce Figure 2.9 from the textbook.
                </p>

                <div class="hash-wrap">
                    <div class="hash-header">
                        <h4>🔑 Hash Table Visualiser</h4>
                        <span style="font-size:12px;color:rgba(255,255,255,.7);" id="hash-stats">HASHMAX = 6 | 0 words</span>
                    </div>
                    <div class="hash-body">
                        <div class="hash-controls">
                            <input class="hash-input" id="hash-word-input" type="text" placeholder="Enter word…" maxlength="14" onkeydown="if(event.key==='Enter')hashInsert()">
                            <input class="hash-input" id="hash-max-input" type="number" value="6" min="2" max="20" style="width:80px;" onchange="hashResize()">
                            <label style="font-size:13px;color:var(--muted);">HASHMAX</label>
                            <button class="hash-btn" onclick="hashInsert()">Insert</button>
                            <button class="hash-btn sec" onclick="hashReset()">Clear</button>
                        </div>
                        <div class="preset-btns" style="margin-bottom:14px;">
                            <span style="font-size:12px;color:var(--muted);margin-right:4px;">Presets:</span>
                            <button class="preset-btn" onclick="hashPreset(['frog','tree','hill','bird','bat','cat'],6)">Fig 2.9 (HASHMAX=6)</button>
                            <button class="preset-btn" onclick="hashPreset(['bog','cab','bc','cb','h33','h22','cater'],6)">Ex 2.3.5</button>
                        </div>

                        <div class="hash-table-vis" id="hash-table-vis"></div>

                        <div class="hash-formula" id="hash-formula-display">
                            hash(<span style="color:#A6E3A1;">word</span>) = (len + ascii(first)) % HASHMAX
                        </div>
                        <div class="hash-log" id="hash-log">Insert a word to see the hash function in action.</div>
                    </div>
                </div>
            </section>



            <div class="chapter-nav">
                <a href="c2_3_2.php" class="nav-btn">
                    <span>←</span>
                    <div><span class="btn-sub">Previous page</span>2.3.2 Binary Search Tree</div>
                </a>
                <a href="?complete=14" class="nav-btn next">
                    <div style="text-align:right"><span class="btn-sub">Next page</span>2.4 Lexical Analysis with SableCC</div>
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

        // ════════════════════════════════════════════════════════
        // Hash Table Visualiser
        // ════════════════════════════════════════════════════════
        let hashMax = 6;
        let hashData = []; // array of arrays
        let hashWordCount = 0;

        function hashFunc(word) {
            if (!word) return 0;
            return (word.length + word.charCodeAt(0)) % hashMax;
        }

        function hashInit() {
            hashData = Array.from({
                length: hashMax
            }, () => []);
            hashWordCount = 0;
            renderHashTable();
        }

        function hashResize() {
            const v = parseInt(document.getElementById('hash-max-input').value);
            if (v >= 2 && v <= 20) {
                hashMax = v;
                hashInit();
            }
        }

        function hashReset() {
            hashInit();
            document.getElementById('hash-log').textContent = 'Insert a word to see the hash function in action.';
            document.getElementById('hash-stats').textContent = `HASHMAX = ${hashMax} | 0 words`;
        }

        function hashInsert() {
            const w = document.getElementById('hash-word-input').value.trim().toLowerCase();
            if (!w) return;
            document.getElementById('hash-word-input').value = '';
            const idx = hashFunc(w);
            const bucket = hashData[idx];
            const log = document.getElementById('hash-log');
            const asc = w.charCodeAt(0);
            const formula = `hash("${w}") = (${w.length} + ${asc}) % ${hashMax} = ${w.length + asc} % ${hashMax} = ${idx}`;
            if (bucket.includes(w)) {
                log.textContent = `"${w}" already in bucket ${idx}. ${formula}`;
                return;
            }
            bucket.push(w);
            hashWordCount++;
            document.getElementById('hash-stats').textContent = `HASHMAX = ${hashMax} | ${hashWordCount} words`;
            log.textContent = `${formula}  →  Added to bucket ${idx}`;
            document.getElementById('hash-formula-display').innerHTML =
                `hash(<span style="color:#A6E3A1;">"${w}"</span>) = (<span style="color:#FAB387;">${w.length}</span> + <span style="color:#FAB387;">${asc}</span>) % ${hashMax} = <span style="color:#6EE7B7;font-weight:700;">${idx}</span>`;
            renderHashTable(idx, w);
        }

        function hashPreset(words, max) {
            hashMax = max;
            document.getElementById('hash-max-input').value = max;
            hashInit();
            words.forEach(w => {
                const idx = hashFunc(w);
                if (!hashData[idx].includes(w)) {
                    hashData[idx].push(w);
                    hashWordCount++;
                }
            });
            document.getElementById('hash-stats').textContent = `HASHMAX = ${hashMax} | ${hashWordCount} words`;
            document.getElementById('hash-log').textContent = 'Preset loaded: ' + words.join(', ');
            renderHashTable();
        }

        function renderHashTable(newIdx = -1, newWord = '') {
            const vis = document.getElementById('hash-table-vis');
            vis.innerHTML = '';
            for (let i = 0; i < hashMax; i++) {
                const slot = document.createElement('div');
                slot.className = 'hash-slot';
                const idx = document.createElement('div');
                idx.className = 'hash-idx';
                idx.textContent = i;
                slot.appendChild(idx);
                const chain = document.createElement('div');
                chain.className = 'hash-chain';
                if (hashData[i].length === 0) {
                    const nil = document.createElement('span');
                    nil.className = 'hash-null';
                    nil.textContent = 'null';
                    chain.appendChild(nil);
                } else {
                    hashData[i].forEach((w, j) => {
                        if (j > 0) {
                            const arr = document.createElement('span');
                            arr.className = 'hash-arrow';
                            arr.textContent = '→';
                            chain.appendChild(arr);
                        }
                        const node = document.createElement('div');
                        node.className = 'hash-node' + (i === newIdx && w === newWord ? ' new' : '');
                        node.textContent = w;
                        chain.appendChild(node);
                    });
                }
                slot.appendChild(chain);
                vis.appendChild(slot);
            }
        }

        // init
        hashInit();
    </script>

</body>

</html>