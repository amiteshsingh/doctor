@extends('page.layouts.app')
@section('title', 'RogiSewa — Patient Login')

@section('content')

<style>
.ul-page {
    min-height: 80vh;
    display: flex; align-items: center; justify-content: center;
    padding: 40px 16px;
    background: linear-gradient(135deg, #f0f4ff 0%, #e8f5f0 100%);
    position: relative; overflow: hidden;
}
.ul-page::before {
    content:''; position:absolute;
    width:450px; height:450px; border-radius:50%;
    background:rgba(102,126,234,.07);
    top:-120px; right:-120px;
    animation: floatBg 8s ease-in-out infinite;
}
.ul-page::after {
    content:''; position:absolute;
    width:300px; height:300px; border-radius:50%;
    background:rgba(0,176,116,.06);
    bottom:-80px; left:-80px;
    animation: floatBg 10s ease-in-out infinite reverse;
}
@keyframes floatBg { 0%,100%{transform:scale(1)} 50%{transform:scale(1.12)} }

/* Card */
.ul-card {
    position: relative; z-index: 1;
    background: #fff; border-radius: 24px;
    padding: 40px 36px;
    width: 100%; max-width: 420px;
    box-shadow: 0 20px 60px rgba(102,126,234,.15);
    animation: cardPop .6s cubic-bezier(.34,1.56,.64,1) .1s both;
}
@keyframes cardPop { from{opacity:0;transform:scale(.88) translateY(24px)} to{opacity:1;transform:scale(1) translateY(0)} }

/* Top accent bar */
.ul-card::before {
    content:''; position:absolute; top:0; left:0; right:0; height:5px;
    background: linear-gradient(90deg,#667eea,#764ba2);
    border-radius:24px 24px 0 0;
}

/* Logo */
.ul-logo {
    text-align:center; margin-bottom:20px;
    animation: fadeInUp .5s ease .2s both;
}
.ul-logo img { width:52px; }

/* Head */
.ul-head { text-align:center; margin-bottom:26px; }
.ul-head h2 {
    font-size:22px; font-weight:800; color:#1a1a2e;
    animation: fadeInUp .5s ease .3s both;
}
.ul-head p {
    font-size:13px; color:#888; margin-top:4px;
    animation: fadeInUp .5s ease .4s both;
}
@keyframes fadeInUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }

/* Alerts */
.ul-alert-error {
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

/* Form */
.ul-group {
    margin-bottom:16px;
    animation: fadeInUp .5s ease both;
}
.ul-group:nth-child(1){animation-delay:.35s}
.ul-group:nth-child(2){animation-delay:.45s}

.ul-label {
    font-size:11px; font-weight:700; color:#888;
    text-transform:uppercase; letter-spacing:.5px;
    margin-bottom:5px; display:block;
}
.ul-input-wrap { position:relative; }
.ul-icon {
    position:absolute; left:13px; top:50%; transform:translateY(-50%);
    color:#aaa; font-size:14px; pointer-events:none;
}
.ul-input {
    width:100%; border:1.5px solid #e2e8f0; border-radius:10px;
    padding:10px 14px 10px 38px; font-size:13.5px;
    background:#fafbff; color:#222;
    transition:border-color .25s, box-shadow .25s;
}
.ul-input:focus {
    border-color:#667eea; box-shadow:0 0 0 3px rgba(102,126,234,.12);
    outline:none; background:#fff;
}
.ul-input.is-invalid { border-color:#ef4444; }
.ul-input.is-invalid:focus { box-shadow:0 0 0 3px rgba(239,68,68,.1); }
.ul-invalid { font-size:11.5px; color:#ef4444; margin-top:4px; display:flex; align-items:center; gap:4px; }
.ul-pw-eye {
    position:absolute; right:13px; top:50%; transform:translateY(-50%);
    cursor:pointer; color:#aaa; font-size:13px; transition:color .2s;
}
.ul-pw-eye:hover { color:#667eea; }

/* Remember row */
.ul-row {
    display:flex; align-items:center; justify-content:space-between;
    margin-bottom:20px; font-size:13px;
    animation: fadeInUp .5s ease .55s both;
}
.ul-remember { display:flex; align-items:center; gap:6px; color:#555; cursor:pointer; }
.ul-remember input { accent-color:#667eea; width:15px; height:15px; }
.ul-forgot { color:#667eea; text-decoration:none; font-weight:600; font-size:12.5px; }
.ul-forgot:hover { text-decoration:underline; }

/* Submit */
.ul-btn {
    width:100%; background:linear-gradient(135deg,#667eea,#764ba2);
    color:#fff; border:none; border-radius:12px;
    padding:12px; font-size:14px; font-weight:700;
    cursor:pointer; transition:opacity .25s, transform .2s;
    box-shadow:0 6px 20px rgba(102,126,234,.35);
    animation: fadeInUp .5s ease .6s both;
}
.ul-btn:hover { opacity:.9; transform:translateY(-2px); }
.ul-btn:active { transform:translateY(0); }

/* Divider */
.ul-divider {
    display:flex; align-items:center; gap:12px;
    margin:18px 0; color:#ccc; font-size:11px;
    animation: fadeInUp .5s ease .65s both;
}
.ul-divider::before, .ul-divider::after { content:''; flex:1; height:1px; background:#e2e8f0; }

/* Links */
.ul-links { text-align:center; font-size:13px; color:#888; animation: fadeInUp .5s ease .7s both; }
.ul-links a { color:#667eea; font-weight:700; text-decoration:none; }
.ul-links a:hover { text-decoration:underline; }

.ul-other {
    display:flex; gap:10px; margin-top:14px;
    animation: fadeInUp .5s ease .75s both;
}
.ul-other-btn {
    flex:1; padding:9px; border-radius:10px; font-size:12px;
    font-weight:600; text-align:center; text-decoration:none;
    border:1.5px solid #e2e8f0; color:#555;
    transition:all .2s; display:flex; align-items:center; justify-content:center; gap:5px;
}
.ul-other-btn:hover { border-color:#667eea; color:#667eea; background:#f5f3ff; text-decoration:none; }
</style>

<div class="ul-page">
<div class="ul-card">

    {{-- Logo --}}
    <div class="ul-logo">
        <img src="{{ asset('img/logo.png') }}" alt="RogiSewa">
    </div>

    <div class="ul-head">
        <h2>Patient Login 👤</h2>
        <p>Sign in to book appointments & manage your health</p>
    </div>

    {{-- Error --}}
    @if(session('error'))
    <div class="ul-alert-error">
        <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    <form method="POST" action="{{ route('user.login') }}{{ request('redirect') ? '?redirect='.urlencode(request('redirect')).(request('book') ? '&book=1' : '') : '' }}">
        @csrf

        {{-- Email --}}
        <div class="ul-group">
            <label class="ul-label">Email Address</label>
            <div class="ul-input-wrap">
                <i class="fa fa-envelope-o ul-icon"></i>
                <input type="email" name="email"
                       class="ul-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       value="{{ old('email') }}"
                       placeholder="your@email.com" required autocomplete="email" autofocus>
            </div>
            @error('email')
                <div class="ul-invalid"><i class="fa fa-times-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="ul-group">
            <label class="ul-label">Password</label>
            <div class="ul-input-wrap">
                <i class="fa fa-lock ul-icon"></i>
                <input type="password" name="password" id="ulPw"
                       class="ul-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                       placeholder="Enter your password" required>
                <span class="ul-pw-eye" onclick="toggleUlPw()">
                    <i class="fa fa-eye" id="ulPwIcon"></i>
                </span>
            </div>
            @error('password')
                <div class="ul-invalid"><i class="fa fa-times-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        {{-- Remember + Forgot --}}
        <div class="ul-row">
            <label class="ul-remember">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                Remember me
            </label>
            <a href="#" class="ul-forgot">Forgot password?</a>
        </div>

        <button type="submit" class="ul-btn">
            <i class="fa fa-sign-in mr-2"></i> Sign In
        </button>
    </form>

    <div class="ul-divider">or</div>

    <div class="ul-links">
        Don't have an account? <a href="{{ route('user.register') }}">Register Now</a>
    </div>

    <div class="ul-other">
        <a href="{{ url('/') }}" class="ul-other-btn">
            🏠 Back to Home
        </a>
    </div>

</div>
</div>

<script>
function toggleUlPw() {
    var f = document.getElementById('ulPw');
    var i = document.getElementById('ulPwIcon');
    f.type = f.type === 'password' ? 'text' : 'password';
    i.className = f.type === 'text' ? 'fa fa-eye-slash' : 'fa fa-eye';
}
</script>

@endsection
