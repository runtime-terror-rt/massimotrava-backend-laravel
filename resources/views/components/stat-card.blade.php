{{--
  Stat Card Partial
  Usage: @include('components.admin.stat-card', [
    'icon'       => 'fa-dollar-sign',
    'trend'      => 'up',          // 'up' or 'down'
    'trend_pct'  => '12.4%',
    'value'      => '$84,291',
    'label'      => 'Total Revenue',
    'sparkline'  => $sparklinePath,   // SVG path d="" string
    'color'      => '#6366f1',        // accent color
    'gradient_id'=> 'g1',
  ])
--}}

<div class="stat-card">
  <div class="stat-top">
    <div class="stat-icon">
      <i class="fa-solid {{ $icon }}"></i>
    </div>
    <span class="stat-trend {{ $trend === 'up' ? 'trend-up' : 'trend-down' }}">
      <i class="fa-solid {{ $trend === 'up' ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
      {{ $trend_pct }}
    </span>
  </div>

  <div class="stat-value">{{ $value }}</div>
  <div class="stat-label">{{ $label }}</div>

  <div class="stat-sparkline">
    <svg viewBox="0 0 120 36" preserveAspectRatio="none">
      <defs>
        <linearGradient id="{{ $gradient_id }}" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%" stop-color="{{ $color }}" stop-opacity="0.3"/>
          <stop offset="100%" stop-color="{{ $color }}" stop-opacity="0"/>
        </linearGradient>
      </defs>
      <path d="{{ $sparkline }}" fill="none" stroke="{{ $color }}" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round"/>
      <path d="{{ $sparkline }} L120,36 L0,36Z" fill="url(#{{ $gradient_id }})"/>
    </svg>
  </div>
</div>
