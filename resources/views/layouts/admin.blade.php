<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin — Maria Art\'s Loyalty System')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @yield('head_scripts')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg: #f4f7fa; --surface: #fff; --surface-alt: #f8fafc;
            --text-primary: #0f172a; --text-secondary: #475569; --text-tertiary: #94a3b8;
            --brand: #0284c7; --brand-light: #38bdf8; --brand-subtle: #e0f2fe;
            --accent-green: #059669; --accent-green-bg: #d1fae5;
            --accent-orange: #ea580c; --accent-orange-bg: #ffedd5;
            --accent-red: #dc2626; --accent-red-bg: #fee2e2;
            --accent-blue: #2563eb; --accent-blue-bg: #dbeafe;
            --accent-purple: #7c3aed; --accent-purple-bg: #ede9fe;
            --accent-amber: #d97706; --accent-amber-bg: #fef3c7;
            --border: #e2e8f0; --shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.04); --shadow-lg: 0 12px 40px rgba(0,0,0,0.05);
            --radius-sm: 8px; --radius-md: 12px; --radius-lg: 18px;
            --danger: #dc2626; --danger-bg: #fee2e2;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg); color: var(--text-primary);
            min-height: 100vh; -webkit-font-smoothing: antialiased;
        }

        nav {
            background: rgba(255,255,255,0.85); border-bottom: 1px solid var(--border);
            padding: 0 2rem; height: 68px; display: flex; align-items: center;
            justify-content: space-between; position: sticky; top: 0; z-index: 50;
            backdrop-filter: blur(12px);
        }
        nav .nav-left { display: flex; align-items: center; gap: 16px; }
        nav .brand { display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 1.05rem; color: var(--text-primary); letter-spacing: -0.01em; }
        nav .brand-dot { width: 10px; height: 10px; border-radius: 50%; background: var(--brand); display: inline-block; flex-shrink: 0; }
        nav .nav-right { display: flex; align-items: center; gap: 1rem; }
        .back-btn {
            display: inline-flex; align-items: center; gap: 6px;
            background: transparent; border: 1px solid var(--border);
            color: var(--text-secondary); padding: 0.5rem 1.1rem; font-weight: 600;
            font-size: 0.8rem; border-radius: var(--radius-sm); cursor: pointer;
            text-decoration: none; transition: all 0.2s; font-family: inherit;
        }
        .back-btn:hover { background: var(--brand-subtle); color: var(--brand); border-color: var(--brand); }
        .status-badge {
            display: flex; align-items: center; gap: 6px;
            background: var(--accent-green-bg); color: #065f46;
            padding: 0.4rem 1rem; border-radius: 9999px;
            font-size: 0.8rem; font-weight: 600;
        }
        .status-badge .dot { width: 7px; height: 7px; border-radius: 50%; background: var(--accent-green); display: inline-block; }
        .logout-btn {
            background: transparent; border: 1px solid var(--border);
            color: var(--text-secondary); padding: 0.5rem 1.1rem;
            font-weight: 600; font-size: 0.8rem; border-radius: var(--radius-sm);
            cursor: pointer; transition: all 0.2s; font-family: inherit;
        }
        .logout-btn:hover { background: var(--accent-red-bg); color: var(--accent-red); border-color: var(--accent-red); }
        .container { max-width: 1100px; margin: 2rem auto; padding: 0 1.5rem; width: 100%; }

        .alert { display:flex; align-items:center; gap:8px; padding:0.85rem 1rem; border-radius:var(--radius-sm); font-size:0.85rem; font-weight:500; margin-bottom:1.25rem; }
        .alert-success { background:var(--accent-green-bg); color:#065f46; }
        .alert-error { background:var(--accent-red-bg); color:#991b1b; }

        @media (max-width: 860px) {
            nav { padding: 0 1rem; }
            .container { padding: 0 1rem; }
        }
        @yield('styles')
    </style>
</head>
<body>
    <nav>
        <div class="nav-left">
            <div class="brand"><span class="brand-dot"></span>Syarikat Perniagaan Maria Arts</div>
        </div>
        <div class="nav-right">
            <div class="status-badge"><span class="dot"></span>{{ session('username') }} · Admin</div>
            <form method="POST" action="/logout">@csrf<button type="submit" class="logout-btn">Log Out</button></form>
        </div>
    </nav>

    <div class="container">
        @hasSection('showBack')
            <a href="/admin-dashboard" class="back-btn" style="margin-bottom:1.5rem;display:inline-flex;">&larr;</a>
        @endif
        @yield('content')
    </div>
</body>
</html>
