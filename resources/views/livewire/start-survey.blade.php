@script

<script>
    lucide.createIcons();
</script>

@endscript
@assets
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

<style>
    /* Quiz Container Styles */
    .quiz-container {
        margin: 35px 10px;
        padding: 20px; /* Add padding */
        background-color: #fff; /* White background */
        border-radius: 8px; /* Rounded corners */
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Shadow effect */
        text-align: center; /* Center text */
    }

    /* Title Styles */
    .quiz-title {
        font-size: 28px; /* Title font size */
        color: #333; /* Title color */
        margin-bottom: 15px; /* Space below title */
    }

    /* Description Styles */
    .quiz-description {
        font-size: 18px; /* Description font size */
        color: #666; /* Description color */
        margin-bottom: 20px; /* Space below description */
    }

    /* Quiz Info Styles */
    .quiz-info {
        margin-bottom: 20px; /* Space below info section */
    }

    /* Questions Available Styles */
    .questions-available {
        font-size: 18px; /* Font size for questions available */
        color: #444; /* Color for questions available */
    }

    /* Mode Options Styles */
    .mode-label {
        display: block; /* Stack label above the options */
        margin-top: 15px; /* Space above the label */
        font-size: 16px; /* Font size for label */
    }

    /* Radio Button Styles */
    .mode-options {
        display: flex; /* Flexbox for horizontal layout */
        justify-content: center; /* Center options horizontally */
        margin-top: 5px; /* Space above the radio buttons */
    }

    .mode-option {
        display: flex; /* Flexbox for better alignment */
        align-items: center; /* Center items vertically */
        margin-right: 20px; /* Space between options */
        cursor: pointer; /* Pointer cursor on hover */
        position: relative; /* Position for custom radio */
    }

    .mode-option input[type="radio"] {
        display: none; /* Hide the default radio button */
    }

    .radio-custom {
        width: 20px; /* Custom radio button width */
        height: 20px; /* Custom radio button height */
        border: 2px solid #007BFF; /* Border color */
        border-radius: 50%; /* Make it circular */
        margin-right: 10px; /* Space between radio and label */
        transition: background-color 0.3s ease; /* Transition for hover effect */
    }

    .mode-option input[type="radio"]:checked + .radio-custom {
        background-color: #007BFF; /* Change background when checked */
    }

    /* Button Styles */
    .start-button {
        padding: 15px; /* Padding for button */
        background-color: #28a745; /* Green background */
        color: #fff; /* White text */
        border: none; /* No border */
        border-radius: 5px; /* Rounded corners */
        cursor: pointer; /* Pointer cursor on hover */
        font-size: 18px; /* Font size for button */
        transition: background-color 0.3s ease, transform 0.3s ease; /* Transition for hover effect */
    }

    .start-button:hover {
        background-color: #218838; /* Darker green on hover */
        transform: translateY(-2px); /* Lift effect on hover */
    }

    /* Responsive Styles */
    @media (max-width: 600px) {
        .quiz-container {
            padding: 15px; /* Adjust padding for smaller screens */
        }

        .quiz-title {
            font-size: 24px; /* Smaller title font size */
        }

        .quiz-description {
            font-size: 16px; /* Smaller description font size */
        }

        .questions-available {
            font-size: 16px; /* Smaller font size for questions available */
        }

        .start-button {
            font-size: 16px; /* Smaller font size for button */
        }

        /* Force text wrapping on mobile */
        .mode-button div {
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }

        #current-mode-desc {
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }
    }
</style>
<style>
    .app_container {
        /*background: unset;*/
        /*box-shadow: unset;*/
    }
</style>
@endassets

<div>
    <div class="min-h-screen_  py-8 ">
        <div class="container max-w-[1200px]_ mx-auto">
            <div class="overflow-hidden bg-card shadow-card border border-border/50 rounded-lg">
                <!-- Quiz Image -->
                <div class="relative h-48 overflow-hidden">
                    <img
                        id="quiz-image"
                        src="{{url('storage/' . $topic->picture)}}"
                        alt="Quiz Title"
                        class="w-full h-full object-cover"
                    />
                    <div class="absolute top-4 right-4">
                        <span
                            id="difficulty-badge"
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-500/90 text-white border-0 backdrop-blur-sm"
                        >
                            Średni
                        </span>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="p-6">
                    <!-- Quiz Title -->
                    <h1 id="quiz-title" class="text-2xl md:text-3xl font-bold text-foreground mb-3">
                        {{$topic->name_pl}}
                    </h1>

                    <!-- Description -->
                    <p id="quiz-description" class="text-muted-foreground leading-relaxed mb-6">
                        {{$topic->description_pl}}                    </p>

                    <!-- Stats Row -->
                    <div class="flex items-center gap-4 mb-6 text-sm text-muted-foreground">
                        <div class="flex items-center gap-1">
                            <i data-lucide="book-open" class="w-4 h-4"></i>
                            <span id="quiz-questions">
     {{$topic->questions->count()}}
                                @php
                                    $count = $topic->questions()->count();
                                @endphp

                                @if($count == 1)
                                    pytanie
                                @elseif($count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20))
                                    pytania
                                @else
                                    pytań
                                @endif

                            </span>
                        </div>
                        <div class="flex items-center gap-1">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                            <span id="quiz-duration">30 min</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <i data-lucide="star" class="w-4 h-4 text-quiz-warning fill-current"></i>
                            <span id="quiz-rating">4.5</span>
                        </div>
                    </div>

                    <!-- Mode Selector -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-foreground mb-3">Wybierz tryb:</h3>
                        <div class="grid grid-cols-1 gap-2">
                            <button
                                data-mode="all"
                                class="mode-button p-3 rounded-lg border-2 text-left transition-all border-primary bg-primary/5 text-foreground"
                            >
                                <div class="font-medium">Wszystkie pytania</div>
                                <div class="text-sm opacity-75">Ukończ quiz ze wszystkimi pytaniami</div>
                            </button>
                            {{--                            <button--}}
                            {{--                                data-mode="timed"--}}
                            {{--                                class="mode-button p-3 rounded-lg border-2 text-left transition-all border-border bg-secondary/30 text-muted-foreground hover:border-primary/50"--}}
                            {{--                            >--}}
                            {{--                                <div class="font-medium">Tryb czasowy</div>--}}
                            {{--                                <div class="text-sm opacity-75">Wyścig z czasem</div>--}}
                            {{--                            </button>--}}
                            {{--                            <button--}}
                            {{--                                data-mode="practice"--}}
                            {{--                                class="mode-button p-3 rounded-lg border-2 text-left transition-all border-border bg-secondary/30 text-muted-foreground hover:border-primary/50"--}}
                            {{--                            >--}}
                            {{--                                <div class="font-medium">Tryb ćwiczeń</div>--}}
                            {{--                                <div class="text-sm opacity-75">Bez limitu czasu, ucz się we własnym tempie</div>--}}
                            {{--                            </button>--}}
                        </div>
                    </div>

                    <!-- Selected Mode Info -->
                    <div class="bg-primary/5 border border-primary/20 rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-2 text-sm">
                            <i data-lucide="book-open" class="w-4 h-4 text-primary"></i>
                            <span id="current-mode-questions" class="font-medium text-foreground">
                               {{$topic->questions->count()}}  @if($count == 1)
                                    pytanie
                                @elseif($count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20))
                                    pytania
                                @else
                                    pytań
                                @endif
                            </span>
                            <span class="text-muted-foreground">•</span>
                            <span id="current-mode-desc" class="text-muted-foreground">
                                Ukończ quiz ze wszystkimi pytaniami
                            </span>
                        </div>
                    </div>

                    <!-- Start Button -->
                    <button
                        id="start-button"
                        wire:click="startSurvey"
                        class="w-full bg-gradient-primary hover:shadow-glow transition-all duration-300 text-lg text-white font-medium
           whitespace-nowrap h-11 rounded-md px-8 flex items-center justify-center"
                    >
                        <i data-lucide="play" class="w-5 h-5 mr-2 fill-current"></i>

                        <span class="block sm:hidden">Rozpocznij</span>
                        <span class="hidden sm:block">Rozpocznij wszystkie pytania</span>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>
