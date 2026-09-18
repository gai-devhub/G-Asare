<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Me | Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    @include('component.favicon')
</head>
<body>
    @include('component.nav')

    <!-- Hero About -->
    <section id="hero" class="welcome-hero hero-image-bg page-hero-single hero-bg-about">
        <div class="container welcome-hero-inner">
            <div class="hero-content-left hero-content-centered">
                <h1>Discover My Story</h1>
                <p>{{ $profile->tagline ?? 'I am a Software Engineering student and aspiring Solutions Architect, blending academic knowledge with practical AWS cloud expertise.' }}</p>
            </div>
        </div>
    </section>

    <!-- About Me Intro - Professional two-column layout -->
    <section class="about-me-intro" id="about-intro">
        <div class="container">
            <h2 class="about-me-title">About Me</h2>
            <div class="about-me-grid">
                <div class="about-me-text">
                    @if($profile->bio)
                        {!! nl2br(e($profile->bio)) !!}
                    @else
                        <p>Hello! I'm {{ $profile->name ?? 'Gilbert' }}, a Software Engineering student at Ghana Communication Technology University. I'm passionate about building robust applications and exploring the vast world of cloud computing.</p>
                        <p>As an AWS Cloud Practitioner and a soon-to-be Solutions Architect, my approach combines modern software engineering principles with secure and scalable cloud infrastructure. I thrive on creating solutions that are both visually compelling and architecturally sound.</p>
                        <p>When I'm not studying or coding, you can find me diving deeper into cloud technologies, working on personal projects, and preparing for my next AWS certification. I'm always eager to embrace new challenges and learn something new.</p>
                    @endif
                </div>
                <div class="about-me-image">
                    @if($webContent->hero_image_url)
                        <img src="{{ asset($webContent->hero_image_url) }}" alt="Gilbert Asare" style="width: 100%; max-width: 500px; object-fit: contain; aspect-ratio: 4/5;">
                    @else
                    <img src="{{ asset('images/1783462869_file_00000000e6a4720a83f356bfb12d61a3.png') }}" alt="Gilbert at work - developer workspace" style="width: 100%; object-fit: contain; aspect-ratio: 4/5;">
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Impact Stats -->
    <section class="impact-stats-section">
        <div class="container">
            <div class="impact-stats-flex">
                <div class="impact-stat-item">
                    <div class="impact-stat-value">10%</div>
                    <div class="impact-stat-label">Sustainable innovations</div>
                </div>
                <div class="impact-stat-item">
                    <div class="impact-stat-value">16%</div>
                    <div class="impact-stat-label">Client empowerment</div>
                </div>
                <div class="impact-stat-item">
                    <div class="impact-stat-value">74%</div>
                    <div class="impact-stat-label">Satisfaction rate</div>
                </div>
            </div>
        </div>
    </section>

    <!-- My Story -->
    <section id="story">
        <div class="container">
            <div class="section-title">
                <h2>{{ $webContent->story_title ?? 'Bridging Software Engineering & Cloud Infrastructure' }}</h2>
                <p>{{ $webContent->story_subtitle ?? 'Building a foundation for scalable and secure digital solutions' }}</p>
            </div>
            
            <div class="story-content">
                <div class="story-text">
                    {!! nl2br(e($webContent->story_content ?? "My journey into the world of technology truly took off when I enrolled as a Software Engineering student at Ghana Communication Technology University. It was here that I discovered my passion for building software and understanding the complex systems that power the modern web.\n\nThroughout my studies, I've focused heavily on clean code, software architecture, and modern development frameworks. However, I quickly realized that writing good code is only half the battle; deploying and scaling it is just as important. This realization led me down the path of cloud computing.\n\nI achieved my AWS Cloud Practitioner certification to build a strong foundation in cloud services, security, and architecture. I am currently working hard towards becoming a certified AWS Solutions Architect, learning how to design distributed systems that are highly available, fault-tolerant, and cost-optimized.\n\nToday, my goal is to seamlessly integrate my software engineering skills with my growing cloud expertise. I am excited to take on roles where I can architect robust solutions from the ground up, combining frontend user experiences with powerful backend and cloud infrastructures.")) !!}
                </div>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills">
        <div class="container">
            <div class="section-title">
                <h2>My Skills</h2>
                <p>A combination of technical expertise and creative problem-solving</p>
            </div>
            
            <div class="skills-container">
                <div class="skills-intro">
                    <h3>Technical Expertise</h3>
                    <p>I've cultivated a diverse skill set that allows me to tackle projects from concept to completion. Here's an overview of my technical capabilities:</p>
                    
                    <div class="skills-list">
                        @forelse($skills->groupBy('category') as $category => $categorySkills)
                            <div class="skill-category">
                                <h4>{{ $category }}</h4>
                                <div class="skill-items">
                                    @foreach($categorySkills as $skill)
                                        <span class="skill-item">{{ $skill->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">No technical skills have been added yet.</div>
                        @endforelse
                    </div>
                </div>
                
                <div class="skill-levels">
                    <h3>Proficiency Levels</h3>
                    
                    @forelse($skills->where('percentage', '>', 0) as $skill)
                        <div class="skill-level-item">
                            <div class="skill-name">
                                <span>{{ $skill->name }}</span>
                                <span>{{ $skill->percentage }}%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" style="width: {{ $skill->percentage }}%;"></div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">No proficiency levels to display.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- Experience -->
    <section id="experience">
        <div class="container">
            <div class="section-title">
                <h2>Work Experience</h2>
                <p>My professional journey through the tech industry</p>
            </div>
            
            <div class="timeline">
                @forelse($experiences as $experience)
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-date">{{ $experience->date_from }} - {{ $experience->date_to ?? 'Present' }}</div>
                        <div class="timeline-content">
                            <h3>{{ $experience->role }}</h3>
                            <h4>{{ $experience->company }}</h4>
                            <p>{!! nl2br(e($experience->description)) !!}</p>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">No work experience has been added yet.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Education -->
    <section id="education">
        <div class="container">
            <div class="section-title">
                <h2>Education & Certifications</h2>
                <p>My academic background and professional certifications</p>
            </div>
            
            <div class="education-grid">
                @forelse($educations as $education)
                    <div class="education-card">
                        <div class="education-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3>{{ $education->degree }}</h3>
                        <div class="date">{{ $education->date_from }} - {{ $education->date_to ?? 'Present' }}</div>
                        <p>{{ $education->institution }}  {!! nl2br(e($education->description)) !!}</p>
                    </div>
                @empty
                    <div class="empty-state" style="grid-column: 1 / -1;">No education details have been added yet.</div>
                @endforelse
            </div>
            
            <div class="section-title section-title-spaced">
                <h3>Professional Certifications</h3>
            </div>
            
            <div class="certifications-grid">
                @forelse($certifications as $certification)
                    <div class="cert-card">
                        <div class="cert-icon">
                            <i class="{{ $certification->icon ?? 'fas fa-certificate' }}"></i>
                        </div>
                        <div class="cert-info">
                            <h4>{{ $certification->name }}</h4>
                            <p>{{ $certification->issuer }}  {{ $certification->year }}</p>
                        </div>
                    </div>
                @empty
                    <div class="empty-state" style="grid-column: 1 / -1;">No certifications have been added yet.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Philosophy & Values -->
    <section id="philosophy">
        <div class="container">
            <div class="section-title">
                <h2>{{ $webContent->philosophy_title ?? 'My Philosophy & Values' }}</h2>
                <p>{{ $webContent->philosophy_subtitle ?? 'The principles that guide my work and approach to design' }}</p>
            </div>
            
            <div class="philosophy-content">
                <div class="story-text">
                    {!! nl2br(e($webContent->philosophy_content ?? "I believe that technology should serve people, not the other way around. Every line of code I write and every design decision I make is guided by this principle. Great digital experiences are intuitive, accessible, and delightful to use.\n\nMy approach combines technical excellence with empathy for the end user. I strive to understand not just what users need, but why they need it. This user-centered mindset informs everything from high-level architecture decisions to the smallest interaction details.")) !!}
                </div>
                
                <div class="values-grid">
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4>User-Centered</h4>
                        <p>Always designing with the end user in mind, prioritizing their needs and experiences.</p>
                    </div>
                    
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <h4>Clean Code</h4>
                        <p>Writing maintainable, efficient code that other developers can understand and build upon.</p>
                    </div>
                    
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h4>Continuous Learning</h4>
                        <p>Staying curious and constantly updating my skills to keep up with evolving technologies.</p>
                    </div>
                    
                    <div class="value-card">
                        <div class="value-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h4>Collaboration</h4>
                        <p>Believing that the best results come from diverse teams working together effectively.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Personal Interests -->
    <section id="interests">
        <div class="container">
            <div class="section-title">
                <h2>Beyond Code</h2>
                <p>My interests and hobbies outside of work</p>
            </div>
            
            <div class="interests-grid">
                <div class="interest-card">
                    <div class="interest-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h4>Photography</h4>
                </div>
                
                <div class="interest-card">
                    <div class="interest-icon">
                        <i class="fas fa-hiking"></i>
                    </div>
                    <h4>Hiking</h4>
                </div>
                
                <div class="interest-card">
                    <div class="interest-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h4>Reading</h4>
                </div>
                
                <div class="interest-card">
                    <div class="interest-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h4>Cooking</h4>
                </div>
                
                <div class="interest-card">
                    <div class="interest-icon">
                        <i class="fas fa-music"></i>
                    </div>
                    <h4>Music</h4>
                </div>
                
                <div class="interest-card">
                    <div class="interest-icon">
                        <i class="fas fa-plane"></i>
                    </div>
                    <h4>Travel</h4>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="statement-section">
        <div class="container">
            <h2>Let's Work Together</h2>
            <p>I'm always interested in new opportunities and exciting projects. Whether you need a website, web application, or consultation on your digital strategy, let's connect.</p>
            <a href="{{ route('connect') }}" class="cta-button cta-margin-top">
                <i class="fas fa-envelope"></i> Get In Touch
            </a>
        </div>
    </section>

    @include('component.footer')

    <!-- Back to Top Button -->
    <a href="#about-hero" class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </a>

    <script src="{{ asset('js/style.js') }}"></script>
</body>
</html>
