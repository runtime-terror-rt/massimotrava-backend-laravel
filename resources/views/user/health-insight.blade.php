@extends('layouts.admin')

@section('title', 'Health Insights')

@section('content')

<style>
/* ── Reset ── */
*{box-sizing:border-box}

/* ── Page ── */
.hi{padding:4px 0}
.hi-topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;flex-wrap:wrap;gap:12px}
.hi-title{font-family:'Syne',sans-serif;font-size:24px;font-weight:800;letter-spacing:-0.03em;color:#fff}
.hi-sub{font-size:13px;color:#64748b;margin-top:3px}
.analysis-badge{display:flex;align-items:center;gap:6px;font-size:12px;font-weight:700;color:#10b981;background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.2);padding:7px 16px;border-radius:20px}
.analysis-badge span{width:7px;height:7px;border-radius:50%;background:#10b981;display:inline-block;animation:abPulse 2s ease-in-out infinite}
@keyframes abPulse{0%,100%{opacity:1}50%{opacity:0.4}}

/* ── Alert banner ── */
.alert-banner{background:rgba(34,211,238,0.04);border:1px solid rgba(34,211,238,0.18);border-radius:14px;padding:13px 20px;display:flex;align-items:center;gap:10px;font-size:13px;color:#94a3b8;margin-bottom:24px}
.alert-banner i{color:#22d3ee;font-size:15px;flex-shrink:0}
.alert-banner b{color:#e2e8f0}

/* ── Main grid ── */
.hi-main-grid{display:grid;grid-template-columns:340px 1fr;gap:16px;margin-bottom:20px}

/* ── Longevity Score Card ── */
.score-card{background:var(--surface);border:1.5px solid rgba(34,211,238,0.22);border-radius:20px;padding:24px 26px;position:relative;overflow:hidden}
.score-card::before{content:'';position:absolute;top:-60px;right:-60px;width:180px;height:180px;border-radius:50%;background:radial-gradient(circle,rgba(34,211,238,0.07),transparent);pointer-events:none}
.sc-label{font-size:10px;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:#22d3ee;margin-bottom:10px}
.sc-num-row{display:flex;align-items:flex-end;gap:4px}
.sc-big{font-family:'Syne',sans-serif;font-size:56px;font-weight:900;color:#fff;line-height:1}
.sc-denom{font-size:16px;color:#64748b;margin-bottom:8px}
.sc-meta-row{display:flex;align-items:center;gap:10px;margin-top:6px;flex-wrap:wrap}
.sc-since{font-size:11.5px;color:#64748b}
.sc-since b{color:#22d3ee}
.sc-improve{display:inline-flex;align-items:center;gap:4px;font-size:12px;font-weight:700;color:#10b981;background:rgba(16,185,129,0.08);padding:3px 10px;border-radius:20px;border:1px solid rgba(16,185,129,0.15)}
.mini-chart{margin:18px 0 14px}
.mini-chart svg{width:100%;height:72px}
.sc-message{font-size:12.5px;color:#94a3b8;line-height:1.65;font-style:italic;border-top:1px solid rgba(255,255,255,0.05);padding-top:14px}
.sc-kit-meta{font-size:11px;color:#475569;margin-top:8px}

/* ── Summary card ── */
.summary-card{background:var(--surface);border:1px solid rgba(255,255,255,0.06);border-radius:20px;padding:24px 26px;display:flex;flex-direction:column;gap:18px}
.summary-headline{font-size:15.5px;line-height:1.75;color:#e2e8f0}
.summary-headline .imp{color:#10b981;font-weight:700}
.summary-headline .warn{color:#f59e0b;font-weight:700}

/* ── Focus sections ── */
.focus-tag{display:flex;align-items:center;gap:8px;margin-bottom:10px;flex-wrap:wrap}
.focus-tag-label{font-family:'Syne',sans-serif;font-size:14px;font-weight:800}
.focus-tag-sub{font-size:11.5px;color:#64748b}

/* ── Marker rows ── */
.marker-row{background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.06);border-radius:14px;padding:14px 18px;display:flex;align-items:center;gap:14px;transition:border-color .2s}
.marker-row:hover{border-color:rgba(255,255,255,0.12)}
.marker-icon{width:38px;height:38px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0}
.mi-red{background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.15)}
.mi-green{background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.15)}
.mi-blue{background:rgba(34,211,238,0.08);border:1px solid rgba(34,211,238,0.15)}
.marker-info{flex:1;min-width:0}
.marker-name{font-size:13.5px;font-weight:700;color:#f1f5f9;margin-bottom:2px}
.marker-status{font-size:11.5px;color:#94a3b8}
.marker-right{display:flex;flex-direction:column;align-items:flex-end;gap:5px;flex-shrink:0}
.priority-badge{font-size:11px;font-weight:700;padding:3px 11px;border-radius:20px;white-space:nowrap}
.pb-high{background:rgba(239,68,68,0.08);color:#f87171;border:1px solid rgba(239,68,68,0.15)}
.pb-good{background:rgba(16,185,129,0.08);color:#10b981;border:1px solid rgba(16,185,129,0.15)}
.pb-stable{background:rgba(34,211,238,0.08);color:#22d3ee;border:1px solid rgba(34,211,238,0.15)}
.change-badge{font-size:11.5px;font-weight:700}
.cb-up{color:#f59e0b}
.cb-down{color:#10b981}

/* ── Section header ── */
.sec-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
.sec-title{font-family:'Syne',sans-serif;font-size:16px;font-weight:800;color:#fff}

/* ── Smart Insights ── */
.insights-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px;margin-bottom:22px}
.insight-card{background:var(--surface);border:1px solid rgba(255,255,255,0.06);border-radius:16px;padding:20px 22px;transition:border-color .2s,transform .2s}
.insight-card:hover{border-color:rgba(255,255,255,0.12);transform:translateY(-2px)}
.insight-card.priority{border-color:rgba(245,158,11,0.18);background:rgba(245,158,11,0.02)}
.insight-card.priority:hover{border-color:rgba(245,158,11,0.35)}
.ic-top{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:10px}
.ic-title{font-size:13.5px;font-weight:700;color:#f1f5f9;line-height:1.45;flex:1}
.ic-body{font-size:12.5px;color:#94a3b8;line-height:1.65}
.ic-impact{display:flex;align-items:center;gap:7px;font-size:11.5px;font-weight:600;color:#a78bfa;margin-top:12px}
.ic-impact-dot{width:12px;height:12px;border-radius:50%;background:rgba(167,139,250,0.15);border:1px solid rgba(167,139,250,0.35);flex-shrink:0}

/* ── Biomarker Trends ── */
.trends-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:22px}
.trend-card{background:var(--surface);border:1px solid rgba(255,255,255,0.06);border-radius:16px;padding:16px 18px;transition:border-color .2s}
.trend-card:hover{border-color:rgba(255,255,255,0.12)}
.trend-card.tc-low{border-color:rgba(239,68,68,0.12)}
.trend-card.tc-optimal{border-color:rgba(16,185,129,0.12)}
.tc-badge{display:inline-flex;align-items:center;gap:5px;font-size:10.5px;font-weight:700;padding:3px 10px;border-radius:20px;margin-bottom:10px}
.tcb-low{background:rgba(239,68,68,0.08);color:#f87171;border:1px solid rgba(239,68,68,0.15)}
.tcb-opt{background:rgba(16,185,129,0.08);color:#10b981;border:1px solid rgba(16,185,129,0.15)}
.tc-name{font-size:13px;font-weight:700;color:#f1f5f9;margin-bottom:2px}
.tc-sub{font-size:11.5px;color:#64748b;margin-bottom:8px}
.tc-pct{font-size:13.5px;font-weight:700}
.tc-chart{margin-top:10px}
.tc-chart svg{width:100%;height:42px}

/* ── Retest Reminder ── */
.retest-card{background:var(--surface);border:1px solid rgba(99,102,241,0.2);border-radius:20px;padding:26px 32px;display:flex;align-items:center;justify-content:space-between;gap:28px;flex-wrap:wrap}
.rt-eyebrow{font-size:10.5px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#6366f1;margin-bottom:8px;display:flex;align-items:center;gap:6px}
.rt-title{font-size:16px;font-weight:700;color:#f1f5f9;margin-bottom:6px;line-height:1.4}
.rt-body{font-size:13px;color:#94a3b8;line-height:1.65;max-width:580px}
.rt-btn{background:linear-gradient(135deg,#10b981,#06b6d4);color:#000;font-size:13.5px;font-weight:800;padding:13px 32px;border-radius:12px;border:none;cursor:pointer;white-space:nowrap;letter-spacing:0.02em;transition:opacity .2s,transform .2s;flex-shrink:0}
.rt-btn:hover{opacity:0.9;transform:translateY(-1px)}

/* ── Responsive ── */
@media(max-width:1100px){.hi-main-grid{grid-template-columns:1fr}}
@media(max-width:900px){.insights-grid{grid-template-columns:1fr}.trends-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.trends-grid{grid-template-columns:1fr}.retest-card{flex-direction:column;align-items:flex-start}.hi-topbar{flex-direction:column;align-items:flex-start}}
</style>

<div class="hi">

    {{-- ── Top bar ── --}}
    <div class="hi-topbar">
        <div>
            <div class="hi-title">Insights</div>
            <div class="hi-sub">Showing insights from your latest test</div>
        </div>
        <div class="analysis-badge">
            <span></span> Analysis complete
        </div>
    </div>

    {{-- ── Alert Banner ── --}}
    <div class="alert-banner">
        <i class="fa-solid fa-circle-info"></i>
        <span><b>Update:</b> Your sample was received at the lab yesterday. Results are finalized.</span>
    </div>

    {{-- ── Main Grid: Score + Summary ── --}}
    <div class="hi-main-grid">

        {{-- Longevity Score Card --}}
        <div class="score-card">
            <div class="sc-label">Longevity Score</div>
            <div class="sc-num-row">
                <div class="sc-big">{{ $longevityScore ?? 84 }}</div>
                <div class="sc-denom">/100</div>
            </div>
            <div class="sc-meta-row">
                <span class="sc-since">Since <b>{{ $scoreSince ?? 'Oct 2025' }}</b></span>
                <span class="sc-improve">↑ +{{ $scoreImprovement ?? 12 }} improvement</span>
            </div>

            {{-- Mini Chart --}}
            <div class="mini-chart">
                <svg viewBox="0 0 280 72" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="scoreGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#10b981" stop-opacity="0.2"/>
                            <stop offset="100%" stop-color="#10b981" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <line x1="0" y1="20" x2="280" y2="20" stroke="rgba(255,255,255,0.04)" stroke-width="1"/>
                    <line x1="0" y1="40" x2="280" y2="40" stroke="rgba(255,255,255,0.04)" stroke-width="1"/>
                    <path d="M0,58 L47,52 L94,46 L141,40 L188,30 L235,22 L280,14 L280,72 L0,72Z" fill="url(#scoreGrad)"/>
                    <polyline points="0,58 47,52 94,46 141,40 188,30 235,22 280,14"
                        fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="280" cy="14" r="4" fill="#10b981" stroke="#0d1117" stroke-width="2"/>
                    <text x="2" y="70" font-size="9" fill="#475569" font-family="Inter">Oct</text>
                    <text x="43" y="70" font-size="9" fill="#475569" font-family="Inter">Nov</text>
                    <text x="90" y="70" font-size="9" fill="#475569" font-family="Inter">Dec</text>
                    <text x="137" y="70" font-size="9" fill="#475569" font-family="Inter">Jan</text>
                    <text x="184" y="70" font-size="9" fill="#475569" font-family="Inter">Feb</text>
                    <text x="231" y="70" font-size="9" fill="#475569" font-family="Inter">Mar</text>
                    <text x="236" y="12" font-size="9" fill="#10b981" font-family="Inter" font-weight="700">Now {{ $longevityScore ?? 84 }}</text>
                    <text x="2" y="56" font-size="9" fill="#64748b" font-family="Inter">Prev {{ $previousScore ?? 74 }}</text>
                </svg>
            </div>

            <div class="sc-message">"Your longevity score has steadily improved over the past 6 months."</div>
            <div class="sc-kit-meta">
                Based on {{ $testDate ?? 'Mar 25, 2026' }} test
                · kit #{{ $kitNumber ?? '23281' }}
                · Updated {{ $updatedAgo ?? '2 days ago' }}
            </div>
        </div>

        {{-- Summary + Focus --}}
        <div class="summary-card">
            <div class="summary-headline">
                Your overall health is <span class="imp">improving</span>, but your
                <span class="warn">LDL Cholesterol needs attention.</span>
            </div>

            {{-- Primary Focus --}}
            <div>
                <div class="focus-tag">
                    <span style="font-size:16px">🎯</span>
                    <span class="focus-tag-label" style="color:#f87171">Primary Focus</span>
                    <span class="focus-tag-sub">Compared to your last test (Jan 2026)</span>
                </div>
                <div class="marker-row">
                    <div class="marker-icon mi-red">🫀</div>
                    <div class="marker-info">
                        <div class="marker-name">LDL Cholesterol</div>
                        <div class="marker-status">Needs attention</div>
                    </div>
                    <div class="marker-right">
                        <span class="priority-badge pb-high">High Priority</span>
                        <span class="change-badge cb-up">+8% increase</span>
                    </div>
                </div>
            </div>

            {{-- Improving --}}
            <div>
                <div class="focus-tag">
                    <span style="font-size:16px">📈</span>
                    <span class="focus-tag-label" style="color:#10b981">Improving</span>
                    <span class="focus-tag-sub">Compared to your last test (Jan 2026)</span>
                </div>
                <div class="marker-row">
                    <div class="marker-icon mi-green">☀️</div>
                    <div class="marker-info">
                        <div class="marker-name">Vitamin D</div>
                        <div class="marker-status">Improving</div>
                    </div>
                    <div class="marker-right">
                        <span class="priority-badge pb-good">Good</span>
                        <span class="change-badge cb-down">+4% increase</span>
                    </div>
                </div>
            </div>

            {{-- Stable / Monitor --}}
            <div>
                <div class="focus-tag">
                    <span style="font-size:16px">⚡</span>
                    <span class="focus-tag-label" style="color:#22d3ee">Stable / Monitor</span>
                    <span class="focus-tag-sub">Compared to your last test (Jan 2026)</span>
                </div>
                <div class="marker-row">
                    <div class="marker-icon mi-blue">🧬</div>
                    <div class="marker-info">
                        <div class="marker-name">Testosterone</div>
                        <div class="marker-status">Stable</div>
                    </div>
                    <div class="marker-right">
                        <span class="priority-badge pb-stable">Stable</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Smart Insights ── --}}
    <div class="sec-head">
        <div class="sec-title">💡 Smart Insights</div>
    </div>
    <div class="insights-grid">

        <div class="insight-card priority">
            <div class="ic-top">
                <div class="ic-title">LDL Cholesterol has increased since your last test</div>
                <span class="priority-badge pb-high">High Priority</span>
            </div>
            <div class="ic-body">High LDL can increase long-term heart disease risk. Dietary adjustments now can make a meaningful difference within 8 weeks.</div>
            <div class="ic-impact">
                <div class="ic-impact-dot"></div>
                High Impact: Direct link to cardiovascular longevity
            </div>
        </div>

        <div class="insight-card priority">
            <div class="ic-top">
                <div class="ic-title">LDL is trending upward — act before it peaks</div>
                <span class="priority-badge pb-high">High Priority</span>
            </div>
            <div class="ic-body">Reduce saturated fats, add soluble fiber, and consider omega-3 supplementation. Retest in 3 months to track progress.</div>
            <div class="ic-impact">
                <div class="ic-impact-dot"></div>
                High Impact: Direct link to cardiovascular longevity
            </div>
        </div>

        <div class="insight-card">
            <div class="ic-top">
                <div class="ic-title">Vitamin D is recovering well</div>
                <span class="priority-badge pb-good">Good</span>
            </div>
            <div class="ic-body">Your Vitamin D has improved +4% since last test. Morning sunlight and your supplement routine are working — keep it up.</div>
            <div class="ic-impact">
                <div class="ic-impact-dot"></div>
                Supports immune function and bone density
            </div>
        </div>

        <div class="insight-card">
            <div class="ic-top">
                <div class="ic-title">Testosterone remains in a strong range</div>
                <span class="priority-badge pb-stable">Stable</span>
            </div>
            <div class="ic-body">No change needed. Your current sleep, training, and nutrition routine is maintaining excellent hormonal balance.</div>
            <div class="ic-impact">
                <div class="ic-impact-dot"></div>
                Supports energy, metabolism, and longevity
            </div>
        </div>

    </div>

    {{-- ── Biomarker Trends ── --}}
    <div class="sec-head">
        <div class="sec-title">📊 Biomarker Trends</div>
    </div>
    <div class="trends-grid">

        <div class="trend-card tc-low">
            <div class="tc-badge tcb-low">● Low</div>
            <div class="tc-name">Vitamin D</div>
            <div class="tc-sub">Improving</div>
            <div class="tc-pct" style="color:#f59e0b">↑ +12%</div>
            <div class="tc-chart">
                <svg viewBox="0 0 120 42" xmlns="http://www.w3.org/2000/svg">
                    <polyline points="0,34 20,30 40,26 60,20 80,16 100,10 120,6"
                        fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        <div class="trend-card tc-optimal">
            <div class="tc-badge tcb-opt">● Optimal</div>
            <div class="tc-name">Cortisol</div>
            <div class="tc-sub">Improving</div>
            <div class="tc-pct" style="color:#10b981">↑ +12%</div>
            <div class="tc-chart">
                <svg viewBox="0 0 120 42" xmlns="http://www.w3.org/2000/svg">
                    <polyline points="0,32 20,28 40,22 60,18 80,12 100,8 120,4"
                        fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        <div class="trend-card tc-low">
            <div class="tc-badge tcb-low">● Needs Attention</div>
            <div class="tc-name">LDL Cholesterol</div>
            <div class="tc-sub">Rising</div>
            <div class="tc-pct" style="color:#f87171">↑ +8%</div>
            <div class="tc-chart">
                <svg viewBox="0 0 120 42" xmlns="http://www.w3.org/2000/svg">
                    <polyline points="0,30 20,28 40,24 60,20 80,14 100,10 120,6"
                        fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        <div class="trend-card tc-optimal">
            <div class="tc-badge tcb-opt">● Optimal</div>
            <div class="tc-name">Testosterone</div>
            <div class="tc-sub">Stable</div>
            <div class="tc-pct" style="color:#10b981">→ Stable</div>
            <div class="tc-chart">
                <svg viewBox="0 0 120 42" xmlns="http://www.w3.org/2000/svg">
                    <polyline points="0,22 20,24 40,20 60,22 80,21 100,23 120,21"
                        fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

    </div>

    {{-- ── Retest Reminder ── --}}
    <div class="retest-card">
        <div>
            <div class="rt-eyebrow">
                <i class="fa-regular fa-calendar-check"></i> Retest Reminder
            </div>
            <div class="rt-title">Retest in 3 months to track Vitamin D improvement</div>
            <div class="rt-body">Regular testing helps you see if lifestyle changes are effectively lowering your LDL Cholesterol and improving Vitamin D levels.</div>
        </div>
        <button class="rt-btn" onclick="sendRetestMessage()">
            <i class="fa-solid fa-paper-plane" style="margin-right:6px"></i> Send Message
        </button>
    </div>

</div>

<script>
function sendRetestMessage() {
    alert('Retest reminder sent!');
}
</script>

@endsection