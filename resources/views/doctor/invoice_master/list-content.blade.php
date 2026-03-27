<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Doctor Name</th>
            <th>Hospital/Clinic</th>
            <th>Phone No.</th>
            <th>Consultation Fee</th>
            <th>Booking Mode</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if(count($res) > 0)
        @php $i = 1; @endphp
        @foreach($res as $invoice)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $invoice->doctor_name }}</td>
                <td>{{ $invoice->hospital_clinic_name }}</td>
                <td>+91-{{ $invoice->phone_no }}</td>
                <td>₹ {{ number_format($invoice->consultation_fee, 2) }}</td>
                <td>
                    <span class="badge badge-{{ $invoice->booking_mode == 'ONLINE' ? 'success' : 'secondary' }}">
                        {{ $invoice->booking_mode }}
                    </span>
                </td>
                <td>{{ $invoice->created_at }}</td>
                <td>
                    <a href="{{ url('doctor/invoice-master/add?id='.$invoice->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <a href="{{ url('doctor/invoice-master/delete/'.$invoice->id) }}" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('Are you sure to delete?')">
                       Delete
                    </a>
                </td>
            </tr>
        @endforeach
    @else
        <tr><td colspan="6" class="text-center">No records found</td></tr>
    @endif
    </tbody>
</table>
