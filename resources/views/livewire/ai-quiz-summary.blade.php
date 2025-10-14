<?php

use App\Models\AIQuiz;
use App\Models\QuizAnswer;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app-kp')]
class extends Component {
    public $quizId;
    public $quiz;
    public $statistics = [];
    public $answeredQuestions = [];

    public function mount($quiz)
    {
        $this->quizId = $quiz;
        $this->loadQuizData();
    }

    public function loadQuizData()
    {
        $this->quiz = AIQuiz::with(['quiz', 'topic'])->findOrFail($this->quizId);

        // Get all answered questions with their details
        $this->answeredQuestions = QuizAnswer::where('quiz_id', $this->quiz->quiz_id)
            ->with(['questionAnswer.question'])
            ->get()
            ->map(function ($quizAnswer) {
                $question = $quizAnswer->questionAnswer->question ?? null;
                $selectedAnswer = $quizAnswer->questionAnswer;

                return [
                    'question' => $question?->question_pl ?? 'Unknown question',
                    'selected_answer' => $selectedAnswer?->text ?? 'No answer',
                    'is_correct' => $selectedAnswer?->is_correct ?? false,
                ];
            });

        // Calculate statistics
        $totalAnswered = $this->answeredQuestions->count();
        $correctAnswers = $this->answeredQuestions->where('is_correct', true)->count();
        $percentage = $totalAnswered > 0 ? round(($correctAnswers / $totalAnswered) * 100, 1) : 0;

        $this->statistics = [
            'total_answered' => $totalAnswered,
            'correct_answers' => $correctAnswers,
            'incorrect_answers' => $totalAnswered - $correctAnswers,
            'percentage' => $percentage,
        ];
    }

    public function retakeQuiz()
    {
        return $this->redirect(route('ai-sync-configure'));
    }
}; ?>

<div class="min-h-screen py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 px-4 py-2 bg-green-100 dark:bg-green-900 rounded-full mb-4">
                @svg('lucide-check-circle', 'w-5 h-5 text-green-600 dark:text-green-400')
                <span class="text-sm font-semibold text-green-600 dark:text-green-400">Quiz Completed</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold bg-gradient-primary bg-clip-text text-transparent mb-3">
                Świetna robota!
            </h1>
            <p class="text-lg text-muted-foreground">
                {{ $quiz->topic->{'name_' . app()->getLocale()} ?? $quiz->topic->name_pl }}
            </p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid md:grid-cols-4 gap-4 mb-8">
            <!-- Score Card -->
            <div class="col-span-2 bg-gradient-to-br from-primary/10 to-primary/5 rounded-2xl p-6 border border-primary/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-muted-foreground mb-1">Your Score</p>
                        <p class="text-5xl font-bold text-primary">{{ $statistics['percentage'] }}%</p>
                    </div>
                    <div class="w-20 h-20 rounded-full bg-primary/20 flex items-center justify-center">
                        @svg('lucide-trophy', 'w-10 h-10 text-primary')
                    </div>
                </div>
            </div>

            <!-- Total Answered -->
            <div class="bg-card rounded-2xl p-6 border border-border">
                <div class="flex items-center gap-3 mb-2">
                    @svg('lucide-list-checks', 'w-5 h-5 text-blue-500')
                    <p class="text-sm font-medium text-muted-foreground">Total</p>
                </div>
                <p class="text-3xl font-bold">{{ $statistics['total_answered'] }}</p>
            </div>

            <!-- Correct Answers -->
            <div class="bg-card rounded-2xl p-6 border border-border">
                <div class="flex items-center gap-3 mb-2">
                    @svg('lucide-check', 'w-5 h-5 text-green-500')
                    <p class="text-sm font-medium text-muted-foreground">Correct</p>
                </div>
                <p class="text-3xl font-bold text-green-600">{{ $statistics['correct_answers'] }}</p>
            </div>
        </div>

        <!-- Answered Questions List -->
        <div class="bg-card rounded-2xl p-8 border border-border mb-8">
            <div class="flex items-center gap-2 mb-6">
                @svg('lucide-clipboard-list', 'w-6 h-6 text-primary')
                <h2 class="text-2xl font-bold">Your Answers</h2>
            </div>

            @if($answeredQuestions->isEmpty())
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-full bg-muted mx-auto mb-4 flex items-center justify-center">
                        @svg('lucide-inbox', 'w-8 h-8 text-muted-foreground')
                    </div>
                    <p class="text-muted-foreground">No questions answered yet</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($answeredQuestions as $index => $answer)
                        <div class="flex gap-4 p-4 rounded-xl {{ $answer['is_correct'] ? 'bg-green-50 dark:bg-green-950/20 border border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800' }}">
                            <!-- Number Badge -->
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full {{ $answer['is_correct'] ? 'bg-green-500' : 'bg-red-500' }} text-white flex items-center justify-center font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <p class="font-semibold text-foreground mb-2">{{ $answer['question'] }}</p>
                                <div class="flex items-start gap-2">
                                    @if($answer['is_correct'])
                                        @svg('lucide-check-circle', 'w-5 h-5 text-green-600 mt-0.5')
                                        <p class="text-sm text-green-700 dark:text-green-400">
                                            <span class="font-medium">Your answer:</span> {{ $answer['selected_answer'] }}
                                        </p>
                                    @else
                                        @svg('lucide-x-circle', 'w-5 h-5 text-red-600 mt-0.5')
                                        <p class="text-sm text-red-700 dark:text-red-400">
                                            <span class="font-medium">Your answer:</span> {{ $answer['selected_answer'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4 justify-center">
            <a
                href="{{ route('ai-sync-configure') }}"
                wire:navigate
                class="px-8 py-4 bg-gradient-primary text-primary-foreground rounded-xl font-semibold hover:scale-105 transition-all shadow-lg inline-flex items-center gap-2"
            >
                @svg('lucide-refresh-cw', 'w-5 h-5')
                Try Again
            </a>

            <a
                href="{{ route('dashboard') }}"
                wire:navigate
                class="px-8 py-4 bg-background border-2 border-border text-foreground rounded-xl font-semibold hover:scale-105 transition-all inline-flex items-center gap-2"
            >
                @svg('lucide-home', 'w-5 h-5')
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
