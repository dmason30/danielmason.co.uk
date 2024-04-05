@if (config("hyde.footer") !== false)
    <footer
        aria-label="Page footer"
        class="min-h-content flex w-full justify-end p-4 font-delius text-xl"
    >
        <a
            href="#app"
            aria-label="Go to top of page"
            class="flex gap-2 rounded-lg bg-teal-500 p-2 text-black hover:bg-orange-600"
        >
            <svg
                width="1.5rem"
                height="1.5rem"
                role="presentation"
                class="h-6 w-6 fill-black text-gray-500"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
            >
                <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z" />
            </svg>
            Scroll To Top
        </a>
    </footer>
@endif
