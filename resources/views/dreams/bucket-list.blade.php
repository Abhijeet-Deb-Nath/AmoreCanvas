<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bucket List - AmoreCanvas</title>
    <style>
        body { font-family: 'Georgia', serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { text-align: center; color: white; margin-bottom: 40px; }
        .header h1 { font-size: 48px; margin-bottom: 10px; }
        .nav-buttons { display: flex; gap: 15px; justify-content: center; margin-bottom: 30px; flex-wrap: wrap; }
        .nav-buttons a { padding: 12px 25px; background: white; color: #764ba2; text-decoration: none; border-radius: 25px; font-weight: bold; }
        .dreams-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 25px; }
        .dream-card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); }
        .dream-card h3 { color: #764ba2; font-size: 24px; margin-bottom: 10px; }
        .destiny-date { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 12px; margin: 20px 0; text-align: center; }
        .destiny-date .date { font-size: 22px; font-weight: bold; }
        .countdown { font-size: 18px; color: #f5576c; font-weight: bold; margin: 15px 0; }
        .btn { padding: 10px 20px; border-radius: 20px; text-decoration: none; font-weight: bold; display: inline-block; margin-top: 15px; }
        .btn-primary { background: #764ba2; color: white; }
        .empty-state { text-align: center; color: white; padding: 60px; }
        .empty-state .emoji { font-size: 64px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Bucket List</h1>
            <p>Dreams with confirmed destiny dates</p>
        </div>

        <div class="nav-buttons">
            <a href="{{ route('shared.canvas') }}">← Shared Canvas</a>
            <a href="{{ route('dreams.index') }}">✨ Shared Dreams</a>
            <a href="{{ route('dreams.cherished-memories') }}">💖 Cherished Memories</a>
        </div>

        @if(session('success'))
            <div style="padding: 15px; background: white; color: #155724; border-radius: 12px; margin-bottom: 20px; text-align: center;">{{ session('success') }}</div>
        @endif

        @if($scheduledDreams->isEmpty())
            <div class="empty-state">
                <div class="emoji">🌟</div>
                <h2>Your Bucket List Awaits</h2>
                <p>Once you schedule dreams with confirmed dates, they'll appear here</p>
            </div>
        @else
            <div class="dreams-grid">
                @foreach($scheduledDreams as $dream)
                    <div class="dream-card">
                        <h3>{{ $dream->heading }}</h3>
                        @if($dream->title)<h4 style="color: #666; font-style: italic;">{{ $dream->title }}</h4>@endif
                        <p style="color: #555; margin: 15px 0;">{{ Str::limit($dream->description, 100) }}</p>
                        <p style="color: #764ba2; font-weight: bold;">📍 {{ $dream->place }}</p>
                        
                        <div class="destiny-date">
                            <div style="font-size: 14px; opacity: 0.9; margin-bottom: 5px;">Destiny Date</div>
                            <div class="date">{{ $dream->destiny_date->format('F j, Y') }}</div>
                            <div class="date">{{ $dream->destiny_date->format('g:i A') }}</div>
                        </div>

                        @php
                            $now = now();
                            $diff = $now->diff($dream->destiny_date);
                            if($dream->destiny_date->isFuture()) {
                                $countdown = '';
                                if($diff->days > 0) $countdown .= $diff->days . ' days ';
                                if($diff->h > 0) $countdown .= $diff->h . ' hours ';
                                if($diff->i > 0) $countdown .= $diff->i . ' minutes';
                                echo '<div class="countdown">⏰ ' . trim($countdown) . ' to go!</div>';
                            }
                        @endphp

                        <a href="{{ route('dreams.show', $dream->id) }}" class="btn btn-primary">View Details</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
