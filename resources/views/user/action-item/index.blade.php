@extends('layouts.admin')

@section('title', 'Action Items')

@section('content')

<style>
.ai-page { padding: 4px 0 40px }

/* Top bar */
.ai-topbar { margin-bottom: 28px }
.ai-title { font-family:'Syne',sans-serif;font-size:24px;font-weight:800;letter-spacing:-0.03em;color:#fff }
.ai-sub   { font-size:13px;color:#64748b;margin-top:4px }

/* Progress card */
.ai-progress-card {
    background: linear-gradient(135deg, rgba(16,185,129,0.08), rgba(34,211,238,0.06));
    border: 1px solid rgba(34,211,238,0.15);
    border-radius: 20px;
    padding: 24px 28px;
    margin-bottom: 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}
.ai-progress-left { flex: 1; min-width: 200px }
.ai-progress-title {
    font-family:'Syne',sans-serif;
    font-size: 16px;
    font-weight: 800;
    color: #fff;
    margin-bottom: 6px;
}
.ai-progress-sub { font-size:13px;color:#94a3b8;margin-bottom:14px }
.ai-prog-bar-bg {
    height: 8px;
    border-radius: 99px;
    background: rgba(255,255,255,0.07);
    overflow: hidden;
    max-width: 340px;
}
.ai-prog-bar-fill {
    height: 100%;
    border-radius: 99px;
    background: linear-gradient(90deg, #10b981, #22d3ee);
    transition: width .6s cubic-bezier(.4,0,.2,1);
}
.ai-prog-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 8px;
}
.ai-prog-label { font-size:12px;color:#64748b }
.ai-prog-pct   { font-size:12px;font-weight:700;color:#22d3ee }

/* Big counter */
.ai-counter {
    text-align: center;
    flex-shrink: 0;
}
.ai-counter-num {
    font-family:'Syne',sans-serif;
    font-size: 48px;
    font-weight: 900;
    line-height: 1;
    background: linear-gradient(135deg,#10b981,#22d3ee);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.ai-counter-label { font-size:12px;color:#64748b;margin-top:4px }

/* What to expect section */
.section-label {
    font-size:11px;
    font-weight:700;
    letter-spacing:.07em;
    text-transform:uppercase;
    color:#475569;
    margin-bottom:14px;
    display:flex;
    align-items:center;
    gap:8px;
}
.section-label::after {
    content:'';
    flex:1;
    height:1px;
    background:rgba(255,255,255,0.05);
}

/* What to expect cards */
.wte-grid {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 14px;
    margin-bottom: 32px;
}
.wte-card {
    background: var(--surface);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 16px;
    overflow: hidden;
    transition: border-color .2s, transform .2s;
}
.wte-card:hover {
    border-color: rgba(255,255,255,0.12);
    transform: translateY(-2px);
}
.wte-img {
    width: 100%;
    height: 130px;
    object-fit: cover;
    background: rgba(255,255,255,0.04);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
}
.wte-img-placeholder {
    width: 100%;
    height: 130px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
}
.wte-body { padding: 16px }
.wte-num {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #111827;
    border: 1px solid rgba(255,255,255,0.1);
    font-size: 11px;
    font-weight: 800;
    color: #f1f5f9;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
}
.wte-title { font-size:14px;font-weight:700;color:#f1f5f9;margin-bottom:6px }
.wte-desc  { font-size:12px;color:#64748b;line-height:1.6 }
.wte-link  { font-size:12px;font-weight:600;color:#22d3ee;text-decoration:none;display:inline-flex;align-items:center;gap:5px;margin-top:8px }
.wte-link:hover { color:#67e8f9 }

/* Action steps list */
.ai-steps-list { display:flex;flex-direction:column;gap:12px;margin-bottom:28px }

.ai-step-card {
    background: var(--surface);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 16px;
    padding: 18px 22px;
    display: flex;
    align-items: center;
    gap: 18px;
    transition: border-color .2s, background .2s;
    position: relative;
    overflow: hidden;
}
.ai-step-card:hover { border-color: rgba(255,255,255,0.1); background: rgba(255,255,255,0.015) }
.ai-step-card.done  {
    border-color: rgba(16,185,129,0.15);
    background: rgba(16,185,129,0.03);
}
.ai-step-card.done::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: linear-gradient(180deg,#10b981,#06b6d4);
    border-radius: 3px 0 0 3px;
}

/* Checkbox */
.ai-step-check {
    width: 22px;
    height: 22px;
    border-radius: 6px;
    border: 2px solid rgba(255,255,255,0.12);
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    color: transparent;
    transition: all .2s;
    cursor: pointer;
}
.ai-step-card.done .ai-step-check {
    background: rgba(16,185,129,0.15);
    border-color: #10b981;
    color: #10b981;
}

/* Step icon */
.ai-step-icon-wrap {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
    transition: opacity .2s;
}
.ai-step-card.done .ai-step-icon-wrap { opacity: 0.5 }

/* Step content */
.ai-step-content { flex: 1; min-width: 0 }
.ai-step-title-main {
    font-size: 14px;
    font-weight: 700;
    color: #f1f5f9;
    margin-bottom: 3px;
    transition: color .2s;
}
.ai-step-card.done .ai-step-title-main {
    color: #475569;
    text-decoration: line-through;
    text-decoration-color: #334155;
}
.ai-step-desc-main { font-size:12.5px;color:#64748b;line-height:1.5 }

/* Right side */
.ai-step-right { display:flex;align-items:center;gap:12px;flex-shrink:0 }
.ai-step-time { font-size:11.5px;color:#475569;display:flex;align-items:center;gap:5px }
.ai-step-time i { font-size:10px }

.ai-step-btn {
    font-size: 12px;
    font-weight: 700;
    padding: 8px 18px;
    border-radius: 9px;
    border: 1px solid rgba(255,255,255,0.1);
    background: rgba(255,255,255,0.05);
    color: #f1f5f9;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all .2s;
    white-space: nowrap;
    cursor: pointer;
}
.ai-step-btn:hover { background: rgba(255,255,255,0.1); color:#fff; border-color:rgba(255,255,255,0.2) }
.ai-step-btn.btn-primary-g {
    background: linear-gradient(135deg,#10b981,#06b6d4);
    color: #000;
    border-color: transparent;
    font-weight: 800;
}
.ai-step-btn.btn-primary-g:hover { transform:translateY(-1px);box-shadow:0 4px 16px rgba(16,185,129,0.3) }
.ai-step-card.done .ai-step-btn {
    background: rgba(16,185,129,0.07);
    border-color: rgba(16,185,129,0.2);
    color: #10b981;
}

/* Done banner */
.ai-all-done {
    background: rgba(16,185,129,0.07);
    border: 1px solid rgba(16,185,129,0.2);
    border-radius: 16px;
    padding: 20px 28px;
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 20px;
}
.ai-all-done-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(16,185,129,0.12);
    border: 1px solid rgba(16,185,129,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #10b981;
    flex-shrink: 0;
}
.ai-all-done-title { font-size:15px;font-weight:700;color:#10b981;margin-bottom:2px }
.ai-all-done-sub   { font-size:13px;color:#64748b }

@media(max-width:900px){.wte-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.wte-grid{grid-template-columns:1fr}.ai-progress-card{flex-direction:column}.ai-step-card{flex-wrap:wrap}.ai-step-right{width:100%;justify-content:flex-end}}
</style>

<div class="ai-page">

    {{-- Top bar --}}
    <div class="ai-topbar">
        <div class="ai-title">Action Items</div>
        <div class="ai-sub">Complete these steps to get the most out of your Vyralabs experience.</div>
    </div>

    @php
        $user = auth()->user();

        $steps = [
            [
                'key'       => 'kit_activated',
                'icon'      => 'fa-solid fa-box-open',
                'emoji'     => '📦',
                'color'     => '#22d3ee',
                'bg'        => 'rgba(34,211,238,0.1)',
                'border'    => 'rgba(34,211,238,0.15)',
                'title'     => 'Activate your kit',
                'desc'      => 'Register your home test kit to get started. Your kit ID can be found on the label inside your box.',
                'time'      => '2 minutes',
                'btn_label' => 'Activate',
                'btn_url'   => Route::has('user.kits.index') ? route('user.kits.index') : '#',
                'done_label'=> 'Kit activated',
                'done'      => optional($user->kits())->exists() ?? false,
            ],
            [
                'key'       => 'view_instructions',
                'icon'      => 'fa-solid fa-play-circle',
                'emoji'     => '▶️',
                'color'     => '#a78bfa',
                'bg'        => 'rgba(167,139,250,0.1)',
                'border'    => 'rgba(167,139,250,0.15)',
                'title'     => 'View instructions',
                'desc'      => 'Watch a short video to see how to use your lab kit correctly before collecting your sample.',
                'time'      => '3 minutes',
                'btn_label' => 'View',
                'btn_url'   => Route::has('user.actionitem.instruction') ? route('user.actionitem.instruction') : '#',
                'done_label'=> 'Instructions viewed',
                'done' => (bool) $user->action_item_viewed,
            ],
            [
                'key'       => 'health_profile',
                'icon'      => 'fa-solid fa-clipboard-list',
                'emoji'     => '📋',
                'color'     => '#10b981',
                'bg'        => 'rgba(16,185,129,0.1)',
                'border'    => 'rgba(16,185,129,0.15)',
                'title'     => 'Complete your health profile',
                'desc'      => 'Answer a few questions to help us interpret your results more precisely.',
                'time'      => '5 minutes',
                'btn_label' => 'Start now',
                'btn_url'   => Route::has('user.actionitem.helthprofile') ? route('user.actionitem.helthprofile') : '#',
                'done_label'=> 'Profile completed',
                'done'      => !empty($user->health_profile),
            ],
            [
                'key'       => 'kit_questionnaire',
                'icon'      => 'fa-solid fa-list-check',
                'emoji'     => '✅',
                'color'     => '#f59e0b',
                'bg'        => 'rgba(245,158,11,0.1)',
                'border'    => 'rgba(245,158,11,0.15)',
                'title'     => 'Complete your kit questionnaire',
                'desc'      => 'Answer a few questions specific to your kit to help us interpret your results more precisely.',
                'time'      => '5 minutes',
                'btn_label' => 'Start now',
                'btn_url'   => Route::has('user.actionitem.questionnaire') ? route('user.actionitem.questionnaire') : '#',
                'done_label'=> 'Questionnaire done',
                'done'      => !empty($user->kit_questionnaire),
            ],
            [
                'key'       => 'pickup_scheduled',
                'icon'      => 'fa-solid fa-truck-ramp-box',
                'emoji'     => '🚚',
                'color'     => '#06b6d4',
                'bg'        => 'rgba(6,182,212,0.1)',
                'border'    => 'rgba(6,182,212,0.15)',
                'title'     => 'Schedule a pickup',
                'desc'      => 'Book a courier to collect your sample at your preferred time slot.',
                'time'      => '2 minutes',
                'btn_label' => 'Schedule',
                'btn_url'   => Route::has('user.pickup.index') ? route('user.pickup.index') : '#',
                'done_label'=> 'Pickup scheduled',
                'done'      => optional($user->pickupRequests())->whereIn('status',['scheduled','collected'])->exists() ?? false,
            ],
        ];

        $totalSteps     = count($steps);
        $completedSteps = collect($steps)->where('done', true)->count();
        $progressPct    = $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;
    @endphp

    {{-- Progress overview --}}
    <div class="ai-progress-card">
        <div class="ai-progress-left">
            <div class="ai-progress-title">
                @if($completedSteps === $totalSteps)
                    🎉 All steps completed!
                @elseif($completedSteps === 0)
                    Let's get you started
                @else
                    You're making great progress
                @endif
            </div>
            <div class="ai-progress-sub">
                @if($completedSteps === $totalSteps)
                    Your sample is on its way to the lab. Results will be ready within 3–5 business days.
                @else
                    Complete all {{ $totalSteps }} steps to get your results processed quickly.
                @endif
            </div>
            <div class="ai-prog-bar-bg">
                <div class="ai-prog-bar-fill" style="width:{{ $progressPct }}%"></div>
            </div>
            <div class="ai-prog-meta">
                <span class="ai-prog-label">{{ $completedSteps }} of {{ $totalSteps }} steps completed</span>
                <span class="ai-prog-pct">{{ $progressPct }}%</span>
            </div>
        </div>
        <div class="ai-counter">
            <div class="ai-counter-num">{{ $completedSteps }}<span style="font-size:28px;opacity:.4">/{{ $totalSteps }}</span></div>
            <div class="ai-counter-label">steps done</div>
        </div>
    </div>

    {{-- All done banner --}}
    @if($completedSteps === $totalSteps)
    <div class="ai-all-done">
        <div class="ai-all-done-icon"><i class="fa-solid fa-circle-check"></i></div>
        <div>
            <div class="ai-all-done-title">All action items completed!</div>
            <div class="ai-all-done-sub">Your kit has been collected and sent to the lab. You'll be notified when your results are ready.</div>
        </div>
    </div>
    @endif

    {{-- What to expect --}}
    <div class="section-label"><i class="fa-regular fa-square-check"></i> What to expect</div>

    <div class="wte-grid">
        @php
        $wteSteps = [
            [
                'num'   => 1,
                'image' => 'images/action1.png',
                'title' => 'Receive your kit',
                'desc'  => 'You will receive your at-home kit typically within <strong>3–4 business days</strong> after ordering. You will receive a notification once your kit has shipped.',
                'link'  => null,
            ],
            [
                'num'   => 2,
                'image' => 'images/action2.png',
                'title' => 'Complete at home',
                'desc'  => 'Your lab kit will arrive with complete instructions. Watch the video at any time to see how it works.',
                'link'  => ['label' => 'View Biomarkers', 'url' => Route::has('user.reports.index') ? route('user.reports.index') : '#'],
            ],
            [
                'num'   => 3,
                'image' => 'images/action3.png',
                'title' => 'Ship your sample',
                'desc'  => 'Your lab kit will include everything you need to easily ship your sample to be processed.',
                'link'  => null,
            ],
            [
                'num'   => 4,
                'image' => 'images/action4.png',
                'title' => 'View your results',
                'desc'  => 'Once you ship the kit back, please allow up to 4 days for results to be posted. You will receive a notification when your results are ready.',
                'link'  => null,
            ],
        ];
        @endphp

        @foreach($wteSteps as $wte)
        <div class="wte-card">
            <div class="wte-img-placeholder" style="background:rgba(255,255,255,0.02); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                @if(!empty($wte['image']))
                    <img src="{{ asset($wte['image']) }}" alt="{{ $wte['title'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <span style="font-size: 42px;">📦</span>
                @endif
            </div>
            <div class="wte-body">
                <div class="wte-num">{{ $wte['num'] }}</div>
                <div class="wte-title">{{ $wte['title'] }}</div>
                <div class="wte-desc">{!! $wte['desc'] !!}</div>
                @if($wte['link'])
                    <a href="{{ $wte['link']['url'] }}" class="wte-link">
                        {{ $wte['link']['label'] }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px"></i>
                    </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Action steps --}}
    <div class="section-label"><i class="fa-solid fa-bolt"></i> Your action steps</div>

    <div class="ai-steps-list">
        @foreach($steps as $step)
        <div class="ai-step-card {{ $step['done'] ? 'done' : '' }}">

            {{-- Checkbox --}}
            <div class="ai-step-check">
                @if($step['done'])<i class="fa-solid fa-check"></i>@endif
            </div>

            {{-- Icon --}}
            <div class="ai-step-icon-wrap"
                 style="background:{{ $step['bg'] }};border:1px solid {{ $step['border'] }};color:{{ $step['color'] }}">
                <i class="{{ $step['icon'] }}"></i>
            </div>

            {{-- Content --}}
            <div class="ai-step-content">
                <div class="ai-step-title-main">{{ $step['title'] }}</div>
                <div class="ai-step-desc-main">{{ $step['desc'] }}</div>
            </div>

            {{-- Right --}}
            <div class="ai-step-right">
                @if(!$step['done'] && isset($step['time']))
                <div class="ai-step-time">
                    <i class="fa-regular fa-clock"></i> ~{{ $step['time'] }}
                </div>
                @endif

                @if($step['done'])
                    <a href="{{ $step['btn_url'] }}" class="ai-step-btn">
                        <i class="fa-solid fa-check"></i> {{ $step['done_label'] }}
                    </a>
                @else
                    <a href="{{ $step['btn_url'] }}" class="ai-step-btn btn-primary-g">
                        {{ $step['btn_label'] }} <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @endif
            </div>

        </div>
        @endforeach
    </div>

</div>

@endsection