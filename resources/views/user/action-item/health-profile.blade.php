@extends('layouts.admin')

@section('title', 'Health Profile')

@section('content')

<style>
.hp-page { padding: 4px 0 40px }

.hp-back { display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:#64748b;text-decoration:none;margin-bottom:22px;transition:color .2s }
.hp-back:hover { color:#f1f5f9 }

.hp-header { margin-bottom:28px }
.hp-title  { font-family:'Syne',sans-serif;font-size:24px;font-weight:800;color:#fff;margin-bottom:4px }
.hp-sub    { font-size:13px;color:#64748b }

/* Progress bar */
.hp-prog-wrap { margin-bottom:28px }
.hp-prog-meta { display:flex;justify-content:space-between;margin-bottom:8px }
.hp-prog-label { font-size:12px;color:#64748b }
.hp-prog-pct   { font-size:12px;font-weight:700;color:#22d3ee }
.hp-prog-bg { height:6px;border-radius:99px;background:rgba(255,255,255,0.07);overflow:hidden }
.hp-prog-fill { height:100%;border-radius:99px;background:linear-gradient(90deg,#10b981,#22d3ee);transition:width .4s ease }

/* Form card */
.hp-card {
    background: var(--surface);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 20px;
    padding: 28px 32px;
    margin-bottom: 16px;
}
.hp-q-num  { font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#475569;margin-bottom:8px }
.hp-q-text { font-size:16px;font-weight:700;color:#f1f5f9;margin-bottom:6px }
.hp-q-hint { font-size:12.5px;color:#64748b;margin-bottom:20px }

/* Options grid */
.opt-grid { display:grid;grid-template-columns:repeat(2,1fr);gap:10px }
.opt-grid.cols-1 { grid-template-columns:1fr }
.opt-grid.cols-3 { grid-template-columns:repeat(3,1fr) }

.opt-item { position:relative }
.opt-item input[type=radio],
.opt-item input[type=checkbox] { position:absolute;opacity:0;width:0;height:0 }

.opt-label {
    display:flex;align-items:center;gap:12px;
    padding:13px 16px;
    border-radius:12px;
    border:1px solid rgba(255,255,255,0.08);
    background:rgba(255,255,255,0.03);
    cursor:pointer;
    transition:all .2s;
    font-size:13.5px;color:#cbd5e1;font-weight:500;
}
.opt-label:hover { border-color:rgba(34,211,238,0.3);background:rgba(34,211,238,0.04) }
.opt-dot {
    width:18px;height:18px;border-radius:50%;
    border:2px solid rgba(255,255,255,0.15);
    flex-shrink:0;display:flex;align-items:center;justify-content:center;
    transition:all .2s;
}
.opt-dot-sq { border-radius:5px }
.opt-dot-inner { width:8px;height:8px;border-radius:50%;background:#22d3ee;transform:scale(0);transition:transform .15s }
.opt-dot-inner-sq { border-radius:3px }

input[type=radio]:checked + .opt-label,
input[type=checkbox]:checked + .opt-label {
    border-color:rgba(34,211,238,0.4);
    background:rgba(34,211,238,0.06);
    color:#f1f5f9;
}
input[type=radio]:checked + .opt-label .opt-dot,
input[type=checkbox]:checked + .opt-label .opt-dot {
    border-color:#22d3ee;background:rgba(34,211,238,0.1);
}
input[type=radio]:checked + .opt-label .opt-dot-inner,
input[type=checkbox]:checked + .opt-label .opt-dot-inner { transform:scale(1) }

/* Range input */
.range-wrap { padding:4px 0 }
.range-input {
    -webkit-appearance:none;appearance:none;
    width:100%;height:6px;border-radius:99px;
    background:rgba(255,255,255,0.07);outline:none;cursor:pointer;
}
.range-input::-webkit-slider-thumb {
    -webkit-appearance:none;appearance:none;
    width:20px;height:20px;border-radius:50%;
    background:linear-gradient(135deg,#10b981,#22d3ee);
    cursor:pointer;border:2px solid #0f172a;
    box-shadow:0 2px 8px rgba(16,185,129,0.4);
}
.range-labels { display:flex;justify-content:space-between;margin-top:8px }
.range-labels span { font-size:11px;color:#64748b }
.range-val {
    text-align:center;margin-top:10px;
    font-size:22px;font-weight:900;font-family:'Syne',sans-serif;
    color:#22d3ee;line-height:1;
}
.range-val-label { font-size:11px;color:#64748b;margin-top:2px }

/* Text input */
.hp-text-input {
    width:100%;padding:13px 16px;
    background:rgba(255,255,255,0.04);
    border:1px solid rgba(255,255,255,0.1);
    border-radius:12px;color:#f1f5f9;
    font-size:14px;font-family:'Inter',sans-serif;
    outline:none;transition:border-color .2s;
}
.hp-text-input:focus { border-color:rgba(34,211,238,0.4) }
.hp-text-input option { background:#1e293b;color:#f1f5f9 }

/* Footer */
.hp-footer {
    background:var(--surface);border:1px solid rgba(255,255,255,0.06);
    border-radius:16px;padding:20px 24px;
    display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;
}
.btn-submit-hp {
    padding:11px 28px;border-radius:10px;border:none;
    background:linear-gradient(135deg,#10b981,#06b6d4);
    color:#000;font-size:13px;font-weight:800;cursor:pointer;
    display:inline-flex;align-items:center;gap:8px;transition:all .2s;
}
.btn-submit-hp:hover { transform:translateY(-1px);box-shadow:0 6px 20px rgba(16,185,129,0.3) }
.btn-back-hp {
    padding:11px 22px;border-radius:10px;
    border:1px solid rgba(255,255,255,0.1);background:transparent;
    color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer;
    text-decoration:none;display:inline-flex;align-items:center;gap:8px;transition:all .2s;
}
.btn-back-hp:hover { background:rgba(255,255,255,0.05);color:#f1f5f9 }

@media(max-width:600px){.opt-grid{grid-template-columns:1fr}.opt-grid.cols-3{grid-template-columns:1fr 1fr}}
</style>

<div class="hp-page">

    <a href="{{ route('user.actionitem.index') }}" class="hp-back">
        <i class="fa-solid fa-arrow-left"></i> {{ __('messages.back_to_action_items') }}
    </a>

    <div class="hp-header">
        <div class="hp-title">{{ __('messages.complete_your_health_profile') }}</div>
        <div class="hp-sub">{{ __('messages.sub_header') }}</div>
    </div>

    {{-- Progress --}}
    <div class="hp-prog-wrap">
        <div class="hp-prog-meta">
            <span class="hp-prog-label" id="progLabel">{{ __('messages.question_x_of_y', ['current' => 1, 'total' => 5]) }}</span>
            <span class="hp-prog-pct" id="progPct">0%</span>
        </div>
        <div class="hp-prog-bg">
            <div class="hp-prog-fill" id="progFill" style="width:0%"></div>
        </div>
    </div>

    @if(session('success'))
    <div style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.2);color:#10b981;padding:12px 18px;border-radius:10px;margin-bottom:16px;font-size:13px;display:flex;align-items:center;gap:8px">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('user.health-profile.store') }}" method="POST" id="healthForm">
        @csrf

        {{-- Q1: Age --}}
        <div class="hp-card" id="q1">
            <div class="hp-q-num">{{ __('messages.question_x_of_y', ['current' => 1, 'total' => 5]) }}</div>
            <div class="hp-q-text">{{ __('messages.q1_text') }}</div>
            <div class="hp-q-hint">{{ __('messages.q1_hint') }}</div>
            <div class="range-wrap">
                <input type="range" name="age" id="ageRange" class="range-input"
                       min="18" max="90" value="{{ old('age', auth()->user()->health_profile['age'] ?? 30) }}"
                       oninput="updateRange('ageVal','ageUnit',this.value,'{{ __('messages.years_old') }}')">
                <div class="range-labels"><span>18</span><span>90</span></div>
                <div class="range-val" id="ageVal">{{ old('age', auth()->user()->health_profile['age'] ?? 30) }}</div>
                <div class="range-val-label" id="ageUnit">{{ __('messages.years_old') }}</div>
            </div>
        </div>

        {{-- Q2: Sex --}}
        <div class="hp-card" id="q2">
            <div class="hp-q-num">{{ __('messages.question_x_of_y', ['current' => 2, 'total' => 5]) }}</div>
            <div class="hp-q-text">{{ __('messages.q2_text') }}</div>
            <div class="hp-q-hint">{{ __('messages.q2_hint') }}</div>
            <div class="opt-grid cols-3">
                @php $sexOptions = [
                    'Male' => 'messages.sex_male',
                    'Female' => 'messages.sex_female',
                    'Prefer not to say' => 'messages.sex_prefer_not_to_say'
                ] @endphp
                @foreach($sexOptions as $val => $langKey)
                <div class="opt-item">
                    <input type="radio" name="biological_sex" id="sex_{{ $loop->index }}" value="{{ $val }}"
                           {{ old('biological_sex', auth()->user()->health_profile['biological_sex'] ?? '') === $val ? 'checked' : '' }}>
                    <label for="sex_{{ $loop->index }}" class="opt-label">
                        <span class="opt-dot"><span class="opt-dot-inner"></span></span>
                        {{ __($langKey) }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Q3: Activity level --}}
        <div class="hp-card" id="q3">
            <div class="hp-q-num">{{ __('messages.question_x_of_y', ['current' => 3, 'total' => 5]) }}</div>
            <div class="hp-q-text">{{ __('messages.q3_text') }}</div>
            <div class="hp-q-hint">{{ __('messages.q3_hint') }}</div>
            <div class="opt-grid">
                @php $activityOptions = [
                    ['val'=>'sedentary',   'label'=>'messages.act_sedentary',   'desc'=>'messages.act_sedentary_desc'],
                    ['val'=>'light',       'label'=>'messages.act_light',       'desc'=>'messages.act_light_desc'],
                    ['val'=>'moderate',    'label'=>'messages.act_moderate',    'desc'=>'messages.act_moderate_desc'],
                    ['val'=>'very_active', 'label'=>'messages.act_very_active', 'desc'=>'messages.act_very_active_desc'],
                ] @endphp
                @foreach($activityOptions as $opt)
                <div class="opt-item">
                    <input type="radio" name="activity_level" id="act_{{ $loop->index }}" value="{{ $opt['val'] }}"
                           {{ old('activity_level', auth()->user()->health_profile['activity_level'] ?? '') === $opt['val'] ? 'checked' : '' }}>
                    <label for="act_{{ $loop->index }}" class="opt-label" style="flex-direction:column;align-items:flex-start;gap:4px">
                        <div style="display:flex;align-items:center;gap:10px;width:100%">
                            <span class="opt-dot"><span class="opt-dot-inner"></span></span>
                            <span style="font-weight:600">{{ __($opt['label']) }}</span>
                        </div>
                        <span style="font-size:11.5px;color:#64748b;padding-left:30px">{{ __($opt['desc']) }}</span>
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Q4: Health goals --}}
        <div class="hp-card" id="q4">
            <div class="hp-q-num">{{ __('messages.question_x_of_y', ['current' => 4, 'total' => 5]) }}</div>
            <div class="hp-q-text">{{ __('messages.q4_text') }}</div>
            <div class="hp-q-hint">{{ __('messages.q4_hint') }}</div>
            <div class="opt-grid">
                @php $goals = [
                    ['val'=>'energy',       'label'=>'messages.goal_energy', 'icon'=>'⚡'],
                    ['val'=>'heart',        'label'=>'messages.goal_heart',  'icon'=>'❤️'],
                    ['val'=>'weight',       'label'=>'messages.goal_weight', 'icon'=>'⚖️'],
                    ['val'=>'hormones',     'label'=>'messages.goal_hormones','icon'=>'🔬'],
                    ['val'=>'longevity',    'label'=>'messages.goal_longevity','icon'=>'🌿'],
                    ['val'=>'performance',  'label'=>'messages.goal_performance','icon'=>'🏃'],
                ] @endphp
                @foreach($goals as $g)
                <div class="opt-item">
                    <input type="checkbox" name="health_goals[]" id="goal_{{ $loop->index }}" value="{{ $g['val'] }}"
                           {{ in_array($g['val'], old('health_goals', auth()->user()->health_profile['health_goals'] ?? [])) ? 'checked' : '' }}>
                    <label for="goal_{{ $loop->index }}" class="opt-label">
                        <span class="opt-dot opt-dot-sq"><span class="opt-dot-inner opt-dot-inner-sq"></span></span>
                        <span>{{ $g['icon'] }} {{ __($g['label']) }}</span>
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Q5: Medical conditions --}}
        <div class="hp-card" id="q5">
            <div class="hp-q-num">{{ __('messages.question_x_of_y', ['current' => 5, 'total' => 5]) }}</div>
            <div class="hp-q-text">{{ __('messages.q5_text') }}</div>
            <div class="hp-q-hint">{{ __('messages.q5_hint') }}</div>
            <div class="opt-grid">
                @php $conditions = [
                    ['val'=>'diabetes',     'label'=>'messages.cond_diabetes'],
                    ['val'=>'hypertension', 'label'=>'messages.cond_hypertension'],
                    ['val'=>'thyroid',      'label'=>'messages.cond_thyroid'],
                    ['val'=>'cholesterol',  'label'=>'messages.cond_cholesterol'],
                    ['val'=>'none',         'label'=>'messages.cond_none'],
                    ['val'=>'prefer_not',   'label'=>'messages.cond_prefer_not'],
                ] @endphp
                @foreach($conditions as $c)
                <div class="opt-item">
                    <input type="checkbox" name="medical_conditions[]" id="cond_{{ $loop->index }}" value="{{ $c['val'] }}"
                           {{ in_array($c['val'], old('medical_conditions', auth()->user()->health_profile['medical_conditions'] ?? [])) ? 'checked' : '' }}>
                    <label for="cond_{{ $loop->index }}" class="opt-label">
                        <span class="opt-dot opt-dot-sq"><span class="opt-dot-inner opt-dot-inner-sq"></span></span>
                        {{ __($c['label']) }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Footer --}}
        <div class="hp-footer">
            <div style="font-size:13px;color:#64748b">
                <i class="fa-solid fa-shield-halved" style="color:#22d3ee;margin-right:6px"></i>
                {{ __('messages.encryption_notice') }}
            </div>
            <div style="display:flex;gap:10px">
                <a href="{{ route('user.actionitem.index') }}" class="btn-back-hp">
                    <i class="fa-solid fa-arrow-left"></i> {{ __('messages.back') }}
                </a>
                <button type="submit" class="btn-submit-hp">
                    <i class="fa-solid fa-check"></i> {{ __('messages.save_profile') }}
                </button>
            </div>
        </div>

    </form>

</div>

<script>
function updateRange(valId, unitId, value, unit) {
    document.getElementById(valId).textContent = value;
    if(document.getElementById(unitId)) document.getElementById(unitId).textContent = unit;
    updateProgress();
}

function updateProgress() {
    var total = 5;
    var filled = 0;

    if(document.getElementById('ageRange').value) filled++;
    if(document.querySelector('input[name="biological_sex"]:checked')) filled++;
    if(document.querySelector('input[name="activity_level"]:checked')) filled++;
    if(document.querySelector('input[name="health_goals[]"]:checked')) filled++;
    if(document.querySelector('input[name="medical_conditions[]"]:checked')) filled++;

    var pct = Math.round((filled / total) * 100);
    document.getElementById('progFill').style.width  = pct + '%';
    document.getElementById('progPct').textContent   = pct + '%';
    document.getElementById('progLabel').textContent = filled + ' of ' + total + ' answered';
}

document.querySelectorAll('input[type=radio], input[type=checkbox]').forEach(function(el) {
    el.addEventListener('change', updateProgress);
});

// Init
updateProgress();
</script>

@endsection