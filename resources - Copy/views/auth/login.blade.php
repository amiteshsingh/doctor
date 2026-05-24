<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/logo.png') }}">
    <title>RogiSewa — Doctor Login</title>
    <link rel="stylesheet" href="{{ asset('admin/assets/css/font-awesome.min.css') }}">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            min-height: 100vh;
            font-family: 'Segoe UI', Arial, sans-serif;
            display: flex;
            background: #f0f4ff;
            overflow: hidden;
        }

        /* ── LEFT PANEL ── */
        .left-panel {
            width: 55%;
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 55%, #0a6ebd 100%);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 40px; position: relative; overflow: hidden;
        }
        .left-panel::before {
            content:''; position:absolute; width:500px; height:500px;
            border-radius:50%; background:rgba(255,255,255,.04);
            top:-150px; right:-150px;
            animation: rotateSlow 20s linear infinite;
        }
        .left-panel::after {
            content:''; position:absolute; width:300px; height:300px;
            border-radius:50%; background:rgba(0,176,116,.08);
            bottom:-80px; left:-80px;
            animation: rotateSlow 15s linear infinite reverse;
        }
        @keyframes rotateSlow { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }

        .brand {
            text-align: center; position: relative; z-index: 1;
            animation: fadeInLeft .7s ease both;
        }
        @keyframes fadeInLeft { from{opacity:0;transform:translateX(-30px)} to{opacity:1;transform:translateX(0)} }

        .brand img { width: 70px; margin-bottom: 16px; filter: drop-shadow(0 4px 12px rgba(0,0,0,.3)); }
        .brand h1 { color:#fff; font-size:32px; font-weight:800; margin-bottom:8px; }
        .brand p  { color:rgba(255,255,255,.7); font-size:14px; line-height:1.6; max-width:320px; }

        .features { margin-top:40px; position:relative; z-index:1; }
        .feat-item {
            display:flex; align-items:center; gap:14px;
            color:rgba(255,255,255,.85); font-size:13.5px;
            margin-bottom:16px;
            animation: fadeInLeft .6s ease both;
        }
        .feat-item:nth-child(1){animation-delay:.2s}
        .feat-item:nth-child(2){animation-delay:.35s}
        .feat-item:nth-child(3){animation-delay:.5s}
        .feat-icon {
            width:38px; height:38px; border-radius:10px; flex-shrink:0;
            display:flex; align-items:center; justify-content:center;
            font-size:16px;
        }

        /* ── RIGHT PANEL ── */
        .right-panel {
            width: 45%;
            display: flex; align-items: center; justify-content: center;
            padding: 40px 32px;
            background: #f0f4ff;
        }

        .login-card {
            width: 100%; max-width: 400px;
            background: #fff; border-radius: 24px;
            padding: 40px 36px;
            box-shadow: 0 20px 60px rgba(10,110,189,.12);
            animation: cardPop .6s cubic-bezier(.34,1.56,.64,1) .1s both;
        }
        @keyframes cardPop { from{opacity:0;transform:scale(.88) translateY(20px)} to{opacity:1;transform:scale(1) translateY(0)} }

        .card-head { text-align:center; margin-bottom:28px; }
        .card-head h2 {
            font-size:24px; font-weight:800; color:#1a1a2e;
            animation: fadeInUp .5s ease .3s both;
        }
        .card-head p {
            font-size:13px; color:#888; margin-top:4px;
            animation: fadeInUp .5s ease .4s both;
        }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }

        /* Alerts */
        .alert-error {
            background:#fff0f0; border:1.5px solid #fecaca;
            border-radius:10px; padding:11px 14px;
            color:#ef4444; font-size:13px; margin-bottom:18px;
            display:flex; align-items:center; gap:8px;
            animation: shake .4s ease both;
        }
        @keyframes shake {
            0%,100%{transform:translateX(0)}
            20%{transform:translateX(-6px)} 40%{transform:translateX(6px)}
            60%{transform:translateX(-4px)} 80%{transform:translateX(4px)}
        }

        /* Form fields */
        .form-group {
            margin-bottom: 18px;
            animation: fadeInUp .5s ease both;
        }
        .form-group:nth-child(1){animation-delay:.35s}
        .form-group:nth-child(2){animation-delay:.45s}
        .form-group:nth-child(3){animation-delay:.55s}

        .f-label {
            font-size:11px; font-weight:700; color:#888;
            text-transform:uppercase; letter-spacing:.5px;
            margin-bottom:6px; display:block;
        }
        .input-wrap { position:relative; }
        .input-icon {
            position:absolute; left:13px; top:50%; transform:translateY(-50%);
            color:#aaa; font-size:15px; pointer-events:none;
        }
        .f-input {
            width:100%; border:1.5px solid #e2e8f0; border-radius:10px;
            padding:11px 14px 11px 40px; font-size:14px;
            background:#fafbff; color:#222;
            transition:border-color .25s, box-shadow .25s;
        }
        .f-input:focus {
            border-color:#0a6ebd; box-shadow:0 0 0 3px rgba(10,110,189,.1);
            outline:none; background:#fff;
        }
        .f-input.is-invalid { border-color:#ef4444; }
        .f-input.is-invalid:focus { box-shadow:0 0 0 3px rgba(239,68,68,.1); }
        .invalid-msg {
            font-size:12px; color:#ef4444; margin-top:5px;
            display:flex; align-items:center; gap:4px;
        }
        .pw-eye {
            position:absolute; right:13px; top:50%; transform:translateY(-50%);
            cursor:pointer; color:#aaa; font-size:14px; transition:color .2s;
        }
        .pw-eye:hover { color:#0a6ebd; }

        /* Remember + Forgot */
        .row-between {
            display:flex; align-items:center; justify-content:space-between;
            margin-bottom:20px; font-size:13px;
            animation: fadeInUp .5s ease .6s both;
        }
        .remember { display:flex; align-items:center; gap:6px; color:#555; cursor:pointer; }
        .remember input { accent-color:#0a6ebd; width:15px; height:15px; }
        .forgot { color:#0a6ebd; text-decoration:none; font-weight:600; }
        .forgot:hover { text-decoration:underline; }

        /* Submit */
        .btn-login {
            width:100%; background:linear-gradient(135deg,#0a6ebd,#00b074);
            color:#fff; border:none; border-radius:12px;
            padding:13px; font-size:15px; font-weight:700;
            cursor:pointer; transition:opacity .25s, transform .2s;
            box-shadow:0 6px 20px rgba(10,110,189,.3);
            animation: fadeInUp .5s ease .65s both;
        }
        .btn-login:hover { opacity:.9; transform:translateY(-2px); }
        .btn-login:active { transform:translateY(0); }

        /* Divider */
        .divider {
            display:flex; align-items:center; gap:12px;
            margin:20px 0; color:#ccc; font-size:12px;
            animation: fadeInUp .5s ease .7s both;
        }
        .divider::before, .divider::after {
            content:''; flex:1; height:1px; background:#e2e8f0;
        }

        /* Register link */
        .reg-link {
            text-align:center; font-size:13px; color:#888;
            animation: fadeInUp .5s ease .75s both;
        }
        .reg-link a { color:#0a6ebd; font-weight:700; text-decoration:none; }
        .reg-link a:hover { text-decoration:underline; }

        /* Other login options */
        .other-logins {
            display:flex; gap:10px; margin-top:14px;
            animation: fadeInUp .5s ease .8s both;
        }
        .other-btn {
            flex:1; padding:9px; border-radius:10px; font-size:12px;
            font-weight:600; text-align:center; text-decoration:none;
            border:1.5px solid #e2e8f0; color:#555;
            transition:all .2s; display:flex; align-items:center;
            justify-content:center; gap:6px;
        }
        .other-btn:hover { border-color:#0a6ebd; color:#0a6ebd; background:#f0f7ff; text-decoration:none; }

        /* Responsive */
        @media(max-width:768px) {
            .left-panel { display:none; }
            .right-panel { width:100%; padding:24px 16px; }
        }
    </style>
</head>
<body>

    {{-- ── LEFT PANEL ── --}}
    <div class="left-panel">
        <div class="brand">
            <img src="{{ asset('img/logo.png') }}" alt="RogiSewa">
            <h1>RogiSewa</h1>
            <p>Doctor & Admin portal — manage your hospitals, prescriptions, staff and more.</p>
        </div>
        <div class="features">
            <div class="feat-item">
                <div class="feat-icon" style="background:rgba(102,126,234,.25);">🩺</div>
                <span>Manage your doctor & hospital profiles</span>
            </div>
            <div class="feat-item">
                <div class="feat-icon" style="background:rgba(0,176,116,.2);">📋</div>
                <span>Create prescriptions & invoices digitally</span>
            </div>
            <div class="feat-item">
                <div class="feat-icon" style="background:rgba(251,191,36,.2);">👥</div>
                <span>Track staff attendance & manage medicines</span>
            </div>
        </div>
    </div>

    {{-- ── RIGHT PANEL ── --}}
    <div class="right-panel">
        <div class="login-card">

            <div class="card-head">
                <h2>Doctor Login 🩺</h2>
                <p>Sign in to your RogiSewa doctor panel</p>
            </div>

            {{-- Error --}}
            @if(session('error'))
            <div class="alert-error">
                <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label class="f-label">Email Address</label>
                    <div class="input-wrap">
                        <i class="fa fa-envelope-o input-icon"></i>
                        <input type="email" name="email" id="email"
                               class="f-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               value="{{ old('email') }}"
                               placeholder="your@email.com" required autocomplete="email" autofocus>
                    </div>
                    @error('email')
                        <div class="invalid-msg"><i class="fa fa-times-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label class="f-label">Password</label>
                    <div class="input-wrap">
                        <i class="fa fa-lock input-icon"></i>
                        <input type="password" name="password" id="password"
                               class="f-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                               placeholder="Enter your password" required autocomplete="current-password">
                        <span class="pw-eye" onclick="togglePw()">
                            <i class="fa fa-eye" id="pwIcon"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="invalid-msg"><i class="fa fa-times-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="row-between">
                    <label class="remember">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Remember me
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-login">
                    <i class="fa fa-sign-in mr-2"></i> Sign In
                </button>
            </form>

            <div class="divider">or</div>

            <div class="reg-link">
                New doctor? <a href="{{ url('register') }}">Register Here</a>
            </div>

            <div class="other-logins">
                <a href="{{ url('user/login') }}" class="other-btn">
                    👤 Patient Login
                </a>
                <a href="{{ url('/') }}" class="other-btn">
                    🏠 Back to Home
                </a>
            </div>

        </div>
    </div>

<script>
function togglePw() {
    var f = document.getElementById('password');
    var i = document.getElementById('pwIcon');
    if (f.type === 'password') {
        f.type = 'text';
        i.className = 'fa fa-eye-slash';
    } else {
        f.type = 'password';
        i.className = 'fa fa-eye';
    }
}
</script>

</body>
</html>
