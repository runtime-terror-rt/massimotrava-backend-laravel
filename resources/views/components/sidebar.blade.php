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

<aside class="sidebar" id="sidebar">

  {{-- Brand --}}
  <div class="sidebar-brand" style="position:relative;height:60px;display:block;">
    <img src="{{ asset('images/logo.avif') }}" alt="Vyralabs"
         style="position:absolute!important;top:15px!important;left:40%!important;transform:translateX(-50%)!important;height:38px!important;width:auto!important;object-fit:contain!important;max-width:85%!important;">
  </div>

  <nav class="sidebar-nav">

    {{-- ── MAIN ── --}}
    <div class="nav-section-label">{{ __('messages.main') }}</div>

    @if(!$isAdminOrLab)
      {{-- Action Items --}}
      <a href="{{ Route::has('user.actionitem.index') ? route('user.actionitem.index') : '#' }}"
        class="nav-item {{ request()->routeIs('user.actionitem.index*') ? 'active' : '' }}"
        data-tooltip="{{ __('messages.action_items') }}">
        <i class="fa-solid fa-list-check"></i>
        <span>{{ __('messages.action_items') }}</span>
      </a>
    @endif

    {{-- Dashboard --}}
    @if($isAdminOrLab)
      <a href="{{ route('admin.dashboard.index') }}"
         class="nav-item {{ request()->routeIs('admin.dashboard.index*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.dashboard') }}">
        <i class="fa-solid fa-house-chimney"></i>
        <span>{{ __('messages.dashboard') }}</span>
      </a>
    @else
      <a href="{{ Route::has('user.dashboard.index') ? route('user.dashboard.index') : '#' }}"
         class="nav-item {{ request()->routeIs('user.dashboard.index*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.dashboard') }}">
        <i class="fa-solid fa-house-chimney"></i>
        <span>{{ __('messages.dashboard') }}</span>
      </a>
    @endif

    {{-- Results & History / Reports --}}
    @php
      $reportRoute    = $isAdminOrLab ? route('admin.reports.index') : route('user.reports.index');
      $isReportActive = request()->routeIs('admin.reports*') || request()->routeIs('user.reports*');
      $reportLabel    = $isAdminOrLab ? __('messages.reports') : __('messages.results_history');
    @endphp
    <a href="{{ $reportRoute }}"
       class="nav-item {{ $isReportActive ? 'active' : '' }}"
       data-tooltip="{{ $reportLabel }}">
      <i class="fa-solid fa-chart-bar"></i>
      <span>{{ $reportLabel }}</span>
    </a>

    {{-- ── USER ONLY LINKS ── --}}
    @if(!$isAdminOrLab)
      <a href="{{ Route::has('user.kits.index') ? route('user.kits.index') : '#' }}"
         class="nav-item {{ request()->routeIs('user.kits*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.my_kits') }}">
        <i class="fa-solid fa-box-open"></i>
        <span>{{ __('messages.my_kits') }}</span>
      </a>

      <a href="{{ Route::has('user.pickup.index') ? route('user.pickup.index') : '#' }}"
         class="nav-item {{ request()->routeIs('user.pickup*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.pickup_requests') }}">
        <i class="fa-solid fa-truck-ramp-box"></i>
        <span>{{ __('messages.pickup_requests') }}</span>
      </a>

      <a href="{{ Route::has('user.health.insights') ? route('user.health.insights') : '#' }}"
         class="nav-item {{ request()->routeIs('user.health.insights*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.health_insights') }}">
        <i class="fa-solid fa-brain"></i>
        <span>{{ __('messages.health_insights') }}</span>
      </a>

      <a href="{{ Route::has('user.retests.index') ? route('user.retests.index') : '#' }}"
         class="nav-item {{ request()->routeIs('user.retests.index*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.schedule_retest') }}">
        <i class="fa-solid fa-calendar"></i>
        <span>{{ __('messages.schedule_retest') }}</span>
      </a>

      <a href="{{ route('user.subscription.my') }}" 
        class="nav-item {{ request()->routeIs('user.subscription.my*') ? 'active' : '' }}">
          <i class="fa-solid fa-dollar"></i>
          <span>{{ __('messages.subscription') }}</span>
      </a>
    @endif

    {{-- ── ADMIN/LAB ONLY LINKS ── --}}
    @if($isAdminOrLab)
      @can('manage-labs')
      <a href="{{ route('admin.labs.index') }}"
         class="nav-item {{ request()->routeIs('admin.labs.index*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.labs') }}">
        <i class="fa-solid fa-building"></i>
        <span>{{ __('messages.labs') }}</span>
      </a>
      @endcan

      @can('manage-laboratorians')
      <a href="{{ route('admin.get.lab.users') }}"
         class="nav-item {{ request()->routeIs('admin.get.lab.users*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.laboratorians') }}">
        <i class="fa-solid fa-users-gear"></i>
        <span>{{ __('messages.laboratorians') }}</span>
      </a>
      @endcan

      {{-- ── MANAGEMENT ── --}}
      <div class="nav-section-label">{{ __('messages.management') }}</div>

      @can('manage-users')
      <a href="{{ route('admin.get.users') }}"
         class="nav-item {{ request()->routeIs('admin.get.users*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.users') }}">
        <i class="fa-solid fa-users"></i>
        <span>{{ __('messages.users') }}</span>
      </a>
      @endcan

      @can('manage-categories')
      <a href="{{ route('admin.category.index') }}"
         class="nav-item {{ request()->routeIs('admin.category.index*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.categories') }}">
        <i class="fa-solid fa-table-cells-large"></i>
        <span>{{ __('messages.categories') }}</span>
      </a>
      @endcan

      @can('manage-subcategories')
      <a href="{{ route('admin.biomarker-subcategories.index') }}"
         class="nav-item {{ request()->routeIs('admin.biomarker-subcategories.index*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.sub_categories') }}">
        <i class="fa-solid fa-table-list"></i>
        <span>{{ __('messages.sub_categories') }}</span>
      </a>
      @endcan

      @can('manage-kits')
      <a href="{{ route('admin.kits.index') }}"
         class="nav-item {{ request()->routeIs('admin.kits.index*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.kit_manager') }}">
        <i class="fa-solid fa-arrows-left-right"></i>
        <span>{{ __('messages.kit_manager') }}</span>
      </a>
      @endcan

      {{-- Admin Pickup Requests --}}
      <a href="{{ Route::has('admin.pick-up.index') ? route('admin.pick-up.index') : '#' }}"
         class="nav-item {{ request()->routeIs('admin.pick-up*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.pickup_requests') }}">
        <i class="fa-solid fa-truck-ramp-box"></i>
        <span>{{ __('messages.pickup_requests') }}</span>
      </a>

      @can('view-payments')
      <a href="{{ route('admin.payments.index') }}"
         class="nav-item {{ request()->routeIs('admin.payments.index*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.payments') }}">
        <i class="fa-solid fa-dollar-sign"></i>
        <span>{{ __('messages.payments') }}</span>
      </a>
      @endcan

      {{-- Subscription Plans --}}
      @can('manage-settings')
      <a href="{{ Route::has('admin.review.index') ? route('admin.review.index') : '#' }}"
         class="nav-item {{ request()->routeIs('admin.review.index*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.review') }}">
        <i class="fa-solid fa-credit-card"></i>
        <span>{{ __('messages.review') }}</span>
      </a>
      @endcan

      {{-- Newsletter --}}
      @can('manage-newsletter')
      <a href="{{ route('admin.newsletter.index') }}"
         class="nav-item {{ request()->routeIs('admin.newsletter.index*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.newsletter') }}">
        <i class="fa-solid fa-envelope-open-text"></i>
        <span>{{ __('messages.newsletter') }}</span>
      </a>
      @endcan
      
      {{-- ── SYSTEM ── --}}
      <div class="nav-section-label">{{ __('messages.system') }}</div>

      @can('manage-contents')
      <a href="{{ route('admin.contents.index') }}"
         class="nav-item {{ request()->routeIs('admin.contents.index*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.contents') }}">
        <i class="fa-solid fa-file-lines"></i>
        <span>{{ __('messages.contents') }}</span>
      </a>
      @endcan

      @can('manage-campaigns')
      <a href="{{ route('admin.campaigns.index') }}"
         class="nav-item {{ request()->routeIs('admin.campaigns.index*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.campaigns') }}">
        <i class="fa-solid fa-bullhorn"></i>
        <span>{{ __('messages.campaigns') }}</span>
      </a>
      @endcan

      @can('manage-settings')
      <a href="{{ route('admin.privacy-policy.index') }}"
         class="nav-item {{ request()->routeIs('admin.privacy-policy.index*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.privacy_policy') }}">
        <i class="fa-solid fa-building-lock"></i>
        <span>{{ __('messages.privacy_policy') }}</span>
      </a>

      <a href="{{ route('admin.faq.index') }}"
         class="nav-item {{ request()->routeIs('admin.faq.index*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.faq') }}">
        <i class="fa-solid fa-circle-question"></i>
        <span>{{ __('messages.faq') }}</span>
      </a>
      @endcan

      @can('manage-courier')
      <a href="#" class="nav-item" data-tooltip="{{ __('messages.courier') }}">
        <i class="fa-solid fa-cart-shopping"></i>
        <span>{{ __('messages.courier') }}</span>
      </a>
      @endcan

      @can('manage-roles-permissions')
      <a href="{{ route('admin.role-permission.index') }}"
         class="nav-item {{ request()->routeIs('admin.role-permission.index*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.roles_permissions') }}">
        <i class="fa-solid fa-shield-halved"></i>
        <span>{{ __('messages.roles_permissions') }}</span>
      </a>
      @endcan

      @can('view-audit-logs')
      <a href="{{ route('admin.audit-logs.index') }}"
         class="nav-item {{ request()->routeIs('admin.audit-logs.index*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.audit_logs') }}">
        <i class="fa-solid fa-arrow-up-right-dots"></i>
        <span>{{ __('messages.audit_logs') }}</span>
      </a>
      @endcan
    @endif

    {{-- ── SETTINGS (user only) ── --}}
    @if(!$isAdminOrLab)
      <div class="nav-section-label">{{ __('messages.settings') }}</div>

      <a href="{{ Route::has('user.profile.edit') ? route('user.profile.edit') : '#' }}"
         class="nav-item {{ request()->routeIs('user.profile.edit*') ? 'active' : '' }}"
         data-tooltip="{{ __('messages.account_settings') }}">
        <i class="fa-solid fa-gear"></i>
        <span>{{ __('messages.account_settings') }}</span>
      </a>
    @endif

  </nav>

  {{-- Footer --}}
  <div class="sidebar-footer" style="padding:15px 20px;border-top:1px solid var(--sb-border);text-align:center;">
    <div style="display:flex;flex-direction:column;gap:2px;">
      <span style="font-size:12px;color:var(--text-muted-dark);font-weight:500;letter-spacing:0.3px;">
         © {{ date('Y') }} <span style="color:var(--text-muted);">Vyralabs</span>
      </span>
      <span style="font-size:11px;color:var(--accent);font-family:monospace;font-weight:600;background:rgba(99,102,241,0.1);padding:2px 8px;border-radius:4px;width:fit-content;margin:4px auto 0 auto;">
        v1.0.0
      </span>
    </div>
  </div>

</aside>