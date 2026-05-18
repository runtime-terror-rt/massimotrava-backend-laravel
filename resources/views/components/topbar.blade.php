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
      <button class="profile-btn" id="profileBtn" onclick="toggleDropdown()" style="display: flex; align-items: center; gap: 10px; background: transparent; border: none; cursor: pointer; padding: 5px 10px; border-radius: 12px; transition: 0.3s;">
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
        
        <span style="color: #f1f5f9; font-size: 14px; font-weight: 500;">
            {{ auth()->user()->name ?? 'Admin User' }}
        </span>
        
        <i class="fa-solid fa-chevron-down" style="font-size: 12px; color: #94a3b8; margin-left: 5px;"></i>
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
