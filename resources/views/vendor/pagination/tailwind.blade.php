@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-base-300 bg-base-100 border border-base-300 cursor-default leading-5 rounded-md">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-base-content bg-base-100 border border-base-300 leading-5 rounded-md hover:text-base-content focus:outline-none focus:ring ring-primary focus:border-primary active:bg-base-200 active:text-base-content transition ease-in-out duration-150">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-base-content bg-base-100 border border-base-300 leading-5 rounded-md hover:text-base-content focus:outline-none focus:ring ring-primary focus:border-primary active:bg-base-200 active:text-base-content transition ease-in-out duration-150">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span
                    class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-base-300 bg-base-100 border border-base-300 cursor-default leading-5 rounded-md">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-base-content leading-5">
                    {{ __('pagination.showing', [
                        'first' => $paginator->firstItem() ? $paginator->firstItem() : 0,
                        'last' => $paginator->lastItem() ? $paginator->lastItem() : 0,
                        'total' => $paginator->total(),
                        'perPage' => $paginator->perPage(),
                    ]) }}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rtl:flex-row-reverse shadow-sm rounded-md">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span
                                class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-base-300 bg-base-100 border border-base-300 cursor-default rounded-l-md leading-5"
                                aria-hidden="true">
                                <x-lucide-chevron-left class="w-5 h-5" />
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                            class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-base-content bg-base-100 border border-base-300 rounded-l-md leading-5 hover:text-base-content focus:z-10 focus:outline-none focus:ring ring-primary focus:border-primary active:bg-base-200 active:text-base-content transition ease-in-out duration-150"
                            aria-label="{{ __('pagination.previous') }}">
                            <x-lucide-chevron-left class="w-5 h-5" />
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span
                                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-base-content bg-base-100 border border-base-300 cursor-default leading-5">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span
                                            class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium bg-primary text-primary-content border border-base-300 cursor-default leading-5">
                                            {{ $page }}
                                        </span>
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                        class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-base-content bg-base-100 border border-base-300 leading-5 hover:text-base-content focus:z-10 focus:outline-none focus:ring ring-primary focus:border-primary active:bg-base-200 active:text-base-content transition ease-in-out duration-150"
                                        aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                            class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-base-content bg-base-100 border border-base-300 rounded-r-md leading-5 hover:text-base-content focus:z-10 focus:outline-none focus:ring ring-primary focus:border-primary active:bg-base-200 active:text-base-content transition ease-in-out duration-150"
                            aria-label="{{ __('pagination.next') }}">
                            <x-lucide-chevron-right class="w-5 h-5" />
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span
                                class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-base-300 bg-base-100 border border-base-300 cursor-default rounded-r-md leading-5"
                                aria-hidden="true">
                                <x-lucide-chevron-right class="w-5 h-5" />
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
