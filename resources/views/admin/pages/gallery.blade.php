@extends('admin.admin')

@section('title', 'Gallery Folders')

@push('topbar-add')
<button type="button" class="btn btn-primary" data-modal-open="add-folder-modal"><i class="fas fa-folder-plus"></i> Add Folder</button>
@endpush

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <h1><i class="fas fa-folder-open" style="margin-right: 12px; color: var(--color-primary);"></i>Gallery Folders</h1>
        <p>Manage your gallery folders and the images inside them.</p>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-folder" style="margin-right: 8px;"></i> Folders ({{ $folders->count() }})</div>
            <div class="chart-actions" style="display: flex; gap: 0.5rem;">
                <button type="button" class="btn btn-primary" data-modal-open="add-folder-modal"><i class="fas fa-folder-plus"></i> Add Folder</button>
            </div>
        </div>
        
        <div class="gallery-grid" data-search-container>
            @forelse($folders as $folder)
            <div class="gallery-folder-item" data-search-text="{{ $folder->name }} {{ $folder->category }}" style="cursor: pointer;" onclick="window.location.href='{{ route('admin.gallery-folders.show', $folder) }}'">
                <div class="folder-thumbnail">
                    @if($folder->cover_image_url)
                        <img src="{{ asset($folder->cover_image_url) }}" alt="{{ $folder->name }}">
                    @else
                        <div style="height: 100%; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                            <i class="fas fa-folder" style="font-size: 3.5rem; color: var(--color-primary); margin-bottom: 8px;"></i>
                            <span style="color: var(--text); font-size: 0.85rem;">{{ $folder->items()->count() }} items</span>
                        </div>
                    @endif
                    <div class="gallery-overlay" onclick="event.stopPropagation();">
                        <button type="button" class="action-btn" onclick="window.location.href='{{ route('admin.gallery-folders.show', $folder) }}'" style="background: var(--color-primary); color: white;"><i class="fas fa-external-link-alt"></i></button>
                        <button type="button" class="action-btn" data-modal-open="edit-folder-modal-{{ $folder->id }}" style="background: #f59e0b; color: white;"><i class="fas fa-edit"></i></button>
                        <button type="button" class="action-btn delete-btn" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.gallery-folders.destroy', $folder) }}" data-delete-name="this folder and all its images"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <div class="folder-label">
                    <h3>{{ $folder->name }}</h3>
                    <span>{{ $folder->category ?? 'Uncategorized' }}</span>
                </div>
            </div>
            @empty
            <div class="empty-state-container" style="grid-column: 1 / -1; padding: 4rem 2rem; text-align: center; border: 1px dashed rgba(150, 150, 150, 0.2); border-radius: 12px; background: rgba(0,0,0,0.02);">
                <i class="fas fa-folder-open" style="font-size: 3rem; color: var(--gray); margin-bottom: 1rem; opacity: 0.5;"></i>
                <p class="text-muted" style="font-size: 1.1rem; margin: 0;">No gallery folders yet. Add your first folder above.</p>
            </div>
            @endforelse
        </div>
        
        @if(method_exists($folders, 'hasPages') && $folders->hasPages())
            <div class="pagination-wrapper mt-4">
                {{ $folders->links('admin.pagination') }}
            </div>
        @endif
    </div>
</div>

@push('modals')
<div class="modal-overlay" id="add-folder-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-folder-plus" style="margin-right: 8px;"></i> Create Folder</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.gallery-folders.store') }}" data-submit="server" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="folder-name">Folder Name <span class="required">*</span></label>
                    <input type="text" name="name" id="folder-name" placeholder="e.g. Summer Vacation 2024" required>
                </div>
                <div class="form-group">
                    <label for="folder-category">Category</label>
                    <input type="text" name="category" id="folder-category" list="category-list" placeholder="Select or type a category">
                    <datalist id="category-list">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                        <option value="Project Screenshots">
                        <option value="Personal">
                        <option value="Events">
                        <option value="Work">
                        <option value="Travel">
                    </datalist>
                </div>
                <div class="form-group">
                    <label for="folder-description">Description</label>
                    <textarea name="description" id="folder-description" rows="3" placeholder="Optional short description for this folder"></textarea>
                </div>
                <div class="form-group">
                    <label for="folder-cover">Cover Image (Optional)</label>
                    <input type="file" name="cover_image" id="folder-cover" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create Folder</button>
            </div>
        </form>
    </div>
</div>

{{-- Per-folder edit modals rendered outside the grid to avoid backdrop-filter stacking --}}
@foreach($folders as $folder)
<div class="modal-overlay" id="edit-folder-modal-{{ $folder->id }}" data-modal>
    <div class="modal" style="margin-top: 3rem;">
        <div class="modal-header">
            <h3><i class="fas fa-edit" style="margin-right: 8px;"></i> Edit: {{ $folder->name }}</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.gallery-folders.update', $folder) }}" data-submit="server" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label>Folder Name <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ $folder->name }}" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <input type="text" name="category" value="{{ $folder->category }}" list="cat-list-{{ $folder->id }}" placeholder="Select or type a category">
                    <datalist id="cat-list-{{ $folder->id }}">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                        <option value="Project Screenshots">
                        <option value="Personal">
                        <option value="Events">
                        <option value="Work">
                        <option value="Travel">
                    </datalist>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" placeholder="Optional short description">{{ $folder->description }}</textarea>
                </div>
                <div class="form-group">
                    <label>Cover Image (Leave empty to keep current)</label>
                    @if($folder->cover_image_url)
                    <div style="margin-bottom: 8px;">
                        <p style="font-size: 0.8rem; color: var(--gray); margin: 0 0 6px 0;">Current cover:</p>
                        <img src="{{ asset($folder->cover_image_url) }}" style="width: 100%; max-height: 120px; object-fit: cover; border-radius: 8px;">
                    </div>
                    @endif
                    <input type="file" name="cover_image" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@endpush


@endsection
