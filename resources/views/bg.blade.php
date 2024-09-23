@extends('layouts.app')

@section('content')

    <section class="hero-section">
        <div class="card-grid">
            @foreach($topics as $topic)
                <a class="category-card" href="/{{$topic->slug}}" wire:navigate wire:navigate.hover>
                    <div class="card__background" style="background-image: url({{url('storage/' . $topic->picture)}})"></div>
                    <div class="card__content">
                                                <p class="card__category">
                                                    {{$topic->questions()->count()}}
                                                    @php
                                                        $count = $topic->questions()->count();
                                                    @endphp
                                                    @if($count == 1)
                                                        Pytanie
                                                    @elseif($count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20))
                                                        Pytania
                                                    @else
                                                        Pytań
                                                    @endif
                                                </p>
                        <h3 class="card__heading">{{trim($topic->name_pl)}}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

@endsection
