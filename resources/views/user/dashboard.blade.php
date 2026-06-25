@extends('layouts.admin')
@section('title', __('messages.sb_dashboard') ?? 'Health Dashboard')
@section('page_title_key', 'sb_dashboard')
@section('content')

<style>
/* ── Global Setup & Premium Variables ── */
.hd-wrap * { box-sizing: border-box; }
.hd-wrap {
    padding: clamp(24px, 4vw, 48px);
    font-family: 'Inter', sans-serif;
    color: var(--text-main);
    max-width: 1440px;
    margin: 0 auto;
}

/* ── Hero Profile Section (Apple Premium Minimal) ── */
.hd-hero {
    background: linear-gradient(135deg, rgba(99,102,241,0.05) 0%, rgba(6,182,212,0.03) 100%);
    border: 1px solid rgba(255, 255, 255, 0.04);
    border-radius: 28px;
    padding: 36px;
    margin-bottom: 36px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 28px;
}
.hd-hero-left { display: flex; align-items: center; gap: 24px; }
.hd-avatar-zone {
    position: relative; width: 76px; height: 76px; flex-shrink: 0;
    border-radius: 50%; background: var(--surface-2);
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; color: var(--accent);
}
.hd-avatar-status {
    position: absolute; bottom: 3px; right: 3px; width: 14px; height: 14px;
    background: #10b981; border: 3px solid var(--surface); border-radius: 50%;
}
.hd-greeting { font-size: 13px; color: var(--text-muted); margin: 0 0 4px; font-weight: 500; letter-spacing: 0.03em; text-transform: uppercase; }
.hd-name { font-family: 'Syne', sans-serif; font-size: 32px; font-weight: 700; margin: 0 0 10px; letter-spacing: -0.03em; }
.hd-meta-pills { display: flex; gap: 12px; flex-wrap: wrap; }
.hd-pill {
    font-size: 12px; color: var(--text-muted); background: rgba(255,255,255,0.03);
    border: 1px solid var(--border); padding: 5px 14px; border-radius: 20px;
}
.hd-pill b { color: var(--text-main); font-weight: 600; }

/* Expanded Score Section */
.hd-score-container {
    display: flex; align-items: center; gap: 24px;
    background: rgba(16,185,129,0.03); border: 1px solid rgba(16,185,129,0.12);
    padding: 20px 28px; border-radius: 24px;
}
.hd-score-main { text-align: center; }
.hd-score-num { font-family: 'Syne', sans-serif; font-size: 38px; font-weight: 800; color: #10b981; line-height: 1; }
.hd-score-total { font-size: 12px; color: var(--text-muted); }
.hd-score-breakdown { display: flex; flex-direction: column; gap: 4px; border-left: 1px solid rgba(255,255,255,0.08); padding-left: 20px; }
.hd-sub-score { font-size: 11.5px; color: var(--text-muted); }
.hd-sub-score b { color: var(--text-main); font-weight: 600; }

/* ── Quick Action Quick Links (Highly User-Friendly) ── */
.hd-quick-actions {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 36px;
}
.hd-action-card {
    background: var(--surface-2); border: 1px solid var(--border); border-radius: 16px;
    padding: 16px; text-align: center; text-decoration: none; color: var(--text-main);
    transition: all 0.2s ease; display: flex; flex-direction: column; align-items: center; gap: 8px;
}
.hd-action-card:hover { border-color: var(--accent); transform: translateY(-2px); background: rgba(99,102,241,0.04); }
.hd-action-card i { font-size: 18px; color: var(--accent); }
.hd-action-card span { font-size: 12.5px; font-weight: 600; }

/* ── 4x4 Grid Overview ── */
.hd-metrics-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 36px;
}
.hd-metric-box {
    background: var(--surface); border: 1px solid rgba(255,255,255,0.02); border-radius: 24px;
    padding: 28px; box-shadow: 0 10px 30px -15px rgba(0,0,0,0.2);
    transition: transform 0.2s, border-color 0.2s;
}
.hd-metric-box:hover { transform: translateY(-3px); border-color: rgba(99,102,241,0.2); }
.hd-metric-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
.hd-metric-lbl { font-size: 12px; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.04em; }
.hd-metric-icon { font-size: 16px; width: 36px; height: 36px; background: var(--surface-2); border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border); }
.hd-metric-box.c-indigo .hd-metric-icon { color: var(--accent); }
.hd-metric-box.c-green .hd-metric-icon { color: #10b981; }
.hd-metric-box.c-cyan .hd-metric-icon { color: #38bdf8; }
.hd-metric-box.c-amber .hd-metric-icon { color: #f59e0b; }
.hd-metric-val { font-family: 'Syne', sans-serif; font-size: 32px; font-weight: 800; color: var(--text-main); line-height: 1; margin-bottom: 8px; }
.hd-metric-trend { font-size: 11.5px; display: flex; align-items: center; gap: 6px; color: var(--text-muted); }

/* ── 2-Column Main Layout Grid ── */
.hd-layout-grid {
    display: grid; grid-template-columns: 1.4fr 1fr; gap: 36px;
}
@media (max-width: 1024px) { .hd-layout-grid { grid-template-columns: 1fr; } }

/* ── Component Master Container ── */
.hd-main-card {
    background: var(--surface); border: 1px solid rgba(255, 255, 255, 0.02); border-radius: 28px;
    padding: 36px; box-shadow: 0 20px 50px -25px rgba(0,0,0,0.25); margin-bottom: 36px;
}
.hd-card-header-v5 { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
.hd-card-title-v5 {
    font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 700; margin: 0;
    display: flex; align-items: center; gap: 12px; letter-spacing: -0.01em;
}
.hd-card-title-v5 i { color: var(--accent); font-size: 15px; }
.hd-card-tag { font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 20px; background: rgba(99,102,241,0.08); color: var(--accent); border: 1px solid rgba(99,102,241,0.15); }

/* Clean Minimal Insights Header */
.hd-insight-stripe {
    display: flex; gap: 14px; align-items: flex-start; padding: 16px 20px; border-radius: 16px; margin-bottom: 14px; font-size: 13px;
    background: linear-gradient(90deg, rgba(239,68,68,0.03), transparent); border: 1px solid rgba(239,68,68,0.1);
}
.hd-insight-stripe.green-mode {
    background: linear-gradient(90deg, rgba(16,185,129,0.03), transparent); border-color: rgba(16,185,129,0.1);
}
.hd-insight-stripe i { font-size: 14px; margin-top: 2px; }
.hd-insight-stripe p { margin: 0; line-height: 1.5; color: var(--text-muted); }
.hd-insight-stripe strong { color: var(--text-main); }

/* ── Biomarkers Flat Clean Rows ── */
.hd-bio-rows { display: flex; flex-direction: column; }
.hd-bio-row-v5 {
    display: grid; grid-template-columns: auto 1fr auto; align-items: center; gap: 20px;
    padding: 22px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.04);
}
.hd-bio-row-v5:last-child { border-bottom: none; padding-bottom: 0; }
.hd-bio-row-v5:first-child { padding-top: 0; }

.hd-avatar-box {
    width: 48px; height: 48px; border-radius: 14px; background: var(--surface-2);
    display: flex; align-items: center; justify-content: center; font-size: 18px; border: 1px solid var(--border);
}
.hd-bio-details .cat { font-size: 10px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
.hd-bio-details .name { font-size: 14.5px; font-weight: 600; color: var(--text-main); margin-bottom: 3px; }
.hd-bio-details .guide { font-size: 11.5px; color: var(--text-muted); display: block; }

.hd-bio-data { text-align: right; }
.hd-bio-data .num { font-family: 'Syne', sans-serif; font-size: 17px; font-weight: 700; color: var(--text-main); }
.hd-bio-data .num unit { font-size: 11.5px; color: var(--text-muted); font-weight: 400; margin-left: 2px; }
.hd-bio-status { font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; margin-top: 4px; padding: 2px 8px; border-radius: 6px; }
.hd-st-opt { color: #10b981; background: rgba(16,185,129,0.06); }
.hd-st-high { color: #ef4444; background: rgba(239,68,68,0.06); }
.hd-st-low { color: #f59e0b; background: rgba(245,158,11,0.06); }

/* Premium Action Button */
.hd-download-trigger {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    background: linear-gradient(135deg, var(--accent), #818cf8); color: #fff;
    font-size: 13.5px; font-weight: 700; padding: 16px 32px; border-radius: 16px;
    text-decoration: none; margin-top: 28px; box-shadow: 0 12px 30px -10px rgba(99,102,241,0.3);
    transition: all 0.2s ease;
}
.hd-download-trigger:hover { transform: translateY(-1px); opacity: 0.95; box-shadow: 0 15px 35px -10px rgba(99,102,241,0.4); color: #fff; }

/* ── Timeline Construction ── */
.hd-clean-timeline { position: relative; padding-left: 24px; }
.hd-clean-timeline::before {
    content: ''; position: absolute; left: 7px; top: 8px; bottom: 8px; width: 2px; background: var(--border);
}
.hd-timeline-node { position: relative; margin-bottom: 24px; }
.hd-timeline-node:last-child { margin-bottom: 0; }
.hd-timeline-marker { position: absolute; left: -21px; top: 5px; width: 10px; height: 10px; border-radius: 50%; border: 2px solid var(--surface); }
.hd-node-date { font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-bottom: 4px; }
.hd-node-title { font-size: 14px; color: var(--text-main); font-weight: 500; line-height: 1.4; }
.hd-node-badge { display: inline-block; font-size: 10.5px; font-weight: 600; padding: 2px 8px; border-radius: 6px; margin-top: 6px; background: var(--surface-2); border: 1px solid var(--border); color: var(--text-muted); }

/* ── Right Column Group Items ── */
.hd-group-item {
    padding: 22px 0; border-bottom: 1px solid rgba(255,255,255,0.04); display: flex; justify-content: space-between; align-items: center; gap: 16px;
}
.hd-group-item:first-child { padding-top: 0; }
.hd-group-item:last-child { border-bottom: none; padding-bottom: 0; }
.hd-group-meta .title { font-size: 14px; font-weight: 600; color: var(--text-main); margin-bottom: 3px; }
.hd-group-meta .sub { font-size: 11.5px; color: var(--text-muted); line-height: 1.4; }

/* Premium Switch Slider */
.hd-ios-switch { position: relative; display: inline-block; width: 38px; height: 22px; flex-shrink: 0; }
.hd-ios-switch input { opacity: 0; width: 0; height: 0; }
.hd-ios-slider {
    position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
    background-color: var(--surface-2); border: 1px solid var(--border); transition: .2s ease; border-radius: 30px;
}
.hd-ios-slider:before {
    position: absolute; content: ""; height: 14px; width: 14px; left: 3px; bottom: 3px;
    background-color: var(--text-muted); transition: .2s ease; border-radius: 50%;
}
input:checked + .hd-ios-slider { background-color: var(--accent); border-color: var(--accent); }
input:checked + .hd-ios-slider:before { transform: translateX(16px); background-color: #fff; }

/* Schedule Container Design */
.hd-retest-stripe {
    background: linear-gradient(135deg, rgba(99,102,241,0.05), rgba(6,182,212,0.02));
    border: 1px solid rgba(99,102,241,0.12); border-radius: 20px; padding: 24px;
    display: flex; gap: 18px; align-items: center;
}
.hd-retest-badge-icon { width: 48px; height: 48px; border-radius: 14px; background: rgba(99,102,241,0.06); display: flex; align-items: center; justify-content: center; font-size: 20px; color: var(--accent); border: 1px solid rgba(99,102,241,0.15); }
.hd-retest-stripe .title { font-size: 15px; font-weight: 700; color: var(--text-main); margin-bottom: 4px; }
.hd-retest-stripe .sub { font-size: 12px; color: var(--text-muted); margin-bottom: 12px; line-height: 1.4; }
.hd-retest-action { display: inline-block; background: var(--accent); color: #fff; font-size: 12px; font-weight: 700; padding: 8px 16px; border-radius: 10px; text-decoration: none; transition: background 0.2s; }
.hd-retest-action:hover { opacity: 0.9; color: #fff; }

/* Accordion Component */
.hd-accordion-item { border-bottom: 1px solid rgba(255,255,255,0.04); padding: 16px 0; }
.hd-accordion-item:last-child { border-bottom: none; padding-bottom: 0; }
.hd-accordion-item:first-child { padding-top: 0; }
.hd-accordion-btn {
    width: 100%; background: none; border: none; text-align: left; font-size: 13.5px; font-weight: 600; color: var(--text-main);
    display: flex; justify-content: space-between; align-items: center; cursor: pointer; padding: 0; gap: 12px;
}
.hd-accordion-btn i { font-size: 11px; color: var(--text-muted); transition: transform 0.2s; }
.hd-accordion-body { font-size: 12.5px; color: var(--text-muted); margin-top: 10px; line-height: 1.6; }

</style>

<div class="-wrap">

    {{-- ══ 1. PROFILE HERO HERO BANNER ══ --}}
    <div class="hd-hero">
        <div class="hd-hero-left">
            <div class="hd-avatar-zone">
                <i class="fa-solid fa-user-astronaut"></i>
                <div class="hd-avatar-status"></div>
            </div>
            <div>
                <p class="hd-greeting">Welcome back to Vyralabs,</p>
                <h2 class="hd-name">{{ auth()->user()->name }}</h2>
                <div class="hd-meta-pills">
                    <span class="hd-pill">Age: <b>{{ auth()->user()->age }}</b></span>
                    <span class="hd-pill">Height: <b>{{ auth()->user()->height }}</b></span>
                    <span class="hd-pill">Weight: <b>{{ auth()->user()->weight }}</b></span>
                    <span class="hd-pill"><i class="fa-solid fa-calendar" style="color:var(--accent);margin-right:4px;"></i> Joined Sept 2025</span>
                </div>
            </div>
        </div>
        
        <div class="hd-score-container">
            <div class="hd-score-main">
                <div class="hd-score-num">84</div>
                <div class="hd-score-total">/100</div>
            </div>
            <div class="hd-score-breakdown">
                <span class="hd-sub-score">Biological Age: <b>-2.4 Years</b></span>
                <span class="hd-sub-score">Sleep Index: <b>92% Optimal</b></span>
                <span class="hd-sub-score">Cardio Fitness: <b>Excellent</b></span>
            </div>
        </div>
    </div>

    {{-- ══ 2. USER FRIENDLY QUICK ACTIONS SHORTCUTS ══ --}}
    <div class="hd-quick-actions">
        <a href="#" class="hd-action-card">
            <i class="fa-solid fa-plus-circle"></i>
            <span>Order New Kit</span>
        </a>
        <a href="#" class="hd-action-card">
            <i class="fa-solid fa-truck-ramp-box"></i>
            <span>Track Sample</span>
        </a>
        <a href="{{ route('user.reports.index') }}" class="hd-action-card">
            <i class="fa-solid fa-file-invoice-bleed"></i>
            <span>All Lab Panels</span>
        </a>
        <a href="#" class="hd-action-card">
            <i class="fa-solid fa-user-doctor"></i>
            <span>Consult Expert</span>
        </a>
    </div>

    {{-- ══ 3. 4x4 OVERVIEW METRICS GRID ══ --}}
    <div class="hd-metrics-grid">
        <div class="hd-metric-box c-indigo">
            <div class="hd-metric-head">
                <span class="hd-metric-lbl">Longevity Index</span>
                <div class="hd-metric-icon">🧬</div>
            </div>
            <div class="hd-metric-val" style="color:var(--accent);">84</div>
            <div class="hd-metric-trend" style="color:#10b981;"><i class="fa-solid fa-arrow-trend-up"></i> +5 points increase vs Jan</div>
        </div>

        <div class="hd-metric-box c-green">
            <div class="hd-metric-head">
                <span class="hd-metric-lbl">Biomarkers Validated</span>
                <div class="hd-metric-icon">✅</div>
            </div>
            <div class="hd-metric-val" style="color:#10b981;">3 <span style="font-size:14px;color:var(--text-muted);font-weight:400;">/ 4</span></div>
            <div class="hd-metric-trend"><i class="fa-solid fa-circle" style="font-size:6px;color:#ef4444;vertical-align:middle;"></i> 1 core marker needs target focus</div>
        </div>

        <div class="hd-metric-box c-cyan">
            <div class="hd-metric-head">
                <span class="hd-metric-lbl">Registered Kits</span>
                <div class="hd-metric-icon">🧪</div>
            </div>
            <div class="hd-metric-val" style="color:#38bdf8;">2</div>
            <div class="hd-metric-trend">1 active transit, 1 archive package</div>
        </div>

        <div class="hd-metric-box c-amber">
            <div class="hd-metric-head">
                <span class="hd-metric-lbl">Next Recalibration</span>
                <div class="hd-metric-icon">📅</div>
            </div>
            <div class="hd-metric-val" style="color:#f59e0b;">Jul</div>
            <div class="hd-metric-trend">Target optimal retest timeline</div>
        </div>
    </div>

    {{-- ══ 4. MAIN DUAL COLUMN CONTENT SYSTEM ══ --}}
    <div class="hd-layout-grid">
        
        {{-- LEFT COLUMN: ADVANCED BIOMARKERS & JOURNEY --}}
        <div>
            {{-- Biomarkers Evaluation Card --}}
            <div class="hd-main-card">
                <div class="hd-card-header-v5">
                    <h3 class="hd-card-title-v5"><i class="fa-solid fa-heart-pulse"></i> Biomarker Analysis</h3>
                    <span class="hd-card-tag">Latest Sample Panel</span>
                </div>

                {{-- Actionable Core Insights --}}
                <div class="hd-insight-stripe">
                    <i class="fa-solid fa-circle-exclamation" style="color:#ef4444;"></i>
                    <p><strong>Priority Action Required:</strong> Your LDL Cholesterol levels have risen 8% above baseline. Review your dietary architecture immediately.</p>
                </div>
                <div class="hd-insight-stripe green-mode">
                    <i class="fa-solid fa-shield-heart" style="color:#10b981;"></i>
                    <p><strong>Excellent Metrics:</strong> Fasting Glucose and Testosterone show peak performance stability over the past 90 days.</p>
                </div>

                {{-- Detailed Rows with Embedded Advice --}}
                <div class="hd-bio-rows">
                    {{-- Item 1 --}}
                    <div class="hd-bio-row-v5">
                        <div class="hd-avatar-box" style="color:#a855f7;">🧬</div>
                        <div class="hd-bio-details">
                            <div class="cat">Hormonal Profile</div>
                            <div class="name">Total Testosterone</div>
                            <span class="guide">Maintains peak muscle mass, cellular focus, and metabolic driving rate.</span>
                        </div>
                        <div class="hd-bio-data">
                            <div class="num">650 <unit>ng/dL</unit></div>
                            <span class="hd-bio-status hd-st-opt">● Optimal Baseline</span>
                        </div>
                    </div>

                    {{-- Item 2 --}}
                    <div class="hd-bio-row-v5">
                        <div class="hd-avatar-box" style="color:#ef4444;">❤️</div>
                        <div class="hd-bio-details">
                            <div class="cat">Cardiovascular Matrix</div>
                            <div class="name">LDL Cholesterol</div>
                            <span class="guide" style="color:rgba(239,68,68,0.7);">Dietary Focus: Minimize saturated lipid profile vectors and increase soluble fiber intake.</span>
                        </div>
                        <div class="hd-bio-data">
                            <div class="num" style="color:#ef4444;">145 <unit>mg/dL</unit></div>
                            <span class="hd-bio-status hd-st-high">● Elevated Risk</span>
                        </div>
                    </div>

                    {{-- Item 3 --}}
                    <div class="hd-bio-row-v5">
                        <div class="hd-avatar-box" style="color:#3b82f6;">💧</div>
                        <div class="hd-bio-details">
                            <div class="cat">Metabolic Efficiency</div>
                            <div class="name">Fasting Glucose</div>
                            <span class="guide">Peak insulin response metrics. Carbohydrate processing thresholds are healthy.</span>
                        </div>
                        <div class="hd-bio-data">
                            <div class="num">88 <unit>mg/dL</unit></div>
                            <span class="hd-bio-status hd-st-opt">● Optimal Baseline</span>
                        </div>
                    </div>

                    {{-- Item 4 --}}
                    <div class="hd-bio-row-v5">
                        <div class="hd-avatar-box" style="color:#f59e0b;">☀️</div>
                        <div class="hd-bio-details">
                            <div class="cat">Micronutrient Profile</div>
                            <div class="name">Vitamin D (25-Hydroxy)</div>
                            <span class="guide" style="color:rgba(245,158,11,0.7);">Action Point: Recommended 15 mins daily morning sun or safe nutritional integration.</span>
                        </div>
                        <div class="hd-bio-data">
                            <div class="num" style="color:#f59e0b;">22 <unit>ng/mL</unit></div>
                            <span class="hd-bio-status hd-st-low">● Deficient Alert</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('user.reports.index') }}" class="hd-download-trigger">
                    <i class="fa-solid fa-file-pdf"></i> Download Complete Medical PDF Report
                </a>
            </div>

            {{-- Clinical Timeline Journey --}}
            <div class="hd-main-card">
                <div class="hd-card-header-v5">
                    <h3 class="hd-card-title-v5"><i class="fa-solid fa-route"></i> Health Journey Timeline</h3>
                </div>
                <div class="hd-clean-timeline">
                    <div class="hd-timeline-node">
                        <div class="hd-timeline-marker" style="background:#10b981;"></div>
                        <div class="hd-node-date">March 25, 2026</div>
                        <div class="hd-node-title">Comprehensive biomarker validation successfully generated by laboratory experts.</div>
                        <span class="hd-node-badge">Batch ID: #N/H-8291-952G</span>
                    </div>
                    <div class="hd-timeline-node">
                        <div class="hd-timeline-marker" style="background:var(--accent);"></div>
                        <div class="hd-node-date">January 15, 2026</div>
                        <div class="hd-node-title">Initial foundational health onboarding evaluation and panel tracking complete.</div>
                        <span class="hd-node-badge">Batch ID: #N/H-9120-450L</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: MANAGEMENT & REPREFERENCES CONTROL PANEL --}}
        <div>
            {{-- Logistics Fulfillment Box --}}
            <div class="hd-main-card">
                <div class="hd-card-header-v5">
                    <h3 class="hd-card-title-v5"><i class="fa-solid fa-box"></i> Kit Fulfillment Status</h3>
                </div>
                <div>
                    <div class="hd-group-item">
                        <div class="hd-group-meta">
                            <div class="title">Kit N/H-8291-952G</div>
                            <div class="sub">Inbound courier transit via partner network. Registered Apr 24, 2026.</div>
                        </div>
                        <span style="font-size:11px; font-weight:700; color:#38bdf8; background:rgba(56,189,248,0.08); padding:4px 10px; border-radius:8px;">In-Transit</span>
                    </div>
                    <div class="hd-group-item" style="opacity:0.65;">
                        <div class="hd-group-meta">
                            <div class="title">Kit N/H-9120-450L</div>
                            <div class="sub">Evaluation completed. Secure analytical data successfully stored on Jan 15, 2026.</div>
                        </div>
                        <span style="font-size:11px; font-weight:700; color:#10b981; background:rgba(16,185,129,0.08); padding:4px 10px; border-radius:8px;">Archived</span>
                    </div>
                </div>
            </div>

            {{-- Integrated Control Preferences Panel --}}
            <div class="hd-main-card">
                <div class="hd-card-header-v5">
                    <h3 class="hd-card-title-v5"><i class="fa-solid fa-sliders"></i> Control Center & Settings</h3>
                </div>
                <div>
                    <div class="hd-group-item">
                        <div class="hd-group-meta">
                            <div class="title">Anonymized Lab Sync</div>
                            <div class="sub">Authorize end-to-end encrypted medical sync protocols.</div>
                        </div>
                        <label class="hd-ios-switch">
                            <input type="checkbox" checked>
                            <span class="hd-ios-slider"></span>
                        </label>
                    </div>

                    <div class="hd-group-item">
                        <div class="hd-group-meta">
                            <div class="title">Email Health Reports</div>
                            <div class="sub">Receive complete documentation immediately when verified.</div>
                        </div>
                        <label class="hd-ios-switch">
                            <input type="checkbox" checked>
                            <span class="hd-ios-slider"></span>
                        </label>
                    </div>

                    <div class="hd-group-item">
                        <div class="hd-group-meta">
                            <div class="title">Biomarker Smart Reminders</div>
                            <div class="sub">Notify your linked communications one calendar week before metrics expire.</div>
                        </div>
                        <label class="hd-ios-switch">
                            <input type="checkbox" checked>
                            <span class="hd-ios-slider"></span>
                        </label>
                    </div>

                    <div class="hd-group-item">
                        <div class="hd-group-meta">
                            <div class="title">AI Insights Stream</div>
                            <div class="sub">Allow algorithmic updates for nutrition, lifestyle, and exercise routines.</div>
                        </div>
                        <label class="hd-ios-switch">
                            <input type="checkbox">
                            <span class="hd-ios-slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Scheduling Anchor --}}
            <div class="hd-main-card" style="padding:24px;">
                <div class="hd-retest-stripe">
                    <div class="hd-retest-badge-icon"><i class="fa-solid fa-calendar-plus"></i></div>
                    <div>
                        <div class="title">Schedule Next Retest</div>
                        <div class="sub">Maintain analytical consistency and monitor progression profiles every 90 days.</div>
                        <a href="#" class="hd-retest-action">Initialize Scheduling</a>
                    </div>
                </div>
            </div>

            {{-- Knowledge Accordions Base --}}
            <div class="hd-main-card">
                <div class="hd-card-header-v5">
                    <h3 class="hd-card-title-v5"><i class="fa-solid fa-circle-question"></i> Knowledge & FAQ</h3>
                </div>
                <div style="display:flex; flex-direction:column;">
                    <div class="hd-accordion-item">
                        <button class="hd-accordion-btn" onclick="let b = this.nextElementSibling; b.style.display = b.style.display === 'block' ? 'none' : 'block';">
                            <span>How do I activate my physical kit?</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>
                        <div class="hd-accordion-body" style="display:none;">
                            Navigate to the Quick Action card above or dedicated panel, and input the 8-character unique identity key found inside the box.
                        </div>
                    </div>

                    <div class="hd-accordion-item">
                        <button class="hd-accordion-btn" onclick="let b = this.nextElementSibling; b.style.display = b.style.display === 'block' ? 'none' : 'block';">
                            <span>When will my finalized panel show?</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>
                        <div class="hd-accordion-body" style="display:none;">
                            Laboratory analysis requires between 2 to 5 standard processing business days from reception of sample vectors.
                        </div>
                    </div>

                    <div class="hd-accordion-item">
                        <button class="hd-accordion-btn" onclick="let b = this.nextElementSibling; b.style.display = b.style.display === 'block' ? 'none' : 'block';">
                            <span>Is my personal data completely secure?</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>
                        <div class="hd-accordion-body" style="display:none;">
                            Absolutely. BioVue stores all physiological statistics behind banking-grade algorithmic tokenization systems to maintain privacy frameworks.
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection