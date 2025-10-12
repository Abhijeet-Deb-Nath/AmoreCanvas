<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - AmoreCanvas</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 20px 0;
        }

        /* Romantic floating hearts animation */
        body::before {
            content: '♥';
            position: absolute;
            font-size: 25px;
            color: rgba(255, 255, 255, 0.15);
            animation: float 18s infinite;
            top: -10%;
            left: 15%;
        }

        body::after {
            content: '♥';
            position: absolute;
            font-size: 35px;
            color: rgba(255, 255, 255, 0.15);
            animation: float 22s infinite 7s;
            top: -10%;
            right: 25%;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.15;
            }
            90% {
                opacity: 0.15;
            }
            100% {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }

        .container {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 245, 248, 0.98) 100%);
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(255, 105, 135, 0.3);
            width: 100%;
            max-width: 420px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 182, 193, 0.3);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo h1 {
            color: #e91e63;
            font-size: 36px;
            margin-bottom: 5px;
            font-weight: normal;
        }

        .logo p {
            color: #888;
            font-size: 14px;
            font-style: italic;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: 'Georgia', serif;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #ff6b9d;
            box-shadow: 0 0 10px rgba(255, 107, 157, 0.2);
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #ff6b9d 0%, #ff8fab 50%, #ffa8b9 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Georgia', serif;
            font-weight: bold;
            margin-top: 10px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 107, 157, 0.4);
        }

        .error {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #c33;
        }

        .error ul {
            margin-left: 20px;
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            color: #666;
            font-size: 14px;
        }

        .login-link a {
            color: #e91e63;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>♥ AmoreCanvas ♥</h1>
            <p>a living canvas of love</p>
        </div>

        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('signup') }}">
            @csrf
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn">Sign Up</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="{{ route('login') }}">Login</a>
        </div>
    </div>
</body>
</html>
