<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CompileX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+NZ+Basic:wght@100..400&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: Arial;
            background: #FAFAF9;
            font-family: "Playwrite NZ Basic", cursive;
            font-optical-sizing: auto;
            font-weight: 2px;
            font-style: normal;
        }

        .login-box {
            width: 350px;
            margin: 80px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #2c3e50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #34495e;
        }

        .error {
            color: red;
            text-align: center;
        }

        #navbar {
            background-color: #FFD6FF;
        }
    </style>
</head>

<body>
    <nav id="navbar" class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a id="title" class="navbar-brand" href="#">CompileX</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
        </div>
    </nav>

    <br>
    <h2>Welcome to CompileX</h2>
    <!-- content -->
    <div class="login-box">
        <h2>Login</h2>

        <?php if (isset($error)) {
            echo "<p class='error'>$error</p>";
        } ?>

        <?php
        if (isset($_GET['error'])) {
            echo "<script>alert('Wrong username or password!');</script>";
        }
        ?>

        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <br><br>
            <button type="submit">Login</button>
            <a href="register.php">Don't have account? Register here</a>
        </form>
        <br>
        <a href="google-login.php" class="btn btn-danger w-100">
            Login with Gmail
        </a>
    </div>
</body>

</html>