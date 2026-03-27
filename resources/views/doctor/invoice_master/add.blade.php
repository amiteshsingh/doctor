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
                                        <select name="doctor_id" class="form-control">
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


