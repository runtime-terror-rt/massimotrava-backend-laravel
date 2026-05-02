<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'Massimotrava Admin')</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}"/>
  @stack('styles')
</head>
<body>

{{-- Mobile Overlay --}}
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

{{-- Sidebar --}}
@include('components.sidebar')

{{-- Main Wrapper --}}
<div class="main-wrapper" id="mainWrapper">

  {{-- Top Navbar --}}
  @include('components.topbar')

  {{-- Page Content --}}
  <main class="content">
    @yield('content')
  </main>

</div>

<script src="{{ asset('js/admin.js') }}"></script>

@stack('scripts')
</body>
</html>
