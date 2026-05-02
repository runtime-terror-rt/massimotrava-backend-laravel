<head>
    <!-- ... অন্যান্য ট্যাগ ... -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <!-- আইকনের জন্য FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo"><i class="fas fa-user-plus"></i></div>
                <h1 class="auth-title">Create Account</h1>
                <p class="auth-subtitle">Join us and start managing today</p>
            </div>

            <form id="registerForm">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <div class="input-group">
                        <i class="far fa-user"></i>
                        <input type="text" id="reg-name" class="form-input" placeholder="John Doe" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <div class="input-group">
                        <i class="far fa-envelope"></i>
                        <input type="email" id="reg-email" class="form-input" placeholder="name@company.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="reg-password" class="form-input" placeholder="Min. 8 characters" required>
                    </div>
                    <p style="font-size: 10px; color: var(--text-muted); margin-top: 5px;">Must include letters, numbers & symbols.</p>
                </div>

                <button type="submit" class="btn btn-primary btn-auth">
                    <span>Create Account</span>
                    <i class="fas fa-check"></i>
                </button>
            </form>

            <div class="auth-footer">
                Already have an account? <a href="/login" class="auth-link">Sign In</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('button');
            btn.disabled = true;

            try {
                const response = await axios.post('/api/register', {
                    name: document.getElementById('reg-name').value,
                    email: document.getElementById('reg-email').value,
                    password: document.getElementById('reg-password').value
                });

                if (response.data.success) {
                    alert('Registration Successful! Redirecting to login...');
                    window.location.href = '/login';
                }
            } catch (error) {
                alert(error.response.data.message || 'Registration failed');
                btn.disabled = false;
            }
        });
    </script>
