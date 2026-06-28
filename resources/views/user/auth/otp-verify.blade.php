<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email — Vyralabs</title>
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
            min-height: 100vh;
            background: var(--bg-base);
            color: var(--text-primary);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        /* Background grid */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(0,194,168,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,194,168,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse 60% 50% at 50% 30%, rgba(0,194,168,0.06) 0%, transparent 70%);
            pointer-events: none;
        }

        .card {
            position: relative;
            z-index: 1;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 48px 40px 40px;
            width: 100%;
            max-width: 420px;
        }

        /* Brand */
        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 36px;
        }

        .brand-icon {
            width: 34px;
            height: 34px;
            background: linear-gradient(135deg, var(--cyan), var(--green));
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-icon svg { width: 18px; height: 18px; color: #0a0d12; }

        .brand-name {
            font-size: 17px;
            font-weight: 700;
            letter-spacing: -0.3px;
            background: linear-gradient(90deg, var(--cyan), var(--green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Icon circle */
        .icon-wrap {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: var(--cyan-dim);
            border: 1px solid rgba(0,194,168,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        .icon-wrap svg { width: 28px; height: 28px; color: var(--cyan); }

        .card-title {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.4px;
            text-align: center;
            margin-bottom: 8px;
        }

        .card-sub {
            font-size: 13px;
            color: var(--text-secondary);
            text-align: center;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .card-sub span {
            color: var(--cyan);
            font-weight: 600;
        }

        /* Alerts */
        .alert-error {
            background: var(--error-bg);
            border: 1px solid rgba(245,101,101,0.25);
            border-radius: var(--radius-sm);
            padding: 12px 14px;
            font-size: 13px;
            color: var(--error);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-error svg { width: 15px; height: 15px; flex-shrink: 0; }

        .alert-success {
            background: rgba(34,209,102,0.08);
            border: 1px solid rgba(34,209,102,0.2);
            border-radius: var(--radius-sm);
            padding: 12px 14px;
            font-size: 13px;
            color: var(--green);
            margin-bottom: 20px;
            text-align: center;
        }

        /* OTP boxes */
        .otp-row {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 8px;
        }

        .otp-box {
            width: 52px;
            height: 56px;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            color: var(--text-primary);
            font-family: inherit;
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
            caret-color: var(--cyan);
        }

        .otp-box:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px var(--cyan-glow);
        }

        .otp-box.is-invalid {
            border-color: var(--error);
            box-shadow: 0 0 0 3px rgba(245,101,101,0.15);
        }

        .otp-box.is-filled {
            border-color: rgba(0,194,168,0.4);
        }

        /* Hidden real input for form submit */
        #otpHidden { display: none; }

        .field-error {
            font-size: 12px;
            color: var(--error);
            text-align: center;
            margin-bottom: 16px;
            min-height: 18px;
        }

        /* Timer */
        .timer-row {
            text-align: center;
            font-size: 12.5px;
            color: var(--text-muted);
            margin-bottom: 24px;
        }

        .timer-count {
            color: var(--cyan);
            font-weight: 600;
            font-variant-numeric: tabular-nums;
        }

        /* Submit */
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
            margin-bottom: 16px;
        }

        .btn-submit:hover   { opacity: 0.9; }
        .btn-submit:active  { transform: scale(0.99); }
        .btn-submit:disabled { opacity: 0.45; cursor: not-allowed; }

        .spinner {
            width: 15px;
            height: 15px;
            border: 2px solid rgba(10,13,18,0.3);
            border-top-color: #0a0d12;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* Resend */
        .resend-row {
            text-align: center;
            font-size: 13px;
            color: var(--text-secondary);
            margin-bottom: 20px;
        }

        .btn-resend {
            background: none;
            border: none;
            color: var(--cyan);
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            padding: 0;
            transition: opacity 0.15s;
        }

        .btn-resend:hover    { opacity: 0.75; }
        .btn-resend:disabled { opacity: 0.4; cursor: not-allowed; }

        .divider {
            height: 1px;
            background: var(--border);
            margin-bottom: 16px;
        }

        .back-row {
            text-align: center;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .back-row a {
            color: var(--cyan);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .back-row a:hover { text-decoration: underline; }
        .back-row a svg { width: 13px; height: 13px; }
    </style>
</head>
<body>

<div class="card">

    <div class="brand">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
            </svg>
        </div>
        <span class="brand-name">Vyralabs</span>
    </div>

    <div class="icon-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
        </svg>
    </div>

    <h2 class="card-title">Check your email</h2>
    <p class="card-sub">
        We've sent a 5-digit verification code to<br>
        <span>{{ session('otp_email') ?? 'your email address' }}</span>
    </p>

    {{-- Flash success --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- Error alert --}}
    @if($errors->any() || session('error'))
        <div class="alert-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            {{ $errors->first() ?: session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('otp.verify') }}" id="otpForm">
        @csrf

        {{-- Hidden real OTP input --}}
        <input type="hidden" name="otp" id="otpHidden">

        {{-- 5 visible OTP boxes --}}
        <div class="otp-row">
            <input class="otp-box" type="text" inputmode="numeric" maxlength="1" data-index="0" autocomplete="one-time-code">
            <input class="otp-box" type="text" inputmode="numeric" maxlength="1" data-index="1">
            <input class="otp-box" type="text" inputmode="numeric" maxlength="1" data-index="2">
            <input class="otp-box" type="text" inputmode="numeric" maxlength="1" data-index="3">
            <input class="otp-box" type="text" inputmode="numeric" maxlength="1" data-index="4">
        </div>

        <div class="field-error" id="otpError"></div>

        {{-- Countdown --}}
        <div class="timer-row" id="timerRow">
            Code expires in <span class="timer-count" id="timerCount">10:00</span>
        </div>

        <button type="submit" class="btn-submit" id="submitBtn">
            <span class="spinner" id="spinner"></span>
            <span id="btnText">Verify Email</span>
        </button>
    </form>

    {{-- Resend --}}
    <div class="resend-row">
        Didn't receive the code?
        <form method="POST" action="{{ route('otp.resend') }}" style="display:inline;" id="resendForm">
            @csrf
            <button type="submit" class="btn-resend" id="resendBtn" disabled>
                Resend OTP
            </button>
        </form>
    </div>

    <div class="divider"></div>

    <div class="back-row">
        <a href="{{ route('register') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
            Back to Register
        </a>
    </div>

</div>

<script>
    const boxes      = document.querySelectorAll('.otp-box');
    const hidden     = document.getElementById('otpHidden');
    const otpError   = document.getElementById('otpError');
    const timerCount = document.getElementById('timerCount');
    const resendBtn  = document.getElementById('resendBtn');

    // ── OTP box navigation ────────────────────────────────────────────────
    boxes.forEach((box, i) => {
        box.addEventListener('input', function () {
            // Only digits
            this.value = this.value.replace(/\D/g, '');

            if (this.value) {
                this.classList.add('is-filled');
                if (i < boxes.length - 1) boxes[i + 1].focus();
            } else {
                this.classList.remove('is-filled');
            }

            syncHidden();
        });

        box.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && i > 0) {
                boxes[i - 1].value = '';
                boxes[i - 1].classList.remove('is-filled');
                boxes[i - 1].focus();
                syncHidden();
            }
        });

        // Paste support — paste on any box fills all
        box.addEventListener('paste', function (e) {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
            if (!text) return;
            [...text.slice(0, 5)].forEach((ch, idx) => {
                if (boxes[idx]) {
                    boxes[idx].value = ch;
                    boxes[idx].classList.add('is-filled');
                }
            });
            const next = Math.min(text.length, 4);
            boxes[next].focus();
            syncHidden();
        });
    });

    function syncHidden() {
        hidden.value = [...boxes].map(b => b.value).join('');
    }

    // ── Form submit ───────────────────────────────────────────────────────
    document.getElementById('otpForm').addEventListener('submit', function (e) {
        syncHidden();

        if (hidden.value.length < 5) {
            e.preventDefault();
            otpError.textContent = 'Please enter all 5 digits.';
            boxes.forEach(b => b.classList.add('is-invalid'));
            return;
        }

        otpError.textContent = '';
        boxes.forEach(b => b.classList.remove('is-invalid'));

        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        document.getElementById('spinner').style.display = 'block';
        document.getElementById('btnText').textContent = 'Verifying…';
    });

    // ── Countdown timer (10 minutes) ──────────────────────────────────────
    let seconds = 10 * 60;

    const timer = setInterval(() => {
        seconds--;
        if (seconds <= 0) {
            clearInterval(timer);
            timerCount.textContent = '0:00';
            timerCount.style.color = 'var(--error)';
            document.getElementById('timerRow').innerHTML =
                '<span style="color:var(--error)">Code expired. Please request a new one.</span>';
            resendBtn.disabled = false;
            return;
        }

        const m = Math.floor(seconds / 60);
        const s = String(seconds % 60).padStart(2, '0');
        timerCount.textContent = `${m}:${s}`;

        // Enable resend after timer hits 0
        if (seconds === 0) resendBtn.disabled = false;
    }, 1000);

    // Enable resend after 60s even before timer runs out
    setTimeout(() => { resendBtn.disabled = false; }, 60 * 1000);

    // Auto focus first box
    boxes[0].focus();
</script>

</body>
</html>