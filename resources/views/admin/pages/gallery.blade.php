@extends('admin.admin')

@section('title', 'Gallery Folders')

@push('topbar-add')
<button type="button" class="btn btn-primary" data-modal-open="add-folder-modal"><i class="fas fa-folder-plus"></i> Add Folder</button>
@endpush

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <div>
            <h1><i class="fas fa-folder-open" ></i>Gallery Folders</h1>
            <p>Manage your gallery folders and the images inside them.</p>
        </div>
        <button type="button" class="icon-btn" data-modal-open="add-folder-modal" title="Add Folder">
            <i class="fas fa-plus"></i>
        </button>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-folder" ></i> Folders ({{ $folders->count() }})</div>
        </div>
        
        <div class="folder-grid" data-search-container>
            @forelse($folders as $folder)
            <div class="gallery-folder-item" data-search-text="{{ $folder->name }} {{ $folder->category }}"  onclick="window.location.href='{{ route('admin.gallery-folders.show', $folder) }}'">
                <div class="folder-thumbnail">
                    <div class="mac-folder">
                        <div class="mac-folder-back"></div>
                        @if($folder->cover_image_url)
                            <div class="mac-folder-image">
                                <img src="{{ asset($folder->cover_image_url) }}" alt="{{ $folder->name }}">
                            </div>
                        @else
                            <div class="mac-folder-image empty-folder"></div>
                        @endif
                        <div class="mac-folder-front">
                            <h3 class="folder-title" title="{{ $folder->name }}">{{ $folder->name }}</h3>
                            <span class="folder-subtitle">{{ $folder->items()->count() }} items</span>
                        </div>
                    </div>
                    <div class="folder-actions" onclick="event.stopPropagation();">
                        <button type="button" class="action-btn" onclick="window.location.href='{{ route('admin.gallery-folders.show', $folder) }}'" ><i class="fas fa-external-link-alt"></i></button>
                        <button type="button" class="action-btn" data-modal-open="edit-folder-modal-{{ $folder->id }}" ><i class="fas fa-edit"></i></button>
                        <button type="button" class="action-btn delete-btn" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.gallery-folders.destroy', $folder) }}" data-delete-name="this folder and all its images"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state-container" style="grid-column: 1 / -1;">
                <div class="empty-state-illustration">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h2 class="empty-state-title">No gallery folders yet</h2>
                <p class="empty-state-description">Add your first folder to organize your images.</p>
                <div class="empty-state-actions">
                    <button type="button" class="empty-state-btn" data-modal-open="add-folder-modal"><i class="fas fa-plus"></i> Add Folder</button>
                </div>
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
            <h3><i class="fas fa-folder-plus" ></i> Create Folder</h3>
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
    <div class="modal" >
        <div class="modal-header">
            <h3><i class="fas fa-edit" ></i> Edit: {{ $folder->name }}</h3>
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
                    <div style="margin-bottom: 1rem;">
                        <p style="margin-bottom: 0.5rem; font-size: 0.85rem; color: var(--color-text-muted);">Current cover:</p>
                        <img src="{{ asset($folder->cover_image_url) }}" style="max-width: 100%; max-height: 150px; border-radius: 6px; object-fit: contain; border: 1px solid var(--border-color);">
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


