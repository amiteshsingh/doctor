@extends('page.layouts.app')
@section('title', 'RogiSewa - Choose Login')

@section('content')

<style>
/* ── PAGE ── */
.cl-page {
    min-height: 80vh;
    display: flex; align-items: center; justify-content: center;
    padding: 40px 16px;
    background: linear-gradient(135deg, #f0f4ff 0%, #e8f5f0 100%);
    position: relative; overflow: hidden;
}
.cl-page::before {
    content:''; position:absolute;
    width:500px; height:500px; border-radius:50%;
    background:rgba(10,110,189,.06);
    top:-150px; right:-150px;
    animation: floatBg 8s ease-in-out infinite;
}
.cl-page::after {
    content:''; position:absolute;
    width:350px; height:350px; border-radius:50%;
    background:rgba(0,176,116,.06);
    bottom:-100px; left:-100px;
    animation: floatBg 10s ease-in-out infinite reverse;
}
@keyframes floatBg { 0%,100%{transform:scale(1)} 50%{transform:scale(1.1)} }

/* ── WRAPPER ── */
.cl-wrap {
    position: relative; z-index: 1;
    text-align: center; max-width: 600px; width: 100%;
}

/* ── HEADER ── */
.cl-logo {
    animation: popIn .6s cubic-bezier(.34,1.56,.64,1) both;
}
@keyframes popIn { from{opacity:0;transform:scale(.5)} to{opacity:1;transform:scale(1)} }
.cl-logo img { width: 64px; margin-bottom: 12px; }
.cl-title {
    font-size: 28px; font-weight: 800; color: #1a1a2e;
    margin-bottom: 6px;
    animation: fadeInUp .5s ease .2s both;
}
.cl-sub {
    font-size: 14px; color: #888; margin-bottom: 36px;
    animation: fadeInUp .5s ease .3s both;
}
@keyframes fadeInUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }

/* ── CARDS ── */
.cl-cards { display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; }

.cl-card {
    width: 220px;
    background: #fff;
    border-radius: 20px;
    padding: 36px 24px 28px;
    text-decoration: none;
    box-shadow: 0 8px 32px rgba(0,0,0,.08);
    border: 2px solid transparent;
    transition: transform .3s, box-shadow .3s, border-color .3s;
    position: relative; overflow: hidden;
    animation: cardPop .6s cubic-bezier(.34,1.56,.64,1) both;
}
.cl-card:nth-child(1){ animation-delay:.3s }
.cl-card:nth-child(2){ animation-delay:.45s }
.cl-card:nth-child(3){ animation-delay:.6s }
@keyframes cardPop { from{opacity:0;transform:translateY(30px) scale(.9)} to{opacity:1;transform:translateY(0) scale(1)} }

.cl-card::before {
    content:''; position:absolute; top:0; left:0; right:0; height:4px;
    border-radius:20px 20px 0 0;
    transition: height .3s;
}
.cl-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(0,0,0,.14);
}
.cl-card:hover::before { height: 6px; }

/* User card */
.cl-card.user-card::before  { background: linear-gradient(90deg,#667eea,#764ba2); }
.cl-card.user-card:hover    { border-color: #667eea; }
.cl-card.user-card .cl-icon { background: linear-gradient(135deg,#667eea,#764ba2); }
.cl-card.user-card .cl-btn  { background: linear-gradient(135deg,#667eea,#764ba2); }

/* Doctor card */
.cl-card.doctor-card::before  { background: linear-gradient(90deg,#0a6ebd,#00b074); }
.cl-card.doctor-card:hover    { border-color: #0a6ebd; }
.cl-card.doctor-card .cl-icon { background: linear-gradient(135deg,#0a6ebd,#00b074); }
.cl-card.doctor-card .cl-btn  { background: linear-gradient(135deg,#0a6ebd,#00b074); }

/* Admin card */
.cl-card.admin-card::before  { background: linear-gradient(90deg,#f59e0b,#ef4444); }
.cl-card.admin-card:hover    { border-color: #f59e0b; }
.cl-card.admin-card .cl-icon { background: linear-gradient(135deg,#f59e0b,#ef4444); }
.cl-card.admin-card .cl-btn  { background: linear-gradient(135deg,#f59e0b,#ef4444); }

.cl-icon {
    width: 70px; height: 70px; border-radius: 20px;
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; color: #fff; margin: 0 auto 18px;
    box-shadow: 0 8px 20px rgba(0,0,0,.15);
    transition: transform .3s;
}
.cl-card:hover .cl-icon { transform: scale(1.1) rotate(-5deg); }

.cl-card-title {
    font-size: 18px; font-weight: 800; color: #1a1a2e;
    margin-bottom: 8px;
}
.cl-card-desc {
    font-size: 12.5px; color: #888; line-height: 1.5;
    margin-bottom: 20px; min-height: 38px;
}
.cl-btn {
    display: block; color: #fff; border-radius: 10px;
    padding: 9px 16px; font-size: 13px; font-weight: 700;
    text-decoration: none; transition: opacity .2s, transform .2s;
    box-shadow: 0 4px 12px rgba(0,0,0,.15);
}
.cl-btn:hover { opacity:.9; transform:scale(1.03); color:#fff; text-decoration:none; }

/* ── BOTTOM NOTE ── */
.cl-note {
    margin-top: 32px; font-size: 12.5px; color: #aaa;
    animation: fadeInUp .5s ease .8s both;
}
.cl-note a { color: #0a6ebd; text-decoration: none; font-weight: 600; }
.cl-note a:hover { text-decoration: underline; }
</style>

<div class="cl-page">
    <div class="cl-wrap">

        {{-- Logo --}}
        <div class="cl-logo">
            <img src="{{ asset('img/logo.png') }}" alt="RogiSewa">
        </div>

        <h2 class="cl-title">Welcome to RogiSewa</h2>
        <p class="cl-sub">Choose how you want to sign in</p>

        {{-- Cards --}}
        <div class="cl-cards">

            {{-- User --}}
            <a href="{{ route('user.login') }}" class="cl-card user-card">
                <div class="cl-icon">
                    <i class="fa fa-user"></i>
                </div>
                <div class="cl-card-title">Patient</div>
                <div class="cl-card-desc">Book appointments & manage your health records</div>
                <span class="cl-btn">Login as Patient</span>
            </a>

            {{-- Doctor --}}
            <a href="{{ route('login') }}" class="cl-card doctor-card">
                <div class="cl-icon">
                    <i class="fa fa-user-md"></i>
                </div>
                <div class="cl-card-title">Doctor</div>
                <div class="cl-card-desc">Manage profile, hospitals, prescriptions & staff</div>
                <span class="cl-btn">Login as Doctor</span>
            </a>

        </div>

        {{-- Bottom note --}}
        <div class="cl-note">
            New doctor? <a href="{{ route('register') }}">Register here</a>
            &nbsp;&nbsp;|&nbsp;&nbsp;
            <a href="{{ url('/') }}">← Back to Home</a>
        </div>

    </div>
</div>

@endsection
