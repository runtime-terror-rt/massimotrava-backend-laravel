<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Successful | Vyralabs</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        html{scroll-behavior:smooth}
        body{
            font-family:'Inter',sans-serif;
            background:#070b12;
            color:#f1f5f9;
            min-height:100vh;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            overflow:hidden;
            position:relative;
        }

        /* Background */
        body::before{
            content:'';position:fixed;inset:0;
            background-image:
                linear-gradient(rgba(34,211,238,.03) 1px,transparent 1px),
                linear-gradient(90deg,rgba(34,211,238,.03) 1px,transparent 1px);
            background-size:60px 60px;pointer-events:none;z-index:0;
        }
        body::after{
            content:'';position:fixed;inset:0;
            background:radial-gradient(ellipse 80% 60% at 50% -10%,rgba(16,185,129,0.1),transparent 70%);
            pointer-events:none;z-index:0;
        }

        .wrap{
            position:relative;z-index:1;
            display:flex;flex-direction:column;align-items:center;
            text-align:center;padding:32px 24px;max-width:520px;width:100%;
        }

        /* Success ring animation */
        .success-ring{
            position:relative;width:100px;height:100px;margin-bottom:28px;
        }
        .success-ring svg{
            transform:rotate(-90deg);
            animation:ringDraw 1s ease forwards;
        }
        @keyframes ringDraw{
            from{stroke-dashoffset:283}
            to{stroke-dashoffset:0}
        }
        .success-icon{
            position:absolute;inset:0;
            display:flex;align-items:center;justify-content:center;
            font-size:36px;
            animation:iconPop .4s ease .8s both;
        }
        @keyframes iconPop{
            from{transform:scale(0);opacity:0}
            to{transform:scale(1);opacity:1}
        }

        /* Card */
        .card{
            background:#111827;
            border:1px solid rgba(16,185,129,0.2);
            border-radius:24px;
            padding:40px 36px;
            width:100%;
            box-shadow:0 0 60px rgba(16,185,129,0.06);
            animation:cardFade .6s ease .2s both;
        }
        @keyframes cardFade{
            from{opacity:0;transform:translateY(20px)}
            to{opacity:1;transform:translateY(0)}
        }

        .badge{
            display:inline-flex;align-items:center;gap:6px;
            font-size:11px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;
            color:#10b981;background:rgba(16,185,129,0.08);
            border:1px solid rgba(16,185,129,0.2);
            padding:5px 14px;border-radius:20px;margin-bottom:20px;
        }
        .badge-dot{width:6px;height:6px;border-radius:50%;background:#10b981;animation:blink 1.5s ease-in-out infinite}
        @keyframes blink{0%,100%{opacity:1}50%{opacity:0.3}}

        h1{
            font-family:'Plus Jakarta Sans',sans-serif;
            font-size:28px;font-weight:800;
            letter-spacing:-0.03em;color:#fff;
            margin-bottom:10px;line-height:1.2;
        }
        h1 span{
            background:linear-gradient(135deg,#10b981,#22d3ee);
            -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
        }
        .subtitle{font-size:14px;color:#94a3b8;line-height:1.7;margin-bottom:28px}

        /* Info rows */
        .info-rows{
            display:flex;flex-direction:column;gap:10px;
            margin-bottom:28px;text-align:left;
        }
        .info-row{
            display:flex;align-items:center;gap:12px;
            background:rgba(255,255,255,0.02);
            border:1px solid rgba(255,255,255,0.06);
            border-radius:12px;padding:12px 16px;
        }
        .info-row-icon{
            width:32px;height:32px;border-radius:8px;flex-shrink:0;
            display:flex;align-items:center;justify-content:center;font-size:14px;
        }
        .ir-green{background:rgba(16,185,129,0.1);color:#10b981}
        .ir-cyan{background:rgba(34,211,238,0.1);color:#22d3ee}
        .ir-violet{background:rgba(167,139,250,0.1);color:#a78bfa}
        .info-row-text{font-size:13px;color:#cbd5e1;line-height:1.5}
        .info-row-text b{color:#fff;font-weight:600}

        /* Buttons */
        .btn-row{display:flex;gap:12px;flex-wrap:wrap}
        .btn{
            flex:1;display:inline-flex;align-items:center;justify-content:center;gap:8px;
            padding:13px 20px;border-radius:12px;font-size:13.5px;font-weight:700;
            cursor:pointer;border:none;text-decoration:none;transition:all .2s;
            font-family:'Inter',sans-serif;white-space:nowrap;
        }
        .btn-primary{
            background:linear-gradient(135deg,#10b981,#06b6d4);color:#000;
            box-shadow:0 4px 20px rgba(16,185,129,0.25);
        }
        .btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(16,185,129,0.35)}
        .btn-ghost{
            background:rgba(255,255,255,0.04);color:#f1f5f9;
            border:1px solid rgba(255,255,255,0.1);
        }
        .btn-ghost:hover{background:rgba(255,255,255,0.08)}

        /* Confetti dots */
        .confetti{position:fixed;inset:0;pointer-events:none;z-index:0;overflow:hidden}
        .dot{
            position:absolute;border-radius:50%;
            animation:fall linear forwards;
        }
        @keyframes fall{
            from{transform:translateY(-20px) rotate(0deg);opacity:1}
            to{transform:translateY(110vh) rotate(720deg);opacity:0}
        }

        @media(max-width:480px){
            .card{padding:28px 20px}
            h1{font-size:22px}
            .btn-row{flex-direction:column}
        }
    </style>
</head>
<body>

    {{-- Confetti --}}
    <div class="confetti" id="confetti"></div>

    <div class="wrap">

        {{-- Success Ring --}}
        <div class="success-ring">
            <svg width="100" height="100" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="45" fill="none"
                    stroke="rgba(16,185,129,0.1)" stroke-width="4"/>
                <circle cx="50" cy="50" r="45" fill="none"
                    stroke="#10b981" stroke-width="4"
                    stroke-dasharray="283" stroke-dashoffset="283"
                    stroke-linecap="round"
                    style="animation:ringDraw 1s ease forwards"/>
            </svg>
            <div class="success-icon">✅</div>
        </div>

        {{-- Card --}}
        <div class="card">
            <div class="badge">
                <span class="badge-dot"></span> Payment Confirmed
            </div>

            <h1>You're in! Welcome to <span>Vyralabs.</span></h1>
            <p class="subtitle">
                Your subscription is now active. Your first home test kit will be on its way soon — get ready to unlock your longevity data.
            </p>

            <div class="info-rows">
                <div class="info-row">
                    <div class="info-row-icon ir-green">
                        <i class="fa-solid fa-box"></i>
                    </div>
                    <div class="info-row-text">
                        <b>Kit Dispatch</b><br>
                        Your first test kit will be shipped within 2–3 business days to your registered address.
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-row-icon ir-cyan">
                        <i class="fa-solid fa-flask-vial"></i>
                    </div>
                    <div class="info-row-text">
                        <b>Results in 7–14 Days</b><br>
                        Once your sample reaches the lab, your biomarker insights will appear in your dashboard.
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-row-icon ir-violet">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div class="info-row-text">
                        <b>Confirmation Email Sent</b><br>
                        Check your inbox at <b>{{ auth()->user()->email ?? 'your email' }}</b> for your receipt and next steps.
                    </div>
                </div>
            </div>

            <div class="btn-row">
                <a href="{{ route('user.dashboard.index') }}" class="btn btn-primary">
                    <i class="fa-solid fa-gauge-high"></i> Go to Dashboard
                </a>
                <a href="{{ route('home.index') }}" class="btn btn-ghost">
                    <i class="fa-solid fa-house"></i> Home
                </a>
            </div>
        </div>

    </div>

    <script>
        // Confetti
        const colors = ['#10b981','#22d3ee','#a78bfa','#f59e0b','#34d399','#6366f1'];
        const container = document.getElementById('confetti');
        for (let i = 0; i < 55; i++) {
            const dot = document.createElement('div');
            const size = Math.random() * 8 + 4;
            dot.className = 'dot';
            dot.style.cssText = `
                width:${size}px;height:${size}px;
                left:${Math.random() * 100}%;
                background:${colors[Math.floor(Math.random() * colors.length)]};
                animation-duration:${Math.random() * 3 + 2}s;
                animation-delay:${Math.random() * 1.5}s;
                opacity:${Math.random() * 0.7 + 0.3};
            `;
            container.appendChild(dot);
        }
    </script>

</body>
</html>