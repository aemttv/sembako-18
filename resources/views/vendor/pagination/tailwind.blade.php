@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation">
        <ul class="flex items-center justify-between">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true">
                    <span class="px-4 py-2 text-gray-400 rounded-lg cursor-not-allowed">
                        &laquo; Previous
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" 
                       rel="prev" 
                       class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        &laquo; Previous
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            <div class="flex space-x-2">
                @php
                    $current = $paginator->currentPage();
                    $last = $paginator->lastPage();
                    $showPages = 3; // Number of pages to show
                    $half = floor($showPages / 2);
                    $start = max($current - $half, 1);
                    $end = min($start + $showPages - 1, $last);
                    
                    // Adjust if we're near the end
                    if ($end - $start < $showPages - 1) {
                        $start = max($end - $showPages + 1, 1);
                    }
                @endphp

                {{-- First page link --}}
                @if ($start > 1)
                    <li>
                        <a href="{{ $paginator->url(1) }}" 
                           class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            1
                        </a>
                    </li>
                    @if ($start > 2)
                        <li class="disabled" aria-disabled="true">
                            <span class="px-4 py-2">...</span>
                        </li>
                    @endif
                @endif

                {{-- Page number links --}}
                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $current)
                        <li aria-current="page">
                            <span class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                                {{ $page }}
                            </span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $paginator->url($page) }}" 
                               class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                {{ $page }}
                            </a>
                        </li>
                    @endif
                @endfor

                {{-- Last page link --}}
                @if ($end < $last)
                    @if ($end < $last - 1)
                        <li class="disabled" aria-disabled="true">
                            <span class="px-4 py-2">...</span>
                        </li>
                    @endif
                    <li>
                        <a href="{{ $paginator->url($last) }}" 
                           class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            {{ $last }}
                        </a>
                    </li>
                @endif
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" 
                       rel="next" 
                       class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Next &raquo;
                    </a>
                </li>
            @else
                <li class="disabled" aria-disabled="true">
                    <span class="px-4 py-2 text-gray-400 rounded-lg cursor-not-allowed">
                        Next &raquo;
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif