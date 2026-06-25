@extends('user.frontend.vyralabs-front')

@section('title', ($metaTitle ?? 'Privacy Policy') . ' | Vyralabs')

@section('content')
<header class="policy-hero">
    <div class="container hero-inner">
        <div class="hero-eyebrow">Security Framework</div>
        <h1 class="hero-title">Privacy <span class="grad">Policy</span></h1>
        <div class="hero-meta">
            <div class="hero-meta-item"><i class="fa-solid fa-calendar"></i> Updated Today</div>
            <span class="hero-meta-sep"></span>
            <div class="hero-meta-item"><i class="fa-solid fa-shield-halved"></i> GDPR & HIPAA Aligned</div>
        </div>
    </div>
</header>

<div class="policy-layout">
    @php
    $fallback = [
        ['icon'=>'fa-user-shield','title'=>'Data Collection','body'=>'We process biometric and wellness metrics exclusively to build your optimization profiles.','note'=>'We never share or monetize your private medical insights.'],
        ['icon'=>'fa-lock','title'=>'Data Security','body'=>'We implement banking-grade cryptographic architecture to store physiological records.','note'=>null]
    ];
    @endphp

    <aside class="policy-sidebar">
        <div class="sidebar-label">Table of Contents</div>
        <ul class="toc-list">
            @foreach($fallback as $i => $s)
                <li class="toc-item {{ $i === 0 ? 'active' : '' }}">
                    <a href="#chapter-{{ $i + 1 }}"><span class="toc-num">{{ sprintf('%02d', $i + 1) }}</span> {{ $s['title'] }}</a>
                </li>
            @endforeach
        </ul>
    </aside>

    <main class="content-stream">
        @foreach($fallback as $i => $s)
            <div class="policy-card {{ $i === 0 ? 'open' : '' }}" id="chapter-{{ $i+1 }}">
                <div class="card-head" onclick="toggleCard(this)">
                    <div class="card-icon"><i class="fa-solid {{ $s['icon'] }}"></i></div>
                    <div class="card-meta">
                        <div class="card-num">Section {{ sprintf('%02d',$i+1) }}</div>
                        <div class="card-title">{{ $s['title'] }}</div>
                    </div>
                    <i class="fa-solid fa-chevron-down card-chevron"></i>
                </div>
                <div class="card-body">
                    <p>{{ $s['body'] }}</p>
                    @if($s['note'])
                        <div class="card-note"><strong>Note:</strong> {{ $s['note'] }}</div>
                    @endif
                </div>
            </div>
        @endforeach
    </main>
</div>
@endsection

@push('scripts')
<script>
function toggleCard(head) {
    const card = head.closest('.policy-card');
    const isOpen = card.classList.contains('open');
    document.querySelectorAll('.policy-card').forEach(c => c.classList.remove('open'));
    if (!isOpen) card.classList.add('open');
}
</script>
@endpush