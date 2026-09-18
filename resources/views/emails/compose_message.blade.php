<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Message</title>
    <style>
        body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #1a1d24; background: #f8fafc; margin: 0; padding: 2rem; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .message-box { 
            font-size: 1rem; 
            color: #1a1d24; 
            padding: 1.5rem; 
            background: #f8fafc; 
            border-left: 4px solid #22c55e;
            border-radius: 4px 8px 8px 4px; 
            margin: 1.5rem 0; 
            white-space: pre-wrap;
        }
        .header { margin-top: 0; color: #1a1d24; font-size: 1.25rem; font-weight: 600; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem; }
        .footer { font-size: 0.85rem; color: #94a3b8; margin-top: 2rem; border-top: 1px solid #e2e8f0; padding-top: 1rem; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="header">Message from G-ASARE</h2>
        
        <div class="message-box">{!! nl2br(e($bodyContent)) !!}</div>
        
        <p style="color: #64748b; margin-top: 2rem;">
            Best regards,<br>
            <strong>{{ config('app.name', 'G-ASARE') }}</strong>
        </p>

        <div class="footer">
            © {{ date('Y') }} {{ config('app.name', 'G-ASARE') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
