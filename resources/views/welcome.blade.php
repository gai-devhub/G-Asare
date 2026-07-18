<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gilbert Asare | Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Raleway:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    @include('component.theme-init')
</head>

<body>
    @include('component.nav')

    <!-- Hero Section - Image 1 style -->
    <section id="hero" class="welcome-hero hero-image-bg hero-bg-welcome">
        <div class="container welcome-hero-inner">
            <div class="hero-content-left">
                <h1>Software Engineer &<br>Cloud Enthusiast</h1>
                <p>Hi, I'm Gilbert Asare! I'm a Software Engineering student at Ghana Communication Technology University, an AWS Cloud Practitioner, and an Aspiring AWS Solutions Architect. Building scalable, secure, and modern digital experiences.</p>
                <div class="hero-cta-row">
                    <a href="{{ route('connect') }}" class="cta-button hero-cta">
                        Explore Solutions <i class="fas fa-arrow-right"></i>
                    </a>
                    <div class="hero-rating">
                        <span class="rating-score">4.9</span>
                        <i class="fas fa-star"></i>
                        <span class="rating-text">1,458 reviews</span>
                    </div>
                </div>
            </div>
            <div class="hero-image-right" style="display: flex; align-items: center; justify-content: center;">
                @if($webContent->hero_image_url)
                <img src="{{ asset($webContent->hero_image_url) }}" alt="Gilbert Asare" style="width: 100%; max-width: 500px; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); object-fit: cover; aspect-ratio: 4/5;">
                @else
                <img src="{{ asset('images/1783462869_file_00000000e6a4720a83f356bfb12d61a3.png') }}" alt="Gilbert Asare" style="width: 100%; max-width: 500px; border-radius: 24px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); object-fit: cover; aspect-ratio: 4/5;">
                @endif
            </div>
        </div>
        <div class="hero-partners">
            <div class="partners-slideshow-wrap">
                <div class="partners-slideshow">
                    <div class="partners-track">
                        <span class="partner-item"><i class="fab fa-laravel"></i> Laravel</span>
                        <span class="partner-item"><i class="fab fa-react"></i> React</span>
                        <span class="partner-item"><i class="fab fa-vuejs"></i> Vue.js</span>
                        <span class="partner-item"><i class="fab fa-php"></i> PHP</span>
                        <span class="partner-item"><i class="fab fa-js"></i> JavaScript</span>
                        <span class="partner-item"><i class="fab fa-node-js"></i> Node.js</span>
                        <span class="partner-item"><i class="fab fa-python"></i> Python</span>
                        <span class="partner-item"><i class="fab fa-git-alt"></i> Git</span>
                        <span class="partner-item"><i class="fab fa-aws"></i> AWS</span>
                        <span class="partner-item"><i class="fab fa-docker"></i> Docker</span>
                        {{-- Duplicate for seamless loop --}}
                        <span class="partner-item"><i class="fab fa-laravel"></i> Laravel</span>
                        <span class="partner-item"><i class="fab fa-react"></i> React</span>
                        <span class="partner-item"><i class="fab fa-vuejs"></i> Vue.js</span>
                        <span class="partner-item"><i class="fab fa-php"></i> PHP</span>
                        <span class="partner-item"><i class="fab fa-js"></i> JavaScript</span>
                        <span class="partner-item"><i class="fab fa-node-js"></i> Node.js</span>
                        <span class="partner-item"><i class="fab fa-python"></i> Python</span>
                        <span class="partner-item"><i class="fab fa-git-alt"></i> Git</span>
                        <span class="partner-item"><i class="fab fa-aws"></i> AWS</span>
                        <span class="partner-item"><i class="fab fa-docker"></i> Docker</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Showcasing Section - Image 1 gallery style -->
    <section class="welcome-showcasing">
        <div class="container">
            <div class="showcasing-header">
                <h3 class="showcasing-subtitle">Showcasing the Beauty and Innovation of</h3>
                <h2 class="showcasing-title">Development Through Stunning Visuals and Inspiring Projects</h2>
            </div>
            <style>
                .showcasing-header { text-align: center; margin-bottom: 30px; padding: 0 10px; }
                .showcasing-subtitle { font-size: 1.1rem; font-weight: 600; margin-bottom: 2px; opacity: 0.85; text-wrap: balance; }
                .showcasing-title { font-size: 2.2rem; font-weight: 800; line-height: 1.2; margin: 0; text-wrap: balance; }
                @media (max-width: 768px) {
                    .showcasing-subtitle { font-size: 0.85rem; margin-bottom: 4px; }
                    .showcasing-title { font-size: 1.25rem; }
                }
                .bento-gallery {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 20px;
                }
                .bento-item {
                    position: relative; 
                    border-radius: 20px; 
                    overflow: hidden; 
                    box-shadow: 0 10px 20px rgba(0,0,0,0.05);
                    cursor: pointer;
                    height: 280px;
                }
                .bento-img {
                    width: 100%; 
                    height: 100%; 
                    object-fit: cover; 
                    transition: transform 0.3s ease;
                }
                .bento-item:hover .bento-img {
                    transform: scale(1.05);
                }
                .bento-overlay {
                    position: absolute; 
                    bottom: 0; left: 0; right: 0; 
                    background: linear-gradient(transparent, rgba(0,0,0,0.9)); 
                    padding: 25px 20px 20px; 
                    pointer-events: none;
                }
                .bento-overlay p {
                    color: #fff; margin: 0; font-size: 0.95rem; font-weight: 500;
                }
                
                @media (max-width: 768px) {
                    .bento-gallery {
                        grid-template-columns: 1fr 1fr;
                        gap: 15px;
                    }
                    .bento-item-1 {
                        grid-column: 1 / 2;
                        height: 240px;
                        margin-top: 30px;
                    }
                    .bento-item-2 {
                        grid-column: 2 / 3;
                        height: 170px;
                    }
                    .bento-item-3 {
                        grid-column: 1 / -1;
                        height: 220px;
                    }
                    .bento-overlay {
                        display: none;
                    }
                }
            </style>
            
            <div class="bento-gallery">
                @foreach($galleryFolders as $index => $folder)
                <div class="bento-item bento-item-{{ ($index % 3) + 1 }}" onclick="openLightbox({{ $index }})">
                    <img src="{{ $folder->cover_image_url ? asset($folder->cover_image_url) : 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80' }}" alt="{{ $folder->name }}" class="bento-img">
                    <div class="bento-overlay">
                        <p>{{ $folder->description ?? $folder->name }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Lightbox Modal -->
    <div id="gallery-lightbox" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.95); z-index: 9999; justify-content: center; align-items: center; flex-direction: column;">
        <span onclick="closeLightbox()" style="position: absolute; top: 20px; right: 30px; font-size: 40px; color: white; cursor: pointer;">&times;</span>
        <div style="display: flex; align-items: center; justify-content: center; width: 100%; max-width: 1200px; padding: 0 20px; position: relative;">
            <span onclick="changeLightboxImage(-1)" style="color: white; font-size: 50px; cursor: pointer; user-select: none; position: absolute; left: 20px; z-index: 10;">&#10094;</span>
            <img id="lightbox-img" src="" style="max-height: 75vh; max-width: 100%; object-fit: contain; border-radius: 8px; transition: opacity 0.2s;">
            <span onclick="changeLightboxImage(1)" style="color: white; font-size: 50px; cursor: pointer; user-select: none; position: absolute; right: 20px; z-index: 10;">&#10095;</span>
        </div>
        <div id="lightbox-caption" style="color: white; margin-top: 15px; font-size: 1.2rem; text-align: center;"></div>
        <div style="display: flex; gap: 10px; margin-top: 20px; overflow-x: auto; max-width: 90%; padding-bottom: 10px;" id="lightbox-thumbnails">
            <!-- Thumbnails injected via JS -->
        </div>
    </div>

    <script>
        const galleryData = {!! json_encode($galleryFolders->map(function($folder) {
            return $folder->items->map(function($item) {
                return [
                    'src' => asset($item->image_url),
                    'caption' => $item->title ?? $item->description ?? '',
                ];
            });
        })->toArray()) !!};

        let currentCategory = 0;
        let currentIndex = 0;

        function openLightbox(categoryIndex) {
            currentCategory = categoryIndex;
            currentIndex = 0;
            document.getElementById('gallery-lightbox').style.display = 'flex';
            updateLightboxImage();
            document.body.style.overflow = 'hidden'; // prevent scrolling
            document.addEventListener('keydown', handleLightboxKeydown);
        }

        function closeLightbox() {
            document.getElementById('gallery-lightbox').style.display = 'none';
            document.body.style.overflow = 'auto'; // allow scrolling again
            document.removeEventListener('keydown', handleLightboxKeydown);
        }

        function changeLightboxImage(direction) {
            const catArray = galleryData[currentCategory];
            currentIndex += direction;
            if (currentIndex < 0) currentIndex = catArray.length - 1;
            if (currentIndex >= catArray.length) currentIndex = 0;
            updateLightboxImage();
        }

        function setLightboxImage(index) {
            currentIndex = index;
            updateLightboxImage();
        }

        function updateLightboxImage() {
            const data = galleryData[currentCategory][currentIndex];
            const img = document.getElementById('lightbox-img');
            const caption = document.getElementById('lightbox-caption');
            const thumbsContainer = document.getElementById('lightbox-thumbnails');
            
            img.style.opacity = 0.5;
            setTimeout(() => {
                img.src = data.src;
                caption.innerText = data.caption;
                img.style.opacity = 1;
            }, 100);

            let thumbsHTML = '';
            galleryData[currentCategory].forEach((item, idx) => {
                const opacity = idx === currentIndex ? '1' : '0.4';
                const border = idx === currentIndex ? '2px solid white' : '2px solid transparent';
                thumbsHTML += `<img src="${item.src}" onclick="setLightboxImage(${idx})" style="height: 60px; width: 90px; object-fit: cover; cursor: pointer; border-radius: 4px; opacity: ${opacity}; border: ${border}; transition: all 0.2s;">`;
            });
            thumbsContainer.innerHTML = thumbsHTML;
        }

        function handleLightboxKeydown(e) {
            if (e.key === 'ArrowLeft') changeLightboxImage(-1);
            else if (e.key === 'ArrowRight') changeLightboxImage(1);
            else if (e.key === 'Escape') closeLightbox();
        }
    </script>

    <!-- Our Impact in Numbers - Image 1 stats -->
    <section class="welcome-impact">
        <div class="container">
            <div class="impact-header">
                <h2 class="impact-title">My Journey in Numbers</h2>
                <p class="impact-desc">A snapshot of my continuous learning and hands-on experience building projects as a Software Engineering student.</p>
            </div>
            <div class="impact-stats">
                <div class="impact-stat"><span class="stat-num">{{ $githubStats['repositories'] ?? '142' }}</span><span class="stat-label">Repositories</span></div>
                <div class="impact-stat"><span class="stat-num">{{ $githubStats['commits'] ?? '1.2K+' }}</span><span class="stat-label">Commits This Year</span></div>
                <div class="impact-stat"><span class="stat-num">{{ $githubStats['collaborations'] ?? '89' }}</span><span class="stat-label">Collaborations</span></div>
                <div class="impact-stat"><span class="stat-num">{{ $githubStats['lines_of_code'] ?? '42k+' }}</span><span class="stat-label">Lines of Code</span></div>
            </div>
        </div>
    </section>

    <!-- Comprehensive Solutions - Image 2 style -->
    <section id="services" class="welcome-solutions">
        <div class="container">
            <div class="solutions-header">
                <h2 class="solutions-title">My Core <span class="title-light">Technical</span> Focus</h2>
                <p class="solutions-desc">Combining academic software engineering principles with certified AWS cloud knowledge to build complete, modern applications.</p>
            </div>
            <div class="solutions-cards">
                <div class="solution-card">
                    <div class="solution-card-body">
                        <h3>Web Development</h3>
                        <p>Custom websites and web applications built with modern technologies. Clean code, fast performance, and scalable architecture.</p>
                    </div>
                    <div class="solution-card-img">
                        <img src="https://images.unsplash.com/photo-1551650975-87deedd944c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Web Development">
                    </div>
                </div>
                <div class="solution-card">
                    <div class="solution-card-body">
                        <h3>Backend Development</h3>
                        <p>Building robust, secure, and scalable server-side applications and RESTful APIs using PHP, Laravel, and Node.js.</p>
                    </div>
                    <div class="solution-card-img">
                        <img src="https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Backend Server Code">
                    </div>
                </div>
                <div class="solution-card">
                    <div class="solution-card-body">
                        <h3>Cloud Architecture</h3>
                        <p>Designing secure, highly available, and cost-effective cloud solutions on AWS to support modern web applications.</p>
                    </div>
                    <div class="solution-card-img">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Consulting">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stay Updated - Image 2 news card -->
    <section class="welcome-news">
        <div class="container">
            <div class="news-card">
                <div class="news-content">
                    <h2>Stay Updated with <span class="title-light">Portfolio</span> News</h2>

                    <p>Get the latest insights on web development, design trends, and project updates. Join thousands of readers who stay ahead of the curve.</p>
                    <p>Subscribe to receive articles, tips, and exclusive content delivered straight to your inbox.</p>
                    <form id="news-subscribe-form" class="news-subscribe-form" action="{{ route('subscribe') }}" method="POST">
                        @csrf
                        <input type="email" name="email" placeholder="Your email address" required>
                        <button type="submit" class="subscribe-btn">Subscribe Now</button>
                    </form>
                    <div id="subscribe-message" style="display: none; margin-top: 10px; font-size: 0.9rem;"></div>
                </div>
                <div class="news-images">
                    <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Code" class="news-img-1">
                    <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Workspace" class="news-img-2">
                    <img src="https://images.unsplash.com/photo-1551650975-87deedd944c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Projects" class="news-img-3">
                </div>
            </div>
        </div>
    </section>

    <!-- Latest from the Blog - pulls from database -->
    @if(!empty($latestPosts))
    <section class="welcome-blog-posts">
        <div class="container">
            <h2 class="welcome-blog-title">Latest from the <span class="title-light">Blog</span></h2>
            <p class="welcome-blog-desc">Recent insights on web development, design trends, and best practices.</p>
            <div class="welcome-blog-grid">
                @foreach($latestPosts as $post)
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
                                <img src="{{ $post['author_avatar'] }}" alt="{{ $post['author'] }}">
                                <span>{{ $post['author'] }}  {{ $post['date'] }}</span>
                            </div>
                        </div>
                    </a>
                </article>
                @endforeach
            </div>
            <div class="welcome-blog-cta">
                <a href="{{ route('blog') }}" class="cta-button">View All Posts <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </section>
    @endif

    <!-- Bridging the Gap - Image 2 full-width -->
    <section class="welcome-video-hero hero-bg-welcome">
        <div class="container">
            <div>
                <h2 style="font-size: 2.5rem; margin-bottom: 10px;">Bridging the Gap</h2>
                <p style="color: rgba(255, 255, 255, 0.9); font-size: 1.25rem; max-width: 650px; margin: 0; line-height: 1.6;">Passionate about combining innovative software engineering with resilient AWS cloud infrastructure to build scalable, modern solutions.</p>
            </div>
            <a href="/about" class="video-cta-btn" style="white-space: nowrap; flex-shrink: 0;"><i class="fas fa-rocket"></i> My Journey So Far</a>
        </div>
    </section>

    <!-- Discover the Journey - Image 3 style -->
    <section class="welcome-journey">
        <div class="container journey-grid">
            <div class="journey-image">
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="My journey">
            </div>
            <div class="journey-content">
                <h2><span class="text-muted">Discover the Journey</span><br>That Built My Story</h2>
                <p>My journey began at Ghana Communication Technology University, where my fascination with software engineering took root. Today, as an AWS Certified Cloud Practitioner, I'm constantly learning and building—merging robust software architecture with scalable cloud solutions as I work toward becoming a Solutions Architect.</p>
                <div class="journey-stats">
                    <div class="journey-stat">
                        <div class="journey-stat-icon"><i class="fas fa-graduation-cap"></i></div>
                        <span class="journey-stat-num">BSc.</span>
                        <span class="journey-stat-label">Software Engineering</span>
                    </div>
                    <div class="journey-stat">
                        <div class="journey-stat-icon"><i class="fab fa-aws"></i></div>
                        <span class="journey-stat-num">AWS</span>
                        <span class="journey-stat-label">Cloud Practitioner</span>
                    </div>
                </div>
                <a href="{{ route('about') }}" class="cta-button journey-cta">Get in Touch <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

    <!-- Our Commitment - Image 3 mirrored -->
    <section class="welcome-commitment">
        <div class="container commitment-grid">
            <div class="commitment-content">
                <h2><span class="text-muted">My Dedication to</span><br>Continuous Innovation</h2>
                <p>As a student and an upcoming Solutions Architect, I am deeply committed to pushing the boundaries of what's possible. I focus on combining academic theory with hands-on cloud engineering to deliver scalable, secure, and future-proof solutions.</p>
                <div class="journey-stats">
                    <div class="journey-stat">
                        <div class="journey-stat-icon"><i class="fas fa-laptop-code"></i></div>
                        <span class="journey-stat-num" style="font-size: 1.5rem;">Code</span>
                        <span class="journey-stat-label">Clean & Maintainable</span>
                    </div>
                    <div class="journey-stat">
                        <div class="journey-stat-icon"><i class="fas fa-shield-alt"></i></div>
                        <span class="journey-stat-num" style="font-size: 1.5rem;">Cloud</span>
                        <span class="journey-stat-label">Secure Infrastructure</span>
                    </div>
                </div>
                <a href="{{ route('services') }}" class="cta-button commitment-cta">View Services <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="commitment-image">
                <img src="https://images.unsplash.com/photo-1551650975-87deedd944c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Commitment to excellence">
            </div>
        </div>
    </section>

    <!-- Cloud Capabilities Section -->
    <section class="cloud-capabilities" style="padding: 80px 0; background-color: var(--bg);">
        <div class="container" style="display: flex; flex-wrap: wrap; align-items: center; gap: 40px;">
            <div style="flex: 1; min-width: 300px;">
                <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Earth from space" style="width: 100%; border-radius: 12px; box-shadow: var(--shadow);">
            </div>
            <div style="flex: 1; min-width: 300px;">
                <span style="color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">AWS Infrastructure</span>
                <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 20px; color: var(--text);">Cloud Capabilities</h2>
                <p style="color: var(--text-muted); margin-bottom: 30px; line-height: 1.6;">Leveraging my AWS Certified Cloud Practitioner competencies, I design environments focusing on reliability, structural isolation, and optimized asset delivery.</p>
                
                <div style="display: flex; align-items: flex-start; margin-bottom: 20px;">
                    <div style="color: #f59e0b; font-size: 1.5rem; margin-right: 20px; min-width: 30px; text-align: center;"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <h4 style="margin: 0 0 5px; font-weight: 600; font-size: 1.1rem; color: var(--text);">IAM Security:</h4>
                        <p style="margin: 0; color: var(--text-muted);">Adhering strictly to least-privilege configurations.</p>
                    </div>
                </div>
                <div style="display: flex; align-items: flex-start; margin-bottom: 20px;">
                    <div style="color: #f59e0b; font-size: 1.5rem; margin-right: 20px; min-width: 30px; text-align: center;"><i class="fas fa-server"></i></div>
                    <div>
                        <h4 style="margin: 0 0 5px; font-weight: 600; font-size: 1.1rem; color: var(--text);">Compute & Storage:</h4>
                        <p style="margin: 0; color: var(--text-muted);">Practical provisioning of EC2 layers alongside S3 buckets.</p>
                    </div>
                </div>
                <div style="display: flex; align-items: flex-start;">
                    <div style="color: #f59e0b; font-size: 1.5rem; margin-right: 20px; min-width: 30px; text-align: center;"><i class="fas fa-network-wired"></i></div>
                    <div>
                        <h4 style="margin: 0 0 5px; font-weight: 600; font-size: 1.1rem; color: var(--text);">Database Management:</h4>
                        <p style="margin: 0; color: var(--text-muted);">Managed configuration of relational deployments via AWS RDS.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Competencies Section -->
    <section class="core-competencies" style="padding: 80px 0; background-color: var(--bg-surface);">
        <div class="container">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 50px; gap: 20px;">
                <h2 style="font-size: 2.2rem; font-weight: 700; color: var(--text); margin: 0; flex: 1; min-width: 300px;">Core Competencies & Expertise</h2>
                <p style="color: var(--text-muted); margin: 0; flex: 1; min-width: 300px; line-height: 1.6; border-left: 2px solid var(--border); padding-left: 20px;">Bridging the gap between robust software engineering practices and optimized cloud deployments.</p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                <div style="background: var(--bg); padding: 40px; border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border);">
                    <h3 style="font-size: 1.4rem; font-weight: 600; margin-bottom: 15px; color: var(--text);">Cloud Infrastructure</h3>
                    <p style="color: var(--text-muted); line-height: 1.6; font-size: 0.95rem;">Designing secure environments using IAM, configuring scalable compute instances with EC2, and managing object storage via S3. Focused on cloud cost-optimization and high availability.</p>
                </div>
                <div style="background: var(--bg); padding: 40px; border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border);">
                    <h3 style="font-size: 1.4rem; font-weight: 600; margin-bottom: 15px; color: var(--text);">Backend Engineering</h3>
                    <p style="color: var(--text-muted); line-height: 1.6; font-size: 0.95rem;">Building reliable application logic, RESTful APIs, and relational databases. Extensive architectural work with modern frameworks like Laravel and native PHP development.</p>
                </div>
                <div style="background: var(--bg); padding: 40px; border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border);">
                    <h3 style="font-size: 1.4rem; font-weight: 600; margin-bottom: 15px; color: var(--text);">Frontend Integration</h3>
                    <p style="color: var(--text-muted); line-height: 1.6; font-size: 0.95rem;">Developing responsive layouts and dynamic frontend logic. Emphasizing semantic HTML, modular CSS structure, and modern interactive web experiences.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Empowering Section - Image 3 dark -->
    <section class="welcome-empowering">
        <div class="container empowering-grid">
            <div class="empowering-media">
                <div class="empowering-main-img">
                    <img src="{{ asset('images/gilly.jpeg') }}" alt="Gilbert">
                    <div class="empowering-overlay-box">Clean code. Modern stack. Delivered on time.</div>
                </div>
                <div class="empowering-thumbnails">
                    <div class="thumb" style="overflow: hidden; border-radius: 12px; padding: 0; border: none;"><img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Code snippet" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'"></div>
                    
                    <div class="thumb" style="overflow: hidden; border-radius: 12px; padding: 0; border: none;"><img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Workspace setup" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'"></div>
                </div>
            </div>
            <div class="empowering-content">
                <h2><span class="text-muted">Empowering Your Business With</span><br>Expert Solutions</h2>
                <div class="empowering-rating"><span>4.9/5</span> <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                <ul class="empowering-list">
                    <li><i class="fas fa-check"></i> Responsive, accessible design for all devices</li>
                    <li><i class="fas fa-check"></i> Performance optimization and SEO best practices</li>
                    <li><i class="fas fa-check"></i> Ongoing support and maintenance</li>
                </ul>
                <p>I bring years of experience and a commitment to quality. Every project receives the same level of attention to detail and dedication to excellence.</p>
                <p>Let's build something great together. Get in touch to discuss your next project.</p>
                <a href="{{ route('connect') }}" class="cta-button journey-cta">Get in Touch <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </section>

    @include('component.footer')

    <a href="#hero" class="back-to-top" id="backToTop"><i class="fas fa-chevron-up"></i></a>

    <script src="{{ asset('js/style.js') }}?v={{ filemtime(public_path('js/style.js')) }}"></script>
</body>

</html>
