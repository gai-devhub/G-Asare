<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to Portfolio News</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f5;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #2563eb;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
            line-height: 1.6;
        }
        .button-container {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: bold;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .unsubscribe-link {
            color: #64748b;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome Aboard! 🚀</h1>
        </div>
        
        <div class="content">
            <p>Hello,</p>
            <p>Thank you so much for subscribing to my portfolio news! I'm thrilled to have you here.</p>
            <p>You will now be the first to know whenever I publish new projects, add new skills, or post new articles about web development and cloud computing.</p>
            
            <div class="button-container">
                <a href="{{ url('/') }}" class="button">Visit Portfolio</a>
            </div>
            
            <p>If you have any questions or just want to connect, feel free to reply to this email or reach out through my contact page.</p>
            <p>Best regards,<br>Gilbert Asare</p>
        </div>
        
        <div class="footer">
            <p>You received this email because you subscribed to Gilbert's Portfolio News.</p>
            <p>If you no longer wish to receive these emails, you can <a href="{{ route('unsubscribe', ['email' => $subscriberEmail]) }}" class="unsubscribe-link">unsubscribe here</a>.</p>
            <p>&copy; {{ date('Y') }} Gilbert Asare. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
