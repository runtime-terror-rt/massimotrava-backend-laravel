{{-- ===== ACTIVITY FEED ===== --}}
<div class="card">
  <div class="card-header">
    <div>
      <div class="card-title">Activity</div>
      <div class="card-subtitle">Real-time updates</div>
    </div>
    <button class="btn btn-ghost" style="padding:6px 12px; font-size:12px">View all</button>
  </div>

  <div class="activity-feed">
    @foreach($activities as $activity)
    <div class="activity-item">
      <div class="activity-dot-wrap">
        <div class="activity-dot" style="background:{{ $activity['color'] }}"></div>
        <div class="activity-line"></div>
      </div>
      <div class="activity-content">
        <div class="activity-text">{!! $activity['text'] !!}</div>
        <div class="activity-time">{{ $activity['time'] }}</div>
      </div>
    </div>
    @endforeach
  </div>
</div>
