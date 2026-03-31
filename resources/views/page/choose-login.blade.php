@extends('page.layouts.app')
@section('title', 'RogiSewa - Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h4 class="fw-bold text-center mb-4">Login As</h4>
            <div class="row g-4">

                <div class="col-6">
                    <a href="{{ route('user.login') }}" class="text-decoration-none">
                        <div class="card shadow border-0 rounded-4 p-4 text-center h-100">
                            <i class="fa fa-user-circle fa-4x text-primary mb-3"></i>
                            <h5 class="fw-bold text-dark">User</h5>
                            <p class="text-muted small mb-0">Book appointments & manage your health</p>
                        </div>
                    </a>
                </div>

                <div class="col-6">
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        <div class="card shadow border-0 rounded-4 p-4 text-center h-100">
                            <i class="fa fa-user-md fa-4x text-success mb-3"></i>
                            <h5 class="fw-bold text-dark">Doctor</h5>
                            <p class="text-muted small mb-0">Manage your profile & appointments</p>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
