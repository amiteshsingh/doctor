@extends('page.layouts.app')

@section('title', 'Professional Doctors in India – Book Appointments Online | RogiSewa')
@section('meta_description', 'Browse verified professional doctors across India. Find specialists by city or specialty and book appointments online instantly on RogiSewa.')

@section('content')

<style>
.pro-doctor-card { border:1px solid #e9ecef; border-radius:12px; overflow:hidden; transition:box-shadow .25s, transform .25s; background:#fff; }
.pro-doctor-card:hover { box-shadow:0 8px 28px rgba(13,110,253,.13); transform:translateY(-4px); }
.pro-doctor-card .doc-img { width:100%; height:200px; object-fit:cover; transition:transform .4s; }
.pro-doctor-card:hover .doc-img { transform:scale(1.04); }
.spec-badge { font-size:.73rem; background:#e8f4fd; color:#0d6efd; border-radius:20px; padding:2px 10px; display:inline-block; margin:2px 2px 2px 0; }
.sidebar-card { border:1px solid #e9ecef; border-radius:10px; background:#fff; padding:18px; margin-bottom:20px; }
.sidebar-card h6 { font-weight:700; color:#333; border-bottom:2px solid #13C5DD; padding-bottom:6px; margin-bottom:14px; }
.filter-btn { border-radius:20px; font-size:.82rem; padding:4px 14px; margin:3px 2px; border:1px solid #dee2e6; background:#fff; cursor:pointer; transition:all .2s; display:inline-block; text-decoration:none; color:#333; }
.filter-btn:hover, .filter-btn.active { background:#0d6efd; color:#fff; border-color:#0d6efd; }
.exp-badge { font-size:.72rem; background:#e6f9f0; color:#198754; border:1px solid #b2dfdb; border-radius:20px; padding:2px 10px; }
</style>

<!-- Page Header -->
<div class="container-fluid py-4" style="background:linear-gradient(135deg,#e8f4fd,#d4f5fb);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-1">Professional Doctors in India</h1>
                <p class="mb-0 text-muted">Browse verified, experienced doctors and book your appointment online — instantly, 24/7.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="badge bg-primary fs-6 px-3 py-2">
                    <i class="fa fa-user-md me-1"></i> {{ $doctors->total() }} Verified Doctors
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container-fluid py-3">
    <div class="container">
        <div class="row g-4">

            <!-- LEFT SIDEBAR -->
            <div class="col-lg-3 d-none d-lg-block">

                <!-- Specialty Filter -->
                <div class="sidebar-card">
                    <h6><i class="fa fa-stethoscope me-2 text-primary"></i>Browse by Specialty</h6>
                    @foreach(['Cardiologist','Dermatologist','Orthopedic','Gynecologist','Neurologist','Pediatrician','Dentist','ENT Specialist','Psychiatrist','General Physician','Ophthalmologist','Urologist'] as $spec)
                        <a href="{{ url('doctors') }}?specialization={{ urlencode($spec) }}" class="filter-btn">{{ $spec }}</a>
                    @endforeach
                </div>

                <!-- City Filter -->
                <div class="sidebar-card">
                    <h6><i class="fa fa-map-marker-alt me-2 text-primary"></i>Browse by City</h6>
                    @foreach(['Delhi','Mumbai','Bangalore','Chennai','Hyderabad','Kolkata','Pune','Ahmedabad','Jaipur','Lucknow','Surat','Nagpur'] as $city)
                        <a href="{{ url('doctors') }}?address={{ urlencode($city) }}" class="filter-btn">{{ $city }}</a>
                    @endforeach
                </div>

                <!-- App Download Widget -->
                <div class="sidebar-card text-center" style="background:linear-gradient(135deg,#e8f4fd,#d4f5fb);">
                    <i class="fa fa-3x fa-mobile-alt text-primary mb-2"></i>
                    <h6 class="mb-1">RogiSewa App</h6>
                    <p class="small text-muted mb-3">Book doctor appointments from your phone — free Android app.</p>
                    <a href="https://play.google.com/store/apps/details?id=com.rogisewa"
                       target="_blank" rel="noopener" class="btn btn-primary btn-sm w-100">
                        <i class="fab fa-google-play me-1"></i> Download Free
                    </a>
                </div>

            </div>
            <!-- END SIDEBAR -->

            <!-- DOCTOR GRID -->
            <div class="col-lg-9">

                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <p class="mb-0 text-muted small">
                        <i class="fa fa-user-md text-primary me-1"></i>
                        Showing <strong>{{ $doctors->firstItem() }}–{{ $doctors->lastItem() }}</strong>
                        of <strong>{{ $doctors->total() }}</strong> professional doctors
                    </p>
                    <a href="{{ url('doctors') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-search me-1"></i> Advanced Search
                    </a>
                </div>

                <div class="row g-4">
                    @foreach($doctors as $index => $doctor)
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
                            <div class="pro-doctor-card h-100 d-flex flex-column">

                                <!-- Image -->
                                <div class="overflow-hidden" style="border-radius:12px 12px 0 0;">
                                    <a href="{{ $profileUrl }}">
                                        <img src="{{ $profilePic }}" alt="{{ $practiceName }}" class="doc-img">
                                    </a>
                                </div>

                                <!-- Body -->
                                <div class="p-3 d-flex flex-column flex-grow-1">

                                    <!-- Name + Exp -->
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h2 class="h6 mb-0 fw-bold">
                                            <a href="{{ $profileUrl }}" class="text-dark text-decoration-none">{{ $practiceName }}</a>
                                        </h2>
                                        @if($experience)
                                            <span class="exp-badge ms-2 flex-shrink-0">{{ $experience }}+ Yrs</span>
                                        @endif
                                    </div>

                                    <!-- Degree -->
                                    @if($degree)
                                        <p class="text-muted small mb-1">{{ $degree }}</p>
                                    @endif

                                    <!-- Specializations -->
                                    @if($specs->isNotEmpty())
                                        <div class="mb-2">
                                            @foreach($specs->take(3) as $s)
                                                <span class="spec-badge">{{ $s }}</span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Location -->
                                    <p class="text-muted small mb-3 flex-grow-1">
                                        <i class="fa fa-map-marker-alt text-danger me-1"></i>
                                        @if($location)
                                            {{ $location->city }}, {{ $location->state }}
                                        @else
                                            Location not available
                                        @endif
                                    </p>

                                    <!-- Buttons -->
                                    <div class="d-grid gap-2">
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


                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-5 d-flex justify-content-center">
                    {{ $doctors->links('pagination::bootstrap-5') }}
                </div>

                <!-- SEO Content -->
                <div class="mt-5 pt-4 border-top">
                    <h2 class="h4 mb-3">Why Book with a Professional Doctor on RogiSewa?</h2>
                    <p>RogiSewa's professional doctors are verified, experienced healthcare specialists who have been listed on our platform after thorough review. Each doctor profile includes their qualifications, specializations, clinic address, and available appointment slots — so you can make an informed decision before booking.</p>

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
                                <li>Browse the doctor list and click a profile</li>
                                <li>Review qualifications, specialty, and location</li>
                                <li>Click "Book Appointment" and pick a time slot</li>
                                <li>Fill in patient details and confirm</li>
                                <li>Visit the clinic at the scheduled time</li>
                            </ol>
                        </div>
                    </div>

                    <h3 class="h5 mt-2">Find Professional Doctors by Specialty</h3>
                    <p>Our platform covers all major medical specialties. Search for
                        <a href="{{ url('doctors') }}?specialization=Cardiologist">Cardiologists</a>,
                        <a href="{{ url('doctors') }}?specialization=Dermatologist">Dermatologists</a>,
                        <a href="{{ url('doctors') }}?specialization=Orthopedic">Orthopedic Surgeons</a>,
                        <a href="{{ url('doctors') }}?specialization=Gynecologist">Gynecologists</a>,
                        <a href="{{ url('doctors') }}?specialization=Neurologist">Neurologists</a>,
                        <a href="{{ url('doctors') }}?specialization=Pediatrician">Pediatricians</a>,
                        <a href="{{ url('doctors') }}?specialization=Dentist">Dentists</a>,
                        <a href="{{ url('doctors') }}?specialization=Psychiatrist">Psychiatrists</a> and more.
                    </p>

                    <h3 class="h5 mt-3">Find Professional Doctors in Your City</h3>
                    <p>RogiSewa lists professional doctors in all major Indian cities including
                        <a href="{{ url('doctors') }}?address=Delhi">Delhi</a>,
                        <a href="{{ url('doctors') }}?address=Mumbai">Mumbai</a>,
                        <a href="{{ url('doctors') }}?address=Bangalore">Bangalore</a>,
                        <a href="{{ url('doctors') }}?address=Chennai">Chennai</a>,
                        <a href="{{ url('doctors') }}?address=Hyderabad">Hyderabad</a>,
                        <a href="{{ url('doctors') }}?address=Kolkata">Kolkata</a>,
                        <a href="{{ url('doctors') }}?address=Pune">Pune</a>,
                        <a href="{{ url('doctors') }}?address=Jaipur">Jaipur</a>,
                        <a href="{{ url('doctors') }}?address=Lucknow">Lucknow</a> and more.
                    </p>
                </div>

            </div>
            <!-- END DOCTOR GRID -->

        </div>
    </div>
</div>

@endsection
