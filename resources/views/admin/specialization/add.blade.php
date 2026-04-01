@extends('admin.layout.app')

@section('content')

@if(isset($specialization->id))
    @php $form = 'Update'; @endphp
@else
    @php $form = 'Add'; @endphp
@endif

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">{{ $form }} Specialization</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <h4 class="card-title">{{ $form }} Specialization</h4>
                    <form method="POST" id="specialization_form" name="specialization_form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ isset($specialization->id) ? $specialization->id : '' }}">

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" id="name"
                                    value="{{ isset($specialization->name) ? $specialization->name : '' }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Image</label>
                            <div class="col-md-9">
                                @php
                                    $image = isset($specialization->image) && !empty($specialization->image)
                                        ? asset('uploads/specialization/'.$specialization->image) 
                                        : asset('storage/uploads/specialization/default.jpg');                    
                                @endphp
                                @if(!empty($image))`
                                    <div class="mb-2">
                                        <img src="{{ $image }}"
                                            alt="Specialization Image" style="height:70px; width:70px; object-fit:cover; border-radius:6px;">
                                        <small class="text-muted d-block mt-1">Current image. Upload new to replace.</small>
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="image" id="image" accept="image/*">
                                <small class="text-muted">JPG, PNG, WEBP — max 2MB</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Icon Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="icon_name" id="icon_name"
                                    value="{{ isset($specialization->icon_name) ? $specialization->icon_name : '' }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Status</label>
                            <div class="col-md-9">
                                <select class="select" name="status" id="status">
                                    <option value="">Select status</option>
                                    <option value="1" {{ (isset($specialization->status) && $specialization->status == 1) ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ (isset($specialization->status) && $specialization->status == 0) ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 text-right">
                            <button type="submit" id="save_specialization" class="btn btn-primary">Submit</button>
                            <a href="{{ route('admin.specialization') }}" class="btn btn-primary">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$('#specialization_form').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: '{{ route('admin.specialization.add') }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(res) {
            if (res.status == 200) {
                alert(res.msg);
                window.location.href = '{{ route('admin.specialization') }}';
            } else {
                alert(res.msg);
            }
        },
        error: function(xhr) {
            alert('Something went wrong.');
        }
    });
});
</script>

@endsection
