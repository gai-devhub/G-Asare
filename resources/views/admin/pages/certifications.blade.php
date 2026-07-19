@extends('admin.admin')

@section('title', 'Certifications')

@push('topbar-add')
<button type="button" class="btn btn-primary" data-modal-open="add-cert-modal"><i class="fas fa-plus"></i> Add Certification</button>
@endpush

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <div>
            <h1><i class="fas fa-certificate" ></i>Certifications</h1>
            <p>Manage professional certifications and credentials. These display as cards with icon, issuer, date, and View Credential link.</p>
        </div>
        <button type="button" class="icon-btn" data-modal-open="add-cert-modal" title="Add Certification">
            <i class="fas fa-plus"></i>
        </button>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-award" ></i> All Certifications</div>
        </div>
        
        @if($certifications->count() > 0)
        <table class="data-table" data-search-table>
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Name</th>
                    <th>Issuer</th>
                    <th>Owner</th>
                    <th>Date</th>
                    <th class="table-action-cell"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($certifications as $cert)
                <tr>
                    <td><div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(0,240,255,0.1); display: flex; align-items: center; justify-content: center; color: var(--color-primary);"><i class="{{ trim($cert->icon) != '' && $cert->icon != 'null' ? $cert->icon : 'fas fa-certificate' }}"></i></div></td>
                    <td>
                        <div class="table-name-cell" style="padding-left: 0;">
                            <span>{{ $cert->name }}</span>
                        </div>
                    </td>
                    <td class="text-muted">{{ $cert->issuer }}</td>
                    <td>
                        <div class="table-owner-cell">
                            @php $profile = \App\Models\Profile::first(); @endphp
                            <img src="{{ asset($profile->image_url ?? 'images/gilly.jpeg') }}" alt="Owner">
                            <span>me</span>
                        </div>
                    </td>
                    <td>
                        <div class="table-location-cell">
                            <span class="activity-badge page-view">{{ $cert->year ?? '—' }}</span>
                        </div>
                    </td>
                    <td class="table-action-cell">
                        <div class="kebab-menu-wrapper">
                            <button class="kebab-btn"><i class="fas fa-ellipsis-v"></i></button>
                            <ul class="kebab-dropdown">
                                <li class="has-submenu">
                                    <button type="button"><i class="fas fa-info-circle"></i> File information <i class="fas fa-chevron-right"></i></button>
                                    <ul class="kebab-submenu kebab-submenu-left">
                                        <li><button type="button" onclick="openSidebar('details', { title: '{{ addslashes($cert->name) }}', category: '{{ addslashes($cert->issuer) }}', type: 'Certification', owner: 'me', modified: '{{ $cert->updated_at ? $cert->updated_at->format('M d, Y') : 'Unknown' }}', created: '{{ $cert->created_at ? $cert->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: 'No description', imageUrl: '' })"><i class="fas fa-list"></i> Details</button></li>
                                        <li><button type="button" onclick="openSidebar('activity', { title: '{{ addslashes($cert->name) }}', category: '{{ addslashes($cert->issuer) }}', type: 'Certification', owner: 'me', modified: '{{ $cert->updated_at ? $cert->updated_at->format('M d, Y') : 'Unknown' }}', created: '{{ $cert->created_at ? $cert->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: 'No description', imageUrl: '' })"><i class="fas fa-history"></i> Activity</button></li>
                                    </ul>
                                </li>

                                <li class="divider"></li>
                                <li><button type="button" data-modal-open="edit-cert-modal" data-cert-id="{{ $cert->id }}" data-cert-name="{{ $cert->name }}" data-cert-issuer="{{ $cert->issuer }}" data-cert-year="{{ $cert->year }}" data-cert-url="{{ $cert->credential_url }}" data-cert-icon="{{ $cert->icon }}"><i class="fas fa-edit"></i> Edit</button></li>
                                <li class="divider"></li>
                                <li><button type="button" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.certifications.destroy', $cert) }}" data-delete-name="{{ $cert->title }}"><i class="fas fa-trash"></i> Delete</button></li>
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
                <i class="fas fa-award"></i>
            </div>
            <h2 class="empty-state-title">No certifications yet</h2>
            <p class="empty-state-description">Add your professional certifications and credentials so they appear on your profile.</p>
            <div class="empty-state-actions">
                <button type="button" class="empty-state-btn" data-modal-open="add-cert-modal"><i class="fas fa-plus"></i> Add Certification</button>
            </div>
        </div>
        @endif
            @if(method_exists($certifications, 'hasPages') && $certifications->hasPages())
                <div class="pagination-wrapper">
                    {{ $certifications->links('admin.pagination') }}
                </div>
            @endif

            

            
    </div>
</div>

@push('modals')
<div class="modal-overlay" id="add-cert-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle" ></i> Add Certification</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.certifications.store') }}" data-submit="server">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="cert-name">Certification Name</label>
                    <input type="text" name="name" id="cert-name" placeholder="e.g. AWS Certified Developer" required>
                </div>
                <div class="form-group">
                    <label for="cert-issuer">Issuing Organization</label>
                    <input type="text" name="issuer" id="cert-issuer" placeholder="e.g. Amazon Web Services" required>
                </div>
                <div class="form-group">
                    <label for="cert-date">Year / Issue Date</label>
                    <input type="text" name="year" id="cert-date" placeholder="e.g. 2024">
                </div>
                <div class="form-group">
                    <label for="cert-url">Credential URL</label>
                    <input type="url" name="credential_url" id="cert-url" placeholder="Verification link (View Credential)">
                </div>
                <div class="form-group">
                    <label for="cert-icon">Icon</label>
                    <select name="icon" id="cert-icon">
                        <option value="fas fa-code">Code</option>
                        <option value="fab fa-aws">AWS</option>
                        <option value="fab fa-azure">Azure</option>
                        <option value="fab fa-google">Google</option>
                        <option value="fab fa-microsoft">Microsoft</option>
                        <option value="fab fa-windows">Windows</option>
                        <option value="fab fa-linux">Linux</option>
                        <option value="fab fa-apple">Apple</option>
                        <option value="fab fa-android">Android</option>
                        <option value="fab fa-react">React</option>
                        <option value="fab fa-laravel">Laravel</option>
                        <option value="fas fa-database">Database</option>
                        <option value="fas fa-shield-alt">Security</option>
                        <option value="fas fa-certificate">Certificate</option>
                        <option value="fas fa-cloud-arrow-up">Cloud</option>
                        <option value="fab fa-cloudflare">Cloudflare</option>
                        <option value="fab fa-codepen">Codepen</option>
                        <option value="fab fa-docker">Docker</option>
                        <option value="fab fa-python">Python</option>
                        <option value="fab fa-java">Java</option>
                        <option value="fab fa-javascript">JavaScript</option>
                        <option value="fab fa-php">PHP</option>
                        <option value="fab fa-node-js">Node.js</option>
                        <option value="fab fa-react">React</option>
                        <option value="fab fa-angular">Angular</option>
                        <option value="fab fa-vuejs">Vue.js</option>
                        <option value="fab fa-svelte">Svelte</option>
                        <option value="fab fa-nextjs">Next.js</option>
                        <option value="fab fa-remix">Remix</option>
                        <option value="fab fa-astro">Astro</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Certification</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="edit-cert-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit" ></i> Edit Certification</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="" id="edit-cert-form" data-submit="server">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit-cert-name">Certification Name</label>
                    <input type="text" name="name" id="edit-cert-name" required>
                </div>
                <div class="form-group">
                    <label for="edit-cert-issuer">Issuing Organization</label>
                    <input type="text" name="issuer" id="edit-cert-issuer" required>
                </div>
                <div class="form-group">
                    <label for="edit-cert-date">Year / Issue Date</label>
                    <input type="text" name="year" id="edit-cert-date">
                </div>
                <div class="form-group">
                    <label for="edit-cert-url">Credential URL</label>
                    <input type="url" name="credential_url" id="edit-cert-url">
                </div>
                <div class="form-group">
                    <label for="edit-cert-icon">Icon</label>
                    <select name="icon" id="edit-cert-icon">
                        <option value="fas fa-code">Code</option>
                        <option value="fab fa-aws">AWS</option>
                        <option value="fab fa-google">Google</option>
                        <option value="fab fa-react">React</option>
                        <option value="fab fa-laravel">Laravel</option>
                        <option value="fas fa-database">Database</option>
                        <option value="fas fa-shield-alt">Security</option>
                        <option value="fas fa-certificate">Certificate</option>
                    </select>
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




