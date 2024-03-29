@php
    /** @var \Hyde\Pages\MarkdownPost $post */
@endphp

<article
    itemscope
    itemtype="https://schema.org/Article"
    class="flex flex-col gap-2 rounded-lg border-2 border-gray-200 bg-white px-2 pb-2 hover:border-orange-500 dark:border-gray-800 dark:bg-gray-800 dark:hover:border-orange-500"
>
    <meta itemprop="identifier" content="{{ $post->identifier }}" />
    @if (Hyde::hasSiteUrl())
        <meta
            itemprop="url"
            content="{{ Hyde::url("posts/" . $post->identifier) }}"
        />
    @endif

    <a href="{{ $post->getRoute() }}" class="-mx-2 block w-fit rounded-lg">
        <img
            src="{{ $post->image }}"
            alt="{{ $post->title }}"
            title="{{ $post->title }}"
            class="aspect-[16/9] w-full rounded-t-lg object-cover"
        />
    </a>

    <header class="clip">
        <a href="{{ $post->getRoute() }}" class="block w-fit rounded-lg">
            <h2
                class="text-2xl font-bold text-gray-700 transition-colors duration-75 hover:text-gray-900 dark:text-gray-200 dark:hover:text-white"
            >
                {{ $post->data("title") ?? $post->title }}
            </h2>
        </a>
    </header>

    @if ($description = $post->data("description"))
        <section role="doc-abstract" aria-label="Excerpt">
            <p class="my-1 text-sm leading-relaxed">
                {{ substr($description, 0, strpos($description, "!") ?: strpos($description, ".")) . "." }}
            </p>
        </section>
    @endisset

    <footer>
        @isset($post->author)
            <span
                itemprop="author"
                itemscope
                itemtype="https://schema.org/Person"
                class="hidden"
            >
                <span class="opacity-75">by</span>
                <a href="{{ $post->author->website }}" itemprop="name">
                    {{ $post->author->name ?? $post->author->username }}
                </a>
            </span>
        @endisset
    </footer>

    <footer class="mt-auto flex justify-between">
        @isset($post->date)
            <span class="opacity-75">
                <span itemprop="dateCreated datePublished">
                    {{ $post->date->short }}
                </span>
            </span>
        @endisset

        <a
            href="{{ $post->getRoute() }}"
            class="font-medium text-orange-600 hover:text-orange-600 hover:underline dark:text-teal-500 dark:hover:text-teal-400"
        >
            Read post
        </a>
    </footer>
</article>
