<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portfolio Manager') - Admin</title>
    @include('component.theme-init')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ time() }}">
    <style>
        /* ── Toast Notifications ───────────────────────────── */
        #toast-container {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            pointer-events: none;
        }
        .toast {
            pointer-events: all;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            min-width: 300px;
            max-width: 420px;
            padding: 1rem 1.1rem 0.85rem;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,.18);
            background: #fff;
            border-left: 4px solid #22c55e;
            position: relative;
            overflow: hidden;
            transform: translateX(120%);
            opacity: 0;
            transition: transform .35s cubic-bezier(.34,1.56,.64,1), opacity .25s ease;
        }
        .toast.toast-error  { border-left-color: #ef4444; }
        .toast.toast-show   { transform: translateX(0); opacity: 1; }
        .toast.toast-hide   { transform: translateX(120%); opacity: 0; }
        .toast-icon {
            width: 32px; height: 32px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .toast-success .toast-icon { background: #dcfce7; color: #16a34a; }
        .toast-error   .toast-icon { background: #fee2e2; color: #dc2626; }
        .toast-body { flex: 1; }
        .toast-title {
            font-weight: 600;
            font-size: .85rem;
            margin-bottom: 2px;
            color: #111827;
        }
        .toast-success .toast-title { color: #166534; }
        .toast-error   .toast-title { color: #991b1b; }
        .toast-msg {
            font-size: .82rem;
            color: #4b5563;
            line-height: 1.4;
        }
        .toast-close {
            background: none; border: none; cursor: pointer;
            color: #9ca3af; font-size: .8rem; padding: 0;
            line-height: 1; flex-shrink: 0; margin-top: 2px;
            transition: color .15s;
        }
        .toast-close:hover { color: #374151; }
        .toast-progress {
            position: absolute;
            bottom: 0; left: 0;
            height: 3px;
            border-radius: 0 0 0 12px;
            animation: toastProgress 4s linear forwards;
        }
        .toast-success .toast-progress { background: #22c55e; }
        .toast-error   .toast-progress { background: #ef4444; }
        @keyframes toastProgress {
            from { width: 100%; }
            to   { width: 0%; }
        }
        @media (prefers-color-scheme: dark) {
            .toast { background: #1f2937; }
            .toast-title { color: #f9fafb; }
            .toast-msg   { color: #d1d5db; }
        }
    </style>
</head>
<body>
    <div class="drive-app">
        <!-- Topbar -->
        <header class="drive-header">
            <div class="header-left">
                <button class="icon-btn mobile-menu-btn" id="mobile-menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="logo">
                    <span class="logo-icon"><i class="fab fa-google-drive"></i></span>
                    <span class="logo-text">Portfolio</span>
                </div>
            </div>
            
            <div class="header-middle">
                <div class="search-box" id="admin-search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="admin-search-input" placeholder="Search in Drive..." autocomplete="off">
                </div>
            </div>
            
            <div class="header-right">
                <label class="icon-btn" title="Toggle Theme">
                    <input type="checkbox" id="dark-mode-toggle" style="display: none;">
                    <i class="fas fa-moon" id="theme-icon"></i>
                </label>
                <a href="{{ route('admin.document-activity') }}" class="icon-btn" title="Document Activity"><i class="fas fa-file-alt"></i></a>
                <a href="{{ route('admin.messages') }}" class="icon-btn" title="Messages"><i class="fas fa-envelope"></i></a>
                @php $profile = \App\Models\Profile::first(); @endphp
                <div class="header-avatar">
                    <img src="{{ asset($profile->image_url ?? 'images/gilly.jpeg') }}" alt="Profile">
                </div>
            </div>
        </header>

        <div class="drive-body">
            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="new-dropdown-wrapper">
                    <button class="btn-new" id="btn-new-dropdown">
                        <i class="fas fa-plus"></i>
                        <span>New</span>
                    </button>
                    <ul class="new-dropdown-menu" id="new-dropdown-menu">
                        <li><a href="{{ route('admin.gallery') }}"><i class="fas fa-folder-plus"></i> New folder</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ route('admin.my-files') }}"><i class="fas fa-file-upload"></i> File upload</a></li>
                        <li><a href="{{ route('admin.my-files') }}"><i class="fas fa-upload"></i> Folder upload</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ route('admin.projects') }}"><i class="fas fa-layer-group"></i> New project</a></li>
                        <li><a href="{{ route('admin.blog-posts') }}"><i class="fas fa-pen"></i> New blog post</a></li>
                    </ul>
                </div>

                <ul class="sidebar-menu">
                    <li><a href="{{ route('admin.overview') }}" class="{{ request()->routeIs('admin.overview') ? 'active' : '' }}"><i class="fas fa-house"></i> <span>Overview</span></a></li>
                    
                    <li class="sidebar-dropdown {{ request()->routeIs('admin.about-me') || request()->routeIs('admin.web') || request()->routeIs('admin.skills') || request()->routeIs('admin.education') ? 'active open' : '' }}">
                        <a href="#" class="sidebar-dropdown-trigger"><i class="fas fa-user-circle"></i> <span>Profile</span> <i class="fas fa-chevron-down dropdown-arrow"></i></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('admin.about-me') }}" class="{{ request()->routeIs('admin.about-me') ? 'active' : '' }}">About Me</a></li>
                            <li><a href="{{ route('admin.web') }}" class="{{ request()->routeIs('admin.web') ? 'active' : '' }}">Web Content</a></li>
                            <li><a href="{{ route('admin.skills') }}" class="{{ request()->routeIs('admin.skills') ? 'active' : '' }}">Skills</a></li>
                            <li><a href="{{ route('admin.education') }}" class="{{ request()->routeIs('admin.education') ? 'active' : '' }}">Education</a></li>
                        </ul>
                    </li>
                    
                    <li class="sidebar-dropdown {{ request()->routeIs('admin.certifications') || request()->routeIs('admin.awards') || request()->routeIs('admin.my-files') ? 'active open' : '' }}">
                        <a href="#" class="sidebar-dropdown-trigger"><i class="fas fa-folder"></i> <span>Documents</span> <i class="fas fa-chevron-down dropdown-arrow"></i></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('admin.certifications') }}" class="{{ request()->routeIs('admin.certifications') ? 'active' : '' }}">Certification</a></li>
                            <li><a href="{{ route('admin.awards') }}" class="{{ request()->routeIs('admin.awards') ? 'active' : '' }}">Awards</a></li>
                            <li><a href="{{ route('admin.my-files') }}" class="{{ request()->routeIs('admin.my-files') ? 'active' : '' }}">My Files</a></li>
                        </ul>
                    </li>
                    
                    <li class="sidebar-dropdown {{ request()->routeIs('admin.document-activity') || request()->routeIs('admin.web-admin') ? 'active open' : '' }}">
                        <a href="#" class="sidebar-dropdown-trigger"><i class="fas fa-chart-pie"></i> <span>Activity</span> <i class="fas fa-chevron-down dropdown-arrow"></i></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('admin.document-activity') }}" class="{{ request()->routeIs('admin.document-activity') ? 'active' : '' }}">Document Activity</a></li>
                            <li><a href="{{ route('admin.web-admin') }}" class="{{ request()->routeIs('admin.web-admin') ? 'active' : '' }}">Web & Admin</a></li>
                        </ul>
                    </li>
                    
                    <li class="sidebar-dropdown {{ request()->routeIs('admin.projects') || request()->routeIs('admin.journey') || request()->routeIs('admin.gallery') ? 'active open' : '' }}">
                        <a href="#" class="sidebar-dropdown-trigger"><i class="fas fa-images"></i> <span>Showcase</span> <i class="fas fa-chevron-down dropdown-arrow"></i></a>
                        <ul class="sidebar-submenu">
                           <li><a href="{{ route('admin.projects') }}" class="{{ request()->routeIs('admin.projects') ? 'active' : '' }}">Projects</a></li>
                           <li><a href="{{ route('admin.journey') }}" class="{{ request()->routeIs('admin.journey') ? 'active' : '' }}">Journey</a></li>
                           <li><a href="{{ route('admin.gallery') }}" class="{{ request()->routeIs('admin.gallery') ? 'active' : '' }}">Gallery</a></li>    
                        </ul>
                    </li>
                    
                    <li><a href="{{ route('admin.messages') }}" class="{{ request()->routeIs('admin.messages') ? 'active' : '' }}"><i class="fas fa-inbox"></i> <span>Messages</span></a></li>
                    <li><a href="{{ route('admin.subscribers') }}" class="{{ request()->routeIs('admin.subscribers') ? 'active' : '' }}"><i class="fas fa-users"></i> <span>Subscribers</span></a></li>
                    
                    <li class="sidebar-dropdown {{ request()->routeIs('admin.blog-posts') || request()->routeIs('admin.blog-settings') ? 'active open' : '' }}">
                        <a href="#" class="sidebar-dropdown-trigger"><i class="fas fa-book"></i> <span>Blog</span> <i class="fas fa-chevron-down dropdown-arrow"></i></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('admin.blog-posts') }}" class="{{ request()->routeIs('admin.blog-posts') ? 'active' : '' }}">Posts</a></li>
                            <li><a href="{{ route('admin.blog-settings') }}" class="{{ request()->routeIs('admin.blog-settings') ? 'active' : '' }}">Settings</a></li>
                        </ul>
                    </li>
                    
                    <li><a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}"><i class="fas fa-gear"></i> <span>Settings</span></a></li>
                </ul>
                
                <div class="sidebar-footer">
                    <div class="storage-mini">
                        <div class="storage-info">
                            <i class="fas fa-cloud"></i> Storage
                        </div>
                        <div class="storage-bar">
                            <div class="storage-fill" style="width: {{ $globalStoragePercent }}%;"></div>
                        </div>
                        <div class="storage-text">{{ $globalStorageMB }} MB of 1 GB used</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                </div>
            </aside>
            
            <!-- Main Content Container (The "White Drive Canvas") -->
            <main class="main-content">
                <div class="content-wrapper">
                    {{-- Flash data consumed by toast JS below --}}
                    @if(session('success'))
                        <div id="flash-success" data-msg="{{ session('success') }}" hidden></div>
                    @endif
                    @if(session('error'))
                        <div id="flash-error" data-msg="{{ session('error') }}" hidden></div>
                    @endif
                    @if($errors->any())
                        <div id="flash-errors" data-msg="{{ implode(' | ', $errors->all()) }}" hidden></div>
                    @endif
                    
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('modals')

    {{-- Generic delete confirmation modal (type "confirm delete" to proceed) --}}
    <div class="modal-overlay" id="delete-confirm-modal" data-modal>
        <div class="modal">
            <div class="modal-header">
                <h3>Confirm Delete</h3>
                <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <form method="POST" action="" id="delete-confirm-form" data-submit="server">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>You are about to delete <strong id="delete-confirm-name"></strong>. This action cannot be undone.</p>
                    <p>Type <strong>confirm delete</strong> below to proceed:</p>
                    <div class="form-group">
                        <input type="text" id="delete-confirm-input" placeholder="Type 'confirm delete'" autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                    <button type="submit" class="btn btn-danger" id="delete-confirm-submit" disabled><i class="fas fa-trash"></i> Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    window.ADMIN_URLS = {
        base: '{{ url("admin") }}',
        messages: '{{ url("admin/messages") }}',
        messagesRoute: '{{ route("admin.messages") }}',
        skills: '{{ url("admin/skills") }}',
        projects: '{{ url("admin/projects") }}',
        certifications: '{{ url("admin/certifications") }}',
        gallery: '{{ url("admin/gallery") }}',
        settings: '{{ url("admin/settings") }}',
        web: '{{ url("admin/web") }}',
        journey: '{{ url("admin/journey") }}',
        education: '{{ url("admin/education") }}',
        blogPosts: '{{ url("admin/blog-posts") }}',
        blogSettings: '{{ url("admin/blog-settings") }}',
        myFiles: '{{ url("admin/my-files") }}',
        awards: '{{ url("admin/awards") }}',
        csrf: '{{ csrf_token() }}'
    };
    </script>
    <script src="{{ asset('js/admin-page.js') }}?v={{ time() }}"></script>

    {{-- Flash message data --}}
    @if(session('success'))
        <div id="flash-success" data-msg="{{ session('success') }}" style="display: none;"></div>
    @endif
    @if(session('error'))
        <div id="flash-error" data-msg="{{ session('error') }}" style="display: none;"></div>
    @endif
    @if($errors->any())
        <div id="flash-errors" data-msg="{{ implode(' | ', $errors->all()) }}" style="display: none;"></div>
    @endif

    {{-- Toast container --}}
    <div id="toast-container"></div>

    <script>
    (function () {
        function showToast(message, type) {
            var container = document.getElementById('toast-container');
            var toast = document.createElement('div');
            var isSuccess = type === 'success';
            toast.className = 'toast toast-' + (isSuccess ? 'success' : 'error');
            toast.innerHTML =
                '<div class="toast-icon"><i class="fas fa-' + (isSuccess ? 'check' : 'exclamation') + '"></i></div>' +
                '<div class="toast-body">' +
                    '<div class="toast-title">' + (isSuccess ? 'Success' : 'Error') + '</div>' +
                    '<div class="toast-msg">' + message + '</div>' +
                '</div>' +
                '<button class="toast-close" aria-label="Close"><i class="fas fa-times"></i></button>' +
                '<div class="toast-progress"></div>';

            container.appendChild(toast);

            // Trigger slide-in
            requestAnimationFrame(function() {
                requestAnimationFrame(function() { toast.classList.add('toast-show'); });
            });

            // Close button
            toast.querySelector('.toast-close').addEventListener('click', function() { dismiss(toast); });

            // Auto-dismiss after 4s
            var timer = setTimeout(function() { dismiss(toast); }, 4000);

            function dismiss(el) {
                clearTimeout(timer);
                el.classList.remove('toast-show');
                el.classList.add('toast-hide');
                setTimeout(function() { if (el.parentNode) el.parentNode.removeChild(el); }, 400);
            }
        }

        window.showToast = showToast;

        // Fire flashes from server
        document.addEventListener('DOMContentLoaded', function () {
            var s = document.getElementById('flash-success');
            var e = document.getElementById('flash-error');
            var v = document.getElementById('flash-errors');
            if (v) v.dataset.msg.split(' | ').forEach(function(m) { showToast(m, 'error'); });
        });

        // Global Copy to Clipboard function
        window.copyToClipboard = function(text) {
            if (!text) {
                showToast('No link available to copy.', 'error');
                return;
            }
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(function() {
                    showToast('Link copied to clipboard!', 'success');
                }).catch(function() {
                    showToast('Failed to copy link.', 'error');
                });
            } else {
                // Fallback
                var textArea = document.createElement("textarea");
                textArea.value = text;
                textArea.style.position = "fixed";
                textArea.style.left = "-999999px";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    showToast('Link copied to clipboard!', 'success');
                } catch (err) {
                    showToast('Failed to copy link.', 'error');
                }
                textArea.remove();
            }
        };
        
        // Mobile Sidebar Toggle
        var menuToggle = document.getElementById('mobile-menu-toggle');
        var sidebar = document.querySelector('.sidebar');
        if(menuToggle && sidebar) {
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('mobile-open');
            });
        }

        // New Button Dropdown Toggle
        var btnNewDrop = document.getElementById('btn-new-dropdown');
        var newMenuDrop = document.getElementById('new-dropdown-menu');
        if(btnNewDrop && newMenuDrop) {
            btnNewDrop.addEventListener('click', function(e) {
                e.stopPropagation();
                newMenuDrop.classList.toggle('show');
            });
            document.addEventListener('click', function(e) {
                if(!btnNewDrop.contains(e.target) && !newMenuDrop.contains(e.target)) {
                    newMenuDrop.classList.remove('show');
                }
            });
        }

        // Kebab Menu Dropdown Toggle
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.kebab-btn');
            
            // Close all other open kebab menus
            document.querySelectorAll('.kebab-dropdown.show').forEach(function(menu) {
                if (!btn || menu.previousElementSibling !== btn) {
                    menu.classList.remove('show');
                }
            });

            if (btn) {
                e.stopPropagation();
                var dropdown = btn.nextElementSibling;
                if(dropdown && dropdown.classList.contains('kebab-dropdown')) {
                    dropdown.classList.toggle('show');
                }
            }
        });
        // Right Sidebar Logic
        window.openSidebar = function(tabType, data) {
            var sidebar = document.getElementById('global-details-sidebar');
            var overlay = document.getElementById('global-sidebar-overlay');
            var mainContent = document.querySelector('.main-content');
            if(sidebar && overlay) {
                // Populate data
                document.getElementById('sidebar-item-title').textContent = data.title || 'Item Details';
                document.getElementById('sidebar-item-owner').textContent = data.owner || 'me';
                document.getElementById('sidebar-item-modified').textContent = data.modified || 'Unknown';
                document.getElementById('sidebar-item-opened').textContent = data.opened || 'Unknown';
                document.getElementById('sidebar-item-created').textContent = data.created || 'Unknown';
                document.getElementById('sidebar-item-description').textContent = data.description || 'Add description';
                document.getElementById('sidebar-item-type').textContent = data.type || 'File';
                document.getElementById('sidebar-item-size').textContent = data.size || '-';
                
                var img = document.getElementById('sidebar-item-image');
                if(data.imageUrl) {
                    img.src = data.imageUrl;
                    img.style.display = 'block';
                } else {
                    img.style.display = 'none';
                    img.src = '';
                }

                // Switch to the correct tab
                document.querySelectorAll('.sidebar-tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.sidebar-tab-pane').forEach(p => p.classList.remove('active'));
                
                var targetBtn = document.querySelector('.sidebar-tab-btn[data-tab="' + tabType + '"]');
                var targetPane = document.getElementById('sidebar-pane-' + tabType);
                if (targetBtn) targetBtn.classList.add('active');
                if (targetPane) targetPane.classList.add('active');

                // Show sidebar
                sidebar.classList.add('show');
                overlay.classList.add('show');
                if(mainContent) mainContent.classList.add('sidebar-open');
            }
        };

        window.closeSidebar = function() {
            var sidebar = document.getElementById('global-details-sidebar');
            var overlay = document.getElementById('global-sidebar-overlay');
            var mainContent = document.querySelector('.main-content');
            if(sidebar && overlay) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                if(mainContent) mainContent.classList.remove('sidebar-open');
            }
        };

        // Sidebar Tabs
        document.querySelectorAll('.sidebar-tab-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.sidebar-tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.sidebar-tab-pane').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('sidebar-pane-' + this.dataset.tab).classList.add('active');
            });
        });

    })();
    </script>
    
    <!-- Global Right Sidebar overlay and structure -->
    <div class="sidebar-overlay" id="global-sidebar-overlay" onclick="closeSidebar()"></div>
    <div class="global-details-sidebar" id="global-details-sidebar">
        <div class="sidebar-header">
            <h3 id="sidebar-item-title" class="text-truncate">Item Details</h3>
            <button type="button" class="sidebar-close-btn" onclick="closeSidebar()"><i class="fas fa-times"></i></button>
        </div>
        <div class="sidebar-tabs">
            <button class="sidebar-tab-btn active" data-tab="details">Details</button>
            <button class="sidebar-tab-btn" data-tab="activity">Activity</button>
        </div>
        <div class="sidebar-content">
            <div class="sidebar-tab-pane active" id="sidebar-pane-details">
                <img id="sidebar-item-image" src="" alt="Preview" style="display:none; width: 100%; border-radius: 8px; margin-bottom: 1rem; border: 1px solid var(--border-color); object-fit: cover; max-height: 200px;">
                
                <div class="sidebar-section">
                    <h4>Who has access</h4>
                    <div class="access-info">
                        <img src="{{ asset($profile->image_url ?? 'images/gilly.jpeg') }}" alt="Profile" class="access-avatar">
                        <div>
                            <strong>Private to you</strong>
                        </div>
                    </div>
                    <button class="btn btn-outline" style="margin-top: 0.5rem; width: 100%;">Manage access</button>
                </div>
                
                <div class="sidebar-section">
                    <h4><i class="fas fa-shield-alt"></i> Security limitations</h4>
                    <p class="text-muted mt-2"><strong>No limitations applied</strong></p>
                    <p class="text-muted" style="font-size: 0.75rem;">If any are applied, they will appear here</p>
                </div>

                <div class="sidebar-section">
                    <h4>File details</h4>
                    <div class="detail-row">
                        <span class="detail-label">Type</span>
                        <span class="detail-value" id="sidebar-item-type">File</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Size</span>
                        <span class="detail-value" id="sidebar-item-size">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Location</span>
                        <span class="detail-value"><button class="btn btn-sm btn-outline"><i class="fab fa-google-drive"></i> My Drive</button></span>
                    </div>
                    <div class="detail-row mt-3">
                        <span class="detail-label">Owner</span>
                        <span class="detail-value" id="sidebar-item-owner">me</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Modified</span>
                        <span class="detail-value" id="sidebar-item-modified">Unknown</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Opened</span>
                        <span class="detail-value" id="sidebar-item-opened">Unknown</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Created</span>
                        <span class="detail-value" id="sidebar-item-created">Unknown</span>
                    </div>
                </div>
                
                <div class="sidebar-section">
                    <h4>Description</h4>
                    <div class="description-box" id="sidebar-item-description">
                        Add description
                    </div>
                </div>
            </div>
            <div class="sidebar-tab-pane" id="sidebar-pane-activity">
                <div class="activity-timeline">
                    <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 1rem;">Last month</p>
                    <div class="activity-item">
                        <img src="{{ asset($profile->image_url ?? 'images/gilly.jpeg') }}" alt="Profile" class="activity-avatar">
                        <div class="activity-details">
                            <p><strong>You</strong> created an item in</p>
                            <span class="activity-time">9:20 PM Jun 20</span>
                            <div class="activity-target">
                                <i class="fab fa-google-drive"></i> My Drive
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
