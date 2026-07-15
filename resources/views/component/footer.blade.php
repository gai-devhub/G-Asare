{{-- Professional site-wide footer --}}
<footer class="site-footer">
    @php $profile = \App\Models\Profile::first(); @endphp
    <div class="container">
        <div class="site-footer-grid">
            <div class="site-footer-brand">
                <div class="site-footer-logo">
                    <span class="site-footer-logo-icon"><img src="{{ asset($profile->image_url ?? 'images/logo.png') }}" alt="Logo" class="nav-logo-img" style="border-radius: 50%; object-fit: cover;"></span>
                    <span class="logo-text" style="color: #ffffff !important;">{{ explode(' ', $profile->name ?? 'G - BASE')[0] }} <span>{{ implode(' ', array_slice(explode(' ', $profile->name ?? 'G - BASE'), 1)) }}</span></span>
                </div>
                <p class="site-footer-tagline">Building modern application solutions with clean code, creativity, and a commitment to excellence.</p>
                <div class="site-footer-social">
                    @if($profile && !empty($profile->social_links))
                        @foreach(array_slice($profile->social_links, 0, 4, true) as $platform => $url)
                            @php
                                $icon = 'fa-link';
                                $platformLower = strtolower($platform);
                                if (str_contains($platformLower, 'linkedin')) $icon = 'fa-linkedin-in';
                                elseif (str_contains($platformLower, 'github')) $icon = 'fa-github';
                                elseif (str_contains($platformLower, 'twitter') || str_contains($platformLower, 'x.com')) $icon = 'fa-twitter';
                                elseif (str_contains($platformLower, 'instagram')) $icon = 'fa-instagram';
                                elseif (str_contains($platformLower, 'facebook')) $icon = 'fa-facebook-f';
                                elseif (str_contains($platformLower, 'youtube')) $icon = 'fa-youtube';
                                elseif (str_contains($platformLower, 'dribbble')) $icon = 'fa-dribbble';
                                elseif (str_contains($platformLower, 'behance')) $icon = 'fa-behance';
                                elseif (str_contains($platformLower, 'codepen')) $icon = 'fa-codepen';
                                elseif (str_contains($platformLower, 'whatsapp')) $icon = 'fa-whatsapp';
                            @endphp
                            <a href="{{ $url }}" aria-label="{{ ucfirst($platform) }}" target="_blank" rel="noopener noreferrer"><i class="{{ $icon === 'fa-link' ? 'fas' : 'fab' }} {{ $icon }}"></i></a>
                        @endforeach
                    @else
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    @endif
                </div>
            </div>
            <div class="site-footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ route('services') }}">Services</a></li>
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="{{ route('projects') }}">Projects</a></li>
                    <li><a href="{{ route('edu&certs') }}">Education & Certs</a></li>
                    <li><a href="{{ route('blog') }}">News</a></li>
                    <li><a href="{{ route('connect') }}">Contact</a></li>
                </ul>
            </div>
            <div class="site-footer-col">
                <h4>Contact</h4>
                <ul class="site-footer-contact">
                    <li><i class="fas fa-envelope"></i><a href="mailto:gasare5326@gmail.com">gasare5326@gmail.com</a></li>
                    <li><i class="fas fa-phone"></i><a href="tel:+233599215326">+233 (59) 921-5326</a></li>
                    <li><i class="fas fa-map-marker-alt"></i><span>Accra, Ghana</span></li>
                </ul>
            </div>
        </div>
        <div class="site-footer-bottom">
            <p class="site-footer-copyright">© {{ date('Y') }} <a href="{{ route('login') }}" class="site-footer-copyright-link">G-BASE</a>. All rights reserved.</p>
            <div class="site-footer-legal">
                <a href="#">Privacy Policy</a>
                <span class="site-footer-sep">·</span>
                <a href="#">Terms of Use</a>
            </div>
        </div>
    </div>
</footer>
