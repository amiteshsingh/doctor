@extends('page.layouts.app')

@section('title', 'Find Hospitals Near You – Book Appointments Online | RogiSewa')
@section('meta_description', 'Search verified hospitals across India by name, specialty, city or state. Find the best hospitals near you on RogiSewa – India\'s trusted healthcare platform.')

@section('content')

<style>
.hosp-card { border:1px solid #e9ecef; border-radius:12px; overflow:hidden; background:#fff; transition:box-shadow .25s, transform .25s; }
.hosp-card:hover { box-shadow:0 8px 28px rgba(13,110,253,.12); transform:translateY(-3px); }
.hosp-card .hosp-img { width:100%; height:170px; object-fit:cover; transition:transform .4s; }
.hosp-card:hover .hosp-img { transform:scale(1.04); }
.spec-badge { font-size:.7rem; background:#e8f4fd; color:#0d6efd; border-radius:20px; padding:2px 9px; display:inline-block; margin:2px 2px 2px 0; }
.sidebar-card { border:1px solid #e9ecef; border-radius:12px; background:#fff; padding:16px; margin-bottom:18px; }
.sidebar-title { font-weight:700; font-size:.85rem; color:#333; border-bottom:2px solid #13C5DD; padding-bottom:6px; margin-bottom:12px; text-transform:uppercase; letter-spacing:.5px; }
.filter-tag { border-radius:20px; font-size:.78rem; padding:4px 12px; margin:3px 2px; border:1px solid #dee2e6; background:#f8f9fa; cursor:pointer; transition:all .2s; display:inline-block; text-decoration:none; color:#444; }
.filter-tag:hover, .filter-tag.active { background:#0d6efd; color:#fff; border-color:#0d6efd; text-decoration:none; }
.ui-autocomplete { z-index:9999 !important; }
</style>

<!-- Page Header -->
<div class="container-fluid py-4" style="background:linear-gradient(135deg,#e8f4fd,#d4f5fb);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-1">Find Hospitals Near You</h1>
                <p class="mb-0 text-muted">Search verified hospitals by name, specialty, city or state across India.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="badge bg-primary fs-6 px-3 py-2">
                    <i class="fa fa-hospital me-1"></i> {{ $hospitals->total() }} Hospitals Listed
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Sticky Search Bar -->
<div class="container-fluid bg-white border-bottom py-3 sticky-top" style="z-index:99; box-shadow:0 2px 8px rgba(0,0,0,.06);">
    <div class="container">
        <form method="GET" action="{{ url('hospitals') }}" class="row g-2 align-items-center" id="search-form">
            <div class="col-6 col-md-3">
                <input type="text" name="name" class="form-control form-control-sm"
                       placeholder="🏥 Hospital Name" value="{{ request('name') }}">
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
                <select name="state" class="form-select form-select-sm">
                    <option value="">🗺️ All States</option>
                    @foreach($states as $state)
                        <option value="{{ $state }}" {{ request('state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-1 d-grid">
                <button class="btn btn-primary btn-sm" type="submit">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
            @if(request()->hasAny(['name','specialization','address','state','zip_code']))
            <div class="col-6 col-md-1 d-grid">
                <a href="{{ url('hospitals') }}" class="btn btn-outline-danger btn-sm">
                    <i class="fa fa-times"></i> Clear
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Main Content -->
<div class="container-fluid py-4">
    <div class="container">
        <div class="row g-4">

            <!-- SIDEBAR -->
            <div class="col-lg-3 d-none d-lg-block">

                <!-- By Specialty -->
                <div class="sidebar-card">
                    <div class="sidebar-title"><i class="fa fa-stethoscope me-2 text-primary"></i>By Specialty</div>
                    @foreach($specializations as $spec)
                        <a href="{{ url('hospitals') }}?specialization={{ urlencode($spec) }}"
                           class="filter-tag {{ request('specialization') == $spec ? 'active' : '' }}">
                            {{ $spec }}
                        </a>
                    @endforeach
                </div>

                <!-- By State -->
                <div class="sidebar-card">
                    <div class="sidebar-title"><i class="fa fa-map-marker-alt me-2 text-primary"></i>By State</div>
                    @foreach($states as $state)
                        <a href="{{ url('hospitals') }}?state={{ urlencode($state) }}"
                           class="filter-tag {{ request('state') == $state ? 'active' : '' }}">
                            {{ $state }}
                        </a>
                    @endforeach
                </div>

                <!-- App Widget -->
                <div class="sidebar-card text-center" style="background:linear-gradient(135deg,#e8f4fd,#d4f5fb);">
                    <i class="fa fa-3x fa-mobile-alt text-primary mb-2 d-block"></i>
                    <div class="fw-bold mb-1">RogiSewa App</div>
                    <p class="small text-muted mb-3">Find hospitals & book appointments from your phone — free Android app.</p>
                    <a href="https://play.google.com/store/apps/details?id=com.rogisewa"
                       target="_blank" rel="noopener" class="btn btn-primary btn-sm w-100">
                        <i class="fab fa-google-play me-1"></i> Download Free
                    </a>
                </div>

            </div>
            <!-- END SIDEBAR -->

            <!-- HOSPITAL LIST -->
            <div class="col-lg-9">

                <!-- Result count -->
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <p class="mb-0 text-muted small">
                        <i class="fa fa-hospital text-primary me-1"></i>
                        Showing <strong>{{ $hospitals->firstItem() }}–{{ $hospitals->lastItem() }}</strong>
                        of <strong>{{ $hospitals->total() }}</strong> hospitals
                        @if(request()->hasAny(['name','specialization','address','state','zip_code']))
                            &nbsp;|&nbsp;
                            <a href="{{ url('hospitals') }}" class="text-danger small">
                                <i class="fa fa-times-circle"></i> Clear Filters
                            </a>
                        @endif
                    </p>
                </div>

                <!-- Hospital Cards -->
                <div id="hospital-list" class="row g-3">
                    @include('page.ajax.hospital_list', ['hospitals' => $hospitals])
                </div>

                <!-- Load More -->
                @if($hospitals->hasMorePages())
                <div class="text-center mt-4" id="load-more-container">
                    <button class="btn btn-outline-primary px-5 py-2" id="load-more-btn"
                            data-next-page="{{ $hospitals->currentPage() + 1 }}">
                        <i class="fa fa-plus-circle me-2"></i> Load More Hospitals
                    </button>
                </div>
                @endif

                <!-- SEO Content -->
                <div class="mt-5 pt-4 border-top">
                    <h2 class="h4 mb-3">Find & Compare Hospitals Across India</h2>
                    <p>RogiSewa lists verified hospitals across India, making it easy to find the right facility for your healthcare needs. Search by hospital name, medical specialty, city, or state to discover hospitals near you.</p>

                    <div class="row g-4 mt-1">
                        <div class="col-md-6">
                            <h3 class="h5">How to Find the Right Hospital</h3>
                            <ul class="ps-3">
                                <li>Search by hospital name or specialty</li>
                                <li>Filter by city, area, or state</li>
                                <li>View hospital details, address, and specializations</li>
                                <li>Book doctor appointments at the hospital</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h3 class="h5">Why Use RogiSewa for Hospitals?</h3>
                            <ul class="ps-3">
                                <li>Verified hospital listings across India</li>
                                <li>Search by specialty or location</li>
                                <li>Find hospitals in your state instantly</li>
                                <li>Free Android app for easy access</li>
                            </ul>
                        </div>
                    </div>

                    <h3 class="h5 mt-3">Hospitals by State</h3>
                    <p>
                        @foreach($states as $state)
                            <a href="{{ url('hospitals') }}?state={{ urlencode($state) }}">{{ $state }}</a>{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </p>

                    <h3 class="h5 mt-3">Hospitals by Specialty</h3>
                    <p>
                        @foreach($specializations->take(10) as $spec)
                            <a href="{{ url('hospitals') }}?specialization={{ urlencode($spec) }}">{{ $spec }}</a>{{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </p>
                </div>

            </div>
            <!-- END LIST -->

        </div>
    </div>
</div>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
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
            $('#search-form').submit();
        }
    });

    $(document).on('click', '#load-more-btn', function() {
        let btn  = $(this);
        let page = btn.data('next-page');
        let url  = "{{ url('hospitals') }}?page=" + page + "&" + $('#search-form').serialize();

        btn.html('<i class="fa fa-spinner fa-spin me-2"></i> Loading...').prop('disabled', true);

        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                $('#hospital-list').append(data);
                btn.data('next-page', page + 1)
                   .html('<i class="fa fa-plus-circle me-2"></i> Load More Hospitals')
                   .prop('disabled', false);

                if (!data.includes('hosp-item-marker')) {
                    $('#load-more-container').remove();
                }
            }
        });
    });
});
</script>

@endsection
