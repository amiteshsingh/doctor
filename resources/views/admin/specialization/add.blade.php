@extends('admin.layout.app')

@section('content')
<?php 
// echo "<pre>";
// echo $specialization['name'];
// print_r($specialization);
// echo "<pre>";
?>

@if(isset($specialization->id))
    @php $form = 'Update'; @endphp
@else
    @php $form = 'Add'; @endphp
@endif

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">{{ $form }} specialization</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <h4 class="card-title">{{ $form }} Specialization</h4>
                    <form  method="POST" id="specialization_form" name="specialization_form">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{isset($specialization->id)?$specialization->id:''}}">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Full Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" id="name" value="{{isset($specialization->name)?$specialization->name:''}}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Status</label>
                            <div class="col-md-9">
                                <select class="select" name="status" id="status" >
                                    <option value="">Select status</option>
                                    <option value="1" {{ (isset($specialization->status) && $specialization->status == 1) ? 'selected':''}}>Active</option>
                                    <option value="0"  {{ (isset($specialization->status) && $specialization->status == 0) ? 'selected':''}}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 text-right">
                        <button type="submit" id="save_specialization" class="btn btn-primary">Submit</button>
                        <a href="{{ route('admin.specialization') }}" type="submit" class="btn btn-primary">Back</a>
                                
                        </div>
                       
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
