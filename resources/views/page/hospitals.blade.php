@extends('page.layouts.app')
@section('title', 'MEDINOVA - Hospital List')

@section('content')

    <!-- Hospital Listing Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto mb-3" style="max-width: 600px;">
                <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Hospitals</h5>
                <h1 class="display-6">Hospital Listing</h1>
            </div>

            <!-- Advanced Search -->
            <div class="col-12 mb-4">
                <form method="GET" action="{{ url('hospitals') }}" class="row g-3">

                    <!-- Hospital Name -->
                    <div class="col-md-4">
                        <input type="text" 
                               name="name" 
                               class="form-control" 
                               placeholder="Search by Hospital Name" 
                               value="{{ request('name') }}">
                    </div>

                    <!-- Specialization -->
                    <div class="col-md-4">
                        <input type="text" 
                               name="specialization" 
                               class="form-control" 
                               placeholder="Specialization" 
                               value="{{ request('specialization') }}">
                    </div>

                    <!-- Address -->
                    <div class="col-md-3">
                        <input type="text" 
                               name="address" 
                               class="form-control" 
                               placeholder="Location / Address" 
                               value="{{ request('address') }}">
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
                {{-- Hospital Item --}}
                @foreach($hospitals as $hospital)
                <div class="col-12">
                    <div class="bg-light rounded overflow-hidden p-3 row align-items-center">

                        <!-- Hospital Image -->
                        <div class="col-md-3 mb-3 mb-md-0">
                            <a href="{{ url('hospital-details/' . $hospital->id . '/' . Str::slug($hospital->name)) }}">
                                <img class="img-fluid rounded w-100"
                                     src="{{ asset('uploads/hospital/' . ($hospital->image ?? 'default.png')) }}"
                                     alt="{{ $hospital->name }}"
                                     style="object-fit: cover; height: 160px;">
                            </a>
                        </div>

                        <!-- Hospital Details -->
                        <div class="col-md-9">

                            <h3 class="mb-2">
                                <a href="{{ url('hospital-details/' . $hospital->id . '/' . Str::slug($hospital->name)) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ $hospital->name }}
                                </a>
                            </h3>

                            {{-- ✅ Address + City + State + Zip merged with check --}}
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

                {{-- Load More --}}
                <div class="col-12 text-center mt-4">
                    <button class="btn btn-primary py-3 px-5">Load More</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Hospital Listing End -->

@endsection
