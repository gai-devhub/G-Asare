<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | G-ASARE Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    @include('component.favicon')
    <style>
        .password-wrapper{position:relative}
        .password-wrapper input{padding-right:40px}
        .password-toggle{position:absolute;right:8px;top:50%;transform:translateY(-50%);background:transparent;border:none;padding:4px;cursor:pointer;color:#4b5563}
        .password-toggle:focus{outline:2px solid rgba(59,130,246,0.25);border-radius:4px}
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-tabs">
                <a href="{{ route('login') }}" class="auth-tab">Login</a>
                <a href="{{ route('register') }}" class="auth-tab active">Register</a>
            </div>

            <div class="auth-form-pane active" id="register-pane">
                @if (session('message'))
                    <div class="auth-message success">{{ session('message') }}</div>
                @endif
                <form method="POST" action="{{ url('/register') }}" class="auth-form" id="register-form">
                    @csrf
                    <div class="form-group">
                        <label for="register-name">Full Name</label>
                        <input type="text" id="register-name" name="name" placeholder="John Doe" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="register-email">Email Address</label>
                        <input type="email" id="register-email" name="email" placeholder="user@g-asare.com" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="register-password">Create Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="register-password" name="password" placeholder="" required>
                            <button type="button" class="password-toggle" data-target="register-password" aria-label="Show password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="register-password-confirm">Confirm Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="register-password-confirm" name="password_confirmation" placeholder="" required>
                            <button type="button" class="password-toggle" data-target="register-password-confirm" aria-label="Show password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="passcode-section">
                        <label class="passcode-label">
                            Create Your Passcode <i class="fas fa-key passcode-icon"></i>
                        </label>
                        <div class="passcode-inputs" id="passcode-inputs">
                            <input type="text" inputmode="numeric" maxlength="1" class="passcode-box" data-index="0" autocomplete="off">
                            <input type="text" inputmode="numeric" maxlength="1" class="passcode-box" data-index="1" autocomplete="off">
                            <input type="text" inputmode="numeric" maxlength="1" class="passcode-box" data-index="2" autocomplete="off">
                            <input type="text" inputmode="numeric" maxlength="1" class="passcode-box" data-index="3" autocomplete="off">
                            <input type="text" inputmode="numeric" maxlength="1" class="passcode-box" data-index="4" autocomplete="off">
                            <input type="text" inputmode="numeric" maxlength="1" class="passcode-box" data-index="5" autocomplete="off">
                        </div>
                        <input type="hidden" name="passcode" id="passcode-hidden">
                        <p class="passcode-hint">Create your own 6-digit passcode (numbers only). Keep it secure!</p>
                        @error('passcode')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="auth-btn">
                        Create Account
                    </button>
                </form>
            </div>
        </div>

        <p class="auth-footer">© {{ date('Y') }} G-ASARE. Authorized personnel only.</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const boxes = document.querySelectorAll('.passcode-box');
            const hidden = document.getElementById('passcode-hidden');
            const form = document.getElementById('register-form');

            boxes.forEach((box, i) => {
                box.addEventListener('input', function() {
                    const val = this.value.replace(/\D/g, '');
                    this.value = val ? val[0] : '';
                    if (val && i < 5) boxes[i + 1].focus();
                    updateHidden();
                });
                box.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && i > 0) boxes[i - 1].focus();
                });
                box.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const paste = (e.clipboardData?.getData('text') || '').replace(/\D/g, '').slice(0, 6);
                    paste.split('').forEach((c, j) => { if (boxes[i + j]) boxes[i + j].value = c; });
                    boxes[Math.min(i + paste.length, 5)].focus();
                    updateHidden();
                });
            });

            function updateHidden() {
                hidden.value = Array.from(boxes).map(b => b.value).join('');
            }

            form.addEventListener('submit', function() {
                updateHidden();
            });

            const toggles = document.querySelectorAll('.password-toggle');
            toggles.forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    if (!input) return;
                    const icon = this.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        if (icon) { icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash'); }
                        this.setAttribute('aria-label', 'Hide password');
                    } else {
                        input.type = 'password';
                        if (icon) { icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye'); }
                        this.setAttribute('aria-label', 'Show password');
                    }
                });
            });
        });
    </script>
</body>
</html>
