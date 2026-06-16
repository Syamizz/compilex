<?php
// Define page variables
$chapterTitle = "Chapter 1: Introduction to Compilers";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $chapterTitle; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="container">
    <article>
        <header>
            <h1><?php echo $chapterTitle; ?></h1>
            <p class="intro-text">
                [cite_start]A user interface is the mechanism through which a user communicates with a device[cite: 476]. 
                Programming languages are sophisticated user interfaces that allow us to specify computations 
                [cite_start]naturally rather than using complex binary codes[cite: 477].
            </p>
        </header>

        <section id="what-is-compiler">
            <h2>1.1 What is a Compiler?</h2>
            <p>
                A <strong>compiler</strong> is a software translator that accepts high-level language statements 
                [cite_start]and translates them into sequences of machine language operations[cite: 489, 490].
            </p>
            
            
            <ul>
                [cite_start]<li><strong>Source Program:</strong> The input program written in a high-level language[cite: 496].</li>
                [cite_start]<li><strong>Object Program:</strong> The equivalent output program in machine language[cite: 497].</li>
                [cite_start]<li><strong>Equivalence:</strong> Two programs are equivalent if they produce the same output for the same input[cite: 495].</li>
            </ul>

            <div class="comparison-box">
                <h3>Compiler vs. Interpreter</h3>
                <p>
                    While a compiler's output is a <em>program</em>, an <strong>interpreter</strong> actually 
                    [cite_start]carries out the computations specified in the source program[cite: 571]. 
                    The output of an interpreter is the source program's actual result (e.g., the number 12 
                    [cite_start]instead of a sequence of instructions)[cite: 571, 572].
                </p>
            </div>
        </section>

        <section id="phases">
            <h2>1.2 The Phases of a Compiler</h2>
            [cite_start]<p>Compilers are implemented in phases to simplify the design process[cite: 701].</p>
            
            
            <ol>
                <li>
                    <strong>Lexical Analysis (Scanner):</strong> Isolates "words" (lexemes or tokens) 
                    [cite_start]such as keywords, identifiers, and operators[cite: 706, 707].
                </li>
                <li>
                    <strong>Syntax Analysis (Parser):</strong> Checks for proper syntax and determines the 
                    [cite_start]underlying structure, often producing <em>atoms</em> or <em>syntax trees</em>[cite: 722, 723, 724].
                </li>
                <li>
                    <strong>Global Optimization (Optional):</strong> Improves efficiency by eliminating 
                    [cite_start]redundant instructions or moving "loop invariants" out of loops[cite: 781, 783, 791].
                </li>
                <li>
                    <strong>Code Generation:</strong> Translates atoms or syntax trees into actual 
                    [cite_start]machine or assembly language[cite: 801].
                </li>
                <li>
                    <strong>Local Optimization (Optional):</strong> Examines generated instructions to 
                    find unnecessary or redundant sequences (e.g., removing a "Store" followed by an 
                    [cite_start]identical "Load")[cite: 811, 812, 816].
                </li>
            </ol>
        </section>

        <section id="techniques">
            <h2>1.3 Implementation Techniques</h2>
            <ul>
                <li>
                    <strong>Bootstrapping:</strong> Using a small subset of a language to write a compiler 
                    [cite_start]for the full language, effectively "pulling itself up by its bootstraps"[cite: 850, 856].
                </li>
                <li>
                    <strong>Cross Compiling:</strong> Using a compiler on one machine (e.g., a Sun) to 
                    [cite_start]generate a compiler that runs on a different machine (e.g., a Mac)[cite: 870, 871].
                </li>
                <li>
                    <strong>Intermediate Form:</strong> Compiling to a language between high-level and machine 
                    [cite_start]code (like Java Byte Code), allowing one "front end" to work with multiple "back ends"[cite: 879, 881, 888].
                </li>
            </ul>
        </section>
    </article>
</main>

</body>
</html>