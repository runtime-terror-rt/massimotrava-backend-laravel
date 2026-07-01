<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSRF (safe practice) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .hidden { display: none !important; }
        .step-indicator { margin-bottom: 20px; font-size: 0.9em; color: #64748b; text-align: center; }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <a href="{{ url('/') }}" class="sidebar-brand" style="position: relative; height: 60px; display: block;">
            <img src="{{ asset('images/logo.avif') }}" alt="Logo"
                style="position: absolute !important; top: 15px !important; left: 50% !important;
                        transform: translateX(-50%) !important; height: 38px !important;
                        width: auto !important; object-fit: contain !important; max-width: 85% !important;">
        </a>

        <div class="auth-header" style="margin-top: 30px;">
            <div class="auth-logo"><i class="fas fa-lock-open"></i></div>
            <h1 class="auth-title">Reset Password</h1>
            <p class="auth-subtitle" id="subtitle">Enter your email to receive an OTP</p>
        </div>

        <!-- STEP 1 -->
        <form id="step1Form">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <i class="far fa-envelope"></i>
                    <input type="email" id="email" class="form-input" placeholder="name@company.com" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-auth" id="btnStep1">
                <span>Send OTP</span>
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>

        <!-- STEP 2 -->
        <form id="step2Form" class="hidden">
            <div class="step-indicator">We've sent a 5-digit code to your email.</div>

            <div class="form-group">
                <label class="form-label">Enter OTP</label>
                <div class="input-group">
                    <i class="fas fa-key"></i>
                    <input type="text" id="otp" class="form-input" placeholder="12345" maxlength="5" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-auth" id="btnStep2">
                <span>Verify OTP</span>
                <i class="fas fa-check-circle"></i>
            </button>
        </form>

        <!-- STEP 3 -->
        <form id="step3Form" class="hidden">
            <div class="form-group">
                <label class="form-label">New Password</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="new_password" class="form-input" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="new_password_confirmation" class="form-input" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-auth" id="btnStep3">
                <span>Reset Password</span>
                <i class="fas fa-save"></i>
            </button>
        </form>

        <div class="auth-footer" style="margin-top: 20px;">
            Remember your password? <a href="/login" class="auth-link">Back to Login</a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    // ✅ Dynamic API URL (IMPORTANT FIX)
    const API_URL = '{{ url('/api/v1') }}';

    // CSRF header
    axios.defaults.headers.common['X-CSRF-TOKEN'] =
        document.querySelector('meta[name="csrf-token"]').content;

    const step1Form = document.getElementById('step1Form');
    const step2Form = document.getElementById('step2Form');
    const step3Form = document.getElementById('step3Form');

    const subtitle = document.getElementById('subtitle');
    const emailInput = document.getElementById('email');

    let userEmail = '';

    // STEP 1
    step1Form.addEventListener('submit', async (e) => {
        e.preventDefault();

        userEmail = emailInput.value;

        try {
            const res = await axios.post(`${API_URL}/forgot-password`, {
                email: userEmail
            });

            alert(res.data.message);

            step1Form.classList.add('hidden');
            step2Form.classList.remove('hidden');
            subtitle.textContent = "Verify OTP";

        } catch (err) {
            console.log(err);
            alert(err.response?.data?.message || 'Failed to send OTP');
        }
    });

    // STEP 2
    step2Form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const otp = document.getElementById('otp').value;

        try {
            const res = await axios.post(`${API_URL}/verify-otp`, {
                email: userEmail,
                otp: otp
            });

            alert(res.data.message);

            step2Form.classList.add('hidden');
            step3Form.classList.remove('hidden');
            subtitle.textContent = "Create New Password";

        } catch (err) {
            alert(err.response?.data?.message || 'Invalid OTP');
        }
    });

    // STEP 3
    step3Form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('new_password_confirmation').value;

        try {
            const res = await axios.post(`${API_URL}/reset-password`, {
                email: userEmail,
                new_password: newPassword,
                new_password_confirmation: confirmPassword
            });

            alert(res.data.message);
            window.location.href = '/login';

        } catch (err) {
            alert(err.response?.data?.message || 'Failed to reset password');
        }
    });

</script>

</body>
</html>