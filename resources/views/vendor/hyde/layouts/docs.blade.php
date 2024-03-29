<!DOCTYPE html>
<html lang="{{ config("hyde.language", "en") }}">
    <head>
        @include("hyde::layouts.head")
    </head>
    <body
        id="hyde-docs"
        class="relative min-h-screen w-screen overflow-y-auto overflow-x-hidden bg-white dark:bg-gray-900 dark:text-white"
        x-data="{ sidebarOpen: false, searchWindowOpen: false }"
        x-on:keydown.escape="searchWindowOpen = false; sidebarOpen = false"
        x-on:keydown.slash="searchWindowOpen = true"
    >
        @include("hyde::components.skip-to-content-button")
        @include("hyde::components.docs.mobile-navigation")
        @include("hyde::components.docs.sidebar")

        <main
            id="content"
            class="absolute top-16 min-h-screen w-screen bg-gray-50 dark:bg-gray-900 md:left-64 md:top-0 md:w-[calc(100vw_-_16rem)] md:bg-white"
        >
            @include("hyde::components.docs.documentation-article")
        </main>

        <div id="support">
            @include("hyde::components.docs.sidebar-backdrop")

            @if (Hyde\Facades\Features::hasDocumentationSearch())
                @include("hyde::components.docs.search-widget")
                @include("hyde::components.docs.search-scripts")
            @endif
        </div>

        @include("hyde::layouts.scripts")
    </body>
</html>
