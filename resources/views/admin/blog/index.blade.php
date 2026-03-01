@extends('admin.layout.app')
@section('content')

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-8 col-5">
                <h4 class="page-title">Manage Blogs</h4>
            </div>
            <div class="col-sm-4 col-7 text-right m-b-30">
                <a href="{{ route('admin.blog.add') }}" class="btn btn-primary btn-rounded float-right">
                    <i class="fa fa-plus"></i> Add Blog
                </a>
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
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Visits</th>
                                <th>Date</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($blogs as $blog)
                            <tr>
                                <td>{{ $blog->id }}</td>
                                <td>{{ Str::limit($blog->title, 50) }}</td>
                                <td><span class="custom-badge status-blue">{{ $blog->category }}</span></td>
                                <td>
                                    @if($blog->status)
                                    <span class="custom-badge status-green">Active</span>
                                    @else
                                    <span class="custom-badge status-red">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $blog->visit_count ?? 0 }}</td>
                                <td>{{ $blog->created_at->format('d M Y') }}</td>
                                <td class="text-right">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="{{ route('admin.blog.edit', $blog->id) }}">
                                                <i class="fa fa-pencil m-r-5"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_blog" data-url="{{ route('admin.blog.delete', $blog->id) }}">
                                                <i class="fa fa-trash-o m-r-5"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No blogs found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="box-footer clearfix">
            <div id="pagination_data">
                {{ $blogs->links() }}
            </div>
        </div>

    </div>
</div>

<div id="delete_blog" class="modal fade delete-modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="{{ asset('admin/assets/img/sent.png') }}" alt="" width="50" height="46">
                <h3>Are you sure want to delete this Blog?</h3>
                <div class="m-t-20">
                    <a href="#" class="btn btn-white" data-dismiss="modal">Close</a>
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
document.addEventListener("DOMContentLoaded", function () {
    const deleteLinks = document.querySelectorAll('[data-toggle="modal"][data-target="#delete_blog"]');
    const deleteForm = document.getElementById("deleteForm");

    deleteLinks.forEach(link => {
        link.addEventListener("click", function () {
            const deleteUrl = this.getAttribute("data-url");
            deleteForm.setAttribute("action", deleteUrl);
        });
    });
});
</script>

@endsection
