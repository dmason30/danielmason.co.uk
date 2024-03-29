<!DOCTYPE html>
<html lang="{{ config("hyde.language", "en") }}">
    <head>
        @include("hyde::layouts.head")
    </head>
    <body
        id="app"
        class="flex min-h-screen flex-col overflow-x-hidden bg-gradient-to-b from-white to-teal-100 dark:from-[#0d1117] dark:to-teal-950"
        x-data="{ navigationOpen: false }"
        x-on:keydown.escape="navigationOpen = false;"
    >
        @include("hyde::components.skip-to-content-button")
        @include("hyde::layouts.navigation")

        <section>
            @yield("content")
        </section>

        {{-- @include("hyde::layouts.footer") --}}

        @include("hyde::layouts.scripts")
    </body>
</html>
