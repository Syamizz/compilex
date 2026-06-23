<?php
session_start();
include '../dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CompileX - Learn Compiler Principles</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>


    <style>
        #navbar {
            background-color: #18181B;
        }

        /* Navbar text — white on dark background */
        #navbar a,
        #navbar .nav-link {
            color: #FFFFFF;
        }

        /* Hover state — slightly muted so there's a visual reaction */
        #navbar a:hover,
        #navbar .nav-link:hover {
            color: #A1A1AA;
        }

        /* Active/current page — white + indigo underline */
        #navbar a.active,
        #navbar .nav-link.active {
            color: #FFFFFF;
            border-bottom: 4px solid #6366F1;
        }

        /* CSS */
        .button-28 {
            appearance: none;
            background-color: transparent;
            border: 2px solid #f7f3f3;
            border-radius: 15px;
            box-sizing: border-box;
            color: #504141;
            cursor: pointer;
            display: inline-block;
            font-size: 10px;
            font-weight: 600;
            line-height: normal;
            margin: 0;
            min-height: 30px;
            min-width: 0;
            outline: none;
            padding: 12px 20px;
            text-align: center;
            text-decoration: none;
            transition: all 300ms cubic-bezier(.23, 1, 0.32, 1);
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            width: auto;
            will-change: transform;
        }

        .button-28:disabled {
            pointer-events: none;
        }

        .button-28:hover {
            color: #fff;
            background-color: #1A1A1A;
            box-shadow: rgba(0, 0, 0, 0.25) 0 8px 15px;
            transform: translateY(-2px);
        }

        .button-28:active {
            box-shadow: none;
            transform: translateY(0);
        }

        #titleTools {
            width: fit-content;
            margin: 35px auto 55px;
            padding: 12px 24px;
            position: relative;

            font-size: 30px;
            font-weight: 800;
            text-align: center;
            color: #18181B;

            cursor: default;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        /* Animated underline */
        #titleTools::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 0;

            width: 45%;
            height: 4px;
            border-radius: 20px;
            background: linear-gradient(90deg, #6366F1, #A855F7, #06B6D4);

            transform: translateX(-50%);
            transition: width 0.35s ease;
        }

        #titleTools:hover {
            color: #6366F1;
            transform: translateY(-4px) scale(1.03);
        }

        #titleTools:hover::after {
            width: 100%;
        }

        /* Small interactive icon */
        #titleTools::before {
            content: "⚙";
            display: inline-block;
            margin-right: 10px;
            color: #6366F1;
            transition: transform 0.5s ease;
        }

        #titleTools:hover::before {
            transform: rotate(180deg);
        }

        @media (max-width: 600px) {
            #titleTools {
                font-size: 23px;
                margin-bottom: 35px;
                padding-inline: 12px;
            }
        }
    </style>


</head>

<body>
    <!-- Navbar -->
    <nav id="navbar" class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a id="title" class="navbar-brand" href="../home.php">CompileX</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="../home.php">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../quiz">Quiz</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="../tools">Tools</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../leaderboard.php">Leaderboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../profile.php">Profile</a>
                    </li>
                </ul>
            </div>
            <div class="collapse navbar-collapse">
                <div class="ms-auto">
                    <a href="logout.php" class="button-28">LOGOUT</a>
                </div>
            </div>
        </div>
    </nav>

    <br><br><br><br>
    <div>

        <h2 id="titleTools">Interactive Compiler Tools</h2>

    </div>

    
    <div class="main-grid" style="display:grid; grid-template-columns:1.25fr 1.35fr; gap:32px; align-items:start;">
        <div>
            <?php
            include "tools.php";
            ?>
        </div>
        <div>
            <?php
            include "tools2.php";
            ?>
        </div>

    </div>


    <br><br><br><br>

    <footer>
        © <?php echo date("Y"); ?> CompileX • Compiler Learning Platform
    </footer>

</body>

</html>