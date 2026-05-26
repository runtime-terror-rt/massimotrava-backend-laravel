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

    <a href="{{ route('admin.dashboard.index') }}" class="nav-item {{ request()->routeIs('admin.dashboard.index*') ? 'active' : '' }}" data-tooltip="{{ __('messages.sb_dashboard') }}">
      <i class="fa-solid fa-house-chimney"></i>
      <span>{{ __('messages.sb_dashboard') }}</span>
    </a>
    
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
    
    @can('view-reports')
    <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}" data-tooltip="{{ __('messages.sb_reports') }}">
      <i class="fa-solid fa-chart-bar"></i>
      <span>{{ __('messages.sb_reports') }}</span>
    </a>
    @endcan

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
    <a href="#" class="nav-item" data-tooltip="{{ __('messages.sb_payments') }}">
      <i class="fa-solid fa-dollar-sign"></i>
      <span>{{ __('messages.sb_payments') }}</span>
    </a>
    @endcan

    <div class="nav-section-label">{{ __('messages.sb_sec_system') }}</div>

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

  </nav>

  {{-- Sidebar Footer --}}
  <div class="sidebar-footer" style="padding: 15px 20px; border-top: 1px solid var(--border, #1e293b); text-align: center;">
    <div class="footer-content" style="display: flex; flex-direction: column; gap: 2px;">
        <span style="font-size: 12px; color: #64748b; font-weight: 500; letter-spacing: 0.3px;">
            © {{ date('Y') }} <span style="color: #cbd5e1;">Vyralabs</span>
        </span>
        <span style="font-size: 11px; color: var(--primary, #6366f1); font-family: monospace; font-weight: 600; background: rgba(99, 102, 241, 0.1); padding: 2px 8px; border-radius: 4px; width: fit-content; margin: 4px auto 0 auto;">
            v1.0.0
        </span>
    </div>
  </div>
</aside>