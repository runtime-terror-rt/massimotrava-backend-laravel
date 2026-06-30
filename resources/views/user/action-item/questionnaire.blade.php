@extends('layouts.admin')

@section('title', 'Kit Questionnaire')

@section('content')

<style>
.kq-page { padding: 4px 0 40px }

.kq-back { display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:#64748b;text-decoration:none;margin-bottom:22px;transition:color .2s }
.kq-back:hover { color:#f1f5f9 }

.kq-title { font-family:'Syne',sans-serif;font-size:24px;font-weight:800;color:#fff;margin-bottom:4px }
.kq-sub   { font-size:13px;color:#64748b;margin-bottom:28px }

/* Progress */
.kq-prog-wrap { margin-bottom:28px }
.kq-prog-meta { display:flex;justify-content:space-between;margin-bottom:8px }
.kq-prog-label { font-size:12px;color:#64748b }
.kq-prog-pct   { font-size:12px;font-weight:700;color:#a78bfa }
.kq-prog-bg { height:6px;border-radius:99px;background:rgba(255,255,255,0.07);overflow:hidden }
.kq-prog-fill { height:100%;border-radius:99px;background:linear-gradient(90deg,#a78bfa,#06b6d4);transition:width .4s ease }

/* Form card */
.kq-card {
    background:var(--surface);
    border:1px solid rgba(255,255,255,0.06);
    border-radius:20px;padding:28px 32px;margin-bottom:16px;
}
.kq-q-num  { font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#475569;margin-bottom:8px }
.kq-q-text { font-size:16px;font-weight:700;color:#f1f5f9;margin-bottom:6px }
.kq-q-hint { font-size:12.5px;color:#64748b;margin-bottom:20px }

/* Options */
.kq-opt-grid { display:grid;grid-template-columns:repeat(2,1fr);gap:10px }
.kq-opt-grid.cols-1 { grid-template-columns:1fr }

.kq-opt-item { position:relative }
.kq-opt-item input { position:absolute;opacity:0;width:0;height:0 }
.kq-opt-label {
    display:flex;align-items:center;gap:12px;
    padding:13px 16px;border-radius:12px;
    border:1px solid rgba(255,255,255,0.08);
    background:rgba(255,255,255,0.03);
    cursor:pointer;transition:all .2s;
    font-size:13.5px;color:#cbd5e1;font-weight:500;
}
.kq-opt-label:hover { border-color:rgba(167,139,250,0.3);background:rgba(167,139,250,0.04) }
.kq-opt-dot {
    width:18px;height:18px;border-radius:50%;
    border:2px solid rgba(255,255,255,0.15);
    flex-shrink:0;display:flex;align-items:center;justify-content:center;
    transition:all .2s;
}
.kq-opt-dot-sq { border-radius:5px }
.kq-opt-inner { width:8px;height:8px;border-radius:50%;background:#a78bfa;transform:scale(0);transition:transform .15s }
.kq-opt-inner-sq { border-radius:3px }

input:checked + .kq-opt-label { border-color:rgba(167,139,250,0.4);background:rgba(167,139,250,0.06);color:#f1f5f9 }
input:checked + .kq-opt-label .kq-opt-dot { border-color:#a78bfa;background:rgba(167,139,250,0.1) }
input:checked + .kq-opt-label .kq-opt-inner { transform:scale(1) }

/* Textarea */
.kq-textarea {
    width:100%;padding:13px 16px;
    background:rgba(255,255,255,0.04);
    border:1px solid rgba(255,255,255,0.1);
    border-radius:12px;color:#f1f5f9;
    font-size:13.5px;font-family:'Inter',sans-serif;
    outline:none;resize:vertical;min-height:100px;
    transition:border-color .2s;line-height:1.6;
}
.kq-textarea:focus { border-color:rgba(167,139,250,0.4) }
.kq-textarea::placeholder { color:#475569 }

/* Footer */
.kq-footer {
    background:var(--surface);border:1px solid rgba(255,255,255,0.06);
    border-radius:16px;padding:20px 24px;
    display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;
}
.btn-submit-kq {
    padding:11px 28px;border-radius:10px;border:none;
    background:linear-gradient(135deg,#a78bfa,#06b6d4);
    color:#000;font-size:13px;font-weight:800;cursor:pointer;
    display:inline-flex;align-items:center;gap:8px;transition:all .2s;
}
.btn-submit-kq:hover { transform:translateY(-1px);box-shadow:0 6px 20px rgba(167,139,250,0.3) }
.btn-back-kq {
    padding:11px 22px;border-radius:10px;
    border:1px solid rgba(255,255,255,0.1);background:transparent;
    color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer;
    text-decoration:none;display:inline-flex;align-items:center;gap:8px;transition:all .2s;
}
.btn-back-kq:hover { background:rgba(255,255,255,0.05);color:#f1f5f9 }

@media(max-width:600px){.kq-opt-grid{grid-template-columns:1fr}}
</style>

<div class="kq-page">

    <a href="{{ route('user.actionitem.index') }}" class="kq-back">
        <i class="fa-solid fa-arrow-left"></i> {{ __('messages.back_to_action_items') }}
    </a>

    <div class="kq-title">{{ __('messages.kq_title') }}</div>
    <div class="kq-sub">{{ __('messages.kq_sub') }}</div>

    {{-- Progress --}}
    <div class="kq-prog-wrap">
        <div class="kq-prog-meta">
            <span class="kq-prog-label" id="kqProgLabel">0 of 5 answered</span>
            <span class="kq-prog-pct" id="kqProgPct">0%</span>
        </div>
        <div class="kq-prog-bg">
            <div class="kq-prog-fill" id="kqProgFill" style="width:0%"></div>
        </div>
    </div>

    @if(session('success'))
    <div style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.2);color:#10b981;padding:12px 18px;border-radius:10px;margin-bottom:16px;font-size:13px;display:flex;align-items:center;gap:8px">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('user.kit-questionnaire.store') }}" method="POST" id="kitForm">
        @csrf

        {{-- Q1: Fasting --}}
        <div class="kq-card">
            <div class="kq-q-num">{{ __('messages.kq_q1_num') }}</div>
            <div class="kq-q-text">{{ __('messages.kq_q1_text') }}</div>
            <div class="kq-q-hint">{{ __('messages.kq_q1_hint') }}</div>
            <div class="kq-opt-grid">
                @foreach([
                    'messages.kq_q1_opt_yes' => 'yes', 
                    'messages.kq_q1_opt_no' => 'no', 
                    'messages.kq_q1_opt_partial' => 'partial', 
                    'messages.kq_q1_opt_unsure' => 'unsure'
                ] as $labelKey => $val)
                <div class="kq-opt-item">
                    <input type="radio" name="fasting_status" id="fast_{{ $loop->index }}" value="{{ $val }}"
                           {{ old('fasting_status', auth()->user()->kit_questionnaire['fasting_status'] ?? '') === $val ? 'checked' : '' }}>
                    <label for="fast_{{ $loop->index }}" class="kq-opt-label">
                        <span class="kq-opt-dot"><span class="kq-opt-inner"></span></span>
                        {{ __($labelKey) }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Q2: Collection time --}}
        <div class="kq-card">
            <div class="kq-q-num">{{ __('messages.kq_q2_num') }}</div>
            <div class="kq-q-text">{{ __('messages.kq_q2_text') }}</div>
            <div class="kq-q-hint">{{ __('messages.kq_q2_hint') }}</div>
            <div class="kq-opt-grid">
                @foreach([
                    'messages.kq_q2_opt_early_morning' => 'early_morning', 
                    'messages.kq_q2_opt_morning' => 'morning', 
                    'messages.kq_q2_opt_afternoon' => 'afternoon', 
                    'messages.kq_q2_opt_evening' => 'evening'
                ] as $labelKey => $val)
                <div class="kq-opt-item">
                    <input type="radio" name="collection_time" id="time_{{ $loop->index }}" value="{{ $val }}"
                           {{ old('collection_time', auth()->user()->kit_questionnaire['collection_time'] ?? '') === $val ? 'checked' : '' }}>
                    <label for="time_{{ $loop->index }}" class="kq-opt-label">
                        <span class="kq-opt-dot"><span class="kq-opt-inner"></span></span>
                        {{ __($labelKey) }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Q3: Medications --}}
        <div class="kq-card">
            <div class="kq-q-num">{{ __('messages.kq_q3_num') }}</div>
            <div class="kq-q-text">{{ __('messages.kq_q3_text') }}</div>
            <div class="kq-q-hint">{{ __('messages.kq_q3_hint') }}</div>
            <div class="kq-opt-grid">
                @php $meds = [
                    'messages.kq_q3_opt_bp' => 'bp_meds',
                    'messages.kq_q3_opt_statins' => 'statins',
                    'messages.kq_q3_opt_thyroid' => 'thyroid_meds',
                    'messages.kq_q3_opt_hormones' => 'hormones',
                    'messages.kq_q3_opt_supplements' => 'supplements',
                    'messages.kq_q3_opt_none' => 'none',
                ] @endphp
                @foreach($meds as $labelKey => $val)
                <div class="kq-opt-item">
                    <input type="checkbox" name="medications[]" id="med_{{ $loop->index }}" value="{{ $val }}"
                           {{ in_array($val, old('medications', auth()->user()->kit_questionnaire['medications'] ?? [])) ? 'checked' : '' }}>
                    <label for="med_{{ $loop->index }}" class="kq-opt-label">
                        <span class="kq-opt-dot kq-opt-dot-sq"><span class="kq-opt-inner kq-opt-inner-sq"></span></span>
                        {{ __($labelKey) }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Q4: Recent illness --}}
        <div class="kq-card">
            <div class="kq-q-num">{{ __('messages.kq_q4_num') }}</div>
            <div class="kq-q-text">{{ __('messages.kq_q4_text') }}</div>
            <div class="kq-q-hint">{{ __('messages.kq_q4_hint') }}</div>
            <div class="kq-opt-grid">
                @foreach([
                    'messages.kq_q4_opt_yes' => 'yes', 
                    'messages.kq_q4_opt_no' => 'no', 
                    'messages.kq_q4_opt_mild' => 'mild', 
                    'messages.kq_q4_opt_current' => 'current'
                ] as $labelKey => $val)
                <div class="kq-opt-item">
                    <input type="radio" name="recent_illness" id="ill_{{ $loop->index }}" value="{{ $val }}"
                           {{ old('recent_illness', auth()->user()->kit_questionnaire['recent_illness'] ?? '') === $val ? 'checked' : '' }}>
                    <label for="ill_{{ $loop->index }}" class="kq-opt-label">
                        <span class="kq-opt-dot"><span class="kq-opt-inner"></span></span>
                        {{ __($labelKey) }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Q5: Additional notes --}}
        <div class="kq-card">
            <div class="kq-q-num">{{ __('messages.kq_q5_num') }}</div>
            <div class="kq-q-text">{{ __('messages.kq_q5_text') }}</div>
            <div class="kq-q-hint">{{ __('messages.kq_q5_hint') }}</div>
            <textarea name="additional_notes" class="kq-textarea"
                      placeholder="{{ __('messages.kq_q5_placeholder') }}"
                      oninput="kqUpdateProgress()">{{ old('additional_notes', auth()->user()->kit_questionnaire['additional_notes'] ?? '') }}</textarea>
        </div>

        {{-- Footer --}}
        <div class="kq-footer">
            <div style="font-size:13px;color:#64748b">
                <i class="fa-solid fa-lock" style="color:#a78bfa;margin-right:6px"></i>
                {{ __('messages.kq_footer_confidential') }}
            </div>
            <div style="display:flex;gap:10px">
                <a href="{{ route('user.actionitem.index') }}" class="btn-back-kq">
                    <i class="fa-solid fa-arrow-left"></i> {{ __('messages.back') }}
                </a>
                <button type="submit" class="btn-submit-kq">
                    <i class="fa-solid fa-check"></i> {{ __('messages.submit_questionnaire') }}
                </button>
            </div>
        </div>

    </form>

</div>

<script>
function kqUpdateProgress() {
    var filled = 0;
    if(document.querySelector('input[name="fasting_status"]:checked'))  filled++;
    if(document.querySelector('input[name="collection_time"]:checked'))  filled++;
    if(document.querySelector('input[name="medications[]"]:checked'))    filled++;
    if(document.querySelector('input[name="recent_illness"]:checked'))   filled++;
    var notes = document.querySelector('textarea[name="additional_notes"]');
    if(notes && notes.value.trim().length > 0) filled++;

    var pct = Math.round((filled / 5) * 100);
    document.getElementById('kqProgFill').style.width  = pct + '%';
    document.getElementById('kqProgPct').textContent   = pct + '%';
    document.getElementById('kqProgLabel').textContent = filled + ' of 5 answered';
}

document.querySelectorAll('input[type=radio], input[type=checkbox]').forEach(function(el){
    el.addEventListener('change', kqUpdateProgress);
});
kqUpdateProgress();
</script>

@endsection