<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}">
<head>
{{--    <script id="cookieyes" type="text/javascript" src="https://cdn-cookieyes.com/client_data/b2e695576b04046a5a433e90/script.js"></script>--}}

{{--    <!-- Google tag (gtag.js) -->--}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7N1F5NBJY1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-7N1F5NBJY1');
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#e9e8e7">

{{--    <!-- Canonical URL -->--}}
    <link rel="canonical" href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale()) }}">

{{--    <!-- Hreflang tags for multilingual SEO -->--}}
    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        <link rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode) }}" />
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ LaravelLocalization::getLocalizedURL('pl') }}" />

    <link rel="icon" type="image/png" href="/favicon.png"/>

    <title>{{ $title ?? __('Quiz Polaka - Testy przygotowujące do rozmowy na karcie Polaka') }}</title>

    <meta name="description" content="{{ $description ?? __('Przygotuj się do egzaminu na Kartę Polaka z naszym interaktywnym testem! Sprawdź swoją wiedzę o polskiej historii, kulturze i języku. Rozwiązuj testy, aby zwiększyć swoje szanse na uzyskanie Karty Polaka.') }}" />

{{--    <!-- Open Graph tags -->--}}
    <meta property="og:title" content="{{ $title ?? __('Quiz Polaka - Testy przygotowujące do rozmowy na karcie Polaka') }}">
    <meta property="og:description" content="{{ $description ?? __('Przygotuj się do egzaminu na Kartę Polaka z naszym interaktywnym testem! Sprawdź swoją wiedzę o polskiej historii, kulturze i języku. Rozwiązuj testy, aby zwiększyć swoje szanse na uzyskanie Karty Polaka.') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale()) }}">
    <meta property="og:locale" content="{{ str_replace('_', '-', LaravelLocalization::getSupportedLocales()[LaravelLocalization::getCurrentLocale()]['regional']) }}">
    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        @if($localeCode !== LaravelLocalization::getCurrentLocale())
            <meta property="og:locale:alternate" content="{{ str_replace('_', '-', $properties['regional']) }}">
        @endif
    @endforeach
    <meta property="og:site_name" content="{{ __('Quiz Polaka') }}">

{{--    <!-- Twitter Card tags -->--}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? __('Quiz Polaka - Testy przygotowujące do rozmowy na karcie Polaka') }}">
    <meta name="twitter:description" content="{{ $description ?? __('Przygotuj się do egzaminu na Kartę Polaka z naszym interaktywnym testem! Sprawdź swoją wiedzę o polskiej historii, kulturze i języku. Rozwiązuj testy, aby zwiększyć swoje szanse na uzyskanie Karty Polaka.') }}">

{{--    <!-- Language and content locale -->--}}
    <meta http-equiv="Content-Language" content="{{ LaravelLocalization::getCurrentLocale() }}"  />

    {{--    <!-- Fonts -->--}}
    <link rel="preconnect" href="https://fonts.bunny.net">
{{--    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>--}}

{{--    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />--}}

{{--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">--}}

    @vite('resources/css/quiz.scss')
    @vite('resources/css/layout.scss')
    @vite(['resources/css/app.css'])

    @yield('styles')


    @filamentStyles
</head>
<body class="antialiased ">
{{--<body class="antialiased bg-gradient-to-t from-purple-100 via-pink-100 to-indigo-100 ">--}}


<div class="app_container shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)]_">

{{--    <livewire:layout.navigation />--}}
    <livewire:layout.navigation_v2 />

    @yield('content')
    @if(isset($slot))
        {{ $slot }}
    @endif
</div>

@vite('resources/js/app.js')
@filamentScripts


</body>
</html>
