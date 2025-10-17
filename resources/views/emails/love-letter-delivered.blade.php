<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Love Letter Delivered</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #ff6b9d 0%, #c06c84 100%);
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
            color: #e91e63;
            font-size: 32px;
            margin: 0 0 10px 0;
        }
        .envelope-icon {
            font-size: 64px;
            margin: 20px 0;
            text-align: center;
        }
        .letter-preview {
            background: linear-gradient(135deg, #fff5f8 0%, #ffe6f0 100%);
            border-left: 4px solid #e91e63;
            padding: 25px;
            margin: 25px 0;
            border-radius: 12px;
            position: relative;
        }
        .letter-preview::before {
            content: '"';
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 48px;
            color: #e91e63;
            opacity: 0.2;
        }
        .letter-preview h2 {
            color: #e91e63;
            font-size: 24px;
            margin: 0 0 15px 0;
        }
        .letter-preview p {
            color: #555;
            line-height: 1.8;
            margin: 10px 0;
            font-size: 16px;
        }
        .letter-preview .from {
            font-weight: bold;
            color: #e91e63;
            margin-top: 20px;
        }
        .cta-button {
            text-align: center;
            margin: 30px 0;
        }
        .cta-button a {
            display: inline-block;
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
            color: white;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 30px;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.4);
            transition: all 0.3s;
        }
        .cta-button a:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.5);
        }
        .romantic-message {
            text-align: center;
            color: #666;
            font-style: italic;
            margin: 20px 0;
            line-height: 1.8;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #888;
            font-size: 14px;
        }
        .hearts {
            text-align: center;
            margin: 20px 0;
        }
        .hearts span {
            display: inline-block;
            animation: heartbeat 1.5s infinite;
            margin: 0 5px;
        }
        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="envelope-icon">💌</div>
            <h1>A Love Letter Has Arrived!</h1>
        </div>

        <div class="romantic-message">
            <p>Words written with love have found their way to your heart...</p>
        </div>

        <div class="hearts">
            <span>❤️</span>
            <span style="animation-delay: 0.3s;">💕</span>
            <span style="animation-delay: 0.6s;">💖</span>
        </div>

        <div class="letter-preview">
            <h2>{{ $loveLetter->title }}</h2>
            <p class="from">From: {{ $loveLetter->sender->name }}</p>
            <p>Sent with love on {{ $loveLetter->created_at->format('F j, Y') }}</p>
        </div>

        <div class="romantic-message">
            <p>Someone has poured their heart out for you. This letter has been waiting for the perfect moment to reach you, and that moment is now.</p>
        </div>

        <div class="cta-button">
            <a href="{{ route('love-letters.show', $loveLetter->id) }}">Open Your Letter 💕</a>
        </div>

        <div class="footer">
            <p>With love,<br><strong>AmoreCanvas</strong></p>
            <p style="margin-top: 15px; font-size: 12px;">This letter is for your eyes only. Keep it close to your heart. 💝</p>
        </div>
    </div>
</body>
</html>
