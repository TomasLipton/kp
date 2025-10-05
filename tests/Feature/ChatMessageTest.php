<?php

declare(strict_types=1);

use App\Models\AIQuiz;
use App\Models\ChatMessage;
use App\Models\Topics;
use App\Models\User;

test('chat message can be created and associated with ai quiz', function () {
    $user = User::factory()->create();
    $topic = Topics::factory()->create();

    $aiQuiz = AIQuiz::create([
        'user_id' => $user->id,
        'topic_id' => $topic->id,
        'speed' => 'normal',
        'difficulty' => 'medium',
        'gender' => 'female',
        'voice' => 'verse',
        'status' => 'active',
    ]);

    $chatMessage = ChatMessage::create([
        'a_i_quiz_id' => $aiQuiz->id,
        'role' => 'user',
        'content' => 'What is the capital of France?',
    ]);

    expect($chatMessage->aiQuiz->id)->toBe($aiQuiz->id)
        ->and($chatMessage->role)->toBe('user')
        ->and($chatMessage->content)->toBe('What is the capital of France?');
});

test('chat message can store tool call information', function () {
    $user = User::factory()->create();
    $topic = Topics::factory()->create();

    $aiQuiz = AIQuiz::create([
        'user_id' => $user->id,
        'topic_id' => $topic->id,
        'speed' => 'normal',
        'difficulty' => 'medium',
        'gender' => 'female',
        'voice' => 'verse',
        'status' => 'active',
    ]);

    $toolCall = [
        'response' => 'Paris',
        'quiz_session_id' => $aiQuiz->id,
    ];

    $chatMessage = ChatMessage::create([
        'a_i_quiz_id' => $aiQuiz->id,
        'role' => 'assistant',
        'content' => 'The answer is Paris',
        'tool_name' => 'save_quiz_response',
        'tool_call' => $toolCall,
    ]);

    expect($chatMessage->tool_name)->toBe('save_quiz_response')
        ->and($chatMessage->tool_call)->toBe($toolCall)
        ->and($chatMessage->role)->toBe('assistant');
});

test('ai quiz has many chat messages relationship works', function () {
    $user = User::factory()->create();
    $topic = Topics::factory()->create();

    $aiQuiz = AIQuiz::create([
        'user_id' => $user->id,
        'topic_id' => $topic->id,
        'speed' => 'normal',
        'difficulty' => 'medium',
        'gender' => 'female',
        'voice' => 'verse',
        'status' => 'active',
    ]);

    ChatMessage::create([
        'a_i_quiz_id' => $aiQuiz->id,
        'role' => 'user',
        'content' => 'First message',
    ]);

    ChatMessage::create([
        'a_i_quiz_id' => $aiQuiz->id,
        'role' => 'assistant',
        'content' => 'Second message',
    ]);

    expect($aiQuiz->chatMessages()->count())->toBe(2)
        ->and($aiQuiz->chatMessages->first()->content)->toBe('First message')
        ->and($aiQuiz->chatMessages->last()->content)->toBe('Second message');
});
