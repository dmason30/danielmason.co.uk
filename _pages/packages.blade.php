@php
    $title = "Packages";
    $packages = \App\Domains\Packagist\Actions\GetPackagesRequestAction::make()->handle();
@endphp

@extends("hyde::layouts.app")
@section("content")
    <main id="content" class="mx-auto flex max-w-7xl flex-col gap-5 px-8 py-12">
        <h1 class="text-3xl font-bold">My Open-Source Packages</h1>
        <div class="prose font-delius text-lg dark:prose-invert">
            Check out my
            <a
                href="https://opendor.me/@dmason30"
                target="_blank"
                rel="noreferrer noopener"
            >
                opendor.me profile
            </a>
            for the many third party open source packages I have contributed to.
        </div>

        <div
            id="packages-feed"
            class="mx-auto grid gap-4 md:grid-cols-2 lg:grid-cols-3"
        >
            @foreach ($packages as $package)
                <a href="{{ $package->repository }}" class="">
                    <img
                        class="rounded-lg border hover:border-orange-600"
                        src="{{ $package->imageUrl }}"
                        alt="{{ $package->name }}"
                        width="1200"
                        height="600"
                    />
                </a>
            @endforeach
        </div>
    </main>
@endsection

@push("meta")
    @foreach ($packages as $package)
        <link rel="preload" href="{{ $package->imageUrl }}" as="image" />
    @endforeach
@endpush
