{{-- The Markdown Page Layout --}}
@extends("hyde::layouts.app")
@section("content")
    <main id="content" class="mx-auto max-w-7xl px-8 py-16">
        <article
            @class(["mx-auto", config("markdown.prose_classes", "prose dark:prose-invert"), "torchlight-enabled" => Features::hasTorchlight()])
        >
            {{ $content }}
        </article>
    </main>
@endsection
