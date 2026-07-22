@extends('admin.admin')

@section('title', 'My Files')

@push('topbar-add')
<button type="button" class="btn btn-primary" data-modal-open="add-doc-modal"><i class="fas fa-plus"></i> Add Document</button>
@endpush

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <div>
            <h1><i class="fas fa-file-alt" ></i>My Files</h1>
            <p>Manage key documents for download—resume, transcripts, certificates. These display as downloadable file items.</p>
        </div>
        <button type="button" class="icon-btn" data-modal-open="add-doc-modal" title="Add Document">
            <i class="fas fa-plus"></i>
        </button>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-folder-open" ></i> All Documents</div>
        </div>
        
        @if($documents->count() > 0)
        <table class="data-table" data-search-table>
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Owner</th>
                    <th>File</th>
                    <th class="table-action-cell"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                <tr>
                    <td><div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(0,240,255,0.1); display: flex; align-items: center; justify-content: center; color: var(--color-primary);"><i class="{{ $doc->icon ?: 'fas fa-file-alt' }}"></i></div></td>
                    <td>
                        <div class="table-name-cell" style="padding-left: 0;">
                            <span>{{ $doc->title }}</span>
                        </div>
                    </td>
                    <td>{{ Str::limit($doc->description, 50) ?? '—' }}</td>
                    <td>
                        <div class="table-owner-cell">
                            @php $profile = \App\Models\Profile::first(); @endphp
                            <img src="{{ asset($profile->image_url ?? 'images/gilly.jpeg') }}" alt="Owner">
                            <span>me</span>
                        </div>
                    </td>
                    <td>
                        <div class="table-location-cell">
                            <i class="fas fa-folder"></i>
                            <span>{{ Str::limit($doc->file_path, 20) }}</span>
                        </div>
                    </td>
                    <td class="table-action-cell">
                        <div class="kebab-menu-wrapper">
                            <button class="kebab-btn"><i class="fas fa-ellipsis-v"></i></button>
                            <ul class="kebab-dropdown">
                                <li class="has-submenu">
                                    <button type="button"><i class="fas fa-info-circle"></i> File information <i class="fas fa-chevron-right"></i></button>
                                    <ul class="kebab-submenu kebab-submenu-left">
                                        <li><button type="button" onclick="openSidebar('details', { title: '{{ addslashes($doc->title) }}', category: '{{ addslashes($doc->type) }}', type: 'Document', owner: 'me', modified: '{{ $doc->updated_at ? $doc->updated_at->format('M d, Y') : 'Unknown' }}', created: '{{ $doc->created_at ? $doc->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '{{ $doc->file_size }}', description: '{{ addslashes($doc->description ?? 'No description') }}', imageUrl: '', path: 'public/{{ addslashes(str_replace("\\\\", "/", $doc->file_path)) }}' })"><i class="fas fa-list"></i> Details</button></li>
                                        <li><button type="button" onclick="openSidebar('activity', { title: '{{ addslashes($doc->title) }}', category: '{{ addslashes($doc->type) }}', type: 'Document', owner: 'me', modified: '{{ $doc->updated_at ? $doc->updated_at->format('M d, Y') : 'Unknown' }}', created: '{{ $doc->created_at ? $doc->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '{{ $doc->file_size }}', description: '{{ addslashes($doc->description ?? 'No description') }}', imageUrl: '', path: 'public/{{ addslashes(str_replace("\\\\", "/", $doc->file_path)) }}' })"><i class="fas fa-history"></i> Activity</button></li>
                                    </ul>
                                </li>
                                <li class="has-submenu">
                                    <button type="button"><i class="fas fa-share-alt"></i> Share <i class="fas fa-chevron-right"></i></button>
                                    <ul class="kebab-submenu kebab-submenu-left">
                                        <li><button type="button" onclick="if(navigator.share) { navigator.share({title: '{{ addslashes($doc->title) }}', url: '{{ str_starts_with($doc->file_path, 'http') ? $doc->file_path : \Storage::disk('s3')->url($doc->file_path) }}'}); } else { copyToClipboard('{{ str_starts_with($doc->file_path, 'http') ? $doc->file_path : \Storage::disk('s3')->url($doc->file_path) }}'); }"><i class="fas fa-share"></i> Share</button></li>
                                        <li class="has-submenu">
                                            <button type="button"><i class="fas fa-link"></i> Copy link <i class="fas fa-chevron-right"></i></button>
                                            <ul class="kebab-submenu kebab-submenu-left">
                                                <li><button type="button" onclick="copyToClipboard('{{ str_starts_with($doc->file_path, 'http') ? $doc->file_path : \Storage::disk('s3')->url($doc->file_path) }}');"><i class="fas fa-file-alt"></i> Copy file link</button></li>
                                                <li><button type="button" onclick="copyToClipboard('{{ route('admin.my-files') }}');"><i class="fas fa-folder-open"></i> Copy all files link</button></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="divider"></li>
                                <li><a href="{{ str_starts_with($doc->file_path, 'http') ? $doc->file_path : \Storage::disk('s3')->url($doc->file_path) }}" target="_blank"><i class="fas fa-external-link-alt"></i> View</a></li>
                                <li><button type="button" data-modal-open="edit-doc-modal" data-doc-id="{{ $doc->id }}" data-doc-title="{{ $doc->title }}" data-doc-description="{{ $doc->description }}" data-doc-type="{{ $doc->type }}" data-doc-url="{{ $doc->url }}" data-doc-button="{{ $doc->button_text }}"><i class="fas fa-edit"></i> Edit</button></li>
                                <li class="divider"></li>
                                <li><button type="button" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.my-files.destroy', $doc) }}" data-delete-name="{{ $doc->title }}"><i class="fas fa-trash"></i> Delete</button></li>
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
                <i class="fas fa-folder-open"></i>
            </div>
            <h2 class="empty-state-title">No documents yet</h2>
            <p class="empty-state-description">Add your resume, transcripts, or other key documents so they appear on your portfolio.</p>
            <div class="empty-state-actions">
                <button type="button" class="empty-state-btn" data-modal-open="add-doc-modal"><i class="fas fa-plus"></i> Add Document</button>
            </div>
        </div>
        @endif
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
            <h3><i class="fas fa-plus-circle" ></i> Add Document</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.my-files.store') }}" data-submit="server" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="doc-title">Document Title</label>
                    <input type="text" name="title" id="doc-title" class="form-control" placeholder="e.g. Resume / CV" required>
                </div>
                <div class="form-group">
                    <label for="doc-desc">Description</label>
                    <input type="text" name="description" id="doc-desc" class="form-control" placeholder="e.g. Updated professional resume (PDF)">
                </div>
                <div class="form-group">
                    <label for="doc-icon">Icon</label>
                    <select name="icon" id="doc-icon" class="form-control">
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
                    <label for="doc-url">File</label>
                    <input type="file" name="file_path" id="doc-url" class="form-control">
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
            <h3><i class="fas fa-edit" ></i> Edit Document</h3>
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


