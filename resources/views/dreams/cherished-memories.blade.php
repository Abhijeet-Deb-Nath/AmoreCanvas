<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cherished Memories - AmoreCanvas</title>
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
        .btn { padding: 10px 20px; border-radius: 20px; font-weight: bold; display: inline-block; margin: 5px; text-decoration: none; border: none; cursor: pointer; }
        .btn-success { background: #66bb6a; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-danger { background: #ef5350; color: white; }
        .empty-state { text-align: center; color: white; padding: 60px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💖 Cherished Memories</h1>
            <p>Dreams whose time has passed</p>
        </div>

        <div class="nav-buttons">
            <a href="{{ route('bucket-list.index') }}">← Back to Bucket List</a>
            <a href="{{ route('dreams.lived') }}">🌟 Lived in the Dream</a>
        </div>

        @if(session('success'))
            <div style="padding: 15px; background: white; color: #155724; border-radius: 12px; margin-bottom: 20px; text-align: center;">{{ session('success') }}</div>
        @endif

        @if($cherishedDreams->isEmpty())
            <div class="empty-state">
                <div style="font-size: 64px; margin-bottom: 20px;">✨</div>
                <h2>No Cherished Memories Yet</h2>
                <p>Dreams will appear here after their destiny date passes</p>
            </div>
        @else
            <div class="dreams-grid">
                @foreach($cherishedDreams as $dream)
                    <div class="dream-card">
                        <h3>{{ $dream->heading }}</h3>
                        @if($dream->title)<h4 style="color: #666; font-style: italic;">{{ $dream->title }}</h4>@endif
                        <p style="color: #555; margin: 15px 0;">{{ $dream->description }}</p>
                        <p style="color: #764ba2; font-weight: bold;">📍 {{ $dream->place }}</p>
                        <p style="color: #999; margin: 15px 0;">📅 {{ $dream->destiny_date->format('F j, Y \a\t g:i A') }}</p>
                        
                        <div style="margin-top: 20px;">
                            <form action="{{ route('dreams.mark-fulfilled', $dream->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">✓ We Lived This Dream!</button>
                            </form>
                            <a href="{{ route('dreams.request-reschedule', $dream->id) }}" class="btn btn-secondary" style="display: inline-block;">🔄 Reschedule</a>
                            <form action="{{ route('dreams.destroy', $dream->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this dream?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">🗑️ Delete</button>
                            </form>
                        </div>
                        <a href="{{ route('dreams.show', $dream->id) }}" class="btn btn-secondary" style="margin-top: 10px;">View Details</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
