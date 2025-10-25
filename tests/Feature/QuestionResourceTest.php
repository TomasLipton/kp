<?php

declare(strict_types=1);

use App\Filament\Resources\QuestionResource\Pages\CreateQuestion;
use App\Models\Question;
use App\Models\Topics;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseHas;

test('authenticated user is automatically assigned as author when creating a question', function () {
    $admin = User::factory()->admin()->create();
    $topic = Topics::factory()->create();

    $this->actingAs($admin);

    Livewire::test(CreateQuestion::class)
        ->fillForm([
            'question_pl' => 'Test question in Polish?',
            'explanation_pl' => 'Test explanation',
            'question_type' => 'single_text',
            'topics_id' => $topic->id,
            'answers' => [
                [
                    'text' => 'Answer 1',
                    'is_correct' => true,
                    'order' => 1,
                ],
                [
                    'text' => 'Answer 2',
                    'is_correct' => false,
                    'order' => 2,
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoErrors();

    assertDatabaseHas(Question::class, [
        'question_pl' => 'Test question in Polish?',
        'user_id' => $admin->id,
    ]);

    $question = Question::where('question_pl', 'Test question in Polish?')->first();
    expect($question->user_id)->toBe($admin->id);
    expect($question->user->name)->toBe($admin->name);
});

test('user field is hidden on create page', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin);

    Livewire::test(CreateQuestion::class)
        ->assertFormFieldExists('question_pl')
        ->assertFormFieldExists('topics_id')
        ->assertFormFieldExists('question_type')
        ->assertFormFieldDoesNotExist('user_id', 'mountedActionsData.0.data');
});
