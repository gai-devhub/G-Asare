@extends('admin.admin')

@section('title', 'Blog Settings')

@section('content')
<div class="content-section active">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-cogs" ></i>Blog Settings</h1>
            <p>Configure the blog hero and sidebar elements.</p>
        </div>
        <button type="button" class="icon-btn" title="Add Configuration (Multiple configurations not currently supported in DB)">
            <i class="fas fa-plus"></i>
        </button>
    </div>
    
    <div class="settings-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title"><i class="fas fa-sliders-h" ></i> Blog Configurations</div>
            </div>
            <div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Configuration Name</th>
                            <th>Hero Title</th>
                            <th>Sidebar Title</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Default Configuration</strong></td>
                            <td class="text-truncate" style="max-width: 200px;">{{ \App\Models\Setting::get('blog_hero_title', 'Web Development Insights...') }}</td>
                            <td class="text-truncate" style="max-width: 200px;">{{ \App\Models\Setting::get('blog_sidebar_title', 'Driving Digital Solutions...') }}</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td class="table-action-cell">
                                <div class="kebab-menu-wrapper">
                                    <button class="kebab-btn"><i class="fas fa-ellipsis-v"></i></button>
                                    <ul class="kebab-dropdown">
                                        <li class="has-submenu">
                                            <button type="button"><i class="fas fa-info-circle"></i> File information <i class="fas fa-chevron-right"></i></button>
                                            <ul class="kebab-submenu kebab-submenu-left">
                                                <li><button type="button" onclick="openSidebar('details', { title: 'Blog Settings', category: 'Configuration', type: 'Settings', owner: 'System', modified: 'Unknown', created: 'Unknown', opened: 'Unknown', size: '-', description: 'Main blog configuration settings', imageUrl: '' })"><i class="fas fa-list"></i> Details</button></li>
                                                <li><button type="button" onclick="openSidebar('activity', { title: 'Blog Settings', category: 'Configuration', type: 'Settings', owner: 'System', modified: 'Unknown', created: 'Unknown', opened: 'Unknown', size: '-', description: 'Main blog configuration settings', imageUrl: '' })"><i class="fas fa-history"></i> Activity</button></li>
                                            </ul>
                                        </li>
                                        <li class="has-submenu">
                                            <button type="button"><i class="fas fa-share-alt"></i> Share <i class="fas fa-chevron-right"></i></button>
                                            <ul class="kebab-submenu kebab-submenu-left">
                                                <li><button type="button" onclick="showToast('Share dialog opened', 'success')"><i class="fas fa-user-plus"></i> Share</button></li>
                                                <li class="has-submenu">
                                                    <button type="button"><i class="fas fa-link"></i> Copy link <i class="fas fa-chevron-right"></i></button>
                                                    <ul class="kebab-submenu kebab-submenu-left">
                                                        <li><button type="button" onclick="copyToClipboard('{{ route('admin.blog-settings') }}')"><i class="fas fa-external-link-alt"></i> Copy Link</button></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="divider"></li>
                                        <li><button type="button" data-modal-open="edit-blog-settings-modal"><i class="fas fa-edit"></i> Edit</button></li>
                                        <li class="divider"></li>
                                        <li><button type="button" title="Default configuration cannot be deleted" onclick="alert('The default configuration cannot be deleted.')"><i class="fas fa-trash"></i> Delete</button></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('modals')
<div class="modal-overlay" id="edit-blog-settings-modal" data-modal>
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3><i class="fas fa-edit" ></i> Edit Default Configuration</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.settings.bulk') }}" data-submit="server" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div id="blog-settings-step-1">
                <h4 class="mb-3">Hero Section</h4>
                <div class="form-group">
                    <label for="blog-hero-title">Blog Hero Title</label>
                    <input type="text" name="blog_hero_title" id="blog-hero-title" value="{{ \App\Models\Setting::get('blog_hero_title', 'Web Development Insights, Trends and News') }}">
                </div>
                <div class="form-group">
                    <label for="blog-hero-description">Blog Hero Description</label>
                    <textarea name="blog_hero_description" id="blog-hero-description" rows="2">{{ \App\Models\Setting::get('blog_hero_description', 'Stay informed on the latest web technologies, framework updates, design trends, and development best practices.') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="blog-hero-image">Blog Hero Image (path, e.g. images/gilly.jpeg)</label>
                    <input type="text" name="blog_hero_image" id="blog-hero-image" value="{{ \App\Models\Setting::get('blog_hero_image', 'images/gilly.jpeg') }}" placeholder="images/gilly.jpeg">
                </div>
                
                <h4 class="mt-4 mb-3">Sidebar Section</h4>
                <div class="form-group">
                    <label for="blog-sidebar-title">Blog Sidebar Title</label>
                    <input type="text" name="blog_sidebar_title" id="blog-sidebar-title" value="{{ \App\Models\Setting::get('blog_sidebar_title', 'Driving Digital Solutions for a Stronger Online Future') }}">
                </div>
                <div class="form-group">
                    <label for="blog-sidebar-description">Blog Sidebar Description</label>
                    <textarea name="blog_sidebar_description" id="blog-sidebar-description" rows="2">{{ \App\Models\Setting::get('blog_sidebar_description', 'I help businesses build web experiences that convert visitors into customers and scale with growth.') }}</textarea>
                </div>
                </div> <!-- End step 1 -->
                
                <div id="blog-settings-step-2" style="display: none;">
                <h4 class="mt-4 mb-2">Sidebar Stats</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Stat 1 Label</label>
                        <input type="text" name="blog_sidebar_stat1_label" value="{{ \App\Models\Setting::get('blog_sidebar_stat1_label', 'Years experience') }}">
                    </div>
                    <div class="form-group">
                        <label>Stat 1 Value</label>
                        <input type="text" name="blog_sidebar_stat1_value" value="{{ \App\Models\Setting::get('blog_sidebar_stat1_value', '5+') }}">
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Stat 2 Label</label>
                        <input type="text" name="blog_sidebar_stat2_label" value="{{ \App\Models\Setting::get('blog_sidebar_stat2_label', 'Happy clients') }}">
                    </div>
                    <div class="form-group">
                        <label>Stat 2 Value</label>
                        <input type="text" name="blog_sidebar_stat2_value" value="{{ \App\Models\Setting::get('blog_sidebar_stat2_value', '80+') }}">
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Stat 3 Label</label>
                        <input type="text" name="blog_sidebar_stat3_label" value="{{ \App\Models\Setting::get('blog_sidebar_stat3_label', 'Client satisfaction') }}">
                    </div>
                    <div class="form-group">
                        <label>Stat 3 Value</label>
                        <input type="text" name="blog_sidebar_stat3_value" value="{{ \App\Models\Setting::get('blog_sidebar_stat3_value', '98%') }}">
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Stat 4 Label</label>
                        <input type="text" name="blog_sidebar_stat4_label" value="{{ \App\Models\Setting::get('blog_sidebar_stat4_label', 'Projects delivered') }}">
                    </div>
                    <div class="form-group">
                        <label>Stat 4 Value</label>
                        <input type="text" name="blog_sidebar_stat4_value" value="{{ \App\Models\Setting::get('blog_sidebar_stat4_value', '50+') }}">
                    </div>
                </div>

                <h4 class="mt-4 mb-2">Sidebar Video</h4>
                <div class="form-group">
                    <label for="blog-sidebar-video">Upload Video File</label>
                    <input type="file" name="blog_sidebar_video" id="blog-sidebar-video" accept="video/*">
                    @if(\App\Models\Setting::get('blog_sidebar_video'))
                        <small class="text-muted mt-1 d-block">Current video: <a href="{{ asset(\App\Models\Setting::get('blog_sidebar_video')) }}" target="_blank">View</a></small>
                    @endif
                </div>
                </div> <!-- End step 2 -->
            </div>
            <div class="modal-footer" id="blog-settings-footer-1">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('blog-settings-step-1').style.display='none'; document.getElementById('blog-settings-step-2').style.display='block'; document.getElementById('blog-settings-footer-1').style.display='none'; document.getElementById('blog-settings-footer-2').style.display='flex';">Next <i class="fas fa-arrow-right"></i></button>
            </div>
            <div class="modal-footer" id="blog-settings-footer-2" style="display: none;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('blog-settings-step-2').style.display='none'; document.getElementById('blog-settings-step-1').style.display='block'; document.getElementById('blog-settings-footer-2').style.display='none'; document.getElementById('blog-settings-footer-1').style.display='flex';"><i class="fas fa-arrow-left"></i> Back</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Configuration</button>
            </div>
        </form>
    </div>
</div>
@endpush
@endsection


