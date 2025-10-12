<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a Sanctuary - AmoreCanvas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            min-height: 100vh;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            color: #e91e63;
            font-size: 28px;
            font-weight: normal;
        }

        .navbar a {
            color: #e91e63;
            text-decoration: none;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .navbar a:hover {
            color: #ff6b9d;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 0 40px;
        }

        .search-section {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .search-section h2 {
            color: #e91e63;
            font-size: 32px;
            margin-bottom: 20px;
            text-align: center;
        }

        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .search-input {
            flex: 1;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            font-family: 'Georgia', serif;
        }

        .search-input:focus {
            outline: none;
            border-color: #ff6b9d;
        }

        .search-btn {
            padding: 12px 30px;
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            cursor: pointer;
            font-family: 'Georgia', serif;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 157, 0.4);
        }

        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .user-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 182, 193, 0.2);
        }

        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(255, 107, 157, 0.2);
        }

        .user-icon {
            font-size: 50px;
            margin-bottom: 15px;
        }

        .user-name {
            color: #e91e63;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .user-email {
            color: #888;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .invite-btn {
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            font-family: 'Georgia', serif;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .invite-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 157, 0.4);
        }

        .no-users {
            text-align: center;
            padding: 60px 20px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            color: #888;
            font-size: 18px;
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
    <div class="navbar">
        <h1>♥ AmoreCanvas</h1>
        <a href="{{ route('dashboard') }}">← Back to Dashboard</a>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="search-section">
            <h2>Create a Sanctuary with Your Partner</h2>
            <form method="GET" action="{{ route('find.soulmate') }}" class="search-form">
                <input 
                    type="email" 
                    name="search" 
                    class="search-input" 
                    placeholder="Search by partner's email..." 
                    value="{{ $search }}"
                >
                <button type="submit" class="search-btn">Search</button>
            </form>
        </div>

        @if($users->count() > 0)
            <div class="users-grid">
                @foreach($users as $user)
                    <div class="user-card">
                        <div class="user-icon">👤</div>
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-email">{{ $user->email }}</div>
                        <form method="POST" action="{{ route('send.invitation', $user->id) }}">
                            @csrf
                            <button type="submit" class="invite-btn">Send Heart Invitation ♥</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-users">
                <p>{{ $search ? 'No users found with that email address.' : 'Enter your partner\'s email to search.' }}</p>
            </div>
        @endif
    </div>
</body>
</html>
