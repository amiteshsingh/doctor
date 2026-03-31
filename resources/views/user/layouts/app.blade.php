@extends('page.layouts.app')

@section('content')
@php
    $sidePic = auth()->user()->profile_pic
        ? (app()->environment('local')
            ? asset('uploads/profile_images/' . auth()->user()->profile_pic)
            : asset('storage/uploads/profile_images/' . auth()->user()->profile_pic))
        : asset('img/user.jpg');
@endphp
<div class="container py-5">
    <div class="row g-4">

        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="card shadow border-0 rounded-4 p-3 bg-white">
                <div class="text-center mb-3 pb-3 border-bottom">
                    <img src="{{ $sidePic }}"
                         class="rounded-circle border border-3 border-primary mb-2"
                         style="width:80px;height:80px;object-fit:cover;">
                    <h6 class="fw-bold mb-0">{{ auth()->user()->name }}</h6>
                    <small class="text-muted">{{ auth()->user()->phone_no }}</small>
                </div>
                <ul class="list-unstyled mb-0">
                    <li class="mb-1">
                        <a href="{{ route('user.profile') }}"
                           class="d-flex align-items-center px-3 py-2 rounded-3 text-decoration-none {{ Request::is('user/profile') ? 'bg-primary text-white' : 'text-dark' }}">
                            <i class="fa fa-user me-2"></i> My Profile
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="{{ route('user.bookings') }}"
                           class="d-flex align-items-center px-3 py-2 rounded-3 text-decoration-none {{ Request::is('user/bookings') ? 'bg-primary text-white' : 'text-dark' }}">
                            <i class="fa fa-calendar-check me-2"></i> My Bookings
                        </a>
                    </li>
                    <li class="mt-3">
                        <a href="{{ route('user.logout') }}"
                           class="d-flex align-items-center px-3 py-2 rounded-3 text-decoration-none text-danger">
                            <i class="fa fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            @yield('user_content')
        </div>

    </div>
</div>
@endsection
