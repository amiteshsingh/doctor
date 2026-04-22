@extends('doctor.layouts.app')

@section('content')

@php
    $totalDoctors      = getTotalDoctorsBySession();
    $totalHospitals    = getTotalHospitalsBySession();
    $totalAppointments = \App\Models\PrescriptionInvoice::whereHas('invoiceMaster', function($q) {
        $q->where('added_by', Auth::id());
    })->count();
    $todayAppointments = \App\Models\PrescriptionInvoice::whereHas('invoiceMaster', function($q) {
        $q->where('added_by', Auth::id());
    })->whereDate('booking_date', today())->count();
@endphp

<style>
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes countUp {
    from { opacity: 0; transform: scale(0.5); }
    to   { opacity: 1; transform: scale(1); }
}
@keyframes pulse-ring {
    0%   { transform: scale(0.8); opacity: 0.8; }
    50%  { transform: scale(1.1); opacity: 0.4; }
    100% { transform: scale(0.8); opacity: 0.8; }
}
@keyframes shimmer {
    0%   { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50%       { transform: translateY(-8px); }
}

.stat-card {
    border: none;
    border-radius: 16px;
    padding: 24px;
    color: #fff;
    position: relative;
    overflow: hidden;
    animation: fadeInUp 0.6s ease forwards;
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
}
.stat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.2) !important;
}
.stat-card .bg-icon {
    position: absolute;
    right: -10px;
    bottom: -10px;
    font-size: 80px;
    opacity: 0.15;
}
.stat-card .stat-num {
    font-size: 2.5rem;
    font-weight: 800;
    animation: countUp 0.8s ease forwards;
}
.stat-card .pulse {
    width: 50px; height: 50px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex; align-items: center; justify-content: center;
    animation: pulse-ring 2s infinite;
    font-size: 22px;
}
.card-1 { background: linear-gradient(135deg, #667eea, #764ba2); animation-delay: 0.1s; }
.card-2 { background: linear-gradient(135deg, #f093fb, #f5576c); animation-delay: 0.2s; }
.card-3 { background: linear-gradient(135deg, #4facfe, #00f2fe); animation-delay: 0.3s; }
.card-4 { background: linear-gradient(135deg, #43e97b, #38f9d7); animation-delay: 0.4s; }

.welcome-card {
    border: none;
    border-radius: 16px;
    background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460);
    color: #fff;
    padding: 30px;
    animation: fadeInUp 0.7s ease 0.5s forwards;
    opacity: 0;
    position: relative;
    overflow: hidden;
}
.welcome-card::before {
    content: '';
    position: absolute;
    top: -50%; left: -50%;
    width: 200%; height: 200%;
    background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.03) 50%, transparent 70%);
    background-size: 200% 200%;
    animation: shimmer 4s infinite;
}
.welcome-card .doctor-avatar {
    width: 70px; height: 70px;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,0.3);
    object-fit: cover;
    animation: float 3s ease-in-out infinite;
}
.quick-link {
    border: none;
    border-radius: 12px;
    padding: 16px;
    text-align: center;
    text-decoration: none;
    color: #333;
    background: #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s;
    display: block;
    animation: fadeInUp 0.6s ease forwards;
    opacity: 0;
}
.quick-link:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    color: #333;
    text-decoration: none;
}
.quick-link .ql-icon {
    width: 50px; height: 50px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    margin: 0 auto 10px;
    color: #fff;
}
.recent-table { border-radius: 16px; overflow: hidden; }
.recent-table thead { background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; }
.badge-status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
</style>

<div class="page-wrapper">
    <div class="content">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0 fw-bold">Dashboard</h4>
                <small class="text-muted">Welcome back, {{ Auth::user()->name }}! 👋</small>
            </div>
            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#noteModal">
                <i class="fa fa-bell me-1"></i> Important Note
            </button>
        </div>

        <!-- Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-sm-6">
                <a href="{{ url('doctor/mydoctor') }}" style="text-decoration:none;">
                    <div class="stat-card card-1 shadow">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-1" style="opacity:0.85;font-size:13px;">Total Doctors</p>
                                <div class="stat-num" data-target="{{ $totalDoctors }}">0</div>
                            </div>
                            <div class="pulse"><i class="fa fa-stethoscope"></i></div>
                        </div>
                        <div class="mt-2" style="font-size:12px;opacity:0.8;">
                            <i class="fa fa-arrow-up me-1"></i> View all doctors
                        </div>
                        <i class="fa fa-stethoscope bg-icon"></i>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ url('doctor/myhospital') }}" style="text-decoration:none;">
                    <div class="stat-card card-2 shadow">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-1" style="opacity:0.85;font-size:13px;">Total Hospitals</p>
                                <div class="stat-num" data-target="{{ $totalHospitals }}">0</div>
                            </div>
                            <div class="pulse"><i class="fa fa-hospital-o"></i></div>
                        </div>
                        <div class="mt-2" style="font-size:12px;opacity:0.8;">
                            <i class="fa fa-arrow-up me-1"></i> View all hospitals
                        </div>
                        <i class="fa fa-hospital-o bg-icon"></i>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ url('doctor/prescription-invoice') }}" style="text-decoration:none;">
                    <div class="stat-card card-3 shadow">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-1" style="opacity:0.85;font-size:13px;">Total Appointments</p>
                                <div class="stat-num" data-target="{{ $totalAppointments }}">0</div>
                            </div>
                            <div class="pulse"><i class="fa fa-calendar-check-o"></i></div>
                        </div>
                        <div class="mt-2" style="font-size:12px;opacity:0.8;">
                            <i class="fa fa-arrow-up me-1"></i> View appointments
                        </div>
                        <i class="fa fa-calendar bg-icon"></i>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{ url('doctor/prescription-invoice') }}" style="text-decoration:none;">
                    <div class="stat-card card-4 shadow">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-1" style="opacity:0.85;font-size:13px;">Today's Appointments</p>
                                <div class="stat-num" data-target="{{ $todayAppointments }}">0</div>
                            </div>
                            <div class="pulse"><i class="fa fa-clock-o"></i></div>
                        </div>
                        <div class="mt-2" style="font-size:12px;opacity:0.8;">
                            <i class="fa fa-calendar me-1"></i> {{ today()->format('d M Y') }}
                        </div>
                        <i class="fa fa-clock-o bg-icon"></i>
                    </div>
                </a>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <!-- Welcome Card -->
            <div class="col-lg-5">
                <div class="welcome-card h-100">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('storage/upload/doctor/' . (Auth::user()->profile_pic ?? 'default.jpg')) }}"
                             class="doctor-avatar me-3" alt="Profile">
                        <div>
                            <h5 class="mb-0 fw-bold">Dr. {{ Auth::user()->name }}</h5>
                            <small style="opacity:0.7;">{{ Auth::user()->email }}</small>
                        </div>
                    </div>
                    <hr style="border-color:rgba(255,255,255,0.15);">
                    <p style="opacity:0.85;font-size:14px;">
                        🔔 Please let us know which features you need on your dashboard.
                        Based on your requirements, we will improve and customize it.
                    </p>
                    <ul style="opacity:0.8;font-size:13px;padding-left:18px;">
                        <li>🩺 Online Prescription / Appointment Management</li>
                        <li>👨‍⚕️ Patient Data Management</li>
                        <li>💊 Medicine Data Management</li>
                        <li>💳 Payment Management</li>
                        <li>📄 Prescription Invoice Generation</li>
                    </ul>
                    <a href="mailto:rogisewa25@gmail.com" class="btn btn-sm mt-2"
                       style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.3);border-radius:8px;">
                        <i class="fa fa-envelope me-1"></i> rogisewa25@gmail.com
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-7">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-3">
                    <h6 class="fw-bold mb-3 text-muted">Quick Actions</h6>
                    <div class="row g-3">
                        <div class="col-4" style="animation-delay:0.1s;">
                            <a href="{{ url('doctor/mydoctor/add') }}" class="quick-link">
                                <div class="ql-icon" style="background:linear-gradient(135deg,#667eea,#764ba2);">
                                    <i class="fa fa-user-plus"></i>
                                </div>
                                <small class="fw-bold">Add Doctor</small>
                            </a>
                        </div>
                        <div class="col-4" style="animation-delay:0.2s;">
                            <a href="{{ url('doctor/myhospital/add') }}" class="quick-link">
                                <div class="ql-icon" style="background:linear-gradient(135deg,#f093fb,#f5576c);">
                                    <i class="fa fa-plus-square"></i>
                                </div>
                                <small class="fw-bold">Add Hospital</small>
                            </a>
                        </div>
                        <div class="col-4" style="animation-delay:0.3s;">
                            <a href="{{ url('doctor/prescription-invoice/add') }}" class="quick-link">
                                <div class="ql-icon" style="background:linear-gradient(135deg,#4facfe,#00f2fe);">
                                    <i class="fa fa-file-text"></i>
                                </div>
                                <small class="fw-bold">New Invoice</small>
                            </a>
                        </div>
                        <div class="col-4" style="animation-delay:0.4s;">
                            <a href="{{ url('doctor/mydoctor') }}" class="quick-link">
                                <div class="ql-icon" style="background:linear-gradient(135deg,#43e97b,#38f9d7);">
                                    <i class="fa fa-list"></i>
                                </div>
                                <small class="fw-bold">My Doctors</small>
                            </a>
                        </div>
                        <div class="col-4" style="animation-delay:0.5s;">
                            <a href="{{ url('doctor/prescription-invoice') }}" class="quick-link">
                                <div class="ql-icon" style="background:linear-gradient(135deg,#fa709a,#fee140);">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <small class="fw-bold">Appointments</small>
                            </a>
                        </div>
                        <div class="col-4" style="animation-delay:0.6s;">
                            <a href="{{ url('doctor/edit-profile') }}" class="quick-link">
                                <div class="ql-icon" style="background:linear-gradient(135deg,#a18cd1,#fbc2eb);">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                                <small class="fw-bold">My Profile</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Appointments -->
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <h6 class="fw-bold mb-0">Recent Appointments</h6>
                    <a href="{{ url('doctor/prescription-invoice') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 recent-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient</th>
                                <th>Phone</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $recent = \App\Models\PrescriptionInvoice::whereHas('invoiceMaster', function($q) {
                                    $q->where('added_by', Auth::id());
                                })->latest()->take(5)->get();
                            @endphp
                            @forelse($recent as $i => $inv)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><strong>{{ $inv->patient_name }}</strong></td>
                                <td>{{ $inv->patient_phone_no }}</td>
                                <td>{{ $inv->booking_date }}</td>
                                <td>{{ $inv->booking_time }}</td>
                                <td><span class="badge-status" style="background:#e8f4fd;color:#1a73e8;">{{ $inv->invoice_number }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No appointments yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Note Modal -->
<div class="modal fade" id="noteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header" style="background:linear-gradient(135deg,#667eea,#764ba2);border-radius:16px 16px 0 0;">
                <h5 class="modal-title text-white"><i class="fa fa-bell me-2"></i>Important Note</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p><strong>✅ English:</strong><br>
                    If you want to get a doctor's or hospital's profile approved, edit it and copy the URL, then send it via email. - (rogisewa25@gmail.com)
                </p>
                <p><strong>✅ Hindi:</strong><br>
                    अगर आप डॉक्टर या अस्पताल का प्रोफ़ाइल अप्रूव कराना चाहते हैं, तो उसे एडिट कीजिए और यूआरएल कॉपी करके मेल कर दीजिए। - (rogisewa25@gmail.com)
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Animated counter
document.querySelectorAll('.stat-num').forEach(function(el) {
    var target = parseInt(el.getAttribute('data-target')) || 0;
    var duration = 1200;
    var step = target / (duration / 16);
    var current = 0;
    var timer = setInterval(function() {
        current += step;
        if (current >= target) {
            el.textContent = target;
            clearInterval(timer);
        } else {
            el.textContent = Math.floor(current);
        }
    }, 16);
});
</script>

@endsection
