@extends('admin.admin')

@section('title', 'Journey')

@push('topbar-add')
<button type="button" class="btn btn-primary" data-modal-open="add-journey-modal"><i class="fas fa-plus"></i> Add Milestone</button>
@endpush

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <h1><i class="fas fa-route" style="margin-right: 12px; color: var(--color-primary);"></i>Journey</h1>
        <p>Manage your career journey and timeline milestones.</p>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-history" style="margin-right: 8px;"></i> Journey Timeline</div>
            <div class="chart-actions" style="display: flex; gap: 0.5rem;">
                <button type="button" class="btn btn-primary" data-modal-open="add-journey-modal"><i class="fas fa-plus"></i> Add Milestone</button>
            </div>
        </div>
        
        <div class="journey-timeline" data-search-container>
            @forelse($experiences as $exp)
            <div class="journey-item" data-search-text="{{ $exp->role }} {{ $exp->company }} {{ $exp->description }}">
                <div class="journey-dot"></div>
                <div class="journey-content">
                    <h4>{{ $exp->role }} @ {{ $exp->company }}</h4>
                    <p class="journey-date">{{ $exp->date_from }}@if($exp->date_to) - {{ $exp->date_to }}@endif</p>
                    @if($exp->description)<p>{{ $exp->description }}</p>@endif
                    <div class="journey-actions mt-2" style="display: flex; gap: 0.5rem;">
                        <button type="button" class="action-btn edit-btn" data-modal-open="edit-journey-modal" data-exp-id="{{ $exp->id }}" data-exp-role="{{ $exp->role }}" data-exp-company="{{ $exp->company }}" data-exp-from="{{ $exp->date_from }}" data-exp-to="{{ $exp->date_to }}" data-exp-description="{{ $exp->description }}"><i class="fas fa-edit"></i> Edit</button>
                        <button type="button" class="action-btn delete-btn" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.journey.destroy', $exp) }}" data-delete-name="{{ $exp->title }}"><i class="fas fa-trash"></i> Delete</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="journey-item text-muted">
                <div class="journey-dot"></div>
                <div class="journey-content">
                    <p>No milestones yet. Add your first one above.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
            @if(method_exists($experiences, 'hasPages') && $experiences->hasPages())
                <div class="pagination-wrapper">
                    {{ $experiences->links('admin.pagination') }}
                </div>
            @endif
</div>@push('modals')
<div class="modal-overlay" id="add-journey-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle" style="margin-right: 8px;"></i> Add Journey Milestone</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.journey.store') }}" data-submit="server">
            @csrf
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="journey-role">Role / Title</label>
                        <input type="text" name="role" id="journey-role" placeholder="e.g. Full Stack Developer" required>
                    </div>
                    <div class="form-group">
                        <label for="journey-company">Company</label>
                        <input type="text" name="company" id="journey-company" placeholder="e.g. Acme Inc" required>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="journey-from">Start Date</label>
                        <input type="text" name="date_from" id="journey-from" placeholder="e.g. 2023 or Jan 2023" required>
                    </div>
                    <div class="form-group">
                        <label for="journey-to">End Date</label>
                        <input type="text" name="date_to" id="journey-to" placeholder="e.g. Present or 2024">
                    </div>
                </div>
                <div class="form-group">
                    <label for="journey-description">Description</label>
                    <textarea name="description" id="journey-description" rows="4" placeholder="Describe this milestone..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Milestone</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="edit-journey-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit" style="margin-right: 8px;"></i> Edit Journey Milestone</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="" id="edit-journey-form" data-submit="server">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit-journey-role">Role / Title</label>
                        <input type="text" name="role" id="edit-journey-role" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-journey-company">Company</label>
                        <input type="text" name="company" id="edit-journey-company" required>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit-journey-from">Start Date</label>
                        <input type="text" name="date_from" id="edit-journey-from" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-journey-to">End Date</label>
                        <input type="text" name="date_to" id="edit-journey-to">
                    </div>
                </div>
                <div class="form-group">
                    <label for="edit-journey-description">Description</label>
                    <textarea name="description" id="edit-journey-description" rows="4"></textarea>
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
