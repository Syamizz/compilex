<?php
session_start();

if (!isset($_SESSION['user_id'], $_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$error = $_POST['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CompileX - Change Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Syne', sans-serif;
            background: #ffffff;
            margin: 0;
        }

        #navbar {
            background-color: #18181B;
        }

        #navbar a,
        #navbar .nav-link {
            color: #FFFFFF;
        }

        #navbar a:hover,
        #navbar .nav-link:hover {
            color: #A1A1AA;
        }

        .button-28 {
            appearance: none;
            background-color: transparent;
            border: 2px solid #f7f3f3;
            border-radius: 15px;
            color: #504141;
            cursor: pointer;
            display: inline-block;
            font-size: 10px;
            font-weight: 600;
            min-height: 30px;
            outline: none;
            padding: 12px 20px;
            text-align: center;
            text-decoration: none;
            transition: all 300ms cubic-bezier(.23, 1, 0.32, 1);
        }

        .button-28:hover {
            color: #fff;
            background-color: #1A1A1A;
            box-shadow: rgba(0, 0, 0, 0.25) 0 8px 15px;
            transform: translateY(-2px);
        }

        .login-box {
            width: 400px;
            margin: 60px auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(99, 102, 241, 0.08), 0 1px 4px rgba(0,0,0,0.06);
        }

        .login-box h2 {
            text-align: center;
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

        .success-box {
            background: #D1FAE5;
            color: #065F46;
            border: 1px solid #6EE7B7;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 13px;
            margin-bottom: 1rem;
        }

        .rg-field {
            position: relative;
            margin-bottom: 1rem;
        }

        .rg-field input {
            width: 100%;
            height: 48px;
            padding: 18px 44px 6px 12px;
            font-size: 14px;
            font-family: 'Syne', sans-serif;
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            color: #111827;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .rg-field input:focus {
            border-color: #818CF8;
            background: #fff;
            box-shadow: 0 0 0 3px #EEF2FF;
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
            background: transparent;
            border: none;
            color: #6B7280;
            cursor: pointer;
            font-size: 11px;
            line-height: 1;
        }

        .eye-btn:hover { color: #4F46E5; }

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
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.12s;
        }

        .rg-submit:hover {
            background: #4F46E5;
            transform: translateY(-1px);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 14px;
            font-size: 13px;
            color: #6366F1;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover { text-decoration: underline; }

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

<?php
include "dashboard.php";
?>
<br><br><br><br>

<div class="login-box">
    <h2>Change Password</h2>
    <p class="sub">Enter and confirm your new password.</p>

    <?php if ($error !== ''): ?>
        <div class="error-box"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>


    <form method="POST" action="change_password_process.php" id="cpForm">

        <div class="rg-field">
            <input type="password" name="new_password" id="f-new" required>
            <label class="rg-label" for="f-new">New Password</label>
            <button type="button" class="eye-btn" data-target="f-new">Show</button>
            <span class="rg-error" id="new-error"></span>
        </div>

        <div class="rg-field">
            <input type="password" name="confirm_password" id="f-confirm" required>
            <label class="rg-label" for="f-confirm">Confirm New Password</label>
            <button type="button" class="eye-btn" data-target="f-confirm">Show</button>
            <span class="rg-error" id="confirm-error"></span>
        </div>

        <button type="submit" class="rg-submit">Update Password</button>
    </form>

    <a href="profile.php" class="back-link">Back to Profile</a>
</div>

<footer>
    &copy; <?= date("Y") ?> CompileX - Compiler Learning Platform
</footer>

<script>
    const fNew     = document.getElementById('f-new');
    const fConfirm = document.getElementById('f-confirm');
    const newErr   = document.getElementById('new-error');
    const conErr   = document.getElementById('confirm-error');

    // Floating label
    [fNew, fConfirm].forEach(input => {
        input.addEventListener('input', () => {
            input.classList.toggle('has-val', input.value.length > 0);
        });
    });

    // Show/hide password
    document.querySelectorAll('.eye-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.target);
            const show  = input.type === 'password';
            input.type  = show ? 'text' : 'password';
            btn.textContent = show ? 'Hide' : 'Show';
        });
    });

    // Live validation
    function validate() {
        let ok = true;

        newErr.textContent = '';
        conErr.textContent = '';
        fNew.classList.remove('invalid');
        fConfirm.classList.remove('invalid');

        if (fNew.value.length > 0 && fNew.value.length < 8) {
            newErr.textContent = 'Password must be at least 8 characters.';
            fNew.classList.add('invalid');
            ok = false;
        }

        if (fConfirm.value.length > 0 && fNew.value !== fConfirm.value) {
            conErr.textContent = 'Passwords do not match.';
            fConfirm.classList.add('invalid');
            ok = false;
        }

        return ok;
    }

    fNew.addEventListener('input', validate);
    fConfirm.addEventListener('input', validate);

    document.getElementById('cpForm').addEventListener('submit', e => {
        if (!validate()) { e.preventDefault(); return; }

        if (fNew.value === '' || fConfirm.value === '') {
            e.preventDefault();
            newErr.textContent = 'All fields are required.';
        }
    });
</script>
</body>
</html>
