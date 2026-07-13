@extends('admin.admin')

@section('title', 'Settings')

@section('content')
<div class="content-section active">
    <div class="page-header">
        <h1><i class="fas fa-cogs" style="margin-right: 12px; color: var(--color-primary);"></i>Settings</h1>
        <p>Configure your portfolio and admin preferences.</p>
    </div>
    
    <div class="bento-container">
        <!-- Top Action Bar for Forms -->
        <div class="chart-card" style="padding: 1rem 1.5rem;">
            <div class="chart-header" style="margin: 0;">
                <h3 class="chart-title"><i class="fas fa-tools"></i> Global Configuration</h3>
                <div class="chart-actions">
                    <button type="submit" form="settings-form" class="btn btn-primary"><i class="fas fa-save"></i> Save All Changes</button>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.settings.bulk') }}" data-submit="server" id="settings-form">
            @csrf
            <div class="bento-grid">
                
                <!-- General & Maintenance Card -->
                <div class="chart-card col-span-4">
                    <div class="chart-header" style="border-bottom: 1px solid var(--gray-light); padding-bottom: 1rem;">
                        <h3 class="chart-title" style="font-size: 1.1rem; color: var(--text);"><i class="fas fa-power-off" style="margin-right: 8px;"></i> System State</h3>
                    </div>
                    <div class="bento-body" style="padding-top: 1.5rem; display: flex; flex-direction: column; justify-content: center; height: calc(100% - 3rem);">
                        <div class="toggle-label" style="background: var(--bg); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--gray-light);">
                            <div>
                                <h4 style="margin: 0 0 0.5rem 0; font-size: 1.1rem; color: var(--text);">Maintenance Mode</h4>
                                <p style="margin: 0; font-size: 0.85rem; color: var(--gray);">Temporarily take the site offline for visitors.</p>
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
                    <div class="chart-header" style="border-bottom: 1px solid var(--gray-light); padding-bottom: 1rem;">
                        <h3 class="chart-title" style="font-size: 1.1rem; color: var(--text);"><i class="fas fa-paint-brush" style="margin-right: 8px;"></i> Appearance</h3>
                    </div>
                    <div class="bento-body" style="padding-top: 1rem;">
                        <div class="form-group">
                            <label for="site-title">Site Title</label>
                            <input type="text" name="site_title" id="site-title" value="{{ \App\Models\Setting::get('site_title', 'Portfolio Manager') }}" style="border-radius: 8px;">
                        </div>
                        <div class="form-group mb-0">
                            <label for="site-description">Site Description</label>
                            <textarea name="site_description" id="site-description" rows="3" style="border-radius: 8px;">{{ \App\Models\Setting::get('site_description', 'Professional portfolio - Manage your projects, skills, and more.') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings Card -->
                <div class="chart-card col-span-12">
                    <div class="chart-header" style="border-bottom: 1px solid var(--gray-light); padding-bottom: 1rem;">
                        <h3 class="chart-title" style="font-size: 1.1rem; color: var(--text);"><i class="fas fa-search" style="margin-right: 8px;"></i> SEO Optimization</h3>
                    </div>
                    <div class="bento-body" style="padding-top: 1rem;">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="meta-title">Meta Title</label>
                                <input type="text" name="meta_title" id="meta-title" value="{{ \App\Models\Setting::get('meta_title', 'Portfolio - Full Stack Developer') }}" style="border-radius: 8px;">
                            </div>
                            <div class="form-group">
                                <label for="meta-keywords">Meta Keywords</label>
                                <input type="text" name="meta_keywords" id="meta-keywords" value="{{ \App\Models\Setting::get('meta_keywords', 'portfolio, web developer, full stack, projects') }}" style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label for="meta-description">Meta Description</label>
                            <textarea name="meta_description" id="meta-description" rows="2" style="border-radius: 8px;">{{ \App\Models\Setting::get('meta_description', 'Professional portfolio showcasing projects, skills, and experience.') }}</textarea>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <!-- Advanced Key-Value Card -->
        <div class="chart-card" style="margin-top: 1.5rem;">
            <div class="chart-header">
                <div class="chart-title"><i class="fas fa-database" style="margin-right: 8px;"></i> Key-Value Settings</div>
                <div class="chart-actions">
                    <button type="button" class="btn btn-secondary btn-sm" data-modal-open="add-setting-modal" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer;">
                        <i class="fas fa-plus"></i> Add Variable
                    </button>
                </div>
            </div>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Key</th>
                            <th>Value</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($settings as $setting)
                        <tr>
                            <td><code>{{ $setting->key }}</code></td>
                            <td>{{ Str::limit($setting->value, 50) }}</td>
                            <td><span class="badge" style="background: rgba(148, 163, 184, 0.2); color: #64748b; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; text-transform: uppercase;">{{ $setting->type }}</span></td>
                            <td>
                                <button type="button" class="action-btn edit-btn" data-modal-open="edit-setting-modal" data-setting-id="{{ $setting->id }}" data-setting-key="{{ $setting->key }}" data-setting-value="{{ $setting->value }}" data-setting-type="{{ $setting->type }}"><i class="fas fa-edit"></i> Edit</button>
                                <button type="button" class="action-btn delete-btn" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.settings.destroy', $setting) }}" data-delete-name="{{ $setting->key }}"><i class="fas fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">No custom settings configured yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
            <h3><i class="fas fa-plus-circle" style="margin-right: 8px;"></i> Add Setting</h3>
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
            <h3><i class="fas fa-edit" style="margin-right: 8px;"></i> Edit Setting</h3>
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
