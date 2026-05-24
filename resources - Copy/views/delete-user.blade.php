<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account — RogiSewa</title>
    <link rel="shortcut icon" href="{{ asset('img/logo.png') }}">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Segoe UI', Arial, sans-serif;
            padding: 20px;
            position: relative; overflow: hidden;
        }

        /* Animated background circles */
        body::before, body::after {
            content: '';
            position: absolute; border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        body::before {
            width: 400px; height: 400px;
            background: rgba(10,110,189,.15);
            top: -100px; right: -100px;
        }
        body::after {
            width: 300px; height: 300px;
            background: rgba(0,176,116,.1);
            bottom: -80px; left: -80px;
            animation-delay: 3s;
        }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-20px)} }

        /* Card */
        .card {
            background: rgba(255,255,255,.97);
            border-radius: 24px;
            padding: 44px 40px;
            width: 100%; max-width: 440px;
            box-shadow: 0 24px 64px rgba(0,0,0,.4);
            position: relative; z-index: 1;
            animation: cardPop .6s cubic-bezier(.34,1.56,.64,1) both;
        }
        @keyframes cardPop { from{opacity:0;transform:scale(.85) translateY(30px)} to{opacity:1;transform:scale(1) translateY(0)} }

        /* Warning icon */
        .warn-icon {
            width: 72px; height: 72px; border-radius: 50%;
            background: linear-gradient(135deg,#fff0f0,#ffe4e4);
            border: 3px solid #fecaca;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            animation: iconPulse 2s ease-in-out infinite;
        }
        @keyframes iconPulse {
            0%,100% { box-shadow: 0 0 0 0 rgba(239,68,68,.3); }
            50%      { box-shadow: 0 0 0 12px rgba(239,68,68,0); }
        }
        .warn-icon svg { width:34px; height:34px; }

        /* Title */
        .title {
            text-align: center; margin-bottom: 6px;
            font-size: 22px; font-weight: 800; color: #1a1a2e;
            animation: fadeInUp .5s ease .2s both;
        }
        .subtitle {
            text-align: center; font-size: 13px; color: #888;
            margin-bottom: 28px; line-height: 1.5;
            animation: fadeInUp .5s ease .3s both;
        }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }

        /* Alerts */
        .alert-success {
            background: #e6fff5; border: 1.5px solid #b3f0d8;
            border-radius: 12px; padding: 14px 16px;
            color: #00b074; font-size: 13px; font-weight: 600;
            margin-bottom: 20px; display: flex; align-items: center; gap: 10px;
            animation: fadeInUp .4s ease both;
        }
        .alert-error {
            background: #fff0f0; border: 1.5px solid #fecaca;
            border-radius: 10px; padding: 10px 14px;
            color: #ef4444; font-size: 12.5px; margin-top: 5px;
            animation: shake .4s ease both;
        }
        @keyframes shake {
            0%,100%{transform:translateX(0)}
            20%{transform:translateX(-6px)}
            40%{transform:translateX(6px)}
            60%{transform:translateX(-4px)}
            80%{transform:translateX(4px)}
        }

        /* Form */
        .form-group { margin-bottom: 18px; animation: fadeInUp .5s ease both; }
        .form-group:nth-child(1){ animation-delay:.35s }
        .form-group:nth-child(2){ animation-delay:.45s }

        label {
            display: block; font-size: 11px; font-weight: 700;
            color: #888; text-transform: uppercase; letter-spacing: .5px;
            margin-bottom: 6px;
        }
        .input-wrap { position: relative; }
        .input-wrap .icon {
            position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
            color: #aaa; font-size: 15px;
        }
        input[type=email], input[type=password] {
            width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px;
            padding: 11px 14px 11px 40px; font-size: 14px;
            background: #fafbff; color: #222;
            transition: border-color .25s, box-shadow .25s;
        }
        input:focus {
            border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.1);
            outline: none; background: #fff;
        }
        .pw-toggle {
            position: absolute; right: 13px; top: 50%; transform: translateY(-50%);
            cursor: pointer; color: #aaa; font-size: 14px;
            transition: color .2s; user-select: none;
        }
        .pw-toggle:hover { color: #ef4444; }

        /* Submit btn */
        .btn-delete {
            width: 100%; background: linear-gradient(135deg,#ef4444,#dc2626);
            color: #fff; border: none; border-radius: 12px;
            padding: 13px; font-size: 15px; font-weight: 700;
            cursor: pointer; transition: opacity .25s, transform .2s;
            box-shadow: 0 6px 20px rgba(239,68,68,.35);
            margin-top: 6px;
            animation: fadeInUp .5s ease .55s both;
        }
        .btn-delete:hover { opacity: .9; transform: translateY(-2px); }
        .btn-delete:active { transform: translateY(0); }

        /* Warning note */
        .warn-note {
            background: #fff8e6; border: 1.5px solid #fde68a;
            border-radius: 10px; padding: 12px 14px;
            font-size: 12px; color: #92400e; margin-top: 18px;
            line-height: 1.5;
            animation: fadeInUp .5s ease .6s both;
        }
        .warn-note strong { color: #b45309; }

        /* Back link */
        .back-link {
            display: block; text-align: center; margin-top: 20px;
            font-size: 13px; color: #888; text-decoration: none;
            transition: color .2s;
            animation: fadeInUp .5s ease .65s both;
        }
        .back-link:hover { color: #0a6ebd; }

        /* Logo */
        .logo-wrap {
            text-align: center; margin-bottom: 24px;
            animation: fadeInUp .5s ease .1s both;
        }
        .logo-wrap img { height: 36px; }
        .logo-wrap span {
            display: block; font-size: 11px; color: #aaa;
            margin-top: 4px; letter-spacing: 1px; text-transform: uppercase;
        }
    </style>
</head>
<body>

<div class="card">

    {{-- Logo --}}
    <div class="logo-wrap">
        <img src="{{ asset('img/logo.png') }}" alt="RogiSewa">
        <span>rogisewa.com</span>
    </div>

    {{-- Warning Icon --}}
    <div class="warn-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            <line x1="12" y1="9" x2="12" y2="13"/>
            <line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
    </div>

    <h2 class="title">Delete Account</h2>
    <p class="subtitle">Enter your email and password to permanently delete your RogiSewa account.</p>

    {{-- Success --}}
    @if(session('success'))
    <div class="alert-success">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Form --}}
    @if(!session('success'))
    <form action="{{ route('delete.user.post') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Email Address</label>
            <div class="input-wrap">
                <span class="icon">✉</span>
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="your@email.com" required autocomplete="email">
            </div>
            @error('email')
                <div class="alert-error">⚠ {{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Password</label>
            <div class="input-wrap">
                <span class="icon">🔒</span>
                <input type="password" name="password" id="pwField"
                       placeholder="Enter your password" required>
                <span class="pw-toggle" onclick="togglePw()">👁</span>
            </div>
            @error('password')
                <div class="alert-error">⚠ {{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-delete">
            🗑 Delete My Account
        </button>

        <div class="warn-note">
            ⚠ <strong>Warning:</strong> This action will mark your account as deleted.
            You will no longer be able to log in. This cannot be undone.
        </div>
    </form>
    @endif

    <a href="{{ url('/') }}" class="back-link">← Back to Home</a>

</div>

<script>
function togglePw() {
    var f = document.getElementById('pwField');
    f.type = f.type === 'password' ? 'text' : 'password';
}
</script>

</body>
</html>
