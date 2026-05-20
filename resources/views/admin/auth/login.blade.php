<head>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="sidebar-brand" style="position: relative; height: 60px; display: block;">
                <img src="{{ asset('images/logo.avif') }}" alt="Massimo Logo" 
                    style="position: absolute !important; top: 15px !important; left: 50% !important; transform: translateX(-50%) !important; height: 38px !important; width: auto !important; object-fit: contain !important; max-width: 85% !important;">
            </div>
            <h1 class="auth-title">Welcome Back</h1>
            <p class="auth-subtitle">Please enter your details to sign in</p>
        </div>

        <form id="loginForm">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <i class="far fa-envelope"></i>
                    <input type="email" id="email" class="form-input" placeholder="name@company.com" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" class="form-input" placeholder="••••••••" required>
                </div>
            </div>

            <div class="form-options">
                <label class="checkbox-group">
                    <input type="checkbox" id="remember">
                    <span>Remember me</span>
                </label>
                <a href="/forgot-password" class="auth-link">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-primary btn-auth">
                <span>Sign In</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <!-- <div class="auth-footer">
            Don't have an account? <a href="/register" class="auth-link">Create Account</a>
        </div> -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = e.target.querySelector('button');
    btn.disabled = true;
    btn.innerHTML = 'Processing...';

    try {
        const response = await axios.post('/login', {
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            remember: document.getElementById('remember').checked
        });

        if (response.data.success) {
            localStorage.setItem('token', response.data.data.token);
            window.location.href = '/admin/dashboard';
        }
    } catch (error) {
        alert(error.response.data.message || 'Login failed');
        btn.disabled = false;
        btn.innerHTML = '<span>Sign In</span><i class="fas fa-arrow-right"></i>';
    }
});
</script>
