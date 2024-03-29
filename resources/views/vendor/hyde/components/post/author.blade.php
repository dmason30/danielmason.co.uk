by author
<address
    itemprop="author"
    itemscope
    itemtype="https://schema.org/Person"
    aria-label="The post author"
    class="inline-block"
>
    @if ($page->author->website)
        <a
            href="{{ $page->author->website }}"
            rel="author"
            itemprop="url"
            aria-label="The author's website"
            class="inline-block"
        >
            <span
                itemprop="name"
                aria-label="The author's name"
                {{ $page->author->username && $page->author->username !== $page->author->name ? "title=@" . urlencode($page->author->username) . "" : "" }}
            >
                {{ $page->author->name ?? $page->author->username }}
            </span>
        </a>
    @else
        <span
            itemprop="name"
            aria-label="The author's name"
            {{ $page->author->username && $page->author->username !== $page->author->name ? "title=@" . urlencode($page->author->username) . "" : "" }}
        >
            {{ $page->author->name ?? $page->author->username }}
        </span>
    @endif
</address>
