@extends('admin.admin')

@section('title', 'Blog Posts')

@push('topbar-add')
<button type="button" class="btn btn-primary" data-modal-open="add-blog-modal"><i class="fas fa-plus"></i> Add Blog Post</button>
@endpush

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <h1><i class="fas fa-blog" style="margin-right: 12px; color: var(--color-primary);"></i>Blog Posts</h1>
        <p>Manage your blog articles and news updates.</p>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-list" style="margin-right: 8px;"></i> Blog Posts</div>
            <div class="chart-actions" style="display: flex; gap: 0.5rem;">
                <button type="button" class="btn btn-primary" data-modal-open="add-blog-modal"><i class="fas fa-plus"></i> Add Post</button>
            </div>
        </div>
        
        <table class="data-table" data-search-table>
            <thead>
                <tr>
                    <th style="width: 35%;">Title</th>
                    <th style="width: 20%;">Category</th>
                    <th style="width: 15%;">Status</th>
                    <th style="width: 10%;">Published</th>
                    <th style="width: 20%; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td><strong>{{ $post->title }}</strong></td>
                    <td><span class="activity-badge page-view">{{ $post->category ?? '—' }}</span></td>
                    <td><span class="status {{ $post->isPublished() ? 'published' : 'draft' }}">{{ $post->isPublished() ? 'Published' : 'Draft' }}</span></td>
                    <td class="text-muted">{{ $post->published_at ? $post->published_at->format('M j, Y') : '—' }}</td>
                    <td style="text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                            <button type="button" class="action-btn edit-btn" data-modal-open="edit-blog-modal" data-post-id="{{ $post->id }}" data-post-title="{{ $post->title }}" data-post-category="{{ e($post->category ?? '') }}" data-post-excerpt="{{ $post->excerpt }}" data-post-content="{{ $post->content }}" data-post-image="{{ $post->image_url }}" data-post-author="{{ $post->author_name }}" data-post-author-image="{{ $post->author_image_url }}" data-post-signature="{{ e(str_replace(["\r","\n"], ' ', $post->signature ?? '')) }}" data-post-published="{{ $post->published_at ? $post->published_at->format('Y-m-d') : '' }}"><i class="fas fa-edit"></i> Edit</button>
                            <button type="button" class="action-btn delete-btn" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.blog-posts.destroy', $post) }}" data-delete-name="{{ $post->title }}"><i class="fas fa-trash"></i> Delete</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No blog posts yet. Add your first one above.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
            @if(method_exists($posts, 'hasPages') && $posts->hasPages())
                <div class="pagination-wrapper">
                    {{ $posts->links('admin.pagination') }}
                </div>
            @endif

            

            
    </div>
</div>

@push('modals')
<div class="modal-overlay" id="add-blog-modal" data-modal>
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle" style="margin-right: 8px;"></i> Add New Blog Post</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.blog-posts.store') }}" data-submit="server">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="blog-title">Topic</label>
                    <input type="text" name="title" id="blog-title" placeholder="e.g. From Junior to Senior Developer" required>
                </div>
                <div class="form-group">
                    <label for="blog-category">Category</label>
                    <input type="text" name="category" id="blog-category" placeholder="e.g. Development, Design, Tutorial">
                </div>
                <div class="form-group">
                    <label for="blog-excerpt">Short Description</label>
                    <textarea name="excerpt" id="blog-excerpt" placeholder="Brief summary shown on the blog card" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="blog-published">Date Published</label>
                    <input type="date" name="published_at" id="blog-published" placeholder="Leave empty for draft">
                </div>
                <div class="form-group">
                    <label for="blog-author">Author Name</label>
                    <input type="text" name="author_name" id="blog-author" placeholder="e.g. Gilbert Asare">
                </div>
                <div class="form-group">
                    <label for="blog-author-image">Author Image</label>
                    <input type="file" name="author_image_path" id="blog-author-image" placeholder="author.jpg">
                </div>
                <div class="form-group">
                    <label for="blog-image">Featured Image</label>
                    <input type="file" name="image_path" id="blog-image" placeholder="photo.jpg">
                </div>
                <div class="form-group">
                    <label for="blog-content">Long Description</label>
                    <textarea name="content" id="blog-content" rows="12" placeholder="Write your full blog post here. Use HTML for formatting (e.g. &lt;h3&gt;, &lt;p&gt;, &lt;code&gt;)"></textarea>
                </div>
                <div class="form-group">
                    <label for="blog-signature">Signature</label>
                    <textarea name="signature" id="blog-signature" placeholder="e.g. — Gilbert Asare, Web Developer" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Post</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="edit-blog-modal" data-modal>
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3><i class="fas fa-edit" style="margin-right: 8px;"></i> Edit Blog Post</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="" id="edit-blog-form" data-submit="server">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit-blog-title">Topic</label>
                    <input type="text" name="title" id="edit-blog-title" required>
                </div>
                <div class="form-group">
                    <label for="edit-blog-category">Category</label>
                    <input type="text" name="category" id="edit-blog-category" placeholder="e.g. Development, Design, Tutorial">
                </div>
                <div class="form-group">
                    <label for="edit-blog-excerpt">Short Description</label>
                    <textarea name="excerpt" id="edit-blog-excerpt" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="edit-blog-published">Date Published</label>
                    <input type="date" name="published_at" id="edit-blog-published">
                </div>
                <div class="form-group">
                    <label for="edit-blog-author">Author Name</label>
                    <input type="text" name="author_name" id="edit-blog-author" placeholder="e.g. Gilbert Asare">
                </div>
                <div class="form-group">
                    <label for="edit-blog-author-image">Author Image</label>
                    <input type="file" name="author_image_path" id="edit-blog-author-image" placeholder="author.jpg">
                </div>
                <div class="form-group">
                    <label for="edit-blog-image">Featured Image</label>
                    <input type="file" name="image_path" id="edit-blog-image" placeholder="photo.jpg">
                </div>
                <div class="form-group">
                    <label for="edit-blog-content">Long Description</label>
                    <textarea name="content" id="edit-blog-content" rows="12"></textarea>
                </div>
                <div class="form-group">
                    <label for="edit-blog-signature">Signature</label>
                    <textarea name="signature" id="edit-blog-signature" rows="2" placeholder="e.g. — Gilbert Asare, Web Developer"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endpush

@endsection
