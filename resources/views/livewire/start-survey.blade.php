@assets
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
        }
    </style>
@endassets
<div>
    <div class="quiz-container">
        <h1 class="quiz-title">{{$topic->name_pl}}</h1>
        <p class="quiz-description">{{$topic->description_pl}}</p>
        <div class="quiz-info">
            <p class="questions-available">
                Dostępne   @php
                    $count = $topic->questions()->count();
                @endphp
                @if($count == 1) pytanie: @elseif($count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20)) pytania: @else pytań: @endif
                <b class="number">{{$topic->questions->count()}}</b></p>
            <p class="mode-label">Wybierz tryb:</p>
            <div class="mode-options">
                <label class="mode-option" wire:click="setMode('Wszystkie pytania')">
                    <input type="radio" name="mode" value="easy"  checked>
                    <span class="radio-custom"></span>
                    Wszystkie pytania
                </label>
                <label class="mode-option"
{{--                       wire:click="setMode('10 pytań')"--}}
style="color: #9ca3af"
                >
                    <input type="radio" name="mode"  disabled>
                    <span class="radio-custom" ></span>
                    10 pytań (wkrótce)
                </label>
            </div>
        </div>
        <button class="start-button" wire:click="startSurvey">Rozpocznij Quiz</button>
        <br>
    </div>
</div>
