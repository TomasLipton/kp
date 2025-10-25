@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
@endphp

<?php

use App\Models\AIQuiz;
use App\Models\Topics;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app-kp')]
class extends Component {
    public AIQuiz $quiz;

    public function mount(AIQuiz $quiz): void
    {
        $this->quiz = $quiz;
    }

    public function endQuiz(): void
    {
        // Update quiz status to completed
        $this->quiz->update([
            'status' => 'completed'
        ]);

        // Redirect to summary page
        $this->redirect(route('ai-quiz-summary', ['quiz' => $this->quiz->id]));
    }

    public function with(): array
    {
        $appUrl = config('app.url', 'http://localhost');
        $isLocal = str_contains($appUrl, '.test') || str_contains($appUrl, '127.0.0.1');

        $wsUrl = $isLocal
            ? 'ws://localhost:6001'
            : 'wss://quiz-polaka.pl';

        return [
            'topics' => Topics::all(),
            'wsUrl' => $wsUrl,
            'quizId' => $this->quiz->id,
        ];
    }
}; ?>

<section class="py-4">
    <div class="mx-auto relative">
        <div class="overflow-hidden bg-gradient-card backdrop-blur-sm shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)]
                    border border-white/40 rounded-2xl relative
                    before:absolute before:inset-0 before:rounded-2xl before:p-[1px]
                    before:bg-gradient-to-br before:from-white/50 before:via-white/20 before:to-transparent before:-z-10">

            {{-- Header with Status --}}
            <div class="bg-gradient-to-r from-primary/5 via-secondary/5 to-primary/5 border-b border-border/30 p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        @svg('lucide-mic', 'w-5 h-5 text-primary flex-shrink-0')
                        <div>
                            <span class="text-base font-semibold text-foreground">Voice Quiz</span>
                            <p class="text-xs text-muted-foreground">{{ $quiz->topic->name_pl ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2" x-data="{
                        startTime: new Date('{{ $quiz->created_at }}'),
                        timeElapsed: '0:00',
                        updateTimeElapsed() {
                            const totalSeconds = Math.floor((new Date() - this.startTime) / 1000);
                            const minutes = Math.floor(totalSeconds / 60);
                            const seconds = String(totalSeconds % 60).padStart(2, '0');
                            this.timeElapsed = `${minutes}:${seconds}`;
                        }
                    }" x-init="setInterval(() => updateTimeElapsed(), 1000)">
                        @svg('lucide-timer', 'w-5 h-5 text-primary')
                        <span class="text-sm font-medium text-foreground" x-text="timeElapsed"></span>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-3 bg-white/50 backdrop-blur-sm rounded-lg px-3 py-1.5 border border-border/30 w-fit">
                    <div id="statusDot" class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></div>
                    <span id="connectionStatus" class="text-xs font-medium text-foreground">Connecting...</span>
                </div>
            </div>

            {{-- Two Column Layout --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 p-6 sm:p-8">
                {{-- Left Column: Controls --}}
                <div class="space-y-4">
                    {{-- Recording Controls --}}
                    <div class="bg-white/50 rounded-xl border border-border/30 overflow-hidden">
                        <div class="bg-gradient-to-r from-primary/5 to-secondary/5 px-4 py-3 border-b border-border/30">
                            <h3 class="font-semibold text-foreground text-sm flex items-center gap-2">
                                @svg('lucide-circle-dot', 'w-4 h-4 text-red-500')
                                Recording Controls
                            </h3>
                        </div>
                        <div class="p-4 space-y-3">
                            <div class="flex gap-3">
                                <button id="startBtn" disabled
                                        class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-all duration-200 font-medium text-sm flex items-center justify-center gap-2 hover:shadow-md">
                                    @svg('lucide-play', 'w-4 h-4')
                                    Start
                                </button>
                                <button id="stopBtn" disabled
                                        class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-all duration-200 font-medium text-sm flex items-center justify-center gap-2 hover:shadow-md">
                                    @svg('lucide-square', 'w-4 h-4')
                                    Stop
                                </button>
                            </div>
                            <div class="text-center">
                                <span id="spaceHint" class="text-xs text-muted-foreground inline-flex items-center gap-1">
                                    Hold <kbd class="px-2 py-0.5 text-xs font-semibold text-foreground bg-white/80 border border-border/30 rounded">Space</kbd> to record
                                </span>
                                <span id="recordingIndicator" class="hidden text-xs text-red-600 font-semibold inline-flex items-center gap-2">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                    </span>
                                    Recording...
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- End Quiz Button --}}
                    <button
                        wire:click="endQuiz"
                        wire:confirm="Czy na pewno chcesz zakończyć ten quiz? Nie będziesz mógł wrócić."
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-medium
                               bg-red-50 hover:bg-red-100 border border-red-200 hover:border-red-300
                               text-red-700 transition-all duration-200 hover:shadow-md">
                        @svg('lucide-flag', 'w-4 h-4')
                        Zakończ Quiz
                    </button>

                    <audio id="player" class="hidden"></audio>
                </div>

                {{-- Right Column: Chat Log --}}
                <div class="bg-white/50 rounded-xl border border-border/30 overflow-hidden flex flex-col" style="height: calc(100vh - 250px);">
                    <div class="bg-gradient-to-r from-primary/5 to-secondary/5 px-4 py-3 border-b border-border/30">
                        <h3 class="font-semibold text-foreground flex items-center gap-2">
                            @svg('lucide-message-square', 'w-5 h-5 text-primary')
                            Conversation
                        </h3>
                    </div>
                    <div id="chatLog" class="flex-1 p-4 overflow-y-auto bg-gradient-to-br from-primary/5 to-secondary/5">
                        <div id="messages" class="space-y-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@script
<script>
    let ws, mediaRecorder, stopTime;
    const WS_URL = "{{ $wsUrl }}";
    const QUIZ_ID = "{{ $quizId }}";
    let quizSessionId = QUIZ_ID;

    function addMessage(role, text, duration = null) {
        const messagesDiv = document.getElementById('messages');
        const timestamp = new Date().toLocaleTimeString();
        const messageDiv = document.createElement('div');

        // Styling based on role with gradients and shadows
        const roleConfig = {
            'user': {
                bg: 'bg-gradient-to-br from-blue-500 to-blue-600',
                text: 'text-white',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
                align: 'ml-auto',
                rounded: 'rounded-2xl rounded-br-sm'
            },
            'assistant': {
                bg: 'bg-gradient-to-br from-green-500 to-emerald-600',
                text: 'text-white',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>',
                align: 'mr-auto',
                rounded: 'rounded-2xl rounded-bl-sm'
            },
            'system': {
                bg: 'bg-gradient-to-r from-gray-100 to-gray-200',
                text: 'text-gray-700',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>',
                align: 'mx-auto',
                rounded: 'rounded-lg'
            },
            'error': {
                bg: 'bg-gradient-to-br from-red-500 to-red-600',
                text: 'text-white',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>',
                align: 'mx-auto',
                rounded: 'rounded-lg'
            },
            'tool': {
                bg: 'bg-gradient-to-br from-purple-500 to-purple-600',
                text: 'text-white',
                icon: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
                align: 'mx-auto',
                rounded: 'rounded-lg'
            }
        };

        const config = roleConfig[role] || roleConfig['system'];
        const maxWidth = role === 'user' || role === 'assistant' ? 'max-w-[80%]' : 'max-w-full';

        messageDiv.className = `${config.bg} ${config.text} ${config.rounded} p-4 shadow-md ${config.align} ${maxWidth} transform transition-all hover:scale-[1.02]`;

        const durationBadge = duration !== null
            ? `<span class="inline-block bg-white/20 rounded-full px-2 py-0.5 text-xs ml-2">${duration}ms</span>`
            : '';

        messageDiv.innerHTML = `
            <div class="flex items-start gap-2">
                <span class="flex-shrink-0">${config.icon}</span>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium break-words">${text}</div>
                    <div class="text-xs opacity-80 mt-1">${timestamp}${durationBadge}</div>
                </div>
            </div>
        `;

        messagesDiv.appendChild(messageDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    function connectWebSocket() {
        ws = new WebSocket(WS_URL);

        ws.onopen = () => {
            console.log('WebSocket connected');
            document.getElementById('connectionStatus').textContent = 'Connected';
            document.getElementById('statusDot').className = 'w-2 h-2 rounded-full bg-green-400';

            // Initialize session with quiz ID from URL
            ws.send(JSON.stringify({
                type: "INIT_SESSION",
                quiz_session_id: quizSessionId
            }));
        };

        ws.onmessage = (event) => {
            try {
                const data = JSON.parse(event.data);

                if (data.type === 'SESSION_READY') {
                    document.getElementById('startBtn').disabled = false;
                    addMessage('system', `Session loaded with ${data.message_count} messages`);
                } else if (data.type === 'ERROR') {
                    addMessage('error', data.message);
                } else if (data.role === 'user') {
                    addMessage('user', data.text);
                } else if (data.role === 'assistant') {
                    addMessage('assistant', data.text, data.duration);

                    // Decode base64 audio and play
                    const audioData = Uint8Array.from(atob(data.audio), c => c.charCodeAt(0));
                    const blob = new Blob([audioData], {type: "audio/mpeg"});
                    const player = document.getElementById('player');
                    player.src = URL.createObjectURL(blob);
                    player.play();
                } else if (data.role === 'tool') {
                    addMessage('tool', `Called ${data.tool_name}: ${JSON.stringify(data.tool_result)}`);
                }
            } catch (e) {
                console.error('Failed to parse message:', e);
            }
        };

        ws.onerror = (error) => {
            console.error('WebSocket error:', error);
            document.getElementById('connectionStatus').textContent = 'Error';
            document.getElementById('statusDot').className = 'w-2 h-2 rounded-full bg-red-500 animate-pulse';
        };

        ws.onclose = () => {
            console.log('WebSocket disconnected');
            document.getElementById('startBtn').disabled = true;
            document.getElementById('connectionStatus').textContent = 'Disconnected';
            document.getElementById('statusDot').className = 'w-2 h-2 rounded-full bg-gray-400';
        };
    }

    document.getElementById('startBtn').onclick = async () => {
        if (!quizSessionId) {
            alert('Please create or load a session first.');
            return;
        }

        const stream = await navigator.mediaDevices.getUserMedia({audio: true});

        mediaRecorder = new MediaRecorder(stream, {mimeType: 'audio/webm;codecs=opus'});
        mediaRecorder.addEventListener('dataavailable', (e) => {
            if (e.data.size > 0 && ws.readyState === WebSocket.OPEN) {
                ws.send(e.data);
            }
        });

        mediaRecorder.start(250);
        document.getElementById('startBtn').disabled = true;
        document.getElementById('stopBtn').disabled = false;

        // Show recording indicator
        document.getElementById('spaceHint').classList.add('hidden');
        document.getElementById('recordingIndicator').classList.remove('hidden');
    };

    document.getElementById('stopBtn').onclick = () => {
        stopRecording();
    };

    function stopRecording() {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
            stopTime = Date.now();
            if (ws && ws.readyState === WebSocket.OPEN) {
                ws.send("END_RECORDING");
            }
            document.getElementById('startBtn').disabled = false;
            document.getElementById('stopBtn').disabled = true;

            // Hide recording indicator
            document.getElementById('spaceHint').classList.remove('hidden');
            document.getElementById('recordingIndicator').classList.add('hidden');
        }
    }

    // Space bar hold to record
    let isSpacePressed = false;

    document.addEventListener('keydown', async (e) => {
        // Check if space bar and not already pressed (to avoid repeat events)
        if (e.code === 'Space' && !isSpacePressed) {
            // Prevent space from scrolling the page
            e.preventDefault();

            // Only start if we have a session and start button is enabled
            if (!quizSessionId || document.getElementById('startBtn').disabled) {
                return;
            }

            isSpacePressed = true;

            // Start recording
            const stream = await navigator.mediaDevices.getUserMedia({audio: true});

            mediaRecorder = new MediaRecorder(stream, {mimeType: 'audio/webm;codecs=opus'});
            mediaRecorder.addEventListener('dataavailable', (e) => {
                if (e.data.size > 0 && ws.readyState === WebSocket.OPEN) {
                    ws.send(e.data);
                }
            });

            mediaRecorder.start(250);
            document.getElementById('startBtn').disabled = true;
            document.getElementById('stopBtn').disabled = false;

            // Show recording indicator
            document.getElementById('spaceHint').classList.add('hidden');
            document.getElementById('recordingIndicator').classList.remove('hidden');
        }
    });

    document.addEventListener('keyup', (e) => {
        if (e.code === 'Space' && isSpacePressed) {
            e.preventDefault();
            isSpacePressed = false;
            stopRecording();

            // Hide recording indicator
            document.getElementById('spaceHint').classList.remove('hidden');
            document.getElementById('recordingIndicator').classList.add('hidden');
        }
    });

    // Auto-connect on page load
    window.onload = () => {
        connectWebSocket();
    };
</script>

@endscript
