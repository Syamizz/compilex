<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <nav id="navbar" class="navbar navbar-expand-lg fixed-top">
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

                    <li class="nav-item">
                        <a class="nav-link" href="quiz.php">Quiz</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="tools/">Tools</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="leaderboard.php">Leaderboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="profile.php">Profile</a>
                    </li>
                    
                </ul>
            </div>

            <audio id="buttonSound" src="sound/button.mp3" preload="auto"></audio>
            <div class="collapse navbar-collapse">
                <div class="ms-auto">
                    <a href="logout.php"
                        class="button-28"
                        onclick="playLinkSound(event, this.href)">
                        LOGOUT
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <script>
        function playLinkSound(event, url) {
            event.preventDefault();

            const sound = document.getElementById("buttonSound");
            sound.currentTime = 0;
            sound.play();

            setTimeout(function() {
                window.location.href = url;
            }, 250);
        }
    </script>
</body>

</html>