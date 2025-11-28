@extends('layouts.app')

@section('title', $meeting->title . ' - Digital Leap Africa')
@section('meta_description', 'Video conference meeting room')

@push('styles')
<style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #1f2937; color: white; overflow: hidden; }
        
        .meeting-container { display: grid; grid-template-columns: 1fr 300px; height: 100vh; }
        .main-area { display: flex; flex-direction: column; }
        .video-grid { flex: 1; display: grid; gap: 8px; padding: 20px; }
        .video-grid.grid-1 { grid-template-columns: 1fr; }
        .video-grid.grid-2 { grid-template-columns: 1fr 1fr; }
        .video-grid.grid-3 { grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; }
        .video-grid.grid-4 { grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; }
        .video-grid.grid-many { grid-template-columns: repeat(3, 1fr); }
        
        .video-tile { background: #374151; border-radius: 8px; position: relative; overflow: hidden; }
        .video-tile video { width: 100%; height: 100%; object-fit: cover; }
        .video-tile.speaking { border: 3px solid #10b981; }
        .video-tile .user-info { position: absolute; bottom: 8px; left: 8px; background: rgba(0,0,0,0.7); padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .video-tile .muted { position: absolute; top: 8px; right: 8px; background: #ef4444; padding: 4px; border-radius: 50%; }
        
        .controls-bar { display: flex; justify-content: center; gap: 15px; padding: 20px; background: #111827; }
        .control-btn { width: 48px; height: 48px; border: none; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .control-btn.active { background: #ef4444; color: white; }
        .control-btn.inactive { background: #374151; color: #9ca3af; }
        .control-btn.primary { background: #10b981; color: white; }
        
        .sidebar { background: #374151; display: flex; flex-direction: column; }
        .sidebar-tabs { display: flex; border-bottom: 1px solid #4b5563; }
        .tab { flex: 1; padding: 15px; text-align: center; cursor: pointer; background: none; border: none; color: #9ca3af; }
        .tab.active { color: white; background: #4b5563; }
        .sidebar-content { flex: 1; overflow-y: auto; }
        
        .chat-area { padding: 15px; }
        .chat-messages { height: 300px; overflow-y: auto; margin-bottom: 15px; }
        .chat-input { width: 100%; padding: 10px; border: 1px solid #4b5563; border-radius: 6px; background: #1f2937; color: white; }
        
        .notes-area { padding: 15px; }
        .notes-editor { width: 100%; height: 300px; padding: 10px; border: 1px solid #4b5563; border-radius: 6px; background: #1f2937; color: white; resize: none; }
        
        .participants-list { padding: 15px; }
        .participant { display: flex; align-items: center; gap: 10px; padding: 8px; margin-bottom: 5px; }
        .participant.hand-raised { background: #fbbf24; color: #92400e; border-radius: 6px; }
        
        .hand-queue { margin-top: 20px; }
        .hand-queue h4 { margin-bottom: 10px; color: #fbbf24; }
        
        .poll-modal { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.8); display: none; align-items: center; justify-content: center; z-index: 1000; }
        .poll-content { background: #374151; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; }
        .poll-option { margin: 10px 0; padding: 10px; background: #4b5563; border-radius: 6px; cursor: pointer; }
        .poll-option:hover { background: #6b7280; }
</style>
@endpush

@section('content')
<div class="meeting-container" style="margin: -2rem -5% 0; width: 110%; height: calc(100vh - 4rem);">
        <div class="main-area">
            <div id="videoGrid" class="video-grid grid-1">
                <div class="video-tile" id="localTile">
                    <video id="localVideo" autoplay muted></video>
                    <div class="user-info">You</div>
                </div>
            </div>
            
            <div class="controls-bar">
                <button class="control-btn inactive" id="muteBtn" title="Mute/Unmute">
                    <i class="fas fa-microphone"></i>
                </button>
                <button class="control-btn inactive" id="videoBtn" title="Camera On/Off">
                    <i class="fas fa-video"></i>
                </button>
                <button class="control-btn inactive" id="screenBtn" title="Share Screen">
                    <i class="fas fa-desktop"></i>
                </button>
                <button class="control-btn inactive" id="handBtn" title="Raise Hand">
                    <i class="fas fa-hand-paper"></i>
                </button>
                <button class="control-btn inactive" id="pollBtn" title="Create Poll">
                    <i class="fas fa-poll"></i>
                </button>
                <button class="control-btn active" id="leaveBtn" title="Leave Meeting">
                    <i class="fas fa-phone-slash"></i>
                </button>
            </div>
        </div>
        
        <div class="sidebar">
            <div class="sidebar-tabs">
                <button class="tab active" onclick="switchTab('chat')">Chat</button>
                <button class="tab" onclick="switchTab('notes')">Notes</button>
                <button class="tab" onclick="switchTab('people')">People</button>
            </div>
            
            <div class="sidebar-content">
                <div id="chatTab" class="chat-area">
                    <div id="chatMessages" class="chat-messages"></div>
                    <input type="text" id="chatInput" class="chat-input" placeholder="Type a message...">
                </div>
                
                <div id="notesTab" class="notes-area" style="display: none;">
                    <h4>Shared Meeting Notes</h4>
                    <textarea id="notesEditor" class="notes-editor" placeholder="Collaborative notes..."></textarea>
                </div>
                
                <div id="peopleTab" class="participants-list" style="display: none;">
                    <h4>Participants (<span id="participantCount">1</span>)</h4>
                    <div id="participantsList">
                        <!-- Participants will be loaded dynamically -->
                    </div>
                    
                    <div class="hand-queue">
                        <h4><i class="fas fa-hand-paper"></i> Hand Raised Queue</h4>
                        <div id="handQueue"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="pollModal" class="poll-modal">
        <div class="poll-content">
            <h3>Create Quick Poll</h3>
            <input type="text" id="pollQuestion" placeholder="Enter your question..." style="width: 100%; margin: 15px 0; padding: 10px; border-radius: 6px; border: 1px solid #4b5563; background: #1f2937; color: white;">
            <div id="pollOptions">
                <input type="text" placeholder="Option 1" class="poll-option-input">
                <input type="text" placeholder="Option 2" class="poll-option-input">
            </div>
            <div style="margin-top: 20px; text-align: right;">
                <button onclick="closePoll()" style="margin-right: 10px; padding: 8px 16px; background: #6b7280; color: white; border: none; border-radius: 6px;">Cancel</button>
                <button onclick="createPoll()" style="padding: 8px 16px; background: #10b981; color: white; border: none; border-radius: 6px;">Create Poll</button>
            </div>
        </div>
    </div>

@push('scripts')
<script>
        // WebRTC and Meeting State
        let localStream = null;
        let peers = new Map();
        let meetingSettings = JSON.parse(localStorage.getItem('meetingSettings') || '{}');
        let isAudioMuted = !meetingSettings.audioEnabled;
        let isVideoMuted = !meetingSettings.videoEnabled;
        let isHandRaised = false;
        let handQueue = [];
        let activeSpeaker = null;
        let sessionId = null;
        
        const iceServers = [
            { urls: 'stun:stun.l.google.com:19302' },
            { urls: 'stun:stun1.l.google.com:19302' }
        ];

        // Initialize meeting
        async function initMeeting() {
            await setupWebSocket();
            await setupLocalMedia();
            setupEventListeners();
            updateControlButtons();
        }

        async function setupWebSocket() {
            // Join meeting via HTTP
            await fetch('/meetings/{{ $meeting->slug }}/signal', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ type: 'join' })
            });
            
            // Start polling for messages
            startPolling();
        }
        
        function startPolling() {
            setInterval(async () => {
                try {
                    const response = await fetch('/meetings/{{ $meeting->slug }}/poll');
                    const data = await response.json();
                    
                    data.signals.forEach(handleSignalingMessage);
                    
                    // Load participants every poll
                    loadParticipants();
                } catch (error) {
                    console.error('Polling error:', error);
                }
            }, 1000); // Poll every second
        }

        async function setupLocalMedia() {
            try {
                const constraints = getMediaConstraints();
                localStream = await navigator.mediaDevices.getUserMedia(constraints);
                document.getElementById('localVideo').srcObject = localStream;
                
                // Apply initial mute states
                localStream.getAudioTracks().forEach(track => track.enabled = !isAudioMuted);
                localStream.getVideoTracks().forEach(track => track.enabled = !isVideoMuted);
                
            } catch (error) {
                console.error('Failed to get local media:', error);
            }
        }

        function getMediaConstraints() {
            if (meetingSettings.audioOnlyMode) {
                return { audio: true, video: false };
            }
            
            const videoConstraints = meetingSettings.dataSaverMode ? 
                { width: 320, height: 240, frameRate: 15 } : 
                { width: 1280, height: 720, frameRate: 30 };
                
            return { audio: true, video: videoConstraints };
        }

        function setupEventListeners() {
            document.getElementById('muteBtn').onclick = toggleAudio;
            document.getElementById('videoBtn').onclick = toggleVideo;
            document.getElementById('screenBtn').onclick = toggleScreenShare;
            document.getElementById('handBtn').onclick = toggleHand;
            document.getElementById('pollBtn').onclick = showPollModal;
            document.getElementById('leaveBtn').onclick = leaveMeeting;
            
            document.getElementById('chatInput').onkeypress = (e) => {
                if (e.key === 'Enter') sendChatMessage();
            };
            
            document.getElementById('notesEditor').oninput = (e) => {
                broadcastMessage({ type: 'notes', content: e.target.value });
            };
        }

        function handleSignalingMessage(message) {
            
            switch (message.type) {
                case 'user-joined':
                    handleUserJoined(message);
                    break;
                case 'user-left':
                    handleUserLeft(message);
                    break;
                case 'offer':
                    handleOffer(message);
                    break;
                case 'answer':
                    handleAnswer(message);
                    break;
                case 'ice-candidate':
                    handleIceCandidate(message);
                    break;
                case 'chat':
                    displayChatMessage(message);
                    break;
                case 'hand-raised':
                    updateHandQueue(message);
                    break;
                case 'poll':
                    displayPoll(message);
                    break;
            }
        }
        
        async function handleUserJoined(message) {
            const peerId = message.from;
            if (peerId && !peers.has(peerId)) {
                await createPeerConnection(peerId, true);
            }
        }
        
        function handleUserLeft(message) {
            const peerId = message.from;
            if (peers.has(peerId)) {
                peers.get(peerId).close();
                peers.delete(peerId);
                removeVideoTile(peerId);
                updateVideoGrid();
            }
        }
        
        async function createPeerConnection(peerId, isInitiator) {
            const pc = new RTCPeerConnection({ iceServers });
            peers.set(peerId, pc);
            
            localStream.getTracks().forEach(track => {
                pc.addTrack(track, localStream);
            });
            
            pc.ontrack = (event) => {
                addVideoTile(peerId, event.streams[0]);
                updateVideoGrid();
            };
            
            pc.onicecandidate = (event) => {
                if (event.candidate) {
                    broadcastMessage({
                        type: 'ice-candidate',
                        candidate: event.candidate,
                        target: peerId
                    });
                }
            };
            
            if (isInitiator) {
                const offer = await pc.createOffer();
                await pc.setLocalDescription(offer);
                broadcastMessage({
                    type: 'offer',
                    offer: offer,
                    target: peerId
                });
            }
        }
        
        async function handleOffer(message) {
            const peerId = message.from;
            if (!peers.has(peerId)) {
                await createPeerConnection(peerId, false);
            }
            
            const pc = peers.get(peerId);
            await pc.setRemoteDescription(message.offer);
            
            const answer = await pc.createAnswer();
            await pc.setLocalDescription(answer);
            
            broadcastMessage({
                type: 'answer',
                answer: answer,
                target: peerId
            });
        }
        
        async function handleAnswer(message) {
            const peerId = message.from;
            const pc = peers.get(peerId);
            if (pc) {
                await pc.setRemoteDescription(message.answer);
            }
        }
        
        async function handleIceCandidate(message) {
            const peerId = message.from;
            const pc = peers.get(peerId);
            if (pc) {
                await pc.addIceCandidate(message.candidate);
            }
        }
        
        function addVideoTile(peerId, stream) {
            const videoGrid = document.getElementById('videoGrid');
            const tile = document.createElement('div');
            tile.className = 'video-tile';
            tile.id = `tile-${peerId}`;
            
            const video = document.createElement('video');
            video.autoplay = true;
            video.srcObject = stream;
            
            const userInfo = document.createElement('div');
            userInfo.className = 'user-info';
            userInfo.textContent = `User ${peerId.substr(0, 8)}`;
            
            tile.appendChild(video);
            tile.appendChild(userInfo);
            videoGrid.appendChild(tile);
        }
        
        function removeVideoTile(peerId) {
            const tile = document.getElementById(`tile-${peerId}`);
            if (tile) {
                tile.remove();
            }
        }
        
        function updateVideoGrid() {
            const grid = document.getElementById('videoGrid');
            const tileCount = grid.children.length;
            
            grid.className = 'video-grid ';
            if (tileCount === 1) grid.className += 'grid-1';
            else if (tileCount === 2) grid.className += 'grid-2';
            else if (tileCount <= 4) grid.className += 'grid-4';
            else grid.className += 'grid-many';
        }
        
        function updateHandQueue(message) {
            const queueDiv = document.getElementById('handQueue');
            if (message.raised) {
                if (!handQueue.includes(message.from)) {
                    handQueue.push(message.from);
                }
            } else {
                handQueue = handQueue.filter(id => id !== message.from);
            }
            
            queueDiv.innerHTML = handQueue.map(id => 
                `<div class="participant hand-raised"><i class="fas fa-hand-paper"></i> User ${id.substr(0, 8)}</div>`
            ).join('');
        }
        
        function displayPoll(message) {
            const chatMessages = document.getElementById('chatMessages');
            const pollDiv = document.createElement('div');
            pollDiv.innerHTML = `
                <div style="background: #4b5563; padding: 15px; border-radius: 8px; margin: 10px 0;">
                    <h4><i class="fas fa-poll"></i> ${message.question}</h4>
                    ${message.options.map((option, i) => 
                        `<div class="poll-option" onclick="votePoll(${i})">${option}</div>`
                    ).join('')}
                </div>
            `;
            chatMessages.appendChild(pollDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function toggleAudio() {
            isAudioMuted = !isAudioMuted;
            localStream.getAudioTracks().forEach(track => track.enabled = !isAudioMuted);
            updateControlButtons();
            updateParticipantStatus({ is_muted: isAudioMuted });
            broadcastMessage({ type: 'audio-toggle', muted: isAudioMuted });
        }

        function toggleVideo() {
            isVideoMuted = !isVideoMuted;
            localStream.getVideoTracks().forEach(track => track.enabled = !isVideoMuted);
            updateControlButtons();
            updateParticipantStatus({ is_video_off: isVideoMuted });
            broadcastMessage({ type: 'video-toggle', muted: isVideoMuted });
        }
        
        async function updateParticipantStatus(status) {
            try {
                await fetch('/meetings/{{ $meeting->slug }}/participants/status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(status)
                });
            } catch (error) {
                console.error('Status update error:', error);
            }
        }
        
        async function loadParticipants() {
            try {
                const response = await fetch('/meetings/{{ $meeting->slug }}/participants');
                const data = await response.json();
                
                const participantsList = document.getElementById('participantsList');
                const participantCount = document.getElementById('participantCount');
                
                participantCount.textContent = data.participants.length;
                
                participantsList.innerHTML = data.participants.map(participant => `
                    <div class="participant">
                        <span><i class="fas fa-user"></i></span>
                        <span>${participant.user_name}</span>
                        ${participant.is_muted ? '<i class="fas fa-microphone-slash" style="color: #ef4444;"></i>' : ''}
                        ${participant.is_video_off ? '<i class="fas fa-video-slash" style="color: #ef4444;"></i>' : ''}
                    </div>
                `).join('');
            } catch (error) {
                console.error('Load participants error:', error);
            }
        }

        function toggleHand() {
            isHandRaised = !isHandRaised;
            updateControlButtons();
            broadcastMessage({ type: 'hand-raised', raised: isHandRaised });
        }

        function updateControlButtons() {
            document.getElementById('muteBtn').className = `control-btn ${isAudioMuted ? 'active' : 'inactive'}`;
            document.getElementById('videoBtn').className = `control-btn ${isVideoMuted ? 'active' : 'inactive'}`;
            document.getElementById('handBtn').className = `control-btn ${isHandRaised ? 'primary' : 'inactive'}`;
        }

        function sendChatMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();
            if (message) {
                broadcastMessage({ type: 'chat', message, sender: 'You' });
                displayChatMessage({ message, sender: 'You' });
                input.value = '';
            }
        }

        function displayChatMessage(data) {
            const messages = document.getElementById('chatMessages');
            const div = document.createElement('div');
            div.innerHTML = `<strong>${data.sender}:</strong> ${data.message}`;
            div.style.marginBottom = '8px';
            messages.appendChild(div);
            messages.scrollTop = messages.scrollHeight;
        }

        function switchTab(tab) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.sidebar-content > div').forEach(d => d.style.display = 'none');
            
            event.target.classList.add('active');
            document.getElementById(tab + 'Tab').style.display = 'block';
        }

        function showPollModal() {
            document.getElementById('pollModal').style.display = 'flex';
        }

        function closePoll() {
            document.getElementById('pollModal').style.display = 'none';
        }

        function createPoll() {
            const question = document.getElementById('pollQuestion').value;
            const options = Array.from(document.querySelectorAll('.poll-option-input')).map(input => input.value).filter(v => v);
            
            if (question && options.length >= 2) {
                broadcastMessage({ type: 'poll', question, options });
                closePoll();
            }
        }

        async function broadcastMessage(message) {
            try {
                await fetch('/meetings/{{ $meeting->slug }}/signal', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(message)
                });
            } catch (error) {
                console.error('Broadcast error:', error);
            }
        }

        async function leaveMeeting() {
            if (localStream) {
                localStream.getTracks().forEach(track => track.stop());
            }
            
            await fetch('/meetings/{{ $meeting->slug }}/leave', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            window.location.href = '/';
        }

        function votePoll(optionIndex) {
            broadcastMessage({
                type: 'poll-vote',
                option: optionIndex
            });
        }
        
        // Initialize when page loads
        initMeeting();
        
        // Load participants initially
        setTimeout(loadParticipants, 1000);
</script>
@endpush
@endsection