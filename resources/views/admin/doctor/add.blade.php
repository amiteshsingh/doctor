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
                        <li class="nav-item"><a class="nav-link active" href="#basictab1" data-toggle="tab">Doctor's Details</a></li>
                        <?php if(isset($doctor->id) && $doctor->id != ''){ ?>
                        <li class="nav-item"><a class="nav-link" href="#basictab2" data-toggle="tab">Doctor's Specialization</a></li>
                        <li class="nav-item"><a class="nav-link" href="#basictab3" data-toggle="tab">Personal Information</a></li>
                        <li class="nav-item"><a class="nav-link" href="#basictab4" data-toggle="tab">Availability</a></li>
                        <?php } ?>
                    </ul>


                    <div class="tab-content">
                        <div class="tab-pane show active" id="basictab1">
                            <form method="POST" id="doctor_form" name="doctor_form" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{isset($doctor->id)?$doctor->id:''}}">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Full Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="name" id="name" value="{{isset($doctor->name)?$doctor->name:''}}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Profile Picture</label>
                                    <div class="col-md-9">
                                        <input type="file" class="form-control" name="profile_pic" id="profile_pic" accept="image/*">

                                        @php
                                            $profileImage = isset($doctor->profile_pic) && file_exists(public_path('uploads/doctor/'.$doctor->profile_pic))
                                                            ? asset('uploads/doctor/'.$doctor->profile_pic)
                                                            : asset('uploads/doctor/user.jpg'); // default image path
                                        @endphp

                                        <img class="doctor-img avatar" src="{{ $profileImage }}"  style="margin-top: 10px;">
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
                                <?php if(isset($doctor->id) && $doctor->id != ''){ ?>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Hospital</label>
                                    <div class="col-md-9">
                                        <select class="form-control select" name="hospital_id">
                                            <option value="">Select Hospital</option>
                                            @foreach($hospitals as $hospital)
                                                <option value="{{ $hospital->id }}"
                                                    {{ (isset($doctor->language_data) && $hospital->id == $doctor->hospital_id) ? 'selected' : '' }}>
                                                    {{ $hospital->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <?php } ?>
                            
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

                    
                         <div class="tab-pane" id="basictab3">
                            <form method="POST" id="doctor_location_form" name="doctor_location_form">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{isset($doctor->id)?$doctor->id:''}}">

                                <h5 class="mb-3">Location Details</h5>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Practice Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="practice_name"  placeholder="Dr. .........." value="{{isset($doctor->practice_name)?$doctor->practice_name:''}}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Address</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="address">{{isset($doctor->address)?$doctor->address:''}}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">City</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="city" value="{{isset($doctor->city)?$doctor->city:''}}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">State</label>
                                    <div class="col-md-9">

                                        <select name="state" class="form-control select">
                                            <option value="">-- Select State --</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->state_name }}"
                                                    {{ (isset($doctor->state) && $doctor->state == $state->state_name) ? 'selected' : '' }}>
                                                    {{ $state->state_name }}
                                                </option>
                                            @endforeach
                                        </select>                                   
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Pin Code</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" name="pin_code" value="{{isset($doctor->zip_code)?$doctor->zip_code:''}}"  min="100000" max="999999" maxlength="6" >
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Phone</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="location_phone" value="{{isset($doctor->location_phone)?$doctor->location_phone:''}}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                <label class="col-form-label col-md-2">Doctor's Experience <small>(from year)</small></label>
                                    <div class="col-md-9">
                                        <select name="experience" class="form-control" required>
                                            <option value="">-- Select Year --</option>
                                            @for($year = date('Y'); $year >= 1980; $year--)
                                                <option value="{{ $year }}" {{ (isset($doctor->experience) && $doctor->experience == $year) ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <hr>
                                <h5 class="mb-3">Education</h5>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Degree Type</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="degree_type" value="{{isset($doctor->degree_type)?$doctor->degree_type:''}}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Institution Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="institution_name" value="{{isset($doctor->institution_name)?$doctor->institution_name:''}}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Graduation Year</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="graduation_year" value="{{isset($doctor->graduation_year)?$doctor->graduation_year:''}}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Details</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="education_details">{{isset($doctor->education_details)?$doctor->education_details:''}}</textarea>
                                    </div>
                                </div>

                                <hr>
                                <h5 class="mb-3">Languages</h5>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-2">Languages</label>
                                    <div class="col-md-9">
                                        <select class="form-control select" multiple name="languages[]">
                                            @foreach($languages as $lang)
                                                <option value="{{ $lang->id }}"
                                                    {{ (isset($doctor->language_data) && in_array($lang->id, $doctor->language_data)) ? 'selected' : '' }}>
                                                    {{ $lang->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <div class="col-md-4 text-right">
                                        <button type="submit" id="save_doctor_location" class="btn btn-primary">Submit</button>
                                        <a href="{{ route('admin.doctor') }}" class="btn btn-primary">Back</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane" id="basictab4">
                            <form method="POST" id="doctor_availability_form" name="doctor_availability_form">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ isset($doctor->id) ? $doctor->id : '' }}">

                                @php
                                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                @endphp

                                @foreach($days as $day)
                                <div class="row">
                                    <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="col-form-label col-md-2">{{ $day }}</label>
                                    </div>
                                    </div>
                              

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Start Time</label>
                                            <div class="time-icon">
                                                <input type="text" name="availability[{{ $day }}][start_time]" class="form-control datetimepicker3"
                                                        value="{{ $doctor->availability[$day]['start_time'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>End Time</label>
                                            <div class="time-icon">
                                                <input type="text" name="availability[{{ $day }}][end_time]" class="form-control datetimepicker3" 
                                                        value="{{ $doctor->availability[$day]['end_time'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                @endforeach

                                <div class="form-group row">
                                    <div class="col-md-4 text-right">
                                        <button type="submit" id="save_doctor_availability" class="btn btn-primary">Save Availability</button>
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
