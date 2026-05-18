<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'Massimotrava Admin')</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet"/>
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
        /* Global Modal Styling */
        .modal-content {
            background: #1e293b;
            border: 1px solid #334155;
            color: white;
        }
        .modal-header { border-bottom: 1px solid #334155; }
        .modal-footer { border-top: 1px solid #334155; }
        .form-control, .form-select {
            background: #0f172a;
            border: 1px solid #334155;
            color: white;
        }
        .form-control:focus, .form-select:focus {
            background: #0f172a;
            color: white;
            border-color: #6366f1;
            box-shadow: none;
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
@stack('scripts')

</body>
</html>