@extends('doctor.layouts.app')

@section('content')

@php
    $startYear = $doctor->experience ?? null;
    $expYears  = $startYear ? (date('Y') - $startYear) : null;
    $profilePic = isset($doctor->profile_pic) && $doctor->profile_pic
        ? asset('uploads/doctor/' . $doctor->profile_pic)
        : asset('admin/assets/img/user.jpg');
    $specs = $specializations->pluck('specialization_name')->join(', ') ?: 'N/A';
    $langs = $languages->pluck('language_name')->join(', ') ?: 'N/A';
    $loc   = $locations[0] ?? null;
@endphp

<style>
/* ── PAGE ENTRY ── */
.profile-page { animation: pageFadeIn .5s ease both; }
@keyframes pageFadeIn {
    from { opacity:0; transform:translateY(18px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ── HERO CARD ── */
.prof-hero {
    background: linear-gradient(135deg,#0a6ebd 0%,#00b074 100%);
    border-radius: 16px;
    padding: 36px 32px 28px;
    color: #fff;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    animation: heroSlide .6s cubic-bezier(.25,.46,.45,.94) both;
}
@keyframes heroSlide {
    from { opacity:0; transform:translateX(-40px); }
    to   { opacity:1; transform:translateX(0); }
}
.prof-hero::before {
    content:'';
    position:absolute; top:-60px; right:-60px;
    width:220px; height:220px;
    background:rgba(255,255,255,.08);
    border-radius:50%;
}
.prof-hero::after {
    content:'';
    position:absolute; bottom:-40px; right:80px;
    width:130px; height:130px;
    background:rgba(255,255,255,.05);
    border-radius:50%;
}

/* Avatar */
.prof-avatar-wrap {
    position: relative;
    display: inline-block;
    animation: avatarPop .7s cubic-bezier(.34,1.56,.64,1) .2s both;
}
@keyframes avatarPop {
    from { opacity:0; transform:scale(.4); }
    to   { opacity:1; transform:scale(1); }
}
.prof-avatar {
    width: 110px; height: 110px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(255,255,255,.8);
    box-shadow: 0 8px 24px rgba(0,0,0,.2);
    transition: transform .3s ease, box-shadow .3s ease;
}
.prof-avatar:hover { transform:scale(1.07); box-shadow:0 12px 32px rgba(0,0,0,.3); }
.online-dot {
    position:absolute; bottom:6px; right:6px;
    width:16px; height:16px;
    background:#4cff91; border:3px solid #fff;
    border-radius:50%;
    animation: pulse 1.8s infinite;
}
@keyframes pulse {
    0%,100% { box-shadow:0 0 0 0 rgba(76,255,145,.5); }
    50%      { box-shadow:0 0 0 7px rgba(76,255,145,0); }
}

/* Hero text */
.prof-name {
    font-size: 26px; font-weight: 800;
    margin: 0 0 4px;
    animation: fadeInUp .5s ease .3s both;
}
.prof-spec {
    font-size: 13px; opacity: .85;
    margin-bottom: 12px;
    animation: fadeInUp .5s ease .4s both;
}
.prof-badges { animation: fadeInUp .5s ease .5s both; }
.prof-badge {
    display:inline-block;
    background:rgba(255,255,255,.2);
    border:1px solid rgba(255,255,255,.4);
    border-radius:20px;
    padding:3px 12px;
    font-size:12px;
    margin:2px 3px 2px 0;
    backdrop-filter:blur(4px);
}

/* Stats row */
.prof-stats {
    display:flex; gap:16px; margin-top:20px; flex-wrap:wrap;
    animation: fadeInUp .5s ease .6s both;
}
.stat-box {
    background:rgba(255,255,255,.15);
    border:1px solid rgba(255,255,255,.25);
    border-radius:12px;
    padding:10px 20px;
    text-align:center;
    backdrop-filter:blur(6px);
    transition:background .3s, transform .3s;
    cursor:default;
}
.stat-box:hover { background:rgba(255,255,255,.25); transform:translateY(-3px); }
.stat-num { font-size:22px; font-weight:800; line-height:1; }
.stat-lbl { font-size:11px; opacity:.8; margin-top:2px; }

/* Edit btn */
.prof-edit-btn {
    position:absolute; top:20px; right:20px;
    background:rgba(255,255,255,.2);
    border:1px solid rgba(255,255,255,.5);
    color:#fff; border-radius:8px;
    padding:7px 16px; font-size:13px;
    text-decoration:none;
    transition:background .25s, transform .25s;
    backdrop-filter:blur(4px);
    z-index:1;
}
.prof-edit-btn:hover { background:rgba(255,255,255,.35); transform:scale(1.04); color:#fff; text-decoration:none; }

/* ── INFO CARDS ── */
.info-card {
    background:#fff;
    border-radius:14px;
    padding:22px 24px;
    box-shadow:0 2px 16px rgba(0,0,0,.07);
    margin-bottom:22px;
    animation: cardFadeIn .5s ease both;
    transition:box-shadow .3s, transform .3s;
}
.info-card:hover { box-shadow:0 6px 28px rgba(0,0,0,.12); transform:translateY(-2px); }
@keyframes cardFadeIn {
    from { opacity:0; transform:translateY(20px); }
    to   { opacity:1; transform:translateY(0); }
}
.info-card:nth-child(1) { animation-delay:.2s; }
.info-card:nth-child(2) { animation-delay:.35s; }
.info-card:nth-child(3) { animation-delay:.5s; }

.card-head {
    display:flex; align-items:center; gap:10px;
    font-size:15px; font-weight:700; color:#1a1a2e;
    border-bottom:2px solid #f0f4ff;
    padding-bottom:10px; margin-bottom:16px;
}
.card-head .icon {
    width:34px; height:34px; border-radius:8px;
    display:flex; align-items:center; justify-content:center;
    font-size:15px; color:#fff;
}
.icon-blue  { background:linear-gradient(135deg,#0a6ebd,#4da6ff); }
.icon-green { background:linear-gradient(135deg,#00b074,#4cffb0); }
.icon-purple{ background:linear-gradient(135deg,#7c3aed,#a78bfa); }
.icon-orange{ background:linear-gradient(135deg,#f59e0b,#fcd34d); }

/* Info rows */
.info-row {
    display:flex; align-items:flex-start;
    padding:8px 0; border-bottom:1px solid #f5f5f5;
    font-size:13.5px;
    transition:background .2s;
}
.info-row:last-child { border-bottom:none; }
.info-row:hover { background:#f8fbff; border-radius:6px; padding-left:6px; }
.info-lbl { color:#888; font-weight:600; min-width:130px; }
.info-val { color:#222; flex:1; }

/* ── TABS ── */
.prof-tabs .nav-tabs { border-bottom:2px solid #e8edf5; margin-bottom:0; }
.prof-tabs .nav-link {
    color:#666; font-weight:600; font-size:13px;
    border:none; padding:10px 20px;
    border-radius:0; position:relative;
    transition:color .25s;
}
.prof-tabs .nav-link::after {
    content:''; position:absolute; bottom:-2px; left:0; right:0;
    height:2px; background:linear-gradient(90deg,#0a6ebd,#00b074);
    transform:scaleX(0); transition:transform .3s ease;
}
.prof-tabs .nav-link.active { color:#0a6ebd; }
.prof-tabs .nav-link.active::after { transform:scaleX(1); }
.prof-tabs .tab-content { padding:20px 0; }

/* ── TIMELINE (Education) ── */
.timeline { position:relative; padding-left:28px; }
.timeline::before {
    content:''; position:absolute; left:8px; top:0; bottom:0;
    width:2px; background:linear-gradient(180deg,#0a6ebd,#00b074);
    border-radius:2px;
}
.tl-item {
    position:relative; margin-bottom:22px;
    animation: tlFade .4s ease both;
}
.tl-item:nth-child(1){ animation-delay:.1s; }
.tl-item:nth-child(2){ animation-delay:.2s; }
.tl-item:nth-child(3){ animation-delay:.3s; }
@keyframes tlFade {
    from { opacity:0; transform:translateX(-12px); }
    to   { opacity:1; transform:translateX(0); }
}
.tl-dot {
    position:absolute; left:-24px; top:4px;
    width:14px; height:14px; border-radius:50%;
    background:linear-gradient(135deg,#0a6ebd,#00b074);
    border:2px solid #fff;
    box-shadow:0 0 0 3px rgba(10,110,189,.2);
}
.tl-body {
    background:#f8fbff; border-radius:10px;
    padding:12px 16px; border-left:3px solid #0a6ebd;
    transition:transform .25s, box-shadow .25s;
}
.tl-body:hover { transform:translateX(4px); box-shadow:0 4px 14px rgba(10,110,189,.12); }
.tl-title { font-weight:700; color:#1a1a2e; font-size:14px; }
.tl-sub   { color:#555; font-size:13px; margin:2px 0; }
.tl-year  { font-size:11px; color:#0a6ebd; font-weight:600; }

/* ── AVAILABILITY ── */
.avail-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:12px; }
.avail-card {
    background:linear-gradient(135deg,#f0f7ff,#e8f5f0);
    border:1px solid #d0e8ff; border-radius:12px;
    padding:14px 16px; text-align:center;
    animation: avFade .4s ease both;
    transition:transform .25s, box-shadow .25s;
}
.avail-card:hover { transform:translateY(-4px); box-shadow:0 6px 18px rgba(10,110,189,.15); }
@keyframes avFade {
    from { opacity:0; transform:scale(.9); }
    to   { opacity:1; transform:scale(1); }
}
.avail-day  { font-weight:700; color:#0a6ebd; font-size:14px; margin-bottom:6px; }
.avail-time { font-size:12px; color:#444; background:#fff; border-radius:20px; padding:3px 10px; display:inline-block; }

/* ── STATUS BADGES ── */
.status-pill {
    display:inline-block; border-radius:20px;
    padding:3px 12px; font-size:12px; font-weight:600;
}
.pill-success { background:#e6fff5; color:#00b074; border:1px solid #b3f0d8; }
.pill-warning { background:#fff8e6; color:#f59e0b; border:1px solid #fde68a; }
.pill-danger  { background:#fff0f0; color:#ef4444; border:1px solid #fecaca; }

@keyframes fadeInUp {
    from { opacity:0; transform:translateY(14px); }
    to   { opacity:1; transform:translateY(0); }
}
</style>

<div class="page-wrapper profile-page">
    <div class="content">

        {{-- ── HERO CARD ── --}}
        <div class="prof-hero">
            <a href="{{ url('doctor/mydoctor/add?id='.($doctor->id ?? 0)) }}" class="prof-edit-btn">
                <i class="fa fa-pencil"></i> Edit Profile
            </a>

            <div class="d-flex align-items-center flex-wrap" style="gap:24px;">
                <div class="prof-avatar-wrap">
                    <img src="{{ $profilePic }}" class="prof-avatar" alt="{{ $doctor->name }}">
                    <div class="online-dot"></div>
                </div>
                <div>
                    <h2 class="prof-name">Dr. {{ $doctor->name ?? 'N/A' }}</h2>
                    <p class="prof-spec"><i class="fa fa-stethoscope"></i> {{ $specs }}</p>
                    <div class="prof-badges">
                        @if($expYears)
                            <span class="prof-badge"><i class="fa fa-clock-o"></i> {{ $expYears }} yrs exp</span>
                        @endif
                        @if($loc && $loc->city)
                            <span class="prof-badge"><i class="fa fa-map-marker"></i> {{ $loc->city }}</span>
                        @endif
                        <span class="prof-badge"><i class="fa fa-language"></i> {{ $langs }}</span>
                        <span class="prof-badge" style="background:rgba(76,255,145,.2); border-color:rgba(76,255,145,.5);">
                            <i class="fa fa-check-circle"></i>
                            {{ $doctor->approval_status == 1 ? 'Verified' : 'Pending' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="prof-stats">
                <div class="stat-box">
                    <div class="stat-num">{{ $expYears ?? '—' }}</div>
                    <div class="stat-lbl">Years Exp</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num">{{ $specializations->count() }}</div>
                    <div class="stat-lbl">Specializations</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num">{{ $availability->count() }}</div>
                    <div class="stat-lbl">Available Days</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num">{{ $educations->count() }}</div>
                    <div class="stat-lbl">Qualifications</div>
                </div>
            </div>
        </div>

        <div class="row">

            {{-- ── LEFT COLUMN ── --}}
            <div class="col-md-4">

                {{-- Contact Info --}}
                <div class="info-card">
                    <div class="card-head">
                        <div class="icon icon-blue"><i class="fa fa-phone"></i></div>
                        Contact Info
                    </div>
                    <div class="info-row">
                        <span class="info-lbl"><i class="fa fa-phone"></i> Phone</span>
                        <span class="info-val">
                            <a href="tel:{{ $loc->phone ?? '' }}">{{ $loc->phone ?? 'N/A' }}</a>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-lbl"><i class="fa fa-envelope"></i> Email</span>
                        <span class="info-val" style="word-break:break-all;">
                            <a href="mailto:{{ $doctor->email ?? '' }}">{{ $doctor->email ?? 'N/A' }}</a>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-lbl"><i class="fa fa-map-marker"></i> Address</span>
                        <span class="info-val">
                            @if($loc)
                                {{ $loc->address }}, {{ $loc->city }}, {{ $loc->state }} - {{ $loc->zip_code }}
                            @else N/A @endif
                        </span>
                    </div>
                </div>

                {{-- Practice Info --}}
                <div class="info-card">
                    <div class="card-head">
                        <div class="icon icon-green"><i class="fa fa-hospital-o"></i></div>
                        Practice Info
                    </div>
                    <div class="info-row">
                        <span class="info-lbl">Practice</span>
                        <span class="info-val">{{ $loc->practice_name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-lbl">Status</span>
                        <span class="info-val">
                            <span class="status-pill {{ $doctor->status == 1 ? 'pill-success' : 'pill-warning' }}">
                                {{ $doctor->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-lbl">Approval</span>
                        <span class="info-val">
                            <span class="status-pill {{ $doctor->approval_status == 1 ? 'pill-success' : 'pill-warning' }}">
                                {{ $doctor->approval_status == 1 ? 'Approved' : 'Pending' }}
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-lbl">Professional</span>
                        <span class="info-val">
                            <span class="status-pill {{ $doctor->is_professional ? 'pill-success' : 'pill-warning' }}">
                                {{ $doctor->is_professional ? 'Yes' : 'No' }}
                            </span>
                        </span>
                    </div>
                </div>

            </div>

            {{-- ── RIGHT COLUMN ── --}}
            <div class="col-md-8">
                <div class="info-card">
                    <div class="prof-tabs">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#tab-edu" data-toggle="tab">
                                    <i class="fa fa-graduation-cap"></i> Education
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#tab-avail" data-toggle="tab">
                                    <i class="fa fa-calendar-check-o"></i> Availability
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#tab-info" data-toggle="tab">
                                    <i class="fa fa-info-circle"></i> More Info
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">

                            {{-- Education Tab --}}
                            <div class="tab-pane show active" id="tab-edu">
                                @if($educations->isNotEmpty())
                                    <div class="timeline">
                                        @foreach($educations as $edu)
                                            <div class="tl-item">
                                                <div class="tl-dot"></div>
                                                <div class="tl-body">
                                                    <div class="tl-title">{{ $edu->institution_name ?? 'N/A' }}</div>
                                                    <div class="tl-sub">{{ $edu->degree_type ?? 'N/A' }}</div>
                                                    <div class="tl-year"><i class="fa fa-calendar"></i> {{ $edu->graduation_year ?? 'N/A' }}</div>
                                                    @if(!empty($edu->details))
                                                        <div style="font-size:12px;color:#666;margin-top:4px;">{{ $edu->details }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="fa fa-graduation-cap fa-2x mb-2"></i><br>No education data.
                                    </div>
                                @endif
                            </div>

                            {{-- Availability Tab --}}
                            <div class="tab-pane" id="tab-avail">
                                @if($availability->isNotEmpty())
                                    <div class="avail-grid">
                                        @foreach($availability as $i => $slot)
                                            <div class="avail-card" style="animation-delay:{{ $i * 0.07 }}s">
                                                <div class="avail-day">
                                                    <i class="fa fa-calendar-o"></i> {{ $slot->day ?? 'N/A' }}
                                                </div>
                                                <div class="avail-time">
                                                    {{ $slot->start_time ?? '' }} – {{ $slot->end_time ?? '' }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="fa fa-calendar-times-o fa-2x mb-2"></i><br>No availability set.
                                    </div>
                                @endif
                            </div>

                            {{-- More Info Tab --}}
                            <div class="tab-pane" id="tab-info">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-row">
                                            <span class="info-lbl">Full Name</span>
                                            <span class="info-val">{{ $doctor->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-lbl">Phone</span>
                                            <span class="info-val">{{ $doctor->phone_no ?? 'N/A' }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-lbl">Email</span>
                                            <span class="info-val" style="word-break:break-all;">{{ $doctor->email ?? 'N/A' }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-lbl">Experience</span>
                                            <span class="info-val">{{ $expYears ? $expYears . ' years' : 'N/A' }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-lbl">Languages</span>
                                            <span class="info-val">{{ $langs }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-lbl">Specializations</span>
                                            <span class="info-val">{{ $specs }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-row">
                                            <span class="info-lbl">Practice</span>
                                            <span class="info-val">{{ $loc->practice_name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-lbl">City</span>
                                            <span class="info-val">{{ $loc->city ?? 'N/A' }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-lbl">State</span>
                                            <span class="info-val">{{ $loc->state ?? 'N/A' }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-lbl">Zip Code</span>
                                            <span class="info-val">{{ $loc->zip_code ?? 'N/A' }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-lbl">Latitude</span>
                                            <span class="info-val">{{ $doctor->latitude ?? 'N/A' }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-lbl">Longitude</span>
                                            <span class="info-val">{{ $doctor->longitude ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
