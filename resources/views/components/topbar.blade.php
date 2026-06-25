{{-- ===== TOP NAVBAR ===== --}}
<header class="topbar">

  {{-- Sidebar Toggle --}}
  <button class="topbar-toggle" onclick="toggleSidebar()" title="Toggle Sidebar">
    <i class="fa-solid fa-bars"></i>
  </button>

  {{-- Breadcrumb --}}
  <div class="topbar-breadcrumb">
      <span>Vyralabs</span>
      <i class="fa-solid fa-chevron-right" style="font-size:9px"></i>
      <span class="current">
          @if(View::hasSection('page_title_key'))
              {{ __('messages.' . View::getSection('page_title_key')) }}
          @else
              @yield('page_title', __('messages.sb_dashboard'))
          @endif
      </span>
  </div>

  <div class="topbar-spacer"></div>

  {{-- Search --}}
  <div class="search-bar">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="Search…" />
  </div>

  {{-- Action Buttons --}}
  <div class="topbar-actions">

    {{-- 🌐 DYNAMIC LANGUAGE SWITCHER WITH SVG FLAGS --}}
    <div class="lang-dropdown" style="position: relative; z-index: 999999 !important;">
      <button class="lang-btn" onclick="toggleLangMenu(event)" 
        style="background: var(--surface-hover, rgba(0, 0, 0, 0.04)); border: 1px solid var(--border, rgba(0, 0, 0, 0.08)); color: var(--text, #1e293b); padding: 6px 12px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; transition: 0.2s;">
        
        @if(App::getLocale() === 'it')
            <img src="https://flagcdn.com/16x12/it.png" width="16" height="12" alt="Italy Flag" style="border-radius: 2px; object-fit: cover;">
            <span>IT</span>
        @elseif(App::getLocale() === 'de')
            <img src="https://flagcdn.com/16x12/de.png" width="16" height="12" alt="Germany Flag" style="border-radius: 2px; object-fit: cover;">
            <span>DE</span>
        @else
            <img src="https://flagcdn.com/16x12/gb.png" width="16" height="12" alt="UK Flag" style="border-radius: 2px; object-fit: cover;">
            <span>EN</span>
        @endif
        
        <i class="fa-solid fa-chevron-down" style="font-size: 10px; color: var(--text-muted, #94a3b8); transition: transform 0.2s;" id="langChevron"></i>
      </button>
      
      <ul class="custom-lang-menu" id="customLangMenu" 
        style="display: none; position: absolute; right: 0; top: calc(100% + 6px); min-width: 140px; background: var(--surface, #ffffff); border: 1px solid var(--border, #e2e8f0); border-radius: 8px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); padding: 4px; list-style: none; margin: 0; z-index: 999;">
        
        <li>
          <a href="{{ route('lang.switch', 'it') }}" 
            style="color: {{ App::getLocale() === 'it' ? 'var(--accent, #6366f1)' : 'var(--text, #334155)' }}; font-size: 13px; padding: 8px 12px; border-radius: 6px; text-decoration: none; display: flex; align-items: center; gap: 8px; background: {{ App::getLocale() === 'it' ? 'rgba(99, 102, 241, 0.08)' : 'transparent' }}; font-weight: {{ App::getLocale() === 'it' ? '600' : 'normal' }}; transition: background 0.2s, color 0.2s;"
            onmouseover="this.style.background='{{ App::getLocale() === 'it' ? 'rgba(99, 102, 241, 0.12)' : 'var(--surface-hover, #f8fafc)' }}'"
            onmouseout="this.style.background='{{ App::getLocale() === 'it' ? 'rgba(99, 102, 241, 0.08)' : 'transparent' }}'">
            <img src="https://flagcdn.com/16x12/it.png" width="16" height="12" alt="Italiano" style="border-radius: 1px; object-fit: cover;">
            Italiano
          </a>
        </li>
        <li>
          <a href="{{ route('lang.switch', 'en') }}" 
            style="color: {{ App::getLocale() === 'en' ? 'var(--accent, #6366f1)' : 'var(--text, #334155)' }}; font-size: 13px; padding: 8px 12px; border-radius: 6px; text-decoration: none; display: flex; align-items: center; gap: 8px; background: {{ App::getLocale() === 'en' ? 'rgba(99, 102, 241, 0.08)' : 'transparent' }}; font-weight: {{ App::getLocale() === 'en' ? '600' : 'normal' }}; transition: background 0.2s, color 0.2s;"
            onmouseover="this.style.background='{{ App::getLocale() === 'en' ? 'rgba(99, 102, 241, 0.12)' : 'var(--surface-hover, #f8fafc)' }}'"
            onmouseout="this.style.background='{{ App::getLocale() === 'en' ? 'rgba(99, 102, 241, 0.08)' : 'transparent' }}'">
            <img src="https://flagcdn.com/16x12/gb.png" width="16" height="12" alt="English" style="border-radius: 1px; object-fit: cover;">
            English
          </a>
        </li>
        <li>
          <a href="{{ route('lang.switch', 'de') }}" 
            style="color: {{ App::getLocale() === 'de' ? 'var(--accent, #6366f1)' : 'var(--text, #334155)' }}; font-size: 13px; padding: 8px 12px; border-radius: 6px; text-decoration: none; display: flex; align-items: center; gap: 8px; background: {{ App::getLocale() === 'de' ? 'rgba(99, 102, 241, 0.08)' : 'transparent' }}; font-weight: {{ App::getLocale() === 'de' ? '600' : 'normal' }}; transition: background 0.2s, color 0.2s;"
            onmouseover="this.style.background='{{ App::getLocale() === 'de' ? 'rgba(99, 102, 241, 0.12)' : 'var(--surface-hover, #f8fafc)' }}'"
            onmouseout="this.style.background='{{ App::getLocale() === 'de' ? 'rgba(99, 102, 241, 0.08)' : 'transparent' }}'">
            <img src="https://flagcdn.com/16x12/de.png" width="16" height="12" alt="Deutsch" style="border-radius: 1px; object-fit: cover;">
            Deutsch
          </a>
        </li>
      </ul>
    </div>

    {{-- Notifications --}}
    <button class="icon-btn" title="Notifications">
      <i class="fa-regular fa-bell"></i>
      <span class="notif-dot"></span>
    </button>
    
    {{-- Theme Toggle Button --}}
    <button id="themeToggleBtn" style="background: var(--surface, #ffffff); border: 1px solid var(--border, #e2e8f0); color: var(--text, #1e293b); width: 36px; height: 36px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='var(--surface-hover, #f1f5f9)'" onmouseout="this.style.background='var(--surface, #ffffff)'">
        <i class="fa-solid fa-moon" id="themeIcon" style="color: var(--text, #1e293b);"></i>
    </button>

    {{-- Profile Dropdown Wrapper --}}
    <div class="profile-dropdown" style="position: relative; z-index: 99999 !important;">
      <button class="profile-btn" id="profileBtn" onclick="toggleDropdown()" style="display: flex; align-items: center; gap: 10px; background: transparent; border: none; cursor: pointer; padding: 5px 10px; border-radius: 12px; transition: 0.3s;">
        <div class="profile-avatar" style="position: relative; width: 35px; height: 35px;">
            @if(Auth::user() && Auth::user()->image)
              <img src="{{ asset('storage/' . Auth::user()->image) }}" 
                alt="Profile" 
                style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 1.5px solid var(--accent, #6366f1);"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
               
                <div style="display:none; width: 100%; height: 100%; border-radius: 50%; background: var(--bg, #334155); align-items: center; justify-content: center; border: 1.5px solid var(--border, #475569);">
                    <i class="fa-solid fa-user" style="font-size: 14px; color: var(--text-muted, #94a3b8);"></i>
                </div>
            @else
                <div style="width: 100%; height: 100%; border-radius: 50%; background: var(--bg, #334155); display: flex; align-items: center; justify-content: center; border: 1.5px solid var(--border, #475569);">
                    <i class="fa-solid fa-user" style="font-size: 14px; color: var(--text-muted, #94a3b8);"></i>
                </div>
            @endif
          <span style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; background: #10b981; border: 2px solid var(--surface, #0f172a); border-radius: 50%;"></span>
        </div>
        
        <span style="color: var(--text, #f1f5f9); font-size: 14px; font-weight: 500;">
            {{ auth()->user()->name ?? 'Admin User' }}
        </span>
        
        <i class="fa-solid fa-chevron-down" style="font-size: 12px; color: var(--text-muted, #94a3b8); margin-left: 5px;"></i>
      </button>

      <div class="dropdown-menu" id="dropdownMenu" style="right: 0 !important; left: auto !important; top: calc(100% + 10px) !important; width: 220px !important; background: var(--surface, #ffffff) !important; border: 1px solid var(--border, rgba(255,255,255,0.08)) !important; border-radius: 12px !important; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1) !important; padding: 6px !important; margin: 0 !important;">
        <a class="dropdown-item" href="{{ route('admin.profile.edit') }}" style="color: var(--text, #334155);">
            <i class="fa-regular fa-user"></i> My Profile
        </a>
        <div class="dropdown-item" style="color: var(--text, #334155);"><i class="fa-regular fa-credit-card"></i> Billing</div>
        <div class="dropdown-item" style="color: var(--text, #334155);"><i class="fa-solid fa-gear"></i> Settings</div>
        <div class="dropdown-divider" style="height: 1px; background: var(--border, rgba(0,0,0,0.06)); margin: 6px 0;"></div>
        <a href="{{ route('admin.logout') }}" class="dropdown-item" style="color:#ef4444; text-decoration:none"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fa-solid fa-arrow-right-from-bracket"></i> Log out
        </a>
      </div>
    </div>

  </div>
</header>

<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none">
  @csrf
</form>