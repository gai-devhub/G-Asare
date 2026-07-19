@extends('admin.admin')

@section('title', 'Skills')

@push('topbar-add')
<button type="button" class="btn btn-primary" data-modal-open="add-skill-modal"><i class="fas fa-plus"></i> Add Skill</button>
@endpush

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <div>
            <h1><i class="fas fa-laptop-code" ></i>Skills</h1>
            <p>Manage your skills to match the website structure: Frontend Development, Backend & Database, Tools & Platforms, and Soft Skills.</p>
        </div>
        <button type="button" class="icon-btn" data-modal-open="add-skill-modal" title="Add Skill">
            <i class="fas fa-plus"></i>
        </button>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-list" ></i> All Skills ({{ $groupCount }} group{{ $groupCount !== 1 ? 's' : '' }})</div>
        </div>
        
        @php
            $grouped = $skills->groupBy('category');
            $categoryOrder = ['Frontend Development', 'Backend & Database', 'Tools & Platforms', 'Soft Skills'];
            $sortedCategories = $grouped->keys()->sortBy(function ($c) use ($categoryOrder) {
                $pos = array_search($c, $categoryOrder);
                return $pos !== false ? $pos : 999;
            });
        @endphp
        
        @foreach($sortedCategories as $category)
        <div class="skills-section">
            <h3 class="skills-section-title"><i class="fas fa-layer-group" ></i>{{ $category }}</h3>
            <table class="data-table" data-search-table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Owner</th>
                        <th>Level</th>
                        <th class="table-action-cell"></th>
                    </tr>
                </thead>
                <tbody>
                    @php $subGroups = $grouped->get($category)->groupBy(fn($s) => $s->sub_category ?? ''); @endphp
                    @foreach($subGroups as $subCat => $groupSkills)
                    @php $first = $groupSkills->first(); $techStacks = $groupSkills->pluck('name')->implode(','); @endphp
                    <tr>
                        <td>
                            <div class="table-name-cell">
                                <i class="fas fa-layer-group"></i>
                                <span>{{ $subCat ?: '—' }}</span>
                            </div>
                        </td>
                        <td class="text-muted td-limit-280">{{ Str::limit($first->description, 80) ?: '—' }}</td>
                        <td>
                            <div class="table-owner-cell">
                                @php $profile = \App\Models\Profile::first(); @endphp
                                <img src="{{ asset($profile->image_url ?? 'images/gilly.jpeg') }}" alt="Owner">
                                <span>me</span>
                            </div>
                        </td>
                        <td>
                            <div class="table-location-cell">
                            @if($first->percentage !== null)
                                <span>{{ $first->percentage }}%</span>
                            @else
                                <span>—</span>
                            @endif
                            </div>
                        </td>
                        <td class="table-action-cell">
                            <div class="kebab-menu-wrapper">
                                <button class="kebab-btn"><i class="fas fa-ellipsis-v"></i></button>
                                <ul class="kebab-dropdown">
                                    <li class="has-submenu">
                                        <button type="button"><i class="fas fa-info-circle"></i> File information <i class="fas fa-chevron-right"></i></button>
                                        <ul class="kebab-submenu kebab-submenu-left">
                                            <li><button type="button" onclick="openSidebar('details', { title: '{{ addslashes($subCat ? $subCat : $first->category) }}', category: '{{ addslashes($first->category) }}', type: 'Skill', owner: 'me', modified: '{{ $first->updated_at ? $first->updated_at->format('M d, Y') : 'Unknown' }}', created: '{{ $first->created_at ? $first->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: '{{ addslashes(str_replace(["\r","\n"], ' ', $first->description ?? '')) }}', imageUrl: '' })"><i class="fas fa-list"></i> Details</button></li>
                                            <li><button type="button" onclick="openSidebar('activity', { title: '{{ addslashes($subCat ? $subCat : $first->category) }}', category: '{{ addslashes($first->category) }}', type: 'Skill', owner: 'me', modified: '{{ $first->updated_at ? $first->updated_at->format('M d, Y') : 'Unknown' }}', created: '{{ $first->created_at ? $first->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: '{{ addslashes(str_replace(["\r","\n"], ' ', $first->description ?? '')) }}', imageUrl: '' })"><i class="fas fa-history"></i> Activity</button></li>
                                        </ul>
                                    </li>
                                    <li class="has-submenu">
                                        <button type="button"><i class="fas fa-share-alt"></i> Share <i class="fas fa-chevron-right"></i></button>
                                        <ul class="kebab-submenu kebab-submenu-left">
                                            <li><button type="button" onclick="showToast('Share dialog opened', 'success')"><i class="fas fa-user-plus"></i> Share</button></li>
                                            <li class="has-submenu">
                                                <button type="button"><i class="fas fa-link"></i> Copy link <i class="fas fa-chevron-right"></i></button>
                                                <ul class="kebab-submenu kebab-submenu-left">
                                                    <li><button type="button" onclick="copyToClipboard('{{ route('admin.skills') }}')"><i class="fas fa-external-link-alt"></i> Copy Link</button></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="divider"></li>
                                    <li><button type="button" data-modal-open="edit-skill-modal" data-skill-id="{{ $first->id }}" data-skill-category="{{ $first->category }}" data-skill-subcategory="{{ $first->sub_category ?? '' }}" data-skill-description="{{ str_replace(["\r","\n"], ' ', $first->description ?? '') }}" data-skill-percentage="{{ $first->percentage }}" data-skill-order="{{ $first->sort_order }}" data-skill-tech-stacks="{{ $techStacks }}"><i class="fas fa-edit"></i> Edit</button></li>
                                    <li class="divider"></li>
                                    <li><button type="button" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.skills.destroy', $first) }}" data-delete-name="{{ $subCat ? $subCat . ' and all its tech stacks' : 'this skill group' }}"><i class="fas fa-trash"></i> Delete</button></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if(method_exists($sortedCategories, 'hasPages') && $sortedCategories->hasPages())
                <div class="pagination-wrapper">
                    {{ $sortedCategories->links('admin.pagination') }}
                </div>
            @endif

            

            
        </div>
        @endforeach
        
        @if($skills->isEmpty())
        <div class="empty-state-container">
            <div class="empty-state-illustration">
                <i class="fas fa-laptop-code"></i>
            </div>
            <h2 class="empty-state-title">No skills added</h2>
            <p class="empty-state-description">Add your technical and soft skills to build up your professional portfolio.</p>
            <div class="empty-state-actions">
                <button type="button" class="empty-state-btn" data-modal-open="add-skill-modal"><i class="fas fa-plus"></i> Add Skill</button>
            </div>
        </div>
        @endif
    </div>
</div>

@push('modals')
<div class="modal-overlay" id="add-skill-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle" ></i> Add New Skill</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.skills.store-bulk') }}" data-submit="server">
            @csrf
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="skill-category">Main Category</label>
                        <select name="category" id="skill-category" required>
                            <option value="">Select category</option>
                            <option value="Frontend Development">Frontend Development</option>
                            <option value="Backend & Database">Backend & Database</option>
                            <option value="Tools & Platforms">Tools & Platforms</option>
                            <option value="Soft Skills">Soft Skills</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="skill-subcategory">Sub-Category</label>
                        <input type="text" name="sub_category" id="skill-subcategory" placeholder="e.g. Markup & Styling, JavaScript & Frameworks">
                    </div>
                </div>
                <div class="form-group">
                    <label for="skill-description">Description</label>
                    <textarea name="description" id="skill-description" rows="3" placeholder="Optional description for this skill or group"></textarea>
                </div>
                <div class="form-group">
                    <label>Tech Stack / Tools</label>

                    <div class="tech-stack-container">
                        <div class="tech-stack-tags" id="skills-tech-tags"></div>
                        <div class="tech-stack-input-wrap">
                            <input type="text" id="skills-tech-input" placeholder="Type a tool (e.g. React, Laravel) and press Enter" autocomplete="off">
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
                        <input type="hidden" name="skills" id="skills-tech-stack-hidden" value="">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Skills</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="edit-skill-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-edit" ></i> Edit Skill Group</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="" id="edit-skill-form" data-submit="server">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit-skill-category">Main Category</label>
                        <select name="category" id="edit-skill-category" required>
                            <option value="">Select category</option>
                            <option value="Frontend Development">Frontend Development</option>
                            <option value="Backend & Database">Backend & Database</option>
                            <option value="Tools & Platforms">Tools & Platforms</option>
                            <option value="Soft Skills">Soft Skills</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-skill-subcategory">Sub-Category</label>
                        <input type="text" name="sub_category" id="edit-skill-subcategory" placeholder="e.g. Markup & Styling, JavaScript & Frameworks">
                    </div>
                </div>
                <div class="form-group">
                    <label for="edit-skill-description">Description</label>
                    <textarea name="description" id="edit-skill-description" rows="3" placeholder="Optional description for this skill or group"></textarea>
                </div>
                <div class="form-group">
                    <label>Tech Stack / Tools</label>

                    <div class="tech-stack-container">
                        <div class="tech-stack-tags" id="edit-skills-tech-tags"></div>
                        <div class="tech-stack-input-wrap">
                            <input type="text" id="edit-skills-tech-input" placeholder="Type a tool (e.g. React, Laravel) and press Enter" autocomplete="off">
                        </div>
                        <div class="tech-stack-presets">
                            <span class="preset-label">Quick add:</span>
                            <button type="button" class="tech-preset-btn edit-tech-preset" data-tech="React">React</button>
                            <button type="button" class="tech-preset-btn edit-tech-preset" data-tech="Vue">Vue</button>
                            <button type="button" class="tech-preset-btn edit-tech-preset" data-tech="Laravel">Laravel</button>
                            <button type="button" class="tech-preset-btn edit-tech-preset" data-tech="HTML">HTML</button>
                            <button type="button" class="tech-preset-btn edit-tech-preset" data-tech="CSS">CSS</button>
                            <button type="button" class="tech-preset-btn edit-tech-preset" data-tech="JavaScript">JavaScript</button>
                            <button type="button" class="tech-preset-btn edit-tech-preset" data-tech="PHP">PHP</button>
                            <button type="button" class="tech-preset-btn edit-tech-preset" data-tech="Node.js">Node.js</button>
                            <button type="button" class="tech-preset-btn edit-tech-preset" data-tech="Tailwind">Tailwind</button>
                            <button type="button" class="tech-preset-btn edit-tech-preset" data-tech="MySQL">MySQL</button>
                            <button type="button" class="tech-preset-btn edit-tech-preset" data-tech="Git">Git</button>
                        </div>
                        <input type="hidden" name="skills" id="edit-skill-skills-hidden" value="">
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit-skill-level">Proficiency Level (%)</label>
                        <input type="number" name="percentage" id="edit-skill-level" min="0" max="100" placeholder="Leave empty for no percentage">
                    </div>
                    <div class="form-group">
                        <label for="edit-skill-order">Display Order</label>
                        <input type="number" name="sort_order" id="edit-skill-order" min="0">
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


