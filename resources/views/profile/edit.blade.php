<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - AmoreCanvas</title>
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

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-radius: 15px;
        }

        .navbar h1 {
            color: #764ba2;
            font-size: 24px;
        }

        .navbar a {
            padding: 10px 20px;
            background: #764ba2;
            color: white;
            text-decoration: none;
            border-radius: 20px;
            font-size: 14px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .section {
            background: white;
            border-radius: 20px;
            padding: 35px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .section h2 {
            color: #764ba2;
            font-size: 26px;
            margin-bottom: 10px;
        }

        .section-description {
            color: #666;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            font-family: 'Georgia', serif;
            transition: all 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #764ba2;
            box-shadow: 0 0 0 3px rgba(118, 75, 162, 0.1);
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Georgia', serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
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

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .error-text {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }

        .divider {
            height: 1px;
            background: #e0e0e0;
            margin: 30px 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
            }

            .navbar h1 {
                font-size: 22px;
            }

            .navbar a {
                font-size: 14px;
            }

            .container {
                padding: 20px 15px;
            }

            .profile-card {
                padding: 25px 20px;
            }

            .profile-card h2 {
                font-size: 24px;
            }

            .form-group label {
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
                padding: 12px 15px;
            }

            .navbar h1 {
                font-size: 20px;
            }

            .navbar a {
                font-size: 13px;
            }

            .container {
                padding: 15px 10px;
            }

            .profile-card {
                padding: 20px 15px;
            }

            .profile-card h2 {
                font-size: 22px;
            }

            .form-group {
                margin-bottom: 18px;
            }

            .form-group label {
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

            .alert {
                padding: 12px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>✨ Edit Profile</h1>
        <a href="{{ route('dashboard') }}">← Back to Dashboard</a>
    </div>

    <div class="container">
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

        @if(session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif

        <!-- Update Name -->
        <div class="section">
            <h2>👤 Update Name</h2>
            <p class="section-description">Change how your name appears throughout AmoreCanvas</p>
            
            <form action="{{ route('profile.update-name') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name">Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $user->name) }}"
                        required
                    >
                    @error('name')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update Name</button>
            </form>
        </div>

        <!-- Update Email -->
        <div class="section">
            <h2>📧 Update Email</h2>
            <p class="section-description">
                Current email: <strong>{{ $user->email }}</strong><br>
                <small style="color: #999;">You'll need to verify your new email address</small>
            </p>
            
            <form action="{{ route('profile.update-email') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="email">New Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        placeholder="Enter new email address"
                        required
                    >
                    @error('email')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Send Verification Email</button>
            </form>
        </div>

        <!-- Update Password -->
        <div class="section">
            <h2>🔒 Change Password</h2>
            <p class="section-description">Ensure your account stays secure</p>
            
            <form action="{{ route('profile.update-password') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input 
                        type="password" 
                        id="current_password" 
                        name="current_password" 
                        placeholder="Enter current password"
                        required
                    >
                    @error('current_password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="divider"></div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input 
                        type="password" 
                        id="new_password" 
                        name="new_password" 
                        placeholder="Enter new password (min 8 characters)"
                        required
                    >
                    @error('new_password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">Confirm New Password</label>
                    <input 
                        type="password" 
                        id="new_password_confirmation" 
                        name="new_password_confirmation" 
                        placeholder="Re-enter new password"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>
        </div>
    </div>
</body>
</html>
