@forelse($doctors as $index => $doctor)
@php
    $practiceName = optional($doctor->locations->first())->practice_name ?? $doctor->name;
    $location     = $doctor->locations->first();
    $specs        = $doctor->specializations->pluck('specialization.name')->filter();
    $degree       = $doctor->educations->first()->degree ?? null;
    $experience   = !empty($doctor->experience) ? (now()->year - $doctor->experience) : null;
    $profileUrl   = url('doctor-profile/' . $doctor->id . '/' . Str::slug($practiceName));
    $profilePic   = $doctor->profile_pic
                        ? asset('storage/upload/doctor/' . $doctor->profile_pic)
                        : asset('storage/upload/doctor/user.jpg');
@endphp

<div class="col-12 doctor-item-marker mb-3">
    <div class="doctor-card rounded-3 p-3 bg-white">
        <div class="d-flex gap-3 align-items-start">

            <!-- Photo -->
            <a href="{{ $profileUrl }}" class="flex-shrink-0">
                <img src="{{ $profilePic }}" alt="{{ $practiceName }}" class="doctor-photo">
            </a>

            <!-- Info -->
            <div class="flex-grow-1 min-w-0">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h2 class="h5 mb-1">
                            <a href="{{ $profileUrl }}" class="text-decoration-none text-dark">
                                {{ $practiceName }}
                            </a>
                        </h2>

                        @if($degree)
                            <p class="text-muted small mb-1">{{ $degree }}</p>
                        @endif

                        <!-- Specializations -->
                        @if($specs->isNotEmpty())
                            <div class="mb-2">
                                @foreach($specs as $s)
                                    <span class="spec-badge">{{ $s }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Experience Badge -->
                    @if($experience)
                        <span class="badge bg-success bg-opacity-10 border border-success border-opacity-25 px-3 py-2">
                            {{ $experience }}+ Yrs Exp
                        </span>
                    @endif
                </div>

                <!-- Location -->
                <p class="mb-2 small text-muted">
                    <i class="fa fa-map-marker-alt text-danger me-1"></i>
                    @if($location)
                        {{ $location->address }}, {{ $location->city }}, {{ $location->state }} – {{ $location->zip_code }}
                    @else
                        Location not available
                    @endif
                </p>

                <!-- Gender -->
                @if(!empty($doctor->gender))
                    <p class="mb-2 small text-muted">
                        <i class="fa fa-user me-1 text-primary"></i> {{ $doctor->gender }}
                    </p>
                @endif

                <!-- Actions -->
                <div class="d-flex gap-2 flex-wrap mt-2">
                    <a href="{{ $profileUrl }}" class="btn btn-primary btn-sm px-3">
                        <i class="fa fa-calendar-check me-1"></i> Book Appointment
                    </a>
                    <a href="{{ $profileUrl }}" class="btn btn-outline-secondary btn-sm px-3">
                        View Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


@empty
<div class="col-12">
    <div class="alert alert-warning text-center py-4">
        <i class="fa fa-2x fa-user-md mb-2 d-block text-muted"></i>
        No doctors found matching your search. <a href="{{ url('doctors') }}">Clear filters</a> to see all doctors.
    </div>
</div>
@endforelse

@if($doctors->hasMorePages())
    <div id="hasMore"></div>
@endif
