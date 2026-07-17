<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — Maria Art's Loyalty System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg: #f4f7fa;
            --surface: #ffffff;
            --surface-alt: #f8fafc;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-tertiary: #94a3b8;
            --brand: #0284c7;
            --brand-light: #38bdf8;
            --brand-subtle: #e0f2fe;
            --accent-green: #059669;
            --accent-green-bg: #d1fae5;
            --accent-orange: #ea580c;
            --accent-orange-bg: #ffedd5;
            --accent-blue: #2563eb;
            --accent-blue-bg: #dbeafe;
            --danger: #dc2626;
            --danger-bg: #fee2e2;
            --border: #e2e8f0;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.04);
            --shadow-lg: 0 12px 40px rgba(0,0,0,0.05);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 18px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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

        /* ─── CONTAINER ─── */
        .container {
            max-width: 1040px;
            margin: 2.5rem auto;
            padding: 0 1.5rem;
            flex-grow: 1;
            width: 100%;
        }

        /* ─── HERO ─── */
        .welcome-hero {
            margin-bottom: 2.25rem;
        }

        .welcome-hero h1 {
            font-size: 1.85rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-primary);
            margin-bottom: 0.4rem;
        }

        .welcome-hero h1 span {
            background: linear-gradient(135deg, var(--brand), var(--brand-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-hero p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        /* ─── STATS ROW ─── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
            margin-bottom: 2.5rem;
        }

        .stat-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            transition: box-shadow 0.25s, transform 0.25s;
        }

        .stat-box:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }

        .stat-box span.label {
            font-size: 0.8rem;
            color: var(--text-tertiary);
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 0.4rem;
        }

        .stat-box span.value {
            font-size: 1.65rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .stat-box span.value.pending {
            color: var(--text-tertiary);
        }

        /* ─── MODULES GRID ─── */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .module-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 2rem;
            display: flex;
            flex-direction: column;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .module-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--brand);
            opacity: 0;
            transition: opacity 0.25s;
        }

        .module-card:hover::before {
            opacity: 1;
        }

        .module-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-3px);
            border-color: transparent;
        }

        .module-card .card-head {
            display: flex;
            align-items: center;
            margin-bottom: 0.8rem;
        }

        .module-card h3 {
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: -0.01em;
            color: var(--text-primary);
        }

        .module-card p {
            color: var(--text-secondary);
            font-size: 0.88rem;
            line-height: 1.55;
            margin-bottom: 1.75rem;
            flex-grow: 1;
        }

        .module-card a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: var(--brand);
            color: #fff;
            text-decoration: none;
            padding: 0.72rem;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.85rem;
            transition: background 0.2s, transform 0.15s;
        }

        .module-card a:hover {
            background: var(--brand-light);
            transform: scale(1.01);
        }

        .module-card a .arrow {
            transition: transform 0.2s;
        }

        .module-card a:hover .arrow {
            transform: translateX(3px);
        }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 720px) {
            .stats-row {
                grid-template-columns: 1fr;
            }
            .modules-grid {
                grid-template-columns: 1fr;
            }
            nav {
                padding: 0 1rem;
            }
            .status-badge {
                display: none;
            }
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
                {{ session('username') }} · Admin
            </div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="logout-btn">Log Out</button>
            </form>
        </div>
    </nav>

    <div class="container">

        <div class="welcome-hero">
            <h1>Welcome back, <span>{{ session('username') }}</span></h1>
            <p>Select a management module below to execute operations.</p>
        </div>

        <div class="stats-row">
            <div class="stat-box">
                <span class="label">Active Users</span>
                <span class="value">{{ $totalMembers }}</span>
            </div>
            <div class="stat-box">
                <span class="label">Active Rewards</span>
                <span class="value">{{ $activeRewards }}</span>
            </div>
            <div class="stat-box">
                <span class="label">Claims This Month</span>
                <span class="value">{{ $claimsThisMonth }}</span>
            </div>
        </div>

        <div class="modules-grid">

            <div class="module-card">
                <div class="card-head">
                    <h3>Manage Membership Status</h3>
                </div>
                <p>Browse customer profiles, filter by name or status, and archive inactive accounts.</p>
                <a href="/admin/manage-membership">
                    Manage Status
                    <span class="arrow">→</span>
                </a>
            </div>

            <div class="module-card">
                <div class="card-head">
                    <h3>Manage Rewards</h3>
                </div>
                <p>Add, update, or archive rewards in the loyalty system.</p>
                <a href="/admin/manage-rewards">
                    Manage Rewards
                    <span class="arrow">→</span>
                </a>
            </div>

            <div class="module-card">
                <div class="card-head">
                    <h3>Notifications</h3>
                </div>
                <p>Broadcast system status updates, send custom text promotions, or schedule automatic alert announcements.</p>
                <a href="/admin/generate-notification">
                    Generate Notification
                    <span class="arrow">→</span>
                </a>
            </div>

            <div class="module-card">
                <div class="card-head">
                    <h3>View Report</h3>
                </div>
                <p>View comprehensive data reports on customer loyalty points, reward redemptions, and member activity.</p>
                <a href="/admin/generate-report">
                    View Reports
                    <span class="arrow">→</span>
                </a>
            </div>

        </div>
    </div>

</body>
</html>