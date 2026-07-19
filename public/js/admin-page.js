/**
 * Admin Pages JavaScript - Portfolio Manager
 * All admin page-specific scripts consolidated here.
 * Requires window.ADMIN_URLS to be set (base, messages, skills, projects, etc.)
 */

document.addEventListener('DOMContentLoaded', function () {
    const urls = window.ADMIN_URLS || {};
    const base = urls.base || '';

    // ========== Sidebar Dropdown Toggle ==========
    document.querySelectorAll('.sidebar-dropdown-trigger').forEach(trigger => {
        trigger.addEventListener('click', function (e) {
            e.preventDefault();
            const parent = this.closest('.sidebar-dropdown');
            parent.classList.toggle('open');
        });
    });

    // ========== Modal System ==========
    document.querySelectorAll('[data-modal-open]').forEach(trigger => {
        trigger.addEventListener('click', function () {
            const modalId = this.getAttribute('data-modal-open');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            
            if (modalId === 'delete-confirm-modal' && this.hasAttribute('data-delete-url')) {
                const url = (this.getAttribute('data-delete-url') || '').trim();
                const name = this.getAttribute('data-delete-name') || 'this item';
                const form = document.getElementById('delete-confirm-form');
                const nameEl = document.getElementById('delete-confirm-name');
                const input = document.getElementById('delete-confirm-input');
                const submitBtn = document.getElementById('delete-confirm-submit');
                if (form && url) {
                    form.action = url;
                    form.dataset.deleteReady = '1';
                    if (nameEl) nameEl.textContent = name;
                    if (input) input.value = '';
                    if (submitBtn) submitBtn.disabled = true;
                }
            }
        });
    });

    document.querySelectorAll('[data-modal-close]').forEach(trigger => {
        trigger.addEventListener('click', function () {
            const modal = this.closest('[data-modal]');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });

    document.querySelectorAll('[data-modal]').forEach(modal => {
        modal.addEventListener('click', function (e) {
            if (e.target === this) {
                this.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(m => {
                m.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
    });

    // ========== Forms ==========
    document.querySelectorAll('.dashboard form:not(.logout-form)').forEach(form => {
        form.addEventListener('submit', function (e) {
            if (this.dataset.submit === 'server') return;
            const modal = this.closest('.modal-overlay');
            // If the form is not inside a modal, let it submit naturally to the server
            if (!modal) return;

            e.preventDefault();
            modal.classList.remove('active');
            document.body.style.overflow = '';
            this.reset();
        });
    });

    document.querySelector('.compose-form')?.addEventListener('submit', function (e) {
        e.preventDefault();
        document.getElementById('compose-message-modal')?.classList.remove('active');
        document.body.style.overflow = '';
        this.reset();
    });

    // ========== Action Buttons (delete uses HTML popup modal, no alerts) ==========
    document.querySelectorAll('.action-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            if (this.tagName.toLowerCase() === 'a' && this.hasAttribute('href') && this.getAttribute('href') !== '#') {
                return; // Let links navigate naturally
            }
            e.preventDefault();
            e.stopPropagation();
            if (this.classList.contains('edit-btn') && !this.dataset.replyTrigger && !this.dataset.modalOpen) {
                const row = this.closest('tr');
                if (row) { /* Edit would open form */ }
            } else if (this.classList.contains('delete-btn')) {
                if (this.hasAttribute('data-modal-open') && this.hasAttribute('data-delete-url')) {
                    const url = (this.getAttribute('data-delete-url') || '').trim();
                    const name = this.getAttribute('data-delete-name') || 'this item';
                    const form = document.getElementById('delete-confirm-form');
                    const nameEl = document.getElementById('delete-confirm-name');
                    const input = document.getElementById('delete-confirm-input');
                    const submitBtn = document.getElementById('delete-confirm-submit');
                    if (form && url) {
                        form.action = url;
                        form.dataset.deleteReady = '1';
                        if (nameEl) nameEl.textContent = name;
                        if (input) input.value = '';
                        if (submitBtn) submitBtn.disabled = true;
                    }
                    return;
                }
                if (this.type === 'submit' && this.closest('form[data-submit="server"]')) {
                    this.closest('form').submit();
                    return;
                }
                this.closest('tr')?.remove();
                this.closest('.gallery-item')?.remove();
                this.closest('.journey-item')?.remove();
                this.closest('.email-message-row')?.remove();
                const viewModal = document.getElementById('view-message-modal');
                if (viewModal?.classList.contains('active')) {
                    viewModal.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }
        });
    });

    // ========== Messages / Email Interface ==========
    const viewFullscreen = document.getElementById('email-view-fullscreen');
    const composeModal = document.getElementById('compose-message-modal');
    const messageList = document.getElementById('email-message-list');
    const emptyState = document.getElementById('email-empty-state');
    const emailToolbar = document.querySelector('.email-toolbar');
    let currentViewRow = null;

    document.getElementById('email-message-list')?.addEventListener('click', function (e) {
        const row = e.target.closest('.email-message-row');
        if (!row || e.target.closest('.action-btn')) return;
        if (!row.dataset.messageId) return;
        openMessageView(row);
    });

    function openMessageView(row) {
        currentViewRow = row;
        const from = row.dataset.from || '';
        const subject = row.dataset.subject || '';
        const date = row.dataset.date || '';
        const body = row.dataset.body || '';
        const msgId = row.dataset.messageId || '';

        if (viewFullscreen) {
            const fromEl = viewFullscreen.querySelector('#email-view-from');
            const metaLabel = viewFullscreen.querySelector('#email-view-from-label');
            if (fromEl) fromEl.textContent = from;
            if (metaLabel) metaLabel.textContent = 'From:';
            viewFullscreen.querySelector('#email-view-date').textContent = date;
            viewFullscreen.querySelector('#email-view-subject').textContent = subject;
            const bodyEl = viewFullscreen.querySelector('#email-view-body');
            bodyEl.textContent = body;
            bodyEl.style.whiteSpace = 'pre-wrap';
            viewFullscreen.querySelector('#email-view-context').textContent = 'Inbox';
            document.getElementById('email-reply-link').href = 'mailto:' + from + '?subject=Re: ' + encodeURIComponent(subject);
            const deleteBtn = document.getElementById('email-delete-btn');
            if (deleteBtn && msgId && urls.messages) {
                deleteBtn.dataset.deleteUrl = urls.messages + '/' + msgId;
                deleteBtn.dataset.deleteName = subject || 'this message';
            }
            viewFullscreen.classList.add('active');
            if (messageList) messageList.style.display = 'none';
            if (emptyState) emptyState.style.display = 'none';
            if (emailToolbar) emailToolbar.style.display = 'none';
        }
        row.classList.remove('unread');
        if (msgId && urls.messages && urls.csrf) {
            fetch(urls.messages + '/' + msgId + '/read', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': urls.csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' }
            }).catch(() => { });
        }
    }

    function closeMessageView() {
        currentViewRow = null;
        if (viewFullscreen) viewFullscreen.classList.remove('active');
        if (messageList) messageList.style.display = '';
        if (emptyState) emptyState.style.display = '';
        if (emailToolbar) emailToolbar.style.display = '';
    }

    document.getElementById('email-back-btn')?.addEventListener('click', function () {
        closeMessageView();
        if (urls.messagesRoute) window.location.href = urls.messagesRoute;
    });

    document.querySelectorAll('[data-reply-trigger]').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (composeModal) {
                const from = document.getElementById('email-view-from')?.textContent;
                if (from) document.getElementById('compose-to').value = from;
                composeModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    document.querySelectorAll('.email-folders a[data-folder]').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const folder = this.dataset.folder;
            document.querySelectorAll('.email-folders a').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            document.querySelectorAll('.email-folder-pane').forEach(pane => {
                pane.classList.toggle('active', pane.dataset.folderPane === folder);
            });
            const searchInput = document.querySelector('[data-message-search]');
            if (searchInput) searchInput.value = '';
            document.querySelectorAll('.email-message-row').forEach(r => r.style.display = '');
        });
    });

    const messageSearchInput = document.querySelector('[data-message-search]');
    if (messageSearchInput) {
        messageSearchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            const activePane = document.querySelector('.email-folder-pane.active');
            const rows = activePane ? activePane.querySelectorAll('.email-message-row') : document.querySelectorAll('.email-message-row');
            rows.forEach(row => {
                const from = (row.dataset.from || '').toLowerCase();
                const subject = (row.dataset.subject || '').toLowerCase();
                const body = (row.dataset.body || '').toLowerCase();
                const match = !query || from.includes(query) || subject.includes(query) || body.includes(query);
                row.style.display = match ? '' : 'none';
            });
        });
    }

    // ========== Global Search Box ==========
    const searchInput = document.getElementById('admin-search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            const searchableSection = document.querySelector('[data-searchable]');
            if (!searchableSection) return;
            let anyVisible = false;
            const tables = searchableSection.querySelectorAll('table[data-search-table]');
            tables.forEach(table => {
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const match = !query || text.includes(query);
                    row.style.display = match ? '' : 'none';
                    if (match) anyVisible = true;
                });
            });
            const containers = searchableSection.querySelectorAll('[data-search-container]');
            containers.forEach(container => {
                const items = container.querySelectorAll('.journey-item, .gallery-item');
                items.forEach(item => {
                    const text = ((item.dataset.searchText || '') + ' ' + item.textContent).toLowerCase();
                    const match = !query || text.includes(query);
                    item.style.display = match ? '' : 'none';
                    if (match) anyVisible = true;
                });
            });
            let noResults = searchableSection.querySelector('.search-no-results');
            if (query && !anyVisible) {
                if (!noResults) {
                    noResults = document.createElement('div');
                    noResults.className = 'search-no-results';
                    noResults.innerHTML = '<i class="fas fa-search"></i><p>No results found for "' + query + '"</p>';
                    searchableSection.appendChild(noResults);
                }
            } else if (noResults) {
                noResults.remove();
            }
        });
    }

    // ========== Dark Mode Toggle ==========
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    const themeIcon = document.getElementById('theme-icon');

    function updateThemeIcon(theme) {
        if (themeIcon) {
            themeIcon.className = theme === 'dark' ? 'fas fa-moon topbar-dark-icon' : 'fas fa-sun topbar-dark-icon';
        }
    }

    if (darkModeToggle) {
        const saved = localStorage.getItem('theme') || 'light';
        darkModeToggle.checked = saved === 'dark';
        document.documentElement.setAttribute('data-theme', saved);
        updateThemeIcon(saved);

        darkModeToggle.addEventListener('change', function (e) {
            const theme = this.checked ? 'dark' : 'light';
            
            if (!document.startViewTransition) {
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
                updateThemeIcon(theme);
                return;
            }

            // Expanding from the top left corner (0, 0)
            const x = 0;
            const y = 0;
            const endRadius = Math.hypot(window.innerWidth, window.innerHeight);

            const transition = document.startViewTransition(() => {
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
                updateThemeIcon(theme);
            });

            transition.ready.then(() => {
                document.documentElement.animate(
                    {
                        clipPath: [
                            `circle(0px at ${x}px ${y}px)`,
                            `circle(${endRadius}px at ${x}px ${y}px)`
                        ]
                    },
                    {
                        duration: 600,
                        easing: 'ease-in-out',
                        pseudoElement: '::view-transition-new(root)',
                    }
                );
            });
        });
    }

    // ========== Generic Delete Confirmation Modal (HTML popup, no alerts) ==========
    const CONFIRM_TEXT = 'confirm delete';
    const deleteConfirmInput = document.getElementById('delete-confirm-input');
    const deleteConfirmSubmit = document.getElementById('delete-confirm-submit');
    const deleteConfirmModal = document.getElementById('delete-confirm-modal');

    deleteConfirmInput?.addEventListener('input', function () {
        if (deleteConfirmSubmit) deleteConfirmSubmit.disabled = this.value.trim().toLowerCase() !== CONFIRM_TEXT;
    });

    deleteConfirmModal?.addEventListener('click', function (e) {
        if (e.target === deleteConfirmModal || e.target.closest('[data-modal-close]')) {
            if (deleteConfirmInput) deleteConfirmInput.value = '';
            if (deleteConfirmSubmit) deleteConfirmSubmit.disabled = true;
            const form = document.getElementById('delete-confirm-form');
            if (form) delete form.dataset.deleteReady;
        }
    });

    document.getElementById('delete-confirm-form')?.addEventListener('submit', function (e) {
        if (!this.dataset.deleteReady) {
            e.preventDefault();
        }
    });

    // ========== Skills Page ==========
    const editSkillBtns = document.querySelectorAll('[data-modal-open="edit-skill-modal"]');
    if (editSkillBtns.length && urls.skills) {
        editSkillBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('edit-skill-form').action = urls.skills + '/' + this.dataset.skillId;
                document.getElementById('edit-skill-category').value = this.dataset.skillCategory || '';
                document.getElementById('edit-skill-subcategory').value = this.dataset.skillSubcategory || '';
                document.getElementById('edit-skill-description').value = this.dataset.skillDescription || '';
                document.getElementById('edit-skill-level').value = this.dataset.skillPercentage ?? '';
                document.getElementById('edit-skill-order').value = this.dataset.skillOrder || '0';
                const editTags = document.getElementById('edit-skills-tech-tags');
                const editHidden = document.getElementById('edit-skill-skills-hidden');
                if (editTags && editHidden) {
                    editTags.innerHTML = '';
                    const techStacks = (this.dataset.skillTechStacks || '').split(',').map(s => s.trim()).filter(Boolean);
                    techStacks.forEach(function (name) {
                        const tag = document.createElement('span');
                        tag.className = 'tech-tag';
                        tag.dataset.tech = name;
                        tag.innerHTML = name + ' <button type="button" class="tech-tag-remove" aria-label="Remove">&times;</button>';
                        editTags.appendChild(tag);
                        tag.querySelector('.tech-tag-remove').addEventListener('click', function () {
                            tag.remove();
                            setTimeout(editEditHidden, 0);
                        });
                    });
                    editHidden.value = techStacks.join(',');
                    editEditTechPresets();
                }
            });
        });
    }

    function editEditHidden() {
        const tags = document.querySelectorAll('#edit-skills-tech-tags .tech-tag');
        const names = Array.from(tags).map(t => t.dataset.tech);
        const h = document.getElementById('edit-skill-skills-hidden');
        if (h) h.value = names.join(',');
        editEditTechPresets();
    }

    function editEditTechPresets() {
        const tags = document.querySelectorAll('#edit-skills-tech-tags .tech-tag');
        const selected = new Set(Array.from(tags).map(t => t.dataset.tech));
        document.querySelectorAll('.edit-tech-preset').forEach(btn => {
            btn.classList.toggle('selected', selected.has(btn.dataset.tech));
        });
    }

    const editSkillsTags = document.getElementById('edit-skills-tech-tags');
    const editSkillsInput = document.getElementById('edit-skills-tech-input');
    if (editSkillsTags && editSkillsInput) {
        function addEditTech(name) {
            const trimmed = String(name).trim();
            if (!trimmed) return;
            const existing = document.querySelector('#edit-skills-tech-tags .tech-tag[data-tech="' + CSS.escape(trimmed) + '"]');
            if (existing) return;
            const tag = document.createElement('span');
            tag.className = 'tech-tag';
            tag.dataset.tech = trimmed;
            tag.innerHTML = trimmed + ' <button type="button" class="tech-tag-remove" aria-label="Remove">&times;</button>';
            editSkillsTags.appendChild(tag);
            tag.querySelector('.tech-tag-remove').addEventListener('click', function () {
                tag.remove();
                editEditHidden();
            });
            editEditHidden();
        }
        editSkillsInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); addEditTech(this.value); this.value = ''; }
        });
        document.querySelectorAll('.edit-tech-preset').forEach(btn => {
            btn.addEventListener('click', function () {
                const tech = this.dataset.tech;
                const existing = document.querySelector('#edit-skills-tech-tags .tech-tag[data-tech="' + CSS.escape(tech) + '"]');
                if (existing) existing.remove();
                else addEditTech(tech);
                editEditHidden();
            });
        });
        editSkillsTags.addEventListener('click', function (e) {
            if (e.target.classList.contains('tech-tag-remove')) setTimeout(editEditHidden, 0);
        });
        document.getElementById('edit-skill-form')?.addEventListener('submit', function (e) {
            const skills = document.getElementById('edit-skill-skills-hidden')?.value?.trim();
            if (!skills) {
                e.preventDefault();
                editSkillsInput.focus();
            }
        });
    }

    const skillsTechTags = document.getElementById('skills-tech-tags');
    const skillsTechInput = document.getElementById('skills-tech-input');
    const skillsTechHidden = document.getElementById('skills-tech-stack-hidden');
    if (skillsTechTags && skillsTechInput && skillsTechHidden) {
        const selectedTechs = new Set();
        function updateHiddenInput() {
            skillsTechHidden.value = Array.from(selectedTechs).join(',');
            document.querySelectorAll('#add-skill-modal .tech-preset-btn').forEach(btn => {
                btn.classList.toggle('selected', selectedTechs.has(btn.dataset.tech));
            });
        }
        function addTech(name) {
            const trimmed = String(name).trim();
            if (!trimmed || selectedTechs.has(trimmed)) return;
            selectedTechs.add(trimmed);
            const tag = document.createElement('span');
            tag.className = 'tech-tag';
            tag.dataset.tech = trimmed;
            tag.innerHTML = trimmed + ' <button type="button" class="tech-tag-remove" aria-label="Remove">&times;</button>';
            skillsTechTags.appendChild(tag);
            tag.querySelector('.tech-tag-remove').addEventListener('click', function () {
                selectedTechs.delete(trimmed);
                tag.remove();
                updateHiddenInput();
            });
            updateHiddenInput();
        }
        skillsTechInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); addTech(this.value); this.value = ''; }
        });
        document.querySelectorAll('#add-skill-modal .tech-preset-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const tech = this.dataset.tech;
                if (selectedTechs.has(tech)) {
                    selectedTechs.delete(tech);
                    document.querySelector('#skills-tech-tags .tech-tag[data-tech="' + CSS.escape(tech) + '"]')?.remove();
                } else addTech(tech);
                updateHiddenInput();
            });
        });
    }

    // ========== Projects Page ==========
    if (urls.projects) {
        const addPresets = document.querySelectorAll('#add-project-modal .tech-preset-btn');
        const projectTechTags = document.getElementById('project-tech-tags');
        const projectTechInput = document.getElementById('project-tech-input');
        const projectTechHidden = document.getElementById('project-tech-stack-hidden');
        if (projectTechTags && projectTechInput && projectTechHidden) {
            const selectedTechs = new Set();
            function updateHidden() {
                projectTechHidden.value = Array.from(selectedTechs).join(',');
                addPresets?.forEach(btn => btn.classList.toggle('selected', selectedTechs.has(btn.dataset.tech)));
            }
            function addTech(name) {
                const trimmed = String(name).trim();
                if (!trimmed || selectedTechs.has(trimmed)) return;
                selectedTechs.add(trimmed);
                const tag = document.createElement('span');
                tag.className = 'tech-tag';
                tag.dataset.tech = trimmed;
                tag.innerHTML = trimmed + ' <button type="button" class="tech-tag-remove" aria-label="Remove">&times;</button>';
                projectTechTags.appendChild(tag);
                tag.querySelector('.tech-tag-remove').addEventListener('click', function () {
                    selectedTechs.delete(trimmed);
                    tag.remove();
                    updateHidden();
                });
                updateHidden();
            }
            projectTechInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); addTech(this.value); this.value = ''; }
            });
            addPresets?.forEach(btn => {
                btn.addEventListener('click', function () {
                    const tech = this.dataset.tech;
                    selectedTechs.has(tech) ? (selectedTechs.delete(tech), document.querySelector('#project-tech-tags .tech-tag[data-tech="' + CSS.escape(tech) + '"]')?.remove()) : addTech(tech);
                    updateHidden();
                });
            });
        }
        document.querySelectorAll('[data-modal-open="edit-project-modal"]').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('edit-project-form').action = urls.projects + '/' + this.dataset.projectId;
                document.getElementById('edit-project-title').value = this.dataset.projectTitle || '';
                document.getElementById('edit-project-category').value = this.dataset.projectCategory || 'web';
                document.getElementById('edit-project-description').value = this.dataset.projectDescription || '';
                document.getElementById('edit-project-url').value = this.dataset.projectUrl || '';
                document.getElementById('edit-project-github').value = this.dataset.projectGithub || '';
                document.getElementById('edit-project-image').value = this.dataset.projectImage || '';
                const featuredCheckbox = document.getElementById('edit-project-featured');
                if (featuredCheckbox) featuredCheckbox.checked = this.dataset.projectFeatured === '1';
                const tags = (this.dataset.projectTags || '').split(',').map(t => t.trim()).filter(Boolean);
                const editTags = document.getElementById('edit-project-tech-tags');
                const editHidden = document.getElementById('edit-project-tech-stack-hidden');
                if (editTags) {
                    editTags.innerHTML = '';
                    tags.forEach(t => {
                        const tag = document.createElement('span');
                        tag.className = 'tech-tag';
                        tag.dataset.tech = t;
                        tag.innerHTML = t + ' <button type="button" class="tech-tag-remove" aria-label="Remove">&times;</button>';
                        editTags.appendChild(tag);
                        tag.querySelector('.tech-tag-remove').addEventListener('click', function () {
                            tag.remove();
                            const ts = Array.from(document.querySelectorAll('#edit-project-tech-tags .tech-tag')).map(x => x.dataset.tech);
                            if (editHidden) editHidden.value = ts.join(',');
                        });
                    });
                    if (editHidden) editHidden.value = tags.join(',');
                }
            });
        });
        document.getElementById('edit-project-tech-input')?.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const val = this.value.trim();
                if (val) {
                    const tags = document.getElementById('edit-project-tech-tags');
                    const hidden = document.getElementById('edit-project-tech-stack-hidden');
                    const tag = document.createElement('span');
                    tag.className = 'tech-tag';
                    tag.dataset.tech = val;
                    tag.innerHTML = val + ' <button type="button" class="tech-tag-remove" aria-label="Remove">&times;</button>';
                    tags.appendChild(tag);
                    tag.querySelector('.tech-tag-remove').addEventListener('click', function () {
                        tag.remove();
                        const ts = Array.from(document.querySelectorAll('#edit-project-tech-tags .tech-tag')).map(x => x.dataset.tech);
                        if (hidden) hidden.value = ts.join(',');
                    });
                    const current = (hidden?.value || '').split(',').filter(Boolean);
                    current.push(val);
                    if (hidden) hidden.value = current.join(',');
                    this.value = '';
                }
            }
        });
    }

    // ========== Certifications Page ==========
    if (urls.certifications) {
        document.querySelectorAll('[data-modal-open="edit-cert-modal"]').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('edit-cert-form').action = urls.certifications + '/' + this.dataset.certId;
                document.getElementById('edit-cert-name').value = this.dataset.certName || '';
                document.getElementById('edit-cert-issuer').value = this.dataset.certIssuer || '';
                document.getElementById('edit-cert-date').value = this.dataset.certYear || '';
                document.getElementById('edit-cert-url').value = this.dataset.certUrl || '';
                document.getElementById('edit-cert-icon').value = this.dataset.certIcon || 'fas fa-certificate';
            });
        });
    }

    // ========== Gallery Page ==========
    if (urls.gallery) {
        document.querySelectorAll('[data-modal-open="edit-gallery-modal"]').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('edit-gallery-form').action = urls.gallery + '/' + this.dataset.itemId;
                document.getElementById('edit-gallery-url').value = this.dataset.itemUrl || '';
                document.getElementById('edit-gallery-title').value = this.dataset.itemTitle || '';
                document.getElementById('edit-gallery-description').value = this.dataset.itemDescription || '';
                document.getElementById('edit-gallery-category').value = this.dataset.itemCategory || '';
            });
        });
    }

    // ========== Settings Page ==========
    if (urls.settings) {
        document.querySelectorAll('[data-modal-open="edit-setting-modal"]').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('edit-setting-form').action = urls.settings + '/' + this.dataset.settingId;
                document.getElementById('edit-setting-key').value = this.dataset.settingKey || '';
                document.getElementById('edit-setting-value').value = this.dataset.settingValue || '';
                document.getElementById('edit-setting-type').value = this.dataset.settingType || 'string';
            });
        });
    }

    // ========== Journey Page ==========
    if (urls.journey) {
        document.querySelectorAll('[data-modal-open="edit-journey-modal"]').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('edit-journey-form').action = urls.journey + '/' + this.dataset.expId;
                document.getElementById('edit-journey-role').value = this.dataset.expRole || '';
                document.getElementById('edit-journey-company').value = this.dataset.expCompany || '';
                document.getElementById('edit-journey-from').value = this.dataset.expFrom || '';
                document.getElementById('edit-journey-to').value = this.dataset.expTo || '';
                document.getElementById('edit-journey-description').value = this.dataset.expDescription || '';
            });
        });
    }

    // ========== Education Page ==========
    if (urls.education) {
        document.querySelectorAll('[data-modal-open="edit-edu-modal"]').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('edit-edu-form').action = urls.education + '/' + this.dataset.eduId;
                document.getElementById('edit-edu-institution').value = this.dataset.eduInstitution || '';
                document.getElementById('edit-edu-degree').value = this.dataset.eduDegree || '';
                document.getElementById('edit-edu-start').value = this.dataset.eduFrom || '';
                document.getElementById('edit-edu-end').value = this.dataset.eduTo || '';
                document.getElementById('edit-edu-description').value = this.dataset.eduDescription || '';
            });
        });
    }

    // ========== Blog Posts Page ==========
    if (urls.blogPosts) {
        document.querySelectorAll('[data-modal-open="edit-blog-modal"]').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('edit-blog-form').action = urls.blogPosts + '/' + this.dataset.postId;
                document.getElementById('edit-blog-title').value = this.dataset.postTitle || '';
                document.getElementById('edit-blog-category').value = this.dataset.postCategory || '';
                document.getElementById('edit-blog-excerpt').value = this.dataset.postExcerpt || '';
                document.getElementById('edit-blog-content').value = this.dataset.postContent || '';
                document.getElementById('edit-blog-image').value = this.dataset.postImage || '';
                document.getElementById('edit-blog-author').value = this.dataset.postAuthor || '';
                document.getElementById('edit-blog-author-image').value = this.dataset.postAuthorImage || '';
                document.getElementById('edit-blog-signature').value = this.dataset.postSignature || '';
                document.getElementById('edit-blog-published').value = this.dataset.postPublished || '';
            });
        });
    }

    // ========== My Files Page ==========
    if (urls.myFiles) {
        document.querySelectorAll('[data-modal-open="edit-doc-modal"]').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('edit-doc-form').action = urls.myFiles + '/' + this.dataset.docId;
                document.getElementById('edit-doc-title').value = this.dataset.docTitle || '';
                document.getElementById('edit-doc-desc').value = this.dataset.docDescription || '';
                document.getElementById('edit-doc-type').value = this.dataset.docType || 'other';
                document.getElementById('edit-doc-url').value = this.dataset.docUrl || '';
                document.getElementById('edit-doc-button').value = this.dataset.docButton || 'View';
            });
        });
    }

    // ========== Awards Page ==========
    if (urls.awards) {
        document.querySelectorAll('[data-modal-open="edit-award-modal"]').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('edit-award-form').action = urls.awards + '/' + this.dataset.awardId;
                document.getElementById('edit-award-title').value = this.dataset.awardTitle || '';
                document.getElementById('edit-award-issuer').value = this.dataset.awardIssuer || '';
                document.getElementById('edit-award-date').value = this.dataset.awardDate || '';
                document.getElementById('edit-award-description').value = this.dataset.awardDescription || '';
                document.getElementById('edit-award-image').value = this.dataset.awardImage || '';
            });
        });
    }

    // ========== Global File Input Styling ==========
    document.querySelectorAll('input[type="file"]').forEach(input => {
        if (input.parentElement.classList.contains('custom-file-wrapper')) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'custom-file-wrapper';

        const label = document.createElement('label');
        label.className = 'custom-file-label';

        const icon = document.createElement('i');
        icon.className = 'fas fa-cloud-upload-alt';
        icon.style.marginRight = '8px';

        const text = document.createElement('span');
        const isImage = input.accept && input.accept.includes('image');
        text.innerText = isImage ? 'Choose an image' : 'Choose a file';
        text.className = 'custom-file-text';

        label.appendChild(icon);
        label.appendChild(text);

        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        wrapper.appendChild(label);

        wrapper.addEventListener('click', (e) => {
            if (e.target !== input) {
                input.click();
            }
        });

        input.addEventListener('change', (e) => {
            if (input.files && input.files.length > 0) {
                text.innerText = input.files[0].name;
                icon.className = 'fas fa-file-check';
            } else {
                text.innerText = isImage ? 'Choose an image' : 'Choose a file';
                icon.className = 'fas fa-cloud-upload-alt';
            }
        });
    });

    // ========== URL hash #add ==========
    if (window.location.hash === '#add') {
        const section = document.querySelector('[data-searchable]');
        const addBtn = section?.querySelector('[data-modal-open]');
        if (addBtn) addBtn.click();
        history.replaceState(null, '', window.location.pathname);
    }
});
