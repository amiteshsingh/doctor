@extends('page.layouts.app')

@section('title', 'RogiSewa - ' . $doctor->name ?? 'Doctor Profile')

@section('content')
<div class="container py-5">
    @if($doctor)
        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-4">
                <!-- Profile Card -->
                <div class="card shadow-lg border-0 rounded-4 text-center p-4 bg-light">
                    <img src="{{ asset('storage/upload/doctor/' . ($doctor->profile_pic ?? 'user.jpg')) }}" 
                         class="rounded-circle mx-auto mb-3 border border-4 border-primary" 
                         alt="{{ $doctor->name ?? 'Doctor' }}" 
                         style="width: 160px; height: 160px; object-fit: cover;">
                    
                    <h3 class="fw-bold text-dark">{{ $doctor->name ?? 'N/A' }}</h3>
                    <p class="text-muted mb-2"><i class="fa fa-envelope text-danger me-2"></i>{{ $doctor->email ?? 'Not available' }}</p>
                    <p><i class="fa fa-phone text-success me-2"></i>{{ $doctor->phone_no ?? 'N/A' }}</p>
                    
                    <div class="mt-3">
                        <span class="badge bg-gradient bg-success fs-6 p-2">
                            {{ $doctor->experience ? now()->year - $doctor->experience : 'N/A' }}+ Years Experience
                        </span>
                    </div>
                </div>

                <!-- Specializations -->
                <div class="card shadow mt-4 border-0 rounded-4 p-3 bg-white">
                    <h5 class="text-primary fw-bold border-bottom pb-2">Specializations</h5>
                    <ul class="list-unstyled">
                        @forelse($doctor->specializations as $spec)
                            <li class="mb-1">
                                <i class="fa fa-stethoscope text-danger me-2"></i>{{ $spec->specialization->name ?? 'N/A' }}
                            </li>
                        @empty
                            <li class="text-muted">No specialization added</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Languages -->
                <div class="card shadow mt-4 border-0 rounded-4 p-3 bg-white">
                    <h5 class="text-primary fw-bold border-bottom pb-2">Languages</h5>
                    <div>
                        
                        @forelse($doctor->languages as $lang)
                            <span class="badge bg-info text-dark me-1 mb-1">{{ $lang->language->name ?? 'N/A' }}</span>
                        @empty
                            <p class="text-muted">No languages added</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-8">
                <!-- Practice Locations -->
                <div class="card shadow border-0 rounded-4 p-4 mb-4 bg-white">
                    <h4 class="text-danger fw-bold border-bottom pb-2">📍 Practice Location</h4>
                    @forelse($doctor->locations as $loc)
                        <div class="mb-3 p-3 rounded bg-light">
                            <h6 class="fw-bold text-primary">{{ $loc->practice_name ?? 'N/A' }}</h6>
                            <p class="mb-1">
                                {{ $loc->address ?? '' }}, {{ $loc->city ?? '' }}, 
                                {{ $loc->state ?? '' }} - {{ $loc->zip_code ?? '' }}
                            </p>
                            <p class="mb-1"><i class="fa fa-phone text-success me-2"></i>{{ $loc->phone ?? 'N/A' }}</p>
                            @if($loc->website)
                                <a href="{{ $loc->website }}" target="_blank" class="text-decoration-none text-info">
                                    🌐 {{ $loc->website }}
                                </a>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted">No practice location available</p>
                    @endforelse
                </div>

                <!-- Availability -->
                <div class="card shadow border-0 rounded-4 p-4 mb-4 bg-white">
                    <h4 class="text-warning fw-bold border-bottom pb-2">🕑 Availability</h4>
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Day</th>
                                <th>Start</th>
                                <th>End</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($doctor->availability as $slot)
                                <tr>
                                    <td>{{ $slot->day ?? 'N/A' }}</td>
                                    <td class="text-success">{{ $slot->start_time ?? 'N/A' }}</td>
                                    <td class="text-danger">{{ $slot->end_time ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted text-center">No schedule available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- About Doctor -->
                <div class="card shadow border-0 rounded-4 p-4 mb-4 bg-white">
                    <h4 class="text-success fw-bold border-bottom pb-2">👨‍⚕️ About Doctor</h4>
                    <p class="mb-0">{{ $doctor->educations->first()->details ?? 'No details available' }}</p>
                </div>

                <!-- Education -->
                <div class="card shadow border-0 rounded-4 p-4 mb-4 bg-white">
                    <h4 class="text-info fw-bold border-bottom pb-2">🎓 Education</h4>
                    <ul class="list-unstyled">
                        @forelse($doctor->educations as $edu)
                            <li class="mb-3">
                                <strong class="text-dark">{{ $edu->degree_type ?? 'N/A' }}</strong> 
                                from <span class="text-primary">{{ $edu->institution_name ?? 'N/A' }}</span> 
                                ({{ $edu->graduation_year ?? 'N/A' }})
                            
                            </li>
                        @empty
                            <li class="text-muted">No education info</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    @else
        <p class="text-danger text-center fw-bold">❌ Doctor not found</p>
    @endif
</div>
@endsection
