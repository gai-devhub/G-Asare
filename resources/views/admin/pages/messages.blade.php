@extends('admin.admin')

@section('title', 'Messages')

@push('topbar-add')
<button type="button" class="gm-compose-fab" id="topbar-compose-btn" data-modal-open="compose-message-modal">
    <i class="fas fa-pen"></i> <span>Compose</span>
</button>
@endpush

@section('content')
<style>
/* =============================================
   Gmail-Style Messages — Scoped to .gm-shell
   ============================================= */

/* Reset content-wrapper padding for full-bleed layout */
.content-wrapper { padding: 0 !important; height: 100%; }
.content-section.active { height: 100%; display: flex; flex-direction: column; }

/* Shell — the full Gmail canvas */
.gm-shell {
    display: flex;
    height: calc(100vh - 64px); /* minus topbar */
    background: var(--bg-body);
    overflow: hidden;
    position: relative;
}

/* ── Sidebar ── */
.gm-sidebar {
    width: 256px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    padding: 8px 0;
    background: var(--bg-body);
    overflow-y: auto;
    transition: width 0.2s ease, transform 0.3s ease;
}

.gm-compose-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 4px 16px 8px;
    padding: 18px 24px;
    background: var(--bg-surface);
    border: none;
    border-radius: 16px;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-text);
    cursor: pointer;
    box-shadow: var(--shadow-sm);
    transition: box-shadow 0.2s, background 0.2s;
    text-align: left;
}
.gm-compose-btn:hover {
    box-shadow: var(--shadow-md);
    background: var(--bg-surface-hover);
}
.gm-compose-btn i { font-size: 1rem; color: var(--color-text-muted); }

.gm-folder-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.gm-folder-list li a {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 0 28px 0 16px;
    height: 32px;
    border-radius: 0 16px 16px 0;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-text-muted);
    transition: background 0.15s;
    cursor: pointer;
    margin-bottom: 2px;
}
.gm-folder-list li a:hover { background: var(--bg-surface-hover); }
.gm-folder-list li a.gm-folder-active {
    background: var(--color-primary-active);
    color: var(--color-text);
    font-weight: 600;
}
.gm-folder-list li a i { width: 20px; text-align: center; font-size: 1rem; }
.gm-badge {
    margin-left: auto;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--color-text);
}
.gm-badge:empty { display: none; }

/* ── Main Panel ── */
.gm-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
    border-left: 1px solid var(--border-color);
}

/* ── Toolbar ── */
.gm-toolbar {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-bottom: 1px solid var(--border-color);
    background: var(--bg-body);
    flex-shrink: 0;
    flex-wrap: wrap;
}
.gm-search {
    flex: 1;
    min-width: 0;
    display: flex;
    align-items: center;
    background: var(--bg-search);
    border-radius: 24px;
    padding: 0 16px;
    height: 46px;
    transition: background 0.2s, box-shadow 0.2s;
}
.gm-search:focus-within {
    background: var(--bg-surface);
    box-shadow: var(--shadow-sm);
}
.gm-search i { color: var(--color-text-muted); margin-right: 12px; }
.gm-search input {
    border: none;
    background: transparent;
    width: 100%;
    font-size: 0.9375rem;
    color: var(--color-text);
    outline: none;
}
.gm-icon-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: transparent;
    color: var(--color-text-muted);
    font-size: 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.15s;
    flex-shrink: 0;
}
.gm-icon-btn:hover { background: var(--bg-surface-hover); color: var(--color-text); }

/* ── Message List ── */
.gm-list-panel {
    flex: 1;
    overflow-y: auto;
    position: relative;
}
.gm-folder-pane { display: none; }
.gm-folder-pane.active { display: block; }

.gm-msg-row {
    display: grid;
    grid-template-columns: 48px 1fr auto;
    align-items: center;
    gap: 0;
    padding: 0 8px 0 4px;
    height: 52px;
    border-bottom: 1px solid var(--border-color);
    cursor: pointer;
    background: var(--bg-surface);
    transition: background 0.12s, box-shadow 0.12s;
    position: relative;
}
.gm-msg-row:hover { background: var(--bg-surface-hover); box-shadow: var(--shadow-sm); z-index: 1; }
.gm-msg-row.unread { background: var(--bg-body); font-weight: 600; }
.gm-msg-row.unread .gm-msg-from,
.gm-msg-row.unread .gm-msg-subject { font-weight: 700; color: var(--color-text); }

.gm-msg-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4285f4, #34a853);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8125rem;
    font-weight: 600;
    flex-shrink: 0;
    margin: 0 8px 0 4px;
    text-transform: uppercase;
}

.gm-msg-body {
    min-width: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 2px;
    padding: 0 8px;
}
.gm-msg-from {
    font-size: 0.875rem;
    color: var(--color-text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.gm-msg-subject-row {
    display: flex;
    align-items: center;
    gap: 6px;
    min-width: 0;
}
.gm-msg-subject {
    font-size: 0.8125rem;
    color: var(--color-text-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.gm-msg-preview {
    font-size: 0.8125rem;
    color: var(--color-text-muted);
    font-weight: 400;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.gm-msg-date {
    font-size: 0.75rem;
    color: var(--color-text-muted);
    white-space: nowrap;
    padding-left: 8px;
    flex-shrink: 0;
}
.gm-msg-row.unread .gm-msg-date { font-weight: 700; color: var(--color-text); }

/* Empty States */
.gm-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 80px 24px;
    text-align: center;
    gap: 16px;
}
.gm-empty i {
    font-size: 4rem;
    color: var(--color-primary);
    opacity: 0.35;
}
.gm-empty p {
    font-size: 0.9375rem;
    color: var(--color-text-muted);
    max-width: 320px;
    line-height: 1.6;
}

/* ── Email Detail View ── */
.gm-detail-panel {
    position: absolute;
    inset: 0;
    background: var(--bg-body);
    z-index: 20;
    display: none;
    flex-direction: column;
    overflow: hidden;
}
.gm-detail-panel.open { display: flex; }

.gm-detail-topbar {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-bottom: 1px solid var(--border-color);
    background: var(--bg-body);
    flex-shrink: 0;
}
.gm-back-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px 6px 6px;
    border: none;
    background: transparent;
    color: var(--color-text-muted);
    font-size: 0.875rem;
    border-radius: 20px;
    cursor: pointer;
    font-weight: 500;
    transition: background 0.15s;
}
.gm-back-btn:hover { background: var(--bg-surface-hover); color: var(--color-text); }
.gm-back-btn i { font-size: 1.125rem; }

.gm-detail-context {
    font-size: 0.8125rem;
    color: var(--color-text-muted);
    margin-left: 4px;
}

.gm-detail-actions {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-left: auto;
}

.gm-detail-content {
    flex: 1;
    overflow-y: auto;
    padding: 24px 32px;
    max-width: 900px;
    width: 100%;
    margin: 0 auto;
}

.gm-detail-subject {
    font-size: 1.5rem;
    font-weight: 400;
    color: var(--color-text);
    margin-bottom: 20px;
    line-height: 1.3;
}

.gm-detail-meta {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border-color);
}
.gm-detail-meta-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4285f4, #34a853);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    font-weight: 600;
    flex-shrink: 0;
    text-transform: uppercase;
}
.gm-detail-meta-info { flex: 1; min-width: 0; }
.gm-detail-meta-name {
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--color-text);
}
.gm-detail-meta-email {
    font-size: 0.8125rem;
    color: var(--color-text-muted);
}
.gm-detail-meta-date {
    font-size: 0.8125rem;
    color: var(--color-text-muted);
    white-space: nowrap;
}

.gm-detail-body {
    font-size: 0.9375rem;
    color: var(--color-text);
    line-height: 1.75;
    white-space: pre-wrap;
    word-break: break-word;
}

.gm-detail-reply-bar {
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 16px 20px;
    margin-top: 32px;
    background: var(--bg-surface);
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: box-shadow 0.15s;
}
.gm-detail-reply-bar:hover { box-shadow: var(--shadow-sm); }
.gm-detail-reply-bar span {
    color: var(--color-text-muted);
    font-size: 0.9375rem;
}

/* ── Unread dot ── */
.gm-unread-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--color-primary);
    flex-shrink: 0;
}

/* ── Action buttons ── */
.gm-action-reply {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 20px;
    border: 1px solid var(--border-color);
    background: var(--bg-surface);
    color: var(--color-text);
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: background 0.15s, box-shadow 0.15s;
}
.gm-action-reply:hover { background: var(--bg-surface-hover); box-shadow: var(--shadow-sm); }

.gm-action-delete {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 20px;
    border: none;
    background: transparent;
    color: var(--color-text-muted);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.15s, color 0.15s;
}
.gm-action-delete:hover { background: var(--bg-danger, #fce8e6); color: var(--color-danger, #a50e0e); }

/* ── Compose FAB (mobile) ── */
.gm-compose-fab {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0 20px;
    height: 36px;
    border-radius: 18px;
    border: none;
    background: var(--color-primary);
    color: #fff;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.15s, box-shadow 0.15s;
    box-shadow: var(--shadow-sm);
}
.gm-compose-fab:hover { background: #0842a0; box-shadow: var(--shadow-md); }

/* ── Mobile Sidebar Overlay ── */
.gm-sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.35);
    z-index: 100;
}
.gm-sidebar-overlay.open { display: block; }

/* ── Responsive ── */
@media (max-width: 900px) {
    .gm-sidebar {
        position: fixed;
        top: 64px;
        left: 0;
        bottom: 0;
        z-index: 200;
        transform: translateX(-100%);
        box-shadow: var(--shadow-md);
        background: var(--bg-surface);
    }
    .gm-sidebar.mobile-open { transform: translateX(0); }
    .gm-shell { height: calc(100vh - 64px); }
    .gm-main { border-left: none; }
}

@media (max-width: 600px) {
    .gm-toolbar { padding: 6px 8px; gap: 4px; }
    .gm-search { height: 40px; padding: 0 12px; }
    .gm-detail-content { padding: 16px 12px; }
    .gm-detail-subject { font-size: 1.125rem; }
    .gm-msg-row { height: 64px; grid-template-columns: 44px 1fr auto; }
    .gm-msg-body { flex-direction: column; }
    .gm-msg-subject-row { flex-wrap: wrap; }
    .gm-msg-date { font-size: 0.6875rem; }
    .gm-compose-btn { margin: 4px 8px 8px; padding: 14px 16px; }
}

/* Pagination */
.gm-pagination { padding: 8px 16px; border-top: 1px solid var(--border-color); }
</style>

<div class="content-section active">
    {{-- Mobile Sidebar Toggle (only shown on small screens) --}}
    <div class="gm-sidebar-overlay" id="gm-sidebar-overlay"></div>

    <div class="gm-shell" id="gm-shell">

        {{-- ═══════════════════════════════════
             SIDEBAR
        ═══════════════════════════════════ --}}
        <aside class="gm-sidebar" id="gm-sidebar">
            <button type="button" class="gm-compose-btn" data-modal-open="compose-message-modal">
                <i class="fas fa-pen-to-square"></i> Compose
            </button>

            <ul class="gm-folder-list">
                <li>
                    <a href="#" class="gm-folder-active" data-folder="inbox" id="folder-inbox">
                        <i class="fas fa-inbox"></i>
                        Inbox
                        <span class="gm-badge" id="inbox-badge">{{ $messages->total() > 0 ? $messages->total() : '' }}</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-folder="sent" id="folder-sent">
                        <i class="fas fa-paper-plane"></i>
                        Sent
                    </a>
                </li>
                <li>
                    <a href="#" data-folder="drafts" id="folder-drafts">
                        <i class="fas fa-file-alt"></i>
                        Drafts
                    </a>
                </li>
                <li>
                    <a href="#" data-folder="trash" id="folder-trash">
                        <i class="fas fa-trash-alt"></i>
                        Trash
                    </a>
                </li>
            </ul>
        </aside>

        {{-- ═══════════════════════════════════
             MAIN PANEL
        ═══════════════════════════════════ --}}
        <div class="gm-main">

            {{-- Toolbar --}}
            <div class="gm-toolbar">
                {{-- Mobile hamburger --}}
                <button class="gm-icon-btn" id="gm-menu-toggle" title="Menu" style="display:none;">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="gm-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search in mail" id="gm-search-input" data-message-search>
                </div>
                <a href="{{ route('admin.messages') }}" class="gm-icon-btn" title="Refresh">
                    <i class="fas fa-rotate-right"></i>
                </a>
                <button class="gm-icon-btn" title="More options">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>

            {{-- Message List + Detail (stacked, detail overlays) --}}
            <div class="gm-list-panel" id="gm-list-panel">

                {{-- INBOX --}}
                <div class="gm-folder-pane active" data-folder-pane="inbox">
                    @forelse($messages as $message)
                        <div class="gm-msg-row {{ $message->read_at ? '' : 'unread' }}"
                             data-from="{{ $message->name }}"
                             data-email="{{ $message->email }}"
                             data-subject="{{ $message->subject }}"
                             data-date="{{ $message->created_at->format('M j, Y \a\t g:i A') }}"
                             data-body="{{ e($message->message) }}"
                             data-message-id="{{ $message->id }}">

                            <div class="gm-msg-avatar">{{ strtoupper(substr($message->name, 0, 1)) }}</div>

                            <div class="gm-msg-body">
                                <span class="gm-msg-from">{{ $message->name }}</span>
                                <div class="gm-msg-subject-row">
                                    @if(!$message->read_at)
                                        <span class="gm-unread-dot"></span>
                                    @endif
                                    <span class="gm-msg-subject">{{ $message->subject }}</span>
                                    <span class="gm-msg-preview"> — {{ Str::limit($message->message, 60) }}</span>
                                </div>
                            </div>

                            <span class="gm-msg-date">
                                @if($message->created_at->isToday())
                                    {{ $message->created_at->format('g:i A') }}
                                @elseif($message->created_at->year === now()->year)
                                    {{ $message->created_at->format('M j') }}
                                @else
                                    {{ $message->created_at->format('M j, Y') }}
                                @endif
                            </span>
                        </div>
                    @empty
                        <div class="gm-empty">
                            <i class="fas fa-inbox"></i>
                            <p>Your inbox is empty. Messages from your contact form will appear here.</p>
                        </div>
                    @endforelse

                    @if(method_exists($messages, 'hasPages') && $messages->hasPages())
                        <div class="gm-pagination">
                            {{ $messages->links('admin.pagination') }}
                        </div>
                    @endif
                </div>

                {{-- SENT --}}
                <div class="gm-folder-pane" data-folder-pane="sent">
                    <div class="gm-empty">
                        <i class="fas fa-paper-plane"></i>
                        <p>No sent messages yet.</p>
                    </div>
                </div>

                {{-- DRAFTS --}}
                <div class="gm-folder-pane" data-folder-pane="drafts">
                    <div class="gm-empty">
                        <i class="fas fa-file-alt"></i>
                        <p>No drafts saved.</p>
                    </div>
                </div>

                {{-- TRASH --}}
                <div class="gm-folder-pane" data-folder-pane="trash">
                    <div class="gm-empty">
                        <i class="fas fa-trash-alt"></i>
                        <p>Trash is empty.</p>
                    </div>
                </div>

                {{-- ── EMAIL DETAIL OVERLAY ── --}}
                <div class="gm-detail-panel" id="gm-detail-panel">
                    <div class="gm-detail-topbar">
                        <button class="gm-back-btn" id="gm-back-btn" type="button">
                            <i class="fas fa-arrow-left"></i> Inbox
                        </button>
                        <span class="gm-detail-context" id="gm-detail-context"></span>
                        <div class="gm-detail-actions">
                            <button class="gm-icon-btn" title="Archive"><i class="fas fa-archive"></i></button>
                            <button class="gm-icon-btn" id="gm-delete-btn" type="button"
                                    data-modal-open="delete-confirm-modal"
                                    data-delete-url=""
                                    data-delete-name="this message"
                                    title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <button class="gm-icon-btn" title="Mark unread"><i class="fas fa-envelope"></i></button>
                        </div>
                    </div>

                    <div class="gm-detail-content">
                        <h2 class="gm-detail-subject" id="gm-detail-subject"></h2>

                        <div class="gm-detail-meta">
                            <div class="gm-detail-meta-avatar" id="gm-detail-avatar"></div>
                            <div class="gm-detail-meta-info">
                                <div class="gm-detail-meta-name" id="gm-detail-from-name"></div>
                                <div class="gm-detail-meta-email" id="gm-detail-from-email"></div>
                            </div>
                            <div class="gm-detail-meta-date" id="gm-detail-date"></div>
                        </div>

                        <div class="gm-detail-body" id="gm-detail-body"></div>

                        <div class="gm-detail-reply-bar" style="margin-top:32px;">
                            <i class="fas fa-reply" style="color:var(--color-text-muted);"></i>
                            <span>Click here to reply</span>
                            <a id="gm-reply-link" href="#" class="gm-action-reply" style="margin-left:auto;" data-reply-trigger>
                                <i class="fas fa-reply"></i> Reply
                            </a>
                        </div>
                    </div>
                </div>
                {{-- ── /EMAIL DETAIL OVERLAY ── --}}

            </div>
            {{-- /gm-list-panel --}}
        </div>
        {{-- /gm-main --}}

    </div>
    {{-- /gm-shell --}}
</div>

@push('modals')
{{-- Compose Modal --}}
<div class="modal-overlay" id="compose-message-modal" data-modal>
    <div class="modal modal-lg" style="max-width:600px; width:100%;">
        <div class="modal-header" style="border-bottom:1px solid var(--border-color); padding:16px 20px;">
            <h3 style="font-size:1rem; font-weight:500;"><i class="fas fa-pen" style="color:var(--color-text-muted); margin-right:8px;"></i> New Message</h3>
            <button type="button" class="modal-close" data-modal-close aria-label="Close"><i class="fas fa-times"></i></button>
        </div>
        <form class="compose-form">
            <div class="modal-body" style="padding:0;">
                <div style="border-bottom:1px solid var(--border-color); padding:10px 20px; display:flex; align-items:center; gap:12px;">
                    <label for="compose-to" style="width:36px; font-size:0.875rem; color:var(--color-text-muted); font-weight:500;">To</label>
                    <input type="email" id="compose-to" placeholder="" required
                           style="flex:1; border:none; outline:none; background:transparent; font-size:0.875rem; color:var(--color-text);">
                </div>
                <div style="border-bottom:1px solid var(--border-color); padding:10px 20px; display:flex; align-items:center; gap:12px;">
                    <label for="compose-cc" style="width:36px; font-size:0.875rem; color:var(--color-text-muted); font-weight:500;">Cc</label>
                    <input type="text" id="compose-cc" placeholder=""
                           style="flex:1; border:none; outline:none; background:transparent; font-size:0.875rem; color:var(--color-text);">
                </div>
                <div style="border-bottom:1px solid var(--border-color); padding:10px 20px; display:flex; align-items:center; gap:12px;">
                    <label for="compose-subject" style="width:36px; font-size:0.875rem; color:var(--color-text-muted); font-weight:500;">Sub</label>
                    <input type="text" id="compose-subject" placeholder="Subject" required
                           style="flex:1; border:none; outline:none; background:transparent; font-size:0.875rem; color:var(--color-text);">
                </div>
                <div style="padding:16px 20px; min-height:200px;">
                    <textarea id="compose-body" rows="10" placeholder="Write your message here..."
                              style="width:100%; border:none; outline:none; background:transparent; font-size:0.875rem; color:var(--color-text); resize:vertical; min-height:200px; font-family:inherit;"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid var(--border-color); padding:12px 20px; display:flex; align-items:center; gap:8px;">
                <button type="submit" class="btn btn-primary" style="border-radius:20px; padding:8px 20px;">
                    <i class="fas fa-paper-plane"></i> Send
                </button>
                <button type="button" style="background:transparent; border:none; color:var(--color-text-muted); font-size:1rem; width:36px; height:36px; border-radius:50%; cursor:pointer; display:flex; align-items:center; justify-content:center;" title="Formatting">
                    <i class="fas fa-text-height"></i>
                </button>
                <button type="button" style="background:transparent; border:none; color:var(--color-text-muted); font-size:1rem; width:36px; height:36px; border-radius:50%; cursor:pointer; display:flex; align-items:center; justify-content:center;" title="Attach">
                    <i class="fas fa-paperclip"></i>
                </button>
                <button type="button" class="btn btn-secondary" data-modal-close style="margin-left:auto; border-radius:20px;">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

<script>
(function () {
    /* ── Folder switching ── */
    const folders = document.querySelectorAll('[data-folder]');
    const panes   = document.querySelectorAll('[data-folder-pane]');

    folders.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = this.dataset.folder;
            folders.forEach(function(l) { l.classList.remove('gm-folder-active'); });
            this.classList.add('gm-folder-active');
            panes.forEach(function(p) {
                p.classList.toggle('active', p.dataset.folderPane === target);
            });
            closeDetail();
            // Close sidebar on mobile
            if (window.innerWidth <= 900) closeSidebar();
        });
    });

    /* ── Mobile sidebar ── */
    const sidebar      = document.getElementById('gm-sidebar');
    const overlay      = document.getElementById('gm-sidebar-overlay');
    const menuToggle   = document.getElementById('gm-menu-toggle');

    function openSidebar()  { sidebar.classList.add('mobile-open'); overlay.classList.add('open'); }
    function closeSidebar() { sidebar.classList.remove('mobile-open'); overlay.classList.remove('open'); }

    function applyResponsive() {
        if (window.innerWidth <= 900) {
            menuToggle.style.display = '';
        } else {
            menuToggle.style.display = 'none';
            closeSidebar();
        }
    }
    applyResponsive();
    window.addEventListener('resize', applyResponsive);

    if (menuToggle)  menuToggle.addEventListener('click', openSidebar);
    if (overlay)     overlay.addEventListener('click', closeSidebar);

    /* ── Email detail ── */
    const detailPanel = document.getElementById('gm-detail-panel');
    const backBtn     = document.getElementById('gm-back-btn');

    function openDetail(row) {
        const name    = row.dataset.from    || '';
        const email   = row.dataset.email   || '';
        const subject = row.dataset.subject || '(no subject)';
        const date    = row.dataset.date    || '';
        const body    = row.dataset.body    || '';
        const msgId   = row.dataset.messageId || '';

        document.getElementById('gm-detail-subject').textContent  = subject;
        document.getElementById('gm-detail-avatar').textContent    = name.charAt(0).toUpperCase();
        document.getElementById('gm-detail-from-name').textContent = name;
        document.getElementById('gm-detail-from-email').textContent = email;
        document.getElementById('gm-detail-date').textContent      = date;
        document.getElementById('gm-detail-body').textContent      = body;

        // Reply link
        const replyLink = document.getElementById('gm-reply-link');
        if (replyLink) replyLink.href = 'mailto:' + email + '?subject=Re: ' + encodeURIComponent(subject);

        // Delete btn
        const deleteBtn = document.getElementById('gm-delete-btn');
        if (deleteBtn && msgId) {
            deleteBtn.dataset.deleteUrl = '{{ url("/admin/messages") }}/' + msgId;
            deleteBtn.dataset.deleteName = subject;
        }

        detailPanel.classList.add('open');
        row.classList.remove('unread');

        // Mark as read via fetch
        if (msgId) {
            fetch('{{ url("/admin/messages") }}/' + msgId + '/read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            }).catch(function() {});
        }
    }

    function closeDetail() {
        detailPanel.classList.remove('open');
    }

    document.querySelectorAll('.gm-msg-row').forEach(function(row) {
        row.addEventListener('click', function() { openDetail(this); });
    });

    if (backBtn) backBtn.addEventListener('click', closeDetail);

    /* ── Search ── */
    const searchInput = document.getElementById('gm-search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.gm-msg-row').forEach(function(row) {
                const text = (row.dataset.from + ' ' + row.dataset.subject + ' ' + row.dataset.body).toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
            });
        });
    }
})();
</script>
@endsection
