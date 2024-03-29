@php($title = 'Articles')
@extends('hyde::layouts.app')
@section('content')
    <main id="content" class="mx-auto max-w-7xl py-12 px-8">
        <div id="post-feed" class="mx-auto grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @include('hyde::components.blog-post-feed')
        </div>
    </main>
@endsection
