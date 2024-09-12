@extends('layouts.main')

@section('content')

    <section class="hero-section">
        <div class="card-grid">
            @foreach($topics as $topic)
                <a class="category-card" href="#">
                    <div class="card__background" style="background-image: url({{url('storage/' . $topic->picture)}})"></div>
                    <div class="card__content">
                                                <p class="card__category">Category</p>
                        <h3 class="card__heading">{{$topic->name_pl}}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

@endsection
