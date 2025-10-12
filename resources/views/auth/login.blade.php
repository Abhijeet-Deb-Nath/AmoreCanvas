<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AmoreCanvas</title>
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
        }

        /* Romantic floating hearts animation */
        body::before {
            content: '♥';
            position: absolute;
            font-size: 20px;
            color: rgba(255, 255, 255, 0.1);
            animation: float 15s infinite;
            top: -10%;
            left: 10%;
        }

        body::after {
            content: '♥';
            position: absolute;
            font-size: 30px;
            color: rgba(255, 255, 255, 0.1);
            animation: float 20s infinite 5s;
            top: -10%;
            right: 20%;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.1;
            }
            90% {
                opacity: 0.1;
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
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-size: 14px;
        }

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

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #ff6b9d;
            box-shadow: 0 0 10px rgba(255, 107, 157, 0.2);
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .remember-me input[type="checkbox"] {
            margin-right: 8px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .remember-me label {
            margin-bottom: 0;
            cursor: pointer;
            font-size: 14px;
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

        .signup-link {
            text-align: center;
            margin-top: 25px;
            color: #666;
            font-size: 14px;
        }

        .signup-link a {
            color: #e91e63;
            text-decoration: none;
            font-weight: bold;
        }

        .signup-link a:hover {
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
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember Me</label>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <div class="signup-link">
            Don't have an account? <a href="{{ route('signup') }}">Sign Up</a>
        </div>
    </div>
</body>
</html>
