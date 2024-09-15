@section('styles')
    <style>
        .avatar {
            background-size: cover;
            background-position: center;
            border-radius: var(--spacing-l);
            filter: brightness(0.75) saturate(1.2) contrast(0.85);
            width:280px;
            height:210px;
        }
        .topic_page {
            padding: 0 10px;
        }
    </style>
@endsection
<div>

    <div class="container my-5">
        <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-5">
            <h1 class="bi mt-5 mb-3 text-body-emphasis">{{$topic->name_pl}}</h1>
            <p class="col-lg-6 mx-auto mb-4">
                {{$topic->description_pl}}
            </p>

            <div class="btn-group btn-group-lg btn-group-mobile flex-wrap" role="group" aria-label="Button group with nested dropdown">

                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        {{$surveyMode}}
                    </button>
                    <ul class="dropdown-menu">
{{--                        <li><a class="dropdown-item"  wire:click="setMode('10 pytań')">10 pytań</a></li>--}}
                        <li><a class="dropdown-item"  wire:click="setMode('Wszystkie pytania')">Wszystkie pytania</a></li>
                    </ul>
                </div>
                <button wire:click="startSurvey" type="button" class="btn btn-primary">Rozpocznij test</button>

            </div>

        </div>
    </div>

</div>
