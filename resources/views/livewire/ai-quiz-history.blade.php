<?php

use App\Models\AIQuiz;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app-kp')]
class extends Component
{
    use WithPagination;

    public $activeTab = 'quizzes'; // 'quizzes' or 'ai_quizzes'

    public $statusFilter = 'all';

    public function with(): array
    {
        if ($this->activeTab === 'quizzes') {
            // Regular quiz history
            $quizzes = Quiz::with(['topics', 'answers.questionAnswer'])
                ->where('user_id', auth()->id())
                ->whereNotNull('completed_at')
                ->orderBy('completed_at', 'desc')
                ->paginate(12);

            // Add statistics for each quiz
            $quizzes->getCollection()->transform(function ($quiz) {
                $totalAnswered = $quiz->answers->count();
                $correctAnswers = $quiz->answers->filter(function ($answer) {
                    return $answer->questionAnswer?->is_correct ?? false;
                })->count();

                $quiz->total_answered = $totalAnswered;
                $quiz->correct_answers = $correctAnswers;
                $quiz->percentage = $totalAnswered > 0 ? (int) round(($correctAnswers / $totalAnswered) * 100) : 0;

                return $quiz;
            });

            return [
                'quizzes' => $quizzes,
                'aiQuizzes' => collect(),
            ];
        } else {
            // AI quiz history
            $query = AIQuiz::with(['topic', 'quiz'])
                ->where('user_id', auth()->id())
                ->orderBy('created_at', 'desc');

            if ($this->statusFilter !== 'all') {
                $query->where('status', $this->statusFilter);
            }

            $aiQuizzes = $query->paginate(12);

            // Add statistics for each AI quiz
            $aiQuizzes->getCollection()->transform(function ($quiz) {
                if ($quiz->quiz_id) {
                    $answeredQuestions = QuizAnswer::where('quiz_id', $quiz->quiz_id)
                        ->with('questionAnswer')
                        ->get();

                    $totalAnswered = $answeredQuestions->count();
                    $correctAnswers = $answeredQuestions->filter(function ($answer) {
                        return $answer->questionAnswer?->is_correct ?? false;
                    })->count();

                    $quiz->total_answered = $totalAnswered;
                    $quiz->correct_answers = $correctAnswers;
                    $quiz->percentage = $totalAnswered > 0 ? (int) round(($correctAnswers / $totalAnswered) * 100) : 0;
                } else {
                    $quiz->total_answered = 0;
                    $quiz->correct_answers = 0;
                    $quiz->percentage = 0;
                }

                return $quiz;
            });

            return [
                'quizzes' => collect(),
                'aiQuizzes' => $aiQuizzes,
            ];
        }
    }

    public function updatedActiveTab()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }
}; ?>

<div class="min-h-screen py-12 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold bg-gradient-primary bg-clip-text text-transparent mb-2">
                        Historia Quizów
                    </h1>
                    <p class="text-lg text-muted-foreground">
                        Zobacz wszystkie swoje wcześniejsze sesje quizów
                    </p>
                </div>
@if($activeTab === 'quizzes')
                    <a
                        href="/topics"
                        wire:navigate
                        class="px-6 py-3 bg-gradient-primary text-primary-foreground rounded-xl font-semibold hover:scale-105 transition-all shadow-lg inline-flex items-center gap-2"
                    >
                        @svg('lucide-plus', 'w-5 h-5')
                        Nowy Quiz
                    </a>
                @elseif(auth()->user()->isAdmin())
                    <a
                        href="{{ route('ai-sync-configure') }}"
                        wire:navigate
                        class="px-6 py-3 bg-gradient-primary text-primary-foreground rounded-xl font-semibold hover:scale-105 transition-all shadow-lg inline-flex items-center gap-2"
                    >
                        @svg('lucide-plus', 'w-5 h-5')
                        Nowy Quiz AI
                    </a>
                @endif
            </div>

            <!-- Tabs -->
            <div class="flex gap-2 mb-6">
                <button
                    wire:click="$set('activeTab', 'quizzes')"
                    class="px-6 py-3 rounded-lg font-semibold transition-all {{ $activeTab === 'quizzes' ? 'bg-primary text-primary-foreground shadow-lg' : 'bg-card text-muted-foreground hover:bg-muted' }}"
                >
                    @svg('lucide-clipboard-list', 'w-5 h-5 inline-block mr-2')
                    Quizy
                </button>
                @if(auth()->user()->isAdmin())
                    <button
                        wire:click="$set('activeTab', 'ai_quizzes')"
                        class="px-6 py-3 rounded-lg font-semibold transition-all {{ $activeTab === 'ai_quizzes' ? 'bg-primary text-primary-foreground shadow-lg' : 'bg-card text-muted-foreground hover:bg-muted' }}"
                    >
                        @svg('lucide-sparkles', 'w-5 h-5 inline-block mr-2')
                        Quizy AI
                    </button>
                @endif
            </div>

            <!-- Filters (only for AI quizzes) -->
            @if($activeTab === 'ai_quizzes')
                <div class="flex gap-2">
                    <button
                        wire:click="$set('statusFilter', 'all')"
                        class="px-4 py-2 rounded-lg font-medium transition-all {{ $statusFilter === 'all' ? 'bg-primary text-primary-foreground' : 'bg-card text-muted-foreground hover:bg-muted' }}"
                    >
                        Wszystkie
                    </button>
                    <button
                        wire:click="$set('statusFilter', 'completed')"
                        class="px-4 py-2 rounded-lg font-medium transition-all {{ $statusFilter === 'completed' ? 'bg-primary text-primary-foreground' : 'bg-card text-muted-foreground hover:bg-muted' }}"
                    >
                        Ukończone
                    </button>
                    <button
                        wire:click="$set('statusFilter', 'in_progress')"
                        class="px-4 py-2 rounded-lg font-medium transition-all {{ $statusFilter === 'in_progress' ? 'bg-primary text-primary-foreground' : 'bg-card text-muted-foreground hover:bg-muted' }}"
                    >
                        W trakcie
                    </button>
                </div>
            @endif
        </div>

        <!-- Regular Quiz History Tab -->
        @if($activeTab === 'quizzes')
            @if($quizzes->isEmpty())
                <div class="bg-card rounded-2xl p-12 border border-border text-center">
                    <div class="w-20 h-20 rounded-full bg-muted mx-auto mb-4 flex items-center justify-center">
                        @svg('lucide-inbox', 'w-10 h-10 text-muted-foreground')
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Brak Quizów</h3>
                    <p class="text-muted-foreground mb-6">Rozpocznij swój pierwszy quiz, aby zobaczyć go tutaj</p>
                    <a
                        href="/topics"
                        wire:navigate
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-primary text-primary-foreground rounded-xl font-semibold hover:scale-105 transition-all"
                    >
                        @svg('lucide-play-circle', 'w-5 h-5')
                        Rozpocznij Pierwszy Quiz
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($quizzes as $quiz)
                        <div class="bg-card rounded-2xl p-6 border border-border hover:shadow-lg transition-all">
                            <div class="flex items-center gap-6">
                                <!-- Status Badge -->
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-950 flex items-center justify-center">
                                        @svg('lucide-check-circle', 'w-6 h-6 text-green-600 dark:text-green-400')
                                    </div>
                                </div>

                                <!-- Main Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-3 mb-1">
                                                <h3 class="font-bold text-xl truncate">
                                                    {{ $quiz->topics->{'name_' . app()->getLocale()} ?? $quiz->topics?->name_pl }}
                                                </h3>
                                                <span class="px-2 py-1 bg-green-100 dark:bg-green-950 text-green-700 dark:text-green-400 text-xs font-semibold rounded-lg flex-shrink-0">
                                                    Ukończony
                                                </span>
                                                @if($quiz->type)
                                                    <span class="px-2 py-1 bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-400 text-xs font-semibold rounded-lg flex-shrink-0">
                                                        {{ ucfirst($quiz->type) }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-4 text-sm text-muted-foreground mb-2">
                                                <div class="flex items-center gap-1">
                                                    @svg('lucide-calendar', 'w-4 h-4')
                                                    <span>{{ $quiz->completed_at->diffForHumans() }}</span>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    @svg('lucide-list-checks', 'w-4 h-4')
                                                    <span>{{ $quiz->questions_amount ?? $quiz->total_answered }} pytań</span>
                                                </div>
                                                @if($quiz->completed_at && $quiz->created_at)
                                                    @php
                                                        $diff = $quiz->created_at->diff($quiz->completed_at);
                                                        $minutes = $diff->i;
                                                        $seconds = $diff->s;
                                                    @endphp
                                                    <div class="flex items-center gap-1">
                                                        @svg('lucide-timer', 'w-4 h-4')
                                                        <span>{{ $minutes }}m {{ $seconds }}s</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Statistics -->
                                            @if($quiz->total_answered > 0)
                                                <div class="flex items-center gap-4 text-sm">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-2xl font-bold text-primary">{{ $quiz->percentage }}%</span>
                                                        <span class="text-muted-foreground">wynik</span>
                                                    </div>
                                                    <div class="flex items-center gap-1 text-muted-foreground">
                                                        @svg('lucide-check-circle', 'w-4 h-4 text-green-500')
                                                        <span>{{ $quiz->correct_answers }}/{{ $quiz->total_answered }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            <a
                                                href="/{{ $quiz->topics->slug }}/{{ $quiz->uuid }}"
                                                wire:navigate
                                                class="px-4 py-2 bg-primary text-primary-foreground rounded-lg font-medium hover:scale-105 transition-all text-sm inline-flex items-center gap-2"
                                            >
                                                @svg('lucide-bar-chart', 'w-4 h-4')
                                                Zobacz Wyniki
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $quizzes->links() }}
                </div>
            @endif

        <!-- AI Quiz History Tab -->
        @else
            @if($aiQuizzes->isEmpty())
                <div class="bg-card rounded-2xl p-12 border border-border text-center">
                    <div class="w-20 h-20 rounded-full bg-muted mx-auto mb-4 flex items-center justify-center">
                        @svg('lucide-inbox', 'w-10 h-10 text-muted-foreground')
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Brak Quizów AI</h3>
                    <p class="text-muted-foreground mb-6">Rozpocznij swoją pierwszą sesję quizu AI, aby zobaczyć ją tutaj</p>
                    <a
                        href="{{ route('ai-sync-configure') }}"
                        wire:navigate
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-primary text-primary-foreground rounded-xl font-semibold hover:scale-105 transition-all"
                    >
                        @svg('lucide-play-circle', 'w-5 h-5')
                        Rozpocznij Pierwszy Quiz AI
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($aiQuizzes as $quiz)
                        <div class="bg-card rounded-2xl p-6 border border-border hover:shadow-lg transition-all">
                            <div class="flex items-center gap-6">
                                <!-- Status Badge -->
                                <div class="flex-shrink-0">
                                    @if($quiz->status === 'completed')
                                        <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-950 flex items-center justify-center">
                                            @svg('lucide-check-circle', 'w-6 h-6 text-green-600 dark:text-green-400')
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-950 flex items-center justify-center">
                                            @svg('lucide-clock', 'w-6 h-6 text-amber-600 dark:text-amber-400')
                                        </div>
                                    @endif
                                </div>

                                <!-- Main Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-3 mb-1">
                                                <h3 class="font-bold text-xl truncate">
                                                    {{ $quiz->topic->{'name_' . app()->getLocale()} ?? $quiz->topic?->name_pl }}
                                                </h3>
                                                @if($quiz->status === 'completed')
                                                    <span class="px-2 py-1 bg-green-100 dark:bg-green-950 text-green-700 dark:text-green-400 text-xs font-semibold rounded-lg flex-shrink-0">
                                                        Ukończony
                                                    </span>
                                                @elseif($quiz->status === 'in_progress')
                                                    <span class="px-2 py-1 bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-400 text-xs font-semibold rounded-lg flex-shrink-0">
                                                        W trakcie
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-400 text-xs font-semibold rounded-lg flex-shrink-0">
                                                        {{ ucfirst($quiz->status) }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-4 text-sm text-muted-foreground mb-2">
                                                <div class="flex items-center gap-1">
                                                    @svg('lucide-calendar', 'w-4 h-4')
                                                    <span>{{ $quiz->created_at->diffForHumans() }}</span>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    @svg('lucide-user', 'w-4 h-4')
                                                    <span>Głos {{ $quiz->gender === 'male' ? 'męski' : ($quiz->gender === 'female' ? 'żeński' : 'B/D') }}</span>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    @svg('lucide-gauge', 'w-4 h-4')
                                                    <span>
                                                        @if($quiz->difficulty === 'easy')
                                                            Łatwy
                                                        @elseif($quiz->difficulty === 'hard')
                                                            Trudny
                                                        @else
                                                            Średni
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Statistics -->
                                            @if($quiz->total_answered > 0)
                                                <div class="flex items-center gap-4 text-sm">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-2xl font-bold text-primary">{{ $quiz->percentage }}%</span>
                                                        <span class="text-muted-foreground">wynik</span>
                                                    </div>
                                                    <div class="flex items-center gap-1 text-muted-foreground">
                                                        @svg('lucide-check-circle', 'w-4 h-4 text-green-500')
                                                        <span>{{ $quiz->correct_answers }}/{{ $quiz->total_answered }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                <p class="text-sm text-muted-foreground italic">Brak zapisanych odpowiedzi</p>
                                            @endif
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            @if($quiz->total_answered > 0)
                                                <a
                                                    href="{{ route('ai-quiz-summary', ['quiz' => $quiz->id]) }}"
                                                    wire:navigate
                                                    class="px-4 py-2 bg-primary text-primary-foreground rounded-lg font-medium hover:scale-105 transition-all text-sm inline-flex items-center gap-2"
                                                >
                                                    @svg('lucide-bar-chart', 'w-4 h-4')
                                                    Zobacz Wyniki
                                                </a>
                                            @else
                                                <a
                                                    href="{{ route('voice-quiz', ['quiz' => $quiz->id]) }}"
                                                    wire:navigate
                                                    class="px-4 py-2 bg-primary text-primary-foreground rounded-lg font-medium hover:scale-105 transition-all text-sm inline-flex items-center gap-2"
                                                >
                                                    @svg('lucide-play', 'w-4 h-4')
                                                    Kontynuuj
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $aiQuizzes->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
