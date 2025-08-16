@extends('doctor.layouts.app')

@section('content')

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-7 col-6">
                <h4 class="page-title">My Profile</h4>
            </div>
            <div class="col-sm-5 col-6 text-right m-b-30">
                <a href="{{ url('doctor/mydoctor/add?id='.($doctor->id ?? 0)) }}" class="btn btn-primary btn-rounded">
                    <i class="fa fa-plus"></i> Edit Profile
                </a>
            </div>
        </div>

        <div class="card-box profile-header">
            <div class="row">
                <div class="col-md-12">
                    <div class="profile-view">
                        <div class="profile-img-wrap">
                            <div class="profile-img">
                                <a href="#">
                                    <img class="avatar" 
                                         src="{{ isset($doctor->profile_pic) ? asset('uploads/doctor/'.$doctor->profile_pic) : asset('assets/img/default-doctor.jpg') }}" 
                                         alt="">
                                </a>
                            </div>
                        </div>
                        <div class="profile-basic">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="profile-info-left">
                                        <h3 class="user-name m-t-0 mb-0">{{ $doctor->name ?? 'N/A' }}</h3>
                                        <small class="text-muted">
                                            {{ $specializations->pluck('specialization_name')->join(', ') ?? 'N/A' }}
                                        </small>
                                        <div class="staff-msg">
                                            <a href="{{ url('doctor/chat/'.($doctor->id ?? 0)) }}" class="btn btn-primary">View Profile</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <ul class="personal-info">
                                        <li>
                                            <span class="title">Phone:</span>
                                            <span class="text">
                                                <a href="tel:{{ $locations[0]->phone ?? '' }}">
                                                    {{ $locations[0]->phone ?? 'N/A' }}
                                                </a>
                                            </span>
                                        </li>
                                        <li>
                                            <span class="title">Email:</span>
                                            <span class="text">
                                                <a href="mailto:{{ $doctor->email ?? '' }}">
                                                    {{ $doctor->email ?? 'N/A' }}
                                                </a>
                                            </span>
                                        </li>

                                        <li>
                                            <span class="title">Address:</span>
                                            <span class="text">
                                                @if(!empty($locations))
                                                    @foreach($locations as $loc)
                                                        {{ $loc->address ?? '' }}, {{ $loc->city ?? '' }}, {{ $loc->state ?? '' }} - {{ $loc->zip_code ?? '' }}
                                                    @endforeach
                                                @else
                                                    N/A
                                                @endif
                                            </span>
                                        </li>

                                        <li>
                                            <span class="title">Languages:</span>
                                            <span class="text">{{ $languages->pluck('language_name')->join(', ') ?? 'N/A' }}</span>
                                        </li>

                                        <li>
                                            <span class="title">Experience:</span>
                                            <span class="text">
                                                @php
                                                    $startYear = $doctor->experience ?? null;
                                                    $currentYear = date('Y');
                                                    $totalExperience = $startYear ? ($currentYear - $startYear) : null;
                                                @endphp

                                                {{ $totalExperience ? $totalExperience . ' years Experience' : 'N/A' }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>                        
                </div>
            </div>
        </div>

        <div class="profile-tabs">
            <ul class="nav nav-tabs nav-tabs-bottom">
                <li class="nav-item"><a class="nav-link active" href="#about-cont" data-toggle="tab">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#bottom-tab2" data-toggle="tab">Availability</a></li>
                <li class="nav-item"><a class="nav-link" href="#bottom-tab3" data-toggle="tab">Information</a></li>
            </ul>

            <div class="tab-content">
                {{-- Education --}}
                <div class="tab-pane show active" id="about-cont">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-box">
                                <h3 class="card-title">Education</h3>
                                <div class="experience-box">
                                    <ul class="experience-list">
                                        @forelse($educations as $edu)
                                            <li>
                                                <div class="experience-user"><div class="before-circle"></div></div>
                                                <div class="experience-content">
                                                    <div class="timeline-content">
                                                        <a href="#/" class="name">{{ $edu->institution_name ?? 'N/A' }}</a>
                                                        <div>{{ $edu->degree_type ?? 'N/A' }}</div>
                                                        <span class="time">{{ $edu->graduation_year ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                        @empty
                                            <li>No education data.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Availability --}}
                <div class="tab-pane" id="bottom-tab2">
                    @if($availability->isNotEmpty())
                        <div class="list-group">
                            @foreach($availability as $slot)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="font-weight-bold">{{ $slot->day ?? 'N/A' }}</span>
                                    <span class="badge badge-primary badge-pill">
                                        {{ $slot->start_time ?? '' }} - {{ $slot->end_time ?? '' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning text-center">
                            No availability data.
                        </div>
                    @endif
                </div>

                <div class="tab-pane" id="bottom-tab3">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $doctor->name ?? 'N/A' }}</p>
                            <p><strong>Phone:</strong> {{ $doctor->phone_no ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $doctor->email ?? 'N/A' }}</p>
                            <p><strong>Latitude:</strong> {{ $doctor->latitude ?? 'N/A' }}</p>
                            <p><strong>Longitude:</strong> {{ $doctor->longitude ?? 'N/A' }}</p>
                            <p><strong>Hospital ID:</strong> {{ $doctor->hospital_id ?? 'N/A' }}</p>
                            <p>
                                <strong>Experience:</strong>
                                @php
                                    $startYear = $doctor->experience ?? null;
                                    $currentYear = date('Y');
                                    $totalExperience = $startYear ? ($currentYear - $startYear) : null;
                                @endphp
                                {{ $totalExperience ? $totalExperience . ' years' : 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Practice Name:</strong> {{ $locations[0]->practice_name ?? 'N/A' }}</p>
                            <p><strong>Address:</strong> {{ $locations[0]->address ?? 'N/A' }}</p>
                            <p><strong>City:</strong> {{ $locations[0]->city ?? 'N/A' }}</p>
                            <p><strong>State:</strong> {{ $locations[0]->state ?? 'N/A' }}</p>
                            <p><strong>Zip Code:</strong> {{ $locations[0]->zip_code ?? 'N/A' }}</p>
                            <p><strong>Location Phone:</strong> {{ $locations[0]->phone ?? 'N/A' }}</p>
                            <p>
                                <strong>Status:</strong> 
                                <span class="badge badge-{{ $doctor->status == 1 ? 'success' : 'warning' }}">
                                    {{ $doctor->status == 1 ? 'Active' : 'Pending' }}
                                </span>
                            </p>

                            <p>
                                <strong>Approval Status:</strong> 
                                <span class="badge badge-{{ $doctor->approval_status == 1 ? 'success' : 'warning' }}">
                                    {{ $doctor->approval_status == 1 ? 'Approved' : 'Pending' }}
                                </span>
                            </p>

                        </div>
                        
                    </div>
                </div>



            </div>
        </div>
    </div>
</div>

@endsection
