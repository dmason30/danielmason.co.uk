@extends('_layouts.master')

@section('body')
    <div class="container mx-auto px-4 py-8">
        <div class="sm:mt-32">
            <div class="flex justify-center sm:mt-32">
                <div class="w-full lg:w-1/2">
                    <div class="bg-transparent p-6 rounded">
                        <div class="text-center">
                            <img
                                class="h-32 mx-auto block rounded-full shadow-lg"
                                src="images/avatar.jpg"
                                alt="Daniel Mason"
                            />
                            <h1 class="block mt-6 text-4xl uppercase tracking-wide">
                                Daniel Mason
                            </h1>
                            <h2 class="block mt-3 text-lg">
                                Senior Software Engineer
                            </h2>
                            <span class="block mt-3 text-xl font-light">
                                @
                            </span>

                            <div class="mt-4">
                                <a href="https://www.spendology.co.uk" title="Spendology Ltd">
                                    <img class="h-16 mx-auto block" src="images/spendology-logo.png" alt="Spendology Ltd" />
                                </a>

                                <div class="mt-8">
                                    <a href="https://www.travelmoneyclub.co.uk" title="Travel Money Club">
                                        <img class="h-12 mx-auto mb-12 block" src="images/tmc-logo.png" alt="Travel Money Club" />
                                    </a>
                                </div>
                            </div>

                            <div class="flex mt-8">
                                <div class="w-1/3">
                                    <a href="//twitter.com/danmasonmp"  title="Twitter">
                                        <img class="h-12 mx-auto block" src="svg/Twitter_Logo_Blue.svg" alt="Twitter" />
                                    </a>
                                </div>
                                <div class="w-1/3">
                                    <a href="//github.com/dmason30" title="GitHub">
                                        <img class="h-12 mx-auto block" src="images/Octocat.png" alt="GitHub" />
                                    </a>
                                </div>
                                <div class="w-1/3">
                                    <a href="//linkedin.com/in/masondaniel" title="LinkedIn">
                                        <img class="h-12 mx-auto block" src="images/linkedin.png" alt="LinkedIn" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
