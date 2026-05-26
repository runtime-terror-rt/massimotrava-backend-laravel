<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Massimotrava | Security Code</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');

        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; 
            background-color: #f3f4f6; 
            margin: 0; 
            padding: 0; 
        }

        .email-wrapper {
            width: 100%;
            padding: 40px 0;
            background-color: #f3f4f6;
        }

        .main-card {
            max-width: 520px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.04);
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .brand-header {
            padding: 40px 0 20px 0;
            text-align: center;
        }
        .brand-logo {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #111827;
            text-transform: uppercase;
        }

        .body-content {
            padding: 0 40px 40px 40px;
            text-align: center;
        }

        h2 {
            color: #111827;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        p {
            color: #4b5563;
            font-size: 15px;
            line-height: 1.6;
            margin-top: 0;
        }

        .otp-section {
            background-color: #f9fafb;
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            border: 1px solid #f3f4f6;
        }

        .otp-label {
            font-size: 12px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            display: block;
        }

        .otp-code {
            font-size: 38px;
            font-weight: 800;
            color: #4f46e5; /* Indigo */
            letter-spacing: 8px;
            display: block;
        }

        .info-text {
            font-size: 13px;
            color: #9ca3af;
            margin-top: 20px;
        }

        .footer {
            padding: 30px 40px;
            background-color: #fafafa;
            border-top: 1px solid #f3f4f6;
            text-align: center;
        }

        .footer p {
            font-size: 12px;
            color: #9ca3af;
            margin: 0;
        }

        @media only screen and (max-width: 600px) {
            .main-card { margin: 0 15px; border-radius: 16px; }
            .body-content { padding: 0 25px 30px 25px; }
            .otp-code { font-size: 32px; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="main-card">
            <div class="brand-header">
                <div class="brand-logo">Vyralabs</div>
            </div>

            <div class="body-content">
                <h2>Verify your email</h2>
                <p>Hi <span style="color: #111827; font-weight: 600;">{{ $user->name }}</span>, use the code below to complete your registration and start your longevity journey.</p>
                
                <div class="otp-section">
                    <span class="otp-label">Verification Code</span>
                    <span class="otp-code">{{ $otp }}</span>
                    <div style="margin-top: 15px; font-size: 13px; color: #ef4444; font-weight: 600;">
                        Valid for 5 minutes only
                    </div>
                </div>

                <p class="info-text">
                    If you didn't request this, you can safely ignore this email. 
                    Need help? <a href="mailto:support@massimotrava.com" style="color: #4f46e5; text-decoration: none; font-weight: 600;">Contact Support</a>
                </p>
            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} Massimotrava. All rights reserved.</p>
                <p style="margin-top: 5px;">Via della Salute, Milan, Italy</p>
            </div>
        </div>
    </div>
</body>
</html>