@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center gap-1.5 flex-wrap">

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}"
                class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-slate-300 cursor-not-allowed">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}"
                class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-slate-600 hover:border-indigo-400 hover:text-indigo-600 transition">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span aria-disabled="true" class="inline-flex items-center justify-center w-10 h-10 text-slate-400 font-bold">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page"
                            class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-600 text-white font-bold shadow-lg shadow-indigo-200">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                            class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-slate-600 font-medium hover:border-indigo-400 hover:text-indigo-600 transition">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}"
                class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-slate-600 hover:border-indigo-400 hover:text-indigo-600 transition">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
        @else
            <span aria-disabled="true" aria-label="{{ __('pagination.next') }}"
                class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-slate-300 cursor-not-allowed">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </span>
        @endif
    </nav>
@endif
