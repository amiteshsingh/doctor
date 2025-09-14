@extends('page.layouts.app')
@section('title', 'RogiSewa - Doctor List')

@section('content')

<div class="container-fluid py-5">
    <div class="container">
        <div class="text-center mx-auto mb-3" style="max-width: 600px;">
            <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Doctors</h5>
            <h1 class="display-6">Doctor Listing</h1>
        </div>
        
        <!-- Search Form -->
        <div class="col-12 mb-4">
            <!-- Added id="doctorSearchForm" -->
            <form method="GET" action="{{ url('doctors') }}" class="row g-3" id="doctorSearchForm">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control"
                           placeholder="Search by Name" value="{{ request('name') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="specialization" id="specialization" class="form-control"
                           placeholder="Specialization" value="{{ request('specialization') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="address" class="form-control"
                           placeholder="Location / Address" value="{{ request('address') }}">
                </div>

                <div class="col-md-2">
                    <input type="text" name="zip_code" class="form-control" placeholder="PIN Code" value="{{ request('zip_code') }}">
                </div>

                <div class="col-md-2">
                    <select name="gender" class="form-control">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                
                <div class="col-md-1 d-grid">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Doctors List -->
        <div class="row g-4" id="doctorList">
            @include('page.ajax.doctor_list', ['doctors' => $doctors])
        </div>

        <!-- Load More Button -->
        @if($doctors->hasMorePages())
        <div class="col-12 text-center mt-4">
            <button id="loadMoreBtn" class="btn btn-primary py-3 px-5"
                    data-next-page="{{ $doctors->currentPage() + 1 }}">
                Load More
            </button>
        </div>
        @endif
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<script>
    
$(function () {

    $("#specialization").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "{{ route('specializations.suggest') }}",
                data: { term: request.term },
                success: function(data) {
                    response(data); // backend से आए results को autocomplete को भेजो
                }
            });
        },
        minLength: 1
    });


    // Load More Click
    $(document).on('click', '#loadMoreBtn', function () {
        let page = $(this).data('next-page');
        let formData = $('#doctorSearchForm').serialize(); // get search filters

        $.ajax({
            url: "{{ url('doctors') }}?page=" + page + "&" + formData,
            type: 'GET',
            success: function (data) {
                $('#doctorList').append(data); // append new doctors
                $('#loadMoreBtn').data('next-page', page + 1);

                // अगर aur pages nahi hain तो button hata do
                if (!data.includes('doctor-item-marker')) {
                    $('#loadMoreBtn').remove();
                }
            }
        });
    });

    // Search Submit - reset doctor list
    // $('#doctorSearchForm').on('submit', function (e) {
    //     e.preventDefault();
    //     let formData = $(this).serialize();

    //     $.ajax({
    //         url: "{{ url('doctors') }}?" + formData,
    //         type: 'GET',
    //         success: function (data) {
    //             $('#doctorList').html(data);

    //             // reset Load More button
    //             if ($('#doctorList').find('#hasMore').length) {
    //                 $('#loadMoreBtn').show().data('next-page', 2);
    //             } else {
    //                 $('#loadMoreBtn').hide();
    //             }
    //         }
    //     });
    // });
});
</script>
@endsection
