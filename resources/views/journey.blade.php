<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Journey | Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Raleway:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
</head>
<body>
    @include('component.nav')

    <section id="hero" class="welcome-hero hero-image-bg page-hero-single hero-bg-journey">
        <div class="container welcome-hero-inner">
            <div class="hero-content-left hero-content-centered">
                <h1>My Journey</h1>
                <p>Career milestones, work experience, education, and key achievements. A timeline of my professional growth and development.</p>
                <div class="hero-stats-inline">
                    <div class="hero-stat-item"><span class="hero-stat-num">{{ date('Y') - 2024 }}+</span><span class="hero-stat-label">Years</span></div>
                    <div class="hero-stat-item"><span class="hero-stat-num">{{ $experiences->count() }}</span><span class="hero-stat-label">Milestones</span></div>
                    <div class="hero-stat-item"><span class="hero-stat-num">{{ $experiences->pluck('company')->unique()->count() }}</span><span class="hero-stat-label">Companies</span></div>
                </div>
            </div>
        </div>
    </section>

    <section id="journey" class="edu-section welcome-solutions">
        <div class="container">
            <div class="edu-section-header solutions-header">
                <h2 class="solutions-title">Career <span class="title-light">Timeline</span></h2>
                <p class="solutions-desc">Key moments that shaped my professional journey—from education to present day.</p>
            </div>
            <div class="edu-timeline">
                @forelse($experiences as $experience)
                <div class="edu-timeline-item">
                    <div class="edu-timeline-card">
                        <span class="journey-type-badge journey-type-work">Work Experience</span>
                        <span class="edu-timeline-year">{{ $experience->date_from }} – {{ $experience->date_to ?: 'Present' }}</span>
                        <h3>{{ $experience->role }}</h3>
                        <p class="edu-timeline-institution">{{ $experience->company }}</p>
                        <p class="edu-timeline-desc">{{ $experience->description }}</p>
                    </div>
                </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-route" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                        <h3>No journey milestones found</h3>
                        <p>Check back later for updates!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="statement-section">
        <div class="container">
            <h2>Ready to Write the Next Chapter?</h2>
            <p>I'm always open to new opportunities and collaborations. Let's connect and see how we can work together.</p>
            <a href="{{ route('connect') }}" class="cta-button cta-margin-top"><i class="fas fa-envelope"></i> Get In Touch</a>
        </div>
    </section>

    @include('component.footer')
    <a href="#hero" class="back-to-top" id="backToTop"><i class="fas fa-chevron-up"></i></a>
    <script src="{{ asset('js/style.js') }}"></script>
</body>
</html>
