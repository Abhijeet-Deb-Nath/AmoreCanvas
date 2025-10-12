<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Love Sanctuary - AmoreCanvas</title>
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
            overflow-x: hidden;
        }

        /* Romantic floating hearts */
        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.3;
            }
            90% {
                opacity: 0.3;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }

        .heart {
            position: fixed;
            font-size: 30px;
            color: rgba(255, 255, 255, 0.3);
            animation: float 15s infinite;
            pointer-events: none;
        }

        .heart:nth-child(1) { left: 10%; animation-delay: 0s; }
        .heart:nth-child(2) { left: 25%; animation-delay: 3s; font-size: 25px; }
        .heart:nth-child(3) { left: 40%; animation-delay: 6s; font-size: 35px; }
        .heart:nth-child(4) { left: 60%; animation-delay: 2s; font-size: 28px; }
        .heart:nth-child(5) { left: 75%; animation-delay: 5s; font-size: 32px; }
        .heart:nth-child(6) { left: 90%; animation-delay: 8s; font-size: 26px; }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 100;
        }

        .navbar h1 {
            color: #e91e63;
            font-size: 28px;
            font-weight: normal;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar-right a {
            color: #e91e63;
            text-decoration: none;
            font-size: 16px;
        }

        .logout-btn {
            padding: 10px 25px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Georgia', serif;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(245, 87, 108, 0.3);
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 40px;
            position: relative;
            z-index: 10;
        }

        .sanctuary-header {
            background: rgba(255, 255, 255, 0.98);
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(255, 105, 135, 0.3);
            text-align: center;
            margin-bottom: 40px;
            border: 2px solid rgba(255, 182, 193, 0.3);
        }

        .sanctuary-header h2 {
            color: #e91e63;
            font-size: 42px;
            margin-bottom: 20px;
            font-weight: normal;
        }

        .bond-info {
            font-size: 24px;
            color: #555;
            margin-bottom: 15px;
        }

        .partner-names {
            font-size: 28px;
            color: #e91e63;
            font-weight: bold;
            margin: 20px 0;
        }

        .heart-divider {
            font-size: 40px;
            color: #ff6b9d;
            margin: 20px 0;
            animation: heartbeat 1.5s infinite;
        }

        @keyframes heartbeat {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        .bond-date {
            color: #888;
            font-size: 16px;
            font-style: italic;
        }

        .sanctuary-content {
            background: rgba(255, 255, 255, 0.98);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border: 2px solid rgba(255, 182, 193, 0.3);
        }

        .sanctuary-content h3 {
            color: #e91e63;
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
        }

        .sanctuary-content p {
            color: #666;
            font-size: 18px;
            line-height: 1.8;
            text-align: center;
            margin-bottom: 15px;
        }

        .coming-soon {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            padding: 30px;
            border-radius: 15px;
            margin-top: 30px;
            text-align: center;
        }

        .coming-soon h4 {
            color: #e91e63;
            font-size: 22px;
            margin-bottom: 15px;
        }

        .coming-soon p {
            color: #555;
            font-size: 16px;
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
        <h1>♥ AmoreCanvas</h1>
        <div class="navbar-right">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="sanctuary-header">
            <h2>Love Sanctuary</h2>
            <div class="heart-divider">♥</div>
            <div class="partner-names">
                {{ Auth::user()->name }} & {{ $partner->name }}
            </div>
            <div class="bond-info">Eternal Bond</div>
            <div class="bond-date">
                Together since {{ $bond->bonded_at->format('F j, Y') }}
            </div>
        </div>

        <div class="sanctuary-content">
            <h3>Your Shared Sacred Space</h3>
            <p>Welcome to your Love Sanctuary, a special place created just for you two.</p>
            <p>This is where your love story unfolds, a canvas waiting to be painted with your memories.</p>
            
            <div class="coming-soon">
                <h4>✨ More Magic Coming Soon ✨</h4>
                <p>We're preparing beautiful features for your sanctuary...</p>
                <p>Share memories, create together, and celebrate your eternal bond.</p>
            </div>
        </div>
    </div>
</body>
</html>
