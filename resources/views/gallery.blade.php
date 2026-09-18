<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gallery | Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Raleway:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
</head>
<body>
    @include('component.nav')

    <section id="hero" class="welcome-hero hero-image-bg page-hero-single hero-bg-gallery">
        <div class="container welcome-hero-inner">
            <div class="hero-content-left hero-content-centered">
                <h1>Gallery</h1>
                <p>Project screenshots, event photos, and visual highlights. A glimpse into my work and experiences.</p>
                <div class="hero-stats-inline">
                    <div class="hero-stat-item"><span class="hero-stat-num">{{ $folders->count() }}</span><span class="hero-stat-label">Folders</span></div>
                    <div class="hero-stat-item"><span class="hero-stat-num">{{ $folders->pluck('category')->filter()->unique()->count() }}</span><span class="hero-stat-label">Categories</span></div>
                </div>
            </div>
        </div>
    </section>

    <section class="edu-section edu-section-quick-nav">
        <div class="container">
            <div class="edu-quick-nav">
                @foreach($folders->pluck('category')->filter()->unique() as $cat)
                <a href="#cat-{{ Str::slug($cat) }}">{{ $cat }}</a>
                @endforeach
            </div>
        </div>
    </section>

    @foreach($folders->groupBy('category') as $category => $catFolders)
    <section id="cat-{{ Str::slug($category ?: 'uncategorized') }}" class="edu-section welcome-solutions {{ $loop->iteration % 2 == 0 ? 'edu-section-bg-light' : '' }}">
        <div class="container">
            <div class="edu-section-header solutions-header">
                <h2 class="solutions-title">{{ $category ?: 'Uncategorized' }}</h2>
            </div>
            <div class="gallery-grid">
                @foreach($catFolders as $folder)
                <div class="gallery-item" style="cursor: pointer;" onclick="window.location.href='{{ route('gallery.folder', $folder) }}'">
                    @if($folder->cover_image_url)
                        <img src="{{ asset($folder->cover_image_url) }}" alt="{{ $folder->name }}">
                    @else
                        <div style="background: var(--bg-surface); height: 100%; min-height: 250px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                            <i class="fas fa-folder" style="font-size: 5rem; color: var(--color-primary); margin-bottom: 15px;"></i>
                            <span style="color: var(--text);">{{ $folder->items()->count() }} images</span>
                        </div>
                    @endif
                    <div class="gallery-item-overlay">
                        <div>
                            <h3 class="gallery-item-title">{{ $folder->name }}</h3>
                            <span class="gallery-item-cat"><i class="fas fa-images"></i> View Folder</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endforeach

    @if($folders->isEmpty())
    <section class="edu-section welcome-solutions">
        <div class="container">
            <div class="empty-state" style="text-align: center; padding: 4rem 2rem; background: #fff; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.05); width: 100%;">
                <i class="fas fa-images" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                <h3 style="margin-bottom: 0.5rem; color: #1e293b;">No gallery items found</h3>
                <p style="color: #64748b;">Check back later for updates!</p>
            </div>
        </div>
    </section>
    @endif

    <section class="statement-section">
        <div class="container">
            <h2>Like What You See?</h2>
            <p>Let's discuss how we can bring your vision to life. I'd love to hear about your project.</p>
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
