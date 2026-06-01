<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'Vyralabs Admin')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/favicon.png') }}">
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,wght=0,300;0,400;0,500;1,300&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}"/>
    
    <style>
      .profile-dropdown .dropdown-menu {
          display: none !important;
          position: absolute !important;
          opacity: 1 !important;
          pointer-events: auto !important;
          visibility: visible !important;
      }
      .profile-dropdown .dropdown-menu.show-menu {
          display: block !important;
      }

      /* Dynamic Theme Matched Modal Styling */
      .modal-content {
          background: var(--surface-modal) !important;
          border: 1px solid var(--border) !important;
          color: var(--text-main) !important;
      }
      .modal-header { border-bottom: 1px solid var(--border) !important; }
      .modal-footer { border-top: 1px solid var(--border) !important; }
      
      .form-control, .form-select {
          background: var(--surface-input) !important;
          border: 1px solid var(--border) !important;
          color: var(--text-main) !important;
      }
      .form-control:focus, .form-select:focus {
          background: var(--surface-input) !important;
          color: var(--text-main) !important;
          border-color: var(--accent) !important;
          box-shadow: 0 0 0 3px var(--sb-hover-bg) !important;
      }

      body {
          background-color: var(--bg-main);
          color: var(--text-main);
          transition: background-color 0.3s ease, color 0.3s ease;
          font-family: 'Inter', sans-serif;
      }
    </style>
    @stack('styles')
</head>
<body>

<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

@include('components.sidebar')

<div class="main-wrapper" id="mainWrapper">
    @include('components.topbar')
    
    <main class="content">
        @yield('content')
    </main>
</div>

@stack('modals')

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/admin.js') }}"></script>
<script>
  function toggleLangMenu(event) {
    event.stopPropagation();
    const menu = document.getElementById('customLangMenu');
    const chevron = document.getElementById('langChevron');
    
    if (menu.style.display === 'none' || menu.style.display === '') {
      menu.style.display = 'block';
      chevron.style.transform = 'rotate(180deg)';
    } else {
      menu.style.display = 'none';
      chevron.style.transform = 'rotate(0deg)';
    }
  }

  document.addEventListener('click', function() {
    const menu = document.getElementById('customLangMenu');
    const chevron = document.getElementById('langChevron');
    if (menu) {
      menu.style.display = 'none';
      if(chevron) chevron.style.transform = 'rotate(0deg)';
    }
  });

  // Theme Sync Handler
  document.addEventListener('DOMContentLoaded', function () {
    const themeToggleBtn = document.getElementById('themeToggleBtn');
    const themeIcon = document.getElementById('themeIcon');
    const body = document.body;

    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'light') {
        body.classList.add('light-mode');
        if(themeIcon) themeIcon.classList.replace('fa-moon', 'fa-sun'); 
    } else {
        body.classList.remove('light-mode');
        if(themeIcon) themeIcon.classList.replace('fa-sun', 'fa-moon'); 
    }

    if(themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function () {
            body.classList.toggle('light-mode');
            
            if (body.classList.contains('light-mode')) {
                localStorage.setItem('theme', 'light');
                if(themeIcon) themeIcon.classList.replace('fa-moon', 'fa-sun');
            } else {
                localStorage.setItem('theme', 'dark');
                if(themeIcon) themeIcon.classList.replace('fa-sun', 'fa-moon');
            }
        });
    }
  });
</script>
@stack('scripts')

</body>
</html>