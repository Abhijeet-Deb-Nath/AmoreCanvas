<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lived in the Dream - AmoreCanvas</title>
    <style>
        body { font-family: 'Georgia', serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { text-align: center; color: white; margin-bottom: 40px; }
        .header h1 { font-size: 48px; margin-bottom: 10px; }
        .nav-buttons { display: flex; gap: 15px; justify-content: center; margin-bottom: 30px; flex-wrap: wrap; }
        .nav-buttons a { padding: 12px 25px; background: white; color: #764ba2; text-decoration: none; border-radius: 25px; font-weight: bold; }
        .dreams-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 25px; }
        .dream-card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); border-left: 5px solid #f5576c; }
        .dream-card h3 { color: #764ba2; font-size: 24px; margin-bottom: 10px; }
        .btn { padding: 10px 20px; border-radius: 20px; font-weight: bold; display: inline-block; margin-top: 15px; text-decoration: none; }
        .btn-primary { background: #764ba2; color: white; }
        .btn-success { background: #66bb6a; color: white; }
        .empty-state { text-align: center; color: white; padding: 60px; }

        /* Responsive Design */
        @media (max-width: 768px) {
            body { padding: 15px; }
            .header h1 { font-size: 36px; }
            .header p { font-size: 14px; }
            .nav-buttons { gap: 10px; }
            .nav-buttons a { padding: 10px 18px; font-size: 14px; }
            .dreams-grid { grid-template-columns: 1fr; gap: 20px; }
            .dream-card { padding: 20px; }
            .dream-card h3 { font-size: 20px; }
        }

        @media (max-width: 480px) {
            body { padding: 10px; }
            .header h1 { font-size: 28px; }
            .nav-buttons a { padding: 9px 16px; font-size: 13px; }
            .dream-card { padding: 18px; }
            .dream-card h3 { font-size: 18px; }
            .btn { padding: 9px 18px; font-size: 13px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌟 Lived in the Dream</h1>
            <p>Dreams you've fulfilled together</p>
        </div>

        <div class="nav-buttons">
            <a href="{{ route('dreams.cherished-memories') }}">← Cherished Memories</a>
            <a href="{{ route('bucket-list.index') }}">📋 Bucket List</a>
        </div>

        @if(session('success'))
            <div style="padding: 15px; background: white; color: #155724; border-radius: 12px; margin-bottom: 20px; text-align: center;">{{ session('success') }}</div>
        @endif

        @if($fulfilledDreams->isEmpty())
            <div class="empty-state">
                <div style="font-size: 64px; margin-bottom: 20px;">💖</div>
                <h2>No Fulfilled Dreams Yet</h2>
                <p>Mark cherished memories as lived to see them here</p>
            </div>
        @else
            <div class="dreams-grid">
                @foreach($fulfilledDreams as $dream)
                    <div class="dream-card">
                        <div style="display: inline-block; background: #f5576c; color: white; padding: 5px 15px; border-radius: 15px; font-size: 12px; font-weight: bold; margin-bottom: 15px;">✨ FULFILLED</div>
                        <h3>{{ $dream->heading }}</h3>
                        @if($dream->title)<h4 style="color: #666; font-style: italic;">{{ $dream->title }}</h4>@endif
                        <p style="color: #555; margin: 15px 0;">{{ $dream->description }}</p>
                        <p style="color: #764ba2; font-weight: bold;">📍 {{ $dream->place }}</p>
                        <p style="color: #999; margin: 10px 0;">📅 Original Date: {{ $dream->destiny_date->format('F j, Y') }}</p>
                        <p style="color: #999;">💖 Fulfilled: {{ $dream->fulfilled_at->format('F j, Y') }}</p>
                        
                        <a href="{{ route('dreams.show', $dream->id) }}" class="btn btn-primary">View Details</a>
                        <a href="{{ route('dreams.create-memory', $dream->id) }}" class="btn btn-success">+ Add to Memory Lane</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
