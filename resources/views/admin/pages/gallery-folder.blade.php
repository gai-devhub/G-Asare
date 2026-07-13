@extends('admin.admin')

@section('title', 'Gallery Folder: ' . $galleryFolder->name)

@push('topbar-add')
<button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('admin.gallery') }}'" style="margin-right: 10px;"><i class="fas fa-arrow-left"></i> Back to Folders</button>
<button type="button" class="btn btn-primary" data-modal-open="add-gallery-modal"><i class="fas fa-upload"></i> Add Image</button>
@endpush

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header" style="display: flex; align-items: center; gap: 15px;">
        <a href="{{ route('admin.gallery') }}" class="btn btn-secondary" style="border-radius: 50%; width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;" title="Back to Folders">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 style="margin-bottom: 5px;"><i class="fas fa-folder-open" style="margin-right: 12px; color: var(--color-primary);"></i>{{ $galleryFolder->name }}</h1>
            <p style="margin: 0;">Category: <span style="color: var(--color-primary);">{{ $galleryFolder->category ?? 'Uncategorized' }}</span> | Images: {{ $items->count() }}</p>
        </div>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-image" style="margin-right: 8px;"></i> Images in Folder</div>
            <div class="chart-actions" style="display: flex; gap: 0.5rem;">
                <button type="button" class="btn btn-primary" data-modal-open="add-gallery-modal"><i class="fas fa-upload"></i> Add Image</button>
            </div>
        </div>
        
        <div class="gallery-grid" data-search-container>
            @forelse($items as $item)
            <div class="gallery-item" data-search-text="{{ $item->title }} {{ $item->description }}">
                <img src="{{ asset($item->image_url) }}" alt="{{ $item->title ?? 'Gallery Image' }}">
                <div class="gallery-overlay">
                    <button type="button" class="action-btn edit-btn" data-modal-open="edit-gallery-modal" data-item-id="{{ $item->id }}" data-item-title="{{ $item->title }}" data-item-description="{{ $item->description }}" data-item-url="{{ $item->image_url }}" data-item-sort="{{ $item->sort_order }}"><i class="fas fa-edit"></i></button>
                    <button type="button" class="action-btn delete-btn" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.gallery.destroy', $item) }}" data-delete-name="this image"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            @empty
            <div class="empty-state-container" style="grid-column: 1 / -1; padding: 4rem 2rem; text-align: center; border: 1px dashed rgba(150, 150, 150, 0.2); border-radius: 12px; background: rgba(0,0,0,0.02);">
                <i class="fas fa-images" style="font-size: 3rem; color: var(--gray); margin-bottom: 1rem; opacity: 0.5;"></i>
                <p class="text-muted" style="font-size: 1.1rem; margin: 0;">No images in this folder yet. Add your first image above.</p>
            </div>
            @endforelse
        </div>
        
        @if(method_exists($items, 'hasPages') && $items->hasPages())
            <div class="pagination-wrapper mt-4">
                {{ $items->links('admin.pagination') }}
            </div>
        @endif
    </div>
</div>

@push('modals')
<div class="modal-overlay" id="add-gallery-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle" style="margin-right: 8px;"></i> Add Image to Folder</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.gallery.store') }}" data-submit="server" enctype="multipart/form-data">
            @csrf
            <!-- Automatically assign to this folder and category -->
            <input type="hidden" name="gallery_folder_id" value="{{ $galleryFolder->id }}">
            <input type="hidden" name="category" value="{{ $galleryFolder->category }}">
            
            <div class="modal-body">
                <div class="form-group">
                    <label for="gallery-url">Select Images <span class="required">*</span></label>
                    <input type="file" name="images[]" id="gallery-url" accept="image/*" multiple required>
                    <small style="color: var(--gray); display: block; margin-top: 5px;">You can select multiple images to upload at once.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload Images</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="edit-gallery-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit" style="margin-right: 8px;"></i> Edit Image</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="" id="edit-gallery-form" data-submit="server" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="gallery_folder_id" value="{{ $galleryFolder->id }}">
            <input type="hidden" name="category" value="{{ $galleryFolder->category }}">
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit-gallery-url">Image File (Leave empty to keep current)</label>
                    <input type="file" name="image" id="edit-gallery-url" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="edit-gallery-title">Title</label>
                    <input type="text" name="title" id="edit-gallery-title">
                </div>
                <div class="form-group">
                    <label for="edit-gallery-description">Description</label>
                    <textarea name="description" id="edit-gallery-description" rows="2"></textarea>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle edit gallery modal
        const editGalleryBtns = document.querySelectorAll('.edit-btn[data-item-id]');
        const editGalleryForm = document.getElementById('edit-gallery-form');
        
        if (editGalleryBtns.length > 0 && editGalleryForm) {
            editGalleryBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-item-id');
                    const title = this.getAttribute('data-item-title');
                    const desc = this.getAttribute('data-item-description');
                    const url = this.getAttribute('data-item-url');
                    
                    document.getElementById('edit-gallery-title').value = title || '';
                    document.getElementById('edit-gallery-description').value = desc || '';
                    document.getElementById('edit-gallery-url').value = ''; // Cannot prefill file input
                    
                    editGalleryForm.action = `/admin/gallery/${id}`;
                });
            });
        }
    });
</script>
@endpush
@endsection
