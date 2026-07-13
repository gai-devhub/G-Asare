<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Access Code</title>
    <style>
        body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #1a1d24; background: #f8fafc; margin: 0; padding: 2rem; }
        .container { max-width: 480px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .code-box { font-size: 2rem; font-weight: 700; letter-spacing: 0.5em; color: #22c55e; text-align: center; padding: 1rem; background: rgba(34, 197, 94, 0.1); border-radius: 8px; margin: 1.5rem 0; }
        p { margin: 0.5rem 0; color: #64748b; }
        .footer { font-size: 0.85rem; color: #94a3b8; margin-top: 2rem; }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="margin-top: 0; color: #1a1d24;">Your Login Access Code</h2>
        <p>Use the code below to sign in to your G-BASE admin account:</p>
        <div class="code-box">{{ $code }}</div>
        <p>This code expires in {{ $expiresInMinutes }} minutes. Do not share it with anyone.</p>
        <p class="footer">© {{ date('Y') }} G-BASE. If you didn't request this code, please ignore this email.</p>
    </div>
</body>
</html>
