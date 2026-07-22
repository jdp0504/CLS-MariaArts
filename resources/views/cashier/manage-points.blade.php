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
        :root {
            --bg: #f4f7fa; --surface: #ffffff; --surface-alt: #f8fafc;
            --text-primary: #0f172a; --text-secondary: #475569; --text-tertiary: #94a3b8;
            --brand: #10b981; --brand-light: #34d399; --brand-subtle: #d1fae5;
            --accent-green: #059669; --accent-green-bg: #d1fae5;
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
            min-height: 100vh; -webkit-font-smoothing: antialiased;
        }

        /* ─── NAV ─── */
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
        .status-badge {
            display: flex; align-items: center; gap: 6px;
            background: var(--accent-green-bg); color: #065f46;
            padding: 0.4rem 1rem; border-radius: 9999px;
            font-size: 0.8rem; font-weight: 600; letter-spacing: 0.01em;
        }
        .status-badge .dot { width: 7px; height: 7px; border-radius: 50%; background: var(--accent-green); display: inline-block; animation: pulse-dot 2s ease-in-out infinite; }
        @keyframes pulse-dot { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
        .logout-btn {
            background: transparent; border: 1px solid var(--border);
            color: var(--text-secondary); padding: 0.5rem 1.1rem;
            font-weight: 600; font-size: 0.8rem; border-radius: var(--radius-sm);
            cursor: pointer; transition: all 0.2s; font-family: inherit;
        }
        .logout-btn:hover { background: var(--danger-bg); color: var(--danger); border-color: var(--danger); }

        .container { max-width: 560px; margin: 2.5rem auto; padding: 0 1.5rem; }

        .step-link {
            display: inline-flex; align-items: center; gap: 6px;
            color: var(--text-secondary); text-decoration: none;
            font-size: 0.85rem; font-weight: 600; margin-bottom: 1.5rem;
            padding: 0.5rem 1.1rem; border: 1.5px solid #94a3b8;
            border-radius: var(--radius-sm); transition: all 0.2s;
        }
        .step-link:hover { color: var(--brand); border-color: var(--brand); }

        .card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius-md); padding: 2rem;
        }
        .card h2 { font-size: 1.25rem; font-weight: 800; margin-bottom: 0.3rem; }
        .card .subtitle { color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 1.5rem; }

        .customer-info {
            background: var(--surface-alt); border: 1px solid var(--border);
            border-radius: var(--radius-md); padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem; display: flex; align-items: center;
            justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;
        }
        .customer-info .name { font-size: 1.05rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
        .customer-info .meta { font-size: 0.82rem; color: var(--text-tertiary); }
        .reset-link {
            display: inline-flex; align-items: center; gap: 4px;
            background: #fef3c7; color: #92400e; padding: 0.2rem 0.6rem;
            border-radius: 9999px; font-size: 0.7rem; font-weight: 600;
            text-decoration: none; transition: background 0.2s, color 0.2s;
            white-space: nowrap;
        }
        .reset-link:hover { background: #f59e0b; color: #fff; }
        .customer-info .pts {
            font-size: 1.15rem; font-weight: 800; color: var(--brand);
        }

        .form-group { margin-bottom: 1.25rem; }
        label { display: block; font-size: 0.82rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem; }
        input, select {
            width: 100%; padding: 0.72rem 0.9rem; font-size: 0.9rem; font-family: inherit;
            border: 1.5px solid var(--border); border-radius: var(--radius-sm);
            background: var(--surface-alt); outline: none; transition: border-color .2s, box-shadow .2s;
        }
        input:focus, select:focus {
            border-color: var(--brand); box-shadow: 0 0 0 3px rgba(16,185,129,0.1);
            background: var(--surface);
        }

        .btn {
            width: 100%; padding: 0.78rem; font-size: 0.9rem; font-weight: 700;
            font-family: inherit; border: none; border-radius: var(--radius-sm);
            cursor: pointer; transition: background 0.2s, transform 0.15s;
        }
        .btn-primary { background: var(--brand); color: #fff; }
        .btn-primary:hover { background: #059669; }

        .action-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .action-btn {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 0.5rem; padding: 1.75rem 1rem; background: var(--surface);
            border: 2px solid var(--border); border-radius: var(--radius-md);
            text-decoration: none; color: var(--text-primary);
            transition: all 0.25s ease; cursor: pointer;
        }
        .action-btn:hover {
            border-color: var(--brand); background: var(--brand-subtle);
            box-shadow: var(--shadow-md); transform: translateY(-2px);
        }
        .action-btn .icon { font-size: 1.8rem; }
        .action-btn .label { font-weight: 700; font-size: 0.95rem; }
        .action-btn .desc { font-size: 0.78rem; color: var(--text-secondary); text-align: center; }

        .reward-list { display: flex; flex-direction: column; gap: 0.75rem; margin-top: 1.25rem; }
        .reward-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.25rem; background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius-md); transition: border-color 0.2s;
        }
        .reward-item:hover { border-color: var(--brand); }
        .reward-item .info { flex: 1; }
        .reward-item .rname { font-weight: 700; font-size: 0.95rem; }
        .reward-item .rmeta { font-size: 0.78rem; color: var(--text-tertiary); margin-top: 0.15rem; }
        .reward-item .redeem-btn {
            padding: 0.5rem 1rem; background: var(--brand); color: #fff;
            border: none; border-radius: var(--radius-sm); font-weight: 600;
            font-size: 0.82rem; cursor: pointer; font-family: inherit;
            transition: background 0.2s; white-space: nowrap;
        }
        .reward-item .redeem-btn:hover { background: #059669; }
        .reward-item .redeem-btn:disabled {
            background: var(--text-tertiary); cursor: not-allowed; opacity: 0.6;
        }
        .reward-item .insufficient {
            font-size: 0.78rem; color: var(--text-tertiary); font-weight: 600;
            white-space: nowrap;
        }

        .alert { display: flex; align-items: center; gap: 8px; padding: 0.85rem 1rem; border-radius: var(--radius-sm); font-size: 0.84rem; font-weight: 500; margin-bottom: 1.25rem; animation: slideDown 0.3s ease; }
        .alert-success { background: var(--accent-green-bg); color: #065f46; }
        .alert-error { background: var(--danger-bg); color: #b91c1c; }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }

        .empty-msg { text-align: center; padding: 2rem; color: var(--text-tertiary); font-size: 0.85rem; }

        .done-row { display: flex; justify-content: flex-end; margin-top: 1.5rem; }
        .done-btn {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--brand); color: #fff; text-decoration: none;
            padding: 0.6rem 1.4rem; font-size: 0.85rem; font-weight: 600;
            border-radius: var(--radius-sm); transition: background 0.2s;
        }
        .done-btn:hover { background: #059669; }

        @media (max-width: 600px) {
            .action-grid { grid-template-columns: 1fr; }
            nav { padding: 0 1rem; }
            .status-badge { display: none; }
            .customer-info { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-left">
            <div class="brand"><span class="brand-dot"></span>Syarikat Perniagaan Maria Arts</div>
        </div>
        <div class="nav-right">
            <div class="status-badge"><span class="dot"></span>{{ session('username') }} · Cashier</div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="logout-btn">Log Out</button>
            </form>
        </div>
    </nav>

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ══════ STEP 1: Search by phone ══════ --}}
        @if (!$customer)
            <div class="card">
                <h2>Search Customer</h2>
                <p class="subtitle">Enter the customer's phone number to search for their account.</p>
                <form method="POST" action="/cashier/manage-points/search">
                    @csrf
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="search" placeholder="e.g. 0123456789" required autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>

        {{-- ══════ STEP 2: Action selection ══════ --}}
        @elseif (!$step)
            <a href="/cashier/manage-points/back" class="step-link">&larr; New Search</a>
            <div class="customer-info">
                <div>
                    <div class="name">
                        {{ $customer->customerName }}
                        <a href="/cashier/manage-points/step/reset" class="reset-link">Reset Password</a>
                    </div>
                    <div class="meta">{{ $customer->customerID }} &middot; {{ $customer->phoneNumber }}</div>
                </div>
                <div class="pts">{{ number_format($customer->currentPoints) }} pts</div>
            </div>
            <div class="action-grid">
                <a href="/cashier/manage-points/step/add" class="action-btn">
                    <span class="icon">+</span>
                    <span class="label">Add Points</span>
                    <span class="desc">Record a purchase</span>
                </a>
                <a href="/cashier/manage-points/step/redeem" class="action-btn">
                    <span class="icon">&#x2713;</span>
                    <span class="label">Redeem Reward</span>
                    <span class="desc">Redeem for a reward</span>
                </a>
            </div>
            <div class="done-row">
                <a href="/cashier/manage-points/done" class="done-btn">Done</a>
            </div>

        {{-- ══════ STEP 3A: Add Points ══════ --}}
        @elseif ($step === 'add')
            <a href="/cashier/manage-points/back" class="step-link">&larr; Back to Actions</a>
            <div class="customer-info">
                <div>
                    <div class="name">{{ $customer->customerName }}</div>
                    <div class="meta">{{ $customer->customerID }}</div>
                </div>
                <div class="pts">{{ number_format($customer->currentPoints) }} pts</div>
            </div>
            <div class="card">
                <h2>Add Points</h2>
                <p class="subtitle">Enter the total purchase amount. Points are calculated at RM1 = 1 point.</p>
                <form method="POST" action="/cashier/manage-points/add">
                    @csrf
                    <div class="form-group">
                        <label>Total Purchase (RM)</label>
                        <input type="number" step="0.01" name="totalPrice" placeholder="0.00" required autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Points</button>
                </form>
            </div>
            <div class="done-row">
                <a href="/cashier/manage-points/done" class="done-btn">Done</a>
            </div>

        {{-- ══════ STEP 3B: Redeem Reward ══════ --}}
        @elseif ($step === 'redeem')
            <a href="/cashier/manage-points/back" class="step-link">&larr; Back to Actions</a>
            <div class="customer-info">
                <div>
                    <div class="name">{{ $customer->customerName }}</div>
                    <div class="meta">{{ $customer->customerID }}</div>
                </div>
                <div class="pts">{{ number_format($customer->currentPoints) }} pts</div>
            </div>

            @if ($rewards->count() > 0)
                <div class="reward-list">
                    @foreach ($rewards as $r)
                        <div class="reward-item">
                            <div class="info">
                                <div class="rname">{{ $r->rewardName }}</div>
                                <div class="rmeta">{{ $r->pointRequired }} pts &middot; Stock: {{ $r->stock }}</div>
                            </div>
                            @if ($customer->currentPoints >= $r->pointRequired)
                                <form method="POST" action="/cashier/manage-points/redeem" style="margin:0;">
                                    @csrf
                                    <input type="hidden" name="rewardID" value="{{ $r->rewardID }}">
                                    <button type="submit" class="redeem-btn">Redeem</button>
                                </form>
                            @else
                                <span class="insufficient">Need {{ number_format($r->pointRequired - $customer->currentPoints) }} more</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card">
                    <p class="empty-msg">No rewards available at this time.</p>
                </div>
            @endif
            <div class="done-row">
                <a href="/cashier/manage-points/done" class="done-btn">Done</a>
            </div>

        {{-- ══════ STEP 3C: Reset Password ══════ --}}
        @elseif ($step === 'reset')
            <a href="/cashier/manage-points/back" class="step-link">&larr; Back to Actions</a>
            <div class="customer-info">
                <div>
                    <div class="name">{{ $customer->customerName }}</div>
                    <div class="meta">{{ $customer->customerID }}</div>
                </div>
                <div class="pts">{{ number_format($customer->currentPoints) }} pts</div>
            </div>
            <div class="card">
                <h2>Reset Password</h2>
                <p class="subtitle">Enter a new password for this customer. Minimum 4 characters.</p>
                <form method="POST" action="/cashier/manage-points/reset-password">
                    @csrf
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="newPassword" placeholder="Enter new password" required minlength="4" autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </form>
            </div>
            <div class="done-row">
                <a href="/cashier/manage-points/done" class="done-btn">Done</a>
            </div>
        @endif
    </div>
</body>
</html>
