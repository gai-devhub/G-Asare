<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribed | Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('component.favicon')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f5;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            max-width: 500px;
            width: 100%;
        }
        .icon {
            font-size: 4rem;
            color: #10b981;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 1.8rem;
            margin-bottom: 10px;
            font-weight: 600;
        }
        p {
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            background: #2563eb;
            color: #fff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Successfully Unsubscribed</h1>
        <p>You have been successfully removed from our mailing list. You will no longer receive any email updates.</p>
        <a href="{{ url('/') }}" class="btn">Return to Homepage</a>
    </div>
</body>

</html>
