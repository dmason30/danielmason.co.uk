@php
    $title = "Packages";
    $packages = \App\Domains\Packagist\Actions\GetPackagesRequestAction::make()->handle();
@endphp

@extends("hyde::layouts.app")
@section("content")
    <main id="content" class="mx-auto flex max-w-7xl flex-col gap-5 px-8 py-12">
        <header class="prose font-grand dark:prose-invert">
            Check out my
            <a
                href="https://opendor.me/@dmason30"
                target="_blank"
                rel="noreferrer noopener"
            >
                opendor.me profile
            </a>
            for the many third party open source packages I have contributed to.
        </header>

        <div
            id="packages-feed"
            class="mx-auto grid gap-4 md:grid-cols-2 lg:grid-cols-3"
        >
            @foreach ($packages as $package)
                @if (! $package->abandoned)
                    <a href="{{ $package->repository }}" class="">
                        <img
                            class="rounded-lg border hover:border-orange-600"
                            src="{{ $package->imageUrl }}"
                            alt="{{ $package->name }}"
                            loading="lazy"
                        />
                    </a>
                @endif
            @endforeach
        </div>
    </main>
@endsection
