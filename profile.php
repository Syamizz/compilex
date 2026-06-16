<?php
session_start();
include 'dbconn.php';

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


    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+NZ+Basic:wght@100..400&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            color: #374151;

            font-family: Arial;
            background: #FAFAF9;
            font-family: "Playwrite NZ Basic", cursive;
            font-optical-sizing: auto;
            font-weight: 2px;
            font-style: normal;
        }


        .logo {
            font-size: 22px;
            font-weight: bold;
            color: #6366F1;
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
            background-color: #FFD6FF;
        }



        /* CSS */
        .button-28 {
            appearance: none;
            background-color: transparent;
            border: 2px solid #1A1A1A;
            border-radius: 15px;
            box-sizing: border-box;
            color: #3B3B3B;
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



        .fade {
            animation: fadeEffect 1s;
        }

        @keyframes fadeEffect {
            from {
                opacity: .4
            }

            to {
                opacity: 1
            }
        }

        .progress-card {
            padding: 25px;
        }

        .chapter-title {
            font-size: 18px;
            margin-bottom: 15px;
            color: #6366F1;
        }

        .big-progress {
            height: 28px;
            /* makes the bar larger */
            font-size: 14px;
            font-weight: bold;
        }



        /* Make the card slightly lift or change color when hovered */
        .progress-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }

        .progress-card:hover {
            transform: translateY(-3px);
            /* Subtle lift */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            /* Soft shadow */
            border-color: #6366f1;
            /* Highlight the border */
        }


        /* CSS */
        .button-4 {
            appearance: none;
            background-color: #FAFBFC;
            border: 1px solid rgba(27, 31, 35, 0.15);
            border-radius: 6px;
            box-shadow: rgba(27, 31, 35, 0.04) 0 1px 0, rgba(255, 255, 255, 0.25) 0 1px 0 inset;
            box-sizing: border-box;
            color: #24292E;
            cursor: pointer;
            display: inline-block;
            font-family: -apple-system, system-ui, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji";
            font-size: 14px;
            font-weight: 500;
            line-height: 20px;
            list-style: none;
            padding: 6px 16px;
            position: relative;
            transition: background-color 0.2s cubic-bezier(0.3, 0, 0.5, 1);
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            vertical-align: middle;
            white-space: nowrap;
            word-wrap: break-word;
        }

        .button-4:hover {
            background-color: #F3F4F6;
            text-decoration: none;
            transition-duration: 0.1s;
        }

        .button-4:disabled {
            background-color: #FAFBFC;
            border-color: rgba(27, 31, 35, 0.15);
            color: #959DA5;
            cursor: default;
        }

        .button-4:active {
            background-color: #EDEFF2;
            box-shadow: rgba(225, 228, 232, 0.2) 0 1px 0 inset;
            transition: none 0s;
        }

        .button-4:focus {
            outline: 1px transparent;
        }

        .button-4:before {
            display: none;
        }

        .button-4:-webkit-details-marker {
            display: none;
        }

        .profile-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 70vh;
            /* center vertically */
        }

        .profile-card {
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            width: 45%;
            text-align: left;
        }

        .profile-card h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #6366F1;
        }

        .profile-card table {
            width: 100%;
        }

        .profile-card th {
            text-align: left;
            padding: 8px 0;
            color: #374151;
        }

        .profile-card td {
            text-align: left;
            color: #6B7280;
        }

        .profile-card .btn-container {
            text-align: center;
            margin-top: 20px;
        }

        .profile-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            /* Ensures the image doesn't look stretched */
            border: 2px solid #ccc;
            /* Optional: adds a nice border ring */
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav id="navbar" class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a id="title" class="navbar-brand" href="#">CompileX</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Chapter
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Chap 1</a></li>
                            <li><a class="dropdown-item" href="#">Chap 2</a></li>
                            <li><a class="dropdown-item" href="#">Chap 3</a></li>
                            <li><a class="dropdown-item" href="#">Chap 4</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Quiz</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Leaderboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">Profile</a>
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

    <div class="profile-container">
        <div class="profile-card">
            <h2>My Profile</h2>

            <table>
                <tr>
                    <th colspan="2" style="text-align: center;">
                        <img class="profile-circle" src="<?php echo $user_img; ?>" alt="Profile Picture">
                    </th>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="btn-container">
                            <form action="upload_profile.php" method="POST" enctype="multipart/form-data">
                                <input type="file" name="profile_pic" id="file-input" style="display:none;" onchange="this.form.submit()">
                                <button type="button" class="button-4" onclick="document.getElementById('file-input').click();">
                                    Edit Image
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Full Name:</th>
                    <td><?php echo $user['fullname']; ?></td>
                </tr>
                <tr>
                    <th>Username:</th>
                    <td><?php echo $user['username']; ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><?php echo $user['email']; ?></td>
                </tr>
            </table>
            <br>
            <div class="btn-container">
                <button type="button" class="button-4" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    Edit Profile
                </button>
            </div>
        </div>
    </div>


    <br><br>






    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="font-family: Arial, sans-serif;">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Update Profile Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="update_profile.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <p class="text-muted" style="font-size: 12px;">Note: Username cannot be changed for security reasons.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #6366F1; border: none;">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <footer>
        © <?php echo date("Y"); ?> ezComp • Compiler Learning Platform
    </footer>

    <script>
        let slideIndex = 0;
        showSlides();

        function showSlides() {
            let slides = document.getElementsByClassName("slide");

            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }

            slideIndex++;
            if (slideIndex > slides.length) {
                slideIndex = 1
            }

            slides[slideIndex - 1].style.display = "block";

            setTimeout(showSlides, 4000); // change every 4 seconds
        }
    </script>

</body>

</html>