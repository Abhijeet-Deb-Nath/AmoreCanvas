<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Lane - AmoreCanvas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 50%, #ff9a9e 100%);
            min-height: 100vh;
            position: relative;
        }

        /* Floating hearts */
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0; }
            10%, 90% { opacity: 0.3; }
            100% { transform: translateY(-100vh) rotate(360deg); }
        }

        .heart {
            position: fixed;
            font-size: 30px;
            color: rgba(255, 255, 255, 0.3);
            animation: float 15s infinite;
            pointer-events: none;
        }

        .heart:nth-child(1) { left: 10%; animation-delay: 0s; }
        .heart:nth-child(2) { left: 25%; animation-delay: 3s; }
        .heart:nth-child(3) { left: 40%; animation-delay: 6s; }
        .heart:nth-child(4) { left: 60%; animation-delay: 2s; }
        .heart:nth-child(5) { left: 75%; animation-delay: 5s; }
        .heart:nth-child(6) { left: 90%; animation-delay: 8s; }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar h1 {
            color: #e91e63;
            font-size: 28px;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .back-btn, .logout-btn {
            padding: 10px 25px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .back-btn:hover, .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(245, 87, 108, 0.3);
        }

        .container {
            max-width: 1400px;
            margin: 40px auto;
            padding: 0 40px;
            position: relative;
            z-index: 10;
        }

        .header {
            background: rgba(255, 255, 255, 0.98);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(255, 105, 135, 0.3);
            margin-bottom: 40px;
            text-align: center;
        }

        .header h2 {
            color: #e91e63;
            font-size: 42px;
            margin-bottom: 15px;
        }

        .header p {
            color: #666;
            font-size: 18px;
        }

        .create-btn {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #ff6b9d 0%, #e91e63 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-size: 18px;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .create-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(233, 30, 99, 0.4);
        }

        .filter-section {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 30px 0;
            gap: 15px;
        }

        .filter-label {
            font-size: 16px;
            color: #666;
            font-weight: bold;
        }

        .filter-dropdown {
            padding: 10px 20px;
            border: 2px solid #e91e63;
            border-radius: 8px;
            font-size: 16px;
            font-family: 'Georgia', serif;
            background: white;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-dropdown:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(233, 30, 99, 0.3);
        }

        .memories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .memory-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid rgba(255, 182, 193, 0.3);
        }

        .memory-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(255, 105, 135, 0.3);
        }

        .memory-media {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 64px;
            color: rgba(255, 255, 255, 0.8);
        }

        .memory-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .memory-media video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .memory-content {
            padding: 25px;
        }

        .memory-date {
            font-size: 14px;
            color: #ff6b9d;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .memory-heading {
            font-size: 24px;
            color: #e91e63;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .memory-title {
            font-size: 16px;
            color: #888;
            margin-bottom: 12px;
            font-style: italic;
        }

        .memory-description {
            font-size: 15px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .memory-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 182, 193, 0.3);
        }

        .memory-author {
            font-size: 14px;
            color: #666;
        }

        .memory-reviews {
            font-size: 14px;
            color: #ff6b9d;
        }

        .empty-state {
            text-align: center;
            padding: 60px 40px;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .empty-state h3 {
            color: #e91e63;
            font-size: 32px;
            margin-bottom: 20px;
        }

        .empty-state p {
            color: #666;
            font-size: 18px;
            margin-bottom: 30px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 15px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>
    <!-- Floating hearts -->
    <div class="heart">♥</div>
    <div class="heart">♥</div>
    <div class="heart">♥</div>
    <div class="heart">♥</div>
    <div class="heart">♥</div>
    <div class="heart">♥</div>

    <div class="navbar">
        <h1>📸 Memory Lane</h1>
        <div class="navbar-right">
            <a href="{{ route('shared.canvas') }}" class="back-btn">← Back to Canvas</a>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="header">
            <h2>Your Memory Lane</h2>
            <p>Where your precious moments live forever 💕</p>
            <a href="{{ route('memory-lane.create') }}" class="create-btn">+ Create New Memory</a>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <label class="filter-label" for="memoryFilter">Filter by Type:</label>
            <select id="memoryFilter" class="filter-dropdown" onchange="filterMemories(this.value)">
                <option value="all" {{ isset($filter) && $filter == 'all' ? 'selected' : '' }}>All Memories</option>
                <option value="image" {{ isset($filter) && $filter == 'image' ? 'selected' : '' }}>📷 Photos</option>
                <option value="video" {{ isset($filter) && $filter == 'video' ? 'selected' : '' }}>🎥 Videos</option>
                <option value="audio" {{ isset($filter) && $filter == 'audio' ? 'selected' : '' }}>🎵 Audio</option>
                <option value="text" {{ isset($filter) && $filter == 'text' ? 'selected' : '' }}>💌 Love Letters</option>
            </select>
        </div>

        @if($memories->count() > 0)
            <div class="memories-grid">
                @foreach($memories as $memory)
                    <a href="{{ route('memory-lane.show', $memory->id) }}" style="text-decoration: none; color: inherit;">
                        <div class="memory-card">
                            <div class="memory-media">
                                @if($memory->media_path && $memory->media_type === 'image')
                                    <img src="{{ asset('storage/' . $memory->media_path) }}" alt="{{ $memory->heading }}">
                                @elseif($memory->media_path && $memory->media_type === 'video')
                                    <video>
                                        <source src="{{ asset('storage/' . $memory->media_path) }}" type="video/mp4">
                                    </video>
                                @elseif($memory->media_type === 'audio')
                                    🎵
                                @elseif($memory->media_type === 'text')
                                    📝
                                @else
                                    📸
                                @endif
                            </div>
                            <div class="memory-content">
                                <div class="memory-date">{{ $memory->story_date->format('F j, Y') }}</div>
                                <div class="memory-heading">{{ $memory->heading }}</div>
                                @if($memory->title)
                                    <div class="memory-title">{{ $memory->title }}</div>
                                @endif
                                <div class="memory-description">{{ $memory->description }}</div>
                                <div class="memory-footer">
                                    <div class="memory-author">By {{ $memory->user->name }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <h3>No Memories Yet</h3>
                <p>Start creating your beautiful story together!</p>
                <a href="{{ route('memory-lane.create') }}" class="create-btn">Create Your First Memory</a>
            </div>
        @endif
    </div>

    <script>
        function filterMemories(type) {
            window.location.href = '{{ route("memory-lane.index") }}?type=' + type;
        }
    </script>
</body>
</html>
