@extends('admin.layout.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-8 col-5">
                <h4 class="page-title">{{ $title }}</h4>
            </div>
            <div class="col-sm-4 col-7 text-right m-b-30">
                <a href="{{ route('admin.user.add') }}" class="btn btn-primary btn-rounded float-right">
                    <i class="fa fa-plus"></i> Add User
                </a>
            </div>
        </div>

        <div class="row filter-row">
            <div class="col-md-5">
                <div class="form-group form-focus">
                    <label class="focus-label">Name, Phone, Email</label>
                    <input type="text" class="form-control floating filterUser" id="search">
                    <input type="hidden" id="sortBy" value="">
                    <input type="hidden" id="orderBy" value="">
                </div>
            </div>
            <div class="col-md-3">
                <a href="javascript:void(0)" class="btn btn-success btn-block"
                   onclick="FilterReset(1,'user','user','filterUser')">Clear Filters</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name
                                    <i class="fas ajaxSorting fa-sort" data-type="user" data-sort_by="name" data-sort_order="asc"></i>
                                </th>
                                <th>Email</th>
                                <th>Membership</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Updated</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="data_listing">
                            @if(isset($result['content_html']))
                                {!! $result['content_html'] !!}
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="box-footer clearfix">
            <div id="pagination_data">
                @if(isset($result['pagination_html']))
                    {!! $result['pagination_html'] !!}
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div id="delete_user_modal" class="modal fade delete-modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <i class="fa fa-trash fa-3x text-danger mb-3"></i>
                <h3>Are you sure you want to delete this User?</h3>
                <div class="m-t-20">
                    <a href="#" class="btn btn-white" data-dismiss="modal">Cancel</a>
                    <a href="#" class="btn btn-danger" id="confirmDeleteUser">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Delete modal
    document.addEventListener('click', function(e) {
        if (e.target.closest('[data-target="#delete_user_modal"]')) {
            var url = e.target.closest('[data-target="#delete_user_modal"]').getAttribute('data-url');
            document.getElementById('confirmDeleteUser').setAttribute('href', url);
        }
    });

    // Live search filter
    var searchTimer;
    document.getElementById('search').addEventListener('keyup', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () {
            ajaxSearching(1, 'user', 'user');
        }, 400);
    });
});

function ajaxSearching(page, type, filterType) {
    var search  = document.getElementById('search') ? document.getElementById('search').value : '';
    var sortBy  = document.getElementById('sortBy') ? document.getElementById('sortBy').value : '';
    var orderBy = document.getElementById('orderBy') ? document.getElementById('orderBy').value : '';

    $.ajax({
        url: '{{ route("admin.user") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            page: page,
            search: search,
            sortBy: sortBy,
            orderBy: orderBy,
            filterType: filterType
        },
        success: function (res) {
            if (res.error === 0) {
                $('#data_listing').html(res.content_html);
                $('#pagination_data').html(res.pagination_html);
            }
        }
    });
}

function FilterReset(page, type, filterType, filterClass) {
    document.getElementById('search').value = '';
    document.getElementById('sortBy').value = '';
    document.getElementById('orderBy').value = '';
    ajaxSearching(page, type, filterType);
}
</script>
@endsection
