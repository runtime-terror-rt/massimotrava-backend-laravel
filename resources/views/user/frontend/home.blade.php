@extends('user.frontend.vyralabs-front')

@section('title', "Vyralabs | The World's Easiest Performance Lab Test")

@section('content')
<main>
    <div class="container" style="padding: 100px 0; text-align: center;">
        <h1>The World's Easiest Performance Lab Test</h1>
        <p>Premium preventive healthcare insights tailored to your physiology.</p>
        
        <div style="margin-top: 40px;">
            <button class="btn btn-ghost vyr-filter-btn active" data-filter="all">All Tests</button>
            <button class="btn btn-ghost vyr-filter-btn" data-filter="longevity">Longevity</button>
        </div>
        
        <div class="stream-item-node" data-type-node="longevity" style="margin-top:20px;">
            <h3>Biomarker Mapping Analysis Node</h3>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var btns  = document.querySelectorAll('.vyr-filter-btn');
    var cards = document.querySelectorAll('.stream-item-node');
    btns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            btns.forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active');
            var val = this.getAttribute('data-filter');
            cards.forEach(function (card) {
                card.style.display = (val === 'all' || card.getAttribute('data-type-node') === val) ? 'flex' : 'none';
            });
        });
    });
});
</script>
@endpush