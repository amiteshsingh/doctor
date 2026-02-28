@extends('admin.layout.app')
@section('content')

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">Edit Blog</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <h4 class="card-title">Blog Information</h4>
                    <form action="{{ route('admin.blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Title <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" name="title" class="form-control" value="{{ $blog->title }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Category <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" name="category" class="form-control" value="{{ $blog->category }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Excerpt <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <textarea name="excerpt" class="form-control" rows="3" required>{{ $blog->excerpt }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Content <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <textarea name="content" id="content" class="form-control" rows="10" required>{{ $blog->content }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Image</label>
                            <div class="col-md-9">
                                @if($blog->image)
                                <div class="mb-2">
                                    <img src="{{ asset('uploads/blog/'.$blog->image) }}" class="avatar" width="150">
                                </div>
                                @endif
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Status</label>
                            <div class="col-md-9">
                                <select name="status" class="select form-control">
                                    <option value="1" {{ $blog->status ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$blog->status ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-9 offset-md-2">
                                <button type="submit" class="btn btn-primary">Update Blog</button>
                                <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
CKEDITOR.replace('content');
</script>

@endsection
