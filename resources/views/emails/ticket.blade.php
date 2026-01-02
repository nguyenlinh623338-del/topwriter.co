<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>TopWriter Support Ticket</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; }
        .priority { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .priority-low { background: #e3f2fd; color: #1565c0; }
        .priority-medium { background: #fff3e0; color: #ef6c00; }
        .priority-high { background: #ffebee; color: #c62828; }
        .priority-urgent { background: #ffebee; color: #b71c1c; animation: blink 2s infinite; }
        @keyframes blink { 0%, 50% { opacity: 1; } 51%, 100% { opacity: 0.5; } }
        .field { margin-bottom: 20px; }
        .label { font-weight: bold; color: #333; margin-bottom: 5px; }
        .value { background: #f8f9fa; padding: 10px; border-radius: 4px; border-left: 4px solid #667eea; }
        .user-info { background: #e8f4fd; padding: 15px; border-radius: 6px; margin-bottom: 20px; }
        .metadata { font-size: 12px; color: #666; border-top: 1px solid #eee; padding-top: 15px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎫 TopWriter Support Ticket</h1>
            <p>ID: {{ $ticket_id }}</p>
        </div>
        
        <div class="content">
            <div class="user-info">
                <h3>👤 User Information</h3>
                <p><strong>Name:</strong> {{ $user_name }}</p>
                <p><strong>Email:</strong> {{ $user_email }}</p>
                <p><strong>User ID:</strong> {{ $user_id }}</p>
                <p><strong>Dashboard:</strong> <a href="{{ $dashboard_url }}">View Dashboard</a></p>
            </div>

            <div class="field">
                <div class="label">📋 Subject</div>
                <div class="value">{{ $subject }}</div>
            </div>

            <div class="field">
                <div class="label">🚨 Priority</div>
                <div class="value">
                    <span class="priority priority-{{ $priority }}">{{ ucfirst($priority) }}</span>
                </div>
            </div>

            <div class="field">
                <div class="label">📝 Description</div>
                <div class="value">{{ nl2br(e($description)) }}</div>
            </div>

            @if($steps_to_reproduce)
            <div class="field">
                <div class="label">🔄 Steps to Reproduce</div>
                <div class="value">{{ nl2br(e($steps_to_reproduce)) }}</div>
            </div>
            @endif

            @if($expected_behavior)
            <div class="field">
                <div class="label">✅ Expected Behavior</div>
                <div class="value">{{ nl2br(e($expected_behavior)) }}</div>
            </div>
            @endif

            @if($actual_behavior)
            <div class="field">
                <div class="label">❌ Actual Behavior</div>
                <div class="value">{{ nl2br(e($actual_behavior)) }}</div>
            </div>
            @endif

            <div class="metadata">
                <h4>🔧 Technical Information</h4>
                <p><strong>Timestamp:</strong> {{ $timestamp }}</p>
                <p><strong>IP Address:</strong> {{ $ip_address }}</p>
                <p><strong>Browser:</strong> {{ $browser }}</p>
                <p><strong>Ticket ID:</strong> {{ $ticket_id }}</p>
            </div>
        </div>
    </div>
</body>
</html> 