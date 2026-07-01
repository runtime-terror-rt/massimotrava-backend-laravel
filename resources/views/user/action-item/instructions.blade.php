@extends('layouts.admin')

@section('title', 'Kit Instructions')

@section('content')

<style>
.ins-page { padding: 4px 0 40px }

.ins-back { display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:#64748b;text-decoration:none;margin-bottom:22px;transition:color .2s }
.ins-back:hover { color:#f1f5f9 }

/* Header Centered */
.ins-header { text-align: center; margin-bottom: 32px; }
.ins-title { font-family:'Syne',sans-serif;font-size:24px;font-weight:800;color:#fff;margin-bottom:6px }
.ins-sub   { font-size:13px;color:#64748b;max-width: 600px; margin: 0 auto; }

/* Video card - Centered and Compact */
.video-card {
    background: var(--surface);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 20px;
    overflow: hidden;
    margin: 0 auto 36px auto; /* Centered with auto margins */
    max-width: 760px; /* Reduced width for better layout balance */
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}
.video-wrap {
    position: relative;
    width: 100%;
    padding-top: 52%; /* Optimized aspect ratio */
    background: #0a0f1a;
}
.video-wrap iframe,
.video-wrap video {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    border: none;
}
/* Placeholder if no video */
.video-placeholder {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    background: linear-gradient(135deg, rgba(99,102,241,0.04), rgba(34,211,238,0.03));
    cursor: pointer;
}
.video-play-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg,#10b981,#06b6d4);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #000;
    box-shadow: 0 8px 24px rgba(16,185,129,0.25);
    transition: transform .2s;
}
.video-placeholder:hover .video-play-btn { transform: scale(1.08) }
.video-placeholder-label { font-size:13px;font-weight:600;color:#94a3b8 }

.video-meta {
    padding: 16px 20px;
    border-top: 1px solid rgba(255,255,255,0.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.video-meta-title { font-size:14px;font-weight:700;color:#f1f5f9;line-height:1.4 }
.video-meta-sub   { font-size:12px;color:#64748b;margin-top:2px }
.video-duration {
    font-size:12px;font-weight:600;
    padding:5px 12px;border-radius:20px;
    background:rgba(34,211,238,0.08);
    color:#22d3ee;
    border:1px solid rgba(34,211,238,0.18);
    display:flex;align-items:center;gap:6px;
}

/* Steps grid */
.steps-grid {
    display: grid;
    grid-template-columns: repeat(3,1fr);
    gap: 14px;
    margin-bottom: 24px;
}
.step-card {
    background: var(--surface);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 16px;
    padding: 20px;
    transition: border-color .2s;
}
.step-card:hover { border-color: rgba(255,255,255,0.12) }
.step-num {
    width: 28px; height: 28px;
    border-radius: 8px;
    background: rgba(34,211,238,0.08);
    border: 1px solid rgba(34,211,238,0.2);
    color: #22d3ee;
    font-size: 12px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
}
.step-title { font-size:13.5px;font-weight:700;color:#f1f5f9;margin-bottom:6px }
.step-desc  { font-size:12px;color:#64748b;line-height:1.6 }

/* Done button */
.ins-footer {
    background: var(--surface);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 16px;
    padding: 20px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.ins-footer-text { font-size:13px;color:#64748b }
.ins-footer-text b { color:#f1f5f9 }
.btn-mark-done {
    padding:10px 24px;border-radius:10px;border:none;
    background:linear-gradient(135deg,#10b981,#06b6d4);
    color:#000;font-size:13px;font-weight:800;cursor:pointer;
    display:inline-flex;align-items:center;gap:8px;
    text-decoration:none;transition:all .2s;
}
.btn-mark-done:hover { transform:translateY(-1px);box-shadow:0 6px 20px rgba(16,185,129,0.3);color:#000 }
.btn-next {
    padding:10px 22px;border-radius:10px;
    border:1px solid rgba(255,255,255,0.1);
    background:transparent;color:#f1f5f9;
    font-size:13px;font-weight:600;cursor:pointer;
    display:inline-flex;align-items:center;gap:8px;
    text-decoration:none;transition:all .2s;
}
.btn-next:hover { background:rgba(255,255,255,0.06);color:#fff }

@media(max-width:768px){.steps-grid{grid-template-columns:1fr}}
</style>

<div class="ins-page">

    <a href="{{ route('user.actionitem.index') }}" class="ins-back">
        <i class="fa-solid fa-arrow-left"></i> {{ __('messages.back_to_action_items') }}
    </a>

    <div class="ins-header">
        <div class="ins-title">{{ __('messages.ins_title') }}</div>
        <div class="ins-sub">{{ __('messages.ins_sub') }}</div>
    </div>

    {{-- Video Section --}}
    <div class="video-card">
        <div class="video-wrap">
            <div class="video-placeholder" id="videoPlaceholder">
                <div class="video-play-btn"><i class="fa-solid fa-play"></i></div>
                <div class="video-placeholder-label">{{ __('messages.video_placeholder_label') }}</div>
            </div>
        </div>
        <div class="video-meta">
            <div>
                <div class="video-meta-title">{{ __('messages.video_meta_title') }}</div>
                <div class="video-meta-sub">{{ __('messages.video_meta_sub') }}</div>
            </div>
            <div class="video-duration">
                <i class="fa-regular fa-clock"></i> ~{{ __('messages.time_3_mins') }}
            </div>
        </div>
    </div>

    {{-- Step-by-step guide --}}
    <div style="font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#475569;margin-bottom:14px;display:flex;align-items:center;gap:8px">
        <i class="fa-solid fa-list-ol"></i> {{ __('messages.step_by_step_guide') }}
        <span style="flex:1;height:1px;background:rgba(255,255,255,0.05)"></span>
    </div>

    <div class="steps-grid">
        @php $instructionSteps = [
            ['num'=>1, 'title'=>'messages.ins_step1_title', 'desc'=>'messages.ins_step1_desc'],
            ['num'=>2, 'title'=>'messages.ins_step2_title', 'desc'=>'messages.ins_step2_desc'],
            ['num'=>3, 'title'=>'messages.ins_step3_title', 'desc'=>'messages.ins_step3_desc'],
            ['num'=>4, 'title'=>'messages.ins_step4_title', 'desc'=>'messages.ins_step4_desc'],
            ['num'=>5, 'title'=>'messages.ins_step5_title', 'desc'=>'messages.ins_step5_desc'],
            ['num'=>6, 'title'=>'messages.ins_step6_title', 'desc'=>'messages.ins_step6_desc'],
        ]; @endphp

        @foreach($instructionSteps as $s)
        <div class="step-card">
            <div class="step-num">{{ $s['num'] }}</div>
            <div class="step-title">{{ __($s['title']) }}</div>
            <div class="step-desc">{{ __($s['desc']) }}</div>
        </div>
        @endforeach
    </div>

    {{-- Footer actions --}}
    <div class="ins-footer">
        <div class="ins-footer-text">
            {!! __('messages.ins_footer_text') !!}
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <a href="{{ route('user.actionitem.index') }}" class="btn-next">
                <i class="fa-solid fa-arrow-left"></i> {{ __('messages.back') }}
            </a>
            @if(auth()->user()->action_item_viewed)
                <span class="btn-mark-done" style="background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.2); cursor: default; box-shadow: none;">
                    <i class="fa-solid fa-check"></i> {{ __('messages.already_viewed') }}
                </span>
            @else
                <form action="{{ route('user.action-items.mark-viewed') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-mark-done">
                        <i class="fa-solid fa-check"></i> {{ __('messages.mark_as_viewed') }}
                    </button>
                </form>
            @endif
        </div>
    </div>

</div>

@endsection