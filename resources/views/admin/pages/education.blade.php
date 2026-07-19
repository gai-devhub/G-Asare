@extends('admin.admin')

@section('title', 'Education')

@push('topbar-add')
<button type="button" class="btn btn-primary" data-modal-open="add-edu-modal"><i class="fas fa-plus"></i> Add Education</button>
@endpush

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <div>
            <h1><i class="fas fa-graduation-cap" ></i>Education</h1>
            <p>Manage your educational background and qualifications.</p>
        </div>
        <button type="button" class="icon-btn" data-modal-open="add-edu-modal" title="Add Education">
            <i class="fas fa-plus"></i>
        </button>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-university" ></i> Education History</div>
        </div>
        
        @if($education->count() > 0)
        <table class="data-table" data-search-table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Degree</th>
                    <th>Owner</th>
                    <th>Period</th>
                    <th class="table-action-cell"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($education as $edu)
                <tr>
                    <td>
                        <div class="table-name-cell">
                            <i class="fas fa-university"></i>
                            <span>{{ $edu->institution }}</span>
                        </div>
                    </td>
                    <td><span class="activity-badge page-view">{{ $edu->degree }}</span></td>
                    <td>
                        <div class="table-owner-cell">
                            @php $profile = \App\Models\Profile::first(); @endphp
                            <img src="{{ asset($profile->image_url ?? 'images/gilly.jpeg') }}" alt="Owner">
                            <span>me</span>
                        </div>
                    </td>
                    <td>
                        <div class="table-location-cell">
                            <span class="text-muted">{{ $edu->date_from }}@if($edu->date_to) - {{ $edu->date_to }}@endif</span>
                        </div>
                    </td>
                    <td class="table-action-cell">
                        <div class="kebab-menu-wrapper">
                            <button class="kebab-btn"><i class="fas fa-ellipsis-v"></i></button>
                            <ul class="kebab-dropdown">
                                <li class="has-submenu">
                                    <button type="button"><i class="fas fa-info-circle"></i> File information <i class="fas fa-chevron-right"></i></button>
                                    <ul class="kebab-submenu kebab-submenu-left">
                                        <li><button type="button" onclick="openSidebar('details', { title: '{{ addslashes($edu->degree) }}', category: '{{ addslashes($edu->institution) }}', type: 'Education', owner: 'me', modified: '{{ $edu->updated_at ? $edu->updated_at->format('M d, Y') : 'Unknown' }}', created: '{{ $edu->created_at ? $edu->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: '{{ addslashes(str_replace(["\r","\n"], ' ', $edu->description ?? '')) }}', imageUrl: '' })"><i class="fas fa-list"></i> Details</button></li>
                                        <li><button type="button" onclick="openSidebar('activity', { title: '{{ addslashes($edu->degree) }}', category: '{{ addslashes($edu->institution) }}', type: 'Education', owner: 'me', modified: '{{ $edu->updated_at ? $edu->updated_at->format('M d, Y') : 'Unknown' }}', created: '{{ $edu->created_at ? $edu->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: '{{ addslashes(str_replace(["\r","\n"], ' ', $edu->description ?? '')) }}', imageUrl: '' })"><i class="fas fa-history"></i> Activity</button></li>
                                    </ul>
                                </li>
                                <li class="has-submenu">
                                    <button type="button"><i class="fas fa-share-alt"></i> Share <i class="fas fa-chevron-right"></i></button>
                                    <ul class="kebab-submenu kebab-submenu-left">
                                        <li><button type="button" onclick="showToast('Share dialog opened', 'success')"><i class="fas fa-user-plus"></i> Share</button></li>
                                        <li class="has-submenu">
                                            <button type="button"><i class="fas fa-link"></i> Copy link <i class="fas fa-chevron-right"></i></button>
                                            <ul class="kebab-submenu kebab-submenu-left">
                                                <li><button type="button" onclick="copyToClipboard('{{ route('admin.education') }}')"><i class="fas fa-external-link-alt"></i> Copy Link</button></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="divider"></li>
                                <li><button type="button" data-modal-open="edit-edu-modal" data-edu-id="{{ $edu->id }}" data-edu-degree="{{ $edu->degree }}" data-edu-institution="{{ $edu->institution }}" data-edu-from="{{ $edu->date_from }}" data-edu-to="{{ $edu->date_to }}" data-edu-description="{{ $edu->description }}"><i class="fas fa-edit"></i> Edit</button></li>
                                <li class="divider"></li>
                                <li><button type="button" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.education.destroy', $edu) }}" data-delete-name="{{ $edu->institution }}"><i class="fas fa-trash"></i> Delete</button></li>
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
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h2 class="empty-state-title">No education added</h2>
            <p class="empty-state-description">Add your educational background to show visitors your qualifications.</p>
            <div class="empty-state-actions">
                <button type="button" class="empty-state-btn" data-modal-open="add-edu-modal"><i class="fas fa-plus"></i> Add Education</button>
            </div>
        </div>
        @endif
            @if(method_exists($education, 'hasPages') && $education->hasPages())
                <div class="pagination-wrapper">
                    {{ $education->links('admin.pagination') }}
                </div>
            @endif

            

            
    </div>
</div>

@push('modals')
<div class="modal-overlay" id="add-edu-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle" ></i> Add Education</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.education.store') }}" data-submit="server">
            @csrf
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edu-institution">Institution</label>
                        <input type="text" name="institution" id="edu-institution" placeholder="University or School name" required>
                    </div>
                    <div class="form-group">
                        <label for="edu-degree">Degree/Certificate</label>
                        <input type="text" name="degree" id="edu-degree" placeholder="e.g. B.Sc Computer Science" required>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edu-start">Start Date</label>
                        <input type="text" name="date_from" id="edu-start" placeholder="e.g. 2018 or Sept 2018" required>
                    </div>
                    <div class="form-group">
                        <label for="edu-end">End Date</label>
                        <input type="text" name="date_to" id="edu-end" placeholder="e.g. 2022 or Present">
                    </div>
                </div>
                <div class="form-group">
                    <label for="edu-description">Description</label>
                    <textarea name="description" id="edu-description" rows="3" placeholder="Key achievements, courses, etc."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Education</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="edit-edu-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit" ></i> Edit Education</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="" id="edit-edu-form" data-submit="server">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit-edu-institution">Institution</label>
                        <input type="text" name="institution" id="edit-edu-institution" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-edu-degree">Degree/Certificate</label>
                        <input type="text" name="degree" id="edit-edu-degree" required>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit-edu-start">Start Date</label>
                        <input type="text" name="date_from" id="edit-edu-start" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-edu-end">End Date</label>
                        <input type="text" name="date_to" id="edit-edu-end">
                    </div>
                </div>
                <div class="form-group">
                    <label for="edit-edu-description">Description</label>
                    <textarea name="description" id="edit-edu-description" rows="3"></textarea>
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


