@extends('user.frontend.vyralabs-front')

@section('title', ($metaTitle ?? 'Terms & Conditions') . ' | Vyralabs')

@section('content')
<header class="terms-hero">
    <div class="container hero-inner">
        <div class="hero-eyebrow">Legal Framework</div>
        <h1 class="hero-title">Terms & <span class="grad">Conditions</span></h1>
        <div class="hero-meta">
            <div class="hero-meta-item"><i class="fa-solid fa-clock"></i> ~5 min read</div>
        </div>
    </div>
</header>

<div class="terms-layout">
    @php
    $fallback = [
        ['icon'=>'fa-flask','title'=>'Our Services','body'=>'Vyralabs provides preventative biomarker analysis systems. Content is purely informational.','note'=>null],
        ['icon'=>'fa-credit-card','title'=>'Payments & Orders','body'=>'All checkouts are routed securely via Stripe platform API keys.','note'=>null]
    ];
    @endphp

    <aside class="terms-sidebar">
        <div class="sidebar-label">Agreement Chapters</div>
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
            <div class="terms-card {{ $i === 0 ? 'open' : '' }}" id="chapter-{{ $i+1 }}">
                <div class="card-head" onclick="toggleCard(this)">
                    <div class="card-icon"><i class="fa-solid {{ $s['icon'] }}"></i></div>
                    <div class="card-meta">
                        <div class="card-num">Chapter {{ sprintf('%02d',$i+1) }}</div>
                        <div class="card-title">{{ $s['title'] }}</div>
                    </div>
                    <i class="fa-solid fa-chevron-down card-chevron"></i>
                </div>
                <div class="card-body">
                    <p>{{ $s['body'] }}</p>
                </div>
            </div>
        @endforeach
    </main>
</div>
@endsection

@push('scripts')
<script>
function toggleCard(head) {
    const card = head.closest('.terms-card');
    const isOpen = card.classList.contains('open');
    document.querySelectorAll('.terms-card').forEach(c => c.classList.remove('open'));
    if (!isOpen) card.classList.add('open');
}
</script>
@endpush