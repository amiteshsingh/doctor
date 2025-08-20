@extends('page.layouts.app')
@section('title', 'MEDINOVA - Doctor List')

@section('content')

    <!-- Doctor Listing Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 600px;">
                <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Doctors</h5>
                <h1 class="display-6">Doctor Listing</h1>
            </div>

            <!-- Advanced Search -->
            <div class="col-12 mb-4">
                <form method="GET" action="{{ url('doctors') }}" class="row g-3">
                    
                    <!-- Doctor Name -->
                    <div class="col-md-3">
                        <input type="text" 
                               name="name" 
                               class="form-control" 
                               placeholder="Search by Name" 
                               value="{{ request('name') }}">
                    </div>

                    <!-- Specialization -->
                    <div class="col-md-3">
                        <input type="text" 
                               name="specialization" 
                               class="form-control" 
                               placeholder="Specialization" 
                               value="{{ request('specialization') }}">
                    </div>

                    <!-- Location -->
                    <div class="col-md-3">
                        <input type="text" 
                               name="address" 
                               class="form-control" 
                               placeholder="Location / Address" 
                               value="{{ request('address') }}">
                    </div>

                    <!-- Experience -->
                     <div class="col-md-2">
                        <select name="min_experience" class="form-control">
                            <option value="">Select Min Experience</option>
                            @for($i = 0; $i <= 40; $i += 5)
                                <option value="{{ $i }}" {{ request('min_experience') == $i ? 'selected' : '' }}>
                                    {{ $i }} Years
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Search Button -->
                    <div class="col-md-1 d-grid">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="row g-4">
                {{-- Doctor Item --}}
                @foreach($doctors as $doctor)
                <div class="col-12">
                    <div class="bg-light rounded overflow-hidden p-2 row align-items-center">
                        
                        <!-- Doctor Image -->
                        <div class="col-md-3 mb-3 mb-md-0">
                            <img class="img-fluid rounded w-100" 
                                 src="{{ asset($doctor['image']) }}" 
                                 style="height:157px; object-fit:cover;" 
                                 alt="{{ $doctor['name'] }}">
                        </div>

                        <!-- Doctor Details -->
                        <div class="col-md-9">
                            <h3 class="mb-3">{{ $doctor['name'] }}</h3>
                            <p class="mb-1"><strong>Specialization:</strong> {{ $doctor['specialization'] }}</p>
                            <p class="mb-1"><strong>Phone:</strong> {{ $doctor['phone'] }}</p>
                            <p class="mb-1"><strong>Address:</strong> {{ $doctor['address'] }}</p>
                            <p class="mb-3"><strong>Experience:</strong> {{ $doctor['experience'] }} Years</p>

                            <!-- View Profile Button -->
                            <a href="{{ url('doctor-profile/'.$doctor['id']) }}" 
                               class="btn btn-primary btn-sm mt-2 me-3">
                                View Profile
                            </a>

                            <!-- Dynamic Social Icons -->
                            <div class="d-inline-block mt-3">
                                @php
                                    $icons = [
                                        "facebook" => "fab fa-facebook-f btn-outline-primary",
                                        "twitter" => "fab fa-twitter btn-outline-info",
                                        "linkedin" => "fab fa-linkedin-in btn-outline-primary",
                                        "instagram" => "fab fa-instagram btn-outline-danger",
                                        "whatsapp" => "fab fa-whatsapp btn-outline-success"
                                    ];
                                @endphp

                                @if(!empty($doctor['social_links']))
                                    @foreach($doctor['social_links'] as $platform => $link)
                                        @if(isset($icons[$platform]))
                                            <a class="btn {{ $icons[$platform] }} btn-sm rounded-circle me-2" 
                                               href="{{ $link }}" target="_blank">
                                                <i class="{{ explode(' ', $icons[$platform])[0] }}"></i>
                                            </a>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Load More --}}
                <div class="col-12 text-center mt-4">
                    <button class="btn btn-primary py-3 px-5">Load More</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Doctor Listing End -->

@endsection
