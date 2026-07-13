<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact & Connect | Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    @include('component.theme-init')
</head>
<body>
    <!-- Header -->
    @include('component.nav')
    @php $profile = \App\Models\Profile::first() ?? new \App\Models\Profile(); @endphp

    <!-- Hero Section -->
    <section id="hero" class="welcome-hero hero-image-bg page-hero-single hero-bg-connect">
        <div class="container welcome-hero-inner">
            <div class="hero-content-left hero-content-centered">
                <h1>Let's Connect & Collaborate</h1>
                <p>I'm always open to discussing new projects, creative ideas, or opportunities to be part of your vision. Reach out and let's create something amazing together.</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="section-title">
                <h2>Get In Touch</h2>
                <p>Choose your preferred method to reach out - I typically respond within 24 hours</p>
            </div>
            
            <div class="contact-grid">
                <div class="contact-info">
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3>Email Me</h3>
                        <p>For project inquiries, collaboration proposals, or detailed discussions.</p>
                        
                        <div class="contact-detail">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:{{ $profile->email ?? 'gaicorporation.official@gmail.com' }}">{{ $profile->email ?? 'gaicorporation.official@gmail.com' }}</a>
                        </div>
                        @if(!empty(($profile->contact_info ?? [])['email_2']))
                        <div class="contact-detail">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:{{ $profile->contact_info['email_2'] }}">{{ $profile->contact_info['email_2'] }}</a>
                        </div>
                        @endif
                    </div>
                    
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h3>Call or Text</h3>
                        <p>For urgent matters or if you prefer to talk directly.</p>
                        
                        <div class="contact-detail">
                            <i class="fas fa-phone"></i>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $profile->phone ?? '+233599215326') }}">{{ $profile->phone ?? '+233 (59) 921-5326' }}</a>
                        </div>
                        @if(!empty(($profile->contact_info ?? [])['whatsapp']))
                        <div class="contact-detail">
                            <i class="fab fa-whatsapp"></i>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9+]/', '', $profile->contact_info['whatsapp']) }}">WhatsApp: {{ $profile->contact_info['whatsapp'] }}</a>
                        </div>
                        @endif
                        @if(!empty(($profile->contact_info ?? [])['availability_hours']))
                        <div class="contact-detail">
                            <i class="fas fa-clock"></i>
                            <span>Available: {{ $profile->contact_info['availability_hours'] }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3>Visit or Mail</h3>
                        <p>Based in Accra, but available for remote work worldwide.</p>
                        
                        <div class="contact-detail">
                            <i class="fas fa-map-pin"></i>
                            <span>{!! nl2br(e(($profile->contact_info ?? [])['office_address'] ?? "Tesano, GCTU Campus\nAccra, Ghana")) !!}</span>
                        </div>
                        @if(!empty(($profile->contact_info ?? [])['work_modes']))
                        <div class="contact-detail">
                            <i class="fas fa-globe"></i>
                            <span>Open to: {{ $profile->contact_info['work_modes'] }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="contact-form-container">
                    <h3 class="form-title">Send a Message</h3>
                    @if($errors->any())
                        <div class="form-errors">
                            <strong>Please fix the following errors:</strong>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form id="contactForm" method="POST" action="{{ route('contact.store') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name *</label>
                                <input type="text" id="firstName" name="firstName" value="{{ old('firstName') }}" required class="{{ $errors->has('firstName') ? 'input-error' : '' }}">
                                @error('firstName')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name *</label>
                                <input type="text" id="lastName" name="lastName" value="{{ old('lastName') }}" required class="{{ $errors->has('lastName') ? 'input-error' : '' }}">
                                @error('lastName')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required class="{{ $errors->has('email') ? 'input-error' : '' }}">
                            @error('email')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <select id="subject" name="subject" required class="{{ $errors->has('subject') ? 'input-error' : '' }}">
                                <option value="" disabled {{ !old('subject') ? 'selected' : '' }}>Select a subject</option>
                                <option value="project" {{ old('subject') == 'project' ? 'selected' : '' }}>Project Inquiry</option>
                                <option value="collaboration" {{ old('subject') == 'collaboration' ? 'selected' : '' }}>Collaboration Opportunity</option>
                                <option value="job" {{ old('subject') == 'job' ? 'selected' : '' }}>Job Opportunity</option>
                                <option value="consultation" {{ old('subject') == 'consultation' ? 'selected' : '' }}>Consultation Request</option>
                                <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('subject')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Your Message *</label>
                            <textarea id="message" name="message" rows="6" required placeholder="Tell me about your project, timeline, budget, and any specific requirements..." class="{{ $errors->has('message') ? 'input-error' : '' }}">{{ old('message') }}</textarea>
                            @error('message')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="budget">Project Budget (Optional)</label>
                            <select id="budget" name="budget">
                                <option value="" selected>Select budget range</option>
                                <option value="under-5k">Under $5,000</option>
                                <option value="5k-15k">$5,000 - $15,000</option>
                                <option value="15k-50k">$15,000 - $50,000</option>
                                <option value="50k-plus">$50,000+</option>
                                <option value="undecided">To be discussed</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="timeline">Preferred Timeline (Optional)</label>
                            <select id="timeline" name="timeline">
                                <option value="" selected>Select timeline</option>
                                <option value="urgent">Urgent (Less than 1 month)</option>
                                <option value="1-3months">1-3 months</option>
                                <option value="3-6months">3-6 months</option>
                                <option value="6plus">6+ months</option>
                                <option value="flexible">Flexible</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="submit-btn" id="submitBtn">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Connect -->
    <section id="social-connect">
        <div class="container">
            <div class="section-title">
                <h2>Connect on Social Media</h2>
                <p>Follow my work, see what I'm building, and join the conversation</p>
            </div>
            
            @php
            $platformDetails = [
                'linkedin' => ['name' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in', 'bg' => '#0077b5', 'desc' => 'Professional network, work experience, and industry insights'],
                'github' => ['name' => 'GitHub', 'icon' => 'fab fa-github', 'bg' => '#333333', 'desc' => 'Open-source projects, code repositories, and technical contributions'],
                'twitter' => ['name' => 'Twitter / X', 'icon' => 'fab fa-twitter', 'bg' => '#1da1f2', 'desc' => 'Tech news, development tips, and industry commentary'],
                'dribbble' => ['name' => 'Dribbble', 'icon' => 'fab fa-dribbble', 'bg' => '#ea4c89', 'desc' => 'Design work, UI/UX projects, and visual creativity'],
                'instagram' => ['name' => 'Instagram', 'icon' => 'fab fa-instagram', 'bg' => '#e1306c', 'desc' => 'Behind-the-scenes, personal projects, and creative process'],
                'codepen' => ['name' => 'CodePen', 'icon' => 'fab fa-codepen', 'bg' => '#000000', 'desc' => 'Code experiments, frontend tricks, and interactive demos'],
                'whatsapp' => ['name' => 'WhatsApp', 'icon' => 'fab fa-whatsapp', 'bg' => '#25d366', 'desc' => 'Direct messaging and quick communication'],
                'facebook' => ['name' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'bg' => '#1877f2', 'desc' => 'Community engagement and updates'],
                'youtube' => ['name' => 'YouTube', 'icon' => 'fab fa-youtube', 'bg' => '#ff0000', 'desc' => 'Video content, tutorials, and tech reviews'],
                'medium' => ['name' => 'Medium', 'icon' => 'fab fa-medium-m', 'bg' => '#02b875', 'desc' => 'In-depth articles, tutorials, and technical writing'],
            ];
            @endphp
            <div class="social-grid">
                @if(!empty($profile->social_links))
                    @foreach($profile->social_links as $platform => $url)
                        @php $details = $platformDetails[$platform] ?? ['name' => ucfirst($platform), 'icon' => 'fas fa-link', 'bg' => '#666', 'desc' => 'Connect on ' . ucfirst($platform)]; @endphp
                        <div class="social-card">
                            <div class="social-icon" style="background-color: {{ $details['bg'] }};">
                                <i class="{{ $details['icon'] }}" style="color: white;"></i>
                            </div>
                            <h3>{{ $details['name'] }}</h3>
                            <p>{{ $details['desc'] }}</p>
                            <a href="{{ $url }}" class="social-link" target="_blank">
                                <i class="{{ $details['icon'] }}" style="color: {{ $details['bg'] }}; margin-right: 5px;"></i> <span style="color: {{ $details['bg'] }}; font-weight: 500;">Connect on {{ $details['name'] }}</span>
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state" style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem; background: #fff; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.05); width: 100%;">
                        <i class="fas fa-share-nodes" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                        <h3 style="margin-bottom: 0.5rem; color: #1e293b;">No social media links yet</h3>
                        <p style="color: #64748b;">Check back later to connect with me!</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq">
        <div class="container">
            <div class="section-title">
                <h2>Frequently Asked Questions</h2>
                <p>Common questions about working together and what to expect</p>
            </div>
            
            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>What's your typical response time?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>I typically respond to emails within 24 hours on business days. For urgent matters, please call or text. I'm most responsive between 9 AM and 6 PM PST, Monday through Friday.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Do you work with clients internationally?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, I work with clients worldwide. My primary timezone is PST (Pacific Standard Time), but I'm flexible with scheduling to accommodate different time zones. All my work is delivered remotely unless otherwise specified.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>What information should I include in my initial message?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>To help me provide the most relevant response, please include: 1) Project overview and goals, 2) Desired timeline, 3) Budget range if available, 4) Any specific technologies or requirements, 5) Links to current website/project if applicable. The more details you provide, the better I can assist you.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>What are your typical working hours?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>My core working hours are 9 AM - 6 PM PST, Monday through Friday. However, I often work outside these hours to accommodate different time zones or meet project deadlines. Scheduled meetings typically occur within business hours, but I'm flexible when needed.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Do you sign NDAs before discussing projects?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, I'm happy to sign a mutual NDA before discussing sensitive project details. I take client confidentiality seriously. You can send your NDA for review, or I can provide a standard mutual NDA template that I use with clients.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('component.footer')

    <!-- Back to Top Button -->
    <a href="#hero" class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </a>

    <!-- Success Modal -->
    <div class="success-modal" id="successModal">
        <div class="modal-content">
            <div class="modal-icon">
                <i class="fas fa-check"></i>
            </div>
            <h3>Message Sent Successfully!</h3>
            <p>Thank you for reaching out. I've received your message and will get back to you within 24 hours. You should receive a confirmation email shortly.</p>
            <button class="modal-close" id="modalClose">
                <i class="fas fa-times cta-icon-close"></i> Close
            </button>
        </div>
    </div>

    <script src="{{ asset('js/style.js') }}"></script>
</body>
</html>
