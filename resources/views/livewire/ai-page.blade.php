@if(!auth()->check() || !auth()->user()?->is_admin)
    <x-under-construction/>
@else
    @assets
    <style>
        .status-indicator {
            display: inline-block;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            transition: all 0.3s ease;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
        }

        .status-disconnected {
            background-color: #ef4444;
        }

        .status-connecting {
            background-color: #f59e0b;
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .status-connected {
            background-color: #10b981;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }

        /* Message bubbles */
        .message-user {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 12px 16px;
            border-radius: 18px 18px 4px 18px;
            margin-left: auto;
            max-width: 80%;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .message-assistant {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            color: #1f2937;
            padding: 12px 16px;
            border-radius: 18px 18px 18px 4px;
            margin-right: auto;
            max-width: 80%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .dark .message-assistant {
            background: linear-gradient(135deg, #374151 0%, #4b5563 100%);
            color: #f3f4f6;
        }
    </style>
    @endassets
    @script
    <script>
        Livewire.hook('component.init', ({component, cleanup}) => {


        });

        let peerConnection = null;
        let dataChannel = null;
        let audioElement = null;

        const statusIndicator = document.getElementById('statusIndicator');
        const statusText = document.getElementById('statusText');
        const connectBtn = document.getElementById('connectBtn');
        const disconnectBtn = document.getElementById('disconnectBtn');
        const transcript = document.getElementById('transcript');

        function updateStatus(status) {
            statusIndicator.className = 'status-indicator';
            const statusPing = document.getElementById('statusPing');

            switch (status) {
                case 'connected':
                    statusIndicator.classList.add('status-connected');
                    statusText.textContent = 'Exam in Progress';
                    connectBtn.disabled = true;
                    disconnectBtn.disabled = false;
                    if (statusPing) statusPing.style.display = 'none';
                    break;
                case 'connecting':
                    statusIndicator.classList.add('status-connecting');
                    statusText.textContent = 'Connecting...';
                    connectBtn.disabled = true;
                    disconnectBtn.disabled = true;
                    if (statusPing) {
                        statusPing.style.display = 'block';
                        statusPing.className = 'absolute inset-0 rounded-full animate-ping opacity-75 bg-amber-500';
                    }
                    break;
                case 'disconnected':
                    statusIndicator.classList.add('status-disconnected');
                    statusText.textContent = 'Ready to Start';
                    connectBtn.disabled = false;
                    disconnectBtn.disabled = true;
                    if (statusPing) statusPing.style.display = 'none';
                    break;
            }
        }

        function addTranscriptMessage(role, message) {
            console.error(role, message);

            // Remove placeholder if exists
            const placeholder = transcript.querySelector('.flex.flex-col');
            if (placeholder) {
                transcript.innerHTML = '';
            }

            const messageDiv = document.createElement('div');
            messageDiv.className = role === 'user' ? 'text-blue-600' : 'text-green-600';
            messageDiv.innerHTML = `<strong>${role === 'user' ? 'You' : 'AI'}:</strong> ${message}`;

            transcript.appendChild(messageDiv);
            transcript.scrollTop = transcript.scrollHeight;
        }

        async function getEphemeralToken() {
            return "{{$quiz->ephemeral_key}}";
        }

        async function connectToOpenAI() {
            try {
                updateStatus('connecting');

                // Get ephemeral token
                const ephemeralToken = await getEphemeralToken();

                // Create peer connection
                peerConnection = new RTCPeerConnection();

                // Set up audio element for output
                audioElement = document.createElement('audio');
                audioElement.autoplay = true;

                // Handle incoming tracks
                peerConnection.ontrack = (event) => {
                    console.log('Received track:', event.track.kind);
                    audioElement.srcObject = event.streams[0];
                };

                // Add local audio track (microphone)
                const stream = await navigator.mediaDevices.getUserMedia({audio: true});
                stream.getTracks().forEach(track => {
                    peerConnection.addTrack(track, stream);
                });

                // Create data channel for events
                dataChannel = peerConnection.createDataChannel('oai-events');

                dataChannel.onopen = () => {
                    console.log('Data channel opened');
                    updateStatus('connected');

                    // Send session update with instructions
                    const sessionUpdate = {
                        type: 'session.update',
                        session: {
                            input_audio_transcription: {
                                model: 'whisper-1'
                            }
                        }
                    };
                    dataChannel.send(JSON.stringify(sessionUpdate));

                    setTimeout(() => {
                        const createResponse = {
                            type: 'response.create',
                            response: {
                                modalities: ['text', 'audio'],
                                instructions: 'You are a Polish examiner. Speak ONLY in Polish. If the user says anything in another language, correct them and continue in Polish. Start with with explanation of what will happen. Always ask questions first. Lead the conversation',
                            }
                        };
                        dataChannel.send(JSON.stringify(createResponse));
                    }, 500);
                };

                dataChannel.onmessage = (event) => {
                    try {
                        const message = JSON.parse(event.data);
                        console.log('Received event:', message);

                        // Handle different event types
                        if (message.type === 'conversation.item.input_audio_transcription.completed') {
                            addTranscriptMessage('user', message.transcript);
                        } else if (message.type === 'response.audio_transcript.delta') {
                            // Handle AI response transcript
                            const lastMessage = transcript.lastElementChild;
                            if (lastMessage && lastMessage.classList.contains('text-green-600')) {
                                lastMessage.innerHTML = `<strong>AI:</strong> ${lastMessage.textContent.replace('AI: ', '') + message.delta}`;
                            } else {
                                addTranscriptMessage('assistant', message.delta);
                            }
                        } else if (message.type === 'response.audio_transcript.done') {
                            // Final transcript
                            const lastMessage = transcript.lastElementChild;
                            if (lastMessage && lastMessage.classList.contains('text-green-600')) {
                                lastMessage.innerHTML = `<strong>AI:</strong> ${message.transcript}`;
                            }
                        }
                    } catch (error) {
                        console.error('Error parsing message:', error);
                    }
                };

                dataChannel.onclose = () => {
                    console.log('Data channel closed');
                    disconnect();
                };

                // Create and set local description
                const offer = await peerConnection.createOffer();
                await peerConnection.setLocalDescription(offer);

                // Send offer to OpenAI
                const response = await fetch('https://api.openai.com/v1/realtime', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${ephemeralToken}`,
                        'Content-Type': 'application/sdp',
                    },
                    body: offer.sdp,
                });

                if (!response.ok) {
                    throw new Error('Failed to connect to OpenAI');
                }

                const answerSdp = await response.text();
                await peerConnection.setRemoteDescription({
                    type: 'answer',
                    sdp: answerSdp,
                });

                console.log('Connected to OpenAI Realtime API');

            } catch (error) {
                console.error('Connection error:', error);
                alert('Failed to connect: ' + error.message);
                updateStatus('disconnected');
                disconnect();
            }
        }

        function disconnect() {
            if (dataChannel) {
                dataChannel.close();
                dataChannel = null;
            }

            if (peerConnection) {
                peerConnection.close();
                peerConnection = null;
            }

            if (audioElement) {
                audioElement.srcObject = null;
                audioElement = null;
            }

            updateStatus('disconnected');
        }

        connectBtn.addEventListener('click', connectToOpenAI);
        disconnectBtn.addEventListener('click', disconnect);

        // Initialize status
        updateStatus('disconnected');
    </script>
    @endscript

    <div class="min-h-screen py-8 px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Main Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">
                <!-- Status Bar -->
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <span class="status-indicator" id="statusIndicator"></span>
                                <span class="absolute inset-0 rounded-full animate-ping opacity-75" id="statusPing" style="display: none;"></span>
                            </div>
                            <div>
                                <p class="text-sm font-medium opacity-90">Connection Status</p>
                                <p id="statusText" class="text-xl font-bold">Ready to Start</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-90">AI Inspector</p>
                            <p class="text-lg font-semibold">Online</p>
                        </div>
                    </div>
                </div>

                <!-- Controls Section -->
                <div class="p-8 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex gap-4 justify-center">
                        <button
                            id="connectBtn"
                            class="group relative px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold hover:from-green-600 hover:to-emerald-700 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        >
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Start Exam
                            </span>
                        </button>
                        <button
                            id="disconnectBtn"
                            class="px-8 py-4 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-xl font-semibold hover:from-red-600 hover:to-rose-700 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                            disabled
                        >
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                                </svg>
                                End Exam
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Conversation Area -->
                <div class="p-8">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Conversation</h3>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-6 min-h-[400px] max-h-[500px] overflow-y-auto" id="transcript">
                        <div class="flex flex-col items-center justify-center h-full text-center py-12">
                            <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Click "Start Exam" to begin</p>
                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Your conversation will appear here</p>
                        </div>
                    </div>
                </div>

                <!-- Instructions Footer -->
                <div class="bg-blue-50 dark:bg-gray-900 p-6">
                    <button
                        onclick="document.getElementById('instructions').classList.toggle('hidden'); document.getElementById('chevron').classList.toggle('rotate-180')"
                        class="flex items-center gap-3 w-full text-left hover:opacity-80 transition-opacity"
                    >
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="font-semibold text-gray-900 dark:text-white">How it works</p>
                        <svg id="chevron" class="w-5 h-5 text-gray-500 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="instructions" class="hidden mt-3 ml-8 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                        <ul class="space-y-1 ml-2">
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                Click "Start Exam" and allow microphone access when prompted
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                The AI examiner will introduce the exam and ask questions
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                Speak clearly and naturally - all responses are transcribed
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                Click "End Exam" when you're finished
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endif
