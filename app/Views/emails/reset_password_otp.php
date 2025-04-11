<!DOCTYPE html><html><head>    <meta charset="UTF-8">    <title>Password Reset OTP</title>    <style>        body {            font-family: Arial, sans-serif;            line-height: 1.6;            color: #333333;        }        .container {            max-width: 600px;            margin: 0 auto;            padding: 20px;        }        .header {            text-align: center;            margin-bottom: 30px;        }        .otp-code {            background-color: #f8f9fa;            padding: 15px;            text-align: center;            font-size: 24px;            font-weight: bold;            letter-spacing: 5px;            margin: 20px 0;            border-radius: 5px;        }        .expiry {
            color: #dc3545;
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Password Reset Request</h2>
        </div>

        <p>Hello,</p>
        
        <p>We received a request to reset your password. Please use the following OTP code to verify your identity:</p>

        <div class="otp-code">
            <?= $otp ?>
        </div>

        <p>This code will expire in 15 minutes (at <?= date('h:i A', strtotime($expiry)) ?>).</p>

        <div class="expiry">
            For security reasons, please do not share this code with anyone.
        </div>

        <p>If you didn't request this password reset, please ignore this email or contact support if you have concerns.</p>

        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
            <p>&copy; <?= date('Y') ?> Golden Haven Memorial Park. All rights reserved.</p>
        </div>
    </div>
</body>
</html>