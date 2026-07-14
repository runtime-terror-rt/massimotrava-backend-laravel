<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Unsubscribed - Vyralabs</title>
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: 'Inter', Arial, sans-serif;
      background: #05070b;
      color: #e2e8f0;
      height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 24px;
    }
    .box {
      max-width: 420px;
      width: 100%;
      background: #0d1420;
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 16px;
      padding: 40px 32px;
    }
    .logo {
      height: 32px;
      width: auto;
      margin-bottom: 24px;
    }
    .icon-circle {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      background: rgba(34,211,238,0.1);
      border: 1px solid rgba(34,211,238,0.25);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px auto;
    }
    .icon-circle svg { width: 24px; height: 24px; }
    h1 { font-size: 20px; color: #fff; margin: 0 0 12px 0; font-weight: 700; }
    p { color: #94a3b8; font-size: 14px; line-height: 1.7; margin: 0 0 24px 0; }
    .email-chip {
      display: inline-block;
      padding: 4px 12px;
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 20px;
      font-size: 13px;
      color: #e2e8f0;
      margin-bottom: 4px;
    }
    a.btn {
      display: inline-block;
      padding: 12px 28px;
      background: #22d3ee;
      color: #05070b !important;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      border-radius: 10px;
    }
  </style>
</head>
<body>
  <div class="box">
    <img src="{{ asset('images/logo.avif') }}" alt="Vyralabs" class="logo">

    <div class="icon-circle">
      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M20 6L9 17L4 12" stroke="#22d3ee" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>

    <h1>You've been unsubscribed</h1>
    <p>
      <span class="email-chip">{{ $email }}</span><br><br>
      will no longer receive The Longevity Letter. If this was a mistake, you can subscribe again anytime from the homepage.
    </p>

    <a href="{{ url('/') }}" class="btn">Back to Vyralabs</a>
  </div>
</body>
</html>