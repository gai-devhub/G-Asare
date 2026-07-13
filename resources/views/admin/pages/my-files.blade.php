@extends('admin.admin')

@section('title', 'My Files')

@push('topbar-add')
<button type="button" class="btn btn-primary" data-modal-open="add-doc-modal"><i class="fas fa-plus"></i> Add Document</button>
@endpush

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <h1><i class="fas fa-file-alt" style="margin-right: 12px; color: var(--color-primary);"></i>My Files</h1>
        <p>Manage key documents for download—resume, transcripts, certificates. These display as downloadable file items.</p>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-folder-open" style="margin-right: 8px;"></i> All Documents</div>
            <div class="chart-actions" style="display: flex; gap: 0.5rem;">
                <button type="button" class="btn btn-primary" data-modal-open="add-doc-modal"><i class="fas fa-plus"></i> Add Document</button>
            </div>
        </div>
        
        <table class="data-table" data-search-table>
            <thead>
                <tr>
                    <th style="width: 10%;">Icon</th>
                    <th style="width: 25%;">Document</th>
                    <th style="width: 25%;">Description</th>
                    <th style="width: 20%;">File</th>
                    <th style="width: 20%; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $doc)
                <tr>
                    <td><div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(0,240,255,0.1); display: flex; align-items: center; justify-content: center; color: var(--color-primary);"><i class="{{ $doc->icon }}"></i></div></td>
                    <td><strong>{{ $doc->title }}</strong></td>
                    <td class="text-muted">{{ Str::limit($doc->description, 50) ?? '—' }}</td>
                    <td><span class="activity-badge page-view">{{ Str::limit($doc->file_path, 20) }}</span></td>
                    <td style="text-align: right;">
                                <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                <a href="{{ asset($doc->file_path) }}" target="_blank" class="action-btn" style="text-decoration: none; color: #3b82f6; border-color: rgba(59, 130, 246, 0.3); background: rgba(59, 130, 246, 0.05); display: inline-flex; align-items: center; justify-content: center;"><i class="fas fa-external-link-alt"></i> View</a>
                                <button type="button" class="action-btn edit-btn" data-modal-open="edit-doc-modal" data-doc-id="{{ $doc->id }}" data-doc-title="{{ $doc->title }}" data-doc-description="{{ $doc->description }}" data-doc-type="{{ $doc->type }}" data-doc-url="{{ $doc->url }}" data-doc-button="{{ $doc->button_text }}"><i class="fas fa-edit"></i> Edit</button>
                                <button type="button" class="action-btn delete-btn" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.my-files.destroy', $doc) }}" data-delete-name="{{ $doc->title }}"><i class="fas fa-trash"></i> Delete</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">No documents yet. Add your first one above.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
            @if(method_exists($documents, 'hasPages') && $documents->hasPages())
                <div class="pagination-wrapper">
                    {{ $documents->links('admin.pagination') }}
                </div>
            @endif

            

            
    </div>
</div>

@push('modals')
<div class="modal-overlay" id="add-doc-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle" style="margin-right: 8px;"></i> Add Document</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.my-files.store') }}" data-submit="server" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="doc-title">Document Title</label>
                    <input type="text" name="title" id="doc-title" placeholder="e.g. Resume / CV" required>
                </div>
                <div class="form-group">
                    <label for="doc-desc">Description</label>
                    <input type="text" name="description" id="doc-desc" placeholder="e.g. Updated professional resume (PDF)">
                </div>
                <div class="form-group">
                    <label for="doc-icon">Icon</label>
                    <select name="icon" id="doc-icon">
                        <option value="fas fa-file">File</option>
                        <option value="fas fa-file-pdf">PDF</option>
                        <option value="fas fa-file-word">Word</option>
                        <option value="fas fa-file-degree">Degree</option>
                        <option value="fas fa-file-certificate">Certificate</option>
                        <option value="fas fa-file-excel">Excel</option>
                        <option value="fas fa-file-archive">Archive</option>
                        <option value="fas fa-briefcase">Briefcase</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="doc">File</label>
                    <input type="file" name="file_path" id="doc-url" placeholder="file.pdf">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Document</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="edit-doc-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit" style="margin-right: 8px;"></i> Edit Document</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="" id="edit-doc-form" data-submit="server" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit-doc-title">Document Title</label>
                    <input type="text" name="title" id="edit-doc-title" required>
                </div>
                <div class="form-group">
                    <label for="edit-doc-desc">Description</label>
                    <input type="text" name="description" id="edit-doc-desc">
                </div>
                <div class="form-group">
                    <label for="doc-icon">Icon</label>
                    <select name="icon" id="doc-icon">
                        <option value="fas fa-file">File</option>
                        <option value="fas fa-file-pdf">PDF</option>
                        <option value="fas fa-file-word">Word</option>
                        <option value="fas fa-file-degree">Degree</option>
                        <option value="fas fa-file-certificate">Certificate</option>
                        <option value="fas fa-file-excel">Excel</option>
                        <option value="fas fa-file-archive">Archive</option>
                        <option value="fas fa-briefcase">Briefcase</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit-doc">File</label>
                    <input type="file" name="file_path" id="edit-doc-file-path">
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
