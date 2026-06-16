<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chapter 1 – CompileX</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../css/c1_1.css">
</head>

<body>

  <?php include 'navbar_c1.php'; ?>

  <div class="note-container">

    <!-- ── Sidebar TOC ─────────────────────────────────── -->
    <nav class="toc">
      <div class="toc-title">📖 Contents</div>
      <ul>
        <li><a href="#definition" class="toc-link">1.1 What is a Compiler?</a></li>
        <li><a href="#source-object" class="toc-link">1.1.1 Source & Object Programs</a></li>
        <li><a href="#vs-interpreter" class="toc-link">1.1.2 Compiler vs Interpreter</a></li>
        <li><a href="#errors" class="toc-link">1.1.3 Compile-time vs Run-time Errors</a></li>
        <li><a href="#sample-a" class="toc-link">1.1.4 Sample Problem (a) — while loop</a></li>
        <li><a href="#sample-b" class="toc-link">1.1.5 Sample Problem (b) — for loop</a></li>
        <li><a href="#bigc" class="toc-link">1.1.6 Big-C Notation</a></li>
        <li><a href="#sample-c" class="toc-link">1.1.7 Sample Problem (c) — Big-C</a></li>
        <li><a href="#walkthrough" class="toc-link">1.1.8 Step-by-Step Walkthrough</a></li>
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
        <div class="chapter-tag">📘 Chapter 1</div>
        <h1>Introduction to Compilers</h1>
        <div class="metadata">
          <span>⏱ 15 min read</span>
          <span>🎯 Beginner</span>
          <span>⚙️ Compiler Theory</span>
        </div>
      </header>

      <!-- ─────────────────────────────────────────────── -->
      <!-- 1.1 What is a Compiler?                        -->
      <!-- ─────────────────────────────────────────────── -->
      <section id="definition">
        <h2><span class="sec-icon">🔍</span> What is a Compiler?</h2>
        <p>
          Recall from your study of assembly language that the CPU can only execute very simple, primitive instructions —
          adding two numbers in memory, moving data between registers, or branching to another address. There is certainly
          <em>no single instruction</em> capable of evaluating a complex expression such as
          <code>((x−x₀)² + (x−x₁)²)^½</code>, and there is no way to execute a multi-branch statement in one step.
        </p>
        <p>
          These capabilities are implemented by a software translator known as a <strong>compiler</strong>. The function
          of the compiler is to accept high-level statements and translate them into sequences of machine language
          operations which, when loaded into memory and executed, carry out the intended computation.
        </p>

        <div class="note-box warn">
          <span class="box-icon">⚠️</span>
          <div>
            <strong>Important:</strong> When processing a statement such as <code>x = x * 9;</code>, the compiler does
            <em>not</em> perform the multiplication. It generates, as output, a sequence of instructions
            <em>including</em> a "multiply" instruction. The computation happens only at run time.
          </div>
        </div>

        <p>
          Languages that permit complex operations are called <strong>high-level languages</strong>, or
          <strong>programming languages</strong>.
        </p>

        <div class="note-box tip">
          <span class="box-icon">💡</span>
          <div>
            <strong>User Interface Perspective:</strong> A programming language is itself a sophisticated
            <em>user interface</em> — a mechanism through which a programmer communicates with the computer.
            Even if you never design a compiler, understanding how compilers work makes you a significantly
            better programmer.
          </div>
        </div>
      </section>

      <!-- ─────────────────────────────────────────────── -->
      <!-- 1.1.1 Source & Object Programs                 -->
      <!-- ─────────────────────────────────────────────── -->
      <section id="source-object">
        <h2><span class="sec-icon">📄</span> Source &amp; Object Programs</h2>
        <p>
          A compiler accepts as input a program written in a particular high-level language and produces as output an
          equivalent program in machine language for a specific <strong>target machine</strong>. Two programs are
          considered <em>equivalent</em> if they always produce the same output when given the same input.
        </p>

        <table class="compare-table">
          <thead>
            <tr>
              <th>Term</th>
              <th>Definition</th>
              <th>Example</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Source Program</td>
              <td>The input to the compiler — written in the source language</td>
              <td>Java, C++, Ada</td>
            </tr>
            <tr>
              <td>Object Program</td>
              <td>The output of the compiler — written in the object language</td>
              <td>x86 machine code, MIPS assembly</td>
            </tr>
            <tr>
              <td>Target Machine</td>
              <td>The specific machine for which object code is generated</td>
              <td>Apple Macintosh, SUN, PC</td>
            </tr>
          </tbody>
        </table>

        <p>Some concrete examples of compilers:</p>
        <ul style="margin-left:20px; color:var(--muted); margin-bottom:14px;">
          <li>A Java compiler for the Apple Macintosh</li>
          <li>A COBOL compiler for the SUN</li>
          <li>A C++ compiler for the Apple Macintosh</li>
        </ul>

        <h3>Example: Translating <code>A = B + C * D;</code></h3>
        <p>
          The compiler must be smart enough to know that multiplication happens before addition, even though the
          addition appears first when scanning left to right. It also checks for proper <strong>syntax</strong>
          and issues helpful error messages when errors are found.
        </p>

        <div class="code-wrap">
          <div class="code-header">
            <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
            <span class="code-lang">Assembly — Compiled Output for A = B + C * D</span>
            <button class="copy-btn" onclick="copyBlock('abcd-block', this)">Copy</button>
          </div>
          <pre><code id="abcd-block"><span class="op">LOD</span> <span class="reg">R1</span>,<span class="num">C</span>       <span class="cm">// Load the value of C into reg 1</span>
<span class="op">MUL</span> <span class="reg">R1</span>,<span class="num">D</span>       <span class="cm">// Multiply the value of D by reg 1</span>
<span class="op">STO</span> <span class="reg">R1</span>,TEMP1   <span class="cm">// Store the result in TEMP1</span>
<span class="op">LOD</span> <span class="reg">R1</span>,<span class="num">B</span>       <span class="cm">// Load the value of B into reg 1</span>
<span class="op">ADD</span> <span class="reg">R1</span>,TEMP1   <span class="cm">// Add value of TEMP1 to register 1</span>
<span class="op">STO</span> <span class="reg">R1</span>,TEMP2   <span class="cm">// Store the result in TEMP2</span>
<span class="op">MOV</span> <span class="num">A</span>,TEMP2    <span class="cm">// Move TEMP2 to A, the final result</span></code></pre>
        </div>

        <div class="note-box pro">
          <span class="box-icon">🧩</span>
          <div>
            <strong>Semantic Equivalence over Optimality:</strong> In designing a compiler, the primary concern is that
            the object program be <em>semantically equivalent</em> to the source program (produces the same output for
            any given input). Object program efficiency is important, but not as important as correct code generation.
            Many compilers will not generate perfectly optimal code.
          </div>
        </div>

        <h3>Advantages of High-Level Languages</h3>
        <table class="compare-table">
          <thead>
            <tr>
              <th>Advantage</th>
              <th>Explanation</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Readability &amp; maintainability</td>
              <td>Machine/assembly language is difficult to work with and maintain</td>
            </tr>
            <tr>
              <td>Portability</td>
              <td>A high-level program can run on any machine that has a compiler for that language</td>
            </tr>
            <tr>
              <td>No retraining needed</td>
              <td>Programmers don't need to relearn every time a new CPU is introduced</td>
            </tr>
            <tr>
              <td>Abstraction</td>
              <td>High-level languages support data abstraction (data structures) and program abstraction (procedures/functions)</td>
            </tr>
          </tbody>
        </table>

        <h3>Disadvantages of High-Level Languages</h3>
        <table class="compare-table">
          <thead>
            <tr>
              <th>Disadvantage</th>
              <th>Explanation</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Limited control</td>
              <td>Programmer doesn't have complete control of machine resources (registers, interrupts, I/O buffers)</td>
            </tr>
            <tr>
              <td>Possible inefficiency</td>
              <td>The compiler may generate inefficient machine language programs</td>
            </tr>
            <tr>
              <td>Extra software needed</td>
              <td>The compiler itself must be available on the target platform</td>
            </tr>
          </tbody>
        </table>
        <p>As compiler development and hardware have improved, these disadvantages have become less problematic. Consequently, most programming today is done with high-level languages.</p>
      </section>

      <!-- ─────────────────────────────────────────────── -->
      <!-- 1.1.2 Compiler vs Interpreter                  -->
      <!-- ─────────────────────────────────────────────── -->
      <section id="vs-interpreter">
        <h2><span class="sec-icon">⚖️</span> Compiler vs Interpreter</h2>
        <p>
          An <strong>interpreter</strong> serves a purpose very similar to a compiler. The input is also a program
          written in a high-level language — but rather than generating a machine language program, the interpreter
          <em>directly carries out</em> the computations specified in the source program.
        </p>

        <div class="note-box key">
          <span class="box-icon">🔑</span>
          <div>
            <strong>Core distinction:</strong> The <em>output of a compiler</em> is a program (object code).
            The <em>output of an interpreter</em> is the source program's actual output (the computed result).
          </div>
        </div>

        <table class="compare-table">
          <thead>
            <tr>
              <th>Aspect</th>
              <th>Compiler</th>
              <th>Interpreter</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Output</td>
              <td>Object program (machine code)</td>
              <td>Program's computed output</td>
            </tr>
            <tr>
              <td>Execution</td>
              <td>Translates entire program first, then runs</td>
              <td>Reads and executes one line at a time</td>
            </tr>
            <tr>
              <td>Speed at run time</td>
              <td>Faster (already translated)</td>
              <td>Slower (translates on the fly)</td>
            </tr>
            <tr>
              <td>Error reporting</td>
              <td>Compile-time syntax errors reported before run</td>
              <td>Errors found only when that line is reached</td>
            </tr>
            <tr>
              <td>Example</td>
              <td>C, C++, Java (native)</td>
              <td>Python (CPython), early BASIC</td>
            </tr>
          </tbody>
        </table>

        <p>
          Consider the input <code>a = 3; b = 4; println(a*b);</code> — a compiler generates assembly instructions
          for it, while an interpreter evaluates it and directly outputs <strong>12</strong>.
        </p>

        <div class="note-box tip">
          <span class="box-icon">💡</span>
          <div>
            <strong>Common confusion:</strong> Many commercial compilers come packaged with a built-in
            edit–compile–run front end, so students don't notice the compilation step. As larger programs are
            divided into modules, each compiled separately to an <em>object module</em> and then <em>linked</em>,
            the distinction between compile time and run time becomes clearer.
          </div>
        </div>

        <p>
          It is important to remember that a compiler is itself a program, written in some language. When describing
          compilation we are always dealing with <strong>three languages</strong>:
        </p>
        <ol style="margin-left:20px; color:var(--muted); margin-bottom:14px;">
          <li>The <strong>source language</strong> — the input to the compiler</li>
          <li>The <strong>object language</strong> — the output of the compiler</li>
          <li>The <strong>implementation language</strong> — the language in which the compiler itself is written</li>
        </ol>
        <p>Note that the object language need not be machine code — it could itself be a high-level language.</p>
      </section>

      <!-- ─────────────────────────────────────────────── -->
      <!-- 1.1.3 Compile-time vs Run-time Errors          -->
      <!-- ─────────────────────────────────────────────── -->
      <section id="errors">
        <h2><span class="sec-icon">🐛</span> Compile-time vs Run-time Errors</h2>
        <p>
          <strong>Compile-time</strong> is when the source program is being compiled.
          <strong>Run time</strong> is when the resulting object program is loaded and executed.
          The type of error determines when it is detected.
        </p>

        <div class="error-grid">
          <div class="error-col">
            <div class="error-col-header compile">🔴 Compile-Time Errors (Syntax Errors)</div>
            <div class="code-wrap" style="margin:0; border-radius:0;">
              <pre><code><span class="cm">// Missing closing parenthesis</span>
a = ((b+c)*d;

<span class="cm">// Missing parentheses in if</span>
if x&lt;b fn1();
  else fn2();</code></pre>
            </div>
          </div>
          <div class="error-col">
            <div class="error-col-header runtime">🟡 Run-Time Errors (Logic Errors)</div>
            <div class="code-wrap" style="margin:0; border-radius:0;">
              <pre><code><span class="cm">// Division by zero</span>
x = a - a;
y = 100 / x;

<span class="cm">// Invalid array subscript</span>
Integer n[] = new Integer[7];
n[8] = 16;</code></pre>
            </div>
          </div>
        </div>

        <div class="note-box warn">
          <span class="box-icon">⚠️</span>
          <div>
            Syntax errors are reported by the compiler at <em>compile time</em>. Other kinds of errors —
            like division by zero or out-of-bounds array access — are not generally detected by the compiler
            and are called <em>run-time errors</em>. They only manifest when the program executes.
          </div>
        </div>
      </section>

      <!-- ─────────────────────────────────────────────── -->
      <!-- 1.1.4 Sample Problem (a) — while loop          -->
      <!-- ─────────────────────────────────────────────── -->
      <section id="sample-a">
        <h2><span class="sec-icon">🧪</span> Sample Problem 1.1(a) — Compiling a While Loop</h2>

        <div class="sample-problem">
          <div class="sp-header">
            <span style="font-size:18px">📋</span>
            <h4>Sample Problem 1.1 (a)</h4>
          </div>
          <div class="sp-body">
            <p class="sp-question">
              Show the output of a Java native code compiler, in any typical assembly language, for the following Java input:
            </p>
            <div class="code-wrap" style="margin-bottom:16px;">
              <div class="code-header">
                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                <span class="code-lang">Java — Source</span>
              </div>
              <pre><code><span class="kw">while</span> (x &lt; a + b) x = <span class="num">2</span> * x;</code></pre>
            </div>
            <div class="sp-solution-label">✅ Solution — Compiler Output (Assembly)</div>
            <div class="code-wrap">
              <div class="code-header">
                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                <span class="code-lang">Assembly — Object Code</span>
                <button class="copy-btn" onclick="copyBlock('sp-a-block', this)">Copy</button>
              </div>
              <pre><code id="sp-a-block">L1: <span class="op">LOD</span> <span class="reg">R1</span>,A          <span class="cm">// Load A into reg. 1</span>
    <span class="op">ADD</span> <span class="reg">R1</span>,B          <span class="cm">// Add B to reg. 1</span>
    <span class="op">STO</span> <span class="reg">R1</span>,Temp1      <span class="cm">// Temp1 = A + B</span>
    <span class="op">CMP</span> X,Temp1       <span class="cm">// Test for while condition</span>
    <span class="op">BL</span>  L2            <span class="cm">// Continue with loop if X &lt; Temp1</span>
    <span class="op">B</span>   L3            <span class="cm">// Terminate loop</span>
L2: <span class="op">LOD</span> <span class="reg">R1</span>,=<span class="str">'2'</span>
    <span class="op">MUL</span> <span class="reg">R1</span>,X
    <span class="op">STO</span> <span class="reg">R1</span>,X          <span class="cm">// X = 2 * X</span>
    <span class="op">B</span>   L1            <span class="cm">// Repeat loop</span>
L3:</code></pre>
            </div>
            <div class="note-box pro" style="margin-top:16px;">
              <span class="box-icon">🧩</span>
              <div>
                <strong>Why two branches?</strong> Note the somewhat convoluted logic after the <code>CMP</code>
                instruction — it branches to L2 if <code>X &lt; Temp1</code>, then unconditionally branches to L3.
                Some compilers could optimise this by branching directly to L3 when <code>X ≥ Temp1</code>,
                eliminating an instruction. But many compilers generate code this way, prioritising correctness
                over optimal instruction count.
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ─────────────────────────────────────────────── -->
      <!-- 1.1.5 Sample Problem (b) — for loop            -->
      <!-- ─────────────────────────────────────────────── -->
      <section id="sample-b">
        <h2><span class="sec-icon">🔁</span> Sample Problem 1.1(b) — Compiler vs Interpreter Output</h2>

        <div class="sample-problem">
          <div class="sp-header">
            <span style="font-size:18px">📋</span>
            <h4>Sample Problem 1.1 (b)</h4>
          </div>
          <div class="sp-body">
            <p class="sp-question">
              Show the compiler output and the interpreter output for the following Java source code:
            </p>
            <div class="code-wrap" style="margin-bottom:16px;">
              <div class="code-header">
                <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                <span class="code-lang">Java — Source</span>
              </div>
              <pre><code><span class="kw">for</span> (i=<span class="num">1</span>; i&lt;=<span class="num">4</span>; i++) System.out.println(i*<span class="num">3</span>);</code></pre>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
              <div>
                <div class="sp-solution-label">🖥️ Compiler Output (Assembly)</div>
                <div class="code-wrap">
                  <div class="code-header">
                    <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                    <span class="code-lang">Assembly</span>
                  </div>
                  <pre><code>    <span class="op">LOD</span> <span class="reg">R1</span>,=<span class="str">'4'</span>
    <span class="op">STO</span> <span class="reg">R1</span>,Temp1
    <span class="op">MOV</span> i,=<span class="str">'1'</span>
L1: <span class="op">CMP</span> i,Temp1
    <span class="op">BH</span>  L2          <span class="cm">// Branch if i &gt; Temp1</span>
    <span class="op">LOD</span> <span class="reg">R1</span>,i
    <span class="op">MUL</span> <span class="reg">R1</span>,=<span class="str">'3'</span>
    <span class="op">STO</span> <span class="reg">R1</span>,Temp2
    <span class="op">PUSH</span> Temp2
    <span class="op">CALL</span> Println
    <span class="op">ADD</span> i,=<span class="str">'1'</span>     <span class="cm">// Add 1 to i</span>
    <span class="op">B</span>   L1
L2:</code></pre>
                </div>
              </div>
              <div>
                <div class="sp-solution-label">💬 Interpreter Output (Result)</div>
                <div class="code-wrap">
                  <div class="code-header">
                    <div class="code-dots"><span class="dot-r"></span><span class="dot-y"></span><span class="dot-g"></span></div>
                    <span class="code-lang">Printed Output</span>
                  </div>
                  <pre><code><span class="num">3</span>
<span class="num">6</span>
<span class="num">9</span>
<span class="num">12</span></code></pre>
                </div>
                <div class="note-box key" style="margin-top:12px;">
                  <span class="box-icon">💡</span>
                  <div>The interpreter directly computed and printed the values <strong>3, 6, 9, 12</strong> — it
                    did not generate any machine code at all.</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ─────────────────────────────────────────────── -->
      <!-- 1.1.6 Big-C Notation                           -->
      <!-- ─────────────────────────────────────────────── -->
      <section id="bigc">
        <h2><span class="sec-icon">🔠</span> Big-C Notation for Compilers</h2>
        <p>
          A concise notation for describing compilers was introduced by Aho (1986). In these diagrams the large
          <strong>C</strong> stands for <em>Compiler</em> (not the C programming language). The components are:
        </p>

        <table class="compare-table">
          <thead>
            <tr>
              <th>Component</th>
              <th>Meaning</th>
              <th>Example</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Superscript (top arrow)</td>
              <td>The intended <em>translation</em> — source → target language</td>
              <td>Java → Mac machine language</td>
            </tr>
            <tr>
              <td>Subscript (bottom)</td>
              <td>The language the compiler <em>exists in</em> (runs on)</td>
              <td>Written in Sun machine language</td>
            </tr>
          </tbody>
        </table>

        <p>In this notation, the name of a machine represents its machine language — i.e. <em>Sun</em> means Sun machine language, <em>PC</em> means Intel Pentium machine language.</p>

        <div class="bigc-grid">
          <div class="bigc-card">
            <div class="bigc-label">Figure 1.2 (a)</div>
            <div class="bigc-notation">C<sup>Java→Mac</sup><sub>Mac</sub></div>
            <div class="bigc-sub">A Java compiler that runs on the Mac and produces Mac machine code</div>
          </div>
          <div class="bigc-card">
            <div class="bigc-label">Figure 1.2 (b)</div>
            <div class="bigc-notation">C<sup>Java→Mac</sup><sub>Sun</sub></div>
            <div class="bigc-sub">A Java compiler that generates Mac code but runs on a Sun computer</div>
          </div>
          <div class="bigc-card">
            <div class="bigc-label">Figure 1.2 (c)</div>
            <div class="bigc-notation">C<sup>PC→Java</sup><sub>Ada</sub></div>
            <div class="bigc-sub">Translates PC machine language to Java; written in Ada — won't run on any machine directly</div>
          </div>
        </div>

        <div class="note-box tip">
          <span class="box-icon">💡</span>
          <div>
            The language the compiler is written in does <em>not</em> need to match the object language. A compiler
            that produces Mac machine code could run on a Sun. And the object language doesn't need to be machine code —
            it could be a high-level language like Java.
          </div>
        </div>
      </section>

      <!-- ─────────────────────────────────────────────── -->
      <!-- 1.1.7 Sample Problem (c) — Big-C               -->
      <!-- ─────────────────────────────────────────────── -->
      <section id="sample-c">
        <h2><span class="sec-icon">✏️</span> Sample Problem 1.1(c) — Big-C Notation Practice</h2>

        <div class="sample-problem">
          <div class="sp-header">
            <span style="font-size:18px">📋</span>
            <h4>Sample Problem 1.1 (c)</h4>
          </div>
          <div class="sp-body">
            <p class="sp-question">Using the Big-C notation, show each of the following compilers:</p>
            <ol style="color:var(--muted); margin-left:20px; margin-bottom:16px;">
              <li>(a) An Ada compiler which runs on the PC and compiles to PC machine language.</li>
              <li>(b) An Ada compiler which compiles to PC machine language, but which is written in Ada.</li>
              <li>(c) An Ada compiler which compiles to PC machine language, but runs on a Sun.</li>
            </ol>

            <div class="sp-solution-label">✅ Solution</div>
            <div class="bigc-grid">
              <div class="bigc-card">
                <div class="bigc-label">(a) Ada → PC, runs on PC</div>
                <div class="bigc-notation">C<sup>Ada→PC</sup><sub>PC</sub></div>
                <div class="bigc-sub">Compiles Ada to PC machine language; the compiler itself runs on a PC</div>
              </div>
              <div class="bigc-card">
                <div class="bigc-label">(b) Ada → PC, written in Ada</div>
                <div class="bigc-notation">C<sup>Ada→PC</sup><sub>Ada</sub></div>
                <div class="bigc-sub">Compiles Ada to PC machine language; written in Ada — won't run directly on any machine</div>
              </div>
              <div class="bigc-card">
                <div class="bigc-label">(c) Ada → PC, runs on Sun</div>
                <div class="bigc-notation">C<sup>Ada→PC</sup><sub>Sun</sub></div>
                <div class="bigc-sub">Compiles Ada to PC machine language; the compiler runs on a Sun machine</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ─────────────────────────────────────────────── -->
      <!-- 1.1.8 Step-by-Step Walkthrough                 -->
      <!-- ─────────────────────────────────────────────── -->
      <section id="walkthrough">
        <h2 class="sr-only">Step-by-step compiler walkthrough examples with interactive stepper</h2>

        <div style="padding: 1rem 0;">
          <p class="section-label">Choose an example to step through:</p>
          <div class="example-picker">
            <button class="pick-btn active" onclick="switchExample(0, this)">A = B + C * D</button>
            <button class="pick-btn" onclick="switchExample(1, this)">while (x &lt; a+b)</button>
            <button class="pick-btn" onclick="switchExample(2, this)">for (i=1; i&lt;=4; i++)</button>
            <button class="pick-btn" onclick="switchExample(3, this)">if (a &gt; b)</button>
          </div>

          <div class="stepper-wrap" id="stepper">
            <div class="stepper-header">
              <span class="stepper-title" id="stepper-title">Executing: A = B + C * D</span>
              <span class="step-counter" id="step-counter">Step 1 of 7</span>
            </div>
            <div class="stepper-body">
              <div class="stepper-code">
                <code id="step-code"></code>
              </div>
              <div class="stepper-explain">
                <div class="step-label" id="step-label">STEP 1</div>
                <div class="step-title" id="step-title"></div>
                <div class="step-desc" id="step-desc"></div>
                <table class="reg-table">
                  <thead>
                    <tr>
                      <th>Register / Var</th>
                      <th>Value</th>
                    </tr>
                  </thead>
                  <tbody id="reg-tbody"></tbody>
                </table>
              </div>
            </div>
            <div class="stepper-nav">
              <button class="step-btn" id="btn-prev" onclick="stepMove(-1)" disabled>← Prev</button>
              <button class="step-btn" id="btn-reset" onclick="resetStep()">Reset</button>
              <button class="step-btn primary" id="btn-next" onclick="stepMove(1)">Next →</button>
            </div>
          </div>
        </div>
      </section>

      <!-- ─────────────────────────────────────────────── -->


      <!-- Chapter nav -->
      <div class="chapter-nav">
        <a href="index.php" class="nav-btn">
          <span>←</span>
          <div><span class="btn-sub">Back to</span>Home</div>
        </a>
        <a href="c1_2.php" class="nav-btn next">
          <div style="text-align:right"><span class="btn-sub">Next page</span>1.2 The Phases of a Compiler</div>
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

    // ── Copy code blocks ─────────────────────────────────────
    function copyBlock(id, btn) {
      const text = document.getElementById(id).innerText;
      navigator.clipboard.writeText(text).then(() => {
        btn.textContent = 'Copied!';
        setTimeout(() => btn.textContent = 'Copy', 1800);
      });
    }

    const examples = [{
        title: "Executing: A = B + C * D",
        source: "A = B + C * D;",
        lines: [
          "LOD R1,C       // Load C into register R1",
          "MUL R1,D       // R1 = C × D",
          "STO R1,TEMP1   // TEMP1 = C × D",
          "LOD R1,B       // Load B into R1",
          "ADD R1,TEMP1   // R1 = B + (C×D)",
          "STO R1,TEMP2   // TEMP2 = B + (C×D)",
          "MOV A,TEMP2    // A = final result ✓",
        ],
        steps: [{
            label: "STEP 1 — LOD R1,C",
            title: "Load C into R1",
            desc: "The LOD instruction reads the value stored at memory address C and places it into CPU register R1. No computation yet — just a data move.",
            regs: [{
              r: "R1",
              v: "C",
              c: true
            }, {
              r: "TEMP1",
              v: "—"
            }, {
              r: "TEMP2",
              v: "—"
            }, {
              r: "A",
              v: "—"
            }]
          },
          {
            label: "STEP 2 — MUL R1,D",
            title: "Multiply R1 by D",
            desc: "MUL takes the value in R1 (which holds C) and multiplies it by the value at memory address D. The result C×D overwrites R1.",
            regs: [{
              r: "R1",
              v: "C × D",
              c: true
            }, {
              r: "TEMP1",
              v: "—"
            }, {
              r: "TEMP2",
              v: "—"
            }, {
              r: "A",
              v: "—"
            }]
          },
          {
            label: "STEP 3 — STO R1,TEMP1",
            title: "Spill result to TEMP1",
            desc: "STO copies R1 into memory address TEMP1. This frees R1 so it can be reused. The intermediate result C×D is now safe in memory.",
            regs: [{
              r: "R1",
              v: "C × D"
            }, {
              r: "TEMP1",
              v: "C × D",
              c: true
            }, {
              r: "TEMP2",
              v: "—"
            }, {
              r: "A",
              v: "—"
            }]
          },
          {
            label: "STEP 4 — LOD R1,B",
            title: "Load B into R1",
            desc: "R1 is now reloaded with B from memory. The previous value C×D was safely stored in TEMP1 so nothing is lost.",
            regs: [{
              r: "R1",
              v: "B",
              c: true
            }, {
              r: "TEMP1",
              v: "C × D"
            }, {
              r: "TEMP2",
              v: "—"
            }, {
              r: "A",
              v: "—"
            }]
          },
          {
            label: "STEP 5 — ADD R1,TEMP1",
            title: "Add TEMP1 to R1",
            desc: "ADD fetches TEMP1 (C×D) and adds it to R1 (B). The sum B+(C×D) is stored back into R1. Operator precedence is handled correctly.",
            regs: [{
              r: "R1",
              v: "B + (C×D)",
              c: true
            }, {
              r: "TEMP1",
              v: "C × D"
            }, {
              r: "TEMP2",
              v: "—"
            }, {
              r: "A",
              v: "—"
            }]
          },
          {
            label: "STEP 6 — STO R1,TEMP2",
            title: "Store result in TEMP2",
            desc: "The full result B+(C×D) is stored in TEMP2, ready to be assigned to variable A.",
            regs: [{
              r: "R1",
              v: "B + (C×D)"
            }, {
              r: "TEMP1",
              v: "C × D"
            }, {
              r: "TEMP2",
              v: "B + (C×D)",
              c: true
            }, {
              r: "A",
              v: "—"
            }]
          },
          {
            label: "STEP 7 — MOV A,TEMP2",
            title: "Assign final result to A",
            desc: "MOV copies TEMP2 into variable A's memory address. The expression A = B + C*D is now fully evaluated and stored.",
            regs: [{
              r: "R1",
              v: "B + (C×D)"
            }, {
              r: "TEMP1",
              v: "C × D"
            }, {
              r: "TEMP2",
              v: "B + (C×D)"
            }, {
              r: "A",
              v: "B + (C×D)",
              c: true
            }]
          },
        ]
      },
      {
        title: "Compiling: while (x < a+b) x = 2*x",
        source: "while (x < a + b) x = 2 * x;",
        lines: [
          "L1: LOD R1,A        // Load A",
          "    ADD R1,B        // R1 = A + B",
          "    STO R1,TEMP1   // TEMP1 = A+B",
          "    CMP X,TEMP1    // Compare X vs TEMP1",
          "    BL  L2         // Branch to L2 if X < TEMP1",
          "    B   L3         // Else jump out of loop",
          "L2: LOD R1,='2'    // Load literal 2",
          "    MUL R1,X       // R1 = 2 * X",
          "    STO R1,X       // X = 2 * X",
          "    B   L1         // Jump back to loop start",
          "L3:                // Loop exit point",
        ],
        steps: [{
            label: "STEP 1 — LOD R1,A",
            title: "Load A (start of loop body)",
            desc: "Each iteration begins at label L1. We first compute the loop condition a+b, so A is loaded into R1.",
            regs: [{
              r: "R1",
              v: "A",
              c: true
            }, {
              r: "TEMP1",
              v: "—"
            }, {
              r: "X",
              v: "X (unchanged)"
            }]
          },
          {
            label: "STEP 2 — ADD R1,B",
            title: "Compute A + B",
            desc: "B is added to R1. Now R1 holds the value a+b, which is the right-hand side of the while condition.",
            regs: [{
              r: "R1",
              v: "A + B",
              c: true
            }, {
              r: "TEMP1",
              v: "—"
            }, {
              r: "X",
              v: "X (unchanged)"
            }]
          },
          {
            label: "STEP 3 — STO R1,TEMP1",
            title: "Save A+B to TEMP1",
            desc: "The computed value a+b is stored into TEMP1 so it can be used in the comparison without recalculating.",
            regs: [{
              r: "R1",
              v: "A + B"
            }, {
              r: "TEMP1",
              v: "A + B",
              c: true
            }, {
              r: "X",
              v: "X (unchanged)"
            }]
          },
          {
            label: "STEP 4 — CMP X,TEMP1",
            title: "Test the while condition",
            desc: "CMP compares X against TEMP1 (a+b). It sets CPU condition flags but does NOT branch yet — the next instruction decides what to do with the result.",
            regs: [{
              r: "R1",
              v: "A + B"
            }, {
              r: "TEMP1",
              v: "A + B"
            }, {
              r: "X",
              v: "X (compared)"
            }]
          },
          {
            label: "STEP 5 — BL L2",
            title: "Branch if X < TEMP1 (continue loop)",
            desc: "BL (Branch if Less) jumps to L2 if the condition x < a+b is true, meaning the loop should continue executing.",
            regs: [{
              r: "flags",
              v: "x < a+b ?",
              c: true
            }, {
              r: "TEMP1",
              v: "A + B"
            }, {
              r: "X",
              v: "X"
            }]
          },
          {
            label: "STEP 6 — B L3",
            title: "Unconditional jump to exit",
            desc: "If BL did NOT branch (x >= a+b), we fall through to this unconditional branch to L3, which exits the loop.",
            regs: [{
              r: "flags",
              v: "x >= a+b"
            }, {
              r: "TEMP1",
              v: "A + B"
            }, {
              r: "X",
              v: "X"
            }]
          },
          {
            label: "STEP 7 — LOD R1,'2'",
            title: "Load literal 2 (inside loop)",
            desc: "We are now inside the loop (at L2). Load the literal constant 2 into R1 to prepare the multiplication.",
            regs: [{
              r: "R1",
              v: "2",
              c: true
            }, {
              r: "TEMP1",
              v: "A + B"
            }, {
              r: "X",
              v: "X"
            }]
          },
          {
            label: "STEP 8 — MUL R1,X",
            title: "Multiply 2 × X",
            desc: "MUL multiplies R1 (holds 2) by the current value of X. The result 2×X is stored back in R1.",
            regs: [{
              r: "R1",
              v: "2 × X",
              c: true
            }, {
              r: "TEMP1",
              v: "A + B"
            }, {
              r: "X",
              v: "X (old)"
            }]
          },
          {
            label: "STEP 9 — STO R1,X",
            title: "Update X = 2 * X",
            desc: "STO stores R1 back into X's memory location. X has now been updated to its new value for this iteration.",
            regs: [{
              r: "R1",
              v: "2 × X"
            }, {
              r: "TEMP1",
              v: "A + B"
            }, {
              r: "X",
              v: "2 × X",
              c: true
            }]
          },
          {
            label: "STEP 10 — B L1",
            title: "Jump back to loop start",
            desc: "Unconditional branch back to L1. The condition is re-evaluated. If x < a+b still holds, the loop runs again.",
            regs: [{
              r: "R1",
              v: "2 × X"
            }, {
              r: "TEMP1",
              v: "A + B"
            }, {
              r: "X",
              v: "2 × X"
            }]
          },
          {
            label: "STEP 11 — L3 (exit)",
            title: "Loop exit point",
            desc: "Label L3 marks where control jumps when the while condition becomes false (x >= a+b). Execution continues from here.",
            regs: [{
              r: "R1",
              v: "(last 2×X)"
            }, {
              r: "TEMP1",
              v: "A + B"
            }, {
              r: "X",
              v: "final X"
            }]
          },
        ]
      },
      {
        title: "Compiling: for (i=1; i<=4; i++) println(i*3)",
        source: "for (i=1; i<=4; i++) System.out.println(i*3);",
        lines: [
          "    LOD R1,='4'     // Load limit 4",
          "    STO R1,TEMP1   // TEMP1 = 4",
          "    MOV i,='1'     // i = 1 (init)",
          "L1: CMP i,TEMP1    // Compare i vs 4",
          "    BH  L2         // Exit if i > 4",
          "    LOD R1,i       // Load i",
          "    MUL R1,='3'    // R1 = i * 3",
          "    STO R1,TEMP2   // TEMP2 = i*3",
          "    PUSH TEMP2     // Push arg",
          "    CALL Println   // Print it",
          "    ADD i,='1'     // i++",
          "    B   L1         // Loop back",
          "L2:                // Loop done",
        ],
        steps: [{
            label: "STEP 1 — LOD R1,'4'",
            title: "Load the loop limit",
            desc: "We load the constant 4 (the upper bound of the for loop) into R1 so it can be stored for repeated comparisons.",
            regs: [{
              r: "R1",
              v: "4",
              c: true
            }, {
              r: "TEMP1",
              v: "—"
            }, {
              r: "i",
              v: "—"
            }]
          },
          {
            label: "STEP 2 — STO R1,TEMP1",
            title: "Save limit to TEMP1",
            desc: "Store the limit value (4) into TEMP1 in memory. This avoids reloading the constant every iteration.",
            regs: [{
              r: "R1",
              v: "4"
            }, {
              r: "TEMP1",
              v: "4",
              c: true
            }, {
              r: "i",
              v: "—"
            }]
          },
          {
            label: "STEP 3 — MOV i,'1'",
            title: "Initialize i = 1",
            desc: "MOV places the constant 1 directly into variable i. This is the initialization part of the for statement.",
            regs: [{
              r: "R1",
              v: "4"
            }, {
              r: "TEMP1",
              v: "4"
            }, {
              r: "i",
              v: "1",
              c: true
            }]
          },
          {
            label: "STEP 4 — CMP i,TEMP1",
            title: "Check loop condition i <= 4",
            desc: "Each iteration starts here (L1). CMP compares i against TEMP1 (the limit 4). Condition flags are set for the next branch.",
            regs: [{
              r: "R1",
              v: "4"
            }, {
              r: "TEMP1",
              v: "4"
            }, {
              r: "i",
              v: "current i"
            }]
          },
          {
            label: "STEP 5 — BH L2",
            title: "Exit if i > 4",
            desc: "BH (Branch if Higher) jumps to L2 to exit the loop when i exceeds 4. If i <= 4, we fall through and continue.",
            regs: [{
              r: "flags",
              v: "i > 4 ?",
              c: true
            }, {
              r: "TEMP1",
              v: "4"
            }, {
              r: "i",
              v: "current i"
            }]
          },
          {
            label: "STEP 6 — LOD R1,i",
            title: "Load current i into R1",
            desc: "Load the current value of loop variable i into R1 to compute the body expression i*3.",
            regs: [{
              r: "R1",
              v: "i",
              c: true
            }, {
              r: "TEMP1",
              v: "4"
            }, {
              r: "i",
              v: "current i"
            }]
          },
          {
            label: "STEP 7 — MUL R1,'3'",
            title: "Compute i * 3",
            desc: "Multiply R1 (holds i) by the literal 3. The product i×3 is stored back in R1.",
            regs: [{
              r: "R1",
              v: "i × 3",
              c: true
            }, {
              r: "TEMP1",
              v: "4"
            }, {
              r: "i",
              v: "current i"
            }]
          },
          {
            label: "STEP 8 — STO R1,TEMP2",
            title: "Save result to TEMP2",
            desc: "The computed value i×3 is written to TEMP2 to prepare it as an argument for the println call.",
            regs: [{
              r: "R1",
              v: "i × 3"
            }, {
              r: "TEMP2",
              v: "i × 3",
              c: true
            }, {
              r: "i",
              v: "current i"
            }]
          },
          {
            label: "STEP 9 — PUSH TEMP2",
            title: "Push argument onto stack",
            desc: "PUSH places TEMP2 onto the call stack. The Println subroutine will read its argument from here.",
            regs: [{
              r: "stack",
              v: "i × 3",
              c: true
            }, {
              r: "TEMP2",
              v: "i × 3"
            }, {
              r: "i",
              v: "current i"
            }]
          },
          {
            label: "STEP 10 — CALL Println",
            title: "Call Println — output the value",
            desc: "CALL transfers control to the Println subroutine, which prints the value (3, 6, 9, or 12) and returns.",
            regs: [{
              r: "output",
              v: "prints i × 3",
              c: true
            }, {
              r: "TEMP2",
              v: "i × 3"
            }, {
              r: "i",
              v: "current i"
            }]
          },
          {
            label: "STEP 11 — ADD i,'1'",
            title: "Increment i (i++)",
            desc: "ADD adds 1 to i in memory. This is the increment part of the for loop: i++.",
            regs: [{
              r: "R1",
              v: "i × 3"
            }, {
              r: "TEMP2",
              v: "i × 3"
            }, {
              r: "i",
              v: "i + 1",
              c: true
            }]
          },
          {
            label: "STEP 12 — B L1",
            title: "Jump back to condition check",
            desc: "Unconditional branch back to L1. The condition i<=4 is tested again. The loop runs while i is 1, 2, 3, 4.",
            regs: [{
              r: "R1",
              v: "i × 3"
            }, {
              r: "TEMP2",
              v: "i × 3"
            }, {
              r: "i",
              v: "i + 1"
            }]
          },
          {
            label: "STEP 13 — L2 (done)",
            title: "Loop finished, execution resumes",
            desc: "When i becomes 5, BH at step 5 jumps here. The for loop is complete. Output was: 3, 6, 9, 12.",
            regs: [{
              r: "output",
              v: "3, 6, 9, 12"
            }, {
              r: "TEMP1",
              v: "4"
            }, {
              r: "i",
              v: "5"
            }]
          },
        ]
      },
      {
        title: "Compiling: if (a > b) x = a; else x = b;",
        source: "if (a > b) x = a; else x = b;",
        lines: [
          "    CMP a,b         // Compare a vs b",
          "    BH  L1         // Branch if a > b",
          "    MOV x,b        // else: x = b",
          "    B   L2         // Skip 'then' block",
          "L1: MOV x,a        // then: x = a",
          "L2:                // Merge point",
        ],
        steps: [{
            label: "STEP 1 — CMP a,b",
            title: "Compare a and b",
            desc: "CMP subtracts b from a and sets CPU condition flags based on the result. It does NOT store a value — it only sets flags for the branch instructions that follow.",
            regs: [{
              r: "a",
              v: "a"
            }, {
              r: "b",
              v: "b"
            }, {
              r: "flags",
              v: "a vs b",
              c: true
            }, {
              r: "x",
              v: "—"
            }]
          },
          {
            label: "STEP 2 — BH L1",
            title: "Branch if a > b (then branch)",
            desc: "BH (Branch if Higher) checks the flags. If a > b, it jumps to label L1 where the 'then' block lives: x = a.",
            regs: [{
              r: "flags",
              v: "a > b ?",
              c: true
            }, {
              r: "b",
              v: "b"
            }, {
              r: "a",
              v: "a"
            }, {
              r: "x",
              v: "—"
            }]
          },
          {
            label: "STEP 3 — MOV x,b",
            title: "Else branch: assign x = b",
            desc: "If BH did NOT branch (meaning a <= b), we arrive here — the else branch. MOV copies b directly into x.",
            regs: [{
              r: "flags",
              v: "a <= b"
            }, {
              r: "b",
              v: "b"
            }, {
              r: "a",
              v: "a"
            }, {
              r: "x",
              v: "b",
              c: true
            }]
          },
          {
            label: "STEP 4 — B L2",
            title: "Skip over the then-block",
            desc: "After the else branch runs, an unconditional B jumps to L2, bypassing the 'then' block (x = a) which doesn't apply here.",
            regs: [{
              r: "flags",
              v: "a <= b"
            }, {
              r: "b",
              v: "b"
            }, {
              r: "a",
              v: "a"
            }, {
              r: "x",
              v: "b"
            }]
          },
          {
            label: "STEP 5 — L1: MOV x,a",
            title: "Then branch: assign x = a",
            desc: "Label L1 is where BH jumped when a > b. MOV copies a into x. This is the 'then' branch of the if statement.",
            regs: [{
              r: "flags",
              v: "a > b"
            }, {
              r: "b",
              v: "b"
            }, {
              r: "a",
              v: "a"
            }, {
              r: "x",
              v: "a",
              c: true
            }]
          },
          {
            label: "STEP 6 — L2 (merge)",
            title: "Both branches meet here",
            desc: "Label L2 is the merge point. Whether we took the 'then' branch or the 'else' branch, execution continues from here. x now holds max(a, b).",
            regs: [{
              r: "flags",
              v: "—"
            }, {
              r: "b",
              v: "b"
            }, {
              r: "a",
              v: "a"
            }, {
              r: "x",
              v: "max(a,b)"
            }]
          },
        ]
      }
    ];

    let currentExample = 0;
    let currentStep = 0;

    function render() {
      const ex = examples[currentExample];
      const s = ex.steps[currentStep];

      document.getElementById("stepper-title").textContent = ex.title;
      document.getElementById("step-counter").textContent = `Step ${currentStep + 1} of ${ex.steps.length}`;

      document.getElementById("step-code").innerHTML = ex.lines.map((line, i) =>
        `<span class="code-line${i === currentStep ? " active" : ""}">${line}</span>`
      ).join("\n");

      document.getElementById("step-label").textContent = s.label;
      document.getElementById("step-title").textContent = s.title;
      document.getElementById("step-desc").textContent = s.desc;

      document.getElementById("reg-tbody").innerHTML = s.regs.map(r =>
        `<tr class="${r.c ? "changed" : ""}"><td>${r.r}</td><td>${r.v}</td></tr>`
      ).join("");

      document.getElementById("btn-prev").disabled = currentStep === 0;
      const nextBtn = document.getElementById("btn-next");
      nextBtn.disabled = currentStep === ex.steps.length - 1;
      nextBtn.textContent = currentStep === ex.steps.length - 1 ? "Done ✓" : "Next →";
      nextBtn.className = "step-btn" + (currentStep === ex.steps.length - 1 ? "" : " primary");
    }

    function stepMove(dir) {
      const ex = examples[currentExample];
      currentStep = Math.max(0, Math.min(ex.steps.length - 1, currentStep + dir));
      render();
    }

    function resetStep() {
      currentStep = 0;
      render();
    }

    function switchExample(idx, btn) {
      document.querySelectorAll(".pick-btn").forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      currentExample = idx;
      currentStep = 0;
      render();
    }

    render();
  </script>

</body>

</html>