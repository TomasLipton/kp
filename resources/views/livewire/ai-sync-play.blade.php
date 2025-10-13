@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
@endphp

<?php

use App\Models\AIQuiz;
use App\Models\Topics;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app-kp')] class extends Component
{
    public AIQuiz $quiz;

    public function mount(AIQuiz $quiz): void
    {
        $this->quiz = $quiz;
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


    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-light text-gray-900 mb-2">🎤 Voice Stream</h2>
            <p class="text-sm text-gray-500">Real-time voice conversation interface</p>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column: Controls -->
            <div class="space-y-6">
                <!-- Session Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Session Info</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quiz ID</label>
                            <div class="text-sm text-gray-900 font-mono bg-gray-50 px-3 py-2 rounded-md border border-gray-200">
                                {{ $quiz->id }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Topic</label>
                            <div class="text-sm text-gray-900 px-3 py-2">
                                {{ $quiz->topic->name_pl ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Connection Status -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Connection Status</span>
                        <span id="connectionStatus" class="text-sm text-gray-500">Connecting...</span>
                    </div>
                </div>

                <!-- Recording Controls -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recording</h3>
                    <div class="flex gap-3 mb-3">
                        <button id="startBtn" disabled
                                class="flex-1 px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors font-medium">
                            Start Recording
                        </button>
                        <button id="stopBtn" disabled
                                class="flex-1 px-6 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors font-medium">
                            Stop Recording
                        </button>
                    </div>
                    <div class="text-center">
                        <span id="spaceHint" class="text-xs text-gray-500">
                            💡 Hold <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 border border-gray-200 rounded">Space</kbd> to record
                        </span>
                        <span id="recordingIndicator" class="hidden text-xs text-red-600 font-medium animate-pulse">
                            🔴 Recording...
                        </span>
                    </div>
                </div>

                <audio id="player" class="hidden"></audio>
            </div>

            <!-- Right Column: Chat Log -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden h-full flex flex-col">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Chat Log</h3>
                </div>
                <div id="chatLog" class="flex-1 p-4 overflow-y-auto" style="min-height: 600px; max-height: calc(100vh - 200px);">
                    <div id="messages" class="space-y-3"></div>
                </div>
            </div>
        </div>
    </div>

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

        // Styling based on role
        const colorClasses = {
            'user': 'bg-blue-50 border-blue-200',
            'assistant': 'bg-green-50 border-green-200',
            'system': 'bg-gray-50 border-gray-200',
            'error': 'bg-red-50 border-red-200',
            'tool': 'bg-purple-50 border-purple-200'
        };

        const textColorClasses = {
            'user': 'text-blue-900',
            'assistant': 'text-green-900',
            'system': 'text-gray-900',
            'error': 'text-red-900',
            'tool': 'text-purple-900'
        };

        messageDiv.className = `p-3 rounded-md border ${colorClasses[role] || 'bg-gray-50 border-gray-200'}`;

        const durationText = duration !== null ? ` <span class="text-gray-500">(${duration}ms)</span>` : '';
        messageDiv.innerHTML = `
            <div class="font-medium text-xs uppercase ${textColorClasses[role] || 'text-gray-900'} mb-1">${role}</div>
            <div class="text-sm text-gray-800 mb-1">${text}</div>
            <div class="text-xs text-gray-500">${timestamp}${durationText}</div>
        `;

        messagesDiv.appendChild(messageDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    function connectWebSocket() {
        ws = new WebSocket(WS_URL);

        ws.onopen = () => {
            console.log('WebSocket connected');
            document.getElementById('connectionStatus').textContent = 'Connected';
            document.getElementById('connectionStatus').className = 'text-sm text-green-600 font-medium';

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
                    const blob = new Blob([audioData], { type: "audio/mpeg" });
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
            document.getElementById('connectionStatus').className = 'text-sm text-red-600 font-medium';
        };

        ws.onclose = () => {
            console.log('WebSocket disconnected');
            document.getElementById('startBtn').disabled = true;
            document.getElementById('connectionStatus').textContent = 'Disconnected';
            document.getElementById('connectionStatus').className = 'text-sm text-gray-500';
        };
    }

    document.getElementById('startBtn').onclick = async () => {
        if (!quizSessionId) {
            alert('Please create or load a session first.');
            return;
        }

        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });

        mediaRecorder = new MediaRecorder(stream, { mimeType: 'audio/webm;codecs=opus' });
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
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });

            mediaRecorder = new MediaRecorder(stream, { mimeType: 'audio/webm;codecs=opus' });
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
