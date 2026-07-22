<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Loyalty Program — Maria Art's</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        :root {
            --brand: #2563eb; --brand-hover: #1d4ed8; --surface: #fff;
            --text-primary: #0b1120; --text-secondary: #5a6a85; --text-tertiary: #94a3b8;
            --border: #e9edf4; --danger-bg: #fee2e2; --radius: 8px;
        }
        body {
            font-family: 'Inter', sans-serif; background: #f8fafc;
            color: var(--text-primary); min-height: 100vh;
            display: flex; -webkit-font-smoothing: antialiased;
        }
        .brand-panel {
            flex: 1;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%);
            display: flex; flex-direction: column; justify-content: center;
            align-items: center; padding: 3rem; position: relative; overflow: hidden;
        }
        .brand-panel::before {
            content: ''; position: absolute; width: 600px; height: 600px;
            border-radius: 50%; background: rgba(255,255,255,0.04);
            top: -200px; right: -200px;
        }
        .brand-panel::after {
            content: ''; position: absolute; width: 400px; height: 400px;
            border-radius: 50%; background: rgba(255,255,255,0.03);
            bottom: -100px; left: -100px;
        }
        .brand-content { position: relative; z-index: 1; max-width: 440px; text-align: center; }
        .brand-icon {
            width: 72px; height: 72px; background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px); border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem; font-size: 1.8rem; font-weight: 800;
            color: #fff; border: 1px solid rgba(255,255,255,0.2);
        }
        .brand-content h1 { color: #fff; font-size: 1.75rem; font-weight: 800; letter-spacing: -0.03em; margin-bottom: 0.75rem; }
        .brand-content p { color: rgba(255,255,255,0.7); font-size: 0.95rem; line-height: 1.6; }
        .form-panel { width: 480px; display: flex; flex-direction: column; justify-content: center; padding: 3rem; background: var(--surface); }
        .form-header { margin-bottom: 2rem; }
        .form-header h2 { font-size: 1.5rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.35rem; }
        .form-header p { color: var(--text-secondary); font-size: 0.9rem; }
        .form-group { margin-bottom: 1.15rem; }
        label { display: block; font-size: 0.82rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem; }
        input { width: 100%; padding: 0.72rem 0.9rem; font-size: 0.9rem; font-family: inherit; border: 1.5px solid var(--border); border-radius: var(--radius); background: #f8fafc; outline: none; transition: border-color .2s, box-shadow .2s; }
        input:focus { border-color: var(--brand); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); background: var(--surface); }
        .btn { width: 100%; padding: 0.82rem; font-size: 0.9rem; font-weight: 700; font-family: inherit; background: var(--brand); color: #fff; border: none; border-radius: var(--radius); cursor: pointer; transition: background .2s, transform .15s; }
        .btn:hover { background: var(--brand-hover); transform: translateY(-1px); }
        .form-footer { margin-top: 1.5rem; text-align: center; font-size: 0.82rem; color: var(--text-tertiary); }
        .form-footer a { color: var(--brand); text-decoration: none; font-weight: 600; }
        .form-footer a:hover { text-decoration: underline; }
        .error { display: flex; align-items: flex-start; gap: 8px; background: var(--danger-bg); color: #b91c1c; padding: 0.85rem 1rem; border-radius: var(--radius); font-size: 0.84rem; font-weight: 500; line-height: 1.45; margin-bottom: 1.25rem; }
        .error-icon { flex-shrink: 0; width: 20px; height: 20px; border-radius: 50%; background: #ef4444; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; }
        @media (max-width:860px) { body { flex-direction: column; } .brand-panel { flex: none; padding: 2.5rem 1.5rem; } .form-panel { width: 100%; padding: 2rem 1.5rem; } }
    </style>
</head>
<body>
    <div class="brand-panel">
        <div class="brand-content">
            <h1>Maria Art's<br>Loyalty System</h1>
        </div>
    </div>

    <div class="form-panel">
        <div class="form-header">
            <h2>Join Loyalty Program</h2>
            <p>Create your account and start earning points today.</p>
        </div>

        @if ($errors->any())
            <div class="error">
                <span class="error-icon">!</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="/register">
            @csrf
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="customerName" value="{{ old('customerName') }}" required>
                @error('customerName') <span style="color:#b91c1c;font-size:0.78rem;">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required>
                @error('username') <span style="color:#b91c1c;font-size:0.78rem;">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
                @error('email') <span style="color:#b91c1c;font-size:0.78rem;">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phoneNumber" value="{{ old('phoneNumber') }}">
                @error('phoneNumber') <span style="color:#b91c1c;font-size:0.78rem;">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Birth Date</label>
                <input type="date" name="birthDate" value="{{ old('birthDate') }}">
                @error('birthDate') <span style="color:#b91c1c;font-size:0.78rem;">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
                @error('password') <span style="color:#b91c1c;font-size:0.78rem;">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Referral Code <span style="color:var(--text-tertiary);font-weight:400;">(optional)</span></label>
                <input type="text" name="referral_code" value="{{ old('referral_code') }}" placeholder="Enter referral code">
                @error('referral_code') <span style="color:#b91c1c;font-size:0.78rem;">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn">Create Account</button>
        </form>

        <div class="form-footer">
            Already have an account? <a href="/my-login">Sign In</a>
        </div>
    </div>
</body>
</html>
