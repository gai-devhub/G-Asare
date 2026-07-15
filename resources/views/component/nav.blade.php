{{-- Floating white nav bar - capsule style with logo, links, search, CTA --}}
<header class="main-nav nav-floating">
    @php $profile = \App\Models\Profile::first(); @endphp
    <div class="nav-inner">
        <a href="{{ url('/') }}" class="nav-logo">
            <span class="logo-icon"><img src="{{ asset($profile->image_url ?? 'images/logo.png') }}" alt="Logo" class="nav-logo-img" style="border-radius: 50%; object-fit: cover;"></span>
            <span class="logo-text">{{ explode(' ', $profile->name ?? 'G - BASE')[0] }} <span>{{ implode(' ', array_slice(explode(' ', $profile->name ?? 'G - BASE'), 1)) }}</span></span>
        </a>
        <ul class="nav-links" id="nav-links">
            <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('services') }}" class="{{ request()->is('services') ? 'active' : '' }}">Services</a></li>
            <li class="nav-dropdown">
                <a href="{{ route('about') }}" class="nav-link-dropdown {{ request()->is('about', 'edu*', 'skills', 'journey', 'gallery') ? 'active' : '' }}">About <i class="fas fa-chevron-down"></i></a>
                <ul class="nav-dropdown-menu">
                    <li><a href="{{ route('about') }}">Me</a></li>
                    <li><a href="{{ route('projects') }}">Projects</a></li>
                    <li><a href="{{ route('edu&certs') }}">Education & Certifications</a></li>
                    <li><a href="{{ route('skills') }}">Skills</a></li>
                    <li><a href="{{ route('journey') }}">My Journey</a></li>
                    <li><a href="{{ route('gallery') }}">Gallery</a></li>
                </ul>
            </li>
            <li><a href="{{ route('blog') }}" class="{{ request()->is('news*') ? 'active' : '' }}">My Blog</a></li>
            <li><a href="{{ route('connect') }}" class="{{ request()->is('connect') ? 'active' : '' }}">Contact</a></li>
            <li class="nav-dropdown-theme">
                <button class="theme-toggle theme-toggle-dropdown" aria-label="Toggle theme" title="Toggle dark/light mode" data-theme-trigger>
                    <i class="fas fa-moon" data-icon-dark></i>
                    <i class="fas fa-sun" data-icon-light></i>
                </button>
            </li>
        </ul>
        <div class="nav-actions">
            <button class="theme-toggle theme-toggle-desktop" aria-label="Toggle theme" title="Toggle dark/light mode" data-theme-trigger>
                <i class="fas fa-moon" data-icon-dark></i>
                <i class="fas fa-sun" data-icon-light></i>
            </button>
            <a href="{{ route('connect') }}" class="nav-cta">Get Started <i class="fas fa-arrow-right"></i></a>
        </div>
        <button class="menu-toggle" id="menu-toggle" type="button" aria-label="Open navigation menu" aria-expanded="false">
            <i class="fas fa-bars" aria-hidden="true"></i>
        </button>
    </div>
</header>
