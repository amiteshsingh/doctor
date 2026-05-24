@extends('page.layouts.app')

@section('title', 'Find Doctors Near You – Book Online Appointments | RogiSewa')
@section('meta_description', 'Search and find qualified doctors near you by specialty, location, or disease. Book doctor appointments online instantly on RogiSewa – India\'s trusted healthcare platform.')

@section('content')

<style>
.doctor-card { transition: box-shadow .2s; border: 1px solid #e9ecef; }
.doctor-card:hover { box-shadow: 0 6px 24px rgba(13,110,253,.13); }
.doctor-photo { width:110px; height:110px; object-fit:cover; border-radius:50%; border:3px solid #e8f4fd; flex-shrink:0; }
.spec-badge { font-size:.75rem; background:#e8f4fd; color:#0d6efd; border-radius:20px; padding:2px 10px; display:inline-block; margin:2px 2px 2px 0; }
.sidebar-card { border:1px solid #e9ecef; border-radius:10px; background:#fff; padding:18px; margin-bottom:20px; }
.sidebar-card h6 { font-weight:700; color:#333; border-bottom:2px solid #13C5DD; padding-bottom:6px; margin-bottom:14px; }
.filter-btn { border-radius:20px; font-size:.82rem; padding:4px 14px; margin:3px 2px; border:1px solid #dee2e6; background:#fff; cursor:pointer; transition:all .2s; }
.filter-btn:hover, .filter-btn.active { background:#0d6efd; color:#fff; border-color:#0d6efd; }
.result-count { font-size:.9rem; color:#666; }
</style>

<!-- Page Header -->
<div class="container-fluid py-4" style="background:linear-gradient(135deg,#e8f4fd,#d4f5fb);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-1">Find Doctors Near You</h1>
                <p class="mb-0 text-muted">Search qualified doctors by specialty, location, or disease. Book appointments online instantly.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="badge bg-primary fs-6 px-3 py-2">
                    <i class="fa fa-user-md me-1"></i> Online Booking Available
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Search Bar -->
<div class="container-fluid bg-white border-bottom py-3 sticky-top" style="z-index:99; box-shadow:0 2px 8px rgba(0,0,0,.06);">
    <div class="container">
        <form method="GET" action="{{ url('doctors') }}" class="row g-2 align-items-center" id="doctorSearchForm">
            <div class="col-6 col-md-3">
                <input type="text" name="name" class="form-control form-control-sm"
                       placeholder="🔍 Doctor Name" value="{{ request('name') }}">
            </div>
            <div class="col-6 col-md-3">
                <input type="text" name="specialization" id="specialization" class="form-control form-control-sm"
                       placeholder="🩺 Specialization" value="{{ request('specialization') }}">
            </div>
            <div class="col-6 col-md-2">
                <input type="text" name="address" class="form-control form-control-sm"
                       placeholder="📍 City / Area" value="{{ request('address') }}">
            </div>
            <div class="col-6 col-md-2">
                <input type="text" name="zip_code" class="form-control form-control-sm"
                       placeholder="📮 PIN Code" value="{{ request('zip_code') }}">
            </div>
            <div class="col-6 col-md-1">
                <select name="gender" class="form-select form-select-sm">
                    <option value="">Gender</option>
                    <option value="Male"   {{ request('gender')=='Male'   ? 'selected':'' }}>Male</option>
                    <option value="Female" {{ request('gender')=='Female' ? 'selected':'' }}>Female</option>
                </select>
            </div>
            <div class="col-6 col-md-1 d-grid">
                <button class="btn btn-primary btn-sm" type="submit">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="container-fluid py-4">
    <div class="container">
        <div class="row g-4">

            <!-- LEFT SIDEBAR -->
            <div class="col-lg-3 d-none d-lg-block">

                <!-- Quick Specialty Filter -->
                <div class="sidebar-card">
                    <h6><i class="fa fa-stethoscope me-2 text-primary"></i>Popular Specialties</h6>
                    @foreach($specializations as $spec)
                        <a href="{{ url('doctors') }}?specialization={{ urlencode($spec) }}"
                           class="filter-btn {{ request('specialization')==$spec ? 'active':'' }}">
                            {{ $spec }}
                        </a>
                    @endforeach
                </div>

                <!-- Quick State Filter -->
                <div class="sidebar-card">
                    <h6><i class="fa fa-map-marker-alt me-2 text-primary"></i>Browse by State</h6>
                    @foreach($states as $state)
                        <a href="{{ url('doctors') }}?address={{ urlencode($state) }}"
                           class="filter-btn {{ request('address')==$state ? 'active':'' }}">
                            {{ $state }}
                        </a>
                    @endforeach
                </div>

                <!-- App Download Widget -->
                <div class="sidebar-card text-center" style="background:linear-gradient(135deg,#e8f4fd,#d4f5fb);">
                    <i class="fa fa-3x fa-mobile-alt text-primary mb-2"></i>
                    <h6 class="mb-1">RogiSewa App</h6>
                    <p class="small text-muted mb-3">Book doctor appointments from your phone — free Android app.</p>
                    <a href="https://play.google.com/store/apps/details?id=com.rogisewa"
                       target="_blank" rel="noopener" class="btn btn-primary btn-sm w-100">
                        <i class="fab fa-google-play me-1"></i> Download Free
                    </a>
                </div>

            </div>
            <!-- END SIDEBAR -->

            <!-- MAIN DOCTOR LIST -->
            <div class="col-lg-9">

                <!-- Result Count + Sort -->
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <p class="result-count mb-0">
                        <i class="fa fa-user-md text-primary me-1"></i>
                        Showing <strong>{{ $doctors->firstItem() }}–{{ $doctors->lastItem() }}</strong>
                        of <strong>{{ $doctors->total() }}</strong> doctors
                        @if(request()->hasAny(['name','specialization','address','zip_code','gender']))
                            &nbsp;|&nbsp;
                            <a href="{{ url('doctors') }}" class="text-danger small">
                                <i class="fa fa-times-circle"></i> Clear Filters
                            </a>
                        @endif
                    </p>
                    @if($userState)
                        <span class="badge bg-success">
                            <i class="fa fa-map-marker-alt me-1"></i> Showing doctors near {{ $userState }}
                        </span>
                    @endif
                </div>

                <!-- Doctor Cards -->
                <div id="doctorList">
                    @include('page.ajax.doctor_list', ['doctors' => $doctors])
                </div>

                <!-- Load More -->
                @if($doctors->hasMorePages())
                <div class="text-center mt-4">
                    <button id="loadMoreBtn" class="btn btn-outline-primary px-5 py-2"
                            data-next-page="{{ $doctors->currentPage() + 1 }}">
                        <i class="fa fa-plus-circle me-2"></i> Load More Doctors
                    </button>
                </div>
                @endif

                <!-- SEO Content Block -->
                <div class="mt-5 pt-4 border-top">
                    <h2 class="h4 mb-3">Find & Book Doctors Online Across India</h2>
                    <p>RogiSewa makes it easy to find qualified doctors near you. Whether you need a general physician for a routine check-up or a specialist for a specific condition, our platform lists thousands of verified doctors across India — searchable by name, specialty, city, or PIN code.</p>

                    <div class="row g-4 mt-1">
                        <div class="col-md-6">
                            <h3 class="h5">How to Book a Doctor Appointment Online</h3>
                            <ol class="ps-3">
                                <li>Search for a doctor by name, specialty, or location</li>
                                <li>View the doctor's profile, qualifications, and clinic address</li>
                                <li>Click "Book Appointment" and select a time slot</li>
                                <li>Confirm your booking — receive instant confirmation</li>
                            </ol>
                        </div>
                        <div class="col-md-6">
                            <h3 class="h5">Why Choose RogiSewa?</h3>
                            <ul class="ps-3">
                                <li>Verified doctor profiles with qualifications</li>
                                <li>Online appointment booking — 24/7</li>
                                <li>Search by specialty, city, or PIN code</li>
                                <li>Free Android app for easy access</li>
                                <li>Covers all major cities across India</li>
                            </ul>
                        </div>
                    </div>

                    <h3 class="h5 mt-3">Popular Doctor Specialties on RogiSewa</h3>
                    <p>Our platform covers a wide range of medical specialties including
                        @foreach($specializations->take(8) as $spec)
                            <a href="{{ url('doctors') }}?specialization={{ urlencode($spec) }}">{{ $spec }}</a>{{ !$loop->last ? ',' : '' }}
                        @endforeach
                        and more. Use the search filters above to find the right specialist for your health needs.
                    </p>

                    <h3 class="h5 mt-3">Find Doctors by State</h3>
                    <p>RogiSewa lists doctors across Indian states including
                        @foreach($states as $state)
                            <a href="{{ url('doctors') }}?address={{ urlencode($state) }}">{{ $state }}</a>{{ !$loop->last ? ',' : '' }}
                        @endforeach.
                        You can also search by city or PIN code to find doctors in your exact locality.
                    </p>
                </div>

            </div>
            <!-- END MAIN -->

        </div>
    </div>
</div>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<style>
/* Fix autocomplete dropdown appearing behind sticky bar */
.ui-autocomplete { z-index: 9999 !important; }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
$(function () {
    $("#specialization").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "{{ route('specializations.suggest') }}",
                data: { term: request.term },
                success: function(data) { response(data); }
            });
        },
        minLength: 1,
        select: function(event, ui) {
            $('#specialization').val(ui.item.value);
            $('#doctorSearchForm').submit();
        }
    });

    $(document).on('click', '#loadMoreBtn', function () {
        let btn  = $(this);
        let page = btn.data('next-page');
        let formData = $('#doctorSearchForm').serialize();

        btn.html('<i class="fa fa-spinner fa-spin me-2"></i> Loading...').prop('disabled', true);

        $.ajax({
            url: "{{ url('doctors') }}?page=" + page + "&" + formData,
            type: 'GET',
            success: function (data) {
                $('#doctorList').append(data);
                btn.data('next-page', page + 1).html('<i class="fa fa-plus-circle me-2"></i> Load More Doctors').prop('disabled', false);
                if (!data.includes('doctor-item-marker')) {
                    btn.closest('div').remove();
                }
            }
        });
    });
});
</script>

@endsection
