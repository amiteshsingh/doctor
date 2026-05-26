@extends('doctor.layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-8 col-5">
                <h4 class="page-title">{{ $title }}</h4>
            </div>
        </div>

        {{-- Search & Filter Bar --}}
        <div style="background:#fff;border-radius:14px;padding:16px 20px;margin-bottom:20px;box-shadow:0 2px 12px rgba(0,0,0,.06);display:flex;align-items:center;gap:12px;flex-wrap:wrap;">

            {{-- Search --}}
            <div style="flex:1;min-width:200px;position:relative;">
                <i class="fa fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#aaa;font-size:14px;"></i>
                <input type="text" id="search"
                       class="filterPrescriptionInvoice"
                       placeholder="Search invoice, patient, phone..."
                       style="width:100%;border:1.5px solid #e2e8f0;border-radius:10px;padding:9px 12px 9px 36px;font-size:13px;background:#f8fbff;outline:none;transition:border-color .2s;"
                       onfocus="this.style.borderColor='#0a6ebd'" onblur="this.style.borderColor='#e2e8f0'">
                <input type="hidden" id="sortBy" value="">
                <input type="hidden" id="orderBy" value="">
            </div>

            {{-- Date Picker --}}
            <div style="position:relative;">
                <i class="fa fa-calendar" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#0a6ebd;font-size:14px;pointer-events:none;z-index:1;"></i>
                <input type="text" id="filter_date"
                       class="filterPrescriptionInvoice"
                       placeholder="Select date"
                       readonly
                       style="border:1.5px solid #e2e8f0;border-radius:10px;padding:9px 12px 9px 36px;font-size:13px;background:#f8fbff;width:160px;cursor:pointer;outline:none;">
            </div>

            {{-- Clear --}}
            <button onclick="clearFilters()"
                    style="background:#f1f5f9;color:#555;border:1.5px solid #e2e8f0;border-radius:10px;padding:9px 18px;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap;transition:all .2s;"
                    onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                <i class="fa fa-times" style="margin-right:5px;"></i> Clear
            </button>

            {{-- Add Button --}}
            <a href="{{ url('doctor/prescription-invoice/add') }}"
               style="background:linear-gradient(135deg,#0a6ebd,#00b074);color:#fff;border-radius:10px;padding:9px 18px;font-size:13px;font-weight:700;text-decoration:none;white-space:nowrap;display:flex;align-items:center;gap:6px;">
                <i class="fa fa-plus"></i> Add New
            </a>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive" style="overflow-x:scroll">
                    <table class="table table-bordered" style="min-width:1200px;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <!-- <th>Invoice Master <i class="fa fa-sort ajaxSorting" data-type="prescription" data-sort_by="invoice_master_id" data-sort_order="asc"></i></th> -->
                                <!-- <th>Invoice Number <i class="fa fa-sort ajaxSorting" data-type="prescription" data-sort_by="invoice_number" data-sort_order="asc"></i></th> -->
                                <th>Doctor Name</th>
                                <th>Patient Name <i class="fa fa-sort ajaxSorting" data-type="prescription" data-sort_by="patient_name" data-sort_order="asc"></i></th>
                                <th>Age <i class="fa fa-sort ajaxSorting" data-type="prescription" data-sort_by="age" data-sort_order="asc"></i></th>
                                <th>Gender <i class="fa fa-sort ajaxSorting" data-type="prescription" data-sort_by="gender" data-sort_order="asc"></i></th>
                                <th>Address <i class="fa fa-sort ajaxSorting" data-type="prescription" data-sort_by="address" data-sort_order="asc"></i></th>
                                <th>Patient Phone <i class="fa fa-sort ajaxSorting" data-type="prescription" data-sort_by="patient_phone_no" data-sort_order="asc"></i></th>
                                <th>Booking Date</th>
                                <th>Booking Time</th>
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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    flatpickr('#filter_date', {
        dateFormat: 'Y-m-d',
        defaultDate: 'today',
        disableMobile: true,
        onChange: function() {
            ajaxSearching(1, 'prescription', 'prescription', 'filterPrescriptionInvoice');
        }
    });
});

function clearFilters() {
    document.getElementById('search').value = '';
    document.getElementById('filter_date')._flatpickr.clear();
    ajaxSearching(1, 'prescription', 'prescription', 'filterPrescriptionInvoice');
}

function cancelBooking(id, btn) {
    if (!confirm('Cancel this appointment? User will be notified.')) return;
    btn.disabled = true;
    fetch('{{ url("doctor/prescription-invoice/cancel") }}/' + id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
        }
    })
    .then(r => r.json())
    .then(function(res) {
        if (res.status === 200) {
            // Reload listing
            ajaxSearching(1, 'prescription', 'prescription', 'filterPrescriptionInvoice');
        } else {
            alert(res.msg || 'Something went wrong.');
            btn.disabled = false;
        }
    })
    .catch(function() { btn.disabled = false; });
}
</script>

@endsection