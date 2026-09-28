@extends('admin.layout.app')
@section('content')

<div class="page-wrapper">
    <div class="content">

        <div class="row mb-3">
            <div class="col-sm-8">
                <h4 class="page-title"><i class="fa fa-comments" style="color:#0a6ebd;margin-right:8px;"></i> Testimonials</h4>
            </div>
            <div class="col-sm-4 text-right">
                <button class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#addModal">
                    <i class="fa fa-plus"></i> Add Testimonial
                </button>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-border table-striped custom-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Rating</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($testimonials as $t)
                            <tr>
                                <td>{{ $t->id }}</td>
                                <td>
                                    @if($t->photo)
                                        <img src="{{ asset('storage/upload/testimonials/'.$t->photo) }}"
                                             style="width:45px;height:45px;border-radius:50%;object-fit:cover;border:2px solid #0a6ebd;">
                                    @else
                                        <div style="width:45px;height:45px;border-radius:50%;background:#e8f3ff;display:flex;align-items:center;justify-content:center;">
                                            <i class="fa fa-user" style="color:#0a6ebd;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><strong>{{ $t->name }}</strong></td>
                                <td>{{ $t->designation ?? '-' }}</td>
                                <td>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star" style="color:{{ $i <= $t->rating ? '#f59e0b' : '#ddd' }};font-size:12px;"></i>
                                    @endfor
                                </td>
                                <td>{{ Str::limit($t->message, 60) }}</td>
                                <td>
                                    @if($t->status)
                                        <span class="custom-badge status-green">Active</span>
                                    @else
                                        <span class="custom-badge status-red">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{ route('admin.testimonial.edit', $t->id) }}">
                                                <i class="fa fa-pencil m-r-5"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.testimonial.toggle', $t->id) }}">
                                                <i class="fa fa-toggle-on m-r-5"></i> Toggle Status
                                            </a>
                                            <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#deleteModal"
                                               data-url="{{ route('admin.testimonial.destroy', $t->id) }}">
                                                <i class="fa fa-trash-o m-r-5"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center">No testimonials found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $testimonials->links() }}</div>
            </div>
        </div>

    </div>
</div>

{{-- Add Modal --}}
<div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.testimonial.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-plus"></i> Add Testimonial</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Designation</label>
                                <input type="text" name="designation" class="form-control" placeholder="e.g. Patient, Doctor...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rating <span class="text-danger">*</span></label>
                                <select name="rating" class="form-control" required>
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Photo</label>
                                <input type="file" name="photo" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Message <span class="text-danger">*</span></label>
                                <textarea name="message" class="form-control" rows="4" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="{{ asset('admin/assets/img/sent.png') }}" alt="" width="50" height="46">
                <h3>Are you sure want to delete?</h3>
                <div class="m-t-20">
                    <button class="btn btn-white" data-dismiss="modal">Close</button>
                    <form id="deleteForm" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('[data-target="#deleteModal"]').forEach(function(el) {
    el.addEventListener('click', function() {
        document.getElementById('deleteForm').action = this.getAttribute('data-url');
    });
});
</script>
@endsection
