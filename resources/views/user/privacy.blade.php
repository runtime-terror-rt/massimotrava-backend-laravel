<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $metaTitle ?? 'Privacy Policy' }} | Vyralabs</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
:root {
    --bg:#070b12;
    --bg2:#0d1117;
    --surface:#111827;
    --surface2:#1a2235;
    --border:rgba(255,255,255,.06);
    --border2:rgba(255,255,255,.1);
    --glass:rgba(13,17,23,.75);
    --c:#22d3ee;
    --c-glow:rgba(34,211,238,.3);
    --c2:#a78bfa;
    --c3:#34d399;
    --amber:#f59e0b;
    --text:#f1f5f9;
    --muted:#94a3b8;
    --muted2:#64748b;
    --font:'Plus Jakarta Sans',sans-serif;
    --body:'Inter',sans-serif;
    --r:14px;
    --r-sm:8px;
    --ease:cubic-bezier(.4,0,.2,1);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{
    font-family:var(--body);
    background:var(--bg);
    color:var(--text);
    min-height:100vh;
    overflow-x:hidden;
}
body::before{
    content:'';position:fixed;inset:0;
    background-image:
        linear-gradient(rgba(34,211,238,.03) 1px,transparent 1px),
        linear-gradient(90deg,rgba(34,211,238,.03) 1px,transparent 1px);
    background-size:60px 60px;
    pointer-events:none;z-index:0;
}
body::after{
    content:'';position:fixed;inset:0;
    background:radial-gradient(ellipse 80% 60% at 50% -20%,rgba(34,211,238,.07),transparent 70%);
    pointer-events:none;z-index:0;
}
a{color:inherit;text-decoration:none}
.container{max-width:1240px;margin:0 auto;padding:0 32px;position:relative;z-index:1}

/* ── NAVBAR (exact match from main site) ── */
.navbar{
    position:sticky;top:0;z-index:900;
    background:rgba(7,11,18,.85);
    backdrop-filter:blur(24px);
    -webkit-backdrop-filter:blur(24px);
    border-bottom:1px solid var(--border);
    transition:background .3s var(--ease);
}
.navbar.scrolled{background:rgba(7,11,18,.95)}
.nav-inner{display:flex;align-items:center;justify-content:space-between;height:72px}
.logo{
    font-family:var(--font);font-weight:800;font-size:22px;
    letter-spacing:-.5px;display:flex;align-items:center;gap:10px;
    background:linear-gradient(135deg,#fff 0%,var(--c) 100%);
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.logo .dot{
    width:10px;height:10px;border-radius:50%;
    background:linear-gradient(135deg,var(--c),var(--c2));
    box-shadow:0 0 20px var(--c-glow);
    animation:dotPulse 3s ease-in-out infinite;
}
@keyframes dotPulse{0%,100%{box-shadow:0 0 10px var(--c-glow)}50%{box-shadow:0 0 25px var(--c-glow),0 0 40px rgba(34,211,238,.15)}}
.nav-links{display:flex;align-items:center;gap:32px}
.nav-links a{
    font-size:13.5px;font-weight:500;color:var(--muted);
    display:flex;align-items:center;gap:6px;
    transition:color .25s;position:relative;
}
.nav-links a::after{
    content:'';position:absolute;bottom:-4px;left:0;right:0;
    height:1px;background:var(--c);
    transform:scaleX(0);transition:transform .25s var(--ease);
}
.nav-links a:hover{color:#fff}
.nav-links a:hover::after{transform:scaleX(1)}
.nav-actions{display:flex;align-items:center;gap:12px}
.btn{
    display:inline-flex;align-items:center;gap:8px;justify-content:center;
    padding:10px 22px;border-radius:var(--r-sm);
    font-size:13px;font-weight:700;cursor:pointer;border:none;
    font-family:var(--body);transition:all .25s var(--ease);
    white-space:nowrap;letter-spacing:.2px;
}
.btn-primary{
    background:linear-gradient(135deg,var(--c),#0ea5e9);
    color:#000;font-weight:800;
    box-shadow:0 4px 20px rgba(34,211,238,.3),inset 0 1px 0 rgba(255,255,255,.2);
    position:relative;overflow:hidden;
}
.btn-primary:hover{
    box-shadow:0 8px 30px rgba(34,211,238,.5),inset 0 1px 0 rgba(255,255,255,.2);
    transform:translateY(-1px);
}
.btn-ghost{
    background:rgba(255,255,255,.05);
    color:var(--text);border:1px solid var(--border2);
}
.btn-ghost:hover{background:rgba(255,255,255,.1);border-color:rgba(34,211,238,.4)}
.nav-toggle{
    display:none;background:none;border:1px solid var(--border);
    color:var(--text);width:40px;height:40px;border-radius:var(--r-sm);
    font-size:16px;cursor:pointer;
}

/* ── HERO SECTION ── */
.policy-hero{
    padding:72px 0 52px;
    border-bottom:1px solid var(--border);
    position:relative;overflow:hidden;
}
.policy-hero::before{
    content:'';
    position:absolute;top:-120px;left:50%;transform:translateX(-50%);
    width:600px;height:600px;border-radius:50%;
    border:0.5px solid rgba(34,211,238,.08);
    pointer-events:none;
}
.policy-hero::after{
    content:'';
    position:absolute;top:-60px;left:50%;transform:translateX(-50%);
    width:380px;height:380px;border-radius:50%;
    border:0.5px solid rgba(34,211,238,.05);
    pointer-events:none;
}
.hero-inner{text-align:center;position:relative;z-index:1}
.hero-eyebrow{
    display:inline-flex;align-items:center;gap:8px;
    font-size:11px;font-weight:700;letter-spacing:1.4px;text-transform:uppercase;
    color:var(--c);margin-bottom:16px;
}
.hero-eyebrow::before{content:'';width:20px;height:2px;background:linear-gradient(90deg,var(--c),var(--c2));border-radius:2px}
.hero-eyebrow::after{content:'';width:20px;height:2px;background:linear-gradient(90deg,var(--c2),var(--c));border-radius:2px}
.hero-title{
    font-family:var(--font);font-size:40px;font-weight:700;
    letter-spacing:-1.2px;color:#fff;line-height:1.1;margin-bottom:18px;
}
.hero-title .grad{
    background:linear-gradient(135deg,var(--c) 0%,var(--c2) 100%);
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.hero-meta{
    display:inline-flex;align-items:center;gap:20px;flex-wrap:wrap;justify-content:center;
    font-size:13px;color:var(--muted);
}
.hero-meta-item{display:flex;align-items:center;gap:6px}
.hero-meta-item i{color:var(--c);font-size:13px}
.hero-meta-sep{width:3px;height:3px;border-radius:50%;background:var(--border2)}

/* ── LAYOUT ── */
.policy-layout{
    max-width:1240px;margin:56px auto 100px auto;
    padding:0 32px;
    display:grid;
    grid-template-columns:260px 1fr;
    gap:56px;
    position:relative;z-index:1;
}

/* ── SIDEBAR ── */
.policy-sidebar{
    position:sticky;top:96px;
    height:fit-content;
}
.sidebar-label{
    font-size:11px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;
    color:var(--muted2);margin-bottom:14px;
}
.toc-list{list-style:none;border-left:1px solid var(--border)}
.toc-list li a{
    display:flex;align-items:center;gap:10px;
    padding:9px 16px;
    font-size:13px;color:var(--muted);font-weight:500;
    transition:all .2s;
    border-left:2px solid transparent;margin-left:-1px;
    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
}
.toc-list li a .toc-num{
    font-size:10px;color:var(--muted2);min-width:18px;
    font-variant-numeric:tabular-nums;
}
.toc-list li a:hover,
.toc-list li.active a{
    color:#fff;
    border-left-color:var(--c);
    background:linear-gradient(90deg,rgba(34,211,238,.05),transparent);
}
.toc-list li.active a .toc-num{color:var(--c)}

/* sidebar bottom card */
.sidebar-contact{
    margin-top:28px;
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:var(--r-sm);
    padding:18px;
}
.sidebar-contact p{font-size:12.5px;color:var(--muted);line-height:1.6;margin-bottom:12px}
.sidebar-contact a{
    display:inline-flex;align-items:center;gap:6px;
    font-size:12px;font-weight:600;color:var(--c);
    background:rgba(34,211,238,.08);
    border:1px solid rgba(34,211,238,.2);
    padding:7px 14px;border-radius:20px;
    transition:all .2s;
}
.sidebar-contact a:hover{background:rgba(34,211,238,.15)}

/* ── CONTENT STREAM ── */
.content-stream{display:flex;flex-direction:column;gap:14px}

.policy-card{
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:var(--r);
    overflow:hidden;
    transition:border-color .3s,box-shadow .3s;
}
.policy-card:hover{
    border-color:rgba(34,211,238,.18);
    box-shadow:0 16px 48px rgba(0,0,0,.35),0 0 24px rgba(34,211,238,.04);
}

/* card header — clickable accordion */
.card-head{
    display:flex;align-items:center;gap:16px;
    padding:22px 28px;cursor:pointer;
    transition:background .2s;
    user-select:none;
}
.card-head:hover{background:rgba(255,255,255,.02)}
.card-icon{
    width:38px;height:38px;border-radius:var(--r-sm);flex-shrink:0;
    background:rgba(34,211,238,.08);
    border:1px solid rgba(34,211,238,.15);
    display:flex;align-items:center;justify-content:center;
    transition:all .25s;
}
.card-icon i{font-size:15px;color:var(--c)}
.policy-card.open .card-icon{
    background:linear-gradient(135deg,var(--c),#0ea5e9);
    border-color:transparent;
}
.policy-card.open .card-icon i{color:#000}
.card-meta{flex:1;min-width:0}
.card-num{font-size:10px;color:var(--muted2);letter-spacing:.8px;margin-bottom:3px;text-transform:uppercase}
.card-title{font-family:var(--font);font-size:16px;font-weight:700;color:#fff}
.card-chevron{
    font-size:13px;color:var(--muted2);
    transition:transform .3s var(--ease),color .2s;
    flex-shrink:0;
}
.policy-card.open .card-chevron{transform:rotate(180deg);color:var(--c)}

/* card body */
.card-body{
    max-height:0;overflow:hidden;
    transition:max-height .4s var(--ease);
}
.policy-card.open .card-body{max-height:800px}
.card-body-inner{
    padding:0 28px 28px 82px;
    font-size:14.5px;color:var(--muted);
    line-height:1.85;white-space:pre-line;
}
.card-note{
    margin-top:18px;
    padding:13px 16px;
    background:rgba(34,211,238,.04);
    border:1px solid rgba(34,211,238,.12);
    border-radius:var(--r-sm);
    font-size:13px;color:var(--muted);
    display:flex;gap:10px;align-items:flex-start;line-height:1.65;
}
.card-note i{color:var(--c);font-size:14px;margin-top:1px;flex-shrink:0}

/* first card open by default */
.policy-card:first-child{border-color:rgba(34,211,238,.15)}

/* ── FOOTER (exact match) ── */
footer{background:var(--surface);border-top:1px solid var(--border);padding-top:60px;position:relative;z-index:1}
.footer-top{
    display:grid;grid-template-columns:1.5fr 1fr 1fr 1fr;gap:48px;
    padding-bottom:48px;border-bottom:1px solid var(--border);
}
.footer-brand p{font-size:13px;color:var(--muted);line-height:1.75;max-width:280px;margin:14px 0 18px}
.social-row{display:flex;gap:10px}
.social-row a{
    width:36px;height:36px;border-radius:var(--r-sm);border:1px solid var(--border);
    display:flex;align-items:center;justify-content:center;
    color:var(--muted);transition:all .25s;font-size:13px;
}
.social-row a:hover{background:rgba(34,211,238,.1);color:var(--c);border-color:rgba(34,211,238,.4)}
.footer-col h4{
    font-family:var(--font);font-size:12px;font-weight:700;
    color:#fff;letter-spacing:1.4px;text-transform:uppercase;margin-bottom:18px;
}
.footer-col ul{list-style:none;display:flex;flex-direction:column;gap:12px}
.footer-col a{font-size:13.5px;color:var(--muted);transition:color .2s}
.footer-col a:hover{color:var(--c)}
.footer-bottom{
    display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;
    padding:22px 0;font-size:12px;color:var(--muted2);
}
.footer-legal{display:flex;gap:18px;flex-wrap:wrap}
.footer-legal a{color:var(--muted2);transition:color .2s}
.footer-legal a:hover{color:var(--muted)}
.footer-legal a.active-policy{color:var(--c)}
.disclaimer{
    font-size:10.5px;color:var(--muted2);line-height:1.75;
    padding:20px 0 30px;border-top:1px solid var(--border);margin-top:8px;
}

/* ── RESPONSIVE ── */
@media(max-width:1080px){
    .policy-layout{grid-template-columns:1fr}
    .policy-sidebar{display:none}
}
@media(max-width:768px){
    .container,.policy-layout{padding:0 20px}
    .nav-links{display:none}
    .nav-toggle{display:flex;align-items:center;justify-content:center}
    .hero-title{font-size:32px}
    .footer-top{grid-template-columns:1fr 1fr;gap:32px}
    .footer-bottom{flex-direction:column;align-items:flex-start}
    .card-body-inner{padding:0 20px 24px 20px}
}
@media(max-width:480px){
    .footer-top{grid-template-columns:1fr}
    .hero-title{font-size:28px}
}

/* animations */
@keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
[data-reveal]{opacity:0;transform:translateY(24px);transition:opacity .6s var(--ease),transform .6s var(--ease)}
[data-reveal].revealed{opacity:1;transform:translateY(0)}
[data-reveal].d1{transition-delay:.1s}
[data-reveal].d2{transition-delay:.2s}
[data-reveal].d3{transition-delay:.3s}
    </style>
</head>
<body>

{{-- ── NAVBAR ── --}}
<nav class="navbar" id="navbar">
    <div class="container nav-inner">
        <div class="logo">
            <img src="{{ asset('images/logo.avif') }}" alt="Vyralabs"
                 style="position:absolute;top:15px;left:6%;transform:translateX(-50%);height:38px;width:auto;object-fit:contain;max-width:85%">
        </div>
        <div class="nav-links">
            <a href="{{ url('/') }}#how">What We Test <i class="fa-solid fa-chevron-down" style="font-size:10px;color:var(--muted2)"></i></a>
            <a href="{{ url('/') }}#pricing">Pricing</a>
            <a href="{{ url('/') }}#about">About Us</a>
            <a href="{{ url('/') }}#partners">Creator Partnerships</a>
        </div>
        <div class="nav-actions">
            <a href="{{ route('login') }}" class="btn btn-ghost">Login <i class="fa-solid fa-arrow-right-to-bracket"></i></a>
            <a href="{{ url('/') }}#pricing" class="btn btn-primary">Get Vyralabs</a>
            <button class="nav-toggle" id="navToggle"><i class="fa-solid fa-bars"></i></button>
        </div>
    </div>
</nav>

{{-- ── HERO ── --}}
<header class="policy-hero">
    <div class="container hero-inner" data-reveal>
        <div class="hero-eyebrow">Legal &amp; Privacy</div>
        <h1 class="hero-title">
            {{ $policy->name ?? '' }}<span class="grad">{{ isset($policy->name) ? '' : 'Privacy Policy' }}</span>
        </h1>
        <h3 class="hero-title">
            {{ $policy->title ?? '<span class="grad">Privacy</span> Policy' }}
        </h3>
        <div class="hero-meta">
            <div class="hero-meta-item">
                <i class="fa-regular fa-calendar-check"></i>
                Last Updated: <strong style="color:#fff">{{ $lastUpdated }}</strong>
            </div>
            <span class="hero-meta-sep"></span>
            <div class="hero-meta-item">
                <i class="fa-solid fa-shield-halved"></i>
                HIPAA Aligned
            </div>
            <span class="hero-meta-sep"></span>
            <div class="hero-meta-item">
                <i class="fa-solid fa-clock"></i>
                ~5 min read
            </div>
        </div>
    </div>
</header>

{{-- ── MAIN LAYOUT ── --}}
<div class="policy-layout">

    {{-- SIDEBAR --}}
    @if($policy && is_array($policy->content) && count($policy->content) > 0)
    <aside class="policy-sidebar" data-reveal>
        <div class="sidebar-label hero-eyebrow">Table of Contents</div>
        <ul class="toc-list">
            @foreach($policy->content as $index => $item)
                @if(!empty($item['heading']))
                <li class="toc-item {{ $index === 0 ? 'active' : '' }}">
                    <a href="#chapter-{{ $index + 1 }}">
                        <span class="toc-num">{{ sprintf('%02d', $index + 1) }}</span>
                        {{ $item['heading'] }}
                    </a>
                </li>
                @endif
            @endforeach
        </ul>

        <div class="sidebar-contact">
            <p>Have questions about our privacy practices?</p>
            <a href="mailto:privacy@vyralabs.com">
                <i class="fa-solid fa-envelope"></i> Contact Us
            </a>
        </div>
    </aside>
    @endif

    {{-- CONTENT --}}
    <main class="content-stream">

        @if($policy && is_array($policy->content) && count($policy->content) > 0)

            @php
            $icons = [
                'fa-database','fa-cpu','fa-share-nodes','fa-user-shield',
                'fa-cookie','fa-lock','fa-bell','fa-file-contract',
                'fa-globe','fa-envelope','fa-shield-halved','fa-key',
            ];
            @endphp

            @foreach($policy->content as $index => $item)
                @if(!empty($item['heading']) || !empty($item['content']))
                <div class="policy-card {{ $index === 0 ? 'open' : '' }}" id="chapter-{{ $index + 1 }}" data-reveal>
                    <div class="card-head" onclick="toggleCard(this)">
                        <div class="card-icon">
                            <i class="fa-solid {{ $icons[$index % count($icons)] }}"></i>
                        </div>
                        <div class="card-meta">
                            <div class="card-num">Section {{ sprintf('%02d', $index + 1) }}</div>
                            <div class="card-title">{{ $item['heading'] ?? '' }}</div>
                        </div>
                        <i class="fa-solid fa-chevron-down card-chevron"></i>
                    </div>
                    <div class="card-body">
                        <div class="card-body-inner">
                            {!! nl2br(e($item['content'] ?? '')) !!}
                        </div>
                    </div>
                </div>
                @endif
            @endforeach

        @else
            @php
            $fallback = [
                ['icon'=>'fa-database','title'=>'Information We Collect','body'=>'We collect information you provide directly — such as your name, email, and profile details — as well as data generated through your use of our services, including test records, booking history, and payment information.','note'=>'We never collect more than what is strictly necessary to deliver the service.'],
                ['icon'=>'fa-cpu','title'=>'How We Use Your Data','body'=>'Your data is used to process orders, deliver test results, send service notifications, and improve platform performance. We do not use your data for advertising or sell it to any third party.','note'=>null],
                ['icon'=>'fa-share-nodes','title'=>'Data Sharing','body'=>'We share minimal data with trusted service providers — including Stripe for payment processing and Firebase for notifications — only as required to deliver our services. All partners are contractually bound to protect your data.','note'=>null],
                ['icon'=>'fa-user-shield','title'=>'Your Rights','body'=>'You have the right to access, correct, or delete your personal data at any time. You may also request a copy of all data we hold about you. Contact us through the in-app support or at privacy@vyralabs.com.','note'=>null],
                ['icon'=>'fa-cookie','title'=>'Cookies','body'=>'We use only essential cookies required to maintain your session and remember your preferences. No tracking, profiling, or advertising cookies are used.','note'=>null],
                ['icon'=>'fa-lock','title'=>'Data Security','body'=>'We implement industry-standard security measures including end-to-end encryption, secure HTTPS connections, and regular security audits to protect your personal data.','note'=>'All payment data is handled exclusively by Stripe — we never store card details on our servers.'],
                ['icon'=>'fa-flask-vial','title'=>'Laboratory Services Consent','body'=>'This section will contain your consent to laboratory services provided through Vyralabs. Details will be updated soon.','note'=>null],
            ];
            @endphp
            @foreach($fallback as $i => $s)
            <div class="policy-card {{ $i === 0 ? 'open' : '' }}" id="chapter-{{ $i+1 }}" data-reveal>
                <div class="card-head" onclick="toggleCard(this)">
                    <div class="card-icon"><i class="fa-solid {{ $s['icon'] }}"></i></div>
                    <div class="card-meta">
                        <div class="card-num">Section {{ sprintf('%02d',$i+1) }}</div>
                        <div class="card-title">{{ $s['title'] }}</div>
                    </div>
                    <i class="fa-solid fa-chevron-down card-chevron"></i>
                </div>
                <div class="card-body">
                    <div class="card-body-inner">
                        {{ $s['body'] }}
                        @if($s['note'])
                        <div class="card-note">
                            <i class="fa-solid fa-circle-info"></i>
                            {{ $s['note'] }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        @endif

    </main>
</div>

{{-- ── FOOTER (exact match from main site) ── --}}
<footer>
    <div class="container">
        <div class="footer-top">
            <div class="footer-brand">
                <div class="logo"><span class="dot"></span> vyralabs</div>
                <p>Painless at-home blood testing for everyone. Clinical-grade insights, delivered to your dashboard in under 72 hours.</p>
                <div class="social-row">
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Get in Vyralabs</h4>
                <ul>
                    <li><a href="{{ url('/') }}#pricing">Start Testing</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="#">Careers</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Explore</h4>
                <ul>
                    <li><a href="{{ url('/') }}#how">Biomarkers We Test</a></li>
                    <li><a href="#">Customer Reviews</a></li>
                    <li><a href="{{ url('/') }}#about">About Us</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Partnerships</h4>
                <ul>
                    <li><a href="#">Creator Partnerships</a></li>
                    <li><a href="#">Affiliate Programs</a></li>
                    <li><a href="#">Chat With Us Now</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div>&copy; {{ date('Y') }} Vyralabs. All rights reserved.</div>
            <div class="footer-legal">
                <a href="{{ url('/terms-and-condition') }}">Terms &amp; Conditions</a>
                <a href="#">Refund Policy</a>
                <a href="{{ request()->url() }}" class="active-policy">Privacy Policy</a>
                <a href="#">Consumer Health Data Privacy Policy</a>
                <a href="{{ url('/laboratory-services-consent') }}">Laboratory Services Consent</a>
            </div>
        </div>

        <div class="disclaimer">
            DISCLAIMER: Vyralabs does not recommend or refer you to any healthcare providers, and you are free to choose any healthcare provider and to continue to use Vyralabs' services. Vyralabs does not offer medical advice, a diagnosis, medical treatment, or any form of medical opinion through our services or otherwise. We recommend that you discuss those questions with your primary care physician or other licensed provider. All material, information, data, and content that Vyralabs provides is strictly for general information purposes.
        </div>
    </div>
</footer>

<script>
// ── Navbar scroll
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 60);
});

// ── Mobile nav
document.getElementById('navToggle')?.addEventListener('click', () => {
    const links = document.querySelector('.nav-links');
    const visible = links.style.display === 'flex';
    links.style.cssText = visible ? '' : `
        display:flex;flex-direction:column;position:fixed;
        top:72px;left:0;right:0;background:rgba(7,11,18,.97);
        backdrop-filter:blur(20px);padding:24px 32px;gap:20px;
        border-bottom:1px solid rgba(255,255,255,.06);z-index:899;
    `;
});

// ── Accordion
function toggleCard(head) {
    const card = head.closest('.policy-card');
    const isOpen = card.classList.contains('open');
    document.querySelectorAll('.policy-card').forEach(c => c.classList.remove('open'));
    if (!isOpen) card.classList.add('open');
}

// ── Scroll reveal
const revealObs = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('revealed');
            revealObs.unobserve(e.target);
        }
    });
}, { threshold: 0.1 });
document.querySelectorAll('[data-reveal]').forEach(el => revealObs.observe(el));

// ── TOC active highlight on scroll
const sections = document.querySelectorAll('.policy-card[id]');
const tocItems = document.querySelectorAll('.toc-item');
window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(sec => {
        if (window.scrollY >= sec.offsetTop - 130) current = sec.id;
    });
    tocItems.forEach(item => {
        item.classList.remove('active');
        if (item.querySelector('a')?.getAttribute('href') === `#${current}`) {
            item.classList.add('active');
        }
    });
});
</script>
</body>
</html>