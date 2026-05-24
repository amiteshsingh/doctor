<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/logo.png') }}">
    <title>RogiSewa — Doctor Registration</title>
    <link rel="stylesheet" href="{{ asset('admin/assets/css/font-awesome.min.css') }}">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            min-height: 100vh;
            font-family: 'Segoe UI', Arial, sans-serif;
            display: flex;
            background: #f0f4ff;
            overflow-x: hidden;
        }

        /* ── LEFT PANEL ── */
        .left-panel {
            width: 50%;
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 55%, #0a6ebd 100%);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 40px; position: relative; overflow: hidden;
        }
        .left-panel::before {
            content:''; position:absolute; width:450px; height:450px;
            border-radius:50%; background:rgba(255,255,255,.04);
            top:-120px; right:-120px;
            animation: rotateSlow 18s linear infinite;
        }
        .left-panel::after {
            content:''; position:absolute; width:280px; height:280px;
            border-radius:50%; background:rgba(0,176,116,.08);
            bottom:-70px; left:-70px;
            animation: rotateSlow 14s linear infinite reverse;
        }
        @keyframes rotateSlow { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }

        .brand {
            text-align: center; position: relative; z-index: 1;
            animation: fadeInLeft .7s ease both;
        }
        @keyframes fadeInLeft { from{opacity:0;transform:translateX(-30px)} to{opacity:1;transform:translateX(0)} }

        .brand img { width: 70px; margin-bottom: 16px; filter: drop-shadow(0 4px 12px rgba(0,0,0,.3)); }
        .brand h1 { color:#fff; font-size:30px; font-weight:800; margin-bottom:8px; }
        .brand p  { color:rgba(255,255,255,.7); font-size:13.5px; line-height:1.6; max-width:340px; }

        .features { margin-top:36px; position:relative; z-index:1; }
        .feat-item {
            display:flex; align-items:center; gap:14px;
            color:rgba(255,255,255,.85); font-size:13px;
            margin-bottom:14px;
            animation: fadeInLeft .6s ease both;
        }
        .feat-item:nth-child(1){animation-delay:.2s}
        .feat-item:nth-child(2){animation-delay:.35s}
        .feat-item:nth-child(3){animation-delay:.5s}
        .feat-item:nth-child(4){animation-delay:.65s}
        .feat-icon {
            width:36px; height:36px; border-radius:10px; flex-shrink:0;
            display:flex; align-items:center; justify-content:center;
            font-size:15px;
        }

        /* ── RIGHT PANEL ── */
        .right-panel {
            width: 50%;
            display: flex; align-items: center; justify-content: center;
            padding: 40px 28px;
            background: #f0f4ff;
            overflow-y: auto;
        }

        .reg-card {
            width: 100%; max-width: 480px;
            background: #fff; border-radius: 24px;
            padding: 36px 32px;
            box-shadow: 0 20px 60px rgba(10,110,189,.12);
            animation: cardPop .6s cubic-bezier(.34,1.56,.64,1) .1s both;
        }
        @keyframes cardPop { from{opacity:0;transform:scale(.88) translateY(20px)} to{opacity:1;transform:scale(1) translateY(0)} }

        .card-head { text-align:center; margin-bottom:24px; }
        .card-head h2 {
            font-size:22px; font-weight:800; color:#1a1a2e;
            animation: fadeInUp .5s ease .3s both;
        }
        .card-head p {
            font-size:12.5px; color:#888; margin-top:4px;
            animation: fadeInUp .5s ease .4s both;
        }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }

        /* Alerts */
        .alert-error {
            background:#fff0f0; border:1.5px solid #fecaca;
            border-radius:10px; padding:11px 14px;
            color:#ef4444; font-size:12.5px; margin-bottom:16px;
            animation: shake .4s ease both;
        }
        @keyframes shake {
            0%,100%{transform:translateX(0)}
            20%{transform:translateX(-6px)} 40%{transform:translateX(6px)}
            60%{transform:translateX(-4px)} 80%{transform:translateX(4px)}
        }

        /* Form fields */
        .form-group {
            margin-bottom: 16px;
            animation: fadeInUp .5s ease both;
        }
        .form-group:nth-child(1){animation-delay:.35s}
        .form-group:nth-child(2){animation-delay:.42s}
        .form-group:nth-child(3){animation-delay:.49s}
        .form-group:nth-child(4){animation-delay:.56s}

        .f-label {
            font-size:11px; font-weight:700; color:#888;
            text-transform:uppercase; letter-spacing:.5px;
            margin-bottom:5px; display:block;
        }
        .input-wrap { position:relative; }
        .input-icon {
            position:absolute; left:13px; top:50%; transform:translateY(-50%);
            color:#aaa; font-size:14px; pointer-events:none;
        }
        .f-input {
            width:100%; border:1.5px solid #e2e8f0; border-radius:10px;
            padding:10px 14px 10px 38px; font-size:13.5px;
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
            font-size:11.5px; color:#ef4444; margin-top:4px;
            display:flex; align-items:center; gap:4px;
        }
        .pw-eye {
            position:absolute; right:13px; top:50%; transform:translateY(-50%);
            cursor:pointer; color:#aaa; font-size:13px; transition:color .2s;
        }
        .pw-eye:hover { color:#0a6ebd; }

        /* Submit */
        .btn-register {
            width:100%; background:linear-gradient(135deg,#0a6ebd,#00b074);
            color:#fff; border:none; border-radius:12px;
            padding:12px; font-size:14px; font-weight:700;
            cursor:pointer; transition:opacity .25s, transform .2s;
            box-shadow:0 6px 20px rgba(10,110,189,.3);
            margin-top:6px;
            animation: fadeInUp .5s ease .63s both;
        }
        .btn-register:hover { opacity:.9; transform:translateY(-2px); }

        /* Divider */
        .divider {
            display:flex; align-items:center; gap:12px;
            margin:18px 0; color:#ccc; font-size:11px;
            animation: fadeInUp .5s ease .7s both;
        }
        .divider::before, .divider::after {
            content:''; flex:1; height:1px; background:#e2e8f0;
        }

        /* Login link */
        .login-link {
            text-align:center; font-size:13px; color:#888;
            animation: fadeInUp .5s ease .75s both;
        }
        .login-link a { color:#0a6ebd; font-weight:700; text-decoration:none; }
        .login-link a:hover { text-decoration:underline; }

        /* Info note */
        .info-note {
            background:#f0f7ff; border:1.5px solid #bfdbfe;
            border-radius:10px; padding:10px 14px;
            font-size:12px; color:#1e40af; margin-top:16px;
            line-height:1.5;
            animation: fadeInUp .5s ease .8s both;
        }
        .info-note strong { color:#0a6ebd; }

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
            <h1>Join RogiSewa</h1>
            <p>Register as a doctor and connect with thousands of patients across India.</p>
        </div>
        <div class="features">
            <div class="feat-item">
                <div class="feat-icon" style="background:rgba(102,126,234,.25);">🩺</div>
                <span>Create your professional doctor profile</span>
            </div>
            <div class="feat-item">
                <div class="feat-icon" style="background:rgba(0,176,116,.2);">🏥</div>
                <span>List your hospitals & clinics</span>
            </div>
            <div class="feat-item">
                <div class="feat-icon" style="background:rgba(251,191,36,.2);">📋</div>
                <span>Manage prescriptions & invoices digitally</span>
            </div>
            <div class="feat-item">
                <div class="feat-icon" style="background:rgba(239,68,68,.2);">👥</div>
                <span>Track staff attendance & medicines</span>
            </div>
        </div>
    </div>

    {{-- ── RIGHT PANEL ── --}}
    <div class="right-panel">
        <div class="reg-card">

            <div class="card-head">
                <h2>Doctor Registration 🩺</h2>
                <p>Create your account to get started</p>
            </div>

            {{-- Validation errors --}}
            @if($errors->any())
            <div class="alert-error">
                <i class="fa fa-exclamation-circle"></i>
                <ul style="margin:0;padding-left:18px;">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Name --}}
                <div class="form-group">
                    <label class="f-label">Full Name</label>
                    <div class="input-wrap">
                        <i class="fa fa-user-md input-icon"></i>
                        <input type="text" name="name" id="name"
                               class="f-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ old('name') }}"
                               placeholder="Dr. Your Name" required autofocus>
                    </div>
                    @error('name')
                        <div class="invalid-msg"><i class="fa fa-times-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label class="f-label">Email Address</label>
                    <div class="input-wrap">
                        <i class="fa fa-envelope-o input-icon"></i>
                        <input type="email" name="email" id="email"
                               class="f-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               value="{{ old('email') }}"
                               placeholder="your@email.com" required>
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
                               placeholder="Min 8 characters" required>
                        <span class="pw-eye" onclick="togglePw('password','pwIcon1')">
                            <i class="fa fa-eye" id="pwIcon1"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="invalid-msg"><i class="fa fa-times-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="form-group">
                    <label class="f-label">Confirm Password</label>
                    <div class="input-wrap">
                        <i class="fa fa-lock input-icon"></i>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="f-input {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                               placeholder="Repeat password" required>
                        <span class="pw-eye" onclick="togglePw('password_confirmation','pwIcon2')">
                            <i class="fa fa-eye" id="pwIcon2"></i>
                        </span>
                    </div>
                    @error('password_confirmation')
                        <div class="invalid-msg"><i class="fa fa-times-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-register">
                    <i class="fa fa-user-plus mr-2"></i> Register as Doctor
                </button>
            </form>

            <div class="divider">or</div>

            <div class="login-link">
                Already have an account? <a href="{{ url('login') }}">Login Now</a>
            </div>

            <div class="info-note">
                <strong>📌 Note:</strong> After registration, you can add your hospitals, manage prescriptions,
                track staff attendance, and more. Get your profile approved by contacting us at
                <strong>rogisewa25@gmail.com</strong>
            </div>

        </div>
    </div>

<script>
function togglePw(fieldId, iconId) {
    var f = document.getElementById(fieldId);
    var i = document.getElementById(iconId);
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
