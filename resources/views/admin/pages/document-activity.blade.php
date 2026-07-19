@extends('admin.admin')

@section('title', 'Document & Link Activity')

@push('topbar-add')
@endpush

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <h1><i class="fas fa-mouse-pointer" ></i>Document & Link Activity</h1>
        <p>Track when visitors view or download your documents, certificates, awards, and files.</p>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-table" ></i> Activity Log Table</div>
        </div>

        <div class="table-responsive">
            @if($logs->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Action</th>
                        <th>Owner</th>
                        <th>Date</th>
                        <th class="table-action-cell"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        @php
                            $action = 'Viewed';
                            $itemType = 'Link';
                            $itemName = $log->message;

                            // Parse the generic logs into structured data
                            if ($log->title === 'Someone viewed page') {
                                if (str_contains($log->link, 'edu&certs')) {
                                    $action = 'Viewed';
                                    $itemType = 'Certificate / Education';
                                    $itemName = 'Certifications Page';
                                } elseif (str_contains($log->link, 'download')) {
                                    $action = 'Downloaded';
                                    $itemType = 'Document';
                                    // Try to extract document ID or show generic message
                                    $parts = explode('/', $log->link);
                                    $docId = isset($parts[1]) ? " (ID: {$parts[1]})" : '';
                                    $itemName = 'File Download' . $docId;
                                } elseif (str_contains($log->link, 'gallery')) {
                                    $action = 'Viewed';
                                    $itemType = 'Gallery';
                                    $itemName = 'Gallery Page';
                                } elseif (str_contains($log->link, 'awards')) {
                                    $action = 'Viewed';
                                    $itemType = 'Award';
                                    $itemName = 'Awards Page';
                                } else {
                                    $action = 'Clicked';
                                    $itemType = 'Web Page';
                                    $itemName = '/' . $log->link;
                                }
                            } elseif ($log->title === 'Downloaded document' || $log->type === 'Download') {
                                $action = 'Downloaded';
                                $itemType = 'Document';
                                $itemName = $log->message ?: 'Document File';
                            } elseif (str_contains(strtolower($log->title), 'document')) {
                                $action = str_replace(' document', '', $log->title);
                                $itemType = 'Document';
                                $itemName = $log->message ?: $log->title;
                            } else {
                                $action = $log->title;
                                $itemType = $log->type ?: 'Activity';
                                $itemName = $log->message ?: '-';
                            }
                        @endphp
                        <tr>
                            <td>
                                <div class="table-name-cell">
                                    <i class="fas fa-file-alt"></i>
                                    <span>{{ Str::limit($itemName, 60) }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="table-location-cell">
                                    <span class="status-badge">{{ $action }} ({{ $itemType }})</span>
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
                                    <span class="text-muted">{{ $log->created_at->format('M j, Y - g:i A') }}</span>
                                </div>
                            </td>
                            <td class="table-action-cell">
                                <div class="kebab-menu-wrapper">
                                    <button class="kebab-btn"><i class="fas fa-ellipsis-v"></i></button>
                                    <ul class="kebab-dropdown">
                                        <li class="has-submenu">
                                            <button type="button"><i class="fas fa-info-circle"></i> File information <i class="fas fa-chevron-right"></i></button>
                                            <ul class="kebab-submenu kebab-submenu-left">
                                                <li><button type="button" onclick="openSidebar('details', { title: '{{ addslashes($itemName) }}', category: '{{ addslashes($itemType) }}', type: 'Activity', owner: 'me', modified: '{{ $log->updated_at ? $log->updated_at->format('M d, Y') : 'Unknown' }}', created: '{{ $log->created_at ? $log->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: '{{ addslashes($log->description ?? 'No description') }}', imageUrl: '' })"><i class="fas fa-list"></i> Details</button></li>
                                                <li><button type="button" onclick="openSidebar('activity', { title: '{{ addslashes($itemName) }}', category: '{{ addslashes($itemType) }}', type: 'Activity', owner: 'me', modified: '{{ $log->updated_at ? $log->updated_at->format('M d, Y') : 'Unknown' }}', created: '{{ $log->created_at ? $log->created_at->format('M d, Y') : 'Unknown' }}', opened: 'Unknown', size: '-', description: '{{ addslashes($log->description ?? 'No description') }}', imageUrl: '' })"><i class="fas fa-history"></i> Activity</button></li>
                                            </ul>
                                        </li>

                                        <li class="divider"></li>
                                        <li><button type="button" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.document-activity.destroy', $log) }}" data-delete-name="this activity log"><i class="fas fa-trash"></i> Delete</button></li>
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
                    <i class="fas fa-file-invoice"></i>
                </div>
                <h2 class="empty-state-title">No activity recorded</h2>
                <p class="empty-state-description">Your document and file activities will be recorded and shown here.</p>

            </div>
            @endif
        </div>

        @if($logs->hasPages())
        <div class="pagination-wrapper p-3" >
            {{ $logs->links('admin.pagination') }}
        </div>
        @endif
    </div>
</div>
@endsection




