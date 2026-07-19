@extends('admin.admin')

@section('title', 'Search')

@section('content')
<div class="content-section active">
    <style>
        /* ── Search Page ── */
        .search-page {
            max-width: 860px;
            margin: 0 auto;
            padding: 0 0 3rem;
        }

        /* Hero search bar */
        .search-page-bar {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--bg-surface);
            border: 1.5px solid var(--border-color);
            border-radius: 50px;
            padding: 0.65rem 1.25rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .search-page-bar:focus-within {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(var(--color-primary-rgb, 99,102,241), 0.12);
        }
        .search-page-bar i {
            font-size: 1.1rem;
            color: var(--color-text-muted);
            flex-shrink: 0;
        }
        .search-page-bar input {
            flex: 1;
            border: none;
            background: transparent;
            font-size: 1.05rem;
            color: var(--color-text);
            outline: none;
        }
        .search-page-bar input::placeholder {
            color: var(--color-text-muted);
        }
        #search-clear-btn {
            background: none;
            border: none;
            color: var(--color-text-muted);
            cursor: pointer;
            font-size: 0.9rem;
            padding: 0.25rem;
            border-radius: 50%;
            display: none;
            transition: color 0.2s;
        }
        #search-clear-btn:hover { color: var(--color-text); }
        #search-clear-btn.visible { display: inline-flex; align-items: center; justify-content: center; }

        /* State indicators */
        .search-state {
            text-align: center;
            padding: 4rem 1rem;
            color: var(--color-text-muted);
        }
        .search-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
            opacity: 0.35;
        }
        .search-state p { font-size: 0.95rem; }

        /* Spinner */
        .search-spinner {
            display: none;
            text-align: center;
            padding: 3rem;
        }
        .search-spinner.active { display: block; }
        .spinner-ring {
            width: 40px;
            height: 40px;
            border: 3px solid var(--border-color);
            border-top-color: var(--color-primary);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Summary bar */
        .search-summary {
            display: none;
            font-size: 0.82rem;
            color: var(--color-text-muted);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border-color);
        }
        .search-summary.visible { display: block; }
        .search-summary strong { color: var(--color-text); }

        /* Result groups */
        .result-groups { display: flex; flex-direction: column; gap: 1.5rem; }

        .result-group {}

        .result-group-header {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 0.6rem;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--color-text-muted);
        }
        .result-group-header .group-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .result-items {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .result-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.85rem 1.1rem;
            border-radius: 12px;
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            text-decoration: none;
            transition: background 0.15s, border-color 0.15s, transform 0.1s;
            cursor: pointer;
        }
        .result-item:hover {
            background: var(--bg-surface-hover);
            border-color: var(--color-primary);
            transform: translateX(3px);
        }

        .result-item-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            color: white;
            flex-shrink: 0;
        }

        .result-item-text {
            flex: 1;
            min-width: 0;
        }
        .result-item-title {
            font-size: 0.92rem;
            font-weight: 500;
            color: var(--color-text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .result-item-subtitle {
            font-size: 0.78rem;
            color: var(--color-text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 1px;
        }

        .result-item-arrow {
            color: var(--color-text-muted);
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        /* No results */
        .no-results {
            display: none;
            text-align: center;
            padding: 4rem 1rem;
            color: var(--color-text-muted);
        }
        .no-results i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
            opacity: 0.3;
        }
        .no-results p { font-size: 0.95rem; }
        .no-results strong { color: var(--color-text); }

        /* Mobile tweak */
        @media (max-width: 640px) {
            .search-page-bar { padding: 0.55rem 1rem; }
            .search-page-bar input { font-size: 0.95rem; }
        }
    </style>

    <div class="search-page">
        {{-- Big search bar --}}
        <div class="search-page-bar">
            <i class="fas fa-search"></i>
            <input
                type="text"
                id="global-search-input"
                placeholder="Search projects, posts, messages, skills…"
                autocomplete="off"
                autofocus
                value="{{ request('q') }}"
            >
            <button id="search-clear-btn" title="Clear">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Spinner --}}
        <div class="search-spinner" id="search-spinner">
            <div class="spinner-ring"></div>
        </div>

        {{-- Summary --}}
        <div class="search-summary" id="search-summary"></div>

        {{-- Default state (empty input) --}}
        <div class="search-state" id="search-empty-state">
            <i class="fas fa-search"></i>
            <p>Start typing to search across all your content</p>
        </div>

        {{-- No results --}}
        <div class="no-results" id="search-no-results">
            <i class="fas fa-face-meh"></i>
            <p>No results for <strong id="search-no-results-query"></strong></p>
        </div>

        {{-- Results --}}
        <div class="result-groups" id="search-results-container"></div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const input       = document.getElementById('global-search-input');
    const clearBtn    = document.getElementById('search-clear-btn');
    const spinner     = document.getElementById('search-spinner');
    const summary     = document.getElementById('search-summary');
    const emptyState  = document.getElementById('search-empty-state');
    const noResults   = document.getElementById('search-no-results');
    const noResultsQ  = document.getElementById('search-no-results-query');
    const container   = document.getElementById('search-results-container');

    const SEARCH_URL  = '{{ route("admin.search") }}';
    let debounceTimer = null;
    let currentQuery  = '';

    function showOnly(el) {
        [spinner, summary, emptyState, noResults, container].forEach(e => {
            e.classList.remove('active', 'visible');
            e.style.display = 'none';
        });
        if (el) {
            el.style.display = '';
            el.classList.add(el === summary ? 'visible' : 'active');
        }
    }

    function iconBg(color) {
        return `background:${color};`;
    }

    function renderResults(data) {
        const { results, total, query } = data;

        if (total === 0) {
            noResultsQ.textContent = `"${query}"`;
            noResults.style.display = '';
            summary.style.display   = 'none';
            container.innerHTML     = '';
            return;
        }

        summary.innerHTML    = `Found <strong>${total}</strong> result${total !== 1 ? 's' : ''} for <strong>"${query}"</strong>`;
        summary.style.display = '';
        noResults.style.display = 'none';

        container.innerHTML = results.map(group => `
            <div class="result-group">
                <div class="result-group-header">
                    <span class="group-dot" style="background:${group.color}"></span>
                    <span>${group.group}</span>
                    <span style="margin-left:auto;opacity:.6">${group.items.length}</span>
                </div>
                <div class="result-items">
                    ${group.items.map(item => `
                        <a href="${item.url}" class="result-item">
                            <div class="result-item-icon" style="${iconBg(group.color)}">
                                <i class="${item.icon}"></i>
                            </div>
                            <div class="result-item-text">
                                <div class="result-item-title">${item.title}</div>
                                ${item.subtitle ? `<div class="result-item-subtitle">${item.subtitle}</div>` : ''}
                            </div>
                            <i class="fas fa-arrow-right result-item-arrow"></i>
                        </a>
                    `).join('')}
                </div>
            </div>
        `).join('');
    }

    async function doSearch(q) {
        if (q.length < 2) {
            showOnly(emptyState);
            emptyState.style.display = '';
            return;
        }

        showOnly(spinner);
        spinner.style.display = '';

        try {
            const res  = await fetch(`${SEARCH_URL}?q=${encodeURIComponent(q)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            showOnly(null);
            renderResults(data);
        } catch (err) {
            showOnly(emptyState);
            emptyState.querySelector('p').textContent = 'Something went wrong. Please try again.';
        }
    }

    input.addEventListener('input', function () {
        const q = this.value.trim();
        currentQuery = q;

        clearBtn.classList.toggle('visible', q.length > 0);

        clearTimeout(debounceTimer);

        if (q.length < 2) {
            showOnly(emptyState);
            emptyState.style.display = '';
            return;
        }

        // Show spinner immediately after short delay
        debounceTimer = setTimeout(() => {
            if (currentQuery === q) doSearch(q);
        }, 300);
    });

    clearBtn.addEventListener('click', function () {
        input.value = '';
        clearBtn.classList.remove('visible');
        currentQuery = '';
        container.innerHTML = '';
        summary.style.display = 'none';
        noResults.style.display = 'none';
        emptyState.style.display = '';
        input.focus();
    });

    // Run search on load if URL has ?q=
    const initialQ = input.value.trim();
    if (initialQ.length >= 2) {
        clearBtn.classList.add('visible');
        doSearch(initialQ);
    } else {
        emptyState.style.display = '';
    }
})();
</script>
@endpush
@endsection
