<!DOCTYPE html>
<html lang="{{ config("hyde.language", "en") }}">
    <head>
        @include("hyde::layouts.head")
    </head>
    <body
        id="app"
        class="relative flex min-h-screen flex-col overflow-x-hidden bg-white dark:bg-[#0d1117]"
        x-data="{ navigationOpen: false }"
        x-on:keydown.escape="navigationOpen = false;"
    >
        <div
            class="absolute inset-0 -z-10 min-h-0 bg-[url('/assets/grid.svg')] dark:bg-[url('/assets/dots.svg')]"
        ></div>

        @include("hyde::components.skip-to-content-button")
        @include("hyde::layouts.navigation")

        <section>
            @yield("content")
        </section>

        {{-- @include("hyde::layouts.footer") --}}

        @include("hyde::layouts.scripts")
    </body>
</html>
