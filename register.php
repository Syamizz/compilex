<?php
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ezComp - Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+NZ+Basic:wght@100..400&display=swap" rel="stylesheet">

    <style>
        

        body {
            margin: 0;
            color: #374151;
            font-family: var(--font-sans);
            background: #FAFAF9;
        }

        #navbar {
            background-color: #FFD6FF;
        }

        #title {
            font-family: var(--font-serif);
        }

        .button-28 {
            appearance: none;
            background-color: transparent;
            border: 2px solid #1A1A1A;
            border-radius: 15px;
            color: #3B3B3B;
            cursor: pointer;
            display: inline-block;
            font-size: 10px;
            font-weight: 600;
            min-height: 30px;
            padding: 12px 20px;
            text-align: center;
            text-decoration: none;
            transition: all 300ms cubic-bezier(.23, 1, 0.32, 1);
            width: auto;
        }

        .button-28:hover {
            color: #fff;
            background-color: #1A1A1A;
            box-shadow: rgba(0, 0, 0, 0.25) 0 8px 15px;
            transform: translateY(-2px);
        }

        .login-box {
            width: 380px;
            margin: 60px auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(99, 102, 241, 0.08), 0 1px 4px rgba(0, 0, 0, 0.06);
        }

        .login-box h2 {
            text-align: center;
            font-family: var(--font-sans);
            font-size: 20px;
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 4px;
        }

        .login-box p.sub {
            text-align: center;
            font-size: 13px;
            color: #6B7280;
            margin-bottom: 1.5rem;
        }

        .error-box {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FCA5A5;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 13px;
            margin-bottom: 1rem;
        }

        .rg-progress {
            display: flex;
            gap: 4px;
            margin-bottom: 1.5rem;
        }

        .rg-step {
            height: 3px;
            flex: 1;
            border-radius: 99px;
            background: #E5E7EB;
            transition: background 0.35s ease;
        }

        .rg-step.active {
            background: #6366F1;
        }

        .rg-field {
            position: relative;
            margin-bottom: 1rem;
        }

        .rg-field input {
            width: 100%;
            height: 48px;
            padding: 18px 12px 6px;
            font-size: 14px;
            font-family: var(--font-sans);
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            color: #111827;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .rg-field.password-field input {
            padding-right: 58px;
        }

        .rg-field input:focus {
            border-color: #818CF8;
            background: #fff;
            box-shadow: 0 0 0 3px #EEF2FF;
        }

        .rg-field input.valid {
            border-color: #6EE7B7;
        }

        .rg-field input.invalid {
            border-color: #EF4444;
        }

        .rg-label {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: #9CA3AF;
            pointer-events: none;
            transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: left top;
        }

        .rg-field input:focus ~ .rg-label,
        .rg-field input.has-val ~ .rg-label {
            top: 10px;
            transform: translateY(0) scale(0.78);
            color: #6366F1;
        }

        .eye-btn {
            position: absolute;
            right: 10px;
            top: 24px;
            transform: translateY(-50%);
            width: 42px;
            height: 24px;
            padding: 0;
            margin: 0;
            background: transparent;
            border: none;
            color: #6B7280;
            cursor: pointer;
            font-size: 11px;
            line-height: 1;
        }

        .eye-btn:hover {
            color: #4F46E5;
        }

        .rg-error {
            display: block;
            min-height: 14px;
            margin-top: 4px;
            font-size: 11px;
            color: #EF4444;
        }

        .rg-submit {
            width: 100%;
            height: 44px;
            margin-top: 0.5rem;
            background: #6366F1;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: var(--font-sans);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.12s;
        }

        .rg-submit:hover {
            background: #4F46E5;
            transform: translateY(-1px);
        }

        footer {
            text-align: center;
            padding: 20px;
            background: #FFFFFF;
            margin-top: 40px;
            font-size: 14px;
            color: #6B7280;
        }
    </style>
</head>

<body>
    <nav id="navbar" class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a id="title" class="navbar-brand" href="#">CompileX</a>

            <div class="collapse navbar-collapse">
                <div class="ms-auto">
                    <a href="index" class="button-28">LOGIN</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="login-box">
        <h2>Create your account</h2>
        <p class="sub">Start learning compiler principles today.</p>

        <?php if ($error != ''): ?>
            <div class="error-box"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="rg-progress">
            <div class="rg-step" id="s1"></div>
            <div class="rg-step" id="s2"></div>
            <div class="rg-step" id="s3"></div>
            <div class="rg-step" id="s4"></div>
        </div>

        <form method="POST" action="register_process">
            <div class="rg-field">
                <input type="text" name="username" id="f-username" required autocomplete="off">
                <label class="rg-label" for="f-username">Username</label>
            </div>

            <div class="rg-field">
                <input type="text" name="fullname" id="f-fullname" required autocomplete="off">
                <label class="rg-label" for="f-fullname">Full name</label>
            </div>

            <div class="rg-field">
                <input type="email" name="email" id="f-email" required autocomplete="off">
                <label class="rg-label" for="f-email">Email address</label>
            </div>

            <div class="rg-field password-field">
                <input type="password" name="password" id="f-password" required>
                <label class="rg-label" for="f-password">Password</label>
                <button type="button" class="eye-btn" data-target="f-password">Show</button>
                <span class="rg-error" id="password-error"></span>
            </div>

            <div class="rg-field password-field">
                <input type="password" name="confirm_password" id="f-confirm-password" required>
                <label class="rg-label" for="f-confirm-password">Retype password</label>
                <button type="button" class="eye-btn" data-target="f-confirm-password">Show</button>
                <span class="rg-error" id="confirm-password-error"></span>
            </div>

            <button type="submit" class="rg-submit">Register</button>
        </form>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> CompileX - Compiler Learning Platform
    </footer>

    <script>
        const passwordInput = document.getElementById('f-password');
        const confirmPasswordInput = document.getElementById('f-confirm-password');
        const passwordError = document.getElementById('password-error');
        const confirmPasswordError = document.getElementById('confirm-password-error');

        const fields = [
            {
                id: 'f-username',
                step: 's1',
                validate: v => v.trim().length >= 3
            },
            {
                id: 'f-fullname',
                step: 's2',
                validate: v => v.trim().length >= 2
            },
            {
                id: 'f-email',
                step: 's3',
                validate: v => /^[^@]+@[^@]+\.[^@]+$/.test(v.trim())
            },
            {
                id: 'f-password',
                step: 's4',
                validate: v => v.length >= 6
            }
        ];

        function validatePasswords() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            passwordError.textContent = '';
            confirmPasswordError.textContent = '';

            passwordInput.classList.remove('invalid');
            confirmPasswordInput.classList.remove('invalid');

            if (password.length > 0 && password.length < 6) {
                passwordError.textContent = 'Password must be at least 6 characters';
                passwordInput.classList.add('invalid');
            }

            if (confirmPassword.length > 0 && password !== confirmPassword) {
                confirmPasswordError.textContent = 'Password does not match';
                confirmPasswordInput.classList.add('invalid');
            }

            confirmPasswordInput.classList.toggle(
                'valid',
                confirmPassword.length >= 6 && password === confirmPassword
            );
        }

        fields.forEach(({ id, step, validate }) => {
            const input = document.getElementById(id);

            input.addEventListener('input', () => {
                input.classList.toggle('has-val', input.value.length > 0);
                input.classList.toggle('valid', validate(input.value));
                document.getElementById(step).classList.toggle('active', validate(input.value));

                if (id === 'f-password') {
                    validatePasswords();
                }
            });
        });

        confirmPasswordInput.addEventListener('input', () => {
            confirmPasswordInput.classList.toggle('has-val', confirmPasswordInput.value.length > 0);
            validatePasswords();
        });

        document.querySelectorAll('.eye-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = document.getElementById(btn.dataset.target);
                const showPassword = input.type === 'password';

                input.type = showPassword ? 'text' : 'password';
                btn.textContent = showPassword ? 'Hide' : 'Show';
            });
        });

        document.querySelector('form').addEventListener('submit', (e) => {
            validatePasswords();

            if (passwordInput.value.length < 6 || passwordInput.value !== confirmPasswordInput.value) {
                e.preventDefault();
            }
        });
    </script>
</body>

</html>