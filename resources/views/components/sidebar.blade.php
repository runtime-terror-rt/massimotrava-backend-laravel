@php
    $user = auth()->user();
    
    $isAdminOrLab = false;
    if (method_exists($user, 'hasRole')) {
        $isAdminOrLab = $user->hasRole(['admin', 'lab']);
    } else if (method_exists($user, 'roles')) {
        $isAdminOrLab = $user->roles()->whereIn('name', ['admin', 'lab'])->exists();
    } else {
        $isAdminOrLab = $user->can('manage-kits');
    }
@endphp

{{-- ===== SIDEBAR ===== --}}
<aside class="sidebar" id="sidebar">

  {{-- Brand --}}
  <div class="sidebar-brand" style="position: relative; height: 60px; display: block;">
    <img src="{{ asset('images/logo.avif') }}" alt="Massimo Logo" 
         style="position: absolute !important; top: 15px !important; left: 40% !important; transform: translateX(-50%) !important; height: 38px !important; width: auto !important; object-fit: contain !important; max-width: 85% !important;">
  </div>

  {{-- Navigation --}}
  <nav class="sidebar-nav">

    <div class="nav-section-label">{{ __('messages.sb_sec_main') }}</div>

    @if($isAdminOrLab)
        <a href="{{ route('admin.dashboard.index') }}" class="nav-item {{ request()->routeIs('admin.dashboard.index*') ? 'active' : '' }}" data-tooltip="{{ __('messages.sb_dashboard') }}">
          <i class="fa-solid fa-house-chimney"></i>
          <span>{{ __('messages.sb_dashboard') }}</span>
        </a>
    @else
        <a href="{{ Route::has('user.dashboard.index') ? route('user.dashboard.index') : '#' }}" class="nav-item {{ request()->routeIs('user.dashboard.index*') ? 'active' : '' }}" data-tooltip="{{ __('messages.sb_dashboard') }}">
          <i class="fa-solid fa-house-chimney"></i>
          <span>{{ __('messages.sb_dashboard') }}</span>
        </a>
    @endif
    
    @if($isAdminOrLab)
        @can('manage-labs')
        <a href="{{ route('admin.labs.index') }}" class="nav-item {{ request()->routeIs('admin.labs.index*') ? 'active' : '' }}" data-tooltip="{{ __('messages.sb_lab') }}">
          <i class="fa-solid fa-building"></i>
          <span>{{ __('messages.sb_lab') }}</span>
        </a>
        @endcan
        
        @can('manage-laboratorians')
        <a href="{{ route('admin.get.lab.users') }}" class="nav-item {{ request()->routeIs('admin.get.lab.users*') ? 'active' : '' }}" data-tooltip="{{ __('messages.sb_laboratorian') }}">
          <i class="fa-solid fa-users-gear"></i>
          <span>{{ __('messages.sb_laboratorian') }}</span>
        </a>
        @endcan
    @endif
    
    @php
        $reportRoute = $isAdminOrLab ? route('admin.reports.index') : route('user.reports.index');
        $isReportActive = request()->routeIs('admin.reports*') || request()->routeIs('user.reports*');
        $reportLabel = $isAdminOrLab ? __('messages.sb_reports') : (__('messages.sb_results_history') ?? 'Results & History');
    @endphp
    <a href="{{ $reportRoute }}" class="nav-item {{ $isReportActive ? 'active' : '' }}" data-tooltip="{{ $reportLabel }}">
      <i class="fa-solid fa-chart-bar"></i>
      <span>{{ $reportLabel }}</span>
    </a>

    @if(!$isAdminOrLab)
        <a href="{{ route('admin.kits.index') }}" class="nav-item {{ request()->routeIs('admin.kits.index*') ? 'active' : '' }}" data-tooltip="{{ __('messages.sb_my_kits') ?? 'My Kits' }}">
          <i class="fa-solid fa-box-open"></i>
          <span>{{ __('messages.sb_my_kits') ?? 'Kits' }}</span>
        </a>

        <a href="{{ Route::has('user.pickup.index') ? route('user.pickup.index') : '#' }}" class="nav-item {{ request()->routeIs('user.pickup*') ? 'active' : '' }}" data-tooltip="Pickup">
          <i class="fa-solid fa-truck-ramp-box"></i>
          <span>{{ __('messages.sb_pickup') ?? 'Pickup' }}</span>
        </a>

        <a href="{{ Route::has('user.insights.index') ? route('user.insights.index') : '#' }}" class="nav-item {{ request()->routeIs('user.insights*') ? 'active' : '' }}" data-tooltip="Insights">
          <i class="fa-solid fa-brain"></i>
          <span>{{ __('messages.sb_insights') ?? 'Insights' }}</span>
        </a>
    @endif


    @if($isAdminOrLab)
        <div class="nav-section-label">{{ __('messages.sb_sec_management') }}</div>

        @can('manage-users')
        <a href="{{ route('admin.get.users') }}" class="nav-item {{ request()->routeIs('admin.get.users*') ? 'active' : '' }}" data-tooltip="{{ __('messages.sb_users') }}">
          <i class="fa-solid fa-users"></i>
          <span>{{ __('messages.sb_users') }}</span>
        </a>
        @endcan
        
        @can('manage-categories')
        <a href="{{ route('admin.category.index') }}" class="nav-item {{ request()->routeIs('admin.category.index*') ? 'active' : '' }}" data-tooltip="{{ __('messages.sb_category') }}">
          <i class="fa-solid fa-table-cells-large"></i>
          <span>{{ __('messages.sb_category') }}</span>
        </a>
        @endcan
        
        @can('manage-subcategories')
        <a href="{{ route('admin.biomarker-subcategories.index') }}" class="nav-item {{ request()->routeIs('admin.biomarker-subcategories.index*') ? 'active' : '' }}" data-tooltip="{{ __('messages.sb_sub_category') }}">
          <i class="fa-solid fa-table-list"></i>
          <span>{{ __('messages.sb_sub_category') }}</span>
        </a>
        @endcan
        
        @can('manage-kits')
        <a href="{{ route('admin.kits.index') }}" class="nav-item {{ request()->routeIs('admin.kits.index*') ? 'active' : '' }}" data-tooltip="{{ __('messages.sb_kit_manager') }}">
          <i class="fa-solid fa-arrows-left-right"></i>
          <span>{{ __('messages.sb_kit_manager') }}</span>
        </a>
        @endcan

        @can('view-payments')
        <a href="{{route('admin.payments.index')}}" class="nav-item {{ request()->routeIs('admin.payments.index*') ? 'active' : ''}}" data-tooltip="{{ __('messages.sb_payments') }}">
          <i class="fa-solid fa-dollar-sign"></i>
          <span>{{ __('messages.sb_payments') }}</span>
        </a>
        @endcan
    @endif


    <div class="nav-section-label">{{ $isAdminOrLab ? __('messages.sb_sec_system') : (__('messages.sb_sec_settings') ?? 'Settings') }}</div>

    @if($isAdminOrLab)
        {{-- Admin System Links --}}
        @can('manage-contents')
        <a href="{{ route('admin.contents.index') }}" class="nav-item {{ request()->routeIs('admin.contents.index*') ? 'active' : '' }}" data-tooltip="{{ __('messages.sb_contents') }}">
          <i class="fa-solid fa-building-lock"></i>
          <span>{{ __('messages.sb_contents') }}</span>
        </a>
        @endcan
        
        @can('manage-campaigns')
        <a href="{{ route('admin.campaigns.index') }}" class="nav-item {{ request()->routeIs('admin.campaigns.index*') ? 'active' : '' }}" data-tooltip="{{ __('messages.sb_campaigns') }}">
          <i class="fa-solid fa-building-lock"></i>
          <span>{{ __('messages.sb_campaigns') }}</span>
        </a>
        @endcan

        @can('manage-settings')
        <a href="{{ route('admin.privacy-policy.index') }}" class="nav-item {{ request()->routeIs('admin.privacy-policy.index*') ? 'active' : '' }}" data-tooltip="{{ __('messages.sb_privacy_policy') }}">
          <i class="fa-solid fa-building-lock"></i>
          <span>{{ __('messages.sb_privacy_policy') }}</span>
        </a>
        
        <a href="{{ route('admin.faq.index') }}" class="nav-item {{ request()->routeIs('admin.faq.index*') ? 'active' : '' }}" data-tooltip="{{ __('messages.sb_faq') }}">
          <i class="fa-solid fa-circle-question"></i>
          <span>{{ __('messages.sb_faq') }}</span>
        </a>
        @endcan
        
        @can('manage-courier')
        <a href="#" class="nav-item" data-tooltip="{{ __('messages.sb_courier') }}">
          <i class="fa-solid fa-cart-shopping"></i>
          <span>{{ __('messages.sb_courier') }}</span>
        </a>
        @endcan
        
        @can('manage-roles-permissions')
        <a href="{{ route('admin.role-permission.index') }}" class="nav-item {{ request()->routeIs('admin.role-permission.index*') ? 'active' : '' }}" data-tooltip="{{ __('messages.sb_role_permission') }}">
          <i class="fa-solid fa-shield-halved"></i>
          <span>{{ __('messages.sb_role_permission') }}</span>
        </a>
        @endcan
        
        @can('view-audit-logs')
        <a href="{{ route('admin.audit-logs.index') }}" class="nav-item {{ request()->routeIs('admin.audit-logs.index*') ? 'active' : '' }}" data-tooltip="{{ __('messages.sb_audit_log') }}">
          <i class="fa-solid fa-arrow-up-right-dots"></i>
          <span>{{ __('messages.sb_audit_log') }}</span>
        </a>
        @endcan
    @else
        <a href="{{ Route::has('user.settings.index') ? route('user.settings.index') : '#' }}" class="nav-item {{ request()->routeIs('user.settings*') ? 'active' : '' }}" data-tooltip="Settings">
          <i class="fa-solid fa-gear"></i>
          <span>{{ __('messages.settings_header') ?? 'Settings' }}</span>
        </a>
    @endif

  </nav>

  {{-- Sidebar Footer --}}
  <div class="sidebar-footer" style="padding: 15px 20px; border-top: 1px solid var(--sb-border); text-align: center;">
    <div class="footer-content" style="display: flex; flex-direction: column; gap: 2px;">
        <span style="font-size: 12px; color: var(--text-muted-dark); font-weight: 500; letter-spacing: 0.3px;">
            © {{ date('Y') }} <span style="color: var(--text-muted);">Vyralabs</span>
        </span>
        <span style="font-size: 11px; color: var(--accent); font-family: monospace; font-weight: 600; background: rgba(99, 102, 241, 0.1); padding: 2px 8px; border-radius: 4px; width: fit-content; margin: 4px auto 0 auto;">
            v1.0.0
        </span>
    </div>
  </div>
</aside>