@php
    /** @var \Hyde\Support\Paginator $paginator */
@endphp

<nav class="mt-4 flex justify-center">
    @if ($paginator->previous())
        <x-link :href="$paginator->previous()">&#8249;</x-link>
    @else
        <span class="opacity-75">&#8249;</span>
    @endif

    <div class="px-2">
        @foreach ($paginator->getPageLinks() as $pageNumber => $destination)
            @if ($paginator->currentPage() === $pageNumber)
                <strong>{{ $pageNumber }}</strong>
            @else
                <x-link :href="$destination">{{ $pageNumber }}</x-link>
            @endif
        @endforeach
    </div>

    @if ($paginator->next())
        <x-link :href="$paginator->next()">&#8250;</x-link>
    @else
        <span class="opacity-75">&#8250;</span>
    @endif
</nav>
