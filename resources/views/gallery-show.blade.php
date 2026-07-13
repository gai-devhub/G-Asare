<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $galleryFolder->name }} | Gallery | Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Raleway:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    @include('component.theme-init')
</head>
<body>
    @include('component.nav')

    <section id="hero" class="welcome-hero hero-image-bg page-hero-single hero-bg-gallery" style="min-height: 40vh; padding-top: 150px; padding-bottom: 50px;">
        <div class="container welcome-hero-inner">
            <div class="hero-content-left hero-content-centered">
                <p style="margin-bottom: 10px;">
                    <a href="{{ route('gallery') }}" style="color: rgba(255,255,255,0.7); text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> Back to Gallery
                    </a>
                </p>
                <h1>{{ $galleryFolder->name }}</h1>
                <p>Category: {{ $galleryFolder->category ?? 'Uncategorized' }}</p>
                <div class="hero-stats-inline">
                    <div class="hero-stat-item"><span class="hero-stat-num">{{ $items->count() }}</span><span class="hero-stat-label">Images</span></div>
                </div>
            </div>
        </div>
    </section>

    <section class="edu-section welcome-solutions">
        <div class="container">
            <div class="gallery-grid">
                @forelse($items as $item)
                <div class="gallery-item">
                    <img src="{{ asset($item->image_url) }}" alt="{{ $item->title ?? 'Gallery Image' }}">
                    <div class="gallery-item-overlay">
                        <div>
                            @if($item->title)
                                <h3 class="gallery-item-title">{{ $item->title }}</h3>
                            @endif
                            @if($item->description)
                                <span class="gallery-item-cat">{{ $item->description }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state-container" style="grid-column: 1 / -1; padding: 4rem 2rem; text-align: center;">
                    <i class="fas fa-images" style="font-size: 3rem; color: var(--gray); margin-bottom: 1rem; opacity: 0.5;"></i>
                    <p class="text-muted" style="font-size: 1.1rem; margin: 0;">No images in this folder yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

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
</body>
</html>
