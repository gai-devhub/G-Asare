@extends('admin.admin')

@section('title', 'Skills')

@push('topbar-add')
<button type="button" class="btn btn-primary" data-modal-open="add-skill-modal"><i class="fas fa-plus"></i> Add Skill</button>
@endpush

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <h1><i class="fas fa-laptop-code" style="margin-right: 12px; color: var(--color-primary);"></i>Skills</h1>
        <p>Manage your skills to match the website structure: Frontend Development, Backend & Database, Tools & Platforms, and Soft Skills.</p>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-list" style="margin-right: 8px;"></i> All Skills ({{ $groupCount }} group{{ $groupCount !== 1 ? 's' : '' }})</div>
            <div class="chart-actions" style="display: flex; gap: 0.5rem;">
                <button type="button" class="btn btn-primary" data-modal-open="add-skill-modal"><i class="fas fa-plus"></i> Add Skill</button>
            </div>
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
            <h3 class="skills-section-title"><i class="fas fa-layer-group" style="margin-right: 8px; color: var(--color-secondary);"></i>{{ $category }}</h3>
            <table class="data-table" data-search-table>
                <thead>
                    <tr>
                        <th style="width: 25%;">Sub-Category</th>
                        <th style="width: 40%;">Description</th>
                        <th style="width: 15%;">Level</th>
                        <th style="width: 20%; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $subGroups = $grouped->get($category)->groupBy(fn($s) => $s->sub_category ?? ''); @endphp
                    @foreach($subGroups as $subCat => $groupSkills)
                    @php $first = $groupSkills->first(); $techStacks = $groupSkills->pluck('name')->implode(','); @endphp
                    <tr>
                        <td><strong>{{ $subCat ?: '—' }}</strong></td>
                        <td class="text-muted td-limit-280">{{ Str::limit($first->description, 80) ?: '—' }}</td>
                        <td>
                            @if($first->percentage !== null)
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="color: var(--color-primary); font-weight: bold;">{{ $first->percentage }}%</span>
                                    <div style="flex: 1; height: 6px; background: rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden;">
                                        <div style="height: 100%; width: {{ $first->percentage }}%; background: var(--color-primary); box-shadow: 0 0 10px var(--color-primary);"></div>
                                    </div>
                                </div>
                            @else
                                —
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                <button type="button" class="action-btn edit-btn" data-modal-open="edit-skill-modal" data-skill-id="{{ $first->id }}" data-skill-category="{{ $first->category }}" data-skill-subcategory="{{ $first->sub_category ?? '' }}" data-skill-description="{{ str_replace(["\r","\n"], ' ', $first->description ?? '') }}" data-skill-percentage="{{ $first->percentage }}" data-skill-order="{{ $first->sort_order }}" data-skill-tech-stacks="{{ $techStacks }}"><i class="fas fa-edit"></i> Edit</button>
                                <button type="button" class="action-btn delete-btn" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.skills.destroy', $first) }}" data-delete-name="{{ $subCat ? $subCat . ' and all its tech stacks' : 'this skill group' }}"><i class="fas fa-trash"></i> Delete</button>
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
        <div class="text-center text-muted py-4">No skills yet. Add your first one above.</div>
        @endif
    </div>
</div>

@push('modals')
<div class="modal-overlay" id="add-skill-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle" style="margin-right: 8px;"></i> Add New Skill</h3>
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
                    <p class="form-hint">Type tools and press Enter, or use quick add buttons</p>
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
            <h3><i class="fas fa-edit" style="margin-right: 8px;"></i> Edit Skill Group</h3>
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
                    <p class="form-hint">Type tools and press Enter, or use quick add buttons</p>
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
