@extends('doctor.layouts.app')

@section('content')

@if(isset($invoice->id))
    @php $form = 'Update'; @endphp
@else
    @php $form = 'Add'; @endphp
@endif

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">{{ $form }} Invoice Master</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <h4 class="card-title">{{ $form }} Invoice Master</h4>

                    <div class="container">

                        <form id="invoice_form" method="POST"  name="doctor_form"  enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $invoice->id ?? '' }}">

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Doctor</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select name="doctor_id" id="doctor_id" class="form-control" onchange="fetchDoctorDetails(this.value)">
                                            <option value="">-- Select Doctor --</option>
                                            @foreach($doctors as $id => $name)
                                                <option value="{{ $id }}" {{ (isset($invoice->doctor_id) && $invoice->doctor_id == $id) ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Hospital/Clinic Name</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input type="text" name="hospital_clinic_name" value="{{ $invoice->hospital_clinic_name ?? '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                        <!-- Address Field -->
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Address</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input type="text" name="address" value="{{ $invoice->address ?? '' }}" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Phone Number Field -->
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Phone Number</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">+91</span>
                                        </div>
                                        <input type="number" name="phone_no" value="{{ $invoice->phone_no ?? '' }}" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Email ID <small class="text-muted">(optional)</small></label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input type="email" name="email" value="{{ $invoice->email ?? '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Consultation Fee</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">₹</span>
                                        </div>
                                        <input type="text" class="form-control" name="consultation_fee" value="{{ $invoice->consultation_fee ?? '' }}" >
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Booking Mode</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <select name="booking_mode" class="form-control">
                                            <option value="OFFLINE" {{ (isset($invoice->booking_mode) && $invoice->booking_mode == 'OFFLINE') ? 'selected' : '' }}>OFFLINE</option>
                                            <option value="ONLINE" {{ (isset($invoice->booking_mode) && $invoice->booking_mode == 'ONLINE') ? 'selected' : '' }}>ONLINE</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Start Time</label>
                                <div class="col-md-9">
                                    <select name="start_time" class="form-control">
                                        <option value="">-- Select Start Time --</option>
                                        @for($h = 0; $h < 24; $h++)
                                            @foreach(['00','15','30','45'] as $m)
                                                @php
                                                    $val   = sprintf('%02d:%s', $h, $m);
                                                    $h12   = $h % 12 ?: 12;
                                                    $ampm  = $h < 12 ? 'AM' : 'PM';
                                                    $label = sprintf('%02d:%s %s', $h12, $m, $ampm);
                                                @endphp
                                                <option value="{{ $val }}"
                                                    {{ ($invoice->start_time ?? '') == $val ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        @endfor
                                    </select>
                                    <small class="text-muted">Clinic opening / slot start time</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">End Time</label>
                                <div class="col-md-9">
                                    <select name="end_time_slot" class="form-control">
                                        <option value="">-- Select End Time --</option>
                                        @for($h = 0; $h < 24; $h++)
                                            @foreach(['00','15','30','45'] as $m)
                                                @php
                                                    $val   = sprintf('%02d:%s', $h, $m);
                                                    $h12   = $h % 12 ?: 12;
                                                    $ampm  = $h < 12 ? 'AM' : 'PM';
                                                    $label = sprintf('%02d:%s %s', $h12, $m, $ampm);
                                                @endphp
                                                <option value="{{ $val }}"
                                                    {{ ($invoice->end_time_slot ?? '') == $val ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        @endfor
                                    </select>
                                    <small class="text-muted">Clinic closing / slot end time</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Slot Duration (minutes)</label>
                                <div class="col-md-9">
                                    <input type="number" name="duration_time_slot" class="form-control"
                                        min="1" placeholder="e.g. 15, 30, 60"
                                        value="{{ $invoice->duration_time_slot ?? '' }}">
                                    <small class="text-muted">Each appointment slot duration in minutes</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" id="save_invoice" class="btn btn-success">Save</button>
                                    <a href="{{ url('doctor/invoice-master') }}" class="btn btn-secondary">Back</a>
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

<script>
function fetchDoctorDetails(doctorId) {
    if (!doctorId) return;
    fetch('{{ url("doctor/invoice-master/doctor-details") }}/' + doctorId, {
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
    })
    .then(r => r.json())
    .then(function(d) {
        if (d.status !== 200) return;
        var f = d.data;
        setVal('hospital_clinic_name', f.practice_name || f.name || '');
        setVal('address',              f.address || '');
        setVal('phone_no',             f.phone || f.phone_no || '');
        setVal('email',                f.email || '');
    });
}
function setVal(name, val) {
    var el = document.querySelector('[name="' + name + '"]');
    if (el) el.value = val;
}
document.addEventListener('DOMContentLoaded', function() {
    var sel = document.getElementById('doctor_id');
    if (sel && sel.value) fetchDoctorDetails(sel.value);
});
</script>


