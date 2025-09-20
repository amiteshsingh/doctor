@extends('page.layouts.app')

@section('title', $hospital->name ?? 'Hospital Details')

@section('content')
<div class="container-fluid p-0">
    @if($hospital)
        <!-- Banner Section -->
        <div class="position-relative w-100">
            <!-- Banner Background (Blur + Dark) -->
            <div class="position-absolute top-0 start-0 w-100 h-100" 
                 style="background: url('{{ asset('storage/upload//hospital/' . ($hospital->image ?? 'default.png')) }}') center/cover no-repeat;
                        filter: blur(2px) brightness(0.6);">
            </div>

            <!-- Banner Overlay Content -->
            <div class="position-relative text-center text-white py-5" style="min-height: 350px;">
                <h1 class="fw-bold display-4 text-white">{{ $hospital->name ?? 'Hospital' }}</h1>
                <p class="fs-5 mb-0">{{ $hospital->city ?? '' }} {{ $hospital->state ?? '' }}</p>
            </div>
        </div>

        <!-- Details Section -->
        <div class="container py-5">
            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-lg-4">
                    <!-- Profile Card -->
                    <div class="card shadow-lg border-0 rounded-4 text-center p-4 bg-light">
                        <img src="{{ asset('storage/upload/hospital/' . ($hospital->image ?? 'default.png')) }}" 
                             class="rounded-circle mx-auto mb-3 border border-4 border-primary" 
                             alt="{{ $hospital->name ?? 'Hospital' }}" 
                             style="width: 160px; height: 160px; object-fit: cover;">
                        
                        <h3 class="fw-bold text-dark">{{ $hospital->name ?? 'N/A' }}</h3>
                        <p class="text-muted mb-2">
                            <i class="fa fa-envelope text-danger me-2"></i>{{ $hospital->email ?? 'Not available' }}
                        </p>
                        <p>
                            <i class="fa fa-phone text-success me-2"></i>{{ $hospital->phone_no ?? 'N/A' }}
                        </p>
                        <p class="mt-3">
                            <i class="fa fa-map-marker-alt text-primary me-2"></i>
                            {{ $hospital->address ?? '' }},
                            {{ $hospital->city ?? '' }},
                            {{ $hospital->state ?? '' }} - {{ $hospital->zip_code ?? '' }}
                        </p>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-8">
                    <!-- About Hospital -->
                    <div class="card shadow border-0 rounded-4 p-4 mb-4 bg-white">
                        <h4 class="text-success fw-bold border-bottom pb-2">🏥 About Hospital</h4>
                        <p class="mb-0">{{ $hospital->about_us ?? 'No details available' }}</p>
                    </div>

                    <!-- Specializations -->
                    <div class="card shadow border-0 rounded-4 p-4 bg-white">
                        <h5 class="text-primary fw-bold border-bottom pb-2">Specializations</h5>
                        <ul class="list-unstyled mb-0">
                            @forelse($hospital->specializations as $spec)
                                <li class="mb-2">
                                    <i class="fa fa-stethoscope text-danger me-2"></i>{{ $spec->specialization->name ?? 'N/A' }}
                                </li>
                            @empty
                                <li class="text-muted">No specialization added</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @else
        <p class="text-danger text-center fw-bold">❌ Hospital not found</p>
    @endif
</div>
@endsection
