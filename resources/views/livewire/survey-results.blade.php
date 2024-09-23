@assets
    <style>
        .results-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Title Styles */
        .results-container h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        /* Result Item Styles */
        .result-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .result-item:last-child {
            border-bottom: none; /* Remove border from last item */
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .value {
            color: #333;
        }

        /* Button Styles */
        .retry-button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            text-align: center;
            text-decoration: none;
        }

        .retry-button:hover {
            background-color: #0056b3;
            text-decoration: underline;
        }

        /* Responsive Styles */
        @media (max-width: 600px) {
            .results-container {
                padding: 15px;
            }

            .result-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .result-item .label {
                margin-bottom: 5px; /* Add space between label and value */
            }

            .retry-button {
                font-size: 14px; /* Smaller button text on small screens */
            }
        }
    </style>
@endassets
<div style="padding: 15px 10px">
    <div class="results-container">
        <h1>Wyniki testu</h1>
        <div class="result-item">
            <span class="label">Czas zajęty:</span>
            <span class="value">{{$quiz->created_at->diff($quiz->completed_at)}}</span>
        </div>
        <div class="result-item">
            <span class="label">Razem pytania:</span>
            <span class="value">{{$quiz->answers->count()}}</span>
        </div>
        <div class="result-item">
            <span class="label">Prawidłowe odpowiedzi:</span>
            <span class="value" style="color: #00bb00">{{$quiz->answers->filter(function ($answer) { return $answer->questionAnswer->is_correct; })->count()}}</span>
        </div>
        <div class="result-item">
            <span class="label">Błędne odpowiedzi:</span>
            <span class="value" style="color: #dd4444">{{$quiz->answers->filter(function ($answer) { return !$answer->questionAnswer->is_correct; })->count()}}</span>
        </div>
        <div class="result-item">
            <span class="label">Data:</span>
            <span class="value">{{$quiz->created_at->translatedFormat('F j, H:i') }}</span>
        </div>
        <a href="/" class="retry-button" wire:navigate wire:navigate.hover>Powtórz test</a>
    </div>
</div>
