@php($title = "Articles")
@extends("hyde::layouts.app")
@section("content")
    <main id="content" class="mx-auto flex max-w-7xl flex-col gap-5 px-8 py-12">
        <h1 class="text-3xl font-bold">Latest Articles, Tips & Tricks</h1>
        <div
            id="post-feed"
            class="mx-auto grid gap-4 md:grid-cols-2 lg:grid-cols-3"
        >
            @include("hyde::components.blog-post-feed")
        </div>
    </main>
@endsection
