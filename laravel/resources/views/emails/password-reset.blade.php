<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 20px;
        }
        h1 {
            color: #16a34a;
            margin: 0;
            font-size: 24px;
        }
        .content {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #16a34a;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #15803d;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .link-text {
            word-break: break-all;
            color: #16a34a;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{-- Uncomment if you have logo --}}
            {{-- <img src="{{ asset('storage/images/logo.png') }}" alt="Logo" class="logo"> --}}
            <h1>Reset Your Password</h1>
        </div>

        <div class="content">
            <p>Hello,</p>
            
            <p>You are receiving this email because we received a password reset request for your account.</p>
            
            <p style="text-align: center;">
                <a href="{{ $url }}" class="button">Reset Password</a>
            </p>
            
            <p>Or copy and paste this URL into your browser:</p>
            <p class="link-text">{{ $url }}</p>
            
            <div class="warning">
                <strong>⚠️ Security Notice:</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    <li>This password reset link will expire in <strong>{{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes</strong>.</li>
                    <li>If you did not request a password reset, no further action is required.</li>
                    <li>For security reasons, please do not share this link with anyone.</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL above into your web browser.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>