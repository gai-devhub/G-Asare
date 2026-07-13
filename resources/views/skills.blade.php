<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Skills & Expertise | Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Raleway:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    @include('component.theme-init')
</head>
<body>
    @include('component.nav')

    <section id="hero" class="welcome-hero hero-image-bg page-hero-single hero-bg-skills">
        <div class="container welcome-hero-inner">
            <div class="hero-content-left hero-content-centered">
                <h1>Skills & Expertise</h1>
                <p>Technologies, frameworks, and tools I work with. A comprehensive overview of my technical capabilities across frontend, backend, and beyond.</p>
                <div class="hero-stats-inline">
                    <div class="hero-stat-item"><span class="hero-stat-num">{{ $skills->count() }}+</span><span class="hero-stat-label">Skills</span></div>
                    <div class="hero-stat-item"><span class="hero-stat-num">{{ $skills->pluck('category')->unique()->count() }}</span><span class="hero-stat-label">Categories</span></div>
                    <div class="hero-stat-item"><span class="hero-stat-num">{{ date('Y') - 2024 }}+</span><span class="hero-stat-label">Years</span></div>
                </div>
            </div>
        </div>
    </section>

    <section class="edu-section edu-section-quick-nav">
        <div class="container">
            <div class="edu-quick-nav">
                <a href="#frontend">Frontend</a>
                <a href="#backend">Backend</a>
                <a href="#tools">Tools</a>
                <a href="#soft">Soft Skills</a>
            </div>
        </div>
    </section>

    @php
        $grouped = $skills->groupBy('category');
        $categoryDescriptions = [
            'Frontend Development' => 'Building responsive, accessible, and performant user interfaces with modern frameworks and best practices.',
            'Backend & Database' => 'Server-side development, APIs, and data management with robust and scalable solutions.',
            'Tools & Platforms' => 'Development tools, version control, and cloud platforms I use daily.',
            'Soft Skills' => 'Collaboration, communication, and problem-solving abilities that complement my technical expertise.',
        ];
        $subCategoryIcons = [
            'Markup & Styling' => 'fab fa-html5',
            'JavaScript & Frameworks' => 'fab fa-js',
            'Backend Frameworks' => 'fab fa-laravel',
            'Databases' => 'fas fa-database',
            'DevOps & Version Control' => 'fab fa-git-alt',
        ];
        $categoryIds = [
            'Frontend Development' => 'frontend',
            'Backend & Database' => 'backend',
            'Tools & Platforms' => 'tools',
            'Soft Skills' => 'soft',
        ];
        $categoryTitles = [
            'Frontend Development' => ['Frontend', 'Development'],
            'Backend & Database' => ['Backend &', 'Database'],
            'Tools & Platforms' => ['Tools &', 'Platforms'],
            'Soft Skills' => ['Soft', 'Skills'],
        ];
    @endphp

    @foreach($grouped as $mainCategory => $categorySkills)
    <section id="{{ $categoryIds[$mainCategory] ?? Str::slug($mainCategory) }}" class="edu-section welcome-solutions">
        <div class="container">
            <div class="edu-section-header solutions-header">
                @if(isset($categoryTitles[$mainCategory]))
                <h2 class="solutions-title">{{ $categoryTitles[$mainCategory][0] }} <span class="title-light">{{ $categoryTitles[$mainCategory][1] }}</span></h2>
                @else
                <h2 class="solutions-title">{{ $mainCategory }}</h2>
                @endif
                <p class="solutions-desc">{{ $categoryDescriptions[$mainCategory] ?? '' }}</p>
            </div>
            <div class="skills-grid">
                @foreach($categorySkills->groupBy('sub_category') as $subCat => $subSkills)
                <div class="skill-category-card">
                    @if($subCat)
                    <div class="skill-category-header">
                        <div class="skill-category-icon"><i class="{{ $subCategoryIcons[$subCat] ?? 'fas fa-code' }}"></i></div>
                        <h3>{{ $subCat }}</h3>
                    </div>
                    @endif
                    <div class="skill-list">
                        @foreach($subSkills->sortBy('sort_order') as $skill)
                        <span class="skill-tag {{ $skill->percentage !== null ? 'has-level' : '' }}">
                            {{ $skill->name }}
                            @if($skill->percentage !== null)
                            <span class="skill-level">{{ $skill->percentage }}%</span>
                            @endif
                        </span>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endforeach

    @if($skills->isEmpty())
    <section class="edu-section welcome-solutions">
        <div class="container">
            <div class="empty-state" style="text-align: center; padding: 4rem 2rem; background: #fff; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.05); width: 100%;">
                <i class="fas fa-tools" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                <h3 style="margin-bottom: 0.5rem; color: #1e293b;">No skills found</h3>
                <p style="color: #64748b;">Check back later for updates!</p>
            </div>
        </div>
    </section>
    @endif

    <section class="statement-section">
        <div class="container">
            <h2>Let's Build Something Together</h2>
            <p>Have a project in mind? I'd love to hear about it. Let's connect and explore how we can work together.</p>
            <a href="{{ route('connect') }}" class="cta-button cta-margin-top"><i class="fas fa-envelope"></i> Get In Touch</a>
        </div>
    </section>

    @include('component.footer')
    <a href="#hero" class="back-to-top" id="backToTop"><i class="fas fa-chevron-up"></i></a>
    <script src="{{ asset('js/style.js') }}"></script>
    <script>
        document.querySelectorAll('.edu-quick-nav a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    </script>
</body>
</html>
