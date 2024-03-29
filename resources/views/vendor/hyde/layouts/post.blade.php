{{-- The Post Page Layout --}}
@extends("hyde::layouts.app")
@section("content")
    <main id="content" class="mx-auto max-w-7xl px-8 py-16">
        @include("hyde::components.post.article")
    </main>
@endsection
