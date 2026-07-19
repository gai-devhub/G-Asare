@extends('admin.admin')

@section('title', 'Blog Posts')

@push('topbar-add')
<button type="button" class="btn btn-primary" data-modal-open="add-blog-modal"><i class="fas fa-plus"></i> Add Blog Post</button>
@endpush

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <div>
            <h1><i class="fas fa-blog" ></i>Blog Posts</h1>
            <p>Manage your blog articles and news updates.</p>
        </div>
        <button type="button" class="icon-btn" data-modal-open="add-blog-modal" title="Add Post">
            <i class="fas fa-plus"></i>
        </button>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-list" ></i> Blog Posts</div>
        </div>
        
        @if($posts->count() > 0)
        <table class="data-table" data-search-table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th class="table-action-cell"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                <tr>
                    <td>
                        <div class="table-name-cell">
                            @if($post->image_url)
                                <img src="{{ asset($post->image_url) }}" alt="Post" class="table-image-icon">
                            @else
                                <i class="fas fa-file-alt"></i>
                            @endif
                            <span>{{ $post->title }}</span>
                        </div>
                    </td>
                    <td><span class="activity-badge page-view">{{ $post->category ?? '—' }}</span></td>
                    <td>
                        <div class="table-owner-cell">
                            @php $profile = \App\Models\Profile::first(); @endphp
                            <img src="{{ asset($post->author_image_url ?? $profile->image_url ?? 'images/gilly.jpeg') }}" alt="Author">
                            <span>{{ $post->author_name ?? 'me' }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="table-location-cell">
                            <span class="status {{ $post->isPublished() ? 'published' : 'draft' }}">{{ $post->isPublished() ? 'Published' : 'Draft' }}</span>
                        </div>
                    </td>
                    <td class="table-action-cell">
                        <div class="kebab-menu-wrapper">
                            <button class="kebab-btn"><i class="fas fa-ellipsis-v"></i></button>
                            <ul class="kebab-dropdown">
                                <li class="has-submenu">
                                    <button type="button"><i class="fas fa-info-circle"></i> File information <i class="fas fa-chevron-right"></i></button>
                                    <ul class="kebab-submenu kebab-submenu-left">
                                        <li><button type="button" onclick="openSidebar('details', { title: '{{ addslashes($post->title) }}', category: '{{ addslashes($post->category) }}', type: 'Blog Post', owner: '{{ addslashes($post->author_name ?? 'me') }}', modified: '{{ $post->updated_at ? $post->updated_at->format('M d, Y') : 'Unknown' }}', created: '{{ $post->created_at ? $post->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: '{{ addslashes(Str::limit(strip_tags($post->content), 100)) }}', imageUrl: '{{ $post->image_url ? asset($post->image_url) : '' }}' })"><i class="fas fa-list"></i> Details</button></li>
                                        <li><button type="button" onclick="openSidebar('activity', { title: '{{ addslashes($post->title) }}', category: '{{ addslashes($post->category) }}', type: 'Blog Post', owner: '{{ addslashes($post->author_name ?? 'me') }}', modified: '{{ $post->updated_at ? $post->updated_at->format('M d, Y') : 'Unknown' }}', created: '{{ $post->created_at ? $post->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: '{{ addslashes(Str::limit(strip_tags($post->content), 100)) }}', imageUrl: '{{ $post->image_url ? asset($post->image_url) : '' }}' })"><i class="fas fa-history"></i> Activity</button></li>
                                    </ul>
                                </li>

                                <li class="divider"></li>
                                <li><button type="button" data-modal-open="edit-blog-modal" data-post-id="{{ $post->id }}" data-post-title="{{ $post->title }}" data-post-category="{{ e($post->category ?? '') }}" data-post-excerpt="{{ $post->excerpt }}" data-post-content="{{ $post->content }}" data-post-image="{{ $post->image_url }}" data-post-author="{{ $post->author_name }}" data-post-author-image="{{ $post->author_image_url }}" data-post-signature="{{ e(str_replace(["\r","\n"], ' ', $post->signature ?? '')) }}" data-post-published="{{ $post->published_at ? $post->published_at->format('Y-m-d') : '' }}"><i class="fas fa-edit"></i> Edit</button></li>
                                <li><button type="button" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.blog-posts.destroy', $post) }}" data-delete-name="{{ $post->title }}"><i class="fas fa-trash"></i> Delete</button></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state-container">
            <div class="empty-state-illustration">
                <i class="fas fa-newspaper"></i>
            </div>
            <h2 class="empty-state-title">No blog posts added</h2>
            <p class="empty-state-description">Add your first blog post to share your knowledge and experiences with your visitors.</p>
            <div class="empty-state-actions">
                <button type="button" class="empty-state-btn" data-modal-open="add-blog-modal"><i class="fas fa-plus"></i> Add Post</button>
            </div>
        </div>
        @endif
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
            <h3><i class="fas fa-plus-circle" ></i> Add New Blog Post</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.blog-posts.store') }}" data-submit="server">
            @csrf
            <div class="modal-body">
                <div id="add-blog-step-1">
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
                </div>
                <div id="add-blog-step-2" style="display: none;">
                <div class="form-group">
                    <label for="blog-author">Author Name</label>
                    <input type="text" name="author_name" id="blog-author" placeholder="e.g. Gilbert Asare">
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="blog-author-image">Author Image</label>
                        <input type="file" name="author_image_path" id="blog-author-image" placeholder="author.jpg">
                    </div>
                    <div class="form-group">
                        <label for="blog-image">Featured Image</label>
                        <input type="file" name="image_path" id="blog-image" placeholder="photo.jpg">
                    </div>
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
            </div>
            <div class="modal-footer" id="add-blog-footer-1">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('add-blog-step-1').style.display='none'; document.getElementById('add-blog-step-2').style.display='block'; document.getElementById('add-blog-footer-1').style.display='none'; document.getElementById('add-blog-footer-2').style.display='flex';">Next <i class="fas fa-arrow-right"></i></button>
            </div>
            <div class="modal-footer" id="add-blog-footer-2" style="display: none;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('add-blog-step-2').style.display='none'; document.getElementById('add-blog-step-1').style.display='block'; document.getElementById('add-blog-footer-2').style.display='none'; document.getElementById('add-blog-footer-1').style.display='flex';"><i class="fas fa-arrow-left"></i> Back</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Post</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="edit-blog-modal" data-modal>
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3><i class="fas fa-edit" ></i> Edit Blog Post</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="" id="edit-blog-form" data-submit="server">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div id="edit-blog-step-1">
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
                </div>
                <div id="edit-blog-step-2" style="display: none;">
                <div class="form-group">
                    <label for="edit-blog-author">Author Name</label>
                    <input type="text" name="author_name" id="edit-blog-author" placeholder="e.g. Gilbert Asare">
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit-blog-author-image">Author Image</label>
                        <input type="file" name="author_image_path" id="edit-blog-author-image" placeholder="author.jpg">
                    </div>
                    <div class="form-group">
                        <label for="edit-blog-image">Featured Image</label>
                        <input type="file" name="image_path" id="edit-blog-image" placeholder="photo.jpg">
                    </div>
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
            </div>
            <div class="modal-footer" id="edit-blog-footer-1">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('edit-blog-step-1').style.display='none'; document.getElementById('edit-blog-step-2').style.display='block'; document.getElementById('edit-blog-footer-1').style.display='none'; document.getElementById('edit-blog-footer-2').style.display='flex';">Next <i class="fas fa-arrow-right"></i></button>
            </div>
            <div class="modal-footer" id="edit-blog-footer-2" style="display: none;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('edit-blog-step-2').style.display='none'; document.getElementById('edit-blog-step-1').style.display='block'; document.getElementById('edit-blog-footer-2').style.display='none'; document.getElementById('edit-blog-footer-1').style.display='flex';"><i class="fas fa-arrow-left"></i> Back</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endpush

@endsection




