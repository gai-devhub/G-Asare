@extends('admin.admin')

@section('title', 'Web Content')

@section('content')
<div class="content-section active">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-globe" ></i> Web Content Manager</h1>
            <p>Manage the public-facing content and media for your website pages.</p>
        </div>
        <button type="submit" form="web-content-form" class="btn btn-primary"><i class="fas fa-save"></i> Save Web Content</button>
    </div>

    <form id="web-content-form" method="POST" action="{{ route('admin.web.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Welcome Page Content -->
        <div class="bento-grid" >
            <div class="chart-card col-span-12">
                <div class="chart-header">
                    <div class="chart-title"><i class="fas fa-home" ></i> Welcome Page Section</div>
                </div>
                <div class="bento-body" >
                    <div class="form-group">
                        <label for="hero-image">Hero Section Image</label>
                        <input type="file" class="form-control" name="hero_image_url" id="hero-image" accept="image/*">
                        @if($webContent->hero_image_url)
                            <small class="text-muted mt-1 d-block">Current image: <a href="{{ asset($webContent->hero_image_url) }}" target="_blank">View</a></small>
                        @endif
                    </div>

                </div>
            </div>
        </div><br>

        <!-- My Story & Philosophy Section -->
        <div class="bento-grid" >
            <!-- My Story Section -->
            <div class="chart-card col-span-6">
                <div class="chart-header">
                    <div class="chart-title"><i class="fas fa-book-open" ></i> My Story Section</div>
                </div>
                <div class="bento-body" >
                    <div class="form-group">
                        <label for="story-title">Section Title</label>
                        <input type="text" class="form-control" name="story_title" id="story-title" value="{{ old('story_title', $webContent->story_title ?? '') }}" placeholder="e.g. Bridging Software Engineering & Cloud Infrastructure" >
                    </div>
                    <div class="form-group">
                        <label for="story-subtitle">Section Subtitle</label>
                        <input type="text" class="form-control" name="story_subtitle" id="story-subtitle" value="{{ old('story_subtitle', $webContent->story_subtitle ?? '') }}" placeholder="e.g. Building a foundation for scalable and secure digital solutions" >
                    </div>
                    <div class="form-group mb-0">
                        <label for="story-content">Story Content (Paragraphs)</label>
                        <textarea class="form-control" name="story_content" id="story-content" rows="6" placeholder="Write your story here... (Separate paragraphs with double newlines)" >{{ old('story_content', $webContent->story_content ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Philosophy Section -->
            <div class="chart-card col-span-6">
                <div class="chart-header">
                    <div class="chart-title"><i class="fas fa-lightbulb" ></i> My Philosophy & Values Section</div>
                </div>
                <div class="bento-body" >
                    <div class="form-group">
                        <label for="philosophy-title">Section Title</label>
                        <input type="text" class="form-control" name="philosophy_title" id="philosophy-title" value="{{ old('philosophy_title', $webContent->philosophy_title ?? '') }}" placeholder="e.g. My Philosophy & Values" >
                    </div>
                    <div class="form-group">
                        <label for="philosophy-subtitle">Section Subtitle</label>
                        <input type="text" class="form-control" name="philosophy_subtitle" id="philosophy-subtitle" value="{{ old('philosophy_subtitle', $webContent->philosophy_subtitle ?? '') }}" placeholder="e.g. The principles that guide my work and approach to design" >
                    </div>
                    <div class="form-group mb-0">
                        <label for="philosophy-content">Philosophy Content (Paragraphs)</label>
                        <textarea class="form-control" name="philosophy_content" id="philosophy-content" rows="6" placeholder="Write your philosophy here... (Separate paragraphs with double newlines)" >{{ old('philosophy_content', $webContent->philosophy_content ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection


