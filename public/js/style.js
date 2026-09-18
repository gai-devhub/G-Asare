/**
 * Consolidated JavaScript for Gil Portfolio
 * Handles all pages: welcome, project, about, connect, edu&certs, admin
 */

document.addEventListener('DOMContentLoaded', function() {
    // ========== COMMON: Mobile menu, back to top, smooth scroll ==========
    const menuToggle = document.getElementById('menu-toggle');
    const navLinks = document.getElementById('nav-links');
    const backToTop = document.getElementById('backToTop');

    if (menuToggle && navLinks) {
        function closeMenu() {
            navLinks.classList.remove('active');
            menuToggle.setAttribute('aria-expanded', 'false');
            menuToggle.setAttribute('aria-label', 'Open navigation menu');
            const icon = menuToggle.querySelector('i');
            if (icon) icon.className = 'fas fa-bars';
        }
        menuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = navLinks.classList.toggle('active');
            menuToggle.setAttribute('aria-expanded', isOpen);
            menuToggle.setAttribute('aria-label', isOpen ? 'Close navigation menu' : 'Open navigation menu');
            const icon = menuToggle.querySelector('i');
            if (icon) icon.className = isOpen ? 'fas fa-times' : 'fas fa-bars';
        });
        document.querySelectorAll('.nav-links a').forEach(link => {
            if (link.classList.contains('nav-link-dropdown')) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const dropdown = this.closest('.nav-dropdown');
                    if (dropdown) dropdown.classList.toggle('open');
                });
            } else {
                link.addEventListener('click', closeMenu);
            }
        });
        document.querySelectorAll('.nav-dropdown-menu a').forEach(link => {
            link.addEventListener('click', closeMenu);
        });
        document.addEventListener('click', function(e) {
            if (navLinks.classList.contains('active') && !navLinks.contains(e.target) && !menuToggle.contains(e.target)) {
                closeMenu();
            }
        });
    }

    if (backToTop) {
        window.addEventListener('scroll', () => {
            backToTop.classList.toggle('active', window.pageYOffset > 300);
        });
    }

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            if (this.classList.contains('social-link') || this.classList.contains('document-badge') || this.classList.contains('doc-link') || this.classList.contains('edu-doc-btn') || this.classList.contains('edu-doc-card__btn')) return;
            if (this.classList.contains('cta-button') && this.getAttribute('href') !== '#contact') return;
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({ top: targetElement.offsetTop - 80, behavior: 'smooth' });
            }
        });
    });

    // ========== WELCOME: Portfolio filter, contact form ==========
    const filterButtons = document.querySelectorAll('.filter-btn');
    const portfolioItems = document.querySelectorAll('.portfolio-item');
    const contactForm = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');

    if (filterButtons.length && portfolioItems.length) {
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                const filterValue = button.getAttribute('data-filter');
                portfolioItems.forEach(item => {
                    item.style.display = (filterValue === 'all' || item.getAttribute('data-category') === filterValue) ? 'block' : 'none';
                });
            });
        });
    }

    if (contactForm && !submitBtn) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Thank you for your message! I will get back to you soon.');
            contactForm.reset();
        });
    }

    const subscribeForm = document.getElementById('news-subscribe-form');
    const subscribeMessage = document.getElementById('subscribe-message');
    if (subscribeForm && subscribeMessage) {
        subscribeForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = subscribeForm.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;
            
            try {
                const formData = new FormData(subscribeForm);
                const response = await fetch(subscribeForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json().catch(() => ({}));
                
                subscribeMessage.style.display = 'block';
                if (response.ok) {
                    subscribeMessage.innerHTML = '<span style="color: var(--success, #10b981);">' + (data.message || 'Successfully subscribed!') + '</span>';
                    subscribeForm.reset();
                } else {
                    const msg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : '') || 'There was an error.';
                    subscribeMessage.innerHTML = '<span style="color: var(--danger, #ef4444);">' + msg + '</span>';
                }
            } catch (err) {
                subscribeMessage.style.display = 'block';
                subscribeMessage.innerHTML = '<span style="color: var(--danger, #ef4444);">Network error. Please try again.</span>';
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
                setTimeout(() => subscribeMessage.style.display = 'none', 5000);
            }
        });
    }

    // ========== PROJECT: Projects grid, modal ==========
    const projectsGrid = document.getElementById('projects-grid');
    if (projectsGrid) {
        const projectModal = document.getElementById('projectModal');
        const modalClose = document.getElementById('modalClose');

        function openProjectModal(btn) {
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const modalCategory = document.getElementById('modalCategory');
            const modalDescription = document.getElementById('modalDescription');
            const modalTech = document.getElementById('modalTech');
            const modalDetails = document.getElementById('modalDetails');
            const modalLiveLink = document.getElementById('modalLiveLink');
            const modalSourceLink = document.getElementById('modalSourceLink');
            
            if (modalImage) modalImage.src = btn.getAttribute('data-image');
            if (modalTitle) modalTitle.textContent = btn.getAttribute('data-title');
            if (modalCategory) modalCategory.textContent = btn.getAttribute('data-category');
            if (modalDescription) modalDescription.textContent = btn.getAttribute('data-desc');
            if (modalDetails) modalDetails.textContent = btn.getAttribute('data-desc'); // using desc as details for now
            if (modalLiveLink) {
                const ll = btn.getAttribute('data-livelink');
                modalLiveLink.href = ll || '#';
                modalLiveLink.style.display = ll ? 'inline-block' : 'none';
            }
            if (modalSourceLink) {
                const sl = btn.getAttribute('data-sourcelink');
                modalSourceLink.href = sl || '#';
                modalSourceLink.style.display = sl ? 'inline-block' : 'none';
            }
            if (modalTech) {
                modalTech.innerHTML = '';
                try {
                    const techArr = JSON.parse(btn.getAttribute('data-tech') || '[]');
                    techArr.forEach(tech => {
                        const tag = document.createElement('span');
                        tag.className = 'tech-tag';
                        tag.textContent = tech;
                        modalTech.appendChild(tag);
                    });
                } catch(e) {}
            }
            projectModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeProjectModal() {
            projectModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Attach click listeners to all view-details buttons
        document.querySelectorAll('.view-details').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                openProjectModal(this);
            });
        });

        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const filterRaw = (this.getAttribute('data-filter') || '').toLowerCase();
                const filters = filterRaw.split(',').map(f => f.trim());
                
                document.querySelectorAll('.project-card').forEach(card => {
                    const category = (card.getAttribute('data-category') || '').toLowerCase();
                    if (filterRaw === 'all' || filters.includes(category)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        if (modalClose) modalClose.addEventListener('click', closeProjectModal);
        if (projectModal) projectModal.addEventListener('click', e => { if (e.target === projectModal) closeProjectModal(); });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && projectModal?.classList.contains('active')) closeProjectModal();
        });
    }

    // ========== ABOUT: Skill bars animation ==========
    const skillBars = document.querySelectorAll('.skill-progress');
    if (skillBars.length) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const width = entry.target.getAttribute('data-width') || entry.target.style.width;
                    entry.target.style.width = '0%';
                    setTimeout(() => {
                        entry.target.style.transition = 'width 1.5s ease-in-out';
                        entry.target.style.width = width || '100%';
                    }, 300);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        skillBars.forEach(bar => {
            if (!bar.style.width) bar.style.width = bar.getAttribute('style')?.match(/width:\s*(\d+%?)/)?.[1] || '100%';
            observer.observe(bar);
        });
    }

    const timelineItems = document.querySelectorAll('.timeline-item');
    if (timelineItems.length && !document.querySelector('.timeline-container')) {
        const timelineObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '0';
                    entry.target.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        entry.target.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateX(0)';
                    }, 100);
                    timelineObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        timelineItems.forEach(item => timelineObserver.observe(item));
    }

    // ========== CONNECT: Contact form, FAQ, success modal ==========
    const connectContactForm = document.getElementById('contactForm');
    const successModal = document.getElementById('successModal');
    const successModalClose = document.getElementById('modalClose');
    const faqItems = document.querySelectorAll('.faq-item');

    if (faqItems.length) {
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            if (question) question.addEventListener('click', () => {
                faqItems.forEach(other => { if (other !== item && other.classList.contains('active')) other.classList.remove('active'); });
                item.classList.toggle('active');
            });
        });
    }

    if (connectContactForm && submitBtn && document.getElementById('firstName')) {
        connectContactForm.addEventListener('submit', async (e) => {
            if (!connectContactForm.checkValidity()) return;
            e.preventDefault();
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            try {
                const formData = new FormData(connectContactForm);
                const response = await fetch(connectContactForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json().catch(() => ({}));
                if (response.ok) {
                    successModal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                    connectContactForm.reset();
                } else {
                    const msg = data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : '') || 'There was an error. Please try again.';
                    let errDiv = document.querySelector('.form-errors');
                    if (!errDiv) {
                        errDiv = document.createElement('div');
                        errDiv.className = 'form-errors';
                        connectContactForm.parentNode.insertBefore(errDiv, connectContactForm);
                    }
                    const escape = (s) => String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
                    errDiv.innerHTML = '<strong>Please fix the following errors:</strong><ul>' + (data.errors ? Object.values(data.errors).flat().map(e => '<li>' + escape(e) + '</li>').join('') : '<li>' + escape(msg) + '</li>') + '</ul>';
                    errDiv.style.display = 'block';
                    errDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            } catch (err) {
                alert('There was an error sending your message. Please try again.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Message';
            }
        });
    }

    if (successModal) {
        if (successModalClose) successModalClose.addEventListener('click', () => { successModal.classList.remove('active'); document.body.style.overflow = 'auto'; });
        successModal.addEventListener('click', e => { if (e.target === successModal) { successModal.classList.remove('active'); document.body.style.overflow = 'auto'; } });
        document.addEventListener('keydown', e => { if (e.key === 'Escape' && successModal.classList.contains('active')) { successModal.classList.remove('active'); document.body.style.overflow = 'auto'; } });
    }

    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 3 && value.length <= 6) value = `(${value.substring(0, 3)}) ${value.substring(3)}`;
            else if (value.length > 6) value = `(${value.substring(0, 3)}) ${value.substring(3, 6)}-${value.substring(6, 10)}`;
            e.target.value = value;
        });
    }

});
