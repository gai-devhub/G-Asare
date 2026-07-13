@extends('admin.admin')

@section('title', 'Subscribers')

@section('content')
<div class="content-section active">
    <div class="page-header">
        <h1><i class="fas fa-users" style="margin-right: 12px; color: var(--color-primary);"></i>Subscribers</h1>
        <p>Manage your newsletter subscribers and their status.</p>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success" style="padding: 15px; margin-bottom: 20px; border-radius: 8px; background-color: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2);">
            {{ session('success') }}
        </div>
    @endif

    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title"><i class="fas fa-list" style="margin-right: 8px;"></i> All Subscribers ({{ $subscribers->total() }})</div>
        </div>

        <table class="data-table" data-search-table>
            <thead>
                <tr>
                    <th style="width: 40%;">Email</th>
                    <th style="width: 20%;">Status</th>
                    <th style="width: 20%;">Subscribed At</th>
                    <th style="width: 20%; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $subscriber)
                <tr>
                    <td><strong>{{ $subscriber->email }}</strong></td>
                    <td>
                        @if($subscriber->is_active)
                            <span style="color: var(--success, #10b981); font-weight: bold;"><i class="fas fa-check-circle"></i> Active</span>
                        @else
                            <span style="color: var(--danger, #ef4444); font-weight: bold;"><i class="fas fa-times-circle"></i> Inactive</span>
                        @endif
                    </td>
                    <td class="text-muted">{{ $subscriber->created_at->format('M j, Y') }}</td>
                    <td class="action-cell" style="text-align: right;">
                        <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this subscriber?')" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <div style="font-size: 3rem; color: var(--border); margin-bottom: 15px;"><i class="fas fa-users-slash"></i></div>
                        <p>No subscribers yet. They will appear here once someone subscribes.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div style="margin-top: 20px;">
            {{ $subscribers->links('admin.pagination') }}
        </div>
    </div>
</div>
@endsection
