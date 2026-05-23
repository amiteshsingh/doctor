@extends('page.layouts.app')

@section('title', 'How To Use RogiSewa – Doctor Dashboard Guide | RogiSewa')
@section('meta_description', 'Learn how to use RogiSewa doctor dashboard. Registration, admin approval, appointments, hospitals, medicines, staff and dashboard management guide.')

@section('content')

<style>
.step-card{
    border:1px solid #e9ecef;
    border-radius:14px;
    background:#fff;
    padding:22px;
    margin-bottom:18px;
    transition:all .2s ease;
}
.step-card:hover{
    box-shadow:0 8px 24px rgba(13,110,253,.08);
    transform:translateY(-2px);
}
.step-num{
    width:42px;
    height:42px;
    border-radius:50%;
    background:#0d6efd;
    color:#fff;
    font-weight:700;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
}
.section-title{
    font-weight:700;
    color:#222;
    border-left:5px solid #13C5DD;
    padding-left:12px;
    margin-bottom:22px;
}
.feature-icon{
    width:52px;
    height:52px;
    border-radius:12px;
    background:#eaf4ff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:1.3rem;
    flex-shrink:0;
}
.tip-box{
    background:#fff9e8;
    border-left:4px solid #ffc107;
    border-radius:8px;
    padding:14px 18px;
    margin-top:18px;
}
.lang-tab .nav-link{
    border:none;
    border-radius:40px;
    padding:9px 24px;
    background:#f1f3f5;
    color:#333;
    font-weight:600;
    margin:4px;
}
.lang-tab .nav-link.active{
    background:#0d6efd;
    color:#fff;
}
.flow-box{
    background:#fff;
    border:1px solid #e9ecef;
    border-radius:18px;
    padding:25px;
    text-align:center;
    height:100%;
}
.flow-icon{
    font-size:45px;
    margin-bottom:15px;
}
.dashboard-img{
    border-radius:18px;
    border:1px solid #e9ecef;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}
</style>

<!-- Header -->
<div class="container-fluid py-5" style="background:linear-gradient(135deg,#e8f4fd,#d4f5fb);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="fw-bold mb-2">How To Use RogiSewa</h1>

                <p class="text-muted mb-0">
                    Complete guide for users, doctors, and hospitals —
                    registration, admin approval, dashboard access,
                    appointments, medicines, staff management and more.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="https://play.google.com/store/apps/details?id=com.rogisewa"
                   target="_blank"
                   class="btn btn-dark">
                    <i class="fab fa-google-play me-2"></i>
                    Download App
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">

    <!-- Registration Flow -->
    <h2 class="section-title">Registration & Approval Process</h2>

    <div class="row g-4 mb-5">

        <div class="col-md-3">
            <div class="flow-box">
                <div class="flow-icon">👤</div>
                <h5 class="fw-bold">User Register</h5>

                <p class="text-muted small mb-0">
                    User creates account using email and mobile number.
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="flow-box">
                <div class="flow-icon">🩺</div>
                <h5 class="fw-bold">Doctor / Hospital Register</h5>

                <p class="text-muted small mb-0">
                    After login, user can register as Doctor or Hospital.
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="flow-box">
                <div class="flow-icon">✅</div>
                <h5 class="fw-bold">Admin Approval</h5>

                <p class="text-muted small mb-0">
                    Admin verifies profile and approves account.
                </p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="flow-box">
                <div class="flow-icon">📊</div>
                <h5 class="fw-bold">Dashboard Access</h5>

                <p class="text-muted small mb-0">
                    Approved users can access Doctor Dashboard.
                </p>
            </div>
        </div>

    </div>

    <!-- Dashboard Preview -->
    <div class="row mb-5">
        <div class="col-12">

            <h2 class="section-title">Doctor Dashboard Preview</h2>

            <div class="step-card text-center">

                <img src="{{ asset('doc_dashboard.png') }}"
                     class="img-fluid dashboard-img"
                     alt="Doctor Dashboard">

                <p class="text-muted mt-3 mb-0">
                    Actual Doctor Dashboard with Hospitals, Doctors,
                    Appointments, Medicines, Staff and Attendance modules.
                </p>

            </div>

        </div>
    </div>

    <!-- Language Tabs -->
    <ul class="nav lang-tab justify-content-center mb-5">

        <li class="nav-item">
            <button class="nav-link active"
                    data-bs-toggle="tab"
                    data-bs-target="#english">
                English
            </button>
        </li>

        <li class="nav-item">
            <button class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#hindi">
                हिंदी
            </button>
        </li>

    </ul>

    <div class="tab-content">

        <!-- ENGLISH -->
        <div class="tab-pane fade show active" id="english">

            <!-- Step 1 -->
            <h2 class="section-title">Step 1 – Create User Account</h2>

            <div class="step-card d-flex gap-3">

                <div class="step-num">1</div>

                <div>

                    <h5 class="fw-bold">Register on RogiSewa</h5>

                    <p>
                        Visit
                        <a href="{{ url('/') }}" target="_blank">
                            rogisewa.com
                        </a>
                        and create your account.
                    </p>

                    <ul class="mb-0">
                        <li>Create account using email and mobile number</li>
                        <li>Login into your account</li>
                        <li>Register as Doctor or Hospital</li>
                        <li>Profile goes for Admin Approval</li>
                        <li>After approval dashboard access will be enabled</li>
                    </ul>

                </div>

            </div>

            <!-- Step 2 -->
            <h2 class="section-title mt-4">Step 2 – Access Dashboard</h2>

            <div class="step-card d-flex gap-3">

                <div class="step-num">2</div>

                <div>

                    <h5 class="fw-bold">Doctor Dashboard Overview</h5>

                    <p>
                        After admin approval, login and access:
                        <strong>/doctor/dashboard</strong>
                    </p>

                    <ul class="mb-0">
                        <li>View Doctors, Hospitals and Appointments</li>
                        <li>Manage Medicines and Staff</li>
                        <li>Track Attendance</li>
                        <li>Manage Invoice Settings</li>
                        <li>View Membership Status</li>
                    </ul>

                </div>

            </div>

            <!-- Step 3 -->
            <h2 class="section-title mt-4">Step 3 – Manage Hospitals</h2>

            <div class="step-card d-flex gap-3">

                <div class="step-num">3</div>

                <div>

                    <h5 class="fw-bold">Hospitals Section</h5>

                    <p>
                        Go to:
                        <strong>Dashboard → Hospitals</strong>
                    </p>

                    <ul class="mb-0">
                        <li>Add clinic or hospital details</li>
                        <li>Manage address and contact information</li>
                        <li>Update hospital profile</li>
                    </ul>

                </div>

            </div>

            <!-- Step 4 -->
            <h2 class="section-title mt-4">Step 4 – Manage Doctors</h2>

            <div class="step-card d-flex gap-3">

                <div class="step-num">4</div>

                <div>

                    <h5 class="fw-bold">Doctors Section</h5>

                    <p>
                        Go to:
                        <strong>Dashboard → Doctors</strong>
                    </p>

                    <ul class="mb-0">
                        <li>Manage doctor profile</li>
                        <li>Update specialization and experience</li>
                        <li>Manage doctor details and information</li>
                    </ul>

                </div>

            </div>

            <!-- Step 5 -->
            <h2 class="section-title mt-4">Step 5 – Appointments</h2>

            <div class="step-card d-flex gap-3">

                <div class="step-num">5</div>

                <div>

                    <h5 class="fw-bold">Manage Appointments</h5>

                    <p>
                        Go to:
                        <strong>Dashboard → Appointments</strong>
                    </p>

                    <ul class="mb-0">
                        <li>View patient appointments</li>
                        <li>Manage booking schedule</li>
                        <li>Track appointment history</li>
                    </ul>

                </div>

            </div>

            <!-- Step 6 -->
            <h2 class="section-title mt-4">Step 6 – Invoice Settings</h2>

            <div class="step-card d-flex gap-3">

                <div class="step-num">6</div>

                <div>

                    <h5 class="fw-bold">Invoice & Booking Settings</h5>

                    <p>
                        Go to:
                        <strong>Dashboard → Invoice Settings</strong>
                    </p>

                    <ul class="mb-0">
                        <li>Manage invoice settings</li>
                        <li>Configure booking system</li>
                        <li>Setup appointment timing</li>
                    </ul>

                </div>

            </div>

            <!-- Step 7 -->
            <h2 class="section-title mt-4">Step 7 – Medicines & Staff</h2>

            <div class="step-card d-flex gap-3">

                <div class="step-num">7</div>

                <div>

                    <h5 class="fw-bold">Medicine & Staff Management</h5>

                    <p>
                        Go to:
                        <strong>Dashboard → Medicine / Staff</strong>
                    </p>

                    <ul class="mb-0">
                        <li>Maintain medicine records</li>
                        <li>Add clinic staff</li>
                        <li>Manage staff roles</li>
                    </ul>

                </div>

            </div>

            <!-- Step 8 -->
            <h2 class="section-title mt-4">Step 8 – Attendance</h2>

            <div class="step-card d-flex gap-3">

                <div class="step-num">8</div>

                <div>

                    <h5 class="fw-bold">Track Attendance</h5>

                    <p>
                        Go to:
                        <strong>Dashboard → Attendance</strong>
                    </p>

                    <ul class="mb-0">
                        <li>Track daily attendance</li>
                        <li>Monitor staff activity</li>
                        <li>Maintain attendance records</li>
                    </ul>

                </div>

            </div>

            <!-- Features -->
            <h2 class="section-title mt-5">Dashboard Features</h2>

            <div class="row g-3">

                @foreach([
                    ['fa-home','Dashboard','View dashboard summary'],
                    ['fa-hospital','Hospitals','Manage hospitals and clinics'],
                    ['fa-user-doctor','Doctors','Manage doctor profiles'],
                    ['fa-calendar-check','Appointments','Manage patient appointments'],
                    ['fa-file-invoice','Invoice Settings','Manage invoice configuration'],
                    ['fa-pills','Medicine','Maintain medicine records'],
                    ['fa-users','Staff','Manage staff members'],
                    ['fa-user-check','Attendance','Track attendance'],
                ] as $f)

                <div class="col-md-6">

                    <div class="step-card d-flex gap-3 align-items-start">

                        <div class="feature-icon">
                            <i class="fa {{ $f[0] }} text-primary"></i>
                        </div>

                        <div>
                            <h6 class="fw-bold mb-1">{{ $f[1] }}</h6>
                            <p class="text-muted small mb-0">{{ $f[2] }}</p>
                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

        <!-- HINDI -->
        <div class="tab-pane fade" id="hindi">

            <h2 class="section-title">RogiSewa कैसे इस्तेमाल करें</h2>

            <div class="step-card">

                <ul class="mb-0">
                    <li>User पहले account बनाएगा</li>
                    <li>Login करने के बाद Doctor या Hospital register करेगा</li>
                    <li>उसके बाद Admin approval होगा</li>
                    <li>Approval के बाद Dashboard access मिलेगा</li>
                </ul>

            </div>

            <div class="step-card">

                <h5 class="fw-bold">Dashboard Modules</h5>

                <ul class="mb-0">
                    <li>Hospitals manage करें</li>
                    <li>Doctors profile manage करें</li>
                    <li>Appointments track करें</li>
                    <li>Medicines maintain करें</li>
                    <li>Staff और Attendance manage करें</li>
                    <li>Invoice Settings configure करें</li>
                </ul>

            </div>

            <div class="tip-box">

                <i class="fa fa-info-circle text-warning me-2"></i>

                <strong>Help चाहिए?</strong>

                हमें
                <a href="mailto:rogisewa25@gmail.com">
                    rogisewa25@gmail.com
                </a>
                पर email करें।

            </div>

        </div>

    </div>

</div>

@endsection