@extends('admin.admin')

@section('title', 'Web Content')

@section('content')
<div class="admin-header">
    <div class="admin-header-content">
        <h2>Web Content Manager</h2>
        <p>Manage the public-facing content and media for your website pages.</p>
    </div>
</div>

<div class="admin-content">
    <form method="POST" action="{{ route('admin.web.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Welcome Page Content -->
        <div class="bento-grid" style="margin-bottom: 2rem;">
            <div class="bento-card col-span-12">
                <div class="bento-header">
                    <div class="bento-title"><i class="fas fa-home" style="margin-right: 8px;"></i> Welcome Page Section</div>
                </div>
                <div class="bento-body" style="padding-top: 1rem;">
                    <div class="form-group">
                        <label for="hero-image">Hero Section Image</label>
                        <input type="file" name="hero_image_url" id="hero-image" accept="image/*">
                        @if($webContent->hero_image_url)
                            <small class="text-muted mt-1 d-block">Current image: <a href="{{ asset($webContent->hero_image_url) }}" target="_blank">View</a></small>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        <!-- My Story Section -->
        <div class="bento-grid" style="margin-bottom: 2rem;">
            <div class="bento-card col-span-12">
                <div class="bento-header">
                    <div class="bento-title"><i class="fas fa-book-open" style="margin-right: 8px;"></i> My Story Section</div>
                </div>
                <div class="bento-body" style="padding-top: 1rem;">
                    <div class="form-group">
                        <label for="story-title">Section Title</label>
                        <input type="text" name="story_title" id="story-title" value="{{ old('story_title', $webContent->story_title ?? '') }}" placeholder="e.g. Bridging Software Engineering & Cloud Infrastructure" style="border-radius: 8px;">
                    </div>
                    <div class="form-group">
                        <label for="story-subtitle">Section Subtitle</label>
                        <input type="text" name="story_subtitle" id="story-subtitle" value="{{ old('story_subtitle', $webContent->story_subtitle ?? '') }}" placeholder="e.g. Building a foundation for scalable and secure digital solutions" style="border-radius: 8px;">
                    </div>
                    <div class="form-group">
                        <label for="story-content">Story Content (Paragraphs)</label>
                        <textarea name="story_content" id="story-content" rows="10" placeholder="Write your story here... (Separate paragraphs with double newlines)" style="border-radius: 8px;">{{ old('story_content', $webContent->story_content ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Philosophy Section -->
        <div class="bento-grid" style="margin-bottom: 2rem;">
            <div class="bento-card col-span-12">
                <div class="bento-header">
                    <div class="bento-title"><i class="fas fa-lightbulb" style="margin-right: 8px;"></i> My Philosophy & Values Section</div>
                </div>
                <div class="bento-body" style="padding-top: 1rem;">
                    <div class="form-group">
                        <label for="philosophy-title">Section Title</label>
                        <input type="text" name="philosophy_title" id="philosophy-title" value="{{ old('philosophy_title', $webContent->philosophy_title ?? '') }}" placeholder="e.g. My Philosophy & Values" style="border-radius: 8px;">
                    </div>
                    <div class="form-group">
                        <label for="philosophy-subtitle">Section Subtitle</label>
                        <input type="text" name="philosophy_subtitle" id="philosophy-subtitle" value="{{ old('philosophy_subtitle', $webContent->philosophy_subtitle ?? '') }}" placeholder="e.g. The principles that guide my work and approach to design" style="border-radius: 8px;">
                    </div>
                    <div class="form-group">
                        <label for="philosophy-content">Philosophy Content (Paragraphs)</label>
                        <textarea name="philosophy_content" id="philosophy-content" rows="6" placeholder="Write your philosophy here... (Separate paragraphs with double newlines)" style="border-radius: 8px;">{{ old('philosophy_content', $webContent->philosophy_content ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Web Content</button>
        </div>
    </form>
</div>
@endsection
