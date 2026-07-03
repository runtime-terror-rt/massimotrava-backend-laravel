<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', App::getLocale()) }}">
  <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Primary SEO Meta Tags -->
  <title>Vyralabs | The World's Easiest Performance Lab Test</title>
  <meta name="description" content="Vyralabs offers the world's easiest performance lab testing. Track biomarkers, monitor testosterone, vitamin D, ferritin, and optimize your health with fast, certified results.">
  <meta name="keywords" content="performance lab test, biomarker tracking, health monitoring, Vyralabs, blood test, certified lab results, testosterone test">
  <meta name="robots" content="index, follow">
  <meta name="theme-color" content="#070b12">
  <link rel="canonical" href="{{ url()->current() }}">

  <!-- Hreflang alternates for multi-language SEO -->
  <link rel="alternate" hreflang="en" href="{{ route('lang.switch', 'en') }}">
  <link rel="alternate" hreflang="it" href="{{ route('lang.switch', 'it') }}">
  <link rel="alternate" hreflang="de" href="{{ route('lang.switch', 'de') }}">
  <link rel="alternate" hreflang="x-default" href="{{ url('/') }}">

  <!-- Open Graph / Facebook SEO Meta Tags -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:title" content="Vyralabs | The World's Easiest Performance Lab Test">
  <meta property="og:description" content="Track your biomarkers, testosterone, longevity score, and get certified results in under 72 hours with Vyralabs.">
  <meta property="og:image" content="{{ asset('images/dna-banner.png') }}">
  <meta property="og:site_name" content="Vyralabs">
  <meta property="og:locale" content="{{ App::getLocale() }}">

  <!-- Twitter Card Meta Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:url" content="{{ url()->current() }}">
  <meta name="twitter:title" content="Vyralabs | The World's Easiest Performance Lab Test">
  <meta name="twitter:description" content="Optimize your performance with the world's easiest home lab testing by Vyralabs.">
  <meta name="twitter:image" content="{{ asset('images/dna-banner.png') }}">

  <script type="application/ld+json">
  {
    "@@context": "https://schema.org",
    "@type": "MedicalOrganization",
    "name": "Vyralabs",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('images/logo.avif') }}",
    "description": "The World's Easiest Performance Lab Test. Track biomarkers, longevity metrics, and optimize health dynamically.",
    "knowsAbout": ["Biomarker Testing", "Blood Analytics", "Performance Optimization", "Wellness Tracking"]
  }
  </script>

  <link rel="icon" type="image/jpeg" href="{{ asset('images/favicon.png') }}">

  <!-- Performance: preconnect to all cross-origin hosts used on this page -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="dns-prefetch" href="https://fonts.googleapis.com">
  <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
  <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

  <!-- Performance: preload the LCP hero background image -->
  <link rel="preload" as="image" href="{{ asset('images/dna-banner.png') }}" fetchpriority="high">
  <link rel="preload" as="image" href="{{ asset('images/logo.avif') }}">

  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

  <!-- Font Awesome loaded non-render-blocking (swapped in once ready) -->
  <link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" media="print" onload="this.media='all'">
  <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>
  <link rel="stylesheet" href="{{ asset('frontend/user.css') }}">
  </head>
  <body>

    <!-- ANNOUNCE BAR -->
    @php
        $latestCampaign = $campaigns->where('status', 'active')->first();
    @endphp

    @if($latestCampaign)
        <div class="vyr-announce-section">
            <div class="vyr-announce-inner">
                <span class="vyr-live-pulse"></span>
                <div class="vyr-announce-text-wrap">
                    <strong class="vyr-announce-strong">
                        {{ $latestCampaign->title }}:
                    </strong>
                    <span class="vyr-announce-desc">
                        {{ Str::limit(strip_tags($latestCampaign->description), 85, '...') }}
                    </span>
                    @if($latestCampaign->end_date)
                        <span class="vyr-announce-date">
                            ({{ __('messages.campaign_ends') }}: {{ \Carbon\Carbon::parse($latestCampaign->end_date)->format('M d') }})
                        </span>
                    @endif
                </div>
                @if($latestCampaign->action_url)
                    <a href="{{ $latestCampaign->action_url }}" target="_blank" class="vyr-announce-pill-btn">
                        {{ __('messages.btn_claim') }} <i class="fa-solid fa-arrow-right" style="font-size:7.5px;"></i>
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- NAVBAR -->
    <nav class="navbar" id="navbar">
      <div class="container nav-inner">

        <div class="logo">
          <a href="{{ url('/') }}" style="display: flex; align-items: center;">
            <img
              src="{{ asset('images/logo.avif') }}"
              alt="{{ __('messages.logo_alt') }}"
              fetchpriority="high"
              decoding="async"
              style="height: 34px; width: auto; object-fit: contain;">
          </a>
        </div>

        <div class="nav-links">
          <a href="#how">{{ __('messages.nav_what_we_test') }} <i class="fa-solid fa-chevron-down"></i></a>
          <a href="#pricing">{{ __('messages.nav_pricing') }}</a>
          <a href="#about">{{ __('messages.nav_about_us') }}</a>
          <a href="#partners">{{ __('messages.nav_partnerships') }}</a>

          <!-- Mobile-only auth links (shown via .nav-links-auth in the mobile menu) -->
          <div class="nav-links-auth">
            @auth
              <a href="{{ url('/dashboard') }}" class="btn btn-ghost">
                <i class="fa-solid fa-user" style="margin-right: 5px;"></i> {{ auth()->user()->name }}
              </a>
              <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="btn btn-ghost" style="color: #ef4444;">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
              </a>
            @else
              <a href="{{ route('login') }}" class="btn btn-ghost">
                {{ __('messages.btn_login') }} <i class="fa-solid fa-arrow-right-to-bracket"></i>
              </a>
              <a href="{{ route('register') }}" class="btn btn-primary">
                {{ __('messages.btn_get_started') }}
              </a>
            @endauth
          </div>
        </div>

        <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
          @csrf
        </form>

        <div class="nav-actions">

          <div class="lang-dropdown" style="position: relative; z-index: 999999 !important;">
            <button class="lang-btn" onclick="toggleLangMenu(event)"
              style="background: var(--surface-hover, rgba(0,0,0,.04)); border: 1px solid var(--border, rgba(0,0,0,.08));
                    color: var(--text, #1e293b); padding: 6px 12px; border-radius: 8px; cursor: pointer;
                    display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; transition: .2s;">
              @if(App::getLocale() === 'it')
                <img src="https://flagcdn.com/16x12/it.png" width="16" height="12" alt="Italy Flag" style="border-radius: 2px; object-fit: cover;">
                <span>IT</span>
              @elseif(App::getLocale() === 'de')
                <img src="https://flagcdn.com/16x12/de.png" width="16" height="12" alt="Germany Flag" style="border-radius: 2px; object-fit: cover;">
                <span>DE</span>
              @else
                <img src="https://flagcdn.com/16x12/gb.png" width="16" height="12" alt="UK Flag" style="border-radius: 2px; object-fit: cover;">
                <span>EN</span>
              @endif
              <i class="fa-solid fa-chevron-down" style="font-size: 10px; color: var(--text-muted, #94a3b8); transition: transform .2s;" id="langChevron"></i>
            </button>

            <ul class="custom-lang-menu" id="customLangMenu"
              style="display: none; position: absolute; right: 0; top: calc(100% + 6px); min-width: 140px;
                    background: var(--surface, #fff); border: 1px solid var(--border, #e2e8f0); border-radius: 8px;
                    box-shadow: 0 10px 25px -5px rgba(0,0,0,.1); padding: 4px; list-style: none; margin: 0; z-index: 999;">
              <li>
                <a href="{{ route('lang.switch', 'it') }}"
                  style="color: {{ App::getLocale() === 'it' ? 'var(--accent, #6366f1)' : 'var(--text, #334155)' }};
                          font-size: 13px; padding: 8px 12px; border-radius: 6px; text-decoration: none;
                          display: flex; align-items: center; gap: 8px;
                          background: {{ App::getLocale() === 'it' ? 'rgba(99,102,241,.08)' : 'transparent' }};
                          font-weight: {{ App::getLocale() === 'it' ? '600' : 'normal' }};">
                  <img src="https://flagcdn.com/16x12/it.png" width="16" height="12" alt="Italiano" style="border-radius: 1px; object-fit: cover;">
                  Italiano
                </a>
              </li>
              <li>
                <a href="{{ route('lang.switch', 'en') }}"
                  style="color: {{ App::getLocale() === 'en' ? 'var(--accent, #6366f1)' : 'var(--text, #334155)' }};
                          font-size: 13px; padding: 8px 12px; border-radius: 6px; text-decoration: none;
                          display: flex; align-items: center; gap: 8px;
                          background: {{ App::getLocale() === 'en' ? 'rgba(99,102,241,.08)' : 'transparent' }};
                          font-weight: {{ App::getLocale() === 'en' ? '600' : 'normal' }};">
                  <img src="https://flagcdn.com/16x12/gb.png" width="16" height="12" alt="English" style="border-radius: 1px; object-fit: cover;">
                  English
                </a>
              </li>
              <li>
                <a href="{{ route('lang.switch', 'de') }}"
                  style="color: {{ App::getLocale() === 'de' ? 'var(--accent, #6366f1)' : 'var(--text, #334155)' }};
                          font-size: 13px; padding: 8px 12px; border-radius: 6px; text-decoration: none;
                          display: flex; align-items: center; gap: 8px;
                          background: {{ App::getLocale() === 'de' ? 'rgba(99,102,241,.08)' : 'transparent' }};
                          font-weight: {{ App::getLocale() === 'de' ? '600' : 'normal' }};">
                  <img src="https://flagcdn.com/16x12/de.png" width="16" height="12" alt="Deutsch" style="border-radius: 1px; object-fit: cover;">
                  Deutsch
                </a>
              </li>
            </ul>
          </div>

          <!-- SINGLE desktop auth block: logged-in -> profile dropdown, guest -> login/register -->
          <div class="nav-auth-desktop" style="display: flex; align-items: center; gap: 15px;">
            @auth
              <div class="user-profile-dropdown">
                <button class="btn btn-ghost dropdown-toggle" type="button"
                        onclick="toggleDropdown('desktopDropdown')"
                        style="display: flex; align-items: center; gap: 6px;">
                  <i class="fa-solid fa-user-circle" style="font-size: 16px;"></i>
                  {{ auth()->user()->name }}
                  <i class="fa-solid fa-chevron-down" style="font-size: 10px;"></i>
                </button>
                <div id="desktopDropdown" class="dropdown-menu-custom">
                  @if(auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('lab')))
                    <a href="{{ url('/admin/dashboard') }}"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                  @else
                    <a href="{{ url('/user/dashboard') }}"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                  @endif
                  <hr style="border-top: 1px solid rgba(255,255,255,.1); margin: 5px 0;">
                  <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #ef4444;">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                  </a>
                </div>
              </div>
            @else
              <a href="{{ route('login') }}" class="btn btn-ghost">
                {{ __('messages.btn_login') }} <i class="fa-solid fa-arrow-right-to-bracket"></i>
              </a>
              <a href="{{ route('register') }}" class="btn btn-primary">
                {{ __('messages.btn_get_started') }}
              </a>
            @endauth
          </div>

          <button class="nav-toggle"><i class="fa-solid fa-bars"></i></button>
        </div>
      </div>
    </nav>
    <!-- ================= /NAVBAR ================= -->

    <!-- HERO -->
    <header class="hero" style="
      background-image:
        linear-gradient(270deg,rgba(7,11,18,0) 0%,rgba(7,11,18,.75) 50%,rgba(7,11,18,1) 100%),
        url('images/dna-banner.png');
      background-size:100% 100%,cover;
      background-position:center,right center;
      background-repeat:no-repeat; ">
      <div class="hero-bg-orb"></div>
      <div class="container hero-grid">
        <div class="hero-copy">
          <div class="badge-row" style="animation:fadeUp .5s ease both">
            <span class="badge"><i class="fa-solid fa-circle-check"></i> {{ __('messages.hero_badge_accuracy') }}</span>
            <span class="badge"><i class="fa-solid fa-shield-halved"></i> {{ __('messages.hero_badge_certified') }}</span>
          </div>
          <h1 style="animation:fadeUp .55s ease both .08s">
            {{ __('messages.hero_title_start') }}<br><span class="grad">{{ __('messages.hero_title_grad') }}</span>
          </h1>
          <p style="animation:fadeUp .55s ease both .16s">{{ __('messages.hero_description') }}</p>
          <div class="hero-cta-row" style="animation:fadeUp .55s ease both .22s">
            <a href="#pricing" class="btn btn-primary btn-lg">{{ __('messages.hero_btn_try_now') }} <span style="opacity:.65;font-style:italic;font-weight:500">{{ __('messages.hero_btn_risk_free') }}</span></a>
          </div>
          <div class="hero-trustline" style="animation:fadeUp .55s ease both .28s">
            <div><i class="fa-solid fa-check"></i> {{ __('messages.trust_hsa_fsa') }}</div>
            <div><i class="fa-solid fa-droplet"></i> {{ __('messages.trust_millions_delivered') }}</div>
            <div><span class="stars">★★★★★</span>&nbsp; {{ __('messages.trust_rating') }}</div>
          </div>
          <div class="trust-strip" style="animation:fadeUp .55s ease both .36s">
            <div class="trust-item"><i class="fa-solid fa-shield-halved"></i> {{ __('messages.strip_hipaa') }}</div>
            <div class="trust-item"><i class="fa-solid fa-globe"></i> {{ __('messages.strip_clia') }}</div>
            <div class="trust-item"><i class="fa-solid fa-award"></i> {{ __('messages.strip_cap') }}</div>
            <div class="trust-item"><i class="fa-solid fa-location-dot"></i> {{ __('messages.strip_fda') }}</div>
          </div>
        </div>

        <div class="hero-visual" style="animation:fadeUp .7s ease both .1s">
          <div class="float-card float-1"><i class="fa-solid fa-shield"></i> {{ __('messages.float_results_time') }}</div>
          <div class="float-card float-2"><i class="fa-solid fa-droplet"></i> {{ __('messages.float_sample_type') }}</div>
          <div class="sphere-wrap">
            <div class="sphere-ring ring-1"></div>
            <div class="sphere-ring ring-2"></div>
            <div class="sphere-ring ring-3"></div>
            <div class="orb-orbit"><div class="orb-dot"></div></div>
            <div class="sphere-core">{{ __('messages.sphere_core_text') }}</div>
          </div>
        </div>
      </div>
    </header>

    <!-- HOW IT WORKS -->
    <section class="how-section" id="how">
      <div class="container how-grid">
        <div class="how-visual" data-reveal>
          <div class="how-visual-orb">{{ __('messages.sphere_core_text') }}</div>
        </div>
        <div class="how-steps">
          <div class="section-eyebrow">{{ __('messages.how_eyebrow') }}</div>
          <div class="how-step" data-reveal>
            <div class="how-num">1</div>
            <div class="how-step-body">
              <div class="tag">{{ __('messages.how_step1_tag') }}</div>
              <h3>{{ __('messages.how_step1_title') }}</h3>
              <p>{{ __('messages.how_step1_desc') }}</p>
            </div>
          </div>
          <div class="how-step" data-reveal delay-1>
            <div class="how-num">2</div>
            <div class="how-step-body">
              <div class="tag">{{ __('messages.how_step2_tag') }}</div>
              <h3>{{ __('messages.how_step2_title') }}</h3>
              <p>{{ __('messages.how_step2_desc') }}</p>
            </div>
          </div>
          <div class="how-step" data-reveal delay-2>
            <div class="how-num">3</div>
            <div class="how-step-body">
              <div class="tag">{{ __('messages.how_step3_tag') }}</div>
              <h3>{{ __('messages.how_step3_title') }}</h3>
              <p>{{ __('messages.how_step3_desc') }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- COMPARE -->
    <section class="compare-section">
      <div class="container compare-grid">
        <div data-reveal>
          <div class="section-eyebrow">{{ __('messages.compare_eyebrow') }}</div>
          <h2 class="section-title">{{ __('messages.compare_title_start') }} <strong>{{ __('messages.compare_title_strong') }}</strong> {{ __('messages.compare_title_end') }}</h2>
          <p class="section-sub">{{ __('messages.compare_description') }}</p>
        </div>
        <div class="compare-img-wrap" data-reveal delay-1>
          <img src="images/bloodtube.png" alt="{{ __('messages.compare_img_alt') }}" loading="lazy" decoding="async" style="max-width:340px;border-radius:var(--r);filter:drop-shadow(0 0 30px rgba(34,211,238,.15))">
        </div>
      </div>
    </section>

    <!-- INSIGHTS -->
    <section class="insights-section" id="insights">
      <div class="container">
        <div class="insights-head" data-reveal>
          <h2>{{ __('messages.insights_title_start') }}<br>{{ __('messages.insights_title_end') }}</h2>
          <p>{{ __('messages.insights_description') }}</p>
        </div>

        <div class="insights-cards-grid">
          <!-- Photo -->
          <div class="i-card photo-card" data-reveal>
            <img src="images/1.webp" alt="{{ __('messages.insights_athlete_alt') }}" loading="lazy" decoding="async">
          </div>

          <!-- Ferritin trend -->
          <div class="i-card" data-reveal delay-1>
            <div class="ic-header">
              <span class="ic-title">{{ __('messages.biomarker_ferritin') }}</span>
              <span class="ic-badge cyan">{{ __('messages.badge_optimal') }}</span>
            </div>
            <div class="ferritin-mini-chart">
              <svg viewBox="0 0 200 70" preserveAspectRatio="none">
                <defs>
                  <linearGradient id="fl" x1="0" y1="0" x2="1" y2="0">
                    <stop offset="0%" stop-color="#f59e0b"/>
                    <stop offset="40%" stop-color="#f59e0b"/>
                    <stop offset="60%" stop-color="#22d3ee"/>
                    <stop offset="100%" stop-color="#22d3ee"/>
                  </linearGradient>
                  <filter id="glow"><feGaussianBlur stdDeviation="2" result="blur"/><feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge></filter>
                </defs>
                <line x1="0" y1="35" x2="200" y2="35" stroke="rgba(255,255,255,.08)" stroke-width="1"/>
                <polyline points="0,58 40,52 80,30 120,24 160,18 200,14" fill="none" stroke="url(#fl)" stroke-width="2.5" stroke-linecap="round" filter="url(#glow)"/>
                <circle cx="80" cy="30" r="3.5" fill="#22d3ee" filter="url(#glow)"/>
                <circle cx="120" cy="24" r="3.5" fill="#22d3ee" filter="url(#glow)"/>
                <circle cx="160" cy="18" r="3.5" fill="#22d3ee" filter="url(#glow)"/>
                <circle cx="200" cy="14" r="3.5" fill="#22d3ee" filter="url(#glow)"/>
              </svg>
            </div>
            <div class="chart-months">
                <span>{{ __('messages.month_short_aug') }}</span>
                <span>{{ __('messages.month_short_sep') }}</span>
                <span>{{ __('messages.month_short_oct') }}</span>
                <span>{{ __('messages.month_short_nov') }}</span>
                <span>{{ __('messages.month_short_dec') }}</span>
            </div>
          </div>

          <!-- Score gauge -->
          <div class="i-card" data-reveal delay-2>
            <div class="ic-header">
              <div>
                <div style="font-size:10px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:var(--muted2);margin-bottom:3px">{{ __('messages.sphere_core_text') }}</div>
                <span class="ic-title">{{ __('messages.score_longevity_title') }}</span>
              </div>
              <span class="ic-badge amber">{{ __('messages.badge_optimal') }}</span>
            </div>
            <div class="gauge-wrap">
              <svg viewBox="0 0 160 160" style="transform:rotate(-90deg)">
                <circle cx="80" cy="80" r="64" fill="none" stroke="rgba(255,255,255,.06)" stroke-width="12"/>
                <circle cx="80" cy="80" r="64" fill="none" stroke="#f59e0b" stroke-width="12" stroke-dasharray="402" stroke-dashoffset="76" stroke-linecap="round"/>
                <circle cx="80" cy="80" r="64" fill="none" stroke="#22d3ee" stroke-width="12" stroke-dasharray="402" stroke-dashoffset="360" stroke-linecap="round"/>
              </svg>
              <div class="gauge-center">
                <span class="gauge-num">87%</span>
                <span class="gauge-arrow"><i class="fa-solid fa-arrow-up"></i></span>
              </div>
            </div>
          </div>

          <!-- Biomarkers -->
          <div class="i-card" data-reveal delay-3>
            <div class="ic-header">
              <span class="ic-title">{{ __('messages.biomarkers_title') }}</span>
              <span class="ic-badge green">+12%</span>
            </div>
            <div style="font-size:16px;font-weight:600;color:#fff;margin-top:6px">{{ __('messages.biomarker_testosterone') }}</div>
            <div style="font-size:12px;color:var(--muted);margin-top:3px">{{ __('messages.indicator_vitality') }}</div>
            <div class="bm-bar-track">
              <div class="seg" style="width:25%;background:#f59e0b"></div>
              <div class="seg" style="width:35%;background:rgba(255,255,255,.06)"></div>
              <div class="seg" style="width:30%;background:var(--c)"></div>
              <div class="seg" style="width:10%;background:var(--c3)"></div>
            </div>
          </div>

          <!-- Insight text -->
          <div class="i-card" data-reveal delay-1>
            <div class="insight-tag"><i class="fa-regular fa-clock"></i> {{ __('messages.insight_tag_month') }}</div>
            <p style="font-size:13.5px;color:var(--text);line-height:1.7;margin-bottom:8px">
              {{ __('messages.insight_body_text_p1') }}
            </p>
            <p style="font-size:13px;color:var(--muted);line-height:1.7">
              {{ __('messages.insight_body_text_p2') }} 
              <span class="read-more">{{ __('messages.link_read_more') }}</span>
            </p>
            <div class="ask-chat"><i class="fa-regular fa-comment-dots"></i> {{ __('messages.link_ask_chat') }}</div>
          </div>
        </div>
      </div>
    </section>


    <!-- BIOMARKERS INFINITE MOVING SECTION -->
    <section class="biomarkers-moving-section">
      <div class="container biomarkers-moving-grid">
        
        <!-- Left Column: Title & Count -->
        <div class="bm-left-col" data-reveal>
          <div class="bm-title-wrap">
            <h2 class="section-title"><i class="fa-solid fa-circle-check" style="color: var(--c3); font-size: 28px;"></i> <strong>{{ __('messages.moving_title_strong') }}</strong> {{ __('messages.moving_title_end') }}</h2>
            <p class="section-sub">{{ __('messages.moving_sub_title') }}</p>
          </div>
          
          <div class="bm-counter-box">
            <span class="bm-lbl">{{ __('messages.biomarkers_title') }}</span>
            <h3 class="bm-count">25+</h3>
          </div>
        </div>

        <!-- Center Column: Visual & Auto Moving List -->
        <div class="bm-center-col" data-reveal delay-1>
          <div class="bm-card-wrapper">
            <!-- Background Device Image Placeolder -->
            <div class="bm-image-side">
              <img src="images/3dbiomarker.jpg" alt="{{ __('messages.device_img_alt') }}" class="bm-main-img" loading="lazy" decoding="async">
              <!-- Gradient overlay matching the mockup vibe -->
              <div class="bm-img-overlay"></div>
            </div>
            
            <!-- Right Content inside card: Auto Moving Marquee -->
            <div class="bm-list-side">
              <div class="marquee-vertical-container">
                <div class="marquee-vertical-track">
                  
                  <!-- Original List -->
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_vit_d') }}</span><span class="ic-badge cyan">{{ __('messages.badge_optimal') }}</span></div>
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_testosterone') }}</span><span class="ic-badge cyan">{{ __('messages.badge_optimal') }}</span></div>
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_tsh') }}</span><span class="ic-badge amber">{{ __('messages.badge_out_of_range') }}</span></div>
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_free_t3') }}</span><span class="ic-badge cyan">{{ __('messages.badge_optimal') }}</span></div>
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_creatinine') }}</span><span class="ic-badge cyan">{{ __('messages.badge_optimal') }}</span></div>
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_estrogen') }}</span><span class="ic-badge cyan">{{ __('messages.badge_optimal') }}</span></div>
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_ferritin') }}</span><span class="ic-badge amber">{{ __('messages.badge_out_of_range') }}</span></div>
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_progesterone') }}</span><span class="ic-badge cyan">{{ __('messages.badge_optimal') }}</span></div>
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_cortisol') }}</span><span class="ic-badge green">{{ __('messages.badge_normal') }}</span></div>
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_hba1c') }}</span><span class="ic-badge green">{{ __('messages.badge_normal') }}</span></div>

                  <!-- Duplicate List for Seamless Infinite Loop -->
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_vit_d') }}</span><span class="ic-badge cyan">{{ __('messages.badge_optimal') }}</span></div>
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_testosterone') }}</span><span class="ic-badge cyan">{{ __('messages.badge_optimal') }}</span></div>
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_tsh') }}</span><span class="ic-badge amber">{{ __('messages.badge_out_of_range') }}</span></div>
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_free_t3') }}</span><span class="ic-badge cyan">{{ __('messages.badge_optimal') }}</span></div>
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_creatinine') }}</span><span class="ic-badge cyan">{{ __('messages.badge_optimal') }}</span></div>
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_estrogen') }}</span><span class="ic-badge cyan">{{ __('messages.badge_optimal') }}</span></div>
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_ferritin') }}</span><span class="ic-badge amber">{{ __('messages.badge_out_of_range') }}</span></div>
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_progesterone') }}</span><span class="ic-badge cyan">{{ __('messages.badge_optimal') }}</span></div>
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_cortisol') }}</span><span class="ic-badge green">{{ __('messages.badge_normal') }}</span></div>
                  <div class="bm-item-row"><span>{{ __('messages.biomarker_hba1c') }}</span><span class="ic-badge green">{{ __('messages.badge_normal') }}</span></div>

                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column: View All CTA -->
        <div class="bm-right-col" data-reveal delay-2>
          <a href="#pricing" class="view-all-bm-btn">
            <span>{{ __('messages.btn_view_all_biomarkers') }}</span>
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
          </a>
        </div>

      </div>
    </section>

    <section class="vyr-knowledge-stream-section">
        <div class="container vyr-knowledge-container">
            
            <div class="vyr-stream-header-wrapper">
                <div class="vyr-header-left">
                    <div class="section-eyebrow vyr-stream-eyebrow">{{ __('messages.stream_eyebrow') }}</div>
                    <h2 class="section-title vyr-stream-title">
                        {{ __('messages.stream_title_start') }} <strong>{{ __('messages.stream_title_strong') }}</strong>
                    </h2>
                </div>
                
                <div class="vyr-slider-navigation">
                    <button type="button" class="vyr-swiper-prev vyr-nav-btn"><i class="fa-solid fa-chevron-left"></i></button>
                    <button type="button" class="vyr-swiper-next vyr-nav-btn"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>

            <div class="vyr-swiper-outer">
                <div class="swiper vyr-knowledge-swiper">
                    <div class="swiper-wrapper">
                        
                        @forelse($contents->where('type', 'video') as $content)
                            <div class="swiper-slide vyr-unified-card stream-item-node type-video" data-type-node="video">

                                <div class="vyr-node-media-box vyr-video-only-trigger" data-video-trigger data-stream-url="{{ $content->video_url }}" data-video-title="{{ $content->title }}" data-video-desc="{{ Str::limit(strip_tags($content->body ?? ''), 220, '...') }}">
                                    @php
                                        $videoId = '';
                                        $rawUrl  = $content->video_url ?? '';
                                        if (preg_match('/(?:v=|\/embed\/|\/shorts\/|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $rawUrl, $match)) {
                                            $videoId = $match[1];
                                        }
                                    @endphp

                                    @if($videoId)
                                        <iframe
                                            src="https://www.youtube.com/embed/{{ $videoId }}?autoplay=1&mute=1&loop=1&playlist={{ $videoId }}&controls=0&rel=0&playsinline=1&modestbranding=1"
                                            class="vyr-video-iframe"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>
                                    @elseif($content->featured_image)
                                        <img src="{{ asset($content->featured_image) }}" alt="{{ $content->title }}" loading="lazy" decoding="async" class="vyr-video-fallback-img">
                                    @else
                                        <div class="vyr-video-fallback-icon">
                                            <i class="fa-solid fa-feather-pointed"></i>
                                        </div>
                                    @endif

                                    <span class="vyr-badge-indicator">
                                        video
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="vyr-empty-state-fallback">
                                <i class="fa-solid fa-cubes-stacked"></i>
                                <p>{{ __('messages.stream_empty_state') }}</p>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>

        </div>
    </section>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>

    <div id="vyrEcosystemVideoModal" class="vyr-embedded-overlay-modal">
        <div class="vyr-modal-viewport-container vyr-video-modal-box">
            <div class="vyr-modal-top-bar">
                <span id="vyrVideoModalTitle">Vyralabs Media Stream Hub</span>
                <button id="vyrEcosystemCloseBtn" aria-label="Close Stream Player">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="vyr-modal-video-shell">
                <iframe id="vyrEcosystemIframeTarget" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                <div class="vyr-video-shell-overlay"></div>
            </div>
            <div id="vyrVideoModalDesc" class="vyr-video-modal-desc"></div>
        </div>
    </div>

    <div id="vyrEcosystemArticleModal" class="vyr-embedded-overlay-modal">
        <div class="vyr-modal-viewport-container vyr-article-modal-box">
            <div class="vyr-modal-top-bar">
                <span id="vyrArticleModalMetaDate" class="vyr-article-meta-date">ARTICLE INSIGHT</span>
                <button id="vyrArticleCloseBtn" aria-label="Close Article Reader">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="vyr-article-body-wrap">
                <h2 id="vyrArticleModalTitle" class="vyr-article-modal-title"></h2>
                <div id="vyrArticleModalBody" class="vyr-article-modal-body"></div>
            </div>
        </div>
    </div>
    <!-- USE CASES -->
    <section id="about">
      <div class="container">
        <div class="section-head center" data-reveal>
          <div class="section-eyebrow">For every part of life</div>
          <h2 class="section-title">Vyralabs' all-in-one panel is built<br>for every part of your life.</h2>
          <p class="section-sub">Whether you're chasing performance, planning ahead, or just want clarity — Vyralabs is your roadmap to better health.</p>
        </div>
        <div class="usecase-grid">
          <div class="usecase-card" data-reveal>
            <div class="usecase-icon-wrap"><i class="fa-solid fa-person-running"></i></div>
            <div class="usecase-body">
              <h4>Athletic Performance</h4>
              <p>See how your body produces energy and adapts to training — and train smarter, not harder.</p>
            </div>
          </div>
          <div class="usecase-card" data-reveal delay-1>
            <div class="usecase-icon-wrap" style="color:var(--c2)"><i class="fa-solid fa-heart-pulse"></i></div>
            <div class="usecase-body">
              <h4>Women's Health</h4>
              <p>Navigate changes in your body with clarity so you can adapt, stay balanced, and feel your best.</p>
            </div>
          </div>
          <div class="usecase-card" data-reveal delay-2>
            <div class="usecase-icon-wrap" style="color:var(--c3)"><i class="fa-solid fa-vial-circle-check"></i></div>
            <div class="usecase-body">
              <h4>Hormone Optimization</h4>
              <p>Continuous tracking on TRT, HRT, GLP-1s and more — stay informed and in control.</p>
            </div>
          </div>
          <div class="usecase-card" data-reveal delay-3>
            <div class="usecase-icon-wrap" style="color:var(--amber)"><i class="fa-solid fa-magnifying-glass-chart"></i></div>
            <div class="usecase-body">
              <h4>Preventative Tracking</h4>
              <p>Stay ahead of potential health concerns by tracking trends instead of reacting to symptoms.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SECURITY -->
    <section class="security-section">
      <div class="container security-grid">
        <div data-reveal>
          <div class="section-eyebrow">Private &amp; Secure</div>
          <h2 class="section-title">The infrastructure that makes fast, accurate testing possible.</h2>
          <div class="security-items" style="margin-top:28px">
            <div class="security-item">
              <div class="icon"><i class="fa-solid fa-lock"></i></div>
              <div>
                <h4>Military-grade encryption</h4>
                <p>Advanced encryption protocols keep your health data protected and private at every point of the system.</p>
              </div>
            </div>
            <div class="security-item">
              <div class="icon" style="background:rgba(167,139,250,.1);color:var(--c2)"><i class="fa-solid fa-flask"></i></div>
              <div>
                <h4>In-house testing, no third parties</h4>
                <p>We handle every sample ourselves to guarantee privacy and consistent lab-grade results.</p>
              </div>
            </div>
            <div class="security-item">
              <div class="icon" style="background:rgba(52,211,153,.1);color:var(--c3)"><i class="fa-solid fa-shield-halved"></i></div>
              <div>
                <h4>Verified chain of custody</h4>
                <p>Our closed-loop system tracks and protects your sample from the moment it's collected.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="security-stats" data-reveal delay-2>
          <div class="stat-card shimmer">
            <span class="stat-pct pct-green" data-count="97">0%</span>
            <div class="stat-lbl">Enjoyed the collection and would do it again</div>
          </div>
          <div class="stat-card shimmer">
            <span class="stat-pct pct-cyan" data-count="91">0%</span>
            <div class="stat-lbl">Learned something new about their health</div>
          </div>
          <div class="stat-card shimmer">
            <span class="stat-pct pct-amber" data-count="85">0%</span>
            <div class="stat-lbl">Made changes to their diet, exercise &amp; lifestyle</div>
          </div>
          <div class="stat-card shimmer">
            <span class="stat-pct pct-purple" data-count="93">0%</span>
            <div class="stat-lbl">Have recommended Vyralabs to friends &amp; family</div>
          </div>
        </div>
      </div>
    </section>

    <section>
      <div class="container">
        <div class="section-head center" data-reveal>
          <div class="section-eyebrow">Thousands of 5-star reviews</div>
          <h2 class="section-title">This is what people say about Vyralabs</h2>
          <p class="section-sub">Real reviews from real customers who've seen health improvements in less than 90 days.</p>
        </div>
        
        <div class="reviews-grid" style="margin-top: 20px;">
          @forelse($reviews as $review)
            <div class="review-card" data-reveal @if($loop->index > 0) delay-{{ $loop->index }} @endif>
              
              <div class="review-stars">
                @for($i = 1; $i <= 5; $i++)
                  {{ $i <= $review->rating ? '★' : '☆' }}
                @endfor
              </div>
              
              <p>{{ $review->text }}</p>
              
              <div class="review-author">
                @if($review->author_image)
                  <div class="review-av" style="padding: 0;">
                    <img src="{{ asset('images/reviews/' . $review->author_image) }}" alt="{{ $review->author_name }}" loading="lazy" decoding="async" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                  </div>
                @else
                  @php
                    $gradients = [
                        'linear-gradient(135deg, var(--c1, #3b82f6), #1d4ed8)',
                        'linear-gradient(135deg, var(--c2, #a855f7), #ec4899)',
                        'linear-gradient(135deg, var(--c3, #06b6d4), #10b981)'
                    ];
                    $currentGradient = $gradients[$loop->index % count($gradients)];
                  @endphp
                  <div class="review-av" style="background: {{ $currentGradient }}; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                    {{ Str::upper(Str::substr($review->author_name, 0, 1)) }}
                  </div>
                @endif
                
                <div>
                  <div class="review-name">{{ $review->author_name }}</div>
                  
                  @if($review->is_verified)
                    <div class="review-tag">
                      <i class="fa-solid fa-circle-check"></i> Verified Buyer
                    </div>
                  @endif
                </div>
              </div>

            </div>
          @empty
            <div class="col-12 text-center" style="color: var(--text-muted); padding: 40px 0;">
              <p>No reviews available at the moment.</p>
            </div>
          @endforelse
        </div>
      </div>
    </section>

    <section class="premium-pricing-section" id="pricing">
      <div class="container">
        
        <div class="section-head center" data-reveal>
          <div class="section-eyebrow">PRICING PLANS</div>
          <h2 class="section-title">Invest in your <strong>Longevity</strong></h2>
          <p class="section-sub">Choose a plan tailored to your health optimization goals. Cancel anytime.</p>
        </div>

        <div class="pricing-grid">
          @if(session('error'))
            <div style="background: #ff4a4a; color: white; padding: 14px; text-align: center; border-radius: 8px; margin-bottom: 25px; font-weight: bold; width: 100%; max-width: 1200px; margin-left: auto; margin-right: auto;">
                {{ session('error') }}
            </div>
          @endif
          @isset($data)
            @foreach($data as $index => $plan)
              @php
                $isPopular = (isset($plan['id']) && $plan['id'] == 2) || strtolower($plan['name']) == 'premium';
                
                $features = is_string($plan['features']) ? json_decode($plan['features'], true) : $plan['features'];
                $features = $features ?? [];
              @endphp

              <div class="pricing-card {{ $isPopular ? 'popular-plan' : '' }}" data-reveal {{ $index > 0 ? 'delay-' . $index : '' }}>
                
                <div class="card-badge-wrapper">
                  @if($isPopular)
                    <span class="popular-badge">MOST POPULAR</span>
                  @endif
                </div>

                <div class="pricing-card-header">
                  <span class="plan-type text-gradient">
                    {{ $plan['name'] }}
                  </span>
                  <div class="plan-price-block">
                    <span class="currency">€</span>
                    <span class="price">{{ number_format($plan['price'], 0) }}</span>
                    <span class="period">/{{ $plan['billing_cycle'] ?? 'month' }}</span>
                  </div>
                  <p class="plan-desc">
                    @if(isset($plan['duration']) && $plan['duration'] > 0)
                      Includes {{ $plan['duration'] }} days trial setup for baseline optimization.
                    @else
                      Continuous health monitoring tailored for your longevity optimization goals.
                    @endif
                  </p>
                </div>
                
                <div class="plan-divider"></div>
                
                <ul class="plan-features">
                  @if(count($features) > 0)
                    @foreach($features as $feature)
                      @php
                        $isDisabled = str_contains($feature, '__disabled__');
                        $cleanFeature = str_replace('__disabled__', '', $feature);
                      @endphp
                      
                      <li class="{{ $isDisabled ? 'disabled' : '' }}">
                        @if($isDisabled)
                          <i class="fa-solid fa-circle-xmark"></i>
                        @else
                          <i class="fa-solid fa-circle-check"></i>
                        @endif
                        {!! $cleanFeature !!}
                      </li>
                    @endforeach
                  @else
                    <li><i class="fa-solid fa-circle-check"></i> Standard Biomarker Insights</li>
                    <li><i class="fa-solid fa-circle-check"></i> Home Kit Delivery Included</li>
                    <li><i class="fa-solid fa-circle-check"></i> PDF Reports & Data Export</li>
                  @endif
                </ul>

                <div class="pricing-cta-wrap">
                  <form action="{{ route('user.subscribe.checkout', $plan['id']) }}" method="POST">
                    @csrf
                    <button type="submit" class="pricing-btn {{ $isPopular ? 'primary-glow-btn' : 'secondary-btn' }}">
                      @if($isPopular)
                        Subscribe & Optimize
                      @else
                        Get Started {{ $plan['name'] }}
                      @endif
                    </button>
                  </form>
                </div>

              </div>
            @endforeach
          @else
            <div class="center" style="grid-column: 1/-1; padding: 40px; text-align: center; color: var(--c-muted);">
                <p>No active pricing plans found at the moment.</p>
            </div>
          @endisset

        </div>

        <div class="pricing-footnote" data-reveal>
          <p><i class="fa-solid fa-shield-halved" style="color: var(--c);"></i> All options are HSA/FSA eligible. No hidden activation contracts. Cancel or pause anytime in one click.</p>
        </div>

      </div>
    </section>

    <!-- FAQ -->
    <section class="faq-section">
      <div class="container">
        <div class="section-head center" data-reveal>
          <div class="section-eyebrow">FAQ</div>
          <h2 class="section-title">Questions? We've got answers.</h2>
        </div>
        
        <div class="faq-list">
          @forelse($faqs->where('is_active', true) as $faq)
            <div class="faq-item">
              <div class="faq-q">
                {{ $faq->question }} 
                <i class="fa-solid fa-plus"></i>
              </div>
              <div class="faq-a">
                <div class="faq-a-inner">
                  {!! nl2br(e($faq->answer)) !!}
                </div>
              </div>
            </div>
          @empty
            <div class="faq-item">
              <div class="faq-q">Are Vyralabs tests medical diagnostic tests? <i class="fa-solid fa-plus"></i></div>
              <div class="faq-a"><div class="faq-a-inner">No. Our tests are designed for wellness and longevity tracking. They do not provide medical diagnoses and should not replace consultation with a doctor.</div></div>
            </div>
            <div class="faq-item">
              <div class="faq-q">How is my personal and health data protected? <i class="fa-solid fa-plus"></i></div>
              <div class="faq-a"><div class="faq-a-inner">We are fully GDPR compliant. All health data is encrypted and shared only with accredited laboratories when necessary for analysis.</div></div>
            </div>
            <div class="faq-item">
              <div class="faq-q">How long does it take to get my results? <i class="fa-solid fa-plus"></i></div>
              <div class="faq-a"><div class="faq-a-inner">Usually 7 to 14 business days from when the laboratory receives your sample.</div></div>
            </div>
            <div class="faq-item">
              <div class="faq-q">Can I cancel my subscription or order? <i class="fa-solid fa-plus"></i></div>
              <div class="faq-a"><div class="faq-a-inner">Yes. You can cancel your subscription at any time from your account. Unopened kits can be returned within 14 days.</div></div>
            </div>
            <div class="faq-item">
              <div class="faq-q">What happens if my sample is insufficient or invalid? <i class="fa-solid fa-plus"></i></div>
              <div class="faq-a"><div class="faq-a-inner">We will notify you. In some cases, you may need to repeat the test (a new kit may be required at additional cost).</div></div>
            </div>
            <div class="faq-item">
              <div class="faq-q">Is the longevity score a medical assessment? <i class="fa-solid fa-plus"></i></div>
              <div class="faq-a"><div class="faq-a-inner">No. The Longevity Score is a wellness indicator based on your biomarkers. It is not a medical diagnosis.</div></div>
            </div>
            <div class="faq-item">
              <div class="faq-q">Do you ship to all European countries? <i class="fa-solid fa-plus"></i></div>
              <div class="faq-a"><div class="faq-a-inner">Yes, we currently ship to all EU countries.</div></div>
            </div>
            <div class="faq-item">
              <div class="faq-q">How do I activate my kit? <i class="fa-solid fa-plus"></i></div>
              <div class="faq-a"><div class="faq-a-inner">You can activate your kit easily through the mobile app or web portal by entering the kit code provided in the box.</div></div>
            </div>
          @endforelse
        </div>
      </div>
    </section>

    <!-- FINAL CTA -->
    <section class="cta-section">
      <div class="container">
        <h2 data-reveal>Start tracking what actually moves the needle.</h2>
        <p data-reveal delay-1>Join thousands using Vyralabs to turn guesswork into a real, data-backed plan for their health and performance.</p>
        <a href="#pricing" class="btn btn-primary btn-lg" data-reveal delay-2>Try now with 20% off <span style="opacity:.65;font-style:italic;font-weight:500">risk free</span></a>
      </div>
    </section>

    <!-- FOOTER -->
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
              <li><a href="#pricing">Start Testing</a></li>
              <li><a href="{{route('login')}}">Login</a></li>
              <li><a href="#">Careers</a></li>
            </ul>
          </div>
          <div class="footer-col">
            <h4>Explore</h4>
            <ul>
              <li><a href="#how">Biomarkers We Test</a></li>
              <li><a href="#">Customer Reviews</a></li>
              <li><a href="#about">About Us</a></li>
            </ul>
          </div>
          <div class="footer-col" id="partners">
            <h4>Partnerships</h4>
            <ul>
              <li><a href="#">Creator Partnerships</a></li>
              <li><a href="#">Affiliate Programs</a></li>
              <li><a href="#">Chat With Us Now</a></li>
            </ul>
          </div>
        </div>
        <div class="footer-bottom">
          <div>&copy; 2026 Vyralabs. All rights reserved.</div>
          <div class="footer-legal">
            <a href="{{ url('/terms-and-condition') }}">Terms &amp; Conditions</a>
            <a href="#">Refund Policy</a>
            <a href="{{ route('privacy.policy') }}">Privacy Policy</a>
            <a href="#">Consumer Health Data Privacy Policy</a>
            <a href="{{ url('/laboratory-services-consent') }}">Laboratory Services Consent</a>
          </div>
        </div>
        <div class="disclaimer">
          DISCLAIMER: Vyralabs does not recommend or refer you to any healthcare providers, and you are free to choose any healthcare provider and to continue to use Vyralabs' services. Vyralabs does not offer medical advice, a diagnosis, medical treatment, or any form of medical opinion through our services or otherwise. We recommend that you discuss those questions with your primary care physician or other licensed provider. All material, information, data, and content that Vyralabs provides is strictly for general information purposes. Vyralabs' membership pricing includes technology and service fees charged by Vyralabs, as well as access to prepaid laboratory and other services. Certain items and services require additional payment that are not included in standard membership pricing.
        </div>
      </div>
    </footer>

    <script src="{{ asset('frontend/user.js') }}"></script>
  </body>
</html>