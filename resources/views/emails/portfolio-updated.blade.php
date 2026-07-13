<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Portfolio Update</title>
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
        .update-box {
            background-color: #f8fafc;
            border-left: 4px solid #2563eb;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New {{ ucfirst($type) }} Alert!</h1>
        </div>
        
        <div class="content">
            <p>Hello there,</p>
            <p>I just added something new to my portfolio and wanted you to be the first to know!</p>
            
            <div class="update-box">
                <strong>{{ ucfirst($type) }} Title:</strong><br>
                {{ $title }}
            </div>
            
            <p>Click the button below to check it out:</p>
            
            <div class="button-container">
                <a href="{{ $url }}" class="button">View {{ ucfirst($type) }}</a>
            </div>
            
            <p>Thanks for subscribing and supporting my journey.</p>
            <p>Best regards,<br>Gilbert</p>
        </div>
        
        <div class="footer">
            <p>You received this email because you subscribed to Portfolio News.</p>
            <p>&copy; {{ date('Y') }} Gilbert. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
