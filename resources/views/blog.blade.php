<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insights & News | Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    @include('component.theme-init')
</head>
<body class="blog-page">
    @include('component.nav')

    <!-- Blog Hero -->
    <section class="blog-hero blog-hero-image hero-bg-gilly">
        <div class="container">
            <h1>{{ $heroTitle ?? 'Web Development Insights, Trends and News' }}</h1>
            <p>{{ $heroDescription ?? 'Stay informed on the latest web technologies, framework updates, design trends, and development best practices.' }}</p>
        </div>
    </section>

    <!-- Blog Content -->
    <main class="blog-content">
    <div class="container">
        <div class="blog-layout">
            <div class="article-feed">
                @forelse($posts ?? [] as $post)
                <article class="article-card">
                    <a href="{{ route('blog.post', ['slug' => $post['slug']]) }}" class="article-card-link">
                        <div class="article-card-img">
                            <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}">
                        </div>
                        <div class="article-card-body">
                            <span class="category-tag">{{ $post['category'] }}</span>
                            <h3>{{ $post['title'] }}</h3>
                            <p>{{ $post['excerpt'] }}</p>
                            <div class="article-meta">
                                <img src="{{ $post['author_image'] }}" alt="{{ $post['author'] }}">
                                <span>{{ $post['author'] }}  {{ $post['date'] }}</span>
                            </div>
                        </div>
                    </a>
                </article>
                @empty
                    <div class="empty-state" style="text-align: center; padding: 4rem 2rem; background: #fff; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.05); width: 100%;">
                        <i class="fas fa-newspaper" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                        <h3 style="margin-bottom: 0.5rem; color: #1e293b;">No blog posts yet</h3>
                        <p style="color: #64748b;">Check back later for new insights and news!</p>
                    </div>
                @endforelse
            </div>

            <aside class="blog-sidebar">
                <div class="sidebar-block">
                    <h3>{{ $sidebarTitle ?? 'Driving Digital Solutions for a Stronger Online Future' }}</h3>
                    <p>{{ $sidebarDescription ?? 'I help businesses build web experiences that convert visitors into customers and scale with growth.' }}</p>
                    <div class="stats-row">
                        <div class="stat-box"><strong>{{ \App\Models\Setting::get('blog_sidebar_stat1_value', '5+') }}</strong><span>{{ \App\Models\Setting::get('blog_sidebar_stat1_label', 'Years experience') }}</span></div>
                        <div class="stat-box"><strong>{{ \App\Models\Setting::get('blog_sidebar_stat2_value', '80+') }}</strong><span>{{ \App\Models\Setting::get('blog_sidebar_stat2_label', 'Happy clients') }}</span></div>
                        <div class="stat-box"><strong>{{ \App\Models\Setting::get('blog_sidebar_stat3_value', '98%') }}</strong><span>{{ \App\Models\Setting::get('blog_sidebar_stat3_label', 'Client satisfaction') }}</span></div>
                        <div class="stat-box"><strong>{{ \App\Models\Setting::get('blog_sidebar_stat4_value', '50+') }}</strong><span>{{ \App\Models\Setting::get('blog_sidebar_stat4_label', 'Projects delivered') }}</span></div>
                    </div>
                </div>
                
                @if(\App\Models\Setting::get('blog_sidebar_video'))
                <div class="video-container" style="border-radius: 12px; overflow: hidden; margin-top: 20px;">
                    <video src="{{ asset(\App\Models\Setting::get('blog_sidebar_video')) }}" controls style="width: 100%; display: block; border-radius: 12px;"></video>
                </div>
                @else
                <div class="video-placeholder">
                    <i class="fas fa-play"></i>
                </div>
                @endif
            </aside>
        </div>
    </div>
    </main>

    @include('component.footer')

    <a href="#" class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </a>

    <script src="{{ asset('js/style.js') }}"></script>
</body>
</html>
