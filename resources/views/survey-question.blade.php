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
    <livewire:survey-question />



@endsection
