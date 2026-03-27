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
                                        <select name="invoice_master_id" class="form-control">
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
                                    <div class="input-group">
                                        <input type="date" name="booking_date" value="{{ $prescription->booking_date ?? '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Time -->
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3">Booking Time</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input type="time" name="booking_time" value="{{ $prescription->booking_time ?? '' }}" class="form-control">
                                    </div>
                                </div>
                            </div>

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
