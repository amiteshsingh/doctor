<!DOCTYPE html>
<html lang="en">



<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/logo.png') }}">
    <title>RogiSewa.com - Medical & Hospital - Admin Template</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/style.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/bootstrap-datetimepicker.min.css') }}">

<meta name="csrf-token" content="{{ csrf_token() }}">
    <!--[if lt IE 9]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
	<![endif]-->
    <style>
    .hidden {
        display: none !important;
    }

    .loaderDiv {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.6);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1050;
    }

    </style>

    <style>

    /* ===== HEADER COLORFUL THEME ===== */
    .header {
        background: linear-gradient(135deg, #0f0c29 0%, #302b63 60%, #0a6ebd 100%) !important;
        box-shadow: 0 2px 20px rgba(0,0,0,.35) !important;
    }

    /* Toggle btn */
    #toggle_btn { color: rgba(255,255,255,.8) !important; }
    #toggle_btn:hover { color: #fff !important; }

    /* Nav icons */
    .user-menu.nav > li > a {
        color: rgba(255,255,255,.85) !important;
    }
    .user-menu.nav > li > a:hover,
    .user-menu.nav > li > a:focus {
        background: rgba(255,255,255,.12) !important;
        color: #fff !important;
        border-radius: 8px;
    }

    /* Badge */
    .user-menu .badge { box-shadow: 0 0 0 2px rgba(255,255,255,.3); }

    /* User name */
    .user-link span { color: #fff !important; }

    /* Dropdown */
    .header .dropdown-menu {
        background: #1e1b4b !important;
        border: 1px solid rgba(255,255,255,.1) !important;
        box-shadow: 0 8px 32px rgba(0,0,0,.4) !important;
        border-radius: 12px !important;
        overflow: hidden;
    }
    .header .dropdown-menu .dropdown-item {
        color: rgba(255,255,255,.8) !important;
        font-size: 13px;
        padding: 9px 18px;
        transition: background .2s;
    }
    .header .dropdown-menu .dropdown-item:hover {
        background: rgba(255,255,255,.1) !important;
        color: #fff !important;
    }

    /* Notification dropdown */
    .header .notifications {
        background: #1e1b4b !important;
        border: 1px solid rgba(255,255,255,.1) !important;
        border-radius: 12px !important;
    }
    .header .topnav-dropdown-header {
        background: linear-gradient(135deg,#0a6ebd,#00b074) !important;
        color: #fff !important;
        border-radius: 12px 12px 0 0;
    }
    .header .notification-list .noti-details { color: rgba(255,255,255,.8) !important; }
    .header .notification-list .noti-time   { color: rgba(255,255,255,.5) !important; }
    .header .notification-message:hover { background: rgba(255,255,255,.07) !important; }
    .header .topnav-dropdown-footer a {
        color: #60a5fa !important;
        background: rgba(255,255,255,.05) !important;
    }

    /* Mobile menu */
    .mobile-user-menu > a { color: rgba(255,255,255,.85) !important; }
    .mobile-user-menu .dropdown-menu {
        background: #1e1b4b !important;
        border-radius: 10px !important;
    }
    .mobile-user-menu .dropdown-item { color: rgba(255,255,255,.8) !important; }
    .mobile-user-menu .dropdown-item:hover { background: rgba(255,255,255,.1) !important; }
    /* ===== END HEADER THEME ===== */

    /* ===== SIDEBAR COLORFUL THEME ===== */
    .sidebar {
        background: linear-gradient(180deg, #0f0c29 0%, #302b63 50%, #24243e 100%) !important;
        box-shadow: 4px 0 20px rgba(0,0,0,.3) !important;
    }
    .sidebar-menu ul { background: transparent !important; }

    /* Menu title */
    .menu-title {
        color: rgba(255,255,255,.4) !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        letter-spacing: 1.5px !important;
        text-transform: uppercase !important;
        padding: 16px 20px 8px !important;
    }

    /* All links */
    .sidebar-menu li a {
        color: rgba(255,255,255,.65) !important;
        transition: all .25s ease !important;
        border-radius: 0 !important;
        margin: 1px 10px !important;
        border-radius: 8px !important;
        padding: 11px 14px !important;
    }
    .sidebar-menu li a:hover {
        color: #fff !important;
        background: rgba(255,255,255,.12) !important;
        padding-left: 20px !important;
    }

    /* Active item */
    .sidebar-menu li.active > a {
        color: #fff !important;
        background: linear-gradient(135deg, rgba(10,110,189,.7), rgba(0,176,116,.7)) !important;
        box-shadow: 0 4px 14px rgba(0,0,0,.25) !important;
    }

    /* Icons — colorful per item */
    .sidebar-menu ul > li:nth-child(2) > a i  { color: #a78bfa; }
    .sidebar-menu ul > li:nth-child(3) > a i  { color: #f472b6; }
    .sidebar-menu ul > li:nth-child(4) > a i  { color: #38bdf8; }
    .sidebar-menu ul > li:nth-child(5) > a i  { color: #34d399; }
    .sidebar-menu ul > li:nth-child(6) > a i  { color: #fb923c; }
    .sidebar-menu ul > li:nth-child(7) > a i  { color: #fbbf24; }
    .sidebar-menu ul > li:nth-child(8) > a i  { color: #60a5fa; }
    .sidebar-menu ul > li:nth-child(9) > a i  { color: #f87171; }
    .sidebar-menu ul > li.active > a i        { color: #fff !important; }

    /* Submenu */
    .sidebar-menu ul ul {
        background: rgba(0,0,0,.2) !important;
        border-radius: 8px;
        margin: 0 10px 4px;
    }
    .sidebar-menu ul ul a {
        color: rgba(255,255,255,.6) !important;
        font-size: 13px !important;
        padding: 8px 10px 8px 44px !important;
        margin: 0 !important;
        border-radius: 6px !important;
    }
    .sidebar-menu ul ul a:hover,
    .sidebar-menu ul ul a.active {
        color: #fff !important;
        background: rgba(255,255,255,.1) !important;
        text-decoration: none !important;
    }

    /* Logo area */
    .header-left {
        background: linear-gradient(135deg, #0a6ebd, #00b074) !important;
    }
    .logo span { color: #fff !important; }

    /* Scrollbar */
    .sidebar-inner::-webkit-scrollbar { width: 4px; }
    .sidebar-inner::-webkit-scrollbar-track { background: transparent; }
    .sidebar-inner::-webkit-scrollbar-thumb { background: rgba(255,255,255,.2); border-radius: 4px; }

    /* ===== END SIDEBAR THEME ===== */

    /* ===== HEADER & SIDEBAR ANIMATIONS ===== */

    /* Header slide-down animation */
    .header {
        animation: headerSlideDown 0.5s ease forwards;
    }
    @keyframes headerSlideDown {
        from { transform: translateY(-100%); opacity: 0; }
        to   { transform: translateY(0);    opacity: 1; }
    }

    /* Logo pulse on load */
    .header-left .logo img {
        animation: logoPulse 0.6s ease 0.4s both;
    }
    @keyframes logoPulse {
        0%   { transform: scale(0.5); opacity: 0; }
        70%  { transform: scale(1.15); }
        100% { transform: scale(1);   opacity: 1; }
    }

    /* Logo text fade-in */
    .header-left .logo span {
        animation: fadeInLeft 0.5s ease 0.6s both;
    }

    /* Nav items stagger fade-in */
    .nav.user-menu > li {
        animation: fadeInDown 0.4s ease both;
    }
    .nav.user-menu > li:nth-child(1) { animation-delay: 0.5s; }
    .nav.user-menu > li:nth-child(2) { animation-delay: 0.65s; }
    .nav.user-menu > li:nth-child(3) { animation-delay: 0.8s; }

    /* Toggle button spin on hover */
    #toggle_btn { transition: transform 0.3s ease; }
    #toggle_btn:hover { transform: rotate(90deg); }

    /* Bell & message icon bounce on hover */
    .nav.user-menu .fa-bell-o,
    .nav.user-menu .fa-comment-o {
        display: inline-block;
        transition: transform 0.3s ease;
    }
    .nav.user-menu li:hover .fa-bell-o { animation: bellRing 0.5s ease; }
    .nav.user-menu li:hover .fa-comment-o { animation: msgBounce 0.4s ease; }
    @keyframes bellRing {
        0%,100% { transform: rotate(0); }
        25%     { transform: rotate(-20deg); }
        75%     { transform: rotate(20deg); }
    }
    @keyframes msgBounce {
        0%,100% { transform: translateY(0); }
        50%     { transform: translateY(-5px); }
    }

    /* User avatar hover scale */
    .user-img img {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .user-img img:hover {
        transform: scale(1.15);
        box-shadow: 0 0 0 3px rgba(0,176,116,0.4);
    }

    /* Dropdown slide-down */
    .header .dropdown-menu {
        animation: dropSlide 0.25s ease forwards;
        transform-origin: top center;
    }
    @keyframes dropSlide {
        from { opacity: 0; transform: scaleY(0.85) translateY(-8px); }
        to   { opacity: 1; transform: scaleY(1)   translateY(0); }
    }

    /* Sidebar slide-in from left */
    .sidebar {
        animation: sidebarSlideIn 0.5s cubic-bezier(0.25,0.46,0.45,0.94) 0.1s both;
    }
    @keyframes sidebarSlideIn {
        from { transform: translateX(-100%); opacity: 0; }
        to   { transform: translateX(0);     opacity: 1; }
    }

    /* Sidebar menu items stagger */
    #sidebar-menu ul > li {
        opacity: 0;
        animation: sidebarItemFade 0.35s ease forwards;
    }
    #sidebar-menu ul > li:nth-child(1)  { animation-delay: 0.4s; }
    #sidebar-menu ul > li:nth-child(2)  { animation-delay: 0.5s; }
    #sidebar-menu ul > li:nth-child(3)  { animation-delay: 0.6s; }
    #sidebar-menu ul > li:nth-child(4)  { animation-delay: 0.7s; }
    #sidebar-menu ul > li:nth-child(5)  { animation-delay: 0.8s; }
    #sidebar-menu ul > li:nth-child(6)  { animation-delay: 0.9s; }
    #sidebar-menu ul > li:nth-child(7)  { animation-delay: 1.0s; }
    #sidebar-menu ul > li:nth-child(n+8){ animation-delay: 1.1s; }
    @keyframes sidebarItemFade {
        from { opacity: 0; transform: translateX(-20px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    /* Sidebar link hover effect */
    #sidebar-menu ul > li > a {
        transition: padding-left 0.25s ease, background 0.25s ease, color 0.25s ease;
    }
    #sidebar-menu ul > li > a:hover {
        padding-left: 20px;
    }

    /* Sidebar icon spin on hover */
    #sidebar-menu ul > li > a i {
        transition: transform 0.3s ease;
    }
    #sidebar-menu ul > li > a:hover i {
        transform: rotate(15deg) scale(1.2);
    }

    /* Active sidebar item glow */
    #sidebar-menu ul > li.active > a {
        box-shadow: inset 3px 0 0 #00b074;
    }

    /* Submenu arrow rotate */
    .menu-arrow {
        transition: transform 0.3s ease;
    }
    .submenu.active > a .menu-arrow {
        transform: rotate(90deg);
    }

    /* Shared keyframes */
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInLeft {
        from { opacity: 0; transform: translateX(-15px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    /* ===== END ANIMATIONS ===== */

    /* Success theme for jGrowl */
    .jGrowl-notification.success-theme {
        background-color: #4CAF50 !important; /* Green */
        color: white !important;
    }

    /* Error theme for jGrowl */
    .jGrowl-notification.error-theme {
        background-color: #f44336 !important; /* Red */
        color: white !important;
    }

    /* Make header text larger */
    .jGrowl .jGrowl-notification .jGrowl-header {
        font-size: 18px;
        font-weight: bold;
    }

    </style>


</head>

<body>

<div class="loaderDiv hidden">
  <div class="spinner-border text-primary" role="status">
    <span class="sr-only">Loading...</span>
  </div>
</div>
<div id="growl" class="growl"></div>


<div class="main-wrapper">

<div class="header">
    @php
        $user = Auth::user();
        $headerPic = $user->profile_image
            ? asset('storage/upload/profile_images/' . $user->profile_image)
            : asset('admin/assets/img/user.jpg');
    @endphp
    <div class="header-left">
        <a href="{{ route('doctor.dashboard') }}" class="logo">
            <img src="{{ asset('admin/assets/img/logo.png') }}" width="35" height="35" alt=""> <span>RogiSewa</span>
        </a>
    </div>
    <a id="toggle_btn" href="javascript:void(0);"><i class="fa fa-bars"></i></a>
    <a id="mobile_btn" class="mobile_btn float-left" href="#sidebar"><i class="fa fa-bars"></i></a>
    <ul class="nav user-menu float-right">
        <li class="nav-item dropdown d-none d-sm-block">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown"><i class="fa fa-bell-o"></i> <span class="badge badge-pill bg-danger float-right">3</span></a>
            <div class="dropdown-menu notifications">
                <div class="topnav-dropdown-header">
                    <span>Notifications</span>
                </div>
                <div class="drop-scroll">
                    <ul class="notification-list">
                        <li class="notification-message">
                            <a href="activities.html">
                                <div class="media">
                                    <span class="avatar">
                                        <img alt="John Doe" src="{{ asset('admin/assets/img/user.jpg') }}" class="img-fluid">
                                    </span>
                                    <div class="media-body">
                                        <p class="noti-details"><span class="noti-title">John Doe</span> added new task <span class="noti-title">Patient appointment booking</span></p>
                                        <p class="noti-time"><span class="notification-time">4 mins ago</span></p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="notification-message">
                            <a href="activities.html">
                                <div class="media">
                                    <span class="avatar">V</span>
                                    <div class="media-body">
                                        <p class="noti-details"><span class="noti-title">Tarah Shropshire</span> changed the task name <span class="noti-title">Appointment booking with payment gateway</span></p>
                                        <p class="noti-time"><span class="notification-time">6 mins ago</span></p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="notification-message">
                            <a href="activities.html">
                                <div class="media">
                                    <span class="avatar">L</span>
                                    <div class="media-body">
                                        <p class="noti-details"><span class="noti-title">Misty Tison</span> added <span class="noti-title">Domenic Houston</span> and <span class="noti-title">Claire Mapes</span> to project <span class="noti-title">Doctor available module</span></p>
                                        <p class="noti-time"><span class="notification-time">8 mins ago</span></p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="notification-message">
                            <a href="activities.html">
                                <div class="media">
                                    <span class="avatar">G</span>
                                    <div class="media-body">
                                        <p class="noti-details"><span class="noti-title">Rolland Webber</span> completed task <span class="noti-title">Patient and Doctor video conferencing</span></p>
                                        <p class="noti-time"><span class="notification-time">12 mins ago</span></p>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="notification-message">
                            <a href="activities.html">
                                <div class="media">
                                    <span class="avatar">V</span>
                                    <div class="media-body">
                                        <p class="noti-details"><span class="noti-title">Bernardo Galaviz</span> added new task <span class="noti-title">Private chat module</span></p>
                                        <p class="noti-time"><span class="notification-time">2 days ago</span></p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="topnav-dropdown-footer">
                    <a href="activities.html">View all Notifications</a>
                </div>
            </div>
        </li>
        <li class="nav-item dropdown d-none d-sm-block">
            <a href="javascript:void(0);" id="open_msg_box" class="hasnotifications nav-link"><i class="fa fa-comment-o"></i> <span class="badge badge-pill bg-danger float-right">8</span></a>
        </li>
        <li class="nav-item dropdown has-arrow">
            <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                <span class="user-img">
                    <img class="rounded-circle" src="{{ $headerPic }}" width="24" alt="{{ $user->name }}">
                    <span class="status online"></span>
                </span>
                <span>{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ url('doctor/edit-profile') }}">Edit Profile</a>

                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                </form>
            </div>
        </li>
    </ul>
    <div class="dropdown mobile-user-menu float-right">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ url('doctor/edit-profile') }}">Edit Profile</a>
            <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>