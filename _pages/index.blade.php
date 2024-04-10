@php($title = "Full-Stack Developer with 10+ years experience")
@extends("hyde::layouts.app")
@section("content")
    <main class="my-auto antialiased">
        <div class="mx-auto max-w-7xl px-4">
            <!-- Main Hero Content -->
            <div
                class="container mx-auto mt-4 flex max-w-lg flex-col items-center justify-center gap-5 text-left md:mt-24 md:max-w-none md:text-center"
            >
                <section
                    class="flex grid-cols-2 flex-col items-center gap-5 px-4 font-delius md:grid"
                >
                    <div class="prose-2xl flex flex-col gap-5 text-start">
                        <div>
                            <h1
                                class="inline-block bg-gradient-to-r from-teal-500 via-orange-500 to-orange-600 bg-clip-text font-sans text-7xl font-bold text-transparent dark:from-teal-500 dark:via-green-500 dark:to-orange-600"
                            >
                                Dan Mason
                            </h1>
                        </div>
                        <div>
                            I am a Full-Stack Developer with 10+ years
                            experience and 7+ years using Laravel PHP for
                            backend. I have used ReactJs, Vue and more on the
                            frontend.
                        </div>
                        <div class="group">
                            Wolverhampton Wanderers football club fan.
                            <div
                                class="inline-block group-hover:animate-bounce"
                            >
                                &#9917;
                            </div>
                        </div>
                        <div>
                            You can find me helping and educating other
                            developers on the Laravel Discord.
                        </div>
                        <div class="hidden gap-5 md:flex">
                            <x-socials />
                        </div>
                    </div>
                    <div class="group relative flex justify-center">
                        <div
                            class="absolute left-28 z-50 -mt-28 hidden md:block lg:-mt-24"
                        >
                            <div class="-ml-12">
                                <div class="block rotate-6 group-hover:hidden">
                                    This is me :)
                                </div>
                                <div
                                    class="hidden rotate-90 transition duration-200 group-hover:block group-hover:rotate-12"
                                >
                                    Hello World! &#128075;
                                </div>
                            </div>
                            <br />
                            <x-arrow
                                class="mb-2 mt-2 rotate-45 scale-150 text-orange-600 group-hover:text-teal-500"
                            />
                        </div>
                        <img
                            class="aspect-square max-h-72 rounded-full border-2 border-teal-500 duration-200 group-hover:rotate-6 group-hover:border-4 group-hover:border-orange-600"
                            src="assets/profile.png"
                            width="800"
                            height="800"
                            loading="eager"
                            title="An image of Dan Mason wearing a blue suite."
                            alt="Dan Mason wearing a blue suite."
                        />
                    </div>
                </section>
            </div>
        </div>
    </main>
@endsection

@push("meta")
    <link rel="preload" href="assets/profile.png" as="image" />
@endpush
