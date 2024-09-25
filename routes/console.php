<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('d', function () {
    \Carbon\Carbon::setLocale('pl');
dd(\Carbon\Carbon::parse(' 12.11'));
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('a', function () {
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'xi-api-key' => config('app.elevenlabs_api_key')

    ])->post('https://api.elevenlabs.io/v1/text-to-speech/C1DBnkwmDIzoLOPlBvSg', [
        'text' => 'Witam Witam Witam Witam Witam',
        'model_id' => 'eleven_multilingual_v2',
//        'language_code' => 'pl_PL',
//        'voice_settings' => [
//            'stability' => 13,
//            'similarity_boost' => 123,
//            'style' => 123,
//            'use_speaker_boost' => true,
//        ],
    ]);

    if ($response->failed()) {
        echo "Error: " . $response->body();
    } else {
        $fileName = 'speech/' . uniqid('', true) . '.mp3';
        Storage::put($fileName, $response->body());
        (new \App\Models\AiSpeach([
            'path_to_audio' => $fileName,
            'type' => 'free-text',
            'voice_id' => 'C1DBnkwmDIzoLOPlBvSg',
            'text' => 'Witam',
            'voice_settings' => [
                'stability' => 13,
                'similarity_boost' => 123,
                'style' => 123,
                'use_speaker_boost' => true,
            ],
        ]))->save();
        $url = Storage::temporaryUrl(
            $fileName, now()->addMinutes(5)
        );
        echo $url;
    }

})->purpose('Display an inspiring quote')->hourly();
