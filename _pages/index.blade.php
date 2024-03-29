@php($title = 'Personal Site')
@extends('hyde::layouts.app')
@section('content')
<main class="my-auto antialiased">
    <div class="mx-auto max-w-7xl px-4">
        <!-- Main Hero Content -->
        <div class="container mt-4 md:mt-24 flex flex-col justify-center items-center gap-5 max-w-lg mx-auto text-left md:max-w-none md:text-center">
            <section class="font-grand flex flex-col items-center md:grid grid-cols-2 gap-5 px-4">
                <div class="prose-2xl flex flex-col gap-5 text-start">
                    <div>
                        <div class="font-sans text-7xl font-bold bg-gradient-to-r from-teal-500 via-orange-500 to-orange-600 dark:from-teal-500 dark:via-green-500 dark:to-orange-600 inline-block text-transparent bg-clip-text">
                            Dan Mason
                        </div>
                    </div>
                    <div>
                        I am a Full-Stack Developer with 10+ years experience and 7+ years using Laravel PHP for backend. I have used ReactJs, Vue and more on the frontend.
                    </div>
                    <div class="group">
                        Wolverhampton Wanderers football club fan.
                        <div class="inline-block group-hover:animate-bounce">&#9917;</div>
                    </div>
                    <div>
                        You can find me helping and educating other developers on the Laravel Discord.
                    </div>
                    <div class="gap-5 hidden md:flex">
                        <x-socials />
                    </div>
                </div>
                <div class="relative flex justify-center group">
                    <div class="absolute z-50 left-28 hidden md:block -mt-28 lg:-mt-24">
                        <div class="-ml-12">
                            <div class="rotate-6 block group-hover:hidden">
                                This is me :)
                            </div>
                            <div class="transition duration-200 rotate-90 group-hover:rotate-12 hidden group-hover:block">
                                Hello World! &#128075;
                            </div>
                        </div>
                        <br />
                        <x-arrow class="scale-150 mt-2 mb-2 rotate-45 text-orange-600 group-hover:text-teal-500" />
                    </div>
                    <img
                        class="max-h-72 aspect-square rounded-full border-2 border-teal-500 group-hover:border-4 group-hover:border-orange-600 duration-200 group-hover:rotate-6"
                        src="assets/profile.png"
                        title="An image of Dan Mason wearing a blue suite."
                    />
                </div>
            </section>
        </div>
    </div>
</main>
@endsection
