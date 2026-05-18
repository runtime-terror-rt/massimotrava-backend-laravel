{{-- ===== SIDEBAR ===== --}}
<aside class="sidebar" id="sidebar">

  {{-- Brand --}}
  <div class="sidebar-brand">
    <div class="brand-icon"><i class="fa-solid fa-bolt"></i></div>
    <span class="brand-name">Massimo</span>
  </div>

  {{-- Navigation --}}
  <nav class="sidebar-nav">

    <div class="nav-section-label">Main</div>

    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
      <i class="fa-solid fa-house-chimney"></i>
      <span>Dashboard</span>
    </a>
    <a href="{{route('admin.labs.index')}}" class="nav-item {{ request()->routeIs('admin.labs.index') ? 'active' : '' }}" data-tooltip="Labs">
      <i class="fa-solid fa-chart-line"></i>
      <span>Lab</span>
      {{-- <span class="nav-badge">New</span> --}}
    </a>
    <a href="{{route('admin.get.lab.users')}}" class="nav-item {{ request()->routeIs('admin.get.lab.users') ? 'active' : '' }}" data-tooltip="Laboratorian">
      <i class="fa-solid fa-chart-line"></i>
      <span>Laboratorian</span>
      {{-- <span class="nav-badge">New</span> --}}
    </a>
    <a href="{{route('admin.reports.index')}}" class="nav-item {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}" data-tooltip="Reports">
      <i class="fa-solid fa-chart-bar"></i>
      <span>Reports</span>
    </a>

    <div class="nav-section-label">Management</div>

    <a href="{{route('admin.get.users')}}" class="nav-item {{ request()->routeIs('admin.get.users*') ? 'active' : '' }}" data-tooltip="Users">
      <i class="fa-solid fa-users"></i>
      <span>Users</span>
      {{-- <span class="nav-badge">3</span> --}}
    </a>
    <a href="{{route('admin.category.index')}}" class="nav-item {{ request()->routeIs('admin.category.index*') ? 'active' : '' }}" data-tooltip="Category">
      <i class="fa-solid fa-table-cells-large"></i>
      <span>Category</span>
    </a>
    <a href="{{route('admin.biomarker-subcategories.index')}}" class="nav-item {{ request()->routeIs('admin.biomarker-subcategories.index*') ? 'active' : '' }}" data-tooltip="Sub Category">
      <i class="fa-solid fa-table-list"></i>
      {{-- <i class="fa-solid fa-bag-shopping"></i> --}}
      <span>Sub Category</span>
    </a>
    <a href="{{route('admin.kits.index')}}" class="nav-item {{ request()->routeIs('admin.kits.index*') ? 'active' : '' }}" data-tooltip="Kit Manager">
      <i class="fa-solid fa-arrows-left-right"></i>
      <span>Kit Manager</span>
    </a>

    <div class="nav-section-label">System</div>

    <a href="{{route('admin.privacy-policy.index')}}" class="nav-item" data-tooltip="Privecy Policy">
      <i class="fa-solid fa-bell"></i>
      <span>Privecy Policy</span>
    </a>
    <a href="#" class="nav-item" data-tooltip="FAQ">
      <i class="fa-solid fa-bell"></i>
      <span>FAQ</span>
    </a>
    <a href="#" class="nav-item" data-tooltip="Courier">
      <i class="fa-solid fa-bell"></i>
      <span>Courier</span>
    </a>
    <a href="#" class="nav-item" data-tooltip="Notifications">
      <i class="fa-solid fa-bell"></i>
      <span>Notifications</span>
    </a>
    <a href="#" class="nav-item" data-tooltip="Settings">
      <i class="fa-solid fa-gear"></i>
      <span>Settings</span>
    </a>

  </nav>

  {{-- Sidebar Footer: Auth User --}}
  <div class="sidebar-footer">
    <div class="user-card">
      <div class="profile-avatar" style="position: relative; width: 35px; height: 35px;">
          @if(Auth::user()->image)
              <img src="{{ Storage::url(Auth::user()->image) }}" alt="Profile" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 1.5px solid #6366f1;">
          @else
              <div style="width: 100%; height: 100%; border-radius: 50%; background: #334155; display: flex; align-items: center; justify-content: center; border: 1.5px solid #475569;">
                  <i class="fa-solid fa-user" style="font-size: 14px; color: #94a3b8;"></i>
              </div>
          @endif
          <span style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; background: #10b981; border: 2px solid #0f172a; border-radius: 50%;"></span>
      </div>
      <div class="user-info">
        <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
        <div class="user-role">{{ auth()->user()->role ?? 'Administrator' }}</div>
      </div>
    </div>
  </div>

</aside>
