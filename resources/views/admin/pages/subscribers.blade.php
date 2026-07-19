@extends('admin.admin')

@section('title', 'Subscribers')

@section('content')
<div class="content-section active">
    <div class="page-header">
        <h1><i class="fas fa-users" ></i>Subscribers</h1>
        <p>Manage your newsletter subscribers and their status.</p>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success" >
            {{ session('success') }}
        </div>
    @endif

    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-list" ></i> All Subscribers ({{ $subscribers->total() }})</div>
        </div>

        @if($subscribers->count() > 0)
        <table class="data-table" data-search-table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Owner</th>
                    <th>Subscribed At</th>
                    <th class="table-action-cell"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($subscribers as $subscriber)
                <tr>
                    <td>
                        <div class="table-name-cell">
                            <i class="fas fa-user"></i>
                            <span>{{ $subscriber->email }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="table-location-cell">
                        @if($subscriber->is_active)
                            <span style="color: #34a853;"><i class="fas fa-check-circle"></i> Active</span>
                        @else
                            <span class="text-muted"><i class="fas fa-times-circle"></i> Inactive</span>
                        @endif
                        </div>
                    </td>
                    <td>
                        <div class="table-owner-cell">
                            @php $profile = \App\Models\Profile::first(); @endphp
                            <img src="{{ asset($profile->image_url ?? 'images/gilly.jpeg') }}" alt="Owner">
                            <span>me</span>
                        </div>
                    </td>
                    <td>
                        <div class="table-location-cell">
                            <span class="text-muted">{{ $subscriber->created_at->format('M j, Y') }}</span>
                        </div>
                    </td>
                    <td class="table-action-cell">
                        <div class="kebab-menu-wrapper">
                            <button class="kebab-btn"><i class="fas fa-ellipsis-v"></i></button>
                            <ul class="kebab-dropdown">
                                <li class="has-submenu">
                                    <button type="button"><i class="fas fa-info-circle"></i> File information <i class="fas fa-chevron-right"></i></button>
                                    <ul class="kebab-submenu kebab-submenu-left">
                                        <li><button type="button" onclick="openSidebar('details', { title: '{{ addslashes($subscriber->email) }}', category: 'Subscriber', type: 'Email', owner: 'me', modified: '{{ $subscriber->updated_at ? $subscriber->updated_at->format('M d, Y') : 'Unknown' }}', created: '{{ $subscriber->created_at ? $subscriber->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: 'Subscribed email address', imageUrl: '' })"><i class="fas fa-list"></i> Details</button></li>
                                        <li><button type="button" onclick="openSidebar('activity', { title: '{{ addslashes($subscriber->email) }}', category: 'Subscriber', type: 'Email', owner: 'me', modified: '{{ $subscriber->updated_at ? $subscriber->updated_at->format('M d, Y') : 'Unknown' }}', created: '{{ $subscriber->created_at ? $subscriber->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: 'Subscribed email address', imageUrl: '' })"><i class="fas fa-history"></i> Activity</button></li>
                                    </ul>
                                </li>
                                <li class="has-submenu">
                                    <button type="button"><i class="fas fa-share-alt"></i> Share <i class="fas fa-chevron-right"></i></button>
                                    <ul class="kebab-submenu kebab-submenu-left">
                                        <li><button type="button" onclick="showToast('Share dialog opened', 'success')"><i class="fas fa-user-plus"></i> Share</button></li>
                                        <li class="has-submenu">
                                            <button type="button"><i class="fas fa-link"></i> Copy link <i class="fas fa-chevron-right"></i></button>
                                            <ul class="kebab-submenu kebab-submenu-left">
                                                <li><button type="button" onclick="copyToClipboard('{{ route('admin.subscribers') }}')"><i class="fas fa-external-link-alt"></i> Copy Link</button></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" method="POST" style="width: 100%; margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this subscriber?')" style="display: flex; align-items: center; gap: 12px; width: 100%; padding: 10px 16px; color: var(--color-text); text-decoration: none; font-size: 0.875rem; background: transparent; border: none; text-align: left; cursor: pointer;">
                                            <i class="fas fa-trash" style="font-size: 1.125rem; color: var(--color-text-muted); width: 20px; text-align: center;"></i> Delete
                                        </button>
                                    </form>
                                </li>
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
                <i class="fas fa-users-slash"></i>
            </div>
            <h2 class="empty-state-title">No subscribers yet</h2>
            <p class="empty-state-description">They will appear here once someone subscribes to your newsletter.</p>

        </div>
        @endif
        
        <div >
            {{ $subscribers->links('admin.pagination') }}
        </div>
    </div>
</div>
@endsection


