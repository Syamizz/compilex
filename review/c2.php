<?php
session_start();
include '../dbconn.php';

// Get username from session
$username = $_SESSION['username'];
// Query user data
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Fetch data
$user = $result->fetch_assoc();
$user['user_id'] = $_SESSION['user_id'];

// Define the default image path
$default_img = "images/default-avatar-profile.jpg";

// Logic: If database has a value AND the file actually exists in the folder
if (!empty($user['profile_image']) && file_exists("images/" . $user['profile_image'])) {
    $user_img = "images/" . $user['profile_image'];
} else {
    $user_img = $default_img;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CompileX - Learn Compiler Principles</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="body.css">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">


    <!-- font -->

    <style>
        body {
            font-family: 'Syne', sans-serif;
            background: var(--color-background-tertiary);
            margin: 0;
        }


        .login-btn {
            background: #A5B4FC;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
            font-weight: bold;
        }

        .login-btn:hover {
            background: #818CF8;
        }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 80px 20px;
        }

        .hero h1 {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .hero p {
            max-width: 700px;
            margin: auto;
            font-size: 18px;
            color: #6B7280;
        }

        /* Section */
        .section {
            max-width: 1000px;
            margin: auto;
            padding: 60px 20px;
        }

        .section h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1F2937;
        }

        .topics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            background: #FFFFFF;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .card h3 {
            margin-top: 0;
            color: #6366F1;
        }

        /* CTA */
        .cta {
            text-align: center;
            padding: 60px 20px;
        }

        .cta button {
            background: #6366F1;
            color: white;
            border: none;
            padding: 14px 28px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        .cta button:hover {
            background: #4F46E5;
        }

        footer {
            text-align: center;
            padding: 20px;
            background: #FFFFFF;
            margin-top: 40px;
            font-size: 14px;
            color: #6B7280;
        }

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



        h2 {
            width: fit-content;
            margin: 50px auto 28px;
            padding: 12px 28px;
            color: #1F2937;
            font-size: 44px;
            font-weight: 800;
            text-align: center;
            letter-spacing: 0;
            border-bottom: 5px solid #6366F1;
            position: relative;
        }

        h2::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -12px;
            width: 70px;
            height: 5px;
            background: #1D9E75;
            border-radius: 999px;
            transform: translateX(-50%);
        }

        @media (max-width: 600px) {
            h2 {
                font-size: 32px;
                padding: 10px 18px;
            }
        }

        .overview-video-wrap {
            max-width: 900px;
            margin: 0 auto 50px;
            padding: 0 20px;
        }

        .overview-video {
            width: 100%;
            border-radius: 14px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.18);
            background: #000;
        }

        .construction-modal {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            font-family: 'Syne', sans-serif;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.2);
        }

        .construction-modal-header {
            background: #6366F1;
            color: white;
            border-bottom: none;
            padding: 18px 22px;
        }

        .construction-modal-header .modal-title {
            font-size: 20px;
            font-weight: 700;
        }

        .construction-modal-body {
            padding: 24px 22px;
            color: #333;
            font-size: 15px;
            line-height: 1.6;
            background: #ffffff;
        }

        .construction-modal-footer {
            border-top: none;
            padding: 0 22px 22px;
            background: #ffffff;
        }

        .construction-modal-btn {
            width: 100%;
            padding: 11px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }

        .construction-modal-btn:hover {
            background: #34495e;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav id="navbar" class="navbar navbar-expand-lg">
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
                        <a class="nav-link" href="/tools">Tools</a>
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

    <br><br>
    <h2>Overview Chapter 2</h2>


    <br><br>

    <div class="overview-video-wrap">
        <video class="overview-video" controls autoplay muted>
            <source src="video/vid1.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <div class="modal fade" id="underConstructionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content construction-modal">
                <div class="modal-header construction-modal-header">
                    <h5 class="modal-title">Page Under Construction</h5>
                </div>

                <div class="modal-body construction-modal-body">
                    This page is currently under construction. Please come back later.
                </div>

                <div class="modal-footer construction-modal-footer">
                    <button type="button" class="construction-modal-btn" id="constructionOkBtn">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <footer>
        © <?php echo date("Y"); ?> CompileX • Compiler Learning Platform
    </footer>


    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const constructionModal = new bootstrap.Modal(
                document.getElementById('underConstructionModal'), {
                    backdrop: 'static',
                    keyboard: false
                }
            );

            constructionModal.show();

            document.getElementById('constructionOkBtn').addEventListener('click', () => {
                window.location.href = '../home.php';
            });
        });
    </script>
</body>

</html>