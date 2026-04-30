@extends('page.layouts.app')
@section('title', 'RogiSewa — Patient Registration')

@section('content')

<style>
.ur-page {
    min-height: 85vh;
    display: flex; align-items: center; justify-content: center;
    padding: 40px 16px;
    background: linear-gradient(135deg, #f5f3ff 0%, #e8f5f0 100%);
    position: relative; overflow: hidden;
}
.ur-page::before {
    content:''; position:absolute;
    width:500px; height:500px; border-radius:50%;
    background:rgba(102,126,234,.07);
    top:-150px; right:-150px;
    animation: floatBg 9s ease-in-out infinite;
}
.ur-page::after {
    content:''; position:absolute;
    width:320px; height:320px; border-radius:50%;
    background:rgba(0,176,116,.06);
    bottom:-90px; left:-90px;
    animation: floatBg 11s ease-in-out infinite reverse;
}
@keyframes floatBg { 0%,100%{transform:scale(1)} 50%{transform:scale(1.1)} }

/* ── WRAPPER ── */
.ur-wrap {
    position:relative; z-index:1;
    display:flex; gap:28px; align-items:flex-start;
    width:100%; max-width:900px; flex-wrap:wrap; justify-content:center;
}

/* ── FEATURES PANEL ── */
.ur-features {
    width: 300px; flex-shrink:0;
    animation: fadeInLeft .7s ease .1s both;
}
@keyframes fadeInLeft { from{opacity:0;transform:translateX(-24px)} to{opacity:1;transform:translateX(0)} }

.ur-features .brand { margin-bottom:24px; }
.ur-features .brand img { width:52px; margin-bottom:10px; }
.ur-features .brand h2 { font-size:22px; font-weight:800; color:#1a1a2e; margin-bottom:4px; }
.ur-features .brand p  { font-size:13px; color:#666; line-height:1.5; }

.feat-card {
    background:#fff; border-radius:14px;
    padding:14px 16px; margin-bottom:12px;
    box-shadow:0 4px 16px rgba(102,126,234,.1);
    display:flex; align-items:flex-start; gap:12px;
    animation: fadeInLeft .5s ease both;
    transition:transform .25s, box-shadow .25s;
}
.feat-card:hover { transform:translateX(4px); box-shadow:0 6px 20px rgba(102,126,234,.15); }
.feat-card:nth-child(1){animation-delay:.2s}
.feat-card:nth-child(2){animation-delay:.3s}
.feat-card:nth-child(3){animation-delay:.4s}
.feat-card:nth-child(4){animation-delay:.5s}
.feat-card:nth-child(5){animation-delay:.6s}

.feat-ic {
    width:38px; height:38px; border-radius:10px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    font-size:17px;
}
.feat-card .feat-title { font-size:13px; font-weight:700; color:#1a1a2e; margin-bottom:2px; }
.feat-card .feat-desc  { font-size:11.5px; color:#888; line-height:1.4; }

/* ── FORM CARD ── */
.ur-card {
    background:#fff; border-radius:24px;
    padding:36px 32px;
    width:100%; max-width:420px;
    box-shadow:0 20px 60px rgba(102,126,234,.15);
    animation: cardPop .6s cubic-bezier(.34,1.56,.64,1) .15s both;
    position:relative;
}
@keyframes cardPop { from{opacity:0;transform:scale(.88) translateY(24px)} to{opacity:1;transform:scale(1) translateY(0)} }
.ur-card::before {
    content:''; position:absolute; top:0; left:0; right:0; height:5px;
    background:linear-gradient(90deg,#667eea,#764ba2);
    border-radius:24px 24px 0 0;
}

.ur-head { text-align:center; margin-bottom:22px; }
.ur-head h3 { font-size:21px; font-weight:800; color:#1a1a2e; animation:fadeInUp .5s ease .3s both; }
.ur-head p  { font-size:12.5px; color:#888; margin-top:3px; animation:fadeInUp .5s ease .4s both; }
@keyframes fadeInUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }

/* Alerts */
.ur-error {
    background:#fff0f0; border:1.5px solid #fecaca;
    border-radius:10px; padding:10px 14px;
    color:#ef4444; font-size:12.5px; margin-bottom:16px;
    display:flex; align-items:center; gap:8px;
    animation:shake .4s ease both;
}
@keyframes shake {
    0%,100%{transform:translateX(0)}
    20%{transform:translateX(-5px)} 40%{transform:translateX(5px)}
    60%{transform:translateX(-3px)} 80%{transform:translateX(3px)}
}

/* Fields */
.ur-group { margin-bottom:14px; animation:fadeInUp .5s ease both; }
.ur-group:nth-child(1){animation-delay:.35s}
.ur-group:nth-child(2){animation-delay:.42s}
.ur-group:nth-child(3){animation-delay:.49s}
.ur-group:nth-child(4){animation-delay:.56s}
.ur-group:nth-child(5){animation-delay:.63s}

.ur-label {
    font-size:11px; font-weight:700; color:#888;
    text-transform:uppercase; letter-spacing:.5px;
    margin-bottom:5px; display:block;
}
.ur-wrap-i { position:relative; }
.ur-icon {
    position:absolute; left:12px; top:50%; transform:translateY(-50%);
    color:#bbb; font-size:13px; pointer-events:none;
}
.ur-input {
    width:100%; border:1.5px solid #e2e8f0; border-radius:10px;
    padding:10px 13px 10px 36px; font-size:13.5px;
    background:#fafbff; color:#222;
    transition:border-color .25s, box-shadow .25s;
}
.ur-input:focus {
    border-color:#667eea; box-shadow:0 0 0 3px rgba(102,126,234,.12);
    outline:none; background:#fff;
}
.ur-input.is-invalid { border-color:#ef4444; }
.ur-invalid { font-size:11px; color:#ef4444; margin-top:3px; display:flex; align-items:center; gap:3px; }
.ur-pw-eye {
    position:absolute; right:12px; top:50%; transform:translateY(-50%);
    cursor:pointer; color:#bbb; font-size:13px; transition:color .2s;
}
.ur-pw-eye:hover { color:#667eea; }

/* Submit */
.ur-btn {
    width:100%; background:linear-gradient(135deg,#667eea,#764ba2);
    color:#fff; border:none; border-radius:12px;
    padding:12px; font-size:14px; font-weight:700;
    cursor:pointer; transition:opacity .25s, transform .2s;
    box-shadow:0 6px 20px rgba(102,126,234,.35);
    margin-top:4px;
    animation:fadeInUp .5s ease .7s both;
}
.ur-btn:hover { opacity:.9; transform:translateY(-2px); }

/* Divider */
.ur-divider {
    display:flex; align-items:center; gap:10px;
    margin:16px 0; color:#ccc; font-size:11px;
    animation:fadeInUp .5s ease .75s both;
}
.ur-divider::before,.ur-divider::after { content:''; flex:1; height:1px; background:#e2e8f0; }

/* Login link */
.ur-login { text-align:center; font-size:13px; color:#888; animation:fadeInUp .5s ease .8s both; }
.ur-login a { color:#667eea; font-weight:700; text-decoration:none; }
.ur-login a:hover { text-decoration:underline; }

/* Terms note */
.ur-terms {
    font-size:11px; color:#aaa; text-align:center; margin-top:12px;
    animation:fadeInUp .5s ease .85s both;
}
.ur-terms a { color:#667eea; }

@media(max-width:768px) {
    .ur-features { display:none; }
    .ur-card { max-width:100%; }
}
</style>

<div class="ur-page">
<div class="ur-wrap">

    {{-- ── FEATURES PANEL ── --}}
    <div class="ur-features">
        <div class="brand">
            <img src="{{ asset('img/logo.png') }}" alt="RogiSewa">
            <h2>Join RogiSewa</h2>
            <p>India's trusted platform to find doctors & book appointments easily.</p>
        </div>

        <div class="feat-card">
            <div class="feat-ic" style="background:#f0f0ff;">🔍</div>
            <div>
                <div class="feat-title">Find Doctors Near You</div>
                <div class="feat-desc">Search verified doctors by specialization, city or name</div>
            </div>
        </div>

        <div class="feat-card">
            <div class="feat-ic" style="background:#e6fff5;">📅</div>
            <div>
                <div class="feat-title">Book Appointments</div>
                <div class="feat-desc">Book slots instantly with your preferred doctor</div>
            </div>
        </div>

        <div class="feat-card">
            <div class="feat-ic" style="background:#fff8e6;">⭐</div>
            <div>
                <div class="feat-title">Save Favourites</div>
                <div class="feat-desc">Save your favourite doctors & hospitals for quick access</div>
            </div>
        </div>

        <div class="feat-card">
            <div class="feat-ic" style="background:#fff0f0;">📋</div>
            <div>
                <div class="feat-title">View Prescriptions</div>
                <div class="feat-desc">Access your digital prescriptions & invoices anytime</div>
            </div>
        </div>

        <div class="feat-card">
            <div class="feat-ic" style="background:#f0f7ff;">🏥</div>
            <div>
                <div class="feat-title">Find Hospitals</div>
                <div class="feat-desc">Discover top hospitals & clinics near your location</div>
            </div>
        </div>
    </div>

    {{-- ── FORM CARD ── --}}
    <div class="ur-card">

        <div class="ur-head">
            <h3>Create Account 👤</h3>
            <p>Register as a patient on RogiSewa</p>
        </div>

        @if(session('error'))
        <div class="ur-error">
            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="ur-error">
            <i class="fa fa-exclamation-circle"></i>
            <div>
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('user.register') }}">
            @csrf

            {{-- Name --}}
            <div class="ur-group">
                <label class="ur-label">Full Name</label>
                <div class="ur-wrap-i">
                    <i class="fa fa-user ur-icon"></i>
                    <input type="text" name="name"
                           class="ur-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name') }}" placeholder="Your full name" required autofocus>
                </div>
                @error('name')<div class="ur-invalid"><i class="fa fa-times-circle"></i> {{ $message }}</div>@enderror
            </div>

            {{-- Email --}}
            <div class="ur-group">
                <label class="ur-label">Email Address</label>
                <div class="ur-wrap-i">
                    <i class="fa fa-envelope-o ur-icon"></i>
                    <input type="email" name="email"
                           class="ur-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email') }}" placeholder="your@email.com" required>
                </div>
                @error('email')<div class="ur-invalid"><i class="fa fa-times-circle"></i> {{ $message }}</div>@enderror
            </div>

            {{-- Phone --}}
            <div class="ur-group">
                <label class="ur-label">Mobile Number</label>
                <div class="ur-wrap-i">
                    <i class="fa fa-phone ur-icon"></i>
                    <input type="text" name="phone_no"
                           class="ur-input {{ $errors->has('phone_no') ? 'is-invalid' : '' }}"
                           value="{{ old('phone_no') }}" placeholder="10-digit mobile number" required>
                </div>
                @error('phone_no')<div class="ur-invalid"><i class="fa fa-times-circle"></i> {{ $message }}</div>@enderror
            </div>

            {{-- Password --}}
            <div class="ur-group">
                <label class="ur-label">Password</label>
                <div class="ur-wrap-i">
                    <i class="fa fa-lock ur-icon"></i>
                    <input type="password" name="password" id="urPw1"
                           class="ur-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                           placeholder="Min 6 characters" required>
                    <span class="ur-pw-eye" onclick="toggleUrPw('urPw1','urEye1')">
                        <i class="fa fa-eye" id="urEye1"></i>
                    </span>
                </div>
                @error('password')<div class="ur-invalid"><i class="fa fa-times-circle"></i> {{ $message }}</div>@enderror
            </div>

            {{-- Confirm Password --}}
            <div class="ur-group">
                <label class="ur-label">Confirm Password</label>
                <div class="ur-wrap-i">
                    <i class="fa fa-lock ur-icon"></i>
                    <input type="password" name="password_confirmation" id="urPw2"
                           class="ur-input" placeholder="Repeat password" required>
                    <span class="ur-pw-eye" onclick="toggleUrPw('urPw2','urEye2')">
                        <i class="fa fa-eye" id="urEye2"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="ur-btn">
                <i class="fa fa-user-plus mr-2"></i> Create Account
            </button>
        </form>

        <div class="ur-divider">or</div>

        <div class="ur-login">
            Already have an account? <a href="{{ route('user.login') }}">Login Now</a>
        </div>

        <div class="ur-terms">
            By registering, you agree to our
            <a href="{{ url('terms') }}">Terms</a> &
            <a href="{{ url('privacy-policy') }}">Privacy Policy</a>
        </div>
    </div>

</div>
</div>

<script>
function toggleUrPw(fieldId, iconId) {
    var f = document.getElementById(fieldId);
    var i = document.getElementById(iconId);
    f.type = f.type === 'password' ? 'text' : 'password';
    i.className = f.type === 'text' ? 'fa fa-eye-slash' : 'fa fa-eye';
}
</script>

@endsection
