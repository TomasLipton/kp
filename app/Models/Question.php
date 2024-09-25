<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Question extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'question_pl',
        'question_ru',
        'question_type',
        'picture',
        'explanation_pl',
        'explanation_ru',
        'topics_id',
    ];

    public function topics(): BelongsTo
    {
        return $this->belongsTo(Topics::class);
    }

    public function answers()
    {
        return $this->hasMany(QuestionAnswer::class);
    }

    public function aiSpeach()
    {
        return $this->hasMany(AiSpeach::class);
    }

    public function generateVoice()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'xi-api-key' => config('app.elevenlabs_api_key')

        ])->post('https://api.elevenlabs.io/v1/text-to-speech/C1DBnkwmDIzoLOPlBvSg', [
            'text' => $this->question_pl,
            'model_id' => 'eleven_multilingual_v2',
            'voice_settings' => [
                'stability' => 60,
                'similarity_boost' => 20,
                'style' => 17,
                'use_speaker_boost' => true,
            ],
        ]);

        if ($response->failed()) {
            throw new \Exception($response->body());
        } else {
            $fileName = 'speech/' . uniqid('', true) . '.mp3';
            Storage::put($fileName, $response->body());
            (new \App\Models\AiSpeach([
                'path_to_audio' => $fileName,
                'type' => 'questions',
                'voice_id' => 'C1DBnkwmDIzoLOPlBvSg',
                'text' => $this->question_pl,
                'voice_settings' => null,
                'question_id' => $this->id,
            ]))->save();

        }
    }
}
