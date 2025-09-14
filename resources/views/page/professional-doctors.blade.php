@extends('page.layouts.app')
@section('title', 'RogiSewa - Professional Doctors')
@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h5 class="text-primary text-uppercase border-bottom border-3 d-inline-block">Professional Doctors</h5>
        <h2 class="fw-bold mt-2">Find Experienced & Verified Doctors</h2>
        <p class="text-muted">Browse through our panel of highly qualified doctors and book your appointment today.</p>
    </div>

    <div class="row g-4">
        @foreach($doctors as $doctor)
            @php
                $practiceName = optional($doctor->locations->first())->practice_name ?? $doctor->name;
                $location = $doctor->locations->first();
            @endphp

            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden doctor-card">
                    <!-- Doctor Image -->
                    <a href="{{ url('doctor-profile/'.$doctor->id.'/'.Str::slug($practiceName)) }}">
                        <img src="{{ $doctor->profile_pic ? asset('uploads/doctor/'.$doctor->profile_pic) : asset('img/default-doctor.jpg') }}"
                             class="card-img-top doctor-img" alt="{{ $doctor->name }}">
                    </a>

                    <!-- Doctor Info -->
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-2">
                            <a href="{{ url('doctor-profile/'.$doctor->id.'/'.Str::slug($practiceName)) }}" class="text-dark text-decoration-none fw-bold">
                                {{ $doctor->name }}
                            </a>
                        </h5>
                        <p class="text-primary fw-semibold mb-1">
                            {{ $doctor->specializations->first()->specialization->name ?? 'General Specialist' }}
                        </p>
                        <p class="text-muted small flex-grow-1">
                            <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                            @if($location)
                                {{ $location->address }},
                                {{ $location->city }},
                                {{ $location->state }} - {{ $location->zip_code }}
                            @else
                                Not Available
                            @endif
                        </p>

                        <!-- View Details Button -->
                        <div class="mt-auto">
                            <a href="{{ url('doctor-profile/'.$doctor->id.'/'.Str::slug($practiceName)) }}" 
                               class="btn btn-outline-primary w-100 rounded-pill">
                                View Details <i class="fas fa-arrow-right ms-1"></i>
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
</div>
@endsection

<style>
.doctor-card {
    transition: all 0.3s ease-in-out;
}
.doctor-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
}
.doctor-img {
    height: 300px;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.doctor-card:hover .doctor-img {
    transform: scale(1.05);
}
</style>