<div>
    <div class="text-center mb-8 mt-8">
        <h1 class="text-3xl md:text-4xl font-bold bg-gradient-primary bg-clip-text text-transparent mb-4">
            Quiz Topics
        </h1>
        <p class="text-lg text-muted-foreground">
            Choose a topic to start your journey through Polish history
        </p>
    </div>

    <section class="hero-section">
        <div class="card-grid">
            @foreach($topics as $topic)
                <a class="category-card
                rounded-lg
                 " href="/{{$topic->slug}}" wire:navigate wire:navigate.hover>
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
                    <div class="absolute inset-0 border-2 scale-105 border-primary/0 hover:border-primary/50 transition-colors duration-300 rounded-lg"></div>

                </a>
            @endforeach
        </div>
    </section>

</div>
