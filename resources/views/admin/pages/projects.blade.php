@extends('admin.admin')

@section('title', 'Projects')

@push('topbar-add')
<button type="button" class="btn btn-primary" data-modal-open="add-project-modal"><i class="fas fa-plus"></i> Add Project</button>
@endpush

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <h1><i class="fas fa-project-diagram" style="margin-right: 12px; color: var(--color-primary);"></i>Projects</h1>
        <p>Manage your portfolio projects.</p>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-list" style="margin-right: 8px;"></i> All Projects</div>
            <div class="chart-actions" style="display: flex; gap: 0.5rem;">
                <button type="button" class="btn btn-primary" data-modal-open="add-project-modal"><i class="fas fa-plus"></i> Add Project</button>
            </div>
        </div>
        
        <table class="data-table" data-search-table>
            <thead>
                <tr>
                    <th style="width: 40%;">Project</th>
                    <th style="width: 25%;">Category</th>
                    <th style="width: 15%;">Status</th>
                    <th style="width: 20%; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                <tr>
                    <td><strong>{{ $project->title }}</strong></td>
                    <td><span class="activity-badge page-view">{{ strtoupper($project->category) }}</span></td>
                    <td><span class="status {{ $project->is_active ? 'published' : 'draft' }}">{{ $project->is_active ? 'Active' : 'Draft' }}</span></td>
                    <td style="text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                            <button type="button" class="action-btn edit-btn" data-modal-open="edit-project-modal" data-project-id="{{ $project->id }}" data-project-title="{{ $project->title }}" data-project-category="{{ $project->category }}" data-project-description="{{ $project->description }}" data-project-tags="{{ is_array($project->tags) ? implode(', ', $project->tags) : '' }}" data-project-url="{{ $project->project_url }}" data-project-github="{{ $project->github_url }}" data-project-image="{{ $project->image_url }}" data-project-featured="{{ $project->is_featured ? '1' : '0' }}"><i class="fas fa-edit"></i> Edit</button>
                            <button type="button" class="action-btn delete-btn" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.projects.destroy', $project) }}" data-delete-name="{{ $project->title }}"><i class="fas fa-trash"></i> Delete</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">No projects yet. Add your first one above.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
            @if(method_exists($projects, 'hasPages') && $projects->hasPages())
                <div class="pagination-wrapper">
                    {{ $projects->links('admin.pagination') }}
                </div>
            @endif

            

            
    </div>
</div>

@push('modals')
<div class="modal-overlay" id="add-project-modal" data-modal>
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle" style="margin-right: 8px;"></i> Add New Project</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data" data-submit="server">
            @csrf
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="project-title">Project Title</label>
                        <input type="text" name="title" id="project-title" placeholder="Enter project title" required>
                    </div>
                    <div class="form-group">
                        <label for="project-category">Category</label>
                        <select name="category" id="project-category" required>
                            <option value="">Select category</option>
                            <option value="web">Web Development</option>
                            <option value="python">Python</option>
                            <option value="php">PHP</option>
                            <option value="js">JavaScript</option>
                            <option value="app">App Development</option>
                            <option value="ui">UI/UX Design</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="project-description">Description</label>
                    <textarea name="description" id="project-description" placeholder="Enter project description" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label>Tech Stack / Tools</label>
                    <p class="form-hint">Type tools and press Enter, or use quick add buttons</p>
                    <div class="tech-stack-container">
                        <div class="tech-stack-tags" id="project-tech-tags"></div>
                        <div class="tech-stack-input-wrap">
                            <input type="text" id="project-tech-input" placeholder="Type a tool (e.g. React, Laravel) and press Enter" autocomplete="off">
                        </div>
                        <div class="tech-stack-presets">
                            <span class="preset-label">Quick add:</span>
                            <button type="button" class="tech-preset-btn" data-tech="React">React</button>
                            <button type="button" class="tech-preset-btn" data-tech="Vue">Vue</button>
                            <button type="button" class="tech-preset-btn" data-tech="Laravel">Laravel</button>
                            <button type="button" class="tech-preset-btn" data-tech="HTML">HTML</button>
                            <button type="button" class="tech-preset-btn" data-tech="CSS">CSS</button>
                            <button type="button" class="tech-preset-btn" data-tech="JavaScript">JavaScript</button>
                            <button type="button" class="tech-preset-btn" data-tech="PHP">PHP</button>
                            <button type="button" class="tech-preset-btn" data-tech="Node.js">Node.js</button>
                            <button type="button" class="tech-preset-btn" data-tech="Tailwind">Tailwind</button>
                            <button type="button" class="tech-preset-btn" data-tech="MySQL">MySQL</button>
                            <button type="button" class="tech-preset-btn" data-tech="Git">Git</button>
                        </div>
                        <input type="hidden" name="tags" id="project-tech-stack-hidden" value="">
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="project-image">Project Image</label>
                        <input type="file" name="image_url" id="project-image" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="project-url">Project URL</label>
                        <input type="url" name="project_url" id="project-url" placeholder="Enter project URL">
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="project-github">GitHub URL</label>
                        <input type="url" name="github_url" id="project-github" placeholder="Enter GitHub URL">
                    </div>
                    <div class="form-group">
                        <div class="toggle-label mt-4">
                            <span>Featured Project</span>
                            <label class="toggle-switch">
                                <input type="checkbox" name="is_featured" id="project-featured" value="1">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Project</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="edit-project-modal" data-modal>
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3><i class="fas fa-edit" style="margin-right: 8px;"></i> Edit Project</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="" id="edit-project-form" enctype="multipart/form-data" data-submit="server">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit-project-title">Project Title</label>
                        <input type="text" name="title" id="edit-project-title" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-project-category">Category</label>
                        <select name="category" id="edit-project-category">
                            <option value="web">Web Development</option>
                            <option value="python">Python</option>
                            <option value="php">PHP</option>
                            <option value="js">JavaScript</option>
                            <option value="app">App Development</option>
                            <option value="ui">UI/UX Design</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="edit-project-description">Description</label>
                    <textarea name="description" id="edit-project-description" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label>Tech Stack / Tools</label>
                    <div class="tech-stack-container">
                        <div class="tech-stack-tags" id="edit-project-tech-tags"></div>
                        <div class="tech-stack-input-wrap">
                            <input type="text" id="edit-project-tech-input" placeholder="Type and press Enter" autocomplete="off">
                        </div>
                        <input type="hidden" name="tags" id="edit-project-tech-stack-hidden" value="">
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit-project-image">Project Image</label>
                        <input type="file" name="image_url" id="edit-project-image" accept="image/*">
                        <p class="form-hint mt-1" style="font-size: 0.8rem; color: var(--text-muted); margin-top: 5px;">Leave empty to keep existing image</p>
                    </div>
                    <div class="form-group">
                        <label for="edit-project-url">Project URL</label>
                        <input type="url" name="project_url" id="edit-project-url">
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit-project-github">GitHub URL</label>
                        <input type="url" name="github_url" id="edit-project-github">
                    </div>
                    <div class="form-group">
                        <div class="toggle-label mt-4">
                            <span>Featured Project</span>
                            <label class="toggle-switch">
                                <input type="checkbox" name="is_featured" id="edit-project-featured" value="1">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
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
