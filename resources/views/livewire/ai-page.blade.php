@if(!auth()->check() || !auth()->user()?->is_admin)
    <x-under-construction/>
@else
    @assets
    <style>
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .status-disconnected {
            background-color: #ef4444;
        }

        .status-connecting {
            background-color: #f59e0b;
        }

        .status-connected {
            background-color: #10b981;
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

            switch (status) {
                case 'connected':
                    statusIndicator.classList.add('status-connected');
                    statusText.textContent = 'Connected';
                    connectBtn.disabled = true;
                    disconnectBtn.disabled = false;
                    break;
                case 'connecting':
                    statusIndicator.classList.add('status-connecting');
                    statusText.textContent = 'Connecting...';
                    connectBtn.disabled = true;
                    disconnectBtn.disabled = true;
                    break;
                case 'disconnected':
                    statusIndicator.classList.add('status-disconnected');
                    statusText.textContent = 'Disconnected';
                    connectBtn.disabled = false;
                    disconnectBtn.disabled = true;
                    break;
            }
        }

        function addTranscriptMessage(role, message) {
            console.error(role, message);
            const messageDiv = document.createElement('div');
            messageDiv.className = role === 'user' ? 'text-blue-600' : 'text-green-600';
            messageDiv.innerHTML = `<strong>${role === 'user' ? 'You' : 'AI'}:</strong> ${message}`;

            // Remove placeholder if exists
            const placeholder = transcript.querySelector('p.italic');
            if (placeholder) {
                placeholder.remove();
            }

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

    <div class="bg-card/80 backdrop-blur-sm mt-10 border-border/50 rounded-lg p-5 max-w-2xl mx-auto">
        <div class="space-y-6">
            <!-- Header -->
            <div class="text-center">
                <h2 class="text-2xl font-bold">OpenAI Realtime Voice Agent</h2>
                <p class="text-muted-foreground mt-2">Talk to AI using WebRTC</p>
            </div>

            <!-- Status -->
            <div class="flex items-center justify-center">
                <span class="status-indicator" id="statusIndicator"></span>
                <span id="statusText" class="text-sm font-medium">Disconnected</span>
            </div>

            <!-- Controls -->
            <div class="flex gap-4 justify-center">
                <button
                    id="connectBtn"
                    class="px-6 py-3 bg-primary text-primary-foreground rounded-lg font-medium hover:bg-primary/90 transition-colors"
                >
                    Connect
                </button>
                <button
                    id="disconnectBtn"
                    class="px-6 py-3 bg-destructive text-destructive-foreground rounded-lg font-medium hover:bg-destructive/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled
                >
                    Disconnect
                </button>
            </div>

            <!-- Transcript -->
            <div class="border border-border rounded-lg p-4 min-h-[200px] max-h-[400px] overflow-y-auto">
                <h3 class="font-semibold mb-3">Conversation</h3>
                <div id="transcript" class="space-y-2 text-sm">
                    <p class="text-muted-foreground italic">Conversation will appear here...</p>
                </div>
            </div>

            <!-- Instructions -->
            <div class="text-xs text-muted-foreground">
                <p>• Click "Connect" to start the voice conversation</p>
                <p>• Allow microphone access when prompted</p>
                <p>• Speak naturally - the AI will respond in real-time</p>
            </div>
        </div>
    </div>

@endif
