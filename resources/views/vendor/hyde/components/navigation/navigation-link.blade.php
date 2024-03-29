<a
    href="{{ $item }}"
    {!! $item->isCurrent() ? 'aria-current="page"' : "" !!}
    @class([
        "my-2 block py-1 text-gray-700 dark:text-white md:my-0 md:inline-block md:text-black dark:md:text-black",
        "-ml-6 border-l-4 border-teal-500 bg-gray-100 pl-5 font-medium dark:bg-gray-800 md:ml-0 md:border-none md:bg-transparent md:pl-0 dark:md:bg-transparent" => $item->isCurrent(),
    ])
>
    {{ $item->label }}
</a>
