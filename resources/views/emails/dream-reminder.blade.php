<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dream Reminder</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 40px 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #764ba2;
            font-size: 28px;
            margin: 0 0 10px 0;
        }
        .header .time-badge {
            display: inline-block;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 10px 25px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }
        .dream-details {
            background: #f8f9fa;
            border-left: 4px solid #764ba2;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .dream-details h2 {
            color: #764ba2;
            font-size: 22px;
            margin: 0 0 15px 0;
        }
        .dream-details p {
            color: #333;
            line-height: 1.6;
            margin: 10px 0;
        }
        .dream-details .label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 120px;
        }
        .dream-date {
            text-align: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin: 25px 0;
        }
        .dream-date h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
            opacity: 0.9;
        }
        .dream-date .date {
            font-size: 24px;
            font-weight: bold;
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #888;
            font-size: 14px;
        }
        .emoji {
            font-size: 32px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✨ Your Dream Awaits ✨</h1>
            <div class="time-badge">
                @if($notificationType === '24_hours')
                    ⏰ 24 Hours Until Your Dream
                @elseif($notificationType === '1_hour')
                    ⏰ 1 Hour Until Your Dream
                @elseif($notificationType === '10_minutes')
                    ⏰ 10 Minutes Until Your Dream
                @else
                    💖 It's Time to Live Your Dream!
                @endif
            </div>
        </div>

        <div class="emoji">
            @if($notificationType === 'exact_time')
                🎉🌟💖
            @else
                🌙💫✨
            @endif
        </div>

        <div class="dream-details">
            <h2>{{ $dream->heading }}</h2>
            
            @if($dream->title)
                <p><span class="label">Title:</span> {{ $dream->title }}</p>
            @endif
            
            <p><span class="label">Description:</span> {{ $dream->description }}</p>
            
            <p><span class="label">Place:</span> 📍 {{ $dream->place }}</p>
        </div>

        <div class="dream-date">
            <h3>Destiny Date</h3>
            <div class="date">
                {{ $dream->destiny_date->format('l, F j, Y') }}
            </div>
            <div class="date">
                {{ $dream->destiny_date->format('g:i A') }}
            </div>
        </div>

        @if($notificationType === 'exact_time')
            <p style="text-align: center; font-size: 18px; color: #764ba2; font-weight: bold; margin: 25px 0;">
                🎊 The moment has arrived! Go live your dream together! 🎊
            </p>
        @elseif($notificationType === '10_minutes')
            <p style="text-align: center; font-size: 16px; color: #555; margin: 25px 0;">
                Get ready! Your beautiful moment is just minutes away. 💕
            </p>
        @elseif($notificationType === '1_hour')
            <p style="text-align: center; font-size: 16px; color: #555; margin: 25px 0;">
                One hour until your dream comes true. Prepare your hearts! 💝
            </p>
        @else
            <p style="text-align: center; font-size: 16px; color: #555; margin: 25px 0;">
                Tomorrow is the day! Get everything ready for your special moment. 🌟
            </p>
        @endif

        <div class="footer">
            <p>With love from AmoreCanvas 💕</p>
            <p style="font-size: 12px; margin-top: 10px;">
                This is an automated reminder for your scheduled dream.
            </p>
        </div>
    </div>
</body>
</html>
