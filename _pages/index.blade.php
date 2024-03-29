@php($title = 'Blog')
@extends('hyde::layouts.app')
@section('content')
<main class="my-auto px-6 pb-12 antialiased app-gradient-dark">
    <div class="mx-auto max-w-7xl">
        <!-- Main Hero Content -->
        <div class="container max-w-lg px-4 py-32 mx-auto text-left md:max-w-none md:text-center">
            <h1
                class="text-5xl font-extrabold leading-10 tracking-tight text-left text-gray-100 md:text-center sm:leading-none md:text-6xl lg:text-7xl">
                <span class="block text-4xl md:text-5xl mb-4 sm:mb-0">You're running on </span><span
                    class="relative mt-2 text-transparent bg-clip-text bg-gradient-to-br logo-gradient md:inline-block drop-shadow-2xl tracking-normal">HydePHP</span>
            </h1>
            <div class="mx-auto mt-8 sm:mt-4 text-gray-200 md:mt-8 md:max-w-2xl md:text-center">
                <section aria-label="About Hyde">
                    <p class="lg:text-lg">
                        Leap into the future of static HTML blogs and documentation with the tools you already know and love.
                        Made with Tailwind, Laravel, and Coffee.
                    </p>
                </section>

                <section aria-label="About this page">
                    <p class="mt-4 mb-4">
                        This is the default homepage stored as index.blade.php, however you can publish any of the built-in views using the following command:

                        <!-- Syntax highlighted by torchlight.dev -->
                    <pre style="margin-top: 1.5rem; margin-bottom: 1.5rem;"><code data-theme="material-theme-palenight" data-lang="bash" class="torchlight" style="background-color: #292D3E; padding: 0.5rem 1rem; border-radius: 0.25rem;"><span style="color: #FFCB6B;">php hyde</span> <span style="color: #C3E88D;">publish:homepage</span></code></pre>
                    </p>
                </section>

                <div class="mt-4 md:mt-8 text-white">
                    <span class="sr-only">Resources for getting started</span>
                    <ul class="flex flex-wrap justify-center list-none" style="padding: 0;">
                        <li>
                            <a href="https://hydephp.com/docs/1.x" class="uppercase font-bold text-sm flex text-center m-2 mx-3">
                                Documentation
                            </a>
                        </li>
                        <li>
                            <a href="https://hydephp.com/docs/1.x/getting-started" class="uppercase font-bold text-sm flex text-center m-2 mx-3">
                                Getting Started
                            </a>
                        </li>
                        <li>

                            <a href="https://github.com/hydephp/hyde" class="uppercase font-bold text-sm flex text-center m-2 mx-3">
                                GitHub Source Code
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Main Hero Content -->
    </div>
</main>
@endsection
