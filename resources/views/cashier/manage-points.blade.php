<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Points — Maria Art's</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        :root { --bg:#f0f2f8; --surface:#fff; --text-primary:#0b1120; --text-secondary:#5a6a85; --text-tertiary:#94a3b8; --brand:#10b981; --brand-subtle:#d1fae5; --danger:#ef4444; --danger-bg:#fee2e2; --border:#e9edf4; --radius:8px; }
        body { font-family:'Inter',sans-serif; background:var(--bg); color:var(--text-primary); min-height:100vh; -webkit-font-smoothing:antialiased; }
        nav { background:var(--surface); border-bottom:1px solid var(--border); padding:0 2rem; height:64px; display:flex; align-items:center; justify-content:space-between; }
        nav .brand { font-weight:700; font-size:1rem; }
        nav .nav-right { display:flex; align-items:center; gap:1rem; }
        .role-badge { background:var(--brand-subtle); color:#065f46; padding:0.35rem 0.9rem; border-radius:9999px; font-size:0.8rem; font-weight:600; }
        .logout-btn { background:transparent; border:1px solid var(--border); color:var(--text-secondary); padding:0.45rem 1rem; font-weight:600; font-size:0.8rem; border-radius:6px; cursor:pointer; font-family:inherit; }
        .logout-btn:hover { background:#fee2e2; color:#ef4444; border-color:#ef4444; }
        .container { max-width:840px; margin:2.5rem auto; padding:0 1.5rem; }
        .back-link { display:inline-flex; align-items:center; gap:6px; color:var(--text-secondary); text-decoration:none; font-size:0.85rem; font-weight:600; margin-bottom:1.5rem; }
        .back-link:hover { color:var(--text-primary); }
        .grid { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; }
        .card { background:var(--surface); border:1px solid var(--border); border-radius:12px; padding:1.75rem; }
        .card h3 { font-size:1.05rem; font-weight:700; margin-bottom:0.3rem; }
        .card .subtitle { color:var(--text-secondary); font-size:0.82rem; margin-bottom:1.25rem; }
        .form-group { margin-bottom:1rem; }
        label { display:block; font-size:0.82rem; font-weight:600; color:var(--text-secondary); margin-bottom:0.35rem; }
        input, select { width:100%; padding:0.65rem 0.85rem; font-size:0.88rem; font-family:inherit; border:1.5px solid var(--border); border-radius:var(--radius); background:#f8fafc; outline:none; }
        input:focus, select:focus { border-color:var(--brand); box-shadow:0 0 0 3px rgba(16,185,129,0.1); background:var(--surface); }
        .btn { width:100%; padding:0.7rem; font-size:0.85rem; font-weight:700; font-family:inherit; border:none; border-radius:var(--radius); cursor:pointer; }
        .btn-primary { background:var(--brand); color:#fff; }
        .btn-primary:hover { background:#059669; }
        .btn-warning { background:#f59e0b; color:#fff; }
        .btn-warning:hover { background:#d97706; }
        .alert { padding:0.85rem 1rem; border-radius:var(--radius); font-size:0.84rem; font-weight:500; margin-bottom:1.25rem; }
        .alert-success { background:var(--brand-subtle); color:#065f46; }
        .alert-error { background:var(--danger-bg); color:#b91c1c; }
        .reward-list { margin-top:1.5rem; }
        .reward-item { display:flex; justify-content:space-between; align-items:center; padding:0.6rem 0; border-bottom:1px solid var(--border); font-size:0.85rem; }
        .reward-item:last-child { border-bottom:none; }
        .reward-item .name { font-weight:600; }
        .reward-item .meta { color:var(--text-tertiary); font-size:0.8rem; }
        @media (max-width:720px) { .grid { grid-template-columns:1fr; } }
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
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        <a href="/cashier-dashboard" class="back-link">&larr; Back to Dashboard</a>

        <div class="grid">
            {{-- Add Points --}}
            <div class="card">
                <h3>Add Points</h3>
                <p class="subtitle">Record a purchase and award points.</p>
                <form method="POST" action="/cashier/manage-points/add">
                    @csrf
                    <div class="form-group">
                        <label>Customer ID</label>
                        <input type="text" name="customerID" placeholder="e.g. CUS001" required>
                    </div>
                    <div class="form-group">
                        <label>Total Purchase (RM)</label>
                        <input type="number" step="0.01" name="totalPrice" placeholder="0.00" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Points</button>
                </form>
            </div>

            {{-- Redeem Points --}}
            <div class="card">
                <h3>Redeem Points</h3>
                <p class="subtitle">Redeem a reward for a customer.</p>
                <form method="POST" action="/cashier/manage-points/redeem">
                    @csrf
                    <div class="form-group">
                        <label>Customer ID</label>
                        <input type="text" name="customerID" placeholder="e.g. CUS001" required>
                    </div>
                    <div class="form-group">
                        <label>Reward</label>
                        <select name="rewardID" required>
                            <option value="">-- Select Reward --</option>
                            @foreach ($rewards as $r)
                                <option value="{{ $r->rewardID }}">{{ $r->rewardName }} ({{ $r->pointRequired }} pts &middot; {{ $r->stock }} left)</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning">Redeem</button>
                </form>

                @if ($rewards->count() > 0)
                    <div class="reward-list">
                        <p style="font-size:0.8rem;font-weight:600;color:var(--text-tertiary);text-transform:uppercase;margin-bottom:0.3rem;">Active Rewards</p>
                        @foreach ($rewards as $r)
                            <div class="reward-item">
                                <div>
                                    <span class="name">{{ $r->rewardName }}</span>
                                    <span class="meta"> &middot; Stock: {{ $r->stock }}</span>
                                </div>
                                <span style="font-weight:700;">{{ $r->pointRequired }} pts</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
