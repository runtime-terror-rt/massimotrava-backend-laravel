<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', "Vyralabs | The World's Easiest Performance Lab Test")</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght=0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    
    @stack('styles')
</head>
<body>

    <nav class="navbar" id="navbar">
        <div class="container nav-inner">
            <a href="{{ url('/') }}" class="logo">
                <div class="dot"></div>Vyralabs
            </a>
            <div class="nav-links" id="navLinks">
                <a href="{{ url('/') }}#how"><i class="fa-solid fa-layer-group"></i>How It Works</a>
                <a href="{{ url('/') }}#biomarkers"><i class="fa-solid fa-dna"></i>Biomarkers</a>
                <a href="{{ url('/') }}#pricing"><i class="fa-solid fa-tags"></i>Pricing</a>
                <a href="{{ url('/') }}"><i class="fa-solid fa-shield-halved"></i>Privacy Framework</a>
            </div>
            <div class="nav-actions">
                <a href="{{ route('login') }}" class="btn btn-ghost">Client Login</a>
                <a href="{{ url('/') }}#pricing" class="btn btn-primary">Order Test Kit</a>
                <button class="nav-toggle" id="navToggle"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="vyr-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="{{ url('/') }}" class="logo"><div class="dot"></div>Vyralabs</a>
                    <p>Next-generation diagnostic platform offering preventative biomarker insight tracking for optimization frameworks.</p>
                    <div class="footer-socials">
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Get in Vyralabs</h4>
                    <ul>
                        <li><a href="{{ url('/') }}#pricing">Start Testing</a></li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Explore</h4>
                    <ul>
                        <li><a href="{{ url('/') }}#how">Biomarkers We Test</a></li>
                        <li><a href="{{ url('/') }}">Terms & Conditions</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Corporate</h4>
                    <ul>
                        <li><a href="mailto:support@vyralabs.com">Support</a></li>
                        <li><a href="{{ url('/') }}">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-disclaimer">
                    <strong>Medical Disclaimer:</strong> Vyralabs does not provide clinical medical advice. All material, data, and insights provided are strictly for general preventive tracking.
                </div>
            </div>
            <div class="footer-bottom">
                <div class="footer-copy">&copy; {{ date('Y') }} Vyralabs. All rights reserved. Registered in Estonia.</div>
            </div>
        </div>
    </footer>

    <script>
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 60);
        });
        
        const navToggle = document.getElementById('navToggle');
        const navLinks = document.getElementById('navLinks');
        if(navToggle) {
            navToggle.addEventListener('click', () => {
                navLinks.classList.toggle('active');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>