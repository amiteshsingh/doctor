<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Doctor ID</th>
            <th>Hospital/Clinic</th>
            <th>Consultation Fee</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @if(count($res) > 0)
        @foreach($res as $invoice)
            <tr>
                <td>{{ $invoice->id }}</td>
                <td>{{ $invoice->doctor_id }}</td>
                <td>{{ $invoice->hospital_clinic_name }}</td>
                <td>₹ {{ number_format($invoice->consultation_fee, 2) }}</td>
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
