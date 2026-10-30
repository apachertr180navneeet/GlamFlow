<header class="main-header">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark p-0">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <span class="logo-icon me-2"><i class="bx bx-spa"></i></span>
                <span class="logo-text">Aura <span class="logo-subtext" style="color: #D4AF37; font-weight: 800;">ERP</span></span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation" style="border: none; box-shadow: none;">
                <i class="bx bx-menu fs-1" style="color: #D4AF37;"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="#demo">Live Demo</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="#pricing">Pricing</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="#faqs">FAQs</a>
                    </li>
                </ul>
                <div class="nav-actions mt-3 mt-lg-0">
                    <a href="{{ route('admin.login') }}" class="btn btn-nav-cta">
                        <i class="bx bx-lock-open-alt me-1"></i> Launch App
                    </a>
                </div>
            </div>
        </nav>
    </div>
</header>
