@extends('page.layouts.app')

@section('title', 'RogiSewa - Home')

@section('content')
    <!-- Hero Start -->
    <div class="container-fluid bg-primary py-5 mb-5 hero-header">
        <div class="container py-5">
            <div class="row justify-content-start">
                <div class="col-lg-8 text-center text-lg-start">
                    <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5" style="border-color: rgba(256, 256, 256, .3) !important;">Welcome To RogiSewa</h5>
                    <h1 class="display-1 text-white mb-md-4">Best Healthcare Solution In Your City</h1>
                    <div class="pt-2">
                        <a href="{{ url('doctors') }}" class="btn btn-light rounded-pill py-md-3 px-md-5 mx-2">Find Doctor</a>
                        <a href="{{ url('hospitals') }}" class="btn btn-outline-light rounded-pill py-md-3 px-md-5 mx-2">Find Hospital</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Team Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 500px;">
                <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Our Doctors</h5>
                <h2 class="display-7">Qualified Healthcare Professionals</h2>
            </div>

            <div class="owl-carousel team-carousel position-relative">
                @foreach($doctors as $doctor)
                    @php
                        $practiceName = optional($doctor->locations->first())->practice_name ?? $doctor->name;
                    @endphp
                    <div class="team-item">
                        <div class="row g-0 bg-light rounded overflow-hidden">
                            <div class="col-12 col-sm-5 h-100">
                                <a href="{{ url('doctor-profile/' . $doctor->id . '/' . Str::slug($practiceName)) }}">
                                <img class="img-fluid h-100" 
                                    src="{{ $doctor->profile_pic ? asset('uploads/doctor/'.$doctor->profile_pic) : asset('img/default-doctor.jpg') }}" 
                                    style="object-fit: cover;">
                                </a>
                            </div>
                            <div class="col-12 col-sm-7 h-100 d-flex flex-column">
                                <div class="mt-auto p-4">
                                    <h3> <a href="{{ url('doctor-profile/' . $doctor->id . '/' . Str::slug($practiceName)) }}">{{ $practiceName }}</a></h3>
                                    <h6 class="fw-normal fst-italic text-primary mb-2">
                                        {{ $doctor->specializations->first()->specialization->name ?? 'General Specialist' }}
                                    </h6>
                                    <p class="mb-2">
                                        {{ $doctor->educations->first()->degree ?? 'Experienced Healthcare Professional' }}
                                    </p>

                                    @php
                                        $location = $doctor->locations->first();
                                    @endphp
                                    @if($location)
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                            {{ $location->address }},
                                            {{ $location->city }},
                                            {{ $location->state }} - {{ $location->zip_code }}
                                        </p>
                                    @else
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                            Address not available
                                        </p>
                                    @endif
                                </div>

                                <div class="d-flex mt-auto border-top p-4">
                                    <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-3" href="{{ $doctor->twitter ?? '#' }}"><i class="fab fa-twitter"></i></a>
                                    <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-3" href="{{ $doctor->facebook ?? '#' }}"><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-lg btn-primary btn-lg-square rounded-circle" href="{{ $doctor->linkedin ?? '#' }}"><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-end mt-3">
                <a href="{{ route('professional.doctors') }}" class="btn btn-light btn-sm">
                    View More <i class="fa fa-arrow-right ms-2"></i>
                </a>
            </div>

        </div>
    </div>
    <!-- Team End -->


    <!-- Search Start -->
    <div class="container-fluid bg-primary my-5 py-5">
        <div class="container py-5">
            <div class="text-center mx-auto mb-5" style="max-width: 500px;">
                <h5 class="d-inline-block text-white text-uppercase border-bottom border-5">Find A Doctor</h5>
                <h1 class="display-4 mb-4">Find A Healthcare Professionals</h1>
                <h5 class="text-white fw-normal">Duo ipsum erat stet dolor sea ut nonumy tempor. Tempor duo lorem eos sit sed ipsum takimata ipsum sit est. Ipsum ea voluptua ipsum sit justo</h5>
            </div>
            <div class="mx-auto" style="width: 100%; max-width: 600px;">
                <div class="input-group">
                    <select class="form-select border-primary w-25" style="height: 60px;">
                        <option selected>Department</option>
                        <option value="1">Department 1</option>
                        <option value="2">Department 2</option>
                        <option value="3">Department 3</option>
                    </select>
                    <input type="text" class="form-control border-primary w-50" placeholder="Keyword">
                    <button class="btn btn-dark border-0 w-25">Search</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Search End -->



    
    @endsection