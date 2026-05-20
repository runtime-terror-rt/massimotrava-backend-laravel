{{-- ===== SIDEBAR ===== --}}
<aside class="sidebar" id="sidebar">

  {{-- Brand --}}
  <div class="sidebar-brand" style="position: relative; height: 60px; display: block;">
    <img src="{{ asset('images/logo.avif') }}" alt="Massimo Logo" 
         style="position: absolute !important; top: 15px !important; left: 40% !important; transform: translateX(-50%) !important; height: 38px !important; width: auto !important; object-fit: contain !important; max-width: 85% !important;">
  </div>

  {{-- Navigation --}}
  <nav class="sidebar-nav">

    <div class="nav-section-label">Main</div>

    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard*') || request()->routeIs('admin.dashboard.index') ? 'active' : '' }}" data-tooltip="Dashboard">
      <i class="fa-solid fa-house-chimney"></i>
      <span>Dashboard</span>
    </a>
    
    <a href="{{ route('admin.labs.index') }}" class="nav-item {{ request()->routeIs('admin.labs.index*') ? 'active' : '' }}" data-tooltip="Labs">
      <i class="fa-solid fa-building"></i>
      <span>Lab</span>
    </a>
    
    <a href="{{ route('admin.get.lab.users') }}" class="nav-item {{ request()->routeIs('admin.get.lab.users*') ? 'active' : '' }}" data-tooltip="Laboratorian">
      <i class="fa-solid fa-users-gear"></i>
      <span>Laboratorian</span>
    </a>
    
    <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}" data-tooltip="Reports">
      <i class="fa-solid fa-chart-bar"></i>
      <span>Reports</span>
    </a>

    <div class="nav-section-label">Management</div>

    <a href="{{ route('admin.get.users') }}" class="nav-item {{ request()->routeIs('admin.get.users*') ? 'active' : '' }}" data-tooltip="Users">
      <i class="fa-solid fa-users"></i>
      <span>Users</span>
    </a>
    
    <a href="{{ route('admin.category.index') }}" class="nav-item {{ request()->routeIs('admin.category.index*') ? 'active' : '' }}" data-tooltip="Category">
      <i class="fa-solid fa-table-cells-large"></i>
      <span>Category</span>
    </a>
    
    <a href="{{ route('admin.biomarker-subcategories.index') }}" class="nav-item {{ request()->routeIs('admin.biomarker-subcategories.index*') ? 'active' : '' }}" data-tooltip="Sub Category">
      <i class="fa-solid fa-table-list"></i>
      <span>Sub Category</span>
    </a>
    
    
    <a href="{{ route('admin.kits.index') }}" class="nav-item {{ request()->routeIs('admin.kits.index*') ? 'active' : '' }}" data-tooltip="Kit Manager">
      <i class="fa-solid fa-arrows-left-right"></i>
      <span>Kit Manager</span>
    </a>

    <a href="#" class="nav-item {{ request()->routeIs('admin.biomarker-subcategories.index*') ? 'active' : '' }}" data-tooltip="Sub Category">
      <i class="fa-solid fa-dollar-sign"></i>
      <span>Payments</span>
    </a>

    <div class="nav-section-label">System</div>

    <a href="{{ route('admin.contents.index') }}" class="nav-item {{ request()->routeIs('admin.contents.index.index*') ? 'active' : '' }}" data-tooltip="Contents">
      <i class="fa-solid fa-building-lock"></i>
      <span>Contents</span>
    </a>
    
    <a href="{{ route('admin.campaigns.index') }}" class="nav-item {{ request()->routeIs('admin.campaigns.index*') ? 'active' : '' }}" data-tooltip="Campaigns">
      <i class="fa-solid fa-building-lock"></i>
      <span>Campaigns</span>
    </a>


    <a href="{{ route('admin.privacy-policy.index') }}" class="nav-item {{ request()->routeIs('admin.privacy-policy.index*') ? 'active' : '' }}" data-tooltip="Privacy Policy">
      <i class="fa-solid fa-building-lock"></i>
      <span>Privacy Policy</span>
    </a>
    
    <a href="{{ route('admin.faq.index') }}" class="nav-item {{ request()->routeIs('admin.faq.index*') ? 'active' : '' }}" data-tooltip="FAQ">
      <i class="fa-solid fa-circle-question"></i>
      <span>FAQ</span>
    </a>
    
    <a href="#" class="nav-item" data-tooltip="Courier">
      <i class="fa-solid fa-cart-shopping"></i>
      <span>Courier</span>
    </a>
    
    {{-- Fixed Icon and Active State Route matching --}}
    <a href="{{ route('admin.role-permission.index') }}" class="nav-item {{ request()->routeIs('admin.role-permission.index*') ? 'active' : '' }}" data-tooltip="Role & Permission">
      <i class="fa-solid fa-shield-halved"></i>
      <span>Role & Permission</span>
    </a>
    
    <a href="#" class="nav-item" data-tooltip="Notifications">
      <i class="fa-solid fa-bell"></i>
      <span>Notifications</span>
    </a>
    <a href="#" class="nav-item" data-tooltip="Notifications">
      <i class="fa-brands fa-supportnow"></i>
      <span>Support Tickets</span>
    </a>
    <a href="#" class="nav-item" data-tooltip="Notifications">
      <i class="fa-solid fa-arrow-up-right-dots"></i>
      <span>Security & Audit Log</span>
    </a>
    
    <a href="#" class="nav-item" data-tooltip="Settings">
      <i class="fa-solid fa-gear"></i>
      <span>Settings</span>
    </a>

  </nav>

  {{-- Sidebar Footer: Auth User --}}
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