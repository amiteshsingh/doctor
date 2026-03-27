@extends('doctor.layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-8 col-5">
                <h4 class="page-title">{{ $title }}</h4>
            </div>
            <div class="col-sm-4 col-7 text-right m-b-30">
                <a href="{{ url('doctor/prescription-invoice/add') }}" class="btn btn-primary btn-rounded float-right">
                    <i class="fa fa-plus"></i> Add Prescription Invoice
                </a>
            </div>
        </div>

        <div class="row filter-row">
            <div class="col-md-5">
                <div class="form-group form-focus">
                    <label class="focus-label">Search by Invoice Number, Patient Name, Phone</label>
                    <input type="text" class="form-control floating filterPrescriptionInvoice" id="search">
                    <input type="hidden" name="sortBy" id="sortBy" value="">
                    <input type="hidden" name="orderBy" id="orderBy" value="">
                </div>
            </div>

          

            <div class="col-md-3">
                <a href="javascript:void(0)" class="btn btn-success btn-block" onclick="FilterReset(1,'prescription','prescription','filterPrescriptionInvoice')">Clear All Filters</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive" style="overflow-x:scroll">
                    <table class="table table-bordered" style="min-width:1200px;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice Master <i class="fa fa-sort ajaxSorting" data-type="prescription" data-sort_by="invoice_master_id" data-sort_order="asc"></i></th>
                                <th>Invoice Number <i class="fa fa-sort ajaxSorting" data-type="prescription" data-sort_by="invoice_number" data-sort_order="asc"></i></th>
                                <th>Patient Name <i class="fa fa-sort ajaxSorting" data-type="prescription" data-sort_by="patient_name" data-sort_order="asc"></i></th>
                                <th>Age <i class="fa fa-sort ajaxSorting" data-type="prescription" data-sort_by="age" data-sort_order="asc"></i></th>
                                <th>Gender <i class="fa fa-sort ajaxSorting" data-type="prescription" data-sort_by="gender" data-sort_order="asc"></i></th>
                                <th>Patient Phone <i class="fa fa-sort ajaxSorting" data-type="prescription" data-sort_by="patient_phone_no" data-sort_order="asc"></i></th>
                                <th>Booking Date</th>
                                <th>Booking Time</th>
                                <th>Created At <i class="fa fa-sort ajaxSorting" data-type="prescription" data-sort_by="created_at" data-sort_order="asc"></i></th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="data_listing">
                            @if(isset($result['content_html']))
                                {!! $result['content_html'] !!}
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="box-footer clearfix">
            <div id="pagination_data">
                @if(isset($result['pagination_html']))
                    {!! $result['pagination_html'] !!}
                @endif
            </div>
        </div>

    </div>
</div>

{{-- Delete Modal --}}
<div id="delete_invoice" class="modal fade delete-modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="{{ asset('assets/img/sent.png') }}" alt="" width="50" height="46">
                <h3>Are you sure want to delete this Prescription Invoice?</h3>
                <div class="m-t-20">
                    <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
                    <a href="#" class="btn btn-danger" id="confirmDelete">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const deleteLinks = document.querySelectorAll('[data-toggle="modal"][data-target="#delete_invoice"]');
    const confirmDelete = document.getElementById("confirmDelete");

    deleteLinks.forEach(link => {
        link.addEventListener("click", function () {
            const deleteUrl = this.getAttribute("data-url");
            confirmDelete.setAttribute("href", deleteUrl);
        });
    });
});
</script>

@endsection