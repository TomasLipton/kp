<?php

declare(strict_types=1);

use App\Models\AIQuiz;
use App\Models\ChatMessage;
use App\Models\Topics;
use App\Models\User;

test('chat messages are persisted in chronological order', function () {
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

    // Simulate a conversation
    ChatMessage::create([
        'a_i_quiz_id' => $aiQuiz->id,
        'role' => 'user',
        'content' => 'What is the capital of France?',
    ]);

    ChatMessage::create([
        'a_i_quiz_id' => $aiQuiz->id,
        'role' => 'assistant',
        'content' => 'The capital of France is Paris.',
    ]);

    ChatMessage::create([
        'a_i_quiz_id' => $aiQuiz->id,
        'role' => 'user',
        'content' => 'What about Germany?',
    ]);

    ChatMessage::create([
        'a_i_quiz_id' => $aiQuiz->id,
        'role' => 'assistant',
        'content' => 'The capital of Germany is Berlin.',
    ]);

    $messages = $aiQuiz->chatMessages()->orderBy('created_at')->get();

    expect($messages)->toHaveCount(4)
        ->and($messages[0]->role)->toBe('user')
        ->and($messages[0]->content)->toBe('What is the capital of France?')
        ->and($messages[1]->role)->toBe('assistant')
        ->and($messages[1]->content)->toBe('The capital of France is Paris.')
        ->and($messages[2]->role)->toBe('user')
        ->and($messages[2]->content)->toBe('What about Germany?')
        ->and($messages[3]->role)->toBe('assistant')
        ->and($messages[3]->content)->toBe('The capital of Germany is Berlin.');
});

test('conversation history can be loaded for quiz session', function () {
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

    // Create multiple messages
    for ($i = 1; $i <= 5; $i++) {
        ChatMessage::create([
            'a_i_quiz_id' => $aiQuiz->id,
            'role' => $i % 2 === 1 ? 'user' : 'assistant',
            'content' => "Message {$i}",
        ]);
    }

    $history = ChatMessage::where('a_i_quiz_id', $aiQuiz->id)
        ->orderBy('created_at')
        ->get()
        ->map(fn ($m) => ['role' => $m->role, 'content' => $m->content])
        ->toArray();

    expect($history)->toHaveCount(5)
        ->and($history[0]['role'])->toBe('user')
        ->and($history[1]['role'])->toBe('assistant')
        ->and($history[4]['content'])->toBe('Message 5');
});

test('tool calls are stored with chat messages', function () {
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
        'content' => 'The answer is Paris',
    ]);

    $toolCallData = [
        'response' => 'Paris',
        'quiz_session_id' => $aiQuiz->id,
    ];

    ChatMessage::create([
        'a_i_quiz_id' => $aiQuiz->id,
        'role' => 'assistant',
        'content' => 'Paris',
        'tool_name' => 'save_quiz_response',
        'tool_call' => $toolCallData,
    ]);

    ChatMessage::create([
        'a_i_quiz_id' => $aiQuiz->id,
        'role' => 'assistant',
        'content' => 'Great! I have saved your response.',
    ]);

    $messages = $aiQuiz->chatMessages()->orderBy('created_at')->get();

    expect($messages)->toHaveCount(3)
        ->and($messages[1]->tool_name)->toBe('save_quiz_response')
        ->and($messages[1]->tool_call)->toBe($toolCallData)
        ->and($messages[2]->tool_name)->toBeNull();
});
