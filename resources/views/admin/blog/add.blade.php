@extends('admin.layout.app')
@section('content')

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">Add Blog</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <h4 class="card-title">Blog Information</h4>
                    <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Title <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" name="title" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Category <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="text" name="category" class="form-control" placeholder="e.g. Health Tips, Medical News" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Excerpt <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <textarea name="excerpt" class="form-control" rows="3" placeholder="Short description" required></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Content <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <textarea name="content" class="form-control" rows="10" required></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Image</label>
                            <div class="col-md-9">
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Status</label>
                            <div class="col-md-9">
                                <select name="status" class="select form-control">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-9 offset-md-2">
                                <button type="submit" class="btn btn-primary">Save Blog</button>
                                <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
