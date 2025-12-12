<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #ff6b6b;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 40px;
            text-align: center;
        }
        .content p {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
        }
        .reset-code {
            display: inline-block;
            background-color: #fff5f5;
            border: 2px dashed #ff6b6b;
            padding: 20px 40px;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #ff6b6b;
            margin: 20px 0;
            border-radius: 5px;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
        }
        .warning p {
            margin: 0;
            font-size: 14px;
            color: #856404;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #777;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kebun Raya</h1>
        </div>
        <div class="content">
            <h2>Hi {{ $userName }},</h2>
            <p>We received a request to reset your password. Use the following recovery code to reset your password:</p>

            <div class="reset-code">
                {{ $resetCode }}
            </div>

            <p>This code will expire in <strong>10 minutes</strong>.</p>

            <div class="warning">
                <p><strong>Security Notice:</strong> If you didn't request a password reset, please ignore this email and ensure your account is secure.</p>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Kebun Raya. All rights reserved.</p>
            <p>This is an automated email, please do not reply.</p>
        </div>
    </div>
</body>
</html>
