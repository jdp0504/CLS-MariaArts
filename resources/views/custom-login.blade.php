<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Maria Art's Loyalty System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --brand: #2563eb;
            --brand-hover: #1d4ed8;
            --surface: #ffffff;
            --text-primary: #0b1120;
            --text-secondary: #5a6a85;
            --text-tertiary: #94a3b8;
            --border: #e9edf4;
            --danger: #ef4444;
            --danger-bg: #fee2e2;
            --radius: 8px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
            background: #f8fafc;
        }

        .brand-panel {
            flex: 1;
            background: url('/img/MariaArts.jpeg') center/cover no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.4);
        }

        .brand-panel::after {
            content: none;
        }

        .brand-content {
            position: relative;
            z-index: 1;
            max-width: 440px;
            text-align: center;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 16px;
            padding: 2.5rem 2rem;
        }

        .brand-icon {
            width: 72px;
            height: 72px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .brand-content h1 {
            color: #fff;
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 0.75rem;
        }

        .brand-content p {
            color: rgba(255,255,255,0.7);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .brand-features {
            margin-top: 2.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            text-align: left;
        }

        .brand-feature {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.8);
            font-size: 0.88rem;
        }

        .brand-feature .feat-icon {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.9rem;
        }

        .form-panel {
            width: 480px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
            background: var(--surface);
        }

        .form-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .form-header-text h2 {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 0.35rem;
        }

        .form-header-text p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .staff-tab {
            font-size: 0.7rem;
            font-weight: 700;
            font-family: inherit;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: var(--text-tertiary);
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 0.3rem 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .staff-tab:hover {
            border-color: var(--text-tertiary);
            color: var(--text-secondary);
        }

        .staff-tab.active {
            border-color: var(--brand);
            color: var(--brand);
            background: rgba(37,99,235,0.06);
        }

        .form-group {
            margin-bottom: 1.15rem;
        }

        label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.35rem;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem 0.9rem;
            font-size: 0.9rem;
            font-family: inherit;
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            background: #f8fafc;
            color: var(--text-primary);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
            background: var(--surface);
        }

        button[type="submit"] {
            width: 100%;
            padding: 0.82rem;
            font-size: 0.9rem;
            font-weight: 700;
            font-family: inherit;
            background: var(--brand);
            color: #fff;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        }

        button[type="submit"]:hover:not(:disabled) {
            background: var(--brand-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(0,0,0,0.1);
        }

        button[type="submit"]:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .form-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-tertiary);
        }

        .form-footer a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 600;
        }

        .form-footer a:hover {
            color: var(--text-primary);
        }

        .error {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            background: var(--danger-bg);
            color: #b91c1c;
            padding: 0.85rem 1rem;
            border-radius: var(--radius);
            font-size: 0.84rem;
            font-weight: 500;
            line-height: 1.45;
            margin-bottom: 1.25rem;
        }

        .error-icon {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--danger);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
            margin-top: 1px;
        }

        .countdown-note {
            font-weight: 600;
        }

        @media (max-width: 860px) {
            body { flex-direction: column; }
            .brand-panel {
                flex: none;
                padding: 2.5rem 1.5rem;
            }
            .brand-content { max-width: 100%; }
            .brand-features { display: none; }
            .form-panel {
                width: 100%;
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>

    <div class="brand-panel">
        <div class="brand-content">
            <h1>Maria Art's<br>Loyalty Programme</h1>
        </div>
    </div>

    <div class="form-panel">
        <div class="form-header">
            <div class="form-header-text">
                <h2 id="formTitle">Sign In</h2>
                <p id="formSubtitle">Enter your credentials.</p>
            </div>
            <button type="button" class="staff-tab" id="staffTab">Staff</button>
        </div>

        @if (session('error'))
            <div class="error">
                <span class="error-icon">!</span>
                <span>
                    @if (session('error') === 'LOCKED')
                        Too many failed attempts. Please try again in
                        <span class="countdown-note" id="countdown"></span>.
                    @else
                        {{ session('error') }}
                    @endif
                </span>
            </div>
        @endif

        <form method="POST" action="/my-login" id="loginForm">
            @csrf

            <input type="hidden" name="role" id="roleInput" value="customer">

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required autocomplete="current-password">
            </div>

            <button type="submit" id="loginBtn">Sign In</button>
        </form>

        <div class="form-footer" style="margin-top:1rem;">
            <a href="/forgot-password" style="color:var(--brand);font-size:0.8rem;">Forgot Password?</a>
        </div>

        <div class="form-footer">
            Not a member? <a href="/register">Join Loyalty Program</a>
        </div>
    </div>

    <script>
        (function() {
            const staffTab = document.getElementById('staffTab');
            const roleInput = document.getElementById('roleInput');
            const formTitle = document.getElementById('formTitle');
            const formSubtitle = document.getElementById('formSubtitle');
            let isStaff = false;

            function enterStaffMode() {
                isStaff = true;
                staffTab.classList.add('active');
                roleInput.value = 'staff';
                formTitle.textContent = 'Staff Access';
                formSubtitle.textContent = 'Sign in as staff.';
            }

            function enterCustomerMode() {
                isStaff = false;
                staffTab.classList.remove('active');
                roleInput.value = 'customer';
                formTitle.textContent = 'Sign In';
                formSubtitle.textContent = 'Enter your credentials.';
            }

            staffTab.addEventListener('click', function() {
                if (isStaff) {
                    enterCustomerMode();
                } else {
                    enterStaffMode();
                }
            });
        })();
    </script>

    @if (session('error') === 'LOCKED')
    <script>
        let remaining = {{ session('lockout_seconds') }};
        const loginBtn = document.getElementById("loginBtn");
        loginBtn.disabled = true;

        function updateTimer() {
            let minutes = Math.floor(remaining / 60);
            let seconds = remaining % 60;
            document.getElementById("countdown").textContent =
                minutes + "m " + (seconds < 10 ? "0" : "") + seconds + "s";
            if (remaining > 0) {
                remaining--;
                setTimeout(updateTimer, 1000);
            } else {
                loginBtn.disabled = false;
                const el = document.querySelector(".error");
                if (el) el.innerHTML =
                    '<span class="error-icon">!</span><span>You may now try again.</span>';
            }
        }
        updateTimer();
    </script>
    @endif
</body>
</html>
