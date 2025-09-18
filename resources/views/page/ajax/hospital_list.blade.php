@foreach($hospitals as $hospital)
    <div class="col-12">
        <div class="bg-light rounded overflow-hidden p-3 row align-items-center">
            <div class="col-md-3 mb-3 mb-md-0">
                <a href="{{ url('hospital-details/' . $hospital->id . '/' . Str::slug($hospital->name)) }}">
                    <img class="img-fluid rounded w-100"
                         src="{{ asset('storage/upload/hospital/' . ($hospital->image ?? 'default.png')) }}"
                         alt="{{ $hospital->name }}"
                         style="object-fit: cover; height: 160px;">
                </a>
            </div>

            <div class="col-md-9">
                <h3 class="mb-2">
                    <a href="{{ url('hospital-details/' . $hospital->id . '/' . Str::slug($hospital->name)) }}" 
                       class="text-decoration-none text-dark">
                        {{ $hospital->name }}
                    </a>
                </h3>

                <p class="mb-1">
                    <strong>Address:</strong> 
                    @if($hospital->address || $hospital->city || $hospital->state || $hospital->zip_code)
                        {{ $hospital->address ?? '' }}
                        @if(!empty($hospital->city)), {{ $hospital->city }}@endif
                        @if(!empty($hospital->state)), {{ $hospital->state }}@endif
                        @if(!empty($hospital->zip_code)), {{ $hospital->zip_code }}@endif
                    @else
                        N/A
                    @endif
                </p>

                <p class="mb-1">
                    <strong>Specializations:</strong>
                    @if($hospital->specializations->isNotEmpty())
                        {{ $hospital->specializations->pluck('specialization.name')->implode(', ') }}
                    @else
                        N/A
                    @endif
                </p>

                <a href="{{ url('hospital-details/' . $hospital->id . '/' . Str::slug($hospital->name)) }}" 
                   class="btn btn-primary btn-sm mt-2">
                    View Hospital
                </a>
            </div>
        </div>
    </div>
@endforeach
