@php
    $navigation = \Hyde\Framework\Features\Navigation\NavigationMenu::create();
@endphp

<nav
    aria-label="Main navigation"
    id="main-navigation"
    class="align-center flex w-full flex-wrap items-center border-b bg-[#d0d7de] bg-opacity-25 p-4 dark:border-none dark:bg-black md:flex-row md:flex-nowrap md:border-none md:bg-inherit dark:md:bg-inherit xl:mx-auto xl:max-w-7xl"
>
    <div
        class="flex flex-shrink-0 flex-grow items-center text-gray-700 dark:text-gray-200 md:flex-none"
    >
        @include("hyde::components.navigation.navigation-brand")

        <div class="ml-auto flex items-center gap-2 md:hidden">
            <x-icon-bar />
        </div>
    </div>

    <div class="block md:hidden">
        <button
            id="navigation-toggle-button"
            class="flex items-center p-2 hover:text-gray-700 dark:text-gray-200"
            aria-label="Toggle navigation menu"
            @click="navigationOpen = ! navigationOpen"
        >
            <svg
                x-show="! navigationOpen"
                title="Open Navigation Menu"
                class="block h-8 w-8 dark:fill-gray-200"
                id="open-main-navigation-menu-icon"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
            >
                <title>Open Menu</title>
                <path d="M0 0h24v24H0z" fill="none" />
                <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z" />
            </svg>
            <svg
                x-show="navigationOpen"
                title="Close Navigation Menu"
                class="dark:fill-gray-200"
                style="display: none"
                id="close-main-navigation-menu-icon"
                xmlns="http://www.w3.org/2000/svg"
                height="24"
                viewBox="0 0 24 24"
                width="24"
            >
                <title>Close Menu</title>
                <path d="M0 0h24v24H0z" fill="none"></path>
                <path
                    d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"
                ></path>
            </svg>
        </button>
    </div>

    <div
        id="main-navigation-links"
        class="x-uncloak-md md:align-center mt-3 w-full border-t border-gray-200 px-6 pt-3 dark:border-gray-700 md:mt-0 md:flex md:w-auto md:flex-grow md:items-center md:border-none md:py-0"
        :class="navigationOpen ? '' : 'hidden'"
        x-cloak
    >
        <ul
            aria-label="Navigation links"
            class="justify-center gap-5 md:flex md:flex-grow"
        >
            @foreach ($navigation->items as $item)
                <li
                    class="font-grand md:mx-2 md:bg-teal-500 md:px-2 md:text-2xl md:transition md:duration-200 odd:md:rotate-6 even:md:-rotate-6 hover:md:rotate-0 hover:md:scale-110 hover:md:bg-orange-600 lg:text-3xl"
                >
                    @if ($item instanceof \Hyde\Framework\Features\Navigation\DropdownNavItem)
                        <x-hyde::navigation.dropdown
                            :label="\Hyde\Hyde::makeTitle($item->label)"
                            :items="$item->items"
                        />
                    @else
                        @include("hyde::components.navigation.navigation-link")
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    <div class="hidden items-center justify-end md:flex lg:gap-5">
        <x-icon-bar />
    </div>
</nav>
