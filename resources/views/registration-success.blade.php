<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful — Maria Art's</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="preload" href="/img/MariaArts.png" as="image">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        :root {
            --brand: #2563eb; --brand-hover: #1d4ed8; --surface: #fff;
            --text-primary: #0b1120; --text-secondary: #5a6a85; --text-tertiary: #94a3b8;
            --border: #e9edf4; --success-bg: #d1fae5; --success-text: #065f46;
            --radius: 8px;
        }
        body {
            font-family: 'Inter', sans-serif; background: #1a1a2e;
            color: var(--text-primary); min-height: 100vh;
            display: flex; -webkit-font-smoothing: antialiased;
        }
        .brand-panel {
            flex: 1;
            background: #1a1a2e url('/img/MariaArts.png') center/cover no-repeat;
            display: flex; flex-direction: column; justify-content: center;
            align-items: center; padding: 3rem; position: relative; overflow: hidden;
        }
        .brand-panel::before {
            content: ''; position: absolute; inset: 0;
            background: rgba(0,0,0,0.4);
        }
        .brand-panel::after { content: none; }
        .brand-content {
            position: relative; z-index: 1; max-width: 440px; text-align: center;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.2); border-radius: 16px;
            padding: 2.5rem 2rem;
        }
        .brand-icon {
            width: 72px; height: 72px; background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px); border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem; font-size: 1.8rem; font-weight: 800;
            color: #fff; border: 1px solid rgba(255,255,255,0.2);
        }
        .brand-content h1 { color: #fff; font-size: 1.75rem; font-weight: 800; letter-spacing: -0.03em; margin-bottom: 0.75rem; }
        .brand-content p { color: rgba(255,255,255,0.7); font-size: 0.95rem; line-height: 1.6; }
        .form-panel { width: 520px; display: flex; flex-direction: column; justify-content: center; padding: 3rem; background: var(--surface); }
        .checkmark {
            width: 56px; height: 56px; background: #d1fae5; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.25rem; font-size: 1.5rem; color: #065f46;
        }
        .form-panel h2 { font-size: 1.5rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.35rem; }
        .form-panel > p { color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1.5rem; }
        .details-card {
            background: #f8fafc; border: 1px solid var(--border); border-radius: var(--radius);
            padding: 1.25rem 1.5rem; margin-bottom: 1.5rem;
        }
        .detail-row { display: flex; justify-content: space-between; padding: 0.65rem 0; border-bottom: 1px solid var(--border); }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 0.82rem; font-weight: 600; color: var(--text-secondary); }
        .detail-value { font-size: 0.9rem; font-weight: 600; color: var(--text-primary); }
        .detail-value.points { color: var(--brand); }
        .detail-value.referral { color: #059669; letter-spacing: 0.02em; }
        .detail-value.bonus { color: #059669; font-weight: 700; }
        .referral-banner {
            display: flex; align-items: center; gap: 8px; background: #ecfdf5; color: #065f46;
            border-radius: var(--radius); padding: 0.8rem 1rem; margin-bottom: 1.25rem;
            font-size: 0.85rem; font-weight: 600;
        }
        .referral-banner svg { flex-shrink: 0; }
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            width: 100%; padding: 0.82rem; font-size: 0.9rem; font-weight: 700;
            font-family: inherit; background: var(--brand); color: #fff;
            border: none; border-radius: var(--radius); cursor: pointer;
            text-decoration: none; transition: background .2s, transform .15s;
        }
        .btn:hover { background: var(--brand-hover); transform: translateY(-1px); }
        .form-footer { margin-top: 1.25rem; text-align: center; font-size: 0.82rem; color: var(--text-tertiary); }
        @media (max-width:860px) { body { flex-direction: column; } .brand-panel { flex: none; padding: 2.5rem 1.5rem; } .form-panel { width: 100%; padding: 2rem 1.5rem; } }
    </style>
</head>
<body>
    <div class="brand-panel">
        <div class="brand-content">
            <div class="brand-icon">M</div>
            <h1>Maria Art's<br>Loyalty System</h1>
            <p>Your journey to exclusive rewards starts here.</p>
        </div>
    </div>

    <div class="form-panel">
        <div class="checkmark">&#10003;</div>
        <h2>Registration Successful!</h2>
        <p>Your account has been created. Here are your registration details.</p>

        @if (session('referralUsed'))
            <div class="referral-banner">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="10" fill="#059669"/><path d="M6 10l2.5 2.5L14 7" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                +5 referral bonus points added to your account!
            </div>
        @endif

        <div class="details-card">
            <div class="detail-row">
                <span class="detail-label">Customer ID</span>
                <span class="detail-value">{{ session('customerID') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Name</span>
                <span class="detail-value">{{ session('customerName') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Points</span>
                <span class="detail-value points">{{ session('referralUsed') ? 5 : 0 }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Referral Code</span>
                <span class="detail-value referral">{{ session('referralCode') }}</span>
            </div>
        </div>

        <a href="/my-login" class="btn">Proceed to Login</a>
        <div class="form-footer">
            Share your referral code to earn bonus points!
        </div>
    </div>
</body>
</html>
