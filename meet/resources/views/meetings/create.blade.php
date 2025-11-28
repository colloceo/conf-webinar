<!DOCTYPE html>
<html>
<head>
    <title>Create Meeting - Digital Leap Africa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; }
        .container { max-width: 600px; margin: 50px auto; padding: 30px; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: 500; color: #374151; }
        input, textarea { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 16px; }
        textarea { height: 100px; resize: vertical; }
        .btn { padding: 12px 24px; background: #4f46e5; color: white; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; width: 100%; }
        .btn:hover { background: #4338ca; }
        h1 { margin-bottom: 30px; color: #1f2937; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create New Meeting</h1>
        
        <form action="{{ route('meetings.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="title">Meeting Title</label>
                <input type="text" id="title" name="title" required placeholder="Digital Leap Africa Community Call">
            </div>
            
            <div class="form-group">
                <label for="description">Description (Optional)</label>
                <textarea id="description" name="description" placeholder="Brief description of the meeting agenda..."></textarea>
            </div>
            
            <div class="form-group">
                <label for="scheduled_at">Schedule for Later (Optional)</label>
                <input type="datetime-local" id="scheduled_at" name="scheduled_at">
            </div>
            
            <button type="submit" class="btn">Create Meeting</button>
        </form>
    </div>
</body>
</html>