@extends('user.layouts.app')
@section('title', 'RogiSewa - My Bookings')

@section('user_content')
<div class="card shadow border-0 rounded-4 p-4 bg-white">
    <h5 class="fw-bold border-bottom pb-2 mb-4"><i class="fa fa-calendar-check me-2 text-primary"></i>My Bookings</h5>

    <table id="bookingsTable" class="table table-striped table-hover w-100">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Invoice No</th>
                <th>Doctor</th>
                <th>Patient</th>
                <th>Phone</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $i => $b)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $b->invoice_number }}</td>
                <td>{{ $b->invoiceMaster->doctor->name ?? 'N/A' }}</td>
                <td>{{ $b->patient_name }}</td>
                <td>{{ $b->patient_phone_no }}</td>
                <td>{{ $b->booking_date }}</td>
                <td>{{ $b->booking_time }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$('#bookingsTable').DataTable({ order: [[5, 'desc']] });
</script>
@endsection
