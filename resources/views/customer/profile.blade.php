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
            --bg:#f0f2f8; --surface:#fff; --text-primary:#0b1120; --text-secondary:#5a6a85; --text-tertiary:#94a3b8;
            --brand:#f59e0b; --brand-light:#fbbf24; --brand-subtle:#fef3c7;
            --danger-bg:#fee2e2; --border:#e9edf4; --radius:8px;
        }
        body { font-family:'Inter',sans-serif; background:var(--bg); color:var(--text-primary); min-height:100vh; -webkit-font-smoothing:antialiased; }
        nav { background:var(--surface); border-bottom:1px solid var(--border); padding:0 2rem; height:64px; display:flex; align-items:center; justify-content:space-between; }
        nav .brand { font-weight:700; font-size:1rem; }
        nav .nav-right { display:flex; align-items:center; gap:1rem; }
        .role-badge { background:var(--brand-subtle); color:#92400e; padding:0.35rem 0.9rem; border-radius:9999px; font-size:0.8rem; font-weight:600; }
        .logout-btn { background:transparent; border:1px solid var(--border); color:var(--text-secondary); padding:0.45rem 1rem; font-weight:600; font-size:0.8rem; border-radius:6px; cursor:pointer; font-family:inherit; transition:all .2s; }
        .logout-btn:hover { background:var(--danger-bg); color:#ef4444; border-color:#ef4444; }
        .container { max-width:640px; margin:2.5rem auto; padding:0 1.5rem; }
        .back-link { display:inline-flex; align-items:center; gap:6px; color:var(--text-secondary); text-decoration:none; font-size:0.85rem; font-weight:600; margin-bottom:1.5rem; }
        .back-link:hover { color:var(--text-primary); }
        .card { background:var(--surface); border:1px solid var(--border); border-radius:12px; padding:2rem; }
        .card h2 { font-size:1.3rem; font-weight:800; margin-bottom:0.3rem; }
        .card .subtitle { color:var(--text-secondary); font-size:0.85rem; margin-bottom:1.5rem; }
        .form-group { margin-bottom:1.15rem; }
        label { display:block; font-size:0.82rem; font-weight:600; color:var(--text-secondary); margin-bottom:0.35rem; }
        input { width:100%; padding:0.72rem 0.9rem; font-size:0.9rem; font-family:inherit; border:1.5px solid var(--border); border-radius:var(--radius); background:#f8fafc; outline:none; transition:border-color .2s,box-shadow .2s; }
        input:focus { border-color:var(--brand); box-shadow:0 0 0 3px rgba(245,158,11,0.1); background:var(--surface); }
        .btn { width:100%; padding:0.78rem; font-size:0.9rem; font-weight:700; font-family:inherit; background:var(--brand); color:#fff; border:none; border-radius:var(--radius); cursor:pointer; transition:background .2s; }
        .btn:hover { background:var(--brand-light); }
        .alert { padding:0.85rem 1rem; border-radius:var(--radius); font-size:0.84rem; font-weight:500; margin-bottom:1.25rem; }
        .alert-success { background:var(--brand-subtle); color:#92400e; }
        .alert-error { background:var(--danger-bg); color:#b91c1c; display:flex; align-items:flex-start; gap:8px; }
        .alert-error .err-icon { flex-shrink:0; width:20px; height:20px; border-radius:50%; background:#ef4444; color:#fff; display:flex; align-items:center; justify-content:center; font-size:0.7rem; font-weight:700; margin-top:1px; }
        .info-box { background:#f8fafc; border:1px solid var(--border); border-radius:var(--radius); padding:0.85rem 1rem; margin-bottom:1.5rem; font-size:0.85rem; color:var(--text-secondary); display:flex; flex-wrap:wrap; gap:1.5rem; }
        .info-box strong { color:var(--text-primary); }
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

        <a href="/customer-dashboard" class="back-link">&larr; Back to Dashboard</a>
        <div style="margin-bottom:1rem;text-align:right;">
            <a href="/customer/points-rewards" style="display:inline-flex;align-items:center;gap:4px;background:var(--brand-subtle);color:#92400e;text-decoration:none;padding:0.45rem 1rem;border-radius:9999px;font-size:0.8rem;font-weight:600;transition:background .2s;">View Points & Rewards &rarr;</a>
        </div>
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
