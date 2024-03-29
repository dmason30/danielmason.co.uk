@php($title = "Articles")
@extends("hyde::layouts.app")
@section("content")
    <main id="content" class="mx-auto max-w-7xl px-8 py-12">
        <div
            id="post-feed"
            class="mx-auto grid gap-4 md:grid-cols-2 lg:grid-cols-3"
        >
            @include("hyde::components.blog-post-feed")
        </div>
    </main>
@endsection
