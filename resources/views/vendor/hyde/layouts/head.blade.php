<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>{{ $page->title() }}</title>

@if (file_exists(Hyde::mediaPath("favicon.ico")))
    <link rel="shortcut icon" href="/assets/favicon.ico" type="image/x-icon" />
@endif

<link rel="manifest" href="/assets/site.webmanifest" />

{{-- App Meta Tags --}}
@include("hyde::layouts.meta")

{{-- App Stylesheets --}}
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
    href="https://fonts.googleapis.com/css2?family=Delius&display=swap"
    rel="stylesheet"
/>
@include("hyde::layouts.styles")

@if (Hyde::hasFeature("darkmode"))
    {{-- Check the local storage for theme preference to avoid FOUC --}}
    <meta
        id="meta-color-scheme"
        name="color-scheme"
        content="{{ config("hyde.default_color_scheme", "light") }}"
    />
    <script>
        if (
            localStorage.getItem('color-theme') === 'dark' ||
            (!('color-theme' in localStorage) &&
                window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
            document
                .getElementById('meta-color-scheme')
                .setAttribute('content', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
@endif

{{-- If the user has defined any custom head tags, render them here --}}
{!! config("hyde.head") !!}
{!! Includes::html("head") !!}
