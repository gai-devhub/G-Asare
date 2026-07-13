@extends('admin.admin')

@section('title', 'Messages')

@push('topbar-add')
<button type="button" class="btn btn-primary" data-modal-open="compose-message-modal"><i class="fas fa-pen"></i> Compose</button>
@endpush

@section('content')
<div class="content-section active">
    <div class="page-header">
        <h1><i class="fas fa-envelope" style="margin-right: 12px; color: var(--color-primary);"></i>Messages</h1>
        <p>Manage your portfolio messages and inquiries.</p>
    </div>
    
    <div class="email-layout">
        <aside class="email-sidebar">
            <button type="button" class="email-compose-btn" data-modal-open="compose-message-modal">
                <i class="fas fa-pen"></i> Compose
            </button>
            <ul class="email-folders">
                <li><a href="#" class="active" data-folder="inbox"><i class="fas fa-inbox"></i> Inbox <span class="badge">{{ $messages->total() }}</span></a></li>
                <li><a href="#" data-folder="sent"><i class="fas fa-paper-plane"></i> Sent <span class="badge">0</span></a></li>
                <li><a href="#" data-folder="drafts"><i class="fas fa-file-alt"></i> Drafts <span class="badge">0</span></a></li>
                <li><a href="#" data-folder="trash"><i class="fas fa-trash"></i> Trash</a></li>
            </ul>
        </aside>
        
        <div class="email-main">
            <div class="email-toolbar">
                <div class="search-box search-mini" id="message-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search messages..." data-message-search>
                </div>
                <a href="{{ route('admin.messages') }}" class="action-btn edit-btn" title="Refresh"><i class="fas fa-sync-alt"></i></a>
                <button class="action-btn edit-btn" title="Filter"><i class="fas fa-filter"></i></button>
            </div>
            
            <div class="email-message-list" id="email-message-list">
                <div class="email-folder-pane active" data-folder-pane="inbox">
                    @forelse($messages as $message)
                    <div class="email-message-row {{ $message->read_at ? '' : 'unread' }}" 
                         data-from="{{ $message->email }}" 
                         data-subject="{{ $message->subject }}" 
                         data-date="{{ $message->created_at->format('M j, Y g:i A') }}" 
                         data-body="{{ e($message->message) }}"
                         data-message-id="{{ $message->id }}">
                        <div class="col-from">
                            <span class="col-from-name">{{ $message->name }}</span>
                            <span class="col-from-email">{{ $message->email }}</span>
                        </div>
                        <span class="col-subject">{{ $message->subject }}</span>
                        <span class="col-date">{{ $message->created_at->format('M j, Y') }}</span>
                    </div>
                    @empty
                    <div class="email-empty-inbox">
                        <i class="fas fa-inbox"></i>
                        <p>No messages yet. Messages from your contact form will appear here.</p>
                    </div>
                    @endforelse
                </div>
                <div class="email-folder-pane" data-folder-pane="sent">
                    <div class="email-empty-inbox">
                        <i class="fas fa-paper-plane"></i>
                        <p>No sent messages.</p>
                    </div>
                </div>
                <div class="email-folder-pane" data-folder-pane="drafts">
                    <div class="email-empty-inbox">
                        <i class="fas fa-file-alt"></i>
                        <p>No drafts.</p>
                    </div>
                </div>
                <div class="email-folder-pane" data-folder-pane="trash">
                    <div class="email-empty-inbox">
                        <i class="fas fa-trash"></i>
                        <p>Trash is empty.</p>
                    </div>
                </div>
            </div>
            
            
            
            
            @if(method_exists($messages, 'hasPages') && $messages->hasPages())
                <div class="pagination-wrapper">
                    {{ $messages->links('admin.pagination') }}
                </div>
            @endif
            <div class="email-empty-state" id="email-empty-state">
                <i class="fas fa-envelope-open"></i>
                <p>Click a message to open it</p>
            </div>

            <div class="email-view-fullscreen" id="email-view-fullscreen">
                <div class="email-view-header-bar">
                    <button type="button" class="email-back-btn" id="email-back-btn" aria-label="Back to messages">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <span class="email-view-context" id="email-view-context">Inbox</span>
                </div>
                <div class="email-view-content">
                    <h2 class="email-view-subject" id="email-view-subject">Message Subject</h2>
                    <div class="email-view-meta-row">
                        <span><strong id="email-view-from-label">From:</strong> <span id="email-view-from">-</span></span>
                        <span><strong>Date:</strong> <span id="email-view-date">-</span></span>
                    </div>
                    <div class="email-view-body" id="email-view-body">Message content will appear here.</div>
                </div>
                <div class="email-view-actions-bar">
                    <div class="email-view-actions">
                        <a id="email-reply-link" href="#" class="action-btn edit-btn" data-reply-trigger><i class="fas fa-reply"></i> Reply</a>
                        <button type="button" class="action-btn delete-btn" id="email-delete-btn" data-modal-open="delete-confirm-modal" data-delete-url="" data-delete-name="this message"><i class="fas fa-trash"></i> Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('modals')
<div class="modal-overlay" id="compose-message-modal" data-modal>
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3><i class="fas fa-paper-plane" style="margin-right: 8px;"></i> Compose Message</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form class="compose-form">
            <div class="modal-body">
                <div class="form-group">
                    <label for="compose-to">To</label>
                    <input type="email" id="compose-to" placeholder="recipient@example.com" required>
                </div>
                <div class="form-group">
                    <label for="compose-cc">Cc <span class="text-muted">(optional)</span></label>
                    <input type="text" id="compose-cc" placeholder="cc@example.com">
                </div>
                <div class="form-group">
                    <label for="compose-subject">Subject</label>
                    <input type="text" id="compose-subject" placeholder="Enter subject" required>
                </div>
                <div class="form-group">
                    <label for="compose-body">Message</label>
                    <textarea id="compose-body" rows="10" placeholder="Write your message here..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send Message</button>
            </div>
        </form>
    </div>
</div>
@endpush

@endsection
