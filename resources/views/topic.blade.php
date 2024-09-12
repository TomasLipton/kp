@extends('layouts.main')

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
    </style>
@endsection

@section('content')
<section class="topic_page">
    <h1>{{$topic->picture}}</h1>
    <h1>{{$topic->name_pl}}</h1>
    <h1>{{$topic->description_pl}}</h1>
    <h1>{{$topic->description_pl}}</h1>


    <div class="avatar" style="background-image: url({{url('storage/' . $topic->picture)}})"></div>
</section>


@endsection
