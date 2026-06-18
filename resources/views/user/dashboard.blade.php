{{-- @extends('layouts.admin')
@section('title', __('messages.sb_dashboard') ?? 'Advanced User Portal')
@section('page_title_key', 'sb_dashboard')
@section('content')

<div class="dashboard-wrapper" style="padding: clamp(15px, 3vw, 30px); color: var(--text-main); font-family: 'Inter', sans-serif;">
    
    <div class="welcome-section" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(16, 185, 129, 0.05) 100%); border: 1px solid var(--border); padding: 25px; border-radius: 20px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
            <div style="position: relative; width: 75px; height: 75px; border-radius: 50%; padding: 3px; background: linear-gradient(to right, var(--accent), #10b981);">
                <div style="width: 100%; height: 100%; border-radius: 50%; background: var(--surface); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <i class="fa-solid fa-user-astronaut" style="font-size: 32px; color: var(--text-muted);"></i>
                </div>
                <div style="position: absolute; bottom: 2px; right: 2px; width: 16px; height: 16px; background: #10b981; border: 3px solid var(--surface); border-radius: 50%;"></div>
            </div>
            <div>
                <h2 style="font-family: 'Syne', sans-serif; font-weight: 700; margin: 0 0 5px 0; font-size: 24px; color: var(--text-main);">
                    {{ auth()->user()->name }} <span style="font-size: 14px; font-weight: 500; color: var(--text-muted); font-family: 'Inter';">(Member since {{ auth()->user()->created_at }})</span>
                </h2>
                <div style="display: flex; gap: 15px; flex-wrap: wrap; font-size: 12px; color: var(--text-muted);">
                    <span><i class="fa-solid fa-dna" style="color: var(--accent);"></i> Age: <b>{{ auth()->user()->age }}</b></span>
                    <span><i class="fa-solid fa-arrows-up-down" style="color: #38bdf8;"></i> Height: <b>{{ auth()->user()->height }}</b></span>
                    <span><i class="fa-solid fa-weight-scale" style="color: #fbbf24;"></i> Weight: <b>{{ auth()->user()->weight }}</b></span>
                </div>
            </div>
        </div>
        
        <div style="display: flex; align-items: center; gap: 15px; background: var(--surface-2); padding: 12px 20px; border-radius: 14px; border: 1px solid var(--border);">
            <div style="position: relative; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                <div style="position: absolute; inset: 0; border-radius: 50%; border: 4px solid var(--border); border-top-color: var(--accent); border-right-color: #10b981; transform: rotate(45deg);"></div>
                <span style="font-family: 'Syne'; font-weight: 700; font-size: 15px;">84</span>
            </div>
            <div>
                <span style="font-size: 11px; color: var(--text-muted); display: block; text-transform: uppercase; font-weight: 600;">Longevity Score</span>
                <strong style="color: #10b981; font-size: 14px;">Optimal Condition</strong>
            </div>
        </div>
    </div>

    <div class="main-dashboard-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 25px;">
        
        <div style="display: flex; flex-direction: column; gap: 25px;">
            
            <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 22px;">
                <h3 style="font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; margin-top: 0; margin-bottom: 15px; display: flex; align-items: center; justify-content: space-between;">
                    <span>Connected KITS</span>
                    <span style="font-size: 11px; background: rgba(99, 102, 241, 0.15); color: var(--accent); padding: 2px 8px; border-radius: 20px;">Fleet Manager</span>
                </h3>

                <div style="background: var(--surface-2); border: 1px solid var(--border); padding: 12px 15px; border-radius: 12px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong style="font-size: 13px; display: block; font-family: monospace; color: var(--text-main);">N/H-8291-952G</strong>
                        <small style="color: var(--text-muted); font-size: 11px;">Registered: Apr 24, 2026</small>
                    </div>
                    <span style="font-size: 10px; font-weight: 700; color: #38bdf8; background: rgba(56, 189, 248, 0.1); padding: 3px 8px; border-radius: 6px; text-transform: uppercase;">Active</span>
                </div>

                <div style="background: var(--surface-2); border: 1px solid var(--border); padding: 12px 15px; border-radius: 12px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong style="font-size: 13px; display: block; font-family: monospace; color: var(--text-main);">N/H-9120-450L</strong>
                        <small style="color: var(--text-muted); font-size: 11px;">Completed: Jan 15, 2026</small>
                    </div>
                    <span style="font-size: 10px; font-weight: 700; color: #10b981; background: rgba(16, 185, 129, 0.1); padding: 3px 8px; border-radius: 6px; text-transform: uppercase;">Used</span>
                </div>

                <div style="background: rgba(16, 185, 129, 0.04); border: 1px solid rgba(16, 185, 129, 0.2); padding: 15px; border-radius: 12px; text-align: center;">
                    <i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 24px; margin-bottom: 8px;"></i>
                    <p style="margin: 0; font-size: 12px; color: var(--text-muted); line-height: 1.4;">
                        This active kit has been verified and processed by our certified partner laboratory.
                    </p>
                    <button style="margin-top: 12px; background: var(--surface-2); border: 1px solid var(--border); color: var(--text-main); font-size: 12px; width: 100%; padding: 8px; border-radius: 6px; font-weight: 600; cursor: pointer;">View Full Kit Details</button>
                </div>
            </div>

            <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 22px;">
                <h3 style="font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; margin-top: 0; margin-bottom: 18px;">Privacy & Security</h3>
                
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-size: 13px; font-weight: 600; display: block;">Data Sharing Control</span>
                            <small style="color: var(--text-muted); font-size: 11px;">Control how your health data is shared.</small>
                        </div>
                        <i class="fa-solid fa-toggle-on" style="color: var(--accent); font-size: 24px; cursor: pointer;"></i>
                    </div>
                </div>
            </div>

        </div>

        <div style="display: flex; flex-direction: column; gap: 25px;">
            
            <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 22px;">
                <h3 style="font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; margin-top: 0; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center;">
                    <span>Biomarker Focus</span>
                    <i class="fa-solid fa-chart-line" style="color: var(--accent);"></i>
                </h3>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="background: var(--surface-2); padding: 12px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-size: 12px; color: var(--text-muted); display: block;">Hormones / Testosterone</span>
                            <strong style="font-size: 14px;">650 ng/dL</strong>
                        </div>
                        <span style="font-size: 10px; color: #10b981; font-weight: bold; background: rgba(16, 185, 129, 0.1); padding: 2px 6px; border-radius: 4px;">Optimal</span>
                    </div>

                    <div style="background: var(--surface-2); padding: 12px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-size: 12px; color: var(--text-muted); display: block;">Cardiovascular / LDL Cholesterol</span>
                            <strong style="font-size: 14px;">145 mg/dL</strong>
                        </div>
                        <span style="font-size: 10px; color: #ef4444; font-weight: bold; background: rgba(239, 68, 68, 0.1); padding: 2px 6px; border-radius: 4px;">High Risk</span>
                    </div>

                    <div style="background: var(--surface-2); padding: 12px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-size: 12px; color: var(--text-muted); display: block;">Metabolic / Fasting Glucose</span>
                            <strong style="font-size: 14px;">88 mg/dL</strong>
                        </div>
                        <span style="font-size: 10px; color: #10b981; font-weight: bold; background: rgba(16, 185, 129, 0.1); padding: 2px 6px; border-radius: 4px;">Optimal</span>
                    </div>

                    <div style="background: var(--surface-2); padding: 12px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-size: 12px; color: var(--text-muted); display: block;">Nutrients / Vitamin D</span>
                            <strong style="font-size: 14px;">22 ng/mL</strong>
                        </div>
                        <span style="font-size: 10px; color: #fbbf24; font-weight: bold; background: rgba(251, 191, 36, 0.1); padding: 2px 6px; border-radius: 4px;">Low</span>
                    </div>
                </div>

                <a href="{{ route('user.reports.index') }}" style="display: block; text-align: center; background: var(--text-main); color: var(--bg-main); font-size: 13px; font-weight: 700; padding: 12px; border-radius: 10px; margin-top: 15px; text-decoration: none;">
                    Download Comprehensive PDF Report
                </a>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 25px;">
            
            <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 22px;">
                <h3 style="font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; margin-top: 0; margin-bottom: 15px;">Notification Alerts</h3>
                
                <div style="display: flex; flex-direction: column; gap: 12px; font-size: 12.5px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--text-main); font-weight: 500;">Email Health Reports</span>
                        <i class="fa-solid fa-square-check" style="color: #10b981; font-size: 18px;"></i>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: 10px;">
                        <span style="color: var(--text-main); font-weight: 500;">Biomarker Test Reminders</span>
                        <i class="fa-solid fa-square-check" style="color: #10b981; font-size: 18px;"></i>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: 10px;">
                        <span style="color: var(--text-main); font-weight: 500;">AI Smart Insights</span>
                        <i class="fa-solid fa-square-check" style="color: #10b981; font-size: 18px;"></i>
                    </div>
                </div>
            </div>

            <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 22px;">
                <h3 style="font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; margin-top: 0; margin-bottom: 15px; color: var(--text-main);">FAQ & Support Help</h3>
                
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="background: var(--surface-2); padding: 10px 12px; border-radius: 8px; border: 1px solid var(--border);">
                        <strong style="font-size: 12px; display: block; margin-bottom: 4px; color: var(--text-main);">How do I activate my new kit?</strong>
                        <p style="margin: 0; font-size: 11px; color: var(--text-muted); line-height: 1.4;">Go to the "Activate Kit" scan page or enter the 8-digit unique ID code printed manually.</p>
                    </div>

                    <div style="background: var(--surface-2); padding: 10px 12px; border-radius: 8px; border: 1px solid var(--border);">
                        <strong style="font-size: 12px; display: block; margin-bottom: 4px; color: var(--text-main);">Is my biological data secure?</strong>
                        <p style="margin: 0; font-size: 11px; color: var(--text-muted); line-height: 1.4;">Yes, all bio-samples use end-to-end multi-tenant cryptographic hashing protocols.</p>
                    </div>
                </div>

                <a href="#" style="display: block; text-align: center; border: 1px dashed var(--accent); color: var(--text-main); font-size: 12.5px; font-weight: 600; padding: 10px; border-radius: 8px; margin-top: 15px; text-decoration: none;">
                    <i class="fa-solid fa-headset" style="color: var(--accent); margin-right: 4px;"></i> Open Support Ticket
                </a>
            </div>

        </div>

    </div>

</div>

<style>
    div[style*="background: var(--surface)"] {
        transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.2s ease;
    }
    div[style*="background: var(--surface)"]:hover {
        transform: translateY(-3px);
        border-color: rgba(99, 102, 241, 0.35) !important;
        box-shadow: 0 12px 30px -8px rgba(0, 0, 0, 0.5);
    }
</style>

@endsection --}}

@extends('layouts.admin')
@section('title', __('messages.sb_dashboard') ?? 'Health Dashboard')
@section('page_title_key', 'sb_dashboard')
@section('content')

<style>
/* ── Reset & Base ── */
.hd-wrap * { box-sizing: border-box; }
.hd-wrap {
    padding: clamp(16px, 3vw, 32px);
    font-family: 'Inter', sans-serif;
    color: var(--text-main);
    max-width: 1400px;
    margin: 0 auto;
}

/* ── Hero Banner ── */
.hd-hero {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, rgba(6,182,212,0.12) 0%, rgba(99,102,241,0.12) 50%, rgba(16,185,129,0.08) 100%);
    border: 1px solid var(--border);
    border-radius: 24px;
    padding: 28px 30px;
    margin-bottom: 28px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}
.hd-hero::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 260px; height: 260px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 70%);
    pointer-events: none;
}
.hd-hero-left { display: flex; align-items: center; gap: 18px; flex-wrap: wrap; }
.hd-avatar-ring {
    position: relative; width: 76px; height: 76px; flex-shrink: 0;
    border-radius: 50%;
    background: conic-gradient(var(--accent) 0deg 220deg, #10b981 220deg 320deg, var(--border) 320deg 360deg);
    padding: 3px;
}
.hd-avatar-inner {
    width: 100%; height: 100%; border-radius: 50%;
    background: var(--surface);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    font-size: 30px; color: var(--text-muted);
}
.hd-avatar-dot {
    position: absolute; bottom: 3px; right: 3px;
    width: 15px; height: 15px;
    background: #10b981;
    border: 3px solid var(--surface);
    border-radius: 50%;
}
.hd-greeting { font-size: 12px; color: var(--text-muted); margin: 0 0 4px; font-weight: 500; letter-spacing: 0.05em; text-transform: uppercase; }
.hd-name { font-family: 'Syne', sans-serif; font-size: 22px; font-weight: 700; margin: 0 0 8px; }
.hd-meta { display: flex; gap: 12px; flex-wrap: wrap; }
.hd-meta-pill {
    display: flex; align-items: center; gap: 5px;
    font-size: 11.5px; color: var(--text-muted);
    background: var(--surface-2);
    border: 1px solid var(--border);
    padding: 4px 10px; border-radius: 20px;
}
.hd-meta-pill i { font-size: 10px; }
.hd-meta-pill b { color: var(--text-main); font-weight: 600; }

/* Longevity Score pill */
.hd-score-box {
    display: flex; align-items: center; gap: 14px;
    background: var(--surface);
    border: 1px solid var(--border);
    padding: 14px 20px; border-radius: 18px;
    flex-shrink: 0;
}
.hd-score-ring {
    position: relative; width: 64px; height: 64px;
    display: flex; align-items: center; justify-content: center;
}
.hd-score-ring svg { position: absolute; top: 0; left: 0; transform: rotate(-90deg); }
.hd-score-ring .score-num { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 18px; line-height: 1; }
.hd-score-ring .score-total { font-size: 9px; color: var(--text-muted); }
.hd-score-label { font-size: 10px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.08em; color: var(--text-muted); display: block; margin-bottom: 3px; }
.hd-score-status { font-size: 13px; font-weight: 700; color: #10b981; }
.hd-score-trend { font-size: 10.5px; color: var(--text-muted); margin-top: 2px; display: block; }

/* ── Alert Banner ── */
.hd-alert {
    display: flex; align-items: center; gap: 12px;
    background: linear-gradient(90deg, rgba(239,68,68,0.08), rgba(239,68,68,0.03));
    border: 1px solid rgba(239,68,68,0.3);
    border-left: 4px solid #ef4444;
    border-radius: 12px; padding: 12px 16px;
    margin-bottom: 28px;
    font-size: 13px;
}
.hd-alert i { color: #ef4444; font-size: 16px; flex-shrink: 0; }
.hd-alert strong { color: var(--text-main); }
.hd-alert a { color: var(--accent); font-weight: 600; text-decoration: none; margin-left: 6px; }
.hd-alert a:hover { text-decoration: underline; }

/* ── Stats Row ── */
.hd-stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 14px;
    margin-bottom: 28px;
}
.hd-stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 16px 18px;
    position: relative;
    overflow: hidden;
    transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
}
.hd-stat-card:hover {
    transform: translateY(-3px);
    border-color: rgba(99,102,241,0.4);
    box-shadow: 0 10px 28px -8px rgba(0,0,0,0.45);
}
.hd-stat-card::after {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    border-radius: 16px 16px 0 0;
}
.hd-stat-card.c-indigo::after { background: linear-gradient(90deg, var(--accent), #818cf8); }
.hd-stat-card.c-green::after  { background: linear-gradient(90deg, #10b981, #34d399); }
.hd-stat-card.c-cyan::after   { background: linear-gradient(90deg, #06b6d4, #38bdf8); }
.hd-stat-card.c-amber::after  { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
.hd-stat-icon { font-size: 20px; margin-bottom: 10px; }
.hd-stat-value { font-family: 'Syne', sans-serif; font-size: 26px; font-weight: 800; line-height: 1; margin-bottom: 4px; }
.hd-stat-label { font-size: 11px; color: var(--text-muted); font-weight: 500; }
.hd-stat-sub { font-size: 10.5px; color: var(--text-muted); margin-top: 6px; display: flex; align-items: center; gap: 4px; }
.hd-stat-sub .up   { color: #10b981; }
.hd-stat-sub .down { color: #ef4444; }

/* ── Grid Layout ── */
.hd-grid {
    display: grid;
    grid-template-columns: 1.1fr 1fr 0.9fr;
    gap: 22px;
}
@media (max-width: 1100px) { .hd-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 700px)  { .hd-grid { grid-template-columns: 1fr; } }

.hd-col { display: flex; flex-direction: column; gap: 22px; }

/* ── Card Base ── */
.hd-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 22px;
    transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
}
.hd-card:hover {
    transform: translateY(-3px);
    border-color: rgba(99,102,241,0.35);
    box-shadow: 0 12px 32px -8px rgba(0,0,0,0.5);
}
.hd-card-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 18px;
}
.hd-card-title {
    font-family: 'Syne', sans-serif;
    font-size: 15px; font-weight: 700;
    margin: 0; display: flex; align-items: center; gap: 8px;
}
.hd-card-title i { font-size: 14px; color: var(--accent); }
.hd-card-badge {
    font-size: 10px; font-weight: 700;
    padding: 2px 8px; border-radius: 20px;
    background: rgba(99,102,241,0.12); color: var(--accent);
}

/* ── Biomarker Items ── */
.hd-bio-item {
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 13px 14px;
    margin-bottom: 10px;
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: center;
    gap: 10px;
    transition: border-color 0.15s;
}
.hd-bio-item:hover { border-color: rgba(99,102,241,0.3); }
.hd-bio-item:last-child { margin-bottom: 0; }
.hd-bio-category { font-size: 10.5px; color: var(--text-muted); margin-bottom: 2px; text-transform: uppercase; letter-spacing: 0.04em; }
.hd-bio-name { font-size: 13px; font-weight: 600; color: var(--text-main); margin-bottom: 3px; }
.hd-bio-val { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; }
.hd-bio-bar { height: 3px; border-radius: 2px; background: var(--border); margin-top: 6px; overflow: hidden; }
.hd-bio-bar-fill { height: 100%; border-radius: 2px; }
.hd-status-badge {
    font-size: 10px; font-weight: 700;
    padding: 3px 9px; border-radius: 6px;
    white-space: nowrap; text-align: center;
}
.s-optimal { color: #10b981; background: rgba(16,185,129,0.12); }
.s-high    { color: #ef4444; background: rgba(239,68,68,0.12); }
.s-low     { color: #f59e0b; background: rgba(245,158,11,0.12); }
.s-good    { color: #38bdf8; background: rgba(56,189,248,0.12); }

/* ── Progress bar widths ── */
.pw-80 { width: 80%; }
.pw-65 { width: 65%; }
.pw-55 { width: 55%; }
.pw-35 { width: 35%; }

/* ── Insight Banner ── */
.hd-insight {
    background: linear-gradient(135deg, rgba(239,68,68,0.07), rgba(239,68,68,0.03));
    border: 1px solid rgba(239,68,68,0.2);
    border-radius: 12px;
    padding: 13px 15px;
    margin-bottom: 10px;
    font-size: 12.5px;
    display: flex; gap: 10px; align-items: flex-start;
}
.hd-insight.green-i {
    background: linear-gradient(135deg, rgba(16,185,129,0.07), rgba(16,185,129,0.02));
    border-color: rgba(16,185,129,0.2);
}
.hd-insight i { margin-top: 1px; flex-shrink: 0; font-size: 13px; }
.hd-insight p { margin: 0; color: var(--text-muted); line-height: 1.5; }
.hd-insight strong { color: var(--text-main); }

/* ── PDF Button ── */
.hd-pdf-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    background: linear-gradient(135deg, var(--accent), #818cf8);
    color: #fff; font-size: 13px; font-weight: 700;
    padding: 13px; border-radius: 12px; margin-top: 16px;
    text-decoration: none;
    transition: opacity 0.2s, transform 0.2s;
}
.hd-pdf-btn:hover { opacity: 0.9; transform: translateY(-1px); color: #fff; }

/* ── KIT Items ── */
.hd-kit-item {
    background: var(--surface-2);
    border: 1px solid var(--border);
    padding: 13px 15px; border-radius: 12px;
    margin-bottom: 10px;
    display: flex; justify-content: space-between; align-items: center;
    gap: 10px;
}
.hd-kit-id { font-size: 13px; font-weight: 700; font-family: monospace; color: var(--text-main); margin-bottom: 2px; }
.hd-kit-date { font-size: 10.5px; color: var(--text-muted); }
.hd-kit-progress { height: 3px; background: var(--border); border-radius: 2px; margin-top: 5px; }
.hd-kit-progress-fill { height: 100%; border-radius: 2px; }

.hd-verified-box {
    background: rgba(16,185,129,0.05);
    border: 1px solid rgba(16,185,129,0.2);
    border-radius: 12px; padding: 14px;
    text-align: center; margin-top: 4px;
}
.hd-verified-box i { font-size: 22px; color: #10b981; display: block; margin-bottom: 7px; }
.hd-verified-box p { margin: 0 0 10px; font-size: 11.5px; color: var(--text-muted); line-height: 1.5; }
.hd-ghost-btn {
    width: 100%; background: var(--surface-2);
    border: 1px solid var(--border); color: var(--text-main);
    font-size: 12px; font-weight: 600; padding: 8px; border-radius: 8px;
    cursor: pointer; transition: border-color 0.2s;
}
.hd-ghost-btn:hover { border-color: var(--accent); }

/* ── Health Timeline ── */
.hd-timeline { position: relative; padding-left: 22px; }
.hd-timeline::before {
    content: '';
    position: absolute; left: 7px; top: 6px; bottom: 6px;
    width: 2px; background: var(--border); border-radius: 2px;
}
.hd-tl-item { position: relative; margin-bottom: 16px; }
.hd-tl-item:last-child { margin-bottom: 0; }
.hd-tl-dot {
    position: absolute; left: -18px; top: 3px;
    width: 10px; height: 10px; border-radius: 50%;
    border: 2px solid var(--surface);
}
.hd-tl-date { font-size: 10px; color: var(--text-muted); font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase; margin-bottom: 2px; }
.hd-tl-desc { font-size: 12.5px; color: var(--text-main); font-weight: 500; }
.hd-tl-tag {
    display: inline-block; font-size: 9.5px; font-weight: 700;
    padding: 1px 6px; border-radius: 4px; margin-top: 3px;
}

/* ── Notification Rows ── */
.hd-notif-row {
    display: flex; align-items: center; gap: 12px;
    padding: 11px 0;
    border-bottom: 1px solid var(--border);
}
.hd-notif-row:last-child { border-bottom: none; padding-bottom: 0; }
.hd-notif-icon {
    width: 34px; height: 34px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; flex-shrink: 0;
}
.hd-notif-row .label { font-size: 13px; font-weight: 600; flex: 1; }
.hd-notif-row .sub   { font-size: 10.5px; color: var(--text-muted); display: block; margin-top: 1px; }
.hd-toggle {
    width: 38px; height: 21px; border-radius: 20px;
    background: var(--accent); border: none; cursor: pointer;
    position: relative; flex-shrink: 0; transition: background 0.2s;
}
.hd-toggle::after {
    content: '';
    position: absolute; top: 3px; right: 3px;
    width: 15px; height: 15px; border-radius: 50%; background: #fff;
    transition: right 0.2s;
}

/* ── Privacy Card ── */
.hd-privacy-row {
    display: flex; justify-content: space-between; align-items: flex-start;
    padding: 13px 14px;
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: 12px; margin-bottom: 10px;
    gap: 12px;
}
.hd-privacy-row:last-child { margin-bottom: 0; }
.hd-privacy-label { font-size: 13px; font-weight: 600; display: block; margin-bottom: 2px; }
.hd-privacy-sub   { font-size: 10.5px; color: var(--text-muted); }

/* ── FAQ ── */
.hd-faq-item {
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: 10px; padding: 12px 14px;
    margin-bottom: 10px;
}
.hd-faq-item:last-child { margin-bottom: 0; }
.hd-faq-q { font-size: 12px; font-weight: 700; margin-bottom: 5px; display: flex; gap: 6px; }
.hd-faq-q i { color: var(--accent); margin-top: 1px; flex-shrink: 0; font-size: 11px; }
.hd-faq-a { font-size: 11px; color: var(--text-muted); line-height: 1.55; }

/* ── Support CTA ── */
.hd-support-cta {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    border: 1.5px dashed rgba(99,102,241,0.5);
    color: var(--text-main); font-size: 12.5px; font-weight: 600;
    padding: 11px; border-radius: 10px; margin-top: 14px;
    text-decoration: none; transition: border-color 0.2s, background 0.2s;
}
.hd-support-cta:hover { border-color: var(--accent); background: rgba(99,102,241,0.05); color: var(--text-main); }
.hd-support-cta i { color: var(--accent); }

/* ── Retest Banner ── */
.hd-retest {
    background: linear-gradient(135deg, rgba(99,102,241,0.1), rgba(6,182,212,0.07));
    border: 1px solid rgba(99,102,241,0.25);
    border-radius: 14px; padding: 16px 18px;
    display: flex; gap: 14px; align-items: center;
}
.hd-retest-icon {
    width: 42px; height: 42px; border-radius: 12px;
    background: rgba(99,102,241,0.12);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: var(--accent); flex-shrink: 0;
}
.hd-retest-title { font-size: 13px; font-weight: 700; margin-bottom: 2px; }
.hd-retest-sub { font-size: 11px; color: var(--text-muted); }
.hd-retest-btn {
    margin-top: 9px; display: inline-block;
    background: var(--accent); color: #fff;
    font-size: 11px; font-weight: 700;
    padding: 5px 12px; border-radius: 7px;
    text-decoration: none; transition: opacity 0.2s;
}
.hd-retest-btn:hover { opacity: 0.85; color: #fff; }
</style>

<div class="table-wrap">

    {{-- ══ HERO ══ --}}
    <div class="hd-hero">
        <div class="hd-hero-left">
            <div class="hd-avatar-ring">
                <div class="hd-avatar-inner">
                    <i class="fa-solid fa-user-astronaut"></i>
                </div>
                <div class="hd-avatar-dot"></div>
            </div>
            <div>
                <p class="hd-greeting"><i class="fa-solid fa-sun" style="color:#fbbf24; margin-right:4px;"></i> Good Morning</p>
                <h2 class="hd-name">{{ auth()->user()->name }}</h2>
                <div class="hd-meta">
                    <span class="hd-meta-pill"><i class="fa-solid fa-dna" style="color:var(--accent);"></i> Age <b>{{ auth()->user()->age }}</b></span>
                    <span class="hd-meta-pill"><i class="fa-solid fa-arrows-up-down" style="color:#38bdf8;"></i> <b>{{ auth()->user()->height }}</b></span>
                    <span class="hd-meta-pill"><i class="fa-solid fa-weight-scale" style="color:#fbbf24;"></i> <b>{{ auth()->user()->weight }}</b></span>
                    <span class="hd-meta-pill"><i class="fa-solid fa-calendar" style="color:#a78bfa;"></i> Member since Sept 2025</span>
                </div>
            </div>
        </div>

        <div class="hd-score-box">
            <div class="hd-score-ring">
                <svg width="64" height="64" viewBox="0 0 64 64">
                    <circle cx="32" cy="32" r="26" fill="none" stroke="var(--border)" stroke-width="5"/>
                    <circle cx="32" cy="32" r="26" fill="none" stroke="url(#scoreGrad)" stroke-width="5"
                        stroke-dasharray="163" stroke-dashoffset="29" stroke-linecap="round"/>
                    <defs>
                        <linearGradient id="scoreGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="var(--accent)"/>
                            <stop offset="100%" stop-color="#10b981"/>
                        </linearGradient>
                    </defs>
                </svg>
                <div style="text-align:center; position:relative; z-index:1;">
                    <div class="score-num">84</div>
                    <div class="score-total">/100</div>
                </div>
            </div>
            <div>
                <span class="hd-score-label">Longevity Score</span>
                <strong class="hd-score-status"><i class="fa-solid fa-circle-check" style="font-size:11px;"></i> Optimal</strong>
                <span class="hd-score-trend"><i class="fa-solid fa-arrow-trend-up" style="color:#10b981;"></i> +5 since last test</span>
            </div>
        </div>
    </div>

    {{-- ══ ALERT BANNER ══ --}}
    <div class="hd-alert">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div>
            <strong>Action needed:</strong> Your LDL Cholesterol (145 mg/dL) is above the safe threshold.
            <a href="{{ route('user.reports.index') }}">View full report →</a>
        </div>
    </div>

    {{-- ══ STATS ROW ══ --}}
    <div class="hd-stats-row">
        <div class="hd-stat-card c-indigo">
            <div class="hd-stat-icon">🧬</div>
            <div class="hd-stat-value" style="color:var(--accent);">84</div>
            <div class="hd-stat-label">Longevity Score</div>
            <div class="hd-stat-sub"><span class="up"><i class="fa-solid fa-caret-up"></i> +5</span> vs last test</div>
        </div>
        <div class="hd-stat-card c-green">
            <div class="hd-stat-icon">✅</div>
            <div class="hd-stat-value" style="color:#10b981;">3/4</div>
            <div class="hd-stat-label">Biomarkers Optimal</div>
            <div class="hd-stat-sub">1 needs attention</div>
        </div>
        <div class="hd-stat-card c-cyan">
            <div class="hd-stat-icon">🧪</div>
            <div class="hd-stat-value" style="color:#38bdf8;">2</div>
            <div class="hd-stat-label">Active Kits</div>
            <div class="hd-stat-sub">Last test: Mar 25, 2026</div>
        </div>
        <div class="hd-stat-card c-amber">
            <div class="hd-stat-icon">📅</div>
            <div class="hd-stat-value" style="color:#f59e0b;">Jul</div>
            <div class="hd-stat-label">Next Retest Due</div>
            <div class="hd-stat-sub">Recommended in 3 months</div>
        </div>
    </div>

    {{-- ══ MAIN GRID ══ --}}
    <div class="hd-grid">

        {{-- COL 1: Biomarkers + Timeline --}}
        <div class="hd-col">

            {{-- Biomarker Focus --}}
            <div class="hd-card">
                <div class="hd-card-header">
                    <h3 class="hd-card-title"><i class="fa-solid fa-chart-line"></i> Key Biomarkers</h3>
                    <span class="hd-card-badge">Latest Results</span>
                </div>

                {{-- AI Insight --}}
                <div class="hd-insight">
                    <i class="fa-solid fa-circle-exclamation" style="color:#ef4444;"></i>
                    <p><strong>Primary Focus:</strong> LDL Cholesterol needs immediate attention — 8% increase since Jan 2026.</p>
                </div>
                <div class="hd-insight green-i" style="margin-bottom:14px;">
                    <i class="fa-solid fa-arrow-trend-up" style="color:#10b981;"></i>
                    <p><strong>Improving:</strong> Vitamin D increased +4% since last test. Keep it up!</p>
                </div>

                <div class="hd-bio-item">
                    <div>
                        <div class="hd-bio-category">Hormones</div>
                        <div class="hd-bio-name">Testosterone</div>
                        <div class="hd-bio-val">650 <small style="font-size:11px;font-weight:400;color:var(--text-muted);">ng/dL</small></div>
                        <div class="hd-bio-bar"><div class="hd-bio-bar-fill pw-80" style="background:#10b981;"></div></div>
                    </div>
                    <span class="hd-status-badge s-optimal">Optimal</span>
                </div>

                <div class="hd-bio-item" style="border-color:rgba(239,68,68,0.25);">
                    <div>
                        <div class="hd-bio-category">Cardiovascular</div>
                        <div class="hd-bio-name">LDL Cholesterol</div>
                        <div class="hd-bio-val" style="color:#ef4444;">145 <small style="font-size:11px;font-weight:400;color:var(--text-muted);">mg/dL</small></div>
                        <div class="hd-bio-bar"><div class="hd-bio-bar-fill pw-65" style="background:#ef4444;"></div></div>
                    </div>
                    <span class="hd-status-badge s-high">High Risk</span>
                </div>

                <div class="hd-bio-item">
                    <div>
                        <div class="hd-bio-category">Metabolic</div>
                        <div class="hd-bio-name">Fasting Glucose</div>
                        <div class="hd-bio-val">88 <small style="font-size:11px;font-weight:400;color:var(--text-muted);">mg/dL</small></div>
                        <div class="hd-bio-bar"><div class="hd-bio-bar-fill pw-55" style="background:#10b981;"></div></div>
                    </div>
                    <span class="hd-status-badge s-optimal">Optimal</span>
                </div>

                <div class="hd-bio-item">
                    <div>
                        <div class="hd-bio-category">Nutrients</div>
                        <div class="hd-bio-name">Vitamin D</div>
                        <div class="hd-bio-val" style="color:#f59e0b;">22 <small style="font-size:11px;font-weight:400;color:var(--text-muted);">ng/mL</small></div>
                        <div class="hd-bio-bar"><div class="hd-bio-bar-fill pw-35" style="background:#f59e0b;"></div></div>
                    </div>
                    <span class="hd-status-badge s-low">Low</span>
                </div>

                <a href="{{ route('user.reports.index') }}" class="hd-pdf-btn">
                    <i class="fa-solid fa-file-arrow-down"></i> Download Full PDF Report
                </a>
            </div>

            {{-- Health Timeline --}}
            <div class="hd-card">
                <div class="hd-card-header">
                    <h3 class="hd-card-title"><i class="fa-solid fa-clock-rotate-left"></i> Health Timeline</h3>
                </div>
                <div class="hd-timeline">
                    <div class="hd-tl-item">
                        <div class="hd-tl-dot" style="background:#10b981;"></div>
                        <div class="hd-tl-date">Mar 25, 2026</div>
                        <div class="hd-tl-desc">Comprehensive biomarker test completed</div>
                        <span class="hd-tl-tag" style="background:rgba(16,185,129,0.12);color:#10b981;">Kit N/H-8291-952G</span>
                    </div>
                    <div class="hd-tl-item">
                        <div class="hd-tl-dot" style="background:var(--accent);"></div>
                        <div class="hd-tl-date">Jan 15, 2026</div>
                        <div class="hd-tl-desc">Previous test — LDL noted as borderline</div>
                        <span class="hd-tl-tag" style="background:rgba(99,102,241,0.12);color:var(--accent);">Kit N/H-9120-450L</span>
                    </div>
                    <div class="hd-tl-item">
                        <div class="hd-tl-dot" style="background:#a78bfa;"></div>
                        <div class="hd-tl-date">Sept 2025</div>
                        <div class="hd-tl-desc">Joined the platform — baseline test</div>
                        <span class="hd-tl-tag" style="background:rgba(167,139,250,0.12);color:#a78bfa;">Member Start</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- COL 2: Connected KITs + Retest + Privacy --}}
        <div class="hd-col">

            {{-- Connected KITs --}}
            <div class="hd-card">
                <div class="hd-card-header">
                    <h3 class="hd-card-title"><i class="fa-solid fa-vial"></i> Connected KITS</h3>
                    <span class="hd-card-badge">Fleet Manager</span>
                </div>

                <div class="hd-kit-item" style="border-color:rgba(56,189,248,0.25);">
                    <div style="flex:1;">
                        <div class="hd-kit-id">N/H-8291-952G</div>
                        <div class="hd-kit-date"><i class="fa-regular fa-calendar" style="color:var(--accent); margin-right:3px;"></i> Registered: Apr 24, 2026</div>
                        <div class="hd-kit-progress"><div class="hd-kit-progress-fill" style="width:70%;background:#38bdf8;"></div></div>
                    </div>
                    <span class="hd-status-badge s-good">Active</span>
                </div>

                <div class="hd-kit-item">
                    <div style="flex:1;">
                        <div class="hd-kit-id">N/H-9120-450L</div>
                        <div class="hd-kit-date"><i class="fa-regular fa-calendar" style="color:#10b981; margin-right:3px;"></i> Completed: Jan 15, 2026</div>
                        <div class="hd-kit-progress"><div class="hd-kit-progress-fill" style="width:100%;background:#10b981;"></div></div>
                    </div>
                    <span class="hd-status-badge s-optimal">Used</span>
                </div>

                <div class="hd-verified-box">
                    <i class="fa-solid fa-shield-check"></i>
                    <p>Active kit verified & processed by our certified partner laboratory. Results are finalized.</p>
                    <button class="hd-ghost-btn"><i class="fa-solid fa-magnifying-glass" style="margin-right:4px;"></i> View Full Kit Details</button>
                </div>
            </div>

            {{-- Retest Reminder --}}
            <div class="hd-card" style="padding: 18px 20px;">
                <div class="hd-retest">
                    <div class="hd-retest-icon">
                        <i class="fa-solid fa-rotate"></i>
                    </div>
                    <div>
                        <div class="hd-retest-title">Schedule Your Retest</div>
                        <div class="hd-retest-sub">Recommended: every 3 months to track your progress and adjust your health plan.</div>
                        <a href="#" class="hd-retest-btn"><i class="fa-solid fa-calendar-plus" style="margin-right:4px;"></i> Schedule Now</a>
                    </div>
                </div>
            </div>

            {{-- Privacy & Security --}}
            <div class="hd-card">
                <div class="hd-card-header">
                    <h3 class="hd-card-title"><i class="fa-solid fa-shield-halved"></i> Privacy & Security</h3>
                </div>
                <div class="hd-privacy-row">
                    <div>
                        <span class="hd-privacy-label">Data Sharing Control</span>
                        <span class="hd-privacy-sub">Manage how your health data is shared with partners.</span>
                    </div>
                    <button class="hd-toggle" title="Toggle data sharing"></button>
                </div>
                <div class="hd-privacy-row">
                    <div>
                        <span class="hd-privacy-label">Two-Factor Authentication</span>
                        <span class="hd-privacy-sub">Add an extra layer of account security.</span>
                    </div>
                    <button class="hd-toggle" title="Toggle 2FA"></button>
                </div>
                {{-- <div class="hd-privacy-row">
                    <div>
                        <span class="hd-privacy-label">Marketing Preferences</span>
                        <span class="hd-privacy-sub">Control how we contact you about new features.</span>
                    </div>
                    <button class="hd-toggle" style="background:var(--border);" title="Toggle marketing"></button>
                </div> --}}
            </div>
        </div>

        {{-- COL 3: Notifications + FAQ --}}
        <div class="hd-col">

            {{-- Notifications --}}
            <div class="hd-card">
                <div class="hd-card-header">
                    <h3 class="hd-card-title"><i class="fa-solid fa-bell"></i> Notifications</h3>
                </div>
                <div class="hd-notif-row">
                    <div class="hd-notif-icon" style="background:rgba(16,185,129,0.1);color:#10b981;">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div style="flex:1;">
                        <span class="label">Email Health Reports</span>
                        <span class="sub">Sent after each test is finalized</span>
                    </div>
                    <button class="hd-toggle" title="Toggle email"></button>
                </div>
                <div class="hd-notif-row">
                    <div class="hd-notif-icon" style="background:rgba(99,102,241,0.1);color:var(--accent);">
                        <i class="fa-solid fa-flask"></i>
                    </div>
                    <div style="flex:1;">
                        <span class="label">Biomarker Test Reminders</span>
                        <span class="sub">Notified 1 week before due date</span>
                    </div>
                    <button class="hd-toggle" title="Toggle reminders"></button>
                </div>
                <div class="hd-notif-row">
                    <div class="hd-notif-icon" style="background:rgba(56,189,248,0.1);color:#38bdf8;">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <div style="flex:1;">
                        <span class="label">AI Smart Insights</span>
                        <span class="sub">Weekly personalized health tips</span>
                    </div>
                    <button class="hd-toggle" title="Toggle AI insights"></button>
                </div>
                {{-- <div class="hd-notif-row">
                    <div class="hd-notif-icon" style="background:rgba(245,158,11,0.1);color:#f59e0b;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div style="flex:1;">
                        <span class="label">Community Updates</span>
                        <span class="sub">News & platform announcements</span>
                    </div>
                    <button class="hd-toggle" style="background:var(--border);" title="Toggle community"></button>
                </div> --}}
            </div>

            {{-- FAQ & Support --}}
            <div class="hd-card">
                <div class="hd-card-header">
                    <h3 class="hd-card-title"><i class="fa-solid fa-circle-question"></i> FAQ & Support</h3>
                </div>

                <div class="hd-faq-item">
                    <div class="hd-faq-q"><i class="fa-solid fa-chevron-right"></i> How do I activate my new kit?</div>
                    <p class="hd-faq-a">Go to the "Activate Kit" page or enter the 8-digit ID code printed on your kit packaging.</p>
                </div>
                <div class="hd-faq-item">
                    <div class="hd-faq-q"><i class="fa-solid fa-chevron-right"></i> When will I get my results?</div>
                    <p class="hd-faq-a">Results are typically available within 2–5 business days after our lab receives your sample.</p>
                </div>
                <div class="hd-faq-item">
                    <div class="hd-faq-q"><i class="fa-solid fa-chevron-right"></i> Is my biological data secure?</div>
                    <p class="hd-faq-a">Yes. We use industry-standard end-to-end encryption and strict privacy protocols to keep your data safe.</p>
                </div>
                <div class="hd-faq-item">
                    <div class="hd-faq-q"><i class="fa-solid fa-chevron-right"></i> How often should I retest?</div>
                    <p class="hd-faq-a">We recommend retesting every 3–6 months to track your progress and adjust your health plan accordingly.</p>
                </div>

                <a href="#" class="hd-support-cta">
                    <i class="fa-solid fa-headset"></i> Open a Support Ticket
                </a>
            </div>

        </div>
    </div>

</div>

@endsection