<div style="padding: 15px 10px">
<h1>Test zakończony</h1>
    <p>Całkowita liczba pytań: <b>{{$quiz->answers->count()}}</b></p>
    <p>Rozpoczęto test: <b> {{$quiz->created_at->translatedFormat('F j, H:i') }}</b></p>
    <p>Czas trwania: <b>{{$quiz->created_at->diff()}}</b></p>
{{--    <p>Correct answrs: {{$quiz->answers->questionAnswer->}}</p>--}}
</div>
