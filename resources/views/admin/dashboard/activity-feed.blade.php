{{-- Activity Feed Partial --}}
{{-- Variables: $activities (Collection of activity objects) --}}

<div class="card">
  <div class="card-header">
    <div>
      <div class="card-title">Activity</div>
      <div class="card-subtitle">Real-time updates</div>
    </div>
    <a href="{{ route('admin.activities') }}" class="btn btn-ghost" style="padding:6px 12px; font-size:12px">
      View all
    </a>
  </div>

  <div class="activity-feed">
    @forelse($activities as $activity)
      <div class="activity-item">
        <div class="activity-dot-wrap">
          <div class="activity-dot" style="background:{{ $activity->color ?? '#6366f1' }}"></div>
          @if(!$loop->last)
            <div class="activity-line"></div>
          @endif
        </div>
        <div class="activity-content">
          <div class="activity-text">{!! $activity->description !!}</div>
          <div class="activity-time">{{ $activity->created_at->diffForHumans() }}</div>
        </div>
      </div>
    @empty
      <p style="color:var(--text-muted); font-size:13px; padding:16px 0;">No recent activity.</p>
    @endforelse
  </div>
</div>
