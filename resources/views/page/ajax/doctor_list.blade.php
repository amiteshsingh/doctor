@foreach($doctors as $doctor)
<div class="col-12 doctor-item-marker"> {{-- marker AJAX check ke liye --}}
    <div class="bg-light rounded overflow-hidden p-2 row align-items-center">
        @php
            $practiceName = optional($doctor->locations->first())->practice_name ?? $doctor->name;
        @endphp

        <!-- Doctor Image -->
        <div class="col-md-3 mb-3 mb-md-0">
            <a href="{{ url('doctor-profile/' . $doctor->id . '/' . Str::slug($practiceName)) }}">
                <img class="img-fluid rounded w-100"
                     src="{{ asset('uploads/doctor/' . ($doctor->profile_pic ?? 'default.jpg')) }}"
                     style="object-fit:cover;" alt="{{ $doctor->name }}">
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

            <p class="mb-1">
                <strong>Specialization:</strong>
                @if($doctor->specializations->isNotEmpty())
                    {{ $doctor->specializations->pluck('specialization.name')->implode(', ') }}
                @else
                    N/A
                @endif
            </p>

            <p class="mb-1">
                <strong>Address:</strong>
                @if($doctor->locations->isNotEmpty())
                    {{ $doctor->locations->first()->address }},
                    {{ $doctor->locations->first()->city }},
                    {{ $doctor->locations->first()->state }}
                    {{ $doctor->locations->first()->zip_code }}
                @else
                    N/A
                @endif
            </p>

            @if(!empty($doctor->experience))
                <p class="mb-3"><strong>Experience:</strong>
                    {{ now()->year - $doctor->experience }}+ Years
                </p>
            @endif

            <a href="{{ url('doctor-profile/' . $doctor->id . '/' . Str::slug($doctor->name)) }}"
               class="btn btn-primary btn-sm mt-2">
               View Doctor
            </a>

        </div>
    </div>
</div>
@endforeach

{{-- marker for "has more" check --}}
@if($doctors->hasMorePages())
    <div id="hasMore"></div>
@endif
