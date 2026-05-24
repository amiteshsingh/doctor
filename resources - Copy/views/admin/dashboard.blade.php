@extends('admin.layout.app')

@section('content')

<style>
@keyframes fadeInUp  { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
@keyframes fadeInLeft{ from{opacity:0;transform:translateX(-20px)} to{opacity:1;transform:translateX(0)} }
@keyframes countUp   { from{opacity:0;transform:scale(.7)} to{opacity:1;transform:scale(1)} }
@keyframes pulse-ring{ 0%,100%{transform:scale(.85);opacity:.8} 50%{transform:scale(1.1);opacity:.3} }
@keyframes barGrow   { from{width:0} to{width:var(--w)} }
@keyframes shimmer   { 0%{background-position:-400px 0} 100%{background-position:400px 0} }

/* ── STAT CARDS ── */
.adm-stat {
    border-radius:18px; padding:22px 20px; color:#fff;
    position:relative; overflow:hidden;
    animation:fadeInUp .5s ease both;
    transition:transform .3s, box-shadow .3s;
    cursor:default;
}
.adm-stat:hover { transform:translateY(-6px); box-shadow:0 20px 40px rgba(0,0,0,.2)!important; }
.adm-stat .bg-ic { position:absolute; right:-14px; bottom:-14px; font-size:88px; opacity:.12; }
.adm-stat .stat-num { font-size:2.2rem; font-weight:800; line-height:1; animation:countUp .6s cubic-bezier(.34,1.56,.64,1) both; }
.adm-stat .stat-lbl { font-size:12px; opacity:.85; margin-top:4px; }
.adm-stat .pulse-ic {
    width:46px; height:46px; border-radius:50%;
    background:rgba(255,255,255,.2);
    display:flex; align-items:center; justify-content:center;
    font-size:18px; animation:pulse-ring 2s infinite;
}
.s1 { background:linear-gradient(135deg,#667eea,#764ba2); animation-delay:.05s; }
.s2 { background:linear-gradient(135deg,#f093fb,#f5576c); animation-delay:.12s; }
.s3 { background:linear-gradient(135deg,#4facfe,#00f2fe); animation-delay:.19s; }
.s4 { background:linear-gradient(135deg,#43e97b,#38f9d7); animation-delay:.26s; }
.s5 { background:linear-gradient(135deg,#fa709a,#fee140); animation-delay:.33s; }

/* ── CARDS ── */
.adm-card {
    background:#fff; border-radius:16px;
    box-shadow:0 4px 20px rgba(0,0,0,.07);
    overflow:hidden; margin-bottom:22px;
    animation:fadeInUp .5s ease both;
}
.adm-card:nth-child(1){animation-delay:.2s}
.adm-card:nth-child(2){animation-delay:.3s}
.adm-card-head {
    background:linear-gradient(135deg,#0f0c29,#302b63);
    padding:14px 20px; color:#fff;
    display:flex; align-items:center; justify-content:space-between;
}
.adm-card-head h6 { margin:0; font-size:14px; font-weight:700; display:flex; align-items:center; gap:8px; }
.adm-card-head a {
    background:rgba(255,255,255,.15); color:#fff; border-radius:8px;
    padding:4px 12px; font-size:12px; text-decoration:none;
    transition:background .2s;
}
.adm-card-head a:hover { background:rgba(255,255,255,.3); color:#fff; }
.adm-card-body { padding:16px 20px; }

/* Doctor row */
.doc-row {
    display:flex; align-items:center; gap:12px;
    padding:10px 0; border-bottom:1px solid #f0f4ff;
    animation:fadeInLeft .4s ease both;
    transition:background .2s;
}
.doc-row:last-child { border-bottom:none; }
.doc-row:hover { background:#f8fbff; border-radius:8px; padding-left:8px; }
.doc-av {
    width:38px; height:38px; border-radius:10px; flex-shrink:0;
    background:linear-gradient(135deg,#667eea,#764ba2);
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-weight:800; font-size:14px;
}
.doc-av img { width:38px; height:38px; border-radius:10px; object-fit:cover; }
.doc-name { font-size:13.5px; font-weight:700; color:#1a1a2e; }
.doc-sub  { font-size:11px; color:#888; }

/* User row */
.usr-row {
    display:flex; align-items:center; gap:12px;
    padding:10px 0; border-bottom:1px solid #f0f4ff;
    animation:fadeInLeft .4s ease both;
    transition:background .2s;
}
.usr-row:last-child { border-bottom:none; }
.usr-row:hover { background:#f8fbff; border-radius:8px; padding-left:8px; }
.usr-av {
    width:36px; height:36px; border-radius:50%; flex-shrink:0;
    background:linear-gradient(135deg,#4facfe,#00f2fe);
    display:flex; align-items:center; justify-content:center;
    color:#fff; font-weight:800; font-size:13px;
}
.usr-name { font-size:13px; font-weight:700; color:#1a1a2e; }
.usr-sub  { font-size:11px; color:#888; }

/* Quick links */
.ql-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
.ql-item {
    background:#f8fbff; border:1.5px solid #e2e8f0;
    border-radius:12px; padding:14px 10px; text-align:center;
    text-decoration:none; color:#333;
    transition:all .25s; animation:fadeInUp .4s ease both;
}
.ql-item:hover { background:#f0f7ff; border-color:#0a6ebd; transform:translateY(-3px); color:#0a6ebd; text-decoration:none; }
.ql-item i { font-size:20px; margin-bottom:6px; display:block; }
.ql-item span { font-size:11px; font-weight:700; }

/* Activity bars */
.act-bar-wrap { background:#f0f4ff; border-radius:6px; height:7px; overflow:hidden; margin-top:4px; }
.act-bar { height:100%; border-radius:6px; animation:barGrow .9s ease forwards; }
</style>

<div class="page-wrapper">
<div class="content">

    {{-- Top bar --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap" style="gap:10px;animation:fadeInUp .4s ease both;">
        <div>
            <h4 class="mb-0" style="font-weight:800;color:#1a1a2e;">Admin Dashboard</h4>
            <small class="text-muted">Welcome back, <strong>{{ Auth::user()->name }}</strong> 👋 &nbsp;|&nbsp; {{ now()->format('l, d M Y') }}</small>
        </div>
        <div style="font-size:12px;color:#888;background:#f0f4ff;padding:6px 14px;border-radius:8px;">
            <i class="fa fa-clock-o mr-1"></i> {{ now()->format('h:i A') }}
        </div>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="row mb-4" style="row-gap:16px;">
        @foreach([
            ['label'=>'Total Doctors',       'val'=>$totalDoctors,       'icon'=>'fa-user-md',    'class'=>'s1', 'link'=>route('admin.doctor'),         'delay'=>'.05s'],
            ['label'=>'Total Hospitals',      'val'=>$totalHospitals,     'icon'=>'fa-hospital-o', 'class'=>'s2', 'link'=>route('admin.hospital'),        'delay'=>'.12s'],
            ['label'=>'Registered Users',     'val'=>$totalUsers,         'icon'=>'fa-users',      'class'=>'s3', 'link'=>route('admin.user'),            'delay'=>'.19s'],
            ['label'=>'Specializations',      'val'=>$totalSpecializations,'icon'=>'fa-stethoscope','class'=>'s4','link'=>route('admin.specialization'),  'delay'=>'.26s'],
            ['label'=>'Active Memberships',   'val'=>$activeMemberships,  'icon'=>'fa-star',       'class'=>'s5', 'link'=>route('admin.user'),            'delay'=>'.33s'],
            ['label'=>'Online Doctors',        'val'=>$onlineDoctors,      'icon'=>'fa-circle',     'class'=>'s1', 'link'=>route('admin.user'),            'delay'=>'.40s'],
        ] as $stat)
        <div class="col-xl col-lg-4 col-sm-6">
            <a href="{{ $stat['link'] }}" style="text-decoration:none;">
                <div class="adm-stat {{ $stat['class'] }} shadow-sm" style="animation-delay:{{ $stat['delay'] }};">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="mb-1" style="opacity:.85;font-size:12px;">{{ $stat['label'] }}</p>
                            <div class="stat-num" data-target="{{ $stat['val'] }}">0</div>
                        </div>
                        <div class="pulse-ic"><i class="fa {{ $stat['icon'] }}"></i></div>
                    </div>
                    <div class="mt-2" style="font-size:11px;opacity:.8;"><i class="fa fa-arrow-right mr-1"></i>View all</div>
                    <i class="fa {{ $stat['icon'] }} bg-ic"></i>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <div class="row" style="row-gap:20px;">

        {{-- ── RECENT DOCTORS ── --}}
        <div class="col-lg-4">
            <div class="adm-card">
                <div class="adm-card-head">
                    <h6><i class="fa fa-user-md"></i> Recent Doctors</h6>
                    <a href="{{ route('admin.doctor') }}">View All</a>
                </div>
                <div class="adm-card-body">
                    @forelse($recentDoctors as $i => $doc)
                    <div class="doc-row" style="animation-delay:{{ $i * 0.07 }}s;">
                        <div class="doc-av">
                            @if($doc->profile_pic)
                                <img src="{{ asset('storage/upload/doctor/'.$doc->profile_pic) }}" alt="">
                            @else
                                {{ strtoupper(substr($doc->name,0,1)) }}
                            @endif
                        </div>
                        <div style="flex:1;">
                            <div class="doc-name">{{ $doc->name }}</div>
                            <div class="doc-sub">{{ $doc->phone_no ?? $doc->email ?? '—' }}</div>
                        </div>
                        <span style="font-size:11px;padding:2px 8px;border-radius:10px;background:{{ $doc->status==1 ? '#e6fff5' : '#fff0f0' }};color:{{ $doc->status==1 ? '#00b074' : '#ef4444' }};font-weight:700;">
                            {{ $doc->status==1 ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3" style="font-size:13px;">No doctors yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ── RECENT USERS ── --}}
        <div class="col-lg-4">
            <div class="adm-card">
                <div class="adm-card-head">
                    <h6><i class="fa fa-users"></i> Recent Users</h6>
                    <a href="{{ route('admin.user') }}">View All</a>
                </div>
                <div class="adm-card-body">
                    @forelse($recentUsers as $i => $usr)
                    <div class="usr-row" style="animation-delay:{{ $i * 0.07 }}s;">
                        <div class="usr-av">{{ strtoupper(substr($usr->name,0,1)) }}</div>
                        <div style="flex:1;">
                            <div class="usr-name">{{ $usr->name }}</div>
                            <div class="usr-sub">{{ $usr->email }}</div>
                        </div>
                        <span style="font-size:11px;color:#888;">{{ \Carbon\Carbon::parse($usr->created_at)->format('d M') }}</span>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3" style="font-size:13px;">No users yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ── ONLINE DOCTORS ── --}}
        <div class="col-lg-4">
            <div class="adm-card">
                <div class="adm-card-head" style="background:linear-gradient(135deg,#00b074,#38f9d7);">
                    <h6>
                        <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#fff;animation:pulse-ring 1.5s infinite;margin-right:4px;"></span>
                        Online Doctors
                    </h6>
                    <span style="background:rgba(255,255,255,.25);border-radius:20px;padding:2px 12px;font-size:12px;font-weight:700;">{{ $onlineDoctors }} Online</span>
                </div>
                <div class="adm-card-body">
                    @forelse($onlineDoctorsList as $i => $doc)
                    <div class="doc-row" style="animation-delay:{{ $i * 0.07 }}s;">
                        <div style="position:relative;">
                            <div class="doc-av">
                                @if($doc->profile_image)
                                    <img src="{{ asset('storage/upload/profile_images/'.$doc->profile_image) }}" alt="">
                                @else
                                    {{ strtoupper(substr($doc->name,0,1)) }}
                                @endif
                            </div>
                            <span style="position:absolute;bottom:-1px;right:-1px;width:11px;height:11px;border-radius:50%;background:#00b074;border:2px solid #fff;"></span>
                        </div>
                        <div style="flex:1;">
                            <div class="doc-name">{{ $doc->name }}</div>
                            <div class="doc-sub">
                                <i class="fa fa-clock-o" style="color:#00b074;"></i>
                                {{ \Carbon\Carbon::parse($doc->last_seen)->diffForHumans() }}
                            </div>
                        </div>
                        <span style="font-size:11px;padding:2px 8px;border-radius:10px;background:#e6fff5;color:#00b074;font-weight:700;">Online</span>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fa fa-circle-o" style="font-size:36px;color:#d0f0e0;display:block;margin-bottom:10px;"></i>
                        <div style="font-size:13px;color:#888;">No doctors online right now</div>
                        <div style="font-size:11px;color:#aaa;margin-top:4px;">Last 5 minutes activity</div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ── RIGHT COLUMN ── --}}
        <div class="col-lg-4">

            {{-- Quick Actions --}}
            <div class="adm-card mb-3">
                <div class="adm-card-head">
                    <h6><i class="fa fa-bolt"></i> Quick Actions</h6>
                </div>
                <div class="adm-card-body">
                    <div class="ql-grid">
                        @foreach([
                            ['url'=>route('admin.doctor.add'),        'icon'=>'fa-user-plus',   'label'=>'Add Doctor',   'color'=>'#667eea', 'delay'=>'.1s'],
                            ['url'=>route('admin.hospital.add'),      'icon'=>'fa-plus-square', 'label'=>'Add Hospital', 'color'=>'#f5576c', 'delay'=>'.15s'],
                            ['url'=>route('admin.specialization.add'),'icon'=>'fa-stethoscope', 'label'=>'Add Spec.',    'color'=>'#4facfe', 'delay'=>'.2s'],
                            ['url'=>route('admin.user.add'),          'icon'=>'fa-user',        'label'=>'Add User',     'color'=>'#43e97b', 'delay'=>'.25s'],
                            ['url'=>route('admin.blog.add'),          'icon'=>'fa-newspaper-o', 'label'=>'Add Blog',     'color'=>'#fa709a', 'delay'=>'.3s'],
                            ['url'=>route('admin.user'),              'icon'=>'fa-star',        'label'=>'Memberships',  'color'=>'#f59e0b', 'delay'=>'.35s'],
                        ] as $ql)
                        <a href="{{ $ql['url'] }}" class="ql-item" style="animation-delay:{{ $ql['delay'] }};">
                            <i class="fa {{ $ql['icon'] }}" style="color:{{ $ql['color'] }};"></i>
                            <span>{{ $ql['label'] }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Overview bars --}}
            <div class="adm-card">
                <div class="adm-card-head">
                    <h6><i class="fa fa-bar-chart"></i> Overview</h6>
                </div>
                <div class="adm-card-body">
                    @php $maxVal = max($totalDoctors, $totalHospitals, $totalUsers, $totalSpecializations, 1); @endphp
                    @foreach([
                        ['label'=>'Doctors',       'val'=>$totalDoctors,        'color'=>'#667eea'],
                        ['label'=>'Hospitals',     'val'=>$totalHospitals,      'color'=>'#f5576c'],
                        ['label'=>'Users',         'val'=>$totalUsers,          'color'=>'#4facfe'],
                        ['label'=>'Specializations','val'=>$totalSpecializations,'color'=>'#43e97b'],
                        ['label'=>'Memberships',   'val'=>$activeMemberships,   'color'=>'#f59e0b'],
                    ] as $bar)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between" style="font-size:12px;color:#555;margin-bottom:3px;">
                            <span>{{ $bar['label'] }}</span><span style="font-weight:700;">{{ $bar['val'] }}</span>
                        </div>
                        <div class="act-bar-wrap">
                            <div class="act-bar" style="background:{{ $bar['color'] }};--w:{{ $maxVal>0 ? round($bar['val']/$maxVal*100) : 0 }}%;width:var(--w);"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

</div>
</div>

<script>
// Counter animation
document.querySelectorAll('.stat-num').forEach(function(el) {
    var target = parseInt(el.getAttribute('data-target')) || 0;
    var step = Math.max(1, Math.ceil(target / 60));
    var current = 0;
    var timer = setInterval(function() {
        current = Math.min(current + step, target);
        el.textContent = current.toLocaleString();
        if (current >= target) clearInterval(timer);
    }, 16);
});
</script>

@endsection
