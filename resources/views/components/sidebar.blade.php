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
    <a href="{{route('admin.get.lab.users')}}" class="nav-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}" data-tooltip="Analytics">
      <i class="fa-solid fa-chart-line"></i>
      <span>Laboratorian</span>
      {{-- <span class="nav-badge">New</span> --}}
    </a>
    <a href="{{route('admin.reports.index')}}" class="nav-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}" data-tooltip="Reports">
      <i class="fa-solid fa-chart-bar"></i>
      <span>Reports</span>
    </a>

    <div class="nav-section-label">Management</div>

    <a href="{{route('admin.get.users')}}" class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}" data-tooltip="Users">
      <i class="fa-solid fa-users"></i>
      <span>Users</span>
      {{-- <span class="nav-badge">3</span> --}}
    </a>
    <a href="#" class="nav-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}" data-tooltip="Products">
      <i class="fa-solid fa-table-cells-large"></i>
      <span>Category</span>
    </a>
    <a href="#" class="nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}" data-tooltip="Orders">
      <i class="fa-solid fa-table-list"></i>
      {{-- <i class="fa-solid fa-bag-shopping"></i> --}}
      <span>Sub Category</span>
    </a>
    <a href="{{route('admin.kits.index')}}" class="nav-item {{ request()->routeIs('admin.transactions*') ? 'active' : '' }}" data-tooltip="Transactions">
      <i class="fa-solid fa-arrows-left-right"></i>
      <span>Kit Manager</span>
    </a>

    <div class="nav-section-label">System</div>

    <a href="#" class="nav-item" data-tooltip="Privecy Policy">
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
    <a href="#" class="nav-item" data-tooltip="Help">
      <i class="fa-solid fa-circle-question"></i>
      <span>Help & Docs</span>
    </a>

  </nav>

  {{-- Sidebar Footer: Auth User --}}
  <div class="sidebar-footer">
    <div class="user-card">
      <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'Admin', 0, 2)) }}</div>
      <div class="user-info">
        <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
        <div class="user-role">{{ auth()->user()->role ?? 'Administrator' }}</div>
      </div>
    </div>
  </div>

</aside>
