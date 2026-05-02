{{-- ===== TOP NAVBAR ===== --}}
<header class="topbar">

  {{-- Sidebar Toggle --}}
  <button class="topbar-toggle" onclick="toggleSidebar()" title="Toggle Sidebar">
    <i class="fa-solid fa-bars"></i>
  </button>

  {{-- Breadcrumb --}}
  <div class="topbar-breadcrumb">
    <span>Massimotrava</span>
    <i class="fa-solid fa-chevron-right" style="font-size:9px"></i>
    <span class="current">@yield('page_title', 'Dashboard')</span>
  </div>

  <div class="topbar-spacer"></div>

  {{-- Search --}}
  <div class="search-bar">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="Search…" />
  </div>

  {{-- Action Buttons --}}
  <div class="topbar-actions">

    <button class="icon-btn" title="Messages">
      <i class="fa-regular fa-comment-dots"></i>
    </button>

    <button class="icon-btn" title="Notifications">
      <i class="fa-regular fa-bell"></i>
      <span class="notif-dot"></span>
    </button>

    {{-- Profile Dropdown --}}
    <div class="profile-dropdown">
      <button class="profile-btn" id="profileBtn" onclick="toggleDropdown()">
        <div class="profile-avatar">
          {{ strtoupper(substr(auth()->user()->name ?? 'Admin', 0, 2)) }}
        </div>
        <span>{{ auth()->user()->name ?? 'Admin' }}</span>
        <i class="fa-solid fa-chevron-down"></i>
      </button>

      <div class="dropdown-menu" id="dropdownMenu">
        <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
            <i class="fa-regular fa-user"></i> My Profile
        </a>
        <div class="dropdown-item"><i class="fa-regular fa-credit-card"></i> Billing</div>
        <div class="dropdown-item"><i class="fa-solid fa-gear"></i> Settings</div>
        <div class="dropdown-divider"></div>
        <a href="{{ route('admin.logout') }}"
           class="dropdown-item"
           style="color:#ef4444; text-decoration:none"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fa-solid fa-arrow-right-from-bracket"></i> Log out
        </a>
      </div>
    </div>

  </div>
</header>

{{-- Logout Form (hidden) --}}
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none">
  @csrf
</form>
