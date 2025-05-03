@extends('admin.layout.app')

@section('content')
<?php 
// echo "<pre>";
// echo $hospital['name'];
// print_r($hospital);
// echo "<pre>";
?>

@if(isset($hospital->id))
    @php $form = 'Update'; @endphp
@else
    @php $form = 'Add'; @endphp
@endif

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">{{ $form }} Hospital</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <h4 class="card-title">{{ $form }} Hospital</h4>
                    <form  method="POST" id="hospital_form" name="hospital_form">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{isset($hospital->id)?$hospital->id:''}}">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Full Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" id="name" value="{{isset($hospital->name)?$hospital->name:''}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Phone Number</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="phone_no" id="phone_no" value="{{isset($hospital->phone_no)?$hospital->phone_no:''}}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Address</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="address" id="address" value="{{isset($hospital->address)?$hospital->address:''}}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Email</label>
                            <div class="col-md-9">
                                <input type="email" class="form-control" name="email" id="email" value="{{isset($hospital->email)?$hospital->email:''}}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">City</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="city" id="city" value="{{isset($hospital->city)?$hospital->city:''}}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">State</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="state" id="state" value="{{isset($hospital->state)?$hospital->state:''}}">
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Pin Code</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="zip_code" id="zip_code" value="{{isset($hospital->zip_code)?$hospital->zip_code:''}}">
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Latitude</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="latitude" id="latitude" value="{{isset($hospital->latitude)?$hospital->latitude:''}}">
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Longitude</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="longitude" id="longitude" value="{{isset($hospital->longitude)?$hospital->longitude:''}}">
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Status</label>
                            <div class="col-md-9">
                                <select class="select" name="status" id="status" >
                                    <option value="">Select status</option>
                                    <option value="1" {{ (isset($hospital->status) && $hospital->status == 1) ? 'selected':''}}>Active</option>
                                    <option value="0"  {{ (isset($hospital->status) && $hospital->status == 0) ? 'selected':''}}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">	Approval Status</label>
                            <div class="col-md-9">
                                <select class="select" name="approval_status" id="approval_status" >
                                    <option value="">Select Status</option>
                                    <option value="1"  {{ (isset($hospital->approval_status) && $hospital->approval_status == 1) ? 'selected':''}}>Active</option>
                                    <option value="0" {{ (isset($hospital->approval_status) && $hospital->approval_status == 0) ? 'selected':''}}>Inactive</option>
                                    <option value="2" {{ (isset($hospital->approval_status) && $hospital->approval_status == 2) ? 'selected':''}}>Block</option>
                                </select>
                            </div>
                        </div>

                            <div class="col-md-4 text-right">
                            <button type="submit" id="save_hospital" class="btn btn-primary">Submit</button>
                            <a href="{{ route('admin.hospital') }}" type="submit" class="btn btn-primary">Back</a>
                                
                        </div>
                       
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
