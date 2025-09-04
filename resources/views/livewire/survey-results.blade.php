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
            width: 50%;
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
            /*text-decoration: underline;*/
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


        @layer base {
            :root {
                /* Polish flag inspired theme - light and modern */
                --background: 0 0% 99%;
                --foreground: 0 0% 12%;

                --card: 0 0% 100%;
                --card-foreground: 0 0% 12%;

                --popover: 0 0% 100%;
                --popover-foreground: 0 0% 12%;

                /* Polish red as primary - modern and elegant */
                /*--primary: 348 75% 60%;*/
                /*--primary-foreground: 0 0% 100%;*/

                --secondary: 0 0% 96%;
                --secondary-foreground: 0 0% 20%;

                --muted: 0 0% 97%;
                --muted-foreground: 0 0% 45%;

                /* Light red accent for Polish theme */
                --accent: 348 50% 95%;
                --accent-foreground: 348 75% 60%;

                --destructive: 0 84% 60%;
                --destructive-foreground: 0 0% 98%;

                --border: 0 0% 90%;
                --input: 0 0% 90%;
                --ring: 348 75% 60%;

                --radius: 0.75rem;

                /* Polish theme design tokens */
                /*--gradient-primary: linear-gradient(135deg, hsl(348 75% 60%), hsl(348 65% 70%));*/
                /*--gradient-primary: linear-gradient(135deg, hsl(220 70% 45%), hsl(220 65% 60%));*/

                --gradient-card: linear-gradient(135deg, hsl(0 0% 100%), hsl(348 20% 97%));
                --quiz-success: 145 65% 50%;
                --quiz-warning: 35 85% 55%;
                --quiz-info: 348 40% 70%;
                --shadow-glow: 0 10px 40px -10px hsl(348 75% 60% / 0.25);
                --shadow-card: 0 4px 20px -4px hsl(0 0% 0% / 0.08);
                --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);

                --sidebar-background: 0 0% 98%;

                --sidebar-foreground: 240 5.3% 26.1%;

                --sidebar-primary: 240 5.9% 10%;

                --sidebar-primary-foreground: 0 0% 98%;

                --sidebar-accent: 240 4.8% 95.9%;

                --sidebar-accent-foreground: 240 5.9% 10%;

                --sidebar-border: 220 13% 91%;

                --sidebar-ring: 217.2 91.2% 59.8%;
            }

            .dark {
                --background: 222.2 84% 4.9%;
                --foreground: 210 40% 98%;

                --card: 222.2 84% 4.9%;
                --card-foreground: 210 40% 98%;

                --popover: 222.2 84% 4.9%;
                --popover-foreground: 210 40% 98%;

                --primary: 210 40% 98%;
                --primary-foreground: 222.2 47.4% 11.2%;

                --secondary: 217.2 32.6% 17.5%;
                --secondary-foreground: 210 40% 98%;

                --muted: 217.2 32.6% 17.5%;
                --muted-foreground: 215 20.2% 65.1%;

                --accent: 217.2 32.6% 17.5%;
                --accent-foreground: 210 40% 98%;

                --destructive: 0 62.8% 30.6%;
                --destructive-foreground: 210 40% 98%;

                --border: 217.2 32.6% 17.5%;
                --input: 217.2 32.6% 17.5%;
                --ring: 212.7 26.8% 83.9%;
                --sidebar-background: 240 5.9% 10%;
                --sidebar-foreground: 240 4.8% 95.9%;
                --sidebar-primary: 224.3 76.3% 48%;
                --sidebar-primary-foreground: 0 0% 100%;
                --sidebar-accent: 240 3.7% 15.9%;
                --sidebar-accent-foreground: 240 4.8% 95.9%;
                --sidebar-border: 240 3.7% 15.9%;
                --sidebar-ring: 217.2 91.2% 59.8%;
            }
        }
    </style>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

@endassets
@script
<script>
    lucide.createIcons();
</script>

@endscript
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
        <div style="display: flex; gap: 10px;">
            <button
                class="retry-button_ mt-5 bg-primary-500 hover:shadow-glow transition-all duration-300 text-lg text-white font-medium
           h-11 rounded-md px-8 w-full flex items-center justify-center"
                style="background: #2657b2"
                wire:navigate wire:navigate.hover
            >
                <i data-lucide="list" class="w-5 h-5 mr-2 inline"></i>
                Wszystkie testy
            </button>

            <button
                class="retry-button_ mt-5 hover:shadow-glow transition-all duration-300 text-lg text-white font-medium
           h-11 rounded-md px-8 w-full flex items-center justify-center"
                style="background: #2e7d32"
                wire:navigate wire:navigate.hover
                href="/{{$quiz->topics->slug}}"
            >
                <i data-lucide="refresh-cw" class="w-5 h-5 mr-2 inline"></i>
                Powtórz test
            </button>

        </div>
    </div>
</div>
