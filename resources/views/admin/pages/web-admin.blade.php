@extends('admin.admin')

@section('title', 'Web & Admin Activity')

@section('content')
<div class="content-section active" data-searchable>
    <div class="page-header">
        <h1><i class="fas fa-chart-line" style="margin-right: 12px; color: var(--color-primary);"></i>Web & Admin Activity</h1>
        <p>Track activities on the website and in the admin panel: additions, page views, file access.</p>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-history" style="margin-right: 8px;"></i> Activity Log</div>
            <div class="chart-actions" style="display: flex; gap: 0.5rem;">
                <button class="action-btn edit-btn" title="Filter"><i class="fas fa-filter"></i></button>
                <button class="action-btn edit-btn" title="Export"><i class="fas fa-download"></i></button>
            </div>
        </div>

        <table class="data-table" data-search-table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Activity</th>
                    <th>Details</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr data-searchable-item>
                        <td>
                            @php
                                $type = strtolower($log->type);
                                $badgeClass = match ($type) {
                                    'admin' => 'admin-add',
                                    'view' => 'page-view',
                                    'file' => 'file-access',
                                    default => 'page-view',
                                };
                                $iconClass = match ($type) {
                                    'admin' => 'fas fa-user-shield',
                                    'file' => 'fas fa-file',
                                    default => 'fas fa-eye',
                                };
                            @endphp
                            <span class="activity-badge {{ $badgeClass }}">
                                <i class="{{ $iconClass }}"></i> {{ $log->type }}
                            </span>
                        </td>
                        <td>{{ $log->title }}</td>
                        <td>
                            @if($log->message)
                                {{ $log->message }}
                            @elseif($log->link)
                                {{ $log->link }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $log->created_at?->format('M d, Y g:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">No activity yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
            @if(method_exists($logs, 'hasPages') && $logs->hasPages())
                <div class="pagination-wrapper">
                    {{ $logs->links('admin.pagination') }}
                </div>
            @endif

            

            
        
    </div>
</div>
@endsection
