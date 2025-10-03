<?php

use App\Models\Topics;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use OpenAI\Laravel\Facades\OpenAI;

new #[Layout('layouts.app-kp')]
class extends Component {
    public string $speed = 'normal';
    public string $difficulty = 'medium';
    public string $gender = 'female';
    public ?int $topic_id = null;
    public bool $isAdmin = false;

    public function mount()
    {
        $this->isAdmin = auth()->check() && auth()->user()?->is_admin;
    }

    public function with(): array
    {
        return [
            'topics' => Topics::whereNull('parent_id')->get(),
        ];
    }

    public function startQuiz()
    {
     $validated =    $this->validate([
            'speed' => 'required|in:slow,normal,fast',
            'difficulty' => 'required|in:easy,medium,hard',
            'gender' => 'required|in:male,female',
            'topic_id' => 'required|exists:topics,id',
        ]);

        $topic = Topics::find($this->topic_id);

        $prompt = <<<TEXT

You are a Polish Language Inspector conducting the "Karta Polaka" oral exam.
Your name is Barbara.
You must speak only in Polish.

Rules:
1. Always correct the user's mistakes politely in Polish.
2. Never respond in any language other than Polish, even if the user uses another language.
3. Act professionally, as a real examiner.
4. Start the conversation immediately when the session begins.
5. Make the conversation natural, but include questions and prompts typical for the "Karta Polaka" exam.

Topic emphasis:
- Focus on the topic specified by the variable $topic->name_pl.
- Always try to guide the conversation towards this topic while staying natural.

Instructions for first message:
- Begin with a greeting as an examiner, introduce yourself, and explain that this is a test mode.
- Ask the first question related to $topic->name_pl.



TEXT;

        $voices = [
            'male' => [

                'ash',
            ],
            'female' => [
//                'ash',
//                'ballad',
//                'coral',
//                'echo',
//                'sage',
                'alloy',
                'shimmer',
            ],
        ];

        $selectedVoice = $voices[$this->gender][array_rand($voices[$this->gender])];

        $response = OpenAI::realtime()->token([
            'instructions' => $prompt,
            'model' => 'gpt-4o-realtime-preview-2024-12-17',
            'voice' => $selectedVoice,
        ]);

        // Create AIQuiz record
        $aiQuiz = \App\Models\AIQuiz::create([
            'user_id' => auth()->id(),
            'topic_id' => $this->topic_id,
            'speed' => $this->speed,
            'difficulty' => $this->difficulty,
            'gender' => $this->gender,
            'status' => 'preparing',
            'ephemeral_key' =>  $response->clientSecret->value,
            'ephemeral_key_expiry' =>  $response->clientSecret->expiresAt,
        ]);

        // Redirect to AI quiz session
        $this->redirect(route('ai', ['quiz' => $aiQuiz->id]));
    }
}; ?>

@assets
<style>
    .quiz-config-card {
        background: linear-gradient(135deg, hsl(var(--card) / 0.8) 0%, hsl(var(--card) / 0.95) 100%);
        backdrop-filter: blur(20px);
    }

    .topic-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .topic-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    }

    .topic-card.selected {
        border-color: hsl(var(--primary));
        background: linear-gradient(135deg, hsl(var(--primary) / 0.1) 0%, hsl(var(--primary) / 0.05) 100%);
        box-shadow: 0 0 0 3px hsl(var(--primary) / 0.2);
    }

    .option-card {
        transition: all 0.2s ease;
    }

    .option-card:hover {
        transform: scale(1.02);
    }

    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px hsl(var(--primary) / 0.3); }
        50% { box-shadow: 0 0 30px hsl(var(--primary) / 0.5); }
    }

    .start-button:hover {
        animation: pulse-glow 2s ease-in-out infinite;
    }
</style>
@endassets

<div>
@if(!$isAdmin)
    <x-under-construction />
@else
<div class="min-h-screen py-12 px-4">
    <div class="max-w-5xl mx-auto">
        {{-- Header --}}
        <div class="text-center mb-12 space-y-4">
            <div class="inline-flex items-center gap-3 px-4 py-2 bg-primary/10 rounded-full mb-4">
                @svg('lucide-mic', 'w-5 h-5 text-primary')
                <span class="text-sm font-semibold text-primary">AI-Powered Voice Quiz</span>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold bg-gradient-primary bg-clip-text text-transparent">
                Configure Your Session
            </h1>
            <p class="text-lg md:text-xl text-muted-foreground max-w-2xl mx-auto">
                Choose your topic, difficulty level, and speaking speed for an interactive AI quiz experience
            </p>
        </div>

        {{-- How it works --}}
        <div class="relative p-5 bg-gradient-to-br from-primary/5 via-primary/10 to-primary/5 rounded-xl border border-primary/20 mb-8 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full blur-3xl"></div>
            <div class="relative flex gap-4">
                <div class="flex-shrink-0 p-2 bg-primary/10 rounded-lg flex items-center justify-center">
                    @svg('lucide-lightbulb', 'w-5 h-5 text-primary')
                </div>
                <div class="flex-1">
                    <p class="font-bold text-foreground mb-2 flex items-center gap-2">
                        How it works
                        <span class="text-xs px-2 py-0.5 bg-primary/20 text-primary rounded-full">Quick guide</span>
                    </p>
                    <div class="grid md:grid-cols-3 gap-3 text-sm">
                        <div class="flex items-start gap-2">
                            <span class="flex-shrink-0 w-5 h-5 bg-primary/20 text-primary rounded-full flex items-center justify-center text-xs font-bold">1</span>
                            <span class="text-muted-foreground">AI asks questions about your topic</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="flex-shrink-0 w-5 h-5 bg-primary/20 text-primary rounded-full flex items-center justify-center text-xs font-bold">2</span>
                            <span class="text-muted-foreground">Respond using your voice</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="flex-shrink-0 w-5 h-5 bg-primary/20 text-primary rounded-full flex items-center justify-center text-xs font-bold">3</span>
                            <span class="text-muted-foreground">Get instant feedback</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Configuration Form --}}
        <form wire:submit.prevent="startQuiz" class="space-y-8">
            {{-- Visual Banner --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-primary/10 via-primary/5 to-primary/10 border border-primary/20">
                <div class="grid md:grid-cols-2 gap-6 items-center p-8">
                    <div>
                        <h3 class="text-2xl font-bold mb-3">Talk with AI Examiner</h3>
                        <p class="text-muted-foreground mb-4">Experience a realistic interview simulation with our AI voice assistant. Practice your Polish knowledge in a natural conversation setting.</p>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2 text-sm">
                                @svg('lucide-mic', 'w-4 h-4 text-primary')
                                <span class="text-muted-foreground">Voice Recognition</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                @svg('lucide-brain', 'w-4 h-4 text-primary')
                                <span class="text-muted-foreground">AI-Powered</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                @svg('lucide-check-circle', 'w-4 h-4 text-primary')
                                <span class="text-muted-foreground">Real-time</span>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:flex justify-center items-center">
                        <div class="relative">
                            <div class="absolute inset-0 bg-primary/20 rounded-full blur-3xl"></div>
                            <div class="relative bg-gradient-to-br from-primary/20 to-primary/5 rounded-2xl p-8 border border-primary/30">
                                @svg('lucide-user-round-search', 'w-32 h-32 text-primary/70')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- All Settings in One Card --}}
            <div class="quiz-config-card rounded-2xl p-8 border border-border/50 shadow-xl space-y-6">
                {{-- Topic --}}
                <div class="grid md:grid-cols-[200px,1fr] gap-6 items-start">
                    <label class="flex items-center gap-2 text-lg font-semibold pt-2">
                        @svg('lucide-book-open', 'w-5 h-5 text-primary')
                        Topic
                    </label>
                    <div>
                        <select
                            wire:model.live="topic_id"
                            class="w-full px-4 py-3 bg-background border-2 border-border rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all font-medium"
                        >
                            <option value="">Choose a topic...</option>
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}">
                                    {{ $topic->{'name_' . app()->getLocale()} ?? $topic->name_pl }} ({{ $topic->questions()->count() }} questions)
                                </option>
                            @endforeach
                        </select>
                        @error('topic_id')
                            <p class="mt-2 text-sm text-destructive flex items-center gap-2">
                                @svg('lucide-alert-circle', 'w-4 h-4')
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Difficulty --}}
                <div class="grid md:grid-cols-[200px,1fr] gap-6 items-start">
                    <label class="flex items-center gap-2 text-lg font-semibold pt-2">
                        @svg('lucide-gauge', 'w-5 h-5 text-primary')
                        Difficulty
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="difficulty" value="easy" wire:model.live="difficulty" class="peer sr-only">
                            <div class="p-3 bg-background border-2 border-border rounded-lg text-center peer-checked:border-primary peer-checked:bg-primary/10 transition-all">
                                <div class="font-semibold text-sm">Easy</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="difficulty" value="medium" wire:model.live="difficulty" class="peer sr-only">
                            <div class="p-3 bg-background border-2 border-border rounded-lg text-center peer-checked:border-primary peer-checked:bg-primary/10 transition-all">
                                <div class="font-semibold text-sm">Medium</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="difficulty" value="hard" wire:model.live="difficulty" class="peer sr-only">
                            <div class="p-3 bg-background border-2 border-border rounded-lg text-center peer-checked:border-primary peer-checked:bg-primary/10 transition-all">
                                <div class="font-semibold text-sm">Hard</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Speed --}}
                <div class="grid md:grid-cols-[200px,1fr] gap-6 items-start">
                    <label class="flex items-center gap-2 text-lg font-semibold pt-2">
                        @svg('lucide-audio-lines', 'w-5 h-5 text-primary')
                        Speed
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="speed" value="slow" wire:model.live="speed" class="peer sr-only">
                            <div class="p-3 bg-background border-2 border-border rounded-lg text-center peer-checked:border-primary peer-checked:bg-primary/10 transition-all">
                                <div class="font-semibold text-sm">Slow</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="speed" value="normal" wire:model.live="speed" class="peer sr-only">
                            <div class="p-3 bg-background border-2 border-border rounded-lg text-center peer-checked:border-primary peer-checked:bg-primary/10 transition-all">
                                <div class="font-semibold text-sm">Normal</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="speed" value="fast" wire:model.live="speed" class="peer sr-only">
                            <div class="p-3 bg-background border-2 border-border rounded-lg text-center peer-checked:border-primary peer-checked:bg-primary/10 transition-all">
                                <div class="font-semibold text-sm">Fast</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Gender --}}
                <div class="grid md:grid-cols-[200px,1fr] gap-6 items-start">
                    <label class="flex items-center gap-2 text-lg font-semibold pt-2">
                        @svg('lucide-user', 'w-5 h-5 text-primary')
                        Voice Gender
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="gender" value="female" wire:model.live="gender" class="peer sr-only">
                            <div class="p-3 bg-background border-2 border-border rounded-lg text-center peer-checked:border-primary peer-checked:bg-primary/10 transition-all">
                                <div class="font-semibold text-sm">Female</div>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="gender" value="male" wire:model.live="gender" class="peer sr-only">
                            <div class="p-3 bg-background border-2 border-border rounded-lg text-center peer-checked:border-primary peer-checked:bg-primary/10 transition-all">
                                <div class="font-semibold text-sm">Male</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Start Button or Login Prompt --}}
                @auth
                    <button
                        type="submit"
                        class="start-button w-full group relative px-8 py-4 bg-gradient-primary text-primary-foreground rounded-xl font-bold text-lg hover:scale-[1.02] transition-all duration-300 shadow-xl"
                    >
                        <span class="flex items-center justify-center gap-3">
                            @svg('lucide-play-circle', 'w-5 h-5')
                            Start AI Quiz Session
                            @svg('lucide-arrow-right', 'w-5 h-5 group-hover:translate-x-1 transition-transform')
                        </span>
                    </button>
                @else
                    <div class="text-center p-6 bg-muted/30 rounded-xl border border-border">
                        <div class="mb-4">
                            @svg('lucide-lock', 'w-12 h-12 mx-auto text-muted-foreground')
                        </div>
                        <h3 class="font-bold text-lg mb-2">Login Required</h3>
                        <p class="text-sm text-muted-foreground mb-4">Please log in to start your AI quiz session</p>
                        <div class="flex gap-3 justify-center">
                            <a
                                href="{{ route('login') }}"
                                wire:navigate
                                class="inline-flex items-center gap-2 px-6 py-3 bg-background border-2 border-border text-foreground rounded-lg font-semibold hover:scale-105 transition-all"
                            >
                                @svg('lucide-log-in', 'w-5 h-5')
                                Log In
                            </a>
                            <a
                                href="{{ route('register') }}"
                                wire:navigate
                                class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-primary text-primary-foreground rounded-lg font-semibold hover:scale-105 transition-all"
                            >
                                @svg('lucide-user-plus', 'w-5 h-5')
                                Register
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </form>
    </div>
</div>
@endif
</div>
