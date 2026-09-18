<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Education & Certifications | Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Raleway:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
</head>
<body>
    @include('component.nav')

    <!-- Hero Section -->
    <section id="hero" class="welcome-hero hero-image-bg page-hero-single hero-bg-educerts">
        <div class="container welcome-hero-inner">
            <div class="hero-content-left hero-content-centered">
                <h1>Education & Certifications</h1>
                <p>My academic journey, professional certifications, awards, and key documents. A comprehensive overview of my qualifications and achievements.</p>
                <div class="hero-stats-inline">
                    <div class="hero-stat-item"><span class="hero-stat-num" id="eduCount">{{ $educations->count() }}</span><span class="hero-stat-label">Education</span></div>
                    <div class="hero-stat-item"><span class="hero-stat-num" id="certCount">{{ $certifications->count() }}</span><span class="hero-stat-label">Certifications</span></div>
                    <div class="hero-stat-item"><span class="hero-stat-num" id="awardCount">{{ $awards->count() }}</span><span class="hero-stat-label">Awards</span></div>
                    <div class="hero-stat-item"><span class="hero-stat-num" id="docCount">{{ $documents->count() }}</span><span class="hero-stat-label">Documents</span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Navigation -->
    <section class="edu-section edu-section-quick-nav">
        <div class="container">
            <div class="edu-quick-nav">
                <a href="#education">Education</a>
                <a href="#certifications">Certifications</a>
                <a href="#awards">Awards</a>
                <a href="#documents">Documents</a>
            </div>
        </div>
    </section>

    <!-- Education History -->
    <section id="education" class="edu-section welcome-solutions">
        <div class="container">
            <div class="edu-section-header solutions-header">
                <h2 class="solutions-title">Education <span class="title-light">History</span></h2>
                <p class="solutions-desc">My academic background and formal education that laid the foundation for my career in technology and development.</p>
            </div>
            <div class="edu-timeline">
                @forelse($educations as $education)
                <div class="edu-timeline-item">
                    <div class="edu-timeline-card">
                        <span class="edu-timeline-year">{{ $education->date_from }} – {{ $education->date_to ?: 'Present' }}</span>
                        <h3>{{ $education->degree }}</h3>
                        <p class="edu-timeline-institution">{{ $education->institution }}</p>
                        <p class="edu-timeline-desc">{{ $education->description }}</p>
                    </div>
                </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-graduation-cap" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                        <h3>No education records found</h3>
                        <p>Check back later for updates!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Certifications -->
    <section id="certifications" class="edu-section welcome-solutions edu-section-bg-light">
        <div class="container">
            <div class="edu-section-header solutions-header">
                <h2 class="solutions-title">Professional <span class="title-light">Certifications</span></h2>
                <p class="solutions-desc">Industry-recognized certifications that validate my expertise and commitment to staying current with technology.</p>
            </div>
            <div class="cert-grid">
                @forelse($certifications as $cert)
                <div class="cert-card">
                    <div class="cert-card-icon"><i class="{{ $cert->icon ?? 'fas fa-certificate' }}"></i></div>
                    <div class="cert-card-body">
                        <h3>{{ $cert->name }}</h3>
                        <p class="cert-card-issuer">{{ $cert->issuer }}</p>
                        <span class="cert-card-date">{{ $cert->date_issued }}</span>
                        @if($cert->credential_url)
                        <a href="{{ $cert->credential_url }}" class="cert-card-link" target="_blank"><i class="fas fa-external-link-alt"></i> View Credential</a>
                        @endif
                    </div>
                </div>
                @empty
                    <div class="empty-state" style="grid-column: 1 / -1;">
                        <i class="fas fa-certificate" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                        <h3>No certifications found</h3>
                        <p>Check back later for updates!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Awards -->
    <section id="awards" class="edu-section welcome-solutions">
        <div class="container">
            <div class="edu-section-header solutions-header">
                <h2 class="solutions-title">Awards & <span class="title-light">Recognition</span></h2>
                <p class="solutions-desc">Recognition received for excellence in development, innovation, and contribution to the tech community.</p>
            </div>
            <div class="award-grid">
                @forelse($awards as $award)
                <div class="award-card">
                    <div class="award-icon"><i class="{{ $award->icon ?? 'fas fa-trophy' }}"></i></div>
                    <div class="award-content">
                        <h3>{{ $award->title }}</h3>
                        <p class="award-org">{{ $award->issuer ?? $award->organization }}</p>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem;">
                            <span class="award-year">{{ $award->date ?? $award->year }}</span>
                            @if(!empty($award->image_url))
                                <a href="{{ $award->image_url }}" class="cert-card-link" target="_blank" style="font-size: 0.95rem; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; color: #10b981;"><i class="fas fa-external-link-alt"></i> View </a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                    <div class="empty-state" style="grid-column: 1 / -1;">
                        <i class="fas fa-trophy" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                        <h3>No awards found</h3>
                        <p>Check back later for updates!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Documents -->
    <section id="documents" class="edu-section welcome-solutions edu-section-bg-light">
        <div class="container">
            <div class="edu-section-header solutions-header">
                <h2 class="solutions-title">Key <span class="title-light">Documents</span></h2>
                <p class="solutions-desc">Resume, transcripts, and other documents available for download. Feel free to reach out for additional materials.</p>
            </div>
            <div class="doc-list">
                @forelse($documents as $doc)
                <div class="doc-item">
                    <div class="doc-item-info">
                        <div class="doc-item-icon"><i class="fas fa-file-pdf"></i></div>
                        <div class="doc-item-text">
                            <h4>{{ $doc->title }}</h4>
                            <span>{{ $doc->description }}</span>
                        </div>
                    </div>
                    @if($doc->file_path)
                    <div class="doc-item-actions">
                        <a href="#" class="doc-item-view" data-url="{{ str_starts_with($doc->file_path, 'http') ? $doc->file_path : \Storage::disk('s3')->url($doc->file_path) }}" data-title="{{ $doc->title }}"><i class="fas fa-eye"></i> View</a>
                        <a href="{{ route('documents.download', $doc) }}" class="doc-item-download"><i class="fas fa-download"></i> Download</a>
                    </div>
                    @endif
                </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-file-alt" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                        <h3>No documents found</h3>
                        <p>Check back later for updates!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="statement-section">
        <div class="container">
            <h2>Need More Information?</h2>
            <p>I'm happy to provide additional documents or discuss my qualifications in detail. Let's connect and explore how we can work together.</p>
            <a href="{{ route('connect') }}" class="cta-button cta-margin-top"><i class="fas fa-envelope"></i> Get In Touch</a>
        </div>
    </section>

    @include('component.footer')

    <a href="#hero" class="back-to-top" id="backToTop"><i class="fas fa-chevron-up"></i></a>



    <script src="{{ asset('js/style.js') }}"></script>
    <script>
        // Smooth scroll for quick nav
        document.querySelectorAll('.edu-quick-nav a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
        // Highlight active section on scroll
        const sections = document.querySelectorAll('#education, #certifications, #awards, #documents');
        const navLinks = document.querySelectorAll('.edu-quick-nav a');
        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const top = section.offsetTop;
                const height = section.offsetHeight;
                if (scrollY >= top - 150) current = section.getAttribute('id');
            });
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) link.classList.add('active');
            });
        });

        // Document Viewer (Opens in New Tab)
        document.querySelectorAll('.doc-item-view').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                let url = this.getAttribute('data-url');
                
                // Use Google Docs viewer for Office files (so they don't just force download)
                if (url.match(/\.(doc|docx|xls|xlsx|ppt|pptx)$/i)) {
                    // For local development (like .test), Google Docs Viewer won't be able to fetch the file, 
                    // but it works perfectly once deployed to a live server.
                    url = 'https://docs.google.com/viewer?url=' + encodeURIComponent(url);
                }
                
                window.open(url, '_blank');
            });
        });
    </script>
</body>
</html>
