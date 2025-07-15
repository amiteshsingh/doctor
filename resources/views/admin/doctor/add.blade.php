@extends('admin.layout.app')

@section('content')
<?php 
// echo "<pre>";
// echo $doctor['name'];
// print_r($doctor);
// echo "<pre>";
?>

@if(isset($doctor->id))
    @php $form = 'Update'; @endphp
@else
    @php $form = 'Add'; @endphp
@endif

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">{{ $form }} Doctor</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <h4 class="card-title">{{ $form }} Doctor</h4>

                    <ul class="nav nav-tabs nav-tabs-top">
                        <li class="nav-item"><a class="nav-link active" href="#basictab1" data-toggle="tab">Doctor Details</a></li>
                        <?php if(isset($doctor->id) && $doctor->id != ''){ ?>
                        <li class="nav-item"><a class="nav-link" href="#basictab2" data-toggle="tab">Doctor Specialization</a></li>
                        <?php } ?>
                    </ul>


                    <div class="tab-content">
                        <div class="tab-pane show active" id="basictab1">
                            <form  method="POST" id="doctor_form" name="doctor_form">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{isset($doctor->id)?$doctor->id:''}}">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Full Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="name" id="name" value="{{isset($doctor->name)?$doctor->name:''}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Phone Number</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="phone_no" id="phone_no" value="{{isset($doctor->phone_no)?$doctor->phone_no:''}}">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" name="email" id="email" value="{{isset($doctor->email)?$doctor->email:''}}">
                                    </div>
                                </div>
                            
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Latitude</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="latitude" id="latitude" value="{{isset($doctor->latitude)?$doctor->latitude:''}}">
                                    </div>
                                </div>
                            
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Longitude</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="longitude" id="longitude" value="{{isset($doctor->longitude)?$doctor->longitude:''}}">
                                    </div>
                                </div>
                            
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Status</label>
                                    <div class="col-md-9">
                                        <select class="select" name="status" id="status" >
                                            <option value="">Select status</option>
                                            <option value="1" {{ (isset($doctor->status) && $doctor->status == 1) ? 'selected':''}}>Active</option>
                                            <option value="0"  {{ (isset($doctor->status) && $doctor->status == 0) ? 'selected':''}}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">	Approval Status</label>
                                    <div class="col-md-9">
                                        <select class="select" name="approval_status" id="approval_status" >
                                            <option value="">Select Status</option>
                                            <option value="1"  {{ (isset($doctor->approval_status) && $doctor->approval_status == 1) ? 'selected':''}}>Active</option>
                                            <option value="0" {{ (isset($doctor->approval_status) && $doctor->approval_status == 0) ? 'selected':''}}>Inactive</option>
                                            <option value="2" {{ (isset($doctor->approval_status) && $doctor->approval_status == 2) ? 'selected':''}}>Block</option>
                                        </select>
                                    </div>
                                </div>

                                    <div class="col-md-4 text-right">
                                    <button type="submit" id="save_doctor" class="btn btn-primary">Submit</button>
                                    <a href="{{ route('admin.doctor') }}" type="submit" class="btn btn-primary">Back</a>
                                        
                                </div>
                            
                            </form>
                         </div>

                          <div class="tab-pane" id="basictab2">
                          <form method="POST" id="doctor_specialization_form" name="doctor_specialization_form">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{isset($doctor->id)?$doctor->id:''}}">
                                
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Specialization</label>
                                    <div class="col-md-9">
                                        @foreach($specializations as $specialization)
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" 
                                                    id="specialization_{{ $specialization->id }}" 
                                                    name="specialization_ids[]" 
                                                    value="{{ $specialization->id }}"
                                                    {{ (isset($doctor->specialization_data) && in_array($specialization->id, $doctor->specialization_data)) ? 'checked' : '' }} >
                                                <label class="custom-control-label" for="specialization_{{ $specialization->id }}">
                                                    {{ $specialization->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-4 text-right">
                                        <button type="submit" id="save_doctor_specialization" class="btn btn-primary">Submit</button>
                                        <a href="{{ route('admin.doctor') }}" class="btn btn-primary">Back</a>
                                    </div>
                                </div>
                            </form>
                                
                          </div>

                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
