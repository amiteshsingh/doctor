@extends('admin.layout.app')
@section('content')

<div class="page-wrapper">
    <div class="content">

        <div class="row mb-3">
            <div class="col-sm-8">
                <h4 class="page-title"><i class="fa fa-pencil" style="color:#0a6ebd;margin-right:8px;"></i> Edit Testimonial</h4>
            </div>
            <div class="col-sm-4 text-right">
                <a href="{{ route('admin.testimonial.index') }}" class="btn btn-secondary btn-rounded">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card-box">
                    <form method="POST" action="{{ route('admin.testimonial.update', $testimonial->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ $testimonial->name }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Designation</label>
                                    <input type="text" name="designation" class="form-control" value="{{ $testimonial->designation }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Rating <span class="text-danger">*</span></label>
                                    <select name="rating" class="form-control" required>
                                        @for($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}" {{ $testimonial->rating == $i ? 'selected' : '' }}>
                                                {{ $i }} Star{{ $i > 1 ? 's' : '' }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1" {{ $testimonial->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $testimonial->status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Photo</label>
                                    @if($testimonial->photo)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/upload/testimonials/'.$testimonial->photo) }}"
                                                 style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid #0a6ebd;">
                                        </div>
                                    @endif
                                    <input type="file" name="photo" class="form-control" accept="image/*">
                                    <small class="text-muted">Leave empty to keep existing photo.</small>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Message <span class="text-danger">*</span></label>
                                    <textarea name="message" class="form-control" rows="5" required>{{ $testimonial->message }}</textarea>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
                        <a href="{{ route('admin.testimonial.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
