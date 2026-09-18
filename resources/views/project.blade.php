<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects | Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Raleway:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
</head>
<body>
    @include('component.nav')

    <!-- Hero Section -->
    <section id="hero" class="welcome-hero hero-image-bg page-hero-single hero-bg-project">
        <div class="container welcome-hero-inner">
            <div class="hero-content-left hero-content-centered">
                <h1>My Project Portfolio</h1>
                <p>A curated collection of my work, showcasing web applications, design projects, and development solutions built with modern technologies.</p>
                
                <div class="hero-stats-inline">
                    <div class="hero-stat-item"><span class="hero-stat-num">{{ \App\Models\Project::count() ?? '80' }}+</span><span class="hero-stat-label">Projects</span></div>
                    <div class="hero-stat-item"><span class="hero-stat-num">{{ \App\Models\Profile::first()->stat_clients ?? '50+' }}</span><span class="hero-stat-label">Happy Clients</span></div>
                    <div class="hero-stat-item"><span class="hero-stat-num">{{ \App\Models\Skill::count() ?? '8' }}+</span><span class="hero-stat-label">Technologies</span></div>
                    <div class="hero-stat-item"><span class="hero-stat-num">{{ date('Y') - 2024 }}+</span><span class="hero-stat-label">Years Experience</span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="welcome-solutions">
        <div class="container">
            <div class="solutions-header">
                <h2 class="solutions-title">My <span class="title-light">Projects</span></h2>
                <p class="solutions-desc">Browse through my portfolio of web development, design, and software projects. Each project includes details about technologies used and challenges solved.</p>
            </div>
            <div class="projects-filter">
                <button class="filter-btn active" data-filter="all">All Projects</button>
                <button class="filter-btn" data-filter="ui,ux,ui/ux,ui/ux design">UI/UX</button>
                <button class="filter-btn" data-filter="web,web development">Web Development</button>
                <button class="filter-btn" data-filter="app,mobile apps,app development">Mobile Apps</button>
                <button class="filter-btn" data-filter="python,javascript,php,backend">Backend</button>
                <button class="filter-btn" data-filter="fullstack,full stack">Full Stack</button>
            </div>
            <div class="projects-grid" id="projects-grid">
                @forelse($projects as $project)
                    <div class="project-card" data-category="{{ $project->category }}">
                        <div class="project-img">
                            <img src="{{ $project->image_url ? asset($project->image_url) : 'https://images.unsplash.com/photo-1545235617-9465d2a55698?ixlib=rb-4.0.3&auto=format&fit=crop&w=1180&q=80' }}" alt="{{ $project->title }}">
                            <span class="project-category">{{ ucfirst($project->category) }}</span>
                        </div>
                        <div class="project-info">
                            <h3>{{ $project->title }}</h3>
                            <p>{{ $project->description }}</p>
                            @if($project->tags)
                                <div class="project-tech">
                                    @foreach($project->tags as $tag)
                                        <span class="tech-tag">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <div class="project-links">
                                <a href="#" class="project-link view-details" data-project="{{ $project->id }}" 
                                    data-title="{{ $project->title }}" 
                                    data-desc="{{ $project->description }}" 
                                    data-category="{{ ucfirst($project->category) }}"
                                    data-image="{{ $project->image_url ? asset($project->image_url) : 'https://images.unsplash.com/photo-1545235617-9465d2a55698?ixlib=rb-4.0.3&auto=format&fit=crop&w=1180&q=80' }}"
                                    data-livelink="{{ $project->project_url }}"
                                    data-sourcelink="{{ $project->github_url }}"
                                    data-tech="{{ json_encode($project->tags ?? []) }}">
                                    <i class="fas fa-info-circle"></i> Details
                                </a>
                                @if($project->project_url)
                                    <a href="{{ $project->project_url }}" class="project-link" target="_blank"><i class="fas fa-external-link-alt"></i> Demo</a>
                                @endif
                                @if($project->github_url)
                                    <a href="{{ $project->github_url }}" class="project-link" target="_blank"><i class="fab fa-github"></i> Code</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state" style="grid-column: 1 / -1;">
                        <i class="fas fa-folder-open" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                        <h3>There is no project yet</h3>
                        <p>Check back later to see my latest work!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    @if($featuredProject)
    <!-- Featured Project -->
    <section class="welcome-journey">
        <div class="container journey-grid">
            <div class="journey-image">
                <img src="{{ $featuredProject->image_url ? asset($featuredProject->image_url) : 'https://images.unsplash.com/photo-1551650975-87deedd944c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}" alt="Featured Project">
            </div>
            <div class="journey-content">
                <span class="featured-badge">Featured Project</span>
                <h2>{{ $featuredProject->title }}</h2>
                <p>{{ $featuredProject->description }}</p>
                @if($featuredProject->tags)
                <div class="project-tech">
                    @foreach($featuredProject->tags as $tag)
                        <span class="tech-tag">{{ $tag }}</span>
                    @endforeach
                </div>
                @endif
                <div class="project-links">
                    @if($featuredProject->project_url)
                        <a href="{{ $featuredProject->project_url }}" class="cta-button journey-cta" target="_blank"><i class="fas fa-external-link-alt"></i> Live Demo</a>
                    @endif
                    @if($featuredProject->github_url)
                        <a href="{{ $featuredProject->github_url }}" class="project-link-outline" target="_blank"><i class="fab fa-github"></i> Source Code</a>
                    @endif
                    <a href="#" class="project-link-outline view-details" 
                        data-project="{{ $featuredProject->id }}"
                        data-title="{{ $featuredProject->title }}" 
                        data-desc="{{ $featuredProject->description }}" 
                        data-category="{{ ucfirst($featuredProject->category) }}"
                        data-image="{{ $featuredProject->image_url ? asset($featuredProject->image_url) : 'https://images.unsplash.com/photo-1551650975-87deedd944c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}"
                        data-livelink="{{ $featuredProject->project_url }}"
                        data-sourcelink="{{ $featuredProject->github_url }}"
                        data-tech="{{ json_encode($featuredProject->tags ?? []) }}">
                        <i class="fas fa-info-circle"></i> Details
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Development Stats -->
    <section class="welcome-empowering">
        <div class="container">
            <div class="impact-header impact-header-spaced">
                <h2 class="impact-title impact-title-light">Development Activity</h2>
                <p class="impact-desc impact-desc-light">My coding contributions and project statistics</p>
            </div>
            <div class="impact-stats">
                <div class="impact-stat"><span class="stat-num stat-light">{{ $githubStats['repositories'] ?? '142' }}</span><span class="stat-label stat-muted">Repositories</span></div>
                <div class="impact-stat"><span class="stat-num stat-light">{{ $githubStats['commits'] ?? '1,248' }}</span><span class="stat-label stat-muted">Commits This Year</span></div>
                <div class="impact-stat"><span class="stat-num stat-light">{{ $githubStats['collaborations'] ?? '89' }}</span><span class="stat-label stat-muted">Collaborations</span></div>
                <div class="impact-stat"><span class="stat-num stat-light">{{ $githubStats['lines_of_code'] ?? '42k+' }}</span><span class="stat-label stat-muted">Lines of Code</span></div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="statement-section">
        <div class="container">
            <h2>Have a Project in Mind?</h2>
            <p>I'm always open to discussing new opportunities and interesting projects. Let's connect and bring your vision to life.</p>
            <a href="{{ route('connect') }}" class="cta-button cta-margin-top"><i class="fas fa-envelope"></i> Get In Touch</a>
        </div>
    </section>

    @include('component.footer')

    <a href="#hero" class="back-to-top" id="backToTop"><i class="fas fa-chevron-up"></i></a>

    <!-- Project Modal -->
    <div class="project-modal" id="projectModal">
        <div class="modal-content">
            <div class="modal-close" id="modalClose"><i class="fas fa-times"></i></div>
            <div class="modal-img">
                <img id="modalImage" src="" alt="Project">
            </div>
            <div class="modal-body">
                <span class="modal-category" id="modalCategory">Web Development</span>
                <h2 id="modalTitle">Project Title</h2>
                <p id="modalDescription">Project description.</p>
                <h3>Technologies Used</h3>
                <div class="modal-tech" id="modalTech"></div>
                <h3>Project Details</h3>
                <p id="modalDetails">Details.</p>
                <div class="modal-links">
                    <a href="#" class="modal-link" id="modalLiveLink" target="_blank"><i class="fas fa-external-link-alt"></i> Live Demo</a>
                    <a href="#" class="modal-link outline" id="modalSourceLink" target="_blank"><i class="fab fa-github"></i> Source Code</a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/style.js') }}?v={{ time() }}"></script>
</body>
</html>
