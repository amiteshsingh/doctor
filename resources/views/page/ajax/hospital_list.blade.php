@forelse($hospitals as $hospital)
@php
    $specs      = $hospital->specializations->pluck('specialization.name')->filter();
    $detailUrl  = url('hospital-details/' . $hospital->id . '/' . Str::slug($hospital->name));
    $image      = $hospital->image
                    ? asset('storage/upload/hospital/' . $hospital->image)
                    : asset('img/about.jpg');
@endphp

<div class="col-12 hosp-item-marker">
    <div class="hosp-card">
        <div class="row g-0">

            <!-- Image -->
            <div class="col-md-3 col-sm-4">
                <a href="{{ $detailUrl }}">
                    <img src="{{ $image }}" alt="{{ $hospital->name }}" class="hosp-img w-100 h-100" style="object-fit:cover; min-height:170px;">
                </a>
            </div>

            <!-- Info -->
            <div class="col-md-9 col-sm-8 p-3 d-flex flex-column">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <h2 class="h5 fw-bold mb-1">
                        <a href="{{ $detailUrl }}" class="text-dark text-decoration-none">
                            {{ $hospital->name }}
                        </a>
                    </h2>
                    @if($hospital->visit_count)
                        <span class="badge bg-light text-muted border" style="font-size:.7rem;">
                            <i class="fa fa-eye me-1"></i> {{ number_format($hospital->visit_count) }} views
                        </span>
                    @endif
                </div>

                <!-- Address -->
                <p class="text-muted small mb-2">
                    <i class="fa fa-map-marker-alt text-danger me-1"></i>
                    @if($hospital->address || $hospital->city || $hospital->state)
                        {{ implode(', ', array_filter([$hospital->address, $hospital->city, $hospital->state, $hospital->zip_code])) }}
                    @else
                        Address not available
                    @endif
                </p>

                <!-- Phone -->
                @if(!empty($hospital->phone_no))
                    <p class="text-muted small mb-2">
                        <i class="fa fa-phone text-primary me-1"></i>
                        <a href="tel:{{ $hospital->phone_no }}" class="text-muted text-decoration-none">{{ $hospital->phone_no }}</a>
                    </p>
                @endif

                <!-- Specializations -->
                @if($specs->isNotEmpty())
                    <div class="mb-3 flex-grow-1">
                        @foreach($specs->take(4) as $s)
                            <span class="spec-badge">{{ $s }}</span>
                        @endforeach
                        @if($specs->count() > 4)
                            <span class="spec-badge">+{{ $specs->count() - 4 }} more</span>
                        @endif
                    </div>
                @else
                    <div class="flex-grow-1"></div>
                @endif

                <!-- Actions -->
                <div class="d-flex gap-2 flex-wrap mt-auto">
                    <a href="{{ $detailUrl }}" class="btn btn-primary btn-sm px-3">
                        <i class="fa fa-hospital me-1"></i> View Hospital
                    </a>
                    <!-- <a href="{{ $detailUrl }}" class="btn btn-outline-secondary btn-sm px-3">
                        <i class="fa fa-calendar-check me-1"></i> Book Appointment
                    </a> -->
                    @if(!empty($hospital->phone_no))
                        <a href="tel:{{ $hospital->phone_no }}" class="btn btn-outline-success btn-sm px-3">
                            <i class="fa fa-phone me-1"></i> Call Now
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@empty
<div class="col-12">
    <div class="alert alert-warning text-center py-5">
        <i class="fa fa-3x fa-hospital d-block mb-3 text-muted"></i>
        No hospitals found matching your search.
        <a href="{{ url('hospitals') }}" class="d-block mt-2">Clear filters</a>
    </div>
</div>
@endforelse
