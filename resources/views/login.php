<?php
// login.php
session_start();

// Initialize tracking if not set
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
}
if (!isset($_SESSION['lockout_until'])) {
    $_SESSION['lockout_until'] = null;
}

$error = "";
$lockout_seconds = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // 🔒 Check if currently locked
    if ($_SESSION['lockout_until'] && time() < $_SESSION['lockout_until']) {
        $lockout_seconds = $_SESSION['lockout_until'] - time();
        $error = "LOCKED"; // marker for JS
    } else {
        // Reset lockout if expired
        if ($_SESSION['lockout_until'] && time() >= $_SESSION['lockout_until']) {
            $_SESSION['failed_attempts'] = 0;
            $_SESSION['lockout_until'] = null;
        }

        // ✅ Only validate credentials if not locked
        if ($username === "admin" && $password === "admin123") {
            $_SESSION['role'] = "admin";
            $_SESSION['failed_attempts'] = 0;
            $_SESSION['lockout_until'] = null;
            header("Location: admin_menu.php");
            exit();
        } elseif ($username === "cashier" && $password === "cashier123") {
            $_SESSION['role'] = "cashier";
            $_SESSION['failed_attempts'] = 0;
            $_SESSION['lockout_until'] = null;
            header("Location: cashier_menu.php");
            exit();
        } else {
            // ❌ Wrong credentials
            $_SESSION['failed_attempts']++;

            if ($_SESSION['failed_attempts'] >= 5) {
                $_SESSION['lockout_until'] = time() + 10; // 10 seconds lockout
                $lockout_seconds = 10;
                $error = "LOCKED";
            } else {
                $remaining = 5 - $_SESSION['failed_attempts'];
                $error = "Invalid username or password. Attempts left: $remaining";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Loyalty System</title>
    <style>
        body { background-color: #ebf0ff; font-family: 'Segoe UI', sans-serif; }
        .card { background: #fff; border-radius: 20px; padding: 30px; width: 350px;
                margin: 80px auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; font-weight: bold; }
        p.subtitle { text-align: center; color: gray; font-size: 14px; }
        label { font-size: 14px; display: block; margin-top: 15px; }
        input[type="text"], input[type="password"] {
            width: 100%; padding: 10px; font-size: 14px;
            border: 1px solid #ccc; border-radius: 5px; margin-top: 5px;
        }
        button { margin-top: 25px; width: 100%; padding: 12px;
                 background: black; color: white; font-weight: bold;
                 border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #333; }
        .error { color: red; text-align: center; margin-top: 15px; }
        .disabled { background: #999; cursor: not-allowed; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Maria Art's Loyalty System</h2>
        <p class="subtitle">Sign in to access the customer loyalty<br>management system</p>

        <?php if (!empty($error)): ?>
            <?php if ($error === "LOCKED"): ?>
                <div class="error">
                    Too many failed attempts. Please try again in 
                    <span id="countdown"></span>.
                </div>
            <?php else: ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
        <?php endif; ?>

        <form method="post" action="">
            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit" id="loginBtn">Sign In</button>
        </form>
    </div>

    <?php if ($error === "LOCKED"): ?>
    <script>
        let remaining = <?= $lockout_seconds ?>; // seconds from PHP
        const btn = document.getElementById("loginBtn");
        btn.classList.add("disabled");
        btn.disabled = true;

        function updateTimer() {
            let minutes = Math.floor(remaining / 60);
            let seconds = remaining % 60;
            document.getElementById("countdown").textContent =
                minutes + "m " + (seconds < 10 ? "0" : "") + seconds + "s";
            if (remaining > 0) {
                remaining--;
                setTimeout(updateTimer, 1000);
            } else {
                // Unlock when timer ends
                btn.classList.remove("disabled");
                btn.disabled = false;
                document.querySelector(".error").textContent = "You may now try again.";
            }
        }
        updateTimer();
    </script>
    <?php endif; ?>
</body>
</html>