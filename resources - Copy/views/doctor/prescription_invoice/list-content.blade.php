<?php
if(isset($res) && count($res) > 0){
    $i = 0;
    if(!empty($page)){
        $i = ($page - 1) * $page_size;
    }
    foreach($res as $row){
?>
<tr>
    <td>{{ ++$i }}</td>
    <td>{{ $row->invoice_number }}</td>
    <td>{{ $row->invoiceMaster->doctor->name ?? 'N/A' }}</td>
    <td>{{ $row->patient_name }}</td>
    <td>{{ $row->age }}</td>
    <td>{{ $row->gender }}</td>
    <td>{{ $row->patient_address }}</td>
    <td>{{ $row->patient_phone_no }}</td>
    <td>{{ $row->booking_date }}</td>
    <td>{{ $row->booking_time }}</td>
    <td>{{ $row->created_at }}</td>
    <td>
        <a href="{{ url('doctor/prescription-invoice/add?id='.$row->id) }}" class="btn btn-warning btn-sm">Edit</a>
        <a href="{{ url('doctor/prescription-invoice/delete/'.$row->id) }}"
           onclick="return confirm('Are you sure?')"
           class="btn btn-danger btn-sm">Delete</a>
        <a href="{{ url('doctor/prescription-invoice/pdf/'.$row->id) }}"
           target="_blank"
           class="btn btn-primary btn-sm">PDF</a>
    </td>
</tr>
<?php
    }
} else {
?>
<tr>
    <td colspan="12" class="text-center">No record found.</td>
</tr>
<?php } ?>
