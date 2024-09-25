<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#e9e8e7">


    <link rel="icon" type="image/png" href="/favicon.png"/>

    <title>{{ $title ?? 'Karta Polaka - Testy' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    @vite('resources/css/quiz.scss')

    <style>
        body, html {
            margin: 0;
            padding: 0;
            touch-action: pan-x pan-y;

        }

        * {
            box-sizing: border-box;
        }

        :root {
            --background-dark: #2d3548;
            --text-light: rgba(255, 255, 255, 0.6);
            --text-lighter: rgba(255, 255, 255, 0.9);
            --spacing-s: 8px;
            --spacing-m: 16px;
            --spacing-l: 24px;
            --spacing-xl: 32px;
            --spacing-xxl: 64px;
            --width-container: 1200px;
            --font-family: 'Inter';
        }

        body {
            background: linear-gradient(to bottom, #e9e8e7 50%, rgba(255, 0, 0, 0.35) 50%);
            min-height: 100dvh;
            margin: 0;
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);

            font-feature-settings: normal;
            -webkit-tap-highlight-color: transparent;
            font-family: var(--font-family), ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-variation-settings: normal;
        }

        .app_container {
            width: 1220px;
            min-height: 100dvh;
            background-color:  rgb(235, 235, 235);
            margin: 0 auto;

            box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;

            /*overflow: auto; !* AdminPanelProvider*/
        }
        @media only screen and (max-width: 1220px) {
            .app_container {
                width: 94%;
            }
        }


        .hero-section {
            align-items: flex-start;
            /*background-image: linear-gradient(15deg, #0f4667 0%, #2a6973 150%);*/
            display: flex;
            /*justify-content: center;*/
            margin-block: 20px;
            padding: 0 10px;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-column-gap: var(--spacing-l);
            grid-row-gap: var(--spacing-l);
            max-width: var(--width-container);
            width: 100%;
        }

        @media (max-width: 444px) {
            .card-grid {
                grid-template-columns: repeat(1, 1fr);
            }
        }
        @media (min-width: 540px) {
            .card-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 960px) {
            .card-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .category-card {
            list-style: none;
            position: relative;
        }

        .category-card:before {
            content: '';
            display: block;
            padding-bottom: 75%;
            width: 100%;
        }

        .card__background {
            background-size: cover;
            background-position: center;
            border-radius: var(--spacing-l);
            bottom: 0;
            filter: brightness(0.75) saturate(1.2) contrast(0.85);
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            transform-origin: center;
            trsnsform: scale(1) translateZ(0);
            transition: filter 200ms linear,
            transform 200ms linear;
        }

        .category-card:hover .card__background {
            transform: scale(1.05) translateZ(0);
        }

        .card-grid:hover > .category-card:not(:hover) .card__background {
            /*filter: brightness(0.5) saturate(0) contrast(1.2) blur(20px);*/
        }

        .card__content {
            left: 0;
            padding: var(--spacing-l);
            position: absolute;
            top: 0;
        }

        .card__category {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: var(--spacing-s);
            text-transform: uppercase;
        }

        .card__heading {
            color: var(--text-lighter);
            font-size: 1.9rem;
            text-shadow: 2px 2px 20px rgba(0, 0, 0, 0.2);
            line-height: 1.4;
            word-spacing: 100vw;
        }

    </style>
    @yield('styles')

</head>
<body>
<div class="app_container shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)]">
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a href="/" wire:navigate  class="navbar-brand mb-0 h1">Karta Polaka - Testy</a>
        </div>
    </nav>
    @yield('content')
    @if(isset($slot))
        {{ $slot }}
    @endif
</div>
{{--@livewireScripts--}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
