<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | G-BASE Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-tabs">
                <a href="{{ route('login') }}" class="auth-tab active">Login</a>
                <a href="{{ route('register') }}" class="auth-tab">Register</a>
            </div>

            <div class="auth-form-pane active" id="login-pane">
                @if (session('message'))
                    <div class="auth-message {{ session('email_delivery_failed') ? 'warning' : 'success' }}">{{ session('message') }}</div>
                @endif
                @if ($errors->any())
                    <div class="auth-message error">{{ $errors->first() }}</div>
                @endif

                @if ($codeSent ?? false)
                    {{-- Step 2: Enter access code --}}
                    <form method="POST" action="{{ url('/login/verify') }}" class="auth-form" id="verify-form">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <div class="form-group">
                            <label for="login-email">Email Address</label>
                            <input type="text" id="login-email" value="{{ $email }}" readonly class="input-readonly">
                        </div>
                        <div class="form-group">
                            <div class="label-row">
                                <label for="login-code">Access Code</label>
                                <button type="submit" form="resend-form" class="resend-link">Resend Code</button>
                            </div>
                            <input type="text" id="login-code" name="code" placeholder="Enter 6-digit code" maxlength="6" pattern="\d{6}" inputmode="numeric" autocomplete="one-time-code" required autofocus class="code-input">
                            @error('code')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="auth-btn">
                            Sign In <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                    <form method="POST" action="{{ url('/login/request-code') }}" id="resend-form" class="hidden">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                    </form>
                @else
                    {{-- Step 1: Request access code --}}
                    <form method="POST" action="{{ url('/login/request-code') }}" class="auth-form">
                        @csrf
                        <div class="form-group">
                            <label for="login-email">Email Address</label>
                            <input type="email" id="login-email" name="email" placeholder="admin@g-base.com" value="{{ old('email', $email ?? '') }}" required autofocus>
                            @error('email')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="auth-btn">
                            Get Access Code <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <p class="auth-footer">© {{ date('Y') }} G-BASE. Authorized personnel only.</p>
    </div>
</body>
</html>
