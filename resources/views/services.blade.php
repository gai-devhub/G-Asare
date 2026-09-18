<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services | Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Raleway:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    @include('component.favicon')
</head>
<body>
    @include('component.nav')

    <!-- Services Hero -->
    <section id="hero" class="welcome-hero hero-image-bg page-hero-single hero-bg-services">
        <div class="container welcome-hero-inner">
            <div class="hero-content-left hero-content-centered">
                <h1>Software Engineering &<br>Cloud Architecture Services</h1>
                <p>As a Software Engineering student and AWS Cloud Practitioner, I provide modern web development and cloud infrastructure solutions tailored to your needs. Combining clean code with scalable AWS architectures.</p>
            </div>
        </div>
    </section>

    <!-- Comprehensive Solutions -->
    <section id="services-main" class="welcome-solutions">
        <div class="container">
            <div class="solutions-header">
                <h2 class="solutions-title">My Technical <span class="title-light">Expertise</span></h2>
                <p class="solutions-desc">Leveraging my academic background and AWS certifications to deliver robust, scalable, and secure digital solutions.</p>
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
                        <p>Designing secure, highly available, and cost-effective cloud solutions on AWS. Preparation for AWS Solutions Architect certification ensures industry best practices.</p>
                    </div>
                    <div class="solution-card-img">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Consulting">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Services -->
    <section class="welcome-impact">
        <div class="container">
            <div class="impact-header">
                <h2 class="impact-title">Why Work With Me</h2>
                <p class="impact-desc">I bring a fresh perspective, strong academic foundation, and certified AWS cloud knowledge to every project.</p>
            </div>
            <div class="impact-stats">
                <div class="impact-stat"><span class="stat-num">AWS</span><span class="stat-label">Cloud Practitioner</span></div>
                <div class="impact-stat"><span class="stat-num">BSc</span><span class="stat-label">Software Engineering</span></div>
                <div class="impact-stat"><span class="stat-num">100%</span><span class="stat-label">Dedication</span></div>
                <div class="impact-stat"><span class="stat-num">24/7</span><span class="stat-label">Commitment</span></div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="statement-section">
        <div class="container">
            <h2>Ready to Start Your Project?</h2>
            <p>Let's discuss how I can help bring your vision to life with modern, scalable web solutions.</p>
            <a href="{{ route('connect') }}" class="cta-button cta-margin-top"><i class="fas fa-envelope"></i> Get In Touch</a>
        </div>
    </section>

    @include('component.footer')

    <a href="#hero" class="back-to-top" id="backToTop"><i class="fas fa-chevron-up"></i></a>

    <script src="{{ asset('js/style.js') }}"></script>
</body>
</html>
