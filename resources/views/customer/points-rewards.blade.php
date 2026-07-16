<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Points & Rewards — Maria Art's</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        :root {
            --bg:#f0f2f8; --surface:#fff; --text-primary:#0b1120; --text-secondary:#5a6a85; --text-tertiary:#94a3b8;
            --brand:#f59e0b; --brand-light:#fbbf24; --brand-subtle:#fef3c7;
            --accent-green:#059669; --accent-green-bg:#d1fae5;
            --accent-red:#dc2626; --accent-red-bg:#fee2e2;
            --border:#e9edf4; --radius-sm:8px; --radius-md:12px;
        }
        body { font-family:'Inter',sans-serif; background:var(--bg); color:var(--text-primary); min-height:100vh; -webkit-font-smoothing:antialiased; }
        nav { background:var(--surface); border-bottom:1px solid var(--border); padding:0 2rem; height:64px; display:flex; align-items:center; justify-content:space-between; }
        nav .brand { font-weight:700; font-size:1rem; }
        nav .nav-right { display:flex; align-items:center; gap:1rem; }
        .role-badge { background:var(--brand-subtle); color:#92400e; padding:0.35rem 0.9rem; border-radius:9999px; font-size:0.8rem; font-weight:600; }
        .logout-btn { background:transparent; border:1px solid var(--border); color:var(--text-secondary); padding:0.45rem 1rem; font-weight:600; font-size:0.8rem; border-radius:6px; cursor:pointer; font-family:inherit; transition:all .2s; }
        .logout-btn:hover { background:var(--accent-red-bg); color:var(--accent-red); border-color:var(--accent-red); }
        .container { max-width:860px; margin:2.5rem auto; padding:0 1.5rem; }
        .back-link { display:inline-flex; align-items:center; gap:6px; color:var(--text-secondary); text-decoration:none; font-size:0.85rem; font-weight:600; margin-bottom:1.5rem; }
        .back-link:hover { color:var(--text-primary); }

        .points-hero { background:linear-gradient(135deg,#f59e0b,#fbbf24); border-radius:var(--radius-md); padding:2rem; margin-bottom:1.5rem; color:#fff; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; }
        .points-hero .pts-label { font-size:0.85rem; font-weight:600; opacity:0.85; text-transform:uppercase; letter-spacing:0.04em; }
        .points-hero .pts-value { font-size:2.5rem; font-weight:800; letter-spacing:-0.03em; line-height:1.1; }
        .points-hero .pts-stats { display:flex; gap:2rem; }
        .points-hero .pts-stat { text-align:center; }
        .points-hero .pts-stat .num { font-size:1.3rem; font-weight:700; }
        .points-hero .pts-stat .lbl { font-size:0.75rem; opacity:0.8; }

        .section-title { font-size:1.1rem; font-weight:700; margin-bottom:1rem; }

        .rewards-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(240px,1fr)); gap:1rem; margin-bottom:2rem; }
        .reward-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-md); padding:1.25rem; transition:box-shadow .2s,transform .2s; }
        .reward-card:hover { box-shadow:0 4px 16px rgba(0,0,0,0.04); transform:translateY(-2px); }
        .reward-card h4 { font-size:1rem; font-weight:700; margin-bottom:0.3rem; }
        .reward-card .pts { font-size:0.85rem; color:var(--text-secondary); margin-bottom:0.6rem; }
        .reward-card .pts strong { color:var(--brand); }
        .reward-card .stock { font-size:0.78rem; color:var(--text-tertiary); }
        .elig-badge { display:inline-block; padding:0.25rem 0.65rem; border-radius:9999px; font-size:0.72rem; font-weight:700; margin-top:0.5rem; }
        .elig-yes { background:var(--accent-green-bg); color:var(--accent-green); }
        .elig-no { background:#f1f5f9; color:var(--text-tertiary); }

        .history-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-md); overflow:hidden; }
        table { width:100%; border-collapse:collapse; }
        thead { background:#f8fafc; }
        th { text-align:left; padding:0.75rem 1rem; font-size:0.75rem; font-weight:700; color:var(--text-tertiary); text-transform:uppercase; letter-spacing:0.04em; border-bottom:1px solid var(--border); }
        td { padding:0.75rem 1rem; font-size:0.85rem; border-bottom:1px solid var(--border); }
        tr:last-child td { border-bottom:none; }
        .empty { text-align:center; padding:2rem; color:var(--text-tertiary); font-size:0.85rem; }

        @media (max-width:640px) { .points-hero { flex-direction:column; text-align:center; } .points-hero .pts-stats { justify-content:center; } }
    </style>
</head>
<body>
    <nav>
        <div class="brand">Syarikat Perniagaan Maria Arts</div>
        <div class="nav-right">
            <span class="role-badge">Customer</span>
            <form method="POST" action="/logout">@csrf<button type="submit" class="logout-btn">Log Out</button></form>
        </div>
    </nav>
    <div class="container">
        <a href="/customer-dashboard" class="back-link">&larr; Back to Dashboard</a>

        <div class="points-hero">
            <div>
                <div class="pts-label">Available Points</div>
                <div class="pts-value">{{ number_format($customer->currentPoints) }}</div>
            </div>
            <div class="pts-stats">
                <div class="pts-stat"><div class="num">{{ $redeemedCount }}</div><div class="lbl">Redeemed</div></div>
                <div class="pts-stat"><div class="num">{{ $referralCount }}</div><div class="lbl">Referrals</div></div>
            </div>
        </div>

        <div class="section-title">Available Rewards</div>
        <div class="rewards-grid">
            @forelse ($rewards as $reward)
                <div class="reward-card">
                    <h4>{{ $reward->rewardName }}</h4>
                    <div class="pts">Needs <strong>{{ number_format($reward->pointRequired) }}</strong> points</div>
                    <div class="stock">Stock: {{ $reward->stock }}</div>
                    @if ($customer->currentPoints >= $reward->pointRequired)
                        <span class="elig-badge elig-yes">You can redeem this</span>
                    @else
                        <span class="elig-badge elig-no">Need {{ number_format($reward->pointRequired - $customer->currentPoints) }} more pts</span>
                    @endif
                </div>
            @empty
                <div class="reward-card" style="grid-column:1/-1;text-align:center;color:var(--text-tertiary);">No rewards available at this time.</div>
            @endforelse
        </div>

        <div class="section-title">Redemption History</div>
        <div class="history-card">
            <table>
                <thead><tr><th>Reward</th><th>Date</th></tr></thead>
                <tbody>
                    @forelse ($redemptions as $r)
                        <tr>
                            <td>{{ $rewards->firstWhere('rewardID', $r->rewardID)->rewardName ?? $r->rewardID }}</td>
                            <td style="color:var(--text-secondary);">{{ $r->redeemedDate }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="empty">No redemptions yet. Start earning points!</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
