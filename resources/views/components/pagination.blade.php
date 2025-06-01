<style>
    .custom-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
}

.custom-pagination a {
    margin: 0 5px;
    padding: 10px;
    border: 1px solid #F28123;
    border-radius: 5px;
    text-decoration: none;
    color: black;
    transition: background-color 0.3s;
}

.custom-pagination a:hover {
    background-color: #F28123;
    color: white;
}

.custom-pagination .active {
    background-color: #F28123;
    color: white;
    padding: 10px;
    border-radius: 5px;
}

.custom-pagination .disabled {
    margin: 0 5px;
    padding: 10px;
    color: gray;
}

</style>
<div class="flex justify-center items-center space-x-2">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <span class="px-4 py-2 text-gray-400 bg-white rounded-lg shadow-sm cursor-not-allowed">
            « Previous
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" 
            class="px-4 py-2 bg-white text-gray-800 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
            « Previous
        </a>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="px-4 py-2 bg-gray-800 text-white rounded-lg shadow-sm">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" 
                        class="px-4 py-2 bg-white text-gray-800 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
                        {{ $page }}
                    </a>
                @endif
            @endforeach
        @endif

        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            <span class="px-4 py-2 text-gray-400">
                {{ $element }}
            </span>
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" 
            class="px-4 py-2 bg-white text-gray-800 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
            Next »
        </a>
    @else
        <span class="px-4 py-2 text-gray-400 bg-white rounded-lg shadow-sm cursor-not-allowed">
            Next »
        </span>
    @endif
</div>
