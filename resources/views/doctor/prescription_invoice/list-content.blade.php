<?php
if(isset($res) && count($res) > 0){
    $i = 0;
    if(!empty($page)) $i = ($page - 1) * $page_size;
    foreach($res as $row){
        $isToday = $row->booking_date && \Carbon\Carbon::parse($row->booking_date)->isToday();
?>
<tr>
    <td>{{ ++$i }}</td>
    <td>{{ $row->invoiceMaster->doctor->name ?? 'N/A' }}</td>
    <td>{{ $row->patient_name }}</td>
    <td>{{ $row->age }}</td>
    <td>{{ $row->gender }}</td>
    <td>{{ $row->patient_address }}</td>
    <td>{{ $row->patient_phone_no }}</td>

    {{-- Booking Date --}}
    <td>
        @if($row->booking_date)
            @if($isToday)
                <span style="display:inline-flex;align-items:center;gap:5px;background:linear-gradient(135deg,#0a6ebd,#00b074);color:#fff;border-radius:8px;padding:4px 10px;font-size:12px;font-weight:700;">
                    <i class="fa fa-calendar-check-o"></i>
                    {{ \Carbon\Carbon::parse($row->booking_date)->format('d-m-Y') }}
                </span>
            @else
                <span style="color:#555;font-size:13px;">
                    {{ \Carbon\Carbon::parse($row->booking_date)->format('d-m-Y') }}
                </span>
            @endif
        @endif
    </td>

    {{-- Booking Time --}}
    <td>
        @if($row->booking_time)
            @if($isToday)
                <span style="display:inline-flex;align-items:center;gap:5px;background:linear-gradient(135deg,#f59e0b,#ef4444);color:#fff;border-radius:8px;padding:4px 10px;font-size:12px;font-weight:700;">
                    <i class="fa fa-clock-o"></i>
                    {{ $row->booking_time }}
                </span>
            @else
                <span style="color:#555;font-size:13px;">{{ $row->booking_time }}</span>
            @endif
        @endif
    </td>

    <td>
        @if(isset($row->status) && $row->status === 'cancelled')
            <span style="background:#fef2f2;color:#ef4444;border:1px solid #fecaca;border-radius:6px;padding:3px 10px;font-size:11px;font-weight:700;">Cancelled</span>
        @else
            <a href="{{ url('doctor/prescription-invoice/add?id='.$row->id) }}" title="Edit"
               style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;background:#fff8e1;color:#f59e0b;border:1px solid #fde68a;margin-right:4px;">
                <i class="fa fa-pencil" style="font-size:13px;"></i>
            </a>
            <button onclick="cancelBooking({{ $row->id }}, this)" title="Cancel Appointment"
               style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;background:#fff7ed;color:#f97316;border:1px solid #fed7aa;margin-right:4px;cursor:pointer;">
                <i class="fa fa-ban" style="font-size:13px;"></i>
            </button>
            <a href="{{ url('doctor/prescription-invoice/delete/'.$row->id) }}"
               onclick="return confirm('Are you sure?')" title="Delete"
               style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;background:#fff0f0;color:#ef4444;border:1px solid #fecaca;margin-right:4px;">
                <i class="fa fa-trash" style="font-size:13px;"></i>
            </a>
            <a href="{{ url('doctor/prescription-invoice/pdf/'.$row->id) }}" target="_blank" title="Download PDF"
               style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;background:#fff0f0;color:#e53e3e;border:1px solid #fecaca;">
                <i class="fa fa-file-pdf-o" style="font-size:13px;"></i>
            </a>
        @endif
    </td>
</tr>
<?php
    }
} else {
?>
<tr><td colspan="11" class="text-center">No record found.</td></tr>
<?php } ?>
