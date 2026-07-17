<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — Maria Art's Loyalty System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --brand: #2563eb;
            --surface: #ffffff;
            --text-primary: #0b1120;
            --text-secondary: #5a6a85;
            --border: #e9edf4;
            --radius: 8px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
            background: #f8fafc;
        }

        .brand-panel {
            flex: 1;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
            top: -200px;
            right: -200px;
        }

        .brand-panel::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: rgba(255,255,255,0.03);
            bottom: -100px;
            left: -100px;
        }

        .brand-content {
            position: relative;
            z-index: 1;
            max-width: 440px;
            text-align: center;
        }

        .brand-icon {
            width: 72px;
            height: 72px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .brand-content h1 {
            color: #fff;
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 0.75rem;
        }

        .brand-content p {
            color: rgba(255,255,255,0.7);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .form-panel {
            width: 480px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem;
            background: var(--surface);
        }

        .form-panel h2 {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        .form-panel .subtitle {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .info-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: var(--radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-box h3 {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #0369a1;
        }

        .info-box p {
            font-size: 0.88rem;
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 0.75rem;
        }

        .info-box p:last-child {
            margin-bottom: 0;
        }

        .info-box .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.88rem;
            color: var(--text-primary);
            font-weight: 600;
        }

        .info-box .contact-icon {
            width: 28px;
            height: 28px;
            background: #e0f2fe;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--brand);
        }

        @media (max-width: 860px) {
            body { flex-direction: column; }
            .brand-panel {
                flex: none;
                padding: 2.5rem 1.5rem;
            }
            .brand-content { max-width: 100%; }
            .form-panel {
                width: 100%;
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>

    <div class="brand-panel">
        <div class="brand-content">
            <div class="brand-icon">M</div>
            <h1>Maria Art's<br>Loyalty System</h1>
        </div>
    </div>

    <div class="form-panel">
        <h2>Forgot Password?</h2>
        <p class="subtitle">No worries — visit the store and a cashier will help you reset your password.</p>

        <div class="info-box">
            <h3>Contact the Cashier</h3>
            <p>Please visit the store in person and ask the cashier to reset your password. You will need to provide your username and verify your identity.</p>
            <div class="contact-item">
                <span class="contact-icon">📍</span>
                <span>Maria Art's — [Store Address]</span>
            </div>
            <div class="contact-item" style="margin-top:0.5rem;">
                <span class="contact-icon">📞</span>
                <span>[Phone Number]</span>
            </div>
        </div>

        <a href="/my-login" class="back-link">← Back to Sign In</a>
    </div>

</body>
</html>
