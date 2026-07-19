@extends('admin.admin')

@section('title', 'Projects')

@push('topbar-add')
<button type="button" class="btn btn-primary" data-modal-open="add-project-modal"><i class="fas fa-plus"></i> Add Project</button>
@endpush

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <div>
            <h1><i class="fas fa-project-diagram" ></i>Projects</h1>
            <p>Manage your portfolio projects.</p>
        </div>
        <button type="button" class="icon-btn" data-modal-open="add-project-modal" title="Add Project">
            <i class="fas fa-plus"></i>
        </button>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-list" ></i> All Projects</div>
        </div>
        
        @if($projects->count() > 0)
        <table class="data-table" data-search-table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Owner</th>
                    <th>Status</th>
                    <th class="table-action-cell"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                <tr>
                    <td>
                        <div class="table-name-cell">
                            @if($project->image_url)
                                <img src="{{ asset($project->image_url) }}" alt="Project" class="table-image-icon">
                            @else
                                <i class="fas fa-project-diagram"></i>
                            @endif
                            <span>{{ $project->title }}</span>
                        </div>
                    </td>
                    <td><span class="activity-badge page-view">{{ strtoupper($project->category) }}</span></td>
                    <td>
                        <div class="table-owner-cell">
                            @php $profile = \App\Models\Profile::first(); @endphp
                            <img src="{{ asset($profile->image_url ?? 'images/gilly.jpeg') }}" alt="Owner">
                            <span>me</span>
                        </div>
                    </td>
                    <td>
                        <div class="table-location-cell">
                            <span class="status {{ $project->is_active ? 'published' : 'draft' }}">{{ $project->is_active ? 'Active' : 'Draft' }}</span>
                        </div>
                    </td>
                    <td class="table-action-cell">
                        <div class="kebab-menu-wrapper">
                            <button class="kebab-btn"><i class="fas fa-ellipsis-v"></i></button>
                            <ul class="kebab-dropdown">
                                <li class="has-submenu">
                                    <button type="button"><i class="fas fa-info-circle"></i> File information <i class="fas fa-chevron-right"></i></button>
                                    <ul class="kebab-submenu kebab-submenu-left">
                                        <li><button type="button" onclick="openSidebar('details', { title: '{{ addslashes($project->title) }}', category: '{{ addslashes($project->category) }}', type: 'Project', owner: 'me', modified: '{{ $project->updated_at ? $project->updated_at->format('M d, Y') : 'Unknown' }}', created: '{{ $project->created_at ? $project->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: '{{ addslashes($project->description) }}', imageUrl: '{{ $project->image_url ? asset($project->image_url) : '' }}' })"><i class="fas fa-list"></i> Details</button></li>
                                        <li><button type="button" onclick="openSidebar('activity', { title: '{{ addslashes($project->title) }}', category: '{{ addslashes($project->category) }}', type: 'Project', owner: 'me', modified: '{{ $project->updated_at ? $project->updated_at->format('M d, Y') : 'Unknown' }}', created: '{{ $project->created_at ? $project->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: '{{ addslashes($project->description) }}', imageUrl: '{{ $project->image_url ? asset($project->image_url) : '' }}' })"><i class="fas fa-history"></i> Activity</button></li>
                                    </ul>
                                </li>
                                <li class="has-submenu">
                                    <button type="button"><i class="fas fa-share-alt"></i> Share <i class="fas fa-chevron-right"></i></button>
                                    <ul class="kebab-submenu kebab-submenu-left">
                                        <li><button type="button" onclick="copyToClipboard('{{ $project->project_url ?? '' }}'); showToast('Project link copied', 'success')"><i class="fas fa-project-diagram"></i> Share this project</button></li>
                                        <li><button type="button" onclick="copyToClipboard('{{ route('projects') }}'); showToast('All projects link copied', 'success')"><i class="fas fa-folder-open"></i> Share all links in project</button></li>
                                    </ul>
                                </li>
                                <li class="divider"></li>
                                <li><button type="button" data-modal-open="edit-project-modal" data-project-id="{{ $project->id }}" data-project-title="{{ $project->title }}" data-project-category="{{ $project->category }}" data-project-description="{{ $project->description }}" data-project-tags="{{ is_array($project->tags) ? implode(', ', $project->tags) : '' }}" data-project-url="{{ $project->project_url }}" data-project-github="{{ $project->github_url }}" data-project-image="{{ $project->image_url }}" data-project-featured="{{ $project->is_featured ? '1' : '0' }}"><i class="fas fa-edit"></i> Edit</button></li>
                                <li class="divider"></li>
                                <li><button type="button" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.projects.destroy', $project) }}" data-delete-name="{{ $project->title }}"><i class="fas fa-trash"></i> Delete</button></li>
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
                <i class="fas fa-project-diagram"></i>
            </div>
            <h2 class="empty-state-title">No projects yet</h2>
            <p class="empty-state-description">Add the exact projects you want to focus on so they appear on your public portfolio.</p>
            <div class="empty-state-actions">
                <button type="button" class="empty-state-btn" data-modal-open="add-project-modal"><i class="fas fa-plus"></i> Create a project</button>
            </div>
        </div>
        @endif
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
            <h3><i class="fas fa-plus-circle" ></i> Add New Project</h3>
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
            <h3><i class="fas fa-edit" ></i> Edit Project</h3>
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
                        <div id="edit-project-image-preview-container" style="display: none; margin-bottom: 1rem;">
                            <p style="margin-bottom: 0.5rem; font-size: 0.75rem; color: var(--color-text-muted);">Current image:</p>
                            <img id="edit-project-image-preview" src="" style="max-width: 100%; max-height: 120px; border-radius: 6px; object-fit: contain; border: 1px solid var(--border-color);">
                        </div>
                        <input type="file" name="image_url" id="edit-project-image" accept="image/*">
                        <p class="form-hint mt-1" style="font-size: 0.65rem; color: #6b7280; margin-top: 2px;">Leave empty to keep existing image</p>
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


