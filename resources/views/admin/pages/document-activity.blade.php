@extends('admin.admin')

@section('title', 'Document & Link Activity')

@push('topbar-add')
@endpush

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <h1><i class="fas fa-mouse-pointer" style="margin-right: 12px; color: var(--color-primary);"></i>Document & Link Activity</h1>
        <p>Track when visitors view or download your documents, certificates, awards, and files.</p>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-table" style="margin-right: 8px;"></i> Activity Log Table</div>
        </div>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Item Type</th>
                        <th>Name / Details</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th style="text-align: right;">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
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
                            <td><span class="status-badge" style="background: rgba(var(--color-primary-rgb), 0.1); color: var(--color-primary);">{{ $action }}</span></td>
                            <td><span style="color: var(--gray);">{{ $itemType }}</span></td>
                            <td style="font-weight: 500;">{{ Str::limit($itemName, 60) }}</td>
                            <td>{{ $log->created_at->format('M j, Y') }}</td>
                            <td>{{ $log->created_at->format('g:i A') }}</td>
                            <td style="text-align: right;">
                                <button type="button" class="action-btn delete-btn" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.document-activity.destroy', $log) }}" data-delete-name="this activity log" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4" style="color: var(--gray);">No activity recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="pagination-wrapper p-3" style="border-top: 1px solid rgba(255,255,255,0.05);">
            {{ $logs->links('admin.pagination') }}
        </div>
        @endif
    </div>
</div>
@endsection
