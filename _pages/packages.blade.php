@php
$title = 'Packages';
$packages = \App\Domains\Packagist\Actions\GetPackagesRequestAction::make()->handle();
@endphp
@extends('hyde::layouts.app')
@section('content')
    <main id="content" class="mx-auto max-w-7xl py-12 px-8 flex flex-col gap-5">

        <header class="prose dark:prose-invert font-grand">
            Check out my
            <a href="https://opendor.me/@dmason30" target="_blank" rel="noreferrer noopener">OpenDor.Me profile</a>
            for the many third party open source packages I have contributed to.
        </header>

        <div id="packages-feed" class="mx-auto grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($packages as $package)
                @if(! $package->abandoned)
                    <a href="{{$package->repository}}" class="">
                        <img
                            class="border rounded-lg hover:border-orange-600"
                            src="{{$package->imageUrl}}"
                            alt="{{$package->name}}"
                        />
                    </a>
                @endif
            @endforeach
        </div>
    </main>
@endsection
