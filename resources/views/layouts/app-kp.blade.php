<!DOCTYPE html>
<html lang="pl">
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


    <link rel="icon" type="image/png" href="/favicon.png"/>

    <title>{{ $title ?? 'Quiz Polaka - Testy przygotowujące do rozmowy na karcie Polaka' }}</title>

    <meta name="description" content="Przygotuj się do egzaminu na Kartę Polaka z naszym interaktywnym testem! Sprawdź swoją wiedzę o polskiej historii, kulturze i języku. Rozwiązuj testy, aby zwiększyć swoje szanse na uzyskanie Karty Polaka." />

    {{--    <!-- Fonts -->--}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

{{--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">--}}

    @vite('resources/css/quiz.scss')
    @vite('resources/css/layout.scss')
    @vite(['resources/css/app.css'])

    @yield('styles')


    @filamentStyles
</head>
<body class="antialiased">

<div class="app_container shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)]">

    <livewire:layout.navigation />
    <livewire:layout.navigation_v2 />

    @yield('content')
    @if(isset($slot))
        {{ $slot }}
    @endif
</div>
{{--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>--}}
@vite('resources/js/app.js')
@filamentScripts

</body>
</html>
