@if ($paginator->hasPages())
    <div class="pagination-container">
        <div class="pagination-info">
            Showing page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
        </div>

        <div class="pagination-actions">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button class="btn btn-secondary pagination-btn" disabled >
                    <i class="fas fa-chevron-left" ></i> Previous
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-secondary pagination-btn" >
                    <i class="fas fa-chevron-left" ></i> Previous
                </a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-secondary pagination-btn" >
                    Next <i class="fas fa-chevron-right" ></i>
                </a>
            @else
                <button class="btn btn-secondary pagination-btn" disabled >
                    Next <i class="fas fa-chevron-right" ></i>
                </button>
            @endif
        </div>
    </div>
@endif


