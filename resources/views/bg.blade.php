<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/favicon.png" />

    <title> Karty Polaka</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            background: linear-gradient(to bottom, #e9e8e7 50%, red 50%);
            height: 100vh;
            margin: 0;
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .app_container {
            width: 80%;
            height: 100%;
            background: #fdfdfd;
        }
        :root{
            --background-dark: #2d3548;
            --text-light: rgba(255,255,255,0.6);
            --text-lighter: rgba(255,255,255,0.9);
            --spacing-s: 8px;
            --spacing-m: 16px;
            --spacing-l: 24px;
            --spacing-xl: 32px;
            --spacing-xxl: 64px;
            --width-container: 1200px;
        }

        .hero-section{
            align-items: flex-start;
            /*background-image: linear-gradient(15deg, #0f4667 0%, #2a6973 150%);*/
            display: flex;
            justify-content:center;
            margin-block: 20px;

        }

        .card-grid{
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            grid-column-gap: var(--spacing-l);
            grid-row-gap: var(--spacing-l);
            max-width: var(--width-container);
            width: 100%;
        }

        @media(min-width: 540px){
            .card-grid{
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(min-width: 960px){
            .card-grid{
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .category-card{
            list-style: none;
            position: relative;
        }

        .category-card:before{
            content: '';
            display: block;
            padding-bottom: 75%;
            width: 100%;
        }

        .card__background{
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
            transition:
                    filter 200ms linear,
                    transform 200ms linear;
        }

        .category-card:hover .card__background{
            transform: scale(1.05) translateZ(0);
        }

        .card-grid:hover > .category-card:not(:hover) .card__background{
            /*filter: brightness(0.5) saturate(0) contrast(1.2) blur(20px);*/
        }

        .card__content{
            left: 0;
            padding: var(--spacing-l);
            position: absolute;
            top: 0;
        }

        .card__category{
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: var(--spacing-s);
            text-transform: uppercase;
        }

        .card__heading{
            color: var(--text-lighter);
            font-size: 1.9rem;
            text-shadow: 2px 2px 20px rgba(0,0,0,0.2);
            line-height: 1.4;
            word-spacing: 100vw;
        }

    </style>
</head>
<body>
<div class="app_container shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)]">
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Karta Polaka</span>
        </div>
    </nav>
    <section class="hero-section">
        <div class="card-grid">
            @foreach($topics as $topic)
                <a class="category-card" href="#">
                    <div class="card__background" style="background-image: url({{url('storage/' . $topic->picture)}})"></div>
                    <div class="card__content">
{{--                        <p class="card__category">Category</p>--}}
                        <h3 class="card__heading">{{$topic->name_pl}}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
</div>
</body>
</html>
