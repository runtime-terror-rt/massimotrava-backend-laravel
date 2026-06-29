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

/* ── Mobile Dashboard Responsive ── */
@media (max-width: 768px) {
    .hd-wrap { padding: 16px; }
    .hd-hero { flex-direction: column; align-items: flex-start; padding: 24px; gap: 20px; border-radius: 20px; }
    .hd-score-container { width: 100%; justify-content: flex-start; }
    .hd-name { font-size: 24px; }
    .hd-metrics-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
    .hd-metric-val { font-size: 26px; }
    .hd-quick-actions { grid-template-columns: repeat(3, 1fr); gap: 10px; }
    .hd-main-card { padding: 20px; border-radius: 20px; }
    .hd-bio-row-v5 { grid-template-columns: auto 1fr; gap: 12px; }
    .hd-bio-data { grid-column: 2; text-align: left; padding-left: 60px; margin-top: -8px; }
}
@media (max-width: 480px) {
    .hd-metrics-grid { grid-template-columns: 1fr 1fr; }
    .hd-quick-actions { grid-template-columns: repeat(3, 1fr); }
    .hd-score-breakdown { display: none; }
    .hd-meta-pills { gap: 6px; }
    .hd-pill { font-size: 11px; padding: 4px 10px; }
}

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
    background: linear-gradient(90deg, rgba(245,158,11,0.03), transparent); border: 1px solid rgba(245,158,11,0.12);
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
.hd-st-high { color: #f59e0b; background: rgba(245,158,11,0.06); }
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
    {{-- CSS --}}
<style>
.hd-hero {
    background: linear-gradient(135deg, rgba(20, 24, 33, 0.8) 0%, rgba(11, 15, 22, 0.95) 100%);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 32px;
    padding: 36px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 32px;
    flex-wrap: wrap;
    margin-bottom: 36px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.05);
}

/* Ambient Ambient Background Glows */
.hd-hero::before {
    content: '';
    position: absolute; top: -100px; right: -100px;
    width: 350px; height: 350px; border-radius: 50%;
    background: radial-gradient(circle, rgba(34, 211, 238, 0.12) 0%, transparent 70%);
    filter: blur(50px);
    pointer-events: none;
}
.hd-hero::after {
    content: '';
    position: absolute; bottom: -80px; left: 120px;
    width: 250px; height: 250px; border-radius: 50%;
    background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, transparent 70%);
    filter: blur(40px);
    pointer-events: none;
}

.hd-hero-left { display: flex; align-items: center; gap: 24px; position: relative; z-index: 1; }

/* Premium Avatar Ring with Gradient */
.hd-avatar-zone { position: relative; width: 76px; height: 76px; flex-shrink: 0; }
.hd-avatar-zone svg { position: absolute; inset: 0; transform: rotate(-90deg); }
.hd-avatar-inner {
    position: absolute; inset: 6px;
    background: #141923;
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; 
    background: linear-gradient(135deg, #22d3ee, #0ea5e9);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.hd-avatar-status {
    position: absolute; bottom: 3px; right: 3px;
    width: 14px; height: 14px;
    background: #10b981;
    border: 2.5px solid #0b0f16;
    border-radius: 50%;
    box-shadow: 0 0 10px rgba(16, 185, 129, 0.6);
}

/* Hero Info */
.hd-greeting { 
    font-size: 11px; 
    color: #22d3ee; 
    text-transform: uppercase; 
    letter-spacing: 0.12em; 
    font-weight: 700; 
    margin-bottom: 8px;
    opacity: 0.9;
}
.hd-name { 
    font-family: 'Syne', sans-serif; 
    font-size: 28px; 
    font-weight: 800; 
    letter-spacing: -0.02em; 
    margin-bottom: 14px; 
    color: #ffffff;
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}
.hd-meta-pills { display: flex; gap: 10px; flex-wrap: wrap; }
.hd-pill {
    font-size: 12px; color: rgba(255, 255, 255, 0.6);
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    padding: 6px 14px; border-radius: 100px;
    display: flex; align-items: center; gap: 6px;
    backdrop-filter: blur(4px);
    transition: all 0.3s ease;
}
.hd-pill:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(255, 255, 255, 0.12);
    color: #fff;
}
.hd-pill b { color: #ffffff; font-weight: 600; }
.hd-pill i { color: #22d3ee; font-size: 11px; }

/* Divider */
.hd-hero-divider { width: 1px; height: 90px; background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.08), transparent); flex-shrink: 0; }

/* Score Container Upgrade */
.hd-score-container { display: flex; align-items: center; gap: 28px; position: relative; z-index: 1; }
.hd-score-ring { 
    position: relative; 
    width: 110px; 
    height: 110px; 
    flex-shrink: 0; 
}
.hd-score-ring svg { transform: rotate(-90deg); }
.hd-score-center {
    position: absolute; inset: 0;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
}

/* Text Gradient for the 84 Score */
.hd-score-num { 
    font-family: 'Syne', sans-serif; 
    font-size: 36px; 
    font-weight: 900; 
    line-height: 1;
    background: linear-gradient(135deg, #22d3ee 0%, #10b981 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    filter: drop-shadow(0 2px 8px rgba(34, 211, 238, 0.2));
}
.hd-score-total { font-size: 11px; color: rgba(255, 255, 255, 0.4); font-weight: 600; margin-top: 2px; }

/* Stats Breakdown Layout */
.hd-score-breakdown { display: flex; flex-direction: column; gap: 14px; }
.hd-sub-score { display: flex; flex-direction: column; gap: 4px; }
.hd-sub-label { font-size: 10px; color: rgba(255, 255, 255, 0.4); text-transform: uppercase; letter-spacing: 0.08em; font-weight: 700; }
.hd-sub-val { font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 4px; }
.hd-sub-bar { height: 4px; background: rgba(255, 255, 255, 0.05); border-radius: 100px; width: 140px; overflow: hidden; margin-top: 3px; }
.hd-sub-fill { height: 100%; border-radius: 100px; }

@media (max-width: 991px) {
    .hd-hero { padding: 28px; }
    .hd-hero-divider { display: none; }
    .hd-score-container { width: 100%; justify-content: flex-start; margin-top: 10px; }
}
@media (max-width: 576px) {
    .hd-name { font-size: 24px; }
    .hd-sub-bar { width: 100px; }
    .hd-avatar-zone { width: 64px; height: 64px; }
    .hd-avatar-zone svg { width: 64px; height: 64px; }
}
</style>

{{-- HTML --}}
<div class="hd-hero">
    <div class="hd-hero-left">
        {{-- Avatar with Gradient Ring --}}
        <div class="hd-avatar-zone">
            <svg width="76" height="76" viewBox="0 0 76 76">
                <defs>
                    <linearGradient id="avatarGrad" x1="0%" y1="100%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#0ea5e9" />
                        <stop offset="100%" stop-color="#22d3ee" />
                    </linearGradient>
                </defs>
                <circle cx="38" cy="38" r="34" fill="none" stroke="rgba(255,255,255,0.04)" stroke-width="3"/>
                <circle cx="38" cy="38" r="34" fill="none" stroke="url(#avatarGrad)" stroke-width="3"
                    stroke-dasharray="213" stroke-dashoffset="60" stroke-linecap="round"/>
            </svg>
            <div class="hd-avatar-inner">
                <i class="fa-solid fa-user-astronaut"></i>
            </div>
            <div class="hd-avatar-status"></div>
        </div>

        <div>
            <p class="hd-greeting">Welcome back to Vyralabs</p>
            <h2 class="hd-name">{{ auth()->user()->name }}</h2>
            <div class="hd-meta-pills">
                <span class="hd-pill"><i class="fa-solid fa-cake-candles"></i> Age: <b>{{ auth()->user()->age ?? 32 }}</b></span>
                <span class="hd-pill"><i class="fa-solid fa-ruler-vertical"></i> Height: <b>{{ auth()->user()->height ?? '178 cm' }}</b></span>
                <span class="hd-pill"><i class="fa-solid fa-weight-scale"></i> Weight: <b>{{ auth()->user()->weight ?? '74 kg' }}</b></span>
                <span class="hd-pill"><i class="fa-solid fa-calendar"></i> Joined <b>{{ auth()->user()->created_at ? auth()->user()->created_at->format('M Y') : 'Jun 2026' }}</b></span>
            </div>
        </div>
    </div>

    <div class="hd-hero-divider"></div>

    <div class="hd-score-container">
        {{-- High-End Gradient & Glowing Ring Score --}}
        <div class="hd-score-ring">
            <svg width="110" height="110" viewBox="0 0 110 110">
                <defs>
                    <linearGradient id="scoreRingGradient" x1="0%" y1="100%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#22d3ee" />   <!-- Cyan Accent -->
                        <stop offset="60%" stop-color="#10b981" />  <!-- Longevity Emerald Green -->
                        <stop offset="100%" stop-color="#059669" /> <!-- Deep Green -->
                    </linearGradient>
                    <filter id="ringGlow" x="-20%" y="-20%" width="140%" height="140%">
                        <feGaussianBlur stdDeviation="3" result="blur" />
                        <feComposite in="SourceGraphic" in2="blur" operator="over" />
                    </filter>
                </defs>
                <circle cx="55" cy="55" r="46" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="5.5"/>
                <circle cx="55" cy="55" r="46" fill="none" stroke="url(#scoreRingGradient)" stroke-width="5.5"
                    stroke-dasharray="289" stroke-dashoffset="46" stroke-linecap="round" filter="url(#ringGlow)"/>
            </svg>
            <div class="hd-score-center">
                <div class="hd-score-num">84</div>
                <div class="hd-score-total">/100</div>
            </div>
        </div>

        {{-- Stats Breakdown with Subtle Shading --}}
        <div class="hd-score-breakdown">
            <div class="hd-sub-score">
                <div class="hd-sub-label">Biological Age</div>
                <div class="hd-sub-val" style="color:#c084fc;"><i class="fa-solid fa-sparkles" style="font-size:10px;"></i> −2.4 Years Younger</div>
                <div class="hd-sub-bar"><div class="hd-sub-fill" style="width:78%; background: linear-gradient(to right, #a78bfa, #c084fc);"></div></div>
            </div>
            <div class="hd-sub-score">
                <div class="hd-sub-label">Sleep Index</div>
                <div class="hd-sub-val" style="color:#22d3ee;"><i class="fa-solid fa-moon" style="font-size:10px;"></i> 92% Optimal</div>
                <div class="hd-sub-bar"><div class="hd-sub-fill" style="width:92%; background: linear-gradient(to right, #06b6d4, #22d3ee);"></div></div>
            </div>
            <div class="hd-sub-score">
                <div class="hd-sub-label">Cardio Fitness</div>
                <div class="hd-sub-val" style="color:#10b981;"><i class="fa-solid fa-heart-pulse" style="font-size:10px;"></i> Excellent</div>
                <div class="hd-sub-bar"><div class="hd-sub-fill" style="width:88%; background: linear-gradient(to right, #059669, #10b981);"></div></div>
            </div>
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
        {{-- <a href="#" class="hd-action-card">
            <i class="fa-solid fa-user-doctor"></i>
            <span>Consult Expert</span>
        </a> --}}
    </div>

    {{-- ══ 3. 4x4 OVERVIEW METRICS GRID ══ --}}
    <div class="hd-metrics-grid">
        <div class="hd-metric-box c-indigo">
            <div class="hd-metric-head">
                <span class="hd-metric-lbl">Longevity Index</span>
                <div class="hd-metric-icon">🧬</div>
            </div>
            <div class="hd-metric-val" style="color:var(--accent);font-size:42px;text-shadow:0 0 30px rgba(99,102,241,0.4);">84</div>
            <div class="hd-metric-trend" style="color:#10b981;"><i class="fa-solid fa-arrow-trend-up"></i> +5 points vs last panel — you're trending younger</div>
        </div>

        <div class="hd-metric-box c-green">
            <div class="hd-metric-head">
                <span class="hd-metric-lbl">Biomarkers Validated</span>
                <div class="hd-metric-icon">✅</div>
            </div>
            <div class="hd-metric-val" style="color:#10b981;">3 <span style="font-size:14px;color:var(--text-muted);font-weight:400;">/ 4</span></div>
            <div class="hd-metric-trend"><i class="fa-solid fa-circle" style="font-size:6px;color:#f59e0b;vertical-align:middle;"></i> 1 marker needs a little focus</div>
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
                    <i class="fa-solid fa-circle-info" style="color:#f59e0b;"></i>
                    <p><strong>Action Recommended:</strong> Your LDL Cholesterol has risen 8% above your personal baseline. A small dietary adjustment now can make a meaningful difference.</p>
                </div>
                <div class="hd-insight-stripe green-mode">
                    <i class="fa-solid fa-shield-heart" style="color:#10b981;"></i>
                    <p><strong>Looking Great:</strong> Fasting Glucose and Testosterone are showing peak stability over the past 90 days — keep it up!</p>
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
                            <span class="guide" style="color:rgba(245,158,11,0.8);">Small win: reduce saturated fats and add more fiber to your daily meals — your heart will thank you.</span>
                        </div>
                        <div class="hd-bio-data">
                            <div class="num" style="color:#f59e0b;">145 <unit>mg/dL</unit></div>
                            <span class="hd-bio-status hd-st-high">● Needs Attention</span>
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
                            <span class="guide" style="color:rgba(245,158,11,0.8);">Easy boost: 15 mins of morning sunlight or a simple Vitamin D supplement can lift your levels quickly.</span>
                        </div>
                        <div class="hd-bio-data">
                            <div class="num" style="color:#f59e0b;">22 <unit>ng/mL</unit></div>
                            <span class="hd-bio-status hd-st-low">● Needs Attention</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('user.reports.index') }}" class="hd-download-trigger">
                    <i class="fa-solid fa-file-lines"></i> Download Your Longevity Report
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
                    {{-- <div class="hd-group-item">
                        <div class="hd-group-meta">
                            <div class="title">Anonymized Lab Sync</div>
                            <div class="sub">Authorize end-to-end encrypted medical sync protocols.</div>
                        </div>
                        <label class="hd-ios-switch">
                            <input type="checkbox" checked>
                            <span class="hd-ios-slider"></span>
                        </label>
                    </div> --}}

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
                            <div class="title">SMS Notification</div>
                            <div class="sub">Receive sms when complete your report.</div>
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
            {{-- <div class="hd-main-card">
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
            </div> --}}
        </div>

    </div>
</div>

@endsection