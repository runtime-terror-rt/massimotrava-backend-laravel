@extends('layouts.admin')

@section('page_title_key', 'action_items')

@section('content')

<style>
.ai-page { padding: 4px 0 40px }

/* Top bar */
.ai-topbar { margin-bottom: 28px }
.ai-title { font-family:'Syne',sans-serif;font-size:24px;font-weight:800;letter-spacing:-0.03em;color:var(--text) }
.ai-sub   { font-size:13px;color:var(--text-muted);margin-top:4px }

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
    color: var(--text);
    margin-bottom: 6px;
}
.ai-progress-sub { font-size:13px;color:var(--text-muted);margin-bottom:14px }
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
.ai-prog-label { font-size:12px;color:var(--text-muted) }
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
.ai-counter-label { font-size:12px;color:var(--text-muted);margin-top:4px }

/* What to expect section */
.section-label {
    font-size:11px;
    font-weight:700;
    letter-spacing:.07em;
    text-transform:uppercase;
    color:var(--text-muted);
    margin-bottom:14px;
    display:flex;
    align-items:center;
    gap:8px;
}
.section-label::after {
    content:'';
    flex:1;
    height:1px;
    background:var(--border);
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
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    transition: border-color .2s, transform .2s;
}
.wte-card:hover {
    border-color: var(--accent);
    transform: translateY(-2px);
}
.wte-img {
    width: 100%;
    height: 130px;
    object-fit: cover;
    background: var(--surface-2);
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
    background: var(--surface-2);
    border: 1px solid var(--border);
    font-size: 11px;
    font-weight: 800;
    color: var(--text);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
}
.wte-title { font-size:14px;font-weight:700;color:var(--text);margin-bottom:6px }
.wte-desc  { font-size:12px;color:var(--text-muted);line-height:1.6 }
.wte-link  { font-size:12px;font-weight:600;color:#22d3ee;text-decoration:none;display:inline-flex;align-items:center;gap:5px;margin-top:8px }
.wte-link:hover { color:#67e8f9 }

/* Action steps list */
.ai-steps-list { display:flex;flex-direction:column;gap:12px;margin-bottom:28px }

.ai-step-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 18px 22px;
    display: flex;
    align-items: center;
    gap: 18px;
    transition: border-color .2s, background .2s;
    position: relative;
    overflow: hidden;
}
.ai-step-card:hover { border-color: var(--accent); background: var(--surface-2) }
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
    border: 2px solid var(--border);
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
    color: var(--text);
    margin-bottom: 3px;
    transition: color .2s;
}
.ai-step-card.done .ai-step-title-main {
    color: var(--text-muted);
    text-decoration: line-through;
    text-decoration-color: var(--border);
}
.ai-step-desc-main { font-size:12.5px;color:var(--text-muted);line-height:1.5 }

/* Right side */
.ai-step-right { display:flex;align-items:center;gap:12px;flex-shrink:0 }
.ai-step-time { font-size:11.5px;color:var(--text-muted);display:flex;align-items:center;gap:5px }
.ai-step-time i { font-size:10px }

.ai-step-btn {
    font-size: 12px;
    font-weight: 700;
    padding: 8px 18px;
    border-radius: 9px;
    border: 1px solid var(--border);
    background: var(--surface-2);
    color: var(--text);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all .2s;
    white-space: nowrap;
    cursor: pointer;
}
.ai-step-btn:hover { background: var(--surface); color:var(--text); border-color:var(--accent) }
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
.ai-all-done-sub   { font-size:13px;color:var(--text-muted) }

@media(max-width:900px){.wte-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.wte-grid{grid-template-columns:1fr}.ai-progress-card{flex-direction:column}.ai-step-card{flex-wrap:wrap}.ai-step-right{width:100%;justify-content:flex-end}}
</style>

<div class="ai-page">

    {{-- Top bar --}}
    <div class="ai-topbar">
        <div class="ai-title">{{ __('messages.action_items') }}</div>
        <div class="ai-sub">{{ __('messages.action_items_sub') }}</div>
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
                'title'     => 'messages.step_activate_title',
                'desc'      => 'messages.step_activate_desc',
                'time'      => 'messages.time_2_mins',
                'btn_label' => 'messages.btn_activate',
                'btn_url'   => Route::has('user.kits.index') ? route('user.kits.index') : '#',
                'done_label'=> 'messages.done_kit_activated',
                'done'      => optional($user->kits())->exists() ?? false,
            ],
            [
                'key'       => 'view_instructions',
                'icon'      => 'fa-solid fa-play-circle',
                'emoji'     => '▶️',
                'color'     => '#a78bfa',
                'bg'        => 'rgba(167,139,250,0.1)',
                'border'    => 'rgba(167,139,250,0.15)',
                'title'     => 'messages.step_instructions_title',
                'desc'      => 'messages.step_instructions_desc',
                'time'      => 'messages.time_3_mins',
                'btn_label' => 'messages.btn_view',
                'btn_url'   => Route::has('user.actionitem.instruction') ? route('user.actionitem.instruction') : '#',
                'done_label'=> 'messages.done_instructions_viewed',
                'done'      => (bool) $user->action_item_viewed,
            ],
            [
                'key'       => 'health_profile',
                'icon'      => 'fa-solid fa-clipboard-list',
                'emoji'     => '📋',
                'color'     => '#10b981',
                'bg'        => 'rgba(16,185,129,0.1)',
                'border'    => 'rgba(16,185,129,0.15)',
                'title'     => 'messages.step_profile_title',
                'desc'      => 'messages.step_profile_desc',
                'time'      => 'messages.time_5_mins',
                'btn_label' => 'messages.btn_start_now',
                'btn_url'   => Route::has('user.actionitem.helthprofile') ? route('user.actionitem.helthprofile') : '#',
                'done_label'=> 'messages.done_profile_completed',
                'done'      => !empty($user->health_profile),
            ],
            [
                'key'       => 'kit_questionnaire',
                'icon'      => 'fa-solid fa-list-check',
                'emoji'     => '✅',
                'color'     => '#f59e0b',
                'bg'        => 'rgba(245,158,11,0.1)',
                'border'    => 'rgba(245,158,11,0.15)',
                'title'     => 'messages.step_questionnaire_title',
                'desc'      => 'messages.step_questionnaire_desc',
                'time'      => 'messages.time_5_mins',
                'btn_label' => 'messages.btn_start_now',
                'btn_url'   => Route::has('user.actionitem.questionnaire') ? route('user.actionitem.questionnaire') : '#',
                'done_label'=> 'messages.done_questionnaire_completed',
                'done'      => !empty($user->kit_questionnaire),
            ],
            [
                'key'       => 'pickup_scheduled',
                'icon'      => 'fa-solid fa-truck-ramp-box',
                'emoji'     => '🚚',
                'color'     => '#06b6d4',
                'bg'        => 'rgba(6,182,212,0.1)',
                'border'    => 'rgba(6,182,212,0.15)',
                'title'     => 'messages.step_pickup_title',
                'desc'      => 'messages.step_pickup_desc',
                'time'      => 'messages.time_2_mins',
                'btn_label' => 'messages.btn_schedule',
                'btn_url'   => Route::has('user.pickup.index') ? route('user.pickup.index') : '#',
                'done_label'=> 'messages.done_pickup_scheduled',
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
                    {{ __('messages.progress_all_completed') }}
                @elseif($completedSteps === 0)
                    {{ __('messages.progress_started') }}
                @else
                    {{ __('messages.progress_great') }}
                @endif
            </div>
            <div class="ai-progress-sub">
                @if($completedSteps === $totalSteps)
                    {{ __('messages.progress_all_completed_sub') }}
                @else
                    {{ __('messages.progress_incomplete_sub', ['total' => $totalSteps]) }}
                @endif
            </div>
            <div class="ai-prog-bar-bg">
                <div class="ai-prog-bar-fill" style="width:{{ $progressPct }}%"></div>
            </div>
            <div class="ai-prog-meta">
                <span class="ai-prog-label">{{ __('messages.steps_completed_meta', ['completed' => $completedSteps, 'total' => $totalSteps]) }}</span>
                <span class="ai-prog-pct">{{ $progressPct }}%</span>
            </div>
        </div>
        <div class="ai-counter">
            <div class="ai-counter-num">{{ $completedSteps }}<span style="font-size:28px;opacity:.4">/{{ $totalSteps }}</span></div>
            <div class="ai-counter-label">{{ __('messages.steps_done') }}</div>
        </div>
    </div>

    {{-- All done banner --}}
    @if($completedSteps === $totalSteps)
    <div class="ai-all-done">
        <div class="ai-all-done-icon"><i class="fa-solid fa-circle-check"></i></div>
        <div>
            <div class="ai-all-done-title">{{ __('messages.banner_all_done_title') }}</div>
            <div class="ai-all-done-sub">{{ __('messages.banner_all_done_sub') }}</div>
        </div>
    </div>
    @endif

    {{-- What to expect --}}
    <div class="section-label"><i class="fa-regular fa-square-check"></i> {{ __('messages.what_to_expect') }}</div>

    <div class="wte-grid">
        @php
        $wteSteps = [
            [
                'num'   => 1,
                'image' => 'images/action1.png',
                'title' => 'messages.wte_step1_title',
                'desc'  => 'messages.wte_step1_desc',
                'link'  => null,
            ],
            [
                'num'   => 2,
                'image' => 'images/action2.png',
                'title' => 'messages.wte_step2_title',
                'desc'  => 'messages.wte_step2_desc',
                'link'  => ['label' => 'messages.wte_step2_link', 'url' => Route::has('user.reports.index') ? route('user.reports.index') : '#'],
            ],
            [
                'num'   => 3,
                'image' => 'images/action3.png',
                'title' => 'messages.wte_step3_title',
                'desc'  => 'messages.wte_step3_desc',
                'link'  => null,
            ],
            [
                'num'   => 4,
                'image' => 'images/action4.png',
                'title' => 'messages.wte_step4_title',
                'desc'  => 'messages.wte_step4_desc',
                'link'  => null,
            ],
        ];
        @endphp

        @foreach($wteSteps as $wte)
        <div class="wte-card">
            <div class="wte-img-placeholder" style="background:var(--surface-2); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                @if(!empty($wte['image']))
                    <img src="{{ asset($wte['image']) }}" alt="{{ __($wte['title']) }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <span style="font-size: 42px;">📦</span>
                @endif
            </div>
            <div class="wte-body">
                <div class="wte-num">{{ $wte['num'] }}</div>
                <div class="wte-title">{{ __($wte['title']) }}</div>
                <div class="wte-desc">{!! __($wte['desc']) !!}</div>
                @if($wte['link'])
                    <a href="{{ $wte['link']['url'] }}" class="wte-link">
                        {{ __($wte['link']['label']) }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px"></i>
                    </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Action steps --}}
    <div class="section-label"><i class="fa-solid fa-bolt"></i> {{ __('messages.your_action_steps') }}</div>

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
                <div class="ai-step-title-main">{{ __($step['title']) }}</div>
                <div class="ai-step-desc-main">{{ __($step['desc']) }}</div>
            </div>

            {{-- Right --}}
            <div class="ai-step-right">
                @if($step['done'])
                    <a href="{{ $step['btn_url'] }}" class="ai-step-btn">
                        <i class="fa-solid fa-check"></i> {{ __($step['done_label']) }}
                    </a>
                @else
                    @if(isset($step['time']))
                    <div class="ai-step-time">
                        <i class="fa-regular fa-clock"></i> ~{{ __($step['time']) }}
                    </div>
                    @endif
                    <a href="{{ $step['btn_url'] }}" class="ai-step-btn btn-primary-g">
                        {{ __($step['btn_label']) }} <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @endif
            </div>

        </div>
        @endforeach
    </div>

</div>

@endsection