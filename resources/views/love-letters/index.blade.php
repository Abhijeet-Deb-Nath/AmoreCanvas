<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Letter Box - AmoreCanvas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #ffeaa7 0%, #fd79a8 50%, #fdcb6e 100%);
            min-height: 100vh;
            position: relative;
        }

        /* Floating envelopes */
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0; }
            10%, 90% { opacity: 0.2; }
            100% { transform: translateY(-100vh) rotate(360deg); }
        }

        .envelope-float {
            position: fixed;
            font-size: 40px;
            color: rgba(255, 255, 255, 0.3);
            animation: float 20s infinite;
            pointer-events: none;
        }

        .envelope-float:nth-child(1) { left: 15%; animation-delay: 0s; }
        .envelope-float:nth-child(2) { left: 35%; animation-delay: 4s; }
        .envelope-float:nth-child(3) { left: 55%; animation-delay: 8s; }
        .envelope-float:nth-child(4) { left: 75%; animation-delay: 12s; }
        .envelope-float:nth-child(5) { left: 85%; animation-delay: 16s; }

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

        .back-btn, .compose-btn {
            padding: 10px 25px;
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .compose-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .back-btn:hover, .compose-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .header-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .header-section h2 {
            font-size: 36px;
            color: white;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .header-section p {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.9);
        }

        .filter-tabs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .filter-tab {
            padding: 12px 30px;
            background: rgba(255, 255, 255, 0.3);
            color: white;
            border: 2px solid white;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .filter-tab.active {
            background: white;
            color: #e91e63;
        }

        .filter-tab:hover {
            transform: translateY(-2px);
        }

        .letters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .letter-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .letter-card::before {
            content: '💌';
            position: absolute;
            font-size: 100px;
            top: -20px;
            right: -20px;
            opacity: 0.1;
            transform: rotate(15deg);
        }

        .letter-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .letter-card.unread {
            border-left: 5px solid #e91e63;
        }

        .letter-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .unread-badge {
            background: #e91e63;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }

        .letter-title {
            font-size: 22px;
            color: #333;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .letter-from {
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .letter-date {
            color: #999;
            font-size: 13px;
            margin-top: 10px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            margin-top: 40px;
        }

        .empty-state-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 16px;
            color: #666;
            margin-bottom: 25px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .letters-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .navbar {
                padding: 15px 20px;
            }

            .navbar h1 {
                font-size: 22px;
            }

            .navbar-right {
                gap: 10px;
            }

            .back-btn,
            .logout-btn {
                font-size: 13px;
                padding: 8px 16px;
            }

            .header-section h2 {
                font-size: 28px;
            }

            .header-section p {
                font-size: 14px;
            }

            .write-btn {
                padding: 12px 24px;
                font-size: 15px;
            }

            .letter-card {
                padding: 20px;
            }

            .letter-card h3 {
                font-size: 20px;
            }

            .empty-state h3 {
                font-size: 28px;
            }

            .empty-state p {
                font-size: 15px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }

            .navbar {
                padding: 12px 15px;
                flex-wrap: wrap;
            }

            .navbar h1 {
                font-size: 20px;
                width: 100%;
                margin-bottom: 10px;
            }

            .navbar-right {
                width: 100%;
                justify-content: space-between;
            }

            .back-btn,
            .logout-btn {
                font-size: 12px;
                padding: 7px 14px;
            }

            .header-section {
                padding: 25px 20px;
            }

            .header-section h2 {
                font-size: 24px;
            }

            .header-section p {
                font-size: 13px;
            }

            .write-btn {
                padding: 11px 20px;
                font-size: 14px;
            }

            .letter-card {
                padding: 18px;
            }

            .letter-card h3 {
                font-size: 18px;
            }

            .letter-meta {
                font-size: 12px;
            }

            .empty-state {
                padding: 45px 20px;
            }

            .empty-state h3 {
                font-size: 24px;
            }

            .empty-state p {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Floating envelopes -->
    <div class="envelope-float">💌</div>
    <div class="envelope-float">💌</div>
    <div class="envelope-float">💌</div>
    <div class="envelope-float">💌</div>
    <div class="envelope-float">💌</div>

    <!-- Navigation -->
    <nav class="navbar">
        <h1>💌 Letter Box</h1>
        <div class="navbar-right">
            <a href="{{ route('love-letters.create') }}" class="compose-btn">✍️ Write a Letter</a>
            <a href="{{ route('dashboard') }}" class="back-btn">← Back to Dashboard</a>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <div class="header-section">
            <h2>Your Letters from {{ $partner->name }} 💕</h2>
            <p>Words of love that traveled through time to reach your heart</p>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <a href="{{ route('love-letters.index', ['filter' => 'unread']) }}" 
               class="filter-tab {{ $filter === 'unread' ? 'active' : '' }}">
                Unread Letters
            </a>
            <a href="{{ route('love-letters.index', ['filter' => 'all']) }}" 
               class="filter-tab {{ $filter === 'all' ? 'active' : '' }}">
                All Letters
            </a>
        </div>

        @if(session('success'))
            <div style="background: #4CAF50; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #f44336; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                {{ session('error') }}
            </div>
        @endif

        <!-- Letters Grid -->
        @if($letters->count() > 0)
            <div class="letters-grid">
                @foreach($letters as $letter)
                    <div class="letter-card {{ !$letter->isRead() ? 'unread' : '' }}" 
                         onclick="window.location.href='{{ route('love-letters.show', $letter->id) }}'">
                        <div class="letter-header">
                            @if(!$letter->isRead())
                                <span class="unread-badge">NEW</span>
                            @endif
                        </div>
                        <h3 class="letter-title">{{ $letter->title }}</h3>
                        <p class="letter-from">From: {{ $letter->sender->name }}</p>
                        <p class="letter-date">
                            Delivered: {{ $letter->delivered_at->format('F j, Y \a\t g:i A') }}
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h3>No Letters Yet</h3>
                <p>
                    @if($filter === 'unread')
                        You have no unread letters at the moment.
                    @else
                        Your letter box is empty. Letters from {{ $partner->name }} will appear here once delivered.
                    @endif
                </p>
                <a href="{{ route('love-letters.create') }}" class="compose-btn">Write Your First Letter 💌</a>
            </div>
        @endif
    </div>
</body>
</html>
