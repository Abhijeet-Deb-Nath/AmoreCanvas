<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AmoreCanvas</title>
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

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-name {
            color: #555;
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
        }

        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 60px 40px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .welcome-card h2 {
            color: #e91e63;
            font-size: 42px;
            margin-bottom: 20px;
            font-weight: normal;
        }

        .welcome-card p {
            color: #666;
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .heart-icon {
            font-size: 60px;
            margin: 30px 0;
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

        .personal-space-note {
            margin-top: 40px;
            padding: 25px;
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            border-radius: 15px;
            color: #555;
            font-size: 16px;
            font-style: italic;
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

        .find-btn, .sanctuary-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 15px 40px;
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .find-btn:hover, .sanctuary-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 107, 157, 0.4);
        }

        .partner-name {
            font-size: 24px;
            color: #e91e63;
            font-weight: bold;
            margin: 20px 0;
        }

        .invitations-section {
            margin-top: 40px;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .invitations-section h3 {
            color: #e91e63;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        .invitation-card {
            background: linear-gradient(135deg, #fff5f8 0%, #ffffff 100%);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 2px solid rgba(255, 182, 193, 0.3);
        }

        .invitation-info {
            flex: 1;
        }

        .sender-name {
            font-weight: bold;
            color: #e91e63;
            font-size: 18px;
        }

        .invitation-text {
            color: #666;
            margin-left: 10px;
        }

        .invitation-actions {
            display: flex;
            gap: 10px;
        }

        .accept-btn, .decline-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Georgia', serif;
            font-weight: bold;
        }

        .accept-btn {
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            color: white;
        }

        .accept-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 157, 0.4);
        }

        .decline-btn {
            background: #f0f0f0;
            color: #666;
        }

        .decline-btn:hover {
            background: #e0e0e0;
        }

        .invitation-section {
            margin-top: 30px;
            padding: 25px;
            background: linear-gradient(135deg, #fff5f8 0%, #ffffff 100%);
            border-radius: 15px;
            border: 2px solid rgba(255, 182, 193, 0.3);
        }

        .invitation-section h3 {
            color: #e91e63;
            font-size: 22px;
            margin-bottom: 15px;
            text-align: center;
        }

        .invitation-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .email-input {
            padding: 14px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            font-family: 'Georgia', serif;
            transition: all 0.3s ease;
        }

        .email-input:focus {
            outline: none;
            border-color: #ff6b9d;
            box-shadow: 0 0 10px rgba(255, 107, 157, 0.2);
        }

        .invite-btn-main {
            padding: 15px 30px;
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            font-family: 'Georgia', serif;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .invite-btn-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 157, 0.4);
        }

        .waiting-message {
            margin-top: 30px;
            padding: 25px;
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            border-radius: 15px;
            text-align: center;
            border: 2px solid rgba(156, 39, 176, 0.2);
        }

        .waiting-message p {
            color: #555;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .waiting-text {
            color: #9c27b0;
            font-style: italic;
            font-size: 14px;
        }

        .declined-message {
            margin-top: 30px;
            padding: 25px;
            background: linear-gradient(135deg, #ffebee 0%, #fce4ec 100%);
            border-radius: 15px;
            text-align: center;
            border: 2px solid rgba(244, 67, 54, 0.2);
        }

        .declined-message p {
            color: #555;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .try-again-text {
            color: #e91e63;
            font-style: italic;
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 25px;
                flex-wrap: wrap;
            }

            .navbar h1 {
                font-size: 24px;
            }

            .navbar-right {
                gap: 12px;
            }

            .user-name {
                font-size: 14px;
            }

            .logout-btn {
                padding: 8px 18px;
                font-size: 13px;
            }

            .container {
                padding: 0 25px;
                margin: 35px auto;
            }

            .welcome-card {
                padding: 45px 30px;
            }

            .welcome-card h2 {
                font-size: 36px;
            }

            .welcome-card p {
                font-size: 16px;
            }

            .heart-icon {
                font-size: 50px;
            }

            .personal-space-note {
                padding: 20px;
                font-size: 15px;
            }

            .invite-card,
            .bond-request-card {
                padding: 30px 25px;
            }

            .invite-card h3,
            .bond-request-card h3 {
                font-size: 26px;
            }

            .invite-card p,
            .bond-request-card p {
                font-size: 15px;
            }

            .form-group input {
                padding: 10px;
                font-size: 14px;
            }

            .btn {
                padding: 10px 20px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                padding: 12px 20px;
            }

            .navbar h1 {
                font-size: 22px;
                width: 100%;
                margin-bottom: 10px;
            }

            .navbar-right {
                width: 100%;
                justify-content: space-between;
            }

            .user-name {
                font-size: 13px;
            }

            .logout-btn {
                padding: 7px 14px;
                font-size: 12px;
            }

            .container {
                padding: 0 15px;
                margin: 25px auto;
            }

            .welcome-card {
                padding: 35px 25px;
            }

            .welcome-card h2 {
                font-size: 32px;
            }

            .welcome-card p {
                font-size: 15px;
            }

            .heart-icon {
                font-size: 45px;
                margin: 25px 0;
            }

            .personal-space-note {
                padding: 18px;
                font-size: 14px;
            }

            .invite-card,
            .bond-request-card {
                padding: 25px 20px;
            }

            .invite-card h3,
            .bond-request-card h3 {
                font-size: 24px;
            }

            .invite-card p,
            .bond-request-card p {
                font-size: 14px;
            }

            .form-group input {
                padding: 9px;
                font-size: 13px;
            }

            .btn {
                padding: 9px 18px;
                font-size: 13px;
            }

            .declined-message {
                padding: 18px;
            }

            .declined-message p {
                font-size: 15px;
            }

            .try-again-text {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>♥ AmoreCanvas</h1>
        <div class="navbar-right">
            <span class="user-name">Welcome, {{ Auth::user()->name }}</span>
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

        @if(Auth::user()->hasEternalBond())
            {{-- User has an Eternal Bond --}}
            <div class="welcome-card">
                <h2>Your Eternal Bond ♥</h2>
                <div class="heart-icon">♥</div>
                <p class="partner-name">Bonded with {{ Auth::user()->partner()->name }}</p>
                <a href="{{ route('shared.canvas') }}" class="sanctuary-btn">Enter Your Shared Canvas</a>
            </div>
        @else
            {{-- User doesn't have an Eternal Bond yet --}}
            <div class="welcome-card">
                <h2>Welcome to Your Personal Space</h2>
                <div class="heart-icon">♥</div>
                <p>Hello, {{ Auth::user()->name }}!</p>
                <p>This is your romantic canvas where love meets creativity.</p>
                
                {{-- Check if user has sent an invitation --}}
                @php
                    $sentInvitation = Auth::user()->sentInvitations()->where('status', 'pending')->first();
                    $declinedInvitation = Auth::user()->sentInvitations()->where('status', 'declined')->first();
                @endphp

                @if($sentInvitation)
                    <div class="waiting-message">
                        <p>💌 Heart Invitation sent to <strong>{{ $sentInvitation->receiver->email }}</strong></p>
                        <p class="waiting-text">Waiting for them to accept...</p>
                    </div>
                @elseif($declinedInvitation)
                    <div class="declined-message">
                        <p>💔 Your Heart Invitation to <strong>{{ $declinedInvitation->receiver->email }}</strong> was declined.</p>
                        <p class="try-again-text">You can send a new invitation to someone else.</p>
                    </div>
                    <div class="invitation-section">
                        <h3>Send Heart Invitation</h3>
                        <form method="POST" action="{{ route('send.invitation.email') }}" class="invitation-form">
                            @csrf
                            <input 
                                type="email" 
                                name="email" 
                                class="email-input" 
                                placeholder="Enter your partner's email..." 
                                required
                            >
                            <button type="submit" class="invite-btn-main">Send Heart Invitation ♥</button>
                        </form>
                    </div>
                @else
                    <div class="invitation-section">
                        <h3>Send Heart Invitation</h3>
                        <form method="POST" action="{{ route('send.invitation.email') }}" class="invitation-form">
                            @csrf
                            <input 
                                type="email" 
                                name="email" 
                                class="email-input" 
                                placeholder="Enter your partner's email..." 
                                required
                            >
                            <button type="submit" class="invite-btn-main">Send Heart Invitation ♥</button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Pending Invitations --}}
            @php
                $pendingInvitations = Auth::user()->pendingInvitations();
            @endphp
            
            @if($pendingInvitations->count() > 0)
                <div class="invitations-section">
                    <h3>Heart Invitations</h3>
                    @foreach($pendingInvitations as $invitation)
                        <div class="invitation-card">
                            <div class="invitation-info">
                                <span class="sender-name">{{ $invitation->sender->name }}</span>
                                <span class="invitation-text">sent you a Heart Invitation</span>
                            </div>
                            <div class="invitation-actions">
                                <form method="POST" action="{{ route('accept.invitation', $invitation->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="accept-btn">Accept</button>
                                </form>
                                <form method="POST" action="{{ route('decline.invitation', $invitation->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="decline-btn">Decline</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</body>
</html>
