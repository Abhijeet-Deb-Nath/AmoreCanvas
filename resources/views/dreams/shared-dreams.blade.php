<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shared Dreams - AmoreCanvas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 48px;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header p {
            font-size: 18px;
            opacity: 0.9;
        }

        .nav-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .nav-buttons a, .nav-buttons button {
            padding: 12px 25px;
            background: white;
            color: #764ba2;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .nav-buttons a:hover, .nav-buttons button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .alert {
            padding: 15px 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .dreams-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .dreams-section h2 {
            color: #764ba2;
            font-size: 28px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dreams-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .dream-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 15px;
            padding: 25px;
            transition: all 0.3s;
            border: 2px solid transparent;
            position: relative;
        }

        .dream-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            border-color: #764ba2;
        }

        .dream-card.my-dream {
            border-left: 5px solid #667eea;
        }

        .dream-card.partner-dream {
            border-left: 5px solid #f093fb;
        }

        .dream-card.shared-dream {
            border-left: 5px solid #f5576c;
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        }

        .dream-tag {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .tag-mine {
            background: #667eea;
            color: white;
        }

        .tag-partner {
            background: #f093fb;
            color: white;
        }

        .tag-shared {
            background: #f5576c;
            color: white;
        }

        .dream-card h3 {
            color: #333;
            font-size: 22px;
            margin-bottom: 8px;
        }

        .dream-card h4 {
            color: #666;
            font-size: 16px;
            margin-bottom: 12px;
            font-style: italic;
        }

        .dream-card p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .dream-card .place {
            color: #764ba2;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .dream-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .empty-state .emoji {
            font-size: 48px;
            margin-bottom: 15px;
        }

        form {
            display: inline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .container {
                padding: 0 15px;
            }

            .header {
                padding: 25px 20px;
                border-radius: 15px;
            }

            .header h1 {
                font-size: 28px;
            }

            .header p {
                font-size: 14px;
            }

            .nav-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .nav-buttons a {
                padding: 10px 18px;
                font-size: 14px;
            }

            .dreams-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .dream-card {
                padding: 20px;
            }

            .dream-card h3 {
                font-size: 20px;
            }

            .dream-card h4 {
                font-size: 16px;
            }

            .dream-card p {
                font-size: 14px;
            }

            .dream-actions {
                flex-direction: column;
            }

            .dream-actions a,
            .dream-actions button {
                width: 100%;
                text-align: center;
                font-size: 13px;
                padding: 9px 16px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 0 10px;
            }

            .header {
                padding: 20px 15px;
            }

            .header h1 {
                font-size: 24px;
            }

            .header p {
                font-size: 13px;
            }

            .nav-buttons a {
                padding: 9px 16px;
                font-size: 13px;
            }

            .dream-card {
                padding: 18px;
            }

            .dream-card h3 {
                font-size: 18px;
            }

            .dream-card h4 {
                font-size: 15px;
            }

            .dream-tag {
                font-size: 11px;
                padding: 4px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✨ Shared Dreams ✨</h1>
            <p>Dreams you and {{ $partner->name }} wish to live together</p>
        </div>

        <div class="nav-buttons">
            <a href="{{ route('shared.canvas') }}">← Back to Shared Canvas</a>
            <a href="{{ route('dreams.create') }}">+ Whisper a New Dream</a>
            <a href="{{ route('bucket-list.index') }}">📋 Bucket List</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <!-- My Dreams (Solo) -->
        <div class="dreams-section">
            <h2><span>💭</span> My Dreams</h2>
            <p style="color: #666; margin-bottom: 15px;">Dreams you've whispered, waiting for {{ $partner->name }} to join</p>
            
            @if($myDreams->isEmpty())
                <div class="empty-state">
                    <div class="emoji">🌙</div>
                    <p>You haven't whispered any solo dreams yet.</p>
                </div>
            @else
                <div class="dreams-grid">
                    @foreach($myDreams as $dream)
                        <div class="dream-card my-dream">
                            <span class="dream-tag tag-mine">My Dream</span>
                            <h3>{{ $dream->heading }}</h3>
                            @if($dream->title)
                                <h4>{{ $dream->title }}</h4>
                            @endif
                            <p>{{ Str::limit($dream->description, 100) }}</p>
                            <p class="place">📍 {{ $dream->place }}</p>
                            
                            <div class="dream-actions">
                                <a href="{{ route('dreams.show', $dream->id) }}" class="btn btn-primary">View Details</a>
                                <a href="{{ route('dreams.edit', $dream->id) }}" class="btn btn-secondary">Edit</a>
                                <form action="{{ route('dreams.destroy', $dream->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this dream?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Partner's Dreams (Solo) -->
        <div class="dreams-section">
            <h2><span>💖</span> {{ $partner->name }}'s Dreams</h2>
            <p style="color: #666; margin-bottom: 15px;">Dreams {{ $partner->name }} wants to share with you</p>
            
            @if($partnerDreams->isEmpty())
                <div class="empty-state">
                    <div class="emoji">🌟</div>
                    <p>{{ $partner->name }} hasn't shared any solo dreams yet.</p>
                </div>
            @else
                <div class="dreams-grid">
                    @foreach($partnerDreams as $dream)
                        <div class="dream-card partner-dream">
                            <span class="dream-tag tag-partner">{{ $partner->name }}'s Dream</span>
                            <h3>{{ $dream->heading }}</h3>
                            @if($dream->title)
                                <h4>{{ $dream->title }}</h4>
                            @endif
                            <p>{{ Str::limit($dream->description, 100) }}</p>
                            <p class="place">📍 {{ $dream->place }}</p>
                            
                            <div class="dream-actions">
                                <a href="{{ route('dreams.show', $dream->id) }}" class="btn btn-primary">View Details</a>
                                <form action="{{ route('dreams.validate', $dream->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Let's Dream This Together! ✨</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Shared Dreams (Both Validated) -->
        <div class="dreams-section">
            <h2><span>💝</span> Our Shared Dreams</h2>
            <p style="color: #666; margin-bottom: 15px;">Dreams you both want to live together</p>
            
            @if($sharedDreams->isEmpty())
                <div class="empty-state">
                    <div class="emoji">✨</div>
                    <p>No shared dreams yet. Validate each other's dreams to start!</p>
                </div>
            @else
                <div class="dreams-grid">
                    @foreach($sharedDreams as $dream)
                        <div class="dream-card shared-dream">
                            <span class="dream-tag tag-shared">Shared Dream</span>
                            <h3>{{ $dream->heading }}</h3>
                            @if($dream->title)
                                <h4>{{ $dream->title }}</h4>
                            @endif
                            <p>{{ Str::limit($dream->description, 100) }}</p>
                            <p class="place">📍 {{ $dream->place }}</p>
                            
                            <div class="dream-actions">
                                <a href="{{ route('dreams.show', $dream->id) }}" class="btn btn-primary">View Details</a>
                                @if(!$dream->hasPendingNegotiation())
                                    <a href="{{ route('dreams.plan-destiny', $dream->id) }}" class="btn btn-success">Planning 💫</a>
                                @else
                                    <a href="{{ route('dreams.plan-destiny', $dream->id) }}" class="btn btn-secondary">View Planning</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</body>
</html>
