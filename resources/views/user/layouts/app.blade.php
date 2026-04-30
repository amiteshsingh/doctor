@extends('page.layouts.app')

@section('content')
@php
    $sidePic = auth()->user()->profile_image
        ? (app()->environment('local')
            ? asset('uploads/profile_images/' . auth()->user()->profile_image)
            : asset('storage/uploads/profile_images/' . auth()->user()->profile_image))
        : asset('img/user.jpg');
@endphp

<style>
.up-page { animation: pgFade .45s ease both; }
@keyframes pgFade { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }

/* ── SIDEBAR ── */
.up-sidebar {
    background: linear-gradient(180deg,#1a1a2e 0%,#302b63 60%,#0a6ebd 100%);
    border-radius: 18px;
    padding: 24px 16px;
    box-shadow: 0 8px 32px rgba(10,110,189,.2);
    animation: sideSlide .5s ease both;
    position: sticky; top: 80px;
}
@keyframes sideSlide { from{opacity:0;transform:translateX(-20px)} to{opacity:1;transform:translateX(0)} }

.up-avatar-wrap { text-align:center; margin-bottom:20px; padding-bottom:18px; border-bottom:1px solid rgba(255,255,255,.12); }
.up-avatar {
    width:80px; height:80px; border-radius:50%; object-fit:cover;
    border:3px solid rgba(255,255,255,.5);
    box-shadow:0 4px 16px rgba(0,0,0,.3);
    animation: avatarPop .6s cubic-bezier(.34,1.56,.64,1) .2s both;
}
@keyframes avatarPop { from{opacity:0;transform:scale(.5)} to{opacity:1;transform:scale(1)} }
.up-uname { color:#fff; font-weight:700; font-size:14px; margin-top:10px; margin-bottom:2px; }
.up-uphone { color:rgba(255,255,255,.6); font-size:12px; }

/* Nav links */
.up-nav { list-style:none; padding:0; margin:0; }
.up-nav li { margin-bottom:4px; animation:fadeInLeft .4s ease both; }
.up-nav li:nth-child(1){animation-delay:.2s}
.up-nav li:nth-child(2){animation-delay:.3s}
.up-nav li:nth-child(3){animation-delay:.4s}
.up-nav li:nth-child(4){animation-delay:.5s}
@keyframes fadeInLeft { from{opacity:0;transform:translateX(-14px)} to{opacity:1;transform:translateX(0)} }

.up-nav a {
    display:flex; align-items:center; gap:10px;
    padding:10px 14px; border-radius:10px;
    color:rgba(255,255,255,.7); text-decoration:none;
    font-size:13.5px; font-weight:600;
    transition:all .25s;
}
.up-nav a:hover { background:rgba(255,255,255,.12); color:#fff; padding-left:18px; }
.up-nav a.active { background:linear-gradient(135deg,rgba(10,110,189,.7),rgba(0,176,116,.7)); color:#fff; box-shadow:0 4px 12px rgba(0,0,0,.2); }
.up-nav a i { width:18px; text-align:center; font-size:14px; }
.up-nav .logout-link a { color:rgba(255,100,100,.8); }
.up-nav .logout-link a:hover { background:rgba(239,68,68,.15); color:#ff6b6b; }

/* ── CONTENT CARD ── */
.up-content {
    animation: contentFade .5s ease .1s both;
}
@keyframes contentFade { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
</style>

<div class="container py-4 up-page">
    <div class="row g-4">

        {{-- Sidebar --}}
        <div class="col-lg-3">
            <div class="up-sidebar">
                <div class="up-avatar-wrap">
                    <img src="{{ $sidePic }}" class="up-avatar" alt="{{ auth()->user()->name }}">
                    <div class="up-uname">{{ auth()->user()->name }}</div>
                    <div class="up-uphone">{{ auth()->user()->phone_no }}</div>
                </div>
                <ul class="up-nav">
                    <li>
                        <a href="{{ route('user.profile') }}" class="{{ Request::is('user/profile') ? 'active' : '' }}">
                            <i class="fa fa-user"></i> My Profile
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.bookings') }}" class="{{ Request::is('user/bookings') ? 'active' : '' }}">
                            <i class="fa fa-calendar-check-o"></i> My Bookings
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.favourites') }}" class="{{ Request::is('user/favourites') ? 'active' : '' }}">
                            <i class="fa fa-heart"></i> My Favourites
                        </a>
                    </li>
                    <li class="logout-link" style="margin-top:12px;border-top:1px solid rgba(255,255,255,.1);padding-top:12px;">
                        <a href="{{ route('user.logout') }}">
                            <i class="fa fa-sign-out"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="col-lg-9 up-content">
            @yield('user_content')
        </div>

    </div>
</div>
@endsection
