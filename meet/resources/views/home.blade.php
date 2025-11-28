<!DOCTYPE html>
<html>
<head>
    <title>Digital Leap Africa - Video Conferencing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .container { max-width: 1200px; margin: 0 auto; padding: 50px 20px; text-align: center; }
        .hero { color: white; margin-bottom: 60px; }
        .hero h1 { font-size: 3.5rem; margin-bottom: 20px; font-weight: 700; }
        .hero p { font-size: 1.2rem; opacity: 0.9; max-width: 600px; margin: 0 auto; }
        .actions { display: flex; gap: 20px; justify-content: center; margin-bottom: 80px; flex-wrap: wrap; }
        .btn { padding: 15px 30px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; transition: transform 0.2s; }
        .btn:hover { transform: translateY(-2px); }
        .btn-primary { background: #10b981; color: white; }
        .btn-secondary { background: white; color: #374151; }
        .features { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        .feature { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .feature h3 { color: #1f2937; margin-bottom: 15px; font-size: 1.3rem; }
        .feature p { color: #6b7280; line-height: 1.6; }
        .icon { font-size: 2.5rem; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="hero">
            <h1>Digital Leap Africa</h1>
            <p>Low-bandwidth video conferencing designed for African communities. Connect, collaborate, and grow together with optimized technology.</p>
        </div>
        
        <div class="actions">
            <a href="{{ route('meetings.create') }}" class="btn btn-primary">🚀 Start New Meeting</a>
            <a href="#" class="btn btn-secondary" onclick="joinMeeting()">📞 Join Meeting</a>
        </div>
        
        <div class="features">
            <div class="feature">
                <div class="icon">📡</div>
                <h3>Low Bandwidth Optimized</h3>
                <p>Automatically adjusts video quality based on your connection. Works great even on 2G/3G networks.</p>
            </div>
            
            <div class="feature">
                <div class="icon">🤝</div>
                <h3>Community Tools</h3>
                <p>Hand raising queue, shared notes, and real-time polls designed for community collaboration.</p>
            </div>
            
            <div class="feature">
                <div class="icon">🔒</div>
                <h3>Secure & Private</h3>
                <p>End-to-end encrypted peer-to-peer connections. No data stored on external servers.</p>
            </div>
        </div>
    </div>

    <script>
        function joinMeeting() {
            const meetingId = prompt('Enter Meeting ID:');
            if (meetingId) {
                window.location.href = `/meetings/${meetingId}/lobby`;
            }
        }
    </script>
</body>
</html>