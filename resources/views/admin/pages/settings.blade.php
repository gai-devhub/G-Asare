@extends('admin.admin')

@section('title', 'Settings')

@section('content')
<div class="content-section active">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-cogs" ></i>Settings</h1>
            <p>Configure your portfolio and admin preferences.</p>
        </div>
        <button type="submit" form="settings-form" class="btn btn-primary"><i class="fas fa-save"></i> Save All Changes</button>
    </div>
    
    <div class="bento-container">

        <form method="POST" action="{{ route('admin.settings.bulk') }}" data-submit="server" id="settings-form">
            @csrf
            <div class="bento-grid">
                
                <!-- General & Maintenance Card -->
                <div class="chart-card col-span-4">
                    <div class="chart-header" >
                        <h3 class="chart-title" ><i class="fas fa-power-off" ></i> System State</h3>
                    </div>
                    <div class="bento-body" >
                        <div class="toggle-label" >
                            <div>
                                <h4 >Maintenance Mode</h4>
                                <p >Temporarily take the site offline for visitors.</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="maintenance_mode" value="1" {{ \App\Models\Setting::get('maintenance_mode', false) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Appearance Card -->
                <div class="chart-card col-span-8">
                    <div class="chart-header" >
                        <h3 class="chart-title" ><i class="fas fa-paint-brush" ></i> Appearance</h3>
                    </div>
                    <div class="bento-body" >
                        <div class="form-group">
                            <label for="site-title">Site Title</label>
                            <input type="text" name="site_title" id="site-title" value="{{ \App\Models\Setting::get('site_title', 'Portfolio Manager') }}" >
                        </div>
                        <div class="form-group mb-0">
                            <label for="site-description">Site Description</label>
                            <textarea name="site_description" id="site-description" rows="3" >{{ \App\Models\Setting::get('site_description', 'Professional portfolio - Manage your projects, skills, and more.') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings Card -->
                <div class="chart-card col-span-12">
                    <div class="chart-header" >
                        <h3 class="chart-title" ><i class="fas fa-search" ></i> SEO Optimization</h3>
                    </div>
                    <div class="bento-body" >
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="meta-title">Meta Title</label>
                                <input type="text" name="meta_title" id="meta-title" value="{{ \App\Models\Setting::get('meta_title', 'Portfolio - Full Stack Developer') }}" >
                            </div>
                            <div class="form-group">
                                <label for="meta-keywords">Meta Keywords</label>
                                <input type="text" name="meta_keywords" id="meta-keywords" value="{{ \App\Models\Setting::get('meta_keywords', 'portfolio, web developer, full stack, projects') }}" >
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label for="meta-description">Meta Description</label>
                            <textarea name="meta_description" id="meta-description" rows="2" >{{ \App\Models\Setting::get('meta_description', 'Professional portfolio showcasing projects, skills, and experience.') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <!-- Advanced Key-Value Card -->
        <div class="chart-card" >
            <div class="chart-header">
                <div class="chart-title"><i class="fas fa-database" ></i> Key-Value Settings</div>
                <div class="chart-actions">
                    <button type="button" class="btn btn-secondary btn-icon" data-modal-open="add-setting-modal" title="Add Variable">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div >
                @if($settings->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Value</th>
                            <th>Owner</th>
                            <th>Type</th>
                            <th class="table-action-cell"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($settings as $setting)
                        <tr>
                            <td>
                                <div class="table-name-cell">
                                    <i class="fas fa-database"></i>
                                    <span><code>{{ $setting->key }}</code></span>
                                </div>
                            </td>
                            <td><div class="table-location-cell text-muted">{{ Str::limit($setting->value, 50) }}</div></td>
                            <td>
                                <div class="table-owner-cell">
                                    @php $profile = \App\Models\Profile::first(); @endphp
                                    <img src="{{ asset($profile->image_url ?? 'images/gilly.jpeg') }}" alt="Owner">
                                    <span>System</span>
                                </div>
                            </td>
                            <td>
                                <div class="table-location-cell">
                                    <span class="badge" >{{ $setting->type }}</span>
                                </div>
                            </td>
                            <td class="table-action-cell">
                                <div class="kebab-menu-wrapper">
                                    <button class="kebab-btn"><i class="fas fa-ellipsis-v"></i></button>
                                    <ul class="kebab-dropdown">
                                        <li><button type="button" data-modal-open="edit-setting-modal" data-setting-id="{{ $setting->id }}" data-setting-key="{{ $setting->key }}" data-setting-value="{{ $setting->value }}" data-setting-type="{{ $setting->type }}"><i class="fas fa-edit"></i> Edit</button></li>
                                        <li class="divider"></li>
                                        <li><button type="button" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.settings.destroy', $setting) }}" data-delete-name="{{ $setting->key }}"><i class="fas fa-trash"></i> Delete</button></li>
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
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <h2 class="empty-state-title">No custom settings</h2>
                    <p class="empty-state-description">Add custom key-value settings for your system configuration.</p>
                    <div class="empty-state-actions">
                        <button type="button" class="empty-state-btn" data-modal-open="add-setting-modal"><i class="fas fa-plus"></i> Add Variable</button>
                    </div>
                </div>
                @endif
            @if(method_exists($settings, 'hasPages') && $settings->hasPages())
                <div class="pagination-wrapper">
                    {{ $settings->links('admin.pagination') }}
                </div>
            @endif

            

                
            </div>
        </div>
    </div>
</div>

@push('modals')
<div class="modal-overlay" id="add-setting-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle" ></i> Add Setting</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.settings.store') }}" data-submit="server">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="setting-key">Key</label>
                    <input type="text" name="key" id="setting-key" placeholder="e.g. site_title" required>
                </div>
                <div class="form-group">
                    <label for="setting-value">Value</label>
                    <input type="text" name="value" id="setting-value" placeholder="Value">
                </div>
                <div class="form-group">
                    <label for="setting-type">Type</label>
                    <select name="type" id="setting-type">
                        <option value="string">String</option>
                        <option value="boolean">Boolean</option>
                        <option value="integer">Integer</option>
                        <option value="json">JSON</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="edit-setting-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit" ></i> Edit Setting</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="" id="edit-setting-form" data-submit="server">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label>Key</label>
                    <input type="text" id="edit-setting-key" readonly class="form-control">
                </div>
                <div class="form-group">
                    <label for="edit-setting-value">Value</label>
                    <input type="text" name="value" id="edit-setting-value">
                </div>
                <div class="form-group">
                    <label for="edit-setting-type">Type</label>
                    <select name="type" id="edit-setting-type">
                        <option value="string">String</option>
                        <option value="boolean">Boolean</option>
                        <option value="integer">Integer</option>
                        <option value="json">JSON</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>
@endpush

@endsection


