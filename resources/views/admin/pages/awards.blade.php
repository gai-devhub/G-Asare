@extends('admin.admin')

@section('title', 'Awards')

@push('topbar-add')
<button type="button" class="btn btn-primary" data-modal-open="add-award-modal"><i class="fas fa-plus"></i> Add Award</button>
@endpush

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <div>
            <h1><i class="fas fa-trophy" ></i>Awards</h1>
            <p>Manage awards and recognition. These display as cards with icon, organization, and year.</p>
        </div>
        <button type="button" class="icon-btn" data-modal-open="add-award-modal" title="Add Award">
            <i class="fas fa-plus"></i>
        </button>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-award" ></i> All Awards</div>
        </div>
        
        @if($awards->count() > 0)
        <table class="data-table" data-search-table>
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Name</th>
                    <th>Organization</th>
                    <th>Owner</th>
                    <th>Date</th>
                    <th class="table-action-cell"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($awards as $award)
                <tr>
                    <td><div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(0,240,255,0.1); display: flex; align-items: center; justify-content: center; color: var(--color-primary);"><i class="{{ trim($award->icon) != '' && $award->icon != 'null' ? $award->icon : 'fas fa-trophy' }}"></i></div></td>
                    <td>
                        <div class="table-name-cell" style="padding-left: 0;">
                            <span>{{ $award->title }}</span>
                        </div>
                    </td>
                    <td class="text-muted">{{ $award->issuer ?? '—' }}</td>
                    <td>
                        <div class="table-owner-cell">
                            @php $profile = \App\Models\Profile::first(); @endphp
                            <img src="{{ asset($profile->image_url ?? 'images/gilly.jpeg') }}" alt="Owner">
                            <span>me</span>
                        </div>
                    </td>
                    <td>
                        <div class="table-location-cell">
                            <span class="activity-badge page-view">{{ $award->year ?? '—' }}</span>
                        </div>
                    </td>
                    <td class="table-action-cell">
                        <div class="kebab-menu-wrapper">
                            <button class="kebab-btn"><i class="fas fa-ellipsis-v"></i></button>
                            <ul class="kebab-dropdown">
                                <li class="has-submenu">
                                    <button type="button"><i class="fas fa-info-circle"></i> File information <i class="fas fa-chevron-right"></i></button>
                                    <ul class="kebab-submenu kebab-submenu-left">
                                        <li><button type="button" onclick="openSidebar('details', { title: '{{ addslashes($award->title) }}', category: '{{ addslashes($award->issuer) }}', type: 'Award', owner: 'me', modified: '{{ $award->updated_at ? $award->updated_at->format('M d, Y') : 'Unknown' }}', created: '{{ $award->created_at ? $award->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: '{{ addslashes(str_replace(["\r","\n"], ' ', $award->description ?? '')) }}', imageUrl: '' })"><i class="fas fa-list"></i> Details</button></li>
                                        <li><button type="button" onclick="openSidebar('activity', { title: '{{ addslashes($award->title) }}', category: '{{ addslashes($award->issuer) }}', type: 'Award', owner: 'me', modified: '{{ $award->updated_at ? $award->updated_at->format('M d, Y') : 'Unknown' }}', created: '{{ $award->created_at ? $award->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: '{{ addslashes(str_replace(["\r","\n"], ' ', $award->description ?? '')) }}', imageUrl: '' })"><i class="fas fa-history"></i> Activity</button></li>
                                    </ul>
                                </li>

                                <li class="divider"></li>
                                <li><button type="button" data-modal-open="edit-award-modal" data-award-id="{{ $award->id }}" data-award-title="{{ $award->title }}" data-award-issuer="{{ $award->issuer }}" data-award-year="{{ $award->year }}" data-award-icon="{{ $award->icon }}" data-award-description="{{ $award->description }}"><i class="fas fa-edit"></i> Edit</button></li>
                                <li class="divider"></li>
                                <li><button type="button" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.awards.destroy', $award) }}" data-delete-name="{{ $award->title }}"><i class="fas fa-trash"></i> Delete</button></li>
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
                <i class="fas fa-trophy"></i>
            </div>
            <h2 class="empty-state-title">No awards yet</h2>
            <p class="empty-state-description">Add your awards and recognition so they appear on your profile.</p>
            <div class="empty-state-actions">
                <button type="button" class="empty-state-btn" data-modal-open="add-award-modal"><i class="fas fa-plus"></i> Add Award</button>
            </div>
        </div>
        @endif
            @if(method_exists($awards, 'hasPages') && $awards->hasPages())
                <div class="pagination-wrapper">
                    {{ $awards->links('admin.pagination') }}
                </div>
            @endif

            

            
    </div>
</div>

@push('modals')
<div class="modal-overlay" id="add-award-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle" ></i> Add Award</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.awards.store') }}" data-submit="server" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="award-title">Award Title</label>
                    <input type="text" name="title" id="award-title" class="form-control" placeholder="e.g. Outstanding Developer Award" required>
                </div>
                <div class="form-group">
                    <label for="award-issuer">Organization</label>
                    <input type="text" name="issuer" id="award-issuer" class="form-control" placeholder="e.g. Tech Innovation Summit">
                </div>
                <div class="form-group">
                    <label for="award-date">Year / Date</label>
                    <input type="text" name="date" id="award-date" class="form-control" placeholder="e.g. 2024">
                </div>
                <div class="form-group">
                    <label for="award-description">Description</label>
                    <textarea name="description" id="award-description" rows="3" class="form-control" placeholder="Optional description"></textarea>
                </div>
                <div class="form-group">
                    <label for="award-icon">Icon</label>
                    <select name="icon" id="award-icon" class="form-control">
                        <option value="fas fa-trophy">Trophy</option>
                        <option value="fas fa-medal">Medal</option>
                        <option value="fas fa-star">Star</option>
                        <option value="fas fa-certificate">Certificate</option>
                        <option value="fas fa-id-badge">ID Badge</option>
                        <option value="fas fa-award">Award</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="award-image">Image File</label>
                    <input type="file" name="image_url" id="award-image" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Award</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="edit-award-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit" ></i> Edit Award</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="" id="edit-award-form" data-submit="server">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit-award-title">Award Title</label>
                    <input type="text" name="title" id="edit-award-title" required>
                </div>
                <div class="form-group">
                    <label for="edit-award-issuer">Organization</label>
                    <input type="text" name="issuer" id="edit-award-issuer">
                </div>
                <div class="form-group">
                    <label for="edit-award-date">Year / Date</label>
                    <input type="text" name="date" id="edit-award-date">
                </div>
                <div class="form-group">
                    <label for="edit-award-description">Description</label>
                    <textarea name="description" id="edit-award-description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="award-icon">Icon</label>
                    <select name="icon" id="award-icon">
                        <option value="fas fa-trophy">Trophy</option>
                        <option value="fas fa-medal">Medal</option>
                        <option value="fas fa-star">Star</option>
                        <option value="fas fa-certificate">Certificate</option>
                        <option value="fas fa-badge">Badge</option>
                        <option value="fas fa-award">Award</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit-award-image">Image URL</label>
                    <input type="text" name="image_url" id="edit-award-image">
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




