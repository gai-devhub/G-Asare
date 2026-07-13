@extends('admin.admin')

@section('title', 'View Message')

@section('content')
<div class="content-section active">
    <div class="page-header" style="display: flex; flex-direction: column; align-items: flex-start; gap: 0.5rem;">
        <a href="{{ route('admin.messages') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
        <h1><i class="fas fa-envelope-open-text" style="margin-right: 12px; color: var(--color-primary);"></i>Message from {{ $contactMessage->name }}</h1>
    </div>
    
    <div class="chart-card">
        <div class="message-view">
            <div class="message-meta">
                <p><strong>From:</strong> {{ $contactMessage->name }} &lt;{{ $contactMessage->email }}&gt;</p>
                <p><strong>Subject:</strong> {{ $contactMessage->subject }}</p>
                <p><strong>Date:</strong> {{ $contactMessage->created_at->format('F j, Y \a\t g:i A') }}</p>
            </div>
            <div class="message-body">
                {!! nl2br(e($contactMessage->message)) !!}
            </div>
            <div class="message-actions mt-3">
                <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ $contactMessage->subject }}" class="btn btn-primary"><i class="fas fa-reply"></i> Reply</a>
                <button type="button" class="btn btn-secondary action-btn delete-btn" data-modal-open="delete-confirm-modal" data-delete-url="{{ route('admin.messages.destroy', $contactMessage) }}" data-delete-name="{{ $contactMessage->subject }}"><i class="fas fa-trash"></i> Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection
