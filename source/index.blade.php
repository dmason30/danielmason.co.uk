@extends('_layouts.master')

@section('body')
    <div class="container mx-auto px-4 py-8">
        <div class="sm:mt-32">
            <div class="flex justify-center sm:mt-32">
                <div class="w-full lg:w-1/2">
                    <div class="bg-white shadow-lg p-6 rounded">
                        <div class="text-center">
                            <img
                                class="h-32 mx-auto block rounded-full shadow-lg"
                                src="images/avatar.jpg"
                                alt="Daniel Mason"
                            />
                            <h1 class="block mt-6 text-4xl font-light uppercase tracking-wide text-grey-darkest">
                                Daniel Mason
                            </h1>
                            <span class="block mt-3 text-lg font-light text-grey-darkest">
                                Senior Software Engineer
                            </span>
                            <span class="block mt-3 text-xl font-light text-grey-darkest">
                                @
                            </span><span class="block mt-3 text-xl font-light text-grey-darkest">
                                <a href="https://www.travelmoneyclub.co.uk" title="Travel Money Club">
                                    <img class="h-12" src="images/TMC_Logo.png" alt="Travel Money Club" />
                                </a>
                            </span>
                            <div class="flex mt-8">
                                <div class="w-1/3">
                                    <a href="//twitter.com/danmasonmp"  title="Twitter">
                                        <img class="h-12" src="svg/Twitter_Logo_Blue.svg" alt="Twitter" />
                                    </a>
                                </div>
                                <div class="w-1/3">
                                    <a href="//github.com/dmason30" title="GitHub">
                                        <img class="h-12" src="images/Octocat.png" alt="GitHub" />
                                    </a>
                                </div>
                                <div class="w-1/3">
                                    <a href="//linkedin.com/in/masondaniel" title="LinkedIn">
                                        <img class="h-12" src="images/linkedin.png" alt="LinkedIn" />
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
