@extends('admin.admin')

@section('title', 'Web & Admin Activity')

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <h1><i class="fas fa-chart-line" ></i>Web & Admin Activity</h1>
        <p>Track activities on the website and in the admin panel: additions, page views, file access.</p>
    </div>

    <div class="chart-card table-card-layout">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-history" ></i> Activity Log</div>

        </div>

        <div class="table-responsive">
            @if($logs->count() > 0)
        <table class="data-table" data-search-table>
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Details</th>
                    <th>Owner</th>
                    <th>Time</th>
                    <th class="table-action-cell"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr data-searchable-item>
                        <td>
                            <div class="table-name-cell">
                                @php
                                    $type = strtolower($log->type);
                                    $iconClass = match ($type) {
                                        'admin' => 'fas fa-user-shield',
                                        'file' => 'fas fa-file',
                                        default => 'fas fa-eye',
                                    };
                                @endphp
                                <i class="{{ $iconClass }}"></i>
                                <span>{{ $log->title }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="table-location-cell text-muted">
                                @if($log->message)
                                    {{ Str::limit($log->message, 50) }}
                                @elseif($log->link)
                                    {{ Str::limit($log->link, 50) }}
                                @else
                                    -
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
                                <span class="text-muted">{{ $log->created_at?->format('M d, Y g:i A') }}</span>
                            </div>
                        </td>
                        <td class="table-action-cell">
                            <div class="kebab-menu-wrapper">
                                <button class="kebab-btn"><i class="fas fa-ellipsis-v"></i></button>
                                <ul class="kebab-dropdown">
                                    <li class="has-submenu">
                                        <button type="button"><i class="fas fa-info-circle"></i> File information <i class="fas fa-chevron-right"></i></button>
                                        <ul class="kebab-submenu kebab-submenu-left">
                                            <li><button type="button" onclick="openSidebar('details', { title: '{{ addslashes($log->action) }}', category: 'Activity Log', type: 'Log', owner: 'System', modified: '{{ $log->created_at ? $log->created_at->format('M d, Y') : 'Unknown' }}', created: '{{ $log->created_at ? $log->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: '{{ addslashes($log->description ?? 'No description') }}', imageUrl: '' })"><i class="fas fa-list"></i> Details</button></li>
                                            <li><button type="button" onclick="openSidebar('activity', { title: '{{ addslashes($log->action) }}', category: 'Activity Log', type: 'Log', owner: 'System', modified: '{{ $log->created_at ? $log->created_at->format('M d, Y') : 'Unknown' }}', created: '{{ $log->created_at ? $log->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: '{{ addslashes($log->description ?? 'No description') }}', imageUrl: '' })"><i class="fas fa-history"></i> Activity</button></li>
                                        </ul>
                                    </li>

                                    <li class="divider"></li>
                                    <li><button type="button"><i class="fas fa-info-circle"></i> View Details</button></li>
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
                <i class="fas fa-history"></i>
            </div>
            <h2 class="empty-state-title">No activity yet</h2>
            <p class="empty-state-description">System activity and logs will appear here once actions are performed.</p>

        </div>
        @endif
        </div>
            @if(method_exists($logs, 'hasPages') && $logs->hasPages())
                <div class="pagination-wrapper">
                    {{ $logs->links('admin.pagination') }}
                </div>
            @endif

            

            
        
    </div>
</div>
@endsection




