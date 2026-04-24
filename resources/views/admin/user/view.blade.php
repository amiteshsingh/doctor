@extends('admin.layout.app')

@section('content')

<style>
.uview-page { animation: pgFade .5s ease both; }
@keyframes pgFade { from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }

/* ── TOP BAR ── */
.uview-top {
    display:flex; align-items:center; justify-content:space-between;
    margin-bottom:24px;
}
.uview-top h4 { margin:0; font-size:20px; font-weight:700; color:#1a1a2e; }
.uview-actions { display:flex; gap:8px; }
.btn-uv {
    border-radius:8px; padding:7px 16px; font-size:13px;
    text-decoration:none; transition:all .2s; font-weight:600;
}
.btn-uv-back { background:#f0f4ff; color:#0a6ebd; border:1px solid #d0e4ff; }
.btn-uv-back:hover { background:#dbeafe; color:#0a6ebd; text-decoration:none; }
.btn-uv-edit { background:linear-gradient(135deg,#0a6ebd,#00b074); color:#fff; border:none; }
.btn-uv-edit:hover { opacity:.9; color:#fff; text-decoration:none; }

/* ── PROFILE HERO CARD ── */
.prof-hero-card {
    background:linear-gradient(135deg,#0a6ebd 0%,#00b074 100%);
    border-radius:16px; padding:32px 28px; color:#fff;
    position:relative; overflow:hidden; margin-bottom:24px;
    animation: heroSlide .6s cubic-bezier(.25,.46,.45,.94) both;
}
@keyframes heroSlide { from{opacity:0;transform:translateX(-30px)} to{opacity:1;transform:translateX(0)} }
.prof-hero-card::before {
    content:''; position:absolute; top:-50px; right:-50px;
    width:200px; height:200px; background:rgba(255,255,255,.08);
    border-radius:50%;
}
.prof-hero-wrap { display:flex; align-items:center; gap:24px; position:relative; z-index:1; }
.prof-avatar-big {
    width:100px; height:100px; border-radius:50%; object-fit:cover;
    border:4px solid rgba(255,255,255,.8); box-shadow:0 8px 24px rgba(0,0,0,.2);
    animation: avatarPop .7s cubic-bezier(.34,1.56,.64,1) .2s both;
}
@keyframes avatarPop { from{opacity:0;transform:scale(.5)} to{opacity:1;transform:scale(1)} }
.prof-info { flex:1; }
.prof-name { font-size:24px; font-weight:800; margin:0 0 6px; animation:fadeInUp .5s ease .3s both; }
.prof-meta { font-size:13px; opacity:.88; margin-bottom:10px; animation:fadeInUp .5s ease .4s both; }
.prof-meta i { margin-right:6px; }
.prof-badges { display:flex; gap:8px; flex-wrap:wrap; animation:fadeInUp .5s ease .5s both; }
.prof-badge {
    background:rgba(255,255,255,.2); border:1px solid rgba(255,255,255,.4);
    border-radius:20px; padding:4px 12px; font-size:12px; font-weight:600;
    backdrop-filter:blur(4px);
}
@keyframes fadeInUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }

/* ── INFO CARDS ── */
.info-card {
    background:#fff; border-radius:14px; padding:24px;
    box-shadow:0 2px 16px rgba(0,0,0,.07); margin-bottom:20px;
    animation:cardFade .5s ease both;
    transition:box-shadow .3s, transform .3s;
}
.info-card:hover { box-shadow:0 6px 28px rgba(0,0,0,.12); transform:translateY(-2px); }
@keyframes cardFade { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
.info-card:nth-child(1) { animation-delay:.1s; }
.info-card:nth-child(2) { animation-delay:.25s; }
.info-card:nth-child(3) { animation-delay:.4s; }

.card-hd {
    display:flex; align-items:center; gap:10px;
    font-size:15px; font-weight:700; color:#1a1a2e;
    border-bottom:2px solid #f0f4ff; padding-bottom:10px; margin-bottom:18px;
}
.card-ic {
    width:36px; height:36px; border-radius:9px;
    display:flex; align-items:center; justify-content:center;
    font-size:15px; color:#fff;
}
.ic-blue   { background:linear-gradient(135deg,#0a6ebd,#4da6ff); }
.ic-green  { background:linear-gradient(135deg,#00b074,#4cffb0); }
.ic-gold   { background:linear-gradient(135deg,#f59e0b,#fcd34d); }

.info-row {
    display:flex; padding:9px 0; border-bottom:1px solid #f5f5f5;
    font-size:13.5px; transition:background .2s;
}
.info-row:last-child { border-bottom:none; }
.info-row:hover { background:#f8fbff; border-radius:6px; padding-left:8px; }
.info-lbl { color:#888; font-weight:600; min-width:140px; }
.info-val { color:#222; flex:1; }

/* ── MEMBERSHIP CARD ── */
.mem-card {
    border-radius:14px; padding:22px 24px;
    display:flex; align-items:center; gap:20px;
    animation:memPop .5s cubic-bezier(.34,1.56,.64,1) .2s both;
}
@keyframes memPop { from{opacity:0;transform:scale(.92)} to{opacity:1;transform:scale(1)} }
.mem-card.active-mem  { background:linear-gradient(135deg,#e6fff5,#f0fff8); border:2px solid #b3f0d8; }
.mem-card.expired-mem { background:linear-gradient(135deg,#fff8e6,#fffbf0); border:2px solid #fde68a; }
.mem-card.no-mem      { background:#f8fbff; border:2px dashed #d0e4ff; }
.mem-icon-big { font-size:42px; }
.mem-content { flex:1; }
.mem-title { font-size:16px; font-weight:700; color:#1a1a2e; margin-bottom:4px; }
.mem-sub   { font-size:13px; color:#666; }
.mem-badge-big {
    border-radius:24px; padding:6px 18px; font-size:13px; font-weight:700;
}
.badge-active  { background:#00b074; color:#fff; }
.badge-expired { background:#f59e0b; color:#fff; }
.badge-none    { background:#94a3b8; color:#fff; }

/* ── MEMBERSHIP DETAILS TABLE ── */
.mem-details {
    background:#f8fbff; border-radius:12px; padding:18px 20px;
    margin-top:16px; border:1px solid #e2e8f0;
}
.mem-details .info-row { padding:8px 0; }
.mem-details .info-lbl { color:#0a6ebd; font-weight:700; }
.mem-details .info-val { color:#222; font-weight:600; }
</style>

<div class="page-wrapper uview-page">
<div class="content">

    {{-- Top Bar --}}
    <div class="uview-top">
        <h4>
            <i class="fa fa-user-circle" style="color:#0a6ebd;margin-right:8px;"></i>
            User Profile
        </h4>
        <div class="uview-actions">
            <a href="{{ route('admin.user') }}" class="btn-uv btn-uv-back">
                <i class="fa fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('admin.user.add') }}?id={{ $user->id }}" class="btn-uv btn-uv-edit">
                <i class="fa fa-pencil"></i> Edit
            </a>
        </div>
    </div>

    {{-- Hero Profile Card --}}
    <div class="prof-hero-card">
        <div class="prof-hero-wrap">
            @php
                $pic = $user->profile_image
                    ? asset('storage/upload/profile_images/' . $user->profile_image)
                    : asset('admin/assets/img/user.jpg');
                $role = DB::table('user_roles')->where('user_id', $user->id)->value('role');
            @endphp
            <img src="{{ $pic }}" alt="{{ $user->name }}" class="prof-avatar-big">
            <div class="prof-info">
                <h2 class="prof-name">{{ $user->name }}</h2>
                <div class="prof-meta">
                    <i class="fa fa-envelope"></i> {{ $user->email }}
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <i class="fa fa-phone"></i> {{ $user->phone_no ?: 'N/A' }}
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <i class="fa fa-calendar"></i> Joined {{ $user->created_at->format('d M Y') }}
                </div>
                <div class="prof-badges">
                    <span class="prof-badge">
                        <i class="fa fa-user"></i> {{ ucfirst($role ?? 'user') }}
                    </span>
                    @if($user->gender)
                        <span class="prof-badge"><i class="fa fa-venus-mars"></i> {{ $user->gender }}</span>
                    @endif
                    @if($user->email_verified_at)
                        <span class="prof-badge" style="background:rgba(76,255,145,.3);border-color:rgba(76,255,145,.6);">
                            <i class="fa fa-check-circle"></i> Verified
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- Left Column --}}
        <div class="col-md-6">

            {{-- Personal Info --}}
            <div class="info-card">
                <div class="card-hd">
                    <div class="card-ic ic-blue"><i class="fa fa-user"></i></div>
                    Personal Information
                </div>
                <div class="info-row">
                    <span class="info-lbl">Full Name</span>
                    <span class="info-val">{{ $user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Email</span>
                    <span class="info-val">{{ $user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Phone</span>
                    <span class="info-val">{{ $user->phone_no ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Gender</span>
                    <span class="info-val">{{ $user->gender ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Date of Birth</span>
                    <span class="info-val">{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d M Y') : '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Address</span>
                    <span class="info-val">{{ $user->address ?: '—' }}</span>
                </div>
            </div>

            {{-- Account Info --}}
            <div class="info-card">
                <div class="card-hd">
                    <div class="card-ic ic-green"><i class="fa fa-info-circle"></i></div>
                    Account Information
                </div>
                <div class="info-row">
                    <span class="info-lbl">User ID</span>
                    <span class="info-val">#{{ $user->id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Role</span>
                    <span class="info-val">
                        <span class="badge badge-pill"
                              style="background:{{ $role === 'admin' ? '#ff5b5b' : ($role === 'doctor' ? '#009efb' : '#00b074') }};
                                     color:#fff; padding:4px 12px;">
                            {{ ucfirst($role ?? 'user') }}
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Email Verified</span>
                    <span class="info-val">
                        @if($user->email_verified_at)
                            <span class="text-success"><i class="fa fa-check-circle"></i>
                                {{ \Carbon\Carbon::parse($user->email_verified_at)->format('d M Y') }}
                            </span>
                        @else
                            <span class="text-danger"><i class="fa fa-times-circle"></i> Not Verified</span>
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Registered On</span>
                    <span class="info-val">{{ $user->created_at->format('d M Y, h:i A') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Last Updated</span>
                    <span class="info-val">{{ $user->updated_at->format('d M Y, h:i A') }}</span>
                </div>
            </div>

        </div>

        {{-- Right Column --}}
        <div class="col-md-6">

            {{-- Membership Card --}}
            <div class="info-card">
                <div class="card-hd">
                    <div class="card-ic ic-gold"><i class="fa fa-star"></i></div>
                    Membership Status
                </div>

                @php
                    $mem = $membership ?? null;
                    $today = \Carbon\Carbon::today();
                    $memStatus = 'none';
                    if ($mem) {
                        $endDate = \Carbon\Carbon::parse($mem->membership_subscription_end_date);
                        $memStatus = $endDate->gte($today) ? 'active' : 'expired';
                    }
                @endphp

                <div class="mem-card {{ $memStatus === 'active' ? 'active-mem' : ($memStatus === 'expired' ? 'expired-mem' : 'no-mem') }}">
                    <div class="mem-icon-big">
                        {{ $memStatus === 'active' ? '✅' : ($memStatus === 'expired' ? '⚠️' : '🔒') }}
                    </div>
                    <div class="mem-content">
                        <div class="mem-title">
                            {{ $memStatus === 'active' ? 'Active Membership' : ($memStatus === 'expired' ? 'Membership Expired' : 'No Membership') }}
                        </div>
                        <div class="mem-sub">
                            @if($mem)
                                Subscription is {{ $memStatus === 'active' ? 'currently active' : 'expired' }}
                            @else
                                No membership assigned to this user yet.
                            @endif
                        </div>
                    </div>
                    <span class="mem-badge-big {{ $memStatus === 'active' ? 'badge-active' : ($memStatus === 'expired' ? 'badge-expired' : 'badge-none') }}">
                        {{ ucfirst($memStatus) }}
                    </span>
                </div>

                @if($mem)
                <div class="mem-details">
                    <div class="info-row">
                        <span class="info-lbl"><i class="fa fa-money"></i> Amount</span>
                        <span class="info-val">₹{{ number_format($mem->membership_amount, 2) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-lbl"><i class="fa fa-calendar"></i> Start Date</span>
                        <span class="info-val">{{ \Carbon\Carbon::parse($mem->membership_subscription_date)->format('d M Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-lbl"><i class="fa fa-calendar-check-o"></i> End Date</span>
                        <span class="info-val">{{ \Carbon\Carbon::parse($mem->membership_subscription_end_date)->format('d M Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-lbl"><i class="fa fa-clock-o"></i> Duration</span>
                        <span class="info-val">
                            @php
                                $start = \Carbon\Carbon::parse($mem->membership_subscription_date);
                                $end   = \Carbon\Carbon::parse($mem->membership_subscription_end_date);
                                $days  = $start->diffInDays($end);
                            @endphp
                            {{ $days }} days
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-lbl"><i class="fa fa-refresh"></i> Last Updated</span>
                        <span class="info-val">{{ $mem->updated_at->format('d M Y, h:i A') }}</span>
                    </div>
                </div>
                @endif

            </div>

        </div>

    </div>

</div>
</div>

@endsection
