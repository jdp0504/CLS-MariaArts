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
            --bg: #f0f2f8; --surface: #fff; --text-primary: #0b1120;
            --text-secondary: #5a6a85; --text-tertiary: #94a3b8;
            --brand: #10b981; --brand-light: #34d399; --brand-subtle: #d1fae5;
            --border: #e9edf4; --radius: 12px;
        }
        body {
            font-family: 'Inter', sans-serif; background: var(--bg);
            color: var(--text-primary); min-height: 100vh;
            display: flex; flex-direction: column; -webkit-font-smoothing: antialiased;
        }
        nav {
            background: var(--surface); border-bottom: 1px solid var(--border);
            padding: 0 2rem; height: 64px; display: flex;
            align-items: center; justify-content: space-between;
        }
        nav .brand { font-weight: 700; font-size: 1rem; }
        nav .nav-right { display: flex; align-items: center; gap: 1rem; }
        .role-badge {
            background: var(--brand-subtle); color: #065f46;
            padding: 0.35rem 0.9rem; border-radius: 9999px;
            font-size: 0.8rem; font-weight: 600;
        }
        .logout-btn {
            background: transparent; border: 1px solid var(--border);
            color: var(--text-secondary); padding: 0.45rem 1rem;
            font-weight: 600; font-size: 0.8rem; border-radius: 6px;
            cursor: pointer; font-family: inherit;
            transition: all 0.2s;
        }
        .logout-btn:hover { background: #fee2e2; color: #ef4444; border-color: #ef4444; }
        .container { max-width: 1000px; margin: 2.5rem auto; padding: 0 1.5rem; width: 100%; }
        .welcome h1 { font-size: 1.7rem; font-weight: 800; margin-bottom: 0.3rem; }
        .welcome p { color: var(--text-secondary); font-size: 0.95rem; }
        .actions { display: grid; grid-template-columns: 1fr; gap: 1.25rem; margin-top: 2rem; max-width: 500px; }
        .action-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 1.75rem;
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
    </style>
</head>
<body>
    <nav>
        <div class="brand">Syarikat Perniagaan Maria Arts</div>
        <div class="nav-right">
            <span class="role-badge">Cashier</span>
            <form method="POST" action="/logout">@csrf<button type="submit" class="logout-btn">Log Out</button></form>
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
