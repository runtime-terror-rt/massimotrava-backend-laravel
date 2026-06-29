<head>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="sidebar-brand" style="position: relative; height: 60px; display: block;">
                <img src="{{ asset('images/logo.avif') }}" alt="Logo"
                    style="position: absolute !important; top: 15px !important; left: 50% !important;
                           transform: translateX(-50%) !important; height: 38px !important;
                           width: auto !important; object-fit: contain !important; max-width: 85% !important;">
            </div>
            <h1 class="auth-title">Welcome Back</h1>
            <p class="auth-subtitle">Please enter your details to sign in</p>
        </div>

        {{-- Error alert (non-JS fallback) --}}
        @if ($errors->any())
            <div class="auth-alert auth-alert-error">
                <i class="fas fa-circle-exclamation"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <div id="jsAlert" class="auth-alert auth-alert-error" style="display:none;">
            <i class="fas fa-circle-exclamation"></i>
            <span id="jsAlertMsg"></span>
        </div>

        <form id="loginForm">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <i class="far fa-envelope"></i>
                    <input type="email" id="email" class="form-input"
                           placeholder="name@company.com" required autocomplete="email">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" class="form-input"
                           placeholder="••••••••" required autocomplete="current-password">
                </div>
            </div>

            <div class="form-options">
                <label class="checkbox-group">
                    <input type="checkbox" id="remember">
                    <span>Remember me</span>
                </label>
                <a href="/forgot-password" class="auth-link">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-primary btn-auth" id="submitBtn">
                <span>Sign In</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <div class="auth-footer">
                Don't have an account? <a href="/register" class="auth-link">Sign up</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
(function () {
    const form      = document.getElementById('loginForm');
    const btn       = document.getElementById('submitBtn');
    const alertBox  = document.getElementById('jsAlert');
    const alertMsg  = document.getElementById('jsAlertMsg');

    function showError(msg) {
        alertMsg.textContent = msg;
        alertBox.style.display = 'flex';
    }

    function resetBtn() {
        btn.disabled = false;
        btn.innerHTML = '<span>Sign In</span><i class="fas fa-arrow-right"></i>';
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        alertBox.style.display = 'none';
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Signing in…';

        try {
            const { data } = await axios.post('/login', {
                email:    document.getElementById('email').value,
                password: document.getElementById('password').value,
                remember: document.getElementById('remember').checked,
            });

            if (data.success) {
                localStorage.setItem('token', data.data.token);

                // ── Role-based redirect from server response ──
                window.location.href = data.data.redirect_url;
            }
        } catch (err) {
            const msg = err.response?.data?.message || 'Login failed. Please try again.';
            showError(msg);
            resetBtn();
        }
    });
})();
</script>