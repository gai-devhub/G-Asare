@if ($paginator->hasPages())
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.5rem; border-top: 1px solid rgba(255, 255, 255, 0.05); width: 100%;">
        <div style="color: var(--gray); font-size: 0.85rem;">
            Showing page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
        </div>

        <div style="display: flex; gap: 0.5rem;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button class="action-btn edit-btn" disabled style="opacity: 0.5; cursor: not-allowed; padding: 0.35rem 0.75rem; border-radius: 6px; font-size: 0.85rem;">
                    <i class="fas fa-chevron-left" style="margin-right: 4px;"></i> Previous
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="action-btn edit-btn" style="text-decoration: none; display: inline-flex; align-items: center; padding: 0.35rem 0.75rem; border-radius: 6px; font-size: 0.85rem;">
                    <i class="fas fa-chevron-left" style="margin-right: 4px;"></i> Previous
                </a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="action-btn edit-btn" style="text-decoration: none; display: inline-flex; align-items: center; padding: 0.35rem 0.75rem; border-radius: 6px; font-size: 0.85rem;">
                    Next <i class="fas fa-chevron-right" style="margin-left: 4px;"></i>
                </a>
            @else
                <button class="action-btn edit-btn" disabled style="opacity: 0.5; cursor: not-allowed; padding: 0.35rem 0.75rem; border-radius: 6px; font-size: 0.85rem;">
                    Next <i class="fas fa-chevron-right" style="margin-left: 4px;"></i>
                </button>
            @endif
        </div>
    </div>
@endif
