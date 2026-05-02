{{-- ===== STAT CARDS ===== --}}
<div class="stats-grid">
  @foreach($stats as $stat)
  <div class="stat-card">
    <div class="stat-top">
      <div class="stat-icon">
        <i class="{{ $stat['icon'] }}"></i>
      </div>
      <span class="stat-trend {{ $stat['trend'] > 0 ? 'trend-up' : 'trend-down' }}">
        <i class="fa-solid {{ $stat['trend'] > 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
        {{ abs($stat['trend']) }}%
      </span>
    </div>
    <div class="stat-value">{{ $stat['value'] }}</div>
    <div class="stat-label">{{ $stat['label'] }}</div>
    <div class="stat-sparkline">
      {!! $stat['sparkline'] !!}
    </div>
  </div>
  @endforeach
</div>
