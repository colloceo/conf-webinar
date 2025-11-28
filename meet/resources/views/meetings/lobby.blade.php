@extends('layouts.app')

@section('title', 'Join ' . $meeting->title . ' - Digital Leap Africa')
@section('meta_description', 'Test your camera and microphone before joining the meeting')

@push('styles')
<style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; }
        .lobby { max-width: 800px; margin: 50px auto; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .preview { display: grid; grid-template-columns: 1fr 300px; gap: 20px; margin-bottom: 30px; }
        .video-preview { background: #000; border-radius: 8px; position: relative; aspect-ratio: 16/9; }
        .video-preview video { width: 100%; height: 100%; border-radius: 8px; }
        .controls { display: flex; gap: 10px; margin-top: 15px; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; }
        .btn-primary { background: #4f46e5; color: white; }
        .btn-secondary { background: #e5e7eb; color: #374151; }
        .settings { padding: 20px; background: #f9fafb; border-radius: 8px; }
        .setting-item { margin-bottom: 15px; }
        .toggle { display: flex; align-items: center; gap: 10px; }
        .switch { position: relative; width: 44px; height: 24px; background: #ccc; border-radius: 12px; cursor: pointer; }
        .switch.active { background: #4f46e5; }
        .switch::after { content: ''; position: absolute; width: 20px; height: 20px; background: white; border-radius: 50%; top: 2px; left: 2px; transition: 0.3s; }
        .switch.active::after { left: 22px; }
        .network-status { padding: 10px; border-radius: 6px; margin-bottom: 15px; }
        .network-good { background: #d1fae5; color: #065f46; }
        .network-poor { background: #fee2e2; color: #991b1b; }
        .join-btn { width: 100%; padding: 15px; background: #059669; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; }
</style>
@endpush

@section('content')
<div class="lobby">
        <h1>Ready to join "{{ $meeting->title }}"?</h1>
        <p style="color: #6b7280; margin-bottom: 30px;">Test your camera and microphone before joining</p>
        
        <div class="preview">
            <div>
                <div class="video-preview">
                    <video id="localVideo" autoplay muted></video>
                </div>
                <div class="controls">
                    <button class="btn btn-secondary" id="toggleVideo">
                        <i class="fas fa-video"></i> Camera
                    </button>
                    <button class="btn btn-secondary" id="toggleAudio">
                        <i class="fas fa-microphone"></i> Microphone
                    </button>
                </div>
            </div>
            
            <div class="settings">
                <div id="networkStatus" class="network-status network-good">
                    <i class="fas fa-circle" style="color: #10b981;"></i> Network: Good (Loading...)
                </div>
                
                <div class="setting-item">
                    <div class="toggle">
                        <div class="switch" id="dataSaverToggle"></div>
                        <label>Data Saver Mode</label>
                    </div>
                    <small style="color: #6b7280;">Reduces video quality to save bandwidth</small>
                </div>
                
                <div class="setting-item">
                    <div class="toggle">
                        <div class="switch" id="audioOnlyToggle"></div>
                        <label>Audio Only Mode</label>
                    </div>
                    <small style="color: #6b7280;">Join without video</small>
                </div>
            </div>
        </div>
        
        <button class="join-btn" onclick="joinMeeting()">Join Meeting</button>
    </div>

@push('scripts')
<script>
        let localStream = null;
        let videoEnabled = true;
        let audioEnabled = true;
        let dataSaverMode = false;
        let audioOnlyMode = false;
        let networkQuality = 'good';

        async function init() {
            await checkNetworkQuality();
            await setupMedia();
            setupToggles();
        }

        async function checkNetworkQuality() {
            const start = Date.now();
            try {
                await fetch('/ping?' + Date.now());
                const ping = Date.now() - start;
                const status = document.getElementById('networkStatus');
                
                if (ping > 300) {
                    networkQuality = 'poor';
                    status.className = 'network-status network-poor';
                    status.innerHTML = '<i class="fas fa-circle" style="color: #ef4444;"></i> Network: Poor (' + ping + 'ms) - Consider Audio Only';
                    document.getElementById('audioOnlyToggle').click();
                } else {
                    status.innerHTML = '<i class="fas fa-circle" style="color: #10b981;"></i> Network: Good (' + ping + 'ms)';
                }
            } catch (e) {
                console.warn('Network check failed');
            }
        }

        async function setupMedia() {
            try {
                const constraints = getMediaConstraints();
                localStream = await navigator.mediaDevices.getUserMedia(constraints);
                document.getElementById('localVideo').srcObject = localStream;
            } catch (e) {
                console.error('Media access failed:', e);
            }
        }

        function getMediaConstraints() {
            if (audioOnlyMode) {
                return { audio: true, video: false };
            }
            
            const videoConstraints = dataSaverMode ? 
                { width: 320, height: 240, frameRate: 15 } : 
                { width: 1280, height: 720, frameRate: 30 };
                
            return { audio: true, video: videoConstraints };
        }

        function setupToggles() {
            document.getElementById('dataSaverToggle').onclick = () => toggleDataSaver();
            document.getElementById('audioOnlyToggle').onclick = () => toggleAudioOnly();
            document.getElementById('toggleVideo').onclick = () => toggleVideo();
            document.getElementById('toggleAudio').onclick = () => toggleAudio();
        }

        function toggleDataSaver() {
            dataSaverMode = !dataSaverMode;
            document.getElementById('dataSaverToggle').classList.toggle('active');
            if (localStream && !audioOnlyMode) {
                setupMedia();
            }
        }

        function toggleAudioOnly() {
            audioOnlyMode = !audioOnlyMode;
            document.getElementById('audioOnlyToggle').classList.toggle('active');
            setupMedia();
        }

        function toggleVideo() {
            if (localStream) {
                const videoTrack = localStream.getVideoTracks()[0];
                if (videoTrack) {
                    videoEnabled = !videoEnabled;
                    videoTrack.enabled = videoEnabled;
                }
            }
        }

        function toggleAudio() {
            if (localStream) {
                const audioTrack = localStream.getAudioTracks()[0];
                if (audioTrack) {
                    audioEnabled = !audioEnabled;
                    audioTrack.enabled = audioEnabled;
                }
            }
        }

        function joinMeeting() {
            const settings = {
                dataSaverMode,
                audioOnlyMode,
                videoEnabled,
                audioEnabled,
                networkQuality
            };
            
            localStorage.setItem('meetingSettings', JSON.stringify(settings));
            window.location.href = '{{ route("meetings.join", $meeting) }}';
        }

        init();
</script>
@endpush
@endsection