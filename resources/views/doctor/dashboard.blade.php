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
    $totalMedicines = \Illuminate\Support\Facades\DB::table('doctor_medicines')->where('added_by', Auth::id())->count();
    $totalStaff     = \Illuminate\Support\Facades\DB::table('doctor_staff')->where('added_by', Auth::id())->count();
    $recent = \App\Models\PrescriptionInvoice::whereHas('invoiceMaster', function($q) {
        $q->where('added_by', Auth::id());
    })->latest()->take(5)->get();
    $u = Auth::user();
    $dashPic = $u->profile_image
        ? asset('storage/upload/profile_images/' . $u->profile_image)
        : asset('admin/assets/img/user.jpg');

    $today       = \Carbon\Carbon::today();
    $memStatus   = 'none';
    $memExpired  = false;
    if ($membership) {
        $memEnd     = \Carbon\Carbon::parse($membership->membership_subscription_end_date);
        $memStatus  = $memEnd->gte($today) ? 'active' : 'expired';
        $memExpired = $memStatus === 'expired';
    }
@endphp

<style>
@keyframes fadeInUp  { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }
@keyframes fadeInLeft{ from{opacity:0;transform:translateX(-24px)} to{opacity:1;transform:translateX(0)} }
@keyframes float     { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
@keyframes pulse-ring{ 0%,100%{transform:scale(0.85);opacity:.8} 50%{transform:scale(1.1);opacity:.4} }
@keyframes shimmer   { 0%{background-position:-400px 0} 100%{background-position:400px 0} }
@keyframes spin-slow { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
@keyframes bar-grow  { from{width:0} to{width:var(--w)} }

/* Stat Cards */
.stat-card{
    border:none; border-radius:20px; padding:22px; color:#fff;
    position:relative; overflow:hidden;
    animation:fadeInUp .55s ease forwards; opacity:0;
    transition:transform .3s,box-shadow .3s; cursor:pointer;
}
.stat-card:hover{ transform:translateY(-7px); box-shadow:0 22px 45px rgba(0,0,0,.22)!important; }
.stat-card .bg-icon{ position:absolute;right:-12px;bottom:-12px;font-size:90px;opacity:.12; }
.stat-card .stat-num{ font-size:2.4rem;font-weight:800; }
.stat-card .pulse{ width:48px;height:48px;border-radius:50%;background:rgba(255,255,255,.2);
    display:flex;align-items:center;justify-content:center;font-size:20px;
    animation:pulse-ring 2s infinite; }
.c1{ background:linear-gradient(135deg,#667eea,#764ba2); animation-delay:.05s; }
.c2{ background:linear-gradient(135deg,#f093fb,#f5576c); animation-delay:.12s; }
.c3{ background:linear-gradient(135deg,#4facfe,#00f2fe); animation-delay:.19s; }
.c4{ background:linear-gradient(135deg,#43e97b,#38f9d7); animation-delay:.26s; }
.c5{ background:linear-gradient(135deg,#fa709a,#fee140); animation-delay:.33s; }
.c6{ background:linear-gradient(135deg,#a18cd1,#fbc2eb); animation-delay:.40s; }

/* Welcome card */
.welcome-card{
    border:none; border-radius:20px;
    background:linear-gradient(135deg,#0f0c29,#302b63,#24243e);
    color:#fff; padding:28px; position:relative; overflow:hidden;
    animation:fadeInLeft .6s ease .45s forwards; opacity:0;
}
.welcome-card::after{
    content:''; position:absolute; top:-60%;left:-60%;
    width:220%;height:220%;
    background:linear-gradient(45deg,transparent 30%,rgba(255,255,255,.04) 50%,transparent 70%);
    background-size:200% 200%; animation:shimmer 5s infinite;
}
.welcome-card .avatar-wrap img{
    width:72px;height:72px;border-radius:50%;
    border:3px solid rgba(255,255,255,.3);object-fit:cover;
    animation:float 3.5s ease-in-out infinite;
}
.welcome-card .info-pill{
    background:rgba(255,255,255,.12); border-radius:30px;
    padding:4px 14px; font-size:12px; display:inline-block; margin-top:6px;
}

/* Quick links */
.ql-card{
    border:none; border-radius:16px; padding:18px 12px; text-align:center;
    background:#fff; box-shadow:0 4px 18px rgba(0,0,0,.07);
    transition:all .3s; display:block; text-decoration:none; color:#333;
    animation:fadeInUp .5s ease forwards; opacity:0;
}
.ql-card:hover{ transform:translateY(-5px); box-shadow:0 14px 30px rgba(0,0,0,.13); color:#333; text-decoration:none; }
.ql-icon{
    width:52px;height:52px;border-radius:14px;
    display:flex;align-items:center;justify-content:center;
    font-size:22px;color:#fff;margin:0 auto 10px;
}

/* Activity bar */
.act-bar-wrap{ background:#f3f4f6; border-radius:8px; height:8px; overflow:hidden; }
.act-bar{ height:100%; border-radius:8px; animation:bar-grow .9s ease forwards; }

/* Table */
.dash-table thead{ background:linear-gradient(135deg,#667eea,#764ba2); color:#fff; }
.dash-table{ border-radius:16px; overflow:hidden; }
.dash-table tbody tr{ transition:background .2s; }
.dash-table tbody tr:hover{ background:#f8f7ff; }

/* Badge */
.inv-badge{ padding:3px 12px;border-radius:20px;font-size:11px;font-weight:700;
    background:#eef2ff;color:#667eea; }
</style>

<div class="page-wrapper">
<div class="content">

    <!-- Top bar -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap" style="gap:10px;">
        <div>
            <h4 class="mb-0 fw-bold" style="color:#2d3748;">Dashboard</h4>
            <small class="text-muted">Welcome back, <strong>{{ Auth::user()->name }}</strong> 👋 &nbsp;|&nbsp; {{ now()->format('l, d M Y') }}</small>
        </div>
        <button class="btn btn-sm" data-toggle="modal" data-target="#noteModal"
            style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border-radius:10px;border:none;">
            <i class="fa fa-bell mr-1"></i> Important Note
        </button>
    </div>

    <!-- Stat Cards Row 1 -->
    <div class="row mb-3" style="row-gap:16px;">
        <div class="col-xl-2 col-lg-4 col-sm-6">
            <a href="{{ url('doctor/mydoctor') }}" style="text-decoration:none;">
                <div class="stat-card c1 shadow-sm">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1" style="opacity:.85;font-size:12px;">Doctors</p>
                            <div class="stat-num" data-target="{{ $totalDoctors }}">0</div>
                        </div>
                        <div class="pulse"><i class="fa fa-stethoscope"></i></div>
                    </div>
                    <div class="mt-2" style="font-size:11px;opacity:.8;"><i class="fa fa-arrow-right mr-1"></i>View all</div>
                    <i class="fa fa-stethoscope bg-icon"></i>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-sm-6">
            <a href="{{ url('doctor/myhospital') }}" style="text-decoration:none;">
                <div class="stat-card c2 shadow-sm">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1" style="opacity:.85;font-size:12px;">Hospitals</p>
                            <div class="stat-num" data-target="{{ $totalHospitals }}">0</div>
                        </div>
                        <div class="pulse"><i class="fa fa-hospital-o"></i></div>
                    </div>
                    <div class="mt-2" style="font-size:11px;opacity:.8;"><i class="fa fa-arrow-right mr-1"></i>View all</div>
                    <i class="fa fa-hospital-o bg-icon"></i>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-sm-6">
            <a href="{{ url('doctor/prescription-invoice') }}" style="text-decoration:none;">
                <div class="stat-card c3 shadow-sm">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1" style="opacity:.85;font-size:12px;">Appointments</p>
                            <div class="stat-num" data-target="{{ $totalAppointments }}">0</div>
                        </div>
                        <div class="pulse"><i class="fa fa-calendar-check-o"></i></div>
                    </div>
                    <div class="mt-2" style="font-size:11px;opacity:.8;"><i class="fa fa-arrow-right mr-1"></i>View all</div>
                    <i class="fa fa-calendar bg-icon"></i>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-sm-6">
            <a href="{{ url('doctor/prescription-invoice') }}" style="text-decoration:none;">
                <div class="stat-card c4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1" style="opacity:.85;font-size:12px;">Today</p>
                            <div class="stat-num" data-target="{{ $todayAppointments }}">0</div>
                        </div>
                        <div class="pulse"><i class="fa fa-clock-o"></i></div>
                    </div>
                    <div class="mt-2" style="font-size:11px;opacity:.8;"><i class="fa fa-calendar mr-1"></i>{{ today()->format('d M') }}</div>
                    <i class="fa fa-clock-o bg-icon"></i>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-sm-6">
            <a href="{{ url('doctor/medicine') }}" style="text-decoration:none;">
                <div class="stat-card c5 shadow-sm">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1" style="opacity:.85;font-size:12px;">Medicines</p>
                            <div class="stat-num" data-target="{{ $totalMedicines }}">0</div>
                        </div>
                        <div class="pulse"><i class="fa fa-medkit"></i></div>
                    </div>
                    <div class="mt-2" style="font-size:11px;opacity:.8;"><i class="fa fa-arrow-right mr-1"></i>Manage</div>
                    <i class="fa fa-medkit bg-icon"></i>
                </div>
            </a>
        </div>
        <div class="col-xl-2 col-lg-4 col-sm-6">
            <a href="{{ url('doctor/staff') }}" style="text-decoration:none;">
                <div class="stat-card c6 shadow-sm">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1" style="opacity:.85;font-size:12px;">Staff</p>
                            <div class="stat-num" data-target="{{ $totalStaff }}">0</div>
                        </div>
                        <div class="pulse"><i class="fa fa-users"></i></div>
                    </div>
                    <div class="mt-2" style="font-size:11px;opacity:.8;"><i class="fa fa-arrow-right mr-1"></i>Manage</div>
                    <i class="fa fa-users bg-icon"></i>
                </div>
            </a>
        </div>
    </div>

    <!-- Membership Status Card -->
    @if($memStatus === 'none' || $memExpired)
    <div class="row mb-3">
        <div class="col-12">
            <div style="
                background:linear-gradient(135deg,#fff8e6,#fffbf0);
                border:2px solid #fde68a; border-radius:16px;
                padding:18px 24px; display:flex; align-items:center;
                gap:16px; flex-wrap:wrap;
                animation:fadeInUp .5s ease .4s both; opacity:0;
            ">
                <div style="font-size:36px;">{{ $memExpired ? '⚠️' : '🔒' }}</div>
                <div style="flex:1;">
                    <div style="font-size:15px;font-weight:700;color:#1a1a2e;">
                        {{ $memExpired ? 'Membership Expired!' : 'No Active Membership' }}
                    </div>
                    <div style="font-size:13px;color:#666;margin-top:2px;">
                        {{ $memExpired
                            ? 'Your membership expired on ' . \Carbon\Carbon::parse($membership->membership_subscription_end_date)->format('d M Y') . '. Please renew to continue.'
                            : 'You have not purchased a membership yet. Get listed on RogiSewa!' }}
                    </div>
                </div>
                <button data-toggle="modal" data-target="#noteModal"
                    style="background:linear-gradient(135deg,#f59e0b,#fcd34d);color:#fff;border:none;
                           border-radius:10px;padding:9px 20px;font-size:13px;font-weight:700;cursor:pointer;white-space:nowrap;">
                    <i class="fa fa-star mr-1"></i> Get Membership
                </button>
            </div>
        </div>
    </div>
    @endif

    @if($memStatus === 'active')
    <div class="row mb-3">
        <div class="col-12">
            <div style="
                background:linear-gradient(135deg,#e6fff5,#f0fff8);
                border:2px solid #b3f0d8; border-radius:16px;
                padding:18px 24px; display:flex; align-items:center;
                gap:16px; flex-wrap:wrap;
                animation:fadeInUp .5s ease .4s both; opacity:0;
            ">
                <div style="font-size:36px;">✅</div>
                <div style="flex:1;">
                    <div style="font-size:15px;font-weight:700;color:#1a1a2e;">Active Membership</div>
                    <div style="font-size:13px;color:#555;margin-top:2px;">
                        <strong style="color:#00b074;">₹{{ number_format($membership->membership_amount, 2) }}</strong>
                        &nbsp;|&nbsp;
                        <i class="fa fa-calendar"></i>
                        {{ \Carbon\Carbon::parse($membership->membership_subscription_date)->format('d M Y') }}
                        →
                        {{ \Carbon\Carbon::parse($membership->membership_subscription_end_date)->format('d M Y') }}
                        &nbsp;|&nbsp;
                        <strong style="color:#00b074;">
                            {{ \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($membership->membership_subscription_end_date)) }} days left
                        </strong>
                    </div>
                </div>
                <span style="background:#00b074;color:#fff;border-radius:20px;padding:5px 16px;font-size:12px;font-weight:700;">
                    Active
                </span>
            </div>
        </div>
    </div>
    @endif

    <!-- Middle Row -->
    <div class="row mb-4" style="row-gap:16px;">

        <!-- Welcome Card -->
        <div class="col-lg-4">
            <div class="welcome-card h-100">
                <div class="d-flex align-items-center mb-3 avatar-wrap">
                    <img src="{{ $dashPic }}" class="mr-3" alt="{{ Auth::user()->name }}">
                    <div>
                        <h6 class="mb-0 fw-bold">{{ Auth::user()->name }}</h6>
                        <small style="opacity:.7;">{{ Auth::user()->email }}</small>
                        <div class="info-pill"><i class="fa fa-circle text-success mr-1" style="font-size:8px;"></i>Online</div>
                    </div>
                </div>
                <hr style="border-color:rgba(255,255,255,.15);">

                <!-- Activity bars -->
                <p class="mb-2" style="font-size:12px;opacity:.8;">Activity Overview</p>
                @php
                    $maxVal = max($totalDoctors, $totalHospitals, $totalAppointments, 1);
                @endphp
                @foreach([
                    ['label'=>'Doctors',      'val'=>$totalDoctors,      'color'=>'#667eea'],
                    ['label'=>'Hospitals',     'val'=>$totalHospitals,    'color'=>'#f5576c'],
                    ['label'=>'Appointments',  'val'=>$totalAppointments, 'color'=>'#00f2fe'],
                    ['label'=>'Medicines',     'val'=>$totalMedicines,    'color'=>'#fee140'],
                    ['label'=>'Staff',         'val'=>$totalStaff,        'color'=>'#fbc2eb'],
                ] as $bar)
                <div class="mb-2">
                    <div class="d-flex justify-content-between mb-1" style="font-size:11px;opacity:.8;">
                        <span>{{ $bar['label'] }}</span><span>{{ $bar['val'] }}</span>
                    </div>
                    <div class="act-bar-wrap">
                        <div class="act-bar" style="background:{{ $bar['color'] }};--w:{{ $maxVal > 0 ? round($bar['val']/$maxVal*100) : 0 }}%;width:var(--w);"></div>
                    </div>
                </div>
                @endforeach

                <a href="mailto:rogisewa25@gmail.com" class="btn btn-sm mt-3"
                   style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.25);border-radius:10px;">
                    <i class="fa fa-envelope mr-1"></i> rogisewa25@gmail.com
                </a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-8">
            <div class="card border-0 rounded-4 shadow-sm h-100 p-3">
                <h6 class="fw-bold mb-3" style="color:#667eea;"><i class="fa fa-bolt mr-2"></i>Quick Actions</h6>
                <div class="row" style="row-gap:12px;">
                    @foreach([
                        ['url'=>'doctor/mydoctor/add',                'icon'=>'fa-user-plus',         'label'=>'Add Doctor',        'bg'=>'linear-gradient(135deg,#667eea,#764ba2)', 'delay'=>'.05s'],
                        ['url'=>'doctor/myhospital/add',              'icon'=>'fa-plus-square',       'label'=>'Add Hospital',      'bg'=>'linear-gradient(135deg,#f093fb,#f5576c)', 'delay'=>'.10s'],
                        ['url'=>'doctor/prescription-invoice/add',    'icon'=>'fa-file-text',         'label'=>'New Invoice',       'bg'=>'linear-gradient(135deg,#4facfe,#00f2fe)', 'delay'=>'.15s'],
                        ['url'=>'doctor/mydoctor',                    'icon'=>'fa-list',              'label'=>'My Doctors',        'bg'=>'linear-gradient(135deg,#43e97b,#38f9d7)', 'delay'=>'.20s'],
                        ['url'=>'doctor/prescription-invoice',        'icon'=>'fa-calendar',          'label'=>'Appointments',      'bg'=>'linear-gradient(135deg,#fa709a,#fee140)', 'delay'=>'.25s'],
                        ['url'=>'doctor/medicine',                    'icon'=>'fa-medkit',            'label'=>'Medicines',         'bg'=>'linear-gradient(135deg,#f7971e,#ffd200)', 'delay'=>'.30s'],
                        ['url'=>'doctor/staff',                       'icon'=>'fa-users',             'label'=>'Staff',             'bg'=>'linear-gradient(135deg,#a18cd1,#fbc2eb)', 'delay'=>'.35s'],
                        ['url'=>'doctor/staff/attendance',            'icon'=>'fa-calendar-check-o',  'label'=>'Attendance',        'bg'=>'linear-gradient(135deg,#0a6ebd,#00b074)',  'delay'=>'.40s'],
                        ['url'=>'doctor/staff/attendance/report',     'icon'=>'fa-bar-chart',         'label'=>'Att. Report',       'bg'=>'linear-gradient(135deg,#11998e,#38ef7d)',  'delay'=>'.45s'],
                        ['url'=>'doctor/invoice-master',              'icon'=>'fa-cog',               'label'=>'Invoice Settings',  'bg'=>'linear-gradient(135deg,#373b44,#4286f4)',  'delay'=>'.50s'],
                        ['url'=>'doctor/edit-profile',                'icon'=>'fa-user-circle',       'label'=>'My Profile',        'bg'=>'linear-gradient(135deg,#0f2027,#203a43,#2c5364)', 'delay'=>'.55s'],
                    ] as $ql)
                    <div class="col-2 col-sm-2">
                        <a href="{{ url($ql['url']) }}" class="ql-card" style="animation-delay:{{ $ql['delay'] }};">
                            <div class="ql-icon" style="background:{{ $ql['bg'] }};"><i class="fa {{ $ql['icon'] }}"></i></div>
                            <small class="fw-bold" style="font-size:11px;">{{ $ql['label'] }}</small>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="card border-0 rounded-4 shadow-sm mb-4" style="animation:fadeInUp .6s ease .5s forwards;opacity:0;">
        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
            <h6 class="fw-bold mb-0"><i class="fa fa-calendar-check-o mr-2 text-primary"></i>Recent Appointments</h6>
            <a href="{{ url('doctor/prescription-invoice') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">View All</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 dash-table">
                <thead>
                    <tr><th>#</th><th>Patient</th><th>Phone</th><th>Date</th><th>Time</th><th>Invoice</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($recent as $i => $inv)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong>{{ $inv->patient_name }}</strong></td>
                        <td>{{ $inv->patient_phone_no }}</td>
                        <td>{{ $inv->booking_date }}</td>
                        <td>{{ $inv->booking_time }}</td>
                        <td><span class="inv-badge">{{ $inv->invoice_number }}</span></td>
                        <td style="white-space:nowrap;">
                            <a href="{{ url('doctor/prescription-invoice/add?id='.$inv->id) }}"
                               title="Edit" style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:8px;background:#eef2ff;color:#667eea;margin-right:4px;">
                                <i class="fa fa-pencil" style="font-size:12px;"></i>
                            </a>
                            <a href="{{ route('prescription-invoice.pdf', $inv->id) }}"
                               target="_blank" title="Download PDF"
                               style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:8px;background:#fff0f0;color:#e53e3e;">
                                <i class="fa fa-file-pdf-o" style="font-size:12px;"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No appointments yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

<!-- Note Modal -->
<div class="modal fade" id="noteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header" style="background:linear-gradient(135deg,#667eea,#764ba2);border-radius:16px 16px 0 0;">
                <h5 class="modal-title text-white">
                    <i class="fa fa-{{ $memStatus === 'active' ? 'check-circle' : 'star' }} mr-2"></i>
                    {{ $memStatus === 'active' ? 'Important Note' : 'Get Membership' }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">

                @if($memStatus === 'active')
                    {{-- Already has membership --}}
                    <div style="background:#e6fff5;border:1.5px solid #b3f0d8;border-radius:12px;padding:16px 20px;margin-bottom:16px;">
                        <div style="font-size:15px;font-weight:700;color:#00b074;">✅ Your Membership is Active</div>
                        <div style="font-size:13px;color:#555;margin-top:6px;">
                            <strong>Amount:</strong> ₹{{ number_format($membership->membership_amount, 2) }}<br>
                            <strong>Valid:</strong>
                            {{ \Carbon\Carbon::parse($membership->membership_subscription_date)->format('d M Y') }}
                            →
                            {{ \Carbon\Carbon::parse($membership->membership_subscription_end_date)->format('d M Y') }}<br>
                            <strong>Days Left:</strong>
                            {{ \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($membership->membership_subscription_end_date)) }} days
                        </div>
                    </div>
                    <p><strong>✅ English:</strong><br>
                        If you want to get a doctor's or hospital's profile approved, edit it and copy the URL, then send it via email — rogisewa25@gmail.com
                    </p>
                    <p class="mb-0"><strong>✅ Hindi:</strong><br>
                        अगर आप डॉक्टर या अस्पताल का प्रोफ़ाइल अप्रूव कराना चाहते हैं, तो उसे एडिट कीजिए और URL कॉपी करके मेल कर दीजिए — rogisewa25@gmail.com
                    </p>

                @else
                    {{-- No membership / expired --}}
                    @if($memExpired)
                    <div style="background:#fff8e6;border:1.5px solid #fde68a;border-radius:12px;padding:14px 18px;margin-bottom:20px;">
                        <strong style="color:#f59e0b;">⚠️ Your membership expired on {{ \Carbon\Carbon::parse($membership->membership_subscription_end_date)->format('d M Y') }}.</strong><br>
                        <span style="font-size:13px;color:#666;">Please renew to keep your profile active on RogiSewa.</span>
                    </div>
                    @endif

                    <h6 style="font-weight:700;color:#1a1a2e;margin-bottom:16px;">🌟 Membership Plan</h6>

                    <div style="border:2px solid #00b074;border-radius:14px;padding:24px;text-align:center;background:linear-gradient(135deg,#f0fff8,#e6fff5);">
                        <div style="font-size:13px;font-weight:700;color:#00b074;text-transform:uppercase;letter-spacing:1px;">Annual Plan</div>
                        <div style="margin:10px 0 4px;">
                            <span style="font-size:18px;color:#888;text-decoration:line-through;font-weight:700;">
                                ₹2,000
                            </span>

                            <span style="font-size:13px;background:#ff4d4d;color:#fff;padding:4px 10px;border-radius:20px;margin-left:10px;font-weight:700;">
                                50% OFF
                            </span>

                            <div style="font-size:42px;font-weight:800;color:#1a1a2e;margin-top:8px;">
                                ₹1,000
                            </div>
                        </div>

                        <div style="font-size:12px;color:#888;margin-bottom:18px;">per year</div>
                        <ul style="list-style:none;padding:0;margin:0 0 20px;font-size:13px;color:#444;text-align:left;">
                            <li style="padding:6px 0;border-bottom:1px solid #d0f0e0;">✅ Doctor profile listing</li>
                            <li style="padding:6px 0;border-bottom:1px solid #d0f0e0;">✅ Hospital profile listing</li>
                            <li style="padding:6px 0;border-bottom:1px solid #d0f0e0;">✅ Prescription invoices</li>
                            <li style="padding:6px 0;border-bottom:1px solid #d0f0e0;">✅ Priority profile approval</li>
                            <li style="padding:6px 0;">✅ Support via call & email</li>
                        </ul>
                        <a href="https://wa.me/919650657691?text=Hi, I want to buy Annual Membership Plan (Original ₹2000, Offer Price ₹1000 - 50% OFF)"
                           target="_blank"
                           style="display:block;background:linear-gradient(135deg,#00b074,#38f9d7);color:#fff;border-radius:10px;padding:11px;font-weight:700;font-size:13px;text-decoration:none;">
                            <i class="fa fa-whatsapp mr-1"></i> Buy Now — WhatsApp
                        </a>
                    </div>

                    <div style="background:#f8fbff;border-radius:10px;padding:14px 16px;margin-top:18px;font-size:13px;color:#555;">
                        <i class="fa fa-info-circle" style="color:#0a6ebd;"></i>
                        <strong>How to buy?</strong> Call/WhatsApp
                        <a href="tel:+919650657691" style="color:#00b074;font-weight:700;"><i class="fa fa-phone"></i> +91 9650657691</a>
                        &nbsp;or email
                        <a href="mailto:rogisewa25@gmail.com">rogisewa25@gmail.com</a>
                        — We will activate your membership within 24 hours.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                @if($memStatus !== 'active')
                <a href="https://wa.me/919650657691?text=Hi, I want to buy Membership Plan"
                   target="_blank" class="btn btn-success" style="font-weight:700;">
                    <i class="fa fa-whatsapp mr-1"></i> +91 9650657691
                </a>
                <a href="mailto:rogisewa25@gmail.com?subject=Membership Enquiry"
                   class="btn btn-warning" style="font-weight:700;">
                    <i class="fa fa-envelope mr-1"></i> Email Us
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.stat-num').forEach(function(el) {
    var target = parseInt(el.getAttribute('data-target')) || 0;
    var step = target / 60;
    var current = 0;
    var timer = setInterval(function() {
        current += step;
        if (current >= target) { el.textContent = target; clearInterval(timer); }
        else { el.textContent = Math.floor(current); }
    }, 16);
});
</script>

@endsection
