@extends('admin.admin')

@section('title', 'Education')

@push('topbar-add')
<button type="button" class="btn btn-primary" data-modal-open="add-edu-modal"><i class="fas fa-plus"></i> Add Education</button>
@endpush

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <h1><i class="fas fa-graduation-cap" style="margin-right: 12px; color: var(--color-primary);"></i>Education</h1>
        <p>Manage your educational background and qualifications.</p>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-university" style="margin-right: 8px;"></i> Education History</div>
            <div class="chart-actions" style="display: flex; gap: 0.5rem;">
                <button type="button" class="btn btn-primary" data-modal-open="add-edu-modal"><i class="fas fa-plus"></i> Add Education</button>
            </div>
        </div>
        
        <table class="data-table" data-search-table>
            <thead>
                <tr>
                    <th style="width: 35%;">Institution</th>
                    <th style="width: 30%;">Degree</th>
                    <th style="width: 15%;">Period</th>
                    <th style="width: 20%; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($education as $edu)
                <tr>
                    <td><strong>{{ $edu->institution }}</strong></td>
                    <td><span class="activity-badge page-view">{{ $edu->degree }}</span></td>
                    <td class="text-muted">{{ $edu->date_from }}@if($edu->date_to) - {{ $edu->date_to }}@endif</td>
                    <td style="text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                            <button type="button" class="action-btn edit-btn" data-modal-open="edit-edu-modal" data-edu-id="{{ $edu->id }}" data-edu-degree="{{ $edu->degree }}" data-edu-institution="{{ $edu->institution }}" data-edu-from="{{ $edu->date_from }}" data-edu-to="{{ $edu->date_to }}" data-edu-description="{{ $edu->description }}"><i class="fas fa-edit"></i> Edit</button>
                            <button type="button" class="action-btn delete-btn" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.education.destroy', $edu) }}" data-delete-name="{{ $edu->institution }}"><i class="fas fa-trash"></i> Delete</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">No education entries yet. Add your first one above.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
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
            <h3><i class="fas fa-plus-circle" style="margin-right: 8px;"></i> Add Education</h3>
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
            <h3><i class="fas fa-edit" style="margin-right: 8px;"></i> Edit Education</h3>
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
