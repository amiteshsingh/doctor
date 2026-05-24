@extends('page.layouts.app')

@section('title', 'Professional Doctors in India – Book Appointments Online | RogiSewa')
@section('meta_description', 'Browse verified professional doctors across India. Filter by specialty or state and book appointments online instantly on RogiSewa.')

@section('content')

<style>
/* Cards */
.doc-card { border:1px solid #e9ecef; border-radius:14px; overflow:hidden; background:#fff; transition:box-shadow .25s, transform .25s; height:100%; display:flex; flex-direction:column; }
.doc-card:hover { box-shadow:0 10px 32px rgba(13,110,253,.13); transform:translateY(-5px); }
.doc-card .doc-img { width:100%; height:190px; object-fit:cover; transition:transform .4s; }
.doc-card:hover .doc-img { transform:scale(1.05); }
.doc-card .card-body { padding:14px; display:flex; flex-direction:column; flex:1; }

/* Badges */
.spec-badge { font-size:.7rem; background:#e8f4fd; color:#0d6efd; border-radius:20px; padding:2px 9px; display:inline-block; margin:2px 2px 2px 0; }
.exp-badge  { font-size:.7rem; background:#e6f9f0; color:#198754; border:1px solid #b2dfdb; border-radius:20px; padding:2px 9px; white-space:nowrap; }
.gender-badge { font-size:.7rem; background:#fff3cd; color:#856404; border-radius:20px; padding:2px 9px; }

/* Sidebar */
.sidebar-card { border:1px solid #e9ecef; border-radius:12px; background:#fff; padding:16px; margin-bottom:18px; }
.sidebar-card .sidebar-title { font-weight:700; font-size:.85rem; color:#333; border-bottom:2px solid #13C5DD; padding-bottom:6px; margin-bottom:12px; text-transform:uppercase; letter-spacing:.5px; }
.filter-tag { border-radius:20px; font-size:.78rem; padding:4px 12px; margin:3px 2px; border:1px solid #dee2e6; background:#f8f9fa; cursor:pointer; transition:all .2s; display:inline-block; text-decoration:none; color:#444; }
.filter-tag:hover { background:#0d6efd; color:#fff; border-color:#0d6efd; text-decoration:none; }
.filter-tag.active { background:#0d6efd; color:#fff; border-color:#0d6efd; }

/* Stats bar */
.stats-bar { background:linear-gradient(135deg,#0d6efd,#13C5DD); color:#fff; border-radius:12px; padding:18px 24px; margin-bottom:20px; }
.stats-bar .stat-item { text-align:center; }
.stats-bar .stat-num { font-size:1.6rem; font-weight:700; line-height:1; }
.stats-bar .stat-label { font-size:.75rem; opacity:.85; }
</style>

<!-- Page Header -->
<div class="container-fluid py-4" style="background:linear-gradient(135deg,#e8f4fd,#d4f5fb);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-1">Professional Doctors in India</h1>
                <p class="mb-0 text-muted">Verified, experienced doctors — search by specialty or state and book online instantly.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ url('doctors') }}" class="btn btn-primary">
                    <i class="fa fa-search me-1"></i> Advanced Search
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filter Bar (mobile-friendly top bar) -->
<div class="container-fluid bg-white border-bottom py-3" style="box-shadow:0 2px 8px rgba(0,0,0,.05);">
    <div class="container">
        <form method="GET" action="{{ route('professional.doctors') }}" class="row g-2 align-items-center" id="filterForm">
            <div class="col-6 col-md-4">
                <select name="specialization" class="form-select form-select-sm">
                    <option value="">🩺 All Specialties</option>
                    @foreach($specializations as $spec)
                        <option value="{{ $spec }}" {{ request('specialization') == $spec ? 'selected' : '' }}>
                            {{ $spec }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="state" class="form-select form-select-sm">
                    <option value="">📍 All States</option>
                    @foreach($states as $state)
                        <option value="{{ $state }}" {{ request('state') == $state ? 'selected' : '' }}>
                            {{ $state }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="gender" class="form-select form-select-sm">
                    <option value="">👤 Any Gender</option>
                    <option value="Male"   {{ request('gender') == 'Male'   ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="col-3 col-md-1 d-grid">
                <button class="btn btn-primary btn-sm" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            @if(request()->hasAny(['specialization','state','gender']))
            <div class="col-3 col-md-1 d-grid">
                <a href="{{ route('professional.doctors') }}" class="btn btn-outline-danger btn-sm">
                    <i class="fa fa-times"></i>
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="container-fluid py-4">
    <div class="container">
        <div class="row g-4">

            <!-- SIDEBAR -->
            <div class="col-lg-3 d-none d-lg-block">

                <!-- Stats -->
                <div class="stats-bar mb-4">
                    <div class="row g-2">
                        <div class="col-6 stat-item border-end border-white border-opacity-25">
                            <div class="stat-num">{{ $doctors->total() }}</div>
                            <div class="stat-label">Doctors</div>
                        </div>
                        <div class="col-6 stat-item">
                            <div class="stat-num">{{ $states->count() }}</div>
                            <div class="stat-label">States</div>
                        </div>
                    </div>
                </div>

                <!-- Specialty Filter -->
                <div class="sidebar-card">
                    <div class="sidebar-title"><i class="fa fa-stethoscope me-2 text-primary"></i>By Specialty</div>
                    @foreach($specializations as $spec)
                        <a href="{{ route('professional.doctors') }}?specialization={{ urlencode($spec) }}"
                           class="filter-tag {{ request('specialization') == $spec ? 'active' : '' }}">
                            {{ $spec }}
                        </a>
                    @endforeach
                </div>

                <!-- State Filter -->
                <div class="sidebar-card">
                    <div class="sidebar-title"><i class="fa fa-map-marker-alt me-2 text-primary"></i>By State</div>
                    @foreach($states as $state)
                        <a href="{{ route('professional.doctors') }}?state={{ urlencode($state) }}"
                           class="filter-tag {{ request('state') == $state ? 'active' : '' }}">
                            {{ $state }}
                        </a>
                    @endforeach
                </div>

                <!-- Gender Filter -->
                <div class="sidebar-card">
                    <div class="sidebar-title"><i class="fa fa-user me-2 text-primary"></i>By Gender</div>
                    <a href="{{ route('professional.doctors') }}?gender=Male"
                       class="filter-tag {{ request('gender') == 'Male' ? 'active' : '' }}">
                        <i class="fa fa-mars me-1"></i> Male Doctors
                    </a>
                    <a href="{{ route('professional.doctors') }}?gender=Female"
                       class="filter-tag {{ request('gender') == 'Female' ? 'active' : '' }}">
                        <i class="fa fa-venus me-1"></i> Female Doctors
                    </a>
                </div>

                <!-- App Widget -->
                <div class="sidebar-card text-center" style="background:linear-gradient(135deg,#e8f4fd,#d4f5fb);">
                    <i class="fa fa-3x fa-mobile-alt text-primary mb-2 d-block"></i>
                    <div class="fw-bold mb-1">RogiSewa App</div>
                    <p class="small text-muted mb-3">Book appointments from your phone — free Android app.</p>
                    <a href="https://play.google.com/store/apps/details?id=com.rogisewa"
                       target="_blank" rel="noopener" class="btn btn-primary btn-sm w-100">
                        <i class="fab fa-google-play me-1"></i> Download Free
                    </a>
                </div>

            </div>
            <!-- END SIDEBAR -->

            <!-- DOCTOR GRID -->
            <div class="col-lg-9">

                <!-- Result info -->
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <p class="mb-0 text-muted small">
                        <i class="fa fa-user-md text-primary me-1"></i>
                        Showing <strong>{{ $doctors->firstItem() }}–{{ $doctors->lastItem() }}</strong>
                        of <strong>{{ $doctors->total() }}</strong> doctors
                        @if(request('specialization'))
                            &nbsp;·&nbsp; <span class="text-primary">{{ request('specialization') }}</span>
                        @endif
                        @if(request('state'))
                            &nbsp;·&nbsp; <span class="text-primary">{{ request('state') }}</span>
                        @endif
                        @if(request()->hasAny(['specialization','state','gender']))
                            &nbsp;
                            <a href="{{ route('professional.doctors') }}" class="text-danger small">
                                <i class="fa fa-times-circle"></i> Clear
                            </a>
                        @endif
                    </p>
                </div>

                <!-- Cards -->
                <div class="row g-3">
                    @forelse($doctors as $doctor)
                        @php
                            $practiceName = optional($doctor->locations->first())->practice_name ?? $doctor->name;
                            $location     = $doctor->locations->first();
                            $specs        = $doctor->specializations->pluck('specialization.name')->filter();
                            $degree       = $doctor->educations->first()->degree ?? null;
                            $experience   = !empty($doctor->experience) ? (now()->year - $doctor->experience) : null;
                            $profileUrl   = url('doctor-profile/'.$doctor->id.'/'.Str::slug($practiceName));
                            $profilePic   = $doctor->profile_pic
                                                ? asset('storage/upload/doctor/'.$doctor->profile_pic)
                                                : asset('storage/upload/doctor/user.jpg');
                        @endphp

                        <div class="col-sm-6 col-xl-4">
                            <div class="doc-card">

                                <!-- Image -->
                                <div class="overflow-hidden position-relative">
                                    <a href="{{ $profileUrl }}">
                                        <img src="{{ $profilePic }}" alt="{{ $practiceName }}" class="doc-img">
                                    </a>
                                    @if($experience)
                                        <span class="exp-badge position-absolute top-0 end-0 m-2">
                                            {{ $experience }}+ Yrs
                                        </span>
                                    @endif
                                    @if(!empty($doctor->gender))
                                        <span class="gender-badge position-absolute top-0 start-0 m-2">
                                            {{ $doctor->gender }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Body -->
                                <div class="card-body">
                                    <h2 class="h6 fw-bold mb-1">
                                        <a href="{{ $profileUrl }}" class="text-dark text-decoration-none">
                                            {{ $practiceName }}
                                        </a>
                                    </h2>

                                    @if($degree)
                                        <p class="text-muted small mb-1" style="font-size:.72rem;">{{ $degree }}</p>
                                    @endif

                                    @if($specs->isNotEmpty())
                                        <div class="mb-2">
                                            @foreach($specs->take(2) as $s)
                                                <span class="spec-badge">{{ $s }}</span>
                                            @endforeach
                                            @if($specs->count() > 2)
                                                <span class="spec-badge">+{{ $specs->count() - 2 }} more</span>
                                            @endif
                                        </div>
                                    @endif

                                    <p class="text-muted mb-3 flex-grow-1" style="font-size:.78rem;">
                                        <i class="fa fa-map-marker-alt text-danger me-1"></i>
                                        @if($location)
                                            {{ $location->city }}@if($location->state), {{ $location->state }}@endif
                                        @else
                                            Location not available
                                        @endif
                                    </p>

                                    <div class="d-grid gap-1 mt-auto">
                                        <a href="{{ $profileUrl }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-calendar-check me-1"></i> Book Appointment
                                        </a>
                                        <a href="{{ $profileUrl }}" class="btn btn-outline-secondary btn-sm">
                                            View Profile
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>

                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning text-center py-5">
                                <i class="fa fa-3x fa-user-md d-block mb-3 text-muted"></i>
                                No doctors found for the selected filters.
                                <a href="{{ route('professional.doctors') }}" class="d-block mt-2">Clear filters</a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-5 d-flex justify-content-center">
                    {{ $doctors->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>

                <!-- SEO Content -->
                <div class="mt-5 pt-4 border-top">
                    <h2 class="h4 mb-3">Verified Professional Doctors on RogiSewa</h2>
                    <p>RogiSewa's professional doctors are verified, experienced healthcare specialists. Each profile includes qualifications, specializations, clinic address, and available appointment slots — so you can make an informed decision before booking.</p>
                    <div class="row g-4 mt-1">
                        <div class="col-md-6">
                            <h3 class="h5">What Makes a Professional Doctor?</h3>
                            <ul class="ps-3">
                                <li>Verified medical qualifications and degrees</li>
                                <li>Listed clinic or hospital address</li>
                                <li>Specialization in one or more medical fields</li>
                                <li>Online appointment booking enabled</li>
                                <li>Approved and active on RogiSewa platform</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h3 class="h5">How to Book an Appointment</h3>
                            <ol class="ps-3">
                                <li>Browse the list and click a doctor profile</li>
                                <li>Review qualifications, specialty, and location</li>
                                <li>Click "Book Appointment" and pick a time slot</li>
                                <li>Fill in patient details and confirm</li>
                                <li>Visit the clinic at the scheduled time</li>
                            </ol>
                        </div>
                    </div>
                </div>

            </div>
            <!-- END GRID -->

        </div>
    </div>
</div>

@endsection
