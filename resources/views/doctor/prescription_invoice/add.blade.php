@extends('doctor.layouts.app')

@section('content')

@if(isset($prescription->id))
    @php $form = 'Update'; @endphp
@else
    @php $form = 'Add'; @endphp
@endif

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">{{ $form }} Prescription Invoice</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <h4 class="card-title">{{ $form }} Prescription Invoice</h4>

                    <div class="container">

                        <form id="prescription_invoice_form" method="POST" name="prescription_invoice_form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $prescription->id ?? '' }}">

                            <!-- Invoice Master Dropdown -->
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Invoice Master</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select name="invoice_master_id" id="invoice_master_id" class="form-control" onchange="loadSlots()">
                                            <option value="">-- Select Invoice --</option>
                                            @foreach($invoiceMasters as $id => $name)
                                                <option value="{{ $id }}" {{ (isset($prescription->invoice_master_id) && $prescription->invoice_master_id == $id) ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Patient Name -->
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Patient Name</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input type="text" name="patient_name" value="{{ $prescription->patient_name ?? '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Patient Age -->
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Patient Age</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input type="age" name="age" value="{{ $prescription->age ?? '' }}" class="form-control" min="0">
                                    </div>
                                </div>
                            </div>

                            <!-- Patient Gender -->
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Gender</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select name="gender" class="form-control">
                                            <option value="">-- Select Gender --</option>
                                            <option value="Male" {{ (isset($prescription->gender) && $prescription->gender == 'Male') ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ (isset($prescription->gender) && $prescription->gender == 'Female') ? 'selected' : '' }}>Female</option>
                                            <option value="Other" {{ (isset($prescription->gender) && $prescription->gender == 'Other') ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Patient Address -->
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Patient Address</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <textarea name="patient_address" class="form-control" rows="2">{{ $prescription->patient_address ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Patient Phone -->
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Patient Phone</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input type="text" name="patient_phone_no" value="{{ $prescription->patient_phone_no ?? '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Date -->
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Booking Date</label>
                                <div class="col-md-9">
                                    <input type="text" name="booking_date" id="booking_date"
                                           value="{{ $prescription->booking_date ?? '' }}"
                                           class="form-control" placeholder="Select date" readonly>
                                </div>
                            </div>

                            <!-- Slot Picker -->
                            <div class="form-group row" id="slot_section" style="display:none;">
                                <label class="col-form-label col-lg-3">Select Time Slot</label>
                                <div class="col-md-9">
                                    <div id="slot_loader" style="display:none;color:#888;font-size:13px;">
                                        <i class="fa fa-spinner fa-spin"></i> Loading slots...
                                    </div>
                                    <div id="slot_grid" style="display:flex;flex-wrap:wrap;gap:10px;margin-top:4px;"></div>
                                    <div id="slot_msg" style="font-size:12px;color:#ef4444;margin-top:6px;"></div>
                                </div>
                            </div>

                            <!-- Booking Time (hidden, filled by slot click) -->
                            <input type="hidden" name="booking_time" id="booking_time"
                                   value="{{ $prescription->booking_time ?? '' }}">

                            <!-- Submit -->
                            <div class="form-group row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" id="save_prescription_invoice" class="btn btn-success">Save</button>
                                    <a href="{{ url('doctor/prescription-invoice') }}" class="btn btn-secondary">Back</a>
                                </div>
                            </div>

                        </form>
                    </div> <!-- /.container -->

                </div> <!-- /.card-box -->
            </div> <!-- /.col-lg-12 -->
        </div> <!-- /.row -->

    </div> <!-- /.content -->
</div> <!-- /.page-wrapper -->

@endsection

<!-- Slot Already Booked Modal -->
<div id="slotConflictModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.55);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:20px;padding:32px 28px;max-width:380px;width:90%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,.2);">
        <div style="font-size:52px;margin-bottom:12px;">⚠️</div>
        <div style="font-size:18px;font-weight:800;color:#1a1a2e;margin-bottom:8px;">Slot Already Booked!</div>
        <div style="font-size:13px;color:#666;margin-bottom:24px;line-height:1.6;">This time slot was just booked by someone else. Please select a different slot.</div>
        <button onclick="closeConflict()" style="background:linear-gradient(135deg,#0a6ebd,#00b074);color:#fff;border:none;border-radius:10px;padding:11px 28px;font-size:14px;font-weight:700;cursor:pointer;width:100%;">Choose Another Slot</button>
    </div>
</div>

<style>
.slot-chip {
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border: 2px solid #e2e8f0;
    background: #f8fbff;
    color: #333;
    transition: all .2s;
    user-select: none;
}
.slot-chip:hover { border-color: #0a6ebd; background: #e8f3ff; color: #0a6ebd; }
.slot-chip.selected { background: linear-gradient(135deg,#0a6ebd,#00b074); color: #fff; border-color: transparent; box-shadow: 0 4px 12px rgba(10,110,189,.3); }
.slot-chip.booked { background: #f1f5f9; color: #ccc; border-color: #e2e8f0; cursor: not-allowed; text-decoration: line-through; }
.slot-chip.past  { background: #fef2f2; color: #fca5a5; border-color: #fecaca; cursor: not-allowed; text-decoration: line-through; }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    flatpickr('#booking_date', {
        minDate: 'today',
        dateFormat: 'Y-m-d',
        disableMobile: true,
        onChange: function() { loadSlots(); }
    });

    var masterId = document.getElementById('invoice_master_id').value;
    var date     = document.getElementById('booking_date').value;
    if (masterId && date) loadSlots();

    // AJAX form submit
    document.getElementById('prescription_invoice_form').addEventListener('submit', function(e) {
        e.preventDefault();
        var form    = this;
        var btn     = document.getElementById('save_prescription_invoice');
        var formData = new FormData(form);
        btn.disabled = true;
        btn.textContent = 'Saving...';

        fetch(window.location.href, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(r => r.json())
        .then(function(res) {
            btn.disabled = false;
            btn.textContent = 'Save';
            if (res.status === 409) {
                // Slot conflict — show modal and reload slots
                document.getElementById('slotConflictModal').style.display = 'flex';
                document.getElementById('booking_time').value = '';
                loadSlots();
            } else if (res.status === 200) {
                window.location.href = '{{ url("doctor/prescription-invoice") }}';
            } else {
                alert(res.msg || 'Something went wrong.');
            }
        })
        .catch(function() {
            btn.disabled = false;
            btn.textContent = 'Save';
            alert('Network error. Please try again.');
        });
    });
});

function closeConflict() {
    document.getElementById('slotConflictModal').style.display = 'none';
}

function loadSlots() {
    var masterId = document.getElementById('invoice_master_id').value;
    var date     = document.getElementById('booking_date').value;
    var section  = document.getElementById('slot_section');
    var grid     = document.getElementById('slot_grid');
    var loader   = document.getElementById('slot_loader');
    var msg      = document.getElementById('slot_msg');

    if (!masterId || !date) { section.style.display = 'none'; return; }

    section.style.display = 'flex';
    loader.style.display  = 'block';
    grid.innerHTML = '';
    msg.textContent = '';

    var currentTime = document.getElementById('booking_time').value;

    fetch('{{ route("prescription-invoice.slots") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify({ invoice_master_id: masterId, booking_date: date })
    })
    .then(r => r.json())
    .then(function(res) {
        loader.style.display = 'none';
        if (res.status !== 200 || !res.slots.length) {
            msg.textContent = 'No slots available. Please set start/end time in Invoice Settings.';
            return;
        }

        // Browser current time in minutes
        var now     = new Date();
        var nowMin  = res.isToday ? (now.getHours() * 60 + now.getMinutes()) : -1;

        res.slots.forEach(function(slot) {
            var isPast   = nowMin >= 0 && slot.minutes < nowMin;
            var isBooked = slot.booked;
            var chip     = document.createElement('div');
            var cls      = 'slot-chip';
            if (isPast)        cls += ' past';
            else if (isBooked) cls += ' booked';
            else if (slot.time === currentTime) cls += ' selected';
            chip.className = cls;
            chip.innerHTML = isPast
                ? slot.time + ' <small style="font-size:10px;opacity:.7;">(past)</small>'
                : slot.time;
            if (!isPast && !isBooked) {
                chip.onclick = function() {
                    document.querySelectorAll('.slot-chip').forEach(function(c) { c.classList.remove('selected'); });
                    chip.classList.add('selected');
                    document.getElementById('booking_time').value = slot.time;
                };
            }
            grid.appendChild(chip);
        });
    });
}
</script>
