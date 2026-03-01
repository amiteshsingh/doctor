@forelse($doctors as $doctor)
<div class="col-12 doctor-item-marker"> {{-- marker AJAX check ke liye --}}
    <div class="bg-light rounded overflow-hidden p-2 row align-items-center">
        @php
            $practiceName = optional($doctor->locations->first())->practice_name ?? $doctor->name;
        @endphp

        <!-- Doctor Image -->
        <div class="col-md-3 mb-3 mb-md-0">
            <a href="{{ url('doctor-profile/' . $doctor->id . '/' . Str::slug($practiceName)) }}">
                <img class="img-fluid rounded"
                     src="{{ asset('storage/upload/doctor/' . ($doctor->profile_pic ?? 'user.jpg')) }}"
                     style="width: 100%; height: 250px; object-fit: cover;" alt="{{ $doctor->name }}">
            </a>
        </div>

        <!-- Doctor Details -->
        <div class="col-md-9">
            <h3 class="mb-3">
                <a href="{{ url('doctor-profile/' . $doctor->id . '/' . Str::slug($practiceName)) }}"
                   class="text-decoration-none text-dark">
                   {{ $practiceName }}
                </a>
            </h3>

            <!-- Specialization -->
            <p class="mb-1">
                <strong>Specialization:</strong>
                @if($doctor->specializations->isNotEmpty())
                    <span class="text-primary">
                        {!! $doctor->specializations->pluck('specialization.name')
                            ->map(function($name) {
                                return e($name);
                            })
                            ->join('<span class="text-danger">,</span> ') !!}
                    </span>
                @else
                    <span class="text-muted">N/A</span>
                @endif
            </p>


            <!-- Address with Icon -->
            <p class="mb-1">
                <i class="fa fa-map-marker-alt text-danger me-1"></i>
                @if($doctor->locations->isNotEmpty())
                    {{ $doctor->locations->first()->address }},
                    {{ $doctor->locations->first()->city }},
                    {{ $doctor->locations->first()->state }}
                    {{ $doctor->locations->first()->zip_code }}
                @else
                    <span class="text-muted">N/A</span>
                @endif
            </p>

            <!-- Experience -->
            @if(!empty($doctor->experience))
                <p class="mb-3"><strong>Experience:</strong>
                    {{ now()->year - $doctor->experience }}+ Years
                </p>
            @endif

            <!-- View Doctor Button -->
            <a href="{{ url('doctor-profile/' . $doctor->id . '/' . Str::slug($doctor->name)) }}"
               class="btn btn-primary btn-sm mt-2">
               View Doctor
            </a>
        </div>
    </div>
</div>
@empty
    <div class="col-12">
        <div class="alert alert-warning text-center">
            No Doctors Available
        </div>
    </div>
@endforelse

{{-- marker for "has more" check --}}
@if($doctors->hasMorePages())
    <div id="hasMore"></div>
@endif
