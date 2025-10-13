<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 40px 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #764ba2;
            font-size: 32px;
            margin: 0 0 10px 0;
        }
        .emoji {
            font-size: 48px;
            margin: 20px 0;
        }
        .content {
            color: #333;
            line-height: 1.8;
            margin: 25px 0;
        }
        .verify-button {
            text-align: center;
            margin: 35px 0;
        }
        .verify-button a {
            display: inline-block;
            padding: 18px 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 5px 20px rgba(118, 75, 162, 0.4);
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #888;
            font-size: 14px;
        }
        .link-text {
            word-break: break-all;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            color: #666;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="emoji">💌</div>
            <h1>Welcome to AmoreCanvas!</h1>
        </div>

        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            
            <p>Thank you for joining AmoreCanvas! We're excited to have you on this beautiful journey.</p>
            
            <p>To complete your registration and start creating memories, please verify your email address by clicking the button below:</p>
        </div>

        <div class="verify-button">
            <a href="{{ $verificationUrl }}">Verify My Email ✨</a>
        </div>

        <div class="content">
            <p><strong>Why verify?</strong> This helps us ensure the security of your account and allows you to receive important notifications about your shared dreams and memories.</p>
        </div>

        <div class="link-text">
            <p style="margin: 0 0 10px 0; font-weight: bold; color: #333;">If the button doesn't work, copy and paste this link:</p>
            {{ $verificationUrl }}
        </div>

        <div class="footer">
            <p>With love from AmoreCanvas 💕</p>
            <p style="margin-top: 10px; font-size: 12px;">
                If you didn't create an account, please ignore this email.
            </p>
        </div>
    </div>
</body>
</html>
