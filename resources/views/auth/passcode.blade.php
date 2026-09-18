<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Passcode | G-ASARE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
        .passcode-container {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 20px 0;
        }
        .passcode-box {
            width: 45px;
            height: 55px;
            font-size: 24px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff;
            color: #333;
            font-weight: 600;
        }
        .passcode-box:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        }
        .hidden-input {
            display: none;
        }
        .passcode-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .passcode-header h2 {
            font-family: 'Poppins', sans-serif;
            color: #1f2937;
            font-size: 1.5rem;
            margin-bottom: 5px;
        }
        .passcode-header p {
            color: #6b7280;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-form-pane active">
                <div class="passcode-header">
                    <h2>Enter Passcode <i class="fas fa-lock" style="color: #10b981;"></i></h2>
                    <p>Please enter your 6-digit personal passcode to continue.</p>
                </div>

                @if ($errors->any())
                    <div class="auth-message error">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('login.passcode.verify') }}" class="auth-form" id="passcode-form">
                    @csrf
                    
                    <div class="passcode-container" id="passcode-inputs">
                        <input type="text" class="passcode-box" maxlength="1" inputmode="numeric" pattern="[0-9]" autofocus>
                        <input type="text" class="passcode-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
                        <input type="text" class="passcode-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
                        <input type="text" class="passcode-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
                        <input type="text" class="passcode-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
                        <input type="text" class="passcode-box" maxlength="1" inputmode="numeric" pattern="[0-9]">
                    </div>
                    
                    <input type="hidden" name="passcode" id="actual-passcode" required>
                    
                    <button type="submit" class="auth-btn">
                        Access Dashboard <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <div style="text-align: center; margin-top: 20px;">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="resend-link" style="border: none; background: none; cursor: pointer; padding: 0;">
                            Cancel & Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <p class="auth-footer">© {{ date('Y') }} G-ASARE. Authorized personnel only.</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.passcode-box');
            const hiddenInput = document.getElementById('actual-passcode');
            const form = document.getElementById('passcode-form');

            inputs.forEach((input, index) => {
                // Handle input
                input.addEventListener('input', function() {
                    // Allow only numbers
                    this.value = this.value.replace(/[^0-9]/g, '');
                    
                    if (this.value.length === 1) {
                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        } else {
                            // If it's the last box, automatically update hidden input and maybe submit
                            updateHiddenInput();
                        }
                    }
                    updateHiddenInput();
                });

                // Handle backspace
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value === '') {
                        if (index > 0) {
                            inputs[index - 1].focus();
                            inputs[index - 1].value = '';
                        }
                    } else if (e.key === 'Enter') {
                        e.preventDefault();
                        if (hiddenInput.value.length === 6) {
                            form.submit();
                        }
                    }
                });

                // Handle paste
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
                    
                    if (pastedData) {
                        for (let i = 0; i < pastedData.length; i++) {
                            if (inputs[index + i]) {
                                inputs[index + i].value = pastedData[i];
                            }
                        }
                        
                        // Focus on the next empty box or the last box
                        const nextIndex = Math.min(index + pastedData.length, inputs.length - 1);
                        inputs[nextIndex].focus();
                        
                        updateHiddenInput();
                        
                        if (pastedData.length === 6) {
                            form.submit();
                        }
                    }
                });
            });

            function updateHiddenInput() {
                let passcode = '';
                inputs.forEach(input => {
                    passcode += input.value;
                });
                hiddenInput.value = passcode;
                
                // Optional: auto submit when 6 digits are entered
                if (passcode.length === 6) {
                    setTimeout(() => {
                        form.submit();
                    }, 300);
                }
            }
        });
    </script>
</body>
</html>
