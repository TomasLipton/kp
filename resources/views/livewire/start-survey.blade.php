@section('styles')
    <style>
        .quiz-container {
            margin: 10px; /* Margin for the container */
            padding: 20px; /* Add padding */
            background-color: #fff; /* White background */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Shadow effect */
            text-align: center; /* Center text */
        }

        /* Title Styles */
        .quiz-title {
            font-size: 24px; /* Title font size */
            color: #333; /* Title color */
            margin-bottom: 10px; /* Space below title */
        }

        /* Description Styles */
        .quiz-description {
            font-size: 16px; /* Description font size */
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

        .mode-options {
            text-align: center;
            /*text-align: left; !* Align radio buttons to the left *!*/
            margin-top: 5px; /* Space above the radio buttons */
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
            transition: background-color 0.3s ease; /* Transition for hover effect */
        }

        .start-button:hover {
            background-color: #218838; /* Darker green on hover */
        }

        /* Responsive Styles */
        @media (max-width: 600px) {
            .quiz-container {
                padding: 15px; /* Adjust padding for smaller screens */
            }

            .quiz-title {
                font-size: 20px; /* Smaller title font size */
            }

            .quiz-description {
                font-size: 14px; /* Smaller description font size */
            }

            .questions-available {
                font-size: 16px; /* Smaller font size for questions available */
            }

            .start-button {
                font-size: 16px; /* Smaller font size for button */
            }
        }
    </style>
@endsection
<div>

    <div class="quiz-container mt-5">
        <h1 class="quiz-title">{{$topic->name_pl}}</h1>
        <p class="quiz-description">{{$topic->description_pl}}</p>
        <div class="quiz-info">
            <p class="questions-available">Dostępne pytania: <span class="number">{{$topic->questions->count()}}</span></p>
            <p class="mode-label">Wybierz tryb:</p>
            <div class="mode-options">
                <label>
                    <input type="radio" name="mode" value="easy" checked wire:click="setMode('Wszystkie pytania')"> Wszystkie pytania
                </label>
                <label>
                    <input type="radio" name="mode" value="medium" wire:click="setMode('10 pytań')"> 10 pytań
                </label>
            </div>
        </div>
        <button class="start-button" wire:click="startSurvey">Rozpocznij Test</button><br>
        <small>(  {{$surveyMode}})</small>

    </div>

</div>
