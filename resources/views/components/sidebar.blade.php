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
    <div class="nav-section-label">Main</div>

    @if(!$isAdminOrLab)

      {{-- Action Items (Rythm Health style) --}}
      <a href="{{ Route::has('user.actionitem.index') ? route('user.actionitem.index') : '#' }}"
        class="nav-item {{ request()->routeIs('user.actionitem.index*') ? 'active' : '' }}"
        data-tooltip="Action Items">
        <i class="fa-solid fa-list-check"></i>
        <span>Action Items</span>
       
      </a>

    @endif
    {{-- Dashboard --}}
    @if($isAdminOrLab)
      <a href="{{ route('admin.dashboard.index') }}"
         class="nav-item {{ request()->routeIs('admin.dashboard.index*') ? 'active' : '' }}"
         data-tooltip="Dashboard">
        <i class="fa-solid fa-house-chimney"></i>
        <span>Dashboard</span>
      </a>
    @else
      <a href="{{ Route::has('user.dashboard.index') ? route('user.dashboard.index') : '#' }}"
         class="nav-item {{ request()->routeIs('user.dashboard.index*') ? 'active' : '' }}"
         data-tooltip="Dashboard">
        <i class="fa-solid fa-house-chimney"></i>
        <span>Dashboard</span>
      </a>
    @endif

    {{-- Results & History (both) --}}
    @php
      $reportRoute    = $isAdminOrLab ? route('admin.reports.index') : route('user.reports.index');
      $isReportActive = request()->routeIs('admin.reports*') || request()->routeIs('user.reports*');
      $reportLabel    = $isAdminOrLab ? 'Reports' : 'Results & History';
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
         data-tooltip="My Kits">
        <i class="fa-solid fa-box-open"></i>
        <span>My Kits</span>
      </a>

      <a href="{{ Route::has('user.pickup.index') ? route('user.pickup.index') : '#' }}"
         class="nav-item {{ request()->routeIs('user.pickup*') ? 'active' : '' }}"
         data-tooltip="Pickup Requests">
        <i class="fa-solid fa-truck-ramp-box"></i>
        <span>Pickup Requests</span>
      </a>

      <a href="{{ Route::has('user.health.insights') ? route('user.health.insights') : '#' }}"
         class="nav-item {{ request()->routeIs('user.health.insights*') ? 'active' : '' }}"
         data-tooltip="Health Insights">
        <i class="fa-solid fa-brain"></i>
        <span>Health Insights</span>
      </a>

      <a href="{{ Route::has('user.retests.index') ? route('user.retests.index') : '#' }}"
         class="nav-item {{ request()->routeIs('user.retests.index*') ? 'active' : '' }}"
         data-tooltip="Schedule Retest">
        <i class="fa-solid fa-calendar"></i>
        <span>Schedule Retest </span>
      </a>
    @endif

    {{-- ── ADMIN/LAB ONLY LINKS ── --}}
    @if($isAdminOrLab)
      @can('manage-labs')
      <a href="{{ route('admin.labs.index') }}"
         class="nav-item {{ request()->routeIs('admin.labs.index*') ? 'active' : '' }}"
         data-tooltip="Labs">
        <i class="fa-solid fa-building"></i>
        <span>Labs</span>
      </a>
      @endcan

      @can('manage-laboratorians')
      <a href="{{ route('admin.get.lab.users') }}"
         class="nav-item {{ request()->routeIs('admin.get.lab.users*') ? 'active' : '' }}"
         data-tooltip="Laboratorians">
        <i class="fa-solid fa-users-gear"></i>
        <span>Laboratorians</span>
      </a>
      @endcan

      {{-- ── MANAGEMENT ── --}}
      <div class="nav-section-label">Management</div>

      @can('manage-users')
      <a href="{{ route('admin.get.users') }}"
         class="nav-item {{ request()->routeIs('admin.get.users*') ? 'active' : '' }}"
         data-tooltip="Users">
        <i class="fa-solid fa-users"></i>
        <span>Users</span>
      </a>
      @endcan

      @can('manage-categories')
      <a href="{{ route('admin.category.index') }}"
         class="nav-item {{ request()->routeIs('admin.category.index*') ? 'active' : '' }}"
         data-tooltip="Categories">
        <i class="fa-solid fa-table-cells-large"></i>
        <span>Categories</span>
      </a>
      @endcan

      @can('manage-subcategories')
      <a href="{{ route('admin.biomarker-subcategories.index') }}"
         class="nav-item {{ request()->routeIs('admin.biomarker-subcategories.index*') ? 'active' : '' }}"
         data-tooltip="Sub Categories">
        <i class="fa-solid fa-table-list"></i>
        <span>Sub Categories</span>
      </a>
      @endcan

      @can('manage-kits')
      <a href="{{ route('admin.kits.index') }}"
         class="nav-item {{ request()->routeIs('admin.kits.index*') ? 'active' : '' }}"
         data-tooltip="Kit Manager">
        <i class="fa-solid fa-arrows-left-right"></i>
        <span>Kit Manager</span>
      </a>
      @endcan

      {{-- Admin Pickup Requests --}}
      <a href="{{ Route::has('admin.pickup.index') ? route('admin.pickup.index') : '#' }}"
         class="nav-item {{ request()->routeIs('admin.pickup*') ? 'active' : '' }}"
         data-tooltip="Pickup Requests">
        <i class="fa-solid fa-truck-ramp-box"></i>
        <span>Pickup Requests</span>
      </a>

      @can('view-payments')
      <a href="{{ route('admin.payments.index') }}"
         class="nav-item {{ request()->routeIs('admin.payments.index*') ? 'active' : '' }}"
         data-tooltip="Payments">
        <i class="fa-solid fa-dollar-sign"></i>
        <span>Payments</span>
      </a>
      @endcan

      {{-- Subscription Plans --}}
      {{-- @can('manage-settings')
      <a href="{{ Route::has('admin.plans.index') ? route('admin.plans.index') : '#' }}"
         class="nav-item {{ request()->routeIs('admin.plans*') ? 'active' : '' }}"
         data-tooltip="Subscription Plans">
        <i class="fa-solid fa-credit-card"></i>
        <span>Subscription Plans</span>
      </a>
      @endcan --}}

      {{-- ── SYSTEM ── --}}
      <div class="nav-section-label">System</div>

      @can('manage-contents')
      <a href="{{ route('admin.contents.index') }}"
         class="nav-item {{ request()->routeIs('admin.contents.index*') ? 'active' : '' }}"
         data-tooltip="Contents">
        <i class="fa-solid fa-file-lines"></i>
        <span>Contents</span>
      </a>
      @endcan

      @can('manage-campaigns')
      <a href="{{ route('admin.campaigns.index') }}"
         class="nav-item {{ request()->routeIs('admin.campaigns.index*') ? 'active' : '' }}"
         data-tooltip="Campaigns">
        <i class="fa-solid fa-bullhorn"></i>
        <span>Campaigns</span>
      </a>
      @endcan

      @can('manage-settings')
      <a href="{{ route('admin.privacy-policy.index') }}"
         class="nav-item {{ request()->routeIs('admin.privacy-policy.index*') ? 'active' : '' }}"
         data-tooltip="Privacy Policy">
        <i class="fa-solid fa-building-lock"></i>
        <span>Privacy Policy</span>
      </a>

      <a href="{{ route('admin.faq.index') }}"
         class="nav-item {{ request()->routeIs('admin.faq.index*') ? 'active' : '' }}"
         data-tooltip="FAQ">
        <i class="fa-solid fa-circle-question"></i>
        <span>FAQ</span>
      </a>
      @endcan

      @can('manage-courier')
      <a href="#" class="nav-item" data-tooltip="Courier">
        <i class="fa-solid fa-cart-shopping"></i>
        <span>Courier</span>
      </a>
      @endcan

      @can('manage-roles-permissions')
      <a href="{{ route('admin.role-permission.index') }}"
         class="nav-item {{ request()->routeIs('admin.role-permission.index*') ? 'active' : '' }}"
         data-tooltip="Roles & Permissions">
        <i class="fa-solid fa-shield-halved"></i>
        <span>Roles & Permissions</span>
      </a>
      @endcan

      @can('view-audit-logs')
      <a href="{{ route('admin.audit-logs.index') }}"
         class="nav-item {{ request()->routeIs('admin.audit-logs.index*') ? 'active' : '' }}"
         data-tooltip="Audit Logs">
        <i class="fa-solid fa-arrow-up-right-dots"></i>
        <span>Audit Logs</span>
      </a>
      @endcan
    @endif

    {{-- ── SETTINGS (user only) ── --}}
    @if(!$isAdminOrLab)
      <div class="nav-section-label">Settings</div>

      <a href="{{ Route::has('user.settings.index') ? route('user.settings.index') : '#' }}"
         class="nav-item {{ request()->routeIs('user.settings*') ? 'active' : '' }}"
         data-tooltip="Account Settings">
        <i class="fa-solid fa-gear"></i>
        <span>Account Settings</span>
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