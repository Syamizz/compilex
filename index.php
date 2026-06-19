<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CompileX</title>
 
    <!-- font -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="body.css">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
 
        body {
            font-family: 'Syne', sans-serif;
            background: var(--color-background-tertiary);
            margin: 0;
        }

        .page {
            background: #FAFAF9;
            min-height: 500px;
        }

         #navbar {
            background-color:  #18181B;
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


        nav span {
            font-family: var(--font-serif);
            font-size: 18px;
            color: #2c3e50;
        }

        .hero {
            text-align: center;
            padding: 36px 16px 12px;
            font-family: var(--font-serif);
            font-size: 22px;
            color: #2c2c2a;
        }

        .card {
            width: 340px;
            margin: 0 auto;
            background: white;
            border-radius: 14px;
            border: 0.5px solid rgba(0, 0, 0, 0.1);
            padding: 32px 28px 28px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07);
        }

        .card-title {
            text-align: center;
            font-family: var(--font-serif);
            font-size: 22px;
            color: #2c2c2a;
            margin-bottom: 22px;
        }

        .field {
            position: relative;
            margin-bottom: 14px;
        }

        .field input {
            width: 100%;
            padding: 11px 14px 11px 38px;
            border: 1.5px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            background: #fafafa;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            font-family: var(--font-sans);
        }

        .field input:focus {
            border-color: #7F77DD;
            box-shadow: 0 0 0 3px rgba(127, 119, 221, 0.15);
            background: #fff;
        }

        .field input.valid {
            border-color: #1D9E75;
        }

        .field input.invalid {
            border-color: #E24B4A;
        }

        .field .icon {
            position: absolute;
            left: 11px;
            top: 35%;
            transform: translateY(-50%);
            font-size: 15px;
            pointer-events: none;
        }

        .field .toggle-pw {
            position: absolute;
            right: 11px;
            top: 40%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 13px;
            color: #888;
            user-select: none;
        }

        .field .toggle-pw:hover {
            color: #534AB7;
        }

        .field-hint {
            font-size: 11px;
            color: #E24B4A;
            margin-top: 3px;
            padding-left: 4px;
            min-height: 14px;
            transition: opacity 0.2s;
        }

        .btn-login {
            width: 100%;
            padding: 11px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-family: var(--font-serif);
            cursor: pointer;
            margin-top: 6px;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-login:hover {
            background: #34495e;
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        .btn-login:disabled {
            background: #aaa;
            cursor: not-allowed;
            transform: none;
        }

        .btn-login.loading {
            position: relative;
            color: transparent;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 16px;
            height: 16px;
            margin: -8px 0 0 -8px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 14px 0;
            color: #aaa;
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 0.5px;
            background: #ddd;
        }

        .btn-google {
            width: 100%;
            padding: 10px;
            background: #fff;
            color: #444;
            border: 1.5px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.2s, border-color 0.2s;
            font-family: var(--font-sans);
        }

        .btn-google:hover {
            background: #f9f9f9;
            border-color: #c44;
        }

        .btn-google svg {
            flex-shrink: 0;
        }

        .register-link {
            text-align: center;
            margin-top: 14px;
            font-size: 13px;
            color: #888;
        }

        .register-link a {
            color: #534AB7;
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .toast {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(60px);
            background: #1D9E75;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            opacity: 0;
            transition: opacity 0.3s, transform 0.3s;
            pointer-events: none;
            z-index: 99;
        }

        .toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        .toast.error {
            background: #E24B4A;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            color: #666;
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

    

    <!-- content -->
    <div class="page">
        <br><br>
        <div class="card">
            <p class="card-title">Login</p>

            <form action="login" method="post">

                <div class="field">
                    <span class="icon">👤</span>
                    <input type="text" id="username" name="username" placeholder="Enter Username" autocomplete="off">
                    <div class="field-hint" id="user-hint"></div>
                </div>

                <div class="field">
                    <span class="icon">🔒</span>
                    <input type="password" id="password" name="password" placeholder="Enter Password" autocomplete="off">
                    <span class="toggle-pw" id="toggle-pw" title="Show/hide password">Show</span>
                    <div class="field-hint" id="pw-hint"></div>
                </div>
<p class="register-link">Forgot password? <a href="reset_pass.php">Click here</a></p>
                <button type="submit" class="btn-login" id="btn-login">Login</button>

            </form>


            <div class="divider">or</div>

            <button class="btn-google" id="btn-google">
                <svg width="16" height="16" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.18 0 5.36 1.37 6.59 2.52l4.88-4.74C32.59 4.54 28.68 2.5 24 2.5 14.98 2.5 7.44 8.3 4.88 16.3l5.72 4.44C12.07 14.04 17.55 9.5 24 9.5z" />
                    <path fill="#4285F4" d="M46.5 24.5c0-1.6-.14-3.13-.4-4.6H24v8.7h12.66c-.56 2.93-2.22 5.41-4.7 7.07l7.2 5.59C43.54 37.04 46.5 31.24 46.5 24.5z" />
                    <path fill="#FBBC05" d="M10.6 28.26A14.53 14.53 0 0 1 9.5 24c0-1.48.26-2.91.72-4.26L4.5 15.3A22.5 22.5 0 0 0 1.5 24c0 3.64.87 7.07 2.4 10.13l6.7-5.87z" />
                    <path fill="#34A853" d="M24 45.5c4.54 0 8.35-1.5 11.14-4.07l-7.2-5.59c-1.52 1.02-3.47 1.63-5.94 1.63-6.42 0-11.87-4.5-13.86-10.6l-5.73 4.44C7.42 39.6 15 45.5 24 45.5z" />
                </svg>
                Continue with Google
            </button>

            <p class="register-link">No account? <a href="register.php">Register here</a></p>
        </div>
        <br><br><br>
    </div>

    <div class="toast" id="toast"></div>

    <footer>
        © <?php echo date("Y"); ?> ezComp • Compiler Learning Platform
    </footer>

    <script>
        const usernameEl = document.getElementById('username');
        const passwordEl = document.getElementById('password');
        const btnLogin = document.getElementById('btn-login');
        const togglePw = document.getElementById('toggle-pw');
        const userHint = document.getElementById('user-hint');
        const pwHint = document.getElementById('pw-hint');
        const toast = document.getElementById('toast');

        function showToast(msg, isError) {
            toast.textContent = msg;
            toast.className = 'toast' + (isError ? ' error' : '') + ' show';
            setTimeout(() => toast.className = 'toast', 2500);
        }

        function validate() {
            const u = usernameEl.value.trim();
            const p = passwordEl.value;
            let ok = true;

            if (u.length > 0 && u.length < 3) {
                usernameEl.className = 'invalid';
                userHint.textContent = 'Username must be at least 3 characters';
                ok = false;
            } else if (u.length >= 3) {
                usernameEl.className = 'valid';
                userHint.textContent = '';
            } else {
                usernameEl.className = '';
                userHint.textContent = '';
                ok = false;
            }

            if (p.length === 0) ok = false;
            if (p.length > 0 && p.length < 3) {
                pwHint.textContent = 'Password too short';
                ok = false;
            } else {
                pwHint.textContent = '';
            }

            btnLogin.disabled = !ok;
            return ok;
        }

        passwordEl.addEventListener('input', validate);
        usernameEl.addEventListener('input', validate);

        togglePw.addEventListener('click', () => {
            const isText = passwordEl.type === 'text';
            passwordEl.type = isText ? 'password' : 'text';
            togglePw.textContent = isText ? 'Show' : 'Hide';
        });

        btnLogin.addEventListener('click', (e) => {
            e.preventDefault(); // stop instant submit
            if (!validate()) return;

            btnLogin.classList.add('loading');
            btnLogin.disabled = true;
            showToast('Login successful! Redirecting…', false);

            setTimeout(() => {
                document.querySelector('form').submit(); // actually submit after toast
            }, 1500);
        });

        document.getElementById('btn-google').addEventListener('click', () => {
            showToast('Redirecting to Google…', false);
        });
    </script>
</body>

</html>