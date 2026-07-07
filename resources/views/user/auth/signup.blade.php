<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — Vyralabs</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-base:      #0a0d12;
            --bg-card:      #111620;
            --bg-input:     #181e2a;
            --border:       #1f2a3a;
            --border-focus: #00c2a8;
            --cyan:         #00c2a8;
            --cyan-dim:     rgba(0,194,168,0.12);
            --cyan-glow:    rgba(0,194,168,0.25);
            --green:        #22d166;
            --text-primary: #e8edf5;
            --text-secondary:#7a8898;
            --text-muted:   #4a5568;
            --error:        #f56565;
            --error-bg:     rgba(245,101,101,0.08);
            --radius:       12px;
            --radius-sm:    8px;
        }

        html, body {
            height: 100%;
            background: var(--bg-base);
            color: var(--text-primary);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }

        /* ── Layout ── */
        .page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 480px;
        }

        /* ── Left panel ── */
        .panel-left {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 72px;
            overflow: hidden;
            background: radial-gradient(ellipse 80% 60% at 20% 50%, rgba(0,194,168,0.07) 0%, transparent 70%);
        }

        .panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle 300px at 10% 20%, rgba(0,194,168,0.05) 0%, transparent 70%),
                radial-gradient(circle 200px at 80% 80%, rgba(34,209,102,0.04) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Grid lines */
        .panel-left::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(0,194,168,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,194,168,0.04) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        .brand {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 64px;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--cyan), var(--green));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-icon svg { width: 20px; height: 20px; color: #0a0d12; }

        .brand-name {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.3px;
            background: linear-gradient(90deg, var(--cyan), var(--green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-text { position: relative; z-index: 1; }

        .hero-eyebrow {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--cyan);
            margin-bottom: 16px;
        }

        .hero-title {
            font-size: 40px;
            font-weight: 700;
            line-height: 1.15;
            letter-spacing: -0.8px;
            color: var(--text-primary);
            margin-bottom: 20px;
        }

        .hero-title span {
            background: linear-gradient(90deg, var(--cyan), var(--green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-sub {
            font-size: 15px;
            color: var(--text-secondary);
            max-width: 380px;
            line-height: 1.7;
            margin-bottom: 48px;
        }

        .features {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .feature {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .feature-dot {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: var(--cyan-dim);
            border: 1px solid rgba(0,194,168,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .feature-dot svg { width: 13px; height: 13px; color: var(--cyan); }

        .feature-text { font-size: 13px; color: var(--text-secondary); line-height: 1.5; }
        .feature-text strong { color: var(--text-primary); font-weight: 600; display: block; margin-bottom: 2px; }

        /* ── Right panel / form card ── */
        .panel-right {
            background: var(--bg-card);
            border-left: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 48px;
            overflow-y: auto;
        }

        .form-wrap { width: 100%; max-width: 360px; }

        .form-head { margin-bottom: 28px; }
        .form-title { font-size: 22px; font-weight: 700; letter-spacing: -0.4px; margin-bottom: 6px; }
        .form-sub   { font-size: 13px; color: var(--text-secondary); }

        /* Error banner */
        .alert-error {
            background: var(--error-bg);
            border: 1px solid rgba(245,101,101,0.25);
            border-radius: var(--radius-sm);
            padding: 12px 14px;
            font-size: 13px;
            color: var(--error);
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .alert-error svg { width: 15px; height: 15px; flex-shrink: 0; margin-top: 1px; }

        /* Flash success */
        .alert-success {
            background: rgba(34,209,102,0.08);
            border: 1px solid rgba(34,209,102,0.2);
            border-radius: var(--radius-sm);
            padding: 12px 14px;
            font-size: 13px;
            color: var(--green);
            margin-bottom: 20px;
        }

        /* Form fields */
        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .field { margin-bottom: 14px; }
        .field:last-of-type { margin-bottom: 0; }

        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
            letter-spacing: 0.2px;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
            display: flex;
        }
        .input-icon svg { width: 15px; height: 15px; }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="file"],
        select {
            width: 100%;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 10px 12px 10px 36px;
            color: var(--text-primary);
            font-family: inherit;
            font-size: 13.5px;
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }

        input:focus, select:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px var(--cyan-glow);
        }

        input.is-invalid {
            border-color: var(--error);
        }

        .field-error {
            font-size: 11.5px;
            color: var(--error);
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Password toggle */
        .toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 0;
            display: flex;
            transition: color 0.15s;
        }
        .toggle-pw:hover { color: var(--text-secondary); }
        .toggle-pw svg { width: 15px; height: 15px; }

        /* Password strength */
        .pw-strength {
            display: flex;
            gap: 4px;
            margin-top: 6px;
        }
        .pw-bar {
            flex: 1;
            height: 3px;
            border-radius: 2px;
            background: var(--border);
            transition: background 0.25s;
        }
        .pw-bar.active-weak   { background: var(--error); }
        .pw-bar.active-medium { background: #f6ad55; }
        .pw-bar.active-strong { background: var(--green); }

        .pw-label {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Avatar upload */
        .avatar-upload {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }

        .avatar-preview {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .avatar-preview svg { width: 22px; height: 22px; color: var(--text-muted); }

        .avatar-btn {
            flex: 1;
            background: var(--bg-input);
            border: 1px dashed var(--border);
            border-radius: var(--radius-sm);
            padding: 10px 14px;
            color: var(--text-secondary);
            font-size: 12.5px;
            cursor: pointer;
            text-align: left;
            transition: border-color 0.15s, color 0.15s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .avatar-btn:hover { border-color: var(--border-focus); color: var(--cyan); }
        .avatar-btn svg { width: 14px; height: 14px; flex-shrink: 0; }
        #imageInput { display: none; }

        /* Terms */
        .terms-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 16px 0;
        }

        .terms-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            accent-color: var(--cyan);
            cursor: pointer;
            flex-shrink: 0;
            margin-top: 1px;
            padding: 0;
        }

        .terms-label {
            font-size: 12.5px;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .terms-label a { color: var(--cyan); text-decoration: none; }
        .terms-label a:hover { text-decoration: underline; }

        /* Submit button */
        .btn-submit {
            width: 100%;
            padding: 12px;
            border-radius: var(--radius-sm);
            border: none;
            background: linear-gradient(135deg, var(--cyan), #00a896);
            color: #0a0d12;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            letter-spacing: 0.2px;
        }

        .btn-submit:hover   { opacity: 0.9; }
        .btn-submit:active  { transform: scale(0.99); }
        .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(10,13,18,0.3);
            border-top-color: #0a0d12;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            color: var(--text-muted);
            font-size: 12px;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .signin-row {
            text-align: center;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .signin-row a {
            color: var(--cyan);
            text-decoration: none;
            font-weight: 600;
        }

        .signin-row a:hover { text-decoration: underline; }

        /* Scrollbar */
        ::-webkit-scrollbar       { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-base); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .page { grid-template-columns: 1fr; }
            .panel-left { display: none; }
            .panel-right { padding: 32px 24px; }
        }

        /* ── Left panel ── */
        .panel-left {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 72px;
            overflow: hidden;
            background: 
                radial-gradient(ellipse 80% 60% at 20% 50%, rgba(0,194,168,0.15) 0%, rgba(10,13,18,0.85) 80%),
                linear-gradient(rgba(10,13,18,0.4), rgba(10,13,18,0.7)),
                url('images/dna_image.webp') no-repeat center center;
            background-size: cover;
        }
    </style>
</head>
<body>

<div class="page">

    <!-- Left decorative panel -->
    <div class="panel-left">
        <div class="brand">
            <div class="brand-ico">
                <img src="{{ asset('images/logo.avif') }}" alt="Vyralabs"
                    style="position:absolute!important;top:15px!important;left:3%!important;transform:translateX(-50%)!important;height:38px!important;width:auto!important;object-fit:contain!important;max-width:85%!important;">
            </div>
        </div>

        <div class="hero-text">
            <p class="hero-eyebrow">Wellness & Longevity</p>
            <h1 class="hero-title">Your health data,<br><span>decoded.</span></h1>
            <p class="hero-sub">Advanced blood testing and biomarker analysis built for people who take their longevity seriously.</p>

            <div class="features">
                <div class="feature">
                    <div class="feature-dot">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                        </svg>
                    </div>
                    <div class="feature-text">
                        <strong>100+ Biomarkers Tracked</strong>
                        Comprehensive panels covering hormones, metabolic health, and inflammation.
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-dot">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <div class="feature-text">
                        <strong>HIPAA-Compliant & Secure</strong>
                        Your health data is encrypted and never shared without consent.
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-dot">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <div class="feature-text">
                        <strong>At-Home Sample Collection</strong>
                        Schedule a pickup — our inspectors come to you.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right form panel -->
    <div class="panel-right">
        <div class="form-wrap">
            <div class="form-head">
                <!-- Mobile brand -->
                <div class="brand" style="display:none; margin-bottom:28px;" id="mobileBrand">
                    <div class="brand-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                            <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <span class="brand-name">Vyralabs</span>
                </div>

                <h2 class="form-title">Create your account</h2>
                <p class="form-sub">Start your longevity journey today.</p>
            </div>

            {{-- Flash success message --}}
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            {{-- Validation error summary --}}
            @if($errors->any())
                <div class="alert-error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registerForm" novalidate>
                @csrf

                {{-- Avatar upload --}}
                <div class="avatar-upload">
                    <div class="avatar-preview" id="avatarPreview">
                        <img id="avatarImg" src="" alt="Preview">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" id="avatarPlaceholder">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <label class="avatar-btn" for="imageInput">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        <span id="avatarBtnText">Upload profile photo (optional)</span>
                    </label>
                    <input type="file" id="imageInput" name="image" accept="image/jpeg,image/png,image/jpg,image/gif">
                </div>

                {{-- Name --}}
                <div class="field">
                    <label for="name">Full Name <span style="color:var(--error)">*</span></label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </span>
                        <input type="text" id="name" name="name" placeholder="John Doe"
                               value="{{ old('name') }}"
                               class="{{ $errors->has('name') ? 'is-invalid' : '' }}" required>
                    </div>
                    @error('name')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Email --}}
                <div class="field">
                    <label for="email">Email Address <span style="color:var(--error)">*</span></label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </span>
                        <input type="email" id="email" name="email" placeholder="john@example.com"
                               value="{{ old('email') }}"
                               class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required>
                    </div>
                    @error('email')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Password --}}
                <div class="field">
                    <label for="password">Password <span style="color:var(--error)">*</span></label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input type="password" id="password" name="password" placeholder="Min 8 chars"
                               class="{{ $errors->has('password') ? 'is-invalid' : '' }}" required autocomplete="new-password">
                        <button type="button" class="toggle-pw" onclick="togglePw('password','eyeIcon1')">
                            <svg id="eyeIcon1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    <!-- Strength bars -->
                    <div class="pw-strength" id="strengthBars">
                        <div class="pw-bar" id="bar1"></div>
                        <div class="pw-bar" id="bar2"></div>
                        <div class="pw-bar" id="bar3"></div>
                        <div class="pw-bar" id="bar4"></div>
                    </div>
                    <div class="pw-label" id="pwLabel"></div>
                    @error('password')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                {{-- Confirm Password --}}
                <div class="field">
                    <label for="password_confirmation">Confirm Password <span style="color:var(--error)">*</span></label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               placeholder="Re-enter password" autocomplete="new-password">
                        <button type="button" class="toggle-pw" onclick="togglePw('password_confirmation','eyeIcon2')">
                            <svg id="eyeIcon2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    <div class="field-error" id="confirmError" style="display:none;">Passwords do not match.</div>
                </div>

                {{-- Terms checkbox --}}
                <div class="terms-row">
                    <input type="checkbox" id="terms" name="terms_accepted" value="1"
                           {{ old('terms_accepted') ? 'checked' : '' }}>
                    <label for="terms" class="terms-label">
                        I agree to Vyralabs' <a href="/terms-and-condition" target="_blank">Terms of Service</a>
                        and <a href="{{route('privacy.policy')}}" target="_blank">Privacy Policy</a>
                    </label>
                </div>
                <div class="field-error" id="termsError" style="display:none; margin-top:-10px; margin-bottom:12px;">
                    You must accept the Terms of Service to continue.
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <span class="spinner" id="spinner"></span>
                    <span id="btnText">Create Account</span>
                </button>
            </form>

            <div class="divider">or</div>

            <div class="signin-row">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Mobile brand show
    if (window.innerWidth <= 900) {
        document.getElementById('mobileBrand').style.display = 'flex';
    }

    // Toggle password visibility
    function togglePw(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        const show  = input.type === 'password';
        input.type  = show ? 'text' : 'password';
        icon.innerHTML = show
            ? `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
               <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
               <line x1="1" y1="1" x2="23" y2="23"/>`
            : `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
    }

    // Password strength
    document.getElementById('password').addEventListener('input', function () {
        const val  = this.value;
        const bars = [document.getElementById('bar1'), document.getElementById('bar2'),
                      document.getElementById('bar3'), document.getElementById('bar4')];
        const lbl  = document.getElementById('pwLabel');

        bars.forEach(b => { b.className = 'pw-bar'; });

        if (!val) { lbl.textContent = ''; return; }

        let score = 0;
        if (val.length >= 8)                     score++;
        if (/[a-z]/.test(val) && /[A-Z]/.test(val)) score++;
        if (/\d/.test(val))                      score++;
        if (/[^a-zA-Z0-9]/.test(val))            score++;

        const labels  = ['', 'Weak', 'Fair', 'Good', 'Strong'];
        const classes = ['', 'active-weak', 'active-medium', 'active-medium', 'active-strong'];
        const colors  = ['', 'active-weak', 'active-medium', 'active-strong', 'active-strong'];

        for (let i = 0; i < score; i++) {
            bars[i].classList.add(colors[score]);
        }
        lbl.textContent = score ? labels[score] : '';
        lbl.style.color = score <= 1 ? 'var(--error)' : score <= 2 ? '#f6ad55' : 'var(--green)';
    });

    // Avatar preview
    document.getElementById('imageInput').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('avatarImg');
            img.src = e.target.result;
            img.style.display = 'block';
            document.getElementById('avatarPlaceholder').style.display = 'none';
            document.getElementById('avatarBtnText').textContent = file.name.length > 22
                ? file.name.substring(0, 22) + '…' : file.name;
        };
        reader.readAsDataURL(file);
    });

    // Client-side form validation
    document.getElementById('registerForm').addEventListener('submit', function (e) {
        const pw         = document.getElementById('password').value;
        const cpw        = document.getElementById('password_confirmation').value;
        const terms      = document.getElementById('terms');
        const confirmErr = document.getElementById('confirmError');
        const termsErr   = document.getElementById('termsError');

        let hasError = false;

        // Password match check
        if (pw !== cpw) {
            confirmErr.style.display = 'flex';
            hasError = true;
        } else {
            confirmErr.style.display = 'none';
        }

        // Terms check — inline error, no alert()
        if (!terms.checked) {
            termsErr.style.display = 'flex';
            hasError = true;
        } else {
            termsErr.style.display = 'none';
        }

        if (hasError) {
            e.preventDefault();
            return;
        }

        // Loading state — only when form is valid
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        document.getElementById('spinner').style.display = 'block';
        document.getElementById('btnText').textContent = 'Creating account…';
    });
</script>
</body>
</html>