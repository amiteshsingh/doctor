@extends('page.layouts.app')
@section('title', 'MEDINOVA - Hospital List')

@section('content')
<div class="container-fluid py-5">
    <div class="container">
        <div class="text-center mx-auto mb-3" style="max-width: 600px;">
            <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Hospitals</h5>
            <h1 class="display-6">Hospital Listing</h1>
        </div>

        <!-- Advanced Search -->
        <div class="col-12 mb-4">
            <form method="GET" action="{{ url('hospitals') }}" class="row g-3" id="search-form">

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

        <div class="row g-4" id="hospital-list">
            @include('page.ajax.hospital_list', ['hospitals' => $hospitals])
        </div>

        {{-- Load More --}}
        @if ($hospitals->hasMorePages())
            <div class="col-12 text-center mt-4" id="load-more-container">
                <button class="btn btn-primary py-3 px-5" id="load-more-btn" 
                        data-next-page="{{ $hospitals->currentPage() + 1 }}">
                    Load More
                </button>
            </div>
        @endif
    </div>
</div>

<!-- jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).on('click', '#load-more-btn', function() {
    let page = $(this).data('next-page');
    let url = "{{ url('hospitals') }}?page=" + page + "&" + $('#search-form').serialize();

    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function() {
            $('#load-more-btn').text('Loading...');
        },
        success: function(data) {
            $('#hospital-list').append(data);

            // Next page check
            let nextPage = page + 1;
            if (nextPage <= {{ $hospitals->lastPage() }}) {
                $('#load-more-btn').text('Load More').data('next-page', nextPage);
            } else {
                $('#load-more-container').remove();
            }
        }
    });
});
</script>
@endsection
