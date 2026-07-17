<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Dashboard — Maria Art's Loyalty System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg: #f4f7fa; --surface: #ffffff; --surface-alt: #f8fafc;
            --text-primary: #0f172a; --text-secondary: #475569; --text-tertiary: #94a3b8;
            --brand: #10b981; --brand-light: #34d399; --brand-subtle: #d1fae5;
            --accent-green: #059669; --accent-green-bg: #d1fae5;
            --accent-orange: #ea580c; --accent-orange-bg: #ffedd5;
            --accent-blue: #2563eb; --accent-blue-bg: #dbeafe;
            --danger: #dc2626; --danger-bg: #fee2e2;
            --border: #e2e8f0;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.04);
            --shadow-lg: 0 12px 40px rgba(0,0,0,0.05);
            --radius-sm: 8px; --radius-md: 12px; --radius-lg: 18px;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg); color: var(--text-primary);
            min-height: 100vh; display: flex; flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        /* ─── NAV ─── */
        nav {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(12px);
            background: rgba(255,255,255,0.85);
        }

        nav .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--text-primary);
            letter-spacing: -0.01em;
        }

        nav .brand-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--brand);
            display: inline-block;
            flex-shrink: 0;
        }

        nav .nav-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .status-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            background: var(--accent-green-bg);
            color: #065f46;
            padding: 0.4rem 1rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        .status-badge .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--accent-green);
            display: inline-block;
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        .logout-btn {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-secondary);
            padding: 0.5rem 1.1rem;
            font-weight: 600;
            font-size: 0.8rem;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }

        .logout-btn:hover {
            background: var(--danger-bg);
            color: var(--danger);
            border-color: var(--danger);
        }

        .container { max-width: 1000px; margin: 2.5rem auto; padding: 0 1.5rem; width: 100%; }
        .welcome h1 { font-size: 1.7rem; font-weight: 800; margin-bottom: 0.3rem; }
        .welcome p { color: var(--text-secondary); font-size: 0.95rem; }
        .actions { display: grid; grid-template-columns: 1fr; gap: 1.25rem; margin-top: 2rem; max-width: 500px; }
        .action-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius-md); padding: 1.75rem;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .action-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.05); transform: translateY(-2px); }
        .action-card h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.4rem; }
        .action-card p { color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 1.25rem; }
        .action-card .btn {
            display: inline-block; background: var(--brand); color: #fff;
            text-decoration: none; padding: 0.65rem 1.25rem; border-radius: 6px;
            font-weight: 600; font-size: 0.85rem; transition: background 0.2s;
        }
        .action-card .btn:hover { background: var(--brand-light); }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 720px) {
            nav { padding: 0 1rem; }
            .status-badge { display: none; }
        }
    </style>
</head>
<body>
    <nav>
        <div class="brand">
            <span class="brand-dot"></span>
            Syarikat Perniagaan Maria Arts
        </div>
        <div class="nav-right">
            <div class="status-badge">
                <span class="dot"></span>
                {{ session('username') }} · Cashier
            </div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="logout-btn">Log Out</button>
            </form>
        </div>
    </nav>
    <div class="container">
        <div class="welcome">
            <h1>Welcome, {{ session('username') }}</h1>
            <p>Cashier operations panel — manage loyalty points.</p>
        </div>
        <div class="actions">
            <div class="action-card">
                <h3>Manage Loyalty Points</h3>
                <p>Add or redeem customer loyalty points.</p>
                <a href="/cashier/manage-points" class="btn">Open</a>
            </div>
        </div>
    </div>
</body>
</html>
