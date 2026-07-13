@extends('admin.admin')

@section('title', 'About Me')

@section('content')
<div class="content-section active">
    <div class="page-header">
        <h1><i class="fas fa-user-astronaut" style="margin-right: 12px; color: var(--color-primary);"></i>About Me</h1>
        <p>Manage your profile and personal information.</p>
    </div>
    <form id="about-me-form" method="POST" action="{{ route('admin.about-me.update') }}" enctype="multipart/form-data" data-submit="server">
        @csrf
        @method('PUT')
        
        <div class="bento-container">


            <div class="bento-grid">
                <!-- Profile Information Card -->
                <div class="chart-card col-span-7">
                    <div class="chart-header" style="border-bottom: 1px solid var(--gray-light); padding-bottom: 1rem;">
                        <h3 class="chart-title" style="font-size: 1.1rem; color: var(--text);">Profile Information</h3>
                    </div>
                    <div class="bento-body" style="padding-top: 1rem;">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="profile-name">Full Name</label>
                                <input type="text" name="name" id="profile-name" value="{{ old('name', $profile->name ?? auth()->user()->name) }}" placeholder="Your full name" required style="border-radius: 8px;">
                            </div>
                            <div class="form-group">
                                <label for="profile-title">Professional Title</label>
                                <input type="text" name="tagline" id="profile-title" value="{{ old('tagline', $profile->tagline ?? '') }}" placeholder="e.g. Full Stack Developer" style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="profile-email">Email</label>
                                <input type="email" name="email" id="profile-email" value="{{ old('email', $profile->email ?? auth()->user()->email) }}" placeholder="Your email" style="border-radius: 8px;">
                            </div>
                            <div class="form-group">
                                <label for="profile-phone">Phone</label>
                                <input type="tel" name="phone" id="profile-phone" value="{{ old('phone', $profile->phone ?? '') }}" placeholder="+1 (555) 123-4567" style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="profile-bio">Bio</label>
                            <textarea name="bio" id="profile-bio" rows="5" placeholder="Tell visitors about yourself..." style="border-radius: 8px;">{{ old('bio', $profile->bio ?? '') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="profile-image">Profile Image</label>
                            @if(!empty($profile->image_url))
                                <div style="margin-bottom: 15px; display: flex; align-items: center; gap: 15px;">
                                    <img src="{{ asset($profile->image_url) }}" alt="Profile Image" style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover; border: 2px solid var(--color-primary);">
                                    <span style="font-size: 0.85rem; color: var(--gray);">Current Image</span>
                                </div>
                            @endif
                            <input type="file" name="image" id="profile-image" accept="image/*" class="form-control" style="border-radius: 8px;">
                        </div>
                    </div>
                </div>

                <!-- Right Column (Social & Contact) -->
                <div class="chart-card col-span-5" style="display: flex; flex-direction: column; gap: 1.5rem; background: transparent; box-shadow: none; border: none; padding: 0;">
                    
                    <!-- Social Media Card -->
                    <div class="chart-card" style="margin: 0;">
                        <div class="chart-header" style="border-bottom: 1px solid var(--gray-light); padding-bottom: 1rem;">
                            <h3 class="chart-title" style="font-size: 1.1rem; color: var(--text);">Social Media</h3>
                            <button type="button" class="btn btn-secondary btn-sm" data-modal-open="add-social-modal" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                        <div class="bento-body" style="padding-top: 1rem;">
                            <div id="social-media-container">
                                @php
                                    $oldPlatforms = old('social_platforms');
                                    $oldUrls = old('social_urls');
                                    $socialLinks = [];
                                    
                                    if (is_array($oldPlatforms) && is_array($oldUrls)) {
                                        foreach ($oldPlatforms as $i => $platform) {
                                            if (!empty($platform) && !empty($oldUrls[$i])) {
                                                $socialLinks[$platform] = $oldUrls[$i];
                                            }
                                        }
                                    } elseif (!empty($profile->social_links)) {
                                        $socialLinks = $profile->social_links;
                                    }
                                @endphp

                                @if(!empty($socialLinks))
                                    @foreach($socialLinks as $platform => $url)
                                        <div class="form-row align-items-center social-media-row" style="display: flex; gap: 10px; margin-bottom: 10px; background: var(--bg); padding: 10px; border-radius: 8px; border: 1px solid var(--gray-light);">
                                            <input type="hidden" name="social_platforms[]" value="{{ $platform }}">
                                            <input type="hidden" name="social_urls[]" value="{{ $url }}">
                                            
                                            <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(79, 70, 229, 0.15); color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                                                <i class="fab fa-{{ $platform == 'twitter' ? 'x-twitter' : $platform }}"></i>
                                            </div>
                                            <div style="flex: 1; min-width: 0;">
                                                <div style="font-size: 0.85rem; font-weight: 600; text-transform: capitalize; color: var(--text);">{{ $platform }}</div>
                                                <div style="font-size: 0.8rem; color: var(--gray); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $url }}</div>
                                            </div>
                                            <button type="button" style="width: 32px; height: 32px; border-radius: 6px; background: rgba(220, 38, 38, 0.1); color: #dc2626; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onclick="this.closest('.social-media-row').remove()" onmouseover="this.style.background='#dc2626'; this.style.color='white';" onmouseout="this.style.background='rgba(220, 38, 38, 0.1)'; this.style.color='#dc2626';">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Extended Contact Details Card -->
                    <div class="chart-card" style="margin: 0; flex: 1;">
                        <div class="chart-header" style="border-bottom: 1px solid var(--gray-light); padding-bottom: 1rem;">
                            <h3 class="chart-title" style="font-size: 1.1rem; color: var(--text);">Extended Contact</h3>
                        </div>
                        <div class="bento-body" style="padding-top: 1rem;">
                            <div class="form-group">
                                <label for="contact-email-2">Secondary Email</label>
                                <input type="email" name="contact_email_2" id="contact-email-2" value="{{ old('contact_email_2', ($profile->contact_info ?? [])['email_2'] ?? '') }}" placeholder="Secondary email for inquiries" style="border-radius: 8px;">
                            </div>
                            <div class="form-group">
                                <label for="contact-whatsapp">WhatsApp Number</label>
                                <input type="text" name="whatsapp" id="contact-whatsapp" value="{{ old('whatsapp', ($profile->contact_info ?? [])['whatsapp'] ?? '') }}" placeholder="WhatsApp number e.g. +233 (59) 921-5326" style="border-radius: 8px;">
                            </div>
                            <div class="form-group">
                                <label for="availability-hours">Availability</label>
                                <input type="text" name="availability_hours" id="availability-hours" value="{{ old('availability_hours', ($profile->contact_info ?? [])['availability_hours'] ?? '') }}" placeholder="e.g. Mon-Fri, 9 AM - 6 PM PST" style="border-radius: 8px;">
                            </div>
                            <div class="form-group">
                                <label for="work-modes">Work Modes</label>
                                <input type="text" name="work_modes" id="work-modes" value="{{ old('work_modes', ($profile->contact_info ?? [])['work_modes'] ?? '') }}" placeholder="e.g. Remote, Hybrid, or On-site" style="border-radius: 8px;">
                            </div>
                            <div class="form-group mb-0">
                                <label for="office-address">Office Address</label>
                                <textarea name="office_address" id="office-address" rows="2" placeholder="Tesano, GCTU Campus \n Accra, Ghana" style="border-radius: 8px;">{{ old('office_address', ($profile->contact_info ?? [])['office_address'] ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        
        <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        </div>
    </form>
</div>

@push('modals')
<div class="modal-overlay" id="add-social-modal" data-modal>
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle" style="margin-right: 8px;"></i> Add Social Link</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="modal-social-platform">Platform</label>
                <select id="modal-social-platform" class="form-control" style="border-radius: 8px;">
                    <option value="linkedin">LinkedIn</option>
                    <option value="github">GitHub</option>
                    <option value="twitter">Twitter / X</option>
                    <option value="dribbble">Dribbble</option>
                    <option value="instagram">Instagram</option>
                    <option value="codepen">CodePen</option>
                    <option value="whatsapp">WhatsApp</option>
                    <option value="facebook">Facebook</option>
                    <option value="youtube">YouTube</option>
                    <option value="medium">Medium</option>
                </select>
            </div>
            <div class="form-group">
                <label for="modal-social-url">Profile URL</label>
                <input type="url" id="modal-social-url" class="form-control" placeholder="https://..." style="border-radius: 8px;">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
            <button type="button" class="btn btn-primary" onclick="addSocialFromModal()"><i class="fas fa-plus"></i> Add to List</button>
        </div>
    </div>
</div>
@endpush

<script>
function addSocialFromModal() {
    const platformSelect = document.getElementById('modal-social-platform');
    const urlInput = document.getElementById('modal-social-url');
    
    const platform = platformSelect.value;
    const url = urlInput.value.trim();
    
    if (!url) {
        alert('Please enter a valid URL');
        urlInput.focus();
        return;
    }
    
    const container = document.getElementById('social-media-container');
    const row = document.createElement('div');
    row.className = 'form-row align-items-center social-media-row';
    row.style.display = 'flex';
    row.style.gap = '10px';
    row.style.marginBottom = '10px';
    row.style.background = 'var(--bg)';
    row.style.padding = '10px';
    row.style.borderRadius = '8px';
    row.style.border = '1px solid var(--gray-light)';
    
    let iconClass = platform === 'twitter' ? 'x-twitter' : platform;
    
    row.innerHTML = `
        <input type="hidden" name="social_platforms[]" value="${platform}">
        <input type="hidden" name="social_urls[]" value="${url}">
        
        <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(79, 70, 229, 0.15); color: #4f46e5; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
            <i class="fab fa-${iconClass}"></i>
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 0.85rem; font-weight: 600; text-transform: capitalize; color: var(--text);">${platform}</div>
            <div style="font-size: 0.8rem; color: var(--gray); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${url}</div>
        </div>
        <button type="button" style="width: 32px; height: 32px; border-radius: 6px; background: rgba(220, 38, 38, 0.1); color: #dc2626; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onclick="this.closest('.social-media-row').remove()" onmouseover="this.style.background='#dc2626'; this.style.color='white';" onmouseout="this.style.background='rgba(220, 38, 38, 0.1)'; this.style.color='#dc2626';">
            <i class="fas fa-trash"></i>
        </button>
    `;
    
    container.appendChild(row);
    
    // Reset and close modal
    urlInput.value = '';
    document.getElementById('add-social-modal').classList.remove('active');
    document.body.style.overflow = '';
    
    // Submit the form immediately to save to database
    document.getElementById('about-me-form').submit();
}
</script>
@endsection
