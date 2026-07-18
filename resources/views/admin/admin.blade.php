<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portfolio Manager') - Admin</title>
    @include('component.theme-init')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
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
    <div class="dashboard">
        <!-- Sidebar -->
        @php $profile = \App\Models\Profile::first(); @endphp
        <div class="sidebar">
            <div class="sidebar-profile">
                <img src="{{ asset($profile->image_url ?? 'images/gilly.jpeg') }}" alt="Profile" class="sidebar-avatar" style="object-fit: cover;">
                <h3 class="sidebar-username">{{ $profile->name ?? 'G-BASE' }}</h3>
                <p class="sidebar-role">Administrator</p>
            </div>
            
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.overview') }}" class="{{ request()->routeIs('admin.overview') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> <span>Overview</span></a></li>
                <li class="sidebar-dropdown {{ request()->routeIs('admin.about-me') || request()->routeIs('admin.web') || request()->routeIs('admin.skills') || request()->routeIs('admin.education') ? 'active open' : '' }}">
                    <a href="#" class="sidebar-dropdown-trigger"><i class="fas fa-user"></i> <span>Profile</span> <i class="fas fa-chevron-down dropdown-arrow"></i></a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{ route('admin.about-me') }}" class="{{ request()->routeIs('admin.about-me') ? 'active' : '' }}"><i class="fas fa-user"></i> About Me</a></li>
                        <li><a href="{{ route('admin.web') }}" class="{{ request()->routeIs('admin.web') ? 'active' : '' }}"><i class="fas fa-globe"></i> <span>Web Content</span></a></li>
                        <li><a href="{{ route('admin.skills') }}" class="{{ request()->routeIs('admin.skills') ? 'active' : '' }}"><i class="fas fa-code"></i> Skills</a></li>
                        <li><a href="{{ route('admin.education') }}" class="{{ request()->routeIs('admin.education') ? 'active' : '' }}"><i class="fas fa-graduation-cap"></i> Education</a></li>
                    </ul>
                </li>
                <li class="sidebar-dropdown {{ request()->routeIs('admin.certifications') || request()->routeIs('admin.awards') || request()->routeIs('admin.my-files') ? 'active open' : '' }}">
                    <a href="#" class="sidebar-dropdown-trigger"><i class="fas fa-folder-open"></i> <span>Documents</span> <i class="fas fa-chevron-down dropdown-arrow"></i></a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{ route('admin.certifications') }}" class="{{ request()->routeIs('admin.certifications') ? 'active' : '' }}"><i class="fas fa-certificate"></i> Certification</a></li>
                        <li><a href="{{ route('admin.awards') }}" class="{{ request()->routeIs('admin.awards') ? 'active' : '' }}"><i class="fas fa-trophy"></i> Awards</a></li>
                        <li><a href="{{ route('admin.my-files') }}" class="{{ request()->routeIs('admin.my-files') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> My Files</a></li>
                    </ul>
                </li>
                <li class="sidebar-dropdown {{ request()->routeIs('admin.document-activity') || request()->routeIs('admin.web-admin') ? 'active open' : '' }}">
                    <a href="#" class="sidebar-dropdown-trigger"><i class="fa fa-tasks"></i> <span>Activity</span> <i class="fas fa-chevron-down dropdown-arrow"></i></a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{ route('admin.document-activity') }}" class="{{ request()->routeIs('admin.document-activity') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Document Activity</a></li>
                        <li><a href="{{ route('admin.web-admin') }}" class="{{ request()->routeIs('admin.web-admin') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Web & Admin</a></li>
                    </ul>
                </li>
                <li class="sidebar-dropdown {{ request()->routeIs('admin.projects') || request()->routeIs('admin.journey') || request()->routeIs('admin.gallery') ? 'active open' : '' }}">
                    <a href="#" class="sidebar-dropdown-trigger"><i class="fas fa-image"></i> <span>Showcase</span> <i class="fas fa-chevron-down dropdown-arrow"></i></a>
                    <ul class="sidebar-submenu">
                       <li><a href="{{ route('admin.projects') }}" class="{{ request()->routeIs('admin.projects') ? 'active' : '' }}"><i class="fas fa-briefcase"></i> <span>Projects</span></a></li>
                       <li><a href="{{ route('admin.journey') }}" class="{{ request()->routeIs('admin.journey') ? 'active' : '' }}"><i class="fas fa-route"></i> <span>Journey</span></a></li>
                       <li><a href="{{ route('admin.gallery') }}" class="{{ request()->routeIs('admin.gallery') ? 'active' : '' }}"><i class="fas fa-images"></i> <span>Gallery</span></a></li>    
                    </ul>
                </li>
                <li><a href="{{ route('admin.messages') }}" class="{{ request()->routeIs('admin.messages') ? 'active' : '' }}"><i class="fas fa-envelope"></i> <span>Messages</span></a></li>
                <li><a href="{{ route('admin.subscribers') }}" class="{{ request()->routeIs('admin.subscribers') ? 'active' : '' }}"><i class="fas fa-users"></i> <span>Subscribers</span></a></li>
                <li class="sidebar-dropdown {{ request()->routeIs('admin.blog-posts') || request()->routeIs('admin.blog-settings') ? 'active open' : '' }}">
                    <a href="#" class="sidebar-dropdown-trigger"><i class="fas fa-blog"></i> <span>Blog</span> <i class="fas fa-chevron-down dropdown-arrow"></i></a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{ route('admin.blog-posts') }}" class="{{ request()->routeIs('admin.blog-posts') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> Posts</a></li>
                        <li><a href="{{ route('admin.blog-settings') }}" class="{{ request()->routeIs('admin.blog-settings') ? 'active' : '' }}"><i class="fas fa-cog"></i> Settings</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
            </ul>
            
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="sidebar-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Topbar -->
            <div class="topbar">
                <div class="search-box" id="admin-search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="admin-search-input" placeholder="Search in this page..." autocomplete="off">
                </div>
                
                <div class="topbar-actions">
                    @stack('topbar-add')
                    <label class="topbar-icon-btn" title="Toggle Theme" style="cursor: pointer; margin: 0; padding: 0.8rem; display: flex; align-items: center; justify-content: center;">
                        <input type="checkbox" id="dark-mode-toggle" style="display: none;">
                        <i class="fas fa-moon topbar-dark-icon" id="theme-icon"></i>
                    </label>
                    <a href="{{ route('admin.document-activity') }}" class="topbar-icon-btn" title="Document Activity"><i class="fas fa-file-alt"></i></a>
                    <a href="{{ route('admin.messages') }}" class="topbar-icon-btn" title="Messages"><i class="fas fa-envelope"></i></a>
                </div>
            </div>
            
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
            if (s) showToast(s.dataset.msg, 'success');
            if (e) showToast(e.dataset.msg, 'error');
            if (v) v.dataset.msg.split(' | ').forEach(function(m) { showToast(m, 'error'); });
        });
    })();
    </script>
    @stack('scripts')
</body>
</html>
