@extends('admin.admin')

@section('title', 'Blog Settings')

@section('content')
<div class="content-section active">
    <div class="page-header">
        <h1><i class="fas fa-cogs" style="margin-right: 12px; color: var(--color-primary);"></i>Blog Settings</h1>
        <p>Configure the blog hero and sidebar elements.</p>
    </div>
    
    <div class="settings-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title"><i class="fas fa-sliders-h" style="margin-right: 8px;"></i> Blog Configuration</div>
            </div>
            <form method="POST" action="{{ route('admin.settings.bulk') }}" data-submit="server" enctype="multipart/form-data">
                @csrf
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
                <div class="form-group">
                    <label for="blog-sidebar-title">Blog Sidebar Title</label>
                    <input type="text" name="blog_sidebar_title" id="blog-sidebar-title" value="{{ \App\Models\Setting::get('blog_sidebar_title', 'Driving Digital Solutions for a Stronger Online Future') }}">
                </div>
                <div class="form-group">
                    <label for="blog-sidebar-description">Blog Sidebar Description</label>
                    <textarea name="blog_sidebar_description" id="blog-sidebar-description" rows="2">{{ \App\Models\Setting::get('blog_sidebar_description', 'I help businesses build web experiences that convert visitors into customers and scale with growth.') }}</textarea>
                </div>
                
                <h4 class="mt-4 mb-2">Sidebar Stats</h4>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Stat 1 Label</label>
                        <input type="text" name="blog_sidebar_stat1_label" value="{{ \App\Models\Setting::get('blog_sidebar_stat1_label', 'Years experience') }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Stat 1 Value</label>
                        <input type="text" name="blog_sidebar_stat1_value" value="{{ \App\Models\Setting::get('blog_sidebar_stat1_value', '5+') }}">
                    </div>
                    
                    <div class="col-md-6 form-group">
                        <label>Stat 2 Label</label>
                        <input type="text" name="blog_sidebar_stat2_label" value="{{ \App\Models\Setting::get('blog_sidebar_stat2_label', 'Happy clients') }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Stat 2 Value</label>
                        <input type="text" name="blog_sidebar_stat2_value" value="{{ \App\Models\Setting::get('blog_sidebar_stat2_value', '80+') }}">
                    </div>
                    
                    <div class="col-md-6 form-group">
                        <label>Stat 3 Label</label>
                        <input type="text" name="blog_sidebar_stat3_label" value="{{ \App\Models\Setting::get('blog_sidebar_stat3_label', 'Client satisfaction') }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Stat 3 Value</label>
                        <input type="text" name="blog_sidebar_stat3_value" value="{{ \App\Models\Setting::get('blog_sidebar_stat3_value', '98%') }}">
                    </div>
                    
                    <div class="col-md-6 form-group">
                        <label>Stat 4 Label</label>
                        <input type="text" name="blog_sidebar_stat4_label" value="{{ \App\Models\Setting::get('blog_sidebar_stat4_label', 'Projects delivered') }}">
                    </div>
                    <div class="col-md-6 form-group">
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
                <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
