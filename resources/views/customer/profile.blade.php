<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Profile — Maria Art's</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        :root {
            --bg: #f4f7fa; --surface: #ffffff; --surface-alt: #f8fafc;
            --text-primary: #0f172a; --text-secondary: #475569; --text-tertiary: #94a3b8;
            --brand: #f59e0b; --brand-light: #fbbf24; --brand-subtle: #fef3c7;
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
            background: var(--brand-subtle); color: #92400e;
            padding: 0.4rem 1rem; border-radius: 9999px;
            font-size: 0.8rem; font-weight: 600; letter-spacing: 0.01em;
        }
        .status-badge .dot { width: 7px; height: 7px; border-radius: 50%; background: var(--brand); display: inline-block; animation: pulse-dot 2s ease-in-out infinite; }
        @keyframes pulse-dot { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
        .logout-btn {
            background: transparent; border: 1px solid var(--border);
            color: var(--text-secondary); padding: 0.5rem 1.1rem;
            font-weight: 600; font-size: 0.8rem; border-radius: var(--radius-sm);
            cursor: pointer; transition: all 0.2s; font-family: inherit;
        }
        .logout-btn:hover { background: var(--danger-bg); color: var(--danger); border-color: var(--danger); }

        .container { max-width:640px; margin:2.5rem auto; padding:0 1.5rem; }
        .card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-md); padding:2rem; }
        .card h2 { font-size:1.3rem; font-weight:800; margin-bottom:0.3rem; }
        .card .subtitle { color:var(--text-secondary); font-size:0.85rem; margin-bottom:1.5rem; }
        .form-group { margin-bottom:1.15rem; }
        label { display:block; font-size:0.82rem; font-weight:600; color:var(--text-secondary); margin-bottom:0.35rem; }
        input { width:100%; padding:0.72rem 0.9rem; font-size:0.9rem; font-family:inherit; border:1.5px solid var(--border); border-radius:var(--radius-sm); background:var(--surface-alt); outline:none; transition:border-color .2s,box-shadow .2s; }
        input:focus { border-color:var(--brand); box-shadow:0 0 0 3px rgba(245,158,11,0.1); background:var(--surface); }
        .btn { width:100%; padding:0.78rem; font-size:0.9rem; font-weight:700; font-family:inherit; background:var(--brand); color:#fff; border:none; border-radius:var(--radius-sm); cursor:pointer; transition:background .2s; }
        .btn:hover { background:var(--brand-light); }
        .alert { padding:0.85rem 1rem; border-radius:var(--radius-sm); font-size:0.84rem; font-weight:500; margin-bottom:1.25rem; }
        .alert-success { background:var(--brand-subtle); color:#92400e; }
        .alert-error { background:var(--danger-bg); color:#b91c1c; display:flex; align-items:flex-start; gap:8px; }
        .alert-error .err-icon { flex-shrink:0; width:20px; height:20px; border-radius:50%; background:#ef4444; color:#fff; display:flex; align-items:center; justify-content:center; font-size:0.7rem; font-weight:700; margin-top:1px; }
        .info-box { background:var(--surface-alt); border:1px solid var(--border); border-radius:var(--radius-sm); padding:0.85rem 1rem; margin-bottom:1.5rem; font-size:0.85rem; color:var(--text-secondary); display:flex; flex-wrap:wrap; gap:1.5rem; }
        .info-box strong { color:var(--text-primary); }

        @media (max-width:720px) {
            nav { padding: 0 1rem; }
            .status-badge { display: none; }
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-left">
            <div class="brand"><span class="brand-dot"></span>Syarikat Perniagaan Maria Arts</div>
        </div>
        <div class="nav-right">
            <div class="status-badge"><span class="dot"></span>{{ session('username') }} · Customer</div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="logout-btn">Log Out</button>
            </form>
        </div>
    </nav>
    <div class="container">
        <a href="/customer-dashboard" class="back-btn" style="margin-bottom:1.5rem;display:inline-flex;">&larr;</a>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <span class="err-icon">!</span>
                <span>
                    @foreach ($errors->all() as $e)
                        @if (Str::contains($e, 'email'))
                            The Email and Phone Number is already taken by another customer.
                        @elseif (Str::contains($e, 'phone'))
                            Invalid Detail
                        @else
                            {{ $e }}
                        @endif
                    @endforeach
                </span>
            </div>
        @endif

        <div class="card">
            <h2>Manage Profile</h2>
            <p class="subtitle">View and update your account details.</p>

            <div class="info-box">
                <span>ID: <strong>{{ $customer->customerID }}</strong></span>
                <span>Points: <strong>{{ $customer->currentPoints }}</strong></span>
                <span>Referral: <strong>{{ $customer->referralCode ?? '-' }}</strong></span>
            </div>

            <form method="POST" action="/customer/profile">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="customerName" value="{{ old('customerName', $customer->customerName) }}" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phoneNumber" value="{{ old('phoneNumber', $customer->phoneNumber) }}">
                </div>
                <div class="form-group">
                    <label>Birth Date</label>
                    <input type="date" name="birthDate" value="{{ old('birthDate', $customer->birthDate) }}">
                </div>
                <button type="submit" class="btn">Save Changes</button>
            </form>
        </div>
    </div>
</body>
</html>
