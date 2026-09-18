<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post['title'] }} | Portfolio Blog</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($post['excerpt'] ?? $post['content'] ?? ''), 160) }}">
    <meta property="og:title" content="{{ $post['title'] }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($post['excerpt'] ?? $post['content'] ?? ''), 200) }}">
    <meta property="og:image" content="{{ $post['image'] }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    @include('component.favicon')
</head>
<body class="blog-post-page">
    @include('component.nav')

    <section class="blog-hero blog-hero-image hero-bg-gilly">
        <div class="container">
            <a href="{{ route('blog') }}" class="post-back"><i class="fas fa-arrow-left"></i> Back to Blog</a>
        </div>
    </section>

    <main class="blog-post-content">
    <div class="container">
        <article class="post-detail">
            <div class="post-detail-hero">
                <span class="category-tag">{{ $post['category'] }}</span>
                <h1>{{ $post['title'] }}</h1>
                <div class="article-meta">
                    <img src="{{ $post['author_image'] }}" alt="{{ $post['author'] }}">
                    <span>{{ $post['author'] }}  {{ $post['date'] }}</span>
                </div>
            </div>

            <div class="post-detail-img">
                <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}">
            </div>

            <div class="post-detail-body">
                {!! $post['content'] !!}
                @if(!empty($post['signature']))
                <p class="post-signature">{{ $post['signature'] }}</p>
                @endif
            </div>
        </article>
    </div>
    </main>

    @include('component.footer')

    <a href="#" class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </a>

    <script src="{{ asset('js/style.js') }}"></script>
</body>
</html>
