<?php

use App\Models\AIQuiz;
use App\Models\QuizAnswer;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app-kp')]
class extends Component {
    use WithPagination;

    public $statusFilter = 'all';

    public function with(): array
    {
        $query = AIQuiz::with(['topic', 'quiz'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc');

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }


        $quizzes = $query->paginate(12);

        // Add statistics for each quiz
        $quizzes->getCollection()->transform(function ($quiz) {
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
                $quiz->percentage = $totalAnswered > 0 ? round(($correctAnswers / $totalAnswered) * 100) : 0;
            } else {
                $quiz->total_answered = 0;
                $quiz->correct_answers = 0;
                $quiz->percentage = 0;
            }

            return $quiz;
        });

        return [
            'quizzes' => $quizzes,
        ];
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function deleteQuiz($quizId)
    {
        $quiz = AIQuiz::where('id', $quizId)
            ->where('user_id', auth()->id())
            ->first();

        if ($quiz) {
            $quiz->delete();
            session()->flash('message', 'Quiz deleted successfully');
        }
    }
}; ?>

<div class="min-h-screen py-12 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold bg-gradient-primary bg-clip-text text-transparent mb-2">
                        Quiz History
                    </h1>
                    <p class="text-lg text-muted-foreground">
                        View all your past AI quiz sessions
                    </p>
                </div>
                <a
                    href="{{ route('ai-sync-configure') }}"
                    wire:navigate
                    class="px-6 py-3 bg-gradient-primary text-primary-foreground rounded-xl font-semibold hover:scale-105 transition-all shadow-lg inline-flex items-center gap-2"
                >
                    @svg('lucide-plus', 'w-5 h-5')
                    New Quiz
                </a>
            </div>

            <!-- Filters -->
            <div class="flex gap-2">
                <button
                    wire:click="$set('statusFilter', 'all')"
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ $statusFilter === 'all' ? 'bg-primary text-primary-foreground' : 'bg-card text-muted-foreground hover:bg-muted' }}"
                >
                    All
                </button>
                <button
                    wire:click="$set('statusFilter', 'completed')"
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ $statusFilter === 'completed' ? 'bg-primary text-primary-foreground' : 'bg-card text-muted-foreground hover:bg-muted' }}"
                >
                    Completed
                </button>
                <button
                    wire:click="$set('statusFilter', 'preparing')"
                    class="px-4 py-2 rounded-lg font-medium transition-all {{ $statusFilter === 'preparing' ? 'bg-primary text-primary-foreground' : 'bg-card text-muted-foreground hover:bg-muted' }}"
                >
                    In Progress
                </button>
            </div>
        </div>

        @if(session()->has('message'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-950/20 border border-green-200 dark:border-green-800 rounded-xl">
                <p class="text-green-700 dark:text-green-400">{{ session('message') }}</p>
            </div>
        @endif

        <!-- Quiz List -->
        @if($quizzes->isEmpty())
            <div class="bg-card rounded-2xl p-12 border border-border text-center">
                <div class="w-20 h-20 rounded-full bg-muted mx-auto mb-4 flex items-center justify-center">
                    @svg('lucide-inbox', 'w-10 h-10 text-muted-foreground')
                </div>
                <h3 class="text-xl font-semibold mb-2">No Quizzes Yet</h3>
                <p class="text-muted-foreground mb-6">Start your first AI quiz session to see it here</p>
                <a
                    href="{{ route('ai-sync-configure') }}"
                    wire:navigate
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-primary text-primary-foreground rounded-xl font-semibold hover:scale-105 transition-all"
                >
                    @svg('lucide-play-circle', 'w-5 h-5')
                    Start First Quiz
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($quizzes as $quiz)
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
                                                    Completed
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
                                                <span>{{ ucfirst($quiz->gender ?? 'N/A') }} voice</span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                @svg('lucide-gauge', 'w-4 h-4')
                                                <span>{{ ucfirst($quiz->difficulty ?? 'Medium') }}</span>
                                            </div>
                                        </div>

                                        <!-- Statistics -->
                                        @if($quiz->total_answered > 0)
                                            <div class="flex items-center gap-4 text-sm">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-2xl font-bold text-primary">{{ $quiz->percentage }}%</span>
                                                    <span class="text-muted-foreground">score</span>
                                                </div>
                                                <div class="flex items-center gap-1 text-muted-foreground">
                                                    @svg('lucide-check-circle', 'w-4 h-4 text-green-500')
                                                    <span>{{ $quiz->correct_answers }}/{{ $quiz->total_answered }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-sm text-muted-foreground italic">No answers recorded</p>
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
                                                View Results
                                            </a>
                                        @else
                                            <a
                                                href="{{ route('voice-quiz', ['quiz' => $quiz->id]) }}"
                                                wire:navigate
                                                class="px-4 py-2 bg-primary text-primary-foreground rounded-lg font-medium hover:scale-105 transition-all text-sm inline-flex items-center gap-2"
                                            >
                                                @svg('lucide-play', 'w-4 h-4')
                                                Continue
                                            </a>
                                        @endif

                                        <button
                                            wire:click="deleteQuiz('{{ $quiz->id }}')"
                                            wire:confirm="Are you sure you want to delete this quiz?"
                                            class="px-4 py-2 bg-destructive/10 text-destructive rounded-lg font-medium hover:bg-destructive/20 transition-all text-sm"
                                            title="Delete quiz"
                                        >
                                            @svg('lucide-trash-2', 'w-4 h-4')
                                        </button>
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
    </div>
</div>
